<?php
/* Nastavime spravne datum ze skriptu */
$article_date_on = $_POST['podcast_pub_date'];
$podcast_pub_date = $article_date_on[6].$article_date_on[7].$article_date_on[8].$article_date_on[9].$article_date_on[5].$article_date_on[3].$article_date_on[4].$article_date_on[2].$article_date_on[0].$article_date_on[1].' '.$_POST['podcast_pub_date_h'].':'.$_POST['podcast_pub_date_m'].':00';

/* Nastavime spravnou dobu trvani */
$podcast_duration = $_POST['podcast_duration_h'].':'.$_POST['podcast_duration_m'].':'.$_POST['podcast_duration_s'];

$res_adm = mysql_query("SELECT admin_nick, admin_firstname, admin_name FROM $db_admin WHERE admin_id=".(float)$_POST['podcast_author_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar_adm = mysql_fetch_array($res_adm);

if ($_GET['action'] == "add_rss_i"){
	mysql_query("INSERT INTO $db_podcast VALUES (
	'',
	'".(float)$_POST['chid']."',
	'".mysql_real_escape_string($_POST['podcast_title'])."',
	'".mysql_real_escape_string($_POST['podcast_subtitle'])."',
	'".mysql_real_escape_string($ar_adm['admin_firstname']." ".$ar_adm['admin_name'])."',
	'".(integer)$_POST['podcast_author_id']."',
	'".mysql_real_escape_string($_POST['podcast_summary'])."',
	'".mysql_real_escape_string($_POST['podcast_enclosure_url'])."',
	'".(integer)$_POST['podcast_enclosure_lenght']."',
	'".mysql_real_escape_string($_POST['podcast_enclosure_type'])."',
	'".mysql_real_escape_string($_POST['podcast_guid'])."',
	'".$podcast_pub_date."',
	NOW(),
	'".$podcast_duration."',
	'".mysql_real_escape_string($_POST['podcast_keywords'])."',
	'".(integer)$_POST['podcast_block']."',
	'".(integer)$_POST['podcast_explicit']."',
	'')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
}
if ($_GET['action'] == "edit_rss_i"){
	mysql_query("UPDATE $db_podcast SET
	podcast_channel_id='".(float)$_POST['chid']."',
	podcast_title='".mysql_real_escape_string($_POST['podcast_title'])."',
	podcast_subtitle='".mysql_real_escape_string($_POST['podcast_subtitle'])."',
	podcast_author='".mysql_real_escape_string($ar_adm['admin_firstname']." ".$ar_adm['admin_name'])."',
	podcast_author_id='".(float)$_POST['podcast_author_id']."',
	podcast_summary='".mysql_real_escape_string($_POST['podcast_summary'])."',
	podcast_enclosure_url='".mysql_real_escape_string($_POST['podcast_enclosure_url'])."',
	podcast_enclosure_lenght=".(integer)$_POST['podcast_enclosure_lenght'].",
	podcast_enclosure_type='".mysql_real_escape_string($_POST['podcast_enclosure_type'])."',
	podcast_guid='".mysql_real_escape_string($_POST['podcast_guid'])."',
	podcast_pub_date='".$podcast_pub_date."',
	podcast_edit_date=NOW(),
	podcast_duration='".$podcast_duration."',
	podcast_keywords='".mysql_real_escape_string($_POST['podcast_keywords'])."',
	podcast_block=".(integer)$_POST['podcast_block'].",
	podcast_explicit=".(integer)$_POST['podcast_explicit']." WHERE podcast_id=".$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
}
header ("Location: ".$eden_cfg['url_cms']."modul_rss_itunes.php?action=showitems&chid=".$_POST['chid']."&project=".$_SESSION['project']);
exit;