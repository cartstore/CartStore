<?php
/**
 * @brief Various constants for Checkout by Amazon code  
 * @catagory osCommerce Checkout by Amazon Payment Module
 * @author Allison Naaktgeboren
 * @author Joshua Wong
 * @copyright 2007-2009 Amazon Technologies, Inc
 * @license GPL v2, please see LICENSE.txt
 * @access public
 * @version $Id: $
 *  
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


define('DIR_WS_CBA', DIR_FS_CATALOG . 'checkout_by_amazon/');
define('DIR_WS_CBA_LIB', DIR_WS_CBA . 'library/');
ini_set('include_path','.:' .
          DIR_FS_CATALOG .":" . 
          DIR_FS_CATALOG . "checkout_by_amazon:".
          DIR_FS_CATALOG . "checkout_by_amazon/library/PHP_Compat-1.6.0a1:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/SOAP-0.12.0:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/PEAR-1.7.2:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/PEAR-1.7.2/PEAR:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/HTTP-1.4.3:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/Mail_Mime-1.5.2:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/Mail_mimeDecode-1.5.0:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/Net_Socket-1.0.9:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/Net_URL-1.0.15:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/XML_Parser-1.3.1:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/XML_Serializer-0.19.0:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/XML_Util-1.2.1:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library:" .
          ini_get('include_path'));

require_once("PEAR/PEAR.php");
require_once("XML/Serializer.php");
require_once("HTTP/Request.php");
?>
