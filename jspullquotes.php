<?php
/*
Plugin Name: JavaScript Pull-Quotes
Plugin URI: http://striderweb.com/nerdaphernalia/features/wp-javascript-pull-quotes/
Description: Allows you to create customizable magazine-style pull-quotes without duplicating text in your markup or feeds.
Version: 2.1
Tested For WP: 2.6-rc1
Date: 2008-07-13
Author: Stephen Rider
Author URI: http://striderweb.com/
*/

/* To Do
	TO DO:	fix Styles select:
		-	DONE: if only one style available, choose that and disable control
		-	if selected style is missing, select Default
		-	?? move Default to "resources" so it's harder to misplace 
	TO DO:	allow user to specify location of "styles" folder
	TO DO:	add optional "Uninstall" routine to deactivation hook
	TO DO:	add headers to Styles to display custom name, author info, etc.
	TO DO:	find better way to combine style and "core" CSS files into single call
	TO DO:	(??) Allow for semi-random styling a la "Fancy Pullquotes" plugin
	TO DO:	Fix encoding bug for extended ASCII text in alt-text comments
		-	Maybe related to WP core bug: http://trac.wordpress.org/ticket/3603
	TO DO:	Option: [B]racket-capitalize quotes starting with lowercase letter
	TO DO:	Allow for quote to appear at top of Post instead of top of paragraph (requires user to specify post div class)
*/

// wrap it all in a class to avoid function name conflicts
class jspullquotes {

	var $option_name = 'plugin_jspullquotes_settings';
	var $plugin_path;
	var $plugin_url;

	function init() {
		$this->set_plugin_paths();
		$this->compare_versions();

		load_plugin_textdomain( 'jspullquotes', $this->plugin_path );

		add_action( 'wp_head', array(&$this, 'wp_head') );
		add_action( 'admin_menu', array(&$this, 'add_settings_page') );
		
	}

	function set_plugin_paths() {
		//  2.6 is coming -- Prepare Ye The Way
		if ( !defined('WP_CONTENT_DIR') ) define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
		if ( !defined('WP_CONTENT_URL') ) define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');

		$this->plugin_path = WP_CONTENT_DIR . '/plugins/' . plugin_basename(dirname(__FILE__));
		$this->plugin_url = WP_CONTENT_URL.'/plugins/' . plugin_basename(dirname(__FILE__));
	}

	function get_plugin_data( $param = null ) {
		// You can optionally pass a specific value to fetch, e.g. 'Version' -- but it's inefficient to do that multiple times
		// As of WP 2.5.1: 'Name', 'Title', 'Description', 'Author', 'Version'
		if( !function_exists( 'get_plugin_data' ) ) require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		static $plugin_data;
		if( !$plugin_data ) $plugin_data = get_plugin_data( __FILE__ );
		$output = $plugin_data;
		if( $param && is_array($plugin_data)  ) {
			foreach( $plugin_data as $key => $value ) {
				if( $param == $key ){
					$output = $value;
					break;
				}
			}
		}
		return $output;
	}

// abstracting l18n functions so I don't have to pass domain each time
	function __( $string ) {
		return __( $string,'jspullquotes' );
	}
	function _e( $string ) {
		_e( $string, 'jspullquotes' );
	}
 
