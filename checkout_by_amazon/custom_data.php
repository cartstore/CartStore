<?php
/**
 * @brief CustomData Class for use by merchant for adding custom data to cart
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

class CustomData {

	var $debug = false;
	var $stack = array();

	/*
	 *	@brief Based on module directory and item id, calls the appropriate module and puts the custom data in stack array
	 * @returns nothing
	 *
	 */
	function GetCustomData($module_dir,$item = null){

		$module_directory = DIR_FS_CATALOG . "checkout_by_amazon/modules/custom_data/".$module_dir."/";

		$directory_array = $this->getModules($module_dir);

		if($this->debug){
			print_r($directory_array);
		}
		/* reset the stack before using it */
		$this->stack = array();
		$installed_modules = array();

		for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {

			$file = $directory_array[$i];
			if(!(substr_compare($file, "php", -3, 3) == 0)) {
				continue;
			}
			include_once($module_directory . $file);
			$class = substr($file, 0, strrpos($file, '.'));

			if (class_exists($class)) {			  
			  $module = new $class;
			  $data = $module->custom_data($item);
			  if(isset($data)){
				$this->stack[$class] = $data;
			  }
			}else{			
				/* class not found */
				trigger_error($class . " class not found", E_USER_WARNING);
			}

		}
	}

	/*
	 *	@brief returns the list of modules for a given module directory
	 * @returns modules array
	 *
	 */
	function getModules($module_dir){
	
		$module_directory = DIR_FS_CATALOG . "checkout_by_amazon/modules/custom_data/" . $module_dir . "/";
		$directory_array = array();
		if ($dir = dir($module_directory)) {
			while ($file = $dir->read()) {
				
				 if (!is_dir($module_directory . $file)) {
					 $directory_array[] = $file;
				 }
			}
			sort($directory_array);
			$dir->close();
		}
		if($this->debug){
			print_r($directory_array);
		}
		return $directory_array;
	}

	/*
	 *	@brief calls the item modules
	 * @returns item custom data array
	 *
	 */
	function GetItemCustomXml($item){
		$this->GetCustomData("item", $item);
		if($this->debug){
			print_r($this->stack);
		}
		return $this->stack;		 
	}

	/*
	 *	@brief calls the cart modules
	 * @returns cart custom data array
	 *
	 */
	function GetCartCustomXml(){	
		$this->GetCustomData("cart");
		if($this->debug){
			print_r($this->stack);
		}
		return $this->stack;		 
	}
}
?>
