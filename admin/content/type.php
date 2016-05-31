<?php
/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
require_once('./../../config/mainconf.php');
require_once($PATH_classes . "/class.KeyHandler.php");

$keyHandler = new KeyHandler();
$typeList = $keyHandler->get_type();
?>
<!-- Subscription Type -->

<div id="type">
	<form method="post" action="process/type.php" class="ajax typeForm">
		<input type="hidden" name="action" value="" />
		<input placeholder="Short name" title="Short name of subscription type" size="10" class="text" type="text" maxlength="5" name="short" value="" />
		<input placeholder="Path to download server" class="text" type="text" maxlength="255" name="path" value="" />
		<input placeholder="Description" class="text" type="text" maxlength="255" name="description" value="" />
		<button class="button_add" type="submit" value="add">Add</button>
	</form>
	<br/>
	<? if (!empty($typeList)) { ?>
		<table class="list ui-widget ui-widget-content">
			<? foreach ($typeList as $type) { ?>
			<tr>
				<td>
					<form method="post" action="process/type.php" class="ajax typeForm tr">
						<input type="hidden" name="tID" value="<?= $type["tID"] ?>" />
						<input type="hidden" name="action" value="" />
						<input disabled="disabled" title="Short name of subscription type" size="10" class="text" type="text" maxlength="5" name="short" value="<?= $type["short"] ?>" />&nbsp;
							<input placeholder="Path to download server" class="text" type="text" maxlength="255" name="path" value="<?= $type["path"] ?>" />&nbsp;
							<input placeholder="Description" class="text" type="text" maxlength="255" name="description" value="<?= $type["description"] ?>" />&nbsp;
							<button class="button_edit" type="submit" value="update">Update</button>&nbsp;
							<button class="button_delete confirm" type="submit" value="delete" data-target="#delete-key-dialog">Delete</button>
					</form>
				</td>
			</tr>
			<? } ?>
		</table>
	<? } ?>

	<div id="delete-key-dialog" class="confirmation" title="Are you sure you want to delete this type?">
		<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>These type and all corresponding keys (if any) will be permanently deleted and cannot be recovered. Are you sure?</p>
	</div>
</div>

