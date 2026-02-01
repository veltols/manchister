<script>
function start_loader(){
	$('#loader .progresser').css('width', '0%');
	$('#loader').css('display', 'flex');
}
function set_loader(d){
	$('#loader .progresser').css('width', d + '%');
}
function end_loader(){
	$('#loader .progresser').css('width', '0%');
	$('#loader').css('display', 'none');
}
activeRequest = false;
</script>

<div id="loader" style="position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.2); z-index: 999; display: flex; justify-content: center; align-items: center;flex-wrap: wrap;flex-direction: column;">
	<img src="<?=$UPLOADS_DIRECTORY; ?>loader.gif" style="display: block;width: 2%;">
	<div class="progress" style="width: 50%;margin: 0 auto;margin-top: 0.5em;display: block;height: 4px;background: #FFF;">
		<div class="progresser" style="height: 4px;background: red;"></div>
	</div>
</div>


<?php
$thsAsideClass = 'mainAside';
$isShowAsideBtn = false;
if( isset($asideIsHidden) ){
	$thsAsideClass = 'mainAside mainAsideHidden';
	$isShowAsideBtn = true;
}
?>

<div class="userLiner" style="background-color: <?=$LIGHT_COLOR; ?>;">
	
	<div id="logoCol" class="logoCol" style="background-color: <?=$LIGHT_COLOR; ?>;color: <?=$ONLIGHT_COLOR; ?>;">


		<div id="asideBtn" class="asideBtn" onclick="toggleAside();">
			<i class="fa-solid fa-bars"></i>
		</div>
		
		<a href="<?=$DIR_dashboard; ?>" class="mainLogo">
			<img src="<?=$UPLOADS_DIRECTORY; ?><?=$COMPANY_LOGO; ?>" alt="" class="mainLogoImg">
		</a>
		<div class="themeIcons">
			<div class="themeIcon">
				<i class="far fa-moon"></i>
			</div>
			<div class="themeIcon">
				<i class="fas fa-globe"></i>
			</div>
		</div>
	</div>

	<div class="userOptions" style="background-color: <?=$DARK_COLOR; ?>;">
		<div class="userName" onclick="showUserOptions();" style="color: <?=$ONDARK_COLOR; ?>;">
		<img src="<?=$UPLOADS_DIRECTORY; ?><?=$EMPLOYEE_PICTURE; ?>" alt="<?=$EMPLOYEE_NAME; ?>" class="empLogoImg">
			<span><?=$EMPLOYEE_NAME; ?></span>
		</div>

		<div id="userOptionsMenu" class="userOptionsMenu userOptionsMenuHidden">
			
			<a href="<?=$DIR_profile; ?>" class="userOpt">
				<i class="fas fa-user"></i>
				<span><?=lang('My Profile'); ?></span>
			</a>
			<a href="<?=$DIR_settings; ?>" class="userOpt">
				<i class="fas fa-cogs"></i>
				<span><?=lang('settings'); ?></span>
			</a>
			
			<a href="<?=$DIR_logout; ?>" class="userOpt">
				<i class="fas fa-sign-out-alt"></i>
				<span><?=lang('logout'); ?></span>
			</a>
		</div>
	</div>

</div>

<script>
	var asideInv = 'hide';
	function toggleAside(){
		if( asideInv == 'show' ){
			$('#mainAside').removeClass('mainAsideHidden');
			$('#mainArticle').removeClass('mainArticleFull');
			$('#asideBtn i').addClass('fa-bars');
			$('#asideBtn i').removeClass('fa-table-columns');
			asideInv = 'hide';
		} else {
			$('#mainAside').addClass('mainAsideHidden');
			$('#mainArticle').addClass('mainArticleFull');
			$('#asideBtn i').removeClass('fa-bars');
			$('#asideBtn i').addClass('fa-table-columns');
			asideInv = 'show';
		}
	}

	
	function showUserOptions(){
		$('#userOptionsMenu').removeClass('userOptionsMenuHidden');
		$('#mainArticle').attr('onclick', 'hideUserOptions()');
		$('#logoCol').attr('onclick', 'hideUserOptions()');
		$('#mainAside').attr('onclick', 'hideUserOptions()');
	}
	function hideUserOptions(){
		$('#userOptionsMenu').addClass('userOptionsMenuHidden');
		$('#mainArticle').attr('onclick', '');
		$('#logoCol').attr('onclick', '');
		$('#mainAside').attr('onclick', '');
	}
</script>


<?php
$thsAsideClass = 'mainAside';
$mainArticleClass = '';
$isShowAsideBtn = false;
if( isset($asideIsHidden) ){
	$thsAsideClass = 'mainAside mainAsideHidden';
	$mainArticleClass = 'mainArticleFull';
	$isShowAsideBtn = true;
?>
<script>
	toggleAside();
</script>
<?php
}
?>


<aside id="mainAside" class="<?=$thsAsideClass; ?>">
	<div class="asideLogo">
		<img src="<?=$UPLOADS_DIRECTORY.$EMPLOYEE_PICTURE; ?>" alt="PT logo" class="ptLogo">
	</div>
	
	<div class="asideMenu">

		<a href="<?=$DIR_dashboard; ?>" class="mnuItem <?php if($pageId==100) { echo 'mnuItemSelected'; } ?>">
			<i class="fa-solid fa-house-chimney"></i>
			<span><?=lang("Dashboard"); ?></span>
		</a>
		
		
		<a href="<?=$DIR_atps; ?>" class="mnuItem <?php if($pageId==500) { echo 'mnuItemSelected'; } ?>">
			<i class="fa-regular fa-building"></i>
			<span><?=lang("Training_Providers", "AAR"); ?></span>
		</a>

	</div>

	<div class="asideMenuHr"></div>

	<div class="asideMenu">
		<?php
		/*
		<a href="<?=$DIR_settings; ?>" class="mnuItem <?php if($pageId==1500) { echo 'mnuItemSelected'; } ?>">
			<i class="fa-solid fa-gear"></i>
			<span><?=lang("Settings", "AAR"); ?></span>
		</a>
			*/
			?>

		<a href="<?=$DIR_logout; ?>" class="mnuItem">
			<i class="fa-solid fa-sign-out-alt"></i>
			<span><?=lang("logout", "AAR"); ?></span>
		</a>
	</div>

</aside>



<!-- ARTICLE START -->
<article class="<?=$mainArticleClass; ?>" id="mainArticle">