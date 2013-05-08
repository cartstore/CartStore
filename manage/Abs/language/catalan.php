<?php
// --- FRONT AREA---//
define("LM_FRONT_CHOOSE_PACKAGE","<b>Escull el paquet a instal�lar: </b>");
define("LM_FRONT_CHOOSE_PACKAGE_SUB","<small>Si us plau, selecciona la teva versi� de Joomla per instal�lar</small>");
define("LM_FRONT_TOP","<span colspan='2' class='contentheading'>Instal�lar programari Joomla v�a web</span>");
define("LM_FRONT_TOP_FTP_DETAILS","<h2>Introdueix les teves dades ftp: </h2>");
define("LM_FRONT_WEBSITE_URL","<b>Url de la web: </b>");
define("LM_FRONT_WEBSITE_URL_SUB","<small>Si us plau, introdueix la url de la web on s'instal�lar� Joomla. Exemple: http://www.sitename.com/joomla</small>");
define("LM_FRONT_FTP_HOST","<b>servidor FTP:</b>");
define("LM_FRONT_FTP_HOST_SUB","<small>Exemple ftp.sitename.de</small>");
define("LM_FRONT_FTP_USER","<b>FTP Login:</b>");
define("LM_FRONT_FTP_USER_SUB","<small>Exemple Juan</small>");
define("LM_FRONT_FTP_PASS","<b>Contrasenya FTP:</b>");
define("LM_FRONT_FTP_PASS_SUB","<small>Exemple contrasenyaJuan</small>");
define("LM_FRONT_FTP_DIR","<b>Directori FTP: </b>");
define("LM_FRONT_FTP_DIR_SUB","<small>Si us plau, introduiu el directori FTP on us agradaria instal�lar Joomla. Exemple: public_html/Joomla o htdocs/joomla i assegureu-vos de que t� permisos per tothom, generalment 777</small>");
define("LM_TRANSFER_FTP_INCT","transfer�ncia incremental:");
define("LM_TRANSFER_FTP_INCT_SUB","Intentar� transferir els fitxers per FTP en mode incremental per evitar p�gines en blanc o timeouts.");
define("LM_FRONT_BOTTOM","No t'ha funcionat? Si us plau, envian's un comentari amb el que ha passat <a href='http://www.xcloner.com/contact/'>P�gina de contacte </a><br />Fet per<a href='http://www.xcloner.com'>XCloner</a>");
define("LM_FRONT_MSG_OK","S'ha pujat el paquet d'utilitats de restauraci� al vostre lloc FTP. Per continuar, cliqueu aqu�.");
define("LM_NOPAKCAGE_ERROR","No s'ha seleccionat cap paquet. Abortant...!");

// --- BACKEND AREA---//

//Amazon S3
define("LM_AMAZON_S3", "Emmagatzament Amazon S3");
define("LM_AMAZON_S3_ACTIVATE", "Activar");
define("LM_AMAZON_S3_AWSACCESSKEY", "Clau d'acc�s AWS:");
define("LM_AMAZON_S3_AWSSECRETKEY", "Clau secreta AWS:");
define("LM_AMAZON_S3_BUCKET", "Nom:");
define("LM_AMAZON_S3_DIRNAME", "Directori de pujada:");
define("LM_AMAZON_S3_SSL","Activar transfer�ncia SSL");

