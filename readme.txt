=== JAVASCRIPT PULLQUOTES ===
v 1.7

By Stephen Rider -- http://striderweb.com/
Plugin Home Page -- http://striderweb.com/nerdaphernalia/features/wp-javascript-pull-quotes/

building on the work of:
	Roger Johansson -- http://www.456bereastreet.com/
See the full History below.

    This plugin is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

== DESCRIPTION ==

This plugin allows you to make pull-quotes ( http://en.wikipedia.org/wiki/Pull-quote ) in your posts without duplicating any content. Text you select will be duplicated as a pull-quote.  This is done entirely via JavaScript and is designed to seamlessly vanish if the plugin is disabled later.  Using JavaScript instead of PHP also ensures that duplicate content doesn't show up in your feeds.

== INSTALLATION ==

Upload the ENTIRE folder to your plugins folder (`/wp-content/plugins/`) while keeping the file structure intact. You've done it right if you end up with a folder called "jspullquotes" inside your "plugins" folder.

Then activate it on your plugin management page.

Finally, go to the Pull-Quotes options panel, (under the "Design" tab), select your options.  This step is optional, as the program works pretty well right out of the box, but I recommend previewing the different quote styles at least.

== USAGE ==

NOTE: If you would like to have a "Pull-quotes" button in the post editor, please read How_to_add_quicktags.htm , which you can find in this plugin's "extras" folder. 

1)	Just wrap the text you want to put in a pullquote like this:

	All you need to do is <span class="pullquote">wrap the quotable text in a span element and give it the class name "pullquote"</span>.

	The <span> won't affect how that text shows up, but it tells the Javascript to _duplicate_ it as a pull-quote.

2)	If you want a pull-quote that has text *different* than the text in the <span>, put it inside <!-- an HTML comment --> like so:

	<span class="pullquote"><!-- This sentence should be a pull-quote -->This sentence, without this middle clause, should be a pull-quote</span>.

	The content inside the HTML comment will *only* show up as the pull-quote -- it will not appear at all in the main flow of text.

3)	To specify a side for a particular pull-quote, give the <span> a secondary class of either pqLeft or pqRight, like so:

	<span class="pullquote pqRight">This will appear on the right</span> no matter what the Options screen says.


BUGS AND WORKAROUNDS:

NOTE REGARDING HTML COMMENTS:

	A little known "gotcha" with HTML quotes is that technically speaking you are not allowed to put a double-dash ("--") inside a comment, except as part of the beginning and end markers.  (A double-dash technically ends an HTML comment.)  In some browsers I have seen this expose comment data as page text.  This is part of the official HTML spec and not a "bug" per se this plugin.

BUG: WordPress does weird things if you try to put tags inside a comment.

BUG: Accented characters in alternate comments get messed up.

WORKAROUND REGARDING BUGGY BROWSERS:

	There is a bug in the Javascript rendering of certain less common browsers (including older versions of Safari) which causes it to misfire on the alternate text.  Assuming that some of your readers may use these browsers, you have two options:

	A) Do the alternate text as described.  Affected browsers will show the *actual* text in the span as though the alternate were not there.

	B) Put the comment alone in a pullquote span, just _before_ the sentence you're (sort of) quoting.  Affected browsers will not show any pull-quote, but other browsers will work normally.
	
	Example:  <span class="pullquote"><!-- Darn that browser! --></span>Darn that Javascript-mangling browser!

== HISTORY ==

I've been using WordPress for years now.  In late 2006 I was reading through the excellent book "DOM Scripting", by Jeremy Keith, which teaches how to use JavaScript to manipulate the structure of (X)HTML documents.  As I read through, learning how to move, duplicate, and change parts of a web page with scripts, I came up with the concept that it could be used to make pull-quotes programmatically.  The CSS required to do pull-quotes is easy, but I didn't like the idea of having text duplicated in the HTML for what is ultimately a purely visual effect, so the idea of doing it with JavaScript appealed to me -- it created the entire pull-quote from whole cloth and disappeared entirely if the JavaScript didn't run for some reason.

Entirely by coincidence, blogger Jeff Harrell, right about that time, pointed me to a script written by Roger Johanssen <http://www.456bereastreet.com/archive/200609/automatic_pullquotes_with_javascript_and_css/>.  We weren't even discussing my idea at the time -- it was simply a "hey, this is neat" post on Jeff's blog.  So I took a look.

As it turns out, Roger basically had created _exactly_ the script I had had in mind -- right down to a limitation mine would have had because I (and he) didn't know how to code around it.  Before I ever saw his script, a commenter on his blog had fixed that problem, and a second commenter had gone and tucked it into a "wrapper" that made it into a WordPress plugin.  No frills, no fuss -- it simply applied the script to WordPress pages.  (You can find that version here: http://www.viper007bond.com/wordpress-plugins/javascript-pullquotes/ ).

Finally, I (Stephen Rider) picked it up and started adding features, including the options panel and all user changeable options, alternate text quotes, styles, localization compatibility, and pretty much everything beyond the simple script link.  I've put a lot of hours into this plugin, and have learned a lot about JavaScript, PHP, and WordPress along the way.

== VERSION HISTORY ==

