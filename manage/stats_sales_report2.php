<?php
  /*
   $Id: stats_sales_report2.php,v 1.00 2003/03/08 19:02:22 Exp $

   Charly Wilhelm  charly@yoshi.ch

   GNU General Public License Compatible

   Copyright (c) 2008 Adoovo Inc. USA

   possible views (srView):
   1 yearly
   2 monthly
   3 weekly
   4 daily

   possible options (srDetail):
   0 no detail
   1 show details (products)
   2 show details only (products)

   export
   0 normal view
   1 html view without left and right
   2 csv

   sort
   0 no sorting
   1 product description asc
   2 product description desc
   3 #product asc, product descr asc
   4 #product desc, product descr desc
   5 revenue asc, product descr asc
   6 revenue desc, product descr desc

   */
  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  // default detail no detail
  $srDefaultDetail = 0;
  // default view (daily)
  $srDefaultView = 2;
  // default export
  $srDefaultExp = 0;
  // default sort
  $srDefaultSort = 4;

  $srFilter = 0;

  // report views (1: yearly 2: monthly 3: weekly 4: daily)
  $srView = (($_GET['report']) && (tep_not_null($_GET['report']))) ? $_GET['report'] : $srDefaultView;
  if ($srView < 1 || $srView > 4) {
      $srView = $srDefaultView;
  }
  // detail
  $srDetail = (($_GET['detail']) && (tep_not_null($_GET['detail']))) ? $_GET['detail'] : $srDefaultDetail;
  if ($srDetail < 0 || $srDetail > 2) {
      $srDetail = $srDefaultDetail;
  }
  // report views (1: yearly 2: monthly 3: weekly 4: daily)
  $srExp = (($_GET['export']) && (tep_not_null($_GET['export']))) ? $_GET['export'] : $srDefaultExp;
  if ($srExp < 0 || $srExp > 2) {
      $srExp = $srDefaultExp;
  }
  // item_level
  $srMax = (($_GET['max']) && (tep_not_null($_GET['max']))) ? $_GET['max'] : 0;
  if (!is_numeric($srMax)) {
      $srMax = 0;
  }
  // order status
  $srStatus = (($_GET['status']) && (tep_not_null($_GET['status']))) ? $_GET['status'] : 0;
  if (!is_numeric($srStatus)) {
      $srStatus = 0;
  }
  // order vendor
  $srVendor = (($_GET['vendor']) && (tep_not_null($_GET['vendor']))) ? $_GET['vendor'] : 0;
  if (!is_numeric($srVendor)) {
      $srVendor = 0;
  }
  // sort
  $srSort = (($_GET['sort']) && (tep_not_null($_GET['sort']))) ? $_GET['sort'] : $srDefaultSort;
  if ($srSort < 1 || $srSort > 6) {
      $srSort = $srDefaultSort;
  }
  // check start and end Date
  $startDate = "";
  $startDateG = 0;
  if (($_GET['startD']) && (tep_not_null($_GET['startD']))) {
      $sDay = $_GET['startD'];
      $startDateG = 1;
  } else {
      $sDay = 1;
  }
  if (($_GET['startM']) && (tep_not_null($_GET['startM']))) {
      $sMon = $_GET['startM'];
      $startDateG = 1;
  } else {
      $sMon = 1;
  }
  if (($_GET['startY']) && (tep_not_null($_GET['startY']))) {
      $sYear = $_GET['startY'];
      $startDateG = 1;
  } else {
      $sYear = date("Y");
  }
  if ($startDateG) {
      $startDate = mktime(0, 0, 0, $sMon, $sDay, $sYear);
  } else {
      $startDate = mktime(0, 0, 0, date("m"), 1, date("Y"));
  }
  $endDate = "";
  $endDateG = 0;
  if (($_GET['endD']) && (tep_not_null($_GET['endD']))) {
      $eDay = $_GET['endD'];
      $endDateG = 1;
  } else {
      $eDay = 1;
  }
  if (($_GET['endM']) && (tep_not_null($_GET['endM']))) {
      $eMon = $_GET['endM'];
      $endDateG = 1;
  } else {
      $eMon = 1;
  }
  if (($_GET['endY']) && (tep_not_null($_GET['endY']))) {
      $eYear = $_GET['endY'];
      $endDateG = 1;
  } else {
      $eYear = date("Y");
  }
  if ($endDateG) {
      $endDate = mktime(0, 0, 0, $eMon, $eDay + 1, $eYear);
  } else {
      $endDate = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
  }
  require(DIR_WS_CLASSES . 'sales_report2.php');
  $sr = new sales_report($srView, $startDate, $endDate, $srSort, $srStatus, $srVendor, $srFilter);
  $startDate = $sr->startDate;
  $endDate = $sr->endDate;
  if ($srExp < 2) {
      // not for csv export
?>


<?php
      if ($srExp < 1) {
          require(DIR_WS_INCLUDES . 'header.php');
      }
?>


    <?php
      if ($srExp < 1) {
?>
   

       

    <?php
          } // end sr_exp
?>
 
<div class="page-header"><h1>
<?php
          echo HEADING_TITLE;
?>
</h1></div>


        <?php
          if ($srExp < 1) {
?>
   <form action="" method="get">
              <?php
              if (isset($_GET[tep_session_name()])) {
                  echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
              }
?>
              <table class="table table-condensed table-responsive">
                <tr>
                  <td align="left" rowspan="2" class="menuBoxHeading"><input type="radio" name="report" value="1" <?php
              if ($srView == 1)
                  echo "checked";
?>>
                    <?php
              echo REPORT_TYPE_YEARLY;
?><br>
                    <input type="radio" name="report" value="2" <?php
              if ($srView == 2)
                  echo "checked";
?>>
                    <?php
              echo REPORT_TYPE_MONTHLY;
?><br>
                    <input type="radio" name="report" value="3" <?php
              if ($srView == 3)
                  echo "checked";
?>>
                    <?php
              echo REPORT_TYPE_WEEKLY;
?><br>
                    <input type="radio" name="report" value="4" <?php
              if ($srView == 4)
                  echo "checked";
?>>
                    <?php
              echo REPORT_TYPE_DAILY;
?><br></td>
                  <td class="menuBoxHeading"><?php
              echo REPORT_START_DATE;
?><br>
                    <select class="form-control"  name="startD" size="1">
                      <?php
              if ($startDate) {
                  $j = date("j", $startDate);
              } else {
                  $j = 1;
              }
              for ($i = 1; $i < 32; $i++) {
?>
                      <option<?php
                  if ($j == $i)
                      echo " selected";
?>><?php
                  echo $i;
?></option>
                      <?php
              }
?>
                    </select>
                    <select class="form-control"  name="startM" size="1">
                      <?php
              if ($startDate) {
                  $m = date("n", $startDate);
              } else {
                  $m = 1;
              }
              for ($i = 1; $i < 13; $i++) {
?>
                      <option<?php

                  if ($m == $i)
                      echo " selected";
?> value="<?php
                  echo $i;
?>"><?php
                  echo strftime("%B", mktime(0, 0, 0, $i, 1));
?></option>
                      <?php
              }
?>
                    </select>
                    <select class="form-control"  name="startY" size="1">
                      <?php
              if ($startDate) {
                  $y = date("Y") - date("Y", $startDate);
              } else {
                  $y = 0;
              }
              for ($i = 10; $i >= 0; $i--) {
?>
                      <option<?php
                  if ($y == $i)
                      echo " selected";
?>><?php
                  echo date("Y") - $i;
?></option>
                      <?php
              }
?>
                    </select></td>
                  <td rowspan="2" align="left" class="menuBoxHeading"><?php
              echo REPORT_DETAIL;
?><br>
                    <select class="form-control"  name="detail" size="1">
                      <option value="0"<?php
              if ($srDetail == 0)
                  echo "selected";
?>><?php
              echo DET_HEAD_ONLY;
?></option>
                      <option value="1"<?php
              if ($srDetail == 1)
                  echo " selected";
?>><?php
              echo DET_DETAIL;
?></option>
                      <option value="2"<?php
              if ($srDetail == 2)
                  echo " selected";
?>><?php
              echo DET_DETAIL_ONLY;
?></option>
                    </select>
                    <br>
                    <?php
              echo REPORT_MAX;
?><br>
                    <select class="form-control"  name="max" size="1">
                      <option value="0"><?php
              echo REPORT_ALL;
?></option>
                      <option<?php
              if ($srMax == 1)
                  echo " selected";
?>>1</option>
                      <option<?php
              if ($srMax == 3)
                  echo " selected";
?>>3</option>
                      <option<?php
              if ($srMax == 5)
                  echo " selected";
?>>5</option>
                      <option<?php
              if ($srMax == 10)
                  echo " selected";
?>>10</option>
                      <option<?php
              if ($srMax == 25)
                  echo " selected";
?>>25</option>
                      <option<?php
              if ($srMax == 50)
                  echo " selected";
?>>50</option>
                    </select></td>
                  <td rowspan="2" align="left" class="menuBoxHeading"><?php
              echo REPORT_STATUS_FILTER;
?><br>
                    <select class="form-control"  name="status" size="1">
                      <option value="0"><?php
              echo REPORT_ALL;
?></option>
                      <?php
              foreach ($sr->status as $value) {
?>
                      <option value="<?php
                  echo $value["orders_status_id"]
?>"<?php
                  if ($srStatus == $value["orders_status_id"])
                      echo " selected";
?>><?php
                  echo $value["orders_status_name"];
?></option>
                      <?php
              }
?>
                    </select>
                    <br>
                    <?php
              echo REPORT_VENDOR_FILTER;
?><br>
                    <select class="form-control"  name="vendor" size="1">
                      <option value="0"><?php
              echo REPORT_ALL;
?></option>
                      <?php
           if (is_array($sr->vendor)) {
              foreach ($sr->vendor as $value) {
?>
                      <option value="<?php
                  echo $value["vendors_id"]
?>"<?php
                  if ($srVendor == $value["vendors_id"])
                      echo " selected";
?>><?php
                  echo $value["vendors_name"];
?></option>
                      <?php
              }
           }
?>
                    </select></td>
                  <td rowspan="2" align="left" class="menuBoxHeading"><?php
              echo REPORT_EXP;
?><br>
                    <select class="form-control"  name="export" size="1">
                      <option value="0" selected><?php
              echo EXP_NORMAL;
?></option>
                      <option value="1"><?php
              echo EXP_HTML;
?></option>
                      <option value="2"><?php
              echo EXP_CSV;
?></option>
                    </select>
                    <br>
                    <?php
              echo REPORT_SORT;
?><br>
                    <select class="form-control"  name="sort" size="1">
                      <option value="0"<?php
              if ($srSort == 0)
                  echo " selected";
?>><?php
              echo SORT_VAL0;
?></option>
                      <option value="1"<?php
              if ($srSort == 1)
                  echo " selected";
?>><?php
              echo SORT_VAL1;
?></option>
                      <option value="2"<?php
              if ($srSort == 2)
                  echo " selected";
?>><?php
              echo SORT_VAL2;
?></option>
                      <option value="3"<?php
              if ($srSort == 3)
                  echo " selected";
?>><?php
              echo SORT_VAL3;
?></option>
                      <option value="4"<?php
              if ($srSort == 4)
                  echo " selected";
?>><?php
              echo SORT_VAL4;
?></option>
                      <option value="5"<?php
              if ($srSort == 5)
                  echo " selected";
?>><?php
              echo SORT_VAL5;
?></option>
                      <option value="6"<?php
              if ($srSort == 6)
                  echo " selected";
?>><?php
              echo SORT_VAL6;
?></option>
                    </select>
                    <br></td>
                </tr>
                <tr>
                  <td class="menuBoxHeading"><?php
              echo REPORT_END_DATE;
?><br>
                    <select class="form-control"  name="endD" size="1">
                      <?php
              if ($endDate) {
                  $j = date("j", $endDate - 60 * 60 * 24);
              } else {
                  $j = date("j");
              }
              for ($i = 1; $i < 32; $i++) {
?>
                      <option<?php
                  if ($j == $i)
                      echo " selected";
?>><?php
                  echo $i;
?></option>
                      <?php
              }
?>
                    </select>
                    <select class="form-control"  name="endM" size="1">
                      <?php
              if ($endDate) {
                  $m = date("n", $endDate - 60 * 60 * 24);
              } else {
                  $m = date("n");
              }
              for ($i = 1; $i < 13; $i++) {
?>
                      <option<?php
                  if ($m == $i)
                      echo " selected";
?> value="<?php
                  echo $i;
?>"><?php
                  echo strftime("%B", mktime(0, 0, 0, $i, 1));
?></option>
                      <?php
              }
?>
                    </select>
                    <select class="form-control"  name="endY" size="1">
                      <?php
              if ($endDate) {
                  $y = date("Y") - date("Y", $endDate - 60 * 60 * 24);
              } else {
                  $y = 0;
              }
              for ($i = 10; $i >= 0; $i--) {
?>
                      <option<?php
                  if ($y == $i)
                      echo " selected";
?>><?php
                  echo date("Y") - $i;
?></option>
                      <?php
              }
?>
                    </select></td>
                </tr>
                <tr>
                  <td colspan="5" class="menuBoxHeading" align="right"><p><input type="submit" class="btn btn-default" value="<?php
              echo REPORT_SEND;
?>"></p></td>
              </table>
            </form> 
        <?php
              } // end of ($srExp < 1)
?>
      <table class="table table-hover table-condensed table-responsive">
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent"><?php
              echo TABLE_HEADING_DATE;
?></td>
                      <td class="dataTableHeadingContent"><?php
              echo TABLE_HEADING_ORDERS;
?></td>
                      <td class="dataTableHeadingContent"><?php
              echo TABLE_HEADING_ITEMS;
?></td>
                      <td class="dataTableHeadingContent"><?php
              echo TABLE_HEADING_REVENUE;
?></td>
                      <td class="dataTableHeadingContent"><?php
              echo TABLE_HEADING_SHIPPING;
?></td>
                    </tr>
                    <?php
              } // end of if $srExp < 2 csv export
              $sum = 0;
              while ($sr->actDate < $sr->endDate) {
                  $info = $sr->getNext();
                  $last = sizeof($info) - 1;
                  if ($srExp < 2) {
?>
                    <tr class="dataTableRow" onMouseOver="this.className='dataTableRowOver';this.style.cursor='hand'" onMouseOut="this.className='dataTableRow'">
                      <?php
                      switch ($srView) {
                          case '3':
?>
                      <td class="dataTableContent"><?php
                              echo tep_date_long(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd));
?></td>
                      <?php
                              break;
                          case '4':
?>
                      <td class="dataTableContent"><?php
                              echo tep_date_long(date("Y-m-d\ H:i:s", $sr->showDate));
?></td>
                      <?php
                              break;
                              default;
?>
                      <td class="dataTableContent"><?php
                              echo tep_date_short(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd));
?></td>
                      <?php
                          }
