<html>
<head>
	<style type="text/css">
		@import "scr.css";
	</style>

	<script type="text/javascript" src="domtab.js">
	</script>
	<script type="text/javascript">
		document.write('<style type="text/css">');
		document.write('div.domtab div{display: ;}<');
		document.write('/s'+'tyle>');
    </script>
</head>
<table cellpadding="0" cellspacing="0" width="70%" style="BORDER:none;background:none;">
      <tr align="left">
        <td>
<div <?php echo"id=\"mainnavtabbed".$i."\"";?> class="domtab">
  <ul class="domtabs">
     <li><a href="#t1<?php echo $i;?>"><?PHP ECHO TEXT_TAB_DESCRIPTION;?></a></li>
    <li><a href="#t2<?php echo $i;?>"><?PHP ECHO TEXT_TAB_SPEC;?></a></li>
    <li><a href="#t3<?php echo $i;?>"><?PHP ECHO TEXT_TAB_MUSTHAVE;?></a></li>
    <li><a href="#t4<?php echo $i;?>"><?PHP ECHO TEXT_TAB_EXTRAIMAGE;?></a></li>
    <li><a href="#t5<?php echo $i;?>"><?PHP ECHO TEXT_TAB_MANUAL;?></a></li>
	<li><a href="#t6<?php echo $i;?>"><?PHP ECHO TEXT_TAB_EXTRA1;?></a></li>
	<li><a href="#t7<?php echo $i;?>"><?PHP ECHO TEXT_TAB_MOREINFO;?></a></li>
  </ul>

   <div class="tabcontent">
    <a name="t1<?php echo $i;?>" id="t1<?php echo $i;?>"></a>
		<table border="0" cellspacing="0" cellpadding="1" width="100%">
              <tr align="left">
                <td valign="top" class="main"><?php echo tep_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '120', '15', (isset($products_description[$languages[$i]['id']]) ? $products_description[$languages[$i]['id']] : tep_get_products_description($pInfo->products_id, $languages[$i]['id']))); ?></td>
				<td><?php  echo TEXT_PRODUCTS_DESCRIPTION; ?><br>
				<?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
              </tr>
     </table>
   <a href="#top"></a>
  </div>

  <div class="tabcontent">
    <a name="t2<?php echo $i;?>" id="t2<?php echo $i;?>"></a>
		<table border="0" cellspacing="0" cellpadding="1" width="100%">
              <tr align="left">
                <td valign="top" class="main"><?php echo tep_draw_textarea_field('products_spec[' . $languages[$i]['id'] . ']', 'soft', '120', '15', (isset($products_spec[$languages[$i]['id']]) ? $products_spec[$languages[$i]['id']] : tep_get_products_spec($pInfo->products_id, $languages[$i]['id']))); ?></td>
				<td><?php  echo TEXT_PRODUCTS_SPEC; ?><br>
				<?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
              </tr>
    </table>
   <a href="#top"></a>
</div>

  <div class="tabcontent">
   <a name="t3<?php echo $i;?>" id="t3<?php echo $i;?>"></a>
		<table border="0" cellspacing="0" cellpadding="1" width="100%">
              <tr align="left">
                <td  valign="top" class="main"><?php echo tep_draw_textarea_field('products_musthave[' . $languages[$i]['id'] . ']', 'soft', '120', '15', (isset($products_musthave[$languages[$i]['id']]) ? $products_musthave[$languages[$i]['id']] : tep_get_products_musthave($pInfo->products_id, $languages[$i]['id']))); ?></td>
				<td ><?php  echo TEXT_PRODUCTS_MUSTHAVE; ?><br>
				<?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
              </tr>
    </table>
	<a href="#top"></a>
  </div>

    <div class="tabcontent">
   <a name="t4<?php echo $i;?>" id="t4<?php echo $i;?>"></a>
		<table border="0" cellspacing="0" cellpadding="1" width="100%">
              <tr align="left">
                <td  valign="top" class="main" width="0"><?php echo tep_draw_textarea_field('products_extraimage[' . $languages[$i]['id'] . ']', 'soft', '120', '15', (isset($products_extraimage[$languages[$i]['id']]) ? $products_extraimage[$languages[$i]['id']] : tep_get_products_extraimage($pInfo->products_id, $languages[$i]['id']))); ?></td>
				<td><?php  echo TEXT_PRODUCTS_EXTRAIMAGE; ?><br>
				<?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
              </tr>
    </table>
	<a href="#top"></a>
  </div>

  <div class="tabcontent">
   <a name="t5<?php echo $i;?>" id="t5<?php echo $i;?>"></a>
		<table border="0" cellspacing="0" cellpadding="1" width="100%">
              <tr align="left">
                <td  valign="top" class="main" width="0"><?php echo tep_draw_textarea_field('products_manual[' . $languages[$i]['id'] . ']', 'soft', '120', '15', (isset($products_manual[$languages[$i]['id']]) ? $products_manual[$languages[$i]['id']] : tep_get_products_manual($pInfo->products_id, $languages[$i]['id']))); ?></td>
				<td><?php  echo TEXT_PRODUCTS_MANUAL; ?><br>
				<?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
              </tr>
    </table>
	<a href="#top"></a>
  </div>

     <div class="tabcontent">
    <a name="t6<?php echo $i;?>" id="t6<?php echo $i;?>"></a>
		<table border="0" cellspacing="0" cellpadding="1" width="100%">
              <tr align="left">
                <td valign="top" class="main" width="0"><?php echo tep_draw_textarea_field('products_extra1[' . $languages[$i]['id'] . ']', 'soft', '120', '15', (isset($products_extra1[$languages[$i]['id']]) ? $products_extra1[$languages[$i]['id']] : tep_get_products_extra1($pInfo->products_id, $languages[$i]['id']))); ?></td>
				<td><?php echo TEXT_PRODUCTS_EXTRA1; ?><br>
				<?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
              </tr>
      </table>
	<a href="#top"></a>
  </div>
   <div class="tabcontent">
    <a name="t7<?php echo $i;?>" id="t7<?php echo $i;?>"></a>
			<table border="0" cellspacing="0" cellpadding="1" width="100%">
              <tr align="left">
                <td valign="top" class="main" width="0"><?php echo tep_draw_textarea_field('products_moreinfo[' . $languages[$i]['id'] . ']', 'soft', '120', '15', (isset($products_moreinfo[$languages[$i]['id']]) ? $products_moreinfo[$languages[$i]['id']] : tep_get_products_moreinfo($pInfo->products_id, $languages[$i]['id']))); ?></td>
				<td><?php  echo TEXT_PRODUCTS_MOREINFO; ?><br>
				<?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
              </tr>
            </table>
	<a href="#top"></a>
  </div>
</td></tr></table>

