<?php
  require('includes/application_top.php');
  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  if (tep_not_null($action)) {
      switch ($action) {
          case 'save':
              $separator = chr(10);
              $query = "select customers_email_address";
              $string = "Email Address";
              if (isset($_POST['c_name'])) {
                  $query .= ", customers_firstname";
                  $string .= ",First Name";
              } //if (isset($_POST['c_name']))
              if (isset($_POST['c_lastname'])) {
                  $query .= ", customers_lastname";
                  $string .= ",Last Name";
              } //if (isset($_POST['c_lastname']))
              $query .= " from " . TABLE_CUSTOMERS . " where customers_paypal_ec =0";
              $string .= $separator;
              $filename = date("m-d-Y-H-i-s") . ".csv";
              $customers_query = tep_db_query($query);
              while ($customers = tep_db_fetch_array($customers_query)) {
                  $string .= $customers["customers_email_address"];
                  if (isset($_POST['c_name']))
                      $string .= "," . $customers["customers_firstname"];
                  if (isset($_POST['c_lastname']))
                      $string .= "," . $customers["customers_lastname"];
                  $string .= $separator;
              } //while ($customers = tep_db_fetch_array($customers_query))
              $fp = fopen(DIR_FS_MAIL_DUMPS . $filename, 'w');
              fwrite($fp, $string);
              fclose($fp);
              tep_redirect(tep_href_link(FILENAME_CONSTANT_CONTACT, 'dID=' . $filename));
              break;
          case 'download':
              header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
              header("Last-Modified: " . gmdate("D,d M Y H:i:s") . " GMT");
              header("Cache-Control: no-cache, must-revalidate");
              header("Pragma: no-cache");
              header("Content-Type: Application/octet-stream");
              header("Content-disposition: attachment; filename=" . $_GET['dID']);
              header("Content-size: " . filesize(DIR_FS_MAIL_DUMPS . $_GET['dID']));
              readfile(DIR_FS_MAIL_DUMPS . $_GET['dID']);
              break;
          case 'deleteconfirm':
              unlink(DIR_FS_MAIL_DUMPS . $_GET['dID']);
              tep_redirect(tep_href_link(FILENAME_CONSTANT_CONTACT, 'page=' . $_GET['page']));
              break;
      } //switch ($action)
  } //if (tep_not_null($action))
?>
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>


    <div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a>
 
    <?php
  echo HEADING_TITLE;
?></h1></div>
        <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body"><i class="fa fa-envelope-o fa-5x pull-left"></i>
This page will generate a csv file of your customers data so that you may import it into constant contact or other email marketing services.                          </div>
                      </div>
                  </div>   
              </div>    
<h3><b>Note:</b> This will export all customers.</h3>



<table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top"><table class="table table-hover table-condensed table-responsive">
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent"><?php
  echo TABLE_HEADING_EXPORT_NAME;
?></td>
                      <td class="dataTableHeadingContent"><?php
  echo TABLE_HEADING_ACTION;
?>&nbsp;</td>
                    </tr>
                    <?php
  $d = dir(DIR_FS_MAIL_DUMPS);
  $all_count = 0;
  while ($mdump = $d->read()) {
      if ($mdump != "." && $mdump != "..") {
          $all_count++;
          if ($mdump == $_GET["dID"]) {
              echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONSTANT_CONTACT, 'page=' . $_GET['page'] . '&dID=' . $mdump . '&action=donwload') . '\'">' . "\n";
          } //if ($mdump == $_GET["dID"])
          else {
              echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONSTANT_CONTACT, 'page=' . $_GET['page'] . '&dID=' . $mdump) . '\'">' . "\n";
          } //else
?>
                    <td class="dataTableContent"><?php
          echo "<b>" . $mdump . "</b>, " . date(PHP_DATE_TIME_FORMAT, filectime(DIR_FS_MAIL_DUMPS . $mdump)) . ", " . sprintf(TEXT_FILE_SIZE, filesize(DIR_FS_MAIL_DUMPS . $mdump));
