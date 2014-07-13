           
            <?php
            if (PRODUCT_LIST_NUMCOL == 1) {
                include(dirname(__FILE__) . '/product_listing.php');
            } else {
                if (!tep_session_is_registered('sppc_customer_group_id')) {
                    $customer_group_id = '0';
                } else {
                    $customer_group_id = $sppc_customer_group_id;
                }
                $list_box_contents = array();
                $list_box_contents[] = array('params' => '');
                $cur_row = sizeof($list_box_contents) - 1;
                for ($col = 0, $n = sizeof($column_list); $col < $n; $col++) {
                    switch ($column_list[$col]) {
                        case 'PRODUCT_LIST_MULTIPLE':
                            $add_multiple = "1";
                            echo '';
                            break;
                    }
                }
                ?>


                <?php
                //	echo '' . $listing_split -> display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y')));

                echo '
		
	
 <div class="row">
<div class="col-lg-4 col-md-4 col-sm-6 col-xs-8 content_sortPagiBar">


<form name="sort_dropdown" method="get" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action'))) . '" class="">
				
			';

                if (isset($_GET['manufacturers_id'])) {
                    $manufacture = "&manufacturers_id=" . $_GET['manufacturers_id'];
                    $options = array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES));
                } else {
                    echo tep_draw_hidden_field('cPath', $cPath);
                    $manufacture = "";
                    $options = array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS));
                }
                if (isset($_GET['search_in_description']) && $_GET['search_in_description'] == 1)
                    $searchDes = '&search_in_description=1';
                else
                    $searchDes = '';
                echo tep_draw_hidden_field('sort_by', isset($_GET['sort_by']) ? $_GET['sort_by'] : '');
                $options_sort[] = array('id' => 'sortorder', 'text' => 'Sort By');
                $options_sort[] = array('id' => 'title', 'text' => 'Title');
                $options_sort[] = array('id' => 'low', 'text' => 'Price Low To High');
                $options_sort[] = array('id' => 'high', 'text' => 'Price High To Low');
                echo tep_draw_pull_down_menu('sort_id', $options_sort, (isset($_GET['sort_id']) ? $_GET['sort_id'] : ''), 'onchange="sortBy(\'' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('sort_id', 'sort', 'page', 'keywords', 'manufacturers_id', 'search_in_description'))) . '?keywords=' . (isset($_GET['keywords']) ? $_GET['keywords'] : '') . $manufacture . $searchDes . '&page=\')"');
                echo tep_hide_session_id() . ' 
				
				</form>
                        

</div>




 <div class="col-lg-8 col-md-8 col-sm-6 col-xs-4 content_sortPagiBar pull-right" style="text-align:right;">
    <div id="productsview" class="btn-group">
			<a rel="view-grid" href="#" class="btn visible-lg hidden-xs"><i class="fa fa-th"></i></a>
			<a rel="view-list" href="#" class="btn active hidden-xs"><i class="fa fa-th-list"></i></a>
                        <!--
