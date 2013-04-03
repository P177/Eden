<?php
require_once("./db.".$_GET['project'].".inc.php");
$link = mysql_connect($eden_cfg['db_server'], $eden_cfg['db_uname'], $eden_cfg['db_pass']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
mysql_select_db($eden_cfg['db_name']); 
$dow_date = date("YmdHis");
if ($_GET['mirr'] == 1 || $_GET['mirr'] == 2 || $_GET['mirr'] == 3){
	/* DOWNLOAD */
	mysql_query("UPDATE $db_download SET download_num_download=download_num_download+1 WHERE download_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$res = mysql_query("SELECT download_service, download_service2, download_service3, download_link, download_link2, download_link3 FROM $db_download WHERE download_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	if ($_GET['mirr'] == 1){header ("Location: ".$ar['download_service'].$ar['download_link']);}
	if ($_GET['mirr'] == 2){header ("Location: ".$ar['download_service2'].$ar['download_link2']);}
	if ($_GET['mirr'] == 3){header ("Location: ".$ar['download_service3'].$ar['download_link3']);}
} elseif ($_GET['mirr'] == "podcasts"){
	/* PODCASTS */
	mysql_query("UPDATE $db_podcast SET podcast_downloads=podcast_downloads+1 WHERE podcast_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$res = mysql_query("SELECT podcast_enclosure_url, podcast_enclosure_type FROM $db_podcast WHERE podcast_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	header("Content-type: ".$ar['podcast_enclosure_type']);
	header ("Location: ".stripslashes($ar['podcast_enclosure_url']));
}