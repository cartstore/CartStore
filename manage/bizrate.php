<?php
//**************
//CONFIGURATION*
//    START    *
//**************
include('includes/application_top.php');
//Create a directory within your CATALOG directory and make sure it have full read, write, and execute access.
//Update directory name below and your BizRate filename, which you chose when you "Manage Inventory"
//"CHANGEME-full-path-to-file-with-777-dir-and-file-permissions.fr-outfile.txt"

$OutFile = DIR_FS_DOCUMENT_ROOT."feeds/".BIZRATE_FILENAME; 

//Update your BizRate filename, which you chose when you "Manage Inventory"
//"CHANGEME-filename-to-upload-to-bizrate.txt"
$destination_file = BIZRATE_FILENAME;

//DO NOT Chnage
$source_file = $OutFile;

//THIS IS VERY IMPORTANT
//Make sure to update the below with the URL path to your product images
//Make sure to double check this. Right click on a product image and select properties from your website to make sure this is the correct path
//When you generate the test file, copy and paste an image link into a web browser to make sure it is correct. 
//Example http://www.seekshopping.com/catalog/images/
//Example http://www.storegroup.org/images/
$imageURL = HTTP_SERVER.'/images/';

//When you generate the test file, copy and paste an image link into a web browser to make sure it is correct. 
//Example http://www.seekshopping.com/catalog/product_info.php?products_id=
//Example http://www.storegroup.org/product_info.php?products_id=
$productURL = HTTP_SERVER.'/product_info.php?products_id=';

//DO NOT Chnage
$already_sent = array();

//Input your DB information
//This information is located in your catalog/includes/configuration.php file if you don't know this info
$home=DB_SERVER;
$user=DB_SERVER_USERNAME;
$pass=DB_SERVER_PASSWORD;
$base=DB_DATABASE;

//Input BizRate FTP information
//Don't change this, just update the username and password below

$ftp_server = BIZRATE_FTP_SERVER;

//The username and password are created seperately from your account username and password in the "Manage Inventory" section - https://merchant.bizrate.com/pp/product_inventory/index.xpml
$ftp_user_name = BIZRATE_USER;


$ftp_user_pass = BIZRATE_PASS;


//In most cases you won't need to use this, however, update the link with your domain below just in case
$convertCur = false; //default = false
$curType = "USD"; // Converts Currency to any defined currency (eg. USD, EUR, GBP)
if($convertCur)
{
//Example: http://www.seekshopping.com/catalog/product_info.php?currency=CURTYPE&products_id=
//Example: http://www.storegroup.org/product_info.php?currency=CURTYPE&products_id=
$productURL = 'http://www.mttextstore.com/product_info.php?currency=USD&products_id=';  //where CURTYPE is your currency type (eg. USD, EUR, GBP)
}

//IMPORTANT: There is a portion of this code below that should be uncommented once you test the file creation process.
//Do the following before uncommenting the section below
//      Run this file
//      Go to the directory you created
//      View the file
//      Test the links
//
//If everything is successful, uncomment the portion of code below to actually send the file to BizRate.
//Login to BizRate to make sure the file is there.  Note: check filesize, it should be greater then 0 and you should not have gotten any errors when running this script
//
//If all is good, setup a CRON JOB so this script once a week to keep BizRate updated

//**************
//CONFIGURATION*
//     END     *
//**************


if (!($link=mysql_connect($home,$user,$pass)))
{
echo "Error when connecting itself to the data base";
exit();
}
if (!mysql_select_db( $base , $link ))
{
echo "Error the data base does not exist";
exit();
}

$sql = "
SELECT concat( '" . $productURL . "' ,products.products_id) AS product_url,
products_model , products_weight, products_quantity,
manufacturers.manufacturers_name AS manufacturer,
products.products_id AS id,
products_description.products_name AS name,
products_description.products_description AS description,
FORMAT(products.products_price,2) AS price,
CONCAT( '" . $imageURL . "' ,products.products_image) AS image_url,
products_to_categories.categories_id AS prodCatID,
categories.parent_id AS catParentID,
categories_description.categories_name AS catName
FROM (categories,
categories_description,
products,
products_description,
products_to_categories )

left join manufacturers on ( manufacturers.manufacturers_id = products.manufacturers_id )

WHERE products.products_id=products_description.products_id
AND products.products_id=products_to_categories.products_id
AND products_to_categories.categories_id=categories.categories_id
AND categories.categories_id=categories_description.categories_id
AND products.products_status != 0
AND products.products_price != 0
AND products.products_price != ''
ORDER BY
products.products_id ASC
";


$catInfo = "
SELECT
categories.categories_id AS curCatID,
categories.parent_id AS parentCatID,
categories_description.categories_name AS catName
FROM
categories,
categories_description
WHERE categories.categories_id = categories_description.categories_id
";

function findCat($curID, $catTempPar, $catTempDes, $catIndex)
{
	if($catTempPar[$curID] != 0 )
	{
		if($catIndex[$catTempPar[$curID]] != null)
		{
			$temp=$catIndex[$catTempPar[$curID]];
		}
		else
		{
			$catIndex = findCat($catTempPar[$curID], $catTempPar, $catTempDes, $catIndex);
			$temp = $catIndex[$catTempPar[$curID]];
		}
	}
	if($catTempPar[$curID] == 0)
	{
		$catIndex[$curID] = $catTempDes[$curID];
	}
	else
	{
		$catIndex[$curID] = $temp . " > " . $catTempDes[$curID];
	}
	return $catIndex;

}

