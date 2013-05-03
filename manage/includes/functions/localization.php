<?php
/*
  $Id: localization.php,v 1.12 2003/06/25 20:36:48 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  function quote_oanda_currency($code, $base = DEFAULT_CURRENCY) {
    $page = file('http://www.oanda.com/convert/fxdaily?value=1&redirected=1&exch=' . $code .  '&format=CSV&dest=Get+Table&sel_list=' . $base);

    $match = array();

    preg_match('/(.+),(\w{3}),([0-9.]+),([0-9.]+)/i', implode('', $page), $match);

    if (sizeof($match) > 0) {
      return $match[3];
    } else {
      return false;
    }
  }

  function quote_xe_currency($to, $from = DEFAULT_CURRENCY) {
    $page = file('http://www.xe.net/ucc/convert.cgi?Amount=1&From=' . $from . '&To=' . $to);

    $match = array();

    preg_match('/[0-9.]+\s*' . $from . '\s*=\s*([0-9.]+)\s*' . $to . '/', implode('', $page), $match);

    if (sizeof($match) > 0) {
      return $match[1];
    } else {
      return false;
    }
  }
  
  
  
  function quote_ECBank_currency($to) {
# Read currency exchanges rates
$xmlcontents = file("http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml");

# $xld may be used in your output to inform you user or admin
# Extract exchange rates
foreach ($xmlcontents as $line) {
ereg("currency='([[:alpha:]]+)'",$line,$gota);
if (ereg("rate='([[:graph:]]+)'",$line,$gotb)) {
$exchrate[$gota[1]] = $gotb[1];
}
}
$exchrate['EUR'] = 1; /* manually add 1 EUR = 1 EUR to the array (all Exch.Rates are from EUR to X because we're getting them from ECB*/

if (!array_key_exists(DEFAULT_CURRENCY, $exchrate)) {
return false; /* the Store Default currency must be present in the list because these exchange rates are based in 1EUR=xx
Therefore we need to calculate the rate from EUR to the Default currency and then from the Default currency to the destination currency */
}

if (!array_key_exists($to, $exchrate)) {
return false;
}

$DefaultCurr_to_EUR_Rate = round(1 / $exchrate[DEFAULT_CURRENCY], 8);
$DefaultCurr_to_DestCurr = round($DefaultCurr_to_EUR_Rate * $exchrate[$to], 8);

if (is_numeric($DefaultCurr_to_DestCurr) && $DefaultCurr_to_DestCurr > 0) { /* make sure we got a valid number */
return $DefaultCurr_to_DestCurr;
} else {
return false;
}
}
  
  
  
  
?>
