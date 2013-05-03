<?php
/*
  $Id: link_manage.php v2.2 2008-11-12 00:52:16Z hpdl $
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com
  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible

 */
  require('includes/application_top.php');
  require(DIR_FS_CATALOG.'includes/functions/pagerank.php');
	if (!is_dir(DIR_FS_CATALOG_IMAGES . '/links')) mkdir(DIR_FS_CATALOG_IMAGES . '/links');  //Create links directory within images if it does'nt exist.
	$links_per_page = 10; // set number of links to display per page.
	$page = (isset($_GET['page']) ? '&page=' . $_GET['page'] : '');
	$action = (isset($_GET['action']) ? $_GET['action'] : '');
	$linkID = (isset($_GET['l_id']) ? $_GET['l_id'] : '');
	$valid_lid = tep_not_null($linkID);
	$catID = (isset($_GET['cat_id']) ? $_GET['cat_id'] : '');
	$valid_cat = tep_not_null($catID);
	$sess_id = (tep_not_null(SID));
  if (tep_not_null($_POST['sort_category'])) $_GET['sort_category'] = $_POST['sort_category'];
	if (tep_not_null($_GET['sort_category'])) { $sort_category = $_GET['sort_category']; $sort = '&sort_category=' . $_GET['sort_category']; }

	function URLCheck($linksID='')
    {
		global $ctr, $fnd, $sort_category;
		$category_array = array();
    $category_query = tep_db_query("select category_id, category_name from links_categories");
    while ($category_values = tep_db_fetch_array($category_query)) {
		$categories[$category_values['category_id']] = $category_values['category_name'];
    }
    set_time_limit(300);
		if ($linksID=='') {
		if ($sort_category && $sort_category != 999)
		$links_query = tep_db_query("select reciprocal, links_id from links where category = '" . $sort_category . "'");
		else
    $links_query = tep_db_query("select reciprocal, links_id, category from links");
		} else {
		$links_query = tep_db_query("select reciprocal, links_id from links where links_id = '" . (int)$linksID  . "'");
    }
		$fnd = 0;
		$ctr = 0;
		$from = 0;
    $max =  tep_db_num_rows($links_query);
    $check_phrase = LM_CHECK_PHRASE;  // set in language file
    while ($links = tep_db_fetch_array($links_query)) {
		if ($sort_category == 999 && ($categories[$links['category']])) continue;
		  $links_id = $links['links_id'];
			$found = 0;
      if ($ctr < $from)
      {
        $ctr++;
        continue;
      }
 					if (($file = @fopen($links['reciprocal'],'r'))) {

   			     $phases = explode(",", $check_phrase);

     			   while (!feof($file)) {
     		     $page_line = trim(fgets($file, 4096));

     			     for ($i = 0; $i < count($phases); ++$i)
     				     {
           				 if (@preg_match("/".$phases[$i]."/i", $page_line)) {
          		    $found = 1;
         			     break;
         			   }
        	  } // loop
          if ($found)
           break;
          } // while

        fclose($file);

        tep_db_query("update links set link_found = '" . (int)$found  . "', date_last_checked = '" .date("Y-m-d H:i:s")."' where links_id = '" . (int)$links_id  . "'");

       } else {  // invalid recip url
        tep_db_query("update links set link_found = 0, date_last_checked = '0000-00-00 00:00:00' where links_id = '" . (int)$links_id  . "'");
      }
      $ctr++;
			$fnd += $found;
      if ($ctr > $max)       break;
      } // while
	 return $found;
	 }

	if ($action=='cxstat' && $valid_lid) {
			$sql_data_array = array('link_state' => !(int)$_GET['cstat']);
			tep_db_perform('links', $sql_data_array, 'update', "links_id = '" . (int)$linkID . "'");
	$messageStack->add(LM_CHANGE_STATUS.$linkID, 'success');
	 }
	if ($action=='checkall') { URLCheck();
	$messageStack->add(sprintf(LM_CHECK_LINK, $ctr, $fnd), 'success');
	 }
	if ($action=='check') { URLCheck($linkID); if ($_GET['single'] != 1) $place='update';
	$messageStack->add(LM_CHECK_LINK1 . $linkID. ($fnd ? LM_CHECK_LINK2 : LM_CHECK_LINK3), $fnd?'success':'warning'); }
  if($action=='confirm_delete' && $valid_lid){
  tep_db_query("delete from links where links_id='".(int)$linkID."'");

	$messageStack->add(TEXT_DETEL_SUCESS, 'success');
  }
  //
  if($action=='edite' && $valid_lid) $place='Update';
  //
  if($action=='Update' && $valid_lid){
	    list($year,$month,$day) = explode("-",$_POST['submitted']);$day = substr($_POST['submitted'], 8, 2);
	    if (checkdate((int)$month,(int)$day,(int)$year)) $link_date = $_POST['submitted']; else $link_date = date("Y-m-d");
			$category_id = tep_db_prepare_input($_POST['category']);
			if ($_POST['accept_new'] == 'on' && tep_not_null($_POST['new_category'])) {
			$sql_data_array = array('category_name' => tep_db_prepare_input($_POST['new_category']),
																	'sort_order' => (int)tep_db_prepare_input($_POST['sort_order']),
																	'status' => 0,
																	'date_added' => date("Y-m-d H:i:s"));
																	tep_db_perform('links_categories', $sql_data_array);
																	$category_id = tep_db_insert_id();
					$messageStack->add('Added Category ' . $_POST['new_category'], 'success');
					}
			$sql_data_array = array('link_title' => tep_db_prepare_input($_POST['title']),
															'link_url' => tep_db_prepare_input($_POST['url']),
															'link_description' => tep_db_prepare_input($_POST['description']),
															'category' => (int)$category_id,
															'link_date' => $link_date,
															'name' => tep_db_prepare_input($_POST['name']),
															'email' => tep_db_prepare_input($_POST['email']),
															'reciprocal' => tep_db_prepare_input($_POST['reciprocal']),
															'link_state' => (int)$_POST['Link_status'],
															'link_codes' => tep_db_prepare_input($_POST['Link_codes']));
					if ($_POST['accept_new'] == 'on') {
					$update_sql_data = array('new_category' => '');
          $sql_data_array = array_merge($sql_data_array, $update_sql_data);
									}
															tep_db_perform('links', $sql_data_array, 'update', "links_id = '" . (int)$linkID . "'");
        if ($_FILES['links_image']['name']) {
				$links_image = new upload('links_image');
        $links_image->set_destination(DIR_FS_CATALOG_IMAGES . '/links');
        if ($links_image->parse() && $links_image->save()) {
          tep_db_query("update links set links_image = '" . tep_db_input($links_image->filename) . "' where links_id = '" . (int)$linkID . "'");
        }  }
				if ($_POST['delete_image'] == 'on') tep_db_query("update links set links_image = '' where links_id = '" . (int)$linkID . "'");
	$messageStack->add(TEXT_UPDATE_LINKS . ' ' . $_POST['title'], 'success');
  }
  //
  if($action=='add1') $place='Add';
  //
  if($action=='Add'){

  $sql_data_array = array('link_title' => tep_db_prepare_input($_POST['title']),
																	'link_url' => tep_db_prepare_input($_POST['url']),
																	'link_description' => tep_db_prepare_input($_POST['description']),
																	'category' => (int)tep_db_prepare_input($_POST['category']),
																	'link_date' => date("Y-m-d H:i:s"),
																	'name' => tep_db_prepare_input($_POST['name']),
																	'email' => tep_db_prepare_input($_POST['email']),
																	'reciprocal' => tep_db_prepare_input($_POST['reciprocal']),
																	'link_state' => (int)$_POST['Link_status'],
															    'link_codes' => tep_db_prepare_input($_POST['Link_codes']));
																	tep_db_perform('links', $sql_data_array);
																	$links_id = tep_db_insert_id();
     if ($_FILES['links_image']['name']) {
				$links_image = new upload('links_image');
        $links_image->set_destination(DIR_FS_CATALOG_IMAGES . '/links');
        if ($links_image->parse() && $links_image->save()) {
          tep_db_query("update links set links_image = '" . tep_db_input($links_image->filename) . "' where links_id = '" . (int)$links_id . "'");
        }  }
	$messageStack->add(TEXT_ADD_LINKS_SUCCESS, 'success');
  }
	if($action=='update_category' && $valid_cat){
		$sql_data_array = array('category_name' => tep_db_prepare_input($_POST['category_name']),
																	'sort_order' => (int)tep_db_prepare_input($_POST['sort_order']),
																	'status' => (int)tep_db_prepare_input($_POST['status']),
																	'last_modified' => date("Y-m-d H:i:s"));
																	tep_db_perform('links_categories', $sql_data_array, 'update', "category_id = '" . (int)$catID . "'");
	$messageStack->add('Updated Category ' . $_POST['category_name'], 'success');
  }
  if($action=='add_category' && tep_not_null($_POST['category_name'])){
    $sql_data_array = array('category_name' => tep_db_prepare_input($_POST['category_name']),
																	'sort_order' => (int)tep_db_prepare_input($_POST['sort_order']),
																	'status' => (int)tep_db_prepare_input($_POST['status']),
																	'date_added' => date("Y-m-d H:i:s"));
																	tep_db_perform('links_categories', $sql_data_array);

	$messageStack->add('Added Category ' . $_POST['category_name'], 'success');
	if ($_GET['fm_edit'] != '') $place=$_GET['fm_edit'];
  }
	if ($action=='cxcatstat' && $valid_cat) {
			$sql_data_array = array('status' => !(int)$_GET['catstat'],
															'last_modified' => date("Y-m-d H:i:s"));
			tep_db_perform('links_categories', $sql_data_array, 'update', "category_id = '" . (int)$catID . "'");
	$messageStack->add('Changed Category Status for ' .$catID, 'success');
	 }
	 if($action=='confirm_cat_delete' && $valid_cat){
  tep_db_query("delete from links_categories where category_id='".(int)$catID."'");

	$messageStack->add('Deleted Category ' . $catID , 'success');
  }
	//category drop-down
  $category_array = array();
	$category_array[0] = array('id' => '', 'text' => 'None');
	$sort_category_array = array();
	$sort_category_array[999] = array('id' => '999', 'text' => 'Not allocated');
	$category_query = tep_db_query("select category_id, category_name from links_categories order by sort_order, category_name");
	$rows = tep_db_num_rows($category_query);
  while ($category_values = tep_db_fetch_array($category_query)) {
    $category_array[] = array('id' => $category_values['category_id'], 'text' => $category_values['category_name']);
		$categories[$category_values['category_id']] = $category_values['category_name'];
  }
	$sort_category_array = array_merge($category_array, $sort_category_array);
	$sort_category_array[0] = array('id' => '', 'text' => 'All');
	// order drop down
	$order_array = array();$order_array_2 = array();
	for ($i = 1;$i <= $rows;$i++) {
	$order_array[] = array('id' => $i, 'text' => $i);
	}
	$order_array_2 = $order_array;
	$order_array_2[] = array('id' => $rows+1, 'text' => $rows+1);
	$edit_mode = $place != '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>
