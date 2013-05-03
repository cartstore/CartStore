<?php
/*
  $Id: version checker, v 1.0 2009/12/01 
  Originally Created by: Jack_mcs - http://www.oscommerce-solution.com
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Portions Copyright 2009 oscommerce-solution.com

  Released under the GNU General Public License
*/

function AnnounceVersion($contribPath, $currentVersion, $contribName)
{
  $lines = array();
  $lines = GetFileArray($contribPath);
  
  if (tep_not_null($lines))
  {
    $result = CheckContributionVersion($lines, $currentVersion, $contribName);
    switch ($result)
    {
      case 0: return sprintf(TEXT_VERSION_LATEST, $currentVersion); break;
      case ERROR_NO_MATCH_FOUND: return TEXT_VERSION_NO_MATCH_FOUND; break;
      default: if ($result > 0) {
       $plural = ($result > 1) ? sprintf(TEXT_ARE, $result) : sprintf(TEXT_IS, $result);
       return '<a style="font-weight: bold; color: red;" target="_blank" href="' . $contribPath . '">' . sprintf(TEXT_VERSION_FOUND_UPDATE, $plural) . '</a>'; 
       break;
      } 
    }
  }
  return '';
}
   
function CheckContributionVersion($lines, $currentVersion, $contribName)
{
  $skip = true;
  $versionFound = 0; 
  
  foreach ($lines as $line)
  {
    /************ Quickly skip up to the version information *************/
    if ($skip && strpos($line, 'Expand All') === FALSE)
      continue;
    
    /************ Found it so stop skipping *************/
    if (strpos($line, 'Expand All') !== FALSE)
      $skip = false;
    
    /************ Only look at version strings *************/
    $line = trim(strip_tags($line));  
    if (strpos($line, $contribName) !== FALSE)  
    {
       if ($line === $currentVersion)
         return $versionFound;
       else 
         $versionFound++;
    }  
  }
  return ERROR_NO_MATCH_FOUND;
}
   
if (! function_exists('GetFileArray'))
{   
  function GetFileArray($path)
  {
    $lines = array();
    
    if (function_exists('curl_init')) 
    {
       $ch = curl_init();
       $timeout = 5; // set to zero for no timeout
       curl_setopt ($ch, CURLOPT_URL, $path);
       curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
       $file_contents = curl_exec($ch);
       curl_close($ch);
       $lines = explode("\n", $file_contents);
    }
    else
       $lines = @file($path); 
  
    return $lines;  
  }
}
?>