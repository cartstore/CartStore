
<div class="modulelist">
  <center><?php
          echo tep_display_banner('dynamic', 'specials');
?></center>
 <?php
          include(DIR_WS_MODULES . 'homecats.php');
?>

  <div id="news_sticky">
    <?php
          require_once(DIR_WS_MODULES . FILENAME_NEWSDESK_STICKY);
?>
  </div>
  <?php
          include(DIR_WS_MODULES . FILENAME_FEATURED);
?>
  <?php
          include(DIR_WS_MODULES . 'new_products.php');
?>
  <div class="newsdesk">
  <?php
          require_once(DIR_WS_MODULES . FILENAME_NEWSDESK);
?>
  </div>
  <?php
          include(DIR_WS_BOXES . 'manufacturers_logos.php');
?>


</div>
