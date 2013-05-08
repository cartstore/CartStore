<?php
/*
$Id: qbi_general.php,v 2.10 2005/05/08 al Exp $
Language file: English

Quickbooks Import QBI
contribution for CartStore
ver 2.10 May 8, 2005
(c) 2005 Adam Liberman
www.libermansound.com
info@libermansound.com
Please use the osC forum for support.
GNU General Public License Compatible

    This file is part of Quickbooks Import QBI.

    Quickbooks Import QBI is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    Quickbooks Import QBI is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Quickbooks Import QBI; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

define('HEADING_TITLE', 'Quickbooks Import QBI');

// Menu
define('QBI_MENU_CREATE', 'Create iif file');
define('QBI_MENU_PRODUCTS', 'Set Up Products');
define('QBI_MENU_SHIP', 'Set Up Shipping');
define('QBI_MENU_PRODUCTSMATCH', 'Match Products');
define('QBI_MENU_SHIPMATCH', 'Match Shipping');
define('QBI_MENU_CONFIG', 'Configure');
define('QBI_MENU_UTILITIES', 'Utilities');

// Menu (new)
define('MENU_1', 'Create iif');
define('MENU_2', 'Set Up');
define('MENU_2A', 'Products');
define('MENU_2B', 'Discounts/Fees');
define('MENU_2C', 'Shipping');
define('MENU_2D', 'Payment');
define('MENU_3', 'Match');
define('MENU_3A', 'Products');
define('MENU_3B', 'Discounts/Fees');
define('MENU_3C', 'Shipping');
define('MENU_3D', 'Payment');
define('MENU_4', 'Configure');
define('MENU_5', 'About');

// Setup files
define('SETUP_FILE_FOUND1', 'Found iif import file');
define('SETUP_FILE_FOUND2', '. Import now?');
define('SETUP_FILE_MISSING', 'Did not find iif import file.');
define('SETUP_FILE_BUTTON', 'Import iif File');
define('SETUP_SUCCESS', 'Setup successful!');
define('SETUP_NAME', 'Name');
define('SETUP_DESC', 'Description');
define('SETUP_ACCT', 'Account');
define('SETUP_ACTION', 'Action');
define('SETUP_NO_CHANGE', 'No change');
define('SETUP_UPDATED', 'Updated');
define('SETUP_ADDED', 'Added');

// Match
define('MATCH_BUTTON', 'Update Matches On This Page');
define('MATCH_PAGE', 'Results page:');
define('MATCH_PREV', 'Previous');
define('MATCH_NEXT', 'Next');
define('MATCH_SUCCESS', 'Matches updated.');
define('MATCH_OSC', 'CartStore');
define('MATCH_QB', 'Quickbooks');

// Warnings
define('WARN_CONFIGURE', 'QB Import must be set up and configured before use.');
define('WARN_CONFIGURE_LINK', 'Configure QB Import now.');

// Errors
define('ERROR_DIRECTORY_NOT_WRITEABLE', 'Error: I can not write to this directory. Please set the right user permissions on: %s');
define('ERROR_DIRECTORY_DOES_NOT_EXIST', 'Error: Directory does not exist: %s');
?>