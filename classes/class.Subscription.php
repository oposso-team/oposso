<?php

require_once($PATH_classes . "/vendor/autoload.php");
require_once($PATH_classes . '/class.DBconn.php');
require_once($PATH_classes . '/class.PasswordGenerator.php');

/**
 * Subscription handler
 *
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
class Subscription {

	/**
	 * Error message, if any
	 * @var string
	 */
	public $error_msg = "";

	/**
	 * current User ID
	 * @var int 
	 */
	public $uID = NULL;

	/**
	 * No special status 
	 */
	const STATUS_NONE = 0;

	/**
	 * Status for subscriptions set as deleted
	 */
	const STATUS_DELETED = 1;

	private $db = NULL;
	private $logger = NULL;

	public function __construct($uID = NULL) {
		$this->uID = $uID;
		$this->db = new DBconn();
		if (php_sapi_name() != 'cli') {
			$this->logger = new Katzgrau\KLogger\Logger($GLOBALS["PATH_log"]);
			if (!empty($this->db->error)) {
				$this->exception($this->db->error);
			}
		}
	}

	public function add_subscription($user, $duration, $kID = "") {
		$this->db->addParams("i", $this->uID);
		$this->db->addParams("s", $user);
		$this->db->addParams("i", $duration);
		$sql = "INSERT INTO subscription (uID, username, exp_time) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL ? MONTH))";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		} else {
			$sql = "SELECT LAST_INSERT_ID() as sID";
			if ($this->db->query($sql) === FALSE) {
				$this->exception($this->db->error);
				return FALSE;
			} else {
				$key = empty($kID) ? $user : "#{$kID}";
				$return = $this->db->fetch_array_assoc();
				$this->sync_external_user_table($return["sID"]);
				$this->log("User #{$this->uID} create subscription #{$return["sID"]} (Key: '{$key}')");
				return $return;
			}
		}
	}

	public function get_subscription($sID = "") {
		$sql = "SELECT s.*, t.path, t.tID FROM subscription s LEFT JOIN subscription_key k ON s.sID = k.sID JOIN subscription_type t ON k.tID = t.tID";
		$where = array();
		if (!empty($this->uID)) {
			$this->db->addParams("i", $this->uID);
			$where[] = "s.uID = ?";
		}
		if (!empty($sID)) {
			$this->db->addParams("i", $sID);
			$where[] = "s.sID = ?";
		}
		$where[] = "s.status <> " . self::STATUS_DELETED;
		$sql = $sql . " WHERE " . implode(" AND ", $where) . " GROUP BY s.sID";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		} else {
			if (!empty($sID))
				return $this->db->fetch_array_assoc();
			else
				return $this->db->fetch_all_array();
		}
	}

	public function set_pass($sID, $pass) {
		$password = crypt($pass, '$2y$10$' . PasswordGenerator::getAlphaNumericPassword(22));
		$sql = "UPDATE subscription SET password = ? WHERE uID = ?";
		$this->db->addParams("s", $password);
		$this->db->addParams("i", $this->uID);
		if (!empty($sID) && $sID != -1) {
			$this->db->addParams("i", $sID);
			$sql .= " AND sID = ?";
		} else {
			$sID = "all";
		}
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		} else {
			if ($sID == "all") {
				$this->sync_external_passwords($password);
			} else {
				$this->sync_external_user_table($sID);
			}
			$this->log("User #{$this->uID} update subscription #{$sID} (Password)");
			return TRUE;
		}
	}

	public function set_description($sID, $descr) {
		$this->db->addParams("s", $descr);
		$this->db->addParams("i", $this->uID);
		$this->db->addParams("i", $sID);
		$sql = "UPDATE subscription SET description = ? WHERE uID = ? AND sID = ?";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		} else {
			$this->log("User #{$this->uID} update subscription #{$sID} (Description)");
			return TRUE;
		}
	}

	public function get_all_subscription_user_info($active = TRUE) {
		$sql = "SELECT s.*, u.firstname, u.lastname, u.organization FROM subscription s JOIN user u ON s.uID = u.uID WHERE s.status <> " . self::STATUS_DELETED;
		if ($active) {
			$sql .= " AND u.confirmed = 1";
		}
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		} else {
			return $this->db->fetch_all_array();
		}
	}

	public function get_active_subscriptions($sID = "") {
		$sql = "SELECT sID, uID, username, password, exp_time FROM subscription WHERE active = 1 AND exp_time > NOW() AND status <> " . self::STATUS_DELETED;
		if (!empty($sID)) {
			$this->db->addParams("i", $sID);
			$sql .= " AND sID = ?";
		}
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		} else {
			if (!empty($sID))
				return $this->db->fetch_array_assoc();
			else
				return $this->db->fetch_all_array();
		}
	}

	public function get_sID() {
		$this->db->addParams("i", $this->uID);
		$sql = "SELECT sID, username FROM subscription WHERE uID = ?";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		} else {
			$result = $this->db->fetch_all_array();
			if (!empty($result))
				return $result;
			else
				return FALSE;
		}
	}

	public function set_expire($sID, $date, $format = "%Y/%m/%d") {
		$this->db->addParams("s", $date);
		$this->db->addParams("s", $format);
		$this->db->addParams("i", $sID);
		$sql = "UPDATE subscription SET exp_time = STR_TO_DATE(?, ?) WHERE sID = ?";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		} else {
			$this->sync_external_user_table($sID);
			$this->log("Update subscription #{$sID} (Expire Date)");
			return TRUE;
		}
	}

	public function set_notification($sID) {
		$sIDs = is_array($sID) ? implode(",", $sID) : $sID;
		$this->db->addParams("s", $sIDs);
		$sql = "UPDATE subscription SET last_notification = NOW() WHERE sID IN (?)";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function set_status($sID, $status) {
		$this->db->addParams("i", $status);
		$this->db->addParams("i", $sID);
		$sql = "UPDATE subscription SET status = ? WHERE sID = ?";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function add_expire($sID, $duration) {
		$this->db->addParams("i", $duration);
		$this->db->addParams("i", $this->uID);
		$this->db->addParams("i", $sID);
		$sql = "UPDATE subscription SET exp_time = DATE_ADD(IF(exp_time>NOW(),exp_time,NOW()), INTERVAL ? MONTH) WHERE uID = ? AND sID = ?";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		} else {
			$this->sync_external_user_table($sID);
			$this->log("User #{$this->uID} update subscription #{$sID} (Expire Date + $duration month)");
			return TRUE;
		}
	}

	public function toggle_subscription($sID) {
		$this->db->addParams("i", $sID);
		$sql = "UPDATE subscription SET active = IF(active = 0, 1, 0) WHERE sID = ?";
		if ($this->db->query($sql) === FALSE) {
			$this->exception($this->db->error);
			return FALSE;
		} else {
			$this->sync_external_user_table($sID);
			$this->log("Update subscription #{$sID} (Toggle Subscription)");
			return TRUE;
		}
	}

	public function delete_subscription($sID) {
		if ($this->set_status($sID, self::STATUS_DELETED)) {
			$this->sync_external_user_table($sID);
			$this->log("Update subscription #{$sID} (Set as deleted)");
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function sync_external_user_table($sID) {
		if (sync_external_db) {
			$sub = $this->get_active_subscriptions($sID);
			$ext_db = new DBconn(ext_DB_host, ext_DB_user, ext_DB_pass, ext_DB_name, ext_DB_flag, ext_DB_ca);
			$ext_sub = $this->get_external_user($ext_db, $sID);
			if (!empty($sub)) {
				if (!empty($ext_sub)) {
					$ext_db->addParams("s", $sub['password']);
					$ext_db->addParams("s", $sub['exp_time']);
					$ext_db->addParams("i", $ext_sub['ID']);
					$sql = "UPDATE user_auth SET password = ?, expire_time = ? WHERE ID = ?";
					if ($ext_db->query($sql) === FALSE) {
						$this->exception($ext_db->error);
						return FALSE;
					}
				} elseif ($ext_sub !== FALSE) {
					$ext_db->addParams("i", $sub['uID']);
					$ext_db->addParams("i", $sub['sID']);
					$ext_db->addParams("s", $sub['username']);
					$ext_db->addParams("s", $sub['password']);
					$ext_db->addParams("s", $sub['exp_time']);
					$sql = "INSERT INTO user_auth (user_ID, sub_ID, username, password, expire_time) VALUES (?, ?, ?, ?, ?)";
					if ($ext_db->query($sql) === FALSE) {
						$this->exception($ext_db->error);
						return FALSE;
					}
				}
			} elseif (!empty($ext_sub)) {
				$ext_db->addParams("i", $ext_sub['ID']);
				$sql = "DELETE FROM user_auth WHERE ID = ?";
				if ($ext_db->query($sql) === FALSE) {
					$this->exception($ext_db->error);
					return FALSE;
				}
			}
		} else {
			return TRUE;
		}
	}

	public static function map_by_id($result) {
		if (!is_array($result)) {
			return $result;
		}
		$temp = array();
		foreach ($result as $value) {
			$temp[$value['sID']] = $value;
		}
		return $temp;
	}

	private function get_external_user(&$ext_db, $sID) {
		$ext_db->addParams("i", $sID);
		$sql = "SELECT ID FROM user_auth WHERE sub_ID = ?";
		if ($ext_db->query($sql) === FALSE) {
			$this->exception($ext_db->error);
			return FALSE;
		} else {
			return $ext_db->fetch_array_assoc();
		}
	}

	private function sync_external_user_table_all() {
		$subs = $this->get_active_subscriptions();
		if (!empty($subs))
			foreach ($subs as $sub) {
				$this->sync_external_user_table($sub["sID"]);
			}
	}

	private function sync_external_passwords($pass) {
		if (sync_external_db) {
			$ext_db = new DBconn(ext_DB_host, ext_DB_user, ext_DB_pass, ext_DB_name, ext_DB_flag, ext_DB_ca);
			$ext_db->addParams("s", $pass);
			$ext_db->addParams("i", $this->uID);
			$sql = "UPDATE user_auth SET password = ? WHERE user_ID = ?";
			if ($ext_db->query($sql) === FALSE) {
				$this->exception($ext_db->error);
				return FALSE;
			}
		} else {
			return TRUE;
		}
	}

	private function create_external_table(&$ext_db) {
		$sql = "CREATE TABLE IF NOT EXISTS `user_auth` (
				`ID` INT(11) NOT NULL AUTO_INCREMENT,
				`user_ID` int(11) NOT NULL,
				`sub_ID` int(11) NOT NULL,
				`username` varchar(50) NOT NULL,
				`password` varchar(50) DEFAULT NULL,
				`expire_time` timestamp NULL DEFAULT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		if ($ext_db->query($sql) === FALSE) {
			$this->exception($ext_db->error);
		}
	}

	private function log($msg) {
		$this->logger->info($msg);
	}

	private function exception($msg) {
		if (!empty($msg) && !empty($this->logger)) {
			$this->logger->error("User #{$this->uID}: {$msg}");
			$this->error_msg .= $msg . " \n";
		} elseif (php_sapi_name() == 'cli') {
			echo $msg;
		}
	}

}

?>
