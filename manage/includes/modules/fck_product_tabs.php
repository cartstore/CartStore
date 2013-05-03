<style type="text/css">
@import "scr.css";
</style>
  </head>

  <body>
<script type="text/javascript" src="scr.js">
</script>

    <table cellpadding="0" cellspacing="0" width="100%" style="BORDER:none;background:none;">
      <tr>
        <td>
        </td>
      </tr>

      <!-- <tr>
        <td><?php // echo tep_draw_separator('pixel_trans.png', '100%', '5'); ?></td>
      </tr>
      <tr>
        <td>
          <hr width="100%" color="#000000">
        </td>
      </tr>
 -->
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png','100%', '10'); ?></td>
      </tr>

      <tr>
        <td>
			<table class="tabline" cellpadding="0" cellspacing="0" align="left" width="100%">
				<tr>
					<td>
					<ul id="mainnav1">
					<li><a href="#DESC"><?PHP ECHO TEXT_TAB_DESCRIPTION;
					?></a></li>
					<?php
						   if ($product_info['products_spec'] > '') {
					?>
		
					<li><a href="#SPEC"><?PHP ECHO TEXT_TAB_SPEC;
					?></a></li>
					<?php      }
					?><?php
						   if ($product_info['products_musthave'] > '') {
					?>
		
					<li><a href="#MUSTHAVE"><?PHP ECHO TEXT_TAB_MUSTHAVE;
					?></a></li>
					<?php      }
					?><?php
						   if ($product_info['products_extraimage'] > '')
					{
					?>
		
					<li><a href="#EXTRAIMAGE"><?PHP ECHO
					TEXT_TAB_EXTRAIMAGE; ?></a></li>
					<?php      }
					?><?php
						   if ($product_info['products_manual'] > '') {
					?>
		
					<li><a href="#MANUAL"><?PHP ECHO TEXT_TAB_MANUAL;
					?></a></li>
					<?php      }
					?><?php
						   if ($product_info['products_extra1'] > '') {
					?>
		
					<li><a href="#EXTRA1"><?PHP ECHO TEXT_TAB_EXTRA1;
					?></a></li>
					<?php      }
					?><?php
						   if ($product_info['products_moreinfo'] > '') {
					?>
		
					<li><a href="#MOREINFO"><?PHP ECHO TEXT_TAB_MOREINFO;
					?></a></li>
					<?php      }
					?>
				  </ul>
					</td>
				</tr>
			</table>
          
		  
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png','100%', '10'); ?></td>
      </tr>

      <tr>
        <td>
          <div id="DESC" class="tabcontent">
            <div style="font-size: 12px;font-weight: bold; 
            border-bottom: 1px dashed #999999;">
              <?php echo TEXT_TAB_DH; ?>
            </div>
            <br>
             <?php echo
            stripslashes($product_info['products_description']);
            ?>
          </div>

           <?php
                   if ($product_info['products_spec'] > '') {
            ?>
          <div id="SPEC" class="tabcontent">
            <div style="font-size: 12px;font-weight: bold; 
            border-bottom: 1px dashed #999999;">
              <?PHP echo TEXT_TAB_SH; ?>
            </div>
            <br>
             <?php echo
            stripslashes($product_info['products_spec']); ?>
          </div>
          <?php
        }
        ?>
        
        <?php
                   if ($product_info['products_musthave'] > '') {
            ?>          
          <div id="MUSTHAVE" class="tabcontent">
            <div style="font-size: 12px;font-weight: bold; 
            border-bottom: 1px dashed #999999;">
              <?PHP echo TEXT_TAB_MUSTHAVE; ?>
            </div>
            <br>
             <?php echo
            stripslashes($product_info['products_musthave']); ?>
          </div>
          <?php
        }
        ?>
        
        <?php
                   if ($product_info['products_extraimage'] > '') {
            ?>          
          <div id="EXTRAIMAGE" class="tabcontent">
            <div style="font-size: 12px;font-weight: bold; 
            border-bottom: 1px dashed #999999;">
              <?PHP echo TEXT_TAB_EXTRAIMAGE; ?>
            </div>
            <br>
             <?php echo
            stripslashes($product_info['products_extraimage']); ?>
          </div>
          <?php
          }
          ?>
          
          <?php
                   if ($product_info['products_manual'] > '') {
            ?>          
          <div id="MANUAL" class="tabcontent">
            <div style="font-size: 12px;font-weight: bold; 
            border-bottom: 1px dashed #999999;">
              <?PHP echo TEXT_TAB_MANUAL; ?>
            </div>
            <br>
             <?php echo
            stripslashes($product_info['products_manual']); ?>
          </div>
          <?php
        }
        ?>
          
          <?php
                   if ($product_info['products_extra1'] > '') {
            ?>
		  <div id="EXTRA1" class="tabcontent">
            <div style="font-size: 12px;font-weight: bold; 
            border-bottom: 1px dashed #999999;">
              <?PHP echo TEXT_TAB_EXTRA1; ?>
            </div>
            <br>
             <?php echo
            stripslashes($product_info['products_extra1']); ?>
          </div>
          <?php
        }
        ?>
        
        <?php
                   if ($product_info['products_moreinfo'] > '') {
            ?>          
          <div id="MOREINFO" class="tabcontent">
            <div style="font-size: 12px;font-weight: bold; 
            border-bottom: 1px dashed #999999;">
              <?PHP echo TEXT_TAB_MOREINFO; ?>
            </div>
            <br>
             <?php echo
            stripslashes($product_info['products_moreinfo']); ?>
          </div>
          <?php
        }
        ?>
        </td>
      </tr>
    </table>
  </body>

