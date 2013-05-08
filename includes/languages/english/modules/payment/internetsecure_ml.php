<?php
/*
  $Id: paypal_ipn.php,v 1.8 2004/12/07 20:19:15 sparky Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/
  define('MODULE_PAYMENT_INTERNETSECURE_TEXT_TITLE', 'Credit Card');
  define('MODULE_PAYMENT_INTERNETSECURE_TEXT_DESCRIPTION', 'InternetSecure');

  define('MODULE_PAYMENT_INTERNETSECURE_CC_TEXT', "%s&nbsp;%s%s%s%s");

  define('MODULE_PAYMENT_INTERNETSECURE_IMAGE_BUTTON_CHECKOUT', 'InternetSecure Checkout');
  
  define('MODULE_PAYMENT_ITSEC_IMAGE','<img  alt="Credit Card" src="images/paymentswithIS.png"/>');
  define('MODULE_PAYMENT_INTERNETSECURE_CC_DESCRIPTION','  
  <br/>
  <ul class="norm"><li><strong>Pay in either US or Canadian currency</strong></li>
  <li>Credit Card processed by InternetSecure.</li>
  <li>For more information about Interac Online payments <a style="text-decoration:underline;" href=\'http://www.interaconline.com/learn\' target="_blank" onclick="window.open( \'http://www.interaconline.com/learn\',\'Learn\', \'toolbar=false,status=false,directories=false,location=false,menubar=false,resizable,scrollbars,width=700,height=500\');return false;">Click Here</a></li>
  </ul>'
 );
  
  
  
  //define('MODULE_PAYMENT_INTERNETSECURE_CC_DESCRIPTION','<ul class="norm"><li><strong>WE ARE STILL TESTING THIS METHOD WITH OUR SYSTEM!  DO NOT USE THIS METHOD OF PAYMENT!</strong></li></ul>');
  define('MODULE_PAYMENT_INTERNETSECURE_CC_URL_TEXT','<font color="blue"><u>[More Info]</u></font>');

  define('MODULE_PAYMENT_INTERNETSECURE_CUSTOMER_COMMENTS', 'Add Comments About Your Order');
  define('MODULE_PAYMENT_INTERNETSECURE_TEXT_TITLE_PROCESSING', 'Processing transaction');
  define('MODULE_PAYMENT_INTERNETSECURE_TEXT_DESCRIPTION_PROCESSING', 'If this page appears for more than 5 seconds, please click the InternetSecure Checkout button to complete your order.');
  define('MODULE_PAYMENT_INTERNETSECURE_IMAGE_BUTTON_CHECKOUT', 'InternetSecure Checkout');
?>