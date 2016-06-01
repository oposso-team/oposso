<?php

/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
require_once('./../../config/mainconf.php');
require_once($PATH_classes . "/class.Helper.php");
require_once($PATH_classes . '/class.User.php');
require_once($PATH_classes . "/class.KeyHandler.php");
require_once($PATH_classes . "/class.Subscription.php");

$uID = isset($_REQUEST["uID"]) ? (int) $_REQUEST["uID"] : "";
if (empty($uID)) {
	echo Helper::boxError("Invalid user ID given.");
	exit();
}

if (!empty($_REQUEST["action"])) {
	$User = new User();
	if ($User->map_user($uID)) {
		$userData = $User->get_user_data();
	} else {
		echo Helper::boxError("User #{$sub["uID"]} not found.");
		exit();
	}

	switch ($_REQUEST["action"]) {

		case "save":
			if (empty($_POST["lastname"])) {
				echo Helper::jsonResponse(FALSE, "error", "Lastname could not be empty");
				exit();
			}
			$organization = isset($_POST["organization"]) ? $_POST["organization"] : "";
			$firstname = isset($_POST["firstname"]) ? $_POST["firstname"] : "";
			$lastname = $_POST["lastname"];
			$email = $_POST["email"];
			$error = FALSE;

			if (!$User->update_user($uID, $userData["password"], $email, $firstname, $lastname, $organization))
				$error = TRUE;

			$expire = isset($_POST["expire"]) ? $_POST["expire"] : "";
			$subscription = new Subscription($uID);
			foreach ($expire as $id => $date) {
				$sub = $subscription->get_subscription($id);
				if (!empty($date) && date("Y/m/d", strtotime($sub["exp_time"])) != date("Y/m/d", strtotime($date))) {
					if (!$subscription->set_expire($id, $date))
						$error = TRUE;
				}
			}

			if ($error) {
				echo Helper::boxError("<b>User #{$uID} could not be updated:</b><br/>" . $User->error_msg);
			} else {
				echo Helper::boxHighlight("User #{$uID} successfully updated.");
				$userData = $User->get_user_data();
			}

		case "edit":
			$subscription = new Subscription($uID);
			$allSub = $subscription->get_subscription();
			$html = "
				<form class='ajax form-edit' action='process/user.php' method='post' data-target='#edit-user-dialog'>
					<div>
						<input type='hidden' name='action' value='save' />
						<input type='hidden' name='uID' value='{$uID}' />
						<table class='dialog-table'>
							<tr>
								<td><label>Firstname:</label></td>
								<td><input placeholder='Firstname' title='Firstname' class='text large' type='text' name='firstname' value='{$userData["firstname"]}' /></td>
							</tr>
							<tr>
								<td><label>Lastname:</label></td>
								<td><input placeholder='Lastname' class='text large' type='text' name='lastname' value='{$userData["lastname"]}' /></td>
							</tr>
							<tr>
								<td><label>Organization:</label></td>
								<td><input placeholder='Organization' class='text large' type='text' name='organization' value='{$userData["organization"]}' /></td>
							</tr>
							<tr>
								<td><label>Email:</label></td>
								<td><input placeholder='Email' class='text large' type='text' name='email' value='{$userData["email"]}' /></td>
							</tr>
						</table>
					</div>
					<div>
						<table class='dataTable user table list ui-widget ui-widget-content'>
							<thead>
								<tr>
									<td class='th'>ID</td>
									<td class='th'>Key</td>
									<td class='th'>Create Date</td>
									<td class='th'>Expire Date</td>
									<td class='th'></td>
								</tr>
							</thead>
							<tbody>";
			foreach ($allSub as $sub) {
				$expireClass = (strtotime($sub['exp_time']) > time()) ? '' : 'expired';
				$html .= "
								<tr>
									<td>{$sub['sID']}</td>
									<td class='key'>{$sub['username']}</td>
									<td>" . date('Y/m/d', strtotime($sub['create_time'])) . "</td>
									<td class=''><input type='text' class='datepicker text {$expireClass}' size='10' name='expire[{$sub['sID']}]' value=" . date("Y/m/d", strtotime($sub["exp_time"])) . " /></td>";
				if (empty($sub['password'])) {
					$html .= "<td class='td tooltip' title='Password not set yet'><span class='ui-icon ui-icon-alert' style='margin-right: .3em;'>Password not set yet</span></td>";
				} else {
					$html .= "<td></td>";
				}
				$html .= "</tr>";
			}
			$html .= "
							</tbody>
						</table>
					</div>
				</form>
			";
			echo $html;
			break;

		case "pass":
			$pass = PasswordGenerator::getAlphaNumericPassword(12);
			if ($User->update_user($uID, $userData["password"], $userData["email"], $userData["firstname"], $userData["lastname"], $userData["organization"], $pass)) {
				if ($User->send_mail("Dear {$userData["firstname"]} {$userData["lastname"]},\n\nWe have assigned you a new password:\n\n{$pass}", "New Password", $CONF["email"])) {
					echo Helper::boxHighlight("A new password has been sent to user #{$uID}.");
				} else {
					echo Helper::boxError("<b>Successfully set a new passwort to user #{$uID} but could not sent it via email:</b><br/>" . $User->error_msg);
				}
			} else {
				echo Helper::boxError("<b>Could not create a new passwort:</b><br/>" . $User->error_msg);
			}
			break;

		case "delete":
			$Subscription = new Subscription($uID);
			$subs = $Subscription->get_sID();
			if ($User->delete_user($uID, $userData["password"])) {
				if ($subs) {
					$keyHandler = new KeyHandler();
					$error = FALSE;
					foreach ($subs as $sub) {
						if ($Subscription->delete_subscription($sub["sID"])) {
							$deletedKeys = $keyHandler->search_key_data("", "", "", $sub["sID"]);
							foreach ($deletedKeys as $key) {
								if (!$keyHandler->set_status($key["kID"], KeyHandler::STATUS_DELETED))
									$error = TRUE;
							}
						} else {
							echo Helper::boxError("<b>Subscription #{$sub["sID"]} could not be deleted:</b><br/>" . $subscription->error_msg);
						}
					}
					if ($error) {
						echo Helper::boxError("<b>One or more effected keys could not be deleted:</b><br/>" . $keyHandler->error_msg);
						exit();
					}
				}
				echo Helper::boxHighlight("User #{$uID} successfully deleted.");
			} else {
				echo Helper::boxError("<b>User #{$uID} could not be deleted:</b><br/>" . $subscription->error_msg);
			}
			break;


			break;

		default:
			break;
	}
}

//	echo Helper::boxError("<re>" . print_r($_POST, TRUE) . "</pre>");

exit();
?>
