<?php

/*

  $Id: server_info.php,v 1.6 2003/06/30 13:13:49 dgw_ Exp $



  CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA



  GNU General Public License Compatible

  

  Setup International Shipping Zones by dave@advancedstyle.com - 8/1/2006

*/



  require('includes/application_top.php');



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

	

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo TITLE; ?></title>

<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />

   

	 	

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->



<!-- body //-->

<table border="0" width="100%" cellspacing="2" cellpadding="2">

  <tr>

    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">

<!-- left_navigation //-->

<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<!-- left_navigation_eof //-->

    </table></td>

<!-- body_text //-->

    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td class="pageHeading">Setup International "Rest of the World" Zone</td>

            <td class="pageHeading2" align="right"></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

          <tr>

            <td class="main">

			<?php if($_POST['doprocess'] == 1){

				// Create the Zone

       			 tep_db_query("insert into " . TABLE_GEO_ZONES . " (geo_zone_name, geo_zone_description, date_added) values ('" . tep_db_input($_POST['zone_name']) . "', '" . tep_db_input($_POST['zone_description']) . "', now())");

				 $last_insert = tep_db_insert_id();

				 // Insert the countries

					$query = mysql_query("SELECT * FROM countries WHERE countries_id <> '".$_POST['country']."'");

					while($rec = mysql_fetch_array($query)){

        				tep_db_query("insert into " . TABLE_ZONES_TO_GEO_ZONES . " (zone_country_id, zone_id, geo_zone_id, date_added) values ('" . (int)$rec['countries_id'] . "', '0', '" . (int)$last_insert . "', now())");

					}

				echo $_POST['zone_name'].' Zone was Successfully Created!';

			}else{

			?>

			This page will setup a "Tax Zone" that includes every country in the world except your home country.<br>

			This zone can be used for the shipping or payment modules, so that you can have a module which gets shown only to customers from countries other than your country (or "International" customers).<hr>

			<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>"><input type="hidden" name="doprocess" value="1">

              <table border="0" cellspacing="0" cellpadding="4">

                <tr>

                  <td class="main">Name of International Zone: </td>

                  <td><input type="text" name="zone_name" value="International"></td>

                </tr>

                <tr>

                  <td class="main">Description for International Zone: </td>

                  <td><input name="zone_description" type="text" value="Zone to Cover the Rest of the World" size="40"></td>

                </tr>

                <tr>

                  <td class="main">Your country:</td>

                  <td><select name="country">

                      <?php

					$query = mysql_query("SELECT * FROM countries");

					while($rec = mysql_fetch_array($query)){

					$selected = '';

					if($rec['countries_id'] == STORE_COUNTRY){

						$selected = ' SELECTED';

					}

						echo '<option value="'.$rec['countries_id'].'"'.$selected.'>'.$rec['countries_name'].'</option>'."\n";

					}

				?>

                  </select></td>

                </tr>

                <tr>

                  <td><input name="submit" type="submit" value="Create Zone"></td>

                  <td>&nbsp;</td>

                </tr>

              </table>

                        </form>

						

			<?php } ?>

            </td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>

      </tr>

    </table></td>

<!-- body_text_eof //-->

  </tr>

</table>

<!-- body_eof //-->



<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

<br>

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

