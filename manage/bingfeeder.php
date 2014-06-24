<?php
//  Title: Bing Data Feeder
//  Last Update: 12/25/09 by Jack_mcs at oscommerce-solution.com


/*************** BEGIN MASTER SETTINGS ******************/

define('SEO_ENABLED','true');    //Change to 'false' to disable if Ultimate SEO URLs is not installed
define('FEEDNAME', 'bingshopping.txt');       //required to be this name
define('DOMAIN_NAME', 'www.yourwebsite.com'); //your correct domain name (don't include www unless it is used)
define('FTP_USERNAME', 'googleFTP-username'); //created from within your googlebase account
define('FTP_PASSWORD', 'googleFTP-password'); //created from within your googlebase account
define('FTP_ENABLED', (isset($_GET['noftp']) ? '0' : '1'));      //set to 0 to disable
define('CONVERT_CURRENCY', '0'); //set to 0 to disable - only needed if a feed in a difference currecny is required
define('CURRENCY_TYPE', 'USD');  //(eg. USD, EUR, GBP)
define('DEFAULT_LANGUAGE', 1);   //Change this to the id of your language.  BY default 1 is english
define('QUOTES_CATEGORY_NAME',''); //if the Quotes contribution is installed, enter the name of the quotes category here

define('OPTIONS_ENABLED', 1);
define('OPTIONS_ENABLED_AGE_RANGE', 0);
define('OPTIONS_ENABLED_ATTRIBUTES', 0);
define('OPTIONS_ENABLED_BRAND', 0);
define('OPTIONS_ENABLED_CONDITION', 1);
define('OPTIONS_ENABLED_CURRENCY', 0);
define('OPTIONS_ENABLED_FEED_LANGUAGE', 0);
define('OPTIONS_ENABLED_FEED_QUANTITY', 0);
define('OPTIONS_ENABLED_MADE_IN', 0);
define('OPTIONS_ENABLED_MANUFACTURER', 0);         //displays the manufacturer name
define('OPTIONS_ENABLED_PAYMENT_ACCEPTED', 0);
define('OPTIONS_ENABLED_PRODUCT_MODEL', 0); //displays the product model
define('OPTIONS_ENABLED_PRODUCT_TYPE', 0);
define('OPTIONS_ENABLED_SHIPPING', 0);
define('OPTIONS_ENABLED_INCLUDE_TAX', 0);
define('OPTIONS_ENABLED_UPC', 0);
define('OPTIONS_ENABLED_WEIGHT', 1);  //required

//the following only matter if the matching option is enabled above.
define('OPTIONS_AGE_RANGE', '20-90 years');
define('OPTIONS_BRAND', '');
define('OPTIONS_CONDITION', 'New');  //possible entries are New, Refurbished, Used
define('OPTIONS_DEFAULT_CURRENCY', 'USD');
define('OPTIONS_DEFAULT_FEED_LANGUAGE', 'en');
define('OPTIONS_LOWEST_SHIPPING', ''); //this is not binary.  Custom Code is required to provide the shipping cost per product.  ###needs to be an array for per product.
define('OPTIONS_MADE_IN', 'US');
define('OPTIONS_MANUFACTURERS_NAME_IGNORE', ''); //list if comma separated manufacturer names to be skipped - e.g. Matrox,Fox
define('OPTIONS_PAYMENT_ACCEPTED_METHODS', '');  //Acceptable values: Cash, Check, GoogleCheckout, Visa, MasterCard, AmericanExpress, Discover, wiretransfer
define('OPTIONS_TAX_RATE', 0); //default = 0 (e.g. for 17.5% tax use 17.5)
define('OPTIONS_WEIGHT_ACCEPTED_METHODS', 'lb'); //Valid units include lb, pound, oz, ounce, g, gram, kg, kilogram.

/*************** END MASTER SETTINGS ******************/

/*************** NO EDITS NEEDED BELOW THIS LINE *****************/

require_once('includes/application_top.php');
require_once('../includes/configure.php');

if (! function_exists("tep_not_null"))
{
  function tep_not_null($value) {
    if (is_array($value)) {
      if (sizeof($value) > 0) {
        return true;
      } else {
        return false;
      }
    } else {
      if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
        return true;
      } else {
        return false;
      }
    }
  }
}