?>
                      <td class="dataTableContent"><?php
                          echo $info[0]['order'];
?></td>
                      <td class="dataTableContent"><?php
                          echo $info[$last - 1]['totitem'];
?></td>
                      <td class="dataTableContent"><?php
                          echo $currencies->format($info[$last - 1]['totsum']);
?></td>
                      <td class="dataTableContent"><?php
                          echo $currencies->format($info[0]['shipping']);
?></td>
                    </tr>
                    <?php
                          } else
                          {
                              // csv export
                              echo date(DATE_FORMAT, $sr->showDate) . SR_SEPARATOR1 . date(DATE_FORMAT, $sr->showDateEnd) . SR_SEPARATOR1;
                              echo $info[0]['order'] . SR_SEPARATOR1;
                              echo $info[$last - 1]['totitem'] . SR_SEPARATOR1;
                              echo $currencies->format($info[$last - 1]['totsum']) . SR_SEPARATOR1;
                              echo $currencies->format($info[0]['shipping']) . SR_NEWLINE;
                          }
                          if ($srDetail) {
                              for ($i = 0; $i < $last; $i++) {
                                  if ($srMax == 0 or $i < $srMax) {
                                      if ($srExp < 2) {
?>
                    <tr class="dataTableRow" onMouseOver="this.className='dataTableRowOver';this.style.cursor='hand'" onMouseOut="this.className='dataTableRow'">
                      <td class="dataTableContent">&nbsp;</td>
                      <td class="dataTableContent"><a href="<?php
                                          echo tep_catalog_href_link("product_info.php?products_id=" . $info[$i]['pid'])
?>" target="_blank"><?php
                                          echo $info[$i]['pname'];
?></a>
                        <?php
                                          if (is_array($info[$i]['attr'])) {
                                              $attr_info = $info[$i]['attr'];
                                              foreach ($attr_info as $attr) {
                                                  echo '<div style="font-style:italic;">&nbsp;' . $attr['quant'] . 'x ';
                                                  //  $attr['options'] . ': '
                                                  $flag = 0;
                                                  foreach ($attr['options_values'] as $value) {
                                                      if ($flag > 0) {
                                                          echo "," . $value;
                                                      } else {
                                                          echo $value;
                                                          $flag = 1;
                                                      }
                                                  }
                                                  $price = 0;
                                                  foreach ($attr['price'] as $value) {
                                                      $price += $value;
                                                  }
                                                  if ($price != 0) {
                                                      echo ' (';
                                                      if ($price > 0) {
                                                          echo "+";
                                                      }
                                                      echo $currencies->format($price) . ')';
                                                  }
                                                  echo '</div>';
                                              }
                                          }
?></td>
                      <td class="dataTableContent"><?php
                                          echo $info[$i]['pquant'];
?></td>
                      <?php
                                          if ($srDetail == 2) {
?>
                      <td class="dataTableContent"><?php
                                              echo $currencies->format($info[$i]['psum']);
?></td>
                      <?php
                                              } else
                                              {
?>
                      <td class="dataTableContent">&nbsp;</td>
                      <?php
                                              }
?>
                      <td class="dataTableContent">&nbsp;</td>
                    </tr>
                    <?php
                                              } else
                                              {
                                                  // csv export
                                                  if (is_array($info[$i]['attr'])) {
                                                      $attr_info = $info[$i]['attr'];
                                                      foreach ($attr_info as $attr) {
                                                          echo $info[$i]['pname'] . "(";
                                                          $flag = 0;
                                                          foreach ($attr['options_values'] as $value) {
                                                              if ($flag > 0) {
                                                                  echo ", " . $value;
                                                              } else {
                                                                  echo $value;
                                                                  $flag = 1;
                                                              }
                                                          }
                                                          $price = 0;
                                                          foreach ($attr['price'] as $value) {
                                                              $price += $value;
                                                          }
                                                          if ($price != 0) {
                                                              echo ' (';
                                                              if ($price > 0) {
                                                                  echo "+";
                                                              } else {
                                                                  echo " ";
                                                              }
                                                              echo $currencies->format($price) . ')';
                                                          }
                                                          echo ")" . SR_SEPARATOR2;
                                                          if ($srDetail == 2) {
                                                              echo $attr['quant'] . SR_SEPARATOR2;
                                                              echo $currencies->format($attr['quant'] * ($info[$i]['price'] + $price)) . SR_NEWLINE;
                                                          } else {
                                                              echo $attr['quant'] . SR_NEWLINE;
                                                          }
                                                          $info[$i]['pquant'] = $info[$i]['pquant'] - $attr['quant'];
                                                      }
                                                  }
                                                  if ($info[$i]['pquant'] > 0) {
                                                      echo $info[$i]['pname'] . SR_SEPARATOR2;
                                                      if ($srDetail == 2) {
                                                          echo $info[$i]['pquant'] . SR_SEPARATOR2;
                                                          echo $currencies->format($info[$i]['pquant'] * $info[$i]['price']) . SR_NEWLINE;
                                                      } else {
                                                          echo $info[$i]['pquant'] . SR_NEWLINE;
                                                      }
                                                  }
                                              }
                                          }
                                      }
                                  }
                              }
                              if ($srExp < 2) {
?>
                  </table> 

<!-- footer //-->
<?php
                                  if ($srExp < 1) {
                                      require(DIR_WS_INCLUDES . 'footer.php');
                                  }
?>

<?php
                                  require(DIR_WS_INCLUDES . 'application_bottom.php');
                              }
                              // end if $srExp < 2
?>