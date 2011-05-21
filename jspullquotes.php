<?php /*

Plugin Name: JavaScript Pull-Quotes
Plugin URI: http://striderweb.com/nerdaphernalia/features/wp-javascript-pull-quotes/
Version: 1.6.7
Description: Allows you to create flexible customizable pull-quotes without duplicating text in your markup or feeds.
Author: Stephen Rider
Author URI: http://striderweb.com/
Options URI: themes.php?page=jspullquotes.php
*/

$jspq_version = array( '1.6.7', '7 January 2008' );

/*
TO DO: add check so preview script only appears in the one Admin page

	1.6.7:
	* NEW French localization -- /Merci/ Ben!
	* BUGFIX - version display in page header was broken
	* Small fix to options page localization
	* minor code changes

	1.6.6:
	* plugin path now uses plugin_basename()
	* Added User Option to _not_ link CSS -- this way admin can put CSS in site's main CSS file and save some HTTP requests
	* BUGFIX - version display on Options screen was broken

	1.6.5: 
	* added function to set defaults if no options are set
	* reorganized files in jspullquotes directory
	* added JSPQPATH constant to make paths less brittle
	* changed User Capability requirement to "switch_themes"
	* test for both "switch_themes" and "edit_themes" capabilites before writing "edit stylesheet" box to admin screen
	* changed name of class, for filename consistency
*/

define( 'JSPQPATH', PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) );

load_plugin_textdomain( 'jspq', JSPQPATH );
require_once( 'files/jspullquotes.class.php' );

// set defaults when plugin is first activated
register_activation_hook( __FILE__, array('jspullquotes', 'set_defaults') );
// if in a class, array can be this form:   array(&$this, 'set_defaults')

add_action( 'wp_head', array('jspullquotes', 'wphead') );
add_action( 'admin_head', array('jspullquotes', 'adminhead') );
add_action( 'admin_menu', 'jspq_optionspage' );

// Add the configuration screen to the Presentation menu in Admin
// NOTE: I left this out of the class file so that the config URL would be nicer
function jspq_optionspage() {
	global $langStr;
	if ( function_exists('add_submenu_page') ) {
		// add_submenu_page( 'themes.php', __('Customize Pull-Quotes','jspq'), __('Pull-Quotes','jspq'), 'switch_themes', basename(__FILE__), array('jspullquotes','options_panel') );
		add_theme_page( __('Customize Pull-Quotes','jspq'), __('Pull-Quotes','jspq'), 'switch_themes', basename(__FILE__), array('jspullquotes','options_panel') );
	}
}

?>