<script language="javascript" src="includes/general.js"></script>
<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" >
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
		<table width="100%">
		<tr>
		<td class=pageHeading><h3><?php echo L_TITLE ; ?></h3></td>
		</tr>
		<tr>
		<td width="50%"><a href=<?php echo tep_href_link('link_manage.php').'><b>'.MAINLIST?> </b></a> |<a href=<?php echo tep_href_link('link_manage.php','action=add1'.$page.$sort).'><b> '.ADDLINK;?> </b></a> |<a href=<?php echo tep_href_link('link_manage.php','action=checkall'.$page.$sort).'><b> '.'Check All ' . ($sort_category ? ($sort_category == 999 ? 'Unallocated ' :  $categories[$sort_category]) . ' ' : '') . 'Links';?> </b></a></td>
<?php if ($rows && !$edit_mode) { ?><td  class="smallText" align="right" width="100%"><?php echo tep_draw_form('sort', 'link_manage.php', 'action=sel_cat', 'post').'View category: ' . tep_draw_pull_down_menu('sort_category', $sort_category_array, $sort_category, 'onChange="this.form.submit();" rel="nofollow"'); ?><noscript><input  title="View" name="" type="submit" value="Go"></noscript></form></td>
<?php } ?>

		</tr>
		</table>
		<?php
		if(!$edit_mode){
		?>
		<table width="100%"  border="0" cellspacing="0" cellpadding="2" >
		<tr class="dataTableHeadingRow"><td align="center" class="dataTableHeadingContent"><?php echo LM_HEAD1; ?></td><td class="dataTableHeadingContent"align="center"><?php echo LM_HEAD2; ?></td><td class="dataTableHeadingContent"><?php echo LM_HEAD3; ?></td><td align="center" class="dataTableHeadingContent"><?php echo 'Category'; ?></td><td align="center" class="dataTableHeadingContent"><?php echo LM_HEAD4; ?></td><td align="center" class="dataTableHeadingContent"><?php echo LM_HEAD5; ?></td><td class="dataTableHeadingContent" align="center"><?php echo LM_HEAD6; ?></td><td class="dataTableHeadingContent" align="center"><?php echo LM_HEAD8; ?></td><td class="dataTableHeadingContent" align="center"><?php echo LM_HEAD7; ?></td></tr>
 <?php
              $link_sql="select * from links  order by links_id desc";
							if (tep_not_null($sort_category) && $sort_category != 999) $link_sql="select * from links where category = '" . $sort_category . "' order by links_id desc";

			if ($sort_category != 999) $link_split=new splitPageResults($_GET['page'], $links_per_page, $link_sql ,$links_query_numrows);

              $links_query=tep_db_query($link_sql);
              while($links_tree=tep_db_fetch_array($links_query)){
							if ($sort_category == 999 && ($categories[$links_tree['category']])) continue;
			        //echo '<tr ' . ($action == 'delete' && $linkID == $links_tree['links_id'] ? '' : '') . '>';?>
    	<!-- thumb -->
		<tr class="dataTableRow" onMouseOver="this.className='dataTableRowOver';this.style.cursor='hand'" onMouseOut="this.className='dataTableRow'">
			<td width="120" height="90" align="center"><a target="blank" href="<?php echo $links_tree['link_url'];?>"><?php if (!$links_tree['links_image']) {
							echo tep_image('http://open.thumbshots.org/image.pxf?url='.$links_tree['link_url'],$links_tree['link_title']);
							} else {
							echo tep_info_image('links/' . $links_tree['links_image'],$links_tree['link_title'],'120','90').'<br /><b>LOCAL</b>';
							} ?></a></td>
		<!-- pagerank -->
    <td width="30px" align="center"><?php
                 $pr = getpr($links_tree['link_url']);
                 $pr = $pr === false ? -1 : $pr;
					echo ($pr != -1 ? '<div title="Google PageRank: '.$pr.'/10" style="background-image:url(' . DIR_WS_CATALOG.DIR_WS_IMAGES . '/pr/pr'.$pr.'.png); background-position: center center ; background-repeat: no-repeat; layer-background-image:url(' . DIR_WS_CATALOG.DIR_WS_IMAGES . '/pr/pr'.$pr.'.png); " >' : '<div title="No Google PageRank Data">' ) . '<font size="4" color="blue"><b>' . ($pr == -1 ? 'n/a' : $pr) . '</b></font></div>';
				 ?></td>
		<!-- description	 -->
    <td class="infoBoxContent" align="center" width="40%"><?php echo  '<a href="' . $links_tree['link_url'] . '" title= "' .  $links_tree['link_url'] . '" target="blank"><b>' . $links_tree['link_title'] . '</b></a>'; ?></td>
	<!-- <td><?php //echo $links_tree['link_codes']?$links_tree['link_codes']:'none';?></td> -->
	  <!-- Category -->
		<td class="smallText" align="center" width="18%"><?php echo $categories[$links_tree['category']]?$categories[$links_tree['category']]:'None Selected'; ?></td>
	  <!-- link status -->
    <td align="center" <?php echo 'id='.$links_tree['links_id'].'><a href=' . tep_href_link('link_manage.php','action=cxstat&l_id=' . $links_tree['links_id'] . '&cstat=' . $links_tree['link_state'] . $page . $sort . ($sess_id ? '' : '#' . $links_tree['links_id'])) . '>'. tep_image($links_tree['link_state']?DIR_WS_IMAGES . 'tick.gif' : DIR_WS_IMAGES . 'cross.gif' , $links_tree['link_state'] ? 'Enabled, click to change' : 'Disabled, click to change');?></a></td>
		<!-- link found -->
		<td align="center"><a href=<?php echo tep_href_link('link_manage.php','action=check&single=1&l_id=' . $links_tree['links_id'] . $page . $sort . ($sess_id ? '' :  '#' . $links_tree['links_id'])) . '>' . tep_image($links_tree['link_found']?DIR_WS_IMAGES . 'tick.gif' : DIR_WS_IMAGES . 'cross.gif' , $links_tree['link_found'] ? 'Live link, click to re-check' : 'Dead link, click to re-check');?></a></td>
		<!-- last_checked -->
   	<td class="smallText" align="center" width="12%"><?php echo ($links_tree['date_last_checked'] != '0000-00-00 00:00:00' ? date("d-m-Y H:i:s", strtotime($links_tree['date_last_checked'])) : LM_NOT_FOUND); ?> <br /><br /><a href="<?php echo $links_tree['reciprocal'] ?>" title= "<?php echo $links_tree['reciprocal'] ?>" target="blank"><?php echo 'Reciprocal';?></a></td>
		<!-- submitted -->
		<td class="smallText" align="center"><?php echo tep_date_short($links_tree['link_date']) ?></td>
		<!-- edit/delete -->
    <td align="center" width="90"><table border="0" cellspacing="0" cellpadding="0">
      <tr>
<?php if ($action == 'delete' && $linkID == $links_tree['links_id']) { ?>
			<td  >
				<a href="<?php echo tep_href_link('link_manage.php','action=confirm_delete'.$page.'&l_id='.$links_tree['links_id'].$sort)?>"><img align="left" vspace="10px" src="<?php echo DIR_WS_CATALOG.DIR_WS_IMAGES . '/pr/delete.gif' ?>" border="0" alt="delete"><?php echo LM_DEL_CONFIRM; ?></a>
<?php } else { ?>
			<td width="100" align="center"><a href="<?php echo tep_href_link('link_manage.php','action=edite'.$page.'&l_id='.$links_tree['links_id'].$sort)?>"><img src="<?php echo DIR_WS_CATALOG.DIR_WS_IMAGES . '/pr/edit.gif' ?>" border="0" alt="edit" vspace="4px"><br /><?php echo LM_EDIT; ?></a></td>
        <td width="100" align="center">
				 <a href="<?php echo tep_href_link('link_manage.php','action=delete'.$page.'&l_id='.$links_tree['links_id'] . $sort . ($sess_id ? '' : '#' . $links_tree['links_id']))?>"><img src="<?php echo DIR_WS_CATALOG.DIR_WS_IMAGES . '/pr/delete.gif' ?>" border="0" alt="delete" vspace="4px"><br /><?php echo LM_DELETE; ?></a>
<?php } ?>
</td>
      </tr>
    </table></td>
 </tr><tr><td colspan="9" align="center" class="smallText"><?php echo ($action=='check' && $linkID == $links_tree['links_id'] ? '<b>'.LM_CHECK_LINK1 . $linkID . ($fnd ? LM_CHECK_LINK2 : LM_CHECK_LINK3) . '</b>' : tep_draw_separator('pixel_trans.png', '1', '10')); ?></td>
</tr><?php }
		if ($sort_category != 999) { ?>
   <tr>
   <td class="smallText" valign="top" colspan="6"><?php echo $link_split->display_count($links_query_numrows, $links_per_page, $_GET['page'], TEXT_DISPLAY_LINKES); ?></td>
               <td class="smallText"  colspan="3" align="right"><?php echo $link_split->display_links($links_query_numrows, $links_per_page, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('action','page', 'info', 'x', 'y'))); ?></td>
	</tr>
	<?php } ?>
	<tr><td colspan="9"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td></tr>
