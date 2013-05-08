var xmlHttp

///////



///////

function getCategory()

{ 



//alert(document.cart_quantity.needle_DD.value);

xmlHttp=GetXmlHttpObject()

if (xmlHttp==null)

{

alert ("Browser does not support HTTP Request")

return

} 



var url="getCat.php";

mID = document.getElementById("manufacturers_id").value

url=url+"?mID="+mID+""

xmlHttp.onreadystatechange=statgetCategory

xmlHttp.open("GET",url,true)

xmlHttp.send(null)

}

function statgetCategory()

{ 



if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")

{ 



document.getElementById("cat").innerHTML=xmlHttp.responseText 



} 

}

/////





///////

function GetXmlHttpObject()

{ 

var objXMLHttp=null

if (window.XMLHttpRequest)

{

objXMLHttp=new XMLHttpRequest()

}

else if (window.ActiveXObject)

{

objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP")

}

return objXMLHttp

}

