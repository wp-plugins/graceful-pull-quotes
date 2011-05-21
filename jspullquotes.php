<?php /*

Plugin Name: Javascript Pull-Quotes
Plugin URI: http://www.striderweb.com/nerdaphernalia/features/wp-javascript-pull-quotes/
Version: 1.3b1
Description: Allows you to create flexible customizable pull-quotes without duplicating text in your markup.  After activating for the first time, be sure to check out the <a href="themes.php?page=jspullquotes.php">Options Panel</a>. Built upon groundwork laid by <a href="http://www.456bereastreet.com/archive/200609/automatic_pullquotes_with_javascript_and_css/">Roger Johansson</a> and <a href="http://www.viper007bond.com/wordpress-plugins/javascript-pullquotes/">"Viper007Bond"</a>.
Author: Stephen Rider
Author URI: http://www.striderweb.com/

*/

require( 'jspullquotes_options.php' );
include('buttonsnap.php');

add_action('wp_head', array('jspquote','wphead'));
//add_action('admin_head', 'Pullquote_admin');
add_action('admin_menu', array('jspquote','optionpage'));

// Using Buttonsnap for the editor button

// wrap it all in a class to avoid function name conflicts
class jspquote {

// This adds the needed links to the <head> of each blog page
	function wphead() {
		echo '	<link rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/jspullquotes-core.css" type="text/css" />' . "\n";
		echo '	<link rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/jspullquotes.css" type="text/css" />' . "\n";
		echo '	<script type="text/javascript" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/jspullquotes.js"></script>' . "\n";
		$options = get_option('jspullquotes_options');
//echo the options into the page's javascript...
		echo '	<script type="text/javascript">pullquoteopts("' . $options['skip_links'] . '", "' . $options['def_side'] . '", "' . $options['alt_sides'] . '", "' . $options['alt_text'] . '");</script>';
	}
	

/* ======================= */
/* START OPTIONS PAGE CODE */
/* ======================= */

// Add the configuration screen to the Presentation menu in wp-admin
// Actual panel function is in jspullquotes_options.php
	function optionpage() {
		if (function_exists('add_submenu_page')) {
			add_submenu_page('themes.php', 'Pull-Quote Options', 'Pull-Quotes', 7, basename(__FILE__), 'jspullquotes_subpanel');
		}
	}
	
	function checkflag($optname) {
		$options = get_option('jspullquotes_options');
		if($options[$optname] != 'true') return '';
		return ' checked="checked"';
	}
	
	function checkcombo($optname,$thisopt,$optdefault) {
		$options = get_option('jspullquotes_options');
		// if option is not set, set to default, then reload options variable
		if(!$options[$optname]) {
			update_option($optname,$optdefault);
			$options = get_option('jspullquotes_options');
		}
		if($options[$optname] != $thisopt) {
			return '';
		}
		return ' selected="selected"';
	}
	
/* ===================== */
/* END OPTIONS PAGE CODE */
/* ===================== */

} // end class


/* ========================= */
/* START WYSIWYG BUTTON CODE */
/* ========================= */
	
// Make a button on the write screens if TinyMCE is OFF
// Sorry, I can't be arsed to deal with TinyMCE at this time
/*	function Pullquote_admin() {
		if (function_exists('user_can_richedit') && !user_can_richedit()) {
			buttonsnap_jsbutton('', 'pull-quote', 'pullquotesButton();');
			echo '	<script type="text/javascript" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/jspullquotes_admin.js"></script>' . "\n";
		}
	}
*/

//	Add an action so buttonsnap knows what to do
//    The action calls a function, defined below
add_action('init', 'Pullquote_button_init');
 
function Pullquote_button_init() {
//	Set up some unique button image URLs for the new buttons to use in the WYSIWYG toolbar (does nothing in the Quicktags)
	$Pullquote_button_image_url = buttonsnap_dirname(__FILE__) . '/jspullquote.gif';

//	Create a vertical separator in the WYSI toolbar (does nothing in the Quicktags)
	buttonsnap_separator();
//	Create a button that uses Ajax to fetch replacement text from a WordPress plugin hook sink
	buttonsnap_ajaxbutton($Pullquote_button_image_url, 'pull-quote', 'Pullquote_insert_hook');

//	Add the filter to match the hook and sink together
	add_filter('Pullquote_insert_hook', 'Pullquote_insert_sink');
}
//	Each function below corresponds to one button and matches to an above add_filter.  The filter returns the selected text AND the before and after text found in single quotes.
function Pullquote_insert_sink($selectedtext) {
return '<span class="pullquote">' . stripslashes($selectedtext) . '</span>';
}

/* ======================= */
/* END WYSIWYG BUTTON CODE */
/* ======================= */

?>