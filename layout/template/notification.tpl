{extends file="layout/template/template.tpl"}

{block name=content}
	<div class="grid66">
		<form method="post" action="process/user_update.php" class="ajax">
			<input type="hidden" name="action" value="notification" />
			<table class="ui-widget ui-widget-content table-form">
				<thead>
					<tr>
						<th colspan="2">
							<h2>{$LOCAL.notification.head}</h2>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><input class="text ui-widget-content ui-corner-all" type="checkbox" id="twoMonth" name="twoMonth" value="1" {if $SESSION.user.notification.twoMonth}checked{/if} /></td>
						<td><label for="twoMonth">{$LOCAL.notification.twoMonth}</label></td>
					</tr>
					<tr>
						<td><input class="text ui-widget-content ui-corner-all" type="checkbox" id="twoWeeks" name="twoWeeks" value="1" {if $SESSION.user.notification.twoWeeks}checked{/if}/></td>
						<td><label for="twoWeeks">{$LOCAL.notification.twoWeeks}</label></td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td></td>
						<td><button class="button_save" type="submit">{$LOCAL.notification.save}</button></td>
					</tr>
				</tfoot>
			</table>
		</form>
	</div>
{/block}