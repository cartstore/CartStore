<?php

  
  define('HTTP_SERVER', 'http://localhost');
  define('HTTPS_SERVER', 'http://localhost');
  define('ENABLE_SSL', false);
  define('HTTP_COOKIE_DOMAIN', 'localhost');
  define('HTTPS_COOKIE_DOMAIN', 'localhost');
  define('HTTP_COOKIE_PATH', '/');
  define('HTTPS_COOKIE_PATH', '/');
  define('DIR_WS_HTTP_CATALOG', '/');
  define('DIR_WS_HTTPS_CATALOG', '/');
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');

  define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
  define('DIR_FS_CATALOG', 'C:/wamp/www/');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');

  define('DB_SERVER', 'localhost');
  define('DB_SERVER_USERNAME', 'root');
  define('DB_SERVER_PASSWORD', '');
  define('DB_DATABASE', 'cartstore');
  define('USE_PCONNECT', 'false');
  define('STORE_SESSIONS', 'mysql');
  define('DIR_FS_CACHE_XSELL', 'cache/');
  define('DIR_WS_RSS', DIR_WS_INCLUDES . 'modules/newsdesk/rss/');
  define('UPLOAD_PREFIX', 'upload_');
  define('DIR_WS_UPLOADS', DIR_WS_IMAGES . 'uploads/');
  define('PRODUCTS_OPTIONS_TYPE_SELECT', 0);
  define('PRODUCTS_OPTIONS_TYPE_TEXT', 1);
  define('PRODUCTS_OPTIONS_TYPE_RADIO', 2);
  define('PRODUCTS_OPTIONS_TYPE_CHECKBOX', 3);
  define('PRODUCTS_OPTIONS_TYPE_FILE', 4);
  define('PRODUCTS_OPTIONS_TYPE_TEXTAREA', 5);
  define('PRODUCTS_OPTIONS_TYPE_CALENDER', 6); 
  define('DIR_FS_UPLOADS', DIR_FS_CATALOG . DIR_WS_UPLOADS);
   define('DIR_WS_TEMPLATES', DIR_FS_CATALOG . 'templates/');
?>