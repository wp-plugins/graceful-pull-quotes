=== JAVASCRIPT PULLQUOTES ===
v 1.5.2
5 April 2007

By Stephen Rider -- http://striderweb.com/
Plugin Home Page -- http://striderweb.com/nerdaphernalia/features/wp-javascript-pull-quotes/

building on the work of:
	Roger Johansson -- http://www.456bereastreet.com/
with some help from:
	Viper007Bond -- http://www.viper007bond.com/

== DESCRIPTION ==

This plugin allows you to make pull-quotes ( http://en.wikipedia.org/wiki/Pull-quote ) in your posts without duplicating any content (i.e. making them manually). This is done entirely via Javascript and is designed to seamlessly vanish if the plugin is disabled later.  Using JavaScript instead of PHP also ensures that duplicate content doesn't show up in your feeds.

== INSTALLATION ==

Upload the ENTIRE folder to your plugins folder (`/wp-content/plugins/`) while keeping the file structure intact. You've done it right if you end up with a folder called "jspullquotes" and a bunch of files inside it.

Then activate it on your plugin management page.

Finally, go to the options panel, and on the Presentation/ Pull-Quotes screen, select your options.  In a future version I hope to add a function that will set up recommended defaults automatically; but until that time you'll have to set them yourself!  The program works pretty well right out of the box.

== USAGE ==

1)	Just wrap the text you want to put in a pullquote like this:

	To turn a piece of text into a pullquote, all I need to do is <span class="pullquote">wrap the text that will become a pullquote in a span element and give it the class name "pullquote"</span>.

	The <span> won't affect how it shows up, but it tells the Javascript to turn it into a <blockquote> and display it.

2)	If you want a pull-quote that has text *different* than the text in the <span>, put it inside <!-- an HTML comment --> like so:

	<span class="pullquote"><!-- This sentence should be a pull-quote -->This sentence, without this middle clause, should be a pull-quote</span>.

	The content inside the HTML comment will *only* show up as the pull-quote -- it will not appear at all in the main flow of text.

3)	To specify a side for a particular pull-quote, give the <span> a secondary class of either pqLeft or pqRight, like so:

	<span class="pullquote pqRight">This will appear on the right</span> no matter what the Options screen says.


BUGS AND WORKAROUNDS:

NOTE REGARDING HTML COMMENTS:

	A little known "gotcha" with HTML quotes is that technically speaking you are not allowed to put a double-dash ("--") inside a comment, except as part of the beginning and end markers.  In some browsers I have seen this expose comment data as page text.  (This is general HTML and not specific to this plugin.)

BUG: WordPress does weird things if you try to put tags inside an alternate comment.

BUG: Accented characters in alternate comments get messed up.

BUG AND WORKAROUND REGARDING SAFARI BROWSER:

	There is a bug in the Javascript rendering of Safari, which causes it to misfire on the alternate text. Assuming that some of your users probably do use this browser, you have two options:

	A) Do the alternate text as described.  Safari will show the *actual* text in the span as though the alternate were not there.

	B) Put the comment alone in a pullquote span, just _before_ the sentence you're (sort of) quoting.  Safari will not show any pullquote, but other browsers will work normally.
	
	Example:  <span class="pullquote"><!-- Darn that Safari! --></span>Darn that Javascript-mangling Safari!

== HISTORY ==

This plugin started out as a "no options" javascript by Roger Johansson:
http://www.456bereastreet.com/archive/200609/automatic_pullquotes_with_javascript_and_css/

Then Viper007Bond added a basic "wrapper" to make it a Wordpress plugin:
http://www.viper007bond.com/wordpress-plugins/javascript-pullquotes/

Finally, Stephen Rider (that's me!) came along and added the options panel and all user changeable options, as well as the ability to specify alternate quote text.
