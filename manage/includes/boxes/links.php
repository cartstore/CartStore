<?php
/*
  $Id: links.php,v 1.00 2003/10/02 Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/
?>
<!-- links //-->
       <div class="module">
<div>
<div>
<div>
<h3>LINKS</h3>
<ul>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_LINKS,
                     'link'  => tep_href_link(FILENAME_LINKS, 'selected_box=links'));

  if ($selected_box == 'links') {
    $contents[] = array('text'  => '<li><a href="' . tep_href_link(FILENAME_LINKS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_LINKS_LINKS . '</a></li>');
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?></ul>
           </div>
</div>
</div>
<!-- links_eof //-->
