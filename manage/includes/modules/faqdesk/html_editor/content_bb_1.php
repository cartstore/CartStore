<table class="dataTableRowSelected" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="">
<tr>
<td valign="top" width="100%">
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<b>text</b>');"><img src="<?php echo EDITOR_IMAGE ?>/bold.png" border="0" alt="Bold Font"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<i>text</i>');"><img src="<?php echo EDITOR_IMAGE ?>/italic.png" border="0" alt="Italics font"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<u>text</u>');"><img src="<?php echo EDITOR_IMAGE ?>/underline.png" border="0" alt="Underline"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<s>text</s>');"><img src="<?php echo EDITOR_IMAGE ?>/strike.png" border="0" alt="Strike Out"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<sub>text</sub>');"><img src="<?php echo EDITOR_IMAGE ?>/sub.png" border="0" alt="Subscript"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<sup>text</sup>');"><img src="<?php echo EDITOR_IMAGE ?>/sup.png" border="0" alt="Superscript"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<shadow=red,left,1>TEXT</shadow>');"><img src="<?php echo EDITOR_IMAGE ?>/shadow.png" border="0" alt="Shadow Text"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<glow=red,2,1>TEXT</glow>');"><img src="<?php echo EDITOR_IMAGE ?>/glow.png" border="0" alt="Glow Text"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<color=red>text</color>');"><img src="<?php echo EDITOR_IMAGE ?>/color.png" border="0" alt="Font color"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<font=verdana>text</font>');"><img src="<?php echo EDITOR_IMAGE ?>/fontface.png" border="0" alt="Font face"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<size=2>text</size>');"><img src="<?php echo EDITOR_IMAGE ?>/fontsize.png" border="0" alt="font size"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<align=left>text</align>');"><img src="<?php echo EDITOR_IMAGE ?>/fontleft.png" border="0" alt="Font alignment"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<tt>text</tt>');"><img src="<?php echo EDITOR_IMAGE ?>/tele.png" border="0" alt="Teletype"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<hr>');"><img src="<?php echo EDITOR_IMAGE ?>/hr.png" border="0" alt="Horizontal Line"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<move>STUFF</move>');"><img src="<?php echo EDITOR_IMAGE ?>/move.png" border="0" alt="Move"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<quote>text</quote>');"><img src="<?php echo EDITOR_IMAGE ?>/quote2.png" border="0" alt="Quote"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<img>URL</img>');"><img src="<?php echo EDITOR_IMAGE ?>/img.png" border="0" alt="Image"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<flash=200,200>URL</flash>');"><img src="<?php echo EDITOR_IMAGE ?>/flash.png" border="0" alt="Flash Image"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<email=username@site.com>Mail Meg!</email>');"><img src="<?php echo EDITOR_IMAGE ?>/email2.png" border="0" alt="E-mail link"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<url=http://www.url.com>address</url>');"><img src="<?php echo EDITOR_IMAGE ?>/url.png" border="0" alt="hyperlink"></a>
<a href="Javascript:<?php echo 'setsmiley_' . $languages[$i]['id'] . '_c'; ?>('<ul><li>text1</li><li>text3</li><li>text3</li></ul>');"><img src="<?php echo EDITOR_IMAGE ?>/list.png" border="0" alt="List"></a>
</td>
</tr>

<tr>
<td width="100%" valign="top">

<?php
echo faqdesk_draw_textarea_field('faqdesk_answer_long_' . $languages[$i]['id'] . '', 'soft', '50', '15', stripbr((($faqdesk_answer_long[$languages[$i]['id']]) ? stripslashes($faqdesk_answer_long[$languages[$i]['id']]) : faqdesk_get_faqdesk_answer_long($pInfo->faqdesk_id, $languages[$i]['id']))
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
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.

	script name:	FAQDesk
	version:		1.0
	date:			2003-03-27
	author:			Carsten aka moyashi
	web site:		www..com

*/
?>
