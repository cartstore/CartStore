<?php
/*
  $Id: affiliate_payment.php,v v 2.00 2003/10/12

  OSC-Affiliate
  
  Contribution based on:
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Pagamento degli Affiliati');
define('HEADING_TITLE_SEARCH', 'Cerca:');
define('HEADING_TITLE_STATUS','Status:');

define('TEXT_ALL_PAYMENTS','Tutti i pagamenti');
define('TEXT_NO_PAYMENT_HISTORY', 'Nessun archivio di pagamenti disponibile');


define('TABLE_HEADING_ACTION', 'Azione');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_AFILIATE_NAME', 'Affiliato');
define('TABLE_HEADING_PAYMENT','Pagamento (incl.)');
define('TABLE_HEADING_NET_PAYMENT','Pagamento (excl.)');
define('TABLE_HEADING_DATE_BILLED','Data di fatturazione');
define('TABLE_HEADING_NEW_VALUE', 'Nuovo valore');
define('TABLE_HEADING_OLD_VALUE', 'Valore precedente');
define('TABLE_HEADING_AFFILIATE_NOTIFIED', 'Affiliato notificato');
define('TABLE_HEADING_DATE_ADDED', 'Data di aggiunta');

define('TEXT_DATE_PAYMENT_BILLED','Fatturato:');
define('TEXT_DATE_ORDER_LAST_MODIFIED','Ultima modifica:');
define('TEXT_AFFILIATE_PAYMENT','Pagamento del guadagno del affiliato');
define('TEXT_AFFILIATE_BILLED','Giorno di pagamento');
define('TEXT_AFFILIATE','Affiliato');
define('TEXT_INFO_DELETE_INTRO','Sicuro di voler cancellare questo pagamento?');
define('TEXT_DISPLAY_NUMBER_OF_PAYMENTS', 'Mostra da <b>%d</b> a <b>%d</b> (di <b>%d</b> pagamenti)');

define('TEXT_AFFILIATE_PAYING_POSSIBILITIES','Puoi pagare il tuo Affiliata con:');
define('TEXT_AFFILIATE_PAYMENT_CHECK','Check:');
define('TEXT_AFFILIATE_PAYMENT_CHECK_PAYEE','Pagabile a:');
define('TEXT_AFFILIATE_PAYMENT_PAYPAL','PayPal:');
define('TEXT_AFFILIATE_PAYMENT_PAYPAL_EMAIL','Acount Email PayPal:');
define('TEXT_AFFILIATE_PAYMENT_BANK_TRANSFER','Bonifico:');
define('TEXT_AFFILIATE_PAYMENT_BANK_NAME','Nome della Banca:');
define('TEXT_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME','Intestazione:');
define('TEXT_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER','Numero del conto:');
define('TEXT_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER','CAB:');
define('TEXT_AFFILIATE_PAYMENT_BANK_SWIFT_CODE','ABI:');

define('TEXT_INFO_HEADING_DELETE_PAYMENT','Cancella Pagamento');

define('IMAGE_AFFILIATE_BILLING','Comincia la procedura di fatturazione');

define('ERROR_PAYMENT_DOES_NOT_EXIST','Il pagamento non esiste');


define('SUCCESS_BILLING','I tuoi affiliati sono stati fatturati con successo');
define('SUCCESS_PAYMENT_UPDATED','Lo status dei pagamenti è stato aggiornato');

define('PAYMENT_STATUS','Status Pagamento');
define('PAYMENT_NOTIFY_AFFILIATE', 'Notifica a un affiliato');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Aggiornamento del pagamento');
define('EMAIL_TEXT_AFFILIATE_PAYMENT_NUMBER', 'Pagamento numero:');
define('EMAIL_TEXT_INVOICE_URL', 'Dettagli fattura:');
define('EMAIL_TEXT_PAYMENT_BILLED', 'Data fattura');
define('EMAIL_TEXT_STATUS_UPDATE', 'Il tuo pagamento è stato aggiornato al seguente status.' . "\n\n" . 'Nuovo status: %s' . "\n\n" . 'Ti prego di rispondere a questa mail se hai domande' . "\n");
define('EMAIL_TEXT_NEW_PAYMENT', 'Una nuova fattura dei tuoi pagamenti' . "\n");
?>
