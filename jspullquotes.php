<?php /*

Plugin Name: Javascript Pull-Quotes
Plugin URI: http://www.striderweb.com/nerdaphernalia/features/wp-javascript-pull-quotes/
Version: 1.4
Description: Allows you to create flexible customizable pull-quotes without duplicating text in your markup.  <strong>Configuration: <a href="themes.php?page=jspullquotes.php">Presentation &raquo; Pull-Quotes</a></strong>.
Author: Stephen Rider
Author URI: http://www.striderweb.com/

*/

//require( 'jspullquotes_options.php' ); // include options page code

add_action('wp_head', array('jspquote','wphead'));
add_action('admin_menu', array('jspquote','optionpage'));

// currently the non-WYSIWYG button is not as good if using the code that allows for a WYSI button, so this IF uses the better code if the beta WYSI option is turned off.
include('buttonsnap.php');	// Use Buttonsnap for the editor button
//$jspq_options = get_option('jspullquotes_options');
//if ($jspq_options['use_beta']=='true') {
//	add_action('init', array('jspquote','WYS_button_init'));
//} else {
	add_action('admin_head', array('jspquote','edit_button_init'));
//}


// wrap it all in a class to avoid function name conflicts
class jspquote {

// This adds the needed links to the <head> of each blog page
	function wphead() {
		echo '	<!-- added by JavaScript Pull-Quotes plugin - http://www.striderweb.com/nerdaphernalia/features/wp-javascript-pull-quotes/ -->' . "\n";
		echo '	<link rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/jspullquotes-core.css" type="text/css" />' . "\n";
		echo '	<link rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/jspullquotes.css" type="text/css" />' . "\n";
		echo '	<script type="text/javascript" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/jspullquotes.js"></script>' . "\n";
		$options = get_option('jspullquotes_options');
//echo the options from the DB into the page's javascript...
		echo '	<script type="text/javascript">
		var arrOptions = new Array("' . 
			$options['skip_links'] . '", "' . 
			$options['def_side'] . '", "' . 
			$options['alt_sides'] . '", "' . 
			$options['alt_text'] . '", "' .
			$options['cap_first'] . '", "' .
			$options['q_container'] . '", "' .
			$options['quote_class'] . '", "' .
			$options['quote_class_alt'] . '");
		pullquoteopts(arrOptions);
	</script>' . "\n";
		echo '	<!-- end pull-quote additions -->' . "\n";
	}
	

// =======================
// START OPTIONS PAGE CODE
// =======================

// Add the configuration screen to the Presentation menu in wp-admin
// Actual panel function is in jspullquotes_options.php
	function optionpage() {
		if (function_exists('add_submenu_page')) {
			add_submenu_page('themes.php', 'Pull-Quote Options', 'Pull-Quotes', 7, basename(__FILE__), array('jspquote','options_panel'));
		}
	}
	
	// these three functions are used by the options page to display set options in the form controls when the page is opened
	
	function checkflag($options,$optname) { // for checkboxes
		if($options[$optname] != 'true') return '';
		return ' checked="checked"';
	}

	function checktext($options,$optname,$optdefault='') { // for text boxes
		if(!$options[$optname]) return $optdefault;
		return $options[$optname];
	}
	
	function checkcombo($options,$optname,$thisopt,$optdefault='') { // for dropdowns
		if(!$options[$optname] && $optdefault == true) return ' selected="selected"';
		if($options[$optname] != $thisopt) return '';
		return ' selected="selected"';
	}

/*	Options Panel code for the Wordpress wp-admin screen. */
//	function jspullquotes_subpanel() {
	function options_panel() {	
		if (isset($_POST['jspullquotes_options_submit'])) {
	?>
			<div class="updated"><p><?php 
			_e('Options Saved','jspullquotes'); ?></p></div>
	<?php
			update_option('jspullquotes_options', $_POST['jspullquotes_options']);
		} 
		/*
		update_option($option_name, $newvalue);
		get_option($option);
		add_option($name, $value, $description, $autoload);
		*/
		
		// get options for use in formsetting functions
		$jspq_options = get_option('jspullquotes_options');
	
	?>
<div class="wrap">
	<h2>Pull-Quotes</h2>
	<form action="themes.php?page=jspullquotes.php" method="post">
		<div style="width: 10em; float: right; border: 1px black solid; margin: 0 0 1em 1em; padding: 0.75em; background-color: #FDFBDE; text-size: 1.2em;">
			<a href="templates.php?file=/wp-content/plugins/jspullquotes/jspullquotes.css">Click here to edit the StyleSheet that controls the appearance of the pull-quotes.</a>
		</div>
		<fieldset name="basic">
		<h3>Basic Options</h3>
			<p><input type="checkbox" name="jspullquotes_options[skip_links]" id="skip_links" value="true"<?php echo(jspquote::checkflag($jspq_options,'skip_links')); ?> /> <label for="skip_links">Remove link information from pull-quotes</label> <strong>(Highly recommended)</strong></p>
			<p><label for="def_side">Display quotes on <select name="jspullquotes_options[def_side]" id="def_side">
				<option value="left"<?php echo(jspquote::checkcombo($jspq_options,'def_side','left',true)); ?>>left</option>
				<option value="right"<?php echo(jspquote::checkcombo($jspq_options,'def_side','right')); ?>>right</option>
			</select> side</label></p>
			<p><input type="checkbox" name="jspullquotes_options[alt_sides]" id="alt_sides" value="true"<?php echo(jspquote::checkflag($jspq_options,'alt_sides')); ?> /> <label for="alt_sides">Successive quotes on one page alternate sides</label></p>
			<!-- <p><input type="checkbox" name="jspullquotes_options[cap_first]" id="cap_first" value="true"<?php echo(jspquote::checkflag($jspq_options,'cap_first')); ?> /> <label for="cap_first">Capitalize first letter in quote if lowercase</label></p> -->
			<p><input type="checkbox" name="jspullquotes_options[alt_text]" id="alt_text" value="true"<?php echo(jspquote::checkflag($jspq_options,'alt_text')); ?> /> <label for="alt_text">Use alternate text if available</label> (<a href="../wp-content/plugins/jspullquotes/alt-text-info.htm">how?</a>)</p>
		</fieldset>
		<fieldset name="advanced">
		<h3>Advanced Options</h3>
			<p><strike><input type="checkbox" name="jspullquotes_options[use_beta]" id="use_beta" value="true"<?php echo(jspquote::checkflag($jspq_options,'use_beta')); ?> /> <label for="use_beta">(BETA) Use pull-quote button in rich-text post editor</label></strike><br /><em>(Disabled due to changes in WP 2.1.  Probably won't be fixed until certain improvements scheduled for WordPress 2.2 are implemented.  Currently, if you turn off the rich-text editor under User settings, there is a Pull-Quote button in the HTML editor.)</em></p>
			<p><label for="q_container">Contain pull-quote in an HTML</label> <select name="jspullquotes_options[q_container]" id="q_container">
				<option value="blockquote"<?php echo(jspquote::checkcombo($jspq_options,'q_container','blockquote',true)); ?>>&lt;blockquote&gt;</option>
				<option value="div"<?php echo(jspquote::checkcombo($jspq_options,'q_container','div')); ?>>&lt;div&gt;</option>
				<!--<option value="p"<?php echo(jspquote::checkcombo($jspq_options,'q_container','p')); ?>>&lt;p&gt;</option>-->
				</select></p>
			<p>Note: changing any of the following may require you to update both the <a href="templates.php?file=/wp-content/plugins/jspullquotes/jspullquotes.css">jspullquotes.css</a> and <a href="templates.php?file=/wp-content/plugins/jspullquotes/jspullquotes-core.css">jspullquotes-core.css</a> files.</p>
			<p><label for="quote_class">CSS class selector for default pull-quote:</label> <input type="text" name="jspullquotes_options[quote_class]" id="quote_class" value="<?php echo(jspquote::checktext($jspq_options,'quote_class','pullquote')); ?>" /></p>
			<p><label for="quote_class_alt">CSS class selector for alt-side pull-quote:</label> <input type="text" name="jspullquotes_options[quote_class_alt]" id="quote_class_alt" value="<?php echo(jspquote::checktext($jspq_options,'quote_class_alt','pullquote pqAlt')); ?>"/></p>
		</fieldset>
		<div class="submit">
			<input type="submit" name="jspullquotes_options_submit" value="<?php _e('Update Options &raquo;', 'jspullquotes') ?>" /></div>
	</form>
</div><!-- end of wrap -->
	<?php
	}

// =====================
// END OPTIONS PAGE CODE
// =====================

// =========================
// START WYSIWYG BUTTON CODE
// =========================
	
// Make a button on the edit screens if TinyMCE is OFF
	function edit_button_init() {
		if (function_exists('user_can_richedit') && !user_can_richedit()) {
			buttonsnap_jsbutton('', 'pull-quote', 'pullquotesButton();');
			echo '	<script type="text/javascript" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/jspullquotes_quicktags.js"></script>' . "\n";
		}
	}
	 
// Make a button on the edit screens if TinyMCE is ON
	function WYS_button_init() {
		
		//	Set up some unique button image URLs for the new buttons to use in the WYSIWYG toolbar (does nothing in the Quicktags)
		$Pullquote_button_image_url = buttonsnap_dirname(__FILE__) . '/jspullquote.gif';
		
		//	Create a vertical separator in the WYSI toolbar (does nothing in the Quicktags)
		buttonsnap_separator();
		//	Create a button that uses Ajax to fetch replacement text from a WordPress plugin hook sink
		buttonsnap_ajaxbutton($Pullquote_button_image_url, 'pull-quote', 'Pullquote_insert_hook');
		
		//	Add the filter to match the hook and sink together
		add_filter('Pullquote_insert_hook', array('jspquote','Pullquote_insert_sink'));

		// Add CSS to mark the pull-quoted text in the editor
		//add_action('admin_head', array('jspquote','WYS_style_editor'));
	}

	//	Function below corresponds to one button and matches an above add_filter.  The filter returns the selected text AND the before and after text found in single quotes.
	function Pullquote_insert_sink($selectedtext) {
		return '<span class="pullquote">' . stripslashes($selectedtext) . '</span>';
	}

// =======================
// END WYSIWYG BUTTON CODE
// =======================

} // end class

?>