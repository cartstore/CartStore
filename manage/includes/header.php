<!DOCTYPE html>
 <html class=" js no-touch localstorage svg">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>CartStore Administration</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
 
 		<link href="favicon.ico" rel="shortcut icon" type="image/x-icon">
 		<link href="./templates/responsive-red/assets/light-theme.css" media="all" id="color-settings-body-color" rel="stylesheet" type="text/css">
 
		<link href="./templates/responsive-red/assets/bootstrap.css" media="all" rel="stylesheet" type="text/css">

 	   

<link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
		<link href="//codeorigin.jquery.com/ui/1.10.3/themes/blitzer/jquery-ui.css" rel="stylesheet">
		<link href="redactor/redactor.css" rel="stylesheet">
		 
	</head>
	<body class="contrast-red " style="" <?php if('new_product' == $action || 'update_product' == $action) { echo 'onload="goOnLoad()"'; } ?>>
		<header>
			<nav class="navbar navbar-inverse">
				<a class="navbar-brand" href="./orders.php"> <img width="auto" height="22" class="logo" alt="CartStore" src="./templates/responsive-red/assets/logo.png"> </a>
				<a class="toggle-nav btn pull-left" href="#"> <i class="icon-reorder"></i> </a>
				<ul class="nav">
                                    <li class="dropdown medium only-icon widget">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-rss"></i> <div class="label"><?php
  $orders_pending_query = tep_db_query("select * from " . TABLE_ORDERS . " where orders_status = '1' order by date_purchased DESC LIMIT 5");
?><?php echo tep_db_num_rows($orders_pending_query); ?></div></a>
						<?php if (tep_db_num_rows($orders_pending_query)): ?>
						<ul class="dropdown-menu">
							<?php while ($orders_pending = tep_db_fetch_array($orders_pending_query)): ?>
								<li>
									<a href="orders.php?page=1&oID=<?php echo $orders_pending['orders_id']; ?>&action=edit">
										<div class="widget-body">
											<div class="pull-left icon">
												<i class="icon-inbox text-error"></i>
											</div>
											<div class="pull-left text">
												Notice: Pending Order #<?php echo sprintf("%04d",$orders_pending['orders_id']); ?>
												<small class="text-muted">@ <?php echo date("m/j/Y",strtotime($orders_pending['date_purchased'])); ?> (<?php echo ago($orders_pending['date_purchased']); ?>)</small>
											</div>
										</div> 
									</a>
								</li>
								<?php if (tep_db_num_rows($orders_pending_query)): ?>
								<?php endif; ?>
								<li class="divider"></li>
							<?php endwhile; ?>
							<li class="widget-footer">
								<a href="orders.php?status=1">All Pending Orders</a>
							</li>
						</ul>
						<?php endif; ?>
					</li>
                                    
					<li class="dropdown light only-icon">
						<a class="dropdown-toggle" data-toggle="dropdown" href="orders.php"> <i class="fa fa-cogs"></i> </a>
						<ul class="dropdown-menu color-settings">
							<li class="color-settings-body-color">
								
										
<a href="javascript:popUp('./Abs/')">Backup / Rollback <i class="fa fa-umbrella"></i></a>	
 								<a href="configuration.php?gID=1"> My Store </a>
 								<a href="configuration.php?gID=7"> Shipping Packing </a>
																<a href="configuration.php?gID=9"> Stock </a>
<a href="modules.php?set=payment"> Payment Modules </a>
<a href="modules.php?set=shipping"> Shipping Modules </a>
<a href="configuration.php?gID=1&cID=55482&action=edit"> Store Online </a>

<a href="configuration.php?gID=22">Points & Rewards</a>
								
<a href="currencies.php"> Currencies </a>
<a href="server_info.php"> Server Info </a>


<a href="phpids_report.php"> Security Log </a>


<a href="server_info.php"> Server Info </a>
<a target="_BLANK" href="../scanner_2.6.php">Scan Virus/Trojans</a>
<a href="javascript:popUp('phpMyAdmin/index.php')">PhpMyAdmin</a>
								
								
							</li>

						</ul>
					</li>
					
					<li class="dropdown dark user-menu">
						<a class="dropdown-toggle" data-toggle="dropdown" href="admin_account.php"> 
							<i class="fa fa-users"></i> <span class="user-name">   <?php
  print_r($_SESSION['login_email_address']);