define("LM_DATABASE_EXCLUDE_TABLES","Selecciona les taules que vols excloure (opcional)");
define("LM_CONFIG_SYSTEM_FOLDER","Visualitzaci� de carpetes:");
define("LM_CONFIG_SYSTEM_FOLDER_SUB","Si us plau, selecciona el mode en el que vols seleccionar les carpetes excloses des de la secci� -Generar c�pia de seguretat-");
define("LM_CONFIG_SYSTEM_LANG","Idioma del sistema:");
define("LM_CONFIG_SYSTEM_LANG_SUB","<small>Configura l'idoma de XCloner. si es deix en -default- mostrar� el que v� per defecte en Joomla.</small>");
define("LM_CONFIG_SYSTEM_LANG_DEFAULT","Idioma per defecte");
define("LM_CONFIG_SYSTEM_DOWNLOAD","Activar enlla� de desc�rrega directa:");
define("LM_CONFIG_SYSTEM_DOWNLOAD_SUB","Si s'activa, a la pantalla -Veure c�pies de seguretat-");
define("LM_CONFIG_DISPLAY","Configuraci� de vista");
define("LM_CONFIG_SYSTEM","Configuraci� del sistema");
define("LM_CONFIG_SYSTEM_FTP","Mode de transfer�ncia FTP");
define("LM_CONFIG_SYSTEM_FTP_SUB","<small>Selecciona la forma en que es transferiran els arxius entre servidors amb el protocol FTP.</small>");
define("LM_CONFIG_MEM","C�pia de seguretat utilitzant funcions de servidor:");
define("LM_CONFIG_MEM_SUB","<small>Si s'activa es requerir� que el teu servidor permeti <b>ZIP o TAR</b> i/o <b>comandes MySQLDump</b> i especificar les rutes, i tamb� permisos d' <b>execuci�()</b> al teu php.</small>");
define("LM_CRON_DB_BACKUP","Permetre c�pia de seguretat de la base de dades:");
define("LM_CRON_DB_BACKUP_SUB","<small>Comprova si t'agradaria copiar les dades de mysql</small>");
define("LM_CONFIG_SYSTEM_MBACKUP","Incloure les carpetes de c�pia de seguretat a Clonaci�:");
define("LM_CONFIG_SYSTEM_MBACKUP_SUB","<small>Si s'escull -Si- la c�pia de seguretat creada contindr� arxius de c�pia previs, augmentant cada vegada la seva mida</small>");

define("LM_TAB_MYSQL","MYSQL");
define("LM_CONFIG_MYSQL","configuraci� de connexi� MySQL");
define("LM_CONFIG_MYSQLH","Servidor MySQL:");
define("LM_CONFIG_MYSQLU","usuari MySQL :");
define("LM_CONFIG_MYSQLP","Contrasenya MySQL :");
define("LM_CONFIG_MYSQLD","Base de dades MySQL:");

define("LM_TAB_AUTH","autentificaci�");
define("LM_CONFIG_AUTH","�rea d'autentificaci�");
define("LM_CONFIG_AUTH_USER","Usuari:");
define("LM_CONFIG_AUTH_PASS","Contrasenya:");
define("LM_CONFIG_AUTH_USER_SUB","<small>Aquest ser� el teu usuari per defecte per a XCloner.</small>");
define("LM_CONFIG_AUTH_PASS_SUB","<small>La teva contrasenya per defecte. Deixa-la en blanc si no vols cambiar-la.</small>");