<button class="btn btn-default ymm-link hidden-xs" data-toggle="modal" data-target="#myModal" id="ymm-link">
Advanced Sort</button> -->
 ' . $sts->template['search'] . '


    </div>
 </div>
                     


 
							 



						<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">

									<div class="modal-header">
										 

										<ul class="nav nav-pills pull-right">
										 
											<li class="ymm-link active">
												<a href="#ymmodel" data-toggle="pill">Year Make Model</a>
											</li>
										</ul>

										<h4 class="modal-title" id="myModalLabel">Advanced Sort</h4>
									</div>

									<div class="modal-body">
										<div class="row-fluid">
											<div class="tab-content">

 
		<div class="tab-pane active" id="ymmodel">
			<div class="controls controls-row">

  	  		            
						
	  		            ' . $sts->template['year_make_model']
                . '
	  		          		</div>
		</div>

		</div>' . "\n";
                ?>






                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Close
                    </button>
                </div>

                </div> </div>

                </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

  </div>






 



                <?php
                $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');
                if (($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
                    ?>	





                    <?php
                }
                $list_box_contents = array();
                if ($listing_split->number_of_rows > 0) {
                    if (PRODUCT_LIST_NUMCOL <= 0) {
                        $colnum = 3;
                        $tdsize = floor(100 / 3) - 10;
                    } else {
                        $colnum = PRODUCT_LIST_NUMCOL;
                        $tdsize = floor(100 / PRODUCT_LIST_NUMCOL) - 10;
                    }
                    $row = 0;
                    $column = 0;
                    $listing_query = tep_db_query($listing_split->sql_query);
                    $no_of_listings = tep_db_num_rows($listing_query);
                    if (!tep_session_is_registered('sppc_customer_group_id')) {
                        $customer_group_id = '0';
                    } else {
                        $customer_group_id = $sppc_customer_group_id;
                    }
                    $default_settings = array('products_price1' => '0.0000', 'products_price2' => '0.0000', 'products_price3' => '0.0000', 'products_price4' => '0.0000', 'products_price5' => '0.0000', 'products_price6' => '0.0000', 'products_price7' => '0.0000', 'products_price8' => '0.0000', 'products_price1_qty' => '0', 'products_price2_qty' => '0', 'products_price3_qty' => '0', 'products_price4_qty' => '0', 'products_price5_qty' => '0', 'products_price6_qty' => '0', 'products_price7_qty' => '0', 'products_price8_qty' => '0', 'products_qty_blocks' => '1');
                    while ($_listing = tep_db_fetch_array($listing_query)) {
                        $_new_listing = array_merge($_listing, $default_settings);
                        $listing[] = $_new_listing;
                        $list_of_prdct_ids[] = $_listing['products_id'];
                    }
                    $select_list_of_prdct_ids = "products_id = '" . $list_of_prdct_ids[0] . "' ";
                    if ($no_of_listings > 1) {
                        for ($n = 1; $n < count($list_of_prdct_ids); $n++) {
                            $select_list_of_prdct_ids .= "or products_id = '" . $list_of_prdct_ids[$n] . "' ";
                        }
                    }
                    if ($customer_group_id == '0') {
                        $retail_price_break_query = tep_db_query("select p.products_id, p.products_price1, p.products_price2, p.products_price3, p.products_price4, p.products_price5, p.products_price6, p.products_price7, p.products_price8, p.products_price1_qty, p.products_price2_qty, p.products_price3_qty, p.products_price4_qty, p.products_price5_qty, p.products_price6_qty, p.products_price7_qty, p.products_price8_qty, p.products_qty_blocks from " . TABLE_PRODUCTS . " p where " . $select_list_of_prdct_ids . "");
                        while ($rp_break = tep_db_fetch_array($retail_price_break_query)) {
                            for ($u = 0; $u < $no_of_listings; $u++) {
                                if ($rp_break['products_id'] == $listing[$u]['products_id']) {
                                    $listing[$u]['products_price1'] = $rp_break['products_price1'];
                                    $listing[$u]['products_price2'] = $rp_break['products_price2'];
                                    $listing[$u]['products_price3'] = $rp_break['products_price3'];
                                    $listing[$u]['products_price4'] = $rp_break['products_price4'];
                                    $listing[$u]['products_price5'] = $rp_break['products_price5'];
                                    $listing[$u]['products_price6'] = $rp_break['products_price6'];
                                    $listing[$u]['products_price7'] = $rp_break['products_price7'];
                                    $listing[$u]['products_price8'] = $rp_break['products_price8'];
                                    $listing[$u]['products_price1_qty'] = $rp_break['products_price1_qty'];
                                    $listing[$u]['products_price2_qty'] = $rp_break['products_price2_qty'];
                                    $listing[$u]['products_price3_qty'] = $rp_break['products_price3_qty'];
                                    $listing[$u]['products_price4_qty'] = $rp_break['products_price4_qty'];
                                    $listing[$u]['products_price5_qty'] = $rp_break['products_price5_qty'];
                                    $listing[$u]['products_price6_qty'] = $rp_break['products_price6_qty'];
                                    $listing[$u]['products_price7_qty'] = $rp_break['products_price7_qty'];
                                    $listing[$u]['products_price8_qty'] = $rp_break['products_price8_qty'];
                                    $listing[$u]['products_qty_blocks'] = $rp_break['products_qty_blocks'];
                                }
                            }
                        }
                    }
                    if ($customer_group_id != '0') {
                        $pg_query = tep_db_query("select pg.products_id, customers_group_price as price, pg.products_price1, pg.products_price2, pg.products_price3, pg.products_price4, pg.products_price5, pg.products_price6, pg.products_price7, pg.products_price8, pg.products_price1_qty, pg.products_price2_qty, pg.products_price3_qty, pg.products_price4_qty, pg.products_price5_qty, pg.products_price6_qty, pg.products_price7_qty, pg.products_price8_qty, pg.products_qty_blocks from " . TABLE_PRODUCTS_GROUPS . " pg where (" . $select_list_of_prdct_ids . ") and pg.customers_group_id = '" . $customer_group_id . "' ");
                        while ($pg_array = tep_db_fetch_array($pg_query)) {
                            $new_prices[] = array('products_id' => $pg_array['products_id'], 'products_price' => $pg_array['price'], 'specials_new_products_price' => '', 'final_price' => $pg_array['price'], 'products_price1' => $pg_array['products_price1'], 'products_price2' => $pg_array['products_price2'], 'products_price3' => $pg_array['products_price3'], 'products_price4' => $pg_array['products_price4'], 'products_price5' => $pg_array['products_price5'], 'products_price6' => $pg_array['products_price6'], 'products_price7' => $pg_array['products_price7'], 'products_price8' => $pg_array['products_price8'], 'products_price1_qty' => $pg_array['products_price1_qty'], 'products_price2_qty' => $pg_array['products_price2_qty'], 'products_price3_qty' => $pg_array['products_price3_qty'], 'products_price4_qty' => $pg_array['products_price4_qty'], 'products_price5_qty' => $pg_array['products_price5_qty'], 'products_price6_qty' => $pg_array['products_price6_qty'], 'products_price7_qty' => $pg_array['products_price7_qty'], 'products_price8_qty' => $pg_array['products_price8_qty'], 'products_qty_blocks' => $pg_array['products_qty_blocks']);
                        }
                        for ($x = 0; $x < $no_of_listings; $x++) {
                            if (!empty($new_prices)) {
                                for ($i = 0; $i < count($new_prices); $i++) {
                                    if ($listing[$x]['products_id'] == $new_prices[$i]['products_id']) {
                                        $listing[$x]['products_price'] = $new_prices[$i]['products_price'];
                                        $listing[$x]['final_price'] = $new_prices[$i]['final_price'];
                                        $listing[$x]['products_price1'] = $new_prices[$i]['products_price1'];
                                        $listing[$x]['products_price2'] = $new_prices[$i]['products_price2'];
                                        $listing[$x]['products_price3'] = $new_prices[$i]['products_price3'];
                                        $listing[$x]['products_price4'] = $new_prices[$i]['products_price4'];
                                        $listing[$x]['products_price5'] = $new_prices[$i]['products_price5'];
                                        $listing[$x]['products_price6'] = $new_prices[$i]['products_price6'];
                                        $listing[$x]['products_price7'] = $new_prices[$i]['products_price7'];
                                        $listing[$x]['products_price8'] = $new_prices[$i]['products_price8'];
                                        $listing[$x]['products_price1_qty'] = $new_prices[$i]['products_price1_qty'];
                                        $listing[$x]['products_price2_qty'] = $new_prices[$i]['products_price2_qty'];
                                        $listing[$x]['products_price3_qty'] = $new_prices[$i]['products_price3_qty'];
                                        $listing[$x]['products_price4_qty'] = $new_prices[$i]['products_price4_qty'];
                                        $listing[$x]['products_price5_qty'] = $new_prices[$i]['products_price5_qty'];
                                        $listing[$x]['products_price6_qty'] = $new_prices[$i]['products_price6_qty'];
                                        $listing[$x]['products_price7_qty'] = $new_prices[$i]['products_price7_qty'];
                                        $listing[$x]['products_price8_qty'] = $new_prices[$i]['products_price8_qty'];
                                        $listing[$x]['products_qty_blocks'] = $new_prices[$i]['products_qty_blocks'];
                                    }
                                }
                            }
                            $listing[$x]['specials_new_products_price'] = '';
                            $listing[$x]['final_price'] = $listing[$x]['products_price'];
                        }
                    }
                    $specials_query = tep_db_query("select products_id, specials_new_products_price from " . TABLE_SPECIALS . " where (" . $select_list_of_prdct_ids . ") and status = '1' and customers_group_id = '" . $customer_group_id . "'");
                    while ($specials_array = tep_db_fetch_array($specials_query)) {
                        $new_s_prices[] = array('products_id' => $specials_array['products_id'], 'products_price' => '', 'specials_new_products_price' => $specials_array['specials_new_products_price'], 'final_price' => $specials_array['specials_new_products_price']);
                    }
                    for ($x = 0; $x < $no_of_listings; $x++) {
                        if (!empty($new_s_prices)) {
                            for ($i = 0; $i < count($new_s_prices); $i++) {
                                if ($listing[$x]['products_id'] == $new_s_prices[$i]['products_id']) {
                                    $listing[$x]['specials_new_products_price'] = $new_s_prices[$i]['specials_new_products_price'];
                                    $listing[$x]['final_price'] = $new_s_prices[$i]['final_price'];
                                }
                            }
                        }
                    }
                    print('');
                    $row = 0;
                    $col1 = 0;
                    $count = 1;
                    $rowcount_value = 3;
                    $rowcount = 1;
                    $total = $no_of_listings;
                    $ismulti = 0;
                    for ($x = 0; $x < $no_of_listings; $x++) {
                        $reviews_avg_query = tep_db_query(" SELECT count( `reviews_id` ) countr, sum( `reviews_rating` ) sumrating FROM reviews WHERE `products_id` =" . $listing[$x]['products_id']);
                        $reviews_avg = tep_db_fetch_array($reviews_avg_query);
                        if ($reviews_avg['countr'] > 0)
                            $star_rating = (int) ($reviews_avg['sumrating'] / $reviews_avg['countr']);
                        $rows++;
                        if (($rows / 2) == floor($rows / 2)) {
                            $list_box_contents[] = array('params' => '');
                        } else {
                            $list_box_contents[] = array('params' => '');
                        }
                        $cur_row = sizeof($list_box_contents) - 1;
                        for ($col = 0, $n = sizeof($column_list); $col < $n; $col++) {
                            $lc_align = $lc_valign = $lc_text = '';
                            switch ($column_list[$col]) {
                                case 'PRODUCT_LIST_MODEL':

                                    $lc_text = '<span class="label label-default grid-view-none"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing[$x]['products_id']) . '">' . $listing[$x]['products_model'] . '</a></span>';
                                    break;
                                case 'PRODUCT_LIST_NAME':

                                    if (isset($_GET['manufacturers_id'])) {
                                        $prod_name = '<a class="" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $_GET['manufacturers_id'] . '&products_id=' . $listing[$x]['products_id']) . '">' . $listing[$x]['products_name'] . '</a>';
                                    } else {
                                        $prod_name = '<a class="" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing[$x]['products_id']) . '">' . $listing[$x]['products_name'] . '</a>';
                                    }
                                    break;
                                case 'PRODUCT_LIST_MANUFACTURER':

                                    $lc_text = '<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing[$x]['manufacturers_id']) . '">' . $listing[$x]['manufacturers_name'] . '</a>';
                                    break;
                                case 'PRODUCT_LIST_PRICE':

                                    $pf->parse($listing[$x]);
                                    $prod_price = $pf->getPriceStringShort();
                                    break;
                                case 'PRODUCT_LIST_QUANTITY':

                                    $lc_text = '' . $listing[$x]['products_quantity'] . '';
                                    break;
                                case 'PRODUCT_LIST_WEIGHT':

                                    $lc_text = '' . $listing[$x]['products_weight'] . '';
                                    break;
                                case 'PRODUCT_LIST_IMAGE':
                                    $imgsize = @getimagesize(DIR_WS_IMAGES . $listing[$x]['products_image']);
                                    $img_info = explode("\"", $imgsize[3]);
                                    if (SMALL_IMAGE_WIDTH != '') {
                                        $img_width = SMALL_IMAGE_WIDTH;
                                    }
                                    if (SMALL_IMAGE_WIDTH != '') {
                                        $img_width = SMALL_IMAGE_WIDTH;
                                    } else {
                                        $img_width = SMALL_IMAGE_WIDTH;
                                    }
                                    if (SMALL_IMAGE_HEIGHT != '') {
                                        $img_height = SMALL_IMAGE_HEIGHT;
                                    } else {
                                        $img_height = '';
                                    }


                                    if (isset($_GET['manufacturers_id'])) {
                                        $prod_img = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $_GET['manufacturers_id'] . '&products_id=' . $listing[$x]['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing[$x]['products_image'], $listing[$x]['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
                                    } else {
                                        $prod_img = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing[$x]['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing[$x]['products_image'], $listing[$x]['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
                                    }
                                    break;
                                case 'PRODUCT_LIST_BUY_NOW':

                                    $lc_text = '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing[$x]['products_id']) . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a>';
                                    $prod_btn = '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing[$x]['products_id']) . '">' . "Buy Now" . '</a>';
                                    break;
                            }
                            $product_contents[] = $lc_text;
                        }
                        if (!empty($listing[$x]['map_price']) && $listing[$x]['map_price'] != "0.00") {
                            if (isset($_SESSION['customers_email_address'])) {
                                $whats_new_price = $prod_price . '';
                                $whats_new_price .= '<span class="msrp_name">MSRP:</span> <span class="msrp_price">' . $currencies->display_price($listing[$x]['msrp_price'], tep_get_tax_rate($listing[$x]['products_tax_class_id'])) . '</span><br />

			<span class="map_name">MAP Price:</span> <span class="map_price">' . $currencies->display_price($listing[$x]['map_price'], tep_get_tax_rate($listing[$x]['products_tax_class_id'])) . '</span>';
                            } else {
                                $whats_new_price = '<span class="msrp_name">MSRP:</span> <span class="msrp_price">' . $currencies->display_price($listing[$x]['msrp_price'], tep_get_tax_rate($listing[$x]['products_tax_class_id'])) . '</span>

			<span class="map_name">MAP Price:</span> <span class="map_price">' . $currencies->display_price($listing[$x]['map_price'], tep_get_tax_rate($listing[$x]['products_tax_class_id'])) . '</span>';
                            }
                            if ($_SESSION['customers_email_address'] == '') {
                                $whats_new_price .= '

			<span class="ourprice_name">Our Price:</span> <span class="our_price_price"><a href="login.php"><i class="fa fa-lock"></i> Login for price Price</a></span>';
                            }
                        } elseif (isset($listing[$x]['msrp_price']) && $listing[$x]['msrp_price'] != "0.00") {
                            $whats_new_price = $prod_price . '<br />
			<span class="msrp_name">MSRP:</span> <span class="msrp_price">' . $currencies->display_price($listing[$x]['msrp_price'], tep_get_tax_rate($listing[$x]['products_tax_class_id'])) . '</span>';
                        } else
                            $whats_new_price = $prod_price;
                        if (!empty($listing[$x]['products_url']) && isset($_SESSION['customers_email_address'])) {
                            $multibuy = '';
                            $newArea = '<a class="btn btn-primary" href="' . $listing[$x]['products_url'] . '" title="' . $listing[$x]['products_url'] . '"  >Partner Buy </a>
			<a class="btn btn-info grid-view-none" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing[$x]['products_id']) . '">More</a>
			';
                        } elseif (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '') {
                            $newArea = '';
                            $multibuy = '';
                        } elseif ($listing[$x]['products_price'] > 0) {
                            $newArea = '
                         
                            
			    
		
                      <label class="sr-only" for="exampleInputEmail2">Qty</label>
                 
       <div class="form-group">                
<input name=qty type="text" class="form-control grid-view-none" value="1" size="4"/></div>
   <input class="btn btn-primary" type="submit" value="Add to Cart"/>
   <div class="clear"></div>
			
			
			
			 ';
                            if (!tep_has_product_attributes($listing[$x]['products_id'])) {
                                $ismulti = 1;
                                $multibuy = '<span class="multibuy-select pull-right grid-view-none">Add Multi <input type="checkbox" name="multibuy[]" value="' . $listing[$x]['products_id'] . '" /></span>';
                            } else {
                                $multibuy = '';
                            }
                            $product_info_query = tep_db_query("select p.products_id,p.map_price, p.msrp_price,pd.products_info_title,p.products_status,pd.products_info_desc, pd.products_name, pd.products_description, pd.products_short, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, NULL as specials_new_products_price, p.products_price1, p.products_price2, p.products_price3, p.products_price4, p.products_price5, p.products_price6, p.products_price7, p.products_price8, p.products_price1_qty, p.products_price2_qty, p.products_price3_qty, p.products_price4_qty, p.products_price5_qty, p.products_price6_qty, p.products_price7_qty, p.products_price8_qty, p.products_qty_blocks, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int) $listing[$x]['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int) $languages_id . "'");
                            $product_info = tep_db_fetch_array($product_info_query);
                            $pf->loadProductSppc((int) $listing[$x]['products_id'], (int) $languages_id, $product_info);
                            $qtypricing = $pf->getQtyPriceTable();
                        } else {
                            $newArea = '';
                            $multibuy = '';
                        }
                        if (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '') {
                            $whats_new_price = "";
                        } elseif ($listing[$x]['products_price'] > 0) {
                            $whats_new_price = $prod_price;
                        } else {
                            $urlContactUs = "email_for_price.php?product_name=" . addslashes(addslashes($listing[$x]['products_name'])) . "&products_model=" . $listing[$x]['products_model'];
                            $whats_new_price = '<a class="text-success" href="' . $urlContactUs . '">Email for Price</a>';
                        }
                        $list_box_contents[$row][$column] = array('align' => '', 'valign' => $lc_valign, 'params' => '', 'text' => '' . $prod_name . '' . $prod_img . ' ' . $prod_price . '' . $prod_btn . '');
                        print(' ');




                        print('');
                        print('' . tep_draw_form('frm' . $listing[$x]['products_id'], tep_href_link(basename($PHP_SELF)), 'get') . tep_draw_hidden_field("products_id", $listing[$x]['products_id']) . tep_draw_hidden_field("action", "buy_now") . tep_draw_hidden_field("sort", (isset($_GET['sort']) ? $_GET['sort'] : '')) . tep_draw_hidden_field(tep_session_name(), tep_session_id()) . '');
                        if (!empty($_GET['cPath']))
                            print tep_draw_hidden_field("cPath", $_GET['cPath']);
                        if ($listing[$x]['products_model'])
                            $model = '<span class="label label-default grid-view-none"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing[$x]['products_id']) . '">' . $listing[$x]['products_model'] . '</a></span>';
                        else
                            $model = '';
                        $extra_fields_query = tep_db_query("
			SELECT pef.products_extra_fields_name as name, ptf.products_extra_fields_value as value ,pef.products_extra_fields_status as status
			FROM " . TABLE_PRODUCTS_EXTRA_FIELDS . " pef
			LEFT JOIN  " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " ptf
			ON ptf.products_extra_fields_id=pef.products_extra_fields_id
			WHERE ptf.products_id=" . $listing[$x]['products_id'] . " and ptf.products_extra_fields_value<>'' and (pef.languages_id='0' or pef.languages_id='" . $languages_id . "')
			ORDER BY products_extra_fields_order");
                        if (tep_db_num_rows($extra_fields_query) > 0) {
                            $extraField = '<ul class="list-group grid-view-none">';
                        }
                        while ($extra_fields = tep_db_fetch_array($extra_fields_query)) {
                            if (!$extra_fields['status'])
                                continue;
                            $extraField .= '<li class="list-group-item"><b>' . $extra_fields['name'] . ':</b> ';
                            $extraField .= '' . $extra_fields['value'] . '</li>';
                        }
                        if (tep_db_num_rows($extra_fields_query) > 0) {
                            $extraField .= '</ul>';
                        } else
                            $extraField = '';

                        print('
			
			<div class="media productitem clear">
			 <div class="">
			  <div class="media-image col-lg-3 col-md-3 col-sm-3 col-xs-12">');

                        if ($listing[$x]['products_image'] != "" && file_exists(DIR_WS_IMAGES . '/' . $listing[$x]['products_image'])) {
                            $imgsize = @getimagesize(DIR_WS_IMAGES . $listing[$x]['products_image']);
                            if ($imgsize['mime'] == 'image/bmp') {
                                $imag_var = '<img src=' . DIR_WS_IMAGES . $listing[$x]['products_image'] . ' height=' . SMALL_IMAGE_HEIGHT . ' width=' . SMALL_IMAGE_WIDTH . ' />';
                            } else {
                                $imag_var = tep_image(DIR_WS_IMAGES . $listing[$x]['products_image'], $listing[$x]['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
                            }

                            print ('<a class="no-border-grid img-responsive" style="" href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing[$x]['products_id']) . '">' . $imag_var . '</a>'
                                    . ''
                                    . ''
                                    . '');
                        } else {

                            print ('<div class="well" style=""><center><a class="" style="" href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing[$x]['products_id']) . '"><i class="fa fa-link fa-5x"></i></a></center></div>');
                        };

                        print ('   
                            

<span class="text-success lead">' . $whats_new_price . '</span></div>

		   
			
		         <div class="media-body col-lg-9 col-md-9 col-sm-9 col-xs-12">
               
 
' . $multibuy .
                                '<h3 class="">' . $prod_name . '</h3>
			<span class="grid-view-none">' . (!empty($listing[$x]['products_short']) ? '' . $listing[$x]['products_short'] . '' : '') . '</span>

			 
			
			' . $extraField . '

					
		 ');

                        if (!empty($listing[$x]['products_info_title'])) {
                            print('
			
		 
  
			
			
			<p class="grid-view-none ship-time"><i class="fa fa-truck"></i> Ships in ' . $listing[$x]['products_info_title']) . ' Days </p>



 

';
                        }


 print(''
         . ''
         . ''
         . ''
         . ''
         . ''
         . ' 
'
         . '<div class="tags-product-list"><p>'. $model . '');


                        if ($listing[$x]['manufacturers_name'] != "") {
                            if (!isset($listing[$x]['manufacturers_id'])) {
                                $mfg_query = tep_db_query("select manufacturers_id from " . TABLE_MANUFACTURERS . " where manufacturers_name='" . $listing[$x]['manufacturers_name'] . "'");
                                if (tep_db_num_rows($mfg_query) > 0) {
                                    $mfg = tep_db_fetch_array($mfg_query);
                                    $listing[$x]['manufacturers_id'] = $mfg['manufacturers_id'];
                                }
                            }
                            print('
	 <span class="label label-default grid-view-none"><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing[$x]['manufacturers_id']) . '">' . $listing[$x]['manufacturers_name'] . '</a></span>');
                        }

                     //   $category_to_product_query = tep_db_query("select pc.*,c.categories_name from " . TABLE_PRODUCTS_TO_CATEGORIES . " pc," . TABLE_CATEGORIES_DESCRIPTION . " c where products_id = '" . (int) $listing[$x]['products_id'] . "' and c.categories_id=pc.categories_id");

                     //   if (tep_db_num_rows($category_to_product_query) > 1) {


                      //      while ($category_to_product = tep_db_fetch_array($category_to_product_query)) {

                      //          print('<span class="label label-default grid-view-none"><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_path($category_to_product['categories_id'])) . '">' . $category_to_product['categories_name'] . '</a></span> ');
                      //      }
                            
                       //     }

 print('</p> </div>');
                            if (!empty($qtypricing)) {
                                echo '<p class="grid-view-none qty-price">' . $qtypricing . '</p>';
                            }


                            print( '<div class="clear"></div> '
                                    . '' . $newArea . '	'
                                    . '<div class="clear"></div>');
                        

	if ($reviews_avg['countr'] > 0):
		echo '<p class="grid-view-none pull-left star-rating">';
		for ($s = 0; $s < $star_rating; $s++){
			echo '<i class="fa fa-star"></i>';
		}
		echo '</p>';
	endif;

                            
//                            print(' <br><p class="pull-left grid-view-none">' . tep_image(DIR_WS_IMAGES . 'stars_' . $star_rating . '.gif', sprintf(BOX_REVIEWS_TEXT_OF_5_STARS, $star_rating)) . '</p>');
//                        }

                        print ' <a class="grid-view-none pull-right" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing[$x]['products_id']) . '">More Info...</a> ';

                        print('
			
			</div>
									
		
</div><div class="clear"></div>
<hr><div class="clear"></div> </div></form>	 
			
		
                       
                            ');
                    }
                    $column++;
                    $col1++;
                    if ($col1 > (PRODUCT_LIST_NUMCOL - 1) || $count == $total) {
                        if ($total == 2) {
                            print('');
                        }
                        print('

			');
                        $row++;
                        $col1 = 0;
                    }
                    $count++;

                if ($ismulti == 1) {
                    ?>
                    
                    <div class="productitem">	 

                        <div class="multi_buy_hide pull-right grid-view-none">
                            <div  class="multibuy productitem-list ui-widget ui-widget-content ui-corner-all " style="text-align: right; display: none;">
                                <input class="btn btn-primary grid-view-none" type="submit" value="AddMulti" onclick="addMulti()" />
                            </div>
                        </div>
                    </div>

                    <script type="text/javascript">
                        jQuery(document).ready(function() {
                            jQuery(".multibuy").show();
                        });
                        function addMulti() {
                            var multi_count = $("input[name='multibuy[]']:checked").size();
                            if (multi_count > 0) {
                                formurl = document.location.href;
                                if (document.location.href.indexOf("?") < 0)
                                    formurl = document.location.href + "?action=add_multi";
                                else
                                    formurl = document.location.href + "&action=add_multi";
                                $("body").append('<form action="' + formurl + '" method="post" id="multibuy-form"></form>');
                                $("input[name='multibuy[]']:checked").each(function() {
                                    var thisqty = $(this).closest("div.productdes").children(".qty").children("input").val();
                                    $("#multibuy-form").append('<input type="hidden" name="multibuy[' + $(this).val() + ']" value="' + thisqty + '" />');
                                });
                                $("#multibuy-form").submit();
                            } else {
                                return false;
                            }
                        }
                    </script>
<?php
				}
?>
                <div class="clear"></div>
                <hr>

        <?php
    } else {
        print('<span class="no_products">' . TEXT_NO_PRODUCTS . '</span>');
    }
    if (($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
        ?>

                    
                    <ul class="pagination pagination-centered">
        <?php
        echo '' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y')));
        ?>
                        <?php
                        echo '
' . "\n";
                        ?>
                    </ul>

                    <p>	<?php
                echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS);
                ?>
                    </p>


        <?php
        if (isset($add_multiple) && $add_multiple == "1") {
            ?>
                        <?php
                    }
                    ?>
                    <?php
                }
            }
            ?>

               
