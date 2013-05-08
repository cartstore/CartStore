<?php
  require('includes/application_top.php');

  require(DIR_WS_INCLUDES . 'header.php');

  require(DIR_WS_INCLUDES . 'column_left.php');
?>

<!-- body_text //-->

<table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td> <div class="bluebg"><?php
  include(DIR_WS_MODULES . 'homecats.php');
?></div>

 

  <div class="clear"></div>
<br>

  <div id="moduleList"><?php
   include (DIR_WS_MODULES . 'new_products.php');
?></div> 



</td>

  </tr>

</table>



<!-- body_text_eof //-->



<?php
  require(DIR_WS_INCLUDES . 'column_right.php');

  require(DIR_WS_INCLUDES . 'footer.php');

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>