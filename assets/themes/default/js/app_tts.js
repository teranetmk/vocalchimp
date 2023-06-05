"use strict";

var tts_global_base_url = $("#global_base_url").val();
if ($('#global_caption').length) {
var tts_global_caption_array = $("#global_caption").val().split("||");
}
var enabledEngine = $("#enabledEngine").val();
var tts_language_list_json;

function scrollto(id) { $('html, body').animate({ scrollTop: $('#'+id).offset().top}, 1000); }

(function($){
	
	$.ajax({  // get language list and details, the result will be saved to local as a variable
        url: tts_global_base_url + "tts/get_language_detail/",
        async: false,
        dataType: "json",
        success: function(data) {
			tts_language_list_json = data;
        }
    });


	$('#tts_language').on("change", function(){
		tts_list_voice_builder($("#tts_language").val(), "neural");
	});
	
	
	$('#tts_text').bind("input propertychange", function() {
		var spaceCount = (this.value.split(" ").length - 1);
		var textWithoutSpaces = this.value.replace(/ /g,'')
		$("#tts_text_used").text(textWithoutSpaces.length);
		$('#tts_text_input_position').val(textWithoutSpaces.length);
		var maximum_characters = parseInt($("#tts_text_character_limit").text()) + spaceCount
		if (textWithoutSpaces.length > maximum_characters) {
			showMessage("warning", "", $('#maximum_characters_notice').val() + maximum_characters, "");
			this.value = this.value.substring(0, maximum_characters);
			$("#tts_text_used").text(maximum_characters);
		}
	});

	$('#tts_voice_list').on('change', function(){
		$('#tts_resource_ids').val($(this).val())
	});



	$("#tts_text_clear").on("click", function(){
		$("#tts_text").val("");
		$("#tts_text_used").text('0');
		$('#tts_text_input_position').val(0);
	});
	
	
	
	/*$("#tts_text").on("focusout", function() {
		$('#tts_text_input_position').val($('#tts_text').prop("selectionStart"));		
	});*/	

	$('#translation_needed-yes').click(function() {
		$.ajax({
			url: '/translation/start/newform',
			success: function(data) {
				$('#translation_needed_area').html(data);
				$('#translation_needed_area').removeClass('d-none');
				undertts();
			}
		})
	});

	$('#translation_needed-no').click(function() {
		$('#translation_needed_area').html('');
		$('#translation_needed_area').addClass('d-none');
		if ($('textarea[name=original_script]').length > 0) {
			$('textarea[name=original_script]').attr('name', 'tts_text');
			$('#translated_text').parent().remove();
		}
	});

	$("#tts_text_pause").on("click", function(){
		var tts_text = $('#tts_text').val();
		var tts_text_input_position = $('#tts_text_input_position').val();
		var tts_text_left = tts_text.substring(0, tts_text_input_position);
		var tts_text_right = tts_text.substring(tts_text_input_position);
		tts_text = tts_text_left + '<break time="1s"/>' + tts_text_right;
		$('#tts_text').val(tts_text);
		$("#tts_text_used").text($('#tts_text').val().length);
		$('#tts_text').focus();
	});
	
	
	
	$("#ssml_mode").change(function() {
		if(this.checked) {
          if ($('#tts_text').val() == '') {
			  $('#tts_text').val('<speak></speak>');
		  }
		}
		else {
			if ($('#tts_text').val() == '<speak></speak>') {
				$('#tts_text').val('');
			}
		}
	});



	$("#tts_btn_synthesize_to_preview").on("click", function(event){
		event.preventDefault();
		$('.is-invalid').removeClass('is-invalid');
		if ($('#translation_needed-yes').prop('checked')) {
			if ($('#translated_text').length == 0) {
				showMessage('error',"Haven't Translated", 'Please click on Translate to translate your script.', '');
				return;
			}
		}
		if ($('#tts_language').val() == 0) {
			$('#tts_language').addClass('is-invalid');
			$('html, body').animate({
				scrollTop: $("#tts_language").offset().top
			}, 1000);
			tts_list_voice_builder($("#tts_language").val(), "neural");
			return false;
		}
		$('#tts_view_text').hide();
		$("#synthesize_type").val('preview');
		my_blockUI(180);
		$.ajax({
			type: 'post',
			async: true,
			url: $("#tts_start").attr("action"),
			data: $("#tts_start").serialize(),
			success: function(data) {
				//need to code here for without refresh
				var json = JSON.parse(data);
				if (json.result) {
					$("#tts_player").attr('controlsList', "nodownload");
					setTimeout(function(){
						$.unblockUI();
						play_file(json.tts_uri, '');
					}, $("#ttsc_preview_delay").val()*1000);
				}
				else {
					$.unblockUI();
					showMessage("warning", "", json.message, "");
					return;
				}
			}
		});	
	});

function langspan() {
	$('.langspan').each(function(){
		let lc = $(this).html();
		if (supportedlang[lc] !== undefined) {
			$(this).html(supportedlang[lc]);
		}
	});
}

	$('.viewtranslation').on('click', function(){
		let toprow = $(this).parent().parent();
		let id = toprow.attr('data-id');
		let toshowdiv = $(this).parent().next();
		$.ajax({
			url: '/translation/view/'+id,
			beforeSend: function() {
				toshowdiv.html('loading...');
			},
			success: function(data) {
				toshowdiv.html(data);
				langspan();
			}
		});
	});

	$('.deletetranslation').on('click', function(){
		let toprow = $(this).parent().parent();
		toprow.addClass('table-danger');
		let id = toprow.attr('data-id');
		let csrf = toprow.attr('data-csrf');
		setTimeout(function() {
			if (confirm('Are you sure you want to delete this translation?')) {
				if (confirm('Deleting a translation does not reverse your translation credits. Are you sure?')) {
					$.ajax({
						type: 'post',
						url: '/translation/del_action',
						data: 'id='+id+'&csrf_test_name='+csrf,
						success: function() {
							toprow.remove();
						}
					});
				}
			} else {
				toprow.removeClass('table-danger');
			}
		}, 1500);
	});

	function savetx() {
		$('#save-translation').on('click', function(event) {
			event.preventDefault();
			$.ajax({
				type: 'post',
				url: '/translation/final_action',
				data: $('#translation_start').serialize(),
				success: function() {
					location.reload();
				}
			});
		});
	}

	$("#translate_text").on("click", function(event) {
		event.preventDefault();
		let t = $(this);
		$.ajax({
			type: 'post',
			async: true,
			url: $("#translation_start").attr('action'),
			data: $("#translation_start").serialize(),
			beforeSend: function() {
				t.attr('disabled', 'disabled');
				t.val('Translating...');
			},
			success: function(data) {
				let newhtml = '<textarea name="target_script" id="target_script" class="form-control" rows="5">'+ data +'</textarea>';
				$('#translated-text-area').append(newhtml);
				$('#translated-text-area').after('<input type="submit" id="save-translation" class="btn btn-primary" value="Save Translation" />');
				$('html, body').animate({
					scrollTop: $("#translated-text-area").offset().top
				}, 1000);
				savetx();
				t.remove();
			}
		});
	});

function undertts() {
	$('#translate_text_under_tts').on("click", function(event) {
		$('.is-invalid').removeClass('is-invalid');
		event.preventDefault();
		if ($('#tts_text').val().trim() == '') {
			showMessage("warning", "", "Please enter the script.", "");
			$('#tts_text').addClass('is-invalid');
			scrollto('scriptarea');
			return;
		}
		if ($('#name').val().trim() == '') {
			$('#name').addClass('is-invalid');
			scrollto('translation_needed_area');
			return;
		}
		if ($('#translate_from').val() == $('#translate_to').val()) {
			showMessage("error", "", "Original language and target language are the same. Please check the details.", "");
			$('#translate_to').addClass('is-invalid');
			$('#translate_from').addClass('is-invalid');
			scrollto('translation_needed_area');
			return;
		}
		let t = $(this);
		let original_lang = encodeURI($('#translate_from').val());
		let script = encodeURI($('#tts_text').val());

		let target_lang = encodeURI($('#translate_to').val());
		let csrf = $('input[name=csrf_test_name]').val();
		$('#tts_language').val(0);
		$.ajax({
			type: 'post',
			url: '/translation/start_action',
			data: 'csrf_test_name='+csrf+'&original_lang='+original_lang+'&target_lang='+target_lang+'&script='+script,
			beforeSend: function() {
				t.attr('disabled', 'disabled');
				t.val('Translating...');
			},
			success: function(data) {
				$('#scriptarea').append('<div class="form-group mt-3 lightblue"><label for="translated_text"><b>TRANSLATED SCRIPT</b></label><textarea class="form-control" cols="40" rows="8" name="tts_text" placeholder="Translated script" id="translated_text">'+data+'</textarea></div>');
				$('html, body').animate({
					scrollTop: $("#scriptarea").offset().top
				}, 1000);
				t.remove();
				checkTranslateText();
				$('#tts_text').attr('name', 'original_script');
			}
		});
	});
}

	function checkTranslateText(){
		var spaceCount = ($('#translated_text').val().split(" ").length - 1);
		var textWithoutSpaces = $('#translated_text').val().replace(/ /g,'')
		$("#tts_text_used").text(textWithoutSpaces.length);
		$('#tts_text_input_position').val(textWithoutSpaces.length);
		var maximum_characters = parseInt($("#tts_text_character_limit").text()) + spaceCount
		if (textWithoutSpaces.length > maximum_characters) {
			showMessage("warning", "", $('#maximum_characters_notice').val() + maximum_characters, "");
			this.value = this.value.substring(0, maximum_characters);
			$("#tts_text_used").text(maximum_characters);
		}
	}

	$("#tts_btn_synthesize_to_file").on("click", function(event){
		
		event.preventDefault();
		if ($('#tts_text').val().trim() == '') {
			showMessage("warning", "", "Please enter the script.", "");
			$('#tts_text').addClass('is-invalid');
			scrollto('scriptarea');
			return;
		}

		if ($('#tts_language').val() == 0) {
			$('#tts_language').addClass('is-invalid');
			$('html, body').animate({
				scrollTop: $("#tts_language").offset().top - 100
			}, 1000);
			showMessage("warning", "", "Choose a language", "");
			return;
		}
		my_blockUI(180);
		if ($('#translated_text').length > 0) {
			let name = encodeURI($('#name').val());
			let original_lang = $('#translate_from').val();
			let script = encodeURI($('#tts_text').val());
			let target_lang = $('#translate_to').val();
			let target_script = encodeURI($('#translated_text').val());
			let csrf = $('input[name=csrf_test_name]').val();
			$.ajax({
				type: 'post',
				url: '/translation/final_action',
				data: 'name='+name+'&script='+script+'&original_lang='+original_lang+'&target_lang='+target_lang+'&target_script='+target_script+'&csrf_test_name='+csrf,
				success: function (d) {
					console.log('translation saved');
				}
			});
		}
		$("#synthesize_type").val('save');
		$.ajax({
			type: 'post',
			url: $('#tts_start').attr('action'),
			data: $('#tts_start').serialize(),
			success: function(data) {
				var json = JSON.parse(data);
				$.unblockUI();
				if (json.result) {
					$("html, body").animate({scrollTop: $("#dataTable_list_tts").offset().top}, 500);
					$("#dataTable_list_tts").DataTable().ajax.reload();
					//update charactors_used_number here
					$("#charactors_used_number").text(parseInt($("#charactors_used_number").text()) + parseInt($('#tts_text_input_position').val()))
				}
				else {
					showMessage("warning", "", json.message, "");
					return;
				}
			}
		});	
	});
	
	
	
	$("#tts_sync_aws, #tts_sync_google, #tts_sync_azure, #bulk_enable_aws ,#bulk_disable_aws ,#bulk_delete_aws, #bulk_enable_google, #bulk_disable_google, #bulk_delete_google, #bulk_enable_azure, #bulk_disable_azure, #bulk_delete_azure, #bulk_free, #bulk_payg, #bulk_revoke").on("click", function(){
		var id = this.id;
		Swal.fire({
			text: this.value,
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Yes"
		}).then(function(result) {
			if (result.value) {
				my_blockUI(180);
				window.location.href = global_base_url + "tts/admin_resource_bulk_action/" + id;
			}
		});	
	});



	if ($("#dataTable_list_tts").length) {
		var columnDefs = [
		  {
			"targets": 0,
			"createdCell": function (td, cellData, rowData, row, col) {
				$(td).html(time_conversion(cellData, $("#timezone_offset").val(), $("#user_dateformat").val(), $("#user_timeformat").val()));
			}
		  },
		  {
			"targets": 2,
			"createdCell": function (td, cellData, rowData, row, col) {
				$(td).html(cellData.substring(0, 100) + "...");
			}
		  },
		  {
			"targets": 4,
			"createdCell": function (td, cellData, rowData, row, col) {
				var cellData_array = cellData.split(",");
				var play_btn = '<button type="button" title="Play this script" onclick="play_file(\'' + cellData_array[1] + '\', \'' + cellData_array[0] + '\')" id="btn_play1" class="btn btn-light btn-sm mr-2"><i class="fa fa-play text-gray-500"></i></button>';
				var download_btn = '<a title="Download this file" href="' + global_base_url + 'tts/download/' + cellData_array[0] + '" target="_blank" class="btn btn-light btn-sm mr-2"><i class="fa fa-download text-gray-500"></i></a>';
				var action_btn = '<a title="Delete" href="javascript:void(0)" onclick="actionQuery(\'Delete\', \'You will not be able to revert this\', \'/tts/remove/' + cellData_array[0] + '\')" class="btn btn-light btn-sm"><i class="fa fa-trash text-gray-500"></i></a>'
				//renderDataTableButton(cellData_array[0], '', '', 'tts/remove/');
				$(td).html(play_btn + download_btn + action_btn);
			}
		  }
		];
		renderDataTable('dataTable_list_tts', 'query/tts_list/', columnDefs);
	}
	
	
	
	if ($("#dataTable_list_tts_admin").length) {
		var columnDefs = [
		  {
			"targets": 3,
			"createdCell": function (td, cellData, rowData, row, col) {
				$(td).html(cellData.substring(0, 100) + "...");
			}
		  },
		{
			"targets": 5,
			"createdCell": function (td, cellData, rowData, row, col) {
				$(td).html(renderDataTableButton(cellData, 'tts/admin_tts_view/', '', 'tts/remove/'));
			}
		}
		];
		renderDataTable('dataTable_list_tts_admin', 'query/tts_list_admin/', columnDefs);
	}
	
		
		
	if ($("#dataTable_tts_resource").length) {
		var columnDefs = [
		  {
			"targets": 0,
			"createdCell": function (td, cellData, rowData, row, col) {
				switch (cellData) {
					case '1' :
					  $(td).html('<span class="badge badge-success">Enabled</span>');
					  break;
					case '0' :
					  $(td).html('<span class="badge badge-warning">Disabled</span>');
					  break;
				}
			}
		  },
		  {
			"targets": 7,
			"createdCell": function (td, cellData, rowData, row, col) {
				$(td).html('<a href="' + global_base_url + 'tts/admin_resource_edit/' + cellData + '" target="_blank" class="btn btn-light btn-sm"><i class="fa fa-edit text-gray-500"></i></a>');
			}
		  }
		];
		renderDataTable('dataTable_tts_resource', 'query/tts_resource/', columnDefs);
	}
	
	
	
	$("#tts_listen_modal").on('hidden.bs.modal', function () {
		var player = document.getElementById("tts_player");
		player.pause();
	});
	
	
	
	if ($("#item_type").length && $("#purchase_times").length) {  //item_detail page loaded, for legacy reason ,need to check
		var act = $("#item_type").val();
		if (act == 'purchase') {
			$("#payment_item_row_recurring").hide();
			$("#renew_action option[value=3]").hide();
			$("#renew_action option[value=4]").hide();
		}
		else if (act == 'top-up') {
			$("#payment_item_row_recurring").hide();
			$("#payment_item_row_characters_limit").hide();
			$("#payment_item_row_actions").hide();
		}
		else if (act == 'subscription') {
			$("#payment_item_row_recurring").show();
			$("#renew_action option[value=1]").hide();
			$("#renew_action option[value=2]").hide();
		}
	}
	
	
	
	$("#item_type").on("change", function(){
		if ($("#purchase_times").length) {  //it's at item_detail page, for legacy reason ,need to check
			var act = $("#item_type").val();
			if (act == 'purchase') {
				$("#payment_item_row_recurring").hide();
				$("#payment_item_row_characters_limit").show();
				$("#payment_item_row_actions").show();
				$("#renew_action option[value=1]").show();
				$("#renew_action option[value=2]").show();
				$("#renew_action option[value=3]").hide();
				$("#renew_action option[value=4]").hide();
				$("#renew_action").val(1);
			}
			else if (act == 'top-up') {
				$("#payment_item_row_recurring").hide();
				$("#payment_item_row_characters_limit").hide();
				$("#payment_item_row_actions").hide();
			}
			else if (act == 'subscription') {
				$("#payment_item_row_recurring").show();
				$("#payment_item_row_characters_limit").show();
				$("#payment_item_row_actions").show();
				$("#renew_action option[value=1]").hide();
				$("#renew_action option[value=2]").hide();
				$("#renew_action option[value=3]").show();
				$("#renew_action option[value=4]").show();
				$("#renew_action").val(3);
			}
		}
	});
	
	
	if (enabledEngine == 'both' || enabledEngine == 'standard') {
		tts_list_voice_builder($("#tts_hidden_current_language").val(), "standard");  //init when page is loaded
	}
	else {
		tts_list_voice_builder($("#tts_hidden_current_language").val(), "neural");  //init when page is loaded
	}
	
})(jQuery);