<?php if ($action=='checkall') { ?>
<tr><td colspan="9"><?php echo '<b>' . sprintf(LM_CHECK_LINK, $ctr, $fnd) . '</b>'; ?></td></tr>
<?php } ?>
<tr><td width="70%" colspan="9" align="right" class="smallText"><?php echo LM_NOFOLLOW_TEXT; ?></td></tr>
</table>

<!-- CATEGORIES -->
<table summary="" border="0">
<tr><td class=pageHeading><br /><h3>Link Categories</h3></td></tr>
<tr><td colspan="1"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td></tr>
</table>
<table width="700px"  border="0" cellspacing="0" cellpadding="2" >
<tr class="dataTableHeadingRow"><td class="dataTableHeadingContent"><?php echo MLTITLE; ?></td><td align="center" class="dataTableHeadingContent">Order</td><td align="center" class="dataTableHeadingContent"><?php echo LM_HEAD4; ?></td><td class="dataTableHeadingContent" align="center"><?php echo LM_HEAD8; ?></td><td class="dataTableHeadingContent" align="center">Modified</td><td class="dataTableHeadingContent" align="center"><?php echo LM_HEAD7; ?></td></tr>
<?php $category_query = tep_db_query("select * from links_categories order by sort_order, category_name");
       while ($category = tep_db_fetch_array($category_query)) {
			  if ($action == 'cat_edit' && $catID == $category['category_id']) {
				 echo tep_draw_form('info', 'link_manage.php', 'action=update_category&cat_id='.$catID.$page.$sort)?>
	 <!-- edit category -->

	<tr class="dataTableRow" onMouseOver="this.className='dataTableRowOver';this.style.cursor='hand'" onMouseOut="this.className='dataTableRow'">
<!-- name -->
<td class="dataTableContent" align="left" width="30%"><?php echo tep_draw_input_field('category_name', $category['category_name'], 'maxLength=32 size=32'); ?></td>
<!-- sort_order -->
<td align="center" width="8%" class="dataTableContent" ><?php echo tep_draw_pull_down_menu('sort_order', $order_array, $category['sort_order']); ?></td>
<!--  status -->
    <td align="center" <?php echo 'id=c'.$category['category_id']; ?> width="24%">
		<label> <?php echo tep_draw_radio_field('status', '1', $category['status']) . tep_image(DIR_WS_IMAGES . 'tick.gif', 'Click to enable'); ?></label>
  <label><?php echo tep_draw_radio_field('status', '0', !$category['status']) . tep_image(DIR_WS_IMAGES . 'cross.gif', 'Click to disable'); ?></label></td>
<td align="center" width="20%" class="dataTableContent"><?php echo tep_date_short($category['date_added']); ?></td>
<td align="center" width="20%" class="dataTableContent"><?php echo $category['last_modified'] ? date("d-m-Y H:i:s", strtotime($category['last_modified'])) : 'Not Yet'; ?></td><td align="center" class="dataTableContent" >
	<input  title="Update Category" name="" type="submit" value="<?php echo 'Update';?>">
	</td>
		</tr></form>

<?php } else { ?>

<tr <?php echo ($action == 'cat_delete' && $catID == $category['category_id'] ? '' : '') ?> class="dataTableRow" onMouseOver="this.className='dataTableRowOver';this.style.cursor='hand'" onMouseOut="this.className='dataTableRow'">
<!-- name -->
<td class="dataTableContent" align="left" width="30%"><?php echo $category['category_name']; ?></td>
<!-- sort_order -->
<td align="center" width="8%" class="dataTableContent"  ><?php echo $category['sort_order']; ?></td>
<!--  status -->
    <td   class="dataTableContent" align="center" <?php echo 'id=c'.$category['category_id'].'><a href=' . tep_href_link('link_manage.php','action=cxcatstat&cat_id=' . $category['category_id'] . '&catstat=' . $category['status'] . $page . $sort . ($sess_id ? '' :  '#c' . $category['category_id'])) . '>'. tep_image($category['status']?DIR_WS_IMAGES . 'tick.gif' : DIR_WS_IMAGES . 'cross.gif' , $category['status'] ? 'Enabled, click to change' : 'Disabled, click to change');?></a></td>
<td  align="center" class="dataTableContent" width="20%"><?php echo tep_date_short($category['date_added']); ?></td>
<td align="center" class="dataTableContent" width="20%"><?php echo $category['last_modified'] ? date("d-m-Y H:i:s", strtotime($category['last_modified'])) : 'Not Yet'; ?></td>
<!-- edit -->
<td align="center" width="90"><table border="0" cellspacing="0" cellpadding="0">
      <tr>
<?php if ($action == 'cat_delete' && $catID == $category['category_id']) { ?>
			<td   class="dataTableContent" >
				<a href="<?php echo tep_href_link('link_manage.php','action=confirm_cat_delete'.$page.'&cat_id='.$category['category_id'].$sort)?>"><img align="left" vspace="10px" src="<?php echo DIR_WS_CATALOG.DIR_WS_IMAGES . '/pr/delete.gif' ?>" border="0" alt="delete"><?php echo LM_DEL_CONFIRM; ?></a>
<?php } else { ?>
			<td width="100" align="center"  class="dataTableContent"><a href="<?php echo tep_href_link('link_manage.php','action=cat_edit'.$page.'&cat_id='.$category['category_id'] . $sort . ($sess_id ? '' : '#c' . $category['category_id']))?>"><img src="<?php echo DIR_WS_CATALOG.DIR_WS_IMAGES . '/pr/edit.gif' ?>" border="0" alt="edit" vspace="4px" align="top"><br /><?php echo LM_EDIT; ?></a></td>
        <td width="100" align="center"  class="dataTableContent">
				 <a href="<?php echo tep_href_link('link_manage.php','action=cat_delete'.$page.'&cat_id='.$category['category_id'] . $sort . ($sess_id ? '' : '#c' . $category['category_id']))?>"><img src="<?php echo DIR_WS_CATALOG.DIR_WS_IMAGES . '/pr/delete.gif' ?>" border="0" alt="delete" hspace="0px" vspace="0px" align="middle"><br /><?php echo LM_DELETE; ?></a>
<?php } ?>
</td>
      </tr>
    </table></td>
		</tr>
<?php } } // while ?>
</table>
<table summary=""><tr><td colspan="1"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td></tr></table>
<table width="680px">
		<tr><td width="100%" align="center">
