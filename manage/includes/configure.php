<?php
  define('HTTP_SERVER', 'http://dev.cartstore.com');
  define('HTTP_CATALOG_SERVER', 'http://dev.cartstore.com');
  define('HTTPS_CATALOG_SERVER', 'http://dev.cartstore.com');
  define('ENABLE_SSL_CATALOG', 'false');
  define('DIR_FS_DOCUMENT_ROOT', '/home/devcarts/public_html/');
  define('DIR_WS_ADMIN', '/manage/');
  define('DIR_FS_ADMIN', '/home/devcarts/public_html/manage/');
  define('DIR_WS_CATALOG', '/');
  define('DIR_FS_CATALOG', '/home/devcarts/public_html/');
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'images/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
  define('DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
  define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');
  define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');
  define('DIR_FS_MAIL_DUMPS', DIR_FS_ADMIN . 'maildumps/');

  define('DB_SERVER', 'localhost');
  define('DB_SERVER_USERNAME', 'devcarts_dbase');
  define('DB_SERVER_PASSWORD', '2b^ovkuT9K}q');
  define('DB_DATABASE', 'devcarts_79');
  define('USE_PCONNECT', 'false');
  define('STORE_SESSIONS', 'mysql');
  
  define('PRODUCTS_OPTIONS_TYPE_TEXT', 1);
  define('PRODUCTS_OPTIONS_TYPE_RADIO', 2);
  define('PRODUCTS_OPTIONS_TYPE_CHECKBOX', 3);
  define('PRODUCTS_OPTIONS_TYPE_FILE', 4);
  define('PRODUCTS_OPTIONS_TYPE_TEXTAREA', 5);
  define('PRODUCTS_OPTIONS_TYPE_CALENDER', 6);
  define('DIR_WS_TEMPLATES', DIR_FS_CATALOG . 'templates/');
  define('SR_ENABLED', true);
  define('SR_APITOKEN', "CSpKz0XrhEWLDzwql5KsTA");
  define('SR_APISECRET', "KfF_e0Ar_E2F7b4QgHDglw");
  define('SR_ENDPOINT', "http://platform.social-runner.com/API/");

?>
