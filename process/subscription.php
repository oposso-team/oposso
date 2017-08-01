<?php

/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
require_once('./../config/mainconf.php');
require_once($PATH_classes . "/class.Helper.php");
require_once($PATH_classes . '/class.KeyHandler.php');
require_once($PATH_classes . '/class.Subscription.php');
require_once($PATH_l10n . "/locallang.php");

if (empty($_SESSION['script_access']) || empty($_POST["action"]) || empty($_SESSION["user"]["uID"])) {
	header('Location:' . $URL_home);
	exit();
}

switch ($_POST["action"]) {
	case "addkeys":
		$keys = array();
		if (empty($_POST["keys"])) {
			echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["invalid_key"]);
			exit();
		}
		if ($CONF["tc_required"] && empty($_POST['conditions'])) {
			echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["empty_termsconditions"]);
			exit();
		}
		$keyHandler = new KeyHandler();
		$subscription = new Subscription($_SESSION["user"]["uID"]);
		$keys = explode("\n", $_POST["keys"]);
		$count = 0;
		foreach ($keys as $key) {
			$key = trim($key);
			if (!empty($key)) {
				$keyData = $keyHandler->search_key_data("", $key, "", "", "", KeyHandler::STATUS_UNUSED);
				if (empty($keyData) || $keyData === FALSE) {
					echo Helper::jsonResponse(FALSE, "error", Helper::setMarker(array("key"), array($key), $LOCAL["msg"]["error"]["key_not_found"]));
					exit();
				} else {
					$keyData = $keyData[0];
					$sub = $subscription->add_subscription($key, $keyData["duration"], $keyData["kID"]);
					if ($sub === FALSE || empty($sub["sID"]) || !$keyHandler->activate($keyData["kID"], $sub["sID"])) {
						echo Helper::jsonResponse(FALSE, "error", Helper::setMarker(array("key"), array($key), $LOCAL["msg"]["error"]["add_key"]));
						exit();
					}
					$count++;
				}
			}
		}
		if ($count > 0) {
			$_SESSION["SUCCESS"] = Helper::boxHighlight(Helper::setMarker(array("keys"), array($count), $LOCAL["msg"]["success"]["add_key"]));
			echo Helper::jsonResponse(TRUE, "redirect", $_SERVER["HTTP_REFERER"]);
		} else {
			echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["invalid_key"]);
		}

		break;

	case "setpass":
		$passExpression = "/^[".$CONF["pass_filter"]."]+$/";
		if (empty($_POST["target"]) || !is_numeric($_POST["target"])) {
			echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["no_subscription_id"]);
			exit();
		}
		if (empty($_POST["password"])) {
			echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["empty_password"]);
			exit();
		} elseif (!preg_match($passExpression, $_POST["password"])) {
			echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["invalid_password"]);
			exit();
		} elseif (empty($_POST["password_confirm"]) || $_POST["password_confirm"] != $_POST["password"]) {
			echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["invalid_password_confirm"]);
			exit();
		}

		$target = $_POST["target"];
		$password = $_POST["password"];
		unset($_POST["password"], $_POST["password_confirm"]);

		$subscription = new Subscription($_SESSION["user"]["uID"]);
		if ($subscription->set_pass($target, $password)) {
			$target = $target == -1 ? "all" : $target;
			$_SESSION["SUCCESS"] = Helper::boxHighlight(Helper::setMarker(array("id"), array($target), $LOCAL["msg"]["success"]["subscription_password_set"]));
			echo Helper::jsonResponse(TRUE, "redirect", $_SERVER["HTTP_REFERER"]);
		} else {
			echo Helper::jsonResponse(FALSE, "error", $subscription->error_msg);
		}
		break;

	case "edit":
		if (!is_array($_POST["description"])) {
			echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["unexpected_error"]);
			exit();
		}
		$subscription = new Subscription($_SESSION["user"]["uID"]);
		foreach ($_POST["description"] as $key => $description) {
			if (!$subscription->set_description($key, $description)) {
				echo Helper::jsonResponse(FALSE, "error", Helper::setMarker(array("id", "errormsg"), array($key, $subscription->error_msg), $LOCAL["msg"]["error"]["update_description"]));
				exit();
			}
		}
		echo Helper::jsonResponse(TRUE, "success", $LOCAL["msg"]["success"]["update_description"]);
		break;

	case "extend":
		if (empty($_POST['source'])) {
			echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["empty_subscription_list"]);
			exit();
		}
		if (empty($_POST['keys'])) {
			echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["keys_number_mismatch"]);
			exit();
		}
		$source = array();
		if (is_array($_POST['source'])) {
			$source = $_POST['source'];
		} else {
			parse_str($_POST['source'], $source);
		}
		$subscription = new Subscription($_SESSION["user"]["uID"]);
		if ($CONF["tc_required"] && empty($_POST['conditions'])) {
			echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["empty_termsconditions"]);
			exit();
		}
		$keys = explode("\n", $_POST["keys"]);
		if (count($source) != count($keys)) {
			echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["keys_number_mismatch"]);
			exit();
		}
		$keyHandler = new KeyHandler();
		$count = 0;
		foreach ($keys as $iterator => $key) {
			$key = trim($key);
			if (!empty($key)) {
				$sourceKey = $subscription->get_subscription($source[$iterator]);
				$keyData = $keyHandler->search_key_data("", $key, $sourceKey['tID'], "", "", KeyHandler::STATUS_UNUSED);
				if (empty($keyData) || $keyData === FALSE) {
					echo Helper::jsonResponse(FALSE, "error", Helper::setMarker(array("key"), array($key), $LOCAL["msg"]["error"]["add_key"]));
					exit();
				} else {
					$keyData = $keyData[0];
					if (!$subscription->add_expire($source[$iterator], $keyData["duration"]) || !$keyHandler->set_status($keyData["kID"], KeyHandler::STATUS_USED_AS_RENEWAL, $source[$iterator])) {
						echo Helper::jsonResponse(FALSE, "error", Helper::setMarker(array("key"), array($key), $LOCAL["msg"]["error"]["add_key"] . ' : ' . $subscription->error_msg));
						exit();
					}
					$count++;
				}
			}
		}
		if ($count > 0) {
			$_SESSION["SUCCESS"] = Helper::boxHighlight(Helper::setMarker(array("keys"), array($count), $LOCAL["msg"]["success"]["extend_duration"]));
			echo Helper::jsonResponse(TRUE, "redirect", $_SERVER["HTTP_REFERER"]);
		} else {
			echo Helper::jsonResponse(FALSE, "error", $LOCAL["msg"]["error"]["invalid_key"]);
		}
		break;

	case "getform":
		if (empty($_POST['source'])) {
			echo Helper::boxError($LOCAL["msg"]["error"]["empty_subscription_list"]);
			exit();
		}
		$source = array();
		if (is_array($_POST['source'])) {
			$source = $_POST['source'];
		} else {
			parse_str($_POST['source'], $source);
		}
		if (empty($source['sub'])) {
			echo Helper::boxError($LOCAL["msg"]["error"]["empty_subscription_list"]);
			exit();
		}
		$source = $source['sub'];
		$subscription = new Subscription($_SESSION["user"]["uID"]);
		$subs = Subscription::map_by_id($subscription->get_sID());
		foreach ($subs as $sub) {
			if (!in_array($sub['sID'], $source)) {
				unset($subs[$sub['sID']]);
			}
		}
		$html = "
		<form method='post' action='process/subscription.php' class='ajax' data-target='#extendsubs'>
			<fieldset>
				<input type='hidden' name='action' value='extend' />
				<div class='source-wrap'>
					<label>" . $LOCAL["subscription"]["dialog"]["extend"]["label_selected"] . "</label>
					<select id='source' name='source[]' size='" . count($subs) . "' multiple class='ui-widget-content ui-corner-all'>";
		foreach ($subs as $sub) {
			$html .= "<option value='{$sub["sID"]}' selected>#{$sub["sID"]} {$sub["username"]}</option>";
		}
		$html .= "</select>
				</div>
				<div class='keys-wrap'>
					<label for='extendkeys'>{$LOCAL["subscription"]["dialog"]["extend"]["label_keys"]}</label>
					<textarea id='extendkeys' name='keys' class='text ui-widget-content ui-corner-all' style='height: " . count($subs) * 1.25 . "em;'></textarea>
				</div>
				<!-- Allow form submission with keyboard without duplicating the dialog button -->
				<input type='submit' tabindex='-1' style='position:absolute; top:-1000px' />
			</fieldset>";
		if ($CONF["tc_required"]) {
			$html .= " 
			<fieldset>
				<input type='checkbox' name='conditions' class='inline' id='extendsubs-conditions' /><label class='inline' for='extendsubs-conditions'>{$LOCAL["subscription"]["dialog"]["extend"]["label_termsconditions"]}</label>
			</fieldset>";
		}
		$html .= "
		</form>";

		echo $html;

	default:
		break;
}
exit();
?>
