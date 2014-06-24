<?php

// --- FRONT AREA---//
define("LM_FRONT_CHOOSE_PACKAGE","<b>Choisissez votre installation: </b>");
define("LM_FRONT_CHOOSE_PACKAGE_SUB","<small>S'il vous pla�t choisissez votre version de joomla ou de wordpress que vous souhaitez installer </small>");
define("LM_FRONT_TOP","<span colspan='2' class='contentheading'> Installez le Logiciel en ligne</span>");
define("LM_FRONT_TOP_FTP_DETAILS","<h2>Fournissez vos d�tails ftp ci-dessous: </h2>");
define("LM_FRONT_WEBSITE_URL","<b>Url de votre site</b>");
define("LM_FRONT_WEBSITE_URL_SUB","<small>S'il vous pla�t fournissez l'url du site Web Joomla ou wordpress sera install�, exemple http://www.sitename.com/Joomla ou Wordpress </small>");
define("LM_FRONT_FTP_HOST","<b>Nom du ftp:</b>");
define("LM_FRONT_FTP_HOST_SUB","<small>exemple ftp://123456878.fr</small>");
define("LM_FRONT_FTP_USER","<b>Login Ftp:</b>");
define("LM_FRONT_FTP_USER_SUB","<small>exmple 12345</small>");
define("LM_FRONT_FTP_PASS","<b>Mot de passe Ftp:</b>");
define("LM_FRONT_FTP_PASS_SUB","<small>exemple 5412</small>");
define("LM_FRONT_FTP_DIR","<b>R�pertoire Ftp</b>");
define("LM_FRONT_FTP_DIR_SUB","<small>S'il vous pla�t indiquer le r�pertoire du ftp o� vous aimeriez installer Joomla ou wordpress, exemple public_html / Joomla ou wordpress / ou htdocs / Joomla ou wordpress / et assurer vous d'avoir donn� les autorisations n�c�ssaires CHMOD</small>");
define("LM_TRANSFER_FTP_INCT","transfert Croissant:");
define("LM_TRANSFER_FTP_INCT_SUB","Transfert des fichiers par FTP en mode incr�mental afin d'�viter toute pages blanches ou des d�lais d'expiration");
define("LM_FRONT_BOTTOM","une erreur <a href='http://www.xcloner.com/contact/'>Page de Contact</a><br/>Propos� par <a href='http://www.xcloner.com'>XCloner</a>");
define("LM_FRONT_MSG_OK","Nous avons transf�r� la sauvegarde sur votre site ftp, pour continuer cliquez ici");
define("LM_NOPAKCAGE_ERROR","il n'y a aucun paquet s�lectionn�, erreur...");

// --- BACKEND AREA---//

//Amazon S3
define("LM_AMAZON_S3","Amazon S3");
define("LM_AMAZON_S3_ACTIVATE","Activer");
define("LM_AMAZON_S3_AWSACCESSKEY","Clef de l'Acc�s:");
define("LM_AMAZON_S3_AWSSECRETKEY","AWS Clef Secr�te:");
define("LM_AMAZON_S3_BUCKET","nom Bucket:");
define("LM_AMAZON_S3_DIRNAME","T�l�charger le R�pertoire:");


