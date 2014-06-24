<?php
/*
 *      admin.cloner.html.php
 *
 *      Copyright 2011 Ovidiu Liuta <info@thinkovi.com>
 *
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */


/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

if($_COOKIE['auth_clone'] != 1)
 setcookie('auth_clone', '1');

class mosTabs{

	function mosTabs($int){

		echo "<div id=\"tabs\">";

	}

	function startTab($name, $class){

		echo "<div id=\"tabs-$class\"><p>";


	}

	function endTab(){

		echo "</pp></div>";

	}

	function endPane(){

		echo "</div>";
	}

}

/**
* @package Joomla
* @subpackage JoomlaCloner
*/
class HTML_cloner {

function header(){

	global $mosConfig_live_site, $task;
	$excl_tasks = array("view", "config","");

	/*if(!in_array($_REQUEST['task'], $excl_tasks)){
		$seconds_to_cache = 3600;
		$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
		@header("Expires: $ts");
		@header("Pragma: cache");
		@header("Cache-Control: maxage=$seconds_to_cache");
	}*/
?>

<!DOCTYPE html>
 <html class=" js no-touch localstorage svg">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>CartStore Administration</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
 
 		<link href="favicon.ico" rel="shortcut icon" type="image/x-icon">
 		<link href="../templates/responsive-red/assets/light-theme.css" media="all" id="color-settings-body-color" rel="stylesheet" type="text/css">
 
		<link href="../templates/responsive-red/assets/bootstrap.css" media="all" rel="stylesheet" type="text/css">

 	  

		<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
				<link href="//codeorigin.jquery.com/ui/1.10.3/themes/blitzer/jquery-ui.css" rel="stylesheet">
<!-- / jquery [required] -->
		<script src="../templates/responsive-red/assets/jquery.min.js" type="text/javascript"></script>
		<!-- / jquery mobile (for touch events) -->
		<script src="../templates/responsive-red/assets/jquery.mobile.custom.min.js" type="text/javascript"></script>
		<!-- / jquery migrate (for compatibility with new jquery) [required] -->
		<script src="../templates/responsive-red/assets/jquery-migrate.min.js" type="text/javascript"></script>
		<!-- / jquery ui -->
		<script src="../templates/responsive-red/assets/jquery-ui.min.js" type="text/javascript"></script>
		<!-- / jQuery UI Touch Punch -->
		<script src="../templates/responsive-red/assets/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
		<!-- / bootstrap [required] -->
		<script src="../templates/responsive-red/assets/bootstrap.js" type="text/javascript"></script>
		<!-- / modernizr -->
		<script src="../templates/responsive-red/assets/modernizr.min.js" type="text/javascript"></script>
		<!-- / retina -->
		<script src="../templates/responsive-red/assets/retina.js" type="text/javascript"></script>
		<!-- / theme file [required] -->
		<script src="../templates/responsive-red/assets/theme.js" type="text/javascript"></script>
 		<!-- / END - page related files and scripts [optional] -->
 		
 		
 		
 		<script src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>../templates/jquery.init.local.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>../ckeditor/ckeditor.js"></script>

<script type="text/javascript" src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>../ckfinder/ckfinder.js"></script>

<script language="javascript" src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>../includes/general.js"></script>
		 

	</head>
	<body class="contrast-red " style="">
		<header>
			<nav class="navbar navbar-inverse">
				<a class="navbar-brand" href="./"> <img width="81" height="auto" class="logo" alt="CartStore" src="../templates/responsive-red/assets/logo.png"> <i class="fa fa-dashboard"></i> </a>
 				<ul class="nav">
					<li class="dropdown light only-icon">
						<a class="dropdown-toggle" data-toggle="dropdown" href="orders.php"> <i class="icon-cog"></i> </a>
						<ul class="dropdown-menu color-settings">
							<li class="color-settings-body-color">
								
										
<a href="javascript:popUp('./Abs/')">View Backups</a>	
 								<a href="configuration.php?gID=1"> Generate Backup </a>
								 
								
							</li>

						</ul>
					 
                                            
					
                                            
				
                                            
			</nav>
		</header>
<div class="container">


<script type="text/javascript">

/* Optional: Temporarily hide the "tabber" class so it does not "flash"
   on the page as plain HTML. After tabber runs, the class is changed
   to "tabberlive" and it will appear. */

document.write('<style type="text/css">.tabber{display:none;}<\/style>');
</script>



 

<div class="status ">

    
		<div id="toolbar" style="display:none;margin-top:8px;margin-bottom:8px;">         

		<?php
		# Generating the buttons...
		require_once( "toolbar.cloner.php" );
		?>  
		</div>

    
</div>
<div class="clear"></div>

        
 


 


<?php
if($_REQUEST['mosmsg']!="")

 echo "<h2>".strip_tags($_REQUEST['mosmsg'])."</h2>";

}

function footer(){

?>
 
<script> $( "#toolbar" ).show(); </script>
 

<?php

}

function goRefreshHtml($filename, $perm_lines, $excl_manual){

	global $_CONFIG;

	$f = pathinfo($filename);
	$backupFile = $f['basename'];

	if (file_exists($filename)) {
                  echo "<h2>Initializing backup...</h2>";
                  echo "<h3 >Backup <b>$filename</b> created, we may continue!</h3><br />";

                  $urlReturn = "index2.php?option=com_cloner&lines=" . $perm_lines . "&task=refresh&backup=$backupFile&excl_manual=$excl_manual";

                  if(!$_CONFIG['refresh_mode']){

                  echo "<a href=\"".$urlReturn."\" id='cLink'>Please click here to continue!</a>";
                  echo " <strong id='countdown'>5</strong>";
                  echo "<script type='text/javascript'>cLink_load();</script>";

                  }else{

				  echo "<script>var dbbackup = ".intval($_REQUEST['dbbackup']).";</script>";

					  ?>
				<!--Start ProgressBar-->
				<script type="text/javascript">

				$(document).ready(function() {

					var globalUrl;
					var step = "r1";
					var count = 0;
					var counter = 0;
					var counter_old = 0;
					var completeSize = 0;
					var oldBackupName = "";
					var parts = 0;
					var oldSize = 0;

					$("#progressbar").progressbar({ value: 0 });

					$.ajaxSetup({
					"error":function(request, status, error) {
					//reset state here;
						$("#error").show();
						$("#errorText").append(status+" -- "+error);
						$("#errorText").append("<br /><br />JSON url: "+globalUrl);
					}});

					function getSize(bytes, conv){

						return (parseInt(bytes)/parseInt(conv)).toFixed(2);

						}
					function appendIcon(icon){

						return '<span class="ui-icon ui-icon-'+icon+'" style="float:left;"></span>';

						}

					function xclonerRecurseMYSQL(url){
					// create database backup
						globalUrl = url;
						step = "r1";

						$.getJSON(url, function(json) {

						if(!json){
							$("#error").show();
							$("#errorText").text(url);
						}

						if(json.dumpsize && !json.endDump){
									$("#mysqlProcess").append(" ("+getSize(json.dumpsize, 1024*1024)+" MB) <br />");
								}

						if(json.newDump){
								count++;
								//$("#mysqlProcess").append(appendIcon("arrowthick-1-e"));
								if(json.databaseName!="")
									$("#mysqlProcess").append("<b>["+json.databaseName+"]</b> <span id='db"+count+"'></span> tables ");
								counter = parseInt(json.startAtLine);

						}else{
								$("#db"+count).text(json.startAtLine - counter);
							}

						if(!parseInt(json.finished)){
						//get next records

							$("#db"+count).text(json.startAtLine - counter);

							recurseUrl = "index2.php?task=recurse_database&nohtml=1&dbbackup_comp="+json.dbbackup_comp+"&dbbackup_drop="+json.dbbackup_drop+"&startAtLine="+json.startAtLine+"&startAtRecord="+json.startAtRecord+"&dumpfile="+json.dumpfile;
							xclonerRecurseMYSQL(recurseUrl);

							}
						else{

							$("#fileSystem").show();
							var recurseUrl="index2.php?task=recurse_files&mode=start&nohtml=1";
							xclonerRecurseJSON(recurseUrl);

							}


						});
					}

					function xclonerRecurseJSON(url){
					//scan file system
						$("#result").hide();

						globalUrl = url;
						step = "r2";

						$.getJSON(url, function(json) {

						if(!json){
							$("#error").show();
							$("#errorText").text(url);
						}

						if(!parseInt(json.finished)){

							$("#recurseStatus").text(json.tfiles);

							var recurseUrl = "index2.php?task=recurse_files&mode="+json.mode+"&nohtml=1";
							xclonerRecurseJSON(recurseUrl);

							}
						else{
							var size = parseFloat(json.size)/(1024*1024);
							$("#recurseStatus").text(" done! (Estimated size:"+size.toFixed(2)+"MB) in "+json.tfiles+" files");
							$("#result").show();

							if(json.overlimit.length > 0){
								$("#overlimit").show();
								for(var i=0; i < json.overlimit.length; i++){

									$("#overlimit").append("<span class='oversizedFile'></span>"+json.overlimit[i]+"<br />");

									}
							}

							//xclonerGetJSON("<?php echo $urlReturn;?>");
							returnUrl = "index2.php?option=com_cloner&lines="+json.tfiles+"&task=refresh&backup=<?php echo $backupFile; ?>&excl_manual=";
							xclonerGetJSON(returnUrl);

							}


						});
					}

					function xclonerGetJSON(url){
					//create backup archive
					globalUrl = url;
					step = "r3";

					$.getJSON(url, function(json) {

						if(!json){
							$("#error").show();
							$("#errorText").append(url);
						}

						var percent = parseInt(json.percent);
						$("#progressbar").progressbar({ value: percent });
						$("#backupSize").text(getSize(json.backupSize, 1024*1024));
						$("#nFiles").text(json.startf);
						$("#percent").text(json.percent);
						$("#backupName").text(json.backup);
						if(!json.finished){

							if(oldBackupName != json.backup){
								oldBackupName = json.backup;
								completeSize  = completeSize + oldSize;
								parts++;
							}else{
								oldSize = parseInt(json.backupSize);
								}

							var url = "index2.php?option="+json.option+"&task="+json.task+"&json="+json.json+"&startf="+json.startf+"&lines="+json.lines+"&backup="+json.backup+"&excl_manual="+json.excl_manual;
							xclonerGetJSON(url);
						}else{

							//all done
							url = "index2.php?task=cleanup&nohtml=1";
							$.getJSON(url, function(json) {
							});

							$("#complete").show();
							$("#nFiles").text(json.lines);
							if(parts > 0){
								$("#backupParts").show();
								$("#backupPartsNr").text(parts);
							}
							$("#backupFiles").text(json.lines);
							$("#backupSizeComplete").append(getSize(completeSize+parseInt(json.backupSize), 1024*1024));
							$("#backupNameC").text(json.backup);
							$( "#dialog:ui-dialog" ).dialog( "destroy" );
							$( "#dialog-message" ).dialog({
								modal: true,
								width: 600,
								buttons: {
									Close: function() {
										$( this ).dialog( "close" );
									}
								}
							});

						}

					});

					}

					//Main program here

					$("#retry").click(function(){
						$("#error").hide();
						$("#errorText").empty();
						if(step == "r1"){
							xclonerRecurseMYSQL(globalUrl);
						}
						else
						if(step == "r2"){
							xclonerRecurseJSON(globalUrl);
						}
						else if(step == "r3"){
							xclonerGetJSON(globalUrl);
						}
					});

					$("#result").hide();
					$("#fileSystem").hide();

					if(dbbackup){
						recurseUrl = "index2.php?task=recurse_database&nohtml=1&dbbackup_comp=<?php echo $_REQUEST['dbbackup_comp']?>&dbbackup_drop=<?php echo $_REQUEST['dbbackup_drop']?>";
						xclonerRecurseMYSQL(recurseUrl);
					}else{
						$("#fileSystem").show();
					    var recurseUrl="index2.php?task=recurse_files&mode=start&nohtml=1";
					    xclonerRecurseJSON(recurseUrl);

					}

				});
				</script>

				<?php

				if($_REQUEST['dbbackup']){
				//lets start the incremental procedure
				?>

				<div id="mysqlBackup">
					<h2>Database backup...</h2><br />
					<div id="mysqlProcess"></div><div id="counter"></div>
				</div>

				<?php
				}
				?>

				<div id="fileSystem">
					<h2>Filesystem backup...</h2>

					<div id="recurseFiles">
							<br /><strong>Scanning files system...</strong> <span id="recurseStatus"></span>
							<br /><div id="overlimit" style="display:none"><b>Excluded oversized files:</b><br /> </div>
					</div>

					<div id="result">
					<br /> <strong>Processing Files:</strong> <span id="percent">0</span>% (<span id="nFiles"></span> files)
					<br /><br /> <strong>Backup Name: </strong><span id="backupName"></span>
					<br /><br /> <strong>Backup Size: </strong><span id="backupSize"></span>MB
					<br /><br /> <div id="progressbar"></div>
					</div>

					<div id="complete">
						<br /><h2>Backup completed!</h2>

						<form action="index2.php" name="adminForm" method="post">
						<input type=hidden name=files[1] value='<?php echo $backupFile?>'>
						<input type=hidden name=cid[1] value='<?php echo $backupFile?>'>
						<input type="hidden" name="option" value="<?php echo $option; ?>"/>
						<input type="hidden" name="task" value=""/>
						</form>

						<div id="dialog-message" title="Backup completed">
							<p>
								<span class="ui-icon ui-icon-arrowthick-1-e" style="float:left;"></span>
								<strong>Backup name:</strong> <span id="backupNameC"></span>
							</p>
							<p>
								<span class="ui-icon ui-icon-arrowthick-1-e" style="float:left;"></span><strong>Backup size:</strong> <span id="backupSizeComplete"></span>MB
							</p>
							<p>
								<span class="ui-icon ui-icon-arrowthick-1-e" style="float:left;"></span><strong>Number of files:</strong> <span id="backupFiles"></span>
							</p>
							<p class="backupParts">
								<span class="ui-icon ui-icon-arrowthick-1-e" style="float:left;"></span><strong>Backup Parts:</strong> <span id="backupPartsNr"></span>
							</p>
						</div>

					</div>
				</div>

				<div id="error" style="display:none;">
					<div class="alert alert-success"><b><?php echo LM_REFRESH_ERROR;?></b>
					<p><b>Details:</b> <span id="errorText"></span>
					</p>
					<a class="btn btn-default" href="#" id="retry">Click to Retry</a>

				</div>

				<!-- End ProgressBar -->

					  <?php

					  }
                  return;
              } else {
                  E_print("Backup failed, please check your tar server utility support!");
                  return;
              }

	}

function path_check($path){

	if(!is_dir($path)){
		$stat['code'] = 1;
		$stat['message'] = "Invalid directory";
		return $stat;
		}

	if(!is_readable($path)){
		$stat['code'] = 2;
		$stat['message'] = "Directory is not readable";
		return $stat;
		}

	if(!is_writeable($path)){
		$stat['code'] = 3;
		$stat['message'] = "Directory not writeable";
		return $stat;
		}

		return 0;

	}

function  _FDefault(){
		global $_CONFIG, $html;
?>

<form action="index2.php" method="post" name="adminForm">

 
 
<div class="jumbotron">
   
    <h3><a href="index2.php?option=com_cloner&amp;task=view"><i class="fa fa-heart-o fa-1x pull-left"></i> 
		<?php echo LM_MAIN_View_Backups?></a></h3>
			
	
   
    <h3>
<i class="fa fa-cogs fa-1x pull-left"></i>
				<a href="index2.php?option=com_cloner&amp;task=confirm">
				 
                   <?php echo LM_MAIN_Generate_Backup?> 
                                </a></h3>
		
    
   
    




</div>
 

<?php
$error	= 0;
?>

<div class="statusCheck">

	 
	
	<?php
		$html = new HTML_cloner();
		$stat = $html->path_check($_CONFIG[backup_start_path]);

		if( $stat['code'] > 0 and $stat['code'] < 3){
				echo "<div class=\"alert alert-danger\"><b>Backup Start Path Check: </b> ".$stat['message']; $error = 1;
			}
			else{
				echo "<div class=\"alert alert-success\"> <b>Backup Start Path Check: </b> OK";
				if(!is_dir($_CONFIG[backup_start_path]."/administrator/backups")){	
					@mkdir($_CONFIG[backup_start_path]."/administrator");
					if(@mkdir($_CONFIG[backup_start_path]."/administrator/backups"))
						echo "<script>window.location='index2.php'</script";
				}	
			}
		echo " ($_CONFIG[backup_start_path])";
	?>
	</div> 

 
	 
	<?php

		$stat = $html->path_check($_CONFIG[backup_store_path]);

		if( $stat['code'] > 0){
				echo "<div class=\"alert alert-danger\"><b>Backup Store Path Check:</b> ".$stat['message']; $error = 1;
			}
			else{
				echo "<div class=\"alert alert-success\"><b>Backup Store Path Check:</b> OK";
				}
		echo " ($_CONFIG[backup_store_path])";
	?>
	</div>
	 

	 
	
	<?php

		$stat = $html->path_check($_CONFIG[temp_dir]);

		if( $stat['code'] > 0){
				echo "<div class=\"alert alert-danger\"><b>Temporary Path Check: </b> ".$stat['message']; $error = 1;
			}
			else{
				echo "<div class=\"alert alert-success\"><b>Temporary Path Check: </b> OK";
				}
		echo " ($_CONFIG[temp_dir])";
	?>
	 </div>  

 
	
	<?php

		if($_CONFIG['jcpass'] == md5('admin')){
				echo "<div class=\"alert alert-danger\"><b>Authentication: </b> Change default password 'admin'"; $error = 1;
			}
			else{
				echo "<div class=\"alert alert-success\"><b>Authentication: </b> OK";
				}

	?>
	 </div> 

	 
	
	<?php

		if($error ){
				echo "<div class=\"alert alert-danger\"><b>Backup Ready: </b> NO"; $error = 1;
			}
			else{
				echo "<div class=\"alert alert-success\"><b>Backup Ready: </b> YES";
				}

	?>
	</div> 

 
 
<input type="hidden" name="option" value="com_cloner" />
<input type="hidden" name="task" value="lang" />
</form>

<?php
}

/*The basic Authentication form*/
function Login(){

	?>
	 

	<script>
	$(function() {
		$( "#login" ).button({
            icons: {
                primary: "ui-icon-locked"
            }
        })
        $("#login").click(function() {
				$("#adminForm")[0].submit();
				return false;
			})
        
        $( "#reset" ).button({
				icons: {
                primary: "ui-icon-trash"
				}
			})
		$( "#reset" ).click(function() {
				$("#username").val('');$("#password").val('');
				return false;
			});

	});
	</script>


	<div class="loginform">
	<form action="index2.php" method="post" name="adminForm" id="adminForm">
	<table class="loginForm">
		<tr><td align='center'>
			<table align='center' cellpadding='10' cellspacing='20'>
				<tr ><td colspan='2' align='center'><b>Authentication Area:</b></td></tr>
				<tr><td>Username:</td><td><input type='text' size='30' name='username' id='username'></td></tr>
				<tr><td>Password:</td><td><input type='password' size='30' name='password' id='password'></td></tr>
				<tr><td>&nbsp;</td><td>
				<div class="loginform">
				<button id="login">Login</button>
				<button id="reset">Reset</button>


				</div>
				</td></tr>
				<tr><td colspan='2'><?php echo LM_LOGIN_TEXT;?></td></tr>
			</table>
		</td></tr>
	</table>

	<input type="hidden" name="option" value="com_cloner" />
    <input type="hidden" name="task" value="dologin" />
   	<input type="hidden" name="boxchecked" value="0" />
   	<input type="hidden" name="hidemainmenu" value="0" />

	</form>
	</center>
<?php

}

function Cron(){
    global $_CONFIG;
?>

<script>
	$(function() {
		$( "#tabs" ).tabs();
	});
	</script>

<div id="tabs">
	<ul>
		<li><a href="#tabs-1"><?php echo LM_CRON_TOP?></a></li>
	</ul>

	<div id="tabs-1"><p>

		<div class="mainText">
		<?php echo LM_CRON_SUB?>
		<br /><br />

		<ul>
			<li><input type="text" value="/usr/bin/php  <?php echo dirname(__FILE__);?>/cloner.cron.php" size="150" /></li>
			<li><strong>curl http://website/path_to_xcloner_folder/cloner.cron.php</strong></li>
			<li><strong>wget -q http://website/path_to_xcloner_folder/cloner.cron.php</strong></li>
			<li><strong>lynx -sourcehttp://website/path_to_xcloner_folder/cloner.cron.php</strong></li>
		</ul>
		<br /><br />

		For <b>Running Multiple Crons</b>, you need to first create a custom configuration file in the XCloner Configuration -> Cron tab
		and then replace "cloner.cron.php" with "cloner.cron.php?config=myconfig.php", only use 'links' or 'lynx' options to run the cronjob
		<br /><br />

		If you would like to use the <b>php SSH command</b> for running Multiple Crons, you will need to replace
		the  "cloner.cron.php" with <b>"cloner.cron.php myconfig.php"</b> in the command line.
		<br /><br />

		<?php echo LM_CRON_HELP?>
		</div>

	</p></div>
</div>

<?php
}


function Translator_Edit_DEFAULT($option, $content, $file, $lang){
	global $_CONFIG;
?>
	<form action="index2.php" method="post" name="adminForm">
    <table class="table table-hover table-condensed table-responsive">
    <tr>
      <th align="left"><?php echo LM_LANG_EDIT_FILE?> <?php echo $file?></th>
    </tr>

    <tr>

	  <td><textarea class="form-control" name='def_content' cols='100' rows='30'><?php echo $content;?></textarea></td>

	</tr>

	<input type="hidden" name="option" value="com_cloner" />
    <input type="hidden" name="language" value="<?php echo $lang?>" />
    <input type="hidden" name="task" value="lang" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="hidemainmenu" value="0" />
    </form>

<?php

}

function Translator_Add($option){
	global $_CONFIG;
?>
	<form action="index2.php" method="post" name="adminForm">
    <table class="table table-hover table-condensed table-responsive">
    <tr>
      <th align="left"><?php echo LM_LANG_NEW?></th>
    </tr>

    <tr>

	  <td><input size='40' type=text name='lname' value=''></td>

	</tr>

	<input type="hidden" name="option" value="com_cloner" />
    <input type="hidden" name="language" value="<?php echo $lang?>" />
    <input type="hidden" name="task" value="add_lang_new" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="hidemainmenu" value="0" />
    </form>
<?php
}

function Translator_Edit($option, $data, $def_data, $file, $lang){
    global $_CONFIG;
?>

	<form action="index2.php" method="post" name="adminForm">
    <table class="table table-hover table-condensed table-responsive">
    <tr>
      <th align="left"><?php echo LM_LANG_EDIT_FILE?> <input type=text name='lfile' size=100 value='<?php echo $file?>'><br />
	  <font color='red'><?php echo LM_LANG_EDIT_FILE_SUB?></font>

	  <script language="javascript" type="text/javascript">
			function submitbutton(pressbutton) {
				var form = document.adminForm;

                if (pressbutton == 'save_lang_apply') {
					if(confirm('Before you continue please make sure you are still logged in, else press Cancel and then try again!')){
					submitform( pressbutton );
					}
     				return;
				}
                else
				if (pressbutton == 'save_lang') {
					if(confirm('Before you continue please make sure you are still logged in, else press Cancel and then try again!')){
					submitform( pressbutton );
					}
					return;
				}
                else{
                    submitform( pressbutton );
                    }
           }
      </script>

	  </th>
    </tr>
    </table>
    <?php
	foreach($data as $key=>$value)
	if($def_data[$key]!="")	{
	if($i++ %2 == 0)
	 $bgcolor = '#eeeeee';
	else
	 $bgcolor = '#dddddd';
	?>
	<table class="table table-hover table-condensed table-responsive">
    <tr>
      <th width='50%' align="left">Default Variable <?php echo $key?></th>
      <th width='50%' align="left">Translation <?php echo $key?></th>
    </tr>
	<tr bgcolor="<?php echo $bgcolor?>">
	  <td><textarea class="form-control" cols=65 rows=3 class="form-control"><?php echo stripslashes($def_data[$key])?></textarea></td>

	  <td bgcolor='<?php if( trim(str_replace(array("\n","\r"," "),array("","",""),$def_data[$key])) !=
	                      trim(str_replace(array("\n","\r"," "),array("","",""),$value)))
						  echo 'green';
						 else
						  echo 'red';?>'>
	  <textarea cols=65 rows=3 name=lang[<?php echo $key?>]><?php echo stripslashes($value)?></textarea></td>
	</tr>

	<?php
	}
	?>

