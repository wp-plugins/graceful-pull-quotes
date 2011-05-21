<?php
/*
Plugin Name: JavaScript Pull-Quotes
Plugin URI: http://striderweb.com/nerdaphernalia/features/wp-javascript-pull-quotes/
Description: Allows you to create customizable magazine-style pull-quotes without duplicating text in your markup or feeds.  (NOTE: This plugin is <em>not</em> automatically checked for updates.)
Version: 1.7.0
Date: 2008-06-07
Author: Stephen Rider
Author URI: http://striderweb.com/
*/

// DONE: add check so preview script only appears in the one Admin page
// TO DO: fix Admin screen appearance for WP 2.5
// DONE: add "Settings" link to plugins page
// TO DO: add "Uninstall" routine to deactivation hook
// TO DO: fix extended characters in alt-text comments
// TO DO: clean up options_panel() code.
// TO DO: add headers to Styles to display custom name, author info, etc.
// TO DO:  Add filter hook so other plugins can make changes to pull-quotes
// TO DO: (??) longer term: add page that showcases different Styles, akin to Themes page
// ALSO See "to do"s in jspullquoes.js file 

define( 'JSPQPATH', PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) );
load_plugin_textdomain( 'jspq', JSPQPATH );

// wrap it all in a class to avoid function name conflicts
class jspullquotes {

	function add_actions() {
		register_activation_hook( __FILE__, array(&$this, 'set_defaults') );
		add_action( 'wp_head', array(&$this, 'wphead') );
		add_action( 'admin_menu', array(&$this, 'add_settings_page') );
	}

	function get_plugin_data() {
		/* You can optionally pass a specific value to fetch, e.g. 'Version' -- but it's inefficient to do that multiple times */
		if( !function_exists( 'get_plugin_data' ) ) require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$plugin_data = get_plugin_data( __FILE__ );

		$plugin_data['URI'] = "http://striderweb.com/nerdaphernalia/features/wp-javascript-pull-quotes/";

		$param = func_get_args();
		if( is_array($plugin_data) && !empty($param) ) {
			foreach( $plugin_data as $key => $value ) {
				if( $param[0] == $key ){
					$plugin_data = $value;
				}
			}
		}
		return $plugin_data;
	}

// abstracting l18n functions so I don't have to pass domain each time
	function __( $string ) {
		return __( $string,'jspq' );
	}
	function _e( $string ) {
		_e( $string, 'jspq' );
	}

