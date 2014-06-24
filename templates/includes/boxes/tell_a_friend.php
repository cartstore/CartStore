<!-- tell_a_friend //-->

<li class="nav-header">Tell a Freind</li> 
        <?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_TELL_A_FRIEND);
  new infoBoxHeading($info_box_contents, false, false);
  $info_box_contents = array();
  $info_box_contents[] = array('form' => tep_draw_form('tell_a_friend', tep_href_link(FILENAME_TELL_A_FRIEND, '', 'NONSSL', false), 'get'), 'align' => '', 'text' => tep_draw_input_field('to_email_address', '', '') . '' . tep_image_submit('button_tell_a_friend.gif', BOX_HEADING_TELL_A_FRIEND) . tep_draw_hidden_field('products_id', $_GET['products_id']) . tep_hide_session_id() . '');
  new infoBox($info_box_contents);
?>
   

<!-- tell_a_friend_eof //-->