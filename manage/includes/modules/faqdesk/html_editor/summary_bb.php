<table class="dataTableRowSelected" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="">
<tr>
<td valign="top" width="100%">
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<b>text</b>');"><img src="<?php echo EDITOR_IMAGE ?>/bold.png" border="0" alt="Bold Font"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<i>text</i>');"><img src="<?php echo EDITOR_IMAGE ?>/italic.png" border="0" alt="Italics font"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<u>text</u>');"><img src="<?php echo EDITOR_IMAGE ?>/underline.png" border="0" alt="Underline"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<s>text</s>');"><img src="<?php echo EDITOR_IMAGE ?>/strike.png" border="0" alt="Strike Out"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<sub>text</sub>');"><img src="<?php echo EDITOR_IMAGE ?>/sub.png" border="0" alt="Subscript"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<sup>text</sup>');"><img src="<?php echo EDITOR_IMAGE ?>/sup.png" border="0" alt="Superscript"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<span style=width=80%; filter:shadow(color=red,strength=3,left)>for this to work you must place a quote symbol between (= and width) also between () and >)</span>');"><img src="<?php echo EDITOR_IMAGE ?>/shadow.png" border="0" alt="Shadow Text"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<span style=width=80%; filter:glow(color=red,strength=2)>for this to work you must place a quote symbol between (= and width) also between () and >)</span>');"><img src="<?php echo EDITOR_IMAGE ?>/glow.png" border="0" alt="Glow Text"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<font color=red>text</font>');"><img src="<?php echo EDITOR_IMAGE ?>/color.png" border="0" alt="Font color"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<font face=verdana>text</font>');"><img src="<?php echo EDITOR_IMAGE ?>/fontface.png" border="0" alt="Font face"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<font size=2>text</font>');"><img src="<?php echo EDITOR_IMAGE ?>/fontsize.png" border="0" alt="Font size"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<div align=center>text</div>');"><img src="<?php echo EDITOR_IMAGE ?>/fontleft.png" border="0" alt="Font alignment"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<tt>text</tt>');"><img src="<?php echo EDITOR_IMAGE ?>/tele.png" border="0" alt="Teletype"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<hr>');"><img src="<?php echo EDITOR_IMAGE ?>/hr.png" border="0" alt="Horizontal Line"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<span><marquee direction=up>Text</marquee></span>');"><img src="<?php echo EDITOR_IMAGE ?>/move1.png" border="0" alt="Scroll"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<table width=100% bgcolor=#f8f8f9 border=0><tr><td>quote</td></tr></table>');"><img src="<?php echo EDITOR_IMAGE ?>/quote2.png" border="0" alt="Quote"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<img src=http://www.image.com/images/img.png>');"><img src="<?php echo EDITOR_IMAGE ?>/img.png" border="0" alt="Image"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<embed src=http://www.flash/images/image.swf quality=high pluginspage=http://www.macromedia.com/go/getflashplayer type=application/x-shockwave-flash width=200 height=200></embed>');"><img src="<?php echo EDITOR_IMAGE ?>/flash.png" border="0" alt="Flash Image"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<a href=mailto:username@site.com>Mail Me!</a>');"><img src="<?php echo EDITOR_IMAGE ?>/email2.png" border="0" alt="E-mail link"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<a href=http://www.link.com>address</a>');"><img src="<?php echo EDITOR_IMAGE ?>/url.png" border="0" alt="Hyperlink"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_s'; ?>('<ul><li>text1</li><li>text3</li><li>text3</li></ul>');"><img src="<?php echo EDITOR_IMAGE ?>/list.png" border="0" alt="List"></a>
</td>
</tr>

<tr>
<td width="100%" valign="top">

<?php
echo faqdesk_draw_textarea_field('faqdesk_answer_short_' . $languages[$i]['id'] . '', 'soft', '50', '3', stripbr((($faqdesk_answer_short[$languages[$i]['id']]) ? stripslashes($faqdesk_answer_short[$languages[$i]['id']]) : faqdesk_get_faqdesk_answer_short($pInfo->faqdesk_id, $languages[$i]['id']))
));
?>

		</td>
	</tr>
</table>

<?php
/*

	CartStore eCommerce Software, for The Next Generation ---- http://www.cartstore.com
	Copyright (c) 2008 Adoovo Inc. USA	GNU General Public License Compatible

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.	script name:		NewsDesk
	version:        		1.48.2
	date:       			06-05-2004 (dd/mm/yyyy)
	original author:		Carsten aka moyashi
	web site:       		www..com
	modified code by:		Wolfen aka 241

*/
?>