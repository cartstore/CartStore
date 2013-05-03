/**
 * @author alexerm
 */

$(document).ready(function () {

});

var Taxonomy = {
	constSameAsParent: '[Same as Parent Category]',
	constNone: '[None]'
};

function switchToEditMode(id)
{
	var cell = $('#' + id);
	cell.find('.showmode').hide();
	cell.find('.editmode').show();
	cell.find('.editmode input')
		.autocomplete(remoteMatching, {
		width: 300,
		multiple: false,
		matchContains: true,
		max: 30
	});
	cell.find('.editmode input').get(0).select();

	cell.find('.editmode input').result(function (event, data, formatted) {
		var c = $(this).parents('td');
		cid = c.attr('id');
		$('#' + cid).find('.matches-id').val(data[1]);

	});
	cell.parent('tr').css('background-color', '#f9f9f9');

} 

function switchOffEditMode(id)
{
	var cell = $('#' + id);
	cell.find('.showmode').show();
	cell.find('.editmode').hide();
	cell.find('.editmode input').unautocomplete();
	cell.parent('tr').css('background-color', '#ffffff');
}

function cancelEditMode(id)
{
	switchOffEditMode(id);
	var cell = $('#' + id);
	var oldText = cell.find('.showmode span').text();
	cell.find('.editmode input').val(oldText);
}

 // Modified by tsergiy - custom taxonomy feature
 // 20.11.2008
 
//function saveResult(id)
function saveResult(id, allowCustomValue)

 // End of tsergiy's modifying
 
{ 
	var cell = $('#' + id);
	var newText = cell.find('.editmode input').val();
	$.ajax({
	  type: "GET",
	  url: remoteMatching + '&q=' + escape(newText) + '&count=1',
	  cellid: id,  
	  success: function(msg){
	  	
	  	//Added by tsergiy - http://jira:8080/browse/CR-476 fix on http://www.bitznpeesiz.com
	  	msg = trim(msg);
	  	//End of fix
	  	
	  	 // Added by tsergiy - custom taxonomy feature
		 // 20.11.2008
		 
	  	var taxval;
		 
		if ((msg != '1') && allowCustomValue && newText && (newText != '') && (newText.toUpperCase() != 'P')) {
	  		msg = '1';
	  		taxval = newText;
	  	}

	  	 // End of tsergiy's adding
	  	
	  	if ($('#' + id).find('.editmode input').val() == Taxonomy.constNone)
		{
			setToNone(this.cellid);
		}
		else if ($('#' + id).find('.editmode input').val() == Taxonomy.constSameAsParent)
		{
			setToAsParent(this.cellid);
		}
	  	else if (msg == '1')
		{
			switchOffEditMode(id);
			var c = $('#' + this.cellid);
			
			 // Added by tsergiy - custom taxonomy feature
			 // 20.11.2008
			 
			if (!taxval) {
				
			 // End of tsergiy's adding
				
				var taxid = c.find('.matches-id').val();
				
			 // Added by tsergiy - custom taxonomy feature
			 // 20.11.2008
			 
			 	taxval = taxid;
			}
			
			 // End of tsergiy's adding
			 
			var storeid = c.find('.matches-id').attr('rel')
			
			 // Modified by tsergiy - custom taxonomy feature
			 // 20.11.2008
			 
			//if (saveOnServerSide(storeid, taxid))
			if (saveOnServerSide(storeid, taxval))
			
			 // End of tsergiy's modifying
			 
			{
				var t = c.find('.editmode input').val();
				c.find('.showmode span').text(t);
			}
			else
			{
				setToNone(this.cellid);
			}
		}
		// entered value not found in taxonomy db
		else 
		{
			showSaveErrorMessage(this.cellid);
		}
	  },
	  error: function () {
	  	cancelEditMode(this.cellid);
	  }
	});
}

function saveOnServerSide(storeid, taxid)
{
	
	 // Added by tsergiy - custom taxonomy feature
	 // 20.11.2008
	 
	taxid = taxid.replace('&', '%26');
	 
	 // End of tsergiy's adding		 
			 
	var response = $.ajax({
	  type: "GET",
	  url: remoteMatching + '&action=update&M1_EXPORT_CATEGORY_MATCHES['+storeid+']=' + taxid,
	  async: false
	}).responseText;
	
	//Added by tsergiy - http://jira:8080/browse/CR-476 fix on http://www.bitznpeesiz.com
	response = trim(response);
	//End of fix
	
	return response == '1';
}

function setToNone(id)
{
	switchOffEditMode(id);
	var cell = $('#' + id);
	cell.find('.editmode input').val(Taxonomy.constNone)
	cell.find('.showmode span').text(Taxonomy.constNone);
	cell.find('.matches-id').val('');
	var storeid = cell.find('.matches-id').attr('rel');
	saveOnServerSide(storeid, '');
}

function setToAsParent(id)
{
	switchOffEditMode(id);
	var cell = $('#' + id);
	cell.find('.editmode input').val(Taxonomy.constSameAsParent);
	cell.find('.showmode span').text(Taxonomy.constSameAsParent);
	cell.find('.matches-id').val('P');
	var storeid = cell.find('.matches-id').attr('rel');
	saveOnServerSide(storeid, 'P');
}

 // Modified by tsergiy - custom taxonomy feature
 // 20.11.2008
 
//function editKeyEvent(id, e)
function editKeyEvent(id, e, allowCustomValue)

 // End of tsergiy's modifying
 
{
	if (e.keyCode == 27)
	{
		cancelEditMode(id);
	}
	else if(e.keyCode == 13)
	{
		if ($('div.ac_results').css('display') == 'none')
		
		 // Modified by tsergiy - custom taxonomy feature
		 // 20.11.2008
		 
			//saveResult(id);
			saveResult(id, allowCustomValue);
			
		 // End of tsergiy's modifying
	}
}

function showSaveErrorMessage(id)
{
	var cell = $('#' + id);
	if(cell.find('.save_error').length == 0)
		cell.append('<div class="save_error" style="display: none">Category you entered does not match any category in Comparison Shopping Engine (CSE) taxonomy. <br>You need to select one of existing categories. <br>Please hold few seconds after finish typing.</div>');
	cell.find('.save_error').fadeIn(500);
	setTimeout("$('#" + id + "').find('.save_error').fadeOut(1000)", 10000);
}

//Added by tsergiy - http://jira:8080/browse/CR-476 fix on http://www.bitznpeesiz.com
function trim(tmp)
{
	 blanks={' ':true,"\n":true,"\r":true,"\t":true};
	 
	 //ltrim
	 while (blanks[tmp.charAt(0)]) {
		tmp=tmp.substring(1,tmp.length);
	 }
	 
	 //rtrim
	while (blanks[tmp.charAt(last=tmp.length-1)]) {
		tmp=tmp.substring(0,last); 
	}
 
	return tmp;
}
//End of fix