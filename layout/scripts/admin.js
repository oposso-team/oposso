
$(function () {
	$("#tabs").tabs({
		activate: function (event, ui) {
			window.location.hash = ui.newPanel.attr('id');

			$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
		},
		create: function (event, ui) {
			window.location.hash = ui.panel.attr('id');
		},
		beforeLoad: function (event, ui) {
			toggleLoading($(ui.panel), true, "100%", "100%");
		},
		load: function (event, ui) {

//			$(".ui-dialog.ui-widget").remove();
			toggleLoading($(ui.panel), false);

			onLoad();
			
			$(".dialog, .dialog-edit").dialog({
				autoOpen: false,
				resizable: true,
				width: 500,
				height: 500,
				buttons: [{
						text: 'Save',
						class: 'button_save',
						click: function () {
							$('form', this).submit();
						}
					}, {
						text: 'Close',
						click: function () {
							$(this).dialog("close").html("");
						}
					}
				],
				close: function () {
					$(this).html("");
				}
			});
			$(".confirmation").dialog({
				autoOpen: false,
				width: 500,
				height: 200
			});
			$(".dialog.large, .dialog-edit.large, .confirmation.large").dialog("option", "width", 800);
			$(".accordion").accordion({
				collapsible: true,
				heightStyle: "content"
			});
			$(".datepicker").datepicker({
				dateFormat: "yy/mm/dd",
				showButtonPanel: true,
				showWeek: true,
				firstDay: 1
			});
			$("#slider-duration").slider({
				range: "min",
				value: 36,
				min: 1,
				max: 60,
				slide: function (event, ui) {
					$("#duration").val(ui.value);
				}
			});
			$("#duration").val($("#slider-duration").slider("value"));
			$("select.select").selectmenu();
			$("select.submit").selectmenu({
				change: function (event, data) {
					$(data.item.element).parents("form").submit();
				}
			});
			$("select.combobox").combobox();
			$("table.dataTable").each(function () {
				if ($.fn.dataTable.isDataTable(this)) {
					table = $(this).DataTable();
				} else if ($(this).is(".subscription")) {
					$(this).dataTable({
						"order": [[0, "desc"]],
						"lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]],
						"columnDefs": [
							{
								"targets": [7],
								"sortable": false,
								"searchable": false
							}
						],
						"drawCallback": onLoad
					});
				} else if ($(this).is(".user")) {
					$(this).dataTable({
						"processing": true,
						"serverSide": true,
						"ajax": "/admin/content/ajax/user.php",
						"order": [[0, "desc"]],
						"lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]],
						"columnDefs": [
							{
								"targets": [5],
								"sortable": false,
								"searchable": false
							}
						],
						"drawCallback": onLoad
					});
				}
			});
		}
	});
});

function onLoad() {
	$("a.ajax").bind("click", function (event) {
		event.preventDefault();
		$($(this).attr("data-target")).dialog("option", "title", $(this).attr("title"));
		initEditDialog($(this).attr("data-target"), $(this).attr("href"), "");
	});

	// ajax based form request and response handler
	$("form.ajax").bind("submit", function (event) {
		event.preventDefault();
		var form = new FormData($(this).get(0));
		$('input[type=file]', this).each(function () {
			var file_data = $(this).prop('files')[0];
			form.append('file', file_data);
		});
		if ($(this).is(".keyForm")) {
			$.ajax({
				url: $(this).attr('action'),
				type: 'POST',
				data: form,
				processData: false,
				contentType: false,
				beforeSend: function () {
					$("#keylist textarea").val("");
					$("#keylist").dialog("open");
					toggleLoading($("#keylist"), true, "100%", "100%");
				},
				success: function (response) {
					reloadTab();
					$("#keylist").dialog("open");
					toggleLoading($("#keylist"), false);
					$("#keylist").removeClass("loading");
					var textarea = $('<textarea/>').val(response);
					$("#keylist").append(textarea);
				}
			});
		} else {
			$.ajax({
				url: $(this).attr('action'),
				type: 'POST',
				data: form,
				processData: false,
				contentType: false,
				beforeSend: function () {
					toggleLoading($(ui.panel), true, "100%", "100%");
				},
				success: function (response) {
					reloadTab(function () {
						message(response);
					});
				}
			});
		}
		return false;
	});

	// set form target of confirmation requests
	$("form.ajax").each(function () {
		var form = $(this);
		$("button[type='submit']", form).click(function (event) {
			event.preventDefault();
			var value = $(this).val(),
					rel = $(this).attr("data-rel");
			if (value != "")
				$("input[name='action']", form).val(value);
			if (rel != "")
				$("input[name='rel']", form).val(rel);
			if (typeof $(this).attr("data-target") != "undefined")
				dialog = $($(this).attr("data-target"));
			else
				dialog = $(".confirmation");
			if (typeof $(this).attr("title") !== "undefined")
				$(dialog).dialog("option", "title", $(this).attr("title"));
			if ($(this).is(".confirm")) {
				$(dialog).dialog("option", "buttons", [
					{
						text: 'Confirm',
						click: function () {
							$(form).submit();
							$(this).dialog("close");
						}
					}, {
						text: 'Cancel',
						click: function () {
							$(this).dialog("close");
						}
					}
				]).dialog("open");
			} else {
				$(form).submit();
			}
		});
	});
	
	init_button();
}

function initEditDialog(target, requestURL, data) {
	$.ajax({
		url: requestURL,
		type: 'POST',
		data: data,
		beforeSend: function () {
			$(target).html();
			$(target).dialog("open");
			toggleLoading($(target), true, "100%", "100%");
		},
		success: function (response) {
			toggleLoading($(target), false);
			$(target).removeClass("loading");
			$(target).html(response);
			$(".datepicker", target).datepicker({
				dateFormat: "yy/mm/dd",
				showButtonPanel: true,
				showWeek: true,
				firstDay: 1
			});
			message($('<div>').append($(".message", target)).html());

			var form = $("form", target);
			$(form).bind("submit", function (event) {
				event.preventDefault();
				var data = form.serialize();
				initEditDialog($(form).attr("data-target"), $(form).attr('action'), data);
			});
		}
	});
}

function message(msg) {
	var message = $(msg).appendTo("body").position({
		my: "center center",
		at: "center center",
		of: "body",
		collision: "none"
	});
	setTimeout(function () {
		$(message).hide("fade", 1000, function () {
			$(message).remove();
		});
	}, 5000);
}

function reloadTab(on) {
	var current_index = $("#tabs").tabs("option", "active");
	$("#tabs").tabs('load', current_index);
	if (typeof on == "function") {
		$("#tabs").on("tabsload", on());
	}
}

function toggleLoading(jQ, on, width, height) {
	if (!on) {
		$(jQ).children(".loading").remove();
	} else {
		var loader = $("<div/>").addClass("loading").css({
			width: width,
			height: height
		});
		$("<div/>").addClass("loadingBg").appendTo(loader);
		$("<div/>").addClass("loadingImg").appendTo(loader);
		$(loader).appendTo(jQ);
	}
}