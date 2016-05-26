<?php

/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 */
require_once('./config/mainconf.php');
require_once($PATH_classes . "/class.Helper.php");
require_once($PATH_l10n . "/locallang.php");

if(!isset($_SESSION)) 
	session_start();
session_regenerate_id(TRUE);

if (!isset($_SESSION['login'])) {
	$_SESSION['ERROR'] = Helper::boxError($LOCAL["msg"]["error"]["access_denied"]);
	header('Location: ' . $URL_home . "?redirect=" . urlencode($_SERVER["REQUEST_URI"]));
	exit;
}
?>