$catIndex = array();
$catTempDes = array();
$catTempPar = array();
$processCat = mysql_query( $catInfo )or die( $FunctionName . ": SQL error " . mysql_error() . "| catInfo = " . htmlentities($catInfo) );
while ( $catRow = mysql_fetch_object( $processCat ) )
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

//Check for any applicable specials for the corresponding products_id
$sql2 = "
SELECT
specials.products_id AS idS,
FORMAT(specials.specials_new_products_price,2) AS priceS
FROM
specials,
products
WHERE
specials.products_id=products.products_id
AND specials.status != 0
AND products.products_status != 0
ORDER BY
specials.products_id ASC
";

$_strip_search = array(
"![\t ]+$|^[\t ]+!m", // remove leading/trailing space chars
'%[\r\n]+%m'); // remove CRs and newlines
$_strip_replace = array(
'',
'');

// The following OUTPUT MUST BE IN THIS ORDER according to BizRate specs
// MANDATORY information: Category, Title, Link, SKU, Price / The rest is optional, but we will populate most of the optional information
$output = "Category \t Mfr \t Title \t Description \t Link \t Image \t SKU \t # on Hand \t Condition \t Ship. Weight \t Ship. Cost \t Bid \t Promo Des. \t Other \t Price\n";
//$output = "product_url \t name \t description \t price \t image_url \t category\n";

$result=mysql_query( $sql )or die( $FunctionName . ": SQL error " . mysql_error() . "| sql = " . htmlentities($sql) );

//Specials Checker
$result2=mysql_query( $sql2 )or die( $FunctionName . ": SQL error " . mysql_error() . "| sql2 = " . htmlentities($sql2) );
$row2 = mysql_fetch_object( $result2 );

//Currency Information
if($convertCur)
{
	$sql3 = "
	SELECT
	currencies.value AS curUSD
	FROM
	currencies
	WHERE currencies.code = '$curType'
	";

	$result3=mysql_query( $sql3 )or die( $FunctionName . ": SQL error " . mysql_error() . "| sql3 = " . htmlentities($sql3) );
	$row3 = mysql_fetch_object( $result3 );
}

while( $row = mysql_fetch_object( $result ) )
{
	if ($already_sent[$row->id] == 1) continue; // if we've sent this one, skip the rest of the while loop

	// reset the products price to our special price if there is one for this product
	if( $row2->idS == $row->id ){
		$row->price = $row2->priceS;
		$previdS = $row2->idS;
		while ( $row2->idS == $previdS )
		{
		$row2 = mysql_fetch_object( $result2 );  //advance row in special's table
		}
	}

	if($convertCur)
	{
		$row->price = $row->price *  $row3->curUSD;
		$row->price = number_format($row->price, 2, '.', '');
	}
//"Category \t Mfr \t Title \t Description \t Link \t Image \t SKU \t # on Hand \t Condition \t Ship. Weight \t Ship. Cost \t Bid \t Promo Des. \t Other \t Price\n";
	$output .= 
	$catIndex[$row->prodCatID] . "\t" .
	$row->manufacturer . "\t" .
	preg_replace($_strip_search, $strip_replace, strip_tags( str_replace(">", "> ", $row->name) ) ) . "\t" .
	preg_replace($_strip_search, $strip_replace, strip_tags( str_replace(">", "> ", $row->description) ) ) . "\t" .
	$row->product_url . "\t" .
	$row->image_url . "\t" .
	$row->id . "\t" .
	$row->products_quantity . "\t" .
	"\t" . //condition - not going to populate, optional field, populate at your own risk
	$row->products_weight . "\t" .
	"\t" . //ship cost - not going to populate, optional field, populate at your own risk
	"\t" . //bid - not going to populate, optional field, populate at your own risk
	"\t" . //Promo Des. - not going to populate, optional field, populate at your own risk
	"\t" . //Other - not going to populate, optional field, populate at your own risk
	$row->price . "\n";

	$already_sent[$row->id] = 1;
}

if ( file_exists( $OutFile ) )
unlink( $OutFile );

$fp = fopen( $OutFile , "w" );
$fout = fwrite( $fp , $output );
fclose( $fp );

//START FTP TO BIZRATE - UNCOMMENT AFTER TESTING FILE CREATION
function ftp_file( $ftpservername, $ftpusername, $ftppassword, $ftpsourcefile, $ftpdirectory, $ftpdestinationfile )
{
// set up basic connection
$conn_id = ftp_connect($ftpservername);
if ( $conn_id == false )
{
echo "FTP open connection failed to $ftpservername <BR>\n";
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

ftp_pasv ( $conn_id, true );
// upload the file
$upload = ftp_put( $conn_id, $ftpdestinationfile, $ftpsourcefile, FTP_ASCII ); 

// check upload status
if (!$upload) { 
echo "$ftpservername: FTP upload has failed! Check BizRate Username & Password.<BR>\n";
return false; 
} else {
echo "Uploaded " . $ftpsourcefile . " to " . $ftpservername . " as " . $ftpdestinationfile . "<BR>\n";
}

// close the FTP stream 
ftp_close($conn_id); 

return true;
}

ftp_file( $ftp_server, $ftp_user_name, $ftp_user_pass, $source_file, $ftp_directory, $destination_file);
 //END FTP TO BIZRATE - UNCOMMENT AFTER TESTING FILE CREATION

//We hope this module works well for you and you make a lot of money!  Thanks, the StoreGroup.org

?>