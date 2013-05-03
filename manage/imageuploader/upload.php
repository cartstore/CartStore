<?php


	#	SWFUpload Email Notify
		
		# SWFUpload upload.php with simple file saving and email reporting
	
		# by Eric Pecoraro ( eric dot pecoraro at shepard dot com )
		# ***USE AT YOUR OWN RISK***  ***NO WARRENTIES EXPRESSED OR IMPLIED***
		# THIS SCRIPT WAS *NOT* WRITTEN FOR SECURITY, BUT RATHER AS SIMPLE PHP UPLOAD IMPLEMENTATION.
		# FEEL FREE TO USE/CHANGE/MODIFY/REDISTRIBUTE. Please notify me with improvements.
		# Developed as sample to be used in conjunction with SWFUpload (http://www.swfupload.org)
		
		# ABSTRACT
		# Saves SWFUpload uploaded files to a directory and intelligently reports on the process via email. 
		
		# SIMPLE SETUP, UP & RUNNING IN A FEW MINUTES
		# 1. Replace the original "upload.php" files from the SWFUpload v2.1.0 package with this file.
		# 2. Assign your email address to $upload_notify_email below.
		# 3. Create a PHP writable "uploads" directory as follows: swfupload/demos/uploads
		


	#	Setup SWFUpload Email Notify
	#	---------------	
	
		$upload_email_reporting = false ; 	// true or false (false turns email reporting off)
	
		$upload_notify_email = 'scriptTest@mailinator.com' ;  	// enter your valid email address
		
		$upload_directory = '../../images/' ; // leave blank for default
		
		# DEFINING $upload_directory
		# Must point to a PHP writable directory
		# See http://www.onlamp.com/pub/a/php/2003/02/06/php_foundations.html for dealing with PHP permissions
		
		/*
		The default directory for this script is set to "uploads" directory 
		in the same directory as the index.php of the SWFUpload demo files:
		
			# SWFUpload v2.1.0 Beta.zip (SWFUpload package)
				# swfupload/demos/uploads
				
		This 'uploads' directory may not exist with the SWFUploads package and may need created (with PHP write permissions).
		In any case, this script will send an email message concerning the status of the upload directory.
		*/
		
		
	#	PHP Email Configuration Test
	#	---------------	
		# Set to "true" to test if your server's PHP mail() function configuration works, by attempting to upload one file.
		# A simple email will be sent per upload attempt, letting you know PHP's mail() funciton is working.
		$test_php_mail_config = false ; // true or false
		







	#	---------------
	#	NO MODIFICATIONS REQUIRED BELOW THIS LINE
	#	---------------
	

	#	CREATE DEFAULT UPLOAD DIRECTY LOCATION
	#	---------------	
		If ( !$upload_directory ) {
			$upload_directory = '../../../images/' ; 
			$parent_dir = array_pop(explode(DIRECTORY_SEPARATOR, dirname(__FILE__)));
			$upload_directory = substr(dirname(__FILE__), 0, strlen(dirname(__FILE__)) - strlen($parent_dir) ) . $upload_directory ; 
		}

		

	#	---------------	
	#	EMAIL TESTS
		If ( !$upload_notify_email ) {
			$upload_email_reporting = false ;
		}
		# Sends one email per SWFUpload attempt. 
		if ( $test_php_mail_config == true ) {
			send_mail("SWFUpload Email Test: SUCCESS!",'Be sure to set $test_php_mail_config back to false so that SWFUpload Email Notify reporting is turned on.'); 
			$upload_email_reporting = false ;
		}
	
	
	#	---------------	
	#	TEST UPLOAD DIRECTORY

		If ( !file_exists($upload_directory) ) {	
			$msg = "The assigned SWFUpload directory, \"$upload_directory\" does not exist."; 
			send_mail("SWFUpload Directory Not Found: $upload_directory",$msg);
			$upload_email_reporting = false ;
		}
		if ( $upload_email_reporting == true ) {
			$uploadfile = $upload_directory. DIRECTORY_SEPARATOR . basename($_FILES['Filedata']['name']);   
			if ( !is_writable($upload_directory) ) {
				$msg = "The directory, \"$upload_directory\" is not writable by PHP. Permissions must be changed to upload files."; 
				send_mail("SWFUpload Directory Unwritable: $upload_directory",$msg);
				$upload_directory_writable = false ;
			} else {
				$upload_directory_writable = true ;
			}
		}
	

	// Work-around for setting up a session because Flash Player doesn't send the cookies
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}
	session_start();



	if ( !isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
		
		#	---------------	
		#	UPLOAD FAILURE REPORT
			if ( $upload_email_reporting == true ) {
				switch ($_FILES['Filedata']["error"]) {	
					case 1: $error_msg = 'File exceeded maximum server upload size of '.ini_get('upload_max_filesize').'.'; break;
					case 2: $error_msg = 'File exceeded maximum file size.'; break;
					case 3: $error_msg = 'File only partially uploaded.'; break;
					case 4: $error_msg = 'No file uploaded.'; break; 
				}
				send_mail("SWFUpload Failure: ".$_FILES["Filedata"]["name"],'PHP Error: '.$error_msg."\n\n".'Save Path: '.$uploadfile."\n\n".'$_FILES data: '."\n".print_r($_FILES,true)); 
			}
			
	
		echo "There was a problem with the upload";
		exit(0);

	} else {
	

		#	---------------	
		#	COPY UPLOAD SUCCESS/FAILURE REPORT
			if ( $upload_email_reporting == true AND $upload_directory_writable == true ) {
				if ( move_uploaded_file( $_FILES['Filedata']['tmp_name'] , $uploadfile ) ) {
				 send_mail("SWFUpload File Saved: ".$_FILES["Filedata"]["name"],'Save Path: '.$uploadfile."\n\n".'$_FILES data: '."\n".print_r($_FILES,true)); 
				}else{
				 send_mail("SWFUpload File Not Saved: ".$_FILES["Filedata"]["name"],'Save Path: '.$uploadfile."\n\n".'$_FILES data: '."\n".print_r($_FILES,true)); 
				}
			}


		echo "Flash requires that we output something or it won't fire the uploadSuccess event";
	}
	
	


	#	---------------	
	#	MAIL FUNCTION
		function send_mail($subject="Email Notify",$message="") { 
			global $upload_notify_email ; 
			$from = 'SWFUpload@mailinator.com' ; 
			$return_path = '-f '.$from ;
			mail($upload_notify_email,$subject,$message,"From: $from\nX-Mailer: PHP/ . $phpversion()");
		}

	
	
?>