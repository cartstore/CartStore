<?php
  require('../includes/database_tables.php');
  osc_db_connect(trim($_POST['DB_SERVER']), trim($_POST['DB_SERVER_USERNAME']), trim($_POST['DB_SERVER_PASSWORD']));
  osc_db_select_db(trim($_POST['DB_DATABASE']));
  osc_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . trim($_POST['CFG_STORE_NAME']) . '" where configuration_key = "STORE_NAME"');
  osc_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . trim($_POST['CFG_STORE_OWNER_NAME']) . '" where configuration_key = "STORE_OWNER"');
  osc_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . trim($_POST['CFG_STORE_OWNER_EMAIL_ADDRESS']) . '" where configuration_key = "STORE_OWNER_EMAIL_ADDRESS"');
  if (!empty($_POST['CFG_STORE_OWNER_NAME']) && !empty($_POST['CFG_STORE_OWNER_EMAIL_ADDRESS'])) {
      osc_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "\"' . trim($_POST['CFG_STORE_OWNER_NAME']) . '\" <' . trim($_POST['CFG_STORE_OWNER_EMAIL_ADDRESS']) . '>" where configuration_key = "EMAIL_FROM"');
  }
  if (!empty($_POST['CFG_STORE_OWNER_EMAIL_ADDRESS'])) {
      osc_db_query('update ' . TABLE_ADMINISTRATORS . ' set admin_password  = "' . osc_encrypt_string(trim($_POST['CFG_ADMINISTRATOR_PASSWORD'])) . '" where  1');
      osc_db_query('update ' . TABLE_ADMINISTRATORS . ' set admin_email_address = "' . trim($_POST['CFG_STORE_OWNER_EMAIL_ADDRESS']) . '" where  1');
  }
?>

<div class="mainBlock">
  <div class="stepsBox">
    <ol>
      <li>Database Server</li>
      <li>Web Server</li>
      <li>Online Store Settings</li>
      <li style="font-weight: bold;">Finished!</li>
    </ol>
  </div>
  <h1>New Installation</h1>
  <p>This web-based installation routine will correctly setup and configure CartStore to run on this server.</p>
  <p>Please follow the on-screen instructions that will take you through the database server, web server, and store configuration options. If help is needed at any stage, please consult the documentation or seek help at the community support forums.</p>
