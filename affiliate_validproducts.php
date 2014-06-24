<?php
/*
  $Id: affiliate_validproducts.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

 // require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_BANNERS_BUILD);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD));
?>

    
<table class="table">

<?
    echo "<tr><td><b>". TEXT_VALID_PRODUCTS_ID . "</b></td><td><b>" . TEXT_VALID_PRODUCTS_NAME . "</b></td></tr><tr>";
    $result = tep_db_query("SELECT * FROM products, products_description WHERE products.products_id = products_description.products_id and products_description.language_id = '" . $languages_id . "' ORDER BY products_description.products_name");
    if ($row = tep_db_fetch_array($result)) {
        do {
            echo "<td class='infoBoxContents'>&nbsp;".$row["products_id"]."</td>\n";
            echo "<td class='infoBoxContents'>".$row["products_name"]."</td>\n";
            echo "</tr>\n";
        }
        while($row = tep_db_fetch_array($result));
    }
    echo "</table>\n";
?>


<?php // require('includes/application_bottom.php'); ?>