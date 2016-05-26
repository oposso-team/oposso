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
if (!empty($_POST["action"]) && $_POST["action"] == "send") { // Request confirmation email
	$user->map_user($_SESSION["user"]["uID"], $_SESSION["user"]["email"]);
	$regURL = "{$URL_root}/process/email_auth.php?uID={$user->uID}&hash={$user->user['hash']}";
	$emailText = Helper::setMarker(array("firstname", "lastname", "url"), array($user->user["firstname"], $user->user["lastname"], $regURL), $LOCAL["email"]["confirm_registration"]["body"]);
	if ($user->send_mail($emailText, $LOCAL["email"]["confirm_registration"]["subject"], $CONF["email"]))
		echo Helper::jsonResponse(TRUE, "success", Helper::setMarker(array("email"), array($user->user["email"]), $LOCAL["msg"]["success"]["user_register"]));
	else
		echo Helper::jsonResponse(FALSE, "error", $user->error_msg);
} elseif (!empty($_POST["action"]) && $_POST["action"] == "update") { // Update Userdata
	if (empty($_POST["email"]) || !Helper::validate_Email($_POST["email"])) {
		echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["invalid_email"]);
		exit();
	}
	if (!$user->check_email($_POST["email"], $_SESSION["user"]["uID"])) {
		echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["email_already_exists"]);
		exit();
	}
	if (empty($_POST["lastname"])) {
		echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["empty_lastname"]);
		exit();
	}
	if (!empty($_POST["password"]) && (empty($_POST["password_confirm"]) || $_POST["password_confirm"] != $_POST["password"])) {
		echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["invalid_password_confirm"]);
		exit();
	}
	$organization = isset($_POST["organization"]) ? $_POST["organization"] : "";
	$firstname = isset($_POST["firstname"]) ? $_POST["firstname"] : "";
	$lastname = $_POST["lastname"];
	$email = $_POST["email"];
	$password = isset($_POST["password"]) ? $_POST["password"] : "";
	unset($_POST["password"], $_POST["password_confirm"]);
	if ($user->update_user($_SESSION["user"]["uID"], $_SESSION["user"]["password"], $email, $firstname, $lastname, $organization, $password)) {
		$_SESSION["user"] = $user->user;
		echo Helper::jsonResponse(TRUE, "success", Helper::setMarker(array("email"), array($email), $LOCAL["msg"]["success"]["user_updated"]));
	} else {
		echo Helper::jsonResponse(FALSE, "error", Helper::setMarker(array("email", "errormsg"), array($email, $user->error_msg), $LOCAL["msg"]["error"]["user_update"]));
	}
} elseif (!empty($_POST["action"]) && $_POST["action"] == "notification") { // Update notification settings
	$config = array();
	if (is_array($CONF["notification"])) {
		foreach ($CONF["notification"] as $notificationType => $notificationValue) {
			$config[$notificationType] = !empty($_POST[$notificationType]);
		}
	}
	if($user->set_notification($_SESSION["user"]["uID"], $_SESSION["user"]["password"], serialize($config))) {
		$_SESSION["user"] = $user->user;
		echo Helper::jsonResponse(TRUE, "success", Helper::setMarker(array("email"), array($email), $LOCAL["msg"]["success"]["notification_set"]));
	} else {
		echo Helper::jsonResponse(FALSE, "error", Helper::setMarker(array("email", "errormsg"), array($email, $user->error_msg), $LOCAL["msg"]["error"]["notification_set"]));
	}
	
}
exit();
