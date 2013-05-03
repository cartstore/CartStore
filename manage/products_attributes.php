<?php
/*
  $Id: products_attributes.php,v 1.52 2003/07/10 20:46:01 dgw_ Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');
  $languages = tep_get_languages();

  link_get_variable('option_page');
  link_get_variable('value_page');
  link_get_variable('attribute_page');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    $page_info = '';
    if (isset($_GET['option_page'])) $page_info .= 'option_page=' . $_GET['option_page'] . '&';
    if (isset($_GET['value_page'])) $page_info .= 'value_page=' . $_GET['value_page'] . '&';
    if (isset($_GET['attribute_page'])) $page_info .= 'attribute_page=' . $_GET['attribute_page'] . '&';
    $file_group_page = (isset($HTTP_GET_VARS['file_group_page']) && is_numeric($HTTP_GET_VARS['file_group_page'])) ? $HTTP_GET_VARS['file_group_page'] : 1;
    $group_file_page = (isset($HTTP_GET_VARS['group_file_page']) && is_numeric($HTTP_GET_VARS['group_file_page'])) ? $HTTP_GET_VARS['group_file_page'] : 1;
    $order_by = (isset($HTTP_GET_VARS['order_by']) && is_string($HTTP_GET_VARS['order_by'])) ? $HTTP_GET_VARS['order_by'] : '';
    $page_info .= '&file_group_page=' . $file_group_page . '&group_file_page=' . $group_file_page . '&order_by' . $order_by;
    if (tep_not_null($page_info)) {
      $page_info = substr($page_info, 0, -1);
    }

    switch ($action) {
      case 'add_product_options':
        $products_options_id = tep_db_prepare_input($_POST['products_options_id']);
        $option_name_array = $_POST['option_name'];
		$option_type = $_POST['option_type'];	//clr 030714 update to add option type to products_option
		$option_length = $_POST['option_length'];	//clr 030714 update to add option length to products_option

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $option_name = tep_db_prepare_input($option_name_array[$languages[$i]['id']]);
		  $option_comment = $_POST['option_comment'];	//clr 030714 update to add option comment to products_option
//++++ QT Pro: Begin Changed code
          $track_stock=isset($_POST['track_stock'])?1:0;
          //tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, products_options_name, language_id,products_options_track_stock) values ('" . (int)$products_options_id . "', '" . tep_db_input($option_name) . "', '" . (int)$languages[$i]['id'] . "', '" . (int)$track_stock . "')");

		  tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, products_options_name, language_id, products_options_track_stock, products_options_type, products_options_length, products_options_comment) values ('" . (int)$products_options_id . "', '" . tep_db_input($option_name) . "', '" . (int)$languages[$i]['id'] . "', '" . (int)$track_stock . "', '" . $option_type . "', '" . $option_length . "', '" . $option_comment[$languages[$i]['id']]  . "')");
	        if($option_type != 0 && $option_type != 2){
						tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " (products_options_id, products_options_values_id) values ('" . (int)$products_options_id . "', '0')");
			}

//++++ QT Pro: End Changed Code
// iii 030811 added:  For TEXT and FILE option types, automatically add
// PRODUCTS_OPTIONS_VALUE_TEXT to the TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS
        switch ($option_type) {
          case PRODUCTS_OPTIONS_TYPE_TEXT:
          case PRODUCTS_OPTIONS_TYPE_FILE:
            tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " (products_options_values_id, products_options_id) values ('" . PRODUCTS_OPTIONS_VALUES_TEXT_ID .  "', '" .  (int)$products_options_id .  "')");
            break;
        }
		}
// iii 030811 added:  END
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'add_product_option_values':
        $value_name_array = $_POST['value_name'];
        $value_id = tep_db_prepare_input($_POST['value_id']);
        $option_id = tep_db_prepare_input($_POST['option_id']);

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $value_name = tep_db_prepare_input($value_name_array[$languages[$i]['id']]);

          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . (int)$value_id . "', '" . (int)$languages[$i]['id'] . "', '" . tep_db_input($value_name) . "')");
        }

        tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " (products_options_id, products_options_values_id) values ('" . (int)$option_id . "', '" . (int)$value_id . "')");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'add_product_attributes':
// iii 030811 added:  For TEXT and FILE option types, ignore option value
// entered by administrator and use PRODUCTS_OPTIONS_VALUES_TEXT instead.
        $products_options_query = tep_db_query("select products_options_type from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $_POST['options_id'] . "'");
        $products_options_array = tep_db_fetch_array($products_options_query);
        $values_id = tep_db_prepare_input((($products_options_array['products_options_type'] == PRODUCTS_OPTIONS_TYPE_TEXT) or ($products_options_array['products_options_type'] == PRODUCTS_OPTIONS_TYPE_FILE)) ? PRODUCTS_OPTIONS_VALUE_TEXT_ID : $_POST['values_id']);
// iii 030811 added:  END
        $products_id = tep_db_prepare_input($_POST['products_id']);
        $options_id = tep_db_prepare_input($_POST['options_id']);
//        $values_id = tep_db_prepare_input($_POST['values_id']);
        $value_price = tep_db_prepare_input($_POST['value_price']);
        $price_prefix = tep_db_prepare_input($_POST['price_prefix']);

        tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values ('', '" . (int)$products_id . "', '" . (int)$options_id . "', '" . (int)$values_id . "', '" . tep_db_input($value_price) . "', '" . tep_db_input($price_prefix) . "','','','')");

        if (DOWNLOAD_ENABLED == 'true') {
          $products_attributes_id = tep_db_insert_id();

          $products_attributes_filename = tep_db_prepare_input($_POST['products_attributes_filename']);
          $products_attributes_maxdays = tep_db_prepare_input($_POST['products_attributes_maxdays']);
          $products_attributes_maxcount = tep_db_prepare_input($_POST['products_attributes_maxcount']);

          if (DOWNLOADS_CONTROLLER_FILEGROUP_STATUS == 'Yes') {
            $product_attribute_filegroup_id = tep_db_prepare_input($HTTP_POST_VARS['filegroup_id']);
            if ($product_attribute_filegroup_id > 0) {
              $products_attributes_filename = 'Group_Files-' . $product_attribute_filegroup_id;
            } else {
              if (strstr($products_attributes_filename, 'Group_Files-')) $products_attributes_filename = 'File_Not_Assigned';
            }
            if (tep_not_null($products_attributes_filename)) tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " values (" . (int)$products_attributes_id . ", '" . tep_db_input($products_attributes_filename) . "', '" . tep_db_input($product_attribute_filegroup_id) . "', '" . tep_db_input($products_attributes_maxdays) . "', '" . tep_db_input($products_attributes_maxcount) . "')");
          } else if (tep_not_null($products_attributes_filename)) {
            tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " values (" . (int)$products_attributes_id . ", '" . tep_db_input($products_attributes_filename) . "', '', '" . tep_db_input($products_attributes_maxdays) . "', '" . tep_db_input($products_attributes_maxcount) . "')");
          }
        }

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'add_filegroups':
        $filegroup_id = tep_db_prepare_input($HTTP_POST_VARS['filegroup_id']);
        $file_group_array = $HTTP_POST_VARS['file_group_name'];
        // check if any name given
        $name_check = 0;
        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          if (!empty($file_group_array[$languages[$i]['id']])) $name_check = 1;
        }

        if (is_numeric($filegroup_id) && $name_check == 1) {
          for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
            $file_group_name = tep_db_prepare_input($file_group_array[$languages[$i]['id']]);

            tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS . " (download_group_id, download_group_name, language_id) values ('" . (int)$filegroup_id . "', '" . tep_db_input($file_group_name) . "', '" . (int)$languages[$i]['id'] . "')");
          }
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'add_group_file':
        $filegroup_id = tep_db_prepare_input($HTTP_POST_VARS['filegroup_id']);
        $file_desc_array = $HTTP_POST_VARS['file_desc'];
        $file_name = '';
        if (isset($HTTP_POST_VARS['input_filename']) && !empty($HTTP_POST_VARS['input_filename'])) {
          $file_name = tep_db_prepare_input($HTTP_POST_VARS['input_filename']);
        }
        if (tep_not_null($file_name) && file_exists(DIR_FS_CATALOG_DOWNLOAD . $file_name)) {
          // check existing file and descriptions
          $file_query = tep_db_query("select download_groups_file_id from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES . " where download_group_filename = '" . $file_name . "'");
          if (tep_db_num_rows($file_query) > 0) {
            $file_array = tep_db_fetch_array($file_query);
            tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES . " (download_groups_file_id, download_group_id, download_group_filename) values ('" . (int)$file_array['download_groups_file_id'] . "', '" . (int)$filegroup_id . "', '" . tep_db_input($file_name) . "')");
          } else {
            tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES . " (download_group_id, download_group_filename) values ('" . (int)$filegroup_id . "', '" . tep_db_input($file_name) . "')");
            $download_groups_file_id = tep_db_insert_id();

            for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
              $file_desc = tep_db_prepare_input($file_desc_array[$languages[$i]['id']]);
              if (empty($file_desc)) $file_desc = tep_db_prepare_input(TEXT_TEMP_DESC . ' ' . $download_groups_file_id);

              tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_TO_FILES . " (download_groups_file_id, language_id, download_group_file_description) values ('" . (int)$download_groups_file_id . "', '" . (int)$languages[$i]['id'] . "', '" . tep_db_input($file_desc) . "')");
            }
          }
        } else {
          if (!tep_not_null($file_name)) $messageStack->add_session(ERROR_NO_FILENAME, 'error');
          else $messageStack->add_session(sprintf(ERROR_FILE_DOES_NOT_EXIST, $file_name), 'error');
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'add_mass_files':
        $filegroup_id = tep_db_prepare_input($HTTP_POST_VARS['filegroup_id']);
        $file_selected = $HTTP_POST_VARS['file_selected'];
        foreach($file_selected as $k => $file_name) {
          if (tep_not_null($file_name)) {
            $file_name = tep_db_prepare_input($file_name);
            // check if file already in group
            $file_check_query = tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES . " where download_group_id = '" . (int)$filegroup_id . "' and download_group_filename = '" . $file_name . "'");
            if (tep_db_num_rows($file_check_query) == 0) {
              // check existing file and descriptions
              $file_query = tep_db_query("select download_groups_file_id from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES . " where download_group_filename = '" . $file_name . "'");
              if (tep_db_num_rows($file_query) > 0) {
                $file_array = tep_db_fetch_array($file_query);
                tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES . " (download_groups_file_id, download_group_id, download_group_filename) values ('" . (int)$file_array['download_groups_file_id'] . "', '" . (int)$filegroup_id . "', '" . tep_db_input($file_name) . "')");
              } else {
                tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES . " (download_group_id, download_group_filename) values ('" . (int)$filegroup_id . "', '" . tep_db_input($file_name) . "')");
                $download_groups_file_id = tep_db_insert_id();

                for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
                  $file_desc = tep_db_prepare_input(TEXT_TEMP_DESC . ' ' . $download_groups_file_id);

                  tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_TO_FILES . " (download_groups_file_id, language_id, download_group_file_description) values ('" . (int)$download_groups_file_id . "', '" . (int)$languages[$i]['id'] . "', '" . tep_db_input($file_desc) . "')");
                }
              }
            }
          }
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_filegroup_name':
        $file_group_name_array = $HTTP_POST_VARS['file_group_name'];
        $filegroup_id = tep_db_prepare_input($HTTP_POST_VARS['filegroup_id']);

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $file_group_name = tep_db_prepare_input($file_group_name_array[$languages[$i]['id']]);

          tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS . " set download_group_name = '" . tep_db_input($file_group_name) . "' where download_group_id = '" . (int)$filegroup_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
        }

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_file_desc':
        $file_desc_array = $HTTP_POST_VARS['file_desc'];
        $file_id = tep_db_prepare_input($HTTP_POST_VARS['file_id']);

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $file_desc = tep_db_prepare_input($file_desc_array[$languages[$i]['id']]);

          tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_TO_FILES . " set download_group_file_description = '" . tep_db_input($file_desc) . "' where download_groups_file_id = '" . (int)$file_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
        }

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_download_group':
        $filegroup_id = tep_db_prepare_input($HTTP_GET_VARS['filegroup_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS . " where download_group_id = '" . (int)$filegroup_id . "'");
        // check files in other group
        $files_query = tep_db_query("select download_groups_file_id from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES . " where download_group_id = '" . (int)$filegroup_id . "'");
        while ($file_array = tep_db_fetch_array($files_query)) {
          $file_check = tep_db_query("select download_groups_file_id from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES . " where download_group_id != '" . (int)$filegroup_id . "' and download_groups_file_id = '" . $file_array['download_groups_file_id'] . "'");
          if (tep_db_num_rows($file_check) === 0) {
            tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_TO_FILES . " where download_groups_file_id = '" . $file_array['download_groups_file_id'] . "'");
          }
        }
        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES . " where download_group_id = '" . (int)$filegroup_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_file_from_group':
        $filegroup_id = tep_db_prepare_input($HTTP_GET_VARS['filegroup_id']);
        $file_id = tep_db_prepare_input($HTTP_GET_VARS['group_file_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES . "
                      where download_group_id = '" . (int)$filegroup_id . "'
                      and download_groups_file_id = '" . (int)$file_id . "'");
        // check files in other group
        $file_check = tep_db_query("select download_groups_file_id from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES . "
                                    where download_group_id != '" . (int)$filegroup_id . "'
                                    and download_groups_file_id = '" . (int)$file_id . "'");
        if (tep_db_num_rows($file_check) === 0) {
          tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_TO_FILES . " where download_groups_file_id = '" . (int)$file_id . "'");
        }

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_file_all_groups':
        $file_id = tep_db_prepare_input($HTTP_GET_VARS['group_file_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES . "
                      where download_groups_file_id = '" . (int)$file_id . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_TO_FILES . " where download_groups_file_id = '" . (int)$file_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_option_name':
        $option_name_array = $_POST['option_name'];
        $option_id = tep_db_prepare_input($_POST['option_id']);
		//$option_name_array = $_POST['option_name'];
	    $option_type = $_POST['option_type'];	//clr 030714 update to add option type to products_option
	    $option_length = $_POST['option_length'];	//clr 030714 update to add option length to products_option

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $option_name = tep_db_prepare_input($option_name_array[$languages[$i]['id']]);
		   $option_comment = $_POST['option_comment'];	//clr 030714 update to add option comment to products_option
//++++ QT Pro: Begin Changed code
          $track_stock=isset($_POST['track_stock'])?1:0;

		  tep_db_query("update " . TABLE_PRODUCTS_OPTIONS . " set products_options_track_stock='".(int)$track_stock."', products_options_name = '" . tep_db_input($option_name) . "', products_options_type = '" . $option_type . "', products_options_length = '" . $option_length . "', products_options_comment = '" . $option_comment[$languages[$i]['id']] . "' where products_options_id = '" . (int)$option_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");

          //tep_db_query("update " . TABLE_PRODUCTS_OPTIONS . " set products_options_track_stock='" . (int)$track_stock . "',products_options_name = '" . tep_db_input($option_name) . "' where products_options_id = '" . (int)$option_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");


//++++ QT Pro: End Changed Code
        }
// iii added 030811:  automate insertion or deletion of text option values
        switch ($option_type) {
          case PRODUCTS_OPTIONS_TYPE_TEXT:
          case PRODUCTS_OPTIONS_TYPE_FILE:
//            tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . $_POST['option_id'] . "'"); // disabled because this could cause trouble if someone changed types unintentionally and deleted all their option values.  Shops with small numbers of values per option should consider uncommenting this.
            tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " values (NULL, '" . $_POST['option_id'] . "', '" . PRODUCTS_OPTIONS_VALUES_TEXT_ID . "')");
            break;
          default:
            tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_values_id = '" . PRODUCTS_OPTIONS_VALUES_TEXT_ID . "'");
        }
// iii added 030811:  END

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_value':
        $value_name_array = $_POST['value_name'];
        $value_id = tep_db_prepare_input($_POST['value_id']);
        $option_id = tep_db_prepare_input($_POST['option_id']);

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $value_name = tep_db_prepare_input($value_name_array[$languages[$i]['id']]);

          tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set products_options_values_name = '" . tep_db_input($value_name) . "' where products_options_values_id = '" . tep_db_input($value_id) . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
        }

        tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " set products_options_id = '" . (int)$option_id . "'  where products_options_values_id = '" . (int)$value_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_product_attribute':
// iii 030811 added:  Enforce rule that TEXT and FILE Options use value PRODUCTS_OPTIONS_VALUE_TEXT_ID
        $products_options_query = tep_db_query("select products_options_type from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $_POST['options_id'] . "'");
        $products_options_array = tep_db_fetch_array($products_options_query);
        switch ($products_options_array['products_options_type']) {
          case PRODUCTS_OPTIONS_TYPE_TEXT:
          case PRODUCTS_OPTIONS_TYPE_FILE:
            $values_id = PRODUCTS_OPTIONS_VALUE_TEXT_ID;
            break;
          default:
        $values_id = tep_db_prepare_input($_POST['values_id']);
}
// iii 030811 added END

        $products_id = tep_db_prepare_input($_POST['products_id']);
        $options_id = tep_db_prepare_input($_POST['options_id']);
//        $values_id = tep_db_prepare_input($_POST['values_id']);
        $value_price = tep_db_prepare_input($_POST['value_price']);
        $price_prefix = tep_db_prepare_input($_POST['price_prefix']);
        $attribute_id = tep_db_prepare_input($_POST['attribute_id']);

        tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set products_id = '" . (int)$products_id . "', options_id = '" . (int)$options_id . "', options_values_id = '" . (int)$values_id . "', options_values_price = '" . tep_db_input($value_price) . "', price_prefix = '" . tep_db_input($price_prefix) . "' where products_attributes_id = '" . (int)$attribute_id . "'");

        if (DOWNLOAD_ENABLED == 'true') {
          $products_attributes_filename = tep_db_prepare_input($_POST['products_attributes_filename']);
          $products_attributes_maxdays = tep_db_prepare_input($_POST['products_attributes_maxdays']);
          $products_attributes_maxcount = tep_db_prepare_input($_POST['products_attributes_maxcount']);

          if (DOWNLOADS_CONTROLLER_FILEGROUP_STATUS == 'Yes') {
            $product_attribute_filegroup_id = tep_db_prepare_input($HTTP_POST_VARS['filegroup_id']);
            if ($product_attribute_filegroup_id > 0) {
              $products_attributes_filename = 'Group_Files-' . $product_attribute_filegroup_id;
            } else {
              if (strstr($products_attributes_filename, 'Group_Files-')) $products_attributes_filename = 'File_Not_Assigned';
            }
            tep_db_query("replace into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " set products_attributes_id = '" . (int)$attribute_id . "', products_attributes_filename = '" . tep_db_input($products_attributes_filename) . "', products_attributes_filegroup_id = '" . tep_db_input($product_attribute_filegroup_id) . "', products_attributes_maxdays = '" . tep_db_input($products_attributes_maxdays) . "', products_attributes_maxcount = '" . tep_db_input($products_attributes_maxcount) . "'");
          } else if (tep_not_null($products_attributes_filename)) {
            tep_db_query("replace into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " set products_attributes_id = '" . (int)$attribute_id . "', products_attributes_filename = '" . tep_db_input($products_attributes_filename) . "', products_attributes_maxdays = '" . tep_db_input($products_attributes_maxdays) . "', products_attributes_maxcount = '" . tep_db_input($products_attributes_maxcount) . "'");
          }
        }

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_option':
        $option_id = tep_db_prepare_input($_GET['option_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$option_id . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$option_id . "' and products_options_values_id = '" . PRODUCTS_OPTIONS_VALUES_TEXT_ID . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_value':
        $value_id = tep_db_prepare_input($_GET['value_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$value_id . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$value_id . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_values_id = '" . (int)$value_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_attribute':
        $attribute_id = tep_db_prepare_input($_GET['attribute_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . (int)$attribute_id . "'");

// added for DOWNLOAD_ENABLED. Always try to remove attributes, even if downloads are no longer enabled
        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id = '" . (int)$attribute_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
    }
  }

//iii 031103 added to get results from database option type query
  $products_options_types_list = array();
  $products_options_types_query = tep_db_query("select products_options_types_id, products_options_types_name from " . TABLE_PRODUCTS_OPTIONS_TYPES . " where language_id='" . (int)$languages_id . "' order by products_options_types_id");
  while ($products_options_type_array = tep_db_fetch_array($products_options_types_query)) {
    $products_options_types_list[$products_options_type_array['products_options_types_id']] = $products_options_type_array['products_options_types_name'];
  }

  //CLR 030312 add function to draw pulldown list of option types
// Draw a pulldown for Option Types
//iii 031103 modified to use results of database option type query from above
function draw_optiontype_pulldown($name, $default = '') {
  global $products_options_types_list;
  $values = array();
  foreach ($products_options_types_list as $id => $text) {
    $values[] = array('id' => $id, 'text' => $text);
  }
/*  $values[] = array('id' => PRODUCTS_OPTIONS_TYPE_SELECT, 'text' => TABLE_TEXT_OPT_TYPE_SELECT);
  $values[] = array('id' => PRODUCTS_OPTIONS_TYPE_TEXT, 'text' => TABLE_TEXT_OPT_TYPE_TEXT);
  $values[] = array('id' => PRODUCTS_OPTIONS_TYPE_RADIO, 'text' => TABLE_TEXT_OPT_TYPE_RADIO);
  $values[] = array('id' => PRODUCTS_OPTIONS_TYPE_CHECKBOX, 'text' => TABLE_TEXT_OPT_TYPE_CHECKBOX);
  $values[] = array('id' => PRODUCTS_OPTIONS_TYPE_FILE, 'text' => TABLE_TEXT_OPT_TYPE_FILE);*/
  return tep_draw_pull_down_menu($name, $values, $default);
}

