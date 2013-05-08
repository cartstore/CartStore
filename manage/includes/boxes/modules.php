<?php
/*
  $Id: modules.php,v 1.16 2003/07/09 01:18:53 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/
?>
<!-- modules //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_MODULES,
                     'link'  => tep_href_link(FILENAME_MODULES, 'set=payment&selected_box=modules'));

  if ($selected_box == 'modules') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_MODULES, 'set=payment', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_MODULES_PAYMENT . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_MODULES, 'set=shipping', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_MODULES_SHIPPING . '</a><br>' .
								   // START STS 4.1
								   '<a href="' . tep_href_link(FILENAME_MODULES, 'set=sts', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_MODULES_STS . '</a><br>' .
								   // END STS 4.1
								   '<a href = "' . tep_href_link(FILENAME_MODULES, 'set=sociallogin', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_MODULES_SOCIAL_LOGIN . '<br>' .
                                   '<a href="' . tep_href_link(FILENAME_MODULES, 'set=ordertotal', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_MODULES_ORDER_TOTAL . '</a>');
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- modules_eof //-->
