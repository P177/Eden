<?php
if ($_GET['project'] != "" && isset($_GET['id'])){
	require_once("./db.".$_GET['project'].".inc.php");
	require_once("./functions_frontend.php");
	if ($_GET['rss_version'] == "0.91" || $_GET['rss_version'] == "1.0" || $_GET['rss_version'] == "2.0" || $_GET['rss_version'] == "atom0.3" || $_GET['rss_version'] == "atom1.0" || $_GET['rss_version'] == "itunes"){$rss_version = $_GET['rss_version'];} else {$rss_version = "0.91";}
	if ($_GET['mode'] == "all"){
		$cat = 0;
		$mode = "all";
	} elseif ($_GET['mode'] == "shop"){
		$cat = 0;
		$mode = "shop";
	} elseif ($_GET['mode'] == "podcast"){
		$cat = 0;
		$mode = "podcast";
	} else {
		$cat = $_GET['cat'];
		$mode = "articles";
	}
	if ($_GET['g'] == 1){$google = 1;} else {$google = 0;}
	header('Content-Type: application/xml; charset=UTF-8');
	if ($_GET['rss_version'] == "itunes"){
		echo MakeRSSiTunes((float)$_GET['id']);
	}else{
		echo MakeRSS($_GET['id'],$cat,$rss_version,$mode,$google);
	}
}