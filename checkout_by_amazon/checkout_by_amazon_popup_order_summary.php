<?php
/////////////////////////////////////////////////////////////////////////////////////
//
// Produces the javascript popup from amazon with order summary
//
/////////////////////////////////////////////////////////////////////////////////////
?>
<script src="<?php echo(CBA_JQUERY_SETUP); ?>" type="text/javascript"></script>
<link href="<?php echo(CBA_STYLE_SHEET); ?>" media="screen" rel="stylesheet" type="text/css"/>
<link type="text/css" rel="stylesheet" media="screen" href=<?php echo(CBA_POPUP_STYLE_SHEET); ?>/>
<script src="<?php if(MODULE_PAYMENT_CHECKOUTBYAMAZON_OPERATING_ENVIRONMENT == 'Production'){echo(PROD_POPUP_ORDER_SUMMARY);} else {echo(SANDBOX_POPUP_ORDER_SUMMARY);}?>" type="text/javascript"></script>
<?php
/////////////////////////////////////////////////////////////////////////////////////
//
//
/////////////////////////////////////////////////////////////////////////////////////
?>
