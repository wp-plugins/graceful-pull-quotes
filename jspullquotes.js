/* This is the actual javascript file that is loaded in the <head> of each page */

var pullquote = {
	init : function(arrOptions) {

	// Get options and set defaults as needed
		var $skiplinks = arrOptions[0];
		var $defside = arrOptions[1];
		var $altsides = arrOptions[2];
		var $alttext = arrOptions[3];
		var $capfirst = arrOptions[4];
		var $qcontainer = arrOptions[5];
		if ($qcontainer=='') {$qcontainer='blockquote';};
		var $quoteclass = arrOptions[6];
		if ($quoteclass=='') {$quoteclass='pullquote';};
		var $quoteclassAlt = arrOptions[7];
		if ($quoteclassAlt=='') {$quoteclassAlt='pullquote pqAlt';};

/*
Things to do:
	* Allow a “side override” where the user can specify a side for a particular pull-quote
	* Allow for quote to appear at top of Post instead of top of paragraph (requires user to specify post div class)
	* Selectively strip problematic link attributes:
    	o If quote has no href OR an href starting with '#', remove the whole link
    	o Else if quote has name, remove name
    * Allow user to select CSS file to use for pull-quotes
    * Avoid need for jspullquotes-core.css by writing those lines directly to <head> (??? is this a good idea ???)
    * Option for first letter to always be upper/lowercase
	* [B]racket-capitalize quotes starting with lowercase letter
	* Alternate sides within a post, instead of over a whole page (must allow user to specify div that signifies individual posts!)
	* Strip all tag information out of the quote, leaving just the text (or allow user to specify tags to be removed?)
	* In the WYSIWYG editor (any of these may require full rewrite of WSY code as buttonsnap appears limited and undocumented):
		o A visual indication that text has been selected as a pull-quote
		o A means to remove a pull-quote (other than editing HTML directly)
		o A pop-up options box that allows user to specify options such as side or alternate text (like when adding a link).
		o Fix open/close tag function if buttonclick with no text selected, ***OR*** make button non-functional unless text is selected (again like link button)
*/

	// Check that the browser supports the methods used
		if (!document.getElementById || !document.createElement || !document.appendChild) return false;
		var oElement, oClassName, oPullquote, oPullquoteP, oQuoteContent, i, j, k;

	// Find all span elements
		var arrElements = document.getElementsByTagName('span');
		var oRegExp = new RegExp("(^|\\s)pullquote(\\s|$)");

	// Setup for default side option
		if ($defside == 'right') {
			$quoteclass = $quoteclassAlt;
		}

	// loop through all span elements
		for (i=0; i<arrElements.length; i++) {
			oElement = arrElements[i];
	// Proceed if current element is pullquote
			oClassName = oElement.className
			if (oRegExp.test(oClassName)) {
		// re-init oAltQuote
				var oAltQuote = undefined;
		// Create the blockquote and p elements
				oPullquote = document.createElement($qcontainer);
				oPullquoteP = document.createElement('p');

		// If alternating sides, add "alt" class every second loop
				if ($altsides == "true") {
					if ($defside != '') {
						$defside = ''; // skip first quote
					} else if ($quoteclass == 'pullquote') {
						$quoteclass = 'pullquote pqAlt';
					} else {
						$quoteclass = 'pullquote';
					}
				}

		// If a side is user-specified, use that, otherwise follow the alternation
				var oSideRegExp = new RegExp("(^|\\s)(pqRight|pqLeft)(\\s|$)");
				var oSideFound = oSideRegExp.exec(oClassName);
				if (oSideFound && (oSideFound[2]=="pqLeft" || oSideFound[2]=="pqRight")) {
					oPullquote.className = 'pullquote ' + oSideFound[2];		
				} else {
					oPullquote.className = $quoteclass;
				}

		// If the first child of the span is a comment, its content is our quote...
		// Safari jumps straight to the text node and thus always fails this test
		// thx: https://www.engr.uga.edu/adminsite/modules/htmlarea/example-fully-loaded.html
				if ($alttext == "true" && oElement.firstChild && oElement.firstChild.nodeType == 8) { // 8 means comment
						oAltQuote = document.createTextNode(oElement.firstChild.data);
						oPullquoteP.appendChild(oAltQuote);
				} else { // otherwise get all content as normal
					for(j=0;j<oElement.childNodes.length;j++) { //loop through the children of <span>
						if (oElement.childNodes[j].nodeType == 8) {
							// if a comment, do nothing!
						}
						else if ($skiplinks == "true" && oElement.childNodes[j].nodeType == 1 && oElement.childNodes[j].tagName.toLowerCase() == "a") {
			// If current node is an <a> tag, apply the append loop to its decendants, but not the A tag itself  (assumes there is not another A tag within the A tag, as that would be illegal)
							var oCurrChild = oElement.childNodes[j];
							for(k=0;k<oCurrChild.childNodes.length;k++) {
//								oAltQuote = oCurrChild.childNodes[k].cloneNode(true);
								oAltQuote = pullquote.dupeNode(oCurrChild.childNodes[k],true);
								oPullquoteP.appendChild(oAltQuote);
							}
						} else {
			// Standard "copy everything and append to P node"
//							oAltQuote = oElement.childNodes[j].cloneNode(true);
							oAltQuote = pullquote.dupeNode(oElement.childNodes[j],true);
							oPullquoteP.appendChild(oAltQuote);
						}
					}
				}
		// only insert the pull-quote if it is not empty!
				if(oAltQuote != undefined && oAltQuote != '') {
			// append text to the paragraph node
					oPullquote.appendChild(oPullquoteP);
			// Insert the blockquote element before the span element's parent element
					oElement.parentNode.parentNode.insertBefore(oPullquote,oElement.parentNode);
				}
			}
		}
	},

/*
dupeNode function by Stephen Rider
http://www.striderweb.com/nerdaphernalia/features/javascript-dupenode-function/
*/
	dupeNode : function($the_node,$include_all) {
		var i;
		var $new_node = $the_node.cloneNode(false);
		// if there are children...
		if ($include_all == true && $the_node.hasChildNodes() == true) {
			for(i=0;i<$the_node.childNodes.length;i++) {
				// recursively pass the child back to THIS function
				$child_node = arguments.callee($the_node.childNodes[i],true);
				$new_node.appendChild($child_node);
			}
		}
		return $new_node;
	},
	
	// addEvent function from http://www.quirksmode.org/blog/archives/2005/10/_and_the_winner_1.html
	addEvent : function(obj, type, fn) {
		if (obj.addEventListener)
			obj.addEventListener( type, fn, false );
		else if (obj.attachEvent)
		{
			obj["e"+type+fn] = fn;
			obj[type+fn] = function() { obj["e"+type+fn]( window.event ); }
			obj.attachEvent( "on"+type, obj[type+fn] );
		}
	}
};

// This allows you to call the function with parameters from a page
 function pullquoteopts(arrOptions) {
	pullquote.addEvent(window, 'load', function(){pullquote.init(arrOptions);});
}
