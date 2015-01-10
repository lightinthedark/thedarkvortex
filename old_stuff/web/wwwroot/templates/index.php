<html>
<head>
<title>The Dark Vortex</title>

<link rel="stylesheet" type="text/css" href="<?php echo TDV_TEMPLATE; ?>style/style.css" />
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="<?php echo TDV_TEMPLATE; ?>style/ie/style.css" />
<![endif]-->

</head>

<body>
<div id="menu">
<div id="menu_inner">

	<?php if( TDV_SECTION == TDV_MAIN ) : ?>
	<div class="menu_section_active">
	<div class="menu_button active">
	<a href="<?php echo TDV_ROOT.$TDV_folders[TDV_MAIN]; ?>" >Main</a>
	</div>
	<?php $TDV_controller->getMenu() ;?>
	</div>
	<?php else: ?>
	<div class="menu_section">
	<div class="menu_button">
	<a href="<?php echo TDV_ROOT.$TDV_folders[TDV_MAIN]; ?>" >Main</a>
	</div>
	</div>
	<?php endif; ?>
	
	<?php if( TDV_SECTION == TDV_GAME ) : ?>
	<div class="menu_section_active">
	<div class="menu_button active">
	<a href="<?php echo TDV_ROOT.$TDV_folders[TDV_GAME]; ?>" >Game</a>
	</div>
	<?php $TDV_controller->getMenu() ;?>
	</div>
	<?php else: ?>
	<div class="menu_section">
	<div class="menu_button">
	<a href="<?php echo TDV_ROOT.$TDV_folders[TDV_GAME]; ?>" >Game</a>
	</div>
	</div>
	<?php endif; ?>
	
	<?php if( TDV_SECTION == TDV_ACCOUNT ) : ?>
	<div class="menu_section_active">
	<div class="menu_button active">
	<a href="<?php echo TDV_ROOT.$TDV_folders[TDV_ACCOUNT]; ?>" >Account</a>
	</div>
	<?php $TDV_controller->getMenu() ;?>
	</div>
	<?php else: ?>
	<div class="menu_section">
	<div class="menu_button">
	<a href="<?php echo TDV_ROOT.$TDV_folders[TDV_ACCOUNT]; ?>" >Account</a>
	</div>
	</div>
	<?php endif; ?>

</div>
</div> <!--  end menu -->

<div id="content">
<div id="content_inner">

<!-- this is the section content -->
<?php $TDV_controller->execute( $TDV_task ); ?>
<!-- end content-->

</div>
</div>

</body>
</html>