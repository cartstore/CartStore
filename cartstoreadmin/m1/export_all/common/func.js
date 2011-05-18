
 // Custom functions for working with strings and arrays
 // author tsergiy
 
 // strings functions

function splitByUppercase(str)
{
	var strlen = str.length;
	var minCode = 65;	//ord('A');
	var maxCode = 90;	//ord('Z');
	
	var result = Array();
	var curStr = '';

	for (i = 0; i < strlen; i++) {
		
		chrCode = str.charCodeAt(i);
				  
		if ((chrCode >= minCode) && (chrCode <= maxCode)) {

			if (curStr) result[result.length] = curStr;
			curStr = str.charAt(i);
		
		} else {
			curStr = curStr + str.charAt(i);
		}
	}
	
	result[result.length] = curStr;
	return result;
}

function splitByWords(str, words)
{
	var strlen = str.length;
	
	var result = Array();
	var curStr = '';

	for (i = 0; i < strlen; i++) {
			  
		if (in_array(curStr, words)) {

			if (curStr) result[result.length] = curStr;
			curStr = str.charAt(i);
			checkArr = false;
		
		} else {
			curStr = curStr + str.charAt(i);
		}
	}
	
	if (in_array(curStr, words)) {
		result[result.length] = curStr;
		
	} else {
		curPos =  result.length - 1;
		
		for (i = curPos; i >= 0; i--) {
			curStr = result[i] + curStr;
			
			if (in_array(curStr, words)) {
				result[i] = curStr;
				break;
			}
			
			result.pop();
		}
		
		if (!result) {		
			result = Array(str);
		}
	}
	
	return result;
}

 // end of strings functions
 
 // array functions

function in_array(needle, haystack)
{
	for (j = 0; j < haystack.length; j++) {
			
		if (haystack[j] == needle) {
			return true;
		}
	}

	return false;
}

 // end of array functions