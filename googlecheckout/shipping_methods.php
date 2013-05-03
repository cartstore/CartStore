<?php
/**
  * File: googlecheckout/shipping_methods.php file
  */ 
$mc_shipping_methods = array(
                        'fedex1' => array(
                                    'domestic_types' =>
                                      array(
                                          '06' => 'First Overnight',
                                          '01' => 'Priority (by 10:30AM, later for rural)',
                                          '05' => 'Standard Overnight (by 3PM, later for rural)',
                                          '03' => '2 Day Air',
                                          '20' => 'Express Saver (3 Day)',
                                          '90' => 'Home Delivery (1 days)',

                                           ),

                                    'international_types' =>
                                      array(
                                          '01' => 'International Priority (1-3 Days)',
                                          '03' => 'International Economy (4-5 Days)',

                                           ),
                                        ),
                        'flat' => array(
                                    'domestic_types' =>
                                      array(
                                          'flat' => 'Best Way',

                                           ),

                                    'international_types' =>
                                      array(
                                          'flat' => 'Best Way_2',

                                           ),
                                        ),
                        'table' => array(
                                    'domestic_types' =>
                                      array(
                                          'table' => 'Best Way_1',

                                           ),

                                    'international_types' =>
                                      array(
                                          'table' => 'Best Way_3',

                                           ),
                                        ),
                        'zones' => array(
                                    'domestic_types' =>
                                      array(
                                          'zones' => 'Shipping to US : 0 lb(s)',

                                           ),

                                    'international_types' =>
                                      array(

                                           ),
                                        ),
                        'indvship' => array(
                                    'domestic_types' =>
                                      array(
                                          'indvship' => 'Shipping Total',

                                           ),

                                    'international_types' =>
                                      array(
                                          'indvship' => 'Shipping Total_1',

                                           ),
                                        ),
                        'pickup' => array(
                                    'domestic_types' =>
                                      array(
                                          'pickup' => 'Customer Pickup',

                                           ),

                                    'international_types' =>
                                      array(
                                          'pickup' => 'Customer Pickup_1',

                                           ),
                                        ),
                        'upsxml' => array(
                                    'domestic_types' =>
                                      array(
                                          'UPS Ground' => 'UPS Ground (billed dimensional weight 1 LBS)',
                                          'UPS 3 Day Select' => 'UPS 3 Day Select (billed dimensional weight 1 LBS)',
                                          'UPS 2nd Day Air' => 'UPS 2nd Day Air (billed dimensional weight 1 LBS)',
                                          'UPS 2nd Day Air A.M.' => 'UPS 2nd Day Air A.M. (billed dimensional weight 1 LBS)',
                                          'UPS Next Day Air Saver' => 'UPS Next Day Air Saver (billed dimensional weight 1 LBS)',
                                          'UPS Next Day Air' => 'UPS Next Day Air (billed dimensional weight 1 LBS)',
                                          'UPS Next Day Air Early A.M.' => 'UPS Next Day Air Early A.M. (billed dimensional weight 1 LBS)',

                                           ),

                                    'international_types' =>
                                      array(
                                          'UPS Worldwide Expedited' => 'UPS Worldwide Expedited (billed dimensional weight 1 LBS)',
                                          'UPS Saver' => 'UPS Saver (billed dimensional weight 1 LBS)',

                                           ),
                                        ),
                        'usps' => array(
                                    'domestic_types' =>
                                      array(
                                          'First-Class Mail regimark Letter' => 'First-Class Mail regimark Letter&amp;lt;br&amp;gt;---Approx. delivery time 3 Days',
                                          'Media Mail regimark' => 'Media Mail regimark&amp;lt;br&amp;gt;---Approx. delivery time 4 Days',
                                          'Priority Mail regimark Flat Rate Envelope' => 'Priority Mail regimark Flat Rate Envelope&amp;lt;br&amp;gt;---Approx. delivery time 3 Days',
                                          'Priority Mail regimark' => 'Priority Mail regimark&amp;lt;br&amp;gt;---Approx. delivery time 3 Days',
                                          'Priority Mail regimark Small Flat Rate Box' => 'Priority Mail regimark Small Flat Rate Box&amp;lt;br&amp;gt;---Approx. delivery time 3 Days',
                                          'Parcel Post regimark' => 'Parcel Post regimark&amp;lt;br&amp;gt;---Approx. delivery time 4 Days',
                                          'Express Mail regimark' => 'Express Mail regimark&amp;lt;br&amp;gt;---Approx. delivery time 25-Jun-2011',
                                          'Express Mail regimark Flat Rate Envelope' => 'Express Mail regimark Flat Rate Envelope&amp;lt;br&amp;gt;---Approx. delivery time 25-Jun-2011',

                                           ),

                                    'international_types' =>
                                      array(
                                          'First-Class Mail regimark International Letter**' => 'First-Class Mail regimark International Letter**&amp;lt;br&amp;gt;---Approx. delivery time Varies by country',
                                          'First-Class Mail regimark International Large Envelope**' => 'First-Class Mail regimark International Large Envelope**&amp;lt;br&amp;gt;---Approx. delivery time Varies by country',
                                          'First-Class Mail regimark International Package**' => 'First-Class Mail regimark International Package**&amp;lt;br&amp;gt;---Approx. delivery time Varies by country',
                                          'Priority Mail regimark International Small Flat Rate Box**' => 'Priority Mail regimark International Small Flat Rate Box**&amp;lt;br&amp;gt;---Approx. delivery time 6 - 10 business days',
                                          'Priority Mail regimark International Flat Rate Envelope**' => 'Priority Mail regimark International Flat Rate Envelope**&amp;lt;br&amp;gt;---Approx. delivery time 6 - 10 business days',
                                          'Priority Mail regimark International' => 'Priority Mail regimark International&amp;lt;br&amp;gt;---Approx. delivery time 6 - 10 business days',
                                          'Express Mail regimark International' => 'Express Mail regimark International&amp;lt;br&amp;gt;---Approx. delivery time 3 - 5 business days',
                                          'Express Mail regimark International Flat Rate Envelope' => 'Express Mail regimark International Flat Rate Envelope&amp;lt;br&amp;gt;---Approx. delivery time 3 - 5 business days',
                                          'USPS GXG tradmrk Envelopes**' => 'USPS GXG tradmrk Envelopes**&amp;lt;br&amp;gt;---Approx. delivery time 1 - 3 business days',
                                          'Priority Mail regimark International Medium Flat Rate Box' => 'Priority Mail regimark International Medium Flat Rate Box&amp;lt;br&amp;gt;---Approx. delivery time 6 - 10 business days',
                                          'Priority Mail regimark International Large Flat Rate Box' => 'Priority Mail regimark International Large Flat Rate Box&amp;lt;br&amp;gt;---Approx. delivery time 6 - 10 business days',

                                           ),
                                        ),
                                  );

$mc_shipping_methods_names = array(
                                         'fedex1' => 'Federal Express (1 x 0lbs)',
                                         'flat' => 'Flat Rate',
                                         'table' => 'Table Rate',
                                         'zones' => 'Zone Rates',
                                         'indvship' => 'Individual Shipping - Zone 1',
                                         'pickup' => 'Pickup Rate',
                                         'upsxml' => 'United Parcel Service (XML) (1 pkg x 0 lbs total)',
                                         'usps' => 'United States Postal Service&amp;nbsp;0 lbs, 1 oz',
                                        );
?> 