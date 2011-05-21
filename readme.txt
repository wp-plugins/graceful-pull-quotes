=== JAVASCRIPT PULLQUOTES ===
v 1.6.5
31 August 2007

By Stephen Rider -- http://striderweb.com/
Plugin Home Page -- http://striderweb.com/nerdaphernalia/features/wp-javascript-pull-quotes/

building on the work of:
	Roger Johansson -- http://www.456bereastreet.com/
with some help from:
	Viper007Bond -- http://www.viper007bond.com/
See the full History below.

== DESCRIPTION ==

This plugin allows you to make pull-quotes ( http://en.wikipedia.org/wiki/Pull-quote ) in your posts without duplicating any content. Text you select will be duplicated as a pull-quote.  This is done entirely via Javascript and is designed to seamlessly vanish if the plugin is disabled later.  Using JavaScript instead of PHP also ensures that duplicate content doesn't show up in your feeds.

== INSTALLATION ==

Upload the ENTIRE folder to your plugins folder (`/wp-content/plugins/`) while keeping the file structure intact. You've done it right if you end up with a folder called "jspullquotes" inside your "plugins" folder.

Then activate it on your plugin management page.

Finally, go to the Pull-Quotes options panel, (under the "Presentation" tab), select your options.  This step is optional, as the program works pretty well right out of the box, but I recommend checking out the different quote styles at least.

== USAGE ==

NOTE: If you would like to have a "Pull-quotes" button in the post editor, please read How_to_add_quicktags.htm , which you can find in this plugin's "Extras" folder. 

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

	A little known "gotcha" with HTML quotes is that technically speaking you are not allowed to put a double-dash ("--") inside a comment, except as part of the beginning and end markers.  In some browsers I have seen this expose comment data as page text.  (This is general HTML and not specific to this plugin.)

BUG: WordPress does weird things if you try to put tags inside a comment.

BUG: Accented characters in alternate comments get messed up.

WORKAROUND REGARDING SAFARI BROWSER:

	There is a bug in the Javascript rendering of Safari, which causes it to misfire on the alternate text. (Note: this appears to be fixed in version 3.)  Assuming that some of your users probably do use this browser, you have two options:

	A) Do the alternate text as described.  Safari will show the *actual* text in the span as though the alternate were not there.

	B) Put the comment alone in a pullquote span, just _before_ the sentence you're (sort of) quoting.  Safari will not show any pullquote, but other browsers will work normally.
	
	Example:  <span class="pullquote"><!-- Darn that Safari! --></span>Darn that Javascript-mangling Safari!

== HISTORY ==

I've been using WordPress for years now.  In late 2006 I was reading through the excellent book "DOM Scripting", by Jeremy Keith, which teaches how to use JavaScript to manupulate the structure of (X)HTML documents.  As I read through, learning how to move, duplicate, and change parts of a web page with scripts, I came up with the concept that it could be used to make pull-quotes programmatically.  The CSS required to do pull-quotes is easy, but I didn't like the idea of having text duplicated in the HTML for what is ultimately a purely visual effect, so the idea of doing it with Javascript appealed to me -- it created the entire pull-quote from whole cloth and disappeared entirely if the JavaScript didn't run for some reason.

Entirely by coincidence, blogger Jeff Harrell, right about that time, pointed me to a script written by Roger Johanssen <http://www.456bereastreet.com/archive/200609/automatic_pullquotes_with_javascript_and_css/>.  We weren't even discussing my idea at the time -- it was simply a "hey, this is neat" post on Jeff's blog.  So I took a look.

As it turns out, Roger basically had created _exactly_ the script I had had in mind -- right down to a limitation mine would have had because I (and he) didn't know how to code around it.  Before I ever saw his script, a commenter on his blog had fixed that problem, and a second commenter ("Viper007Bond") had gone and tucked it into a "wrapper" that made it into a WordPress plugin.  No frills, no fuss -- it simply applied the script to WordPress pages.  (You can find it here: <http://www.viper007bond.com/wordpress-plugins/javascript-pullquotes/>).

Finally, I (Stephen Rider) picked it up and started adding features, including the options panel and all user changeable options, alternate text quotes, styles, localization compatibility, and pretty much everything beyond the simple script link.  I've put a lot of hours into this plugin, and have learned a lot about JavaScript, PHP, and WordPress along the way.

Enjoy!
