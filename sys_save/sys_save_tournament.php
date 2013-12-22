<?php
/* Provereni opravneni */
if ($_GET['action'] == "tournament_add"){
	if (CheckPriv("groups_tournament_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_poll.php?action=showmain&project=".$_SESSION['project']."&msg=nep&page=".$_GET['page']."&hits=".$_GET['hits']);}
}elseif ($_GET['action'] == "tournament_edit"){
	if (CheckPriv("groups_tournament_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_poll.php?action=showmain&project=".$_SESSION['project']."&msg=nep&page=".$_GET['page']."&hits=".$_GET['hits']);}
}elseif ($_GET['action'] == "tournament_del"){
	if (CheckPriv("groups_tournament_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_poll.php?action=showmain&project=".$_SESSION['project']."&msg=nep&page=".$_GET['page']."&hits=".$_GET['hits']);}
}else{
	header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']);
}
// Z obsahu promenné body vyjmout nepovolené tagy
if ($_POST['name'] != ""){$tournament_name = $_POST['name'];}
if ($_POST['format'] != ""){$tournament_format = $_POST['format'];}
if ($_POST['regularity'] != ""){$tournament_regularity = $_POST['regularity'];}
if ($_POST['date'] != ""){$tournament_date = $_POST['date'];}
if ($_POST['time'] != ""){$tournament_time = $_POST['time'];}
if ($_POST['registration_start'] != ""){$tournament_registration = $_POST['registration_start'];}
if ($_POST['buyin'] != ""){$tournament_buyin = $_POST['buyin'];}
if ($_POST['prizes'] != ""){$tournament_prizes = $_POST['prizes'];}
$tournament_description = $_POST['description'];
$tournament_id = $_POST['id'];
require_once './class/HTMLPurifier/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$config->set('HTML.Doctype', 'HTML 4.01 Transitional');
$config->set('HTML.SafeObject', true);
$config->set('Filter.YouTube', true);
$config->set('Attr', 'AllowedRel', 'facebox,nofollow,print');
$config->set('Output.FlashCompat', true);
$config->set('AutoFormat.AutoParagraph', true);
$config->set('CSS.AllowTricky', true);
//$config->set('Filter.Custom', array(new HTMLPurifier_Filter_MyIframe()));
$config->set('HTML.SafeIframe', true);
$config->set('URI.SafeIframeRegexp','%^http://(www.vsadte-se.cz/|www.youtube(?:-nocookie)?.com/embed/|player.vimeo.com/video/)%');
$purifier = new HTMLPurifier($config);

// Purify string
$tournament_description = $purifier->purify($tournament_description);

if ($_GET['action'] == "tournament_add"){
	$res = mysql_query("
	INSERT INTO "._DB_TOURNAMENTS." (
	tournament_name, 
	tournament_admin_id, 
	tournament_format, 
	tournament_regularity, 
	tournament_date, 
	tournament_time, 
	tournament_registration_start, 
	tournament_buyin, 
	tournament_prizes, 
	tournament_description
	) VALUES (
	'".mysql_real_escape_string($tournament_name)."',
	'".(integer)$_SESSION['loginid']."',
	'".mysql_real_escape_string($tournament_format)."',
	'".(integer)$tournament_regularity."',
	'".mysql_real_escape_string($tournament_date)."',
	'".mysql_real_escape_string($tournament_time)."',
	'".mysql_real_escape_string($tournament_registration)."',
	'".(integer)$tournament_buyin."',
	'".mysql_real_escape_string($tournament_prizes)."',
	'".mysql_real_escape_string($tournament_description)."')"
	) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "tournament_add_ok";
}
if ($_GET['action'] == "tournament_edit"){
	$res = mysql_query("UPDATE 
	"._DB_TOURNAMENTS." 
	SET tournament_name = '".mysql_real_escape_string($tournament_name)."', 
	tournament_format = '".mysql_real_escape_string($tournament_format)."', 
	tournament_regularity = '".(integer)$tournament_regularity."', 
	tournament_date = '".mysql_real_escape_string($tournament_date)."', 
	tournament_time = '".mysql_real_escape_string($tournament_time)."', 
	tournament_registration_start = '".mysql_real_escape_string($tournament_registration)."', 
	tournament_buyin = '".(integer)$tournament_buyin."', 
	tournament_prizes = '".mysql_real_escape_string($tournament_prizes)."', 
	tournament_description = '".mysql_real_escape_string($tournament_description)."' 
	WHERE tournament_id = ".(integer)$tournament_id
	) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "tournament_edit_ok";
}
if ($_GET['action'] == "tournament_del"){
	$res = mysql_query("DELETE 
	FROM "._DB_TOURNAMENTS." 
	WHERE tournament_id = ".(integer)$_POST['id']
	) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "tournament_del_ok";
}
header ("Location: ".$eden_cfg['url_cms']."modul_tournaments.php?action=showmain&project=".$_SESSION['project']."&msg=".$msg."&page=".$_GET['page']."&hits=".$_GET['hits']);
exit;
