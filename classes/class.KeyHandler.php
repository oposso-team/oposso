<?php

require_once($PATH_classes . '/class.DBconn.php');
require_once($PATH_classes . '/class.PasswordGenerator.php');

/**
 * Key, type and CSPRN (cryptographically secure pseudo-random number) handler
 *
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
class KeyHandler {

	/**
	 * Error message, if any
	 * @var string
	 */
	public $error_msg = "";

	/**
	 * Status for unused keys
	 */
	const STATUS_UNUSED = 0;

	/**
	 * Status for used keys
	 */
	const STATUS_USED = 1;

	/**
	 * Status for used keys as username
	 */
	const STATUS_USED_AS_USER = 2;

	/**
	 * Status for used keys in deleted subscriptions
	 */
	const STATUS_DELETED = 3;

	/**
	 * Status for used keys as renewal
	 */
	const STATUS_USED_AS_RENEWAL = 4;

	private $db = NULL;

	public function __construct() {
		$this->db = new DBconn();
		$this->exception($this->db->error);
	}

	public function add_type($short, $path = '', $description = '') {
		if (!empty($short) && strlen($short) <= 30) {
			$this->db->setParams("sss", array(&$short, &$path, &$description));
			$sql = "INSERT INTO subscription_type (short, path, description) VALUES (?, ?, ?)";
			if ($this->db->query($sql) === FALSE) {
				$this->exception($this->db->error);
				return FALSE;
			}
			return TRUE;
		}
		$this->exception("Short name of subscription type must not be empty!");
		return FALSE;
	}

	public function get_type($typeID = NULL) {
		if ($typeID) {
			$this->db->addParams("i", $typeID);
			$sql = "SELECT * FROM subscription_type WHERE tID = ? ORDER BY short";
		} else {
			$sql = "SELECT * FROM subscription_type ORDER BY short";
		}
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		} else {
			if ($typeID)
				return $this->db->fetch_array_assoc();
			else
				return $this->db->fetch_all_array();
		}
	}

	public function get_type_by_key($key) {
		$csprn = $this->csprn_from_key($key);
		if (!empty($csprn)) {
			$this->db->addParams("s", $csprn);
			$sql = "SELECT * FROM subscription_type t JOIN subscription_key k ON t.tID = k.tID WHERE k.CSPRN = ?";
			if ($this->db->query($sql) === FALSE) {
				$this->exception($this->db->error);
				return FALSE;
			} else {
				return $this->db->fetch_array_assoc();
			}
		}
		$this->exception("Invalid Key given!");
		return FALSE;
	}

	public function get_mapped_types() {
		$types = $this->get_type();
		$map = array();
		foreach ($types as $value) {
			$map[$value["tID"]] = $value;
		}
		return $map;
	}

	public function update_type($typeID, $path = '', $description = '') {
		if (!empty($typeID) && is_numeric($typeID)) {
			$this->db->setParams("ssi", array(&$path, &$description, &$typeID));
			$sql = "UPDATE subscription_type SET path = ?, description = ? WHERE tID = ?";
			if ($this->db->query($sql) === FALSE) {
				$this->exception($this->db->error);
				return FALSE;
			}
			return $this->get_type($typeID);
		}
		$this->exception("No type selected!");
		return FALSE;
	}

	public function delete_type($typeID) {
		if (!empty($typeID) && is_numeric($typeID)) {
			$deletedType = $this->get_type($typeID);
			$this->db->addParams("i", $typeID);
			$sql = "DELETE FROM subscription_type WHERE tID = ?";
			if ($this->db->query($sql) === FALSE) {
				$this->exception($this->db->error);
				return FALSE;
			}
			return $deletedType;
		}
		$this->exception("No type selected!");
		return FALSE;
	}

	public function add_keys($typeID, $duration, $number, $platform = "", $contract = "") {
		if (empty($typeID) || $typeID == 0)
			$this->exception("No type selected!");
		if (empty($duration) || $duration == 0)
			$this->exception("No duration selected!");
		if (empty($number) || $number == 0)
			$this->exception("No number selected!");
		if (!empty($this->error_msg))
			return FALSE;

		$success = 0;
		$allCSPRN = $this->get_all_csprn();
		$type = $this->get_type($typeID);
		$type = $type["short"];
		$CSPRN = NULL;
		$returnArray = array();
		$this->db->setParams("iisss", array(&$typeID, &$duration, &$CSPRN, &$platform, &$contract));
		$sql = "INSERT INTO subscription_key (tID, duration, CSPRN, platform, contract) VALUES (?, ?, ?, ?, ?)";
		$this->db->multiQuery($sql);
		for ($i = 0; $i < $number; $i++) {
			$CSPRN = PasswordGenerator::getAlphaNumericPassword(32);
			while (in_array($CSPRN, $allCSPRN)) {
				$CSPRN = PasswordGenerator::getAlphaNumericPassword(32);
			}
			if ($this->db->execute()) {
				array_push($allCSPRN, $CSPRN);
				$success++;
				$returnArray[] = $this->keyFormat($type, $duration, $CSPRN);
			}
		}
		$this->db->commit();
		if ($success != $number) {
			$this->exception("Only {$success} keys could be created: {$this->db->error}");
			return FALSE;
		}
		return $returnArray;
	}

	public function get_keys($tID = "", $duration = "", $platform = "", $status = "") {
		$keys = array();
		$keyData = $this->search_key_data("", "", $tID, "", $duration, $status, $platform);
		$allTypes = $this->get_mapped_types();
		foreach ($keyData as $value) {
			$keys[] = $this->keyFormat($allTypes[$value["tID"]]["short"], $value["duration"], $value["CSPRN"]);
		}
		return $keys;
	}

	public function get_all_csprn() {
		$sql = "SELECT CSPRN FROM subscription_key";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		} else {
			$result = $this->db->fetch_all_array();
			$return = array();
			foreach ($result as $value) {
				$return[] = $value["CSPRN"];
			}
			return $return;
		}
	}

	public function get_key_summary() {
		$sql = "SELECT tID, duration, platform, contract, count(CSPRN) as total, SUM(IF(status > " . self::STATUS_UNUSED . ",1,0)) as used FROM subscription_key GROUP BY tID, duration, platform, contract";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		} else {
			return $this->db->fetch_all_array();
		}
	}

	public function search_key_data($kID = "", $key = "", $tID = "", $sID = "", $duration = "", $status = "", $platform = "", $contract = "") {
		$col = array();
		$where = "";
		if (!empty($kID)) {
			$col[] = "kID = ?";
			$this->db->addParams("i", $kID);
		}
		if (!empty($key)) {
			$CSPRN = $this->csprn_from_key($key);
			$this->db->addParams("s", $CSPRN);
			$col[] = "CSPRN = ?";
		}
		if (!empty($tID)) {
			$col[] = "tID = ?";
			$this->db->addParams("i", $tID);
		}
		if (!empty($sID)) {
			$col[] = "sID = ?";
			$this->db->addParams("i", $sID);
		}
		if (!empty($duration)) {
			$col[] = "duration = ?";
			$this->db->addParams("i", $duration);
		}
		if (!empty($status) && $status == "used") {
			$col[] = "status > " . self::STATUS_UNUSED;
		} elseif (!empty($status) && $status == "unused") {
			$col[] = "status = " . self::STATUS_UNUSED;
		} elseif (!empty($status) || $status === self::STATUS_UNUSED) {
			$col[] = "status = ?";
			$this->db->addParams("i", $status);
		}
		if (!empty($platform)) {
			$col[] = "platform = ?";
			$this->db->addParams("s", $platform);
		}
		if (!empty($contract)) {
			$col[] = "contract = ?";
			$this->db->addParams("s", $contract);
		}
		if (!empty($col))
			$where = " WHERE " . implode(" AND ", $col);
		$sql = "SELECT * FROM subscription_key {$where}";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		} else {
			return $this->db->fetch_all_array();
		}
	}

	public function set_status($kID, $status = "", $sID = "") {
		$status = empty($status) ? self::STATUS_USED : $status;
		$this->db->addParams("i", $status);
		$col = "";
		if (!empty($sID)) {
			$this->db->addParams("i", $sID);
			$col = ", sID = ?, act_time = NOW()";
		}
		$this->db->addParams("i", $kID);
		$sql = "UPDATE subscription_key SET status = ?{$col} WHERE kID = ?";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		}
		return TRUE;
	}

	public function activate($kID, $sID) {
		return $this->set_status($kID, self::STATUS_USED_AS_USER, $sID);
	}

	private function csprn_from_key($key) {
		return substr($key, strrpos($key, "-") + 1);
	}

	private function keyFormat($type, $duration, $CSPRN) {
		return "{$type}{$duration}-{$CSPRN}";
	}

	private function exception($msg) {
		if (!empty($msg))
			$this->error_msg .= $msg . " \n";
	}

}

?>
