<?php
/*
  $Id: gv_admin.php,v 1.2.2.1 2003/04/18 21:13:51 wilt Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  Gift Voucher System v1.0
  Copyright (c) 2001,2002 Ian C Wilson
  http://www.phesis.org

  GNU General Public License Compatible
*/
?>
<!-- gv_admin //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_GV_ADMIN,
                     'link'  => tep_href_link(FILENAME_COUPON_ADMIN, 'selected_box=gv_admin'));

  if ($selected_box == 'gv_admin') {
    $contents[] = array('text'  => tep_admin_files_boxes(FILENAME_COUPON_ADMIN,BOX_COUPON_ADMIN ) .
                                   tep_admin_files_boxes(FILENAME_GV_QUEUE,BOX_GV_ADMIN_QUEUE) .
                                   tep_admin_files_boxes(FILENAME_GV_MAIL,BOX_GV_ADMIN_MAIL) . 
                                   tep_admin_files_boxes(FILENAME_GV_SENT,BOX_GV_ADMIN_SENT ));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- gv_admin_eof //-->