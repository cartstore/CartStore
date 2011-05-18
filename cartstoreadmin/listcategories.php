<?php
/*
  $Id: validcategories.php,v 0.01 2002/08/17 15:38:34 Richard Fielder

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  

  Copyright (c) 2002 Richard Fielder

  GNU General Public License Compatible
*/

require('includes/application_top.php');


?>
<html>
<head>
<title>Valid Categories/Products List</title>
<style type="text/css">
<!--
h4 {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: x-small; text-align: center}
p {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: xx-small}
th {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: xx-small}
td {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: xx-small}
-->
</style>
<head>
<body>
<table width="550" border="1" cellspacing="1" bordercolor="gray">
<tr>
<td colspan="4">
<h4>Valid Categories List</h4>
</td>
</tr>
<?
   $coupon_get=tep_db_query("select restrict_to_categories from " . TABLE_COUPONS . " where coupon_id='".$_GET['cid']."'");
   $get_result=tep_db_fetch_array($coupon_get);
   echo "<tr><th>Category ID</th><th>Category Name</th></tr><tr>";
   $cat_ids = split("[,]", $get_result['restrict_to_categories']);
   for ($i = 0; $i < count($cat_ids); $i++) {
     $result = mysql_query("SELECT * FROM categories, categories_description WHERE categories.categories_id = categories_description.categories_id and categories_description.language_id = '" . $languages_id . "' and categories.categories_id='" . $cat_ids[$i] . "'");
     if ($row = mysql_fetch_array($result)) {
       echo "<td>".$row["categories_id"]."</td>\n";
       echo "<td>".$row["categories_name"]."</td>\n";
       echo "</tr>\n";
     } 
   }
    echo "</table>\n";
?>
<br>
<table width="550" border="0" cellspacing="1">
<tr>
<td align=middle><input type="button" value="Close Window" onClick="window.close()"></td>
</tr></table>
</body>
</html>
