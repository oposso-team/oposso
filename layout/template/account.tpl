{extends file="layout/template/template.tpl"}

{block name=content}
	<div class="grid50">
		<form method="post" action="process/user_update.php" class="ajax">
			<input type="hidden" name="action" value="update" />
			<table class="ui-widget ui-widget-content table-form">
				<thead>
					<tr>
						<th colspan="2">
							<h2>{$LOCAL.account.head}</h2>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{$LOCAL.account.firstname}</td>
						<td><input placeholder="{$LOCAL.account.firstname}" class="text ui-widget-content ui-corner-all" type="text" maxlength="255" name="firstname" value="{$SESSION.user.firstname}" /></td>
					</tr>
					<tr>
						<td>{$LOCAL.account.lastname}</td>
						<td><input placeholder="{$LOCAL.account.lastname}" class="text ui-widget-content ui-corner-all" type="text" maxlength="255" name="lastname" value="{$SESSION.user.lastname}" /></td>
					</tr>
					<tr>
						<td>{$LOCAL.account.organization}</td>
						<td><input placeholder="{$LOCAL.account.organization}" class="text ui-widget-content ui-corner-all" type="text" maxlength="255" name="organization" value="{$SESSION.user.organization}" /></td>
					</tr>
					<tr>
						<td>{$LOCAL.account.email}</td>
						<td><input placeholder="{$LOCAL.account.email}" class="text ui-widget-content ui-corner-all" type="text" maxlength="255" name="email" value="{$SESSION.user.email}" /></td>
					</tr>
					<tr>
						<td>{$LOCAL.account.password}</td>
						<td><input placeholder="{$LOCAL.account.password}" class="text ui-widget-content ui-corner-all" type="password" maxlength="255" name="password" value="" /></td>
					</tr>
					<tr>
						<td>{$LOCAL.account.password_confirm}</td>
						<td><input placeholder="{$LOCAL.account.password_confirm}" class="text ui-widget-content ui-corner-all" type="password" maxlength="255" name="password_confirm" value="" /></td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td></td>
						<td><button class="button_save" type="submit">{$LOCAL.account.save}</button></td>
					</tr>
				</tfoot>
			</table>
		</form>
	</div>
	{if !$SESSION.login}
		<div class="grid50">
			<p><span style="float: left; margin-right: .3em; margin-top: 2px;" class="ui-icon ui-icon-alert"></span> {$LOCAL.account.msg.confirmation}</p>
			
			<form method="post" action="process/user_update.php" class="ajax">
							<input type="hidden" name="action" value="send" />
							<button class="button_resend" type="submit">{$LOCAL.account.send}</button>
			</form>
		</div>
	{/if}
{/block}