//CLR 030312 add function to translate type_id to name
// Translate option_type_values to english string
//iii 031103 modified to use results of database option type query from above
function translate_type_to_name($opt_type) {
  global $products_options_types_list;
  return isset($products_options_types_list[$opt_type]) ? $products_options_types_list[$opt_type] : 'Error ' . $opt_type;
/*  if ($opt_type == PRODUCTS_OPTIONS_TYPE_SELECT) return TABLE_TEXT_OPT_TYPE_SELECT;
  if ($opt_type == PRODUCTS_OPTIONS_TYPE_TEXT) return TABLE_TEXT_OPT_TYPE_TEXT;
  if ($opt_type == PRODUCTS_OPTIONS_TYPE_RADIO) return TABLE_TEXT_OPT_TYPE_RADIO;
  if ($opt_type == PRODUCTS_OPTIONS_TYPE_CHECKBOX) return TABLE_TEXT_OPT_TYPE_CHECKBOX;
  if ($opt_type == PRODUCTS_OPTIONS_TYPE_FILE) return TABLE_TEXT_OPT_TYPE_FILE;
  return 'Error ' . $opt_type;*/
}
// iii 031103 added/modified:  END

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>
<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />


<script language="javascript"><!--
function go_option() {
  if (document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value != "none") {
    location = "<?php echo tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . ($_GET['option_page'] ? $_GET['option_page'] : 1)); ?>&option_order_by="+document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value;
  }
}
//--></script>
<style type="text/css">
<!--
.hide {
	display: none;
}
-->
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<!-- options and values//-->
      <tr>
        <td width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
