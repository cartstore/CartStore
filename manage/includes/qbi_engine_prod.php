<?php
/*
$Id: qbi_engine_prod.php,v 2.10 2005/05/08 al Exp $

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

if (isset($stage) AND $stage=='process' AND isset($engine) AND $engine=='products') {

// Update prior products
  if ($qbimported==0) $result2=tep_db_query("UPDATE ".TABLE_PRODUCTS." SET qbi_imported=2 WHERE qbi_imported=1");

// Read product data
  (ITEM_ACTIVE==1) ? $whereclause="AND products_status='1' " : $whereclause=""; 
  $resultpd = tep_db_query("SELECT * FROM ".TABLE_PRODUCTS." AS p, ".TABLE_PRODUCTS_DESCRIPTION." AS pd WHERE p.products_id=pd.products_id AND language_id='$languages_id' AND qbi_imported='$qbimported' ".$whereclause."ORDER BY products_model");

// Check for products
  if ($myrowpd=tep_db_fetch_array($resultpd)) {

// Open output file
    $handle=fopen("qbi_output/qbi_prod.iif", "wb");

// Create iif header
    $prod_data="!INVITEM\tNAME\tINVITEMTYPE\tDESC\tPURCHASEDESC\tACCNT\tASSETACCNT\tCOGSACCNT\tPRICE\tCOST\tTAXABLE\tPAYMETH\tTAXVEND\tTAXDIST\tPREFVEND\tREORDERPOINT\tQNTY\tEXTRA\n";

// Set item type
    if (ITEM_IMPORT_TYPE==2) {
	  $item_type="SERV";
    } elseif (ITEM_IMPORT_TYPE==1) {
	  $item_type="PART";
    } else {
	  $item_type="INVENTORY";
    }  

// Loop through each product
	do {
      $prod_id=$myrowpd["products_id"];
	  $prod_price=round($myrowpd["products_price"],2);
	  $myrowpd["products_tax_class_id"]>0 ? $ptaxable="Y" : $ptaxable="N";
      $prod_prefix="";
	  $prod_name=$myrowpd["products_model"];
      $prod_desc=$myrowpd["products_name"];
      $prod_quan=$myrowpd["products_quantity"];

// Add product line without attributes
      $prod_data.="INVITEM\t$prod_name\t$item_type\t$prod_desc\t$prod_desc\t".ITEM_ACCT."\t".ITEM_ASSET_ACCT."\t".ITEM_COG_ACCT."\t$prod_price\t0\t$ptaxable\t\t\t\t\t\t$prod_quan\t\n";

// Add product line if 1 or 2 attributes
      $resultpx = tep_db_query("SELECT DISTINCT options_id FROM ".TABLE_PRODUCTS_ATTRIBUTES." WHERE products_id='$prod_id' ORDER BY options_id");
      if ($myrowpx=tep_db_fetch_array($resultpx)) {
	    $options_id=$myrowpx["options_id"];
		$option_data=prod_options($prod_id,$options_id,$prod_name,$prod_desc,$prod_price);
	    if ($myrowpx=tep_db_fetch_array($resultpx)) {
          $options_id_sub=$myrowpx["options_id"];
          foreach ($option_data as $option_row) {
		    $option_data=prod_options($prod_id,$options_id_sub,$option_row[0],$option_row[1],$option_row[2]);
		    foreach ($option_data as $option_row) {
		      $prod_data.="INVITEM\t".$option_row[0]."\t$item_type\t".$option_row[1]."\t".$option_row[1]."\t".ITEM_ACCT."\t".ITEM_ASSET_ACCT."\t".ITEM_COG_ACCT."\t".$option_row[2]."\t0\t$ptaxable\t\t\t\t\t\t\t\n";
			}
		  }
		} else {
		  foreach ($option_data as $option_row) {
	        $prod_data.="INVITEM\t".$option_row[0]."\t$item_type\t".$option_row[1]."\t".$option_row[1]."\t".ITEM_ACCT."\t".ITEM_ASSET_ACCT."\t".ITEM_COG_ACCT."\t".$option_row[2]."\t0\t$ptaxable\t\t\t\t\t\t\t\n";
		  }
		}
      }
	  
// Update qbi_imported status of order
      if ($qbimported==0) $result1=tep_db_query("UPDATE ".TABLE_PRODUCTS." SET qbi_imported=1 WHERE products_id=$prod_id");
	} while ($myrowpd = tep_db_fetch_array($resultpd));
	
// Write product data
    fwrite($handle,$prod_data);
	
// Close file
    fclose($handle);

// Download file
    if (QBI_DL_IIF==1) {
      $filename="qbi_output/qbi_prod.iif";
      $size=filesize($filename);
      header("Pragma: public");
      header("Cache-Control: private");
      header("Content-type: application/octet-stream");
      header("Content-Disposition: attachment; filename=qbi_prod.iif");
      header("Content-length: $size");
      readfile("$filename");
      exit();
    }
  }
}
?>