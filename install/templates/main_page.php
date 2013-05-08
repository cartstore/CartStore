<?php
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
<title>CartStore, Open Source E-Commerce Solutions</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" type="text/css" href="templates/main_page/stylesheet.css">
<link rel="stylesheet" type="text/css" href="ext/niftycorners/niftyCorners.css">
<script type="text/javascript" src="ext/niftycorners/nifty.js"></script>
</head>

<body>
<div id="pageHeader">
  <div>
    <div style="float: right; padding-top: 40px; padding-right: 15px; color: #000000; font-weight: bold;"></div>
    <a href="index.php"><img src="images/logo.png" border="0" style="margin: 10px 10px 0px 10px;" /></a> </div>
</div>
<div id="pageContent">
  <?php
  require('templates/pages/' . $page_contents);
?>
</div>
<div id="pageFooter"> <a href="../copyright.html" onclick="window.open(this.href, '', 'resizable=no,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=yes,dependent=no,width=640,height=480'); return false;">Copyright &copy; 2013 CartStore</a> </a></div>
</body>
</html>