<?php

/*

  $Id: SEO_Assistant.php,v 1.2 2004/08/07 22:50:52 hpdl Exp $

  SEO Originally Created by: Jack York

  CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA



  GNU General Public License Compatible

*/



  require('includes/application_top.php');

  set_time_limit(360);



  $maxEntries = '10';

  $google_query = tep_db_query("select search_url, search_term, rank, sites_searched, date from " . TABLE_SEO_GOOGLE ) or die("Query failed");;

  $google = tep_db_fetch_array($google_query);



  $searchurl = tep_db_prepare_input($_POST['search_url_google']);

 	if (empty($searchurl)) {

	  $firstpass = true;

    $searchurl = $google['search_url'];

	}

	else

	  $firstpass = false;



  $searchquery = tep_db_prepare_input($_POST['search_term_google']);

  if (empty($searchquery))

    $searchquery = $google['search_term'];



  $searchtotal = tep_db_prepare_input($_POST['search_total_google']);

  if (empty($searchtotal))

    $searchtotal = $google['sites_searched'];



  $showlinks = tep_db_prepare_input($_POST['show_links']);

  $showlinks = (empty($showlinks)) ? '' : '1';



  $showhistory = tep_db_prepare_input($_POST['show_history']);

  $showhistory = (empty($showhistory)) ? '' : '1';



	$yahoo_query = tep_db_query("select search_url, search_term, rank, sites_searched, date from " . TABLE_SEO_YAHOO ) or die("Query failed");;

  $yahoo = tep_db_fetch_array($yahoo_query);



	$action_google = (isset($_POST['search_url_google']) ? $_POST['search_url_google'] : '');

  $action_rank = (isset($_POST['rank_url']) ? $_POST['rank_url'] : '');

  $action_linkpop = (isset($_POST['linkpop_url']) ? $_POST['linkpop_url'] : '');

  $action_kwdensity = (isset($_POST['density_url']) ? $_POST['density_url'] : '');

  $action_check_links = (isset($_POST['check_page']) ? $_POST['check_page'] : '');



	if (tep_not_null($action_google)) {

		$a = explode ("http://", $searchurl );

	  if (empty($a[0]))

     $searchurl = $a[1];



    require(DIR_WS_MODULES . 'seo_google_position.php');

    require(DIR_WS_MODULES . 'seo_yahoo_position.php');

	}	elseif (tep_not_null($action_rank))	{

	  require(DIR_WS_FUNCTIONS . FILENAME_SEO_ASSISTANT);



	  $rank_url = tep_db_prepare_input($_POST['rank_url']);

 	  if (! empty($rank_url)) {

	    if (! ($pageRank = getPR($rank_url))) {

		   $error = 'Failed to read url: '.$rank_url;

	     $messageStack->add($error);

	    }

	    $prRating = array("Very poor","Poor","Below average","Average","Above Average","Good","Good","Very Good","Very Good","Excellent");

	 	}

	  else

	    $pageRank ='';

	}	elseif (tep_not_null($action_linkpop)) {

	 require(DIR_WS_FUNCTIONS . 'seo_link_popularity.php');

	 $link_1_url = tep_db_prepare_input($_POST['linkpop_url']);

   $link_2_url = tep_db_prepare_input($_POST['linkpop_2_url']);



   if (empty($link_2_url)) {

	  $link_url = $link_1_url;

		$show_second_link = false;

	 } else {

 	  $link_url = $link_2_url;

		$show_second_link = true;

	 }



	 $total = 0;

	 $results = array();

   if ( ! ($results = get_link_popularity($link_url))) {

	  $error = 'Failed to read url: '.$rank_url;

	  $messageStack->add($error);

	 }



	 if (! empty($link_2_url)) {

	 	 $results_2 = $results;

	   $total_2 = $total;

	   reset($results);

	   $total = 0;

	   $link_url = $link_1_url;



     if ( ! ($results = get_link_popularity($link_url))) {

	    $error = 'Failed to read url: '.$rank_url;

	    $messageStack->add($error);

	   }

	 }

	}

	elseif (tep_not_null($action_kwdensity)) {

	 $density_url = tep_db_prepare_input($_POST['density_url']);

	 $use_meta_tags = tep_db_prepare_input($_POST['use_meta_tags']);

   $use_partial_total = tep_db_prepare_input($_POST['use_partial_total']);

	 require(DIR_WS_FUNCTIONS . 'seo_density.php');

	 $ttl_words = 0;

	 if (! empty($density_url)) {

	   if (! ($dens = kda($density_url, $ttl_words, $use_meta_tags, $use_partial_total))) {

	     $error = 'Failed to read url: '.$density_url;

	     $messageStack->add($error);

	   }

	 }

	}

  elseif (tep_not_null($action_check_links)) {

   require(DIR_WS_FUNCTIONS . FILENAME_SEO_ASSISTANT);

   $badLinks = array();

   $idx = 0;

   $totalLinks = 0;



   $url = tep_db_prepare_input($_POST['check_page']);

   if (FALSE === strpos($url, 'http://'))

      $link = 'http://'.$url;

   CheckLinks($link, $idx);



   /*

   $files = ListFiles();

   for($i=0; $i<count($files); $i++)

   {

      if (FALSE === strpos($files[$i], '.php')  || FALSE !== strpos($files[$i], 'search') ||

          FALSE !== strpos($files[$i], 'login') || FALSE !== strpos($files[$i], 'checkout_') )

         continue;

   //   echo $files[$i].'<br>';

    $link = $url . $files[$i];



    break;

   }

   */

  }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo TITLE; ?></title>

