<?php
/*
  $Id: links_check.php,v 1.00 2006/6/06

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/
function GetLinksFileArray($path) //use curl if possible to read in site information
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
  {
    $fd = fopen ($path, "r");
    while (!feof ($fd))
    {
      $buffer = fgets($fd, 4096);
      $lines[] = $buffer;
    }
    fclose ($fd);
  }
  return $lines;
}

function CheckSiteData($lines)
{
  global $check_phase;
  $found = 0;

  $phases = explode(",", $check_phase);

  foreach ($lines as $line)
  {
    $page_line = trim($line);

    for ($i = 0; $i < count($phases); ++$i)
    {
      if (@preg_match("/".$phases[$i]."/", $page_line))
      {
        $found = 1;
        break;
      }
    }
    if ($found)
      break;
  }
  return $found;
}

function CheckURL($url, $links_id)
{
  $found = 0;
  $lines = GetLinksFileArray($url);

  if (sizeof($lines) > 0)
  {
    $found = CheckSiteData($lines);

    if ($found == true)
    {
      $links_check_query = mysql_query("select links_id, date_last_checked, link_found from links_check where links_id = " . (int)$links_id ) or die(mysql_error());
      if (mysql_num_rows($links_check_query) > 0)
        mysql_query("UPDATE links_check SET link_found = '" . (int)$found  . "', date_last_checked = now() where links_id = '" . (int)$links_id  . "'")  or die(mysql_error());
      else
        mysql_query("INSERT INTO links_check (links_id, link_found, date_last_checked) VALUES ('" . (int)$links_id . "', '" . (int)$found . "',  now()) ") or die(mysql_error());
    }
  }
  return $found;
}

  require('includes/configure.php');

  $link = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD) or die("Could not connect");
  mysql_select_db(DB_DATABASE);

  // Get the Site email address
  $configuration_query = mysql_query("select configuration_key, configuration_value from configuration where configuration_key = 'STORE_OWNER_EMAIL_ADDRESS' limit 1 ") or die(mysql_error());
  $configuration = mysql_fetch_array($configuration_query, MYSQL_ASSOC);
  $site_email = $configuration['configuration_value'];

  // Get the Check Phase(s)
  $configuration_query = mysql_query("select configuration_key, configuration_value from configuration where configuration_key = 'LINKS_CHECK_PHRASE' limit 1 ") or die(mysql_error());
  $configuration = mysql_fetch_array($configuration_query, MYSQL_ASSOC);
  $check_phase = $configuration['configuration_value'];

  // Get the Check Count
  $configuration_query = mysql_query("select configuration_key, configuration_value from configuration where configuration_key = 'LINKS_RECIPROCAL_CHECK_COUNT' limit 1 ") or die(mysql_error());
  $configuration = mysql_fetch_array($configuration_query, MYSQL_ASSOC);
  $check_count = explode(",", $configuration['configuration_value']);

  // Get the links to check
  $links_query = mysql_query("select links_id, links_reciprocal_url, links_reciprocal_check_count, links_status from links where links_status <> 3 and links_reciprocal_disable = 0") or die(mysql_error());

  $sleepCnt = 50;        //sleep after this many url's have been checked
  $sleepDuration = 600;  //how long it will sleep in seconds
  $ctr = 1;

  while ($links = mysql_fetch_array($links_query, MYSQL_ASSOC))
  {
     if (! CheckURL( $links['links_reciprocal_url'],  $links['links_id']))
     {
       if ($check_count[0] > $links['links_reciprocal_check_count']) //don't disable link yet
       {
 // echo 'count '.$check_count[0]. ' - '.$links['links_reciprocal_check_count'];
         mysql_query("UPDATE links SET links_reciprocal_check_count = '" . ($links['links_reciprocal_check_count'] + 1) . "' where links_id = '" . (int)$links['links_id']  . "'")  or die(mysql_error());
       }
       else
       {
          mysql_query("UPDATE links SET links_status = 3 where links_id = '" . (int)$links['links_id']  . "'")  or die(mysql_error());

          $links_check_query = mysql_query("select links_id, date_last_checked, link_found from links_check where links_id = " . (int)$links['links_id']) or die(mysql_error());
          if (mysql_num_rows($links_check_query) > 0)
            mysql_query("UPDATE links_check SET link_found = 0, date_last_checked = now() where links_id = '" . (int)$links['links_id']  . "'")  or die(mysql_error());
          else
            mysql_query("INSERT INTO links_check (links_id, link_found, date_last_checked) VALUES ('" . (int)$links['links_id'] . "', '0',  now()) ") or die(mysql_error());

          $contact_query = mysql_query("select links_contact_name, links_contact_email from links where links_id = '" . (int)$links['links_id'] . "'") or die(mysql_error());
          $contact = mysql_fetch_array($contact_query, MYSQL_ASSOC);

          $to      = $contact['links_contact_email'];
          $subject = 'Link stauts change';
          $message = 'Hello ' . $contact['links_contact_name'] . ',' . "\r\n\r\n" . 'We cannot find our link on the page you provided:' . "\r\n" . $links['links_reciprocal_url'] .
              "\r\n\r\n" . 'Please add our link to that page or send us an updated link to prevent your link from being deleted from our site. Your link status as been changed to Disabled utill this matter is resolved.' .
              "\r\n\r\n" . 'Please feel free to contact us at ' . $site_email . ' if you have any questions.' . "\r\n\r\n" . 'Thank you.';
          $headers = 'From: ' . $site_email . "\r\n" .
              'Reply-To: ' . $site_email . "\r\n" .
              'X-Mailer: LinksManager auto checker';

          mail($to, $subject, $message, $headers);
       }
     }

     if (($ctr % $sleepCnt) == 0)
     {
       sleep($sleepDuration);
     }
     $ctr++;
  }
  mysql_close($link);
?>
