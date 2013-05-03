<?php
/*
  $Id: googlesitemap.php admin page,v 2.0 2/03/2006 developer@eurobigstore.com
  GNU General Public License Compatible
*/

  require('includes/application_top.php');
   
  	function GenerateSubmitURL(){
		$url = urlencode(HTTP_SERVER . DIR_WS_CATALOG . 'sitemapindex.xml');
		return htmlspecialchars(utf8_encode('http://www.google.com/webmasters/sitemaps/ping?sitemap=' . $url));
	} # end function

// controllo delle lingue	
        $controllo = $languages_id;
		$query = "SELECT 
							languages_id,
							code
					FROM
							" . TABLE_LANGUAGES . "
					WHERE
							languages_id = $controllo";
    	
		$result = mysql_query($query);
    	
		while ($row = mysql_fetch_array($result))
				{ 
					$codice = $row[code]; 
							    };
	
	$file = 'sitemaps.index.php?language=';
	$url = $file . $codice;

// Fine	
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo TITLE_GOOGLE_SITEMAPS; ?></td>
            <td class="pageHeading" align="right"><img src="images/google-sitemaps.gif" width="110" height="48"></td>
          </tr>
        </table>
          <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="main">
            <tr>
              <td width="78%" align="left" valign="top"><p><strong><?php echo OVERVIEW_TITLE_GOOGLE_SITEMAPS; ?></strong></p>
                <p><?php echo OVERVIEW_GOOGLE_SITEMAPS; ?></p>
                <p><strong><?php echo INSTRUCTIONS_TITLE_GOOGLE_SITEMAPS; ?></strong></p>
                <p><strong><font color="#FF0000"><?php echo INSTRUCTIONS_STEP1_GOOGLE_SITEMAPS; ?></font></strong><?php echo INSTRUCTIONS_CLICK_GOOGLE_SITEMAPS; ?><a href="javascript:(void 0)" class="splitPageLink" onClick="window.open('<?php echo $HTTP_SERVER . DIR_WS_CATALOG . $url;?>','google','resizable=0,statusbar=5,width=960,height=310,top=0,left=50,scrollbars=yes')"><strong><?php echo EXEC_GOOGLE_SITEMAPS; ?></strong></a><?php echo INSTRUCTIONS_END1_GOOGLE_SITEMAPS; ?></p>
                <p><?php echo INSTRUCTIONS_NOTE_GOOGLE_SITEMAPS; ?></p>
                <p><strong><font color="#FF0000"><?php echo INSTRUCTIONS_STEP2_GOOGLE_SITEMAPS; ?></font></strong><?php echo INSTRUCTIONS_CLICK_GOOGLE_SITEMAPS; ?><a href="javascript:(void 0)"  onClick="window.open('<?php echo $returned_url = GenerateSubmitURL();?>','google','resizable=1,statusbar=5,width=400,height=200,top=0,left=50,scrollbars=yes')" class="splitPageLink"><strong><?php echo EXEC_GOOGLE_SITEMAPS; ?></strong></a><?php echo INSTRUCTIONS_END2_GOOGLE_SITEMAPS; ?></p>
                <p><?php echo INSTRUCTIONS_COMPLETE_GOOGLE_SITEMAPS; ?></p>
                <p>&nbsp;</p></td>
              <td width="22%" align="right" valign="top"><table width="98%"  border="0" cellpadding="1" cellspacing="0" bgcolor="#E1EEFF">
                <tr>
                  <td align="center" class="smallText"> <strong><?php echo WHATIS_TITLE_GOOGLE_SITEMAPS; ?></strong></td>
                </tr>
                <tr>
                  <td class="smallText"><table width="100%"  border="0" cellpadding="4" cellspacing="0" bgcolor="#F0F8FF">
                    <tr>
                      <td align="left" valign="top" class="smallText"><p><?php echo WHATIS_TEXT_GOOGLE_SITEMAPS; ?></p>
                        <p><?php echo WHATIS_REGISTER_GOOGLE_SITEMAPS; ?><strong><a href="https://www.google.com/webmasters/sitemaps/login" target="_blank" class="splitPageLink"><?php echo EXEC_GOOGLE_SITEMAPS; ?></a></strong>.</p>
                        </td>
                    </tr>
                  </table></td>
                </tr>
              </table>
                <p>&nbsp;</p></td>
            </tr>
          </table>
          </td>
      </tr>
      <tr>
        <td></td>
          </tr>       
<!-- body_text_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>