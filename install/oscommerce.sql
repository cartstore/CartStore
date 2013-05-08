SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `address_book` (
  `address_book_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_id` int(11) NOT NULL DEFAULT '1',
  `entry_gender` char(1) NOT NULL DEFAULT '',
  `entry_company` varchar(32) DEFAULT NULL,
  `entry_company_tax_id` varchar(32) DEFAULT NULL,
  `entry_firstname` varchar(32) NOT NULL DEFAULT '',
  `entry_lastname` varchar(32) NOT NULL DEFAULT '',
  `entry_street_address` varchar(64) NOT NULL DEFAULT '',
  `entry_street_address_2` varchar(64) NOT NULL,
  `entry_suburb` varchar(32) DEFAULT NULL,
  `entry_postcode` varchar(10) NOT NULL DEFAULT '',
  `entry_city` varchar(32) NOT NULL DEFAULT '',
  `entry_state` varchar(32) DEFAULT NULL,
  `entry_country_id` int(11) NOT NULL DEFAULT '0',
  `entry_zone_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`address_book_id`),
  KEY `idx_address_book_customers_id` (`customers_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

INSERT INTO `address_book` VALUES(4, 4, '', NULL, NULL, 'Jef', 'Shilt', '221 W Washington Apt M', '', NULL, '45701', 'Athens', '', 223, 47);
INSERT INTO `address_book` VALUES(5, 5, '', NULL, NULL, 'test', 'test', '750 s orange blossom trail', 'second line', NULL, '32805', 'orlando', '', 223, 18);
INSERT INTO `address_book` VALUES(6, 6, '', NULL, NULL, 'test', 'test', '750 s orange blossom trail', '', NULL, '32805', 'orlando', '', 223, 18);
INSERT INTO `address_book` VALUES(10, 4, '', NULL, NULL, 'Jef', 'Shilt', '10746 5th Ave NE', '', NULL, '98125', 'Seattle', '', 223, 62);
INSERT INTO `address_book` VALUES(11, 5, '', NULL, NULL, 'test', 'test', '123 test way', '', NULL, '32805', 'orlando', '', 223, 18);

CREATE TABLE IF NOT EXISTS `address_format` (
  `address_format_id` int(11) NOT NULL AUTO_INCREMENT,
  `address_format` varchar(128) NOT NULL DEFAULT '',
  `address_summary` varchar(48) NOT NULL DEFAULT '',
  PRIMARY KEY (`address_format_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

INSERT INTO `address_format` VALUES(1, '$firstname $lastname$cr$streets$cr$city, $postcode$cr$statecomma$country', '$city / $country');
INSERT INTO `address_format` VALUES(2, '$firstname $lastname$cr$streets$cr$city, $state    $postcode$cr$country', '$city, $state / $country');
INSERT INTO `address_format` VALUES(3, '$firstname $lastname$cr$streets$cr$city$cr$postcode - $statecomma$country', '$state / $country');
INSERT INTO `address_format` VALUES(4, '$firstname $lastname$cr$streets$cr$city ($postcode)$cr$country', '$postcode / $country');
INSERT INTO `address_format` VALUES(5, '$firstname $lastname$cr$streets$cr$postcode $city$cr$country', '$city / $country');

CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_groups_id` int(11) DEFAULT NULL,
  `admin_firstname` varchar(32) NOT NULL DEFAULT '',
  `admin_lastname` varchar(32) DEFAULT NULL,
  `admin_email_address` varchar(96) NOT NULL DEFAULT '',
  `admin_password` varchar(40) NOT NULL DEFAULT '',
  `admin_created` datetime DEFAULT NULL,
  `admin_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `admin_logdate` datetime DEFAULT NULL,
  `admin_lognum` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `admin_email_address` (`admin_email_address`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

INSERT INTO `admin` VALUES(16, 1, 'System', 'Admin', 'bugs@cartstore.com', '626f3ddad9129695182d7c1823e4e29c:59', '2009-05-22 23:40:41', '2011-08-10 16:15:07', '2012-10-18 17:14:39', 1075);

CREATE TABLE IF NOT EXISTS `admin_access_files` (
  `file_access_id` int(4) NOT NULL AUTO_INCREMENT,
  `admin_files_id` int(11) NOT NULL DEFAULT '0',
  `admin_id` int(11) NOT NULL DEFAULT '1',
  `admin_access_values` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`file_access_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `admin_access_files` VALUES(1, 21, 3, 2);

CREATE TABLE IF NOT EXISTS `admin_files` (
  `admin_files_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_files_name` varchar(64) NOT NULL DEFAULT '',
  `admin_files_is_boxes` tinyint(5) NOT NULL DEFAULT '0',
  `admin_files_to_boxes` int(11) NOT NULL DEFAULT '0',
  `admin_groups_id` set('1','2','3','4') NOT NULL DEFAULT '1',
  `admin_id` set('1','2','3','4') NOT NULL DEFAULT '1',
  PRIMARY KEY (`admin_files_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=54 ;

INSERT INTO `admin_files` VALUES(1, 'administrator.php', 1, 0, '1', '1');
INSERT INTO `admin_files` VALUES(2, 'configuration.php', 1, 0, '1', '1');
INSERT INTO `admin_files` VALUES(3, 'catalog.php', 1, 0, '1', '1');
INSERT INTO `admin_files` VALUES(4, 'modules.php', 1, 0, '1', '1');
INSERT INTO `admin_files` VALUES(5, 'customers.php', 1, 0, '1', '1,3');
INSERT INTO `admin_files` VALUES(6, 'taxes.php', 1, 0, '1', '1');
INSERT INTO `admin_files` VALUES(7, 'localization.php', 1, 0, '1', '1');
INSERT INTO `admin_files` VALUES(8, 'reports.php', 1, 0, '1', '1,4');
INSERT INTO `admin_files` VALUES(9, 'tools.php', 1, 0, '1', '1');
INSERT INTO `admin_files` VALUES(10, 'admin_members.php', 0, 1, '1', '1');
INSERT INTO `admin_files` VALUES(11, 'admin_files.php', 0, 1, '1', '1');
INSERT INTO `admin_files` VALUES(12, 'configuration.php', 0, 2, '1', '1');
INSERT INTO `admin_files` VALUES(13, 'categories.php', 0, 3, '1', '1');
INSERT INTO `admin_files` VALUES(14, 'products_attributes.php', 0, 3, '1', '1');
INSERT INTO `admin_files` VALUES(15, 'manufacturers.php', 0, 3, '1', '1');
INSERT INTO `admin_files` VALUES(16, 'reviews.php', 0, 3, '1', '1');
INSERT INTO `admin_files` VALUES(17, 'specials.php', 0, 3, '1', '1');
INSERT INTO `admin_files` VALUES(18, 'products_expected.php', 0, 3, '1', '1');
INSERT INTO `admin_files` VALUES(19, 'modules.php', 0, 4, '1', '1');
INSERT INTO `admin_files` VALUES(20, 'customers.php', 0, 5, '1', '1');
INSERT INTO `admin_files` VALUES(21, 'orders.php', 0, 5, '1', '1,3');
INSERT INTO `admin_files` VALUES(22, 'countries.php', 0, 6, '1', '1');
INSERT INTO `admin_files` VALUES(23, 'zones.php', 0, 6, '1', '1');
INSERT INTO `admin_files` VALUES(24, 'geo_zones.php', 0, 6, '1', '1');
INSERT INTO `admin_files` VALUES(25, 'tax_classes.php', 0, 6, '1', '1');
INSERT INTO `admin_files` VALUES(26, 'tax_rates.php', 0, 6, '1', '1');
INSERT INTO `admin_files` VALUES(27, 'currencies.php', 0, 7, '1', '1');
INSERT INTO `admin_files` VALUES(28, 'languages.php', 0, 7, '1', '1');
INSERT INTO `admin_files` VALUES(29, 'orders_status.php', 0, 7, '1', '1');
INSERT INTO `admin_files` VALUES(30, 'stats_products_viewed.php', 0, 8, '1', '1,4');
INSERT INTO `admin_files` VALUES(31, 'stats_products_purchased.php', 0, 8, '1', '1,4');
INSERT INTO `admin_files` VALUES(32, 'stats_customers.php', 0, 8, '1', '1,4');
INSERT INTO `admin_files` VALUES(33, 'backup.php', 0, 9, '1', '1');
INSERT INTO `admin_files` VALUES(34, 'banner_manager.php', 0, 9, '1', '1');
INSERT INTO `admin_files` VALUES(35, 'cache.php', 0, 9, '1', '1');
INSERT INTO `admin_files` VALUES(36, 'define_language.php', 0, 9, '1', '1');
INSERT INTO `admin_files` VALUES(37, 'file_manager.php', 0, 9, '1', '1');
INSERT INTO `admin_files` VALUES(38, 'mail.php', 0, 9, '1', '1');
INSERT INTO `admin_files` VALUES(39, 'newsletters.php', 0, 9, '1', '1');
INSERT INTO `admin_files` VALUES(40, 'server_info.php', 0, 9, '1', '1,4');
INSERT INTO `admin_files` VALUES(41, 'whos_online.php', 0, 9, '1', '1,4');
INSERT INTO `admin_files` VALUES(42, 'banner_statistics.php', 0, 9, '1', '1');
INSERT INTO `admin_files` VALUES(43, 'admin_members_edit.php', 0, 1, '1', '1');
INSERT INTO `admin_files` VALUES(44, 'articles.php', 1, 0, '1', '1');
INSERT INTO `admin_files` VALUES(45, 'articles.php', 0, 44, '1', '1');
INSERT INTO `admin_files` VALUES(46, 'affiliate.php', 1, 0, '1', '1');
INSERT INTO `admin_files` VALUES(47, 'general_link.php', 1, 0, '1', '1');
INSERT INTO `admin_files` VALUES(48, 'newsdesk.php', 1, 0, '1', '1');
INSERT INTO `admin_files` VALUES(49, 'newsdesk.php', 0, 48, '1', '1');
INSERT INTO `admin_files` VALUES(50, 'links.php', 1, 0, '1', '1');
INSERT INTO `admin_files` VALUES(51, 'link_manage.php', 0, 50, '1', '1');
INSERT INTO `admin_files` VALUES(52, 'links.php', 0, 50, '1', '1');

CREATE TABLE IF NOT EXISTS `admin_groups` (
  `admin_groups_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_groups_name` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`admin_groups_id`),
  UNIQUE KEY `admin_groups_name` (`admin_groups_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

INSERT INTO `admin_groups` VALUES(1, 'Top Administrator');

CREATE TABLE IF NOT EXISTS `affiliate_affiliate` (
  `affiliate_id` int(11) NOT NULL AUTO_INCREMENT,
  `affiliate_gender` char(1) NOT NULL DEFAULT '',
  `affiliate_firstname` varchar(32) NOT NULL DEFAULT '',
  `affiliate_lastname` varchar(32) NOT NULL DEFAULT '',
  `affiliate_dob` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_email_address` varchar(96) NOT NULL DEFAULT '',
  `affiliate_telephone` varchar(32) NOT NULL DEFAULT '',
  `affiliate_fax` varchar(32) NOT NULL DEFAULT '',
  `affiliate_password` varchar(40) NOT NULL DEFAULT '',
  `affiliate_homepage` varchar(96) NOT NULL DEFAULT '',
  `affiliate_street_address` varchar(64) NOT NULL DEFAULT '',
  `affiliate_suburb` varchar(64) NOT NULL DEFAULT '',
  `affiliate_city` varchar(32) NOT NULL DEFAULT '',
  `affiliate_postcode` varchar(10) NOT NULL DEFAULT '',
  `affiliate_state` varchar(32) NOT NULL DEFAULT '',
  `affiliate_country_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_zone_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_agb` tinyint(4) NOT NULL DEFAULT '0',
  `affiliate_company` varchar(60) NOT NULL DEFAULT '',
  `affiliate_company_taxid` varchar(64) NOT NULL DEFAULT '',
  `affiliate_commission_percent` decimal(4,2) NOT NULL DEFAULT '0.00',
  `affiliate_payment_check` varchar(100) NOT NULL DEFAULT '',
  `affiliate_payment_paypal` varchar(64) NOT NULL DEFAULT '',
  `affiliate_payment_bank_name` varchar(64) NOT NULL DEFAULT '',
  `affiliate_payment_bank_branch_number` varchar(64) NOT NULL DEFAULT '',
  `affiliate_payment_bank_swift_code` varchar(64) NOT NULL DEFAULT '',
  `affiliate_payment_bank_account_name` varchar(64) NOT NULL DEFAULT '',
  `affiliate_payment_bank_account_number` varchar(64) NOT NULL DEFAULT '',
  `affiliate_date_of_last_logon` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_number_of_logons` int(11) NOT NULL DEFAULT '0',
  `affiliate_date_account_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_date_account_last_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_lft` int(11) NOT NULL,
  `affiliate_rgt` int(11) NOT NULL,
  `affiliate_root` int(11) NOT NULL,
  `affiliate_newsletter` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`affiliate_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

INSERT INTO `affiliate_affiliate` VALUES(1, '', 'jason', 'phillips', '0000-00-00 00:00:00', 'adoovo@gmail.com', '12345678', '', '6467c1ff4666f37e14b45c93b7ab1253:4f', 'http://www.ebay.com', '123 test way', '', 'orlando', '32805', '', 223, 18, 1, '', '', 0.00, 'adoovo@gmail.com', 'adoovo@gmail.com', '', '', '', '', '', '0000-00-00 00:00:00', 0, '2011-10-16 01:48:21', '0000-00-00 00:00:00', 1, 2, 1, '1');

CREATE TABLE IF NOT EXISTS `affiliate_banners` (
  `affiliate_banners_id` int(11) NOT NULL AUTO_INCREMENT,
  `affiliate_banners_title` varchar(64) NOT NULL DEFAULT '',
  `affiliate_products_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_category_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_banners_image` varchar(64) NOT NULL DEFAULT '',
  `affiliate_banners_group` varchar(10) NOT NULL DEFAULT '',
  `affiliate_banners_html_text` text,
  `affiliate_expires_impressions` int(7) DEFAULT '0',
  `affiliate_expires_date` datetime DEFAULT NULL,
  `affiliate_date_scheduled` datetime DEFAULT NULL,
  `affiliate_date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_date_status_change` datetime DEFAULT NULL,
  `affiliate_status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`affiliate_banners_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `affiliate_banners_history` (
  `affiliate_banners_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `affiliate_banners_products_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_banners_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_banners_affiliate_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_banners_shown` int(11) NOT NULL DEFAULT '0',
  `affiliate_banners_clicks` tinyint(4) NOT NULL DEFAULT '0',
  `affiliate_banners_history_date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`affiliate_banners_history_id`,`affiliate_banners_products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `affiliate_clickthroughs` (
  `affiliate_clickthrough_id` int(11) NOT NULL AUTO_INCREMENT,
  `affiliate_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_clientdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_clientbrowser` varchar(200) DEFAULT 'Could Not Find This Data',
  `affiliate_clientip` varchar(50) DEFAULT 'Could Not Find This Data',
  `affiliate_clientreferer` varchar(200) DEFAULT 'none detected (maybe a direct link)',
  `affiliate_products_id` int(11) DEFAULT '0',
  `affiliate_banner_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`affiliate_clickthrough_id`),
  KEY `refid` (`affiliate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `affiliate_news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `news_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`news_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `affiliate_newsletters` (
  `affiliate_newsletters_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `module` varchar(255) NOT NULL DEFAULT '',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_sent` datetime DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `locked` int(1) DEFAULT '0',
  PRIMARY KEY (`affiliate_newsletters_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `affiliate_news_contents` (
  `affiliate_news_contents_id` int(11) NOT NULL AUTO_INCREMENT,
  `affiliate_news_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_news_languages_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_news_headlines` varchar(255) NOT NULL DEFAULT '',
  `affiliate_news_contents` text NOT NULL,
  PRIMARY KEY (`affiliate_news_contents_id`),
  KEY `affiliate_news_id` (`affiliate_news_id`),
  KEY `affiliate_news_languages_id` (`affiliate_news_languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `affiliate_payment` (
  `affiliate_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `affiliate_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_payment` decimal(15,2) NOT NULL DEFAULT '0.00',
  `affiliate_payment_tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `affiliate_payment_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `affiliate_payment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_payment_last_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_payment_status` int(5) NOT NULL DEFAULT '0',
  `affiliate_firstname` varchar(32) NOT NULL DEFAULT '',
  `affiliate_lastname` varchar(32) NOT NULL DEFAULT '',
  `affiliate_street_address` varchar(64) NOT NULL DEFAULT '',
  `affiliate_suburb` varchar(64) NOT NULL DEFAULT '',
  `affiliate_city` varchar(32) NOT NULL DEFAULT '',
  `affiliate_postcode` varchar(10) NOT NULL DEFAULT '',
  `affiliate_country` varchar(32) NOT NULL DEFAULT '0',
  `affiliate_company` varchar(60) NOT NULL DEFAULT '',
  `affiliate_state` varchar(32) NOT NULL DEFAULT '0',
  `affiliate_address_format_id` int(5) NOT NULL DEFAULT '0',
  `affiliate_last_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`affiliate_payment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `affiliate_payment_status` (
  `affiliate_payment_status_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_language_id` int(11) NOT NULL DEFAULT '1',
  `affiliate_payment_status_name` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`affiliate_payment_status_id`,`affiliate_language_id`),
  KEY `idx_affiliate_payment_status_name` (`affiliate_payment_status_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `affiliate_payment_status_history` (
  `affiliate_status_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `affiliate_payment_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_new_value` int(5) NOT NULL DEFAULT '0',
  `affiliate_old_value` int(5) DEFAULT NULL,
  `affiliate_date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_notified` int(1) DEFAULT '0',
  PRIMARY KEY (`affiliate_status_history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `affiliate_sales` (
  `affiliate_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_browser` varchar(100) NOT NULL DEFAULT '',
  `affiliate_ipaddress` varchar(20) NOT NULL DEFAULT '',
  `affiliate_orders_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `affiliate_payment` decimal(15,2) NOT NULL DEFAULT '0.00',
  `affiliate_clickthroughs_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_billing_status` int(5) NOT NULL DEFAULT '0',
  `affiliate_payment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_payment_id` int(11) NOT NULL DEFAULT '0',
  `affiliate_percent` decimal(4,2) NOT NULL DEFAULT '0.00',
  `affiliate_salesman` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`affiliate_orders_id`,`affiliate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `amazon_orders_lock` (
  `lock_key` varchar(255) NOT NULL,
  `lock_value` varchar(255) NOT NULL,
  PRIMARY KEY (`lock_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `amazon_orders_products` (
  `orders_products_id` int(11) NOT NULL,
  `products_shipping` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_shipping_tax` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_promotion_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_promotion_shipping` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_promotion_tax` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_promotion_claim_code` varchar(64) NOT NULL DEFAULT '',
  `products_promotion_merchant_promotion_id` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`orders_products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `am_attributes_to_templates` (
  `template_id` int(5) unsigned NOT NULL,
  `options_id` int(5) unsigned NOT NULL,
  `option_values_id` int(5) unsigned NOT NULL,
  `price_prefix` char(1) DEFAULT '+',
  `options_values_price` decimal(15,4) DEFAULT '0.0000',
  `products_options_sort_order` int(11) DEFAULT '0',
  `weight_prefix` char(1) DEFAULT '+',
  `options_values_weight` decimal(6,3) DEFAULT '0.000',
  `products_attributes_sort_order` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `template_id` (`template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `am_templates` (
  `template_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `template_name` varchar(255) NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `anti_robotreg` (
  `session_id` char(32) NOT NULL DEFAULT '',
  `reg_key` char(10) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `anti_robotreg` VALUES('67197b752f41a227110d1e4382e79b82', 'L5HU46', 1306511813);

CREATE TABLE IF NOT EXISTS `articles` (
  `articles_id` int(11) NOT NULL AUTO_INCREMENT,
  `articles_date_added` datetime DEFAULT '0000-00-00 00:00:00',
  `articles_last_modified` datetime DEFAULT NULL,
  `articles_date_available` datetime DEFAULT NULL,
  `articles_status` tinyint(1) NOT NULL DEFAULT '0',
  `authors_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`articles_id`),
  KEY `idx_articles_date_added` (`articles_date_added`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=139 ;

INSERT INTO `articles` VALUES(131, '2011-07-20 23:22:34', '2012-10-03 20:22:32', NULL, 1, 0);
INSERT INTO `articles` VALUES(133, '2011-09-12 18:56:42', '2011-09-12 19:02:11', NULL, 1, 0);
INSERT INTO `articles` VALUES(134, '2011-09-12 18:57:36', NULL, NULL, 1, 0);
INSERT INTO `articles` VALUES(136, '2012-06-26 00:28:24', NULL, NULL, 1, 0);
INSERT INTO `articles` VALUES(137, '2012-06-26 00:29:36', NULL, NULL, 1, 0);
INSERT INTO `articles` VALUES(138, '2012-06-26 00:41:35', '2012-06-26 00:43:30', NULL, 1, 0);

CREATE TABLE IF NOT EXISTS `articles_description` (
  `articles_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL DEFAULT '1',
  `articles_name` varchar(255) NOT NULL DEFAULT '',
  `articles_description` text,
  `articles_url` varchar(255) DEFAULT NULL,
  `articles_viewed` int(5) DEFAULT '0',
  `articles_head_title_tag` varchar(80) DEFAULT NULL,
  `articles_head_desc_tag` text,
  `articles_head_keywords_tag` text,
  PRIMARY KEY (`articles_id`,`language_id`),
  KEY `articles_name` (`articles_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=139 ;

INSERT INTO `articles_description` VALUES(131, 1, 'Conditions', '<p>\r\n	Enter your terms and conditions here. Terms and conditions can be edited in the admin under CMS / Articel Blog Manager.</p>\r\n<p>\r\n	Enter your terms and conditions here. Terms and conditions can be edited in the admin under CMS / Articel Blog Manager.</p>\r\n<p>\r\n	Enter your terms and conditions here. Terms and conditions can be edited in the admin under CMS / Articel Blog Manager.</p>\r\n<p>\r\n	Enter your terms and conditions here. Terms and conditions can be edited in the admin under CMS / Articel Blog Manager.</p>\r\n<p>\r\n	Enter your terms and conditions here. Terms and conditions can be edited in the admin under CMS / Articel Blog Manager.</p>\r\n<p>\r\n	Enter your terms and conditions here. Terms and conditions can be edited in the admin under CMS / Articel Blog Manager.</p>', '', 4, 'Conditions', '', '');
INSERT INTO `articles_description` VALUES(133, 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', '<p>\r\n	<img alt="" src="/images/cartstore_oscommerce_left.jpg" style="width: 250px; height: 204px; margin: 5px; float: left;" />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc dapibus feugiat hendrerit. Nam sed condimentum augue. Etiam vehicula congue orci, sit amet feugiat urna aliquet sed. Fusce at orci ligula, id ultrices tortor. Proin facilisis tortor nec dolor egestas ultricies. Nunc porta tincidunt porttitor. Sed tincidunt turpis dui, non feugiat sem. Suspendisse ipsum elit, vulputate sit amet pellentesque vitae, luctus ut enim. Phasellus iaculis tristique lacus, at sollicitudin massa scelerisque in. Mauris at nisi ac metus mattis egestas at sed magna.<br />\r\n	<br />\r\n	Sed egestas aliquam suscipit. Aliquam venenatis sodales dignissim. Aliquam eleifend enim ac nibh laoreet in ullamcorper urna pulvinar. Nulla porta placerat nisi, non blandit odio gravida non. Pellentesque in orci massa. Nam egestas sapien quis ligula dignissim dignissim. Nam eros tellus, accumsan bibendum bibendum ut, elementum sit amet orci. Donec nunc orci, porta in convallis vitae, vulputate molestie massa. Etiam aliquet turpis et magna laoreet ullamcorper. Nullam euismod dolor vel arcu feugiat vel accumsan nisl consequat. Donec ultricies turpis eget nisi tristique eu ultricies enim mattis. Nunc ut nulla non sapien ornare bibendum.</p>\r\n<p>\r\n	Integer adipiscing adipiscing viverra. Sed ut auctor felis. Sed id nulla ac turpis congue faucibus eget quis eros. Etiam condimentum, lacus non tempus tristique, purus velit tempus lorem, et pretium arcu eros a elit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nunc eget risus dapibus nisl vestibulum cursus. Integer ac nunc laoreet libero porttitor facilisis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;<br />\r\n	<br />\r\n	Sed est odio, elementum ac aliquet eu, lacinia at diam. Ut commodo, risus sit amet lobortis condimentum, justo lorem tincidunt quam, eleifend pretium lorem arcu quis lorem. Integer sit amet erat risus. Etiam sagittis, turpis in pharetra lobortis, nisi est suscipit purus, nec viverra erat risus vitae massa. Sed augue enim, cursus sed gravida in, faucibus nec dui. Integer vel vehicula lectus. Fusce luctus sapien ut eros porta nec feugiat dui adipiscing. Vestibulum rhoncus tempus hendrerit. Curabitur nec quam et ante consectetur bibendum. Etiam urna massa, fermentum sed mollis ac, pharetra eget lectus. Cras vulputate fermentum massa vitae molestie. Curabitur facilisis porttitor orci, ut imperdiet tortor pretium nec. Praesent at magna augue.<br />\r\n	<br />\r\n	Ut venenatis auctor leo ac rutrum. Mauris lectus elit, scelerisque vitae bibendum at, euismod ultricies nibh. Suspendisse</p>', '', 147, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', '<p>\r\n	<img alt="" src="/images/cartstore_oscommerce_left.jpg" style="width: 250px; height: 204px; margin: 5px; float: left;" />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc dapibus feugiat hendrerit. Nam sed condimentum augue. Etiam vehicula congue orci, sit amet feugiat urna aliquet sed. Fusce at orci ligula, id ultrices tortor. Proin facilisis tortor nec dolor egestas ultricies. Nunc porta tincidunt porttitor. Sed tincidunt turpis dui, non feugiat sem. Suspendisse ipsum elit, vulputate sit amet pellentesque vitae, luctus ut enim. Phasellus iaculis tristique lacus, at sollicitudin massa scelerisque in. Mauris at nisi ac metus mattis egestas at sed magna.<br />\r\n	<br />\r\n	Sed egestas aliquam suscipit. Aliquam venenatis sodales dignissim. Aliquam eleifend enim ac nibh laoreet in ullamcorper urna pulvinar. Nulla porta placerat nisi, non blandit odio gravida non. Pellentesque in orci massa. Nam egestas sapien quis ligula dignissim dignissim. Nam eros tellus, accumsan bibendum bibendum ut, elementum sit amet orci. Donec nunc orci, porta in convallis vitae, vulputate molestie massa. Etiam aliquet turpis et magna laoreet ullamcorper. Nullam euismod dolor vel arcu feugiat vel accumsan nisl consequat. Donec ultricies turpis eget nisi tristique eu ultricies enim mattis. Nunc ut nulla non sapien ornare bibendum.</p>', '');
INSERT INTO `articles_description` VALUES(134, 1, 'Vestibulum placerat accumsan ligula, et elementum enim tempor sit amet.', '<p>\r\n	<br />\r\n	Mauris bibendum ornare gravida. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut lacinia fermentum odio, sit amet volutpat arcu ullamcorper in. Proin et nunc magna, malesuada rutrum augue. Quisque lacus massa, aliquam ut condimentum quis, tristique sagittis metus. Nunc aliquam blandit egestas. In hac habitasse platea dictumst. Nullam ut metus odio, sit amet hendrerit enim.<br />\r\n	<br />\r\n	Vivamus ut imperdiet nunc. Nulla neque velit, lobortis sit amet faucibus eget, ullamcorper non dolor. Aliquam purus purus, volutpat facilisis tristique a, sollicitudin sit amet lorem. Fusce pharetra sollicitudin fringilla. Donec nibh nibh, dictum eget sodales condimentum, congue vitae odio. Praesent nisi sapien, convallis a malesuada sed, aliquam vel est. Praesent lobortis rhoncus nisi, vel cursus arcu bibendum at. Cras mattis fermentum lacus a tincidunt. Duis quis dui magna, ut semper massa. Vivamus at sapien erat. Cras porta est eu urna porttitor nec euismod arcu porttitor. Duis lectus est, congue et mollis a, condimentum non nulla. Vivamus mi augue, sollicitudin non commodo eget, pellentesque vitae ante.<br />\r\n	<br />\r\n	Pellentesque lacinia, dui quis semper fermentum, nisi felis blandit ante, et convallis lorem sem a urna. In ut dolor massa. Morbi sapien nisi, luctus id elementum eu, egestas eu felis. Cras elementum, augue at molestie euismod, dui dolor porttitor nibh, nec laoreet tortor neque quis tellus. Maecenas ac dui sed nisi tempor congue. Suspendisse potenti. Quisque luctus</p>', '', 136, '', '<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut tristique neque et nunc facilisis porta. Sed est ipsum, ultrices at aliquam vel, eleifend nec tortor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum a neque est. Etiam eu urna in neque adipiscing porta. Donec laoreet varius velit at convallis. Ut a tortor ut sapien convallis rhoncus. Sed bibendum, nulla id dignissim bibendum, urna nulla auctor sapien, nec fermentum eros arcu nec tellus. Aenean est ipsum, venenatis nec aliquam sed, ullamcorper vitae quam. Ut luctus bibendum est vel euismod. Morbi euismod scelerisque lorem, nec ornare nulla cursus eu.<br />\r\n	<br />\r\n	Vestibulum placerat accumsan ligula, et elementum enim tempor sit amet. Integer id nulla a tellus commodo viverra. Mauris dapibus placerat lectus, vel volutpat purus placerat quis. Vivamus ac justo a sapien adipiscing bibendum in id nibh. Integer nec molestie est. Mauris lacinia mollis erat at pellentesque. Sed vitae dapibus nisi. Nunc adipiscing vehicula est, ac placerat quam gravida a. Nulla ut auctor augue. Quisque blandit libero sed dui semper a iaculis sapien ullamcorper. Praesent pulvinar justo risus. Nulla vehicula mauris purus, et imperdiet mi. Sed id turpis eu augue commodo luctus.</p>', '');
INSERT INTO `articles_description` VALUES(136, 1, 'Shipping & Returns', '<table border="0" cellpadding="5" cellspacing="1" width="100%">\r\n	<tbody>\r\n		<tr>\r\n			<td>\r\n				<p align="center">\r\n					<strong>Purchase Price </strong></p>\r\n			</td>\r\n			<td>\r\n				<p align="center">\r\n					<strong>UPS </strong></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				<p align="center">\r\n					Purchase Price of items</p>\r\n			</td>\r\n			<td>\r\n				<p align="center">\r\n					UPS Actual</p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				<p align="center">\r\n					Please see our FAQ section for Returns</p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<p>\r\n	&nbsp;</p>', '', 0, 'Shipping & Returns', '', '');
INSERT INTO `articles_description` VALUES(137, 1, 'Privacy Notice', '<p>\r\n	We consider the protection of your privacy one of our main commitments. We use information collected about you to personalize the services we offer and to expedite the order process.</p>\r\n<p>\r\n	<strong>Information Collection and Use </strong></p>\r\n<p>\r\n	We collect information from our users at several different points on our website. We will not sell or rent this information to others in ways different from what is disclosed in this statement. We may also release account information when we believe, in good faith, that such release is necessary to (a) comply with law, (b) enforce or apply the terms of any of our user agreements or (c) protect the rights, property or safety of our users or others.</p>\r\n<p>\r\n	<strong>Order </strong></p>\r\n<p>\r\n	We request information from the user on our order form. Here a user must provide contact information (like name and shipping address) and financial information (like credit card number, expiration date). This information is used for billing purposes and to fill customer&rsquo;s orders. If we have trouble processing an order, this contact information is used to get in touch with the user.</p>\r\n<p>\r\n	<strong>Cookies </strong></p>\r\n<p>\r\n	A cookie is a piece of data stored on the user&rsquo;s hard drive containing information about the user. Most Web browsers automatically accept cookies, but you can change your browser to prevent that. Even without a cookie, you can still use most of the features on our site.</p>\r\n<p>\r\n	<strong>Sharing </strong></p>\r\n<p>\r\n	We may sometimes share demographic and user information with our partners. This is not linked to any personal information that can identify any individual person. We use an outside shipping company to ship orders, and a credit card processing company to bill users for goods and services. These companies do not retain, share, store or use personally identifiable information for any secondary purposes. We may also partner with other parties to provide specific services. When the user signs up for these services, we will share names, or other contact information that is necessary for the third party to provide these services. These parties are not allowed to use personally identifiable information except for the purpose of providing these services.</p>\r\n<p>\r\n	<strong>Links </strong></p>\r\n<p>\r\n	This web site may contain links to other sites. Please be aware that we are not responsible for the privacy practices of such other sites. We encourage our users to be aware when they leave our site and to read the privacy statements of each and every web site that collects personally identifiable information. This privacy statement applies solely to information collected by this website.</p>\r\n<p>\r\n	<strong>Security </strong></p>\r\n<p>\r\n	This website takes every precaution to protect our user&rsquo;s information. When users submit sensitive information via the website, your information is protected both online and off-line. When our registration/order form asks users to enter sensitive information (such as credit card number and/or social security number), that information is encrypted and is protected with the best encryption software in the industry &ndash; Secure Socket Layer or SSL, in short.</p>\r\n<p>\r\n	<strong>Special Offers </strong></p>\r\n<p>\r\n	We send all new members a welcoming email to verify username. Established members will occasionally receive information on products, services, special deals, and a newsletter. Out of respect for the privacy of our users we present the option to not receive these types of communications. Please see our choice and opt-out below.</p>\r\n<p>\r\n	<strong>Site and Service Updates </strong></p>\r\n<p>\r\n	We may also send the user site and service announcement updates. Members may not able to un-subscribe from service announcements, which contain important information about the service. We communicate with the user to provide requested services and in regards to issues relating to their account via email or phone.</p>\r\n<p>\r\n	<strong>Correction/Updating Personal Information </strong></p>\r\n<p>\r\n	If a user&rsquo;s personally identifiable information changes (such as your address), or if a user no longer desires our service, we will endeavor to provide a way to correct, update or remove that user&rsquo;s personal data provided to us. This can usually be done at the appropriate member information page.</p>\r\n<p>\r\n	<strong>Notification of Changes </strong></p>\r\n<p>\r\n	If we decide to change our privacy policy, we will post those changes on this website so our users are always aware of what information we collect, how we use it, and under what circumstances, if any, we disclose it. If at any point we decide to use personally identifiable information in a manner different from that stated at the time it was collected, we will notify users. Users will have a choice as to whether or not we use their information in this different manner. We will use information in accordance with the privacy policy under which the information was collected.</p>', '', 0, 'Privacy Notice', '', '');
INSERT INTO `articles_description` VALUES(138, 1, 'Conditions of Use', '<p>\r\n	Welcome to the internet site of Kamdarplaza (Site). Please review these Site terms of use, which govern your use of, and purchase of products from our Site. By accessing, browsing, or using this Site, you acknowledge that you have read, understand, and agree to be bound by these terms. If you do not agree to these terms then please do not use this Site.</p>\r\n<p>\r\n	We control and operate this Site from its offices within the United States . Claims relating to, including the use of, this Site and the materials contained herein are governed by the laws of the United States . If you do not agree, please do not use this Site. If you choose to access this Site from another location, you do so on your own initiative and are responsible for compliance with applicable local laws.</p>\r\n<p>\r\n	<strong>DISCLAIMER OF WARRANTY </strong></p>\r\n<p>\r\n	Kamdarplaza is providing this site and its contents on an &quot;as is&quot; basis and makes no representations or warranties of any kind, either express or implied, including, without limitation, warranties or conditions of title or implied warranties of merchantability or fitness for a particular purpose, and non-infringement. Although Kamdarplaza believes the content to be accurate, complete, and current, Kamdarplaza does not represent or warrant that the information accessible on this site is accurate, complete, or current. Price and availability information is subject to change without notice.</p>\r\n<p>\r\n	<strong>DISCLAIMER OF LIABILITY </strong></p>\r\n<p>\r\n	In no event shall Kamdarplaza be liable for special, indirect, exemplary, or consequential damages or any damages whatsoever, including but not limited to, loss of use, data, or profits, without regard to the form of any action, including but not limited to contract, negligence, or other tortuous actions, all arising out of or in connection with the use, copying, or display of the contents of this site. In an effort to provide our customers with the most current information, Kamdarplaza will, from time to time, make changes in the Contents and in the products or services described on this Site.</p>\r\n<p>\r\n	<strong>ABOUT OUR PRICES </strong></p>\r\n<p>\r\n	The prices advertised on this Site are for Internet orders. Prices on some items may differ from those charged at Kamdarplaza store. Prices and the availability of items are subject to change without notice. Any &quot;list prices&quot; used on this Site are the manufacturers&#39; suggested retail prices and may not be indicative of the actual selling prices in your area.</p>\r\n<p>\r\n	Any reference to &quot;savings&quot; used on this Site indicates the average savings off the &quot;list price&quot;. Your actual savings will vary depending upon the goods purchased and the date of the transaction.</p>\r\n<p>\r\n	We reserve the right to limit sales, including the right to prohibit sales to re-sellers. We are not responsible for typographical or photographic errors.</p>\r\n<p>\r\n	<strong>TERMS OF USE REVISIONS </strong></p>\r\n<p>\r\n	Kamdarplaza may revise these terms of use by updating this posting. You agree that in the event any portion of these Site terms of use are found to be unenforceable, the remainder of these Site terms and conditions shall remain in full force and effect. By using this Site you agree to be bound by any such revisions and should therefore periodically visit this page to determine the then current terms of use to which you are bound.</p>', '', 4, 'Conditions of Use', '', '');

CREATE TABLE IF NOT EXISTS `articles_to_topics` (
  `articles_id` int(11) NOT NULL DEFAULT '0',
  `topics_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`articles_id`,`topics_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `articles_to_topics` VALUES(131, 0);
INSERT INTO `articles_to_topics` VALUES(133, 24);
INSERT INTO `articles_to_topics` VALUES(134, 24);
INSERT INTO `articles_to_topics` VALUES(136, 0);
INSERT INTO `articles_to_topics` VALUES(137, 0);
INSERT INTO `articles_to_topics` VALUES(138, 0);

CREATE TABLE IF NOT EXISTS `articles_xsell` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `articles_id` int(10) unsigned NOT NULL DEFAULT '1',
  `xsell_id` int(10) unsigned NOT NULL DEFAULT '1',
  `sort_order` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS `article_reviews` (
  `reviews_id` int(11) NOT NULL AUTO_INCREMENT,
  `articles_id` int(11) NOT NULL DEFAULT '0',
  `customers_id` int(11) DEFAULT NULL,
  `customers_name` varchar(64) NOT NULL DEFAULT '',
  `reviews_rating` int(1) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `reviews_read` int(5) NOT NULL DEFAULT '0',
  `approved` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`reviews_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `article_reviews_description` (
  `reviews_id` int(11) NOT NULL DEFAULT '0',
  `languages_id` int(11) NOT NULL DEFAULT '0',
  `reviews_text` text NOT NULL,
  PRIMARY KEY (`reviews_id`,`languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `authors` (
  `authors_id` int(11) NOT NULL AUTO_INCREMENT,
  `authors_name` varchar(32) NOT NULL DEFAULT '',
  `authors_image` varchar(64) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`authors_id`),
  KEY `IDX_AUTHORS_NAME` (`authors_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `authors_info` (
  `authors_id` int(11) NOT NULL DEFAULT '0',
  `languages_id` int(11) NOT NULL DEFAULT '0',
  `authors_description` text,
  `authors_url` varchar(255) NOT NULL DEFAULT '',
  `url_clicked` int(5) NOT NULL DEFAULT '0',
  `date_last_click` datetime DEFAULT NULL,
  PRIMARY KEY (`authors_id`,`languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `banned_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL,
  `ip_status` int(1) NOT NULL DEFAULT '0',
  `reason` tinytext,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Banned IP addresses that are not allowed to access website' AUTO_INCREMENT=22 ;

CREATE TABLE IF NOT EXISTS `banners` (
  `banners_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL DEFAULT '1',
  `banners_title` varchar(64) NOT NULL DEFAULT '',
  `banners_url` varchar(255) NOT NULL DEFAULT '',
  `banners_image` varchar(64) NOT NULL DEFAULT '',
  `banners_group` varchar(10) NOT NULL DEFAULT '',
  `banners_html_text` text,
  `expires_impressions` int(7) DEFAULT '0',
  `expires_date` datetime DEFAULT NULL,
  `date_scheduled` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_status_change` datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`banners_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `banners` VALUES(1, 1, 'CartStore', '', '', 'specials', '<div class="modulelist">\r\n  <div class="module-cart">\r\n    <div>\r\n      <div>\r\n        <div>\r\n          <div class="description">\r\n            <h3>Welcome to CartStore 5            </h3>\r\n            <p>CartStore is a very unique software product unlike any other  in the world. CartStore brings in new features at a exponential rate at the direction  of its clients, CartStore clients are the actual ones building the features  found in CartStore to date therefore producing a very useful and functional  product as the actual needs of thousands of store owners are actually building  it.</p>\r\n<p>\r\n  CartStore is else very unique in that it is the world&rsquo;s most  secure shopping cart software. CartStore has built in intrusion detection technology  and protects its self not only from known attacks but it also uses heuristic  detection technology. In regards to web security CartStore doesn&rsquo;t just stop at  its intrusion defense capabilities but it also incorporates other distinct  security systems such as file change monitoring and will alert you in such case.</p>\r\n<p>\r\n  CartStore is an incredibly robust shopping cart technology  built on the osCommerce framework it incorporates over 200 of the most popular osCommerce  add-ons plus 8 years of customizations by StoreCoders programmers that you will  only find in CartStore. CartStore maintains excellent compatibility with available  osCommerce extension both for 2.2 and 2.3 and its add-ons are mostly taken from  the osCommerce extensions site. If you were to attempt to replicate what we  have done it would take over 1 million dollars in programmer hours and many  years. </p>\r\n            <div class="clear"></div>\r\n            <br>\r\n            <a class="request" href="article_info.php?articles_id=135">Learn More</a>\r\n            <div class="clear"></div>\r\n            <br>\r\n          </div>\r\n          <div class="title"> Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s. </div>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>', 0, NULL, NULL, '2011-08-17 15:20:25', NULL, 1, 0);

CREATE TABLE IF NOT EXISTS `banners_history` (
  `banners_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `banners_id` int(11) NOT NULL DEFAULT '0',
  `banners_shown` int(5) NOT NULL DEFAULT '0',
  `banners_clicked` int(5) NOT NULL DEFAULT '0',
  `banners_history_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`banners_history_id`),
  KEY `banners_id` (`banners_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=319 ;

INSERT INTO `banners_history` VALUES(1, 1, 33, 0, '2011-08-17 15:20:30');
INSERT INTO `banners_history` VALUES(2, 1, 14, 0, '2011-08-18 04:56:30');
INSERT INTO `banners_history` VALUES(3, 1, 2, 0, '2011-08-19 06:06:16');
INSERT INTO `banners_history` VALUES(4, 1, 1, 0, '2011-08-25 02:24:15');
INSERT INTO `banners_history` VALUES(5, 1, 1, 0, '2011-08-27 00:13:04');
INSERT INTO `banners_history` VALUES(6, 1, 1, 0, '2011-08-28 13:35:51');
INSERT INTO `banners_history` VALUES(7, 1, 2, 0, '2011-08-31 01:54:24');
INSERT INTO `banners_history` VALUES(8, 1, 1, 0, '2011-09-04 00:04:25');
INSERT INTO `banners_history` VALUES(9, 1, 5, 0, '2011-09-07 19:16:44');
INSERT INTO `banners_history` VALUES(10, 1, 4, 0, '2011-09-08 00:28:45');
INSERT INTO `banners_history` VALUES(11, 1, 34, 0, '2011-09-12 18:30:06');
INSERT INTO `banners_history` VALUES(12, 1, 2, 0, '2011-09-13 04:09:20');
INSERT INTO `banners_history` VALUES(13, 1, 9, 0, '2011-09-14 14:21:29');
INSERT INTO `banners_history` VALUES(14, 1, 5, 0, '2011-09-15 18:28:36');
INSERT INTO `banners_history` VALUES(15, 1, 1, 0, '2011-09-16 04:40:37');
INSERT INTO `banners_history` VALUES(16, 1, 2, 0, '2011-09-17 10:52:05');
INSERT INTO `banners_history` VALUES(17, 1, 3, 0, '2011-09-19 00:57:26');
INSERT INTO `banners_history` VALUES(18, 1, 3, 0, '2011-09-20 19:36:51');
INSERT INTO `banners_history` VALUES(19, 1, 2, 0, '2011-09-23 07:44:15');
INSERT INTO `banners_history` VALUES(20, 1, 2, 0, '2011-09-26 18:17:03');
INSERT INTO `banners_history` VALUES(21, 1, 5, 0, '2011-09-28 03:37:20');
INSERT INTO `banners_history` VALUES(22, 1, 2, 0, '2011-09-29 11:10:40');
INSERT INTO `banners_history` VALUES(23, 1, 1, 0, '2011-09-30 01:14:32');
INSERT INTO `banners_history` VALUES(24, 1, 5, 0, '2011-10-02 00:34:00');
INSERT INTO `banners_history` VALUES(25, 1, 7, 0, '2011-10-03 07:18:16');
INSERT INTO `banners_history` VALUES(26, 1, 6, 0, '2011-10-04 02:53:03');
INSERT INTO `banners_history` VALUES(27, 1, 4, 0, '2011-10-05 05:46:16');
INSERT INTO `banners_history` VALUES(28, 1, 1, 0, '2011-10-06 09:56:32');
INSERT INTO `banners_history` VALUES(29, 1, 2, 0, '2011-10-07 06:15:06');
INSERT INTO `banners_history` VALUES(30, 1, 1, 0, '2011-10-08 23:39:42');
INSERT INTO `banners_history` VALUES(31, 1, 28, 0, '2011-10-09 02:51:13');
INSERT INTO `banners_history` VALUES(32, 1, 8, 0, '2011-10-10 03:09:27');
INSERT INTO `banners_history` VALUES(33, 1, 2, 0, '2011-10-11 00:15:00');
INSERT INTO `banners_history` VALUES(34, 1, 2, 0, '2011-10-12 00:47:34');
INSERT INTO `banners_history` VALUES(35, 1, 1, 0, '2011-10-13 00:57:09');
INSERT INTO `banners_history` VALUES(36, 1, 1, 0, '2011-10-15 02:46:44');
INSERT INTO `banners_history` VALUES(37, 1, 3, 0, '2011-10-16 01:16:33');
INSERT INTO `banners_history` VALUES(38, 1, 1, 0, '2011-10-17 20:11:37');
INSERT INTO `banners_history` VALUES(39, 1, 1, 0, '2011-10-18 00:38:41');
INSERT INTO `banners_history` VALUES(40, 1, 4, 0, '2011-10-21 02:20:28');
INSERT INTO `banners_history` VALUES(41, 1, 3, 0, '2011-10-24 00:44:54');
INSERT INTO `banners_history` VALUES(42, 1, 2, 0, '2011-10-25 02:56:40');
INSERT INTO `banners_history` VALUES(43, 1, 5, 0, '2011-10-26 03:53:13');
INSERT INTO `banners_history` VALUES(44, 1, 3, 0, '2011-10-28 23:11:48');
INSERT INTO `banners_history` VALUES(45, 1, 2, 0, '2011-11-01 16:59:32');
INSERT INTO `banners_history` VALUES(46, 1, 2, 0, '2011-11-02 15:57:22');
INSERT INTO `banners_history` VALUES(47, 1, 6, 0, '2011-11-03 00:22:48');
INSERT INTO `banners_history` VALUES(48, 1, 4, 0, '2011-11-04 12:34:01');
INSERT INTO `banners_history` VALUES(49, 1, 1, 0, '2011-11-06 05:26:49');
INSERT INTO `banners_history` VALUES(50, 1, 3, 0, '2011-11-07 15:38:33');
INSERT INTO `banners_history` VALUES(51, 1, 2, 0, '2011-11-08 14:53:51');
INSERT INTO `banners_history` VALUES(52, 1, 2, 0, '2011-11-09 02:11:30');
INSERT INTO `banners_history` VALUES(53, 1, 1, 0, '2011-11-10 05:12:59');
INSERT INTO `banners_history` VALUES(54, 1, 2, 0, '2011-11-11 10:13:33');
INSERT INTO `banners_history` VALUES(55, 1, 2, 0, '2011-11-12 00:33:33');
INSERT INTO `banners_history` VALUES(56, 1, 1, 0, '2011-11-14 01:52:50');
INSERT INTO `banners_history` VALUES(57, 1, 16, 0, '2011-11-16 01:13:32');
INSERT INTO `banners_history` VALUES(58, 1, 1, 0, '2011-11-17 14:48:59');
INSERT INTO `banners_history` VALUES(59, 1, 1, 0, '2011-11-18 21:26:22');
INSERT INTO `banners_history` VALUES(60, 1, 1, 0, '2011-11-19 20:54:53');
INSERT INTO `banners_history` VALUES(61, 1, 3, 0, '2011-11-21 13:16:23');
INSERT INTO `banners_history` VALUES(62, 1, 1, 0, '2011-11-22 00:17:33');
INSERT INTO `banners_history` VALUES(63, 1, 3, 0, '2011-11-23 19:22:26');
INSERT INTO `banners_history` VALUES(64, 1, 3, 0, '2011-11-24 04:04:40');
INSERT INTO `banners_history` VALUES(65, 1, 10, 0, '2011-11-25 11:33:05');
INSERT INTO `banners_history` VALUES(66, 1, 1, 0, '2011-11-26 12:25:47');
INSERT INTO `banners_history` VALUES(67, 1, 4, 0, '2011-11-28 15:18:57');
INSERT INTO `banners_history` VALUES(68, 1, 5, 0, '2011-11-30 16:37:24');
INSERT INTO `banners_history` VALUES(69, 1, 3, 0, '2011-12-01 15:58:06');
INSERT INTO `banners_history` VALUES(70, 1, 1, 0, '2011-12-04 00:01:39');
INSERT INTO `banners_history` VALUES(71, 1, 7, 0, '2011-12-05 16:22:42');
INSERT INTO `banners_history` VALUES(72, 1, 37, 0, '2011-12-06 00:02:10');
INSERT INTO `banners_history` VALUES(73, 1, 1, 0, '2011-12-07 00:29:11');
INSERT INTO `banners_history` VALUES(74, 1, 3, 0, '2011-12-08 17:54:55');
INSERT INTO `banners_history` VALUES(75, 1, 1, 0, '2011-12-10 09:41:46');
INSERT INTO `banners_history` VALUES(76, 1, 2, 0, '2011-12-12 18:49:04');
INSERT INTO `banners_history` VALUES(77, 1, 2, 0, '2011-12-13 06:45:41');
INSERT INTO `banners_history` VALUES(78, 1, 3, 0, '2011-12-15 00:50:26');
INSERT INTO `banners_history` VALUES(79, 1, 3, 0, '2011-12-17 02:42:18');
INSERT INTO `banners_history` VALUES(80, 1, 1, 0, '2011-12-20 10:51:44');
INSERT INTO `banners_history` VALUES(81, 1, 2, 0, '2011-12-21 22:47:24');
INSERT INTO `banners_history` VALUES(82, 1, 11, 0, '2011-12-22 09:55:29');
INSERT INTO `banners_history` VALUES(83, 1, 1, 0, '2011-12-23 05:38:43');
INSERT INTO `banners_history` VALUES(84, 1, 1, 0, '2011-12-24 02:43:47');
INSERT INTO `banners_history` VALUES(85, 1, 1, 0, '2011-12-25 00:44:10');
INSERT INTO `banners_history` VALUES(86, 1, 2, 0, '2011-12-27 00:02:01');
INSERT INTO `banners_history` VALUES(87, 1, 10, 0, '2011-12-29 18:36:06');
INSERT INTO `banners_history` VALUES(88, 1, 3, 0, '2011-12-30 19:28:34');
INSERT INTO `banners_history` VALUES(89, 1, 25, 0, '2011-12-31 00:46:50');
INSERT INTO `banners_history` VALUES(90, 1, 9, 0, '2012-01-01 00:18:13');
INSERT INTO `banners_history` VALUES(91, 1, 4, 0, '2012-01-02 11:50:07');
INSERT INTO `banners_history` VALUES(92, 1, 2, 0, '2012-01-03 16:29:25');
INSERT INTO `banners_history` VALUES(93, 1, 3, 0, '2012-01-04 02:50:42');
INSERT INTO `banners_history` VALUES(94, 1, 4, 0, '2012-01-05 01:24:27');
INSERT INTO `banners_history` VALUES(95, 1, 1, 0, '2012-01-08 20:28:03');
INSERT INTO `banners_history` VALUES(96, 1, 5, 0, '2012-01-09 01:52:43');
INSERT INTO `banners_history` VALUES(97, 1, 86, 0, '2012-01-10 03:35:16');
INSERT INTO `banners_history` VALUES(98, 1, 15, 0, '2012-01-11 04:46:52');
INSERT INTO `banners_history` VALUES(99, 1, 9, 0, '2012-01-12 01:29:46');
INSERT INTO `banners_history` VALUES(100, 1, 5, 0, '2012-01-16 20:39:16');
INSERT INTO `banners_history` VALUES(101, 1, 2, 0, '2012-01-17 00:19:56');
INSERT INTO `banners_history` VALUES(102, 1, 2, 0, '2012-01-18 20:25:07');
INSERT INTO `banners_history` VALUES(103, 1, 6, 0, '2012-01-19 02:39:15');
INSERT INTO `banners_history` VALUES(104, 1, 2, 0, '2012-01-20 12:54:13');
INSERT INTO `banners_history` VALUES(105, 1, 4, 0, '2012-01-21 11:14:38');
INSERT INTO `banners_history` VALUES(106, 1, 71, 0, '2012-01-22 00:25:11');
INSERT INTO `banners_history` VALUES(107, 1, 2, 0, '2012-01-23 21:22:05');
INSERT INTO `banners_history` VALUES(108, 1, 9, 0, '2012-01-24 00:30:14');
INSERT INTO `banners_history` VALUES(109, 1, 6, 0, '2012-01-25 01:36:30');
INSERT INTO `banners_history` VALUES(110, 1, 5, 0, '2012-01-26 08:19:12');
INSERT INTO `banners_history` VALUES(111, 1, 11, 0, '2012-01-27 00:35:56');
INSERT INTO `banners_history` VALUES(112, 1, 5, 0, '2012-01-28 20:41:29');
INSERT INTO `banners_history` VALUES(113, 1, 1, 0, '2012-01-29 00:12:07');
INSERT INTO `banners_history` VALUES(114, 1, 3, 0, '2012-01-30 01:33:30');
INSERT INTO `banners_history` VALUES(115, 1, 22, 0, '2012-01-31 08:26:19');
INSERT INTO `banners_history` VALUES(116, 1, 23, 0, '2012-02-01 00:15:39');
INSERT INTO `banners_history` VALUES(117, 1, 3, 0, '2012-02-02 04:06:01');
INSERT INTO `banners_history` VALUES(118, 1, 1, 0, '2012-02-03 02:33:20');
INSERT INTO `banners_history` VALUES(119, 1, 2, 0, '2012-02-05 04:19:26');
INSERT INTO `banners_history` VALUES(120, 1, 1, 0, '2012-02-06 22:19:18');
INSERT INTO `banners_history` VALUES(121, 1, 2, 0, '2012-02-07 03:50:29');
INSERT INTO `banners_history` VALUES(122, 1, 1, 0, '2012-02-08 11:55:40');
INSERT INTO `banners_history` VALUES(123, 1, 2, 0, '2012-02-09 08:09:02');
INSERT INTO `banners_history` VALUES(124, 1, 1, 0, '2012-02-11 08:21:49');
INSERT INTO `banners_history` VALUES(125, 1, 3, 0, '2012-02-13 16:31:30');
INSERT INTO `banners_history` VALUES(126, 1, 3, 0, '2012-02-14 00:21:02');
INSERT INTO `banners_history` VALUES(127, 1, 1, 0, '2012-02-15 16:12:20');
INSERT INTO `banners_history` VALUES(128, 1, 2, 0, '2012-02-16 18:05:05');
INSERT INTO `banners_history` VALUES(129, 1, 17, 0, '2012-02-17 02:56:19');
INSERT INTO `banners_history` VALUES(130, 1, 9, 0, '2012-02-18 01:57:12');
INSERT INTO `banners_history` VALUES(131, 1, 20, 0, '2012-02-20 04:06:07');
INSERT INTO `banners_history` VALUES(132, 1, 11, 0, '2012-02-21 12:19:12');
INSERT INTO `banners_history` VALUES(133, 1, 18, 0, '2012-02-22 00:21:07');
INSERT INTO `banners_history` VALUES(134, 1, 2, 0, '2012-02-23 23:09:28');
INSERT INTO `banners_history` VALUES(135, 1, 2, 0, '2012-02-24 01:14:37');
INSERT INTO `banners_history` VALUES(136, 1, 2, 0, '2012-02-26 18:41:24');
INSERT INTO `banners_history` VALUES(137, 1, 1, 0, '2012-02-27 03:25:02');
INSERT INTO `banners_history` VALUES(138, 1, 2, 0, '2012-02-28 16:26:06');
INSERT INTO `banners_history` VALUES(139, 1, 3, 0, '2012-03-04 15:01:04');
INSERT INTO `banners_history` VALUES(140, 1, 2, 0, '2012-03-07 21:20:38');
INSERT INTO `banners_history` VALUES(141, 1, 1, 0, '2012-03-08 11:41:13');
INSERT INTO `banners_history` VALUES(142, 1, 5, 0, '2012-03-13 02:40:46');
INSERT INTO `banners_history` VALUES(143, 1, 1, 0, '2012-03-15 15:49:23');
INSERT INTO `banners_history` VALUES(144, 1, 3, 0, '2012-03-16 01:51:14');
INSERT INTO `banners_history` VALUES(145, 1, 3, 0, '2012-03-17 01:15:20');
INSERT INTO `banners_history` VALUES(146, 1, 4, 0, '2012-03-18 02:22:12');
INSERT INTO `banners_history` VALUES(147, 1, 5, 0, '2012-03-19 02:03:46');
INSERT INTO `banners_history` VALUES(148, 1, 3, 0, '2012-03-20 04:46:24');
INSERT INTO `banners_history` VALUES(149, 1, 3, 0, '2012-03-21 05:22:51');
INSERT INTO `banners_history` VALUES(150, 1, 4, 0, '2012-03-22 02:43:31');
INSERT INTO `banners_history` VALUES(151, 1, 1, 0, '2012-03-23 21:55:05');
INSERT INTO `banners_history` VALUES(152, 1, 1, 0, '2012-03-24 12:19:08');
INSERT INTO `banners_history` VALUES(153, 1, 2, 0, '2012-03-26 12:10:47');
INSERT INTO `banners_history` VALUES(154, 1, 17, 0, '2012-03-27 17:57:59');
INSERT INTO `banners_history` VALUES(155, 1, 1, 0, '2012-03-29 23:37:08');
INSERT INTO `banners_history` VALUES(156, 1, 1, 0, '2012-03-30 22:19:03');
INSERT INTO `banners_history` VALUES(157, 1, 4, 0, '2012-04-01 10:55:57');
INSERT INTO `banners_history` VALUES(158, 1, 3, 0, '2012-04-02 15:48:58');
INSERT INTO `banners_history` VALUES(159, 1, 4, 0, '2012-04-03 00:45:43');
INSERT INTO `banners_history` VALUES(160, 1, 2, 0, '2012-04-04 01:28:33');
INSERT INTO `banners_history` VALUES(161, 1, 4, 0, '2012-04-15 20:31:19');
INSERT INTO `banners_history` VALUES(162, 1, 22, 0, '2012-04-17 17:52:34');
INSERT INTO `banners_history` VALUES(163, 1, 1, 0, '2012-04-18 15:03:33');
INSERT INTO `banners_history` VALUES(164, 1, 1, 0, '2012-04-19 00:17:04');
INSERT INTO `banners_history` VALUES(165, 1, 5, 0, '2012-04-20 01:40:15');
INSERT INTO `banners_history` VALUES(166, 1, 9, 0, '2012-04-21 07:12:45');
INSERT INTO `banners_history` VALUES(167, 1, 6, 0, '2012-04-22 02:23:18');
INSERT INTO `banners_history` VALUES(168, 1, 5, 0, '2012-04-23 11:37:21');
INSERT INTO `banners_history` VALUES(169, 1, 5, 0, '2012-04-25 05:11:27');
INSERT INTO `banners_history` VALUES(170, 1, 5, 0, '2012-04-26 15:50:03');
INSERT INTO `banners_history` VALUES(171, 1, 1, 0, '2012-04-28 11:53:25');
INSERT INTO `banners_history` VALUES(172, 1, 4, 0, '2012-04-29 09:38:57');
INSERT INTO `banners_history` VALUES(173, 1, 4, 0, '2012-04-30 18:45:13');
INSERT INTO `banners_history` VALUES(174, 1, 2, 0, '2012-05-01 19:14:55');
INSERT INTO `banners_history` VALUES(175, 1, 28, 0, '2012-05-02 16:01:16');
INSERT INTO `banners_history` VALUES(176, 1, 5, 0, '2012-05-03 00:21:23');
INSERT INTO `banners_history` VALUES(177, 1, 4, 0, '2012-05-04 06:18:12');
INSERT INTO `banners_history` VALUES(178, 1, 2, 0, '2012-05-05 05:57:39');
INSERT INTO `banners_history` VALUES(179, 1, 3, 0, '2012-05-06 02:59:00');
INSERT INTO `banners_history` VALUES(180, 1, 4, 0, '2012-05-07 00:31:41');
INSERT INTO `banners_history` VALUES(181, 1, 1, 0, '2012-05-09 21:31:02');
INSERT INTO `banners_history` VALUES(182, 1, 1, 0, '2012-05-12 09:13:29');
INSERT INTO `banners_history` VALUES(183, 1, 1, 0, '2012-05-14 23:04:45');
INSERT INTO `banners_history` VALUES(184, 1, 2, 0, '2012-05-16 00:30:14');
INSERT INTO `banners_history` VALUES(185, 1, 1, 0, '2012-05-17 06:25:56');
INSERT INTO `banners_history` VALUES(186, 1, 1, 0, '2012-05-19 02:14:53');
INSERT INTO `banners_history` VALUES(187, 1, 3, 0, '2012-05-21 04:02:57');
INSERT INTO `banners_history` VALUES(188, 1, 3, 0, '2012-05-22 19:53:02');
INSERT INTO `banners_history` VALUES(189, 1, 1, 0, '2012-05-24 15:03:43');
INSERT INTO `banners_history` VALUES(190, 1, 4, 0, '2012-05-25 19:18:04');
INSERT INTO `banners_history` VALUES(191, 1, 2, 0, '2012-05-26 13:13:33');
INSERT INTO `banners_history` VALUES(192, 1, 1, 0, '2012-05-27 02:31:21');
INSERT INTO `banners_history` VALUES(193, 1, 1, 0, '2012-05-28 16:29:36');
INSERT INTO `banners_history` VALUES(194, 1, 5, 0, '2012-05-29 05:40:33');
INSERT INTO `banners_history` VALUES(195, 1, 3, 0, '2012-05-30 08:03:21');
INSERT INTO `banners_history` VALUES(196, 1, 2, 0, '2012-05-31 08:56:26');
INSERT INTO `banners_history` VALUES(197, 1, 2, 0, '2012-06-01 10:22:59');
INSERT INTO `banners_history` VALUES(198, 1, 7, 0, '2012-06-03 00:09:51');
INSERT INTO `banners_history` VALUES(199, 1, 1, 0, '2012-06-04 06:23:08');
INSERT INTO `banners_history` VALUES(200, 1, 5, 0, '2012-06-05 04:49:21');
INSERT INTO `banners_history` VALUES(201, 1, 5, 0, '2012-06-06 11:16:15');
INSERT INTO `banners_history` VALUES(202, 1, 2, 0, '2012-06-07 14:44:57');
INSERT INTO `banners_history` VALUES(203, 1, 5, 0, '2012-06-09 02:22:16');
INSERT INTO `banners_history` VALUES(204, 1, 2, 0, '2012-06-10 20:26:44');
INSERT INTO `banners_history` VALUES(205, 1, 2, 0, '2012-06-12 02:35:09');
INSERT INTO `banners_history` VALUES(206, 1, 10, 0, '2012-06-13 11:50:40');
INSERT INTO `banners_history` VALUES(207, 1, 1, 0, '2012-06-14 06:10:48');
INSERT INTO `banners_history` VALUES(208, 1, 1, 0, '2012-06-15 17:39:42');
INSERT INTO `banners_history` VALUES(209, 1, 2, 0, '2012-06-16 04:27:19');
INSERT INTO `banners_history` VALUES(210, 1, 2, 0, '2012-06-18 00:48:15');
INSERT INTO `banners_history` VALUES(211, 1, 7, 0, '2012-06-19 01:46:29');
INSERT INTO `banners_history` VALUES(212, 1, 1, 0, '2012-06-22 22:21:06');
INSERT INTO `banners_history` VALUES(213, 1, 8, 0, '2012-06-25 11:28:40');
INSERT INTO `banners_history` VALUES(214, 1, 5, 0, '2012-06-26 05:02:01');
INSERT INTO `banners_history` VALUES(215, 1, 6, 0, '2012-06-27 12:26:58');
INSERT INTO `banners_history` VALUES(216, 1, 4, 0, '2012-06-28 22:57:02');
INSERT INTO `banners_history` VALUES(217, 1, 5, 0, '2012-06-29 08:12:56');
INSERT INTO `banners_history` VALUES(218, 1, 4, 0, '2012-06-30 04:12:30');
INSERT INTO `banners_history` VALUES(219, 1, 12, 0, '2012-07-01 00:10:23');
INSERT INTO `banners_history` VALUES(220, 1, 11, 0, '2012-07-02 12:12:50');
INSERT INTO `banners_history` VALUES(221, 1, 16, 0, '2012-07-03 01:09:19');
INSERT INTO `banners_history` VALUES(222, 1, 20, 0, '2012-07-04 00:36:55');
INSERT INTO `banners_history` VALUES(223, 1, 14, 0, '2012-07-05 02:24:07');
INSERT INTO `banners_history` VALUES(224, 1, 13, 0, '2012-07-06 05:51:50');
INSERT INTO `banners_history` VALUES(225, 1, 1, 0, '2012-07-07 23:01:15');
INSERT INTO `banners_history` VALUES(226, 1, 4, 0, '2012-07-08 03:32:49');
INSERT INTO `banners_history` VALUES(227, 1, 7, 0, '2012-07-09 05:53:46');
INSERT INTO `banners_history` VALUES(228, 1, 11, 0, '2012-07-10 03:54:06');
INSERT INTO `banners_history` VALUES(229, 1, 14, 0, '2012-07-11 01:07:31');
INSERT INTO `banners_history` VALUES(230, 1, 18, 0, '2012-07-12 03:19:41');
INSERT INTO `banners_history` VALUES(231, 1, 7, 0, '2012-07-13 07:17:07');
INSERT INTO `banners_history` VALUES(232, 1, 6, 0, '2012-07-14 04:21:13');
INSERT INTO `banners_history` VALUES(233, 1, 2, 0, '2012-07-15 09:34:43');
INSERT INTO `banners_history` VALUES(234, 1, 5, 0, '2012-07-16 02:33:32');
INSERT INTO `banners_history` VALUES(235, 1, 2, 0, '2012-07-17 12:01:34');
INSERT INTO `banners_history` VALUES(236, 1, 8, 0, '2012-07-18 10:50:01');
INSERT INTO `banners_history` VALUES(237, 1, 2, 0, '2012-07-19 19:47:43');
INSERT INTO `banners_history` VALUES(238, 1, 14, 0, '2012-07-20 10:18:42');
INSERT INTO `banners_history` VALUES(239, 1, 6, 0, '2012-07-21 09:24:09');
INSERT INTO `banners_history` VALUES(240, 1, 6, 0, '2012-07-22 00:56:17');
INSERT INTO `banners_history` VALUES(241, 1, 6, 0, '2012-07-23 10:18:44');
INSERT INTO `banners_history` VALUES(242, 1, 12, 0, '2012-07-24 12:52:37');
INSERT INTO `banners_history` VALUES(243, 1, 4, 0, '2012-07-25 08:20:12');
INSERT INTO `banners_history` VALUES(244, 1, 25, 0, '2012-07-26 03:39:46');
INSERT INTO `banners_history` VALUES(245, 1, 12, 0, '2012-07-27 02:47:20');
INSERT INTO `banners_history` VALUES(246, 1, 10, 0, '2012-07-28 01:04:17');
INSERT INTO `banners_history` VALUES(247, 1, 21, 0, '2012-07-29 03:01:15');
INSERT INTO `banners_history` VALUES(248, 1, 12, 0, '2012-07-30 13:44:06');
INSERT INTO `banners_history` VALUES(249, 1, 10, 0, '2012-07-31 06:06:23');
INSERT INTO `banners_history` VALUES(250, 1, 8, 0, '2012-08-01 02:44:26');
INSERT INTO `banners_history` VALUES(251, 1, 15, 0, '2012-08-02 00:54:03');
INSERT INTO `banners_history` VALUES(252, 1, 17, 0, '2012-08-03 02:48:37');
INSERT INTO `banners_history` VALUES(253, 1, 4, 0, '2012-08-04 01:57:41');
INSERT INTO `banners_history` VALUES(254, 1, 22, 0, '2012-08-05 02:12:54');
INSERT INTO `banners_history` VALUES(255, 1, 8, 0, '2012-08-06 10:28:59');
INSERT INTO `banners_history` VALUES(256, 1, 10, 0, '2012-08-07 00:56:49');
INSERT INTO `banners_history` VALUES(257, 1, 4, 0, '2012-08-08 10:04:09');
INSERT INTO `banners_history` VALUES(258, 1, 6, 0, '2012-08-09 06:27:27');
INSERT INTO `banners_history` VALUES(259, 1, 2, 0, '2012-08-10 03:39:53');
INSERT INTO `banners_history` VALUES(260, 1, 3, 0, '2012-08-11 02:15:08');
INSERT INTO `banners_history` VALUES(261, 1, 3, 0, '2012-08-12 03:39:04');
INSERT INTO `banners_history` VALUES(262, 1, 7, 0, '2012-08-13 12:05:42');
INSERT INTO `banners_history` VALUES(263, 1, 1, 0, '2012-08-14 00:54:00');
INSERT INTO `banners_history` VALUES(264, 1, 7, 0, '2012-08-15 03:53:25');
INSERT INTO `banners_history` VALUES(265, 1, 2, 0, '2012-08-16 03:10:53');
INSERT INTO `banners_history` VALUES(266, 1, 4, 0, '2012-08-17 16:50:40');
INSERT INTO `banners_history` VALUES(267, 1, 2, 0, '2012-08-18 07:57:49');
INSERT INTO `banners_history` VALUES(268, 1, 2, 0, '2012-08-19 06:54:45');
INSERT INTO `banners_history` VALUES(269, 1, 8, 0, '2012-08-20 04:22:07');
INSERT INTO `banners_history` VALUES(270, 1, 14, 0, '2012-08-21 07:27:13');
INSERT INTO `banners_history` VALUES(271, 1, 4, 0, '2012-08-22 05:38:46');
INSERT INTO `banners_history` VALUES(272, 1, 1, 0, '2012-08-24 07:58:18');
INSERT INTO `banners_history` VALUES(273, 1, 3, 0, '2012-08-25 10:35:27');
INSERT INTO `banners_history` VALUES(274, 1, 3, 0, '2012-08-26 01:18:25');
INSERT INTO `banners_history` VALUES(275, 1, 8, 0, '2012-08-27 08:03:34');
INSERT INTO `banners_history` VALUES(276, 1, 2, 0, '2012-08-29 04:58:37');
INSERT INTO `banners_history` VALUES(277, 1, 12, 0, '2012-08-30 08:46:22');
INSERT INTO `banners_history` VALUES(278, 1, 3, 0, '2012-08-31 00:25:15');
INSERT INTO `banners_history` VALUES(279, 1, 1, 0, '2012-09-01 21:27:42');
INSERT INTO `banners_history` VALUES(280, 1, 1, 0, '2012-09-02 03:57:45');
INSERT INTO `banners_history` VALUES(281, 1, 15, 0, '2012-09-04 05:41:01');
INSERT INTO `banners_history` VALUES(282, 1, 5, 0, '2012-09-05 09:13:18');
INSERT INTO `banners_history` VALUES(283, 1, 4, 0, '2012-09-06 02:52:00');
INSERT INTO `banners_history` VALUES(284, 1, 1, 0, '2012-09-07 12:14:51');
INSERT INTO `banners_history` VALUES(285, 1, 4, 0, '2012-09-08 00:04:55');
INSERT INTO `banners_history` VALUES(286, 1, 1, 0, '2012-09-09 07:27:06');
INSERT INTO `banners_history` VALUES(287, 1, 5, 0, '2012-09-12 00:29:27');
INSERT INTO `banners_history` VALUES(288, 1, 2, 0, '2012-09-13 16:28:55');
INSERT INTO `banners_history` VALUES(289, 1, 2, 0, '2012-09-14 01:35:17');
INSERT INTO `banners_history` VALUES(290, 1, 2, 0, '2012-09-15 21:03:44');
INSERT INTO `banners_history` VALUES(291, 1, 5, 0, '2012-09-17 01:04:07');
INSERT INTO `banners_history` VALUES(292, 1, 6, 0, '2012-09-18 03:23:38');
INSERT INTO `banners_history` VALUES(293, 1, 3, 0, '2012-09-20 21:43:35');
INSERT INTO `banners_history` VALUES(294, 1, 4, 0, '2012-09-21 08:47:05');
INSERT INTO `banners_history` VALUES(295, 1, 2, 0, '2012-09-22 14:01:42');
INSERT INTO `banners_history` VALUES(296, 1, 1, 0, '2012-09-23 09:49:22');
INSERT INTO `banners_history` VALUES(297, 1, 2, 0, '2012-09-24 23:56:43');
INSERT INTO `banners_history` VALUES(298, 1, 2, 0, '2012-09-25 16:11:56');
INSERT INTO `banners_history` VALUES(299, 1, 1, 0, '2012-09-26 14:23:04');
INSERT INTO `banners_history` VALUES(300, 1, 8, 0, '2012-09-27 00:41:15');
INSERT INTO `banners_history` VALUES(301, 1, 2, 0, '2012-09-28 17:19:56');
INSERT INTO `banners_history` VALUES(302, 1, 4, 0, '2012-09-30 01:09:46');
INSERT INTO `banners_history` VALUES(303, 1, 9, 0, '2012-10-01 05:23:23');
INSERT INTO `banners_history` VALUES(304, 1, 2, 0, '2012-10-02 19:55:21');
INSERT INTO `banners_history` VALUES(305, 1, 29, 0, '2012-10-03 02:37:58');
INSERT INTO `banners_history` VALUES(306, 1, 1, 0, '2012-10-04 03:05:23');
INSERT INTO `banners_history` VALUES(307, 1, 4, 0, '2012-10-05 07:50:00');
INSERT INTO `banners_history` VALUES(308, 1, 7, 0, '2012-10-06 00:38:04');
INSERT INTO `banners_history` VALUES(309, 1, 14, 0, '2012-10-07 11:46:01');
INSERT INTO `banners_history` VALUES(310, 1, 2, 0, '2012-10-08 15:52:51');
INSERT INTO `banners_history` VALUES(311, 1, 3, 0, '2012-10-09 22:31:39');
INSERT INTO `banners_history` VALUES(312, 1, 3, 0, '2012-10-10 07:27:38');
INSERT INTO `banners_history` VALUES(313, 1, 1, 0, '2012-10-12 09:35:15');
INSERT INTO `banners_history` VALUES(314, 1, 4, 0, '2012-10-13 00:44:18');
INSERT INTO `banners_history` VALUES(315, 1, 4, 0, '2012-10-14 10:12:29');
INSERT INTO `banners_history` VALUES(316, 1, 4, 0, '2012-10-15 07:58:12');
INSERT INTO `banners_history` VALUES(317, 1, 2, 0, '2012-10-16 06:01:20');
INSERT INTO `banners_history` VALUES(318, 1, 2, 0, '2012-10-17 19:03:40');

CREATE TABLE IF NOT EXISTS `cache` (
  `cache_id` varchar(32) NOT NULL DEFAULT '',
  `cache_language_id` tinyint(1) NOT NULL DEFAULT '0',
  `cache_name` varchar(255) NOT NULL DEFAULT '',
  `cache_data` mediumtext NOT NULL,
  `cache_global` tinyint(1) NOT NULL DEFAULT '1',
  `cache_gzip` tinyint(1) NOT NULL DEFAULT '1',
  `cache_method` varchar(20) NOT NULL DEFAULT 'RETURN',
  `cache_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cache_expires` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`cache_id`,`cache_language_id`),
  KEY `cache_id` (`cache_id`),
  KEY `cache_language_id` (`cache_language_id`),
  KEY `cache_global` (`cache_global`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `cache` VALUES('e607bcb0366b4523875fb74fc2d58df9', 1, 'seo_urls_v2_articles', 'fZA9CwIxDIZ3f4XbKZjhOFHBSeQGQR3E/aht7gz0izY98N/biuAiNyY8efImCnuyuKgOt/vpeG676+HSdnVTV6t5JZ1VxORsrJb7mfpPNoXULqAB8jEZUC5XIAwyZEFEycgpNxR5ipLsAKiJJ4zrYhwxMj2SzkKvhcQgGISUyURhQdOQtMgeNGg5I2jJAKPx380T9k2xxyd5X6KEkm3yvm3hfaBRyBdYxyRxwr4r9O9v4HpI8TPwBg==', 1, 1, 'EVAL', '2012-10-06 15:53:09', '2012-11-05 15:53:09');
INSERT INTO `cache` VALUES('779207fa39b193ede370adb76cd2a839', 1, 'seo_urls_v2_topics', 'S0lNy8xL1VAP8Q/wdI73c/R1jTcyUddRUC9OzC3ISdVNLCrJTM5JLVbXtOYCAA==', 1, 1, 'EVAL', '2012-10-06 15:53:09', '2012-11-05 15:53:09');
INSERT INTO `cache` VALUES('dd37d333c182772e53d35489b49669c5', 1, 'seo_urls_v2_links', 'AwA=', 1, 1, 'EVAL', '2012-10-06 15:53:09', '2012-11-05 15:53:09');
INSERT INTO `cache` VALUES('a93b9170a03ff54d81e95917742ea01b', 1, 'seo_urls_v2_categories', 'S0lNy8xL1VB3dgxxdfcPioz3c/R1jTcyijcyjjcyUddRUE9OLElNzy+q1IUx1DWtuVJwawPpKUktLtEtKMpPSS4tKSZKI4Yu/LaYYqgnyhawr8zI8JVpvJE5qdosQBpS8vMSSzLz88ChUZpcUozHX5YwT4HUAAA=', 1, 1, 'EVAL', '2012-10-06 15:53:09', '2012-11-05 15:53:09');
INSERT INTO `cache` VALUES('4404c1df54fdb1291c8dd9bb259f32a9', 1, 'seo_urls_v2_manufacturers', 'S0lNy8xL1VD3dfQLdXN0DgkNcg2K93P0dY03U9dRUC9JLS5JLVLXtOZKwanQHKZQ1wSkEAA=', 1, 1, 'EVAL', '2012-10-06 15:53:09', '2012-11-05 15:53:09');
INSERT INTO `cache` VALUES('ca34fbe5f9a075091ad59abf02c259a7', 1, 'seo_urls_v2_products', 'lZfLTsMwEEX3fAW7gkQke8Z2YrFCwJKHEKyr0LgQAU2VuvD7NImQWNB7y9qvOeeOU7dJy3aVTmb3D3dXT5eP89uLm+u5Gjc7O57NTs+Pmr/GpTJk3MJxW5VwPHg4rFbhuI34eLVC1uPzbazgejFET4nxxWA+Yk8sLs8K1mMFl2cF76+WdE+J10uF45EKxyMW8wmJX0h7CeMTEj/xL4LjFyF+hfiRgNuX5C8kf5GI91fiR0l+SvpDiT8l/pT4U+JPCb8j/I7wO8LvCL8j/I7wO8Lv8PdDHPHjiR9P/HjixxM/nvB7wu8JXyB8gfAFwhcIXyD5k59fCYQ/kPwD+f4H4q8k/CWpryR+SuKnJP1RkfojyZ88XyQS/kj4IuGLhC8Sv5Hkz55PEftT8rxSg++PkueVWpyPGuxHDfajBvtRg++HGuKHvH/U4vV+qi+nTS7WfddsF3nvPwE/1XrY3MnrMLddvRSLus+b3PWpWKbUbIr81eac+mJZL9Jz173tP3Pi+9lnKHGo8deG/9hqvEov7TIXn9128Zp6cOx4rca5u9obMHG8XwduinvJ27GXBlZwHu4nP/0fyH29k7SpP9bviec69Qg+d3rnDnOKOue+fd7mNFT5DQ==', 1, 1, 'EVAL', '2012-10-06 15:53:09', '2012-11-05 15:53:09');

CREATE TABLE IF NOT EXISTS `calendar_event` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `event` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `day` tinyint(2) NOT NULL,
  `month` tinyint(2) NOT NULL,
  `year` int(4) NOT NULL,
  `time_from` varchar(10) NOT NULL,
  `time_until` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `calendar_users` (
  `user_id` int(8) NOT NULL AUTO_INCREMENT,
  `username` varchar(11) NOT NULL,
  `password` varchar(32) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `calendar_users` VALUES(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

CREATE TABLE IF NOT EXISTS `categories` (
  `categories_id` int(11) NOT NULL AUTO_INCREMENT,
  `categories_image` varchar(64) DEFAULT NULL,
  `altProdDisplay` tinyint(4) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(3) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`categories_id`),
  KEY `idx_categories_parent_id` (`parent_id`),
  KEY `sort_order` (`sort_order`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

INSERT INTO `categories` VALUES(23, 'Black-Wagon-Large.jpg', 0, 22, 0, '2012-01-25 03:36:01', NULL);
INSERT INTO `categories` VALUES(22, 'angel.jpg', 0, 0, 0, '2012-01-25 03:29:35', '2012-08-16 14:32:15');
INSERT INTO `categories` VALUES(24, 'butterfly1.jpg', 0, 23, 0, '2012-01-25 03:47:44', NULL);
INSERT INTO `categories` VALUES(25, 'iStock_000003553750XSmall.jpg', 0, 22, 0, '2012-01-25 03:52:11', '2012-01-25 03:52:26');
INSERT INTO `categories` VALUES(26, 'iStock_000004105680XSmall.jpg', 0, 23, 0, '2012-01-25 04:07:01', NULL);
INSERT INTO `categories` VALUES(27, '', 0, 25, 0, '2012-01-25 04:09:20', NULL);
INSERT INTO `categories` VALUES(28, '', 1, 0, 0, '2012-09-12 21:14:15', '2012-09-27 13:09:38');
INSERT INTO `categories` VALUES(29, '', 0, 0, 0, '2012-09-24 17:35:49', NULL);

CREATE TABLE IF NOT EXISTS `categories_description` (
  `categories_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `categories_name` varchar(96) NOT NULL,
  `categories_htc_title_tag` varchar(80) DEFAULT NULL,
  `categories_htc_desc_tag` longtext,
  `categories_htc_keywords_tag` longtext,
  `categories_htc_description` longtext,
  `categories_seo_url` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`categories_id`,`language_id`),
  KEY `idx_categories_name` (`categories_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `categories_description` VALUES(24, 1, 'Sub Category 1', 'Sub Category 1', 'Sub Category 1', 'Sub Category 1', 'Contrary to popular belief,\r\nLorem Ipsum is not simply\r\nrandom text. It has roots in a\r\npiece of classical Latin\r\nliterature from 45 BC, making\r\nit over 2000 years old.\r\nRichard McClintock, a Latin\r\nprofessor at Hampden-Sydney\r\nCollege in Virginia, looked up\r\none of the more obscure Latin\r\nwords, consectetur, from a\r\nLorem Ipsum passage, and going\r\nthrough the cites of the word\r\nin classical literature,\r\ndiscovered the undoubtable\r\nsource. Lorem Ipsum comes from\r\nsections 1.10.32 and 1.10.33\r\nof "de Finibus Bonorum et\r\nMalorum" (The Extremes of Good\r\nand Evil) by Cicero, written\r\nin 45 BC. This book is a\r\ntreatise on the theory of\r\nethics, very popular during\r\nthe Renaissance. The first\r\nline of Lorem Ipsum, "Lorem\r\nipsum dolor sit amet..", comes\r\nfrom a line in section 1.10.32.', '');
INSERT INTO `categories_description` VALUES(23, 1, 'Category 1', 'Category 1', 'Category 1', 'Category 1', 'Lorem Ipsum is simply dummy\r\ntext of the printing and\r\ntypesetting industry. Lorem\r\nIpsum has been the industry''s\r\nstandard dummy text ever since\r\nthe 1500s, when an unknown\r\nprinter took a galley of type\r\nand scrambled it to make a\r\ntype specimen book. It has\r\nsurvived not only five\r\ncenturies, but also the leap\r\ninto electronic typesetting,\r\nremaining essentially\r\nunchanged. It was popularised\r\nin the 1960s with the release\r\nof Letraset sheets containing\r\nLorem Ipsum passages, and more\r\nrecently with desktop\r\npublishing software like Aldus\r\nPageMaker including versions\r\nof Lorem Ipsum.', '');
INSERT INTO `categories_description` VALUES(22, 1, 'Test Prodcuts', 'Test Prodcuts', 'Test Prodcuts', 'Test Prodcuts', 'Lorem ipsum dolor sit amet,\r\nconsectetur adipiscing elit.\r\nNunc eu urna sapien. Fusce\r\nvehicula egestas est nec\r\npretium. Class aptent taciti\r\nsociosqu ad litora torquent\r\nper conubia nostra, per\r\ninceptos himenaeos. Aliquam\r\ntempor sollicitudin lacus vel\r\nluctus. Class aptent taciti\r\nsociosqu ad litora torquent\r\nper conubia nostra, per\r\ninceptos himenaeos. Maecenas\r\nmi urna, interdum ac placerat\r\nat, rhoncus non sapien. Morbi\r\ncommodo sodales imperdiet. Ut\r\nlibero nulla, tempor auctor\r\nlacinia id, egestas sed augue.\r\nCum sociis natoque penatibus\r\net magnis dis parturient\r\nmontes, nascetur ridiculus\r\nmus. Cras sagittis nunc ac\r\ndiam pretium auctor vel id\r\norci. Nam vehicula posuere\r\nlaoreet. Fusce dignissim\r\neleifend scelerisque.', '');
INSERT INTO `categories_description` VALUES(25, 1, 'Category 2', 'Category 2', 'Category 2', 'Category 2', '"Lorem ipsum dolor sit amet,\r\nconsectetur adipisicing elit,\r\nsed do eiusmod tempor\r\nincididunt ut labore et dolore\r\nmagna aliqua. Ut enim ad minim\r\nveniam, quis nostrud\r\nexercitation ullamco laboris\r\nnisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure\r\ndolor in reprehenderit in\r\nvoluptate velit esse cillum\r\ndolore eu fugiat nulla\r\npariatur. Excepteur sint\r\noccaecat cupidatat non\r\nproident, sunt in culpa qui\r\nofficia deserunt mollit anim\r\nid est laborum."', '');
INSERT INTO `categories_description` VALUES(26, 1, 'Sub Category 2', 'Sub Category 2', 'Sub Category 2', 'Sub Category 2', '"Sed ut perspiciatis unde\r\nomnis iste natus error sit\r\nvoluptatem accusantium\r\ndoloremque laudantium, totam\r\nrem aperiam, eaque ipsa quae\r\nab illo inventore veritatis et\r\nquasi architecto beatae vitae\r\ndicta sunt explicabo. Nemo\r\nenim ipsam voluptatem quia\r\nvoluptas sit aspernatur aut\r\nodit aut fugit, sed quia\r\nconsequuntur magni dolores eos\r\nqui ratione voluptatem sequi\r\nnesciunt. Neque porro quisquam\r\nest, qui dolorem ipsum quia\r\ndolor sit amet, consectetur,\r\nadipisci velit, sed quia non\r\nnumquam eius modi tempora\r\nincidunt ut labore et dolore\r\nmagnam aliquam quaerat\r\nvoluptatem. Ut enim ad minima\r\nveniam, quis nostrum\r\nexercitationem ullam corporis\r\nsuscipit laboriosam, nisi ut\r\naliquid ex ea commodi\r\nconsequatur? Quis autem vel\r\neum iure reprehenderit qui in\r\nea voluptate velit esse quam\r\nnihil molestiae consequatur,\r\nvel illum qui dolorem eum\r\nfugiat quo voluptas nulla\r\npariatur?"', '');
INSERT INTO `categories_description` VALUES(27, 1, 'Sub Category 1', 'Sub Category 1', 'Sub Category 1', 'Sub Category 1', '', '');
INSERT INTO `categories_description` VALUES(28, 1, 'Donation Products', 'Donation Products', 'Donation Products', 'Donation Products', '', '');
INSERT INTO `categories_description` VALUES(29, 1, 'test', 'test', 'test', 'test', '', '');

CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `comment_item_id` tinytext COLLATE utf8_bin,
  `comment_ipaddress` varchar(13) COLLATE utf8_bin DEFAULT NULL,
  `comment_name` tinytext COLLATE utf8_bin,
  `comment_email` tinytext COLLATE utf8_bin,
  `comment_website` tinytext COLLATE utf8_bin,
  `comment_text` text COLLATE utf8_bin,
  `comment_date` datetime DEFAULT NULL,
  `comment_active` smallint(6) DEFAULT '1',
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `configuration` (
  `configuration_id` int(11) NOT NULL AUTO_INCREMENT,
  `configuration_title` varchar(150) NOT NULL DEFAULT '',
  `configuration_key` varchar(64) NOT NULL DEFAULT '',
  `configuration_value` text NOT NULL,
  `configuration_description` varchar(255) NOT NULL DEFAULT '',
  `configuration_group_id` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(5) DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `use_function` varchar(255) DEFAULT NULL,
  `set_function` text,
  PRIMARY KEY (`configuration_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=555190 ;

INSERT INTO `configuration` VALUES(1, 'Store Name', 'STORE_NAME', 'CartStore', 'The name of my store', 1, 1, '2012-04-03 01:04:58', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(2, 'Store Owner', 'STORE_OWNER', 'CartStore', 'The name of my store owner', 1, 2, '2012-04-03 01:05:14', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(3, 'E-Mail Address', 'STORE_OWNER_EMAIL_ADDRESS', 'jasonphillips@pacificwest.com', 'The e-mail address of my store owner', 1, 3, '2012-10-03 21:01:12', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(4, 'E-Mail From', 'EMAIL_FROM', 'jasonphillips@pacificwest.com', 'The e-mail address used in (sent) e-mails', 1, 4, '2012-10-03 21:01:23', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(5, 'Country', 'STORE_COUNTRY', '223', 'The country my store is located in <br><br><b>Note: Please remember to update the store zone.</b>', 1, 6, '2010-07-31 22:41:30', '2006-06-15 13:53:25', 'tep_get_country_name', 'tep_cfg_pull_down_country_list(');
INSERT INTO `configuration` VALUES(6, 'Zone', 'STORE_ZONE', '18', 'The zone my store is located in', 1, 7, '2012-04-03 01:05:49', '2006-06-15 13:53:25', 'tep_cfg_get_zone_name', 'tep_cfg_pull_down_zone_list(');
INSERT INTO `configuration` VALUES(7, 'Expected Sort Order', 'EXPECTED_PRODUCTS_SORT', 'desc', 'This is the sort order used in the expected products box.', 1, 8, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', 'tep_cfg_select_option(array(''asc'', ''desc''),');
INSERT INTO `configuration` VALUES(8, 'Expected Sort Field', 'EXPECTED_PRODUCTS_FIELD', 'date_expected', 'The column to sort by in the expected products box.', 1, 9, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', 'tep_cfg_select_option(array(''products_name'', ''date_expected''),');
INSERT INTO `configuration` VALUES(9, 'Switch To Default Language Currency', 'USE_DEFAULT_LANGUAGE_CURRENCY', 'false', 'Automatically switch to the language''s currency when it is changed', 1, 10, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(10, 'Send Order Emails To', 'SEND_EXTRA_ORDER_EMAILS_TO', 'jasonphillips@pacificwest.com', 'Send order emails to the following email addresses, in this format: Name 1 &lt;email@address1&gt;, Name 2 &lt;email@address2&gt;', 1, 11, '2012-10-03 21:01:35', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(12, 'Display Cart After Adding Product', 'DISPLAY_CART', 'true', 'Display the shopping cart after adding a product (or return back to their origin)', 1, 14, '2011-06-16 16:38:05', '2006-06-15 13:53:25', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(13, 'Allow Guest To Tell A Friend', 'ALLOW_GUEST_TO_TELL_A_FRIEND', 'true', 'Allow guests to tell a friend about a product', 1, 15, '2006-07-03 02:15:27', '2006-06-15 13:53:25', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(14, 'Default Search Operator', 'ADVANCED_SEARCH_DEFAULT_OPERATOR', 'and', 'Default search operators', 1, 17, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', 'tep_cfg_select_option(array(''and'', ''or''),');
INSERT INTO `configuration` VALUES(15, 'Store Address and Phone', 'STORE_NAME_ADDRESS', '1234 Test Way\r\nOrlando FL 32805', 'This is the Store Name, Address and Phone used on printable documents and displayed online', 1, 18, '2012-04-03 01:08:05', '2006-06-15 13:53:25', '', 'tep_cfg_textarea(');
INSERT INTO `configuration` VALUES(5347, 'Handling Fee', 'MODULE_SHIPPING_TABLE_HANDLING', '0', 'Handling fee for this shipping method.', 6, 0, '0000-00-00 00:00:00', '2009-03-18 05:36:46', '', '');
INSERT INTO `configuration` VALUES(16, 'Show Category Counts', 'SHOW_COUNTS', 'true', 'Count recursively how many products are in each category', 1, 19, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(17, 'Tax Decimal Places', 'TAX_DECIMAL_PLACES', '0', 'Pad the tax value this amount of decimal places', 1, 20, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(18, 'Display Prices with Tax', 'DISPLAY_PRICE_WITH_TAX', 'false', 'Display prices with tax included (true) or add the tax at the end (false)', 1, 21, '2012-09-20 23:02:46', '2006-06-15 13:53:25', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(19, 'First Name', 'ENTRY_FIRST_NAME_MIN_LENGTH', '2', 'Minimum length of first name', 2, 1, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(20, 'Last Name', 'ENTRY_LAST_NAME_MIN_LENGTH', '2', 'Minimum length of last name', 2, 2, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(21, 'Date of Birth', 'ENTRY_DOB_MIN_LENGTH', '10', 'Minimum length of date of birth', 2, 3, '2007-03-16 23:50:58', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(22, 'E-Mail Address', 'ENTRY_EMAIL_ADDRESS_MIN_LENGTH', '6', 'Minimum length of e-mail address', 2, 4, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(23, 'Street Address', 'ENTRY_STREET_ADDRESS_MIN_LENGTH', '5', 'Minimum length of street address', 2, 5, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(24, 'Company', 'ENTRY_COMPANY_MIN_LENGTH', '2', 'Minimum length of company name', 2, 6, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(25, 'Post Code', 'ENTRY_POSTCODE_MIN_LENGTH', '4', 'Minimum length of post code', 2, 7, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(26, 'City', 'ENTRY_CITY_MIN_LENGTH', '3', 'Minimum length of city', 2, 8, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(27, 'State', 'ENTRY_STATE_MIN_LENGTH', '2', 'Minimum length of state', 2, 9, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(28, 'Telephone Number', 'ENTRY_TELEPHONE_MIN_LENGTH', '3', 'Minimum length of telephone number', 2, 10, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(29, 'Password', 'ENTRY_PASSWORD_MIN_LENGTH', '5', 'Minimum length of password', 2, 11, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(30, 'Credit Card Owner Name', 'CC_OWNER_MIN_LENGTH', '3', 'Minimum length of credit card owner name', 2, 12, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(31, 'Credit Card Number', 'CC_NUMBER_MIN_LENGTH', '10', 'Minimum length of credit card number', 2, 13, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(32, 'Review Text', 'REVIEW_TEXT_MIN_LENGTH', '10', 'Minimum length of review text', 2, 14, '2007-03-08 13:24:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(33, 'Best Sellers', 'MIN_DISPLAY_BESTSELLERS', '1', 'Minimum number of best sellers to display', 2, 15, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(34, 'Also Purchased', 'MIN_DISPLAY_ALSO_PURCHASED', '1', 'Minimum number of products to display in the ''This Customer Also Purchased'' box', 2, 16, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(35, 'Address Book Entries', 'MAX_ADDRESS_BOOK_ENTRIES', '5', 'Maximum address book entries a customer is allowed to have', 3, 1, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(36, 'Search Results', 'MAX_DISPLAY_SEARCH_RESULTS', '14', 'Amount of products to list', 3, 2, '2009-10-25 22:32:45', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(37, 'Page Links', 'MAX_DISPLAY_PAGE_LINKS', '4', 'Number of ''number'' links use for page-sets', 3, 3, '2008-08-09 03:47:52', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(38, 'Special Products', 'MAX_DISPLAY_SPECIAL_PRODUCTS', '24', 'Maximum number of products on special to display', 3, 4, '2009-05-22 18:47:14', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(39, 'New Products Module', 'MAX_DISPLAY_NEW_PRODUCTS', '8', 'Maximum number of new products to display in a category', 3, 5, '2009-10-27 21:09:54', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(40, 'Products Expected', 'MAX_DISPLAY_UPCOMING_PRODUCTS', '25', 'Maximum number of products expected to display', 3, 6, '2009-05-16 00:37:59', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(41, 'Manufacturers List', 'MAX_DISPLAY_MANUFACTURERS_IN_A_LIST', '1', 'Used in manufacturers box; when the number of manufacturers exceeds this number, a drop-down list will be displayed instead of the default list', 3, 7, '2012-06-25 23:56:47', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(42, 'Manufacturers Select Size', 'MAX_MANUFACTURERS_LIST', '1', 'Used in manufacturers box; when this value is ''1'' the classic drop-down list will be used for the manufacturers box. Otherwise, a list-box with the specified number of rows will be displayed.', 3, 7, '2012-06-25 23:57:37', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(43, 'Length of Manufacturers Name', 'MAX_DISPLAY_MANUFACTURER_NAME_LEN', '15', 'Used in manufacturers box; maximum length of manufacturers name to display', 3, 8, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(44, 'New Reviews', 'MAX_DISPLAY_NEW_REVIEWS', '6', 'Maximum number of new reviews to display', 3, 9, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(45, 'Selection of Random Reviews', 'MAX_RANDOM_SELECT_REVIEWS', '10', 'How many records to select from to choose one random product review', 3, 10, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(46, 'Selection of Random New Products', 'MAX_RANDOM_SELECT_NEW', '15', 'How many records to select from to choose one random new product to display', 3, 11, '2006-10-01 21:21:33', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(47, 'Selection of Products on Special', 'MAX_RANDOM_SELECT_SPECIALS', '10', 'How many records to select from to choose one random product special to display', 3, 12, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(48, 'Categories To List Per Row', 'MAX_DISPLAY_CATEGORIES_PER_ROW', '3', 'How many categories to list per row', 3, 13, '2007-08-27 16:16:01', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(49, 'New Products Listing', 'MAX_DISPLAY_PRODUCTS_NEW', '4', 'Maximum number of new products to display in new products page', 3, 14, '2006-09-24 15:47:39', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(50, 'Best Sellers', 'MAX_DISPLAY_BESTSELLERS', '10', 'Maximum number of best sellers to display', 3, 15, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(51, 'Also Purchased', 'MAX_DISPLAY_ALSO_PURCHASED', '8', 'Maximum number of products to display in the ''This Customer Also Purchased'' box', 3, 16, '2010-07-28 01:20:37', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(52, 'Customer Order History Box', 'MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX', '6', 'Maximum number of products to display in the customer order history box', 3, 17, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(53, 'Order History', 'MAX_DISPLAY_ORDER_HISTORY', '10', 'Maximum number of orders to display in the order history page', 3, 18, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(54, 'Small Image Width', 'SMALL_IMAGE_WIDTH', '80', 'The pixel width of small images', 4, 5, '2009-04-23 23:52:25', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(55, 'Small Image Height', 'SMALL_IMAGE_HEIGHT', '', 'The pixel height of small images', 4, 6, '2007-01-03 04:30:37', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(56, 'Heading Image Width', 'HEADING_IMAGE_WIDTH', '350', 'The pixel width of heading images', 4, 1, '2012-06-26 00:17:14', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(57, 'Heading Image Height', 'HEADING_IMAGE_HEIGHT', '', 'The pixel height of heading images', 4, 2, '2006-08-18 06:07:55', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(58, 'Subcategory Image Width', 'SUBCATEGORY_IMAGE_WIDTH', '350', 'The pixel width of subcategory images', 4, 3, '2012-06-26 00:16:35', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(59, 'Subcategory Image Height', 'SUBCATEGORY_IMAGE_HEIGHT', '', 'The pixel height of subcategory images', 4, 4, '2007-02-18 04:02:21', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(60, 'Calculate Image Size', 'CONFIG_CALCULATE_IMAGE_SIZE', 'true', 'Calculate the size of images?', 4, 13, '2006-08-17 00:28:24', '2006-06-15 13:53:25', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(61, 'Image Required', 'IMAGE_REQUIRED', 'false', 'Enable to display broken images. Good for development.', 4, 14, '2007-06-11 18:01:28', '2006-06-15 13:53:25', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(62, 'Gender', 'ACCOUNT_GENDER', 'false', 'Display gender in the customers account', 5, 1, '2008-05-19 16:57:49', '2006-06-15 13:53:25', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(63, 'Date of Birth', 'ACCOUNT_DOB', 'false', 'Display date of birth in the customers account', 5, 2, '2008-04-01 16:19:14', '2006-06-15 13:53:25', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(64, 'Company', 'ACCOUNT_COMPANY', 'false', 'Display company in the customers account', 5, 3, '2008-04-01 16:23:24', '2006-06-15 13:53:25', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(65, 'Suburb', 'ACCOUNT_SUBURB', 'false', 'Display suburb in the customers account', 5, 4, '2006-12-29 22:23:47', '2006-06-15 13:53:25', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(66, 'State', 'ACCOUNT_STATE', 'true', 'Display state in the customers account', 5, 5, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(67, 'Installed Modules', 'MODULE_PAYMENT_INSTALLED', 'ccerr.php;moneyorder.php;paypal_express.php;paypal_standard.php', 'List of payment module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: cc.php;cod.php;paypal.php)', 6, 0, '2012-09-21 08:47:58', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(55648, 'Enable Check/Money Order Module', 'MODULE_PAYMENT_MONEYORDER_STATUS', 'True', 'Do you want to accept Check/Money Order payments?', 6, 1, NULL, '2012-03-18 02:32:00', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(68, 'Installed Modules', 'MODULE_ORDER_TOTAL_INSTALLED', 'ot_subtotal.php;ot_tax.php;ot_shipping.php;ot_gv.php;ot_coupon.php;ot_redemptions.php;ot_total.php', 'List of order_total module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: ot_subtotal.php;ot_tax.php;ot_shipping.php;ot_total.php)', 6, 0, '2012-07-03 19:29:01', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(69, 'Installed Modules', 'MODULE_SHIPPING_INSTALLED', 'canadapost.php;rmfirst.php;fedex1.php;flat.php;freeamount.php;table.php;ups.php;indvship.php;dly3.php;dly3datetime.php;mzmt.php;pickup.php;rmRec.php;upsxml.php;usps.php;zipship.php;zones.php', 'List of shipping module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: ups.php;flat.php;item.php)', 6, 0, '2012-10-03 02:40:50', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(5486, 'UPS Pickup Method', 'MODULE_SHIPPING_UPS_PICKUP', 'CC', 'How do you give packages to UPS? CC - Customer Counter, RDP - Daily Pickup, OTP - One Time Pickup, LC - Letter Center, OCA - On Call Air', 6, 0, '0000-00-00 00:00:00', '2009-08-02 08:07:40', '', '');
INSERT INTO `configuration` VALUES(5487, 'UPS Packaging?', 'MODULE_SHIPPING_UPS_PACKAGE', 'CP', 'CP - Your Packaging, ULE - UPS Letter, UT - UPS Tube, UBE - UPS Express Box', 6, 0, '0000-00-00 00:00:00', '2009-08-02 08:07:40', '', '');
INSERT INTO `configuration` VALUES(5488, 'Residential Delivery?', 'MODULE_SHIPPING_UPS_RES', 'RES', 'Quote for Residential (RES) or Commercial Delivery (COM)', 6, 0, '0000-00-00 00:00:00', '2009-08-02 08:07:40', '', '');
INSERT INTO `configuration` VALUES(5489, 'Handling Fee', 'MODULE_SHIPPING_UPS_HANDLING', '0', 'Handling fee for this shipping method.', 6, 0, '0000-00-00 00:00:00', '2009-08-02 08:07:40', '', '');
INSERT INTO `configuration` VALUES(5490, 'Tax Class', 'MODULE_SHIPPING_UPS_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', 6, 0, '0000-00-00 00:00:00', '2009-08-02 08:07:40', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(');
INSERT INTO `configuration` VALUES(5491, 'Shipping Zone', 'MODULE_SHIPPING_UPS_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', 6, 0, '0000-00-00 00:00:00', '2009-08-02 08:07:40', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(');
INSERT INTO `configuration` VALUES(5492, 'Sort order of display.', 'MODULE_SHIPPING_UPS_SORT_ORDER', '6', 'Sort order of display. Lowest is displayed first.', 6, 0, '0000-00-00 00:00:00', '2009-08-02 08:07:40', '', '');
INSERT INTO `configuration` VALUES(84, 'Default Currency', 'DEFAULT_CURRENCY', 'USD', 'Default Currency', 6, 0, '0000-00-00 00:00:00', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(85, 'Default Language', 'DEFAULT_LANGUAGE', 'en', 'Default Language', 6, 0, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(86, 'Default Order Status For New Orders', 'DEFAULT_ORDERS_STATUS_ID', '1', 'When a new order is created, this order status will be assigned to it.', 6, 0, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(87, 'Display Shipping', 'MODULE_ORDER_TOTAL_SHIPPING_STATUS', 'true', 'Do you want to display the order shipping cost?', 6, 1, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(88, 'Sort Order', 'MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER', '3', 'Sort order of display.', 6, 2, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(89, 'Allow Free Shipping', 'MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING', 'false', 'Do you want to allow free shipping?', 6, 3, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(90, 'Free Shipping For Orders Over', 'MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER', '100', 'Provide free shipping for orders over the set amount.', 6, 4, '0000-00-00 00:00:00', '2006-06-15 13:53:26', 'currencies->format', '');
INSERT INTO `configuration` VALUES(91, 'Provide Free Shipping For Orders Made', 'MODULE_ORDER_TOTAL_SHIPPING_DESTINATION', 'national', 'Provide free shipping for orders sent to the set destination.', 6, 5, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''national'', ''international'', ''both''),');
INSERT INTO `configuration` VALUES(92, 'Display Sub-Total', 'MODULE_ORDER_TOTAL_SUBTOTAL_STATUS', 'true', 'Do you want to display the order sub-total cost?', 6, 1, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(93, 'Sort Order', 'MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER', '1', 'Sort order of display.', 6, 2, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(94, 'Display Tax', 'MODULE_ORDER_TOTAL_TAX_STATUS', 'true', 'Do you want to display the order tax value?', 6, 1, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(95, 'Sort Order', 'MODULE_ORDER_TOTAL_TAX_SORT_ORDER', '2', 'Sort order of display.', 6, 2, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(96, 'Display Total', 'MODULE_ORDER_TOTAL_TOTAL_STATUS', 'true', 'Do you want to display the total order value?', 6, 1, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(97, 'Sort Order', 'MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER', '900', 'Sort order of display.', 6, 2, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(98, 'Country of Origin', 'SHIPPING_ORIGIN_COUNTRY', '223', 'Select the country of origin to be used in shipping quotes.', 7, 1, '2007-01-02 16:51:19', '2006-06-15 13:53:26', 'tep_get_country_name', 'tep_cfg_pull_down_country_list(');
INSERT INTO `configuration` VALUES(99, 'Postal Code', 'SHIPPING_ORIGIN_ZIP', '32839', 'Enter the Postal Code (ZIP) of the Store to be used in shipping quotes.', 7, 2, '2009-08-04 21:14:58', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(100, 'Enter the Maximum Package Weight you will ship', 'SHIPPING_MAX_WEIGHT', '70', 'Carriers have a max weight limit for a single package. This is a common one for all.', 7, 3, '2009-08-06 14:26:55', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(101, 'Package Tare weight.', 'SHIPPING_BOX_WEIGHT', '0', 'What is the weight of typical packaging of small to medium packages?', 7, 4, '2009-08-06 14:27:57', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(102, 'Larger packages - percentage increase.', 'SHIPPING_BOX_PADDING', '0', 'For 10% enter 10', 7, 5, '2006-11-04 13:49:57', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(103, 'Display Product Image', 'PRODUCT_LIST_IMAGE', '1', 'Do you want to display the Product Image?', 8, 1, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(104, 'Display Product Manufaturer Name', 'PRODUCT_LIST_MANUFACTURER', '1', 'Do you want to display the Product Manufacturer Name?', 8, 2, '2007-05-04 16:52:07', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(105, 'Display Product Model', 'PRODUCT_LIST_MODEL', '1', 'Do you want to display the Product Model?', 8, 3, '2007-05-04 16:50:19', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(106, 'Display Product Name', 'PRODUCT_LIST_NAME', '2', 'Do you want to display the Product Name?', 8, 4, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(107, 'Display Product Price', 'PRODUCT_LIST_PRICE', '3', 'Do you want to display the Product Price', 8, 5, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(108, 'Display Product Quantity', 'PRODUCT_LIST_QUANTITY', '1', 'Do you want to display the Product Quantity?', 8, 6, '2008-05-12 18:30:13', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(109, 'Display Product Weight', 'PRODUCT_LIST_WEIGHT', '0', 'Do you want to display the Product Weight?', 8, 7, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(110, 'Display Buy Now column', 'PRODUCT_LIST_BUY_NOW', '4', 'Do you want to display the Buy Now column?', 8, 8, '2007-05-08 08:40:06', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(111, 'Display Category/Manufacturer Filter (0=disable; 1=enable)', 'PRODUCT_LIST_FILTER', '1', 'Do you want to display the Category/Manufacturer Filter?', 8, 9, '2008-05-23 04:14:43', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(112, 'Location of Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', 'PREV_NEXT_BAR_LOCATION', '3', 'Sets the location of the Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', 8, 10, '2008-05-23 04:47:53', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(113, 'Check stock level', 'STOCK_CHECK', 'false', 'Check to see if sufficent stock is available', 9, 1, '2008-12-03 00:42:41', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(114, 'Subtract stock', 'STOCK_LIMITED', 'true', 'Subtract product in stock by product orders', 9, 2, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(115, 'Allow Checkout', 'STOCK_ALLOW_CHECKOUT', 'true', 'Allow customer to checkout even if there is insufficient stock', 9, 3, '2007-05-25 15:17:53', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(116, 'Mark product out of stock', 'STOCK_MARK_PRODUCT_OUT_OF_STOCK', 'This item is currently out of stock', 'Display something on screen so customer can see which product has insufficient stock', 9, 4, '2006-10-18 14:49:49', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(117, 'Stock Re-order level', 'STOCK_REORDER_LEVEL', '1', 'Define when stock needs to be re-ordered', 9, 5, '2006-12-29 22:24:37', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(118, 'Store Page Parse Time', 'STORE_PAGE_PARSE_TIME', 'false', 'Store the time it takes to parse a page', 10, 1, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(119, 'Log Destination', 'STORE_PAGE_PARSE_TIME_LOG', '/var/log/www/tep/page_parse_time.log', 'Directory and filename of the page parse time log', 10, 2, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(120, 'Log Date Format', 'STORE_PARSE_DATE_TIME_FORMAT', '%d/%m/%Y %H:%M:%S', 'The date format', 10, 3, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(121, 'Display The Page Parse Time', 'DISPLAY_PAGE_PARSE_TIME', 'true', 'Display the page parse time (store page parse time must be enabled)', 10, 4, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(122, 'Store Database Queries', 'STORE_DB_TRANSACTIONS', 'false', 'Store the database queries in the page parse time log (PHP4 only)', 10, 5, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(123, 'Use Cache', 'USE_CACHE', 'true', 'Use caching features', 11, 1, '2010-09-30 16:59:12', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(124, 'Cache Directory', 'DIR_FS_CACHE', '/tmp/demo46/', 'The directory where the cached files are saved', 11, 2, '2010-09-30 16:59:31', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(125, 'E-Mail Transport Method', 'EMAIL_TRANSPORT', 'sendmail', 'Defines if this server uses a local connection to sendmail or uses an SMTP connection via TCP/IP. Servers running on Windows and MacOS should change this setting to SMTP.', 12, 1, '2008-05-19 16:57:21', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''sendmail'', ''smtp''),');
INSERT INTO `configuration` VALUES(126, 'E-Mail Linefeeds', 'EMAIL_LINEFEED', 'LF', 'Defines the character sequence used to separate mail headers.', 12, 2, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''LF'', ''CRLF''),');
INSERT INTO `configuration` VALUES(127, 'Use MIME HTML When Sending Emails', 'EMAIL_USE_HTML', 'true', 'Send e-mails in HTML format', 12, 3, '2007-08-29 20:40:03', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(128, 'Verify E-Mail Addresses Through DNS', 'ENTRY_EMAIL_ADDRESS_CHECK', 'false', 'Verify e-mail address through a DNS server', 12, 4, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(129, 'Send E-Mails', 'SEND_EMAILS', 'true', 'Send out e-mails', 12, 5, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(130, 'Enable download', 'DOWNLOAD_ENABLED', 'true', 'Enable the products download functions.', 13, 1, '2007-08-29 20:41:19', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(131, 'Download by redirect', 'DOWNLOAD_BY_REDIRECT', 'false', 'Use browser redirection for download. Disable on non-Unix systems.', 13, 2, '2011-01-19 04:14:53', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(132, 'Expiry delay (days)', 'DOWNLOAD_MAX_DAYS', '7', 'Set number of days before the download link expires. 0 means no limit.', 13, 3, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(133, 'Maximum number of downloads', 'DOWNLOAD_MAX_COUNT', '5', 'Set the maximum number of downloads. 0 means no download authorized.', 13, 4, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(134, 'Enable GZip Compression', 'GZIP_COMPRESSION', 'false', 'Enable HTTP GZip compression.', 14, 1, '2010-07-22 16:38:41', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(135, 'Compression Level', 'GZIP_LEVEL', '5', 'Use this compression level 0-9 (0 = minimum, 9 = maximum).', 14, 2, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(136, 'Session Directory', 'SESSION_WRITE_DIRECTORY', 'tmp/', 'If sessions are file based, store them in this directory.', 15, 1, '2012-05-02 16:58:39', '2006-06-15 13:53:26', '', '');
INSERT INTO `configuration` VALUES(137, 'Force Cookie Use', 'SESSION_FORCE_COOKIE_USE', 'False', 'Force the use of sessions when cookies are only enabled.', 15, 2, '2012-05-02 16:58:28', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(138, 'Check SSL Session ID', 'SESSION_CHECK_SSL_SESSION_ID', 'False', 'Validate the SSL_SESSION_ID on every secure HTTPS page request.', 15, 3, '2008-03-24 07:49:19', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(139, 'Check User Agent', 'SESSION_CHECK_USER_AGENT', 'False', 'Validate the clients browser user agent on every page request.', 15, 4, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(140, 'Check IP Address', 'SESSION_CHECK_IP_ADDRESS', 'False', 'Validate the clients IP address on every page request.', 15, 5, '0000-00-00 00:00:00', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(141, 'Prevent Spider Sessions', 'SESSION_BLOCK_SPIDERS', 'True', 'Prevent known spiders from starting a session.', 15, 6, '2010-09-23 15:18:21', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(142, 'Recreate Session', 'SESSION_RECREATE', 'True', 'Recreate the session to generate a new session ID when the customer logs on or creates an account (PHP >=4.1 needed).', 15, 7, '2012-05-02 16:56:15', '2006-06-15 13:53:26', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(166, 'Display featured products', 'FEATURED_PRODUCTS_DISPLAY', 'true', 'Show featured products?', 99, 1, '2008-02-27 23:43:48', '2006-08-17 00:24:35', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(167, 'Featured products maximum', 'MAX_DISPLAY_FEATURED_PRODUCTS', '12', 'How many featured products show?', 99, 2, '2009-10-27 21:09:18', '2006-08-17 00:24:35', '', '');
INSERT INTO `configuration` VALUES(169, 'Words per short description when it''s not written', 'MAX_FEATURED_WORD_DESCRIPTION', '50', 'When you don''t enter short description, truncate description upto how many words?', 99, 4, '2007-01-03 06:33:23', '2006-08-17 00:24:35', '', '');
INSERT INTO `configuration` VALUES(170, 'Featured period', 'DAYS_UNTIL_FEATURED_PRODUCTS', '365', 'How many days do you want to add to the current date when you click on the green light.', 99, 5, '2007-03-01 01:48:14', '2006-08-17 00:24:35', '', '');
INSERT INTO `configuration` VALUES(171, 'Installed Modules', 'MODULE_STS_INSTALLED', 'sts_default.php;sts_index.php;sts_infobox.php', 'This is automatically updated. No need to edit.', 6, 0, '2008-03-24 00:11:08', '2006-08-18 01:35:46', '', '');
INSERT INTO `configuration` VALUES(172, 'Use Templates?', 'MODULE_STS_DEFAULT_STATUS', 'true', 'Do you want to use Simple Template System?', 6, 1, '0000-00-00 00:00:00', '2006-08-18 01:35:57', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(173, 'Code for debug output', 'MODULE_STS_DEBUG_CODE', 'debug', 'Code to enable debug output from URL (ex: index.php?sts_debug=debug', 6, 2, '0000-00-00 00:00:00', '2006-08-18 01:35:57', '', '');
INSERT INTO `configuration` VALUES(174, 'Files for normal template', 'MODULE_STS_DEFAULT_NORMAL', 'sts_user_code.php;headertags.php', 'Files to include for a normal template, separated by semicolon', 6, 2, '0000-00-00 00:00:00', '2006-08-18 01:35:57', '', '');
INSERT INTO `configuration` VALUES(175, 'Template folder', 'MODULE_STS_TEMPLATE_FOLDER', 'default', 'Location of templates inside the includes/sts_templates/ folder. Do not start nor end with a slash', 6, 2, '0000-00-00 00:00:00', '2006-08-18 01:35:57', '', '');
INSERT INTO `configuration` VALUES(176, 'Default template file', 'MODULE_STS_TEMPLATE_FILE', 'sts_template.html', 'Name of the default template file', 6, 2, '0000-00-00 00:00:00', '2006-08-18 01:35:57', '', '');
INSERT INTO `configuration` VALUES(177, 'Use template for infoboxes', 'MODULE_STS_INFOBOX_STATUS', 'true', 'Do you want to use templates for infoboxes?', 6, 1, '0000-00-00 00:00:00', '2006-08-18 01:54:00', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(178, 'Files for infobox template', 'MODULE_STS_INFOBOX_NORMAL', '', 'Files to include for infobox template, separated by semicolon', 6, 2, '0000-00-00 00:00:00', '2006-08-18 01:54:00', '', '');
INSERT INTO `configuration` VALUES(3205, 'Product Information Image Width', 'PRODUCT_INFO_IMAGE_WIDTH', '240', 'The pixel width of images shown on your product information page', 4, 100, '2009-05-19 21:10:31', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3206, 'Product Information Image Height', 'PRODUCT_INFO_IMAGE_HEIGHT', '', 'The pixel height of images shown on your product information page', 4, 101, '2007-01-18 13:56:31', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3207, 'Image Magic Master Switch', 'CFG_MASTER_SWITCH', 'On', 'Switch OSC Image Magic on or off', 333, 3, '2010-08-25 12:48:05', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''On'', ''Off''),');
INSERT INTO `configuration` VALUES(3278, 'Apply security features to registered customers', 'CFG_REGISTERED_WATERMARKS', 'Yes', 'If this option is set to no, all image security features will be disabled when a registered customer is browsing your site', 333, 4, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3209, 'Auto Clean Cache', 'CFG_CACHE_AUTO_CLEAN', 'True', 'If selected, the cache will automatically be cleared of un-needed items. Set to true if you want to sacrifice a small amount of performance for server disk space saving', 333, 9, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(3210, 'Encrypt Image Filenames', 'CFG_ENCRYPT_FILENAMES', 'False', 'If you select this option all of your filenames will be encrypted. This option will prevent visitors from discovering your image filenames. Use it in combination with image watermarking to prevent theft of your images.', 333, 12, '2007-02-07 06:34:23', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(3211, 'Filename Encryption Key', 'CFG_ENCRYPTION_KEY', 'changeme', 'If you have switched on image filename encryption, then enter a string here to be used as the encryption key', 333, 15, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3212, 'Apply Internet Explorer PNG Transparency work-around?', 'CFG_PNG_BUG', 'False', 'This option will switch on a work-around so that PNG alpha transparency images will display correctly in Internet Explorer<br><b>Note:</b> Two files need to be modified to enable this - See readme.', 333, 18, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(3213, 'Use Resampling', 'CFG_USE_RESAMPLING', 'True', 'If selected, thumbnails will be resampled rather than resized. Resampling creates much higher quality thumbnails.', 333, 21, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(3214, 'Create Truecolour Thumbnails', 'CFG_CREATE_TRUECOLOR', 'True', 'Create True color Thumbnails? Better quality overall but set to false if you have GD version < 2.01 or if creating transparent thumbnails.', 333, 24, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(3215, 'Output GIFs as JPEGs', 'CFG_GIFS_AS_JPEGS', 'False', 'Set this option true if you have GD version > 1.6 and want to output GIFs as JPGs. Note that transparencies will not be retained (set matte colour below). If you have GD Library < 1.6 with GIF create support, GIFs will be output as GIFs.', 333, 27, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(3220, '''GIF as JPEG'' Matte colour (HEX)', 'CFG_MATTE_COLOR', 'FFFFFF', 'Enter the HEX colour value that transparent backgrounds will be converted to if GIFs output as JPGs', 333, 28, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3216, 'Cache Thumbnails on the Server', 'CFG_TN_SERVER_CACHE', 'True', 'Set to true if you want to cache previously processed thumbnails on disk. This will add to disk space but will save your processor from having to create a new thumbnail for every visitor. (recommended)', 333, 30, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(3219, 'Cache Thumbnails in user''s browser', 'CFG_TN_BROWSER_CACHE', 'True', 'Set to true if you want browsers to be able to cache viewed thumbnails in their own cache. This will save bandwidth for every visitor that views the same thumbnail again. (recommended)', 333, 31, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(3217, 'Thumbnail Cache directory', 'CFG_TN_CACHE_DIRECTORY', '/thumbnails', 'Directory where cached thumbnails will be stored. <br><b>Note:</b> This directory should be automatically created, if not, do so manually and chmod it to 777', 333, 33, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3218, 'Use 404 Response if image not found?', 'CFG_USE_404', 'True', 'If set to true a 404 (not found) response will be sent (broken image), otherwise a small error picture will be shown', 333, 36, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(3221, 'Allow thumbnails larger than original', 'CFG_ALLOW_LARGER', 'True', 'Set to true if you want to allow scaling UP of source image files.', 333, 45, '2008-11-23 03:31:06', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(3271, 'Center if thumbnail larger than original', 'CFG_CENTER_THUMB', 'False', 'If your source is smaller than the thumbnail, should it be centered on the larger thumbnail rather than resized?<br><b>Note:</b> ''Allow thumbnails larger than original'' (above) must be set to true', 333, 46, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(3222, 'JPEG Quality - Pop-up Images', 'POPUP_JPEG_QUALITY', '100', 'The output quality of JPEG pop-up images.', 333, 48, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3223, 'JPEG Quality - Product Information Thumbnails', 'PRODUCT_JPEG_QUALITY', '100', 'The output quality of JPEG thumbnails displayed on your product information page', 333, 51, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3224, 'JPEG Quality - Category Thumbnails', 'CATEGORY_JPEG_QUALITY', '100', 'The output quality of category JPEG thumbnails', 333, 52, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3265, 'JPEG Quality - Heading Thumbnails', 'HEADING_JPEG_QUALITY', '100', 'The output quality of heading JPEG thumbnails', 333, 53, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3260, 'JPEG Quality - Small Thumbnails', 'SMALL_JPEG_QUALITY', '100', 'The output quality of your small sized JPEG thumbnails', 333, 55, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3225, 'Graphic Watermark in Pop-up Images', 'USE_WATERMARK_IMAGE_POPUP', 'Yes', 'Do you wish to use a watermark image in your pop-up product image?<br><b>Note:</b>Graphic Watermarks will NOT be added to GIF images', 333, 57, '2010-01-07 02:48:59', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3226, 'Graphic Watermark in Product Information Thumbnails', 'USE_WATERMARK_IMAGE_PRODUCT', 'Yes', 'Do you wish to use a watermark image in your product information thumbnails?<br><b>Note:</b>Graphic Watermarks will NOT be added to GIF images', 333, 58, '2010-01-07 02:49:06', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3227, 'Graphic Watermark in Category Thumbnails', 'USE_WATERMARK_IMAGE_CATEGORY', 'No', 'Do you wish to use a watermark image in your category thumbnails?<br><b>Note:</b>Graphic Watermarks will NOT be added to GIF images', 333, 59, '2008-09-17 03:50:56', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3266, 'Graphic Watermark in Heading Thumbnails', 'USE_WATERMARK_IMAGE_HEADING', 'No', 'Do you wish to use a watermark image in your heading thumbnails?<br><b>Note:</b>Graphic Watermarks will NOT be added to GIF images', 333, 60, '2010-01-07 02:53:12', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3261, 'Graphic Watermark in Small Thumbnails', 'USE_WATERMARK_IMAGE_SMALL', 'No', 'Do you wish to use a watermark image in your small thumbnails<br><b>Note:</b>Graphic Watermarks will NOT be added to GIF images?', 333, 61, '2009-05-16 03:27:25', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3228, 'Watermark Image File', 'WATERMARK_IMAGE', 'logo.png', 'Select which watermark image you wish to use<br><br>New watermark images may be installed in your:<br><b>/catalog/includes/imagemagic/watermarks/</b><br>directory<br>', 333, 66, '2009-04-26 23:00:48', '2005-01-06 20:24:30', '', 'tep_cfg_pull_down_installed_watermarks(');
INSERT INTO `configuration` VALUES(3229, 'Image Watermark Transparency', 'WATERMARK_IMAGE_OPACITY', '10', 'Enter a value of 0 to 100 to set the opacity value of your watermark image (0=transparent, 100=opaque)', 333, 69, '2010-01-07 02:52:44', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3230, 'Image Watermark Position', 'WATERMARK_IMAGE_POSITION', 'Center', 'Select where you would like your watermark image to be positioned on your thumbnail', 333, 71, '2010-01-07 02:52:14', '2005-01-06 20:24:30', '', 'tep_cfg_pull_down_watermark_alignment(');
INSERT INTO `configuration` VALUES(3231, 'Image Watermark Margin', 'WATERMARK_IMAGE_MARGIN', '0', 'Enter the offset in pixels where you would like your watermark image to be positioned', 333, 72, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3270, 'Resize Watermark Image', 'CFG_RESIZE_WATERMARK', 'True', 'If selected, your watermark image will be resized in the same ratio as your source image, otherwise the watermark image will always be added full-sized', 333, 73, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(3232, 'Text Watermark in Pop-up Images', 'USE_WATERMARK_TEXT_POPUP', 'Yes', 'Do you wish to use watermark text in your pop-up product images?<br><b>Note:</b>Text Watermarks do not work well with GIF images', 333, 75, '2009-03-20 14:32:39', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3233, 'Text Watermark in Product Information Thumbnails', 'USE_WATERMARK_TEXT_PRODUCT', 'No', 'Do you wish to use watermark text in your product information thumbnails?<br><b>Note:</b>Text Watermarks do not work well with GIF images', 333, 76, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3234, 'Text Watermark in Category Thumbnails', 'USE_WATERMARK_TEXT_CATEGORY', 'Yes', 'Do you wish to use watermark text in your category thumbnails?<br><b>Note:</b>Text Watermarks do not work well with GIF images', 333, 77, '2009-03-20 14:33:26', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3267, 'Text Watermark in Heading Thumbnails', 'USE_WATERMARK_TEXT_HEADING', 'No', 'Do you wish to use watermark text in your heading thumbnails?<br><b>Note:</b>Text Watermarks do not work well with GIF images', 333, 78, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3262, 'Text Watermark in Small Thumbnails', 'USE_WATERMARK_TEXT_SMALL', 'No', 'Do you wish to use watermark text in your small thumbnails?<br><b>Note:</b>Text Watermarks do not work well with GIF images', 333, 79, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3235, 'Watermark Text', 'WATERMARK_TEXT', 'www.toneronsell.com', 'Enter the text you wish to appear in your thumbnails as a watermark', 333, 84, '2009-03-20 14:32:18', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3236, 'Text Watermark Font Name', 'WATERMARK_TEXT_FONT', 'arial.ttf', 'Select the font filename you wish to use for watermark text<br><br>New TTF fonts may be installed in your:<br><b>/catalog/includes/imagemagic/fonts/</b><br>directory<br>', 333, 87, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_pull_down_installed_fonts(');
INSERT INTO `configuration` VALUES(3237, 'Text Watermark Size', 'WATERMARK_TEXT_SIZE', '10', 'Enter the font point size of your text watermark', 333, 90, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3238, 'Text Watermark Colour (HEX)', 'WATERMARK_TEXT_COLOR', '000000', 'Enter the hex value for the colour of your text watermark', 333, 93, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3239, 'Text Watermark Transparency', 'WATERMARK_TEXT_OPACITY', '20', 'Enter a value of 0 to 100 to set the transparency value of your watermark text (0=transparent, 100=opaque)', 333, 96, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3240, 'Text Watermark Position', 'WATERMARK_TEXT_POSITION', 'Top', 'Select where you would like your watermark text to be positioned on your thumbnails', 333, 99, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_pull_down_watermark_alignment(');
INSERT INTO `configuration` VALUES(3241, 'Text Watermark Margin', 'WATERMARK_TEXT_MARGIN', '0', 'Enter the offset in pixels where you would like your watermark text to be positioned', 333, 102, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3242, 'Text Watermark Angle', 'WATERMARK_TEXT_ANGLE', '0', 'Enter the counter-clockwise angle of the text watermark', 333, 105, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3243, 'Auto Adjust Brightness', 'BRIGHTNESS_ADJUST', '0', 'Enter an amount between -255 and 255 which your thumbnail brightness will be adjusted by<br><b>Note:</b> This will not work with GIF images', 333, 108, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3244, 'Auto Adjust Contrast', 'CONTRAST_ADJUST', '0', 'Enter an amount between -255 and 255 which your thumbnail contrast will be adjusted by<br><b>Note:</b> This will not work with GIF images', 333, 111, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3245, 'Frame Pop-up Images', 'FRAME_POPUP', 'No', 'Do you want to include a frame around your pop-up product images?', 333, 114, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3246, 'Frame Product Information Thumbnails', 'FRAME_PRODUCT', 'No', 'Do you want to include a frame around your product information thumbnails?', 333, 115, '2006-11-12 17:59:59', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3247, 'Frame Category Thumbnails', 'FRAME_CATEGORY', 'No', 'Do you want to include a frame around your category thumbnails?', 333, 116, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3268, 'Frame Heading Thumbnails', 'FRAME_HEADING', 'No', 'Do you want to include a frame around your heading thumbnails?', 333, 117, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3263, 'Frame Small Thumbnails', 'FRAME_SMALL', 'No', 'Do you want to include a frame around your small thumbnails?', 333, 118, '2006-09-29 17:28:11', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3248, 'Frame Width', 'FRAME_WIDTH', '1', 'Enter the width in pixels of the thumbnail frame', 333, 120, '2006-09-29 17:26:04', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3249, 'Frame Depth', 'FRAME_EDGE_WIDTH', '0', 'Enter the 3D depth of the frame in pixels', 333, 123, '2006-08-18 15:37:56', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3250, 'Frame Colour (HEX)', 'FRAME_COLOR', '000000', 'Enter the HEX colour of the thumbnail frame', 333, 126, '2006-09-29 17:26:46', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3251, 'Frame Inside 3D Highlight Colour (HEX)', 'FRAME_INSIDE_COLOR1', 'FFFFFF', 'Enter the colour (in hex) you wish the frame''s inside higlight colour to be', 333, 129, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3252, 'Frame Inside 3D Shadow Colour (HEX)', 'FRAME_INSIDE_COLOR2', 'ffffff', 'Enter the colour (in hex) you wish the frame''s inside shadow colour to be', 333, 132, '2006-09-29 17:27:04', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3253, 'Buttonize Pop-up Images', 'BEVEL_POPUP', 'No', 'Do you want to add a 3D button effect to your pop-up images?', 333, 133, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3254, 'Buttonize Product Information Thumbnails', 'BEVEL_PRODUCT', 'No', 'Do you want to add a 3D button effect to your product information thumbnails?', 333, 134, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3255, 'Buttonize Category Thumbnails', 'BEVEL_CATEGORY', 'No', 'Do you want to add a 3D button effect to your category thumbnails?', 333, 135, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3269, 'Buttonize Heading Thumbnails', 'BEVEL_HEADING', 'No', 'Do you want to add a 3D button effect to your heading thumbnails?', 333, 136, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3264, 'Buttonize Small Thumbnails', 'BEVEL_SMALL', 'No', 'Do you want to add a 3D button effect to your small thumbnails?', 333, 137, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(3256, 'Button Height', 'BEVEL_HEIGHT', '4', 'Enter the height in pixels of the 3D button effect', 333, 144, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3257, 'Button Highlight Colour (HEX)', 'BEVEL_HIGHLIGHT', 'CCCCCC', 'Enter the colour (in hex) you wish the button''s higlight colour to be', 333, 147, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3258, 'Button Shadow Colour (HEX)', 'BEVEL_SHADOW', '000000', 'Enter the colour (in hex) you wish the button''s shadow colour to be', 333, 150, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', '');
INSERT INTO `configuration` VALUES(3259, 'Last Hash (System Use - Read Only)', 'LAST_HASH', '580e8530cdd7840bd1993532fe297918', 'Stores the last hash value of the thumbnail configuration settings.  This will allow the script to detect when they have changed and maintain the cache.', 333, 153, '0000-00-00 00:00:00', '2005-01-06 20:24:30', '', 'tep_cfg_readonly(');
INSERT INTO `configuration` VALUES(3280, 'Froogle FTP username', 'FROOGLE_FTP_USER', 'debug@strongcode.net', 'Froogle FTP username', 62, 1, '2007-08-29 20:52:44', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(3281, 'Froogle FTP password', 'FROOGLE_FTP_PASS', '0000000', 'Froogle FTP password', 62, 2, '2007-08-29 20:52:51', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(3282, 'Froogle FTP Server', 'FROOGLE_FTP_SERVER', 'uploads.google.com', 'Froogle FTP Server', 62, 3, '2006-06-18 17:50:35', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(3283, 'Froogle FTP Filename', 'FROOGLE_FTP_FILENAME', 'frooglefile.txt', 'Froogle FTP Filename', 62, 4, '2005-12-18 23:52:28', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(3284, 'BizRate username', 'BIZRATE_USER', '', 'BizRate username', 62, 5, '2006-09-12 07:17:56', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(3285, 'BizRate password', 'BIZRATE_PASS', '', 'BizRate password', 62, 6, '2006-09-12 07:18:01', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(3286, 'BizRate Filename', 'BIZRATE_FILENAME', 'testbizfile.txt', 'BizRate Filename', 62, 7, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(3287, 'BizRate FTP Server', 'BIZRATE_FTP_SERVER', 'ftp.bizrate.com', 'BizRate FTP Server', 62, 8, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(3288, 'Yahoo FTP username', 'YAHOO_FTP_USER', '0000000', 'Yahoo FTP username', 62, 9, '2007-08-29 20:53:05', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(3289, 'Yahoo FTP password', 'YAHOO_FTP_PASS', '0000000000', 'Yahoo FTP password', 62, 10, '2007-08-29 20:52:59', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(3290, 'Yahoo FTP filename', 'YAHOO_FTP_FILENAME', 'yahoofile.txt', 'Yahoo FTP filename', 62, 11, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(3291, 'Yahoo FTP directory', 'YAHOO_FTP_DIRECTORY', 'youryahoodirectory', 'Yahoo FTP directory', 62, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(3292, 'Yahoo FTP server', 'YAHOO_FTP_SERVER', 'ftp.productsubmit.adcentral.yahoo.com', 'Yahoo FTP server', 62, 13, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(4246, 'Enable CyberSource SOP Module', 'MODULE_PAYMENT_CYBS_STATUS', 'True', 'Do you want to accept CyberSource payments?', 6, 0, '0000-00-00 00:00:00', '2006-12-01 22:57:14', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(4253, 'Set Order Status', 'MODULE_PAYMENT_CYBS_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', 6, 0, '0000-00-00 00:00:00', '2006-12-01 22:57:14', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses(');
INSERT INTO `configuration` VALUES(4252, 'Payment Zone', 'MODULE_PAYMENT_CYBS_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', 6, 2, '0000-00-00 00:00:00', '2006-12-01 22:57:14', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(');
INSERT INTO `configuration` VALUES(4095, 'Product Info Attribute Display Plugin', 'PRODINFO_ATTRIBUTE_PLUGIN', 'multiple_dropdowns', 'The plugin used for displaying attributes on the product information page.', 888001, 1, '2009-09-05 18:41:06', '2006-10-28 03:38:53', '', 'tep_cfg_pull_down_class_files(''pad_'',');
INSERT INTO `configuration` VALUES(4250, 'Card Verification Value', 'MODULE_PAYMENT_CYBS_CVV', 'True', 'This will enable or disable the CVV field.', 6, 0, '0000-00-00 00:00:00', '2006-12-01 22:57:14', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(3319, 'Display Extra images (on products_info)', 'DISPLAY_EXTRA_IMAGES', 'true', 'Display Extra images', 1, 87, '2005-11-17 17:20:36', '2005-10-20 17:40:05', '', 'tep_cfg_select_option(array(''false'', ''true''),');
INSERT INTO `configuration` VALUES(4096, 'Show Out of Stock Attributes', 'PRODINFO_ATTRIBUTE_SHOW_OUT_OF_STOCK', 'True', 'Controls the display of out of stock attributes.', 888001, 10, '0000-00-00 00:00:00', '2006-10-28 03:38:53', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(4099, 'Prevent Adding Out of Stock to Cart', 'PRODINFO_ATTRIBUTE_NO_ADD_OUT_OF_STOCK', 'True', 'Prevents adding an out of stock attribute combination to the cart.', 888001, 40, '2007-05-27 23:19:41', '2006-10-28 03:38:53', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(4097, 'Mark Out of Stock Attributes', 'PRODINFO_ATTRIBUTE_MARK_OUT_OF_STOCK', 'Right', 'Controls how out of stock attributes are marked as out of stock.', 888001, 20, '2007-05-27 23:18:22', '2006-10-28 03:38:53', '', 'tep_cfg_select_option(array(''None'', ''Right'', ''Left''),');
INSERT INTO `configuration` VALUES(4098, 'Display Out of Stock Message Line', 'PRODINFO_ATTRIBUTE_OUT_OF_STOCK_MSGLINE', 'True', 'Controls the display of a message line indicating an out of stock attributes is selected.', 888001, 30, '2006-10-28 06:17:52', '2006-10-28 03:38:53', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(3528, 'Use template for index page', 'MODULE_STS_INDEX_STATUS', 'true', 'Do you want to use templates for index page?', 6, 1, '0000-00-00 00:00:00', '2006-09-24 12:27:57', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(3529, 'Files for index.php template', 'MODULE_STS_INDEX_NORMAL', 'sts_user_code.php;headertags.php', 'Files to include for an index.php template, separated by semicolon', 6, 2, '0000-00-00 00:00:00', '2006-09-24 12:27:57', '', '');
INSERT INTO `configuration` VALUES(4024, 'Enable Points system', 'USE_POINTS_SYSTEM', 'true', 'Enable the system so customers can earn points for orders made?', 22, 1, '2009-04-27 01:21:51', '2006-09-30 19:26:32', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(555130, 'Sort order of display.', 'MODULE_PAYMENT_CCERR_SORT_ORDER', '99', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2012-07-03 19:29:24', NULL, NULL);
INSERT INTO `configuration` VALUES(4251, 'Sort order of display.', 'MODULE_PAYMENT_CYBS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, '0000-00-00 00:00:00', '2006-12-01 22:57:14', '', '');
INSERT INTO `configuration` VALUES(3910, 'Debug Email Notifications', 'MODULE_PAYMENT_PAYPAL_IPN_DEBUG', 'Yes', 'Enable debug email notifications', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', '', 'tep_cfg_select_option(array(''Yes'',''No''), ');
INSERT INTO `configuration` VALUES(3911, 'Digest Key', 'MODULE_PAYMENT_PAYPAL_IPN_DIGEST_KEY', 'PayPal_Shopping_Cart_IPN', 'Key to use for the digest functionality', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', '', '');
INSERT INTO `configuration` VALUES(3912, 'Test Mode', 'MODULE_PAYMENT_PAYPAL_IPN_TEST_MODE', 'Off', 'Set test mode <a style="color: #0033cc;" href="http://www.mineralchics.com/admin/FILENAME_PAYPAL?action=itp" target="ipn">[IPN Test Panel]</a>', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', '', 'tep_cfg_select_option(array(''Off'',''On''), ');
INSERT INTO `configuration` VALUES(3913, 'Cart Test', 'MODULE_PAYMENT_PAYPAL_IPN_CART_TEST', 'On', 'Set cart test mode to verify the transaction amounts', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', '', 'tep_cfg_select_option(array(''Off'',''On''), ');
INSERT INTO `configuration` VALUES(3915, 'PayPal Domain', 'MODULE_PAYMENT_PAYPAL_DOMAIN', 'www.paypal.com', 'Select which PayPal domain to use<br>(for live production select www.paypal.com)', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', '', 'tep_cfg_select_option(array(''www.paypal.com'',''www.sandbox.paypal.com''), ');
INSERT INTO `configuration` VALUES(3916, 'Return URL behavior', 'MODULE_PAYMENT_PAYPAL_RM', '1', 'How should the customer be sent back from PayPal to the specified URL?<br>0=No IPN, 1=GET, 2=POST', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', '', 'tep_cfg_select_option(array(''0'',''1'',''2''), ');
INSERT INTO `configuration` VALUES(4233, 'Card Types', 'MODULE_PAYMENT_CYBS_CARD_TYPES', '111100', 'Accepted card types', 6, 0, '0000-00-00 00:00:00', '2006-11-20 15:26:36', 'cybs_display_card_types', 'tep_cybs_card_options(array(''Visa'', ''MasterCard'', ''American Express'', ''Discover'', ''Diners Club'', ''JCB''), ');
INSERT INTO `configuration` VALUES(3898, 'Set On Hold Order Status', 'MODULE_PAYMENT_PAYPAL_ORDER_ONHOLD_STATUS_ID', '1', 'Set the status of <b>On Hold</b> orders made with this payment module to this value', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses(');
INSERT INTO `configuration` VALUES(3909, 'Enable PayPal Shipping Address', 'MODULE_PAYMENT_PAYPAL_SHIPPING_ALLOWED', 'No', 'Allow the customer to choose their own PayPal shipping address?', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', '', 'tep_cfg_select_option(array(''Yes'',''No''), ');
INSERT INTO `configuration` VALUES(3908, 'Shopping Cart Method', 'MODULE_PAYMENT_PAYPAL_METHOD', 'Aggregate', 'What type of shopping cart do you want to use?', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', '', 'tep_cfg_select_option(array(''Aggregate'',''Itemized''), ');
INSERT INTO `configuration` VALUES(3899, 'Set Canceled Order Status', 'MODULE_PAYMENT_PAYPAL_ORDER_CANCELED_STATUS_ID', '1', 'Set the status of <b>Canceled</b> orders made with this payment module to this value', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses(');
INSERT INTO `configuration` VALUES(3900, 'Synchronize Invoice', 'MODULE_PAYMENT_PAYPAL_INVOICE_REQUIRED', 'False', 'Do you want to specify the order number as the PayPal invoice number?', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(4248, 'Transaction Mode', 'MODULE_PAYMENT_CYBS_MODE', 'Production', 'Transaction mode used for processing orders', 6, 0, '0000-00-00 00:00:00', '2006-12-01 22:57:14', '', 'tep_cfg_select_option(array(''Test'', ''Production'', ''Debug Test'', ''Debug Production''), ');
INSERT INTO `configuration` VALUES(3902, 'Set Refunded Order Status', 'MODULE_PAYMENT_PAYPAL_ORDER_REFUNDED_STATUS_ID', '1', 'Set the status of <b>Refunded</b> orders made with this payment module to this value', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses(');
INSERT INTO `configuration` VALUES(3903, 'Background Color', 'MODULE_PAYMENT_PAYPAL_CS', 'White', 'Select the background color of PayPal''s payment pages.', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', '', 'tep_cfg_select_option(array(''White'',''Black''), ');
INSERT INTO `configuration` VALUES(3904, 'Processing logo', 'MODULE_PAYMENT_PAYPAL_PROCESSING_LOGO', 'oscommerce.gif', 'The image file name to display the store''s checkout process', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', '', '');
INSERT INTO `configuration` VALUES(3905, 'Store logo', 'MODULE_PAYMENT_PAYPAL_STORE_LOGO', '', 'The image file name for PayPal to display (leave empty if your store does not have SSL)', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', '', '');
INSERT INTO `configuration` VALUES(3906, 'PayPal Page Style Name', 'MODULE_PAYMENT_PAYPAL_PAGE_STYLE', 'default', 'The name of the page style you have configured in your PayPal Account', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', '', '');
INSERT INTO `configuration` VALUES(3907, 'Include a note with payment', 'MODULE_PAYMENT_PAYPAL_NO_NOTE', 'No', 'Choose whether your customer should be prompted to include a note or not?', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', '', 'tep_cfg_select_option(array(''Yes'',''No''), ');
INSERT INTO `configuration` VALUES(3892, 'Business ID', 'MODULE_PAYMENT_PAYPAL_BUSINESS_ID', 'strongcode@strongcode.net', 'Email address or account ID of the payment recipient', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', '', '');
INSERT INTO `configuration` VALUES(3893, 'Default Currency', 'MODULE_PAYMENT_PAYPAL_DEFAULT_CURRENCY', 'USD', 'The <b>default</b> currency to use for when the customer chooses to checkout via the store using a currency not supported by PayPal.<br />(This currency must exist in your store)', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', '', 'tep_cfg_select_option(array(''USD'',''CAD'',''EUR'',''GBP'',''JPY'',''AUD''), ');
INSERT INTO `configuration` VALUES(3671, 'To offer a gift voucher', 'NEW_SIGNUP_GIFT_VOUCHER_AMOUNT', '0', 'Please indicate the amount of the gift voucher which you want to offer a new customer.<br><br>Put 0 if you do not want to offer gift voucher.<br>', 1, 31, '2007-06-29 01:14:42', '2003-12-05 05:01:41', '', '');
INSERT INTO `configuration` VALUES(3672, 'To offer a discount coupon', 'NEW_SIGNUP_DISCOUNT_COUPON', '', 'To offer a discount coupon to a new customer, enter the code of the coupon.<br><br>Leave empty if you do not want to offer discount coupon.<BR>', 1, 32, '2007-12-04 22:51:54', '2003-12-05 05:01:41', '', '');
INSERT INTO `configuration` VALUES(3896, 'Set Pending Notification Status', 'MODULE_PAYMENT_PAYPAL_PROCESSING_STATUS_ID', '1', 'Set the Pending Notification status of orders made with this payment module to this value (''Pending'' recommended)', 6, 0, '0000-00-00 00:00:00', '2006-09-30 18:35:07', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses(');
INSERT INTO `configuration` VALUES(4249, 'Transaction Type', 'MODULE_PAYMENT_CYBS_TYPE', 'Authorization Only', 'Transaction type used for processing orders', 6, 0, '0000-00-00 00:00:00', '2006-12-01 22:57:14', '', 'tep_cfg_select_option(array(''Authorization Only'', ''Auto-Capture''), ');
INSERT INTO `configuration` VALUES(4247, 'HOP Security Script', 'MODULE_PAYMENT_CYBS_KEY', 'Installed', 'Is your HOP Security Script is installed or not?', 6, 0, '0000-00-00 00:00:00', '2006-12-01 22:57:14', 'cybs_display_hop_file', 'cybs_hop_file(');
INSERT INTO `configuration` VALUES(555121, 'Tax Class', 'MODULE_ORDER_TOTAL_COUPON_TAX_CLASS', '0', 'Use the following tax class when treating Discount Coupon as Credit Note.', 6, 0, NULL, '2012-07-03 19:28:53', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(');
INSERT INTO `configuration` VALUES(555120, 'Re-calculate Tax', 'MODULE_ORDER_TOTAL_COUPON_CALC_TAX', 'Credit Note', 'Re-Calculate Tax', 6, 7, NULL, '2012-07-03 19:28:53', NULL, 'tep_cfg_select_option(array(''None'', ''Standard'', ''Credit Note''), ');
INSERT INTO `configuration` VALUES(555119, 'Include Tax', 'MODULE_ORDER_TOTAL_COUPON_INC_TAX', 'true', 'Include Tax in calculation.', 6, 6, NULL, '2012-07-03 19:28:53', NULL, 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(555117, 'Sort Order', 'MODULE_ORDER_TOTAL_COUPON_SORT_ORDER', '5', 'Sort order of display.', 6, 2, NULL, '2012-07-03 19:28:53', NULL, NULL);
INSERT INTO `configuration` VALUES(555118, 'Include Shipping', 'MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING', 'false', 'Include Shipping in calculation', 6, 5, NULL, '2012-07-03 19:28:53', NULL, 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(555129, 'Credit including Tax', 'MODULE_ORDER_TOTAL_GV_CREDIT_TAX', 'true', 'Add tax to purchased Gift Voucher when crediting to Account', 6, 8, NULL, '2012-07-03 19:29:01', NULL, 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(555128, 'Tax Class', 'MODULE_ORDER_TOTAL_GV_TAX_CLASS', '0', 'Use the following tax class when treating Gift Voucher as Credit Note.', 6, 0, NULL, '2012-07-03 19:29:01', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(');
INSERT INTO `configuration` VALUES(555127, 'Re-calculate Tax', 'MODULE_ORDER_TOTAL_GV_CALC_TAX', 'Credit Note', 'Re-Calculate Tax', 6, 7, NULL, '2012-07-03 19:29:01', NULL, 'tep_cfg_select_option(array(''None'', ''Standard'', ''Credit Note''), ');
INSERT INTO `configuration` VALUES(555126, 'Include Tax', 'MODULE_ORDER_TOTAL_GV_INC_TAX', 'true', 'Include Tax in calculation.', 6, 6, NULL, '2012-07-03 19:29:01', NULL, 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(555125, 'Include Shipping', 'MODULE_ORDER_TOTAL_GV_INC_SHIPPING', 'false', 'Include Shipping in calculation', 6, 5, NULL, '2012-07-03 19:29:01', NULL, 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(555124, 'Queue Purchases', 'MODULE_ORDER_TOTAL_GV_QUEUE', 'true', 'Do you want to queue purchases of the Gift Voucher?', 6, 3, NULL, '2012-07-03 19:29:01', NULL, 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(4389, 'Purchase without account', 'PURCHASE_WITHOUT_ACCOUNT', 'yes', 'Do you allow customers to purchase without an account?', 5, 10, '2008-03-24 07:51:10', '2007-01-17 01:19:58', '', 'tep_cfg_select_option(array(''yes'', ''no''), ');
INSERT INTO `configuration` VALUES(4390, 'Purchase without account shippingaddress', 'PURCHASE_WITHOUT_ACCOUNT_SEPARATE_SHIPPING', 'yes', 'Do you allow customers without account to create separately shipping address?', 5, 11, '0000-00-00 00:00:00', '2007-01-17 01:19:58', '', 'tep_cfg_select_option(array(''yes'', ''no''), ');
INSERT INTO `configuration` VALUES(5374, 'Enter special character conversions', 'SEO_CHAR_CONVERT_SET', '', 'This setting will convert characters.<br><br>The format <b>MUST</b> be in the form: <b>char=>conv,char2=>conv2</b>', 888004, 15, '2009-06-19 10:09:16', '2009-06-19 10:09:16', '', '');
INSERT INTO `configuration` VALUES(5373, 'Choose URL Rewrite Type', 'SEO_REWRITE_TYPE', 'Rewrite', 'Choose which SEO URL format to use.', 888004, 14, '2009-06-19 10:09:16', '2009-06-19 10:09:16', '', 'tep_cfg_select_option(array(''Rewrite''),');
INSERT INTO `configuration` VALUES(5372, 'Enable automatic redirects?', 'USE_SEO_REDIRECT', 'true', 'This will activate the automatic redirect code and send 301 headers for old to new URLs.', 888004, 13, '2009-06-19 10:09:16', '2009-06-19 10:09:16', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5371, 'Enable link directory cache?', 'USE_SEO_CACHE_LINKS', 'true', 'This will turn off caching for the link category pages.', 888004, 12, '2009-06-19 10:09:16', '2009-06-19 10:09:16', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5370, 'Enable information cache?', 'USE_SEO_CACHE_INFO_PAGES', 'true', 'This will turn off caching for the information pages.', 888004, 11, '2009-06-19 10:09:16', '2009-06-19 10:09:16', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5369, 'Enable topics cache?', 'USE_SEO_CACHE_TOPICS', 'true', 'This will turn off caching for the article topics.', 888004, 10, '2009-06-19 10:09:16', '2009-06-19 10:09:16', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5368, 'Enable articles cache?', 'USE_SEO_CACHE_ARTICLES', 'true', 'This will turn off caching for the articles.', 888004, 9, '2009-06-19 10:09:16', '2009-06-19 10:09:16', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5367, 'Enable manufacturers cache?', 'USE_SEO_CACHE_MANUFACTURERS', 'true', 'This will turn off caching for the manufacturers.', 888004, 8, '2009-06-19 10:09:16', '2009-06-19 10:09:16', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5366, 'Enable categories cache?', 'USE_SEO_CACHE_CATEGORIES', 'true', 'This will turn off caching for the categories.', 888004, 7, '2009-06-19 10:09:16', '2009-06-19 10:09:16', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5365, 'Enable product cache?', 'USE_SEO_CACHE_PRODUCTS', 'true', 'This will turn off caching for the products.', 888004, 6, '2009-06-19 10:09:16', '2009-06-19 10:09:16', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5364, 'Enable SEO cache to save queries?', 'USE_SEO_CACHE_GLOBAL', 'true', 'This is a global setting and will turn off caching completely.', 888004, 5, '2009-06-19 10:09:16', '2009-06-19 10:09:16', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5363, 'Output W3C valid URLs (parameter string)?', 'SEO_URLS_USE_W3C_VALID', 'true', 'This setting will output W3C valid URLs.', 888004, 4, '2011-03-16 23:24:04', '2009-06-19 10:09:16', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5362, 'Filter Short Words', 'SEO_URLS_FILTER_SHORT_WORDS', '3', 'This setting will filter words less than or equal to the value from the URL.', 888004, 3, '2009-06-19 10:09:16', '2009-06-19 10:09:16', '', '');
INSERT INTO `configuration` VALUES(5361, 'Add category parent to begining of URLs?', 'SEO_ADD_CAT_PARENT', 'true', 'This setting will add the category parent name to the beginning of the category URLs (i.e. - parent-category-c-1.html).', 888004, 2, '2009-06-19 10:09:16', '2009-06-19 10:09:16', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5360, 'Add cPath to product URLs?', 'SEO_ADD_CPATH_TO_PRODUCT_URLS', 'false', 'This setting will append the cPath to the end of product URLs (i.e. - some-product-p-1.html?cPath=xx).', 888004, 1, '2009-06-19 10:09:16', '2009-06-19 10:09:16', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5359, 'Enable SEO URLs?', 'SEO_ENABLED', 'true', 'Enable the SEO URLs?  This is a global setting and will turn them off completely.', 888004, 0, '2009-09-01 00:23:48', '2009-06-19 10:09:16', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4026, 'Points per 1.Dollar purchase', 'POINTS_PER_AMOUNT_PURCHASE', '1', 'No. of points awarded for each 1. Dollar spent.<br>(currency defined according to admin DEFAULT currency)', 22, 3, '2006-09-30 19:26:32', '2006-09-30 19:26:32', '', '');
INSERT INTO `configuration` VALUES(4025, 'Enable Redemptions system', 'USE_REDEEM_SYSTEM', 'true', 'Enable customers to Redeem points at checkout?', 22, 2, '2011-03-25 17:26:58', '2006-09-30 19:26:32', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4027, 'The value of 1 point when Redeemed', 'REDEEM_POINT_VALUE', '0.005', 'The value of one point.<br>(pointvalue currency defined according to admin DEFAULT currency)', 22, 4, '2007-08-29 20:50:22', '2006-09-30 19:26:32', '', '');
INSERT INTO `configuration` VALUES(4028, 'Points Decimal Places', 'POINTS_DECIMAL_PLACES', '0', 'Pad the points value this amount of decimal places', 22, 5, '2006-09-30 19:26:32', '2006-09-30 19:26:32', '', '');
INSERT INTO `configuration` VALUES(4029, 'Auto Credit Pending Points', 'POINTS_AUTO_ON', '1', 'Enable Auto Credit Pending Points and set a days period before the reward points will actually added to customers account.<br>For same day set to 0(zero).<br>To disable this option leave empty.', 22, 6, '2007-07-11 06:07:07', '2006-09-30 19:26:32', '', '');
INSERT INTO `configuration` VALUES(4030, 'Auto Expires Points', 'POINTS_AUTO_EXPIRES', '12', 'Set a month period before points will auto Expires.<br>To disable this option leave empty.', 22, 7, '2006-09-30 19:26:32', '2006-09-30 19:26:32', '', '');
INSERT INTO `configuration` VALUES(4031, 'Points Expires Auto Remainder', 'POINTS_EXPIRES_REMIND', '30', 'Enable Points Expires Auto Remainder and set the numbers of days prior points expiration for the script to run.(Auto Expires Points must be enabled)<br>To disable this option leave empty.', 22, 8, '2006-09-30 19:26:32', '2006-09-30 19:26:32', '', '');
INSERT INTO `configuration` VALUES(4032, 'Award points for shipping', 'USE_POINTS_FOR_SHIPPING', 'false', 'Enable customers to earn points for shipping fees?', 22, 9, '2006-09-30 19:26:32', '2006-09-30 19:26:32', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4033, 'Award points for Tax', 'USE_POINTS_FOR_TAX', 'false', 'Enable customers to earn points for Tax?', 22, 10, '2006-09-30 19:26:32', '2006-09-30 19:26:32', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4034, 'Award points for Specials', 'USE_POINTS_FOR_SPECIALS', 'false', 'Enable customers to earn points for items already discounted?<br>When set to false, Points awarded only on items with full price', 22, 11, '2007-06-29 17:38:20', '2006-09-30 19:26:32', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4035, 'Award points for order with redeemed points', 'USE_POINTS_FOR_REDEEMED', 'false', 'When order made with Redeemed Points. Enable customers to earn points for the amount spend other then points?<br>When set to false, customers will NOT awarded even if only part of the payment made by points.', 22, 12, '2006-10-23 23:43:19', '2006-09-30 19:26:32', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4036, 'Award points for Products Reviews', 'USE_POINTS_FOR_REVIEWS', '50', 'If you want to award points when customers add Product Review, set the points amount to be given or leave empty to disable this option', 22, 13, '2006-09-30 19:26:32', '2006-09-30 19:26:32', '', '');
INSERT INTO `configuration` VALUES(4037, 'Enable and set points for Referral System', 'USE_REFERRAL_SYSTEM', '100', 'Do you want to Enable the Referral System and award points when customers refer someone?<br>Set the amount of points to be given or leave empty to disable this option.', 22, 14, '2006-09-30 19:26:32', '2006-09-30 19:26:32', '', '');
INSERT INTO `configuration` VALUES(4038, 'Enable Products Model Restriction', 'RESTRICTION_MODEL', '', 'Restriction Products by model.<br>Set product model Allowed or leave empty to disable it.', 22, 15, '2006-09-30 19:26:32', '2006-09-30 19:26:32', '', '');
INSERT INTO `configuration` VALUES(4039, 'Enable Products ID Restriction', 'RESTRICTION_PID', '', 'Restriction Products by Product ID.<br>Set a comma separated list of Products ID Allowed or leave empty to disable it.', 22, 16, '2006-09-30 19:26:32', '2006-09-30 19:26:32', '', '');
INSERT INTO `configuration` VALUES(4040, 'Enable Categories ID Restriction', 'RESTRICTION_PATH', '', 'Restriction Products by Categories ID.<br>Set a comma separated list of Cpaths Allowed or leave empty to disable it.', 22, 17, '2006-09-30 19:26:32', '2006-09-30 19:26:32', '', '');
INSERT INTO `configuration` VALUES(4041, 'Enable Products Price Restriction', 'REDEMPTION_DISCOUNTED', 'false', 'When customers redeem points, do you want to exclude items already discounted ?<br>Redemptions enabled only on items with full price', 22, 18, '2006-09-30 19:26:32', '2006-09-30 19:26:32', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4042, 'If you wish to limit points before Redemptions, set points limit', 'POINTS_LIMIT_VALUE', '1', 'Set the No. of points nedded before they can be redeemed. set to 0 if you wish to disable it', 22, 19, '2006-12-29 20:39:53', '2006-09-30 19:26:32', '', '');
INSERT INTO `configuration` VALUES(4043, 'If you wish to limit points to be use per order, set points Max', 'POINTS_MAX_VALUE', '1000', 'Set the Maximum No. of points customer can redeem per order. to avoid points maximum limit, set to high No.', 22, 20, '2006-09-30 19:26:32', '2006-09-30 19:26:32', '', '');
INSERT INTO `configuration` VALUES(4044, 'Restrict Points Redemption For Minimum Purchase Amount', 'POINTS_MIN_AMOUNT', '', 'Enter the Minimum Purchase Amount(total cart contain) required before Redemptions enabled.<br>Leave empty for no Restriction', 22, 21, '2006-09-30 19:26:32', '2006-09-30 19:26:32', '', '');
INSERT INTO `configuration` VALUES(4045, 'New signup customers Welcome Points amount', 'NEW_SIGNUP_POINT_AMOUNT', '10', 'Set the Welcome Points amount to be auto-credited for New signup customers. set to 0 if you wish to disable it', 22, 22, '2007-07-11 06:08:20', '2006-09-30 19:26:32', '', '');
INSERT INTO `configuration` VALUES(4046, 'Maximum number of points records to display', 'MAX_DISPLAY_POINTS_RECORD', '20', 'Set the Maximum number of points records to display per page in my_points.php page', 22, 23, '2006-09-30 19:26:32', '2006-09-30 19:26:32', '', '');
INSERT INTO `configuration` VALUES(4047, 'Display Points information in Product info page', 'DISPLAY_POINTS_INFO', 'true', 'Do you want to show Points information Product info page?', 22, 24, '2007-03-16 07:37:21', '2006-09-30 19:26:32', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4048, 'Keep Records of Redeemed Points', 'DISPLAY_POINTS_REDEEMED', 'true', 'Do you want to keep records of all Points redeemed?', 22, 25, '2006-09-30 19:26:32', '2006-09-30 19:26:32', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(55055, 'Sort Order', 'MODULE_ORDER_TOTAL_REDEMPTIONS_SORT_ORDER', '741', 'Sort order of display.', 6, 2, NULL, '2011-03-25 17:28:21', NULL, NULL);
INSERT INTO `configuration` VALUES(4050, 'E-Mail Address', 'AFFILIATE_EMAIL_ADDRESS', '<debug@strongcode.net>', 'The E Mail Address for the Affiliate Program', 900, 1, '2007-08-29 20:52:26', '2006-10-01 13:29:19', '', '');
INSERT INTO `configuration` VALUES(4051, 'Affiliate Pay Per Sale Payment % Rate', 'AFFILIATE_PERCENT', '10.0000', 'Percentage Rate for the Affiliate Program', 900, 2, '0000-00-00 00:00:00', '2006-10-01 13:29:19', '', '');
INSERT INTO `configuration` VALUES(4052, 'Payment Threshold', 'AFFILIATE_THRESHOLD', '50.00', 'Payment Threshold for paying affiliates', 900, 3, '0000-00-00 00:00:00', '2006-10-01 13:29:19', '', '');
INSERT INTO `configuration` VALUES(4053, 'Cookie Lifetime', 'AFFILIATE_COOKIE_LIFETIME', '7200', 'How long does the click count (seconds) if customer comes back', 900, 4, '0000-00-00 00:00:00', '2006-10-01 13:29:19', '', '');
INSERT INTO `configuration` VALUES(4054, 'Billing Time', 'AFFILIATE_BILLING_TIME', '90', 'Orders billed must be at least "30" days old.<br>This is needed if a order is refunded', 900, 5, '2006-12-29 22:26:38', '2006-10-01 13:29:19', '', '');
INSERT INTO `configuration` VALUES(4055, 'Order Min Status', 'AFFILIATE_PAYMENT_ORDER_MIN_STATUS', '3', 'The status an order must have at least, to be billed', 900, 6, '2006-12-29 22:25:51', '2006-10-01 13:29:19', '', '');
INSERT INTO `configuration` VALUES(4056, 'Pay Affiliates with check', 'AFFILIATE_USE_CHECK', 'true', 'Pay Affiliates with check', 900, 7, '0000-00-00 00:00:00', '2006-10-01 13:29:19', '', 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(4057, 'Pay Affiliates with PayPal', 'AFFILIATE_USE_PAYPAL', 'true', 'Pay Affiliates with PayPal', 900, 8, '0000-00-00 00:00:00', '2006-10-01 13:29:19', '', 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(4058, 'Pay Affiliates by Bank', 'AFFILIATE_USE_BANK', 'false', 'Pay Affiliates by Bank', 900, 9, '2006-12-29 20:29:47', '2006-10-01 13:29:19', '', 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(4059, 'Individual Affiliate Percentage', 'AFFILATE_INDIVIDUAL_PERCENTAGE', 'true', 'Allow per Affiliate provision', 900, 10, '0000-00-00 00:00:00', '2006-10-01 13:29:19', '', 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(4060, 'Use Affiliate-tier', 'AFFILATE_USE_TIER', 'false', 'Multilevel Affiliate provisions', 900, 11, '2006-12-29 22:26:10', '2006-10-01 13:29:19', '', 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(4061, 'Number of Tierlevels', 'AFFILIATE_TIER_LEVELS', '0', 'Number of Tierlevels', 900, 12, '0000-00-00 00:00:00', '2006-10-01 13:29:19', '', '');
INSERT INTO `configuration` VALUES(4062, 'Percentage Rate for the Tierlevels', 'AFFILIATE_TIER_PERCENTAGE', '8.00;5.00;1.00', 'Percent Rates for the tierlevels<br>Example: 8.00;5.00;1.00', 900, 13, '0000-00-00 00:00:00', '2006-10-01 13:29:19', '', '');
INSERT INTO `configuration` VALUES(4063, 'Affiliate News', 'MAX_DISPLAY_AFFILIATE_NEWS', '3', 'Maximum number of items to display on the Affiliate News page', 900, 14, '0000-00-00 00:00:00', '2006-10-01 13:29:19', '', '');
INSERT INTO `configuration` VALUES(4799, '# of products as special in module (Side)', 'PWNO_OF_SPECIAL_DISPLAY', '2', 'How many special products to show at leftbar', 3, 12, '2012-01-26 23:33:34', '2007-08-01 10:16:42', '', '');
INSERT INTO `configuration` VALUES(4310, 'Display Add Multiples column', 'PRODUCT_LIST_MULTIPLE', '0', 'Do you want to display the Multiple Quantity with Attributes column?', 8, 11, '2008-05-23 04:15:49', '2006-12-18 16:47:26', '', '');
INSERT INTO `configuration` VALUES(4311, 'Display Add Multiples with Buy Now column', 'PRODUCT_LIST_BUY_NOW_MULTIPLE', '0', 'Do you want to display the Multiple Quantity Buy Now with Attributes column?', 8, 12, '2008-05-07 15:22:37', '2006-12-18 16:47:26', '', '');
INSERT INTO `configuration` VALUES(4312, 'Number of column per row', 'PRODUCT_LIST_NUMCOL', '2', 'How many columns per row to display?', 8, 13, '2008-05-23 04:27:02', '2006-12-18 16:47:26', '', '');
INSERT INTO `configuration` VALUES(4416, 'Header Text', 'FAMILY_HEADER_TEXT', 'Related Items', 'The text that will appear as the header of the Family Products v3.0 module.', 16, 1, '2007-01-18 18:27:40', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(4417, 'Family Header Format', 'FAMILY_HEADER_FORMAT', 'Family Text', 'Please choose whether your headers of your families will be your default text or the actual name of the family.', 16, 2, '2003-10-13 22:56:28', '0000-00-00 00:00:00', '', 'tep_cfg_select_option(array(''Family Text'', ''Family Name''),');
INSERT INTO `configuration` VALUES(4418, 'Family Display Type', 'FAMILY_DISPLAY_TYPE', 'Box', 'Please choose whether you would like to display an infoBox or a list.', 16, 3, '2007-01-18 18:28:37', '0000-00-00 00:00:00', '', 'tep_cfg_select_option(array(''Box'', ''List'', ''None''),');
INSERT INTO `configuration` VALUES(4419, 'Family Display Format', 'FAMILY_DISPLAY_FORMAT', 'Seperate', 'Please choose whether you would like to randomly select products frm all matching families, or if you would like to display seperate families.', 16, 4, '2007-01-18 18:22:00', '0000-00-00 00:00:00', '', 'tep_cfg_select_option(array(''Random'', ''Seperate''),');
INSERT INTO `configuration` VALUES(4448, 'Allow Category Descriptions', 'ALLOW_CATEGORY_DESCRIPTIONS', 'true', 'Allow use of full text descriptions for categories', 1, 19, '0000-00-00 00:00:00', '2007-02-06 04:43:47', '', 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(4449, 'Use Images for Product Options?', 'OPTIONS_AS_IMAGES_ENABLED', 'true', 'Do you wish to enable images for options?', 735, 1, '2009-09-05 18:24:47', '0000-00-00 00:00:00', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4450, 'Maximum number of images per row', 'OPTIONS_IMAGES_NUMBER_PER_ROW', '4', 'Enter the maximum number of images shown per row', 735, 2, '2007-03-19 04:01:13', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(4451, 'Options as Images - Image Width', 'OPTIONS_IMAGES_WIDTH', '50', 'Set width of option value images', 735, 3, '2007-02-13 03:13:21', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(4452, 'Options as Images - Image Height', 'OPTIONS_IMAGES_HEIGHT', '20', 'Options Images Height', 735, 4, '2007-02-13 03:13:08', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(4453, 'Click to Enlarge Function', 'OPTIONS_IMAGES_CLICK_ENLARGE', 'true', 'Do you wish to enable the Click to Enlarge Function?', 735, 5, '2003-08-21 12:59:58', '0000-00-00 00:00:00', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4489, 'Display New Articles Link', 'DISPLAY_NEW_ARTICLES', 'true', 'Display a link to New Articles in the Articles box?', 456, 1, '2007-03-07 09:56:04', '2007-03-07 09:56:04', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4490, 'Number of Days Display New Articles', 'NEW_ARTICLES_DAYS_DISPLAY', '30', 'The number of days to display New Articles?', 456, 2, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', '');
INSERT INTO `configuration` VALUES(4491, 'Maximum New Articles Per Page', 'MAX_NEW_ARTICLES_PER_PAGE', '10', 'The maximum number of New Articles to display per page<br>(New Articles page)', 456, 3, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', '');
INSERT INTO `configuration` VALUES(4492, 'Display All Articles Link', 'DISPLAY_ALL_ARTICLES', 'true', 'Display a link to All Articles in the Articles box?', 456, 4, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4493, 'Maximum Articles Per Page', 'MAX_ARTICLES_PER_PAGE', '10', 'The maximum number of Articles to display per page<br>(All Articles and Topic/Author pages)', 456, 5, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', '');
INSERT INTO `configuration` VALUES(4494, 'Maximum Display Upcoming Articles', 'MAX_DISPLAY_UPCOMING_ARTICLES', '5', 'Maximum number of articles to display in the Upcoming Articles module', 456, 6, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', '');
INSERT INTO `configuration` VALUES(4495, 'Enable Article Reviews', 'ENABLE_ARTICLE_REVIEWS', 'false', 'Enable registered users to review articles?', 456, 7, '2007-03-16 04:16:29', '2007-03-07 09:56:05', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4496, 'Enable Tell a Friend About Article', 'ENABLE_TELL_A_FRIEND_ARTICLE', 'false', 'Enable Tell a Friend option in the Article Information page?', 456, 8, '2007-03-16 04:17:09', '2007-03-07 09:56:05', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4497, 'Minimum Number Cross-Sell Products', 'MIN_DISPLAY_ARTICLES_XSELL', '1', 'Minimum number of products to display in the articles Cross-Sell listing.', 456, 9, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', '');
INSERT INTO `configuration` VALUES(4498, 'Maximum Number Cross-Sell Products', 'MAX_DISPLAY_ARTICLES_XSELL', '6', 'Maximum number of products to display in the articles Cross-Sell listing.', 456, 10, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', '');
INSERT INTO `configuration` VALUES(4499, 'Show Article Counts', 'SHOW_ARTICLE_COUNTS', 'true', 'Count recursively how many articles are in each topic', 456, 11, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4500, 'Maximum Length of Author Name', 'MAX_DISPLAY_AUTHOR_NAME_LEN', '20', 'The maximum length of the author''s name for display in the Author box', 456, 12, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', '');
INSERT INTO `configuration` VALUES(4501, 'Authors List Style', 'MAX_DISPLAY_AUTHORS_IN_A_LIST', '1', 'Used in Authors box. When the number of authors exceeds this number, a drop-down list will be displayed instead of the default list', 456, 13, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', '');
INSERT INTO `configuration` VALUES(4502, 'Authors Select Box Size', 'MAX_AUTHORS_LIST', '1', 'Used in Authors box. When this value is 1 the classic drop-down list will be used for the authors box. Otherwise, a list-box with the specified number of rows will be displayed.', 456, 14, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', '');
INSERT INTO `configuration` VALUES(4503, 'Display Author in Article Listing', 'DISPLAY_AUTHOR_ARTICLE_LISTING', 'true', 'Display the Author in the Article Listing?', 456, 15, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4504, 'Display Topic in Article Listing', 'DISPLAY_TOPIC_ARTICLE_LISTING', 'true', 'Display the Topic in the Article Listing?', 456, 16, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4505, 'Display Abstract in Article Listing', 'DISPLAY_ABSTRACT_ARTICLE_LISTING', 'true', 'Display the Abstract in the Article Listing?', 456, 17, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4506, 'Display Date Added in Article Listing', 'DISPLAY_DATE_ADDED_ARTICLE_LISTING', 'true', 'Display the Date Added in the Article Listing?', 456, 18, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4507, 'Maximum Article Abstract Length', 'MAX_ARTICLE_ABSTRACT_LENGTH', '300', 'Sets the maximum length of the Article Abstract to be displayed<br><br>(No. of characters)', 456, 19, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', '');
INSERT INTO `configuration` VALUES(4508, 'Display Topic/Author Filter', 'ARTICLE_LIST_FILTER', 'true', 'Do you want to display the Topic/Author Filter?', 456, 20, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4509, 'Location of Prev/Next Navigation Bar', 'ARTICLE_PREV_NEXT_BAR_LOCATION', 'both', 'Sets the location of the Previous/Next Navigation Bar<br><br>(top; bottom; both)', 456, 21, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', 'tep_cfg_select_option(array(''top'', ''bottom'', ''both''),');
INSERT INTO `configuration` VALUES(4510, 'Display Box Authors ?', 'AUTHOR_BOX_DISPLAY', 'true', 'Display the Author box in the destination column', 456, 22, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4511, 'Display Box Articles ?', 'ARTICLE_BOX_DISPLAY', 'true', 'Display the Articles box in the destination column', 456, 23, '2007-03-07 09:56:05', '2007-03-07 09:56:05', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4512, 'USE FCKeditor ? Need exist', 'FCK_EDITOR', 'false', 'Prefer FckEdit in replacement of HTML-Area (disable HTML-Area !!! Warning !!! a contribution with FCKedit MUST be installed before!!!)', 456, 24, '2007-03-07 13:40:59', '2007-03-07 09:56:05', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(55058, 'Downloads Controller Update Status Value', 'DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE', '12', 'What orders_status resets the Download days and Max Downloads - Default is 12', 13, 90, '2003-02-18 13:22:32', '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `configuration` VALUES(55059, 'Downloads Controller Download on hold message', 'DOWNLOADS_CONTROLLER_ON_HOLD_MSG', '<BR><font color="FF0000">NOTE: Downloads are not available until payment has been confirmed</font>', 'Downloads Controller Download on hold message', 13, 91, '2011-06-03 12:54:39', '2011-06-03 12:54:39', NULL, NULL);
INSERT INTO `configuration` VALUES(55060, 'Downloads Controller Order Status Value', 'DOWNLOADS_CONTROLLER_ORDERS_STATUS', '10', 'Downloads Controller Order Status Value - Default=10', 13, 92, '2011-06-03 12:54:39', '2011-06-03 12:54:39', NULL, NULL);
INSERT INTO `configuration` VALUES(55061, 'Enable Group File Download?', 'DOWNLOADS_CONTROLLER_FILEGROUP_STATUS', 'Yes', 'Do you want to enable group file for download? - Default=Yes', 13, 93, '2011-06-03 12:55:55', '2011-06-03 12:55:55', NULL, 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(4538, 'Max Wish List', 'MAX_DISPLAY_WISHLIST_PRODUCTS', '12', 'How many wish list items to show per page on the main wishlist.php file', 12954, 0, '2007-03-18 10:38:07', '2007-03-18 10:38:07', '', '');
INSERT INTO `configuration` VALUES(4539, 'Max Wish List Box', 'MAX_DISPLAY_WISHLIST_BOX', '4', 'How many wish list items to display in the infobox before it changes to a counter', 12954, 0, '2007-03-18 10:38:07', '2007-03-18 10:38:07', '', '');
INSERT INTO `configuration` VALUES(4540, 'Display Emails', 'DISPLAY_WISHLIST_EMAILS', '3', 'How many emails to display when the customer emails their wishlist link', 12954, 0, '2007-08-29 20:51:02', '2007-03-18 10:38:07', '', '');
INSERT INTO `configuration` VALUES(4541, 'Wishlist Redirect', 'WISHLIST_REDIRECT', 'No', 'Do you want to redirect back to the product_info.php page when a customer adds a product to their wishlist?', 12954, 0, '2007-08-29 20:51:30', '2007-03-18 10:38:07', '', 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(4542, 'Title for the Link InfoBox to your site', 'LINKS_MANAGER_MYINFO_TITLE', 'Our Link-Information', 'Title for the Link InfoBox to your site', 10050, 100, '0000-00-00 00:00:00', '2007-03-19 12:05:01', '', '');
INSERT INTO `configuration` VALUES(4543, 'Link-Name Title', 'LINKS_MANAGER_LINK_NAME', 'Link:', 'Link-Name Title', 10050, 101, '0000-00-00 00:00:00', '2007-03-19 12:05:01', '', '');
INSERT INTO `configuration` VALUES(4544, 'Link-Name', 'LINKS_MANAGER_LINK_VALUE', 'My home Page', 'Link-Name', 10050, 102, '0000-00-00 00:00:00', '2007-03-19 12:05:01', '', '');
INSERT INTO `configuration` VALUES(4545, 'Link Description Title', 'LINKS_MANAGER_LINK_DESCR_NAME', 'Link Description:', 'Link Description Title', 10050, 103, '0000-00-00 00:00:00', '2007-03-19 12:05:01', '', '');
INSERT INTO `configuration` VALUES(4546, 'Link Description', 'LINKS_MANAGER_LINK_DESCR_VALUE', 'My home page sells the best products you can imagine.', 'Link Description', 10050, 104, '0000-00-00 00:00:00', '2007-03-19 12:05:01', '', '');
INSERT INTO `configuration` VALUES(4547, 'URL Title', 'LINKS_MANAGER_URL_NAME', 'URL:', 'URL Title', 10050, 105, '0000-00-00 00:00:00', '2007-03-19 12:05:01', '', '');
INSERT INTO `configuration` VALUES(4548, 'URL - The <B>complete</B> URL to your site', 'LINKS_MANAGER_URL_VALUE', 'http://www.myhomepage.com', 'URL - The <B>complete</B> URL to your site', 10050, 106, '0000-00-00 00:00:00', '2007-03-19 12:05:01', '', '');
INSERT INTO `configuration` VALUES(55062, 'Must accept when registering', 'MATC_AT_REGISTER', 'false', '<b>If true</b>, the customer must accept the Terms &amp; Conditions <b>when registrating</b>.', 73, 1, NULL, '2011-06-06 12:13:03', NULL, 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(4634, 'Order Editor- Display Payment Method dropdown?', 'DISPLAY_PAYMENT_METHOD_DROPDOWN', 'true', 'Display Payment Method in Order Editor as dropdown menu (true) or as input field (false)', 1, 21, '0000-00-00 00:00:00', '2006-04-02 11:51:01', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4635, 'Enable Additional Images?', 'ULTIMATE_ADDITIONAL_IMAGES', 'enable', 'Display Additional Images below Product Description?', 4, 10, '2007-04-03 10:02:53', '2007-04-03 10:02:53', '', 'tep_cfg_select_option(array(''enable'', ''disable''),');
INSERT INTO `configuration` VALUES(4636, 'Additional Thumb Width', 'ULT_THUMB_IMAGE_WIDTH', '140', 'The pixel width of additional thumb images', 4, 11, '2007-04-03 10:02:53', '2007-04-03 10:02:53', '', '');
INSERT INTO `configuration` VALUES(4637, 'Additional Thumb Height', 'ULT_THUMB_IMAGE_HEIGHT', '120', 'The pixel height of additional thumb images', 4, 12, '2007-04-03 10:02:53', '2007-04-03 10:02:53', '', '');
INSERT INTO `configuration` VALUES(4638, 'Medium Image Width', 'MEDIUM_IMAGE_WIDTH', '200', 'The pixel width of medium images', 4, 7, '2007-04-03 10:02:53', '2007-04-03 10:02:53', '', '');
INSERT INTO `configuration` VALUES(4639, 'Medium Image Height', 'MEDIUM_IMAGE_HEIGHT', '200', 'The pixel height of medium images', 4, 8, '2007-04-03 10:02:53', '2007-04-03 10:02:53', '', '');
INSERT INTO `configuration` VALUES(4640, 'Large Image Width', 'LARGE_IMAGE_WIDTH', '400', 'The pixel width of large images', 4, 9, '2007-04-03 10:02:53', '2007-04-03 10:02:53', '', '');
INSERT INTO `configuration` VALUES(4641, 'Large Image Height', 'LARGE_IMAGE_HEIGHT', '400', 'The pixel height of large images', 4, 10, '2007-04-03 10:02:53', '2007-04-03 10:02:53', '', '');
INSERT INTO `configuration` VALUES(4671, 'Ajax enhanced search: Search results limit', 'AJAX_ENHANCED_SEARCH_LIMIT', '25', 'Ajax enhanced search: Search results limit', 10020, 2, '0000-00-00 00:00:00', '2007-07-02 01:55:08', '', '');
INSERT INTO `configuration` VALUES(4672, 'Set "Contact Us" Email List', 'CONTACT_US_LIST', 'jasonphillips@pacificwest.com', 'On the "Contact Us" Page, set the list of email addresses , in this format: Name 1 &lt;email@address1&gt;, Name 2 &lt;email@address2&gt;', 1, 11, '2012-10-03 21:01:45', '2007-07-10 12:30:40', '', '');
INSERT INTO `configuration` VALUES(4670, 'Ajax enhanced search: enabled', 'AJAX_ENHANCED_SEARCH_ACTIVE', 'true', 'Ajax enhanced search<br>(true=enabled false=disabled)', 10020, 1, '2008-02-25 13:28:43', '2007-07-02 01:55:08', '', 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(4660, 'Enable Vendor Shipping', 'SELECT_VENDOR_SHIPPING', 'false', 'Enable Multi-Vendor shipping-(true/false)', 7, 6, '2011-06-20 16:38:52', '2004-05-04 14:43:03', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4661, 'Use Optional Confirmation Email', 'SELECT_VENDOR_EMAIL_OPTION', 'true', 'Use the email showing a seperated list of Vendor''s group of products-(true/false)', 7, 10, '2007-08-29 20:32:12', '2004-05-04 14:43:03', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(4662, 'When to send the Vendor Email', 'SELECT_VENDOR_EMAIL_WHEN', 'Both', 'Select when in the checkout process to send the email to the Vendors. You can also disable automatic email. See the readme.txt file for details on these options.', 7, 11, '2008-05-20 23:26:35', '2005-04-25 11:00:43', '', 'tep_cfg_select_option(array(''Catalog'', ''Admin'', ''Both'', ''Not at all''),');
INSERT INTO `configuration` VALUES(4791, 'MultiSocket Shipping Quotes Retrieval', 'MODULE_PAYMENT_GOOGLECHECKOUT_MULTISOCKET', 'False', 'This configuration will enable a multisocket feature to parallelize Shipping Providers quotes. This should reduce the time this call take and avoid GC Merchant Calculation TimeOut. <a href="multisock.html" target="_OUT">More Info</a>.(Alfa)', 6, 4, '0000-00-00 00:00:00', '2008-03-24 04:46:49', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(4819, 'Installed Modules', '', '', 'This is automatically updated. No need to edit.', 6, 0, '0000-00-00 00:00:00', '2008-03-24 05:24:57', '', '');
INSERT INTO `configuration` VALUES(600, 'All Products Image Width', 'ALLPROD_IMAGE_WIDTH', '100', 'The pixel width of heading images', 4, 11, '2003-07-31 19:35:01', '2003-07-24 17:45:15', '', '');
INSERT INTO `configuration` VALUES(601, 'All Products Image Height', 'ALLPROD_IMAGE_HEIGHT', '100', 'The pixel height of heading images', 4, 12, '2003-07-31 19:34:01', '2003-07-24 17:45:15', '', '');
INSERT INTO `configuration` VALUES(4906, 'All Products: ON/OFF', 'ALL_PRODUCTS', 'true', 'All Products <br>(true=on false=off)', 17, 1, '2008-04-24 11:13:57', '2008-04-24 04:57:26', '', 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(4907, 'All Products: filename', 'ALL_PRODUCTS_FILENAME', 'allprods.php', 'All Products filename Default=allprods.php', 17, 2, '0000-00-00 00:00:00', '2008-04-24 04:57:26', '', '');
INSERT INTO `configuration` VALUES(4908, 'All Products: Display Mode', 'ALL_PRODUCTS_DISPLAY_MODE', 'true', 'Display in standard table format <br>(true=on false=off)', 17, 3, '0000-00-00 00:00:00', '2008-04-24 04:57:26', '', 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(5080, 'Product option type Select', 'PRODUCTS_OPTIONS_TYPE_SELECT', '0', 'The number representing the Select type of product option.', 0, 0, '2008-06-26 21:20:38', '2008-06-26 21:20:38', '', '');
INSERT INTO `configuration` VALUES(5081, 'Text product option type', 'PRODUCTS_OPTIONS_TYPE_TEXT', '1', 'Numeric value of the text product option type', 6, 0, '2008-06-26 21:20:38', '2008-06-26 21:20:38', '', '');
INSERT INTO `configuration` VALUES(5082, 'Radio button product option type', 'PRODUCTS_OPTIONS_TYPE_RADIO', '2', 'Numeric value of the radio button product option type', 6, 0, '2008-06-26 21:20:38', '2008-06-26 21:20:38', '', '');
INSERT INTO `configuration` VALUES(5083, 'Check box product option type', 'PRODUCTS_OPTIONS_TYPE_CHECKBOX', '3', 'Numeric value of the check box product option type', 6, 0, '2008-06-26 21:20:38', '2008-06-26 21:20:38', '', '');
INSERT INTO `configuration` VALUES(5084, 'File product option type', 'PRODUCTS_OPTIONS_TYPE_FILE', '4', 'Numeric value of the file product option type', 6, 0, '2008-06-26 21:20:38', '2008-06-26 21:20:38', '', '');
INSERT INTO `configuration` VALUES(5085, 'ID for text and file oroducts options values', 'PRODUCTS_OPTIONS_VALUE_TEXT_ID', '0', 'Numeric value of the products_options_values_id used by the text and file attributes.', 6, 0, '2008-06-26 21:20:38', '2008-06-26 21:20:38', '', '');
INSERT INTO `configuration` VALUES(5086, 'Upload prefix', 'UPLOAD_PREFIX', 'upload_', 'Prefix used to differentiate between upload options and other options', 0, 0, '2008-06-26 21:20:38', '2008-06-26 21:20:38', '', '');
INSERT INTO `configuration` VALUES(5087, 'Text prefix', 'TEXT_PREFIX', 'txt_', 'Prefix used to differentiate between text option values and other option values', 0, 0, '2008-06-26 21:20:38', '2008-06-26 21:20:38', '', '');
INSERT INTO `configuration` VALUES(885, 'eBay User ID', 'EBAY_USERID', 'queen-ebabe', 'Enter your eBay User ID', 30, 1, '2009-07-21 15:46:38', '2004-09-15 04:00:47', '', '');
INSERT INTO `configuration` VALUES(886, 'Display Thumbnails', 'DISPLAY_THUMBNAILS', '1', 'Would you like to display thumbnails?<br>0=no, 1=yes', 30, 2, '2008-04-23 11:30:40', '2004-09-15 04:02:58', '', '');
INSERT INTO `configuration` VALUES(887, 'Timezone Difference', 'AUCTION_TIMEZONE', '3', 'Ebay uses Pacific time zone (PST). Use this to add or subtract hours for your local time zone.', 30, 3, '2004-09-15 04:07:37', '2004-09-15 04:07:37', '', '');
INSERT INTO `configuration` VALUES(888, 'Display Ended Auctions', 'AUCTION_ENDED', '-1', 'Display Ended Auctions<br>-1 = Current<br>1 - 30 = Up to 30 Days in the past', 30, 4, '2004-09-15 07:13:03', '2004-09-15 04:15:08', '', '');
INSERT INTO `configuration` VALUES(889, 'Sort Order', 'AUCTION_SORT', '8', 'Sort By<br>Item Number = 1<br>Auction Start = 2<br>Auction End = 3<br>Lowest Price = 4<br>Newest First = 8', 30, 5, '2008-04-23 11:46:50', '2004-09-15 04:19:38', '', '');
INSERT INTO `configuration` VALUES(890, 'How Many Auctions', 'AUCTION_DISPLAY', '15', 'How many auctions would you like to display? (Set to a very high number like 999999 for all your listings)', 30, 6, '2008-04-23 11:50:08', '2004-09-15 04:23:56', '', '');
INSERT INTO `configuration` VALUES(891, 'eBay URL', 'AUCTION_URL', 'http://cgi.ebay.com', 'This is the main request URL for ebay, you may change this for different countries.', 30, 7, '2004-09-15 04:30:45', '2004-09-15 04:28:07', '', '');
INSERT INTO `configuration` VALUES(55070, 'Textarea - Returning Code', 'MATC_TEXTAREA_RETURNING_CODE', 'TEXT_INFORMATION', 'A <b>pice of code which returns</b> the contents of the textarea. This can for example be a definition that you loaded from the languagefile.<br><br><b>Example:</b> <i>TEXT_INFORMATION</i>', 73, 9, NULL, '2011-06-06 12:13:03', NULL, '');
INSERT INTO `configuration` VALUES(55071, 'Textarea - SQL', 'MATC_TEXTAREA_SQL', '"SELECT articles_description AS thetext FROM articles_description WHERE articles_id = 131;"', 'Warning posting SQL here will result in your ip address ebing banned by CartStore''s intrusion detection system. Update this value using phpmyadmin instead. SQL should be a string and have the text aliased to "thetext".<br><br><b>Example:</b> <i>"SELECT pr', 73, 10, '2011-09-08 00:08:48', '2011-06-06 12:13:03', NULL, '');
INSERT INTO `configuration` VALUES(55072, 'Textarea - Use HTML to Plain text convertion tool?', 'MATC_TEXTAREA_HTML_2_PLAIN_TEXT_CONVERT', 'true', '<b>If true</b>, the loaded text will be converted from html <b>to plain text</b>, using this conversion tool: <a href="http://www.chuggnutt.com/html2text.php" style="color:green;">http://www.chuggnutt.com/html2text.php</a>', 73, 11, NULL, '2011-06-06 12:13:03', NULL, 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(55073, 'Disabled buttonstyle', 'MATC_BUTTONSTYLE', 'transparent', '<b><i>&quot;transparent&quot;</i></b> will work on all servers but <b><i>&quot;gray&quot;</i></b> requires php version >= 5 ', 73, 11, NULL, '2011-06-06 12:13:03', NULL, 'tep_cfg_select_option(array(''transparent'', ''gray''), ');
INSERT INTO `configuration` VALUES(55063, 'Must accept at checkout', 'MATC_AT_CHECKOUT', 'true', '<b>If true</b>, the customer must accept the Terms &amp; Conditions <b>at the order confirmation</b>.', 73, 2, '2011-06-06 12:56:17', '2011-06-06 12:13:03', NULL, 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(55064, 'Link - Show?', 'MATC_SHOW_LINK', 'true', '<b>If true</b>, a link to the Terms &amp; Conditions will be <b>displayed</b> next to the checkbox.', 73, 3, NULL, '2011-06-06 12:13:03', NULL, 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(55065, 'Link - Filename', 'MATC_FILENAME', 'article_info.php?articles_id=131', 'This is the filename of the terms and conditions. <br><br><b>Example:</b> <i>conditions.php</i>', 73, 4, '2011-09-08 00:09:15', '2011-06-06 12:13:03', NULL, '');
INSERT INTO `configuration` VALUES(55066, 'Link - Parameters', 'MATC_PARAMETERS', '', 'This is the parameters to use together with the filename in the URL. This will need to be used only when certain other contributions is installed. <br><br><b>Example:</b> <i>hello=world&foo=bar</i>', 73, 5, NULL, '2011-06-06 12:13:03', NULL, '');
INSERT INTO `configuration` VALUES(55067, 'Textarea - Show?', 'MATC_SHOW_TEXTAREA', 'true', '<b>If true</b>, the Terms &amp; Conditions will be displayed in a <b>textarea at the same page</b>.', 73, 6, NULL, '2011-06-06 12:13:03', NULL, 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(55068, 'Textarea - Languagefile Filename', 'MATC_TEXTAREA_FILENAME', 'conditions.php', 'Pick a languagefile to require. If set to nothing, nothing will be required. <br><br><b>Example:</b> <i>conditions.php</i>', 73, 7, NULL, '2011-06-06 12:13:03', NULL, '');
INSERT INTO `configuration` VALUES(55069, 'Textarea - Mode (How to get the contents)', 'MATC_TEXTAREA_MODE', 'SQL', 'Returning code will be "php-evaluated" and should return the text. SQL should be a string and have the text aliased to "thetext".<br><br><b>Default:</b> <i>Returning code</i>', 73, 8, '2011-07-19 20:57:49', '2011-06-06 12:13:03', NULL, 'tep_cfg_select_option(array(''Returning code'', ''SQL''), ');
INSERT INTO `configuration` VALUES(5154, '<font color=blue>Click Count</font>', 'ENABLE_LINKS_COUNT', 'True', 'Enable links click count.', 18, 1, '2008-06-02 15:26:52', '2008-04-19 22:49:45', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(5155, '<font color=blue>Spider Friendly Links</font>', 'ENABLE_SPIDER_FRIENDLY_LINKS', 'True', 'Enable spider friendly links (recommended).', 18, 2, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(5156, '<font color=blue>Links Image Width</font>', 'LINKS_IMAGE_WIDTH', '120', 'Maximum width of the links image.', 18, 3, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5157, '<font color=blue>Links Image Height</font>', 'LINKS_IMAGE_HEIGHT', '60', 'Maximum height of the links image.', 18, 4, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5158, '<font color=green>Display Link Image</font>', 'LINK_LIST_IMAGE', '0', 'Do you want to display the Link Image?', 18, 5, '2008-05-31 14:39:16', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5159, '<font color=green>Display Link URL</font>', 'LINK_LIST_URL', '0', 'Do you want to display the Link URL?', 18, 6, '2008-06-02 15:25:53', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5160, '<font color=green>Display Link Title</font>', 'LINK_LIST_TITLE', '2', 'Do you want to display the Link Title?', 18, 7, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5161, '<font color=green>Display Link Description</font>', 'LINK_LIST_DESCRIPTION', '3', 'Do you want to display the Link Description?', 18, 8, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5162, '<font color=green>Display Link Click Count</font>', 'LINK_LIST_COUNT', '0', 'Do you want to display the Link Click Count?', 18, 9, '2008-06-02 15:24:27', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5163, '<font color=fuchsia>Display English Links</font>', 'LINKS_DISPLAY_ENGLISH', 'True', 'Display links in this language in the shop.', 18, 10, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5164, '<font color=fuchsia>Display German Links</font>', 'LINKS_DISPLAY_GERMAN', 'False', 'Display links in this language in the shop.', 18, 11, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5165, '<font color=fuchsia>Display Spanish Links</font>', 'LINKS_DISPLAY_SPANISH', 'False', 'Display links in this language in the shop.', 18, 12, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5166, '<font color=fuchsia>Display French Links</font>', 'LINKS_DISPLAY_FRENCH', 'False', 'Display links in this language in the shop.', 18, 13, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5167, '<font color=Brown>Display Link Title as links</font>', 'TITLES_AS_LINKS', 'False', 'Make the links title a link.', 18, 14, '2008-06-02 15:22:59', '2008-04-19 22:49:45', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(5168, '<font color=Brown>Display Links Category images</font>', 'SHOW_LINKS_CATEGORIES_IMAGE', 'False', 'Display the images for the Links Categories.', 18, 15, '2008-05-31 15:07:15', '2008-04-19 22:49:45', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(5169, '<font color=Brown>Display in standard format</font>', 'LINKS_DISPLAY_FORMAT_STANDARD', 'True', 'Dislay the links in the standard format (true) or in a vertical listing (false).', 18, 16, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(5170, '<font color=Brown>Display Featured Link</font>', 'LINKS_FEATURED_LINK', 'True', 'Display a randomly selected link.', 18, 17, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(5171, '<font color=Brown>Display Links in Categories</font>', 'LINKS_SHOW_CATEGORIES', 'True', 'Use categories to show the links. If this is disabled, all links are shown on one page.', 18, 18, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(5172, '<font color=Brown>Display Link Count in Categories</font>', 'LINKS_SHOW_CATEGORIES_COUNT', 'False', 'Show the number of links in a category.', 18, 19, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(5173, '<font color=purple>Link Title Minimum Length</font>', 'ENTRY_LINKS_TITLE_MIN_LENGTH', '2', 'Minimum length of link title.', 18, 20, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5174, '<font color=purple>Link URL Minimum Length</font>', 'ENTRY_LINKS_URL_MIN_LENGTH', '10', 'Minimum length of link URL.', 18, 21, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5175, '<font color=purple>Link Description Minimum Length</font>', 'ENTRY_LINKS_DESCRIPTION_MIN_LENGTH', '10', 'Minimum length of link description.', 18, 22, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5176, '<font color=purple>Link Description Maximum Length</font>', 'ENTRY_LINKS_DESCRIPTION_MAX_LENGTH', '200', 'Maximum length of link description.', 18, 23, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5177, '<font color=purple>Link Contact Name Minimum Length</font>', 'ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH', '2', 'Minimum length of link contact name.', 18, 24, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5178, '<font color=purple>Link Maximum to Display</font>', 'MAX_LINKS_DISPLAY', '20', 'How many links should be displayed per page?', 18, 25, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5179, 'Links Blacklist', 'LINKS_CHECK_BLACKLIST', '', 'Do not allow links to be submitted if they contain these words. To enter more than one one, use a comma seperator, i.e., bad word a, bad word b.', 18, 26, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5180, 'Links Check Phrase', 'LINKS_CHECK_PHRASE', 'the velveteen rabbit', 'Phrase to look for, when you perform a link check. To enter more than one phase, use a comma seperator, i.e., phase a, phase b.', 18, 27, '2008-04-26 13:34:47', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5181, 'Check Link on Edit', 'LINKS_CHECK_ON_EDIT', 'True', 'Check if a reciprocol link is valid when Edit is clicked. This will slow down the loading of the edit page a little.', 18, 28, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(5182, 'Links open in new page', 'LINKS_OPEN_NEW_PAGE', 'True', 'Open links in new page when clicked.', 18, 29, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(5183, 'Reciprocal Link required', 'LINKS_RECIPROCAL_REQUIRED', 'True', 'A reciprocal link is required when a link is submitted.', 18, 30, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(5184, 'Reciprocal Link Check Count', 'LINKS_RECIPROCAL_CHECK_COUNT', '2', 'How many times a link is checked by the link_check script before it is disabled.', 18, 31, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', '');
INSERT INTO `configuration` VALUES(5185, 'Check for Duplicate Links', 'LINKS_CHECK_DUPLICATE', 'True', 'Check if the submitted link is already on file.', 18, 32, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(5186, 'Allow Link Editing', 'LINKS_ALLOW_EDITING', 'False', 'Set this option to true to allow link partners to edit their links.', 18, 34, '0000-00-00 00:00:00', '2008-04-19 22:49:45', '', 'tep_cfg_select_option(array(''True'', ''False''),');
INSERT INTO `configuration` VALUES(5346, 'Table Method', 'MODULE_SHIPPING_TABLE_MODE', 'weight', 'The shipping cost is based on the order total or the total weight of the items ordered.', 6, 0, '0000-00-00 00:00:00', '2009-03-18 05:36:46', '', 'tep_cfg_select_option(array(''weight'', ''price''), ');
INSERT INTO `configuration` VALUES(5345, 'Shipping Table', 'MODULE_SHIPPING_TABLE_COST', '5:5.00,8:8.50,15:10.00,25:20.00,50:40:00', 'The shipping cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc', 6, 0, '0000-00-00 00:00:00', '2009-03-18 05:36:46', '', '');
INSERT INTO `configuration` VALUES(5344, 'Enable Table Method', 'MODULE_SHIPPING_TABLE_STATUS', 'True', 'Do you want to offer table rate shipping?', 6, 0, '0000-00-00 00:00:00', '2009-03-18 05:36:46', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5254, 'Category Image Width', 'CATEGORY_IMAGE_WIDTH', '350', 'The pixel width of category images', 4, 3, '2012-06-26 00:16:02', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(5253, 'Category Image Height', 'CATEGORY_IMAGE_HEIGHT', '', 'The pixel height of category images', 4, 3, '2008-12-23 15:01:48', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(5255, '<br/>GENERAL OPTIONS<br/><hr/><br/>Enable Checkout by Amazon Mod', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_STATUS', 'False', 'Allow customers to use Checkout by Amazon on your web store', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5256, 'Your Checkout by Amazon merchant ID', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTID', 'A1PE0HGUD4HL3Z', '<a href=''https://sellercentral.amazon.com/gp/cba/seller/account/settings/user-settings-view.html/ref=sc_navbar_m1k_cba_order_pipe_settings'' target=''_blank''/>Click here to get your MerchantID</a>', 6, 4, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', '');
INSERT INTO `configuration` VALUES(5257, '<br/><br/>POST ORDER MANAGEMENT OPTIONS<br/><br/><hr/><br/>Enabl', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDER_MANAGEMENT', 'True', 'Manage orders placed through Checkout by Amazon within your OSCommerce admin UI', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5258, 'Checkout by Amazon Merchant Login Id', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTEMAIL', 'mwadwani+04@amazon.com', '', 6, 4, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', '');
INSERT INTO `configuration` VALUES(5259, 'Checkout by Amazon Merchant Password<br/>(<i>this field will be ', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTPASSWORD', '2ZPMqL3W4GGDaQ==', '', 6, 4, '0000-00-00 00:00:00', '2009-02-19 02:52:28', 'crypt', 'tep_draw_input_password_field(');
INSERT INTO `configuration` VALUES(5260, 'Checkout by Amazon Merchant Token', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTTOKEN', 'M_CBA_8478993', '<a href=''https://sellercentral.amazon.com/gp/seller/configuration/account-info-page.html/ref=sc_navbar_m1k_seller_cfg'' target=''_blank''/>Click here to get your Merchant Token</a>', 6, 4, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', '');
INSERT INTO `configuration` VALUES(5261, 'Checkout by Amazon Merchant Name', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTNAME', 'CBA-07-23-09', '<a href=''https://sellercentral.amazon.com/gp/seller/configuration/account-info-page.html/ref=sc_navbar_m1k_seller_cfg'' target=''_blank''/>Click here to get your Merchant Name</a>', 6, 4, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', '');
INSERT INTO `configuration` VALUES(5262, '<br/>SIGNING OPTIONS<br/><hr/><br/>Enable Order Signing', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_SIGNING', 'True', '<i>Please note that Amazon recommends Signed carts. The signature helps to validate that the cart is not manipulated between your website and Amazon Payments. </i>', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5263, 'Operating environment', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_OPERATING_ENVIRONMENT', 'Sandbox', 'Select whether Checkout by Amazon should operate in the test sandbox or the live production environment. <br><i>Note: Currently Post Order Management cannot be tested on Sandbox</i>', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Production'', ''Sandbox''), ');
INSERT INTO `configuration` VALUES(5264, 'Fulfillment by Amazon', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_USE_FBA', 'False', 'Enables the Fulfillment by Amazon service. Please note that you must use AWS signed orders in order to use Fulfillment by Amazon.', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5265, 'Enable Diagnostic Logging', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_USE_DIAGNOSTIC_LOGGING', 'False', 'Enables diagnostic logging for debugging this OSCommerce plugin.', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5266, 'AWS Access ID', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_AWSACCESSID', '0G6JNCM0TJ9YTN9XP902', '<a href=''https://sellercentral.amazon.com/gp/cba/seller/accesskey/showAccessKey.html/ref=sc_tab_home_cba_access_key'' target=''_blank''/>Click here to get your AWS Access ID</a>', 6, 4, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', '');
INSERT INTO `configuration` VALUES(5267, 'AWS Secret Key<br/>(<i>this field will be encrypted when display', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_AWSSECRETKEY', 'llGklsOS1pWFgofv3+nPob/Vvr+6YN2Zp6TZX5akk9Td16uqeZ6H2cGSi1Q=', '<a href=''https://sellercentral.amazon.com/gp/cba/seller/accesskey/showAccessKey.html/ref=sc_tab_home_cba_access_key'' target=''_blank''/>Click here to get your AWS Secret Key</a>', 6, 4, '0000-00-00 00:00:00', '2009-02-19 02:52:28', 'crypt', 'tep_draw_input_password_field(');
INSERT INTO `configuration` VALUES(5268, 'Cart expiration time (in minutes)', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CART_EXPIRATION', '0', 'The number of minutes a cart is valid for (0 for no expiration)', 6, 4, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', '');
INSERT INTO `configuration` VALUES(5269, 'Sort order of display.', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_SORT_ORDER', '0', 'Order in which different payment options you have enabled are displayed. Lowest is displayed first.', 6, 0, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', '');
INSERT INTO `configuration` VALUES(5270, 'Success Return Page', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_RETURN_URL', '', 'Please enter the complete URL of the page you would like your customers to return after a purchase.  If you choose not to specify one, the index osCommerce page will be used', 6, 4, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', '');
INSERT INTO `configuration` VALUES(5271, '<br/>CALLBACK OPTIONS<br/><hr/><br/>Enable Callbacks', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_USE_CALLBACK', 'True', '<i>The Callback API lets you specify shipping and taxes using your own application logic at the time an order is placed when using Checkout by Amazon</i>', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5272, 'Callback Page', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_URL', 'http://demo.storecoders.com:80/catalog/checkout_by_amazon_callback_processor.php', 'Please enter the complete URL of the Callback page. use HTTPS if you are Operating environment is <b>Production</b> else use HTTP.  If you choose not to specify one, the index osCommerce page will be used', 6, 4, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', '');
INSERT INTO `configuration` VALUES(5273, 'Enable Shipping Calculations', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_SHIPPING', 'True', 'Should dynamic shipping calculations be enabled as part of Callbacks', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5274, 'Shipping Carrier', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_SHIPPING_CARRIER', 'USPS', 'Please select which carrier should be used to compute shipping rates. You must install and enable the selected carrier in Administration > Modules > Shipping first.', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''USPS'', ''UPS'', ''FedEx''), ');
INSERT INTO `configuration` VALUES(5275, 'Enable Tax Calculations', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_TAXES', 'True', 'Should dynamic tax calculations be enabled as part of Callbacks', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5276, 'Is Shipping and Handling Taxed', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_IS_SHIPPING_TAXED', 'False', 'Please specify whether the shipping amount should be taxed as part of Callbacks', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5277, 'Cancelation Return Page', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CANCEL_URL', '', 'Please enter the complete URL of the page you would like your customers to return to if they abandon or cancel an order.  If you do not enter one, the default is the main osCommerce catalog page', 6, 4, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', '');
INSERT INTO `configuration` VALUES(5278, 'Checkout Button Size', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_BUTTON_SIZE', 'Large', 'Creates either a large(151x27) or medium(126x24) Checkout By Amazon button.', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Large'',''Medium''), ');
INSERT INTO `configuration` VALUES(5279, 'Button Style', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_BUTTON_STYLE', 'Orange', 'Choose from two styles of buttons', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Orange'', ''Tan''), ');
INSERT INTO `configuration` VALUES(5280, '<br/>DOMESTIC SHIPPING SPEED MAPPING<br/><hr/><a href=''http://ww', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_USPS_STANDARD', 'Parcel Post', 'Maps the Domestic Shipping Career to Standard Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Express Mail'',''First-Class Mail'',''Priority Mail'',''Parcel Post'',''None''), ');
INSERT INTO `configuration` VALUES(5281, 'Expedited Shipping', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_USPS_EXPEDITED', 'None', 'Maps the Domestic Shipping Career to Expedited Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Express Mail'',''First-Class Mail'',''Priority Mail'',''Parcel Post'',''None''), ');
INSERT INTO `configuration` VALUES(5282, 'One Day Shipping', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_USPS_ONEDAY', 'Express Mail', 'Maps the Domestic Shipping Career to One Day Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Express Mail'',''First-Class Mail'',''Priority Mail'',''Parcel Post'',''None''), ');
INSERT INTO `configuration` VALUES(5283, 'Two Day Shipping', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_USPS_TWODAY', 'Priority Mail', 'Maps the Domestic Shipping Career to Two Day Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Express Mail'',''First-Class Mail'',''Priority Mail'',''Parcel Post'',''None''), ');
INSERT INTO `configuration` VALUES(5284, '<br/>INTERNATIONAL SHIPPING SPEED MAPPING<br/><hr/><a href=''http', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_USPS_INTL_STANDARD', 'Priority Mail International Flat Rate Box', 'Maps the International Shipping Career to Standard Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Global Express Guaranteed'',''Express Mail International (EMS)'',''Priority Mail International'',''Priority Mail International Flat Rate Box'',''First-Class Mail International'',''None''), ');
INSERT INTO `configuration` VALUES(5285, 'Expedited Shipping', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_USPS_INTL_EXPEDITED', 'Global Express Guaranteed', 'Maps the International Shipping Career to Expedited Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Global Express Guaranteed'',''Express Mail International (EMS)'',''Priority Mail International'',''Priority Mail International Flat Rate Box'',''First-Class Mail International'',''None''), ');
INSERT INTO `configuration` VALUES(5286, 'One Day Shipping', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_USPS_INTL_ONEDAY', 'None', 'Maps the International Shipping Career to One Day Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Global Express Guaranteed'',''Express Mail International (EMS)'',''Priority Mail International'',''Priority Mail International Flat Rate Box'',''First-Class Mail International'',''None''), ');
INSERT INTO `configuration` VALUES(5287, 'Two Day Shipping', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_USPS_INTL_TWODAY', 'None', 'Maps the International Shipping Career to Two Day Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Global Express Guaranteed'',''Express Mail International (EMS)'',''Priority Mail International'',''Priority Mail International Flat Rate Box'',''First-Class Mail International'',''None''), ');
INSERT INTO `configuration` VALUES(5288, '<br/><a href=''http://www.ups.com/content/us/en/shipping/time/ser', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_UPS_STANDARD', 'Ground', 'Maps the Domestic Shipping Career to Standard Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Next Day Air'',''Next Day Air Intra (Puerto Rico)'',''Next Day Air Saver'',''2nd Day Air'',''3 Day Select'',''Ground'',''Ground Commercial'',''Ground Residential'',''None''), ');
INSERT INTO `configuration` VALUES(5289, 'Expedited Shipping', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_UPS_EXPEDITED', '3 Day Select', 'Maps the Domestic Shipping Career to Expedited Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Next Day Air'',''Next Day Air Intra (Puerto Rico)'',''Next Day Air Saver'',''2nd Day Air'',''3 Day Select'',''Ground'',''Ground Commercial'',''Ground Residential'',''None''), ');
INSERT INTO `configuration` VALUES(5290, 'One Day Shipping', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_UPS_ONEDAY', 'Next Day Air Saver', 'Maps the Domestic Shipping Career to One Day Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Next Day Air'',''Next Day Air Intra (Puerto Rico)'',''Next Day Air Saver'',''2nd Day Air'',''3 Day Select'',''Ground'',''Ground Commercial'',''Ground Residential'',''None''), ');
INSERT INTO `configuration` VALUES(5291, 'Two Day Shipping', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_UPS_TWODAY', '2nd Day Air', 'Maps the Domestic Shipping Career to Two Day Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Next Day Air'',''Next Day Air Intra (Puerto Rico)'',''Next Day Air Saver'',''2nd Day Air'',''3 Day Select'',''Ground'',''Ground Commercial'',''Ground Residential'',''None''), ');
INSERT INTO `configuration` VALUES(5292, '<br/><a href=''http://www.ups.com/content/us/en/shipping/time/ser', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_UPS_INTL_STANDARD', 'None', 'Maps the International Shipping Career to Standard Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Canada Standard'',''Worldwide Express'',''Worldwide Expedited'',''None''), ');
INSERT INTO `configuration` VALUES(5293, 'Expedited Shipping', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_UPS_INTL_EXPEDITED', 'Worldwide Expedited', 'Maps the International Shipping Career to Expedited Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Canada Standard'',''Worldwide Express'',''Worldwide Expedited'',''None''), ');
INSERT INTO `configuration` VALUES(5294, 'One Day Shipping', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_UPS_INTL_ONEDAY', 'None', 'Maps the International Shipping Career to One Day Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Canada Standard'',''Worldwide Express'',''Worldwide Expedited'',''None''), ');
INSERT INTO `configuration` VALUES(5295, 'Two Day Shipping', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_UPS_INTL_TWODAY', 'Worldwide Express', 'Maps the International Shipping Career to Two Day Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Canada Standard'',''Worldwide Express'',''Worldwide Expedited'',''None''), ');
INSERT INTO `configuration` VALUES(5296, '<br/><a href=''http://www.fedex.com/ratetools/RateToolsMain.do?li', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_FEDEX1_STANDARD', 'Home Delivery', 'Maps the Domestic Shipping Career to Standard Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Priority (by 10:30AM, later for rural)'',''2 Day Air'',''Standard Overnight (by 3PM, later for rural)'',''First Overnight'',''Express Saver (3 Day)'',''Home Delivery'',''Ground Service'',''None''), ');
INSERT INTO `configuration` VALUES(5297, 'Expedited Shipping', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_FEDEX1_EXPEDITED', 'Express Saver (3 Day)', 'Maps the Domestic Shipping Career to Expedited Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Priority (by 10:30AM, later for rural)'',''2 Day Air'',''Standard Overnight (by 3PM, later for rural)'',''First Overnight'',''Express Saver (3 Day)'',''Home Delivery'',''Ground Service'',''None''), ');
INSERT INTO `configuration` VALUES(5298, 'One Day Shipping', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_FEDEX1_ONEDAY', 'Standard Overnight (by 3PM, later for rural)', 'Maps the Domestic Shipping Career to One Day Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Priority (by 10:30AM, later for rural)'',''2 Day Air'',''Standard Overnight (by 3PM, later for rural)'',''First Overnight'',''Express Saver (3 Day)'',''Home Delivery'',''Ground Service'',''None''), ');
INSERT INTO `configuration` VALUES(5299, 'Two Day Shipping', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_FEDEX1_TWODAY', '2 Day Air', 'Maps the Domestic Shipping Career to Two Day Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''Priority (by 10:30AM, later for rural)'',''2 Day Air'',''Standard Overnight (by 3PM, later for rural)'',''First Overnight'',''Express Saver (3 Day)'',''Home Delivery'',''Ground Service'',''None''), ');
INSERT INTO `configuration` VALUES(5300, '<br/><a href=''http://www.fedex.com/international/'' target=''_blan', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_FEDEX1_INTL_STANDARD', 'International Economy (4-5 Days)', 'Maps the International Shipping Career to Standard Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''International Priority (1-3 Days)'',''International Economy (4-5 Days)'',''International First'',''Home Delivery'',''Ground Service'',''None''), ');
INSERT INTO `configuration` VALUES(5301, 'Expedited Shipping', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_FEDEX1_INTL_EXPEDITED', 'International Priority (1-3 Days)', 'Maps the International Shipping Career to Expedited Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''International Priority (1-3 Days)'',''International Economy (4-5 Days)'',''International First'',''Home Delivery'',''Ground Service'',''None''), ');
INSERT INTO `configuration` VALUES(5302, 'One Day Shipping', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_FEDEX1_INTL_ONEDAY', 'International First', 'Maps the International Shipping Career to One Day Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''International Priority (1-3 Days)'',''International Economy (4-5 Days)'',''International First'',''Home Delivery'',''Ground Service'',''None''), ');
INSERT INTO `configuration` VALUES(5303, 'Two Day Shipping', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_FEDEX1_INTL_TWODAY', 'None', 'Maps the International Shipping Career to Two Day Shipping Speed', 6, 3, '0000-00-00 00:00:00', '2009-02-19 02:52:28', '', 'tep_cfg_select_option(array(''International Priority (1-3 Days)'',''International Economy (4-5 Days)'',''International First'',''Home Delivery'',''Ground Service'',''None''), ');
INSERT INTO `configuration` VALUES(5348, 'Tax Class', 'MODULE_SHIPPING_TABLE_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', 6, 0, '0000-00-00 00:00:00', '2009-03-18 05:36:46', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(');
INSERT INTO `configuration` VALUES(5349, 'Shipping Zone', 'MODULE_SHIPPING_TABLE_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', 6, 0, '0000-00-00 00:00:00', '2009-03-18 05:36:46', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(');
INSERT INTO `configuration` VALUES(5350, 'Sort Order', 'MODULE_SHIPPING_TABLE_SORT_ORDER', '5', 'Sort order of display.', 6, 0, '0000-00-00 00:00:00', '2009-03-18 05:36:46', '', '');
INSERT INTO `configuration` VALUES(5351, 'Max Category Item on Menu', 'MAX_CATEGORY_ITEM', '2', 'Maximum number of items to display on the Menu', 3, 18, '2011-07-26 05:24:54', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(5358, 'Rss news url', 'AZER_RSSNEWS_URL', 'http://www.strongcode.net/company/blog?format=feed&type=rss', 'RSS InfoBox URL', 923, 100, '2009-06-14 23:32:29', '2006-03-04 00:00:00', '', '');
INSERT INTO `configuration` VALUES(5375, 'Remove all non-alphanumeric characters?', 'SEO_REMOVE_ALL_SPEC_CHARS', 'false', 'This will remove all non-letters and non-numbers.  This should be handy to remove all special characters with 1 setting.', 888004, 16, '2009-06-19 10:09:16', '2009-06-19 10:09:16', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5376, 'Reset SEO URLs Cache', 'SEO_URLS_CACHE_RESET', 'false', 'This will reset the cache data for SEO', 888004, 17, '2011-07-16 12:33:45', '2009-06-19 10:09:16', 'tep_reset_cache_data_seo_urls', 'tep_cfg_select_option(array(''reset'', ''false''),');
INSERT INTO `configuration` VALUES(5377, 'Hide Prices and Buy Now Options to Non Logged in Users', 'HIDE_PRICE_NON_LOGGED', 'false', 'Hide Prices and Buy Now Options to Non Logged in Users', 1, 10, '2012-01-24 00:33:10', '2006-06-15 13:53:25', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5378, 'Default charge for restocking a non-faulty item', 'DEFAULT_RESTOCK_VALUE', '12.5', 'This is the charge applied to refund to cover the return of non-faulty items which are to be entered back into stock for resale', 9, 0, '2003-03-01 15:22:17', '0001-01-01 00:00:00', '', '');
INSERT INTO `configuration` VALUES(5379, 'Default Return Reason', 'DEFAULT_RETURN_REASON', '2', 'This is the default reason applied to all returns', 6, 0, '2003-02-27 06:44:29', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(5380, 'Default Return Status', 'DEFAULT_RETURN_STATUS_ID', '1', 'Default return status assigned to all new returns', 6, 0, '2003-02-28 07:10:04', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(5381, 'Default Refund Method', 'DEFAULT_REFUND_METHOD', '1', 'Default method for refund payment', 6, 0, '2003-03-01 16:46:12', '0000-00-00 00:00:00', '', '');
INSERT INTO `configuration` VALUES(55395, 'Zone 1 Handling Fee', 'MODULE_SHIPPING_ZONES_HANDLING_1', '0', 'Handling Fee for this shipping zone', 6, 0, NULL, '2011-06-25 23:02:21', NULL, NULL);
INSERT INTO `configuration` VALUES(55396, 'Zone 2 Countries', 'MODULE_SHIPPING_ZONES_COUNTRIES_2', '', 'Comma separated list of two character ISO country codes that are part of Zone 2.', 6, 0, NULL, '2011-06-25 23:02:21', NULL, NULL);
INSERT INTO `configuration` VALUES(55393, 'Zone 1 Countries', 'MODULE_SHIPPING_ZONES_COUNTRIES_1', 'US,CA', 'Comma separated list of two character ISO country codes that are part of Zone 1.', 6, 0, NULL, '2011-06-25 23:02:21', NULL, NULL);
INSERT INTO `configuration` VALUES(55394, 'Zone 1 Shipping Table', 'MODULE_SHIPPING_ZONES_COST_1', '3:8.50,7:10.50,99:20.00', 'Shipping rates to Zone 1 destinations based on a group of maximum order weights. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone 1 destinations.', 6, 0, NULL, '2011-06-25 23:02:21', NULL, NULL);
INSERT INTO `configuration` VALUES(55392, 'Sort Order', 'MODULE_SHIPPING_ZONES_SORT_ORDER', '0', 'Sort order of display.', 6, 0, NULL, '2011-06-25 23:02:21', NULL, NULL);
INSERT INTO `configuration` VALUES(5404, 'Enable Fedex Shipping', 'MODULE_SHIPPING_FEDEX1_STATUS', 'True', 'Do you want to offer Fedex shipping?', 6, 10, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5405, 'Display Transit Times', 'MODULE_SHIPPING_FEDEX1_TRANSIT', 'True', 'Do you want to show transit times for ground or home delivery rates?', 6, 10, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5406, 'Your Fedex Account Number', 'MODULE_SHIPPING_FEDEX1_ACCOUNT', '165369092', 'Enter the fedex Account Number assigned to you, required', 6, 11, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', '');
INSERT INTO `configuration` VALUES(5407, 'Your Fedex Meter ID', 'MODULE_SHIPPING_FEDEX1_METER', '6027998', 'Enter the Fedex MeterID assigned to you, set to NONE to obtain a new meter number', 6, 12, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', '');
INSERT INTO `configuration` VALUES(5408, 'cURL Path', 'MODULE_SHIPPING_FEDEX1_CURL', 'NONE', 'Enter the path to the cURL program, normally, leave this set to NONE to execute cURL using PHP', 6, 12, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', '');
INSERT INTO `configuration` VALUES(5409, 'Debug Mode', 'MODULE_SHIPPING_FEDEX1_DEBUG', 'False', 'Turn on Debug', 6, 19, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5410, 'Weight Units', 'MODULE_SHIPPING_FEDEX1_WEIGHT', 'LBS', 'Weight Units:', 6, 19, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', 'tep_cfg_select_option(array(''LBS'', ''KGS''), ');
INSERT INTO `configuration` VALUES(5411, 'First line of street address', 'MODULE_SHIPPING_FEDEX1_ADDRESS_1', '5100 park central dr', 'Enter the first line of your ship from street address, required', 6, 13, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', '');
INSERT INTO `configuration` VALUES(5412, 'Second line of street address', 'MODULE_SHIPPING_FEDEX1_ADDRESS_2', 'NONE', 'Enter the second line of your ship from street address, leave set to NONE if you do not need to specify a second line', 6, 14, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', '');
INSERT INTO `configuration` VALUES(5413, 'City name', 'MODULE_SHIPPING_FEDEX1_CITY', 'orlando', 'Enter the city name for the ship from street address, required', 6, 15, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', '');
INSERT INTO `configuration` VALUES(5414, 'State or Province name', 'MODULE_SHIPPING_FEDEX1_STATE', 'fl', 'Enter the 2 letter state or province name for the ship from street address, required for Canada and US', 6, 16, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', '');
INSERT INTO `configuration` VALUES(5415, 'Postal code', 'MODULE_SHIPPING_FEDEX1_POSTAL', '32839', 'Enter the postal code for the ship from street address, required', 6, 17, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', '');
INSERT INTO `configuration` VALUES(5416, 'Phone number', 'MODULE_SHIPPING_FEDEX1_PHONE', '8007687851', 'Enter a contact phone number for your company, required', 6, 18, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', '');
INSERT INTO `configuration` VALUES(5417, 'Which server to use', 'MODULE_SHIPPING_FEDEX1_SERVER', 'production', 'You must have an account with Fedex', 6, 19, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', 'tep_cfg_select_option(array(''test'', ''production''), ');
INSERT INTO `configuration` VALUES(5418, 'Drop off type', 'MODULE_SHIPPING_FEDEX1_DROPOFF', '4', 'Dropoff type (1 = Regular pickup, 2 = request courier, 3 = drop box, 4 = drop at BSC, 5 = drop at station)?', 6, 20, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', '');
INSERT INTO `configuration` VALUES(5419, 'Fedex surcharge?', 'MODULE_SHIPPING_FEDEX1_SURCHARGE', '0', 'Surcharge amount to add to shipping charge?', 6, 21, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', '');
INSERT INTO `configuration` VALUES(5420, 'Show List Rates?', 'MODULE_SHIPPING_FEDEX1_LIST_RATES', 'False', 'Show LIST Rates?', 6, 21, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5421, 'Residential surcharge?', 'MODULE_SHIPPING_FEDEX1_RESIDENTIAL', '0', 'Residential Surcharge (in addition to other surcharge) for Express packages within US, or ground packages within Canada?', 6, 21, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', '');
INSERT INTO `configuration` VALUES(5422, 'Insurance?', 'MODULE_SHIPPING_FEDEX1_INSURE', 'NONE', 'Insure packages over what dollar amount?', 6, 22, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', '');
INSERT INTO `configuration` VALUES(5423, 'Enable Envelope Rates?', 'MODULE_SHIPPING_FEDEX1_ENVELOPE', 'False', 'Do you want to offer Fedex Envelope rates? All items under 1/2 LB (.23KG) will quote using the envelope rate if True.', 6, 10, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5424, 'Sort rates: ', 'MODULE_SHIPPING_FEDEX1_WEIGHT_SORT', 'High to Low', 'Sort rates top to bottom: ', 6, 19, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', 'tep_cfg_select_option(array(''High to Low'', ''Low to High''), ');
INSERT INTO `configuration` VALUES(5425, 'Timeout in Seconds', 'MODULE_SHIPPING_FEDEX1_TIMEOUT', 'NONE', 'Enter the maximum time in seconds you would wait for a rate request from Fedex? Leave NONE for default timeout.', 6, 22, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', '');
INSERT INTO `configuration` VALUES(5426, 'Tax Class', 'MODULE_SHIPPING_FEDEX1_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', 6, 23, '0000-00-00 00:00:00', '2009-08-02 08:00:46', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(');
INSERT INTO `configuration` VALUES(5427, 'Sort Order', 'MODULE_SHIPPING_FEDEX1_SORT_ORDER', '2', 'Sort order of display.', 6, 24, '0000-00-00 00:00:00', '2009-08-02 08:00:46', '', '');
INSERT INTO `configuration` VALUES(5428, 'Shipping Zone', 'MODULE_SHIPPING_FEDEX1_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', 6, 0, '0000-00-00 00:00:00', '2009-08-02 08:00:46', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(');
INSERT INTO `configuration` VALUES(5494, 'Enable Free Shipping with Minimum Purchase', 'MODULE_SHIPPING_FREEAMOUNT_STATUS', 'True', 'Do you want to offer minimum order free shipping?', 6, 7, '0000-00-00 00:00:00', '2009-08-05 07:53:32', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5495, 'Maximum Weight', 'MODULE_SHIPPING_FREEAMOUNT_WEIGHT_MAX', '10', 'What is the maximum weight you will ship?', 6, 8, '0000-00-00 00:00:00', '2009-08-05 07:53:32', '', '');
INSERT INTO `configuration` VALUES(5499, 'Sort Order', 'MODULE_SHIPPING_FREEAMOUNT_SORT_ORDER', '4', 'Sort order of display.', 6, 0, '0000-00-00 00:00:00', '2009-08-05 07:53:32', '', '');
INSERT INTO `configuration` VALUES(5500, 'Shipping Zone', 'MODULE_SHIPPING_FREEAMOUNT_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', 6, 0, '0000-00-00 00:00:00', '2009-08-05 07:53:32', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(');
INSERT INTO `configuration` VALUES(5498, 'Disable for Specials', 'MODULE_SHIPPING_FREEAMOUNT_HIDE_SPECIALS', 'True', 'Do you want to disable free shipping for products on special?', 6, 7, '0000-00-00 00:00:00', '2009-08-05 07:53:32', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5497, 'Minimum Cost', 'MODULE_SHIPPING_FREEAMOUNT_AMOUNT', '98.00', 'Minimum order amount purchased before shipping is free?', 6, 8, '0000-00-00 00:00:00', '2009-08-05 07:53:32', '', '');
INSERT INTO `configuration` VALUES(5496, 'Enable Display', 'MODULE_SHIPPING_FREEAMOUNT_DISPLAY', 'True', 'Do you want to display text way if the minimum amount is not reached?', 6, 7, '0000-00-00 00:00:00', '2009-08-05 07:53:32', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5466, 'Shipping Zone', 'MODULE_SHIPPING_FLAT_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', 6, 0, '0000-00-00 00:00:00', '2009-08-02 08:06:36', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(');
INSERT INTO `configuration` VALUES(5467, 'Sort Order', 'MODULE_SHIPPING_FLAT_SORT_ORDER', '3', 'Sort order of display.', 6, 0, '0000-00-00 00:00:00', '2009-08-02 08:06:36', '', '');
INSERT INTO `configuration` VALUES(5485, 'Enable UPS Shipping', 'MODULE_SHIPPING_UPS_STATUS', 'False', 'Do you want to offer UPS shipping?', 6, 0, '0000-00-00 00:00:00', '2009-08-02 08:07:40', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5463, 'Enable Flat Shipping', 'MODULE_SHIPPING_FLAT_STATUS', 'True', 'Do you want to offer flat rate shipping?', 6, 0, '0000-00-00 00:00:00', '2009-08-02 08:06:36', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5493, 'Shipping Methods', 'MODULE_SHIPPING_UPS_TYPES', '2DM, 3DS, GND, STD', 'Select the USPS services to be offered.', 6, 13, '0000-00-00 00:00:00', '2009-08-02 08:07:40', '', 'tep_cfg_select_multioption(array(''1DM'',''1DML'', ''1DA'', ''1DAL'', ''1DAPI'', ''1DP'', ''1DPL'', ''2DM'', ''2DML'', ''2DA'', ''2DAL'', ''3DS'',''GND'', ''STD'', ''XPR'', ''XPRL'', ''XDM'', ''XDML'', ''XPD''), ');
INSERT INTO `configuration` VALUES(55646, 'Constant Contact API Key', 'CONSTANT_CONTACT_API_KEY', '', 'API Key for your Constant Contact account.', 5, 45, '2012-05-14 23:09:23', '2012-02-23 10:16:04', NULL, NULL);
INSERT INTO `configuration` VALUES(5465, 'Tax Class', 'MODULE_SHIPPING_FLAT_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', 6, 0, '0000-00-00 00:00:00', '2009-08-02 08:06:36', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(');
INSERT INTO `configuration` VALUES(5464, 'Shipping Cost', 'MODULE_SHIPPING_FLAT_COST', '5.00', 'The shipping cost for all orders using this shipping method.', 6, 0, '0000-00-00 00:00:00', '2009-08-02 08:06:36', '', '');
INSERT INTO `configuration` VALUES(5522, 'Enable Local Delivery', 'MODULE_SHIPPING_DLY3_STATUS', 'False', 'Do you want to offer Local Delivery?', 6, 2, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5523, 'Delivery Cost Method', 'MODULE_SHIPPING_DLY3_MODE', 'weight', 'The delivery cost is based on the order total or the total weight of the items ordered.', 6, 4, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', 'tep_cfg_select_option(array(''weight'', ''price''), ');
INSERT INTO `configuration` VALUES(5524, 'Tax Class', 'MODULE_SHIPPING_DLY3_TAX_CLASS', '0', 'Use the following Tax Class on the Shipping Fee.', 6, 6, '0000-00-00 00:00:00', '2009-09-14 17:41:27', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(');
INSERT INTO `configuration` VALUES(5525, 'Minimum Order Total', 'MODULE_SHIPPING_DLY3_MINIMUM_ORDER_TOTAL', '0.00', 'What is the Minimum Order Total required for this option to be activated.', 6, 8, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5526, 'Maximum Local Delivery Distance', 'MODULE_SHIPPING_DLY3_MAX_LOCAL_DISTANCE', '12 Km', 'What is the Maximum Local delivery distance which you will travel to deliver orders. [ ie. 12 Km ]', 6, 10, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5527, 'Shipping Zone', 'MODULE_SHIPPING_DLY3_ZONE', '', 'Only enable this shipping method for these SHIPPING ZONES . Separate with comma if several, empty if all. SHIPPING ZONES including letters must be in capital letters.', 6, 12, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5528, 'Zip codes 0', 'MODULE_SHIPPING_DLY3_ZIPCODE0', '32839', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 14, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5529, 'Local Delivery Cost zone 0', 'MODULE_SHIPPING_DLY3_COST0', '25:7.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 16, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5530, 'Zip codes 1', 'MODULE_SHIPPING_DLY3_ZIPCODE1', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 18, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5531, 'Local Delivery Cost Zone 1', 'MODULE_SHIPPING_DLY3_COST1', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 20, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5532, 'Zip codes 2', 'MODULE_SHIPPING_DLY3_ZIPCODE2', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 22, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5533, 'Local Delivery Cost zone 2', 'MODULE_SHIPPING_DLY3_COST2', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 24, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5534, 'Zip codes 3', 'MODULE_SHIPPING_DLY3_ZIPCODE3', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 26, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5535, 'Local Delivery Cost zone 3', 'MODULE_SHIPPING_DLY3_COST3', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 28, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5536, 'Zip codes 4', 'MODULE_SHIPPING_DLY3_ZIPCODE4', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 30, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5537, 'Local Delivery Cost zone 4', 'MODULE_SHIPPING_DLY3_COST4', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 32, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5538, 'Zip codes 5', 'MODULE_SHIPPING_DLY3_ZIPCODE5', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 34, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5539, 'Local Delivery Cost zone 5', 'MODULE_SHIPPING_DLY3_COST5', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 36, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5540, 'Zip codes 6', 'MODULE_SHIPPING_DLY3_ZIPCODE6', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 38, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5541, 'Local Delivery Cost zone 6', 'MODULE_SHIPPING_DLY3_COST6', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 40, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5542, 'Zip codes 7', 'MODULE_SHIPPING_DLY3_ZIPCODE7', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 42, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5543, 'Local Delivery Cost zone 7', 'MODULE_SHIPPING_DLY3_COST7', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 44, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5544, 'Zip codes 8', 'MODULE_SHIPPING_DLY3_ZIPCODE8', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 46, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5545, 'Local Delivery Cost zone 8', 'MODULE_SHIPPING_DLY3_COST8', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 48, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5546, 'Zip codes 9', 'MODULE_SHIPPING_DLY3_ZIPCODE9', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 50, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5547, 'Local Delivery Cost zone 9', 'MODULE_SHIPPING_DLY3_COST9', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 52, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5548, 'Zip codes 10', 'MODULE_SHIPPING_DLY3_ZIPCODE10', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 54, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5549, 'Local Delivery Cost zone 10', 'MODULE_SHIPPING_DLY3_COST10', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 56, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5550, 'Zip codes 11', 'MODULE_SHIPPING_DLY3_ZIPCODE11', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 58, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5551, 'Local Delivery Cost zone 11', 'MODULE_SHIPPING_DLY3_COST11', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 60, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5552, 'Zip codes 12', 'MODULE_SHIPPING_DLY3_ZIPCODE12', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 62, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5553, 'Local Delivery Cost zone 12', 'MODULE_SHIPPING_DLY3_COST12', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 64, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5554, 'Zip codes 13 ', 'MODULE_SHIPPING_DLY3_ZIPCODE13', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 66, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5555, 'Local Delivery Cost zone 13', 'MODULE_SHIPPING_DLY3_COST13', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 68, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5556, 'Zip codes 14', 'MODULE_SHIPPING_DLY3_ZIPCODE14', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 70, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5557, 'Local Delivery Cost zone 14', 'MODULE_SHIPPING_DLY3_COST14', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 72, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5558, 'Zip codes 15', 'MODULE_SHIPPING_DLY3_ZIPCODE15', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 74, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5559, 'Local Delivery Cost zone 15', 'MODULE_SHIPPING_DLY3_COST15', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 76, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5560, 'Zip codes 16', 'MODULE_SHIPPING_DLY3_ZIPCODE16', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 78, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5561, 'Local Delivery Cost zone 16', 'MODULE_SHIPPING_DLY3_COST16', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 80, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5562, 'Zip codes 17', 'MODULE_SHIPPING_DLY3_ZIPCODE17', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 82, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5563, 'Local Delivery Cost zone 17', 'MODULE_SHIPPING_DLY3_COST17', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 84, 4, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5564, 'Zip codes 18', 'MODULE_SHIPPING_DLY3_ZIPCODE18', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 86, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5565, 'Local Delivery Cost zone 18', 'MODULE_SHIPPING_DLY3_COST18', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 88, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5566, 'Zip codes 19', 'MODULE_SHIPPING_DLY3_ZIPCODE19', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 90, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5567, 'Local Delivery Cost zone 19', 'MODULE_SHIPPING_DLY3_COST19', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 92, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5568, 'Zip codes 20', 'MODULE_SHIPPING_DLY3_ZIPCODE20', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 94, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5569, 'Local Delivery Cost zone 20', 'MODULE_SHIPPING_DLY3_COST20', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 96, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5570, 'Zip codes 21', 'MODULE_SHIPPING_DLY3_ZIPCODE21', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 98, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5571, 'Local Delivery Cost zone 21', 'MODULE_SHIPPING_DLY3_COST21', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 100, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5572, 'Zip codes 22', 'MODULE_SHIPPING_DLY3_ZIPCODE22', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 102, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5573, 'Local Delivery Cost zone 22', 'MODULE_SHIPPING_DLY3_COST22', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 104, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5574, 'Sort Order', 'MODULE_SHIPPING_DLY3_SORT_ORDER', '18', 'Sort order of display.', 6, 108, '0000-00-00 00:00:00', '2009-09-14 17:41:27', '', '');
INSERT INTO `configuration` VALUES(5588, 'Minimum Order Total', 'MODULE_SHIPPING_dly3datetime_MINIMUM_ORDER_TOTAL', '0.00', 'What is the Minimum Order Total required for this option to be activated.', 6, 8, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5587, 'Tax Class', 'MODULE_SHIPPING_dly3datetime_TAX_CLASS', '0', 'Use the following Tax Class on the Shipping Fee.', 6, 6, '0000-00-00 00:00:00', '2009-09-23 20:12:55', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(');
INSERT INTO `configuration` VALUES(5586, 'Delivery Cost Method', 'MODULE_SHIPPING_dly3datetime_MODE', 'weight', 'The delivery cost is based on the order total or the total weight of the items ordered.', 6, 4, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', 'tep_cfg_select_option(array(''weight'', ''price''), ');
INSERT INTO `configuration` VALUES(5585, 'Enable Local Delivery', 'MODULE_SHIPPING_dly3datetime_STATUS', 'False', 'Do you want to offer Local Delivery?', 6, 2, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5589, 'Maximum Local Delivery Distance', 'MODULE_SHIPPING_dly3datetime_MAX_LOCAL_DISTANCE', '12 Km', 'What is the Maximum Local delivery distance which you will travel to deliver orders. [ ie. 12 Km ]', 6, 10, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5590, 'Shipping Zone', 'MODULE_SHIPPING_dly3datetime_ZONE', '', 'Only enable this shipping method for these SHIPPING ZONES . Separate with comma if several, empty if all. SHIPPING ZONES including letters must be in capital letters.', 6, 12, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5591, 'Zip codes 0', 'MODULE_SHIPPING_dly3datetime_ZIPCODE0', '32839', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 14, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5592, 'Local Delivery Cost zone 0', 'MODULE_SHIPPING_dly3datetime_COST0', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 16, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5593, 'Zip codes 1', 'MODULE_SHIPPING_dly3datetime_ZIPCODE1', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 18, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5594, 'Local Delivery Cost Zone 1', 'MODULE_SHIPPING_dly3datetime_COST1', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 20, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5595, 'Zip codes 2', 'MODULE_SHIPPING_dly3datetime_ZIPCODE2', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 22, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5596, 'Local Delivery Cost zone 2', 'MODULE_SHIPPING_dly3datetime_COST2', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 24, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5597, 'Zip codes 3', 'MODULE_SHIPPING_dly3datetime_ZIPCODE3', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 26, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5598, 'Local Delivery Cost zone 3', 'MODULE_SHIPPING_dly3datetime_COST3', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 28, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5599, 'Zip codes 4', 'MODULE_SHIPPING_dly3datetime_ZIPCODE4', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 30, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5600, 'Local Delivery Cost zone 4', 'MODULE_SHIPPING_dly3datetime_COST4', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 32, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5601, 'Zip codes 5', 'MODULE_SHIPPING_dly3datetime_ZIPCODE5', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 34, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5602, 'Local Delivery Cost zone 5', 'MODULE_SHIPPING_dly3datetime_COST5', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 36, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5603, 'Zip codes 6', 'MODULE_SHIPPING_dly3datetime_ZIPCODE6', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 38, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5604, 'Local Delivery Cost zone 6', 'MODULE_SHIPPING_dly3datetime_COST6', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 40, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5605, 'Zip codes 7', 'MODULE_SHIPPING_dly3datetime_ZIPCODE7', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 42, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5606, 'Local Delivery Cost zone 7', 'MODULE_SHIPPING_dly3datetime_COST7', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 44, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5607, 'Zip codes 8', 'MODULE_SHIPPING_dly3datetime_ZIPCODE8', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 46, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5608, 'Local Delivery Cost zone 8', 'MODULE_SHIPPING_dly3datetime_COST8', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 48, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5609, 'Zip codes 9', 'MODULE_SHIPPING_dly3datetime_ZIPCODE9', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 50, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5610, 'Local Delivery Cost zone 9', 'MODULE_SHIPPING_dly3datetime_COST9', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 52, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5611, 'Zip codes 10', 'MODULE_SHIPPING_dly3datetime_ZIPCODE10', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 54, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5612, 'Local Delivery Cost zone 10', 'MODULE_SHIPPING_dly3datetime_COST10', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 56, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5613, 'Zip codes 11', 'MODULE_SHIPPING_dly3datetime_ZIPCODE11', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 58, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5614, 'Local Delivery Cost zone 11', 'MODULE_SHIPPING_dly3datetime_COST11', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 60, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5615, 'Zip codes 12', 'MODULE_SHIPPING_dly3datetime_ZIPCODE12', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 62, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5616, 'Local Delivery Cost zone 12', 'MODULE_SHIPPING_dly3datetime_COST12', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 64, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5617, 'Zip codes 13 ', 'MODULE_SHIPPING_dly3datetime_ZIPCODE13', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 66, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5618, 'Local Delivery Cost zone 13', 'MODULE_SHIPPING_dly3datetime_COST13', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 68, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5619, 'Zip codes 14', 'MODULE_SHIPPING_dly3datetime_ZIPCODE14', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 70, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5620, 'Local Delivery Cost zone 14', 'MODULE_SHIPPING_dly3datetime_COST14', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 72, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5621, 'Zip codes 15', 'MODULE_SHIPPING_dly3datetime_ZIPCODE15', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 74, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5622, 'Local Delivery Cost zone 15', 'MODULE_SHIPPING_dly3datetime_COST15', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 76, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5623, 'Zip codes 16', 'MODULE_SHIPPING_dly3datetime_ZIPCODE16', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 78, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5624, 'Local Delivery Cost zone 16', 'MODULE_SHIPPING_dly3datetime_COST16', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 80, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5625, 'Zip codes 17', 'MODULE_SHIPPING_dly3datetime_ZIPCODE17', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 82, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5626, 'Local Delivery Cost zone 17', 'MODULE_SHIPPING_dly3datetime_COST17', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 84, 4, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5627, 'Zip codes 18', 'MODULE_SHIPPING_dly3datetime_ZIPCODE18', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 86, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5628, 'Local Delivery Cost zone 18', 'MODULE_SHIPPING_dly3datetime_COST18', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 88, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5629, 'Zip codes 19', 'MODULE_SHIPPING_dly3datetime_ZIPCODE19', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 90, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5630, 'Local Delivery Cost zone 19', 'MODULE_SHIPPING_dly3datetime_COST19', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 92, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5631, 'Zip codes 20', 'MODULE_SHIPPING_dly3datetime_ZIPCODE20', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 94, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5632, 'Local Delivery Cost zone 20', 'MODULE_SHIPPING_dly3datetime_COST20', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 96, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5633, 'Zip codes 21', 'MODULE_SHIPPING_dly3datetime_ZIPCODE21', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 98, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5634, 'Local Delivery Cost zone 21', 'MODULE_SHIPPING_dly3datetime_COST21', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 100, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5635, 'Zip codes 22', 'MODULE_SHIPPING_dly3datetime_ZIPCODE22', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', 6, 102, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5636, 'Local Delivery Cost zone 22', 'MODULE_SHIPPING_dly3datetime_COST22', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', 6, 104, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5637, 'Sort Order', 'MODULE_SHIPPING_dly3datetime_SORT_ORDER', '0', 'Sort order of display.', 6, 108, '0000-00-00 00:00:00', '2009-09-23 20:12:55', '', '');
INSERT INTO `configuration` VALUES(5644, 'Enable Customer Pickup', 'MODULE_SHIPPING_PICKUP_STATUS', 'True', 'Do you want to offer customer pickup?', 6, 0, '0000-00-00 00:00:00', '2009-09-26 20:40:02', '', 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(5645, 'Pickup Fee', 'MODULE_SHIPPING_PICKUP_COST', '0.00', 'The fee for all orders being picked up by the customer. Most likely will be 0.00 but can be changed if needed.', 6, 0, '0000-00-00 00:00:00', '2009-09-26 20:40:02', '', '');
INSERT INTO `configuration` VALUES(5646, 'Tax Class', 'MODULE_SHIPPING_PICKUP_TAX_CLASS', '0', 'Use the following tax class on the fee.', 6, 0, '0000-00-00 00:00:00', '2009-09-26 20:40:02', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(');
INSERT INTO `configuration` VALUES(5647, 'Shipping Zone', 'MODULE_SHIPPING_PICKUP_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', 6, 0, '0000-00-00 00:00:00', '2009-09-26 20:40:02', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(');
INSERT INTO `configuration` VALUES(5648, 'Sort Order', 'MODULE_SHIPPING_PICKUP_SORT_ORDER', '0', 'Sort order of display.', 6, 0, '0000-00-00 00:00:00', '2009-09-26 20:40:02', '', '');
INSERT INTO `configuration` VALUES(5649, 'YMM data on Product Info page', 'YMM_DISPLAY_DATA_ON_PRODUCT_INFO_PAGE', 'Yes', 'Display YMM data on the Product Info page?', 888005, 1, '0000-00-00 00:00:00', '2009-09-28 18:13:31', '', 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(5650, 'number of Input fields', 'YMM_NUMBER_OF_INPUT_FIELDS', '10', 'Enter the number of how many YMM input fields should be displayed on the Edit Product page.', 888005, 2, '0000-00-00 00:00:00', '2009-09-28 18:13:31', '', '');
INSERT INTO `configuration` VALUES(5651, 'Categories box', 'YMM_FILTER_CATEGORIES_BOX', 'Yes', 'Filter Categories box by year, make, model selected?', 888005, 3, '0000-00-00 00:00:00', '2009-09-28 18:13:31', '', 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(5652, 'count products in category', 'YMM_FILTER_COUNT_PRODUCTS_IN_CATEGORY', 'Yes', 'Filter count products in category by year, make, model selected?', 888005, 4, '0000-00-00 00:00:00', '2009-09-28 18:13:31', '', 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(5653, 'categories listing', 'YMM_FILTER_CATEGORIES_LISTING', 'Yes', 'Filter categories listing by year, make, model selected?', 888005, 5, '0000-00-00 00:00:00', '2009-09-28 18:13:31', '', 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(5654, 'product listing module', 'YMM_FILTER_PRODUCT_LISTING', 'Yes', 'Filter product listing module by year, make, model selected?', 888005, 6, '0000-00-00 00:00:00', '2009-09-28 18:13:31', '', 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(5655, 'Product Info page', 'YMM_FILTER_PRODUCT_INFO', 'Yes', 'Filter Product Info page (product_info.php) by year, make, model selected?', 888005, 7, '0000-00-00 00:00:00', '2009-09-28 18:13:31', '', 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(5656, 'What''s New box', 'YMM_FILTER_WHATS_NEW_BOX', 'Yes', 'Filter What''s New box by year, make, model selected?', 888005, 8, '0000-00-00 00:00:00', '2009-09-28 18:13:31', '', 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(5657, 'New Products module', 'YMM_FILTER_NEW_PRODUCTS', 'Yes', 'Filter New Products module by year, make, model selected?', 888005, 9, '0000-00-00 00:00:00', '2009-09-28 18:13:31', '', 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(5658, 'New Products page', 'YMM_FILTER_PRODUCTS_NEW', 'Yes', 'Filter New Products page (products_new.php) by year, make, model selected?', 888005, 10, '0000-00-00 00:00:00', '2009-09-28 18:13:31', '', 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(5659, 'Product Reviews page', 'YMM_FILTER_PRODUCT_REVIEWS', 'Yes', 'Filter Product Reviews page (product_reviews.php) by year, make, model selected?', 888005, 11, '0000-00-00 00:00:00', '2009-09-28 18:13:31', '', 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(5660, 'Reviews box', 'YMM_FILTER_REVIEWS_BOX', 'Yes', 'Filter Reviews box by year, make, model selected?', 888005, 12, '0000-00-00 00:00:00', '2009-09-28 18:13:31', '', 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(5661, 'Reviews page', 'YMM_FILTER_REVIEWS', 'Yes', 'Filter Reviews page (reviews.php) by year, make, model selected?', 888005, 13, '0000-00-00 00:00:00', '2009-09-28 18:13:31', '', 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(5662, 'Specials box', 'YMM_FILTER_SPECIALS_BOX', 'Yes', 'Filter Specials box by year, make, model selected?', 888005, 14, '0000-00-00 00:00:00', '2009-09-28 18:13:31', '', 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(5663, 'Specials page', 'YMM_FILTER_SPECIALS', 'Yes', 'Filter Specials page (specials.php) by year, make, model selected?', 888005, 15, '0000-00-00 00:00:00', '2009-09-28 18:13:31', '', 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(5664, 'Bestsellers box', 'YMM_FILTER_BEST_SELLERS_BOX', 'Yes', 'Filter Bestsellers box by year, make, model selected?', 888005, 16, '0000-00-00 00:00:00', '2009-09-28 18:13:31', '', 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(5665, 'Also Purchased Products module', 'YMM_FILTER_ALSO_PURCHASED_PRODUCTS', 'Yes', 'Filter Also Purchased Products module by year, make, model selected?', 888005, 17, '0000-00-00 00:00:00', '2009-09-28 18:13:31', '', 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(5666, 'Upcoming Products module', 'YMM_FILTER_UPCOMING_PRODUCTS', 'Yes', 'Filter Upcoming Products module by year, make, model selected?', 888005, 18, '0000-00-00 00:00:00', '2009-09-28 18:13:31', '', 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(5667, 'Search Results page', 'YMM_FILTER_ADVANCED_SEARCH_RESULT', 'Yes', 'Filter Search Results page (advanced_search_result.php) by year, make, model selected?', 888005, 19, '0000-00-00 00:00:00', '2009-09-28 18:13:31', '', 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(188, 'Master Password', 'MASTER_PASS', '637b9adadf7acce5c70e5d327a725b13', 'This password will allow you to login to any customers account.', 1, 23, '2004-06-15 07:10:52', '2004-06-15 07:10:52', '', '');
INSERT INTO `configuration` VALUES(5668, 'Enable Autoparts Store Features?', 'AUTO_CONFIG', 'true', 'Enable Autoparts Store Features?', 1, 10, '2009-10-26 20:25:49', '2006-06-15 13:53:25', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5669, 'Look back days', 'RCS_BASE_DAYS', '30', 'Number of days to look back from today for abandoned cards.', 6501, 10, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', '');
INSERT INTO `configuration` VALUES(5670, 'Skip days', 'RCS_SKIP_DAYS', '5', 'Number of days to skip when looking for abandoned carts.', 6501, 11, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', '');
INSERT INTO `configuration` VALUES(5671, 'Sales Results Report days', 'RCS_REPORT_DAYS', '90', 'Number of days the sales results report takes into account. The more days the longer the SQL queries!.', 6501, 15, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', '');
INSERT INTO `configuration` VALUES(5672, 'Use Calculated taxes', 'RCS_INCLUDE_TAX_IN_PRICES', 'false', 'Try to calculate the taxes when determining prices. This may not be 100% correct as determing location being shopped from, etc. may be incorrect.', 6501, 16, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5673, 'Use Fixed tax rate', 'RCS_USE_FIXED_TAX_IN_PRICES', 'false', 'Use a fixed tax rate when determining prices (rate set below). Overridden if ''Use Calculated taxes'' is true.', 6501, 17, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5674, 'Fixed Tax Rate', 'RCS_FIXED_TAX_RATE', '.10', 'The fixed tax rate for use when ''Use Fixed tax rate'' is true and ''Use Calculated taxes'' is false.<br><br>Use decimal values, ie: 0.10 for 10% ', 6501, 18, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', '');
INSERT INTO `configuration` VALUES(5675, 'E-Mail time to live', 'RCS_EMAIL_TTL', '90', 'Number of days to give for emails before they no longer show as being sent', 6501, 20, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', '');
INSERT INTO `configuration` VALUES(5676, 'Friendly E-Mails', 'RCS_EMAIL_FRIENDLY', 'true', 'If <b>true</b> then the customer''s name will be used in the greeting. If <b>false</b> then a generic greeting will be used.', 6501, 30, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5677, 'E-Mail Copies to', 'RCS_EMAIL_COPIES_TO', '', 'If you want copies of emails that are sent to customers by this contribution, enter the email address here. If empty no copies are sent', 6501, 35, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', '');
INSERT INTO `configuration` VALUES(5678, 'Show Attributes', 'RCS_SHOW_ATTRIBUTES', 'false', 'Controls display of item attributes.<br><br>Some sites have attributes for their items.<br><br>Set this to <b>true</b> if yours does and you want to show them, otherwise set to <b>false</b>.', 6501, 40, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5679, 'Ignore Customers with Sessions', 'RCS_CHECK_SESSIONS', 'false', 'If you want the tool to ignore customers with an active session (ie, probably still shopping) set this to <b>true</b>.<br><br>Setting this to <b>false</b> will operate in the default manner of ignoring session data &amp; using less resources.', 6501, 40, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5680, 'Current Customer Color', 'RCS_CURCUST_COLOR', '0000FF', 'Color for the word/phrase used to notate a current customer<br><br>A current customer is someone who has purchased items from your store in the past.', 6501, 50, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', '');
INSERT INTO `configuration` VALUES(5681, 'Uncontacted hilight color', 'RCS_UNCONTACTED_COLOR', '9FFF9F', 'Row highlight color for uncontacted customers.<br><br>An uncontacted customer is one that you have <i>not</i> used this tool to send an email to before.', 6501, 60, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', '');
INSERT INTO `configuration` VALUES(5682, 'Contacted hilight color', 'RCS_CONTACTED_COLOR', 'FF9F9F', 'Row highlight color for contacted customers.<br><br>An contacted customer is one that you <i>have</i> used this tool to send an email to before.', 6501, 70, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', '');
INSERT INTO `configuration` VALUES(5683, 'Matching Order Hilight', 'RCS_MATCHED_ORDER_COLOR', '9FFFFF', 'Row highlight color for entrees that may have a matching order.<br><br>An entry will be marked with this color if an order contains one or more of an item in the abandoned cart <b>and</b> matches either the cart''s customer email address or database ID.', 6501, 72, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', '');
INSERT INTO `configuration` VALUES(5684, 'Skip Carts w/Matched Orders', 'RCS_SKIP_MATCHED_CARTS', 'true', 'To ignore carts with an a matching order set this to <b>true</b>.<br><br>Setting this to <b>false</b> will cause entries with a matching order to show, along with the matching order''s status.<br><br>See documentation for details.', 6501, 80, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5685, 'Autocheck "safe" carts to email', 'RCS_AUTO_CHECK', 'true', 'To check entries which are most likely safe to email (ie, not existing customers, not previously emailed, etc.) set this to <b>true</b>.<br><br>Setting this to <b>false</b> will leave all entries unchecked (must manually check entries to send an email to)', 6501, 82, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5686, 'Match orders from any date', 'RCS_CARTS_MATCH_ALL_DATES', 'true', 'If <b>true</b> then any order found with a matching item will be considered a matched order.<br><br>If <b>false</b> only orders placed after the abandoned cart are considered.', 6501, 84, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(5687, 'Lowest Pending sales status', 'RCS_PENDING_SALE_STATUS', '1', 'The highest value that an order can have and still be considered pending. Any value higher than this will be considered by RCS as sale which completed.<br><br>See documentation for details.', 6501, 85, '0000-00-00 00:00:00', '2009-11-04 15:53:49', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses(');
INSERT INTO `configuration` VALUES(5688, 'Report Even Row Style', 'RCS_REPORT_EVEN_STYLE', 'dataTableRow', 'Style for even rows in results report. Typical options are <i>dataTableRow</i> and <i>attributes-even</i>.', 6501, 90, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', '');
INSERT INTO `configuration` VALUES(5689, 'Report Odd Row Style', 'RCS_REPORT_ODD_STYLE', '', 'Style for odd rows in results report. Typical options are NULL (ie, no entry) and <i>attributes-odd</i>.', 6501, 92, '0000-00-00 00:00:00', '2009-11-04 15:53:49', '', '');
INSERT INTO `configuration` VALUES(54996, 'Rss News Url 2', 'AZER_RSSNEWS_URL2', 'http://rss.cnn.com/rss/money_latest.rss', 'RSS InfoBox URL 2', 923, 101, '2011-10-10 03:27:43', '2009-11-11 00:00:00', 'NULL', '');
INSERT INTO `configuration` VALUES(55009, 'Indiv Ship Home Country', 'INDIVIDUAL_SHIP_HOME_COUNTRY', '223', 'Individual ship home country ID (other countries will have extra freight)', 7, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `configuration` VALUES(55010, 'Indiv Ship Outside Home Increase', 'INDIVIDUAL_SHIP_INCREASE', '3', 'Individual ship x increase for shipping outside home country. For example: If you set your item ship price to $50 and this value to 3 and ship outside your home country they will pay $150, and if this value was 2, they would pay $100.', 7, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `configuration` VALUES(55011, 'Enable Individual Shipping Prices', 'MODULE_SHIPPING_INDVSHIP_STATUS', 'True', 'Do you want to offer individual shipping prices?', 6, 0, NULL, '2010-07-13 05:23:59', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55012, 'Tax Class', 'MODULE_SHIPPING_INDVSHIP_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', 6, 0, NULL, '2010-07-13 05:23:59', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(');
INSERT INTO `configuration` VALUES(55013, 'Shipping Zone', 'MODULE_SHIPPING_INDVSHIP_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', 6, 0, NULL, '2010-07-13 05:23:59', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(');
INSERT INTO `configuration` VALUES(55014, 'Sort Order', 'MODULE_SHIPPING_INDVSHIP_SORT_ORDER', '10', 'Sort order of display.', 6, 0, NULL, '2010-07-13 05:23:59', NULL, NULL);
INSERT INTO `configuration` VALUES(55015, 'Higher Rated States', 'MODULE_SHIPPING_INDVSHIP_STATES', 'alaska,hawaii', 'Higher rate States', 6, 0, NULL, '2010-07-13 05:23:59', NULL, NULL);
INSERT INTO `configuration` VALUES(55016, 'Handling Fee', 'MODULE_SHIPPING_INDVSHIP_HANDLING', '4.95', 'Handling fee for these States.', 6, 0, NULL, '2010-07-13 05:23:59', NULL, NULL);
INSERT INTO `configuration` VALUES(55670, 'Enable PayPal Website Payments Standard', 'MODULE_PAYMENT_PAYPAL_STANDARD_STATUS', 'True', 'Do you want to accept PayPal Website Payments Standard payments?', 6, 3, NULL, '2012-03-27 18:47:35', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55671, 'E-Mail Address', 'MODULE_PAYMENT_PAYPAL_STANDARD_ID', 'info@strongcode.net', 'The PayPal seller e-mail address to accept payments for', 6, 4, NULL, '2012-03-27 18:47:35', NULL, NULL);
INSERT INTO `configuration` VALUES(55672, 'Sort order of display.', 'MODULE_PAYMENT_PAYPAL_STANDARD_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2012-03-27 18:47:35', NULL, NULL);
INSERT INTO `configuration` VALUES(55022, 'Banner Order', 'BANNER_ORDER', 'banners_id', 'Order that the Banner Rotator uses to show the banners.', 1661, 10, NULL, '2010-06-25 17:25:11', NULL, 'tep_cfg_select_option(array(''banners_id'', ''rand()''), ');
INSERT INTO `configuration` VALUES(55023, 'Banner Rotator Group', 'BANNER_ROTATOR_GROUP', 'rotator', 'Name of the banner group that the Banner Rotator uses to show the banners.', 1661, 5, NULL, '2010-06-25 17:25:11', NULL, '');
INSERT INTO `configuration` VALUES(55024, 'Banner Rotator Max Banners', 'MAX_DISPLAY_BANNER_ROTATOR', '4', 'Maximum number of banners that the Banner Rotator will show', 1661, 15, '2010-06-27 01:38:54', '2010-06-25 17:25:11', NULL, '');
INSERT INTO `configuration` VALUES(555123, 'Sort Order', 'MODULE_ORDER_TOTAL_GV_SORT_ORDER', '4', 'Sort order of display.', 6, 2, NULL, '2012-07-03 19:29:01', NULL, NULL);
INSERT INTO `configuration` VALUES(555122, 'Display Total', 'MODULE_ORDER_TOTAL_GV_STATUS', 'true', 'Do you want to display the Gift Voucher value?', 6, 1, NULL, '2012-07-03 19:29:01', NULL, 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(55056, 'reCAPTCHA Public Key', 'RECAPTCHA_PUBLIC_KEY', '6LfFwsQSAAAAAPgtfkryuGWggtorYpTQc_pX5dqv', 'Public key for reCAPTCHA', 1, 50, '2010-08-06 01:27:42', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(55057, 'reCAPTCHA Private Key', 'RECAPTCHA_PRIVATE_KEY', '6LfFwsQSAAAAAD1F2fA0vbO9ML9ZUjnzbUnbtics', 'Private key for reCAPTCHA', 1, 51, '2010-08-06 01:27:42', '2006-06-15 13:53:25', '', '');
INSERT INTO `configuration` VALUES(55074, 'PHPIDS Module (Store)', 'PHPIDS_MODULE', 'true', 'Enable PHPIDS for the store front.', 888006, 1, '2012-01-25 01:38:20', '2011-06-13 10:39:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(55075, 'IP Ban Module', 'PHPIDS_IP_BAN_MODULE', 'false', 'Enable IP Ban', 888006, 2, '2011-07-20 22:35:54', '2011-06-13 10:39:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(55076, 'Show Intrusion Result', 'PHPIDS_SHOW_RESULT', 'true', 'Show Intrusion Results on Screen - Enable only during Testing.', 888006, 4, '2011-12-29 19:05:48', '2011-06-13 10:39:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(55077, 'E-mail Log Impact Score', 'PHPIDS_MAIL_LOG_IMPACT', '8', 'Default is 8. Intrusion E-mails are sent when the Impact Score is greater or equal to this set value. You could change this to a lesser or higher value as per your requirement.', 888006, 6, NULL, '2011-06-13 10:39:17', NULL, NULL);
INSERT INTO `configuration` VALUES(55078, 'DB Log Impact Score', 'PHPIDS_DB_LOG_IMPACT', '5', 'Default is 4. Intrusion logs are created in the database when the Impact Score is greater or equal to this set value. You could change this to a lesser or higher value as per your requirement.', 888006, 7, '2012-08-02 17:52:29', '2011-06-13 10:39:17', NULL, NULL);
INSERT INTO `configuration` VALUES(55079, 'IP Ban Impact Score', 'PHPIDS_IP_BAN_IMPACT', '70', 'Default is 70. IP gets banned automatically when the Impact Score is greater or equal to this set value. You could change this to a lesser or higher value as per your requirement.', 888006, 8, NULL, '2011-06-13 10:39:17', NULL, NULL);
INSERT INTO `configuration` VALUES(55080, 'Variable Exclusions', 'PHPIDS_EXCLUSIONS', 'REQUEST.__utmz, COOKIE.__utmz, \r\nREQUEST.custom, POST.custom, \r\nREQUEST.osCsid, COOKIE.osCsid, \r\nREQUEST.verify_sign, POST.verify_sign, \r\nREQUEST.s_pers, COOKIE.s_pers, \r\nREQUEST.enquiry, POST.enquiry, \r\nREQUEST.articles_head_desc_tag.1, \r\nPOST.articles_head_desc_tag.1, \r\nREQUEST.articles_description.1, \r\nPOST.articles_description.1,\r\nREQUEST.articles_head_desc_tag, \r\nPOST.articles_head_desc_tag, \r\nREQUEST.articles_description.1, \r\nPOST.articles_description.1,\r\nPOST.conditions,REQUEST.conditions,\r\nPOST.products_info_desc.1,\r\nPOST.products_info_desc,\r\nREQUEST.products_info_desc.1,\r\nREQUEST.products_info_desc,\r\nREQUEST.products_description,\r\nPOST.products_description,\r\nPOST.products_description.1,\r\nREQUEST.products_description.1,\r\nPOST.configuration_value,\r\nREQUEST.configuration_value,\r\nREQUEST.fbsr_,\r\nCOOKIE.fbsr_,\r\nREQUEST.fbsr_165991923464047,\r\nCOOKIE.fbsr_165991923464047,\r\nREQUEST.comments,\r\nPOST.comments,\r\nREQUEST.enquiry,\r\nPOST.enquiry', 'List of safe Variables to exclude from intrusion report. Separated by comma and space. Example: REQUEST.__utmz, COOKIE.__utmz<br>', 888006, 12, '2012-06-19 15:11:04', '2011-06-13 10:39:17', NULL, 'tep_cfg_textarea(');
INSERT INTO `configuration` VALUES(55109, 'RM First Class Rates', 'MODULE_SHIPPING_RMFIRST_COST_1', '.1:1.58,.25:1.96,.5:2.48,.75:3.05,1:3.71,1.25:4.9,1.5:5.66,1.75:6.42,2:7.18,4:8.95,6:12,8:15.05,10:18.1', 'Enter values upto 5,2 decimal places. (12345.67) Example: .1:1,.25:1.27 - Weights less than or equal to 0.1Kg would cost 1.00, Weights less than or equal to 0.25g but more than 0.1Kg will cost 1.27. Do not enter KG or currency symbols.', 6, 0, NULL, '2011-06-17 10:04:06', NULL, NULL);
INSERT INTO `configuration` VALUES(55108, 'Valid ISO Country Codes', 'MODULE_SHIPPING_RMFIRST_COUNTRIES_1', 'GB', 'Comma separated list of two character ISO country codes that are valid destinations for this method (Default: GB)', 6, 0, NULL, '2011-06-17 10:04:06', NULL, NULL);
INSERT INTO `configuration` VALUES(55107, 'Display delivery time', 'MODULE_SHIPPING_RMFIRST_DISPLAY_TIME', 'True', 'Do you want to display the shipping time? (e.g. Ships within 3 to 5 days)', 6, 0, NULL, '2011-06-17 10:04:06', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55106, 'Display delivery weight', 'MODULE_SHIPPING_RMFIRST_DISPLAY_WEIGHT', 'True', 'Do you want to display the shipping weight? (e.g. Delivery Weight : 2.7674 Kg''s)', 6, 0, NULL, '2011-06-17 10:04:06', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55105, 'Split shipments on maximum value to ship', 'MODULE_SHIPPING_RMFIRST_VALUE_SPLIT', 'False', 'Do you want to split your shipment by maximum value to ship?', 6, 0, NULL, '2011-06-17 10:04:06', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55104, 'Maximum value to ship', 'MODULE_SHIPPING_RMFIRST_MAX_VALUE', '45', 'Enter the maximum value to ship', 6, 0, NULL, '2011-06-17 10:04:06', NULL, NULL);
INSERT INTO `configuration` VALUES(55103, 'Minimum value to ship', 'MODULE_SHIPPING_RMFIRST_MIN_VALUE', '0', 'Enter the minimum value to ship', 6, 0, NULL, '2011-06-17 10:04:06', NULL, NULL);
INSERT INTO `configuration` VALUES(55101, 'Maximum weight to ship', 'MODULE_SHIPPING_RMFIRST_MAX_WEIGHT', '10', 'Enter the maximum weight to ship', 6, 0, NULL, '2011-06-17 10:04:06', NULL, NULL);
INSERT INTO `configuration` VALUES(55102, 'Split shipments on maximum weight to ship', 'MODULE_SHIPPING_RMFIRST_WEIGHT_SPLIT', 'False', 'Do you want to split your shipment by maximum weight to ship?', 6, 0, NULL, '2011-06-17 10:04:06', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55100, 'Minimum weight to ship', 'MODULE_SHIPPING_RMFIRST_MIN_WEIGHT', '0', 'Enter the minimum weight to ship', 6, 0, NULL, '2011-06-17 10:04:06', NULL, NULL);
INSERT INTO `configuration` VALUES(55098, 'Tax Class', 'MODULE_SHIPPING_RMFIRST_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', 6, 0, NULL, '2011-06-17 10:04:06', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(');
INSERT INTO `configuration` VALUES(55099, 'Sort Order', 'MODULE_SHIPPING_RMFIRST_SORT_ORDER', '1', 'Sort order of display (1 shown first 99 etc shown last to customer)', 6, 0, NULL, '2011-06-17 10:04:06', NULL, NULL);
INSERT INTO `configuration` VALUES(55097, 'Enable RM First Class Postage', 'MODULE_SHIPPING_RMFIRST_STATUS', 'True', 'Do you want to offer this shipping option?', 6, 0, NULL, '2011-06-17 10:04:06', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55096, 'Version', 'MODULE_SHIPPING_RMFIRST_VERSION', '2.2.2', 'Sort order of display (1 shown first 99 etc shown last to customer)', 6, 0, NULL, '2011-06-17 10:04:06', NULL, NULL);
INSERT INTO `configuration` VALUES(55110, 'Packaging / Handling Fee', 'MODULE_SHIPPING_RMFIRST_HANDLING_1', '0', 'If you want to add extra costs to customers for jiffy bags etc, the cost can be entered below (eg enter 1.50 for a value of 1.50)', 6, 0, NULL, '2011-06-17 10:04:06', NULL, NULL);
INSERT INTO `configuration` VALUES(55169, 'Unit Length', 'SHIPPING_UNIT_LENGTH', 'IN', 'By what unit are your packages sized?', 7, 8, NULL, '2011-06-17 13:15:05', NULL, 'tep_cfg_select_option(array(''IN'', ''CM''), ');
INSERT INTO `configuration` VALUES(55168, 'Unit Weight', 'SHIPPING_UNIT_WEIGHT', 'LBS', 'By what unit are your packages weighed?', 7, 7, NULL, '2011-06-17 13:15:05', NULL, 'tep_cfg_select_option(array(''LBS'', ''KGS''), ');
INSERT INTO `configuration` VALUES(55167, 'Dimensions Support', 'SHIPPING_DIMENSIONS_SUPPORT', 'No', 'Do you use the additional dimensions support (read dimensions.txt in the UPSXML package)?', 7, 6, NULL, '2011-06-17 13:15:05', NULL, 'tep_cfg_select_option(array(''No'', ''Ready-to-ship only'', ''With product dimensions''), ');
INSERT INTO `configuration` VALUES(55286, 'Enable UPS Shipping', 'MODULE_SHIPPING_UPSXML_RATES_STATUS', 'True', 'Do you want to offer UPS shipping?', 6, 0, NULL, '2011-06-17 13:35:36', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55287, 'UPS Rates Access Key', 'MODULE_SHIPPING_UPSXML_RATES_ACCESS_KEY', '5C5B93CEDFBA5530', 'Enter the XML rates access key assigned to you by UPS.', 6, 1, NULL, '2011-06-17 13:35:36', NULL, NULL);
INSERT INTO `configuration` VALUES(55288, 'UPS Rates Username', 'MODULE_SHIPPING_UPSXML_RATES_USERNAME', 'jefs42', 'Enter your UPS Services account username.', 6, 2, NULL, '2011-06-17 13:35:36', NULL, NULL);
INSERT INTO `configuration` VALUES(55289, 'UPS Rates Password', 'MODULE_SHIPPING_UPSXML_RATES_PASSWORD', '2muchjoy', 'Enter your UPS Services account password.', 6, 3, NULL, '2011-06-17 13:35:36', NULL, NULL);
INSERT INTO `configuration` VALUES(55290, 'Pickup Method', 'MODULE_SHIPPING_UPSXML_RATES_PICKUP_METHOD', 'Daily Pickup', 'How do you give packages to UPS (only used when origin is US)?', 6, 4, NULL, '2011-06-17 13:35:36', NULL, 'tep_cfg_select_option(array(''Daily Pickup'', ''Customer Counter'', ''One Time Pickup'', ''On Call Air Pickup'', ''Letter Center'', ''Air Service Center'', ''Suggested Retail Rates (UPS Store)''), ');
INSERT INTO `configuration` VALUES(55291, 'Packaging Type', 'MODULE_SHIPPING_UPSXML_RATES_PACKAGE_TYPE', 'Package', 'What kind of packaging do you use?', 6, 5, NULL, '2011-06-17 13:35:36', NULL, 'tep_cfg_select_option(array(''Package'', ''UPS Letter'', ''UPS Tube'', ''UPS Pak'', ''UPS Express Box'', ''UPS 25kg Box'', ''UPS 10kg box''), ');
INSERT INTO `configuration` VALUES(55292, 'Customer Classification Code', 'MODULE_SHIPPING_UPSXML_RATES_CUSTOMER_CLASSIFICATION_CODE', '01', '01 - If you are billing to a UPS account and have a daily UPS pickup, 03 - If you do not have a UPS account or you are billing to a UPS account but do not have a daily pickup, 04 - If you are shipping from a retail outlet (only used when origin is US)', 6, 6, NULL, '2011-06-17 13:35:36', NULL, 'tep_cfg_select_option(array(''01'', ''03'', ''04''), ');
INSERT INTO `configuration` VALUES(55293, 'Shipping Origin', 'MODULE_SHIPPING_UPSXML_RATES_ORIGIN', 'US Origin', 'What origin point should be used (this setting affects only what UPS product names are shown to the user)', 6, 7, NULL, '2011-06-17 13:35:36', NULL, 'tep_cfg_select_option(array(''US Origin'', ''Canada Origin'', ''European Union Origin'', ''Puerto Rico Origin'', ''Mexico Origin'', ''All other origins''), ');
INSERT INTO `configuration` VALUES(55294, 'Origin City', 'MODULE_SHIPPING_UPSXML_RATES_CITY', 'New York', 'Enter the name of the origin city.', 6, 8, NULL, '2011-06-17 13:35:36', NULL, NULL);
INSERT INTO `configuration` VALUES(55295, 'Origin State/Province', 'MODULE_SHIPPING_UPSXML_RATES_STATEPROV', 'NY', 'Enter the two-letter code for your origin state/province.', 6, 9, NULL, '2011-06-17 13:35:36', NULL, NULL);
INSERT INTO `configuration` VALUES(55296, 'Origin Country', 'MODULE_SHIPPING_UPSXML_RATES_COUNTRY', 'US', 'Enter the two-letter code for your origin country.', 6, 10, NULL, '2011-06-17 13:35:36', NULL, NULL);
INSERT INTO `configuration` VALUES(55297, 'Origin Zip/Postal Code', 'MODULE_SHIPPING_UPSXML_RATES_POSTALCODE', '10265', 'Enter your origin zip/postalcode.', 6, 11, NULL, '2011-06-17 13:35:36', NULL, NULL);
INSERT INTO `configuration` VALUES(55298, 'Test or Production Mode', 'MODULE_SHIPPING_UPSXML_RATES_MODE', 'Production', 'Use this module in Test or Production mode?', 6, 12, NULL, '2011-06-17 13:35:36', NULL, 'tep_cfg_select_option(array(''Test'', ''Production''), ');
INSERT INTO `configuration` VALUES(55299, 'Quote Type', 'MODULE_SHIPPING_UPSXML_RATES_QUOTE_TYPE', 'Commercial', 'Quote for Residential or Commercial Delivery', 6, 15, NULL, '2011-06-17 13:35:36', NULL, 'tep_cfg_select_option(array(''Commercial'', ''Residential''), ');
INSERT INTO `configuration` VALUES(55300, 'Negotiated rates', 'MODULE_SHIPPING_UPSXML_RATES_USE_NEGOTIATED_RATES', 'False', 'Do you receive discounted rates from UPS and want to use these for shipping quotes? <strong>Note:</strong>  You need to enter your UPS account number below.', 6, 25, NULL, '2011-06-17 13:35:36', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55301, 'Manual Negotiated Rate', 'MODULE_SHIPPING_UPSXML_RATES_MANUAL_NEGOTIATED_RATE', '', 'Enter a negotiated rate manually. <strong>Note:</strong> If ''Negotiated Rates'' above is set to ''True'', This <strong>WILL NOT</strong> be applied. If using this option, set ''Negotiated Rates'' to ''False''. Usage: '' 57 '' returns 57% of published UPS rate.', 6, 26, NULL, '2011-06-17 13:35:36', NULL, NULL);
INSERT INTO `configuration` VALUES(55302, 'UPS Account Number', 'MODULE_SHIPPING_UPSXML_RATES_UPS_ACCOUNT_NUMBER', '', 'Enter your UPS Account number when you have and want to use negotiated rates.', 6, 27, NULL, '2011-06-17 13:35:36', NULL, NULL);
INSERT INTO `configuration` VALUES(55303, 'Handling Type', 'MODULE_SHIPPING_UPSXML_HANDLING_TYPE', 'Flat Fee', 'Handling type for this shipping method.', 6, 14, NULL, '2011-06-17 13:35:36', NULL, 'tep_cfg_select_option(array(''Flat Fee'', ''Percentage''), ');
INSERT INTO `configuration` VALUES(55304, 'Handling Fee', 'MODULE_SHIPPING_UPSXML_RATES_HANDLING', '0', 'Handling fee for this shipping method.', 6, 16, NULL, '2011-06-17 13:35:36', NULL, NULL);
INSERT INTO `configuration` VALUES(55305, 'UPS Currency Code', 'MODULE_SHIPPING_UPSXML_CURRENCY_CODE', '', 'Enter the 3 letter currency code for your country of origin. United States (USD)', 6, 2, NULL, '2011-06-17 13:35:36', NULL, NULL);
INSERT INTO `configuration` VALUES(55306, 'Enable Insurance', 'MODULE_SHIPPING_UPSXML_INSURE', 'True', 'Do you want to insure packages shipped by UPS?', 6, 22, NULL, '2011-06-17 13:35:36', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55307, 'Tax Class', 'MODULE_SHIPPING_UPSXML_RATES_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', 6, 17, NULL, '2011-06-17 13:35:36', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(');
INSERT INTO `configuration` VALUES(55308, 'Shipping Zone', 'MODULE_SHIPPING_UPSXML_RATES_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', 6, 18, NULL, '2011-06-17 13:35:36', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(');
INSERT INTO `configuration` VALUES(55309, 'Sort order of display.', 'MODULE_SHIPPING_UPSXML_RATES_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 19, NULL, '2011-06-17 13:35:36', NULL, NULL);
INSERT INTO `configuration` VALUES(55310, 'Disallowed Shipping Methods', 'MODULE_SHIPPING_UPSXML_TYPES', '--none--', 'Select the UPS services <span style=''color: red; font-weight: bold''>not</span> to be offered.', 6, 20, NULL, '2011-06-17 13:35:36', 'get_multioption_upsxml', 'upsxml_cfg_select_multioption_indexed(array(''US_01'', ''US_02'', ''US_03'', ''US_07'', ''US_54'', ''US_08'', ''CAN_01'', ''US_11'', ''US_12'', ''US_13'', ''US_14'', ''CAN_02'', ''US_59'', ''US_65'', ''CAN_14'', ''MEX_54'', ''EU_82'', ''EU_83'', ''EU_84'', ''EU_85'', ''EU_86''), ');
INSERT INTO `configuration` VALUES(55311, 'Shipping Delay', 'MODULE_SHIPPING_UPSXML_SHIPPING_DAYS_DELAY', '1', 'How many days from when an order is placed to when you ship it (Decimals are allowed). Arrival date estimations are based on this value.', 6, 21, NULL, '2011-06-17 13:35:36', NULL, NULL);
INSERT INTO `configuration` VALUES(55312, 'Email UPS errors', 'MODULE_SHIPPING_UPSXML_EMAIL_ERRORS', 'Yes', 'Do you want to receive UPS errors by email?', 6, 24, NULL, '2011-06-17 13:35:36', NULL, 'tep_cfg_select_option(array(''Yes'', ''No''), ');
INSERT INTO `configuration` VALUES(55313, 'Time in Transit View Type', 'MODULE_SHIPPING_UPSXML_RATES_TIME_IN_TRANSIT_VIEW', 'Not', 'If and how the module should display the time in transit to the customer.', 6, 13, NULL, '2011-06-17 13:35:36', NULL, 'tep_cfg_select_option(array(''Not'',''Raw'', ''Detailed''), ');
INSERT INTO `configuration` VALUES(55314, 'Display Weight', 'MODULE_SHIPPING_UPSXML_WEIGHT1', 'True', 'Do you want to show number of packages and package weight?', 6, 28, NULL, '2011-06-17 13:35:36', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55686, 'OpenSSL Location', 'MODULE_PAYMENT_PAYPAL_STANDARD_EWP_OPENSSL', '/usr/bin/openssl', 'The location of the openssl binary file.', 6, 4, NULL, '2012-03-27 18:47:35', NULL, NULL);
INSERT INTO `configuration` VALUES(55685, 'Working Directory', 'MODULE_PAYMENT_PAYPAL_STANDARD_EWP_WORKING_DIRECTORY', '', 'The working directory to use for temporary files. (trailing slash needed)', 6, 4, NULL, '2012-03-27 18:47:35', NULL, NULL);
INSERT INTO `configuration` VALUES(55683, 'PayPals Public Certificate', 'MODULE_PAYMENT_PAYPAL_STANDARD_EWP_PAYPAL_KEY', '', 'The location of the PayPal Public Certificate for encrypting the data.', 6, 4, NULL, '2012-03-27 18:47:35', NULL, NULL);
INSERT INTO `configuration` VALUES(55684, 'Your PayPal Public Certificate ID', 'MODULE_PAYMENT_PAYPAL_STANDARD_EWP_CERT_ID', '', 'The Certificate ID to use from your PayPal Encrypted Payment Settings Profile.', 6, 4, NULL, '2012-03-27 18:47:35', NULL, NULL);
INSERT INTO `configuration` VALUES(55681, 'Your Private Key', 'MODULE_PAYMENT_PAYPAL_STANDARD_EWP_PRIVATE_KEY', '', 'The location of your Private Key to use for signing the data. (*.pem)', 6, 4, NULL, '2012-03-27 18:47:35', NULL, NULL);
INSERT INTO `configuration` VALUES(55682, 'Your Public Certificate', 'MODULE_PAYMENT_PAYPAL_STANDARD_EWP_PUBLIC_KEY', '', 'The location of your Public Certificate to use for signing the data. (*.pem)', 6, 4, NULL, '2012-03-27 18:47:35', NULL, NULL);
INSERT INTO `configuration` VALUES(1891, 'Zones Module Quantity Option', 'ZONES_MODULE_NUMBER', '4', 'The Number of options needed for the Zones delivery Module', 7, 1004, NULL, '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `configuration` VALUES(55400, 'Zone 3 Shipping Table', 'MODULE_SHIPPING_ZONES_COST_3', '', 'Shipping rates to Zone 3 destinations based on a group of maximum order weights. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone 3 destinations.', 6, 0, NULL, '2011-06-25 23:02:21', NULL, NULL);
INSERT INTO `configuration` VALUES(55382, 'Enable Postcode Method', 'MODULE_SHIPPING_RMREC_STATUS', 'True', 'Do you want to offer Postcode rate shipping/delivery?', 6, 0, NULL, '2011-06-25 22:53:08', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55680, 'Enable Encrypted Web Payments', 'MODULE_PAYMENT_PAYPAL_STANDARD_EWP_STATUS', 'False', 'Do you want to enable Encrypted Web Payments?', 6, 3, NULL, '2012-03-27 18:47:35', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55397, 'Zone 2 Shipping Table', 'MODULE_SHIPPING_ZONES_COST_2', '', 'Shipping rates to Zone 2 destinations based on a group of maximum order weights. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone 2 destinations.', 6, 0, NULL, '2011-06-25 23:02:21', NULL, NULL);
INSERT INTO `configuration` VALUES(55398, 'Zone 2 Handling Fee', 'MODULE_SHIPPING_ZONES_HANDLING_2', '', 'Handling Fee for this shipping zone', 6, 0, NULL, '2011-06-25 23:02:21', NULL, NULL);
INSERT INTO `configuration` VALUES(55399, 'Zone 3 Countries', 'MODULE_SHIPPING_ZONES_COUNTRIES_3', '', 'Comma separated list of two character ISO country codes that are part of Zone 3.', 6, 0, NULL, '2011-06-25 23:02:21', NULL, NULL);
INSERT INTO `configuration` VALUES(55389, 'Order max weight', 'MODULE_SHIPPING_RMREC_MAX_WEIGHT', '2000', 'Maximum weight in g(s) for order', 6, 0, NULL, '2011-06-25 22:53:08', NULL, NULL);
INSERT INTO `configuration` VALUES(55390, 'Enable Zones Method', 'MODULE_SHIPPING_ZONES_STATUS', 'True', 'Do you want to offer zone rate shipping?', 6, 0, NULL, '2011-06-25 23:02:21', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55391, 'Tax Class', 'MODULE_SHIPPING_ZONES_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', 6, 0, NULL, '2011-06-25 23:02:21', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(');
INSERT INTO `configuration` VALUES(55387, 'Zone 1 Handling Fee', 'MODULE_SHIPPING_RMREC_HANDLING_1', '0.75', 'Handling Fee for this Postcode', 6, 0, NULL, '2011-06-25 22:53:08', NULL, NULL);
INSERT INTO `configuration` VALUES(55388, 'Order min weight', 'MODULE_SHIPPING_RMREC_MIN_WEIGHT', '0', 'Minimum weight in g(s) for order', 6, 0, NULL, '2011-06-25 22:53:08', NULL, NULL);
INSERT INTO `configuration` VALUES(55383, 'Tax Class', 'MODULE_SHIPPING_RMREC_TAX_CLASS', '0', 'Use the following tax class on the shipping/delivery fee.', 6, 0, NULL, '2011-06-25 22:53:08', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(');
INSERT INTO `configuration` VALUES(55384, 'Sort Order', 'MODULE_SHIPPING_RMREC_SORT_ORDER', '0', 'Sort order of display.', 6, 0, NULL, '2011-06-25 22:53:08', NULL, NULL);
INSERT INTO `configuration` VALUES(55385, 'Zone 1 Postcode(s)', 'MODULE_SHIPPING_RMREC_CODES_1', 'IM, AB, AL, B, BA, BB, BD, BH, BL, BN, BR, BS, BT, CA, CB, CF, CH, CM, CO, CR, CT, CV, CW, DA, DD, DE, DG, DH, DL, DN, DT, DY, E, EC, EH, EN, EX, FK, FY, G, GL, GU, HA, HD, HG, HP, HR, HS, HU, HX, IG, IP, IV, KA, KT, KW, KY, L, LA, LD, LE, LL, LN, LS, LU, M, ME, MK, ML, N, NE, NG, NN, NP, NR, NW, OL, OX, PA, PE, PH, PL, PO, PR, RG, RH, RM, S, SA, SE, SG, SK, SL, SM, SN, SO, SP, SR, SS, ST, SW, SY, TA, TD, TF, TN, TQ, TR, TS, TW, UB, W, WA, WC, WD, WF, WN, WR, WS, WV, YO, ZE', 'Comma separated list of postcodes that are part of Zone 1.', 6, 0, NULL, '2011-06-25 22:53:08', NULL, NULL);
INSERT INTO `configuration` VALUES(55386, 'Zone 1 Shipping/Delivery Fee Table', 'MODULE_SHIPPING_RMREC_COST_1', '0:00,100:1.00,250:1.62,500:2.14,750:2.65,1000:3.25,1250:4.45,1500:5.15,1750:5.85,25000:6.50,45000:12.50,70000:18.50,90000:24.50, 115000:30.50,135000:36.50,160000:42.50,185000:48.50,210000:54.50,235000:60.50', 'Shipping rates to Zone 1 destinations based on a group of maximum order weights. Example: 4:5,8:7,... weights less than or equal to 4 would cost $5 for Zone 1 destinations.', 6, 0, NULL, '2011-06-25 22:53:08', NULL, NULL);
INSERT INTO `configuration` VALUES(55401, 'Zone 3 Handling Fee', 'MODULE_SHIPPING_ZONES_HANDLING_3', '', 'Handling Fee for this shipping zone', 6, 0, NULL, '2011-06-25 23:02:21', NULL, NULL);
INSERT INTO `configuration` VALUES(55402, 'Zone 4 Countries', 'MODULE_SHIPPING_ZONES_COUNTRIES_4', '', 'Comma separated list of two character ISO country codes that are part of Zone 4.', 6, 0, NULL, '2011-06-25 23:02:21', NULL, NULL);
INSERT INTO `configuration` VALUES(55403, 'Zone 4 Shipping Table', 'MODULE_SHIPPING_ZONES_COST_4', '', 'Shipping rates to Zone 4 destinations based on a group of maximum order weights. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone 4 destinations.', 6, 0, NULL, '2011-06-25 23:02:21', NULL, NULL);
INSERT INTO `configuration` VALUES(55404, 'Zone 4 Handling Fee', 'MODULE_SHIPPING_ZONES_HANDLING_4', '', 'Handling Fee for this shipping zone', 6, 0, NULL, '2011-06-25 23:02:21', NULL, NULL);
INSERT INTO `configuration` VALUES(55405, 'Worldwide Delivery Title', 'MODULE_SHIPPING_ZONES_TEXT_TITLE', 'Worldwide Delivery', 'The text used as the title of this module', 7, 0, NULL, '2011-06-25 23:02:21', NULL, NULL);
INSERT INTO `configuration` VALUES(55406, 'Worldwide Delivery description Text', 'MODULE_SHIPPING_ZONES_TEXT_DESCRIPTION', 'Delivery outside of Europe', 'The text used as the description of this module', 7, 0, NULL, '2011-06-25 23:02:21', NULL, NULL);
INSERT INTO `configuration` VALUES(55407, 'Worldwide Delivery Way Text', 'MODULE_SHIPPING_ZONES_TEXT_WAY', 'Shipping to', 'The text used as the description of this module', 7, 0, NULL, '2011-06-25 23:02:21', NULL, NULL);
INSERT INTO `configuration` VALUES(55408, 'Weight Text', 'MODULE_SHIPPING_ZONES_TEXT_UNITS', 'kg(s)', 'The text used in association to weight', 7, 0, NULL, '2011-06-25 23:02:21', NULL, NULL);
INSERT INTO `configuration` VALUES(55409, 'Invalid Country Text', 'MODULE_SHIPPING_ZONES_INVALID_ZONE', 'Sorry, this method is not available for your country.', 'TThe text used to inform customer that this method is not available outside the UK', 7, 0, NULL, '2011-06-25 23:02:21', NULL, NULL);
INSERT INTO `configuration` VALUES(55410, 'Underfined Rate Text', 'MODULE_SHIPPING_ZONES_UNDEFINED_RATE', 'The shipping rate cannot be determined at this time.', 'The text used if weight is not covered', 7, 0, NULL, '2011-06-25 23:02:21', NULL, NULL);
INSERT INTO `configuration` VALUES(55679, 'Debug E-Mail Address', 'MODULE_PAYMENT_PAYPAL_STANDARD_DEBUG_EMAIL', '', 'All parameters of an Invalid IPN notification will be sent to this email address if one is entered.', 6, 4, NULL, '2012-03-27 18:47:35', NULL, NULL);
INSERT INTO `configuration` VALUES(55678, 'Page Style', 'MODULE_PAYMENT_PAYPAL_STANDARD_PAGE_STYLE', '', 'The page style to use for the transaction procedure (defined at your PayPal Profile page)', 6, 4, NULL, '2012-03-27 18:47:35', NULL, NULL);
INSERT INTO `configuration` VALUES(55677, 'Transaction Method', 'MODULE_PAYMENT_PAYPAL_STANDARD_TRANSACTION_METHOD', 'Sale', 'The processing method to use for each transaction.', 6, 0, NULL, '2012-03-27 18:47:35', NULL, 'tep_cfg_select_option(array(''Authorization'', ''Sale''), ');
INSERT INTO `configuration` VALUES(55675, 'Set PayPal Acknowledged Order Status', 'MODULE_PAYMENT_PAYPAL_STANDARD_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', 6, 0, NULL, '2012-03-27 18:47:35', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses(');
INSERT INTO `configuration` VALUES(55676, 'Gateway Server', 'MODULE_PAYMENT_PAYPAL_STANDARD_GATEWAY_SERVER', 'Live', 'Use the testing (sandbox) or live gateway server for transactions?', 6, 6, NULL, '2012-03-27 18:47:35', NULL, 'tep_cfg_select_option(array(''Live'', ''Sandbox''), ');
INSERT INTO `configuration` VALUES(55673, 'Payment Zone', 'MODULE_PAYMENT_PAYPAL_STANDARD_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', 6, 2, NULL, '2012-03-27 18:47:35', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(');
INSERT INTO `configuration` VALUES(55649, 'Make Payable to:', 'MODULE_PAYMENT_MONEYORDER_PAYTO', 'GCustoms.com', 'Who should payments be made payable to?', 6, 1, NULL, '2012-03-18 02:32:00', NULL, NULL);
INSERT INTO `configuration` VALUES(55650, 'Sort order of display.', 'MODULE_PAYMENT_MONEYORDER_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2012-03-18 02:32:00', NULL, NULL);
INSERT INTO `configuration` VALUES(55651, 'Payment Zone', 'MODULE_PAYMENT_MONEYORDER_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', 6, 2, NULL, '2012-03-18 02:32:00', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(');
INSERT INTO `configuration` VALUES(55652, 'Set Order Status', 'MODULE_PAYMENT_MONEYORDER_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', 6, 0, NULL, '2012-03-18 02:32:00', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses(');
INSERT INTO `configuration` VALUES(55674, 'Set Preparing Order Status', 'MODULE_PAYMENT_PAYPAL_STANDARD_PREPARE_ORDER_STATUS_ID', '4', 'Set the status of prepared orders made with this payment module to this value', 6, 0, NULL, '2012-03-27 18:47:35', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses(');
INSERT INTO `configuration` VALUES(55454, 'Enable USPS Shipping', 'MODULE_SHIPPING_USPS_STATUS', 'True', 'Do you want to offer USPS shipping?', 6, 0, NULL, '2011-07-15 23:02:08', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55455, 'Enter the USPS User ID', 'MODULE_SHIPPING_USPS_USERID', '048STRON2639', 'Enter the USPS USERID assigned to you. <u>You must contact USPS to have them switch you to the Production server.</u>  Otherwise this module will not work!', 6, 3, NULL, '2011-07-15 23:02:08', NULL, 'tep_cfg_multiinput_list(array(''''), ');
INSERT INTO `configuration` VALUES(55456, 'Sort Order', 'MODULE_SHIPPING_USPS_SORT_ORDER', '0', 'Sort order of display.', 6, 0, NULL, '2011-07-15 23:02:08', NULL, NULL);
INSERT INTO `configuration` VALUES(55457, 'Tax Class', 'MODULE_SHIPPING_USPS_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', 6, 0, NULL, '2011-07-15 23:02:08', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(');
INSERT INTO `configuration` VALUES(55458, 'Shipping Zone', 'MODULE_SHIPPING_USPS_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', 6, 0, NULL, '2011-07-15 23:02:08', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(');
INSERT INTO `configuration` VALUES(55459, 'Display Options', 'MODULE_SHIPPING_USPS_OPTIONS', 'Display weight, Display transit time, Display insurance', 'Select display options', 6, 0, NULL, '2011-07-15 23:02:08', NULL, 'tep_cfg_select_multioption(array(''Display weight'', ''Display transit time'', ''Display insurance''), ');
INSERT INTO `configuration` VALUES(55460, 'Processing Time', 'MODULE_SHIPPING_USPS_PROCESSING', '1', 'Days to Process Order', 6, 0, NULL, '2011-07-15 23:02:08', NULL, NULL);
INSERT INTO `configuration` VALUES(55461, 'Domestic Shipping Methods', 'MODULE_SHIPPING_USPS_DMSTC_TYPES', 'First-Class Mail regimark, Media Mail regimark, Parcel Post regimark, Priority Mail regimark, Priority Mail regimark Flat Rate Envelope, Priority Mail regimark Small Flat Rate Box, Priority Mail regimark Medium Flat Rate Box, Priority Mail regimark Large Flat Rate Box, Express Mail regimark, Express Mail regimark Flat Rate Envelope', 'Select the domestic services to be offered:', 6, 4, NULL, '2011-07-15 23:02:08', NULL, 'tep_cfg_select_multioption(array(''First-Class Mail regimark'', ''Media Mail regimark'', ''Parcel Post regimark'', ''Priority Mail regimark'', ''Priority Mail regimark Flat Rate Envelope'', ''Priority Mail regimark Small Flat Rate Box'', ''Priority Mail regimark Medium Flat Rate Box'', ''Priority Mail regimark Large Flat Rate Box'', ''Express Mail regimark'', ''Express Mail regimark Flat Rate Envelope''), ');
INSERT INTO `configuration` VALUES(55462, 'Domestic Rates', 'MODULE_SHIPPING_USPS_DMSTC_RATE', 'Retail', 'Charge retail pricing or internet pricing?', 6, 0, NULL, '2011-07-15 23:02:08', NULL, 'tep_cfg_select_option(array(''Retail'', ''Internet''), ');
INSERT INTO `configuration` VALUES(55463, 'Domestic Delivery Confirmation', 'MODULE_SHIPPING_USPS_DMST_DEL_CONF', 'True', 'Automatically charge Delivery Confirmation for first class and parcel ($0.19)?', 6, 0, NULL, '2011-07-15 23:02:08', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55464, 'Domestic Signature Confirmation', 'MODULE_SHIPPING_USPS_DMST_SIG_CONF', 'True', 'Automatically charge Signature Confirmation when available ($1.95)?', 6, 0, NULL, '2011-07-15 23:02:08', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55465, 'Signature Confirmation Threshold', 'MODULE_SHIPPING_USPS_SIG_THRESH', '100', 'Order total required before Signature Confirmation is triggered?', 6, 0, NULL, '2011-07-15 23:02:08', NULL, NULL);
INSERT INTO `configuration` VALUES(55466, 'Domestic Insurance Options', 'MODULE_SHIPPING_USPS_DMSTC_INSURANCE_OPTION', 'False', 'Force USPS Calculated Domestic Insurance?', 6, 0, NULL, '2011-07-15 23:02:08', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55467, 'Domestic Flat Handling Fees', 'MODULE_SHIPPING_USPS_DMSTC_HANDLING', '0, 0, 0, 0, 0, 0, 0, 0, 0, 0', 'Add a different handling fee for each shipping type.', 6, 0, NULL, '2011-07-15 23:02:08', NULL, 'tep_cfg_multiinput_list(array(''First-Class'', ''Media'', ''Parcel'', ''Priority'', ''Priority Flat Env'', ''Priority Sm Flat Box'', ''Priority Med Flat Box'', ''Priority Lg Flat Box'', ''Express'', ''Express Flat Env''), ');
INSERT INTO `configuration` VALUES(55468, 'Domestic First-Class Threshold', 'MODULE_SHIPPING_USPS_DMSTC_FIRSTCLASS_THRESHOLD', '0, 3.5, 3.5, 10, 10, 13', '<u>Maximums:</u><br>Letters 3.5oz<br>Large envelopes and parcels 13oz', 6, 0, NULL, '2011-07-15 23:02:08', NULL, 'tep_cfg_multiinput_duallist_oz(array(''Letter'', ''Lg Env'', ''Package''), ');
INSERT INTO `configuration` VALUES(55469, 'Domestic Other Mail Threshold', 'MODULE_SHIPPING_USPS_DMSTC_OTHER_THRESHOLD', '0, 3, 0, 3, 3, 11, 11, 15, 0, 70, 0, 3, 0, 70, 0, 70, 0, 70', '<u>Maximums:</u><br>70 lb', 6, 0, NULL, '2011-07-15 23:02:08', NULL, 'tep_cfg_multiinput_duallist_lb(array(''Flat Rate Envelope'', ''Sm Flat Rate Box'', ''Md Flat Rate Box'', ''Lg Flat Rate Box'', ''Standard Priority'', ''Express FltRt Env'', ''Express Standard'', ''Parcel Pst'', ''Media Mail''), ');
INSERT INTO `configuration` VALUES(55470, 'Int''l Shipping Methods', 'MODULE_SHIPPING_USPS_INTL_TYPES', 'Global Express, Global Express Non-Doc Rect, Global Express Non-Doc Non-Rect, USPS GXG Envelopes, Express Mail Int, Express Mail Int Flat Rate Env, Priority Mail International, Priority Mail Int Flat Rate Env, Priority Mail Int Flat Rate Small Box, Priority Mail Int Flat Rate Med Box, Priority Mail Int Flat Rate Lrg Box, First-Class Mail Int Lrg Env, First-Class Mail Int Package, First-Class Mail Int Letter', 'Select the international services to be offered:', 6, 0, NULL, '2011-07-15 23:02:08', NULL, 'tep_cfg_select_multioption(array(''Global Express'', ''Global Express Non-Doc Rect'', ''Global Express Non-Doc Non-Rect'', ''USPS GXG Envelopes'', ''Express Mail Int'', ''Express Mail Int Flat Rate Env'', ''Priority Mail International'', ''Priority Mail Int Flat Rate Env'', ''Priority Mail Int Flat Rate Small Box'', ''Priority Mail Int Flat Rate Med Box'', ''Priority Mail Int Flat Rate Lrg Box'', ''First-Class Mail Int Lrg Env'', ''First-Class Mail Int Package'', ''First-Class Mail Int Letter''), ');
INSERT INTO `configuration` VALUES(55471, 'Int''l Rates', 'MODULE_SHIPPING_USPS_INTL_RATE', 'Retail', 'Charge retail pricing or internet pricing?', 6, 0, NULL, '2011-07-15 23:02:08', NULL, 'tep_cfg_select_option(array(''Retail'', ''Internet''), ');
INSERT INTO `configuration` VALUES(55472, 'Int''l Insurance Options', 'MODULE_SHIPPING_USPS_INTL_INSURANCE_OPTION', 'False', 'Force USPS Calculated International Insurance?', 6, 0, NULL, '2011-07-15 23:02:08', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55473, 'Int''l Flat Handling Fees', 'MODULE_SHIPPING_USPS_INTL_HANDLING', '0', 'Add a flat fee international shipping.', 6, 0, NULL, '2011-07-15 23:02:08', NULL, 'tep_cfg_multiinput_list(array(''''), ');
INSERT INTO `configuration` VALUES(55474, 'Int''l Package Sizes', 'MODULE_SHIPPING_USPS_INTL_SIZE', '1, 1, 1, 0', 'Standard package dimensions required by USPS for international rates', 6, 0, NULL, '2011-07-15 23:02:08', NULL, 'tep_cfg_multiinput_list(array(''Width'', ''Length'', ''Height'', ''Girth''), ');
INSERT INTO `configuration` VALUES(55475, 'Non USPS Insurance - Domestic and international', 'MODULE_SHIPPING_USPS_INSURE', 'False', 'Would you like to charge insurance for packages independent of USPS, i.e, merchant provided, Stamps.com, Endicia?  If used in conjunction with USPS calculated insurance, the higher of the two will apply.', 6, 0, NULL, '2011-07-15 23:02:08', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55476, 'Non USPS Insurance', 'MODULE_SHIPPING_USPS_INS1', '1.75', 'Totals $.01-$50.00', 6, 0, NULL, '2011-07-15 23:02:08', 'currencies->format', NULL);
INSERT INTO `configuration` VALUES(55477, 'Non USPS Insurance', 'MODULE_SHIPPING_USPS_INS2', '2.25', 'Totals $50.01-$100', 6, 0, NULL, '2011-07-15 23:02:08', 'currencies->format', NULL);
INSERT INTO `configuration` VALUES(55478, 'Non USPS Insurance', 'MODULE_SHIPPING_USPS_INS3', '2.75', 'Totals $100.01-$200', 6, 0, NULL, '2011-07-15 23:02:08', 'currencies->format', NULL);
INSERT INTO `configuration` VALUES(55479, 'Non USPS Insurance', 'MODULE_SHIPPING_USPS_INS4', '4.70', 'Totals $200.01-$300', 6, 0, NULL, '2011-07-15 23:02:08', 'currencies->format', NULL);
INSERT INTO `configuration` VALUES(55480, 'Non USPS Insurance', 'MODULE_SHIPPING_USPS_INS5', '1.00', 'For every $100 over $300 (add)', 6, 0, NULL, '2011-07-15 23:02:08', 'currencies->format', NULL);
INSERT INTO `configuration` VALUES(55481, 'Insure Tax?', 'MODULE_SHIPPING_USPS_INSURE_TAX', 'False', 'Would you like to insure sales tax paid by the customer?', 6, 0, NULL, '2011-07-15 23:02:08', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55482, 'Store is offline', 'TAKE_STORE_OFFLINE', 'False', 'Take store offline for maintenance', 1, 15, '2011-07-21 11:28:03', '2008-01-19 13:34:03', '', 'tep_cfg_select_option(array(''True'',''False''),');
INSERT INTO `configuration` VALUES(55483, 'Allowed IPs when offline', 'STORE_OFFLINE_ALLOW', '', 'When offline the specified IP addresses (comma separated, es: 123.123.123.123,222.222.222.222) are allowed to access the catalog', 1, 16, '2010-08-10 00:00:00', '2010-08-10 00:00:00', NULL, NULL);
INSERT INTO `configuration` VALUES(55501, 'Enable Postcode Method', 'MODULE_SHIPPING_ZIPSHIP_STATUS', 'True', 'Do you want to offer Postcode rate shipping/delivery?', 6, 0, NULL, '2011-09-29 11:09:41', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55502, 'Tax Class', 'MODULE_SHIPPING_ZIPSHIP_TAX_CLASS', '0', 'Use the following tax class on the shipping/delivery fee.', 6, 0, NULL, '2011-09-29 11:09:41', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(');
INSERT INTO `configuration` VALUES(55503, 'Sort Order', 'MODULE_SHIPPING_ZIPSHIP_SORT_ORDER', '0', 'Sort order of display.', 6, 0, NULL, '2011-09-29 11:09:41', NULL, NULL);
INSERT INTO `configuration` VALUES(55504, 'Zone 1 Postcode(s)', 'MODULE_SHIPPING_ZIPSHIP_CODES_1', '32903,32937,^45', 'Comma separated list of postcodes that are part of Zone 1.', 6, 0, NULL, '2011-09-29 11:09:41', NULL, NULL);
INSERT INTO `configuration` VALUES(55505, 'Zone 1 Shipping/Delivery Fee Table', 'MODULE_SHIPPING_ZIPSHIP_COST_1', '4:5,10:6,99:10', 'Shipping rates to Zone 1 destinations based on a group of maximum order weights. Example: 4:5,8:7,... weights less than or equal to 4 would cost $5 for Zone 1 destinations.', 6, 0, NULL, '2011-09-29 11:09:41', NULL, NULL);
INSERT INTO `configuration` VALUES(55506, 'Zone 1 Handling Fee', 'MODULE_SHIPPING_ZIPSHIP_HANDLING_1', '0', 'Handling Fee for this Postcode', 6, 0, NULL, '2011-09-29 11:09:41', NULL, NULL);
INSERT INTO `configuration` VALUES(55507, 'Zone 2 Postcode(s)', 'MODULE_SHIPPING_ZIPSHIP_CODES_2', '32901,32935', 'Comma separated list of postcodes that are part of Zone 2.', 6, 0, NULL, '2011-09-29 11:09:41', NULL, NULL);
INSERT INTO `configuration` VALUES(55508, 'Zone 2 Shipping/Delivery Fee Table', 'MODULE_SHIPPING_ZIPSHIP_COST_2', '4:7,10:10,99:13.50', 'Shipping rates to Zone 2 destinations based on a group of maximum order weights. Example: 4:5,8:7,... weights less than or equal to 4 would cost $5 for Zone 2 destinations.', 6, 0, NULL, '2011-09-29 11:09:41', NULL, NULL);
INSERT INTO `configuration` VALUES(55509, 'Zone 2 Handling Fee', 'MODULE_SHIPPING_ZIPSHIP_HANDLING_2', '0', 'Handling Fee for this Postcode', 6, 0, NULL, '2011-09-29 11:09:41', NULL, NULL);
INSERT INTO `configuration` VALUES(555100, 'Google Analytics Key', 'GOOGLE_UA', 'ua-', 'Google Analytics Key', 1, 60, '2012-04-03 01:08:25', '2011-11-25 22:23:23', NULL, NULL);
INSERT INTO `configuration` VALUES(55518, 'Sort Order', 'MODULE_SHIPPING_MZMT_SORT_ORDER', '0', 'Sort order of display.', 6, 0, NULL, '2011-11-28 22:20:41', NULL, NULL);
INSERT INTO `configuration` VALUES(55517, 'Tax Class', 'MODULE_SHIPPING_MZMT_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', 6, 0, NULL, '2011-11-28 22:20:41', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(');
INSERT INTO `configuration` VALUES(55516, 'Enable MultiRegion MultiTable Method', 'MODULE_SHIPPING_MZMT_STATUS', 'True', 'Do you want to offer multi-region multi-table rate shipping?', 6, 0, NULL, '2011-11-28 22:20:41', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(55514, 'Number of Geo Zones', 'MODULE_SHIPPING_MZMT_NUMBER_GEOZONES', '5', 'The number of shipping geo zones.', 888007, 0, '2011-12-03 22:28:24', '2011-11-28 22:15:55', NULL, NULL);
INSERT INTO `configuration` VALUES(55515, 'Number of Tables per Geo Zone', 'MODULE_SHIPPING_MZMT_NUMBER_TABLES', '1', 'The number of shipping tables per geo zone.', 888007, 0, '2011-12-03 22:28:14', '2011-11-28 22:15:55', NULL, NULL);
INSERT INTO `configuration` VALUES(55519, '<hr />Geo Zone 1', 'MODULE_SHIPPING_MZMT_GEOZONE_1_ID', '', 'Enable this for the following geo zone.', 6, 0, NULL, '2011-11-28 22:20:41', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(');
INSERT INTO `configuration` VALUES(55520, 'Geo Zone 1 Table Method', 'MODULE_SHIPPING_MZMT_GEOZONE_1_MODE', 'weight', 'The shipping cost is based on the total weight, total price, or total count of the items ordered.', 6, 0, NULL, '2011-11-28 22:20:41', NULL, 'tep_cfg_select_option(array(''weight'', ''price'', ''count''), ');
INSERT INTO `configuration` VALUES(55521, 'Geo Zone 1 Shipping Table 1', 'MODULE_SHIPPING_MZMT_GEOZONE_1_TABLE_1', '', 'Shipping table 1 for this geo zone', 6, 0, NULL, '2011-11-28 22:20:41', NULL, NULL);
INSERT INTO `configuration` VALUES(55522, 'Geo Zone 1 Shipping Table 2', 'MODULE_SHIPPING_MZMT_GEOZONE_1_TABLE_2', '', 'Shipping table 2 for this geo zone', 6, 0, NULL, '2011-11-28 22:20:41', NULL, NULL);
INSERT INTO `configuration` VALUES(55523, 'Geo Zone 1 Shipping Table 3', 'MODULE_SHIPPING_MZMT_GEOZONE_1_TABLE_3', '', 'Shipping table 3 for this geo zone', 6, 0, NULL, '2011-11-28 22:20:41', NULL, NULL);
INSERT INTO `configuration` VALUES(55524, 'Geo Zone 1 Handling Fee', 'MODULE_SHIPPING_MZMT_GEOZONE_1_HANDLING', '0', 'Handling Fee for this shipping geo zone', 6, 0, NULL, '2011-11-28 22:20:41', NULL, NULL);
INSERT INTO `configuration` VALUES(55525, '<hr />Geo Zone 2', 'MODULE_SHIPPING_MZMT_GEOZONE_2_ID', '', 'Enable this for the following geo zone.', 6, 0, NULL, '2011-11-28 22:20:41', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(');
INSERT INTO `configuration` VALUES(55526, 'Geo Zone 2 Table Method', 'MODULE_SHIPPING_MZMT_GEOZONE_2_MODE', 'weight', 'The shipping cost is based on the total weight, total price, or total count of the items ordered.', 6, 0, NULL, '2011-11-28 22:20:41', NULL, 'tep_cfg_select_option(array(''weight'', ''price'', ''count''), ');
INSERT INTO `configuration` VALUES(55527, 'Geo Zone 2 Shipping Table 1', 'MODULE_SHIPPING_MZMT_GEOZONE_2_TABLE_1', '', 'Shipping table 1 for this geo zone', 6, 0, NULL, '2011-11-28 22:20:41', NULL, NULL);
INSERT INTO `configuration` VALUES(55528, 'Geo Zone 2 Shipping Table 2', 'MODULE_SHIPPING_MZMT_GEOZONE_2_TABLE_2', '', 'Shipping table 2 for this geo zone', 6, 0, NULL, '2011-11-28 22:20:41', NULL, NULL);
INSERT INTO `configuration` VALUES(55529, 'Geo Zone 2 Shipping Table 3', 'MODULE_SHIPPING_MZMT_GEOZONE_2_TABLE_3', '', 'Shipping table 3 for this geo zone', 6, 0, NULL, '2011-11-28 22:20:41', NULL, NULL);
INSERT INTO `configuration` VALUES(55530, 'Geo Zone 2 Handling Fee', 'MODULE_SHIPPING_MZMT_GEOZONE_2_HANDLING', '0', 'Handling Fee for this shipping geo zone', 6, 0, NULL, '2011-11-28 22:20:41', NULL, NULL);
INSERT INTO `configuration` VALUES(55531, '<hr />Geo Zone 3', 'MODULE_SHIPPING_MZMT_GEOZONE_3_ID', '', 'Enable this for the following geo zone.', 6, 0, NULL, '2011-11-28 22:20:41', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(');
INSERT INTO `configuration` VALUES(55532, 'Geo Zone 3 Table Method', 'MODULE_SHIPPING_MZMT_GEOZONE_3_MODE', 'weight', 'The shipping cost is based on the total weight, total price, or total count of the items ordered.', 6, 0, NULL, '2011-11-28 22:20:41', NULL, 'tep_cfg_select_option(array(''weight'', ''price'', ''count''), ');
INSERT INTO `configuration` VALUES(55533, 'Geo Zone 3 Shipping Table 1', 'MODULE_SHIPPING_MZMT_GEOZONE_3_TABLE_1', '', 'Shipping table 1 for this geo zone', 6, 0, NULL, '2011-11-28 22:20:41', NULL, NULL);
INSERT INTO `configuration` VALUES(55534, 'Geo Zone 3 Shipping Table 2', 'MODULE_SHIPPING_MZMT_GEOZONE_3_TABLE_2', '', 'Shipping table 2 for this geo zone', 6, 0, NULL, '2011-11-28 22:20:41', NULL, NULL);
INSERT INTO `configuration` VALUES(55535, 'Geo Zone 3 Shipping Table 3', 'MODULE_SHIPPING_MZMT_GEOZONE_3_TABLE_3', '', 'Shipping table 3 for this geo zone', 6, 0, NULL, '2011-11-28 22:20:41', NULL, NULL);
INSERT INTO `configuration` VALUES(55536, 'Geo Zone 3 Handling Fee', 'MODULE_SHIPPING_MZMT_GEOZONE_3_HANDLING', '0', 'Handling Fee for this shipping geo zone', 6, 0, NULL, '2011-11-28 22:20:41', NULL, NULL);
INSERT INTO `configuration` VALUES(55537, 'Skip Shipping Products Available', 'SKIP_SHIPPING_DOWNLOADS_ZERO_WEIGHT', 'No', 'Will this store ship downloadable products or have zero weight products that will skip the shipping modules page?', 1, 21, '2011-12-01 16:18:51', '2011-12-01 15:54:48', NULL, 'tep_cfg_select_option(array(''Yes'', ''No''),');
INSERT INTO `configuration` VALUES(55538, 'PHPIDS Module (Admin)', 'PHPIDS_MODULE_ADMIN', 'false', 'Enable PHPIDS for the admin area.', 888006, 1, '2011-12-13 07:02:11', '2011-06-13 10:39:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(55645, 'Constant Contact Password', 'CONSTANT_CONTACT_PW', '', 'Password for your Constant Contact account.', 5, 44, '2012-05-14 23:09:05', '2012-02-23 10:16:04', NULL, NULL);
INSERT INTO `configuration` VALUES(55647, 'Constant Contact List', 'CONSTANT_CONTACT_LIST_ID', '2', 'ID of Constant Contact List to subscribe customers to.', 5, 46, '2012-02-24 00:41:40', '2012-02-23 23:44:36', NULL, NULL);
INSERT INTO `configuration` VALUES(55644, 'Constant Contact Login', 'CONSTANT_CONTACT_USER', '', 'Username for your Constant Contact account.', 5, 43, '2012-05-14 23:08:43', '2012-02-23 10:16:04', NULL, NULL);
INSERT INTO `configuration` VALUES(55643, 'Constant Contact Newsletter', 'USE_CONSTANT_CONTACT', 'false', 'Use Constant Contact for newsletter subscriptions.', 5, 42, '2012-05-30 08:13:45', '2012-02-23 10:16:04', NULL, 'tep_cfg_select_option(array(''true'', ''false''),');
INSERT INTO `configuration` VALUES(55687, 'Save Credit Card Number to Database', 'CONFIG_SAVE_CC_NUMBER', 'true', 'Save customer''s credit card number to database.', 1, 25, NULL, '2012-04-22 11:48:41', NULL, 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(555116, 'Display Total', 'MODULE_ORDER_TOTAL_COUPON_STATUS', 'true', 'Do you want to display the Discount Coupon value?', 6, 1, NULL, '2012-07-03 19:28:53', NULL, 'tep_cfg_select_option(array(''true'', ''false''), ');
INSERT INTO `configuration` VALUES(555158, 'Printable Catalog-Manufacturers column', 'PRODUCT_LIST_CATALOG_MANUFACTURERS', 'hide', 'Do you want to display the Manufacturers column?', 899, 10, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, 'tep_cfg_select_option(array(''show'', ''hide''),');
INSERT INTO `configuration` VALUES(555157, 'Printable Catalog-Name column', 'PRODUCT_LIST_CATALOG_NAME', 'show', 'Do you want to display the Name column?', 899, 9, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, 'tep_cfg_select_option(array(''show'', ''hide''),');
INSERT INTO `configuration` VALUES(555156, 'Printable Catalog-Options column', 'PRODUCT_LIST_CATALOG_OPTIONS', 'hide', 'Do you want to display the Options colum?', 899, 8, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, 'tep_cfg_select_option(array(''show'', ''hide''),');
INSERT INTO `configuration` VALUES(555155, 'Printable Catalog-Image column in full catalog url page', 'PRODUCT_LIST_CATALOG_IMAGE_FULL', 'hide', 'Do you want to display the Image column in the Full Catalog Script?(catalog_products_with_images_full.php) note: hide image colum for faster page loads on full catalog', 899, 7, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, 'tep_cfg_select_option(array(''show'', ''hide''),');
INSERT INTO `configuration` VALUES(555149, 'Printable Catalog-Customer Discount in Catalog', 'PRODUCT_LIST_CUSTOMER_DISCOUNT', 'hide', 'Setting to -show- will display the catalog with a customer discount applied if logged in. It will display pricing without discount if not logged in. (only valid if Members Discount Mod is loaded. Default if Mod not present is -hide-)', 899, 0, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, 'tep_cfg_select_option(array(''show'', ''hide''),');
INSERT INTO `configuration` VALUES(555154, 'Printable Catalog-Image column in standard url page', 'PRODUCT_LIST_CATALOG_IMAGE', 'show', 'Do you want to display the Image column?(catalog_products_with_images.php) ', 899, 6, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, 'tep_cfg_select_option(array(''show'', ''hide''),');
INSERT INTO `configuration` VALUES(555152, 'Printable Catalog-Show Results Per Page Links', 'PRODUCT_LIST_CATALOG_PERPAGESHOW', 'show', 'Do you want to display the Results Per Page Link?', 899, 4, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, 'tep_cfg_select_option(array(''show'', ''hide''),');
INSERT INTO `configuration` VALUES(555153, 'Printable Catalog-Length of the Description Text', 'PRODUCT_LIST_DESCRIPTION_LENGTH', '400', 'How many characters in the description to display?', 899, 5, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, NULL);
INSERT INTO `configuration` VALUES(555151, 'Printable Catalog-Results Per Page', 'PRODUCT_LIST_CATALOG_PERPAGE', '10', 'How many products do you want to list per page? (Setting this value 1 above the total number of products you have will list all products in one page. Then setting hide on -Show Results Per Page Link- might be wanted', 899, 3, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, NULL);
INSERT INTO `configuration` VALUES(555150, 'Printable Catalog-Number of Page Breaks Displayed', 'PRODUCT_LIST_PAGEBREAK_NUMBERS_PERPAGE', '10', 'How page breaks numbers to display?', 899, 2, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, NULL);
INSERT INTO `configuration` VALUES(555159, 'Printable Catalog-Description column', 'PRODUCT_LIST_CATALOG_DESCRIPTION', 'hide', 'Do you want to display the Products Description column?', 899, 11, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, 'tep_cfg_select_option(array(''show'', ''hide''),');
INSERT INTO `configuration` VALUES(555160, 'Printable Catalog-Categories column', 'PRODUCT_LIST_CATALOG_CATEGORIES', 'show', 'Do you want to display the Categories column?', 899, 12, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, 'tep_cfg_select_option(array(''show'', ''hide''),');
INSERT INTO `configuration` VALUES(555161, 'Printable Catalog-Model column', 'PRODUCT_LIST_CATALOG_MODEL', 'show', 'Do you want to display the Model column?', 899, 13, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, 'tep_cfg_select_option(array(''show'', ''hide''),');
INSERT INTO `configuration` VALUES(555162, 'Printable Catalog-UPC column', 'PRODUCT_LIST_CATALOG_UPC', 'hide', 'Do you want to display the UPC column? (only valid if Members Discount Mod is loaded Default if not present is -hide-)', 899, 14, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, 'tep_cfg_select_option(array(''show'', ''hide''),');
INSERT INTO `configuration` VALUES(555163, 'Printable Catalog-Quantity column', 'PRODUCT_LIST_CATALOG_QUANTITY', 'hide', 'Do you want to display the Quantity column?', 899, 15, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, 'tep_cfg_select_option(array(''show'', ''hide''),');
INSERT INTO `configuration` VALUES(555164, 'Printable Catalog-Weight column', 'PRODUCT_LIST_CATALOG_WEIGHT', 'hide', 'Do you want to display the Weight column?', 899, 16, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, 'tep_cfg_select_option(array(''show'', ''hide''),');
INSERT INTO `configuration` VALUES(555165, 'Printable Catalog-Price column', 'PRODUCT_LIST_CATALOG_PRICE', 'show', 'Do you want to display the Price column?', 899, 17, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, 'tep_cfg_select_option(array(''show'', ''hide''),');
INSERT INTO `configuration` VALUES(555166, 'Printable Catalog-Date column', 'PRODUCT_LIST_CATALOG_DATE', 'hide', 'Do you want to display the Product Date Added column?', 899, 18, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, 'tep_cfg_select_option(array(''show'', ''hide''),');
INSERT INTO `configuration` VALUES(555167, 'Printable Catalog-Show the Date?', 'PRODUCT_LIST_CATALOG_DATE_SHOW', 'hide', 'Do you want to display the Product Date Added (only valid if Display Printable Catalog Date column is set to -show-)', 899, 19, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, 'tep_cfg_select_option(array(''show'', ''hide''),');
INSERT INTO `configuration` VALUES(555168, 'Printable Catalog-Show Currency?', 'PRODUCT_LIST_CATALOG_CURRENCY', 'hide', 'Do you want to display the Currency Pull Down', 899, 20, '2012-09-18 08:30:11', '2012-09-18 08:30:11', NULL, 'tep_cfg_select_option(array(''show'', ''hide''),');
INSERT INTO `configuration` VALUES(555169, 'Enable PayPal Express Checkout', 'MODULE_PAYMENT_PAYPAL_EXPRESS_STATUS', 'True', 'Do you want to accept PayPal Express Checkout payments?', 6, 1, NULL, '2012-09-21 08:47:58', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(555170, 'API Username', 'MODULE_PAYMENT_PAYPAL_EXPRESS_API_USERNAME', 'develo_1307198004_biz_api1.fortytwo-it.com', 'The username to use for the PayPal API service', 6, 0, NULL, '2012-09-21 08:47:58', NULL, NULL);
INSERT INTO `configuration` VALUES(555171, 'API Password', 'MODULE_PAYMENT_PAYPAL_EXPRESS_API_PASSWORD', '1307198015', 'The password to use for the PayPal API service', 6, 0, NULL, '2012-09-21 08:47:58', NULL, NULL);
INSERT INTO `configuration` VALUES(555172, 'API Signature', 'MODULE_PAYMENT_PAYPAL_EXPRESS_API_SIGNATURE', 'AkfoZj.aU9QBdhCJhv-82jVKvu73Aes96QMR5xPDL8cnL.dTrmN9rSuF', 'The signature to use for the PayPal API service', 6, 0, NULL, '2012-09-21 08:47:58', NULL, NULL);
INSERT INTO `configuration` VALUES(555173, 'Transaction Server', 'MODULE_PAYMENT_PAYPAL_EXPRESS_TRANSACTION_SERVER', 'Sandbox', 'Use the live or testing (sandbox) gateway server to process transactions?', 6, 0, NULL, '2012-09-21 08:47:58', NULL, 'tep_cfg_select_option(array(''Live'', ''Sandbox''), ');
INSERT INTO `configuration` VALUES(555174, 'Transaction Method', 'MODULE_PAYMENT_PAYPAL_EXPRESS_TRANSACTION_METHOD', 'Sale', 'The processing method to use for each transaction.', 6, 0, NULL, '2012-09-21 08:47:58', NULL, 'tep_cfg_select_option(array(''Authorization'', ''Sale''), ');
INSERT INTO `configuration` VALUES(555175, 'Payment Zone', 'MODULE_PAYMENT_PAYPAL_EXPRESS_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', 6, 2, NULL, '2012-09-21 08:47:58', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(');
INSERT INTO `configuration` VALUES(555176, 'Sort order of display.', 'MODULE_PAYMENT_PAYPAL_EXPRESS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2012-09-21 08:47:58', NULL, NULL);
INSERT INTO `configuration` VALUES(555177, 'Set Order Status', 'MODULE_PAYMENT_PAYPAL_EXPRESS_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', 6, 0, NULL, '2012-09-21 08:47:58', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses(');
INSERT INTO `configuration` VALUES(555178, 'cURL Program Location', 'MODULE_PAYMENT_PAYPAL_EXPRESS_CURL', '/usr/bin/curl', 'The location to the cURL program application.', 6, 0, NULL, '2012-09-21 08:47:58', NULL, NULL);
INSERT INTO `configuration` VALUES(555179, 'Enable CanadaPost Shipping', 'MODULE_SHIPPING_CANADAPOST_STATUS', 'True', 'Do you want to offer Canada Post shipping?', 6, 0, NULL, '2012-10-03 02:40:50', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `configuration` VALUES(555180, 'Enter CanadaPost Server IP', 'MODULE_SHIPPING_CANADAPOST_SERVERIP', 'sellonline.canadapost.ca', 'ip address of canada post server', 6, 11, NULL, '2012-10-03 02:40:50', NULL, NULL);
INSERT INTO `configuration` VALUES(555181, 'Enter CanadaPost Server Port', 'MODULE_SHIPPING_CANADAPOST_SERVERPOST', '30000', 'service port of canadapast server', 6, 12, NULL, '2012-10-03 02:40:50', NULL, NULL);
INSERT INTO `configuration` VALUES(555182, 'Enter Selected Language(optional)', 'MODULE_SHIPPING_CANADAPOST_LANGUAGE', 'en', 'canada posr support two languages. en: english fr: franch', 6, 13, NULL, '2012-10-03 02:40:50', NULL, NULL);
INSERT INTO `configuration` VALUES(555183, 'Enter Your CanadaPost Customer ID', 'MODULE_SHIPPING_CANADAPOST_CPCID', 'CPC_CHILIHEAT_COM', '(Canada Post Customer ID)Merchant Identification assigned by Canada Post', 6, 14, NULL, '2012-10-03 02:40:50', NULL, NULL);
INSERT INTO `configuration` VALUES(555184, 'Enter Turn Around Time(optional)', 'MODULE_SHIPPING_CANADAPOST_TIME', '24', 'Turn Around Time (hours)', 6, 15, NULL, '2012-10-03 02:40:50', NULL, NULL);
INSERT INTO `configuration` VALUES(555185, 'Handling Fee', 'MODULE_SHIPPING_CANADAPOST_HANDLING', '0', 'Handling fee for this shipping method. Can also be used as an allowance for extra packaging weight etc.', 6, 0, NULL, '2012-10-03 02:40:50', NULL, NULL);
INSERT INTO `configuration` VALUES(555186, 'Tax Class', 'MODULE_SHIPPING_CANADAPOST_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', 6, 0, NULL, '2012-10-03 02:40:50', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(');
INSERT INTO `configuration` VALUES(555187, 'Shipping Zone', 'MODULE_SHIPPING_CANADAPOST_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', 6, 0, NULL, '2012-10-03 02:40:50', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(');
INSERT INTO `configuration` VALUES(555188, 'Sort Order', 'MODULE_SHIPPING_CANADAPOST_SORT_ORDER', '0', 'Sort order of display.', 6, 0, NULL, '2012-10-03 02:40:50', NULL, NULL);
INSERT INTO `configuration` VALUES(555189, 'Display Attributes with Price Total or Additional Charge', 'DISPLAY_ATTRIBUTES_WITH_PRICE', 'true', 'Display the total price of a product plus attribute charge?', 1, 21, '2012-10-03 14:56:13', '2011-04-15 16:11:25', NULL, 'tep_cfg_select_option(array(''true'', ''false''), ');

CREATE TABLE IF NOT EXISTS `configuration_group` (
  `configuration_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `configuration_group_title` varchar(64) NOT NULL DEFAULT '',
  `configuration_group_description` varchar(255) NOT NULL DEFAULT '',
  `sort_order` int(5) DEFAULT NULL,
  `visible` int(1) DEFAULT '1',
  PRIMARY KEY (`configuration_group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=888008 ;

INSERT INTO `configuration_group` VALUES(1, 'My Store', 'General information about my store', 1, 1);
INSERT INTO `configuration_group` VALUES(2, 'Minimum Values', 'The minimum values for functions / data', 2, 1);
INSERT INTO `configuration_group` VALUES(3, 'Maximum Values', 'The maximum values for functions / data', 3, 1);
INSERT INTO `configuration_group` VALUES(4, 'Images', 'Image parameters', 4, 1);
INSERT INTO `configuration_group` VALUES(5, 'Customer Details', 'Customer account configuration', 5, 1);
INSERT INTO `configuration_group` VALUES(6, 'Module Options', 'Hidden from configuration', 6, 0);
INSERT INTO `configuration_group` VALUES(7, 'Shipping/Packaging', 'Shipping options available at my store', 7, 1);
INSERT INTO `configuration_group` VALUES(8, 'Product Listing', 'Product Listing    configuration options', 8, 0);
INSERT INTO `configuration_group` VALUES(9, 'Stock', 'Stock configuration options', 9, 1);
INSERT INTO `configuration_group` VALUES(10, 'Logging', 'Logging configuration options', 10, 1);
INSERT INTO `configuration_group` VALUES(11, 'Cache', 'Caching configuration options', 11, 1);
INSERT INTO `configuration_group` VALUES(12, 'E-Mail Options', 'General setting for E-Mail transport and HTML E-Mails', 12, 1);
INSERT INTO `configuration_group` VALUES(13, 'Download', 'Downloadable products options', 13, 1);
INSERT INTO `configuration_group` VALUES(14, 'GZip Compression', 'GZip compression options', 14, 1);
INSERT INTO `configuration_group` VALUES(15, 'Sessions', 'Session options', 15, 1);
INSERT INTO `configuration_group` VALUES(99, 'Featured Products', 'Configure featured products', 15, 1);
INSERT INTO `configuration_group` VALUES(333, 'Image Magic', 'Configuration options for the OSC Image Magic contribution', 4, 1);
INSERT INTO `configuration_group` VALUES(62, 'Feed Settings', 'Info for data feeds', 62, 1);
INSERT INTO `configuration_group` VALUES(22, 'Points/RewardsV2.00', 'Points/Rewards System Configuration', 22, 1);
INSERT INTO `configuration_group` VALUES(900, 'Affiliate Program', 'Options for the Affiliate Program', 50, 1);
INSERT INTO `configuration_group` VALUES(888001, 'Product Information', 'Product Information page configuration options', 8, 1);
INSERT INTO `configuration_group` VALUES(888004, 'SEO URLs', 'Options for Ultimate SEO URLs by Chemo', 10021, 1);
INSERT INTO `configuration_group` VALUES(16, 'Family Products', 'The options for the Family Products v3.4', 16, 1);
INSERT INTO `configuration_group` VALUES(735, 'Options as Images', 'Configuration for the Options as Images Function', 20, 1);
INSERT INTO `configuration_group` VALUES(12954, 'Wish List Settings', 'Settings for your Wish List', 25, 1);
INSERT INTO `configuration_group` VALUES(10050, 'Links - Infobox Config', 'Links Manager Infobox Configuration', 100, 1);
INSERT INTO `configuration_group` VALUES(888003, 'Links', 'Links Manager configuration options', 99, 0);
INSERT INTO `configuration_group` VALUES(10020, 'Ajax enhanced search', 'Ajax enhanced search configuration', 10020, 1);
INSERT INTO `configuration_group` VALUES(73, 'Terms &amp; Conditions', 'Configuration options for Terms &amp; Conditions.', 72, 1);
INSERT INTO `configuration_group` VALUES(17, 'All Products', 'All Product Options', 17, 1);
INSERT INTO `configuration_group` VALUES(30, 'Current Auctions', 'Current Auction configuration', 30, 1);
INSERT INTO `configuration_group` VALUES(18, 'Links', 'Links Manager configuration options', 99, 1);
INSERT INTO `configuration_group` VALUES(923, 'RSS Infobox', 'Configuration for the RSS Infobox', 22, 1);
INSERT INTO `configuration_group` VALUES(888005, 'Year Make Model', 'Year Make Model Filter Options', 10021, 1);
INSERT INTO `configuration_group` VALUES(6501, 'Recover Cart Sales', 'Recover Cart Sales (RCS) Configuration Values', 15, 1);
INSERT INTO `configuration_group` VALUES(1661, 'Banner Rotator', 'Banner Rotator options', 16, 1);
INSERT INTO `configuration_group` VALUES(888006, 'PHPIDS', 'PHPIDS for osCommerce', 10022, 1);
INSERT INTO `configuration_group` VALUES(888007, 'MultiGeoZone MultiTable Shipping', 'The options which configure the MultiGeoZone MultiTable Shipping Module', 888007, 1);
INSERT INTO `configuration_group` VALUES(899, 'Printable Catalog', 'Options for Printable Catalog', 899, 1);

CREATE TABLE IF NOT EXISTS `counter` (
  `startdate` char(8) DEFAULT NULL,
  `counter` int(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `counter` VALUES('20110817', 42412);

CREATE TABLE IF NOT EXISTS `counter_history` (
  `month` char(8) DEFAULT NULL,
  `counter` int(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `countries` (
  `countries_id` int(11) NOT NULL AUTO_INCREMENT,
  `countries_name` varchar(64) NOT NULL DEFAULT '',
  `countries_iso_code_2` char(2) NOT NULL DEFAULT '',
  `countries_iso_code_3` char(3) NOT NULL DEFAULT '',
  `address_format_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`countries_id`),
  KEY `IDX_COUNTRIES_NAME` (`countries_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=240 ;

INSERT INTO `countries` VALUES(1, 'Afghanistan', 'AF', 'AFG', 1);
INSERT INTO `countries` VALUES(2, 'Albania', 'AL', 'ALB', 1);
INSERT INTO `countries` VALUES(3, 'Algeria', 'DZ', 'DZA', 1);
INSERT INTO `countries` VALUES(4, 'American Samoa', 'AS', 'ASM', 1);
INSERT INTO `countries` VALUES(5, 'Andorra', 'AD', 'AND', 1);
INSERT INTO `countries` VALUES(6, 'Angola', 'AO', 'AGO', 1);
INSERT INTO `countries` VALUES(7, 'Anguilla', 'AI', 'AIA', 1);
INSERT INTO `countries` VALUES(8, 'Antarctica', 'AQ', 'ATA', 1);
INSERT INTO `countries` VALUES(9, 'Antigua and Barbuda', 'AG', 'ATG', 1);
INSERT INTO `countries` VALUES(10, 'Argentina', 'AR', 'ARG', 1);
INSERT INTO `countries` VALUES(11, 'Armenia', 'AM', 'ARM', 1);
INSERT INTO `countries` VALUES(12, 'Aruba', 'AW', 'ABW', 1);
INSERT INTO `countries` VALUES(13, 'Australia', 'AU', 'AUS', 1);
INSERT INTO `countries` VALUES(14, 'Austria', 'AT', 'AUT', 5);
INSERT INTO `countries` VALUES(15, 'Azerbaijan', 'AZ', 'AZE', 1);
INSERT INTO `countries` VALUES(16, 'Bahamas', 'BS', 'BHS', 1);
INSERT INTO `countries` VALUES(17, 'Bahrain', 'BH', 'BHR', 1);
INSERT INTO `countries` VALUES(18, 'Bangladesh', 'BD', 'BGD', 1);
INSERT INTO `countries` VALUES(19, 'Barbados', 'BB', 'BRB', 1);
INSERT INTO `countries` VALUES(20, 'Belarus', 'BY', 'BLR', 1);
INSERT INTO `countries` VALUES(21, 'Belgium', 'BE', 'BEL', 1);
INSERT INTO `countries` VALUES(22, 'Belize', 'BZ', 'BLZ', 1);
INSERT INTO `countries` VALUES(23, 'Benin', 'BJ', 'BEN', 1);
INSERT INTO `countries` VALUES(24, 'Bermuda', 'BM', 'BMU', 1);
INSERT INTO `countries` VALUES(25, 'Bhutan', 'BT', 'BTN', 1);
INSERT INTO `countries` VALUES(26, 'Bolivia', 'BO', 'BOL', 1);
INSERT INTO `countries` VALUES(27, 'Bosnia and Herzegowina', 'BA', 'BIH', 1);
INSERT INTO `countries` VALUES(28, 'Botswana', 'BW', 'BWA', 1);
INSERT INTO `countries` VALUES(29, 'Bouvet Island', 'BV', 'BVT', 1);
INSERT INTO `countries` VALUES(30, 'Brazil', 'BR', 'BRA', 1);
INSERT INTO `countries` VALUES(31, 'British Indian Ocean Territory', 'IO', 'IOT', 1);
INSERT INTO `countries` VALUES(32, 'Brunei Darussalam', 'BN', 'BRN', 1);
INSERT INTO `countries` VALUES(33, 'Bulgaria', 'BG', 'BGR', 1);
INSERT INTO `countries` VALUES(34, 'Burkina Faso', 'BF', 'BFA', 1);
INSERT INTO `countries` VALUES(35, 'Burundi', 'BI', 'BDI', 1);
INSERT INTO `countries` VALUES(36, 'Cambodia', 'KH', 'KHM', 1);
INSERT INTO `countries` VALUES(37, 'Cameroon', 'CM', 'CMR', 1);
INSERT INTO `countries` VALUES(38, 'Canada', 'CA', 'CAN', 1);
INSERT INTO `countries` VALUES(39, 'Cape Verde', 'CV', 'CPV', 1);
INSERT INTO `countries` VALUES(40, 'Cayman Islands', 'KY', 'CYM', 1);
INSERT INTO `countries` VALUES(41, 'Central African Republic', 'CF', 'CAF', 1);
INSERT INTO `countries` VALUES(42, 'Chad', 'TD', 'TCD', 1);
INSERT INTO `countries` VALUES(43, 'Chile', 'CL', 'CHL', 1);
INSERT INTO `countries` VALUES(44, 'China', 'CN', 'CHN', 1);
INSERT INTO `countries` VALUES(45, 'Christmas Island', 'CX', 'CXR', 1);
INSERT INTO `countries` VALUES(46, 'Cocos (Keeling) Islands', 'CC', 'CCK', 1);
INSERT INTO `countries` VALUES(47, 'Colombia', 'CO', 'COL', 1);
INSERT INTO `countries` VALUES(48, 'Comoros', 'KM', 'COM', 1);
INSERT INTO `countries` VALUES(49, 'Congo', 'CG', 'COG', 1);
INSERT INTO `countries` VALUES(50, 'Cook Islands', 'CK', 'COK', 1);
INSERT INTO `countries` VALUES(51, 'Costa Rica', 'CR', 'CRI', 1);
INSERT INTO `countries` VALUES(52, 'Cote D''Ivoire', 'CI', 'CIV', 1);
INSERT INTO `countries` VALUES(53, 'Croatia', 'HR', 'HRV', 1);
INSERT INTO `countries` VALUES(54, 'Cuba', 'CU', 'CUB', 1);
INSERT INTO `countries` VALUES(55, 'Cyprus', 'CY', 'CYP', 1);
INSERT INTO `countries` VALUES(56, 'Czech Republic', 'CZ', 'CZE', 1);
INSERT INTO `countries` VALUES(57, 'Denmark', 'DK', 'DNK', 1);
INSERT INTO `countries` VALUES(58, 'Djibouti', 'DJ', 'DJI', 1);
INSERT INTO `countries` VALUES(59, 'Dominica', 'DM', 'DMA', 1);
INSERT INTO `countries` VALUES(60, 'Dominican Republic', 'DO', 'DOM', 1);
INSERT INTO `countries` VALUES(61, 'East Timor', 'TP', 'TMP', 1);
INSERT INTO `countries` VALUES(62, 'Ecuador', 'EC', 'ECU', 1);
INSERT INTO `countries` VALUES(63, 'Egypt', 'EG', 'EGY', 1);
INSERT INTO `countries` VALUES(64, 'El Salvador', 'SV', 'SLV', 1);
INSERT INTO `countries` VALUES(65, 'Equatorial Guinea', 'GQ', 'GNQ', 1);
INSERT INTO `countries` VALUES(66, 'Eritrea', 'ER', 'ERI', 1);
INSERT INTO `countries` VALUES(67, 'Estonia', 'EE', 'EST', 1);
INSERT INTO `countries` VALUES(68, 'Ethiopia', 'ET', 'ETH', 1);
INSERT INTO `countries` VALUES(69, 'Falkland Islands (Malvinas)', 'FK', 'FLK', 1);
INSERT INTO `countries` VALUES(70, 'Faroe Islands', 'FO', 'FRO', 1);
INSERT INTO `countries` VALUES(71, 'Fiji', 'FJ', 'FJI', 1);
INSERT INTO `countries` VALUES(72, 'Finland', 'FI', 'FIN', 1);
INSERT INTO `countries` VALUES(73, 'France', 'FR', 'FRA', 1);
INSERT INTO `countries` VALUES(74, 'France, Metropolitan', 'FX', 'FXX', 1);
INSERT INTO `countries` VALUES(75, 'French Guiana', 'GF', 'GUF', 1);
INSERT INTO `countries` VALUES(76, 'French Polynesia', 'PF', 'PYF', 1);
INSERT INTO `countries` VALUES(77, 'French Southern Territories', 'TF', 'ATF', 1);
INSERT INTO `countries` VALUES(78, 'Gabon', 'GA', 'GAB', 1);
INSERT INTO `countries` VALUES(79, 'Gambia', 'GM', 'GMB', 1);
INSERT INTO `countries` VALUES(80, 'Georgia', 'GE', 'GEO', 1);
INSERT INTO `countries` VALUES(81, 'Germany', 'DE', 'DEU', 5);
INSERT INTO `countries` VALUES(82, 'Ghana', 'GH', 'GHA', 1);
INSERT INTO `countries` VALUES(83, 'Gibraltar', 'GI', 'GIB', 1);
INSERT INTO `countries` VALUES(84, 'Greece', 'GR', 'GRC', 1);
INSERT INTO `countries` VALUES(85, 'Greenland', 'GL', 'GRL', 1);
INSERT INTO `countries` VALUES(86, 'Grenada', 'GD', 'GRD', 1);
INSERT INTO `countries` VALUES(87, 'Guadeloupe', 'GP', 'GLP', 1);
INSERT INTO `countries` VALUES(88, 'Guam', 'GU', 'GUM', 1);
INSERT INTO `countries` VALUES(89, 'Guatemala', 'GT', 'GTM', 1);
INSERT INTO `countries` VALUES(90, 'Guinea', 'GN', 'GIN', 1);
INSERT INTO `countries` VALUES(91, 'Guinea-bissau', 'GW', 'GNB', 1);
INSERT INTO `countries` VALUES(92, 'Guyana', 'GY', 'GUY', 1);
INSERT INTO `countries` VALUES(93, 'Haiti', 'HT', 'HTI', 1);
INSERT INTO `countries` VALUES(94, 'Heard and Mc Donald Islands', 'HM', 'HMD', 1);
INSERT INTO `countries` VALUES(95, 'Honduras', 'HN', 'HND', 1);
INSERT INTO `countries` VALUES(96, 'Hong Kong', 'HK', 'HKG', 1);
INSERT INTO `countries` VALUES(97, 'Hungary', 'HU', 'HUN', 1);
INSERT INTO `countries` VALUES(98, 'Iceland', 'IS', 'ISL', 1);
INSERT INTO `countries` VALUES(99, 'India', 'IN', 'IND', 1);
INSERT INTO `countries` VALUES(100, 'Indonesia', 'ID', 'IDN', 1);
INSERT INTO `countries` VALUES(101, 'Iran (Islamic Republic of)', 'IR', 'IRN', 1);
INSERT INTO `countries` VALUES(102, 'Iraq', 'IQ', 'IRQ', 1);
INSERT INTO `countries` VALUES(103, 'Ireland', 'IE', 'IRL', 1);
INSERT INTO `countries` VALUES(104, 'Israel', 'IL', 'ISR', 1);
INSERT INTO `countries` VALUES(105, 'Italy', 'IT', 'ITA', 1);
INSERT INTO `countries` VALUES(106, 'Jamaica', 'JM', 'JAM', 1);
INSERT INTO `countries` VALUES(107, 'Japan', 'JP', 'JPN', 1);
INSERT INTO `countries` VALUES(108, 'Jordan', 'JO', 'JOR', 1);
INSERT INTO `countries` VALUES(109, 'Kazakhstan', 'KZ', 'KAZ', 1);
INSERT INTO `countries` VALUES(110, 'Kenya', 'KE', 'KEN', 1);
INSERT INTO `countries` VALUES(111, 'Kiribati', 'KI', 'KIR', 1);
INSERT INTO `countries` VALUES(112, 'Korea, Democratic People''s Republic of', 'KP', 'PRK', 1);
INSERT INTO `countries` VALUES(113, 'Korea, Republic of', 'KR', 'KOR', 1);
INSERT INTO `countries` VALUES(114, 'Kuwait', 'KW', 'KWT', 1);
INSERT INTO `countries` VALUES(115, 'Kyrgyzstan', 'KG', 'KGZ', 1);
INSERT INTO `countries` VALUES(116, 'Lao People''s Democratic Republic', 'LA', 'LAO', 1);
INSERT INTO `countries` VALUES(117, 'Latvia', 'LV', 'LVA', 1);
INSERT INTO `countries` VALUES(118, 'Lebanon', 'LB', 'LBN', 1);
INSERT INTO `countries` VALUES(119, 'Lesotho', 'LS', 'LSO', 1);
INSERT INTO `countries` VALUES(120, 'Liberia', 'LR', 'LBR', 1);
INSERT INTO `countries` VALUES(121, 'Libyan Arab Jamahiriya', 'LY', 'LBY', 1);
INSERT INTO `countries` VALUES(122, 'Liechtenstein', 'LI', 'LIE', 1);
INSERT INTO `countries` VALUES(123, 'Lithuania', 'LT', 'LTU', 1);
INSERT INTO `countries` VALUES(124, 'Luxembourg', 'LU', 'LUX', 1);
INSERT INTO `countries` VALUES(125, 'Macau', 'MO', 'MAC', 1);
INSERT INTO `countries` VALUES(126, 'Macedonia, The Former Yugoslav Republic of', 'MK', 'MKD', 1);
INSERT INTO `countries` VALUES(127, 'Madagascar', 'MG', 'MDG', 1);
INSERT INTO `countries` VALUES(128, 'Malawi', 'MW', 'MWI', 1);
INSERT INTO `countries` VALUES(129, 'Malaysia', 'MY', 'MYS', 1);
INSERT INTO `countries` VALUES(130, 'Maldives', 'MV', 'MDV', 1);
INSERT INTO `countries` VALUES(131, 'Mali', 'ML', 'MLI', 1);
INSERT INTO `countries` VALUES(132, 'Malta', 'MT', 'MLT', 1);
INSERT INTO `countries` VALUES(133, 'Marshall Islands', 'MH', 'MHL', 1);
INSERT INTO `countries` VALUES(134, 'Martinique', 'MQ', 'MTQ', 1);
INSERT INTO `countries` VALUES(135, 'Mauritania', 'MR', 'MRT', 1);
INSERT INTO `countries` VALUES(136, 'Mauritius', 'MU', 'MUS', 1);
INSERT INTO `countries` VALUES(137, 'Mayotte', 'YT', 'MYT', 1);
INSERT INTO `countries` VALUES(138, 'Mexico', 'MX', 'MEX', 1);
INSERT INTO `countries` VALUES(139, 'Micronesia, Federated States of', 'FM', 'FSM', 1);
INSERT INTO `countries` VALUES(140, 'Moldova, Republic of', 'MD', 'MDA', 1);
INSERT INTO `countries` VALUES(141, 'Monaco', 'MC', 'MCO', 1);
INSERT INTO `countries` VALUES(142, 'Mongolia', 'MN', 'MNG', 1);
INSERT INTO `countries` VALUES(143, 'Montserrat', 'MS', 'MSR', 1);
INSERT INTO `countries` VALUES(144, 'Morocco', 'MA', 'MAR', 1);
INSERT INTO `countries` VALUES(145, 'Mozambique', 'MZ', 'MOZ', 1);
INSERT INTO `countries` VALUES(146, 'Myanmar', 'MM', 'MMR', 1);
INSERT INTO `countries` VALUES(147, 'Namibia', 'NA', 'NAM', 1);
INSERT INTO `countries` VALUES(148, 'Nauru', 'NR', 'NRU', 1);
INSERT INTO `countries` VALUES(149, 'Nepal', 'NP', 'NPL', 1);
INSERT INTO `countries` VALUES(150, 'Netherlands', 'NL', 'NLD', 1);
INSERT INTO `countries` VALUES(151, 'Netherlands Antilles', 'AN', 'ANT', 1);
INSERT INTO `countries` VALUES(152, 'New Caledonia', 'NC', 'NCL', 1);
INSERT INTO `countries` VALUES(153, 'New Zealand', 'NZ', 'NZL', 1);
INSERT INTO `countries` VALUES(154, 'Nicaragua', 'NI', 'NIC', 1);
INSERT INTO `countries` VALUES(155, 'Niger', 'NE', 'NER', 1);
INSERT INTO `countries` VALUES(156, 'Nigeria', 'NG', 'NGA', 1);
INSERT INTO `countries` VALUES(157, 'Niue', 'NU', 'NIU', 1);
INSERT INTO `countries` VALUES(158, 'Norfolk Island', 'NF', 'NFK', 1);
INSERT INTO `countries` VALUES(159, 'Northern Mariana Islands', 'MP', 'MNP', 1);
INSERT INTO `countries` VALUES(160, 'Norway', 'NO', 'NOR', 1);
INSERT INTO `countries` VALUES(161, 'Oman', 'OM', 'OMN', 1);
INSERT INTO `countries` VALUES(162, 'Pakistan', 'PK', 'PAK', 1);
INSERT INTO `countries` VALUES(163, 'Palau', 'PW', 'PLW', 1);
INSERT INTO `countries` VALUES(164, 'Panama', 'PA', 'PAN', 1);
INSERT INTO `countries` VALUES(165, 'Papua New Guinea', 'PG', 'PNG', 1);
INSERT INTO `countries` VALUES(166, 'Paraguay', 'PY', 'PRY', 1);
INSERT INTO `countries` VALUES(167, 'Peru', 'PE', 'PER', 1);
INSERT INTO `countries` VALUES(168, 'Philippines', 'PH', 'PHL', 1);
INSERT INTO `countries` VALUES(169, 'Pitcairn', 'PN', 'PCN', 1);
INSERT INTO `countries` VALUES(170, 'Poland', 'PL', 'POL', 1);
INSERT INTO `countries` VALUES(171, 'Portugal', 'PT', 'PRT', 1);
INSERT INTO `countries` VALUES(172, 'Puerto Rico', 'PR', 'PRI', 1);
INSERT INTO `countries` VALUES(173, 'Qatar', 'QA', 'QAT', 1);
INSERT INTO `countries` VALUES(174, 'Reunion', 'RE', 'REU', 1);
INSERT INTO `countries` VALUES(175, 'Romania', 'RO', 'ROM', 1);
INSERT INTO `countries` VALUES(176, 'Russian Federation', 'RU', 'RUS', 1);
INSERT INTO `countries` VALUES(177, 'Rwanda', 'RW', 'RWA', 1);
INSERT INTO `countries` VALUES(178, 'Saint Kitts and Nevis', 'KN', 'KNA', 1);
INSERT INTO `countries` VALUES(179, 'Saint Lucia', 'LC', 'LCA', 1);
INSERT INTO `countries` VALUES(180, 'Saint Vincent and the Grenadines', 'VC', 'VCT', 1);
INSERT INTO `countries` VALUES(181, 'Samoa', 'WS', 'WSM', 1);
INSERT INTO `countries` VALUES(182, 'San Marino', 'SM', 'SMR', 1);
INSERT INTO `countries` VALUES(183, 'Sao Tome and Principe', 'ST', 'STP', 1);
INSERT INTO `countries` VALUES(184, 'Saudi Arabia', 'SA', 'SAU', 1);
INSERT INTO `countries` VALUES(185, 'Senegal', 'SN', 'SEN', 1);
INSERT INTO `countries` VALUES(186, 'Seychelles', 'SC', 'SYC', 1);
INSERT INTO `countries` VALUES(187, 'Sierra Leone', 'SL', 'SLE', 1);
INSERT INTO `countries` VALUES(188, 'Singapore', 'SG', 'SGP', 4);
INSERT INTO `countries` VALUES(189, 'Slovakia (Slovak Republic)', 'SK', 'SVK', 1);
INSERT INTO `countries` VALUES(190, 'Slovenia', 'SI', 'SVN', 1);
INSERT INTO `countries` VALUES(191, 'Solomon Islands', 'SB', 'SLB', 1);
INSERT INTO `countries` VALUES(192, 'Somalia', 'SO', 'SOM', 1);
INSERT INTO `countries` VALUES(193, 'South Africa', 'ZA', 'ZAF', 1);
INSERT INTO `countries` VALUES(194, 'South Georgia and the South Sandwich Islands', 'GS', 'SGS', 1);
INSERT INTO `countries` VALUES(195, 'Spain', 'ES', 'ESP', 3);
INSERT INTO `countries` VALUES(196, 'Sri Lanka', 'LK', 'LKA', 1);
INSERT INTO `countries` VALUES(197, 'St. Helena', 'SH', 'SHN', 1);
INSERT INTO `countries` VALUES(198, 'St. Pierre and Miquelon', 'PM', 'SPM', 1);
INSERT INTO `countries` VALUES(199, 'Sudan', 'SD', 'SDN', 1);
INSERT INTO `countries` VALUES(200, 'Suriname', 'SR', 'SUR', 1);
INSERT INTO `countries` VALUES(201, 'Svalbard and Jan Mayen Islands', 'SJ', 'SJM', 1);
INSERT INTO `countries` VALUES(202, 'Swaziland', 'SZ', 'SWZ', 1);
INSERT INTO `countries` VALUES(203, 'Sweden', 'SE', 'SWE', 1);
INSERT INTO `countries` VALUES(204, 'Switzerland', 'CH', 'CHE', 1);
INSERT INTO `countries` VALUES(205, 'Syrian Arab Republic', 'SY', 'SYR', 1);
INSERT INTO `countries` VALUES(206, 'Taiwan', 'TW', 'TWN', 1);
INSERT INTO `countries` VALUES(207, 'Tajikistan', 'TJ', 'TJK', 1);
INSERT INTO `countries` VALUES(208, 'Tanzania, United Republic of', 'TZ', 'TZA', 1);
INSERT INTO `countries` VALUES(209, 'Thailand', 'TH', 'THA', 1);
INSERT INTO `countries` VALUES(210, 'Togo', 'TG', 'TGO', 1);
INSERT INTO `countries` VALUES(211, 'Tokelau', 'TK', 'TKL', 1);
INSERT INTO `countries` VALUES(212, 'Tonga', 'TO', 'TON', 1);
INSERT INTO `countries` VALUES(213, 'Trinidad and Tobago', 'TT', 'TTO', 1);
INSERT INTO `countries` VALUES(214, 'Tunisia', 'TN', 'TUN', 1);
INSERT INTO `countries` VALUES(215, 'Turkey', 'TR', 'TUR', 1);
INSERT INTO `countries` VALUES(216, 'Turkmenistan', 'TM', 'TKM', 1);
INSERT INTO `countries` VALUES(217, 'Turks and Caicos Islands', 'TC', 'TCA', 1);
INSERT INTO `countries` VALUES(218, 'Tuvalu', 'TV', 'TUV', 1);
INSERT INTO `countries` VALUES(219, 'Uganda', 'UG', 'UGA', 1);
INSERT INTO `countries` VALUES(220, 'Ukraine', 'UA', 'UKR', 1);
INSERT INTO `countries` VALUES(221, 'United Arab Emirates', 'AE', 'ARE', 1);
INSERT INTO `countries` VALUES(222, 'United Kingdom', 'GB', 'GBR', 1);
INSERT INTO `countries` VALUES(223, 'United States', 'US', 'USA', 2);
INSERT INTO `countries` VALUES(224, 'United States Minor Outlying Islands', 'UM', 'UMI', 1);
INSERT INTO `countries` VALUES(225, 'Uruguay', 'UY', 'URY', 1);
INSERT INTO `countries` VALUES(226, 'Uzbekistan', 'UZ', 'UZB', 1);
INSERT INTO `countries` VALUES(227, 'Vanuatu', 'VU', 'VUT', 1);
INSERT INTO `countries` VALUES(228, 'Vatican City State (Holy See)', 'VA', 'VAT', 1);
INSERT INTO `countries` VALUES(229, 'Venezuela', 'VE', 'VEN', 1);
INSERT INTO `countries` VALUES(230, 'Viet Nam', 'VN', 'VNM', 1);
INSERT INTO `countries` VALUES(231, 'Virgin Islands (British)', 'VG', 'VGB', 1);
INSERT INTO `countries` VALUES(232, 'Virgin Islands (U.S.)', 'VI', 'VIR', 1);
INSERT INTO `countries` VALUES(233, 'Wallis and Futuna Islands', 'WF', 'WLF', 1);
INSERT INTO `countries` VALUES(234, 'Western Sahara', 'EH', 'ESH', 1);
INSERT INTO `countries` VALUES(235, 'Yemen', 'YE', 'YEM', 1);
INSERT INTO `countries` VALUES(236, 'Yugoslavia', 'YU', 'YUG', 1);
INSERT INTO `countries` VALUES(237, 'Zaire', 'ZR', 'ZAR', 1);
INSERT INTO `countries` VALUES(238, 'Zambia', 'ZM', 'ZMB', 1);
INSERT INTO `countries` VALUES(239, 'Zimbabwe', 'ZW', 'ZWE', 1);

CREATE TABLE IF NOT EXISTS `coupons` (
  `coupon_id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_type` char(1) NOT NULL DEFAULT 'F',
  `coupon_code` varchar(32) NOT NULL DEFAULT '',
  `coupon_amount` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `coupon_minimum_order` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `coupon_start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `coupon_expire_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uses_per_coupon` int(5) NOT NULL DEFAULT '1',
  `uses_per_user` int(5) NOT NULL DEFAULT '1',
  `restrict_to_products` varchar(255) DEFAULT NULL,
  `restrict_to_categories` varchar(255) DEFAULT NULL,
  `restrict_to_customers` text,
  `coupon_active` char(1) NOT NULL DEFAULT 'Y',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`coupon_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

INSERT INTO `coupons` VALUES(1, 'G', '980ce4', 25.7500, 0.0000, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0, NULL, NULL, NULL, 'N', '2012-07-03 11:33:40', '0000-00-00 00:00:00');
INSERT INTO `coupons` VALUES(2, 'G', 'cb3fc8', 75.0000, 0.0000, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0, NULL, NULL, NULL, 'N', '2012-07-03 13:18:27', '0000-00-00 00:00:00');
INSERT INTO `coupons` VALUES(3, 'F', 'ea8e4a', 25.0000, 0.0000, '2012-07-06 00:00:00', '2013-07-06 00:00:00', 0, 0, '', '', NULL, 'Y', '1970-01-01 00:00:00', '2012-07-20 10:25:57');
INSERT INTO `coupons` VALUES(5, 'G', '93ea5d', 75.0000, 0.0000, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 1, NULL, NULL, NULL, 'N', '2012-07-20 10:37:01', '0000-00-00 00:00:00');
INSERT INTO `coupons` VALUES(4, 'G', 'be4638', 12.0000, 0.0000, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 1, NULL, NULL, NULL, 'N', '2012-07-10 22:17:14', '0000-00-00 00:00:00');
INSERT INTO `coupons` VALUES(6, 'G', '40c987', 100.0000, 0.0000, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 1, NULL, NULL, NULL, 'N', '2012-07-20 18:25:49', '0000-00-00 00:00:00');
INSERT INTO `coupons` VALUES(7, 'F', '21177d', 10.0000, 0.0000, '2012-09-13 00:00:00', '2013-09-13 00:00:00', 0, 1, '', '', NULL, 'Y', '1970-01-01 00:00:00', '2012-09-12 23:43:11');
INSERT INTO `coupons` VALUES(8, 'P', '732fda', 10.0000, 0.0000, '2012-09-20 00:00:00', '2013-09-21 00:00:00', 0, 0, '', '', NULL, 'Y', '1970-01-01 00:00:00', '2012-10-06 12:14:18');
INSERT INTO `coupons` VALUES(9, 'P', 'c43e80', 10.0000, 0.0000, '2012-10-04 00:00:00', '2013-10-04 00:00:00', 0, 1, '', '', NULL, 'Y', '1970-01-01 00:00:00', '2012-10-03 23:43:41');
INSERT INTO `coupons` VALUES(10, 'P', 'test1111', 10.0000, 0.0000, '2012-10-15 00:00:00', '2013-10-15 00:00:00', 0, 1, '', '', NULL, 'Y', '1970-01-01 00:00:00', '2012-10-15 16:23:54');

CREATE TABLE IF NOT EXISTS `coupons_description` (
  `coupon_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '0',
  `coupon_name` varchar(32) NOT NULL DEFAULT '',
  `coupon_description` text,
  KEY `coupon_id` (`coupon_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `coupons_description` VALUES(3, 1, 'TEST01', 'testing....');
INSERT INTO `coupons_description` VALUES(7, 1, 'TESTER', '');
INSERT INTO `coupons_description` VALUES(8, 1, 'NEWTEST', 'testing 10');
INSERT INTO `coupons_description` VALUES(9, 1, 'wert', '');
INSERT INTO `coupons_description` VALUES(10, 1, 'test1111', '');

CREATE TABLE IF NOT EXISTS `coupon_email_track` (
  `unique_id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_id` int(11) NOT NULL DEFAULT '0',
  `customer_id_sent` int(11) NOT NULL DEFAULT '0',
  `sent_firstname` varchar(32) DEFAULT NULL,
  `sent_lastname` varchar(32) DEFAULT NULL,
  `emailed_to` varchar(32) DEFAULT NULL,
  `date_sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`unique_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

INSERT INTO `coupon_email_track` VALUES(1, 1, 0, 'Admin', NULL, 'jef@fortytwo-it.com', '2012-07-03 11:33:40');
INSERT INTO `coupon_email_track` VALUES(2, 2, 0, 'Admin', NULL, 'jef@fortytwo-it.com', '2012-07-03 13:18:27');
INSERT INTO `coupon_email_track` VALUES(3, 4, 0, 'Admin', NULL, 'adoovo@gmail.com', '2012-07-10 22:17:14');
INSERT INTO `coupon_email_track` VALUES(4, 5, 0, 'Admin', NULL, 'jef@fortytwo-it.com', '2012-07-20 10:37:01');
INSERT INTO `coupon_email_track` VALUES(5, 6, 0, 'Admin', NULL, 'adoovo@gmail.com', '2012-07-20 18:25:49');

CREATE TABLE IF NOT EXISTS `coupon_gv_customer` (
  `customer_id` int(5) NOT NULL DEFAULT '0',
  `amount` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `amount_redeemed` decimal(8,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`customer_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `coupon_gv_customer` VALUES(3, 252.9900, 166.7500);
INSERT INTO `coupon_gv_customer` VALUES(0, 112.0000, 0.0000);

CREATE TABLE IF NOT EXISTS `coupon_gv_queue` (
  `unique_id` int(5) NOT NULL AUTO_INCREMENT,
  `customer_id` int(5) NOT NULL DEFAULT '0',
  `order_id` int(5) NOT NULL DEFAULT '0',
  `amount` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_released` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ipaddr` varchar(32) NOT NULL DEFAULT '',
  `release_flag` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`unique_id`),
  KEY `uid` (`unique_id`,`customer_id`,`order_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

INSERT INTO `coupon_gv_queue` VALUES(1, 0, 8, 100.0000, '2012-07-10 22:13:29', '0000-00-00 00:00:00', '', 'N');
INSERT INTO `coupon_gv_queue` VALUES(2, 3, 9, 25.0000, '2012-07-20 10:27:30', '0000-00-00 00:00:00', '', 'Y');
INSERT INTO `coupon_gv_queue` VALUES(3, 0, 11, 100.0000, '2012-07-20 18:21:00', '0000-00-00 00:00:00', '', 'N');
INSERT INTO `coupon_gv_queue` VALUES(4, 3, 12, 119.9900, '2012-07-20 22:14:59', '0000-00-00 00:00:00', '', 'Y');
INSERT INTO `coupon_gv_queue` VALUES(5, 3, 13, 99.0000, '2012-07-20 22:31:21', '0000-00-00 00:00:00', '', 'Y');
INSERT INTO `coupon_gv_queue` VALUES(6, 0, 14, 102.0000, '2012-07-20 23:20:12', '0000-00-00 00:00:00', '', 'N');
INSERT INTO `coupon_gv_queue` VALUES(7, 0, 26, 25.0000, '2012-10-03 21:21:25', '0000-00-00 00:00:00', '', 'N');

CREATE TABLE IF NOT EXISTS `coupon_redeem_track` (
  `unique_id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_id` int(11) NOT NULL DEFAULT '0',
  `customer_id` int(11) NOT NULL DEFAULT '0',
  `redeem_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `redeem_ip` varchar(32) NOT NULL DEFAULT '',
  `order_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`unique_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

INSERT INTO `coupon_redeem_track` VALUES(1, 1, 3, '2012-07-03 11:35:59', '', 0);
INSERT INTO `coupon_redeem_track` VALUES(2, 2, 3, '2012-07-03 13:18:37', '', 0);
INSERT INTO `coupon_redeem_track` VALUES(3, 3, 3, '2012-07-06 07:15:46', '', 7);
INSERT INTO `coupon_redeem_track` VALUES(4, 4, 0, '2012-07-10 22:18:20', '', 0);
INSERT INTO `coupon_redeem_track` VALUES(5, 3, 3, '2012-07-20 10:27:30', '', 9);
INSERT INTO `coupon_redeem_track` VALUES(6, 5, 3, '2012-07-20 10:37:24', '', 0);
INSERT INTO `coupon_redeem_track` VALUES(7, 6, 0, '2012-07-20 18:26:51', '', 0);

CREATE TABLE IF NOT EXISTS `currencies` (
  `currencies_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '',
  `code` char(3) NOT NULL DEFAULT '',
  `symbol_left` varchar(12) DEFAULT NULL,
  `symbol_right` varchar(12) DEFAULT NULL,
  `decimal_point` char(1) DEFAULT NULL,
  `thousands_point` char(1) DEFAULT NULL,
  `decimal_places` char(1) DEFAULT NULL,
  `value` float(13,8) DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`currencies_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

INSERT INTO `currencies` VALUES(1, 'USD - US Dollar', 'USD', '$', '', '.', ',', '2', 1.00000000, '2011-09-10 00:03:18');
INSERT INTO `currencies` VALUES(2, 'EUR - Euero', 'EUR', 'ÃƒÂ¢Ã¢â‚¬Å¡Ã', '', '.', ',', '2', 0.72380000, '2011-09-10 00:03:18');
INSERT INTO `currencies` VALUES(3, 'GBP - British Pound', 'GBP', '&pound;', '', '.', ',', '2', 0.62739998, '2011-09-10 00:08:41');
INSERT INTO `currencies` VALUES(4, 'CAD - Canadian Dollar', 'CAD', '$', '', '.', ',', '2', 0.99169999, '2011-09-10 00:08:41');
INSERT INTO `currencies` VALUES(5, 'AUD - Australian Dollar', 'AUD', '$', '', '.', ',', '2', 0.94819999, '2011-09-10 00:05:55');
INSERT INTO `currencies` VALUES(6, 'INR - Indian Rupee', 'INR', 'Rs', '', '.', ',', '2', 46.68640137, '2011-09-10 00:08:41');
INSERT INTO `currencies` VALUES(7, 'CNY - China Yuan', 'CNY', 'Ãƒâ€šÃ‚Â¥', '', '.', ',', '2', 6.39410019, '2011-09-10 00:08:41');

CREATE TABLE IF NOT EXISTS `customers` (
  `customers_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_gender` char(1) NOT NULL DEFAULT '',
  `customers_firstname` varchar(32) NOT NULL DEFAULT '',
  `customers_lastname` varchar(32) NOT NULL DEFAULT '',
  `customers_dob` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `customers_email_address` varchar(96) NOT NULL DEFAULT '',
  `customers_default_address_id` int(11) NOT NULL DEFAULT '1',
  `customers_telephone` varchar(32) NOT NULL DEFAULT '',
  `customers_fax` varchar(32) DEFAULT NULL,
  `customers_password` varchar(40) NOT NULL DEFAULT '',
  `customers_newsletter` char(1) DEFAULT NULL,
  `customers_advertiser` varchar(30) DEFAULT NULL,
  `customers_referer_url` varchar(255) DEFAULT NULL,
  `customers_paypal_payerid` varchar(20) DEFAULT NULL,
  `customers_paypal_ec` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `customers_shopping_points` decimal(15,2) NOT NULL DEFAULT '0.00',
  `customers_points_expires` date DEFAULT NULL,
  `customers_group_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `customers_group_ra` enum('0','1') NOT NULL DEFAULT '0',
  `customers_payment_allowed` varchar(255) NOT NULL DEFAULT '',
  `customers_shipment_allowed` varchar(255) NOT NULL DEFAULT '',
  `fb_user_id` varchar(32) DEFAULT NULL,
  `customers_dba` varchar(150) NOT NULL,
  PRIMARY KEY (`customers_id`),
  KEY `customers_email_address` (`customers_email_address`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

INSERT INTO `customers` VALUES(5, '', 'test', 'test', '0000-00-00 00:00:00', 'adoovo22@gmail.com', 5, '12345678', 'bugs@cartstore.com', '444fdf917c4d2fea1d52c3371d69809a:3c', '1', NULL, NULL, NULL, 0, 10.00, '2013-09-27', 0, '0', '', '', '', '');
INSERT INTO `customers` VALUES(4, '', 'Jef', 'Shilt', '0000-00-00 00:00:00', 'jef@fortytwo-it.com', 4, '123.456.7890', '', '1f8ced6662c587791da4a4f987fff78f:63', '', NULL, NULL, NULL, 0, 10.00, '2013-09-20', 0, '0', '', '', '', '');

CREATE TABLE IF NOT EXISTS `customers_basket` (
  `customers_basket_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_id` int(11) NOT NULL DEFAULT '0',
  `products_id` tinytext NOT NULL,
  `customers_basket_quantity` int(2) NOT NULL DEFAULT '0',
  `final_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `customers_basket_date_added` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`customers_basket_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

INSERT INTO `customers_basket` VALUES(34, 4, '508', 1, 0.0000, '20121006');
INSERT INTO `customers_basket` VALUES(33, 4, '520{2}3{1}4', 5, 0.0000, '20121006');

CREATE TABLE IF NOT EXISTS `customers_basket_attributes` (
  `customers_basket_attributes_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_id` int(11) NOT NULL DEFAULT '0',
  `products_id` tinytext NOT NULL,
  `products_options_id` int(11) NOT NULL DEFAULT '0',
  `products_options_value_id` int(11) NOT NULL DEFAULT '0',
  `products_options_value_text` text,
  PRIMARY KEY (`customers_basket_attributes_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `customers_basket_attributes` VALUES(1, 4, '520{2}3{1}4', 2, 3, '');
INSERT INTO `customers_basket_attributes` VALUES(2, 4, '520{2}3{1}4', 1, 4, '');

CREATE TABLE IF NOT EXISTS `customers_groups` (
  `customers_group_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `customers_group_name` varchar(32) NOT NULL DEFAULT '',
  `customers_group_show_tax` enum('1','0') NOT NULL DEFAULT '1',
  `customers_group_tax_exempt` enum('0','1') NOT NULL DEFAULT '0',
  `group_payment_allowed` varchar(255) NOT NULL DEFAULT '',
  `group_shipment_allowed` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`customers_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `customers_groups` VALUES(0, 'Retail (Required, Do Not Delete)', '1', '0', '', '');
INSERT INTO `customers_groups` VALUES(1, 'Wholesale', '1', '0', '', '');

CREATE TABLE IF NOT EXISTS `customers_info` (
  `customers_info_id` int(11) NOT NULL DEFAULT '0',
  `customers_info_date_of_last_logon` datetime DEFAULT NULL,
  `customers_info_number_of_logons` int(5) DEFAULT NULL,
  `customers_info_date_account_created` datetime DEFAULT NULL,
  `customers_info_date_account_last_modified` datetime DEFAULT NULL,
  `global_product_notifications` int(1) DEFAULT '0',
  PRIMARY KEY (`customers_info_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `customers_info` VALUES(4, '2012-10-07 12:03:21', 11, '2012-09-20 22:56:48', NULL, 0);
INSERT INTO `customers_info` VALUES(5, NULL, 0, '2012-09-27 00:41:56', NULL, 0);

CREATE TABLE IF NOT EXISTS `customers_points_pending` (
  `unique_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL DEFAULT '0',
  `orders_id` int(11) NOT NULL DEFAULT '0',
  `points_pending` decimal(15,2) NOT NULL DEFAULT '0.00',
  `points_comment` varchar(200) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `points_status` int(1) NOT NULL DEFAULT '1',
  `points_type` char(2) NOT NULL DEFAULT 'SP',
  PRIMARY KEY (`unique_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

INSERT INTO `customers_points_pending` VALUES(4, 1, 338, 50.00, 'TEXT_DEFAULT_REVIEWS', '2011-10-09 23:53:55', 1, 'RV');
INSERT INTO `customers_points_pending` VALUES(5, 1, 338, 50.00, 'TEXT_DEFAULT_REVIEWS', '2011-10-09 23:54:20', 1, 'RV');
INSERT INTO `customers_points_pending` VALUES(6, 1, 320, 50.00, 'TEXT_DEFAULT_REVIEWS', '2011-10-09 23:55:46', 1, 'RV');
INSERT INTO `customers_points_pending` VALUES(14, 4, 19, 2.40, 'TEXT_DEFAULT_COMMENT', '2012-10-01 16:09:53', 1, 'SP');
INSERT INTO `customers_points_pending` VALUES(15, 0, 23, 11.00, 'TEXT_DEFAULT_COMMENT', '2012-10-03 20:39:13', 1, 'SP');
INSERT INTO `customers_points_pending` VALUES(16, 0, 26, 175.00, 'TEXT_DEFAULT_COMMENT', '2012-10-03 21:21:25', 1, 'SP');
INSERT INTO `customers_points_pending` VALUES(17, 0, 27, 99.00, 'TEXT_DEFAULT_COMMENT', '2012-10-03 21:31:55', 1, 'SP');
INSERT INTO `customers_points_pending` VALUES(18, 0, 2000, 11.00, 'TEXT_DEFAULT_COMMENT', '2012-10-03 21:53:10', 1, 'SP');

CREATE TABLE IF NOT EXISTS `customers_wishlist` (
  `products_id` tinytext NOT NULL,
  `customers_id` int(13) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `customers_wishlist_attributes` (
  `customers_wishlist_attributes_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_id` int(11) NOT NULL DEFAULT '0',
  `products_id` tinytext NOT NULL,
  `products_options_id` int(11) NOT NULL DEFAULT '0',
  `products_options_value_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`customers_wishlist_attributes_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `data` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `year` int(4) NOT NULL DEFAULT '0',
  `mon` int(2) NOT NULL DEFAULT '0',
  `date` int(2) NOT NULL DEFAULT '0',
  `datetime` varchar(8) NOT NULL DEFAULT '',
  `title` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `rank` tinyint(2) NOT NULL DEFAULT '0',
  `sender` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`datetime`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `dealers` (
  `dealer_id` int(11) NOT NULL AUTO_INCREMENT,
  `dealer_name` varchar(75) NOT NULL DEFAULT '',
  `dealer_address1` varchar(150) NOT NULL DEFAULT '',
  `dealer_address2` varchar(150) NOT NULL DEFAULT '',
  `dealer_city` varchar(50) NOT NULL DEFAULT '',
  `dealer_state` varchar(5) NOT NULL DEFAULT '',
  `dealer_zip` int(6) NOT NULL DEFAULT '0',
  `dealer_country` varchar(50) NOT NULL DEFAULT '',
  `dealer_phone` varchar(20) NOT NULL DEFAULT '',
  `dealer_fax` varchar(20) NOT NULL DEFAULT '',
  `dealer_email` varchar(100) NOT NULL DEFAULT '',
  `dealer_url` varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY `dealer_id` (`dealer_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=115 ;

INSERT INTO `dealers` VALUES(114, 'CartStore.com', '123 Sample Way', '', 'Orlando', 'FL', 32839, '', '800-768-7851', '', 'noreply@cartstore.com', 'cartstore.com');

CREATE TABLE IF NOT EXISTS `events_calendar` (
  `event_id` int(3) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL DEFAULT '1',
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `end_date` varchar(20) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `event_image` varchar(64) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `OSC_link` varchar(255) DEFAULT NULL,
  `description` text,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`event_id`,`language_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `families` (
  `family_id` smallint(3) NOT NULL AUTO_INCREMENT,
  `family_name` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`family_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `faqdesk` (
  `faqdesk_id` int(11) NOT NULL AUTO_INCREMENT,
  `faqdesk_image` varchar(64) DEFAULT NULL,
  `faqdesk_image_two` varchar(64) DEFAULT NULL,
  `faqdesk_image_three` varchar(64) DEFAULT NULL,
  `faqdesk_date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `faqdesk_last_modified` datetime DEFAULT NULL,
  `faqdesk_date_available` datetime DEFAULT NULL,
  `faqdesk_status` tinyint(1) NOT NULL DEFAULT '0',
  `faqdesk_sticky` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`faqdesk_id`),
  KEY `idx_faqdesk_date_added` (`faqdesk_date_added`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

CREATE TABLE IF NOT EXISTS `faqdesk_categories` (
  `categories_id` int(11) NOT NULL AUTO_INCREMENT,
  `categories_image` varchar(64) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(3) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `catagory_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`categories_id`),
  KEY `idx_categories_parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `faqdesk_categories_description` (
  `categories_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `categories_name` varchar(32) NOT NULL DEFAULT '',
  `categories_heading_title` varchar(64) DEFAULT NULL,
  `categories_description` text,
  PRIMARY KEY (`categories_id`,`language_id`),
  KEY `idx_categories_name` (`categories_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `faqdesk_configuration` (
  `configuration_id` int(11) NOT NULL AUTO_INCREMENT,
  `configuration_title` varchar(64) NOT NULL DEFAULT '',
  `configuration_key` varchar(64) NOT NULL DEFAULT '',
  `configuration_value` varchar(255) NOT NULL DEFAULT '',
  `configuration_description` varchar(255) NOT NULL DEFAULT '',
  `configuration_group_id` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(5) DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `use_function` varchar(255) DEFAULT NULL,
  `set_function` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`configuration_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

INSERT INTO `faqdesk_configuration` VALUES(1, 'Search Results', 'MAX_DISPLAY_FAQDESK_SEARCH_RESULTS', '3', 'How many FAQS do you want to list?', 1, 1, '2004-05-19 18:11:18', '2003-02-16 02:08:36', '', '');
INSERT INTO `faqdesk_configuration` VALUES(2, 'Page Links', 'MAX_DISPLAY_FAQDESK_PAGE_LINKS', '1', 'Number of links to use for page-sets', 1, 2, '2004-05-08 21:06:19', '2003-02-16 02:08:36', '', '');
INSERT INTO `faqdesk_configuration` VALUES(3, 'Display Question', 'FAQDESK_QUESTION', '2', 'Do you want to display the question? (0=disable; 1-4 sort order)', 1, 3, '2004-05-08 21:04:50', '2003-02-16 02:08:36', '', '');
INSERT INTO `faqdesk_configuration` VALUES(4, 'Display Short Answer', 'FAQDESK_ANSWER_SHORT', '0', 'Do you want to display the short answer? (0=disable; 1-4 sort order)', 1, 4, '2004-05-08 21:04:24', '2003-02-16 02:08:36', '', '');
INSERT INTO `faqdesk_configuration` VALUES(5, 'Display Long Answer', 'FAQDESK_ANSWER_LONG', '0', 'Do you want to display the long Answer? (0=disable; 1-4 sort order)', 1, 5, '2004-05-08 21:04:30', '2003-02-16 02:08:36', '', '');
INSERT INTO `faqdesk_configuration` VALUES(6, 'Display Date', 'FAQDESK_DATE_AVAILABLE', '0', 'Do you want to display the date? (0=disable; 1-4 sort order)', 1, 6, '2007-03-17 05:41:42', '2003-02-16 02:08:36', '', '');
INSERT INTO `faqdesk_configuration` VALUES(7, 'Location of Prev/Next Navigation Bar', 'FAQDESK_PREV_NEXT_BAR_LOCATION', '3', 'Sets the location of the Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', 1, 12, '0000-00-00 00:00:00', '2003-02-16 02:08:36', '', '');
INSERT INTO `faqdesk_configuration` VALUES(8, 'Display Main FAQS Items', 'MAX_DISPLAY_FAQDESK_FAQS', '3', 'How many FAQS do you want to display on the top page?', 2, 1, '0000-00-00 00:00:00', '2003-02-16 02:08:36', '', '');
INSERT INTO `faqdesk_configuration` VALUES(9, 'Latest FAQS Box Counts', 'LATEST_DISPLAY_FAQDESK_FAQS', '3', 'How many FAQS do you want to display in the Latest News Box?', 2, 2, '2004-05-08 21:06:52', '2003-02-16 02:08:36', '', '');
INSERT INTO `faqdesk_configuration` VALUES(10, 'Display Latest FAQS Box', 'DISPLAY_LATEST_FAQS_BOX', '1', 'Do you want to display the Latest FAQS Box? (0=disable; 1=enable)', 2, 3, '0000-00-00 00:00:00', '2003-02-16 02:08:36', '', '');
INSERT INTO `faqdesk_configuration` VALUES(11, 'Display FAQS Catagory Box', 'DISPLAY_FAQS_CATAGORY_BOX', '1', 'Do you want to display the FAQS Catagory Box? (0=disable; 1=enable)', 2, 4, '0000-00-00 00:00:00', '2003-02-16 02:08:36', '', '');
INSERT INTO `faqdesk_configuration` VALUES(12, 'Display View Counts', 'DISPLAY_FAQDESK_VIEWCOUNT', '1', 'Do you want to display View Counts? (0=disable; 1=enable)', 2, 5, '0000-00-00 00:00:00', '2003-02-16 02:08:36', '', '');
INSERT INTO `faqdesk_configuration` VALUES(13, 'Display Read More', 'DISPLAY_FAQDESK_READMORE', '1', 'Do you want to display Read More? (0=disable; 1=enable)', 2, 6, '0000-00-00 00:00:00', '2003-02-16 02:08:36', '', '');
INSERT INTO `faqdesk_configuration` VALUES(14, 'Display Short Answwer', 'DISPLAY_FAQDESK_SHORT_ANSWER', '1', 'Do you want to display the Short Answer? (0=disable; 1=enable)', 2, 7, '2004-03-14 18:55:24', '2003-02-16 02:08:36', '', '');
INSERT INTO `faqdesk_configuration` VALUES(15, 'Display Question', 'DISPLAY_FAQDESK_QUESTION', '1', 'Do you want to display the Question? (0=disable; 1=enable)', 2, 8, '0000-00-00 00:00:00', '2003-02-16 02:08:36', '', '');
INSERT INTO `faqdesk_configuration` VALUES(16, 'Display Date', 'DISPLAY_FAQDESK_DATE', '1', 'Do you want to display the FAQS Date? (0=disable; 1=enable)', 2, 9, '0000-00-00 00:00:00', '2003-02-16 02:08:36', '', '');
INSERT INTO `faqdesk_configuration` VALUES(17, 'Display Image 1', 'DISPLAY_FAQDESK_IMAGE', '1', 'Do you want to display image "1" for the FAQ? (0=disable; 1=enable)', 2, 10, '0000-00-00 00:00:00', '2003-03-03 11:59:47', '', '');
INSERT INTO `faqdesk_configuration` VALUES(18, 'Display Image 2', 'DISPLAY_FAQDESK_IMAGE_TWO', '1', 'Do you want to display image "2" for the FAQ? (0=disable; 1=enable)', 2, 11, '2003-03-03 12:08:55', '2003-03-03 11:59:47', '', '');
INSERT INTO `faqdesk_configuration` VALUES(19, 'Display Image 3', 'DISPLAY_FAQDESK_IMAGE_THREE', '1', 'Do you want to display image "3" for the FAQ? (0=disable; 1=enable)', 2, 12, '2003-03-03 12:09:16', '2003-03-03 11:59:47', '', '');
INSERT INTO `faqdesk_configuration` VALUES(20, 'Display Reviews', 'DISPLAY_FAQDESK_REVIEWS', '1', 'Do you want to display FAQS Reviews? (0=disable; 1=enable)', 3, 1, '0000-00-00 00:00:00', '2003-02-16 02:08:36', '', '');
INSERT INTO `faqdesk_configuration` VALUES(21, 'New Reviews', 'MAX_DISPLAY_NEW_REVIEWS', '10', 'Maximum number of new reviews to display', 3, 2, '0000-00-00 00:00:00', '2003-02-16 02:08:36', '', '');
INSERT INTO `faqdesk_configuration` VALUES(22, 'Display Question', 'STICKY_QUESTION', '1', 'Do you want to display the question? (0=disable; 1=enable)', 4, 1, '0000-00-00 00:00:00', '2003-03-02 00:47:21', '', '');
INSERT INTO `faqdesk_configuration` VALUES(23, 'Display Short Answer', 'STICKY_SHORT_ANSWER', '1', 'Do you want to display the short answer? (0=disable; 1=enable)', 4, 2, '0000-00-00 00:00:00', '2003-03-02 00:47:21', '', '');
INSERT INTO `faqdesk_configuration` VALUES(24, 'Display Long Answer', 'STICKY_LONG_ANSWER', '1', 'Do you want to display the long answer? (0=disable; 1=enable)', 4, 3, '2004-03-14 19:06:45', '2003-03-02 00:47:21', '', '');
INSERT INTO `faqdesk_configuration` VALUES(25, 'Display View Counts', 'STICKY_FAQDESK_VIEWCOUNT', '0', 'Do you want to display View Counts? (0=disable; 1=enable)', 4, 4, '2007-03-17 05:46:26', '2003-03-02 00:47:21', '', '');
INSERT INTO `faqdesk_configuration` VALUES(26, 'Display Read More', 'STICKY_FAQDESK_READMORE', '1', 'Do you want to display Read More? (0=disable; 1=enable)', 4, 5, '0000-00-00 00:00:00', '2003-03-02 00:47:21', '', '');
INSERT INTO `faqdesk_configuration` VALUES(27, 'Display Date', 'STICKY_DATE_ADDED', '0', 'Do you want to display the date? (0=disable; 1=enable)', 4, 6, '2007-03-17 05:46:12', '2003-03-02 00:47:21', '', '');
INSERT INTO `faqdesk_configuration` VALUES(28, 'Display URL', 'STICKY_EXTRA_URL', '1', 'Do you want to display the extra URL? (0=disable; 1=enable)', 4, 7, '2003-03-02 00:50:28', '2003-03-02 00:47:21', '', '');
INSERT INTO `faqdesk_configuration` VALUES(29, 'Display Image', 'STICKY_IMAGE', '1', 'Do you want to display image "1" for the FAQ? (0=disable; 1=enable)', 4, 8, '2003-03-02 00:50:14', '2003-03-02 00:47:21', '', '');
INSERT INTO `faqdesk_configuration` VALUES(30, 'Display Image 2', 'STICKY_IMAGE_TWO', '1', 'Do you want to display image "2"for the FAQ? (0=disable; 1=enable)', 4, 9, '0000-00-00 00:00:00', '2003-03-03 23:10:34', '', '');
INSERT INTO `faqdesk_configuration` VALUES(31, 'Display Image 3', 'STICKY_IMAGE_THREE', '1', 'Do you want to display image "3" for the FAQ? (0=disable; 1=enable)', 4, 10, '0000-00-00 00:00:00', '2003-03-03 23:10:34', '', '');

CREATE TABLE IF NOT EXISTS `faqdesk_configuration_group` (
  `configuration_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `configuration_group_title` varchar(64) NOT NULL DEFAULT '',
  `configuration_group_description` varchar(255) NOT NULL DEFAULT '',
  `sort_order` int(5) DEFAULT NULL,
  `visible` int(1) DEFAULT '1',
  PRIMARY KEY (`configuration_group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `faqdesk_configuration_group` VALUES(1, 'Listing Settings', 'Listing Page configuration options', 1, 1);
INSERT INTO `faqdesk_configuration_group` VALUES(2, 'Frontpage Settings', 'Front Page configuration options', 1, 1);
INSERT INTO `faqdesk_configuration_group` VALUES(3, 'Reviews Settings', 'Reviews configuration options', 1, 1);
INSERT INTO `faqdesk_configuration_group` VALUES(4, 'Sticky Settings', 'Reviews configuration options', 1, 1);

CREATE TABLE IF NOT EXISTS `faqdesk_description` (
  `faqdesk_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL DEFAULT '1',
  `faqdesk_question` varchar(64) NOT NULL DEFAULT '',
  `faqdesk_answer_long` text,
  `faqdesk_answer_short` text,
  `faqdesk_extra_url` varchar(255) DEFAULT NULL,
  `faqdesk_extra_url_name` varchar(255) DEFAULT NULL,
  `faqdesk_extra_viewed` int(5) DEFAULT '0',
  `faqdesk_image_text` text,
  `faqdesk_image_text_two` text,
  `faqdesk_image_text_three` text,
  PRIMARY KEY (`faqdesk_id`,`language_id`),
  KEY `faqdesk_question` (`faqdesk_question`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

CREATE TABLE IF NOT EXISTS `faqdesk_reviews` (
  `reviews_id` int(11) NOT NULL AUTO_INCREMENT,
  `faqdesk_id` int(11) NOT NULL DEFAULT '0',
  `customers_id` int(11) DEFAULT NULL,
  `customers_name` varchar(64) NOT NULL DEFAULT '',
  `reviews_rating` int(1) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `reviews_read` int(5) NOT NULL DEFAULT '0',
  `approved` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`reviews_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `faqdesk_reviews_description` (
  `reviews_id` int(11) NOT NULL DEFAULT '0',
  `languages_id` int(11) NOT NULL DEFAULT '0',
  `reviews_text` text NOT NULL,
  PRIMARY KEY (`reviews_id`,`languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `faqdesk_to_categories` (
  `faqdesk_id` int(11) NOT NULL DEFAULT '0',
  `categories_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`faqdesk_id`,`categories_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `files_uploaded` (
  `files_uploaded_id` int(11) NOT NULL AUTO_INCREMENT,
  `sesskey` varchar(32) DEFAULT NULL,
  `customers_id` int(11) DEFAULT NULL,
  `files_uploaded_name` varchar(64) NOT NULL,
  PRIMARY KEY (`files_uploaded_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Must always have either a sesskey or customers_id' AUTO_INCREMENT=26 ;

CREATE TABLE IF NOT EXISTS `forum` (
  `forumid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `description` char(250) NOT NULL DEFAULT '',
  `active` smallint(6) NOT NULL DEFAULT '0',
  `displayorder` smallint(6) NOT NULL DEFAULT '0',
  `replycount` int(10) unsigned NOT NULL DEFAULT '0',
  `lastpost` int(11) NOT NULL DEFAULT '0',
  `threadcount` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `allowposting` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`forumid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

CREATE TABLE IF NOT EXISTS `geo_zones` (
  `geo_zone_id` int(11) NOT NULL AUTO_INCREMENT,
  `geo_zone_name` varchar(32) NOT NULL DEFAULT '',
  `geo_zone_description` varchar(255) NOT NULL DEFAULT '',
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`geo_zone_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

INSERT INTO `geo_zones` VALUES(23, 'US - Lower 48, DC', 'US - Lower 48, DC', NULL, '2011-11-07 16:47:59');
INSERT INTO `geo_zones` VALUES(21, 'US - AK, HI, APO/FPO', 'US - AK, HI, APO/FPO', NULL, '2011-11-07 16:47:44');
INSERT INTO `geo_zones` VALUES(20, 'US - 50 States, DC, APO/FPO', 'US - 50 States, DC, APO/FPO', NULL, '2011-11-07 16:47:37');
INSERT INTO `geo_zones` VALUES(22, 'US - All States and Territories', 'US - All States and Territories', NULL, '2011-11-07 16:47:51');
INSERT INTO `geo_zones` VALUES(19, 'US - 50 States, DC', 'US - 50 States, DC', NULL, '2011-11-07 16:47:30');
INSERT INTO `geo_zones` VALUES(18, 'International', 'Zone to Cover the Rest of the World', NULL, '2011-11-03 02:10:45');
INSERT INTO `geo_zones` VALUES(24, 'Washington State', 'WA Tax Zone', NULL, '2012-09-20 22:36:02');

CREATE TABLE IF NOT EXISTS `google_checkout` (
  `customers_id` int(11) DEFAULT NULL,
  `buyer_id` bigint(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `google_checkout` VALUES(30, 809562694029263);
INSERT INTO `google_checkout` VALUES(30, 809562694029263);
INSERT INTO `google_checkout` VALUES(30, 809562694029263);
INSERT INTO `google_checkout` VALUES(30, 809562694029263);
INSERT INTO `google_checkout` VALUES(30, 809562694029263);
INSERT INTO `google_checkout` VALUES(30, 809562694029263);
INSERT INTO `google_checkout` VALUES(30, 809562694029263);
INSERT INTO `google_checkout` VALUES(30, 809562694029263);
INSERT INTO `google_checkout` VALUES(30, 809562694029263);
INSERT INTO `google_checkout` VALUES(30, 809562694029263);
INSERT INTO `google_checkout` VALUES(30, 809562694029263);
INSERT INTO `google_checkout` VALUES(30, 809562694029263);
INSERT INTO `google_checkout` VALUES(30, 809562694029263);
INSERT INTO `google_checkout` VALUES(30, 809562694029263);
INSERT INTO `google_checkout` VALUES(30, 809562694029263);

CREATE TABLE IF NOT EXISTS `google_orders` (
  `orders_id` int(11) DEFAULT NULL,
  `google_order_number` bigint(20) DEFAULT NULL,
  `order_amount` decimal(15,4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `languages` (
  `languages_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '',
  `code` char(2) NOT NULL DEFAULT '',
  `image` varchar(64) DEFAULT NULL,
  `directory` varchar(32) DEFAULT NULL,
  `sort_order` int(3) DEFAULT NULL,
  PRIMARY KEY (`languages_id`),
  KEY `IDX_LANGUAGES_NAME` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

INSERT INTO `languages` VALUES(1, 'English', 'en', 'icon.gif', 'english', 1);

CREATE TABLE IF NOT EXISTS `links` (
  `links_id` int(4) NOT NULL AUTO_INCREMENT,
  `link_url` varchar(127) NOT NULL DEFAULT '',
  `link_description` text NOT NULL,
  `link_codes` varchar(127) NOT NULL,
  `link_state` int(1) NOT NULL DEFAULT '0',
  `link_date` varchar(10) NOT NULL DEFAULT '',
  `link_title` varchar(100) NOT NULL DEFAULT '',
  `links_image` varchar(64) DEFAULT NULL,
  `name` varchar(31) NOT NULL DEFAULT '',
  `email` varchar(127) NOT NULL DEFAULT '',
  `reciprocal` varchar(127) NOT NULL DEFAULT '',
  `link_found` int(1) NOT NULL DEFAULT '0',
  `category` int(2) DEFAULT NULL,
  `new_category` varchar(32) DEFAULT NULL,
  `date_last_checked` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`links_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

INSERT INTO `links` VALUES(5, 'http://www.storecoders.com', 'osCommerce Web Design from StoreCoders. Ranked 1 osCommerce developer. Each osCommerce programmer has 3 yrs oscommerce development experience. We are the leading oscommerce web design company. Quality affordable osCommerce web design services from StoreCoders.', '', 1, '2009-09-11', 'osCommerce Web Design Programmers and Developers', '', 'Jason Phillips', 'info@storecoders.com', 'http://www.storecoders.com', 0, 0, '', '2009-09-11 23:37:31');
INSERT INTO `links` VALUES(6, 'http://www.cartstore.com/', 'CartStore is professional grade PHP shopping cart software. 3 Versions to choose from Free PRO and B2B. CartStore features advanced functionality for all types of online stores including B2B features wholesaling multiple pricing per customer and so much more.', '', 1, '2009-09-11', 'CartStore Shopping Cart Software', '', 'Jason', 'info@cartstore.com', 'http://www.cartstore.com/', 0, 0, '', '0000-00-00 00:00:00');

CREATE TABLE IF NOT EXISTS `links_categories` (
  `category_id` int(3) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(32) NOT NULL,
  `sort_order` int(2) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`),
  KEY `idx_date_added` (`date_added`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

INSERT INTO `links_categories` VALUES(20, 'Sample Category', 1, '2009-09-12 00:15:24', '0000-00-00 00:00:00', 1);

CREATE TABLE IF NOT EXISTS `links_check` (
  `links_id` int(11) NOT NULL AUTO_INCREMENT,
  `date_last_checked` datetime DEFAULT NULL,
  `link_found` tinyint(1) NOT NULL,
  PRIMARY KEY (`links_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `links_description` (
  `links_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL DEFAULT '1',
  `links_title` varchar(64) NOT NULL DEFAULT '',
  `links_description` text,
  PRIMARY KEY (`links_id`,`language_id`),
  KEY `links_title` (`links_title`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `links_exchange` (
  `links_exchange_name` varchar(255) NOT NULL DEFAULT '',
  `links_exchange_description` text,
  `links_exchange_url` varchar(255) DEFAULT NULL,
  `language_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`links_exchange_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `links_featured` (
  `links_id` int(11) NOT NULL AUTO_INCREMENT,
  `date_added` datetime DEFAULT NULL,
  `expires_date` datetime DEFAULT NULL,
  `links_all_pages` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`links_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `links_status` (
  `links_status_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `links_status_name` varchar(32) NOT NULL,
  PRIMARY KEY (`links_status_id`,`language_id`),
  KEY `idx_links_status_name` (`links_status_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `links_to_link_categories` (
  `links_id` int(11) NOT NULL,
  `link_categories_id` int(11) NOT NULL,
  PRIMARY KEY (`links_id`,`link_categories_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `link_categories_description` (
  `link_categories_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `link_categories_name` varchar(32) NOT NULL,
  `link_categories_description` text,
  PRIMARY KEY (`link_categories_id`,`language_id`),
  KEY `idx_link_categories_name` (`link_categories_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `m1_export_category_matching` (
  `feed_id` int(11) unsigned NOT NULL DEFAULT '0',
  `category_id` int(11) unsigned NOT NULL DEFAULT '0',
  `taxonomy_id` varchar(64) NOT NULL DEFAULT '0',
  PRIMARY KEY (`feed_id`,`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `m1_export_clickstats` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `time` datetime DEFAULT NULL,
  `product_id` int(11) unsigned DEFAULT NULL,
  `cse_id` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `sessid` varchar(32) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `order_quantity` int(11) DEFAULT NULL,
  `order_total` decimal(8,2) DEFAULT NULL,
  `referer` varchar(255) DEFAULT NULL,
  `ip` varchar(16) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `user_language` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `maillist` (
  `customers_firstname` varchar(20) DEFAULT NULL,
  `customers_lastname` varchar(20) DEFAULT NULL,
  `customers_email_address` varchar(40) NOT NULL DEFAULT '',
  `customers_newsletter` char(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`customers_email_address`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `manufacturers` (
  `manufacturers_id` int(11) NOT NULL AUTO_INCREMENT,
  `manufacturers_name` varchar(128) NOT NULL,
  `manufacturers_image` varchar(64) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`manufacturers_id`),
  KEY `IDX_MANUFACTURERS_NAME` (`manufacturers_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

INSERT INTO `manufacturers` VALUES(6, 'tester', '', '2012-06-25 23:55:40', NULL);
INSERT INTO `manufacturers` VALUES(7, 'test 4', '', '2012-06-25 23:55:55', NULL);

CREATE TABLE IF NOT EXISTS `manufacturers_info` (
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `languages_id` int(11) NOT NULL DEFAULT '0',
  `manufacturers_url` varchar(255) NOT NULL DEFAULT '',
  `url_clicked` int(5) NOT NULL DEFAULT '0',
  `date_last_click` datetime DEFAULT NULL,
  `manufacturers_htc_title_tag` varchar(80) DEFAULT NULL,
  `manufacturers_htc_desc_tag` longtext,
  `manufacturers_htc_keywords_tag` longtext,
  `manufacturers_htc_description` longtext,
  PRIMARY KEY (`manufacturers_id`,`languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `manufacturers_info` VALUES(6, 1, '', 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `manufacturers_info` VALUES(7, 1, '', 0, NULL, NULL, NULL, NULL, NULL);

CREATE TABLE IF NOT EXISTS `newsdesk` (
  `newsdesk_id` int(11) NOT NULL AUTO_INCREMENT,
  `newsdesk_image` varchar(64) DEFAULT NULL,
  `newsdesk_image_two` varchar(64) DEFAULT NULL,
  `newsdesk_image_three` varchar(64) DEFAULT NULL,
  `newsdesk_date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `newsdesk_last_modified` datetime DEFAULT NULL,
  `newsdesk_date_available` datetime DEFAULT NULL,
  `newsdesk_status` tinyint(1) NOT NULL DEFAULT '0',
  `newsdesk_sticky` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`newsdesk_id`),
  KEY `idx_newsdesk_date_added` (`newsdesk_date_added`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

INSERT INTO `newsdesk` VALUES(28, '', '', '', '2011-09-12 21:19:03', '2011-09-12 21:19:09', NULL, 1, 1);
INSERT INTO `newsdesk` VALUES(29, '', '', '', '2011-09-12 21:22:31', NULL, NULL, 1, 0);
INSERT INTO `newsdesk` VALUES(30, '', '', '', '2011-09-12 21:25:52', NULL, NULL, 1, 0);

CREATE TABLE IF NOT EXISTS `newsdesk_categories` (
  `categories_id` int(11) NOT NULL AUTO_INCREMENT,
  `categories_image` varchar(64) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(3) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `catagory_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`categories_id`),
  KEY `idx_categories_parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

CREATE TABLE IF NOT EXISTS `newsdesk_categories_description` (
  `categories_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `categories_name` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`categories_id`,`language_id`),
  KEY `idx_categories_name` (`categories_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `newsdesk_configuration` (
  `configuration_id` int(11) NOT NULL AUTO_INCREMENT,
  `configuration_title` varchar(64) NOT NULL DEFAULT '',
  `configuration_key` varchar(64) NOT NULL DEFAULT '',
  `configuration_value` varchar(255) NOT NULL DEFAULT '',
  `configuration_description` varchar(255) NOT NULL DEFAULT '',
  `configuration_group_id` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(5) DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `use_function` varchar(255) DEFAULT NULL,
  `set_function` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`configuration_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

INSERT INTO `newsdesk_configuration` VALUES(1, 'Search Results', 'MAX_DISPLAY_NEWSDESK_SEARCH_RESULTS', '20', 'How many articles do you want to list?', 1, 1, '0000-00-00 00:00:00', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(2, 'Page Links', 'MAX_DISPLAY_NEWSDESK_PAGE_LINKS', '5', 'Number of links to use for page-sets', 1, 2, '0000-00-00 00:00:00', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(3, 'Display Headline', 'NEWSDESK_ARTICLE_NAME', '1', 'Do you want to display the headline? (0=disable; 1=enable)', 1, 3, '2009-10-25 14:33:57', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(4, 'Display Summary', 'NEWSDESK_ARTICLE_SHORTTEXT', '1', 'Do you want to display the summary? (0=disable; \r\n\r\n1=enable)', 1, 4, '2009-10-25 14:33:21', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(5, 'Display Content', 'NEWSDESK_ARTICLE_DESCRIPTION', '1', 'Do you want to display the content? (0=disable; \r\n\r\n1=enable)', 1, 5, '2009-10-25 14:32:52', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(6, 'Display Date', 'NEWSDESK_DATE_AVAILABLE', '0', 'Do you want to display the date? (0=disable; 1=enable)', 1, 6, '2009-10-25 14:33:08', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(7, 'Display URL', 'NEWSDESK_ARTICLE_URL', '0', 'Do you want to display the outside resource URL? (0=disable; \r\n\r\n1=enable)', 1, 7, '2009-10-25 14:33:38', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(8, 'Display URL Name', 'NEWSDESK_ARTICLE_URL_NAME', '0', 'Do you want to display the outside resource URL Name (0=disable; 1=enable)', 1, 8, '2009-10-25 14:33:45', '2004-05-26 17:07:00', '', '');
INSERT INTO `newsdesk_configuration` VALUES(9, 'Display Status', 'NEWSDESK_STATUS', '0', 'Do you want to display the status for the article? (0=disable; 1=enable)', 1, 9, '2004-05-10 13:28:52', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(10, 'Display Image 1', 'NEWSDESK_IMAGE', '0', 'Do you want to display image "1" for the article? (0=disable; 1=enable)', 1, 10, '2004-05-10 13:28:57', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(11, 'Display Image 2', 'NEWSDESK_IMAGE_TWO', '0', 'Do you want to display image "2" for the article? (0=disable; 1=enable)', 1, 11, '2004-05-10 13:29:01', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(12, 'Display Image 3', 'NEWSDESK_IMAGE_THREE', '0', 'Do you want to display image "3" for the article? (0=disable; 1=enable)', 1, 12, '2004-05-10 13:29:06', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(14, 'Location of Prev/Next Navigation Bar', 'NEWSDESK_PREV_NEXT_BAR_LOCATION', '3', 'Sets the location of the Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', 1, 14, '2009-10-25 14:45:36', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(15, 'Display Main News Items', 'MAX_DISPLAY_NEWSDESK_NEWS', '5', 'How many articles do you want to display on the top page?', 2, 1, '2009-10-25 10:55:16', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(16, 'Latest News Box Counts', 'LATEST_DISPLAY_NEWSDESK_NEWS', '5', 'How many articles do you want to display in the Latest News Box?', 2, 2, '0000-00-00 00:00:00', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(17, 'Display Latest News Box', 'DISPLAY_LATEST_NEWS_BOX', '1', 'Do you want to display the Latest News Box? (0=disable; 1=enable)', 2, 3, '0000-00-00 00:00:00', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(18, 'Display News Catagory Box', 'DISPLAY_NEWS_CATAGORY_BOX', '1', 'Do you want to display the News Catagory Box? (0=disable; 1=enable)', 2, 4, '0000-00-00 00:00:00', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(19, 'Display View Counts', 'DISPLAY_NEWSDESK_VIEWCOUNT', '0', 'Do you want to display View Counts? (0=disable; 1=enable)', 2, 5, '2007-03-16 01:31:41', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(20, 'Display Read More', 'DISPLAY_NEWSDESK_READMORE', '1', 'Do you want to display Read More? (0=disable; 1=enable)', 2, 6, '0000-00-00 00:00:00', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(21, 'Display Summary', 'DISPLAY_NEWSDESK_SUMMARY', '1', 'Do you want to display the News Summary? (0=disable; 1=enable)', 2, 7, '0000-00-00 00:00:00', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(22, 'Display Headline', 'DISPLAY_NEWSDESK_HEADLINE', '1', 'Do you want to display the News Headline? (0=disable; 1=enable)', 2, 8, '0000-00-00 00:00:00', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(23, 'Display Date', 'DISPLAY_NEWSDESK_DATE', '0', 'Do you want to display the News Date? (0=disable; 1=enable)', 2, 9, '2007-03-16 06:56:11', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(24, 'Display Image 1', 'DISPLAY_NEWSDESK_IMAGE', '1', 'Do you want to display image "1" for the article? (0=disable; 1=enable)', 2, 10, '0000-00-00 00:00:00', '2003-03-03 11:59:47', '', '');
INSERT INTO `newsdesk_configuration` VALUES(25, 'Display Image 2', 'DISPLAY_NEWSDESK_IMAGE_TWO', '1', 'Do you want to display image "2" for the article? (0=disable; 1=enable)', 2, 11, '2003-03-03 12:08:55', '2003-03-03 11:59:47', '', '');
INSERT INTO `newsdesk_configuration` VALUES(26, 'Display Image 3', 'DISPLAY_NEWSDESK_IMAGE_THREE', '1', 'Do you want to display image "3" for the article? (0=disable; 1=enable)', 2, 12, '2003-03-03 12:09:16', '2003-03-03 11:59:47', '', '');
INSERT INTO `newsdesk_configuration` VALUES(27, 'Display Reviews', 'DISPLAY_NEWSDESK_REVIEWS', '1', 'Do you want to display News Reviews? (0=disable; 1=enable)', 3, 1, '2009-10-25 11:01:38', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(28, 'New Reviews', 'MAX_DISPLAY_NEW_REVIEWS', '10', 'Maximum number of new reviews to display', 3, 2, '0000-00-00 00:00:00', '2003-02-16 02:08:36', '', '');
INSERT INTO `newsdesk_configuration` VALUES(29, 'Display Headline', 'STICKY_ARTICLE_NAME', '1', 'Do you want to display the headline? (0=disable; 1=enable)', 4, 1, '0000-00-00 00:00:00', '2003-03-02 00:47:21', '', '');
INSERT INTO `newsdesk_configuration` VALUES(30, 'Display Summary', 'STICKY_ARTICLE_SHORTTEXT', '1', 'Do you want to display the summary? (0=disable; 1=enable)', 4, 2, '0000-00-00 00:00:00', '2003-03-02 00:47:21', '', '');
INSERT INTO `newsdesk_configuration` VALUES(31, 'Display Content', 'STICKY_ARTICLE_DESCRIPTION', '1', 'Do you want to display the content? (0=disable; 1=enable)', 4, 3, '2009-10-25 10:53:32', '2003-03-02 00:47:21', '', '');
INSERT INTO `newsdesk_configuration` VALUES(32, 'Display View Counts', 'STICKY_NEWSDESK_VIEWCOUNT', '0', 'Do you want to display View Counts? (0=disable; 1=enable)', 4, 4, '2007-03-16 01:31:02', '2003-03-02 00:47:21', '', '');
INSERT INTO `newsdesk_configuration` VALUES(33, 'Display Read More', 'STICKY_NEWSDESK_READMORE', '1', 'Do you want to display Read More? (0=disable; 1=enable)', 4, 5, '0000-00-00 00:00:00', '2003-03-02 00:47:21', '', '');
INSERT INTO `newsdesk_configuration` VALUES(34, 'Display Date', 'STICKY_DATE_ADDED', '0', 'Do you want to display the date? (0=disable; 1=enable)', 4, 6, '2007-03-16 01:40:43', '2003-03-02 00:47:21', '', '');
INSERT INTO `newsdesk_configuration` VALUES(35, 'Display URL', 'STICKY_ARTICLE_URL', '0', 'Do you want to display the outside resource URL? (0=disable; 1=enable)', 4, 7, '2004-05-26 17:13:50', '2003-03-02 00:47:21', '', '');
INSERT INTO `newsdesk_configuration` VALUES(36, 'Display URL Name', 'STICKY_ARTICLE_URL_NAME', '1', 'Do you want to display the outside resource URL Name (0=disable; 1=enable)', 4, 8, '2003-03-02 00:51:00', '2003-03-02 00:50:00', '', '');
INSERT INTO `newsdesk_configuration` VALUES(37, 'Display Image', 'STICKY_IMAGE', '1', 'Do you want to display image "1" for the article? (0=disable; 1=enable)', 4, 9, '2003-03-02 00:50:14', '2003-03-02 00:47:21', '', '');
INSERT INTO `newsdesk_configuration` VALUES(38, 'Display Image 2', 'STICKY_IMAGE_TWO', '1', 'Do you want to display image "2"for the article? (0=disable; 1=enable)', 4, 10, '0000-00-00 00:00:00', '2003-03-03 23:10:34', '', '');
INSERT INTO `newsdesk_configuration` VALUES(39, 'Display Image 3', 'STICKY_IMAGE_THREE', '1', 'Do you want to display image "3" for the article? (0=disable; 1=enable)', 4, 11, '0000-00-00 00:00:00', '2003-03-03 23:10:34', '', '');

CREATE TABLE IF NOT EXISTS `newsdesk_configuration_group` (
  `configuration_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `configuration_group_title` varchar(64) NOT NULL DEFAULT '',
  `configuration_group_description` varchar(255) NOT NULL DEFAULT '',
  `sort_order` int(5) DEFAULT NULL,
  `visible` int(1) DEFAULT '1',
  PRIMARY KEY (`configuration_group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `newsdesk_configuration_group` VALUES(1, 'Listing Settings', 'Listing Page configuration options', 1, 1);
INSERT INTO `newsdesk_configuration_group` VALUES(2, 'Frontpage Settings', 'Front Page configuration options', 1, 1);
INSERT INTO `newsdesk_configuration_group` VALUES(3, 'Reviews Settings', 'Reviews configuration options', 1, 1);
INSERT INTO `newsdesk_configuration_group` VALUES(4, 'Sticky Settings', 'Reviews configuration options', 1, 1);

CREATE TABLE IF NOT EXISTS `newsdesk_description` (
  `newsdesk_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL DEFAULT '1',
  `newsdesk_article_name` varchar(64) NOT NULL DEFAULT '',
  `newsdesk_article_description` text,
  `newsdesk_article_shorttext` text,
  `newsdesk_article_url` varchar(255) DEFAULT NULL,
  `newsdesk_article_url_name` varchar(255) DEFAULT NULL,
  `newsdesk_article_viewed` int(5) DEFAULT '0',
  `newsdesk_image_text` text,
  `newsdesk_image_text_two` text,
  `newsdesk_image_text_three` text,
  PRIMARY KEY (`newsdesk_id`,`language_id`),
  KEY `newsdesk_article_name` (`newsdesk_article_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

INSERT INTO `newsdesk_description` VALUES(28, 1, 'Mauris bibendum ornare gravida.', '<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut tristique neque et nunc facilisis porta. Sed est ipsum, ultrices at aliquam vel, eleifend nec tortor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum a neque est. Etiam eu urna in neque adipiscing porta. Donec laoreet varius velit at convallis. Ut a tortor ut sapien convallis rhoncus. Sed bibendum, nulla id dignissim bibendum, urna nulla auctor sapien, nec fermentum eros arcu nec tellus. Aenean est ipsum, venenatis nec aliquam sed, ullamcorper vitae quam. Ut luctus bibendum est vel euismod. Morbi euismod scelerisque lorem, nec ornare nulla cursus eu.<br />\r\n	<br />\r\n	Vestibulum placerat accumsan ligula, et elementum enim tempor sit amet. Integer id nulla a tellus commodo viverra. Mauris dapibus placerat lectus, vel volutpat purus placerat quis. Vivamus ac justo a sapien adipiscing bibendum in id nibh. Integer nec molestie est. Mauris lacinia mollis erat at pellentesque. Sed vitae dapibus nisi. Nunc adipiscing vehicula est, ac placerat quam gravida a. Nulla ut auctor augue. Quisque blandit libero sed dui semper a iaculis sapien ullamcorper. Praesent pulvinar justo risus. Nulla vehicula mauris purus, et imperdiet mi. Sed id turpis eu augue commodo luctus.<br />\r\n	<br />\r\n	Mauris bibendum ornare gravida. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut lacinia fermentum odio, sit amet volutpat arcu ullamcorper in. Proin et nunc magna, malesuada rutrum augue. Quisque lacus massa, aliquam ut condimentum quis, tristique sagittis metus. Nunc aliquam blandit egestas. In hac habitasse platea dictumst. Nullam ut metus odio, sit amet hendrerit enim.<br />\r\n	<br />\r\n	Vivamus ut imperdiet nunc. Nulla neque velit, lobortis sit amet faucibus eget, ullamcorper non dolor. Aliquam purus purus, volutpat facilisis tristique a, sollicitudin sit amet lorem. Fusce pharetra sollicitudin fringilla. Donec nibh nibh, dictum eget sodales condimentum, congue vitae odio. Praesent nisi sapien, convallis a malesuada sed, aliquam vel est. Praesent lobortis rhoncus nisi, vel cursus arcu bibendum at. Cras mattis fermentum lacus a tincidunt. Duis quis dui magna, ut semper massa. Vivamus at sapien erat. Cras porta est eu urna porttitor nec euismod arcu porttitor. Duis lectus est, congue et mollis a, condimentum non nulla. Vivamus mi augue, sollicitudin non commodo eget, pellentesque vitae ante.<br />\r\n	<br />\r\n	Pellentesque lacinia, dui quis semper fermentum, nisi felis blandit ante, et convallis lorem sem a urna. In ut dolor massa. Morbi sapien nisi, luctus id elementum eu, egestas eu felis. Cras elementum, augue at molestie euismod, dui dolor porttitor nibh, nec laoreet tortor neque quis tellus. Maecenas ac dui sed nisi tempor congue. Suspendisse potenti. Quisque luctus urna et neque ultrices ut luctus lectus interdum.</p>', '<p>\r\n	<img alt="" src="/images/iStock_000012242123XSmall.jpg" style="width: 200px; height: 300px; margin: 5px; float: right;" />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut tristique neque et nunc facilisis porta. Sed est ipsum, ultrices at aliquam vel, eleifend nec tortor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum a neque est. Etiam eu urna in neque adipiscing porta. Donec laoreet varius velit at convallis. Ut a tortor ut sapien convallis rhoncus. Sed bibendum, nulla id dignissim bibendum, urna nulla auctor sapien, nec fermentum eros arcu nec tellus. Aenean est ipsum, venenatis nec aliquam sed, ullamcorper vitae quam. Ut luctus bibendum est vel euismod. Morbi euismod scelerisque lorem, nec ornare nulla cursus eu.</p>', '', '', 179, '', '', '');
INSERT INTO `newsdesk_description` VALUES(29, 1, 'Integer id nulla a tellus commodo viverra.', '<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut tristique neque et nunc facilisis porta. Sed est ipsum, ultrices at aliquam vel, eleifend nec tortor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum a neque est. Etiam eu urna in neque adipiscing porta. Donec laoreet varius velit at convallis. Ut a tortor ut sapien convallis rhoncus. Sed bibendum, nulla id dignissim bibendum, urna nulla auctor sapien, nec fermentum eros arcu nec tellus. Aenean est ipsum, venenatis nec aliquam sed, ullamcorper vitae quam. Ut luctus bibendum est vel euismod. Morbi euismod scelerisque lorem, nec ornare nulla cursus eu.<br />\r\n	<br />\r\n	Vestibulum placerat accumsan ligula, et elementum enim tempor sit amet. Integer id nulla a tellus commodo viverra. Mauris dapibus placerat lectus, vel volutpat purus placerat quis. Vivamus ac justo a sapien adipiscing bibendum in id nibh. Integer nec molestie est. Mauris lacinia mollis erat at pellentesque. Sed vitae dapibus nisi. Nunc adipiscing vehicula est, ac placerat quam gravida a. Nulla ut auctor augue. Quisque blandit libero sed dui semper a iaculis sapien ullamcorper. Praesent pulvinar justo risus. Nulla vehicula mauris purus, et imperdiet mi. Sed id turpis eu augue commodo luctus.<br />\r\n	<br />\r\n	Mauris bibendum ornare gravida. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut lacinia fermentum odio, sit amet volutpat arcu ullamcorper in. Proin et nunc magna, malesuada rutrum augue. Quisque lacus massa, aliquam ut condimentum quis, tristique sagittis metus. Nunc aliquam blandit egestas. In hac habitasse platea dictumst. Nullam ut metus odio, sit amet hendrerit enim.<br />\r\n	<br />\r\n	Vivamus ut imperdiet nunc. Nulla neque velit, lobortis sit amet faucibus eget, ullamcorper non dolor. Aliquam purus purus, volutpat facilisis tristique a, sollicitudin sit amet lorem. Fusce pharetra sollicitudin fringilla. Donec nibh nibh, dictum eget sodales condimentum, congue vitae odio. Praesent nisi sapien, convallis a malesuada sed, aliquam vel est. Praesent lobortis rhoncus nisi, vel cursus arcu bibendum at. Cras mattis fermentum lacus a tincidunt. Duis quis dui magna, ut semper massa. Vivamus at sapien erat. Cras porta est eu urna porttitor nec euismod arcu porttitor. Duis lectus est, congue et mollis a, condimentum non nulla. Vivamus mi augue, sollicitudin non commodo eget, pellentesque vitae ante.<br />\r\n	<br />\r\n	Pellentesque lacinia, dui quis semper fermentum, nisi felis blandit ante, et convallis lorem sem a urna. In ut dolor massa. Morbi sapien nisi, luctus id elementum eu, egestas eu felis. Cras elementum, augue at molestie euismod, dui dolor porttitor nibh, nec laoreet tortor neque quis tellus. Maecenas ac dui sed nisi tempor congue. Suspendisse potenti. Quisque luctus urna et neque ultrices ut luctus lectus interdum.</p>', '<p>\r\n	<img alt="" src="/images/iStock_000003553750XSmall.jpg" style="width: 150px; height: 111px; margin: 5px; float: left;" />Mauris bibendum ornare gravida. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut lacinia fermentum odio, sit amet volutpat arcu ullamcorper in. Proin et nunc magna, malesuada rutrum augue. Quisque lacus massa, aliquam ut condimentum quis, tristique sagittis metus. Nunc aliquam blandit egestas. In hac habitasse platea dictumst. Nullam ut metus odio, sit amet hendrerit enim.</p>', '', '', 150, '', '', '');
INSERT INTO `newsdesk_description` VALUES(30, 1, 'Pellentesque lacinia, dui quis semper fermentum, nisi felis blan', '<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut tristique neque et nunc facilisis porta. Sed est ipsum, ultrices at aliquam vel, eleifend nec tortor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum a neque est. Etiam eu urna in neque adipiscing porta. Donec laoreet varius velit at convallis. Ut a tortor ut sapien convallis rhoncus. Sed bibendum, nulla id dignissim bibendum, urna nulla auctor sapien, nec fermentum eros arcu nec tellus. Aenean est ipsum, venenatis nec aliquam sed, ullamcorper vitae quam. Ut luctus bibendum est vel euismod. Morbi euismod scelerisque lorem, nec ornare nulla cursus eu.<br />\r\n	<br />\r\n	Vestibulum placerat accumsan ligula, et elementum enim tempor sit amet. Integer id nulla a tellus commodo viverra. Mauris dapibus placerat lectus, vel volutpat purus placerat quis. Vivamus ac justo a sapien adipiscing bibendum in id nibh. Integer nec molestie est. Mauris lacinia mollis erat at pellentesque. Sed vitae dapibus nisi. Nunc adipiscing vehicula est, ac placerat quam gravida a. Nulla ut auctor augue. Quisque blandit libero sed dui semper a iaculis sapien ullamcorper. Praesent pulvinar justo risus. Nulla vehicula mauris purus, et imperdiet mi. Sed id turpis eu augue commodo luctus.<br />\r\n	<br />\r\n	Mauris bibendum ornare gravida. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut lacinia fermentum odio, sit amet volutpat arcu ullamcorper in. Proin et nunc magna, malesuada rutrum augue. Quisque lacus massa, aliquam ut condimentum quis, tristique sagittis metus. Nunc aliquam blandit egestas. In hac habitasse platea dictumst. Nullam ut metus odio, sit amet hendrerit enim.<br />\r\n	<br />\r\n	<img alt="" src="/images/iStock_000012242123XSmall.jpg" style="width: 283px; height: 424px; margin: 5px; float: left;" />Vivamus ut imperdiet nunc. Nulla neque velit, lobortis sit amet faucibus eget, ullamcorper non dolor. Aliquam purus purus, volutpat facilisis tristique a, sollicitudin sit amet lorem. Fusce pharetra sollicitudin fringilla. Donec nibh nibh, dictum eget sodales condimentum, congue vitae odio. Praesent nisi sapien, convallis a malesuada sed, aliquam vel est. Praesent lobortis rhoncus nisi, vel cursus arcu bibendum at. Cras mattis fermentum lacus a tincidunt. Duis quis dui magna, ut semper massa. Vivamus at sapien erat. Cras porta est eu urna porttitor nec euismod arcu porttitor. Duis lectus est, congue et mollis a, condimentum non nulla. Vivamus mi augue, sollicitudin non commodo eget, pellentesque vitae ante.<br />\r\n	<br />\r\n	Pellentesque lacinia, dui quis semper fermentum, nisi felis blandit ante, et convallis lorem sem a urna. In ut dolor massa. Morbi sapien nisi, luctus id elementum eu, egestas eu felis. Cras elementum, augue at molestie euismod, dui dolor porttitor nibh, nec laoreet tortor neque quis tellus. Maecenas ac dui sed nisi tempor congue. Suspendisse potenti. Quisque luctus urna et neque ultrices ut luctus lectus interdum.</p>', '<p>\r\n	<img alt="" src="/images/iStock_000004105680XSmall.jpg" style="width: 300px; height: 199px; margin: 5px; float: right;" />Vestibulum placerat accumsan ligula, et elementum enim tempor sit amet. Integer id nulla a tellus commodo viverra. Mauris dapibus placerat lectus, vel volutpat purus placerat quis. Vivamus ac justo a sapien adipiscing bibendum in id nibh. Integer nec molestie est. Mauris lacinia mollis erat at pellentesque. Sed vitae dapibus nisi. Nunc adipiscing vehicula est, ac placerat quam gravida a. Nulla ut auctor augue. Quisque blandit libero sed dui semper a iaculis sapien ullamcorper. Praesent pulvinar justo risus. Nulla vehicula mauris purus, et imperdiet mi. Sed id turpis eu augue commodo luctus.</p>', '', '', 325, '', '', '');

CREATE TABLE IF NOT EXISTS `newsdesk_reviews` (
  `reviews_id` int(11) NOT NULL AUTO_INCREMENT,
  `newsdesk_id` int(11) NOT NULL DEFAULT '0',
  `customers_id` int(11) DEFAULT NULL,
  `customers_name` varchar(64) NOT NULL DEFAULT '',
  `reviews_rating` int(1) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `reviews_read` int(5) NOT NULL DEFAULT '0',
  `approved` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`reviews_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

CREATE TABLE IF NOT EXISTS `newsdesk_reviews_description` (
  `reviews_id` int(11) NOT NULL DEFAULT '0',
  `languages_id` int(11) NOT NULL DEFAULT '0',
  `reviews_text` text NOT NULL,
  PRIMARY KEY (`reviews_id`,`languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `newsdesk_to_categories` (
  `newsdesk_id` int(11) NOT NULL DEFAULT '0',
  `categories_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`newsdesk_id`,`categories_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `newsdesk_to_categories` VALUES(28, 0);
INSERT INTO `newsdesk_to_categories` VALUES(29, 0);
INSERT INTO `newsdesk_to_categories` VALUES(30, 0);

CREATE TABLE IF NOT EXISTS `newsletters` (
  `newsletters_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `module` varchar(255) NOT NULL DEFAULT '',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_sent` datetime DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `locked` int(1) DEFAULT '0',
  PRIMARY KEY (`newsletters_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

CREATE TABLE IF NOT EXISTS `orders` (
  `orders_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_id` int(11) NOT NULL DEFAULT '0',
  `customers_name` varchar(64) NOT NULL DEFAULT '',
  `customers_company` varchar(32) DEFAULT NULL,
  `customers_street_address` varchar(64) NOT NULL DEFAULT '',
  `customers_street_address_2` varchar(64) NOT NULL,
  `customers_suburb` varchar(32) DEFAULT NULL,
  `customers_city` varchar(32) NOT NULL DEFAULT '',
  `customers_postcode` varchar(10) NOT NULL DEFAULT '',
  `customers_state` varchar(32) DEFAULT NULL,
  `customers_country` varchar(32) NOT NULL DEFAULT '',
  `customers_telephone` varchar(32) NOT NULL DEFAULT '',
  `customers_email_address` varchar(96) NOT NULL DEFAULT '',
  `customers_address_format_id` int(5) NOT NULL DEFAULT '0',
  `delivery_name` varchar(64) NOT NULL DEFAULT '',
  `delivery_company` varchar(32) DEFAULT NULL,
  `delivery_street_address` varchar(64) NOT NULL DEFAULT '',
  `delivery_street_address_2` varchar(64) NOT NULL,
  `delivery_suburb` varchar(32) DEFAULT NULL,
  `delivery_city` varchar(32) NOT NULL DEFAULT '',
  `delivery_postcode` varchar(10) NOT NULL DEFAULT '',
  `delivery_state` varchar(32) DEFAULT NULL,
  `delivery_country` varchar(32) NOT NULL DEFAULT '',
  `delivery_address_format_id` int(5) NOT NULL DEFAULT '0',
  `billing_name` varchar(64) NOT NULL DEFAULT '',
  `billing_company` varchar(32) DEFAULT NULL,
  `billing_street_address` varchar(64) NOT NULL DEFAULT '',
  `billing_street_address_2` varchar(64) NOT NULL,
  `billing_suburb` varchar(32) DEFAULT NULL,
  `billing_city` varchar(32) NOT NULL DEFAULT '',
  `billing_postcode` varchar(10) NOT NULL DEFAULT '',
  `billing_state` varchar(32) DEFAULT NULL,
  `billing_country` varchar(32) NOT NULL DEFAULT '',
  `billing_address_format_id` int(5) NOT NULL DEFAULT '0',
  `payment_method` varchar(32) NOT NULL DEFAULT '',
  `cc_type` varchar(20) DEFAULT NULL,
  `cc_owner` varchar(64) DEFAULT NULL,
  `cc_number` varchar(96) DEFAULT NULL,
  `cc_expires` varchar(4) DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_purchased` datetime DEFAULT NULL,
  `orders_status` int(5) NOT NULL DEFAULT '0',
  `orders_date_finished` datetime DEFAULT NULL,
  `usps_track_num` varchar(40) DEFAULT NULL,
  `usps_track_num2` varchar(40) DEFAULT NULL,
  `ups_track_num` varchar(40) DEFAULT NULL,
  `ups_track_num2` varchar(40) DEFAULT NULL,
  `fedex_track_num` varchar(40) DEFAULT NULL,
  `fedex_track_num2` varchar(40) DEFAULT NULL,
  `dhl_track_num` varchar(40) DEFAULT NULL,
  `dhl_track_num2` varchar(40) DEFAULT NULL,
  `currency` char(3) DEFAULT NULL,
  `currency_value` decimal(14,6) DEFAULT NULL,
  `account_name` varchar(32) NOT NULL DEFAULT '',
  `account_number` varchar(20) DEFAULT NULL,
  `po_number` varchar(12) DEFAULT NULL,
  `shipping_tax` decimal(7,4) NOT NULL DEFAULT '0.0000',
  `qbi_imported` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `delivery_date` date NOT NULL,
  `delivery_time_slotid` int(10) NOT NULL,
  `quickbooksid` varchar(150) NOT NULL,
  `wa_dest_tax` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`orders_id`),
  KEY `qbi_imported` (`qbi_imported`),
  KEY `customers_id` (`customers_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2005 ;

INSERT INTO `orders` VALUES(2004, 0, 'tester test', '', '123 test way', '', '', 'orlando', '32805', 'Florida', 'United States', '12345678', 'adoovo@gmail.com', 2, 'tester test', '', '123 test way', '', '', 'orlando', '32805', 'Florida', 'United States', 2, 'tester test', '', '123 test way', '', '', 'orlando', '32805', 'Florida', 'United States', 2, 'Check/Money Order', '', '', '', '', NULL, '2012-10-03 23:13:02', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'USD', 1.000000, '', NULL, NULL, 0.0000, 0, '0000-00-00', 0, '', 0);
INSERT INTO `orders` VALUES(2003, 0, 'tester test', '', '123 test way', '', '', 'orlando', '32805', 'Florida', 'United States', '12345678', 'adoovo@gmail.com', 2, 'tester test', '', '123 test way', '', '', 'orlando', '32805', 'Florida', 'United States', 2, 'tester test', '', '123 test way', '', '', 'orlando', '32805', 'Florida', 'United States', 2, 'Check/Money Order', '', '', '', '', NULL, '2012-10-03 22:38:32', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'USD', 1.000000, '', NULL, NULL, 0.0000, 0, '0000-00-00', 0, '', 0);
INSERT INTO `orders` VALUES(2002, 0, 'tester test', '', '123 test way', '', '', 'orlando', '32805', 'Florida', 'United States', '12345678', 'adoovo@gmail.com', 2, 'tester test', '', '123 test way', '', '', 'orlando', '32805', 'Florida', 'United States', 2, 'tester test', '', '123 test way', '', '', 'orlando', '32805', 'Florida', 'United States', 2, 'Check/Money Order', '', '', '', '', NULL, '2012-10-03 22:34:31', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'USD', 1.000000, '', NULL, NULL, 0.0000, 0, '0000-00-00', 0, '', 0);
INSERT INTO `orders` VALUES(2001, 0, 'tester test', '', '123 test way', '', '', 'orlando', '32805', 'Florida', 'United States', '12345678', 'adoovo@gmail.com', 2, 'tester test', '', '123 test way', '', '', 'orlando', '32805', 'Florida', 'United States', 2, 'tester test', '', '123 test way', '', '', 'orlando', '32805', 'Florida', 'United States', 2, 'Check/Money Order', '', '', '', '', NULL, '2012-10-03 22:22:23', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'USD', 1.000000, '', NULL, NULL, 0.0000, 0, '0000-00-00', 0, '', 0);
INSERT INTO `orders` VALUES(2000, 0, 'tester test', '', '123 test way', '', '', 'orlando', '32805', 'Florida', 'United States', '12345678', 'adoovo@gmail.com', 2, 'tester test', '', '123 test way', '', '', 'orlando', '32805', 'Florida', 'United States', 2, 'tester test', '', '123 test way', '', '', 'orlando', '32805', 'Florida', 'United States', 2, 'Check/Money Order', '', '', '', '', NULL, '2012-10-03 21:53:10', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'USD', 1.000000, '', NULL, NULL, 0.0000, 0, '0000-00-00', 0, '', 0);

CREATE TABLE IF NOT EXISTS `orders_pay_methods` (
  `pay_methods_id` int(11) NOT NULL AUTO_INCREMENT,
  `pay_method` varchar(255) NOT NULL DEFAULT '',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`pay_methods_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `orders_pay_methods` VALUES(1, 'Bank Transfer Payments', '2003-11-01 13:00:34');
INSERT INTO `orders_pay_methods` VALUES(2, 'PayPal', '2003-11-01 13:01:00');
INSERT INTO `orders_pay_methods` VALUES(3, 'Check/Money Order', '2003-11-01 13:01:36');
INSERT INTO `orders_pay_methods` VALUES(4, 'Purchase Order', '2003-11-01 13:02:07');

CREATE TABLE IF NOT EXISTS `orders_products` (
  `orders_products_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL DEFAULT '0',
  `products_id` int(11) NOT NULL DEFAULT '0',
  `products_model` varchar(64) DEFAULT NULL,
  `products_name` varchar(64) NOT NULL DEFAULT '',
  `products_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `final_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_tax` decimal(7,4) NOT NULL DEFAULT '0.0000',
  `products_quantity` int(2) NOT NULL DEFAULT '0',
  `products_stock_attributes` varchar(255) DEFAULT NULL,
  `vendors_id` int(11) NOT NULL DEFAULT '1',
  `products_returned` tinyint(2) unsigned DEFAULT '0',
  `products_exchanged` tinyint(2) NOT NULL DEFAULT '0',
  `products_exchanged_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`orders_products_id`),
  KEY `orders_id` (`orders_id`),
  KEY `products_id` (`products_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

INSERT INTO `orders_products` VALUES(17, 15, 508, 'TEST2', 'Test Product 2', 66.0000, 66.0000, 0.0000, 1, NULL, 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(18, 16, 508, 'TEST2', 'Test Product 2', 66.0000, 66.0000, 0.0000, 1, '', 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(19, 16, 507, 'TEST1', 'Test Product 1', 99.0000, 99.0000, 8.2500, 1, '', 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(20, 17, 507, 'TEST1', 'Test Product 1', 75.0000, 75.0000, 8.2500, 2, '', 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(21, 18, 507, 'TEST1', 'Test Product 1', 99.0000, 99.0000, 8.2500, 1, '', 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(22, 19, 518, '', 'Tract Sample Product 1', 0.8000, 0.8000, 0.0000, 3, '', 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(23, 20, 508, 'TEST2', 'Test Product 2', 66.0000, 66.0000, 0.0000, 2, '', 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(24, 21, 508, 'TEST2', 'Test Product 2', 66.0000, 66.0000, 0.0000, 1, '', 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(25, 22, 508, 'TEST2', 'Test Product 2', 66.0000, 66.0000, 0.0000, 1, '', 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(26, 23, 519, '', 'test', 11.0000, 11.0000, 0.0000, 1, '', 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(27, 24, 508, 'TEST2', 'Test Product 2', 66.0000, 66.0000, 0.0000, 1, '', 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(28, 25, 508, 'TEST2', 'Test Product 2', 66.0000, 66.0000, 0.0000, 1, '', 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(29, 26, 507, 'TEST1', 'Test Product 1', 75.0000, 75.0000, 8.2500, 2, '', 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(30, 26, 512, 'GIFT_25', 'Gift Voucher', 25.0000, 25.0000, 0.0000, 1, '', 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(31, 27, 511, '', 'testing new prodduct cartstore twitter facebook out', 99.0000, 99.0000, 0.0000, 1, '', 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(32, 28, 508, 'TEST2', 'Test Product 2', 66.0000, 66.0000, 0.0000, 1, '', 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(33, 2000, 519, '', 'test', 11.0000, 11.0000, 0.0000, 1, '', 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(34, 2001, 507, 'TEST1', 'Test Product 1', 99.0000, 99.0000, 8.2500, 1, '', 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(35, 2002, 507, 'TEST1', 'Test Product 1', 75.0000, 75.0000, 8.2500, 2, '', 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(36, 2003, 507, 'TEST1', 'Test Product 1', 99.0000, 99.0000, 8.2500, 1, '', 1, 0, 0, 0);
INSERT INTO `orders_products` VALUES(37, 2004, 508, 'TEST2', 'Test Product 2', 66.0000, 66.0000, 0.0000, 1, '', 1, 0, 0, 0);

CREATE TABLE IF NOT EXISTS `orders_products_attributes` (
  `orders_products_attributes_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL DEFAULT '0',
  `orders_products_id` int(11) NOT NULL DEFAULT '0',
  `products_options` varchar(32) NOT NULL DEFAULT '',
  `products_options_values` varchar(32) NOT NULL DEFAULT '',
  `options_values_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `price_prefix` char(1) NOT NULL DEFAULT '',
  PRIMARY KEY (`orders_products_attributes_id`),
  KEY `orders_products_id` (`orders_products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `orders_products_download` (
  `orders_products_download_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL DEFAULT '0',
  `orders_products_id` int(11) NOT NULL DEFAULT '0',
  `orders_products_filename` varchar(255) NOT NULL DEFAULT '',
  `download_maxdays` int(2) NOT NULL DEFAULT '0',
  `download_count` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`orders_products_download_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `orders_shipping` (
  `orders_shipping_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL DEFAULT '0',
  `vendors_id` int(11) NOT NULL DEFAULT '1',
  `shipping_module` varchar(16) NOT NULL DEFAULT '',
  `shipping_method` varchar(128) NOT NULL DEFAULT '',
  `shipping_cost` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `shipping_tax` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `vendors_name` varchar(64) NOT NULL DEFAULT '',
  `vendor_order_sent` char(3) NOT NULL DEFAULT 'no',
  PRIMARY KEY (`orders_shipping_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `orders_ship_methods` (
  `ship_methods_id` int(11) NOT NULL AUTO_INCREMENT,
  `ship_method` varchar(255) NOT NULL DEFAULT '',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ship_methods_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

INSERT INTO `orders_ship_methods` VALUES(1, 'FedEx Priority to Canada', '2003-11-01 09:40:19');
INSERT INTO `orders_ship_methods` VALUES(2, 'FedEx Priority to USA', '2003-11-01 09:40:56');
INSERT INTO `orders_ship_methods` VALUES(3, 'FedEx Priority International', '2003-11-01 09:41:33');
INSERT INTO `orders_ship_methods` VALUES(4, 'Canada Xpresspost Post shipping', '2003-11-01 09:42:08');
INSERT INTO `orders_ship_methods` VALUES(5, 'Canada USA Xpresspost shipping USA', '2003-11-01 09:43:27');
INSERT INTO `orders_ship_methods` VALUES(6, 'Canada Post Standard Airmail shipping', '2003-11-01 09:44:00');

CREATE TABLE IF NOT EXISTS `orders_status` (
  `orders_status_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `orders_status_name` varchar(32) NOT NULL DEFAULT '',
  `downloads_flag` int(11) DEFAULT '1',
  `public_flag` int(11) DEFAULT '1',
  PRIMARY KEY (`orders_status_id`,`language_id`),
  KEY `idx_orders_status_name` (`orders_status_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `orders_status` VALUES(1, 1, 'Pending', 1, 1);
INSERT INTO `orders_status` VALUES(2, 1, 'Processing', 1, 1);
INSERT INTO `orders_status` VALUES(3, 1, 'Delivered', 1, 1);
INSERT INTO `orders_status` VALUES(4, 1, 'Preparing [PayPal Standard]', 0, 0);
INSERT INTO `orders_status` VALUES(5, 1, 'Preparing [PayPal IPN]', 1, 1);
INSERT INTO `orders_status` VALUES(100, 1, 'Google New', 1, 1);
INSERT INTO `orders_status` VALUES(101, 1, 'Google Processing', 1, 1);
INSERT INTO `orders_status` VALUES(102, 1, 'Google Shipped', 1, 1);
INSERT INTO `orders_status` VALUES(103, 1, 'Google Refunded', 1, 1);
INSERT INTO `orders_status` VALUES(104, 1, 'Google Shipped and Refunded', 1, 1);
INSERT INTO `orders_status` VALUES(105, 1, 'Google Canceled', 1, 1);

CREATE TABLE IF NOT EXISTS `orders_status_history` (
  `orders_status_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL DEFAULT '0',
  `orders_status_id` int(5) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `customer_notified` int(1) DEFAULT '0',
  `comments` text,
  PRIMARY KEY (`orders_status_history_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=240 ;

INSERT INTO `orders_status_history` VALUES(27, 27, 2, '2010-04-02 10:45:11', 1, '');
INSERT INTO `orders_status_history` VALUES(26, 26, 1, '2010-03-19 16:07:37', 1, '');
INSERT INTO `orders_status_history` VALUES(28, 26, 3, '2010-05-18 22:50:03', 1, '');
INSERT INTO `orders_status_history` VALUES(29, 27, 3, '2010-05-18 22:50:14', 1, '');
INSERT INTO `orders_status_history` VALUES(30, 28, 2, '2010-06-12 02:27:48', 1, '');
INSERT INTO `orders_status_history` VALUES(31, 29, 2, '2010-06-24 19:49:00', 1, '');
INSERT INTO `orders_status_history` VALUES(32, 29, 3, '2010-06-30 02:18:14', 1, '');
INSERT INTO `orders_status_history` VALUES(33, 28, 3, '2010-06-30 02:18:44', 1, '');
INSERT INTO `orders_status_history` VALUES(34, 30, 2, '2010-07-13 16:09:34', 1, '');
INSERT INTO `orders_status_history` VALUES(35, 31, 2, '2010-07-14 11:42:12', 1, '');
INSERT INTO `orders_status_history` VALUES(36, 32, 2, '2010-07-15 14:12:37', 1, 'I tried to use a discount code that was on facebook but it didn''t work. I guess this just isn''t my day on the internet :))');
INSERT INTO `orders_status_history` VALUES(37, 32, 3, '2010-07-28 18:34:37', 1, '');
INSERT INTO `orders_status_history` VALUES(38, 31, 3, '2010-07-28 18:34:46', 1, '');
INSERT INTO `orders_status_history` VALUES(39, 30, 3, '2010-07-28 18:34:53', 1, '');
INSERT INTO `orders_status_history` VALUES(40, 33, 2, '2010-07-28 18:44:20', 1, '');
INSERT INTO `orders_status_history` VALUES(41, 33, 3, '2010-08-11 18:57:03', 1, '');
INSERT INTO `orders_status_history` VALUES(42, 34, 2, '2010-08-13 03:38:03', 1, '');
INSERT INTO `orders_status_history` VALUES(43, 34, 3, '2010-08-14 03:51:49', 1, '');
INSERT INTO `orders_status_history` VALUES(44, 35, 2, '2010-08-18 02:49:28', 1, '');
INSERT INTO `orders_status_history` VALUES(45, 36, 2, '2010-08-19 00:07:57', 1, '');
INSERT INTO `orders_status_history` VALUES(46, 36, 3, '2010-08-19 17:49:52', 1, '');
INSERT INTO `orders_status_history` VALUES(47, 35, 3, '2010-08-19 17:50:00', 1, '');
INSERT INTO `orders_status_history` VALUES(48, 37, 2, '2010-08-27 23:22:09', 1, '');
INSERT INTO `orders_status_history` VALUES(49, 38, 2, '2010-08-27 23:35:06', 1, '');
INSERT INTO `orders_status_history` VALUES(50, 38, 3, '2010-08-30 14:42:25', 1, '');
INSERT INTO `orders_status_history` VALUES(51, 37, 3, '2010-08-30 14:42:36', 1, '');
INSERT INTO `orders_status_history` VALUES(52, 39, 2, '2010-09-03 21:40:30', 1, '');
INSERT INTO `orders_status_history` VALUES(53, 40, 2, '2010-09-08 19:33:36', 1, '');
INSERT INTO `orders_status_history` VALUES(54, 39, 3, '2010-09-09 01:51:39', 1, '');
INSERT INTO `orders_status_history` VALUES(55, 40, 3, '2010-09-16 19:24:23', 1, '');
INSERT INTO `orders_status_history` VALUES(56, 41, 2, '2010-10-05 13:03:55', 1, '');
INSERT INTO `orders_status_history` VALUES(57, 41, 3, '2010-10-13 17:20:40', 1, '');
INSERT INTO `orders_status_history` VALUES(58, 42, 2, '2010-10-18 19:26:10', 1, 'If at all possible, I need this order by Saturday... :) Thank you, in advance, for your assistance.');
INSERT INTO `orders_status_history` VALUES(59, 42, 3, '2010-10-18 21:05:16', 1, 'This has been shipped and you should have received the Tracking number.  You should receive the package within 2-3 days.\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(60, 43, 2, '2010-10-21 20:34:09', 1, '');
INSERT INTO `orders_status_history` VALUES(61, 43, 2, '2010-10-23 16:50:27', 1, 'This is being shipped to you today.  You should receive it in 6-10 business days.  Please let me know if you have any questions.\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(62, 43, 3, '2010-10-23 21:54:36', 1, '');
INSERT INTO `orders_status_history` VALUES(63, 44, 2, '2010-10-23 22:39:34', 1, 'Thank you!');
INSERT INTO `orders_status_history` VALUES(64, 45, 2, '2010-10-24 12:50:16', 1, '');
INSERT INTO `orders_status_history` VALUES(65, 44, 3, '2010-10-25 16:44:08', 1, 'This ships out today. You should receive in 3-4 days.\r\n\r\nThank you for your business.\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(66, 45, 2, '2010-10-25 16:45:19', 1, 'Thank you for your order Theresa.  I am preparing it for you and it should ship today.  You will receive it in 2-3 days.\r\n\r\nLet me know if you have any questions.\r\n\r\nLove and Light,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(67, 45, 3, '2010-10-27 14:21:35', 1, '');
INSERT INTO `orders_status_history` VALUES(68, 46, 2, '2010-10-29 13:16:44', 1, '');
INSERT INTO `orders_status_history` VALUES(69, 46, 3, '2010-10-31 15:06:20', 1, '');
INSERT INTO `orders_status_history` VALUES(70, 47, 2, '2010-11-07 16:39:42', 1, '');
INSERT INTO `orders_status_history` VALUES(71, 48, 2, '2010-11-07 16:41:30', 1, '');
INSERT INTO `orders_status_history` VALUES(72, 47, 3, '2010-11-09 01:49:27', 1, 'Your order shipped out today.  Please let me now when you have received it.\r\n\r\nLove and Light,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(73, 48, 3, '2010-11-09 01:49:39', 1, '');
INSERT INTO `orders_status_history` VALUES(74, 49, 2, '2010-11-15 13:33:26', 1, '');
INSERT INTO `orders_status_history` VALUES(75, 50, 2, '2010-11-15 14:09:26', 1, '');
INSERT INTO `orders_status_history` VALUES(76, 50, 3, '2010-11-15 22:54:35', 1, 'This is on its way to you.\r\n\r\nMuch love');
INSERT INTO `orders_status_history` VALUES(77, 49, 3, '2010-11-16 17:04:03', 1, 'This package was shipped out today.  You should receive it this week.  Let me know if you have any questions.\r\n\r\nLove and Light,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(78, 51, 2, '2010-11-18 04:51:38', 1, '');
INSERT INTO `orders_status_history` VALUES(79, 52, 2, '2010-11-18 08:50:56', 1, 'Hello! I need my postage to be insured, is your BEST WAY rate include insurance and tracking?');
INSERT INTO `orders_status_history` VALUES(80, 51, 2, '2010-11-19 17:42:06', 1, 'This package is going out today.  You should receive it in 6-10 business days.  Let me know if you have any questions.\r\n\r\nLove and Light,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(81, 52, 2, '2010-11-19 17:48:53', 1, 'Hello Galina,\r\n\r\nThank you for your order. The shipping included on the site is USPS International Priority Mail.  Unfortunately they do not offer insurance internationally or tracking. If you want a different kind of shipping which includes insurance, the shipping cost will be about $30.  Let me know what you would like me to do.\r\n\r\nEmail me at info@houseofshakti.com to let me know.\r\n\r\nLove and Light,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(82, 53, 2, '2010-11-21 14:14:38', 1, '');
INSERT INTO `orders_status_history` VALUES(83, 51, 3, '2010-11-21 16:45:58', 1, '');
INSERT INTO `orders_status_history` VALUES(84, 52, 3, '2010-11-22 19:47:52', 1, '');
INSERT INTO `orders_status_history` VALUES(85, 53, 3, '2010-11-22 19:48:33', 1, 'Thank you for your order David.  Your package has shipped today and should reach Dubai in 6-10 business days.\r\n\r\nLet me know if you have any questions.\r\n\r\nBest,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(86, 54, 2, '2010-11-29 11:52:14', 1, '');
INSERT INTO `orders_status_history` VALUES(87, 54, 2, '2010-11-30 04:02:21', 1, 'Hello Aiyana,\r\n\r\nI am ready to ship your order but want to confirm the address before I send.  What is the street name or is this a PO Box? \r\n\r\nOnce you confirm I can ship package to you.\r\n\r\nLove and Light,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(88, 54, 3, '2010-12-02 14:18:20', 1, 'This package was shipped to you yesterday Aiyana.  Please let me know when  you receive it.\r\n\r\nLove and Light,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(89, 55, 2, '2010-12-04 13:16:53', 1, '');
INSERT INTO `orders_status_history` VALUES(90, 55, 3, '2010-12-09 14:33:31', 1, 'This package shipped to you 2 days ago.  Please let us know if you have any questions.\r\n\r\nLove and Light,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(91, 56, 1, '2010-12-19 17:08:36', 1, '');
INSERT INTO `orders_status_history` VALUES(92, 56, 3, '2010-12-26 05:22:06', 1, '');
INSERT INTO `orders_status_history` VALUES(102, 66, 2, '2011-05-04 17:56:31', 1, '');
INSERT INTO `orders_status_history` VALUES(100, 64, 2, '2011-05-04 14:49:46', 1, '');
INSERT INTO `orders_status_history` VALUES(101, 65, 2, '2011-05-04 17:12:51', 1, '');
INSERT INTO `orders_status_history` VALUES(106, 64, 2, '2011-05-05 16:40:53', 1, 'Hello Holly,\r\n\r\nYour order is shipping today so you should receive it in the next 2 days.  I hope you like it.\r\n\r\nLove and Light,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(107, 65, 2, '2011-05-05 16:41:49', 1, 'Hello Debra,\r\n\r\nYour order is shipping today so you will receive it within 6-10 business days.  I marked the value on the customs forms at $100 so you don''t have to pay too much customs.  I hope you are ok with this.  Hope you enjoy the lariat.  It is beautiful!\r\n\r\nLove and Light,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(108, 66, 2, '2011-05-05 16:42:24', 1, 'Hello Pamela,\r\n\r\nYour order is shipping today. You will receive it in the next 3 days.  I hope you enjoy the mala.\r\n\r\nLove and Light,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(109, 64, 3, '2011-05-09 16:36:32', 1, 'Hope you enjoy your order.  Please take a moment to send a testimonial if you can.  I would really appreciate it.\r\n\r\nLove and Light,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(110, 66, 3, '2011-05-09 16:37:15', 1, 'Hope you enjoy your order Pamela.  Look forward to hearing from you.  Would love if you could send me a testimonial.\r\n\r\nLove and Light,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(114, 73, 2, '2011-05-12 01:35:38', 1, '');
INSERT INTO `orders_status_history` VALUES(115, 74, 2, '2011-05-12 02:19:08', 1, '');
INSERT INTO `orders_status_history` VALUES(116, 72, 2, '2011-05-12 03:19:05', 1, 'Hello Shari,\r\n\r\nYour package shipped today without signature confirmation.  You should receive it at you New York address within the next few days.\r\n\r\nI hope you enjoy it.  Would love to get a testimonial from you once you receive it and if you are so compelled.\r\n\r\nLove and Light,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(113, 72, 2, '2011-05-11 12:26:21', 1, '');
INSERT INTO `orders_status_history` VALUES(118, 73, 2, '2011-05-13 12:00:09', 1, 'Hello Shella,\r\n\r\nThank you for you orders.  Everything will be shipping out to you on Monday.  You should receive it 6-10 business days from then.  Let me know if you have any questions and we apologize for the difficulties you had in placing an order on the website.\r\n\r\nLove and Light,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(119, 72, 3, '2011-05-16 18:34:07', 1, '');
INSERT INTO `orders_status_history` VALUES(137, 86, 3, '2011-05-29 00:02:53', 1, '');
INSERT INTO `orders_status_history` VALUES(138, 90, 2, '2011-05-29 06:29:33', 1, '');
INSERT INTO `orders_status_history` VALUES(141, 91, 2, '2011-06-02 21:39:55', 1, '');
INSERT INTO `orders_status_history` VALUES(142, 91, 3, '2011-06-04 16:28:04', 1, '\nOrder Shipped  on 6/2/2011 1. Via  Zone Rates Tracking Number is: 9101010521297397631872. \n.');
INSERT INTO `orders_status_history` VALUES(136, 89, 2, '2011-05-27 14:55:10', 1, '');
INSERT INTO `orders_status_history` VALUES(144, 92, 2, '2011-06-07 15:47:13', 1, 'I started working with Archangel Michael & Raphel 1 year ago, recently Archangel Ariel has entered into my life. I''m excited to purchase this braclet. Thank you. Blessings, Mary');
INSERT INTO `orders_status_history` VALUES(128, 65, 3, '2011-05-19 13:21:15', 1, 'I am going to mark this order as delivered.  Please let us know if you do not receive it in a timely manner.\r\n\r\nLove and Light,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(129, 73, 3, '2011-05-19 13:21:59', 1, 'Shella\r\n\r\nI am going to mark your orders as delivered.  Please let me know if you do not receive your package in a timely manner.\r\n\r\nLove and Light,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(130, 74, 3, '2011-05-19 13:22:08', 1, '');
INSERT INTO `orders_status_history` VALUES(143, 89, 3, '2011-06-04 16:59:22', 1, '\nOrder Shipped  on 6/4/2011 1. Via  United States Postal Service Tracking Number is: LJ867055626US. \n.');
INSERT INTO `orders_status_history` VALUES(132, 86, 2, '2011-05-26 13:49:14', 1, '');
INSERT INTO `orders_status_history` VALUES(140, 90, 3, '2011-05-31 16:59:19', 1, '\nOrder Shipped  on 5/31/2011 . Via  Zone Rates Tracking Number is: LJ866163425US. \n.');
INSERT INTO `orders_status_history` VALUES(145, 92, 2, '2011-06-07 16:06:24', 1, 'Hello Mary,\r\n\r\nSo glad to hear from you and glad you found us.  I started working a little with Archangels Michael and Raphael 2 years ago.  They have helped me a lot.  I hope your bracelet brings you a lot of angelic energy. Your bracelet will ship tomorrow.  \r\n\r\nLove and Light,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(146, 93, 2, '2011-06-07 16:21:48', 1, '');
INSERT INTO `orders_status_history` VALUES(147, 93, 3, '2011-06-07 22:06:28', 1, '\nOrder Shipped  on 6/7/2011 1. Via  United States Postal Service Tracking Number is: LN767739702US. \nThank you for your order..');
INSERT INTO `orders_status_history` VALUES(148, 92, 3, '2011-06-07 22:06:28', 1, '\nOrder Shipped  on 6/8/2011 1. Via  United States Postal Service Tracking Number is: 9405510200793886032055. \nThank you for your order Mary..');
INSERT INTO `orders_status_history` VALUES(149, 94, 2, '2011-06-09 12:19:22', 1, '');
INSERT INTO `orders_status_history` VALUES(150, 94, 3, '2011-06-09 23:14:19', 1, '\nOrder Shipped  on 6/9/2011 1. Via  Priority Mail Tracking Number is: 9405510200793897153145. \n.');
INSERT INTO `orders_status_history` VALUES(151, 95, 2, '2011-06-27 12:22:40', 1, '');
INSERT INTO `orders_status_history` VALUES(152, 95, 3, '2011-06-27 14:42:15', 1, '\nOrder Shipped  on 6/27/2011 . Via  Flat Rate Tracking Number is: 9405510200793977724852. \n.');
INSERT INTO `orders_status_history` VALUES(155, 98, 2, '2011-07-04 11:34:25', 1, '');
INSERT INTO `orders_status_history` VALUES(156, 98, 3, '2011-07-08 23:42:44', 1, '\nOrder Shipped  on 7/8/2011 1. Via  Zone Rates Tracking Number is: LN768840118US. \n.');
INSERT INTO `orders_status_history` VALUES(157, 99, 2, '2011-07-13 22:38:25', 1, '');
INSERT INTO `orders_status_history` VALUES(158, 99, 3, '2011-07-14 01:58:03', 1, '\nOrder Shipped  on 7/13/2011 . Via  First Class Mail Tracking Number is: 9400110200830111661752. \n.');
INSERT INTO `orders_status_history` VALUES(164, 105, 2, '2011-07-21 04:32:43', 1, 'This is the necklace I remember you holding for me, hope I am correct. I will email you about what I want to do next.\r\nThank You.\r\n\r\nPam');
INSERT INTO `orders_status_history` VALUES(165, 105, 3, '2011-07-21 20:42:40', 1, '\nOrder Shipped  on 7/21/2011 . Via  First Class Mail Tracking Number is: 9400110200883116859310 , 9400110200828116502750. \n.');
INSERT INTO `orders_status_history` VALUES(166, 106, 2, '2011-07-22 12:54:45', 1, '');
INSERT INTO `orders_status_history` VALUES(167, 106, 3, '2011-07-22 14:49:23', 1, '\nOrder Shipped  on 7/22/2011 . Via  First Class Mail Tracking Number is: 9400110200830118255374. \n.');
INSERT INTO `orders_status_history` VALUES(168, 107, 2, '2011-07-26 15:01:07', 1, '');
INSERT INTO `orders_status_history` VALUES(169, 107, 2, '2011-07-26 18:15:21', 1, '\nOrder Shipped  on 7/26/2011 . Via  United States Postal Service Tracking Number is: LN769491935US. \n.');
INSERT INTO `orders_status_history` VALUES(170, 108, 2, '2011-07-29 20:30:14', 1, '');
INSERT INTO `orders_status_history` VALUES(171, 109, 2, '2011-07-30 18:39:20', 1, '');
INSERT INTO `orders_status_history` VALUES(172, 108, 2, '2011-07-31 01:08:18', 1, '\nOrder Shipped  on 7/30/2011 . Via  Priority Mail Tracking Number is: 9405510200829122415372. \n.');
INSERT INTO `orders_status_history` VALUES(173, 109, 2, '2011-07-31 01:11:17', 1, '\nOrder Shipped  on 8/1/2011 1. Via  United States Postal Service Tracking Number is: LN769643216US. \n.');
INSERT INTO `orders_status_history` VALUES(174, 110, 2, '2011-08-01 16:07:35', 1, 'Thank you! :)');
INSERT INTO `orders_status_history` VALUES(175, 110, 2, '2011-08-01 17:23:17', 1, '\nOrder Shipped  on 8/1/2011 1. Via  First Class Mail Tracking Number is: 9400110200830125381424. \n.');
INSERT INTO `orders_status_history` VALUES(176, 108, 3, '2011-08-03 22:50:28', 1, '');
INSERT INTO `orders_status_history` VALUES(177, 111, 2, '2011-08-04 00:58:01', 1, 'Hello Lubna,\r\n\r\nMy wrist measures 7.5", hoping this will fit.\r\n\r\nBlessings,\r\n\r\nPam');
INSERT INTO `orders_status_history` VALUES(178, 111, 2, '2011-08-04 04:03:29', 1, '\nOrder Shipped  on 8/4/2011 1. Via  First Class Mail Tracking Number is: 9400110200828125735989. \n.');
INSERT INTO `orders_status_history` VALUES(179, 111, 0, '2011-08-04 12:27:20', 0, '');
INSERT INTO `orders_status_history` VALUES(180, 111, 1, '2011-08-04 12:27:52', 1, 'Hello Pam,\r\n\r\nThe bracelet is stretchy so I hope it does fit. If not, feel free to send it back and we can work something out.\r\n\r\nBlessings,\r\n\r\nPam');
INSERT INTO `orders_status_history` VALUES(181, 110, 3, '2011-08-04 15:32:54', 1, '');
INSERT INTO `orders_status_history` VALUES(182, 112, 2, '2011-08-07 21:02:10', 1, '');
INSERT INTO `orders_status_history` VALUES(183, 112, 2, '2011-08-08 00:04:05', 1, '\nOrder Shipped  on 8/7/2011 1. Via  First Class Mail Tracking Number is: 9400110200828127677799. \n.');
INSERT INTO `orders_status_history` VALUES(184, 111, 3, '2011-08-08 02:25:59', 1, '');
INSERT INTO `orders_status_history` VALUES(185, 113, 2, '2011-08-09 18:32:22', 1, '');
INSERT INTO `orders_status_history` VALUES(186, 113, 2, '2011-08-11 13:51:23', 1, 'Hello Annie,\r\n\r\nI will be shipping your earrings tomorrow to you.  Let me know if you have any questions.\r\n\r\nBlessings,\r\n\r\nLubna');
INSERT INTO `orders_status_history` VALUES(187, 113, 2, '2011-08-12 00:55:22', 1, '\nOrder Shipped  on 8/11/2011 . Via  First Class Mail Tracking Number is: 9405510200829131714893 , 9400110200881131747537. \n.');
INSERT INTO `orders_status_history` VALUES(188, 112, 3, '2011-08-12 01:00:24', 1, '');
INSERT INTO `orders_status_history` VALUES(189, 114, 2, '2011-08-12 13:32:43', 1, 'Gift Message:\r\n\r\nDearest Jaya,\r\n\r\nWish you A Happy Raksha Bandhan. My blessings and love always.\r\nMiss you.\r\n\r\nLove,\r\nHemanshu');
INSERT INTO `orders_status_history` VALUES(190, 114, 2, '2011-08-12 13:54:25', 1, '\nOrder Shipped  on 8/12/2011 . Via  First Class Mail Tracking Number is: 9400110200882132169373. \n.');
INSERT INTO `orders_status_history` VALUES(191, 115, 2, '2011-08-13 02:38:16', 1, 'Hello Lubna,\r\n\r\nThank you in advance for my order. \r\n\r\nLove & Light,\r\n\r\nPam');
INSERT INTO `orders_status_history` VALUES(192, 115, 2, '2011-08-13 02:49:15', 1, '\nOrder Shipped  on 8/12/2011 . Via  First Class Mail Tracking Number is: 9400110200829132528311. \n.');
INSERT INTO `orders_status_history` VALUES(193, 113, 3, '2011-08-13 19:20:50', 1, '');
INSERT INTO `orders_status_history` VALUES(200, 25, 1, '2012-02-17 08:08:26', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(201, 26, 1, '2012-02-17 10:36:19', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(202, 27, 1, '2012-02-17 11:03:08', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(203, 28, 1, '2012-02-17 11:42:05', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(204, 29, 1, '2012-02-17 11:51:20', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(222, 16, 1, '2012-09-21 09:00:34', 1, '');
INSERT INTO `orders_status_history` VALUES(223, 17, 1, '2012-09-27 01:54:39', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(224, 18, 1, '2012-10-01 16:00:48', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(225, 19, 1, '2012-10-01 16:09:54', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(226, 20, 1, '2012-10-01 16:12:58', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(227, 21, 1, '2012-10-03 20:23:36', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(228, 22, 1, '2012-10-03 20:29:08', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(229, 23, 1, '2012-10-03 20:39:13', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(230, 24, 1, '2012-10-03 21:05:05', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(231, 25, 1, '2012-10-03 21:15:51', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(232, 26, 1, '2012-10-03 21:21:25', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(233, 27, 1, '2012-10-03 21:31:55', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(234, 28, 1, '2012-10-03 21:40:03', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(235, 2000, 1, '2012-10-03 21:53:10', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(236, 2001, 1, '2012-10-03 22:22:23', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(237, 2002, 1, '2012-10-03 22:34:31', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(238, 2003, 1, '2012-10-03 22:38:32', 1, '<br />');
INSERT INTO `orders_status_history` VALUES(239, 2004, 1, '2012-10-03 23:13:02', 1, '<br />');

CREATE TABLE IF NOT EXISTS `orders_total` (
  `orders_total_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `text` varchar(255) NOT NULL DEFAULT '',
  `value` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `class` varchar(32) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`orders_total_id`),
  KEY `idx_orders_total_orders_id` (`orders_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=598 ;

INSERT INTO `orders_total` VALUES(78, 27, 'Sub-Total:', '$165.00', 165.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(79, 27, 'Table Rate (Best Way):', '$13.45', 13.4500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(80, 27, 'Total:', '<b>$178.45</b>', 178.4500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(75, 26, 'Sub-Total:', '$35.00', 35.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(76, 26, 'Table Rate (Best Way):', '$5.50', 5.5000, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(77, 26, 'Total:', '<b>$40.50</b>', 40.5000, 'ot_total', 4);
INSERT INTO `orders_total` VALUES(81, 28, 'Sub-Total:', '$165.00', 165.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(82, 28, 'Table Rate (Best Way):', '$13.45', 13.4500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(83, 28, 'Total:', '<b>$178.45</b>', 178.4500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(84, 29, 'Sub-Total:', '$135.00', 135.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(85, 29, 'Table Rate (Best Way):', '$13.45', 13.4500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(86, 29, 'Total:', '<b>$148.45</b>', 148.4500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(87, 30, 'Sub-Total:', '$170.00', 170.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(88, 30, 'Table Rate (Best Way):', '$13.45', 13.4500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(89, 30, 'Total:', '<b>$183.45</b>', 183.4500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(90, 31, 'Sub-Total:', '$42.00', 42.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(91, 31, 'Table Rate (Best Way):', '$7.30', 7.3000, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(92, 31, 'Total:', '<b>$49.30</b>', 49.3000, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(93, 32, 'Sub-Total:', '$45.00', 45.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(94, 32, 'Table Rate (Best Way):', '$7.30', 7.3000, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(95, 32, 'Total:', '<b>$52.30</b>', 52.3000, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(96, 33, 'Sub-Total:', '$145.00', 145.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(97, 33, 'Table Rate (Best Way):', '$13.45', 13.4500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(98, 33, 'Total:', '<b>$158.45</b>', 158.4500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(99, 34, 'Sub-Total:', '$360.00', 360.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(100, 34, 'Table Rate (Best Way):', '$13.45', 13.4500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(101, 34, 'Total:', '<b>$373.45</b>', 373.4500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(102, 35, 'Sub-Total:', '$45.00', 45.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(103, 35, 'Table Rate (Best Way):', '$7.30', 7.3000, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(104, 35, '9.75% CA Sales Tax:', '$4.39', 4.3875, 'ot_tax', 3);
INSERT INTO `orders_total` VALUES(105, 35, 'Total:', '<b>$56.69</b>', 56.6875, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(106, 36, 'Sub-Total:', '$455.00', 455.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(107, 36, 'Table Rate (Best Way):', '$13.45', 13.4500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(108, 36, 'Discount Coupon 3HOAUG applied:', '-$45.50', -45.5000, 'ot_discount_coupon', 4);
INSERT INTO `orders_total` VALUES(109, 36, 'Total:', '<b>$422.95</b>', 422.9500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(110, 37, 'Sub-Total:', '$150.00', 150.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(111, 37, 'Table Rate (Best Way):', '$13.45', 13.4500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(112, 37, 'Total:', '<b>$163.45</b>', 163.4500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(113, 38, 'Sub-Total:', '$150.00', 150.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(114, 38, 'Table Rate (Best Way):', '$13.45', 13.4500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(115, 38, 'Total:', '<b>$163.45</b>', 163.4500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(116, 39, 'Sub-Total:', '$180.00', 180.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(117, 39, 'Table Rate (Best Way):', '$13.45', 13.4500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(118, 39, 'Total:', '<b>$193.45</b>', 193.4500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(119, 40, 'Sub-Total:', '$275.00', 275.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(120, 40, 'Table Rate (Best Way):', '$13.45', 13.4500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(121, 40, 'Total:', '<b>$288.45</b>', 288.4500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(122, 41, 'Sub-Total:', '$130.00', 130.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(123, 41, 'Table Rate (Best Way):', '$13.45', 13.4500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(124, 41, 'Total:', '<b>$143.45</b>', 143.4500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(125, 42, 'Sub-Total:', '$90.00', 90.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(126, 42, 'Table Rate (Best Way):', '$8.50', 8.5000, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(127, 42, '9.75% CA Sales Tax:', '$8.78', 8.7750, 'ot_tax', 3);
INSERT INTO `orders_total` VALUES(128, 42, 'Total:', '<b>$107.28</b>', 107.2750, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(129, 43, 'Sub-Total:', '$40.00', 40.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(130, 43, 'Table Rate (Best Way):', '$7.30', 7.3000, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(131, 43, 'Total:', '<b>$47.30</b>', 47.3000, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(132, 44, 'Sub-Total:', '$35.00', 35.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(133, 44, 'Table Rate (Best Way):', '$2.50', 2.5000, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(134, 44, 'Total:', '<b>$37.50</b>', 37.5000, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(135, 45, 'Sub-Total:', '$120.00', 120.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(136, 45, 'Table Rate (Best Way):', '$13.45', 13.4500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(137, 45, '9.75% CA Sales Tax:', '$11.70', 11.7000, 'ot_tax', 3);
INSERT INTO `orders_total` VALUES(138, 45, 'Total:', '<b>$145.15</b>', 145.1500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(139, 46, 'Sub-Total:', '$100.00', 100.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(140, 46, 'Table Rate (Best Way):', '$9.00', 9.0000, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(141, 46, '9.75% CA Sales Tax:', '$9.75', 9.7500, 'ot_tax', 3);
INSERT INTO `orders_total` VALUES(142, 46, 'Total:', '<b>$118.75</b>', 118.7500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(143, 47, 'Sub-Total:', '$180.00', 180.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(144, 47, 'Table Rate (Best Way):', '$13.45', 13.4500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(145, 47, 'Total:', '<b>$193.45</b>', 193.4500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(146, 48, 'Sub-Total:', '$80.00', 80.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(147, 48, 'Table Rate (Best Way):', '$9.00', 9.0000, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(148, 48, 'Total:', '<b>$89.00</b>', 89.0000, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(149, 49, 'Sub-Total:', '$389.00', 389.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(150, 49, 'Table Rate (Best Way):', '$13.45', 13.4500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(151, 49, 'Total:', '<b>$402.45</b>', 402.4500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(152, 50, 'Sub-Total:', '$345.00', 345.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(153, 50, 'Table Rate (Best Way):', '$13.45', 13.4500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(154, 50, 'Discount Coupon 5OFF applied:', '-$17.25', -17.2500, 'ot_discount_coupon', 4);
INSERT INTO `orders_total` VALUES(155, 50, 'Total:', '<b>$341.20</b>', 341.2000, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(156, 51, 'Sub-Total:', '$130.00', 130.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(157, 51, 'Table Rate (Best Way):', '$13.45', 13.4500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(158, 51, 'Total:', '<b>$143.45</b>', 143.4500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(159, 52, 'Sub-Total:', '$185.00', 185.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(160, 52, 'Table Rate (Best Way):', '$13.45', 13.4500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(161, 52, 'Total:', '<b>$198.45</b>', 198.4500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(162, 53, 'Sub-Total:', '$87.00', 87.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(163, 53, 'Table Rate (Best Way):', '$9.00', 9.0000, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(164, 53, 'Total:', '<b>$96.00</b>', 96.0000, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(165, 54, 'Sub-Total:', '$135.00', 135.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(166, 54, 'Table Rate (Best Way):', '$13.45', 13.4500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(167, 54, 'Total:', '<b>$148.45</b>', 148.4500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(168, 55, 'Sub-Total:', '$85.00', 85.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(169, 55, 'Table Rate (Best Way):', '$9.00', 9.0000, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(170, 55, 'Total:', '<b>$94.00</b>', 94.0000, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(171, 56, 'Sub-Total:', '$100.00', 100.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(172, 56, 'United States Postal Service (Priority Mail: Estimated 1 - 3 Days):', '$7.80', 7.8000, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(173, 56, '9.75% CA Sales Tax:', '$5.85', 5.8500, 'ot_tax', 3);
INSERT INTO `orders_total` VALUES(174, 56, 'Discount Coupon 40OFF applied:', '-$40.00', -40.0000, 'ot_discount_coupon', 4);
INSERT INTO `orders_total` VALUES(175, 56, 'Total:', '<b>$73.65</b>', 73.6500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(234, 72, 'United States Postal Service<br> (Priority Mail reg International Small Flat Rate Box**<br>---Approx. delivery time 6 - 10 business days<br>---Insured for $85):', '$13.73', 13.7300, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(239, 74, 'Sub-Total:', '$56.25', 56.2500, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(238, 73, 'Total:', '<b>$54.20</b>', 54.2000, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(236, 73, 'Sub-Total:', '$40.00', 40.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(237, 73, 'United States Postal Service<br> (Priority Mail reg International Small Flat Rate Box**<br>---Approx. delivery time 6 - 10 business days):', '$14.20', 14.2000, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(213, 66, 'Total:', '<b>$136.95</b>', 136.9500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(240, 74, 'United States Postal Service<br> (Priority Mail reg International Small Flat Rate Box**<br>---Approx. delivery time 6 - 10 business days<br>---Insured for $56.25):', '$15.73', 15.7300, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(233, 72, 'Sub-Total:', '$85.00', 85.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(204, 64, 'Sub-Total:', '$208.00', 208.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(205, 64, 'Table Rate (Best Way):', '$12.95', 12.9500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(206, 64, '9.75% CA Sales Tax:', '$20.28', 20.2800, 'ot_tax', 3);
INSERT INTO `orders_total` VALUES(207, 64, 'Total:', '<b>$241.23</b>', 241.2300, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(208, 65, 'Sub-Total:', '$225.00', 225.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(209, 65, 'Table Rate (Best Way):', '$12.95', 12.9500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(210, 65, 'Total:', '<b>$237.95</b>', 237.9500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(211, 66, 'Sub-Total:', '$155.00', 155.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(212, 66, 'Table Rate (Best Way):', '$12.95', 12.9500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(241, 74, 'Total:', '<b>$71.98</b>', 71.9800, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(235, 72, 'Total:', '<b>$98.73</b>', 98.7300, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(324, 99, 'Sub-Total:', '$35.00', 35.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(307, 94, 'Sub-Total:', '$150.00', 150.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(308, 94, 'Zone Rates (Shipping to US : 0.0625 lb(s)):', '$8.95', 8.9500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(309, 94, 'Total:', '<b>$158.95</b>', 158.9500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(310, 95, 'Sub-Total:', '$90.00', 90.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(311, 95, 'Zone Rates (Shipping to US : 0.0625 lb(s)):', '$8.95', 8.9500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(312, 95, 'Total:', '<b>$98.95</b>', 98.9500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(323, 98, 'Total:', '<b>$151.00</b>', 151.0000, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(321, 98, 'Sub-Total:', '$135.00', 135.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(306, 93, 'Total:', '<b>$969.50</b>', 969.5000, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(298, 91, 'Sub-Total:', '$275.00', 275.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(299, 91, 'Zone Rates (Shipping to US : 0.0625 lb(s)):', '$8.95', 8.9500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(300, 91, 'Total:', '<b>$283.95</b>', 283.9500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(301, 92, 'Sub-Total:', '$25.00', 25.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(302, 92, 'Zone Rates (Shipping to US : 0.0625 lb(s)):', '$8.95', 8.9500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(303, 92, 'Total:', '<b>$33.95</b>', 33.9500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(304, 93, 'Sub-Total:', '$955.00', 955.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(305, 93, 'Zone Rates (Shipping to CA : 0.0625 lb(s)):', '$14.50', 14.5000, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(297, 90, 'Total:', '<b>$124.75</b>', 124.7500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(294, 89, 'Total:', '<b>$20.00</b>', 20.0000, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(295, 90, 'Sub-Total:', '$108.75', 108.7500, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(296, 90, 'Zone Rates (Shipping to AU : 0.0625 lb(s)):', '$16.00', 16.0000, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(293, 89, 'Sub-Total:', '$20.00', 20.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(347, 106, 'Sub-Total:', '$100.00', 100.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(348, 106, 'Zone Rates (Shipping to US : 0.0625 lb(s)):', '$8.95', 8.9500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(345, 105, 'Zone Rates (Shipping to US : 0.2625 lb(s)):', '$8.95', 8.9500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(346, 105, 'Total:', '<b>$108.95</b>', 108.9500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(344, 105, 'Sub-Total:', '$100.00', 100.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(326, 99, 'Total:', '<b>$43.95</b>', 43.9500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(325, 99, 'Zone Rates (Shipping to US : 0.0625 lb(s)):', '$8.95', 8.9500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(322, 98, 'Zone Rates (Shipping to NL : 0.0625 lb(s)):', '$16.00', 16.0000, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(282, 86, 'Sub-Total:', '$40.00', 40.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(283, 86, 'Zone Rates (Shipping to US : 0.0625 lb(s)):', '$8.95', 8.9500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(284, 86, '9.75% CA Sales Tax:', '$3.90', 3.9000, 'ot_tax', 3);
INSERT INTO `orders_total` VALUES(285, 86, 'Total:', '<b>$52.85</b>', 52.8500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(359, 109, 'Total:', '<b>$217.00</b>', 217.0000, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(349, 106, '9.75% CA Sales Tax:', '$9.75', 9.7500, 'ot_tax', 3);
INSERT INTO `orders_total` VALUES(350, 106, 'Total:', '<b>$118.70</b>', 118.7000, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(351, 107, 'Sub-Total:', '$365.00', 365.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(352, 107, 'Zone Rates (Shipping to GB : 0.3125 lb(s)):', '$18.50', 18.5000, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(353, 107, 'Total:', '<b>$383.50</b>', 383.5000, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(354, 108, 'Sub-Total:', '$75.00', 75.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(355, 108, 'Zone Rates (Shipping to US : 0.3625 lb(s)):', '$8.95', 8.9500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(356, 108, 'Total:', '<b>$83.95</b>', 83.9500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(357, 109, 'Sub-Total:', '$200.00', 200.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(358, 109, 'Zone Rates (Shipping to BR : 0.0625 lb(s)):', '$17.00', 17.0000, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(360, 110, 'Sub-Total:', '$83.00', 83.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(361, 110, 'Zone Rates (Shipping to US : 0.3025 lb(s)):', '$8.95', 8.9500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(362, 110, 'Total:', '<b>$91.95</b>', 91.9500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(363, 111, 'Sub-Total:', '$55.00', 55.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(364, 111, 'Zone Rates (Shipping to US : 0.1825 lb(s)):', '$8.95', 8.9500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(365, 111, 'Total:', '<b>$63.95</b>', 63.9500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(366, 112, 'Sub-Total:', '$65.00', 65.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(367, 112, 'Zone Rates (Shipping to US : 0.1825 lb(s)):', '$8.95', 8.9500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(368, 112, 'Total:', '<b>$73.95</b>', 73.9500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(369, 113, 'Sub-Total:', '$35.00', 35.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(370, 113, 'Zone Rates (Shipping to US : 0.1625 lb(s)):', '$8.95', 8.9500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(371, 113, '9.75% CA Sales Tax:', '$3.41', 3.4125, 'ot_tax', 3);
INSERT INTO `orders_total` VALUES(372, 113, 'Total:', '<b>$47.36</b>', 47.3625, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(373, 114, 'Sub-Total:', '$55.00', 55.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(374, 114, 'Zone Rates (Shipping to US : 0.1825 lb(s)):', '$8.95', 8.9500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(375, 114, 'Total:', '<b>$63.95</b>', 63.9500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(376, 115, 'Sub-Total:', '$175.00', 175.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(377, 115, 'Zone Rates (Shipping to US : 0.6025 lb(s)):', '$8.95', 8.9500, 'ot_shipping', 2);
INSERT INTO `orders_total` VALUES(378, 115, 'Total:', '<b>$183.95</b>', 183.9500, 'ot_total', 5);
INSERT INTO `orders_total` VALUES(580, 2000, 'Sub-Total:', '$11.00', 11.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(579, 28, 'Total:', '<strong>$66.00</strong>', 66.0000, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(578, 28, 'Pickup Rate (Customer Pickup):', '$0.00', 0.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(441, 18, 'Sub-Total:', '$165.00', 165.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(442, 18, 'Flat Rate (Best Way):', '$5.00', 5.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(443, 18, 'Total:', '<strong>$170.00</strong>', 170.0000, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(467, 24, 'Total:', '<strong>$280.67</strong>', 280.6675, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(466, 24, 'Table Rate (Best Way):', '$8.50', 8.5000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(465, 24, 'Sales Tax:', '$8.17', 8.1675, 'ot_tax', 2);
INSERT INTO `orders_total` VALUES(464, 24, 'Sub-Total:', '$264.00', 264.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(468, 25, 'Sub-Total:', '$264.00', 264.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(469, 25, 'Sales Tax:', '$8.17', 8.1675, 'ot_tax', 2);
INSERT INTO `orders_total` VALUES(470, 25, 'United States Postal Service&nbsp;6 lbs, 0 oz (Media Mail<sup>&reg;</sup><br>---Approx. delivery time 3 Days):', '$7.97', 7.9700, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(471, 25, 'Total:', '<strong>$280.14</strong>', 280.1375, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(472, 26, 'Sub-Total:', '$99.00', 99.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(473, 26, 'Sales Tax:', '$8.17', 8.1675, 'ot_tax', 2);
INSERT INTO `orders_total` VALUES(474, 26, 'Table Rate (Best Way):', '$5.00', 5.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(475, 26, 'Total:', '<strong>$112.17</strong>', 112.1675, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(476, 27, 'Sub-Total:', '$66.00', 66.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(477, 27, 'Table Rate (Best Way):', '$5.00', 5.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(478, 27, 'Total:', '<strong>$71.00</strong>', 71.0000, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(479, 28, 'Sub-Total:', '$66.00', 66.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(480, 28, 'Federal Express (1 x 2lbs) (Standard Overnight (by 3PM, later for rural)):', '$22.39', 22.3900, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(481, 28, 'Total:', '<strong>$88.39</strong>', 88.3900, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(482, 29, 'Sub-Total:', '$66.00', 66.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(483, 29, 'Federal Express (1 x 2lbs) (Home Delivery (1 days)):', '$8.71', 8.7100, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(484, 29, 'Total:', '<strong>$74.71</strong>', 74.7100, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(577, 28, 'Sub-Total:', '$66.00', 66.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(534, 15, 'Sub-Total:', '$66.00', 66.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(535, 15, 'Flat Rate (Best Way):', '$5.00', 5.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(536, 15, 'Total:', '<strong>$71.00</strong>', 71.0000, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(537, 16, 'Sub-Total:', '$165.00', 165.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(538, 16, 'Sales Tax:', '$8.17', 8.1675, 'ot_tax', 2);
INSERT INTO `orders_total` VALUES(539, 16, 'Pickup Rate (Customer Pickup):', '$0.00', 0.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(540, 16, 'Total:', '<strong>$173.17</strong>', 173.1675, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(541, 17, 'Sub-Total:', '$150.00', 150.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(542, 17, 'Sales Tax:', '$12.38', 12.3750, 'ot_tax', 2);
INSERT INTO `orders_total` VALUES(543, 17, 'Pickup Rate (Customer Pickup):', '$0.00', 0.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(544, 17, 'Total:', '<strong>$162.38</strong>', 162.3750, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(545, 18, 'Sub-Total:', '$99.00', 99.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(546, 18, 'Sales Tax:', '$8.17', 8.1675, 'ot_tax', 2);
INSERT INTO `orders_total` VALUES(547, 18, 'Pickup Rate (Customer Pickup):', '$0.00', 0.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(548, 18, 'Total:', '<strong>$107.17</strong>', 107.1675, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(549, 19, 'Sub-Total:', '$2.40', 2.4000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(550, 19, 'Pickup Rate (Customer Pickup):', '$0.00', 0.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(551, 19, 'Total:', '<strong>$2.40</strong>', 2.4000, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(552, 20, 'Sub-Total:', '$132.00', 132.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(553, 20, 'Flat Rate (Best Way):', '$5.00', 5.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(554, 20, 'Total:', '<strong>$137.00</strong>', 137.0000, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(555, 21, 'Sub-Total:', '$66.00', 66.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(556, 21, 'Pickup Rate (Customer Pickup):', '$0.00', 0.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(557, 21, 'Total:', '<strong>$66.00</strong>', 66.0000, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(558, 22, 'Sub-Total:', '$66.00', 66.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(559, 22, 'Pickup Rate (Customer Pickup):', '$0.00', 0.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(560, 22, 'Total:', '<strong>$66.00</strong>', 66.0000, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(561, 23, 'Sub-Total:', '$11.00', 11.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(562, 23, 'Worldwide Delivery (Shipping to United States: 1 kg(s)):', '$8.50', 8.5000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(563, 23, 'Total:', '<strong>$19.50</strong>', 19.5000, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(564, 24, 'Sub-Total:', '$66.00', 66.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(565, 24, 'Flat Rate (Best Way):', '$5.00', 5.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(566, 24, 'Total:', '<strong>$71.00</strong>', 71.0000, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(567, 25, 'Sub-Total:', '$66.00', 66.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(568, 25, 'Flat Rate (Best Way):', '$5.00', 5.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(569, 25, 'Total:', '<strong>$71.00</strong>', 71.0000, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(570, 26, 'Sub-Total:', '$175.00', 175.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(571, 26, 'Sales Tax:', '$12.38', 12.3750, 'ot_tax', 2);
INSERT INTO `orders_total` VALUES(572, 26, 'United States Postal Service&nbsp;4 lbs, 0 oz (Media Mail<sup>&reg;</sup><br>---Approx. delivery time 3 Days):', '$7.13', 7.1300, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(573, 26, 'Total:', '<strong>$194.51</strong>', 194.5050, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(574, 27, 'Sub-Total:', '$99.00', 99.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(575, 27, 'Free Shipping (USA Only) (For orders of $98.00 or more with a maximum package weight of 10  lbs ):', '$0.00', 0.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(576, 27, 'Total:', '<strong>$99.00</strong>', 99.0000, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(581, 2000, 'Pickup Rate (Customer Pickup):', '$0.00', 0.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(582, 2000, 'Total:', '<strong>$11.00</strong>', 11.0000, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(583, 2001, 'Sub-Total:', '$99.00', 99.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(584, 2001, 'Sales Tax:', '$8.17', 8.1675, 'ot_tax', 2);
INSERT INTO `orders_total` VALUES(585, 2001, 'Pickup Rate (Customer Pickup):', '$0.00', 0.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(586, 2001, 'Total:', '<strong>$107.17</strong>', 107.1675, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(587, 2002, 'Sub-Total:', '$150.00', 150.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(588, 2002, 'Sales Tax:', '$12.38', 12.3750, 'ot_tax', 2);
INSERT INTO `orders_total` VALUES(589, 2002, 'Pickup Rate (Customer Pickup):', '$0.00', 0.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(590, 2002, 'Total:', '<strong>$162.38</strong>', 162.3750, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(591, 2003, 'Sub-Total:', '$99.00', 99.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(592, 2003, 'Sales Tax:', '$8.17', 8.1675, 'ot_tax', 2);
INSERT INTO `orders_total` VALUES(593, 2003, 'Pickup Rate (Customer Pickup):', '$0.00', 0.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(594, 2003, 'Total:', '<strong>$107.17</strong>', 107.1675, 'ot_total', 900);
INSERT INTO `orders_total` VALUES(595, 2004, 'Sub-Total:', '$66.00', 66.0000, 'ot_subtotal', 1);
INSERT INTO `orders_total` VALUES(596, 2004, 'Pickup Rate (Customer Pickup):', '$0.00', 0.0000, 'ot_shipping', 3);
INSERT INTO `orders_total` VALUES(597, 2004, 'Total:', '<strong>$66.00</strong>', 66.0000, 'ot_total', 900);

CREATE TABLE IF NOT EXISTS `packaging` (
  `package_id` int(11) NOT NULL AUTO_INCREMENT,
  `package_name` varchar(64) NOT NULL DEFAULT '',
  `package_description` varchar(255) NOT NULL DEFAULT '',
  `package_length` decimal(6,2) NOT NULL DEFAULT '5.00',
  `package_width` decimal(6,2) NOT NULL DEFAULT '5.00',
  `package_height` decimal(6,2) NOT NULL DEFAULT '5.00',
  `package_empty_weight` decimal(6,2) NOT NULL DEFAULT '0.00',
  `package_max_weight` decimal(6,2) NOT NULL DEFAULT '50.00',
  `package_cost` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`package_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `phpids_intrusions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `value` text NOT NULL,
  `page` varchar(255) NOT NULL,
  `tags` varchar(128) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `impact` int(11) NOT NULL,
  `origin` varchar(15) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='PHPIDS Log' AUTO_INCREMENT=45 ;

INSERT INTO `phpids_intrusions` VALUES(1, 'REQUEST.error', '*** This is a valid coupon code. HOWEVER: No price reduction can be applied, please see the coupon restrictions that were included with the offer. **', '/checkout_payment.php?payment_error=ot_coupon&error=%2A%2A%2A+This+is+a+valid+coupon+code.+HOWEVER%3A+No+price+reduction+can+be+applied%2C+please+see+the+coupon+restrictions+that+were+included+with+the+offer.+%2A%2A', 'xss, csrf, id, rfe, lfi', '184.57.87.71', 7, '50.28.26.56', '2012-07-06 07:07:40');
INSERT INTO `phpids_intrusions` VALUES(2, 'GET.error', '*** This is a valid coupon code. HOWEVER: No price reduction can be applied, please see the coupon restrictions that were included with the offer. **', '/checkout_payment.php?payment_error=ot_coupon&error=%2A%2A%2A+This+is+a+valid+coupon+code.+HOWEVER%3A+No+price+reduction+can+be+applied%2C+please+see+the+coupon+restrictions+that+were+included+with+the+offer.+%2A%2A', 'xss, csrf, id, rfe, lfi', '184.57.87.71', 7, '50.28.26.56', '2012-07-06 07:07:40');
INSERT INTO `phpids_intrusions` VALUES(3, 'REQUEST.enquiry', '<p>\r\n	&lt;p&gt; Sarah,&lt;/p&gt; &lt;p&gt; &amp;nbsp;&lt;/p&gt; &lt;p&gt; I sent a general inquiry and had no problems with the contact form.&amp;nbsp; This is a product inquiry.&lt;/p&gt; &lt;p&gt; &amp;nbsp;&lt;/p&gt; &lt;p&gt; Mary&lt;/p&gt;</p>\r\n', '/contact_us.php?action=send', 'xss, csrf, id, rfe', '187.184.109.123', 4, '50.28.26.56', '2012-08-02 17:44:07');
INSERT INTO `phpids_intrusions` VALUES(4, 'REQUEST.enquiry', '<p>\r\n	&lt;p&gt; Sarah,&lt;/p&gt; &lt;p&gt; &amp;nbsp;&lt;/p&gt; &lt;p&gt; I sent a general inquiry and had no problems with the contact form.&amp;nbsp; This is a product inquiry.&lt;/p&gt; &lt;p&gt; &amp;nbsp;&lt;/p&gt; &lt;p&gt; Mary&lt;/p&gt;</p>\r\n', '/contact_us.php?action=send', 'xss, csrf, id, rfe', '187.184.109.123', 4, '50.28.26.56', '2012-08-02 17:47:38');
INSERT INTO `phpids_intrusions` VALUES(5, 'REQUEST.enquiry', '<p>\r\n	&lt;p&gt; Sarah,&lt;/p&gt; &lt;p&gt; &amp;nbsp;&lt;/p&gt; &lt;p&gt; I sent a general inquiry and had no problems with the contact form.&amp;nbsp; This is a product inquiry.&lt;/p&gt; &lt;p&gt; &amp;nbsp;&lt;/p&gt; &lt;p&gt; Mary&lt;/p&gt;</p>\r\n', '/contact_us.php?action=send', 'xss, csrf, id, rfe', '187.184.109.123', 4, '50.28.26.56', '2012-08-02 17:48:12');
INSERT INTO `phpids_intrusions` VALUES(6, 'REQUEST.enquiry', '<p>\r\n	&lt;p&gt; Sarah,&lt;/p&gt; &lt;p&gt; &amp;nbsp;&lt;/p&gt; &lt;p&gt; I sent a general inquiry and had no problems with the contact form.&amp;nbsp; This is a product inquiry.&lt;/p&gt; &lt;p&gt; &amp;nbsp;&lt;/p&gt; &lt;p&gt; Mary&lt;/p&gt;</p>\r\n', '/contact_us.php?action=send', 'xss, csrf, id, rfe', '187.184.109.123', 4, '50.28.26.56', '2012-08-02 17:48:42');
INSERT INTO `phpids_intrusions` VALUES(7, 'REQUEST.keywords', '<scri', '/quickfind.php?&keywords=%3Cscri', 'xss', '187.184.109.123', 4, '50.28.26.56', '2012-08-02 18:00:00');
INSERT INTO `phpids_intrusions` VALUES(8, 'GET.keywords', '<scri', '/quickfind.php?&keywords=%3Cscri', 'xss', '187.184.109.123', 4, '50.28.26.56', '2012-08-02 18:00:00');
INSERT INTO `phpids_intrusions` VALUES(9, 'REQUEST.keywords', '<scrip', '/quickfind.php?&keywords=%3Cscrip', 'xss', '187.184.109.123', 4, '50.28.26.56', '2012-08-02 18:00:01');
INSERT INTO `phpids_intrusions` VALUES(10, 'GET.keywords', '<scrip', '/quickfind.php?&keywords=%3Cscrip', 'xss', '187.184.109.123', 4, '50.28.26.56', '2012-08-02 18:00:01');
INSERT INTO `phpids_intrusions` VALUES(11, 'REQUEST.keywords', '<script', '/quickfind.php?&keywords=%3Cscript', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:01');
INSERT INTO `phpids_intrusions` VALUES(12, 'GET.keywords', '<script', '/quickfind.php?&keywords=%3Cscript', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:01');
INSERT INTO `phpids_intrusions` VALUES(13, 'REQUEST.keywords', '<script>', '/quickfind.php?&keywords=%3Cscript%3E', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:03');
INSERT INTO `phpids_intrusions` VALUES(14, 'GET.keywords', '<script>', '/quickfind.php?&keywords=%3Cscript%3E', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:03');
INSERT INTO `phpids_intrusions` VALUES(15, 'REQUEST.keywords', '<script>', '/quickfind.php?&keywords=%3Cscript%3E', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:03');
INSERT INTO `phpids_intrusions` VALUES(16, 'GET.keywords', '<script>', '/quickfind.php?&keywords=%3Cscript%3E', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:03');
INSERT INTO `phpids_intrusions` VALUES(17, 'REQUEST.keywords', '<script><', '/quickfind.php?&keywords=%3Cscript%3E%3C', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:04');
INSERT INTO `phpids_intrusions` VALUES(18, 'GET.keywords', '<script><', '/quickfind.php?&keywords=%3Cscript%3E%3C', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:04');
INSERT INTO `phpids_intrusions` VALUES(19, 'REQUEST.keywords', '<script></s', '/quickfind.php?&keywords=%3Cscript%3E%3C/s', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:05');
INSERT INTO `phpids_intrusions` VALUES(20, 'GET.keywords', '<script></s', '/quickfind.php?&keywords=%3Cscript%3E%3C/s', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:05');
INSERT INTO `phpids_intrusions` VALUES(21, 'REQUEST.keywords', '<script></', '/quickfind.php?&keywords=%3Cscript%3E%3C/', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:05');
INSERT INTO `phpids_intrusions` VALUES(22, 'GET.keywords', '<script></', '/quickfind.php?&keywords=%3Cscript%3E%3C/', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:05');
INSERT INTO `phpids_intrusions` VALUES(23, 'REQUEST.keywords', '<script></sc', '/quickfind.php?&keywords=%3Cscript%3E%3C/sc', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:05');
INSERT INTO `phpids_intrusions` VALUES(24, 'GET.keywords', '<script></sc', '/quickfind.php?&keywords=%3Cscript%3E%3C/sc', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:05');
INSERT INTO `phpids_intrusions` VALUES(25, 'REQUEST.keywords', '<script></scr', '/quickfind.php?&keywords=%3Cscript%3E%3C/scr', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:05');
INSERT INTO `phpids_intrusions` VALUES(26, 'GET.keywords', '<script></scr', '/quickfind.php?&keywords=%3Cscript%3E%3C/scr', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:05');
INSERT INTO `phpids_intrusions` VALUES(27, 'REQUEST.keywords', '<script><', '/quickfind.php?&keywords=%3Cscript%3E%3C', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:05');
INSERT INTO `phpids_intrusions` VALUES(28, 'GET.keywords', '<script><', '/quickfind.php?&keywords=%3Cscript%3E%3C', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:05');
INSERT INTO `phpids_intrusions` VALUES(29, 'REQUEST.keywords', '<script></scri', '/quickfind.php?&keywords=%3Cscript%3E%3C/scri', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:05');
INSERT INTO `phpids_intrusions` VALUES(30, 'GET.keywords', '<script></scri', '/quickfind.php?&keywords=%3Cscript%3E%3C/scri', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:05');
INSERT INTO `phpids_intrusions` VALUES(31, 'REQUEST.keywords', '<script></scrip', '/quickfind.php?&keywords=%3Cscript%3E%3C/scrip', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:05');
INSERT INTO `phpids_intrusions` VALUES(32, 'GET.keywords', '<script></scrip', '/quickfind.php?&keywords=%3Cscript%3E%3C/scrip', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:05');
INSERT INTO `phpids_intrusions` VALUES(33, 'REQUEST.keywords', '<script></script', '/quickfind.php?&keywords=%3Cscript%3E%3C/script', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:05');
INSERT INTO `phpids_intrusions` VALUES(34, 'GET.keywords', '<script></script', '/quickfind.php?&keywords=%3Cscript%3E%3C/script', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:05');
INSERT INTO `phpids_intrusions` VALUES(35, 'REQUEST.keywords', '<script></script>', '/quickfind.php?&keywords=%3Cscript%3E%3C/script%3E', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:05');
INSERT INTO `phpids_intrusions` VALUES(36, 'GET.keywords', '<script></script>', '/quickfind.php?&keywords=%3Cscript%3E%3C/script%3E', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:05');
INSERT INTO `phpids_intrusions` VALUES(37, 'REQUEST.keywords', '<script></script>', '/quickfind.php?&keywords=%3Cscript%3E%3C/script%3E', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:06');
INSERT INTO `phpids_intrusions` VALUES(38, 'GET.keywords', '<script></script>', '/quickfind.php?&keywords=%3Cscript%3E%3C/script%3E', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:00:06');
INSERT INTO `phpids_intrusions` VALUES(39, 'REQUEST.email_address', '<script>', '/login.php?action=process', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:01:52');
INSERT INTO `phpids_intrusions` VALUES(40, 'POST.email_address', '<script>', '/login.php?action=process', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-02 18:01:52');
INSERT INTO `phpids_intrusions` VALUES(41, 'REQUEST.email_address', '<script>', '/login.php?action=process', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-14 00:54:20');
INSERT INTO `phpids_intrusions` VALUES(42, 'POST.email_address', '<script>', '/login.php?action=process', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-14 00:54:20');
INSERT INTO `phpids_intrusions` VALUES(43, 'REQUEST.email_address', '<script>', '/login.php?action=process', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-14 00:56:09');
INSERT INTO `phpids_intrusions` VALUES(44, 'POST.email_address', '<script>', '/login.php?action=process', 'xss, csrf, id, rfe, lfi', '187.184.109.123', 8, '50.28.26.56', '2012-08-14 00:56:09');

CREATE TABLE IF NOT EXISTS `post` (
  `postid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `threadid` int(10) unsigned NOT NULL DEFAULT '0',
  `forumid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `dateline` int(11) NOT NULL DEFAULT '0',
  `pagetext` mediumtext,
  `public` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`postid`),
  KEY `idxdisp` (`threadid`,`dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

CREATE TABLE IF NOT EXISTS `products` (
  `products_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_quantity` int(4) NOT NULL DEFAULT '0',
  `products_model` varchar(64) DEFAULT NULL,
  `products_mpn` varchar(64) NOT NULL,
  `products_gtin` varchar(64) NOT NULL,
  `products_image` varchar(64) DEFAULT NULL,
  `products_image_med` varchar(64) DEFAULT NULL,
  `products_image_lrg` varchar(64) DEFAULT NULL,
  `products_image_sm_1` varchar(64) DEFAULT NULL,
  `products_image_xl_1` varchar(64) DEFAULT NULL,
  `products_image_sm_2` varchar(64) DEFAULT NULL,
  `products_image_xl_2` varchar(64) DEFAULT NULL,
  `products_image_sm_3` varchar(64) DEFAULT NULL,
  `products_image_xl_3` varchar(64) DEFAULT NULL,
  `products_image_sm_4` varchar(64) DEFAULT NULL,
  `products_image_xl_4` varchar(64) DEFAULT NULL,
  `products_image_sm_5` varchar(64) DEFAULT NULL,
  `products_image_xl_5` varchar(64) DEFAULT NULL,
  `products_image_sm_6` varchar(64) DEFAULT NULL,
  `products_image_xl_6` varchar(64) DEFAULT NULL,
  `products_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `products_last_modified` datetime DEFAULT NULL,
  `products_date_available` datetime DEFAULT NULL,
  `products_featured_until` text,
  `products_weight` decimal(5,2) NOT NULL DEFAULT '0.00',
  `products_status` tinyint(1) NOT NULL DEFAULT '0',
  `products_featured` text,
  `products_tax_class_id` int(11) NOT NULL DEFAULT '0',
  `manufacturers_id` int(11) DEFAULT NULL,
  `products_ordered` int(11) NOT NULL DEFAULT '0',
  `products_length` decimal(6,2) NOT NULL DEFAULT '12.00',
  `products_width` decimal(6,2) NOT NULL DEFAULT '12.00',
  `products_height` decimal(6,2) NOT NULL DEFAULT '12.00',
  `products_ready_to_ship` int(1) NOT NULL DEFAULT '0',
  `vendors_id` int(11) DEFAULT '1',
  `vendors_product_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `vendors_prod_id` varchar(24) NOT NULL DEFAULT '',
  `vendors_prod_comments` text,
  `products_price1` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_price2` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_price3` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_price4` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_price5` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_price6` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_price7` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_price8` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_price1_qty` int(11) NOT NULL DEFAULT '0',
  `products_price2_qty` int(11) NOT NULL DEFAULT '0',
  `products_price3_qty` int(11) NOT NULL DEFAULT '0',
  `products_price4_qty` int(11) NOT NULL DEFAULT '0',
  `products_price5_qty` int(11) NOT NULL DEFAULT '0',
  `products_price6_qty` int(11) NOT NULL DEFAULT '0',
  `products_price7_qty` int(11) NOT NULL DEFAULT '0',
  `products_price8_qty` int(11) NOT NULL DEFAULT '0',
  `products_qty_blocks` int(11) NOT NULL DEFAULT '1',
  `qbi_imported` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `map_price` decimal(10,2) NOT NULL,
  `msrp_price` decimal(10,2) NOT NULL,
  `pSortOrder` int(11) NOT NULL,
  `products_special` tinyint(1) unsigned zerofill NOT NULL DEFAULT '0',
  `product_image_2` varchar(64) DEFAULT NULL,
  `product_image_3` varchar(64) DEFAULT NULL,
  `product_image_4` varchar(64) DEFAULT NULL,
  `product_image_5` varchar(64) DEFAULT NULL,
  `product_image_6` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`products_id`),
  KEY `idx_products_date_added` (`products_date_added`),
  KEY `qbi_imported` (`qbi_imported`),
  KEY `manufacturers_id` (`manufacturers_id`),
  KEY `products_model` (`products_model`),
  KEY `products_price` (`products_price`),
  KEY `products_date_available` (`products_date_available`),
  KEY `manufacturers_id_2` (`manufacturers_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=521 ;

INSERT INTO `products` VALUES(32, 24, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55.0000, '2009-12-09 17:04:40', '2009-12-09 17:59:27', NULL, NULL, 0.00, 0, NULL, 0, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(38, 0, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 33.0000, '2009-12-10 11:46:07', NULL, NULL, NULL, 0.00, 0, NULL, 0, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(170, 1, '65', '', '', 'products/top/DSC_0053_72cs_cat.jpg', 'products/top/DSC_0053_72cs_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 135.0000, '2010-02-10 18:25:53', '2011-08-03 04:32:51', NULL, NULL, 0.22, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(174, 1, '41', '', '', 'products/necklaces/all/41ac_cat.jpg', 'products/necklaces/all/41ac_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 145.0000, '2010-02-10 18:25:53', '2011-06-02 01:19:05', NULL, NULL, 0.00, 0, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(176, 0, '80', '', '', 'products/necklaces/all/DSC_0082_72cs_cat.jpg', 'products/necklaces/all/DSC_0082_72cs_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 110.0000, '2010-02-10 18:25:53', '2011-07-23 22:59:04', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(179, 2, '95', '', '', 'products/necklaces/all/DSC_0094_72c_cat.jpg', 'products/necklaces/all/DSC_0094_72c_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 75.0000, '2010-02-10 18:25:53', '2011-05-14 15:31:45', NULL, NULL, 0.00, 0, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(304, 3, '217', '', '', 'products/top/DSC_7164_cat.jpg', 'products/top/DSC_7164_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 40.0000, '2010-11-18 03:32:33', '2011-07-25 02:24:52', NULL, NULL, 0.10, 1, NULL, 1, 0, 2, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(280, 7, '222', '', '', 'products/top/DSC_7143_cat.jpg', 'products/top/DSC_7143_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 100.0000, '2010-09-29 02:16:41', '2011-07-25 01:31:12', NULL, NULL, 0.13, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(281, 10, '163', '', '', 'products/top/DSC_7146_cat.jpg', 'products/top/DSC_7146_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 85.0000, '2010-09-29 02:16:41', '2011-07-25 01:34:45', NULL, NULL, 0.13, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(187, 2, '103', '', '', 'products/top/103cw_cat.jpg', 'products/top/103cw_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 40.0000, '2010-02-10 18:25:53', '2011-07-25 01:41:06', NULL, NULL, 0.12, 1, NULL, 1, 0, 2, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(65, 1, '83', '', '', 'products/necklaces/malas/DSC_0084_72cs_cat.jpg', 'products/top/DSC_0084_72cs_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 130.0000, '2009-12-14 16:28:58', '2011-07-25 02:01:32', NULL, NULL, 0.30, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(313, 12, 'test', '', '', 'products/necklaces/silver/black-onyx-gem-stone-necklaces.jpg', 'products/necklaces/silver/black-onyx-gem-stone-necklacem.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10.0000, '2011-05-11 10:11:00', NULL, NULL, NULL, 0.00, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(191, 1, '75', '', '', 'products/necklaces/silver/DSC_0074cs_cat.jpg', 'products/top/DSC_0074cs_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 120.0000, '2010-02-10 18:25:53', '2011-07-25 01:48:12', NULL, NULL, 0.22, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(312, 5, '152', '', '', 'products/top/DSC_2506_cat.jpg', 'products/top/DSC_2506_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.0000, '2011-04-20 22:47:59', '2011-07-25 01:46:54', NULL, NULL, 0.12, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(193, 0, '96', '', '', 'products/necklaces/malas/96c_cat.jpg', 'products/top/96c_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 120.0000, '2010-02-10 18:25:53', '2011-07-23 23:00:40', NULL, NULL, 0.00, 0, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(194, 1, '118', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55.0000, '2010-02-10 18:25:53', '2010-02-18 14:15:47', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(195, 1, '62', '', '', 'products/top/DSC_0089cs_cat.jpg', 'products/top/DSC_0089cs_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 120.0000, '2010-02-10 18:25:53', '2011-07-25 02:03:08', NULL, NULL, 0.22, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(197, 1, '132', '', '', 'products/top/132cw_cat.jpg', 'products/top/132cw_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 45.0000, '2010-02-10 18:25:53', '2011-04-26 04:12:39', NULL, NULL, 0.00, 0, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(198, 5, '127', '', '', 'products/top/127cw_cat.jpg', 'products/top/127cw_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.0000, '2010-02-10 18:25:53', '2011-07-25 02:02:06', NULL, NULL, 0.12, 1, NULL, 1, 0, 3, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(199, 1, '130', '', '', 'products/top/130cw_cat.jpg', 'products/top/130cw_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 32.0000, '2010-02-10 18:25:53', '2011-03-23 14:04:00', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(200, 1, '73', '', '', 'products/top/DSC_0072_72cs_cat.jpg', 'products/top/DSC_0072_72cs_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 130.0000, '2010-02-10 18:25:53', '2011-07-25 02:03:35', NULL, NULL, 0.22, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(277, 1, '165', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 85.0000, '2010-09-29 02:16:41', NULL, '0000-00-00 00:00:00', NULL, 0.00, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(202, 0, '138', '', '', 'products/top/138c_cat.jpg', 'products/top/138c_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 100.0000, '2010-02-10 18:25:53', '2011-07-23 22:58:27', NULL, NULL, 0.20, 0, NULL, 1, 0, 2, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(203, 1, '55', '', '', 'products/top/55c_cat.jpg', 'products/top/55c_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 100.0000, '2010-02-10 18:25:53', '2011-07-25 01:25:57', NULL, NULL, 0.30, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(204, 0, '61', '', '', 'products/top/61ac_cat.jpg', 'products/top/61ac_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 85.0000, '2010-02-10 18:25:53', '2010-12-04 14:08:56', NULL, NULL, 0.00, 0, NULL, 1, 0, 2, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(87, 1, '76', '', '', 'products/top/76ac_cat.jpg', 'products/top/76ac_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 120.0000, '2009-12-15 10:48:06', '2011-07-25 02:23:01', NULL, NULL, 0.22, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(218, 1, '59', '', '', 'products/necklaces/all/DSC_0063_72csi_cat2.jpg', 'products/top/59cs_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 150.0000, '2010-02-13 19:23:48', '2011-07-25 01:21:55', NULL, NULL, 0.22, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(107, 1, '114', '', '', 'products/necklaces/malas/114c_cat.jpg', 'products/top/114c_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 170.0000, '2010-01-19 21:00:48', '2011-07-30 15:25:18', NULL, NULL, 0.30, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(108, 1, '47', '', '', 'products/top/47cs_cat.jpg', 'products/top/47cs_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 100.0000, '2010-01-19 21:00:48', '2011-06-01 19:19:49', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(109, 1, '105', '', '', 'products/top/105cw_cat.jpg', 'products/top/105cw_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.0000, '2010-01-19 21:00:48', '2010-11-18 12:02:44', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(110, 0, '39', '', '', 'products/top/39ac_cat.jpg', 'products/top/39ac_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 175.0000, '2010-01-19 21:00:48', '2011-07-23 23:01:20', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(114, 1, '51', '', '', 'products/top/51c_cat.jpg', 'products/top/51c_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 200.0000, '2010-01-19 21:00:48', '2010-11-07 16:54:55', NULL, NULL, 0.00, 0, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(118, 1, '91', '', '', 'products/top/DSC_0085_72cs_cat.jpg', 'products/top/DSC_0085_72cs_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 180.0000, '2010-01-19 21:00:48', '2011-05-04 21:08:25', NULL, NULL, 0.00, 0, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(121, 2, '90', '', '', 'products/necklaces/malas/DSC_0088cs_cat.jpg', 'products/top/DSC_0088cs_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 200.0000, '2010-01-19 21:00:48', '2011-07-25 02:26:01', NULL, NULL, 0.30, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(123, 1, '48', '', '', 'products/top/48c_cat.jpg', 'products/top/48c_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 150.0000, '2010-01-19 21:00:48', '2010-12-07 00:13:38', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(124, 1, '46', '', '', 'products/top/46c_cat.jpg', 'products/top/46c_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 165.0000, '2010-01-19 21:00:48', '2010-10-31 21:39:39', NULL, NULL, 0.00, 0, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(127, 1, '125', '', '', 'products/bracelets/all/125cw_cat.jpg', 'products/top/125cw_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 50.0000, '2010-01-19 21:00:48', '2011-07-25 00:01:28', NULL, NULL, 0.12, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(128, 1, '124', '', '', 'products/top/124c_cat.jpg', 'products/top/124c_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 135.0000, '2010-01-19 21:00:48', '2011-07-25 02:06:01', NULL, NULL, 0.22, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(130, 0, '92', '', '', 'products/necklaces/malas/92c_cat.jpg', 'products/top/92c_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 180.0000, '2010-01-19 21:00:48', '2011-08-13 03:14:28', NULL, NULL, 0.30, 0, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(314, 12, 'test', '', '', 'products/necklaces/silver/black-onyx-gem-stone-necklaces.jpg', 'products/necklaces/silver/black-onyx-gem-stone-necklacem.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10.0000, '2011-05-11 10:11:20', NULL, NULL, NULL, 0.00, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(133, 1, '120', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55.0000, '2010-01-19 21:00:48', '2010-02-13 17:11:14', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(136, 1, '116', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55.0000, '2010-01-19 21:00:48', '2010-02-13 17:03:38', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(166, 0, '102', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 45.0000, '2010-02-10 18:25:53', '2010-02-13 17:00:13', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(278, 1, '212', '', '', 'products/top/DSC_5590_cat.jpg', 'products/top/DSC_5590_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 45.0000, '2010-09-29 02:16:41', '2011-07-25 01:01:38', NULL, NULL, 0.10, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(217, 0, '37', '', '', 'products/necklaces/malas/37ac_cat.jpg', 'products/top/37ac_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 180.0000, '2010-02-10 18:25:53', '2011-07-23 22:58:48', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(219, 1, '139', '', '', 'products/necklaces/all/DSC_0069_72cs_cat2.jpg', 'products/top/DSC_0069_72cs_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 130.0000, '2010-02-13 19:23:48', '2011-05-17 00:54:16', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(206, 1, '141', '', '', 'products/necklaces/malas/141ac_cat.jpg', 'products/top/141ac_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 140.0000, '2010-02-10 18:25:53', '2011-07-30 15:26:37', NULL, NULL, 0.30, 0, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(207, 1, '123', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.0000, '2010-02-10 18:25:53', '2011-04-26 19:35:08', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(208, 1, '122', '', '', 'products/top/DSC_0015_item.jpg', 'products/top/DSC_0015_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.0000, '2010-02-10 18:25:53', '2011-04-26 19:34:32', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(282, 2, '215', '', '', 'products/top/DSC_5644_cat.jpg', 'products/top/DSC_5644_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.0000, '2010-09-29 02:16:41', '2011-07-25 01:36:22', NULL, NULL, 0.10, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(284, 1, '169', '', '', 'products/top/DSC_5650_cat.jpg', 'products/top/DSC_5650_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 30.0000, '2010-09-29 02:16:41', '2011-06-22 15:37:41', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(285, 3, '164', '', '', 'products/bracelets/all/DSC_7109_cat.jpg', 'products/bracelets/all/DSC_7109_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 28.0000, '2010-09-29 02:16:41', '2011-07-25 01:45:30', NULL, NULL, 0.12, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(211, 3, '101', '', '', 'products/top/101cw_cat.jpg', 'products/top/101cw_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 36.0000, '2010-02-10 18:25:53', '2011-07-25 01:03:46', NULL, NULL, 0.12, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(212, 1, '44', '', '', 'products/necklaces/malas/44c_cat.jpg', 'products/top/44c_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 125.0000, '2010-02-10 18:25:53', '2011-07-25 01:25:31', NULL, NULL, 0.30, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(213, 10, '126', '', '', 'products/top/126cw_cat.jpg', 'products/top/126cw_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 28.0000, '2010-02-10 18:25:53', '2011-07-25 02:13:08', NULL, NULL, 0.12, 1, NULL, 1, 0, 3, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(214, 4, '115', '', '', 'products/earrings/all/DSC_0029_item.jpg', 'products/top/DSC_0029_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25.0000, '2010-02-10 18:25:53', '2011-07-25 02:15:47', NULL, NULL, 0.10, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(220, 0, '40', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 200.0000, '2010-05-18 22:54:36', NULL, '0000-00-00 00:00:00', NULL, 0.00, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(221, 1, '147', '', '', 'products/necklaces/all/DSC_2476_cata.jpg', 'products/top/DSC_2476_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 130.0000, '2010-05-18 22:54:36', '2011-07-24 23:01:53', NULL, NULL, 0.22, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(222, 1, '208', '', '', 'products/top/DSC_2459_cat.jpg', 'products/top/DSC_2459item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 215.0000, '2010-05-18 22:54:36', '2010-11-15 23:20:07', NULL, NULL, 0.00, 0, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(223, 4, '160', '', '', 'products/necklaces/all/DSC_2420_cat.jpg', 'products/necklaces/all/DSC_2420_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.0000, '2010-05-18 22:54:36', '2011-07-24 23:41:33', NULL, NULL, 0.10, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(224, 2, '', '', '', 'products/bracelets/all/DSC_7105_cat.jpg', 'products/bracelets/all/DSC_7105_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55.0000, '2010-05-18 22:54:36', '2011-08-02 14:57:12', NULL, NULL, 0.12, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(225, 1, '155', '', '', 'products/collection/angels/DSC_7203_cat2.jpg', 'products/collection/angels/DSC_7203_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 190.0000, '2010-05-18 22:54:36', '2011-08-01 03:35:57', NULL, NULL, 0.16, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(226, 8, '186', '', '', 'products/bracelets/all/DSC_7119_cat.jpg', 'products/bracelets/all/DSC_7119_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55.0000, '2010-05-18 22:54:36', '2011-07-25 01:17:32', NULL, NULL, 0.12, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(227, 3, '156', '', '', 'products/collection/angels/DSC_7205_cat.jpg', 'products/collection/angels/DSC_7205_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 200.0000, '2010-05-18 22:54:36', '2011-08-01 02:41:34', NULL, NULL, 0.16, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(228, 4, '183', '', '', 'products/bracelets/all/DSC_7108_cat.jpg', 'products/bracelets/all/DSC_7108_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55.0000, '2010-05-18 22:54:36', '2011-07-25 01:26:41', NULL, NULL, 0.12, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(229, 4, '157', '', '', 'products/collection/angels/DSC_7200_zoom2ls.jpg', 'products/top/DSC_7200_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 200.0000, '2010-05-18 22:54:36', '2011-08-01 03:27:28', NULL, NULL, 0.16, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(230, 17, '150', '', '', 'products/top/DSC_0820-20_cat.jpg', 'products/collection/angels/DSC_0820-20_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 65.0000, '2010-05-18 22:54:36', '2011-07-25 01:59:50', NULL, NULL, 0.12, 1, NULL, 1, 0, 4, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(231, 4, '158', '', '', 'products/collection/angels/DSC_7207_cat.jpg', 'products/collection/angels/DSC_7207_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 200.0000, '2010-05-18 22:54:36', '2011-08-01 03:38:32', NULL, NULL, 0.16, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(232, 10, '211', '', '', 'products/collection/angels/DSC_5584_cat.jpg', 'products/collection/angels/DSC_5584_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 70.0000, '2010-05-18 22:54:36', '2011-07-25 02:08:34', NULL, NULL, 0.12, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(233, 6, '154', '', '', 'products/collection/angels/DSC_7166_cat.jpg', 'products/collection/angels/DSC_7166_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 200.0000, '2010-05-18 22:54:36', '2011-08-01 03:45:33', NULL, NULL, 0.16, 1, NULL, 1, 0, 2, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(234, 1, '185', '', '', 'products/top/DSC_7192_cat.jpg', 'products/top/DSC_7192_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 630.0000, '2010-05-18 22:54:36', '2011-07-25 02:16:17', NULL, NULL, 0.32, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(235, 0, '143', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, '2010-05-18 22:54:36', NULL, '0000-00-00 00:00:00', NULL, 0.00, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(236, 2, '153', '', '', 'products/necklaces/all/DSC_2479_cat2.jpg', 'products/necklaces/all/DSC_2479_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 120.0000, '2010-05-18 22:54:36', '2011-07-25 01:02:58', NULL, NULL, 0.22, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(237, 1, '207', '', '', 'products/top/DSC_0828_cat.jpg', 'products/top/DSC_0828_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 225.0000, '2010-05-18 22:54:36', '2011-05-04 17:43:39', NULL, NULL, 0.00, 0, NULL, 1, 0, 2, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(238, 1, '203', '', '', 'products/top/DSC_2471_cat.jpg', 'products/top/DSC_2471_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 95.0000, '2010-05-18 22:54:36', '2011-08-03 04:36:47', NULL, NULL, 0.22, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(239, 0, '210', '', '', 'products/bracelets/all/DSC_5582_cat.jpg', 'products/top/DSC_5582_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55.0000, '2010-05-18 22:54:36', '2011-07-25 01:18:00', NULL, NULL, 0.12, 1, NULL, 1, 0, 2, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(240, 7, '194', '', '', 'products/top/DSC_2423_cat.jpg', 'products/top/DSC_2423_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 30.0000, '2010-05-18 22:54:36', '2011-07-25 01:18:50', NULL, NULL, 0.10, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(241, 1, '178', '', '', 'products/top/DSC_7181_cat.jpg', 'products/top/DSC_7181_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 160.0000, '2010-05-18 22:54:36', '2011-07-25 02:19:04', NULL, NULL, 0.30, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(242, 3, '148', '', '', 'products/top/DSC_2505_cat.jpg', 'products/top/DSC_2505_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55.0000, '2010-05-18 22:54:36', '2011-07-25 01:24:16', NULL, NULL, 0.12, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(243, 4, '149', '', '', 'products/top/DSC_2502_cat.jpg', 'products/top/DSC_2502_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 60.0000, '2010-05-18 22:54:36', '2011-07-25 01:37:50', NULL, NULL, 0.12, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(244, 1, '180', '', '', 'products/top/DSC_5651_cat.jpg', 'products/top/DSC_5651_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 65.0000, '2010-05-18 22:54:36', '2011-07-25 01:38:41', NULL, NULL, 0.10, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(245, 1, '161', '', '', 'products/top/DSC_2422_cat.jpg', 'products/top/DSC_2422item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 36.0000, '2010-05-18 22:54:36', '2011-07-25 01:40:07', NULL, NULL, 0.10, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(246, 1, '175', '', '', 'products/top/DSC_2435_cat.jpg', 'products/top/DSC_2435_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 420.0000, '2010-05-18 22:54:36', '2011-07-25 01:41:52', NULL, NULL, 0.30, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(247, 4, '190', '', '', 'products/necklaces/all/DSC_2462_cat2.jpg', 'products/top/DSC_2462_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 110.0000, '2010-05-18 22:54:36', '2011-07-25 01:46:18', NULL, NULL, 0.22, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(249, 0, '188', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 90.0000, '2010-05-18 22:54:36', NULL, '0000-00-00 00:00:00', NULL, 0.00, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(250, 1, '187', '', '', 'products/top/DSC_7197_cat.jpg', 'products/top/DSC_7197_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 140.0000, '2010-05-18 22:54:36', '2011-07-25 01:47:41', NULL, NULL, 0.15, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(251, 2, '191', '', '', 'products/necklaces/all/DSC_2482_cat2.jpg', 'products/top/DSC_2482_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 150.0000, '2010-05-18 22:54:36', '2011-07-25 01:59:23', NULL, NULL, 0.22, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(252, 10, '198', '', '', 'products/bracelets/all/DSC_7111_cat.jpg', 'products/bracelets/all/DSC_7111_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 40.0000, '2010-05-18 22:54:36', '2011-07-25 02:01:00', NULL, NULL, 0.12, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(253, 5, '151', '', '', 'products/top/DSC_2507_cat.jpg', 'products/top/DSC_2507_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 50.0000, '2010-05-18 22:54:36', '2011-07-25 02:04:26', NULL, NULL, 0.12, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(255, 1, '209', '', '', 'products/top/DSC_7193_cat.jpg', 'products/top/DSC_7193_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 150.0000, '2010-05-18 22:54:36', '2011-07-25 02:06:52', NULL, NULL, 0.15, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(256, 1, '206', '', '', 'products/top/DSC_2432_cat.jpg', 'products/top/DSC_2432_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 450.0000, '2010-05-18 22:54:36', '2011-07-25 01:43:55', NULL, NULL, 0.30, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(257, 1, '196', '', '', 'products/top/DSC_1408_cat.jpg', 'products/top/DSC_1408_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 85.0000, '2010-05-18 22:54:36', '2011-07-23 23:00:19', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(258, 1, '204', '', '', 'products/top/DSC_2487_cat.jpg', 'products/top/DSC_2487_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 200.0000, '2010-05-18 22:54:36', '2010-07-15 14:01:09', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(259, 2, '199', '', '', 'products/bracelets/all/DSC_7115_cat.jpg', 'products/bracelets/all/DSC_7115_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 60.0000, '2010-05-18 22:54:36', '2011-07-25 02:07:21', NULL, NULL, 0.12, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(260, 1, '144', '', '', 'products/top/DSC_5598_cat.jpg', 'products/top/DSC_5598_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 70.0000, '2010-05-18 22:54:36', '2011-07-25 02:08:05', NULL, NULL, 0.11, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(261, 4, '201', '', '', 'products/top/DSC_7160_cat.jpg', 'products/top/DSC_7161_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 40.0000, '2010-05-18 22:54:36', '2011-07-25 02:09:55', NULL, NULL, 0.10, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(262, 4, '202', '', '', 'products/top/DSC_7161_cat.jpg', 'products/top/DSC_7161_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 40.0000, '2010-05-18 22:54:36', '2011-07-25 02:11:14', NULL, NULL, 0.10, 1, NULL, 1, 0, 2, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(263, 5, '192', '', '', 'products/top/DSC_2412_cat.jpg', 'products/top/DSC_2412_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 30.0000, '2010-05-18 22:54:36', '2011-07-25 02:14:01', NULL, NULL, 0.10, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(264, 1, '146', '', '', 'products/top/DSC_0830-20_cat.jpg', 'products/top/DSC_0830-20_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 130.0000, '2010-05-18 22:54:36', '2011-07-25 02:14:56', NULL, NULL, 0.22, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(265, 1, '195', '', '', 'products/top/DSC_7179_cat.jpg', 'products/top/DSC_7179_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 325.0000, '2010-05-18 22:54:36', '2011-07-25 02:20:49', NULL, NULL, 0.30, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(266, 1, '193', '', '', 'products/top/DSC_2407_cat.jpg', 'products/top/DSC_2407_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 30.0000, '2010-05-18 22:54:36', '2011-07-25 02:22:30', NULL, NULL, 0.10, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(267, 1, '182', '', '', 'products/top/DSC_5573_c_cat.jpg', 'products/top/DSC_5573_c_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 190.0000, '2010-05-18 22:54:36', '2011-07-25 02:23:39', NULL, NULL, 0.30, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(268, 1, '181', '', '', 'products/top/DSC_5562_c_cat.jpg', 'products/top/DSC_5562_c_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 300.0000, '2010-05-18 22:54:36', '2011-07-25 02:24:17', NULL, NULL, 0.32, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(269, 1, '179', '', '', 'products/top/DSC_5658_cat.jpg', 'products/top/DSC_5658_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 90.0000, '2010-05-18 22:54:36', '2011-07-25 02:25:24', NULL, NULL, 0.10, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(270, 0, '200', '', '', 'products/bracelets/all/DSC_7104_cat.jpg', 'products/bracelets/all/DSC_7104_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55.0000, '2010-05-18 22:54:36', '2011-07-14 12:53:10', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(271, 1, '205', '', '', 'products/top/DSC_0823-20_cat.jpg', 'products/top/DSC_0823-20_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 100.0000, '2010-05-18 22:54:36', '2010-12-19 19:30:00', NULL, NULL, 0.00, 0, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(272, 0, '184', '', '', 'products/top/DSC_7190_cat.jpg', 'products/top/DSC_7190_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 630.0000, '2010-05-18 22:54:36', '2011-07-25 02:27:36', NULL, NULL, 0.32, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(276, 12, '221', '', '', 'products/top/DSC_7134_2_cat.jpg', 'products/top/DSC_7134_2_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 95.0000, '2010-09-29 02:16:41', '2011-07-24 23:58:53', NULL, NULL, 0.13, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(273, 2, '189', '', '', 'products/necklaces/all/DSC_2470_cat.jpg', 'products/top/DSC_2470_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 70.0000, '2010-08-25 15:25:37', '2011-07-25 02:05:29', NULL, NULL, 0.22, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(274, 2, '159', '', '', 'products/top/DSC_5648_cat.jpg', 'products/top/DSC_5648_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.0000, '2010-09-24 17:36:01', '2011-07-24 23:34:48', NULL, NULL, 0.10, 1, NULL, 1, 0, 2, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(275, 5, '162', '', '', 'products/top/DSC_5622_cat.jpg', 'products/top/DSC_5622_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 30.0000, '2010-09-24 17:36:26', '2011-07-25 02:22:00', NULL, NULL, 0.10, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(286, 1, '167', '', '', 'products/top/DSC_5617_cat.jpg', 'products/top/DSC_5617_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 22.0000, '2010-09-29 02:16:41', '2011-05-04 16:14:44', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(287, 1, '145', '', '', 'products/top/DSC_5604_cat.jpg', 'products/top/DSC_5604_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.0000, '2010-09-29 02:16:41', '2011-07-14 21:56:55', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(288, 1, '214', '', '', 'products/top/DSC_5608_cat.jpg', 'products/top/DSC_5608_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.0000, '2010-09-29 02:16:41', '2011-07-14 21:56:51', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(289, 2, '168', '', '', 'products/top/DSC_5614_cat.jpg', 'products/top/DSC_5614_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 42.0000, '2010-09-29 02:16:41', '2011-07-25 02:12:10', NULL, NULL, 0.10, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(290, 1, '216', '', '', 'products/top/DSC_5641_cat.jpg', 'products/top/DSC_5641_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 33.0000, '2010-09-29 02:16:41', '2011-07-25 02:21:27', NULL, NULL, 0.10, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(291, 9, '223', '', '', 'products/top/DSC_7151_cat.jpg', 'products/top/DSC_7151_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 140.0000, '2010-10-31 15:48:50', '2011-07-24 23:59:39', NULL, NULL, 0.25, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(292, 9, '220', '', '', 'products/top/DSC_7129_cat.jpg', 'products/top/DSC_7129_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 140.0000, '2010-10-31 15:48:50', '2011-07-25 00:55:20', NULL, NULL, 0.25, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(293, 8, '224', '', '', 'products/top/DSC_7133_cat.jpg', 'products/top/DSC_7133_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 90.0000, '2010-10-31 15:48:50', '2011-07-25 00:14:30', NULL, NULL, 0.13, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(294, 10, '226', '', '', 'products/top/DSC_7157_cat.jpg', 'products/top/DSC_7157_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 140.0000, '2010-10-31 15:48:50', '2011-07-25 00:58:58', NULL, NULL, 0.25, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(295, 15, '174', '', '', 'products/top/DSC_7137_cat.jpg', 'products/top/DSC_7137_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 100.0000, '2010-10-31 15:48:50', '2011-07-31 21:02:38', NULL, NULL, 0.13, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(296, 1, '171', '', '', 'products/top/DSC_7199_cat.jpg', 'products/top/DSC_7199_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 110.0000, '2010-10-31 15:48:50', '2011-07-25 01:15:12', NULL, NULL, 0.15, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(297, 7, '173', '', '', 'products/collection/goddesses/DSC_7139_cat2.jpg', 'products/collection/goddesses/DSC_7139_item2.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 100.0000, '2010-10-31 15:48:50', '2011-07-25 01:22:40', NULL, NULL, 0.13, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(298, 12, '219', '', '', 'products/top/DSC_7159_cat.jpg', 'products/top/DSC_7159_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 145.0000, '2010-10-31 15:48:50', '2011-07-25 01:23:45', NULL, NULL, 0.25, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(299, 10, '225', '', '', 'products/top/DSC_7153_cat.jpg', 'products/top/DSC_7153_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 130.0000, '2010-10-31 15:48:50', '2011-07-25 01:33:47', NULL, NULL, 0.25, 1, NULL, 1, 0, 2, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(300, 14, '177', '', '', 'products/top/DSC_7141_cat.jpg', 'products/top/DSC_7141_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 90.0000, '2010-10-31 15:48:50', '2011-07-25 01:48:57', NULL, NULL, 0.13, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(301, 10, '218', '', '', 'products/top/DSC_7148_cat.jpg', 'products/top/DSC_7148_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 125.0000, '2010-10-31 15:48:50', '2011-07-25 01:50:35', NULL, NULL, 0.25, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(302, 1, '228', '', '', 'products/top/DSC_0066_72cs_cat.jpg', 'products/top/DSC_0066_72cs_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 110.0000, '2010-11-03 23:21:55', '2010-11-18 03:38:43', NULL, NULL, 0.00, 0, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(303, 13, '176', '', '', 'products/top/DSC_7149_cat.jpg', 'products/top/DSC_7149_item_c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 120.0000, '2010-11-11 22:48:32', '2011-07-25 01:35:47', NULL, NULL, 0.25, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(310, 6, '238', '', '', 'products/necklaces/malas/DSC_9530_cat.jpg', 'products/necklaces/malas/DSC_9530_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 175.0000, '2011-04-05 21:34:58', '2011-08-05 22:06:59', NULL, NULL, 0.30, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(305, 3, '229', '', '', 'products/top/DSC_9531_cat.jpg', 'products/top/DSC_9531_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 150.0000, '2011-03-18 23:43:49', '2011-07-25 16:16:59', NULL, NULL, 0.30, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(306, 7, '227', '', '', 'products/top/DSC_9539_cat.jpg', 'products/top/DSC_9539_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 155.0000, '2011-03-18 23:43:49', '2011-07-25 16:18:39', NULL, NULL, 0.32, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(307, 5, '231', '', '', 'products/top/DSC_9543_cat.jpg', 'products/top/DSC_9543_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 145.0000, '2011-03-18 23:43:49', '2011-07-25 16:18:20', NULL, NULL, 0.32, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(308, 1, '230', '', '', 'products/top/DSC_9537_cat.jpg', 'products/top/DSC_9537_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 155.0000, '2011-03-18 23:43:49', '2011-07-25 16:19:26', NULL, NULL, 0.32, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(309, 6, '238', '', '', 'products/top/DSC_9547_cat.jpg', 'products/top/DSC_9547_item.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 175.0000, '2011-03-18 23:43:49', '2011-07-25 16:29:05', NULL, NULL, 0.30, 1, NULL, 1, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(311, 1, '68', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 90.0000, '2011-04-15 19:33:39', NULL, '0000-00-00 00:00:00', NULL, 0.00, 1, NULL, 1, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(318, 0, '242', '', '', 'products/top/imghead.jpg', 'products/top/imghead.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 200.0000, '2011-07-30 16:33:55', '2011-07-31 23:01:17', NULL, NULL, 0.00, 0, NULL, 0, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(316, 20, 'Jeremiel', '', '', 'products/collection/angels/DSC_7200_zoom2ls.jpg', 'products/collection/angels/DSC_7200_zoom2lm.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 200.0000, '2011-06-14 01:43:12', '2011-06-15 01:01:36', NULL, NULL, 0.00, 0, NULL, 0, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(317, 1, '241', '', '', 'products/necklaces/malas/imghead.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 225.0000, '2011-07-24 14:16:19', '2011-07-26 18:15:53', NULL, NULL, 0.00, 0, NULL, 0, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(319, 12, '11111', '', '', 'Hydrangeas.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11.0000, '2011-08-16 20:02:32', '2011-08-16 20:03:34', NULL, NULL, 0.00, 1, '0', 0, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', '', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `products` VALUES(507, 987, 'TEST1', '0773889992883', '0773889992883', 'Black-Wagon-Large.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 178.8700, '2012-01-25 03:45:24', '2012-06-25 11:41:27', NULL, NULL, 2.00, 1, '0', 1, 0, 13, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', '', 75.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 2, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 239.95, 0, 0, 'heavy-duty-wagon-black.jpg', '', '', '', '');
INSERT INTO `products` VALUES(508, 981, 'TEST2', '', '', 'iStock_000012242123XSmall.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 132.8500, '2012-01-26 23:21:24', '2012-06-25 11:36:05', NULL, NULL, 2.00, 1, '0', 0, 0, 19, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', '', 129.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 5, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 239.95, 0, 0, 'iStock_000012242123XSmall.jpg', '', '', '', '');
INSERT INTO `products` VALUES(509, 0, 'TEST3', '', 'ABC123CBA', 'wagons-beepbeepzoom.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 127.0000, '2012-01-26 23:26:14', '2012-04-22 10:14:37', '2020-01-31 00:00:00', NULL, 2.05, 0, '0', 0, 0, 4, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', '', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 199.00, 0, 0, 'iStock_000003553750XSmall.jpg', 'iStock_000004105680XSmall.jpg', 'iStock_000012242123XSmall.jpg', '', '');
INSERT INTO `products` VALUES(510, 1000, '', '', '', 'divinitybydave-layout2.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 99.0000, '2012-02-17 20:25:23', NULL, NULL, NULL, 0.00, 1, '0', 0, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', '', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, '', '', '', '', '');
INSERT INTO `products` VALUES(511, 999, '', '', '', 'divinitybydave-layout2.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 99.0000, '2012-02-17 20:31:01', NULL, NULL, NULL, 0.00, 1, '0', 0, 0, 1, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', '', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, '', '', '', '', '');
INSERT INTO `products` VALUES(512, 2, 'GIFT_25', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25.0000, '2012-07-01 20:31:58', NULL, NULL, NULL, 0.00, 1, '0', 0, 0, 3, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', '', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, '', '', '', '', '');
INSERT INTO `products` VALUES(513, 109, 'GIFT100', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 100.0000, '2012-07-10 22:11:54', NULL, NULL, NULL, 0.00, 1, '0', 0, 0, 2, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', '', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, '', '', '', '', '');
INSERT INTO `products` VALUES(514, 108, 'GIFTBLAHBLAH', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 102.0000, '2012-07-20 18:19:23', '2012-07-20 23:18:29', NULL, NULL, 0.00, 1, '0', 0, 0, 3, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', '', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, '', '', '', '', '');
INSERT INTO `products` VALUES(515, 0, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, '2012-07-25 20:21:01', NULL, NULL, NULL, 0.00, 1, '0', 0, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', '', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, '', '', '', '', '');
INSERT INTO `products` VALUES(516, 0, '1213', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, '2012-07-25 20:21:25', NULL, NULL, NULL, 0.00, 1, '0', 0, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', '', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, '', '', '', '', '');
INSERT INTO `products` VALUES(517, 0, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, '2012-07-29 23:34:48', NULL, NULL, NULL, 0.00, 1, '0', 0, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', '', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, '', '', '', '', '');
INSERT INTO `products` VALUES(518, 108, '', '', '', 'Scanned Document.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.8000, '2012-09-12 21:16:19', '2012-09-12 21:19:10', NULL, NULL, 0.00, 1, '0', 0, 0, 3, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', '', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, '', '', '', '', '');
INSERT INTO `products` VALUES(519, 109, '', '', '', 'kitty.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11.0000, '2012-09-24 17:36:36', NULL, NULL, NULL, 1.00, 1, '0', 0, 0, 2, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', '', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, '', '', '', '', '');
INSERT INTO `products` VALUES(520, 1111, '', '', '', 'kitty.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1.0000, '2012-10-03 02:37:14', '2012-10-06 11:53:03', NULL, NULL, 0.00, 1, '0', 0, 0, 0, 12.00, 12.00, 12.00, 0, 1, 0.0000, '', '', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0.00, 0.00, 0, 0, '', '', '', '', '');

CREATE TABLE IF NOT EXISTS `products_attributes` (
  `products_attributes_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_id` int(11) NOT NULL DEFAULT '0',
  `options_id` int(11) NOT NULL DEFAULT '0',
  `options_values_id` int(11) NOT NULL DEFAULT '0',
  `options_values_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `price_prefix` char(1) NOT NULL DEFAULT '',
  `products_options_sort_order` int(10) unsigned NOT NULL DEFAULT '0',
  `attribute_sort` int(10) unsigned NOT NULL DEFAULT '0',
  `products_attributes_sort_order` int(11) NOT NULL,
  PRIMARY KEY (`products_attributes_id`),
  KEY `products_id` (`products_id`,`options_id`),
  KEY `products_id_2` (`products_id`),
  KEY `options_id` (`options_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7052 ;

INSERT INTO `products_attributes` VALUES(7051, 520, 1, 4, 4.5000, '+', 0, 0, 2);
INSERT INTO `products_attributes` VALUES(7049, 520, 1, 1, 2.0000, '+', 1, 0, 2);
INSERT INTO `products_attributes` VALUES(7050, 520, 2, 3, 0.0000, '', 0, 0, 0);

CREATE TABLE IF NOT EXISTS `products_attributes_download` (
  `products_attributes_id` int(11) NOT NULL DEFAULT '0',
  `products_attributes_filename` varchar(255) NOT NULL DEFAULT '',
  `products_attributes_filegroup_id` int(11) DEFAULT NULL,
  `products_attributes_maxdays` int(2) DEFAULT '0',
  `products_attributes_maxcount` int(2) DEFAULT '0',
  PRIMARY KEY (`products_attributes_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `products_attributes_download_groups` (
  `download_group_id` int(11) NOT NULL,
  `download_group_name` varchar(255) DEFAULT NULL,
  `language_id` int(11) NOT NULL,
  PRIMARY KEY (`download_group_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `products_attributes_download_groups` VALUES(0, 'No File Group', 1);
INSERT INTO `products_attributes_download_groups` VALUES(1, 'TEST', 1);

CREATE TABLE IF NOT EXISTS `products_attributes_download_groups_files` (
  `download_groups_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `download_group_id` int(11) NOT NULL,
  `download_group_filename` varchar(255) NOT NULL,
  PRIMARY KEY (`download_groups_file_id`,`download_group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `products_attributes_download_groups_to_files` (
  `download_groups_file_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `download_group_file_description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`download_groups_file_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `products_description` (
  `products_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL DEFAULT '1',
  `products_name` varchar(128) NOT NULL,
  `products_short` text,
  `products_description` text,
  `products_moreinfo` text,
  `products_extra1` text,
  `products_manual` text,
  `products_extraimage` text,
  `products_musthave` text,
  `products_spec` text,
  `products_url` varchar(255) DEFAULT NULL,
  `products_url_alt_buy_now` varchar(640) NOT NULL,
  `products_viewed` int(5) DEFAULT '0',
  `products_head_title_tag` varchar(80) DEFAULT NULL,
  `products_head_desc_tag` longtext,
  `products_head_keywords_tag` longtext,
  `products_seo_url` varchar(100) NOT NULL DEFAULT '',
  `products_info_title` varchar(250) DEFAULT NULL,
  `products_info_desc` text,
  PRIMARY KEY (`products_id`,`language_id`),
  KEY `products_name` (`products_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=521 ;

INSERT INTO `products_description` VALUES(509, 1, 'Test Prodcut 3', '<p>\r\n	There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don&#39;t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn&#39;t anything embarrassing hidden in the middle of text</p>', '<p>\r\n	<strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Testing updating a product.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '', '', 94, 'Test Prodcut 3', 'Test Prodcut 3', 'Test Prodcut 3', '', '', '<iframe width="560" height="315" src="http://www.youtube.com/embed/a6cNdhOKwi0" frameborder="0" allowfullscreen></iframe>');
INSERT INTO `products_description` VALUES(507, 1, 'Test Product 1', '<p>\r\n	There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don&#39;t look even slightly believable.</p>', '<p>\r\n	It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &#39;Content here, content here&#39;, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &#39;lorem ipsum&#39; will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>\r\n<p>\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '', '', 71, 'Test Product 1', 'Test Product 1', 'Test Product 1', '', '9', '<iframe width="560" height="315" src="http://www.youtube.com/embed/3f7l-Z4NF70" frameborder="0" allowfullscreen></iframe>');
INSERT INTO `products_description` VALUES(508, 1, 'Test Product 2', '<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.&nbsp;</p>', '<p>\r\n	In <a href="http://en.wikipedia.org/wiki/Publishing" title="Publishing">publishing</a> and <a href="http://en.wikipedia.org/wiki/Graphic_design" title="Graphic design">graphic design</a>, <b>lorem ipsum</b><a href="http://en.wikipedia.org/wiki/Lorem_ipsum#References"><sup>[p]</sup></a><sup class="reference" id="cite_ref-SDop_0-0"><a href="http://en.wikipedia.org/wiki/Lorem_ipsum#cite_note-SDop-0"><span>[</span>1<span>]</span></a></sup> is <a class="mw-redirect" href="http://en.wikipedia.org/wiki/Placeholder_text" title="Placeholder text">placeholder text</a> (filler text) commonly used to demonstrate the <a class="mw-redirect" href="http://en.wikipedia.org/wiki/Graphic" title="Graphic">graphics</a> elements of a document or visual presentation, such as <a href="http://en.wikipedia.org/wiki/Font" title="Font">font</a>, <a href="http://en.wikipedia.org/wiki/Typography" title="Typography">typography</a>, and <a href="http://en.wikipedia.org/wiki/Page_layout" title="Page layout">layout</a>. The lorem ipsum text is typically a section of a <a href="http://en.wikipedia.org/wiki/Latin" title="Latin">Latin</a> text by <a href="http://en.wikipedia.org/wiki/Cicero" title="Cicero">Cicero</a> with words altered, added and removed that make it nonsensical in meaning and not proper Latin.<sup class="reference" id="cite_ref-SDop_0-1"><a href="http://en.wikipedia.org/wiki/Lorem_ipsum#cite_note-SDop-0"><span>[</span>1<span>]</span></a></sup></p>\r\n<p>\r\n	Even though &quot;lorem ipsum&quot; may arouse curiosity because of its resemblance to classical <a href="http://en.wikipedia.org/wiki/Latin" title="Latin">Latin</a>, it is not intended to have meaning. Where text is comprehensible in a document, people tend to focus on the textual content rather than upon overall presentation, so publishers use <i>lorem ipsum</i> when displaying a <a href="http://en.wikipedia.org/wiki/Typeface" title="Typeface">typeface</a> or design elements and page layout in order to direct the focus to the publication style and not the meaning of the text. In spite of its basis in Latin, use of <i>lorem ipsum</i> is often referred to as <a href="http://en.wikipedia.org/wiki/Greeking" title="Greeking">greeking</a>, from the phrase &quot;<a href="http://en.wikipedia.org/wiki/Greek_to_me" title="Greek to me">it&#39;s all Greek to me</a>,&quot; which indicates that this is not meant to be readable text.<sup class="reference" id="cite_ref-Greeking_1-0"><a href="http://en.wikipedia.org/wiki/Lorem_ipsum#cite_note-Greeking-1"><span>[</span>2<span>]</span></a></sup></p>', NULL, NULL, NULL, NULL, NULL, NULL, '', '', 95, 'Test Product 2', 'Test Product 2', 'Test Product 2', '', '', '');
INSERT INTO `products_description` VALUES(511, 1, 'testing new prodduct cartstore twitter facebook out', '', '<p>\r\n	This is the description area.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '', '', 42, 'testing new prodduct cartstore twitter facebook out', 'testing new prodduct cartstore twitter facebook out', 'testing new prodduct cartstore twitter facebook out', '', '', '');
INSERT INTO `products_description` VALUES(510, 1, 'Testing CartStore feeds to twitter and facebook', '', '<p>\r\n	This is the description area</p>', NULL, NULL, NULL, NULL, NULL, NULL, '', '', 13, 'Testing CartStore feeds to twitter and facebook', 'Testing CartStore feeds to twitter and facebook', 'Testing CartStore feeds to twitter and facebook', '', '', '');
INSERT INTO `products_description` VALUES(512, 1, 'Gift Voucher', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '', '', 7, 'Gift Voucher', 'Gift Voucher', 'Gift Voucher', '', '', '');
INSERT INTO `products_description` VALUES(513, 1, '$100 Gift Card', '', '<p>\r\n	gift card</p>', NULL, NULL, NULL, NULL, NULL, NULL, '', '', 14, '$100 Gift Card', '$100 Gift Card', '$100 Gift Card', '', '', '');
INSERT INTO `products_description` VALUES(514, 1, '102$ gift voucher', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '', '', 7, '100$ gift voucher', '100$ gift voucher', '100$ gift voucher', '', '', '');
INSERT INTO `products_description` VALUES(515, 1, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '', '', 30, '', '', '', '', '', '');
INSERT INTO `products_description` VALUES(516, 1, 'test', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '', '', 27, 'test', 'test', 'test', '', '', '');
INSERT INTO `products_description` VALUES(517, 1, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '', '', 24, '', '', '', '', '', '');
INSERT INTO `products_description` VALUES(518, 1, 'Tract Sample Product 1', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '', '', 13, 'Tract Sample Product 1', 'Tract Sample Product 1', 'Tract Sample Product 1', '', '', '');
INSERT INTO `products_description` VALUES(519, 1, 'test', '', '<p>\r\n	we test</p>', NULL, NULL, NULL, NULL, NULL, NULL, '', '', 32, 'test', 'test', 'test', '', '', '');
INSERT INTO `products_description` VALUES(520, 1, 'test attribute', '', '<p>\r\n	tester</p>', NULL, NULL, NULL, NULL, NULL, NULL, '', '', 33, 'test attribute', 'test attribute', 'test attribute', '', '', '');

CREATE TABLE IF NOT EXISTS `products_extra_fields` (
  `products_extra_fields_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_extra_fields_name` varchar(64) NOT NULL DEFAULT '',
  `products_extra_fields_order` int(3) NOT NULL DEFAULT '0',
  `products_extra_fields_status` tinyint(1) NOT NULL DEFAULT '1',
  `languages_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`products_extra_fields_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

CREATE TABLE IF NOT EXISTS `products_extra_images` (
  `products_extra_images_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_id` int(11) DEFAULT NULL,
  `products_extra_image` varchar(64) DEFAULT NULL,
  KEY `products_extra_images_id` (`products_extra_images_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=139 ;

CREATE TABLE IF NOT EXISTS `products_families` (
  `family_id` smallint(3) NOT NULL DEFAULT '0',
  `products_id` smallint(3) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `products_groups` (
  `customers_group_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `customers_group_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_id` int(11) NOT NULL DEFAULT '0',
  `products_price1` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_price2` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_price3` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_price4` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_price5` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_price6` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_price7` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_price8` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_price1_qty` int(11) NOT NULL DEFAULT '0',
  `products_price2_qty` int(11) NOT NULL DEFAULT '0',
  `products_price3_qty` int(11) NOT NULL DEFAULT '0',
  `products_price4_qty` int(11) NOT NULL DEFAULT '0',
  `products_price5_qty` int(11) NOT NULL DEFAULT '0',
  `products_price6_qty` int(11) NOT NULL DEFAULT '0',
  `products_price7_qty` int(11) NOT NULL DEFAULT '0',
  `products_price8_qty` int(11) NOT NULL DEFAULT '0',
  `products_qty_blocks` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`customers_group_id`,`products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `products_notifications` (
  `products_id` int(11) NOT NULL DEFAULT '0',
  `customers_id` int(11) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`products_id`,`customers_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `products_notifications` VALUES(216, 14, '2010-07-13 16:09:44');
INSERT INTO `products_notifications` VALUES(120, 16, '2010-07-28 18:44:30');
INSERT INTO `products_notifications` VALUES(211, 23, '2010-08-18 02:50:20');
INSERT INTO `products_notifications` VALUES(190, 22, '2010-08-27 23:22:22');
INSERT INTO `products_notifications` VALUES(119, 22, '2010-08-27 23:35:14');
INSERT INTO `products_notifications` VALUES(115, 25, '2010-09-03 21:40:51');
INSERT INTO `products_notifications` VALUES(183, 35, '2010-10-05 13:04:11');
INSERT INTO `products_notifications` VALUES(233, 43, '2010-10-24 12:50:26');
INSERT INTO `products_notifications` VALUES(114, 48, '2010-11-07 16:39:50');
INSERT INTO `products_notifications` VALUES(165, 48, '2010-11-07 16:41:36');
INSERT INTO `products_notifications` VALUES(274, 50, '2010-11-21 14:14:58');
INSERT INTO `products_notifications` VALUES(230, 50, '2010-11-21 14:14:58');
INSERT INTO `products_notifications` VALUES(304, 57, '2010-11-29 11:52:29');
INSERT INTO `products_notifications` VALUES(269, 57, '2010-11-29 11:52:29');
INSERT INTO `products_notifications` VALUES(204, 58, '2010-12-04 13:17:16');
INSERT INTO `products_notifications` VALUES(271, 63, '2010-12-19 17:08:52');
INSERT INTO `products_notifications` VALUES(237, 92, '2011-05-04 17:13:14');
INSERT INTO `products_notifications` VALUES(308, 91, '2011-05-04 17:56:51');
INSERT INTO `products_notifications` VALUES(224, 100, '2011-05-19 10:11:02');
INSERT INTO `products_notifications` VALUES(276, 100, '2011-05-19 10:11:02');
INSERT INTO `products_notifications` VALUES(298, 100, '2011-05-19 10:11:02');
INSERT INTO `products_notifications` VALUES(310, 100, '2011-05-19 10:11:02');
INSERT INTO `products_notifications` VALUES(305, 108, '2011-06-09 12:19:37');
INSERT INTO `products_notifications` VALUES(230, 118, '2011-07-04 11:34:47');
INSERT INTO `products_notifications` VALUES(232, 118, '2011-07-04 11:34:47');
INSERT INTO `products_notifications` VALUES(198, 122, '2011-07-13 22:38:39');

CREATE TABLE IF NOT EXISTS `products_options` (
  `products_options_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `products_options_name` varchar(200) NOT NULL,
  `products_options_track_stock` tinyint(4) NOT NULL DEFAULT '0',
  `products_options_images_enabled` varchar(5) NOT NULL DEFAULT 'false',
  `products_options_type` int(5) NOT NULL DEFAULT '0',
  `products_options_length` smallint(2) NOT NULL DEFAULT '32',
  `products_options_comment` varchar(32) DEFAULT NULL,
  `products_options_sort_order` int(10) unsigned NOT NULL DEFAULT '0',
  `products_attributes_sort_order` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`products_options_id`,`language_id`),
  KEY `products_options_name` (`products_options_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `products_options` VALUES(1, 1, 'Size', 0, 'false', 0, 32, NULL, 1, 1);
INSERT INTO `products_options` VALUES(2, 1, 'Color', 0, 'false', 0, 32, NULL, 0, 2);

CREATE TABLE IF NOT EXISTS `products_options_types` (
  `products_options_types_id` int(11) NOT NULL DEFAULT '0',
  `products_options_types_name` varchar(32) DEFAULT NULL,
  `language_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`products_options_types_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Track products_options_types';

INSERT INTO `products_options_types` VALUES(0, 'Select', 1);
INSERT INTO `products_options_types` VALUES(1, 'Text', 1);
INSERT INTO `products_options_types` VALUES(2, 'Radio', 1);
INSERT INTO `products_options_types` VALUES(3, 'Checkbox', 1);
INSERT INTO `products_options_types` VALUES(4, 'File', 1);
INSERT INTO `products_options_types` VALUES(6, 'Calender', 1);
INSERT INTO `products_options_types` VALUES(5, 'Text Area', 1);

CREATE TABLE IF NOT EXISTS `products_options_values` (
  `products_options_values_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `products_options_values_name` varchar(64) NOT NULL DEFAULT '',
  `products_options_values_thumbnail` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`products_options_values_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `products_options_values` VALUES(1, 1, 'Small', '');
INSERT INTO `products_options_values` VALUES(4, 1, 'Large', '');
INSERT INTO `products_options_values` VALUES(3, 1, 'Blue', '');

CREATE TABLE IF NOT EXISTS `products_options_values_to_products_options` (
  `products_options_values_to_products_options_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_options_id` int(11) NOT NULL DEFAULT '0',
  `products_options_values_id` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`products_options_values_to_products_options_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

INSERT INTO `products_options_values_to_products_options` VALUES(20, 3, 6, 0);
INSERT INTO `products_options_values_to_products_options` VALUES(19, 2, 5, 0);
INSERT INTO `products_options_values_to_products_options` VALUES(21, 1, 1, 1);
INSERT INTO `products_options_values_to_products_options` VALUES(25, 1, 4, 0);
INSERT INTO `products_options_values_to_products_options` VALUES(23, 2, 3, 0);

CREATE TABLE IF NOT EXISTS `products_shipping` (
  `products_id` int(11) NOT NULL DEFAULT '0',
  `products_ship_methods_id` int(11) DEFAULT NULL,
  `products_ship_zip` varchar(32) DEFAULT NULL,
  `products_ship_price` varchar(10) DEFAULT NULL,
  `products_ship_price_two` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `products_shipping` VALUES(25122, 0, '', '12', '2');
INSERT INTO `products_shipping` VALUES(25130, 0, '', '10', '0');
INSERT INTO `products_shipping` VALUES(25131, 0, '', '10', '5');
INSERT INTO `products_shipping` VALUES(25133, 0, '', '9.95', '0');
INSERT INTO `products_shipping` VALUES(329, 0, '', '5', '2');
INSERT INTO `products_shipping` VALUES(504, 0, '', '99', '0');
INSERT INTO `products_shipping` VALUES(507, 0, '', '99', '0');

CREATE TABLE IF NOT EXISTS `products_stock` (
  `products_stock_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_id` int(11) NOT NULL DEFAULT '0',
  `products_stock_attributes` varchar(255) NOT NULL DEFAULT '',
  `products_stock_quantity` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`products_stock_id`),
  UNIQUE KEY `idx_products_stock_attributes` (`products_id`,`products_stock_attributes`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=257 ;

CREATE TABLE IF NOT EXISTS `products_to_categories` (
  `products_id` int(11) NOT NULL DEFAULT '0',
  `categories_id` int(11) NOT NULL DEFAULT '0',
  KEY `idx_p2c_categories_id` (`categories_id`),
  KEY `idx_p2c_products_id` (`products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `products_to_categories` VALUES(319, 139);
INSERT INTO `products_to_categories` VALUES(507, 27);
INSERT INTO `products_to_categories` VALUES(507, 26);
INSERT INTO `products_to_categories` VALUES(507, 24);
INSERT INTO `products_to_categories` VALUES(508, 27);
INSERT INTO `products_to_categories` VALUES(508, 26);
INSERT INTO `products_to_categories` VALUES(508, 24);
INSERT INTO `products_to_categories` VALUES(509, 27);
INSERT INTO `products_to_categories` VALUES(511, 24);
INSERT INTO `products_to_categories` VALUES(509, 26);
INSERT INTO `products_to_categories` VALUES(509, 24);
INSERT INTO `products_to_categories` VALUES(512, 23);
INSERT INTO `products_to_categories` VALUES(513, 27);
INSERT INTO `products_to_categories` VALUES(514, 24);
INSERT INTO `products_to_categories` VALUES(518, 28);
INSERT INTO `products_to_categories` VALUES(519, 29);
INSERT INTO `products_to_categories` VALUES(520, 29);

CREATE TABLE IF NOT EXISTS `products_to_products_extra_fields` (
  `products_id` int(11) NOT NULL DEFAULT '0',
  `products_extra_fields_id` int(11) NOT NULL DEFAULT '0',
  `products_extra_fields_value` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`products_id`,`products_extra_fields_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `products_xsell` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `products_id` int(10) unsigned NOT NULL DEFAULT '1',
  `xsell_id` int(10) unsigned NOT NULL DEFAULT '1',
  `sort_order` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `products_id` (`products_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=129 ;

CREATE TABLE IF NOT EXISTS `products_ymm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `products_id` int(11) NOT NULL,
  `products_car_make` varchar(100) NOT NULL,
  `products_car_model` varchar(100) NOT NULL,
  `products_car_year_bof` int(11) NOT NULL,
  `products_car_year_eof` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_index` (`products_id`),
  KEY `products_id` (`products_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `products_ymm` VALUES(1, 330, 'Chevy', 'Firebird', 1969, 1972);
INSERT INTO `products_ymm` VALUES(2, 330, 'Ford', 'Mustang', 2002, 2003);
INSERT INTO `products_ymm` VALUES(3, 421, 'Chevy', 'Firebird', 1969, 1972);
INSERT INTO `products_ymm` VALUES(4, 421, 'Ford', 'Mustang', 2002, 2003);

CREATE TABLE IF NOT EXISTS `qbi_config` (
  `qbi_config_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `qbi_config_ver` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `qbi_qb_ver` smallint(5) unsigned NOT NULL DEFAULT '2003',
  `qbi_dl_iif` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `qbi_prod_rows` smallint(5) unsigned NOT NULL DEFAULT '5',
  `qbi_log` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `qbi_status_update` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `qbi_cc_status_select` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `qbi_mo_status_select` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `qbi_email_send` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `qbi_cc_clear` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `orders_status_import` int(11) NOT NULL DEFAULT '1',
  `orders_docnum` varchar(36) NOT NULL DEFAULT '%I',
  `orders_ponum` varchar(36) NOT NULL DEFAULT '%I',
  `cust_nameb` varchar(41) NOT NULL DEFAULT '%C10W-%I',
  `cust_namer` varchar(41) NOT NULL DEFAULT '%L10W-%I',
  `cust_limit` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_type` varchar(48) NOT NULL DEFAULT '',
  `cust_state` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `cust_country` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `cust_compcon` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `cust_phone` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `invoice_acct` varchar(30) NOT NULL DEFAULT 'Accounts Receivable',
  `invoice_salesacct` varchar(30) NOT NULL DEFAULT 'Undeposited Funds',
  `invoice_toprint` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `invoice_pmt` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `invoice_termscc` varchar(30) NOT NULL DEFAULT '',
  `invoice_terms` varchar(30) NOT NULL DEFAULT '',
  `invoice_rep` varchar(41) NOT NULL DEFAULT '',
  `invoice_fob` varchar(13) NOT NULL DEFAULT '',
  `invoice_comments` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `invoice_message` varchar(128) NOT NULL DEFAULT '',
  `invoice_memo` varchar(128) NOT NULL DEFAULT '',
  `item_acct` varchar(30) NOT NULL DEFAULT '',
  `item_asset_acct` varchar(30) NOT NULL DEFAULT 'Inventory Asset',
  `item_class` varchar(30) NOT NULL DEFAULT '',
  `item_cog_acct` varchar(30) NOT NULL DEFAULT 'Cost of Goods Sold',
  `item_osc_lang` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `item_match_inv` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `item_match_noninv` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `item_match_serv` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `item_default` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `item_default_name` varchar(40) NOT NULL DEFAULT '',
  `item_import_type` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `item_active` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `ship_acct` varchar(30) NOT NULL DEFAULT '',
  `ship_name` varchar(30) NOT NULL DEFAULT '',
  `ship_desc` varchar(36) NOT NULL DEFAULT '',
  `ship_class` varchar(30) NOT NULL DEFAULT '',
  `ship_tax` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `tax_on` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `tax_lookup` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `tax_name` varchar(30) NOT NULL DEFAULT '',
  `tax_agency` varchar(30) NOT NULL DEFAULT '',
  `tax_rate` float NOT NULL DEFAULT '0',
  `pmts_memo` varchar(128) NOT NULL DEFAULT '',
  `prods_sort` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `prods_width` smallint(5) unsigned NOT NULL DEFAULT '48',
  `qbi_config_active` tinyint(2) NOT NULL DEFAULT '0',
  `qbi_config_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`qbi_config_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `qbi_config` VALUES(1, 2.10, 2003, 1, 5, 0, 0, 1, 1, 0, 0, 1, '%I', '%I', '%C10W-%I', '%L10W-%I', 0, '', 1, 0, 1, 0, 'Accounts Receivable', 'Undeposited Funds', 1, 0, '', '', '', '', 1, '', '', '', 'Inventory Asset', '', 'Cost of Goods Sold', 0, 1, 0, 0, 0, '', 0, 0, '', 'John Beyerlein', '', '', 0, 0, 0, '', '', 0, '', 0, 48, 1, '2007-12-15 06:22:49');

CREATE TABLE IF NOT EXISTS `qbi_disc` (
  `qbi_disc_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `qbi_disc_refnum` int(10) unsigned NOT NULL DEFAULT '0',
  `qbi_disc_name` varchar(40) NOT NULL DEFAULT '',
  `qbi_disc_desc` varchar(128) NOT NULL DEFAULT '',
  `qbi_disc_accnt` varchar(40) NOT NULL DEFAULT '',
  `qbi_disc_price` float unsigned NOT NULL DEFAULT '0',
  `qbi_disc_type` varchar(16) NOT NULL DEFAULT '',
  `qbi_disc_tax` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`qbi_disc_id`),
  KEY `qbi_items_refnum` (`qbi_disc_refnum`),
  KEY `qbi_items_name` (`qbi_disc_name`),
  KEY `qbi_items_desc` (`qbi_disc_desc`),
  KEY `qbi_disc_tax` (`qbi_disc_tax`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `qbi_groups` (
  `qbi_groups_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `qbi_groups_refnum` int(10) unsigned NOT NULL DEFAULT '0',
  `qbi_groups_name` varchar(40) NOT NULL DEFAULT '',
  `qbi_groups_desc` varchar(128) NOT NULL DEFAULT '',
  `qbi_groups_toprint` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`qbi_groups_id`),
  KEY `qbi_groups_refnum` (`qbi_groups_refnum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `qbi_groups_items` (
  `qbi_groups_items_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `qbi_groups_refnum` int(10) unsigned NOT NULL DEFAULT '0',
  `qbi_items_refnum` int(10) unsigned NOT NULL DEFAULT '0',
  `qbi_groups_items_quan` float unsigned NOT NULL DEFAULT '0',
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`qbi_groups_items_id`),
  KEY `qbi_groups_ref` (`qbi_groups_refnum`),
  KEY `qbi_items_refnum` (`qbi_items_refnum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `qbi_items` (
  `qbi_items_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `qbi_items_refnum` int(10) unsigned NOT NULL DEFAULT '0',
  `qbi_items_name` varchar(40) NOT NULL DEFAULT '',
  `qbi_items_desc` varchar(128) NOT NULL DEFAULT '',
  `qbi_items_accnt` varchar(40) NOT NULL DEFAULT '',
  `qbi_items_price` float unsigned NOT NULL DEFAULT '0',
  `qbi_items_type` varchar(16) NOT NULL DEFAULT '',
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`qbi_items_id`),
  KEY `qbi_items_refnum` (`qbi_items_refnum`),
  KEY `qbi_items_name` (`qbi_items_name`),
  KEY `qbi_items_desc` (`qbi_items_desc`),
  KEY `qbi_items_type` (`qbi_items_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `qbi_ot` (
  `qbi_ot_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `qbi_ot_mod` varchar(48) NOT NULL DEFAULT '',
  `language_id` int(10) unsigned NOT NULL DEFAULT '0',
  `qbi_ot_text` varchar(48) NOT NULL DEFAULT '',
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`qbi_ot_id`),
  KEY `qbi_ot_mod` (`qbi_ot_mod`),
  KEY `language_id` (`language_id`),
  KEY `qbi_ot_text` (`qbi_ot_text`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `qbi_ot` VALUES(1, 'ot_loworderfee', 1, 'Low Order Fee', '2007-12-15 06:23:12');
INSERT INTO `qbi_ot` VALUES(2, 'ot_coupon', 1, 'Discount Coupons', '2007-12-15 06:23:12');
INSERT INTO `qbi_ot` VALUES(3, 'ot_redemptions', 1, 'Points Redeemptions', '2007-12-15 06:23:12');
INSERT INTO `qbi_ot` VALUES(4, 'ot_gv', 1, 'Gift Vouchers (-)', '2007-12-15 06:23:12');

CREATE TABLE IF NOT EXISTS `qbi_ot_disc` (
  `qbi_ot_disc_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `qbi_ot_mod` varchar(48) NOT NULL DEFAULT '',
  `qbi_disc_refnum` int(10) unsigned NOT NULL DEFAULT '0',
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`qbi_ot_disc_id`),
  KEY `qbi_ot_id` (`qbi_ot_mod`),
  KEY `qbi_disc_id` (`qbi_disc_refnum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `qbi_payosc` (
  `qbi_payosc_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `qbi_payosc_mod` varchar(48) NOT NULL DEFAULT '',
  `language_id` int(10) unsigned NOT NULL DEFAULT '0',
  `qbi_payosc_text` varchar(48) NOT NULL DEFAULT '',
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`qbi_payosc_id`),
  KEY `qbi_payosc_file` (`qbi_payosc_mod`),
  KEY `language_id` (`language_id`),
  KEY `qbi_payosc_text` (`qbi_payosc_text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `qbi_payosc_payqb` (
  `qbi_payosc_payqb_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `qbi_payosc_mod` varchar(48) NOT NULL DEFAULT '',
  `qbi_payqb_refnum` int(10) unsigned NOT NULL DEFAULT '0',
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`qbi_payosc_payqb_id`),
  KEY `qbi_payosc_mod` (`qbi_payosc_mod`),
  KEY `qbi_payqb_refnum` (`qbi_payqb_refnum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `qbi_payqb` (
  `qbi_payqb_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `qbi_payqb_refnum` int(10) unsigned NOT NULL DEFAULT '0',
  `qbi_payqb_name` varchar(48) NOT NULL DEFAULT '',
  `qbi_payqb_hidden` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `qbi_payqb_type` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`qbi_payqb_id`),
  KEY `qbi_payqb_refnum` (`qbi_payqb_refnum`),
  KEY `qbi_payqb_name` (`qbi_payqb_name`),
  KEY `qbi_payqb_hidden` (`qbi_payqb_hidden`),
  KEY `qbi_payqb_type` (`qbi_payqb_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `qbi_products_items` (
  `qbi_products_items_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `products_id` int(10) unsigned NOT NULL DEFAULT '0',
  `products_options_values_id` int(10) unsigned NOT NULL DEFAULT '0',
  `qbi_groupsitems_refnum` int(10) unsigned NOT NULL DEFAULT '0',
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`qbi_products_items_id`),
  KEY `products_id` (`products_id`),
  KEY `qbi_groupsitems_refnum` (`qbi_groupsitems_refnum`),
  KEY `products_options_values_id` (`products_options_values_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `qbi_shiposc` (
  `qbi_shiposc_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int(10) unsigned NOT NULL DEFAULT '0',
  `qbi_shiposc_car_code` varchar(48) NOT NULL DEFAULT '',
  `qbi_shiposc_serv_code` varchar(48) NOT NULL DEFAULT '',
  `qbi_shiposc_car_text` varchar(48) NOT NULL DEFAULT '',
  `qbi_shiposc_serv_text` varchar(48) NOT NULL DEFAULT '',
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`qbi_shiposc_id`),
  KEY `language_id` (`language_id`),
  KEY `qbi_shiposc_car_code` (`qbi_shiposc_car_code`),
  KEY `qbi_shiposc_serv_code` (`qbi_shiposc_serv_code`),
  KEY `qbi_shiposc_car_text` (`qbi_shiposc_car_text`),
  KEY `qbi_shiposc_serv_text` (`qbi_shiposc_serv_text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `qbi_shiposc_shipqb` (
  `qbi_shiposc_shipqb_refnum` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `qbi_shiposc_car_code` varchar(48) NOT NULL DEFAULT '',
  `qbi_shiposc_serv_code` varchar(48) NOT NULL DEFAULT '',
  `qbi_shipqb_refnum` int(10) unsigned NOT NULL DEFAULT '0',
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`qbi_shiposc_shipqb_refnum`),
  KEY `qbi_shiposc_car_code` (`qbi_shiposc_car_code`),
  KEY `qbi_shiposc_serv_code` (`qbi_shiposc_serv_code`),
  KEY `qbi_shipqb_refnum` (`qbi_shipqb_refnum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `qbi_shipqb` (
  `qbi_shipqb_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `qbi_shipqb_refnum` int(10) unsigned NOT NULL DEFAULT '0',
  `qbi_shipqb_name` varchar(16) NOT NULL DEFAULT '',
  `qbi_shipqb_hidden` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`qbi_shipqb_id`),
  KEY `qbi_shipqb_refnum` (`qbi_shipqb_refnum`),
  KEY `qbi_shipqb_name` (`qbi_shipqb_name`),
  KEY `qbi_shipqb_hidden` (`qbi_shipqb_hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `ratingbans` (
  `identityList` varchar(25) NOT NULL,
  `idList` varchar(100) NOT NULL,
  KEY `identityList` (`identityList`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `ratingbans` VALUES('127001', 'a');

CREATE TABLE IF NOT EXISTS `ratingBans` (
  `identityList` varchar(25) NOT NULL,
  `idList` varchar(100) NOT NULL,
  KEY `identityList` (`identityList`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `ratingBans` VALUES('6624971183', 'a');
INSERT INTO `ratingBans` VALUES('6624971206', 'a');
INSERT INTO `ratingBans` VALUES('6624971241', 'a');
INSERT INTO `ratingBans` VALUES('662497275', 'a');
INSERT INTO `ratingBans` VALUES('6624971174', 'a');
INSERT INTO `ratingBans` VALUES('6612678148', 'a');
INSERT INTO `ratingBans` VALUES('6624968225', 'a');
INSERT INTO `ratingBans` VALUES('6624971238', 'a');
INSERT INTO `ratingBans` VALUES('6624971162', 'a');
INSERT INTO `ratingBans` VALUES('6624971168', 'a');
INSERT INTO `ratingBans` VALUES('694669194', 'a');
INSERT INTO `ratingBans` VALUES('6624971197', 'a');
INSERT INTO `ratingBans` VALUES('662497214', 'a');
INSERT INTO `ratingBans` VALUES('50282656', 'a');
INSERT INTO `ratingBans` VALUES('6624967245', 'a');
INSERT INTO `ratingBans` VALUES('6624971177', 'a');
INSERT INTO `ratingBans` VALUES('6624971142', 'a');
INSERT INTO `ratingBans` VALUES('6624971139', 'a');
INSERT INTO `ratingBans` VALUES('662497284', 'a');
INSERT INTO `ratingBans` VALUES('6624971133', 'a');
INSERT INTO `ratingBans` VALUES('1992199108', 'a');
INSERT INTO `ratingBans` VALUES('6624973132', 'a');
INSERT INTO `ratingBans` VALUES('6624968245', 'a');
INSERT INTO `ratingBans` VALUES('6624971145', 'a');
INSERT INTO `ratingBans` VALUES('187184109123', 'a');
INSERT INTO `ratingBans` VALUES('6624972107', 'a');
INSERT INTO `ratingBans` VALUES('6624971136', 'a');
INSERT INTO `ratingBans` VALUES('662497315', 'a');
INSERT INTO `ratingBans` VALUES('187184116116', 'a');
INSERT INTO `ratingBans` VALUES('662497335', 'a');

CREATE TABLE IF NOT EXISTS `ratingitems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniqueName` varchar(25) NOT NULL,
  `totalVotes` int(11) NOT NULL DEFAULT '0',
  `totalPoints` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqueName` (`uniqueName`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

INSERT INTO `ratingitems` VALUES(2, '133', 0, 0);

CREATE TABLE IF NOT EXISTS `ratingItems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniqueName` varchar(25) NOT NULL,
  `totalVotes` int(11) NOT NULL DEFAULT '0',
  `totalPoints` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqueName` (`uniqueName`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

INSERT INTO `ratingItems` VALUES(1, '133', 0, 0);
INSERT INTO `ratingItems` VALUES(2, '134', 0, 0);
INSERT INTO `ratingItems` VALUES(3, '135', 0, 0);
INSERT INTO `ratingItems` VALUES(4, '131', 0, 0);
INSERT INTO `ratingItems` VALUES(5, '138', 0, 0);

CREATE TABLE IF NOT EXISTS `refund_method` (
  `refund_method_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `refund_method_name` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`refund_method_id`,`language_id`),
  KEY `idx_refund_method_name` (`refund_method_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `refund_method` VALUES(1, 1, 'Paypal');
INSERT INTO `refund_method` VALUES(2, 1, 'NoChex');
INSERT INTO `refund_method` VALUES(3, 1, 'Exchange');
INSERT INTO `refund_method` VALUES(4, 1, 'Gift Vouchers');

CREATE TABLE IF NOT EXISTS `refund_payments` (
  `refund_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `returns_id` int(11) NOT NULL DEFAULT '0',
  `refund_payment_name` varchar(64) NOT NULL DEFAULT 'No Refund Made',
  `refund_payment_value` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `refund_payment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `refund_payment_reference` varchar(50) DEFAULT NULL,
  `refund_payment_deductions` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `customer_method` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`refund_payment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `returned_products` (
  `returns_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_id` int(11) NOT NULL DEFAULT '0',
  `rma_value` varchar(15) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '0',
  `customers_name` varchar(64) NOT NULL DEFAULT '',
  `customers_acct` varchar(32) DEFAULT NULL,
  `customers_company` varchar(32) DEFAULT NULL,
  `customers_street_address` varchar(64) NOT NULL DEFAULT '',
  `customers_suburb` varchar(32) DEFAULT NULL,
  `customers_city` varchar(32) NOT NULL DEFAULT '',
  `customers_postcode` varchar(10) NOT NULL DEFAULT '',
  `customers_state` varchar(32) DEFAULT NULL,
  `customers_country` varchar(32) NOT NULL DEFAULT '',
  `customers_telephone` varchar(32) NOT NULL DEFAULT '',
  `customers_fax` varchar(32) NOT NULL DEFAULT '',
  `customers_email_address` varchar(96) NOT NULL DEFAULT '',
  `customers_address_format_id` int(5) NOT NULL DEFAULT '0',
  `delivery_name` varchar(64) NOT NULL DEFAULT '',
  `delivery_company` varchar(32) DEFAULT NULL,
  `delivery_street_address` varchar(64) NOT NULL DEFAULT '',
  `delivery_suburb` varchar(32) DEFAULT NULL,
  `delivery_city` varchar(32) NOT NULL DEFAULT '',
  `delivery_postcode` varchar(10) NOT NULL DEFAULT '',
  `delivery_state` varchar(32) DEFAULT NULL,
  `delivery_country` varchar(32) NOT NULL DEFAULT '',
  `delivery_address_format_id` int(5) NOT NULL DEFAULT '0',
  `billing_name` varchar(64) NOT NULL DEFAULT '',
  `billing_acct` varchar(32) DEFAULT NULL,
  `billing_company` varchar(32) DEFAULT NULL,
  `billing_street_address` varchar(64) NOT NULL DEFAULT '',
  `billing_suburb` varchar(32) DEFAULT NULL,
  `billing_city` varchar(32) NOT NULL DEFAULT '',
  `billing_postcode` varchar(10) NOT NULL DEFAULT '',
  `billing_state` varchar(32) DEFAULT NULL,
  `billing_country` varchar(32) NOT NULL DEFAULT '',
  `billing_address_format_id` int(5) NOT NULL DEFAULT '0',
  `payment_method` varchar(64) NOT NULL DEFAULT '',
  `cc_type` varchar(20) DEFAULT NULL,
  `cc_owner` varchar(64) DEFAULT NULL,
  `cc_number` varchar(32) DEFAULT NULL,
  `cc_expires` varchar(4) DEFAULT NULL,
  `cvvnumber` char(3) DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_purchased` datetime DEFAULT NULL,
  `returns_status` int(5) NOT NULL DEFAULT '1',
  `returns_date_finished` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comments` text,
  `currency` char(3) DEFAULT NULL,
  `currency_value` decimal(14,6) DEFAULT NULL,
  `account_name` varchar(32) NOT NULL DEFAULT '',
  `account_number` varchar(20) DEFAULT NULL,
  `po_number` varchar(12) DEFAULT NULL,
  `date_finished` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `returns_reason` tinyint(5) unsigned DEFAULT '0',
  `contact_user_name` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`returns_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `returns_products_data` (
  `returns_products_id` int(11) NOT NULL AUTO_INCREMENT,
  `returns_id` int(11) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '0',
  `products_id` int(11) NOT NULL DEFAULT '0',
  `products_model` varchar(12) DEFAULT NULL,
  `products_name` varchar(120) NOT NULL DEFAULT '',
  `products_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_discount_made` decimal(4,2) DEFAULT NULL,
  `final_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `products_tax` decimal(7,4) NOT NULL DEFAULT '0.0000',
  `products_quantity` int(2) NOT NULL DEFAULT '0',
  `products_serial_number` varchar(128) DEFAULT NULL,
  `products_returned` tinyint(2) unsigned DEFAULT '0',
  `products_exchanged` tinyint(2) unsigned DEFAULT '0',
  PRIMARY KEY (`returns_products_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `returns_status` (
  `returns_status_id` int(11) NOT NULL DEFAULT '1',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `returns_status_name` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`returns_status_id`,`language_id`),
  KEY `idx_returns_status_name` (`returns_status_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `returns_status` VALUES(1, 1, 'Pending');
INSERT INTO `returns_status` VALUES(2, 1, 'Awaiting Return');
INSERT INTO `returns_status` VALUES(3, 1, 'Cancelled');
INSERT INTO `returns_status` VALUES(4, 1, 'Complete');

CREATE TABLE IF NOT EXISTS `returns_status_history` (
  `returns_status_history_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `returns_id` int(11) unsigned NOT NULL DEFAULT '0',
  `returns_status` int(5) unsigned NOT NULL DEFAULT '0',
  `date_added` datetime DEFAULT NULL,
  `customer_notified` int(1) unsigned DEFAULT '0',
  `comments` text,
  PRIMARY KEY (`returns_status_history_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Return products''s status change history' AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS `returns_total` (
  `returns_total_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `returns_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `text` varchar(255) NOT NULL DEFAULT '',
  `value` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `class` varchar(32) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`returns_total_id`),
  KEY `idx_returns_total_returns_id` (`returns_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `return_reasons` (
  `return_reason_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `language_id` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `return_reason_name` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`return_reason_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `return_reasons` VALUES(1, 1, 'Faulty');
INSERT INTO `return_reasons` VALUES(2, 1, 'Damaged');
INSERT INTO `return_reasons` VALUES(3, 1, 'Incorrect Item');
INSERT INTO `return_reasons` VALUES(4, 1, 'Warranty');

CREATE TABLE IF NOT EXISTS `return_text` (
  `return_text_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `language_id` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `return_text_one` text,
  PRIMARY KEY (`return_text_id`,`language_id`),
  KEY `status_id` (`return_text_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `reviews` (
  `reviews_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_id` int(11) NOT NULL DEFAULT '0',
  `customers_id` int(11) DEFAULT NULL,
  `customers_name` varchar(64) NOT NULL DEFAULT '',
  `reviews_rating` int(1) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `reviews_read` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reviews_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

CREATE TABLE IF NOT EXISTS `reviews_description` (
  `reviews_id` int(11) NOT NULL DEFAULT '0',
  `languages_id` int(11) NOT NULL DEFAULT '0',
  `reviews_text` text NOT NULL,
  PRIMARY KEY (`reviews_id`,`languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `scart` (
  `scartid` int(11) NOT NULL AUTO_INCREMENT,
  `customers_id` int(11) NOT NULL,
  `dateadded` varchar(8) NOT NULL,
  `datemodified` varchar(8) NOT NULL,
  PRIMARY KEY (`scartid`),
  UNIQUE KEY `scartid` (`scartid`),
  UNIQUE KEY `customers_id` (`customers_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `seo_google` (
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `search_url` varchar(100) DEFAULT NULL,
  `search_term` varchar(50) DEFAULT NULL,
  `rank` int(4) DEFAULT '0',
  `sites_searched` tinyint(4) DEFAULT '0',
  `show_results` tinyint(4) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `seo_yahoo` (
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `search_url` varchar(100) DEFAULT NULL,
  `search_term` varchar(50) DEFAULT NULL,
  `rank` int(4) DEFAULT '0',
  `sites_searched` tinyint(4) DEFAULT '0',
  `show_results` tinyint(4) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sessions` (
  `sesskey` varchar(64) NOT NULL DEFAULT '',
  `expiry` int(11) unsigned NOT NULL DEFAULT '0',
  `value` text NOT NULL,
  PRIMARY KEY (`sesskey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `sessions` VALUES('48aaf6d82187a3782b0924c1f5ee3cab', 1350603292, 'language|s:7:"english";languages_id|s:1:"1";selected_box|s:13:"configuration";login_email_address|s:18:"bugs@cartstore.com";login_id|s:2:"16";login_groups_id|s:1:"1";login_firstname|s:6:"System";clone|i:1;');
INSERT INTO `sessions` VALUES('48c92fb03f68a9c28b46f1e15e036f92', 1350369661, 'language|s:7:"english";languages_id|s:1:"1";selected_box|s:13:"configuration";login_email_address|s:18:"bugs@cartstore.com";login_id|s:2:"16";login_groups_id|s:1:"1";login_firstname|s:6:"System";clone|i:1;');
INSERT INTO `sessions` VALUES('895c502a19950e411f9c064051740844', 1350340199, 'cart|O:12:"shoppingCart":8:{s:8:"contents";a:1:{i:508;a:1:{s:3:"qty";i:1;}}s:5:"total";d:66;s:6:"weight";d:2;s:6:"cartID";s:5:"89554";s:12:"content_type";s:8:"physical";s:9:"shiptotal";s:0:"";s:13:"total_virtual";d:66;s:14:"weight_virtual";d:184;}language|s:7:"english";languages_id|s:1:"1";currency|s:3:"USD";navigation|O:17:"navigationHistory":2:{s:4:"path";a:5:{i:0;a:4:{s:4:"page";s:9:"index.php";s:4:"mode";s:6:"NONSSL";s:3:"get";a:3:{s:11:"products_id";s:3:"508";s:6:"action";s:7:"buy_now";s:6:"osCsid";s:32:"8c76867ba13c7a6f279b51ad9d66270c";}s:4:"post";a:0:{}}i:1;a:4:{s:4:"page";s:17:"shopping_cart.php";s:4:"mode";s:6:"NONSSL";s:3:"get";a:0:{}s:4:"post";a:0:{}}i:2;a:4:{s:4:"page";s:0:"";s:4:"mode";s:6:"NONSSL";s:3:"get";a:0:{}s:4:"post";a:0:{}}i:3;a:4:{s:4:"page";s:21:"checkout_shipping.php";s:4:"mode";s:6:"NONSSL";s:3:"get";a:0:{}s:4:"post";a:3:{s:6:"action";s:7:"process";s:8:"shipping";s:13:"pickup_pickup";s:8:"comments";s:8:"<br />\r\n";}}i:4;a:4:{s:4:"page";s:20:"checkout_payment.php";s:4:"mode";s:6:"NONSSL";s:3:"get";a:2:{s:13:"payment_error";s:9:"ot_coupon";s:5:"error";s:32:"You did not enter a redeem code.";}s:4:"post";a:0:{}}}s:8:"snapshot";a:4:{s:4:"page";s:21:"checkout_shipping.php";s:4:"mode";s:6:"NONSSL";s:3:"get";a:0:{}s:4:"post";a:0:{}}}wishList|O:8:"wishlist":1:{s:6:"wishID";N;}affiliate_ref|N;affiliate_clickthroughs_id|N;pwa_array_customer|a:8:{s:19:"customers_firstname";s:4:"test";s:18:"customers_lastname";s:4:"test";s:23:"customers_email_address";s:17:"testeet@neter.com";s:19:"customers_telephone";s:8:"12345678";s:13:"customers_fax";s:0:"";s:20:"customers_newsletter";b:0;s:18:"customers_password";s:35:"5ca2aa845c8cd5ace6b016841f100d82:da";s:10:"fb_user_id";N;}pwa_array_address|a:10:{s:12:"customers_id";i:0;s:15:"entry_firstname";s:4:"test";s:14:"entry_lastname";s:4:"test";s:20:"entry_street_address";s:12:"123 test way";s:22:"entry_street_address_2";s:0:"";s:14:"entry_postcode";s:5:"32805";s:10:"entry_city";s:7:"orlando";s:16:"entry_country_id";s:3:"223";s:13:"entry_zone_id";s:2:"18";s:11:"entry_state";s:0:"";}customer_id|i:0;customer_first_name|s:4:"test";customer_default_address_id|i:0;customer_country_id|s:3:"223";customer_zone_id|s:2:"18";sendto|i:1;cartID|s:5:"89554";comments|s:6:"<br />";shipping|a:3:{s:2:"id";s:13:"pickup_pickup";s:5:"title";s:29:"Pickup Rate (Customer Pickup)";s:4:"cost";s:4:"0.00";}billto|i:0;cc_id|s:2:"10";');

CREATE TABLE IF NOT EXISTS `specials` (
  `specials_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_id` int(11) NOT NULL DEFAULT '0',
  `specials_new_products_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `specials_date_added` datetime DEFAULT NULL,
  `specials_last_modified` datetime DEFAULT NULL,
  `expires_date` date DEFAULT NULL,
  `date_status_change` datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `customers_group_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `specialStartDate` date NOT NULL,
  PRIMARY KEY (`specials_id`),
  KEY `products_id` (`products_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

INSERT INTO `specials` VALUES(8, 509, 99.0000, '2012-01-26 23:32:35', NULL, '0000-00-00', '2012-10-18 17:09:14', 1, 0, '2012-01-27');
INSERT INTO `specials` VALUES(7, 507, 99.0000, '2012-01-25 04:17:06', NULL, '0000-00-00', '2012-10-18 17:09:14', 1, 0, '2012-01-25');
INSERT INTO `specials` VALUES(9, 508, 66.0000, '2012-01-26 23:32:50', NULL, '0000-00-00', '2012-10-18 17:09:14', 1, 0, '2012-01-27');

CREATE TABLE IF NOT EXISTS `specials_retail_prices` (
  `products_id` int(11) NOT NULL DEFAULT '0',
  `specials_new_products_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `status` tinyint(4) DEFAULT NULL,
  `customers_group_id` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `specials_retail_prices` VALUES(509, 99.0000, 1, 0);
INSERT INTO `specials_retail_prices` VALUES(507, 99.0000, 1, 0);
INSERT INTO `specials_retail_prices` VALUES(508, 66.0000, 1, 0);

CREATE TABLE IF NOT EXISTS `supertracker` (
  `tracking_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `browser_string` varchar(255) NOT NULL DEFAULT '',
  `country_code` char(2) NOT NULL DEFAULT '',
  `country_name` varchar(100) NOT NULL DEFAULT '',
  `customer_id` int(11) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '0',
  `referrer` varchar(255) NOT NULL DEFAULT '',
  `referrer_query_string` varchar(255) NOT NULL DEFAULT '',
  `landing_page` varchar(255) NOT NULL DEFAULT '',
  `exit_page` varchar(100) NOT NULL DEFAULT '',
  `time_arrived` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_click` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `num_clicks` int(11) NOT NULL DEFAULT '1',
  `added_cart` varchar(5) NOT NULL DEFAULT 'false',
  `completed_purchase` varchar(5) NOT NULL DEFAULT 'false',
  `categories_viewed` varchar(255) NOT NULL DEFAULT '',
  `products_viewed` varchar(255) NOT NULL DEFAULT '',
  `cart_contents` mediumtext NOT NULL,
  `cart_total` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tracking_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4927 ;

INSERT INTO `supertracker` VALUES(4925, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=4&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-18 20:12:01', '2012-10-18 20:12:01', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4926, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/-c-1_4.html?amp;currency=INR&amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;currency=GBP&amp;amp;amp;page=4&amp;currency=CAD', '/allprods.php', '2012-10-18 20:54:27', '2012-10-18 21:09:14', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4923, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_2_11_4&amp;currency=CNY&amp;amp;amp;currency=AUD&amp;amp;page=6', '/index.php', '2012-10-18 17:43:09', '2012-10-18 18:29:21', 12, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4924, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CNY&amp;currency=AUD', '/allprods.php', '2012-10-18 19:18:37', '2012-10-18 19:18:39', 3, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4922, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=AUD&amp;amp;currency=AUD&amp;currency=AUD', '/index.php', '2012-10-18 17:00:45', '2012-10-18 17:00:51', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4920, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CNY&amp;currency=CNY', '/index.php', '2012-10-18 15:02:43', '2012-10-18 15:02:43', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4921, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;currency=CAD&amp;amp;amp;currency=CAD&amp;amp;page=4', '/index.php', '2012-10-18 16:02:21', '2012-10-18 16:21:49', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4919, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CNY&amp;currency=CAD', '/index.php', '2012-10-18 14:12:49', '2012-10-18 14:12:50', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4918, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CNY&amp;currency=AUD', '/index.php', '2012-10-18 13:03:13', '2012-10-18 13:03:14', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4916, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=CAD', '/index.php', '2012-10-18 09:16:19', '2012-10-18 09:16:20', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4917, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=CNY', '/index.php', '2012-10-18 10:18:13', '2012-10-18 10:18:14', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4915, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=CAD&amp;amp;currency=AUD&amp;currency=CNY', '/index.php', '2012-10-18 07:14:36', '2012-10-18 07:51:46', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4913, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=AUD', '/index.php', '2012-10-18 05:45:24', '2012-10-18 05:45:24', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4914, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=CAD&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-10-18 06:37:23', '2012-10-18 06:37:24', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4912, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=CAD&amp;amp;currency=AUD&amp;currency=AUD', '/index.php', '2012-10-18 04:56:30', '2012-10-18 04:56:31', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4911, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=AUD&amp;amp;currency=CNY&amp;currency=CNY', '/index.php', '2012-10-18 04:11:11', '2012-10-18 04:11:11', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4909, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=AUD&amp;amp;currency=CNY&amp;currency=CAD', '/index.php', '2012-10-18 03:23:19', '2012-10-18 03:23:20', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4910, '66.249.73.35', 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_1 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8B117 Safari/6531.22.7 (compatible; Googlebot-Mobile/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-10-18 03:43:01', '2012-10-18 03:43:01', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4908, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=AUD&amp;amp;currency=CNY&amp;currency=AUD', '/index.php', '2012-10-18 02:48:57', '2012-10-18 02:48:57', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4907, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=AUD&amp;amp;currency=CAD&amp;currency=CNY', '/index.php', '2012-10-18 01:44:49', '2012-10-18 01:44:50', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4906, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/lorem-ipsum-dolor-amet-consectetur-adipiscing-elit-a-133.html', '/article_info.php', '2012-10-18 01:12:12', '2012-10-18 01:12:12', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4904, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_3_4&amp;currency=CAD&amp;amp;amp;currency=CAD&amp;amp;page=4', '/index.php', '2012-10-17 23:52:32', '2012-10-17 23:52:33', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4905, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=AUD&amp;amp;currency=CAD&amp;currency=CAD', '/index.php', '2012-10-18 00:51:28', '2012-10-18 00:51:29', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4902, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-10-17 23:03:40', '2012-10-17 23:03:40', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4903, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=CAD', '/index.php', '2012-10-17 23:15:38', '2012-10-17 23:15:39', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4901, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=AUD&amp;amp;currency=CAD&amp;currency=AUD', '/index.php', '2012-10-17 22:39:23', '2012-10-17 22:39:24', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4900, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=AUD&amp;amp;currency=AUD&amp;currency=CNY', '/index.php', '2012-10-17 21:54:25', '2012-10-17 21:54:26', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4898, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_3_4&amp;page=1&amp;amp;currency=INR&amp;currency=GBP', '/index.php', '2012-10-17 19:42:56', '2012-10-17 20:16:18', 12, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4899, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=AUD&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-10-17 20:54:21', '2012-10-17 20:54:22', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4896, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/gift-voucher-p-514.html', '/article_info.php', '2012-10-17 17:46:07', '2012-10-17 18:06:40', 3, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4897, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/root/integer-nulla-tellus-commodo-viverra-n-29.html', '/wishlist.php', '2012-10-17 18:38:37', '2012-10-17 19:04:01', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4895, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=AUD&amp;amp;currency=CNY&amp;currency=CNY', '/index.php', '2012-10-17 16:51:12', '2012-10-17 17:15:21', 7, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4894, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CNY&amp;currency=CNY', '/index.php', '2012-10-17 15:40:11', '2012-10-17 15:40:11', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4893, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CNY&amp;currency=CAD', '/index.php', '2012-10-17 14:52:50', '2012-10-17 14:52:51', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4891, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=CNY', '/index.php', '2012-10-17 12:40:48', '2012-10-17 12:40:49', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4892, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CNY&amp;currency=AUD', '/allprods.php', '2012-10-17 13:47:41', '2012-10-17 13:50:35', 3, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4890, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=AUD', '/index.php', '2012-10-17 12:09:44', '2012-10-17 12:09:45', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4888, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=CAD&amp;amp;currency=AUD&amp;currency=CNY', '/index.php', '2012-10-17 10:06:30', '2012-10-17 10:25:31', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4889, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_3_4&amp;page=1&amp;amp;currency=INR&amp;currency=GBP', '/index.php', '2012-10-17 11:01:19', '2012-10-17 11:25:39', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4887, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/lorem-ipsum-dolor-amet-consectetur-adipiscing-elit-a-133.html', '/article_info.php', '2012-10-17 09:02:13', '2012-10-17 09:02:13', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4886, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=CAD&amp;amp;currency=AUD&amp;currency=AUD', '/index.php', '2012-10-17 07:56:32', '2012-10-17 09:09:17', 12, 'false', 'false', 'a:2:{i:4;i:1;i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4884, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=AUD&amp;amp;currency=CNY&amp;currency=AUD', '/index.php', '2012-10-17 04:58:15', '2012-10-17 04:58:16', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4885, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=21_25_27_26', '/index.php', '2012-10-17 06:08:11', '2012-10-17 06:08:12', 2, 'false', 'false', 'a:1:{i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4883, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=AUD&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-10-17 03:07:37', '2012-10-17 03:07:38', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4882, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;amp;page=6&amp;amp;amp;amp;currency=AUD&amp;amp;currency=CAD&amp;currency=CNY', '/index.php', '2012-10-17 01:17:01', '2012-10-17 01:17:02', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4880, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=4&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-16 21:19:03', '2012-10-16 21:19:04', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4881, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_3_4&amp;page=1&amp;amp;currency=GBP&amp;currency=EUR', '/index.php', '2012-10-16 21:59:35', '2012-10-16 22:37:59', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4879, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=CAD&products_id=513&cPath=22_25_27', '/index.php', '2012-10-16 20:07:53', '2012-10-16 20:41:33', 5, 'false', 'false', 'a:2:{i:27;i:1;i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4877, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/allprods.php?fl=K', '/allprods.php', '2012-10-16 17:17:06', '2012-10-16 17:17:06', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4878, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_3_4&amp;page=1&amp;amp;currency=GBP&amp;currency=EUR', '/wishlist.php', '2012-10-16 19:05:37', '2012-10-16 19:35:38', 5, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4875, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/shopping_cart.php?currency=INR&reviews_id=31', '/email_for_price.php', '2012-10-16 12:59:43', '2012-10-16 13:35:58', 4, 'false', 'false', 'a:1:{i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4876, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/allprods.php?fl=F', '/allprods.php', '2012-10-16 16:37:24', '2012-10-16 16:37:24', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4874, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/shopping_cart.php?currency=INR&reviews_id=30', '/shopping_cart.php', '2012-10-16 12:29:14', '2012-10-16 12:29:14', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4873, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=4&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-16 11:33:46', '2012-10-16 11:33:48', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4871, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/shopping_cart.php?currency=CNY&reviews_id=31', '/shopping_cart.php', '2012-10-16 07:06:15', '2012-10-16 07:59:22', 5, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4872, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=&amp;amp;manufacturers_id=1&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=AUD', '/index.php', '2012-10-16 10:01:19', '2012-10-16 11:00:45', 14, 'false', 'false', 'a:2:{s:0:"";i:1;i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4870, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/shopping_cart.php?currency=CNY&reviews_id=30', '/shopping_cart.php', '2012-10-16 06:32:25', '2012-10-16 06:32:25', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4868, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_reviews.php?currency=INR&products_id=338&amp;reviews_id=31&amp;amp;currency=CAD', '/shopping_cart.php', '2012-10-16 01:03:55', '2012-10-16 02:02:53', 5, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4869, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-16 05:09:51', '2012-10-16 05:09:53', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4867, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_reviews.php?currency=INR&products_id=338&amp;reviews_id=31&amp;amp;currency=AUD', '/reviews.php', '2012-10-16 00:14:22', '2012-10-16 00:14:23', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4865, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_reviews.php?currency=INR&products_id=338&amp;reviews_id=30&amp;amp;currency=CAD', '/product_reviews.php', '2012-10-15 21:21:39', '2012-10-15 21:21:39', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4866, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-10-15 22:06:45', '2012-10-15 22:06:45', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4864, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_reviews.php?currency=INR&products_id=338&amp;reviews_id=30&amp;amp;currency=AUD', '/reviews.php', '2012-10-15 20:50:12', '2012-10-15 20:50:13', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4863, '187.184.116.116', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:16.0) Gecko/20100101 Firefox/16.0', '', '', 0, 0, 'http://dev.cartstore.com/manage/coupon_admin.php', 'action=update_confirm&oldaction=new&cid=', '/', '/checkout_payment.php', '2012-10-15 20:24:08', '2012-10-15 20:29:59', 31, 'true', 'false', 'b:0;', '', 'a:1:{i:508;a:1:{s:3:"qty";i:1;}}', 66);
INSERT INTO `supertracker` VALUES(4861, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_2_11_4&amp;currency=CAD&amp;amp;amp;currency=CAD&amp;amp;page=6', '/index.php', '2012-10-15 17:50:32', '2012-10-15 17:50:33', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4862, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_reviews.php?currency=GBP&products_id=338&amp;reviews_id=31&amp;amp;currency=CAD', '/reviews.php', '2012-10-15 18:46:44', '2012-10-15 19:47:19', 6, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4860, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=4&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-15 15:35:00', '2012-10-15 15:35:01', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4859, '95.108.151.244', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'ru', 'Russian Federation', 0, 0, '', '', '/-p-515.html', '/product_info.php', '2012-10-15 14:16:41', '2012-10-15 14:17:04', 12, 'false', 'false', 'a:1:{i:27;i:1;}', '*515?*508?*518?', '', 0);
INSERT INTO `supertracker` VALUES(4857, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_reviews.php?currency=GBP&products_id=320&amp;reviews_id=32&amp;amp;currency=AUD', '/product_reviews.php', '2012-10-15 09:03:30', '2012-10-15 09:03:30', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4858, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_reviews.php?currency=GBP&products_id=320&amp;reviews_id=32&amp;amp;currency=CNY', '/index.php', '2012-10-15 11:34:37', '2012-10-15 14:13:43', 20, 'false', 'false', 'a:2:{s:0:"";i:1;i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4855, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/integer-nulla-tellus-commodo-viverra-n-29.html', '/newsdesk_info.php', '2012-10-15 05:57:54', '2012-10-15 05:57:54', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4856, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_reviews.php?currency=EUR&products_id=338&amp;reviews_id=30&amp;amp;currency=AUD', '/reviews.php', '2012-10-15 07:13:25', '2012-10-15 08:20:58', 8, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4854, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/', '/product_info.php', '2012-10-15 00:43:31', '2012-10-15 02:12:24', 10, 'false', 'false', 'a:1:{i:4;i:1;}', '*402?', '', 0);
INSERT INTO `supertracker` VALUES(4852, '65.60.9.170', 'Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.10.289 Version/12.00', 'us', 'United States', 0, 0, 'http://dev.cartstore.com/index.php', '', '/index.php', '/index.php', '2012-10-14 21:59:26', '2012-10-14 21:59:26', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4853, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_reviews.php?currency=CNY&products_id=338&amp;reviews_id=31&amp;amp;currency=AUD', '/reviews.php', '2012-10-15 00:10:06', '2012-10-15 00:10:06', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4851, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_reviews.php?currency=CNY&products_id=338&amp;reviews_id=30&amp;amp;currency=CAD', '/reviews.php', '2012-10-14 20:00:51', '2012-10-14 20:00:52', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4850, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_reviews.php?currency=CNY&products_id=320&amp;reviews_id=32&amp;amp;currency=CNY', '/product_reviews.php', '2012-10-14 18:47:28', '2012-10-14 19:05:49', 3, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4849, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_3_4&amp;page=1&amp;amp;currency=GBP&amp;currency=EUR', '/index.php', '2012-10-14 17:14:03', '2012-10-14 18:02:40', 10, 'false', 'false', 'a:1:{i:4;i:1;}', '*382?*392?', '', 0);
INSERT INTO `supertracker` VALUES(4848, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=CNY&products_id=519&cPath=29', '/product_info.php', '2012-10-14 13:11:32', '2012-10-14 16:34:38', 52, 'false', 'false', 'a:2:{s:0:"";i:1;i:4;i:1;}', '*519?*515?*516?*327?*517?*321?*325?*328?*338?*371?', '', 0);
INSERT INTO `supertracker` VALUES(4847, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_reviews.php?currency=CAD&products_id=338&amp;reviews_id=30&amp;amp;currency=AUD', '/product_reviews.php', '2012-10-14 12:24:08', '2012-10-14 12:24:08', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4845, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_reviews.php?currency=AUD&products_id=338&amp;reviews_id=31&amp;amp;currency=CAD', '/reviews.php', '2012-10-14 06:40:27', '2012-10-14 07:18:00', 5, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4846, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_reviews.php?currency=CNY&products_id=320&amp;reviews_id=32&amp;amp;currency=AUD', '/reviews.php', '2012-10-14 11:38:59', '2012-10-14 11:38:59', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4844, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_reviews.php?currency=AUD&products_id=338&amp;reviews_id=31&amp;amp;currency=AUD', '/reviews.php', '2012-10-14 06:05:54', '2012-10-14 06:05:55', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4842, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/tract-sample-product-p-518.html', '/product_info.php', '2012-10-14 03:00:13', '2012-10-14 03:00:13', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4843, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=CNY&products_id=517&cPath=', '/product_info.php', '2012-10-14 03:59:26', '2012-10-14 05:17:13', 12, 'false', 'false', 'a:1:{i:4;i:1;}', '*517?*515?', '', 0);
INSERT INTO `supertracker` VALUES(4841, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_reviews.php?currency=AUD&products_id=338&amp;reviews_id=30&amp;amp;currency=CAD', '/product_info.php', '2012-10-14 02:06:20', '2012-10-14 03:28:25', 11, 'false', 'false', 'b:0;', '*517?*519?*422?*515?*516?', '', 0);
INSERT INTO `supertracker` VALUES(4839, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_reviews.php?currency=AUD&products_id=320&amp;reviews_id=32&amp;amp;currency=CNY', '/product_reviews.php', '2012-10-14 00:29:34', '2012-10-14 00:29:34', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4840, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;amp;page=4&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=GBP', '/reviews.php', '2012-10-14 01:03:49', '2012-10-14 01:08:17', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4837, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=INR&products_id=516&cPath=', '/product_info.php', '2012-10-13 22:05:20', '2012-10-13 22:05:21', 2, 'false', 'false', 'b:0;', '*516?', '', 0);
INSERT INTO `supertracker` VALUES(4838, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=USD&products_id=520&cPath=29', '/wishlist.php', '2012-10-13 23:39:33', '2012-10-13 23:39:33', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4835, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=USD&products_id=405&cPath=', '/product_info.php', '2012-10-13 19:51:54', '2012-10-13 19:51:55', 2, 'false', 'false', 'b:0;', '*405?', '', 0);
INSERT INTO `supertracker` VALUES(4836, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_reviews.php?currency=AUD&products_id=320&amp;reviews_id=32&amp;amp;currency=AUD', '/product_reviews.php', '2012-10-13 21:09:21', '2012-10-13 21:28:42', 3, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4834, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=USD&products_id=402&cPath=', '/product_info.php', '2012-10-13 18:53:48', '2012-10-13 18:53:49', 2, 'false', 'false', 'b:0;', '*402?', '', 0);
INSERT INTO `supertracker` VALUES(4832, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=GBP&products_id=519&cPath=29', '/index.php', '2012-10-13 16:19:45', '2012-10-13 16:32:48', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '*519?', '', 0);
INSERT INTO `supertracker` VALUES(4833, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=USD&products_id=400&cPath=', '/product_info.php', '2012-10-13 17:55:42', '2012-10-13 17:55:43', 2, 'false', 'false', 'b:0;', '*400?', '', 0);
INSERT INTO `supertracker` VALUES(4831, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-13 15:14:11', '2012-10-13 15:40:24', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '*517?', '', 0);
INSERT INTO `supertracker` VALUES(4829, '46.105.54.85', 'Opera/9.80 (Windows NT 6.1; WOW64; U; ru) Presto/2.10.229 Version/11.64', '', '', 0, 0, 'http://dev.cartstore.com/index.php', '', '/index.php', '/index.php', '2012-10-13 14:26:35', '2012-10-13 14:26:35', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4830, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_2_11_4&amp;currency=CAD&amp;amp;amp;currency=CAD&amp;amp;page=6', '/index.php', '2012-10-13 14:40:05', '2012-10-13 14:40:06', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4827, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=USD&products_id=382&cPath=', '/product_info.php', '2012-10-13 12:22:39', '2012-10-13 13:02:11', 7, 'false', 'false', 'b:0;', '*382?*392?', '', 0);
INSERT INTO `supertracker` VALUES(4828, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=USD&products_id=396&cPath=', '/product_info.php', '2012-10-13 14:08:33', '2012-10-13 14:08:34', 2, 'false', 'false', 'b:0;', '*396?', '', 0);
INSERT INTO `supertracker` VALUES(4825, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=USD&products_id=370&cPath=', '/product_info.php', '2012-10-13 09:23:43', '2012-10-13 09:23:47', 2, 'false', 'false', 'b:0;', '*370?', '', 0);
INSERT INTO `supertracker` VALUES(4826, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=USD&products_id=371&cPath=', '/product_info.php', '2012-10-13 11:40:12', '2012-10-13 11:40:13', 2, 'false', 'false', 'b:0;', '*371?', '', 0);
INSERT INTO `supertracker` VALUES(4824, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=USD&products_id=354&cPath=', '/product_info.php', '2012-10-13 08:25:38', '2012-10-13 08:25:39', 2, 'false', 'false', 'b:0;', '*354?', '', 0);
INSERT INTO `supertracker` VALUES(4823, '66.249.73.35', 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_1 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8B117 Safari/6531.22.7 (compatible; Googlebot-Mobile/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-10-13 07:50:50', '2012-10-13 07:50:50', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4821, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=&amp;amp;manufacturers_id=1&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=AUD', '/index.php', '2012-10-13 04:44:18', '2012-10-13 06:51:24', 30, 'false', 'false', 'a:2:{s:0:"";i:1;i:4;i:1;}', '*519?*327?*515?*516?*328?', '', 0);
INSERT INTO `supertracker` VALUES(4822, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=USD&products_id=338&cPath=', '/product_info.php', '2012-10-13 07:27:36', '2012-10-13 07:27:37', 2, 'false', 'false', 'b:0;', '*338?', '', 0);
INSERT INTO `supertracker` VALUES(4820, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=21_27_26_27', '/product_info.php', '2012-10-13 02:55:52', '2012-10-13 03:49:47', 12, 'false', 'false', 'a:2:{i:27;i:1;i:4;i:1;}', '*321?*325?*517?', '', 0);
INSERT INTO `supertracker` VALUES(4818, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-10-12 13:35:15', '2012-10-12 13:35:15', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4819, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=INR&products_id=519&cPath=29', '/product_info.php', '2012-10-12 23:58:39', '2012-10-12 23:58:40', 2, 'false', 'false', 'b:0;', '*519?', '', 0);
INSERT INTO `supertracker` VALUES(4816, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-11 02:49:22', '2012-10-11 02:49:23', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4817, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_3_4&amp;page=1&amp;amp;currency=INR&amp;currency=CNY', '/index.php', '2012-10-11 03:27:34', '2012-10-11 03:46:41', 8, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4815, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/root/category-category-c-22_26.html', '/product_info.php', '2012-10-10 23:44:58', '2012-10-11 02:11:11', 21, 'false', 'false', 'a:5:{i:26;i:1;i:27;i:1;i:29;i:1;i:28;i:1;i:4;i:1;}', '*519?*422?*474?*515?*516?', '', 0);
INSERT INTO `supertracker` VALUES(4814, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=INR&products_id=515&cPath=', '/product_info.php', '2012-10-10 19:34:15', '2012-10-10 21:00:19', 8, 'false', 'false', 'a:1:{i:27;i:1;}', '*515?*516?*517?', '', 0);
INSERT INTO `supertracker` VALUES(4813, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=GBP&products_id=517&cPath=', '/index.php', '2012-10-10 17:42:39', '2012-10-10 18:53:06', 11, 'false', 'false', 'a:3:{i:26;i:1;i:24;i:1;i:27;i:1;}', '*517?*519?', '', 0);
INSERT INTO `supertracker` VALUES(4812, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=CNY&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-10 16:57:41', '2012-10-10 16:57:42', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4811, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-10 13:59:58', '2012-10-10 16:13:59', 32, 'false', 'false', 'a:1:{i:4;i:1;}', '*515?*516?*453?*464?', '', 0);
INSERT INTO `supertracker` VALUES(4810, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=EUR&products_id=519&cPath=29', '/product_info.php', '2012-10-10 13:08:30', '2012-10-10 13:08:31', 2, 'false', 'false', 'b:0;', '*519?', '', 0);
INSERT INTO `supertracker` VALUES(4809, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=EUR&products_id=517&cPath=', '/product_info.php', '2012-10-10 12:25:31', '2012-10-10 12:25:32', 2, 'false', 'false', 'b:0;', '*517?', '', 0);
INSERT INTO `supertracker` VALUES(4808, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=EUR&products_id=516&cPath=', '/product_info.php', '2012-10-10 11:43:14', '2012-10-10 11:43:15', 2, 'false', 'false', 'b:0;', '*516?', '', 0);
INSERT INTO `supertracker` VALUES(4807, '91.207.6.210', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)', 'ua', 'Ukraine', 0, 0, '', '', '/affiliate_signup.php', '/newsdesk_info.php', '2012-10-10 11:27:33', '2012-10-10 11:31:10', 113, 'false', 'false', 'a:8:{i:28;i:1;i:29;i:1;i:22;i:1;i:23;i:1;i:24;i:1;i:26;i:1;i:25;i:1;i:27;i:1;}', '*508?*507?*514?*512?*518?*513?*519?*511?', '', 0);
INSERT INTO `supertracker` VALUES(4806, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=EUR&products_id=515&cPath=', '/product_info.php', '2012-10-10 09:12:26', '2012-10-10 09:12:27', 2, 'false', 'false', 'b:0;', '*515?', '', 0);
INSERT INTO `supertracker` VALUES(4804, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=CNY&products_id=517&cPath=', '/product_info.php', '2012-10-10 07:38:12', '2012-10-10 07:38:13', 2, 'false', 'false', 'b:0;', '*517?', '', 0);
INSERT INTO `supertracker` VALUES(4805, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=CNY&products_id=519&cPath=29', '/product_info.php', '2012-10-10 08:41:02', '2012-10-10 08:41:03', 2, 'false', 'false', 'b:0;', '*519?', '', 0);
INSERT INTO `supertracker` VALUES(4803, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=CNY&products_id=516&cPath=', '/product_info.php', '2012-10-10 06:51:05', '2012-10-10 06:51:06', 2, 'false', 'false', 'b:0;', '*516?', '', 0);
INSERT INTO `supertracker` VALUES(4801, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=CAD&products_id=517&cPath=', '/product_info.php', '2012-10-10 02:37:02', '2012-10-10 03:52:44', 16, 'false', 'false', 'a:2:{s:0:"";i:1;i:4;i:1;}', '*517?*519?*422?', '', 0);
INSERT INTO `supertracker` VALUES(4802, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_3_4&amp;page=1&amp;amp;currency=INR&amp;currency=GBP', '/product_info.php', '2012-10-10 05:18:49', '2012-10-10 06:19:41', 12, 'false', 'false', 'a:1:{i:4;i:1;}', '*418?*515?', '', 0);
INSERT INTO `supertracker` VALUES(4799, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=CAD&products_id=516&cPath=', '/product_info.php', '2012-10-10 01:34:17', '2012-10-10 01:34:18', 2, 'false', 'false', 'b:0;', '*516?', '', 0);
INSERT INTO `supertracker` VALUES(4800, '69.175.51.106', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.165 Safari/535.19 YI', '', '', 0, 0, 'http://dev.cartstore.com/index.php', '', '/index.php', '/index.php', '2012-10-10 02:31:39', '2012-10-10 02:31:39', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4798, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=CAD&products_id=515&cPath=', '/product_info.php', '2012-10-10 00:47:12', '2012-10-10 00:47:13', 2, 'false', 'false', 'b:0;', '*515?', '', 0);
INSERT INTO `supertracker` VALUES(4797, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=CAD&products_id=493&cPath=', '/product_info.php', '2012-10-10 00:00:08', '2012-10-10 00:00:09', 2, 'false', 'false', 'b:0;', '*493?', '', 0);
INSERT INTO `supertracker` VALUES(4796, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=CAD&products_id=484&cPath=', '/product_info.php', '2012-10-09 21:45:21', '2012-10-09 21:45:23', 2, 'false', 'false', 'b:0;', '*484?', '', 0);
INSERT INTO `supertracker` VALUES(4795, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=CAD&products_id=475&cPath=', '/product_info.php', '2012-10-09 20:36:03', '2012-10-09 20:36:04', 2, 'false', 'false', 'b:0;', '*475?', '', 0);
INSERT INTO `supertracker` VALUES(4794, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=CAD&products_id=474&cPath=', '/product_info.php', '2012-10-09 20:02:52', '2012-10-09 20:02:54', 2, 'false', 'false', 'b:0;', '*474?', '', 0);
INSERT INTO `supertracker` VALUES(4793, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=AUD&products_id=519&cPath=29', '/product_info.php', '2012-10-09 18:48:53', '2012-10-09 19:25:25', 6, 'false', 'false', 'a:1:{i:27;i:1;}', '*519?*422?', '', 0);
INSERT INTO `supertracker` VALUES(4792, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=INR&amp;currency=AUD', '/product_info.php', '2012-10-09 15:35:48', '2012-10-09 17:22:32', 22, 'false', 'false', 'a:4:{i:4;i:1;i:24;i:1;i:27;i:1;i:26;i:1;}', '*517?', '', 0);
INSERT INTO `supertracker` VALUES(4790, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=AUD&products_id=515&cPath=', '/product_info.php', '2012-10-09 14:29:30', '2012-10-09 14:29:31', 2, 'false', 'false', 'b:0;', '*515?', '', 0);
INSERT INTO `supertracker` VALUES(4791, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=AUD&products_id=516&cPath=', '/product_info.php', '2012-10-09 15:03:47', '2012-10-09 15:03:47', 2, 'false', 'false', 'b:0;', '*516?', '', 0);
INSERT INTO `supertracker` VALUES(4789, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=AUD&products_id=484&cPath=', '/product_info.php', '2012-10-09 13:52:53', '2012-10-09 13:52:54', 2, 'false', 'false', 'b:0;', '*484?', '', 0);
INSERT INTO `supertracker` VALUES(4788, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=AUD&products_id=475&cPath=', '/product_info.php', '2012-10-09 13:16:09', '2012-10-09 13:16:10', 2, 'false', 'false', 'b:0;', '*475?', '', 0);
INSERT INTO `supertracker` VALUES(4786, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=AUD&products_id=464&cPath=', '/product_info.php', '2012-10-09 11:13:04', '2012-10-09 11:13:05', 2, 'false', 'false', 'b:0;', '*464?', '', 0);
INSERT INTO `supertracker` VALUES(4787, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=AUD&products_id=474&cPath=', '/product_info.php', '2012-10-09 12:21:14', '2012-10-09 12:21:15', 2, 'false', 'false', 'b:0;', '*474?', '', 0);
INSERT INTO `supertracker` VALUES(4785, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;page=6', '/index.php', '2012-10-09 09:58:05', '2012-10-09 09:58:07', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4783, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_2_11_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;page=6', '/product_info.php', '2012-10-09 05:52:10', '2012-10-09 06:12:08', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '*449?', '', 0);
INSERT INTO `supertracker` VALUES(4784, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=AUD&products_id=453&cPath=', '/index.php', '2012-10-09 06:49:34', '2012-10-09 07:34:28', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '*453?', '', 0);
INSERT INTO `supertracker` VALUES(4782, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_3_4&amp;page=1&amp;amp;currency=GBP&amp;currency=CAD', '/index.php', '2012-10-09 04:28:29', '2012-10-09 05:15:00', 12, 'false', 'false', 'a:1:{i:4;i:1;}', '*442?', '', 0);
INSERT INTO `supertracker` VALUES(4780, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=AUD&products_id=422&cPath=', '/product_info.php', '2012-10-09 00:53:46', '2012-10-09 00:53:47', 2, 'false', 'false', 'b:0;', '*422?', '', 0);
INSERT INTO `supertracker` VALUES(4781, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=AUD&products_id=425&cPath=', '/product_info.php', '2012-10-09 01:43:19', '2012-10-09 02:09:11', 4, 'false', 'false', 'b:0;', '*425?*438?', '', 0);
INSERT INTO `supertracker` VALUES(4778, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/links.php?currency=USD&category=0', '/links.php', '2012-10-08 23:41:38', '2012-10-08 23:41:38', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4779, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=AUD&products_id=418&cPath=', '/product_info.php', '2012-10-09 00:22:04', '2012-10-09 00:22:05', 2, 'false', 'false', 'b:0;', '*418?', '', 0);
INSERT INTO `supertracker` VALUES(4777, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=29', '/index.php', '2012-10-08 19:10:16', '2012-10-08 22:07:26', 22, 'false', 'false', 'a:4:{i:29;i:1;i:28;i:1;s:0:"";i:1;i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4775, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=INR&amp;currency=EUR', '/index.php', '2012-10-08 16:01:39', '2012-10-08 16:17:26', 4, 'false', 'false', 'a:2:{i:4;i:1;i:27;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4776, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/allprods.php?fl=M', '/index.php', '2012-10-08 16:57:28', '2012-10-08 18:04:56', 10, 'false', 'false', 'a:4:{i:27;i:1;i:4;i:1;i:26;i:1;i:24;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4774, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=21_27_24', '/index.php', '2012-10-08 14:18:49', '2012-10-08 14:18:49', 2, 'false', 'false', 'a:1:{i:24;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4772, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=21_25_27_24_27', '/index.php', '2012-10-08 12:28:27', '2012-10-08 12:28:28', 2, 'false', 'false', 'a:1:{i:27;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4773, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=21_25_27_26', '/index.php', '2012-10-08 13:12:36', '2012-10-08 13:34:41', 4, 'false', 'false', 'a:2:{i:26;i:1;i:27;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4770, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/test-attribute-p-520.html', '/wishlist.php', '2012-10-08 10:51:26', '2012-10-08 11:11:12', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4771, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=USD&products_id=519&cPath=29', '/wishlist.php', '2012-10-08 11:55:20', '2012-10-08 11:55:20', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4768, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=CAD&products_id=520&cPath=29', '/index.php', '2012-10-08 05:58:50', '2012-10-08 08:15:12', 13, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4769, '66.249.74.231', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-10-08 09:50:39', '2012-10-08 09:50:40', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4767, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/pellentesque-lacinia-quis-semper-fermentum-nisi-felis-blan-n-30.html?currency=CAD', '/wishlist.php', '2012-10-08 04:55:00', '2012-10-08 05:04:47', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4766, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/category-category-c-22_25_27_26.html?currency=AUD', '/allprods.php', '2012-10-08 03:52:29', '2012-10-08 04:02:17', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4765, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_4&amp;amp;page=4&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-10-08 00:12:13', '2012-10-08 01:11:24', 7, 'false', 'false', 'a:2:{i:4;i:1;i:24;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4764, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_4&amp;amp;page=4&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-07 23:36:42', '2012-10-07 23:36:43', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4763, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-10-07 22:15:40', '2012-10-07 23:01:13', 8, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4762, '95.108.151.244', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'ru', 'Russian Federation', 0, 0, '', '', '/-p-515.html', '/product_info.php', '2012-10-07 19:18:36', '2012-10-07 19:18:58', 12, 'false', 'false', 'a:1:{i:27;i:1;}', '*515?*508?*518?', '', 0);
INSERT INTO `supertracker` VALUES(4761, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-10-07 18:22:37', '2012-10-07 21:41:48', 33, 'false', 'false', 'a:4:{i:4;i:1;i:26;i:1;i:29;i:1;s:0:"";i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4760, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-10-07 17:45:49', '2012-10-07 17:45:50', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4759, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-07 16:58:44', '2012-10-07 16:58:45', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4757, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=29', '/index.php', '2012-10-07 11:37:38', '2012-10-07 13:25:41', 14, 'false', 'false', 'a:2:{i:29;i:1;i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4758, '184.57.87.71', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 4, 0, '', '', '/', '/checkout_payment.php', '2012-10-07 15:46:01', '2012-10-07 16:03:54', 64, 'true', 'false', 'b:0;', '', 'a:2:{i:508;a:1:{s:3:"qty";s:1:"1";}s:11:"520{2}3{1}4";a:2:{s:3:"qty";s:1:"5";s:10:"attributes";a:2:{i:2;s:1:"3";i:1;s:1:"4";}}}', 94);
INSERT INTO `supertracker` VALUES(4756, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-10-07 09:34:20', '2012-10-07 10:46:24', 10, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4755, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=GBP&products_id=519&cPath=29', '/index.php', '2012-10-07 08:20:15', '2012-10-07 08:41:17', 3, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4754, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/privacy-policy-a-114.html', '/article_info.php', '2012-10-07 06:07:12', '2012-10-07 06:07:12', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4753, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_3_4&amp;page=1&amp;amp;currency=INR&amp;currency=CNY', '/index.php', '2012-10-07 05:17:45', '2012-10-07 07:03:59', 12, 'false', 'false', 'a:2:{i:4;i:1;i:27;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4751, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-10-07 01:38:52', '2012-10-07 01:38:52', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4752, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_3_4&amp;page=1&amp;amp;currency=INR&amp;currency=CAD', '/index.php', '2012-10-07 04:16:05', '2012-10-07 04:16:07', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4750, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_3_4&amp;page=1&amp;amp;currency=INR&amp;currency=AUD', '/index.php', '2012-10-07 01:29:06', '2012-10-07 01:29:07', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4748, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=21_27_26_27', '/index.php', '2012-10-06 23:17:32', '2012-10-06 23:33:56', 4, 'false', 'false', 'a:2:{i:27;i:1;i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4749, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_3_4&amp;page=1&amp;amp;currency=GBP&amp;currency=GBP', '/index.php', '2012-10-07 00:25:18', '2012-10-07 00:50:50', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4747, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=GBP&amp;currency=AUD', '/index.php', '2012-10-06 21:20:41', '2012-10-06 22:32:59', 20, 'false', 'false', 'a:4:{i:4;i:1;i:26;i:1;i:27;i:1;i:24;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4746, '66.249.73.35', 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_1 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8B117 Safari/6531.22.7 (compatible; Googlebot-Mobile/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-10-06 20:28:18', '2012-10-06 20:28:18', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4745, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-10-06 19:49:36', '2012-10-06 19:49:37', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4744, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_3_4&amp;page=1&amp;amp;currency=EUR&amp;currency=INR', '/index.php', '2012-10-06 18:45:47', '2012-10-06 19:11:21', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4742, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_3_4&amp;page=1&amp;amp;currency=EUR&amp;currency=EUR', '/index.php', '2012-10-06 17:29:06', '2012-10-06 17:29:07', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4743, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_3_4&amp;page=1&amp;amp;currency=EUR&amp;currency=GBP', '/index.php', '2012-10-06 18:07:26', '2012-10-06 18:07:27', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4741, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/cookie_usage.php', '/cookie_usage.php', '2012-10-06 17:14:19', '2012-10-06 17:14:19', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4740, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_3_4&amp;page=1&amp;amp;currency=EUR&amp;currency=CAD', '/index.php', '2012-10-06 16:50:46', '2012-10-06 16:50:47', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4738, '184.57.87.71', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 4, 0, 'http://dev.cartstore.com/test-p-519.html', '', '/test-attribute-p-520.html', '/checkout_payment.php', '2012-10-06 15:25:00', '2012-10-06 16:24:15', 42, 'true', 'false', 'b:0;', '*520?', 'a:2:{i:508;a:1:{s:3:"qty";s:1:"1";}s:11:"520{2}3{1}4";a:2:{s:3:"qty";s:1:"5";s:10:"attributes";a:2:{i:2;s:1:"3";i:1;s:1:"4";}}}', 94);
INSERT INTO `supertracker` VALUES(4739, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=21_25_27_24', '/logoff.php', '2012-10-06 15:53:35', '2012-10-06 16:17:34', 14, 'true', 'false', 'a:1:{i:24;i:1;}', '', 'a:1:{i:508;a:1:{s:3:"qty";i:1;}}', 66);
INSERT INTO `supertracker` VALUES(4737, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=CNY&amp;currency=AUD', '/index.php', '2012-10-06 14:49:54', '2012-10-06 14:56:48', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4736, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=CNY&amp;currency=GBP', '/index.php', '2012-10-06 14:08:35', '2012-10-06 14:08:36', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4735, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-06 12:00:29', '2012-10-06 13:13:26', 20, 'false', 'false', 'a:3:{i:4;i:1;s:0:"";i:1;i:27;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4733, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/root/category-category-c-22_25_27.html', '/index.php', '2012-10-06 07:41:29', '2012-10-06 08:22:05', 3, 'false', 'false', 'a:2:{i:22;i:1;i:25;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4734, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=INR&amp;currency=GBP', '/index.php', '2012-10-06 10:07:22', '2012-10-06 11:23:50', 26, 'false', 'false', 'a:2:{i:4;i:1;i:29;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4732, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=GBP&amp;amp;page=4', '/index.php', '2012-10-06 07:07:12', '2012-10-06 07:07:13', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4730, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_2_11_4&amp;page=6&amp;amp;currency=AUD&amp;currency=INR', '/index.php', '2012-10-06 05:53:21', '2012-10-06 05:53:22', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4731, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_2_11_4&amp;page=6&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-06 06:30:17', '2012-10-06 06:30:17', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4728, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-10-06 04:38:04', '2012-10-06 04:38:04', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4729, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_2_11_4&amp;currency=CNY&amp;amp;amp;currency=CAD&amp;amp;page=6', '/index.php', '2012-10-06 04:39:38', '2012-10-06 04:39:39', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4727, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=CNY&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-06 03:50:46', '2012-10-06 03:50:47', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4725, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_2_11_4&amp;currency=CNY&amp;amp;amp;currency=AUD&amp;amp;page=6', '/index.php', '2012-10-06 01:33:34', '2012-10-06 01:33:35', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4726, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-06 02:46:55', '2012-10-06 02:46:57', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4724, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_2_11_4&amp;currency=CAD&amp;amp;amp;currency=AUD&amp;amp;page=6', '/index.php', '2012-10-06 00:32:02', '2012-10-06 01:00:27', 5, 'false', 'false', 'a:2:{i:4;i:1;i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4723, '184.173.183.171', 'AddThis.com robot tech.support@clearspring.com', '', '', 0, 0, '', '', '/test-prodcuts-c-22.html?osCsid=e905878506bcb4ce8c2d782456186f6f', '/product_info.php', '2012-10-05 22:37:48', '2012-10-05 22:48:06', 2, 'false', 'false', 'b:0;', '*507?', '', 0);
INSERT INTO `supertracker` VALUES(4721, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=21_27_26_27', '/index.php', '2012-10-05 19:20:04', '2012-10-05 19:20:05', 2, 'false', 'false', 'a:1:{i:27;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4722, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=29', '/index.php', '2012-10-05 22:33:21', '2012-10-05 23:59:01', 18, 'false', 'false', 'a:5:{i:29;i:1;i:4;i:1;i:27;i:1;i:26;i:1;i:24;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4720, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=21_27_24', '/index.php', '2012-10-05 17:53:30', '2012-10-05 18:43:20', 6, 'false', 'false', 'a:3:{i:24;i:1;i:27;i:1;i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4719, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=21_25_27_26_27', '/index.php', '2012-10-05 17:16:46', '2012-10-05 17:16:46', 2, 'false', 'false', 'a:1:{i:27;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4717, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-05 16:19:50', '2012-10-05 16:21:38', 4, 'false', 'false', 'a:2:{i:4;i:1;i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4718, '184.57.87.71', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 0, 0, '', '', '/', '/product_info.php', '2012-10-05 16:52:14', '2012-10-05 17:07:18', 16, 'false', 'false', 'a:3:{i:22;i:1;i:25;i:1;i:29;i:1;}', '*508?*507?*519?*520?', '', 0);
INSERT INTO `supertracker` VALUES(4716, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=INR&amp;currency=EUR', '/index.php', '2012-10-05 15:26:54', '2012-10-05 15:26:54', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4715, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-05 13:59:42', '2012-10-05 13:59:42', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4713, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=AUD&products_id=418&cPath=1_3_4', '/index.php', '2012-10-05 11:45:39', '2012-10-05 13:16:35', 19, 'false', 'false', 'a:4:{s:0:"";i:1;i:4;i:1;i:24;i:1;i:27;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4714, '24.127.224.105', '', 'us', 'United States', 0, 0, '', '', '/index.php', '/index.php', '2012-10-05 11:50:00', '2012-10-05 11:50:00', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4712, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=21_27_26', '/newsdesk_info.php', '2012-10-05 10:57:18', '2012-10-05 11:03:57', 3, 'false', 'false', 'a:1:{i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4711, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;amp;page=4&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-05 10:23:50', '2012-10-05 10:23:51', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4710, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/category-category-c-21_26.html', '/index.php', '2012-10-05 09:45:04', '2012-10-05 09:46:49', 3, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4709, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/password_forgotten.php', '/password_forgotten.php', '2012-10-05 09:01:18', '2012-10-05 09:01:18', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4708, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/allprods.php?fl=y', '/allprods.php', '2012-10-05 08:29:31', '2012-10-05 08:29:31', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4707, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/allprods.php?fl=a', '/index.php', '2012-10-05 07:36:56', '2012-10-05 07:57:45', 2, 'false', 'false', 'a:1:{i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4706, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-05 05:55:11', '2012-10-05 06:43:46', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4705, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-10-05 05:18:33', '2012-10-05 05:18:34', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4704, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_2_11_4&amp;currency=CAD&amp;amp;amp;currency=AUD&amp;amp;page=6', '/index.php', '2012-10-05 03:55:33', '2012-10-05 04:03:49', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4703, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_2_11_4&amp;currency=CNY&amp;amp;amp;currency=CAD&amp;amp;page=6', '/index.php', '2012-10-05 02:32:45', '2012-10-05 02:53:27', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4702, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-04 23:40:50', '2012-10-05 02:01:43', 20, 'false', 'false', 'a:3:{i:4;i:1;i:29;i:1;i:27;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4701, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-04 22:10:49', '2012-10-04 22:25:06', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4700, '184.173.183.171', 'AddThis.com robot tech.support@clearspring.com', '', '', 0, 0, '', '', '/test-attribute-p-520.html', '/product_info.php', '2012-10-04 20:42:17', '2012-10-04 20:42:17', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4699, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=21_25_27_26', '/index.php', '2012-10-04 19:05:45', '2012-10-04 19:05:46', 2, 'false', 'false', 'a:1:{i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4698, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-10-04 18:09:54', '2012-10-04 18:23:39', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4696, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=CNY&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-04 16:54:50', '2012-10-04 16:54:50', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4697, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/articles.php', '/index.php', '2012-10-04 17:25:00', '2012-10-04 17:31:30', 3, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4695, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-04 15:54:13', '2012-10-04 15:54:14', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4694, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_2_4&amp;currency=GBP&amp;amp;amp;currency=EUR&amp;amp;page=4', '/index.php', '2012-10-04 11:39:44', '2012-10-04 14:53:54', 24, 'false', 'false', 'a:3:{i:4;i:1;i:77;i:1;i:24;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4693, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=CNY&amp;amp;page=2', '/index.php', '2012-10-04 10:54:43', '2012-10-04 10:54:44', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4692, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_2_11_4&amp;page=6&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-04 10:09:42', '2012-10-04 10:09:43', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4691, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/allprods.php?fl=z', '/allprods.php', '2012-10-04 09:27:05', '2012-10-04 09:27:05', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4690, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/root/testing-cartstore-feeds-twitter-facebook-p-510.html', '/product_info.php', '2012-10-04 08:27:09', '2012-10-04 08:27:09', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4689, '187.184.116.116', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 0, 0, '', '', '/', '/checkout_payment.php', '2012-10-04 07:05:22', '2012-10-04 07:07:10', 17, 'true', 'false', 'a:1:{i:24;i:1;}', '', 'a:1:{i:507;a:1:{s:3:"qty";i:1;}}', 99);
INSERT INTO `supertracker` VALUES(4688, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/test-prodcuts-c-22.html', '/index.php', '2012-10-04 06:33:20', '2012-10-04 06:39:24', 3, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4687, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_2_11_4&amp;currency=CNY&amp;amp;amp;currency=CAD&amp;amp;page=6', '/index.php', '2012-10-04 05:54:23', '2012-10-04 05:54:24', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4686, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_2_11_4&amp;currency=CNY&amp;amp;amp;currency=AUD&amp;amp;page=6', '/index.php', '2012-10-04 05:09:23', '2012-10-04 05:09:25', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4685, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_2_11_4&amp;currency=CAD&amp;amp;amp;currency=CAD&amp;amp;page=6', '/index.php', '2012-10-04 04:09:24', '2012-10-04 04:09:54', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4684, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_2_11_4&amp;page=6&amp;amp;currency=AUD&amp;currency=INR', '/index.php', '2012-10-04 03:38:29', '2012-10-04 03:38:29', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4683, '46.105.54.85', 'Mozilla/5.0 (Windows NT 5.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1', '', '', 0, 0, 'http://dev.cartstore.com/index.php', '', '/index.php', '/index.php', '2012-10-04 03:29:38', '2012-10-04 03:29:38', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4682, '187.184.116.116', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 0, 2004, 'http://dev.cartstore.com/checkout_success.php', '', '/index.php', '/checkout_success.php', '2012-10-04 03:12:05', '2012-10-04 03:13:02', 9, 'true', 'true', 'b:0;', '', 'Array', 66);
INSERT INTO `supertracker` VALUES(4680, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 24, '', '', '/index.php?currency=GBP&cPath=1_2_11_4&amp;currency=CAD&amp;amp;amp;currency=AUD&amp;amp;page=6', '/checkout_success.php', '2012-10-04 00:42:56', '2012-10-04 02:38:33', 123, 'true', 'true', 'a:3:{i:4;i:1;s:0:"";i:1;i:26;i:1;}', '', 'Array', 99);
INSERT INTO `supertracker` VALUES(4681, '187.184.116.116', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 0, 0, 'http://dev.cartstore.com/checkout_success.php', '', '/index.php', '/create_account.php', '2012-10-04 01:15:58', '2012-10-04 01:39:23', 21, 'true', 'false', 'b:0;', '', 'a:1:{i:508;a:1:{s:3:"qty";i:1;}}', 66);
INSERT INTO `supertracker` VALUES(4679, '187.184.116.116', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 0, 21, 'http://dev.cartstore.com/manage/orders.php', '', '/', '/checkout_success.php', '2012-10-04 00:09:06', '2012-10-04 00:39:14', 46, 'true', 'true', 'a:2:{i:23;i:1;i:29;i:1;}', '', 'Array', 11);
INSERT INTO `supertracker` VALUES(4678, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_2_11_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;page=6', '/index.php', '2012-10-03 23:27:48', '2012-10-04 00:12:54', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4676, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=29', '/index.php', '2012-10-03 21:30:03', '2012-10-03 21:30:04', 2, 'false', 'false', 'a:1:{i:29;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4677, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=21_27_26_27', '/index.php', '2012-10-03 22:05:40', '2012-10-03 22:29:30', 4, 'false', 'false', 'a:2:{i:27;i:1;i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4674, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=29', '/index.php', '2012-10-03 19:32:53', '2012-10-03 19:53:54', 6, 'false', 'false', 'a:2:{i:29;i:1;i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4675, '187.184.116.116', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 0, 0, 'http://dev.cartstore.com/manage/coupon_admin.php', 'selected_box=gv_admin', '/', '/index.php', '2012-10-03 20:15:10', '2012-10-03 20:15:10', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4672, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=21_27_24', '/index.php', '2012-10-03 17:40:09', '2012-10-03 18:57:09', 20, 'false', 'false', 'a:4:{i:24;i:1;i:27;i:1;i:4;i:1;i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4673, '184.57.87.71', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 0, 0, '', '', '/test-attribute-p-520.html', '/product_info.php', '2012-10-03 18:24:30', '2012-10-03 18:56:06', 14, 'false', 'false', 'b:0;', '*520?', '', 0);
INSERT INTO `supertracker` VALUES(4670, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;currency=CNY&amp;amp;amp;currency=AUD&amp;amp;page=6', '/index.php', '2012-10-03 14:45:46', '2012-10-03 14:55:28', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4671, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=21_25_27_24_27', '/index.php', '2012-10-03 15:32:32', '2012-10-03 16:55:03', 29, 'false', 'false', 'a:4:{i:27;i:1;i:4;i:1;i:26;i:1;i:24;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4669, '187.184.116.116', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 0, 0, 'http://dev.cartstore.com/manage/categories.php', 'cPath=29&pID=520&action=new_product', '/', '/ext/estimated_shipping.php', '2012-10-03 06:37:58', '2012-10-03 06:42:04', 9, 'true', 'false', 'a:1:{i:29;i:1;}', '*519?*520?', 'a:1:{i:508;a:1:{s:3:"qty";i:1;}}', 66);
INSERT INTO `supertracker` VALUES(4667, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-03 03:28:40', '2012-10-03 04:02:32', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4668, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=INR', '/product_info.php', '2012-10-03 04:44:46', '2012-10-03 12:18:54', 53, 'false', 'false', 'a:4:{i:4;i:1;i:27;i:1;i:24;i:1;i:28;i:1;}', '*520?*513?', '', 0);
INSERT INTO `supertracker` VALUES(4666, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-03 02:12:45', '2012-10-03 02:34:55', 6, 'false', 'false', 'a:2:{i:4;i:1;i:24;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4665, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_2_11_4&amp;currency=CNY&amp;amp;amp;currency=CAD&amp;amp;page=6', '/index.php', '2012-10-02 23:25:24', '2012-10-03 01:38:26', 32, 'false', 'false', 'a:2:{i:4;i:1;s:0:"";i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4664, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-02 22:29:02', '2012-10-02 22:29:04', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4662, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_2_11_4&amp;currency=CAD&amp;amp;amp;currency=AUD&amp;amp;page=6', '/index.php', '2012-10-02 18:52:16', '2012-10-02 18:52:17', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4663, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_2_11_4&amp;currency=CAD&amp;amp;amp;currency=CAD&amp;amp;page=6', '/index.php', '2012-10-02 21:27:05', '2012-10-02 21:56:49', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4661, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_2_11_4&amp;currency=AUD&amp;amp;amp;currency=CAD&amp;amp;page=6', '/index.php', '2012-10-02 18:22:11', '2012-10-02 18:22:12', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4659, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=21_27_26_27', '/index.php', '2012-10-02 16:36:54', '2012-10-02 16:36:55', 2, 'false', 'false', 'a:1:{i:27;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4660, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=29', '/index.php', '2012-10-02 17:22:02', '2012-10-02 17:37:05', 4, 'false', 'false', 'a:2:{i:29;i:1;i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4658, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=21_27_24_27', '/index.php', '2012-10-02 15:34:19', '2012-10-02 15:58:23', 4, 'false', 'false', 'a:2:{i:27;i:1;i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4656, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=21_25_27_24_27', '/index.php', '2012-10-02 10:28:40', '2012-10-02 13:11:39', 33, 'false', 'false', 'a:4:{i:27;i:1;i:26;i:1;i:4;i:1;i:24;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4657, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-10-02 13:41:55', '2012-10-02 13:41:56', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4655, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;amp;page=4&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-02 05:04:45', '2012-10-02 05:53:59', 8, 'false', 'false', 'a:2:{i:4;i:1;i:24;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4653, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-02 01:14:31', '2012-10-02 01:44:07', 14, 'false', 'false', 'a:3:{i:4;i:1;i:27;i:1;i:29;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4654, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=CNY&amp;currency=CAD', '/index.php', '2012-10-02 02:19:39', '2012-10-02 04:15:25', 18, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4652, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=INR&amp;currency=EUR', '/index.php', '2012-10-01 23:24:41', '2012-10-02 00:28:39', 10, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4650, '184.57.87.71', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 4, 20, '', '', '/', '/checkout_success.php', '2012-10-01 19:12:23', '2012-10-01 20:12:58', 77, 'true', 'true', 'b:0;', '', 'Array', 132);
INSERT INTO `supertracker` VALUES(4651, '66.249.71.226', 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_1 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8B117 Safari/6531.22.7 (compatible; Googlebot-Mobile/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-10-01 20:35:44', '2012-10-01 20:35:44', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4648, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=EUR&products_id=519&cPath=29', '/wishlist.php', '2012-10-01 17:48:22', '2012-10-01 18:06:40', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4649, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=INR&products_id=519&cPath=29', '/wishlist.php', '2012-10-01 18:38:58', '2012-10-01 18:38:58', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4647, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=CNY&products_id=519&cPath=29', '/wishlist.php', '2012-10-01 17:11:45', '2012-10-01 17:11:45', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4645, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=21_25_27_26_27', '/index.php', '2012-10-01 15:03:44', '2012-10-01 15:28:08', 7, 'false', 'false', 'a:2:{i:27;i:1;i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4646, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=AUD&products_id=519&cPath=29', '/wishlist.php', '2012-10-01 16:14:32', '2012-10-01 16:14:32', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4644, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-01 13:25:26', '2012-10-01 14:31:15', 10, 'false', 'false', 'a:3:{i:4;i:1;s:0:"";i:1;i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4643, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-10-01 13:18:54', '2012-10-01 13:18:54', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4642, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-01 12:30:29', '2012-10-01 12:30:30', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4640, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-01 10:40:36', '2012-10-01 10:40:37', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4641, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-01 11:17:14', '2012-10-01 11:17:15', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4639, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-10-01 10:03:59', '2012-10-01 10:04:00', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4638, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-10-01 09:23:22', '2012-10-01 09:27:22', 3, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4637, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=CNY&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-01 06:39:56', '2012-10-01 06:39:57', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4636, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-01 05:47:53', '2012-10-01 05:47:53', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4635, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-10-01 03:16:23', '2012-10-01 04:21:07', 16, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4634, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=CAD&amp;currency=AUD', '/index.php', '2012-10-01 02:27:24', '2012-10-01 02:27:25', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4633, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-10-01 00:55:41', '2012-10-01 00:55:42', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4632, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_2_11_4&amp;page=6&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-30 23:33:26', '2012-09-30 23:33:27', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4631, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_2_11_4&amp;page=6&amp;amp;currency=AUD&amp;currency=INR', '/index.php', '2012-09-30 22:58:43', '2012-09-30 22:58:44', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4630, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_2_11_4&amp;currency=CNY&amp;amp;amp;currency=AUD&amp;amp;page=6', '/index.php', '2012-09-30 21:49:19', '2012-09-30 22:06:41', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4629, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-30 16:53:33', '2012-09-30 17:18:35', 8, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4628, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=GBP&amp;currency=AUD', '/index.php', '2012-09-30 15:52:29', '2012-09-30 16:23:01', 14, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4627, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=29', '/index.php', '2012-09-30 15:15:44', '2012-09-30 15:15:45', 2, 'false', 'false', 'a:1:{i:29;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4625, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=21_27_24', '/index.php', '2012-09-30 10:56:47', '2012-09-30 10:56:47', 2, 'false', 'false', 'a:1:{i:24;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4626, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=21_27_24_27', '/index.php', '2012-09-30 11:31:36', '2012-09-30 12:07:29', 6, 'false', 'false', 'a:2:{i:27;i:1;i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4624, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=21_25_27_26', '/index.php', '2012-09-30 09:47:09', '2012-09-30 10:04:34', 4, 'false', 'false', 'a:2:{i:26;i:1;i:27;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4623, '66.249.71.226', 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_1 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8B117 Safari/6531.22.7 (compatible; Googlebot-Mobile/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/allprods.php?fl=d', '/allprods.php', '2012-09-30 09:10:58', '2012-09-30 09:10:58', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4622, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=INR&amp;currency=EUR', '/index.php', '2012-09-30 09:03:10', '2012-09-30 09:03:10', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4621, '95.108.151.244', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'ru', 'Russian Federation', 0, 0, '', '', '/', '/index.php', '2012-09-30 05:09:45', '2012-09-30 05:09:48', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4620, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=4&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-30 04:56:40', '2012-09-30 06:24:47', 20, 'false', 'false', 'a:4:{i:4;i:1;s:0:"";i:1;i:24;i:1;i:27;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4618, '66.249.71.226', 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_1 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8B117 Safari/6531.22.7 (compatible; Googlebot-Mobile/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/allprods.php?fl=t', '/allprods.php', '2012-09-30 03:31:11', '2012-09-30 03:31:11', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4619, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-30 04:04:26', '2012-09-30 04:04:27', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4616, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-30 01:08:46', '2012-09-30 01:08:47', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4617, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=EUR&amp;currency=CNY', '/index.php', '2012-09-30 02:59:39', '2012-09-30 02:59:41', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4615, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=CNY&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-30 00:33:57', '2012-09-30 00:33:58', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4614, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-29 23:59:08', '2012-09-29 23:59:08', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4613, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-29 23:24:19', '2012-09-29 23:24:20', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4612, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-29 21:17:45', '2012-09-29 21:17:47', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4611, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;currency=CAD&amp;amp;amp;currency=CAD&amp;amp;page=6', '/index.php', '2012-09-29 18:13:36', '2012-09-29 19:32:34', 18, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4610, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=INR', '/product_info.php', '2012-09-29 17:00:18', '2012-09-29 17:40:43', 15, 'false', 'false', 'a:3:{i:4;i:1;i:27;i:1;i:77;i:1;}', '*511?', '', 0);
INSERT INTO `supertracker` VALUES(4608, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/allprods.php?fl=T', '/index.php', '2012-09-29 15:16:26', '2012-09-29 15:38:47', 5, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4609, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-29 16:13:34', '2012-09-29 16:13:35', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4607, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-29 12:16:25', '2012-09-29 12:16:27', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4605, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=INR&amp;currency=CNY', '/index.php', '2012-09-29 09:47:52', '2012-09-29 09:47:53', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4606, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=INR&amp;currency=EUR', '/index.php', '2012-09-29 10:48:24', '2012-09-29 11:35:07', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4604, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=INR&amp;currency=CAD', '/index.php', '2012-09-29 09:06:34', '2012-09-29 09:06:34', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4603, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-29 07:17:17', '2012-09-29 08:32:39', 16, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4601, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=EUR&amp;currency=INR', '/index.php', '2012-09-29 04:00:55', '2012-09-29 04:27:31', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4602, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=CNY&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-29 04:59:57', '2012-09-29 05:17:09', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4600, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=EUR&amp;currency=GBP', '/index.php', '2012-09-29 03:06:34', '2012-09-29 03:06:34', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4599, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=CNY&amp;currency=INR', '/index.php', '2012-09-28 21:08:39', '2012-09-28 22:50:29', 16, 'false', 'false', 'a:3:{i:4;i:1;s:0:"";i:1;i:27;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4598, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;currency=AUD&amp;amp;amp;currency=CAD&amp;amp;page=6', '/index.php', '2012-09-28 19:33:42', '2012-09-28 19:52:06', 12, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4597, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=29', '/index.php', '2012-09-28 17:57:29', '2012-09-28 18:08:00', 8, 'false', 'false', 'a:2:{i:4;i:1;i:29;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4595, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=CNY&amp;currency=AUD', '/index.php', '2012-09-28 16:23:36', '2012-09-28 16:43:57', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4596, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=CNY&amp;currency=CNY', '/index.php', '2012-09-28 17:15:40', '2012-09-28 17:15:41', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4594, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-28 10:42:57', '2012-09-28 11:22:40', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4593, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=4&amp;amp;currency=CAD&amp;currency=CNY', '/index.php', '2012-09-28 09:45:31', '2012-09-28 09:45:33', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4592, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-28 03:52:41', '2012-09-28 04:34:28', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4591, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-28 03:20:31', '2012-09-28 03:20:32', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4589, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;page=6&amp;amp;currency=AUD&amp;currency=INR', '/index.php', '2012-09-28 01:03:09', '2012-09-28 01:03:10', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4590, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;page=6&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-28 02:42:21', '2012-09-28 02:42:22', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4588, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;currency=CNY&amp;amp;amp;currency=CAD&amp;amp;page=6', '/index.php', '2012-09-27 23:57:04', '2012-09-27 23:57:05', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4587, '24.211.74.172', 'Mozilla/5.0 (iPhone; CPU iPhone OS 5_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9B176 Safari/7534.48.3', 'us', 'United States', 0, 0, '', '', '/donation-products-c-28.html', '/index.php', '2012-09-27 23:21:34', '2012-09-27 23:21:34', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4585, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;currency=CAD&amp;amp;amp;currency=CAD&amp;amp;page=6', '/index.php', '2012-09-27 22:36:24', '2012-09-27 22:36:25', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4586, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;currency=CNY&amp;amp;amp;currency=AUD&amp;amp;page=6', '/index.php', '2012-09-27 23:13:00', '2012-09-27 23:13:01', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4584, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=CNY&products_id=508&cPath=22_25_27', '/index.php', '2012-09-27 21:11:22', '2012-09-27 21:41:11', 8, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4582, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/privacy-policy-a-114.html', '/article_info.php', '2012-09-27 20:02:58', '2012-09-27 20:02:58', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4583, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/-c-21_53.html?currency=CAD', '/product_info.php', '2012-09-27 20:15:55', '2012-09-27 20:36:53', 5, 'false', 'false', 'a:2:{i:78;i:1;i:4;i:1;}', '*445?', '', 0);
INSERT INTO `supertracker` VALUES(4580, '94.19.191.183', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows 98; Win 9x 4.90)', 'ru', 'Russian Federation', 0, 0, 'http://dev.cartstore.com/index.php', '', '/index.php', '/index.php', '2012-09-27 17:45:49', '2012-09-27 17:45:49', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4581, '24.211.74.172', 'Mozilla/5.0 (iPhone; CPU iPhone OS 5_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9B176 Safari/7534.48.3', 'us', 'United States', 0, 0, '', '', '/donation-products-c-28.html', '/index.php', '2012-09-27 18:25:27', '2012-09-27 18:25:27', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4579, '187.184.116.116', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 0, 0, '', '', '/', '/index.php', '2012-09-27 17:05:18', '2012-09-27 17:09:47', 3, 'false', 'false', 'a:1:{i:28;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4577, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=21_27_26_27', '/index.php', '2012-09-27 14:26:32', '2012-09-27 14:29:32', 4, 'false', 'false', 'a:2:{i:27;i:1;i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4578, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/popup_search_help.php', '/index.php', '2012-09-27 15:43:15', '2012-09-27 16:46:27', 7, 'false', 'false', 'a:3:{i:29;i:1;s:0:"";i:1;i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4576, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=21_27_24_27', '/index.php', '2012-09-27 12:45:45', '2012-09-27 12:45:46', 2, 'false', 'false', 'a:1:{i:27;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4574, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-09-27 11:30:24', '2012-09-27 11:30:24', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4575, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=21_27_24', '/index.php', '2012-09-27 12:12:55', '2012-09-27 12:12:56', 2, 'false', 'false', 'a:1:{i:24;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4573, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=21_25_27_26_27', '/index.php', '2012-09-27 11:25:48', '2012-09-27 11:25:49', 2, 'false', 'false', 'a:1:{i:27;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4572, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=21_25_27_24_27', '/index.php', '2012-09-27 09:28:02', '2012-09-27 09:28:03', 2, 'false', 'false', 'a:1:{i:27;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4571, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=21_25_27_24', '/index.php', '2012-09-27 08:06:48', '2012-09-27 08:36:46', 4, 'false', 'false', 'a:2:{i:24;i:1;i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4570, '187.184.116.116', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 5, 17, 'http://dev.cartstore.com/manage/configuration.php', 'gID=1', '/', '/account_edit.php', '2012-09-27 05:53:48', '2012-09-27 05:54:48', 11, 'true', 'true', 'b:0;', '', '', 150);
INSERT INTO `supertracker` VALUES(4569, '187.184.116.116', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 5, 0, '', '', '/', '/checkout_shipping.php', '2012-09-27 04:41:15', '2012-09-27 04:41:59', 10, 'true', 'false', 'b:0;', '', 'a:1:{i:507;a:1:{s:3:"qty";s:1:"1";}}', 99);
INSERT INTO `supertracker` VALUES(4568, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-27 04:37:48', '2012-09-27 05:22:08', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4566, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/test-p-516.html', '/product_info.php', '2012-09-27 03:12:56', '2012-09-27 03:12:56', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4567, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-27 03:26:51', '2012-09-27 03:26:53', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4565, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-27 02:21:06', '2012-09-27 02:21:07', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4564, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/gift-card-p-513.html', '/product_info.php', '2012-09-27 00:08:23', '2012-09-27 00:08:23', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4563, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-26 21:21:56', '2012-09-26 22:12:32', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4562, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-26 20:50:50', '2012-09-26 20:50:51', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4561, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-26 20:10:13', '2012-09-26 20:10:14', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4560, '66.249.71.226', 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_1 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8B117 Safari/6531.22.7 (compatible; Googlebot-Mobile/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-09-26 18:23:04', '2012-09-26 18:23:04', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4558, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-26 15:54:48', '2012-09-26 16:13:05', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4559, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/allprods.php?fl=x', '/allprods.php', '2012-09-26 16:55:02', '2012-09-26 16:55:02', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4557, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-26 14:51:50', '2012-09-26 15:19:07', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4555, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-26 11:24:39', '2012-09-26 11:24:40', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4556, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/test-c-29.html', '/product_info.php', '2012-09-26 11:59:12', '2012-09-26 12:18:08', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '*519?', '', 0);
INSERT INTO `supertracker` VALUES(4554, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=INR', '/newsdesk_info.php', '2012-09-26 10:47:38', '2012-09-26 10:51:50', 3, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4553, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;page=6&amp;amp;currency=CAD&amp;currency=INR', '/index.php', '2012-09-26 10:06:35', '2012-09-26 10:06:36', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4551, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/category-category-c-22_24.html', '/index.php', '2012-09-26 08:40:44', '2012-09-26 09:07:26', 2, 'false', 'false', 'a:1:{i:27;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4552, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;page=6&amp;amp;currency=AUD&amp;currency=INR', '/index.php', '2012-09-26 09:05:00', '2012-09-26 09:05:01', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4549, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/tract-sample-product-p-518.html', '/product_info.php', '2012-09-26 05:12:40', '2012-09-26 05:12:40', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4550, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;currency=CNY&amp;amp;amp;currency=CAD&amp;amp;page=6', '/index.php', '2012-09-26 07:55:17', '2012-09-26 07:55:18', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4547, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/account_newsletters.php', '/cookie_usage.php', '2012-09-26 03:24:24', '2012-09-26 03:24:28', 3, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4548, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;currency=CNY&amp;amp;amp;currency=AUD&amp;amp;page=6', '/index.php', '2012-09-26 04:01:40', '2012-09-26 04:01:42', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4545, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-09-26 02:20:29', '2012-09-26 02:20:29', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4546, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;currency=CAD&amp;amp;amp;currency=CAD&amp;amp;page=6', '/index.php', '2012-09-26 03:00:07', '2012-09-26 03:00:08', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4543, '184.173.183.171', 'AddThis.com robot tech.support@clearspring.com', '', '', 0, 0, '', '', '/test-p-519.html', '/product_info.php', '2012-09-25 23:07:19', '2012-09-25 23:07:19', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4544, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;currency=AUD&amp;amp;amp;currency=CAD&amp;amp;page=6', '/index.php', '2012-09-26 01:55:43', '2012-09-26 02:09:59', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4542, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;page=6', '/index.php', '2012-09-25 21:53:48', '2012-09-25 21:53:49', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4541, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/email_for_price.php?currency=GBP&product_name=test&products_model=1213', '/email_for_price.php', '2012-09-25 20:31:46', '2012-09-25 21:12:46', 3, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4539, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/email_for_price.php?currency=GBP&product_name=&products_model=', '/email_for_price.php', '2012-09-25 19:51:59', '2012-09-25 19:51:59', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4540, '141.105.66.51', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.46 Safari/535.11 MRCHROME', 'us', 'United States', 0, 0, 'http://dev.cartstore.com/index.php', '', '/index.php', '/index.php', '2012-09-25 20:11:56', '2012-09-25 20:11:56', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4538, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/email_for_price.php?currency=EUR&product_name=&products_model=', '/email_for_price.php', '2012-09-25 16:36:03', '2012-09-25 16:56:34', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4537, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/email_for_price.php?currency=CNY&product_name=&products_model=', '/email_for_price.php', '2012-09-25 14:32:58', '2012-09-25 14:53:29', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4535, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/email_for_price.php?currency=AUD&product_name=&products_model=', '/email_for_price.php', '2012-09-25 10:59:13', '2012-09-25 11:51:46', 3, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4536, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/email_for_price.php?currency=CAD&product_name=test&products_model=1213', '/email_for_price.php', '2012-09-25 13:57:45', '2012-09-25 13:57:45', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4534, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=USD&products_id=518&cPath=28', '/wishlist.php', '2012-09-25 09:45:33', '2012-09-25 09:45:33', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4532, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=3&amp;amp;amp;amp;currency=AUD&amp;amp;currency=EUR&amp;currency=AUD', '/index.php', '2012-09-25 04:49:09', '2012-09-25 04:49:10', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4533, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=3&amp;amp;amp;amp;currency=AUD&amp;amp;currency=EUR&amp;currency=CAD', '/index.php', '2012-09-25 05:28:13', '2012-09-25 06:20:48', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4531, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=3&amp;amp;amp;amp;currency=AUD&amp;amp;currency=CNY&amp;currency=CAD', '/index.php', '2012-09-25 04:14:16', '2012-09-25 04:14:17', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4530, '187.184.116.116', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 0, 0, 'http://dev.cartstore.com/manage/orders.php', '', '/', '/ext/modules/payment/paypal/express.php', '2012-09-25 03:56:43', '2012-09-25 03:59:56', 15, 'true', 'false', 'b:0;', '*519?', 'a:1:{i:519;a:1:{s:3:"qty";i:1;}}', 11);
INSERT INTO `supertracker` VALUES(4528, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?products_id=519&cPath=29', '/wishlist.php', '2012-09-25 01:39:49', '2012-09-25 01:39:52', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4529, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=3&amp;amp;amp;amp;currency=AUD&amp;amp;currency=CNY&amp;currency=AUD', '/index.php', '2012-09-25 03:13:47', '2012-09-25 03:13:48', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4526, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/test-c-29.html', '/index.php', '2012-09-24 22:49:15', '2012-09-24 22:49:15', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4527, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/test-p-519.html', '/product_info.php', '2012-09-24 23:52:05', '2012-09-24 23:52:05', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4525, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=3&amp;amp;amp;amp;currency=AUD&amp;amp;currency=CAD&amp;currency=AUD', '/index.php', '2012-09-24 21:41:53', '2012-09-24 22:00:51', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4524, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=3&amp;amp;amp;amp;currency=AUD&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-24 20:57:04', '2012-09-24 20:57:05', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4522, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/tract-sample-product-p-518.html', '/product_info.php', '2012-09-24 18:18:35', '2012-09-24 18:18:35', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4523, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=3&amp;amp;amp;amp;currency=AUD&amp;amp;currency=AUD&amp;currency=AUD', '/index.php', '2012-09-24 19:35:46', '2012-09-24 19:35:47', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4520, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-24 15:14:20', '2012-09-24 15:43:04', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4521, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-24 16:40:30', '2012-09-24 17:09:13', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4519, '66.249.73.35', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-09-24 14:16:54', '2012-09-24 14:16:55', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4518, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=CNY&amp;amp;currency=GBP&amp;currency=CAD', '/index.php', '2012-09-24 09:25:54', '2012-09-24 10:24:57', 8, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4517, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=CNY&amp;amp;currency=EUR&amp;currency=CAD', '/index.php', '2012-09-24 08:05:40', '2012-09-24 08:05:41', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4516, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=CNY&amp;amp;currency=GBP&amp;currency=AUD', '/index.php', '2012-09-24 07:26:35', '2012-09-24 07:26:37', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4514, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=CNY&amp;amp;currency=CNY&amp;currency=CAD', '/index.php', '2012-09-24 04:11:37', '2012-09-24 04:23:33', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4515, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/introducing-cartstore-enterprise-a-135.html', '/article_info.php', '2012-09-24 04:52:05', '2012-09-24 04:52:05', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4512, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=CNY&amp;amp;currency=CAD&amp;currency=CAD', '/index.php', '2012-09-24 03:00:47', '2012-09-24 03:00:48', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4513, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=CNY&amp;amp;currency=CNY&amp;currency=AUD', '/index.php', '2012-09-24 03:36:12', '2012-09-24 03:36:13', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4510, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=CNY&amp;amp;currency=CAD&amp;currency=AUD', '/index.php', '2012-09-24 02:25:22', '2012-09-24 02:25:23', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4511, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/allprods.php?fl=m', '/allprods.php', '2012-09-24 02:48:49', '2012-09-24 02:48:49', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4509, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/current_auctions.php', '/current_auctions.php', '2012-09-24 01:39:03', '2012-09-24 01:39:03', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4508, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-24 01:36:37', '2012-09-24 01:36:38', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4506, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/category-category-c-22_23_24_27.html', '/index.php', '2012-09-23 22:00:27', '2012-09-23 22:00:27', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4507, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/allprods.php?fl=k', '/allprods.php', '2012-09-23 23:59:01', '2012-09-23 23:59:01', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4505, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=CAD&amp;amp;currency=INR&amp;currency=AUD', '/index.php', '2012-09-23 21:54:17', '2012-09-23 22:41:32', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4504, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=CAD&amp;amp;currency=GBP&amp;currency=CAD', '/index.php', '2012-09-23 21:18:52', '2012-09-23 21:18:53', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4503, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/category-category-c-22_23_26_27.html', '/index.php', '2012-09-23 20:54:09', '2012-09-23 20:54:12', 2, 'false', 'false', 'a:1:{i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4502, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=CAD&amp;amp;currency=GBP&amp;currency=AUD', '/index.php', '2012-09-23 20:31:39', '2012-09-23 20:31:40', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4501, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=CAD&amp;amp;currency=EUR&amp;currency=CAD', '/index.php', '2012-09-23 19:32:31', '2012-09-23 19:32:32', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4499, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/conditions-of-use-a-19.html', '/article_info.php', '2012-09-23 15:42:55', '2012-09-23 15:42:55', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4500, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CNY&amp;currency=AUD', '/index.php', '2012-09-23 15:47:28', '2012-09-23 16:34:41', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4497, '94.19.191.183', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)', 'ru', 'Russian Federation', 0, 0, '', '', '/index.php', '/index.php', '2012-09-23 13:49:22', '2012-09-23 13:49:22', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4498, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=AUD', '/index.php', '2012-09-23 14:23:46', '2012-09-23 14:23:47', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4496, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=CAD', '/index.php', '2012-09-23 13:26:14', '2012-09-23 13:26:14', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4494, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=AUD&amp;amp;currency=GBP&amp;currency=AUD', '/index.php', '2012-09-23 07:53:33', '2012-09-23 07:53:34', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4495, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=AUD&amp;amp;currency=GBP&amp;currency=CAD', '/index.php', '2012-09-23 08:51:42', '2012-09-23 10:17:24', 10, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4493, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=AUD&amp;amp;currency=CNY&amp;currency=CAD', '/index.php', '2012-09-23 03:59:27', '2012-09-23 04:37:29', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4492, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=AUD&amp;amp;currency=CNY&amp;currency=AUD', '/index.php', '2012-09-23 03:22:43', '2012-09-23 03:22:44', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4491, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=AUD&amp;amp;currency=CAD&amp;currency=CAD', '/index.php', '2012-09-23 02:33:45', '2012-09-23 02:33:46', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4489, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=AUD&amp;amp;currency=CAD&amp;currency=AUD', '/index.php', '2012-09-23 01:41:15', '2012-09-23 01:41:15', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4490, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-09-23 02:00:54', '2012-09-23 02:00:54', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4488, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_2_11_4&amp;page=6&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-09-22 21:26:26', '2012-09-22 22:27:40', 8, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4487, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_2_11_4&amp;page=6&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-22 20:25:12', '2012-09-22 20:25:13', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4486, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/vestibulum-placerat-accumsan-ligula-elementum-enim-tempor-amet-a-134.html', '/article_info.php', '2012-09-22 20:08:07', '2012-09-22 20:08:07', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4485, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=AUD&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-22 19:25:32', '2012-09-22 19:49:04', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4484, '66.249.71.226', 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_1 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8B117 Safari/6531.22.7 (compatible; Googlebot-Mobile/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-09-22 18:01:42', '2012-09-22 18:01:42', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4483, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=USD&products_id=416&cPath=1_3_4', '/wishlist.php', '2012-09-22 17:25:27', '2012-09-22 17:25:27', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4482, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=4&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-09-22 14:28:01', '2012-09-22 16:09:54', 10, 'false', 'false', 'a:2:{i:4;i:1;i:28;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4481, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=4&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-22 13:25:39', '2012-09-22 13:25:43', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4480, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=CNY', '/index.php', '2012-09-22 09:25:01', '2012-09-22 10:56:31', 10, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4478, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-09-22 07:16:20', '2012-09-22 07:16:21', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4479, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-22 07:56:00', '2012-09-22 07:56:01', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4477, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-22 02:54:59', '2012-09-22 05:04:27', 12, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4475, '187.184.116.116', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 0, 0, '', '', '/', '/product_info.php', '2012-09-21 23:32:16', '2012-09-22 00:32:59', 36, 'false', 'false', 'a:1:{i:24;i:1;}', '*513?*508?*518?', '', 0);
INSERT INTO `supertracker` VALUES(4476, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=CNY', '/index.php', '2012-09-22 01:04:01', '2012-09-22 01:04:02', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4474, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-21 23:15:09', '2012-09-21 23:30:13', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4473, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-09-21 22:47:34', '2012-09-21 22:47:34', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4472, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=CNY', '/index.php', '2012-09-21 22:45:04', '2012-09-21 22:45:05', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4471, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-09-21 22:14:58', '2012-09-21 22:14:59', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4470, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-21 21:44:52', '2012-09-21 21:44:53', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4469, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-21 21:14:46', '2012-09-21 21:14:47', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4467, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-21 15:53:06', '2012-09-21 16:23:11', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4468, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=CNY', '/index.php', '2012-09-21 20:44:40', '2012-09-21 20:44:41', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4466, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-09-21 14:24:21', '2012-09-21 15:23:02', 8, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4465, '184.57.87.71', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 4, 16, 'http://dev.cartstore.com/checkout_payment.php', '', '/shopping_cart.php', '/checkout_success.php', '2012-09-21 12:45:50', '2012-09-21 13:00:34', 22, 'true', 'true', 'b:0;', '', 'Array', 165);
INSERT INTO `supertracker` VALUES(4464, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=4&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-21 11:50:40', '2012-09-21 12:43:10', 8, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4463, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-21 10:58:24', '2012-09-21 10:58:25', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4462, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-09-21 10:23:33', '2012-09-21 10:23:34', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4461, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-21 09:48:42', '2012-09-21 09:48:43', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4460, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-09-21 09:13:51', '2012-09-21 09:13:53', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4458, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;page=6&amp;amp;currency=AUD&amp;currency=GBP', '/index.php', '2012-09-21 06:11:44', '2012-09-21 06:39:36', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4459, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-21 07:29:18', '2012-09-21 07:29:19', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4457, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;page=6&amp;amp;currency=AUD&amp;currency=EUR', '/index.php', '2012-09-21 04:22:06', '2012-09-21 04:22:07', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4456, '184.57.87.71', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 4, 0, 'http://dev.cartstore.com/checkout_payment.php', 'payment_error=ot_coupon&error=The coupon has been successfully applied for : $9.90 ', '/checkout_shipping.php', '/checkout_payment.php', '2012-09-21 02:43:11', '2012-09-21 03:10:38', 55, 'true', 'false', 'b:0;', '*507?*508?', 'a:2:{i:507;a:1:{s:3:"qty";s:1:"1";}i:508;a:1:{s:3:"qty";i:1;}}', 165);
INSERT INTO `supertracker` VALUES(4455, '184.57.87.71', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 0, 0, 'http://dev.cartstore.com/manage/coupon_admin.php', 'action=update_confirm&oldaction=new&cid=', '/', '/checkout_payment.php', '2012-09-21 01:43:35', '2012-09-21 01:52:09', 24, 'true', 'false', 'a:1:{i:22;i:1;}', '', 'a:1:{i:507;a:1:{s:3:"qty";i:1;}}', 99);
INSERT INTO `supertracker` VALUES(4454, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=4&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=CNY', '/index.php', '2012-09-21 00:34:07', '2012-09-21 00:46:47', 4, 'false', 'false', 'a:2:{i:4;i:1;i:28;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4453, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=4&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-09-20 23:41:22', '2012-09-20 23:41:23', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4452, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=4&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-20 20:56:39', '2012-09-20 20:56:40', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4451, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=4&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=CNY', '/index.php', '2012-09-20 17:03:17', '2012-09-20 17:03:24', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4450, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=CNY', '/index.php', '2012-09-20 16:13:40', '2012-09-20 16:30:13', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4449, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-20 15:40:38', '2012-09-20 15:40:39', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4448, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=CNY', '/index.php', '2012-09-20 14:51:05', '2012-09-20 14:51:11', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4447, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-09-20 13:45:01', '2012-09-20 13:45:02', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4446, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-20 13:03:09', '2012-09-20 13:03:11', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4445, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=3&amp;amp;amp;amp;currency=CNY&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-20 10:11:53', '2012-09-20 10:28:25', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4444, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-20 09:22:20', '2012-09-20 09:22:21', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4443, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=CNY', '/index.php', '2012-09-20 08:49:18', '2012-09-20 08:49:19', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4442, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-09-20 08:16:16', '2012-09-20 08:16:18', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4441, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-20 06:58:03', '2012-09-20 06:58:04', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4440, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=CNY', '/index.php', '2012-09-20 04:51:02', '2012-09-20 04:51:03', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4439, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-09-20 04:17:56', '2012-09-20 04:17:57', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4438, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=CNY', '/index.php', '2012-09-20 02:05:32', '2012-09-20 02:05:33', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4437, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=2&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-20 01:15:50', '2012-09-20 01:15:55', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4436, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-19 23:05:28', '2012-09-19 23:22:01', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4435, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=CNY', '/index.php', '2012-09-19 22:32:22', '2012-09-19 22:32:23', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4434, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-09-19 21:59:16', '2012-09-19 21:59:16', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4433, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/integer-nulla-tellus-commodo-viverra-n-29.html?currency=CNY', '/index.php', '2012-09-19 21:24:14', '2012-09-19 21:26:10', 3, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4432, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=CNY', '/index.php', '2012-09-19 20:53:04', '2012-09-19 20:53:05', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4431, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/mauris-bibendum-ornare-gravida-n-28.html?currency=GBP', '/newsdesk_info.php', '2012-09-19 19:53:27', '2012-09-19 20:05:58', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4430, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/mauris-bibendum-ornare-gravida-n-28.html?currency=CAD', '/newsdesk_info.php', '2012-09-19 18:40:47', '2012-09-19 18:40:47', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4429, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/mauris-bibendum-ornare-gravida-n-28.html?currency=EUR', '/newsdesk_info.php', '2012-09-19 18:01:12', '2012-09-19 18:01:12', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4428, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-09-19 16:23:40', '2012-09-19 16:40:14', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4427, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;amp;page=1&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-19 15:34:02', '2012-09-19 15:34:03', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4426, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-09-19 14:44:23', '2012-09-19 14:44:24', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4425, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=INR&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-19 13:54:44', '2012-09-19 13:54:44', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4424, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=GBP', '/index.php', '2012-09-19 13:21:40', '2012-09-19 13:21:41', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4423, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=GBP&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-19 07:52:04', '2012-09-19 07:52:05', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4422, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;page=6&amp;amp;currency=CAD&amp;currency=EUR', '/index.php', '2012-09-19 05:17:11', '2012-09-19 06:29:20', 10, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4421, '141.105.66.51', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11', 'us', 'United States', 0, 0, 'http://dev.cartstore.com/index.php', '', '/index.php', '/index.php', '2012-09-19 03:36:11', '2012-09-19 03:36:11', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4420, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;page=6&amp;amp;currency=AUD&amp;currency=EUR', '/index.php', '2012-09-19 00:59:40', '2012-09-19 01:11:33', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4419, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=INR&products_id=518&cPath=28', '/wishlist.php', '2012-09-19 00:22:02', '2012-09-19 00:22:02', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4417, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=GBP&products_id=518&cPath=28', '/wishlist.php', '2012-09-18 23:34:35', '2012-09-18 23:34:35', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4418, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-09-18 23:42:41', '2012-09-18 23:42:41', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4416, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=EUR&products_id=518&cPath=28', '/index.php', '2012-09-18 19:57:32', '2012-09-18 20:02:15', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4415, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=CNY&products_id=518&cPath=28', '/wishlist.php', '2012-09-18 19:14:34', '2012-09-18 19:14:34', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4413, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=CAD&products_id=518&cPath=28', '/wishlist.php', '2012-09-18 18:19:07', '2012-09-18 18:19:07', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4414, '187.184.116.116', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 0, 0, 'http://adoovo.kayako.com/staff/Tickets/Ticket/View/2506/inbox/-1/-1/-1', '', '/', '/catalog_products_with_images.php', '2012-09-18 18:56:19', '2012-09-18 18:56:31', 2, 'true', 'false', 'b:0;', '', 'a:1:{i:508;a:1:{s:3:"qty";i:1;}}', 66);
INSERT INTO `supertracker` VALUES(4412, '187.184.116.116', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 0, 0, '', '', '/', '/checkout_confirmation.php', '2012-09-18 18:14:21', '2012-09-18 18:16:48', 12, 'true', 'false', 'b:0;', '', 'a:1:{i:508;a:1:{s:3:"qty";i:1;}}', 66);
INSERT INTO `supertracker` VALUES(4411, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=AUD&products_id=518&cPath=28', '/wishlist.php', '2012-09-18 17:42:34', '2012-09-18 17:42:34', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4410, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=CAD', '/index.php', '2012-09-18 16:00:29', '2012-09-18 16:00:31', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4408, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;page=6&amp;amp;currency=CAD&amp;currency=CNY', '/index.php', '2012-09-18 13:12:43', '2012-09-18 13:12:44', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4409, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_3_4&amp;amp;page=2&amp;amp;amp;amp;currency=EUR&amp;amp;currency=CAD&amp;currency=AUD', '/index.php', '2012-09-18 14:36:36', '2012-09-18 14:36:37', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4407, '184.57.87.71', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 0, 0, '', '', '/catalog_products_with_images.php', '/catalog_products_with_images.php', '2012-09-18 12:18:09', '2012-09-18 12:30:21', 5, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4406, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;page=6&amp;amp;currency=CAD&amp;currency=AUD', '/index.php', '2012-09-18 11:26:33', '2012-09-18 11:54:39', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4404, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-09-18 07:23:38', '2012-09-18 07:23:38', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4405, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;page=6&amp;amp;currency=AUD&amp;currency=CNY', '/index.php', '2012-09-18 07:58:42', '2012-09-18 07:58:43', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4403, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;page=6&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-18 06:48:14', '2012-09-18 06:48:15', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4402, '184.173.183.171', 'AddThis.com robot tech.support@clearspring.com', '', '', 0, 0, '', '', '/test-prodcuts-c-22.html?osCsid=c5d02e3c69aed786b3ca5582be06b4e0', '/index.php', '2012-09-18 06:45:15', '2012-09-18 06:45:15', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4401, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;page=6&amp;amp;currency=AUD&amp;currency=AUD', '/index.php', '2012-09-18 06:13:00', '2012-09-18 06:13:01', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4399, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;amp;page=1&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=CNY', '/index.php', '2012-09-17 22:10:53', '2012-09-17 22:10:53', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4400, '187.184.116.116', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 0, 0, 'http://adoovo.kayako.com/staff/Tickets/Ticket/View/2506/inbox/-1/-1/-1', '', '/', '/catalog_products_with_images.php', '2012-09-18 03:18:22', '2012-09-18 03:35:25', 7, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4398, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/product_info.php?currency=GBP&products_id=493&cPath=', '/product_info.php', '2012-09-17 19:55:20', '2012-09-17 19:55:21', 2, 'false', 'false', 'b:0;', '*493?', '', 0);
INSERT INTO `supertracker` VALUES(4397, '187.184.116.116', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 0, 0, '', '', '/', '/ext/estimated_shipping.php', '2012-09-17 19:30:23', '2012-09-17 19:32:11', 5, 'true', 'false', 'a:2:{i:22;i:1;i:28;i:1;}', '', 'a:1:{i:518;a:1:{s:3:"qty";i:1;}}', 1);
INSERT INTO `supertracker` VALUES(4395, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=USD&cPath=22_25_27_24_27', '/index.php', '2012-09-17 13:40:39', '2012-09-17 13:40:40', 2, 'false', 'false', 'a:1:{i:27;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4396, '66.249.71.226', 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_1 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8B117 Safari/6531.22.7 (compatible; Googlebot-Mobile/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-09-17 17:51:49', '2012-09-17 17:51:49', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4394, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=2', '/index.php', '2012-09-17 12:35:31', '2012-09-17 13:00:58', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4393, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=1', '/index.php', '2012-09-17 11:45:52', '2012-09-17 11:45:53', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4391, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=3', '/index.php', '2012-09-17 06:27:15', '2012-09-17 06:27:17', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4392, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=4', '/index.php', '2012-09-17 07:20:38', '2012-09-17 07:20:39', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4390, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=2', '/index.php', '2012-09-17 05:46:00', '2012-09-17 05:46:01', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4389, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=1', '/index.php', '2012-09-17 05:11:25', '2012-09-17 05:11:26', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4387, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=4', '/index.php', '2012-09-16 22:35:18', '2012-09-16 22:35:19', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4388, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-09-17 05:04:07', '2012-09-17 05:04:07', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4386, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=3', '/index.php', '2012-09-16 21:52:22', '2012-09-16 21:52:23', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4384, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CNY&amp;amp;page=3', '/index.php', '2012-09-16 20:12:12', '2012-09-16 20:12:13', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4385, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=2', '/index.php', '2012-09-16 21:09:26', '2012-09-16 21:09:28', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4383, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CNY&amp;amp;page=1', '/index.php', '2012-09-16 19:19:14', '2012-09-16 19:19:14', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4381, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CAD&amp;amp;page=1', '/index.php', '2012-09-16 15:12:22', '2012-09-16 15:12:23', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4382, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CAD&amp;amp;page=2', '/index.php', '2012-09-16 16:24:34', '2012-09-16 16:24:35', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4380, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_3_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=2', '/index.php', '2012-09-16 14:12:32', '2012-09-16 14:12:33', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4379, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_3_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=2', '/index.php', '2012-09-16 11:46:37', '2012-09-16 12:16:34', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4378, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_2_11_4&amp;currency=CAD&amp;page=6', '/index.php', '2012-09-16 09:48:01', '2012-09-16 09:48:01', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4377, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_2_11_4&amp;currency=AUD&amp;page=6', '/index.php', '2012-09-16 08:36:09', '2012-09-16 08:36:10', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4376, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_2_11_4&amp;amp;page=4&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-16 07:36:11', '2012-09-16 07:36:12', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4375, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_2_11_4&amp;amp;page=4&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=AUD', '/index.php', '2012-09-16 05:59:03', '2012-09-16 05:59:05', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4374, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_2_11_4&amp;amp;page=3&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-16 02:49:14', '2012-09-16 02:49:15', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4373, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=1_2_11_4&amp;amp;page=3&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=AUD', '/index.php', '2012-09-16 02:02:04', '2012-09-16 02:02:05', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4371, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=3', '/index.php', '2012-09-16 00:18:44', '2012-09-16 00:18:45', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4372, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=INR&cPath=&amp;amp;manufacturers_id=1&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-16 01:03:44', '2012-09-16 01:03:45', 2, 'false', 'false', 'a:1:{s:0:"";i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4370, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=2', '/index.php', '2012-09-15 20:48:12', '2012-09-15 20:48:13', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4368, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=4', '/index.php', '2012-09-15 18:59:57', '2012-09-15 18:59:58', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4369, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=1', '/index.php', '2012-09-15 19:42:12', '2012-09-15 19:42:13', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4367, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=3', '/index.php', '2012-09-15 17:56:25', '2012-09-15 17:56:25', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4366, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=2', '/index.php', '2012-09-15 14:11:18', '2012-09-15 14:11:19', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4365, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=1', '/index.php', '2012-09-15 13:26:21', '2012-09-15 13:26:22', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4363, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=3', '/index.php', '2012-09-15 12:12:22', '2012-09-15 12:12:23', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4364, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=4', '/index.php', '2012-09-15 12:46:58', '2012-09-15 12:46:58', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4362, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=2', '/index.php', '2012-09-15 08:09:20', '2012-09-15 08:09:21', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4360, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CNY&amp;amp;page=1', '/index.php', '2012-09-15 05:41:29', '2012-09-15 05:41:30', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4361, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CNY&amp;amp;page=3', '/index.php', '2012-09-15 07:03:22', '2012-09-15 07:03:23', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4359, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CAD&amp;amp;page=2', '/index.php', '2012-09-15 01:59:54', '2012-09-15 02:00:00', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4358, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CAD&amp;amp;page=1', '/index.php', '2012-09-15 01:21:40', '2012-09-15 01:21:41', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4356, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_3_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=2', '/index.php', '2012-09-14 19:46:05', '2012-09-14 19:46:06', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4357, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_3_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=2', '/index.php', '2012-09-15 00:19:25', '2012-09-15 00:44:44', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4355, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_2_4&amp;currency=GBP&amp;amp;amp;currency=GBP&amp;amp;page=2', '/index.php', '2012-09-14 19:15:02', '2012-09-14 19:15:03', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4354, '66.249.71.226', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_2_11_4&amp;currency=CAD&amp;page=6', '/index.php', '2012-09-14 18:14:17', '2012-09-14 18:16:26', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4352, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_2_11_4&amp;amp;page=4&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-14 11:38:27', '2012-09-14 11:38:29', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4353, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_2_11_4&amp;currency=AUD&amp;page=6', '/index.php', '2012-09-14 12:17:49', '2012-09-14 12:17:50', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4351, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_2_11_4&amp;amp;page=4&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=AUD', '/index.php', '2012-09-14 08:26:07', '2012-09-14 08:26:08', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4350, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_2_11_4&amp;amp;page=3&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-14 07:27:51', '2012-09-14 07:27:52', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4348, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=&amp;amp;manufacturers_id=1&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-14 05:35:16', '2012-09-14 05:35:17', 2, 'false', 'false', 'a:1:{s:0:"";i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4349, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=GBP&cPath=1_2_11_4&amp;amp;page=3&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=AUD', '/index.php', '2012-09-14 06:29:36', '2012-09-14 06:29:36', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4347, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=3', '/index.php', '2012-09-14 01:01:20', '2012-09-14 01:01:21', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4345, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=1', '/index.php', '2012-09-13 23:16:37', '2012-09-13 23:16:38', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4346, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=2', '/index.php', '2012-09-14 00:12:39', '2012-09-14 00:12:40', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4344, '66.249.74.180', 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_1 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8B117 Safari/6531.22.7 (compatible; Googlebot-Mobile/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-09-13 22:06:30', '2012-09-13 22:06:30', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4342, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=4', '/index.php', '2012-09-13 20:11:30', '2012-09-13 20:11:31', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4343, '187.184.116.116', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 0, 0, '', '', '/', '/ext/estimated_shipping.php', '2012-09-13 20:28:55', '2012-09-13 20:29:05', 3, 'true', 'false', 'b:0;', '', 'a:1:{i:508;a:1:{s:3:"qty";i:1;}}', 66);
INSERT INTO `supertracker` VALUES(4341, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=3', '/index.php', '2012-09-13 18:57:30', '2012-09-13 18:57:31', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4339, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=1', '/index.php', '2012-09-13 17:21:28', '2012-09-13 17:21:29', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4340, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=2', '/index.php', '2012-09-13 18:04:32', '2012-09-13 18:04:33', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4338, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=3', '/index.php', '2012-09-13 12:52:26', '2012-09-13 12:52:27', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4336, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CNY&amp;amp;page=3', '/index.php', '2012-09-13 10:45:20', '2012-09-13 10:45:21', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4337, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=2', '/index.php', '2012-09-13 11:46:24', '2012-09-13 11:46:25', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4335, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CNY&amp;amp;page=1', '/index.php', '2012-09-13 09:40:36', '2012-09-13 10:07:38', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4333, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CAD&amp;amp;page=1', '/wishlist.php', '2012-09-13 01:47:54', '2012-09-13 02:37:53', 6, 'false', 'false', 'a:2:{i:4;i:1;i:28;i:1;}', '*518?', '', 0);
INSERT INTO `supertracker` VALUES(4334, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CAD&amp;amp;page=2', '/index.php', '2012-09-13 03:41:33', '2012-09-13 03:41:35', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4332, '187.184.116.116', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1', '', '', 0, 0, 'http://dev.cartstore.com/manage/categories.php', 'cPath=28&pID=518', '/', '/checkout_confirmation.php', '2012-09-13 01:16:28', '2012-09-13 01:45:11', 30, 'true', 'false', 'a:1:{i:28;i:1;}', '*518?', 'a:1:{i:518;a:1:{s:3:"qty";s:1:"3";}}', 2);
INSERT INTO `supertracker` VALUES(4331, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_3_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=2', '/index.php', '2012-09-12 21:27:03', '2012-09-12 21:27:04', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4330, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-09-12 20:51:03', '2012-09-12 20:51:03', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4329, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_3_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=2', '/index.php', '2012-09-12 20:43:32', '2012-09-12 20:43:34', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4328, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_3_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=2', '/index.php', '2012-09-12 18:59:12', '2012-09-12 18:59:13', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4327, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_2_11_4&amp;currency=CAD&amp;page=6', '/index.php', '2012-09-12 14:54:21', '2012-09-12 14:54:22', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4326, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_2_11_4&amp;currency=AUD&amp;page=6', '/index.php', '2012-09-12 14:15:53', '2012-09-12 14:15:54', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4325, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_2_11_4&amp;amp;page=4&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-12 13:39:09', '2012-09-12 13:39:12', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4324, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_2_11_4&amp;amp;page=4&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=AUD', '/index.php', '2012-09-12 09:13:49', '2012-09-12 09:13:50', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4323, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_2_11_4&amp;amp;page=3&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-12 08:34:35', '2012-09-12 08:34:37', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4322, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=1_2_11_4&amp;amp;page=3&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=AUD', '/index.php', '2012-09-12 07:41:21', '2012-09-12 07:41:22', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4321, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=EUR&cPath=&amp;amp;manufacturers_id=1&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-12 04:29:27', '2012-09-12 04:29:29', 2, 'false', 'false', 'a:1:{s:0:"";i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4320, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=3', '/index.php', '2012-09-12 03:21:57', '2012-09-12 03:21:59', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4319, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=2', '/index.php', '2012-09-12 02:24:19', '2012-09-12 02:24:20', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4318, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=1', '/index.php', '2012-09-11 20:43:09', '2012-09-11 20:43:10', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4317, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=4', '/index.php', '2012-09-11 19:35:29', '2012-09-11 19:35:30', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4316, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=3', '/index.php', '2012-09-11 14:37:39', '2012-09-11 14:37:40', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4315, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=2', '/index.php', '2012-09-11 13:35:39', '2012-09-11 13:35:40', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4314, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=1', '/index.php', '2012-09-11 10:24:03', '2012-09-11 10:24:05', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4313, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=4', '/index.php', '2012-09-11 07:57:45', '2012-09-11 07:57:46', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4312, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=3', '/index.php', '2012-09-11 02:43:03', '2012-09-11 02:43:04', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4311, '66.249.74.180', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=2', '/index.php', '2012-09-11 01:31:50', '2012-09-11 01:31:51', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4310, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CNY&amp;amp;page=3', '/index.php', '2012-09-10 21:30:17', '2012-09-10 21:30:18', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4308, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/mauris-bibendum-ornare-gravida-n-28.html', '/newsdesk_info.php', '2012-09-10 16:43:39', '2012-09-10 16:43:39', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4309, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CNY&amp;amp;page=1', '/index.php', '2012-09-10 19:27:46', '2012-09-10 19:27:48', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4307, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CAD&amp;amp;page=2', '/index.php', '2012-09-10 15:00:33', '2012-09-10 15:00:34', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4305, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=2', '/index.php', '2012-09-10 10:39:04', '2012-09-10 10:39:05', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4306, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CAD&amp;amp;page=1', '/index.php', '2012-09-10 13:25:03', '2012-09-10 13:25:04', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4304, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=2', '/index.php', '2012-09-10 07:16:26', '2012-09-10 07:16:27', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4303, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_3_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=2', '/index.php', '2012-09-10 02:56:19', '2012-09-10 02:56:20', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4302, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_2_11_4&amp;currency=CAD&amp;page=6', '/index.php', '2012-09-10 01:14:09', '2012-09-10 01:14:11', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4301, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_2_11_4&amp;currency=AUD&amp;page=6', '/index.php', '2012-09-09 20:46:04', '2012-09-09 20:46:05', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4299, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_2_11_4&amp;amp;page=4&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=AUD', '/index.php', '2012-09-09 14:19:46', '2012-09-09 14:19:48', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4300, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_2_11_4&amp;amp;page=4&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-09 19:23:17', '2012-09-09 19:23:18', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4297, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/allprods.php?fl=n', '/allprods.php', '2012-09-09 12:22:10', '2012-09-09 12:22:10', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4298, '66.249.72.58', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_2_11_4&amp;amp;page=3&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-09 13:20:14', '2012-09-09 13:20:15', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4296, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-09-09 11:27:05', '2012-09-09 11:27:05', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4295, '66.249.72.58', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_2_11_4&amp;amp;page=3&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=AUD', '/index.php', '2012-09-09 07:13:18', '2012-09-09 07:13:19', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4294, '66.249.72.58', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=1_2_11_4&amp;amp;page=1&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-09 01:02:33', '2012-09-09 01:02:34', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4293, '66.249.68.26', 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_1 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8B117 Safari/6531.22.7 (compatible; Googlebot-Mobile/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-09-08 20:31:14', '2012-09-08 20:31:14', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4292, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=&amp;amp;manufacturers_id=1&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-08 20:10:01', '2012-09-08 20:10:02', 2, 'false', 'false', 'a:1:{s:0:"";i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4290, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=2', '/index.php', '2012-09-08 15:16:04', '2012-09-08 15:16:16', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4291, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=3', '/index.php', '2012-09-08 18:52:40', '2012-09-08 18:52:40', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4288, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/-c-21_87.html?currency=CNY', '/index.php', '2012-09-08 09:33:05', '2012-09-08 09:33:05', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4289, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=1', '/index.php', '2012-09-08 13:37:58', '2012-09-08 13:37:59', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4287, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=4', '/index.php', '2012-09-08 08:54:58', '2012-09-08 08:55:00', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4285, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=CNY&products_id=428&cPath=1_3_4', '/wishlist.php', '2012-09-08 05:19:51', '2012-09-08 05:19:51', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4286, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/category-category-c-22_23_26_27.html', '/index.php', '2012-09-08 07:27:02', '2012-09-08 07:27:20', 3, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4283, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=2', '/index.php', '2012-09-08 04:00:28', '2012-09-08 04:00:29', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4284, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-09-08 04:04:55', '2012-09-08 04:04:55', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4282, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=1', '/index.php', '2012-09-08 02:32:50', '2012-09-08 02:32:51', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4280, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/pellentesque-lacinia-quis-semper-fermentum-nisi-felis-blan-n-30.html', '/newsdesk_info.php', '2012-09-07 22:17:18', '2012-09-07 22:17:18', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4281, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=4', '/index.php', '2012-09-08 01:05:15', '2012-09-08 01:05:18', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4278, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=2', '/index.php', '2012-09-07 19:02:45', '2012-09-07 19:02:46', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4279, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=3', '/index.php', '2012-09-07 20:30:31', '2012-09-07 20:30:32', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4276, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CNY&amp;amp;page=1', '/index.php', '2012-09-07 14:42:20', '2012-09-07 14:42:21', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4277, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-09-07 16:14:50', '2012-09-07 16:14:50', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4275, '178.120.27.174', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)', '', '', 0, 0, '', '', '/category-category-c-21_27.html?currency=CAD&sa=U&ei=rnVCUKOZEK7T4QT2_oH4Aw&ved=0CJgCEBYwXTiEBw&usg=AFQjCNEflHe0jebwu2MoR6ENlr68RPoMNg', '/index.php', '2012-09-07 13:02:51', '2012-09-07 13:02:51', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4274, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CNY&amp;amp;page=3', '/index.php', '2012-09-07 12:36:50', '2012-09-07 12:55:22', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4273, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CAD&amp;amp;page=1', '/index.php', '2012-09-07 08:09:09', '2012-09-07 08:09:12', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4271, '66.249.68.14', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=2', '/index.php', '2012-09-07 01:58:54', '2012-09-07 01:58:55', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4272, '66.249.68.26', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=2', '/index.php', '2012-09-07 06:35:05', '2012-09-07 06:36:36', 3, 'false', 'false', 'a:2:{i:4;i:1;i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4269, '184.57.87.71', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0', '', '', 0, 0, '', '', '/', '/index.php', '2012-09-06 23:56:53', '2012-09-06 23:56:53', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4270, '66.249.68.14', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=2', '/index.php', '2012-09-07 00:53:35', '2012-09-07 00:53:36', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4267, '66.249.68.14', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;currency=CAD&amp;page=6', '/index.php', '2012-09-06 20:24:19', '2012-09-06 20:24:20', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4268, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-09-06 22:51:32', '2012-09-06 22:51:32', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4265, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/allprods.php?fl=n', '/allprods.php', '2012-09-06 13:36:54', '2012-09-06 13:36:54', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4266, '66.249.68.14', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/category-category-c-22_27_26.html', '/index.php', '2012-09-06 18:51:50', '2012-09-06 19:38:34', 6, 'false', 'false', 'a:2:{i:4;i:1;i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4264, '66.249.68.8', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/products_new.php', '/products_new.php', '2012-09-06 13:18:11', '2012-09-06 13:18:11', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4263, '66.249.68.8', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=4&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-06 12:28:23', '2012-09-06 12:28:24', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4261, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-09-06 09:46:43', '2012-09-06 09:46:43', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4262, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/allprods.php?fl=g', '/allprods.php', '2012-09-06 12:11:49', '2012-09-06 12:11:49', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4260, '66.249.68.8', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=4&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=AUD', '/index.php', '2012-09-06 08:07:58', '2012-09-06 08:07:59', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4258, '66.249.68.8', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=3&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-06 06:14:03', '2012-09-06 06:14:05', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4259, '141.105.66.51', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.52 Safari/536.5', 'us', 'United States', 0, 0, 'http://dev.cartstore.com/index.php', '', '/index.php', '/index.php', '2012-09-06 06:52:00', '2012-09-06 06:52:00', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4257, '66.249.68.8', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_11_4&amp;amp;page=3&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=AUD', '/index.php', '2012-09-06 02:20:18', '2012-09-06 02:20:19', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4255, '66.249.68.8', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=&amp;amp;manufacturers_id=1&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-06 01:08:43', '2012-09-06 01:08:58', 2, 'false', 'false', 'a:1:{s:0:"";i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4256, '187.184.109.123', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0', '', '', 0, 0, 'http://dev.cartstore.com/manage/orders.php', '', '/', '/index.php', '2012-09-06 01:19:13', '2012-09-06 01:19:13', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4253, '199.21.99.108', 'Mozilla/5.0 (compatible; YandexBot/3.0;  http://yandex.com/bots)', 'us', 'United States', 0, 0, '', '', '/allprods.php?fl=n', '/allprods.php', '2012-09-05 23:12:07', '2012-09-05 23:12:10', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4254, '66.249.72.46', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/allprods.php?fl=P', '/allprods.php', '2012-09-05 23:14:36', '2012-09-05 23:14:36', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4251, '66.249.67.144', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=3', '/index.php', '2012-09-05 19:43:09', '2012-09-05 19:43:10', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4252, '187.52.69.199', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'br', 'Brazil', 0, 0, '', '', '/contact_us.php', '/contact_us.php', '2012-09-05 22:53:04', '2012-09-05 22:53:04', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4250, '66.249.67.144', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=INR&amp;amp;page=2', '/index.php', '2012-09-05 18:25:22', '2012-09-05 18:25:23', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4249, '187.184.109.123', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0', '', '', 0, 0, '', '', '/', '/ext/estimated_shipping.php', '2012-09-05 16:31:01', '2012-09-05 16:31:11', 3, 'true', 'false', 'b:0;', '', 'a:1:{i:508;a:1:{s:3:"qty";i:1;}}', 66);
INSERT INTO `supertracker` VALUES(4247, '94.23.117.229', 'Mozilla/5.0 (Windows NT 6.0; WOW64) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.52 Safari/536.5', 'fr', 'France', 0, 0, 'http://dev.cartstore.com/index.php', '', '/index.php', '/index.php', '2012-09-05 13:13:18', '2012-09-05 13:13:18', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4248, '66.249.72.46', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=4', '/index.php', '2012-09-05 13:27:13', '2012-09-05 13:51:40', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4245, '66.249.73.15', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/category-category-c-22_27_26.html', '/index.php', '2012-09-05 09:01:52', '2012-09-05 09:01:52', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4246, '66.249.67.144', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=4', '/index.php', '2012-09-05 12:15:34', '2012-09-05 12:39:09', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4244, '66.249.73.15', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=1', '/index.php', '2012-09-05 07:15:59', '2012-09-05 07:32:28', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4243, '66.249.73.15', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=4', '/product_info.php', '2012-09-05 06:15:12', '2012-09-05 06:27:02', 3, 'false', 'false', 'a:1:{i:4;i:1;}', '*493?', '', 0);
INSERT INTO `supertracker` VALUES(4242, '66.249.73.15', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CNY&amp;amp;page=3', '/index.php', '2012-09-05 03:09:48', '2012-09-05 03:41:12', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4240, '66.249.73.15', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_4&amp;currency=CAD&amp;amp;amp;currency=CAD&amp;amp;page=2', '/index.php', '2012-09-05 02:04:06', '2012-09-05 02:36:53', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4241, '201.0.155.245', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'br', 'Brazil', 0, 0, '', '', '/contact_us.php', '/contact_us.php', '2012-09-05 02:36:26', '2012-09-05 02:36:26', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4239, '66.249.73.15', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_3_4&amp;currency=CAD&amp;amp;amp;currency=GBP&amp;amp;page=2', '/index.php', '2012-09-05 01:19:17', '2012-09-05 01:32:34', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4237, '66.249.73.15', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/password_forgotten.php', '/password_forgotten.php', '2012-09-04 21:57:14', '2012-09-04 21:57:14', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4238, '66.249.73.15', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_3_4&amp;currency=CAD&amp;amp;amp;currency=EUR&amp;amp;page=2', '/index.php', '2012-09-05 00:15:07', '2012-09-05 00:15:08', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4235, '66.249.73.15', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/allprods.php?fl=b', '/index.php', '2012-09-04 18:26:24', '2012-09-04 19:11:48', 9, 'false', 'false', 'a:2:{s:0:"";i:1;i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4236, '66.249.73.15', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=AUD&cPath=1_2_11_4&amp;amp;page=4&amp;amp;amp;amp;currency=CNY&amp;amp;currency=AUD&amp;currency=CAD', '/index.php', '2012-09-04 19:42:06', '2012-09-04 20:03:54', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4234, '66.249.71.197', 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_1 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8B117 Safari/6531.22.7 (compatible; Googlebot-Mobile/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-09-04 14:34:17', '2012-09-04 14:34:17', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4233, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=&amp;amp;amp;manufacturers_id=1&amp;amp;amp;amp;amp;amp;currency=CAD&amp;amp;amp;amp;currency=CAD&amp;amp;currency=AUD&amp;currency=CAD', '/allprods.php', '2012-09-04 12:52:58', '2012-09-04 16:27:01', 24, 'false', 'false', 'a:2:{s:0:"";i:1;i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4232, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CNY&cPath=&amp;amp;amp;amp;manufacturers_id=3&amp;amp;amp;amp;amp;amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;amp;currency=CAD&amp;amp;amp;amp;currency=AUD&amp;amp;currency=AUD&amp;currency=AUD', '/index.php', '2012-09-04 12:16:56', '2012-09-04 12:16:57', 2, 'false', 'false', 'a:1:{s:0:"";i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4231, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;page=6&amp;amp;currency=GBP&amp;currency=AUD', '/index.php', '2012-09-04 08:52:15', '2012-09-04 09:41:01', 8, 'false', 'false', 'a:3:{i:4;i:1;i:27;i:1;s:0:"";i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4230, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;page=5&amp;amp;currency=EUR&amp;currency=EUR', '/index.php', '2012-09-04 06:48:04', '2012-09-04 08:10:52', 10, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4229, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;page=5&amp;amp;currency=CNY&amp;currency=EUR', '/index.php', '2012-09-04 05:57:33', '2012-09-04 05:57:34', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4227, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;page=1&amp;amp;currency=GBP&amp;currency=INR', '/index.php', '2012-09-03 16:44:34', '2012-09-03 17:25:58', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4228, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;page=2&amp;amp;currency=EUR&amp;currency=INR', '/index.php', '2012-09-03 19:31:52', '2012-09-03 22:11:08', 18, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4226, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_4&amp;page=1&amp;amp;currency=CNY&amp;currency=INR', '/index.php', '2012-09-03 15:42:28', '2012-09-03 16:03:11', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4225, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=2&amp;amp;currency=GBP&amp;currency=CAD', '/index.php', '2012-09-03 14:19:41', '2012-09-03 14:40:24', 5, 'false', 'false', 'a:2:{i:4;i:1;i:26;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4224, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;page=2&amp;amp;currency=EUR&amp;currency=CAD', '/index.php', '2012-09-03 13:38:18', '2012-09-03 13:38:19', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4223, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;currency=CNY&amp;amp;amp;currency=INR&amp;amp;amp;amp;amp;currency=CNY&amp;amp;amp;page=1', '/index.php', '2012-09-03 09:39:52', '2012-09-03 09:39:53', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4222, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;currency=CNY&amp;amp;amp;currency=INR&amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;page=1', '/index.php', '2012-09-03 08:47:42', '2012-09-03 09:05:06', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4221, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;currency=CNY&amp;amp;amp;currency=GBP&amp;amp;amp;amp;amp;currency=CNY&amp;amp;amp;page=1', '/index.php', '2012-09-03 07:55:31', '2012-09-03 07:55:32', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4220, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;currency=CNY&amp;amp;amp;currency=EUR&amp;amp;amp;amp;amp;currency=CAD&amp;amp;amp;page=1', '/index.php', '2012-09-03 04:49:45', '2012-09-03 05:03:49', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4219, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;currency=CNY&amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;currency=CNY&amp;amp;amp;page=1', '/index.php', '2012-09-03 03:47:53', '2012-09-03 04:17:12', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4218, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;currency=CNY&amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;currency=CAD&amp;amp;amp;page=1', '/index.php', '2012-09-03 02:56:03', '2012-09-03 02:56:04', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4216, '178.121.183.113', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)', '', '', 0, 0, '', '', '/category-category-c-21_27.html?currency=CAD&sa=U&ei=rnVCUKOZEK7T4QT2_oH4Aw&ved=0CJgCEBYwXTiEBw&usg=AFQjCNEflHe0jebwu2MoR6ENlr68RPoMNg', '/index.php', '2012-09-02 14:55:03', '2012-09-02 14:55:03', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4217, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_3_4&amp;currency=CAD&amp;amp;amp;currency=CNY&amp;amp;amp;amp;amp;currency=CAD&amp;amp;amp;page=6', '/index.php', '2012-09-03 01:42:46', '2012-09-03 02:20:17', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4215, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=EUR&amp;amp;amp;amp;amp;currency=CNY&amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=CNY&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=A', '/index.php', '2012-09-02 14:51:44', '2012-09-02 16:06:15', 9, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4214, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=I', '/index.php', '2012-09-02 13:46:51', '2012-09-02 14:20:23', 5, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4213, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=G', '/index.php', '2012-09-02 11:24:12', '2012-09-02 11:24:13', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4212, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=C', '/index.php', '2012-09-02 09:57:16', '2012-09-02 10:49:26', 8, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4211, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=C', '/index.php', '2012-09-02 08:30:19', '2012-09-02 08:47:43', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4210, '178.121.183.113', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)', '', '', 0, 0, '', '', '/category-category-c-21_27.html?currency=CAD&sa=U&ei=rnVCUKOZEK7T4QT2_oH4Aw&ved=0CJgCEBYwXTiEBw&usg=AFQjCNEflHe0jebwu2MoR6ENlr68RPoMNg', '/index.php', '2012-09-02 07:56:45', '2012-09-02 07:57:45', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4209, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=C', '/index.php', '2012-09-02 07:29:16', '2012-09-02 07:29:17', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4208, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;currency=EUR&amp;amp;amp;amp;amp;amp;amp;currency=CNY&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=I', '/index.php', '2012-09-02 03:19:30', '2012-09-02 03:36:55', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4206, '178.121.183.113', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)', '', '', 0, 0, '', '', '/category-category-c-21_27.html?currency=CAD&sa=U&ei=rnVCUKOZEK7T4QT2_oH4Aw&ved=0CJgCEBYwXTiEBw&usg=AFQjCNEflHe0jebwu2MoR6ENlr68RPoMNg', '/index.php', '2012-09-02 01:27:05', '2012-09-02 01:27:42', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4207, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;currency=EUR&amp;amp;amp;amp;amp;amp;amp;currency=CNY&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=C', '/index.php', '2012-09-02 01:45:35', '2012-09-02 02:27:22', 7, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4205, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;currency=EUR&amp;amp;amp;amp;amp;amp;amp;currency=CNY&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=A', '/index.php', '2012-09-01 20:15:03', '2012-09-01 20:15:04', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4204, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;currency=CNY&amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=CNY&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=C', '/index.php', '2012-09-01 19:40:26', '2012-09-01 19:40:27', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4203, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=EUR&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=C', '/index.php', '2012-09-01 18:50:43', '2012-09-01 19:05:50', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4202, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=EUR&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=C', '/index.php', '2012-09-01 18:19:55', '2012-09-01 18:19:56', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4201, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=EUR&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=C', '/index.php', '2012-09-01 15:31:26', '2012-09-01 17:04:13', 9, 'false', 'false', 'a:1:{i:4;i:1;}', '*437?', '', 0);
INSERT INTO `supertracker` VALUES(4200, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=EUR&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=C', '/index.php', '2012-09-01 14:58:24', '2012-09-01 14:58:25', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4199, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=EUR&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=C', '/index.php', '2012-09-01 14:08:51', '2012-09-01 14:26:37', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4198, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=EUR&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=C', '/index.php', '2012-09-01 13:35:48', '2012-09-01 13:35:49', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4197, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=EUR&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=C', '/index.php', '2012-09-01 12:46:16', '2012-09-01 12:54:46', 3, 'false', 'false', 'a:2:{i:4;i:1;i:27;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4196, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=EUR&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=C', '/index.php', '2012-09-01 09:26:21', '2012-09-01 09:53:45', 4, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4195, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=EUR&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=C', '/index.php', '2012-09-01 06:55:37', '2012-09-01 08:45:15', 12, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4194, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=EUR&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=C', '/index.php', '2012-09-01 06:16:57', '2012-09-01 06:16:58', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4193, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/lorem-ipsum-dolor-amet-consectetur-adipiscing-elit-a-133.html', '/article_info.php', '2012-09-01 05:23:38', '2012-09-01 05:23:38', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4192, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=EUR&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=C', '/index.php', '2012-09-01 03:20:06', '2012-09-01 04:01:21', 6, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4191, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;currency=AUD&amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=EUR&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=C', '/index.php', '2012-09-01 00:21:24', '2012-09-01 02:38:53', 17, 'false', 'false', 'a:2:{i:4;i:1;s:0:"";i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4190, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/-m-43.html?currency=CAD', '/index.php', '2012-08-31 23:46:24', '2012-08-31 23:46:24', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4188, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/wishlist.php?currency=AUD&products_id=445&cPath=1_3_4', '/wishlist.php', '2012-08-31 21:45:57', '2012-08-31 21:45:57', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4189, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/-m-38.html?currency=CAD', '/index.php', '2012-08-31 23:01:19', '2012-08-31 23:16:18', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4187, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;amp;amp;amp;amp;page=3&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=CNY&amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;currency=CAD&amp;amp;currency=CNY&amp;cur', '/index.php', '2012-08-31 20:53:37', '2012-08-31 20:53:38', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4186, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;amp;amp;amp;amp;amp;page=3&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=GBP&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;curre', '/index.php', '2012-08-31 18:19:15', '2012-08-31 20:19:52', 16, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4185, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;amp;amp;amp;amp;amp;page=3&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=GBP&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;curre', '/index.php', '2012-08-31 16:03:55', '2012-08-31 16:03:56', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4184, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;amp;amp;amp;amp;amp;page=2&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=GBP&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;curre', '/index.php', '2012-08-31 12:51:12', '2012-08-31 15:22:38', 17, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4183, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;amp;amp;amp;amp;amp;page=2&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=EUR&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;curre', '/index.php', '2012-08-31 12:12:12', '2012-08-31 12:13:33', 3, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4182, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;amp;amp;amp;amp;amp;amp;page=3&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=CNY&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=CNY&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&a', '/index.php', '2012-08-31 06:43:22', '2012-08-31 09:28:33', 17, 'false', 'false', 'a:2:{i:4;i:1;i:127;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4181, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;amp;amp;amp;amp;amp;amp;page=3&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=CNY&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&a', '/index.php', '2012-08-31 06:04:22', '2012-08-31 06:04:22', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4180, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/', '/index.php', '2012-08-31 04:25:15', '2012-08-31 04:25:15', 1, 'false', 'false', '', '', '', 0);
INSERT INTO `supertracker` VALUES(4179, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;amp;amp;amp;amp;amp;amp;page=3&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&a', '/index.php', '2012-08-31 01:51:03', '2012-08-31 03:36:44', 14, 'false', 'false', 'a:2:{i:4;i:1;i:59;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4177, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;amp;amp;amp;amp;amp;amp;amp;page=2&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;amp;amp;amp;am', '/index.php', '2012-08-30 23:51:04', '2012-08-30 23:51:05', 2, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4178, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;amp;amp;amp;amp;amp;amp;page=1&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=CNY&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&a', '/index.php', '2012-08-31 00:24:54', '2012-08-31 01:11:26', 8, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);
INSERT INTO `supertracker` VALUES(4175, '187.184.109.123', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0', '', '', 0, 0, 'http://dev.cartstore.com/manage/configuration.php', 'gID=30&cID=885', '/', '/current_auctions.php', '2012-08-30 19:31:09', '2012-08-30 19:31:20', 2, 'false', 'false', 'b:0;', '', '', 0);
INSERT INTO `supertracker` VALUES(4176, '66.249.71.197', 'Mozilla/5.0 (compatible; Googlebot/2.1;  http://www.google.com/bot.html)', 'us', 'United States', 0, 0, '', '', '/index.php?currency=CAD&cPath=1_2_4&amp;amp;amp;amp;amp;amp;amp;amp;page=2&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=AUD&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;currency=CAD&amp;amp;amp;amp;amp;amp;amp;amp;am', '/index.php', '2012-08-30 19:32:08', '2012-08-30 20:28:07', 9, 'false', 'false', 'a:1:{i:4;i:1;}', '', '', 0);

CREATE TABLE IF NOT EXISTS `sw_default_delivery_time` (
  `defaultid` int(10) NOT NULL AUTO_INCREMENT,
  `dayid` int(10) NOT NULL DEFAULT '0',
  `slotid` int(10) NOT NULL DEFAULT '0',
  `cost` float NOT NULL DEFAULT '0',
  `max_limit` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`defaultid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;

INSERT INTO `sw_default_delivery_time` VALUES(1, 1, 1, 0, 68);
INSERT INTO `sw_default_delivery_time` VALUES(2, 1, 2, 0, 46);
INSERT INTO `sw_default_delivery_time` VALUES(3, 1, 3, 0, 12);
INSERT INTO `sw_default_delivery_time` VALUES(4, 1, 4, 0, 11);
INSERT INTO `sw_default_delivery_time` VALUES(5, 1, 5, 0, 11);
INSERT INTO `sw_default_delivery_time` VALUES(6, 1, 6, 0, 14);
INSERT INTO `sw_default_delivery_time` VALUES(7, 1, 7, 0, 34);
INSERT INTO `sw_default_delivery_time` VALUES(8, 2, 1, 0, 25);
INSERT INTO `sw_default_delivery_time` VALUES(9, 2, 2, 0, 54);
INSERT INTO `sw_default_delivery_time` VALUES(10, 2, 3, 0, 12);
INSERT INTO `sw_default_delivery_time` VALUES(11, 2, 4, 0, 11);
INSERT INTO `sw_default_delivery_time` VALUES(12, 2, 5, 0, 11);
INSERT INTO `sw_default_delivery_time` VALUES(13, 2, 6, 0, 14);
INSERT INTO `sw_default_delivery_time` VALUES(14, 2, 7, 0, 34);
INSERT INTO `sw_default_delivery_time` VALUES(15, 3, 1, 3, 67);
INSERT INTO `sw_default_delivery_time` VALUES(16, 3, 2, 0, 46);
INSERT INTO `sw_default_delivery_time` VALUES(17, 3, 3, 5, 12);
INSERT INTO `sw_default_delivery_time` VALUES(18, 3, 4, 1, 11);
INSERT INTO `sw_default_delivery_time` VALUES(19, 3, 5, 0, 11);
INSERT INTO `sw_default_delivery_time` VALUES(20, 3, 6, 3, 14);
INSERT INTO `sw_default_delivery_time` VALUES(21, 3, 7, 0, 34);
INSERT INTO `sw_default_delivery_time` VALUES(22, 4, 1, 3, 67);
INSERT INTO `sw_default_delivery_time` VALUES(23, 4, 2, 0, 46);
INSERT INTO `sw_default_delivery_time` VALUES(24, 4, 3, 5, 12);
INSERT INTO `sw_default_delivery_time` VALUES(25, 4, 4, 1, 11);
INSERT INTO `sw_default_delivery_time` VALUES(26, 4, 5, 0, 11);
INSERT INTO `sw_default_delivery_time` VALUES(27, 4, 6, 3, 14);
INSERT INTO `sw_default_delivery_time` VALUES(28, 4, 7, 0, 34);
INSERT INTO `sw_default_delivery_time` VALUES(29, 5, 1, 3, 67);
INSERT INTO `sw_default_delivery_time` VALUES(30, 5, 2, 0, 46);
INSERT INTO `sw_default_delivery_time` VALUES(31, 5, 3, 5, 12);
INSERT INTO `sw_default_delivery_time` VALUES(32, 5, 4, 1, 11);
INSERT INTO `sw_default_delivery_time` VALUES(33, 5, 5, 0, 11);
INSERT INTO `sw_default_delivery_time` VALUES(34, 5, 6, 3, 14);
INSERT INTO `sw_default_delivery_time` VALUES(35, 5, 7, 0, 34);
INSERT INTO `sw_default_delivery_time` VALUES(36, 6, 1, 3, 67);
INSERT INTO `sw_default_delivery_time` VALUES(37, 6, 2, 0, 46);
INSERT INTO `sw_default_delivery_time` VALUES(38, 6, 3, 5, 12);
INSERT INTO `sw_default_delivery_time` VALUES(39, 6, 4, 1, 11);
INSERT INTO `sw_default_delivery_time` VALUES(40, 6, 5, 0, 11);
INSERT INTO `sw_default_delivery_time` VALUES(41, 6, 6, 3, 14);
INSERT INTO `sw_default_delivery_time` VALUES(42, 6, 7, 0, 34);
INSERT INTO `sw_default_delivery_time` VALUES(43, 7, 1, 0, 10);
INSERT INTO `sw_default_delivery_time` VALUES(44, 7, 2, 0, 46);
INSERT INTO `sw_default_delivery_time` VALUES(45, 7, 3, 5, 12);
INSERT INTO `sw_default_delivery_time` VALUES(46, 7, 4, 1, 11);
INSERT INTO `sw_default_delivery_time` VALUES(47, 7, 5, 0, 11);
INSERT INTO `sw_default_delivery_time` VALUES(48, 7, 6, 3, 14);
INSERT INTO `sw_default_delivery_time` VALUES(49, 7, 7, 0, 34);

CREATE TABLE IF NOT EXISTS `sw_emargengency_delivery_time` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `delv_date` date NOT NULL DEFAULT '0000-00-00',
  `slotid` int(10) NOT NULL DEFAULT '0',
  `em_cost` float NOT NULL DEFAULT '0',
  `em_max_limit` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `sw_emargengency_delivery_time` VALUES(1, '2006-05-26', 2, 1, 0);
INSERT INTO `sw_emargengency_delivery_time` VALUES(3, '2006-06-30', 3, 5, 11);
INSERT INTO `sw_emargengency_delivery_time` VALUES(4, '2006-05-27', 5, 3, 44);

CREATE TABLE IF NOT EXISTS `sw_time_slots` (
  `slotid` int(10) NOT NULL AUTO_INCREMENT,
  `slot` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`slotid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

INSERT INTO `sw_time_slots` VALUES(1, '8AM-10AM');
INSERT INTO `sw_time_slots` VALUES(2, '10AM-12PM');
INSERT INTO `sw_time_slots` VALUES(3, '12PM-2PM');
INSERT INTO `sw_time_slots` VALUES(4, '2PM-4PM');
INSERT INTO `sw_time_slots` VALUES(5, '4PM-6PM');
INSERT INTO `sw_time_slots` VALUES(6, '6PM-8PM');
INSERT INTO `sw_time_slots` VALUES(7, '8PM-10PM');

CREATE TABLE IF NOT EXISTS `sw_week_days` (
  `dayid` int(10) NOT NULL AUTO_INCREMENT,
  `day` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`dayid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

INSERT INTO `sw_week_days` VALUES(1, 'Sunday');
INSERT INTO `sw_week_days` VALUES(2, 'Monday');
INSERT INTO `sw_week_days` VALUES(3, 'Tuesday');
INSERT INTO `sw_week_days` VALUES(4, 'Wednesday');
INSERT INTO `sw_week_days` VALUES(5, 'Thursday');
INSERT INTO `sw_week_days` VALUES(6, 'Friday');
INSERT INTO `sw_week_days` VALUES(7, 'Saturday');

CREATE TABLE IF NOT EXISTS `tax_class` (
  `tax_class_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_class_title` varchar(32) NOT NULL DEFAULT '',
  `tax_class_description` varchar(255) NOT NULL DEFAULT '',
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`tax_class_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

INSERT INTO `tax_class` VALUES(1, 'Taxable Goods', 'The following types of products are included non-food, services, etc', '2006-11-08 21:23:27', '2006-06-15 13:53:26');

CREATE TABLE IF NOT EXISTS `tax_rates` (
  `tax_rates_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_zone_id` int(11) NOT NULL DEFAULT '0',
  `tax_class_id` int(11) NOT NULL DEFAULT '0',
  `tax_priority` int(5) DEFAULT '1',
  `tax_rate` decimal(7,4) NOT NULL DEFAULT '0.0000',
  `tax_description` varchar(255) NOT NULL DEFAULT '',
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`tax_rates_id`),
  KEY `tax_zone_id` (`tax_zone_id`),
  KEY `tax_class_id` (`tax_class_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

INSERT INTO `tax_rates` VALUES(3, 14, 1, 100, 8.2500, 'Sales Tax', '2009-12-04 16:23:29', '2006-10-24 10:29:39');
INSERT INTO `tax_rates` VALUES(5, 24, 1, 90, 8.7500, 'WA State Tax', NULL, '2012-09-20 22:37:18');

CREATE TABLE IF NOT EXISTS `thread` (
  `threadid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `lastpost` int(10) unsigned NOT NULL DEFAULT '0',
  `forumid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `replycount` int(10) unsigned NOT NULL DEFAULT '0',
  `postusername` char(50) NOT NULL DEFAULT '',
  `lastpostuser` char(50) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `allowposting` smallint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`threadid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `topics` (
  `topics_id` int(11) NOT NULL AUTO_INCREMENT,
  `topics_image` varchar(64) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(3) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`topics_id`),
  KEY `idx_topics_parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

INSERT INTO `topics` VALUES(24, NULL, 0, 0, '2011-09-12 18:54:10', NULL);

CREATE TABLE IF NOT EXISTS `topics_description` (
  `topics_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `topics_name` varchar(32) NOT NULL DEFAULT '',
  `topics_heading_title` varchar(64) DEFAULT NULL,
  `topics_description` text,
  PRIMARY KEY (`topics_id`,`language_id`),
  KEY `idx_topics_name` (`topics_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `topics_description` VALUES(24, 1, 'Sample Articles', 'Sample Articles', '');

CREATE TABLE IF NOT EXISTS `vendors` (
  `vendors_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendors_contact` varchar(32) NOT NULL DEFAULT '',
  `vendors_name` varchar(32) NOT NULL DEFAULT '',
  `vendors_phone1` varchar(20) NOT NULL DEFAULT '',
  `vendors_phone2` varchar(20) NOT NULL DEFAULT '',
  `vendors_fax` varchar(20) NOT NULL DEFAULT '',
  `vendors_email` varchar(64) NOT NULL DEFAULT '',
  `vendors_url` varchar(64) NOT NULL DEFAULT '',
  `vendors_comments` text,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `vendors_image` varchar(32) DEFAULT NULL,
  `vendors_send_email` tinyint(1) DEFAULT '0',
  `vendors_status_send` int(11) NOT NULL DEFAULT '2',
  `vendors_zipcode` varchar(11) NOT NULL DEFAULT '',
  `vendor_street` varchar(32) DEFAULT NULL,
  `vendor_add2` varchar(32) DEFAULT NULL,
  `vendor_city` varchar(32) NOT NULL DEFAULT '',
  `vendor_state` varchar(32) DEFAULT NULL,
  `vendor_country` varchar(32) DEFAULT NULL,
  `vendor_add_info` text,
  `account_number` varchar(32) DEFAULT NULL,
  `handling_charge` decimal(5,2) NOT NULL DEFAULT '0.00',
  `handling_per_box` decimal(5,3) NOT NULL DEFAULT '0.000',
  `tare_weight` decimal(5,2) NOT NULL DEFAULT '0.00',
  `max_box_weight` decimal(15,3) unsigned NOT NULL DEFAULT '0.000',
  `percent_tare_weight` int(3) NOT NULL DEFAULT '0',
  `zones` int(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`vendors_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Vendors table add-in' AUTO_INCREMENT=8 ;

INSERT INTO `vendors` VALUES(7, '', 'default (this store) DONT DELETE', '', '', '', '', '', NULL, NULL, NULL, NULL, 0, 2, '', NULL, NULL, '', NULL, NULL, NULL, NULL, 0.00, 0.000, 0.00, 0.000, 0, 1);

CREATE TABLE IF NOT EXISTS `vendors_info` (
  `vendors_id` int(16) NOT NULL DEFAULT '0',
  `languages_id` int(11) NOT NULL DEFAULT '0',
  `vendors_url` varchar(255) NOT NULL DEFAULT '',
  `url_clicked` int(5) NOT NULL DEFAULT '0',
  `date_last_click` datetime DEFAULT NULL,
  PRIMARY KEY (`vendors_id`,`languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `vendor_configuration` (
  `vendor_configuration_id` int(11) NOT NULL AUTO_INCREMENT,
  `configuration_title` varchar(64) NOT NULL DEFAULT '',
  `configuration_key` varchar(64) NOT NULL DEFAULT '',
  `configuration_value` text,
  `configuration_description` varchar(255) NOT NULL DEFAULT '',
  `configuration_group_id` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(5) DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `use_function` varchar(255) DEFAULT NULL,
  `set_function` text,
  `vendors_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`vendor_configuration_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=155 ;

INSERT INTO `vendor_configuration` VALUES(1, 'Installed Modules', 'MODULE_VENDOR_SHIPPING_INSTALLED_1', 'fedex1.php;freeamount.php;usps.php', 'This is automatically updated. No need to edit.', 6, 0, '2010-10-29 08:58:43', '2007-05-01 03:31:11', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(25, 'Shipping Zone', 'MODULE_SHIPPING_UPS_ZONE_3', '0', 'If a zone is selected, only enable this shipping method for that zone.', 6, 0, '0000-00-00 00:00:00', '2007-05-02 23:25:23', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', 3);
INSERT INTO `vendor_configuration` VALUES(23, 'Handling Fee', 'MODULE_SHIPPING_UPS_HANDLING_3', '0', 'Handling fee for this shipping method.', 6, 0, '0000-00-00 00:00:00', '2007-05-02 23:25:23', '', '', 3);
INSERT INTO `vendor_configuration` VALUES(24, 'Tax Class', 'MODULE_SHIPPING_UPS_TAX_CLASS_3', '0', 'Use the following tax class on the shipping fee.', 6, 0, '0000-00-00 00:00:00', '2007-05-02 23:25:23', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', 3);
INSERT INTO `vendor_configuration` VALUES(21, 'UPS Packaging?', 'MODULE_SHIPPING_UPS_PACKAGE_3', 'CP', 'CP - Your Packaging, ULE - UPS Letter, UT - UPS Tube, UBE - UPS Express Box', 6, 0, '0000-00-00 00:00:00', '2007-05-02 23:25:23', '', '', 3);
INSERT INTO `vendor_configuration` VALUES(22, 'Residential Delivery?', 'MODULE_SHIPPING_UPS_RES_3', 'RES', 'Quote for Residential (RES) or Commercial Delivery (COM)', 6, 0, '0000-00-00 00:00:00', '2007-05-02 23:25:23', '', '', 3);
INSERT INTO `vendor_configuration` VALUES(19, 'Enable UPS Shipping', 'MODULE_SHIPPING_UPS_STATUS_3', 'True', 'Do you want to offer UPS shipping?', 6, 0, '0000-00-00 00:00:00', '2007-05-02 23:25:23', '', 'tep_cfg_select_option(array(''True'', ''False''), ', 3);
INSERT INTO `vendor_configuration` VALUES(20, 'UPS Pickup Method', 'MODULE_SHIPPING_UPS_PICKUP_3', 'CC', 'How do you give packages to UPS? CC - Customer Counter, RDP - Daily Pickup, OTP - One Time Pickup, LC - Letter Center, OCA - On Call Air', 6, 0, '0000-00-00 00:00:00', '2007-05-02 23:25:23', '', '', 3);
INSERT INTO `vendor_configuration` VALUES(18, 'Installed Modules', 'MODULE_VENDOR_SHIPPING_INSTALLED_3', 'ups.php', 'This is automatically updated. No need to edit.', 6, 0, '2007-05-02 23:25:23', '2007-05-02 23:25:19', '', '', 3);
INSERT INTO `vendor_configuration` VALUES(26, 'Sort order of display.', 'MODULE_SHIPPING_UPS_SORT_ORDER_3', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, '0000-00-00 00:00:00', '2007-05-02 23:25:23', '', '', 3);
INSERT INTO `vendor_configuration` VALUES(27, 'Shipping Methods', 'MODULE_SHIPPING_UPS_TYPES_3', '1DM, 1DML, 1DA, 1DAL, 1DAPI, 1DP, 1DPL, 2DM, 2DML, 2DA, 2DAL, 3DS, GND, STD, XPR, XPRL, XDM, XDML, XPD, --none--', 'Select the USPS services to be offered.', 6, 13, '0000-00-00 00:00:00', '2007-05-02 23:25:23', '', 'tep_cfg_select_multioption(array(''1DM'',''1DML'', ''1DA'', ''1DAL'', ''1DAPI'', ''1DP'', ''1DPL'', ''2DM'', ''2DML'', ''2DA'', ''2DAL'', ''3DS'',''GND'', ''STD'', ''XPR'', ''XPRL'', ''XDM'', ''XDML'', ''XPD''), ', 3);
INSERT INTO `vendor_configuration` VALUES(61, 'Enable Free Shipping with Minimum Purchase', 'MODULE_SHIPPING_FREEAMOUNT_STATUS_1', 'True', 'Do you want to offer minimum order free shipping?', 6, 7, '0000-00-00 00:00:00', '2007-06-29 01:17:40', '', 'tep_cfg_select_option(array(''True'', ''False''), ', 1);
INSERT INTO `vendor_configuration` VALUES(62, 'Maximum Weight', 'MODULE_SHIPPING_FREEAMOUNT_WEIGHT_MAX_1', '100', 'What is the maximum weight you will ship?', 6, 8, '0000-00-00 00:00:00', '2007-06-29 01:17:40', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(63, 'Enable Display', 'MODULE_SHIPPING_FREEAMOUNT_DISPLAY_1', 'True', 'Do you want to display text way if the minimum amount is not reached?', 6, 7, '0000-00-00 00:00:00', '2007-06-29 01:17:40', '', 'tep_cfg_select_option(array(''True'', ''False''), ', 1);
INSERT INTO `vendor_configuration` VALUES(64, 'Minimum Cost', 'MODULE_SHIPPING_FREEAMOUNT_AMOUNT_1', '50.00', 'Minimum order amount purchased before shipping is free?', 6, 8, '0000-00-00 00:00:00', '2007-06-29 01:17:40', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(65, 'Sort Order', 'MODULE_SHIPPING_FREEAMOUNT_SORT_ORDER_1', '0', 'Sort order of display.', 6, 0, '0000-00-00 00:00:00', '2007-06-29 01:17:40', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(66, 'Tax Class', 'MODULE_SHIPPING_FREEAMOUNT_TAX_CLASS_1', '0', 'Use the following tax class on the shipping fee.', 6, 0, '0000-00-00 00:00:00', '2007-06-29 01:17:40', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', 1);
INSERT INTO `vendor_configuration` VALUES(67, 'Shipping Zone', 'MODULE_SHIPPING_FREEAMOUNT_ZONE_1', '10', 'If a zone is selected, only enable this shipping method for that zone.', 6, 0, '0000-00-00 00:00:00', '2007-06-29 01:17:40', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', 1);
INSERT INTO `vendor_configuration` VALUES(68, 'Installed Modules', 'MODULE_VENDOR_SHIPPING_INSTALLED_4', 'spu.php', 'This is automatically updated. No need to edit.', 6, 0, '2010-08-05 16:33:48', '2008-03-02 14:35:38', '', '', 4);
INSERT INTO `vendor_configuration` VALUES(91, 'Postal code', 'MODULE_SHIPPING_FEDEX1_POSTAL_1', '32839', 'Enter the postal code for the ship from street address, required', 6, 17, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(90, 'State or Province name', 'MODULE_SHIPPING_FEDEX1_STATE_1', 'fl', 'Enter the 2 letter state or province name for the ship from street address, required for Canada and US', 6, 16, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(89, 'City name', 'MODULE_SHIPPING_FEDEX1_CITY_1', 'orlando', 'Enter the city name for the ship from street address, required', 6, 15, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(88, 'Second line of street address', 'MODULE_SHIPPING_FEDEX1_ADDRESS_2_1', 'NONE', 'Enter the second line of your ship from street address, leave set to NONE if you do not need to specify a second line', 6, 14, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(86, 'Weight Units', 'MODULE_SHIPPING_FEDEX1_WEIGHT_1', 'LBS', 'Weight Units:', 6, 19, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', 'tep_cfg_select_option(array(''LBS'', ''KGS''), ', 1);
INSERT INTO `vendor_configuration` VALUES(87, 'First line of street address', 'MODULE_SHIPPING_FEDEX1_ADDRESS_1_1', '5112 park central dr', 'Enter the first line of your ship from street address, required', 6, 13, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(84, 'cURL Path', 'MODULE_SHIPPING_FEDEX1_CURL_1', 'NONE', 'Enter the path to the cURL program, normally, leave this set to NONE to execute cURL using PHP', 6, 12, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(85, 'Debug Mode', 'MODULE_SHIPPING_FEDEX1_DEBUG_1', 'False', 'Turn on Debug', 6, 19, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', 'tep_cfg_select_option(array(''True'', ''False''), ', 1);
INSERT INTO `vendor_configuration` VALUES(82, 'Your Fedex Account Number', 'MODULE_SHIPPING_FEDEX1_ACCOUNT_1', '165369092', 'Enter the fedex Account Number assigned to you, required', 6, 11, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(83, 'Your Fedex Meter ID', 'MODULE_SHIPPING_FEDEX1_METER_1', '6089684', 'Enter the Fedex MeterID assigned to you, set to NONE to obtain a new meter number', 6, 12, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(81, 'Display Transit Times', 'MODULE_SHIPPING_FEDEX1_TRANSIT_1', 'True', 'Do you want to show transit times for ground or home delivery rates?', 6, 10, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', 'tep_cfg_select_option(array(''True'', ''False''), ', 1);
INSERT INTO `vendor_configuration` VALUES(80, 'Enable Fedex Shipping', 'MODULE_SHIPPING_FEDEX1_STATUS_1', 'True', 'Do you want to offer Fedex shipping?', 6, 10, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', 'tep_cfg_select_option(array(''True'', ''False''), ', 1);
INSERT INTO `vendor_configuration` VALUES(92, 'Phone number', 'MODULE_SHIPPING_FEDEX1_PHONE_1', '8007687851', 'Enter a contact phone number for your company, required', 6, 18, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(93, 'Which server to use', 'MODULE_SHIPPING_FEDEX1_SERVER_1', 'production', 'You must have an account with Fedex', 6, 19, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', 'tep_cfg_select_option(array(''test'', ''production''), ', 1);
INSERT INTO `vendor_configuration` VALUES(94, 'Drop off type', 'MODULE_SHIPPING_FEDEX1_DROPOFF_1', '5', 'Dropoff type (1 = Regular pickup, 2 = request courier, 3 = drop box, 4 = drop at BSC, 5 = drop at station)?', 6, 20, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(95, 'Fedex surcharge?', 'MODULE_SHIPPING_FEDEX1_SURCHARGE_1', '0', 'Surcharge amount to add to shipping charge?', 6, 21, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(96, 'Show List Rates?', 'MODULE_SHIPPING_FEDEX1_LIST_RATES_1', 'False', 'Show LIST Rates?', 6, 21, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', 'tep_cfg_select_option(array(''True'', ''False''), ', 1);
INSERT INTO `vendor_configuration` VALUES(97, 'Residential surcharge?', 'MODULE_SHIPPING_FEDEX1_RESIDENTIAL_1', '0', 'Residential Surcharge (in addition to other surcharge) for Express packages within US, or ground packages within Canada?', 6, 21, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(98, 'Insurance?', 'MODULE_SHIPPING_FEDEX1_INSURE_1', 'NONE', 'Insure packages over what dollar amount?', 6, 22, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(99, 'Enable Envelope Rates?', 'MODULE_SHIPPING_FEDEX1_ENVELOPE_1', 'False', 'Do you want to offer Fedex Envelope rates? All items under 1/2 LB (.23KG) will quote using the envelope rate if True.', 6, 10, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', 'tep_cfg_select_option(array(''True'', ''False''), ', 1);
INSERT INTO `vendor_configuration` VALUES(100, 'Sort rates: ', 'MODULE_SHIPPING_FEDEX1_WEIGHT_SORT_1', 'High to Low', 'Sort rates top to bottom: ', 6, 19, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', 'tep_cfg_select_option(array(''High to Low'', ''Low to High''), ', 1);
INSERT INTO `vendor_configuration` VALUES(101, 'Timeout in Seconds', 'MODULE_SHIPPING_FEDEX1_TIMEOUT_1', 'NONE', 'Enter the maximum time in seconds you would wait for a rate request from Fedex? Leave NONE for default timeout.', 6, 22, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(102, 'Tax Class', 'MODULE_SHIPPING_FEDEX1_TAX_CLASS_1', '0', 'Use the following tax class on the shipping fee.', 6, 23, '0000-00-00 00:00:00', '2009-10-14 15:48:47', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', 1);
INSERT INTO `vendor_configuration` VALUES(103, 'Shipping Zone', 'MODULE_SHIPPING_FEDEX1_ZONE_1', '0', 'If a zone is selected, only enable this shipping method for that zone.', 6, 0, '0000-00-00 00:00:00', '2009-10-14 15:48:47', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', 1);
INSERT INTO `vendor_configuration` VALUES(104, 'Sort Order', 'MODULE_SHIPPING_FEDEX1_SORT_ORDER_1', '0', 'Sort order of display.', 6, 24, '0000-00-00 00:00:00', '2009-10-14 15:48:47', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(105, 'Enable UPS Shipping', 'MODULE_SHIPPING_UPS_STATUS_1', 'True', 'Do you want to offer UPS shipping?', 6, 0, '0000-00-00 00:00:00', '2009-10-14 15:49:56', '', 'tep_cfg_select_option(array(''True'', ''False''), ', 1);
INSERT INTO `vendor_configuration` VALUES(106, 'UPS Pickup Method', 'MODULE_SHIPPING_UPS_PICKUP_1', 'CC', 'How do you give packages to UPS? CC - Customer Counter, RDP - Daily Pickup, OTP - One Time Pickup, LC - Letter Center, OCA - On Call Air', 6, 0, '0000-00-00 00:00:00', '2009-10-14 15:49:56', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(107, 'UPS Packaging?', 'MODULE_SHIPPING_UPS_PACKAGE_1', 'CP', 'CP - Your Packaging, ULE - UPS Letter, UT - UPS Tube, UBE - UPS Express Box', 6, 0, '0000-00-00 00:00:00', '2009-10-14 15:49:56', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(108, 'Residential Delivery?', 'MODULE_SHIPPING_UPS_RES_1', 'RES', 'Quote for Residential (RES) or Commercial Delivery (COM)', 6, 0, '0000-00-00 00:00:00', '2009-10-14 15:49:56', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(109, 'Handling Fee', 'MODULE_SHIPPING_UPS_HANDLING_1', '0', 'Handling fee for this shipping method.', 6, 0, '0000-00-00 00:00:00', '2009-10-14 15:49:56', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(110, 'Tax Class', 'MODULE_SHIPPING_UPS_TAX_CLASS_1', '0', 'Use the following tax class on the shipping fee.', 6, 0, '0000-00-00 00:00:00', '2009-10-14 15:49:56', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', 1);
INSERT INTO `vendor_configuration` VALUES(111, 'Shipping Zone', 'MODULE_SHIPPING_UPS_ZONE_1', '0', 'If a zone is selected, only enable this shipping method for that zone.', 6, 0, '0000-00-00 00:00:00', '2009-10-14 15:49:56', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', 1);
INSERT INTO `vendor_configuration` VALUES(112, 'Sort order of display.', 'MODULE_SHIPPING_UPS_SORT_ORDER_1', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, '0000-00-00 00:00:00', '2009-10-14 15:49:56', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(113, 'Shipping Methods', 'MODULE_SHIPPING_UPS_TYPES_1', '1DA, 2DA, 3DS, GND, STD, --none--', 'Select the USPS services to be offered.', 6, 13, '0000-00-00 00:00:00', '2009-10-14 15:49:56', '', 'tep_cfg_select_multioption(array(''1DM'',''1DML'', ''1DA'', ''1DAL'', ''1DAPI'', ''1DP'', ''1DPL'', ''2DM'', ''2DML'', ''2DA'', ''2DAL'', ''3DS'',''GND'', ''STD'', ''XPR'', ''XPRL'', ''XDM'', ''XDML'', ''XPD''), ', 1);
INSERT INTO `vendor_configuration` VALUES(114, 'Enable USPS Shipping', 'MODULE_SHIPPING_USPS_STATUS_1', 'True', 'Do you want to offer USPS shipping?', 6, 0, '0000-00-00 00:00:00', '2009-10-14 15:50:46', '', 'tep_cfg_select_option(array(''True'', ''False''), ', 1);
INSERT INTO `vendor_configuration` VALUES(115, 'Enter the USPS User ID', 'MODULE_SHIPPING_USPS_USERID_1', '048STRON2639', 'Enter the USPS USERID assigned to you.', 6, 0, '0000-00-00 00:00:00', '2009-10-14 15:50:46', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(116, 'Enter the USPS Password', 'MODULE_SHIPPING_USPS_PASSWORD_1', '048STRON2639', 'See USERID, above.', 6, 0, '0000-00-00 00:00:00', '2009-10-14 15:50:46', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(117, 'Which server to use', 'MODULE_SHIPPING_USPS_SERVER_1', 'production', 'An account at USPS is needed to use the Production server', 6, 0, '0000-00-00 00:00:00', '2009-10-14 15:50:46', '', 'tep_cfg_select_option(array(''test'', ''production''), ', 1);
INSERT INTO `vendor_configuration` VALUES(118, 'Handling Fee', 'MODULE_SHIPPING_USPS_HANDLING_1', '0', 'Handling fee for this shipping method.', 6, 0, '0000-00-00 00:00:00', '2009-10-14 15:50:46', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(119, 'Tax Class', 'MODULE_SHIPPING_USPS_TAX_CLASS_1', '0', 'Use the following tax class on the shipping fee.', 6, 0, '0000-00-00 00:00:00', '2009-10-14 15:50:46', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', 1);
INSERT INTO `vendor_configuration` VALUES(120, 'Shipping Zone', 'MODULE_SHIPPING_USPS_ZONE_1', '0', 'If a zone is selected, only enable this shipping method for that zone.', 6, 0, '0000-00-00 00:00:00', '2009-10-14 15:50:46', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', 1);
INSERT INTO `vendor_configuration` VALUES(121, 'Sort Order', 'MODULE_SHIPPING_USPS_SORT_ORDER_1', '0', 'Sort order of display.', 6, 0, '0000-00-00 00:00:00', '2009-10-14 15:50:46', '', '', 1);
INSERT INTO `vendor_configuration` VALUES(122, 'Domestic Shipping Methods', 'MODULE_SHIPPING_USPS_TYPES_1', 'Express, Priority, First Class, Parcel, --none--', 'Select the domestic services to be offered:', 6, 14, '0000-00-00 00:00:00', '2009-10-14 15:50:46', '', 'tep_cfg_select_multioption(array(''Express'', ''Priority'', ''First Class'', ''Parcel''), ', 1);
INSERT INTO `vendor_configuration` VALUES(123, 'Int''l Shipping Methods', 'MODULE_SHIPPING_USPS_TYPES_INTL_1', 'GXG Document, GXG Non-Document, Express, Priority Lg, Priority Sm, Priority Var, Airmail Letter, Airmail Parcel, Surface Letter, Surface Post, --none--', 'Select the international services to be offered:', 6, 15, '0000-00-00 00:00:00', '2009-10-14 15:50:46', '', 'tep_cfg_select_multioption(array(''GXG Document'', ''GXG Non-Document'', ''Express'', ''Priority Lg'', ''Priority Sm'', ''Priority Var'', ''Airmail Letter'', ''Airmail Parcel'', ''Surface Letter'', ''Surface Post''), ', 1);
INSERT INTO `vendor_configuration` VALUES(124, 'USPS Options', 'MODULE_SHIPPING_USPS_OPTIONS_1', 'Display weight, Display transit time, --none--', 'Select from the following the USPS options.', 6, 16, '0000-00-00 00:00:00', '2009-10-14 15:50:46', '', 'tep_cfg_select_multioption(array(''Display weight'', ''Display transit time''), ', 1);
INSERT INTO `vendor_configuration` VALUES(153, 'Shipping Text', 'MODULE_SHIPPING_SPU_SHIP_TEXT_4', 'Pickup during regular business hours.', 'The text the cusotmer will see explaining this method.', 6, 0, NULL, '2010-08-05 16:33:48', NULL, NULL, 4);
INSERT INTO `vendor_configuration` VALUES(152, 'Sort Order', 'MODULE_SHIPPING_SPU_SORT_ORDER_4', '0', 'Sort order of display.', 6, 0, NULL, '2010-08-05 16:33:48', NULL, NULL, 4);
INSERT INTO `vendor_configuration` VALUES(150, 'Tax Class', 'MODULE_SHIPPING_SPU_TAX_CLASS_4', '0', 'Use the following tax class on the Store Pickup fee(if any).', 6, 0, NULL, '2010-08-05 16:33:48', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', 4);
INSERT INTO `vendor_configuration` VALUES(151, 'Shipping Zone', 'MODULE_SHIPPING_SPU_ZONE_4', '0', 'If a zone is selected, only enable Store Pickup for that zone.', 6, 0, NULL, '2010-08-05 16:33:48', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', 4);
INSERT INTO `vendor_configuration` VALUES(149, 'Cost', 'MODULE_SHIPPING_SPU_COST_4', '5.00', 'The Store Pickup(if any) for all orders using this shipping method.', 6, 0, NULL, '2010-08-05 16:33:48', NULL, NULL, 4);
INSERT INTO `vendor_configuration` VALUES(148, 'Enable Store Pickup', 'MODULE_SHIPPING_SPU_STATUS_4', 'True', 'Do you want to offer spu rate shipping?', 6, 0, NULL, '2010-08-05 16:33:48', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ', 4);
INSERT INTO `vendor_configuration` VALUES(154, 'Installed Modules', 'MODULE_VENDOR_SHIPPING_INSTALLED_7', '', 'This is automatically updated. No need to edit.', 6, 0, NULL, '2011-11-30 23:16:46', NULL, NULL, 7);

CREATE TABLE IF NOT EXISTS `webcfg` (
  `id` int(2) NOT NULL DEFAULT '0',
  `webname` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `weburl` varchar(250) NOT NULL DEFAULT '',
  `counter` varchar(8) NOT NULL DEFAULT '0',
  `langcode` varchar(30) NOT NULL DEFAULT 'english',
  `adminemail` varchar(250) NOT NULL DEFAULT '',
  `adminid` varchar(100) NOT NULL DEFAULT '',
  `adminpsd` varchar(100) NOT NULL DEFAULT '',
  `countrycode` char(2) NOT NULL DEFAULT 'tw',
  `timezone` int(3) NOT NULL DEFAULT '8',
  `topmsg` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `footmsg` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `calstartdate` varchar(6) DEFAULT '199001',
  `datetype` varchar(30) NOT NULL DEFAULT 'yymmdd',
  `firstweek` char(3) NOT NULL DEFAULT 'sun',
  `theme` varchar(40) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`adminid`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `whos_online` (
  `customer_id` int(11) DEFAULT NULL,
  `full_name` varchar(64) NOT NULL DEFAULT '',
  `session_id` varchar(128) NOT NULL DEFAULT '',
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `time_entry` varchar(14) NOT NULL DEFAULT '',
  `time_last_click` varchar(14) NOT NULL DEFAULT '',
  `last_page_url` varchar(64) NOT NULL DEFAULT '',
  `http_referer` varchar(255) NOT NULL DEFAULT '',
  `user_agent` varchar(255) NOT NULL DEFAULT '',
  KEY `session_id` (`session_id`),
  KEY `ip_address` (`ip_address`),
  KEY `time_last_click` (`time_last_click`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `whos_online` VALUES(-1, 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/b', '66.249.73.35', '66.249.73.35', '1350593667', '1350594554', '/allprods.php?fl=c', '', 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');

CREATE TABLE IF NOT EXISTS `zipcodes` (
  `id` int(11) NOT NULL DEFAULT '0',
  `FIPS` varchar(4) NOT NULL DEFAULT '',
  `zipcode` varchar(9) NOT NULL DEFAULT '',
  `state` char(2) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `lat` double NOT NULL DEFAULT '0',
  `lon` double NOT NULL DEFAULT '0',
  `population` int(11) NOT NULL DEFAULT '0',
  `allocation` double NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `zones` (
  `zone_id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_country_id` int(11) NOT NULL DEFAULT '0',
  `zone_code` varchar(32) NOT NULL DEFAULT '',
  `zone_name` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`zone_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=319 ;

INSERT INTO `zones` VALUES(1, 223, 'AL', 'Alabama');
INSERT INTO `zones` VALUES(2, 223, 'AK', 'Alaska');
INSERT INTO `zones` VALUES(3, 223, 'AS', 'American Samoa');
INSERT INTO `zones` VALUES(4, 223, 'AZ', 'Arizona');
INSERT INTO `zones` VALUES(5, 223, 'AR', 'Arkansas');
INSERT INTO `zones` VALUES(6, 223, 'AF', 'Armed Forces Africa');
INSERT INTO `zones` VALUES(7, 223, 'AA', 'Armed Forces Americas');
INSERT INTO `zones` VALUES(8, 223, 'AC', 'Armed Forces Canada');
INSERT INTO `zones` VALUES(9, 223, 'AE', 'Armed Forces Europe');
INSERT INTO `zones` VALUES(10, 223, 'AM', 'Armed Forces Middle East');
INSERT INTO `zones` VALUES(11, 223, 'AP', 'Armed Forces Pacific');
INSERT INTO `zones` VALUES(12, 223, 'CA', 'California');
INSERT INTO `zones` VALUES(13, 223, 'CO', 'Colorado');
INSERT INTO `zones` VALUES(14, 223, 'CT', 'Connecticut');
INSERT INTO `zones` VALUES(15, 223, 'DE', 'Delaware');
INSERT INTO `zones` VALUES(16, 223, 'DC', 'District of Columbia');
INSERT INTO `zones` VALUES(17, 223, 'FM', 'Federated States Of Micronesia');
INSERT INTO `zones` VALUES(18, 223, 'FL', 'Florida');
INSERT INTO `zones` VALUES(19, 223, 'GA', 'Georgia');
INSERT INTO `zones` VALUES(20, 223, 'GU', 'Guam');
INSERT INTO `zones` VALUES(21, 223, 'HI', 'Hawaii');
INSERT INTO `zones` VALUES(22, 223, 'ID', 'Idaho');
INSERT INTO `zones` VALUES(23, 223, 'IL', 'Illinois');
INSERT INTO `zones` VALUES(24, 223, 'IN', 'Indiana');
INSERT INTO `zones` VALUES(25, 223, 'IA', 'Iowa');
INSERT INTO `zones` VALUES(26, 223, 'KS', 'Kansas');
INSERT INTO `zones` VALUES(27, 223, 'KY', 'Kentucky');
INSERT INTO `zones` VALUES(28, 223, 'LA', 'Louisiana');
INSERT INTO `zones` VALUES(29, 223, 'ME', 'Maine');
INSERT INTO `zones` VALUES(30, 223, 'MH', 'Marshall Islands');
INSERT INTO `zones` VALUES(31, 223, 'MD', 'Maryland');
INSERT INTO `zones` VALUES(32, 223, 'MA', 'Massachusetts');
INSERT INTO `zones` VALUES(33, 223, 'MI', 'Michigan');
INSERT INTO `zones` VALUES(34, 223, 'MN', 'Minnesota');
INSERT INTO `zones` VALUES(35, 223, 'MS', 'Mississippi');
INSERT INTO `zones` VALUES(36, 223, 'MO', 'Missouri');
INSERT INTO `zones` VALUES(37, 223, 'MT', 'Montana');
INSERT INTO `zones` VALUES(38, 223, 'NE', 'Nebraska');
INSERT INTO `zones` VALUES(39, 223, 'NV', 'Nevada');
INSERT INTO `zones` VALUES(40, 223, 'NH', 'New Hampshire');
INSERT INTO `zones` VALUES(41, 223, 'NJ', 'New Jersey');
INSERT INTO `zones` VALUES(42, 223, 'NM', 'New Mexico');
INSERT INTO `zones` VALUES(43, 223, 'NY', 'New York');
INSERT INTO `zones` VALUES(44, 223, 'NC', 'North Carolina');
INSERT INTO `zones` VALUES(45, 223, 'ND', 'North Dakota');
INSERT INTO `zones` VALUES(46, 223, 'MP', 'Northern Mariana Islands');
INSERT INTO `zones` VALUES(47, 223, 'OH', 'Ohio');
INSERT INTO `zones` VALUES(48, 223, 'OK', 'Oklahoma');
INSERT INTO `zones` VALUES(49, 223, 'OR', 'Oregon');
INSERT INTO `zones` VALUES(50, 223, 'PW', 'Palau');
INSERT INTO `zones` VALUES(51, 223, 'PA', 'Pennsylvania');
INSERT INTO `zones` VALUES(52, 223, 'PR', 'Puerto Rico');
INSERT INTO `zones` VALUES(53, 223, 'RI', 'Rhode Island');
INSERT INTO `zones` VALUES(54, 223, 'SC', 'South Carolina');
INSERT INTO `zones` VALUES(55, 223, 'SD', 'South Dakota');
INSERT INTO `zones` VALUES(56, 223, 'TN', 'Tennessee');
INSERT INTO `zones` VALUES(57, 223, 'TX', 'Texas');
INSERT INTO `zones` VALUES(58, 223, 'UT', 'Utah');
INSERT INTO `zones` VALUES(59, 223, 'VT', 'Vermont');
INSERT INTO `zones` VALUES(60, 223, 'VI', 'Virgin Islands');
INSERT INTO `zones` VALUES(61, 223, 'VA', 'Virginia');
INSERT INTO `zones` VALUES(62, 223, 'WA', 'Washington');
INSERT INTO `zones` VALUES(63, 223, 'WV', 'West Virginia');
INSERT INTO `zones` VALUES(64, 223, 'WI', 'Wisconsin');
INSERT INTO `zones` VALUES(65, 223, 'WY', 'Wyoming');
INSERT INTO `zones` VALUES(66, 38, 'AB', 'Alberta');
INSERT INTO `zones` VALUES(67, 38, 'BC', 'British Columbia');
INSERT INTO `zones` VALUES(68, 38, 'MB', 'Manitoba');
INSERT INTO `zones` VALUES(69, 38, 'NF', 'Newfoundland');
INSERT INTO `zones` VALUES(70, 38, 'NB', 'New Brunswick');
INSERT INTO `zones` VALUES(71, 38, 'NS', 'Nova Scotia');
INSERT INTO `zones` VALUES(72, 38, 'NT', 'Northwest Territories');
INSERT INTO `zones` VALUES(73, 38, 'NU', 'Nunavut');
INSERT INTO `zones` VALUES(74, 38, 'ON', 'Ontario');
INSERT INTO `zones` VALUES(75, 38, 'PE', 'Prince Edward Island');
INSERT INTO `zones` VALUES(76, 38, 'QC', 'Quebec');
INSERT INTO `zones` VALUES(77, 38, 'SK', 'Saskatchewan');
INSERT INTO `zones` VALUES(78, 38, 'YT', 'Yukon Territory');
INSERT INTO `zones` VALUES(79, 81, 'NDS', 'Niedersachsen');
INSERT INTO `zones` VALUES(80, 81, 'BAW', 'Baden-WÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ');
INSERT INTO `zones` VALUES(81, 81, 'BAY', 'Bayern');
INSERT INTO `zones` VALUES(82, 81, 'BER', 'Berlin');
INSERT INTO `zones` VALUES(83, 81, 'BRG', 'Brandenburg');
INSERT INTO `zones` VALUES(84, 81, 'BRE', 'Bremen');
INSERT INTO `zones` VALUES(85, 81, 'HAM', 'Hamburg');
INSERT INTO `zones` VALUES(86, 81, 'HES', 'Hessen');
INSERT INTO `zones` VALUES(87, 81, 'MEC', 'Mecklenburg-Vorpommern');
INSERT INTO `zones` VALUES(88, 81, 'NRW', 'Nordrhein-Westfalen');
INSERT INTO `zones` VALUES(89, 81, 'RHE', 'Rheinland-Pfalz');
INSERT INTO `zones` VALUES(90, 81, 'SAR', 'Saarland');
INSERT INTO `zones` VALUES(91, 81, 'SAS', 'Sachsen');
INSERT INTO `zones` VALUES(92, 81, 'SAC', 'Sachsen-Anhalt');
INSERT INTO `zones` VALUES(93, 81, 'SCN', 'Schleswig-Holstein');
INSERT INTO `zones` VALUES(94, 81, 'THE', 'ThÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚');
INSERT INTO `zones` VALUES(95, 14, 'WI', 'Wien');
INSERT INTO `zones` VALUES(96, 14, 'NO', 'NiederÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’');
INSERT INTO `zones` VALUES(97, 14, 'OO', 'OberÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢');
INSERT INTO `zones` VALUES(98, 14, 'SB', 'Salzburg');
INSERT INTO `zones` VALUES(99, 14, 'KN', 'KÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬');
INSERT INTO `zones` VALUES(100, 14, 'ST', 'Steiermark');
INSERT INTO `zones` VALUES(101, 14, 'TI', 'Tirol');
INSERT INTO `zones` VALUES(102, 14, 'BL', 'Burgenland');
INSERT INTO `zones` VALUES(103, 14, 'VB', 'Voralberg');
INSERT INTO `zones` VALUES(104, 204, 'AG', 'Aargau');
INSERT INTO `zones` VALUES(105, 204, 'AI', 'Appenzell Innerrhoden');
INSERT INTO `zones` VALUES(106, 204, 'AR', 'Appenzell Ausserrhoden');
INSERT INTO `zones` VALUES(107, 204, 'BE', 'Bern');
INSERT INTO `zones` VALUES(108, 204, 'BL', 'Basel-Landschaft');
INSERT INTO `zones` VALUES(109, 204, 'BS', 'Basel-Stadt');
INSERT INTO `zones` VALUES(110, 204, 'FR', 'Freiburg');
INSERT INTO `zones` VALUES(111, 204, 'GE', 'Genf');
INSERT INTO `zones` VALUES(112, 204, 'GL', 'Glarus');
INSERT INTO `zones` VALUES(113, 204, 'JU', 'GraubÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã');
INSERT INTO `zones` VALUES(114, 204, 'JU', 'Jura');
INSERT INTO `zones` VALUES(115, 204, 'LU', 'Luzern');
INSERT INTO `zones` VALUES(116, 204, 'NE', 'Neuenburg');
INSERT INTO `zones` VALUES(117, 204, 'NW', 'Nidwalden');
INSERT INTO `zones` VALUES(118, 204, 'OW', 'Obwalden');
INSERT INTO `zones` VALUES(119, 204, 'SG', 'St. Gallen');
INSERT INTO `zones` VALUES(120, 204, 'SH', 'Schaffhausen');
INSERT INTO `zones` VALUES(121, 204, 'SO', 'Solothurn');
INSERT INTO `zones` VALUES(122, 204, 'SZ', 'Schwyz');
INSERT INTO `zones` VALUES(123, 204, 'TG', 'Thurgau');
INSERT INTO `zones` VALUES(124, 204, 'TI', 'Tessin');
INSERT INTO `zones` VALUES(125, 204, 'UR', 'Uri');
INSERT INTO `zones` VALUES(126, 204, 'VD', 'Waadt');
INSERT INTO `zones` VALUES(127, 204, 'VS', 'Wallis');
INSERT INTO `zones` VALUES(128, 204, 'ZG', 'Zug');
INSERT INTO `zones` VALUES(129, 204, 'ZH', 'ZÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬');
INSERT INTO `zones` VALUES(130, 195, 'A CoruÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’', 'A CoruÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’');
INSERT INTO `zones` VALUES(131, 195, 'Alava', 'Alava');
INSERT INTO `zones` VALUES(132, 195, 'Albacete', 'Albacete');
INSERT INTO `zones` VALUES(133, 195, 'Alicante', 'Alicante');
INSERT INTO `zones` VALUES(134, 195, 'Almeria', 'Almeria');
INSERT INTO `zones` VALUES(135, 195, 'Asturias', 'Asturias');
INSERT INTO `zones` VALUES(136, 195, 'Avila', 'Avila');
INSERT INTO `zones` VALUES(137, 195, 'Badajoz', 'Badajoz');
INSERT INTO `zones` VALUES(138, 195, 'Baleares', 'Baleares');
INSERT INTO `zones` VALUES(139, 195, 'Barcelona', 'Barcelona');
INSERT INTO `zones` VALUES(140, 195, 'Burgos', 'Burgos');
INSERT INTO `zones` VALUES(141, 195, 'Caceres', 'Caceres');
INSERT INTO `zones` VALUES(142, 195, 'Cadiz', 'Cadiz');
INSERT INTO `zones` VALUES(143, 195, 'Cantabria', 'Cantabria');
INSERT INTO `zones` VALUES(144, 195, 'Castellon', 'Castellon');
INSERT INTO `zones` VALUES(145, 195, 'Ceuta', 'Ceuta');
INSERT INTO `zones` VALUES(146, 195, 'Ciudad Real', 'Ciudad Real');
INSERT INTO `zones` VALUES(147, 195, 'Cordoba', 'Cordoba');
INSERT INTO `zones` VALUES(148, 195, 'Cuenca', 'Cuenca');
INSERT INTO `zones` VALUES(149, 195, 'Girona', 'Girona');
INSERT INTO `zones` VALUES(150, 195, 'Granada', 'Granada');
INSERT INTO `zones` VALUES(151, 195, 'Guadalajara', 'Guadalajara');
INSERT INTO `zones` VALUES(152, 195, 'Guipuzcoa', 'Guipuzcoa');
INSERT INTO `zones` VALUES(153, 195, 'Huelva', 'Huelva');
INSERT INTO `zones` VALUES(154, 195, 'Huesca', 'Huesca');
INSERT INTO `zones` VALUES(155, 195, 'Jaen', 'Jaen');
INSERT INTO `zones` VALUES(156, 195, 'La Rioja', 'La Rioja');
INSERT INTO `zones` VALUES(157, 195, 'Las Palmas', 'Las Palmas');
INSERT INTO `zones` VALUES(158, 195, 'Leon', 'Leon');
INSERT INTO `zones` VALUES(159, 195, 'Lleida', 'Lleida');
INSERT INTO `zones` VALUES(160, 195, 'Lugo', 'Lugo');
INSERT INTO `zones` VALUES(161, 195, 'Madrid', 'Madrid');
INSERT INTO `zones` VALUES(162, 195, 'Malaga', 'Malaga');
INSERT INTO `zones` VALUES(163, 195, 'Melilla', 'Melilla');
INSERT INTO `zones` VALUES(164, 195, 'Murcia', 'Murcia');
INSERT INTO `zones` VALUES(165, 195, 'Navarra', 'Navarra');
INSERT INTO `zones` VALUES(166, 195, 'Ourense', 'Ourense');
INSERT INTO `zones` VALUES(167, 195, 'Palencia', 'Palencia');
INSERT INTO `zones` VALUES(168, 195, 'Pontevedra', 'Pontevedra');
INSERT INTO `zones` VALUES(169, 195, 'Salamanca', 'Salamanca');
INSERT INTO `zones` VALUES(170, 195, 'Santa Cruz de Tenerife', 'Santa Cruz de Tenerife');
INSERT INTO `zones` VALUES(171, 195, 'Segovia', 'Segovia');
INSERT INTO `zones` VALUES(172, 195, 'Sevilla', 'Sevilla');
INSERT INTO `zones` VALUES(173, 195, 'Soria', 'Soria');
INSERT INTO `zones` VALUES(174, 195, 'Tarragona', 'Tarragona');
INSERT INTO `zones` VALUES(175, 195, 'Teruel', 'Teruel');
INSERT INTO `zones` VALUES(176, 195, 'Toledo', 'Toledo');
INSERT INTO `zones` VALUES(177, 195, 'Valencia', 'Valencia');
INSERT INTO `zones` VALUES(178, 195, 'Valladolid', 'Valladolid');
INSERT INTO `zones` VALUES(179, 195, 'Vizcaya', 'Vizcaya');
INSERT INTO `zones` VALUES(180, 195, 'Zamora', 'Zamora');
INSERT INTO `zones` VALUES(181, 195, 'Zaragoza', 'Zaragoza');

CREATE TABLE IF NOT EXISTS `zones_to_geo_zones` (
  `association_id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_country_id` int(11) NOT NULL DEFAULT '0',
  `zone_id` int(11) DEFAULT NULL,
  `geo_zone_id` int(11) DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`association_id`),
  KEY `geo_zone_id` (`geo_zone_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1603 ;

INSERT INTO `zones_to_geo_zones` VALUES(1602, 223, 62, 24, NULL, '2012-09-20 22:36:29');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
