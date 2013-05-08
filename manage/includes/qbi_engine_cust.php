<?php
/*
$Id: qbi_engine_cust.php,v 2.10 2005/05/08 al Exp $

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

if (isset($stage) AND $stage=='process' AND isset($engine) AND $engine=='customers') {

// Retreive all selected orders
	$engine="orders";
	$id_field="orders_id";
	$whereclause=" WHERE qbi_imported='2'";

	if (get_magic_quotes_gpc()) $whereclause=stripslashes($whereclause);
	$sql="SELECT *, DATE_FORMAT(date_purchased,'%m/%d/%Y') AS purchdate FROM ".constant("TABLE_".strtoupper($engine)).$whereclause." ORDER BY ".$id_field;
	$resulto=tep_db_query($sql);
	if ($myrowo=tep_db_fetch_array($resulto)) {

// Open output file
		$handle=fopen("qbi_output/qbi_cust.iif", "wb");
		
// Create header
		if (QBI_QB_VER=="2003") {
			$header_data="!CUST\tNAME\tBADDR1\tBADDR2\tBADDR3\tBADDR4\tBADDR5\tSADDR1\tSADDR2\tSADDR3\tSADDR4\tSADDR5\tPHONE1\tPHONE2\tFAXNUM\tEMAIL\tNOTE\tCONT1\tCONT2\tCTYPE\tTERMS\tTAXABLE\tTAXITEM\tLIMIT\tRESALENUM\tREP\tCOMPANYNAME\tSALUTATION\tFIRSTNAME\tLASTNAME\n";
		} else {
			$header_data="!CUST\tNAME\tBADDR1\tBADDR2\tBADDR3\tSADDR1\tSADDR2\tSADDR3\tPHONE1\tPHONE2\tFAXNUM\tEMAIL\tNOTE\tCONT1\tCONT2\tCTYPE\tTERMS\tTAXABLE\tTAXITEM\tLIMIT\tRESALENUM\tREP\tCOMPANYNAME\tSALUTATION\tFIRSTNAME\tLASTNAME\n";
		}

// Write header
      fwrite($handle,$header_data);

// Loop through each order
		do {

// Assign variables
			$ordersid=$myrowo["orders_id"];
			$custid=$myrowo["customers_id"];
			$paymeth=$myrowo["payment_method"];
			$billing_country_id=find_country_id($myrowo["billing_country"]);
			$delivery_country_id=find_country_id($myrowo["delivery_country"]);

// Don't show local country in address
			if (CUST_COUNTRY==0) {
				if ($billing_country_id==STORE_COUNTRY) $myrowo["billing_country"]='';
				if ($delivery_country_id==STORE_COUNTRY) $myrowo["delivery_country"]='';
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

// Phone 2 option
      $phonefax=$phone2="";
      (CUST_PHONE==1) ? $phone2=$myrowc[customers_fax] : $phonefax=$myrowc[customers_phone];

// Create QB customer name
       $custname=cust_name($myrowo["customers_id"],$myrowo["billing_company"],$myrowc["customers_lastname"],$myrowc["customers_firstname"]);
			
// Retreive order tax data and determine customer tax status
       if (TAX_ON==1) {
         $resultott=tep_db_query("SELECT * FROM ".TABLE_ORDERS_TOTAL." WHERE orders_id=$ordersid AND class='ot_tax'");
         if ($myrowott=tep_db_fetch_array($resultott)) {
		   $tax_amt=round_amt(-$myrowott["value"]);
           $tax_status="Y";
         } else {
           $tax_amt=0;
           $tax_status="N";
         }
       }

// Format customer limit
      (CUST_LIMIT=='0') ? $customer_limit="" : $customer_limit=CUST_LIMIT;

// Create customer data
      if (QBI_QB_VER=="2003") {
        $cust_data="CUST\t$custname\t$baddr1[0]\t$baddr1[1]\t$baddr1[2]\t$baddr1[3]\t$baddr1[4]\t$daddr1[0]\t$daddr1[1]\t$daddr1[2]\t$daddr1[3]\t$daddr1[4]\t".$myrowo["customers_telephone"]."\t$phone2\t$phonefax\t".$myrowo["customers_email_address"]."\t\t$contact\t\t".CUST_TYPE."\t$terms\t$tax_status\t".TAX_NAME."\t$customer_limit\t\t".INVOICE_REP."\t".$billing_company."\t\t".$myrowc["customers_firstname"]."\t".$myrowc["customers_lastname"]."\n";
      } else {
        $cust_data="CUST\t$custname\t$baddr1[0]\t$baddr1[1]\t$baddr1[2]\t$daddr1[0]\t$daddr1[1]\t$daddr1[2]\t".$myrowo["customers_telephone"]."\t$phone2\t$phonefax\t".$myrowo["customers_email_address"]."\t\t$contact\t\t".CUST_TYPE."\t$terms\t$tax_status\t".TAX_NAME."\t$customer_limit\t\t".INVOICE_REP."\t".$billing_company."\t\t".$myrowc["customers_firstname"]."\t".$myrowc["customers_lastname"]."\n";
      }

// Write data
      fwrite($handle,$cust_data);
      unset($baddr1,$daddr1);

// End main loop
		} while ($myrowo=tep_db_fetch_array($resulto));

// Close file
		fclose($handle);
	
// Download file
		if (QBI_DL_IIF==1) {
			$filename="qbi_output/qbi_cust.iif";
			$size=filesize($filename);
			header("Pragma: public");
			header("Cache-Control: private");
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=qbi_cust.iif");
			header("Content-length: $size");
			readfile("$filename");
			exit();
		}
	} else {
	// Do nothing
	}
}
?>