<?php
 header("Content-type: text/javascript");
 include("includes/application_top.php"); 
?>

function doIframe(){
	o = document.getElementsByTagName('iframe');
	for(i = 0; i < o.length; i++){
		if (/\bautoHeight\b/.test(o[i].className)){
			setHeight(o[i]);
			addEvent(o[i],'load', doIframe);
		}
	}
}

function setHeight(e){

if(e.contentDocument){

e.height = e.contentDocument.body.offsetHeight + 35;

} else {

e.height = e.contentWindow.document.body.scrollHeight;

}

}

function addEvent(obj, evType, fn){

if(obj.addEventListener)

{

obj.addEventListener(evType, fn,false);

return true;

} else if (obj.attachEvent){

var r = obj.attachEvent("on"+evType, fn);

return r;

} else {

return false;

}

}

if (document.getElementById && document.createTextNode){

addEvent(window,'load', doIframe);

}

function popupPrintReceipt(url) {

window.open(url,'popupPrintReceipt','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=750')

}

function newwindow()

{

window.open('cvv_help.php','jav','width=500,height=550,resizable=no,toolbar=no,menubar=no,status=no');

}

var selected;

var submitter = null;

function submitFunction() {

submitter = 1;

}

function selectRowEffect2(object, buttonSelect) {

if (!document.checkout_payment.payment[0].disabled){

if (!selected) {

if (document.getElementById) {

selected = document.getElementById('defaultSelected');

} else {

selected = document.all['defaultSelected'];

}

}

// one button is not an array

if (document.checkout_payment.payment[0]) {

} else {

}

}

}

function rowOverEffect2(object) {

if (object.className == 'moduleRow') object.className = 'moduleRowOver';

}

function rowOutEffect2(object) {

if (object.className == 'moduleRowOver') object.className = 'moduleRow';

}

function clearRadeos(){

document.checkout_payment.cot_gv.checked=!document.checkout_payment.cot_gv.checked;

}

function check_form() {

var error = 0;

var error_message = "Errors have occured during the process of your form.\n\nPlease make the following corrections:\n\n";

var payment_value = null;
if (typeof(document.checkout_payment) != 'undefined'){
if (document.checkout_payment.payment.length) {

for (var i=0; i<document.checkout_payment.payment.length; i++) {

if (document.checkout_payment.payment[i].checked) {

payment_value = document.checkout_payment.payment[i].value;

}

}

} else if (document.checkout_payment.payment.checked) {

payment_value = document.checkout_payment.payment.value;

} else if (document.checkout_payment.payment.value) {

payment_value = document.checkout_payment.payment.value;

}

if (payment_value == "linkpoint_api") {

var cc_number = document.checkout_payment.linkpoint_api_cc_number.value;

if (cc_number == "" || cc_number.length < 10) {

error_message = error_message + "* The credit card number must be at least 10 characters.\n";

error = 1;

}

}

if (payment_value == null && submitter != 1) {

error_message = error_message + "* Please select a payment method for your order.\n";

error = 1;

}

if (error == 1 && submitter != 1) {

alert(error_message);

return false;

} else {

return true;

}
}
}

function selectRowEffect(object, buttonSelect) {

if (!selected) {

if (document.getElementById) {

selected = document.getElementById('defaultSelected');

} else {

selected = document.all['defaultSelected'];

}

}

// one button is not an array

if (document.checkout_address.shipping[0]) {

document.checkout_address.shipping[buttonSelect].checked=true;

} else {

document.checkout_address.shipping.checked=true;

}

}

function rowOverEffect(object) {

if (object.className == 'moduleRow') object.className = 'moduleRowOver';

}

function rowOutEffect(object) {

if (object.className == 'moduleRowOver') object.className = 'moduleRow';

}

var req_adv;

