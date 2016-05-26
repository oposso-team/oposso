{if !isset($SESSION.login)}
	<div class="loginout ui-button ui-button-text-icon-primary"><span class="ui-button-icon-primary ui-icon ui-icon-person"></span><span class="ui-button-text"><label for="email1" class="btn-dialog">{$LOCAL.global.signin_or_register[0]}</label> {$LOCAL.global.signin_or_register[1]} <label for="firstname" class="btn-dialog">{$LOCAL.global.signin_or_register[2]}</label></span></div>
{else}
	<div class="loginout ui-button ui-button-text-icon-primary"><span class="ui-button-icon-primary ui-icon ui-icon-person"></span><span class="ui-button-text"><a href="process/user_logout.php" class="btn-dialog">{$LOCAL.global.logout} {$SESSION.user.firstname} {$SESSION.user.lastname}</a></span></div>
{/if}