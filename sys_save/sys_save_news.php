<?php

/* Provereni opravneni */
if ($_GET['action'] == "news_add"){
	if (CheckPriv("groups_news_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_news.php?project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "news_edit"){
	if (CheckPriv("groups_news_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_news.php?project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "news_del"){
	if (CheckPriv("groups_news_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_news.php?project=".$_SESSION['project']."&msg=nep");}
}else{
	header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']);
}

// Jestlize se uklada novinka proveri se jestli je povolena - pokud ano zaradi se z prispevku do novinek
if ($_POST['act'] == "articles_public"){$articles_public = 1;} else {$articles_public = 0;}
if ($_POST['news_allowfulltext'] == ""){$news_allowfulltext = 0;} else {$news_allowfulltext = $_POST['news_allowfulltext'];}

// Nastaveni spravne posloupnosti data pred ulozenim do databaze
$news_date_on = explode (".", $_POST['news_date_start']);
$news_date_off = explode (".", $_POST['news_date_end']);
$news_date_on = $news_date_on[2].$news_date_on[1].$news_date_on[0].Zerofill($_POST['news_date_on_4'],10).Zerofill($_POST['news_date_on_5'],10)."00";
$news_date_off = $news_date_off[2].$news_date_off[1].$news_date_off[0].Zerofill($_POST['news_date_off_4'],10).Zerofill($_POST['news_date_off_5'],10)."00";

$news_text = $_POST['news_text'];
$news_headline = strip_tags($_POST['news_headline']);
$news_source = strip_tags($_POST['news_source']);

if ($_POST['kill_word'] == 1){
	$news_text = HTMLcleaner::cleanup($news_text,$_POST['kill_word_font'],$_POST['kill_word_style'],$_POST['kill_word_class'],$_POST['kill_word_span'],$_POST['kill_word_p'],$_POST['kill_word_ul'],$_POST['kill_word_table'],0,0);
// Kdyz je v Nastaveni zatrhnuto Cleaner
} elseif ($ar_setup['setup_eden_editor_cleaner'] == 1){
	$news_text = HTMLcleaner::cleanup($news_text,1,$_POST['kill_word_style'],$_POST['kill_word_class'],$_POST['kill_word_span'],$_POST['kill_word_p'],$_POST['kill_word_ul'],$_POST['kill_word_table'],0,0);
}

require_once './class/HTMLPurifier/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$config->set('HTML.Doctype', 'HTML 4.01 Transitional');
$config->set('HTML.SafeObject', true);
$config->set('Filter.YouTube', true);
$config->set('Output.FlashCompat', true);
$config->set('AutoFormat.AutoParagraph', true);
$config->set('CSS.AllowTricky', true);
$config->set('Filter.Custom', array(new HTMLPurifier_Filter_MyIframe()));
$purifier = new HTMLPurifier($config);
$news_text = $purifier->purify( $news_text );

$news_source = StripInetService($news_source);

/* Nacteni datumu */
$res = mysql_query("SELECT news_author_id FROM "._DB_NEWS." WHERE news_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar = mysql_fetch_array($res);
$news_date = date("YmdHis"); // Aktualni datum pri ukladani novinky

// Zapsani zmenenych informaci do databaze
if ($_GET['action'] == "news_edit"){
	if (!isset($_POST['zmena_redaktora'])){$news_author = $ar['news_author_id'];} else {$news_author = $_POST['zmena_redaktora'];}
	mysql_query("UPDATE "._DB_NEWS." 
	SET news_headline='".mysql_real_escape_string($news_headline)."', 
	news_text='".mysql_real_escape_string($news_text)."', 
	news_author_id=".(integer)$news_author.", 
	news_category_id=".(integer)$_POST['news_section'].", 
	news_category_sub_id=".(integer)$_POST['news_section_sub'].", 
	news_date_edit='".mysql_real_escape_string($news_date)."', 
	news_date_on=".(float)$news_date_on.", 
	news_date_off=".(float)$news_date_off.", 
	news_user_use=0, 
	news_publish=".(integer)$_POST['news_publish'].", 
	news_comments=".(integer)$_POST['news_comments'].", 
	news_source='".mysql_real_escape_string($news_source)."' WHERE news_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	EdenLog(4,0,(integer)$_POST['id'],(integer)$_POST['news_section']);
	$news_id = $_POST['id'];
	$msg = "news_edit_ok";
}

if ($_GET['action'] == "news_add"){
	mysql_query("INSERT INTO "._DB_NEWS." (
	news_headline, 
	news_text, 
	news_author_id, 
	news_category_id, 
	news_category_sub_id, 
	news_date, 
	news_lang, 
	news_date_on, 
	news_date_off, 
	news_publish, 
	news_comments, 
	news_source 
	) VALUES (
	'".mysql_real_escape_string($news_headline)."',
	'".mysql_real_escape_string($news_text)."',
	'".(integer)$_SESSION['loginid']."',
	'".(integer)$_POST['news_section']."',
	'".(integer)$_POST['news_section_sub']."',
	'".mysql_real_escape_string($news_date)."',
	'cz',
	'".(float)$news_date_on."',
	'".(float)$news_date_off."',
	'".(integer)$_POST['news_publish']."',
	'".(integer)$_POST['news_comments']."',
	'".mysql_real_escape_string($news_source)."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$news_id = mysql_insert_id();
	$msg = "news_add_ok";
}

if ($_GET['action'] == "news_del"){
	//mysql_query("DELETE FROM "._DB_NEWS." WHERE news_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	mysql_query("UPDATE "._DB_NEWS." SET news_publish = 2 WHERE news_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "news_del_ok";
}

// Insert tags
$res = mysql_query("DELETE FROM "._DB_NEWS_TAGS." WHERE news_news_id = ".(integer)$news_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
foreach ($_POST['news_tags'] as $tag_id) {
 	$res_ins = mysql_query("INSERT INTO "._DB_NEWS_TAGS." VALUES('".(integer)$news_id."','".(integer)$tag_id."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
}


// Kdyz je zatrhut checkbox pridani kapitoly, najede znova editor, pro jeji pridani
if ($_POST['save'] == 1){
	$action = "news_edit&id=".$news_id."&ser=".$_GET['ser']."&podle=".$_GET['podle'];
} else {
	$action = "";
}

header ("Location: ".$eden_cfg['url_cms']."modul_news.php?action=".$action."&project=".$_SESSION['project']."&msg=".$msg);
exit;