<?php

/*

$Id: qbi_page_top.php,v 2.10 2005/05/08 al Exp $



Quickbooks Import QBI

contribution for CartStore

ver 2.10 May 8, 2005

(c) 2005 Adam Liberman

www.libermansound.com

info@libermansound.com

Please use the osC forum for support.

GNU General Public License Compatible



    This file is part of Quickbooks Import QBI.



    Quickbooks Import QBI is free software; you can redistribute it and/or modify

    it under the terms of the GNU General Public License as published by

    the Free Software Foundation; either version 2 of the License, or

    (at your option) any later version.



    Quickbooks Import QBI is distributed in the hope that it will be useful,

    but WITHOUT ANY WARRANTY; without even the implied warranty of

    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

    GNU General Public License for more details.



    You should have received a copy of the GNU General Public License

    along with Quickbooks Import QBI; if not, write to the Free Software

    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/



require_once(DIR_WS_FUNCTIONS . 'qbi_functions.php');

require_once(DIR_WS_CLASSES . 'qbi_classes.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

	

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo TITLE; ?></title>

<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />

   

	 	

</head>

<body>

<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->



<!-- body //-->

<table border="0" width="100%" cellspacing="2" cellpadding="2">

  <tr>

    <td width="<?php echo BOX_WIDTH; ?>" valign="top">

	<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">

<!-- left_navigation //-->

<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<!-- left_navigation_eof //-->

    </table></td>

<!-- body_text //-->

    <td width="100%" valign="top">

	<table border="0" width="100%" cellspacing="0" cellpadding="2">

      <tr>

        <td width="100%">

		<table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></td>

            <td class="pageHeading2" align="right"></td>

          </tr>

        </table></td>

      </tr>

	</table>

<?php

$pageurl=$PHP_SELF;

?>