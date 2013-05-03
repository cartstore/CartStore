<?php
/*
  $Id: links_setup.php,v 1.00 2003/10/02 Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  // check if links db already installed
  $links_check_query = tep_db_query("select * from configuration_group where configuration_group_title = 'Links' LIMIT 1");

  if (tep_db_num_rows($links_check_query) < 0) {
    echo 'Looks like Links Manager is not yet installed...';
    tep_exit();
  }

  $cgID = tep_db_fetch_array($links_check_query);

  //save current configuration settings for later restore  
  $links_config_query = tep_db_query("select * from configuration where configuration_group_id = '" . $cgID['configuration_group_id']. "'");

  tep_db_query("DELETE FROM configuration WHERE configuration_group_id= '" . $cgID['configuration_group_id']. "'")  or die(mysql_error()); 

  $configuration_group_id = $cgID['configuration_group_id'];

  // create configuration variables
  $config_sql_array = array(
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('<font color=blue>Click Count</font>', 'ENABLE_LINKS_COUNT', 'False', 'Enable links click count.', '" . $configuration_group_id . "', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('<font color=blue>Spider Friendly Links</font>', 'ENABLE_SPIDER_FRIENDLY_LINKS', 'True', 'Enable spider friendly links (recommended).', '" . $configuration_group_id . "', '2', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<font color=blue>Links Image Width</font>', 'LINKS_IMAGE_WIDTH', '120', 'Maximum width of the links image.', '" . $configuration_group_id . "', '3', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<font color=blue>Links Image Height</font>', 'LINKS_IMAGE_HEIGHT', '60', 'Maximum height of the links image.', '" . $configuration_group_id . "', '4', now())"),

                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<font color=green>Display Link Image</font>', 'LINK_LIST_IMAGE', '1', 'Do you want to display the Link Image?', '" . $configuration_group_id . "', '5', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<font color=green>Display Link URL</font>', 'LINK_LIST_URL', '4', 'Do you want to display the Link URL?', '" . $configuration_group_id . "', '6', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<font color=green>Display Link Title</font>', 'LINK_LIST_TITLE', '2', 'Do you want to display the Link Title?', '" . $configuration_group_id . "', '7', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<font color=green>Display Link Description</font>', 'LINK_LIST_DESCRIPTION', '3', 'Do you want to display the Link Description?', '" . $configuration_group_id . "', '8', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<font color=green>Display Link Click Count</font>', 'LINK_LIST_COUNT', '0', 'Do you want to display the Link Click Count?', '" . $configuration_group_id . "', '9', now())"),

                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<font color=fuchsia>Display English Links</font>', 'LINKS_DISPLAY_ENGLISH', 'True', 'Display links in this language in the shop.', '" . $configuration_group_id . "', '10', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<font color=fuchsia>Display German Links</font>', 'LINKS_DISPLAY_GERMAN', 'False', 'Display links in this language in the shop.', '" . $configuration_group_id . "', '11', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<font color=fuchsia>Display Spanish Links</font>', 'LINKS_DISPLAY_SPANISH', 'False', 'Display links in this language in the shop.', '" . $configuration_group_id . "', '12', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<font color=fuchsia>Display French Links</font>', 'LINKS_DISPLAY_FRENCH', 'False', 'Display links in this language in the shop.', '" . $configuration_group_id . "', '13', now())"),

                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('<font color=Brown>Display Link Title as links</font>', 'TITLES_AS_LINKS', 'False', 'Make the links title a link.', '" . $configuration_group_id . "', '14', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('<font color=Brown>Display Links Category images</font>', 'SHOW_LINKS_CATEGORIES_IMAGE', 'True', 'Display the images for the Links Categories.', '" . $configuration_group_id . "', '15', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('<font color=Brown>Display in standard format</font>', 'LINKS_DISPLAY_FORMAT_STANDARD', 'True', 'Dislay the links in the standard format (true) or in a vertical listing (false).', '" . $configuration_group_id . "', '16', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('<font color=Brown>Display Featured Link</font>', 'LINKS_FEATURED_LINK', 'True', 'Display a randomly selected link.', '" . $configuration_group_id . "', '17', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('<font color=Brown>Display Links in Categories</font>', 'LINKS_SHOW_CATEGORIES', 'True', 'Use categories to show the links. If this is disabled, all links are shown on one page.', '" . $configuration_group_id . "', '18', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('<font color=Brown>Display Link Count in Categories</font>', 'LINKS_SHOW_CATEGORIES_COUNT', 'False', 'Show the number of links in a category.', '" . $configuration_group_id . "', '19', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"),

                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<font color=purple>Link Title Minimum Length</font>', 'ENTRY_LINKS_TITLE_MIN_LENGTH', '2', 'Minimum length of link title.', '" . $configuration_group_id . "', '20', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<font color=purple>Link URL Minimum Length</font>', 'ENTRY_LINKS_URL_MIN_LENGTH', '10', 'Minimum length of link URL.', '" . $configuration_group_id . "', '21', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<font color=purple>Link Description Minimum Length</font>', 'ENTRY_LINKS_DESCRIPTION_MIN_LENGTH', '10', 'Minimum length of link description.', '" . $configuration_group_id . "', '22', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<font color=purple>Link Description Maximum Length</font>', 'ENTRY_LINKS_DESCRIPTION_MAX_LENGTH', '200', 'Maximum length of link description.', '" . $configuration_group_id . "', '23', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<font color=purple>Link Contact Name Minimum Length</font>', 'ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH', '2', 'Minimum length of link contact name.', '" . $configuration_group_id . "', '24', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<font color=purple>Link Maximum to Display</font>', 'MAX_LINKS_DISPLAY', '20', 'How many links should be displayed per page?', '" . $configuration_group_id . "', '25', now())"),

                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Links Blacklist', 'LINKS_CHECK_BLACKLIST', '', 'Do not allow links to be submitted if they contain these words. To enter more than one one, use a comma seperator, i.e., bad word a, bad word b.', '" . $configuration_group_id . "', '26', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Links Check Phrase', 'LINKS_CHECK_PHRASE', '" . $_SERVER['SERVER_NAME'] . "', 'Phrase to look for, when you perform a link check. To enter more than one phase, use a comma seperator, i.e., phase a, phase b.', '" . $configuration_group_id . "', '27', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Check Link on Edit', 'LINKS_CHECK_ON_EDIT', 'True', 'Check if a reciprocol link is valid when Edit is clicked. This will slow down the loading of the edit page a little.', '" . $configuration_group_id . "', '28', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Links open in new page', 'LINKS_OPEN_NEW_PAGE', 'True', 'Open links in new page when clicked.', '" . $configuration_group_id . "', '29', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Reciprocal Link required', 'LINKS_RECIPROCAL_REQUIRED', 'True', 'A reciprocal link is required when a link is submitted.', '" . $configuration_group_id . "', '30', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Reciprocal Link Check Count', 'LINKS_RECIPROCAL_CHECK_COUNT', '2', 'How many times a link is checked by the link_check script before it is disabled.', '" . $configuration_group_id . "', '31', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Check for Duplicate Links', 'LINKS_CHECK_DUPLICATE', 'True', 'Check if the submitted link is already on file.', '" . $configuration_group_id . "', '32', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Allow Link Editing', 'LINKS_ALLOW_EDITING', 'False', 'Set this option to true to allow link partners to edit their links.', '" . $configuration_group_id . "', '34', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"));

  foreach ($config_sql_array as $sql_array) {
    foreach ($sql_array as $value) {
    //  echo $value . '<br>';
      if (tep_db_query($value) == false) {
        $db_error = true;
      }
    }
  }
   
  if (! $db_error)
  {
    while ($links_config = tep_db_fetch_array($links_config_query))
    {
      $sql_data_array = array('configuration_value ' => $links_config['configuration_value']);
      if (false == tep_db_perform(TABLE_CONFIGURATION, $sql_data_array, 'update', "configuration_key = '" . $links_config['configuration_key'] . "'"))
        $db_error = true;
      }
  }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>"> 
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo 'Links Manager Setup'; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main">
<?php
  if ($db_error == false) {
    echo 'Links configuration settings successfully updated!!!';
  } else {
    echo 'Error encountered during database update.';
  }
?>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><?php echo '<a class="button" href="' . tep_href_link(FILENAME_DEFAULT) . '">' . IMAGE_BUTTON_CONTINUE . '</a>'; ?></td>
      </tr>
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
