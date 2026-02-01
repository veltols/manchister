</article>

<!-- ARTICLE END -->



<footer>
</footer>



<div id="siteAlerter" class="siteAlertContainer siteAlertHidden01 siteAlertHidden02">
	<div class="siteAlertBG"></div>
	<div class="siteAlertBody">
		<div class="flexer">
			<i class="fa-solid fa-circle-check"></i>
			<i class="fa-solid fa-circle-xmark"></i>
			<p>whatever message is here</p>
			<div onclick="closeSiteAlert();" class="alertBtner"><?= lang("Done"); ?></div>
		</div>
	</div>
</div>
<div id="siteNot" onclick="closeSiteNot();">
	<i class="fa-solid fa-circle-check"></i>
	<p>whatever message is here</p>
</div>
<!-- datepicker css -->
<?php
/*
<script src="../../public/assets/js/form_submissions.js"></script>
*/
?>
<script src="<?= $POINTER; ?>../public/assets/js/form_submissions.js"></script>
<script type="text/javascript" src="<?= $POINTER; ?>../public/assets/js/jquery_datepicker.js"></script>
<link type="text/css" rel="stylesheet" href="<?= $POINTER; ?>../public/assets/js/jquery_datepicker.css">

<script>

	function getInt(vv) {
		var tI = parseInt(vv);
		if (isNaN(tI)) {
			return 0;
		} else {
			return tI;
		}
	}

	/*
	function do_slide_effects(){
		
		var HH = parseInt( $('header').css('height') );
		var header_h = $('header').height();
		var topper = parseInt($(document).scrollTop());
		if(topper >= HH){
			$('header').addClass('headerScrolled');
		} else {
			$('header').removeClass('headerScrolled');
		}
		
	}
	$(document).on('scroll', function(){ do_slide_effects(); });
	*/



	var onCall = false;
	function sendPostToBindData(loadContainer, dataToSend, urlWithoutPointer) {
		if (onCall == false) {
			onCall = true;
			start_loader(loadContainer);
			$.ajax({
				type: 'POST',
				url: '<?= $POINTER; ?>' + urlWithoutPointer,
				dataType: "JSON",
				data: dataToSend,
				success: function (responser) {
					onCall = false;
					end_loader(loadContainer);
					if (responser[0].success == true && responser[0].data) {
						bindData(responser[0].data);
					} else {
						makeSiteAlert('err', "General Error, please try later !!");
					}
				},
				error: function () {
					onCall = false;
					end_loader(loadContainer);
					makeSiteAlert('err', "General Error, please try later !");
				}
			});
		}
	}


	//AUTORUN


	setTimeout(function () {
		set_loader(100);
	}, 500);

	function makeSiteNot(mess) {
		$('#siteNot').addClass('siteNot-Showed');
		$('#siteNot p').text(mess);
		setTimeout(function () {
			$('#siteNot').removeClass('siteNot-Showed');
		}, 3000);
	}
	function closeSiteNot() {
		$('#siteNot').removeClass('siteNot-Showed');
	}
	function makeSiteAlert(typo, mess) {
		if (typo == 'suc') {
			// $('#siteAlerter').addClass('siteAlert-gd');
			makeSiteNot(mess);
		} else {
			$('#siteAlerter').addClass('siteAlert-bd');
			$('#siteAlerter p').text(mess);


			$('#siteAlerter').removeClass('siteAlertHidden01');
			setTimeout(function () {
				$('#siteAlerter').removeClass('siteAlertHidden02');
			}, 200);
		}
	}

	function closeSiteAlert() {
		$('#siteAlerter').addClass('siteAlertHidden02');
		setTimeout(function () {
			$('#siteAlerter').addClass('siteAlertHidden01');
			$('#siteAlerter').removeClass('siteAlert-bd');
			$('#siteAlerter').removeClass('siteAlert-gd');
			$('#siteAlerter p').text('');
		}, 600);
	}
</script>

<div id="freeContainer" style="display:none"></div>


<script>
	end_loader();
</script>



<script>
	var screenWidth = $(window).width();
	console.log(screenWidth);
	if (screenWidth <= 800) {
		toggleAside();
	}
</script>

<?php
//include("app/form_controller.php");
?>
</body>

</html>