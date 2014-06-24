<?php
/*
  $Id: print_header.php,v 1.3 2003/06/10 18:20:38 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/
?>

<h1><?php echo nl2br(STORE_NAME_ADDRESS); ?></h1>
<table border="0" width="100%" height="27"cellspacing="0" cellpadding="0">
  <tr class="headerNavigation">
    <td class="headerNavigation" align="center"><a href="#" onclick="window.print();return false">
<?php echo PRINT_ORDER_HEADER_TEXT ?></td>
  </tr>
</table>
<?php
  if (isset($_GET['error_message']) && tep_not_null($_GET['error_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr class="headerError">
    <td class="headerError"><?php echo htmlspecialchars(urldecode($_GET['error_message'])); ?></td>
  </tr>
</table>
<?php
  }

  if (isset($_GET['info_message']) && tep_not_null($_GET['info_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr class="headerInfo">
    <td class="headerInfo"><?php echo htmlspecialchars($_GET['info_message']); ?></td>
  </tr>
</table>
<?php
  }
?>
