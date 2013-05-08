<?php
/*
  $Id: affiliate_newsletters.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Manager delle Newsletter per gli Affiliati');

define('TABLE_HEADING_NEWSLETTERS', 'Newsletters');
define('TABLE_HEADING_SIZE', 'Peso');
define('TABLE_HEADING_MODULE', 'Modulo');
define('TABLE_HEADING_SENT', 'Inviate');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Azione');

define('TEXT_NEWSLETTER_MODULE', 'Modulo:');
define('TEXT_NEWSLETTER_TITLE', 'Newsletter Titolo:');
define('TEXT_NEWSLETTER_CONTENT', 'Contenuto:');

define('TEXT_NEWSLETTER_DATE_ADDED', 'Aggiunta il:');
define('TEXT_NEWSLETTER_DATE_SENT', 'Spedita il:');

define('TEXT_INFO_DELETE_INTRO', 'Sicuro di voler cancellare questa newsletter?');

define('TEXT_PLEASE_WAIT', 'Per favore attendi .. sto inviando ..<br><br>Non interrompere questa operazione!');
define('TEXT_FINISHED_SENDING_EMAILS', 'Ho terminato. Newsletters inviate!');

define('ERROR_NEWSLETTER_TITLE', 'Error: Il titolo è richiesto');
define('ERROR_NEWSLETTER_MODULE', 'Error: E' richiesto il modulo');
define('ERROR_REMOVE_UNLOCKED_NEWSLETTER', 'Error: Per favore blocca la newsletter prima di cancellarla.');
define('ERROR_EDIT_UNLOCKED_NEWSLETTER', 'Error: Per favore blocca la newsletter prima di aggiornarla.');
define('ERROR_SEND_UNLOCKED_NEWSLETTER', 'Error: Per favore blocca la newsletter prima di inviarla.');
?>