<table border="0" cellspacing="2" cellpadding="2" class="dataTableRow">
  <tr><?php echo tep_draw_form('info', 'link_manage.php', 'action=add_category'.$page . $sort)?>

    <td><?php echo tep_draw_input_field('category_name', '', 'maxLength=32 size=32', '', '', false) ?></td>
      <td><?php echo tep_draw_pull_down_menu('sort_order', $order_array_2, $rows+1); ?></td>
 <td><label> <?php echo tep_draw_radio_field('status', '1', 1) . tep_image(DIR_WS_IMAGES . 'tick.gif', 'Click to enable'); ?></label></td><td>
  <label><?php echo tep_draw_radio_field('status', '0') . tep_image(DIR_WS_IMAGES . 'cross.gif', 'Click to disable'); ?></label></td>
		<td></td><td>
	<input  title="Add New Category" name="" type="submit" value="<?php echo 'Add New Category';?>">
	</td></form></tr>
</table>
<table summary=""><tr><td colspan="1"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td></tr></table>
</td></tr><tr><td width="100%" align="center">
<table border="0" cellspacing="2" cellpadding="2" class="dataTableRow" style="font-size: 12px;">
  <tr><?php echo tep_draw_form('info', 'link_manage.php', 'action=check_rank'.$page . $sort . ($sess_id ? '' : '#ranking'))?>
   <td width="40" align="center" id="ranking"><?php   //show the pagerank
	 if ($action == 'check_rank' && tep_not_null($_POST['site_url'])) {
                 $pr = getpr($_POST['site_url']);
                 $pr = $pr === false ? -1 : $pr;
							echo ($pr != -1 ? '<div title="Google PageRank: '.$pr.'/10" style="background-image:url(' . DIR_WS_CATALOG.DIR_WS_IMAGES . '/pr/pr'.$pr.'.png); background-position: center center ; background-repeat: no-repeat; layer-background-image:url(' . DIR_WS_CATALOG.DIR_WS_IMAGES . '/pr/pr'.$pr.'.png); " >' : '<div title="No Google PageRank Data">' ) . '<font size="5" color="blue"><b>' . ($pr == -1 ? 'n/a' : $pr) . '</b></font></div>';
				} else { echo '<font size="5" color="blue"><b>?</b></font>'; }  ?></td>
    <td ><?php echo tep_draw_input_field('site_url', $_POST['site_url'], 'maxLength=60 size=50', '', '', false) ?></td>
      <td>
	<input  title="Check Website Google PageRank" name="" type="submit" value="<?php echo 'Check Website Google PageRank';?>">
	</td></form></tr>