?></span> <b class="caret"></b> </a>
						<ul class="dropdown-menu">
						
							<li>
								<a href="admin_account.php"> <i class="icon-cog"></i> My Profile </a>
							</li>
							
								<li>
								<a href="admin_members.php?page=1&mID=16&action=new_member"> <i class="fa fa-users"></i> Add Administrator </a>
							</li>
							
								<li>
								<a href="about.php"> <i class="fa fa-medkit"></i> Help  </a>
							</li>
                                                        
                                                        <li>
								<a href="about.php"><i class="fa fa-info-circle"></i> About CartStore </a>
							</li>
							
							<li class="divider"></li>
							<li>
								<a href="logoff.php"> <i class="icon-signout"></i> Sign out </a>
							</li>
						</ul>
					</li>
				</ul>
 					
					<form name="search" action="categories.php" method="get" class="navbar-form navbar-left hidden-xs">
					
					
					<button class="btn btn-link fa fa-caret-down dropdown-toggle" name="button" data-toggle="dropdown"></button>
					<button class="btn btn-link icon-search" name="button" type="submit">&nbsp;</button>
                                           
					<ul class="dropdown-menu">
						<li>
							<a data-target="categories.php"> Products <?php if (basename($_SERVER['PHP_SELF']) != 'customers.php' && basename($_SERVER['PHP_SELF']) != 'orders.php'): ?><i class="fa fa-check text-success"></i><?php endif; ?></a>
						</li>
						<li>
							<a data-target="customers.php">Customers <?php if (basename($_SERVER['PHP_SELF']) == 'customers.php'): ?><i class="fa fa-check text-success"></i><?php endif; ?></a>
						</li>
						<li>
							<a data-target="orders.php">Orders <?php if (basename($_SERVER['PHP_SELF']) == 'orders.php'): ?><i class="fa fa-check text-success"></i><?php endif; ?></a>
						</li>
                                                
                                                
                                                <li>
							<a data-target="returns.php">Exchanges <?php if (basename($_SERVER['PHP_SELF']) == 'returns.php'): ?><i class="fa fa-check text-success"></i><?php endif; ?></a>
						</li>
                                                
                                                 <li>
							<a data-target="newsdesk.php">Blog <?php if (basename($_SERVER['PHP_SELF']) == 'newsdesk.php'): ?><i class="fa fa-check text-success"></i><?php endif; ?></a>
						</li>
                                                
                                                 <li>
							<a data-target="articles.php">Information Pages <?php if (basename($_SERVER['PHP_SELF']) == 'articles.php'): ?><i class="fa fa-check text-success"></i><?php endif; ?></a>
						</li>
                                                
                                                
                                                
						<li class="divider"></li>
						<li> 
							<a data-target="../advanced_search_result.php">Front Site <i class="fa fa-sign-out"></i></a>
						</li>
					</ul>
					<div class="form-group">
						<input value="" class="form-control" placeholder="Search..." autocomplete="off" id="q_header" name="search" type="text">
					</div>
				</form>
			</nav>
		</header>
		<div id="wrapper">
			<div id="main-nav-bg"></div>
			<nav id="main-nav">
				<div class="navigation">
					
					<ul class="nav nav-stacked">

						<li>
                                                    <a href="orders.php" class="dropdown-collapse"> <span><i class="fa fa-plus-circle"></i> Orders <i class="icon-angle-down angle-down pull-right"></i></span> 

							</a>
							<ul class="nav nav-stacked">
								<li>
									<a href="orders.php">Orders</a>
								</li>

								<li>
									<a href="create_order.php">Create Order</a>
								</li>

								<li>
									<a href="recover_cart_sales.php"> Recover Cart Sales </a>
								</li>
								

								<li>
									<a href="coupon_admin.php?selected_box=gv_admin" class="dropdown-collapse"> <span>Coupons</span> <i class="icon-angle-down angle-down"></i> </a>
									<ul class="nav nav-stacked">
										<li>
											<a href="coupon_admin.php">Coupon Admin</a>
										</li>
										<li>
											<a href="gv_queue.php">Gift Voucher Queue</a>
										</li>
										<li>
											<a href="gv_mail.php">Mail Gift Voucher</a>
										</li>
										<li>
											<a href="gv_sent.php">Gift Vouchers sent</a>
										</li>
									</ul>
								</li>

								<li class=" parent">
									<a href="returns.php" class="dropdown-collapse"> <span>Returns</span> <i class="icon-angle-down angle-down"></i> </a>
									<ul class="nav nav-stacked">
										<li>
											<a href="returns.php">Returned Products</a>
										</li>
										<li>
											<a href="returns_reasons.php">Return Reasons</a>
										</li>
										<li>
											<a href="refund_methods.php"> Refund Methods </a>
										</li>
										<li>
											<a href="returns_status.php"> Returns Status </a>
										</li>
										<li>
											<a href="return_text.php"> Return Text Edit </a>
										</li>
									</ul>
								</li>

								<li>
									<a href="export_orders_csv.php">Export Orders CSV</a>
								</li>

								<li>
									<a href="export_quickbooks_csv.php">Export QuickBooks&#153;</a>
								</li>

								<li>
									<a href="javascript:;" class="dropdown-collapse"> <span>Commissions</span> <i class="icon-angle-down angle-down"></i> </a>
									<ul class="nav nav-stacked">
										 
										
										
									
										
										
										
										
										
										<li class="active parent">
											<a href="javascript:;" class="dropdown-collapse"> <span>Affiliates</span> <i class="icon-angle-down angle-down"></i> </a>
											<ul class="nav nav-stacked">
												<li>
													<a href="affiliate_summary.php">Summary</a>
												</li>
												<li>
													<a href="affiliate_affiliates.php">Affiliates</a>
												</li>
												<li>
													<a href="affiliate_payment.php">Payment</a>
												</li>
												<li>
													<a href="affiliate_sales.php">Sales</a>
												</li>
												<li>
													<a href="affiliate_clicks.php">Clicks</a>
												</li>
												<li>
													<a href="affiliate_banners.php">Banners</a>
												</li>
												<li>
													<a href="affiliate_news.php">Affiliate News</a>
												</li>
												<li>
													<a href="affiliate_newsletters.php">Affiliate Newsletter</a>
												</li>
												<li>
													<a href="affiliate_contact.php">Contact</a>
												</li>
												<li>
													<a href="/manage/affiliate_statistics.php">Affiliate Statistics</a>
												</li>
											</ul>
										</li>
									</ul>
								</li>

								<li>

									<a href="javascript:;" class="dropdown-collapse"> <span>Order Settings</span> <i class="icon-angle-down angle-down"></i> </a>

									<ul class="nav nav-stacked">
										<li>
											<a href="currencies.php">Currencies</a>
										</li>
										<li>
											<a href="orders_status.php">Order Status Types</a>
										</li>
										<li>
											<a href="configuration.php?gID=1">Store Infomation Settings</a>
										</li>

										<li class="active parent">
											<a href="javascript:;" class="dropdown-collapse"> <span>Taxes</span> <i class="icon-angle-down angle-down"></i> </a>
											<ul class="nav nav-stacked">
												<li>
													<a href="countries.php">Countries</a>
												</li>
												<li>
													<a href="zones.php">Zones</a>
												</li>
												<li>
													<a href="geo_zones.php">Tax Zones</a>
												</li>
												<li>
													<a href="tax_classes.php">Tax Classes</a>
												</li>
												<li>
													<a href="tax_rates.php">Tax Rates</a>
												</li>

												<li>
													<a href="wa_taxes_report.php">WA State Tax Report</a>
												</li>

											</ul>
										</li>

									</ul>

								</li>

							</ul>
						</li>
						<li>
							<a href="javascript:;" class="dropdown-collapse"> <span><i class="fa fa-plus-circle"></i> Products <i class="icon-angle-down angle-down"></i></span>  </a>
							<ul class="nav nav-stacked">
                                                            
                                                           

								<li>
									<a href="categories.php?action=new_product">Add Product</a>
								</li>
								<li>
									<a href="categories.php">Categories</a>
								</li>

								<li>
									<a href="specials.php">Specials</a>
								</li>

								<li>
									<a href="manufacturers.php">Brands / Manufacturers</a>
								</li>

								<li>
									<a href="product_list.php">Quick Inventory</a>
								</li>

								 
								<li>
									<a href="easypopulate.php">CSV Import/Export</a>
								</li>
								<li>
									<a href="products_options.php">Product Options</a>
								</li>
								<li>
									<a href="new_attributes.php">Quick Product Options</a>
								</li>

								<li>
									<a href="reviews.php">Reviews</a>
								</li>
								<li>
									<a href="xsell.php">Cross Sell</a>
								</li>

								<li>
									<a href="products_expected.php">Products Expected</a>
								</li>
								<li>
									<a href="product_extra_fields.php">Product Extra Fields</a>
								</li>
								<li>
									<a href="configuration.php?gID=9">Stock Settings</a>
								</li>
                                                                	<li>
											<a href="newsletters.php">Newsletter Manager</a>
										</li>
                                                                
                                                                 <li>
											<a href="feeders.php">Google&#153; Merchant Center</a>
										</li>

								<li>
									<a href="" class="dropdown-collapse"> <span>Drop Shipping System</span> <i class="icon-angle-down angle-down"></i> </a>
									<ul class="nav nav-stacked">
										<li>
											<a href="vendors.php">Vendor Manager</a>
										</li>
										<li>
											<a href="prods_by_vendor.php">Product Vendor Reports</a>
										</li>
										<li>
											<a href="javascript:;">Vendors Orders List</a>
										</li>
										<li>
											<a href="move_vendor_prods.php">Move Products Between
											<br>
											Vendors</a>
										</li>
										<li>
											<a href="configuration.php?gID=7&amp;cID=4660&amp;action=edit">Enable Vendor System</a>
										</li>
									</ul>
								</li>

								
								
							</ul>
						</li>

						<li class="">
							<a href="customers.php" class="dropdown-collapse"> <span><i class="fa fa-plus-circle"></i> Customers <i class="icon-angle-down angle-down"></i> </span></a>
							<ul class="nav nav-stacked">

								<li>
									<a href="create_account.php">Create Customer</a>
								</li>

								<li>
									<a href="customers.php">Manage Customers</a>
								</li>

								<li>
									<a href="mail.php">Send Customer Emails</a>
								</li>

								<li>
									<a href="customers_groups.php">Customers Groups</a>
								</li>
								<li>
									<a href="constant_contact.php">Constant Contact&#153; Export </a>
								</li>

								<li>
									<a href="customers.php" class="dropdown-collapse"><span> Customer Points</span> <i class="icon-angle-down angle-down"></i> </a>
									<ul class="nav nav-stacked">

										<li>
											<a href="customers_points_pending.php">Approve Pending Points</a>
										</li>
										<li>
											<a href="customers_points_pending.php">Pending Points</a>
										</li>
										<li>
											<a href="customers_points.php">Customers Points</a>
										</li>

										<li>
											<a href="customers_points_referral.php"> Referral Points</a>
										</li>
									</ul>

								</li>

						</li>

					</ul>
					</li>

					<li>
						<a href="javascript:;" class="dropdown-collapse"> <span><i class="fa fa-plus-circle"></i> CMS <i class="icon-angle-down angle-down"></i> </span></a>
						<ul class="nav nav-stacked">
                                                    
                                                    <li>
											<a href="link_manage.php">Links Manager</a>
										</li>
                                                    <li>
											<a href="banner_manager.php">Banner Manager</a>
										</li>
						
							<li>
								<a href="newsdesk.php?selected_box=newsdesk"> Blog </a>
							</li>
                                                        
                                                        	<li>
								<a href="articles.php?selected_box=articles">Information Pages</a>
							</li>
							<li>
								<a href="configuration.php?gID=923&amp;selected_box=configuration">RSS News Module</a>
							</li>
                                                        
                                                        <li>
											<a href="configuration.php?gID=30&amp;cID=885">Ebay&#153; Auctions</a>
										</li>
							
							<li class="parent">
								<a href="javascript:;" class="dropdown-collapse"> <span>HTML Module Editors</span> <i class="icon-angle-down angle-down"></i> </a>
								<ul class="nav nav-stacked">
									<li>
										<a href="general_area1.php">HTML Module Position 1</a>
									</li>
									<li>
										<a href="general_area2.php">HTML Module Position 2</a>
									</li>
									<li>
										<a href="general_area3.php">HTML Module Position 3</a>
									</li>
									<li>
										<a href="general_area4.php">HTML Module Position 4</a>
									</li>
									<li>
										<a href="general_area5.php">HTML Module Position 5</a>
									</li>
								</ul>
							</li>
							<li class=" parent">
								<a href="javascript:;" class="dropdown-collapse"> <span> Source Code Modules</span> <i class="icon-angle-down angle-down"></i> </a>
								<ul class="nav nav-stacked">
									<li>
										<a href="static_menu1.php">Source Code Module Position 1</a>
									</li>
									<li>
										<a href="static_menu2.php">Source Code Module Position 2</a>
									</li>
									<li>
										<a href="static_menu3.php">Source Code Module Position 3</a>
									</li>
									<li>
										<a href="static_menu3.php">Source Code Module Position 4</a>
									</li>
								</ul>
							</li>
							<li class="parent">
								<a href="javascript:;" class="dropdown-collapse"> <span>Adsence&#153; Code Editors</span> <i class="icon-angle-down angle-down"></i> </a>
								<ul class="nav nav-stacked">
									<li>
										<a href="adsence.php">Adsence Code Position 1</a>
									</li>
									<li>
										<a href="adsence2.php">Adsence Code Position 2</a>
									</li>
									<li>
										<a href="adsence3.php">Adsence Code Position 3</a>
									</li>
								</ul>
							</li>
                                                        
                                                     
							<li>
								<a href="javascript:popUp('ckfinder/ckfinder.html')">Media Manager</a>
							</li>
							
							<li>
								<a href="link_manage.php">Links Manager</a>
							</li>
                                                        
                                                        <li>
								<a href="events_main.php">Event / Calendar</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="javascript:;" class="dropdown-collapse"> <span><i class="fa fa-plus-circle"></i> Reports <i class="icon-angle-down angle-down"></i> </span> </a>

						<ul class=" nav nav-stacked">
                                                    
                                                    <li>
											<a href="whos_online.php">See Who's Online</a>
										</li>
                                                    <li>
									<a href="stats_recover_cart_sales.php"> Recovered Sales Results </a>
								</li>
							<li>
								<a href="stats_sales_report2.php">SalesReport2</a>
							</li>
							<li>
								<a href="stats_products_viewed.php">Products Viewed</a>
							</li>
							<li>
								<a href="stats_products_purchased.php">Products Purchased</a>
							</li>
							<li>
								<a href="stats_customers.php">Customer Orders-Total</a>
							</li>
							<li>
								<a href="configuration.php?gID=1&cID=555100&action=edit"> Google Analytics Ecommerce </a>
							</li>
							<li>
								<a href="stats_low_stock_attrib.php">Low Stock Report</a>
							</li>
							<li>
								<a href="stats_monthly_sales.php">Monthly Sales/Tax</a>
							</li>
						</ul>
					</li>

					<li class="">
                                            <a href="admin_account.php" class="dropdown-collapse"> <span><i class="fa fa-plus-circle"></i>  Advanced  <i class="icon-angle-down angle-down"></i></span></a>
						<ul class="nav nav-stacked">
						
							<li class="">
								<a href="javascript:;" class="dropdown-collapse"><i class="fa fa-unlock"></i> <span> System (Advanced) </span> <i class="icon-angle-down angle-down"></i> </a>
								<ul class=" nav nav-stacked">
									<li class="active parent">
										<a href="configuration.php?gID=1&amp;selected_box=configuration" class="dropdown-collapse"> <i class="icon-caret-right"></i> <span>Configuration</span> <i class="icon-angle-down angle-down"></i> </a>
										<ul id="config_menu" class="nav nav-stacked">
										
											<li>
												<a href="configuration.php?gID=1">My Store</a>
											</li>
											<li>
												<a href="configuration.php?gID=2">Minimum Values</a>
											</li>
											<li>
												<a href="configuration.php?gID=3">Maximum Values</a>
											</li>
											<li>
												<a href="configuration.php?gID=333">Image Magic</a>
											</li>
											<li>
												<a href="configuration.php?gID=4">Images</a>
											</li>
											<li>
												<a href="configuration.php?gID=5">Customer Details</a>
											</li>
											<li>
												<a href="configuration.php?gID=7">Shipping/Packaging</a>
											</li>
											<li>
												<a href="configuration.php?gID=888001">Product Information</a>
											</li>
											<li>
												<a href="configuration.php?gID=9">Stock</a>
											</li>
											<li>
												<a href="configuration.php?gID=10">Logging</a>
											</li>
											<li>
												<a href="configuration.php?gID=11">Cache</a>
											</li>
											<li>
												<a href="configuration.php?gID=12">E-Mail Options</a>
											</li>
											<li>
												<a href="configuration.php?gID=13">Download</a>
											</li>
											<li>
												<a href="configuration.php?gID=14">GZip Compression</a>
											</li>
											<li>
												<a href="configuration.php?gID=15">Sessions</a>
											</li>
											<li>
												<a href="configuration.php?gID=99">Featured Products</a>
											</li>
											<li>
												<a href="configuration.php?gID=17">All Products</a>
											</li>
											<li>
												<a href="configuration.php?gID=22">Points and Rewards</a>
											</li>
											<li>
												<a href="configuration.php?gID=12954">Wish List Settings</a>
											</li>
											<li>
												<a href="configuration.php?gID=30">eBay Auctions</a>
											</li>
											<li>
												<a href="configuration.php?gID=900">Affiliate Program</a>
											</li>
											<li>
												<a href="configuration.php?gID=62">Feed Settings</a>
											</li>
											<li>
												<a href="configuration.php?gID=888004">SEO URLs</a>
											</li>
											<li>
												<a href="configuration.php?gID=18">Links</a>
											</li>
											<li>
												<a href="configuration.php?gID=73">Terms &amp; Conditions</a>
											</li>
											<li>
												<a href="configuration.php?gID=10020">Ajax enhanced search</a>
											</li>
											<li>
												<a href="configuration.php?gID=888005">Year Make Model</a>
											</li>
											<li>
												<a href="configuration.php?gID=6501">Recover Cart Sales</a>
											</li>
											<li>
												<a href="configuration.php?gID=1661">jQuery Banner Options</a>
											</li>
											<li>
												<a href="configuration.php?gID=888006">PHPIDS</a>
											</li>

											<li>
												<a href="configuration.php?gID=888007">MultiGeoZone MultiTable Shipping</a>
											</li>

										</ul>
									</li>
									<li>
										<a href="modules.php?set=payment&amp;selected_box=modules" class="dropdown-collapse"> <i class="icon-caret-right"></i> <span>Modules </span> <i class="icon-angle-down angle-down"></i> </a>

										<ul class="nav nav-stacked">
											<li>
												<a href="modules.php?set=payment">Payment</a>
											</li>
											<li>
												<a href="modules.php?set=shipping">Shipping</a>
											</li>
											<li>
												<a href="modules.php?set=sts">STS</a>
											</li>
											<li>
												<a href="modules.php?set=ordertotal">Order Total</a>
											</li>
											<li>
												<a href="modules.php?set=checkout">Checkout</a>
											</li>
											<li>
												<a href="modules.php?set=sociallogin">Social Login</a>
											</li>
										</ul> 
									</li>
									<li>
										<a href="javascript:;" class="dropdown-collapse"> <i class="icon-caret-right"></i> <span>Tools</span> <i class="icon-angle-down angle-down"></i> </a>
										<ul class="nav nav-stacked">
					  						<li>
												<a href="cache.php">Cache Control</a>
											</li>
											<li>
												<a href="server_info.php">Server Info</a>
											</li>
											<li>
												<a href="phpids_report.php">PHPIDS Log</a>
											</li>
											<li>
												<a href="banned_ip.php">Banned IP</a>
											</li>
											<li>
												<a class="menuBoxContentLink" href="mysqlperformance.php">MySQL Performance</a>
											</li>
										</ul>
									</li>
									<li>
										<a href="javascript:;" class="dropdown-collapse"> <i class="icon-caret-right"></i> Delivery Time Table <i class="icon-angle-down angle-down"></i> </a>
										<ul class="nav nav-stacked">
											<li>
												<a href="default_delivery_time.php"> Defaut Time </a>
											</li>
											<li>
												<a href="special_time.php"> Special Time </a>
											</li>
										</ul>
									</li>
									<li>
										<a href="javascript:popUp('./Abs/')">Backup / Rollback</a>
									</li>
									<li>
										<a target="_BLANK" href="../scanner_2.6.php">Scan Virus/Trojans</a>
									</li>

									<li>
										<a href="javascript:popUp('phpMyAdmin/index.php')">PhMyAdmin</a>
									</li>
									<li>
										<a href="javascript:;" class="dropdown-collapse"> <i class="icon-caret-right"></i> <span>Header Tags </span> <i class="icon-angle-down angle-down"></i> </a>

										<ul class="nav nav-stacked">
											<li>
												<a href="header_tags_english.php">Text Control</a>
											</li>
											<li>
												<a href="header_tags_fill_tags.php">Fill Tags</a>
											</li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>
					</li>

					</ul>

				</div>
			</nav>
			<section id="content">
				<div class="container">
					<div class="row" id="content-wrapper">
						<div class="col-xs-12">
						 

							<div class="row">
								<div class="col-sm-12">
									<div class="box bordered-box orange-border" style="margin-bottom:0;">

										<div class="box-content box-no-padding">
											<div class="responsive-table">
												
<div class="scrollable-area">