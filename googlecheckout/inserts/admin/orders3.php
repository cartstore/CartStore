<?php
/*
  Copyright (C) 2009 Google Inc.

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
 * Google Checkout v1.5.0
 * $Id$
 * 
 * This code is meant to be included in catalog/admin/orders.php.
 * 
 * TODO(eddavisson): Investigate refactoring.
 * 
 * @author Ed Davisson (ed.davisson@gmail.com)
 */

// Google Checkout Tracking Number
//orders_status == STATE_PROCESSING -> Processing before delivery
if(strpos($order->info['payment_method'], 'Google')!= -1 && $order->info['orders_status'] == GC_STATE_PROCESSING){
  echo '' .
      '<td><table border="0" cellpadding="3" cellspacing="0" width="100%">   
        <tbody>
          <tr>  
            <td style="border-top: 2px solid rgb(255, 255, 255); border-right: 2px solid rgb(255, 255, 255);" nowrap="nowrap" colspan="2">
                <b>Shipping Information</b>  
            </td>  
          </tr>
          <tr>  
            <td nowrap="nowrap" valign="middle" width="1%">  
              <font size="2">  
                <b>Tracking:</b>  
              </font>  
            </td>  
            <td style="border-right: 2px solid rgb(255, 255, 255); border-bottom: 2px solid rgb(255, 255, 255);" nowrap="nowrap">   
              <input name="tracking_number" style="color: rgb(0, 0, 0);" id="trackingBox" size="20" type="text">   
            </td>  
          </tr>  
          <tr>  
            <td nowrap="nowrap" valign="middle" width="1%">  
              <font size="2">  
                <b>Carrier:</b>  
              </font>  
            </td>  
            <td style="border-right: 2px solid rgb(255, 255, 255);" nowrap="nowrap">  
              <select name="carrier_select" style="color: rgb(0, 0, 0);" id="carrierSelect">  
                <option value="select" selected="selected">
                 Select ...  
                </option>   
                <option value="USPS">
                 USPS  
                </option>   
                <option value="DHL">
                 DHL  
                </option>   
                <option value="UPS">
                 UPS  
                </option>   
                <option value="Other">
                 Other  
                </option>   
                <option value="FedEx">
                 FedEx  
                </option>   
              </select>  
            </td>  
          </tr>     
        </tbody> 
      </table></td>';
}

?>
