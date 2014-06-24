<?php
/*
      QT Pro Version 4.1
  
      pad_multiple_dropdowns.php
  
      Contribution extension to:
        CartStore eCommerce Software, for The Next Generation
        http://www.cartstore.com
     
      Copyright (c) 2004, 2005 Ralph Day
      GNU General Public License Compatible
  
      Based on prior works GNU General Public License Compatible:
        QT Pro prior versions
          Ralph Day, October 2004
          Tom Wojcik aka TomThumb 2004/07/03 based on work by Michael Coffman aka coffman
          FREEZEHELL - 08/11/2003 freezehell@hotmail.com Copyright (c) 2003 IBWO
          Joseph Shain, January 2003
        CartStore 2.0
          Copyright (c) 2008 Adoovo Inc. USA
          
      Modifications made:
          11/2004 - Created
          12/2004 - Fix _draw_out_of_stock_message_js to add semicolon to end of js stock array
          03/2005 - Remove '&' for pass by reference from parameters to call of
                    _build_attributes_combinations.  Only needed on method definition and causes
                    error messages on some php versions/configurations
  
*******************************************************************************************
  
      QT Pro Product Attributes Display Plugin
  
      pad_multiple_dropdowns.php - Display stocked product attributes first as one dropdown for each attribute.
  
      Class Name: pad_multiple_dropdowns
  
      This class generates the HTML to display product attributes.  First, product attributes that
      stock is tracked for are displayed, each attribute in its own dropdown list.  Then attributes that
      stock is not tracked for are displayed, each attribute in its own dropdown list.
      
      Methods overidden or added:
  
        _draw_stocked_attributes            draw attributes that stock is tracked for
        _draw_out_of_stock_message_js       draw Javascript to display out of stock message for out of
                                            stock attribute combinations
*/
  require_once(DIR_WS_CLASSES . 'pad_base.php');

  class pad_multiple_dropdowns extends pad_base {


/*
    Method: _draw_stocked_attributes
  
    draw dropdown lists for attributes that stock is tracked for

  
    Parameters:
  
      none
  
    Returns:
  
      string:         HTML to display dropdown lists for attributes that stock is tracked for
  
*/
    function _draw_stocked_attributes() {
      global $languages_id;
      
      $out='';
      
      $attributes = $this->_build_attributes_array(true, false);
      if (sizeof($attributes)>0) {
        for($o=0; $o<sizeof($attributes); $o++) {
          $s=sizeof($attributes[$o]['ovals']);
          for ($a=0; $a<$s; $a++) {
            $attribute_stock_query = tep_db_query("select products_stock_quantity from " . TABLE_PRODUCTS_STOCK . " where products_id = '" . (int)$this->products_id . "' AND products_stock_attributes REGEXP '(^|,)" . (int)$attributes[$o]['oid'] . "-" . (int)$attributes[$o]['ovals'][$a]['id'] . "(,|$)' AND products_stock_quantity > 0");
            $out_of_stock=(tep_db_num_rows($attribute_stock_query)==0);
            if ($out_of_stock && ($this->show_out_of_stock == 'True')) {
              switch ($this->mark_out_of_stock) {
                case 'Left':   $attributes[$o]['ovals'][$a]['text']=TEXT_OUT_OF_STOCK.' - '.$attributes[$o]['ovals'][$a]['text'];
                               break;
                case 'Right':  $attributes[$o]['ovals'][$a]['text'].=' - '.TEXT_OUT_OF_STOCK;
                               break;
              }
            }
            elseif ($out_of_stock && ($this->show_out_of_stock != 'True')) {
              unset($attributes[$o]['ovals'][$a]);
            }
          }
          $out.='<tr><td align="right" class=main><b>'.$attributes[$o]['oname'].":</b></td><td class=main>".tep_draw_pull_down_menu('id['.$attributes[$o]['oid'].']',array_values($attributes[$o]['ovals']),$attributes[$o]['default'], "onchange=\"stkmsg(this.form);\"")."</td></tr>\n";
        }        
        $out.=$this->_draw_out_of_stock_message_js($attributes);
        
        return $out;
      }
    }


/*
    Method: _draw_out_of_stock_message_js
  
    draw Javascript to display out of stock message for out of stock attribute combinations

  
    Parameters:
  
      $attributes     array   Array of attributes for the product.  Format is as returned by
                              _build_attributes_array.
  
    Returns:
  
      string:         Javascript to display out of stock message for out of stock attribute combinations
  
*/
    function _draw_out_of_stock_message_js($attributes) {
      $out='';
      
      $out.="<tr><td>&nbsp</td><td><span id=\"oosmsg\" class=errorBox></span>\n";
  
      if (($this->out_of_stock_msgline == 'True' | $this->no_add_out_of_stock == 'True')) {
        $out.="<SCRIPT LANGUAGE=\"JavaScript\"><!--\n";
        $combinations = array();
        $selected_combination = 0;
        $this->_build_attributes_combinations($attributes, false, 'None', $combinations, $selected_combination);
        
        $out.="  function chkstk(frm) {\n";
      
        // build javascript array of in stock combinations
        $out.="    var stk=".$this->_draw_js_stock_array($combinations).";\n";
        $out.="    var instk=false;\n";
      
        // build javascript if statement to test level by level for existance  
        $out.='    ';
        for ($i=0; $i<sizeof($attributes); $i++) {
          $out.='if (stk';
          for ($j=0; $j<=$i; $j++) {
            $out.="[frm['id[".$attributes[$j]['oid']."]'].value]";
          }
          $out.=') ';
        }
        
        $out.="instk=true;\n";
        $out.="  return instk;\n";
        $out.="  }\n";

        if ($this->out_of_stock_msgline == 'True') {
          // set/reset out of stock message based on selection
          $out.="  function stkmsg(frm) {\n";
          $out.="    var instk=chkstk(frm);\n";
          $out.="    var span=document.getElementById(\"oosmsg\");\n";
          $out.="    while (span.childNodes[0])\n";
          $out.="      span.removeChild(span.childNodes[0]);\n";
          $out.="    if (!instk)\n";
          $out.="      span.appendChild(document.createTextNode(\"".TEXT_OUT_OF_STOCK_MESSAGE."\"));\n";
          $out.="    else\n";
          $out.="      span.appendChild(document.createTextNode(\" \"));\n";
          $out.="  }\n";
          //initialize out of stock message
          $out.="  stkmsg(document.cart_quantity);\n";
        }
      
        if ($this->no_add_out_of_stock == 'True') {
          // js to not allow add to cart if selection is out of stock
          $out.="  function chksel() {\n";
          $out.="    var instk=chkstk(document.cart_quantity);\n";
          $out.="    if (!instk) alert('".TEXT_OUT_OF_STOCK_MESSAGE."');\n";
          $out.="    return instk;\n";
          $out.="  }\n";
          $out.="  document.cart_quantity.onsubmit=chksel;\n";
        }
        $out.="//--></SCRIPT>\n";
      }
      $out.="</td></tr>\n";
      
      return $out;
    }

  }
?>
