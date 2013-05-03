<?php

/*
  $Id: sts.php,v 4.1 2006/03/06 22:30:54 Rigadin Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  GNU General Public License Compatible

STS PLUS v4.1 by Rigadin (rigadin@osc-help.net)
Based on: Simple Template System (STS) - Copyright (c) 2004 Brian Gallagher - brian@diamondsea.com

 */

// STRIP_UNWANTED_TAGS() - Remove leading and trailing <tr><td> from strings
function sts_strip_unwanted_tags($tmpstr, $commentlabel) {
  // Now lets remove the <tr><td> that the require puts in front of the tableBox
  $tablestart = strpos($tmpstr, '<table"');
  // If empty, return nothing
  if ($tablestart < 1) {
  	return  "\n<!-- start $commentlabel //-->\n$tmpstr\n<!-- end $commentlabel //-->\n";
  }

  $tmpstr = substr($tmpstr, $tablestart); // strip off stuff before <table>
  // Now lets remove the </td></tr> at the end of the tableBox output
  // strrpos only works for chars, not strings, so we'll cheat and reverse the string and then use strpos
  $tmpstr = strrev($tmpstr);

  $tableend = strpos($tmpstr, strrev("</table>"), 1);
  $tmpstr = substr($tmpstr, $tableend);  // strip off stuff after </table>

  // Now let's un-reverse it
  $tmpstr = strrev($tmpstr);
  // print "<hr>After cleaning tmpstr:" . strlen($tmpstr) . ": FULL=[".  htmlspecialchars($tmpstr) . "]<hr>\n";
  return  "\n<!-- start $commentlabel //-->\n$tmpstr\n<!-- end $commentlabel //-->\n";
}

// STRIP_CONTENT_TAGS() - Remove text before "body_text" and after "body_text_eof"
function sts_strip_content_tags($tmpstr, $commentlabel) {
  // Now lets remove the <tr><td> that the require puts in front of the tableBox
  $tablestart = strpos($tmpstr, '<table"');
  $formstart = strpos($tmpstr, "<form");
  $formfirst = false;

  // If there is a <form> tag before the <table> tag, keep it
  if ($formstart !== false and $formstart < $tablestart) {
     $tablestart = $formstart;
     $formfirst = true;
  }

  // If empty, return nothing
  if ($tablestart < 1) {
        return  "\n<!-- start $commentlabel //-->\n$tmpstr\n<!-- end $commentlabel //-->\n";
  }

  $tmpstr = substr($tmpstr, $tablestart); // strip off stuff before <table>

  // Now lets remove the </td></tr> at the end of the tableBox output
  // strrpos only works for chars, not strings, so we'll cheat and reverse the string and then use strpos
  $tmpstr = strrev($tmpstr);

  if ($formfirst == true) {
    $tableend = strpos($tmpstr, strrev("</form>"), 1);
  } else {
    $tableend = strpos($tmpstr, strrev("</table>"), 1);
  }

  $tmpstr = substr($tmpstr, $tableend);  // strip off stuff after <!-- body_text_eof //-->

  // Now let's un-reverse it
  $tmpstr = strrev($tmpstr);

  // print "<hr>After cleaning tmpstr:" . strlen($tmpstr) . ": FULL=[".  htmlspecialchars($tmpstr) . "]<hr>\n";
  return  "\n<!-- start $commentlabel //-->\n$tmpstr\n<!-- end $commentlabel //-->\n";
}

  function sts_read_template_file ($template_file){
  // Open Template file and read into a variable
    if (! file_exists($template_file)) {
      print 'Template file does not exist: ['.$template_file.']';
	  return '';
    }

	ob_start(); // Start capture to buffer
	require $template_file; // Includes the template, this way php code can be used in templates
	$template_html = ob_get_contents(); // Get content of buffer
	ob_end_clean(); // Clear out the capture buffer
	return $template_html;
  }

function get_javascript($tmpstr, $commentlabel) {
  // Now lets remove the <tr><td> that the require puts in front of the tableBox
  $tablestart = strpos($tmpstr, "<script");

  // If empty, return nothing
  if ($tablestart === false) {
  	return  "\n<!-- start $commentlabel //-->\n\n<!-- end $commentlabel //-->\n";
  }

  $tmpstr = substr($tmpstr, $tablestart); // strip off stuff before <table>

  // Now lets remove the </td></tr> at the end of the tableBox output
  // strrpos only works for chars, not strings, so we'll cheat and reverse the string and then use strpos
  $tmpstr = strrev($tmpstr);

  $tableend = strpos($tmpstr, strrev("</script>"), 1);
  $tmpstr = substr($tmpstr, $tableend);  // strip off stuff after </table>

  // Now let's un-reverse it
  $tmpstr = strrev($tmpstr);

  // print "<hr>After cleaning tmpstr:" . strlen($tmpstr) . ": FULL=[".  htmlspecialchars($tmpstr) . "]<hr>\n";
  return  "\n<!-- start $commentlabel //-->\n$tmpstr\n<!-- end $commentlabel //-->\n";
}

// Return the value between $startstr and $endstr in $tmpstr
function str_between($tmpstr, $startstr, $endstr) {
  $startpos = strpos($tmpstr, $startstr);

  // If empty, return nothing
  if ($startpos === false) {
        return  "";
  }

  $tmpstr = substr($tmpstr, $startpos + strlen($startstr)); // strip off stuff before $start

  // Now lets remove the </td></tr> at the end of the tableBox output
  // strrpos only works for chars, not strings, so we'll cheat and reverse the string and then use strpos
  $tmpstr = strrev($tmpstr);

  $endpos = strpos($tmpstr, strrev($endstr), 1);

  $tmpstr = substr($tmpstr, $endpos + strlen($endstr));  // strip off stuff after </table>

  // Now let's un-reverse it
  $tmpstr = strrev($tmpstr);

  return  $tmpstr;
}

  function sortbykeylength($a,$b) {
  $alen = strlen($a);
  $blen = strlen($b);
  if ($alen == $blen) $r = 0;
  if ($alen < $blen) $r = 1;
  if ($alen > $blen) $r = -1;
  return $r;
}

?>