<!-- options //-->
<?php
  if ($action == 'delete_product_option') { // delete product option
    $options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$_GET['option_id'] . "' and language_id = '" . (int)$languages_id . "'");
    $options_values = tep_db_fetch_array($options);
?>
              <tr>
                <td class="pageHeading">&nbsp;<?php echo $options_values['products_options_name']; ?>&nbsp;</td>
                <td>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.png', '', '1', '53'); ?>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?> </td>
                  </tr>
<?php
    $products = tep_db_query("select p.products_id, pd.products_name, pov.products_options_values_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pov.language_id = '" . (int)$languages_id . "' and pd.language_id = '" . (int)$languages_id . "' and pa.products_id = p.products_id and pa.options_id='" . (int)$_GET['option_id'] . "' and pov.products_options_values_id = pa.options_values_id order by pd.products_name");
    if (tep_db_num_rows($products)) {
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
      $rows = 0;
      while ($products_values = tep_db_fetch_array($products)) {
        $rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td align="center" class="smallText">&nbsp;<?php echo $products_values['products_id']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_name']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_options_values_name']; ?>&nbsp;</td>
                  </tr>
<?php
      }
?>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
                  <tr>
                    <td colspan="3" class="main"><br><?php echo TEXT_WARNING_OF_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="3" class="main"><br><?php echo '<a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, (isset($_GET['value_page']) ? 'value_page=' . $_GET['value_page'] . '&' : '') . (isset($_GET['attribute_page']) ? 'attribute_page=' . $_GET['attribute_page'] : ''), 'NONSSL') . '">'; ?><?php echo ' cancel '; ?></a>&nbsp;</td>
                  </tr>
