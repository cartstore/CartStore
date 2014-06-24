<?php
Header('Content-Type: application/xml');

require('includes/application_top.php');

$peticion = 'SELECT p.newsdesk_id, pd.language_id, pd.newsdesk_article_name, pd.newsdesk_article_description,
					pd.newsdesk_article_shorttext, pd.newsdesk_article_url, pd.newsdesk_article_url_name,
					p.newsdesk_image, p.newsdesk_date_added, p.newsdesk_last_modified, p.newsdesk_date_available,
					p.newsdesk_status

			 FROM ' . TABLE_NEWSDESK . ' p, ' . TABLE_NEWSDESK_DESCRIPTION . ' pd

			 WHERE pd.newsdesk_id = p.newsdesk_id

			 AND   pd.language_id = 3 and newsdesk_status = 1';

$query = tep_db_query($peticion);

echo '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
echo '<?xml-stylesheet href="http://www.w3.org/2000/08/w3c-synd/style.css" type="text/css"?>' . "\n";
?>
<rss version="0.91">
<channel>

		<title>Jose Angel Peñarrubia Serrano</title>
		<link>http://www.infosercomputer.es</link>
		<description>Servicios Informaticos</description>
		<language>es-ES</language>


		<image>
			<title>Jose Angel Peñarrubia Serrano</title>
			<url>http://www.infosercomputer.es/title.png</url>
			<link>http://www.infosercomputer.es</link>
			<width>96</width>
			<height>36</height>
		</image>

<?php
$ant = array( "'",      "&",     "\"",     ">",    "<");
$desp = array("&apos;", "&amp;", "&quot;", "&gt;", "&lt;");

while ($latest_news = tep_db_fetch_array($query)) {
	$id_new = $latest_news['newsdesk_id'];
	//$id_new = utf8_encode($id_new);
	$title_new1 = $latest_news['newsdesk_article_name'];
	$title_new1 = utf8_encode($title_new1);
	$title_new = str_replace($ant,$desp,$title_new1);
	$description_new1 = $latest_news['newsdesk_article_description'];
	$description_new1 = utf8_encode($description_new1);
	$description_new = str_replace($ant,$desp,$description_new1);

	$weblink = 'http://www.infosercomputer.es/newsdesk_info.php?newsdesk_id=' . $id_new;
?>
	<item>
		<title><?php echo $title_new; ?></title>
		<link><?php echo $weblink; ?></link>
		<description><?php echo $description_new; ?></description>
	</item>
<?php
}
?>
  </channel>
</rss>