function loadXMLDoc_advanced(key) {

var url="quickfind.php?&keywords="+key;

// Internet Explorer

try { req_adv = new ActiveXObject("Msxml2.XMLHTTP"); }

catch(e) {

try { req_adv = new ActiveXObject("Microsoft.XMLHTTP"); }

catch(oc) { req_adv = null; }

}

// Mozailla/Safari

if (!req_adv && typeof XMLHttpRequest != "undefined") { req_adv = new XMLHttpRequest(); }

// Call the processChange_advanced() function when the page has loaded

if (req_adv != null) {

req_adv.onreadystatechange = processChange_advanced;

req_adv.open("GET", url, true);

req_adv.send(null);

}

}

function processChange_advanced() {

// The page has loaded and the HTTP status code is 200 OK

if (req_adv.readyState == 4 && req_adv.status == 200) {

// Write the contents of this URL to the searchResult layer

getObject_advanced("quicksearch").innerHTML = req_adv.responseText;

}

}

function getObject_advanced(name) {

var ns4 = (document.layers) ? true : false;

var w3c = (document.getElementById) ? true : false;

var ie4 = (document.all) ? true : false;

if (ns4) return eval('document.' + name);

if (w3c) return document.getElementById(name);

if (ie4) return eval('document.all.' + name);

return false;

}

window.onload = function() {

getObject_advanced("keywords").focus();

}

var req;

function loadXMLDoc(key) {

var url="state_dropdown.php?&country="+key;

getObject("states").innerHTML = '&nbsp;<img src="static/loading.gif" width="32px" hieght="32px">Please wait...';

try { req = new ActiveXObject("Msxml2.XMLHTTP"); }

catch(e) {

try { req = new ActiveXObject("Microsoft.XMLHTTP"); }

catch(oc) { req = null; }

}

if (!req && typeof XMLHttpRequest != "undefined") { req = new XMLHttpRequest(); }

if (req != null) {

req.onreadystatechange = processChange;

req.open("GET", url, true);

req.send(null);

}

}

function processChange() {
	if (req.readyState == 4 && req.status == 200) {
		getObject("states").innerHTML = req.responseText;
 		if(typeof(document.account) != 'undefined')
			document.account.state.focus();
 		if(typeof(document.create_account) != 'undefined')
			document.create_account.state.focus();
	}
}

function getObject(name) {
	var ns4 = (document.layers) ? true : false;
	var w3c = (document.getElementById) ? true : false;
	var ie4 = (document.all) ? true : false;
	if (ns4) return eval('document.' + name);
	if (w3c) return document.getElementById(name);
	if (ie4) return eval('document.all.' + name);
	return false;
}

function change(file){
	document.cart_quantity.source.src=file;
}

function print_option(form){
	window.print(form);
	return false;
}

function newwindow(){
	window.open('cvv_help.php','jav','width=500,height=550,resizable=no,toolbar=no,menubar=no,status=no');
}

function popupWindow(url) {
	window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=550px,height=500px,screenX=150,screenY=250');
	return false;
}

function popupstsWindow(url){
	newwindow=window.open(url,'name','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150');
}

window.onload=show;

function show(id) {
	var d = document.getElementById(id);
	for (var i = 1; i<=20; i++) {
		if (document.getElementById('answer_q'+i)) {document.getElementById('answer_q'+i).style.display='none';}
	}
	if (d) {d.style.display='block';}
}

function sortBy(url){
	window.location.href = url+"&sort_id="+document.sort_dropdown.sort_id.value;
}

function UpdateCartQuantity(){
	document.cart_quantity.submit();
}
function changeQuantity(i,qty){
	document.cart_quantity['qty_'+i].value = Number(document.cart_quantity['qty_'+i].value)+Number(qty);
	UpdateCartQuantity();
}

function check_agree(TheForm) {
	if (TheForm.agree.checked) {
		return true;
	} else {
		alert(unescape('Please read our conditions of use and agree to them. If you do not do so, your order will not be processed.'));
	}
	return false;
}

var win = null;

function NewWindow(mypage,myname,w,h,scroll){

LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;

TopPosition = (screen.height) ? (screen.height-h)/2 : 0;

settings =

'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable'

win = window.open(mypage,myname,settings)

}

function session_win() {

window.open("info_shopping_cart.php","info_shopping_cart","height=460,width=790,toolbar=no,statusbar=no,scrollbars=yes").focus();

}

