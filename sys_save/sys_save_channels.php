<?php
/* Provereni opravneni */
if ($_GET['action'] == "article_channel_add"){
	if (CheckPriv("groups_article_channel_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_articles_channel.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "article_channel_edit"){
	if (CheckPriv("groups_article_channel_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_articles_channel.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "article_channel_del"){
	if (CheckPriv("groups_article_channel_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_articles_channel.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}else{
	header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']);
}

$channel_title = PrepareForDB($_POST['channel_title'],1,"",1);
$channel_desc = PrepareForDB($_POST['channel_desc'],1,"",1);
$channel_img = PrepareForDB($_POST['image'],1,"",1);

if ($_GET['action'] == "article_channel_add"){
	$res = mysql_query("INSERT INTO $db_articles_channel VALUES('','".(integer)$_POST['channel_lang_id']."','".$channel_title."','".$channel_desc."',NOW(),'".$channel_img."','".(integer)$_POST['channel_importance']."','".(integer)$_POST['channel_active']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "article_channel_add_ok";
}

if ($_GET['action'] == "article_channel_edit"){
	$res = mysql_query("UPDATE $db_articles_channel SET article_channel_lang_id=".(integer)$_POST['channel_lang_id'].", article_channel_title='".$channel_title."', article_channel_description='".$channel_desc."', article_channel_image='".$channel_img."', article_channel_importance=".(integer)$_POST['channel_importance'].", article_channel_active=".(integer)$_POST['channel_active']." WHERE article_channel_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "article_channel_edit_ok";
}

if ($_GET['action'] == "article_channel_del"){
	$res = mysql_query("DELETE FROM $db_articles_channel WHERE article_channel_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "article_channel_del_ok";
}

header ("Location: ".$eden_cfg['url_cms']."modul_articles_channel.php?action=showmain&project=".$_SESSION['project']."&msg=".$msg);
exit;