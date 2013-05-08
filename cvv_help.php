<?php
// this file was created by Clifton Murphy <blue_glowstick@yahoo.com>
// using code supplied in a previous release by Thomas Nordstrom <t_nordstrom@yahoo.com>
// I take no credit for any of this work. I simply fixed this file and
// recompiled the distribution zip file with the fixes to prevent parse errors.
/*
  $Id: cvs_help.php,v 1.3 2004/02/4 07:28:00 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/


  require('includes/application_top.php');

  $navigation->remove_current_page();

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_POPUP_CVV_HELP);
?>

