<!-- newsdesk //-->

	<tr>
		<td>

<?php
	$heading = array();
	$contents = array();

	$heading[] = array(
		'text'  => BOX_HEADING_NEWSDESK,
		'link'  => tep_href_link('newsdesk.php', 'selected_box=newsdesk'));
		
if ($selected_box == 'newsdesk') {
	//$contents[] = array('text'  => 
//Admin begin
                                 // tep_admin_files_boxes(FILENAME_NEWSDESK, BOX_NEWSDESK) .
                                  //tep_admin_files_boxes(FILENAME_NEWSDESK_REVIEWS, BOX_NEWSDESK_REVIEWS));
//Admin end

$cfg_groups = '';
$configuration_groups_query = tep_db_query("
select configuration_group_id as cgID, configuration_group_title as cgTitle from " . TABLE_NEWSDESK_CONFIGURATION_GROUP . " 
where visible = '1' order by sort_order
	");

while ($configuration_groups = tep_db_fetch_array($configuration_groups_query)) {
	$cfg_groups .= '
<a href="' . tep_href_link('newsdesk.php', 'newsdesk=newsdesk', 'NONSSL') . '" class="menuBoxContentLink">' . 
$configuration_groups['cgTitle'] . '</a><br>
	';
}

//$contents[] = array('text'  => $cfg_groups);


}

	$box = new box;
	echo $box->menuBox($heading, $contents);
?>

		</td>
	</tr>

<!-- newsdesk_eof //-->


<?php 
/*

	CartStore eCommerce Software, for The Next Generation ---- http://www.cartstore.com
	Copyright (c) 2008 Adoovo Inc. USA	GNU General Public License Compatible

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.
	
	script name:			NewsDesk
	version:        		1.48.2
	date:       			22-06-2004 (dd/mm/yyyy)
	original author:		Carsten aka moyashi
	web site:       		www..com
	modified code by:		Wolfen aka 241
*/
?>