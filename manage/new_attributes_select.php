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
<div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a>
 <?php echo $pageTitle; ?> </h1></div>
              <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body"><i class="fa fa-pencil-square-o fa-5x pull-left"></i>
This is a quick method to edit existing options value prices in cartstore. A value of 1 with a price prefix of + will increase the cost of that product by 1 when selected by a customer on the front end.                         </div>
                      </div>
                  </div>   
              </div>    

<FORM ACTION="<?php echo $PHP_SELF; ?>" NAME="SELECT_PRODUCT" METHOD="POST">
<INPUT TYPE="HIDDEN" NAME="action" VALUE="select">
<?php
echo "";
echo "<div class=\"form-group\"><h3>".HEADING_SELECT."</h3>";
echo "";
echo "";
echo "<SELECT class=\"form-control\" NAME=\"current_product_id\">";

$query = "SELECT * FROM products_description where products_id LIKE '%' AND language_id = '$languageFilter' ORDER BY products_name ASC";

$result = tep_db_query($query) or die(tep_db_error());

$matches = tep_db_num_rows($result);

if ($matches) {

   while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {

        $title = $line['products_name'];
        $current_product_id = $line['products_id'];

        echo "<OPTION VALUE=\"" . $current_product_id . "\">" . $title;

   }
} else { echo HEADING_NO_PRODUCTS; }

echo "</SELECT></div>";
echo " ";

echo " ";
echo "<p> <input class=\"btn btn-default\" type=\"submit\" value=\"Edit\"></p>";
echo " ";

?>
</FORM>

