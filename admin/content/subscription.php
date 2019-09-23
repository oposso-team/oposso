<?php
/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
require_once('./../../config/mainconf.php');
require_once($PATH_classes . '/class.Subscription.php');

$subscription = new Subscription();
$allSub = $subscription->get_all_subscription_user_info(FALSE);
if (is_array($allSub)) {
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
?>

<!-- Subscription Overview -->
<div id="subscription">
	<div class="accordion">
		<?php if (!empty($currSub)) { ?>
			<h3>Current subscriptions</h3> 
			<div>
				<table class="dataTable subscription table list ui-widget ui-widget-content">
					<thead>
						<tr>
							<td class="th">ID</td>
							<td class="th">Key</td>
							<td class="th">User</td>
							<td class="th">Organization</td>
							<td class="th">Create Date</td>
							<td class="th">Expire Date</td>
							<td class="th">Active</td>
							<td class="th"></td>
							<td class="th"></td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($currSub as $sub) { ?>
							<tr>
								<td><?= $sub["sID"] ?></td>
								<td class="key"><?= $sub["username"] ?></td>
								<td><?= implode(" ", array($sub["firstname"], $sub["lastname"])) ?></td>
								<td><?= $sub["organization"] ?></td>
								<td><?= date("Y/m/d", strtotime($sub["create_time"])) ?></td>
								<td><?= date("Y/m/d", strtotime($sub["exp_time"])) ?></td>
								<?php if ($sub["active"] === 1) { ?>
									<td class="td tooltip highlight-green" title="Active"><span class='ui-icon ui-icon-check'>Active</span></td>
								<?php } else { ?>
									<td class="td tooltip highlight-red" title="Inactive"><span class='ui-icon ui-icon-cancel'>Inactive</span></td>
								<?php } ?>
								<td><a class="ajax button_edit" href="process/subscription.php?rel=<?= $sub["sID"] ?>" data-target="#edit-subscription-dialog" title="Edit subscription #<?= $sub["sID"] ?>">Edit</a></span>
									<?php if (empty($sub["password"])) { ?>
									<td class="td tooltip" title="Password not set yet"><span class='ui-icon ui-icon-alert' style='margin-right: .3em;'>Password not set yet</span></td>
								<?php } else { ?>
									<td></td>
								<?php } ?>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		<?php } ?>
		<?php if (!empty($expSub)) { ?>
			<h3>Expired subscriptions</h3>
			<div>
				<table class="dataTable subscription table list ui-widget ui-widget-content">
					<thead>
						<tr>
							<td class="th">ID</td>
							<td class="th">Key</td>
							<td class="th">User</td>
							<td class="th">Organization</td>
							<td class="th">Create Date</td>
							<td class="th">Expire Date</td>
							<td class="th">Active</td>
							<td class="th"></td>
							<td class="th"></td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($expSub as $sub) { ?>
							<tr>
								<td><?= $sub["sID"] ?></td>
								<td class="key"><?= $sub["username"] ?></td>
								<td><?= implode(" ", array($sub["firstname"], $sub["lastname"])) ?></td>
								<td><?= $sub["organization"] ?></td>
								<td><?= date("Y/m/d", strtotime($sub["create_time"])) ?></td>
								<td><?= date("Y/m/d", strtotime($sub["exp_time"])) ?></td>
								<?php if ($sub["active"] === 1) { ?>
									<td class="td tooltip highlight-green" title="Active"><span class='ui-icon ui-icon-check'>Active</span></td>
								<?php } else { ?>
									<td class="td tooltip highlight-red" title="Inactive"><span class='ui-icon ui-icon-cancel'>Inactive</span></td>
								<?php } ?>
								<td><a class="ajax button_edit" href="process/subscription.php?rel=<?= $sub["sID"] ?>" data-target="#edit-subscription-dialog" title="Edit subscription #<?= $sub["sID"] ?>">Edit</a></span>
								<?php if (empty($sub["password"])) { ?>
									<td class="td tooltip" title="Password not set yet"><span class='ui-icon ui-icon-alert' style='margin-right: .3em;'>Password not set yet</span></td>
								<?php } else { ?>
									<td></td>
								<?php } ?>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		<?php } ?>
	</div>
	<div id="delete-subscription-dialog" class="confirmation">
		<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>The subsciption will be permanently deleted and cannot be recovered.<br/>Are you sure?</p>
	</div>
	<div id="edit-subscription-dialog" class="dialog-edit">

	</div>
</div>