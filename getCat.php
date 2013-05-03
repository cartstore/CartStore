<?php ob_start();

error_reporting(0);
require_once('includes/application_top.php');
$mID=$_GET['mID'];
if($mID)
{
$categories_query = tep_db_query("SELECT distinct cd.categories_name, cd.categories_id FROM `products` p, categories_description cd, products_to_categories pc WHERE cd.categories_id = pc.categories_id AND p.products_id = pc.products_id AND p.manufacturers_id =$mID");

  if ($number_of_rows = tep_db_num_rows($categories_query)) {
?>
          <!-- manufacturers //-->
          <?php
    $info_box_contents = array();
    $info_box_contents[] = array('text' => BOX_HEADING_MANUFACTURERS);

   // new infoBoxHeading($info_box_contents, false, false);

    
// Display a drop-down
      $categories_array = array();
    
        $categories_array[] = array('id' => '', 'text' => 'Select Category');
   
      while ($categories = tep_db_fetch_array($categories_query)) {
	 
        $categories_name = ((strlen($categories['categories_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($categories['categories_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $categories['categories_name']);
        $categories_array[] = array('id' => $categories['categories_id'],
                                       'text' => $categories_name);
      }

      $info_box_contents = array();
      $info_box_contents[] = array('text' => tep_draw_pull_down_menu('cPath', $categories_array, (isset($_GET['cPath']) ? $_GET['cPath'] : ''), 'onchange="this.form.submit();"') . tep_hide_session_id());
    }else
	{
	$info_box_contents = array();
      $info_box_contents[] = array('text' => 'No matches found');
	}
new infoBox($info_box_contents);
}
?>