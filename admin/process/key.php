<?php

/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
require_once('./../../config/mainconf.php');
require_once($PATH_classes . "/class.KeyHandler.php");

switch ($_POST["action"]) {

	case "add":
		$typeID = isset($_POST["type"]) ? (int) $_POST["type"] : 0;
		$duration = isset($_POST["duration"]) ? (int) $_POST["duration"] : 0;
		$platform = isset($_POST["platform"]) ? (string) $_POST["platform"] : "";
		$contract = isset($_POST["contract"]) ? (string) $_POST["contract"] : "";
		$number = isset($_POST["number"]) ? (int) $_POST["number"] : 0;
		$keyHandler = new KeyHandler();
		$keys = $keyHandler->add_keys($typeID, $duration, $number, $platform, $contract);
		if (!empty($keys)) {
			foreach ($keys as $key) {
				echo $key . "\n";
			}
		} else {
			echo "One or more keys could not be created:\n{$keyHandler->error_msg}";
		}
		break;

	case "export":
		$typeID = isset($_POST["tID"]) ? (int) $_POST["tID"] : 0;
		$duration = isset($_POST["duration"]) ? (int) $_POST["duration"] : 0;
		$platform = isset($_POST["platform"]) ? (string) $_POST["platform"] : "";
		$export = isset($_POST["export"]) ? (string) $_POST["export"] : 0;
		switch ($export) {
			case "all":
				$status = "";
				break;
			case "unused":
				$status = "unused";
				break;
			case "used":
				$status = "used";
				break;

			default:
				echo "Wrong value of export.";
				exit();
				break;
		}
		$keyHandler = new KeyHandler();
		$keys = $keyHandler->get_keys($typeID, $duration, $platform, $status);
		if (!empty($keys) && $keys) {
			foreach ($keys as $key) {
				echo $key . "\n";
			}
		} else {
			echo "No subscription keys found.";
		}
		break;

	case "csv":

		$keyHandler = new KeyHandler();
		$data = $keyHandler->search_key_data();
		// Open the output stream
		$fh = fopen('php://output', 'w');

		// Start output buffering (to capture stream contents)
		ob_start();

		// Loop over the * to export
		if (!empty($data)) {
			$header = array();
			foreach ($data[0] as $name => $item) {
				$header[] = $name;
			}
			fputcsv($fh, $header, ";");
			foreach ($data as $item) {
				fputcsv($fh, $item, ";");
			}
		}

		// Get the contents of the output buffer
		$output = ob_get_clean();

		$filename = 'csv_' . date('Ymd');

		// Output CSV-specific headers
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1");
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"$filename.csv\"");
		header("Content-Transfer-Encoding: binary");

		exit($output);

	default:
		break;
}
exit();
?>
