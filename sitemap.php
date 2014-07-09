<?php
/*
  $Id: sitemap.php,v1.0 2004/05/25 devosc Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SITEMAP);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SITEMAP));

require(DIR_WS_INCLUDES . 'header.php'); 
 require(DIR_WS_INCLUDES . 'column_left.php'); ?>


<!-- body_text //-->
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td>
            	
            	
            	
            <div class="page-header"><h1><i class="fa fa-sitemap"></i> <?php echo HEADING_TITLE; ?></h1></div>	
 
            	            	<h3>Quick Links</h3>

            	 <ul class="list-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <li class="list-group-item"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '"><i class="fa fa-user"></i> ' . PAGE_ACCOUNT . '</a>'; ?></li>
             
                  <li class="list-group-item"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '"><i class="fa fa-cogs"></i> ' . PAGE_ACCOUNT_EDIT . '</a>'; ?></li>
                  <li class="list-group-item"><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '"><i class="fa fa-envelope-o"></i> ' . PAGE_ADDRESS_BOOK . '</a>'; ?></li>
                  <li class="list-group-item"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '"><i class="fa fa-calendar"></i> ' . PAGE_ACCOUNT_HISTORY . '</a>'; ?></li>
                  <li class="list-group-item"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL') . '"><i class="fa fa-trophy"></i> ' . PAGE_ACCOUNT_NOTIFICATIONS . '</a>'; ?></li>

                  <li class="list-group-item"><?php echo '<a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><i class="fa fa-shopping-cart"></i> ' . PAGE_SHOPPING_CART . '</a>'; ?></li>
                  <li class="list-group-item"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '"><i class="fa fa-bolt"></i> ' . PAGE_CHECKOUT_SHIPPING . '</a>'; ?></li>
                  <li class="list-group-item"><?php echo '<a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH) . '"><i class="fa fa-search"></i> ' . PAGE_ADVANCED_SEARCH . '</a>'; ?></li>
                  <li class="list-group-item"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_NEW) . '"><i class="fa fa-magic"></i> ' . PAGE_PRODUCTS_NEW . '</a>'; ?></li>
                  <li class="list-group-item"><?php echo '<a href="' . tep_href_link(FILENAME_SPECIALS) . '"><i class="fa fa-thumbs-o-up"></i> ' . PAGE_SPECIALS . '</a>'; ?></li>
                  <li class="list-group-item"><?php echo '<a href="' . tep_href_link(FILENAME_REVIEWS) . '"><i class="fa fa-star"></i> ' . PAGE_REVIEWS . '</a>'; ?></li>
                     <li class="list-group-item"><?php echo '<a href="' . tep_href_link(FILENAME_CONTACT_US) . '"><i class="fa fa-life-ring"></i> ' . BOX_INFORMATION_CONTACT . '</a>'; ?></li>
              
              </ul>
<div class="clear"> </div>            	

				
				<h3 clas="col-lg-12 col-md-12 col-sm-12 col-xs-12"><i class="fa fa-folder-o"></i> Categories</h3>
            	<?php require DIR_WS_CLASSES . 'category_tree.php'; $osC_CategoryTree = new osC_CategoryTree; echo $osC_CategoryTree->buildTree(); ?></td>
          
             
           </td>
      </tr>
    </table> 
<!-- body_text_eof //-->


<?php require(DIR_WS_INCLUDES . 'column_right.php');
 require(DIR_WS_INCLUDES . 'footer.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
