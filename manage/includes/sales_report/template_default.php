<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
  <title><?php echo TITLE; ?></title>
  <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF"><!-- header //-->
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top">
      <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">      <!-- left_navigation //-->
<?php
      require(DIR_WS_INCLUDES . 'column_left.php');
?>
      <!-- left_navigation_eof //-->
      </table>
    </td>
<!-- body_text //-->
    <td width="100%" valign="top">
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td colspan=2>
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo  SR_HEADING_TITLE; ?></td>
                <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.png', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <form action="" method="get">
              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="left" rowspan="2" class="menuBoxHeading">
                    <input type="radio" name="report" value="1" <?php if ($srView == 1) echo "checked"; ?>><?php echo SR_REPORT_TYPE_YEARLY; ?><br>
                    <input type="radio" name="report" value="2" <?php if ($srView == 2) echo "checked"; ?>><?php echo SR_REPORT_TYPE_MONTHLY; ?><br>
                    <input type="radio" name="report" value="3" <?php if ($srView == 3) echo "checked"; ?>><?php echo SR_REPORT_TYPE_WEEKLY; ?><br>
                    <input type="radio" name="report" value="4" <?php if ($srView == 4) echo "checked"; ?>><?php echo SR_REPORT_TYPE_DAILY; ?><br>
                  </td>
                  <td class="menuBoxHeading">
<?php echo SR_REPORT_START_DATE; ?><br>
                    <select name="startD" size="1">
<?php
      if ($startDate) {
        $j = date("j", $startDate);
      } else {
        $j = 1;
      }
      for ($i = 1; $i < 32; $i++) {
?>
                        <option<?php if ($j == $i) echo " selected"; ?>><?php echo $i; ?></option>
<?php
      }
?>
                    </select>
                    <select name="startM" size="1">
<?php
      if ($startDate) {
        $m = date("n", $startDate);
      } else {
        $m = 1;
      }
      for ($i = 1; $i < 13; $i++) {
?>
                      <option<?php if ($m == $i) echo " selected"; ?> value="<?php echo $i; ?>"><?php echo strftime("%B", mktime(0, 0, 0, $i, 1)); ?></option>
<?php
      }
?>
                    </select>
                    <select name="startY" size="1">
<?php
      if ($startDate) {
        $y = date("Y") - date("Y", $startDate);
      } else {
        $y = 0;
      }
      for ($i = 10; $i >= 0; $i--) {
?>
                      <option<?php if ($y == $i) echo " selected"; ?>><?php echo date("Y") - $i; ?></option>
<?php
    }
?>
                    </select>
                  </td>
                  <td align="left" class="menuBoxHeading">
                    <?php echo SR_REPORT_DETAIL; ?><br>
                    <select name="detail" size="1">
                      <option value="0"<?php if ($srDetail == 0) echo " selected"; ?>><?php echo  SR_DET_HEAD_ONLY; ?></option>
                      <option value="1"<?php if ($srDetail == 1) echo " selected"; ?>><?php echo  SR_DET_DETAIL; ?></option>
                      <option value="2"<?php if ($srDetail == 2) echo " selected"; ?>><?php echo  SR_DET_DETAIL_ONLY; ?></option>
                    </select>
                  </td>
                  <td align="left" class="menuBoxHeading">
                    <?php echo SR_REPORT_STATUS_FILTER; ?><br>
                    <select name="status" size="1">
                      <option value="0"><?php echo SR_REPORT_ALL; ?></option>
<?php
                        foreach ($sr->status as $value) {
?>
                      <option value="<?php echo $value["orders_status_id"]?>"<?php if ($srStatus == $value["orders_status_id"]) echo " selected"; ?>><?php echo $value["orders_status_name"] ; ?></option>
<?php
                         }
?>
                    </select><br>
                  </td>
                  <td align="left" class="menuBoxHeading">
                    <?php echo SR_REPORT_EXP; ?><br>
                    <select name="export" size="1">
                      <option value="0" selected><?php echo  SR_EXP_NORMAL; ?></option>
                      <option value="1"><?php echo  SR_EXP_CSV; ?></option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td class="menuBoxHeading">
<?php echo SR_REPORT_END_DATE; ?><br>
                    <select name="endD" size="1">
<?php
    if ($endDate) {
      $j = date("j", $endDate - 60* 60 * 24);
    } else {
      $j = date("j");
    }
    for ($i = 1; $i < 32; $i++) {
?>
                      <option<?php if ($j == $i) echo " selected"; ?>><?php echo $i; ?></option>
<?php
    }
?>
                    </select>
                    <select name="endM" size="1">
