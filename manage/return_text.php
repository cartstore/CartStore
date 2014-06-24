<?php
 /*
 $id author Puddled Internet - http://www.puddled.co.uk
  email support@puddled.co.uk
   osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License


*/
?>
<?php require('includes/application_top.php');?>

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>



<script language="Javascript1.2"><!-- // load htmlarea
_editor_url = "<?php echo HTTP_SERVER . DIR_WS_ADMIN . 'htmlarea/' ?>";                     // URL to htmlarea files
var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
if (win_ie_ver >= 5.5) {
 document.write('<scr' + 'ipt src="' +_editor_url+ 'editor.js"');
 document.write(' language="Javascript1.2"></scr' + 'ipt>');
} else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
// --></script>
<?php
  if ( ($action == 'new') || ($action == 'edit') ) {
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">
<script language="JavaScript" src="includes/javascript/calendarcode.js"></script>
<?php
  }
?>


<div id="popupcalendar" class="text"></div>

<div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a>
<?php
       echo "Returns Text";
 ?> </h1>
 </div>
          <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body">
<i class="fa fa-asterisk fa-5x pull-left"></i>
Help for this section is not yet available.                                  </div>
                      </div>
                  </div>   
              </div>    
 
 <?php


  if ($REQUEST_METHOD=="POST")
  {
  
    tep_db_query('REPLACE INTO return_text VALUES (1, "' . $languages_id . '", "'  . $aboutus .'")')
          or die(tep_db_error());
  }

  $sql=tep_db_query("SELECT * FROM return_text where return_text_id = '1' and language_id = '" . $languages_id . "'")
    or die(tep_db_error());
  $row=tep_db_fetch_array($sql);

?>

 <form name="aboutusform" method="Post" action="">
<div class="form-group"><label>Returns Text</label>
  <textarea name="aboutus" class="form-control"><?php echo $row['return_text_one'] ?></textarea></td>
    
  <script language="JavaScript1.2" defer>
editor_generate('aboutus');
</script>
</div>
<p>
 <input type="submit" class="btn btn-default" name="Save" value="Save" /> </p>


<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<p> </p>
<p> </p><p><br>
</p></body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
