<?php

/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
require_once('./../config/mainconf.php');
require_once($PATH_classes . "/class.Helper.php");
require_once($PATH_classes . '/class.User.php');
require_once($PATH_l10n . "/locallang.php");

if (empty($_SESSION['script_access'])) {
	header('Location:' . $URL_home);
	exit();
}

if (empty($_POST["email"]) || !Helper::validate_Email($_POST["email"])) {
	echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["invalid_email"]);
	exit();
}

$email = $_POST["email"];
$User = new User();
$User->map_user("", $email);
$userData = $User->get_user_data();

if (!empty($userData)) {
	$pass = PasswordGenerator::getAlphaNumericPassword(12);
	if ($User->update_user($userData["uID"], $userData["password"], $userData["email"], $userData["firstname"], $userData["lastname"], $userData["organization"], $pass)) {
		$emailText = Helper::setMarker(array("firstname", "lastname", "pass"), array($userData["firstname"], $userData["lastname"], $pass), $LOCAL["email"]["new_password"]["body"]);
		if ($User->send_mail($emailText, $LOCAL["email"]["new_password"]["subject"], $CONF["email"])) {
			echo Helper::jsonResponse(TRUE, "success", $LOCAL["msg"]["success"]["new_password"]);
		} else {
			echo Helper::jsonResponse(FALSE, "error", Helper::setMarker(array("errormsg"), array($User->error_msg), $LOCAL["msg"]["error"]["new_password_but_not_email"]));
		}
	} else {
		echo Helper::jsonResponse(FALSE, "error", Helper::setMarker(array("errormsg"), array($User->error_msg), $LOCAL["msg"]["error"]["password_not_set"]));
	}
} else {
	echo Helper::jsonResponse(TRUE, "success", $LOCAL["msg"]["success"]["new_password"]);
}
exit();
?>
