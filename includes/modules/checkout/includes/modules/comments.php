<table width="100%">
<tr>
<td width="50%"><div class="finalProducts"></div><br><div style="float:right; padding:15px;" class="orderTotals"><?php echo MODULE_ORDER_TOTAL_INSTALLED ? '<table>' . $order_total_modules->output() . '</table>' : '';?></div><br><br></td>
</tr>
<tr>
<td width="50%" align=center><div style="width:75%; margin:auto; padding-top:10px; padding-bottom:10px;"><?php echo tep_draw_textarea_field('comments', 'soft', '60', '5', $comments); ?>
</td>
</tr>
</table>