<?php
    } else {
?>
                  <tr>
                    <td class="main" colspan="3"><br><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br><?php echo '<a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_option&option_id=' . $_GET['option_id'], 'NONSSL') . '">'; ?><?php echo ' delete '; ?></a>&nbsp;&nbsp;&nbsp;<?php echo '<a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, (isset($_GET['order_by']) ? 'order_by=' . $_GET['order_by'] . '&' : '') . (isset($_GET['page']) ? 'page=' . $_GET['page'] : ''), 'NONSSL') . '">'; ?><?php echo ' cancel '; ?></a>&nbsp;</td>
                  </tr>
<?php
    }
?>
                </table></td>
              </tr>
<?php
  } else {
    if (isset($_GET['option_order_by'])) {
      $option_order_by = $_GET['option_order_by'];
    } else {
      $option_order_by = 'products_options_id';
    }
?>
              <tr>
<?php
//++++ QT Pro: Begin Changed code
?>
                <td colspan="3" class="pageHeading">&nbsp;<?php echo HEADING_TITLE_OPT; ?>&nbsp;</td>
<?php
//++++ QT Pro: End Changed Code
?>
                <td align="right"><br><form name="option_order_by" action="<?php echo FILENAME_PRODUCTS_ATTRIBUTES; ?>"><select name="selected" onChange="go_option()"><option value="products_options_id"<?php if ($option_order_by == 'products_options_id') { echo ' SELECTED'; } ?>><?php echo TEXT_OPTION_ID; ?></option><option value="products_options_name"<?php if ($option_order_by == 'products_options_name') { echo ' SELECTED'; } ?>><?php echo TEXT_OPTION_NAME; ?></option></select></form></td>
              </tr>
              <tr>
<?php
//++++ QT Pro: Begin Changed code
?>
                <td colspan="4" class="smallText">
<?php
//++++ QT Pro: End Changed Code
    $per_page = MAX_ROW_LISTS_OPTIONS;
    $options = "select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages_id . "' order by " . $option_order_by;
    if (!isset($option_page)) {
      $option_page = 1;
    }
    $prev_option_page = $option_page - 1;
    $next_option_page = $option_page + 1;

    $option_query = tep_db_query($options);

    $option_page_start = ($per_page * $option_page) - $per_page;
    $num_rows = tep_db_num_rows($option_query);

    if ($num_rows <= $per_page) {
      $num_pages = 1;
    } else if (($num_rows % $per_page) == 0) {
      $num_pages = ($num_rows / $per_page);
    } else {
      $num_pages = ($num_rows / $per_page) + 1;
    }
    $num_pages = (int) $num_pages;

    $options = $options . " LIMIT $option_page_start, $per_page";

    // Previous
    if ($prev_option_page)  {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $prev_option_page) . '"> &lt;&lt; </a> | ';
    }

    for ($i = 1; $i <= $num_pages; $i++) {
      if ($i != $option_page) {
        echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $i) . '">' . $i . '</a> | ';
      } else {
        echo '<b><font color=red>' . $i . '</font></b> | ';
      }
    }

    // Next
    if ($option_page != $num_pages) {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $next_option_page) . '"> &gt;&gt; </a>';
    }
	//CLR 030212 - Add column for option type
