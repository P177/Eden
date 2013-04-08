<?php /*ř*/
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
if ($_POST['name'] != ""){$tournament_name .= PrepareForDB($_POST['name'],1,"",1);}
if ($_POST['format'] != ""){$tournament_format = PrepareForDB($_POST['format'],1,"",1);}
if ($_POST['regularity'] != ""){$tournament_regularity = PrepareForDB($_POST['regularity'],1,"",1);}
if ($_POST['date'] != ""){$tournament_date = PrepareForDB($_POST['date'],1,"",1);}
if ($_POST['time'] != ""){$tournament_time = PrepareForDB($_POST['time'],1,"",1);}
if ($_POST['registration_start'] != ""){$tournament_registration = PrepareForDB($_POST['registration_start'],1,"",1);}
if ($_POST['buyin'] != ""){$tournament_buyin = PrepareForDB($_POST['buyin'],1,"",1);}
if ($_POST['prizes'] != ""){$tournament_prizes = PrepareForDB($_POST['prizes'],1,"",1);}
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

// Replace &amp; for & - to pass the HTMLPurifier
$tournament_description = str_replace("&amp;","&",$tournament_description);

// Purify string
$tournament_description = $purifier->purify($tournament_description);

// Replace & for &amp; - to put it back as it was
$tournament_description = str_replace("&","&amp;",$tournament_description);


if ($_GET['action'] == "tournament_add"){
	$res = mysql_query("INSERT INTO $db_tournaments VALUES ('','$tournament_name','".$_SESSION['loginid']."','$tournament_format', '$tournament_regularity', '$tournament_date', '$tournament_time', '$tournament_registration', '$tournament_buyin', '$tournament_prizes', '$tournament_description')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "tournament_add_ok";
}
if ($_GET['action'] == "tournament_edit"){
	$res = mysql_query("UPDATE $db_tournaments SET tournament_name='$tournament_name', tournament_format='$tournament_format', tournament_regularity='$tournament_regularity', tournament_date='$tournament_date', tournament_time='$tournament_time', tournament_registration_start='$tournament_registration', tournament_buyin='$tournament_buyin', tournament_prizes='$tournament_prizes', tournament_description='$tournament_description' WHERE tournament_id = '$tournament_id'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "tournament_edit_ok";
}
if ($_GET['action'] == "tournament_del"){
	$res = mysql_query("DELETE FROM $db_tournaments WHERE tournament_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "tournament_del_ok";
}
header ("Location: ".$eden_cfg['url_cms']."modul_tournaments.php?action=showmain&project=".$_SESSION['project']."&msg=".$msg."&page=".$_GET['page']."&hits=".$_GET['hits']);
exit;