<?php
define("LM_FRONT_CHOOSE_PACKAGE","<b>Elige el paquete a instalar: </b>");
define("LM_FRONT_CHOOSE_PACKAGE_SUB","<small>Por favor, selecciona tu version de Joomla para instalar</small>");
define("LM_FRONT_TOP","<span colspan='2' class='contentheading'>Instalar software Joomla via web</span>");
define("LM_FRONT_TOP_FTP_DETAILS","<h2>Introduce tus datos ftp: </h2>");
define("LM_FRONT_WEBSITE_URL","<b>Url de la web: </b>");
define("LM_FRONT_WEBSITE_URL_SUB","<small>Por favor, introduce la url de la web donde se instalar� Joomla. Ejemplo: http://www.nombresitio.com/joomla</small>");
define("LM_FRONT_FTP_HOST","<b>servidor FTP:</b>");
define("LM_FRONT_FTP_HOST_SUB","<small>Beispiel ftp.seitenname.de</small>");
define("LM_FRONT_FTP_USER","<b>FTP Username:</b>");
define("LM_FRONT_FTP_USER_SUB","<small>Ejemplo Juan</small>");
define("LM_FRONT_FTP_PASS","<b>contrase�a FTP:</b>");
define("LM_FRONT_FTP_PASS_SUB","<small>ejemplo contrase�aJuan</small>");
define("LM_FRONT_FTP_DIR","<b>Directorio FTP: </b>");
define("LM_FRONT_FTP_DIR_SUB","<small>Por favor introduce el directorio FTP donde te gustar�a instalar Joomla. Ejemplo: public_html/Joomla o htdocs/joomla y asegurate de que tiene permisos para todos, generalmente 777</small>");
define("LM_TRANSFER_FTP_INCT","transferencia incremental:");
define("LM_TRANSFER_FTP_INCT_SUB","Intentar� trnsferir los archivos por FTP en modo incremental para evitar p�ginas en blanco o timeouts.");
define("LM_FRONT_BOTTOM","No te ha funcionado? Por favor, env�anos un comentario con lo sucedido <a href='http://www.xcloner.com/contact/'>Pagina de contacto </a><br />Hecho por<a href='http://www.xcloner.com'>XCloner</a>");
define("LM_FRONT_MSG_OK","Hemos subido el paquete de utilidad de restauraci�n a tu sitio FTP. Para continuar, pulsa aqu�.");
define("LM_NOPAKCAGE_ERROR","No se ha seleccionado ning�n paquete. Deteniendo...!");
define("LM_DATABASE_EXCLUDE_TABLES","Selecciona las tablas que desees excluir (opcional)");
define("LM_CONFIG_SYSTEM_FOLDER","Visualizaci�n de carpetas:");
define("LM_CONFIG_SYSTEM_FOLDER_SUB","Por favor, selecciona el modo en el que quieres seleccionar las carpetas excluidas desde la secci�n -Generar copia de seguridad-");
define("LM_CONFIG_SYSTEM_LANG","Lenguaje de sistema:");
define("LM_CONFIG_SYSTEM_LANG_SUB","<small>Configura el lenguaje XCloner.Si se deja en -default- mostrar� el que viene por defecto en Joomla.</small>");
define("LM_CONFIG_SYSTEM_LANG_DEFAULT","Lenguaje por defecto");
define("LM_CONFIG_SYSTEM_DOWNLOAD","Activar v�nculo de descarga directa:");
define("LM_CONFIG_SYSTEM_DOWNLOAD_SUB","Si se activa, en la pantalla -Ver copias de seguridad-");
define("LM_CONFIG_DISPLAY","Configuraci�n de vista");
define("LM_CONFIG_SYSTEM","Configuraci�n de sistema");
define("LM_CONFIG_SYSTEM_FTP","Modo de transferencia FTP");
define("LM_CONFIG_SYSTEM_FTP_SUB","<small>Selecciona la manera en que se transferir�n los archivos entre servidores con el protocolo ftp.</small>");
define("LM_CONFIG_MEM","Copia de seguridad usando funciones de servidor:");
define("LM_CONFIG_MEM_SUB","<small>Si se activa se requerir� que tu servidor permita <b>ZIP o TAR</b> y / o <b>comandos MySQLDump</b> y especificar sus rutas, y tambi�n permisos de <b>ejecuci�n()</b> en tu php.</small>");
define("LM_CRON_DB_BACKUP","Permitir copia de seguridad de la base de datos:");
define("LM_CRON_DB_BACKUP_SUB","<small>Comprueba si te gustar�a copiar los datos de mysql</small>");
define("LM_CONFIG_SYSTEM_MBACKUP","Incluir carpetas de copia de seguridad en Clonaci�n:");
define("LM_CONFIG_SYSTEM_MBACKUP_SUB","<small>Si se elige -s�- la copia de seguridad creada contendr� archivos de copia previos aumentando cada vez su tama�o</small>");
define("LM_TAB_MYSQL","MYSQL");
define("LM_CONFIG_MYSQL","configuraci�n de conexi�n MySQL");
define("LM_CONFIG_MYSQLH","Servidor MySQL:");
define("LM_CONFIG_MYSQLU","usuario MySQL :");
define("LM_CONFIG_MYSQLP","Contrase�a MySQL :");
define("LM_CONFIG_MYSQLD","Base de datos MySQL:");
define("LM_TAB_AUTH","autentificaci�n");
define("LM_CONFIG_AUTH","Area de autentificaci�n");
define("LM_CONFIG_AUTH_USER","Usuario:");
define("LM_CONFIG_AUTH_PASS","Contrase�a:");
define("LM_CONFIG_AUTH_USER_SUB","<small>Este ser� tu usuario por defecto para Xcloner.</small>");
define("LM_CONFIG_AUTH_PASS_SUB","<small>Tu contrase�a por defecto. Dejala en blanco si no quieres cambiarla.</small>");
define("LM_YES","S�");
define("LM_NO","No");
define("LM_ACTIVE","Activo:");
define("LM_TAR_PATH","Ruta o comando Tar:");
define("LM_TAR_PATH_SUB","<small>(se requiere cuando el tipo de archivo es Tar y 'Activo' est� marcado.)</small>");
define("LM_ZIP_PATH","Ruta o comando ZIP:");
define("LM_ZIP_PATH_SUB","<small>(Se requiere si el tipo de archivo es Zip y 'Activo' est� marcado.)</small>");
define("LM_MYSQLDUMP_PATH","Ruta o comando MySQLDump:<br />(Se requiere si 'Activo' est� marcado
<br /><b><small>mysqldump --quote-names --quick --single-transaction --skip-comments</b></small>");
define("LM_CONFIG_MANUAL","Proceso de copia de seguridad manual");
define("LM_CONFIG_MANUAL_BACKUP","Copia de seguridad manual");
define("LM_CONFIG_MANUAL_BACKUP_SUB","Esta opci�n es para cuando tienes limitaciones de tiempo de ejecuci�n en tu servidor. Requiere que tengas activado javascript en tu navegador.");
define("LM_CONFIG_MANUAL_FILES","Archivos a procesar en la sesi�n manual:");
define("LM_CONFIG_MANUAL_REFRESH","Tiempo de refresco entre sesiones:");
define("LM_CONFIG_SYSTEM_MDATABASES","Copia de seguridad de bases de datos m�ltiples:");
define("LM_CONFIG_SYSTEM_MDATABASES_SUB","esta opci�n controla cu�ndo XCloner puede hacer copia de seguridad de bases de datos m�ltiples");
define("LM_CONFIG_CRON_LOCAL","Servidor local*");
define("LM_CONFIG_CRON_REMOTE","Cuenta FTP remota");
define("LM_CONFIG_CRON_EMAIL","Correo**");
define("LM_CONFIG_CRON_FULL","Completo (archivos + BBDD)");
define("LM_CONFIG_CRON_FILES","Archivos solo");
define("LM_CONFIG_CRON_DATABASE","BBDD solo");
define("LM_CONFIG_EDIT","Editar archivo de configuraci�n:");
define("LM_CONFIG_BSETTINGS","Ruta configuraci�n de la copia de seguridad.");
define("LM_CONFIG_BSETTINGS_OPTIONS","Opciones del generador de copias de seguridad");
define("LM_CONFIG_BSETTINGS_SERVER","Opciones del uso del servidor");
define("LM_CONFIG_BPATH","Ruta para guardar copia de seguridad:");
define("LM_CONFIG_UBPATH","Ruta de inicio de copia de seguridad:");
define("LM_CONFIG_BPATH_SUB","<small>�sta es la ruta donde se guardar�n todas las copias de seguridad.</small>");
define("LM_CONFIG_UBPATH_SUB","<small>Introduce aqu� la ruta de inicio de la copia de seguridad desde donde XCloner comenzar� todos los procesos.</small>");
define("LM_CRON_EXCLUDE","Carpetas excluidas");
define("LM_CRON_EXCLUDE_DIR","Carpetas excluidas, lista una por l�nea<br>por favor, usa la ruta completa del directorio del servidor");
define("LM_CRON_BNAME","Nombre de la copia de seguridad:");
define("LM_CRON_BNAME_SUB","<small>Si se deja en blanco, generaremos un nombre por defecto cada vez que una copia de seguridad cron se haga.</small>");
define("LM_CRON_IP","IP's Cron permitidas:");
define("LM_CRON_IP_SUB","<small>por defecto, solo el servidor local tendr� acceso al proceso cron pero puedes introducir otras IP's, una por l�nea.</small>");
define("LM_CRON_DELETE_FILES","Borrar viejas copias de seguridad");
define("LM_CRON_DELETE_FILES_SUB","Borrar copias de seguridad previas:");
define("LM_CRON_DELETE_FILES_SUB_ACTIVE","Activo:");
define("LM_CRON_SEMAIL","Enviar log Cron a:");
define("LM_CRON_SEMAIL_SUB","<small>Si se escribe una direcci�n de correo, despu�s de correr el proceso Cron se enviar� un correo a esa direcci�n. Si son mas direcciones separar por ';'.</small>");
define("LM_CRON_MCRON","Nombre de la configuraci�n:");
define("LM_CRON_MCRON_AVAIL","Configuraciones disponibles:");
define("LM_CRON_MCRON_R","Por favor introduce un nombre para tu configuraci�n Cron.");
define("LM_CRON_MCRON_SUB","<small>Con esta opci�n podras salvar la configuraci�n actual en un archivo separado y usarlo para correr procesos Cron m�ltiples.</small>");

define("LM_CRON_SETTINGS_M","Configuraci�n de procesos Cron m�ltiples");
define("LM_MENU_OPEN_ALL","Abrir todos");
define("LM_MENU_CLOSE_ALL","Cerrar todos");
define("LM_MENU_ADMINISTRATION","Administraci�n");
define("LM_MENU_CLONER","XCloner");
define("LM_MENU_CONFIGURATION","Configuraci�n");
define("LM_MENU_CRON","Cron");
define("LM_MENU_LANG","Taductor");
define("LM_MENU_ACTIONS","Acciones");
define("LM_MENU_Generate_backup","Generar copia de seguridad");
define("LM_MENU_Restore_backup","Restaurar copia de seguridad");
define("LM_MENU_View_backups","Ver copias de seguridad");
define("LM_MENU_Documentation","Ayuda");
define("LM_MENU_ABOUT","Sobre XCloner");
define("LM_DELETE_FILE_FAILED","Ha fallado el borrado, por favor comprueba los permisos de archivos");
define("LM_JOOMLAPLUG_CP","XCloner - Tu soluci�n de copias de seguridad");
define("LM_MENU_FORUM","Foro");
define("LM_MENU_SUPPORT","Soporte t�cnico");
define("LM_MENU_WEBSITE","P�gina web");
define("LM_MAIN_Settings","Configuraci�n");
define("LM_MAIN_View_Backups","Ver copias de seguridad");
define("LM_MAIN_Generate_Backup","Generar copias de seguridad");
define("LM_MAIN_Help","Ayuda");
define("LM_FTP_TRANSFER_MORE","Modo conexi�n FTP");
define("LM_LANG_NAME","Nombre de idioma");
define("LM_LANG_MSG_DEL","Lenguaje borrado con �xito!");
define("LM_LANG_NEW","Nuevo nombre de idioma:");
define("LM_LANG_EDIT_FILE","Editar archivo:");
define("LM_LANG_EDIT_FILE_SUB","No olvides salvar tu traducci�n cada 5 minutos. Solo pulsa el bot�n aplicar para actualizar.");
define("LM_TAB_GENERAL","General");
define("LM_TAB_G_STRUCTURE","Estructura");
define("LM_TAB_SYSTEM","Sistema");
define("LM_TAB_CRON","Cron");
define("LM_TAB_INFO","Info servidor");
define("LM_TAB_G_DATABASE","Opciones de BBDD");
define("LM_TAB_G_FILES","Opciones de archivos");
define("LM_G_EXCLUDE_COMMENT","<br>Por favor, introduce aqu� las carpetas excluidas.
    <br><b>Puede que quieras deshabilitar el cach� cuando hagas una copia de seguridad, o si no, excluir la carpeta cach� de la copia de seguridad</b>");
define("LM_MSG_FRONT_1","Ning�n paquete disponible");
define("LM_MSG_FRONT_2","Ha fallado la subida FTP para este destino");
define("LM_MSG_FRONT_3","Subida hecha para");
define("LM_MSG_FRONT_4","La conexi�n FTP ha fallado");
define("LM_MSG_FRONT_5","Intentando conectar a");
define("LM_MSG_FRONT_6","para el usuario");
define("LM_MSG_BACK_1","Configuraci�n actualizada con �xito...");
define("LM_MSG_BACK_2","La conexi�n FTP ha fallado!");
define("LM_MSG_BACK_3","copia de seguridad movida con �xito!. La copia de seguridad deber�a ser visible en el lugar elegido.");
define("LM_MSG_BACK_4","transferencia realizada, comenzando el prceso de clonado en el servidor remoto");
define("LM_MSG_BACK_5","despublicado del frontend con �xito");
define("LM_MSG_BACK_6","Ha fallado la despublicaci�n! Por favor, compruebe sus rutas");
define("LM_MSG_BACK_7","Publicado en el frontend con �xito!");
define("LM_MSG_BACK_8","Publicaci�n fallida! Por favor, compruebe sus rutas");
define("LM_MSG_BACK_9","Clones renombrados con �xito!");
define("LM_MSG_BACK_10","La ruta Joomla no est� dentro de la ruta de copia de seguridad! No podr�a usar el modo de descarga directa! Deber�a editar su Configuraci�n -> Sistema y establecer el -Vinculo de descarga directa- a -No-");
define("LM_MSG_BACK_11","Proceso de copia de seguridad manual completada!<a href='index2.php?option=com_cloner&task=view'>Pulsar aqu� para continuar</a>");
define("LM_MSG_BACK_12","<h2>Ha fallado la copia de seguridad!. Por favor, compruebe que tiene soporte para utilidades ZIP (/usr/bin/zip or /usr/local/bin/zip) en su servidor y la ruta que estableciste en la configuraci�n es correcta, o elige el tipo de archivo Zip.</h2>");
define("LM_MSG_BACK_13","<h2>Ha fallado la copia de seguridad!. Por favor, compruebe que tiene soporte para utilidades TAR (/usr/bin/tar or /usr/local/bin/tar) en su servidor y la ruta que estableciste en la configuraci�n es correcta, o elige el tipo de archivo Tar.</h2>");
define("LM_MSG_BACK_14","<font color='red'>Hubo un problema al generar la copia de seguridad de la base de datos, Por favor comprueba tu ruta al servidor mysqldump.</font>");
define("LM_CRON_TOP","Configurando la copia de seguridad Cron:");
define("LM_CRON_SUB","<b>Usando la funci�n Cron puedes configurar un generador autom�tico de copias de seguridad para tu web Joomla:</b><br>
Para configurarla necesitas a�adir en tu panel de control Cron el comando siguiente:");
define("LM_CRON_HELP","Atenci�n:
 - Si tu ruta php es diferente de /usr/bin/php Por favor, usa esta: format /$"."php_path/php


Weiterf�hrende Informationen (englisch) wie man ein Cronjob konfiguriert f�r
 - Cpanel <a href='http://www.cpanel.net/docs/cpanel/' target='_blank'>click here</a>
 - Plesk <a href='http://www.swsoft.com/doc/tutorials/Plesk/Plesk7/plesk_plesk7_eu/plesk7_eu_crontab.htm' target='_blank'>click here</a>
 - Interworx <a href='http://www.sagonet.com/interworx/tutorials/siteworx/cron.php' target='_blank'>click here</a>
 - General Linux crontab info <a href='http://www.computerhope.com/unix/ucrontab.htm#01' target='_blank'>click here</a>

Wenn du Hilfe beim Setup des Cron Backups ben�tigst oder Probleme beim Cron Backups hast, dann wende dich bitte 
an unser Forum <a href='http://www.xcloner.com/support/forums/'>http://www.xcloner.com/support/forums/</a> oder schreibe uns eine Email an <a href='mailto:admin@xcloner.com'>admin@xcloner.com</a>");
define("LM_CRON_SETTINGS","Configuraci�n Cron");
define("LM_CRON_MODE","Modo de guardado e copias de seguridad:");
define("LM_CRON_MODE_INFO"," <br />
      <small>Fijese bien*: Si se elige -Servidor local- Usaremos la ruta de copias de seguridad por defecto de la secci�n General para guardar la copia de seguridad </small>
<br />
<small> Atenci�n:** Si se usa el modo correo no garantizamos que la copia de seguridad alcance la cuenta de correo debido a limitaciones del proveedor</small>");
define("LM_CRON_TYPE_INFO","<small><br />Por favor, elije el tipo de copia que querr�as crear.</small>");
define("LM_CRON_MYSQL_DETAILS","Opciones MySQL");
define("LM_CRON_MYSQL_DROP","A�ade MySQL Drop:");
define("LM_CRON_TYPE","Modo Copia de seguridad:");
define("LM_CRON_FTP_DETAILS","Detalles del modo de guardado FTP:");
define("LM_CRON_FTP_SERVER","Servidor FTP:");
define("LM_CRON_FTP_USER","Usuario FTP:");
define("LM_CRON_FTP_PASS","Contrase�a FTP:");
define("LM_CRON_FTP_PATH","Ruta FTP:");
define("LM_CRON_FTP_DELB","Borrar copia de seguridad tras transferir:");
define("LM_CRON_EMAIL_DETAILS","Detalles del modo correo:");
define("LM_CRON_EMAIL_ACCOUNT","Cuenta de correo:");
define("LM_CRON_COMPRESS","Comprimir archivos de copia de seguridad:");
define("LM_RESTORE_TOP","Info de restaurar copias de seguridad");
define("LM_CREDIT_TOP","Sobre XCloner:");
define("LM_CLONE_FORM_TOP","<h2>Introduce tus datos FTP abajo:</h2>");
define("LM_CONFIG_INFO_T_SAFEMODE","Modo de seguridad Php:");
define("LM_CONFIG_INFO_T_MTIME","Tiempo m�ximo de ejecuci�n Php::");
define("LM_CONFIG_INFO_T_MEML","L�mite de memoria Php:");
define("LM_CONFIG_INFO_T_BDIR","PHP open_basedir:");
define("LM_CONFIG_INFO_T_EXEC","exec() Ayuda funci�n:");
define("LM_CONFIG_INFO_T_TAR","Ruta servidor utilidad Tar:");
define("LM_CONFIG_INFO_T_ZIP","Ruta servidor utilidad Zip:");
define("LM_CONFIG_INFO_T_MSQL","Ruta servidor utilidad MySQLDump:");
define("LM_CONFIG_INFO_T_BPATH","Ruta Copia de seguridad:");
define("LM_CONFIG_INFO_ROOT_PATH_SUB","<small>La ruta de inicio del la copia de seguridad necesita existir y ser legible para que XCloner comience el proceso de copia.</small>");
define("LM_CONFIG_INFO_ROOT_BPATH_TMP","Carpeta temporal:");
define("LM_CONFIG_INFO_ROOT_PATH_TMP_SUB","<small>Esta ruta necesita ser creada y escribible para que XCloner funcione correctamente.</small>");
define("LM_CONFIG_INFO","Esta pesta�a mostrar� Las opciones generales de sistema y las rutas");
define("LM_CONFIG_INFO_PATHS","Informaci�n de rutas generales:");
define("LM_CONFIG_INFO_PHP","Informaci�n de la configuraci�n PHp:");
define("LM_CONFIG_INFO_TIME","<small>Esto controla el tiempo m�ximo que se permite a tu script para correr en el servidor, en segundos.</small>");
define("LM_CONFIG_INFO_MEMORY","<small>Esto controla la memoria m�xima que pueden usar los procesos del script.</small>");
define("LM_CONFIG_INFO_BASEDIR","<small>Esto controla las rutas a las que tu script puede acceder. Sin valor, significa que puede acceder a todas.</small>");
define("LM_CONFIG_INFO_SAFEMODE","<small>El modo seguro necesita configurarse a Off para que XCloner funcione correctamente.</small>");
define("LM_CONFIG_INFO_TAR","<small>Si el script no puede determinar la ruta tar autom�ticamente necesitar�s desmarcar la caja de verificaci�n en la l�nea TAR en la pesta�a 'General'.</small>");
define("LM_CONFIG_INFO_ZIP","<small>Si el script no puede determinar la ruta zip autom�ticamente, necesitar�s desmarcar la caja de verificaci�n en la l�nea ZIP en la pesta�a 'General'</small>");
define("LM_CONFIG_INFO_MSQL","<small>Si el script no puede determinar la ruta mysqldump autom�ticamente, necesitar�s desmarcar la caja de verificaci�n en la l�nea MYSQLDUMP en la pesta�a 'General'</small>");
define("LM_CONFIG_INFO_EXEC","<small>Si esta funci�n est� deshabilitada, Puedes necesitar desmarcar ambas cajas de verificaci�n en la pesta�a 'General'.</small>");
define("LM_CONFIG_INFO_BPATH","<small>necesita ser escribible para que XCloner pueda archivar copias de seguridad.</small>");
define("LM_TRANSFER_URL","URL sitio web:");
define("LM_TRANSFER_URL_SUB","<small>Por favor, escriba la url de su sitio web donde se mover� la copia de seguridad. Ejemplo: http://www.nombresitio.com/ Esto se necesita para dirijirte all� y que accedas al script de restauraci�n</small>");
define("LM_TRANSFER_FTP_HOST","Servidor FTP:");
define("LM_TRANSFER_FTP_HOST_SUB","Ejemplo: ftp.nombre de sitio.com");
define("LM_TRANSFER_FTP_USER","Usuario FTP:");
define("LM_TRANSFER_FTP_USER_SUB","Ejemplo 'Juan'");
define("LM_TRANSFER_FTP_PASS","Contrase�a FTP:");
define("LM_TRANSFER_FTP_PASS_SUB","Ejemplo: 'juancontrase�a'");
define("LM_TRANSFER_FTP_DIR","Directorio FTP:");
define("LM_TRANSFER_FTP_DIR_SUB","Por favor, escribe el directorio FTP donde querr�as mover la copia de seguridad. Ejemplo: public_html/ o htdocs/ . Aseg�rate de que tiene permisos de escritura para todos. Normalmente 777.");
define("LM_BACKUP_NAME","<b>Por favor, elije el nombre de tu copia de seguridad</b>");
define("LM_BACKUP_NAME_SUB","<small>Si se deja en blanco, generar� un nombre por defecto.</small>");
define("LM_COL_AVALAIBLE","Paquete Frontend");
define("LM_DELETE_FILE_SUCCESS","Archivo(s) borrado(s)");
define("LM_DOWNLOAD_TITLE","Descargar esta copia de seguridad");
define("LM_DATABASE_EXCLUDED","Excluidos");
define("LM_DATABASE_CURRENT","BBDD actual:");
define("LM_DATABASE_INCLUDE_DATABASES","Incluir BBDD extra");
define("LM_DATABASE_INCLUDE_DATABASES_SUB","<small>Puedes seleccionar m�ltiples BBDD para incluir en la copia de seguridad presionando la tecla Ctrl y seleccionando con el rat�n los objetos que se desee incluir.</small>");
define("LM_DATABASE_MISSING_TABLES","Error: Definiciones de tabla no encontradas");
define("LM_DATABASE_BACKUP_FAILED","Copia de seguridad fallida, por favor comprueba que la carpeta administrator/backups es escribible!");
define("LM_DATABASE_BACKUP_COMPLETED","Copia de seguridad completada");
define("LM_RENAME_TOP","Renombrar clones seleccionados");
define("LM_RENAME","Renombrar clone");
define("LM_RENAME_TO","a");
define("LM_CLONER_RESTORE","<h2>Como restaurar una copia de seguridad en diferentes lugares INFO!</h2>
<pre>
Restaurar tus copias de seguridad nunca fu� tan f�cil! Con la ayuda de tu funvi�n de clonado de <a href='index2.php?option=com_cloner&task=view'>Ver copias</a>
tu podr�s mover tus copias a cualquier parte en internet

Esto es lo que debes hacer:
   
   <b>Paso 1 - mueve la copia al servidor de restauraci�n</b>
  
    - Ir al �rea 'Ver copias de seguridad'	
    - Tras seleccionar tu copia, pulsa en el Bot�n 'Cl�nalo'
    - Introduce los detalles ftp donde querr�as clonar la copia de seguridad
    - Tras pulsra en 'enviar' la copia y el script de restauraci�n ser�n transferidos al nuevo servidor y se te dar� una url para acceder al nuevo paso basado en la url que introdujiste lara acceso remoto. Ejemplo: <b>http://Misitioderestauraci�n.com/XCloner.php</b>

<b>Atenci�n:</b>Si este proceso falla por alguna raz�n. Debes hacer esto: 
     1. Desc�rgate la copia de seguridad en tu PC.
     2. Desc�rgate el script de restauraci�n, los archivos, desde el directorio administrator/components/com_xcloner-backupandrestore/restore/
     3. Sube la copia de seguridad y el script a tu directorio de restauraci�n.
     4. Lanza XCloner.php en tu navegador y sigue la pantalla de restauraci�n tal y como especifica abajo
     Beispiel URL: <b>http://neue-seite.de/XCloner.php</b>
   
   <b>Paso 2: La pantalla de restauraci�n de XCloner.php:</b>
   <b>XCloner.php - El script de restauraci�n -</b>
        - En este paso ya tienes en posici�n el clon que has hecho basado en tu sitio Joomla y script de restauraci�n
    - Introduzca los nuevos detalles de mysql. Esto incluye tu nuevo servidor MYSQL, usuario, contrase�a y nombre de BBDD.
    - Introducetu nueva Url y contrase�a
    - Para restaurar los archivos tienes <b>2 opciones:</b>
       
       	- 1. Restaurar los archivos pot ftp, el script simular� un proceso de subida ftp en tu servidor, esto solucionar� los problemas de permisos del paso 2. 
       	- 2. Restaura los archivos directamente, esto desarchivar� los archivos en tu servidor, ser� r�pido pero podr� encontrar algun problema de permisos si el acceso ftp se usa muy a menudo para hacer cambios en el sitio
      			
    - Tras pulsar el bot�n de env�o el script tratar� de mover los archivos a la nueva ruta directamente o usando ftp e instalar� la BBDD.  
    - Si todo va bientu clon del sitio estar� subido y corriendo normalmente en su nuevo emplazamiento
    
  Para soporte, consulta nuestro foro <a href='http://www.xcloner.com/support/forums/' target='_blank'>http://www.xcloner.com/support/forums/</a>
    o env�anos un correo a <a href='mailto:info@xcloner.com'>info@xcloner.com</a>.   

</pre>");
define("LM_CLONER_ABOUT","<h2>Copia de seguridad XCloner</h2><br />
      <pre>XCloner es una herramienta que te ayudar� a manejar tus copias de seguridad de tus sitios Joomla, generar/restaurar/mover, de manera que tu sitio est� siempre seguro. <b>Caracter�sticas:</b>
       - Script Cron para generar copias de seguridad
       - Opciones para multiples copias de seguridad
       - Herramienta de restauraci�n para mover el sitio web r�pidamente a otros emplazamientos.
       - Multiples emplazamientos en donde podr�as guardar la copia a salvo.

Para reportar problemas o enviarnos sugerencias, cont�ctenos a admin@xcloner.com o vis�tenos en  
<a href='http://www.xcloner.com'>http://www.xcloner.com</a>.
	   </pre>
     <br/><br/>

      XCloner.com � 2004-2010 | <a href='http://www.xcloner.com'>www.xcloner.com</a>
      <br/><p/><br/>");
define("LM_LOGIN_TEXT","<pre>
<b>Atenci�n:</b>
 1. Si est�s en esta pantalla por primera vez tu usuario por defecto es '<i>admin</i>' y la contrase�a '<i>admin</i>'. 
    necesitar�s cambiarla tras entrar en el sistema
 
 2. Si olvidas tu contrase�a necesitar�s resetearla con este c�digo: 
        
	<b>$"."_CONFIG[\"jcpass\"] = md5(\"mi_nueva_contrase�a\");</b>
</pre>
");
?>
