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
 * An interface for option classes. Inheritance is a PHP5 option, so we
 * may not want to use it explicitly, but this can still serve as a guide
 * to creating new options.
 * 
 * TODO(eddavisson): Investigate PHP5. Maybe we can use it...
 * 
 * @author Ed Davisson (ed.davisson@gmail.com)
 */
class GoogleOptionInterface {
  
	function getOptionType() {}

  function getKey() {}
  
  function getTitle() {}
  
  function getDescription() {}
  
  function getValue() {}
  
  function setValue($value) {}
  
  function getHtml() {}

}

?>
