</article>

<!-- ARTICLE END -->



<footer>
</footer>

<script>

	//Tabs functions START ----------------------------------
	function switchTab(tId) {
		$('.tabHeader').removeClass('tabHeaderHidden');

		$('.tabItemActive').removeClass('tabItemActive');
		$('.tabHeaderItems #tabHeader-' + tId).addClass('tabItemActive');

		$('.tabBody .tabBodyItemActive').removeClass('tabBodyItemActive');
		$('.tabBody #tabBody-' + tId).addClass('tabBodyItemActive');
	}
	//Tabs functions END ----------------------------------

</script>

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

<?php
/*
<script src="<?= $POINTER; ?>../public/assets/js/form_submissions.js"></script>
*/
?>
<script type="text/javascript" src="<?= $POINTER; ?>../public/assets/js/jquery_datepicker.js"></script>
<link type="text/css" rel="stylesheet" href="<?= $POINTER; ?>../public/assets/js/jquery_datepicker.css">

<script>

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

	function getInt(vv) {
		var tI = parseInt(vv);
		if (isNaN(tI)) {
			return 0;
		} else {
			return tI;
		}
	}




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
						makeSiteAlert('err', responser[0].message);
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






	$('#notsBadger').hide();
	$('#chatsBadger').hide();
	var localNots = 0;
	var localChats = 0;
	var lastCount = 0;
/*

    async function listen() {
      try {
		console.log('started');
        const res = await fetch('<?= $POINTER; ?>get_unseen_notifications_list?last_count=' + lastCount);
        if (res.status === 200) {

			const data = await res.json();
			
			var totNots = getInt(data[0].new_notifications);
			var totChats = getInt(data[0].new_chats);
			if (totNots > 0) {
				$('#notsBadger').text(totNots);
				$('#notsBadger').show();
			} else {
				$('#notsBadger').hide();
			}
			if (totChats > 0) {
				$('#chatsBadger').text(totChats);
				$('#chatsBadger').show();
			} else {
				$('#chatsBadger').hide();
			}
			if (localChats != totChats) {
				//$('#notificationAudio')[0].play();
			}
			if (localNots != totNots) {
				//$('#notificationAudio')[0].play();
			}

			localChats = totChats;
			localNots = totNots;

			lastCount = totChats + totNots;




        }
			setTimeout(() => {
				listen(); // immediately re-listen
			}, 1500);

      } catch (err) {
        console.error('Disconnected, retrying...');
        setTimeout(listen, 2000);
      }
    }

    listen();

*/



	function getNewNotifications() {
		$.ajax({
			type: 'POST',
			url: '<?= $POINTER; ?>get_unseen_notifications_list',
			dataType: "JSON",
			data: { 'no_data': 0 },
			success: function (responser) {
				//end_loader('article');
				if (responser[0].success == true) {
					//var data = responser[0].data;
					var totNots = getInt(responser[0].new_notifications);
					if (totNots > 0) {
						$('#notsBadger').text(totNots);
						$('#notsBadger').show();
					} else {
						$('#notsBadger').hide();
					}

				}
			},
			error: function () {

				//end_loader('article');
				//makeSiteAlert('err', "General Error, please try later !");
			}
		});
	}

	<?php
	if (
		$is_NOTIFICATIONS == true
	) {
		?>
		setInterval(function () {
			getNewNotifications();
		}, 5000);
		<?php
	}
	?>

getNewNotifications();
</script>



<script>
	end_loader();
</script>



<script>
	function hideAllAmMnus() {
		$('.extraMenu').addClass('extraMenuHidden');
		$('#extraMnuDarker').addClass('extraMnuDarkerHidden');
	}
	function showSmallMnu(mnId) {
		hideAllAmMnus();
		$('#em-' + mnId).removeClass('extraMenuHidden');
		$('#extraMnuDarker').removeClass('extraMnuDarkerHidden');
	}
</script>
<script>
	var screenWidth = $(window).width();
	//console.log(screenWidth);

	$('#newMessageContent').keypress(function (e) {
		var key = e.which;
		if (key == 13)  // the enter key code
		{
			if (e.shiftKey) {
				// new line
			}
			else {
				sendChatMessage();
			}

		}
	});
</script>
<div id="extraMnuDarker" onclick="hideAllAmMnus();" class="extraMnuDarkerHidden"></div>
<audio id="notificationAudio" src="<?= $POINTER; ?>../uploads/not.wav"></audio>
<script>
	
	function do_slide_effects() {

		var HH = parseInt($('.userLiner').css('height'));

		var header_h = $('.userLiner').height();
		var topper = parseInt($(document).scrollTop());
		if (topper >= HH) {
			$('.userLiner').addClass('userLinerScrolled');
		} else {
			$('.userLiner').removeClass('userLinerScrolled');
		}
		
	}



	$(document).on('scroll', function () { do_slide_effects(); });
	do_slide_effects();

	function fixTableTh(){
		$('.tr .th').each( function(){
			var thsTxt = $(this).html().trim();
			if( thsTxt != '' ){
				$(this).html('<span>' + thsTxt + '</span>');
			}
		} );
	}
	fixTableTh();
</script>
</body>

</html>