if(SEO_ENABLED=='true'){
  //********************
  // Modification for SEO
  // Since the ultimate SEO was only installed on the public side, we will include our files from there.
  require_once('../includes/filenames.php');
  require_once('../includes/database_tables.php');

  include_once('../' .DIR_WS_CLASSES . 'seo.class.php');
  $seo_urls = new SEO_URL(DEFAULT_LANGUAGE);

  function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
     global $seo_urls;
     return $seo_urls->href_link($page, $parameters, $connection, $add_session_id);
  }
}

//********************
//  Start TIMER
//  -----------
$stimer = explode( ' ', microtime() );
$stimer = $stimer[1] + $stimer[0];
//  -----------


$OutFile = "../feeds/" . FEEDNAME;
$destination_file = FEEDNAME;
$source_file = $OutFile;
$imageURL = 'http://' . DOMAIN_NAME . '/images/';
if(SEO_ENABLED=='true'){
   $productURL = 'product_info.php'; // ***** Revised for SEO
   $productParam = "products_id=";   // ***** Added for SEO
}else{
   $productURL = 'http://' . DOMAIN_NAME . '/product_info.php?products_id=';
}

$already_sent = array();
$taxCalc = (OPTIONS_ENABLED_INCLUDE_TAX ? (OPTIONS_TAX_RATE/100) + 1 : 1);  //Do not edit

if(CONVERT_CURRENCY)
{
   if(SEO_ENABLED=='true'){
       $productParam="currency=" . CURRENCY_TYPE . "&products_id=";
   }else{
       $productURL = "http://" . DOMAIN_NAME . "/product_info.php?currency=" . CURRENCY_TYPE . "&products_id=";  //where CURRENCY_TYPE is your currency type (eg. USD, EUR, GBP)
   }
}

date_default_timezone_set('Europe/Berlin');
$feed_exp_date = date('Y-m-d', time() + 2419200 );

/*
if (!($link=mysql_connect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD)))
{
echo "Error when connecting itself to the data base";
exit();
}
if (!mysql_select_db( DB_DATABASE , $link ))
{
echo "Error the data base does not exist";
exit();
}
*/

$quotes = '';
if (QUOTES_CATEGORY_NAME !== '') {
  $quotes = " and products.customers_email_address = '' and products.quotes_email_address = ''";
}

$sql = "
SELECT concat( '" . $productURL . "' ,products.products_id) AS product_url,
products_model AS prodModel,
manufacturers.manufacturers_name AS mfgName,
manufacturers.manufacturers_id,
products.products_id AS id,
products_description.products_name AS name,
products_description.products_description AS description,
products.products_quantity AS quantity,
products.products_status AS prodStatus,
products.products_weight AS prodWeight,
FORMAT( IFNULL(specials.specials_new_products_price, products.products_price) * " . $taxCalc . ",2) AS price,
CONCAT( '" . $imageURL . "' ,products.products_image) AS image_url,
products_to_categories.categories_id AS prodCatID,
categories.parent_id AS catParentID,
categories_description.categories_name AS catName
FROM (categories,
categories_description,
products,
products_description,
products_to_categories)

left join manufacturers on ( manufacturers.manufacturers_id = products.manufacturers_id )
left join specials on ( specials.products_id = products.products_id AND ( ( (specials.expires_date > CURRENT_DATE) OR (specials.expires_date is NULL) OR (specials.expires_date = 0) ) AND ( specials.status = 1 ) ) )

WHERE products.products_id=products_description.products_id
AND products.products_id=products_to_categories.products_id
AND products_to_categories.categories_id=categories.categories_id
AND categories.categories_id=categories_description.categories_id " . $quotes . "
AND categories_description.language_id = " . DEFAULT_LANGUAGE . "
AND products_description.language_id = " . DEFAULT_LANGUAGE . "
ORDER BY
products.products_id ASC
";

$quotes = '';
if (QUOTES_CATEGORY_NAME !== '') {
  $quotes = " and categories_description.categories_name NOT LIKE '" . QUOTES_CATEGORY_NAME . "' ";
}

$catInfo = "
SELECT
categories.categories_id AS curCatID,
categories.parent_id AS parentCatID,
categories_description.categories_name AS catName
FROM
categories,
categories_description
WHERE categories.categories_id = categories_description.categories_id " . $quotes . "
AND categories_description.language_id = " . DEFAULT_LANGUAGE . "";

