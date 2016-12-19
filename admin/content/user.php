<?php
/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
require_once('./../../config/mainconf.php');
require_once($PATH_classes . '/class.User.php');
require_once($PATH_classes . '/class.Subscription.php');

$User = new User();
$allUsers = $User->get_all_users(1);
?>

<!-- User -->
<? if (!empty($allUsers)) { ?>
	<table class="dataTable user table list ui-widget ui-widget-content">
		<thead>
			<tr>
				<td class="th">ID</td>
				<td class="th">Firstname</td>
				<td class="th">Lastname</td>
				<td class="th">Organization</td>
				<td class="th">Email</td>
				<td class="th"></td>
			</tr>
		</thead>
		<tbody>
		<?
		foreach ($allUsers as $user) {
			$subscription = new Subscription($user["uID"]);
			$allSub = $subscription->get_subscription();
			?>
			<tr>
				<td><?= $user["uID"] ?></td>
				<td><?= $user["firstname"] ?></td>
				<td><?= $user["lastname"] ?></td>
				<td><?= $user["organization"] ?></td>
				<td><?= $user["email"] ?></td>
				<td>
					<form class="ajax" style="display: inline" action="process/user.php" method="post">
						<input type="hidden" name="action" value="" />
						<input type="hidden" name="uID" value="<?= $user["uID"] ?>" />
						<button class="button_key confirm" type="submit" name="action" value="pass" data-target="#setpass-user-dialog" title="Are you sure you want to reset the user password #<?= $user["uID"] ?>?">Send new password</button>
						<a class="ajax button_edit" href="process/user.php?action=edit&uID=<?= $user["uID"] ?>" data-target="#edit-user-dialog" title="Edit user #<?= $user["uID"] ?>">Edit</a>
						<button class="button_delete confirm" type="submit" name="action" value="delete" data-target="#delete-user-dialog" title="Are you sure you want to delete the user #<?= $user["uID"] ?>?">Delete</button>
					</form>
				</td>
			</tr>
		<? } ?>
		</tbody>
	</table>
	<div id="delete-user-dialog" class="confirmation">
		<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>The user and all corresponding subsciptions (if any) will be flagged as deleted.<br/>Are you sure?</p>
	</div>
	<div id="setpass-user-dialog" class="confirmation">
		<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>The user password will be overwritten by a randomly generated password.<br/>Are you sure?</p>
	</div>
	<div id="edit-subscription-dialog" class="dialog-edit">

	</div>
	<div id="edit-user-dialog" class="dialog-edit large">
		
	</div>
<? } ?>
