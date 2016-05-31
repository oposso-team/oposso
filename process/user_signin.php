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
if (empty($_POST["password"])) {
	echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["empty_password"]);
	exit();
}

$email = $_POST["email"];
$pass = $_POST["password"];
$user = new User();

if ($user->signin_user($email, $pass)) {
	$_SESSION["login"] = (bool) $user->user['confirmed'];
	$_SESSION["user"] = $user->user;
	if (!empty($_POST["redirect"]))
		echo Helper::jsonResponse(TRUE, "redirect", $_POST["redirect"]);
	else
		echo Helper::jsonResponse(TRUE);
} else {
	echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["incorrect_email_password"]);
}
?>
