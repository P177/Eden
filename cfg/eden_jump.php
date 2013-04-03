<?php


require_once("./db.".$_GET['project'].".inc.php");
$link = mysql_connect($eden_cfg['db_server'], $eden_cfg['db_uname'], $eden_cfg['db_pass']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
mysql_select_db($eden_cfg['db_name']); 
if ($_GET['jump_mode'] == "adds"){
	mysql_query("UPDATE $db_adds SET adds_out=adds_out+1 WHERE adds_id=".(integer)$_GET['id']."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$res = mysql_query("SELECT adds_link FROM $db_adds WHERE adds_id=".(integer)$_GET['id']."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$add_part = "adds_link";
} else {
	mysql_query("UPDATE $db_links SET links_out=links_out+1 WHERE links_id=".(integer)$_GET['id']."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$res = mysql_query("SELECT links_link FROM $db_links WHERE links_id=".(integer)$_GET['id']."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$add_part = "links_link";
}
$ar = mysql_fetch_array($res);

header ("Location: http://".$ar[$add_part]."");