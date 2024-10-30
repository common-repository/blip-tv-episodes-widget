<?php
/*
	Plugin Name: Blip TV Episodes Widget
	Version: 0.3
	Plugin URI: http://www.webtechglobal.co.uk/featured/blip-tv-episodes-widget
	Description: Blip TV Episodes plugin will allow you to setup a video widget
	Author: Ryan Bayne
	Author URI: http://www.webtechglobal.co.uk
*/

### developer configuration start
$easycsvimporterdebugmode = 0;
if( $easycsvimporterdebugmode == 1 )
{
	ini_set('display_errors',1);
	error_reporting(E_ALL);
}
### developer configuration end

function blip_commonincludes()
{
	require_once('functions/blip_functions_global.php');
	require_once('functions/blip_functions_interface.php');
	require_once('functions/blip_functions_processing.php');
}

// wysiwyg editor used to style widget boxes
function blip_wysiwygeditor() 
{
	wp_enqueue_script( 'common' );
	wp_enqueue_script( 'jquery-color' );
	wp_print_scripts('editor');
	if (function_exists('add_thickbox')) add_thickbox();
	wp_print_scripts('media-upload');
	if (function_exists('wp_tiny_mce')) wp_tiny_mce();
	wp_admin_css();
	wp_enqueue_script('utils');
	do_action("admin_print_styles-post-php");
	do_action('admin_print_styles');
}	

// plugin installation
function blip_installation() 
{	
	global $wordpressmu;
	blip_commonincludes();
	require_once('functions/blip_functions_installation.php');
	blip_insert_pluginsettings();	
}					
	
// plugin admin pages
function blip_createmenu() 
{
	require_once('functions/blip_functions_global.php');
	
 	$blip = get_option( 'blip_settings' );
	
	$per = 10;
	
	add_menu_page('Blip TV', 'Blip TV', $per, __FILE__, 'blip_toppage1');
    add_submenu_page(__FILE__, 'Manager', 'Manager', $per, 'blip_manager', 'blip_subpage2');
    add_submenu_page(__FILE__, 'Settings', 'Settings', $per, 'blip_settings', 'blip_subpage12');
    add_submenu_page(__FILE__, 'Tools', 'Tools', $per, 'blip_tools', 'blip_subpage13');
    add_submenu_page(__FILE__, 'Developer Notes', 'Developer Notes', $per, 'blip_developer', 'blip_subpage15');
}

function blip_toppage1(){blip_commonincludes();require_once('blip_home.php');}
function blip_subpage2(){blip_commonincludes();require_once('blip_manager.php');}
function blip_subpage12(){blip_commonincludes();require_once('blip_settings.php');}
function blip_subpage13(){blip_commonincludes();require_once('blip_tools.php');}
function blip_subpage15(){blip_commonincludes();require_once('blip_developernotes.php');}

// register widget - makes it available for use in admin
function blip_registerwidget1()
{
	blip_commonincludes();
	$widget_ops = array('classname' => 'blip_widget', 'description' => "Display videos in the sidebar" );
	wp_register_sidebar_widget('blip_widget', 'Video Blog Builder', 'blip_widget', $widget_ops);
}

// admin menu action
add_action('admin_menu', 'blip_createmenu');

// wordpress wysiwyg editor load
add_action('admin_head', 'blip_wysiwygeditor');

// sidebar widget 1 load
add_action('plugins_loaded','blip_registerwidget1');

// installation trigger
register_activation_hook(__FILE__,'blip_installation');
?>