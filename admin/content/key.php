<?php
/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
require_once('./../../config/mainconf.php');
require_once($PATH_classes . "/class.KeyHandler.php");

$keyHandler = new KeyHandler();
$types = $keyHandler->get_type();
$keySum = $keyHandler->get_key_summary();
?>

<!-- Subscription Key -->

<div id="key">
	<form method="post" action="process/key.php" class="ajax keyForm">
		<input type="hidden" name="action" value="add" />
		<select name="type" class="select small">
			<option value="0">++ Type ++</option>
			<? foreach ($types as $type) { ?>
				<option value="<?= $type["tID"] ?>"><?= $type["short"] ?></option>
			<? } ?>
		</select>
		<div class="slider-container">
			<label for="duration">Duration (months):</label>
			<input type="text" id="duration" name="duration" readonly style="border:0; font-weight:bold;">
			<div id="slider-duration"></div>
		</div>
		<input placeholder="Platform" class="text" type="text" name="platform" value="" />
		<select name="contract" class="select wide">
			<option value="">++ Contract Type ++</option>
			<option value="AGB1">AGB1</option>
			<option value="EVBIT-UebA">EVBIT-UebA</option>
		</select>
		<hr class="vert"/>
		<select name="number" class="select small">
			<option value="0">++ Number ++</option>
			<option value="1">1</option>
			<option value="5">5</option>
			<option value="10">10</option>
			<option value="50">50</option>
			<option value="100">100</option>
			<option value="500">500</option>
			<option value="1000">1,000</option>
			<option value="5000">5,000</option>
		</select>
		<button class="button_add" type="submit">Generate</button>
	</form>
	<? if (!empty($keySum)) { ?>
		<h3>Summary</h3>
		<table class="list ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Type</th>
					<th>Platform</th>
					<th>Contract</th>
					<th>Total</th>
					<th>Used</th>
					<th>
			<form method="post" action="process/key.php" target="_blank">
				<input type="hidden" name="action" value="csv" />
				<button class="button_download" type="submit">Download CSV</button>
			</form>
			</th>
			</tr>
			</thead>
			<tbody>
				<?
				foreach ($keySum as $keyS) {
					$type = $keyHandler->get_type($keyS["tID"]);
					?>
					<tr>
						<td><?= $type["short"] ?><?= $keyS["duration"] ?></td>
						<td><?= $keyS["platform"] ?></td>
						<td><?= $keyS["contract"] ?></td>
						<td><?= $keyS["total"] ?></td>
						<td><?= $keyS["used"] ?></td>
						<td>
							<form method="post" action="process/key.php" class="ajax keyForm">
								<input type="hidden" name="action" value="export" />
								<input type="hidden" name="tID" value="<?= $keyS["tID"] ?>" />
								<input type="hidden" name="duration" value="<?= $keyS["duration"] ?>" />
								<input type="hidden" name="platform" value="<?= $keyS["platform"] ?>" />
								<select name="export" class="submit wide">
									<option value="0">++ Select export ++</option>
									<option value="all">All</option>
									<option value="unused">Unused</option>
									<option value="used">Used</option>
								</select>
							</form>
						</td>
					</tr>
				<? } ?>
			</tbody>
		</table>
	<? } ?>

	<div id="keylist" class="dialog" title="Subscription Keys">
	</div>
</div>