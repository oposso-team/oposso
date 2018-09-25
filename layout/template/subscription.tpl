{extends file="layout/template/template.tpl"}

{block name=content}
	<div class="tabs">
		<ul>
			<li><a href="#tabs-current">{$LOCAL.subscription.head.current_subscription}</a></li>
			<li><a href="#tabs-expired">{$LOCAL.subscription.head.expired_subscription}</a></li>
			<button class="button_key btn-dialog" value="-1" title="{$LOCAL.subscription.button.set_global_password}" data-target="#setpass">{$LOCAL.subscription.button.set_global_password}</button>
			<button class="button_add btn-dialog" type="button" data-target="#addkeys">{$LOCAL.subscription.button.add_subscription}</button>
			<button class="button_add ajax" type="button" data-href="process/subscription.php" data-action="getform" data-source=".checksub" data-callback="subInfo" data-target="#extendsubs">{$LOCAL.subscription.button.extend_subscription}</button>
		</ul>
		<div id="tabs-current">
			{if !empty($currSub)}
				{$currPagination}
				<form method="post" action="process/subscription.php" class="ajax">
					<input type="hidden" name="action" value="edit" />
					<table class="list ui-widget ui-widget-content">
						<thead>
							<tr class="ui-widget-header">
								<th>{$LOCAL.subscription.table_head.id}</th>
								<th><input type="checkbox" id="checkall-current" /></th>
								<th><button class="button_save" type="submit">{$LOCAL.subscription.button.save_description}</button></th>
								<th>{$LOCAL.subscription.table_head.key}</th>
								<th>{$LOCAL.subscription.table_head.create_date}</th>
								<th>{$LOCAL.subscription.table_head.expire_date}</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							{foreach $currSub as $sub}
								<tr>
									<td>{$sub.sID}</td>
									<td><input type="checkbox" class="checksub current" name="sub[{$sub.sID}]" value="{$sub.sID}" /></td>
									<td><input placeholder="Description" class="text ui-widget-content ui-corner-all" type="text" maxlength="255" name="description[{$sub.sID}]" value="{$sub.description}" /></td>
									<td class="key">{$sub.username}</td>
									<td>{$sub.create_time|date_format:"Y/m/d"}</td>
									<td>{$sub.exp_time|date_format:"Y/m/d"}</td>
									<td>
										<button class="button_key button_notext btn-dialog" type="button" value="{$sub.sID}" title="{$LOCAL.subscription.dialog.setpass.title} #{$sub.sID}" data-target="#setpass">{$LOCAL.subscription.button.set_password}</button>
									</td>
									{if empty($sub.password)}
										<td class="tooltip" title="{$LOCAL.subscription.info.setpass}"><span class='ui-icon ui-icon-alert' style='float: left; margin-right: .3em;'></span></td>
									{/if}
								</tr>
							{/foreach}
						</tbody>
					</table>
				</form>
				{$currPagination}
			{/if}
		</div>
		<div id="tabs-expired">
			{if !empty($expSub)}
				{$expPagination}
				<table class="list ui-widget ui-widget-content">
					<thead>
						<tr class="ui-widget-header">
							<th>{$LOCAL.subscription.table_head.id}</th>
								<th><input type="checkbox" id="checkall-expired" /></th>
							<th>{$LOCAL.subscription.table_head.description}</th>
							<th>{$LOCAL.subscription.table_head.key}</th>
							<th>{$LOCAL.subscription.table_head.create_date}</th>
							<th>{$LOCAL.subscription.table_head.expire_date}</th>
						</tr>
					</thead>
					<tbody>
						{foreach $expSub as $sub}
							<tr>
								<td>{$sub.sID}</td>
								<td><input type="checkbox" class="checksub expired" name="sub[{$sub.sID}]" value="{$sub.sID}" /></td>
								<td>{$sub.description}</td>
								<td class="key">{$sub.username}</td>
								<td>{$sub.create_time|date_format:"Y/m/d"}</td>
								<td>{$sub.exp_time|date_format:"Y/m/d"}</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
				{$expPagination}
			{/if}
		</div>
	</div>
	<div class="dialog" id="addkeys" title="{$LOCAL.subscription.dialog.key.title}">
		<form method="post" action="process/subscription.php" class="ajax">
			<fieldset>
				<input type="hidden" name="action" value="addkeys" />
				<label for="keys">{$LOCAL.subscription.dialog.key.label}</label>
				<textarea id="keys" name="keys" class="text ui-widget-content ui-corner-all"></textarea>
				<!-- Allow form submission with keyboard without duplicating the dialog button -->
				<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
			</fieldset>
		{if $tc_required}
			<fieldset>
				<input type='checkbox' name='conditions' class='inline' id='addkeys-conditions' /><label class='inline' for='addkeys-conditions'>{$LOCAL.subscription.dialog.extend.label_termsconditions}</label>
			</fieldset>
		{/if}
		</form>
	</div>
	<div class="dialog" id="extendsubs" title="{$LOCAL.subscription.dialog.extend.title}">
	</div>

	<div class="dialog" id="setpass">
		<form method="post" action="process/subscription.php" class="ajax">
			<fieldset>
				<input type="hidden" name="action" value="setpass" />
				<input type="hidden" name="target" value="" />
				<label for="password">{$LOCAL.subscription.dialog.setpass.label_pass}</label>
				<input type="password" name="password" id="password" placeholder="{$LOCAL.subscription.dialog.setpass.label_pass}" value="" class="text ui-widget-content ui-corner-all">
				<label for="password_confirm">{$LOCAL.subscription.dialog.setpass.label_pass_confirm}</label>
				<input type="password" name="password_confirm" id="password_confirm" placeholder="{$LOCAL.subscription.dialog.setpass.label_pass_confirm}" value="" class="text ui-widget-content ui-corner-all">
				<!-- Allow form submission with keyboard without duplicating the dialog button -->
				<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
			</fieldset>
		</form>
	</div>
{/block}