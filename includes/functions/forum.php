<?php
/* new global file to accomodate all vaiables */

// query the forum table to retrieve values

/*
$forum_info_query = tep_db_query("SELECT forumid, title, description, active, displayorder, replycount, lastpost, threadcount, allowposting FROM ". TABLE_FORUM ."");
while ($forum_info =  tep_db_fetch_array($forum_info_query))
{
   $forumid = $forum_info['forumid'];
   $title = $forum_info['title'];
   $decription = $forum_info['description'];
   $active = $forum_info['active'];
   $dis_order = $forum_ifo['displayorder'];
   $reply_count = $forum_info['replycount'];
   $lastpost = $forum_info['lastpost'];
   $threadcount = $forum_info['threadcount'];
   $allow_post = $forum_info['allowpost'];
}

  */


function parsemessage($message, $smile=1) {
if ($smile==1) {
$message = parsesmile($message);
}
$message = parsebbcode($message);
$message = nl2br($message);
return $message;
}

function parsebbcode($message, $html=1) {
if ($html==0) {
htmlspecialchars();
}

  $message=preg_replace("/".quotemeta("[b]")."/i",quotemeta("<b>"),$message);
  $message=preg_replace("/".quotemeta("[/b]")."/i",quotemeta("</b>"),$message);
  $message=preg_replace("/".quotemeta("[i]")."/i",quotemeta("<i>"),$message);
  $message=preg_replace("/".quotemeta("[/i]")."/i",quotemeta("</i>"),$message);
  $message=preg_replace("/".quotemeta("[u]")."/i",quotemeta("<u>"),$message);
  $message=preg_replace("/".quotemeta("[/u]")."/i",quotemeta("</u>"),$message);


  // do [url]xxx[/url]
  $message=preg_replace("/\[url\]www.([^\[]*)\[\/url\]/i","<a href=\"http:\/\/www.\\1\" target=_blank>\\1</a>",$message);
  $message=preg_replace("/\[url\]([^\[]*)\[/url\]/i","<a href=\"\\1\" target=_blank>\\1</a>",$message);

  // do [email]xxx[/email]
  $message=preg_replace("/\[email\]([^\[]*)\[\/email\]/i","<a href=\"mailto:\\1\">\\1</a>",$message);

  // do quotes
  $message=preg_replace("/quote\]/i","quote]",$message);  // make lower case
  $message=str_replace("[quote]\r\n","<blockquote><smallfont>quote:</smallfont><hr>",$message);
  $message=str_replace("[quote]","<blockquote><smallfont>quote:</smallfont><hr>",$message);
  $message=str_replace("[/quote]\r\n","<hr></blockquote>",$message);
  $message=str_replace("[/quote]","<hr></blockquote>",$message);

  // do codes
  $message=preg_replace("/code\]/i","code]",$message);  // make lower case
  $message=str_replace("[code]\r\n","<blockquote><smallfont>code:</smallfont><pre><hr>",$message);
  $message=str_replace("[code]","<blockquote><smallfont>code:</smallfont><hr><pre>\n",$message);
  $message=str_replace("[/code]\r\n","</pre><hr></blockquote>",$message);
  $message=str_replace("[/code]","</pre><hr></blockquote>",$message);

  // do [img]xxx[/img]
  $message=preg_replace("/\[img\]([^\[]*)\[/img\]/i","<img src=\"\\1\" border=0>",$message);



return $message;
}

function parsesmile($message) {
//evil
$message = str_replace(">:)", "<IMG SRC=\"images/emoticons/evil.gif\">", $message);

//smile
$message = str_replace(":)", "<IMG SRC=\"images/emoticons/smile.gif\">", $message);

//mad
$message = str_replace(">:(", "<IMG SRC=\"images/emoticons/mad.gif\">", $message);

//sad
$message = str_replace(":(", "<IMG SRC=\"images/emoticons/sad.gif\">", $message);

//tired
$message = str_replace(":tired", "<IMG SRC=\"images/emoticons/tired.gif\">", $message);

//redface
$message = str_replace(":o", "<IMG SRC=\"images/emoticons/redface.gif\">", $message);

//tounge
$message = str_replace(":p", "<IMG SRC=\"images/emoticons/tounge.gif\">", $message);

//biggrin
$message = str_replace(":D", "<IMG SRC=\"images/emoticons/biggrin.gif\">", $message);

//wink
$message = str_replace(";)", "<IMG SRC=\"images/emoticons/wink.gif\">", $message);

//cool
$message = str_replace(":cool", "<IMG SRC=\"images/emoticons/cool.gif\">", $message);

//rolleyes
$message = str_replace(":roll", "<IMG SRC=\"images/emoticons/rolleyes.gif\">", $message);

//drunk
$message = str_replace(":~", "<IMG SRC=\"images/emoticons/drunk.gif\">", $message);

//eek
$message = str_replace(":eek", "<IMG SRC=\"images/emoticons/eek.gif\">", $message);

//confused
$message = str_replace(":?", "<IMG SRC=\"images/emoticons/confused.gif\">", $message);

//finger
$message = str_replace(":finger", "<IMG SRC=\"images/emoticons/finger.gif\">", $message);

//dunno
$message = str_replace(":dunno", "<IMG SRC=\"images/emoticons/dunno.gif\">", $message);

return $message;
}


