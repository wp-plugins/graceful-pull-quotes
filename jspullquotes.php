<?php /*

Plugin Name: Javascript Pull-Quotes
Plugin URI: http://www.striderweb.com/nerdaphernalia/features/wp-javascript-pull-quotes/
Version: 1.3
Description: Allows you to create flexible customizable pull-quotes without duplicating text in your markup.  After activating for the first time, be sure to check out the <a href="themes.php?page=jspullquotes.php">Options Panel</a>. Built upon groundwork laid by <a href="http://www.456bereastreet.com/archive/200609/automatic_pullquotes_with_javascript_and_css/">Roger Johansson</a> and <a href="http://www.viper007bond.com/wordpress-plugins/javascript-pullquotes/">"Viper007Bond"</a>.
Author: Stephen Rider
Author URI: http://www.striderweb.com/

*/

require( 'jspullquotes_options.php' );
include('buttonsnap.php');

add_action('wp_head', array('jspquote','wphead'));
add_action('admin_menu', array('jspquote','optionpage'));
//add_action('init', array('jspquote','WYS_button_init'));

$jspq_options = get_option('jspullquotes_options');
if ($jspq_options['use_beta']=='true') {
	add_action('init', array('jspquote','WYS_button_init'));
} else {
	add_action('admin_head', array('jspquote','edit_button_init'));
}


// wrap it all in a class to avoid function name conflicts
class jspquote {

// This adds the needed links to the <head> of each blog page
	function wphead() {
		echo '	<link rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/jspullquotes-core.css" type="text/css" />' . "\n";
		echo '	<link rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/jspullquotes.css" type="text/css" />' . "\n";
		echo '	<script type="text/javascript" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/jspullquotes.js"></script>' . "\n";
		$options = get_option('jspullquotes_options');
//echo the options into the page's javascript...
	echo '	<script type="text/javascript">
		var arrOptions = new Array("' . 
			$options['skip_links'] . '", "' . 
			$options['def_side'] . '", "' . 
			$options['alt_sides'] . '", "' . 
			$options['alt_text'] . '", "' .
			$options['q_container'] . '", "' .
			$options['quote_class'] . '", "' .
			$options['quote_class_alt'] . '");
		pullquoteopts(arrOptions);
	</script>';
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

	function checktext($optname,$optdefault) {
		$options = get_option('jspullquotes_options');
		if(!$options[$optname]) return $optdefault;
		return $options[$optname];
	}
	
	function checkcombo($optname,$thisopt,$optdefault) {
		$options = get_option('jspullquotes_options');
		if($options[$optname] != $thisopt) return '';
		return ' selected="selected"';
	}
	
/* ===================== */
/* END OPTIONS PAGE CODE */
/* ===================== */

/* ========================= */
/* START WYSIWYG BUTTON CODE */
/* ========================= */
	
// Make a button on the write screens if TinyMCE is OFF
	function edit_button_init() {
		if (function_exists('user_can_richedit') && !user_can_richedit()) {
			buttonsnap_jsbutton('', 'pull-quote', 'pullquotesButton();');
			echo '	<script type="text/javascript" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/jspullquotes_admin.js"></script>' . "\n";
		}
	}
	 
// Using Buttonsnap for the editor button
	function WYS_button_init() {
		//	Set up some unique button image URLs for the new buttons to use in the WYSIWYG toolbar (does nothing in the Quicktags)
			$Pullquote_button_image_url = buttonsnap_dirname(__FILE__) . '/jspullquote.gif';
		
		//	Create a vertical separator in the WYSI toolbar (does nothing in the Quicktags)
			buttonsnap_separator();
		//	Create a button that uses Ajax to fetch replacement text from a WordPress plugin hook sink
			buttonsnap_ajaxbutton($Pullquote_button_image_url, 'pullquote', 'Pullquote_insert_hook');
		
		//	Add the filter to match the hook and sink together
			add_filter('Pullquote_insert_hook', array('jspquote','Pullquote_insert_sink'));

		// Add CSS to mark the pull-quoted text in the editor
			//add_action('admin_head', array('jspquote','WYS_style_editor'));
	}

	//	Function below corresponds to one button and matches an above add_filter.  The filter returns the selected text AND the before and after text found in single quotes.
	function Pullquote_insert_sink($selectedtext) {
		return '<span class="pullquote">' . stripslashes($selectedtext) . '</span>';
	}
	
	function WYS_style_editor() {
		echo '	<style type="text/css">
<!--
		div textarea#content {}
		span.pullquote {
				text-decoration: underline overline;
				color: red;
		}
-->
	</style>
';
	}

/* ======================= */
/* END WYSIWYG BUTTON CODE */
/* ======================= */

} // end class


?>