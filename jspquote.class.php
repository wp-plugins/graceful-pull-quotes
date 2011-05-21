<?php 

// wrap it all in a class to avoid function name conflicts
class jspquote {

	function get_pqcss($theStyle) {
		If (file_exists(TEMPLATEPATH . '/jspullquotes.css')) {
			$theStyle = get_bloginfo('stylesheet_directory') . '/jspullquotes.css';
		} else {
			$styleDir = get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/styles/';
			if (!$theStyle == "") {
				if (file_exists(dirname(__FILE__) . '/styles/' . $theStyle . '/pullquote.css')) {
					$theStyle = $styleDir . $theStyle . '/pullquote.css';
				} else {
					$theStyle = $styleDir . $theStyle;
				}
			} else {
				$theStyle = $styleDir . 'Default.css';
			}
		}
		return $theStyle;
	}

// This adds the needed links to the <head> of each blog page
	function wphead() {
		global $version;
		$options = get_option('jspullquotes_options');
		echo '
<!-- JavaScript Pull-Quotes plugin v' . $version . '
	http://striderweb.com/nerdaphernalia/features/wp-javascript-pull-quotes/ -->' . "\n";
		echo '	<link rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/jspullquotes-core.css" type="text/css" />' . "\n";

		$currStyle = jspquote::get_pqcss($options['style_name']);
		echo '	<link rel="stylesheet" href="' . $currStyle . '" type="text/css" />' . "\n";
		echo '	<script type="text/javascript" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/jspullquotes.js"></script>' . "\n";
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
		pullQuoteOpts(arrOptions);
	</script>
<!-- end pull-quote additions -->
';
	}
	

// =======================
// START OPTIONS PAGE CODE
// =======================
	
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

