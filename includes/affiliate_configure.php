<?php
/*
  $Id: affiliate_configure.php,v 2.00 2003/10/12

  

  Contribution based on:

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  define('AFFILIATE_KIND_OF_BANNERS','2');          // 1 Direct Link to Banner ; no counting of how much banners are shown
                                                    // 2 Banners are shown with affiliate_show_banner.php; bannerviews are counted (recommended)
  define('AFFILIATE_SHOW_BANNERS_DEBUG', 'false');  // Debug for affiliate_show_banner.php; If you have difficulties geting banners set to true,
                                                    // and try to load the banner in a new Browserwindow
                                                    // i.e.: http://yourdomain.com/affiliate_show_banner.php?ref=3569&affiliate_banner_id=3
  define('AFFILIATE_SHOW_BANNERS_DEFAULT_PIC', ''); // absolute path to default pic for affiliate_show_banner.php, which is showed if no banner is found
                                                    // Only works with AFFILIATE_KIND_OF_BANNERS=2
?>