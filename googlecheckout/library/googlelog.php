<?php
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
 * Google Checkout v1.5.0
 * $Id: googlelog.php 153 2009-01-30 00:16:37Z ed.davisson $
 */

  // Log levels
  define("L_OFF", 0); // No log
  define("L_ERR", 1); // Log Errors
  define("L_RQST", 2); // Log Request from GC
  define("L_RESP", 4); // Log Resoponse To Google
  define("L_ERR_RQST", L_ERR | L_RQST);
  define("L_ALL", L_ERR | L_RQST | L_RESP);

class GoogleLog {

    var $errorLogFile;
    var $messageLogFile;
 // L_ALL (err+requests+responses), L_ERR, L_RQST, L_RESP, L_OFF
    var $logLevel = L_ERR_RQST;

  /**
   * SetLogFiles
   */
  function GoogleLog($errorLogFile, $messageLogFile, $logLevel=L_ERR_RQST, $die=true){
    $this->logLevel = $logLevel;
    if($logLevel == L_OFF) {
      $this->logLevel = L_OFF;
    } else {
      if (!$this->errorLogFile = @fopen($errorLogFile, "a")) {
        header('HTTP/1.0 500 Internal Server Error');
        $log = "Cannot open " . $errorLogFile . " file.\n" .
                    "Logs are not writable, set them to 777";
        error_log($log, 0);
        if($die) {
          die($log);
        }else {
          echo $log;
          $this->logLevel = L_OFF;
        }
      }
      if (!$this->messageLogFile = @fopen($messageLogFile, "a")) {
        fclose($this->errorLogFile);
        header('HTTP/1.0 500 Internal Server Error');
        $log = "Cannot open " . $messageLogFile . " file.\n" .
                    "Logs are not writable, set them to 777";
        error_log($log, 0);
        if($die) {
          die($log);
        }else {
          echo $log;
          $this->logLevel = L_OFF;
        }
      }
    }
    $this->logLevel = $logLevel;;
  }

  function LogError($log){
    if($this->logLevel & L_ERR){
      fwrite($this->errorLogFile,
      sprintf("\n%s:- %s\n",date("D M j G:i:s T Y"),$log));
      return true;
    }
    return false;
  }

  function LogRequest($log){
    if($this->logLevel & L_RQST){
      fwrite($this->messageLogFile,
       sprintf("\n%s:- %s\n",date("D M j G:i:s T Y"),$log));
       return true;
    }
    return false;
  }

  function LogResponse($log) {
    if($this->logLevel & L_RESP){
      $this->LogRequest($log);
      return true;
    }
    return false;
  }
}
?>