	function set_defaults( $reset = false ) {
		if ( $reset ) delete_option( 'jspullquotes_options' );
		if (!get_option('jspullquotes_options')){
			// if options not set, set defaults
			add_option ('jspullquotes_options',array(
				'style_name' => 'Default.css', 
				'def_side' => 'right', 
				'alt_sides' => true, 
				'alt_text' => true, 
				'skip_links' => true, 
				'q_container' => 'blockquote', 
				'quote_class' => 'pullquote', 
				'quote_class_alt' => 'pullquote pqRight' ) );
		}
	}
	
// Fetch the stylesheet used for the active pullquote style
	function get_pqcss( $theStyle ) {
		If ( file_exists( TEMPLATEPATH . '/jspullquotes.css' ) ) {
			$theStyle = get_bloginfo( 'stylesheet_directory' ) . '/jspullquotes.css';
		} else {
			$styleDir = get_bloginfo( 'wpurl' ) . '/' . JSPQPATH . '/styles/';
			if ( !$theStyle == "" ) {
				if (file_exists(ABSPATH . '/' . JSPQPATH . '/styles/' . $theStyle . '/pullquote.css')) {
	// A stylesheet embedded in a WordPress theme overrides Options Page selection
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
		$options = get_option('jspullquotes_options');
		echo '
<!-- JavaScript Pull-Quotes plugin v' . $this->get_plugin_data('Version') . " -->\n";
		if( $options['omit_styles'] == false ) {
			$currStyle = $this->get_pqcss($options['style_name']);
			echo '	<link rel="stylesheet" href="' . get_bloginfo('wpurl') . '/' . JSPQPATH . '/files/jspullquotes-core.css" type="text/css" />' . "\n";
			echo '	<link rel="stylesheet" href="' . $currStyle . '" type="text/css" />' . "\n";
		}
		echo '	<script type="text/javascript" src="' . get_bloginfo('wpurl') . '/' . JSPQPATH . '/files/jspullquotes.js"></script>' . "\n";
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

// Add the configuration screen to the Presentation menu in Admin
	function add_settings_page() {
		if ( function_exists('add_theme_page') ) {
			// Add the page to the menu
//			$page = add_theme_page( $this->__('Customize Pull-Quotes'), $this->__('Pull-Quotes'), 'switch_themes', 'pull-quotes', array(&$this,'options_panel') );
			$page = add_submenu_page( 'themes.php', $this->__('Customize Pull-Quotes'), $this->__('Pull-Quotes'), 'switch_themes', 'pull-quotes', array(&$this,'options_panel') );
// $page == 'design_page_pull-quotes'
error_log($page);
			// Add "preview" script to the head of the settings page
			add_action( "admin_head-$page", array(&$this, 'adminhead') );
			// Add "Settings" link to plugins page
			add_filter('plugin_action_links', array(&$this, 'filter_action_links'), 10, 2);
		}
	}

	function filter_action_links($links, $file){
// Thanks Dion Hulse -- http://dd32.id.au/wordpress-plugins/?configure-link
		//Static so we don't call plugin_basename on every plugin row.
		static $this_plugin;
		if( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

		if( $file == $this_plugin ){
			$link = '<a href="themes.php?page=pull-quotes">Settings</a>';
			$links = array_merge( array($link), $links); // before other links
//			$links[] = $link; // after other links
		}
		return $links;
	}



// these three functions are used by the options page to display set options in the form controls when the page is opened

// for checkboxes
	function checkflag( $options, $optname ) {
		if( $options[$optname] != 'true' ) return '';
		return ' checked="checked"';
	}

// for text boxes
	function checktext( $options, $optname, $optdefault = '' ) {
		if( !$options[$optname] ) return $optdefault;
		return $options[$optname];
	}

// for dropdowns
	function checkcombo( $options, $optname, $thisopt, $optdefault = false ) {
		if( ( !$options[$optname] && $optdefault == true ) || $options[$optname] == $thisopt ) return ' selected="selected"';
		return '';
	}

	// adds script for pull-quote previews
	function adminhead() {
// TODO: put in check so this only runs on the pull-quotes admin page
		if (true) {
?>
<!-- JavaScript Pull-Quotes plugin -->
	<script src="<?php echo(get_bloginfo('wpurl') . '/' . JSPQPATH); ?>/files/preview.js" type="text/javascript" language="JavaScript" charset="utf-8"></script>
<!-- end pull-quote additions -->
<?php
		}
	}

/*	Options Panel code for the Wordpress wp-admin screen. */
	function options_panel() {	
		if (isset($_POST['jspullquotes_options_submit'])) {
			check_admin_referer('jspullquotes-update-options');
			update_option('jspullquotes_options', $_POST['jspullquotes_options']);
			echo '<div id="message" class="updated fade"><p><strong>' . __('Options saved.') . '</strong></p></div>';
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
		if ($handle = opendir(ABSPATH . '/' . JSPQPATH . '/styles')) {
		   while (false !== ($file = readdir($handle))) {
			   if ($file != '.' && $file != '..' && substr($file,1) != '.') {
					$arrStyles[] = $file;
				}
		   }
		   closedir($handle);
		}

	?>
<div class="wrap">
	<h2><?php $this->_e('Pull-Quotes'); ?></h2>
	<form action="themes.php?page=pull-quotes" method="post">
		<?php
		if ( function_exists('wp_nonce_field') )
			wp_nonce_field('jspullquotes-update-options');
		?>
		<fieldset name="basic">
			<legend style="font-size: 120%;"><?php $this->_e('Basic Options'); ?></legend>
<?php
		if ($arrStyles[1] != null) {
		?>
			<p><label for="style_name"><?php $this->_e('Pull-quote style'); ?>: </label><select name="jspullquotes_options[style_name]" id="style_name">
<?php
			$i = 0;
			while ($arrStyles[$i] != null) {
				$style = $arrStyles[$i];
				if ($style == 'Default.css') {
					$deftag = true;
				} else {
					$deftag = '';
				}
				echo "				<option value=\"" . $style . "\"" . $this->checkcombo($jspq_options,'style_name',$style,$deftag) . ">" . basename($style,'.css') . "</option>\n";
				$i++;
			}

			$stylePath = get_bloginfo('wpurl') . '/' . JSPQPATH . '/';

?>			</select> (<a href="#" onclick="pullquote_preview_pop('<?php echo($stylePath); ?>','files/jspullquotes-core.css',document.getElementById('style_name').value); return false;" title="<?php $this->_e('show a preview of the selected style in a pop-up window'); ?>"><?php $this->_e('preview'); ?></a>)</p>
<?php	}
		$cmbpicksides = '<select name="jspullquotes_options[def_side]" id="def_side">
				<option value="left"' . $this->checkcombo($jspq_options,'def_side','left',true) . '>' . $this->__('left') . '</option>
				<option value="right"' . $this->checkcombo($jspq_options,'def_side','right') . '>' . $this->__('right') . '</option>
			</select>';
?>
			<p><label for="def_side"><?php echo(sprintf($this->__('Display quotes on %s side'), $cmbpicksides)); ?></label></p>
			<p><input type="checkbox" name="jspullquotes_options[alt_sides]" id="alt_sides" value="true"<?php echo($this->checkflag($jspq_options,'alt_sides')); ?> /> <label for="alt_sides"><?php $this->_e('Successive quotes on one page alternate sides'); ?></label></p>
			<p><input type="checkbox" name="jspullquotes_options[alt_text]" id="alt_text" value="true"<?php echo($this->checkflag($jspq_options,'alt_text')); ?> /> <label for="alt_text"><?php $this->_e('Use alternate text if available'); ?></label> (<a href="<?php echo(get_bloginfo('wpurl') . '/' . JSPQPATH) ?>/files/help/alt-text-info.<?php $this->_e('en_US'); ?>.htm"><?php $this->_e('how?'); ?></a>)</p>
		</fieldset>
		<fieldset name="advanced">
		<legend style="font-size: 120%;"><?php $this->_e('Advanced Options'); ?></legend>
<?php
		$cmbq_container = '<select name="jspullquotes_options[q_container]" id="q_container">
				<option value="blockquote"' . $this->checkcombo($jspq_options,'q_container','blockquote',true) . '>&lt;blockquote&gt;</option>
				<option value="div"' . $this->checkcombo($jspq_options,'q_container','div') . '>&lt;div&gt;</option>
				</select>';
?>
			<p><label for="def_side"><?php echo(sprintf($this->__('Contain pull-quote in an HTML %s'), $cmbq_container)); ?></label></p>
			<p><input type="checkbox" name="jspullquotes_options[skip_links]" id="skip_links" value="true"<?php echo($this->checkflag($jspq_options,'skip_links')); ?> /> <label for="skip_links"><?php $this->_e('Remove link information from pull-quotes'); ?></label> <strong><?php $this->_e('(recommended)'); ?></strong></p>
			<p><?php $this->_e('Note: changing any of the following may require you to update your CSS file.'); ?></p>
			<p><input type="checkbox" name="jspullquotes_options[omit_styles]" id="omit_styles" value="true"<?php echo($this->checkflag($jspq_options,'omit_styles')); ?> /> <label for="omit_styles"><?php $this->_e('Do not link CSS'); ?></label> <?php $this->_e('(Check this if you prefer to manually put your pull-quote styles elsewhere)'); ?></p> 
			<p><label for="quote_class"><?php $this->_e('CSS class selector for default pull-quote:'); ?></label> <input type="text" name="jspullquotes_options[quote_class]" id="quote_class" value="<?php echo($this->checktext($jspq_options,'quote_class','pullquote')); ?>" /></p>
			<p><label for="quote_class_alt"><?php $this->_e('CSS class selector for alt-side pull-quote:'); ?></label> <input type="text" name="jspullquotes_options[quote_class_alt]" id="quote_class_alt" value="<?php echo($this->checktext($jspq_options,'quote_class_alt','pullquote pqRight')); ?>"/></p>
		</fieldset>
		<div class="submit">
			<input type="submit" name="jspullquotes_options_submit" value="<?php _e('Update Options &raquo;') ?>" /></div>
	</form>
	<hr />
	<?php
		$plugin_data = $this->get_plugin_data();
		echo ($plugin_data['Title'] . ' v' . $plugin_data['Version'] );
	?>
</div><!-- wrap -->
	<?php
	}

// =====================
// END OPTIONS PAGE CODE
// =====================

// =========================
// START WYSIWYG BUTTON CODE
// =========================

//NOTE: none of this is currently used.  I'm going to wait until WordPress standardizes a method for adding quicktags, as currently things break every time a new version comes out.

// Make a button on the edit screens if TinyMCE is OFF
	function edit_button_init() {
		if (function_exists('user_can_richedit') && !user_can_richedit()) {
			buttonsnap_jsbutton('', 'pull-quote', 'pullquotesButton();');
			echo '	<script type="text/javascript" src="' . get_bloginfo('wpurl') . '/' . JSPQPATH . '/quicktags/jspullquotes_quicktags.js"></script>' . "\n";
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
		add_filter( 'Pullquote_insert_hook', array( &$this, 'Pullquote_insert_sink' ) );

		// Add CSS to mark the pull-quoted text in the editor
		//add_action('admin_head', array('jspullquotes','WYS_style_editor'));
	}

	//	Function below corresponds to one button and matches an above add_filter.  The filter returns the selected text AND the before and after text found in single quotes.
	function Pullquote_insert_sink($selectedtext) {
		return '<span class="pullquote">' . stripslashes($selectedtext) . '</span>';
	}

// =======================
// END WYSIWYG BUTTON CODE
// =======================

} // end class

$jspullquotes = new jspullquotes;

$jspullquotes->add_actions();

?>