<?php



require('includes/application_top.php');

set_time_limit(0);



	$Esc="\t";

	$CR="\n";



/*======================================== E.X.P.O.R.T.E.R START==============================================*/

if ($status){



$col1="Manufacturer Name";

$col2="Manufacturer Image";

$col3="Main Category";

$col4="Sub Categories";

$col5="Sub Sub Categories";

$col6="Product Model";

$col7="Product Image";

$col8="Product Price";

$col9="Product Weight";

$col10="Product Name";

$col11="Product Description";



$head=$col1."	".$col2."	".$col3."	".$col4."	".$col5."	".$col6."	".$col7."	".$col8."	".$col9."	".$col10."	".$col11."\n";



$ProdSql="select manufacturers_name, manufacturers_image, products_model, substring(products_image,locate('/',products_image)+1, length(products_image)) products_image, products_price, products_weight, products_name, products_description ";

$ProdSql.="from products a, products_description b, products_to_categories c , manufacturers d where c.products_id=a.products_id";

$ProdSql.=" and a.products_id=b.products_id and d.manufacturers_id=a.manufacturers_id and c.categories_id=";





	$CatList="";



	$CatList=$head;





	$res1=tep_db_query("select a.categories_id categories_id, categories_name from categories a, categories_description b where a.categories_id=b.categories_id and parent_id=0");



	while ($cat1=tep_db_fetch_array($res1)){



			$res2=tep_db_query("select a.categories_id categories_id, categories_name from categories a, categories_description b where a.categories_id=b.categories_id and parent_id=".$cat1["categories_id"]);



			if (tep_db_num_rows($res2)>0){

			while ($cat2=tep_db_fetch_array($res2)){







				$res3=tep_db_query("select a.categories_id categories_id, categories_name from categories a, categories_description b where a.categories_id=b.categories_id and parent_id=".$cat2["categories_id"]);

				if (tep_db_num_rows($res3)>0){

					while ($cat3=tep_db_fetch_array($res3)){





						$Pres3=tep_db_query($ProdSql.$cat3['categories_id']);

							if (tep_db_num_rows($Pres3)){

								while ($prod3=tep_db_fetch_array($Pres3)){



										$manufacturer=$prod3['manufacturers_name'];

										$CatList.=$prod3['manufacturers_name'].$Esc.$prod3['manufacturers_image'].$Esc.$cat1['categories_name'].$Esc.$cat2['categories_name'].$Esc;

										$CatList.=$cat3['categories_name'].$Esc.$prod3['products_model'].$Esc.$prod3['products_image'].$Esc;

										$CatList.=$prod3['products_price'].$Esc.$prod3['products_weight'].$Esc.$prod3['products_name'].$Esc;

										$CatList.=$prod3['products_description'].$CR;

								}

							}else{

							$CatList.=$cat1['categories_name'].$Esc. $cat2['categories_name'].$Esc.$cat3['categories_name'].$CR;

							}



					}

				}else{



						$Pres2=tep_db_query($ProdSql.$cat2['categories_id']);

							if (tep_db_num_rows($Pres2)){

								while ($prod2=tep_db_fetch_array($Pres2)){



										$manufacturer=$prod2['manufacturers_name'];

										$CatList.=$prod2['manufacturers_name'].$Esc.$prod1['manufacturers_image'].$Esc.$cat1['categories_name'].$Esc.$cat2['categories_name'].$Esc;

										$CatList.=$Esc.$prod2['products_model'].$Esc.$prod2['products_image'].$Esc;

										$CatList.=$prod2['products_price'].$Esc.$prod2['products_weight'].$Esc.$prod2['products_name'].$Esc;

										$CatList.=$prod2['products_description'].$CR;





								}

							}else{

							$CatList.=$cat1['categories_name'].$Esc. $cat2['categories_name'].$CR;

							}

				}



			}

			}else{



						$Pres1=tep_db_query($ProdSql.$cat1['categories_id']);

							if (tep_db_num_rows($Pres1)){

								while ($prod1=tep_db_fetch_array($Pres1)){

										$manufacturer=$prod1['manufacturers_name'];

										$CatList.=$prod1['manufacturers_name'].$Esc.$prod1['manufacturers_image'].$Esc.$cat1['categories_name'].$Esc.$Esc.$Esc;

										$CatList.=$prod1['products_model'].$Esc.$prod1['products_image'].$Esc;

										$CatList.=$prod1['products_price'].$Esc.$prod1['products_weight'].$Esc.$prod1['products_name'].$Esc;

										$CatList.=$prod1['products_description'].$CR;

								}

							}else{

						}

			}

	}

	/* IF YOU WANT TO COPY THE EXPORTED DATA INTO SOME FILE,

	   UNCOMMENT THE FOLLOWING CODE AND MAKE CHANGES ACCORDING TO YOUR REQUIREMENTS*/





	/*EXPORT DATA INTO A TAB DELIMETED TEXT FILE*/

		/*$filename="full_data_export.txt";

		$handle=fopen("full_data_export.txt",'w');

		fwrite($handle,$CatList );

		fclose($handle);

		header("location:".$filename);

		*/



}

