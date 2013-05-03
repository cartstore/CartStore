<?php
/*
  $Id: box.php,v 1.7 2003/06/20 16:23:08 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible

  Example usage:

  $heading = array();
  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => BOX_HEADING_TOOLS,
                     'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=tools'));

  $contents = array();
  $contents[] = array('text'  => SOME_TEXT);

  $box = new box;
  echo $box->infoBox($heading, $contents);
*/

  class box extends tableBlock {
    function box() {
      $this->heading = array();
      $this->contents = array();
    }

    function infoBox($heading, $contents) {
	$replace = array("<b>", "</b>");
	
      $this->table_row_parameters = '';
      $this->table_data_parameters = '';
	 $heading[0]['text']='<div id="rightCol">
				<div class="module">
					<div><div><div><h3>'.str_replace($replace, "",$heading[0]['text']) .'</h3>';
	  $this->heading = $this->tableBlock($heading);

      $this->table_row_parameters = '';
      $this->table_data_parameters = '';
	  $arrayItem=count($contents)-1;
	  $contents[$arrayItem]['text']=$contents[$arrayItem]['text'].'</div></div></div></div></div>';
	  
      $this->contents = $this->tableBlock($contents);

      return $this->heading . $this->contents;
    }

    function menuBox($heading, $contents) {
      $this->table_data_parameters = '';
      if (isset($heading[0]['link'])) {
        $this->table_data_parameters .= '';
        $heading[0]['text'] = '<a href="' . $heading[0]['link'] . '" class="menuBoxHeadingLink">' . $heading[0]['text'] . '</a>';
      } else {
        $heading[0]['text'] = '' . $heading[0]['text'] . '';
      }
      $this->heading = $this->tableBlock($heading);

      $this->table_data_parameters = '';
      $this->contents = $this->tableBlock($contents);

      return $this->heading . $this->contents;
    }
  }
?>
