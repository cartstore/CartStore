<?php
/*
$Id: qbi_config.php,v 2.10 2005/05/08 al Exp $
Language file: English

Quickbooks Import QBI
contribution for osCommerce
ver 2.10 May 8, 2005
(c) 2005 Adam Liberman
www.libermansound.com
info@libermansound.com
Please use the osC forum for support.
GNU General Public License Compatible

    This file is part of Quickbooks Import QBI.

    Quickbooks Import QBI is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    Quickbooks Import QBI is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Quickbooks Import QBI; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Messages
define('CONFIG_SUCCESS', 'Configuration successful');
define('CONFIG_QBI_VER', 'QBI version');
define('CONFIG_SET_OPT', 'Database has just been updated. Please check and update the configuration.');
define('CONFIG_SET_OPT2', 'To continue, you must click "Submit" even if you made no changes.');
// Button
define('CONFIG_SUBMIT', 'Update');
// Form
// Sections
define('CONFIG_SEC_QBI', 'QBI');
define('CONFIG_SEC_ORDERS', 'Orders');
define('CONFIG_SEC_CUST', 'Customers');
define('CONFIG_SEC_INV', 'Invoices');
define('CONFIG_SEC_ITEM', 'Items');
define('CONFIG_SEC_SHIP', 'Shipping');
define('CONFIG_SEC_TAX', 'Taxes');
define('CONFIG_SEC_PMTS', 'Payments');
define('CONFIG_SEC_PRODS', 'QB Products');
// QB Import Options
define('QBI_QB_VER_L', 'Quickbooks version');
  define('QBI_QB_VER_1999', '1999-2000');
  define('QBI_QB_VER_2001', '2001-2002');
  define('QBI_QB_VER_2003', '2003 and up');
define('QBI_DL_IIF_L', 'Download iif file');
define('QBI_PROD_ROWS_L', 'Product rows dispayed');
define('PRODS_SORT_L', 'Products dropdown sort order');
  define('PRODS_SORT_NAME', 'Name');
  define('PRODS_SORT_DESC', 'Description');
define('PRODS_WIDTH_L', 'Products dropdown width');
define('QBI_LOG_L', 'Create log file');
// Orders
define ('ORDERS_STATUS_IMPORT_L', 'Import orders with status');
  define ('CONFIG_STATUS_ANY', 'any');  
define ('QBI_STATUS_UPDATE_L', 'Update status');
define ('QBI_CC_STATUS_SELECT_L', 'Change CC order status to');
define ('QBI_MO_STATUS_SELECT_L', 'Change check/mo status to');
define ('QBI_EMAIL_SEND_L', 'Send status email');
define ('QBI_CC_CLEAR_L', 'Delete credit card number');
// Customers
define('CUST_NAMEB_L', 'Customer number (business)');
define('CUST_NAMER_L', 'Customer number (residence)');
define('CUST_LIMIT_L', 'Customer limit');
define('CUST_TYPE_L', 'Customer type');
define('CUST_STATE_L', 'Use state codes');
define('CUST_COUNTRY_L', 'Include local country');
define('CUST_COMPCON_L', 'Include company and contact');
define('CUST_PHONE_L', 'Import fax as Alt Phone');
// Invoices
define('INVOICE_ACCT_L', 'Invoice account');
define('INVOICE_SALESACCT_L', 'Sales Receipt account');
define('ORDERS_DOCNUM_L', 'Invoice number');
define('ORDERS_PONUM_L', 'Invoice/Sales Receipt \'PO Number\'');
define('INVOICE_TOPRINT_L', 'Invoice to print');
define('INVOICE_TERMSCC_L', 'Invoice terms paid online');
define('INVOICE_TERMS_L', 'Invoice terms not prepaid');
define('INVOICE_REP_L', 'Invoice rep');
define('INVOICE_FOB_L', 'Invoice fob');
define('INVOICE_COMMENTS_L', 'Include customer comments');
define('INVOICE_MESSAGE_L', 'Customer message');
define('INVOICE_MEMO_L', 'Invoice memo');
// Items
define('ITEM_ACCT_L', 'Item income account');
define('ITEM_ASSET_ACCT_L', 'Item asset account');
define('ITEM_CLASS_L', 'Item class');
define('ITEM_COG_ACCT_L', 'COGS account');
define('ITEM_OSC_LANG_L', 'Description language');
  define('ITEM_LANG_DEF', 'Default');
  define('ITEM_LANG_CUST', 'Customer\'s');
define('ITEM_MATCH_L', 'Match types');
  define('ITEM_MATCH_INV_L', 'Inventory');
  define('ITEM_MATCH_NONINV_L', 'Noninventory');
  define('ITEM_MATCH_SERV_L', 'Services');
define('ITEM_DEFAULT_L', 'Use default item');
  define('ITEM_DEFAULT_NAME_L', 'Default name');
define('ITEM_IMPORT_TYPE_L', 'Export type');
  define('ITEM_IMPORT_INV', 'Inventory');
  define('ITEM_IMPORT_NONINV', 'Noninventory');
  define('ITEM_IMPORT_SERV', 'Services');
define('ITEM_ACTIVE_L', 'Only export active items');
// Shipping
define('SHIP_NAME_L', 'Shipping name');
define('SHIP_DESC_L', 'Shipping description');
define('SHIP_ACCT_L', 'Shipping account');
define('SHIP_CLASS_L', 'Shipping class');
define('SHIP_TAX_L', 'Shipping taxable');
// Taxes
define('TAX_ON_L', 'Tax turned on');
define('TAX_NAME_L', 'Tax name');
define('TAX_AGENCY_L', 'Tax agency');
define('TAX_RATE_L', 'Tax rate');
define('TAX_LOOKUP_L', 'Use tax name table');
// Payments
define('INVOICE_PMT_L', 'Import payments');
  define('INVOICE_PMT_NONE', 'No');
  define('INVOICE_PMT_PMT', 'As Invoice and Payment');
  define('INVOICE_PMT_SR', 'As Sales Receipt');
define('PMTS_MEMO_L', 'Payment memo');

// Field comments
// QB Import Options
define('QBI_IMPORT_PMTS_C', '');
define('QBI_QB_VER_C', '');
define('QBI_DL_IIF_C', '');
define('QBI_PROD_ROWS_C', '');
define('PRODS_SORT_C', '');
define('PRODS_WIDTH_C', '');
define('QBI_LOG_C', '');
// Orders
define ('ORDERS_STATUS_IMPORT_C', '');
define('QBI_STATUS_UPDATE_C', '');
define('QBI_CC_STATUS_SELECT_C', '');
define('QBI_MO_STATUS_SELECT_C', '');
define('QBI_EMAIL_SEND_C', '');
define('QBI_CC_CLEAR_C', '');
// Customers
define('CUST_NAMEB_C', 'See instruction manual.');
define('CUST_NAMER_C', 'See instruction manual.');
define('CUST_LIMIT_C', '0 means no limit.');
define('CUST_TYPE_C', '');
define('CUST_STATE_C', '');
define('CUST_COUNTRY_C', '');
define('CUST_COMPCON_C', '');
define('CUST_PHONE_C', '');
// Invoices
define('INVOICE_ACCT_C', '');
define('INVOICE_SALESACCT_C', '');
define('ORDERS_DOCNUM_C', '%I=osC order number');
define('ORDERS_PONUM_C', '%I=osC order number');
define('INVOICE_TOPRINT_C', '');
define('INVOICE_TERMSCC_C', '');
define('INVOICE_TERMS_C', '');
define('INVOICE_REP_C', '');
define('INVOICE_FOB_C', '');
define('INVOICE_COMMENTS_C', '');
define('INVOICE_MESSAGE_C', '');
define('INVOICE_MEMO_C', '');
// Items
define('ITEM_ACCT_C', '');
define('ITEM_ASSET_ACCT_C', '');
define('ITEM_CLASS_C', '');
define('ITEM_COG_ACCT_C', '');
define('ITEM_OSC_LANG_C', '');
define('ITEM_MATCH_C', '');
  define('ITEM_MATCH_INV_C', '');
  define('ITEM_MATCH_NONINV_C', '');
  define('ITEM_MATCH_SERV_C', '');
define('ITEM_DEFAULT_C', '');
  define('ITEM_DEFAULT_NAME_C', '');
define('ITEM_IMPORT_TYPE_C', '');
define('ITEM_ACTIVE_C', '');
// Shipping
define('SHIP_NAME_C', '');
define('SHIP_DESC_C', '');
define('SHIP_ACCT_C', '');
define('SHIP_CLASS_C', '');
define('SHIP_TAX_C', '');
// Taxes
define('TAX_ON_C', '');
define('TAX_NAME_C', '');
define('TAX_AGENCY_C', '');
define('TAX_RATE_C', '%');
define('TAX_LOOKUP_C', 'Not implemented yet');
// Payments
define('INVOICE_PMT_C', '');
define('PMTS_MEMO_C', '');
?>