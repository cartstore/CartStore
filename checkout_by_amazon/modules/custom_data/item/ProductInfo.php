<?php
/**
 * @brief ProductInfo Class for populating product info into item custom data
 * @catagory Zen Cart Checkout by Amazon Payment Module
 * @author Balachandar Muruganantham
 * @copyright Portions Copyright 2007-2009 Amazon Technologies, Inc
 * @license GPL v2, please see LICENSE.txt
 * @access public
 * @version $Id: $
 */
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
                                                                                                 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
                                                                                                 
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/

class ProductInfo {

	/* 
	 * @brief constrcutor
	 */
    function ProductInfo() {
		
	}

	/* 
	 * @brief custom data is called in button generator 
	 * @param item -  item associative array passed from button generator
	 * @return associative array 
	 */
    function custom_data($item) {

	   $custom_data = array();
	   //return $custom_data;
	}

}
?>
