<?php
   
/*
  $Id: new_attributes.php 
  
   New Attribute Manager v4b, Author: Mike G.
  
  Updates for New Attribute Manager v.5.0 and multilanguage support by: Kiril Nedelchev - kikoleppard
  kikoleppard@hotmail.bg
  
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/
  
  $adminImages = "includes/languages/english/images/buttons/";
  $backLink = "<a href=\"javascript:history.back()\">";

  require('new_attributes_config.php');
  require('includes/application_top.php');
  
 
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEW_ATTRIBUTE_MANAGER);

 
 
  if ( $cPathID && $action == "change" )
  {
        require('new_attributes_change.php');

        tep_redirect( './' . FILENAME_CATEGORIES . '?cPath=' . $cPathID . '&pID=' . $current_product_id );

  }
  
?>


<?php require(DIR_WS_INCLUDES . 'header.php'); ?>




    
<?php
function findTitle( $current_product_id, $languageFilter )
{
  $query = "SELECT * FROM products_description where language_id = '$languageFilter' AND products_id = '$current_product_id'";

  $result = tep_db_query($query) or die(tep_db_error());

  $matches = tep_db_num_rows($result);

  if ($matches) {

  while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
                                                          	
        $productName = $line['products_name'];
        
  }
  
  return $productName;
  
  } else { return HEADING_ERROR; }
  
}

function attribRedirect( $cPath )
{

 return '<SCRIPT LANGUAGE="JavaScript"> window.location="./configure.php?cPath=' . $cPath . '"; </script>';
 
}

switch( $action )
{
  case 'select':
  $pageTitle = HEADING_TITLE_VAL_PRODUCT . findTitle( $current_product_id, $languageFilter );
  require('new_attributes_include.php');
  break;
  
  case 'change':
  $pageTitle = HEADING_UPDATE;
  require('new_attributes_change.php');
  require('new_attributes_select.php');
  break;

  default:
  $pageTitle = HEADING_TITLE_VAL;
  require('new_attributes_select.php');
  break;
  
}

?>


<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>


<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
