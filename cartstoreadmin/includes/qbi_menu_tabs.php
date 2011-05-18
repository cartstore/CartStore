<?php
require(DIR_WS_INCLUDES . 'qbi_menu_items.php');

  echo "<div id=\"nav\"><ul class=\"level1\">\r\n";
  $x=0;
  foreach($menuarray as $menulabel=>$menuurl) {
    $x++;
	((isset($submenuarray[$x]) AND in_array($PHP_SELF,$submenuarray[$x])) OR $PHP_SELF==$menuurl) ? $e=1 : $e=0;
    echo '<li';
    echo ($e==0) ? ' class="inactive"><a href="'.$menuurl.'">' : ' class="active"><a href="'.$menuurl.'" id="current">';
    echo $menulabel."</a>";
	if (isset($submenuarray[$x]) AND $e==1) {
	  echo "<ul class=\"level2\">\r\n";
      foreach($submenuarray[$x] as $submenulabel=>$submenuurl) {
        echo '<li';
        echo ($PHP_SELF!=$submenuurl) ? ' class="subinactive"><a href="'.$submenuurl.'">' : ' class="subactive"><a href="'.$submenuurl.'" id="subcurrent">';
        echo $submenulabel."</a></li>\r\n";
      }
 	  echo '</ul>';
    }
    echo "</li>\r\n";
  }
  echo '</ul></div>';
?><br />