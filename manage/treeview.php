<?php
/*
  $Id: treeview.php,v 1.0 10/08/2005 Beer Monster Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>Catalog Tree</title>

	<link rel="StyleSheet" href="dtree.css" type="text/css" />
	<script type="text/javascript" src="dtree.js"></script>

</head>

<body>
<div class="dtree"><form>
<p><a href="javascript: d.openAll();">open all</a> | <a href="javascript: d.closeAll();">close all</a></p>
<?php
    $defaultlanguage_query_raw ="SELECT l.languages_id FROM " . TABLE_LANGUAGES . " as l WHERE l.code ='" . DEFAULT_LANGUAGE . "'";
    $defaultlanguage_query = tep_db_query($defaultlanguage_query_raw);
    $defaultlanguage= tep_db_fetch_array($defaultlanguage_query);
echo "<script type='text/javascript'>
		<!--

		d = new dTree('d'); \n
      d.add(0,-1,'Catalog','','');\n";



    $categories_query_raw = "SELECT c.categories_id, cd.categories_name, c.parent_id FROM " . TABLE_CATEGORIES_DESCRIPTION . " AS cd INNER JOIN categories as c ON cd.categories_id = c.categories_id WHERE cd.language_id =" . $defaultlanguage[languages_id][0] . " ORDER BY c.sort_order";
    $categories_query = tep_db_query($categories_query_raw);
    while ($categories = tep_db_fetch_array($categories_query)) {
      echo "d.add(" . $categories['categories_id'] . "," . $categories['parent_id'] . ",'" . addslashes($categories['categories_name']) . "','', '<input type=checkbox name=categories value=" . $categories['categories_id'] . ">');\n"; //,," . $categories['categories_id'] . ",,,); \n";

    } //end while

    $products_query_raw = "SELECT distinct pc.categories_id, pd.products_id, pd.products_name FROM " .  TABLE_PRODUCTS_TO_CATEGORIES . " as pc INNER JOIN " . TABLE_PRODUCTS_DESCRIPTION . " as pd ON pc.products_id = pd.products_id";
    $products_query = tep_db_query($products_query_raw);

    while ($products = tep_db_fetch_array($products_query)) {
      echo "d.add(" . $products['products_id'] . "0000," . $products['categories_id'] .",'" . addslashes($products['products_name']) . "','', '<input type=checkbox name=products value=" . $products['products_id'] . ">');\n"; //,," . $products['products_id'] . ",,,); \n";

    }//end while

?>
document.write(d);

		//-->
	</script>
<INPUT TYPE="BUTTON" onClick="cycleCheckboxes(this.form)" VALUE="OK"></form>
<script type='text/javascript'>
		<!--
function cycleCheckboxes(what) {
window.opener.document.coupon.coupon_products.value="";
window.opener.document.coupon.coupon_categories.value ="";
    for (var i = 0; i<what.elements.length; i++) {
        if ((what.elements[i].name.indexOf('products') > -1)) {
            if (what.elements[i].checked) {
                window.opener.document.coupon.coupon_products.value += what.elements[i].value + ',';
            }
        }
    }

    for (var i = 0; i<what.elements.length; i++) {
        if ((what.elements[i].name.indexOf('categories') > -1)) {
            if (what.elements[i].checked) {
                window.opener.document.coupon.coupon_categories.value += what.elements[i].value + ',';
            }
        }
    }
window.close();
}
		//-->
</script>
</div>
</body>

</html>
