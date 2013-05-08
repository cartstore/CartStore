<?php
/*
  $Id: admin_members.php,v 1.13 2002/08/19 01:45:58 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/

if ($_GET['gID']) {
  define('HEADING_TITLE', 'Admin Access');
} elseif ($_GET['gPath']) {
  define('HEADING_TITLE', 'Define Admin Access');
} else {
  define('HEADING_TITLE', 'Admin Members');
}

define('TEXT_COUNT_GROUPS', 'Admin(s): ');

define('TABLE_HEADING_NAME', 'Name');
define('TABLE_HEADING_EMAIL', 'Email Address');
define('TABLE_HEADING_PASSWORD', 'Password');
define('TABLE_HEADING_CONFIRM', 'Confirm Password');
define('TABLE_HEADING_GROUPS', 'Admin Level');
define('TABLE_HEADING_CREATED', 'Account Created');
define('TABLE_HEADING_MODIFIED', 'Account Created');
define('TABLE_HEADING_LOGDATE', 'Last Access');
define('TABLE_HEADING_LOGNUM', 'LogNum');
define('TABLE_HEADING_LOG_NUM', 'Log Number');
define('TABLE_HEADING_ACTION', 'Action');

define('TABLE_HEADING_GROUPS_NAME', 'Admin Name');
define('TABLE_HEADING_GROUPS_DEFINE', 'Boxes and Files Selection');
define('TABLE_HEADING_GROUPS_GROUP', 'Level');
define('TABLE_HEADING_GROUPS_CATEGORIES', 'Categories Permission');


define('TEXT_INFO_HEADING_DEFAULT', 'Admin Member ');
define('TEXT_INFO_HEADING_DELETE', 'Delete Permission ');
define('TEXT_INFO_HEADING_EDIT', 'Edit Category / ');
define('TEXT_INFO_HEADING_NEW', 'New Admin Member ');

define('TEXT_INFO_DEFAULT_INTRO', 'Member group');
define('TEXT_INFO_DELETE_INTRO', 'Remove <nobr><b>%s</b></nobr> from <nobr>Admin Members?</nobr>');
define('TEXT_INFO_DELETE_INTRO_NOT', 'You can not delete <nobr>%s group!</nobr>');
define('TEXT_INFO_EDIT_INTRO', 'Set permission level here: ');

define('TEXT_INFO_FULLNAME', 'Name: ');
define('TEXT_INFO_FIRSTNAME', 'Firstname: ');
define('TEXT_INFO_LASTNAME', 'Lastname: ');
define('TEXT_INFO_EMAIL', 'Email Address: ');
define('TEXT_INFO_PASSWORD', 'Password: ');
define('TEXT_INFO_CONFIRM', 'Confirm Password: ');
define('TEXT_INFO_CREATED', 'Account Created: ');
define('TEXT_INFO_MODIFIED', 'Account Modified: ');
define('TEXT_INFO_LOGDATE', 'Last Access: ');
define('TEXT_INFO_LOGNUM', 'Log Number: ');
define('TEXT_INFO_GROUP', 'Group Level: ');
define('TEXT_INFO_ERROR', '<font color="red">Email address has already been used! Please try again.</font>');

define('JS_ALERT_FIRSTNAME', '- Required: Firstname \n');
define('JS_ALERT_LASTNAME', '- Required: Lastname \n');
define('JS_ALERT_EMAIL', '- Required: Email address \n');
define('JS_ALERT_EMAIL_FORMAT', '- Email address format is invalid! \n');
define('JS_ALERT_EMAIL_USED', '- Email address has already been used! \n');
define('JS_ALERT_LEVEL', '- Required: Group Member \n');

define('ADMIN_EMAIL_SUBJECT', 'New Admin Member');
define('ADMIN_EMAIL_TEXT', 'Hi %s,' . "\n\n" . 'You can access the admin panel with the following password. Once you access the admin, please change your password!' . "\n\n" . 'Website : %s' . "\n" . 'Username: %s' . "\n" . 'Password: %s' . "\n\n" . 'Thanks!' . "\n" . '%s' . "\n\n" . 'This is an automated response, please do not reply!');
define('ADMIN_EMAIL_TEXT_EDIT', 'Hi %s,' . "\n\n" . 'Your password has been changed for access to the admin panel.' . "\n\n" . 'Website : %s' . "\n" . 'Username: %s' . "\n" . 'Password: %s' . "\n\n" . 'Thanks!' . "\n" . '%s' . "\n\n" . 'This is an automated response, please do not reply!');

define('TEXT_INFO_HEADING_DEFAULT_GROUPS', 'Admin Group ');
define('TEXT_INFO_HEADING_DELETE_GROUPS', 'Delete Group ');

define('TEXT_INFO_DEFAULT_GROUPS_INTRO', '<b>NOTE:</b><li><b>edit:</b> edit group name.</li><li><b>delete:</b> delete group.</li><li><b>define:</b> define group access.</li>');
define('TEXT_INFO_DELETE_GROUPS_INTRO', 'It\'s also will delete member of this group. Are you sure want to delete <nobr><b>%s</b> group?</nobr>');
define('TEXT_INFO_DELETE_GROUPS_INTRO_NOT', 'You can not change permissions for this admin!');
define('TEXT_INFO_GROUPS_INTRO', 'Give an unique group name. Click next to submit.');

define('TEXT_INFO_HEADING_GROUPS', 'New Group');
define('TEXT_INFO_GROUPS_NAME', ' <b>Group Name:</b><br>Give an unique group name. Then, click next to submit.<br>');
define('TEXT_INFO_GROUPS_NAME_FALSE', '<font color="red"><b>ERROR:</b> At least the group name must have more than 5 character!</font>');
define('TEXT_INFO_GROUPS_NAME_USED', '<font color="red"><b>ERROR:</b> Group name has already been used!</font>');
define('TEXT_INFO_GROUPS_LEVEL', 'Group Level: ');
define('TEXT_INFO_GROUPS_BOXES', '<b>Boxes Permission:</b><br>Give access to selected boxes.');
define('TEXT_INFO_GROUPS_BOXES_INCLUDE', 'Include files stored in: ');

define('TEXT_INFO_HEADING_DEFINE', 'Define Admin Access');
if ($_GET['gPath'] == 1) {
  define('TEXT_INFO_DEFINE_INTRO', '<b>%s :</b><br>You can not change file permission for this group.<br><br>');
} else {
  define('TEXT_INFO_DEFINE_INTRO', '<b>%s :</b><br>Change permission for this admin by selecting or unselecting boxes and files provided. <br>After that, give him specific access to selected files using <b>add access value</b> link. Click <b>save</b> to save the changes.<br><br>');
}
//Mett
define('TEXT_ADD_ACCESS_VALUE', 'add access value');
define('TEXT_EDIT_ACCESS_VALUE', 'edit access value');
define('TEXT_DELETE_ACCESS_VALUE', 'Delete access value (reset)');
define('TEXT_SELECT_VALUE', 'Select access type');
define('TEXT_INFO_PAGE', 'Page to edit:');
define('TEXT_INFO_NO_RESTRICTIONS', 'No restrictions');
define('TEXT_INFO_PARTIAL_ACCESS', 'Partial access');
define('TEXT_INFO_READ_ONLY', 'Read only');
define('TEXT_INFO_FORBIDDEN', 'Forbidden');
define('TEXT_INFO_RESET', 'Reset access value');

// end Mett

?>
