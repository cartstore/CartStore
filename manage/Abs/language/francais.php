<?php

// --- FRONT AREA---//
define("LM_FRONT_CHOOSE_PACKAGE","<b>Choisissez votre installation: </b>");
define("LM_FRONT_CHOOSE_PACKAGE_SUB","<small>S'il vous plaît choisissez votre version de joomla ou de wordpress que vous souhaitez installer </small>");
define("LM_FRONT_TOP","<span colspan='2' class='contentheading'> Installez le Logiciel en ligne</span>");
define("LM_FRONT_TOP_FTP_DETAILS","<h2>Fournissez vos détails ftp ci-dessous: </h2>");
define("LM_FRONT_WEBSITE_URL","<b>Url de votre site</b>");
define("LM_FRONT_WEBSITE_URL_SUB","<small>S'il vous plaît fournissez l'url du site Web Joomla ou wordpress sera installé, exemple http://www.sitename.com/Joomla ou Wordpress </small>");
define("LM_FRONT_FTP_HOST","<b>Nom du ftp:</b>");
define("LM_FRONT_FTP_HOST_SUB","<small>exemple ftp://123456878.fr</small>");
define("LM_FRONT_FTP_USER","<b>Login Ftp:</b>");
define("LM_FRONT_FTP_USER_SUB","<small>exmple 12345</small>");
define("LM_FRONT_FTP_PASS","<b>Mot de passe Ftp:</b>");
define("LM_FRONT_FTP_PASS_SUB","<small>exemple 5412</small>");
define("LM_FRONT_FTP_DIR","<b>Répertoire Ftp</b>");
define("LM_FRONT_FTP_DIR_SUB","<small>S'il vous plaît indiquer le répertoire du ftp où vous aimeriez installer Joomla ou wordpress, exemple public_html / Joomla ou wordpress / ou htdocs / Joomla ou wordpress / et assurer vous d'avoir donné les autorisations nécéssaires CHMOD</small>");
define("LM_TRANSFER_FTP_INCT","transfert Croissant:");
define("LM_TRANSFER_FTP_INCT_SUB","Transfert des fichiers par FTP en mode incrémental afin d'éviter toute pages blanches ou des délais d'expiration");
define("LM_FRONT_BOTTOM","une erreur <a href='http://www.xcloner.com/contact/'>Page de Contact</a><br/>Proposé par <a href='http://www.xcloner.com'>XCloner</a>");
define("LM_FRONT_MSG_OK","Nous avons transféré la sauvegarde sur votre site ftp, pour continuer cliquez ici");
define("LM_NOPAKCAGE_ERROR","il n'y a aucun paquet sélectionné, erreur...");

// --- BACKEND AREA---//

//Amazon S3
define("LM_AMAZON_S3","Amazon S3");
define("LM_AMAZON_S3_ACTIVATE","Activer");
define("LM_AMAZON_S3_AWSACCESSKEY","Clef de l'Accès:");
define("LM_AMAZON_S3_AWSSECRETKEY","AWS Clef Secrète:");
define("LM_AMAZON_S3_BUCKET","nom Bucket:");
define("LM_AMAZON_S3_DIRNAME","Télécharger le Répertoire:");