?>
                </td>
              </tr>
              <tr>
<?php
//++++ QT Pro: Begin Changed code
?>
                <td colspan="7"><?php echo tep_black_line(); ?></td>
<?php
//++++ QT Pro: End Changed Code
?>
              </tr>
              <tr class="dataTableHeadingRow">
			  	<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>

                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>

				<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_TYPE; ?>&nbsp;</td>

				<!-- CLR 030212 - Add column for option type //-->
    			<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_LENGTH; ?>&nbsp;</td>

				<!-- CLR 030212 - Add column for option length //-->
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_COMMENT; ?>&nbsp;</td>
				<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_TRACK_STOCK; ?>&nbsp;</td>

				<!-- CLR 030212 - Add column for option comment //-->
                <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
				<?php
//++++ QT Pro: Begin Changed code
?>

<?php
//++++ QT Pro: End Changed Code
?>

              </tr>
              <tr>
<?php
//++++ QT Pro: Begin Changed code
?>
                <td colspan="7"><?php echo tep_black_line(); ?></td>
<?php
//++++ QT Pro: End Changed Code
?>
              </tr>
<?php
    $next_id = 1;
    $rows = 0;
    $options = tep_db_query($options);
    while ($options_values = tep_db_fetch_array($options)) {
      $rows++;
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      if (($action == 'update_option') && ($_GET['option_id'] == $options_values['products_options_id'])) {
        echo '<form name="option" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option_name', 'NONSSL') . '" method="post">';
        $inputs = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
          $option_name = tep_db_query("select products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $options_values['products_options_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
          $option_name = tep_db_fetch_array($option_name);
          $inputs .= $languages[$i]['code'] . ':&nbsp;<input type="text" name="option_name[' . $languages[$i]['id'] . ']" size="20" value="' . $option_name['products_options_name'] . '">&nbsp;<br>';
        }
?>

				<td align="center" class="smallText">&nbsp;<?php echo $options_values['products_options_id']; ?><input type="hidden" name="option_id" value="<?php echo $options_values['products_options_id']; ?>">&nbsp;</td>
				<td class="smallText" colspan="3"><?php echo $inputs; ?></td>
				<td class="smallText"><?php echo TABLE_HEADING_OPT_LENGTH . ' <input type="text" name="option_length" size="4" value="' . $option_name['products_options_length'] . '">'; ?></td>	<!-- CLR 030212 - Add column for option length //-->
				<td class="smallText"><?php echo draw_optiontype_pulldown('option_type', $options_values['products_options_type']); ?></td>	<!-- CLR 030212 - Add column for option type //-->
<?php
//++++ QT Pro: Begin Changed code
?>
                <td align="center" class="smallText"><input type=checkbox name=track_stock <?php echo $options_values['products_options_track_stock']?"checked":""; ?>></td>

<?php
//++++ QT Pro: End Changed Code
?>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_update.png', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '', 'NONSSL') . '">'; ?><?php echo  IMAGE_CANCEL; ?></a>&nbsp;</td>

<?php
        echo '</form>' . "\n";
      } else {
?>


				<td align="center" class="smallText">&nbsp;<?php echo $options_values["products_options_id"]; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo $options_values["products_options_name"]; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo translate_type_to_name($options_values["products_options_type"]); ?>&nbsp;</td> <!-- CLR 030212 - Add column for option type //-->
				<td class="smallText">&nbsp;<?php echo $options_values["products_options_length"]; ?>&nbsp;</td>	<!-- CLR 030212 - Add column for option length //-->
				<td class="smallText">&nbsp;<?php echo $options_values["products_options_comment"]; ?>&nbsp;</td>	<!-- CLR 030212 - Add column for option comment //-->
				<?php
				//++++ QT Pro: Begin Changed code
				?>
                <td align="center" class="smallText">&nbsp;<?php echo $options_values['products_options_track_stock']?"Yes":"No"; 				?>
				</td>
				<?php
				//++++ QT Pro: End Changed Code
				?>

                <td align="center" class="smallText">&nbsp;<?php echo '<a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option&option_id=' . $options_values['products_options_id'] . '&option_order_by=' . $option_order_by . '&option_page=' . $option_page, 'NONSSL') . '">'; ?><?php echo  IMAGE_UPDATE; ?></a>&nbsp;&nbsp;<?php echo '<a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_product_option&option_id=' . $options_values['products_options_id'], 'NONSSL') , '">'; ?><?php echo  IMAGE_DELETE; ?></a>&nbsp;</td>

<?php
      }
?>
              </tr>
<?php
      $max_options_id_query = tep_db_query("select max(products_options_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS);
      $max_options_id_values = tep_db_fetch_array($max_options_id_query);
      $next_id = $max_options_id_values['next_id'];
    }
?>
              <tr>
<?php
//++++ QT Pro: Begin Changed code
?>
                <td colspan="7"><?php echo tep_black_line(); ?></td>
<?php
//++++ QT Pro: End Changed Code
?>
              </tr>
<?php
    if ($action != 'update_option') {
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      echo '<form name="options" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_options&option_page=' . $option_page, 'NONSSL') . '" method="post"><input type="hidden" name="products_options_id" value="' . $next_id . '">';
      $inputs = '';
      for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
        $inputs .= $languages[$i]['code'] . ':&nbsp;<input type="text" name="option_name[' . $languages[$i]['id'] . ']" size="20">&nbsp;<br>';
      }
?>

				<td align="center" class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
                <td class="smallText" colspan="2"><?php echo $inputs; ?></td>
				<td class="smallText"><?php echo TABLE_HEADING_OPT_LENGTH . ' <input type="text" name="option_length" size="4" value="">'; ?></td>	<!-- CLR 030212 - Add column for option length //-->
				<td class="smallText"><?php echo draw_optiontype_pulldown('option_type'); ?></td>	<!-- CLR 030212 - Add column for option type //-->
				<td align="center" ><input type=checkbox name=track_stock></td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_insert.png', IMAGE_INSERT); ?>&nbsp;</td>
<?php
      echo '</form>';
?>
              </tr>
              <tr>
<?php
//++++ QT Pro: Begin Changed code
?>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
<?php
//++++ QT Pro: End Changed Code
?>
              </tr>
<?php
    }
  }
?>
            </table></td>
<!-- options eof //-->
            <td valign="top" width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
