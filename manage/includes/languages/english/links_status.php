<?php
/*
  $Id: links_status.php,v 1.00 2003/10/03 Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/

define('HEADING_TITLE_LINKS_STATUS', 'Links Status');
define('HEADING_TITLE_SEARCH', 'Search');
define('TEXT_HEADING_SUB_TEXT', 'This page shows the last date the url
was checked for a recipricol link. Whether the link was found is shown by the check mark or X in the left column. You can also 
change the status of the links or delete them (there\'s no warning so be careful) by clicking on the checkboxes 
in the second column, selecting the option and clicking update.  
');
define('TEXT_DISPLAY_NUMBER_OF_LINKS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> links)');
define('TEXT_CHECK_ALL', 'Check All'); 
define('TEXT_LINK_DELETE', 'Delete');
define('TEXT_LINK_FOUND', 'Link Found');
define('TEXT_LINK_STATUS', 'Status');
define('TEXT_LINK_TITLE', 'Title');
define('TEXT_LINK_URL', 'URL');
define('TEXT_LINK_LAST_DATE_CHECKED', 'Last Date Checked');
define('TEXT_NOTIFY', 'Notify:');
define('TEXT_SET_TO', 'Set To:');
define('IMAGE_BUTTON_CHECK_ALL', 'Check All');
define('IMAGE_BUTTON_UPDATE', 'Update');

define('EMAIL_TEXT_SUBJECT', 'Link Status Update');
define('EMAIL_TEXT_STATUS_UPDATE', 'Dear %s,' . "\n\n" . 'The status of your link at ' . STORE_NAME . ' has been updated.' . "\n\n" . 'New status: %s' . "\n\n" . '%s' . "\n\n" . 'Please reply to this email if you have any questions.' . "\n");

?>
