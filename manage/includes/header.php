<script language="javascript" type="text/javascript">
<!--
function popUp(url) {
	var winHandle = randomString();
	newwindow=window.open(url,winHandle,'height=800,width=1000');
}

function randomString() {
	var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var string_length = 8;
	var randomstring = '';
	for (var i=0; i<string_length; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
	}
	return randomstring;
}

// -->
</script>
<link href="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />
<link href="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>templates/jquery-ui/css/cartstoreadmin/jquery-ui.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>../templates/system/fancybox/jquery.fancybox.css" media="screen" />
<!--<link href="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>ckfinder/sample.css" rel="stylesheet" type="text/css" />-->
<script type="text/javascript" src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>includes/animatedcollapse.js"></script>
<script src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>../templates/system/jquery/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>../templates/system/jquery/js/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>../templates/system/fancybox/jquery.mousewheel.pack.js"></script>
<script type="text/javascript" src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>../templates/system/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript" src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>../templates/system/fancybox/jquery.easing.pack.js"></script>
<script src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>templates/jquery.init.local.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>ckfinder/ckfinder.js"></script>
<script language="javascript" src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>includes/general.js"></script>
<script type="text/javascript" src="templates/superfish/js/superfish.js"></script>
<script type="text/javascript" src="templates/superfish/js/hoverIntent.js"></script>
<script type="text/javascript" src="templates/superfish/js/jquery.bgiframe.min.js"></script>
</head><body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<div id="wrapper">
<div id="header">
  <div id="logo"><a

  href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>index.php"><img src="templates/admin/images/logo.jpg" width="294"

  height="70" alt="" /></a></div>
  <div id="top">
    <div class="loginWrap">
      <div class="login">
        <ul>
          <li class="admin"><a

    href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>admin_account.php">Welcome
            <?php
  print_r($_SESSION['login_email_address']);
?>
            </a></li>
          <li class="logout"><a

    href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>logoff.php"

    title="Logoff!">Logout</a></li>
        </ul>
      </div>
      <div class="date">
        <?php
  echo date("l F d, Y");
?>
      </div>
      <div class="clear"></div>
    </div>
    <div class="searchWrap">
      <form method="get" action="orders.php" name="orders" />
      <input

  type="text" class="inputbox" onClick="value=''" value="Order Search / ID#"

  name="oID" />
      <input type="hidden" value="edit" name="action">
      <input type="submit" class="button" value="." />
      </form>
      <form method="get" action="customers.php" name="search" />
      <input

  type="text" class="inputbox" onClick="value=''" value="Customer Search"

  name="search" />
      <input type="submit" class="button" value="." />
      </form>
      <form method="get" action="categories.php" name="search">
        <input

  type="text" class="inputbox" onClick="value=''" value="Product Search"

  name="search" />
        <input type="submit" class="button" value="." />
      </form>
    </div>
  </div>
  <div class="clear"></div>
</div>
<div class="clear"></div>
<div id="menu">
  <ul>
    <li><a href="admin_account.php">Administrator</a>
      <ul>
        <li><a

      href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>admin_members.php?page=1&mID=16&action=new_member">New Member</a></li>
        <li><a href="javascript:;" onClick="myJsFunc();">System (Advanced)</a>
          <ul>
            <li class="active parent"><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=1&selected_box=configuration">Configuration</a>
              <ul id="config_menu">
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=1">My Store</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=2">Minimum Values</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=3">Maximum Values</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=333">Image Magic</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=4">Images</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=5">Customer Details</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=7">Shipping/Packaging</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=888001">Product Information</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=9">Stock</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=10">Logging</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=11">Cache</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=12">E-Mail Options</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=13">Download</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=14">GZip Compression</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=15">Sessions</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=99">Featured Products</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=17">All Products</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=22">Points and Rewards</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=12954">Wish List Settings</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=30">eBay Auctions</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=900">Affiliate Program</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=62">Feed Settings</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=888004">SEO URLs</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=18">Links</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=73">Terms &amp; Conditions</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=10020">Ajax enhanced search</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=888005">Year Make Model</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=6501">Recover Cart Sales</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=1661">jQuery Banner Options</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=888006">PHPIDS</a></li>

 <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=888007">MultiGeoZone MultiTable Shipping</a></li>


              </ul>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>modules.php?set=payment&selected_box=modules">Modules </a>
              <ul>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>modules.php?set=payment">Payment</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>modules.php?set=shipping">Shipping</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>modules.php?set=sts">STS</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>modules.php?set=ordertotal">Order Total</a></li>
