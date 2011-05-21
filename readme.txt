=== JAVASCRIPT PULLQUOTES ===

By Stephen Rider -- http://www.striderweb.com/

building on the work of:
Roger Johansson -- http://www.456bereastreet.com/
Viper007Bond -- http://www.viper007bond.com/

== DESCRIPTION ==

This plugin allows you to make pull-quotes ( http://en.wikipedia.org/wiki/Pull-quote ) in your posts
without duplicating any content (i.e. making them manually). This is done via Javascript and it
it makes it easy to disable them later on if you wish and makes it so that duplicate content
doesn't show up in your feeds.

== HISTORY ==

This plugin started out as a "no options" javascript by Roger Johansson:
http://www.456bereastreet.com/archive/200609/automatic_pullquotes_with_javascript_and_css/

Then Viper007Bond added a basic "wrapper" to make it a Wordpress plugin:
http://www.viper007bond.com/wordpress-plugins/javascript-pullquotes/

Finally, Stephen Rider came along and added the options panel and all user changeable options, as well as the ability to specify alternate quote text.
http://www.striderweb.com/nerdaphernalia

== INSTALLATION ==

Upload the ENTIRE folder to your plugins folder (`/wp-content/plugins/`) while keeping the file
structure intact. You've done it right if you end up with a folder called "jspullquotes" and a
bunch of files inside it.

Then activate it on your plugin management page.

Finally, go to the options panel, and on the Presentation/ Pull-Quotes screen, select your options.  In a future version I (Stephen) hope to add a function that will set up recommended defaults automatically; but until that time you'll have to set them yourself!

== USAGE ==

1) Just wrap the text you want to put in a pullquote like this:

	To turn a piece of text into a pullquote, all I need to do is <span class="pullquote">wrap
	the text that will become a pullquote in a span element and give it the class name
	"pullquote"</span>.

The <span> won't affect how it shows up, but it tells the Javascript to turn it into a <blockquote>
and display it.

2) If you want a pull-quote that has text *different* than the text in the <span>, put it inside <!-- an HTML comment --> like so:

	<span class="pullquote"><!-- This sentence should be a pull-quote -->This sentence, without this middle clause, should be a pull-quote</span>.

The content inside the HTML comment will *only* show up as the pull-quote -- it will not appear at all in the main flow of text.

3) If you _ARE NOT_ using TinyMCE (the rich text / WYSIWYG editor), then a new button will appear on
your write page. Just click it to put in the start, type some text, and then click it again. You
can also highlight some text and then click the button.

TinyMCE users: sorry, but TinyMCE is too much of a pain in the ass to code for. You'll just have to
resort to making the pullquotes manually (see above example). I (Viper) may possibly add support for
TinyMCE in the future, but don't count on it.