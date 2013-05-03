<?php
/*
$Id: qbi_definitions.php,v 2.10 2005/05/08 al Exp $

Quickbooks Import QBI
contribution for CartStore
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

// QB Import Options
define ("QBI_QB_VER",$myrowqbc["qbi_qb_ver"]);
define ("QBI_DL_IIF",$myrowqbc["qbi_dl_iif"]);
define ("QBI_PROD_ROWS",$myrowqbc["qbi_prod_rows"]);
define ("PRODS_SORT",$myrowqbc["prods_sort"]);
define ("PRODS_WIDTH",$myrowqbc["prods_width"]);
define ("QBI_LOG",$myrowqbc["qbi_log"]);
// Orders
define ("ORDERS_STATUS_IMPORT",$myrowqbc["orders_status_import"]);
define ("QBI_STATUS_UPDATE",$myrowqbc["qbi_status_update"]);
define ("QBI_CC_STATUS_SELECT",$myrowqbc["qbi_cc_status_select"]);
define ("QBI_MO_STATUS_SELECT",$myrowqbc["qbi_mo_status_select"]);
define ("QBI_EMAIL_SEND",$myrowqbc["qbi_email_send"]);
define ("QBI_CC_CLEAR",$myrowqbc["qbi_cc_clear"]);
// Customers
define ("CUST_NAMEB",$myrowqbc["cust_nameb"]);
define ("CUST_NAMER",$myrowqbc["cust_namer"]);
define ("CUST_LIMIT",$myrowqbc["cust_limit"]);
define ("CUST_TYPE",$myrowqbc["cust_type"]);
define ("CUST_STATE",$myrowqbc["cust_state"]);
define ("CUST_COUNTRY",$myrowqbc["cust_country"]);
define ("CUST_COMPCON",$myrowqbc["cust_compcon"]);
define ("CUST_PHONE",$myrowqbc["cust_phone"]);
// Invoices
define ("INVOICE_ACCT",$myrowqbc["invoice_acct"]);
define ("INVOICE_SALESACCT",$myrowqbc["invoice_salesacct"]);
define ("ORDERS_DOCNUM",$myrowqbc["orders_docnum"]);
define ("ORDERS_PONUM",$myrowqbc["orders_ponum"]);
define ("INVOICE_TOPRINT",$myrowqbc["invoice_toprint"]);
define ("INVOICE_TERMSCC",$myrowqbc["invoice_termscc"]);
define ("INVOICE_TERMS",$myrowqbc["invoice_terms"]);
define ("INVOICE_REP",$myrowqbc["invoice_rep"]);
define ("INVOICE_FOB",$myrowqbc["invoice_fob"]);
define ("INVOICE_COMMENTS",$myrowqbc["invoice_comments"]);
define ("INVOICE_MESSAGE",$myrowqbc["invoice_message"]);
define ("INVOICE_MEMO",$myrowqbc["invoice_memo"]);
// Items
define ("ITEM_ACCT",$myrowqbc["item_acct"]);
define ("ITEM_ASSET_ACCT",$myrowqbc["item_asset_acct"]);
define ("ITEM_CLASS",$myrowqbc["item_class"]);
define ("ITEM_COG_ACCT",$myrowqbc["item_cog_acct"]);
define ("ITEM_OSC_LANG",$myrowqbc["item_osc_lang"]);
define ("ITEM_MATCH_INV",$myrowqbc["item_match_inv"]);
define ("ITEM_MATCH_NONINV",$myrowqbc["item_match_noninv"]);
define ("ITEM_MATCH_SERV",$myrowqbc["item_match_serv"]);
define ("ITEM_DEFAULT",$myrowqbc["item_default"]);
define ("ITEM_DEFAULT_NAME",$myrowqbc["item_default_name"]);
define ("ITEM_DEFAULT_DESC",$myrowqbc["item_default_desc"]);
define ("ITEM_IMPORT_TYPE",$myrowqbc["item_import_type"]);
define ("ITEM_ACTIVE",$myrowqbc["item_active"]);
// Shipping
define ("SHIP_NAME",$myrowqbc["ship_name"]);
define ("SHIP_DESC",$myrowqbc["ship_desc"]);
define ("SHIP_ACCT",$myrowqbc["ship_acct"]);
define ("SHIP_CLASS",$myrowqbc["ship_class"]);
define ("SHIP_TAX",$myrowqbc["ship_tax"]);
// Taxes
define ("TAX_ON",$myrowqbc["tax_on"]);
define ("TAX_NAME",$myrowqbc["tax_name"]);
define ("TAX_AGENCY",$myrowqbc["tax_agency"]);
define ("TAX_RATE",$myrowqbc["tax_rate"]);
define ("TAX_LOOKUP",$myrowqbc["tax_lookup"]);
// Payments
define ("INVOICE_PMT",$myrowqbc["invoice_pmt"]);
define ("PMTS_MEMO",$myrowqbc["pmts_memo"]);

// Note: Absolutely no spaces allowed after the following php closing tag to avoid header error.
?>