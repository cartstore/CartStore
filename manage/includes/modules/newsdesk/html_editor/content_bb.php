<table class="dataTableRowSelected" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="">


<tr>
<td width="100%" valign="top">

<?php
echo newsdesk_draw_textarea_field('newsdesk_article_description_' . $languages[$i]['id'] . '', 'soft', '50', '15', stripbr((($newsdesk_article_description[$languages[$i]['id']]) ? stripslashes($newsdesk_article_description[$languages[$i]['id']]) : newsdesk_get_newsdesk_article_description($pInfo->newsdesk_id, $languages[$i]['id']))));
?>

		</td>
	</tr>
</table>

<?php
/*

	CartStore eCommerce Software, for The Next Generation ---- http://www.cartstore.com
	Copyright (c) 2008 Adoovo Inc. USA	GNU General Public License Compatible

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.	script name:		NewsDesk
	version:        		1.48.2
	date:       			06-05-2004 (dd/mm/yyyy)
	original author:		Carsten aka moyashi
	web site:       		www..com
	modified code by:		Wolfen aka 241

*/
?>