<?php
/*
  $Id: affiliate_banners.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Banner Manager di affiliazione');

define('TABLE_HEADING_BANNERS', 'Banners');
define('TABLE_HEADING_GROUPS', 'Gruppi');
define('TABLE_HEADING_ACTION', 'Azione');
define('TABLE_HEADING_STATISTICS', 'Statistiche');
define('TABLE_HEADING_PRODUCT_ID', 'ID articolo');
define('TEXT_VALID_CATEGORIES_LIST', 'Lista delle categorie disponibili');
define('TEXT_VALID_CATEGORIES_ID', 'Categoria #');
define('TEXT_VALID_CATEGORIES_NAME', 'Nome delle Categorie');
define('TABLE_HEADING_CATEGORY_ID', 'Cat ID');
define('TEXT_BANNERS_LINKED_CATEGORY','ID di categoria');
define('TEXT_BANNERS_LINKED_CATEGORY_NOTE','Se vuoi linkare il banner ad una specifica categoria inserisci ID categoria. Se vuoi linkare alla pagina di default inserisci "0"');
define('TEXT_AFFILIATE_VALIDCATS', 'Clicca qui:');
define('TEXT_AFFILIATE_CATEGORY_BANNER_VIEW', 'per vedere le Categorie disponibili');
define('TEXT_AFFILIATE_CATEGORY_BANNER_HELP', 'Seleziona il numero di categoria dalla finestra popup e inseriscilo nel campo ID categoria.');

define('TEXT_BANNERS_TITLE', 'Titolo del Banner:');
define('TEXT_BANNERS_GROUP', 'Gruppo di banner:');
define('TEXT_BANNERS_NEW_GROUP', ', o inserisci un nuovo gruppo di banner sotto');
define('TEXT_BANNERS_IMAGE', 'Immagine:');
define('TEXT_BANNERS_IMAGE_LOCAL', ', o inserisci il file locale sotto');
define('TEXT_BANNERS_IMAGE_TARGET', 'Target di img (Salva in):');
define('TEXT_BANNERS_HTML_TEXT', 'Testo HTML:');
define('TEXT_AFFILIATE_BANNERS_NOTE', '<b>Banner - Note:</b><ul><li>Usa una immagine o il testo html per il banner, non entrambi.</li><li>Il testo HTML ha priorità sulla img</li></ul>');

define('TEXT_BANNERS_LINKED_PRODUCT','ID Articolo');
define('TEXT_BANNERS_LINKED_PRODUCT_NOTE','Se vuoi linkare il Banner ad uno specifico articolo inserisci ID articolo qui. Se vuoi linkarlo alla pagina di default scrivi "0"');

define('TEXT_BANNERS_DATE_ADDED', 'Data in cui è stato aggiunto:');
define('TEXT_BANNERS_STATUS_CHANGE', 'Ultima modifica: %s');

define('TEXT_AFFILIATE_VALIDPRODUCTS', 'Clicca qui:');
define('TEXT_AFFILIATE_INDIVIDUAL_BANNER_VIEW', 'per vedere gli articoli disponibili.');
define('TEXT_AFFILIATE_INDIVIDUAL_BANNER_HELP', 'Seleziona il numero di articolo dalla finestra popup e inserisci ID articolo nel campo predisposto.');

define('TEXT_VALID_PRODUCTS_LIST', 'Lista degli articoli disponibili');
define('TEXT_VALID_PRODUCTS_ID', 'Articolo #');
define('TEXT_VALID_PRODUCTS_NAME', 'Nome degli articoli');

define('TEXT_CLOSE_WINDOW', '<u>Chiudi la finestra</u> [x]');

define('TEXT_INFO_DELETE_INTRO', 'Sicuro di volere cancellare questo Banner?');
define('TEXT_INFO_DELETE_IMAGE', 'Cancella il banner');

define('SUCCESS_BANNER_INSERTED', 'Success: Il banner è stato inserito');
define('SUCCESS_BANNER_UPDATED', 'Success: Il banner è stato aggiornato');
define('SUCCESS_BANNER_REMOVED', 'Success: Il banner è stato rimosso');

define('ERROR_BANNER_TITLE_REQUIRED', 'Error: Il titolo del Banner è richiesto');
define('ERROR_BANNER_GROUP_REQUIRED', 'Error: Il gruppo del banner è richiesto');
define('ERROR_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Error: Il percorso della cartella non esiste');
define('ERROR_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Error: Il percorso della cartella non è scrivibile');
define('ERROR_IMAGE_DOES_NOT_EXIST', 'Error: Img inesistente');
define('ERROR_IMAGE_IS_NOT_WRITEABLE', 'Error: La img non può essere rimossa');
?>