<?php

  
  define('HTTP_SERVER', 'http://localhost:777');
  define('HTTP_CATALOG_SERVER', 'http://localhost:777');
  define('HTTPS_CATALOG_SERVER', 'http://localhost:777');
  define('ENABLE_SSL_CATALOG', 'false');
  define('DIR_FS_DOCUMENT_ROOT', 'C:/wamp/www/');
  define('DIR_WS_ADMIN', '/cartstoreadmin/');
  define('DIR_FS_ADMIN', 'C:/wamp/www/cartstoreadmin/');
  define('DIR_WS_CATALOG', '/');
  define('DIR_FS_CATALOG', 'C:/wamp/www/');
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
  
  define('DB_SERVER_USERNAME', 'root');
  
  define('DB_SERVER_PASSWORD', '');
  
  define('DB_DATABASE', 'cartstore');
  define('USE_PCONNECT', 'false');
  define('STORE_SESSIONS', 'mysql');
  define('PRODUCTS_OPTIONS_TYPE_TEXT', 1);
  define('PRODUCTS_OPTIONS_TYPE_RADIO', 2);
  define('PRODUCTS_OPTIONS_TYPE_CHECKBOX', 3);
  define('PRODUCTS_OPTIONS_TYPE_FILE', 4);
  define('PRODUCTS_OPTIONS_TYPE_TEXTAREA', 5); 
  define('PRODUCTS_OPTIONS_TYPE_CALENDER', 6); 
   define('DIR_WS_TEMPLATES', DIR_FS_CATALOG . 'templates/');
?>