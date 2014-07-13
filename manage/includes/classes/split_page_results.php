<?php
/*
  $Id: split_page_results.php,v 1.13 2003/05/05 17:56:50 dgw_ Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/

        include(DIR_FS_ADMIN . 'includes/classes/pagination.php');
        
  class splitPageResults {
    function splitPageResults(&$current_page_number, $max_rows_per_page, &$sql_query, &$query_num_rows) {
      if (empty($current_page_number)) 
            $current_page_number = 1;
            
        if(isset($_GET['per_page']) && $_GET['per_page'] != '' ){
            $limit_per_page = $_GET['per_page'];    
        } else {
            $limit_per_page = 0;
        }

/*
      $pos_to = strlen($sql_query);
      $pos_from = strpos($sql_query, ' from', 0);

      $pos_group_by = strpos($sql_query, ' group by', $pos_from);
      if (($pos_group_by < $pos_to) && ($pos_group_by != false)) $pos_to = $pos_group_by;

      $pos_having = strpos($sql_query, ' having', $pos_from);
      if (($pos_having < $pos_to) && ($pos_having != false)) $pos_to = $pos_having;

      $pos_order_by = strpos($sql_query, ' order by', $pos_from);
      if (($pos_order_by < $pos_to) && ($pos_order_by != false)) $pos_to = $pos_order_by;

      $reviews_count_query = tep_db_query("select count(*) as total " . substr($sql_query, $pos_from, ($pos_to - $pos_from)));
      $reviews_count = tep_db_fetch_array($reviews_count_query);
      $query_num_rows = $reviews_count['total'];
*/
	 $reviews_count_query = tep_db_query( $sql_query );
	 $query_num_rows = tep_db_num_rows( $reviews_count_query );

      $num_pages = ceil($query_num_rows / $max_rows_per_page);
      if ($current_page_number > $num_pages) {
        $current_page_number = $num_pages;
      }
      $offset = ($max_rows_per_page * ($current_page_number - 1));
      
      $sql_query .= " limit " . $limit_per_page . ", " . $max_rows_per_page;
      
      //print($sql_query);
    }

    function display_links($query_numrows, $max_rows_per_page, $max_page_links, $current_page_number, $parameters = '', $page_name = 'page') {
      global $PHP_SELF;


//////////////////////////////////////////////////////////////////////////////////////
require_once(DIR_FS_ADMIN . 'includes/classes/pagination.php');
$pagination = new Pagination();   

$pageconfig['base_url'] = basename($PHP_SELF).'?';
$pageconfig['total_rows'] = $query_numrows;
$pageconfig['per_page'] = $max_rows_per_page;
$pageconfig['page_query_string'] = true;

$pagination->initialize($pageconfig);


return $pagination->create_links();     

//////////////////////////////////////////////////////////////////////////////////////



     /* if ( tep_not_null($parameters) && (substr($parameters, -1) != '&') ) $parameters .= '&';

// calculate number of pages needing links
      $num_pages = ceil($query_numrows / $max_rows_per_page);

      $pages_array = array();
      for ($i=1; $i<=$num_pages; $i++) {
        $pages_array[] = array('id' => $i, 'text' => $i);
      }

      if ($num_pages > 1) {
        $display_links = tep_draw_form('pages', basename($PHP_SELF), '', 'get');
        
        $display_links .= '<ul class="pagination">';
        
        if ($current_page_number > 1) {
          $display_links .= '<li><a href="' . tep_href_link(basename($PHP_SELF), $parameters . $page_name . '=' . ($current_page_number - 1), 'NONSSL') . '" class="splitPageLink"><i class="fa fa-chevron-left"></i></a></li>';
        } else {
          $display_links .= '<li><a href="javascript:void(0);"><i class="fa fa-chevron-left"></i></a></li>';
        }

        //$display_links .= sprintf(TEXT_RESULT_PAGE, tep_draw_lists_custom($page_name, $pages_array, $current_page_number, 'onChange="this.form.submit();"'), $num_pages);
        $display_links .= tep_draw_lists_custom($page_name, $pages_array, $current_page_number, 'onChange="this.form.submit();"');





        if (($current_page_number < $num_pages) && ($num_pages != 1)) {
          $display_links .= '<li><a href="' . tep_href_link(basename($PHP_SELF), $parameters . $page_name . '=' . ($current_page_number + 1), 'NONSSL') . '" class="splitPageLink"><i class="fa fa-chevron-right"></i> </a></li>';
        } else {
          $display_links .= '<li><a href="javascript:void(0);"><i class="fa fa-chevron-right"></i></a></li>';
        }
        
        $display_links .= '</ul>';

        if ($parameters != '') {
          if (substr($parameters, -1) == '&') $parameters = substr($parameters, 0, -1);
          $pairs = explode('&', $parameters);
          while (list(, $pair) = each($pairs)) {
            list($key,$value) = explode('=', $pair);
            $display_links .= tep_draw_hidden_field(rawurldecode($key), rawurldecode($value));
          }
        }

        if (SID) $display_links .= tep_draw_hidden_field(tep_session_name(), tep_session_id());

        $display_links .= '</form>';
      } else {
        $display_links = sprintf(TEXT_RESULT_PAGE, $num_pages, $num_pages);
      }

      return $display_links;*/
       
    }

    function display_count($query_numrows, $max_rows_per_page, $current_page_number, $text_output) {
        
        if($current_page_number)
            $current_page_number = 1;
        
        if(isset($_GET['per_page']) && $_GET['per_page'] != '' ){
            $from_num = $_GET['per_page']+1;
        } else {
            $from_num = 1;
        }      
      
        $to_num = (($from_num-1) + $max_rows_per_page);  
      
      
      if ($to_num > $query_numrows) {
            $to_num = $query_numrows;
      } 
        
      ///// back up by rokon
      /*$to_num = ($max_rows_per_page * $current_page_number);
      if ($to_num > $query_numrows) $to_num = $query_numrows;
      $from_num = ($max_rows_per_page * ($current_page_number - 1));
      if ($to_num == 0) {
        $from_num = 0;
      } else {
        $from_num++;
      }*/
      
      ///// back up by rokon

      return sprintf($text_output, $from_num, $to_num, $query_numrows);
    }
  }
?>
