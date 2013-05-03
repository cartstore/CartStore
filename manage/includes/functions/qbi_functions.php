<?php
/*
$Id: qbi_functions.php,v 2.10 2005/05/08 al Exp $

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

function arraycombine($a,$b) {
  $c=array();
  foreach($a as $key) list(,$c[$key])=each($b);
  return $c;
}

function cc_clear($oID) {
  tep_db_query("update ".TABLE_ORDERS." set cc_number = '' , cc_expires = '' where orders_id = '".tep_db_input($oID)."'");
  return;
}

function cust_name($c_id,$b_c,$c_l,$c_f) {
  // Set up substitutions
  $search=array("%I","%C","%L","%F");
  $replace=array($c_id,$b_c,$c_l,$c_f);
  // Identify correct template
  (strlen($b_c)>0) ? $custname=CUST_NAMEB : $custname=CUST_NAMER;
  // Parse template and substitute data for each placeholder
  do {
    $pos=strpos($custname,"%");
    $holder=substr($custname,$pos,2);
	$replacement=str_replace($search,$replace,$holder);
	// Clean data
	$replacement=preg_replace("/[^a-zA-Z_0-9 ]+/","",$replacement);
	$holderlen=2;
	if ($holder!="%I") {
	  $holderlen=5;
      // Set case   
      $case=substr($custname,$pos+4,1);
      if ($case=="W") {
	    $replacement=ucwords(strtolower($replacement));
      } elseif ($case=="U") {
	    $replacement=strtoupper($replacement);
      } elseif ($case=="L") {
        $replacement=strtolower($replacement);
      }
	  $replacement=str_replace(" ","",$replacement);
	  // Set length
      $length=substr($custname,$pos+2,2);
      $replacement=substr($replacement,0,$length);
	}
    // Make replacement
    $custname=substr_replace($custname,$replacement,$pos,$holderlen);
  } while (strpos($custname,"%")!==FALSE);
  // Determine overall length
  $custname=substr($custname,0,41);
return($custname);
}

function disc_delete($iifrefnum) {
  $result=tep_db_query("SELECT * FROM ".TABLE_QBI_DISC." ORDER BY qbi_disc_refnum");
  while ($myrow=tep_db_fetch_array($result)) {
    $dbrefnum[]=$myrow["qbi_disc_refnum"];
  }
  if (is_array($dbrefnum) AND is_array($iifrefnum)) {
    $qitemid=array_diff($dbrefnum,$iifrefnum);
    foreach ($qitemid as $item) {
      $resultb=tep_db_query("SELECT * FROM ".TABLE_QBI_DISC." WHERE qbi_disc_refnum='$item'");
      $myrowb=tep_db_fetch_array($resultb);
      echo "<tr><td>".$myrowb["qbi_disc_name"]."</td><td>".$myrowb["qbi_disc_desc"]."</td><td></td><td>Deleted</td></tr>\r\n";
      tep_db_query("DELETE FROM ".TABLE_QBI_DISC." WHERE qbi_disc_refnum='$item'");
    }
  }
  return;
}

function disc_dropdown($ot_mod) {
  // Find existing match
  $result=tep_db_query("SELECT * FROM ".TABLE_QBI_OT_DISC." WHERE qbi_ot_mod='$ot_mod' LIMIT 1");
  if ($myrow=tep_db_fetch_array($result)) {
    $payrefnum=$myrow["qbi_disc_refnum"];
  } else {
    $payrefnum=0;
  }
  // Add all QB disc methods to the dropdown menu
  ?><td class="dropqblist"><select name="disc_menu[<?php echo $ot_mod ?>]"> <?php
  (PRODS_SORT==1) ? $orderby='qbi_disc_desc' : $orderby='qbi_disc_name';
  for ($i=1; $i<=2; $i++) {
    ($i==1) ? $disctype="OTHC" : $disctype="DISC";
    ($i==1) ? $disclabel=DISCMATCH_FEE : $disclabel=DISCMATCH_DISC;
    $resultb=tep_db_query("SELECT * FROM ".TABLE_QBI_DISC." WHERE qbi_disc_type='$disctype' ORDER BY '$orderby'");
    if ($myrowb=tep_db_fetch_array($resultb)) { ?>
      <option value="0"></option>
      <optgroup label="<?php echo $disclabel ?>"><?php
      do {
        if (PRODS_SORT==1) {
          $qnamedesc=substr($myrowb['qbi_disc_desc']." (".$myrowb['qbi_disc_name'].")",0,PRODS_WIDTH);
        } else {
          $qnamedesc=substr($myrowb['qbi_disc_name']." (".$myrowb['qbi_disc_desc'].")",0,PRODS_WIDTH);
        } ?>
        <option value="<?php echo $myrowb["qbi_disc_refnum"]?>" <?php
        if ($payrefnum==$myrowb["qbi_disc_refnum"]) echo 'selected="selected"' ?>> <?php
        echo $qnamedesc ?></option><?php
      } while ($myrowb=tep_db_fetch_array($resultb)); ?>
      </optgroup> <?php
    }
  } ?>
  </select></td>
  </tr> <?php
  return;
}

function disc_list() {
  $disc_type='OTHC';
  $disc_label='DISC_OTHC';
  for ($i=1; $i<=2; $i++) {
    echo "<table class='lists'>";
    $result=tep_db_query("SELECT * FROM ".TABLE_QBI_DISC." WHERE qbi_disc_type='$disc_type' ORDER BY qbi_disc_name");
    if ($myrow=tep_db_fetch_array($result)) {
      echo "<tr><th class='colhead'>".constant($disc_label)."</th><th>&nbsp;</th></tr>\r\n";
      echo "<tr><th class='colhead'>".SETUP_NAME."</th><th class='colhead'>".SETUP_DESC."</th></tr>\r\n";
      do { 
        echo "<tr><td class='qbname'>".$myrow["qbi_disc_name"]."</td><td class='qbname'>".$myrow["qbi_disc_desc"]."</td></tr>\r\n";
      } while ($myrow=tep_db_fetch_array($result));
    }
    $disc_type='DISC';
    $disc_label='DISC_DISC';
    echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\r\n";
	echo "</table>";
  }
  return;
}

function disc_methods() {
  // Find all language directories
  $result=tep_db_query("SELECT * FROM ".TABLE_LANGUAGES." ORDER BY languages_id");
  if ($myrow=tep_db_fetch_array($result)) {
    do {
	  $key=$myrow["languages_id"];
      $lang_dirs[$key]=DIR_FS_CATALOG_LANGUAGES.$myrow["directory"]."/modules/order_total";
    } while ($myrow=tep_db_fetch_array($result));
  }
  // Find all ot modules for each language
  foreach ($lang_dirs as $lang_id=>$lang_dir) {
    $dh=opendir($lang_dir);
    while(false!==($pay_mod_file=readdir($dh))) {
	  // Parse the payment text from the file
      if (strpos($pay_mod_file,"php") AND !strpos($pay_mod_file,"shipping") AND !strpos($pay_mod_file,"subtotal") AND !strpos($pay_mod_file,"tax") AND !strpos($pay_mod_file,"total")) {
	    $handle=fopen($lang_dir."/".$pay_mod_file, "rb");
        while (!feof($handle)) {
		  $pay_mod_text=fgets($handle);
          if (strstr($pay_mod_text,"TITLE',")!=FALSE) {
		    $pay_mod_text=strstr($pay_mod_text,",");
			$start=strpos($pay_mod_text,"'")+1;
			$end=strrpos($pay_mod_text,"'");
			$pay_mod_text=substr($pay_mod_text,$start,$end-$start);
            $pay_mod=str_replace(".php","",$pay_mod_file);
			// Update or insert into db
            $resultb=tep_db_query("SELECT * FROM ".TABLE_QBI_OT." WHERE qbi_ot_mod='$pay_mod' AND language_id='".$lang_id."'");
            if ($myrowb=tep_db_fetch_array($resultb)) {
              tep_db_query("UPDATE ".TABLE_QBI_OT." SET qbi_ot_text='$pay_mod_text' WHERE qbi_ot_id='".$myrowb["qbi_ot_id"]."'");
            } else {
              tep_db_query("INSERT INTO ".TABLE_QBI_OT." (qbi_ot_mod,language_id,qbi_ot_text) VALUES ('$pay_mod','$lang_id','$pay_mod_text')");
            }
          }
		}
	  }
	}
  }
  return;
}

function disc_process($qname,$qrefnum,$qdesc,$qaccnt,$qprice,$qitemtype,$qtax) {
  $message="<tr><td>".$qname."</td><td>".$qdesc."</td><td>".$qaccnt."</td>";
  // Add or update item
  settype ($qprice,"float");
  $qname=tep_db_input($qname);
  $qdesc=tep_db_input($qdesc);
  $qaccnt=tep_db_input($qaccnt);
  $qitemtype=tep_db_input($qitemtype);
  ($qtax=='Y') ? $tax=1 : $tax=0;
  $result=tep_db_query("SELECT * FROM ".TABLE_QBI_DISC." WHERE qbi_disc_refnum='$qrefnum' LIMIT 1");
  if (tep_db_fetch_array($result)) {
    tep_db_query("UPDATE ".TABLE_QBI_DISC." SET qbi_disc_name='$qname',qbi_disc_desc='$qdesc',qbi_disc_accnt='$qaccnt',qbi_disc_price='$qprice',qbi_disc_type='$qitemtype',qbi_disc_tax='$tax' WHERE qbi_disc_refnum='$qrefnum'");
    (mysql_affected_rows()>0) ? $message.='<td>'.SETUP_UPDATED.'</td>' : $message.='<td>'.SETUP_NO_CHANGE.'</td>';
  } else {
    tep_db_query("INSERT INTO ".TABLE_QBI_DISC." (qbi_disc_name,qbi_disc_refnum,qbi_disc_desc,qbi_disc_accnt,qbi_disc_price,qbi_disc_type,qbi_disc_tax) VALUES ('$qname','$qrefnum','$qdesc','$qaccnt','$qprice','$qitemtype','$tax')");
    $message.='<td>'.SETUP_ADDED.'</td>';
  }
  $message.="</tr>";
  echo $message;
  return;
}

function disc_update($disc_menu) {
	// Parse form results
	foreach ($disc_menu as $discoscmod=>$discqbref) {
		settype($discqbref,'integer');
	// Update, delete, or insert into db
		$result=tep_db_query("SELECT * FROM ".TABLE_QBI_OT_DISC." WHERE qbi_ot_mod='$discoscmod' LIMIT 1");
		if (tep_db_fetch_array($result)) {
			if ($discqbref>0) {
				tep_db_query("UPDATE ".TABLE_QBI_OT_DISC." SET qbi_disc_refnum='$discqbref' WHERE qbi_ot_mod='$discoscmod'");
				} else {
				tep_db_query("DELETE FROM ".TABLE_QBI_OT_DISC." WHERE qbi_ot_mod='$discoscmod' LIMIT 1");
			}
		} elseif (!tep_db_fetch_array($result)) {
			if ($discqbref>0) {
				tep_db_query("INSERT INTO ".TABLE_QBI_OT_DISC." (qbi_ot_mod,qbi_disc_refnum) VALUES ('$discoscmod','$discqbref')");
			}
		}
	}
	return;
}

function executeSql($sql_file) {
//	  echo 'start SQL execute';
    file_exists($sql_file) or die("Error. No SQL upgrade file found.");
    $lines = file($sql_file);
    $newline = '';
    foreach ($lines as $line) {
      $line = trim($line);
      $keep_together = 1;

      // The following command checks to see if we're asking for a block of commands to be run at once.
      // Syntax: #NEXT_X_ROWS_AS_ONE_COMMAND:6     for running the next 6 commands together (commands denoted by a ;)
      if (substr($line,0,28) == '#NEXT_X_ROWS_AS_ONE_COMMAND:') $keep_together = substr($line,28);
      if (substr($line,0,1) != '#' && substr($line,0,1) != '-' && $line != '') {
        $newline .= $line . ' ';

        if ( substr($line,-1) ==  ';') {
          //found a semicolon, so treat it as a full command, incrementing counter of rows to process at once
          if (substr($newline,-1)==' ') $newline = substr($newline,0,(strlen($newline)-1)); 
          $lines_to_keep_together_counter++; 
          if ($lines_to_keep_together_counter == $keep_together) { // if all grouped rows have been loaded, go to execute.
            $complete_line = true;
            $lines_to_keep_together_counter=0;
          } else {
            $complete_line = false;
          }
        } //endif found ';'

        if ($complete_line) {
          if ($debug=='ON') echo 'About to execute.  Debug info:<br>$ line='.$line.'<br>$ complete_line='.$complete_line.'<br>$ keep_together='.$keep_together.'<br>SQL='.$newline.'<br><br>';
          if (get_magic_quotes_runtime() > 0) $newline=stripslashes($newline);
          tep_db_query($newline);
          // reset var's
          $newline = '';
          $keep_together=1;
          $complete_line = false;
        } //endif $complete_line

      } //endif ! # or -
    } // end foreach $lines
    return;
  } //end function

function find_country_id($country_name) {
  $country_id=0;
  $result=tep_db_query("select countries_id from ".TABLE_COUNTRIES." where countries_name like '".$country_name."'");
  if ($myrow=tep_db_fetch_array($result)) $country_id=$myrow['countries_id'];
  return($country_id);
}

function find_zone_id($country_id,$state) {
    $zone_id=0;
    $result=tep_db_query("select count(*) as total from ".TABLE_ZONES." where zone_country_id = '".(int)$country_id ."'");
    $myrow=tep_db_fetch_array($result);
    $entry_state_has_zones=($myrow['total']>0);
    if ($entry_state_has_zones==true) {
      $resultb=tep_db_query("select distinct zone_id from ".TABLE_ZONES." where zone_country_id = '".(int)$country_id."' and (zone_name like '".$state."' or zone_code like '%".$state."')");
      if (tep_db_num_rows($resultb)==1) {
        $myrowb=tep_db_fetch_array($resultb);
        $zone_id=$myrowb['zone_id'];
      }
    }
  return($zone_id);
}

function get_language_id($language_code) {
  $languages_query=tep_db_query("select languages_id from ".TABLE_LANGUAGES." where code='".$language_code."'");
  while ($languages=tep_db_fetch_array($languages_query)) {
	$language_id=$languages['languages_id'];
  }
  return $language_id;
}

function get_state_code($state_name,$country_id) {
  $result=tep_db_query("SELECT * FROM ".TABLE_ZONES." WHERE zone_name='$state_name' AND zone_country_id='$country_id'");
  if ($myrow=tep_db_fetch_array($result)) {
    $state_name=$myrow['zone_code'];
  }
  return $state_name;
}

function get_tax_rate_name($class_id, $country_id=0, $zone_id=0) {
  $tax_query=tep_db_query("SELECT SUM(tax_rate) AS tax_rate, tax_description FROM ".TABLE_TAX_RATES." tr left join ".TABLE_ZONES_TO_GEO_ZONES." za ON tr.tax_zone_id = za.geo_zone_id left join ".TABLE_GEO_ZONES." tz ON tz.geo_zone_id = tr.tax_zone_id WHERE (za.zone_country_id IS NULL OR za.zone_country_id = '0' OR za.zone_country_id = '".(int)$country_id."') AND (za.zone_id IS NULL OR za.zone_id = '0' OR za.zone_id = '".(int)$zone_id."') AND tr.tax_class_id = '".(int)$class_id."' GROUP BY tr.tax_priority");
  if (tep_db_num_rows($tax_query)) {
    $tax_multiplier=0;
    while ($tax=tep_db_fetch_array($tax_query)) {
      $tax_multiplier+=$tax['tax_rate'];
    }
	$tax_info[0]=$tax_multiplier;
	$tax_info[1]=$tax['tax_description'];
	$tax_info[2]=$tax['tax_rates_id'];
  }
  return $tax_info;
}

function group_process($qname,$qrefnum,$qdesc,$qtoprint,$handle,$iifheader) {
	$message="<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
	$message.="<tr><td>".$qname."</td><td>".$qdesc."</td><td>".$qtoprint."</td>";
	($qtoprint=='Y')? $qtoprint=1:$qtoprint=0;
	// See if group already exists and either add it or update it
	$qname=tep_db_input($qname);
	$qdesc=tep_db_input($qdesc);	
	$resultgg=tep_db_query("SELECT * FROM ".TABLE_QBI_GROUPS." WHERE qbi_groups_refnum='$qrefnum' LIMIT 1");
	if ($myrowgg=tep_db_fetch_array($resultgg)) {
		tep_db_query("UPDATE ".TABLE_QBI_GROUPS." SET qbi_groups_name='$qname',qbi_groups_desc='$qdesc',qbi_groups_toprint='$qtoprint' WHERE qbi_groups_refnum='$qrefnum'");
		if (mysql_affected_rows()>0) {
			$message.='<td>'.SETUP_UPDATED.'</td>';
		} else {
			$message.='<td>'.SETUP_NO_CHANGE.'</td>';
		}
	} else {
		tep_db_query("INSERT INTO ".TABLE_QBI_GROUPS." (qbi_groups_refnum,qbi_groups_name,qbi_groups_desc,qbi_groups_toprint) VALUES ('$qrefnum','$qname','$qdesc','$qtoprint')");
		$message.='<td>'.SETUP_ADDED.'</td>';
	}
	$message.="</tr>";
	echo $message;
	unset($qitems);
	settype($qitems,"array");
	// Retrieve each following item row until the end of the group is reached, and update or add to the group
	do {
	$iifdetail=fgetcsv($handle, 512, "\t");
	$iifitem=arraycombine($iifheader,$iifdetail);
	if ($iifitem["!INVITEM"]=="INVITEM") {
		$qname=$iifitem["NAME"];
		$qitem=$iifitem["REFNUM"];		
		$qquan=$iifitem["QNTY"];
		settype($qquan,"float");
	// If item is not already in the qbi_items table, give error
		$resultin=tep_db_query("SELECT * FROM ".TABLE_QBI_ITEMS." WHERE qbi_items_refnum='$qitem' LIMIT 1");
		if (tep_db_num_rows($resultin)==0) {
			$message="<tr><td>$qname</td><td>Item does not exist.</td><td></td><td>Error</td>";
		} else {
	// If it is in the table, update or add it to the group
			$myrowin=tep_db_fetch_array($resultin);
			$qitems[]=$iifitem["REFNUM"];
			$qdesc=$myrowin["qbi_items_desc"];
			$qname=tep_db_input($qname);
			$message="<tr><td>".$qname."</td><td>".$qdesc."</td><td>".$qquan."</td>";
			$resultgi=tep_db_query("SELECT * FROM ".TABLE_QBI_GROUPS_ITEMS." WHERE qbi_groups_refnum='$qrefnum' AND qbi_items_refnum='$qitem' LIMIT 1");
			if (tep_db_fetch_array($resultgi)) {
				tep_db_query("UPDATE ".TABLE_QBI_GROUPS_ITEMS." SET qbi_groups_items_quan='$qquan' WHERE qbi_groups_refnum='$qrefnum' AND qbi_items_refnum='$qitem'");
				if (mysql_affected_rows()>0) {
					$message.='<td>'.SETUP_UPDATED.'</td>';
				} else {
					$message.='<td>'.SETUP_NO_CHANGE.'</td>';
				}
			} else {
				tep_db_query("INSERT INTO ".TABLE_QBI_GROUPS_ITEMS." (qbi_groups_refnum,qbi_items_refnum,qbi_groups_items_quan) VALUES ('$qrefnum','$qitem','$qquan')");
				$message.='<td>'.SETUP_ADDED.'</td>';
			}
		}
		$message.="</tr>";
		echo $message;
	}
	} while ($iifitem["!INVITEM"]=="INVITEM");
	// Find items that are no longer in the group and remove them from the group
	$resultgd=tep_db_query("SELECT * FROM ".TABLE_QBI_GROUPS_ITEMS." WHERE qbi_groups_refnum='$qrefnum' ORDER BY qbi_items_refnum");
	if ($myrowgd=tep_db_fetch_array($resultgd)) {
		do {
			$qitemsp[]=$myrowgd["qbi_items_refnum"];
		} while ($myrowgd=tep_db_fetch_array($resultgd));
		$qitemsd=array_diff($qitemsp,$qitems);
		foreach ($qitemsd as $item) {
			$resultgdx=tep_db_query("SELECT gi.qbi_items_refnum AS qbi_items_refnum_gi, g.qbi_items_refnum AS qbi_items_refnum_g, * FROM ".TABLE_QBI_GROUPS_ITEMS." AS gi, ".TABLE_QBI_ITEMS." AS g WHERE qbi_groups_refnum='$qrefnum' AND gi.qbi_items_refnum='$item' AND gi.qbi_items_refnum=g.qbi_items_refnum ORDER BY gi.qbi_items_refnum");
			$myrowgdx=tep_db_fetch_array($resultgdx);
			echo "<tr><td>".$myrowgdx["qbi_items_name"]."</td><td>".$myrowgdx["qbi_items_refnum_gi"]."</td><td>".$myrowgdx["qbi_items_desc"]."</td><td>".$myrowgdx["qbi_groups_items_quan"]."</td><td>Deleted</td></tr>\r\n";
			tep_db_query("DELETE FROM ".TABLE_QBI_GROUPS_ITEMS." WHERE qbi_groups_refnum='$qrefnum' AND qbi_items_refnum='$item'");
		}
	}
	return;
}

function item_delete($iifrefnum) {
  $result=tep_db_query("SELECT * FROM ".TABLE_QBI_ITEMS." ORDER BY qbi_items_refnum");
  while ($myrow=tep_db_fetch_array($result)) {
    $dbrefnum[]=$myrow["qbi_items_refnum"];
  }
  if (is_array($dbrefnum) AND is_array($iifrefnum)) {
    $qitemid=array_diff($dbrefnum,$iifrefnum);
    foreach ($qitemid as $item) {
      $resultb=tep_db_query("SELECT * FROM ".TABLE_QBI_ITEMS." WHERE qbi_items_refnum='$item'");
      $myrowb=tep_db_fetch_array($resultb);
      echo "<tr><td>".$myrowb["qbi_items_name"]."</td><td>".$myrowb["qbi_items_desc"]."</td><td></td><td>Deleted</td></tr>\r\n";
      tep_db_query("DELETE FROM ".TABLE_QBI_ITEMS." WHERE qbi_items_refnum='$item'");
    }
  }
  return;
}

function item_group_list() {
	// List QB items
	echo "<table class='lists'>";
	$result=tep_db_query("SELECT * FROM ".TABLE_QBI_ITEMS." ORDER BY qbi_items_name");
	if ($myrow=tep_db_fetch_array($result)) {
		echo "<tr><th class='colhead'>".PROD_ITEMS."</th><th></th></tr>\r\n";
		echo "<tr><th class='colhead'>".SETUP_NAME."</th><th class='colhead'>".SETUP_DESC."</th></tr>\r\n";
		do { 
			echo "<tr><td class='qbname'>".$myrow["qbi_items_name"]."</td><td class='qbname'>".$myrow["qbi_items_desc"]."</td></tr>\r\n";
		} while ($myrow=tep_db_fetch_array($result));
	}
	// List QB groups
	$resultb=tep_db_query("SELECT * FROM ".TABLE_QBI_GROUPS." ORDER BY qbi_groups_name");
	if ($myrowb=tep_db_fetch_array($resultb)) {
		echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\r\n";
		echo "<tr><th class='colhead'>".PROD_GROUPS."</th><th></th></tr>\r\n";
		echo "<tr><th class='colhead'>".SETUP_NAME."</th><th class='colhead'>".SETUP_DESC."</th></tr>\r\n";
		do {
			echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\r\n";
			echo "<tr><td>".$myrowb["qbi_groups_name"]."</td><td>".$myrowb["qbi_groups_desc"]."</td></tr>\r\n";
			$groupsref=$myrowb["qbi_groups_refnum"];
			$resultc=tep_db_query("SELECT * FROM ".TABLE_QBI_GROUPS_ITEMS." as qgi, ".TABLE_QBI_ITEMS." as qi WHERE qbi_groups_refnum='$groupsref' AND qgi.qbi_items_refnum=qi.qbi_items_refnum ORDER BY qbi_items_desc");
			while ($myrowc=tep_db_fetch_array($resultc)) { 
				echo "<tr><td>".$myrowc["qbi_items_name"]."</td><td>".$myrowc["qbi_items_desc"]."</td></tr>\r\n";
			}
		} while ($myrowb=tep_db_fetch_array($resultb));
	}
	echo "</table>";
	return;
}

function item_menu($prodid,$optionid) { ?>
  <td class="dropqblist"><select name="product_menu[<?php echo $prodid."-".$optionid?>]"> <?php
  // Find existing product - item match
  $result=tep_db_query("SELECT * FROM ".TABLE_QBI_PRODUCTS_ITEMS." WHERE products_id='$prodid' AND products_options_values_id='$optionid' LIMIT 1");
  if ($myrow=tep_db_fetch_array($result)) {
    $itemrefnum=$myrow["qbi_groupsitems_refnum"];
  } else {
    $itemrefnum=0;
  }
  // Add all QB items to the dropdown menu
  (PRODS_SORT==1) ? $orderby='qbi_items_desc' : $orderby='qbi_items_name';
  unset($wherearray);
  $whereclause=" ";
  if (ITEM_MATCH_INV==1 OR ITEM_MATCH_NONINV==1 OR ITEM_MATCH_SERV==1) {
    if (ITEM_MATCH_INV==1) $wherearray[]="qbi_items_type='INVENTORY'";
    if (ITEM_MATCH_NONINV==1) $wherearray[]="qbi_items_type='PART'";
    if (ITEM_MATCH_SERV==1) $wherearray[]="qbi_items_type='SERV'";
    $whereclause=' WHERE '.implode(" OR ", $wherearray).' ';
  }
  $resultb=tep_db_query("SELECT * FROM ".TABLE_QBI_ITEMS.$whereclause."ORDER BY '$orderby'");
  if ($myrowb=tep_db_fetch_array($resultb)) { ?>
    <option value=""></option>
    <optgroup label="<?php echo PRODMATCH_ITEMS ?>"><?php
    do {
      if (PRODS_SORT==1) {
        $qnamedesc=substr($myrowb['qbi_items_desc']." (".$myrowb['qbi_items_name'].")",0,PRODS_WIDTH);
      } else {
        $qnamedesc=substr($myrowb['qbi_items_name']." (".$myrowb['qbi_items_desc'].")",0,PRODS_WIDTH);
      }
      echo '<option value="'.$myrowb["qbi_items_refnum"].'"';
      if ($itemrefnum==$myrowb["qbi_items_refnum"]) echo ' selected="selected"';
      echo ">$qnamedesc</option>";
    } while ($myrowb=tep_db_fetch_array($resultb)); ?>
    </optgroup><?php
  }
  // Add all QB groups to the dropdown menu
  (PRODS_SORT==1) ? $orderby='qbi_groups_desc' : $orderby='qbi_groups_name';
  $resultc=tep_db_query("SELECT * FROM ".TABLE_QBI_GROUPS." ORDER BY '$orderby'");
  if ($myrowc=tep_db_fetch_array($resultc)) { ?>
    <optgroup label="<?php echo PRODMATCH_GROUPS ?>"><?php
    do { 
      if (PRODS_SORT==1) {
        $qnamedesc=substr($myrowc['qbi_groups_desc']." (".$myrowc['qbi_groups_name'].")",0,PRODS_WIDTH);
      } else {
        $qnamedesc=substr($myrowc['qbi_groups_name']." (".$myrowc['qbi_groups_desc'].")",0,PRODS_WIDTH);
      }
      echo '<option value="'.$myrowc["qbi_groups_refnum"].'"';
      if ($itemrefnum==$myrowc["qbi_groups_refnum"]) echo 'selected="selected"';
      echo ">$qnamedesc</option>";
    } while ($myrowc=tep_db_fetch_array($resultc)); ?>
    </optgroup><?php
  }?>
  </select></td></tr> <?php
  return;
}

function item_process($qname,$qrefnum,$qdesc,$qaccnt,$qprice,$qitemtype) {
  $message="<tr><td>".$qname."</td><td>".$qdesc."</td><td>".$qaccnt."</td>";
  // Add or update item
  settype ($qprice,"float");
  $qname=tep_db_input($qname);
  $qdesc=tep_db_input($qdesc);
  $qaccnt=tep_db_input($qaccnt);
  $qitemtype=tep_db_input($qitemtype);	
  $result=tep_db_query("SELECT * FROM ".TABLE_QBI_ITEMS." WHERE qbi_items_refnum='$qrefnum' LIMIT 1");
  if (tep_db_fetch_array($result)) {
    tep_db_query("UPDATE ".TABLE_QBI_ITEMS." SET qbi_items_name='$qname',qbi_items_desc='$qdesc',qbi_items_accnt='$qaccnt',qbi_items_price='$qprice',qbi_items_type='$qitemtype' WHERE qbi_items_refnum='$qrefnum'");
    (mysql_affected_rows()>0) ? $message.='<td>'.SETUP_UPDATED.'</td>' : $message.='<td>'.SETUP_NO_CHANGE.'</td>';
  } else {
    tep_db_query("INSERT INTO ".TABLE_QBI_ITEMS." (qbi_items_name,qbi_items_refnum,qbi_items_desc,qbi_items_accnt,qbi_items_price,qbi_items_type) VALUES ('$qname','$qrefnum','$qdesc','$qaccnt','$qprice','$qitemtype')");
    $message.='<td>'.SETUP_ADDED.'</td>';
  }
  $message.="</tr>";
  echo $message;
  return;
}

function log_config() {
  $logconfig="\nConfiguration:\n";
  $logconfig.="\nQBI\n";
  $logconfig.="Quickbooks version: ".QBI_QB_VER."\n";
  $logconfig.="Download iif: ".QBI_DL_IIF."\n";
  $logconfig.="Product rows dispayed: ".QBI_PROD_ROWS."\n";
  $logconfig.="Products dropdown sort order: ".PRODS_SORT."\n";
  $logconfig.="Products dropdown width: ".PRODS_WIDTH."\n";
  $logconfig.="Products dropdown width: ".QBI_LOG."\n";
  $logconfig.="\nOrders\n";
  $logconfig.="Import orders with status: ".ORDERS_STATUS_IMPORT."\n";
  $logconfig.="Update status: ".QBI_STATUS_UPDATE."\n";
  $logconfig.="Change CC order status to: ".QBI_CC_STATUS_SELECT."\n";
  $logconfig.="Change check/mo status to: ".QBI_MO_STATUS_SELECT."\n";
  $logconfig.="Send status email: ".QBI_EMAIL_SEND."\n";
  $logconfig.="Delete credit card number: ".QBI_CC_CLEAR."\n";
  $logconfig.="\nCustomers\n";
  $logconfig.="Customer number (business): ".CUST_NAMEB."\n";
  $logconfig.="Customer number (residence): ".CUST_NAMER."\n";
  $logconfig.="Customer limit: ".CUST_LIMIT."\n";
  $logconfig.="Customer type: ".CUST_TYPE."\n";
  $logconfig.="Use state codes: ".CUST_STATE."\n";
  $logconfig.="Include local country: ".CUST_COUNTRY."\n";
  $logconfig.="Include company and contact: ".CUST_COMPCON."\n";
  $logconfig.="\nInvoices\n";
  $logconfig.="Invoice account: ".INVOICE_ACCT."\n";
  $logconfig.="Sales Receipt account: ".INVOICE_SALESACCT."\n";
  $logconfig.="Invoice number: ".ORDERS_DOCNUM."\n";
  $logconfig.="Invoice/Sales Receipt 'PO Number': ".ORDERS_PONUM."\n";
  $logconfig.="Invoice to print: ".INVOICE_TOPRINT."\n";
  $logconfig.="Invoice terms paid online: ".INVOICE_TERMSCC."\n";
  $logconfig.="Invoice terms not prepaid: ".INVOICE_TERMS."\n";
  $logconfig.="Invoice rep: ".INVOICE_REP."\n";
  $logconfig.="Invoice fob: ".INVOICE_FOB."\n";
  $logconfig.="Include customer comments: ".INVOICE_COMMENTS."\n";
  $logconfig.="Customer message: ".INVOICE_MESSAGE."\n";
  $logconfig.="Invoice memo: ".INVOICE_MEMO."\n";
  $logconfig.="\nItems\n";
  $logconfig.="Item income account: ".ITEM_ACCT."\n";
  $logconfig.="Item asset account: ".ITEM_ASSET_ACCT."\n";
  $logconfig.="Item class: ".ITEM_CLASS."\n";
  $logconfig.="COGS account: ".ITEM_COG_ACCT."\n";
  $logconfig.="Description language: ".ITEM_OSC_LANG."\n";
  $logconfig.="Match inventory: ".ITEM_MATCH_INV."\n";
  $logconfig.="Match non-inventory: ".ITEM_MATCH_NONINV."\n";
  $logconfig.="Match services: ".ITEM_MATCH_SERV."\n";
  $logconfig.="Use default item: ".ITEM_DEFAULT."\n";
  $logconfig.="Default name: ".ITEM_DEFAULT_NAME."\n";
  $logconfig.="Import type: ".ITEM_IMPORT_TYPE."\n";
  $logconfig.="\nShipping\n";
  $logconfig.="Shipping name: ".SHIP_NAME."\n";
  $logconfig.="Shipping description: ".SHIP_DESC."\n";
  $logconfig.="Shipping account: ".SHIP_ACCT."\n";
  $logconfig.="Shipping class: ".SHIP_CLASS."\n";
  $logconfig.="Shipping taxable: ".SHIP_TAX."\n";
  $logconfig.="\nTaxes\n";
  $logconfig.="Tax turned on: ".TAX_ON."\n";
  $logconfig.="Tax name: ".TAX_NAME."\n";
  $logconfig.="Tax agency: ".TAX_AGENCY."\n";
  $logconfig.="Tax rate: ".TAX_RATE."\n";
  $logconfig.="Use tax name table: ".TAX_LOOKUP."\n";
  $logconfig.="\nPayments\n";
  $logconfig.="Import payments: ".INVOICE_PMT."\n";
  $logconfig.="Payment memo: ".PMTS_MEMO."\n";
  return $logconfig;
}

function log_head() {
  $loghead="Quickbooks Import QBI Error Log\n\n";
  $loghead.="QBI Version: ".QBI_VER."\n";
  $loghead.="Date/Time: ".date("M d, Y h:i:s A")."\n\n";
  return $loghead;
}

function log_lang($language_id_default) {
  $loglang="Default Language Name: ".DEFAULT_LANGUAGE."\n";
  $loglang.="Default Language ID: ".$language_id_default."\n";
  return $loglang;
}
function log_open($logtype) {
  $loghandle=fopen("qbi_output/error_log_".$logtype.".txt", "wb");
  return $loghandle;
}

function pay_delete($iifrefnum) {
  $result=tep_db_query("SELECT * FROM ".TABLE_QBI_PAYQB." ORDER BY qbi_payqb_refnum");
  while ($myrow=tep_db_fetch_array($result)) {
    $dbrefnum[]=$myrow["qbi_payqb_refnum"];
  }
  if (is_array($dbrefnum) AND is_array($iifrefnum)) {
    $qitemid=array_diff($dbrefnum,$iifrefnum);
    foreach ($qitemid as $item) {
      $resultb=tep_db_query("SELECT * FROM ".TABLE_QBI_PAYQB." WHERE qbi_payqb_refnum='$item'");
      $myrowb=tep_db_fetch_array($resultb);
      echo "<tr><td>".$myrowb["qbi_payqb_name"]."</td><td>Deleted</td></tr>\r\n";
      tep_db_query("DELETE FROM ".TABLE_QBI_PAYQB." WHERE qbi_payqb_refnum='$item'");
    }
  }
  return;
}

function pay_dropdown($payosc_mod) {
	// Find existing pay match
	$result=tep_db_query("SELECT * FROM ".TABLE_QBI_PAYOSC_PAYQB." WHERE qbi_payosc_mod='$payosc_mod' LIMIT 1");
	($myrow=tep_db_fetch_array($result)) ? $payrefnum=$myrow["qbi_payqb_refnum"] : $payrefnum=0;
	// Add all QB payment methods to the dropdown menu ?>
	<td class="dropqblist"><select name="pay_menu[<?php echo $payosc_mod?>]">
	<option value="0"></option> <?php
	$resultb=tep_db_query("SELECT * FROM ".TABLE_QBI_PAYQB." WHERE qbi_payqb_hidden=0 ORDER BY qbi_payqb_name");
	while ($myrowb=tep_db_fetch_array($resultb)) {
		echo '<option value='.$myrowb["qbi_payqb_refnum"].'"';
		if ($payrefnum==$myrowb["qbi_payqb_refnum"]) echo ' selected="selected"';
		echo '>'.$myrowb["qbi_payqb_name"].'</option>';
	} ?>
	</select></td>
	</tr> <?php
	return;
}

function pay_listupdate($pay_type) {
  foreach ($pay_type as $key=>$value) {
    tep_db_query("UPDATE ".TABLE_QBI_PAYQB." SET qbi_payqb_type='$value' WHERE qbi_payqb_refnum='$key'");
  }
  return;
}

function pay_listshow() {
  echo "<table class='lists'>";
  $result=tep_db_query("SELECT * FROM ".TABLE_QBI_PAYQB." ORDER BY qbi_payqb_name");
  if ($myrow=tep_db_fetch_array($result)) {
    echo "<tr><th class='colhead'>".PAY_METHOD."</th><th class='colhead'>".PAY_TYPE."</th></tr>\r\n";
    do {
      echo "<tr><td class='qbname'>".$myrow["qbi_payqb_name"]."</td><td class='dropqblist'>"; ?>
	  <select name="pay_type[<?php echo $myrow["qbi_payqb_refnum"] ?>]">
	  <option value="0" <?php if ($myrow["qbi_payqb_type"]==0) echo 'selected="selected"' ?>><?php echo PAY_NOT_PAID ?></option>
	  <option value="1" <?php if ($myrow["qbi_payqb_type"]==1) echo ' selected="selected"' ?>><?php echo PAY_PRE_PAID ?></option>
	  </select>
	  <?php echo "</td></tr>\r\n";
    } while ($myrow=tep_db_fetch_array($result));
  }
  echo "</table>";
  return;
}

function pay_methods() {
  // Find all language directories
  $resultl=tep_db_query("SELECT * FROM ".TABLE_LANGUAGES." ORDER BY languages_id");
  if ($myrowl=tep_db_fetch_array($resultl)) {
    do {
	  $key=$myrowl["languages_id"];
      $lang_dirs[$key]=DIR_FS_CATALOG_LANGUAGES.$myrowl["directory"]."/modules/payment";
    } while ($myrowl=tep_db_fetch_array($resultl));
  }
  // Find all payment modules for each language
  foreach ($lang_dirs as $lang_id=>$lang_dir) {
    $dh=opendir($lang_dir);
    while(false!==($pay_mod_file=readdir($dh))) {
	  // Parse the payment text from the file
      if (strstr($pay_mod_file,"php")) {
	    $handle=fopen($lang_dir."/".$pay_mod_file, "rb");
        while (!feof($handle)) {
		  $pay_mod_text=fgets($handle);
          if (strstr($pay_mod_text,"TEXT_TITLE',")!=FALSE) {
		    $pay_mod_text=strstr($pay_mod_text,",");
			$start=strpos($pay_mod_text,"'")+1;
			$end=strrpos($pay_mod_text,"'");
			$pay_mod_text=substr($pay_mod_text,$start,$end-$start);
            $pay_mod=str_replace(".php","",$pay_mod_file);
			// Update or insert into db
            $result=tep_db_query("SELECT * FROM ".TABLE_QBI_PAYOSC." WHERE qbi_payosc_mod='$pay_mod' AND language_id='".$lang_id."'");
            if (tep_db_fetch_array($result)) {
              tep_db_query("UPDATE ".TABLE_QBI_PAYOSC." SET qbi_payosc_text='$pay_mod_text' WHERE qbi_payosc_id='".$result["qbi_payosc_id"]."'");
            } else {
              tep_db_query("INSERT INTO ".TABLE_QBI_PAYOSC." (qbi_payosc_mod,language_id,qbi_payosc_text) VALUES ('$pay_mod','$lang_id','$pay_mod_text')");
            }
          }
		}
	  }
	}
  }
  return;
}

function pay_methtype($paymeth) {
  $result=tep_db_query("SELECT * FROM ".TABLE_QBI_PAYOSC." AS osc, ".TABLE_QBI_PAYOSC_PAYQB." AS oscqb, ".TABLE_QBI_PAYQB." AS qb WHERE osc.qbi_payosc_text='$paymeth' AND oscqb.qbi_payosc_mod=osc.qbi_payosc_mod AND oscqb.qbi_payqb_refnum=qb.qbi_payqb_refnum");
  if ($myrow=tep_db_fetch_array($result)) {
    $payment["type"]=$myrow["qbi_payqb_type"];
    $payment["text"]=$myrow["qbi_payqb_name"];
  }
  return($payment);
}

function pay_process($qname,$qrefnum,$qhidden) {
  // Add or update item
  $qname=tep_db_input($qname);
  $qhidden=tep_db_input($qhidden);
  ($qhidden=="Y") ? $qhidden=1 : $qhidden=0;
  $result=tep_db_query("SELECT * FROM ".TABLE_QBI_PAYQB." WHERE qbi_payqb_refnum='$qrefnum' LIMIT 1");
  if (tep_db_fetch_array($result)) {
    tep_db_query("UPDATE ".TABLE_QBI_PAYQB." SET qbi_payqb_name='$qname',qbi_payqb_hidden='$qhidden' WHERE qbi_payqb_refnum='$qrefnum'");
  } else {
    tep_db_query("INSERT INTO ".TABLE_QBI_PAYQB." (qbi_payqb_name,qbi_payqb_hidden,qbi_payqb_refnum) VALUES ('$qname','$qhidden','$qrefnum')");
  }
  return;
}

function pay_update($pay_menu) {
	// Parse form results
	foreach ($pay_menu as $payoscmod=>$payqbref) {
		settype($payqbref,'integer');
	// Update, delete, or insert into db
		$result=tep_db_query("SELECT * FROM ".TABLE_QBI_PAYOSC_PAYQB." WHERE qbi_payosc_mod='$payoscmod' LIMIT 1");
		if (tep_db_fetch_array($result)) {
			if ($payqbref>0) {
				tep_db_query("UPDATE ".TABLE_QBI_PAYOSC_PAYQB." SET qbi_payqb_refnum='$payqbref' WHERE qbi_payosc_mod='$payoscmod'");
				} else {
				tep_db_query("DELETE FROM ".TABLE_QBI_PAYOSC_PAYQB." WHERE qbi_payosc_mod='$payoscmod' LIMIT 1");
			}
		} elseif (!tep_db_fetch_array($result)) {
			if ($payqbref>0) {
				tep_db_query("INSERT INTO ".TABLE_QBI_PAYOSC_PAYQB." (qbi_payosc_mod,qbi_payqb_refnum) VALUES ('$payoscmod','$payqbref')");
			}
		}
	}
	return;
}

function prod_options($prod_id,$options_id,$prod_name,$prod_desc,$prod_price) {
  global $languages_id;
  $resultpy = tep_db_query("SELECT * FROM ".TABLE_PRODUCTS_ATTRIBUTES." AS pa, ".TABLE_PRODUCTS_OPTIONS." AS po, ".TABLE_PRODUCTS_OPTIONS_VALUES." AS pov WHERE pa.products_id='$prod_id' AND pa.options_id=$options_id AND pa.options_id=po.products_options_id AND pa.options_values_id=pov.products_options_values_id AND pov.language_id='$languages_id' AND po.language_id='$languages_id' ORDER BY options_values_id");
  while ($myrowpy=tep_db_fetch_array($resultpy)) {
    $option_row[0]=$prod_name.":".$myrowpy["products_options_values_name"];  
    $option_row[1]=$prod_desc." - ".$myrowpy["products_options_name"].":".$myrowpy["products_options_values_name"];
    $price=round($myrowpy["options_values_price"],2);
    if ($myrowpy["price_prefix"]=="-") $price=-$price;
	$option_row[2]=$prod_price+$price;
	$option_data[]=$option_row;
  }
  return $option_data;
}

function prod_update($product_menu) {
	// Parse form results
	foreach ($product_menu as $compid=>$itemid) {
		$compids=explode("-",$compid);
		$prodid=$compids[0];
		$optvalid=$compids[1];
		settype($prodid,'integer');		
		settype($optvalid,'integer');
		settype($itemid,'integer');
	// Update, delete, or insert into db
		$result=tep_db_query("SELECT * FROM ".TABLE_QBI_PRODUCTS_ITEMS." WHERE products_id='$prodid' AND products_options_values_id='$optvalid' LIMIT 1");
		if (tep_db_fetch_array($result)) {
			if ($itemid>0) {
				tep_db_query("UPDATE ".TABLE_QBI_PRODUCTS_ITEMS." SET qbi_groupsitems_refnum='$itemid' WHERE products_id='$prodid' AND products_options_values_id='$optvalid'");
				} else {
				tep_db_query("DELETE FROM ".TABLE_QBI_PRODUCTS_ITEMS." WHERE products_id='$prodid' AND products_options_values_id='$optvalid' LIMIT 1");
			}
		} elseif (!tep_db_fetch_array($result)) {
			if ($itemid>0) {
				tep_db_query("INSERT INTO ".TABLE_QBI_PRODUCTS_ITEMS." (products_id,products_options_values_id,qbi_groupsitems_refnum) VALUES ('$prodid','$optvalid','$itemid')");
			}
		}
	}
	return;
}

function round_amt($price) {
  $price+=0.000001;
  return tep_round($price,2);
}

function ship_delete($iifrefnum) {
  $result=tep_db_query("SELECT * FROM ".TABLE_QBI_SHIPQB." ORDER BY qbi_shipqb_refnum");
  while ($myrow=tep_db_fetch_array($result)) {
    $dbrefnum[]=$myrow["qbi_shipqb_refnum"];
  }
  if (is_array($dbrefnum) AND is_array($iifrefnum)) {
    $qitemid=array_diff($dbrefnum,$iifrefnum);
    foreach ($qitemid as $item) {
      $resultb=tep_db_query("SELECT * FROM ".TABLE_QBI_SHIPQB." WHERE qbi_shipqb_refnum='$item'");
      $myrowb=tep_db_fetch_array($resultb);
      echo "<tr><td>".$myrowb["qbi_shipqb_name"]."</td><td></td><td></td><td>Deleted</td></tr>\r\n";
      tep_db_query("DELETE FROM ".TABLE_QBI_SHIPQB." WHERE qbi_shipqb_refnum='$item'");
    }
  }
  return;
}

function ship_dropdown($car_code,$serv_code,$languages_id) {
	// Find existing shipping match
	$result=tep_db_query("SELECT * FROM ".TABLE_QBI_SHIPOSC_SHIPQB." WHERE qbi_shiposc_car_code='$car_code' AND qbi_shiposc_serv_code='$serv_code' LIMIT 1");
	if ($myrow=tep_db_fetch_array($result)) {
		$shiprefnum=$myrow["qbi_shipqb_refnum"];
	} else {
		$shiprefnum=0;
	}
	// Add all QB shipping methods to the dropdown menu
	?><td class="dropqblist"><select name="ship_menu[<?php echo $car_code."-".$serv_code ?>]">
	<option value="0"></option> <?php
	$resultb=tep_db_query("SELECT * FROM ".TABLE_QBI_SHIPQB." WHERE qbi_shipqb_hidden=0 ORDER BY qbi_shipqb_name");
	while ($myrowb=tep_db_fetch_array($resultb)) { ?>
		<option value="<?php echo $myrowb["qbi_shipqb_refnum"]?>"
	<?php if ($shiprefnum==$myrowb["qbi_shipqb_refnum"]) echo 'selected="selected"' ?>>
	<?php echo $myrowb["qbi_shipqb_name"]?></option>
	<?php } ?>
	</select></td></tr> <?php
	return;
}

function ship_list() {
	// List QB items
	echo "<table class='lists'>";
	$result=tep_db_query("SELECT * FROM ".TABLE_QBI_SHIPQB." WHERE qbi_shipqb_hidden=0 ORDER BY qbi_shipqb_name");
	if ($myrow=tep_db_fetch_array($result)) {
		echo "<tr><th class='colhead'>".SHIP_METHOD."</th></tr>\r\n";
		do {
			echo "<tr><td class='qbname'>".$myrow["qbi_shipqb_name"]."</td></tr>\r\n";
		} while ($myrow=tep_db_fetch_array($result));
	}
	echo "</table>";
	return;
}

function ship_methods() {
  // Find all language directories
  $resultl=tep_db_query("SELECT * FROM ".TABLE_LANGUAGES." ORDER BY languages_id");
  if ($myrowl=tep_db_fetch_array($resultl)) {
    do {
	  $key=$myrowl["languages_id"];
      $lang_dirs[$key]=DIR_FS_CATALOG_LANGUAGES.$myrowl["directory"]."/modules/shipping";
    } while ($myrowl=tep_db_fetch_array($resultl));
  }
  // Find all shipping modules for each language
  foreach ($lang_dirs as $lang_id=>$lang_dir) {
    $dh=opendir($lang_dir);
    while(false!==($ship_mod_file=readdir($dh))) {
	  // Parse the payment text from the file
      if (strstr($ship_mod_file,"php")) {
	    unset($ship_mod_text,$ship_mod_car_text,$ship_mod_car_code,$ship_mod_serv_text,$ship_mod_serv_code);
		// Open file
	    $handle=fopen($lang_dir."/".$ship_mod_file, "rb");
		// Extract carrier code
		$ship_mod_car_code=str_replace(".php","",$ship_mod_file);
        while (!feof($handle)) {
		  // Get a line of text
		  $ship_mod_text=fgets($handle);
		  // Check if line contains title (shipping carrier)
          if (strstr($ship_mod_text,"TEXT_TITLE',")!=FALSE) {
		  // Extract carrier text
		    $ship_mod_car_text=strstr($ship_mod_text,",");
			$start=strpos($ship_mod_car_text,"'")+1;
			$end=strrpos($ship_mod_car_text,"'");
			$ship_mod_car_text=substr($ship_mod_car_text,$start,$end-$start);
          // Check if line contains an option (shipping service)
          } elseif (strstr($ship_mod_text,"_OPT_")!=FALSE) {
		  // Extract service option text
		    $ship_mod_serv_txt=strstr($ship_mod_text,",");
			$start=strpos($ship_mod_serv_txt,"'")+1;
			$end=strrpos($ship_mod_serv_txt,"'");
			$ship_mod_serv_text[]=substr($ship_mod_serv_txt,$start,$end-$start);
			// Extract service option code 
			$ship_mod_serv_cd=strrchr($ship_mod_text,"_");
			$start=strrpos($ship_mod_serv_cd,"_")+1;
			$end=strpos($ship_mod_serv_cd,"'");
			$ship_mod_serv_code[]=substr($ship_mod_serv_cd,$start,$end-$start);
          }
		} // end while
		if (!is_array($ship_mod_serv_text)) {
		  $ship_mod_serv_text[]="";
		  $ship_mod_serv_code[]="";
		}
		$ship_mod_serv_textcode=arraycombine($ship_mod_serv_text,$ship_mod_serv_code);
		foreach($ship_mod_serv_textcode as $shipmod_serv_text=>$shipmod_serv_code) {
          // Update or insert into db
          $result=tep_db_query("SELECT * FROM ".TABLE_QBI_SHIPOSC." WHERE qbi_shiposc_car_code='$ship_mod_car_code' AND qbi_shiposc_serv_code='$shipmod_serv_code' AND language_id='$lang_id' LIMIT 1");
          if (tep_db_fetch_array($result)) {
		  	tep_db_query("UPDATE ".TABLE_QBI_SHIPOSC." SET qbi_shiposc_car_text='$ship_mod_car_text', qbi_shiposc_serv_text='$shipmod_serv_text' WHERE qbi_shiposc_car_code='$ship_mod_car_code' AND qbi_shiposc_serv_code='$shipmod_serv_code' AND language_id='$lang_id'");
          } else {
            tep_db_query("INSERT INTO ".TABLE_QBI_SHIPOSC." (qbi_shiposc_car_code,qbi_shiposc_serv_code,qbi_shiposc_car_text,qbi_shiposc_serv_text,language_id) VALUES ('$ship_mod_car_code','$shipmod_serv_code','$ship_mod_car_text','$shipmod_serv_text','$lang_id')");
          }
        } // end foreach
      }  // end if
    }  // end while
  }  // end foreach
  return;
}

function ship_process($qname,$qrefnum,$qhidden) {
	$message="<tr><td>".$qname."</td><td></td>";
	// Add or update item
	$qname=tep_db_input($qname);
	($qhidden==Y) ? $qhidden=1 : $qhidden=0;
	$result=tep_db_query("SELECT * FROM ".TABLE_QBI_SHIPQB." WHERE qbi_shipqb_refnum='$qrefnum' LIMIT 1");
	if (tep_db_fetch_array($result)) {
		tep_db_query("UPDATE ".TABLE_QBI_SHIPQB." SET qbi_shipqb_name='$qname',qbi_shipqb_hidden='$qhidden' WHERE qbi_shipqb_refnum='$qrefnum'");
		if (mysql_affected_rows()>0) {
			$message.="<td>".SETUP_UPDATED."</td>\r\n";
		} else {
			$message.="<td>".SETUP_NO_CHANGE."</td>\r\n";
		}
	} else {
		tep_db_query("INSERT INTO ".TABLE_QBI_SHIPQB." (qbi_shipqb_name,qbi_shipqb_refnum,qbi_shipqb_hidden) VALUES ('$qname','$qrefnum','$qhidden')");
		$message.="<td>".SETUP_ADDED."</td>\r\n";
	}
	$message.="</tr>";
	echo $message;
	return;
}

function ship_substitute($ordersid) {
  $shipvia=SHIP_NO_METHOD;
  $ship_method_query=tep_db_query("SELECT * FROM ".TABLE_ORDERS_TOTAL." WHERE orders_id=$ordersid AND class='ot_shipping'");
  if ($ship_method=tep_db_fetch_array($ship_method_query)) {
    $title=$ship_method['title'];
    $end=strpos($title,"(")-1;
    $carrier=substr($title,0,$end);
    $start=strrpos($title,"(")+1;
    $end=strrpos($title,")");
    $service=substr($title,$start,$end-$start);
    $ship_match_query=tep_db_query("SELECT * FROM ".TABLE_QBI_SHIPOSC." AS so, ".TABLE_QBI_SHIPOSC_SHIPQB." AS soqb, ".TABLE_QBI_SHIPQB." AS sqb WHERE qbi_shiposc_car_text='$carrier' AND qbi_shiposc_serv_text='$service' AND so.qbi_shiposc_car_code=soqb.qbi_shiposc_car_code AND so.qbi_shiposc_serv_code=soqb.qbi_shiposc_serv_code AND sqb.qbi_shipqb_refnum=soqb.qbi_shipqb_refnum");
    if ($ship_match=tep_db_fetch_array($ship_match_query)) {
      $shipvia=$ship_match["qbi_shipqb_name"];
    }
  }
  return($shipvia);
}

function ship_update($ship_menu) {
	// Parse form results
	foreach ($ship_menu as $carservcode=>$shipqbref) {
	$middle=strpos($carservcode,"-");
	$car_code=substr($carservcode,0,$middle);
	$serv_code=substr($carservcode,$middle+1);
		settype($shipqbref,'integer');
	// Update, delete, or insert into db
		$result=tep_db_query("SELECT * FROM ".TABLE_QBI_SHIPOSC_SHIPQB." WHERE qbi_shiposc_car_code='$car_code' AND qbi_shiposc_serv_code='$serv_code' LIMIT 1");
		if (tep_db_fetch_array($result)) {
			if ($shipqbref>0) {
				tep_db_query("UPDATE ".TABLE_QBI_SHIPOSC_SHIPQB." SET qbi_shipqb_refnum='$shipqbref' WHERE qbi_shiposc_car_code='$car_code' AND qbi_shiposc_serv_code='$serv_code'");
				} else {
				tep_db_query("DELETE FROM ".TABLE_QBI_SHIPOSC_SHIPQB." WHERE qbi_shiposc_car_code='$car_code' AND qbi_shiposc_serv_code='$serv_code' LIMIT 1");
			}
		} elseif (!tep_db_fetch_array($result)) {
			if ($shipqbref>0) {
				tep_db_query("INSERT INTO ".TABLE_QBI_SHIPOSC_SHIPQB." (qbi_shiposc_car_code,qbi_shiposc_serv_code,qbi_shipqb_refnum) VALUES ('$car_code','$serv_code','$shipqbref')");
			}
		}
	}
	return;
}

function status_dropdown($selected=0,$first_choice) {
  $orders_statuses=array();
  $orders_status_array=array();
  global $languages_id;
  $statbox='';
  if ($first_choice==0) {
  $statbox.='<option value="0">('.CONFIG_STATUS_ANY.')</option>';
  }   
  $orders_status_query=tep_db_query("select orders_status_id, orders_status_name from ".TABLE_ORDERS_STATUS." where language_id = '".(int)$languages_id."'");
  while ($orders_status=tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[]=array('id' => $orders_status['orders_status_id'], 'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']]=$orders_status['orders_status_name'];
  }
  foreach ($orders_status_array as $status_id=>$status_name) {
  	$statbox.='<option value="'.$status_id.'"';
	if ($status_id==$selected) {
	  $statbox.=' selected="selected"';
	  }
	$statbox.='>'.$status_name.'</option>';
  }
  return $statbox;
}

function status_email($oID,$payCC) {
  global $languages_id;
  ($payCC==1) ? $status=QBI_CC_STATUS_SELECT : $status=QBI_MO_STATUS_SELECT;
  $comments=tep_db_prepare_input('');
  $check_status_query=tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from ".TABLE_ORDERS." where orders_id = '".(int)$oID."'");
  $check_status=tep_db_fetch_array($check_status_query);
  if ($check_status['orders_status']!=$status) {
    tep_db_query("update ".TABLE_ORDERS." set orders_status = '".tep_db_input($status)."', last_modified = now() where orders_id = '".(int)$oID."'");
    $customer_notified='0';
    if (QBI_EMAIL_SEND==1) {
	  $result=tep_db_query("SELECT orders_status_name FROM ".TABLE_ORDERS_STATUS." WHERE orders_status_id=$status AND language_id=$languages_id");
      if ($myrow=tep_db_fetch_array($result)) $status_name=$myrow['orders_status_name'];
	  $email=STORE_NAME."\n".EMAIL_SEPARATOR."\n".EMAIL_TEXT_ORDER_NUMBER.' '.$oID."\n".EMAIL_TEXT_INVOICE_URL.' '.tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO,'order_id='.$oID,'SSL')."\n".EMAIL_TEXT_DATE_ORDERED.' '.tep_date_long($check_status['date_purchased'])."\n\n".sprintf(EMAIL_TEXT_STATUS_UPDATE,$status_name);
      tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      $customer_notified='1';
    }
    tep_db_query("insert into ".TABLE_ORDERS_STATUS_HISTORY." (orders_id, orders_status_id, date_added, customer_notified, comments) values ('".(int)$oID."', '".tep_db_input($status)."', now(), '".tep_db_input($customer_notified)."', '".tep_db_input($comments)."')");
  }
  return;
}

function tax_delete($iifrefnum) {
	$result=tep_db_query("SELECT * FROM ".TABLE_QBI_TAXES." ORDER BY qbi_taxes_refnum");
	while ($myrow=tep_db_fetch_array($result)) {
		$dbrefnum[]=$myrow["qbi_taxes_refnum"];
	}
	$qitemid=array_diff($dbrefnum,$iifrefnum);
	foreach ($qitemid as $item) {
		$resultb=tep_db_query("SELECT * FROM ".TABLE_QBI_TAXES." WHERE qbi_taxes_refnum='$item'");
		$myrowb=tep_db_fetch_array($resultb);
		echo "<tr><td>".$myrowb["qbi_taxes_name"]."</td><td>".$myrowb["qbi_taxes_desc"]."</td><td>".$myrowb["qbi_taxes_agency"]."</td><td>".$myrowb["qbi_taxes_rate"]."</td><td>Deleted</td></tr>\r\n";
		tep_db_query("DELETE FROM ".TABLE_QBI_TAXES." WHERE qbi_tax_refnum='$item'");
	}
	return;
}

function tax_group_process($handle) {
	do {
	// Retrieve tax lines and ignore them
		$iifread=fgetcsv($handle, 512, "\t");
	} while ($iifread[0]=="INVITEM");
	return;
}

function tax_list() {
	// List QB items
	echo "<table>";
	$result=tep_db_query("SELECT * FROM ".TABLE_QBI_TAXES." WHERE qbi_taxes_hidden=0 ORDER BY qbi_taxes_name");
	if ($myrow=tep_db_fetch_array($result)) {
		echo "<tr><td>".SETUP_NAME."</td><td></td></tr>\r\n";
		do {
			echo "<tr><td>".$myrow["qbi_taxes_name"]."</td><td>".$myrow["qbi_taxes_desc"]."</td><td>".$myrow["qbi_taxes_agency"]."</td><td>".$myrow["qbi_taxes_rate"]."</td></tr>\r\n";
		} while ($myrow=tep_db_fetch_array($result));
	}
	echo "</table>";
	return;
}

function tax_process($qrefnum,$qname,$qdesc,$qtaxvend,$qrate,$qhidden) {
	$message="<tr><td>".$qname."</td><td>".$qdesc."</td><td>".$qtaxvend."</td><td>".$qrate."</td>";
	// Add or update item
	$qname=tep_db_input($qname);
	$qdesc=tep_db_input($qdesc);
	$qtaxvend=tep_db_input($qtaxvend);
	($qhidden==Y)?$qhidden=1:$qhidden=0;
	$result=tep_db_query("SELECT * FROM ".TABLE_QBI_TAXES." WHERE qbi_taxes_refnum='$qrefnum' LIMIT 1");
	if (tep_db_fetch_array($result)) {
		tep_db_query("UPDATE ".TABLE_QBI_TAXES." SET qbi_taxes_name='$qname',qbi_taxes_desc='$qdesc',qbi_taxes_agency='$qtaxvend',qbi_taxes_rate='$qrate',qbi_taxes_hidden='$qhidden' WHERE qbi_taxes_refnum='$qrefnum'");
		if (mysql_affected_rows()>0) {
			$message.='<td>'.SETUP_UPDATED.'</td>';
		} else {
			$message.='<td>'.SETUP_NO_CHANGE.'</td>';
		}
	} else {
		tep_db_query("INSERT INTO ".TABLE_QBI_TAXES." (qbi_taxes_refnum,qbi_taxes_name,qbi_taxes_desc,qbi_taxes_agency,qbi_taxes_rate,qbi_taxes_hidden) VALUES ('$qrefnum','$qname','$qdesc','$qtaxvend','$qrate','$qhidden')");
		$message.='<td>'.SETUP_ADDED.'</td>';
	}
	$message.="</tr>";
	echo $message;
	return;
}
// Note: Absolutely no spaces allowed after the following php closing tag to avoid header error.
?>