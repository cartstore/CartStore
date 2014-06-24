<?php
/*
  $Id: mysqlperformance.php,v 2.0 2007/10/02 22:50:51 hpdl Exp $

  Contribution made by Biznetstar.com 
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_MYSQL_PERFORMANCE);
  require(DIR_WS_FUNCTIONS . 'mysqlperformance.php');

  // require('includes/application_top.php');

  switch ($_GET['action']) {
		case 'deleterecords':
      unlink(DIR_FS_CATALOG.'slow_queries/slow_query_log.txt');
      tep_redirect(tep_href_link(FILENAME_MYSQL_PERFORMANCE));
			break;
		case 'deleteconfirm':
      cutline(DIR_FS_CATALOG.'slow_queries/slow_query_log.txt', $_GET['qID']);
      tep_redirect(tep_href_link(FILENAME_MYSQL_PERFORMANCE, 'page=' . $_GET['page']));
			break;
	}
?> 
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a>
    <?php echo HEADING_TITLE; ?>
</h1></div>

    <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body"><i class="fa fa-question-circle fa-5x pull-left"></i>
Help for this section is not yet available.                          </div>
                      </div>
                  </div>   
              </div>    
    
 <?php if ( $_GET['action'] == 'deleteconfirmall'){ // add a delete button ?>
	  <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
						<td align="center" class="main"><br><?php echo TEXT_DELETE ?></td>
          </tr>
          <tr>
						<td class="main" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_MYSQL_PERFORMANCE, 'action=deleterecords'). '">' . tep_image_button('button_delete.gif', IMAGE_BUTTON_DELETE) . '</a>'; ?></td>
						<td width="5"><?php echo tep_draw_separator('pixel_trans.gif', '5', '1'); ?></td>
						<td class="main" align="left"><?php echo '<a href="' . tep_href_link(FILENAME_MYSQL_PERFORMANCE). '">' . tep_image_button('button_cancel.gif', IMAGE_BUTTON_CANCEL) . '</a>'; ?></td>
					</tr>
        </table></td>
</tr>
<?php }else{ // add a delete button ?>

<p>	  <?php echo TEXT_NOTE_MYSQL_PERFORMANCE; ?> </p>
<p> <?php echo '<a class="btn btn-default" href="' . tep_href_link(FILENAME_MYSQL_PERFORMANCE, 'action=deleteconfirmall'). '">' . IMAGE_BUTTON_DELETE . '</a>'; ?>
</p>

	
<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table class="table table-hover table-condensed table-responsive">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"> <?php echo TABLE_HEADING_NUMBER; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_QUERY; ?>&nbsp;</td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_QLOCATION; ?>&nbsp;</td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_QUERY_TIME; ?>&nbsp;</td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE_CREATED; ?>&nbsp;</td>
                <td class="dataTableHeadingContent"> &nbsp;</td>
			 </tr>
<?php
  // File location (URL or server path)
  $FilePath = DIR_FS_CATALOG.'slow_queries/slow_query_log.txt';
  if (file_exists($FilePath)) {
  // Stores the content of the file
  $Lines = @file($FilePath);
  if ($Lines){
	  // Counts the number of lines
	  $LineCount = count($Lines);
	  // This will be a two dimensional array that holds the content nicely organized
	  $Data = array();
	  // We will use this as an index
	  $performance_numrows = 0;
	  // Loop through each line
	  foreach($Lines as $Value){
	    // In the array store this line with values delimited by \t (tab) as separate array values
	    $Data[$performance_numrows] = explode("\t", $Value);
	    // Increase the line index
	    $performance_numrows++;
	  }
	  $performance_numrows = $LineCount;
	  // determine the rows we need
	  if ($performance_numrows> MAX_DISPLAY_SEARCH_RESULTS){ // if there are more records than will fit on one page
	    $performance_start_row=($_GET['page']* MAX_DISPLAY_SEARCH_RESULTS)+1;
	    $performance_end_row= $performance_start+ MAX_DISPLAY_SEARCH_RESULTS;
	  }else{ // there are less than will fit on a page
		  $performance_start_row=0;
	    $performance_end_row= $performance_numrows;
	  }
	  $performance= $performance_start_row;
	   while ($performance < $performance_end_row)  {
	    if (strlen($performance+1) < 2) {
	      $row_num = '0' . ($performance+1);
	    }else{
	     $row_num = $performance+1;
			}
	?>
	         <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
	           <td class="dataTableContent"><?php echo $row_num; ?></td>
	           <td class="dataTableContent"><?php echo $Data[$performance][0]; ?></td>
	           <td class="dataTableContent"><?php echo $Data[$performance][1];?></td>
	           <td class="dataTableContent"><?php echo round($Data[$performance][2],4);?></td>
	           <td class="dataTableContent"><?php echo $Data[$performance][3];?></td>
	           <td class="dataTableContent">
	    	        <?php 
	              		echo '<a href="' . tep_href_link(FILENAME_MYSQL_PERFORMANCE, 'page=' . $_GET['page'] . '&qID=' . $performance.'&action=delete') . '">' .tep_image(DIR_WS_IMAGES . 'trash.gif', TEXT_DELETE_QUERY) . '</a>'; 
	    	        ?>
	           </td>
	         </tr>
	<?
			$performance++;
	  }
  }
  }
?>
         <tr>
            <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo sprintf(TEXT_DISPLAY_NUMBER_OF_QUERIES,  ($Lines)? $performance_start_row+1 : $performance_start_row,$performance_end_row,$performance_numrows); ?></td>
                 <td class="smallText" align="right"><?php echo display_links($performance_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
	          <td colspan="5"><br/>
              <?php echo TEXT_NOTE_2_MYSQL_PERFORMANCE; ?>
	          </td>
	        </tr>
            </table></td>
<?php
// RIGHT COLUMN STUFF
  switch ($_GET['action']) {
    case 'delete':
      $heading[] = array('text' => '<center><b>' . TEXT_INFO_HEADING_DELETE . '</b></center>');
      $contents = array('form' => tep_draw_form('install_contr_del', FILENAME_MYSQL_PERFORMANCE, 'page=' . $_GET['page'] . '&qID=' . $performance .  '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_MYSQL_PERFORMANCE, 'page=' . $_GET['page'] . '&qID=' . $performance) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
  }
  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";
    $box = new box;
    echo $box->infoBox($heading, $contents);
    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table> 
<?php } // add a delete button ?>
 
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