<li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>modules.php?set=checkout">Checkout</a></li>
<li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>modules.php?set=sociallogin">Social Login</a></li>
              </ul>
            </li>
            <li><a href="javascript:;" onClick="myJsFunc();">Tools</a>
              <ul>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>cache.php">Cache Control</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>server_info.php">Server Info</a></li>
               <li><a href="<?php echo tep_href_link(FILENAME_PHPIDS); ?>">PHPIDS Log</a></li>
               <li><a href="<?php echo tep_href_link(FILENAME_BANNED_IP); ?>">Banned IP</a></li>
               <li><a href="<?php echo  tep_href_link(FILENAME_MYSQL_PERFORMANCE); ?>" class="menuBoxContentLink"><?php echo BOX_TOOLS_MYSQL_PERFORMANCE; ?></a></li>
             </ul>
            </li>
            <li><a href="javascript:;" onClick="myJsFunc();">Delivery Time Table</a>
              <ul>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN . FILENAME_DEFAULT_DELIVERY_TIME;
?>">
                  <?php echo BOX_DEFAULT_DELIVERY_TIME; ?>
                  </a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN . FILENAME_EMERGENCY_DELIVERY_TIME;
?>">
                  <?php echo BOX_SPECIAL_DELIVERY_TIME; ?>
                  </a></li>
              </ul>
            </li>
            <li> <a href="javascript:popUp('./Abs/')">Backup / Rollback</a></li>
            <li> <a href="../scanner_2.6.php" target="_BLANK">Scan Virus/Trojans</a></li>

            <li> <a href="javascript:popUp('phpMyAdmin/index.php')">PhMyAdmin</a></li>
            <li> <a href="javascript:;" onClick="myJsFunc();">Header Tags</a>
              <ul>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>header_tags_english.php">Text Control</a></li>
                <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>header_tags_fill_tags.php">Fill Tags</a> </li>
              </ul>
            </li>
          </ul>
        </li>
      </ul>
    </li>
    <li><a href="orders.php">Manage Sales</a>
      <ul>
        <li><a

      href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>orders.php">Orders</a></li>
        <li><a

      href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN . FILENAME_STATS_RECOVER_CART_SALES;
?>">
          <?php echo BOX_REPORTS_RECOVER_CART_SALES; ?>
          </a></li>
        <li><a

      href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN . FILENAME_RECOVER_CART_SALES;
