<?php
/*
  $Id: categories.php,v 1.146 2003/07/11 14:40:27 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require('includes/languages/product_list.php');

if($_REQUEST['prod_search']!="")
{
	$product_query = tep_db_query("select pd.products_name, p.products_id, p.products_price, p.products_quantity, p.products_model, p.products_weight from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.products_name like '".$_REQUEST['prod_search']."%'");
}
else
{
	$product_query = tep_db_query("select pd.products_name, p.products_id, p.products_price, p.products_quantity, p.products_model, p.products_weight from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
}
  if($_REQUEST['update']=="update") {
	  while($product1 = tep_db_fetch_array($product_query))
			{

			$str=tep_db_query("update products set products_model='".$_REQUEST['model_'.$product1['products_id']]."',products_price='".$_REQUEST['price_'.$product1['products_id']]."',products_weight='".$_REQUEST['weight_'.$product1['products_id']]."', products_quantity='".$_REQUEST['qty_'.$product1['products_id']]."' where products_id='".$product1[products_id]."'");

			}
			header("location:product_list.php?Update=Success&prod_search=".$_REQUEST['prod_search']);
  }
	if ($_POST['update'] == 'update-ajax'){
		if (empty($_POST['info'])) { print json_encode(array('success' => false, 'error' => 'No information provided')); exit(); }
		if (empty($_POST['new_value'])) { print json_encode(array('success' => false, 'error' => 'No new value to update.')); exit(); }
		list($update_type, $product_id) = explode("_",$_POST['info']);
		if (empty($update_type) || empty($product_id)) {
			print json_encode(array('success' => false, 'error' => 'Invalid informaiton provided'));
			exit();
		}
		$check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_id = " . (int)$product_id);
		$check = tep_db_fetch_array($check_query);
		if ($check['total'] == 0){
			print json_encode(array('success' => false, 'error' => 'No product found for updating.'));
			exit();
		}
		$product_query = tep_db_query("select * from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pd.products_id = p.products_id where p.products_id = " . (int)$product_id);
		$product_info = tep_db_fetch_array($product_query);
		$sql = '';
		switch ($update_type){
			case 'model':
				$sql = "update " . TABLE_PRODUCTS . " set products_model = '" . tep_db_input($_POST['new_value']) . "' where products_id = " . (int)$product_id;
				break;
			case 'price':
				$sql = "update " . TABLE_PRODUCTS . " set products_price = '" . (float)$_POST['new_value'] . "' where products_id = " . (int)$product_id;
				break;
			case 'weight':
				$sql = "update " . TABLE_PRODUCTS . " set products_weight = '" . (float)$_POST['new_value'] . "' where products_id = " . (int)$product_id;
				break;
			case 'qty':
				$sql = "update " . TABLE_PRODUCTS . " set products_quantity = '" . (int)$_POST['new_value'] . "' where products_id = " . (int)$product_id;
				break;
		}
		if (!empty($sql)){
			ob_start();
			$rs = tep_db_query($sql);
			$output = ob_get_clean();
			if (!empty($output)){
				print json_encode(array('success' => false, 'error' => $output));
				exit();
			} else {
				print json_encode(array(
					'sucess' => true,
					'product_id' => $product_info['products_id'],
					'product_name' => $product_info['products_name']
				));
				exit();
			}
		} else {
			print json_encode(array('success' => false, 'error' => 'Unknown error occurred. No product information has been modified'));
			exit();
		}
		exit();
	}
?>

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

 <link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />


<div id="spiffycalendar" class="text"></div>

<div class="page-header"><h1>
<?php echo HEADING_TITLE; ?></h1></div>


<div class="form-group">
					<form name="search_products" method="POST" action="product_list.php">
 					<input class="form-control" type="text" name="prod_search" value=""> </div>
					<p><input type="submit" class="btn btn-default" name="search" value="Search"></p>
					</form>


				<?php
				if($_REQUEST['Update']=="Success")
				{
				?>
		<div class="alert alert-success">Record has been updated successfully</div>
				<?php
				}
				?>

	<form name="form1" method="POST" action="product_list.php">
						<table class="table table-hover table-condensed table-responsive">
							<tr class="dataTableHeadingRow">
								<td class="dataTableHeadingContent" width="15%"><?php echo TABLE_HEADING_PART_NO; ?>								</td>
								<td class="dataTableHeadingContent" width="53%"><?php echo TABLE_HEADING_NAME; ?>								</td>
								<td class="dataTableHeadingContent" width="10%"><?php echo TABLE_HEADING_PRICE; ?>&nbsp;								</td>
								<td class="dataTableHeadingContent" width="10%"><?php echo TABLE_HEADING_WEIGHT; ?>&nbsp;								</td>
								<td class="dataTableHeadingContent" width="12%"><?php echo TABLE_HEADING_STOCK_LEVEL; ?>&nbsp;								</td>
							</tr>

							<?php


							while($product = tep_db_fetch_array($product_query))
							{
						     ?>
							 <tr class="dataTableRow" onMouseOver="this.className='dataTableRowOver';this.style.cursor='hand'" onMouseOut="this.className='dataTableRow'">
							<td  valign="top" class="dataTableContent"><input class="form-control" type="text" name="model_<?php echo $product['products_id'];?>" id="model" value="<?php echo $product['products_model'];?>" size="12"></td>

							<td  width="20%" valign="top" class="dataTableContent">
							<?php
								echo $products_name=$product['products_name'];
							//$description1 =substr($products_name, 0, 45);
							//echo $description1.".....";
							?>							</td>
							<td  valign="top" class="dataTableContent"><input class="form-control" type="text" name="price_<?php echo $product['products_id'];?>" id="price" value="<?php echo $product['products_price'];?>" size="6"></td>

							<td  valign="top" class="dataTableContent"><input class="form-control" type="text" name="weight_<?php echo $product['products_id'];?>" id="weight" value="<?php echo $product['products_weight'];?>" size="6"></td>

							<td  valign="top" class="dataTableContent"><input class="form-control" type="text" name="qty_<?php echo $product['products_id'];?>" id="qty" value="<?php echo $product['products_quantity'];?>" size="6"></td>
							</tr>
							<?php
							}
							?>
							<tr>
								<td></td>
								<td></td>
								 <td></td>
								<td class="dataTableContent">
													</td>
							</tr>
							</table>
							</form>
<div id="saving-data" title="Saving..." style="display: none">
	<img src="templates/admin/images/loading.gif" style="margin: 15px auto"><p>Saving new values...</p>
</div>


<p><input type="hidden" name="prod_search" value="<?php echo $_REQUEST['prod_search']; ?>"><input type="submit" class="btn btn-default" name="update" value="update">			</p>

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$("#saving-data").dialog({autoOpen: false });
		$("form[name='form1'] input[type='text']").change(function(){
			$("#saving-data").dialog("open");
			$.ajax({
				url: 'product_list.php',
				data: {update: 'update-ajax', info: $(this).attr('name'), new_value: $(this).val()},
				type: 'post',
				dataType: 'json',
				success: function(return_data){
					setTimeout(function(){$("#saving-data").dialog("close");}, 1500);
				},
				error: function (e1, e2, e3){
					alert ("Unable to update product info. " + e3);
					setTimeout(function(){$("#saving-data").dialog("close");}, 1500);
				}
			});
		});
	});
</script>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>



