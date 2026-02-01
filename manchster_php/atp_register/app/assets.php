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
<?php
		$color_primary = "";
		$color_on_primary = "";
		$color_secondary = "";
		$color_on_secondary = "";
		$color_third = "";
		$color_on_third = "";
	$qu_users_list_themes_sel = "SELECT * FROM  `users_list_themes` WHERE `user_theme_id` = $USER_THEME_ID";
	$qu_users_list_themes_EXE = mysqli_query($KONN, $qu_users_list_themes_sel);
	$users_list_themes_DATA;
	if(mysqli_num_rows($qu_users_list_themes_EXE)){
		$users_list_themes_DATA = mysqli_fetch_assoc($qu_users_list_themes_EXE);
		$user_theme_id = ( int ) $users_list_themes_DATA['user_theme_id'];
		$color_primary = $users_list_themes_DATA['color_primary'];
		$color_on_primary = $users_list_themes_DATA['color_on_primary'];
		$color_secondary = $users_list_themes_DATA['color_secondary'];
		$color_on_secondary = $users_list_themes_DATA['color_on_secondary'];
		$color_third = $users_list_themes_DATA['color_third'];
		$color_on_third = $users_list_themes_DATA['color_on_third'];
	}

?>
	<style>
		/* latin-ext */
		@font-face {
			font-family: 'DM Sans';
			font-style: italic;
			font-weight: 100 1000;
			font-display: swap;
			src: url('<?= $POINTER; ?>../public/fonts/dm/a1.woff2') format('woff2');
			unicode-range: U+0100-02AF, U+0304, U+0308, U+0329, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
		}

		/* latin */
		@font-face {
			font-family: 'DM Sans';
			font-style: italic;
			font-weight: 100 1000;
			font-display: swap;
			src: url('<?= $POINTER; ?>../public/fonts/dm/a2.woff2') format('woff2');
			unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
		}

		/* latin-ext */
		@font-face {
			font-family: 'DM Sans';
			font-style: normal;
			font-weight: 100 1000;
			font-display: swap;
			src: url('<?= $POINTER; ?>../public/fonts/dm/a3.woff2') format('woff2');
			unicode-range: U+0100-02AF, U+0304, U+0308, U+0329, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
		}

		/* latin */
		@font-face {
			font-family: 'DM Sans';
			font-style: normal;
			font-weight: 100 1000;
			font-display: swap;
			src: url('<?= $POINTER; ?>../public/fonts/dm/a4.woff2') format('woff2');
			unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
		}

		/* Custom Colors */
		:root {
			--primary:
				#<?=$color_primary; ?>;
		}
		:root {
			--primary-light:
				#<?=$color_primary; ?>;
		}

		:root {
			--secondary:
			#<?=$color_secondary; ?>;
			;
		}

		:root {
			--on-primary:
			#<?=$color_on_primary; ?>;
			;
		}

		:root {
			--on-secondary:
			#<?=$color_on_secondary; ?>;
			;
		}

		:root {
			--third:
			#<?=$color_third; ?>;
			;
		}

		:root {
			--on-third:
			#<?=$color_on_third; ?>;
			;
		}

		:root {
			--strategy: #575096;
		}

		:root {
			--strategy-grey: #737373;
		}
	</style>


	<!-- PC CSS -->
	<link href="<?= $POINTER; ?>../public/assets/styles/theme.css" rel="stylesheet" media="all">
	<link href="<?= $POINTER; ?>../public/assets/styles/pc<?=$lang_db; ?>.css" rel="stylesheet"
		media="(min-width:1026px) and (max-width:3500px)">
	<!-- tablet CSS -->
	<link href="<?= $POINTER; ?>../public/assets/styles/tablet.css" rel="stylesheet"
		media="(min-width:700px) and (max-width:1025px)">
	<!-- mob CSS -->
	<link href="<?= $POINTER; ?>../public/assets/styles/mob.css" rel="stylesheet"
		media="(min-width:100px) and (max-width:699px)">

	<!-- font awesome -->
	<link href="<?= $POINTER; ?>../public/assets/fa/css/all.css" rel="stylesheet">

	<!-- Jquery files -->
	<script type="text/javascript" src="<?= $POINTER; ?>../public/assets/js/jquery-3.6.4.min.js"></script>
	<script type="text/javascript" src="<?= $POINTER; ?>../public/assets/js/cookies.js"></script>


	<script>
		var lang_db = '<?= $lang_db; ?>';
	</script>


</head>

<body>
	<?php
	include("header.php");
	?>