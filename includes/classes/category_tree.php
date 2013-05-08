<?php
/*
 $Id: category_tree.php,v1.0 2004/05/10 hpdl Exp $

 CartStore eCommerce Software, for The Next Generation
 http://www.cartstore.com

 Copyright (c) 2008 Adoovo Inc. USA

 GNU General Public License Compatible
*/

 class osC_CategoryTree {
   var $root_category_id = 0,
       $max_level = 0,
       $data = array(),
       $root_start_string = '',
       $root_end_string = '',
       $parent_start_string = '',
       $parent_end_string = '',
       $parent_group_start_string = '<ul class="sitemap_ul">',
       $parent_group_end_string = '</ul>',
       $child_start_string = '<li>',
       $child_end_string = '</li>',
       $spacer_string = '',
       $spacer_multiplier = 1;

   function osC_CategoryTree($load_from_database = true) {
     global $languages_id;
         $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.parent_id, c.sort_order, cd.categories_name");
         $this->data = array();
         while ($categories = tep_db_fetch_array($categories_query)) {
			// Ultimate SEO URLs compatibility - Chemo
			# initialize array container
			$c = array();
			# Get the category path, $c is passed by reference
			tep_get_parent_categories($c, $categories['categories_id']);
			# For some reason it seems to return in reverse order so reverse the array
			$c = array_reverse($c);
			# Implode the array to get the full category path
			$id = (implode('_', $c) ? implode('_', $c) . '_' . $categories['categories_id'] : $categories['categories_id']);
           $this->data[$categories['parent_id']][$id] = array('name' => $categories['categories_name'], 'count' => 0);
         }
   }

   function buildBranch($parent_id, $level = 0) {
     $result = $this->parent_group_start_string;

     if (isset($this->data[$parent_id])) {
       foreach ($this->data[$parent_id] as $category_id => $category) {
         $category_link = $category_id;
         $result .= $this->child_start_string;
         if (isset($this->data[$category_id])) {
           $result .= $this->parent_start_string;
         }

         if ($level == 0) {
           $result .= $this->root_start_string;
         }
         $result .= str_repeat($this->spacer_string, $this->spacer_multiplier * $level) . '<a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $category_link) . '">';
         $result .= $category['name'];
         $result .= '</a>';
		 
		 $result .= $this->buildProducts($category_id);
         
		 if ($level == 0) {
           $result .= $this->root_end_string;
         }

         if (isset($this->data[$category_id])) {
           $result .= $this->parent_end_string;
         }

         $result .= $this->child_end_string;

         if (isset($this->data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1))) {
           $result .= $this->buildBranch($category_id, $level+1);
         }
       }
     }

     $result .= $this->parent_group_end_string;

     return $result;
   }
/**
	* function qui va ajouter a la liste result les produit de la category defini
	* vandoorn Bruno karando@karando.com
*/
	function buildProducts($category_id)
	{
     	global $languages_id;
		if (strpos($category_id,"_")!==false)
		{
			$categori_id = explode("_",$category_id); 
			$categori_id=$categori_id[sizeof($categori_id)-1];
		}
		else
		{
			$categori_id=$category_id;
		}
		$req="select * from products prod, products_to_categories prodcate , products_description proddescr
		where prod.products_status = '1' and prodcate.categories_id=".$categori_id." and prod.products_id=prodcate.products_id 
		and proddescr.language_id=".$languages_id." and proddescr.products_id=prod.products_id order by proddescr.products_name";
		$products_query = tep_db_query($req);
		//echo $req;
		$result="";
		$result .= $this->parent_group_start_string;
		while ($products = tep_db_fetch_array($products_query)) 
		{
			$result .= $this->child_start_string;
			$result.='<a href="' .tep_href_link(FILENAME_PRODUCT_INFO, ($category_id ? 'cPath=' . $category_id . '&' : '') . 'products_id=' . $products['products_id']) . '">' . $products['products_name'] . '</a>';
			$result .= $this->child_end_string;
		}
		$result .= $this->parent_group_end_string;
		return $result;
	}


   function buildTree() {
     return $this->buildBranch($this->root_category_id);
   }
 }
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
?>
