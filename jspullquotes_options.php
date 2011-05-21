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
		<fieldset name="basic">
		<h3>Basic Options</h3>
			<p><input type="checkbox" name="jspullquotes_options[skip_links]" id="skip_links" value="true"<?php echo(jspquote::checkflag('skip_links')); ?> /><label for="skip_links">Remove link information from pullquotes (Highly recommended)</label></p>

			<p><label for="def_side">Display quotes on <select name="jspullquotes_options[def_side]" id="def_side">
				<option value="left"<?php echo(jspquote::checkcombo('def_side','left','left')); ?>>left</option>
				<option value="right"<?php echo(jspquote::checkcombo('def_side','right','left')); ?>>right</option>
			</select> side</label></p>

			<p><input type="checkbox" name="jspullquotes_options[alt_sides]" id="alt_sides" value="true"<?php echo(jspquote::checkflag('alt_sides')); ?> /><label for="alt_sides">Successive quotes on one page alternate sides</label></p>

			<p><input type="checkbox" name="jspullquotes_options[alt_text]" id="alt_text" value="true"<?php echo(jspquote::checkflag('alt_text')); ?> /><label for="alt_text">Use alternate text if available</label></p>
			
		</fieldset>

		<fieldset name="advanced">
		<h3>Advanced Options</h3>
			<p><input type="checkbox" name="jspullquotes_options[use_beta]" id="use_beta" value="true"<?php echo(jspquote::checkflag('use_beta')); ?> /><label for="use_beta">(BETA) Use pull-quote button in <acronym title="What You See Is What You Get">WYSIWYG</acronym> post editor</label> (If you <strong>don't</strong> use the WYSIWYG editor, turn this off for now.  Out of Beta it hopefully won't matter)</p>
			
		<p>Note: changing any of the following will require you to update both the <a href="templates.php?file=/wp-content/plugins/jspullquotes/jspullquotes.css">jspullquotes.css</a> and <a href="templates.php?file=/wp-content/plugins/jspullquotes/jspullquotes-core.css">jspullquotes-core.css</a> files.
			<p><label for="q_container">Contain pull-quote in an HTML </label><select name="jspullquotes_options[q_container]" id="q_container">
				<option value="blockquote"<?php echo(jspquote::checkcombo('q_container','blockquote','blockquote')); ?>>&lt;blockquote&gt;</option>
				<option value="div"<?php echo(jspquote::checkcombo('q_container','div','blockquote')); ?>>&lt;div&gt;</option>
				<option value="p"<?php echo(jspquote::checkcombo('q_container','p','blockquote')); ?>>&lt;p&gt;</option>
			</select></p>
			<p><label for="quote_class">CSS class selector for default pull-quote: </label><input type="text" name="jspullquotes_options[quote_class]" id="quote_class" value="<?php echo(jspquote::checktext('quote_class','pullquote')); ?>" /></p>
			<p><label for="quote_class_alt">CSS class selector for alt-side pull-quote: </label><input type="text" name="jspullquotes_options[quote_class_alt]" id="quote_class_alt" value="<?php echo(jspquote::checktext('quote_class_alt','pullquote pqAlt')); ?>"/></p>
	
			
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