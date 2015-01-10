<?php
ob_start();
if( !defined( 'TDV_ROOT' ) ) {
	define( TDV_ROOT, './');
	require( TDV_ROOT.'config.php' );
	define( TDV_SECTION, TDV_MAIN ); // default to the main page(s)
}

require_once( 'libraries/main.php' );

$TDV_controller = Controller::getController( TDV_SECTION );
$TDV_task = Request::get('task');

// get the template (with content)
ob_start();
require( TDV_TEMPLATE.'index.php' );
$tmpl = ob_get_clean();

// prepare to substitute in any style sheets that were requested to be included
$styles = '';
if( !empty($TDV_styles) ) {
	foreach( $TDV_styles as $k=>$style ) {
		$TDV_styles[$k] = '<link rel="stylesheet" type="text/css" href="'.$style.'" />';
	}
	$styles .= implode( "\n", $TDV_styles )."\n";
}
if( !empty($TDV_styles_cond) ) {
	foreach( $TDV_styles_cond as $k=>$style ) {
		$TDV_styles_cond[$k] = '<!--[if '.$style['cond'].']><link rel="stylesheet" type="text/css" href="'.$style['file'].'" /><![endif]-->';
	}
	$styles .= implode( "\n", $TDV_styles_cond )."\n";
}

// prepare to substitute in any scripts that were requested to be included
$scripts = '';
if( !empty($TDV_scripts) ) {
	array_unshift( $TDV_scripts, TDV_ROOT.'libraries/mootools-more.js' );
	array_unshift( $TDV_scripts, TDV_ROOT.'libraries/mootools-core.js' );
	foreach( $TDV_scripts as $k=>$script ) {
		$TDV_scripts[$k] = '<script type="text/javascript" src="'.$script.'"></script>';
	}
	$scripts .= implode( "\n", $TDV_scripts )."\n";
}
if( !empty($TDV_scripts_cond) ) {
	foreach( $TDV_scripts_cond as $k=>$script ) {
		$TDV_scripts_cond[$k] = '<!--[if '.$script['cond'].']><script type="text/javascript" src="'.$script['file'].'"></script><![endif]-->';
	}
	$scripts .= implode( "\n", $TDV_scripts_cond )."\n";
}
ob_end_clean();

// and finally output
echo str_replace( '</head>', $styles.$scripts.'</head>', $tmpl );
?>