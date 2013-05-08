<?php
/*=======================================================================*\
|| #################### //-- SCRIPT INFO --// ########################### ||
|| #	Script name: meta_tags.php                                      # ||
|| #	Contribution: cDynamic Meta Tags                                # ||
|| #	Version: 1.3                                                    # ||
|| #	Date: April 15 2005                                             # ||
|| # ------------------------------------------------------------------ # ||
|| #################### //-- COPYRIGHT INFO --// ######################## ||
|| #	Copyright (C) 2005 Chris LaRocque								# ||
|| #																	# ||
|| #	This script is free software; you can redistribute it and/or	# ||
|| #	modify it under the terms of the GNU General Public License		# ||
|| #	as published by the Free Software Foundation; either version 2	# ||
|| #	of the License, or (at your option) any later version.			# ||
|| #																	# ||
|| #	This script is distributed in the hope that it will be useful,	# ||
|| #	but WITHOUT ANY WARRANTY; without even the implied warranty of	# ||
|| #	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the	# ||
|| #	GNU General Public License for more details.					# ||
|| #																	# ||
|| #	Script is intended to be used with:								# ||
|| #	CartStore eCommerce Software, for The Next Generation					# ||
|| #	http://www.cartstore.com										# ||
|| #	Copyright (c) 2008 Adoovo Inc. USA									# ||
|| ###################################################################### ||
\*========================================================================*/

############################################################################
# USER SETTINGS                          								   #
#--------------------------------------------------------------------------#    
# If you want to cache the results (reduce server load and boost speed)
$cache_type = 0; # number only, no ' or "
#  0 (zero) = off
#  1 = Use Chemo's osC Advanced Cache Class **REQUIRES v1.1 or higher**
#  2 = Cache to directory ** MUST specify $meta_cache_path **

# If using Cache Class set lifetime of cache:
$expires = '30/days'; # only valid if $cache_type = 1

# only valid if $cache_type = 2
# must have read-write permissions
# specified in admin/includes/reset/meta_tags.php (if you changed it) MUST HAVE READ/WRITE CHMOD to 777
$meta_cache_files_path = DIR_FS_CATALOG.'cache/'; #this should work for most


# Show Model in Title
$show_model_in_title = false;  # no ' or "
#  true or false

# Show Manufacturer in Title
$show_man_in_title = true;  # no ' or "
#  true or false

# Enter y=text to be removed from Manufacturers when using them for keywords
# enter in all lower case
$strip_man_array = array('inc.','co.','inc'); 

# Pages to use HEADING_TITLE for title
# Do not list pages w/ specific meta tags: (index.php, product_info.php, specials.php, products_new.php)
$heading_pages = array('contact_us.php', 'product_reviews.php');

#---------------------------------------------------------------------------#
# Define specific meta tags by entering the value between the '':

# For all pages using meta_tags:
define('HEAD_TITLE_TAG_ALL',''); # Title
define('HEAD_DESC_TAG_ALL',''); # Description
define('HEAD_KEY_TAG_ALL',''); # Keywords

# For default index page (no products or categories)
define('HEAD_TITLE_TAG_INDEX',''); # Title
define('HEAD_DESC_TAG_INDEX',''); # Description
define('HEAD_KEY_TAG_INDEX',''); # Keywords
?>