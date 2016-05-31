<?php
/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
require_once('./../../config/mainconf.php');
require_once($PATH_classes . "/class.Customize.php");

$Customize = new Customize();
$settings = $Customize->get_settings();
$values = array();
foreach ($settings as $set) {
	$values[$set['title']] = $set['value'];
}
$logo = empty($values["logo"]) ? "" : $values["logo"];
$product_name = empty($values["product_name"]) ? "" : $values["product_name"];
$pimary_color = empty($values["pimary_color"]) ? "" : $values["pimary_color"];
$secondary_color = empty($values["secondary_color"]) ? "" : $values["secondary_color"];
?>
<!-- Subscription Type -->

<div id="type">
	<form method="post" action="process/customisation.php" class="ajax">
		<input type="hidden" name="action" value="save" />
		<table class="list ui-widget ui-widget-content">
			<tr>
				<th>Product name</th>
				<td>
					<input placeholder="Product name" class="text" type="text" maxlength="255" name="product_name" value="<?= $product_name ?>" />
				</td>
				<td>
				</td>
			</tr>
			<tr>
				<th>Logo</th>
				<td>
					<input class="text" type="file" maxlength="5" name="logo" />
				</td>
				<td>
					<?= $logo ?>
				</td>
			</tr>
			<tr>
				<th>Primary color</th>
				<td>
					<input placeholder="Primary color" class="text" type="text" maxlength="255" name="pimary_color" value="<?= $pimary_color ?>" />
				</td>
				<td style="background-color: <?= $pimary_color ?>">
				</td>
			</tr>
			<tr>
				<th>Secondary color</th>
				<td>
					<input placeholder="Secondary color" class="text" type="text" maxlength="255" name="secondary_color" value="<?= $secondary_color ?>" />
				</td>
				<td style="background-color: <?= $secondary_color ?>">
				</td>
			</tr>
			<tr>
				<th></th>
				<td colspan="2">
					<button class="button_save" type="submit" value="">Save</button>
				</td>
			</tr>
		</table>

	</form>
</div>

