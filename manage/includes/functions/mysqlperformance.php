<?php
/*
  $Id: mysqlperformance.php,v 2.0 2007/10/02 22:50:51 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  // By : DeadlySin3
  // this function strips a specific line from a file 
  // functions returns True else false 
  //
  function cutline($filename,$line_no) {
    $strip_return=FALSE;
    if (isset($filename) && isset($line_no)){
      $data=file($filename);
      $pipe=fopen($filename,'w');
      $size=count($data);
      for($line=0;$line<$size;$line++){
        if($line!=$skip){
          fputs($pipe,$data[$line]); 
        }else{
          $strip_return=TRUE;
        }
      }
     }
    return $strip_return; 
  } 

  function display_links($query_numrows, $max_rows_per_page, $max_page_links, $current_page_number, $parameters = '', $page_name = 'page') {
    global $PHP_SELF;
    if ( tep_not_null($parameters) && (substr($parameters, -1) != '&') ) $parameters .= '&';
// calculate number of pages needing links
    $num_pages = ceil($query_numrows / $max_rows_per_page);
    $pages_array = array();
    for ($i=1; $i<=$num_pages; $i++) {
      $pages_array[] = array('id' => $i, 'text' => $i);
    }
    if ($num_pages > 1) {
      $display_links = tep_draw_form('pages', basename($PHP_SELF), '', 'get');
      if ($current_page_number > 1) {
        $display_links .= '<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $page_name . '=' . ($current_page_number - 1), 'NONSSL') . '" class="splitPageLink">' . PREVNEXT_BUTTON_PREV . '</a>&nbsp;&nbsp;';
      } else {
        $display_links .= PREVNEXT_BUTTON_PREV . '&nbsp;&nbsp;';
      }
      $display_links .= sprintf(TEXT_RESULT_PAGE, tep_draw_pull_down_menu($page_name, $pages_array, $current_page_number, 'onChange="this.form.submit();"'), $num_pages);
      if (($current_page_number < $num_pages) && ($num_pages != 1)) {
        $display_links .= '&nbsp;&nbsp;<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $page_name . '=' . ($current_page_number + 1), 'NONSSL') . '" class="splitPageLink">' . PREVNEXT_BUTTON_NEXT . '</a>';
      } else {
        $display_links .= '&nbsp;&nbsp;' . PREVNEXT_BUTTON_NEXT;
      }
      if ($parameters != '') {
        if (substr($parameters, -1) == '&') $parameters = substr($parameters, 0, -1);
        $pairs = explode('&', $parameters);
        while (list(, $pair) = each($pairs)) {
          list($key,$value) = explode('=', $pair);
          $display_links .= tep_draw_hidden_field(rawurldecode($key), rawurldecode($value));
        }
      }
      $display_links .=   '</form>'; //2.2RC1 update
    } else {
      $display_links = sprintf(TEXT_RESULT_PAGE, $num_pages, $num_pages);
    }
    return $display_links;
  }

?>