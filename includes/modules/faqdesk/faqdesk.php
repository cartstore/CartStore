<!-- faqdesk //-->
<?php


// set application wide parameters
// this query set is for FAQDesk

$configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_FAQDESK_CONFIGURATION . "");
while ($configuration = tep_db_fetch_array($configuration_query)) {
	define($configuration['cfgKey'], $configuration['cfgValue']);
}

$faqdesk_var_query = tep_db_query('select p.faqdesk_id, pd.language_id, pd.faqdesk_question, pd.faqdesk_answer_long, pd.faqdesk_answer_short, pd.faqdesk_extra_url, pd.faqdesk_extra_url_name, 
p.faqdesk_image, p.faqdesk_image_two, p.faqdesk_image_three, p.faqdesk_date_added, p.faqdesk_last_modified, pd.faqdesk_extra_viewed, 
p.faqdesk_date_available, p.faqdesk_status  from ' . TABLE_FAQDESK . ' p, ' . TABLE_FAQDESK_DESCRIPTION . ' 
pd WHERE pd.faqdesk_id = p.faqdesk_id and pd.language_id = "' . $languages_id . '" and faqdesk_status = 1 and p.faqdesk_sticky = 0 ORDER BY faqdesk_date_added DESC LIMIT ' . MAX_DISPLAY_FAQDESK_FAQS);


if (!tep_db_num_rows($faqdesk_var_query)) { // there is no news
	echo '<!-- ' . TEXT_NO_FAQDESK_NEWS . ' -->';

} else {

	$info_box_contents = array();
	$info_box_contents[] = array('align' => 'left',
                                 'text'  => TABLE_HEADING_FAQDESK);
	new contentBoxHeading($info_box_contents);

	$info_box_contents = array();
	$row = 0;
	while ($faqdesk_var = tep_db_fetch_array($faqdesk_var_query)) {


if ( DISPLAY_FAQDESK_IMAGE ) {
if ($faqdesk_var['faqdesk_image'] != '') {
$insert_image = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
<a href="' . tep_href_link(FILENAME_FAQDESK_INFO, 'faqdesk_id=' . $faqdesk_var['faqdesk_id']) . '">' . tep_image(DIR_WS_IMAGES . 
$faqdesk_var['faqdesk_image'], '', '') . '</a>
		</td>
	</tr>
</table>
';
}
}

if ( DISPLAY_FAQDESK_IMAGE_TWO ) {
if ($faqdesk_var['faqdesk_image_two'] != '') {
$insert_image_two = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
<a href="' . tep_href_link(FILENAME_FAQDESK_INFO, 'faqdesk_id=' . $faqdesk_var['faqdesk_id']) . '">' . tep_image(DIR_WS_IMAGES . 
$faqdesk_var['faqdesk_image_two'], '', '') . '</a>
		</td>
	</tr>
</table>
';
}
}

if ( DISPLAY_FAQDESK_IMAGE_THREE ) {
if ($faqdesk_var['faqdesk_image_three'] != '') {
$insert_image_three = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
<a href="' . tep_href_link(FILENAME_FAQDESK_INFO, 'faqdesk_id=' . $faqdesk_var['faqdesk_id']) . '">' . tep_image(DIR_WS_IMAGES . 
$faqdesk_var['faqdesk_image_three'], '', '') . '</a>
		</td>
	</tr>
</table>
';
}
}


if ( DISPLAY_FAQDESK_VIEWCOUNT ) {
$insert_viewcount = '<i>' . TEXT_FAQDESK_VIEWED . $faqdesk_var['faqdesk_extra_viewed'] . '</i>';
}

if ( DISPLAY_FAQDESK_READMORE ) {
$insert_readmore = '<a class="smallText" href="' . tep_href_link(FILENAME_FAQDESK_INFO, 'faqdesk_id=' . $faqdesk_var['faqdesk_id']) . '">[' . TEXT_FAQDESK_READMORE . 
']</a>';
}

if ( DISPLAY_FAQDESK_SHORT_ANSWER ) {
$insert_summary = '<b>'. $faqdesk_var['faqdesk_answer_short'] . '</b>';
}

if ( DISPLAY_FAQDESK_QUESTION ) {
$insert_headline = '<h1>' . $faqdesk_var['faqdesk_question'] . '</h1>';
}

if ( DISPLAY_FAQDESK_DATE ) {
$insert_date = '- <i>' . tep_date_long($faqdesk_var['faqdesk_date_added']) . '</i>';
}


		$info_box_contents[$row] = array(
			'align' => 'left',
			'params' => 'class="smallText" valign="top"',
			'text' => '
<table border="0" width="100%" cellspacing="3" cellpadding="0">
	<tr>
		<td class="faqtitle" colspan="2"><h1>
' . $insert_headline .  '
		</h1></td>
		<td class="smallText" colspan="2" align="right">' . $insert_viewcount . '</td>
	</tr>
	<tr>
		<td class="headerNavigation" colspan="3">' . tep_draw_separator('pixel_trans.gif', '100%', '1') . '</td>
	</tr>
	<tr>
		<td colspan="3">' . tep_draw_separator('pixel_trans.gif', '100%', '5') . '</td>
	</tr>
</table>

<table border="0" width="100%" cellspacing="3" cellpadding="0">
	<tr>
		<td valign="top" width="">


<table border="0" width="100%" cellspacing="3" cellpadding="0">
	<tr>
		<td class="smallText">' . $insert_summary . '</td>
	</tr>
	<tr>
		<td>' . tep_draw_separator('pixel_trans.gif', '100%', '10') . '</td>
	</tr>
	<tr>
		<td class="smallText">' . $insert_readmore . '</td>
	</tr>
</table>

		</td>
		<td valign="top" align="right">

' . $insert_image . '
' . $insert_image_two . '
' . $insert_image_three . '

		</td>
	</tr>
</table>


<table border="0" width="100%" cellspacing="3" cellpadding="0">
	<tr>
		<td colspan="2">' . tep_draw_separator('pixel_trans.gif', '100%', '5') . '</td>
	</tr>
</table>
'

		);
$insert_image = '';
$insert_image_two = '';
$insert_image_three = '';
		$row++;
	}

	new contentBox($info_box_contents);

}
?>
<!-- faqdesk_eof //-->

<?php
/*

	CartStore eCommerce Software, for The Next Generation ---- http://www.cartstore.com
	Copyright (c) 2008 Adoovo Inc. USA	GNU General Public License Compatible

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.
	
	script name:			FAQDesk
	version:        		1.01.0
	date:       			22-06-2004 (dd/mm/yyyy)
	original author:		Carsten aka moyashi
	web site:       		www..com
	modified code by:		Wolfen aka 241
*/
?>
