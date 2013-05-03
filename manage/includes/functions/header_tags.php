<?php
/*
  $Id: header_tags_controller.php,v 1.0 2005/04/08 22:50:52 hpdl Exp $
  Originally Created by: Jack York - http://www.CartStore.com
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/
function ChangeSwitch($line, $arg)
{
  if (isset($arg))
   $line = str_replace("0", "1", $line);
  else  
   $line = str_replace("1", "0", $line);
   
  return $line; 
}

//returns true if line is a comment
function IsComment($line)
{   
  return ((strpos($line, "//") === 0) ? true : false);
}

function IsTitleSwitch($line)
{   
  if (strpos($line, "define('HTTA") === 0 && strpos($line, "define('HTTA_CAT") === FALSE)
    return true;
  else
    return false; 
}

function IsDescriptionSwitch($line)
{   
  return ((strpos($line, "define('HTDA") === 0) ? true : false);
}

function IsKeywordSwitch($line)
{   
  return ((strpos($line, "define('HTKA") === 0) ? true : false); 
}

function IsCatSwitch($line)
{   
  return ((strpos($line, "define('HTTA_CAT") === 0) ? true : false); 
}

function IsTitleTag($line)
{   
  return ((strpos($line, "define('HEAD_TITLE_TAG") === 0) ? true : false);  
}

function IsDescriptionTag($line)
{   
  return ((strpos($line, "define('HEAD_DESC_TAG") === 0) ? true : false);
}

function IsKeywordTag($line)
{   
  return ((strpos($line, "define('HEAD_KEY_TAG") === 0) ? true : false);
}

function FileNotUsingHeaderTags($file)
{
  $file = DIR_FS_CATALOG.$file;
  $fp = file($file);
  for ($i = 0; $i < count($fp); ++$i)
  {
      if (strpos($fp[$i], "Header Tags Controller") !== FALSE)
        return false;
  }
  return true;
}

function GetArgument(&$line, $arg_new, $formActive)
{
  $arg = explode("'", $line);
  
  if ($formActive)
  {
    $line = ReplaceArg($line, $arg_new);
  }
  else
  {             
    for ($i = 4; $i < count($arg); ++$i)
    {
       if (strpos($arg[$i], ");") === FALSE)
         $arg[3] .= $arg[$i];
    }             
  
    $arg[3] = str_replace("\\", "'", $arg[3]);
  }
 
  return $arg[3];
}

function GetColor($title)
{
  if (($fp = @file(DIR_FS_CATALOG . '/' . $title . '.php')) == FALSE) //file doesn't exist
    return "red";
   
  for ($idx = 0; $idx < count($fp); ++$idx)
  {
    if (strpos($fp[$idx], "require(DIR_WS_INCLUDES . 'header_tags.php')") !== FALSE)
    {
       return "black";
    }
  }  
   
 return "red";
}
function GetMainArgument(&$line, $arg2, $formActive)
{
  if (! $formActive)                           //update button was not clicked
  {                                            //just got here by a page load
    $arg = '';
    $def = explode("'", $line);
    for ($i = 3; $i < count($def); ++$i)       //so get text from file and retun it
    {
      if (strpos($def[$i], ");") === FALSE)
      {
        $arg .= str_replace("\\", "'", $def[$i]); //stripslashes won't always work due to 
      }                                           
    }
    return $arg; //stripslashes($arg);
  } 
  
  $storeArg = $arg2;                            //if this pointis reached, the update button was clicked
  $arg2 = addslashes($arg2);                    //so change the text
  
  if (($pos = strpos($line, "define('HEAD_TITLE_TAG_ALL'")) !== FALSE)
  {
    $pos .= strlen("define('HEAD_TITLE_TAG_ALL'") + 1;
  }
  else if (($pos = strpos($line, "define('HEAD_DESC_TAG_ALL'")) !== FALSE)
  {
    $pos .= strlen("define('HEAD_DESC_TAG_ALL'") + 1;
  }
  else if (($pos = strpos($line, "define('HEAD_KEY_TAG_ALL'")) !== FALSE)
  {
    $pos .= strlen("define('HEAD_KEY_TAG_ALL'") + 1;
  }

  $line = substr_replace($line, $arg2, $pos + 1) . "');\n";

  return $storeArg;  
}

function GetSectionName($line)
{
  $name = explode(" ", $line);
  $name[1] = trim($name[1]);
  $pos = strpos($name[1], '.');
  return (substr($name[1], 0, $pos)); 
}

function GetSwitchSetting($line)
{
  return ((strpos($line, "'0'") === FALSE) ? 1 : 0);     
}

function NotDuplicatePage($fp, $pagename)  //return false if the name entered is already present
{
  for ($idx = 0; $idx < count($fp); ++$idx)   
  {
     $section = GetSectionName($fp[$idx]);
     if (! empty($section))
     {
        if (strcasecmp($section, $pagename) === 0)
          return false;
     }     
  }
  return true;
}

function ReplaceArg($line, $arg)
{
  $parts = explode("'", $line);         //break apart the line   
  $parts[3] = $arg;                     //replace the argument  
  
  if (strpos($parts[3], "\\") === FALSE)
    $parts[3] = addslashes($parts[3]);  
   
  $parts = $parts[0] . "'" . $parts[1] . "'" . $parts[2] . "'" . $parts[3] . '\');' . "\n";
  return $parts; 
  return implode("'", $parts);          //put line back together
}

function TotalPages($filename)
{
  $ctr = 0;
  $findTitles = false;
  $fp = file($filename);  
      
  for ($idx = 0; $idx < count($fp); ++$idx)
  { 
    $line=$fp[$idx];

    if (strpos($line, "define('HEAD_TITLE_TAG_ALL','") !== FALSE)
      continue;
    else if (strpos($line, "define('HEAD_DESC_TAG_ALL") !== FALSE)
      continue;
    else if (strpos($line, "define('HEAD_KEY_TAG_ALL") !== FALSE)
    {
      $findTitles = true;  //enable next section
      continue;
    } 
    else if ($findTitles)
    {
      if (($pos = strpos($fp[$idx], '.php')) !== FALSE)
        $ctr++; 
    }
  }  
  return $ctr;
}

function ValidPageName($pagename)  //return false if the page name has an invalid format
{
  if (strpos($pagename, " ") !== FALSE)
   return false;
  else if (strpos($pagename, "-") !== FALSE)
   return false;
  else if (strpos($pagename, "http") !== FALSE)
   return false; 
  else if (strpos($pagename, "\\") !== FALSE)
   return false; 
  else if (strpos($pagename, "'") !== FALSE)
   return false; 
     
  return true;  
}

function WriteHeaderTagsFile($filename, $fp)
{
  if (!is_writable($filename)) 
  {
     if (!chmod($filename, 0666)) {
        echo "Cannot change the mode of file ($filename)";
        exit;
     }
  }
  $fpOut = fopen($filename, "w");
 
  if (!fpOut)
  {
     echo 'Failed to open file '.$filename;
     exit;
  }
       
  for ($idx = 0; $idx < count($fp); ++$idx)
    if (fwrite($fpOut, $fp[$idx]) === FALSE)
    {
       echo "Cannot write to file ($filename)";
       exit;
    } 
  fclose($fpOut);   
}
?>