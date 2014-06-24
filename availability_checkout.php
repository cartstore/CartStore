<?php
  require(DIR_WS_INCLUDES . 'functions/availability.php');
  define("FULLY_BLOCK_COLOR", "#cccccc");
  define("NOT_AVAILABLE_COLOR", "#CCCCCC");
  define("FREE_COLOR", "#cccccc");
  define("COST1_COLOR", "#cccccc");
  define("COST3_COLOR", "#cccccc");
  define("COST5_COLOR", "#cccccc");
  if (isset($_COOKIE['DelvTimeCookie'])) {
      $selected_time_slot = $_COOKIE['DelvTimeCookie'];
  }
?>

<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td width="87%" align="left" class="main"><b>Delivery Preferences: Availability

      Calendar</b></td>
    <td width="13%" align="center" class="main">&nbsp;</td>
  </tr>
</table>
</td>
</tr>
<tr>
  <td colspan="5"><table width="100%" height="94%" border="0" cellpadding="2" cellspacing="1" class="infoBox">
      <tr class="infoBoxContents">
        <td><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="85%" valign="top"><table id="avalibilitycal" width="100%" bordercolor="#CCCCCC" style="border:thin" border="1">
                  <tr>
                    <td>&nbsp;</td>
                    <?php
  $res_slots = tep_get_time_slots();
  while ($row_slots = tep_db_fetch_array($res_slots)) {
      $slt_temp = $row_slots['slot'];
      $slot_mod = explode("-", $slt_temp);
?>
                    <td  align="center" class="main" width="13px" nowrap="nowrap"><?php
      echo $slot_mod[0] . "-";
?><br>
                      <?php
      echo $slot_mod[1];
?></td>
                    <?php
  }
?>
                  </tr>
                  <?php
  for ($delt = 0; $delt < 10; $delt++) {
      $timestamps = strtotime("+$delt day");
      $weekday = date('l', $timestamps);
      $wkdayid = tep_get_dayid($weekday);
?>
                  <tr>
                    <td><table width="100%" border="0">
                        <tr>
                          <td  class="main"><b><?php
      echo date('l', $timestamps);
?></b></td>
                          <td align="right"  class="main"><?php
      echo date('j F', $timestamps);
?></td>
                        </tr>
                      </table></td>
                    <?php
      $res_slots_inner_loop = tep_get_time_slots();
      while ($row_slots_inner_loop = tep_db_fetch_array($res_slots_inner_loop)) {
          $sp_det = tep_get_special_time(date('Y-m-d', $timestamps), $row_slots_inner_loop['slotid']);
          $booked_num = tep_get_total_count(date('Y-m-d', $timestamps), $row_slots_inner_loop['slotid']);
          if ($sp_det != 0) {

              if ($sp_det['em_max_limit'] == 0) {

?>
                    <td align="center" bgcolor="<?php
                  echo FULLY_BLOCK_COLOR;
?>">&nbsp;</td>
                    <?php
              } elseif ($booked_num == $sp_det['em_max_limit']) {
?>
                    <td align="center" bgcolor="<?php
                  echo NOT_AVAILABLE_COLOR;
?>">&nbsp;</td>
                    <?php
              } else {
                  switch ($sp_det['em_cost']) {
                      case 0:
                          $bgcolor = FREE_COLOR;
                          break;
                      case 1:
                          $bgcolor = COST1_COLOR;
                          break;
                      case 3:
                          $bgcolor = COST3_COLOR;
                          break;
                      case 5:
                          $bgcolor = COST5_COLOR;
                          break;
                  }
?>
                    <td align="center" bgcolor="<?php
                  echo $bgcolor;
?>"><input type="radio" name="select_delv_time" value="<?php
                  echo date('Y-m-d', $timestamps) . '~' . $sp_det['slotid'] . '~' . $sp_det['em_cost'];
?>" <?php
                  if ($selected_time_slot == date('Y-m-d', $timestamps) . '~' . $sp_det['slotid'] . '~' . $sp_det['em_cost'])
                      echo "checked";
?> onclick="setCheckedValue(document.forms['checkout_address'].elements['shipping'], 'dly3datetime_dly3datetime');"></td>
                    <?php
              }
          } else {

              $default_det = tep_get_default_time($wkdayid, $row_slots_inner_loop['slotid']);
              if ($default_det['max_limit'] == 0) {

?>
                    <td align="center" bgcolor="<?php
                  echo FULLY_BLOCK_COLOR;
?>">&nbsp;</td>
                    <?php
              } elseif ($booked_num == $default_det['max_limit']) {
?>
                    <td align="center" bgcolor="<?php
                  echo NOT_AVAILABLE_COLOR;
?>">&nbsp;</td>
                    <?php
              } else {
                  switch ($default_det['cost']) {
                      case 0:
                          $bgcolor = FREE_COLOR;
                          break;
                      case 1:
                          $bgcolor = COST1_COLOR;
                          break;
                      case 3:
                          $bgcolor = COST3_COLOR;
                          break;
                      case 5:
                          $bgcolor = COST5_COLOR;
                          break;
                  }
?>
                    <td align="center" bgcolor="<?php
                  echo $bgcolor;
?>"><input type="radio" name="select_delv_time" value="<?php
                  echo date('Y-m-d', $timestamps) . '~' . $default_det['slotid'] . '~' . $default_det['cost'];
?>" <?php
                  if ($selected_time_slot == date('Y-m-d', $timestamps) . '~' . $default_det['slotid'] . '~' . $default_det['cost'])
                      echo "checked";
?> onclick="setCheckedValue(document.forms['checkout_address'].elements['shipping'], 'dly3datetime_dly3datetime');"></td>
                    <?php
              }
          }

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
    <script language="javascript">



   function setCheckedValue(radioObj, newValue) {

  if(!radioObj)

    return;

  var radioLength = radioObj.length;

  if(radioLength == undefined) {

    radioObj.checked = (radioObj.value == newValue.toString());

    return;

  }

  for(var i = 0; i < radioLength; i++) {

    radioObj[i].checked = false;

    if(radioObj[i].value == newValue.toString()) {

      radioObj[i].checked = true;

    }

  }

}



   </script>