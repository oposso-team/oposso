<?php

require_once($PATH_classes . "/vendor/autoload.php");
require_once($PATH_classes . '/class.DBconn.php');
require_once($PATH_classes . '/class.PasswordGenerator.php');
require_once($PATH_classes . '/PHPMailer/PHPMailerAutoload.php');

/**
 * User Management
 *
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
class User {

	/**
	 * Error message, if any
	 * @var string
	 */
	public $error_msg = "";

	/**
	 * User ID
	 * @var int 
	 */
	public $uID = -1;

	/**
	 * User data
	 * @var array
	 */
	public $user = array();
	private $db = NULL;
	private $logger = NULL;
	private $logging = TRUE;

	public function __construct($log = TRUE) {
		$this->logging = $log;
		$this->db = new DBconn();
		if (php_sapi_name() != 'cli') {
			$this->logger = new Katzgrau\KLogger\Logger($GLOBALS["PATH_log"]);
			if (!empty($this->db->error))
				$this->exception($this->db->error);
		}
	}

	public function add_user($firstname, $lastname, $organization, $email, $password, $lang = "EN") {
		$password = crypt($password, '$2a$10$' . PasswordGenerator::getAlphaNumericPassword(22));
		$hash = PasswordGenerator::getAlphaNumericPassword(50);

		$this->db->setParams("sssssss", array(&$firstname, &$lastname, &$organization, &$email, &$password, &$lang, &$hash));
		$sql = "INSERT INTO user (firstname, lastname, organization, email, password, lang, hash) VALUES (?, ?, ?, ?, ?, ?, ?)";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		}
		$this->map_user("", $email);
		$this->log("Create user #{$this->uID}");
		return TRUE;
	}

	public function update_user($uID, $currentPassword, $email, $firstname = "", $lastname = "", $organization = "", $password = "") {
		$update = "";
		if (!empty($password)) {
			$password = crypt($password, '$2a$10$' . PasswordGenerator::getAlphaNumericPassword(22));
			$this->db->addParams("s", $password);
			$update .= "password = ?, ";
		}
		$this->db->addParams("s", $firstname);
		$update .= "firstname = ?, ";
		$this->db->addParams("s", $lastname);
		$update .= "lastname = ?, ";
		$this->db->addParams("s", $organization);
		$update .= "organization = ?, ";
		$this->db->addParams("s", $email);
		$update .= "email = ? ";
		$this->db->addParams("i", $uID);
		$this->db->addParams("s", $currentPassword);
		$sql = "UPDATE user SET {$update} WHERE uID = ? AND password = ?";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		}
		$this->map_user($uID);
		$this->log("User #{$uID} updated (User Data)");
		return TRUE;
	}

	public function set_language($uID, $currentPassword, $lang) {
		if (empty($lang)){
			$this->exception("Empty language String");
			return FALSE;
		}
		$this->db->addParams("s", $lang);
		$this->db->addParams("i", $uID);
		$this->db->addParams("s", $currentPassword);
		$sql = "UPDATE user SET lang = ? WHERE uID = ? AND password = ?";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		}
		$this->map_user($uID);
		$this->log("User #{$uID} updated (Language)");
		return TRUE;
	}

	public function set_notification($uID, $currentPassword, $notification) {
		$update = "";
		$this->db->addParams("s", $notification);
		$update .= "notification = ?";
		$this->db->addParams("i", $uID);
		$this->db->addParams("s", $currentPassword);
		$sql = "UPDATE user SET {$update} WHERE uID = ? AND password = ?";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		}
		$this->map_user($uID);
		$this->log("User #{$uID} updated (Notifications)");
		return TRUE;
	}

	public function delete_user($uID, $currentPassword) {
		$this->db->addParams("i", $uID);
		$this->db->addParams("s", $currentPassword);
		$sql = "UPDATE user SET active = 0 WHERE uID = ? AND password = ?";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		}
		$this->log("User #{$uID} set as deleted");
		$this->user = array();
		return TRUE;
	}

	public function cron_delete($uID, $currentPassword) {
		$this->db->addParams("i", $uID);
		$this->db->addParams("s", $currentPassword);
		$sql = "DELETE FROM user WHERE uID = ? AND password = ?";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		}
		//$this->log("User #{$uID} deleted (Cronjob)");
		$this->user = array();
		return TRUE;
	}

	public function get_user_data() {
		if (!empty($this->user))
			return $this->user;
		else
			return FALSE;
	}

	public function get_all_users($confirmed = -1) {
		$sql = "SELECT * FROM user";
		if ($confirmed !== -1) {
			$this->db->addParams("i", $confirmed);
			$sql .= " WHERE confirmed = ? AND active = 1";
		}
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		} else {
			return $this->db->fetch_all_array();
		}
	}

	public function auth_user_email($uID, $hash) {
		$this->db->addParams("i", $uID);
		$this->db->addParams("s", $hash);
		$sql = "UPDATE user SET confirmed = 1 WHERE uID = ? AND hash = ?";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		}
		$this->map_user($uID);
		$this->log("User #{$uID} email confirmed");
		return TRUE;
	}

	public function signin_user($email, $pass) {
		if ($this->map_user("", $email)) {
			return (crypt($pass, $this->user["password"]) == $this->user["password"]);
		} else {
			return FALSE;
		}
	}

	/**
	 * 
	 * @param string $text Bodytext of sended mail
	 * @param string $subject the subject
	 * @param array $conf contains From name ($conf["From_name"]) and email ($conf["From"])
	 * @return boolean
	 */
	public function send_mail($text, $subject, $conf) {
		$email = new PHPMailer();
		$email->CharSet = 'utf-8';
		$email->From = $conf["From"];
		$email->FromName = $conf["From_name"];
		$name = empty($this->user["firstname"]) ? $this->user["lastname"] : $this->user["firstname"] . ' ' . $this->user["lastname"];
		$email->addAddress($this->user["email"], $name);
		$email->addReplyTo($conf["From"], $conf["From_name"]);
		$email->Subject = $subject;
		$email->Body = $text;
		if (!$email->send()) {
			$this->exception("Message could not be sent.");
			$this->exception("Mailer Error: " . $email->ErrorInfo);
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function check_email($email, $uID = "") {
		$this->db->addParams("s", $email);
		$sql = "SELECT count(*) as number FROM user WHERE email = ?";
		if (!empty($uID)) {
			$this->db->addParams("i", $uID);
			$sql .= " AND uID <> ?";
		}
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		} else {
			$result = $this->db->fetch_array_assoc();
			if ($result["number"] > 0) {
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	/**
	 * 
	 * @param int $uID
	 * @param string $email
	 * @param boolean $confirmed
	 * @return boolean
	 */
	public function map_user($uID = "", $email = "", $confirmed = FALSE) {
		$sql = "SELECT * FROM user";
		$col = array();
		if (!empty($uID)) {
			$this->db->addParams("i", $uID);
			$col[] = "uID = ?";
		}
		if (!empty($email)) {
			$this->db->addParams("s", $email);
			$col[] = "email = ?";
		}
		if ($confirmed) {
			$col[] = "confirmed = 1";
		}
		$col[] = "active = 1";
		$sql = $sql . " WHERE " . implode(" AND ", $col);
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		} else {
			$this->user = $this->db->fetch_array_assoc();
			if (!empty($this->user)) {
				$this->user["notification"] = is_string($this->user["notification"]) ? unserialize($this->user["notification"]) : $this->user["notification"];
				$this->uID = $this->user["uID"];
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}

	private function log($msg) {
		if ($this->logging)
			$this->logger->info($msg);
	}

	private function exception($msg) {
		if (!empty($msg)) {
			$this->logger->error("User #{$this->uID}: {$msg}");
			$this->error_msg .= $msg . " \n";
		}
	}

}
