<?php
/*
  $Id: affiliate_news.php,v 3.00 2003/10/12

  

  Contribution based on:

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

//  Build language list
    $languages_array = array();
    $languages = tep_get_languages();
    $lng_exists = false;
    for ($i = 0, $n = sizeof($languages); $i < $n; $i++)
    {
        if ($languages[$i]['directory'] == $_GET['lngdir']) {
         $lng_exists = true;
         $lng_display_id = $languages[$i]['id'];
        }

        $languages_array[] = array('id' => $languages[$i]['directory'],
            'text' => $languages[$i]['name']);
    }

    if (!$lng_exists)
    {
        $_POST ['lngdir'] = $language;
        $_GET ['lngdir'] = $language;
    }

  if ($_GET['action']) {
    switch ($_GET['action']) {
      case 'setflag': //set the status of a news item.
        if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
          if ($_GET['affiliate_news_id']) {
            tep_db_query("update " . TABLE_AFFILIATE_NEWS . " set news_status = '" . $_GET['flag'] . "' where news_id = '" . $_GET['affiliate_news_id'] . "'");
          }
        }

        tep_redirect(tep_href_link(FILENAME_AFFILIATE_NEWS, isset($_GET['lngdir']) ? 'lngdir=' . $_GET['lngdir']:''));
        break;

      case 'delete_affiliate_news_confirm': //user has confirmed deletion of news article.
        if ($_POST['affiliate_news_id']) {
          $affiliate_news_id = tep_db_prepare_input($_POST['affiliate_news_id']);
          tep_db_query("delete from " . TABLE_AFFILIATE_NEWS . " where news_id = '" . tep_db_input($affiliate_news_id) . "'");
          tep_db_query("delete from " . TABLE_AFFILIATE_NEWS_CONTENTS . " where affiliate_news_id = '" . tep_db_input($affiliate_news_id) . "'");
        }

        tep_redirect(tep_href_link(FILENAME_AFFILIATE_NEWS, isset($_GET['lngdir']) ? 'lngdir=' . $_GET['lngdir']:''));
        break;

      case 'insert_affiliate_news': //insert a new news article.
         $a_headlines_array = $_POST['headlines'];
         $a_contents_array = $_POST['contents'];
         $a_languages_check_array = $_POST['a_languages_check'];
         $languages = tep_get_languages();
         for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $a_languages_id = $languages[$i]['id'];
          $a_headlines = $a_headlines_array[$a_languages_id];
          $a_news = $a_contents_array[$a_languages_id];
          if ($a_headlines != NULL) {
           if (!$a_news_id) {
            $insert_sql_data = array('date_added' => 'now()', //uses the inbuilt mysql function 'now'
                                     'news_status'     => '1'
            );
            tep_db_perform(TABLE_AFFILIATE_NEWS, $insert_sql_data);
            $a_news_id = tep_db_insert_id(); //not actually used ATM -- just there in case
           }
            $sql_data_array = array('affiliate_news_id' =>  tep_db_prepare_input($a_news_id),
                                   'affiliate_news_languages_id' => tep_db_prepare_input($a_languages_id),
                                   'affiliate_news_headlines' => tep_db_prepare_input($a_headlines),
                                   'affiliate_news_contents' => tep_db_prepare_input($a_news)
            );
            tep_db_perform(TABLE_AFFILIATE_NEWS_CONTENTS, $sql_data_array);
          }
         }

        tep_redirect(tep_href_link(FILENAME_AFFILIATE_NEWS, isset($_GET['lngdir']) ? 'lngdir=' . $_GET['lngdir']:''));
        break;

      case 'update_affiliate_news': //user wants to modify a news article.
        if(isset($_GET['affiliate_news_id'])) {
         $a_news_id = tep_db_prepare_input($_GET['affiliate_news_id']);
         $a_headlines_array = $_POST['headlines'];
         $a_contents_array = $_POST['contents'];
         $a_languages_check_array = $_POST['a_languages_check'];
         $a_delete_lng_news_array = $_POST['delete_news'];
         $a_count_lng_def = $_POST['a_count'];
         $languages = tep_get_languages();
         for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $a_languages_id = $languages[$i]['id'];
          $a_headlines = $a_headlines_array[$a_languages_id];
          $a_news = $a_contents_array[$a_languages_id];
          if (($a_languages_check_array[$a_languages_id] == 'not_set' and $a_news != '' and $a_headlines != '')) {
           $insert_sql_data = array('affiliate_news_id' => $a_news_id,
//                                  'date_added' => 'now()'
                                    'affiliate_news_languages_id' => tep_db_prepare_input($a_languages_id)
           );
           $a_count_lng_def++;
           $sql_data_array = array('affiliate_news_headlines' => tep_db_prepare_input($a_headlines),
                                   'affiliate_news_contents'  => tep_db_prepare_input($a_news));
           $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
           tep_db_perform(TABLE_AFFILIATE_NEWS_CONTENTS, $sql_data_array, 'insert');
          } else {
           if ($a_delete_lng_news_array[$a_languages_id] == 1) {
            tep_db_query('delete from ' . TABLE_AFFILIATE_NEWS_CONTENTS . " where affiliate_news_id = '" . tep_db_prepare_input($a_news_id) . "' and affiliate_news_languages_id = '" . (int)$a_languages_id . "'");
            if( $a_count_lng_def == 1) {
             tep_db_query('delete from ' . TABLE_AFFILIATE_NEWS . " where news_id = '" . tep_db_prepare_input($a_news_id) . "'");

            } elseif ($a_count_lng_def > 0 ) {
             $a_count_lng_def--;
            }
           } else {
           $sql_data_array = array('affiliate_news_headlines' => tep_db_prepare_input($a_headlines),
                                   'affiliate_news_contents'  => tep_db_prepare_input($a_news));
           tep_db_perform(TABLE_AFFILIATE_NEWS_CONTENTS, $sql_data_array, 'update', "affiliate_news_id = '" . $a_news_id . "' and affiliate_news_languages_id = '" . (int)$a_languages_id . "'");
           }
          }
         }
        }
        tep_redirect(tep_href_link(FILENAME_AFFILIATE_NEWS, isset($_GET['lngdir']) ? 'lngdir=' . $_GET['lngdir']:''));
        break;
    }
  }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>

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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ($_GET['action'] == 'new_affiliate_news') { //insert or edit a news item

// npe update begin multilingual 040908
//      $affiliate_news_query = tep_db_query("se lect news_id, headline, content from " . TABLE_AFFILIATE_NEWS . " where news_id = '" . $_GET['affiliate_news_id'] . "' and languages_id ='" . $a_language . "'");
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo TABLE_HEADING_AFFILIATE_NEWS; ?></td>
            <td class="pageHeading2" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('new_affiliate_news', FILENAME_AFFILIATE_NEWS, (isset($_GET['affiliate_news_id']) ? 'affiliate_news_id=' . $_GET['affiliate_news_id'] . '&action=update_affiliate_news' : 'action=insert_affiliate_news' ). (isset($_GET['lngdir']) ? '&lngdir=' . $_GET['lngdir']:''), 'post', 'enctype="multipart/form-data"'); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
 <?php
              $languages = tep_get_languages();
              for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
               $a_languages_id = $languages[$i]['id'];
               if ( isset($_GET['affiliate_news_id']) ) { //editing exsiting news item
      $affiliate_news_query = tep_db_query("select news_id, affiliate_news_contents_id, affiliate_news_headlines as headline, affiliate_news_contents as content, date_added, news_status from " . TABLE_AFFILIATE_NEWS . ", " . TABLE_AFFILIATE_NEWS_CONTENTS . " where news_id = '" . $_GET['affiliate_news_id'] . "' and news_id = affiliate_news_id and affiliate_news_languages_id ='" . $a_languages_id . "'");
      $affiliate_news = tep_db_fetch_array($affiliate_news_query);
//      tep_draw_hidden_field('a_languages_check[' . $i . ']', 'set');
    } else { //adding new news item
      $affiliate_news = array();
    }
    if ($affiliate_news['affiliate_news_contents_id'] == NULL) {
     echo tep_draw_hidden_field('a_languages_check[' . $a_languages_id . ']', 'not_set' );
    } else {
     $a_count_lng_def++;
    }
    echo  '<tr><td class="main">' . TEXT_AFFILIATE_NEWS_HEADLINE  . tep_draw_separator('pixel_trans.png', '24', '15') . '</td>';
    echo '<td class="main">' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . ' ' . tep_draw_input_field('headlines[' . $a_languages_id . ']', $affiliate_news['headline'], '', true) . '</td>'; ?>
          <td class="main" valign = "top"><?php echo tep_draw_checkbox_field('delete_news[' . $a_languages_id . ']', 1) .  ' ' . TEXT_AFFILIATE_NEWS_CONTENT_DELETE; ?></td>
          </tr>
          <tr>
            <td colspan = 2><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" valign = "top"><?php echo TEXT_AFFILIATE_NEWS_CONTENT; ?></td>
            <td class="main"><?php echo tep_draw_textarea_field('contents[' . $a_languages_id . ']', 'soft', '70', '15', stripslashes($affiliate_news['content'])); ?>
            </td>
        <td class="main" align="right">
          <?php
            isset($_GET['affiliate_news_id']) ? $cancel_button = '&nbsp;&nbsp;<a class="button" href="' . tep_href_link(FILENAME_AFFILIATE_NEWS, 'affiliate_news_id=' . $_GET['affiliate_news_id']) . (isset($_GET['lngdir']) ? '&lngdir=' . $_GET['lngdir']:'') . '">' . IMAGE_CANCEL . '</a>' : $cancel_button = '';
            echo (isset($_GET['affiliate_news_id']) ? tep_image_submit('button_update.png', IMAGE_UPDATE) : tep_image_submit('button_insert.png', IMAGE_INSERT) )  . $cancel_button ;
          ?>
        </td>
          </tr>
          <tr>
            <td colspan = 2></td>
          </tr>
<?php
              } //end for-language-loop
              echo tep_draw_hidden_field('a_count', $a_count_lng_def);
?>
        </table>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>
      </form></tr>
<?php

  } else {
?>

      </tr>
      <tr>
<!-- npe admin begin add language selection to news #add !-->

          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><?php
// npe admin begin edit languages on affiliate_news.php 040809 #change
//          echo tep_draw_form('lng', FILENAME_AFFILIATE_NEWS, '', 'get');
          echo tep_draw_form('lng', FILENAME_AFFILIATE_NEWS, '', 'get');
          if (isset($_GET['page'])) echo tep_draw_hidden_field('page', $_GET['page']);
          if (isset($_GET['affiliate_news_id'])) echo tep_draw_hidden_field('affiliate_news_id', $_GET['affiliate_news_id']);
// npe admin end edit languages on edit_textdata.php 040809 #change
?>            <td class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.png', '1', HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_pull_down_menu('lngdir', $languages_array, '', 'onChange="this.form.submit();"'); ?></td>
          </form></tr>
        </table></td>

<!-- npe admin end add language selection to news #add !-->

      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_AFFILIATE_NEWS_HEADLINE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_AFFILIATE_NEWS_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_AFFILIATE_NEWS_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $rows = 0;

    $affiliate_news_count = 0;
//<!-- npe admin begin add language selection to news #change !-->

    if (!isset($lng_display_id)) $lng_display_id = $languages_id;
    $affiliate_news_query = tep_db_query('select news_id, affiliate_news_headlines as headline, affiliate_news_contents as content, news_status from ' . TABLE_AFFILIATE_NEWS . ', ' . TABLE_AFFILIATE_NEWS_CONTENTS . " where news_id = affiliate_news_id and affiliate_news_languages_id = '" . $lng_display_id . "' order by date_added desc");

// <!-- npe admin end add language selection to news #change !-->

    while ($affiliate_news = tep_db_fetch_array($affiliate_news_query)) {
      $affiliate_news_count++;
      $rows++;

      if ( ((!$_GET['affiliate_news_id']) || (@$_GET['affiliate_news_id'] == $affiliate_news['news_id'])) && (!$selected_item) && (substr($_GET['action'], 0, 4) != 'new_') ) {
        $selected_item = $affiliate_news;
      }
      if ( (is_array($selected_item)) && ($affiliate_news['news_id'] == $selected_item['news_id']) ) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_AFFILIATE_NEWS, 'affiliate_news_id=' . $affiliate_news['news_id']) . (isset($_GET['lngdir']) ? '&lngdir=' . $_GET['lngdir']:'') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_AFFILIATE_NEWS, 'affiliate_news_id=' . $affiliate_news['news_id']) . (isset($_GET['lngdir']) ? '&lngdir=' . $_GET['lngdir']:'') . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '&nbsp;' . $affiliate_news['headline']; ?></td>
                <td class="dataTableContent" align="center">
<?php
      if ($affiliate_news['news_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.png', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_NEWS, 'action=setflag&flag=0&affiliate_news_id=' . $affiliate_news['news_id'] . (isset($_GET['lngdir']) ? '&lngdir=' . $_GET['lngdir']:'')) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.png', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_NEWS, 'action=setflag&flag=1&affiliate_news_id=' . $affiliate_news['news_id'])  . (isset($_GET['lngdir']) ? '&lngdir=' . $_GET['lngdir']:'') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.png', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.png', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if ($affiliate_news['news_id'] == $_GET['affiliate_news_id']) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_NEWS, 'affiliate_news_id=' . $affiliate_news['news_id']) . (isset($_GET['lngdir']) ? '&lngdir=' . $_GET['lngdir']:'') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo '<br>' . TEXT_NEWS_ITEMS . '&nbsp;' . $affiliate_news_count; ?></td>
                    <td align="right" class="smallText"><?php echo '&nbsp;<a class="button" href="' . tep_href_link(FILENAME_AFFILIATE_NEWS, 'action=new_affiliate_news'  . (isset($_GET['lngdir']) ? '&lngdir=' . $_GET['lngdir']:'')) . '">' .  IMAGE_NEW_NEWS_ITEM . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();
    switch ($_GET['action']) {
      case 'delete_affiliate_news': //generate box for confirming a news article deletion
        $heading[] = array('text'   => '<b>' . TEXT_INFO_HEADING_DELETE_ITEM . '</b>');
        
        $contents = array('form'    => tep_draw_form('news', FILENAME_AFFILIATE_NEWS, 'action=delete_affiliate_news_confirm' . (isset($_GET['lngdir']) ? '&lngdir=' . $_GET['lngdir']:'')) . tep_draw_hidden_field('affiliate_news_id', $_GET['affiliate_news_id']));
        $contents[] = array('text'  => TEXT_DELETE_ITEM_INTRO);
        $contents[] = array('text'  => '<br><b>' . $selected_item['headline'] . '</b>');
        
        $contents[] = array('align' => 'center',
                            'text'  => '<br>' . tep_image_submit('button_delete.png', IMAGE_DELETE) . ' <a class="button" href="' . tep_href_link(FILENAME_AFFILIATE_NEWS, 'affiliate_news_id=' . $selected_item['news_id']) . (isset($_GET['lngdir']) ? '&lngdir=' . $_GET['lngdir']:'') . '">' .  IMAGE_CANCEL . '</a>');
        break;

      default:
        if ($rows > 0) {
          if (is_array($selected_item)) { //an item is selected, so make the side box
            $heading[] = array('text' => '<b>' . $selected_item['headline'] . '</b>');

            $contents[] = array('align' => 'center', 
                                'text' => '<a class="button" href="' . tep_href_link(FILENAME_AFFILIATE_NEWS, 'affiliate_news_id=' . $selected_item['news_id'] . '&action=new_affiliate_news' . (isset($_GET['lngdir']) ? '&lngdir=' . $_GET['lngdir']:'') ) . '">' .  IMAGE_EDIT . '</a> <a class="button" href="' . tep_href_link(FILENAME_AFFILIATE_NEWS, 'affiliate_news_id=' . $selected_item['news_id'] . '&action=delete_affiliate_news' . (isset($_GET['lngdir']) ? '&lngdir=' . $_GET['lngdir']:'')) . '">' . IMAGE_DELETE . '</a>');
            $contents[] = array('text' => '<br>' . $selected_item['content']);
          }
        } else { // create category/product info
          $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');

          $contents[] = array('text' => sprintf(TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS, $parent_categories_name));
        }
        break;
    }

   if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td valign="top"  width="220px">' . "\n";
      

      $box = new box;
      echo $box->infoBox($heading, $contents);

      echo '            </td>' . "\n";
    }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