define("LM_DATABASE_EXCLUDE_TABLES","Sélectionner les tables à exclure de la sauvegarde");
define("LM_CONFIG_SYSTEM_FOLDER","Afficher les dossiers:");
define("LM_CONFIG_SYSTEM_FOLDER_SUB","s'il vous plaît sélectionner les dossiers à exclure de votre sauvegarde");
define("LM_CONFIG_SYSTEM_LANG","Langue du système:");
define("LM_CONFIG_SYSTEM_LANG_SUB","Configurer la langue pour XCloner,par défaut cela sera celle de Joomla ou wordpress si elle est disponible");
define("LM_CONFIG_SYSTEM_LANG_DEFAULT","Système par défaut");
define("LM_CONFIG_SYSTEM_DOWNLOAD","Activer lien de téléchargement direct:");
define("LM_CONFIG_SYSTEM_DOWNLOAD_SUB","si cette case est cochée, l'écran 'View Backups',le lien de téléchargement sera un lien direct à partir de votre serveur afin de télécharger l'ensemble, s'il vous plaît noter que le chemin de sauvegarde doit être dans le chemin d'accès racine de Joomla ou wordpress");
define("LM_CONFIG_DISPLAY","Paramètres d'affichage");
define("LM_CONFIG_SYSTEM","Paramètres système");
define("LM_CONFIG_SYSTEM_FTP","Mode de transfert FTP");
define("LM_CONFIG_SYSTEM_FTP_SUB","Choisir comment les fichiers seront transférés de serveur à serveur lorsque vous utilisez le protocole FTP");
define("LM_CONFIG_MEM","Sauvegarde en utilisant les fonctions du Serveur");
define("LM_CONFIG_MEM_SUB","<small>Si la valeur est active, il vous sera demandé d'avoir sur votre support pour serveur l'utilisation <b> pour zip ou tar </b>et / ou <b> mysqldump</b> en commandes et de préciser leurs chemins, et aussi <b > exec () </b> l'accès dans votre PHP</small>");
define("LM_CRON_DB_BACKUP","Activer la sauvegarde de base de données:");
define("LM_CRON_DB_BACKUP_SUB","<small>cochez <b>Oui</b> si vous voulez sauvegarder les données mysql</small>");
define("LM_CONFIG_SYSTEM_MBACKUP","Inclure les sauvegardes dans le répertoire clone:");
define("LM_CONFIG_SYSTEM_MBACKUP_SUB","<small>Si réglé sur <b>Oui</b>, la sauvegarde crée contiendra également des fichiers des sauvegardes précédentes, ce qui augmente à chaque fois sa taille</small>");

define("LM_TAB_MYSQL","MYSQL ou MYSQLI");
define("LM_CONFIG_MYSQL","Paramètres de connexion MySQL:");
define("LM_CONFIG_MYSQLH","Nom d'hôte Mysql:");
define("LM_CONFIG_MYSQLU","Nom d'utilisateur MySQL:");
define("LM_CONFIG_MYSQLP","Mot de passe Mysql:");
define("LM_CONFIG_MYSQLD","Base de données Mysql:");

define("LM_TAB_AUTH","Authentification");
define("LM_CONFIG_AUTH","Espace d'authentification de l'utilisateur");
define("LM_CONFIG_AUTH_USER","Utilisateur:");
define("LM_CONFIG_AUTH_PASS","Mot de passe:");
define("LM_CONFIG_AUTH_USER_SUB","Votre login utilisateur par défaut à XCloner");
define("LM_CONFIG_AUTH_PASS_SUB","votre mot de passe de connexion par défaut, laissez en blanc si vous ne voulez pas le changer");

