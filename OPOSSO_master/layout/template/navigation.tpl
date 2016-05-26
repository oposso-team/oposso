<nav>
	<ul id="navigation">
		{if !isset($SESSION.login)}
			<li><a href="index.php"><span>{$LOCAL.global.navigation.top.home}</span></a></li>
			<li class="disabled"><a href="subscription.php"><span>{$LOCAL.global.navigation.top.subscription}</span></a></li>
			<li class="disabled"><a href="account.php"><span>{$LOCAL.global.navigation.top.account}</span></a></li>
			<li class="disabled"><a href="notification.php"><span>{$LOCAL.global.navigation.top.notification}</span></a></li>
			<li class="disabled"><a href="how_to.php" ><span>{$LOCAL.global.navigation.footer.how_to}</span></a></li>
		{elseif empty($SESSION.login)}
			<li><a href="index.php"><span>{$LOCAL.global.navigation.top.home}</span></a></li>
			<li class="disabled"><a href="subscription.php"><span>{$LOCAL.global.navigation.top.subscription}</span></a></li>
			<li><a href="account.php"><span>{$LOCAL.global.navigation.top.account}</span></a></li>
			<li class="disabled"><a href="notification.php"><span>{$LOCAL.global.navigation.top.notification}</span></a></li>
			<li class="disabled"><a href="how_to.php" ><span>{$LOCAL.global.navigation.footer.how_to}</span></a></li>
		{else}
			<li><a href="index.php"><span>{$LOCAL.global.navigation.top.home}</span></a></li>
			<li><a href="subscription.php"><span>{$LOCAL.global.navigation.top.subscription}</span></a></li>
			<li><a href="account.php"><span>{$LOCAL.global.navigation.top.account}</span></a></li>
			<li><a href="notification.php"><span>{$LOCAL.global.navigation.top.notification}</span></a></li>
			<li><a href="how_to.php" ><span>{$LOCAL.global.navigation.footer.how_to}</span></a></li>
		{/if}
	</ul>
</nav>