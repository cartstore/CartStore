<!-- faqdesk //-->

	<tr>
		<td>

<?php
	$heading = array();
	$contents = array();

	$heading[] = array(
		'text'  => BOX_HEADING_FAQDESK,
		'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=faqdesk'));

if ($selected_box == 'faqdesk') {
	$contents[] = array('text'  => 
//Admin begin

                                 tep_admin_files_boxes(FILENAME_FAQDESK, BOX_FAQDESK) .
                                  tep_admin_files_boxes(FILENAME_FAQDESK_REVIEWS, BOX_FAQDESK_REVIEWS));
//Admin end



$cfg_groups = '';
$configuration_groups_query = tep_db_query("
select configuration_group_id as cgID, configuration_group_title as cgTitle from " . TABLE_FAQDESK_CONFIGURATION_GROUP . " 
where visible = '1' order by sort_order
	");

while ($configuration_groups = tep_db_fetch_array($configuration_groups_query)) {
	$cfg_groups .= '
<a href="' . tep_href_link(FILENAME_FAQDESK_CONFIGURATION, 'gID=' . $configuration_groups['cgID'], 'NONSSL') . '" class="menuBoxContentLink">' . 
$configuration_groups['cgTitle'] . '</a><br>
	';
}

$contents[] = array('text'  => $cfg_groups);


}

	$box = new box;
	echo $box->menuBox($heading, $contents);
?>

		</td>
	</tr>

<!-- faqdesk_eof //-->


<?php
/*

	CartStore eCommerce Software, for The Next Generation ---- http://www.cartstore.com
	Copyright (c) 2008 Adoovo Inc. USA	GNU General Public License Compatible

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.

	script name:	FAQDesk
	version:		1.0
	date:			2003-03-27
	author:			Carsten aka moyashi
	web site:		www..com

*/
?>
