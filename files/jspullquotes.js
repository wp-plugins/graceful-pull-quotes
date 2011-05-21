/* This is loaded in the <head> of each page */

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
		var $defquoteclass = arrOptions[6];
		if ($defquoteclass=='') {$defquoteclass='pullquote';};
		var $defquoteclassAlt = arrOptions[7];
		if ($defquoteclassAlt=='') {$defquoteclass='pullquote pqRight';};

		if ($defside == 'right') {
			var $quoteclass = $defquoteclassAlt;
		} else {
			var $quoteclass = $defquoteclass;
		}

/*
Things to do:
	* Fix encoding bug for extended ASCII text in alt-text comments
	* Allow for quote to appear at top of Post instead of top of paragraph (requires user to specify post div class)
	* Selectively strip problematic link attributes:
    	o If link has no href OR an href starting with '#', remove the whole link
    	o Else if quote has name, remove name
    * Option for first letter to always be upper/lowercase
	* [B]racket-capitalize quotes starting with lowercase letter
	* Alternate sides within a post, instead of over a whole page (must allow user to specify div that signifies individual posts!)
	* Strip all tag information out of the quote, leaving just the text (or allow user to specify tags to be removed?)
*/

	// Check that the browser supports the methods used
		if (!document.getElementById || !document.createElement || !document.appendChild) return false;
		
		var oElement, oClassName, oPullquote, oPullquoteP, oQuoteContent, i, j, k;
	// Find all span elements
		var arrElements = document.getElementsByTagName('span');
		var oRegExp = new RegExp("(^|\\s)pullquote(\\s|$)");

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


		// If a side is user-specified, use that, otherwise follow the alternation
				var oSideRegExp = new RegExp("(^|\\s)(pqRight|pqLeft)(\\s|$)");
				var oSideFound = oSideRegExp.exec(oClassName);
				if (oSideFound && (oSideFound[2]=="pqLeft" || oSideFound[2]=="pqRight")) {
					if (oSideFound[2]=="pqRight") {
						$quoteclass = $defquoteclassAlt;
						$defside = ''; // doesn't matter if first quote or not....
					} else {
						$quoteclass = $defquoteclass;
						$defside = '';
					}
				} else if ($altsides == "true") {
			// If alternating sides, add "pqRight" class every second loop
					if ($defside != '') {
						$defside = ''; // skip first quote
					} else if ($quoteclass == $defquoteclass) {
						$quoteclass = $defquoteclassAlt;
					} else {
						$quoteclass = $defquoteclass;
					}
				} else {
					if ($defside == 'right') {
						var $quoteclass = $defquoteclassAlt;
					} else {
						var $quoteclass = $defquoteclass;
					}
				}
				oPullquote.className = $quoteclass;

// If the first child of the span is a comment, its content is our quote...
// thx: https://www.engr.uga.edu/adminsite/modules/htmlarea/example-fully-loaded.html
				if ($alttext == "true" && oElement.firstChild && oElement.firstChild.nodeType == 8) { // 8 == comment
						oAltQuote = document.createTextNode(oElement.firstChild.data);
						oPullquoteP.appendChild(oAltQuote);
				} else { // otherwise get all content as normal
					for(j=0;j<oElement.childNodes.length;j++) { //loop through the children of <span>
						var oCurrChild = oElement.childNodes[j];
						if (oCurrChild.nodeType == 8) {
							// if a comment, do nothing!
						}

						else if ($skiplinks == "true" && oCurrChild.nodeType == 1 && oCurrChild.tagName.toLowerCase() == "a") {
			// If current node is an <a> tag, apply the append loop to its decendants, but not the A tag itself  (assumes there is not another A tag within the A tag, as that would be illegal)
							for(k=0;k<oCurrChild.childNodes.length;k++) {
//								oAltQuote = oCurrChild.childNodes[k].cloneNode(true);
								oAltQuote = pullquote.dupeNode(oCurrChild.childNodes[k],true);
								oPullquoteP.appendChild(oAltQuote);
							}

						} else {
			// Standard "copy everything and append to P node"
//							oAltQuote = oElement.childNodes[j].cloneNode(true);
							if (oCurrChild.nodeType == 1 && oCurrChild.tagName.toLowerCase() == "a") {
								//strip out "name" attributes
							}
							oAltQuote = pullquote.dupeNode(oCurrChild,true);
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
http://striderweb.com/nerdaphernalia/features/javascript-dupenode-function/
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
function pullQuoteOpts(arrOptions) {
	pullquote.addEvent(window, 'load', function(){pullquote.init(arrOptions);});
}
