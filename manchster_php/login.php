<?php
$page_title = "iqc - Login";
$idder = $passer = "";


$DARK_COLOR = "#7b6231";
$LIGHT_COLOR = "#c38f2a";
$ONDARK_COLOR = "#FFFFFF";
$ONLIGHT_COLOR = "#FFFFFF";

?>
<!DOCTYPE html>
<html dir="<?= $lang_dir; ?>" lang="<?= $lang; ?>">

<head>
	<title><?= $page_title; ?></title>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">
	<meta http-equiv="content-language" content="<?= $lang; ?>">
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="description" content="<?= $page_title; ?>">
	<!-- Font -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link
		href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Tajawal:wght@400;500;700&display=swap"
		rel="stylesheet">
	<style>
		/* Custom Colors */
		:root {
			--primary:
				<?= $DARK_COLOR; ?>
			;
		}

		:root {
			--secondary:
				<?= $LIGHT_COLOR; ?>
			;
		}

		:root {
			--on-primary:
				<?= $ONDARK_COLOR; ?>
			;
		}

		:root {
			--on-secondary:
				<?= $ONLIGHT_COLOR; ?>
			;
		}
	</style>
	<!-- PC CSS -->
	<link href="assets/styles/theme.css" rel="stylesheet" media="all">
	<link href="assets/styles/pc.css" rel="stylesheet" media="(min-width:1026px) and (max-width:3500px)">
	<!-- tablet CSS -->
	<link href="assets/styles/tablet.css" rel="stylesheet" media="(min-width:700px) and (max-width:1025px)">
	<!-- mob CSS -->
	<link href="assets/styles/mob.css" rel="stylesheet" media="(min-width:100px) and (max-width:699px)">

	<!-- font awesome -->
	<link href="assets/fa/css/all.css" rel="stylesheet">

	<!-- Jquery files -->
	<script type="text/javascript" src="assets/js/jquery-3.6.4.min.js"></script>
	<script type="text/javascript" src="assets/js/cookies.js"></script>


	<script>
		var lang_db = '<?= $lang_db; ?>';
	</script>

</head>

<body>

	<div class="loginPanel">

		<div class="logoCont">
			<img src="uploads/logo.png" alt="iqc logo" />
			<br>
		</div>

		<div class="labelErr" id="labelErr">
			<?= lang("Email_or_password_error"); ?>
		</div>
		<div class="formInputs" id="btnerPlacer">
			<input type="hidden" id="token" name="token" />
			<div class="formGroup idder">
				<input type="text" name="" value="<?= $idder; ?>" placeholder="<?= lang("Username"); ?>" />
			</div>
			<div class="formGroup passer inputHidden">
				<input type="password" name="" value="<?= $passer; ?>" placeholder="<?= lang("Password"); ?>" />
			</div>
			<div class="formGroup">
				<button type="button" onclick="chkInputs();"><?= lang(data: "Login"); ?></button>
			</div>
			<div class="formGroup inputHidden" id="cancelBtn">
				<button type="button" onclick="hidePass();"><?= lang(data: "Cancel"); ?></button>
			</div>
		</div>
	</div>





	<div class="patternContainer blurMe">
		<div class="pattern" style="background: URL('uploads/pattern04.png');"></div>
	</div>


	<!--img src="uploads/connect_text.png" class="connectLogo" alt="" -->



	<script>
		var prog = 0;
		var tkn = $('#token');
		var idd = $('.idder input');
		var pass = $('.passer input');
		var btner = $('#actionBtn');
		var btnerOrg = btner.html();
		var isLoad = false;
		var userType = "";
		//<i class="fa-solid fa-loader fa-spin"></i>
		function chkInputs() {
			console.log('ss');
			resetInputs();
			if (isLoad == false) {
				console.log('dd');

				if (prog == 0 && idd.length > 0) {
					if (validateEmail(idd.val())) {
						btner.html('<img src="uploads/loader.gif" alt="loading" class="loaderImg">');
						isLoad = true;
						$('.loginPanel').addClass('blured');
						$.ajax({
							url: 'get_auth_token',
							data: { 'idder': idd.val() },
							success: function (data) {
								$('.loginPanel').removeClass('blured');
								if (data[0].success == true) {
									tkn.val(data[0].token);
									showPass();
									btner.html(btnerOrg);
									isLoad = false;
									prog = 1;
								} else {
									$('.loginPanel').removeClass('blured');
									tkn.val("");
									hidePass();
								}
							},
							error: function (data) {
								$('.loginPanel').removeClass('blured');
								isLoad = false;
							}
						});


					} else {
						emailError();
					}
				} else if (prog == 1 && idd.length > 0 && pass.length > 0 && tkn.length > 0) {

					if (validateEmail(idd.val()) && pass.val() != "") {
						btner.html('<img src="uploads/loader.gif" alt="loading" class="loaderImg">');
						isLoad = true;
						$('.loginPanel').addClass('blured');
						$.ajax({
							url: 'get_auth_user',
							data: { 'idder': idd.val(), 'passer': pass.val(), 'token': tkn.val() },
							success: function (data) {
								$('.loginPanel').removeClass('blured');
								isLoad = false;
								if (data[0].success == true) {
									var thsTkn = data[0].token;
									userType = data[0].user_type;
									setCookie('iqc_user', thsTkn);
									sendToPortal();
								} else {
									emailError();
								}

							},
							error: function (data) {
								$('.loginPanel').removeClass('blured');
								isLoad = false;
								emailError();
							}
						});


					} else {
						emailError();
					}

				} else {
					console.log('ff');
				}

			}
		}

		function showPass() {
			idd.prop('disabled', true);
			$('#cancelBtn').addClass('cancelBtnShowed');
			$('.passer').removeClass('inputHidden');
			$('#cancelBtn').removeClass('inputHidden');
		}
		function hidePass() {
			prog = 0;
			resetInputs();
			idd.prop('disabled', false);
			$('#cancelBtn').addClass('inputHidden');
			$('.passer').addClass('inputHidden');
		}


		function sendToPortal() {
			if (userType != "") {
				window.location.replace(userType + "/dashboard/");
			} else {
				alert("Error 145");
			}
		}


		function resetInputs() {
			$('#labelErr').removeClass('labelErrShowed');
			$('.hasError').each(function () {
				$(this).removeClass('hasError');
			});
		}
		function emailError() {
			idd.addClass('hasError');
			$('#labelErr').addClass('labelErrShowed');
			alert('User or password error!');
		}
		/*
				const validateEmail = (email) => {
					return email.match(
						/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
					);
				};
				*/
		function validateEmail(email) {
			if (email != '') {
				return true;
			} else {
				return false;
			}
		}


		$.ajaxSetup({
			dataType: 'JSON',
			type: 'POST',
			error: function () {
				$('.loginPanel').removeClass('blured');
				alert("ERR-4154");
			}
		});


		function getQs() {

		}
	</script>





</body>

</html>