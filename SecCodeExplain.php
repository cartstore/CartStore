<?php
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . 'SecCodeExplain.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
  <head>
    <title><?php echo PAGE_TITLE; ?></title>
    <meta http-equiv=Content-Type content="text/html; charset=ISO-8859-1">
    <style>
td {
font-family: Verdana, Arial, Helvetica, sans-serif; 
font-size: 10px;
color: #333333;
} 

TD.schead {
background-color: #CDDDEF;
font-family: verdana, arial, helvetica, sans-serif; 
font-size: 12px; 
font-weight: bold; 
color: #333333;
}
    </style>
  </head>
  <body marginheight=0 marginwidth=0 topmargin=0 leftmargin=2 rightmargin=2 bgcolor=#ffffff onclick=window.close()>
    <table width=100% cellpadding=4 cellspacing=0 border=0>
      <tr>
        <td class=schead valign=top colspan=3><?php echo PAGE_HEADING; ?></td>
      </tr>
      <tr>
        <td colspan=3><?php echo TEXT_EXP; ?></td>
      </tr>
      <tr>
        <td colspan=3>&nbsp;</td>
      </tr>
      <tr>
        <td valign=middle><?php echo TEXT_AMEX; ?></td>
        <td valign=middle width=120 align=right><?php echo tep_image(DIR_WS_IMAGES . 'cc_amex.gif', 'Amex image with security code'); ?></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan=3>&nbsp;</td>
      </tr>
      <tr>
        <td valign=middle><?php echo TEXT_VMCD; ?></td>
        <td valign=middle width=120 align=right><?php echo tep_image(DIR_WS_IMAGES . 'cc_visa.gif', 'Visa image with security code'); ?></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan=3>&nbsp;</td>
      </tr>
      <tr>
        <td><?php echo TEXT_CLICK; ?></td>
      </tr>
    </table>
  </body>
</html>
