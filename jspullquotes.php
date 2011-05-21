<?php
/*
Plugin Name: JavaScript Pull-Quotes
Plugin URI: http://striderweb.com/nerdaphernalia/features/wp-javascript-pull-quotes/
Description: Allows you to create customizable magazine-style pull-quotes without duplicating text in your markup or feeds.  (NOTE: This plugin is <em>not</em> automatically checked for updates.)
Version: 2.0
Tested For WP: 2.5.1
Date: 2008-06-08
Author: Stephen Rider
Author URI: http://striderweb.com/
*/

/* To Do
	TO DO: modify set_defaults() so it fills in missing attributes when possible (also add setting that stores last plugin version used -- if just updated we can trigger setting of new settings' defaults)
	TO DO: fix Styles select:
		o DONE: if only one style available, choose that and disable control
		o if selected style is missing, select Default
		o ?? move Default to "resources" so it's harder to misplace 
	TO DO: allow user to specify location of "styles" folder
	TO DO: add optional "Uninstall" routine to deactivation hook
	TO DO: add headers to Styles to display custom name, author info, etc.
	TO DO: find better way to combine style and "core" CSS files into single call
	TO DO: (??) Allow for semi-random styling a la "Fancy Pullquotes" plugin
		ALSO See "to do"s in jspullquotes.js file
*/

define( 'JSPQPATH', PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) );
load_plugin_textdomain( 'jspullquotes', JSPQPATH );

// wrap it all in a class to avoid function name conflicts
class jspullquotes {

	function add_actions() {
		register_activation_hook( __FILE__, array(&$this, 'set_defaults') );
		add_action( 'wp_head', array(&$this, 'wp_head') );
		add_action( 'admin_menu', array(&$this, 'add_settings_page') );
	}

	function get_plugin_data( $param = null ) {
		// You can optionally pass a specific value to fetch, e.g. 'Version' -- but it's inefficient to do that multiple times
		// Values as of WP 2.5.1: 'Name', 'Title', 'Description', 'Author', 'Version'
		if( !function_exists( 'get_plugin_data' ) ) require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$plugin_data = get_plugin_data( __FILE__ );
		if( $param && is_array($plugin_data)  ) {
			foreach( $plugin_data as $key => $value ) {
				if( $param == $key ){
					$plugin_data = $value;
				}
			}
		}
		return $plugin_data;
	}

// abstracting l18n functions so I don't have to pass domain each time
	function __( $string ) {
		return __( $string,'jspullquotes' );
	}
	function _e( $string ) {
		_e( $string, 'jspullquotes' );
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
				'skip_internal_links' => true, 
				'q_container' => 'blockquote', 
				'quote_class' => 'pullquote', 
				'quote_class_alt' => 'pullquote pqRight' ) );
		}
	}
	
