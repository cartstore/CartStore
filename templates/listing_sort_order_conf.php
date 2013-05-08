<?php

$sort_col = $_GET['sort_id'];
$listing_sql .= ' order by ';
switch ($sort_col) {
	case 'high' :
		$listing_sql .= "products_price desc ";
		break;
	case 'low' :
		$listing_sql .= "products_price asc ";
		break;
	case 'title' :
		$listing_sql .= "pd.products_name ";
		break;
	default :
	case 'sortorder' :
		$listing_sql .= "p.pSortOrder ";
		break;
	default :
		$listing_sql .= "p.pSortOrder ";
		break;
} //switch ($sort_col)
?>