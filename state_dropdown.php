<?php
// GNU General Public License Compatible
require('includes/application_top.php');
$country = $_GET['country'];
$zones_array = array();    
$zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
while ($zones_values = tep_db_fetch_array($zones_query)) {
  $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
}
header('Content-type: text/html; charset='.CHARSET);
if ( tep_db_num_rows($zones_query) ) {
  echo tep_draw_pull_down_menu('state', $zones_array,'', '');
} else {
  echo 'Country Has No States/ Country ID '.$country;
}
?>