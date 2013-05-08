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
 * Renders a single option.
 * 
 * TODO(eddavisson): Should this be handled by the options themselves?
 * 
 * @author Ed Davisson (ed.davisson@gmail.com)
 */
class GoogleOptionRenderer {
  
  /**
   * Constructor.
   */
  function GoogleOptionRenderer() {}
  
  /**
   * Returns the html for the option.
   */
  function render($option) {
    $html = '';
    $html .= '<tr><td>';
    $html .= '<span class="title">' . $option->getTitle() . '</span><br/>';
    $html .= '<span class="description">' . $option->getDescription() . '</span><br/>';
    $html .= '</td><td class="control">';
    $html .= $option->getHtml();
    $html .= '</td></tr>';
    
    return $html;
  }
}

?>
