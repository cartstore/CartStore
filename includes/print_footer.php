<?php
/*
  $Id: print_footer.php,v 1.3 2003/02/10 22:30:54 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require(DIR_WS_INCLUDES . 'counter.php');
?>
<table border="0" width="100%" height="27" cellspacing="0" cellpadding="1">
  <tr class="footer">
    <td class="footer" align="center"><?php echo PRINT_ORDER_FOOTER_TEXT ?></td>
  </tr>
</table>
<br>
<?php
  if ($banner = tep_banner_exists('dynamic', '468x50')) {
?>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><?php echo tep_display_banner('static', $banner); ?></td>
  </tr>
</table>
<?php
  }
?>
