<?php
/*
$Id: qbi_engine_orders.php,v 2.10b 2005/05/08 al Exp $

Quickbooks Import QBI
contribution for CartStore
ver 2.10b May 8, 2005 (slight revision Oct 1, 2005)
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

if (isset($stage) AND $stage=='process' AND isset($engine) AND $engine=='orders') {

  if (!isset($qbimported)) $qbimported=0;

// Open log file
  if (QBI_LOG==1) $loghandle=log_open("orders");
  if (QBI_LOG==1) $log=log_head();

// Determine default language id
  $language_id_default=get_language_id(DEFAULT_LANGUAGE);
  if (QBI_LOG==1) $log.=log_lang($language_id_default);

// Add config info to log
  if (QBI_LOG==1) $log.=log_config();

// Update prior orders
  if ($qbimported==0) $result2=tep_db_query("UPDATE ".TABLE_ORDERS." SET qbi_imported=2 WHERE qbi_imported=1");

// Retreive all selected orders
  $engine="orders";
  $id_field="orders_id";
  if (get_magic_quotes_gpc()) $whereclause=stripslashes($whereclause);
  $sql="SELECT *, DATE_FORMAT(date_purchased,'%m/%d/%Y') AS purchdate FROM ".constant("TABLE_".strtoupper($engine)).$whereclause." ORDER BY ".$id_field;
  $resulto=tep_db_query($sql);
  if ($myrowo=tep_db_fetch_array($resulto)) {

// Open output file
    $handle=fopen("qbi_output/qbi_orders.iif", "wb");

// Loop through each order
    do {

// Assign variables
      $ordersid=$myrowo["orders_id"];
      $custid=$myrowo["customers_id"];
      $paymeth=$myrowo["payment_method"];
      $billing_country_id=find_country_id($myrowo["billing_country"]);
      $delivery_country_id=find_country_id($myrowo["delivery_country"]);

// Determine payment type and text
      $payment=pay_methtype($paymeth);
	  
// Determine terms
	  ($payment["type"]==1) ? $terms=INVOICE_TERMSCC : $terms=INVOICE_TERMS;

// Determine transaction type
      if (INVOICE_PMT==2 AND $payment["type"]==1) {
	    $transtype="CASH SALE";
		$acctname=INVOICE_SALESACCT;
      } else {
        $transtype="INVOICE";
        $acctname=INVOICE_ACCT;
      }

// Don't show local country in address
      if (CUST_COUNTRY==0) {
        if ($billing_country_id==STORE_COUNTRY) {$myrowo["billing_country"]='';}
        if ($delivery_country_id==STORE_COUNTRY) {$myrowo["delivery_country"]='';}
      }

// Replace state name with state code				
      if (CUST_STATE==1) {
        $myrowo["billing_state"]=get_state_code($myrowo["billing_state"],$billing_country_id);
        $myrowo["delivery_state"]=get_state_code($myrowo["delivery_state"],$delivery_country_id);
      }

// Remove invalid company names
      $invalid=array("none","n/a","na","None","N/A","NA");
      $myrowo["billing_company"]=str_replace($invalid, "", $myrowo["billing_company"]);
      $myrowo["delivery_company"]=str_replace($invalid, "", $myrowo["delivery_company"]);
	  $billing_company=$myrowo["billing_company"];

// Consolidate customer's addresses to 3 lines for old versions of QB
      if (QBI_QB_VER!="2003") {
        if (strlen($myrowo["billing_company"])>0) {
          $myrowo["billing_name"]=$myrowo["billing_name"].", ".$myrowo["billing_company"];
          $myrowo["billing_company"]='';
        }
        if (strlen($myrowo["billing_country"])>0) {
          $myrowo["billing_postcode"]=$myrowo["billing_postcode"]." ".$myrowo["billing_country"];
          $myrowo["billing_country"]='';				
        }
        if (strlen($myrowo["delivery_company"])>0) {
          $myrowo["delivery_name"]=$myrowo["delivery_name"].", ".$myrowo["delivery_company"];
          $myrowo["delivery_company"]='';
        }
        if (strlen($myrowo["delivery_country"])>0) {
          $myrowo["delivery_postcode"]=$myrowo["delivery_postcode"]." ".$myrowo["delivery_country"];
          $myrowo["delivery_country"]='';				
        }
      }

// Format customer's addresses (remove line-returns)
      $baddr=array($myrowo["billing_name"],$myrowo["billing_company"],$myrowo["billing_street_address"],$myrowo["billing_city"]." ".$myrowo["billing_state"]." ".$myrowo["billing_postcode"],$myrowo["billing_country"]);
      $daddr=array($myrowo["delivery_name"],$myrowo["delivery_company"],$myrowo["delivery_street_address"],$myrowo["delivery_city"]." ".$myrowo["delivery_state"]." ".$myrowo["delivery_postcode"],$myrowo["delivery_country"]);
	  $baddr=str_replace("\r\n"," ",$baddr);
      $daddr=str_replace("\r\n"," ",$daddr);
	  
// Format customer's addresses (remove commas, periods)
      $removeme=array(",",".");
	  $baddr=str_replace($removeme,"",$baddr);
      $daddr=str_replace($removeme,"",$daddr);

// Format customer's addresses (remove blank lines)  
      foreach($baddr as $badd) if (strlen($badd)>0) $baddr1[]=$badd;
      foreach($daddr as $dadd) if (strlen($dadd)>0) $daddr1[]=$dadd;
	  $contact=$baddr1[0];

// ShipRush adjustment
	  if (CUST_COMPCON==0) $billing_company=$contact="";

// Retreive other customer data
      $resultc=tep_db_query("SELECT * FROM ".TABLE_CUSTOMERS." WHERE customers_id='$custid'");
      $myrowc=tep_db_fetch_array($resultc);

// Clean phone numbers
  	  $phone=str_replace("\r\n"," ",$myrowc[customers_telephone]);
      $fax=str_replace("\r\n"," ",$myrowc[customers_fax]);

// Phone 2 option
      $phonefax=$phone2="";
	  (CUST_PHONE==1) ? $phone2=$fax : $phonefax=$fax;

// Retreive order total
      $resultot=tep_db_query("SELECT * FROM ".TABLE_ORDERS_TOTAL." WHERE orders_id='$ordersid' AND class='ot_total'");
      $myrowot=tep_db_fetch_array($resultot);
      $ordertotal=round_amt($myrowot["value"]);

// Create QB customer name
      $custname=cust_name($myrowo["customers_id"],$billing_company,$myrowc["customers_lastname"],$myrowc["customers_firstname"]);

// Format order number
      $ordersid_docnum=str_replace("%I",$ordersid,ORDERS_DOCNUM);
      $ordersid_ponum=str_replace("%I",$ordersid,ORDERS_PONUM);

// Retreive and substitute shipping method
      $shipvia=ship_substitute($ordersid);
	  if ($ship_via==SHIP_NO_METHOD AND QBI_LOG==1) $log.="Order $ordersid: Shipping method not matched\n";

// Retreive order product data
      $resultop=tep_db_query("SELECT *, op.products_quantity AS prod_quan FROM ".TABLE_ORDERS_PRODUCTS." AS op, ".TABLE_PRODUCTS." AS p WHERE orders_id='$ordersid' AND op.products_id=p.products_id ORDER BY orders_products_id");
      (INVOICE_TOPRINT==1) ? $toprint="Y" : $toprint="N";
      $class_id=0;
      $order_data="";

// Start product line item loop

// Get basic product order data
      while ($myrowop=tep_db_fetch_array($resultop)) {
        $ordprod_id=$myrowop["orders_products_id"];
        $prod_id=$myrowop["products_id"];
        $prod_model=$myrowop["products_model"];
        $prod_name_cust=$myrowop["products_name"];	
        $prod_quan=$myrowop["prod_quan"];
        $prod_price=round_amt($myrowop["final_price"]);
        $prod_totalprice=$prod_price*$prod_quan;
        ($myrowop["products_tax"]>0) ? $prod_taxable="Y" : $prod_taxable="N";
        ($myrowop["products_tax_class_id"]>0) ? $prod_tax_class_id=$myrowop["products_tax_class_id"] : $prod_tax_class_id=0;			

// Get product description in default language
        $resultxp=tep_db_query("SELECT * FROM ".TABLE_PRODUCTS_DESCRIPTION." WHERE products_id='$prod_id' AND language_id='$language_id_default'");
        $myrowxp=tep_db_fetch_array($resultxp);
        $prod_name=$myrowxp["products_name"];
        $resultopa=tep_db_query("SELECT * FROM ".TABLE_ORDERS_PRODUCTS_ATTRIBUTES." WHERE orders_products_id='$ordprod_id'");

// Check for product options and values
        unset($prod_opt_cust,$prod_opt_val_cust,$prod_opt,$prod_opt_val,$prod_opt_id,$prod_opt_val_id);

// Start product options and values loop
        while ($myrowopa=tep_db_fetch_array($resultopa)) {
          $prod_opt_cust[]=$myrowopa["products_options"];  // Size, etc.
          $prod_opt_val_cust[]=$myrowopa["products_options_values"]; // Small, Large, etc.
	  
// Find current array value
          $prodoptcust=mysql_escape_string(end($prod_opt_cust));  // Size, etc.
          $prodoptvalcust=mysql_escape_string(end($prod_opt_val_cust)); // Small, Large, etc.

// Get product options in default language
    	  $resultpo=tep_db_query("SELECT po2.products_options_name AS products_options_name_po2, po2.products_options_id AS products_options_id_po2 FROM ".TABLE_PRODUCTS_OPTIONS." As po1, ".TABLE_PRODUCTS_OPTIONS." As po2 WHERE po1.products_options_name='$prodoptcust' AND  po1.products_options_id=po2.products_options_id AND po2.language_id='$language_id_default'");
          if ($myrowpo=tep_db_fetch_array($resultpo)) {
            $prod_opt[]=$myrowpo["products_options_name_po2"]; // Get option name in default language
            $prod_opt_id[]=$myrowpo["products_options_id_po2"]; // Get option id (not used at this time)						
          }

// Get product option values in default language
          $resultpov=tep_db_query("SELECT pov2.products_options_values_id AS products_options_values_id_pov2, pov2.products_options_values_name AS products_options_values_name_pov2 FROM ".TABLE_PRODUCTS_OPTIONS_VALUES." As pov1, ".TABLE_PRODUCTS_OPTIONS_VALUES." As pov2 WHERE pov1.products_options_values_name='$prodoptvalcust' AND  pov1.products_options_values_id=pov2.products_options_values_id AND pov2.language_id='$language_id_default'");
          if ($myrowpov=tep_db_fetch_array($resultpov)) {
            $prod_opt_val[]=$myrowpov["products_options_values_name_pov2"]; // Get option value name in default language
            $prod_opt_val_id[]=$myrowpov["products_options_values_id_pov2"]; // Get option value id
          }
        }

// Determine language to use
        if (ITEM_OSC_LANG==1) {
          $prod_name=$prod_name_cust;
          $prod_opt=$prod_opt_cust;
          $prod_opt_val=$prod_opt_val_cust;
        }

// Construct product model and name
        if (is_array($prod_opt_val) AND is_array($prod_opt)) {
          foreach ($prod_opt_val as $prod_opt_vals) {
            $prod_model.=":".$prod_opt_vals;
          }
          $prod_opt_val_opt=arraycombine($prod_opt,$prod_opt_val);
          foreach ($prod_opt_val_opt as $prod_opts=>$prod_opt_vals) {
            $prod_name.=", ".$prod_opts.": ".$prod_opt_vals;
          }
        }

// Check for product name substitutions and groups
        $resultqbp=tep_db_query("SELECT * FROM ".TABLE_QBI_PRODUCTS_ITEMS." WHERE products_id='$prod_id' AND products_options_values_id='".$prod_opt_val_id[0]."'");
        if ($myrowqbp=tep_db_fetch_array($resultqbp)) {
          $qb_prod_refnum=$myrowqbp["qbi_groupsitems_refnum"];
          $resultqbi=tep_db_query("SELECT * FROM ".TABLE_QBI_ITEMS." WHERE qbi_items_refnum='$qb_prod_refnum'");
          $resultqbg=tep_db_query("SELECT * FROM ".TABLE_QBI_GROUPS." WHERE qbi_groups_refnum='$qb_prod_refnum'");

// Write non-group substitution
        if ($myrowqbi=tep_db_fetch_array($resultqbi)) {
	      $item_name_use_osc=0; // feature to be added
	      if ($item_name_use_osc==0) $prod_name=$myrowqbi["qbi_items_desc"];
          $order_data.="SPL\t\t$transtype\t".$myrowo["purchdate"]."\t".$myrowqbi["qbi_items_accnt"]."\t\t".ITEM_CLASS."\t".-$prod_totalprice."\t\t".$prod_name."\tN\t".-$prod_quan."\t$prod_price\t".$myrowqbi["qbi_items_name"]."\t$prod_taxable\t\t\n";

// Write group substitution first line
        } elseif ($myrowqbg=tep_db_fetch_array($resultqbg)) {
          $qb_groups_refnum=$myrowqbg["qbi_groups_refnum"];
          $qb_groups_toprint=$myrowqbg["qbi_groups_toprint"];
          $order_data.="SPL\t\t$transtype\t".$myrowo["purchdate"]."\t\t\t\t\t\t\tN\t".-$prod_quan."\t\t".$myrowqbg["qbi_groups_name"]."\t$prod_taxable\t\t\n";
          $resultqbgi=tep_db_query("SELECT * FROM ".TABLE_QBI_GROUPS_ITEMS." AS qgi, ".TABLE_QBI_ITEMS." AS qi WHERE qgi.qbi_groups_refnum='$qb_groups_refnum' AND qgi.qbi_items_refnum=qi.qbi_items_refnum ORDER BY qgi.qbi_items_refnum");

// Write group substitution item lines
          $groupprice=0;
          while ($myrowqbgi=tep_db_fetch_array($resultqbgi)) {
            $groupitemquan=$prod_quan*$myrowqbgi["qbi_groups_items_quan"];
            $giquanprice=$groupitemquan*$myrowqbgi["qbi_items_price"];
            $order_data.="SPL\t\t$transtype\t".$myrowo["purchdate"]."\t".$myrowqbgi["qbi_items_accnt"]."\t\t\t".-$giquanprice."\t\t".$myrowqbgi["qbi_items_desc"]."\tN\t".-$groupitemquan."\t".$myrowqbgi["qbi_items_price"]."\t".$myrowqbgi["qbi_items_name"]."\t$prod_taxable\t\t\n";
            $groupprice+=$giquanprice;
          }

// Write group substitution last line
          $order_data.="SPL\t\t$transtype\t".$myrowo["purchdate"]."\t\t\t\t".-$groupprice."\t\t".$myrowqbg["qbi_groups_desc"]."\tN\t\t\t\t$prod_taxable\t\tENDGRP\n";
        }
      } else {

// Write normal non-group non-substitution item
        if (ITEM_DEFAULT==1) {
          $order_data.="SPL\t\t$transtype\t".$myrowo["purchdate"]."\t".ITEM_ACCT."\t\t".ITEM_CLASS."\t".-$prod_totalprice."\t\t$prod_name\tN\t".-$prod_quan."\t$prod_price\t".ITEM_DEFAULT_NAME."\t$prod_taxable\t\t\n";
        } else {
          $order_data.="SPL\t\t$transtype\t".$myrowo["purchdate"]."\t".ITEM_ACCT."\t\t".ITEM_CLASS."\t".-$prod_totalprice."\t\t$prod_name\tN\t".-$prod_quan."\t$prod_price\t$prod_model\t$prod_taxable\t\t\n";		
        }
	  }

// End product line item loop
    }

// Retreive order fees and discounts (ot modules)
      $resultotv=tep_db_query("SELECT * FROM ".TABLE_ORDERS_TOTAL." WHERE orders_id='$ordersid' AND class!='ot_shipping' AND class!='ot_tax' AND class!='ot_subtotal' AND class!='ot_total' ORDER BY class");
	  while ($myrowotv=tep_db_fetch_array($resultotv)) {
	    $resultotw=tep_db_query("SELECT * FROM ".TABLE_QBI_OT_DISC." AS otdisc, ".TABLE_QBI_DISC." AS disc WHERE otdisc.qbi_ot_mod='".$myrowotv["class"]."' AND otdisc.qbi_disc_refnum=disc.qbi_disc_refnum");
        if ($myrowotw=tep_db_fetch_array($resultotw)) { 
		  $discvalue=round_amt($myrowotv['value']);
          if ($myrowotw['qbi_disc_type']=='DISC') $discvalue= -$discvalue;
		  $disctax=$myrowotw['qbi_disc_tax'];
          ($disctax==1) ? $disctax="Y" : $disctax="N";
		  $order_data.="SPL\t\t$transtype\t".$myrowo["purchdate"]."\t\t\t\t\t\t\tN\t\t\t\t\t\t\n";
          $order_data.="SPL\t\t$transtype\t".$myrowo["purchdate"]."\t".$myrowotw['qbi_disc_accnt']."\t\t".ITEM_CLASS."\t".-$discvalue."\t\t".$myrowotw['qbi_disc_desc']."\tN\t-1\t".$discvalue."\t".$myrowotw['qbi_disc_name']."\t$disctax\t\t\n";
        } else {
		  if (QBI_LOG==1) $log.="Order $ordersid: Fee/discount line item not matched\n";
		}
      }

// Retreive order shipping data
      $resultots=tep_db_query("SELECT * FROM ".TABLE_ORDERS_TOTAL." WHERE orders_id='$ordersid' AND class='ot_shipping'");
      if ($myrowots=tep_db_fetch_array($resultots)) {
        (SHIP_TAX==1) ? $ship_taxable="Y" : $ship_taxable="N";
		$order_data.="SPL\t\t$transtype\t".$myrowo["purchdate"]."\t\t\t\t\t\t\tN\t\t\t\t\t\t\n";
        $order_data.="SPL\t\t$transtype\t".$myrowo["purchdate"]."\t".SHIP_ACCT."\t\t".SHIP_CLASS."\t".-$myrowots["value"]."\t\t".SHIP_DESC."\tN\t-1\t".$myrowots["value"]."\t".SHIP_NAME."\t$ship_taxable\t\t\n";
      } 

// Retreive customer comments
      if (INVOICE_COMMENTS==1) {
        $resultosh=tep_db_query("SELECT * FROM ".TABLE_ORDERS_STATUS_HISTORY." WHERE orders_id='$ordersid' ORDER BY date_added LIMIT 1");
          if ($myrowosh=tep_db_fetch_array($resultosh)) {
            if (strlen($myrowosh['comments'])>0) {
              $comments=str_replace("\r\n"," ",$myrowosh["comments"]);
              $order_data.="SPL\t\t$transtype\t".$myrowo["purchdate"]."\t\t\t\t\t\t\tN\t\t\t\t\t\t\n";
              $order_data.="SPL\t\t$transtype\t".$myrowo["purchdate"]."\t\t\t\t\t\t$comments\tN\t\t\t\t\t\t\n";
            }
          } 
        }
			
// Retreive order tax data and determine customer tax status
        if (TAX_ON==1) {
          $resultott=tep_db_query("SELECT * FROM ".TABLE_ORDERS_TOTAL." WHERE orders_id=$ordersid AND class='ot_tax'");
          if ($myrowott=tep_db_fetch_array($resultott)) {
            $tax_amt=round_amt($myrowott["value"]);
            $tax_status="Y";
          } else {
            $tax_amt=0;
            $tax_status="N";
          }
          $order_data.="SPL\t\t$transtype\t".$myrowo["purchdate"]."\tSales Tax Payable\t".TAX_AGENCY."\t\t".-$tax_amt."\t\t\tN\t\t".TAX_RATE."%\t".TAX_NAME."\tN\t\tAUTOSTAX\n";
        }
			
// End order data
        $order_data.="ENDTRNS\n";

// Create order header		
      $order_header="!TRNS\tTRNSID\tTRNSTYPE\tDATE\tACCNT\tNAME\tCLASS\tAMOUNT\tDOCNUM\tMEMO\tCLEAR\tTOPRINT\tADDR1\tADDR2\tADDR3\tADDR4\tADDR5\tSADDR1\tSADDR2\tSADDR3\tSADDR4\tSADDR5\tNAMEISTAXABLE\tTERMS\tSHIPVIA\tREP\tFOB\tINVMEMO\tPAYMETH\tPONUM\n";
      $order_header.="!SPL\tSPLID\tTRNSTYPE\tDATE\tACCNT\tNAME\tCLASS\tAMOUNT\tDOCNUM\tMEMO\tCLEAR\tQNTY\tPRICE\tINVITEM\tTAXABLE\tPAYMETHOD\tEXTRA\n";
      $order_header.="!ENDTRNS\n";
      $order_header.="TRNS\t\t$transtype\t".$myrowo["purchdate"]."\t$acctname\t$custname\t".ITEM_CLASS."\t$ordertotal\t$ordersid_docnum\t".INVOICE_MEMO."\tN\t$toprint\t$baddr1[0]\t$baddr1[1]\t$baddr1[2]\t$baddr1[3]\t$baddr1[4]\t$daddr1[0]\t$daddr1[1]\t$daddr1[2]\t$daddr1[3]\t$daddr1[4]\t$tax_status\t$terms\t$shipvia\t".INVOICE_REP."\t".INVOICE_FOB."\t".INVOICE_MESSAGE."\t".$payment["text"]."\t$ordersid_ponum\n";

// Format customer limit
      (CUST_LIMIT=='0') ? $customer_limit="" : $customer_limit=CUST_LIMIT;

// Create customer data
      if (QBI_QB_VER=="2003") {
        $cust_data="!CUST\tNAME\tBADDR1\tBADDR2\tBADDR3\tBADDR4\tBADDR5\tSADDR1\tSADDR2\tSADDR3\tSADDR4\tSADDR5\tPHONE1\tPHONE2\tFAXNUM\tEMAIL\tNOTE\tCONT1\tCONT2\tCTYPE\tTERMS\tTAXABLE\tTAXITEM\tLIMIT\tRESALENUM\tREP\tCOMPANYNAME\tSALUTATION\tFIRSTNAME\tLASTNAME\n";
        $cust_data.="CUST\t$custname\t$baddr1[0]\t$baddr1[1]\t$baddr1[2]\t$baddr1[3]\t$baddr1[4]\t$daddr1[0]\t$daddr1[1]\t$daddr1[2]\t$daddr1[3]\t$daddr1[4]\t$phone\t$phone2\t$phonefax\t".$myrowo["customers_email_address"]."\t\t$contact\t\t".CUST_TYPE."\t$terms\t$tax_status\t".TAX_NAME."\t$customer_limit\t\t".INVOICE_REP."\t".$billing_company."\t\t".$myrowc["customers_firstname"]."\t".$myrowc["customers_lastname"]."\n";
      } else {
        $cust_data="!CUST\tNAME\tBADDR1\tBADDR2\tBADDR3\tSADDR1\tSADDR2\tSADDR3\tPHONE1\tPHONE2\tFAXNUM\tEMAIL\tNOTE\tCONT1\tCONT2\tCTYPE\tTERMS\tTAXABLE\tTAXITEM\tLIMIT\tRESALENUM\tREP\tCOMPANYNAME\tSALUTATION\tFIRSTNAME\tLASTNAME\n";
        $cust_data.="CUST\t$custname\t$baddr1[0]\t$baddr1[1]\t$baddr1[2]\t$daddr1[0]\t$daddr1[1]\t$daddr1[2]\t$phone\t$phone2\t$phonefax\t".$myrowo["customers_email_address"]."\t\t$contact\t\t".CUST_TYPE."\t$terms\t$tax_status\t".TAX_NAME."\t$customer_limit\t\t".INVOICE_REP."\t".$billing_company."\t\t".$myrowc["customers_firstname"]."\t".$myrowc["customers_lastname"]."\n";
      }

// Write data
      fwrite($handle,$cust_data);
      fwrite($handle,$order_header);
      fwrite($handle,$order_data);
      unset($baddr1,$daddr1);

// Retreive and write payment data
      if (INVOICE_PMT==1 AND $payment["type"]==1) {	
        $payment_data="!TRNS\tTRNSID\tTRNSTYPE\tDATE\tACCNT\tNAME\tAMOUNT\tDOCNUM\tPAYMETH\tMEMO\n";
        $payment_data.="!SPL\tSPLID\tTRNSTYPE\tDATE\tACCNT\tNAME\tAMOUNT\tDOCNUM\tPAYMETH\tMEMO\n";
        $payment_data.="!ENDTRNS\n";
        $payment_data.="TRNS\t\tPAYMENT\t".$myrowo["purchdate"]."\t".INVOICE_SALESACCT."\t$custname\t$ordertotal\t\t".$payment["text"]."\t".PMTS_MEMO."\n";
        $payment_data.="SPL\t\tPAYMENT\t".$myrowo["purchdate"]."\t".INVOICE_ACCT."\t$custname\t".-$ordertotal."\t\t".$payment["text"]."\t".PMTS_MEMO."\n";
        $payment_data.="ENDTRNS\n";
        fwrite($handle,$payment_data);
      }
		
// Update order status and send email
      if ($qbimported==0 AND QBI_STATUS_UPDATE==1) status_email($ordersid,$payment["type"]);
	  
// Update qbi_imported status of order
      if ($qbimported==0) $result1=tep_db_query("UPDATE ".TABLE_ORDERS." SET qbi_imported=1 WHERE orders_id=$ordersid");

// Delete credit card number
      if (QBI_CC_CLEAR==1) cc_clear($ordersid);
	  
// Write log file
      if (QBI_LOG==1) fwrite($loghandle,$log);
      $log="";

// End main loop
    } while ($myrowo=tep_db_fetch_array($resulto));

// Close file
    fclose($handle);

// Close log file
    if (QBI_LOG==1) fclose($loghandle);

// Download file
    if (QBI_DL_IIF==1) {
      $filename="qbi_output/qbi_orders.iif";
      $size=filesize($filename);
      header("Pragma: public");
      header("Cache-Control: private");
      header("Content-type: application/octet-stream");
      header("Content-Disposition: attachment; filename=qbi_orders.iif");
      header("Content-length: $size");
      readfile("$filename");
      exit();
    }
  }
} else {
  // Do nothing
}
?>