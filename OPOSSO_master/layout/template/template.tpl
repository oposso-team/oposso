<!DOCTYPE html>
<html>
	<head>
		<title>{$product_name} - subscription platform</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="layout/styles/jquery-ui.min.css" type="text/css" />
		<link rel="stylesheet" href="layout/less/less.php" type="text/css" />
		<link rel="stylesheet" href="layout/styles/styles.css" type="text/css" />
		<script type="text/javascript" src="layout/scripts/locallang2js.php"></script>
		<script type="text/javascript" src="layout/scripts/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="layout/scripts/jquery-ui.min.js"></script>
		<script type="text/javascript" src="layout/scripts/jquery.ui.combobox.js"></script>
		<script type="text/javascript" src="layout/scripts/ui-button.js"></script>
		<script type="text/javascript" src="layout/scripts/init.js"></script>
	</head>
	<body>
		<header>
			<div class="wrap_inner">
				<div class="logo"><a href="/" title="{$product_name}">{$product_name}</a></div>
				<div class="brand"><a href="/" title="OPOSSO">OPOSSO</a></div>
				<div id="msg_box">{$msg_error}{$msg_success}</div>
				{include file="layout/template/login_logout.tpl"}
				{include file="layout/template/language.tpl"}
			</div>
		</header>


		<section id="content">
			<div class="border_top"></div>
			<div class="wrap_outer">
				{include file="layout/template/navigation.tpl"}
				<div class="wrap_inner">
					{block name=content}Content{/block}
					<div class="clear"></div>
				</div>
			</div>
			<div class="border_bottom"></div>
		</section>
		
		<footer>
			<div class="wrap_inner">{include file="layout/template/footer.tpl"}</div>
			<div class="border_bottom"></div>
		</footer>

	</body>
</html>