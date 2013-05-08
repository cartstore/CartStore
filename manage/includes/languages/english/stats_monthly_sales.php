<?php
/*
  $Id: stats_monthly_sales.php,v 2.2 $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/

define('HEADING_TITLE', 'Monthly Sales/Tax Summary');
define('HEADING_TITLE_STATUS','Status');
define('HEADING_TITLE_REPORTED','Reported');
define('TEXT_DETAIL','Detail');
define('TEXT_ALL_ORDERS', 'All orders');
define('TEXT_NOTHING_FOUND', 'No income for this date/status selection');
define('TEXT_BUTTON_REPORT_BACK','Back');
define('TEXT_BUTTON_REPORT_INVERT','Invert');
define('TEXT_BUTTON_REPORT_PRINT','Print');
define('TEXT_BUTTON_REPORT_SAVE','Save CSV');
define('TEXT_BUTTON_REPORT_HELP','Help');
define('TEXT_BUTTON_REPORT_BACK_DESC', 'Return to summary by months');
define('TEXT_BUTTON_REPORT_INVERT_DESC', 'Invert rows top to bottom');
define('TEXT_BUTTON_REPORT_PRINT_DESC', 'Show report in printer friendly window');
define('TEXT_BUTTON_REPORT_HELP_DESC', 'About this report and how to use its features');
define('TEXT_BUTTON_REPORT_GET_DETAIL', 'Click to report daily summary for this month');
define('TEXT_REPORT_DATE_FORMAT', 'j M Y -   g:i a'); // date format string
//  as specified in php manual here: http://www.php.net/manual/en/function.date.php

define('TABLE_HEADING_YEAR','Year');
define('TABLE_HEADING_MONTH', 'Month');
define('TABLE_HEADING_DAY', 'Day');
define('TABLE_HEADING_INCOME', 'Gross<br> Income');
define('TABLE_HEADING_SALES', 'Product<br> sales');
define('TABLE_HEADING_NONTAXED', 'Nontaxed<br> sales');
define('TABLE_HEADING_TAXED', 'Taxed<br> sales');
define('TABLE_HEADING_TAX_COLL', 'Taxes<br> collected');
define('TABLE_HEADING_SHIPHNDL', 'Shipping<br> & Handling');
define('TABLE_HEADING_SHIP_TAX', 'Tax on<br /> shipping');
define('TABLE_HEADING_LOWORDER', 'Low Order<br /> Fees');
define('TABLE_HEADING_OTHER', 'Gift<br> Vouchers');  // could be any other extra class value
define('TABLE_FOOTER_YTD','YTD');
define('TABLE_FOOTER_YEAR','YEAR');
define('TEXT_HELP', '<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Monthly Sales/Tax Report</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<BODY>
<center>
<table width="95%"><tr><td>
<p class="main" align="center">
<b>How to view and use the store income summary report</b>
<p class="main" align="justify">
<b>Reporting store activity by month</b>
<p class="smallText" align="justify">
When initially selected from the Reports menu, this report displays a financial summary of all orders in the store database, by month.  Each month of the store\'s history is summarized in a row, showing the store income and its components, and listing the amounts of taxes, shipping and handling charges, low order fees and gift vouchers. (If the store does not have low order fees or gift vouchers enabled, these columns are omitted from the report.)  Activity is reported as of the date of purchase.
<p class="smallText" align="justify">
The top row is the current month, and the rows under it summarize each month of the store\'s order history.  Beneath the rows of each calendar year is a footer line, summarizing that year\'s totals in each column of the report. 
<p class="smallText" align="justify">
To invert the order of the rows, click the "Invert" button.
<p class="main" align="justify">
<b>Reporting monthly summary by days</b>
<p class="smallText" align="justify">
The summary of daily activity within any month may be displayed by clicking on the month\'s name, at the left of the row.  To return from the daily summary to the monthly summary, click the "Back" button in the daily display.
<p class="main" align="justify">
<b>What the columns represent (headers explained)</b>
<p class="smallText" align="justify">
On the left, the month and year of the row are stated.  The other columns are, left to right:
<ul><li class="smallText"><b>Gross Income</b> - the total of all orders  
<li class="smallText"><b>Order Subtotal</b> - the total sales of products purchased in the month
<br>Then, the product sales are broken into two categories:
<li class="smallText"><b>Nontaxed sales</b> - the subtotal of sales which were not taxed, and 
<li class="smallText"><b>Taxed sales</b> - the subtotal of sales which were taxed
<li class="smallText"><b>Taxes collected</b> - the amount collected from customers for taxes
<li class="smallText"><b>Shipping & handling</b> - the total shipping and handling charges collected  
<li class="smallText"><b>Tax on shipping</b> - Tax on shipping and handling charges
<li class="smallText"><b>Low order fees</b> and <b>Gift Vouchers</b> - if the store has low order fees enabled, and/or gift vouchers, the totals of these are shown in separate columns
</ul>
<p class="main" align="justify">
<b>Selecting report summary by status</b>
<p class="smallText" align="justify">
To show the monthly or daily summary information for just one Order Status, select the status in the drop-down box at the upper right of the report screen.  Depending on the store\'s setup for these values, there may be a status for "Pending" or "Shipped" for instance.  Change this status and the report will be recalculated and displayed. 
<p class="main" align="justify">
<b>Showing detail of taxes</b>
<p class="smallText" align="justify">
The amount of tax in any row of the report is a link to a popup window, which shows the name of the tax classes charged and their individual amounts.
<p class="main" align="justify">
<b>Printing the report</b>
<p class="smallText" align="justify">
To view the report in a printer-friendly window, click on the "Print" button, then use your browser\'s print command in the File menu.  The store name and headers are added to show what orders were selected, and when the report was generated. 
<p class="main" align="justify">
<b>Saving report values to a file</b>
<p class="smallText" align="justify">
To save the values of the report to a local file, click on the Save CSV button at the bottom of the report.  The report values will be sent to your browser in a text file, and you will be prompted with a Save File dialog box to choose where to save the file.  The contents of the file are in Comma Separated Value (CSV) format, with a line for each row of the report beginning with the header line, and each value in the row is separated by commas. This file can be conveniently and accurately imported to common spreadsheet financial and statistical tools, such as Excel and QuattroPro. The file is provided to your browser with a suggested file name consisting of the report name, status selected, and date/time. <br><br>
<p class="smallText">v 2.1.1
</td></tr>
</table>
</BODY>
</HTML>');
?>
