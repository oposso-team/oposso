{extends file="layout/template/template.tpl"}

{block name=content}
	<h1>{$LOCAL.home.intro.head}</h1>
	<p>{$LOCAL.home.intro.text}</p>
	{if !isset($SESSION.login)}
		<hr/>
		<div class="grid50 signin">
			<form method="post" action="process/user_signin.php" class="ajax">
				{if !isset($redirect)}
					<input type="hidden" name="redirect" value="subscription.php" />
				{else}
					<input type="hidden" name="redirect" value="{$redirect}" />
				{/if}
				<table class="ui-widget ui-widget-content table-form">
					<thead>
						<tr>
							<th colspan="2"><h2>{$LOCAL.home.signin.title}</h2></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><label for="email1">{$LOCAL.home.signin.email}</label></td>
							<td><input type="text" name="email" id="email1" placeholder="E-Mail" value="" class="text ui-widget-content ui-corner-all" /></td>
						</tr>
						<tr>
							<td><label for="password1">{$LOCAL.home.signin.password}</label></td>
							<td><input type="password" name="password" id="password1" placeholder="Password" value="" class="text ui-widget-content ui-corner-all" /></td>
						</tr>
						<tr>
							<td></td>
							<td><a href="#sendpass" class="btn-dialog">{$LOCAL.home.signin.link_forgot}</a></td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td></td>
							<td><button class="button" type="submit">{$LOCAL.home.signin.submit}</button></td>
						</tr>
					</tfoot>
				</table>
				<!-- Allow form submission with keyboard without duplicating the dialog button -->
				<input type="submit" tabindex="-1" style="position:absolute; top:-1000px"/>
			</form>
		</div>

		<div class="grid50 register">
			<form method="post" action="process/user_register.php" class="ajax">
				<input type="hidden" name="action" value="register" />
				<table class="ui-widget ui-widget-content table-form">
					<thead>
						<tr>
							<th colspan="2"><h2>{$LOCAL.home.register.title}</h2></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><label for="firstname">{$LOCAL.home.register.first_name}</label></td>
							<td><input type="text" name="firstname" id="firstname" placeholder="{$LOCAL.home.register.first_name}" value="" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td><label for="lastname">{$LOCAL.home.register.last_name}*</label></td>
							<td><input type="text" name="lastname" id="lastname" placeholder="{$LOCAL.home.register.last_name}" value="" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td><label for="organization">{$LOCAL.home.register.organization}</label></td>
							<td><input type="text" name="organization" id="organization" placeholder="{$LOCAL.home.register.organization}" value="" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td><label for="email2">{$LOCAL.home.register.email}*</label></td>
							<td><input type="text" name="email" id="email2" placeholder="{$LOCAL.home.register.email}" value="" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td><label for="password2">{$LOCAL.home.register.password}*</label></td>
							<td><input type="password" name="password" id="password2" placeholder="{$LOCAL.home.register.password}" value="" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td><label for="password_confirm">{$LOCAL.home.register.password_confirm}*</label></td>
							<td><input type="password" name="password_confirm" id="password_confirm" placeholder="{$LOCAL.home.register.password}" value="" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td colspan="2">{$LOCAL.home.register.text.0} <a class="link" href="user_agreement.php" target="_blank">{$LOCAL.home.register.text.1}</a> {$LOCAL.home.register.text.2} <a class="link" href="privacy_policy.php" target="_blank">{$LOCAL.home.register.text.3}</a></td>
						</tr>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td></td>
							<td><button class="button" type="submit">{$LOCAL.home.register.submit}</button></td>
						</tr>
					</tfoot>
				</table>
				<!-- Allow form submission with keyboard without duplicating the dialog button -->
				<input type="submit" tabindex="-1" style="position:absolute; top:-1000px"/>
			</form>
		</div>
						
		<div class="dialog" id="sendpass" title="{$LOCAL.home.sendpass.title}">
			<form method="post" action="process/user_forgot_pass.php" class="ajax">
				<fieldset>
					<p>{$LOCAL.home.sendpass.text}</p>
					<label for="email3">{$LOCAL.home.sendpass.email}</label>
					<input type="text" name="email" id="email3" placeholder="{$LOCAL.home.sendpass.email}" value="" class="text ui-widget-content ui-corner-all"/>
					<!-- Allow form submission with keyboard without duplicating the dialog button -->
					<input type="submit" tabindex="-1" style="position:absolute; top:-1000px"/>
				</fieldset>
			</form>
		</div>
	{/if}
						
	{$LOCAL.home.text}
{/block}