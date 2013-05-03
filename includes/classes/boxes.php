<?php
/*
  $Id: boxes.php,v 1.1.1.1 2003/09/18 19:05:12 wilt Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  class tableBox {
    var $table_border = '0';
    var $table_width = '100%';
    var $table_cellspacing = '0';
    var $table_cellpadding = '0';
    var $table_parameters = '';
    var $table_row_parameters = '';
    var $table_data_parameters = '';

// class constructor
    function tableBox($contents, $direct_output = false) {
      $tableBox_string = '';
      if (tep_not_null($this->table_parameters)) $tableBox_string .= ' ' . $this->table_parameters;
      $tableBox_string .= '' . "\n";

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= $contents[$i]['form'] . "\n";
        $tableBox_string .= '  ';
        if (tep_not_null($this->table_row_parameters)) $tableBox_string .= ' ' . $this->table_row_parameters;
        if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) $tableBox_string .= ' ' . $contents[$i]['params'];
        $tableBox_string .= '' . "\n";

        if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
          for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
            if (isset($contents[$i][$x]['text']) && tep_not_null($contents[$i][$x]['text'])) {
              $tableBox_string .= '    ';
              if (isset($contents[$i][$x]['align']) && tep_not_null($contents[$i][$x]['align'])) $tableBox_string .= '' . tep_output_string($contents[$i][$x]['align']) . '"';
              if (isset($contents[$i][$x]['valign']) && tep_not_null($contents[$i][$x]['valign'])) $tableBox_string .= '' . $contents[$i][$x]['valign'] . '"';
              if (isset($contents[$i][$x]['params']) && tep_not_null($contents[$i][$x]['params'])) {
                $tableBox_string .= ' ' . $contents[$i][$x]['params'];
              } elseif (tep_not_null($this->table_data_parameters)) {
                $tableBox_string .= ' ' . $this->table_data_parameters;
              }
              $tableBox_string .= '';
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
              $tableBox_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
              $tableBox_string .= '' . "\n";
            }
          }
        } else {
          $tableBox_string .= '   ';
          if (isset($contents[$i]['align']) && tep_not_null($contents[$i]['align'])) $tableBox_string .= '' . tep_output_string($contents[$i]['align']) . '';
          if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) {
            $tableBox_string .= ' ' . $contents[$i]['params'];
          } elseif (tep_not_null($this->table_data_parameters)) {
            $tableBox_string .= ' ' . $this->table_data_parameters;
          }
          $tableBox_string .= '' . $contents[$i]['text'] . '' . "\n";
        }

        $tableBox_string .= ' ' . "\n";
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= '</form>' . "\n";
      }

      $tableBox_string .= '' . "\n";

      if ($direct_output == true) echo $tableBox_string;

      return $tableBox_string;
    }

	function infoBoxHeaderTemplate($headertext,$right_arrow)
	{
		$btrace=debug_backtrace();
//		var_dump(debug_backtrace());
		$boxname=basename($btrace[1]['file'],".php");
//		echo $boxname;
	    if (file_exists(STS_TEMPLATE_DIR."boxes/infobox_".$boxname."_header.php.html"))
		{
			$template=sts_read_template_file (STS_TEMPLATE_DIR."boxes/infobox_".$boxname."_header.php.html");
		}
		else
		{
			$template=sts_read_template_file (STS_TEMPLATE_DIR."boxes/infobox_header.php.html");
		}

		$template = str_replace('$headertext', $headertext, $template);
		$template = str_replace('$right_arrow', $right_arrow, $template);
		echo $template;
	}

	function infoBoxTemplate($content)
	{
		$btrace=debug_backtrace();
		$boxname=basename($btrace[1]['file'],".php");
	    if (file_exists(STS_TEMPLATE_DIR."boxes/infobox_".$boxname.".php.html"))
		{
			$template=sts_read_template_file (STS_TEMPLATE_DIR."boxes/infobox_".$boxname.".php.html");
		}
		else
		{
			$template=sts_read_template_file (STS_TEMPLATE_DIR."boxes/infobox.php.html");
		}

		$template = str_replace('$content', $content, $template);
		echo $template;
	}
  }

  class infoBox extends tableBox {
    function infoBox($contents) {

      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->infoBoxContents($contents));
      $this->table_cellpadding = '0';
      $this->table_parameters = 'class="infoBox"';


	  // START  STS
	  require_once(DIR_WS_MODULES."sts/sts_infobox.php");
	  $sts_infobox=new sts_infobox();
	  if ($sts_infobox->enabled)
	  {
		$this->infoboxtemplate($this->infoBoxContents($contents));
	  }
	  else
	  {
		$this->tableBox($info_box_contents, true);
	  }
	  // END STS

  }

    function infoBoxContents($contents) {
      $this->table_cellpadding = '0';
      $this->table_parameters = '';
      $info_box_contents = array();

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(array('align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                           'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),

                                           'text' => (isset($contents[$i]['text']) ? $contents[$i]['text'] : '')));
      }

      return $this->tableBox($info_box_contents);
    }
  }

  class infoBoxHeading extends tableBox {
    function infoBoxHeading($contents, $left_corner = true, $right_corner = true, $right_arrow = false) {
      global $language;
      $this->table_cellpadding = '0';

      if ($left_corner == true) {
        $left_corner = tep_image(DIR_WS_IMAGES . 'infobox/corner_left.gif');
      } else {
        $left_corner = tep_image(DIR_WS_IMAGES . 'infobox/corner_right_left.gif');
      }
      if ($right_arrow == true) {
        $right_arrow = '<a href="' . $right_arrow . '">' . tep_image(DIR_WS_IMAGES . 'infobox/arrow_right.gif', ICON_ARROW_RIGHT) . '</a>';
      } else {
        $right_arrow = '';
      }
      if ($right_corner == true) {
        $right_corner = $right_arrow . tep_image(DIR_WS_IMAGES . 'infobox/corner_right.gif');
      } else {
        $right_corner = $right_arrow . tep_draw_separator('pixel_trans.gif', '11', '14');
      }


	  // START  STS
      require_once(DIR_WS_LANGUAGES . $language . '/modules/sts/sts_infobox.php');
	  require_once(DIR_WS_MODULES."sts/sts_infobox.php");
	  $sts_infobox=new sts_infobox();
	  if ($sts_infobox->enabled)
	  {
      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => 'width="100%" class="infoBoxHeading"',
                                         'text' => $contents[0]['text']));

	  $this->infoBoxHeaderTemplate($this->tablebox($info_box_contents),$right_arrow);

	  }
	  else
	  {
      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => '',
                                         'text' => $left_corner),
                                   array('params' => '',
                                         'text' => $contents[0]['text']),
                                   array('params' => 'height="14" class="infoBoxHeading" nowrap',
                                         'text' => $right_corner));
  	  $this->tableBox($info_box_contents, true);
	  }
	  // END  STS
    }
  }

  class contentBox extends tableBox {
    function contentBox($contents) {

	  require_once(DIR_WS_MODULES."sts/sts_infobox.php");
	  $sts_infobox=new sts_infobox();
	  if ($sts_infobox->enabled)
	  {
		$this->infoBoxTemplate($this->tableBox($contents));
	  }
	  else
	  {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->contentBoxContents($contents));
      $this->table_cellpadding = '1';
      $this->table_parameters = 'class="infoBox"';
      $this->tableBox($info_box_contents, true);
	  }
    }

    function contentBoxContents($contents) {
      $this->table_cellpadding = '4';
      $this->table_parameters = '';

      return $this->tableBox($contents);
    }
  }

  class contentBoxHeading extends tableBox {
    function contentBoxHeading($contents) {
      $right_arrow = '';

  	  // START  STS
	  require_once(DIR_WS_MODULES."sts/sts_infobox.php");
	  $sts_infobox=new sts_infobox();
	  if ($sts_infobox->enabled)
	  {
      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => '',
                                         'text' => $contents[0]['text']));

	  $this->infoBoxHeaderTemplate($this->tablebox($info_box_contents),$right_arrow);
	  }
	  else
	  {
      $this->table_width = '100%';
      $this->table_cellpadding = '0';

      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => '',
                                         'text' => tep_image(DIR_WS_IMAGES . 'infobox/corner_left.gif')),
                                   array('params' => '',
                                         'text' => $contents[0]['text']),
                                   array('params' => '',
                                         'text' => tep_image(DIR_WS_IMAGES . 'infobox/corner_right_left.gif')));
      $this->tableBox($info_box_contents, true);
	  }
  	  // END STS

    }
  }

  class errorBox extends tableBox {
    function errorBox($contents) {
      $this->table_data_parameters = 'class="errorBox"';


  	  // START  STS
	  require_once(DIR_WS_MODULES."sts/sts_infobox.php");
	  $sts_infobox=new sts_infobox();
	  if ($sts_infobox->enabled)
	  {
	  	$this->infoBoxTemplate($this->infoBoxContents($contents));
	  }
	  else
	  {
		$this->tableBox($contents, true);
	  }
  	  // END  STS
    }
  }

  class productListingBox extends tableBox {
    function productListingBox($contents) {
      $this->table_parameters = '';

  	  // START  STS
	  require_once(DIR_WS_MODULES."sts/sts_infobox.php");
	  $sts_infobox=new sts_infobox();
	  if ($sts_infobox->enabled)
	  {
		$this->infoBoxHeaderTemplate("","");
		$this->infoBoxTemplate($this->tablebox($contents));
	  }
	  else
	  {
		$this->tableBox($contents, true);
	  }
  	  // END STS

	  }
  }


//New estimatedshippingBox Class
class estimatedshippingBox extends tableBox {
	function estimatedshippingBox($contents) {
		$info_box_contents = array();
		$info_box_contents[] = array('text' => $this->estimatedshippingBoxContents($contents));
		$this->table_cellpadding = '0';
		$this->table_parameters = '';
		$this->tableBox($info_box_contents, true);
	}

	function estimatedshippingBoxContents($contents) {
		$this->table_cellpadding = 'o';
		$this->table_parameters = '';
		$info_box_contents = array();
		$info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '100%', '1')));
		for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
		$info_box_contents[] = array(array('align' => $contents[$i]['align'],
		'form' => $contents[$i]['form'],
		'params' => '',
		'text' => $contents[$i]['text']));
		}
		$info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '100%', '1')));
		return $this->tableBox($info_box_contents);
	}
}


class estimatedshippingBoxHeading extends tableBox { // START CLASS
	function estimatedshippingBoxHeading($contents, $left_corner = true, $right_corner = true, $right_arrow = false) {
		$this->table_cellpadding = '';



		if ($left_corner == true) {
			$left_corner = tep_image(DIR_WS_IMAGES . 'estimatedshippingBox/corner_left.gif');
		} else {
			$left_corner = tep_image(DIR_WS_IMAGES . 'estimatedshippingBox/corner_right_left.gif');
		}
		if ($right_arrow == true) {
			$right_arrow = '<a href="' . $right_arrow . '">' . tep_image(DIR_WS_IMAGES . 'estimatedshippingBox/arrow_right.gif', ICON_ARROW_RIGHT) . '</a>';
		} else {
			$right_arrow = '';
		}
		if ($right_corner == true) {
			$right_corner = $right_arrow . tep_image(DIR_WS_IMAGES . 'estimatedshippingBox/corner_right.gif');
		} else {
			$right_corner = $right_arrow . tep_draw_separator('pixel_trans.gif', '11', '14');
		}

		$info_box_contents = array();
		$info_box_contents[] = array(array('params' => 'height="14" class="estimatedshippingBoxHeading"',
		'text' => $left_corner),
		array('params' => '',
		'text' => $contents[0]['text']),
		array('params' => '',
		'text' => $right_corner));
		$this->tableBox($info_box_contents, true);
	}  // END OF FUNCTION
} // END OF CLASS

?>