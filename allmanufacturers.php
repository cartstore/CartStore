<?php
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_MANUFACTURERS);
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_MANUFACTURERS));
  define('COLUMN_LISTING', 'false');
  require(DIR_WS_INCLUDES . 'header.php');
  require(DIR_WS_INCLUDES . 'column_left.php');
?>
        
    
    <!-- body_text //-->
 
           <ul data-role="listview" data-theme="b" data-divider-theme="a">
           	
           	<li data-role="list-divider" role="heading" class="ui-li ui-li-divider ui-bar-a">
				All Manufacturers
                
					
				
            </li>
            
            <!-- all manufacturers begin //-->
            
            <?php
  if (COLUMN_LISTING == 'true') {
?>
            <?php
      $manufacturers_query = tep_db_query("select manufacturers_name, manufacturers_id, manufacturers_image from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
      if (tep_db_num_rows($manufacturers_query) >= '1') {
          while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
              echo '<li><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers['manufacturers_id'] . '=' . $manufacturers['manufacturers_name']) . '"><span class="manufacturers_name">' . $manufacturers['manufacturers_name'] . ' </span> ' . tep_image(DIR_WS_IMAGES . $manufacturers['manufacturers_image'], $manufacturers['manufacturers_name']) . "</a></li>\n";
          }
      }
?>
          </ul>
          <?php
      } else
      {
          $row = 0;
          $manufacturers_query = tep_db_query("select manufacturers_name, manufacturers_id, manufacturers_image from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
          while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
              $row++;
              echo '';
              echo '<li><a  data-transition="slide"  href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers['manufacturers_id'] . '=' . $manufacturers['manufacturers_name'], 'NONSSL', false) . '"><h1 class="ui-li-heading">' . $manufacturers['manufacturers_name'] . '  </h1>';
              if ($manufacturers['manufacturers_image']) {
                  echo tep_image(DIR_WS_IMAGES . $manufacturers['manufacturers_image'], $manufacturers['manufacturers_name']);
              }
              echo "</a></li>\n";
              echo '';
              if ((($row / 4) == floor($row / 4))) {
?>
          <?php
              }
          }
      }
?>
          
          <!-- all manufacturers end //-->
          
          <div class="clear"></div>
          <?php
      echo '<a class="button" href="' . tep_href_link(FILENAME_DEFAULT) . '">' . IMAGE_BUTTON_CONTINUE . '</a>';
?>
    
    <!-- body_text_eof //-->
  
        
        <?php
      require(DIR_WS_INCLUDES . 'column_right.php');
      require(DIR_WS_INCLUDES . 'footer.php');
      require(DIR_WS_INCLUDES . 'application_bottom.php');
?>