function findCat($curID, $catTempPar, $catTempDes, $catIndex)
{
	if( (isset($catTempPar[$curID])) && ($catTempPar[$curID] != 0) )
	{
		if(isset($catIndex[$catTempPar[$curID]]))
		{
			$temp=$catIndex[$catTempPar[$curID]];
		}
		else
		{
			$catIndex = findCat($catTempPar[$curID], $catTempPar, $catTempDes, $catIndex);
			$temp = $catIndex[$catTempPar[$curID]];
		}
	}
	if( (isset($catTempPar[$curID])) && (isset($catTempDes[$curID])) && ($catTempPar[$curID] == 0) )
	{
		$catIndex[$curID] = $catTempDes[$curID];
	}
	else
	{
		$catIndex[$curID] = $temp . ", " . $catTempDes[$curID];
	}
	return $catIndex;

}

$catIndex = array();
$catTempDes = array();
$catTempPar = array();
$processCat = tep_db_query( $catInfo )or die( $FunctionName . ": SQL error " . mysqli_error() . "| catInfo = " . htmlentities($catInfo) );
while ( $catRow = tep_db_fetch_object( $processCat ) )
{
	$catKey = $catRow->curCatID;
	$catName = $catRow->catName;
	$catParID = $catRow->parentCatID;
	if($catName != "")
	{
		$catTempDes[$catKey]=$catName;
		$catTempPar[$catKey]=$catParID;
	}
}

foreach($catTempDes as $curID=>$des)  //don't need the $des
{
	$catIndex = findCat($curID, $catTempPar, $catTempDes, $catIndex);
}

$_strip_search = array(
"![\t ]+$|^[\t ]+!m", // remove leading/trailing space chars
'%[\r\n]+%m'); // remove CRs and newlines
$_strip_replace = array(
'',
' ');
$_cleaner_array = array(">" => "> ", "&reg;" => "", "®" => "", "&trade;" => "", "™" => "", "\t" => "", "	" => "");

if ( file_exists( $OutFile ) )
unlink( $OutFile );

$output = "ProductURL\tTitle \tDescription\tPrice\tImageURL\tMerchantCategory\tMerchantProductID\tSKU\tAvailability";

$attributesColumns = array();

//create optional section
if(OPTIONS_ENABLED == 1)
{
	if(OPTIONS_ENABLED_AGE_RANGE == 1)
		$output .= "\tage_range";
	if(OPTIONS_ENABLED_BRAND == 1)
		$output .= "\tbrand";
	if(OPTIONS_ENABLED_CONDITION == 1)
		$output .= "\tcondition";
	if(OPTIONS_ENABLED_CURRENCY == 1)
		$output .= "\tcurrency";
	if(OPTIONS_ENABLED_FEED_LANGUAGE == 1)
		$output .= "\tlanguage";
	if(OPTIONS_ENABLED_FEED_QUANTITY == 1)
		$output .= "\tquantity";
	if(OPTIONS_ENABLED_MADE_IN == 1)
		$output .= "\tmade_in";
	if(OPTIONS_ENABLED_MANUFACTURER == 1)
		$output .= "\tmanufacturer";
	if(OPTIONS_ENABLED_PAYMENT_ACCEPTED == 1)
		$output .= "\tpayment_accepted";
	if(OPTIONS_ENABLED_PRODUCT_MODEL == 1)
		$output .= "\tmpn";
	if(OPTIONS_ENABLED_PRODUCT_TYPE == 1)
		$output .= "\tproduct_type";
	if(OPTIONS_ENABLED_SHIPPING == 1)
		$output .= "\tShippingWeight";
	if(OPTIONS_ENABLED_UPC == 1)
		$output .= "\tupc";
	if(OPTIONS_ENABLED_WEIGHT == 1)
		$output .= "\tShippingWeight";

  if (OPTIONS_ENABLED_ATTRIBUTES == 1)
  {
    $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from products_options popt, products_attributes patrib where popt.language_id = '" . (int)1 . "' order by popt.products_options_name");
    while ($products_options_name = tep_db_fetch_object($products_options_name_query)) {
      $attributesColumns[] = $products_options_name->products_options_name;
      $name = strtolower($products_options_name->products_options_name);
      $name = str_replace(" ","_", $name);
      $output .= "\tc:" . $name;
    }

    /*
    //If you want to only show particular attributes, comment out the above and uncomment this section.
    //Then enter two lines for each one you want to show. For example, if the atttributes you want to
    //show are named Color and Fabric, the entries would appear as follows:

    $attributesColumns[] = "Color";
    $attributesColumns[] = "Fabric";

    $output .= "\tc:" . strtolower("Color");
    $output .= "\tc:" . strtolower("Fabric");
    */
  }
}
$output .= "\n";


