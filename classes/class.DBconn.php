<?php

/**
 * connects to database of subscription platform
 *
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
class DBconn {

	private $db, $result, $stmt;
	private $params = array();
	private $types = "";
	public $error;

	public function __construct($db_host = null, $db_user = null, $bd_pass = null, $db_name = null, $flag = null, $ca = null) {
		$db_host = empty($db_host) ? DB_host : $db_host;
		$db_user = empty($db_user) ? DB_user : $db_user;
		$bd_pass = empty($bd_pass) ? DB_pass : $bd_pass;
		$db_name = empty($db_name) ? DB_name : $db_name;
		$this->db = new mysqli(); 
		if (!empty($ca)) {
			$this->db->ssl_set(NULL, NULL, $ca, NULL, NULL);
		}
		if (!$this->db->real_connect($db_host, $db_user, $bd_pass, $db_name, 3306, null, $flag)) {
			$this->exception($this->db->connect_error);
			exit();
		}
		if (!$this->db->set_charset("utf8")) {
			$this->exception($this->db->connect_error);
			exit();
		}
	}

	public function closeStmt() {
		$this->stmt->close();
	}

	public function addParams($type, &$param) {
		$this->types .= $type;
		$this->params[] = &$param;
	}

	public function setParams($types = "", $params = array()) {
		$this->types = $types;
		$this->params = $params;
	}

	public function flushParams() {
		$this->setParams();
	}

	public function commit() {
		$this->db->query("COMMIT");
	}

	public function query($sql) {
		$this->stmt = $this->db->stmt_init();
		if ($this->stmt->prepare($sql)) {
			if (!empty($this->types)) {
				call_user_func_array(array($this->stmt, 'bind_param'), array_merge(array($this->types), $this->params));
			}
			return $this->finalExecute();
		}
		$this->exception($this->stmt->error);
		return FALSE;
	}

	public function execute() {
		$execute = $this->stmt->execute();
		if (!$execute)
			$this->exception($this->stmt->error);
		return $execute;
	}

	public function finalExecute() {
		$execute = $this->execute();
		$this->flushParams();
		return $execute;
	}

	public function multiQuery($sql) {
		$this->stmt = $this->db->stmt_init();
		if ($this->stmt->prepare($sql)) {
			if (!empty($this->types)) {
				call_user_func_array(array($this->stmt, 'bind_param'), array_merge(array($this->types), $this->params));
			}
			$this->db->query("START TRANSACTION");
			return TRUE;
		}
		$this->exception($this->stmt->error);
		return FALSE;
	}

	public function fetch_array() {
		if ($this->result = $this->stmt->get_result()) {
			$this->closeStmt();
			return $this->result->fetch_array(MYSQLI_NUM);
		} else {
			$this->closeStmt();
			$this->exception($this->stmt->error);
			return FALSE;
		}
	}

	public function fetch_array_assoc() {
		if ($this->result = $this->stmt->get_result()) {
			$this->closeStmt();
			return $this->result->fetch_array(MYSQLI_ASSOC);
		} else {
			$this->exception($this->stmt->error);
			$this->closeStmt();
			return FALSE;
		}
	}

	public function fetch_all_array($assoc = TRUE) {
		if ($this->result = $this->stmt->get_result()) {
			$data = array();
			if ($assoc) {
				while ($row = $this->result->fetch_array(MYSQLI_ASSOC)) {
					$data[] = $row;
				}
			} else {
				while ($row = $this->result->fetch_array(MYSQLI_NUM)) {
					$data[] = $row;
				}
			}
			$this->closeStmt();
			return $data;
		} else {
			$this->exception($this->stmt->error);
			$this->closeStmt();
			return FALSE;
		}
	}

	private function exception($error) {
		$this->error .= "MYSQL ERROR: " . $error . "\n";
	}

}

?>