define("LM_YES","Si");
define("LM_NO","No");
define("LM_ACTIVE","Actiu:");
define("LM_TAR_PATH","Ruta o comanda Tar:");
define("LM_TAR_PATH_SUB","<small>(Es requereix quan el tipus d'arxiu es Tar i 'Actiu' est� marcat.)</small>");
define("LM_ZIP_PATH","Ruta o comanda ZIP:");
define("LM_ZIP_PATH_SUB","<small>(Es requereix quan el tipus d'arxiu es Tar i 'Actiu' est� marcat.)</small>");
define("LM_MYSQLDUMP_PATH","Ruta o comanda MySQLDump:<br />(Es requereix si 'Actiu' est� marcat
<br /><b><small>mysqldump --quote-names --quick --single-transaction --skip-comments</b></small>");

// --- CONFIG ---//
define("LM_CONFIG_MANUAL","Proc�s de c�pia de seguretat manual");
define("LM_CONFIG_MANUAL_BACKUP","C�pia de seguretat manual");
define("LM_CONFIG_MANUAL_BACKUP_SUB","Aquesta opci� �s per quan hi ha limitacions de temps de execuci� al servidor. Requereix que tinguis javascript activat al teu navegador.");
define("LM_CONFIG_MANUAL_FILES","Fitxers a processar en la sessi� manual:");
define("LM_CONFIG_MANUAL_REFRESH","Temps de refresc entre sessions:");
define("LM_CONFIG_SYSTEM_MDATABASES","C�pia de seuretat de bases de dades m�ltiples:");
define("LM_CONFIG_SYSTEM_MDATABASES_SUB","Aquesta opci� controla quan XCloner pot fer una c�pia de seguretat de bases de dades m�ltiples.");
define("LM_CONFIG_EXCLUDE_FILES_SIZE","Excloure fitxers m�s grans que:");

define("LM_CONFIG_CRON_LOCAL","Servidor local*");
define("LM_CONFIG_CRON_REMOTE","Compte FTP remot");
define("LM_CONFIG_CRON_EMAIL","Correu**");
define("LM_CONFIG_CRON_FULL","Complet (fitxers + BBDD)");
define("LM_CONFIG_CRON_FILES","Nom�s fitxers");
define("LM_CONFIG_CRON_DATABASE","Nom�s BBDD");

define("LM_CONFIG_EDIT","Editar fitxer de configuraci�:");
define("LM_CONFIG_BSETTINGS","Ruta de configuraci� de la c�pia de seguretat.");
define("LM_CONFIG_BSETTINGS_OPTIONS","Opcions del generador de c�pies de seguretat");
define("LM_CONFIG_BSETTINGS_SERVER","Opcions de l'�s del servidor");
define("LM_CONFIG_BPATH","Ruta per guardar c�pia de seguretat:");
define("LM_CONFIG_UBPATH","Ruta d'inici de c�pia de seguretat:");
define("LM_CONFIG_BPATH_SUB","<small>Aquesta �s la ruta on es guardaran totes les c�pies de seguretat.</small>");
define("LM_CONFIG_UBPATH_SUB","<small>Introduiu aqu� la ruta d'inici de la c�pia de seguretat des d'on XCloner comen�ar� tots els processos.</small>");
define("LM_CRON_EXCLUDE","Carpetes excloses");
define("LM_CRON_EXCLUDE_DIR","Carpetes excloses, llistat una per l�nia <br>si us plau, useu la ruta completa del directori del servidor");
define("LM_CRON_BNAME","Nom de la c�pia de seguretat:");
define("LM_CRON_BNAME_SUB","<small>Si es deixa en blanc, generarem un nom per defecte cada vegada que una c�pia de seguretat cron sigui realitzada.</small>");
define("LM_CRON_IP","IP's Cron permeses:");
define("LM_CRON_IP_SUB","<small>per defecte, nom�s el servidor local tindr� acc�s al proc�s cron, per� pots introduir altres IP's, una per l�nia.</small>");
define("LM_CRON_DELETE_FILES","Borrar velles c�pies de seguretat");
define("LM_CRON_DELETE_FILES_SUB","Borrar c�pies de seguretat pr�vies:");
define("LM_CRON_DELETE_FILES_SUB_ACTIVE","Actiu:");
define("LM_CRON_SEMAIL","Enviar log Cron a:");
define("LM_CRON_SEMAIL_SUB","<small>Si escriviu una adre�a de correu, despr�s de c�rrer el proc�s Cron, enviarem un correu a aquesta adre�a. Si son m�s adreces separeu amb ';'</small>");
define("LM_CRON_MCRON","Nom de la configuraci�:");
define("LM_CRON_MCRON_AVAIL","Configuracions disponibles:");
define("LM_CRON_MCRON_R","Si us plau introdueix un nom per la teva configuraci� Cron.");
define("LM_CRON_MCRON_SUB","<small>Amb aquesta opci� podr�s guardar la configuraci� actual en un fitxer separat i utilitzar-lo per c�rrer processos Cron m�ltiples.</small>");
define("LM_CRON_SETTINGS_M","Configuraci� de processos Cron m�ltiples");
define("LM_CONFIG_SPLIT_BACKUP_SIZE", "Dividir el fitxer de c�pia de seguretat si �s m�s gran que:");

// --- MENU ---//
define("LM_MENU_OPEN_ALL","Obrir tots");
define("LM_MENU_CLOSE_ALL","Tancar tots ");
define("LM_MENU_ADMINISTRATION","Administraci�");
define("LM_MENU_CLONER","XCloner");
define("LM_MENU_CONFIGURATION","Configuraci�");
define("LM_MENU_CRON","Cron");
define("LM_MENU_LANG","Traductor");
define("LM_MENU_ACTIONS","Accions");
define("LM_MENU_Generate_backup","Generar c�pia de seguretat");
define("LM_MENU_Restore_backup","Restaurar c�pia de seguretat");
define("LM_MENU_View_backups","Veure c�pies de seguretat");
define("LM_MENU_Documentation","Ajuda");
define("LM_MENU_ABOUT","Sobre XCloner");
define("LM_DELETE_FILE_FAILED","Ha fallat el borrat, si us plau comprova els permisos dels arxius");
define("LM_JOOMLAPLUG_CP","XCloner - LA soluci� de c�pies de seguretat");
define("LM_MENU_FORUM","F�rum");
define("LM_MENU_SUPPORT","Suport t�cnic");
define("LM_MENU_WEBSITE","P�gina web");

define("LM_MAIN_Settings","Configuraci�");
define("LM_MAIN_View_Backups","Veure c�pies de seguretat");
define("LM_MAIN_Generate_Backup","Generar c�pies de seguretat");
define("LM_MAIN_Help","Ajuda");
define("LM_FTP_TRANSFER_MORE","Mode connexi� FTP");
define("LM_REFRESH_MODE","Mode refrescar c�pia de seguretat");
define("LM_DEBUG_MODE","Activar log:");
define("LM_REFRESH_ERROR","Hi ha hagut un error mentres es recuperava les dades JSON del servidor, proveu una altra vegada o contacteu amb els desenvolupadors!");

// --- LANGUAGE --//
define("LM_LANG_NAME","Nom de l'idioma");
define("LM_LANG_MSG_DEL","Idioma borrat amb �xit!");
define("LM_LANG_NEW","Nou nom de l'idioma:");
define("LM_LANG_EDIT_FILE","Editar arxiu:");
define("LM_LANG_EDIT_FILE_SUB","No oblidis guardar la teva traducci� cada 5 minuts. Nom�s prem el bot� aplicar per actualitzar.");

// --- TABS --//
define("LM_TAB_GENERAL","General");
define("LM_TAB_G_STRUCTURE","Estructura");
define("LM_TAB_SYSTEM","Sistema");
define("LM_TAB_CRON","Cron");
define("LM_TAB_INFO","Info servidor");
define("LM_TAB_G_DATABASE","Opcions de BBDD");
define("LM_TAB_G_FILES","Opcions de fitxers");
define("LM_G_EXCLUDE_COMMENT","<br>Si us plau, introdueix aqui les carpetes excloses.
    <br><b>Pot ser que vulguis deshabilitar la cach� quan facis una c�pia de seguretat, o sino, excloure la carpeta cach� de la c�pia de seguretat</b>");
define("LM_TAB_G_COMMENTS_H2", "Introduiu a sota comentaris addicionals al fitxer:");
define("LM_TAB_G_COMMENTS_NOTE","Els comentaris seran guradats dins de l'arxiu, a <b>administrator/backups/.comments</b>");

// --- MESSAGES --//
// front end
define("LM_MSG_FRONT_1","Cap paquet disponible");
define("LM_MSG_FRONT_2","Ha fallat la pujada FTP per aquest dest�");
define("LM_MSG_FRONT_3","Pujada feta per");
define("LM_MSG_FRONT_4","La connexi� FTP ha fallat");
define("LM_MSG_FRONT_5","Intentant connectar a");
define("LM_MSG_FRONT_6","per l'usuari");

//backend
define("LM_MSG_BACK_1","Configuraci� actualitzada amb �xit...");
define("LM_MSG_BACK_2","La connexi� FTP ha fallat!");
define("LM_MSG_BACK_3","c�pia de seguretat moguda amb �xit!. La c�pia de seguretat haur�a de ser visible en el lloc escollit.");
define("LM_MSG_BACK_4","transfer�ncia realitzada, comen�ant el proc�s de clonat al servidor remot.");
define("LM_MSG_BACK_5","despublicat del front-end amb �xit");
define("LM_MSG_BACK_6","Ha fallat la despublicaci�! Si us plau, comprovi les rutes");
define("LM_MSG_BACK_7","Publicado en el frontend con �xito!");
define("LM_MSG_BACK_8","Publicaci� fallada! Si us plau, comprovi les rutes");
define("LM_MSG_BACK_9","Clons renombrats amb �xit!");
define("LM_MSG_BACK_10","La ruta del Joomla no est� dins de la ruta de la c�pia de seguretat! No podr� usar el mode de desc�rrega directa! Haur�a d'editar la seva Configuraci� -> Sistema i establir l' -Enlla� de desc�rrega directa- a -No-");
define("LM_MSG_BACK_11","Proc�s de c�pia de seguretat manual completat!<a href='index2.php?option=com_cloner&task=view'>Prem aqu� per continuar</a>");
define("LM_MSG_BACK_12","<h2>Ha fallat la c�pia de seguretat!. Si us plau, comprovi que disposa de suport per utilitats ZIP (/usr/bin/zip or /usr/local/bin/zip) al servidor i que la ruta sigui correcta, o escull el tipus d'arxiu Zip.</h2>");
define("LM_MSG_BACK_13","<h2>Ha fallat la c�pia de seguretat!. Si us plau, comprovi que disposa de suport per utilitats TAR (/usr/bin/tar or /usr/local/bin/tar) al servidor i que la ruta sigui correcta, o escull el tipus d'arxiu Tar.</h2>");
define("LM_MSG_BACK_14","<font color='red'>Hi ha hagut un problema al generar la c�pia de seguretat de la base de dades, si us plau comproveu la ruta al servidor mysqldump.</font>");

define("LM_CRON_TOP","Configurant la c�pia de seguretat Cron:");
define("LM_CRON_SUB","<b>Usant la funci� Cron pots configurar un generador autom�tic de c�pies de seguretat per la teva web Joomla:</b><br>
Per configurar-la necessites afegir al teu panel de control Cron la comanda seg�ent:");
define("LM_CRON_HELP","Atenci�:
 - Si la ruta del php �s diferent de /usr/bin/php Si us plau, useu aquesta: format /$"."php_path/php

Per a m�s informaci� de com configurar el Cron:
 - Cpanel <a href='http://www.cpanel.net/docs/cpanel/' target='_blank'>click here</a>
 - Plesk <a href='http://www.swsoft.com/doc/tutorials/Plesk/Plesk7/plesk_plesk7_eu/plesk7_eu_crontab.htm' target='_blank'>click here</a>
 - Interworx <a href='http://www.sagonet.com/interworx/tutorials/siteworx/cron.php' target='_blank'>click here</a>
 - General Linux crontab info <a href='http://www.computerhope.com/unix/ucrontab.htm#01' target='_blank'>click here</a>

Si necessites m�s ajuda per configurar el teu treball Cron, visita els nostres f�rums <a href='http://www.xcloner.com/support/forums/'>http://www.xcloner.com/support/forums/</a>");
define("LM_CRON_SETTINGS","Configuraci�n Cron");
define("LM_CRON_MODE","Mode de guardat de c�pies de seguretat:");
define("LM_CRON_MODE_INFO"," <br />
      <small>Atenci�*: Si s'escull -Servidor local- Usarem la ruta de c�pies de seguretat per defecte de la secci� General per guardar la c�pia de seguretat</small>
<br />
<small> Atenci�:** Si s'usa el mode correu no garantitzem que la c�pia de seguretat arribi a la compte de correu degut a limitacions del proveidor</small>");
define("LM_CRON_TYPE_INFO","<small><br />Si us plau, escull el tipus de c�pia que voldries crear.</small>");
define("LM_CRON_MYSQL_DETAILS","Opcions MySQL");
define("LM_CRON_MYSQL_DROP","Afegir MySQL Drop:");
define("LM_CRON_TYPE","Mode C�pia de seguretat:");
define("LM_CRON_FTP_DETAILS","Detalls del mode de guardat FTP:");
define("LM_CRON_FTP_SERVER","Servidor FTP:");
define("LM_CRON_FTP_USER","Usuari FTP:");
define("LM_CRON_FTP_PASS","Contrasenya FTP:");
define("LM_CRON_FTP_PATH","Ruta FTP:");
define("LM_CRON_FTP_DELB","Borrar c�pia de seguretat despr�s de transferir:");
define("LM_CRON_EMAIL_DETAILS","Detalls del mode correu:");
define("LM_CRON_EMAIL_ACCOUNT","Compte de correu:");
define("LM_CRON_COMPRESS","Comprimir fitxers de c�pia de seguretat:");
define("LM_RESTORE_TOP","Informaci� per restaurar c�pies de seguretat");
define("LM_CREDIT_TOP","Sobre XCloner:");
define("LM_CLONE_FORM_TOP","<h2>Introdueix les dades FTP a continuaci�:</h2>");

// --- Info Tab ---//
define("LM_CONFIG_INFO_T_SAFEMODE","Mode de seguretat Php:");
define("LM_CONFIG_INFO_T_MTIME","Temps m�xim d'execuci� Php:");
define("LM_CONFIG_INFO_T_MEML","L�mit de mem�ria Php:");
define("LM_CONFIG_INFO_T_BDIR","PHP open_basedir:");
define("LM_CONFIG_INFO_T_EXEC","exec() Ajuda funci�:");
define("LM_CONFIG_INFO_T_TAR","Ruta servidor utilitat Tar:");
define("LM_CONFIG_INFO_T_ZIP","Ruta servidor utilitat Zip:");
define("LM_CONFIG_INFO_T_MSQL","Ruta servidor utilitat MySQLDump:");
define("LM_CONFIG_INFO_T_BPATH","Ruta C�pia de seguretat:");
define("LM_CONFIG_INFO_ROOT_PATH_SUB","<small>La ruta d'inici de la c�pia de seguretat necessita existir i ser legible per que XCloner comenci el proc�s de c�pia.</small>");
define("LM_CONFIG_INFO_ROOT_BPATH_TMP","Carpeta temporal:");
define("LM_CONFIG_INFO_ROOT_PATH_TMP_SUB","<small>Aquesta ruta necessita ser creada i amb perm�s d'escriptura per que XCloner funcioni correctament.</small>");

define("LM_CONFIG_INFO","Aquesta pestanya mostrar� les opcions generals del sistema i les rutes");
define("LM_CONFIG_INFO_PATHS","Informaci� de rutes generals:");
define("LM_CONFIG_INFO_PHP","Informaci� de la configuraci� Php:");
define("LM_CONFIG_INFO_TIME","<small>Aix� controla el temps m�im que es permet al teu script executar-se al servidor, en segons.</small>");
define("LM_CONFIG_INFO_MEMORY","<small>Aix� controla la mem�ria que poden usar els procesos script.</small>");
define("LM_CONFIG_INFO_BASEDIR","<small>Aix� controla les rutes a les que el teu script pot accedir. Sense valor, significa que pot accedir a totes.</small>");
define("LM_CONFIG_INFO_SAFEMODE","<small>El mode segur necessita configurar-se a Off per que XCloner funcioni correctament.</small>");
define("LM_CONFIG_INFO_TAR","<small>Si l'script no pot determinar la ruta tar autom�ticament necessitar�s desmarcar la caixa de verificaci� a la l�nia TAR de la pestanya 'General'.</small>");
define("LM_CONFIG_INFO_ZIP","<small>Si l'script no pot determinar la ruta zip autom�ticament, necessitar�s desmarcar la caixa de verificaci� a la l�nia ZIP de la pestanya 'General'</small>");
define("LM_CONFIG_INFO_MSQL","<small>Si l'script no pot determinar la ruta mysqldump autom�ticament, necessitar�s desmarcar la caixa de verificaci� a la l�nia MYSQLDUMP de la pestanya 'General'</small>");
define("LM_CONFIG_INFO_EXEC","<small>Si aquesta funci� est� deshabilitada, Pots necessitar desmarcar les dues caixes de verificaci� de la pestanya 'General'.</small>");
define("LM_CONFIG_INFO_BPATH","<small>necessita tenir perm�s d'escriptura per que XCloner pugui arxivar c�pies de seguretat.</small>");

// --- TRANSFER DETAILS---//
define("LM_TRANSFER_URL","URL lloc web:");
define("LM_TRANSFER_URL_SUB","<small>Si us plau, escriviu la url del vostre lloc web on es mour� la c�pia de seguretat. Exemple: http://www.nomlloc.com/ Aix� es necessita per dirigir-se alli i accedir a l'script de restauraci�.</small>");
define("LM_TRANSFER_FTP_HOST","Servidor FTP:");
define("LM_TRANSFER_FTP_HOST_SUB","Exemple: ftp.nom de lloc.com");
define("LM_TRANSFER_FTP_USER","Usuari FTP:");
define("LM_TRANSFER_FTP_USER_SUB","Exemple 'Juan'");
define("LM_TRANSFER_FTP_PASS","Contrasenya FTP:");
define("LM_TRANSFER_FTP_PASS_SUB","Exemple: 'juancontrasenya'");
define("LM_TRANSFER_FTP_DIR","Directori FTP:");
define("LM_TRANSFER_FTP_DIR_SUB","Si us plau, introduiu el directori FTP on us agradaria instal�lar Joomla. Exemple: public_html/Joomla o htdocs/joomla i assegureu-vos de que t� permisos per tothom, generalment 777.");

// --- GENERATE BACKUP---//
define("LM_BACKUP_NAME","<b>Si us plau, escolliu el nom de la c�pia de seguretat</b>");
define("LM_BACKUP_NAME_SUB","<small>Si es deixa en blanc, es generar� un nom per defecte.</small>");

// -- General --//
define("LM_COM_TITLE","XCloner Manager - ");
define("LM_COM_TITLE_CONFIRM","Confirmar selecci� de carpeta");

define("LM_COL_FILENAME","C�pia de seguretat");
define("LM_COL_DOWNLOAD","Baixar");
define("LM_COL_AVALAIBLE","Paquet Frontend");
define("LM_COL_SIZE","Mida");
define("LM_COL_DATE","Data");
define("LM_COL_FOLDER","<b>Fitxers i/o carpetes exclosos</b>");

define("LM_DELETE_FILE_SUCCESS","Fitxer(s) borrat(s)");
define("LM_DOWNLOAD_TITLE","Descarregar aquesta c�pia de seguretat");

define("LM_ARCHIVE_NAME","Archive Name");
define("LM_NUMBER_FOLDERS","Number of Folders");
define("LM_NUMBER_FILES","Number of Files");
define("LM_SIZE_ORIGINAL","Size of Original File");
define("LM_SIZE_ARCHIVE","Size of Archive");
define("LM_DATABASE_ARCHIVE","Database Backup");

define("LM_CONFIRM_INSTRUCTIONS","<b>Please select the folders you wish to exclude from the archive</b>
<br />- by default all folders are included, if you wish to exclude a folder and it's subfolders just check the box next to it");
define("LM_CONFIRM_DATABASE","Backup Database");

define("LM_DATABASE_EXCLUDED","Exclosos");
define("LM_DATABASE_CURRENT","BBDD actual:");
define("LM_DATABASE_INCLUDE_DATABASES","Incloure BBDD extra");
define("LM_DATABASE_INCLUDE_DATABASES_SUB","<small>Pots seleccionar m�ltiples BBDD per incloure a la c�pia de seguretat presionant la tecla Ctrl i seleccionant amb el ratol� els objectes que es desitji incloure.</small>");

define("LM_DATABASE_MISSING_TABLES","Error: Definicions de taula no trobades");
define("LM_DATABASE_BACKUP_FAILED","C�pia de seguretat fallada, si us plau comprova que la carpeta administrator/backups disposa de perm�s d'escriptura!");
define("LM_DATABASE_BACKUP_COMPLETED","C�pia de seguretat completada");
define("LM_RENAME_TOP","Renombrar clons seleccionats");
define("LM_RENAME","Renombrar clon");
define("LM_RENAME_TO","a");

// --- CLONER RESTORE--- //
define("LM_CLONER_RESTORE","<h2>Com restaurar una c�pia de seguretat en diferents llocs INFO!</h2>
<pre>
Restaurar les teves c�pies de seguretat mai havia sigut tan f�cil. Amb l'ajuda de la funci� de clonat de <a href='index2.php?option=com_cloner&task=view'>Veure c�pies</a>
podr�s moure les teves c�pies a qualsevol part d'Internet

Aix� �s lo que has de fer:
   
   <b>Pas 1 - mou la c�pia al servidor de restauraci�</b>
  
    - Anar a l'�rea 'Veure c�pies de seguretat'	
    - Seleccionar la teva c�pia i clicar el bot� 'Clonar'
    - Introdueix els detalls FTP d'on vulguis clonar la c�pia de seguretat
    - Clicar a 'enviar', i la c�pia i l'script de restauraci� seran transferits al nou servidor i obtindreu una url per accedir al nou pas basat en la url introduida com a lloc remot. Exemple: <b>http://elmeullocderestauracio.com/XCloner.php</b>

<b>Atenci�:</b>Si aquest proc�s falla per alguna ra�. Feu el seg�ent: 
     1. Descarrega la c�pia de seguretat al teu PC.
     2. Descarrega l'script de restauraci�, els fitxers, des del directori administrator/components/com_xcloner-backupandrestore/restore/
     3. Pujeu la c�pia de seguretat i l'script al teu directori de restauraci�.
     4. Executeu XCloner.php al navegador i seguiu la pantalla de restauraci�
     Exemple URL: <b>http://nou-lloc.com/XCloner.php</b>
   
   <b>Paso 2: La pantalla de restauraci� de XCloner.php:</b>
   <b>XCloner.php - L'script de restauraci� -</b>
        - En aquest pas ja tens en posici� el clon que has fet basat en el teu lloc Joomla i l'script de restauraci�.
    - Introdueix els nous detalls mysql. Aix� inclou el teu nou servidor MYSQL, usuari, contrasenya i nom de BBDD.
    - Introducetu nova Url i contrasenya
    - Per restaurar els fitxers tens <b>2 opcions:</b>
       
       	- 1. Restaurar els fitxers per ftp, l'script simular� un proc�s de pujada ftp al teu servidor, aix� solucionar� els problemes de permisos del pas 2. 
       	- 2. Restaura els fitxers directament, aix� desarxivar� els fitxers al teu servidor, ser� r�pid per� podr� trobar algun problema de permisos si l'acc�s ftp s'usa molt sovint per fer canvis al lloc.
      			
    - Despr�s de pr�mer el bot� d'enviament, l'script provar� de moure els fitxers a la nova ruta directament o usant ftp, i instal�lar� la nova base de dades.  
    - Si tot va b� el teu clon del lloc estar� pujat i funcionant correctament al seu nou empla�ament
    
  Per a suport, consulta el nostre f�rum <a href='http://www.xcloner.com/support/forums/' target='_blank'>http://www.xcloner.com/support/forums/</a>
    o envian's un correu a <a href='mailto:info@xcloner.com'>info@xcloner.com</a>.   

</pre>");

// --- ABOUT CLONER---//

define("LM_CLONER_ABOUT","<h2>C�pia de seguretat XCloner</h2><br />
      <pre>XCloner �s una eina que t'ajudar� a manegar les teves c�pies de seguretat dels teus llocs Joomla, generar/restaurar/moure, de forma que el teu lloc sempre estigui segur. <b>Caracter�stiques:</b>
       - Script Cron per generar c�pies de seguretat
       - Opcions per m�ltiples c�pies de seguretat
       - Eina de restauraci� per moure el lloc web r�pidament a altres empla�aments.
       - M�ltiples empla�aments on podrieu guardar les c�pies a resguard.

Per reportar problemes o enviar-nos sugger�ncies, contacteu-nos a admin@xcloner.com o visiteu-nos a  
<a href='http://www.xcloner.com'>http://www.xcloner.com</a>.
	   </pre>
     <br/><br/>

      XCloner.com � 2004-2010 | <a href='http://www.xcloner.com'>www.xcloner.com</a>
      <br/><p/><br/>");

define("LM_LOGIN_TEXT","<pre>
<b>Atenci�n:</b>
 1. Si est�s en aquesta pantalla per primer cop, es que el teu usuari per defecte es '<i>admin</i>' i la contrasenya '<i>admin</i>'. 
    necessitar�s canviar-la despr�s d'entrar en el sistema
 
 2. Si oblides la teva contrasenya necessitar�s resetejar-la amb aquest codi: 
        
	<b>$"."_CONFIG[\"jcpass\"] = md5(\"nova_contrasenya\");</b>
</pre>
");
?>