?>">
          <?php echo BOX_TOOLS_RECOVER_CART; ?>
          </a></li>
        <li><a

      href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>create_order.php">Create Order</a></li>
        <li><a

      href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>coupon_admin.php?selected_box=gv_admin">Coupons</a>
     <ul>
     	<li><a  href="<?php  echo tep_href_link(FILENAME_COUPON_ADMIN); ?>"><?php echo BOX_COUPON_ADMIN; ?></a></li>
     	<li><a  href="<?php  echo tep_href_link(FILENAME_GV_QUEUE); ?>"><?php echo BOX_GV_ADMIN_QUEUE; ?></a></li>
     	<li><a  href="<?php  echo tep_href_link(FILENAME_GV_MAIL); ?>"><?php echo BOX_GV_ADMIN_MAIL; ?></a></li>
     	<li><a  href="<?php  echo tep_href_link(FILENAME_GV_SENT); ?>"><?php echo BOX_GV_ADMIN_SENT; ?></a></li>
     </ul>
    </li>
        <li><a

      href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>export_orders_csv.php">Export Orders</a></li>
        <li><a href="<?php echo HTTP_CATALOG_SERVER; ?><?php echo DIR_WS_ADMIN; ?>export_quickbooks_csv.php">Export to QuickBooks</a></li>
        <li class="active parent"><a href="returns.php">Returns</a>
          <ul>
            <li><a href="<?php echo HTTP_CATALOG_SERVER; ?><?php echo DIR_WS_ADMIN . FILENAME_RETURNS; ?>"><?php echo BOX_RETURNS_MAIN; ?></a></li>
            <li><a href="<?php echo HTTP_CATALOG_SERVER; ?><?php echo DIR_WS_ADMIN . FILENAME_RETURNS_REASONS; ?>"><?php echo BOX_RETURNS_REASONS; ?></a></li>
            <li><a

        href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN . FILENAME_REFUND_METHODS;
?>">
              <?php echo BOX_HEADING_REFUNDS; ?>
              </a></li>
            <li><a

        href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN . FILENAME_RETURNS_STATUS;
?>">
              <?php echo BOX_RETURNS_STATUS; ?>
              </a></li>
            <li><a

        href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN . FILENAME_RETURNS_TEXT;
?>">
              <?php echo BOX_RETURNS_TEXT; ?>
              </a></li>
          </ul>
        <li class="active parent"><a

      href="javascript:;" onClick="myJsFunc();">Taxes</a>
          <ul>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>countries.php">Countries</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>zones.php">Zones</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>geo_zones.php">Tax Zones</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>tax_classes.php">Tax Classes</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>tax_rates.php">Tax Rates</a></li>


    <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>wa_taxes_report.php">WA State Tax Report</a></li>


          </ul>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>currencies.php">Currencies</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>orders_status.php">Order Status Types</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=1">Store Infomation Settings</a></li>
        <li class="active parent"><a href="javascript:;" onClick="myJsFunc();">Affiliates</a>
          <ul>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>affiliate_summary.php">Summary</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>affiliate_affiliates.php">Affiliates</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>affiliate_payment.php">Payment</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>affiliate_sales.php">Sales</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>affiliate_clicks.php">Clicks</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>affiliate_banners.php">Banners</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>affiliate_news.php">Affiliate News</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>affiliate_newsletters.php">Affiliate Newsletter</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>affiliate_contact.php">Contact</a></li>
          </ul>
      </ul>
    </li>
    <li><a href="javascript:;" onClick="myJsFunc();">Products &amp; Categories</a>
      <ul>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>categories.php">Create/Manage Categories &amp; <br>
          Products</a></li>
        <li> <a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_CATALOG;
