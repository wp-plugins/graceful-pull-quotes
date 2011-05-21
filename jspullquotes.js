/* This is the actual javascript file that is loaded in the <head> of each page */

/*	original pullquote function by Roger Johansson, http://www.456bereastreet.com/
	this version by Stephen Rider, http://www.striderweb.com/ */

var pullquote = {
	init : function($skiplinks, $altsides, $alttext) {
		
	// Check that the browser supports the methods used
		if (!document.getElementById || !document.createElement || !document.appendChild) return false;
		var oElement, oPullquote, oPullquoteP, oQuoteContent, i, j;
		var iAltx = 1

	// Find all span elements with a class name of pullquote
		var arrElements = document.getElementsByTagName('span');
		var oRegExp = new RegExp("(^|\\s)pullquote(\\s|$)");

	// loop through all span elements
		for (i=0; i<arrElements.length; i++) {
	// Save the current element
			oElement = arrElements[i];
	// Proceed if current element is pullquote
			if (oRegExp.test(oElement.className)) {
	// Create the blockquote and p elements
				oPullquote = document.createElement('blockquote');
				oPullquoteP = document.createElement('p');
				oPullquote.className = oElement.className;
				if ($altsides == "true") {
					if (iAltx == 1){
						iAltx = 2;
					} else {
						oPullquote.className += " alt";
						iAltx = 1;
					}
				}
/* thx: https://www.engr.uga.edu/adminsite/modules/htmlarea/example-fully-loaded.html */
		// If there is a comment just inside the span, its content is our quote...
				if ($alttext == "true" && oElement.firstChild && oElement.firstChild.nodeType == 8) {
						var $altQuote = document.createTextNode(oElement.firstChild.data);
						oPullquoteP.appendChild($altQuote);
				} else {
					for(j=0;j<oElement.childNodes.length;j++) {
			// Check if current node is an A tag
						if ($skiplinks == "true" && oElement.childNodes[j].nodeType == 1 && oElement.childNodes[j].tagName.toLowerCase() == "a") {
			// If it is, apply the append loop to its decendants, but not the A tag itself  (assumes there is not another A tag within the A tag, as that would be illegal)
							var oCurrChild = oElement.childNodes[j]
							for(k=0;k<oCurrChild.childNodes.length;k++) {
								oPullquoteP.appendChild(oCurrChild.childNodes[k].cloneNode(true));
							}
						} else {
							oPullquoteP.appendChild(oElement.childNodes[j].cloneNode(true));
						}
					}
				}
		// append text to the paragraph node
				oPullquote.appendChild(oPullquoteP);
		// Insert the blockquote element before the span element's parent element
				oElement.parentNode.parentNode.insertBefore(oPullquote,oElement.parentNode);
			}
		}
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
 function pullquoteopts($skiplinks, $altsides, $alttext) {
	pullquote.addEvent(window, 'load', function(){pullquote.init($skiplinks, $altsides, $alttext);});
}