$result=tep_db_query( $sql )or die( $FunctionName . ": SQL error " . mysqli_error() . "| sql = " . htmlentities($sql) );

//Currency Information
if(CONVERT_CURRENCY)
{
	$sql3 = "
	SELECT
	currencies.value AS curUSD
	FROM
	currencies
	WHERE currencies.code = '" . CURRENCY_TYPE . "'";

	$result3=tep_db_query( $sql3 )or die( $FunctionName . ": SQL error " . mysqli_error() . "| sql3 = " . htmlentities($sql3) );
	$row3 = tep_db_fetch_object( $result3 );
}

$loop_counter = 0;

while( $row = tep_db_fetch_object( $result ) )
{
	if (isset($already_sent[$row->id])) continue; // if we've sent this one, skip the rest of the while loop

	if( $row->prodStatus == 1 || (OPTIONS_ENABLED == 1 && $quantity == 1) )
	{
		if (CONVERT_CURRENCY)
		{
			$row->price = preg_replace("/[^.0-9]/", "", $row->price);
			$row->price = $row->price *  $row3->curUSD;
			$row->price = number_format($row->price, 2, '.', ',');
		}

      if(SEO_ENABLED=='true'){
            $output .= tep_href_link($productURL,$productParam . $row->id) . "\t" .
            preg_replace($_strip_search, $_strip_replace, strip_tags( strtr($row->name, $_cleaner_array) ) ) . "\t" .
            preg_replace($_strip_search, $_strip_replace, strip_tags( strtr($row->description, $_cleaner_array) ) ) . "\t" .
            $row->price . "\t" .
            $row->image_url . "\t" .
            $catIndex[$row->prodCatID] . "\t" .
            $row->id . "\t" .
            $row->id . "\t" .
            $row->prodStatus;
      }else{
      		$output .= $row->product_url . "\t" .
      		preg_replace($_strip_search, $_strip_replace, strip_tags( strtr($row->name, $_cleaner_array) ) ) . "\t" .
      		preg_replace($_strip_search, $_strip_replace, strip_tags( strtr($row->description, $_cleaner_array) ) ) . "\t" .
      		$row->price . "\t" .
      		$row->image_url . "\t" .
      		$catIndex[$row->prodCatID] . "\t" .
      		$row->id . "\t" .
      		$row->id . "\t" .
        $row->prodStatus;
      }

      //optional values section
      if(OPTIONS_ENABLED == 1)
      {
         if(OPTIONS_ENABLED_AGE_RANGE == 1)
            $output .= "\t" . OPTIONS_AGE_RANGE;
         if(OPTIONS_ENABLED_BRAND == 1)
            $output .= "\t" . OPTIONS_BRAND;
         if(OPTIONS_ENABLED_CONDITION == 1)
            $output .= "\t" . OPTIONS_CONDITION;
         if(OPTIONS_ENABLED_CURRENCY == 1)
            $output .= "\t" . OPTIONS_DEFAULT_CURRENCY;
         if(OPTIONS_ENABLED_FEED_LANGUAGE == 1)
            $output .= "\t" . OPTIONS_DEFAULT_FEED_LANGUAGE;
         if(OPTIONS_ENABLED_FEED_QUANTITY == 1)
            $output .= "\t" . $row->quantity;
         if(OPTIONS_ENABLED_MADE_IN == 1)
            $output .= "\t" . OPTIONS_MADE_IN;
         if(OPTIONS_ENABLED_MANUFACTURER == 1)
            $output .= "\t" . (in_array($row->mfgName,explode(",",OPTIONS_MANUFACTURERS_NAME_IGNORE)) ? '' : $row->mfgName);
         if(OPTIONS_ENABLED_PAYMENT_ACCEPTED == 1)
            $output .= "\t" . OPTIONS_PAYMENT_ACCEPTED_METHODS;
         if(OPTIONS_ENABLED_PRODUCT_MODEL == 1)
            $output .= "\t" . $row->prodModel;
         if(OPTIONS_ENABLED_PRODUCT_TYPE == 1)
         {
            $catNameTemp = strtolower($catName);
            $output .= "\t" . $row->catName;
         }
         if(OPTIONS_ENABLED_SHIPPING == 1)
            $output .= "\t" . $row->prodWeight;
         if(OPTIONS_ENABLED_UPC == 1)
            $output .= "\t" . "Not Supported";
         if(OPTIONS_ENABLED_WEIGHT == 1)
            $output .= "\t" . $row->prodWeight . ' ' .OPTIONS_WEIGHT_ACCEPTED_METHODS;

         /******************* BEGIN HANDLING THE ATTRIBUTES ********************/
         if (OPTIONS_ENABLED_ATTRIBUTES == 1)
         {
            $products_attributes_query = tep_db_query("select count(*) as total from products_options popt, products_attributes patrib where patrib.products_id='" . $row->id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)1 . "'");
            $products_attributes = tep_db_fetch_object($products_attributes_query);
            if ($products_attributes->total > 0) {
              $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from products_options popt, products_attributes patrib where patrib.products_id='" . (int)$row->id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)1 . "' order by popt.products_options_name");

              $trackTabs = '';

              while ($products_options_name = tep_db_fetch_object($products_options_name_query)) {
                $products_options_array = array();
                $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from products_attributes pa, products_options_values pov where pa.products_id = '" . (int)$row->id . "' and pa.options_id = '" . $products_options_name->products_options_id . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)1 . "'");
                while ($products_options = tep_db_fetch_object($products_options_query)) {
                  $products_options_array[] = array('id' => $products_options->products_options_values_id, 'text' => $products_options->products_options_values_name);
                }

                for ($a = 0; $a < count($attributesColumns); ++$a)
                {
                   if ($products_options_name->products_options_name == $attributesColumns[$a])
                   {
                     if ($a == 0)
                       $trackTabs = "\t";
                     else
                     {
                       if (empty($trackTabs))
                         $trackTabs = str_repeat("\t", $a);
                       $trackTabs .= "\t";
                     }

                     $output .= $trackTabs;
                     foreach ($products_options_array as $arr)
                       $output .=  $arr['text'] . ',';
                     $output = substr($output, 0, -1);
                   }
                }
              }
            }
         }
         /******************* END HANDLING THE ATTRIBUTES ********************/
      }
      $output .= " \n";
   }

   $already_sent[$row->id] = 1;
   $loop_counter++;

   if ($loop_counter>750) {
      $fp = fopen( $OutFile , "a" );
      $fout = fwrite( $fp , $output );
      fclose( $fp );
      $loop_counter = 0;
      $output = "";
   }
}

