<?php
/**
 * @brief Functions
 * @catagory osCommerce Checkout by Amazon Payment Module - Utility Functions file
 * @author Balachandar Muruganantham
 * @copyright 2009-2009 Amazon Technologies, Inc
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

/* function to dump objects */
function ob_writelog($str,$obj){
  ob_start();
  var_dump($obj);
  $content=ob_get_contents();
  ob_end_clean();
  writelog($str ." -> ". $content);
}

/* generic function to write the log */
function writelog($content){
  if(MODULE_PAYMENT_CHECKOUTBYAMAZON_USE_DIAGNOSTIC_LOGGING == 'True') {

    if(!file_exists(LOG_FILE)){
      $handle = fopen(LOG_FILE,"w");
    }else{
      $handle = @fopen(LOG_FILE,"a+");
    }

   if(!$handle){
     return;
   }

    if (is_writable(LOG_FILE)) {

      $somecontent .= date("D M j G:i:s T Y") ." :- " . $content . "\n";
      $somecontent .= "-----------------------------------------------------\n";

      if (fwrite($handle, $somecontent) === FALSE) {
        return;
      }
      fclose($handle);
    }
  }
}

/* Logs the POST request */
function requestlog(){
  if($_POST){
    foreach ($_POST as $k => $v) {
      $somecontent .= "$k = ".str_replace("\\\"","\"",$v)."\n";
    }
    writelog($somecontent);
  }
}
?>
