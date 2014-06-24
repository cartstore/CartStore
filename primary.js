
var req_adv; 

function loadXMLDoc_advanced(key) {
  
   var url="quickfind.php?<?php echo tep_session_name() . '=' . tep_session_id(); ?>&keywords="+key;

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
   var url="state_dropdown.php?CLCid=<?php echo tep_session_id();?>&country="+key;
   getObject("states").innerHTML = '&nbsp;<img style="vertical-align:middle" src="/includes/sts_templates/default/includes/sts_templates/default//includes/sts_templates/default//includes/sts_templates/default/images/loading.gif">Please wait...';
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
      document.account.state.focus();
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



function change(file)
{

document.cart_quantity.source.src=file;
}




function print_option(form)
{
window.print(form);
return false;
}
function newwindow()
{
window.open('cvv_help.php','jav','width=500,height=550,resizable=no,toolbar=no,menubar=no,status=no');
}



function popupWindow(url) {

window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=550px,height=500px,screenX=150,screenY=250');
return false;
}





function popupstsWindow(url)
{
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


var selected;

function selectRowEffect(object, buttonSelect) {
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;

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


function check_agree(TheForm) {
  if (TheForm.agree.checked) {
    return true;
  } else {
    alert(unescape('Please read our conditions of use and agree to them. If you do not do so, your order will not be processed.'));
    return false;
  }
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
  window.open("<?php echo tep_href_link(FILENAME_INFO_SHOPPING_CART); ?>","info_shopping_cart","height=460,width=790,toolbar=no,statusbar=no,scrollbars=yes").focus();
}