/*======================================== E.X.P.O.R.T.E.R END==============================================*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo TITLE; ?></title>

<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />





<script language="JavaScript" type="text/JavaScript">

<!--

function MM_reloadPage(init) {  //reloads the window if Nav4 resized

  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {

    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}

  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();

}

MM_reloadPage(true);

//-->

</script>

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>



<table border="0" width="100%" cellspacing="2" cellpadding="2">

  <tr>

    <td width="<?php echo BOX_WIDTH; ?>" valign="top" height="27">

    <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">

    <?php require(DIR_WS_INCLUDES . 'column_left.php');?>

    </table></td>

    <td class="pageHeading" valign="top"><?php

       echo "Manufacturers, Categories and Products import/export";

 ?>



      <p class="smallText">

      <?php



function set_date($datepart){ //4/23/2001 9:26



//EXPLODE ON THE BASES OF SPACE FIRST

	if (strstr($datepart,' ' )>0)

	{

		$exp1=explode(' ',$datepart);



	$exp2=explode("/",$exp1[0]);



	return $exp2[2]."-". $exp2[0]."-". $exp2[1]." ". $exp1[1];

	}

	else{

	$exp2=explode("/",$datepart);

	return $exp2[2]."-". $exp2[0]."-". $exp2[1];

	}

}



function walk( $item1 ) {





   $item1 = str_replace('	','|',$item1);

 //  $item1 = str_replace(';"',';',$item1);

   $item1 = str_replace('"','',$item1);

//   $item1 = str_replace(',','.',$item1);



	$item1 = str_replace("\n",'',$item1);

	$item1 = str_replace("\r",'',$item1);

	$item1 = str_replace("•",'',$item1); //DON'T  KNOW WHAT THIS CHARACTER IS BUT FOUND IN DATA

	$item1 = str_replace("£",'',$item1);

	//$item1 = str_replace("™",'',$item1);

	$item1 = str_replace('"','\"',$item1);

    $item1 = str_replace("'",'\"',$item1);



   //echo $item1."<br>";

   $item1 = chop($item1);

	//echo $item1."<br>";

   $items = explode("|", $item1);

 	//  echo $items[0];





 /*$res=tep_db_query("select manufacturers_id id from manufacturers where manufacturers_name='".$_POST['man_id'] ."'");

 $manid=tep_db_fetch_array($res);

 */

 //echo strlen($items[1])."<br>";







   $default_product_quantity=100;



   $v_categories_date_added     = date("Y-m-d"); 		//$items[0];//CATEGORIES.categories_date_added

   //$v_categories_id        		= $items[0];		//CATEGORIES_DESCRIPTION.category_id

   $v_language_id         		= 1;        			//$items[2];//CATEGORIES_DESCRIPTION.language_id  //DEFAULT =1



   // $v_categores_description	= $items[2];			//CATEGORIES_DESCRIPTION.categories_name

   $v_manufacturers_name			= $items[0];

   $v_manufacturers_image			= $items[1];

   $v_categores1_name       		= $items[2];			//CATEGORIES_DESCRIPTION.categories_name

   $v_categores2_name 				= $items[3];

   $v_categores3_name 				= $items[4];



   $v_products_quantity			= 0;//$items[4];		//PRODUCTS.products_quantity

   $v_products_model       		= $items[5];			//PRODUCTS.products_model



   $v_products_image			= "";

   $v_products_subimage1        = "";

   $v_products_subimage2        = "";

   $v_products_subimage3        = "";

   $v_products_subimage4        = "";

   $v_products_subimage5        = "";

   $v_products_subimage6        = "";

   if(isset($items[6])&& $items[6] != ""){

       $v_products_image			= "store/".$items[0]."/".$items[6];//store/Manufacturere_name/products_image

   }

   if(isset($items[7]) && $items[7] != ""){

      $v_products_subimage1        = "store/".$items[0]."/".$items[7];//store/Manufacturere_name/products_image

   }

   if(isset($items[8]) && $items[8] != ""){

      $v_products_subimage2        = "store/".$items[0]."/".$items[8];//store/Manufacturere_name/products_image

   }

   if(isset($items[9]) && $items[9] != ""){

      $v_products_subimage3        = "store/".$items[0]."/".$items[9];//store/Manufacturere_name/products_image

   }

   if(isset($items[10]) && $items[10] != ""){

      $v_products_subimage4        = "store/".$items[0]."/".$items[10];//store/Manufacturere_name/products_image

   }

   if(isset($items[11]) && $items[11] != ""){

      $v_products_subimage5        = "store/".$items[0]."/".$items[11];//store/Manufacturere_name/products_image

   }

   if(isset($items[12]) && $items[12] != ""){

      $v_products_subimage6        = "store/".$items[0]."/".$items[12];//store/Manufacturere_name/products_image

   }

   $v_products_price        	= $items[13];			//PRODUCTS.products_price

   $v_products_date_added       = date("Y-m-d");		//set_date($items[5]);	//PRODUCTS.products_date_added

   $v_products_last_modified    = date("Y-m-d");		//set_date($items[6]);	//PRODUCTS.products_last_modified

   $v_products_date_available	= date("Y-m-d");		//set_date($items[7]);	//PRODUCTS.products_date_available

   $v_products_weight         	= $items[14];			//PRODUCTS.products_weight

   $v_products_tax_class_id     = 0;//$items[12];		//PRODUCTS.producst_tax_class_id

   $v_manufacturers_id     		= $manid['id'] ;		//$items[13];//PRODUCTS.manufacturers_id  [USE THE DEFAULT ONE]

   $v_products_name     		= $items[15];			//PRODUCTS_DESCRIPTION.products_name

   $v_products_status			= 1;//$items[15];		//PRODUCTS_DESCRIPTION.products_status

   $v_products_description		= $items[16];			//PRODUCTS_DESCRIPTION.products_status



   if (strlen($items[0])<=1 && strlen($items[2])<=1){}

 else{





   if ($v_tax_class_id == '') {$v_tax_class_id = 0;}

   if ($v_categores1_name==''){$v_categores1_name='Misc';};

   //if ($v_categores2_name==''){$v_categores2_name='Misc';};

   //if ($v_categores3_name==''){$v_categores3_name=$v_products_name;};

   if ($v_products_quantity == '') {$v_products_quantity = $default_product_quantity;}

   if ($v_products_quantity == ' ') {$v_products_quantity = $default_product_quantity;}

   $catname.=$v_categores1_name." " . $v_categores2_name." ".$v_categores3_name."<br>";

   //	echo $v_categores1_name."<-->".$v_categores2_name."<-->".$v_categores3_name."\n";

   //CHECK FOR MANUFACTURER

   $man_res=tep_db_query("select * from manufacturers where manufacturers_name='".$v_manufacturers_name."'");

   	if (tep_db_num_rows($man_res)>0)

   	{

   		$manufacturer_id=tep_db_fetch_array($man_res);

   	$v_manufacturers_id=$manufacturer_id['manufacturers_id'];

   	}else{

   	tep_db_query("insert into manufacturers(manufacturers_name,manufacturers_image,date_added) values('".$v_manufacturers_name."','".$v_manufacturers_image."','".date(Y-m-d)."')");

   	$man_id=tep_db_query ("select max(manufacturers_id) manufacturers_id from manufacturers");

   	$manid=tep_db_fetch_array($man_id);

   	$v_manufacturers_id=$manid['manufacturers_id'];

   	}





	/*=== CATEGORY LEVEL 1 START===*/

	/*BEFORE INSERTING THE CATEGORY.. FIRST FIND OUT EITHER THE CATEGORY IS ALREADY IN DB OR NOT*/

	$cat_count=tep_db_query("select a.* from categories_description a, categories b where categories_name='".$v_categores1_name."' and b.parent_id=0 and a.categories_id=b.categories_id");



	//echo "select * from categories_description where categories_name='".$v_categores_name."'";

	 if (tep_db_num_rows($cat_count) != 0)

	 {

	 	$catid=tep_db_fetch_array($cat_count);

 	$Category_id=$catid['categories_id'];



	 }else{



	$cat_sql="insert into categories(date_added, parent_id) values ('". $v_categories_date_added."',0)";

	tep_db_query($cat_sql);



	$catid_sql=tep_db_query("select max(categories_id) categories_id from categories where date_added='".$v_categories_date_added."'");

	$catid =  tep_db_fetch_array($catid_sql);



	$cat_desc_sql="insert into categories_description (categories_id,language_id,categories_name)values(".$catid['categories_id'].",".$v_language_id.",'".$v_categores1_name."')";



	tep_db_query($cat_desc_sql);

	$Category_id=$catid['categories_id'];

	//$Progress.="Category Level1 inserted...\n";

	 }

	 $Parent1=$Category_id;

	/*=== CATEGORY LEVEL 1 END===*/



	/*=== CATEGORY LEVEL 2 START===*/

	/*BEFORE INSERTING THE CATEGORY.. FIRST FIND OUT EITHER THE CATEGORY IS ALREADY IN DB OR NOT*/



	if (strlen($v_categores2_name)>0){

			//

			$cat_count=tep_db_query("select b.* from categories_description b, categories a where categories_name='".$v_categores2_name."' and a.parent_id=".$Parent1." and a.categories_id=b.categories_id");



			//echo "select b.* from categories_description b, categories a where categories_name='".$v_categores2_name."' and a.parent_id > 0 and a.categories_id=b.categories_id<br>";

			 if (tep_db_num_rows($cat_count) >=1)

			 {

			 	$catid=tep_db_fetch_array($cat_count);

		 	$Category_id=$catid['categories_id'];



			 }else{

			//BEFORE INSERTING GET THE PARENT_ID

			$parent_sql="select a.categories_id from categories_description a , categories b where categories_name='".$v_categores1_name."' and b.parent_id =0 and a.categories_id=b.categories_id";

			//echo $parent_sql."<br>";





			$parent_res=tep_db_query($parent_sql);

			if (tep_db_num_rows($parent_res)<1){

			$cat_sql="insert into categories(date_added, parent_id) values ('". $v_categories_date_added."',1)";

			}else{;

			$parent_id=tep_db_fetch_array($parent_res);

			$cat_sql="insert into categories(date_added, parent_id) values ('". $v_categories_date_added."',".$parent_id['categories_id'].")";

			}



			//echo $cat_sql."-2-<br>";

			tep_db_query($cat_sql);



			$catid_sql=tep_db_query("select max(categories_id) categories_id from categories where date_added='".$v_categories_date_added."'");

			$catid =  tep_db_fetch_array($catid_sql);



			$cat_desc_sql="insert into categories_description (categories_id,language_id,categories_name)values(".$catid['categories_id'].",".$v_language_id.",'".$v_categores2_name."')";

			tep_db_query($cat_desc_sql);

			$Category_id=$catid['categories_id'];

			//$Progress.="Category Level2 inserted...\n";

			 }

			 $Parent2=$Category_id;

	}

	/*=== CATEGORY LEVEL 2 END===*/



		/*=== CATEGORY LEVEL 3 START===*/

	/*BEFORE INSERTING THE CATEGORY.. FIRST FIND OUT EITHER THE CATEGORY IS ALREADY IN DB OR NOT*/

if (strlen($v_categores3_name)>0){

			$cat_count=tep_db_query("select b.* from categories_description b, categories a where categories_name='".$v_categores3_name."' and a.parent_id=".$Parent2." and a.categories_id=b.categories_id");

			//echo "select b.* from categories_description b, categories a where categories_name='".$v_categores3_name."' and a.parent_id>0 and a.categories_id=b.categories_id<br>";



			if (tep_db_num_rows($cat_count) >= 1)

			 {

			 	$catid=tep_db_fetch_array($cat_count);

		 		$Category_id=$catid['categories_id'];



			 }else{

			//BEFORE INSERTING GET THE PARENT_ID

			$parent_sql="select a.categories_id from categories_description a, categories b where categories_name='".$v_categores2_name."' and b.parent_id=".$Parent1." and a.categories_id=b.categories_id";

			//echo $parent_sql."<br>";

			$parent_res=tep_db_query($parent_sql);

			$parent_id=tep_db_fetch_array($parent_res);





			$cat_sql="insert into categories(date_added, parent_id) values ('". $v_categories_date_added."',".$parent_id['categories_id'].")";



			tep_db_query($cat_sql);



			$catid_sql=tep_db_query("select max(categories_id) categories_id from categories where date_added='".$v_categories_date_added."'");

			$catid =  tep_db_fetch_array($catid_sql);



			$cat_desc_sql="insert into categories_description (categories_id,language_id,categories_name)values(".$catid['categories_id'].",".$v_language_id.",'".$v_categores3_name."')";

			tep_db_query($cat_desc_sql);

			$Category_id=$catid['categories_id'];

			//$Progress.="Category Level3 inserted...\n";



			 }

	}

	/*=== CATEGORY LEVEL 3 END===*/



	//echo $Progress;



	$prod_sql="insert into products (products_quantity,products_model, products_image, ";

    $prod_sql.="products_subimage1, products_subimage2, products_subimage3, products_subimage4, products_subimage5, products_subimage6, ";

    $prod_sql.="products_price,products_date_added, ";

	$prod_sql.="products_last_modified,products_date_available,products_weight,products_status,products_tax_class_id,manufacturers_id)";

	$prod_sql.="values(".$v_products_quantity.",'".$v_products_model."','".$v_products_image."','";

	$prod_sql.=$v_products_subimage1."','".$v_products_subimage2."','".$v_products_subimage3."','".$v_products_subimage4."','";

	$prod_sql.=$v_products_subimage5."','".$v_products_subimage6."','";

    $prod_sql.=$v_products_price."','";

	$prod_sql.=$v_products_date_added."','".$v_products_last_modified."','".$v_products_date_available."','". $v_products_weight."','";

	$prod_sql.=$v_products_status."',".$v_products_tax_class_id.",".$v_manufacturers_id.")";



	tep_db_query($prod_sql);



	$products_id_sql="select max(products_id) products_id from products";

	$res3=tep_db_query($products_id_sql);

	$products_id=tep_db_fetch_array($res3);



	$product_to_category_sql="insert into products_to_categories values(".$products_id['products_id'].",".$Category_id.")";

	tep_db_query($product_to_category_sql);



	$prod_desc_sql="insert into products_description (products_id,language_id,products_name,products_description) values(";

	$prod_desc_sql.=$products_id['products_id'].",".$v_language_id.",'".$v_products_name."','".$v_products_description."')";



	tep_db_query($prod_desc_sql);



	//echo $catname;

	//$Progress.=$v_categores_name."->".$v_products_name."\n";

 }

}



