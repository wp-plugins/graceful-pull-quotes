<?php 
/*	This is the options panel code for the Wordpress wp-admin screen.  Option panel appears under "Presentation".	*/


function jspullquotes_subpanel() {

	if (isset($_POST['jspullquotes_options_submit'])) {
    	?>
    	<div class="updated"><p><?php 
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
	<h2>Pull-Quotes</h2>
	<div style="width: 10em; float: right; border: 1px black solid; margin: 0 0 1em 1em; padding: 0.75em; background-color: #FDFBDE; text-size: 1.2em;">
			<a href="templates.php?file=/wp-content/plugins/jspullquotes/jspullquotes.css">Click here to edit the StyleSheet that controls the appearance of the pullquotes.</a>
		</div>
	<form action="themes.php?page=jspullquotes.php" method="post">
		<fieldset name="Active">
			<p><input type="checkbox" name="jspullquotes_options[skip_links]" id="skip_links" value="true"<?php echo(jspquote::checkflag('skip_links')); ?> /><label for="skip_links">Remove link information from pullquotes (Highly recommended)</label></p>

			<p>Display quotes on <select name="jspullquotes_options[def_side]" id="def_side">
				<option value="left"<?php echo(jspquote::checkcombo('def_side','left','left')); ?>>left</option>
				<option value="right"<?php echo(jspquote::checkcombo('def_side','right','left')); ?>>right</option>
			</select> side</p>

			<p><input type="checkbox" name="jspullquotes_options[alt_sides]" id="alt_sides" value="true"<?php echo(jspquote::checkflag('alt_sides')); ?> /><label for="alt_sides">Successive quotes on one page alternate sides</label></p>

			<p><input type="checkbox" name="jspullquotes_options[alt_text]" id="alt_text" value="true"<?php echo(jspquote::checkflag('alt_text')); ?> /><label for="alt_text">Use alternate text if available</label></p>
			
		</fieldset>
		<div class="submit">
			<input type="submit" name="jspullquotes_options_submit" value="<?php _e('Update Options &raquo;', 'jspullquotes') ?>" /></div>
	</form>
	<p>Note: If you want the pullquote to differ from the actual text in the &lt;span&gt;, turn on the "Use alternate text if available" option above, then put the desired quotation in an HTML comment, like so:<br /><code>&lt;span class="pullquote"&gt;&lt;!-- This is a pullquote --&gt;This, with some extra text we don't want, is a pullquote&lt;/span&gt;</code>.</p>
	<div style="border: 1px solid black; margin: 0px; padding: 0 1em;">
		<p><strong>CAUTION:</strong> Due to a bug in Safari (Mac OS X browser), alternate pull-quote text does not display.  You have two choices:</p>
		<ol>
			<li>Do the alternate text as described.  Safari will show the *actual* text in the span as though the alternate were not there.</li>
			<li>Put the comment <em>alone</em> in a pullquote span, just before the sentence you're (sort of) quoting.  Safari will not show any pullquote, but other browsers will work normally. Example:  <code>&lt;span class="pullquote"&gt;&lt;!-- Darn that Safari! -->&lt;/span&gt;Darn that Javascript-mangling Safari!</code></li>
		</ol>
		<p>Please note that in either case, things should "Upgrade Gracefully", meaning that when Apple fixes the bug, we shouldn't have to change anything -- the alternate quotes will suddenly just work.</p>
	</div>
</div><!-- end of wrap -->
<?php
}

?>