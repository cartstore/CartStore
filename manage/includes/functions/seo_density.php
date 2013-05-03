<?php 
/*
  SEO_Assistant for OSC 2.2 2.0 v2.0  08.03.2004
  Originally Created by: Jack York
  GNU General Public License Compatible
  CartStore eCommerce Software, for The Next Generation
  Copyright (c) 2008 Adoovo Inc. USA
*/ 
function kda($url, &$total, $use_meta_tags, $use_partial_total) 
{      
    if(!stristr($url, 'http://')) 
    { 
        $url = 'http://'.$url; 
    } 
 	   
    if($html = @file_get_contents($url)) 
    { 
    $html = html_entity_decode(file_get_contents($url)); 
    //preg_match('/(?<=<title>).*?(?=<\\/title>)/is', $html, $matches); 
    //$title = array_shift($matches);     
		
		$meta_tags = ($use_meta_tags) ? get_meta_tags($url) : ''; 
    $html = kda_strip_tag_script($html); 
    $no_html = strip_tags($html); 
    $tag_info = $meta_tags['description']." ".$meta_tags['keywords']; 
    $text .= $tag_info." ".$no_html;   
	  $total = count(explode(' ', $text)); 
	  $text = kda_clean(kda_stopWords($text));
		$words = explode(' ', $text);     
    $total = count($words);     
	 
    for($x=0; $x<$total; $x++) 
    { 
       $words[$x] = trim($words[$x]); 	
		   if($words[$x]!='') 
       {   	
	      $ws[$words[$x]]++; 
        if(trim($words[$x+1])!='') 
        { 
            $phrase2 = $words[$x]." ".trim($words[$x+1]); 
            $ws[$phrase2]++; 

            if(trim($words[$x+2])!='') 
            { 
                $phrase3 = $words[$x]." ".trim($words[$x+1])." ".trim($words[$x+2]); 
                $ws[$phrase3]++; 
            } 
        } 
        }     	
    } 
    foreach($ws as $word=>$count) 
    { 
        if( ($count>1) and (strlen($word)>2) ) 
        { 
            $phrase_size = count(explode(' ', $word)); 
            $occurances[$phrase_size] = $occurances[$phrase_size] + $count; 
	      } 
    } 
		
    foreach($ws as $word=>$count) 
    { 
        if( ($count>1) and (strlen($word)>2) ) 
        { 				
            $phrase_size = count(explode(' ', $word));             
  					$ttlWords = ($use_partial_total) ? $occurances[$phrase_size] : $total;
	  			  $density = round(($count/$ttlWords)*100, 2);           
            $dens[$phrase_size][$word] = $density;   
						$dens[$word] = $count;         
	      } 
    } 
 
    arsort($dens[1]); 
    if($dens[2]) 
    { 
        arsort($dens[2]); 
    } 
    if($dens[3]) 
    {         
        arsort($dens[3]); 
    } 
	  return $dens ; 
}else { 
return false; 
} 
} 
function kda_strip_tag_script($html) { 
    $pos1 = false; 
    $pos2 = false; 
    do { 
        if ($pos1 !== false && $pos2 !== false) { 
            $first = NULL; 
            $second = NULL; 
            if ($pos1 > 0) 
                 $first = substr($html, 0, $pos1); 
            if ($pos2 < strlen($html) - 1) 
                $second = substr($html, $pos2); 
            $html = $first . $second; 
        } 
        preg_match("/<script[^>]*>/i", $html, $matches); 
        $str1 =& $matches[0]; 
        preg_match("/<\/script>/i", $html, $matches); 
        $str2 =& $matches[0]; 
        $pos1 = strpos($html, $str1); 
        $pos2 = strpos($html, $str2); 
       if ($pos2 !== false) 
            $pos2 += strlen($str2); 
    } while ($pos1 !== false && $pos2 !== false); 
	
    return $html; 
} 
function kda_clean($text) 
{ 
global $stopwords_file; 
    $text = str_replace('.', '', $text); 
    $text = str_replace(',', '', $text); 
    $text = str_replace('(', '', $text); 
    $text = str_replace(')', '', $text);     
    $text = str_replace('_', '', $text); 
    $text = str_replace('*', '', $text);     
    $text = str_replace('"', '', $text);     
    $text = str_replace('-', '', $text);     
    $text = str_replace("!", '', $text);     
    $text = str_replace("?", '', $text);     
    $text = str_replace("\n", '', $text);     
    $text = str_replace('/', '', $text);    
  	$text = str_replace('|', '', $text);    
    $text = str_replace('&#8217;', "'", $text);     
     
    return trim(strtolower($text)); 
} 
function kda_stopWords($term) 
    { 
        global $sw_count; 
    //load list of common words 
    $common = file(DIR_WS_FUNCTIONS.'seo_words.txt'); 
    $total = count($common);     
    for ($x=0; $x<= $total; $x++) 
    { 
        $common[$x] = trim(strtolower($common[$x])); 
    } 
     
    //make array of search terms         
    $_terms = explode(" ", $term); 
     
        foreach ($_terms as $line) 
        { 
            if (in_array(strtolower(trim($line)), $common)) 
            {                 
                $removeKey = array_search($line, $_terms); 
                $sw_count++; 
                unset($_terms[$removeKey]);                 
            } 
            else 
            { 
                $clean_term .= " ".$line; 
            } 
        } 
        return $clean_term;     
    } 
?> 