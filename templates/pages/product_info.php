            <?php
            echo $messageStack->output('upload');
            ?>




            <?php
            echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product'), 'post', 'enctype="multipart/form-data"');
            ?>

            <?php
            if ($product_check['total'] < 1) {
                ?>

<div class="alert alert-warning">
                <?php
                new infoBox(array(array('text' => TEXT_PRODUCT_NOT_FOUND)));
                ?>
</div>

                <?php
                echo '<a class="btn button" href="' . tep_href_link(FILENAME_DEFAULT) . '">' . IMAGE_BUTTON_CONTINUE . '</a>';
                ?>


                <?php
            } else {
                $product_info_query = tep_db_query("select p.products_id,p.map_price, p.msrp_price,pd.products_info_title,p.products_status,pd.products_info_desc, pd.products_name, pd.products_description, pd.products_short, p.products_model, p.products_special, p.products_quantity, p.products_image, p.product_image_2, p.product_image_3, p.product_image_4, p.product_image_5, p.product_image_6, pd.products_url, p.products_price, NULL as specials_new_products_price, p.products_price1, p.products_price2, p.products_price3, p.products_price4, p.products_price5, p.products_price6, p.products_price7, p.products_price8, p.products_price1_qty, p.products_price2_qty, p.products_price3_qty, p.products_price4_qty, p.products_price5_qty, p.products_price6_qty, p.products_price7_qty, p.products_price8_qty, p.products_qty_blocks, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int) $_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int) $languages_id . "'");
                $product_info = tep_db_fetch_array($product_info_query);
                tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int) $_GET['products_id'] . "' and language_id = '" . (int) $languages_id . "'");
                $pf->loadProductSppc((int) $_GET['products_id'], (int) $languages_id, $product_info);
                $products_price = $pf->getPriceString();
                $products_status = $product_info['products_status'];
                if (tep_not_null($product_info['products_model'])) {
                    $products_name = $product_info['products_name'];
                } else {
                    $products_name = $product_info['products_name'];
                }
                if (tep_session_is_registered('wishlist_id')) {
                    ?>
                    <div class="alert alert-success"> <?php
            echo PRODUCT_ADDED_TO_WISHLIST;
                    ?></div>


                    <?php
                    tep_session_unregister('wishlist_id');
                }
                ?>
<div itemscope itemtype="http://data-vocabulary.org/Product">

                <div class="page-header">	<h1 itemprop="name">                              <?php     include(DIR_WS_TEMPLATES . '/system/front-admin-editor/edit-store-product.php'); ?>
