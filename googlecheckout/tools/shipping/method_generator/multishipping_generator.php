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
 * $Id: multishipping_generator.php 153 2009-01-30 00:16:37Z ed.davisson $
 */
 
?>
  <form action="multigenerator.php" method="post" target="frame">
    <table width="100%" align="center" id="methdos">
<?php
if(is_array(@$mc_shipping_methods))
foreach($mc_shipping_methods as $shippername => $shippermethods){
?>

    <tr><td>
      <table border="1" cellpadding="2" cellspacing="0" align="center">
        <tr bgcolor="#F0F0F0">
          <th align="left">Shipping Code:</th>
          <td>
            <input type="text" name="code[<?=$shippername;?>]" size="50" value="<?=$shippername;?>" id="code"/><a onclick="show_help(0);" onmouseover="this.style.cursor='help'"><big>&nbsp;&nbsp;?&nbsp;&nbsp;</big></a>
          </td>
        </tr>
        <tr>
          <th align="left">Shipping Fancy Name:</th>
          <td>
            <input type="text" name="name[<?=$shippername;?>]" size="50" value="<?=$mc_shipping_methods_names[$shippername];?>" id="name"/><a onclick="show_help(1);" onmouseover="this.style.cursor='help'"><big>&nbsp;&nbsp;?&nbsp;&nbsp;</big></a>
          </td>
        </tr>
        <!-- demestic types -->
        <tr>
          <td colspan="2">
            <table>
              <tbody id="d_table<?=$shippername;?>">
                <tr>
                  <th align="left" colspan="3">Domestic Types: <small>(leave LAST empty to ignore)</small></th>
                </tr>
                <tr>
                  <td>
                    Method Code:<a onclick="show_help(2);" onmouseover="this.style.cursor='help'"><big>&nbsp;&nbsp;?&nbsp;&nbsp;</big></a>
                  </td>
                  <td>
                    Method Fancy Name:<a onclick="show_help(3);" size="50" onmouseover="this.style.cursor='help'"><big>&nbsp;&nbsp;?&nbsp;&nbsp;</big></a>
                  </td>
                  <td align="center">
                    Recomended<br /> Default Value:<a onclick="show_help(5);" size="50" onmouseover="this.style.cursor='help'"><big>&nbsp;&nbsp;?&nbsp;&nbsp;</big></a>
                  </td>
                  <td align="center">
                    Action
                  </td>
                </tr>
<?php
        if(is_array(@$shippermethods['domestic_types']))
        foreach($shippermethods['domestic_types'] as $methodcode => $methodname){
?>
                <tr id="d_tr">
                  <td>
                    <input type="text" name="d_m_code[<?=$shippername;?>][]" value="<?=htmlentities($methodcode);?>" id="d_m_code"/>
                  </td>
                  <td>
                    <input type="text" name="d_m_name[<?=$shippername;?>][]" value="<?=htmlentities($methodname['title']);?>" size="50" id="d_m_name"/>
                  </td>
                  <td>
                    <?php echo DEFAULT_CURRENCY . " " . $methodname['cost'];?>
                  </td>
                  <td>
                    <input type="button" value="+" style="display:none" onclick="add_sibling('d_table<?=$shippername;?>', 'd_tr<?=$shippername;?>');this.style.display='none';this.nextSibling.nextSibling.style.display='block';"/>
                    <input type="button" value="-" onclick="this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);"/>
                  </td>
                </tr>
<?php
        }
?>
                <tr id="d_tr<?=$shippername;?>">
                  <td>
                    <input type="text" name="d_m_code[<?=$shippername;?>][]" value="" id="d_m_code"/>
                  </td>
                  <td>
                    <input type="text" name="d_m_name[<?=$shippername;?>][]" value="" size="50" id="d_m_name"/>
                  </td>
                  <td>
                    <input type="button" value="+" onclick="add_sibling('d_table<?=$shippername;?>', 'd_tr<?=$shippername;?>');this.style.display='none';this.nextSibling.nextSibling.style.display='block';"/>
                    <input type="button" value="-" style="display:none" onclick="this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);"/>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
        <!-- int'l types -->
        <tr>
          <td colspan="2">
            <table>
              <tbody id="i_table<?=$shippername;?>">
                <tr>
                  <th align="left" colspan="3">International Types: <small>(leave LAST empty to ignore)</small></th>
                </tr>
                <tr>
                  <td>
                    Method Code:<a onclick="show_help(2);" onmouseover="this.style.cursor='help'"><big>&nbsp;&nbsp;?&nbsp;&nbsp;</big></a>
                  </td>
                  <td>
                    Method Fancy Name:<a onclick="show_help(3);" size="50" onmouseover="this.style.cursor='help'"><big>&nbsp;&nbsp;?&nbsp;&nbsp;</big></a>
                  </td>
                  <td align="center">
                    Recomended<br /> Default Value:<a onclick="show_help(5);" size="50" onmouseover="this.style.cursor='help'"><big>&nbsp;&nbsp;?&nbsp;&nbsp;</big></a>
                  </td>
                  <td align="center">
                    Action
                  </td>
                </tr>
<?php
        if(is_array(@$shippermethods['international_types']))
        foreach($shippermethods['international_types'] as $methodcode => $methodname){
?>
                <tr id="i_tr">
                  <td>
                    <input type="text" name="i_m_code[<?=$shippername;?>][]" value="<?=htmlentities($methodcode);?>" id="i_m_code"/>
                  </td>
                  <td>
                    <input type="text" name="i_m_name[<?=$shippername;?>][]" size="50"   value="<?=htmlentities($methodname['title']);?>" id="i_m_name"/>
                  </td>
                  <td>
                    <?php echo DEFAULT_CURRENCY . " " . $methodname['cost'];?>
                  </td>
                  <td>
                    <input type="button" value="+" style="display:none" onclick="add_sibling('i_table<?=$shippername;?>', 'i_tr<?=$shippername;?>');this.style.display='none';this.nextSibling.nextSibling.style.display='block';"/>
                    <input type="button" value="-" onclick="this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);"/>
                  </td>
                </tr>
<?php
        }
?>
                <tr id="i_tr<?=$shippername;?>">
                  <td>
                    <input type="text" name="i_m_code[<?=$shippername;?>][]" value="" id="i_m_code"/>
                  </td>
                  <td>
                    <input type="text" name="i_m_name[<?=$shippername;?>][]" size="50" value="" id="i_m_name"/>
                  </td>
                  <td>
                    <input type="button" value="+" onclick="add_sibling('i_table<?=$shippername;?>', 'i_tr<?=$shippername;?>');this.style.display='none';this.nextSibling.nextSibling.style.display='block';"/>
                    <input type="button" value="-" style="display:none" onclick="this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);"/>
                  </td>
                </tr>
              </tbody>
            </table>
        </tr>
      </table>
      </td></tr>

<?php
}
?>

      <tr><td>
      <table align="center">
        <tr>
          <th colspan="3">
            <input type="submit" name="Generate" value="Generate" onclick="document.getElementById('frame').focus();"/>
          </th>
        </tr>
      </table>
      </td></tr>
      <tr><td>
      <div align="center"><iframe name="frame" id="frame" style="width:95%; height:600" rows="20"></iframe></div>
      </td></tr>
    </table>
  </form>

<div id="help" style="display:none; position:absolute; top:10px; right:10px">
  <table width="200" border="1" cellpadding="2" cellspacing="0">
    <tr>
      <td bgcolor="#F0F0F0" align="right"><b>Help</b>&nbsp;&nbsp;<a style="align:right" href="javascript:document.getElementById('help').style.display='none';void(0);">[x]</a></td>
    </tr>
    <tr>
      <td colspan="2" id="help_text"></td>
    </tr>
  </table>
</div>
</body>
</html>