	<input type="hidden" name="option" value="com_cloner" />
    <input type="hidden" name="language" value="<?php echo $lang?>" />
    <input type="hidden" name="task" value="lang" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="hidemainmenu" value="0" />
    </form>

<?php
}

function Translator($option, $lang_arr){
    global $_CONFIG;

?>
	<script>
	$(function() {
		$( "#toggle" ).button();
		$( "#toggle" ).click(function() { checkJAll(<?php echo count( $lang_arr ); ?>, "toggle", "cb"); });
		$( "#checklist" ).buttonset();
	});
	</script>

	<form action="index2.php" method="post" name="adminForm">
	<div id="checklist">
    <table class="table table-hover table-condensed table-responsive">
    <tr>
      <th align="center">
      <input id="toggle" type="checkbox" name="toggle" value=""  />
      </th>
      <th align="left">
      <?php echo LM_LANG_NAME ?>
      </th>
    </tr>

    <?php
     for($i=0; $i<sizeof($lang_arr); $i++){

		?>

     		<tr>
		      <!--<td width="5" align="left"><?php echo ($i+1);?></td>-->
			  <td align="center" width="100">
			  <label for="cb<?php echo $i ?>"><?php echo $i ?></label>
			  <input type="checkbox" id="cb<?php echo $i ?>" name="cid[<?php echo $i?>]" value="<?php echo $i ?>" />
              <input type="hidden"  name="files[<?php echo $i?>]" value="<?php echo $lang_arr[$i] ?>"  />
			  </td>
		      <td align="left" >
			  <a href="index2.php?option=<?php echo $option;?>&task=edit_lang&langx=<?php echo $lang_arr[$i];?>"><?php echo ucfirst($lang_arr[$i])?>
			  </td>
			</tr>
	   <?php

	}
	?>
	</table></div>
    <input type="hidden" name="option" value="com_cloner" />
    <input type="hidden" name="task" value="lang" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="hidemainmenu" value="0" />
    </form>
<?php
}

