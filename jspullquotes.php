<?php /*

Plugin Name: JavaScript Pull-Quotes
Plugin URI: http://striderweb.com/nerdaphernalia/features/wp-javascript-pull-quotes/
Version: 1.6.5
Description: Allows you to create flexible customizable pull-quotes without duplicating text in your markup or feeds.
Author: Stephen Rider
Author URI: http://striderweb.com/
Options URI: themes.php?page=jspullquotes.php
*/

$version=array('1.6.5','31 Aug 2007');

define( 'JSPQPATH', PLUGINDIR . '/' . basename( dirname(__FILE__)) );

load_plugin_textdomain( 'jspq', JSPQPATH );
require_once( 'files/jspullquotes.class.php' );

// set defaults when plugin is first activated
register_activation_hook( __FILE__, array('jspullquotes', 'set_defaults') );

add_action( 'wp_head', array('jspullquotes', 'wphead') );
add_action( 'admin_head', array('jspullquotes', 'adminhead') );
add_action( 'admin_menu', 'jspq_optionspage' );

// Add the configuration screen to the Presentation menu in Admin
// NOTE: I left this out of the class file so that the config URL would be nicer
function jspq_optionspage() {
	global $langStr;
	if ( function_exists('add_submenu_page') ) {
		add_submenu_page( 'themes.php', __('Customize Pull-Quotes','jspq'), __('Pull-Quotes','jspq'), 'switch_themes', basename(__FILE__), array('jspullquotes','options_panel') );
	}
}

?>