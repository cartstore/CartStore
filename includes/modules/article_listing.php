<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
 <?php 
 
 if (tep_not_null($topic['topics_heading_title'])) {
      //    echo '<h1>';
       //   echo $topic['topics_heading_title'];
       //   echo 'www</h1>';
     
      }
      
      if (tep_not_null($topic['topics_description'])) {
          echo $topic['topics_description'];
      } ;?>
  <?php
  
  
  $listing_split = new splitPageResults($listing_sql, MAX_ARTICLES_PER_PAGE);
  if (($listing_split->number_of_rows > 0) && ((ARTICLE_PREV_NEXT_BAR_LOCATION == 'top') || (ARTICLE_PREV_NEXT_BAR_LOCATION == 'both'))) {
?>

<div id="module-product">
	
	
        <ul class="ui-tabs-nav ui-listview ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all ">
        	
        	     	<li data-role="list-divider" role="heading" class="ui-li ui-li-divider ui-bar-b">  <?php
      echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_ARTICLES);
?></li>
        	
        	
     
        </ul>
      
      </div>
      </div>
      <span class="clear"></span>
      <?php
  }
?>
      <?php
  if ($listing_split->number_of_rows > 0) {
      $articles_listing_query = tep_db_query($listing_split->sql_query);
?>
      <br />
      <?php
      while ($articles_listing = tep_db_fetch_array($articles_listing_query)) {
?>
      <span class="blog">
      <?php
          echo '<h2><a class="main" href="' . tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $articles_listing['articles_id']) . '">' . $articles_listing['articles_name'] . '</a></h2>

   ';
          if (DISPLAY_AUTHOR_ARTICLE_LISTING == 'true' && tep_not_null($articles_listing['authors_name'])) {
              echo TEXT_BY . ' ' . '<a href="' . tep_href_link(FILENAME_ARTICLES, 'authors_id=' . $articles_listing['authors_id']) . '"> ' . $articles_listing['authors_name'] . '</a>';
          }
?>
      <br />
      <?php
          if (DISPLAY_TOPIC_ARTICLE_LISTING == 'true' && tep_not_null($articles_listing['topics_name'])) {
?>
      <span class="topic">
      <?php
              echo TEXT_TOPIC . '&nbsp;<a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $articles_listing['topics_id']) . '">' . $articles_listing['topics_name'] . '</a>';
?>
      </span><br />
      <?php
          }
?>
      <span class="article_intro">
      <?php
          echo clean_html_comments(substr($articles_listing['articles_head_desc_tag'], 0, '900')) . ((strlen($articles_listing['articles_head_desc_tag']) >= MAX_ARTICLE_ABSTRACT_LENGTH) ? '...' : '');
?>
      </span> <br />
      <br />
      <?php
          echo '<a class="readon" href="' . tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $articles_listing['articles_id']) . '">Read More >></a>';
?>
      <span class="article_seperator"></span> <span class="clear"></span> <br />
      <br />
      </span>
      <?php
          if (DISPLAY_ABSTRACT_ARTICLE_LISTING == 'false') {
          }
          if (DISPLAY_DATE_ADDED_ARTICLE_LISTING == 'true') {
          }
          if (DISPLAY_ABSTRACT_ARTICLE_LISTING == 'true' || DISPLAY_DATE_ADDED_ARTICLE_LISTING) {
          }
      }
  } else {
      if ($listing_no_article != '') {
          echo $listing_no_article;
      } elseif ($topic_depth == 'articles') {
          echo TEXT_NO_ARTICLES;
      } elseif (isset($_GET['authors_id'])) {
          echo TEXT_NO_ARTICLES2;
      }
  }
  if (($listing_split->number_of_rows > 0) && ((ARTICLE_PREV_NEXT_BAR_LOCATION == 'bottom') || (ARTICLE_PREV_NEXT_BAR_LOCATION == 'both'))) {
?>
      <span class="clear"></span>
      <div id="module-product">
        <ul class="ui-tabs-nav ui-listview ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
        	
        	<li data-role="list-divider" role="heading" class="ui-li ui-li-divider ui-bar-b">  <?php
      echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_ARTICLES);
?></li>
        	
        	
          <?php
      echo '' . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y')));
?>
        </ul>
      
      </div>
      <span class="clear"></span>

<?php
  }
?>
</td>
  </tr>
</table>
