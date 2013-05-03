<?php
/*
  $Id: validproducts.php,v 0.01 2002/08/17 15:38:34 Richard Fielder

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
<script language="JavaScript">
	function ClickEvent(products_id)
	{
		oForms=window.opener.document.forms;
		for(i=0;i<oForms.length;i++)
		{	if(oForms[i]["coupon_products"])
			{ tValue=oForms[i]["coupon_products"].value;
			  if(tValue!="")
			    tValue+=","+products_id;
			  else
			    tValue=products_id;
			  oForms[i]["coupon_products"].value=tValue;
			}
		}
	}
</script>
<head>
<body>
<table width="550" border="1" cellspacing="1" bordercolor="gray">
	<tr>
		<td colspan="3" align="center">
			<h4>Products:</h4>
			<form action="" method="post" enctype="multipart/form-data">
				Search: <input type="text" name="search"> <input type="submit" name="submit">
			</form>
		</td>
	</tr>
    <tr>
		<th>Products ID</th>
		<th>Products Name</th>
		<th>Products Model</th>
	</tr>
<?php
if(!empty($search))
{   $result = mysql_query("SELECT * FROM products p, products_description pd WHERE pd.products_name like '%" . tep_db_input($search) . "%' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' ORDER BY pd.products_name");
    if ($row = mysql_fetch_array($result)) {
        do {
?>
	<tr>
		<td><a href="#" onClick="javascript:ClickEvent(<?php echo($row["products_id"]); ?>);"><?php echo($row["products_id"]); ?></a></td>
		<td><a href="#" onClick="javascript:ClickEvent(<?php echo($row["products_id"]); ?>);"><?php echo($row["products_name"]); ?></a></td>
		<td><a href="#" onClick="javascript:ClickEvent(<?php echo($row["products_id"]); ?>);"><?php echo($row["products_model"]); ?></a></td>
	</tr>
<?php
        }
        while($row = mysql_fetch_array($result));
    }
}
?>
</table>
<br>
</body>

</html>