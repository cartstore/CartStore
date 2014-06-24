<?php
/*
  tracking.php,v 2.1 2008/03/08 12:00:01

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2008 osCommerce

  Released under the GNU General Public License

COUNTRY CODES

Argentina = AR
Austria = AT
Australia = AU
Belgium = BE
Brazil = BR
Canada = CA
Switzerland = CH
Chile = CL
China = CN
Colombia = CO
Costa Rica = CR
Germany = DE
Denmark = DK
Dominican Republic = DO
Spain = ES
Finland = FI
France = FR
United Kingdom = GB
Greece = GR
Guatemala = GT
Hong Kong = HK
Indonesia = ID
Ireland = IE
Israel = IL
India = IN
Italy = IT
Japan = JP
Korea (South) = KR
Mexico = MX
Malaysia = MY
Netherlands = NL
Norway = NO
New Zealand = NZ
Panama = PA
Peru = PE
Philippines = PH
Puerto Rico = PR
Portugal = PT
Russian Federation = RU
Sweden = SE
Singapore = SG
Thailand = TH
Taiwan = TW
United States = US
Venezuela = VE
Virgin Islands(U.S.) = VI
South Africa = ZA

LANGUAGE CODES

Danish = dan
Dutch = dut
English = eng
French = fre
German = ger
Italian = ita
Portuguese = por
Spanish = spa
*/

// ** Change These Variables To Match Your Site**
define('NAVBAR_TITLE', 'Tracking'); //Will appear in the navigation bar. Example: Top >> Catalog >> Tracking
define('HEADING_TITLE', 'Package Tracking'); //Will appear in bold at the top of the page.
define('HTML_VERSION', '3.0'); //Tracking HTML Version number as stated on UPS website or confirmation email from UPS.
define('INQUIRY_TYPE', 'T'); // T = By tracking number - R = By reference number. (Don't change unless you know what you are doing)
define('DEFAULT_LANGUAGE', 'eng'); //Default language to view tracking results in. (3 letter language code from above)
define('DEFAULT_COUNTRY', 'us'); // Default country packages will be shipped. (2 letter country code from above)

// ** START USPS INFO**
define('TEXT_INFORMATION_USPS', 'Enter your <b>USPS</b> tracking number below:');
define('TEXT_LINK_USPS', 'http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?');
define('IMAGE_BUTTON_USPSTRACK', 'Track a USPS package');
// ** END USPS INFO**

// ** START UPS INFO**
define('TEXT_INFORMATION_UPS', 'Enter your <b>UPS</b> tracking number below:');
define('TEXT_LINK_UPS', 'http://wwwapps.ups.com/etracking/tracking.cgi');
define('IMAGE_BUTTON_UPSTRACK', 'Track a UPS package');
// ** END UPS INFO**

// ** START FEDEX INFO**
define('TEXT_INFORMATION_FEDEX', 'Enter your <b>Fedex</b> tracking number below:');
define('TEXT_LINK_FEDEX', 'http://www.fedex.com/Tracking?tracknumbers=');
define('IMAGE_BUTTON_FEDEXTRACK', 'Track a FedEx package');
// ** END FEDEX INFO**

// ** START DHL INFO**
define('TEXT_INFORMATION_DHL', 'Enter your <b>DHL</b> tracking number below:');
define('TEXT_LINK_DHL', 'http://track.dhl-usa.com/atrknav.asp');
define('IMAGE_BUTTON_DHLTRACK', 'Track a DHL package');
// ** END DHL INFO**
?>