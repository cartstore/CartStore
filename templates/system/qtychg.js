function changesetQuantity(i,qty)
	{
	 	document.buy_now_['Qty_ProdId_'+i].value = Number(document.buy_now_['Qty_ProdId_'+i].value)+Number(qty);
	}