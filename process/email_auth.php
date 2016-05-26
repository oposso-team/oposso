<?php

/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 */
require_once('./../config/mainconf.php');
require_once($PATH_classes . "/class.Helper.php");
require_once($PATH_classes . '/class.User.php');
require_once($PATH_l10n . "/locallang.php");

if (empty($_GET["hash"]) || empty($_GET["uID"])) {
	header('Location: ' . $URL_home);
	exit();
}

$uID = $_GET["uID"];
$hash = $_GET["hash"];
$user = new User();

if ($user->auth_user_email($uID, $hash)) {
	$_SESSION["login"] = (bool) $user->user['confirmed'];
	$_SESSION["user"] = $user->user;
	$_SESSION['SUCCESS'] = Helper::boxHighlight($LOCAL["msg"]["success"]["email_auth"]);
} else {
	$_SESSION['ERROR'] = Helper::boxError($LOCAL["msg"]["error"]["email_auth"]);
}

header('Location: ' . $URL_home);
exit();
?>