$fp = fopen( $OutFile , "a" );
$fout = fwrite( $fp , $output );
fclose( $fp );
echo "File completed: <a href=\"" . $OutFile . "\" target=\"_blank\">" . $destination_file . "</a><br>\n";
chmod($OutFile, 0777);


//Start FTP

function ftp_file( $ftpservername, $ftpusername, $ftppassword, $ftpsourcefile, $ftpdirectory, $ftpdestinationfile )
{
// set up basic connection
$conn_id = ftp_connect($ftpservername);
if ( $conn_id == false )
{
echo "FTP open connection failed to $ftpservername <BR>\n" ;
return false;
}

// login with username and password
$login_result = ftp_login($conn_id, $ftpusername, $ftppassword);

// check connection
if ((!$conn_id) || (!$login_result)) {
echo "FTP connection has failed!<BR>\n";
echo "Attempted to connect to " . $ftpservername . " for user " . $ftpusername . "<BR>\n";
return false;
} else {
echo "Connected to " . $ftpservername . ", for user " . $ftpusername . "<BR>\n";
}

if ( strlen( $ftpdirectory ) > 0 )
{
if (ftp_chdir($conn_id, $ftpdirectory )) {
echo "Current directory is now: " . ftp_pwd($conn_id) . "<BR>\n";
} else {
echo "Couldn't change directory on $ftpservername<BR>\n";
return false;
}
}

ftp_pasv ( $conn_id, true ) ;
// upload the file
$upload = ftp_put( $conn_id, $ftpdestinationfile, $ftpsourcefile, FTP_ASCII );

// check upload status
if (!$upload) {
echo "$ftpservername: FTP upload has failed!<BR>\n";
return false;
} else {
echo "Uploaded " . $ftpsourcefile . " to " . $ftpservername . " as " . $ftpdestinationfile . "<BR>\n";
}

// close the FTP stream
ftp_close($conn_id);

return true;
}

if (FTP_ENABLED)
 ftp_file( "uploads.google.com", FTP_USERNAME, FTP_PASSWORD, $source_file, "", $destination_file);

//End FTP


//  End TIMER
//  ---------
$etimer = explode( ' ', microtime() );
$etimer = $etimer[1] + $etimer[0];
echo '<p style="margin:auto; text-align:center">';
printf( "Script timer: <b>%f</b> seconds.", ($etimer-$stimer) );
echo '</p>';
//  ---------

?>