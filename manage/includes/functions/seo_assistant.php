<?php
/*
  SEO_Assistant for OSC 2.2 2.0 v2.0  08.03.2004
  Originally Created by: Jack York
  GNU General Public License Compatible
  CartStore eCommerce Software, for The Next Generation
  Copyright (c) 2008 Adoovo Inc. USA
*/
	define('GMAG', 0xE6359A60);

//unsigned shift right
function zeroFill($a, $b)
{
    $z = hexdec(80000000);
        if ($z & $a)
        {
            $a = ($a>>1);
            $a &= (~$z);
            $a |= 0x40000000;
            $a = ($a>>($b-1));
        }
        else
        {
            $a = ($a>>$b);
        }
        return $a;
}


function mix($a,$b,$c) {
  $a -= $b; $a -= $c; $a ^= (zeroFill($c,13));
  $b -= $c; $b -= $a; $b ^= ($a<<8);
  $c -= $a; $c -= $b; $c ^= (zeroFill($b,13));
  $a -= $b; $a -= $c; $a ^= (zeroFill($c,12));
  $b -= $c; $b -= $a; $b ^= ($a<<16);
  $c -= $a; $c -= $b; $c ^= (zeroFill($b,5));
  $a -= $b; $a -= $c; $a ^= (zeroFill($c,3));
  $b -= $c; $b -= $a; $b ^= ($a<<10);
  $c -= $a; $c -= $b; $c ^= (zeroFill($b,15));

  return array($a,$b,$c);
}

function GCH($url, $length=null, $init=GMAG) {
    if(is_null($length)) {
        $length = sizeof($url);
    }
    $a = $b = 0x9E3779B9;
    $c = $init;
    $k = 0;
    $len = $length;
    while($len >= 12) {
        $a += ($url[$k+0] +($url[$k+1]<<8) +($url[$k+2]<<16) +($url[$k+3]<<24));
        $b += ($url[$k+4] +($url[$k+5]<<8) +($url[$k+6]<<16) +($url[$k+7]<<24));
        $c += ($url[$k+8] +($url[$k+9]<<8) +($url[$k+10]<<16)+($url[$k+11]<<24));
        $mix = mix($a,$b,$c);
        $a = $mix[0]; $b = $mix[1]; $c = $mix[2];
        $k += 12;
        $len -= 12;
    }

    $c += $length;
    switch($len)              /* all the case statements fall through */
    {
        case 11: $c+=($url[$k+10]<<24);
        case 10: $c+=($url[$k+9]<<16);
        case 9 : $c+=($url[$k+8]<<8);
          /* the first byte of c is reserved for the length */
        case 8 : $b+=($url[$k+7]<<24);
        case 7 : $b+=($url[$k+6]<<16);
        case 6 : $b+=($url[$k+5]<<8);
        case 5 : $b+=($url[$k+4]);
        case 4 : $a+=($url[$k+3]<<24);
        case 3 : $a+=($url[$k+2]<<16);
        case 2 : $a+=($url[$k+1]<<8);
        case 1 : $a+=($url[$k+0]);
         /* case 0: nothing left to add */
    }
    $mix = mix($a,$b,$c);
    /*-------------------------------------------- report the result */
    return $mix[2];
}

//converts a string into an array of integers containing the numeric value of the char
function strord($string) {
    for($i=0;$i<strlen($string);$i++) {
        $result[$i] = ord($string{$i});
    }
    return $result;
}

function getPR($_url) {
    $url = 'info:'.$_url;
    $ch = GCH(strord($url));
    $url='info:'.urlencode($_url);
    $pr = file("http://www.google.com/search?client=navclient-auto&ch=6$ch&ie=UTF-8&oe=UTF-8&features=Rank&q=$url");
    $pr_str = implode("", $pr);
    return substr($pr_str,strrpos($pr_str, ":")+1);
}

function getLinkPopularity($link_url) {
 $host = "www.google.com";
 $path = "/search?hl=en&ie=UTF-8&oe=UTF-8&q=link:" . $link_url;
 $fp = fsockopen($host, "80", $errno, $errstr);
 if (! $fp) {
  echo "$errstr ($errno)<br />\n";
	return false;
 } else {
  fputs($fp, "GET ".$path." HTTP/1.0\r\nHost: ".$host."\r\n\r\n");
  while(!feof($fp)) {
   $line = fgets($fp, 4096);
   if (preg_match("/of about/", $line)) {
    $total_sites = $line;
    $total_sites = preg_replace("/^.*of about <b>/", "", $total_sites);
    $total_sites = preg_replace("/<.*$/", "", $total_sites);
    $total_sites = preg_replace("/\,/", "", $total_sites);
    $total_sites = trim($total_sites);
		return $total_sites;
   }
  }
 }
}

//http://search.yahoo.com/search?p=link%3Ahttp%3A%2F%2Fwww.cre8asiteforums.com%2Findex.php&ei=UTF-8&fr=FP-tab-web-t&n=20&fl=0&x=wrt
function get_yahoo_links($domain) {
 $lines = array();
 $host = "search.yahoo.com";
 $path = "search?p=link%3Ahttp%3A%2F%2F" . $domain;
 $fp = fsockopen($host, "80");
 if ($fp) {
  fputs($fp, "GET ".$path." HTTP/1.0\r\nHost: ".$host."\r\n\r\n");
  while(!feof($fp)) {
   $line = fgets($fp, 4096);
   if (preg_match("/^1 \- /", $line)) {
    $total_sites = $line;
    $total_sites = preg_replace("/^.*of /", "", $total_sites);
    $total_sites = preg_replace("/ .*$/", "", $total_sites);
    $total_sites = preg_replace("/\,/", "", $total_sites);
    $total_sites = trim($total_sites);
    return($total_sites);
   }
  }
 } else {
  echo "Can't connect to host... ";
 }
}

  function ListFiles()
  {
     $files = array();
     $dir = opendir('.');
     while(($file = readdir($dir)) !== false)
     {
        if($file !== '.' && $file !== '..' && !is_dir($file))
        {
            $files[] = $file;
        }
     }
     closedir($dir);
     sort($files);
     return $files;
  }

  function checkLinks($url, $idx)
  {
    global $badLinks, $totalLinks;
    $file = @fopen($url,'r');

    if (! $file)
    {
       $badLinks[$idx] = $url;
       // echo 'add bad link MAIN '. $url . ' at pos '. $idx . ' result = ' .$badLinks[$idx].'<br>';
       $idx++;
       $totalLinks++;
    }
    else
    {
       $totalLinks++;

       while (!feof($file))
       {
          $page_line = trim(fgets($file, 4096));

          if (preg_match('/http:/i', $page_line))
          {
            $link = stristr($page_line, 'http:');
            if ($link !== FALSE)
            {
               $pos = strpos($link, '"');
               if ($pos !== FALSE)
                  $link = substr($link, 0, $pos);

               $pos = strpos($link, '?osCsid');
               if ($pos !== FALSE)
                  $link = substr($link, 0, $pos);
               else
               {
                  $pos = strpos($link, '&osCsid');
                  if ($pos !== FALSE)
                     $link = substr($link, 0, $pos);
               }

               $actual_link = @fopen($link,'r');
               $totalLinks++;

               if (! $actual_link)
               {
                  $badLinks[$idx] = $link;
                 // echo 'add bad link SUB '. $link . ' at pos '. $idx . ' result = ' .$badLinks[$idx].'<br>';
                  $idx++;
               }
               else
               {
                   fclose($actual_link);
               }
            }
          }
       }
       fclose($file);
    }
  }
?>

