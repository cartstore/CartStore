<?php
/*
  $Id: viewed_products.php, v 1.8 2005/01/09 00:00:00 gjw Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// if (((tep_session_is_registered('customer_id')) or (ENABLE_PAGE_CACHE == 'false')) and (!$spider_flag)){

// HQSQ added call to language define file for Viewed Items Mod START
     require_once(DIR_WS_LANGUAGES . $language . '/' . 'viewed_products.php');
// HQSQ added call to language define file for Viewed Items Mod END

//*******************************************************************************
  DEFINE('HIST_ROWS', 9);         // number of rows per column on display
  DEFINE('HIST_MAX_ROWS', 9);     // max number of products on display
  DEFINE('HIST_MEM_TRIGGER', 5);  // number when memory threshold kicks in
//*******************************************************************************

  // register the array if not already done so

  if (tep_session_is_registered('viewed') && is_object($viewed)) {
  } else {
    tep_session_register('viewed');
    $viewed = new viewed_products;
    $viewed->reset();
  }

// start user switch //
  if (tep_session_is_registered('viewed_switch')) {
  } else {
    tep_session_register('viewed_switch');
    $viewed_switch = 'true';
  }
// end user switch //

  // empty the array if requested by the user
//  if (isset($_GET['action'])) {
//    if ($_GET['action'] == 'viewed_remove') {
//      $viewed->remove();
//    }
//  }

// start user switch //
  if (isset($_GET['action'])) {
    if ($_GET['action'] == 'viewed_remove') {
      $viewed->remove();
    } elseif ($_GET['action'] == 'viewed_switch') {
        if ($viewed_switch == 'true') {
          $viewed_switch = 'false';
        } else {
            $viewed_switch = 'true';
          }
      }
  }
// end user switch //

  // display the box if we have history
//  if ($viewed->count_viewed() > 0) { // displaying

// start shift from line 106 to here
 $items_ids_on_display = array();
// end shift

// start user switch //
  if (($viewed->count_viewed() > 0) and ($viewed_switch == 'true')){ // displaying
// end user switch //

  if (HIST_MAX_ROWS <= HIST_ROWS) {
    $hist_width= '100%';
  } else {
    $hist_width= '100%';
  }

  echo '';

    $row = 0;

    /* get the products array from the class containing all viewed products */

    $items = $viewed->get_viewed_items();

    $index = 1;

    /* determine the first and last record we want to display*/
    $first = sizeof($items)- HIST_MAX_ROWS;
    $last  = sizeof($items)-1;
    if (sizeof($items) < HIST_MAX_ROWS) {$disp = sizeof($items);} else {$disp = HIST_MAX_ROWS;}
    if ($first < 0) {$first = 0;}

    /* only fetch the info for products on display */
