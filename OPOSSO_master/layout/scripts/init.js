
$(function () {
	setNavigation();
	$("#msg_box .ui-widget.message").each(function () {
		message($(this));
	});
	$("#sendpass").dialog({
		autoOpen: false,
		height: 250,
		width: 300,
		modal: true,
		buttons: [
			{
				text: LOCAL["home"]["sendpass"]["submit"],
				click: function () {
					$("form", this).submit();
				}
			},
			{
				text: LOCAL["global"]["dialog"]["cancel"],
				click: function () {
					$(this).dialog("close");
				}
			}
		]
	});
	$("#setpass").dialog({
		autoOpen: false,
		height: 280,
		width: 300,
		modal: true,
		buttons: [
			{
				text: LOCAL["subscription"]["dialog"]["setpass"]["submit"],
				click: function () {
					$("form", this).submit();
				}
			},
			{
				text: LOCAL["global"]["dialog"]["cancel"],
				click: function () {
					$(this).dialog("close");
				}
			}
		]
	});
	$("#addkeys").dialog({
		autoOpen: false,
		height: 540,
		width: 500,
		modal: true,
		buttons: [
			{
				text: LOCAL["subscription"]["dialog"]["key"]["submit"],
				click: function () {
					$("form", this).submit();
				}
			},
			{
				text: LOCAL["global"]["dialog"]["cancel"],
				click: function () {
					$(this).dialog("close");
				}
			}
		]
	});
	$("#extendsubs").dialog({
		autoOpen: false,
		height: 470,
		width: 900,
		modal: true,
		buttons: [
			{
				text: LOCAL["subscription"]["dialog"]["extend"]["submit"],
				click: function () {
					$("form", this).submit();
				}
			},
			{
				text: LOCAL["global"]["dialog"]["cancel"],
				click: function () {
					$(this).dialog("close");
				}
			}
		]
	});
	$(".dialog.autoload").dialog("open");
	$(".btn-dialog").click(function () {
		$(".dialog").dialog("close");
		if ($(this).is("a"))
			$($(this).attr("href")).dialog("open");
		else if ($(this).is("input") || $(this).is("button")) {
			if (typeof $(this).attr("data-target") !== "undefined") {
				if (typeof $(this).attr("title") !== "undefined") {
					$($(this).attr("data-target")).dialog("option", "title", $(this).attr("title"));
				}
				$("input[name='target']", $(this).attr("data-target")).val($(this).val());
			}
			$($(this).attr("data-target")).dialog("open");
		}
	});

	// Handle already existing forms; For dynamic forms loaded via ajax see function initEditDialog below
	$("form.ajax").bind("submit", function (event) {
		event.preventDefault();
		var form = $(this);
		var data = form.serialize();
		$.ajax({
			url: $(form).attr("action"),
			type: "POST",
			dataType: "json",
			data: data,
			beforeSend: function () {
				toggleLoading("body", true, "100%", "100%");
			},
			success: function (response) {
				toggleLoading("body", false);
				if (response["value"]) {
					$(".dialog").dialog("close");
					if (response["message"]["type"] === "error" || response["message"]["type"] === "success")
						message(response["message"]["text"]);
					else if (response["message"]["type"] === "redirect") {
						window.location.href = response["message"]["text"];
					}
				} else {
					if (response["message"]["type"] === "error" || response["message"]["type"] === "success")
						message(response["message"]["text"], form);
					else if (response["message"]["type"] === "log") {
						console.log(response["message"]["text"]);
					}
				}
			}
		});
	});

	$("a.ajax, button.ajax").bind("click", function (event) {
		event.preventDefault();
		var data = {};
		var href = $(this).is('a') ? $(this).attr("href") : $(this).attr("data-href");
		data["source"] = $($(this).attr("data-source")).serialize();
		data["action"] = $(this).attr("data-action");
		if (typeof $(this).attr("title") !== "undefined") {
			$($(this).attr("data-target")).dialog("option", "title", $(this).attr("title"));
		}
		initEditDialog($(this).attr("data-target"), href, data);
	});

	$("#accordion").accordion({
		heightStyle: "content"
	});
	$("input#checkall-current").click(function () {
		$("input.checksub.current").prop("checked", $("input#checkall-current").prop("checked"));
	});
	$("input#checkall-expired").click(function () {
		$("input.checksub.expired").prop("checked", $("input#checkall-expired").prop("checked"));
	});
	$(".tooltip").tooltip();
	$(".tabs").tabs();
	init_button();
});

function message(msg, append) {
	console.log(msg);
	append = (typeof append === "undefined") ? "body" : append;
	var message = $(msg).appendTo(append).position({
		my: "center center",
		at: "center top+100",
		of: append,
		collision: "fit"
	});
	setTimeout(function () {
		$(message).hide("fade", 1000, function () {
			$(message).remove();
			$(msg).remove();
		});
	}, 5000);
}

// Handle dynamic forms loaded via ajax
function initEditDialog(target, requestURL, data, type) {
	type = (typeof type === "undefined") ? "html" : type;
	var form = $(target);
	$.ajax({
		url: requestURL,
		type: 'POST',
		dataType: type,
		data: data,
		beforeSend: function () {
			if (type === 'html') {
				$(target).html();
				$(target).dialog("open");
			}
			toggleLoading("body", true, "100%", "100%");
		},
		success: function (response) {
			toggleLoading("body", false);
			if (type === 'html') {
				$(target).html(response);
				$(".datepicker", target).datepicker({
					dateFormat: "yy/mm/dd",
					showButtonPanel: true,
					showWeek: true,
					firstDay: 1
				});
				message($('<div>').append($(".message", target)).html(), target);

				form = $("form", target);
				$(form).bind("submit", function (event) {
					event.preventDefault();
					var data = form.serialize();
					initEditDialog($(form).attr("data-target"), $(form).attr('action'), data, 'json');
				});
			} else if (type === 'json') {
				if (response["value"]) {
					$(".dialog").dialog("close");
					if (response["message"]["type"] === "error" || response["message"]["type"] === "success")
						message(response["message"]["text"]);
					else if (response["message"]["type"] === "redirect") {
						window.location.href = response["message"]["text"];
					}
				} else {
					if (response["message"]["type"] === "error" || response["message"]["type"] === "success")
						message(response["message"]["text"], form);
					else if (response["message"]["type"] === "log") {
						console.log(response["message"]["text"]);
					}
				}

			}
		}
	});
}

function toggleLoading(jQ, on, width, height) {
	var pos = jQ === "body" ? "fixed" : "relative";
	if (!on) {
		$(jQ).children(".loading").remove();
	} else {
		var loader = $("<div/>").addClass("loading").css({
			position: pos,
			zIndex: 1000,
			width: width,
			height: height
		});
		$("<div/>").addClass("loadingBg").appendTo(loader);
		$("<div/>").addClass("loadingImg").appendTo(loader);
		$(loader).appendTo(jQ);
	}
}

function setNavigation() {
	var path = window.location.pathname;
	path = path.replace(/\/$/, "");
	path = decodeURIComponent(path);
	$("nav a").each(function () {
		var href = $(this).attr('href');
		if (path.substring(0, href.length) === href || (path[0] === "/" && path.substring(1, href.length + 1) === href)) {
			$(this).addClass('active');
		}
	});
}