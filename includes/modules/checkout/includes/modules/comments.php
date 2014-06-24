<table width="100%">

<tr>
<td><div class="form-group"><?php echo tep_draw_textarea_field('comments', 'soft', '60', '5', $comments); ?>
</td>
</tr>
<tr>
<td>
	 
	<h3>Total</h3>
	<div class="finalProducts"></div>
	<div style="" class="orderTotals"><?php echo MODULE_ORDER_TOTAL_INSTALLED ? '
	<table class="table">' . $order_total_modules -> output() . '</table>' : ''; ?>
	</div>
	
</td>
</tr>
</table>
