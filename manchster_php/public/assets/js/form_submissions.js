
/* -- AJAX FUNCTIONS -- */
var submission_res = '';
var form_processed = '';
var form_processed_callback = '';
var modal_processed = '';
var details_processed = '';
var modal_processed_alerter = '';
var btnOrg = '';
var alerts_C = 12;

function mk_alert( alertTxt, alertType, alertDistenation ){
	console.log("alerted");
	alerts_C++;
	if( alertDistenation != '' ){
		var txtFA = '';
		var txtClass = '';
		if( alertType == 'suc' ){
			txtFA = '<i class="fas fa-check"></i>';
			txtClass = 'success';
		} else if( alertType == 'err' ){
			txtFA = '<i class="fas fa-times"></i>';
			txtClass = 'error';
		} else if( alertType == 'warn' ){
			txtFA = '<i class="fas fa-exclamation-circle"></i>';
			txtClass = 'warning';
		}
		var alertData = '<span id="alerter-' + alerts_C + '" onclick="rem_alert(' + alerts_C + ');" class="' + txtClass + '">' + txtFA + ' <p>' + alertTxt + '</p></span>';
		$(alertDistenation).append(alertData);
	}
	
}

function rem_alert( alertID ){
	$('#alerter-' + alertID).remove();
}

function submit_form(frm_id, res, contoller){
	//save editor data
	//tinyMCE.triggerSave();
	var wrongsCount = 0;
	submission_res = res;
	var formdata = new FormData();
	form_processed = '#' + frm_id;
	var URLer = $(form_processed).attr( 'contoller' );
	//alert(form_processed);
	form_processed_callback = $(form_processed).attr( 'id-callback' );
	modal_processed = $(form_processed).attr('id-modal');
	details_processed = $(form_processed).attr('id-details');
	modal_processed_alerter = '#' + modal_processed + ' .form-alerts';
	console.log(modal_processed_alerter);
	$(modal_processed_alerter).html('');
	btnOrg = $('#' + modal_processed + '  .modalAction .actionBtn').html();
	$('#' + modal_processed + '  .modalAction .actionBtn').html('LOADING...');
	$('#' + modal_processed + '  .modalAction .actionBtn').prop('disabled', true);
	//tinyMCE.triggerSave();
	console.log('submission started == ' + frm_id);
	var gate = true;
	$(form_processed + ' .data-input').each(function(){
		$(this).removeClass('inputHasError');
		//collect entry data
		var ths_id = $(this).attr('id');
		var viewid = ths_id;
		if( $(this).attr('viewid') ){
			viewid = $(this).attr('viewid');
		}
		if( viewid == '' || viewid == null || viewid == 'undefined' ){
			viewid = ths_id;
		}
		 console.log(viewid);
		 // alert(viewid);
		$('#'+viewid).removeClass('inputHasError');
		
		var ths_name = $(this).attr('name');
		
		var ths_den = $(this).attr('denier');
		var ths_alert = $(this).attr('alerter');
		var ths_req = parseInt($(this).attr('req'));
		
		var ths_default = "";
		if( $(this).attr('defaulter') ){
			ths_default = $(this).attr('defaulter');
		}
		
		
		var ths_tag = $(this).prop("tagName").toLowerCase();
		
		var ths_val = "";
		if( $(this).val() ){
			ths_val = $(this).val().trim();
		}
		// alert(ths_val);
		
		var ths_namer = capitalizer(ths_name.replace('_', ' '));
		if( ths_alert == '' || ths_alert == null || ths_alert == 'undefined' ){
			
			if( lang_db == '_ar' ){
				ths_alert = "الرجاء التأكد من المدخلات";
			} else {
				ths_alert = "Please_Check_Inputs" + '( '+ ths_namer +' )';
			}
		}
		
		
		var ths_type = '';
		// alert(ths_tag);
		//define element type
		if(ths_tag == 'input'){
			ths_type = $(this).attr('type');
		} else {
			ths_type = ths_tag;
		}
		
		if(ths_type == 'file'){
			//its file
			if (($("#" + ths_id))[0].files.length> 0) {
				var filer = ($("#" + ths_id))[0].files[0];
				formdata.append(ths_name, filer);
			} else {
				if(ths_req == 1){
					gate = false;
					mk_alert(ths_alert, 'err', modal_processed_alerter);
					wrongsCount++;
				}
			}
		} else {
			//its input, select, textarea
			if(ths_val != ths_den){
				if( ths_val != "" ){
					formdata.append(ths_name, ths_val);
				} else {
					if(ths_req == 1){
						if( wrongsCount <= 3 ){
							mk_alert(ths_alert, 'err', modal_processed_alerter);
						}
						
						$('#'+viewid).addClass('inputHasError');
						wrongsCount++;
					} else {
						formdata.append(ths_name, ths_default);
					}
				}
				
			} else {
				if(ths_req == 1){
					if( wrongsCount <= 3 ){
						mk_alert(ths_alert, 'err', modal_processed_alerter);
					}
					
					$('#'+viewid).addClass('inputHasError');
					wrongsCount++;
				} else {
					formdata.append(ths_name, ths_default);
				}
			}
		}
		
	});
	
	// gate = false;
	if( wrongsCount == 0 ){
		var ajax = new XMLHttpRequest();
		ajax.upload.addEventListener("progress", progressHandlerAB, false);
		ajax.addEventListener("load", completeHandlerAB, false);
		ajax.addEventListener("error", errorHandlerAB, false);
		ajax.addEventListener("abort", abortHandlerAB, false);
		ajax.open("POST", URLer);
		if(gate == true){
			//send data to ajax
			//closeModal();
			start_loader();
			ajax.send(formdata);
		}
	} else {
			$('#' + modal_processed + '  .modalAction .actionBtn').html( btnOrg );
			$('#' + modal_processed + '  .modalAction .actionBtn').prop('disabled', false);
	}
}



