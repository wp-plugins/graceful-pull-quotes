function previewpop(corePath,stylePath) {
	var openPars = "height=10,width=10,scrollbars=yes,toolbar=yes";
	var varWin = window.open("","Pic", openPars);
	if (!varWin) {
		alert("I can't show the preview for some reason.  If you have a pop-up blocker enabled, turning it off might help.");
		return true;
	}
	varWin.document.open();
	var varWinDoc = varWin.document;
	
	varWinDoc.writeln("<!DOCTYPE html PUBLIC \"-\/\/W3C\/\/DTD XHTML 1.0 Transitional\/\/EN\"");
	varWinDoc.writeln("        \"http:\/\/www.w3.org\/TR\/2000\/REC-xhtml1-20000126\/DTD\/xhtml1-transitional.dtd\">");
	varWinDoc.writeln("<html xmlns=\"http:\/\/www.w3.org\/1999\/xhtml\" xml:lang=\"en\" lang=\"en\">");
	varWinDoc.writeln("<head>");
	varWinDoc.writeln("<title>Style Preview<\/title>");


	varWinDoc.writeln('<link rel="stylesheet" href="' + corePath + '" type="text/css" />');
	varWinDoc.writeln('<link rel="stylesheet" href="' + stylePath + '" type="text/css" />');
	
	varWinDoc.writeln("<\/head>"); 
	varWinDoc.writeln("<body>");
	varWinDoc.writeln("<p>Dolor amet nulla, ullamcorper luptatum nulla in nulla duis, iriuredolor illum et dolor, odio exerci commodo, esse commodo.<\/p>");
	varWinDoc.writeln("<blockquote class=\"pullquote\"><p>This is a pull-quote<\/p><\/blockquote>");
	varWinDoc.writeln("<p>Augue exerci esse autem, ex aliquam crisare ad esse at, nostrud quis dolore qui iusto in, magna adipiscing. <span class=\"pullquote\">This is a pull-quote<\/span>. Hendrerit blandit te in et augue volutpat delenit consectetuer te delenit te ut iriuredolor ut eros accumsan facilisis nisl lorem. Molestie at feugait at exerci ea aliquip, euismod praesent, duis sed minim dolore! <\/p>"); 
	varWinDoc.writeln("<\/body>");
	varWinDoc.writeln("<\/html>");
	varWin.resizeTo(400, 350);
	varWinDoc.close();
	varWin.focus();
	return false;
}
