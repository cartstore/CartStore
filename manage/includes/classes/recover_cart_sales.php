<?php
/*
 $Id: recover_cart_sales.php,v $
 Recover Cart Sales Tool v2.30
*/

  require_once(DIR_WS_LANGUAGES . $language . '/' . FILENAME_RECOVER_CART_SALES);
  require_once(DIR_WS_CLASSES . '/' . 'table_block.php');
  require_once(DIR_WS_CLASSES . '/' . 'box.php');

  class recover_cart_sales {
    function recover_cart_sales($basedays, $skipdays) {
      $this->basedays = $basedays;
      $this->skipdays = $skipdays;
      $this->infoBoxHeading = array();
      $this->infoBoxContents = array();
    }

    function getInfoBox() {
      $box = new box;
      return $box->infoBox($this->infoBoxHeading, $this->infoBoxContents);
    }

    function seadate($day)
    {
      $rawtime = strtotime("-".$day." days");
      $ndate = date("Ymd", $rawtime);
      return $ndate;
    }

    function cart_date_short($raw_date) {
      if ( ($raw_date == '00000000') || ($raw_date == '') ) return false;

      $year = substr($raw_date, 0, 4);
      $month = (int)substr($raw_date, 4, 2);
      $day = (int)substr($raw_date, 6, 2);

      if (@date('Y', mktime(0, 0, 0, $month, $day, $year)) == $year) {
        return date(DATE_FORMAT, mktime(0, 0, 0, $month, $day, $year));
      } else {
        return preg_replace('/2037' . '$/', $year, date(DATE_FORMAT, mktime(0, 0, 0, $month, $day, 2037)));
      }
    }

	  // This will return a list of customers with sessions. Handles either the mysql or file case
	  // Returns an empty array if the check sessions flag is not true (empty array means same SQL statement can be used)
	  function _GetCustomerSessions()
	  {
		  $cust_ses_ids = array();

		  if( RCS_CHECK_SESSIONS == 'true' )
		  {
			  if (STORE_SESSIONS == 'mysql')
			  {
				  // --- DB RECORDS ---
				  $sesquery = tep_db_query("select value from " . TABLE_SESSIONS . " where 1");
				  while ($ses = tep_db_fetch_array($sesquery))
				  {
					  if ( preg_match( "/customer_id[^\"]*\"([0-9]*)\"/", $ses['value'], $custval ) )
						  $cust_ses_ids[] = $custval[1];
				  }
			  }
			  else	// --- FILES ---
			  {
				  if( $handle = opendir( tep_session_save_path() ) )
				  {
					  while (false !== ($file = readdir( $handle )) )
					  {
						  if ($file != "." && $file != "..")
						  {
							  $file = tep_session_save_path() . '/' . $file;	// create full path to file!
							  if( $fp = fopen( $file, 'r' ) )
							  {
								  $val = fread( $fp, filesize( $file ) );
								  fclose( $fp );

								  if ( preg_match( "/customer_id[^\"]*\"([0-9]*)\"/", $val, $custval ) )
									  $cust_ses_ids[] = $custval[1];
							  }
						  }
					  }
					  closedir( $handle );
				  }
			  }
		  }
		  return $cust_ses_ids;
	  }


    /**
     * \brief Searches the database for incomplete sales. Result is used by admin page (html can be retrieved through getInfoBox) and
     * by cronjob (that actually only uses the returned array of customerid's.
     *
     * \return array of customerid's to send an email to
     */
    function processSearch() {
      global $PHP_SELF, $currencies, $languages_id;

      $custids = array();

      //Clear any content
      $this->infoBoxHeading = array();
      $this->infoBoxContents = array();

      //Compose the heading
      $this->infoBoxContents[] = array(
        'params' => 'class="dataTableHeadingRow" colspan="8"',
        'text' => tep_draw_form('', FILENAME_RECOVER_CART_SALES));

      $this->infoBoxContents[] = array(
        'params' => 'class="dataTableHeadingRow"',
        0 => array(
          'params' => 'class="dataTableHeadingContent" align="left" colspan="2" width="10%" nowrap',
          'text' => TABLE_HEADING_CONTACT),
        1 => array(
          'params' => 'class="dataTableHeadingContent" align="left" colspan="1" width="15%" nowrap',
          'text' => TABLE_HEADING_DATE),
        2 => array(
          'params' => 'class="dataTableHeadingContent" align="left" colspan="1" width="30%" nowrap',
          'text' => TABLE_HEADING_CUSTOMER),
        3 => array(
          'params' => 'class="dataTableHeadingContent" align="left" colspan="2" width="30%" nowrap',
          'text' => TABLE_HEADING_EMAIL),
        4 => array(
          'params' => 'class="dataTableHeadingContent" align="left" colspan="2" width="15%" nowrap',
          'text' => TABLE_HEADING_PHONE));

      $this->infoBoxContents[] = array(
        'params' => 'class="dataTableHeadingRow"',
        0 => array(
          'params' => 'class="dataTableHeadingContent" align="left" colspan="2" width="10%" nowrap',
          'text' => '&nbsp;'),
        1 => array(
          'params' => 'class="dataTableHeadingContent" align="left" colspan="1" width="15%" nowrap',
          'text' => TABLE_HEADING_MODEL),
        2 => array(
          'params' => 'class="dataTableHeadingContent" align="left" colspan="2" width="55%" nowrap',
          'text' => TABLE_HEADING_DESCRIPTION),
        3 => array(
          'params' => 'class="dataTableHeadingContent" align="center" colspan="1" width="5%" nowrap',
          'text' => TABLE_HEADING_QUANTY),
        4 => array(
          'params' => 'class="dataTableHeadingContent" align="right" colspan="1" width="5%" nowrap',
          'text' => TABLE_HEADING_PRICE),
        5 => array(
          'params' => 'class="dataTableHeadingContent" align="right" colspan="1" width="10%" nowrap',
          'text' => TABLE_HEADING_TOTAL));

      $cust_ses_ids = $this->_GetCustomerSessions();
      $bdate = $this->seadate($this->skipdays);
      $ndate = $this->seadate($this->basedays);

      $query1 = tep_db_query("select cb.customers_id cid,
                                  cb.products_id pid,
                                  cb.customers_basket_quantity qty,
                                  cb.customers_basket_date_added bdate,
                                  cus.customers_firstname fname,
                                  cus.customers_lastname lname,
                                  cus.customers_telephone phone,
                                  cus.customers_email_address email
                           from   " . TABLE_CUSTOMERS_BASKET . " cb,
                                  " . TABLE_CUSTOMERS . " cus
                           where  cb.customers_basket_date_added <= '" . $bdate . "' and
                         		    cb.customers_basket_date_added > '" . $ndate . "' and
                                  cus.customers_id not in ('" . implode(", ", $cust_ses_ids) . "') and
                                  cb.customers_id = cus.customers_id order by cb.customers_basket_date_added desc,
                                  cb.customers_id ");
      $results = 0;
      $curcus = "";
      $tprice = 0;
      $totalAll = 0;
      $first_line = true;
      $skip = false;

      $knt = mysql_num_rows($query1);
      for ($i = 0; $i <= $knt; $i++)
      {
        $inrec = tep_db_fetch_array($query1);

	      // If this is a new customer, create the appropriate HTML
        if ($curcus != $inrec['cid'])
        {
          // output line
          $totalAll += $tprice;

          if ($curcus != "" && !$skip) {
            $this->infoBoxContents[] = array(
              0 => array(
                'params' => 'class="dataTableContent" align="right" colspan="8"',
                'text' => '<b>' . TABLE_CART_TOTAL . '</b>' . $currencies->format($tprice)));
            $this->infoBoxContents[] = array(
              0 => array(
                'params' => 'class="dataTableContent" align="right" colspan="6"',
                'text' => '<a class="button" href="' . tep_href_link(FILENAME_RECOVER_CART_SALES, 'action=delete&customer_id=' . $curcus . '&tdate=' . $this->basedays . '&sdate=' . $this->skipdays) . '">' .  IMAGE_DELETE . '</a>'));
          }

          // set new cline and curcus
          $curcus = $inrec['cid'];

          if ($curcus != "")
		      {
			      $tprice = 0;

			      // change the color on those we have contacted add customer tag to customers
			      $fcolor = RCS_UNCONTACTED_COLOR;
			      $checked = 1;	// assume we'll send an email
			      $new = 1;
			      $skip = false;
			      $sentdate = "";
			      $beforeDate = RCS_CARTS_MATCH_ALL_DATES ? '0' : $inrect['bdate'];
			      $customer = $inrec['fname'] . " " . $inrec['lname'];
			      $status = "";

			      $donequery = tep_db_query("select * from ". TABLE_SCART ." where customers_id = '".$curcus."'");
			      $emailttl = $this->seadate(RCS_EMAIL_TTL);

			      if (mysql_num_rows($donequery) > 0) {
				      $ttl = tep_db_fetch_array($donequery);
				      if( $ttl )
				      {
					      if( tep_not_null($ttl['datemodified']) )	// allow for older scarts that have no datemodified field data
						      $ttldate = $ttl['datemodified'];
					      else
						      $ttldate = $ttl['dateadded'];

					      if ($emailttl <= $ttldate) {
						      $sentdate = $ttldate;
						      $fcolor = RCS_CONTACTED_COLOR;
						      $checked = 0;
						      $new = 0;
					      }
				      }
			      }

			      // See if the customer has purchased from us before
			      // Customers are identified by either their customer ID or name or email address
			      // If the customer has an order with items that match the current order, assume order completed, bail on this entry!
				$ccquery = tep_db_query('select orders_id, orders_status from ' . TABLE_ORDERS . ' where (customers_id = ' . (int)$curcus . ' OR customers_email_address like "' . $inrec['email'] .'" or customers_name like "' . $inrec['fname'] . ' ' . $inrec['lname'] . '") and date_purchased >= "' . $beforeDate . '"' );
				if (mysql_num_rows($ccquery) > 0)
			      {
				      // We have a matching order; assume current customer but not for this order
				      $customer = '<font color=' . RCS_CURCUST_COLOR . '><b>' . $customer . '</b></font>';

				      // Now, look to see if one of the orders matches this current order's items
				      while( $orec = tep_db_fetch_array( $ccquery ) )
				      {
					      $ccquery = tep_db_query( 'select products_id from ' . TABLE_ORDERS_PRODUCTS . ' where orders_id = ' . (int)$orec['orders_id'] . ' AND products_id = ' . (int)$inrec['pid'] );
					      if( mysql_num_rows( $ccquery ) > 0 )
					      {
						      // OK, we have a matching order; see if we should just skip this or show the status
						      if(RCS_SKIP_MATCHED_CARTS == 'true')
						      {
							      $skip = true;	// reset flag & break us out of the while loop!
							      break;
						      }
						      else
						      {
							      // It's rare for the same customer to order the same item twice, so we probably have a matching order, show it
							      $fcolor = RCS_MATCHED_ORDER_COLOR;
							      $ccquery = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = " . (int)$languages_id . " AND orders_status_id = " . (int)$orec['orders_status'] );

							      if( $srec = tep_db_fetch_array( $ccquery ) )
								      $status = ' [' . $srec['orders_status_name'] . ']';
							      else
								      $status = ' ['. TEXT_CURRENT_CUSTOMER . ']';
						      }
					      }
				      }

				      if( $skip )
					      continue;	// got a matched cart, skip to next one
			      }
			      $sentInfo = TEXT_NOT_CONTACTED;

			      if ($sentdate != '')
			        $sentInfo = $this->cart_date_short($sentdate);

            if(RCS_AUTO_CHECK == 'true' && $checked) {
              $custids[] = $inrec['cid'];
            }

            $this->infoBoxContents[] = array(
              'params' => 'bgcolor="' . $fcolor . '"',
              0 => array(
                'params' => 'class="dataTableContent" align="center" width="1%"',
                'text' => tep_draw_checkbox_field('custid[]', $curcus, RCS_AUTO_CHECK == 'true' ? $checked : 0)),
              1 => array(
                'params' => 'class="dataTableContent" align="left" width="9%" nowrap',
                'text' => '<b>' . $sentInfo . '</b>'),
              2 => array(
                'params' => 'class="dataTableContent" align="left" width="15%" nowrap',
                'text' => $this->cart_date_short($inrec['bdate'])),
              3 => array(
                'params' => 'class="dataTableContent" align="left" width="30%" nowrap',
                'text' => '<a href="' . tep_href_link(FILENAME_CUSTOMERS, 'search=' . $inrec['lname'], 'NONSSL') . '">' . $customer . '</a>' . $status),
              4 => array(
                'params' => 'class="dataTableContent" align="left" colspan="2" width="30%" nowrap',
                'text' => '<a href="' . tep_href_link('mail.php', 'selected_box=tools&customer=' . $inrec['email']) . '">' . $inrec['email'] . '</a>'),
              5 => array(
                'params' => 'class="dataTableContent" align="left" colspan="2" width="15%" nowrap',
                'text' => $inrec['phone']));
		      }
        }

	      // We only have something to do for the product if the quantity selected was not zero!
        if ($inrec['qty'] != 0)
        {
			    // Get the product information (name, price, etc)
			    $query2 = tep_db_query("select  p.products_price price,
													    p.products_tax_class_id taxclass,
													    p.products_model model,
													    pd.products_name name
										     from    " . TABLE_PRODUCTS . " p,
													    " . TABLE_PRODUCTS_DESCRIPTION . " pd,
													    " . TABLE_LANGUAGES . " l
										     where   p.products_id = '" . (int)$inrec['pid'] . "' and
													    pd.products_id = p.products_id and
													    pd.language_id = " . (int)$languages_id);
			    $inrec2 = tep_db_fetch_array($query2);

			    // Check to see if the product is on special, and if so use that pricing
			    $sprice = tep_get_products_special_price( $inrec['pid'] );
			    if( $sprice < 1 )
				    $sprice = $inrec2['price'];

			    // Some users may want to include taxes in the pricing, allow that. NOTE HOWEVER that we don't have a good way to get individual tax rates based on customer location yet!
			    if( RCS_INCLUDE_TAX_IN_PRICES  == 'true' )
				    $sprice += ($sprice * tep_get_tax_rate( $inrec2['taxclass'] ) / 100);
			    else if( RCS_USE_FIXED_TAX_IN_PRICES  == 'true' && RCS_FIXED_TAX_RATE > 0 )
				    $sprice += ($sprice * RCS_FIXED_TAX_RATE / 100);

			    // BEGIN OF ATTRIBUTE DB CODE
			    $prodAttribs = ''; // DO NOT DELETE

			    if (RCS_SHOW_ATTRIBUTES == 'true')
			    {
				    $attribquery = tep_db_query("select  cba.products_id pid,
															     po.products_options_name poname,
															     pov.products_options_values_name povname
												      from    " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " cba,
															     " . TABLE_PRODUCTS_OPTIONS . " po,
															     " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov,
															     " . TABLE_LANGUAGES . " l
												      where   cba.products_id ='" . $inrec['pid'] . "' and
			 												     cba.customers_id = " . $curcus . " and
			 												     po.products_options_id = cba.products_options_id and
															     pov.products_options_values_id = cba.products_options_value_id and
															     po.language_id = " . (int)$languages_id . " and
															     pov.language_id = " . (int)$languages_id
											      );
				    $hasAttributes = false;

				    if (tep_db_num_rows($attribquery))
				    {
				      $hasAttributes = true;
				      $prodAttribs = '<br>';

				      while ($attribrecs = tep_db_fetch_array($attribquery))
					     $prodAttribs .= '<small><i> - ' . $attribrecs['poname'] . ' ' . $attribrecs['povname'] . '</i></small><br>';
				    }
  			  }

			    // END OF ATTRIBUTE DB CODE
			    $tprice = $tprice + ($inrec['qty'] * $sprice);
			    $pprice_formated  = $currencies->format($sprice);
			    $tpprice_formated = $currencies->format(($inrec['qty'] * $sprice));

          $this->infoBoxContents[] = array(
            'params' => 'class="dataTableRow"',
            0 => array(
              'params' => 'class="dataTableContent" align="left" valign="top" colspan="2" width="12%" nowrap',
              'text' => '&nbsp;'),
            1 => array(
              'params' => 'class="dataTableContent" align="left" valign="top" width="13%" nowrap',
              'text' => $inrec2['model'] . '&nbsp;'),
            2 => array(
              'params' => 'class="dataTableContent" align="left" valign="top" colspan="2" width="55%" nowrap',
              'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=new_product_preview&read=only&pID=' . $inrec['pid'] . '&origin=' . FILENAME_RECOVER_CART_SALES . '?page=' . $_GET['page'], 'NONSSL') . '"><b>' . $inrec2['name'] . '</b></a>' . $prodAttribs),
            3 => array(
              'params' => 'class="dataTableContent" align="center" valign="top" width="5%" nowrap',
              'text' => $inrec['qty']),
            4 => array(
              'params' => 'class="dataTableContent" align="right" valign="top" width="5%" nowrap',
              'text' => $pprice_formated),
            5 => array(
              'params' => 'class="dataTableContent" align="right" valign="top" width="10%" nowrap',
              'text' => $tpprice_formated));
	      }
      }

      $totalAll_formated = $currencies->format($totalAll);

      $this->infoBoxContents[] = array(
        0 => array(
          'params' => 'class="dataTableContent" align="right" colspan="8"',
          'text' => '<hr align=right width=55><b>' . TABLE_GRAND_TOTAL . '</b>' . $totalAll_formated));

      //Removed textbox...
      //echo "<hr size=1 color=000080><b>". PSMSG ."</b><br>". tep_draw_textarea_field('message', 'soft', '80', '5') ."<br>";

      $this->infoBoxContents[] = array(
        0 => array(
          'params' => 'colspan="8"',
          'text' => tep_draw_selection_field('submit_button', 'submit', TEXT_SEND_EMAIL)));

      $this->infoBoxContents[] = array('text' => '</form>');

      return $custids;
    }

    /**
     *
     * \brief Sends an email for the incomplete sales for the given customer(id)s.
     *
     */
    function processEmail($custids) {
      global $currencies, $languages_id;

      //Clear any content
      $this->infoBoxHeading = array();
      $this->infoBoxContents = array();

      //Compose the heading
      $this->infoBoxContents[] = array(
        'params' => 'class="dataTableHeadingRow"',
        0 => array(
          'params' => 'class="dataTableHeadingContent" align="left" colspan="1" width="15%" nowrap',
          'text' => TABLE_HEADING_CUSTOMER),
        1 => array(
          'params' => 'class="dataTableHeadingContent" align="left" colspan="1" width="30%" nowrap',
          'text' => '&nbsp;'),
        2 => array(
          'params' => 'class="dataTableHeadingContent" align="left" colspan="1" width="25%" nowrap',
          'text' => '&nbsp;'),
        3 => array(
          'params' => 'class="dataTableHeadingContent" align="left" colspan="1" width="10%" nowrap',
          'text' => '&nbsp;'),
        4 => array(
          'params' => 'class="dataTableHeadingContent" align="left" colspan="1" width="10%" nowrap',
          'text' => '&nbsp;'),
        5 => array(
          'params' => 'class="dataTableHeadingContent" align="left" colspan="1" width="10%" nowrap',
          'text' => '&nbsp;'));

      $this->infoBoxContents[] = array(
        'params' => 'class="dataTableHeadingRow"',
        0 => array(
          'params' => 'class="dataTableHeadingContent" align="left" colspan="1" width="15%" nowrap',
          'text' => TABLE_HEADING_MODEL),
        1 => array(
          'params' => 'class="dataTableHeadingContent" align="left" colspan="2" width="55%" nowrap',
          'text' => TABLE_HEADING_DESCRIPTION),
        2 => array(
          'params' => 'class="dataTableHeadingContent" align="center" colspan="1" width="10%" nowrap',
          'text' => TABLE_HEADING_QUANTY),
        3 => array(
          'params' => 'class="dataTableHeadingContent" align="right" colspan="1" width="10%" nowrap',
          'text' => TABLE_HEADING_PRICE),
        4 => array(
          'params' => 'class="dataTableHeadingContent" align="right" colspan="1" width="10%" nowrap',
          'text' => TABLE_HEADING_TOTAL));

	    foreach ($custids as $cid)
	    {
        unset($email);

	      $query1 = tep_db_query("select cb.products_id pid,
                                        cb.customers_basket_quantity qty,
                                        cb.customers_basket_date_added bdate,
                                        cus.customers_firstname fname,
                                        cus.customers_lastname lname,
                                        cus.customers_email_address email
                              from      " . TABLE_CUSTOMERS_BASKET . " cb,
                                        " . TABLE_CUSTOMERS . " cus
                              where     cb.customers_id = cus.customers_id  and
                                        cus.customers_id = '".$cid."'
                              order by  cb.customers_basket_date_added desc ");

	      $knt = mysql_num_rows($query1);
	      for ($i = 0; $i < $knt; $i++)
	      {
          $inrec = tep_db_fetch_array($query1);

		      // set new cline and curcus
		      if ($lastcid != $cid) {
			      if ($lastcid != "") {

              $this->infoBoxContents[] = array(
                0 => array(
                  'params' => 'class="dataTableContent" align="right" colspan="6" nowrap',
                  'text' => '<b>' . TABLE_CART_TOTAL . '</b>' . $currencies->format($tprice)));

              $this->infoBoxContents[] = array(
                0 => array(
                  'params' => 'align="right" colspan="6"',
                  'text' => '<a class="button" href="' . tep_href_link(FILENAME_RECOVER_CART_SALES, "action=delete&customer_id=" . $cid . "&tdate=" . $this->basedays . "&sdate=" . $this->skipdays) . '">' .  IMAGE_DELETE . '</a>'));
			      }

            $this->infoBoxContents[] = array(
              0 => array(
                'params' => 'class="dataTableContent" align="left" colspan="6" nowrap',
                'text' => '<a href="' . tep_href_link(FILENAME_CUSTOMERS, 'search=' . $inrec['lname'], 'NONSSL') . '">' . $inrec['fname'] . '&nbsp;' . $inrec['lname'] . '</a>' . $customer));
			      $tprice = 0;
		      }
		      $lastcid = $cid;

		      // get the shopping cart
		      $query2 = tep_db_query("select   p.products_price price,
												    p.products_tax_class_id taxclass,
												    p.products_model model,
                                        pd.products_name name
                                from    " . TABLE_PRODUCTS . " p,
                                        " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                        " . TABLE_LANGUAGES . " l
                                where   p.products_id = '" . $inrec['pid'] . "' and
                                        pd.products_id = p.products_id and
                                        pd.language_id = " . (int)$languages_id );

		      $inrec2 = tep_db_fetch_array($query2);
		      $sprice = tep_get_products_special_price( $inrec['pid'] );
		      if( $sprice < 1 )
			      $sprice = $inrec2['price'];

		        // Some users may want to include taxes in the pricing, allow that. NOTE HOWEVER that we don't have a good way to get individual tax rates based on customer location yet!
			      if( RCS_INCLUDE_TAX_IN_PRICES  == 'true' )
				      $sprice += ($sprice * tep_get_tax_rate( $inrec2['taxclass'] ) / 100);
			      else if( RCS_USE_FIXED_TAX_IN_PRICES  == 'true' && RCS_FIXED_TAX_RATE > 0 )
				      $sprice += ($sprice * RCS_FIXED_TAX_RATE / 100);

		        $tprice = $tprice + ($inrec['qty'] * $sprice);
            $pprice_formated  = $currencies->format($sprice);
            $tpprice_formated = $currencies->format(($inrec['qty'] * $sprice));

            $this->infoBoxContents[] = array(
              'params' => 'class="dataTableRow"',
              0 => array(
                'params' => 'class="dataTableContent" align="left" width="15%" nowrap',
                'text' => $inrec2['model'] . '&nbsp;'),
              1 => array(
                'params' => 'class="dataTableContent" align="left" colspan="2" width="55%"',
                'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=new_product_preview&read=only&pID=' . $inrec['pid'] . '&origin=' . FILENAME_RECOVER_CART_SALES . '?page=' . $_GET['page'], 'NONSSL') . '">' . $inrec2['name'] . '</a>'),
              2 => array(
                'params' => 'class="dataTableContent" align="center" width="10%" nowrap',
                'text' => $inrec['qty']),
              3 => array(
                'params' => 'class="dataTableContent" align="right" width="10%" nowrap',
                'text' => $pprice_formated),
              4 => array(
                'params' => 'class="dataTableContent" align="right" width="10%" nowrap',
                'text' => $tpprice_formated));

		        if( EMAIL_USE_HTML == 'true' ) {
              $mline .= $inrec['qty'] . ' x <a href="' . tep_catalog_href_link(FILENAME_CATALOG_PRODUCT_INFO, 'products_id='. $inrec['pid']) . '">' . $inrec2['name'] . '</a>' . "\n";
			      } else {
              $mline .= $inrec['qty'] . ' x ' . $inrec2['name'] . "\n";
			      }
	        }

			// E-mail Processing - Requires EMAIL_* defines in the
			// includes/languages/english/recover_cart_sales.php file
			$cquery = tep_db_query("select * from orders where customers_id = '".$cid."'" );
			$email = EMAIL_TEXT_LOGIN;

			if( EMAIL_USE_HTML == 'true' )
				$email .= '  <a HREF="' . tep_catalog_href_link(FILENAME_CATALOG_LOGIN, '', 'SSL') . '">' . tep_catalog_href_link(FILENAME_CATALOG_LOGIN, '', 'SSL')  . '</a>';
			else
				$email .= '  (' . tep_catalog_href_link(FILENAME_CATALOG_LOGIN, '', 'SSL') . ')';

			$email .= "\n" . EMAIL_SEPARATOR . "\n\n";

		  if (RCS_EMAIL_FRIENDLY == 'true')
			 $email .= EMAIL_TEXT_SALUTATION . $inrec['fname'] . ",";
		  else
			 $email .= STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n";

		  if (mysql_num_rows($cquery) < 1)
			 $email .= sprintf(EMAIL_TEXT_NEWCUST_INTRO, $mline);
		  else
			 $email .= sprintf(EMAIL_TEXT_CURCUST_INTRO, $mline);

		  $email .= EMAIL_TEXT_BODY_HEADER . $mline . EMAIL_TEXT_BODY_FOOTER;

			if( EMAIL_USE_HTML == 'true' )
				$email .= '<a HREF="' . tep_catalog_href_link('', '') . '">' . STORE_OWNER . "\n" . tep_catalog_href_link('', '')  . '</a>';
			else
				$email .= STORE_OWNER . "\n" . tep_catalog_href_link('', '');

			$email .= "\n\n". $_POST['message'];
			$custname = $inrec['fname']." ".$inrec['lname'];

			$outEmailAddr = '"' . $custname . '" <' . $inrec['email'] . '>';
			if( tep_not_null(RCS_EMAIL_COPIES_TO) )
				$outEmailAddr .= ', ' . RCS_EMAIL_COPIES_TO;

			tep_mail('', $outEmailAddr, EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, EMAIL_FROM);

			$mline = "";

		      // See if a record for this customer already exists; if not create one and if so update it
		      $donequery = tep_db_query("select * from ". TABLE_SCART ." where customers_id = '".$cid."'");
		      if (mysql_num_rows($donequery) == 0)
			      tep_db_query("insert into " . TABLE_SCART . " (customers_id, dateadded, datemodified ) values ('" . $cid . "', '" . $this->seadate('0') . "', '" . $this->seadate('0') . "')");
		      else
			      tep_db_query("update " . TABLE_SCART . " set datemodified = '" . $this->seadate('0') . "' where customers_id = " . $cid );
	      }

        $this->infoBoxContents[] = array(
          0 => array(
            'params' => 'class="dataTableContent" align="right" colspan="8"',
            'text' => '<b>' . TABLE_CART_TOTAL . '</b>' . $currencies->format($tprice)));

        $this->infoBoxContents[] = array(
          0 => array(
            'params' => 'align="right" colspan="6"',
            'text' => '<a class="button" href="' . tep_href_link(FILENAME_RECOVER_CART_SALES, "action=delete&customer_id=" . $cid . "&tdate=" . $this->basedays . "&sdate=" . $this->skipdays) . '">' . IMAGE_DELETE . '</a>'));

        $this->infoBoxContents[] = array(
          0 => array(
            'params' => 'align="center" colspan="6"',
            'text' => '<a href="' . FILENAME_RECOVER_CART_SALES . '">' . TEXT_RETURN . '</a>'));
    }
  }
?>