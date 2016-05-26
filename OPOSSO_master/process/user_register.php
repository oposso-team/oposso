<?php

/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 */
require_once('./../config/mainconf.php');
require_once($PATH_classes . "/class.Helper.php");
require_once($PATH_classes . '/class.User.php');
require_once($PATH_l10n . "/locallang.php");

if (empty($_SESSION['script_access']) || empty($_POST["action"])) {
	header('Location:' . $URL_home);
	exit();
}

$user = new User();
if (empty($_POST["email"]) || !Helper::validate_Email($_POST["email"])) {
	echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["invalid_email"]);
	exit();
}
if (!$user->check_email($_POST["email"])) {
	echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["email_already_exists"]);
	exit();
}
if (empty($_POST["lastname"])) {
	echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["empty_lastname"]);
	exit();
}
if (empty($_POST["password"])) {
	echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["empty_password"]);
	exit();
} elseif (empty($_POST["password_confirm"]) || $_POST["password_confirm"] != $_POST["password"]) {
	echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["invalid_password_confirm"]);
	exit();
}

$organization = isset($_POST["organization"]) ? $_POST["organization"] : "";
$firstname = isset($_POST["firstname"]) ? $_POST["firstname"] : "";
$lastname = $_POST["lastname"];
$email = $_POST["email"];
$password = $_POST["password"];
unset($_POST["password"], $_POST["password_confirm"]);


if ((!empty($_COOKIE['ln']) && $user->add_user($firstname, $lastname, $organization, $email, $password, $_COOKIE['ln'])) || $user->add_user($firstname, $lastname, $organization, $email, $password)) {
	$_SESSION["login"] = (bool) $user->user['confirmed'];
	$_SESSION["user"] = $user->user;
	$regURL = "{$URL_root}/process/email_auth.php?uID={$user->uID}&hash={$user->user['hash']}";
	$emailText = Helper::setMarker(array("firstname", "lastname", "url"), array($firstname, $lastname, $regURL), $LOCAL["email"]["confirm_registration"]["body"]);
	if ($user->send_mail($emailText, $LOCAL["email"]["confirm_registration"]["subject"], $CONF["email"])) {
		$_SESSION['SUCCESS'] = Helper::boxHighlight(Helper::setMarker(array("email"), array($email), $LOCAL["msg"]["success"]["user_register"]));
		echo Helper::jsonResponse(TRUE, "redirect", $URL_root . "/account.php");
	} else {
		echo Helper::jsonResponse(FALSE, "error", $user->error_msg);
	}
} else {
	echo Helper::jsonResponse(FALSE, "error", Helper::setMarker(array("email", "errormsg"), array($email, $user->error_msg), $LOCAL["msg"]["error"]["user_register"]));
}

exit();
?>