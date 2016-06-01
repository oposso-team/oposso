<?php

/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
require_once('./../../config/mainconf.php');
require_once($PATH_classes . "/class.KeyHandler.php");
require_once($PATH_classes . "/class.Subscription.php");
require_once($PATH_classes . "/class.User.php");
require_once($PATH_classes . "/class.Helper.php");

$sID = isset($_REQUEST["rel"]) ? (int) $_REQUEST["rel"] : "";
if (empty($sID)) {
	echo Helper::boxError("Invalid subscription ID given.");
	exit();
}

$subscription = new Subscription();
$sub = $subscription->get_subscription($sID);
$user = new User();
$key;
$user;
$org;
$email;
$crDate;
$expDate;
$pass;

switch ($_POST["action"]) {

	case "edit":
		$expire = isset($_POST["expire"]) ? (string) $_POST["expire"] : "";
		if (empty($expire)) {
			echo Helper::boxError("Invalid expire date given.");
		}
		if (date("Y/m/d", strtotime($sub["exp_time"])) != date("Y/m/d", strtotime($expire))) {
			if ($subscription->set_expire($sID, $expire)) {
				echo Helper::boxHighlight("Subscription #{$sID} successfully updated.");
			} else {
				echo Helper::boxError("<b>Subscription #{$sID} could not be updated:</b><br/>" . $subscription->error_msg);
			}
		}

		$active = isset($_POST["active"]) ? (int) $_POST["active"] : 0;
		if ($active != $sub["active"]) {
			if ($subscription->toggle_subscription($sID)) {
				echo Helper::boxHighlight("Subscription #{$sID} successfully updated.");
			} else {
				echo Helper::boxError("<b>Subscription #{$sID} could not be updated:</b><br/>" . $subscription->error_msg);
			}
		}
		break;

	case "delete":
		if ($subscription->delete_subscription($sID)) {
			$keyHandler = new KeyHandler();
			$deletedKeys = $keyHandler->search_key_data("", "", "", $sID);
			$error = FALSE;
			foreach ($deletedKeys as $key) {
				if (!$keyHandler->set_status($key["kID"], KeyHandler::STATUS_DELETED))
					$error = TRUE;
			}
			if ($error) {
				echo Helper::boxError("<b>One or more effected keys could not be deleted:</b><br/>" . $keyHandler->error_msg);
			} else {
				echo Helper::boxHighlight("Subscription #{$sID} successfully deleted.");
			}
		} else {
			echo Helper::boxError("<b>Subscription #{$sID} could not be deleted:</b><br/>" . $subscription->error_msg);
		}
		break;


		break;

	default:
		break;
}

$sub = $subscription->get_subscription($sID);
if ($user->map_user($sub["uID"]))
	$userData = $user->get_user_data();
else {
	echo Helper::boxError("User #{$sub["uID"]} not found.");
	exit();
}
$html = "<div>
			<form class='ajax form-edit' action='process/subscription.php' method='post' data-target='#edit-subscription-dialog'>
				<input type='hidden' name='action' value='edit' />
				<input type='hidden' name='rel' value='{$sID}' />
				<table class='dialog-table'>
					<tr>
						<td><label for='active'>Active:</label></td>
						<td><input type='checkbox' id='active' name='active' value='1' " . ($sub['active'] ? 'checked' : '') . " /></td>
					</tr>
					<tr>
						<td><label>Key/Username:</label></td>
						<td>{$sub["username"]}</td>
					</tr>
					<tr>
						<td><label>User:</label></td>
						<td>{$userData["firstname"]} {$userData["lastname"]}</td>
					</tr>
					<tr>
						<td><label>Organization:</label></td>
						<td>{$userData["organization"]}</td>
					</tr>
					<tr>
						<td><label>Email:</label></td>
						<td><a href='mailto:{$userData["email"]}'>{$userData["email"]}</a></td>
					</tr>
					<tr>
						<td><label>Create Date:</label></td>
						<td>{$sub["create_time"]}</td>
					</tr>
					<tr>
						<td><label>Expire Date:</label></td>
						<td><input type='text' class='datepicker text' size='10' name='expire' value=" . date("Y/m/d", strtotime($sub["exp_time"])) . " /></td>
					</tr>
				</table>
			</form>
		</div>
	";
echo $html;

//	echo Helper::boxError("<re>" . print_r($_POST, TRUE) . "</pre>");

exit();
?>
