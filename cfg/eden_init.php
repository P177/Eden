<?php
if ( !function_exists('AGet') ){
	function AGet($array, $key, $default = NULL){
	    return isset($array[$key]) ? $array[$key] : $default;
	}
}
