<?php

/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
require_once('./config/mainconf.php');
require_once($PATH_classes . "/class.Helper.php");
require_once($PATH_l10n . "/locallang.php");

if(!isset($_SESSION)) 
	session_start();
//session_regenerate_id(TRUE);

if (empty($_SESSION['login'])) {
	$_SESSION['ERROR'] = Helper::boxError($LOCAL["msg"]["error"]["access_denied_confirmed"]);
	header('Location: ' . $URL_root . "/account.php");
	exit;
}
?>
