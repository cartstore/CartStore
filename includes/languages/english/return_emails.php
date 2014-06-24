<?php
/*
$id author Puddled Internet - http://www.puddled.co.uk
  email support@puddled.co.uk
   osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

*/

/* This section covers the very first confirmation email sent to a customer,
to say that their RMA request has been received. */
define('EMAIL_SUBJECT_OPEN', 'Return request sent to ' . STORE_NAME);
define('EMAIL_TEXT_TICKET_OPEN', 'RMA number: <b><i>' . $rma_value . '</b></i>' . "\n\n");
define('EMAIL_THANKS_OPEN', 'Thank you for submitting your return request to <b>' . STORE_NAME . '</b>.' . "\n\n");
define('EMAIL_TEXT_OPEN', 'Your request has been sent to the relevant department for processing.' . "\n\n" . 'If you need to contact us regarding this matter, please quote the above RMA number so that we may keep track of all relevant correspondance.' . "\n\n");
define('EMAIL_CONTACT_OPEN', 'For help with any of our online services, please contact us at: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING_OPEN', '<b>Note:</b> This email address was given to us by someone using it to submit a support request. If you did not send this request, please send a message to ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");


/* This section covers the confirmation email sent to the assigned administrator after an RMA request has been edited by a customer, in order to inform the admin that the ticket has been edited. */

define('EMAIL_SUBJECT_ADMIN', 'Return request received');
define('EMAIL_TEXT_TICKET_ADMIN', 'RMA number -<b><i>' . $rma_value . '</b></i>' . "\n\n");
define('EMAIL_THANKS_ADMIN', 'This message is meant to inform you that the above return request has been updated by the customer' . "\n\n");
define('EMAIL_TEXT_ADMIN', 'Please log into the admin area to see the return information.' . "\n\n");
define('EMAIL_CONTACT_ADMIN', 'For help with any of our online services, please contact us at: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING_ADMIN', '<b>Note:</b> This email address was given to us by someone using it to submit a support request. If you did not send this request, please send a message to ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
?>