<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />





<style type="text/css">

td.seoHead {color: sienna; font-size: 24px; font-weight: bold; }

td.seo_subHead {color: sienna; font-size: 14px; }

</style>

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

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

    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

     <tr>

  		 <!-- BEGIN GOOGLE CODE -->

		   <tr>

       <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td class="seoHead"><?php echo HEADING_TITLE_SEARCH; ?></td>

          </tr>

          <tr>

            <td class="seo_subHead"><?php echo TEXT_POSITION; ?></td>

          </tr>

				  <tr>

					 <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bodytext">

            <tr>

             <td>&nbsp;</td>

             <td align="right"> <?php echo tep_draw_form('google', FILENAME_SEO_ASSISTANT, tep_get_all_get_params(array('action')) . 'action=' . $form_action, 'post' ); ?></td>

	          </tr>

            <tr>

			       <td><?php echo tep_draw_separator('pixel_trans.png', '10', '1'); ?></td>

            </tr>

			      <tr class="infoBoxContents">

             <td><table border="0" cellspacing="2" cellpadding="2">

				      <tr>

			         <td><p>Enter total searches: </p></td>

               <td><?php  echo tep_draw_input_field('search_total_google', tep_not_null($searchtotal) ? $searchtotal : '100', 'maxlength="255"', false); ?> </td>

              </tr>

			        <tr>

		           <td><p>Enter search term: </p></td>

               <td><?php  echo tep_draw_input_field('search_term_google', tep_not_null($searchquery) ? $searchquery : 'search word', 'maxlength="255"', false); ?> </td>

              </tr>

              <tr>

				       <td>Enter URL to search for: </td>

               <td><?php   echo tep_draw_input_field('search_url_google', tep_not_null($searchurl) ? $searchurl : 'http://', 'maxlength="255", size="40"',   false); ?> </td>

    	        </tr>

             </table></td>

            </tr>

			      <tr>

			       <td><?php echo tep_draw_separator('pixel_trans.png', '10', '10'); ?></td>

            </tr>

				    <tr class="infoBoxContents">

             <td><table border="0" cellspacing="2" cellpadding="2">

              <tr>

			  		   <td class="main">Show results: </td>

               <td ><?php echo tep_draw_checkbox_field('show_links', '', false, ''); ?> </td>

	 					   <td>&nbsp;</td>

							 <td class="main">Show History: </td>

               <td ><?php echo tep_draw_checkbox_field('show_history', '', false, ''); ?> </td>

	 					   <td>&nbsp;</td>

						   <td ><?php echo (tep_image_submit('button_search.png', IMAGE_SEARCH) ); ?></td>

              </tr>

             </table></td>

				     </tr>

						 <?php if (tep_not_null($action_google)) { ?>

						 <tr>

			       <td><?php echo tep_draw_separator('pixel_trans.png', '10', '10'); ?></td>

            </tr>

						<tr>

            <td class="pageHeading"><?php echo HEADING_TITLE_GOOGLE; ?></td>

            </tr>

						<tr>

             <td colspan="3"><?php print($result_google);?></td>

            </tr>

						<tr>

				     <td>&nbsp;</td>

		        </tr>

						<?php if ($found_google && $show_history && mysql_num_rows($google_prev_query)) {	?>

						<tr>

						 <td><table border="1" cellpadding="3" width="100%">

               <tr>

                <th class="smallText" align="center" width="20%"><?php echo "DATE"; ?></th>

          	    <th class="smallText" align="center" width="30%"><?php echo "URL"; ?></th>

                <th class="smallText" align="center" width="5%"><?php echo "RANK"; ?></th>

          		  <th class="smallText" align="center" width="45%"><?php echo "WORD(S)"; ?></th>

               </tr>

						  </table></td>

						</tr>

						<?php while ($google = tep_db_fetch_array($google_prev_query)) { ?>

	   				 <tr>

						  <td><table border="1" cellpadding="3" width="100%">

			         <tr>

				       <td class="smallText" align="center" width="20%"><?php echo $google['date']; ?></td>

			          <td class="smallText" align="left" width="30%"><?php echo $google['search_url']; ?></td>

			          <td class="smallText" align="center" width="5%"><?php echo $google['rank']; ?></td>

			          <td class="smallText" align="left" width="45%"><?php echo $google['search_term']; ?></td>

		           </tr>

							</table></td>

						 </tr>

			       <?php  } }

			       if ($showlinks) {

    			    for ($i = 0; $i<$searchtotal; $i++) {

						   $j = $i + 1;

					      if (empty($siteresults_google[$i]))

						     break;

			       ?>

			       <tr>

						  <td><table>

               <tr>

	        <td class="main"><?php echo $j. ' ' . '<a href="' . $siteresults_google[$i] . '" target="_blank">' . substr($siteresults_google[$i],(strpos($siteresults_google[$i],'/')+2)) . '</a>'; ?></td>

	       </tr>

              </table></td>

             </tr>

             <?php } } } ?>

						</form>

					 </table></td>

					</tr>



					<!-- BEGIN YAHOO CODE -->

					 <tr>

			       <td width="10"><?php echo tep_draw_separator('pixel_trans.png', '10', '10'); ?></td>

            </tr>

            </tr>

 						<?php if (tep_not_null($action_google)) {	?>

						 <tr>

			       <td><?php echo tep_draw_separator('pixel_trans.png', '10', '10'); ?></td>

            </tr>

						<tr>

            <td class="pageHeading"><?php echo HEADING_TITLE_YAHOO; ?></td>

            </tr>

						<tr>

             <td colspan="3"><?php print($result_yahoo);?></td>

            </tr>

						<tr>

				     <td>&nbsp;</td>

		        </tr>

						<?php if ($found_yahoo && $show_history && mysql_num_rows($yahoo_prev_query)) {	?>

						<tr>

						 <td><table border="1" cellpadding="3" width="100%">

              <tr>

               <th class="smallText" align="center" width="20%"><?php echo "DATE"; ?></th>

          	   <th class="smallText" align="center" width="30%"><?php echo "URL"; ?></th>

               <th class="smallText" align="center" width="5%"><?php echo "RANK"; ?></th>

          	   <th class="smallText" align="center" width="45%"><?php echo "WORD(S)"; ?></th>

              </tr>

						 </table></td>

						</tr>

						<?php while ($yahoo = tep_db_fetch_array($yahoo_prev_query)) { ?>

	   				<tr>

						 <td><table border="1" cellpadding="3" width="100%">

			        <tr>

				       <td class="smallText" align="center" width="20%"><?php echo $yahoo['date']; ?></td>

			         <td class="smallText" align="left" width="30%"><?php echo $yahoo['search_url']; ?></td>

			         <td class="smallText" align="center" width="5%"><?php echo $yahoo['rank']; ?></td>

			         <td class="smallText" align="left" width="45%"><?php echo $yahoo['search_term']; ?></td>

		          </tr>

						 </table></td>

						</tr>

			      <?php  } }

			      if ($showlinks) {

    			   for ($i = 0; $i<$searchtotal; $i++) {

						  $j = $i + 1;

				      if (empty($siteresults_yahoo[$i]))

						    break;

			      ?>

			      <tr>

					   <td><table>

              <tr>

	       <td class="main"><?php echo $j. ' ' .'<a href="' . $siteresults_yahoo[$i] . '" target="_blank">' . substr($siteresults_yahoo[$i],(strpos($siteresults_yahoo[$i],'/')+2)) . '</a>'; ?></td>               </tr>

             </table></td>

            </tr>

            <?php } } } ?>



        <!-- BEGIN RANK CODE -->

				<tr>

			   <td><?php echo tep_draw_separator('pixel_trans.png', '10', '10'); ?></td>

        </tr>

				<tr>

         <td><?php echo tep_black_line(); ?></td>

        </tr>

				<tr>

				 <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

           <td class="seoHead"><?php echo HEADING_TITLE_RANK; ?></td>

          </tr>

           <tr>

            <td class="seo_subHead"><?php echo TEXT_RANK; ?></td>

          </tr>

        </table></td>

				</tr>

				<tr>

			   <td align="right" > <?php echo tep_draw_form('seotips', FILENAME_SEO_ASSISTANT, tep_get_all_get_params(array('action2')) . 'action2=' . $form_action, 'post' ); ?></td>

        </tr>

			  <tr>

			   <td width="10"><?php echo tep_draw_separator('pixel_trans.png', '10', '10'); ?></td>

        </tr>

				<tr class="infoBoxContents">

         <td><table border="0" cellspacing="2" cellpadding="2">

          <tr>

			  	 <td>Enter URL: </td>

           <td><?php echo tep_draw_input_field('rank_url', tep_not_null($searchurl) ? $searchurl : '', 'maxlength="255", size="40"',   false); ?> </td>

    	     <td ><?php echo (tep_image_submit('button_admin_get_page_rank.png', IMAGE_GET_PAGE_RANK) ); ?></td>

			  	</tr>

				  <?php if (! empty($pageRank)) { ?>

			 		<tr>

					 <td  >Page Rank:</td>

				   <td ><?php echo sprintf("%d ( %s )",$pageRank, $prRating[(int)$pageRank]); ?> </td>

				  </tr>

				  <?php } ?>

			   </table></td>

				</tr>

      </form>

			<!-- END RANK CODE -->



			<!-- BEGIN LINK POPULARITY CODE -->

			<tr>

			 <td><?php echo tep_draw_separator('pixel_trans.png', '10', '10'); ?></td>

      </tr>

			<tr>

       <td><?php echo tep_black_line(); ?></td>

      </tr>

			<tr>

			 <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

        <tr>

         <td class="seoHead"><?php echo HEADING_TITLE_LINKPOP; ?></td>

        </tr>

        <tr>

         <td class="seo_subHead"><?php echo TEXT_LINKPOP; ?></td>

        </tr>

       </table></td>

			</tr>

			<tr>

			 <td align="right" > <?php echo tep_draw_form('seo_linkpop', FILENAME_SEO_ASSISTANT, tep_get_all_get_params(array('action_link')) . 'action2=' . $form_action, 'post' ); ?></td>

      </tr>

			<tr>

			 <td><?php echo tep_draw_separator('pixel_trans.png', '10', '10'); ?></td>

      </tr>

			<tr class="infoBoxContents">

       <td><table border="0" cellspacing="2" cellpadding="2">

        <tr>

			   <td>Enter URL: </td>

         <td><?php echo tep_draw_input_field('linkpop_url', tep_not_null($searchurl) ? $searchurl : '', 'maxlength="255", size="40"',   false); ?> </td>

    	   <td ><?php echo (tep_image_submit('button_link_popularity.png', IMAGE_LINK_POPULARITY) ); ?></td>

			  </tr>

			  <tr>

			   <td>Compare: </td>

         <td><?php echo tep_draw_input_field('linkpop_2_url', tep_not_null($searchurl) ? $searchurl : '', 'maxlength="255", size="40"',   false); ?> </td>

    	  </tr>

			 </table></td>

			</tr>

			<?php if ($total) { ?>

      <tr>

       <td colspan=2 height=5><!-- SPACER --></td>

      </tr>

      <table class="smallText" border=1 cellpadding=2 cellspacing=0>

			 <tr>

   	    <th class="smallText" align="center" width="25"><?php echo "DOMAIN"; ?></th>

   	    <th class="smallText" align="center" width="150"><?php echo $link_1_url; ?></th>

			   <?php if ($show_second_link) { ?> <th class="smallText" align="center" width="150"><?php echo $link_2_url; ?></th> <?php } ?>

			 </tr>

       <tr>

        <td >Alexa Traffic Ranking</td><td align='right'><?php echo "{$results['alexa'][0]}"; ?> (<a target="_blank" href='<?php echo "{$results['alexa'][1]}"; ?>'>view</a>)</td>

				 <?php if ($show_second_link) { ?> <td align='right'><?php echo "{$results_2['alexa'][0]}"; ?> (<a target="_blank" href='<?php echo "{$results_2['alexa'][1]}"; ?>'>view</a>)</td> <?php } ?>

  		 </tr>



       <tr>

        <td>Present in DMOZ</td><td align='right'><?php echo "{$results['dmoz'][0]}"; ?> (<a target="_blank" href='<?php echo "{$results['dmoz'][1]}"; ?>'>view</a>)</td>

			   <?php if ($show_second_link) { ?> <td align='right'><?php echo "{$results_2['dmoz'][0]}"; ?> (<a target="_blank" href='<?php echo "{$results_2['dmoz'][1]}"; ?>'>view</a>)</td> <?php } ?>

			 </tr>



			 <tr>

        <td>Present in Zeal</td><td align='right'><?php echo "{$results['zeal'][0]}"; ?> (<a target="_blank" href='<?php echo "{$results['zeal'][1]}"; ?>'>view</a>)</td>

				<?php if ($show_second_link) { ?> <td align='right'><?php echo "{$results_2['zeal'][0]}"; ?> (<a target="_blank" href='<?php echo "{$results_2['zeal'][1]}"; ?>'>view</a>)</td> <?php } ?>

			 </tr>



			 <tr>

        <td colspan=3 height=5 bgcolor="#FF0000"><!-- SPACER --></td>

       </tr>

		   <tr>

        <td>AlltheWeb</td><td align='right'><?php echo "{$results['alltheweb'][0]}"; ?> (<a target="_blank" href='<?php echo "{$results['alltheweb'][1]}"; ?>'>view</a>)</td>

				<?php if ($show_second_link) { ?> <td align='right'><?php echo "{$results_2['alltheweb'][0]}"; ?> (<a target="_blank" href='<?php echo "{$results_2['alltheweb'][1]}"; ?>'>view</a>)</td> <?php } ?>

			 </tr>



			 <tr>

        <td>AltaVista</td><td align='right'><?php echo "{$results['altavista'][0]}"; ?> (<a target="_blank" href='<?php echo "{$results['altavista'][1]}"; ?>'>view</a>)</td>

			   <?php if ($show_second_link) { ?> <td align='right'><?php echo "{$results_2['altavista'][0]}"; ?> (<a target="_blank" href='<?php echo "{$results_2['altavista'][1]}"; ?>'>view</a>)</td> <?php } ?>

			 </tr>



			 <tr>

        <td>Google</td><td align='right'><?php echo "{$results['google'][0]}"; ?> (<a target="_blank" href='<?php echo "{$results['google'][1]}"; ?>'>view</a>)</td>

			   <?php if ($show_second_link) { ?> <td align='right'><?php echo "{$results_2['google'][0]}"; ?> (<a target="_blank" href='<?php echo "{$results_2['google'][1]}"; ?>'>view</a>)</td> <?php } ?>

			 </tr>



			 <tr>

        <td>HotBot</td>

        <td align='right'><?php echo "{$results['hotbot'][0]}"; ?> (<a target="_blank" href='<?php echo "{$results['hotbot'][1]}"; ?>'>view</a>)</td>

			   <?php if ($show_second_link) { ?> <td align='right'><?php echo "{$results_2['hotbot'][0]}"; ?> (<a target="_blank" href='<?php echo "{$results_2['hotbot'][1]}"; ?>'>view</a>)</td> <?php } ?>

			 </tr>



			 <tr>

        <td>MSN Search</td>

        <td align='right'><?php echo "{$results['msn'][0]}"; ?> (<a target="_blank" href='<?php echo "{$results['msn'][1]}"; ?>'>view</a>)</td>

				<?php if ($show_second_link) { ?> <td align='right'><?php echo "{$results_2['msn'][0]}"; ?> (<a target="_blank" href='<?php echo "{$results_2['msn'][1]}"; ?>'>view</a>)</td> <?php } ?>

			 </tr>



			 <tr>

        <td>Yahoo!</td>

        <td align='right'><?php echo "{$results['yahoo'][0]}"; ?> (<a target="_blank" href='<?php echo "{$results['yahoo'][1]}"; ?>'>view</a>)</td>

			   <?php if ($show_second_link) { ?> <td align='right'><?php echo "{$results_2['yahoo'][0]}"; ?> (<a target="_blank" href='<?php echo "{$results_2['yahoo'][1]}"; ?>'>view</a>)</td> <?php } ?>

			 </tr>



			 <tr>

        <td><b>Total</b></td>

        <td align='right'><b><?php echo number_format($total); ?></b></td>

				<?php if ($show_second_link) { ?>	<td align='right'><b><?php echo number_format($total_2); ?></b></td> <?php } ?>

			 </tr>

			</table>

      <?php } ?>

     </form>

			<!-- END LINK POPULARITY CODE -->



			<!-- BEGIN KEYWORD DENSITY CODE -->

			<tr>

			 <td><?php echo tep_draw_separator('pixel_trans.png', '10', '10'); ?></td>

      </tr>

			<tr>

       <td><?php echo tep_black_line(); ?></td>

      </tr>

			<tr>

			 <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

        <tr>

         <td class="seoHead"><?php echo HEADING_TITLE_DENSITY; ?></td>

        </tr>

        <tr>

         <td class="seo_subHead"><?php echo TEXT_DENSITY; ?></td>

        </tr>

       </table></td>

			</tr>

			<tr>

			 <td align="right" > <?php echo tep_draw_form('keyword_density', FILENAME_SEO_ASSISTANT, tep_get_all_get_params(array('action3')) . 'action3=' . $form_action, 'post' ); ?></td>

      </tr>

			<tr>

			 <td><?php echo tep_draw_separator('pixel_trans.png', '10', '10'); ?></td>

      </tr>

			<tr class="infoBoxContents">

       <td><table border="0" cellspacing="2" cellpadding="2">

        <tr>

			   <td>Enter URL: </td>

         <td><?php echo tep_draw_input_field('density_url', tep_not_null($searchurl) ? $searchurl : '', 'maxlength="255", size="40"',   false); ?> </td>

    	   <td ><?php echo (tep_image_submit('button_check_density.png', IMAGE_CHECK_DENSITY)); ?></td>

			  </tr>

			 </table></td>

			 <tr>

			  <td><table border="0" cellspacing="2" cellpadding="2">

				<tr>

				 <td>Include Meta Tags: </td>

          <td><?php echo tep_draw_checkbox_field('use_meta_tags', '', false, ''); ?> </td>

				 <td>&nbsp;</td>

				 <td>Use Partial total: </td>

          <td><?php echo tep_draw_checkbox_field('use_partial_total', '', false, ''); ?> </td>

	 			</tr>

			  </table></td>

			 </tr>

			</tr>

			<?php if (! empty($dens[1])) { ?>

			<tr>

			 <td><?php echo tep_draw_separator('pixel_trans.png', '10', '10'); ?></td>

      </tr>

			<tr>

			 <td><?php echo 'Total words: ' . $ttl_words; ?></td>

			</tr>

			<tr>

			 <td><table border="1" width="100%" cellspacing="0" cellpadding="0">

  		  <th class="smallText" align="center" width="20%"><?php echo "Single Word"; ?></th>

  		  <th class="smallText" align="center" width="5%"><?php echo "Count"; ?></th>

	      <th class="smallText" align="center" width="5%"><?php echo "Density (%)"; ?></th>

 		    <th class="smallText" align="center" width="20%"><?php echo "Double Word"; ?></th>

 		    <th class="smallText" align="center" width="5%"><?php echo "Count"; ?></th>

		    <th class="smallText" align="center" width="5%"><?php echo "Density (%)"; ?></th>

 			  <th class="smallText" align="center" width="30%"><?php echo "Triple Word"; ?></th>

	      <th class="smallText" align="center" width="5%"><?php echo "Count"; ?></th>

			  <th class="smallText" align="center" width="5%"><?php echo "Density (%)"; ?></th>

        <?php

				 $cnt2=0; $cnt3=0;

				 while (list($key, $val) = each($dens[1])) {

			  ?>

	      <tr>

	       <td><?php  echo $key; ?> </td>

				<td><?php echo $dens[$key]; ?> </td>

	       <td width="5%"><?php  echo $val; ?> </td>

	      <?php

			  if ($cnt2 < count($dens[2])) {

				  while (list($key2, $val2) = each($dens[2])) { ?>

	          <td><?php  echo $key2; ?> </td>

					<td><?php echo $dens[$key2]; ?> </td>

		        <td width="5%"><?php  echo $val2; ?> </td>

					<?php

					 if ($cnt2 < count($dens[3])) {

					   while (list($key3, $val3) = each($dens[3])) { ?>

                <td><?php echo $key3; ?></td>

						 <td><?php echo $dens[$key3]; ?> </td>

				       <td width="5%"><?php echo $val3; ?></td>

		            <?php

		             break;

               }

					 } else {

					 ?>

				     <td>&nbsp;</td>

              <td>&nbsp;</td>

					  <td>&nbsp;</td>

					 <?php

					 }

             break;

           }

			  } else {

			  ?>

			   <td>&nbsp;</td>

				<td>&nbsp;</td>

				<td>&nbsp;</td>

			  <?php if ($cnt3 < count($dens[1])) {	?>

  			<td>&nbsp;</td>

				<td>&nbsp;</td>

				<td>&nbsp;</td>

			  <?php

			  }

			}

			$cnt2++;

			$cnt3++;

			?>

		 </tr>

     <?php  } ?>

		</table></td>

	 </tr>

	  <?php } ?>

   </form>

			<!-- END KEYWORD DENSITY CODE -->



      <!-- BEGIN CHECK LINKS CODE -->

				<tr>

			   <td><?php echo tep_draw_separator('pixel_trans.png', '10', '10'); ?></td>

        </tr>

				<tr>

         <td><?php echo tep_black_line(); ?></td>

        </tr>

				<tr>

				 <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

           <td class="seoHead"><?php echo HEADING_TITLE_CHECK_LINKS; ?></td>

          </tr>

           <tr>

           <td class="seo_subHead"><?php echo TEXT_CHECK_LINKS; ?></td>

         </tr>

        </table></td>

				</tr>

				<tr>

			   <td align="right" > <?php echo tep_draw_form('check_links', FILENAME_SEO_ASSISTANT, tep_get_all_get_params(array('action2')) . 'action2=' . $form_action, 'post' ); ?></td>

        </tr>

			  <tr>

			   <td><?php echo tep_draw_separator('pixel_trans.png', '10', '10'); ?></td>

        </tr>

				<tr class="infoBoxContents">

         <td><table border="0" cellspacing="2" cellpadding="2">

          <tr>

			  	 <td>Enter URL: </td>

           <td><?php echo tep_draw_input_field('check_page', tep_not_null($searchurl) ? $searchurl : '', 'maxlength="255", size="40"',   false); ?> </td>

    	     <td ><?php echo (tep_image_submit('button_check_links.png', IMAGE_CHECK_LINKS) ); ?></td>

			  	</tr>

           </table></td>

				</tr>

           <?php if (count($badLinks) > 0) { ?>

           <tr>

			      <td><?php echo tep_draw_separator('pixel_trans.png', '10', '10'); ?></td>

           </tr>

           <tr>

            <td>Found <?php echo count($badLinks); ?>&nbsp;suspected bad link(s) out of a total of&nbsp; <?php echo $totalLinks; ?> </td>

           </tr>

           <tr>

			      <td><?php echo tep_draw_separator('pixel_trans.png', '10', '10'); ?></td>

           </tr>

           <tr>

            <td><table border="1" width="80%">

           <?php } for ($idx = 0; $idx < count($badLinks); $idx++) { ?>

			 		<tr>

					 <td width="15%">Broken Link:</td>

				   <td><?php echo $badLinks[$idx]; ?> </td>

				  </tr>

				  <?php } ?>

            </table></td>

            </tr>



      </form>

			<!-- END CHECK LINKS CODE -->



			</table></td>

		 </tr>

    </table></td>

<!-- body_text_eof //-->

  </tr>

</table>

<!-- body_eof //-->



<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

<br>

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