function tts_list_voice_builder(language_code, engine) {
	var voiceRadio = "", radio_id, radio_count = 0, engines, activate, checked, checked_set = false, tts_example_url, tts_example_field, placeholder = 'place_holder', div_col;
	if ($("#tts_voice_list").html() != "") {
		$("#tts_voice_list").html('');
	}
	$.each(tts_language_list_json, function(key, detail){
		engines = detail["engines"];
		if (detail["language_code"] == language_code) {
				voiceRadio += '<option value="' + detail['ids'] + '">' + detail["gender"] + ', ' + detail["name"] + '</option>';
				radio_count++;
		}
	});
	$("#tts_voice_list").html(voiceRadio);
	$('#tts_resource_ids').val($('#tts_voice_list').val());
}



function play_example(ids) {
	$("#tts_example_play_button_" + ids).attr("class", "far fa-stop-circle ml-2 text-gray-500 hand-cursor");
	var audio = document.getElementById("tts_example_audio_" + ids);
	$("#tts_example_audio_" + ids).on("ended", function() {
		$("#tts_example_play_button_" + ids).attr("class", "far fa-play-circle ml-2 text-gray-500 hand-cursor");
	});
	audio.play();
}



function play_file(uri, ids) {
	$('#tts_listen_modal').modal('show');
	if (ids != '') {
		$("#tts_player").attr('controlsList', "");
		$('#tts_view_text').show();
		$('#tts_view_text').attr("href", global_base_url + "tts/view/" + ids);
	}
	$("#tts_player").attr("src", uri).trigger("play");
}


if (document.getElementsByClassName('deletepromocode').length > 0) {
	$('.deletepromocode').on('click', function(){
		let toprow = $(this).parent().parent();
		toprow.addClass('table-danger');
		let id = toprow.attr('data-id');
		let csrf = toprow.attr('data-csrf');
		setTimeout(function() {
			if (confirm('Are you sure you want to delete this code?')) {
				if (confirm('Deleting this code will not affect existing customers.')) {
					$.ajax({
						type: 'post',
						url: '/admin/promocode_delete',
						data: 'couponid='+id+'&csrf_test_name='+csrf,
						success: function() {
							toprow.remove();
						}
					});
				}
			} else {
				toprow.removeClass('table-danger');
			}
		}, 1500);
	});
}

