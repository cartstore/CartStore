<?php

  /**
   * @brief Class which handles post order management
   * @catagory osCommerce Checkout by Amazon Payment Module
   * @author Joshua Wong
   * @copyright Portions copyright 2007-2009 Amazon Technologies, Inc
   * @copyright Portions copyright osCommerce, 2002-2008
   * @license GPL v2, please see LICENSE.txt
   * @access public
   * @version $Id: $
   */
  /*
      This program is free software; you can redistribute it and/or modify
      it under the terms of the GNU General Public License as published by
      the Free Software Foundation; either version 2 of the License, or
      (at your option) any later version.
                                                                                                                                                             
      This program is distributed in the hope that it will be useful,
      but WITHOUT ANY WARRANTY; without even the implied warranty of
      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
      GNU General Public License for more details.
                                                                                                                                                             
      You should have received a copy of the GNU General Public License
      along with this program; if not, write to the Free Software
      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
  */

define('DOCUMENT_PROCESSING_STATUS_COMPLETE', '_DONE_');
// times out after ten minutes
define('DOCUMENT_PROCESSING_STATUS_POLL_TIMEOUT', 600);
// poll every minute to see if the document has completed
define('DOCUMENT_PROCESSING_STATUS_POLL_INTERVAL', 60);

// libraries required to communicate in SOAP
// PHP PEAR libraries needed
require_once('SOAP/Client.php');      // PEAR SOAP client libraries
require_once('SOAP/Value.php');       // PEAR SOAP attachment libraries
require_once('XML/Unserializer.php'); // PEAR XML parser


/**
 * This MerchantAt API offers the following APIs:
 *
 * getAllPendingDocumentInfo
 * getDocument
 * postDocumentDownloadAck
 * postDocument
 *
 *
 * Copyright 2008-2008 Amazon.com, Inc., or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the “License”).
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 *    http://aws.amazon.com/apache2.0/
 *
 * or in the “license” file accompanying this file.
 * This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
 * either express or implied. See the License for the specific language governing permissions and limitations under the License.
 */
class AmazonMerchantAtSoapClient {
   var $client;
   var $merchant;
   var $proxy;

   /**
    * Instantiate an instance of the SOAP client.
    */
   function AmazonMerchantAtSoapClient($login, $password, $merchantid, $merchantname)
   {
      // Setup the SOAP merchant parmater used in all requests
      $this->merchant = array( 'merchantIdentifier' => $merchantid, 'merchantName' => $merchantname );
      $proxy = array('user' => $login, 'pass' => $password);

      // Parse the WSDL and get the client
      $this->client = new SOAP_Client(WSDLPATH, true, false, $proxy, false);
     
      if (PEAR::isError($this->client)) {
         $this->error = "Error: " . $this->client->getMessage();
         return false;
      }
   
      return true;
   }

   /**
    * Get all documents that have yet to have been acknowledged by the merchant.
    * Returns an array
    */
   function getAllPendingDocumentInfo($documentType) {
      $this->error = null;
  
      $params = array('merchant' => $this->merchant, 'messageType' => $documentType);
      $options = array('trace' => true, 'timeout' => '10');

      $result = $this->client->call('getAllPendingDocumentInfo', $params, $options);

      if(PEAR::isError($result)) {
         $this->error = "Error: " . $result->getMessage();

         return false;
      }

      return ($result);
   }

   /**
    * Get the actual document and transform it from a SOAP attachment
    * to a object.
    */
   function getDocument($documentID) {
      $this->error = null;
  
      $params = array('merchant' => $this->merchant, 'documentIdentifier' => $documentID);
      $options = array('trace' => true, 'timeout' => '10');

      $result = $this->client->call('getDocument', $params, $options);

      // Use mime decode to get the response body
      $params['include_bodies'] = true;
      $params['decode_bodies']  = true;
      $params['decode_headers'] = true;

      $decoder = new Mail_mimeDecode($this->client->xml);
      $structure = $decoder->decode($params);
      $xml = $decoder->_body;

      // remove the ending mime boundary from the body,
      // Unfortunately, this is not removed by mimeDecode.
      // i.e:
      //
      // --xxx-WASP-CPP-MIME-Boundary-xxx-0xa8f58f0-0a8f58f0-xxx-END-xxx--
      $boundaryIndex = strripos($xml, '--xxx-WASP-CPP-MIME-Boundary-xxx');

      if (!($boundaryIndex === false)) {
         $xml = substr($xml, 0, $boundaryIndex);
      }

      // convert XML to object for use
      $parser = new XML_Unserializer();
      $rc = $parser->unserialize($xml);

      return ($parser->getUnserializedData());
   }

   /**
    * Acknowledges that pending document has been downloaded,
    * and remove it from the pending list.
    *
    */
   function postDocumentDownloadAck($documentIDs) {
      $this->error = null;
  
      $params = array('merchant' => $this->merchant, 'documentIdentifierArray' => $documentIDs);
      $options = array('trace' => true, 'timeout' => '10');

      $result = $this->client->call('postDocumentDownloadAck', $params, $options);


      if(PEAR::isError($result)) {
         $this->error = "Error: " . $result->getMessage();

         return false;
      }

      return $result;
   }

   /**
    * post document, such as confirm order shipment or payment adjustments 
    * (a.k.a. refund).
    * Accepts a text-format representation of the document.
    *
    */
   function postDocument($messageType, $document) {
      $this->error = null;

      // create the attachment to add to the SOAP call
      $attachment = new SOAP_Attachment('doc', 'application/binary', null, $document);
      $attachment->options['attachment']['encoding'] = '8bit';

      $params = array('merchant' => $this->merchant,
                      'messageType' => $messageType,
                      'doc' => $attachment);

      $options = array('trace' => true, 'timeout' => '10', 'attachments' => 'Mime');

      $result = $this->client->call('postDocument', $params, $options);

      if(PEAR::isError($result)) {
         $this->error = "Error: " . $result->getMessage();
         return false;
      }

      return $result;
   }

   /**
    * Get the status of a transaction, such as postDocument.
    */
   function getDocumentProcessingStatus($transactionID) {
      $this->error = null;

      $params = array('merchant' => $this->merchant, 'documentTransactionIdentifier' => $transactionID);
      $options = array('trace' => true, 'timeout' => '10');

      $result = $this->client->call('getDocumentProcessingStatus', $params, $options);

      if(PEAR::isError($result)) {
         $this->error = "Error: " . $result->getMessage();

         return false;
      }

      return $result;
   }

   /**
    * See if a transaction, such as postDocument, is complete.
    */
   function waitForDocumentProcessingComplete($transactionID) {
      $processingTime = 0;
      $result = $this->getDocumentProcessingStatus($transactionID);

      while (DOCUMENT_PROCESSING_STATUS_COMPLETE != $result['documentProcessingStatus']) {
        if ($processingTime >= DOCUMENT_PROCESSING_STATUS_POLL_TIMEOUT) {
            $this->error = "Error: request timed out in " . DOCUMENT_PROCESSING_STATUS_POLL_TIMEOUT . " seconds.";
            return;
        }

        $processingTime += DOCUMENT_PROCESSING_STATUS_POLL_INTERVAL;
        sleep(DOCUMENT_PROCESSING_STATUS_POLL_INTERVAL);

        $result = $this->getDocumentProcessingStatus($transactionID);
      }

      return $result;
   }
}
?>
