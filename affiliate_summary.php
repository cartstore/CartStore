<?php
/*
  $Id: affiliate_summary.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('affiliate_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_AFFILIATE, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_SUMMARY);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_SUMMARY));

  $affiliate_banner_history_raw = "select sum(affiliate_banners_shown) as count from " . TABLE_AFFILIATE_BANNERS_HISTORY .  " where affiliate_banners_affiliate_id  = '" . $affiliate_id . "'";
  $affiliate_banner_history_query=tep_db_query($affiliate_banner_history_raw);
  $affiliate_banner_history = tep_db_fetch_array($affiliate_banner_history_query);
  $affiliate_impressions = $affiliate_banner_history['count'];
  if ($affiliate_impressions == 0) $affiliate_impressions="n/a"; 

  $affiliate_clickthroughs_raw = "select count(*) as count from " . TABLE_AFFILIATE_CLICKTHROUGHS . " where affiliate_id = '" . $affiliate_id . "'";
  $affiliate_clickthroughs_query = tep_db_query($affiliate_clickthroughs_raw);
  $affiliate_clickthroughs = tep_db_fetch_array($affiliate_clickthroughs_query);
  $affiliate_clickthroughs =$affiliate_clickthroughs['count'];

  $affiliate_sales_raw = "
			select count(*) as count, sum(affiliate_value) as total, sum(affiliate_payment) as payment from " . TABLE_AFFILIATE_SALES . " a 
			left join " . TABLE_ORDERS . " o on (a.affiliate_orders_id=o.orders_id) 
			where a.affiliate_id = '" . $affiliate_id . "' and o.orders_status >= " . AFFILIATE_PAYMENT_ORDER_MIN_STATUS . " 
			";
  $affiliate_sales_query = tep_db_query($affiliate_sales_raw);
  $affiliate_sales = tep_db_fetch_array($affiliate_sales_query);

  $affiliate_transactions=$affiliate_sales['count'];
  if ($affiliate_clickthroughs > 0) {
	$affiliate_conversions = tep_round(($affiliate_transactions / $affiliate_clickthroughs)*100, 2) . "%";
  } else {
    $affiliate_conversions = "n/a";
  }
  $affiliate_amount = $affiliate_sales['total'];
  if ($affiliate_transactions>0) {
	$affiliate_average = tep_round($affiliate_amount / $affiliate_transactions, 2);
  } else {
	$affiliate_average = "n/a";
  }
  $affiliate_commission = $affiliate_sales['payment'];

  $affiliate_values = tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_id = '" . $affiliate_id . "'");
  $affiliate = tep_db_fetch_array($affiliate_values);
  $affiliate_percent = 0;
  $affiliate_percent = $affiliate['affiliate_commission_percent'];
  if ($affiliate_percent < AFFILIATE_PERCENT) $affiliate_percent = AFFILIATE_PERCENT;
?>


<?php require(DIR_WS_INCLUDES . 'header.php'); ?>


<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

    <div class="page-header">
        <h1>
    <?php echo HEADING_TITLE; ?></h1>
    </div>


<?php
  if ($messageStack->size('account') > 0) {
?>
      <?php echo $messageStack->output('account'); ?> 
<?php
}
?>
<b><?php echo TEXT_GREETING . $affiliate['affiliate_firstname'] . ' ' . $affiliate['affiliate_lastname'] . ' <i class="fa fa-users"></i></b><p> ' . TEXT_AFFILIATE_ID . $affiliate_id; ?> </p>

<h3><?php echo TEXT_SUMMARY_TITLE; ?></h3>


<table class="table">
           
                <tr>
                  <td width="35%" align="right" class="boxtext"><?php echo TEXT_IMPRESSIONS; ?>
                      
                      <?php echo '<a href="#" data-toggle="modal" data-target="#TEXT_IMPRESSIONS"><i class="fa fa-info-circle"></i></a>'; ?>
                  
                  
                  <!-- Modal -->
<div class="modal fade" id="TEXT_IMPRESSIONS" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-info-circle"></i> Impressions</h4>
      </div>
      <div class="modal-body">
      Impressions: displays the total number of times a banner or link has been displayed in the given time period. 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


                  
                  
                  
                  
                  
                  
                  
                  </td>
                  <td width="15%" class="boxtext"><?php echo $affiliate_impressions; ?></td>
                  <td width="35%" align="right" class="boxtext"><?php echo TEXT_VISITS; ?><?php echo '<a href="#" data-toggle="modal" data-target="#TEXT_VISITS"><i class="fa fa-info-circle"></i></a>'; ?>
                  
                  
                  
                      <!-- Modal -->
<div class="modal fade" id="TEXT_VISITS" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-info-circle"></i> Visits</h4>
      </div>
      <div class="modal-body">
Visits: represents the total number of click-throughs by visitors from your website.       </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
     
                      
                  
                  
                  
                  
                  
                  </td>
                  <td width="15%" class="boxtext"><?php echo $affiliate_clickthroughs; ?></td>
                </tr>
                <tr>
                  <td width="35%" align="right" class="boxtext"><?php echo TEXT_TRANSACTIONS; ?><?php echo '<a href="#" data-toggle="modal" data-target="#TEXT_TRANSACTIONS"><i class="fa fa-info-circle"></i></a>'; ?>
                  
                  
                  
                                    <!-- Modal -->
<div class="modal fade" id="TEXT_TRANSACTIONS" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-info-circle"></i> Transactions</h4>
      </div>
      <div class="modal-body">
Transactions: represents the total number of successful transactions credited to your account.       </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  </td>
                  <td width="15%" class="boxtext"><?php echo $affiliate_transactions; ?></td>
                  <td width="35%" align="right" class="boxtext"><?php echo TEXT_CONVERSION; ?><?php echo '<a href="#" data-toggle="modal" data-target="#TEXT_CONVERSION"><i class="fa fa-info-circle"></i></a>'; ?>
                  
                  
                                
                                    <!-- Modal -->
<div class="modal fade" id="TEXT_CONVERSION" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-info-circle"></i> Conversions</h4>
      </div>
      <div class="modal-body">
Conversions: represents the percentage of visitors (click-throughs) completing a transaction. 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
                      
                      
                  
                  
                  
                  
                  </td>
                  <td width="15%" class="boxtext"><?php echo $affiliate_conversions;?></td>
                </tr>
                <tr>
                  <td width="35%" align="right" class="boxtext"><?php echo TEXT_AMOUNT; ?><?php echo '<a href="#" data-toggle="modal" data-target="#FILENAME_AFFILIATE_HELP_5"><i class="fa fa-info-circle"></i></a>'; ?>
                  
                  
                                                      <!-- Modal -->
<div class="modal fade" id="FILENAME_AFFILIATE_HELP_5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-info-circle"></i> Sales Amount</h4>
      </div>
      <div class="modal-body">
Sales Amount: represents the total sales value of delivered orders credited to your account. 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
                  
                  
                  
                  </td>
                  <td width="15%" class="boxtext"><?php echo $currencies->display_price($affiliate_amount, ''); ?></td>
                  <td width="35%" align="right" class="boxtext"><?php echo TEXT_AVERAGE; ?><?php echo '<a href="#" data-toggle="modal" data-target="#TEXT_AVERAGE"><i class="fa fa-info-circle"></i></a>'; ?>
                  
                  
                  
                                                                        <!-- Modal -->
<div class="modal fade" id="TEXT_AVERAGE" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-info-circle"></i> Sales Average</h4>
      </div>
      <div class="modal-body">
Sales Average: represents the average sales value credited to your account.       </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
                  
                  
                  
                  
                  </td>
                  <td width="15%" class="boxtext"><?php echo $currencies->display_price($affiliate_average, ''); ?></td>
                </tr>
                <tr>
                   <td align="right" class="boxtext"><?php echo TEXT_CLICKTHROUGH_RATE; ?><?php echo '<a href="#" data-toggle="modal" data-target="#TEXT_CLICKTHROUGH_RATE"><i class="fa fa-info-circle"></i></a>'; ?>
                   
                   
                   
                   
                                                                                           <!-- Modal -->
<div class="modal fade" id="TEXT_CLICKTHROUGH_RATE" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-info-circle"></i> Clickthrough Rate</h4>
      </div>
      <div class="modal-body">
Clickthrough Rate: represents the rate you are paid for clickthroughs on a per click basis.       </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
                  
                   
                   
                   
                   
                   
                   </td>
                   <td class="boxtext"><?php echo  $currencies->display_price(AFFILIATE_PAY_PER_CLICK, ''); ?></td>
                   <td align="right" class="boxtext"><?php echo TEXT_PAYPERSALE_RATE; ?><?php echo '<a href="#" data-toggle="modal" data-target="#TEXT_PAYPERSALE_RATE"> <i class="fa fa-info-circle"></i></a>'; ?>
                   
                   
                   
                   
                                                                                                              <!-- Modal -->
<div class="modal fade" id="TEXT_PAYPERSALE_RATE" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-info-circle"></i> Pay Per Sale Rate</h4>
      </div>
      <div class="modal-body">
Pay Per Sale Rate: represents the rate you are paid for sales on a sale by sale basis.      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
                  
                   
                   
                   
                   </td>
                   <td class="boxtext"><?php echo  $currencies->display_price(AFFILIATE_PAYMENT, ''); ?></td>
                </tr>
                <tr>
                  <td width="35%" align="right" class="boxtext"><?php echo TEXT_COMMISSION_RATE; ?><?php echo '<a href="#" data-toggle="modal" data-target="#TEXT_COMMISSION_RATE"><i class="fa fa-info-circle"></i></a>'; ?>
                  
                  
                  
                  
                                                                                                                                <!-- Modal -->
<div class="modal fade" id="TEXT_COMMISSION_RATE" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-info-circle"></i> Commission Rate</h4>
      </div>
      <div class="modal-body">
Commission Rate: represents the rate you are paid for sales as a percentage.      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
                  
                  
                  
                  </td>
                  <td width="15%" class="boxtext"><?php echo tep_round($affiliate_percent, 2). '%'; ?></td>
                  <td width="35%" align="right" class="boxtext"><?php echo TEXT_COMMISSION; ?><?php echo '<a href="#" data-toggle="modal" data-target="#TEXT_COMMISSION"><i class="fa fa-info-circle"></i></a>'; ?>
                  
                  
                  
                                    
                                                                                                                                <!-- Modal -->
<div class="modal fade" id="TEXT_COMMISSION" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-info-circle"></i> Commission </h4>
      </div>
      <div class="modal-body">
Commission: represents the total commission owed to you.       </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
                  
                  
                  
                  
                  </td>
                  <td width="15%" class="boxtext"><?php echo $currencies->display_price($affiliate_commission, ''); ?></td>
                </tr>
               
                 <tr>
                  <td align="center" class="boxtext" colspan="4"><b><?php echo TEXT_SUMMARY; ?><b></td>
                </tr>
            
    
                </table>
            
            
            
            
            
            
           
<h3>             <?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_SUMMARY, '', 'SSL'). '">' . TEXT_AFFILIATE_SUMMARY . '</a>';?></h3>

   <ul class="nav nav-pills">

    <li> <?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_ACCOUNT, '', 'SSL'). '">' . TEXT_AFFILIATE_ACCOUNT . '</a>';?> </li>
      <li>   <?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_NEWSLETTER, '', 'SSL'). '">' . TEXT_AFFILIATE_NEWSLETTER . '</a>';?> </li>

       <li>      <?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_PASSWORD, '', 'SSL'). '">' . TEXT_AFFILIATE_PASSWORD . '</a>';?></li>
        <li>         <?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_NEWS, '', 'SSL'). '">' . TEXT_AFFILIATE_NEWS . '</a>';?></li>

    </ul>  
                    
                    <h3><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS, '', 'SSL'). '">' . TEXT_AFFILIATE_BANNERS . '</a>';?></h3>
                    
                  <ul class="nav nav-pills">
                      <li> <?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_BANNERS, '', 'SSL'). '">' . TEXT_AFFILIATE_BANNERS_BANNERS . '</a>';?></li>
                       
                  <li> <?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD, '', 'SSL'). '">' . TEXT_AFFILIATE_BANNERS_BUILD . '</a>';?></li>
                 <li> <?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_PRODUCT, '', 'SSL'). '">' . TEXT_AFFILIATE_BANNERS_PRODUCT . '</a>';?></li>
                 <li> <?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_TEXT, '', 'SSL'). '">' . TEXT_AFFILIATE_BANNERS_TEXT . '</a>';?></li>
                  </ul>  
                    
     <h3><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_REPORTS, '', 'SSL'). '">' . TEXT_AFFILIATE_REPORTS . '</a>';?></h3>
         
      <ul class="nav nav-pills">
   
        <li> <?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_CLICKS, '', 'SSL'). '">' . TEXT_AFFILIATE_CLICKRATE . '</a>';?></li>
         <li>    <?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, '', 'SSL'). '">' . TEXT_AFFILIATE_PAYMENT . '</a>';?></li>
           <li>      <?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_SALES, '', 'SSL'). '">' . TEXT_AFFILIATE_SALES . '</a>';?></li>
     
     
      </ul>
     
     
 





     

<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>


<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>


<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>