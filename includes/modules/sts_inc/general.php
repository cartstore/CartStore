<?php
/*
 $Id: general.php,v 4.1 2005/11/03 05:57:21 rigadin Exp $

 CartStore eCommerce Software, for The Next Generation
 http://www.cartstore.com

 Copyright (c) 2008 Adoovo Inc. USA

 GNU General Public License Compatible

 Based on: Simple Template System (STS) - Copyright (c) 2004 Brian Gallagher - brian@diamondsea.com
 STS v4.1 by Rigadin (rigadin@osc-help.net)
 */

// Set $templatedir and $templatepath (aliases) to current template path on web server, allowing for HTTP/HTTPS differences, removing the trailing slash
$sts -> template['templatedir'] = substr(((($request_type == 'SSL') ? DIR_WS_HTTPS_CATALOG : DIR_WS_HTTP_CATALOG) . STS_TEMPLATE_DIR), 0, -1);
$sts -> template['templatepath'] = $sts -> template['templatedir'];

$sts -> template['htmlparams'] = HTML_PARAMS;
// Added in v4.0.7

$sts -> template['date'] = strftime(DATE_FORMAT_LONG);

$sts -> template['sid'] = tep_session_name() . '=' . tep_session_id();
$sts -> template['cataloglogo'] = '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image(DIR_WS_IMAGES . 'oscommerce.gif', 'osCommerce') . '</a>';
$sts -> template['urlcataloglogo'] = tep_href_link(FILENAME_DEFAULT);

$sts -> template['myaccountlogo'] = '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_image(DIR_WS_IMAGES . 'header_account.gif', HEADER_TITLE_MY_ACCOUNT) . '</a>';
$sts -> template['urlmyaccountlogo'] = tep_href_link(FILENAME_ACCOUNT, '', 'SSL');

$sts -> template['cartlogo'] = '<a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '">' . tep_image(DIR_WS_IMAGES . 'header_cart.gif', HEADER_TITLE_CART_CONTENTS) . '</a>';
$sts -> template['urlcartlogo'] = tep_href_link(FILENAME_SHOPPING_CART);

$sts -> template['checkoutlogo'] = '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_image(DIR_WS_IMAGES . 'header_checkout.gif', HEADER_TITLE_CHECKOUT) . '</a>';
$sts -> template['urlcheckoutlogo'] = tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL');

$sts -> template['breadcrumbs'] = $breadcrumb->trail(' >>');

if (tep_session_is_registered('customer_id')) {
	$sts -> template['myaccount'] = '<a href=' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . ' class="headerNavigation">' . HEADER_TITLE_MY_ACCOUNT . '</a>';
	$sts -> template['urlmyaccount'] = tep_href_link(FILENAME_ACCOUNT, '', 'SSL');
	$sts -> template['logoff'] = '<a href=' . tep_href_link(FILENAME_LOGOFF, '', 'SSL') . ' class="headerNavigation">' . HEADER_TITLE_LOGOFF . '</a>';
	$sts -> template['urllogoff'] = tep_href_link(FILENAME_LOGOFF, '', 'SSL');
	$sts -> template['myaccountlogoff'] = $sts -> template['myaccount'] . " | " . $sts -> template['logoff'];
} else {
	$sts -> template['myaccount'] = '<a href=' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . ' class="headerNavigation">' . HEADER_TITLE_MY_ACCOUNT . '</a>';
	$sts -> template['urlmyaccount'] = tep_href_link(FILENAME_ACCOUNT, '', 'SSL');
	$sts -> template['logoff'] = '';
	$sts -> template['urllogoff'] = '';
	$sts -> template['myaccountlogoff'] = $sts -> template['myaccount'];
}

$sts -> template['cartcontents'] = '<a href=' . tep_href_link(FILENAME_SHOPPING_CART) . ' class="headerNavigation">' . HEADER_TITLE_CART_CONTENTS . '</a>';
$sts -> template['urlcartcontents'] = '<a href=' . tep_href_link(FILENAME_SHOPPING_CART) . ' class="headerNavigation">' . HEADER_TITLE_CART_CONTENTS . '</a>';

$sts -> template['checkout'] = '<a href=' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . ' class="headerNavigation">' . HEADER_TITLE_CHECKOUT . '</a>';
$sts -> template['urlcheckout'] = tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL');
$sts -> template['headertags'] = "<title>" . TITLE . "</title>";
?>