<?php
    if ($endDate) {
      $m = date("n", $endDate - 60* 60 * 24);
    } else {
      $m = date("n");
    }
    for ($i = 1; $i < 13; $i++) {
?>
                      <option<?php if ($m == $i) echo " selected"; ?> value="<?php echo $i; ?>"><?php echo strftime("%B", mktime(0, 0, 0, $i, 1)); ?></option>
<?php
    }
?>
                    </select>
                    <select name="endY" size="1">
<?php
    if ($endDate) {
      $y = date("Y") - date("Y", $endDate - 60* 60 * 24);
    } else {
      $y = 0;
    }
    for ($i = 10; $i >= 0; $i--) {
?>
                      <option<?php if ($y == $i) echo " selected"; ?>><?php echo
date("Y") - $i; ?></option><?php
    }
?>
                    </select>
                  </td>
                  <td align="left" class="menuBoxHeading">
                    <?php echo SR_REPORT_MAX; ?><br>
                    <select name="max" size="1">
                      <option value="0"><?php echo SR_REPORT_ALL; ?></option>
                      <option<?php if ($srMax == 1) echo " selected"; ?>>1</option>
                      <option<?php if ($srMax == 3) echo " selected"; ?>>3</option>
                      <option<?php if ($srMax == 5) echo " selected"; ?>>5</option>
                      <option<?php if ($srMax == 10) echo " selected"; ?>>10</option>
                      <option<?php if ($srMax == 25) echo " selected"; ?>>25</option>
                      <option<?php if ($srMax == 50) echo " selected"; ?>>50</option>
                    </select>
                  </td>
                  <td align="left" class="menuBoxHeading">
                    <?php echo SR_REPORT_COMP_FILTER; ?><br>
                    <select name="compare" size="1">
                      <option value="0" <?php if ($srCompare == SR_COMPARE_NO) echo "selected"; ?>><?php echo SR_REPORT_COMP_NO; ?></option>
                      <option value="1" <?php if ($srCompare == SR_COMPARE_DAY) echo "selected"; ?>><?php echo SR_REPORT_COMP_DAY; ?></option>
                      <option value="2" <?php if ($srCompare == SR_COMPARE_MONTH) echo "selected"; ?>><?php echo SR_REPORT_COMP_MONTH; ?></option>
                      <option value="3" <?php if ($srCompare == SR_COMPARE_YEAR) echo "selected"; ?>><?php echo SR_REPORT_COMP_YEAR; ?></option>
                    </select><br>
                  </td>
                  <td align="left" class="menuBoxHeading">
                    <?php echo SR_REPORT_SORT; ?><br>
                    <select name="sort" size="1">
                      <option value="0"<?php if ($srSort == 0) echo " selected"; ?>><?php echo  SR_SORT_VAL0; ?></option>
                      <option value="1"<?php if ($srSort == 1) echo " selected"; ?>><?php echo  SR_SORT_VAL1; ?></option>
                      <option value="2"<?php if ($srSort == 2) echo " selected"; ?>><?php echo  SR_SORT_VAL2; ?></option>
                      <option value="3"<?php if ($srSort == 3) echo " selected"; ?>><?php echo  SR_SORT_VAL3; ?></option>
                      <option value="4"<?php if ($srSort == 4) echo " selected"; ?>><?php echo  SR_SORT_VAL4; ?></option>
                      <option value="5"<?php if ($srSort == 5) echo " selected"; ?>><?php echo  SR_SORT_VAL5; ?></option>
                      <option value="6"<?php if ($srSort == 6) echo " selected"; ?>><?php echo  SR_SORT_VAL6; ?></option>
                    </select><br>
                  </td>
                </tr>
                <tr>
                  <td colspan="5" class="menuBoxHeading" align="right">
                    <input type="submit" value="<?php echo SR_REPORT_SEND; ?>">
                  </td>
                </tr>
              </table>
            </form>
          </td>
        </tr>
        <tr>
          <td width=100% valign=top>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td valign="top">
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent" align="right"><?php echo  SR_TABLE_HEADING_DATE; ?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo  SR_TABLE_HEADING_ORDERS;?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo  SR_TABLE_HEADING_ITEMS; ?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo  SR_TABLE_HEADING_REVENUE;?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo  SR_TABLE_HEADING_SHIPPING;?></td>
                    </tr>
<?php
$sum = 0;
while ($sr->hasNext()) {
  $info = $sr->next();
  $last = sizeof($info) - 1;
?>
                    <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">
<?php
    switch ($srView) {
      case '3':
?>
                      <td class="dataTableContent" align="right"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td>
<?php
        break;
      case '4':
?>
                      <td class="dataTableContent" align="right"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr->showDate)); ?></td>
