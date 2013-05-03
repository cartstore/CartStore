<?php

/*

  $Id: info_shopping_cart.php,v 1.19 2003/02/13 03:01:48 hpdl Exp $



  CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA



  GNU General Public License Compatible

*/



  require("includes/application_top.php");



  $navigation->remove_current_page();



  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_INFO_SHOPPING_CART);

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">

<style type="text/css">

<!--

body {

	font-family: Arial, Helvetica, sans-serif;

	font-size: 11px;

	color: #000;

}

-->

</style>

</head>

<body bgcolor="white"><table width="100%" border="0" cellpadding="15" cellspacing="0" bgcolor="#FFFFFF" class="create_account_box">

  <tr>

    <td><p class="pageHeading"><b><?php echo HEADING_TITLE; ?></b><br><?php echo tep_draw_separator(); ?></p>

<p class="main"><b><?php echo SUB_HEADING_TITLE_1; ?></b><br><?php echo SUB_HEADING_TEXT_1; ?></p>

<p class="main"><b><?php echo SUB_HEADING_TITLE_2; ?></b><br><?php echo SUB_HEADING_TEXT_2; ?></p>

<p class="main"><b><?php echo SUB_HEADING_TITLE_3; ?></b><br><?php echo SUB_HEADING_TEXT_3; ?></p>

 
  </tr>

</table>





</body>

</html>

<?php

  require("includes/counter.php");

  require(DIR_WS_INCLUDES . 'application_bottom.php');

?>

