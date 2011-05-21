<?php /*

Plugin Name: JavaScript Pull-Quotes
Plugin URI: http://striderweb.com/nerdaphernalia/features/wp-javascript-pull-quotes/
Version: 1.5.4
Description: Allows you to create flexible customizable pull-quotes without duplicating text in your markup.  <strong>Configuration: <a href="themes.php?page=jspullquotes.php">Presentation &raquo; Pull-Quotes</a></strong>.
Author: Stephen Rider
Author URI: http://striderweb.com/

*/

$version='1.5.4';

load_plugin_textdomain('jspq','wp-content/plugins/jspullquotes/lang');
require_once('jspquote.class.php');

add_action('wp_head', array('jspquote','wphead'));
add_action('admin_head', array('jspquote','adminhead'));
add_action('admin_menu', 'jspq_optionspage');

// Add the configuration screen to the Presentation menu in wp-admin
function jspq_optionspage() {
	global $langStr;
	if (function_exists('add_submenu_page')) {
		add_submenu_page('themes.php', __('Customize Pull-Quotes','jspq'), __('Pull-Quotes','jspq'), 7, basename(__FILE__), array('jspquote','options_panel'));
	}
}

?>