//    $items_ids_on_display = array();            // shift to line 67
    for ($i=$last, $n=$first; $i>=$n; $i--) {
        $viewed_query = tep_db_query("select pd.products_name,
                                             p.products_image ,
                                              pd.products_short
                                      from " . TABLE_PRODUCTS . " p,
                                           " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                      where p.products_id = '" . $items[$i] . "' and
                                            pd.language_id = '" . $languages_id . "' and
                                            pd.products_id = p.products_id");
        if ($viewed_info = tep_db_fetch_array($viewed_query)) {
         $items_on_display[$i] = array('id' => $items[$i],
                                     'name' => $viewed_info['products_name'],
                                     'short' => $viewed_info['products_short'],
                                     'image' => $viewed_info['products_image']);
         $items_ids_on_display[]= $items[$i];
        }
    }

    echo '<div class="module">
<div>
<div>
<div>
<h3>RECENTLY VIEWED</h3>

          ';
    echo '';
    echo '<ul>';
    for ($i=$last, $n=$first; $i>=$n; $i--) {
      echo '';
      if (isset($items_on_display[$i]))
        echo '<li><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $items_on_display[$i]['id']) . '">' . $items_on_display[$i]['name'] . '</a></li>';

      $row ++;
      $index++;
      if ($row > HIST_ROWS - 1) {
        $row = 0;
        echo '</ul>';
        if ($i > $n) {
          echo  '</ul>';
        } else {
           echo '</ul>';
          }
      }
    }
    echo '</div>
</div>
</div>
</div>';

    /* find random product in displayed list */
    $selected_product1 = '';
    while (empty($selected_product1)){
      $random_number = rand($first,$last);
      if (isset($items_on_display[$random_number])){
        $selected_product1 = $items_on_display[$random_number]['id'];
        $selected_product1_offset = $random_number;
        break;
      }
    }

    /* do the also purchased query */

    $orders_query = tep_db_query("select p.products_id,
                                         p.products_image,
                                         pd.products_short,
                                        pd.products_name
                                  from " . TABLE_ORDERS_PRODUCTS . " opa,
                                       " . TABLE_ORDERS_PRODUCTS . " opb,
                                       " . TABLE_ORDERS . " o,
                                       " . TABLE_PRODUCTS . " p,
                                       " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                  where opa.products_id = '" . $selected_product1 . "' and
                                        opa.orders_id = opb.orders_id and
                                        opb.products_id = p.products_id and
                                        opb.products_id != '" . $selected_product1 . "' and
                                        opb.orders_id = o.orders_id and
                                          p.products_id = pd.products_id and
                                         pd.language_id = '" . $languages_id . "' and
                                        p.products_status = '1'
                                  group by p.products_id
                                  order by o.date_purchased desc
                                  limit 1");


  /* if we find results, display the also purchased product */
  if ($orders = tep_db_fetch_array($orders_query)) {
    echo '<div class="module">
<div>
<div>
<div>
<h3>CUSTOMERS ALSO ORDERED</h3>
<div class="box">';

    echo '<h4><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . $orders['products_name'] . '</a></h4>

    <a class="imagebox" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $orders['products_image'], $orders['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>

    <span class="short_desc">Desc: ' . $orders['products_short'] . '</span>

    <div class="clear"></div>

    <a class="readon" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">More Info</a>
<div class="clear"></div>

    ';
    echo '</div>
</div>
</div>
</div>
</div>';

  /* if there are no also purchased results and we display more than the memory trigger
     display the remember me product */

  } elseif (sizeof($items) > HIST_MEM_TRIGGER) {

    /* select a random record after the memory trigger threshold */
    // $random_number = rand($first,$last-HIST_MEM_TRIGGER);

    if (isset($_GET['products_id'])) {
      if ($items_on_display[$random_number]['id'] != $_GET['products_id']) {

        echo '<div class="module">
<div>
<div>
<div>
<h3>RECENTLY VIEWED</h3>
<div class="box">';
        echo $items_on_display[$selected_product1]['name'];

        echo '<h4><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $items_on_display[$random_number]['id']) . '">' . $items_on_display[$random_number]['name'] . '</a></h4>';
        echo '<a class="imagebox" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $items_on_display[$random_number]['id']) . '">' . tep_image(DIR_WS_IMAGES . $items_on_display[$random_number]['image'], $items_on_display[$random_number]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>

        <span class="short_desc">Desc: ' . $items_on_display[$random_number]['short'] . '</span>

    <div class="clear"></div>

    <a class="readon" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $items_on_display[$random_number]['id']) . '">More Info</a>
<div class="clear"></div>

        ';




        echo '</div></div>
</div>
</div>
</div>';
      } else { // selected item is already displayed on the page so show text
          echo '
               ';
        }
     } else { // no products_id given so go ahead with memory display
        echo '<div class="module">
<div>
<div>
<div>
<h3>OF INTEREST</h3>
<div class="box">';
//        echo $items_on_display[$random_number]['name'];

        echo '<h4><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $items_on_display[$random_number]['id']) . '">' . $items_on_display[$random_number]['name'] . '</a></h4>';
         echo '<a class="imagebox" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $items_on_display[$random_number]['id']) . '">' . tep_image(DIR_WS_IMAGES . $items_on_display[$random_number]['image'], $items_on_display[$random_number]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>

         <span class="short_desc">Desc: ' . $items_on_display[$random_number]['short'] . '</span>

    <div class="clear"></div>

    <a class="readon" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $items_on_display[$random_number]['id']) . '">More Info</a>
<div class="clear"></div>


         ';
        echo '</div></div>
</div>
</div>
</div>';
       }
  } else {

// if no results at all, display the explanation
    echo '';
  }
  echo '
    ';

  } // Displaying

  if (isset($_GET['products_id']) and ($_GET['action'] != 'viewed_remove')) {
    if (!in_array($_GET['products_id'], $items_ids_on_display)) {
      $viewed->add_viewed($_GET['products_id']);
    }
  }
 // general condition
?>