v 2.0 - 8 June 2008 - 
	* Rewrote Settings page to WP 2.5 standard
	* New Setting: strip internal links "href='#id'" from pull-quote
	* Added "Settings" link to Actions on Plugins screen
	* Strips "a" tags that lack "href" attribute from pull-quote
	* Strips "id" and "name" attributes from tags in pull-quote
	* Moved home page link on Settings page to page footer
	* Improved error correction if Style files go missing (not finished though)
	* BUGFIX: Files starting with "." should not appear in styles list in Admin
	* Misc. code clean & polish

v 1.7.0 - 6 June 2008 - 
	* BUGFIX: "preview" script is now only added to Pull-Quotes page -- used to add to ever admin page (was causing problems when editing posts)
	* Improved security via nonces.  See: http://markjaquith.wordpress.com/2006/06/02/wordpress-203-nonces/
	* Changed name of preview script in Admin to avoid potential conflicts
	* Removed "edit CSS" link from Admin.  Doesn't work in WP 2.5
	* Simplified HTML comments on page headers.  Blog pages no longer have plugin URL and Admin page no longer has version.
	* Extensive code cleanup and abstraction -- e.g. function calls now use "$this->" when possible.  Also, new class functions:
		- __()
		- _e()
		- get_plugin_data()
	* Eliminated separate "class" file
	* Consolidated set/reset_defaults() functions
	* Some documentation cleanup

v 1.6.7 — 15 January 2008 –
	* NEW French localization — Merci Ben!
	* BUGFIX - version display in page header was broken
	* Small fix to options page localization
	* minor code changes

v 1.6.6 — 26 November 2007 –
	* Added User Option to _not_ link CSS — this way admin can put CSS in site’s main CSS file and save some HTTP requests
	* Plugin path now uses plugin_basename() (stability improvement)
	* BUGFIX - version display on Options screen was broken

v 1.6.5 — 31 August 2007 –
	* added function to set defaults if no options are set
	* reorganized files in jspullquotes directory
	* added JSPQPATH constant to make paths less brittle
	* changed User Capability requirement to "switch_themes"
	* test for both "switch_themes" and "edit_themes" capabilites before writing "edit stylesheet" box to admin screen
	* changed name of class, for filename consistency

v 1.6 — 25 July 2007 –
	* Italian localization added
	* Changed user requirement for Options Screen. Was Access Level “7″, now requires Capability “edit_themes”
	* Minor experimental change that may or may not kick in in a future update of WordPress (yes, I like keeping you in suspense! :) )  [Update: Won't happen. It was the "settings" link in the header, which I thought I could get implemented in WP.  Removed in plugin v1.7]

v 1.5.4 — 16 June 2007 –
	* The preparations for localizations are complete. A POT file is now included with the download. Please send me translations and I will include them with the download! :)
	* Cleaned up the identification comments included in the page additions
	* Some code cleanup and rearrangement. The main PHP file is now much simplified, with the main code class in a separate file.

v 1.5.3 — 10 May 2007 –
	* Styles can now be in folders, which allows for organizing styles that include graphic elements or other additional files
	* Bug fix: There was a minor error with the side alternation if the first pull-quote had a side specified
	* More under-the-hood code fixes and optimizations.

v 1.5.2 — 6 April 2007 –
	* Added “Style Preview” button in options panel

v 1.5.1 — 29 March 2007 –
	* Added ability to embed pull-quote design into a WordPress theme
	* Now uses _e() function on Options screen to allow for translations
	* Minor cosmetic changes to Options screen

v 1.5 — 19 March 2007 –
	* Added drop down menu so user can select a style for the pull-quotes
	* New “Modern” style
	* Fixed “pull-quote” button in HTML post editor (not sure when this broke!)
	* Bug fixes and code improvements

v 1.4 — 20 February 2007 –
	* Added user-specified sides for particular pull-quotes.
	* Laid groundwork for planned text-modification options
	* Removed <p> option for containing object — conflicted with formatting
	* Disabled button in rich-text post editor, as it is broken in WP 2.1. Hopefully fixed with changes scheduled for WP 2.2
	* A large number of under-the-hood improvements and optimizations

v 1.3 — 20 November 2006 –
	* Added “Advanced” options that allow user to specify HTML and CSS for pull-quotes
	* Rolled Beta WYSIWYG button into main plugin as an option
	* Fixed bug in “skip empty pull-quotes” code
	* Many under-the-hood code changes, including stability improvements and code reorganization that will make further updates easier

v 1.3b1 — BETA VERSION — 11 November 2006 –
	* Added button to rich-text edit page.
	* Reduced risk of function name conflicts with other plugins.

v 1.2.2 — 1 hour later –
	* Added a link to edit the CSS file that controls the appearance of the pull-quotes
	* Cleaned up the Options panel a bit.

v 1.2.1 — November 3, 2006 –
	* Bug Fix: Switched to a CSS selector that is far less likely to be already in use by a user’s stylesheet.
	* Bug Fix: Empty spans do nothing rather than creating an empty pull-quote. This allows for Safari bug work-around (and when Apple fixes the bug, no changes should be needed for Safari to work fully).

v 1.2 — October 26, 2006 –
	* Added option to choose default side on which to display pull-quote

v 1.1 — October 20, 2006 –
	* Initial public release


Enjoy!

	--Stephen Rider