define("LM_DATABASE_EXCLUDE_TABLES","S�lectionner les tables � exclure de la sauvegarde");
define("LM_CONFIG_SYSTEM_FOLDER","Afficher les dossiers:");
define("LM_CONFIG_SYSTEM_FOLDER_SUB","s'il vous pla�t s�lectionner les dossiers � exclure de votre sauvegarde");
define("LM_CONFIG_SYSTEM_LANG","Langue du syst�me:");
define("LM_CONFIG_SYSTEM_LANG_SUB","Configurer la langue pour XCloner,par d�faut cela sera celle de Joomla ou wordpress si elle est disponible");
define("LM_CONFIG_SYSTEM_LANG_DEFAULT","Syst�me par d�faut");
define("LM_CONFIG_SYSTEM_DOWNLOAD","Activer lien de t�l�chargement direct:");
define("LM_CONFIG_SYSTEM_DOWNLOAD_SUB","si cette case est coch�e, l'�cran 'View Backups',le lien de t�l�chargement sera un lien direct � partir de votre serveur afin de t�l�charger l'ensemble, s'il vous pla�t noter que le chemin de sauvegarde doit �tre dans le chemin d'acc�s racine de Joomla ou wordpress");
define("LM_CONFIG_DISPLAY","Param�tres d'affichage");
define("LM_CONFIG_SYSTEM","Param�tres syst�me");
define("LM_CONFIG_SYSTEM_FTP","Mode de transfert FTP");
define("LM_CONFIG_SYSTEM_FTP_SUB","Choisir comment les fichiers seront transf�r�s de serveur � serveur lorsque vous utilisez le protocole FTP");
define("LM_CONFIG_MEM","Sauvegarde en utilisant les fonctions du Serveur");
define("LM_CONFIG_MEM_SUB","<small>Si la valeur est active, il vous sera demand� d'avoir sur votre support pour serveur l'utilisation <b> pour zip ou tar </b>et / ou <b> mysqldump</b> en commandes et de pr�ciser leurs chemins, et aussi <b > exec () </b> l'acc�s dans votre PHP</small>");
define("LM_CRON_DB_BACKUP","Activer la sauvegarde de base de donn�es:");
define("LM_CRON_DB_BACKUP_SUB","<small>cochez <b>Oui</b> si vous voulez sauvegarder les donn�es mysql</small>");
define("LM_CONFIG_SYSTEM_MBACKUP","Inclure les sauvegardes dans le r�pertoire clone:");
define("LM_CONFIG_SYSTEM_MBACKUP_SUB","<small>Si r�gl� sur <b>Oui</b>, la sauvegarde cr�e contiendra �galement des fichiers des sauvegardes pr�c�dentes, ce qui augmente � chaque fois sa taille</small>");

define("LM_TAB_MYSQL","MYSQL ou MYSQLI");
define("LM_CONFIG_MYSQL","Param�tres de connexion MySQL:");
define("LM_CONFIG_MYSQLH","Nom d'h�te Mysql:");
define("LM_CONFIG_MYSQLU","Nom d'utilisateur MySQL:");
define("LM_CONFIG_MYSQLP","Mot de passe Mysql:");
define("LM_CONFIG_MYSQLD","Base de donn�es Mysql:");

define("LM_TAB_AUTH","Authentification");
define("LM_CONFIG_AUTH","Espace d'authentification de l'utilisateur");
define("LM_CONFIG_AUTH_USER","Utilisateur:");
define("LM_CONFIG_AUTH_PASS","Mot de passe:");
define("LM_CONFIG_AUTH_USER_SUB","Votre login utilisateur par d�faut � XCloner");
define("LM_CONFIG_AUTH_PASS_SUB","votre mot de passe de connexion par d�faut, laissez en blanc si vous ne voulez pas le changer");