// Fetch the stylesheet used for the active pullquote style
	function get_pqcss( $theStyle ) {
		If ( file_exists( TEMPLATEPATH . '/jspullquotes.css' ) ) {
		// A stylesheet embedded in a WordPress theme overrides Options Page selection
			$theStyle = get_bloginfo( 'stylesheet_directory' ) . '/jspullquotes.css';
		} else {
			$styleDir = get_bloginfo( 'wpurl' ) . '/' . JSPQPATH . '/styles/';
			if ( !$theStyle == "" ) {
				if (file_exists(ABSPATH . '/' . JSPQPATH . '/styles/' . $theStyle . '/pullquote.css')) {
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

// This adds the links to the <head> of each blog page
	function wp_head() {
		$options = get_option('jspullquotes_options');
		$pluginpath = get_bloginfo('wpurl') . '/' . JSPQPATH;
		$plugin_version = $this->get_plugin_data('Version');
		$optionsarray = '"' .
			$options['skip_links'] . '", "' . 
			$options['skip_internal_links'] . '", "' . 
			$options['def_side'] . '", "' . 
			$options['alt_sides'] . '", "' . 
			$options['alt_text'] . '", "' .
			$options['cap_first'] . '", "' .
			$options['q_container'] . '", "' .
			$options['quote_class'] . '", "' .
			$options['quote_class_alt'] . '"';
		$stylelinks = '';
		if( $options['omit_styles'] == false ) {
			$currStyle = $this->get_pqcss($options['style_name']);
			$stylelinks = <<<EOT
	<link rel="stylesheet" href="{$pluginpath}/resources/jspullquotes-core.css" type="text/css" />
	<link rel="stylesheet" href="{$currStyle}" type="text/css" />\n
EOT;
		}
		echo <<<EOT
<!-- JavaScript Pull-Quotes plugin v{$plugin_version} -->
{$stylelinks}	<script type="text/javascript" src="{$pluginpath}/resources/jspullquotes.js"></script>
	<script type="text/javascript">
		var arrOptions = new Array({$optionsarray});
		pullQuoteOpts(arrOptions);
	</script>
<!-- end pull-quote additions -->\n
EOT;
	}
	

// ========================
// START SETTINGS PAGE CODE
// ========================

// Add the configuration screen to the Design menu in Admin
	function add_settings_page() {
		if ( function_exists('add_theme_page') ) {
			$page = add_theme_page( $this->__('Pull-Quote Settings'), $this->__('Pull-Quotes'), 'switch_themes', 'pull-quotes', array(&$this,'settings_page') );
			// Note: $page == 'design_page_pull-quotes'

			add_action( "admin_head-$page", array(&$this, 'admin_head') );
			add_action( 'in_admin_footer', array(&$this, 'admin_footer') );
			add_filter('plugin_action_links', array(&$this, 'filter_plugin_actions'), 10, 2);
		}
	}

// Add "preview" script to settings page head
	function admin_head() {
		$scriptpath = get_bloginfo('wpurl') . '/' . JSPQPATH;
		echo <<<EOT
<!-- JavaScript Pull-Quotes plugin -->
	<script src="{$scriptpath}/resources/preview.js" type="text/javascript" language="JavaScript" charset="utf-8"></script>
<!-- end pull-quote additions -->\n
EOT;
	}

// Add homepage link to settings page footer
	function admin_footer() {
		if( basename($_SERVER['REQUEST_URI']) == 'themes.php?page=pull-quotes') {
			$plugin_data = $this->get_plugin_data();
			echo ( $plugin_data['Title'] . ' ' . $this->__('plugin | Version') . ' ' . $plugin_data['Version'] . '<br />' );
		}
	}

// Add action link(s) to plugins page
	function filter_plugin_actions($links, $file){
// Thanks Dion Hulse -- http://dd32.id.au/wordpress-plugins/?configure-link
		//Static so we don't call plugin_basename on every plugin row.
		static $this_plugin;
		if( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

		if( $file == $this_plugin ){
			$settings_link = '<a href="themes.php?page=pull-quotes">' . __('Settings') . '</a>';
			$links = array_merge( array($settings_link), $links); // before other links
//			$links[] = $settings_link; // ... or after other links
		}
		return $links;
	}

// these three functions are used by the settings page to display set options in the form controls when the page is opened

// for checkboxes
	function checkflag( $options, $optname ) {
		return $options[$optname] ? ' checked="checked"' : '';
	}

// for text boxes
	function checktext( $options, $optname, $optdefault = '' ) {
		return $options[$optname] ? $options[$optname] : $optdefault;
	}

// for dropdowns
	function checkcombo( $options, $optname, $thisopt, $is_default = false ) {
		return (
			( $is_default && !$options[$optname] ) ||
			$options[$optname] == $thisopt
		) ? ' selected="selected"' : '';
	}

// finally, the Settings Page itself
	function settings_page() {	
		if (isset($_POST['save_settings'])) {
			check_admin_referer('jspullquotes-update-options');
			update_option('jspullquotes_options', $_POST['jspullquotes_options']);
			echo '<div id="message" class="updated fade"><p><strong>' . __('Settings saved.') . '</strong></p></div>';
		} 
		
		// get options for use in formsetting functions
		$opts = get_option('jspullquotes_options');

		// Get array of CSS files for Style dropdown
		$arrStyles = array();
		if ($handle = opendir(ABSPATH . '/' . JSPQPATH . '/styles')) {
		   while (false !== ($file = readdir($handle))) {
			   if ( $file != '.' && $file != '..' && substr($file,0,1) != '.' ) {
					$arrStyles[] = $file;
				}
		   }
		   closedir($handle);
		}
		if ( !$arrStyles[0] ) $arrStyles[0] = "Default";

	?>
<div class="wrap">
	<h2><?php $this->_e('Pull-Quote Settings'); ?></h2>
	<form action="themes.php?page=pull-quotes" method="post">
		<?php
		if ( function_exists('wp_nonce_field') )
			wp_nonce_field('jspullquotes-update-options');
		?>
		<h3><?php $this->_e('Basic Options'); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Appearance</th>
					<td><?php
		if ( $arrStyles[0] ) {
		?><label for="style_name">
						<select name="jspullquotes_options[style_name]" id="style_name"<?php echo( $arrStyles[1] ? '' : ' class="code disabled" disabled="disabled"' ) ?>>
<?php
			if( !$arrStyles[1] ) {
				// if only one style exists, update the settings
				$opts['style_name'] = $arrStyles[0];
				update_option( 'jspullquotes_options', $opts );
			}
			for ($i = 0; $arrStyles[$i] != null; $i++) {
				$style = $arrStyles[$i];
				if ($style == 'Default.css') {
					$deftag = true;
				} else {
					$deftag = '';
				}
				echo "							<option value=\"" . $style . "\"" . $this->checkcombo($opts,'style_name',$style,$deftag) . ">" . basename($style,'.css') . "</option>\n";
			}

			$stylePath = get_bloginfo('wpurl') . '/' . JSPQPATH;

?>						</select>
					</label> (<a href="#" onclick="pullquote_preview_pop('<?php echo($stylePath); ?>','resources/jspullquotes-core.css',document.getElementById('style_name').value); return false;" title="<?php $this->_e('show a preview of the selected style in a pop-up window'); ?>"><?php $this->_e('preview'); ?></a>)<?php
					
	} else {
			// if nothing in Styles directory....
		}
?><br />
					Note: a "jspullquotes.css" file in the active Theme directory will override this setting</td>
				</tr>
<?php
		$cmbpicksides = '<select name="jspullquotes_options[def_side]" id="def_side">
							<option value="left"' . $this->checkcombo($opts,'def_side','left',true) . '>' . $this->__('left') . '</option>
							<option value="right"' . $this->checkcombo($opts,'def_side','right') . '>' . $this->__('right') . '</option>
						</select>';
?>
				<tr valign="top">
					<th scope="row">Position</th>
					<td><label for="def_side">
						<?php echo(sprintf($this->__('Display quotes on %s side'), $cmbpicksides)); ?></label><br />
						<label for="alt_sides"><input type="checkbox" name="jspullquotes_options[alt_sides]" id="alt_sides" value="true"<?php echo($this->checkflag($opts,'alt_sides')); ?> /> <?php $this->_e('Successive quotes on one page alternate sides'); ?></label><br />
						<label for="alt_text"><input type="checkbox" name="jspullquotes_options[alt_text]" id="alt_text" value="true"<?php echo($this->checkflag($opts,'alt_text')); ?> /> <?php $this->_e('Use alternate text if available'); ?></label> (<a href="<?php echo(get_bloginfo('wpurl') . '/' . JSPQPATH) ?>/resources/help/alt-text-info.<?php $this->_e('en_US'); ?>.htm"><?php $this->_e('how?'); ?></a>)</td>
				</tr>
			</tbody>
		</table>

		<h3><?php $this->_e('Advanced Options'); ?></h3>
<?php
		$cmbq_container = '<select name="jspullquotes_options[q_container]" id="q_container">
							<option value="blockquote"' . $this->checkcombo($opts,'q_container','blockquote',true) . '>&lt;blockquote&gt;</option>
							<option value="div"' . $this->checkcombo($opts,'q_container','div') . '>&lt;div&gt;</option>
						</select>';
?>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">HTML Container</th>
					<td><label for="q_container">
						<?php echo $cmbq_container . ' ' . $this->__('Type of tag that will contain the pull-quote') ?></label></td>
				</tr>
				<tr valign="top">
					<th scope="row">HTML Links</th>
					<td><label for="skip_links"><input type="checkbox" name="jspullquotes_options[skip_links]" id="skip_links" value="true"<?php echo($this->checkflag($opts,'skip_links')); ?> /> <?php $this->_e('Remove external links from pull-quotes'); ?></label> <strong><?php $this->_e('(recommended)'); ?></strong><br />
						<label for="skip_internal_links"><input type="checkbox" name="jspullquotes_options[skip_internal_links]" id="skip_internal_links" value="true"<?php echo($this->checkflag($opts,'skip_internal_links')); ?> /> <?php $this->_e('Remove internal links (href="#id") from pull-quotes'); ?></label></td>
				</tr>
				<tr valign="top">
					<th scope="row"><acronym title="Cascading Style Sheets">CSS</acronym></th>
					<td><?php $this->_e('Note: changing any of the following may require you to update your CSS file.'); ?><br />
						<label for="omit_styles"><input type="checkbox" name="jspullquotes_options[omit_styles]" id="omit_styles" value="true"<?php echo($this->checkflag($opts,'omit_styles')); ?> /> <?php $this->_e('Do not link CSS'); ?></label><br /><?php $this->_e('(Check this if you prefer to manually put your pull-quote styles elsewhere)'); ?><br />
						<label for="quote_class"><input type="text" name="jspullquotes_options[quote_class]" id="quote_class" value="<?php echo($this->checktext($opts,'quote_class','pullquote')); ?>" /> <?php $this->_e('Class selector for default pull-quote'); ?></label><br />
						<label for="quote_class_alt"><input type="text" name="jspullquotes_options[quote_class_alt]" id="quote_class_alt" value="<?php echo($this->checktext($opts,'quote_class_alt','pullquote pqRight')); ?>"/> <?php $this->_e('Class selector for alt-side pull-quote'); ?></label>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="submit">
			<input type="submit" name="save_settings" value="<?php _e('Save Changes') ?>" /></div>
	</form>
</div><!-- wrap -->
	<?php
	}

// ======================
// END SETTINGS PAGE CODE
// ======================

} // end class

$jspullquotes = new jspullquotes;
$jspullquotes->add_actions();

?>