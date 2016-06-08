<?php

require_once($PATH_classes . '/class.DBconn.php');

/**
 * customisation settings
 *
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 */
class Customize {

	private $db = NULL;
	public $error_msg = "";

	public function __construct() {
		$this->db = new DBconn();
		if (!empty($this->db->error)) {
			$this->error_msg = $this->db->error;
		}
	}

	/**
	 * 
	 * @param array $settings
	 * @return bool
	 */
	public function set_settings($settings) {
		$update = array();
		$values = array();
		foreach ($settings as $key => $value) {
			$values[$key]['key'] = $key;
			$values[$key]['value'] = $value;
			$this->db->addParams("s", $values[$key]['key']);
			$this->db->addParams("s", $values[$key]['value']);
			$update[] = "(?,?)";
		}
		$sql = "INSERT INTO customisation (title,value) VALUES " . implode(",", $update) . " ON DUPLICATE KEY UPDATE value=VALUES(value)";
		if ($this->db->query($sql) === FALSE) {
			$this->error_msg = $this->db->error;
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * 
	 * @param string $key
	 * @param string $value
	 * @return bool
	 */
	public function set_option($key, $value) {
		return $this->set_settings([$key => $value]);
	}

	/**
	 * 
	 * @param string $key
	 * @return array
	 */
	public function get_settings($key = NULL) {
		$sql = "SELECT * FROM customisation";
		if (!empty($key)) {
			$this->db->addParams("s", $key);
			$sql .= " WHERE title = ?";
		}
		if ($this->db->query($sql) === FALSE) {
			$this->error_msg = $this->db->error;
			return FALSE;
		} else {
			if (!empty($key)) {
				$return = $this->db->fetch_array_assoc();
				return $return['value'];
			} else {
				return $this->db->fetch_all_array();
			}
		}
	}
	

	/**
	 * 
	 * @param UPLOAD_ERR code $code
	 * @return string
	 */
	public static function uploadMessage($code) 
    { 
        switch ($code) { 
            case UPLOAD_ERR_INI_SIZE: 
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini"; 
                break; 
            case UPLOAD_ERR_FORM_SIZE: 
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break; 
            case UPLOAD_ERR_PARTIAL: 
                $message = "The uploaded file was only partially uploaded"; 
                break; 
            case UPLOAD_ERR_NO_FILE: 
                $message = "No file was uploaded"; 
                break; 
            case UPLOAD_ERR_NO_TMP_DIR: 
                $message = "Missing a temporary folder"; 
                break; 
            case UPLOAD_ERR_CANT_WRITE: 
                $message = "Failed to write file to disk"; 
                break; 
            case UPLOAD_ERR_EXTENSION: 
                $message = "File upload stopped by extension"; 
                break; 

            default: 
                $message = "Unknown upload error"; 
                break; 
        } 
        return $message; 
    } 

}
