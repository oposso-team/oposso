<?php
/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
require_once('./../config/mainconf.php');
require_once($PATH_classes . "/class.KeyHandler.php");

$msg_error = isset($_SESSION['ERROR']) ? $_SESSION['ERROR'] : "";
$msg_success = isset($_SESSION['SUCCESS']) ? $_SESSION['SUCCESS'] : "";
unset($_SESSION['ERROR'], $_SESSION['SUCCESS']);

//HTML
?>
<!DOCTYPE html>
<html>
	<head>
		<title>SAMBA - subscription platform</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex,nofollow" />
		<link rel="stylesheet" href="../layout/styles/jquery-ui.min.css" type="text/css" />
		<link rel="stylesheet" href="../layout/styles/jquery.dataTables.min.css" type="text/css" />
		<link rel="stylesheet" href="../layout/styles/admin.css" type="text/css" />
		<script type="text/javascript" src="../layout/scripts/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="../layout/scripts/jquery-ui.min.js"></script>
		<script type="text/javascript" src="../layout/scripts/jquery.ui.combobox.js"></script>
		<script type="text/javascript" src="../layout/scripts/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="../layout/scripts/ui-button.js"></script>
		<script type="text/javascript" src="../layout/scripts/admin.js"></script>
	</head>
	<div id="error_box"><?= $msg_error ?></div>
	<div id="success_box"><?= $msg_success ?></div>
	<div id="tabs">
		<? require_once('navigation.php'); ?>
	</div>
</html>
