<?php
/*-----------------------------------------------------------------------------+
| MagneticOne                                                                  |
| Copyright (c) 2007 MagneticOne.com <contact@magneticone.com>                 |
| All rights reserved                                                          |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "license.txt"|
| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE   |
| AT THE FOLLOWING URL: http://www.magneticone.com/store/license.php           |
|                                                                              |
| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE  |
| THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  MAGNETICONE |
| (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING          |
| AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").    |
| PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT  |
| CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING,  |
| COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY  |
| (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS  |
| LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS  |
| AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND  |
| OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS  |
| AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE  |
| THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE. |
| THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.       |
|                                                                              |
| The Developer of the Code is MagneticOne,                                    |
| Copyright (C) 2006 - 2007 All Rights Reserved.                               |
+-----------------------------------------------------------------------------*/

#
# $Id: m1_export.php 816 2009-10-20 20:00:55Z tsergiy $
#


error_reporting(E_ALL & ~ E_NOTICE);
ini_set('display_errors', 1);
require_once dirname(__FILE__) . '/../../m1_loader.php';
M1_Loader::load('common_functions');
$m1_loader = & M1_Loader::getInstance();
require_once $m1_loader->_dir_catalog . 'includes/m1_export_all_config.php';
require_once $m1_loader->_dir_catalog . 'includes/m1_export_main.php';
unset($m1_loader);
 

$formats = array(
		'become',
		'jellyfish',
		'brokerbin',
		'buyersedge',
		'cnet',
		'cwebusa',
		'edirectory',
		'elmar',
		'epier',
		'everyprice',
		'froogle',
		'getprice',
		'googlebase',
		'kelkoo',
		'windowslive',
		'myshopping',
		'mysimon',
		'nextag',
		'powersource',
		'pricegrabber',
		'pricerunner',
		'ibuyer',
		'pricescan',
		'pronto',
		'rss',
		'shop',
		'shopferret',
		'shopify',
		'shopmania',
		'shoppingcom',
		'shopzilla',
		'smarter',
		'sortprice',
		'streetprice',
		'thefind',
		'vast',
		'yahoo',
		'custom',
		'xml'
	);

switch (m1_export_module :: getShoppingCartType()) {
	default: 
	case 'oscommerce': 
	case 'creloaded': m1_export_all_show_box_oscommerce($formats); break;
	case 'zencart': m1_export_all_show_box_zencart($formats); break;
}

/**
 * Show module links in admin box of osCommerce
 *
 * @param array $formats
 * @return boolean
 */
function m1_export_all_show_box_oscommerce($formats) {
	global $selected_box, $menu_dhtml;

	echo '<tr><td>';
	
	$heading = array();
	$contents = array();
	
	$heading[] = array('text'  => M1OutputConstant('M1_ALLINONE_MAINMENU_PREFIX').'Export Tools',
                     'link'  => tep_href_link('m1_export.php', 'selected_box=m1_export'));
	
	if (
				$selected_box == 'm1_export' || 
				$_GET['selected_box'] == 'm1_export' || 
				MENU_DHTML === true || 
				MENU_DHTML === 'True' || 
				$GLOBALS['menu_dhtml'] === 'True' || 
				$GLOBALS['menu_dhtml'] === true
	) {
	
		$formatsObject = new m1_export_module();

		if (m1_base::getShoppingCartType() == 'oscommerce') { // osCommerce

			//Sales Channel Analysis link
			$contents[] = array('text'  => '<a href="' . tep_href_link('m1_export_stats.php', 'selected_box=m1_export') . '" class="menuBoxContentLink">' . 'Sales Channel Analysis' . '</a><br>');

			foreach ($formats as $v) {
				
				$linkFilename = 'm1_' . $v . '.php';
				$classFile = DIR_FS_CATALOG . DIR_WS_CLASSES . 'm1export/' . $linkFilename;
				
				if (file_exists($classFile)) {
					require_once $classFile;
					$linkObjName = 'm1_' . $v . '_module';
					$linkObject = new $linkObjName();
					$contents[] = array('text'  => '<a href="' . tep_href_link($linkFilename, 'selected_box=m1_export') . '" class="menuBoxContentLink">' . $linkObject->Caption.' Export' . '</a><br>');
				}
			}
			
			if (file_exists(DIR_FS_CATALOG.DIR_WS_CLASSES.'m1export/m1_inventory_syndication.php')) {
				$contents[] = array('text'  => '<a href="' . tep_href_link('m1_inventory_syndication.php', 'selected_box=m1_export') . '" class="menuBoxContentLink">' . 'Inventory Syndication Export' . '</a><br>');
			}
			
			
		} elseif (m1_base::getShoppingCartType() == 'creloaded') { // CRE Loaded

			//Sales Channel Analysis link
			install_admin_page('m1_export_stats.php', $formatsObject->adminbox);
			install_admin_page('m1_category_matching.php', $formatsObject->adminbox);
			$box_text .= tep_admin_files_boxes('m1_export_stats.php', 'Sales Channel Analysis', 'NONSSL', 'selected_box=m1_export');

			foreach ($formats as $v) {
				
				$linkFilename = 'm1_' . $v . '.php';
				$classFile = DIR_FS_CATALOG . DIR_WS_CLASSES . 'm1export/' . $linkFilename;
				
				if (file_exists($classFile)) {
   					require_once $classFile;
					$linkObjName = 'm1_' . $v . '_module';
					$linkObject = new $linkObjName();
					install_admin_page($linkObject->adminBoxRow, $linkObject->adminbox);
   					$box_text .= tep_admin_files_boxes($linkFilename, $linkObject->Caption.' Export', 'NONSSL', 'selected_box=m1_export');
				}
			}
			
			
			$contents[] = array('text'  => $box_text);
		}
	}
	
	$box = new box;
	echo $box->menuBox($heading, $contents);
	echo '</td></tr>';
	return true;
}

/**
 * Show module links in admin box of Zen Cart
 * 
 * @param array $formats
 * @return boolean
 */
function m1_export_all_show_box_zencart($formats) {
	
	$za_contents = array();
	$za_heading = array('text' => M1OutputConstant('M1_ALLINONE_MAINMENU_PREFIX').'Export Tools', 'link' => zen_href_link('m1_export.php', '', 'NONSSL'));
	
	$formatsObject = new m1_export_module();

	//Sales Channel Analysis link
	$za_contents[] = array('text'  => 'Sales Channel Analysis', 'link' => zen_href_link('m1_export_stats.php', 'selected_box=m1_export', 'NONSSL'));

	foreach ($formats as $v) {
		
		$linkFilename = 'm1_' . $v . '.php';
		$classFile = DIR_FS_CATALOG . DIR_WS_CLASSES . 'm1export/' . $linkFilename;
				
		if (file_exists($classFile)) {
			require_once $classFile;
			$linkObjName = 'm1_' . $v . '_module';
			$linkObject = new $linkObjName();
			$za_contents[] = array('text'  => $linkObject->Caption.' Export', 'link' => zen_href_link($linkFilename, '', 'NONSSL'));
		}
	}

	
	if ($za_dir = @dir(DIR_WS_BOXES . 'extra_boxes')) {
		while ($zv_file = $za_dir->read()) {
			if (preg_match('/zcm1_export_dhtml.php$/', $zv_file)) {
				require(DIR_WS_BOXES . 'extra_boxes/' . $zv_file);
			}
		}
	}
	echo zen_draw_admin_box($za_heading, $za_contents);
	return true;
}
?>
