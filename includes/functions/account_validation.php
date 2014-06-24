<?php
/*
  $Id: account_validation.php,v 2.1a 2004/08/10 20:19:27 chaicka Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

////
// This function validates the created profile
// search engine spiders will not know what to do here, so you will not have automatic profiles from them
  function gen_reg_key(){
	$key = '';
	$chars = array('a','b','c','d','e','f','g','h','i','j', 'k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z', '1', '2', '3', '4', '5', '6', '7', '8', '9');
	$count = count($chars) - 1;
	
	srand((double)microtime()*1000000);
	for($i = 0; $i < ENTRY_VALIDATION_LENGTH; $i++){
	  $key .= $chars[rand(0, $count)];
	}
	
// Replace 'O' with 'Z' to avoid confused with numeric '0'	
	$key = str_replace('O', 'Z', strtoupper($key));
    return($key);
  }
?>