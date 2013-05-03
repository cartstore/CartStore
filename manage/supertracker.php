<?php
/*
  $Id: supertracker.php, v3.3
  osCommerce, Open Source E-Commerce Solutions
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  Created by Mark Stephens, http://www.phpworks.co.uk
  Added keywords filters by Monika Mathé, http://www.monikamathe.com
*/

// ********** PAY PER CLICK CONFIGURATION SECTION ************
// Pay per click referral URLs used - to make this work you have to set up your pay-per-click
// URLs like this : http://www.yoursite.com/catalog/index.php?ref=xxx&keyw=yyy
// where xxx is a code representing the PPC service and yyy is the keyword being used
// to generate that referral. Here's an example :
// http://www.yoursite.com/catalog/index.php?ref=googled&keyw=gameboy
// which might be used for the keyword "gameboy" in a google adwords campaign.
// The keyword part is optional - if you don't use it in a particular campaign, you 
// Just set up the $ppc array like that in the example for googlead below

$ppc = array ('googlead' => array ('title' => 'Google Adwords', 'keywords' => 'shortcode1:friendlyterm1,shortcode1:friendlyterm2'));
							 
//Set the following to true to enable the PPC referrer report							
//Eventually, this will probably be moved into the configuration menu
//in admin, where it really should be!
 
define ('SUPERTRACKER_USE_PPC', false);				
// ********** PAY PER CLICK CONFIGURATION SECTION EOF ************

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/supertracker.php');
  include ('includes/classes/currencies.php');
  $currency = new currencies();	
	
  function draw_geo_graph($geo_hits,$country_names,$total_hits) {
	  echo '<table cellpadding=0 cellspacing=0 border=0 width="100%"><tr class="dataTableRow"><td class="dataTableContent"><table cellpadding=2 cellspacing=0 border=0>';
	  $max_pixels = 200;
	  arsort($geo_hits);
	  foreach ($geo_hits as $country_code=>$num_hits) {
        $country_name = $country_names[$country_code];
        $bar_length = ($num_hits/$total_hits) * $max_pixels;
        $percent_hits = round(($num_hits/$total_hits) * 100,2);
        //Create a random colour for each bar
        srand((double)microtime()*1000000);
        $r = dechex(rand (0, 255));
        $g = dechex(rand (0, 255));
        $b = dechex(rand (0, 255));			
			
        echo '<tr class="dataTableRow"><td class="dataTableContent">' . $country_name . ': </td><td class="dataTableContent"><div style="display:justify;background:#' . $r . $g . $b . '; border:1px solid #000; height:10px; width:' . $bar_length . '"></div></td><td class="dataTableContent">' . $percent_hits . '%</td></tr>'; 
	  }
	  echo '</table></td></tr></table>';
  }//end function
	
	if (isset($_GET['action'])) $action = $_GET['action'];
	if ($action == 'del_rows') {
    $rows_to_delete = $_POST['num_rows'];
		$del_query  = "DELETE from supertracker ORDER by tracking_id ASC LIMIT " . $rows_to_delete;
		$del_result = tep_db_query ($del_query);	
	}		
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>

<script language="javascript">
  function page_redirect(url) {
	  url=url.value;
		location.href = url;
	}
</script>
 

 
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top">
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
  		<tr>
		    <td>
				    <div class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></div>
						<div >
						<strong><?php echo TEXT_DATABASE_INFO; ?></strong>
<?php
                        $maint_query = "select tracking_id, time_arrived from supertracker order by tracking_id ASC";
						$maint_result = tep_db_query($maint_query);
						$num_rows = tep_db_num_rows($maint_result);
						$maint_row = tep_db_fetch_array($maint_result);
						echo '<span class="pageHeading">' . sprintf(TEXT_TABLE_DATABASE, $num_rows, tep_date_short($maint_row['time_arrived'])) . '</span><br><br>';
						echo '<form name="del_rows" action="supertracker.php?action=del_rows" method="post">' . TEXT_TABLE_DELETE . '<input name="num_rows" size=10>&nbsp;<input type="submit" class="button" value="' . TEXT_BUTTON_ERASE  . '"></form><br><br>';