</div>
<div class="contentBlock">
  <div class="infoPane">
    <h3>Step 4: Finished!</h3>
    <div class="infoPaneContents">
      <p>Congratulations on installing and configuring CartStore as your online store solution!</p>
      <p>We wish you all the best with the success of your online store and welcome you to join and participate in our community.</p>
      <p align="right">- The CartStore Team</p>
    </div>
  </div>
  <div class="contentPane">
    <h2>Finished!</h2>
    <?php
  $dir_fs_document_root = $_POST['DIR_FS_DOCUMENT_ROOT'];
  if ((substr($dir_fs_document_root, -1) != '\\') && (substr($dir_fs_document_root, -1) != '/')) {
      if (strrpos($dir_fs_document_root, '\\') !== false) {
          $dir_fs_document_root .= '\\';
      } else {
          $dir_fs_document_root .= '/';
      }
  }
  $http_url = parse_url($_POST['HTTP_WWW_ADDRESS']);
  $http_server = $http_url['scheme'] . '://' . $http_url['host'];
  $http_catalog = $http_url['path'];
  if (isset($http_url['port']) && !empty($http_url['port'])) {
      $http_server .= ':' . $http_url['port'];
  }
  if (substr($http_catalog, -1) != '/') {
      $http_catalog .= '/';
  }
  $file_contents = '<?php

  ' . "\n" . '  define(\'HTTP_SERVER\', \'' . $http_server . '\');' . "\n" . '  define(\'HTTPS_SERVER\', \'' . $http_server . '\');' . "\n" . '  define(\'ENABLE_SSL\', false);' . "\n" . '  define(\'HTTP_COOKIE_DOMAIN\', \'' . $http_url['host'] . '\');' . "\n" . '  define(\'HTTPS_COOKIE_DOMAIN\', \'' . $http_url['host'] . '\');' . "\n" . '  define(\'HTTP_COOKIE_PATH\', \'' . $http_catalog . '\');' . "\n" . '  define(\'HTTPS_COOKIE_PATH\', \'' . $http_catalog . '\');' . "\n" . '  define(\'DIR_WS_HTTP_CATALOG\', \'' . $http_catalog . '\');' . "\n" . '  define(\'DIR_WS_HTTPS_CATALOG\', \'' . $http_catalog . '\');' . "\n" . '  define(\'DIR_WS_IMAGES\', \'images/\');' . "\n" . '  define(\'DIR_WS_ICONS\', DIR_WS_IMAGES . \'icons/\');' . "\n" . '  define(\'DIR_WS_INCLUDES\', \'includes/\');' . "\n" . '  define(\'DIR_WS_BOXES\', DIR_WS_INCLUDES . \'boxes/\');' . "\n" . '  define(\'DIR_WS_FUNCTIONS\', DIR_WS_INCLUDES . \'functions/\');' . "\n" . '  define(\'DIR_WS_CLASSES\', DIR_WS_INCLUDES . \'classes/\');' . "\n" . '  define(\'DIR_WS_MODULES\', DIR_WS_INCLUDES . \'modules/\');' . "\n" . '  define(\'DIR_WS_LANGUAGES\', DIR_WS_INCLUDES . \'languages/\');' . "\n\n" . '  define(\'DIR_WS_DOWNLOAD_PUBLIC\', \'pub/\');' . "\n" . '  define(\'DIR_FS_CATALOG\', \'' . $dir_fs_document_root . '\');' . "\n" . '  define(\'DIR_FS_DOWNLOAD\', DIR_FS_CATALOG . \'download/\');' . "\n" . '  define(\'DIR_FS_DOWNLOAD_PUBLIC\', DIR_FS_CATALOG . \'pub/\');' . "\n\n" . '  define(\'DB_SERVER\', \'' . trim($_POST['DB_SERVER']) . '\');' . "\n" . '  define(\'DB_SERVER_USERNAME\', \'' . trim($_POST['DB_SERVER_USERNAME']) . '\');' . "\n" . '  define(\'DB_SERVER_PASSWORD\', \'' . trim($_POST['DB_SERVER_PASSWORD']) . '\');' . "\n" . '  define(\'DB_DATABASE\', \'' . trim($_POST['DB_DATABASE']) . '\');' . "\n" . '  define(\'USE_PCONNECT\', \'false\');' . "\n" . '  define(\'STORE_SESSIONS\', \'mysql\');' . "\n" . '  define(\'DIR_FS_CACHE_XSELL\', \'cache/\');' . "\n" . '  define(\'DIR_WS_RSS\', DIR_WS_INCLUDES . \'modules/newsdesk/rss/\');' . "\n" . '  define(\'UPLOAD_PREFIX\', \'upload_\');' . "\n" . '  define(\'DIR_WS_UPLOADS\', DIR_WS_IMAGES . \'uploads/\');' . "\n" . '  define(\'PRODUCTS_OPTIONS_TYPE_SELECT\', 0);' . "\n" . '  define(\'PRODUCTS_OPTIONS_TYPE_TEXT\', 1);' . "\n" . '  define(\'PRODUCTS_OPTIONS_TYPE_RADIO\', 2);' . "\n" . '  define(\'PRODUCTS_OPTIONS_TYPE_CHECKBOX\', 3);' . "\n" . '  define(\'PRODUCTS_OPTIONS_TYPE_FILE\', 4);' . "\n" . '  define(\'PRODUCTS_OPTIONS_TYPE_TEXTAREA\', 5);' . "\n" . '  define(\'PRODUCTS_OPTIONS_TYPE_CALENDER\', 6); ' . "\n" . '  define(\'DIR_FS_UPLOADS\', DIR_FS_CATALOG . DIR_WS_UPLOADS);' . "\n" . '   define(\'DIR_WS_TEMPLATES\', DIR_FS_CATALOG . \'templates/\');' . "\n" . '?>';
  $fp = fopen($dir_fs_document_root . 'includes/configure.php', 'w');
  fputs($fp, $file_contents);
  fclose($fp);
  $file_contents = '<?php

  ' . "\n" . '  define(\'HTTP_SERVER\', \'' . $http_server . '\');' . "\n" . '  define(\'HTTP_CATALOG_SERVER\', \'' . $http_server . '\');' . "\n" . '  define(\'HTTPS_CATALOG_SERVER\', \'' . $http_server . '\');' . "\n" . '  define(\'ENABLE_SSL_CATALOG\', \'false\');' . "\n" . '  define(\'DIR_FS_DOCUMENT_ROOT\', \'' . $dir_fs_document_root . '\');' . "\n" . '  define(\'DIR_WS_ADMIN\', \'' . $http_catalog . 'cartstoreadmin/\');' . "\n" . '  define(\'DIR_FS_ADMIN\', \'' . $dir_fs_document_root . 'cartstoreadmin/\');' . "\n" . '  define(\'DIR_WS_CATALOG\', \'' . $http_catalog . '\');' . "\n" . '  define(\'DIR_FS_CATALOG\', \'' . $dir_fs_document_root . '\');' . "\n" . '  define(\'DIR_WS_IMAGES\', \'images/\');' . "\n" . '  define(\'DIR_WS_ICONS\', DIR_WS_IMAGES . \'icons/\');' . "\n" . '  define(\'DIR_WS_CATALOG_IMAGES\', DIR_WS_CATALOG . \'images/\');' . "\n" . '  define(\'DIR_WS_INCLUDES\', \'includes/\');' . "\n" . '  define(\'DIR_WS_BOXES\', DIR_WS_INCLUDES . \'boxes/\');' . "\n" . '  define(\'DIR_WS_FUNCTIONS\', DIR_WS_INCLUDES . \'functions/\');' . "\n" . '  define(\'DIR_WS_CLASSES\', DIR_WS_INCLUDES . \'classes/\');' . "\n" . '  define(\'DIR_WS_MODULES\', DIR_WS_INCLUDES . \'modules/\');' . "\n" . '  define(\'DIR_WS_LANGUAGES\', DIR_WS_INCLUDES . \'languages/\');' . "\n" . '  define(\'DIR_WS_CATALOG_LANGUAGES\', DIR_WS_CATALOG . \'includes/languages/\');' . "\n" . '  define(\'DIR_FS_CATALOG_LANGUAGES\', DIR_FS_CATALOG . \'includes/languages/\');' . "\n" . '  define(\'DIR_FS_CATALOG_IMAGES\', DIR_FS_CATALOG . \'images/\');' . "\n" . '  define(\'DIR_FS_CATALOG_MODULES\', DIR_FS_CATALOG . \'includes/modules/\');
  ' . "\n" . '  define(\'DIR_FS_BACKUP\', DIR_FS_ADMIN . \'backups/\');
   ' . "\n" . '  define(\'DIR_FS_MAIL_DUMPS\', DIR_FS_ADMIN . \'maildumps/\');
 
  
  
  ' . "\n\n" . '  define(\'DB_SERVER\', \'' . trim($_POST['DB_SERVER']) . '\');
  ' . "\n" . '  define(\'DB_SERVER_USERNAME\', \'' . trim($_POST['DB_SERVER_USERNAME']) . '\');
  ' . "\n" . '  define(\'DB_SERVER_PASSWORD\', \'' . trim($_POST['DB_SERVER_PASSWORD']) . '\');
  ' . "\n" . '  define(\'DB_DATABASE\', \'' . trim($_POST['DB_DATABASE']) . '\');' . "\n" . '  define(\'USE_PCONNECT\', \'false\');' . "\n" . '  define(\'STORE_SESSIONS\', \'mysql\');' . "\n" . '  define(\'PRODUCTS_OPTIONS_TYPE_TEXT\', 1);' . "\n" . '  define(\'PRODUCTS_OPTIONS_TYPE_RADIO\', 2);' . "\n" . '  define(\'PRODUCTS_OPTIONS_TYPE_CHECKBOX\', 3);' . "\n" . '  define(\'PRODUCTS_OPTIONS_TYPE_FILE\', 4);' . "\n" . '  define(\'PRODUCTS_OPTIONS_TYPE_TEXTAREA\', 5); ' . "\n" . '  define(\'PRODUCTS_OPTIONS_TYPE_CALENDER\', 6); ' . "\n" . '   define(\'DIR_WS_TEMPLATES\', DIR_FS_CATALOG . \'templates/\');' . "\n" . '?>';
  $fp = fopen($dir_fs_document_root . 'cartstoreadmin/includes/configure.php', 'w');
  fputs($fp, $file_contents);
  fclose($fp);
  $myFile = $dir_fs_document_root . "/.htaccess";
  $catalog = $http_catalog;
  $fh = fopen($myFile, 'r+') or die("can't open file");
  $theData = fread($fh, filesize($myFile));
  fclose($fh);
  $fh = fopen($myFile, 'w') or die("can't open file");
  $theData = str_replace('/installer', $catalog, $theData);
  fwrite($fh, $theData);
  fclose($fh);
?>
    <p>The installation and configuration was successful!</p>
    <br />
    <table border="0" width="99%" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center" width="50%"><a href="<?php
  echo $http_server . $http_catalog . 'index.php';
?>" target="_blank"><img src="images/button_catalog.gif" border="0" alt="Catalog" /></a></td>
        <td align="center" width="50%"><a href="<?php
  echo $http_server . $http_catalog . 'cartstoreadmin/index.php';
?>" target="_blank"><img src="images/button_administration_tool.gif" border="0" alt="Administration Tool" /></a></td>
      </tr>
    </table>
  </div>
</div>