</table>
</td></tr></table>
<table width="700px"  border="0" cellspacing="0" cellpadding="0">
<tr><td ><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td></tr>
		<tr><td class="main" align="center" width="100%"><?php // echo '<b>All links within a disabled category will be hidden.</b>'; ?></td></tr></table>
</td>
<?php  } else {
if($valid_lid){

$e_info_query=tep_db_query("select * from links where links_id='".(int)$linkID."'");
$e_info=tep_db_fetch_array($e_info_query);
$aa='&l_id='.$e_info['links_id'];
}
?>

<table border="0" cellspacing="2" cellpadding="2" class="dataTableRow" style="font-size: 12px;">
  <tr><?php echo tep_draw_form('info', 'link_manage.php', 'action='.$place.$aa.$page.$sort, 'post','enctype="multipart/form-data"')?>
    <td ><?php echo MLTITLE;?></td>
    <td><?php echo tep_draw_input_field('title', $e_info['link_title']?$e_info['link_title']:'', 'maxLength=80 size=70') ?></td>
  </tr>
	<tr><td>Category</td><td><table width="100%" summary="" cellspacing="0" cellpadding="0">
<tr><td class="main"><?php
    echo tep_draw_pull_down_menu('category', $category_array, $e_info['category']);
?>  </td><td class="dataTableRow" align="right"><?php echo $e_info['new_category'] ? ' Suggested Category: ' .  $e_info['new_category'] : ''; ?></td><td width="70" align="center"><?php echo $e_info['new_category'] ? tep_draw_checkbox_field('accept_new') . ' Use' : ''; ?></td></tr>
</table></td></tr>
  <tr>
    <td><a class="main" href="<?php echo $e_info['link_url'] ?>" title= "<?php echo $e_info['link_url'] ?>" target="blank"><?php echo MLURL . '</a>' . tep_draw_hidden_field('sort_order',$rows+1);?></td>
    <td><?php echo tep_draw_input_field('url', $e_info['link_url']?$e_info['link_url']:'', 'maxLength=80 size=70') ?></td>
  </tr>
	<tr>
    <td class="main" <?php if ($e_info['links_image']) { ?> style="background-image:url(http://open.thumbshots.org/image.pxf?url=<?php  echo   $e_info['link_url']; ?>); background-position: center center ; background-repeat: no-repeat; layer-background-image:url(http://open.thumbshots.org/image.pxf?url=<?php  echo   $e_info['link_url'] . '); "'; } echo  ' >' . ($e_info['links_image'] ? 'Thumbshot image behind, <br /><br /><br />Local ' : 'Thumbshots ') . 'Image:';?></td>
 	  <td ><table summary=""><tr><td align="left"><a target="blank" href="<?php echo $e_info['link_url'];?>">
		<?php if (!$e_info['links_image']) {
							echo tep_image('http://open.thumbshots.org/image.pxf?url='.$e_info['link_url'],$e_info['link_title']);
							} else {
							echo tep_info_image('links/' . $e_info['links_image'],$e_info['link_title'],'120','90');
							 } ?>
							</a></td><td class="smallText" align="right" width="90%"><?php echo 'Enter local image (120x90)<br /><br />' . tep_draw_input_field('links_image', '', 'size="50"', '', 'file') . '<br /><br />'; if ($e_info['links_image']) echo  tep_draw_checkbox_field('delete_image') . 'Use Thumbshots in place of current'; else echo 'Leave blank to use Thumbshots image' ;?></td></tr>
</table></td>
  </tr>
  <tr>
    <td class="main" ><?php echo MLNAME . tep_draw_hidden_field('new_category',$e_info['new_category']);?></td>
    <td><?php echo tep_draw_input_field('name', $e_info['name']?$e_info['name']:'', 'maxLength=80 size=70') ?></td>
  </tr>
  <tr>
    <td><?php echo MLEMAIL;?></td>
    <td><?php echo tep_draw_input_field('email', $e_info['email']?$e_info['email']:'', 'maxLength=80 size=70') ?></td>
  </tr>
	<tr>
    <td><?php echo LM_HEAD8;?></td>
    <td><?php echo tep_date_long($e_info['link_date']) . tep_draw_hidden_field('submitted',$e_info['link_date']); ?></td>
  </tr>
  <tr>
    <td ><a class="main" href="<?php echo $e_info['reciprocal'] ?>" title= "<?php echo $e_info['reciprocal'] ?>" target="blank"><?php echo MLRECI;?></a></td>
    <td <?php echo ($action != 'add1' ? (!$e_info['link_found'] ? '' : '') : ''); ?>><?php echo tep_draw_input_field('reciprocal', $e_info['reciprocal']?$e_info['reciprocal']:'http://', 'maxLength=80 size=70') ?></td>
  </tr>
  <tr>
    <td><?php echo MLDESCRIPTION;?></td>
    <td><?php echo tep_draw_textarea_field('description','Physical',80,5,$e_info['link_description']?$e_info['link_description']:'');?></td>
  </tr>
  <tr>
    <td><?php echo MLCODE;?></td>
    <td><?php echo tep_draw_textarea_field('Link_codes','Physical',80,5,$e_info['link_codes']?$e_info['link_codes']:'');?></td>
  </tr>
	 <tr>
    <td><?php echo MLRECIP;?></td>
		<td><table summary="">
		<tr>
    <td align="center"><?php echo tep_image($e_info['link_found'] ? DIR_WS_IMAGES . 'tick.gif' : DIR_WS_IMAGES . 'cross.gif' , $e_info['link_found'] ? 'Reciprocal link was found' : 'Reciprocal link not found' );?></td>
		<td class="main"><?php echo LM_HEAD6 . ':'; ?> </td>
		<td class="main" align="center"><?php echo ($e_info['date_last_checked'] != '0000-00-00 00:00:00' ? date("d-m-Y H:i:s", strtotime($e_info['date_last_checked'])) : LM_NOT_FOUND); ?></td></tr></table>
   </td>
   </tr>
	 <tr>
    <td><?php echo MLSTATUE;?></td>
    <td>

        <label>
        <?php echo tep_draw_radio_field('Link_status', '1', $e_info['link_state']) . tep_image(DIR_WS_IMAGES . 'tick.gif', 'Click to enable &amp; show link'); ?>
  </label>
        <br>
        <label>
        <?php echo tep_draw_radio_field('Link_status', '0', !$e_info['link_state']) . tep_image(DIR_WS_IMAGES . 'cross.gif', 'Click to disable &amp; hide link'); ?>
    </label>
        <br>

    </td>
	<tr><td align="center">
	<input  title="Save Changes" name="" type="submit" value="<?php echo $place;?>">
	</td><td><?php
	$back_url = 'link_manage.php';
	$back_url_params = 'page=' . $_GET['page'] . $sort;
	echo '<a class="button" href="' . tep_href_link($back_url, $back_url_params) . '">' .  IMAGE_CANCEL. '</a>' . '<a class="button" href="' . tep_href_link($back_url, $back_url_params . '&action=delete'.$aa.($sess_id ? '' : '#' . $e_info['links_id'])) . '">' .  IMAGE_DELETE . '</a>'; ?></td></tr>
  </tr>
	<tr><td colspan="7" align="right" class="smallText"><?php echo LM_NOFOLLOW_TEXT; ?></td></tr>
  <tr>
    <td colspan="2"></td>

 </form> </tr>
  <?php if ($action != 'add1') {
	 echo '<tr>' . tep_draw_form('info', 'link_manage.php', 'action=check'.$aa.$page . $sort)?>
	<td width="100px">
	<input title="Check for reciprocal link" name="" type="submit" value="<?php echo LM_CHECK; ?>">
	</td><td class="main"><?php echo LM_WARNING; ?></td>
	 </form> </tr>
	 <?php } ?>
</table>



<?php }?>
</td>
   </tr>
	 <tr><td ><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td></tr>

    </table>

<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