<?php
            echo $products_name;
                ?></h1></div>


                <?php
                    $cat_path = tep_get_product_path($product_info['products_id']);
                    $categories = explode("_", $cat_path);
                    $hold = array();
                    foreach ($categories as $category){
						$rs = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd where cd.categories_id = " . $category . " and language_id = " . (int)$languages_id);
						$rq = tep_db_fetch_array($rs);
						array_push($hold, $rq['categories_name']);
                    }
                ?>
                <ul class="breadcrumb" itemprop="category" content="<?php echo implode(" > ", $hold); ?>">
                    <?php
                    echo $breadcrumb->trail('');
                    ?>


                </ul>



                        <div class="row">

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

                            <?php
                            if (tep_not_null($product_info['products_image'])) {
                                ?>

<?php
                        echo '<a href="images/' . $product_info['products_image'] . '"  class="fancybox" data-fancybox-group="gallery">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, ' data-zoomsrc="images/' . $product_info['products_image'] . '" class="imageborder" name="source" itemprop="image" ') . '</a>' . '';

                        ?>


                                <div class="clear"></div>

                                <?php
                            }
                            ?>


                            <?php
                            if (tep_not_null($product_info['products_image'])) {
                                ?>






                                    <?php
                                    if ($product_info['product_image_2'] != "") {
                                        echo '<span class="pull-left extra_images"><a href="' . DIR_WS_IMAGES . $product_info['product_image_2'] . '"  class="fancybox" data-fancybox-group="gallery">' . tep_image(DIR_WS_IMAGES . $product_info['product_image_2'], $product_info['products_name'], 45, SMALL_IMAGE_HEIGHT) . '</a> </span>';
                                        ?>



                                    <?php
                                }
                                ?>



                                    <?php
                                    if ($product_info['product_image_3'] != "") {
                                        echo '  <span class="pull-left extra_images"><a href="' . DIR_WS_IMAGES . $product_info['product_image_3'] . '"  class="fancybox" data-fancybox-group="gallery">' . tep_image(DIR_WS_IMAGES . $product_info['product_image_3'], $product_info['products_name'], 45, SMALL_IMAGE_HEIGHT) . '</a> </span>';
                                        ?>


                                        <?php
                                    }
                                    ?>






                                    <?php
                                    if ($product_info['product_image_4'] != "") {
                                        echo '<span class="pull-left extra_images"><a href="' . DIR_WS_IMAGES . $product_info['product_image_4'] . '"  class="fancybox" data-fancybox-group="gallery">' . tep_image(DIR_WS_IMAGES . $product_info['product_image_4'], $product_info['products_name'], 45, SMALL_IMAGE_HEIGHT) . '</a> </span>';
                                        ?>
                                        <?php
                                    }
                                    ?>





                                    <?php
                                    if ($product_info['product_image_5'] != "") {
                                        echo ' <span class="pull-left extra_images"><a href="' . DIR_WS_IMAGES . $product_info['product_image_5'] . '"  class="fancybox" data-fancybox-group="gallery">' . tep_image(DIR_WS_IMAGES . $product_info['product_image_5'], $product_info['products_name'], 45, SMALL_IMAGE_HEIGHT) . '</a></span>';
                                        ?>
                                        <?php
                                    }
                                    ?>




                                    <?php
                                    if ($product_info['product_image_6'] != "") {
                                        echo '<span class="pull-left extra_images"><a href="' . DIR_WS_IMAGES . $product_info['product_image_6'] . '"  class="fancybox" data-fancybox-group="gallery">' . tep_image(DIR_WS_IMAGES . $product_info['product_image_6'], $product_info['products_name'], 45, SMALL_IMAGE_HEIGHT) . '</a></span>';
                                        ?>
                                        <?php
                                    }
                                    ?>

                                <?php
                                //backwards compat with other popular extra iamage ext do not delete needed for migrations
                                $totproducts_extra_images_query = tep_db_query("SELECT products_extra_image, products_extra_images_id FROM " . TABLE_PRODUCTS_EXTRA_IMAGES . " WHERE products_id='" . $product_info['products_id'] . "'");


                                if (tep_db_num_rows($totproducts_extra_images_query) >= 1) {
                                    ?>

                                    <?php
                                    if (DISPLAY_EXTRA_IMAGES == 'true') {
                                        if ($product_check['total'] >= 1) {
                                            include (DIR_WS_INCLUDES . 'products_extra_images.php');
                                        }
                                    }
                                    ?>

                                <?php } else { ?>

        <?php } ?>







 </div>







                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">




                                <?php
                            }
                            if ($product_info['map_price'] != "0.00") {
                                if (isset($_SESSION['customer_id'])) {
                                    $products_price = $products_price;
                                    $products_price .= '<br />
			<span class="label label-warning"><i class="fa fa-tags fa-3x"></i> MAP Price: ' . $currencies->display_price($product_info['map_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
                                } else {
                                    $products_price = '<br />
		<i class="fa fa-tags"></i> Min Advertised Price <br><span class="lead">' . $currencies->display_price($product_info['map_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
                                }
                                if (!isset($_SESSION['customer_id'])) {
                                    $products_price .= '<br>

		</p></b><a href="login.php"><i class="fa fa-unlock-alt"></i> Login to See Price</a><p><b>';
                                }
                            } elseif ($product_info['msrp_price'] != "0.00") {
                                //$products_price = '' . $products_price . '<ul class="product_price_page"> <li>MSRP Price <span>' . $currencies->display_price($product_info['msrp_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span></li></ul>';
                            } else
                                $products_price = $products_price;

                            if ($product_info['products_url'] != "") {
                                $newArea = '
<a class="btn button" href=' . $product_info['products_url'] . ' title="' . $product_info['products_url'] . '" >Buy From Partner </a>';
                            } elseif (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '') {
                                $newArea = '';
                            } elseif ($product_info['products_price'] > 0) {
                                $newArea = "

<div class=\"form-group\">" . tep_draw_input_field('cart_quantity', $pf->adjustQty(1), 'size="6"') . " </div>
" . tep_draw_hidden_field('products_id', $product_info['products_id']) . '<p><button type="submit" title="Add to Cart " value="Add to Cart" class="btn btn-primary btn-lg"><i class="fa fa-shopping-cart"></i> Add to Cart</button></p>';
                            } else {
                                $newArea = '';
                            }
                            if (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '') {
                                $products_price = "";
                            } else
                                $products_price = $products_price;
                            ?>
                            <?php
                            if ($_SERVER['HTTPS']) {
                                echo '';
                            } else {
                                echo '
		';
                            }
                            ?>
                            <span class="details"> <?php
                            if ($product_info['products_model']) {
                                ?>
                                    <span class="pmodel"><b><i class="fa fa-slack"></i> <span itemprop="identifier"><?php
                                    echo $product_info['products_model'];
                                    ?></span></b></span>

                                            <?php
                                        }
                                        ?>
                                <?php
                                if ($product_info['products_price'] > 0 && empty($product_info['products_url'])) {
                                    echo '<a class="linkup2" href="' . tep_href_link(FILENAME_WISHLIST, tep_get_all_get_params(array('action', 'products_id')) . 'wishlist_x=true&products_id=' . $product_info['products_id']) . '" ><i class="fa fa-share"></i> Wishlist</a>';
                                } else {
                                    $urlContactUs = "email_for_price.php?product_name=" . addslashes(addslashes($product_info['products_name'])) . "&products_model=" . $product_info['products_model'];
                                    echo '<a href="' . $urlContactUs . '"><h3><i class="fa fa-envelope"></i> Email for Price </h3></a>';
                                }
                                ?>


    <?php
    if ($product_info['products_price'] > 0) {
        echo '<p class="text-info lead no-margin"><b>' . $products_price . '</b></p>';
        echo '<p>';
        if ((STOCK_CHECK == 'true') && ($product_info['products_quantity'] < 1)) {
            $status_p = "<span class=\"label label-warning\"><i class=\"fa fa-exclamation-circle\"></i> <span itemprop=\"availability\" content=\"out_of_stock\">Out of Stock</span></span>";
        } elseif ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
            $status_p = '<span class=\"label label-warning\"><i class="fa fa-calendar"></i> <span itemprop=\"availability\" content=\"out_of_stock\">Available ' . tep_date_long($product_info['products_date_available']) . '</span></span>';
        } else {
            $status_p = "<span class=\"label label-success\"><i class=\"fa fa-truck\"></i> <span itemprop=\"availability\" content=\"in_stock\">In Stock</span></span>";
        }
    } else {
        $urlContactUs = "email_for_price.php?product_name=" . addslashes(addslashes($product_info['products_name'])) . "&products_model=" . $product_info['products_model'];
        echo '';
        echo '';
        if ((STOCK_CHECK == 'true') && ($product_info['products_quantity'] < 1)) {
            $status_p = "<span class=\"label label-important\"><i class=\"fa fa-exclamation-circle\"></i> <span itemprop=\"availability\" content=\"out_of_stock\">Out of Stock</span></span>";
        } elseif ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
            $status_p = '<span class=\"label label-warning\"><i class="fa fa-calendar"></i> <span itemprop="availability" content="out_of_stock">Available ' . tep_date_long($product_info['products_date_available']) . '</span></span>';
        } else {
            $status_p = "<span class=\"label label-success\"><i class=\"fa fa-truck\"></i> <span itemprop=\"availability\" content=\"in_stock\">In Stock</span></span>";
        }
    }
    ?>

                                <?php
                                echo $status_p;
                                ?>


    <?php
    if ($product_info['products_info_title'] != "") {
        print('
	<span class="label label-info"><i class="fa fa-truck"></i> Ships In ' . $product_info['products_info_title']) . ' Days</span>';
    }
    ?>

                                <?php
                                if ((USE_POINTS_SYSTEM == 'true') && ($product_info['products_price'] > 0) && (DISPLAY_POINTS_INFO == 'true')) {
                                    if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
                                        $products_price_points = tep_display_points($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . 'rrr</span>';
                                    } else {
                                        $products_price_points = tep_display_points($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
                                    }
                                    $products_points = tep_calc_products_price_points($products_price_points);
                                    $products_points_value = tep_calc_price_pvalue($products_points);
                                    if (USE_POINTS_FOR_SPECIALS == 'true' || $new_price == false) {
                                        echo '

	<span class="label label-info"><i class="fa fa-trophy"></i> ' . sprintf(TEXT_PRODUCT_POINTS, number_format($products_points, POINTS_DECIMAL_PLACES), $currencies->format($products_points_value)) . '</span>';
                                    } else {
                                        echo '

		<span class="label label-info"><i class="fa fa-trophy"></i> ' . TEXT_PRODUCT_NO_POINTS . '</span>



		';
                                    }
                                }
                                ?></p>
                                <div class="clear"></div>
                                <?php
                                list($products_id_clean) = explode('{', $product_info['products_id']);
                                $extra_fields_query = tep_db_query("
		SELECT pef.products_extra_fields_name as name, ptf.products_extra_fields_value as value ,pef.products_extra_fields_status as status
		FROM " . TABLE_PRODUCTS_EXTRA_FIELDS . " pef
		LEFT JOIN  " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " ptf
		ON ptf.products_extra_fields_id=pef.products_extra_fields_id
		WHERE ptf.products_id=" . $products_id_clean . " and ptf.products_extra_fields_value<>'' and (pef.languages_id='0' or pef.languages_id='" . $languages_id . "')
		ORDER BY products_extra_fields_order");
                                while ($extra_fields = tep_db_fetch_array($extra_fields_query)) {
                                    if (!$extra_fields['status'])
                                        continue;
                                    echo '
		<p class="extra-fields"><i class="fa fa-angle-right"></i> ' . $extra_fields['name'] . '<b>: ' . $extra_fields['value'] . '</b></p>';
                                }
                                ?>
                                <?php
                                $extra_shipping_query = tep_db_query("select products_ship_price, products_ship_price_two from " . TABLE_PRODUCTS_SHIPPING . " where products_id = '" . (int) $product_info['products_id'] . "'");
                                if (tep_db_num_rows($extra_shipping_query) > 0) {
                                    $extra_shipping = tep_db_fetch_array($extra_shipping_query);
                                    if ($extra_shipping['products_ship_price'] == '0.00') {
                                        echo '<p><div class="alert alert-info"><small>(Free Shipping for this Item)</small></div>';
                                    } else {
                                        echo '<p><div class="alert alert-info"><small>(This item requires additional shipping of $' . $extra_shipping['products_ship_price'];
                                        if (($extra_shipping['products_ship_price_two']) > 0) {
                                            echo ' for the first item, and $' . $extra_shipping['products_ship_price_two'] . ' for each additional item + regular shipping costs.)</small></div>';
                                        } else {
                                            echo ' + regular shipping costs.)</small></div></p>';
                                        }
                                    }
                                }
                                ?>
                                <?php
                                if ($product_info['products_date_available'] > date('Y-m-d H:i:s'))
                                    echo '<p><b>Order today ships on ' . tep_date_short($product_info['products_date_available']) . '</p>';
                                elseif (($product_info['products_special'] != 1))
                                    echo '';
                                else
                                    echo '<p><span class="markProductOutOfStock">This product is ordered directly from the manufacturer when your order is placed.  Your order may take additonal time to receive.</span></p>';
                                ?>
                                <?php
                                if (tep_session_is_registered('affiliate_id')) {
                                    ?>
                                    <?php
                                    echo '<p><br><a class="btn btn-warning btn-small" href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD, 'individual_banner_id=' . $product_info['products_id']) . '" target="_self"> <i class="fa fa-arrow-circle-right"></i> Build Affiliate Link </a></p>';
                                    ?>
                                    <?php
                                }
                                ?>


    <?php
    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int) $_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int) $languages_id . "'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    if ($products_attributes['total'] > 0) {
        $products_id = (preg_match("/^\d{1,10}(\{\d{1,10}\}\d{1,10})*$/", $_GET['products_id']) ? $_GET['products_id'] : (int) $_GET['products_id']);
        require (DIR_WS_CLASSES . 'pad_' . PRODINFO_ATTRIBUTE_PLUGIN . '.php');
        $class = 'pad_' . PRODINFO_ATTRIBUTE_PLUGIN;
        $pad = new $class($products_id);
        echo '<div class="clear"></div>

<div class="form-group">';
        echo $pad->draw();
        echo '</div>';
    }
    ?>



    <?php echo $newArea; ?>

                            </span>

     </div>

                            <div class="clear"></div>
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">










                <div class="clear"></div>


                <!-- begin tab pane //-->




    <?php
    $product_description_string = '<div itemprop="description">' . $product_info['products_description'] . '</div>';
    $tab_array = preg_match_all("|#newtab#(.*)#/newtab#|Us", $product_description_string, $matches, PREG_SET_ORDER);
    if ($tab_array) {
        ?>



                    <p><ul class="nav nav-tabs" id="pInfoTab">
        <?php
        for ($i = 0, $n = sizeof($matches); $i < $n; $i++) {
            $this_tab_name = preg_match_all("|#tabname#(.*)#/tabname#|Us", $matches[$i][1], $tabname, PREG_SET_ORDER);
            if ($this_tab_name) {
                echo '' . '<li><a href="#tabs-' . $i . '" data-toggle="pill">' . $tabname[0][1] . '</a></li>' . '';
                echo '';
            }
        }
        ?>
                    </ul></p>




                    <div class="tab-content">
        <?php
        for ($i = 0, $n = sizeof($matches); $i < $n; $i++) {
            $this_tab_name = preg_match_all("|#tabname#(.*)#/tabname#|Us", $matches[$i][1], $tabname, PREG_SET_ORDER);
            if ($this_tab_name) {
                if (preg_match_all("|#tabpage#(.*)#/tabpage#|Us", $matches[$i][1], $tabpage, PREG_SET_ORDER)) {
                    require ($tabpage[0][1]);
                } elseif (preg_match_all("|#tabtext#(.*)#/tabtext#|Us", $matches[$i][1], $tabtext, PREG_SET_ORDER)) {
                    echo '<div class="tab-pane" id="tabs-' . $i . '">' . stripslashes($tabtext[0][1]) . '</div>';
                }
                echo '

		';
            }
        }
        ?>
                        <?php
                        if ($tab_array) {
                            ?>



            <?php
            echo $product_info['products_info_desc'];
            ?>
                        <hr>
                    </div>

            <?php
        } else {
            ?>
                            <?php
                        }
                        ?>
                        <?php
                    } else {
                        ?>

                        <!-- End Tab Pane //--> <?php
                        if ($product_info['products_description']) {
                            ?>

                            <h3>Products
                                Description</h3>
                            <?php
                            echo stripslashes($product_info['products_description']);
                            ?>

                            <div class="video" style="text-align: center; margin-bottom: 10px">
                            <?php
                            echo $product_info['products_info_desc'];
                            ?>
                            </div>


                            <div class="clear"></div>






















            <?php
            // to slow query
//$category_to_product_query=tep_db_query("select pc.*,c.categories_name from ".TABLE_PRODUCTS_TO_CATEGORIES." pc,".TABLE_CATEGORIES_DESCRIPTION." c where products_id = '".(int)$_GET['products_id']."' and c.categories_id=pc.categories_id and c.language_id='".(int)$languages_id."'");
//if(tep_db_num_rows($category_to_product_query)>0) {
//print('<ul class="also_listed"><li><b>Listed in:</b></li>');
//while($category_to_product=tep_db_fetch_array($category_to_product_query)) {
//print('<li><a href="'.tep_href_link(FILENAME_DEFAULT,tep_get_path($category_to_product['categories_id'])).'"><span>'.$category_to_product['categories_name'].'</span></a></li>');
//}
//print('</ul><div class="clear"></div>');
//}
            ?>
                            <?php
                        }
                        ?>


                        <?php
                    }
                    ?>
  <?php
            if (YMM_DISPLAY_DATA_ON_PRODUCT_INFO_PAGE == 'Yes') {
                ?>
                                <!-- YMM BOF -->
                                <p>
                                <table width="100%" class="table table-bordered" >
  <tr class="active">
                                    <td><b> <?php
                echo TEXT_PRODUCTS_CAR_HEADING;
                ?></b></td>

                                        <th><?php
                                            echo TEXT_PRODUCTS_CAR_MAKE;
                                            ?></th>
                                        <th><?php
                            echo TEXT_PRODUCTS_CAR_MODEL;
                                            ?></th>
                                        <th><?php
                            echo TEXT_PRODUCTS_CAR_YEARS;
                                            ?></th>
                                    </tr>
                                            <?php
                                            if (isset($_GET['products_id']) && $_GET['products_id'] != '') {
                                                $q = tep_db_query("select * from products_ymm where products_id = " . (int) $_GET['products_id']);
                                                if (tep_db_num_rows($q) > 0) {
                                                    while ($r = tep_db_fetch_array($q)) {
                                                        echo '<tr>

		<td class="dataTableContentLow">' . ($r['products_car_make'] != '' ? $r['products_car_make'] : 'all') . '</td>

		<td class="dataTableContentLow">' . ($r['products_car_model'] != '' ? $r['products_car_model'] : 'all') . '</td>

		<td class="dataTableContentLow">' . $r['products_car_year_bof'] . ' - ' . $r['products_car_year_eof'] . '</td>

		</tr>';
                                                    }
                                                } else {
                                                    echo '<tr><td class="dataTableContentLow" colspan="4">Universal Product</td></tr>';
                                                }
                                            }
                                            ?>
                                </table></p>
                                <!-- YMM EOF -->
                                    <?php
                                }
                                ?>

                            <div class="clear"></div>
                    <!--          Get iew fot    --> <?php
                    if (!tep_session_is_registered('sppc_customer_group_id')) {
                        $customer_group_id = '0';
                    } else {
                        $customer_group_id = $sppc_customer_group_id;
                    }
                    if ($customer_group_id != '0') {
                        $products_extra_images_query = tep_db_query("select distinct p.products_id, p.products_image, pd.products_name, p.products_tax_class_id, IF(pg.customers_group_price IS NOT NULL, pg.customers_group_price, p.products_price) as products_price from " . TABLE_PRODUCTS_XSELL . " xp, " . TABLE_PRODUCTS . " p LEFT JOIN " . TABLE_PRODUCTS_GROUPS . " pg using(products_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd where xp.products_id = '" . $_GET['products_id'] . "' and xp.xsell_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_status = '1' and pg.customers_group_id = '" . $customer_group_id . "' order by sort_order asc limit " . MAX_DISPLAY_ALSO_PURCHASED);
                    } else {
                        $products_extra_images_query = tep_db_query("select distinct p.products_id, p.products_image, pd.products_name, p.products_tax_class_id, products_price from " . TABLE_PRODUCTS_XSELL . " xp, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where xp.products_id = '" . $_GET['products_id'] . "' and xp.xsell_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_status = '1' order by sort_order asc limit " . MAX_DISPLAY_ALSO_PURCHASED);
                    }
                    ?>
                    <?php
                    /* Begin product_previous_next */
