function init_button() {
	$(".button").button();
	$(".button_add").button({
		icons: {
			primary: "ui-icon-plusthick"
		}
	});
	$(".button_conf").button({
		icons: {
			primary: "ui-icon-gear"
		}
	});
	$(".button_delete").button({
		icons: {
			primary: "ui-icon-trash"
		}
	});
	$(".button_download").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$(".button_edit").button({
		icons: {
			primary: "ui-icon-pencil"
		}
	});
	$(".button_lock").button({
		icons: {
			primary: "ui-icon-locked"
		}
	});
	$(".button_refresh").button({
		icons: {
			primary: "ui-icon-refresh"
		}
	});
	$(".button_send").button({
		icons: {
			primary: "ui-icon-transfer-e-w"
		}
	});
	$(".button_save").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$(".button_unlock").button({
		icons: {
			primary: "ui-icon-unlocked"
		}
	});
	$(".button_key").button({
		icons: {
			primary: "ui-icon-key"
		}
	});
	$(".button_resend").button({
		icons: {
			primary: "ui-icon-arrowrefresh-1-e"
		}
	});
	$(".button_notext").button({
		text: false
	});
}