
function Requester()
{
	this.action = null;
	this.XML = null;
	this.commInterface = null;
	this.targetId = null
	// Initialise XMLHttpRequest object
	this.resetXMLHR();

	return true;
}


/**
 * Check if the XMLHttpRequest object is available 
 */
Requester.prototype.isAvailable = function(){
	return (this.commInterface == null) ? false : true;
}


/* Execute the action which has been associated with the completion of this object */
Requester.prototype.executeAction = function() {
	// If XMLHR object has finished retrieving the data
	
	if (this.commInterface.readyState == 4)	{
		// If the data was retrieved successfully
		try	{
			if (this.commInterface.status == 200)	{
				this.responseText = this.commInterface.requestXML;
				this.action();
			}
			// IE returns status = 0 on some occasions, so ignore
			else if (this.commInterface.status != 0){
				alert("There was an error while retrieving the URL: " + this.commInterface.statusText);
			}
		}
		catch (error){}
	}
	return true;
}


/* Return responseText */
Requester.prototype.getText = function() {
	return this.commInterface.responseText;
}


/* Return responseXML */
Requester.prototype.getXML = function() {
	return this.commInterface.responseXML;
}


/* Initialise XMLHR object and load URL */
Requester.prototype.loadURL = function(URL, CGI) {
	this.resetXMLHR();
	
	this.commInterface.open("GET", URL + "?" + CGI);
	var e=(document.charset||document.characterSet||'ISO-8859-8-i');
	this.commInterface.setRequestHeader("Content-Type", "text/html; charset="+e);
	this.commInterface.setRequestHeader('Accept-Charset',e)
	this.commInterface.send(null);

	return true;
}


/* Turn off existing connections and create a new XMLHR object */
Requester.prototype.resetXMLHR = function() {
	var self = this;

	if (this.commInterface != null && this.commInterface.readyState != 0 && this.commInterface.readyState != 4)	{
		this.commInterface.abort();
	}

	try	{
		this.commInterface = new XMLHttpRequest();
	}
	catch (error) {
		try {
			this.commInterface = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (error) {
			return false;
		}
	}

	this.commInterface.onreadystatechange = function()	{
		self.executeAction();
		return true;
	};
	return true;
}

/* Assign the function which will be executed once the XMLHR object finishes retrieving data */
Requester.prototype.setAction = function(actionFunction,part) {
	this.action = actionFunction;
	return true;
}


Requester.prototype.setTarget = function(targetId) {
	this.targetId = targetId;
	return true;
}

Requester.prototype.getTarget = function() {
	return this.targetId;
}