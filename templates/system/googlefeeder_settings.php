<?php
/*************** BEGIN MASTER SETTINGS ******************/

define('SEO_ENABLED','true');    //Change to 'false' to disable if Ultimate SEO URLs is not installed
define('FEEDNAME', 'your-outfile.txt');       //from your googlebase account
define('DOMAIN_NAME',   'tirecovers.com/catalog'); //your correct domain name (don't include www unless it is used)
define('FTP_USERNAME', 'NULL'); //from your googlebase account
define('FTP_PASSWORD', 'NULL'); //from your googlebase account
define('FTP_ENABLED', '0');      //set to 0 to disable
define('CONVERT_CURRENCY', '0'); //set to 0 to disable - only needed if a feed in a difference currecny is required
define('CURRENCY_TYPE', 'USD');  //(eg. USD, EUR, GBP)
define('DEFAULT_LANGUAGE', 1);   //Change this to the id of your language.  BY default 1 is english

define('OPTIONS_ENABLED', 1);
define('OPTIONS_ENABLED_AGE_RANGE', 0);
define('OPTIONS_ENABLED_BRAND', 1);
define('OPTIONS_ENABLED_CONDITION', 1);
define('OPTIONS_ENABLED_CURRENCY', 0);
define('OPTIONS_ENABLED_FEED_LANGUAGE', 0);
define('OPTIONS_ENABLED_FEED_MANUFACTURE_ID', 0);
define('OPTIONS_ENABLED_FEED_QUANTITY', 0);
define('OPTIONS_ENABLED_MADE_IN', 0);
define('OPTIONS_ENABLED_MANUFACTURER', 0);
define('OPTIONS_ENABLED_PAYMENT_ACCEPTED', 0);
define('OPTIONS_ENABLED_PRODUCT_TYPE', 0);

            if (defined(MODULE_SHIPPING_INDVSHIP_STATUS) && MODULE_SHIPPING_INDVSHIP_STATUS == 'True'){
                     define('OPTIONS_ENABLED_SHIPPING', 1);
			} else {
                     define('OPTIONS_ENABLED_SHIPPING', 0);
			}
define('OPTIONS_ENABLED_TAX', 1);
define('OPTIONS_ENABLED_UPC', 0);
define('OPTIONS_ENABLED_WEIGHT', 0);

//the following only matter if the matching option is enabled above.
define('OPTIONS_AGE_RANGE', '0-9');
define('OPTIONS_BRAND', '');
define('OPTIONS_CONDITION', 'New');  //possible entries are New, Refurbished, Used
define('OPTIONS_DEFAULT_CURRENCY', 'USD');
define('OPTIONS_DEFAULT_FEED_LANGUAGE', 'en');
define('OPTIONS_LOWEST_SHIPPING', '1'); //this is not binary.  Custom Code is required to provide the shipping cost per product.  ###needs to be an array for per product.
define('OPTIONS_TAX', "::0:");
define('OPTIONS_MADE_IN', 'USA');
define('OPTIONS_PAYMENT_ACCEPTED_METHODS', ''); //Acceptable values: cash, check, GoogleCheckout, Visa, MasterCard, AmericanExpress, Discover, wiretransfer
define('OPTIONS_WEIGHT_ACCEPTED_METHODS', 'lb'); //Valid units include lb, pound, oz, ounce, g, gram, kg, kilogram.

define('OPTIONS_AVAILABILITY', 'quantity');     //in stock - Include this value if you are certain that it will ship (or be in-transit to the customer) in 3 business days or less.
                                                //available for order - Include this value if it will take 4 or more business days to ship it to the customer.
                                                //out of stock - Youre currently not accepting orders for this product.
                                                //preorder - You are taking orders for this product, but its not yet been released.
                                                //if empty (no entry), the data will be loaded from the database. A field in the products description table named products_availability is required
                                                //if "quantity," the field will be popuplated via the quantity: 0 or less = out of stock, greater than 0 = in stock
                                                //if "status," the field will be popuplated via the status field. in or out of stock



/*************** END MASTER SETTINGS ******************/

?>
