<?php
/*
  $Id: create_order.php,v 1 2003/08/17 23:21:34 frankl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
  
*/


  require('includes/application_top.php');

// #### Get Available Customers

	$query = tep_db_query("select customers_id, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " ORDER BY customers_lastname");
    $result = $query;

	
	if (tep_db_num_rows($result) > 0)
	{
 		// Query Successful
 		$SelectCustomerBox = "<select class=\"form-control\"  name='Customer'><option value=''>" . TEXT_SELECT_CUST . "</option>\n";
 		while($db_Row = tep_db_fetch_array($result))
 		{ $SelectCustomerBox .= "<option value='" . $db_Row["customers_id"] . "'";
		  if(IsSet($_GET['Customer']) and $db_Row["customers_id"]==$_GET['Customer'])
			$SelectCustomerBox .= " SELECTED ";
		  //$SelectCustomerBox .= ">" . $db_Row["customers_lastname"] . " , " . $db_Row["customers_firstname"] . " - " . $db_Row["customers_id"] . "</option>\n"; 
		  $SelectCustomerBox .= ">" . $db_Row["customers_lastname"] . " , " . $db_Row["customers_firstname"] . "</option>\n";
		
		}
		
		$SelectCustomerBox .= "</select>\n";
	}
	
	$query = tep_db_query("select code, value from " . TABLE_CURRENCIES . " ORDER BY code");
	$result = $query;
	
	if (tep_db_num_rows($result) > 0)
	{
 		// Query Successful
 		$SelectCurrencyBox = "<select class=\"form-control\"  name='Currency'><option value='' SELECTED>" . TEXT_SELECT_CURRENCY . "</option>\n";
 		while($db_Row = tep_db_fetch_array($result))
 		{ 
			$SelectCurrencyBox .= "<option value='" . $db_Row["code"] . " , " . $db_Row["value"] . "'";
		  	$SelectCurrencyBox .= ">" . $db_Row["code"] . "</option>\n";
		}
		
		$SelectCurrencyBox .= "</select>\n";
	}

	if(IsSet($_GET['Customer']))
	{
 	$account_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $_GET['Customer'] . "'");
 	$account = tep_db_fetch_array($account_query);
 	$customer = $account['customers_id'];
 	$address_query = tep_db_query("select * from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $_GET['Customer'] . "'");
 	$address = tep_db_fetch_array($address_query);
 	//$customer = $account['customers_id'];
	} elseif (IsSet($_GET['Customer_nr']))
	{
 	$account_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $_GET['Customer_nr'] . "'");
 	$account = tep_db_fetch_array($account_query);
 	$customer = $account['customers_id'];
 	$address_query = tep_db_query("select * from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $_GET['Customer_nr'] . "'");
 	$address = tep_db_fetch_array($address_query);
 	//$customer = $account['customers_id'];
	}


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ORDER_PROCESS);
 

// #### Generate Page
	?>	
	<?php
  			require(DIR_WS_INCLUDES . 'header.php');
		?>

 
 

		<?php require('includes/form_check.js.php'); ?>
		
 <div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a>Create Account</h1></div>
        <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body">
<i class="fa fa-user fa-5x pull-left"></i>
The create order screen allows you to first set or create the customer for the order then on the next screen edit the order.
                                  </div>
                      </div>
                  </div>   
              </div>    
		<h3><?php echo TEXT_STEP_1 ?></h3>
		 
	 

<?php
	print "<form action='$PHP_SELF' method='GET'>\n";
	print "<table class=\"table\">\n";
	print "<tr>\n";
	print "<td><div class=\"form-group\">$SelectCustomerBox </div> <p><input class=\"btn btn-primary\" type='submit' value=\"" . BUTTON_SUBMIT . "\"></p></td>\n";
	print "";
	print "</tr>\n";
	print "\n";
	print "</form>\n";
?>
<?php
	print "<form action='$PHP_SELF' method='GET'>\n";
	print "<table table class=\"table\">\n";
	print "<tr>\n";
	print "<td>  <div class=\"form-group\"><label>" . TEXT_OR_BY . "</label> <input class=\"form-control\" type=text name='Customer_nr'></div><p><input class=\"btn btn-primary\" type='submit' value=\"" . BUTTON_SUBMIT . "\"></p></td>\n";
	print " ";
	print "</tr>\n";
	print "\n";
	print "</form>\n";
?>	
	 
	 <tr>
        <td><?php echo tep_draw_form('create_order', FILENAME_CREATE_ORDER_PROCESS, '', 'post', '', '') . tep_draw_hidden_field('customers_id', $account->customers_id); ?><h3> <?php echo HEADING_CREATE; ?></h3> </td>
      </tr>
 
      <tr>
        <td>
<?php

//onSubmit="return check_form();"

  require(DIR_WS_MODULES . 'create_order_details.php');
 
?>
        </td>
      </tr>
  </table>
   <p> <?php echo '<a class="btn btn-default" href="' . tep_href_link(FILENAME_DEFAULT, '', 'SSL') . '">' .  Back . '</a>'; ?> 
        <?php echo tep_image_submit('button_confirm.png', Confirm); ?></p> 

         
   </form>

 
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>


<?php 
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
 
?>