<?php
/*
  $Id: links.php v2.0 2008-11-14 00:52:16Z hpdl $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/links.php');
  require(DIR_WS_FUNCTIONS.'pagerank.php');
	$action = (isset($_GET['action']) ? $_GET['action'] : '');
	$result_page = 10; //results per page
	//category drop-down
  $category_array = array();
	$category_array[0] = array('id' => '0', 'text' => 'All');
	$category_query = tep_db_query("select category_id, category_name from links_categories where status = 1 order by sort_order, category_name");
	$rows = tep_db_num_rows($category_query);
  while ($category_values = tep_db_fetch_array($category_query)) {
    $category_array[] = array('id' => $category_values['category_id'], 'text' => $category_values['category_name']);
		$categories[$category_values['category_id']] = $category_values['category_name'];
  }
  $breadcrumb->add('links', tep_href_link('links.php', '', 'SSL'));

require(DIR_WS_INCLUDES . 'header.php');
require(DIR_WS_INCLUDES . 'column_left.php'); ?>
       

    <td width="100%" align="center" valign="top"><!-- body_text //-->
      <table width="100%" border="0" cellspacing="0" cellpadding="0" >
        <tr>
          <td><span class="links">
            <h1>Links</h1>
            <?php $link_sql="select link_title,link_url,link_description,link_codes,link_found, links_image from links where link_state=1 order by links_id";

	if ($action = 'catsel' && $_GET['category'] != 0) $link_sql="select link_title,link_url,link_description,link_codes,link_found,links_image from links where link_state=1 and category = '" . (int)$_GET['category'] . "' order by links_id"; ?>
            <?php if ($rows) { ?>
            <?php echo tep_draw_form('cat', tep_href_link('links.php', 'action=catsel'), 'get').'Links Category: ' . tep_draw_pull_down_menu('category', $category_array, $_GET['category'], 'onChange="this.form.submit();" rel="link"');
?>
            <noscript>
            <input  class="button" title="View" name="" type="submit" value="Go">
            </noscript>
            </form>
            <?php } ?>
            <ul>
            <?php  		  $link_split=new splitPageResults($link_sql,$result_page,'links_id','page');
              $links_query=tep_db_query($link_split->sql_query);
              while($links_tree=tep_db_fetch_array($links_query)){
			   ?>
            <li><?php echo  '<a ' . ($links_tree['link_found'] ? '' : 'rel="link" ') . 'href="'.$links_tree['link_url'] . '">';
			 if (!$links_tree['links_image']) {
							//echo tep_image('http://open.thumbshots.org/image.pxf?url='.$links_tree['link_url'],$links_tree['link_title']);
							echo '<img  style="float: left; padding-right: 5px;" title="'. $links_tree['link_title'] .'" alt="'. $links_tree['link_title'] .'" src="http://open.thumbshots.org/image.pxf?url='.$links_tree['link_url'].'" class="imageborder"/>';

							} else {
							//echo tep_image(DIR_WS_IMAGES . 'links/' . $links_tree['links_image'],$links_tree['link_title'],'120','90');
							echo '<img   style="float: left; padding-right: 5px;" width="120" height="90" title="'. $links_tree['link_title'] .'" alt="'. $links_tree['link_title'] .'" src="'.DIR_WS_IMAGES . 'links/' . $links_tree['links_image'].'" class="imageborder"/>';
							} ?> </a>
              <!-- <td align="center"><?php   //show the pagerank
                 $pr = getpr($links_tree['link_url']);
                 $pr = empty($pr) ? 0 : $pr;
				// echo  tep_image(DIR_WS_IMAGES .'pr/pr'.$pr.'.gif','Google PageRank: '.$pr.'/10',40,5); ?></td> -->
              <?php echo  '<a ' . ($links_tree['link_found'] ? '' : 'rel="link" ') . 'href='.$links_tree['link_url'].' target="_BLANK"><b>'.$links_tree['link_title'].'</b></a><br>'.$links_tree['link_description']; ?> </li>
            <div class="clear"></div>
            <br>
            <!-- <td align="center"><?php //echo $links_tree['link_codes']?$links_tree['link_codes']:'';?></td> -->
            <?php }
	if ($link_split->number_of_rows > $result_page){  ?>
            <ul>
              <?php  echo $link_split->display_count(TEXT_DISPLAY_NUMBER_OF_LINKS).''.TEXT_RESULT_PAGE . ' ' . $link_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?>
            </ul>
            <?php } ?>
            <div class="clear"></div>
            <div class="exchange_links"><br>
              <br>
              <br>
              <center>
                <?php echo  tep_draw_form(add_link,tep_href_link('links_submit.php',tep_get_all_get_params()));?>
                <INPUT class="button" type=submit value="<?php echo 'Exchange Links With Us';?>">
                </FORM>
              </center>
            </div></td>
        </tr>
      </table>
      <!-- body_text_eof //-->
    </td>
    <!-- body_text_eof //-->


        <?php require(DIR_WS_INCLUDES . 'column_right.php');
 require(DIR_WS_INCLUDES . 'footer.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