//	if (($product_check['total'] > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
//		include (DIR_WS_INCLUDES . 'products_next_previous.php');
//	}
                    /* End product_previous_next */
                    ?>

                    <!-- AddThis Smart Layers BEGIN -->
                    <!-- Go to http://www.addthis.com/get/smart-layers to customize -->
                    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-5268691e756ff8b7"></script>
                    <script type="text/javascript">
                                addthis.layers({
                                    'theme': 'transparent',
                                    'share': {
                                        'position': 'left',
                                        'numPreferredServices': 5
                                    },
                                    'recommended': {}
                                });
                    </script>
                    <!-- AddThis Smart Layers END -->

                    <div class="clear"></div>

                    <span class="previews"> <?php
                include (DIR_WS_MODULES . 'product_reviews_info.php');
                ?></span>
                        <?php
                        /* Begin product_previous_next */
                        if (($product_check['total'] > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
                            include (DIR_WS_INCLUDES . 'products_next_previous.php');
                        }
                        /* End product_previous_next */
                        ?>



                    <div class="clear"></div>

                    </div>

    <?php
    $num_products_xsell = tep_db_num_rows($products_extra_images_query);
    if ($num_products_xsell > 0) {
        ?>
                        <?php
                    }
                    ?>
                    <div class="xsell col-lg-12 col-md-12 col-sm-12 col-xs-12"> <?php
                    include (DIR_WS_MODULES . 'auto_xsell_products.php');
                    ?></div>
                        <?php
                    }
                    ?>









     		   </div>
     </div>
    </form>

