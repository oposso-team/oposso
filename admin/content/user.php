<?php
/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
require_once('./../../config/mainconf.php');
require_once($PATH_classes . '/class.User.php');
require_once($PATH_classes . '/class.Subscription.php');

?>

<!-- User -->
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
		
	</table>
	<div id="delete-user-dialog" class="confirmation">
		<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>The user and all corresponding subsciptions (if any) will be deleted.<br/>Are you sure?</p>
	</div>
	<div id="setpass-user-dialog" class="confirmation">
		<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>The user password will be overwritten by a randomly generated password.<br/>Are you sure?</p>
	</div>
	<div id="edit-subscription-dialog" class="dialog-edit">

	</div>
	<div id="edit-user-dialog" class="dialog-edit large">
		
	</div>
