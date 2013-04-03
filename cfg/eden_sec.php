<?php
/***********************************************************************************************************
*																											
*		htmlspecialchars_decode									 											
*																											
*		V pripade, ze verze PHP je nizsi nez 5																
*																											
***********************************************************************************************************/
if ( !function_exists('htmlspecialchars_decode') ){
    function htmlspecialchars_decode($string,$style=ENT_COMPAT){
        $translation = array_flip(get_html_translation_table(HTML_SPECIALCHARS,$style));
        if($style === ENT_QUOTES){ $translation['&#039;'] = '\''; }
        return strtr($string,$translation);
    }
}

function Kontrola_hlavicek(){
	global $_POST;
	global $_GET;
		
	foreach($_POST as &$post) {
		if (is_array($post)){
			/* Pokud je $_POST pole, neprovede se nic */
		} else {
			$post = Kontrola($post);
		}
	}
	foreach ($_GET as &$get){
		$get = Kontrola($get);
	}
}

function Kontrola($co){
	$co = @htmlspecialchars_decode($co,ENT_QUOTES);
	$co = addslashes(htmlspecialchars($co,ENT_QUOTES));
	return $co;
}
/* Pokud jsou na serveru v PHP zapnuty magic_quotes, osetrime vstupy */
if (get_magic_quotes_gpc() == 1){
	// odslashuje vsechny stringy v zadanem poli
	function strip_magic_quotes_gpc(&$array) { 
		if (get_magic_quotes_gpc() && is_array($array)) { 
			foreach($array as $i => $j) { 
				if (is_array($j)) { 
					strip_magic_quotes_gpc($array[$i]); 
				} else { 
					$array[stripslashes($i)] = stripslashes($j); 
				}
			}
		}
	}
	// odslashujeme vsechny vstupni parametry
	if (PHP_VERSION < 5.3){
		set_magic_quotes_runtime(0);
	}
	strip_magic_quotes_gpc($_GET);
	strip_magic_quotes_gpc($_POST);
	strip_magic_quotes_gpc($_REQUEST);
	strip_magic_quotes_gpc($_COOKIE);
}