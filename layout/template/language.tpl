{if $COOKIE.ln == 'DE'}
<div class="language ui-button ui-button-text-icon-primary">
	<span class="wrap_inner">
		<a href="?ln=de" title="Deutsch" hreflang="de" class="active" ><img src="layout/images/de.png" alt="DE" /></a>
		<a href="?ln=en" title="English" hreflang="en" ><img src="layout/images/en.png" alt="EN" /></a>
	</span>
</div>
{else}
<div class="language ui-button ui-button-text-icon-primary">
	<span class="wrap_inner">
		<a href="?ln=de" title="Deutsch" hreflang="de" ><img src="layout/images/de.png" alt="DE" /></a>
		<a href="?ln=en" title="English" hreflang="en" class="active" ><img src="layout/images/en.png" alt="EN" /></a>
	</span>
</div>
{/if}