define("LM_YES","Oui");
define("LM_NO","Non");
define("LM_ACTIVE","Activer");
define("LM_TAR_PATH","Chemin path ou commande:");
define("LM_TAR_PATH_SUB","(obligatoire si le type d'archive est TAR et la case cochée est activée)");
define("LM_ZIP_PATH","Chemin du Zip ou de la commande:");
define("LM_ZIP_PATH_SUB","(obligatoire si le type d'archive est ZIP et la case cochée est activée)");
define("LM_MYSQLDUMP_PATH","Chemin de mysqldump ou de commande: (obligatoire si la case Active est cochée) <br/> Pour les grands dumps mysql s'il vous plaît utiliser
<br/> <b> <small> mysqldump - quote-names - rapide - single-transaction - skip-comment </b> </small>");

// --- CONFIG ---//
define("LM_CONFIG_MANUAL","Processus de sauvegarde manuelle");
define("LM_CONFIG_MANUAL_BACKUP","Sauvegarde manuelle:");
define("LM_CONFIG_MANUAL_BACKUP_SUB","Cette option est indiquée si vous avez dans php des limitations de temps d'exécution sur votre serveur, il faudra javascript activé sur votre navigateur");
define("LM_CONFIG_MANUAL_FILES","Fichiers à traiter par la requête:");
define("LM_CONFIG_DB_RECORDS","Enregistrements de base de données selon la requête");
define("LM_CONFIG_MANUAL_REFRESH","Temps entre les requêtes:");
define("LM_CONFIG_SYSTEM_MDATABASES","Sauvegarde des bases de données multiples:");
define("LM_CONFIG_SYSTEM_MDATABASES_SUB","Cette option activé XCloner peut sauvegarder plusieurs bases de données");
define("LM_CONFIG_EXCLUDE_FILES_SIZE","Exclure les fichiers de plus de:");

define("LM_CONFIG_CRON_LOCAL","Serveur local*");
define("LM_CONFIG_CRON_REMOTE","Compte ftp à distance");
define("LM_CONFIG_CRON_EMAIL","Courrier électronique**");
define("LM_CONFIG_CRON_FULL","Intégral (fichiers + base de données)");
define("LM_CONFIG_CRON_FILES","Uniquement les fichiers");
define("LM_CONFIG_CRON_DATABASE","Base de données uniquement");

define("LM_CONFIG_EDIT","Modification du fichier de configuration");
define("LM_CONFIG_BSETTINGS","Paramètres du chemin de sauvegarde");
define("LM_CONFIG_BSETTINGS_OPTIONS","Options général de sauvegarde");
define("LM_CONFIG_BSETTINGS_SERVER","Utiliser les options serveur");
define("LM_CONFIG_BPATH","Chemin de sauvegarde:");
define("LM_CONFIG_UBPATH","Démarrer la sauvegarde:");
define("LM_CONFIG_BPATH_SUB","<small>Chemin où toutes les sauvegardes seront stockées</small>");
define("LM_CONFIG_UBPATH_SUB","<small>désigner un chemin pour la sauvegarde initiale, d'où XCloner va commencer tous les processus</small>");
define("LM_CRON_EXCLUDE","Répertoires exclus");
define("LM_CRON_EXCLUDE_DIR","Exclure la liste des répertoires un par ligne: <br> s'il vous plaît utiliser le chemin complet du répertoire du serveur");
define("LM_CRON_BNAME","Nom de la sauvegarde:");
define("LM_CRON_BNAME_SUB","<small>S'il est laissé en blanc, cela va générer automatiquement un nom par défaut à chaque nouvelle sauvegarde</small>");
define("LM_CRON_IP","Cron admis IP's:");
define("LM_CRON_IP_SUB","<small>Par défaut, seul le serveur local aura accès à la tâche CRON, mais vous pouvez entrer aussi une autre adresse IP, une par ligne</small>");
define("LM_CRON_DELETE_FILES","Supprimer sauvegardes les plus anciennes");
define("LM_CRON_DELETE_FILES_SUB","Supprimer des sauvegardes anciennes de:");
define("LM_CRON_DELETE_FILES_SUB_ACTIVE","Activer");
define("LM_CRON_SEMAIL","Email journal de cron à:");
define("LM_CRON_SEMAIL_SUB","Si une adresse e-mail est inscrite, après l'exécution d'une tâche cron, le journal sera envoyé à cette adresse, des adresses multiples peuvent être ajoutés en les séparants par <b>;</b>");
define("LM_CRON_MCRON","Nom de la configuration:");
define("LM_CRON_MCRON_AVAIL","Configurations disponibles:");
define("LM_CRON_MCRON_R","s'il vous plaît donner un nom simple pour la configuration de votre nouvelle cron");
define("LM_CRON_MCRON_SUB","Avec cette option, vous serez en mesure d'enregistrer la configuration actuelle dans un fichier séparé et de l'utiliser pour l'exécution de tâches cron multiples");
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
define("LM_MENU_Generate_backup","Générer des sauvegardes");
define("LM_MENU_Restore_backup","Restaurer la sauvegarde");
define("LM_MENU_View_backups","Voir les sauvegardes");
define("LM_MENU_Documentation","Aide");
define("LM_MENU_ABOUT","A propos de");
define("LM_DELETE_FILE_FAILED","Échec de la suppression, s'il vous plaît vérifier les permissions sur les fichiers");
define("LM_Joomla ou wordpressPLUG_CP","XCloner - Votre site de sauvegarde et de restauration solution");
define("LM_MENU_FORUM","Forum en ligne");
define("LM_MENU_SUPPORT","Support en ligne");
define("LM_MENU_WEBSITE","Site Web");

define("LM_MAIN_Settings","Paramètres");
define("LM_MAIN_View_Backups","Voir les sauvegardes");
define("LM_MAIN_Generate_Backup","Générer des sauvegardes");
define("LM_MAIN_Help","Aide");
define("LM_FTP_TRANSFER_MORE","Mode de connexion FTP");
define("LM_REFRESH_MODE","Rafraîchir sauvegarde");
define("LM_DEBUG_MODE","Activer le journal:");
define("LM_REFRESH_ERROR","Il y a eu une erreur d'extraction des données JSON à partir du serveur, essayez à nouveau ou contacter les développeurs!");

// --- LANGUAGE --//
define("LM_LANG_NAME","Nom de la langue");
define("LM_LANG_MSG_DEL","Langue(s) supprimé avec succès!");
define("LM_LANG_NEW","Nom de la nouvelle langue:");
define("LM_LANG_EDIT_FILE","Édition du fichier:");
define("LM_LANG_EDIT_FILE_SUB","Ne pas oublier de sauvegarder votre traduction toutes les 5 minutes, appuyez simplement sur le bouton Appliquer pour mettre à jour");

// --- TABS --//
define("LM_TAB_GENERAL","Général");
define("LM_TAB_G_STRUCTURE","Structures");
define("LM_TAB_SYSTEM","Système");
define("LM_TAB_CRON","Cron");
define("LM_TAB_INFO","Info");
define("LM_TAB_G_DATABASE","Options de base de données");
define("LM_TAB_G_FILES","Options Fichiers");
define("LM_TAB_G_COMMENTS","Commentaires sauvegardes");
define("LM_G_EXCLUDE_COMMENT","<br>S'il vous plaît entrer ici les dossiers à exclure,un par ligne!
     <br><b> vous pouvez désactiver la fonction du cache lorsque vous effectuez une sauvegarde, ou ne pas exclure le dossier cache de la sauvegarde </b>");
define("LM_TAB_G_COMMENTS_H2","Saisissez ci-dessous tout commentaire supplémentaire pour archiver:");
define("LM_TAB_G_COMMENTS_NOTE","S'il vous plaît noter que les commentaires sont stockés dans les archives <b>administrator/backups/.comments</b>");

// --- MESSAGES --//
// front end
define("LM_MSG_FRONT_1","Aucune sauvegarde disponible");
define("LM_MSG_FRONT_2","Chargement FTP a échoué pour la destination");
define("LM_MSG_FRONT_3","Envoi effectué pour");
define("LM_MSG_FRONT_4","Connexion FTP a échoué!");
define("LM_MSG_FRONT_5","Tentative de connexion à");
define("LM_MSG_FRONT_6","pour l'utilisateur");

//backend
define("LM_MSG_BACK_1","Configuration mise à jour ...");
define("LM_MSG_BACK_2","Connexion FTP a échoué!");
define("LM_MSG_BACK_3","Déplacement de la sauvegarde FAITE! La sauvegarde sélectionnez doit maintenant être disponible à l'emplacement prévu!");
define("LM_MSG_BACK_4","Déplacement fait, démarrer le processus de clonage sur l'hôte distant");
define("LM_MSG_BACK_5","Ensemble non publiées à partir de l'interface");
define("LM_MSG_BACK_6","Erreur...S'il vous plaît vérifier vos chemins!");
define("LM_MSG_BACK_7","Publié avec succès pour Interface");
define("LM_MSG_BACK_8","Erreur...S'il vous plaît vérifier vos chemins!");
define("LM_MSG_BACK_9","Clones renommé avec succès!");
define("LM_MSG_BACK_10","Le chemin d'accès de Joomla ou wordpress n'est pas au sein de votre répertoire de sauvegarde! Impossible d'utiliser le mode de téléchargement direct!");
define("LM_MSG_BACK_11","Tout est fait! Tout est fait! Le processus de sauvegarde manuel est fini! <a href='index2.php?option=com_cloner&task=view'>Cliquer ici pour continuer </a>");
define("LM_MSG_BACK_12","<h2>La sauvegarde a échoué! S'il vous plaît vérifiez que vous avez le support de l'utilitaire zip (/ usr / bin / zip ou / usr / local / bin / zip) sur votre serveur et que le chemin d'accès soit correcte ou choisir le type d'archive Tar!</h2>");
define("LM_MSG_BACK_13","<h2>La sauvegarde a échoué! S'il vous plaît vérifiez que vous avez le support de l'utilitaire zip (/ usr / bin / zip ou / usr / local / bin / zip) sur votre serveur et que le chemin d'accès soit correcte ou choisir le type d'archive ZIP!</h2>");
define("LM_MSG_BACK_14","<font color='red'>Il y a eu un problème dans la création de la sauvegarde de base de données, s'il vous plaît vérifiez le chemin du serveur mysqldump!</font>");



define("LM_CRON_TOP","Commande de configuration Cron");
define("LM_CRON_SUB","<b>Utilisation de la fonction cron, vous pouvez configurer un générateur automatique de sauvegarde pour votre site web ! </b>
<br/> Pour l'installer, vous devez ajouter à votre panneau de configuration <b>crontab</b> l'une des commandes suivantes:");
define("LM_CRON_HELP","<b>Notes:<br>
 - Si vous avez dans votre php un emplacement différent de celui / usr / bin / php s'il vous plaît utiliser ce format /$"."php_path/php </b>
<br>

Pour plus d'informations sur comment configurer un cron pour
 <br>- Cpanel <a href='http://www.cpanel.net/docs/cpanel/' target='_blank'>Cliquer Ici</a>
 <br>- Plesk <a href='http://www.swsoft.com/doc/tutorials/Plesk/Plesk7/plesk_plesk7_eu/plesk7_eu_crontab.htm' target='_blank'>Cliquer Ici</a>
 <br>- Interworx <a href='http://www.sagonet.com/interworx/tutorials/siteworx/cron.php' target='_blank'>Cliquer Ici</a>
 <br>- Informations générales crontab Linux <a href='http://www.computerhope.com/unix/ucrontab.htm#01' target='_blank'>Cliquer Ici</a>
<br> Si vous avez besoin d'aide pour configurer votre CRON, s'il vous plaît visitez notre forum <a href='http://www.xcloner.com/support/forums/' target='_blank'>http://www.xcloner.com/support/forums/</a>");
define("LM_CRON_SETTINGS","Paramètres Cron");
define("LM_CRON_MODE","Mode de stockage sauvegarde:");
define("LM_CRON_MODE_INFO"," <br/>
      <small> S'il vous plaît noter: * si le serveur local est choisi nous allons utiliser le chemin de sauvegarde par défaut pour stocker la sauvegarde</small>
      <br/>
      <small> S'il vous plaît noter: ** si le mode email est utilisée, nous avons pas de garantie que la sauvegarde sera portée au compte de messagerie en raison de la limitation fournisseur</small>");
define("LM_CRON_TYPE_INFO","<small><br/> s'il vous plaît choisir votre type de sauvegarde que vous souhaitez créer</small>");
define("LM_CRON_MYSQL_DETAILS","Options Mysql");
define("LM_CRON_MYSQL_DROP","Ajouter Mysql Drop");
define("LM_CRON_TYPE","Type de sauvegarde:");
define("LM_CRON_FTP_DETAILS","Sauvegarder configuration FTP:");
define("LM_CRON_FTP_SERVER","Serveur ftp:");
define("LM_CRON_FTP_USER","Nom d'utilisateur FTP:");
define("LM_CRON_FTP_PASS","Mot de passe FTP:");
define("LM_CRON_FTP_PATH","chemin d'accès FTP:");
define("LM_CRON_FTP_DELB","Supprimer sauvegarde après le transfert");
define("LM_CRON_EMAIL_DETAILS","détails Email :");
define("LM_CRON_EMAIL_ACCOUNT","Compte Email:");
define("LM_CRON_COMPRESS","Compresser les fichiers de sauvegarde:");
define("LM_RESTORE_TOP","Information restauration de votre sauvegarde");
define("LM_CREDIT_TOP","A propos de XCloner");
define("LM_CLONE_FORM_TOP","<h2>Fournir les détails de votre ftp ci-dessous:</h2>");

// --- Info Tab ---//

define("LM_CONFIG_INFO_T_SAFEMODE","Mode sans échec PHP:");
define("LM_CONFIG_INFO_T_VERSION","Vérification de la version PHP:");
define("LM_CONFIG_INFO_T_MTIME","Temps maximal d'exécution:");
define("LM_CONFIG_INFO_T_MEML","Limite mémoire:");
define("LM_CONFIG_INFO_T_BDIR","Ouverture base PHP");
define("LM_CONFIG_INFO_T_EXEC","exec () support:");
define("LM_CONFIG_INFO_T_TAR","chemin d'accès Tar:");
define("LM_CONFIG_INFO_T_ZIP","chemin d'accès Zip:");
define("LM_CONFIG_INFO_T_MSQL","chemin d'accès mysqldump:");
define("LM_CONFIG_INFO_T_BPATH","Chemin de sauvegarde:");
define("LM_CONFIG_INFO_ROOT_PATH_SUB","le chemin d'accès du lancement de la sauvegarde doit exister et être lisibles pour que XCloner puisse démarrer le processus de sauvegarde");
define("LM_CONFIG_INFO_ROOT_BPATH_TMP","Dossier temporaire");
define("LM_CONFIG_INFO_ROOT_PATH_TMP_SUB","Le chemin d'accès <i>[Backup Start Path/administrator/backups]</i> doit être crée et être accessible en écriture pour que XCloner fonctionne correctement");



define("LM_CONFIG_INFO","Cet onglet affiche des informations système général et les chemins d'accès");
define("LM_CONFIG_INFO_PATHS","Info Général chemin d'accès:");
define("LM_CONFIG_INFO_PHP","Information configuration Php:");
define("LM_CONFIG_INFO_TIME","<small>Cela contrôle le temps maximum d'éxécution du script vers votre serveur</small>");
define("LM_CONFIG_INFO_MEMORY","<small> Ce contrôle la quantité maximale de mémoire le script peut allouer à ses processus </small>");
define("LM_CONFIG_INFO_BASEDIR","<small>Cela contrôle les chemins d'accès de votre script autorisé à accéder, aucune valeur signifie qu'il peut accéder à n'importe quel chemin d'accès</small>");
define("LM_CONFIG_INFO_SAFEMODE","<small> mode sans échec devra être réglé sur Off pour que XCloner pour fonctionner correctement </small>");
define("LM_CONFIG_INFO_VERSION","<small> PHP> = 5.2.3 est nécessaire</small>");
define("LM_CONFIG_INFO_TAR","<small>Si le script n'est pas en mesure de déterminer le chemin d'accès de TAR automatiquement, vous pourriez avoir besoin de décocher la case activé près de la ligne TAR dans l'onglet Général</small>");
define("LM_CONFIG_INFO_ZIP","<small>Si le script n'est pas en mesure de déterminer le chemin d'accès ZIP automatiquement, vous pourriez avoir besoin de décocher la case activé près de la ligne ZIP dans l'onglet Général</small>");
define("LM_CONFIG_INFO_MSQL","<small>Si le script n'est pas en mesure de déterminer le chemin d'accès MYSQLDUMP automatiquement, vous pourriez avoir besoin de décocher la case activé près de la ligne mysqldump dans l'onglet Général</small>");
define("LM_CONFIG_INFO_EXEC","<small>Si cette fonction est désactivée, vous pouvez décocher les deux cases «actif» de l'onglet Général</small>");
define("LM_CONFIG_INFO_BPATH","<small>doit être accessible en écriture pour que XCloner accède aux sauvegardes d'archives</small>");

// --- TRANSFER DETAILS---//

define("LM_TRANSFER_URL","Adresse du site");
define("LM_TRANSFER_URL_SUB","<small>S'il vous plaît fournir l'URL du site où sera déplacé de sauvegarde, http://www.sitename.com/ exemple, nous avons besoin de cela parce que nous allons vous diriger là pour accéder au script de restauration</small>");
define("LM_TRANSFER_FTP_HOST","Nom d'hôte FTP:");
define("LM_TRANSFER_FTP_HOST_SUB","exemple ftp.123456");
define("LM_TRANSFER_FTP_USER","Nom d'utilisateur FTP:");
define("LM_TRANSFER_FTP_USER_SUB","exemple '1234565'");
define("LM_TRANSFER_FTP_PASS","Mot de passe FTP :");
define("LM_TRANSFER_FTP_PASS_SUB","exemple 'test'");
define("LM_TRANSFER_FTP_DIR","Répertoire ftp:");
define("LM_TRANSFER_FTP_DIR_SUB","S'il vous plaît indiquer le répertoire ftp de l'endroit où vous souhaitez déplacer la sauvegarde, exemple public_html/ ou htdocs/ et assurez-vous qu'il a les permissions d'écriture pour tout le monde");

// --- GENERATE BACKUP---//

define("LM_BACKUP_NAME","<b>S'il vous plaît choisissez votre nom de la sauvegarde</b>");
define("LM_BACKUP_NAME_SUB","<small>s'il est laissé en blanc, cela va générer un nom par défaut!</small>");


// -- General --//
define("LM_COM_TITLE"    , "XCloner Manager - ");
define("LM_COM_TITLE_CONFIRM"    , "Confirmer la sélection des dossiers");

define("LM_COL_FILENAME"    , "Sauvegarde");
define("LM_COL_DOWNLOAD"    , "Télécharger");
define("LM_COL_AVALAIBLE","Interface Programme");
define("LM_COL_SIZE"    , "Taille");
define("LM_COL_DATE"    , "Date");
define("LM_COL_FOLDER"    , "<b>Dossiers exclus et/ou fichiers</b>");

define("LM_DELETE_FILE_SUCCESS","fichiers supprimés");
define("LM_DOWNLOAD_TITLE","Télécharger");

define("LM_ARCHIVE_NAME"    , "Nom Archive");
define("LM_NUMBER_FOLDERS"    , "Nombre de dossiers");
define("LM_NUMBER_FILES"    , "Nombre de fichiers");
define("LM_SIZE_ORIGINAL"    , "Taille du fichier original");
define("LM_SIZE_ARCHIVE"    , "Taille de l'archive");
define("LM_DATABASE_ARCHIVE"    , "Base de données de sauvegarde");

define("LM_CONFIRM_INSTRUCTIONS"    , "<b>S'il vous plaît sélectionnez les dossiers que vous souhaitez exclure de l'archive</b>  <br />
                                       - par défaut, tous les dossiers sont inclus, si vous souhaitez exlure un dossier et un sous-dossiers il suffit de cocher la case à côté de lui");
define("LM_CONFIRM_DATABASE"    , "Sauvegarde Base de données");


define("LM_DATABASE_EXCLUDED","Exclus");
define("LM_DATABASE_CURRENT","Base de données courante:");
define("LM_DATABASE_INCLUDE_DATABASES","Inclure d'autres bases");
define("LM_DATABASE_INCLUDE_DATABASES_SUB","vous pouvez sélectionner plusieurs bases de données à inclure dans la sauvegarde en maintenant la touche CTRL enfoncée et en sélectionnant les éléments souhaités avec votre souris, les bases de données seront stockées dans administrator / répertoire de sauvegarde de vos archives");

define("LM_DATABASE_MISSING_TABLES","Erreur: table définition non trouvé");
define("LM_DATABASE_BACKUP_FAILED","Échec de la sauvegarde, s'il vous plaît vérifiez que l'administrateur / répertoire des sauvegardes est accessible en écriture!");
define("LM_DATABASE_BACKUP_COMPLETED","Sauvegarde terminée");
define("LM_RENAME_TOP","Renommer clones sélectionnés");
define("LM_RENAME","Renommer clone");
define("LM_RENAME_TO","à");
// --- CLONER RESTORE--- //

define("LM_CLONER_RESTORE","<h2> Comment faire pour restaurer une sauvegarde sur différents endroits INFO! </h2> <br/>
<pre>
   Restaurer vos sauvegardes n'a jamais été aussi facile!
   Avec l'aide de notre fonction de clonage à partir du <a href='index2.php?option=com_cloner&task=view'> Voir les sauvegardes </a>
   vous pourrez déplacer votre sauvegarde n'importe où sur le site Internet.

   Voici ce que vous avez à faire:

   <b> Etape 1 - déplacer votre sauvegarde pour la restauration </b>

    - Aller dans XCloner 'Voir les Sauvegardes'
    - Après avoir sélectionné votre sauvegarde cliquez sur le bouton 'Clone'
    - Entrer les détails ftp de l'endroit où vous souhaitez Cloner la sauvegarde
    - après avoir appuyé pour soumettre la sauvegarde et la restauration le script sera transféré sur le nouvel hôte et vous recevrez une url pour accéder à l'étape suivante sur la base des url que vous avez fournis pour la localisation à distance
    - Après avoir cliqué sur le lien fourni, vous serez redirigé vers le nouvel emplacement exemple <b>http://my_restore_site.com/XCloner.php</b>

   <b> Note: </b> Si ce processus échoue pour une raison quelconque, vous devez faire ceci:
   1. Télécharger la sauvegarde sur votre ordinateur
   2. Télécharger le script de restauration, tous les fichiers
   administrator/components/com_xcloner-backupandrestore/restore/
   3. Envoyer la sauvegarde et la restauration du script à votre emplacement de restauration
   4. Lancer XCloner.php dans votre navigateur et suivez la restauration comme indiqué ci-dessous

   <b> Étape 2 - le XCloner.php restauration: </b>

   <b> XCloner.php - le script de restauration </b>
    - À cette étape, vous avez mis en place la sauvegarde que vous avez créé et le script de restauration
    - entrez vos nouvelles coordonnées mysql, ce qui inclut votre nouveau nom d'hôte MySQL, un utilisateur et mot de passe, et une nouvelle base de données
    différent de celui d'origine
    - Entrez votre nouvelle adresse URL et de suivant
    - Pour restaurer les fichiers que vous avez <b> 2 options: </b>

       - 1. restaurer les fichiers par FTP, le script va simuler un processus de transfert ftp sur votre serveur, cela va résoudre le problème des autorisations de l'étape 2.
       - 2. restaurer les fichiers directement, cela décomlpresse les fichiers sur votre serveur, fonctionne plus rapidement, mais elle pourrait donner lieu à des problèmes de droits si vous utilisez votre ftp beaucoup pour apporter des modifications sur le site

    - Une fois que vous cliquez sur Soumettre le script va tenter de déplacer les fichiers vers le nouveau chemin, directement ou par ftp et
    va installer la base de donnée
    - Si tout va bien votre nouveau site est opérationnel sur le nouvel emplacement

    Pour le support s'il vous plaît consulter notre <a forums href='http://www.xcloner.com/support/forums/' target='_blank'> http://www.xcloner.com/support/forums/ </a>
    ou par courriel à href='mailto:info@xcloner.com'> <a info@xcloner.com </a>


</ pre>");
define("LM_CLONER_ABOUT"," <h2>Sauvegarde XCloner</h2>
       XCloner est un outil qui vous aidera à gérer vos sauvegardes de votre site, Générer / Restauration / Déplacer afin que votre site sera toujours Garanti !
<br/> <br/>
       Caractéristiques:
<ul>
<li>Script cron pour générer des sauvegardes automatiques </li>
<li>Plusieurs options de sauvegarde</li>
<li>Outil de restauration pour passer le site rapidement vers d'autres emplacements</li>
<li>Stocker la sauvegarde en local,ou à distance</li>
<li>AJAX/JSON interface de sauvegarde </li>
<li>Code autonome, pouvez sauvegarder n'importe quel PHP / Mysql site web</li>
<li>Base de données et fichiers de backup supplémentaire</li>
<li>Balayage de système de fichiers progressif</li>
<li>Amazon S3 support</li>
</ul>
<br/>
Pour les rapports et propositions s'il vous plaît contacter l'auteur info@xcloner.com ou visiter son site sur <a href='http://www.xcloner.com'>http://www.xcloner.com</a>
                                                    <br/>XCloner.com © 2004-2011 </a> <br/> <p/> <br/>");
define("LM_LOGIN_TEXT","<pre>
<b>Notes:</b>
 1. Si vous êtes sur cet écran pour la première fois,par défaut le nom d'utilisateur est <b> '<i>admin</i>' et le mot de passe est '<i>admin</i>'</b>

 2. si vous avez oublié votre mot de passe, pour le réinitialiser, vous devez ajouter ce code:
 <b>$"."_CONFIG[\"jcpass\"] = md5(\"my_new_pass\");</b>
à la fin du fichier de configuration <b> cloner.config.php juste avant la ligne ?></b>
Ne pas oublier de remplacer le mot de passe <b>my_new_pass </b> par votre mot de passe réel
</pre>");
?>