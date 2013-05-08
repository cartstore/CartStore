<?php

/*
  $Id: new_attributes_select.php

   New Attribute Manager v4b, Author: Mike G.

  Updates for New Attribute Manager v.5.0 and multilanguage support by: Kiril Nedelchev - kikoleppard
  kikoleppard@hotmail.bg

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

?>

<TR>
<TD class="pageHeading" colspan="3"><?php echo $pageTitle; ?></TD>
</TR>
<FORM ACTION="<?php echo $PHP_SELF; ?>" NAME="SELECT_PRODUCT" METHOD="POST">
<INPUT TYPE="HIDDEN" NAME="action" VALUE="select">
<?php
echo "<TR>";
echo "<TD class=\"main\"><BR><B>".HEADING_SELECT."<BR></TD>";
echo "</TR>";
echo "<TR>";
echo "<TD class=\"main\"><SELECT NAME=\"current_product_id\">";

$query = "SELECT * FROM products_description where products_id LIKE '%' AND language_id = '$languageFilter' ORDER BY products_name ASC";

$result = mysql_query($query) or die(mysql_error());

$matches = mysql_num_rows($result);

if ($matches) {

   while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {

        $title = $line['products_name'];
        $current_product_id = $line['products_id'];

        echo "<OPTION VALUE=\"" . $current_product_id . "\">" . $title;

   }
} else { echo HEADING_NO_PRODUCTS; }

echo "</SELECT>";
echo "</TD></TR>";

echo "<TR>";
echo "<TD class=\"main\"><input type=\"image\" src=\"" . $adminImages . "button_edit.png\"></TD>";
echo "</TR>";

?>
</FORM>