function showBackups( &$files, &$sizes, $path, $option ) {
    // ----------------------------------------------------------
    // Presentation of the backup set list screen
    // ----------------------------------------------------------
    global $baDownloadPath, $_CONFIG;

    ?>

    <script type="text/javascript">
		
	$(function() {
		$( "#toggle" ).button();
		$( "#toggle" ).click(function() { checkJAll(<?php echo (count( $files )); ?>, "toggle", "cb"); });
		$( "#checklist" ).buttonset();
		
		$( "#Clone, #Rename, #Delete, #Move" ).unbind("click");
		$( "#Clone, #Rename, #Delete, #Move" ).click(function(){
				if(!$("input:checked").length){
					
					$( "#error-message" ).dialog({
						width: 500,
						height: 200,
						modal: true,
						buttons: {
							Ok: function() {
								$( this ).dialog( "close" );
							}
						}
					});					
				
					return false;
				}else{
					var action = $(this).attr('id').toLowerCase();
					document.adminForm.task.value=action;
					document.adminForm.submit();
				}
		})

	
	})
	</script>
	
	<div id="error-message" title="Error" style="display:none;">
		<p>
			<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
			Please select at least one backup archive.
		</p>

	</div>
	<div id="checklist">
    <form action="index2.php" method="post" name="adminForm">
    <table class="table table-hover table-condensed table-responsive">
    <tr>
      <th width="100">
      <input type="checkbox" id="toggle" name="toggle" value="" /><label for="toggle">Check All</label>
      </th>
      <th align="left" width="100px;">
      <?php echo LM_COL_DOWNLOAD ?>
      </th>
      <th width="50%" class="title">
      <?php echo LM_COL_FILENAME ?>
      </th>
      <th align="left" width="10%">
      <?php echo LM_COL_SIZE ?>
      </th>
      <th align="left" width="">
      <?php echo LM_COL_DATE ?>
      </th>
      </tr>
    <?php
    $k = 0;
    for ($i=0; $i <= (count( $files )-1); $i++) {
      $date = date ("D jS M Y H:i:s (\G\M\T O)", filemtime($path.'/'.$files[$i]));
      $url = "index2.php?option=com_cloner&task=download&file=".'/'.urlencode($files[$i]);
      ?>
      <tr class="<?php echo "row$k"; ?>">

        <td align="center">
          <label for="cb<?php echo $i ?>"><?php echo $i ?></label>
          <input type="checkbox" id="cb<?php echo $i ?>" name="cid[<?php echo $i?>]" value="<?php echo $i ?>" />
          <input type="hidden"  name="files[<?php echo $i?>]" value="<?php echo $files[$i] ?>"  />
        </td>
        <td align="left">
			<a target='_blank' href="<?php echo $url ?>"><i class="fa fa-cloud-download fa-2x"></i></a>
		</td>
        <td>
			<span class="backup_name"><?php echo $files[$i]; ?></span>
			<input type="hidden" id="f<?php echo $i ?>" name="f<?php echo $i ?>" value="<?php echo $files[$i]; ?>" >
        </td>

        <td align="left">
			<?php echo $sizes[$i]; ?>
        </td >
        <td align="left">
			<?php echo $date; ?>
        </td>
      </tr>
      <?php
      $k = 1 - $k;
    }
    ?>
    </table>
	</div>
    <input type="hidden" name="option" value="com_cloner" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="hidemainmenu" value="0" />
    </form>
    <br/>&nbsp;
    <?php
  }

  function Config($option){
            global $config_file,$_CONFIG, $lang_array, $database, $mosConfig_db;
  ?>
    <form name='adminForm' action='index2.php' method='POST'>

	<script>
	$(function() {
		$( "#tabs" ).tabs();
	});

	$(function() {
		$( "#radiog1" ).buttonset();
		$( "#radiog2" ).buttonset();
		$( "#radiog3" ).buttonset();
		$( "#radiog4" ).buttonset();
		$( "#radio" ).buttonset();
		$( "#radiom" ).buttonset();
		$( "#radiob" ).buttonset();
		$( "#radioftp" ).buttonset();
		$( "#radioftps" ).buttonset();
		$( "#radiodebug" ).buttonset();
		$( "#radiorefresh" ).buttonset();
		$( "#checktar" ).button();
		$( "#cron_file_delete_act" ).button();
		$( "#cron_sql_drop" ).button();
		$( "#cron_amazon_active" ).button();
		$( "#cron_amazon_ssl" ).button();
		$( "#cron_ftp_delb" ).button();
		$( "#checkmysqldump" ).button();
	});
	</script>

	<?php $tabs = new mosTabs(1);?>
		<ul>

			<li><a href="#tabs-1"><?php echo LM_TAB_GENERAL;?></a></li>
			<li><a href="#tabs-2"><?php echo LM_TAB_MYSQL;?></a></li>
			<li><a href="#tabs-3"><?php echo LM_TAB_AUTH;?></a></li>
			<li><a href="#tabs-4"><?php echo LM_TAB_SYSTEM;?></a></li>
			<li><a href="#tabs-5"><?php echo LM_TAB_CRON;?></a></li>
			<li><a href="#tabs-6"><?php echo LM_TAB_INFO;?></a></li>
		</ul>

    <table class="table">
    <tr><th colspan='2'>
    <?php echo LM_CONFIG_EDIT?> <?php echo $config_file?>
    </th></tr>
    </table>
    <?php
    $tabs->startTab(LM_TAB_GENERAL,"1");
    ?>

	<div id="configtabinside">

	<div>
		<h3><a href="#"> <?php echo LM_CONFIG_BSETTINGS?></a></h3>
		<div><p>
			<table class='adminform'>

		    <tr>
		     <td>
		      <?php echo LM_CONFIG_UBPATH?>
		     </td>
		     <td>
		      <input class="form-control" type=text size=100 name='backup_path' value='<?php echo $_CONFIG[backup_path]?>'>
		      <br /><?php echo LM_CONFIG_UBPATH_SUB?>
		     </td>
		    </tr>

		    <tr>
		     <td  width='250'>
		      <?php echo LM_CONFIG_BPATH?>
		     </td>
		     <td>
		      <input class="form-control" type=text size=100 name='clonerPath' value='<?php echo $_CONFIG[clonerPath]?>'>
		      <br /><?php echo LM_CONFIG_BPATH_SUB?>
		     </td>
		    </tr>

		    </table>
		</p></div>
	</div>

	<div>
		<h3><a href="#"> <?php echo LM_CONFIG_BSETTINGS_OPTIONS?></a></h3>
		<div><p>
			<table class='adminform'>

		    <tr>
		     <td width='250'>
		      <?php echo LM_CONFIG_MANUAL_BACKUP;?>
		     </td>
		     <td>
		      <div id="radiog1">
		      <label for="radiog11"><?php echo LM_YES?></label> <input class="form-control" id="radiog11" type=radio size=50 value=1 name='backup_refresh' <?php if($_CONFIG[backup_refresh]==1) echo 'checked';?>>
		      <label for="radiog12"><?php echo LM_NO?></label> <input class="form-control" id="radiog12" type=radio size=50 value=0 name='backup_refresh' <?php if($_CONFIG[backup_refresh]==0) echo 'checked';?>>
		      <br><small><?php echo LM_CONFIG_MANUAL_BACKUP_SUB?></small>
		      </div>
		     </td>
		    </tr>

			

		    <tr>
		     <td>
		      <?php echo LM_CRON_DB_BACKUP?>
		     </td>
		     <td>
		      <div id="radiog3">
			      <label for="radiog31">Yes</label> <input class="form-control" id="radiog31" type=radio size=50 value=1 name='enable_db_backup' <?php if($_CONFIG[enable_db_backup]==1) echo 'checked';?>>
			      <label for="radiog32">No</label> <input class="form-control" id="radiog32" type=radio size=50 value=0 name='enable_db_backup' <?php if($_CONFIG[enable_db_backup]==0) echo 'checked';?>>
			      <br /><?php echo LM_CRON_DB_BACKUP_SUB?>
		      </div>

		     </td>
		    </tr>

			<tr>
		     <td>
		      <?php echo LM_CONFIG_SYSTEM_MBACKUP?>
		     </td>
		     <td>
		      <div id="radiog4">
			      <label for="radiog41">Yes</label> <input class="form-control" id="radiog41" type=radio size=50 value=1 name='add_backups_dir' <?php if($_CONFIG[add_backups_dir]==1) echo 'checked';?>>
			      <label for="radiog42">No</label> <input class="form-control" id="radiog42" type=radio size=50 value=0 name='add_backups_dir' <?php if($_CONFIG[add_backups_dir]==0) echo 'checked';?>>
			      <br /><?php echo LM_CONFIG_SYSTEM_MBACKUP_SUB?>
		     </td>
		      </div>
		    </tr>

		    </table>
		</p></div>
	</div>

	<div>
		<h3><a href="#"> <?php echo LM_CONFIG_BSETTINGS_SERVER?></a></h3>
		<div><p>
			<table class='adminform'>

			<tr><td width='250'>
		      <?php echo LM_CONFIG_MEM?>
		     </td>
		     <td align='left'>
		     <table style="width:auto; margin-bottom: 10px;" cellpadding='0' cellspacing='2' border='1'>
		     <tr bgcolor='#efefef'><td style="width:70px;">
		     <label for="checktar"><?php echo LM_ACTIVE;?></label> <input class="form-control" type=checkbox id="checktar" value=1 name='mem' <?php if($_CONFIG[mem]==1) echo 'checked';?>>
		     </td><td align='left'>

		     <table  width='100%' cellpadding='0' cellspacing='0'>
		     <tr><td>
		     <?php echo LM_TAR_PATH;?>  <br /><input class="form-control" size='50' type=text name=tarpath value='<?php echo $_CONFIG[tarpath]?>'><br />
		     <?php echo LM_TAR_PATH_SUB;?>
		     </td></tr>

		     </table>


		     </td></tr>

		     <tr bgcolor='#dedede'><td>
		     <label for="checkmysqldump"><?php echo LM_ACTIVE?></label> <input class="form-control" id="checkmysqldump" type=checkbox value=1 name='sql_mem' <?php if($_CONFIG[sql_mem]==1) echo 'checked';?>>
		     </td><td align='left'>
		     <?php echo LM_MYSQLDUMP_PATH;?> <br /><input class="form-control" type=text size='50' name='sqldump' value='<?php echo $_CONFIG[sqldump]?>'>

			 </td></tr>
		     </table>

		     <?php echo LM_CONFIG_MEM_SUB?>

		     </td>
		    </tr>
		    </table>
		</p></div>
	</div>

	<div>
		<h3><a href="#">Public Key Management</a></h3>
		<div><p>
			<table class='adminForm'>

		    <tr><td width="250">
		      Public Key
		     </td>
		     <td>
		      <input class="form-control" type-text size=50  name='license_code'  value="<?php echo $_CONFIG[license_code]?>"/>
		      <br />Use this code in the MultiSite XCloner Manager <a  target='_blank' href='http://www.xcloner.com/'>XCloner.com Members area</a>
		      <br />Leave it empty to disable it

		     </td>
		    </tr>

		    </table>
		</p></div>
	</div>

	</div>

    <?php
    $tabs->endTab();
    $tabs->startTab(LM_TAB_MYSQL,"2");
    ?>
	<div id="configtabinside">
	<div>
	     <h3><a href="#"><?php echo LM_CONFIG_MYSQL?></a></h3>
		<div><p>

		    <table class='adminform'>

		    <tr>
		     <td width='250'>
		      <?php echo LM_CONFIG_MYSQLH?>
		     </td>
		     <td>
		      <input class="form-control" type=text size=50 name='mysql_host' value='<?php echo $_CONFIG[mysql_host]?>'>
		      <br /><?php echo LM_CONFIG_MYSQLH_SUB?>
		     </td>
		    </tr>

		    <tr>
		     <td>
		      <?php echo LM_CONFIG_MYSQLU?>
		     </td>
		     <td>
		      <input class="form-control" type=text size=50 name='mysql_user' value='<?php echo $_CONFIG[mysql_user]?>'>
		      <br /><?php echo LM_CONFIG_MYSQLU_SUB?>
		     </td>
		    </tr>

		    <tr>
		     <td>
		      <?php echo LM_CONFIG_MYSQLP?>
		     </td>
		     <td>
		      <input class="form-control" type=text size=50 name='mysql_pass' value='<?php echo $_CONFIG[mysql_pass]?>'>
		      <br /><?php echo LM_CONFIG_MYSQLP_SUB?>
		     </td>
		    </tr>

		    <tr>
		     <td>
		      <?php echo LM_CONFIG_MYSQLD?>
		     </td>
		     <td>
		      <input class="form-control" type=text size=50 name='mysql_database' value='<?php echo $_CONFIG[mysql_database]?>'>
		      <br /><?php echo LM_CONFIG_MYSQLD_SUB?>
		     </td>
		    </tr>

		    <tr>
		     <td  width='200'>
		      <?php echo LM_CONFIG_SYSTEM_MDATABASES?>
		     </td>
		     <td>
		      <div id="radiom">
		      <label for="radiom1"><?php echo LM_YES?></label> <input class="form-control" id="radiom1" type=radio name='system_mdatabases' value='0' <?php if(abs($_CONFIG[system_mdatabases])==0) echo "checked";?>>
		      <label for="radiom2"><?php echo LM_NO?></label> <input class="form-control" id="radiom2" type=radio name='system_mdatabases' value='1' <?php if(abs($_CONFIG[system_mdatabases])==1) echo "checked";?>>
		      <br /> <?php echo LM_CONFIG_SYSTEM_MDATABASES_SUB?>
		      </div>
		     </td>
		    </tr>

		    </table>
		     </p></div>
	 </div>
	 </div>
    <?php
	$tabs->endTab();
	$tabs->startTab(LM_TAB_AUTH,"3");
    ?>
    <div id="configtabinside">
	<div>
	     <h3><a href="#"><?php echo LM_CONFIG_AUTH?></a></h3>
		<div><p>
		<table class='adminform'>
		    <tr>
		     <td width='250'>
		      <?php echo LM_CONFIG_AUTH_USER?>
		     </td>
		     <td>
		      <input class="form-control" type=text size=30 name='jcuser' value='<?php echo $_CONFIG[jcuser]?>'>
		      <br /><?php echo LM_CONFIG_AUTH_USER_SUB?>
		     </td>
		    </tr>

		    <tr>
		     <td>
		      <?php echo LM_CONFIG_AUTH_PASS?>
		     </td>
		     <td>
		      <input class="form-control" type=text size=30 name='jcpass' value=''> <?php if($_CONFIG['jcpass'] == md5('admin')) echo "<font color=red>please change the default password  'admin'</font>"?>
		      <br /><?php echo LM_CONFIG_AUTH_PASS_SUB?>
		     </td>
		    </tr>
	    </table>
	    </p></div>
	 </div>
	 </div>
    <?php
	$tabs->endTab();
	$tabs->startTab(LM_TAB_SYSTEM,"4");
    ?>

    <div id="configtabinside">
	<div>
		<h3><a href="#"><?php echo LM_CONFIG_DISPLAY?></a></h3>
		<div><p>
		    <table class='adminform'>
			<tr>
		     <td  width='250'>
		      <?php echo LM_CONFIG_SYSTEM_LANG?>
		     </td><td>
		      <select name='select_lang'>
			  <option value=''><?php echo LM_CONFIG_SYSTEM_LANG_DEFAULT;?></option>
			  <?php
			  foreach($lang_array as $value)
			   if($_CONFIG['select_lang'] == $value)
		   	     echo "<option value='$value' selected>$value</option>\n";
			   else
			     echo "<option value='$value'>$value</option>\n";
			  ?>
			  </select>
			  <br>
		      <br /><?php echo LM_CONFIG_SYSTEM_LANG_SUB?>
		     </td></tr>
			</table>
		</p></div>
	</div>
	<div>
		<h3><a href="#"> <?php echo LM_CONFIG_SYSTEM?></a></h3>
		<div><p>

			<table class='adminform'>

		    <tr>
		     <td  width='250'>
		      <?php echo LM_CONFIG_SYSTEM_FTP?>
		     </td>
		     <td>
		     <div id="radioftp">
		      <label for="radioftp1">Direct</label><input class="form-control" id="radioftp1" type=radio name='system_ftptransfer' value='0' <?php if(abs($_CONFIG[system_ftptransfer])==0) echo "checked";?>>
		      <label for="radioftp2">Passive</label><input class="form-control" id="radioftp2" type=radio name='system_ftptransfer' value='1' <?php if(abs($_CONFIG[system_ftptransfer])==1) echo "checked";?>> <br>
		      <br /><?php echo LM_CONFIG_SYSTEM_FTP_SUB?>
		      </div>

		     </td></tr>
		     <tr><td>
		      <?php echo LM_FTP_TRANSFER_MORE?>
		     </td><td>
		      <div id="radioftps">
		      <label for="radioftps1">Normal</label><input class="form-control" id="radioftps1" type=radio size=50 value=0 name='secure_ftp' <?php if($_CONFIG[secure_ftp]==0) echo 'checked';?>>
		      <label for="radioftps2">Secure(SFTP)</label><input class="form-control" id="radioftps2" type=radio size=50 value=1 name='secure_ftp' <?php if($_CONFIG[secure_ftp]==1) echo 'checked';?>>
		     </td>
		    </tr>

			</table>
		</p></div>
	</div>
	<div>
		<h3><a href="#"> <?php echo LM_CONFIG_MANUAL?></a></h3>
		<div><p>

			<script>
			$(function() {
				$( "#slider" ).slider({
					value:parseInt(<?php echo $_CONFIG[backup_refresh_number];?>),
					min: 10,
					max: 1000,
					step: 10,
					slide: function( event, ui ) {
						$( "#backup_refresh_number" ).val( ui.value );
					}
				});
				$( "#backup_refresh_number" ).val( $( "#slider" ).slider( "value" ) );
			});
			$(function() {
				$( "#sliderRPS" ).slider({
					value:parseInt(<?php echo $_CONFIG[recordsPerSession];?>),
					min: 100,
					max: 100000,
					step: 100,
					slide: function( event, ui ) {
						$( "#recordsPerSession" ).val( ui.value );
					}
				});
				$( "#recordsPerSession" ).val( $( "#sliderRPS" ).slider( "value" ) );
			});
			$(function() {
				$( "#sliderEFZ" ).slider({
					value:parseInt(<?php echo $_CONFIG[excludeFilesSize];?>),
					min: -1,
					max: 10240,
					step: 1,
					slide: function( event, ui ) {
						$( "#excludeFilesSize" ).val( ui.value );
					}
				});
				$( "#excludeFilesSize" ).val( $( "#sliderEFZ" ).slider( "value" ) );
			});

			$(function() {
				$( "#sliderSBS" ).slider({
					value:parseInt(<?php echo $_CONFIG[splitBackupSize];?>),
					min: -1,
					max: 10000,
					step: 1,
					slide: function( event, ui ) {
						$( "#splitBackupSize" ).val( ui.value );
					}
				});
				$( "#splitBackupSize" ).val( $( "#sliderSBS" ).slider( "value" ) );
			});
			</script>

			<table class='adminform'>

		     <tr><td width="250">
		      <?php echo LM_CONFIG_MANUAL_FILES;?>
		     </td><td>
			  <div class="sliderContainer">
				<div id="slider" style="width:500px;padding:5px 0 0 0;float:left"></div>
				<label for="backup_refresh_number"></label>
				<input class="form-control" id="backup_refresh_number" type=text size=10 name='backup_refresh_number' value=<?php echo $_CONFIG[backup_refresh_number];?>>
			  </div>
			  </td></tr>

			  <tr><td width="250">
		      <?php echo LM_CONFIG_DB_RECORDS;?>
		     </td><td>
			  <div class="sliderContainer">
 				 <div id="sliderRPS" style="width:500px;padding:5px 0 0 0;float:left;"></div>
				 <label for="recordsPerSession"></label>
				 <input class="form-control" id="recordsPerSession" type=text size=10 name='recordsPerSession' value=<?php echo $_CONFIG[recordsPerSession];?>>
				</div>
			  </td></tr>

			  <tr><td width="250">
		      <?php echo LM_CONFIG_EXCLUDE_FILES_SIZE;?>
		     </td><td>
			  <div class="sliderContainer">
			  	  <div id="sliderEFZ" style="width:500px;padding:5px 0 0 0;float:left"></div>
				  <label for="excludeFilesSize"></label>
			      <input class="form-control" id="excludeFilesSize" type=text size=10 name='excludeFilesSize' value=<?php echo $_CONFIG[excludeFilesSize];?>> MB
			  </div>
			  </td></tr>

			  <tr><td width="250">
		      <?php echo LM_CONFIG_SPLIT_BACKUP_SIZE;?>
		     </td><td>
		      <div class="sliderContainer">
				  <div id="sliderSBS" style="width:500px;padding:5px 0 0 0;float:left;"></div>
			      <label for="splitBackupSize"></label>
			      <input class="form-control" id="splitBackupSize" type=text size=10 name='splitBackupSize' value=<?php echo $_CONFIG[splitBackupSize];?>> MB
		      </div>

			  </td></tr>

		     <tr><td>
		      <?php echo LM_CONFIG_MANUAL_REFRESH;?>
		     </td><td>
		      <input class="form-control" type=text size=20 name='refresh_time' value=<?php echo $_CONFIG[refresh_time];?>> miliseconds

		     </td></tr>
		     
		     <tr>
		     <td>
		      <?php echo LM_CRON_COMPRESS?>
		     </td>
		     <td>
		      <div id="radiog2">
			      <label for="radiog21"><?php echo LM_YES?></label> <input class="form-control" id="radiog21" type=radio size=50 value=1 name='backup_compress' <?php if($_CONFIG[backup_compress]==1) echo 'checked';?>>
			      <label for="radiog22"><?php echo LM_NO?></label> <input class="form-control"  id="radiog22" type=radio size=50 value=0 name='backup_compress' <?php if($_CONFIG[backup_compress]==0) echo 'checked';?>>
		     <br /> <small>Note: this option might break your backup process if the Manual backup option is also enabled</small>
		     </div>
		     </td>
		    </tr>

		    <tr><td>
		      <?php echo LM_REFRESH_MODE?>
		     </td><td>
		     <div id="radiorefresh">
		      <label for="radiorefresh1">Normal</label> <input class="form-control" id="radiorefresh1" type=radio size=50 value=0 name='refresh_mode' <?php if($_CONFIG[refresh_mode]==0) echo 'checked';?>>
		      <label for="radiorefresh2">AJAX</label> <input class="form-control" id="radiorefresh2" type=radio size=50 value=1 name='refresh_mode' <?php if($_CONFIG[refresh_mode]==1) echo 'checked';?>>
		      </div>
		     </td></tr>

		    <tr><td>
		      <?php echo LM_DEBUG_MODE?>
		     </td><td>
		     <div id="radiodebug">
		      <label for="radiodebug1">No</label> <input class="form-control" id="radiodebug1" type=radio size=50 value=0 name='debug' <?php if($_CONFIG[debug]==0) echo 'checked';?>>
		      <label for="radiodebug2">Yes</label> <input class="form-control" id="radiodebug2" type=radio size=50 value=1 name='debug' <?php if($_CONFIG[debug]==1) echo 'checked';?>>
		     </td></tr>
			</table>

		</p></div>
	</div>

    </div>
    <?php
    $tabs->endTab();
    $tabs->startTab(LM_TAB_CRON,"5");
    ?>
	<div id="configtabinside">

	<div>
		<h3><a href="#"> <?php echo LM_CRON_SETTINGS_M?> - all configs are saved in directory configs/ </a></h3>
		<div><p>
			<table class='adminform'>

		    <tr>
		    <td width='250'>
		      <?php echo LM_CRON_MCRON?>
		     </td>
		     <td>
		      <input class="form-control" type=text size=30 value="<?php echo $_CONFIG[cron_save_as]?>" name='cron_save_as' >.php <br />
		       <?php echo LM_CRON_MCRON_SUB?>
		     </td>
		    </tr>

		    <tr>
		    <td>
		      <?php echo LM_CRON_MCRON_AVAIL?>
		     </td>
		     <td>
		      <?php

		      if ($handle = @opendir($_CONFIG['multiple_config_dir'])) {

		      while (false !== ($file = readdir($handle))) {
		         if( ($file!=".") && ($file!="..") &&($file!="") && (strstr($file, '.php'))){
		           $fcron = "cloner.cron.php?config=$file";

		           echo "<b>$fcron</b>";

		           echo " - <a href='$fcron' target='_blank'>execute cron</a>";

		           echo " | <a href='index2.php?option=com_cloner&task=cron_delete&fconfig=$file'>delete config</a>";

		           echo "\n<br />";
		         }
		      }

		      closedir($handle);
		      }
		      ?>
		     </td>
		    </tr>
		    </table>
		</p></div>
	</div>

	<div>
		<h3><a href="#"> <?php echo LM_CRON_SETTINGS?></a></h3>
		<div><p>
			<table class='adminform'>

		    <tr>
		    <td width='250'>
		      <?php echo LM_CRON_SEMAIL?>
		     </td>
		     <td>
		      <input class="form-control" type=text size=30 value="<?php echo $_CONFIG[cron_logemail]?>" name='cron_logemail' > <br />
		       <?php echo LM_CRON_SEMAIL_SUB?>
		     </td>
		    </tr>

		    <tr>
		     <td>
		     <?php echo LM_CRON_MODE?>
		     </td>
		     <td>

		     <div id="radio">
			      <input class="form-control" id="radio1" type=radio size=50 value=0 name='cron_send' <?php if($_CONFIG[cron_send]==0) echo 'checked';?>>
				  <label for="radio1"><?php echo LM_CONFIG_CRON_LOCAL?></label>
			      <input class="form-control" id="radio2" type=radio size=50 value=1 name='cron_send' <?php if($_CONFIG[cron_send]==1) echo 'checked';?>>
				  <label for="radio2"><?php echo LM_CONFIG_CRON_REMOTE?></label>
			      <input class="form-control" id="radio3" type=radio size=50 value=2 name='cron_send' <?php if($_CONFIG[cron_send]==2) echo 'checked';?>>
				  <label for="radio3"><?php echo LM_CONFIG_CRON_EMAIL?></label>
			  </div>
		     <?php echo LM_CRON_MODE_INFO?>
		     </td>
		    </tr>


		   <tr>
		    <td>
		      <?php echo LM_CRON_TYPE?>
		     </td>
		     <td>
		     <div id="radiob">
				<input  id="radiob1" type=radio size=50 value=0 name='cron_btype' <?php if($_CONFIG[cron_btype]==0) echo 'checked';?>>
				<label for="radiob1"><?php echo LM_CONFIG_CRON_FULL?></label>
				<input  id="radiob2" type=radio size=50 value=1 name='cron_btype' <?php if($_CONFIG[cron_btype]==1) echo 'checked';?>>
				<label for="radiob2"><?php echo LM_CONFIG_CRON_FILES?></label>
				<input  id="radiob3" type=radio size=50 value=2 name='cron_btype' <?php if($_CONFIG[cron_btype]==2) echo 'checked';?>>
				<label for="radiob3"><?php echo LM_CONFIG_CRON_DATABASE?></label>
				<?php echo LM_CRON_TYPE_INFO?>
		       </div>
		     </td>
		    </tr>

		     <tr>
		    <td>
		      <?php echo LM_CRON_BNAME?>
		     </td>
		     <td>
		      <input class="form-control" type=text size=50 value="<?php echo $_CONFIG[cron_bname]?>" name='cron_bname' > <br />
		       <?php echo LM_CRON_BNAME_SUB?>
		     </td>
		    </tr>


		     <tr>
		    <td>
		      <?php echo LM_CRON_IP?>
		     </td>
		     <td>
		      <textarea class="form-control" type=text size=50 name='cron_ip' cols='30' rows='5'><?php echo $_CONFIG[cron_ip]?></textarea> <br />
		       <?php echo LM_CRON_IP_SUB?>
		     </td>
		    </tr>
		    </table>
		</p></div>
	</div>

	<div>
		<h3><a href="#"> <?php echo LM_CRON_FTP_DETAILS?></a></h3>
		<div><p>
			<table class='adminform'>

		    <tr>
		     <td width='250'>
		      <?php echo LM_CRON_FTP_SERVER?>
		     </td>
		     <td>
		      <input type=text size=50 name='cron_ftp_server' value='<?php echo $_CONFIG[cron_ftp_server]?>'>
		     </td>
		    </tr>
		    <tr>
		     <td>
		      <?php echo LM_CRON_FTP_USER?>
		     </td>
		     <td>
		      <input type=text size=50 name='cron_ftp_user' value='<?php echo $_CONFIG[cron_ftp_user]?>'>
		     </td>
		    </tr>
		    <tr>
		     <td>
		      <?php echo LM_CRON_FTP_PASS?>
		     </td>
		     <td>
		      <input type=text size=50 name='cron_ftp_pass' value='<?php echo $_CONFIG[cron_ftp_pass]?>'>
		     </td>
		    </tr>
		    <tr>
		     <td>
		      <?php echo LM_CRON_FTP_PATH?>
		     </td>
		     <td>
		      <input type=text size=50 name='cron_ftp_path' value='<?php echo $_CONFIG[cron_ftp_path]?>'>
		     </td>
		    </tr>
		     <tr>
		     <td>
		      <!--<?php echo LM_CRON_FTP_DELB?>-->
		     </td>
		     <td>
		      <input id="cron_ftp_delb" type=checkbox name='cron_ftp_delb' <?php if($_CONFIG[cron_ftp_delb]==1) echo "checked";?> value='1'>
		      <label for="cron_ftp_delb"><?php echo LM_CRON_FTP_DELB?></label>
		     </td>
		    </tr>
		    </table>
		</p></div>
	</div>

	<div>
		<h3><a href="#"> <?php echo LM_AMAZON_S3?></a></h3>
		<div><p>
			<table class='adminform'>

			<tr>
			<td width='250'>
		     		<?php #echo LM_AMAZON_S3_ACTIVATE?>
			</td>
		     	<td>
		     	<label for="cron_amazon_active"><?php echo LM_AMAZON_S3_ACTIVATE?></label>
				<input id="cron_amazon_active" type=checkbox name='cron_amazon_active' <?php if($_CONFIG[cron_amazon_active]==1) echo "checked";?> value='1'>
				
				<label for="cron_amazon_ssl"><?php echo LM_AMAZON_S3_SSL?></label>
				<input id="cron_amazon_ssl" type=checkbox name='cron_amazon_ssl' <?php if($_CONFIG[cron_amazon_ssl]==1) echo "checked";?> value='1'>
			</td>
			</tr>

			<tr>
			<td>
		     		<?php echo LM_AMAZON_S3_AWSACCESSKEY;?>
			</td>
		     	<td>
				<input type=text size=50  name='cron_amazon_awsAccessKey' value="<?php echo $_CONFIG['cron_amazon_awsAccessKey'];?>">
			</td>
			</tr>

			<tr>
			<td>
		     		<?php echo LM_AMAZON_S3_AWSSECRETKEY;?>
			</td>
		     	<td>
				<input type=text size=50  name='cron_amazon_awsSecretKey' value="<?php echo $_CONFIG['cron_amazon_awsSecretKey'];?>">
			</td>
			</tr>
			
			<tr>
			<td width='200'>
		     		<?php echo LM_AMAZON_S3_BUCKET;?>
			</td>
		     	<td>
				<input type=text size=50  name='cron_amazon_bucket' value="<?php echo $_CONFIG['cron_amazon_bucket'];?>">
			</td>
			</tr>

			<tr>
			<td>
		     		<?php echo LM_AMAZON_S3_DIRNAME;?>
			</td>
		     	<td>
				<input type=text size=50  name='cron_amazon_dirname' value="<?php echo $_CONFIG['cron_amazon_dirname'];?>">
			</td>
			</tr>
		    </tr>
			</table>
		</p></div>
	</div>

	<div>
		<h3><a href="#"> <?php echo LM_CRON_EMAIL_DETAILS?></a></h3>
		<div><p>
			<table class='adminform'>

		    <tr>
		     <td width="250">
		      <?php echo LM_CRON_EMAIL_ACCOUNT?>
		     </td>
		     <td>
		      <input type=text size=50 name='cron_email_address' value='<?php echo $_CONFIG[cron_email_address]?>'>
		     </td>
		    </tr>
			</table>
		</p></div>
	</div>

    <div>
		<h3><a href="#"> <?php echo LM_CRON_MYSQL_DETAILS?></a></h3>
		<div><p>
			<table class='adminform'>

		    <tr bgcolor='#ffffff'>
		     <td width='250'>
		      <?php #echo LM_CRON_MYSQL_DROP?>
		     </td>
		     <td>
		      <label for="cron_sql_drop"><?php echo LM_CRON_MYSQL_DROP?></label>
		      <input id="cron_sql_drop" type=checkbox  name='cron_sql_drop' value='1' <?php if($_CONFIG[cron_sql_drop]) echo "checked";?> >
		     </td>
		    </tr>

		    <?php
		    if((abs($_CONFIG[system_mdatabases])==0) && ($_CONFIG[enable_db_backup]==1)){
		    ?>
		    <tr><td valign='top'>
		    <?php echo LM_DATABASE_INCLUDE_DATABASES?>
		    </td><td>
		    <select class="form-control" name='databases_incl[]' MULTIPLE SIZE=5>
		    <?php

		    $curent_dbs = explode(",", $_CONFIG['databases_incl_list']);

		    $query = @mysql_query("SHOW databases");
		    while($row = @mysql_fetch_array($query)){

			   $table = $row[0];

		       if($table != $_CONFIG['mysql_database'])

			   if(in_array($table, $curent_dbs)){

			     	echo "<option value='".$table."' selected>$table</option>";

			   }else{

				    echo "<option value='".$table."'>$table</option>";

				}
		    }
			?>
		    </select><br />
		    <?php echo LM_DATABASE_INCLUDE_DATABASES_SUB?>
		    </td></tr>
		    <?php
		    }
		    ?>
		    </table>
		</p></div>
	</div>

	<div>
		<h3><a href="#"> <?php echo LM_CRON_DELETE_FILES?></a></h3>
		<div><p>

			<script>
			$(function() {
				$( "#slider2" ).slider({
					value:parseInt(<?php echo (int)$_CONFIG[cron_file_delete];?>),
					min: 0,
					max: 100,
					step: 1,
					slide: function( event, ui ) {
						$( "#cron_file_delete" ).val( ui.value );
					}
				});
				$( "#cron_file_delete" ).val( $( "#slider2" ).slider( "value" ) );
			});
			</script>

			<table class='adminform'>

			<tr>
			<td width='250'>
		      <?php #echo LM_CRON_DELETE_FILES_SUB_ACTIVE?>
		     </td>
		     <td>
		      <label for="cron_file_delete_act"><?php echo LM_CRON_DELETE_FILES_SUB_ACTIVE?></label>
		      <input id="cron_file_delete_act" type=checkbox name='cron_file_delete_act' <?php if ($_CONFIG['cron_file_delete_act'] == 1) echo 'checked';?> value='1'>
		     </td>
		    </tr>
		    <tr>
			<td>
		      <?php echo LM_CRON_DELETE_FILES_SUB?>
		     </td>
		     <td>
			  <div id="slider2" style="width:300px;padding-top:5px;"></div>
			  <br /><label for="cron_file_delete"></label>
		      <input id="cron_file_delete" size=5 name='cron_file_delete' value='<?php echo $_CONFIG[cron_file_delete]?>'> days:
		     </td>
		    </tr>
		    </table>

		    <table class='adminform'>
		    <tr>
		     <th colspan='2'>
		     <?php echo LM_CRON_EXCLUDE?>
		     </th>
		    </tr>
		    </tr>
		    <tr>
		     <td width='250'>
		      <?php echo LM_CRON_EXCLUDE_DIR?>
		     </td>
		     <td>
		      <textarea class="form-control" cols=50 rows=5 name='cron_exclude'><?php echo $_CONFIG[cron_exclude]?></textarea>
		     </td>
		    </tr>

		    </table>
	    </p></div>
	</div>

	</div>
    <?php
    $tabs->endTab();
    $tabs->startTab(LM_TAB_INFO,"6");
    ?>

    <div id="configtabinside">
	<div>
	     <h3><a href="#"><?php echo LM_CONFIG_INFO_PHP?></a></h3>
		<div><p>

		    <table class='adminform'>

		    <tr>
		     <td width='250'>
		      <?php echo LM_CONFIG_INFO_T_VERSION?>
		     </td>
		     <td>
		        <b><?php
				$version = phpversion();
		        $ver = str_replace(".", "", $version);
		        $val = (version_compare(PHP_VERSION, '5.2.3') < 0)? $version: "Off";
		        echo HTML_cloner::get_color($version, $val);
		        ?></b>
		        <br />
		        <?php echo LM_CONFIG_INFO_VERSION?>
		   </td>
		    </tr>

			<tr>
		     <td>
		      <?php echo LM_CONFIG_INFO_T_SAFEMODE?>
		     </td>
		     <td>
		        <b><?php $val = (ini_get('safe_mode') != "")? ini_get('safe_mode'):"Off";
		        echo HTML_cloner::get_color($val, 'On');
		        ?></b>
		        <br />
		        <?php echo LM_CONFIG_INFO_SAFEMODE?>
		   </td>
		    </tr>

		    <tr>
		     <td>
		      <?php echo LM_CONFIG_INFO_T_MTIME?>
		     </td>
		     <td>
		        <b><?php echo (ini_get('max_execution_time') != "")? ini_get('max_execution_time'):"no value";

		        ?></b>
		        <br />
		        <?php echo LM_CONFIG_INFO_TIME?>
		   </td>
		    </tr>

		    <tr>
		     <td>
		      <?php echo LM_CONFIG_INFO_T_MEML?>
		     </td>
		     <td>
		        <b><?php echo (ini_get('memory_limit') != "")? ini_get('memory_limit'):"no value";?> </b>
		        <br />
		         <?php echo LM_CONFIG_INFO_MEMORY?>
		     </td>
		    </tr>

		    <tr>
		     <td>
		      <?php echo LM_CONFIG_INFO_T_BDIR?>
		     </td>
		     <td>
		        <b><?php $val = (ini_get('open_basedir') != "")? ini_get('open_basedir'):"no value";
		        echo HTML_cloner::get_color($val, '/');
		        ?> </b>
		        <br />
		         <?php echo LM_CONFIG_INFO_BASEDIR?>
		     </td>
		    </tr>

		     <tr>
		     <td>
		    <?php echo LM_CONFIG_INFO_T_EXEC?>
		     </td>
		     <td>
		        <b><?php

			$out = "";
			if(function_exists("exec")){

			        $out = @exec("ls -al");
			}

		        $val = ($out != "")? "ENABLED":"<font color='red'>DISABLED</font>";
		        echo HTML_cloner::get_color($val, 'DISABLED');
		        ?> </b>
		        <br />
		         <?php echo LM_CONFIG_INFO_EXEC?>
		     </td>
		    </tr>
		</table>
		</p></div>
	</div>
	<div>
	     <h3><a href="#"><?php echo LM_CONFIG_INFO_PATHS?></a></h3>
		<div><p>
			<table class='adminform'>

		    <tr>
		     <td width='250'>
		      <?php echo LM_CONFIG_INFO_ROOT_BPATH_TMP?>
		     </td>
		     <td>
		        <b><?php $tmp_dir = realpath($_CONFIG['backup_path']."/administrator/backups");
				echo (@is_writeable( $tmp_dir ))? $tmp_dir . " is <font color=green>writeable</font>":$tmp_dir. " <font color=red>incorrect or unreadable</font>";?></b>
		        <br />
		        <?php echo LM_CONFIG_INFO_ROOT_PATH_TMP_SUB?>
		   </td>
		    </tr>

			 <tr>
		     <td>
		      <?php echo LM_CONFIG_INFO_ROOT_BPATH?>
		     </td>
		     <td>
		        <b><?php echo (@is_readable($_CONFIG['backup_path']) )? $_CONFIG['backup_path'] . " is <font color=green>readable</font>":$_CONFIG['backup_path']. " <font color=red>incorrect or unreadable</font>";?></b>
		        <br />
		        <?php echo LM_CONFIG_INFO_ROOT_PATH_SUB?>
		   </td>
		    </tr>


			 <tr>
		     <td>
		      <?php echo LM_CONFIG_INFO_T_BPATH?>
		     </td>
		     <td>
		        <b><?php echo (@is_writeable($_CONFIG['clonerPath']) )? $_CONFIG['clonerPath'] . " is <font color=green>writeable</font>":$_CONFIG['clonerPath']. " <font color=red>unwriteable</font>";?></b>
		        <br />
		        <?php echo LM_CONFIG_INFO_BPATH?>
		   </td>
		    </tr>


		    <tr>
		     <td>
		        <?php echo LM_CONFIG_INFO_T_TAR?>
		     </td>
		     <td>
		        <b><?php
			if(function_exists('exec')){
			        $info_tar_path = explode(" ", @exec("whereis tar"));
			}
		        echo ($info_tar_path['1'] != "")? $info_tar_path['1']:"unable to determine";
		        ?> </b>
		        <br />
		         <?php echo LM_CONFIG_INFO_TAR?>
		     </td>
		    </tr>


		    <tr>
		     <td>
		      <?php echo LM_CONFIG_INFO_T_MSQL?>
		     </td>
		     <td>
		        <b><?php
			if(function_exists('exec')){
			        $info_msql_path = explode(" ", @exec("whereis mysqldump"));
			}
		        echo ($info_msql_path['1'] != "")? $info_msql_path['1']:"unable to determine";
		        ?> </b>
		        <br />
		         <?php echo LM_CONFIG_INFO_MSQL?>
		     </td>
		    </tr>

		    </table>
		    </p></div>

	</div></div>

    <?php
    $tabs->endTab();
    $tabs->endPane();
    ?>
     <input type="hidden" name="option" value="com_cloner" />
     <input type="hidden" name="task" value="config" />
     <input type="hidden" name='action' value='save'>
     </form>

  <?php
  }

  function get_color($val, $comp){

   if(!stristr($val, $comp))
    echo "<span style='color:green'>$val</span>";
   else
    echo "<span style='color:red'>$val</span>";

  }

  function TransferForm($option, $files){
      global $baDownloadPath, $mosConfig_absolute_path, $clonerPath, $task;

     ?>
    <form action="index2.php" method="GET" name="adminForm">
    <script language="javascript" type="text/javascript">


		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}

                submitform( pressbutton );

		}

		function gotocontact( id ) {
			var form = document.adminForm;
			form.contact_id.value = id;
			submitform( 'contact' );
		}
		</script>
    <table class='adminform'>
    <tr><td colspan='2'>
    <b>Transfer <?php echo $file;?> details:</b>
    <br /><b>Attempting to
    <?php echo (($_REQUEST[task]=='move')||($_REQUEST[task2]=='move'))?'Move':'Clone';?> backup(s):</b><br /><?php echo implode("<br />",$files)?>

    </td></tr>
    <tr><td colspan='2'><?php echo LM_CLONE_FORM_TOP?></td></tr>
    <?php
    if(($_REQUEST[task]=='move')||($_REQUEST[task2]=='move')){
    }
    else{

    ?>
    <tr>
     <td width='110'><b><?php echo LM_TRANSFER_URL?></b> </td>
     <td><input type='text' size='30' name='ftp_url' value='<?php echo $_REQUEST[ftp_url]?>'></td>
    </tr>
    <tr>
     <td colspan='2'><?php echo LM_TRANSFER_URL_SUB?></td>
    </tr>
    <?php } ?>
    <tr>
     <td width='110'><b><?php echo LM_TRANSFER_FTP_HOST?></b> </td>
     <td><input type='text' size='30' name='ftp_server'  value='<?php echo $_REQUEST[ftp_server]?>'></td>
    </tr>
    <tr>
     <td colspan='2'><small><?php echo LM_TRANSFER_FTP_HOST_SUB?></small></td></tr>
    <tr>
     <td width='110'><b><?php echo LM_TRANSFER_FTP_USER?></b> </td>
     <td><input type='text' size='30' name='ftp_user'  value='<?php echo $_REQUEST[ftp_user]?>'></td>
    </tr>
    <tr>
     <td colspan='2'><small><?php echo LM_TRANSFER_FTP_USER_SUB?></small></td></tr>
    <tr>
     <td width='110'><b><?php echo LM_TRANSFER_FTP_PASS?></b> </td>
     <td><input type='text' size='30' name='ftp_pass'  value='<?php echo $_REQUEST[ftp_pass]?>'></td>
    </tr>
    <tr>
     <td colspan='2'><small><?php echo LM_TRANSFER_FTP_PASS_SUB?></small></td></tr>
    <tr>
     <td width='110'><b><?php echo LM_TRANSFER_FTP_DIR?></b> </td>
     <td><input type='text' size='30' name='ftp_dir'  value='<?php echo $_REQUEST[ftp_dir]?>'></td>
    </tr>
    <tr>
     <td colspan='2'><small><?php echo LM_TRANSFER_FTP_DIR_SUB?></small></td></tr>

    <tr>
     <td width='140'><b><?php echo LM_TRANSFER_FTP_INCT?></b> </td>
     <td><input type='checkbox' name='ftp_inct'  value='1' <?php if($_REQUEST[ftp_inct] ==1 ) echo "checked";?>></td>
    </tr>
    <tr>
     <td colspan='2'><small><?php echo LM_TRANSFER_FTP_INCT_SUB?></small></td></tr>

    </table>
     <input type="hidden" name="option" value="com_cloner" />
     <input type="hidden" name="task" value="" />
     <input type="hidden" name="task2" value="<?php  if($_REQUEST[task2]!="") echo $_REQUEST[task2]; else echo $task;?>" />
     <?php
     foreach($files as $key=>$value)
     {
     ?>
     <input type="hidden" name="files[<?php echo $key;?>]" value="<?php echo $value?>" />
     <input type="hidden" name="cid[<?php echo $key;?>]" value="<?php echo $value?>" />
     <?php
     }
     ?>
     <input type="hidden" name="action" value="connect" />
     <input type="hidden" name="hidemainmenu" value="0" />
     </form>
     <?php
      }
  function confirmBackups( &$folders, &$sizes, $path, $option ) {
    // ----------------------------------------------------------
    // Presentation of the confirmation screen
    // ----------------------------------------------------------
    global $baDownloadPath, $mosConfig_absolute_path, $clonerPath, $_CONFIG, $database, $mosConfig_db;

    ?>
	<form action="index2.php" method="post" name="adminForm">
	<?php
	$tabs = new mosTabs(1);
	?>

	<script>
	$(function() {
		$( "#tabs" ).tabs().find( ".ui-tabs-nav" ).sortable({ axis: "x" });
		$( "#radio_dbbackup" ).buttonset();
		$( "#radio_dbbackup1" ).button( { icons: {primary:'ui-icon-bullet'} } );
		$( "#radio_dbbackup2" ).button( { icons: {primary:'ui-icon-bullet'} } );
	});
	</script>


	<ul>
		<?php
		 if($_CONFIG['enable_db_backup']){
		?>
		<li><a href="#tabs-users-databse-options-tab"><?php echo LM_TAB_G_DATABASE;?></a></li>
		<?php }?>
		<li><a href="#tabs-users-files-options-tab"><?php echo LM_TAB_G_FILES;?></a></li>
		<li><a href="#tabs-users-comments-options-tab"><?php echo LM_TAB_G_COMMENTS;?></a></li>
	</ul>

	<?php
	#$tabs->startPane("BGeneratePane");
    if($_CONFIG['enable_db_backup']){
	$tabs->startTab(LM_TAB_G_DATABASE,"users-databse-options-tab");
    ?>

	<div id="radio_dbbackup">
    <table class="adminform">
    <!--<tr>
     <th colspan=2>
       <b><?php #echo LM_DATABASE_ARCHIVE; ?></b>
     </th>
    </tr>-->
    <tr>
        <td>
			<label for="radio_dbbackup1"><?php echo LM_CONFIRM_DATABASE; ?></label>
			<input id="radio_dbbackup1" type="checkbox" id="dbbackup" name="dbbackup" checked value="1" />
			&nbsp;<label for="radio_dbbackup2">Add DROP SYNTAX</label>
			<input id="radio_dbbackup2" type="checkbox" id="dbbackup_drop" name="dbbackup_drop"  value="1" />
        </td>
    </tr>
    <tr>
        <td><?php echo "Mysql Compatibility"; ?> &nbsp;
           <select class="form-control" name='dbbackup_comp'>
           <option value=''>Default</option>
           <option value='MYSQL40'>MYSQL40</option>
           <option balue='MYSQL323'>MYSQL323</option>
           </select>
           </td>
    </tr>
    <tr><th colspan=2>
    <?php echo LM_DATABASE_EXCLUDE_TABLES?>
    </th></tr>
    <tr><td>
    <?php echo LM_DATABASE_CURRENT?> <b><?php echo $_CONFIG['mysql_database'];?></b><br />
	<select class="form-control" name='excltables[]' MULTIPLE SIZE=15>
    <?php

    $query = mysql_query("SHOW tables");
    while($row = mysql_fetch_array($query)){

		 echo "<option value='".$row[0]."'>$row[0]</option>";

		}
    ?>
    </select>
    </td></tr>

    <?php
    if(abs($_CONFIG[system_mdatabases])==0){
    ?>

	<tr><th colspan=2>
    <?php echo LM_DATABASE_INCLUDE_DATABASES?>
    </th></tr>
    <tr><td>
    <select class="form-control" name='databases_incl[]' MULTIPLE SIZE=5>
    <?php

    $query = mysql_query("SHOW databases");

	while($row = mysql_fetch_array($query)){

		if($_CONFIG['mysql_database'] != $row[0])
			echo "<option value='".$row[0]."'>$row[0]</option>";

	    }

	?>
    </select><br />
    <?php echo LM_DATABASE_INCLUDE_DATABASES_SUB?>
    </td></tr>

	<?php
    }
	?>

	</table>
	</div>
    <?php
    $tabs->endTab();
    }
    $tabs->startTab(LM_TAB_G_FILES,"users-files-options-tab");
    ?>
    <table class="adminform">
    <tr>
     <th>
       <b><?php echo LM_BACKUP_NAME; ?></b>
     </th>
    </tr>
    <tr>
     <td>
       <input class="form-control" type=text name='bname' value='' size=100><br/>
       <?php echo LM_BACKUP_NAME_SUB?>
     </td>
    </tr>

    <tr>
        <td width="50%"><?php echo LM_CONFIRM_INSTRUCTIONS ?></td>
    </tr>
    </table>
    <table class="table table-hover table-condensed table-responsive">
    <tr>
      <th width="200" valign='top' colspan='2' align='left'>

      <?php echo LM_COL_FOLDER ?>
      <?php
	  {
      ?>
    <tr><td>
    <link href="browser/filebrowser.css" rel="stylesheet" type="text/css">

	<script type="text/javascript" src="browser/xmlhttp.js"></script>


    <div id="browser">
    <?php require_once("browser/files_inpage.php"); ?>
    </div>
    <script>do_browser()</script>

    </td></tr>
    <?php
    }
    ?>

    </table>
     <?php
    $tabs->endTab();
    $tabs->startTab(LM_TAB_G_COMMENTS,"users-comments-options-tab");
    ?>
		<div class="mainText">
		<h2><?php echo LM_TAB_G_COMMENTS_H2?></h2>
		<textarea class="form-control" name="backupComments" rows=20 cols=80></textarea>
		<br /><small> <?php echo LM_TAB_G_COMMENTS_NOTE?></small>
		</div>
    <?php
    $tabs->endTab();
    $tabs->endPane();
    ?>
    <input type="hidden" name="option" value="com_cloner" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="hidemainmenu" value="0" />
    </form>
    <br/>&nbsp;
    <?php
  }


  function generateBackup( $archiveName, $archiveSize, $originalSize, $d, $f, $databaseResult, $option ) {
    // ----------------------------------------------------------
    // Presentation of the final report screen
    // ----------------------------------------------------------


    ?>
    <table cellpadding="4" cellspacing="0" border="0" width="100%">

    <table border="0" align="center" cellspacing="0" cellpadding="2" width="100%" class="adminform">
    </tr>
    <tr>
      <td width="20%"><strong>&nbsp;</strong></td><td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>&nbsp;<?php echo LM_ARCHIVE_NAME; ?></strong></td><td><?php echo $archiveName; ?></td>
    </tr>
    <tr>
      <td><strong>&nbsp;<?php echo LM_NUMBER_FILES; ?></strong></td><td><?php echo $f; ?></td>
    </tr>
    <tr>
      <td><strong>&nbsp;<?php echo LM_SIZE_ORIGINAL; ?></strong></td><td><?php echo $originalSize; ?></td>
    </tr>
    <tr>
      <td><strong>&nbsp;<?php echo LM_SIZE_ARCHIVE; ?></strong></td><td><?php echo $archiveSize; ?></td>
    </tr>
    <tr>
      <td><strong>&nbsp;<?php echo LM_DATABASE_ARCHIVE; ?></strong></td><td><?php echo $databaseResult; ?></td>
    </tr>


    <tr>
      <td><strong>&nbsp;</strong></td><td>&nbsp;</td>
    </tr>
    </table>
    <form action="index2.php" name="adminForm" method="post">
    <input type=hidden name=files[1] value='<?php echo $archiveName?>'>
    <input type=hidden name=cid[1] value='<?php echo $archiveName?>'>
    <input type="hidden" name="option" value="<?php echo $option; ?>"/>
    <input type="hidden" name="task" value=""/>
    </form>
    <?php
  }

  function generateBackup_text( $archiveName, $archiveSize, $originalSize, $d, $f, $databaseResult, $option ) {
    // ----------------------------------------------------------
    // Presentation of the final report screen in text mode
    // ----------------------------------------------------------

    ob_start();
    ?>
    <?php echo LM_ARCHIVE_NAME; ?>: <?php echo $archiveName."\r\n"; ?><br />
    <?php echo LM_NUMBER_FILES; ?>: <?php echo $f."\r\n"; ?><br />
    <?php echo LM_SIZE_ORIGINAL; ?>: <?php echo $originalSize."\r\n"; ?><br />
    <?php echo LM_SIZE_ARCHIVE; ?>: <?php echo $archiveSize."\r\n"; ?><br />
    <?php echo LM_DATABASE_ARCHIVE; ?>: <?php echo $databaseResult."\r\n"; ?><br />
    ### END REPORT
    <?php
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
  }

  function showHelp( $option ) {
    ?>
		<script>
	$(function() {
		$( "#tabs" ).tabs();
	});
	</script>

	<div id="tabs" >
	<ul>
		<li><a href="#tabs-1"><?php echo LM_CREDIT_TOP?></a></li>
	</ul>
		<div id="tabs-1" >
			<div class="mainText">

		        <?php echo LM_CLONER_ABOUT?>
			</div>
		</div>
	</div>
    <form action="index2.php" name="adminForm" method="post">
    <input type="hidden" name="option" value="<?php echo $option; ?>"/>
    <input type="hidden" name="task" value=""/>
    </form>
    <?php
  }

  function Restore( $option ) {
    // ----------------------------------------------------------
    // Presentation of the Help Screem
    // ----------------------------------------------------------

    ?>

	<script>
	$(function() {
		$( "#tabs" ).tabs();
	});
	</script>

	<div id="tabs">
	<ul>
		<li><a href="#tabs-1"><?php echo LM_RESTORE_TOP?></a></li>
	</ul>
		<div id="tabs-1"><p class="mainText">
		    <table border="0" align="center" cellspacing="0" cellpadding="2" width="100%" class="adminform">
		    <tr>
		      <td>
		        <?php echo LM_CLONER_RESTORE?>
		      </td>
		    </tr>
		    </table>
	    </p></div>
    </div>
    <form action="index2.php" name="adminForm" method="post">
    <input type="hidden" name="option" value="<?php echo $option; ?>"/>
    <input type="hidden" name="task" value=""/>
    </form>
    <?php
  }
  function showCredits( $option ) {
    // ----------------------------------------------------------
    // Presentation of the Help Screem
    // ----------------------------------------------------------

    ?>
	<script>
	$(function() {
		$( "#tabs" ).tabs();
	});
	</script>

	<div id="tabs">
	<ul>
		<li><a href="#tabs-1"><?php echo LM_CREDIT_TOP?></a></li>
	</ul>
		<div id="tabs-1"><p class="mainText">
		    <table border="0" align="center" cellspacing="0" cellpadding="2" width="100%" class="adminform">
		    <tr>
		      <td>
		      <?echo LM_CLONER_CREDITS?>
		      </td>
		    </tr>
		    </table>
	    </p></div>
    </div>
    <form action="index2.php" name="adminForm" method="post">
    <input type="hidden" name="option" value="<?php echo $option; ?>"/>
    <input type="hidden" name="task" value=""/>
    </form>
        
        
        
        
        
        
    <?php
  }


  function Rename($files, $option){
       global $_CONFIG;

    ?>
    <form action="index2.php" method="post" name="adminForm">
   <table border="0" align="center" cellspacing="0" cellpadding="2" width="100%" class="adminform">
    <tr><th colspan='2'>
    <?php echo LM_RENAME_TOP?>
    </th></tr>
    <?php

    foreach($files as $key=>$file){
        echo "<tr>
      <td >
        ".LM_RENAME." <input type=hidden name='cfile[]' value='$file' ><b>$file</b>
       </td>
      <td align='left'>
        ".LM_RENAME_TO." <input type=text name='dfile[]' value='$file' size=100>
       </td>
    </tr>";
        }

    ?>
    </table>
    <form action="index2.php" name="adminForm" method="post">
    <input type="hidden" name="option" value="<?php echo $option; ?>"/>
    <input type="hidden" name="task" value="rename_save"/>
    </form>
		 
			 
		

        </div>


	</body>
</html>
    <?php
      }

}
?>
		
												
	 
		