<!-- value //-->
<?php
  if ($action == 'delete_option_value') { // delete product option value
    $values = tep_db_query("select products_options_values_id, products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$_GET['value_id'] . "' and language_id = '" . (int)$languages_id . "'");
    $values_values = tep_db_fetch_array($values);
?>
              <tr>
                <td colspan="3" class="pageHeading">&nbsp;<?php echo $values_values['products_options_values_name']; ?>&nbsp;</td>
                <td>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.png', '', '1', '53'); ?>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
    $products = tep_db_query("select p.products_id, pd.products_name, po.products_options_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and po.language_id = '" . (int)$languages_id . "' and pa.products_id = p.products_id and pa.options_values_id='" . (int)$_GET['value_id'] . "' and po.products_options_id = pa.options_id order by pd.products_name");
    if (tep_db_num_rows($products)) {
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
      while ($products_values = tep_db_fetch_array($products)) {
        $rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td align="center" class="smallText">&nbsp;<?php echo $products_values['products_id']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_name']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_options_name']; ?>&nbsp;</td>
                  </tr>
<?php
      }
?>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
                  <tr>
                    <td class="main" colspan="3"><br><?php echo TEXT_WARNING_OF_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br><?php echo '<a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, (isset($_GET['value_page']) ? 'value_page=' . $_GET['value_page'] . '&' : '') . (isset($_GET['attribute_page']) ? 'attribute_page=' . $attribute_page : ''), 'NONSSL') . '">'; ?><?php ' cancel '; ?></a>&nbsp;</td>
                  </tr>
<?php
    } else {
?>
                  <tr>
                    <td class="main" colspan="3"><br><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br><?php echo '<a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_value&value_id=' . $_GET['value_id'], 'NONSSL') . '">'; ?><?php echo ' delete '; ?></a>&nbsp;&nbsp;&nbsp;<?php echo '<a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '&option_page=' . $option_page . (isset($_GET['value_page']) ? '&value_page=' . $value_page : '') . (isset($_GET['attribute_page']) ? '&attribute_page=' . $attribute_page : ''), 'NONSSL') . '">'; ?><?php echo  ' cancel '; ?></a>&nbsp;</td>
                  </tr>
<?php
    }
?>
              	</table></td>
              </tr>
<?php
  } else {
?>
              <tr>
                <td colspan="3" class="pageHeading">&nbsp;<?php echo HEADING_TITLE_VAL; ?>&nbsp;</td>
                <td>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.png', '', '1', '53'); ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4" class="smallText">
<?php
    $per_page = MAX_ROW_LISTS_OPTIONS;
    $values = "select pov.products_options_values_id, pov.products_options_values_name, pov2po.products_options_id from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov left join " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " pov2po on pov.products_options_values_id = pov2po.products_options_values_id where pov.language_id = '" . (int)$languages_id . "' order by pov.products_options_values_id";
    if (!isset($value_page)) {
      $value_page = 1;
    }
    $prev_value_page = $value_page - 1;
    $next_value_page = $value_page + 1;

    $value_query = tep_db_query($values);

    $value_page_start = ($per_page * $value_page) - $per_page;
    $num_rows = tep_db_num_rows($value_query);

    if ($num_rows <= $per_page) {
      $num_pages = 1;
    } else if (($num_rows % $per_page) == 0) {
      $num_pages = ($num_rows / $per_page);
    } else {
      $num_pages = ($num_rows / $per_page) + 1;
    }
    $num_pages = (int) $num_pages;

    $values = $values . " LIMIT $value_page_start, $per_page";

    // Previous
    if ($prev_value_page)  {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_order_by=' . $option_order_by . '&value_page=' . $prev_value_page) . '"> &lt;&lt; </a> | ';
    }

    for ($i = 1; $i <= $num_pages; $i++) {
      if ($i != $value_page) {
         echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, (isset($option_order_by) ? 'option_order_by=' . $option_order_by . '&' : '') . 'value_page=' . $i) . '">' . $i . '</a> | ';
      } else {
         echo '<b><font color=red>' . $i . '</font></b> | ';
      }
    }

    // Next
    if ($value_page != $num_pages) {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, (isset($option_order_by) ? 'option_order_by=' . $option_order_by . '&' : '') . 'value_page=' . $next_value_page) . '"> &gt;&gt;</a> ';
    }
?>
                </td>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    $next_id = 1;
    $rows = 0;
    $values = tep_db_query($values);
    while ($values_values = tep_db_fetch_array($values)) {
      $options_name = tep_options_name($values_values['products_options_id']);
// iii 030813 added: Option Type Feature and File Uploading
// fetch products_options_id for use if the option value is deleted
// with TEXT and FILE Options, there are multiple options for the single TEXT
// value and only the single reference should be deleted
      $option_id = $values_values['products_options_id'];
      $values_name = $values_values['products_options_values_name'];
      $rows++;
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      if (($action == 'update_option_value') && ($_GET['value_id'] == $values_values['products_options_values_id'])) {
        echo '<form name="values" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_value', 'NONSSL') . '" method="post">';
        $inputs = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
          $value_name = tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$values_values['products_options_values_id'] . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
          $value_name = tep_db_fetch_array($value_name);
          $inputs .= $languages[$i]['code'] . ':&nbsp;<input type="text" name="value_name[' . $languages[$i]['id'] . ']" size="15" value="' . $value_name['products_options_values_name'] . '">&nbsp;<br>';
        }
?>
                <td align="center" class="smallText">&nbsp;<?php echo $values_values['products_options_values_id']; ?><input type="hidden" name="value_id" value="<?php echo $values_values['products_options_values_id']; ?>">&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo "\n"; ?><select name="option_id">
<?php
        $options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages_id . "' order by products_options_name");
        while ($options_values = tep_db_fetch_array($options)) {
          echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '"';
          if ($values_values['products_options_id'] == $options_values['products_options_id']) {
            echo ' selected';
          }
          echo '>' . $options_values['products_options_name'] . '</option>';
        }
?>
                </select>&nbsp;</td>
                <td class="smallText"><?php echo $inputs; ?></td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_update.png', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '', 'NONSSL') . '">'; ?><?php echo  IMAGE_CANCEL; ?></a>&nbsp;</td>
<?php
        echo '</form>';
      } else {
// iii 030813 added:  option ID to parameter list of delete button's href
// allows delete to specify just that option/value pair when deleting from
// the TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS table
?>
                <td align="center" class="smallText">&nbsp;<?php echo $values_values["products_options_values_id"]; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo $options_name; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo $values_name; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo '<a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option_value&value_id=' . $values_values['products_options_values_id'] . (isset($_GET['value_page']) ? '&value_page=' . $_GET['value_page'] : ''), 'NONSSL') . '">'; ?><?php echo  IMAGE_UPDATE; ?></a>&nbsp;&nbsp;<?php echo '<a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_option_value&value_id=' . $values_values['products_options_values_id'] . '&option_id=' . $option_id, 'NONSSL') , '">'; ?><?php echo  IMAGE_DELETE; ?></a>&nbsp;</td>
<?php
      }
      $max_values_id_query = tep_db_query("select max(products_options_values_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS_VALUES);
      $max_values_id_values = tep_db_fetch_array($max_values_id_query);
      $next_id = $max_values_id_values['next_id'];
    }
?>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    if ($action != 'update_option_value') {
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      echo '<form name="values" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_option_values&value_page=' . $value_page, 'NONSSL') . '" method="post">';
?>
                <td align="center" class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<select name="option_id">
<?php
      $options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_name");
      while ($options_values = tep_db_fetch_array($options)) {
        echo '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';
      }

      $inputs = '';
      for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
        $inputs .= $languages[$i]['code'] . ':&nbsp;<input type="text" name="value_name[' . $languages[$i]['id'] . ']" size="15">&nbsp;<br>';
      }
?>
                </select>&nbsp;</td>
                <td class="smallText"><input type="hidden" name="value_id" value="<?php echo $next_id; ?>"><?php echo $inputs; ?></td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_insert.png', IMAGE_INSERT); ?>&nbsp;</td>
