<?php

/*
  $Id: new_attributes_functions.php 
  
New Attribute Manager v4b, Author: Mike G.
  
  Updates for New Attribute Manager v.5.0 and multilanguage support by: Kiril Nedelchev - kikoleppard
  kikoleppard@hotmail.bg
  
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

// A simple little function to determine if the current value is already selected for the current product.
function checkAttribute( $current_value_id, $current_product_id, $current_product_option_id )
{
 global $attribute_value_price, $attribute_value_prefix, /*KIKOLEPPARD zero prefix add*/$zeroCheck/*KIKOLEPPARD zero prefix add*/, $posCheck, $negCheck;

 $query = "SELECT * FROM products_attributes where options_values_id = '$current_value_id' AND products_id = '$current_product_id' AND options_id = '$current_product_option_id'";

 $result = mysql_query($query) or die(mysql_error());

 $isFound = mysql_num_rows($result);
 
 if ($isFound) {

    while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        
        $attribute_value_price = $line['options_values_price'];
        $attribute_value_prefix = $line['price_prefix'];
        //KIKOLEPPARD zero prefix start
		 if ($attribute_value_prefix == " ") { $zeroCheck = "SELECTED";
											  $posCheck = "";
                                              $negCheck = "";
											  }
        elseif ($attribute_value_prefix == "+") { $zeroCheck = "";
											  $posCheck = " SELECTED";
                                              $negCheck = "";
                                              
        } else { $zeroCheck = "";
				 $posCheck = "";
                 $negCheck = " SELECTED";

        }

      }
		//KIKOLEPPARD zero prefix end
    return true; 
    
    } else {
    
    $attribute_value_price = ""; 
    $attribute_value_prefix = "";
   //KIKOLEPPARD add
   $zeroCheck = "";
   //KIKOLEPPARD add
   $posCheck = "";
    $negCheck = "";

    return false; }

}

function rowClass($i){
    $class1 = "attributes-odd";
    $class2 = "attributes-even";

    if ( $i%2 ) {
        return $class1;
    } else {
        return $class2;
    } 
}

// For Options Type Contribution
function extraValues( $current_value_id, $current_product_id )
{
 global $attribute_qty, $attribute_order, $attribute_linked, $attribute_prefix, $attribute_type, $isSelected;

 if ( $isSelected ) {
                    	
        $query = "SELECT * FROM products_attributes where options_values_id = '$current_value_id' AND products_id = '$current_product_id'";

        $result = mysql_query($query) or die(mysql_error());

        while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                                                	
                $attribute_qty = $line['options_values_qty'];
                $attribute_order = $line['attribute_order'];
                $attribute_linked = $line['collegamento'];
                $attribute_prefix = $line['price_prefix'];
                $attribute_type = $line['options_type_id'];

        }
        
 } else {
        	
        	$attribute_qty = "1";
        	$attribute_order = "100";
        	$attribute_linked = "0";
        	$attribute_prefix = "";
        	$attribute_type = "";
 }
 
}

function displayOptionTypes( $attribute_type )
{
 global $isSelected;

 $availableTypes = array( 'Disabled' => '0', 'Select' => '1', 'Checkbox' => '2', 'Radio' => '3', 'Select Multiple' => '4', 'Text' => '5' );
 
 foreach( $availableTypes as $name => $id ){
                                            	
        if ( $isSelected && $attribute_type == $id ) { $SELECT = " SELECTED"; }

        else { $SELECT = ""; }
                                                                                              	
        echo "<OPTION VALUE=\"" . $id . "\"" . $SELECT . ">" . $name;
        
 }

}

// Get values for Linda McGrath's contribution
function getSortCopyValues( $current_value_id, $current_product_id )
{
 global $attribute_sort, $attribute_weight, $attribute_weight_prefix, $isSelected;

 if ( $isSelected ) {
                    	
        $query = "SELECT * FROM products_attributes where options_values_id = '$current_value_id' AND products_id = '$current_product_id'";

        $result = mysql_query($query) or die(mysql_error());

        while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                                                	
                $attribute_sort = $line['products_options_sort_order'];
                $attribute_weight = $line['products_attributes_weight'];
                $attribute_weight_prefix = $line['products_attributes_weight_prefix'];

        }
        
 } else {
        	
        	$attribute_sort = "0";
        	$attribute_weight = "";
        	$attribute_weight_prefix = "";
 }
 
}
function sortCopyWeightPrefix( $attribute_weight_prefix )
{
 global $isSelected;
 
 $availablePrefixes = array( /*KIKOLEPPARD add*/' ', /*KIKOLEPPARD add*/'+', '-' );
 
 foreach( $availablePrefixes as $prefix ) {
                                          	
        if ( $isSelected && $prefix == $attribute_weight_prefix ) {
                                                                  	
                $SELECT = " SELECTED";
                
        } else { $SELECT = ""; }
        
        echo "<OPTION VALUE=\"" . $prefix . "\"" . $SELECT . ">" . $prefix;
        
        }
        
}

?>












