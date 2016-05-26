<?php

/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 */
require_once('./../../config/mainconf.php');
require_once($PATH_classes . "/class.KeyHandler.php");
require_once($PATH_classes . "/class.Helper.php");

if (!empty($_POST["action"])) {
	switch ($_POST["action"]) {

		case "add":
			$short = isset($_POST["short"]) ? $_POST["short"] : "";
			$path = isset($_POST["path"]) ? $_POST["path"] : "";
			$description = isset($_POST["description"]) ? $_POST["description"] : "";
			$keyHandler = new KeyHandler();
			if ($keyHandler->add_type($short, $path, $description))
				echo Helper::boxHighlight("The type '{$short}' has been successfully added.");
			else
				echo Helper::boxError("<b>The type '{$short}' could not be added:</b><br/>{$keyHandler->error_msg}");
			break;

		case "update":
			$tID = isset($_POST["tID"]) ? $_POST["tID"] : "";
			$path = isset($_POST["path"]) ? $_POST["path"] : "";
			$description = isset($_POST["description"]) ? $_POST["description"] : "";
			$keyHandler = new KeyHandler();
			if ($type = $keyHandler->update_type($tID, $path, $description))
				echo Helper::boxHighlight("The type '{$type["short"]}' has been successfully updated.");
			else
				echo Helper::boxError("<b>The type could not be updated:</b><br/>{$keyHandler->error_msg}");
			break;

		case "delete":
			$tID = isset($_POST["tID"]) ? $_POST["tID"] : "";
			$keyHandler = new KeyHandler();
			if ($type = $keyHandler->delete_type($tID))
				echo Helper::boxHighlight("The type '{$type["short"]}' has been successfully deleted.");
			else
				echo Helper::boxError("<b>The type could not be deleted:</b><br/>{$keyHandler->error_msg}");
			break;

		default:
			break;
	}
} else {
	echo Helper::boxError("No action type given.");
}
exit();
?>
