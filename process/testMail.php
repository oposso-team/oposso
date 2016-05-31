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

$user = new User();
$user->map_user(14);

$emailText =  $LOCAL["email"]["confirm_registration"]["body"];

if ($user->send_mail($emailText, $LOCAL["email"]["confirm_registration"]["subject"], $CONF["email"]))
	echo $LOCAL["msg"]["success"]["user_register"];
else
	echo $user->error_msg;

exit();
?>