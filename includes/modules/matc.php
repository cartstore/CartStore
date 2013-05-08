<?php
//We shall now create the disabled looking button... If it doesn't exits allready and is needed
	if(MATC_BUTTONSTYLE == 'gray' && version_compare(PHP_VERSION, "5") == -1){
		die('Your php version is to low to use gray buttons in the MATC extension for oscommerce. Please change "Disabled buttonstyle" in your oscommerce admin panel.');
	}
	if(!file_exists(DIR_WS_LANGUAGES . $language . '/images/buttons/button_continue_disabled.gif') && MATC_BUTTONSTYLE == 'gray'){
		$TempImage = imagecreatefromgif(DIR_WS_LANGUAGES . $language . '/images/buttons/button_continue.gif');
		imagefilter($TempImage, IMG_FILTER_GRAYSCALE); //Make the button gray...
		imagefilter($TempImage, IMG_FILTER_BRIGHTNESS, 35); //And a little bit brighter
		imagegif($TempImage, DIR_WS_LANGUAGES . $language . '/images/buttons/button_continue_disabled.gif');
		imagedestroy($TempImage);
	}
?>
<script type="text/javascript" src="templates/system/jquery.color.js"></script>
<script type="text/javascript"><!--
function disablebutton(){
	$("#TheSubmitButton, #matc-submit-button .ui-btn").attr("disabled","disabled").hide();  //This disables and hides TheSubmitButton
	$("#TheDisabledButton").fadeIn(300);
}

function enablebutton(){
	$("#TheSubmitButton, #matc-submit-button .ui-btn").removeAttr("disabled").fadeIn(300);//This enables and shows the TheSubmitButton.
	$("#TheDisabledButton").hide();
}

function updatebutton(){
	if($("input[name='TermsAgree']").attr("checked")){
		enablebutton();
	}else{
		disablebutton();
	}
}

function warningon(){
	if(!$("input[name='TermsAgree']").attr("checked")){
		$("#CAparagraph").animate({backgroundColor: "#FF0000"}, 200);
	}
}

function warningoff(){
	$("#CAparagraph").animate({backgroundColor: "#FFFFFF"}, 200);
}

//Initiate everything
<?php if (IS_MOBILE_DEVICE == true): ?>
$(document).bind('pageinit',function() {
<?php else: ?>
$(document).ready(function(){
<?php endif; ?>
  	$("#TheSubmitButton").parent().after('<?php
	if(MATC_BUTTONSTYLE == 'gray'){
		echo tep_image(DIR_WS_LANGUAGES . $language . '/images/buttons/button_continue_disabled.gif', '', '', '', ' id="TheDisabledButton" style="display:none;cursor:not-allowed;" onMouseOut="warningoff()" onMouseOver="warningon()"');
	}else{
		echo tep_image(DIR_WS_LANGUAGES . $language . '/images/buttons/button_continue.gif', '', '', '', ' id="TheDisabledButton" style="display:none;cursor:not-allowed;filter:alpha(opacity=33);-moz-opacity:.33;opacity:.33;" onMouseOut="warningoff()" onMouseOver="warningon()"');
	} ?>');

	if(!$("input[name='TermsAgree']").attr("checked")){ //if it isnt checked the button should be disabled
		disablebutton();
	}
});

//--></script>

<?php
if(MATC_SHOW_TEXTAREA != 'false'){ //START "show the textarea if"
	if(MATC_TEXTAREA_FILENAME != ''){//There is a file we should require
		require(DIR_WS_LANGUAGES . $language . '/' . MATC_TEXTAREA_FILENAME);
	}

	if(MATC_TEXTAREA_MODE == 'Returning code'){
		eval('$textarea_contents_material ='.MATC_TEXTAREA_RETURNING_CODE.';');
	}elseif(MATC_TEXTAREA_MODE == 'SQL'){
		eval('$contents_query = tep_db_query('.MATC_TEXTAREA_SQL.');');
   		$contents_query_array = tep_db_fetch_array($contents_query);
		$textarea_contents_material = $contents_query_array['thetext'];
	}else{
		die('No mode was catched! Search for "qwetyqouty34657+234" in matc.php fo find the place where the error occured.'); //Just for error checking.
	};

	if(MATC_TEXTAREA_HTML_2_PLAIN_TEXT_CONVERT  != 'false'){ //Use the conversion tool
		require_once(DIR_WS_CLASSES.'html2text.php');// Include the class definition file.
		$h2t = new html2text(html_entity_decode($textarea_contents_material,ENT_QUOTES,'ISO8859-1'));// Instantiate a new instance of the class. Passing the string variable automatically loads the HTML for you.
		$h2t->width=0; //Do not use word wrap
		$textarea_contents = $h2t->get_text();// Simply call the get_text() method for the class to convert the HTML to the plain text. Store it into the variable.
	}else{//Use the "raw material", that is we do not convert it to plain text
		$textarea_contents = $textarea_contents_material;
	};
?>
<h3><br /><strong><?php echo MATC_HEADING_CONDITIONS; ?></strong></h3>
 <div class="scroll_terms"><?php echo $textarea_contents; ?></div>

 
<?php
}//End "show the textarea if"
?>
<br /><br />
<div id="CAparagraph">
		<?php
			if(MATC_SHOW_LINK != 'false'){
				echo sprintf(MATC_CONDITION_AGREEMENT, tep_href_link(MATC_FILENAME, MATC_PARAMETERS));
			}else{
				echo strip_tags(MATC_CONDITION_AGREEMENT);
			}

			echo tep_draw_checkbox_field('TermsAgree','true', false, 'id="TermsAgree" onchange="updatebutton()"');
		?>
	</div>
<br /><br />