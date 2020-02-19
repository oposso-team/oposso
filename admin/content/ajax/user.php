<?php

/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2019, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
require_once('./../../../config/mainconf.php');
require_once($PATH_classes . '/class.User.php');
require_once($PATH_classes . '/class.Subscription.php');

$params = $_GET;
$search = trim($params["search"]["value"]);
$User = new User();
$allUsers = $User->search_user($search);

$output = array();
$output["draw"] = intval($params["draw"]);
$output["recordsTotal"] = count($allUsers);
$output["data"] = array();

$output["recordsFiltered"] = count($allUsers);
$allUsers = array_slice($allUsers, $params["start"], $params["length"]);

foreach ($allUsers as $user) {

	$subscription = new Subscription($user["uID"]);
	$allSub = $subscription->get_subscription();

	$form = '<form class="ajax" style="display: inline" action="process/user.php" method="post">
			<input type="hidden" name="action" value="" />
			<input type="hidden" name="uID" value="' . $user["uID"] . '" />
			<button class="button_key confirm" type="submit" name="action" value="pass" data-target="#setpass-user-dialog" title="Are you sure you want to reset the user password #' . $user["uID"] . '?">Send new password</button>
			<a class="ajax button_edit" href="process/user.php?action=edit&uID=' . $user["uID"] . '" data-target="#edit-user-dialog" title="Edit user #' . $user["uID"] . '">Edit</a>
			<button class="button_delete confirm" type="submit" name="action" value="delete" data-target="#delete-user-dialog" title="Are you sure you want to delete the user #' . $user["uID"] . '?">Delete</button>
		</form>';
	$output["data"][] = [$user["uID"], $user["firstname"], $user["lastname"], $user["organization"], $user["email"], $form];

}
//echo '<pre>' . print_r($params, TRUE) . '</pre>';
echo json_encode($output);