function bookmark_us(url, title){

if (window.sidebar) // firefox

window.sidebar.addPanel(title, url, "");

else if(window.opera && window.print){ // opera

var elem = document.createElement('a');

elem.setAttribute('href',url);

elem.setAttribute('title',title);

elem.setAttribute('rel','sidebar');

elem.click();

}

else if(document.all)// ie

window.external.AddFavorite(url, title);

}

window.addEvent('domready', function() {

new Rokmoomenu($E('#menu'), {

bgiframe: false,

delay: 500,

animate: {

props: ['opacity', 'width', 'height'],

opts: {

duration:400,

fps: 100,

transition: Fx.Transitions.Expo.easeOut

}

}

});

	  Recaptcha.create("<?php echo RECAPTCHA_PUBLIC_KEY; ?>",
		"ajaxRecaptcha",{
			theme: "red"
		}
	  );

});


$(document).ready(function(){docInit();});
$(document).bind('pageinit',function(){ docInit(); });

function docInit(){
	if ($("#ajaxRecaptcha").length > 0){
	  Recaptcha.create("<?php echo RECAPTCHA_PUBLIC_KEY; ?>",
		"ajaxRecaptcha",{
			theme: "red"
		}
	  );
	}
	
	var validator = $("form[name='create_account']").attr("validate",true).validate({
 		errorClass: 'ui-state-error',
 		onkeyup: false,
 		rules: {
			firstname: {
				required: true,
				minlength: <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>
			},
			lastname: {
				required: true,
				minlength: <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>
			},
			email_address: {
				required: true,
				email: true,
				remote: {
					url: 'ajax.php',
					type: 'post',
					data: { 'check-email': true } 
				}
			},
			street_address: {
				required: true,
				minlength: <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>
			},
			<?php if (ACCOUNT_CITY == 'true') { ?>
			city: {
				required: true,
				minlength: <?php echo ENTRY_CITY_MIN_LENGTH; ?>
			},
			<?php } ?>
			<?php if (ACCOUNT_TELEPHONE == 'true') { ?>
			telephone: {
				required: true,
				minlength: <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>
			},
			<?php } ?>
<?php
    if (!isset($_GET['guest']) && !isset($_POST['guest'])) {
?>
			password: {
				required: true,
				minlength: <?php echo ENTRY_PASSWORD_MIN_LENGTH; ?>
			},
			confirmation: {
				equalTo: "#password-field"	
			},
<?php } ?>
			country: {
				required: true
			},
			postcode: {
				required: true,
				minlength: <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>
			}
		},
		messages: {
			firstname: {
				minlength: '<?php echo ENTRY_FIRST_NAME_ERROR; ?>'
			},
			lastname: {
				minlength: '<?php echo ENTRY_LAST_NAME_ERROR; ?>'
			},
<?php
    if (!isset($_GET['guest']) && !isset($_POST['guest'])) {
?>
			password: {
			 minlength: '<?php echo ENTRY_PASSWORD_ERROR; ?>'	
			},
			confirmation: {
				equalTo: '<?php echo ENTRY_PASSWORD_ERROR_NOT_MATCHING; ?>'	
			},
<?php } ?>
			email_address: {
				email: "<?php echo ENTRY_EMAIL_ADDRESS_CHECK_ERROR; ?>",
			},
			street_address: {
				minlength: '<?php echo ENTRY_STREET_ADDRESS_ERROR; ?>'
			},
			<?php if (ACCOUNT_CITY == 'true') { ?>
			city: {
				minlength: '<?php echo ENTRY_CITY_ERROR; ?>'
			},
			<?php } ?>
			<?php if (ACCOUNT_TELEPHONE == 'true') { ?>
			telephone: {
				minlength: '<?php echo ENTRY_TELEPHONE_NUMBER_ERROR; ?>'
			},
			<?php } ?>
			postcode: {
				minlength: '<?php echo ENTRY_POST_CODE_ERROR; ?>'
			}
		},		
	});
};