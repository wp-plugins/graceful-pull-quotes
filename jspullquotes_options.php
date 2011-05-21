<?php 
/*	This is the options panel code for the Wordpress wp-admin screen.  Option panel appears under "Presentation".	*/


function jspullquotes_subpanel() {

	if (isset($_POST['jspullquotes_options_submit'])) {
    	?><div class="updated"><p><?php 
		_e('Options Saved','jspullquotes'); ?></p></div><?php
		update_option('jspullquotes_options', $_POST['jspullquotes_options']);
	} 
	/*
	update_option($option_name, $newvalue);
	get_option($option);
	add_option($name, $value, $description, $autoload);
	*/
	?>
<div class="wrap">
	<form action="themes.php?page=jspullquotes.php" method="post">
		<h2>Pull-Quotes</h2>
		<fieldset name="Active">
			<h3><?php _e('Active Options', 'jspullquotes') ?></h3>
			<p><input type="checkbox" name="jspullquotes_options[skip_links]" id="skip_links" value="true"<?php echo(checkflag('skip_links')); ?> /><label for="skip_links">Remove link information from pullquotes (Highly recommended)</label></p>

			<p>Display quotes on <select name="jspullquotes_options[def_side]" id="def_side">
				<option value="left"<?php echo(checkcombo('def_side','left','left')); ?>>left</option>
				<option value="right"<?php echo(checkcombo('def_side','right','left')); ?>>right</option>
			</select> side</p>

			<p><input type="checkbox" name="jspullquotes_options[alt_sides]" id="alt_sides" value="true"<?php echo(checkflag('alt_sides')); ?> /><label for="alt_sides">Successive quotes on one page alternate sides</label></p>

			<p><input type="checkbox" name="jspullquotes_options[alt_text]" id="alt_text" value="true"<?php echo(checkflag('alt_text')); ?> /><label for="alt_text">Use alternate text if available</label></p>
			<p>Note: If you want the pullquote to differ from the actual text in the &lt;span&gt;, put the desired quotation in an HTML comment, like so:<br />
				<code>&lt;span class="pullquote"&gt;&lt;!-- This is a pullquote --&gt;This, with some extra text we don't want, is a pullquote&lt;/span&gt;</code>.</p>
			<p><strong>CAUTION:</strong> The alternate text-in-a-comment option does not currently work in Safari (Mac OS X browser) due to a bug in that browser's Javascript engine.  Safari will always show the actual text in the span.</p>
		</fieldset>
		<fieldset name="inactive">

			<h3><?php _e('Inactive Options', 'Localization name') ?></h3>
			<p>These options are not yet implemented.</p>

			<p><input type="checkbox" name="jspullquotes_options[caps]" id="caps" value="caps"<?php echo(checkflag('caps')); ?> disabled="disabled" /><span style="color: grey;">[C]apitalize quote if it starts with lowercase letter</span></p>

			<p><input type="checkbox" name="jspullquotes_options[yeahright]" id="in" value="your"<?php echo(checkflag('dreams')); ?> disabled="disabled" /><span style="color: grey;">Make my bed and fix breakfast</span></p>

		</fieldset>
		<div class="submit">
			<input type="submit" name="jspullquotes_options_submit" value="<?php _e('Update Options &raquo;', 'jspullquotes') ?>" /></div>
	</form>

</div><!-- end of wrap -->
<?php
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

?>