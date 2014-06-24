<?php
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_MANUFACTURERS);
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_MANUFACTURERS));
  define('COLUMN_LISTING', 'false');
  require(DIR_WS_INCLUDES . 'header.php');
  require(DIR_WS_INCLUDES . 'column_left.php');
?>
        
    
    <!-- body_text //-->
 
            	
 			<h1 class="lead">All Brands</h1>
                
				<div class="">	
				
          
             <!-- all manufacturers begin //-->
            
            <?php
  if (COLUMN_LISTING == 'true') {
?>
            <?php
      $manufacturers_query = tep_db_query("select manufacturers_name, manufacturers_id, manufacturers_image from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
      if (tep_db_num_rows($manufacturers_query) >= '1') {
          while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
              echo ' 
    <div class="col-sm-6 col-md-4" style="margin-bottom:10px;">
    <div class="thumbnail">
    
    
       <a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers['manufacturers_id'] . '=' . $manufacturers['manufacturers_name']) . '">
    ' . tep_image(DIR_WS_IMAGES . $manufacturers['manufacturers_image'], $manufacturers['manufacturers_name']) . '</a>
    <div class="caption">
    
   <h3> ' . $manufacturers['manufacturers_name'] . ' </h3> 
    
<p>...</p>
 <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>    
    
   </div>
    </div>
  </div> ';
          }
      }
?>
           <?php
      } else
      {
          $row = 0;
          $manufacturers_query = tep_db_query("select manufacturers_name, manufacturers_id, manufacturers_image from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
          while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
              $row++;
              echo '';
              echo '

               <div class="col-sm-6 col-md-4"  style="margin-bottom:10px;min-height:350px;">
    <div class="thumbnail">';
	
	   if ($manufacturers['manufacturers_image']) {
                  echo ' ';
                  
                 echo  tep_image(DIR_WS_IMAGES . $manufacturers['manufacturers_image'], $manufacturers['manufacturers_name'], 300, SMALL_IMAGE_HEIGHT) ;
				 
				 echo '';
              }
	   
	   
              
              
       echo ' <div class="caption">
              <b>' . $manufacturers['manufacturers_name'] . '  </b>
              
			  <p>....</p>
			  
			  
			   <p><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers['manufacturers_id'] . '=' . $manufacturers['manufacturers_name'], 'NONSSL', false) . '" class="btn btn-primary" role="button">View</a></p>
			  
			         
			  
              
            ';
           
              echo "</div>
    </div>
  </div>        
  ";
              echo '';
              if ((($row / 4) == floor($row / 4))) {
?>
          <?php
              }
          }
      }
?>
          
          <!-- all manufacturers end //-->
           </div>
          <div class="clear"></div>
       <p>   <?php
      echo '<a class="btn btn-info" href="' . tep_href_link(FILENAME_DEFAULT) . '"><i class="fa fa-arrow-circle-o-left"></i> Back</a>';
?>
    </p>
    <!-- body_text_eof //-->
  
        
        <?php
      require(DIR_WS_INCLUDES . 'column_right.php');
      require(DIR_WS_INCLUDES . 'footer.php');
      require(DIR_WS_INCLUDES . 'application_bottom.php');
?>