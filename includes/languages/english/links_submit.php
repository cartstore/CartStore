<?php
/*
  $Id: links_submit.php,v 1.00 2003/10/03 Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

$linkInfo_query = tep_db_query("select * from " . TABLE_LINKS_EXCHANGE . " where language_id = '" . (int)$languages_id . "'");
$linkInfo = tep_db_fetch_array($linkInfo_query);

define('NAVBAR_TITLE_1', 'Links');
define('NAVBAR_TITLE_2', 'Submit A Link');

define('HEADING_TITLE', 'Link Information');

define('TEXT_MAIN', 'Please fill out the following form to submit your website.');
define('TEXT_MAIN_INTRO', '
<p class="main">Welcome to our Link Exchange program. If you would like to exchange links with us, please copy “Our Website Information” below and use it to insert a link on your website. Once our link has been inserted on your website, please fill in the information requested under “Your Website Information” shown below, including the section “Reciprocal Page Details”, where you will fill in the URL of your links page that lists the link to our website.  Submit the request and you should get a response within 48 hours.</p>

<p class="main"><b>Please note:</b> You must have placed our link on your website BEFORE submitting your link to our website. Our policy is to verify our links on a regular basis and we will remove your link if we determine our link is not on your website. 
</p>
');

define('TEXT_MAIN_ALLOW_EDITING', '
<p class="main"><b>If you would like to edit an existing link, enter your login information and then click edit.
</p>
');

define('TEXT_MAIN_RECIPROCAL', '
<p class="main"><b>Our Website Information</b>
 
<tr>
 <td><table class="infoBox" border="3" width="100%" cellpadding="0">
  <tr class="infoBoxContents">
   <td><table border="0" width="100%" cellspacing="2" cellpadding="2">
    <tr>
     <td class="main" width="15%" >Link Name:</td>
     <td class="main">' . $linkInfo['links_exchange_name'] . '</td>
    </tr> 
    <tr>
     <td class="main">Link Description:</td>
     <td class="main">' . $linkInfo['links_exchange_description'] . '</td>
    </tr> 
    <tr>
     <td class="main">Link URL:</td>
     <td class="main">' . $linkInfo['links_exchange_url'] . '</td>
    </tr>  
   </table></td>
  </tr> 
 </table></td>
</tr> 
  
<tr><td  class="main" height="40" valign="bottom"><b>Or copy the actual code below (right box).</b></td></tr>  
 
<tr>
 <td><table class="infoBox" border="2" width="100%" cellpadding="0">
  <tr class="infoBoxContents">
 
      <td width="50%"><table border="0" width="100%" style="border-collapse: collapse;">
       <tr>
           <td><table border="0" width="100%" style="border-collapse: collapse;">
            <tr>
             <td style="font-family: Verdana, Arial, sans-serif; font-size: 11px;"><b><a href="' . $linkInfo['links_exchange_url'] . '" target="_blank" title="' . $linkInfo['links_exchange_name'] . '">' . $linkInfo['links_exchange_name'] . '</a></b></td>
            </tr> 
            <tr><td height="10"></td><tr>
            <tr>
             <td style="font-family: Verdana, Arial, sans-serif; font-size: 11px;">' . $linkInfo['links_exchange_description'] . '</td>
            </tr> 
            <tr><td height="10"></td><tr>
            <tr>
             <td style="font-family: Verdana, Arial, sans-serif; font-size: 11px;"><a href="' . $linkInfo['links_exchange_url'] . '" target="_blank" title="' . $linkInfo['links_exchange_name'] . '">' . $linkInfo['links_exchange_url'] . '</a></td>
            </tr>  
           </table></td>
          </tr>  
      </table></td>
      
     <td width="50%"><table border="1" width="100%" style="border-collapse: collapse;">
      <tr>
       <td> 
        <textarea rows="10" name="S1" cols="45" wrap="virtual">
          <tr>
           <td><table border="0" width="100%" style="border-collapse: collapse;"  cellpadding="0">
            <tr>
             <td style="font-family: Verdana, Arial, sans-serif; font-size: 11px;"><b><a href="' . $linkInfo['links_exchange_url'] . '" target="_blank" title="' . $linkInfo['links_exchange_name'] . '">' . $linkInfo['links_exchange_name'] . '</a></b></td>
            </tr> 
            <tr><td height="10"></td><tr>
            <tr>
             <td style="font-family: Verdana, Arial, sans-serif; font-size: 11px;">' . $linkInfo['links_exchange_description'] . '</td>
            </tr> 
            <tr><td height="10"></td><tr>
            <tr>
             <td style="font-family: Verdana, Arial, sans-serif; font-size: 11px;"><a href="' . $linkInfo['links_exchange_url'] . '" target="_blank" title="' . $linkInfo['links_exchange_name'] . '">' . $linkInfo['links_exchange_url'] . '</a></td>
            </tr>  
           </table></td>
          </tr>  
        </textarea> 
       </td>
      </tr>  
     </table></td>
 
  </tr> 
 </table></td>
</tr>
</p> 
 
 
');

define('EMAIL_SUBJECT', 'Welcome to ' . STORE_NAME . ' link exchange.');
define('EMAIL_SUBJECT_EDIT', 'Your link at ' . STORE_NAME . ' has been edited.');
define('EMAIL_GREET_NONE', 'Dear %s' . "\n\n");
define('EMAIL_WELCOME', 'We welcome you to the <b>' . STORE_NAME . '</b> link exchange program.' . "\n\n");
define('EMAIL_TEXT', 'Your link has been successfully submitted at ' . STORE_NAME . '. It will reviewed and enabled as soon as the changes have been approved. You will receive an email about the status of your update. If you have not received it within the next 48 hours, please contact us before submitting your link again.' . "\n\n");
define('EMAIL_TEXT_EDIT', 'Your link has been edited and resubmitted at ' . STORE_NAME . '. It will be added to our listing as soon as we approve it. You will receive an email about the status of your submittal within that time frame. However, if you have not received it within the next 48 hours, please contact us before submitting your link again.' . "\n\n");
define('EMAIL_CONTACT', 'For help with our link exchange program, please email the store-owner: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>Note:</b> This email address was given to us during a link submittal. If this is incorrect, please send an email to ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
define('EMAIL_OWNER_SUBJECT', 'Link submittal at ' . STORE_NAME);
define('EMAIL_OWNER_TEXT', 'A new link was <b>submitted</b> at ' . STORE_NAME . ' by %s for %s.' . "\n\n" . 'The reciprocal link has been verified and is at %s.' . "\n\n" . 'It is not yet approved. Please verify this link and activate.' . "\n\n");
define('EMAIL_OWNER_TEXT_EDIT', 'A link has been <b>edited</b> on ' . STORE_NAME . ' by %s for %s.' . "\n\n" . '%s' . 'The reciprocal link has been verified and is at %s.' . "\n\n" . 'It is not yet approved. Please verify this link and activate.' . "\n\n");

define('TEXT_LINKS_HELP_LINK', '&nbsp;Help&nbsp;[?]');

define('HEADING_LINKS_HELP', 'Links Help');
define('TEXT_LINKS_HELP', '<b>Site Title:</b> A descriptive title for your website.<br><br><b>URL:</b> The absolute web address of your website, including the \'http://\'.<br><br><b>Category:</b> Most appropriate category under which your website falls.<br><br><b>Suggest a Category:</b> Offer a suggestion for a link category if you cannot find one that suits your site.<br><br><b>Description:</b> A brief description of your website.<br><br><b>Image URL:</b> The absolute URL of the image you wish to submit, including the \'http://\'. This image will be displayed along with your website link.<br>Eg: http://your-domain.com/path/to/your/image.gif <br><br><b>Full Name:</b> Your full name.<br><br><b>Email:</b> Your email address. Please enter a valid email, as you will be notified via email.<br><br><b>Reciprocal Page:</b> The absolute URL of your links page, where a link to our website will be listed/displayed.<br>Eg: http://your-domain.com/path/to/your/links_page.php');
define('TEXT_CLOSE_WINDOW', '<u>Close Window</u> [x]');

// VJ todo - move to common language file
define('CATEGORY_CUSTOMER_EDIT', 'Click here to edit your listing.');
define('CATEGORY_WEBSITE', 'Your Website Information');
define('CATEGORY_RECIPROCAL', 'Reciprocal Page Details');
define('CATEGORY_LOGIN_INFORMATION', 'Login Detail - (only required if you wish to edit your link at a later date)');

define('ENTRY_LINKS_TITLE', 'Site Title:');
define('ENTRY_LINKS_TITLE_ERROR', 'Link title must contain a minimum of ' . ENTRY_LINKS_TITLE_MIN_LENGTH . ' characters.');
define('ENTRY_LINKS_TITLE_TEXT', '*');
define('ENTRY_LINKS_URL', 'URL:');
define('ENTRY_LINKS_URL_ERROR', 'URL must contain a minimum of ' . ENTRY_LINKS_URL_MIN_LENGTH . ' characters.');
define('ENTRY_LINKS_URL_TEXT', '*');
define('ENTRY_LINKS_CATEGORY', 'Category:');
define('ENTRY_LINKS_CATEGORY_TEXT', '');
define('ENTRY_LINKS_DESCRIPTION', 'Description:');
define('ENTRY_LINKS_DESCRIPTION_ERROR', 'Description must contain a minimum of ' . ENTRY_LINKS_DESCRIPTION_MIN_LENGTH . ' characters.');
define('ENTRY_LINKS_MAX_DESCRIPTION_ERROR', 'Description must contain less than ' . ENTRY_LINKS_DESCRIPTION_MAX_LENGTH . ' characters.');
define('ENTRY_LINKS_DESCRIPTION_TEXT', '*');
define('ENTRY_LINKS_IMAGE', 'Image URL:');
define('ENTRY_LINKS_IMAGE_TEXT', '');
define('ENTRY_LINKS_BLACKLISTED', 'Your website information contains a word that is not allowed.');
define('ENTRY_LINKS_CONTACT_NAME', 'Full Name:');
define('ENTRY_LINKS_CONTACT_NAME_ERROR', 'Your Full Name must contain a minimum of ' . ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH . ' characters.');
define('ENTRY_LINKS_CONTACT_NAME_TEXT', '*');
define('ENTRY_LINKS_RECIPROCAL_URL', 'Reciprocal Page:');
define('ENTRY_LINKS_RECIPROCAL_URL_ERROR', 'Reciprocal page must contain a minimum of ' . ENTRY_LINKS_URL_MIN_LENGTH . ' characters.');
define('ENTRY_LINKS_RECIPROCAL_URL_MISSING_ERROR', 'Our link cannot be found on %s.');
define('ENTRY_LINKS_RECIPROCAL_URL_TEXT', '*');
define('ENTRY_LINKS_DUPLICATE_ERROR', 'This link is already on file.');
define('ENTRY_LINKS_SUGGESTION', 'Suggest a Category:');
define('ENTRY_LINKS_USERNAME', 'Username: ');
define('ENTRY_LINKS_PASSWORD', 'Password: ');

define('ERROR_INVALID_LOGIN', 'Please enter a valid username and password.');

define('IMAGE_BUTTON_EDIT' , 'Edit');

?>
