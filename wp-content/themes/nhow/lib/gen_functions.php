<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');

/*-------------TRIM BY CHARS--------------------*/
function trim_by_chars($string, $limit, $pad) {
	$pad = $pad;

	if(strlen($string) <= $limit) { 
		$new_string = $string; 
	}
	else {
		$new_string = substr($string, 0, $limit) . $pad;
	}
	return $new_string;
}	

/*-------------TRIM BY WORDS--------------------*/
function trim_by_words($string,$limit,$pad) {
	$pad = $pad;

	$wordsExp = explode(' ',$string);
	$words = implode(' ',array_slice($wordsExp,0));

	$countWords = str_word_count($words);

	if ($countWords <= $limit) {
		$new_string = $string;
	}
	else {
		$new_string = implode(' ',array_slice($wordsExp,0,$limit)).' '.$pad;
	}
	return $new_string;	
}


/*-------------GET 2ND LEVEL KEYS--------------------*/
function getL2Keys($array)
{
    $result = array();
    foreach($array as $sub) {
        $result = array_merge($result, $sub);
    }        
    return array_keys($result);
}

/*-------------BETTER TRIM BY WORDS--------------------*/
function the_content_limit($max_char, $more_link_text = '', $stripteaser = 0, $more_file = '') {
	$content = get_the_content($more_link_text, $stripteaser, $more_file);
	$content = apply_filters('the_content', $content);

	if (strlen($content)>$max_char && ($espacio = strpos($content, ' ', $max_char))) {
		$content = substr($content, 0, $espacio);
		$newContent = strip_tags($content, '<p>');
//replace 1st P of work entry for excerpts		
		$strippedContent = preg_replace('/<p class="project-header">Project Overview<\/p>/', '', $newContent);
		echo $strippedContent.' . . .';
	}
	elseif (strlen($content)<$max_char) {
		echo $content;
	}
}



				
	
	
//STOP HERE
?>