?></td>
                      <td class="dataTableContent"><?php
          if ($mdump == $_GET["dID"]) {
              echo '<i class="fa fa-long-arrow-right"></i>';
          } //if ($mdump == $_GET["dID"])
          else {
              echo '<a href="' . tep_href_link(FILENAME_CONSTANT_CONTACT, 'page=' . $_GET['page'] . '&dID=' . $mdump) . '"><i class="fa fa-hand-o-up"></i></a>';
          } //else
?>
                        &nbsp;</td>
                    </tr>
                    <?php
      } //if ($mdump != "s_46" && $mdump != "s_47")
  } //while ($mdump = $d->read())
  $d->close();
?>
                    <tr>
                      <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                          <tr>
                            <td class="smallText" align="right"><?php
  echo sprintf(TEXT_DUMPS_TOTALLY, $all_count);
?></td>
                          </tr>
                          <?php
  if (empty($action)) {
?>
                          <tr>
                            <td align="right"><?php
      echo '<a class="btn btn-default" href="' . tep_href_link(FILENAME_CONSTANT_CONTACT, 'page=' . $_GET['page'] . '&cID=' . $_GET["dID"] . '&action=new') . '">' . TEXT_NEW_DUMP . '</a>';
?></td>
                          </tr>
                          <?php
  } //if (empty($action))
?>
                        </table></td>
                    </tr>
                  </table></td>
                <?php
  $heading = array();
  $contents = array();
  switch ($action) {
      case 'new':
          $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_EXPORT . '</b>');
          $contents = array('form' => tep_draw_form('constant_contact', FILENAME_CONSTANT_CONTACT, 'page=' . $_GET['page'] . (isset($_GET['dID']) ? '&dID=' . $_GET['dID'] : '') . '&action=save'));
          $contents[] = array('text' => TEXT_INFO_INSERT_INTRO .'');
		  $contents[] = array('text' =>'<br><br><br><br>');
          $contents[] = array('text' => TEXT_INFO_CUSTOMER_FIRSTNAME . ' ' . tep_draw_checkbox_field('c_name'));
		  $contents[] = array('text' => '<br><br>');
          $contents[] = array('text' => TEXT_INFO_CUSTOMER_LASTNAME . ' ' . tep_draw_checkbox_field('c_lastname'));
		    $contents[] = array('text' =>'<br><br>');
          $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a class="btn btn-default" href="' . tep_href_link(FILENAME_CONSTANT_CONTACT, 'page=' . $_GET['page'] . '&dID=' . $_GET['dID']) . '">Cancel</a>');
          break;
      case 'delete':
          $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_DUMP . '</b>');
          $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
          $contents[] = array('text' => '<br><b>' . $_GET['dID'] . '</b>');
          $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CONSTANT_CONTACT, 'page=' . $_GET['page'] . '&dID=' . $_GET['dID'] . '&action=deleteconfirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a class="btn btn-default" href="' . tep_href_link(FILENAME_CONSTANT_CONTACT, 'page=' . $_GET['page'] . '&dID=' . $_GET['dID']) . '">Cancel</a>');
          break;
      default:
          if (tep_not_null($_GET["dID"])) {
              $heading[] = array('text' => '<b>' . $_GET["dID"] . '</b>');
              $contents[] = array('align' => 'center', 'text' => '<a class="btn btn-default" href="' . tep_href_link(FILENAME_CONSTANT_CONTACT, 'page=' . $_GET['page'] . '&dID=' . $_GET["dID"] . '&action=download') . '"><b>' . TEXT_DOWNLOAD . '</b></a>');
              $contents[] = array('align' => 'center', 'text' => '<a class="btn btn-default" href="' . tep_href_link(FILENAME_CONSTANT_CONTACT, 'page=' . $_GET['page'] . '&dID=' . $_GET["dID"] . '&action=delete') . '"><b>' . TEXT_DELETE . '</b></a>');
          } //if (tep_not_null($_GET["dID"]))
          break;
  } //switch ($action)
  if ((tep_not_null($heading)) && (tep_not_null($contents))) {
      echo '            <td width="220px" valign="top">' . "\n";
      $box = new box;
      echo $box->infoBox($heading, $contents);
      echo '            </td>' . "\n";
  } //if ((tep_not_null($heading)) && (tep_not_null($contents)))
?>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php
  require(DIR_WS_INCLUDES . 'footer.php');
?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
