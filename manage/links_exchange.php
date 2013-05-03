<?php

/*

  $Id: links_exchange.php,v 1.00 2003/10/03 Exp $

  by Jack_mcs - CartStore.com



  CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA



  GNU General Public License Compatible

*/



  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LINKS_EXCHANGE);

 

  if (isset($_POST['links_exchange_x']))

  {

    $languages = tep_get_languages();

    for ($i=0, $n=sizeof($languages); $i<$n; $i++) 

    {

      $name = sprintf("links_exchange_name_%d", $languages[$i]['id']);

      $desc = sprintf("links_exchange_description_%d", $languages[$i]['id']);

      $url  = sprintf("links_exchange_url_%d", $languages[$i]['id']);

      $links_exchange_name = tep_db_prepare_input($_POST[$name]);

      $links_exchange_description = tep_db_prepare_input($_POST[$desc]);

      $links_exchange_url = tep_db_prepare_input($_POST[$url]);



      $links_query = tep_db_query("select count(*) as total from " . TABLE_LINKS_EXCHANGE . " where language_id = '" . (int)$languages[$i]['id'] . "'");

      $links = tep_db_fetch_array($links_query);

      

      if ($links['total'] > 0) 

        tep_db_query("update " . TABLE_LINKS_EXCHANGE . " set links_exchange_name = '" . $links_exchange_name . "', links_exchange_description = '" . $links_exchange_description . "', links_exchange_url = '" . $links_exchange_url . "' where language_id = '" . (int)$languages[$i]['id'] . "'");

      else

        tep_db_query("insert into " . TABLE_LINKS_EXCHANGE . " (links_exchange_name, links_exchange_description, links_exchange_url, language_id) values ('" . $links_exchange_name . "', '" . $links_exchange_description . "', '" . $links_exchange_url . "', '" . (int)$languages[$i]['id'] . "')");

    }

    $messageStack->add(MSG_SUCCESS, 'success');  

  }



  $exchangeData = array();  

  $links_exchange_query = tep_db_query("select * from " . TABLE_LINKS_EXCHANGE);

  while ($links_exchange = tep_db_fetch_array($links_exchange_query))

  {  

    $languages = tep_get_languages();

    for ($i=0, $n=sizeof($languages); $i<$n; $i++)

    {

      if ($links_exchange['language_id'] == $languages[$i]['id'])

      {

        $exchangeData[$i]['name'] = $links_exchange['links_exchange_name'];

        $exchangeData[$i]['desc'] = $links_exchange['links_exchange_description'];

        $exchangeData[$i]['url']  = $links_exchange['links_exchange_url'];

      }

    } 

  } 



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

	

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo TITLE; ?></title>

<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />

   

	 	

</head>

<body>

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

   <td width="100%" valign="top"><?php echo tep_draw_form('links_exchange', FILENAME_LINKS_EXCHANGE, '', 'post'); ?>

    <table border="0" width="100%" cellspacing="0" cellpadding="2">

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td class="pageHeading"><?php echo HEADING_TITLE_LINKS_EXCHANGE; ?></td>

            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.png', '100%', '10'); ?></td>

          </tr>

          <tr>            

            <td><?php echo tep_draw_separator('pixel_trans.png', '100%', '10'); ?></td>

          </tr>          

          <tr>

            <td class="main"><?php echo TEXT_HEADING_SUB_TEXT; ?></td>

          </tr>    

          <tr>

           <td><table border="0" width="75%" cellspacing="0" cellpadding="0">          

            <?php $languages = tep_get_languages();

            for ($i=0, $n=sizeof($languages); $i<$n; $i++) {

            ?>

            <tr>            

             <td><?php echo tep_draw_separator('pixel_trans.png', '100%', '15'); ?></td>

            </tr>            

            <tr>

             <td><table border="0" cellpadding="2" style="border-width: 4px; border-style: ridge; ">

              

              <tr bgcolor="#c9c9c9">

               <th colspan="2" class="main"><?php echo $languages[$i]['name']; ?></th>

              </tr>

               

              <tr class="main">    

                <td><?php echo TEXT_LINKS_EXCHANGE_NAME; ?></td>      

                <td><?php echo tep_draw_input_field('links_exchange_name_' . $languages[$i]['id'], $exchangeData[$i]['name'], 'size="55" maxlength="255"', false); ?> </td>

              </tr> 

  

              <tr class="main">    

                <td valign="top"><?php echo TEXT_LINKS_EXCHANGE_DESCRIPTION; ?></td>      

                <td><?php echo tep_draw_textarea_field('links_exchange_description_' . $languages[$i]['id'], 'hard', 51, 6, $exchangeData[$i]['desc'], '', false); ?></td>

              </tr>      

                          

              <tr class="main">    

                <td><?php echo TEXT_LINKS_EXCHANGE_URL; ?></td>      

                <td><?php echo tep_draw_input_field('links_exchange_url_'. $languages[$i]['id'], $exchangeData[$i]['url'], 'size="55" maxlength="255"', false); ?> </td>

              </tr>    

            

             </table></td>

            </tr>            

            <?php } ?>      

            <tr>            

             <td><?php echo tep_draw_separator('pixel_trans.png', '100%', '5'); ?></td>

            </tr>               

            <tr>

             <td><table border="0" width="75%" cellspacing="0" cellpadding="0">

              <tr>  

               <td align="center"><?php echo tep_image_submit('button_update.png', IMAGE_UPDATE, 'name=links_exchange'); ?></td>

              </tr>

             </table></td>  

            </tr> 

                              

           </table></td>

          </tr>    

        </table></td>

      </tr>    

    

 

      

   

     

  <!-- body_text_eof //-->

    

   </table></form></td> 

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