define("LM_YES","Oui");
define("LM_NO","Non");
define("LM_ACTIVE","Activer");
define("LM_TAR_PATH","Chemin path ou commande:");
define("LM_TAR_PATH_SUB","(obligatoire si le type d'archive est TAR et la case coch�e est activ�e)");
define("LM_ZIP_PATH","Chemin du Zip ou de la commande:");
define("LM_ZIP_PATH_SUB","(obligatoire si le type d'archive est ZIP et la case coch�e est activ�e)");
define("LM_MYSQLDUMP_PATH","Chemin de mysqldump ou de commande: (obligatoire si la case Active est coch�e) <br/> Pour les grands dumps mysql s'il vous pla�t utiliser
<br/> <b> <small> mysqldump - quote-names - rapide - single-transaction - skip-comment </b> </small>");

// --- CONFIG ---//
define("LM_CONFIG_MANUAL","Processus de sauvegarde manuelle");
define("LM_CONFIG_MANUAL_BACKUP","Sauvegarde manuelle:");
define("LM_CONFIG_MANUAL_BACKUP_SUB","Cette option est indiqu�e si vous avez dans php des limitations de temps d'ex�cution sur votre serveur, il faudra javascript activ� sur votre navigateur");
define("LM_CONFIG_MANUAL_FILES","Fichiers � traiter par la requ�te:");
define("LM_CONFIG_DB_RECORDS","Enregistrements de base de donn�es selon la requ�te");
define("LM_CONFIG_MANUAL_REFRESH","Temps entre les requ�tes:");
define("LM_CONFIG_SYSTEM_MDATABASES","Sauvegarde des bases de donn�es multiples:");
define("LM_CONFIG_SYSTEM_MDATABASES_SUB","Cette option activ� XCloner peut sauvegarder plusieurs bases de donn�es");
define("LM_CONFIG_EXCLUDE_FILES_SIZE","Exclure les fichiers de plus de:");

define("LM_CONFIG_CRON_LOCAL","Serveur local*");
define("LM_CONFIG_CRON_REMOTE","Compte ftp � distance");
define("LM_CONFIG_CRON_EMAIL","Courrier �lectronique**");
define("LM_CONFIG_CRON_FULL","Int�gral (fichiers + base de donn�es)");
define("LM_CONFIG_CRON_FILES","Uniquement les fichiers");
define("LM_CONFIG_CRON_DATABASE","Base de donn�es uniquement");

define("LM_CONFIG_EDIT","Modification du fichier de configuration");
define("LM_CONFIG_BSETTINGS","Param�tres du chemin de sauvegarde");
define("LM_CONFIG_BSETTINGS_OPTIONS","Options g�n�ral de sauvegarde");
define("LM_CONFIG_BSETTINGS_SERVER","Utiliser les options serveur");
define("LM_CONFIG_BPATH","Chemin de sauvegarde:");
define("LM_CONFIG_UBPATH","D�marrer la sauvegarde:");
define("LM_CONFIG_BPATH_SUB","<small>Chemin o� toutes les sauvegardes seront stock�es</small>");
define("LM_CONFIG_UBPATH_SUB","<small>d�signer un chemin pour la sauvegarde initiale, d'o� XCloner va commencer tous les processus</small>");
define("LM_CRON_EXCLUDE","R�pertoires exclus");
define("LM_CRON_EXCLUDE_DIR","Exclure la liste des r�pertoires un par ligne: <br> s'il vous pla�t utiliser le chemin complet du r�pertoire du serveur");
define("LM_CRON_BNAME","Nom de la sauvegarde:");
define("LM_CRON_BNAME_SUB","<small>S'il est laiss� en blanc, cela va g�n�rer automatiquement un nom par d�faut � chaque nouvelle sauvegarde</small>");
define("LM_CRON_IP","Cron admis IP's:");
define("LM_CRON_IP_SUB","<small>Par d�faut, seul le serveur local aura acc�s � la t�che CRON, mais vous pouvez entrer aussi une autre adresse IP, une par ligne</small>");
define("LM_CRON_DELETE_FILES","Supprimer sauvegardes les plus anciennes");
define("LM_CRON_DELETE_FILES_SUB","Supprimer des sauvegardes anciennes de:");
define("LM_CRON_DELETE_FILES_SUB_ACTIVE","Activer");
define("LM_CRON_SEMAIL","Email journal de cron �:");
define("LM_CRON_SEMAIL_SUB","Si une adresse e-mail est inscrite, apr�s l'ex�cution d'une t�che cron, le journal sera envoy� � cette adresse, des adresses multiples peuvent �tre ajout�s en les s�parants par <b>;</b>");
define("LM_CRON_MCRON","Nom de la configuration:");
define("LM_CRON_MCRON_AVAIL","Configurations disponibles:");
define("LM_CRON_MCRON_R","s'il vous pla�t donner un nom simple pour la configuration de votre nouvelle cron");
define("LM_CRON_MCRON_SUB","Avec cette option, vous serez en mesure d'enregistrer la configuration actuelle dans un fichier s�par� et de l'utiliser pour l'ex�cution de t�ches cron multiples");
define("LM_CRON_SETTINGS_M","Multiples CronJobs Configuration");
define("LM_CONFIG_SPLIT_BACKUP_SIZE","Split de sauvegarde des archives si la taille plus grande que:");

// --- MENU ---//
define("LM_MENU_OPEN_ALL","ouvrir Menu");
define("LM_MENU_CLOSE_ALL","fermer Menu");
define("LM_MENU_ADMINISTRATION","Administration");
define("LM_MENU_CLONER","Xcloner");
define("LM_MENU_CONFIGURATION","Configurations");
define("LM_MENU_CRON","CRON");
define("LM_MENU_LANG","Traduction");
define("LM_MENU_ACTIONS","Action");
define("LM_MENU_Generate_backup","G�n�rer des sauvegardes");
define("LM_MENU_Restore_backup","Restaurer la sauvegarde");
define("LM_MENU_View_backups","Voir les sauvegardes");
define("LM_MENU_Documentation","Aide");
define("LM_MENU_ABOUT","A propos de");
define("LM_DELETE_FILE_FAILED","�chec de la suppression, s'il vous pla�t v�rifier les permissions sur les fichiers");
define("LM_Joomla ou wordpressPLUG_CP","XCloner - Votre site de sauvegarde et de restauration solution");
define("LM_MENU_FORUM","Forum en ligne");
define("LM_MENU_SUPPORT","Support en ligne");
define("LM_MENU_WEBSITE","Site Web");

define("LM_MAIN_Settings","Param�tres");
define("LM_MAIN_View_Backups","Voir les sauvegardes");
define("LM_MAIN_Generate_Backup","G�n�rer des sauvegardes");
define("LM_MAIN_Help","Aide");
define("LM_FTP_TRANSFER_MORE","Mode de connexion FTP");
define("LM_REFRESH_MODE","Rafra�chir sauvegarde");
define("LM_DEBUG_MODE","Activer le journal:");
define("LM_REFRESH_ERROR","Il y a eu une erreur d'extraction des donn�es JSON � partir du serveur, essayez � nouveau ou contacter les d�veloppeurs!");

// --- LANGUAGE --//
define("LM_LANG_NAME","Nom de la langue");
define("LM_LANG_MSG_DEL","Langue(s) supprim� avec succ�s!");
define("LM_LANG_NEW","Nom de la nouvelle langue:");
define("LM_LANG_EDIT_FILE","�dition du fichier:");
define("LM_LANG_EDIT_FILE_SUB","Ne pas oublier de sauvegarder votre traduction toutes les 5 minutes, appuyez simplement sur le bouton Appliquer pour mettre � jour");

// --- TABS --//
define("LM_TAB_GENERAL","G�n�ral");
define("LM_TAB_G_STRUCTURE","Structures");
define("LM_TAB_SYSTEM","Syst�me");
define("LM_TAB_CRON","Cron");
define("LM_TAB_INFO","Info");
define("LM_TAB_G_DATABASE","Options de base de donn�es");
define("LM_TAB_G_FILES","Options Fichiers");
define("LM_TAB_G_COMMENTS","Commentaires sauvegardes");
define("LM_G_EXCLUDE_COMMENT","<br>S'il vous pla�t entrer ici les dossiers � exclure,un par ligne!
     <br><b> vous pouvez d�sactiver la fonction du cache lorsque vous effectuez une sauvegarde, ou ne pas exclure le dossier cache de la sauvegarde </b>");
define("LM_TAB_G_COMMENTS_H2","Saisissez ci-dessous tout commentaire suppl�mentaire pour archiver:");
define("LM_TAB_G_COMMENTS_NOTE","S'il vous pla�t noter que les commentaires sont stock�s dans les archives <b>administrator/backups/.comments</b>");

// --- MESSAGES --//
// front end
define("LM_MSG_FRONT_1","Aucune sauvegarde disponible");
define("LM_MSG_FRONT_2","Chargement FTP a �chou� pour la destination");
define("LM_MSG_FRONT_3","Envoi effectu� pour");
define("LM_MSG_FRONT_4","Connexion FTP a �chou�!");
define("LM_MSG_FRONT_5","Tentative de connexion �");
define("LM_MSG_FRONT_6","pour l'utilisateur");

//backend
define("LM_MSG_BACK_1","Configuration mise � jour ...");
define("LM_MSG_BACK_2","Connexion FTP a �chou�!");
define("LM_MSG_BACK_3","D�placement de la sauvegarde FAITE! La sauvegarde s�lectionnez doit maintenant �tre disponible � l'emplacement pr�vu!");
define("LM_MSG_BACK_4","D�placement fait, d�marrer le processus de clonage sur l'h�te distant");
define("LM_MSG_BACK_5","Ensemble non publi�es � partir de l'interface");
define("LM_MSG_BACK_6","Erreur...S'il vous pla�t v�rifier vos chemins!");
define("LM_MSG_BACK_7","Publi� avec succ�s pour Interface");
define("LM_MSG_BACK_8","Erreur...S'il vous pla�t v�rifier vos chemins!");
define("LM_MSG_BACK_9","Clones renomm� avec succ�s!");
define("LM_MSG_BACK_10","Le chemin d'acc�s de Joomla ou wordpress n'est pas au sein de votre r�pertoire de sauvegarde! Impossible d'utiliser le mode de t�l�chargement direct!");
define("LM_MSG_BACK_11","Tout est fait! Tout est fait! Le processus de sauvegarde manuel est fini! <a href='index2.php?option=com_cloner&task=view'>Cliquer ici pour continuer </a>");
define("LM_MSG_BACK_12","<h2>La sauvegarde a �chou�! S'il vous pla�t v�rifiez que vous avez le support de l'utilitaire zip (/ usr / bin / zip ou / usr / local / bin / zip) sur votre serveur et que le chemin d'acc�s soit correcte ou choisir le type d'archive Tar!</h2>");
define("LM_MSG_BACK_13","<h2>La sauvegarde a �chou�! S'il vous pla�t v�rifiez que vous avez le support de l'utilitaire zip (/ usr / bin / zip ou / usr / local / bin / zip) sur votre serveur et que le chemin d'acc�s soit correcte ou choisir le type d'archive ZIP!</h2>");
define("LM_MSG_BACK_14","<font color='red'>Il y a eu un probl�me dans la cr�ation de la sauvegarde de base de donn�es, s'il vous pla�t v�rifiez le chemin du serveur mysqldump!</font>");



define("LM_CRON_TOP","Commande de configuration Cron");
define("LM_CRON_SUB","<b>Utilisation de la fonction cron, vous pouvez configurer un g�n�rateur automatique de sauvegarde pour votre site web ! </b>
<br/> Pour l'installer, vous devez ajouter � votre panneau de configuration <b>crontab</b> l'une des commandes suivantes:");
define("LM_CRON_HELP","<b>Notes:<br>
 - Si vous avez dans votre php un emplacement diff�rent de celui / usr / bin / php s'il vous pla�t utiliser ce format /$"."php_path/php </b>
<br>

Pour plus d'informations sur comment configurer un cron pour
 <br>- Cpanel <a href='http://www.cpanel.net/docs/cpanel/' target='_blank'>Cliquer Ici</a>
 <br>- Plesk <a href='http://www.swsoft.com/doc/tutorials/Plesk/Plesk7/plesk_plesk7_eu/plesk7_eu_crontab.htm' target='_blank'>Cliquer Ici</a>
 <br>- Interworx <a href='http://www.sagonet.com/interworx/tutorials/siteworx/cron.php' target='_blank'>Cliquer Ici</a>
 <br>- Informations g�n�rales crontab Linux <a href='http://www.computerhope.com/unix/ucrontab.htm#01' target='_blank'>Cliquer Ici</a>
<br> Si vous avez besoin d'aide pour configurer votre CRON, s'il vous pla�t visitez notre forum <a href='http://www.xcloner.com/support/forums/' target='_blank'>http://www.xcloner.com/support/forums/</a>");
define("LM_CRON_SETTINGS","Param�tres Cron");
define("LM_CRON_MODE","Mode de stockage sauvegarde:");
define("LM_CRON_MODE_INFO"," <br/>
      <small> S'il vous pla�t noter: * si le serveur local est choisi nous allons utiliser le chemin de sauvegarde par d�faut pour stocker la sauvegarde</small>
      <br/>
      <small> S'il vous pla�t noter: ** si le mode email est utilis�e, nous avons pas de garantie que la sauvegarde sera port�e au compte de messagerie en raison de la limitation fournisseur</small>");
define("LM_CRON_TYPE_INFO","<small><br/> s'il vous pla�t choisir votre type de sauvegarde que vous souhaitez cr�er</small>");
define("LM_CRON_MYSQL_DETAILS","Options Mysql");
define("LM_CRON_MYSQL_DROP","Ajouter Mysql Drop");
define("LM_CRON_TYPE","Type de sauvegarde:");
define("LM_CRON_FTP_DETAILS","Sauvegarder configuration FTP:");
define("LM_CRON_FTP_SERVER","Serveur ftp:");
define("LM_CRON_FTP_USER","Nom d'utilisateur FTP:");
define("LM_CRON_FTP_PASS","Mot de passe FTP:");
define("LM_CRON_FTP_PATH","chemin d'acc�s FTP:");
define("LM_CRON_FTP_DELB","Supprimer sauvegarde apr�s le transfert");
define("LM_CRON_EMAIL_DETAILS","d�tails Email :");
define("LM_CRON_EMAIL_ACCOUNT","Compte Email:");
define("LM_CRON_COMPRESS","Compresser les fichiers de sauvegarde:");
define("LM_RESTORE_TOP","Information restauration de votre sauvegarde");
define("LM_CREDIT_TOP","A propos de XCloner");
define("LM_CLONE_FORM_TOP","<h2>Fournir les d�tails de votre ftp ci-dessous:</h2>");

// --- Info Tab ---//

define("LM_CONFIG_INFO_T_SAFEMODE","Mode sans �chec PHP:");
define("LM_CONFIG_INFO_T_VERSION","V�rification de la version PHP:");
define("LM_CONFIG_INFO_T_MTIME","Temps maximal d'ex�cution:");
define("LM_CONFIG_INFO_T_MEML","Limite m�moire:");
define("LM_CONFIG_INFO_T_BDIR","Ouverture base PHP");
define("LM_CONFIG_INFO_T_EXEC","exec () support:");
define("LM_CONFIG_INFO_T_TAR","chemin d'acc�s Tar:");
define("LM_CONFIG_INFO_T_ZIP","chemin d'acc�s Zip:");
define("LM_CONFIG_INFO_T_MSQL","chemin d'acc�s mysqldump:");
define("LM_CONFIG_INFO_T_BPATH","Chemin de sauvegarde:");
define("LM_CONFIG_INFO_ROOT_PATH_SUB","le chemin d'acc�s du lancement de la sauvegarde doit exister et �tre lisibles pour que XCloner puisse d�marrer le processus de sauvegarde");
define("LM_CONFIG_INFO_ROOT_BPATH_TMP","Dossier temporaire");
define("LM_CONFIG_INFO_ROOT_PATH_TMP_SUB","Le chemin d'acc�s <i>[Backup Start Path/administrator/backups]</i> doit �tre cr�e et �tre accessible en �criture pour que XCloner fonctionne correctement");



define("LM_CONFIG_INFO","Cet onglet affiche des informations syst�me g�n�ral et les chemins d'acc�s");
define("LM_CONFIG_INFO_PATHS","Info G�n�ral chemin d'acc�s:");
define("LM_CONFIG_INFO_PHP","Information configuration Php:");
define("LM_CONFIG_INFO_TIME","<small>Cela contr�le le temps maximum d'�x�cution du script vers votre serveur</small>");
define("LM_CONFIG_INFO_MEMORY","<small> Ce contr�le la quantit� maximale de m�moire le script peut allouer � ses processus </small>");
define("LM_CONFIG_INFO_BASEDIR","<small>Cela contr�le les chemins d'acc�s de votre script autoris� � acc�der, aucune valeur signifie qu'il peut acc�der � n'importe quel chemin d'acc�s</small>");
define("LM_CONFIG_INFO_SAFEMODE","<small> mode sans �chec devra �tre r�gl� sur Off pour que XCloner pour fonctionner correctement </small>");
define("LM_CONFIG_INFO_VERSION","<small> PHP> = 5.2.3 est n�cessaire</small>");
define("LM_CONFIG_INFO_TAR","<small>Si le script n'est pas en mesure de d�terminer le chemin d'acc�s de TAR automatiquement, vous pourriez avoir besoin de d�cocher la case activ� pr�s de la ligne TAR dans l'onglet G�n�ral</small>");
define("LM_CONFIG_INFO_ZIP","<small>Si le script n'est pas en mesure de d�terminer le chemin d'acc�s ZIP automatiquement, vous pourriez avoir besoin de d�cocher la case activ� pr�s de la ligne ZIP dans l'onglet G�n�ral</small>");
define("LM_CONFIG_INFO_MSQL","<small>Si le script n'est pas en mesure de d�terminer le chemin d'acc�s MYSQLDUMP automatiquement, vous pourriez avoir besoin de d�cocher la case activ� pr�s de la ligne mysqldump dans l'onglet G�n�ral</small>");
define("LM_CONFIG_INFO_EXEC","<small>Si cette fonction est d�sactiv�e, vous pouvez d�cocher les deux cases �actif� de l'onglet G�n�ral</small>");
define("LM_CONFIG_INFO_BPATH","<small>doit �tre accessible en �criture pour que XCloner acc�de aux sauvegardes d'archives</small>");

// --- TRANSFER DETAILS---//

define("LM_TRANSFER_URL","Adresse du site");
define("LM_TRANSFER_URL_SUB","<small>S'il vous pla�t fournir l'URL du site o� sera d�plac� de sauvegarde, http://www.sitename.com/ exemple, nous avons besoin de cela parce que nous allons vous diriger l� pour acc�der au script de restauration</small>");
define("LM_TRANSFER_FTP_HOST","Nom d'h�te FTP:");
define("LM_TRANSFER_FTP_HOST_SUB","exemple ftp.123456");
define("LM_TRANSFER_FTP_USER","Nom d'utilisateur FTP:");
define("LM_TRANSFER_FTP_USER_SUB","exemple '1234565'");
define("LM_TRANSFER_FTP_PASS","Mot de passe FTP :");
define("LM_TRANSFER_FTP_PASS_SUB","exemple 'test'");
define("LM_TRANSFER_FTP_DIR","R�pertoire ftp:");
define("LM_TRANSFER_FTP_DIR_SUB","S'il vous pla�t indiquer le r�pertoire ftp de l'endroit o� vous souhaitez d�placer la sauvegarde, exemple public_html/ ou htdocs/ et assurez-vous qu'il a les permissions d'�criture pour tout le monde");

// --- GENERATE BACKUP---//

define("LM_BACKUP_NAME","<b>S'il vous pla�t choisissez votre nom de la sauvegarde</b>");
define("LM_BACKUP_NAME_SUB","<small>s'il est laiss� en blanc, cela va g�n�rer un nom par d�faut!</small>");


// -- General --//
define("LM_COM_TITLE"    , "XCloner Manager - ");
define("LM_COM_TITLE_CONFIRM"    , "Confirmer la s�lection des dossiers");

define("LM_COL_FILENAME"    , "Sauvegarde");
define("LM_COL_DOWNLOAD"    , "T�l�charger");
define("LM_COL_AVALAIBLE","Interface Programme");
define("LM_COL_SIZE"    , "Taille");
define("LM_COL_DATE"    , "Date");
define("LM_COL_FOLDER"    , "<b>Dossiers exclus et/ou fichiers</b>");

define("LM_DELETE_FILE_SUCCESS","fichiers supprim�s");
define("LM_DOWNLOAD_TITLE","T�l�charger");

define("LM_ARCHIVE_NAME"    , "Nom Archive");
define("LM_NUMBER_FOLDERS"    , "Nombre de dossiers");
define("LM_NUMBER_FILES"    , "Nombre de fichiers");
define("LM_SIZE_ORIGINAL"    , "Taille du fichier original");
define("LM_SIZE_ARCHIVE"    , "Taille de l'archive");
define("LM_DATABASE_ARCHIVE"    , "Base de donn�es de sauvegarde");

define("LM_CONFIRM_INSTRUCTIONS"    , "<b>S'il vous pla�t s�lectionnez les dossiers que vous souhaitez exclure de l'archive</b>  <br />
                                       - par d�faut, tous les dossiers sont inclus, si vous souhaitez exlure un dossier et un sous-dossiers il suffit de cocher la case � c�t� de lui");
define("LM_CONFIRM_DATABASE"    , "Sauvegarde Base de donn�es");


define("LM_DATABASE_EXCLUDED","Exclus");
define("LM_DATABASE_CURRENT","Base de donn�es courante:");
define("LM_DATABASE_INCLUDE_DATABASES","Inclure d'autres bases");
define("LM_DATABASE_INCLUDE_DATABASES_SUB","vous pouvez s�lectionner plusieurs bases de donn�es � inclure dans la sauvegarde en maintenant la touche CTRL enfonc�e et en s�lectionnant les �l�ments souhait�s avec votre souris, les bases de donn�es seront stock�es dans administrator / r�pertoire de sauvegarde de vos archives");

define("LM_DATABASE_MISSING_TABLES","Erreur: table d�finition non trouv�");
define("LM_DATABASE_BACKUP_FAILED","�chec de la sauvegarde, s'il vous pla�t v�rifiez que l'administrateur / r�pertoire des sauvegardes est accessible en �criture!");
define("LM_DATABASE_BACKUP_COMPLETED","Sauvegarde termin�e");
define("LM_RENAME_TOP","Renommer clones s�lectionn�s");
define("LM_RENAME","Renommer clone");
define("LM_RENAME_TO","�");
// --- CLONER RESTORE--- //

define("LM_CLONER_RESTORE","<h2> Comment faire pour restaurer une sauvegarde sur diff�rents endroits INFO! </h2> <br/>
<pre>
   Restaurer vos sauvegardes n'a jamais �t� aussi facile!
   Avec l'aide de notre fonction de clonage � partir du <a href='index2.php?option=com_cloner&task=view'> Voir les sauvegardes </a>
   vous pourrez d�placer votre sauvegarde n'importe o� sur le site Internet.

   Voici ce que vous avez � faire:

   <b> Etape 1 - d�placer votre sauvegarde pour la restauration </b>

    - Aller dans XCloner 'Voir les Sauvegardes'
    - Apr�s avoir s�lectionn� votre sauvegarde cliquez sur le bouton 'Clone'
    - Entrer les d�tails ftp de l'endroit o� vous souhaitez Cloner la sauvegarde
    - apr�s avoir appuy� pour soumettre la sauvegarde et la restauration le script sera transf�r� sur le nouvel h�te et vous recevrez une url pour acc�der � l'�tape suivante sur la base des url que vous avez fournis pour la localisation � distance
    - Apr�s avoir cliqu� sur le lien fourni, vous serez redirig� vers le nouvel emplacement exemple <b>http://my_restore_site.com/XCloner.php</b>

   <b> Note: </b> Si ce processus �choue pour une raison quelconque, vous devez faire ceci:
   1. T�l�charger la sauvegarde sur votre ordinateur
   2. T�l�charger le script de restauration, tous les fichiers
   administrator/components/com_xcloner-backupandrestore/restore/
   3. Envoyer la sauvegarde et la restauration du script � votre emplacement de restauration
   4. Lancer XCloner.php dans votre navigateur et suivez la restauration comme indiqu� ci-dessous

   <b> �tape 2 - le XCloner.php restauration: </b>

   <b> XCloner.php - le script de restauration </b>
    - � cette �tape, vous avez mis en place la sauvegarde que vous avez cr�� et le script de restauration
    - entrez vos nouvelles coordonn�es mysql, ce qui inclut votre nouveau nom d'h�te MySQL, un utilisateur et mot de passe, et une nouvelle base de donn�es
    diff�rent de celui d'origine
    - Entrez votre nouvelle adresse URL et de suivant
    - Pour restaurer les fichiers que vous avez <b> 2 options: </b>

       - 1. restaurer les fichiers par FTP, le script va simuler un processus de transfert ftp sur votre serveur, cela va r�soudre le probl�me des autorisations de l'�tape 2.
       - 2. restaurer les fichiers directement, cela d�comlpresse les fichiers sur votre serveur, fonctionne plus rapidement, mais elle pourrait donner lieu � des probl�mes de droits si vous utilisez votre ftp beaucoup pour apporter des modifications sur le site

    - Une fois que vous cliquez sur Soumettre le script va tenter de d�placer les fichiers vers le nouveau chemin, directement ou par ftp et
    va installer la base de donn�e
    - Si tout va bien votre nouveau site est op�rationnel sur le nouvel emplacement

    Pour le support s'il vous pla�t consulter notre <a forums href='http://www.xcloner.com/support/forums/' target='_blank'> http://www.xcloner.com/support/forums/ </a>
    ou par courriel � href='mailto:info@xcloner.com'> <a info@xcloner.com </a>


</ pre>");
define("LM_CLONER_ABOUT"," <h2>Sauvegarde XCloner</h2>
       XCloner est un outil qui vous aidera � g�rer vos sauvegardes de votre site, G�n�rer / Restauration / D�placer afin que votre site sera toujours Garanti !
<br/> <br/>
       Caract�ristiques:
<ul>
<li>Script cron pour g�n�rer des sauvegardes automatiques </li>
<li>Plusieurs options de sauvegarde</li>
<li>Outil de restauration pour passer le site rapidement vers d'autres emplacements</li>
<li>Stocker la sauvegarde en local,ou � distance</li>
<li>AJAX/JSON interface de sauvegarde </li>
<li>Code autonome, pouvez sauvegarder n'importe quel PHP / Mysql site web</li>
<li>Base de donn�es et fichiers de backup suppl�mentaire</li>
<li>Balayage de syst�me de fichiers progressif</li>
<li>Amazon S3 support</li>
</ul>
<br/>
Pour les rapports et propositions s'il vous pla�t contacter l'auteur info@xcloner.com ou visiter son site sur <a href='http://www.xcloner.com'>http://www.xcloner.com</a>
                                                    <br/>XCloner.com � 2004-2011 </a> <br/> <p/> <br/>");
define("LM_LOGIN_TEXT","<pre>
<b>Notes:</b>
 1. Si vous �tes sur cet �cran pour la premi�re fois,par d�faut le nom d'utilisateur est <b> '<i>admin</i>' et le mot de passe est '<i>admin</i>'</b>

 2. si vous avez oubli� votre mot de passe, pour le r�initialiser, vous devez ajouter ce code:
 <b>$"."_CONFIG[\"jcpass\"] = md5(\"my_new_pass\");</b>
� la fin du fichier de configuration <b> cloner.config.php juste avant la ligne ?></b>
Ne pas oublier de remplacer le mot de passe <b>my_new_pass </b> par votre mot de passe r�el
</pre>");
?>