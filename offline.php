<?php
/*
  $Id: offline.php

  Store Offline Modification for osCommerce v3.04
  http://www.oscommerce.com

  For Support and more mods for this release of osCommerce
  Please visit

  http://www.box25.net

  Copyright (c) 2008 Box 25

  This program is released under the osCommerce License; you can redistribute it
  and/or modify  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
  require('includes/application_top.php');
  if ( TAKE_STORE_OFFLINE == 'False' ) {
    tep_redirect(HTTP_SERVER);
  }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<style type="text/css">

#page1 p {
    font-family: Verdana;
    font-size: small;
    color: #808080;
    text-align: center;
}

#page1 {
    padding-top: 10%;

}
</style>

<title><?php echo STORE_NAME; ?></title>

<base href="<?php echo HTTP_SERVER; ?>" />


<meta name="Generator" content="osCommerce" />

</head>

<body>
<div id="page1">
<?php
  // display the store name and logo
  echo '<p>' . tep_image(DIR_WS_IMAGES . 'logo.jpg', STORE_NAME) . '</p>';
  echo '<p><br /><br />Sorry ' . STORE_NAME . ' is currently down for maintenance';
  echo '<br />Please try again later<br /></p>';

?>
</div>
</body>

</html>
<?php

  require('includes/application_bottom.php');

?>