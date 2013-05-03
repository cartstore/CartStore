<?php
require('includes/functions/newsdesk_general.php');
require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEWSDESK_INFO);

// set application wide parameters
// this query set is for NewsDesk

$configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_NEWSDESK_CONFIGURATION . "");
while ($configuration = tep_db_fetch_array($configuration_query)) {
	define($configuration['cfgKey'], $configuration['cfgValue']);
}

// lets retrieve all $_GET keys and values..
$get_params = tep_get_all_get_params();
$get_params_back = tep_get_all_get_params(array('reviews_id')); // for back button
$get_params = substr($get_params, 0, -1); //remove trailing &
if ($get_params_back != '') {
    $get_params_back = substr($get_params_back, 0, -1); //remove trailing &
} else {
    $get_params_back = $get_params;
}

// BOF Wolfen added code to retrieve backpath
$get_backpath = tep_get_all_get_params();
$get_backpath_back = tep_get_all_get_params(array('newdesk_id')); // for back button
$get_backpath = substr($get_backpath, 0, -15); //remove trailing &
if ($get_backpath_back != '') {
    $get_backpath_back = substr($get_backpath_back, 0, -15); //remove trailing &
} else {
    $get_backpath_back = $get_backpath;
}
// EOF Wolfen added code to retrieve backpath

// BOF Added by Wolfen
// calculate category path
if ($_GET['newsdeskPath']) {
$newsPath = $_GET['newsdeskPath'];
} elseif ($_GET['newsdesk_id'] && !$_GET['newsdeskPath']) {
$newsPath = newsdesk_get_product_path($_GET['newsdesk_id']);
} else {
$newsPath = '';
}

if (strlen($newsPath) > 0) {
$newsPath_array = newsdesk_parse_category_path($newsPath);
$newsPath = implode('_', $newsPath_array);
$current_category_id = $newsPath_array[(sizeof($newsPath_array)-1)];
} else {
$current_category_id = 0;
}

if (isset($newsPath_array)) {
$n = sizeof($newsPath_array);
for ($i = 0; $i < $n; $i++) {

$categories_query = tep_db_query(

"select categories_name from " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " where categories_id = '" . $newsPath_array[$i] 

. "' and language_id='" . $languages_id . "'"

);

if (tep_db_num_rows($categories_query) > 0) {

$categories = tep_db_fetch_array($categories_query);

$breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_NEWSDESK_INDEX, 'newsPath=' 

. implode('_', array_slice($newsPath_array, 0, ($i+1)))));

} else {

break;

}

}

} 


if ($_GET['newsdesk_id']) {
$model_query = tep_db_query("select newsdesk_article_name from " . TABLE_NEWSDESK_DESCRIPTION . " where newsdesk_id = '" . $_GET['newsdesk_id'] . "'");

$model = tep_db_fetch_array($model_query);
$breadcrumb->add($model['newsdesk_article_name'], tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdeskPath=' . $newsPath . '&newsdesk_id=' 
. $_GET['newsdesk_id']));

}
// EOF Added by Wolfen

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<?php
// BOF: Header Tag Controller v2.6.3

  require(DIR_WS_INCLUDES . 'newsdesk_header_tags.php');

?>
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
    <table>
      <tr>
        <td><div class="contentcol">
            <?php
$product_info = tep_db_query("
select p.newsdesk_id, pd.newsdesk_article_name, pd.newsdesk_article_description, pd.newsdesk_article_shorttext, 
p.newsdesk_image, p.newsdesk_image_two, p.newsdesk_image_three, pd.newsdesk_article_url, pd.newsdesk_article_url_name, pd.newsdesk_article_viewed, p.newsdesk_date_added, 
p.newsdesk_date_available 
from " . TABLE_NEWSDESK . " p, " . TABLE_NEWSDESK_DESCRIPTION . " pd where p.newsdesk_id = '" . $_GET['newsdesk_id'] . "' 
and pd.newsdesk_id = '" . $_GET['newsdesk_id'] . "' and pd.language_id = '" . $languages_id . "'");

if (!tep_db_num_rows($product_info)) { // product not found in database
?>
            <?php echo TEXT_NEWS_NOT_FOUND; ?> <a class="button" href="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><?php echo  ''.IMAGE_BUTTON_CONTINUE.''; ?></a>
            <?php
} else {
	tep_db_query("update " . TABLE_NEWSDESK_DESCRIPTION . " set newsdesk_article_viewed = newsdesk_article_viewed+1 where newsdesk_id = '" . $_GET['newsdesk_id'] . "' and language_id = '" . $languages_id . "'");
	$product_info_values = tep_db_fetch_array($product_info);

if ($product_info_values['newsdesk_image'] != '') {
if (($product_info['newsdesk_image'] != 'Array') or ($product_info['newsdesk_image'] != '')) {
$insert_image = tep_image(DIR_WS_IMAGES . $product_info_values['newsdesk_image'], $product_info_values['newsdesk_article_name'], '', '', 
'hspace="5" vspace="5"');
}
}

if ($product_info_values['newsdesk_image_two'] != '') {
if (($product_info['newsdesk_image_two'] != 'Array') or ($product_info['newsdesk_image_two'] != '')) {
$insert_image_two =  tep_image(DIR_WS_IMAGES . $product_info_values['newsdesk_image_two'], $product_info_values['newsdesk_article_name'], '', '', 
'hspace="5" vspace="5"');
}
}

if ($product_info_values['newsdesk_image_three'] != '') {
if (($product_info['newsdesk_image_three'] != 'Array') or ($product_info['newsdesk_image_three'] != '')) {
$insert_image_three = tep_image(DIR_WS_IMAGES . $product_info_values['newsdesk_image_three'], $product_info_values['newsdesk_article_name'], '', '', 
'hspace="5" vspace="5"');
}
}
?>
            <h1><?php echo $product_info_values['newsdesk_article_name']; ?></h1>
            <div class="article_desc"> <?php echo stripslashes($product_info_values['newsdesk_article_shorttext']); ?> <br>
              <br>
              <?php echo stripslashes($product_info_values['newsdesk_article_description']); ?><br>
              <br>
              <?php
if($_SERVER['HTTPS']){
echo '';
}else{
echo '<script type="text/javascript" src="http://w.sharethis.com/button/sharethis.js#publisher=d0747722-a27b-4a5e-808e-52268da6c4ad&amp;type=website"></script>';
}
?>
            </div>
          </div>
          <?php } ?>
        </td>
      </tr>
    </table>
    <!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
        <!-- right_navigation //-->
        <?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
        <!-- right_navigation_eof //-->
      </table></td>
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<?php
/*

	CartStore eCommerce Software, for The Next Generation ---- http://www.cartstore.com
	Copyright (c) 2008 Adoovo Inc. USA	GNU General Public License Compatible

	IMPORTANT NOTE:

	This script is not part of the official CartStore distribution but an add-on contributed to the CartStore community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.
	
	script name:			NewsDesk
	version:        		1.48.2
	date:       			22-06-2004 (dd/mm/yyyy)
	original author:		Carsten aka moyashi
	web site:       		www..com
	modified code by:		Wolfen aka 241
*/
?>