?>
						  <?php echo TABLE_TEXT_MENU_DESC_TEXT; ?> <form name="report_select"><select name="report_selector" onChange="page_redirect(this)">
								<option value=""><?php echo TABLE_TEXT_MENU_TEXT; ?></option>
								<option value="supertracker.php?report=refer"><?php echo TEXT_TOP_REFERRERS; ?></option>
								<option value="supertracker.php?report=success_refer"><?php echo TEXT_TOP_SALES;?></option>
								<option value="supertracker.php?report=ave_clicks"><?php echo TEXT_AVERAGE_CLICKS;?></option>
								<option value="supertracker.php?report=ave_time"><?php echo TEXT_AVERAGE_TIME_SPENT;?></option>																
								<option value="supertracker.php?special=keywords"><?php echo TEXT_SEARCH_KEYWORDS;?></option>
								<option value="supertracker.php?special=keywords_last24"><?php echo TEXT_SEARCH_KEYWORDS_24;?></option>			
								<option value="supertracker.php?special=keywords_last72"><?php echo TEXT_SEARCH_KEYWORDS_3;?></option>			
								<option value="supertracker.php?special=keywords_lastweek"><?php echo TEXT_SEARCH_KEYWORDS_7;?></option>			
								<option value="supertracker.php?special=keywords_lastmonth"><?php echo TEXT_SEARCH_KEYWORDS_30;?></option>						
								<option value="supertracker.php?report=exit"><?php echo TEXT_TOP_EXIT_PAGES;?></option>
								<option value="supertracker.php?report=exit_added"><?php echo TEXT_TOP_EXIT_PAGES_NO_SALE;?></option>
								<option value="supertracker.php?special=prod_coverage"><?php echo TEXT_PRODUCTS_VIEWED_REPORT;?></option>
								<option value="supertracker.php?special=last_ten"><?php echo TEXT_LAST_TEN_VISITORS;?></option>
								<option value="supertracker.php?special=geo"><?php echo TEXT_VISITORS;?></option>
<?php if (SUPERTRACKER_USE_PPC) {?>						
								<option value="supertracker.php?special=ppc_summary"><?php echo TEXT_PPC_REFERRAL;?></option>			
<?php } ?>
							</select></form>
					    </div>						
					</div>
			  </td>
		  </tr>
