<?php
/*
  $Id: header_tags_popup_help.php,v 1.0 2005/09/22 
   
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  
  GNU General Public License Compatible
*/
?>
<style type="text/css">
.popupText {color: #000; font-size: 12px; } 
</style>
<table border="0" cellpadding="0" cellspacing="0" class="popupText">
  <tr><td><hr class="solid"></td></tr>
  <tr>
   <td class="popupText"><p><b>What are HTTA, HTDA, HTKA and HTCA used for?</b><br><br>
    Header Tags comes with a default set of tags. You can create your own
    set of tags for each page (it comes with some set up, like for index
    and product pages).

<pre>
HT = Header Tags  
T  = Title 
A  = All 
D  = Description
K  = Keywords
C  = Categories *
</pre>  
<b>* Note:</b> The HTCA option only works for the index and product_info pages. 
For the index page, it causes the category name to be displayed in the title. For 
the product_info page, if it is checked, the text in the boxes in Text Control will
be appended to the title, description and keywords, respectively..<br><br>

If HTTA is set on (checked), then it says display the Header Tags Title All 
(default title plus the one you set up).<br><br>

So if you have the option checked, both titles will be displayed.
Let's say your title is Mysite and the default title is CartStore.<br>
<pre>
With HTTA on, the title is
 Mysite CartStore
With HTTA off, the title is
 Mysite
</pre>
</p>
<p>If the name of the section is in <font color="red">red</font>, it means that that file does not have
the required Header Tags code installed in it. See the Install_Catalog.txt file
for instructions on how to do this.</p>
  </td>
 </tr> 
</table>