?>



</p>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">

        <tr>

          <td width="48%" height="91"><table width="100%" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td bgcolor="#003399"> <font color="#FFFFFF" size="5" face="Arial, Helvetica, sans-serif"><strong>Import

                    <input name="status2" type="hidden" id="status2">

              </strong></font></td></tr>

            <tr>

              <td bgcolor="#003399"><div align="center">                </div>

              </td>

            </tr>

            <tr>

              <td bgcolor="#003399"><div align="center">                </div>

              </td>

            </tr>

            <tr>

              <td height="314" valign="top" >

                <table width="100%" border="0" bgcolor="#eeeeee">

                <tr>

                  <td width="75%" height="99" valign="top">

                    <FORM ENCTYPE="multipart/form-data" ACTION="excel.php" METHOD=POST>





                        <p>

                          <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="2000000">

</p>

                        <table width="100%" border="0" cellspacing="0" cellpadding="0">

                          <tr>

                            <td bgcolor="#336666"><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><strong>Rules

                                  to import data</strong></font></td>

                          </tr>

                          <tr>

                            <td valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="0">

                                <tr>

                                  <td width="5%" valign="top"><font color="#000000" size="2">1.</font></td>

                                  <td width="95%"><font color="#000000" size="2">The

                                      data must be tab delimeted</font></td>

                                </tr>

                                <tr>

                                  <td height="16" valign="top"><font color="#000000" size="2">2.</font></td>

                                  <td><font color="#000000" size="2">Remove all currency symbols ($ or GBP)

                                    from price data</font></td>

                                </tr>

                                <tr>

                                  <td valign="top"><font color="#000000" size="2">3.</font></td>

                                  <td><font color="#000000" size="2">Remove all non-standard characters and

                                    bullet points from data before import</font></td>

                                </tr>

                                <tr>

                                  <td valign="top"><font color="#000000" size="2">4.</font></td>

                                  <td><font color="#000000" size="2">Do not use

                                      bullet points in the data</font></td>

                                </tr>

                                <tr>

                                  <td valign="top"><font color="#000000" size="2">5.</font></td>

                                  <td><font color="#000000" size="2">images must

                                      be uploaded saperately in the 'images/store/manufacturer_name'

                                      folder</font></td>

                                </tr>

                                <tr>

                                  <td valign="top"><font color="#000000" size="2">6.</font></td>

                                  <td><font color="#000000" size="2">Leave the

                                      first line as the heading</font></td>

                                </tr>

                                <tr>

                                  <td valign="top"><font color="#000000" size="2">7.</font></td>

                                  <td><font color="#000000" size="2">Quantity

                                      is assumed to be given or else 100 will

                                      be used as default..</font></td>

                                </tr>

                                <tr>

                                  <td valign="top"><font color="#000000" size="2">8.</font></td>

                                  <td><font color="#000000" size="2">Keep the

                                      sequence of the columns as it is given

                                      below. If any of the column doesnot contain

                                      any <br>

            data. Leave it blank but donot remove it from the list</font></td>

                                </tr>

                                <tr>

                                  <td valign="top"><font color="#000000" size="2">9.</font></td>

                                  <td><font color="#000000" size="2">The importer

                                      tries to find the parent of any category,

                                      but if not found it puts in the 'Misc'

                                      General category. if you are looking for

                                      some products and found no where please

                                      try it in 'Misc' Category</font></td>

                                </tr>

                                <tr>

                                  <td valign="top"><font color="#000000" size="2">&nbsp;</font></td>

                                  <td><font color="#000000" size="2">&nbsp;</font></td>

                                </tr>

                                <tr>

                                  <td valign="top"><font color="#000000" size="2">&nbsp;</font></td>

                                  <td><font color="#000000" size="2">&nbsp;</font></td>

                                </tr>

                              </table>

                            </td>

                          </tr>

                        </table>

                        <br>

                        <strong><font color="#000000">Column Sequence to import data</font></strong><br>

                        <font color="#000000" size="1">Manufacturer_name | manufacturers_image

                        | Categories1_name | Categories2_name | Categories3_name

                        | Model_no | Web_pic | subimage1 | subimage2 | subimage3 | subimage4 | subimage5 | subimage6 |

                        | list_price | weight | products_name | products_description</font><br>

                        <br>

                        <input name="usrfl" type="file">

                        <p></p>

                        <p>

                          <input type="image" border="0" name="imageField" src="includes/languages/english/images/buttons/button_upload.png" width="65" height="22">

                        </p>

                      </div>

                      <?php

