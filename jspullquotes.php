<?php /*

Plugin Name: Javascript Pull-Quotes
Plugin URI: http://www.striderweb.com/nerdaphernalia/features/wp-javascript-pull-quotes/
Version: 1.2.1
Description: Allows you to create flexible customizable pull-quotes without duplicating text in your markup.  After activating for the first time, be sure to check out the <a href="themes.php?page=jspullquotes.php">Options Panel</a>. Built upon groundwork laid by <a href="http://www.456bereastreet.com/archive/200609/automatic_pullquotes_with_javascript_and_css/">Roger Johansson</a> and <a href="http://www.viper007bond.com/wordpress-plugins/javascript-pullquotes/">"Viper007Bond"</a>.
Author: Stephen Rider
Author URI: http://www.striderweb.com/

*/

require( 'jspullquotes_options.php' );

add_action('wp_head', 'jspullquotes_wphead');
add_action('admin_head', 'jspullquotes_admin');
add_action('admin_menu', 'jspullquotes');

// We'll use ButtonSnap to make our button (I'm lazy and it's easy)
//include(ABSPATH . 'wp-content/plugins/jspullquotes/buttonsnap.php');
include('buttonsnap.php');

// This adds the needed links to the <head> of each blog page
function jspullquotes_wphead() {
	echo '	<link rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/jspullquotes-core.css" type="text/css" />' . "\n";
	echo '	<link rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/jspullquotes.css" type="text/css" />' . "\n";
	echo '	<script type="text/javascript" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/jspullquotes.js"></script>' . "\n";
	$options = get_option('jspullquotes_options');
	echo '	<script type="text/javascript">pullquoteopts("' . $options['skip_links'] . '", "' . $options['def_side'] . '", "' . $options['alt_sides'] . '", "' . $options['alt_text'] . '");</script>';
}

// Make a button on the write screens if TinyMCE is OFF
// Sorry, I can't be arsed to deal with TinyMCE at this time
function jspullquotes_admin() {
	if (function_exists('user_can_richedit') && !user_can_richedit()) {
		buttonsnap_jsbutton('', 'Pullquote', 'pullquotesButton();');
		echo '	<script type="text/javascript" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/jspullquotes_admin.js"></script>' . "\n";
	}
}

// Add the configuration screen to the Presentation menu in wp-admin
// Actual panel function is in jspullquotes_options.php
function jspullquotes() {
    if (function_exists('add_submenu_page')) {
		add_submenu_page('themes.php', 'Pull-Quote Options', 'Pull-Quotes', 7, basename(__FILE__), 'jspullquotes_subpanel');
    }
 }


?>