<?php
/*
  $Id: categories.php,v 1.23 2002/11/12 14:09:30 dgw_ Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA  Copyright (c) 2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>

  GNU General Public License Compatible

  Ti use this box call rss.php?box=categories
*/



  $categories_string = '';

  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id ."' order by sort_order, cd.categories_name");
while ($categories = tep_db_fetch_array($categories_query)) 
{

  $foo[$categories['categories_id']] = array(
					     'name' => $categories['categories_name'],
					     'parent' => $categories['parent_id'],
					     'level' => 0,
					     'path' => $categories['categories_id'],
					     'next_id' => false
					     );

  if (isset($prev_id))
    {
      $foo[$prev_id]['next_id'] = $categories['categories_id'];
    }
  
  $prev_id = $categories['categories_id'];
  
  if (!isset($first_element)) {
    $first_element = $categories['categories_id'];
  }

//
// Print
//
//header("Content-type: text/plain");

//require('includes/application_top.php');

print '<?xml version="1.0" encoding="iso-8859-1"?>' . "\n";
print '<rss version="0.91">' . "\n";
print "<channel>\n";
print "<source>" . '<br><a href="http://www.yourdomain.com/includes/modules/newsdesk/rss/newsdesk_latest.php">RSS Newsfeeds</a><br>' . "</source>\n";
print "<item>\n";
print "<title>" . $categories['categories_name'] ."</title>\n";
print "<link>" . '<br><a href="' . tep_href_link(FILENAME_DEFAULT, "cPath=".$categories['categories_id'])  . '">' . $categories['categories_name'] . '<a/><br>' . "</link>\n";

  print "</item>\n\n";

}

//	$row++;
//}
print "  </channel>\n";
print "</rss>\n";


?>