if (is_uploaded_file($usrfl))

{

echo "<p class=smallText>";

echo "File uploaded. <br>";

echo "Temporary filename: " . $usrfl . "<br>";

echo "User filename: " . $usrfl_name . "<br>";

echo "Size: " . $usrfl_size . "<br>";



$readed = file($usrfl);

unset( $readed[0] );

foreach ($readed as $arr)

{

walk($arr);

$Counter++;

}

echo "Total Records inserted......".$Counter."<br>";

//array_walk($readed, 'walk');

} else {



	if ($_GET['param'] == "eng") {

    write_tree(0,"",1);



  };

  $rootval = 0;



  //update_tree(0,1);

 // update_tree(0,4);

 /* echo "You can import file from Excel to MySQL. Save the Excel file as CSV (Comma Delimited - option when you save an excel file) and then upload.

  The file has to be CSV format.<br>";

  echo "Version 1.51 <br>";

  echo "<br>";*/

  //echo 'View category code and root code: <a href="excel.php?param=ukr">Ukrainian</a> or <a href="excel.php?param=eng">English</a> ';

};

?>





                    </form>

                  </td>

                </tr>

              </table>

</td>

            </tr>

          </table></td>

          <td width="52%" rowspan="2" valign="top"><form name="form1" method="get" action="excel.php">

		  <script language="JavaScript">

		  function setstatus(status){

		  form1.status.value=status;

		  form1.submit();

		  }

		  </script>

          <table width="100%" border="0" cellspacing="0" cellpadding="0">



            <tr>

              <td bgcolor="#003399"> <font color="#FFFFFF" size="5" face="Arial, Helvetica, sans-serif"><strong>Export

                    <input name="status" type="hidden" id="status">

              </strong></font></td>

            </tr>

            <tr>

              <td>

                  <div align="left">

                      <input name="Button" type="button" id="Button2" value="Export Data " onClick="setstatus(2)">&nbsp;

                </div>

              </td>

            </tr>



            <tr>

            <td bgcolor="#eeeeee">

			<?if ($status==2){?>

            <textarea cols="80" rows="20"><?php echo $CatList; ?></textarea>

              <?php

			}else{?>

			<a href="<?php echo $filename; ?>"><?php echo $filename; ?></a>

			<?php

			}

              ?>

            </td></tr>

          </table>

          </form></td>

        </tr>

        <tr>

          <td>&nbsp;</td>

        </tr>

    </table></td>

 </tr>

</table>



<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>



<p> </p>

<p> </p><p><br>

</p></body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>