?>" target="_BLANK">Preview this Store</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>easypopulate.php">CSV Catalog Import/Export</a></li>
        <li><a href="<?php  echo HTTP_CATALOG_SERVER; ?><?php  echo DIR_WS_ADMIN . FILENAME_PRODUCTS_OPTIONS; ?>">Create Product Options</a>
        </li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>new_attributes.php">Quick Insert Product Options</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>product_list.php">Quick Inventory Editor</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>manufacturers.php">Create/Manage Manufacturers</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>reviews.php">Reviews Manager</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>xsell.php">Cross Sell Products</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>specials.php">Create/Manage Specials</a></li>
        <li> <a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>products_expected.php">Edit Products Expected</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>product_extra_fields.php">Define Product Extra Fields</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=9">Manage Stock Settings</a></li>
        <li><a href="javascript:;" onClick="myJsFunc();">All in One Export <br>
          (Commercial)</a>
          <ul>
            <li><a class="menuBoxHeadingLink" href="m1_export.php?selected_box=m1_export">M1 Export Tools</a></li>
            <li><a class="menuBoxContentLink" href="m1_export_stats.php?selected_box=m1_export">Sales Channel Analysis</a></li>
            <li><a class="menuBoxContentLink" href="m1_become.php?selected_box=m1_export">Become.com Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_jellyfish.php?selected_box=m1_export">Bing Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_brokerbin.php?selected_box=m1_export">BrokerBin.com Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_buyersedge.php?selected_box=m1_export">BuyersEdge Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_cnet.php?selected_box=m1_export">CNET.com Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_cwebusa.php?selected_box=m1_export">CWebUSA Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_edirectory.php?selected_box=m1_export">eDirectory.co.uk Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_elmar.php?selected_box=m1_export">Elm@r Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_epier.php?selected_box=m1_export">ePier Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_everyprice.php?selected_box=m1_export">EveryPrice.com Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_froogle.php?selected_box=m1_export">Froogle Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_getprice.php?selected_box=m1_export">Getprice Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_googlebase.php?selected_box=m1_export">Google Base Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_kelkoo.php?selected_box=m1_export">Kelkoo Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_windowslive.php?selected_box=m1_export">MSN Shopping Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_myshopping.php?selected_box=m1_export">MyShopping Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_mysimon.php?selected_box=m1_export">mySimon Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_nextag.php?selected_box=m1_export">NexTag Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_powersource.php?selected_box=m1_export">PowerSource Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_pricegrabber.php?selected_box=m1_export">PriceGrabber Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_pricerunner.php?selected_box=m1_export">PriceRunner Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_ibuyer.php?selected_box=m1_export">PriceSaving Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_pricescan.php?selected_box=m1_export">PriceSCAN.com Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_pronto.php?selected_box=m1_export">Pronto.com Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_rss.php?selected_box=m1_export">RSS Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_shop.php?selected_box=m1_export">SHOP.COM Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_shopferret.php?selected_box=m1_export">ShopFerret Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_shopify.php?selected_box=m1_export">Shopify Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_shopmania.php?selected_box=m1_export">ShopMania Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_shoppingcom.php?selected_box=m1_export">Shopping.com Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_shopzilla.php?selected_box=m1_export">Shopzilla.com Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_smarter.php?selected_box=m1_export">Smarter.com Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_sortprice.php?selected_box=m1_export">Sortprice.com Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_streetprice.php?selected_box=m1_export">StreetPrices.com Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_thefind.php?selected_box=m1_export">TheFind.com Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_vast.php?selected_box=m1_export">Vast Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_yahoo.php?selected_box=m1_export">Yahoo Store Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_custom.php?selected_box=m1_export">Custom Data Feed Export</a></li>
            <li><a class="menuBoxContentLink" href="m1_xml.php?selected_box=m1_export">Advanced XML Export</a></li>
          </ul>
        </li>
        <li><a href="javascript:;" onClick="myJsFunc();">Amazon  Export (Commercial)</a>
          <ul>
            <li><a href="m1_amazon.php?selected_box=m1_amazon">M1 Amazon Export</a></li>
            <li><a  href="m1_amazon.php?selected_box=m1_amazon">Amazon Export Settings</a></li>
            <li><a  href="m1_export_stats.php?selected_box=m1_amazon">Sales Channel Analysis</a></li>
            <li><a  href="m1_amazon_apparel.php?selected_box=m1_amazon">Amazon Apparel Export</a></li>
            <li><a  href="m1_amazon_autoaccessory.php?selected_box=m1_amazon">Amazon AutoAccessory <br>
              Export</a></li>
            <li><a  href="m1_amazon_beauty.php?selected_box=m1_amazon">Amazon Beauty Export</a></li>
            <li><a  href="m1_amazon_camera.php?selected_box=m1_amazon">Amazon Camera and <br>
              Photo Export</a></li>
            <li><a  href="m1_amazon_electronics.php?selected_box=m1_amazon">Amazon Consumer Electronics <br>
              Export</a></li>
            <li><a  href="m1_amazon_food.php?selected_box=m1_amazon">Amazon Food and Beverages <br>
              Export</a></li>
            <li><a  href="m1_amazon_health.php?selected_box=m1_amazon">Amazon Health &amp; Personal <br>
              Care Export</a></li>
            <li><a  href="m1_amazon_home.php?selected_box=m1_amazon">Amazon Home &amp; Garden <br>
              Export</a></li>
            <li><a  href="m1_amazon_jewelry.php?selected_box=m1_amazon">Amazon Jewelry Export</a></li>
            <li><a  href="m1_amazon_musical.php?selected_box=m1_amazon">Amazon Musical Instruments <br>
              Export</a></li>
            <li><a  href="m1_amazon_office.php?selected_box=m1_amazon">Amazon Office Products <br>
              Export</a></li>
            <li><a  href="m1_amazon_petsupplies.php?selected_box=m1_amazon">Amazon Pet Supplies Export</a></li>
            <li><a  href="m1_amazon_software.php?selected_box=m1_amazon">Amazon SoftwareVideo <br>
              Games Export</a></li>
            <li><a  href="m1_amazon_sports.php?selected_box=m1_amazon">Amazon Sporting Goods <br>
              Export</a></li>
            <li><a  href="m1_amazon_tools.php?selected_box=m1_amazon">Amazon Tools Export</a></li>
            <li><a  href="m1_amazon_toys.php?selected_box=m1_amazon">Amazon ToysBaby Export</a></li>
            <li><a  href="m1_amazon_watches.php?selected_box=m1_amazon">Amazon WATCHES Export</a></li>
            <li><a  href="m1_amazon_wireless.php?selected_box=m1_amazon">Amazon Wireless Export</a></li>
            <li><a  href="m1_amazon_marketplace.php?selected_box=m1_amazon">Amazon Marketplace Export</a></li>
            <li><a  href="m1_amazon_productads.php?selected_box=m1_amazon">Amazon Product Ads Export</a></li>
          </ul>
        </li>
      </ul>
    </li>
    <li><a href="javascript:;" onClick="myJsFunc();"> Drop Shipping System</a>
      <ul>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>vendors.php">Vendor Manager</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>prods_by_vendor.php">Product Vendor Reports</a></li>
        <li><a href="javascript:;" onClick="myJsFunc();">Vendors Orders List</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>move_vendor_prods.php">Move Products Between <br>
          Vendors</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=7&cID=4660&action=edit">Enable Vendor System</a></li>
      </ul>
    </li>
    <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>customers.php">Manage Customers</a>
      <ul>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>customers.php">Manage Customers</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>customers_points.php">Manage Customers Points</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>customers_points_pending.php">Manage Pending Points</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>customers_points_referral.php">Manage Referral Points</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>customers_groups.php">Create & Manage Customers <br>
          Groups</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>constant_contact.php">Export Customers for <br>
          Constant Contact</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>customers_points_pending.php">Approve Pending Points</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>create_account.php">Create Customer Account</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>mail.php">Send Customer(s) a Email</a></li>
      </ul>
    </li>
    <li><a href="javascript:;" onClick="myJsFunc();">Promotions</a>
      <ul>
        <li><a href="javascript:;" onClick="myJsFunc();">XML SiteMaps (Commercial)</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>banner_manager.php">Banner Manager</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>mail.php">Send Emails</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>newsletters.php">Newsletter Manager</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>whos_online.php">See Who's Online</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>link_manage.php">Manage the Links Manager</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>feeders.php">Google Base Export</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=22">Enable Points Rewards System</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=30&cID=885">Ebay Auction Import settings</a></li>
        <li class="active parent"><a href="javascript:;" onClick="myJsFunc();">Affiliates</a>
          <ul>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>affiliate_summary.php">Summary</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>affiliate_affiliates.php">Affiliates</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>affiliate_payment.php">Payment</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>affiliate_sales.php">Sales</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>affiliate_clicks.php">Clicks</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>affiliate_banners.php">Banners</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>affiliate_news.php">Affiliate News</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>affiliate_newsletters.php">Affiliate Newsletter</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>affiliate_contact.php">Contact</a></li>
            <li><a href="<?php
  echo DIR_WS_ADMIN;
