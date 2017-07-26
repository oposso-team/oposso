<?php
/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
require_once('./config/mainconf.php');
require_once("./process/script_access.php");
require_once('./process/user_auth_confirmed.php');
require_once($PATH_classes . "/vendor/autoload.php");
require_once($PATH_classes . '/class.Subscription.php');
require_once($PATH_classes . "/class.Customize.php");
require_once($PATH_l10n . "/locallang.php");

$subscription = new Subscription($_SESSION["user"]["uID"]);
$allSub = $subscription->get_subscription();

$smarty = new Smarty;
		
if ($allSub === FALSE)
	$_SESSION['ERROR'] = $subscription->error_msg;
else {
	$currSub = array();
	$expSub = array();
	foreach ($allSub as $sub) {
		if (strtotime($sub["exp_time"]) > time()) {
			$currSub[] = $sub;
		} else {
			$expSub[] = $sub;
		}
	}
}

$smarty->assign("currSub", $currSub);
$smarty->assign("expSub", $expSub);

$msg_error = isset($_SESSION['ERROR']) ? $_SESSION['ERROR'] : "";
$msg_success = isset($_SESSION['SUCCESS']) ? $_SESSION['SUCCESS'] : "";
unset($_SESSION['ERROR'], $_SESSION['SUCCESS']);

$Customize = new Customize();
$productname = $Customize->get_settings('product_name');

$smarty->assign("msg_error", $msg_error);
$smarty->assign("msg_success", $msg_success);
$smarty->assign("LOCAL", $LOCAL);
$smarty->assign("product_name", $productname);
$smarty->assign("tc_required", $CONF["tc_required"]);
if (!empty($_SESSION))
	$smarty->assign("SESSION", $_SESSION);
if (!empty($_COOKIE))
	$smarty->assign("COOKIE", $_COOKIE);

$smarty->display('layout/template/subscription.tpl');