	function set_defaults( $reset = false, $unset = false, $curr_ver = null ) {
// TODO: add "confirm" value for $reset and $unset.
		if ( $unset ) {
			delete_option( $this->option_name );
			return true;
		}

		$curr_ver = $curr_ver ? $curr_ver : $this->get_plugin_data('Version');
		$defaults = array(
			'last_used' => $curr_ver,
			'style_name' => 'Default.css', 
			'def_side' => 'right', 
			'alt_sides' => true, 
			'alt_text' => true, 
			'skip_links' => true, 
			'skip_internal_links' => true, 
			'q_container' => 'blockquote', 
			'omit_styles' => false,
			'quote_class' => 'pullquote', 
			'quote_class_alt' => 'pullquote pqRight' );


		if ( $reset ) {
			delete_option( $this->option_name );
			add_option( $this->option_name, $defaults );
		} else if ( $curr_options = get_option( $this->option_name ) ) {
		// Merge existing prefs with new or missing defaults
			$curr_options = array_merge( $defaults, $curr_options );
			$curr_options['last_used'] = $curr_ver; // always update
		// Update "true" (string) to true (boolean)
			foreach($curr_options as $key => $value ) {
				if ($value === 'true') 
					$curr_options[$key] = true;
			}
			update_option( $this->option_name, $curr_options );
		} else {
			add_option( $this->option_name, $defaults );
		}
	}

// Option key has changed.  Check DB for old name and update if needed
	function update_option_name() {
		if( $options = get_option( 'jspullquotes_options' ) ) {
			add_option( $this->option_name, $options );
			delete_option( 'jspullquotes_options' );
		} else {
			$options = get_option( $this->option_name );
		}
		return $options;
	}

// Check to see if new version.  If so, make necessary updates to settings
	function compare_versions() {
		$options = $this->update_option_name();
		$curr_ver = $this->get_plugin_data('Version');
		$last_ver = $options['last_used'];
		if( $last_ver ) $last_ver = '2.0';
		if ( version_compare( $curr_ver, $last_ver ) == 1 ) { // if new version
			$this->set_defaults( false, false, $curr_ver );
		}
	}

// ========================
// BLOG PAGE CODE
// ========================

// Fetch the stylesheet used for the active pullquote style
	function get_pqcss( $theStyle ) {
		If ( file_exists( TEMPLATEPATH . '/jspullquotes.css' ) ) {
		// A stylesheet embedded in a WordPress theme overrides Options Page selection
			$theStyle = get_bloginfo( 'stylesheet_directory' ) . '/jspullquotes.css';
		} else {
			$styleDir = $this->plugin_url . '/styles/';
			if ( !$theStyle == "" ) {
				if (file_exists($this->plugin_path . '/styles/' . $theStyle . '/pullquote.css')) {
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

// Add the links to the <head> of each blog page
	function wp_head() {
		$options = get_option( $this->option_name );
		$pluginpath = $this->plugin_url;
		$plugin_version = $this->get_plugin_data('Version');
		$optionsarray = '"' .
			$options['skip_links'] . '", "' . 
			$options['skip_internal_links'] . '", "' . 
			$options['def_side'] . '", "' . 
			$options['alt_sides'] . '", "' . 
			$options['alt_text'] . '", "' .
	//		$options['cap_first'] . '", "' .
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
// SETTINGS PAGE CODE
// ========================

// Add the configuration screen to the Design menu in Admin
	function add_settings_page() {
		if( current_user_can('switch_themes') ) {
			$page = add_theme_page( $this->__('Pull-Quote Settings'), $this->__('Pull-Quotes'), 'switch_themes', 'pull-quotes', array(&$this,'settings_page') );

			add_action( "admin_head-$page", array(&$this, 'admin_head') );
			add_filter( 'plugin_action_links', array(&$this, 'filter_plugin_actions'), 10, 2 );
		}
	}

// Add "preview" script to settings page head
	function admin_head() {
		$scriptpath = $this->plugin_url;
		echo <<<EOT
<!-- JavaScript Pull-Quotes plugin -->
	<script src="{$scriptpath}/resources/preview.js" type="text/javascript" language="JavaScript" charset="utf-8"></script>
<!-- end pull-quote additions -->\n
EOT;
	}

// Add homepage link to settings page footer
	function admin_footer() {
		$pluginfo = $this->get_plugin_data();
		printf('%1$s plugin | Version %2$s | by %3$s<br />', $pluginfo['Title'], $pluginfo['Version'], $pluginfo['Author']);
	}

// Add action link(s) to plugins page
	function filter_plugin_actions($links, $file){
		//Static so we don't call plugin_basename on every plugin row.
		static $this_plugin;
		if( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

		if( $file == $this_plugin ){
			$settings_link = '<a href="themes.php?page=pull-quotes">' . __('Settings') . '</a>';
			array_unshift( $links, $settings_link ); // before other links
		}
		return $links;
	}

// these three functions are used by the settings page to display set options in the form controls when the page is opened

// for checkboxes
	function checkflag( $options, $optname ) {
		return $options[$optname] ? ' checked="checked"' : '';
	}

// for text boxes or textarea
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
			$newoptions = $_POST[$this->option_name];
			$booloptions = array ( 'alt_sides', 'alt_text', 'skip_links', 'skip_internal_links', 'omit_styles' );
			foreach( $booloptions as $bool ) { 
				// explicitly set all checkboxes
				$newoptions[$bool] = $newoptions[$bool] ? true : false;
			}
			update_option( $this->option_name, $newoptions);
			echo '<div id="message" class="updated fade"><p><strong>' . __('Settings saved.') . '</strong></p></div>';
		} 
		
		// get options for use in formsetting functions
		$opts = get_option( $this->option_name );

		// Get array of CSS files for Style dropdown
		$arrStyles = array();
		if ( $handle = opendir($this->plugin_path . '/styles') ) {
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
						<select name="<?php echo $this->option_name ?>[style_name]" id="style_name"<?php echo( $arrStyles[1] ? '' : ' class="code disabled" disabled="disabled"' ) ?>>
<?php
			if( !$arrStyles[1] ) {
				// if only one style exists, update the settings
				$opts['style_name'] = $arrStyles[0];
				update_option( $this->option_name, $opts );
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

			$stylePath = $this->plugin_url;

?>						</select>
					</label> (<a href="#" onclick="pullquote_preview_pop('<?php echo($stylePath); ?>','resources/jspullquotes-core.css',document.getElementById('style_name').value); return false;" title="<?php $this->_e('show a preview of the selected style in a pop-up window'); ?>"><?php $this->_e('preview'); ?></a>)<?php
					
	} else {
			// if nothing in Styles directory....
		}
?><br />
					Note: a "jspullquotes.css" file in the active Theme directory will override this setting</td>
				</tr>
<?php
		$cmbpicksides = '<select name="<?php echo $this->option_name ?>[def_side]" id="def_side">
							<option value="left"' . $this->checkcombo($opts,'def_side','left',true) . '>' . $this->__('left') . '</option>
							<option value="right"' . $this->checkcombo($opts,'def_side','right') . '>' . $this->__('right') . '</option>
						</select>';
?>
				<tr valign="top">
					<th scope="row">Position</th>
					<td><label for="def_side">
						<?php echo(sprintf($this->__('Display quotes on %s side'), $cmbpicksides)); ?></label><br />
						<label for="alt_sides"><input type="checkbox" name="<?php echo $this->option_name ?>[alt_sides]" id="alt_sides" value="true"<?php echo($this->checkflag($opts,'alt_sides')); ?> /> <?php $this->_e('Successive quotes on one page alternate sides'); ?></label><br />
						<label for="alt_text"><input type="checkbox" name="<?php echo $this->option_name ?>[alt_text]" id="alt_text" value="true"<?php echo($this->checkflag($opts,'alt_text')); ?> /> <?php $this->_e('Use alternate text if available'); ?></label> (<a href="<?php echo($this->plugin_url); ?>/resources/help/alt-text-info.<?php $this->_e('en_US'); ?>.htm"><?php $this->_e('how?'); ?></a>)</td>
				</tr>
			</tbody>
		</table>

		<h3><?php $this->_e('Advanced Options'); ?></h3>
<?php
		$cmbq_container = '<select name="<?php echo $this->option_name ?>[q_container]" id="q_container">
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
					<td><label for="skip_links"><input type="checkbox" name="<?php echo $this->option_name ?>[skip_links]" id="skip_links" value="true"<?php echo($this->checkflag($opts,'skip_links')); ?> /> <?php $this->_e('Remove external links from pull-quotes'); ?></label> <strong><?php $this->_e('(recommended)'); ?></strong><br />
						<label for="skip_internal_links"><input type="checkbox" name="<?php echo $this->option_name ?>[skip_internal_links]" id="skip_internal_links" value="true"<?php echo($this->checkflag($opts,'skip_internal_links')); ?> /> <?php $this->_e('Remove internal links (href="#id") from pull-quotes'); ?></label></td>
				</tr>
				<tr valign="top">
					<th scope="row"><acronym title="Cascading Style Sheets">CSS</acronym></th>
					<td><label for="omit_styles"><input type="checkbox" name="<?php echo $this->option_name ?>[omit_styles]" id="omit_styles" value="true"<?php echo($this->checkflag($opts,'omit_styles')); ?> /> <?php $this->_e('Do not link CSS'); ?></label><br /><?php $this->_e('Check this if you prefer to manually put your pull-quote styles elsewhere'); ?><br />
						<br />
						<input type="text" name="<?php echo $this->option_name ?>[quote_class]" id="quote_class" value="<?php echo($this->checktext($opts,'quote_class','pullquote')); ?>" /><label for="quote_class"> <?php $this->_e('Class selector for default pull-quote'); ?></label><br />
						<input type="text" name="<?php echo $this->option_name ?>[quote_class_alt]" id="quote_class_alt" value="<?php echo($this->checktext($opts,'quote_class_alt','pullquote pqRight')); ?>"/><label for="quote_class_alt"> <?php $this->_e('Class selector for alt-side pull-quote'); ?></label>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="submit">
			<input type="submit" name="save_settings" value="<?php _e('Save Changes') ?>" /></div>
	</form>
</div><!-- wrap -->
	<?php

		// add attribution to page footer
		add_action( 'in_admin_footer', array(&$this, 'admin_footer') );

	}

} // end class

$jspullquotes = new jspullquotes;
add_action( 'init', array($jspullquotes, 'init') );
register_activation_hook( __FILE__, array($jspullquotes, 'set_defaults') );

?>