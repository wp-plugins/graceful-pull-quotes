/*	This adds the pullquote button to the "raw HTML" Write Post screen */

/*	This is just a modification of the stuff in WordPress' quicktags.js	*/

var pullquoteOpen = false;
var pullquoteTagStart = '<span class="pullquote">';
var pullquoteTagEnd = '</span>';

function pullquotesToggler() {
	if (pullquoteOpen == true) {
		pullquoteOpen = false;
		document.getElementById('ed_Pullquote').value = document.getElementById('ed_Pullquote').value.replace('/', '');
	} else {
		pullquoteOpen = true;
		document.getElementById('ed_Pullquote').value = '/' + document.getElementById('ed_Pullquote').value;
	}
}

function pullquotesButton() {
	//IE support
	if (document.selection) {
		edCanvas.focus();
	    sel = document.selection.createRange();
		if (sel.text.length > 0) {
			sel.text = pullquoteTagStart + sel.text + pullquoteTagEnd;
		}
		else {
			if (pullquoteOpen != true) {
				sel.text = pullquoteTagStart;
				pullquotesToggler();
			}
			else {
				sel.text = pullquoteTagEnd;
				pullquotesToggler();
			}
		}
		edCanvas.focus();
	}
	//MOZILLA/NETSCAPE support
	else if (edCanvas.selectionStart || edCanvas.selectionStart == '0') {
		var startPos = edCanvas.selectionStart;
		var endPos = edCanvas.selectionEnd;
		var cursorPos = endPos;
		var scrollTop = edCanvas.scrollTop;

		if (startPos != endPos) {
			edCanvas.value = edCanvas.value.substring(0, startPos)
			              + pullquoteTagStart
			              + edCanvas.value.substring(startPos, endPos) 
			              + pullquoteTagEnd
			              + edCanvas.value.substring(endPos, edCanvas.value.length);
			cursorPos += pullquoteTagStart.length + pullquoteTagEnd.length;
		}
		else {
			if (pullquoteOpen != true) {
				edCanvas.value = edCanvas.value.substring(0, startPos) 
				              + pullquoteTagStart
				              + edCanvas.value.substring(endPos, edCanvas.value.length);
				pullquotesToggler();
				cursorPos = startPos + pullquoteTagStart.length;
			}
			else {
				edCanvas.value = edCanvas.value.substring(0, startPos) 
				              + pullquoteTagEnd
				              + edCanvas.value.substring(endPos, edCanvas.value.length);
				pullquotesToggler();
				cursorPos = startPos + pullquoteTagEnd.length;
			}
		}
		edCanvas.focus();
		edCanvas.selectionStart = cursorPos;
		edCanvas.selectionEnd = cursorPos;
		edCanvas.scrollTop = scrollTop;
	}
	else {
		if (pullquoteOpen != true) {
			edCanvas.value += pullquoteTagStart;
			pullquotesToggler();
		}
		else {
			edCanvas.value += pullquoteTagEnd;
			pullquotesToggler();
		}
		edCanvas.focus();
	}
}
