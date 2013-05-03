<?php
  require('includes/application_top.php');
  require(DIR_WS_INCLUDES . 'functions/availability.php');
  define("FULLY_BLOCK_COLOR", "#FF0000");
  define("NOT_AVAILABLE_COLOR", "#CCCCCC");
  define("FREE_COLOR", "#cccccc");
  define("COST1_COLOR", "#FF9900");
  define("COST3_COLOR", "#00CC66");
  define("COST5_COLOR", "#3399FF");
  if ($_REQUEST['act'] == "save_time") {
      $sel_time = $_REQUEST['select_delv_time'];
      setcookie("DelvTimeCookie", $sel_time, time() + 3600, "/");
?>
<script language="JavaScript">
  window.close();
  </script>
<?php
  }
  if (isset($_COOKIE['DelvTimeCookie'])) {
      $selected_time_slot = $_COOKIE['DelvTimeCookie'];
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Availability Calendar</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
function valid()
{
  var i,selOption;
  selOption = -1;
  for (i=document.delvfrm.select_delv_time.length-1; i > -1; i--) {
  if (document.delvfrm.select_delv_time[i].checked) {
  selOption = i;
  }
  }
  if (selOption == -1) {
  alert("Please, select a time slot.");
  return false;
  }

  return true;  
}
</script>
</head>

<body>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td width="87%" align="center" class="main"><b>Availability Calendar</b></td>
    <td width="13%" align="center" class="main">&nbsp;</td>
  </tr>
</table>
</td>
</tr>
<tr>
  <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
      <tr class="infoBoxContents">
        <td><table width="88%" height="262" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="70%" height="262" valign="top"><form action="" name="delvfrm" method="post" onSubmit="return valid();">
                  <input type="hidden" name="act" value="save_time">
                  <table width="506" bordercolor="#CCCCCC" style="border:thin" border="1">
                    <tr>
                      <td width="25%">&nbsp;</td>
                      <?php
  $res_slots = tep_get_time_slots();
  while ($row_slots = tep_db_fetch_array($res_slots)) {
?>
                      <td width="9%"  align="center" class="main"><?php
      echo $row_slots['slot'];
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
                      <td width="25%" class="main"><table width="100%" border="0">
                          <tr>
                            <td><b><?php
      echo date('l', $timestamps);
?></b></td>
                            <td align="right"><b><?php
      echo date('j F', $timestamps);
?></b></td>
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
                      <td  align="center" bgcolor="<?php
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
                      <td  align="center" bgcolor="<?php
                  echo $bgcolor;
?>"><input type="radio" name="select_delv_time" value="<?php
                  echo date('Y-m-d', $timestamps) . '~' . $sp_det['slotid'] . '~' . $sp_det['em_cost'];
?>" <?php
                  if ($selected_time_slot == date('Y-m-d', $timestamps) . '~' . $sp_det['slotid'] . '~' . $sp_det['em_cost'])
                      echo "checked";
?>></td>
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
?>></td>
                      <?php
              }
          }
          
      }
      
?>
                    </tr>
                    <?php
      } 
?>
                    <tr>
                      <td colspan="8" align="center"><input type="submit" class="button" name="sb" value="Select & Close">
                        &nbsp;
                        <input type="button" value="Cancel" onClick="window.close();"></td>
                    </tr>
                  </table>
                </form></td>
              <td width="30%" align="center" valign="top"><table bordercolor="#CCCCCC" style="border:thin" border="1" width="54%">
                  <tr>
                    <td align="center" class="main"><b>Key</b></td>
                  </tr>
                  <tr>
                    <td align="center" bgcolor="FF0000" class="main">Blocked</td>
                  </tr>
                  <tr>
                    <td align="center" bgcolor="3399FF" class="main">5$</td>
                  </tr>
                  <tr>
                    <td align="center" bgcolor="#00CC66" class="main">3$</td>
                  </tr>
                  <tr>
                    <td align="center" bgcolor="#FF9900" class="main">1$</td>
                  </tr>
                  <tr>
                    <td align="center" bgcolor="FFFF66" class="main">Free</td>
                  </tr>
                  <tr>
                    <td align="center" bgcolor="#CCCCCC" class="main">Not Available </td>
                  </tr>
                </table></td>
            </tr>
          </table></td>
      </tr>
    </table>
</body>
</html>