<?php
      echo '</form>';
?>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    }
  }
?>
            </table></td>
          </tr>
        </table></td>
<!-- option value eof //-->
      </tr>
<?php
// BOF Super Download Shop v1.0 mod
  if (DOWNLOAD_ENABLED == 'true' && DOWNLOADS_CONTROLLER_FILEGROUP_STATUS == 'Yes') {
    require('includes/group_download.php');
  }
// EOF Super Download Shop v1.0 mod
?>
<!-- products_attributes //-->
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">

        </table></td>
      </tr>
      <tr>
<?php
  if ($action == 'update_attribute') {
    $form_action = 'update_product_attribute';
  } else {
    $form_action = 'add_product_attributes';
  }

  if (!isset($attribute_page)) {
    $attribute_page = 1;
  }
  $prev_attribute_page = $attribute_page - 1;
  $next_attribute_page = $attribute_page + 1;
?>
        <td><div class="nohide"><form name="attributes" action="<?php echo tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=' . $form_action . '&option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page); ?>" method="post"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="7" class="smallText">
<?php
  $per_page = MAX_ROW_LISTS_OPTIONS;
  $attributes = "select pa.* from " . TABLE_PRODUCTS_ATTRIBUTES . " pa left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pa.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by pd.products_name";
  $attribute_query = tep_db_query($attributes);
  $attribute_page_start = ($per_page * $attribute_page) - $per_page;
  $num_rows = tep_db_num_rows($attribute_query);

  if ($num_rows <= $per_page) {
     $num_pages = 1;
  } else if (($num_rows % $per_page) == 0) {
     $num_pages = ($num_rows / $per_page);
  } else {
     $num_pages = ($num_rows / $per_page) + 1;
  }
  $num_pages = (int) $num_pages;

  $attributes = $attributes . " LIMIT $attribute_page_start, $per_page";

  // Previous
  if ($prev_attribute_page) {
    echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'attribute_page=' . $prev_attribute_page) . '"> &lt;&lt; </a> | ';
  }

  for ($i = 1; $i <= $num_pages; $i++) {
    if ($i != $attribute_page) {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'attribute_page=' . $i) . '">' . $i . '</a> | ';
    } else {
      echo '<b><font color="red">' . $i . '</font></b> | ';
    }
  }

  // Next
  if ($attribute_page != $num_pages) {
    echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'attribute_page=' . $next_attribute_page) . '"> &gt;&gt; </a>';
  }
?>
            </td>
          </tr>
          <tr>
            <td colspan="7"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</td>
            <td class="dataTableHeadingContent" align="right">&nbsp;<?php echo TABLE_HEADING_OPT_PRICE; ?>&nbsp;</td>
            <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_OPT_PRICE_PREFIX; ?>&nbsp;</td>
            <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="7"><?php echo tep_black_line(); ?></td>
          </tr>
<?php
  $next_id = 1;
  $attributes = tep_db_query($attributes);
  while ($attributes_values = tep_db_fetch_array($attributes)) {
    $products_name_only = tep_get_products_name($attributes_values['products_id']);
    $options_name = tep_options_name($attributes_values['options_id']);
    $values_name = tep_values_name($attributes_values['options_values_id']);
    $rows++;
?>
          <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
    if (($action == 'update_attribute') && ($_GET['attribute_id'] == $attributes_values['products_attributes_id'])) {
?>
            <td class="smallText">&nbsp;<?php echo $attributes_values['products_attributes_id']; ?><input type="hidden" name="attribute_id" value="<?php echo $attributes_values['products_attributes_id']; ?>">&nbsp;</td>
            <td class="smallText">&nbsp;<select name="products_id">
<?php
      $products = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
      while($products_values = tep_db_fetch_array($products)) {
        if ($attributes_values['products_id'] == $products_values['products_id']) {
          echo "\n" . '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '" SELECTED>' . $products_values['products_name'] . '</option>';
        } else {
          echo "\n" . '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $products_values['products_name'] . '</option>';
        }
      }
?>
            </select>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="options_id">
<?php
      $options = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_name");
      while($options_values = tep_db_fetch_array($options)) {
        if ($attributes_values['options_id'] == $options_values['products_options_id']) {
          echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '" SELECTED>' . $options_values['products_options_name'] . '</option>';
        } else {
          echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';
        }
      }
?>
            </select>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="values_id">
<?php
      $values = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id ='" . $languages_id . "' order by products_options_values_name");
      while($values_values = tep_db_fetch_array($values)) {
        if ($attributes_values['options_values_id'] == $values_values['products_options_values_id']) {
          echo "\n" . '<option name="' . $values_values['products_options_values_name'] . '" value="' . $values_values['products_options_values_id'] . '" SELECTED>' . $values_values['products_options_values_name'] . '</option>';
        } else {
          echo "\n" . '<option name="' . $values_values['products_options_values_name'] . '" value="' . $values_values['products_options_values_id'] . '">' . $values_values['products_options_values_name'] . '</option>';
        }
      }
?>
            </select>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<input type="text" name="value_price" value="<?php echo $attributes_values['options_values_price']; ?>" size="6">&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<input type="text" name="price_prefix" value="<?php echo $attributes_values['price_prefix']; ?>" size="2">&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_update.png', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><?php echo  IMAGE_CANCEL; ?></a>&nbsp;</td>
<?php
      if (DOWNLOAD_ENABLED == 'true') {
        if (DOWNLOADS_CONTROLLER_FILEGROUP_STATUS == 'Yes') {
          $download_query_raw ="select products_attributes_filename, products_attributes_filegroup_id, products_attributes_maxdays, products_attributes_maxcount
                                from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                                where products_attributes_id='" . $attributes_values['products_attributes_id'] . "'";
          $download_query = tep_db_query($download_query_raw);
          if (tep_db_num_rows($download_query) > 0) {
            $download = tep_db_fetch_array($download_query);
            $products_attributes_filename = $download['products_attributes_filename'];
            $products_attributes_filegroup_id = $download['products_attributes_filegroup_id'];
            $products_attributes_maxdays  = $download['products_attributes_maxdays'];
            $products_attributes_maxcount = $download['products_attributes_maxcount'];
          }
        } else {
         $download_query_raw ="select products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount
                              from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                              where products_attributes_id='" . $attributes_values['products_attributes_id'] . "'";
         $download_query = tep_db_query($download_query_raw);
         if (tep_db_num_rows($download_query) > 0) {
           $download = tep_db_fetch_array($download_query);
           $products_attributes_filename = $download['products_attributes_filename'];
           $products_attributes_maxdays  = $download['products_attributes_maxdays'];
           $products_attributes_maxcount = $download['products_attributes_maxcount'];
         }
        }
?>
          <tr class="<?php echo (!($rows % 2)? 'attributes-even' : 'attributes-odd');?>">
            <td>&nbsp;</td>
            <td colspan="5">
              <table>
                <tr class="<?php echo (!($rows % 2)? 'attributes-even' : 'attributes-odd');?>">
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DOWNLOAD; ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_FILENAME; ?></td>
                  <td class="smallText"><?php echo tep_draw_input_field('products_attributes_filename', $products_attributes_filename, 'size="15"'); ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_DAYS; ?></td>
                  <td class="smallText"><?php echo tep_draw_input_field('products_attributes_maxdays', $products_attributes_maxdays, 'size="5"'); ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_COUNT; ?></td>
                  <td class="smallText"><?php echo tep_draw_input_field('products_attributes_maxcount', $products_attributes_maxcount, 'size="5"'); ?>&nbsp;</td>
                </tr>
              </table>
            </td>
            <td>&nbsp;</td>
          </tr>
<?php
        if ( DOWNLOADS_CONTROLLER_FILEGROUP_STATUS == 'Yes' ) {
?>
                <tr class="<?php echo (!($rows % 2)? 'attributes-even' : 'attributes-odd');?>">
                  <td class="smallText"></td>
                  <td class="smallText"><?php echo TABLE_TEXT_FILEGROUP; ?></td>
                  <td class="smallText">&nbsp;<select name="filegroup_id">
<?php
          $file_groups_query = tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS . " where language_id = '" . $languages_id . "' order by download_group_id");
          while ($file_groups = tep_db_fetch_array($file_groups_query)) {
            if ($file_groups['download_group_id'] == $products_attributes_filegroup_id) {
              echo "\n" . '<option name="' . TEXT_OPTION_FILEGROUP . '" value="' . $file_groups['download_group_id'] . '" SELECTED>' . $file_groups['download_group_name'] . '</option>';
            } else {
              echo "\n" . '<option name="' . TEXT_OPTION_FILEGROUP . '" value="' . $file_groups['download_group_id'] . '">' . $file_groups['download_group_name'] . '</option>';
            }
          }
?>
                  </select>&nbsp;</td>
                </tr>
<?php
        }
      }
?>
<?php
    } elseif (($action == 'delete_product_attribute') && ($_GET['attribute_id'] == $attributes_values['products_attributes_id'])) {
?>
            <td class="smallText">&nbsp;<b><?php echo $attributes_values["products_attributes_id"]; ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo $products_name_only; ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo $options_name; ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo $values_name; ?></b>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<b><?php echo $attributes_values["options_values_price"]; ?></b>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<b><?php echo $attributes_values["price_prefix"]; ?></b>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<b><?php echo '<a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_attribute&attribute_id=' . $_GET['attribute_id']) . '">'; ?><?php echo  IMAGE_CONFIRM; ?></a>&nbsp;&nbsp;<?php echo '<a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '&option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><?php echo  IMAGE_CANCEL; ?></a>&nbsp;</b></td>