<?php
  if (isset($_GET['report'])) {
    $report=$_GET['report'];
    $headings=array();
    $row_data=array();

    if ($report=='refer') { 
 	  $title = TEXT_TOP_REFERRERS;
      $headings[]=TEXT_RANKING;
      $headings[]=TEXT_REFERRING_URL;
      $headings[]=TEXT_NUMBER_OF_HITS;
		 
	  $row_data[]='referrer';
	  $row_data[]='total';		 
	  $tracker_query_raw='SELECT *, COUNT(*) as total FROM supertracker GROUP BY referrer order by total DESC';
	}
	 
	if ($report=='success_refer') {
 	  $title = TEXT_TOP_SALES;
      $headings[]=TEXT_RANKING;
      $headings[]=TEXT_REFERRING_URL;
      $headings[]=TEXT_NUMBER_OF_SALES;
		 
	  $row_data[]='referrer';
	  $row_data[]='total';
	  $tracker_query_raw='SELECT *, COUNT(*) as total FROM supertracker WHERE completed_purchase = "true" group by referrer order by total DESC';	 
    }
	 
	if ($report=='exit') {
      $title =TEXT_TOP_EXIT_PAGES;
      $headings[]=TEXT_RANKING;
      $headings[]=TEXT_EXIT_PAGE;
	  $headings[]=TEXT_NUMBER_OF_OCCURRENCES;
		 
      $row_data[]='exit_page';
      $row_data[]='total';
      $tracker_query_raw='SELECT *, COUNT(*) as total FROM supertracker where completed_purchase="false" group by exit_page order by total DESC';	 
    }	 
	 
    if ($report=='exit_added') {
      $title = TEXT_TOP_EXIT_PAGES_NO_SALE;
      $headings[]=TEXT_RANKING;
      $headings[]=TEXT_EXIT_PAGE;
      $headings[]=TEXT_NUMBER_OF_OCCURRENCES;
		 
      $row_data[]='exit_page';
      $row_data[]='total';
      $tracker_query_raw='SELECT *, COUNT(*) as total FROM supertracker where completed_purchase="false" and added_cart="true" group by exit_page order by total DESC';	 
	}	 
	 
    if ($report=='ave_clicks') {
	  $title = TEXT_AVERAGE_CLICKS;
      $headings[]=TEXT_RANKING;
      $headings[]=TEXT_REFERRING_URL;
      $headings[]=TEXT_NUMBER_OF_CLICKS;
		 
      $row_data[]='referrer';
      $row_data[]='ave_clicks';
      $tracker_query_raw='SELECT *, AVG(num_clicks) as ave_clicks FROM supertracker group by referrer order by ave_clicks DESC';
    }	 
	 
    if ($report=='ave_time') {
      $title = TEXT_AVERAGE_TIME_SPENT;
      $headings[]=TEXT_RANKING;
      $headings[]=TEXT_REFERRING_URL;
      $headings[]=TEXT_AVERAGE_LENGTH_OF_TIME;
		 
      $row_data[]='referrer';
      $row_data[]='ave_time';
      $tracker_query_raw='SELECT *, AVG(UNIX_TIMESTAMP(last_click) - UNIX_TIMESTAMP(time_arrived))/60 as ave_time FROM supertracker group by referrer order by ave_time DESC';	 
    }	 	 
 
    $tracker_query = tep_db_query($tracker_query_raw);
?>
      <tr>
        <td class="pageHeading"><?php echo $title; ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
<?php 
              foreach ($headings as $h) {
                echo '<td class="dataTableHeadingContent">' . $h . '</td>';
							}
?>
              </tr>
							
<?php


  $counter = 0;
  while ($tracker = tep_db_fetch_array($tracker_query)) {
		$counter++;

?>
             <tr class="dataTableRow" onMouseOver="this.className='dataTableRowOver';this.style.cursor='hand'" onMouseOut="this.className='dataTableRow'">
							<td class="dataTableContent"><?php echo $counter?></td>
							
<?php             
              foreach ($row_data as $r) {
							  if (strlen($tracker[$r]) > 50) $tracker[$r] = substr($tracker[$r],0,50); 	
  							echo '<td class="dataTableContent"' . $style_override . '>' . $tracker[$r] . '</td>';
							}
?>							
              </tr>
<?php
  }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>
<?php 
  } //End if 
	
 if (isset($_GET['special'])) {
       if ($_GET['special'] == 'keywords_last24') {
?>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
	   echo '<tr class="dataTableHeadingRow"><td class="dataTableHeadingContent">' . TEXT_SEARCH_KEYWORDS_24 . '</td><td class="dataTableHeadingContent">' . TEXT_NUMBER_OF_HITS . '</td></tr>';
	   $keywords_used = array();
       $keyword_query = "select * from supertracker where DATE_ADD(time_arrived, INTERVAL 1 DAY) >= now() ";
		 $keyword_result = tep_db_query ($keyword_query);
		 while ($keywords = tep_db_fetch_array($keyword_result)) {
		   $key_array = explode ('&', $keywords[referrer_query_string]);
			 for ($i=0; $i<sizeof($key_array); $i++) {
			  if (substr($key_array[$i], 0,2) == 'q=') { 
  				$keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
				}
			  if (substr($key_array[$i], 0,2) == 'p=') {  
  				$keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
				}				
			  if (strstr($key_array[$i], 'query=')) {
				  $keywords_used[str_replace('+', ' ', substr($key_array[$i],6, strlen($key_array[$i])-6))] +=1;
				}
			  if (strstr($key_array[$i], 'keyword=')) {
				  $keywords_used[str_replace('+', ' ', substr($key_array[$i],8, strlen($key_array[$i])-8))] +=1;
				}
			  if (strstr($key_array[$i], 'keywords=')) {
				  $keywords_used[str_replace('+', ' ', substr($key_array[$i],9, strlen($key_array[$i])-9))] +=1;
				}												
			 }
		 }
		 //Need a function to sort $keywords_used into order of no. of hits at some stage!
		 arsort($keywords_used);
		 foreach ($keywords_used as $kw=>$hits) {
		  echo '<tr class="dataTableRow"><td class="dataTableContent">' . $kw . '</td><td class="dataTableContent">' . $hits . '</td></tr>';
		 }
?>
		      </table>
		   </td>
		 </tr>
<?php		 
    }//End Keywords Report last 24h

    if ($_GET['special'] == 'keywords_last72') {
?>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
	   echo '<tr class="dataTableHeadingRow"><td class="dataTableHeadingContent">' . TEXT_SEARCH_KEYWORDS_3 . '</td><td class="dataTableHeadingContent">' . TEXT_NUMBER_OF_HITS . '</td></tr>';
	   $keywords_used = array();
     $keyword_query = "select * from supertracker where DATE_ADD(time_arrived, INTERVAL 3 DAY) >= now() ";
		 $keyword_result = tep_db_query ($keyword_query);
		 while ($keywords = tep_db_fetch_array($keyword_result)) {
		   $key_array = explode ('&', $keywords[referrer_query_string]);
			 for ($i=0; $i<sizeof($key_array); $i++) {
			  if (substr($key_array[$i], 0,2) == 'q=') { 
  				$keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
				}
			  if (substr($key_array[$i], 0,2) == 'p=') {  
  				$keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
				}				
			  if (strstr($key_array[$i], 'query=')) {
				  $keywords_used[str_replace('+', ' ', substr($key_array[$i],6, strlen($key_array[$i])-6))] +=1;
				}
			  if (strstr($key_array[$i], 'keyword=')) {
				  $keywords_used[str_replace('+', ' ', substr($key_array[$i],8, strlen($key_array[$i])-8))] +=1;
				}
			  if (strstr($key_array[$i], 'keywords=')) {
				  $keywords_used[str_replace('+', ' ', substr($key_array[$i],9, strlen($key_array[$i])-9))] +=1;
				}												
			 }
		 }
		 //Need a function to sort $keywords_used into order of no. of hits at some stage!
		 arsort($keywords_used);
		 foreach ($keywords_used as $kw=>$hits) {
		  echo '<tr class="dataTableRow"><td class="dataTableContent">' . $kw . '</td><td class="dataTableContent">' . $hits . '</td></tr>';
		 }
?>
		      </table>
		   </td>
		 </tr>
<?php		 
    }//End Keywords Report last 72h

    if ($_GET['special'] == 'keywords_lastweek') {
?>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
	   echo '<tr class="dataTableHeadingRow"><td class="dataTableHeadingContent">' . TEXT_SEARCH_KEYWORDS_7 . '</td><td class="dataTableHeadingContent">' . TEXT_NUMBER_OF_HITS . '</td></tr>';
	   $keywords_used = array();
     $keyword_query = "select * from supertracker where DATE_ADD(time_arrived, INTERVAL 7 DAY) >= now() ";
		 $keyword_result = tep_db_query ($keyword_query);
		 while ($keywords = tep_db_fetch_array($keyword_result)) {
		   $key_array = explode ('&', $keywords[referrer_query_string]);
			 for ($i=0; $i<sizeof($key_array); $i++) {
			  if (substr($key_array[$i], 0,2) == 'q=') { 
  				$keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
				}
			  if (substr($key_array[$i], 0,2) == 'p=') {  
  				$keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
				}				
			  if (strstr($key_array[$i], 'query=')) {
				  $keywords_used[str_replace('+', ' ', substr($key_array[$i],6, strlen($key_array[$i])-6))] +=1;
				}
			  if (strstr($key_array[$i], 'keyword=')) {
				  $keywords_used[str_replace('+', ' ', substr($key_array[$i],8, strlen($key_array[$i])-8))] +=1;
				}
			  if (strstr($key_array[$i], 'keywords=')) {
				  $keywords_used[str_replace('+', ' ', substr($key_array[$i],9, strlen($key_array[$i])-9))] +=1;
				}												
			 }
		 }
		 //Need a function to sort $keywords_used into order of no. of hits at some stage!
		 arsort($keywords_used);
		 foreach ($keywords_used as $kw=>$hits) {
		  echo '<tr class="dataTableRow"><td class="dataTableContent">' . $kw . '</td><td class="dataTableContent">' . $hits . '</td></tr>';
		 }
?>
		      </table>
		   </td>
		 </tr>
<?php		 
    }//End Keywords Report last 7d

    if ($_GET['special'] == 'keywords_lastmonth') {
?>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
	   echo '<tr class="dataTableHeadingRow"><td class="dataTableHeadingContent">' . TEXT_SEARCH_KEYWORDS_30 . '</td><td class="dataTableHeadingContent">' . TEXT_NUMBER_OF_HITS . '</td></tr>';
	   $keywords_used = array();
     $keyword_query = "select * from supertracker where DATE_ADD(time_arrived, INTERVAL 30 DAY) >= now() ";
		 $keyword_result = tep_db_query ($keyword_query);
		 while ($keywords = tep_db_fetch_array($keyword_result)) {
		   $key_array = explode ('&', $keywords[referrer_query_string]);
			 for ($i=0; $i<sizeof($key_array); $i++) {
			  if (substr($key_array[$i], 0,2) == 'q=') { 
  				$keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
				}
			  if (substr($key_array[$i], 0,2) == 'p=') {  
  				$keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
				}				
			  if (strstr($key_array[$i], 'query=')) {
				  $keywords_used[str_replace('+', ' ', substr($key_array[$i],6, strlen($key_array[$i])-6))] +=1;
				}
			  if (strstr($key_array[$i], 'keyword=')) {
				  $keywords_used[str_replace('+', ' ', substr($key_array[$i],8, strlen($key_array[$i])-8))] +=1;
				}
			  if (strstr($key_array[$i], 'keywords=')) {
				  $keywords_used[str_replace('+', ' ', substr($key_array[$i],9, strlen($key_array[$i])-9))] +=1;
				}												
			 }
		 }
		 //Need a function to sort $keywords_used into order of no. of hits at some stage!
		 arsort($keywords_used);
		 foreach ($keywords_used as $kw=>$hits) {
		  echo '<tr class="dataTableRow"><td class="dataTableContent">' . $kw . '</td><td class="dataTableContent">' . $hits . '</td></tr>';
		 }
?>
		      </table>
		   </td>
		 </tr>
<?php		 
    }//End Keywords Report last month

    if ($_GET['special'] == 'keywords') {
?>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
	   echo '<tr class="dataTableHeadingRow"><td class="dataTableHeadingContent">' . TEXT_SEARCH_KEYWORDS . '</td><td class="dataTableHeadingContent">' . TEXT_NUMBER_OF_HITS . '</td></tr>';
	   $keywords_used = array();
     $keyword_query = "select * from supertracker";
		 $keyword_result = tep_db_query ($keyword_query);
		 while ($keywords = tep_db_fetch_array($keyword_result)) {
		   $key_array = explode ('&', $keywords[referrer_query_string]);
			 for ($i=0; $i<sizeof($key_array); $i++) {
			  if (substr($key_array[$i], 0,2) == 'q=') { 
  				$keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
				}
			  if (substr($key_array[$i], 0,2) == 'p=') {  
  				$keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
				}				
			  if (strstr($key_array[$i], 'query=')) {
				  $keywords_used[str_replace('+', ' ', substr($key_array[$i],6, strlen($key_array[$i])-6))] +=1;
				}
			  if (strstr($key_array[$i], 'keyword=')) {
				  $keywords_used[str_replace('+', ' ', substr($key_array[$i],8, strlen($key_array[$i])-8))] +=1;
				}
			  if (strstr($key_array[$i], 'keywords=')) {
				  $keywords_used[str_replace('+', ' ', substr($key_array[$i],9, strlen($key_array[$i])-9))] +=1;
				}												
			 }
		 }
		 //Need a function to sort $keywords_used into order of no. of hits at some stage!
		 arsort($keywords_used);
		 foreach ($keywords_used as $kw=>$hits) {
		  echo '<tr class="dataTableRow"><td class="dataTableContent">' . $kw . '</td><td class="dataTableContent">' . $hits . '</td></tr>';
		 }
?>
		      </table>
		   </td>
		 </tr>
<?php		 
    }//End Keywords Report 
		
		if ($_GET['special'] == 'last_ten') {
		
		   if (isset($_GET['offset'])) $offset = $_GET['offset'];
			 else $offset = 0; 		

			 if (isset($_GET['refer_match'])) {
			   $match_refer_string = " and referrer like '%" . $_GET['refer_match'] . "%'";
				 $refer_match = $_GET['refer_match'];
			 }
			 else {
			    $match_refer_string = '';
					$refer_match = '';
			}

			 
			 if (isset($_GET['filter'])) {
			   $filter = $_GET['filter'];
			 }
			 else $filter = 'all';

			 switch ($filter) {
			 
			   case 'all' :
				 
    		  if ($refer_match == '') $lt_query = "select * from supertracker ORDER by last_click DESC LIMIT " . $offset . ",10";
					else $lt_query = "select * from supertracker where referrer like '%" . $refer_match . "%' ORDER by last_click DESC LIMIT " . $offset . ",10";				 
				 break;
				 
				 case 'bailed' :
    		   $lt_query = "select * from supertracker where added_cart = 'true' and completed_purchase = 'false' " . $match_refer_string . " ORDER by last_click DESC LIMIT " . $offset . ",10";				 
				 break;
				 
				 case 'completed' :
    		   $lt_query = "select * from supertracker where completed_purchase = 'true'  " . $match_refer_string . " ORDER by last_click DESC LIMIT " . $offset . ",10";				 
				 break;
			 
			 } // end switch


		  $lt_result= tep_db_query ($lt_query);
?>
     <table width="100%" border=0 cellspacing=0 cellpadding=0>
		    <tr>
			  <td class="dataTableContent">
                 <form name="filter_select" action="supertracker.php" method="get" onchange="this.submit()">
				 <input type="hidden" name="special" value="last_ten">
        		 <select name="filter">
      	    	 <option value="all" <?php if ($filter == 'all') echo 'selected';?>><?php echo TEXT_SHOW_ALL; ?></option>
        	  	 <option value="bailed" <?php if ($filter == 'bailed') echo 'selected';?>><?php echo TEXT_BAILED_CARTS; ?></option>
          		 <option value="completed" <?php if ($filter == 'completed') echo 'selected';?>><?php echo TEXT_SUCCESSFUL_CHECKOUTS; ?></option>
				 </select>&nbsp;&nbsp;
                 <?php echo TEXT_REFERRER_STRING; ?><input type="text" size="15" name="refer_match" value="<?php echo $refer_match;?>">
				 <input type="submit" class="button" value = "Update">						 
      		     </form><br><br>
			  </td>
			</tr>
		</table>			
								
<?php
		 while ($lt_row = tep_db_fetch_array($lt_result)) {
		   $customer_ip = $lt_row['ip_address'];
			 $country_code = $lt_row['country_code'];
			 $country_name = $lt_row['country_name'];			 
	
 			 
			 $customer_id = $lt_row['customer_id'];
			 if ($customer_id != 0) {
			   $cust_query = "select * from customers where customers_id ='" . $customer_id . "'";
				 $cust_result = tep_db_query ($cust_query);
				 $cust_row = tep_db_fetch_array($cust_result);
				 $customer_name = $cust_row['customers_firstname'] . ' ' . $cust_row['customers_lastname'];
			 }
			 else $customer_name = "Guest";
			 $referrer = $lt_row['referrer'] . '?' . $lt_row['referrer_query_string'];
			 if ($referrer == '?') $referrer = 'Direct Access / Bookmark';
			 $landing_page = $lt_row['landing_page'];
			 $last_page_viewed = $lt_row['exit_page'];
			 $time_arrived = $lt_row['time_arrived'];
			 $last_click = $lt_row['last_click'];
			 $num_clicks = $lt_row['num_clicks'];
			 $added_cart = $lt_row['added_cart'];
			 $completed_purchase = $lt_row['completed_purchase'];
			 $browser_string = $lt_row['browser_string'];
			 
			 if ($lt_row['products_viewed'] != '') {
  			 $products_viewed = $lt_row['products_viewed'];
  			 $prod_view_array = explode ('*',$products_viewed);
			}
			else $products_viewed = '';
      if($country_code==''){
        $country_code='pixel_trans';
      }			

            echo '<table width="100%" border=0 cellspacing=0 cellpadding=5 style="border:1px solid #000;">';
			echo '<tr><td class="dataTableContent"><b>' . TABLE_TEXT_IP . '</b><a href="http://www.showmyip.com/?ip=' . $customer_ip . '" target="_blank">' . $customer_ip . ' (' . $country_name . ')' . tep_image(DIR_WS_IMAGES . 'geo_flags/' . $country_code . '.gif') . ' - ' . gethostbyaddr($customer_ip) . '</td></tr>';
			echo '<tr><td class="dataTableContent"><b>' . TABLE_TEXT_CUSTOMER_BROWSER . '</b>' . $browser_string . '</td></tr>';			
			echo '<tr><td class="dataTableContent"><b>' . TABLE_TEXT_NAME . '</b>' . $customer_name . '</td></tr>';
  		    echo '<tr><td class="dataTableContent"><b>' . TABLE_TEXT_REFFERED_BY . '<a href="' . $referrer . '" target="_blank">' . $referrer . '</a></b></td></tr>';
			echo '<tr><td class="dataTableContent"><b>' . TABLE_TEXT_LANDING_PAGE . '</b>' . $landing_page . '</td></tr>';
			echo '<tr><td class="dataTableContent"><b>' . TABLE_TEXT_LAST_PAGE_VIEWED . '</b>' . $last_page_viewed . '</td></tr>';
			echo '<tr><td class="dataTableContent"><b>' . TABLE_TEXT_TIME_ARRIVED . '</b>' . tep_datetime_short($time_arrived) . '</td></tr>';
			echo '<tr><td class="dataTableContent"><b>' . TABLE_TEXT_LAST_CLICK . '</b>' . tep_datetime_short($last_click) . '</td></tr>';
			
            $time_on_site = strtotime($last_click) - strtotime($time_arrived);
			$hours_on_site = floor($time_on_site /3600);
            $minutes_on_site = floor( ($time_on_site - ($hours_on_site*3600))  / 60);
            $seconds_on_site = $time_on_site - ($hours_on_site *3600) - ($minutes_on_site * 60);
			$time_on_site = $hours_on_site . 'hrs ' . $minutes_on_site . 'mins ' . $seconds_on_site . ' seconds'; 

			echo '<tr><td class="dataTableContent"><b>' . TABLE_TEXT_TIME_ON_SITE . '</b>' . $time_on_site . '</td></tr>';
			echo '<tr><td class="dataTableContent"><b>' . TABLE_TEXT_NUMBER_OF_CLICKS . '</b>' . $num_clicks . '</td></tr>';
			echo '<tr><td class="dataTableContent"><b>' . TABLE_TEXT_ADDED_CART . '</b>' . $added_cart . '</td></tr>';
			echo '<tr><td class="dataTableContent"><b>' . TABLE_TEXT_COMPLETED_PURCHASE . '</b>' . $completed_purchase . '</td></tr>';
			
			if ($completed_purchase == 'true') {
			   $order_q = "select ot.text as order_total from orders as o, orders_total as ot where o.orders_id=ot.orders_id and o.orders_id = '" . $lt_row['order_id'] . "' and ot.class='ot_total'";
				 $order_result = tep_db_query($order_q);
				 if (tep_db_num_rows($order_result)>0) {
				   $order_row = tep_db_fetch_array($order_result);
      		 echo '<tr><td class="dataTableContent">' . TABLE_TEXT_ORDER_VALUE . $order_row['order_total'] . '</td></tr>';				 
				 }
			}
			
		  $categories_viewed = unserialize($lt_row['categories_viewed']);
			if (!empty($categories_viewed)) {
			  $cat_string = '';
			  foreach ($categories_viewed as $cat_id=>$val) {
				  $cat_query = "select * from categories as c, categories_description as cd where c.categories_id=cd.categories_id and c.categories_id='" . $cat_id . "'";
					$cat_result = tep_db_query($cat_query);
					$cat_row = tep_db_fetch_array($cat_result);
					$cat_string .= $cat_row['categories_name'] . ',';
				}
				$cat_string = rtrim($cat_string, ',');
                echo '<tr><td class="dataTableContent"><strong>' . TABLE_TEXT_CATEGORIES . '</strong>' . $cat_string . '</td></tr>';						
			}
			
			
			if ($products_viewed != '') {
  			echo '<tr><td class="dataTableContent"><strong>' . TABLE_TEXT_PRODUCTS . ' </strong><table cellspacing=0 cellpadding=0 border=1><tr>';
			  foreach ($prod_view_array as $key=>$product_id) {
				  $product_id = rtrim($product_id, '?');
					if ($product_id != '') {
                      $prod_query = "select * from products as p, products_description as pd where p.products_id=pd.products_id and p.products_id='" . $product_id . "'";
  				      $prod_result = tep_db_query($prod_query);
  					  $prod_row = tep_db_fetch_array($prod_result);
					  if (tep_not_null($prod_row['products_image'])) {
  					    echo '<td><table cellspacing=0 cellpadding=2 border=0 align="center"><tr><td align="center">' . tep_image(DIR_WS_CATALOG_IMAGES . $prod_row['products_image'], $prod_row['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</td></tr><tr><td class="dataTableContent" align="center">' . $prod_row['products_name'] . '</td></tr></table></td>';
                      } else {
  					    echo '<td><table cellspacing=0 cellpadding=2 border=0 align="center"><tr><td align="center">' . tep_image(DIR_WS_CATALOG_IMAGES . 'st_no_image.jpg', $prod_row['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</td></tr><tr><td class="dataTableContent" align="center">' . $prod_row['products_name'] . '</td></tr></table></td>';
					  }
					}				
				}
  			  echo '</tr></table></td></tr>';
			}
							
		  $cart_contents = unserialize($lt_row['cart_contents']);

			if (!empty($cart_contents)) {
  			  echo '<tr><td class="dataTableContent"><strong>' . TABLE_TEXT_CUSTOMERS_CART . '(value=' . $currency->format($lt_row['cart_total']) . ') : </strong><table cellspacing=0 cellpadding=0 border=1><tr>';			
              foreach ($cart_contents as $product_id => $qty_array) {
                $prod_query = "select * from products as p, products_description as pd where p.products_id=pd.products_id and p.products_id='" . $product_id . "'";
  				$prod_result = tep_db_query($prod_query);
  				$prod_row = tep_db_fetch_array($prod_result);
				if (tep_not_null($prod_row['products_image'])) {
  				  echo '<td><table cellspacing=0 cellpadding=2 border=0 align="center"><tr><td align="center" valign="middle">' . tep_image(DIR_WS_CATALOG_IMAGES . $prod_row['products_image'], $prod_row['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</td></tr><tr><td class="dataTableContent" align="center">' . $prod_row['products_name'] . '</td></tr><tr><td class="dataTableContent">' . TABLE_TEXT_QUANTITY . $qty_array['qty'] . '</td></tr></table></td>';
                } else {
  				  echo '<td><table cellspacing=0 cellpadding=2 border=0 align="center"><tr><td align="center" valign="middle">' . tep_image(DIR_WS_CATALOG_IMAGES . 'st_no_image.jpg', $prod_row['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</td></tr><tr><td class="dataTableContent" align="center">' . $prod_row['products_name'] . '</td></tr><tr><td class="dataTableContent">' . TABLE_TEXT_QUANTITY . $qty_array['qty'] . '</td></tr></table></td>';
                }
			  }
  			  echo '</tr></table></td></tr>';
			}						
															
			echo '</table>';			  								
			
		
		 }//End While
?>
<br><strong><a href="supertracker.php?special=last_ten&offset=<?php echo $offset + 10;?>&filter=<?php echo $filter;?>&refer_match=<?php echo $refer_match;?>"><input type="submit" class="button" value="<?php echo TABLE_TEXT_NEXT_TEN_RESULTS; ?>"></a></strong>
<?php
		}//End Special "Last Ten" Report
		
   if ($_GET['special'] == 'ppc_summary') {
     echo '<table width="100%" border=0 cellspacing=0 cellpadding=5 style="border:1px solid #000;">';	 
	  foreach ($ppc as $ref_code => $details) {
		  $scheme_name = $details['title'];
			$keywords = $details['keywords'];
		
		  $ppc_q = "SELECT * from supertracker where landing_page like '%ref=" . $ref_code . "%'";
			$ppc_result = tep_db_query ($ppc_q);
			$ppc_num_refs = tep_db_num_rows($ppc_result);
			echo '<tr><td style="font-weight:bold;text-decoration:underline;">' . $scheme_name . ' - Total Referrals ' . $ppc_num_refs . '</td></tr>'; 
			
			if ($keywords != '') {
  			$keyword_array = explode(',',$keywords);
  			foreach ($keyword_array as $key => $val) {
  			  $colon_pos = strpos ($val, ':');
  				$keyword_code = substr($val,0,$colon_pos);
  				$keyword_friendly_name = substr($val,$colon_pos+1,strlen($val)-$colon_pos);
  				$ppc_key_q = "SELECT *, count(*) as count, AVG(num_clicks) as ave_clicks, AVG(UNIX_TIMESTAMP(last_click) - UNIX_TIMESTAMP(time_arrived))/60 as ave_time from supertracker where landing_page like '%ref=" . $ref_code . "&keyw=" . $keyword_code . "%' group by landing_page";
  				$ppc_key_result = tep_db_query($ppc_key_q);
					$ppc_row = tep_db_fetch_array($ppc_key_result);
  				$ppc_key_refs = $ppc_row['count'];
  				echo '<tr><td>' . $keyword_friendly_name . ' : ' . $ppc_key_refs . TABLE_TEXT_AVERAGE_TIME  . $ppc_row['ave_time'] . TABLE_TEXT_MINS_AVERAGE_CLICKS  . $ppc_row['ave_clicks'] . '</td></tr>';
  			}
			}
		}
		echo '</table>';
	 
	 }//End PPC Summary Report		
	 
   if ($_GET['special'] == 'geo') {
?>
	 <tr>
		<td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
	   echo '<tr class="dataTableHeadingRow"><td class="dataTableHeadingContent">' . TABLE_TEXT_COUNTRY . '</td></tr>'; 
	   $geo_query = "select count(*) as count, country_code, country_name from supertracker GROUP by country_code";
		 $geo_result = tep_db_query($geo_query);
		 $geo_hits = array();
		 $country_names = array();
		 $total_hits = 0;
		 while ($geo_row = tep_db_fetch_array($geo_result)) {
		   $total_hits += $geo_row['count'];
           $country_code = strtolower($geo_row['country_code']);
           $geo_hits[$country_code] = $geo_row['count'];
           $country_names[$country_code] = $geo_row['country_name'];			 
		 }
		 draw_geo_graph($geo_hits,$country_names,$total_hits);
	 }//End Geo Report
	 
   if ($_GET['special'] == 'prod_coverage') {
	 
	 		 if (isset($_GET['agent_match'])) {
			   $agent_match = $_GET['agent_match'];
			   $match_agent_string = " and browser_string like '%" . $agent_match . "%'";
			 }
			 else {
			    $match_agent_string = '';
					$agent_match = '';
			}
?>
     <table width="100%" border=0 cellspacing=0 cellpadding=0>
		   <tr>
			   <td class="dataTableContent">
           <form name="filter_select" action="supertracker.php" method="get" onchange="this.submit()">
					 <input type="hidden" name="special" value="prod_coverage">
             
             <?php echo TEXT_REFERRER_STRING; ?>
             <input type="text" size="15" name="agent_match" value="<?php echo $agent_match;?>">
						 <input type="submit" class="button" value = "Update">						 
      		 </form>
				 </td>
			</tr>
		</table>			
<?php
	   $prod_q = "select p.products_id, pd.products_name from products as p, products_description as pd where p.products_id=pd.products_id and p.products_status='1'";
		 $prod_result = tep_db_query($prod_q);
		 $prod_coverage = array();
		 while ($prod_row = tep_db_fetch_array($prod_result)) {
		   $cov_q = "select * from supertracker where products_viewed like '%" . $prod_row['products_id'] . "%'" . $match_agent_string ;
			 $cov_result = tep_db_query($cov_q);
			 $prod_coverage[$prod_row['products_name']] = tep_db_num_rows($cov_result);
		 } // End While loop
		 arsort($prod_coverage); 
?>
     <table cellpadding=2 cellspacing=0 border=0 width="100%">
       <tr><td class="pageHeading" colspan=2 align="left"><?php echo TEXT_PRODUCTS_VIEWED_REPORT; ?></td></tr>		 
       <tr class="dataTableHeadingRow"><td class="dataTableHeadingContent"><?php echo TABLE_TEXT_PRODUCT_NAME; ?></td><td class="dataTableHeadingContent"><?php echo TABLE_TEXT_NUMBER_OF_VIEWING; ?></td></tr>			 
<?php
     foreach ($prod_coverage as $prod_name => $hits) {
		   echo '<tr><td class="dataTableContent">' . $prod_name . '</td><td class="dataTableContent">' . $hits . '</td></tr>';
		 }		 
?>
		 </table>
<?php		 		 
	 } // End Product Coverage Report	 
 }
?>		
		
		</td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>