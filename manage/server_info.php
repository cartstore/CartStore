<?php

/*

  $Id: server_info.php,v 1.6 2003/06/30 13:13:49 dgw_ Exp $



  CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA



  GNU General Public License Compatible

*/



  require('includes/application_top.php');



  $system = tep_get_system_information();

?>
 

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<div class="page-header"><h1><?php echo HEADING_TITLE; ?></h1></div>

<div class="well">Current Server Directory: <?php
$p = getcwd();
echo $p;
?></div>

     
<table class="table">

              <tr>

                <td class="smallText"><b><?php echo TITLE_SERVER_HOST; ?></b></td>

                <td class="smallText"><?php echo $system['host'] . ' (' . $system['ip'] . ')'; ?></td>

                <td class="smallText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo TITLE_DATABASE_HOST; ?></b></td>

                <td class="smallText"><?php echo $system['db_server'] . ' (' . $system['db_ip'] . ')'; ?></td>

              </tr>

              <tr>

                <td class="smallText"><b><?php echo TITLE_SERVER_OS; ?></b></td>

                <td class="smallText"><?php echo $system['system'] . ' ' . $system['kernel']; ?></td>

                <td class="smallText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo TITLE_DATABASE; ?></b></td>

                <td class="smallText"><?php echo $system['db_version']; ?></td>

              </tr>

              <tr>

                <td class="smallText"><b><?php echo TITLE_SERVER_DATE; ?></b></td>

                <td class="smallText"><?php echo $system['date']; ?></td>

                <td class="smallText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo TITLE_DATABASE_DATE; ?></b></td>

                <td class="smallText"><?php echo $system['db_date']; ?></td>

              </tr>

              <tr>

                <td class="smallText"><b><?php echo TITLE_SERVER_UP_TIME; ?></b></td>

                <td colspan="3" class="smallText">Server side execution disabled for security. -CartStore</td>

              </tr>

              

              <tr>

                <td class="smallText"><b><?php echo TITLE_HTTP_SERVER; ?></b></td>

                <td colspan="3" class="smallText"><?php echo $system['http_server']; ?></td>

              </tr>

              <tr>

                <td class="smallText"><b><?php echo TITLE_PHP_VERSION; ?></b></td>

                <td colspan="3" class="smallText"><?php echo $system['php'] . ' (' . TITLE_ZEND_VERSION . ' ' . $system['zend'] . ')'; ?></td>

              </tr>

            </table> 




<?php

  if (function_exists('ob_start')) {

?>



<?php

    ob_start();

    phpinfo();

    $phpinfo = ob_get_contents();

    ob_end_clean();



    $phpinfo = str_replace('border: 1px', '', $phpinfo);

    preg_match('/<body>(.*)<\/body>/', $phpinfo, $regs);

 

    echo $regs[1];

  } else {

    phpinfo();

  }

?>

       
<?php
    ob_start();
    phpinfo();
    $pinfo = ob_get_contents();
    ob_end_clean();
     
    $pinfo = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$pinfo);
    echo $pinfo;

?>
 

<!-- body_eof //-->



<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

