
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>NMEA MTK checksum calculator</title>
<link rev=made href="mailto:wiml@hhhh.org">
<meta name=keywords content="NMEA, checksum, xor, javascript, GPS, utility">
<style type="text/css"><!--

p.warn {
    border-style: solid;
    border-width: thin;
    border-color: red;
    padding:      0.5em;
}

--></style>
<script><!--

// Compute the MTK checksum and display it
function updateChecksum(cmd)
{
  // Compute the checksum by XORing all the character values in the string.
  var checksum = 0;
  for(var i = 0; i < cmd.length; i++) {
    checksum = checksum ^ cmd.charCodeAt(i);
  }

  // Convert it to hexadecimal (base-16, upper case, most significant nybble first).
  var hexsum = Number(checksum).toString(16).toUpperCase();
  if (hexsum.length < 2) {
    hexsum = ("00" + hexsum).slice(-2);
  }
  
  // Display the result
  settext(document.getElementById("output"),checksum +"  " +hexsum);
}

// Helper function to set the contents of the SPAN to some text
function settext(span, text)
{
  if (!span.hasChildNodes()) {
    span.appendChild(span.ownerDocument.createTextNode(text));
    return;
  } else {
    span.firstChild.nodeValue = text;
  }
}

--></script>
</head>
<body>

<h1>MTK NMEA checksum calculator</h1>

<p>This is a simple calculator to compute the checksum field for the
MediaTek / ETEK chipset's command extensions to the NMEA protocol.
The checksum is simple, just an XOR of all the bytes between the
<tt>$</tt> and the <tt>*</tt> (not including the delimiters
themselves), and written in hexadecimal. More information on the commands
can be found in the
<a href="http://www.google.com/search?q=MTK+NMEA+Packet+User+Manual">MTK NMEA Packet User Manual</a>.
</p>

<p>For this to work you'll need to be using a browser that supports JavaScript and DHTML (most modern browsers do).</p>

<p>This page and the JavaScript it contains is in the public domain. Feel free to reuse it for any purpose.</p>

<div style="margin:1em; padding: 2em; background: #ddddff;">
<form onsubmit="document.getElementById('commandfld').select(); return false;">
<table>
<tr><th align=right>Command:</th><td><tt>$<input id="commandfld" type="text" onchange="updateChecksum(this.value);" value="PMTK000">*</tt></td></tr>
<tr><th align=right>With checksum:</th><td><span id="output" style="font-family: monospace;"></span></td></tr>
</table>
</form>
</div>

<script>updateChecksum(document.getElementById("commandfld").value);</script>

<hr>
<small>
<i>$Header: /home/wiml/www-cvs/wiml/proj/nmeaxor.html,v 1.1 2007/10/14 21:33:43 wiml Exp $</i> &mdash; <a href="mailto:wiml@hhhh.org">mail me</a>
</small>
</body>
</html>