<?php
        break;
      default;
?>
                      <td class="dataTableContent" align="right"><?php echo tep_date_short(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td>
<?php
    }
?>
                      <td class="dataTableContent" align="right"><?php echo $info[0]['order']; ?></td>
                      <td class="dataTableContent" align="right"><?php echo $info[$last - 1]['totitem']; ?></td>
                      <td class="dataTableContent" align="right"><?php echo $currencies->format($info[$last - 1]['totsum']);?></td>
                      <td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['shipping']);?></td>
                    </tr>
<?php
  if ($srDetail) {
    for ($i = 0; $i < $last; $i++) {
      if ($srMax == 0 or $i < $srMax) {
?>
                    <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">
                    <td class="dataTableContent">&nbsp;</td>
                    <td class="dataTableContent" align="left"><a href="<?php echo tep_catalog_href_link("product_info.php?products_id=" . $info[$i]['pid']) ?>" target="_blank"><?php echo $info[$i]['pname']; ?></a>
<?php
  if (is_array($info[$i]['attr'])) {
    $attr_info = $info[$i]['attr'];
    foreach ($attr_info as $attr) {
      echo '<div style="font-style:italic;">&nbsp;' . $attr['quant'] . 'x ' ;
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
        echo $currencies->format($price). ')';
      }
      echo '</div>';
    }
  }
?>                    </td>
                    <td class="dataTableContent" align="right"><?php echo $info[$i]['pquant']; ?></td>
<?php
          if ($srDetail == 2) {?>
                    <td class="dataTableContent" align="right"><?php echo $currencies->format($info[$i]['psum']); ?></td>
<?php
          } else { ?>
                    <td class="dataTableContent">&nbsp;</td>
<?php
          }
?>
                    <td class="dataTableContent">&nbsp;</td>
                  </tr>
<?php
      }
    }
  }
}

if ($srCompare > SR_COMPARE_NO) {
?>
                    <tr>
                      <td colspan="5" class="dataTableContent"><?php echo SR_TEXT_COMPARE; ?></td>
                    </tr>
<?php
  $sum = 0;
  while ($sr2->hasNext()) {
    $info = $sr2->next();
    $last = sizeof($info) - 1;
  ?>
                      <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">
  <?php
      switch ($srView) {
        case '3':
  ?>
                        <td class="dataTableContent" align="right"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr2->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr2->showDateEnd)); ?></td>
  <?php
          break;
        case '4':
  ?>
                        <td class="dataTableContent" align="right"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr2->showDate)); ?></td>
  <?php
          break;
        default;
  ?>
                        <td class="dataTableContent" align="right"><?php echo tep_date_short(date("Y-m-d\ H:i:s", $sr2->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr2->showDateEnd)); ?></td>
  <?php
      }
  ?>
                        <td class="dataTableContent" align="right"><?php echo $info[0]['order']; ?></td>
                        <td class="dataTableContent" align="right"><?php echo $info[$last - 1]['totitem']; ?></td>
                        <td class="dataTableContent" align="right"><?php echo $currencies->format($info[$last - 1]['totsum']);?></td>
                        <td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['shipping']);?></td>
                      </tr>
  <?php
    if ($srDetail) {
      for ($i = 0; $i < $last; $i++) {
        if ($srMax == 0 or $i < $srMax) {
  ?>
                      <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">
                      <td class="dataTableContent">&nbsp;</td>
                      <td class="dataTableContent" align="left"><a href="<?php echo tep_catalog_href_link("product_info.php?products_id=" . $info[$i]['pid']) ?>" target="_blank"><?php echo $info[$i]['pname']; ?></a>
  <?php
    if (is_array($info[$i]['attr'])) {
      $attr_info = $info[$i]['attr'];
      foreach ($attr_info as $attr) {
        echo '<div style="font-style:italic;">&nbsp;' . $attr['quant'] . 'x ' ;
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
          echo $currencies->format($price). ')';
        }
        echo '</div>';
      }
    }
  ?>                    </td>
                      <td class="dataTableContent" align="right"><?php echo $info[$i]['pquant']; ?></td>
  <?php
            if ($srDetail == 2) {?>
                      <td class="dataTableContent" align="right"><?php echo $currencies->format($info[$i]['psum']); ?></td>
  <?php
            } else { ?>
                      <td class="dataTableContent">&nbsp;</td>
  <?php
            }
  ?>
                      <td class="dataTableContent">&nbsp;</td>
                    </tr>
  <?php
        }
      }
    }
  }
}
?>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php
  require(DIR_WS_INCLUDES . 'footer.php');
?>
<!-- footer_eof //-->
</body>
</html>
<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