?>affiliate_statistics.php">Affiliate Statistics</a></li>
          </ul>
        </li>
      </ul>
    </li>
    <li><a href="javascript:;" onClick="myJsFunc();">CMS</a>
      <ul>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>articles.php?selected_box=articles">Article/Blog Manager</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>newsdesk.php?selected_box=newsdesk">Front Page Article Manager</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>configuration.php?gID=923&selected_box=configuration">RSS News Module</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>events_main.php">Event/Calendar Manager</a></li>
        <li class="active parent"><a href="javascript:;" onClick="myJsFunc();">Module Area Editors</a>
          <ul>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>general_area1.php">Area 1</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>general_area2.php">Area 2</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>general_area3.php">Area 3</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>general_area4.php">Area 4</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>general_area5.php">Area 5</a></li>
          </ul>
        </li>
        <li class="active parent"><a href="javascript:;" onClick="myJsFunc();">Menu Code Editors</a>
          <ul>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>static_menu1.php">Menu  1</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>static_menu2.php">Menu  2</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>static_menu3.php">Menu  3</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>static_menu3.php">Menu  4</a></li>
          </ul>
        </li>
        <li class="active parent"><a href="javascript:;" onClick="myJsFunc();">Adsence Code Editors</a>
          <ul>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>adsence.php">Adsence Code Editor</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>adsence2.php">Adsence Code Editor 2</a></li>
            <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>adsence3.php">Adsence Code Editor 3</a></li>
          </ul>
        </li>
        <li><a href="javascript:popUp('<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>ckfinder/ckfinder.html')">Media Manager</a></li>
        <li><a href="javascript:popUp('<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>imageuploader/')">Upload Files</a></li>
        <li> <a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>link_manage.php">Links Manager</a></li>
      </ul>
    </li>
    <li><a href="javascript:;" onClick="myJsFunc();"> Reports</a>
      <ul>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>stats_sales_report2.php">SalesReport2</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>stats_products_viewed.php">Products Viewed</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>stats_products_purchased.php">Products Purchased</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>stats_customers.php">Customer Orders-Total</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN . FILENAME_SUPERTRACKER;
?>">
          <?php echo BOX_REPORTS_SUPERTRACKER; ?>
          </a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>stats_low_stock_attrib.php">Low Stock Report</a></li>
        <li><a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>stats_monthly_sales.php">Monthly Sales/Tax</a></li>
      </ul>
    </li>
  </ul>
</div>
<div id="messageWrap">
  <div class="latestMgs"><b>Latest Message:</b>
    <?php

	//include "error_log";
 // $URL = "http://storecoders.com/cart_feed/welcome.php";
 // $handle = fopen($URL, "r");
//  print(fread($handle, 1000000));
  $orders_pending_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS . " where orders_status = '1'");
  $orders_pending = tep_db_fetch_array($orders_pending_query);
?>
  </div>
  <ul>
    <li class="mgsInbox">You have
      <?php echo $orders_pending ['count']; ?>
      New Orders. <a href="<?php
  echo HTTP_CATALOG_SERVER;
?><?php
  echo DIR_WS_ADMIN;
?>orders.php">Go to Orders</a></li>
    <li class="help"><a href="http://www.cartstore.com" target="_blank">Get Help for Cart Store</a></li>
  </ul>
</div>
<div id="container">
<?php
  if ($messageStack->size > 0) {
      echo $messageStack->output();
  }

?>