	// adds script for pull-quote previews
	function adminhead() {
		global $version;
?>
<!-- JavaScript Pull-Quotes plugin v<?php echo($version) ?> -->
	<script src="<?php echo(get_bloginfo('wpurl')); ?>/wp-content/plugins/jspullquotes/preview.js" type="text/javascript" language="JavaScript" charset="utf-8"></script>
<!-- end pull-quote additions -->
<?php
	}

/*	Options Panel code for the Wordpress wp-admin screen. */
	function options_panel() {	
		global $version;
		if (isset($_POST['jspullquotes_options_submit'])) {
	?>
			<div id="message" class="updated fade"><p><strong><?php 
			_e('Options saved.') ?></strong></p></div>
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

		// Get array of CSS files for Style dropdown
		$arrStyles = array();
		if ($handle = opendir(dirname(__FILE__) . '/styles')) {
		   while (false !== ($file = readdir($handle))) {
			   if ($file != '.' && $file != '..' && substr($file,1) != '.') {
					$arrStyles[] = $file;
				}
		   }
		   closedir($handle);
		}

	?>
<div class="wrap">
	<form action="themes.php?page=jspullquotes.php" method="post">
		<div style="width: 10em; float: right; border: 1px black solid; margin: 0 0 1em 1em; padding: 0.75em; background-color: #FDFBDE; text-size: 1.2em;">
			<a href="templates.php?file=<?php echo(substr(jspquote::get_pqcss($jspq_options['style_name']),strlen(get_bloginfo('wpurl')))); ?> "\"><?php _e('Edit the currently active stylesheet','jspq'); ?>: <?php echo($jspq_options['style_name']); ?></a>
		</div>
		<fieldset name="basic">
			<legend style="font-size: 120%;"><?php _e('Basic Options','jspq'); ?></legend>
			<p><input type="checkbox" name="jspullquotes_options[skip_links]" id="skip_links" value="true"<?php echo(jspquote::checkflag($jspq_options,'skip_links')); ?> /> <label for="skip_links"><?php _e('Remove link information from pull-quotes','jspq'); ?></label> <strong><?php _e('(recommended)','jspq'); ?></strong></p>
<?php
		if ($arrStyles[1] != null) {
		?>
			<p><label for="style_name"><?php _e('Pull-quote style','jspq'); ?>: </label><select name="jspullquotes_options[style_name]" id="style_name">
<?php
			$i = 0;
			while ($arrStyles[$i] != null) {
				$style = $arrStyles[$i];
				if ($style == 'Default.css') {
					$deftag = true;
				} else {
					$deftag = '';
				}
				echo "				<option value=\"" . $style . "\"" . jspquote::checkcombo($jspq_options,'style_name',$style,$deftag) . ">" . basename($style,'.css') . "</option>\n";
				$i++;
			}

			$stylePath = get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/';

?>			</select> (<a href="#" onclick="previewpop('<?php echo($stylePath); ?>','jspullquotes-core.css',document.getElementById('style_name').value); return false;" title="<?php _e('show a preview of the selected style in a pop-up window','jspq'); ?>"><?php _e('preview','jspq'); ?></a>)</p>
<?php	}
?>
			<p><label for="def_side"><?php _e('Display quotes on','jspq'); ?> <select name="jspullquotes_options[def_side]" id="def_side">
				<option value="left"<?php echo(jspquote::checkcombo($jspq_options,'def_side','left',true)); ?>><?php _e('left','jspq'); ?></option>
				<option value="right"<?php echo(jspquote::checkcombo($jspq_options,'def_side','right')); ?>><?php _e('right','jspq'); ?></option>
			</select> <?php _e('side','jspq'); ?></label></p>
			<p><input type="checkbox" name="jspullquotes_options[alt_sides]" id="alt_sides" value="true"<?php echo(jspquote::checkflag($jspq_options,'alt_sides')); ?> /> <label for="alt_sides"><?php _e('Successive quotes on one page alternate sides','jspq'); ?></label></p>
			<p><input type="checkbox" name="jspullquotes_options[alt_text]" id="alt_text" value="true"<?php echo(jspquote::checkflag($jspq_options,'alt_text')); ?> /> <label for="alt_text"><?php _e('Use alternate text if available','jspq'); ?></label> (<a href="../wp-content/plugins/jspullquotes/help/alt-text-info.htm"><?php _e('how?','jspq'); ?></a>)</p>
		</fieldset>
		<fieldset name="advanced">
		<legend style="font-size: 120%;"><?php _e('Advanced Options','jspq'); ?></legend>
<!--			<p><strike><input type="checkbox" name="jspullquotes_options[use_beta]" id="use_beta" value="true"<?php echo(jspquote::checkflag($jspq_options,'use_beta')); ?> /> <label for="use_beta">(BETA) Use pull-quote button in rich-text post editor</label></strike></p> -->
			<p><label for="q_container"><?php _e('Contain pull-quote in an HTML','jspq'); ?></label> <select name="jspullquotes_options[q_container]" id="q_container">
				<option value="blockquote"<?php echo(jspquote::checkcombo($jspq_options,'q_container','blockquote',true)); ?>>&lt;blockquote&gt;</option>
				<option value="div"<?php echo(jspquote::checkcombo($jspq_options,'q_container','div')); ?>>&lt;div&gt;</option>
				</select></p>
			<p><?php _e('Note: changing any of the following may require you to update your CSS file.','jspq'); ?></p>
			<p><label for="quote_class"><?php _e('CSS class selector for default pull-quote:','jspq'); ?></label> <input type="text" name="jspullquotes_options[quote_class]" id="quote_class" value="<?php echo(jspquote::checktext($jspq_options,'quote_class','pullquote')); ?>" /></p>
			<p><label for="quote_class_alt"><?php _e('CSS class selector for alt-side pull-quote:','jspq'); ?></label> <input type="text" name="jspullquotes_options[quote_class_alt]" id="quote_class_alt" value="<?php echo(jspquote::checktext($jspq_options,'quote_class_alt','pullquote pqRight')); ?>"/></p>
		</fieldset>
		<div class="submit">
			<input type="submit" name="jspullquotes_options_submit" value="<?php _e('Update Options &raquo;') ?>" /></div>
	</form>
	JavaScript Pull-Quotes v<?php echo($version) ?> -- <a href="http://striderweb.com/nerdaphernalia/features/wp-javascript-pull-quotes/">home page</a>
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
			echo '	<script type="text/javascript" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/jspullquotes/quicktags/jspullquotes_quicktags.js"></script>' . "\n";
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

	function closePageHead() {
		echo '<!-- end pull-quote additions -->
';
	}

} // end class

?>