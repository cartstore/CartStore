<?php
?>
<!-- information //-->

<div><?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_INFORMATION);
  new infoBoxHeading($info_box_contents, false, false);
  $info_box_contents = array();
  $info_box_contents[] = array('text' => '<a href="' . tep_href_link(FILENAME_SHIPPING) . '">' . BOX_INFORMATION_SHIPPING . '</a><br>' . '<a href="' . tep_href_link(FILENAME_PRIVACY) . '">' . BOX_INFORMATION_PRIVACY . '</a><br>' . '<a href="' . tep_href_link(FILENAME_LINKS) . '">' . BOX_INFORMATION_LINKS . '</a><br>' . 
  '<a href="' . tep_href_link(FILENAME_CONDITIONS) . '">' . BOX_INFORMATION_CONDITIONS . '</a><br>' . '<a href="' . tep_href_link(FILENAME_MY_POINTS_HELP) . '">' . BOX_INFORMATION_MY_POINTS_HELP . '</a><br />' .
  '<a href="' . tep_href_link(FILENAME_NEWSLETTER, '', 'NONSSL') . '">' . BOX_INFORMATION_NEWSLETTER . '</a><br>' . 
  
  '<a href="' . tep_href_link(FILENAME_GV_FAQ, '', 'NONSSL') . '">' . BOX_INFORMATION_GV . '</a><br>' .
  
  '<a href="' . tep_href_link(FILENAME_CONTACT_US) . '">' . BOX_INFORMATION_CONTACT . '</a>');
  new infoBox($info_box_contents);
?>
  </div>
<!-- information_eof //-->