function capitalizer(str)
{
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

function clear_form(frm){
	
	$('#' + frm + ' .data-input').each(function(){
		$(this).removeClass('inputHasError');
		//collect entry data
		var ths_id = $(this).attr('id');
		var ths_name = $(this).attr('name');
		var ths_den = $(this).attr('denier');
		var ths_alert = $(this).attr('alerter');
		var ths_default = $(this).attr('defaulter');
		var ths_req = parseInt($(this).attr('req'));
		var ths_val = $(this).val();
		var ths_tag = $(this).prop("tagName").toLowerCase();
		var ths_type = '';
		
		//clear element type based on type
		if(ths_tag == 'input' || ths_tag == 'textarea'){
			$(this).val('');
		} else if(ths_tag == 'select'){
			$(this).val(0);
		} else {
			$(this).val('');
		}

	});
	
}





function progressHandlerAB(event){
	var percent = (event.loaded / event.total) * 100;
	set_loader(percent);
}
function completeHandlerAB(event){
	
	
	
	var responser = JSON.parse(event.target.responseText);
	
	$('#' + modal_processed + '  .modalAction .actionBtn').html( btnOrg );
	$('#' + modal_processed + '  .modalAction .actionBtn').prop('disabled', false);
			
	if( responser[0].success == true){
		
		makeSiteAlert('suc', 'Changes Saved');
		
		
		if (typeof afterFormSubmission02 === "function") {
			afterFormSubmission02(responser[0].message);
		} else {
			if (typeof afterFormSubmission === "function") {
				setTimeout( function(){
					afterFormSubmission(responser[0].message);
				}, 1000 );
			} else {
				end_loader('article');
			}

		}
		
		
		
		
		
	} else {
		end_loader('article');
		makeSiteAlert('err', responser[0].message);
	}
	
	
	
		
}
function errorHandlerAB(event){
	var responser = event.target.responseText;
		makeSiteAlert('err', "Upload Failed, please check your inputs 4656545687");
	alert(responser);
}
function abortHandlerAB(event){
	var responser = event.target.responseText;
		makeSiteAlert('err', "Upload Aborted by user");
	alert(responser);
}