<?php
    } else {
?>
            <td class="smallText">&nbsp;<?php echo $attributes_values["products_attributes_id"]; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $products_name_only; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $options_name; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $values_name; ?>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<?php echo $attributes_values["options_values_price"]; ?>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo $attributes_values["price_prefix"]; ?>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo '<a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_attribute&attribute_id=' . $attributes_values['products_attributes_id'] . '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><?php echo  IMAGE_UPDATE; ?></a>&nbsp;&nbsp;<?php echo '<a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_product_attribute&attribute_id=' . $attributes_values['products_attributes_id'] . '&attribute_page=' . $attribute_page, 'NONSSL') , '">'; ?><?php echo  IMAGE_DELETE; ?></a>&nbsp;</td>
<?php
    }
    $max_attributes_id_query = tep_db_query("select max(products_attributes_id) + 1 as next_id from " . TABLE_PRODUCTS_ATTRIBUTES);
    $max_attributes_id_values = tep_db_fetch_array($max_attributes_id_query);
    $next_id = $max_attributes_id_values['next_id'];
?>
          </tr>
<?php
  }
  if ($action != 'update_attribute') {
?>
          <tr>
            <td colspan="7"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
            <td class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
      	    <td class="smallText">&nbsp;<select name="products_id">
<?php
    $products = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
    while ($products_values = tep_db_fetch_array($products)) {
      echo '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $products_values['products_name'] . '</option>';
    }
?>
            </select>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="options_id">
<?php
    $options = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_name");
    while ($options_values = tep_db_fetch_array($options)) {
      echo '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';
    }
?>
            </select>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="values_id">
<?php
    $values = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . $languages_id . "' order by products_options_values_name");
    while ($values_values = tep_db_fetch_array($values)) {
      echo '<option name="' . $values_values['products_options_values_name'] . '" value="' . $values_values['products_options_values_id'] . '">' . $values_values['products_options_values_name'] . '</option>';
    }
?>
            </select>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<input type="text" name="value_price" size="6">&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<input type="text" name="price_prefix" size="2" value="+">&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_insert.png', IMAGE_INSERT); ?>&nbsp;</td>
          </tr>
<?php
      if (DOWNLOAD_ENABLED == 'true') {
        $products_attributes_maxdays  = DOWNLOAD_MAX_DAYS;
        $products_attributes_maxcount = DOWNLOAD_MAX_COUNT;
        $products_attributes_filename = '';
?>
          <tr class="<?php echo (!($rows % 2)? 'attributes-even' : 'attributes-odd');?>">
            <td>&nbsp;</td>
            <td colspan="5">
              <table>
                <tr class="<?php echo (!($rows % 2)? 'attributes-even' : 'attributes-odd');?>">
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DOWNLOAD; ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_FILENAME; ?></td>
                  <td class="smallText"><?php echo tep_draw_input_field('products_attributes_filename', $products_attributes_filename, 'size="15"'); ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_DAYS; ?></td>
                  <td class="smallText"><?php echo tep_draw_input_field('products_attributes_maxdays', $products_attributes_maxdays, 'size="5"'); ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_COUNT; ?></td>
                  <td class="smallText"><?php echo tep_draw_input_field('products_attributes_maxcount', $products_attributes_maxcount, 'size="5"'); ?>&nbsp;</td>
                </tr>
              </table>
            </td>
            <td>&nbsp;</td>
          </tr>
<?php
        if ( DOWNLOADS_CONTROLLER_FILEGROUP_STATUS == 'Yes' ) {
?>
                <tr class="<?php echo (!($rows % 2)? 'attributes-even' : 'attributes-odd');?>">
                  <td class="smallText"></td>
                  <td class="smallText"><?php echo TABLE_TEXT_FILEGROUP; ?></td>
                  <td class="smallText">&nbsp;<select name="filegroup_id">
<?php
          $file_groups_query = tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS . " where language_id = '" . (int)$languages_id . "' order by download_group_id");
          while ($file_groups = tep_db_fetch_array($file_groups_query)) {
            echo '<option name="' . TEXT_OPTION_FILEGROUP . '" value="' . $file_groups['download_group_id'] . '">' . $file_groups['download_group_name'] . '</option>';
          }
?>
                  </select>&nbsp;</td>
                </tr>
<?php
        }
      } // end of DOWNLOAD_ENABLED section
?>
<?php
  }
?>
          <tr>
            <td colspan="7"><?php echo tep_black_line(); ?></td>
          </tr>
        </table></form>

        </div></td>
      </tr>
    </table></td>
<!-- products_attributes_eof //-->
  </tr>
</table>
<!-- body_text_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
