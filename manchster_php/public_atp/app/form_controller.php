<script>


	// Restricts input for the set of matched elements to the given inputFilter function.
	(function ($) {
		$.fn.inputFilter = function (callback, errMsg) {
			return this.on("input keydown keyup mousedown mouseup select contextmenu drop focusout", function (e) {
				if (callback(this.value)) {
					// Accepted value
					if (["keydown", "mousedown", "focusout"].indexOf(e.type) >= 0) {
						$(this).removeClass("input-error");
						this.setCustomValidity("");
					}
					this.oldValue = this.value;
					this.oldSelectionStart = this.selectionStart;
					this.oldSelectionEnd = this.selectionEnd;
				} else if (this.hasOwnProperty("oldValue")) {
					// Rejected value - restore the previous one
					$(this).addClass("input-error");
					this.setCustomValidity(errMsg);
					this.reportValidity();
					this.value = this.oldValue;
					this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
				} else {
					// Rejected value - nothing to restore
					this.value = "";
				}
			});
		};
	}(jQuery));


	function doOnlyNumbers() {
		$(".onlyNumbers").inputFilter(function (value) {
			return /^\d*$/.test(value);    // Allow digits only, using a RegExp
		}, "Only digits allowed");
	};


	function capitalizer(str) {
		return str.replace(/\w\S*/g, function (txt) { return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase(); });
	}









	function initForms() {

		//loop to add ids
		var idd = 100;
		$('.inputer').each(function () {
			//formGroup
			$(this).closest('.formGroup').attr('id', 'inputerContainer-' + idd);
			$(this).attr('id', 'inputer-' + idd);
			$(this).attr('data-ids', idd);
			idd++;
		});

		//to bind event on input change for extra functionality
		$('.inputer').on('change input', function () {
			if (typeof extraBind === "function") {
				setTimeout(function () {
					extraBind();
				}, 100);
			}
		});

		//to bind dates inputs
		do_date_picker();

		//get select data
		$('.need_data').each(function () {
			//formGroup
			var thsId = '' + $(this).attr('id');
			getSelectData(thsId);

		});
		doOnlyNumbers();
	}


	function getSelectData(selectId) {
		//console.log('s=============' + selectId);
		var thsAPI = '' + $('#' + selectId).attr('data-from');
		var thsDest = '' + $('#' + selectId).attr('data-dest');
		var thsDt = '' + $('#' + selectId).attr('data-initial');


		if (thsAPI != '') {
			if (thsAPI != 'undefined') {
				activeRequest = true;
				start_loader('article');
				$.ajax({
					url: thsAPI,
					dataType: "JSON",
					method: "POST",
					data: { "data_dest": thsDest },
					success: function (RES) {
						var itm = RES[0];
						//console.log(itm);
						activeRequest = false;
						end_loader('article');
						$('#' + selectId).html('');
						if (itm['success'] == true) {
							var thsSelData = itm['data'];
							for (i = 0; i < thsSelData.length; i++) {
								var thsRec = thsSelData[i];
								var nwOpt = '<option value="' + thsRec['idder'] + '">' + thsRec['namer'] + '</option>';
								$('#' + selectId).append(nwOpt);
							}
							$('#' + selectId).val(thsDt);
						} else {
							alert("Failed to load data - 254");
						}
					},
					error: function (res) {
						activeRequest = false;
						end_loader('article');
						console.log(res);
						alert("Failed to load data");
					}
				});
			}
		}
	}


	function saveFormChanges(pageController) {
		var formdata = new FormData();
		var errorCount = 0;
		var notifiedUser = 0;
		$('.inputer').each(function () {

			//collect data
			var namer = $(this).attr('data-name').trim();

			var thsGoodNamer = capitalizer(namer.replace('_', ' '));

			var thsId = $(this).attr('data-ids').trim();
			var denier = $(this).attr('data-den').trim();
			var isRqrd = parseInt($(this).attr('data-req'));
			var isTime = parseInt($(this).attr('data-time'));
			var typer = $(this).attr('data-type').trim();

			console.log("Start========================================= " + thsGoodNamer + '---T===' + typer);

			var thsErrCount = 0;
			if (typer == 'file') {
				//ITS A FILE
				if (($("#inputer-" + thsId))[0].files.length > 0) {
					var filer = ($("#inputer-" + thsId))[0].files[0];
					formdata.append(namer, filer);
				} else {
					if (isRqrd == 1) {
						errorCount++;
						thsErrCount++;
						console.log('Denier Error');
					}
				}
				if (thsErrCount != 0) {
					$('#inputer-' + thsId).addClass('inputHasError');
				} else {
					clearResetItem(thsId);
				}





			} else {
				//NOT A FILE
				var tshVal = $(this).val().trim();
				//check if empty or no
				if (tshVal == "") {
					//check if required
					if (isRqrd == 1) {
						errorCount++;
						thsErrCount++;
						console.log(thsGoodNamer + '====required');
					}
				} else {
					//sanitize and validate
					if (isTime == 1) {
						typer = 'time';
					}
					console.log('VAL==============' + tshVal);
					if (validateData(tshVal, typer) == false) {
						errorCount++;
						thsErrCount++;
						console.log('Validate Error');
						if (typer == 'password' && notifiedUser == 0) {
							notifiedUser = 100;
							makeSiteAlert('err', '<?= lang("Password does not meet minimum requirements"); ?>');
						}
					}
				}
				if (isRqrd == 1) {
					if (tshVal == denier) {
						errorCount++;
						thsErrCount++;
						console.log('Denier Error');
					}
				}

				if (thsErrCount != 0) {
					$('#inputer-' + thsId).addClass('inputHasError');
				} else {
					clearResetItem(thsId);
					formdata.append(namer, tshVal);
				}
			}

			console.log("End========================================= " + thsGoodNamer);


		});

		if (errorCount == 0) {
			// console.log('sent to server');
			//send to server
			if (formdata) {
				var ajax = new XMLHttpRequest();
				ajax.upload.addEventListener("progress", progressHandlerA, false);
				ajax.addEventListener("load", completeHandlerA, false);
				ajax.addEventListener("error", errorHandlerA, false);
				ajax.addEventListener("abort", abortHandlerA, false);
				ajax.open("POST", pageController);
				start_loader('article');
				setTimeout(function () {
					ajax.send(formdata);
				}, 500);

			}

		} else {
			//has error
			makeSiteAlert('err', '<?= lang("Please_check_your_inputs!"); ?>');
			end_loader('article');
		}
	}

	function clearResetItem(idd) {
		$('#inputer-' + idd).removeClass('inputHasError');
	}

	function validateData(val, typer) {

		if (typer == 'int' || typer == 'number') {
			return /^\d+$/.test(val);
		} else if (typer == 'hidden') {
			return /^[a-zA-Z\s\d]+$/.test(val);
		} else if (typer == 'text' || typer == 'text_mixed') {

			// var re = /^\w+$/;
			// var re = /^[ +a-zA-Z0-9._-]+$/;
			var re = /^[ a-zA-Z0-9!@#\$%\^\&*\)\(+=._-\s\S]+$/g;
			return re.test(val);

		} else if (typer == 'float') {
			return !isNaN(val);
		} else if (typer == 'time') {
			//return /^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/.test(val);
			return true
		} else if (typer == 'password') {
			return /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/.test(val);
		} else if (typer == 'email') {
			var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			return regex.test(val);
		} else if (typer == 'phone') {
			return /^\d+$/.test(val);
		} else if (typer == 'date') {
			//var dateRegex = /^(?=\d)(?:(?:31(?!.(?:0?[2469]|11))|(?:30|29)(?!.0?2)|29(?=.0?2.(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(?:\x20|$))|(?:2[0-8]|1\d|0?[1-9]))([-.\/])(?:1[012]|0?[1-9])\1(?:1[6-9]|[2-9]\d)?\d\d(?:(?=\x20\d)\x20|$))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\x20[AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/;
			//return dateRegex.test(val);
			return true;
		} else if (typer == 'file') {
			return true;
		} else {
			return false;
		}
	}











	function do_date_picker() {
		$(".has_date").datepicker({
			dateFormat: "yy-mm-dd",
			changeYear: true,
			changeMonth: true,
			yearRange: "<?= date('Y'); ?>:<?= date('Y') + 5; ?>"
		});
		$(".has_long_date").datepicker({
			dateFormat: "yy-mm-dd",
			changeYear: true,
			changeMonth: true,
			yearRange: "<?= date('Y') - 90; ?>:<?= date('Y'); ?>"
		});
		$(".has_future_date").datepicker({
			dateFormat: "yy-mm-dd",
			changeYear: true,
			changeMonth: true,
			minDate: 1,
			yearRange: "<?= date('Y'); ?>:<?= date('Y') + 10; ?>"
		});
	}








	function progressHandlerA(event) {
		var percent = (event.loaded / event.total) * 100;
		set_loader(percent);
	}
	function completeHandlerA(event) {

		var responser = JSON.parse(event.target.responseText);
		if (responser[0].success == true) {

			makeSiteAlert('suc', '<?= lang("Changes Saved"); ?>');

			if (typeof afterFormSubmission === "function") {
				setTimeout(function () {
					afterFormSubmission();
				}, 1000);
			} else {
				end_loader('article');
			}

		} else {
			end_loader('article');
			makeSiteAlert('err', responser[0].message);
		}
	}
	function errorHandlerA(event) {
		end_loader('article');
		// var responser = event.target.responseText;
		makeSiteAlert('err', "Failed, General Error-232");
	}
	function abortHandlerA(event) {
		end_loader('article');
		// var responser = event.target.responseText;
		makeSiteAlert('err', "Aborted by user");
	}





	//AUTORUN
	initForms();
	doOnlyNumbers();






</script>