/*
  Copyright (C) 2008 Google Inc.

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * Functions to help with complex configuration options.
 * 
 * TODO(eddavisson): Factor out duplicated code.
 */

/**
 * Updates the value of the hidden input on blur.
 */
function ccs_blur(value, code, hidden_id, position) {
	var hidden = document.getElementById(hidden_id);
	var split = hidden.value.substring((code + '_CCS:').length).split('|');
	
	value.value = (isNaN(parseFloat(value.value))) ? '' : parseFloat(value.value);
	if (value.value != '') {
		split[position] = value.value;
	} else {
		split[position] = 0;
		value.value = '0';
	}
	
	hidden.value = code + '_CCS:' + split[0] + '|' + split[1] + '|' + split[2];
}

/**
 * Updates the value of the hidden input on focus.
 */
function ccs_focus(value, code, hidden_id, position) {
	var hidden = document.getElementById(hidden_id);
	var split = hidden.value.substring((code + '_CCS:').length).split('|');
//value.value = value.value.substring((code + '_CCS:').length, hidden.value.length);
	split[position] = value.value;
	hidden.value = code + '_CCS:' + split[0] + '|' + split[1] + '|' + split[2];
}

/**
 * TODO(eddavisson)
 */
function vd_blur(value, code, hidden_id) {
	var hidden = document.getElementById(hidden_id);
	value.value = isNaN(parseFloat(value.value)) ? '' : parseFloat(value.value);
	if (value.value != '') {
		hidden.value = code + '_VD:' + value.value;
	//value.value = value.value;
  //hidden.disabled = false;
	} else {
		hidden.value = code + '_VD:0';
		value.value = '0'
	}
}

/**
 * TODO(eddavisson)
 */
function vd_focus(value, code, hidden_id) {
	var hidden = document.getElementById(hidden_id);
//value.value = value.value.substr((code + '_VD:').length, value.value.length);
	hidden.value = value.value.substr((code + '_VD:').length, value.value.length);
}