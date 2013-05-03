<?php
/*
  $Id: links_submit.php v2.1 2008-11-14 00:52:16Z hpdl $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/
    require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/links.php');
  require(DIR_WS_LANGUAGES . $language . '/header_tags.php');
  $action = $_GET['action'];
	$sess_id = (tep_not_null(SID));
	if ($action == 'add_link'){
	$error=false;
	// clean posted vars
	reset($_POST);
      while (list($key, $value) = each($_POST)) {
			  if (!is_array($_POST[$key])) {
          $_POST[$key] = preg_replace("/[^ \/a-zA-Z0-9@:{}_.-]/i", "", urldecode($_POST[$key]));
        } else { unset($_POST[$key]); } // no arrays expected
      }
  $mailerror = (strlen(trim($_POST['email']))<1);
	if (strlen(trim($_POST['email']))>0 && tep_validate_email($_POST['email']) == false) $mailerror = true;
	$urlerror = (strlen(trim($_POST['url'],'http:// '))<1);
	$rurlerror = (strlen(trim($_POST['recurl'],'http:// '))<1);
	$titleerror = (strlen(trim($_POST['title']))<1);
	$descerror = (strlen(trim($_POST['description']))<1 || strlen($_POST['description'])>280 );
	$nameerror = (strlen(trim($_POST['name']))<1);
	$_POST['description'] = substr($_POST['description'],0,280);
	$error = $nameerror || $descerror || $titleerror || $rurlerror || $mailerror || $urlerror;
  		if (!$error) {
			$sql_data_array = array('link_title' => tep_db_prepare_input($_POST['title']),
																	'link_url' => tep_db_prepare_input($_POST['url']),
																	'link_description' => tep_db_prepare_input($_POST['description']),
																	'link_date' => date("Y-m-d H:i:s"),
																	'name' => tep_db_prepare_input($_POST['name']),
																	'email' => tep_db_prepare_input($_POST['email']),
																	'category' => (int)tep_db_prepare_input($_POST['category']),
																	'new_category' => tep_db_prepare_input($_POST['new_category']),
																	'reciprocal' => tep_db_prepare_input($_POST['recurl']));
																	tep_db_perform('links', $sql_data_array);
					  tep_redirect(tep_href_link('links_submit.php',tep_get_all_get_params(array('action')) . 'action=submited'));
			}
  }
  $category_array = array();
	$category_array[0] = array('id' => '0', 'text' => 'Please Select');
	$category_query = tep_db_query("select category_id, category_name from links_categories where status = 1 order by sort_order, category_name");
  while ($category_values = tep_db_fetch_array($category_query)) {
    $category_array[] = array('id' => $category_values['category_id'], 'text' => $category_values['category_name']);
  }
	 require(DIR_WS_FUNCTIONS.'pagerank.php');
	$breadcrumb->add('links', tep_href_link('links.php', '', 'SSL'));
  $breadcrumb->add('links submit', tep_href_link('links_submit.php', '', 'SSL'));

 require(DIR_WS_INCLUDES . 'header.php'); 
 require(DIR_WS_INCLUDES . 'column_left.php'); ?>


<!-- body_text //-->

<?php if ($action == 'submited'){ ?>
 <table width="100%"border="0" cellspacing="0" cellpadding="0">
 <tr>
    <td><h1><?php echo SUBMIT;?></h1></td></tr>
 <tr><td><br /><br />Thank you for your submission, once we have found your reciprocal link a moderator will approve your link and it will appear here.</td></tr>

 <tr><td align="center"><?php echo  tep_draw_form(add_link,tep_href_link('links.php',tep_get_all_get_params(array('action'))));?><INPUT class="button" type=submit value="<?php echo 'BACK'?>"></FORM></td></tr>
 </table>
 <?php } else {
  if ($action == 'add_link') $status = 'class="messageStackSuccess"'?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
    <td><h1><?php echo SUBMIT;?></h1>
      <P><B><?php echo STEP1;?></B></P>
      <TABLE width="100%" border=0 class=infoBoxContents>
        <TBODY>
          <TR>
            <TD width="20%"><?php echo TURL ?></TD>
            <TD width="80%"><A class="headerInfo" href="<?php echo HTTP_SERVER; ?>" target=_blank><?php echo HTTP_SERVER; ?></A></TD>
          </TR>
          <TR>
            <TD><?php echo TTITLE;  ?></TD>
            <TD><?php echo HEAD_TITLE_TAG_DEFAULT; ?></TD>
          </TR>
          <TR>
            <TD height="40" style="vertical-align:top;"><?php echo TDESCRIPTION; ?></TD>
            <TD ><?php echo HEAD_DESC_TAG_DEFAULT; ?></TD>
          </TR>
        </TBODY>
      </TABLE>
      <P>
        <?php echo tep_draw_textarea_field('codes','Physical',40,5,HEAD_DESC_TAG_DEFAULT);?>
      </P>
      <P><B><?php echo STEP2;?></B></P>
      <P class="main"><?php echo STEP2DS?></P>
    <?php echo  tep_draw_form('add_link',tep_href_link('links_submit.php',tep_get_all_get_params() . 'action=add_link' . ($sess_id ? '' : '#submit')), 'post');?>
     </td></tr><tr><td align="center">
        <TABLE border=0 class=infoBoxContents>
          <TBODY>
					<?php
  if ($error) {
?>
			<tr>
        <td id="mess" colspan="3" class="messageStackError" align="center"><?php echo 'Error: Not All Fields Completed Correctly'; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
  }
?>
            <TR>
              <TD align="left"><B><?php echo NAME  ?></B></TD>
              <TD <?php echo ($nameerror ? 'class=messageStackError' : $status) ?>><?php echo tep_draw_input_field('name', $_POST['name'], 'maxLength=60 size=70') ?>
			  </TD>
            </TR>
            <TR>
              <TD align="left"><B><?php echo EMAIL?></B></TD>
              <TD <?php echo ($mailerror ? 'class=messageStackError' : $status) ?>><?php echo tep_draw_input_field('email', $_POST['email'], 'maxLength=80 size=70 ' ); ?></TD>
            </TR>
            <TR>
              <TD align="left"><B><?php echo WTITLE?></B></TD>
              <TD <?php echo ($titleerror ? 'class=messageStackError' : $status) ?>><?php echo tep_draw_input_field('title', $_POST['title'], 'maxLength=60 size=70') ?></TD>
            </TR>
            <TR>
              <TD align="left"><B><?php echo WURL?></B></TD>
              <TD <?php echo ($urlerror ? 'class=messageStackError' : $status) ?>><?php echo tep_draw_input_field('url', ($_POST['url'] ? $_POST['url'] : 'http://'), 'maxLength=80 size=70') ?></TD>
            </TR>
            <TR>
              <TD align="left"><B><?php echo RURL;?></B></TD>
              <TD <?php echo ($rurlerror ? 'class=messageStackError' : $status) ?>><?php echo tep_draw_input_field('recurl', ($_POST['recurl'] ? $_POST['recurl'] : 'http://'), 'maxLength=80 size=70') ?></TD>
            </TR>
						<TR>
              <TD align="left"><B><?php echo 'Category: ';?></B></TD>
							<td ><table summary="" width="100%" cellspacing="0" cellpadding="0"><tr><TD align="left" class="smallText"><?php echo tep_draw_pull_down_menu('category', $category_array, $_POST['category']). '</td><td align="right" class="smallText">Suggest New Category: ' . tep_draw_input_field('new_category', $_POST['new_category'], 'maxLength=32 size=18 ' );; ?></TD></tr>
</table></td>

            </TR>
          </TBODY>
        </TABLE>
				</td></tr><td align="left" class="smallText"><br />
        <P <?php echo ($descerror ? 'class=messageStackError' : $status) ?>><B><?php echo WDES;?></B><BR>
            <?php echo tep_draw_textarea_field('description','Physical',40,4,$_POST['description'], 'maxlength="50"');?>
        </P>
				</td></tr><tr><td align="center"><br />
        <P>
          <INPUT class="button"  type=submit id="submit" value="<?php echo ADD;?>">
        </P>
      </FORM></td>
  </tr>
	<tr><td align="center"><br /><?php echo  tep_draw_form(add_link,tep_href_link('links.php',tep_get_all_get_params(array('action'))));?><INPUT class="button" type=submit value="<?php echo 'BACK'?>"></FORM></td></tr>
	     <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
</table>
<?php } // action end ?>


<!-- body_text_eof //-->

	</td>
<!-- body_text_eof //-->


<?php require(DIR_WS_INCLUDES . 'column_right.php');
 require(DIR_WS_INCLUDES . 'footer.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
