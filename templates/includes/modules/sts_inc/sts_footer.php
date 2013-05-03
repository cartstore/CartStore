<?php
/*
  $Id: sts_footer.php,v 4.1 2005/02/10 22:30:54 rigadin Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible

Based on: Simple Template System (STS) - Copyright (c) 2004 Brian Gallagher - brian@diamondsea.com
STS v4.1 by Rigadin (rigadin@osc-help.net)
*/

  // Get the number of requests
  require(DIR_WS_INCLUDES . 'counter.php');
  $sts->template['numrequests'] = $counter_now . ' ' . FOOTER_TEXT_REQUESTS_SINCE . ' ' . $counter_startdate_formatted;

/*
  The following copyright announcement can only be
  appropriately modified or removed if the layout of
  the site theme has been modified to distinguish
  itself from the default osCommerce-copyrighted
  theme.

  For more information please read the following
  Frequently Asked Questions entry on the osCommerce
  support site:

  http://www.cartstore.com/community.php/faq,26/q,50

  Please leave this comment intact together with the
  following copyright announcement.
*/
  $sts->template['footer_text']= FOOTER_TEXT_BODY;

// Get the banner if any
  $sts->start_capture();
  if ($banner = tep_banner_exists('dynamic', '468x50')) {
    echo tep_display_banner('static', $banner);
  }
  $sts->stop_capture ('banner_only');
  require (DIR_WS_MODULES . 'sts_inc/sts_display_output.php'); // Print everything out



?>

<!-- do not remove will void licence -->

<?php
$random_text = array("<div align=\"center\"><a href=\"http://www.cartstore.com\" target=\"_blank\">Powered by CartStore Shopping Cart Software</a></div>",
                      "<div align=\"center\"><a href=\"http://www.storecoders.com\" target=\"_blank\">osCommerce Design</a></div>",
					  "<div align=\"center\"><a href=\"http://www.storecoders.com\" target=\"_blank\">osCommerce Developers</a></div>",
					  "<div align=\"center\"><a href=\"http://www.storecoders.com\" target=\"_blank\">osCommerce Website Design</a></div>");
					  echo '<div class="cs_footer_php">';
srand(time());
$sizeof = count($random_text);
$random = (rand()%$sizeof);
print("$random_text[$random]");
?>

<!-- end do not remove will void licence -->



<?php
 if (basename($PHP_SELF) == FILENAME_CHECKOUT_SUCCESS){
     include(DIR_WS_MODULES . 'analytics_success.php');
 } else {
     include(DIR_WS_MODULES . 'analytics.php');
 }
?>
</div>
<!-- Body code opup -->
</body>
</html>