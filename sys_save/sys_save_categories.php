<?php
/* Pokud pridavame a editujeme ulozi se potrebne zmeny */
if ($_POST['add_admin'] == ""){
	//Nasteveni modu pro kategorie
	if ($_POST['category'] == "articles"){$articles = 1;}
	if ($_POST['category'] == "news"){$news = 1;}
	if ($_POST['category'] == "links"){$links = 1;}
	if ($_POST['category'] == "reklama"){$adds = 1;}
	if ($_POST['category'] == "shop"){$shop = 1;}
	if ($_POST['category'] == "download"){$download = 1;}
	if ($_POST['category'] == "stream"){$stream = 1;}
	
	$picture = $_POST['picture'];
	// Výčet povolených tagů
	$allowtags = "<strong>, <u>, <i>";
	// Z obsahu proměnné body vyjmout nepovolené tagy
	$category_name = PrepareForDB($_POST['category_name'],1,$allowtags,1);
	$comment = PrepareForDB($_POST['category_desc'],1,$allowtags,1);
	
	/* ADD CATEGORY - ADD TOPIC */
	if ($_GET['action'] == "category_add" || $_GET['action'] == "topic_add"){
		if (!isset($_POST['picture']) || $_POST['picture'] == ""){$picture = "AllTopics.gif";} else {$picture = $_POST['picture'];}
		if ($_GET['action'] == "topic_add"){
			$res2 = mysql_query("SELECT category_level FROM $db_category WHERE category_parent=".(integer)$_POST['parent']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar2 = mysql_fetch_array($res2);
			$level = $ar2['category_level'] + 1;
			
			mysql_query("INSERT INTO $db_category 
			(
			category_image,
			category_name,
			category_comment,
			category_hits,
			category_parent,
			category_level,
			category_articles,
			category_news,
			category_links,
			category_adds,
			category_shop,
			category_download,
			category_stream,
			category_shows,
			category_archive,
			category_active
			) VALUES(
			'".mysql_real_escape_string($picture)."',
			'".mysql_real_escape_string($category_name)."',
			'".mysql_real_escape_string($comment)."',
			'".(integer)$_POST['hits']."',
			'".(integer)$_POST['parent']."',
			'".(integer)$level."',
			'".(integer)$articles."',
			'".(integer)$news."',
			'".(integer)$links."',
			'".(integer)$adds."',
			'".(integer)$shop."',
			'".(integer)$download."',
			'".(integer)$stream."',
			'".(integer)$_POST['shows']."',
			'".(integer)$_POST['archiv']."',
			'".(integer)$_POST['active']."'
			)") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
		
		if ($_GET['action'] == "category_add"){
			mysql_query("INSERT INTO $db_category 
			(
			category_image,
			category_name,
			category_comment,
			category_hits,
			category_articles,
			category_news,
			category_links,
			category_adds,
			category_shop,
			category_download,
			category_stream,
			category_shows,
			category_archive,
			category_active
			) VALUES(
			'".mysql_real_escape_string($picture)."',
			'".mysql_real_escape_string($category_name)."',
			'".mysql_real_escape_string($comment)."',
			'".(integer)$_POST['hits']."',
			'".(integer)$articles."',
			'".(integer)$news."',
			'".(integer)$links."',
			'".(integer)$adds."',
			'".(integer)$shop."',
			'".(integer)$download."',
			'".(integer)$stream."',
			'".(integer)$_POST['shows']."',
			'".(integer)$_POST['archiv']."',
			'".(integer)$_POST['active']."'
			)") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}
	
	/* EDIT CATEGORY - EDIT TOPIC */
	if ($_GET['action'] == "category_edit" || $_GET['action'] == "topic_edit"){
		$res = mysql_query("SELECT category_id, category_name, category_admin, category_image, category_parent, category_image, category_hits, category_articles, category_news, category_adds, category_shop, category_download, category_links, category_shows, category_archive, category_comment FROM $db_category WHERE category_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		if ($_POST['picture'] == "" && $ar['category_image'] == ""){$picture = "AllTopics.gif";} else {$picture = $_POST['picture'];}
		if ($_POST['picture'] == "" && $ar['category_image'] != ""){$picture = $ar['category_image'];} else {$picture = $_POST['picture'];}
		
		/* Nastaveni levelu */
		if ($_GET['action'] == "topic_edit"){
			$res2 = mysql_query("SELECT category_level FROM $db_category WHERE category_id=".(integer)$ar['category_parent']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar2 = mysql_fetch_array($res2);
			$level = $ar2['category_level'] + 1;
			if ($_POST['parent'] == ""){$_POST['parent'] = $ar['category_parent'];}
		}
		if ($_GET['action'] == "category_edit" || $_GET['action'] == "topic_edit"){
			$res = mysql_query("UPDATE $db_category 
			SET category_image='".mysql_real_escape_string($picture)."', 
			category_name='".mysql_real_escape_string($category_name)."', 
			category_comment='".mysql_real_escape_string($comment)."', 
			category_hits=".(integer)$_POST['hits'].", 
			category_parent=".(integer)$_POST['parent'].", 
			category_level=".(integer)$level.", 
			category_articles=".(integer)$articles.", 
			category_news=".(integer)$news.", 
			category_links=".(integer)$links.", 
			category_adds=".(integer)$adds.", 
			category_shop=".(integer)$shop.", 
			category_download=".(integer)$download.", 
			category_stream=".(integer)$stream.", 
			category_shows=".(integer)$_POST['shows'].", 
			category_archive=".(integer)$_POST['archiv'].", 
			category_active=".(integer)$_POST['active']." 
			WHERE category_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}
} else {
	/* Pokud potrebujeme k dane kategorii priradit urcite adminy */
	if ($_POST['add_admin'] == "»»»»»»"){ // >>>>>>>>
		// Pokud je z administrace nejake diskuze odebran nejaky uzivatel
		$res = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$admini = explode (" ", $ar['category_admin']); // Rozdeli se pole $ar[admin] na jednotlive uzivatele
		$admini2[] = $_POST['admins']; // Do pole o jednom clenu se ulozi odebirany uzivatel
		$result = array_diff ($admini, $admini2); // Do pole $result se ulozi vsichni uzivatele, kteri po odebrani toho jednoho zbyli
		$colon_separated = implode (" ", $result); // Ti se pak ulozi do retezce oddelene mezerami
		// A ulozi do databaze
		$res = mysql_query("UPDATE $db_category SET category_admin='".mysql_real_escape_string($colon_separated)."' WHERE category_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}elseif ($_POST['add_admin'] == "««««««"){ // <<<<<<<<
		// Pokud je do administrace nejake diskuze pridan nejaky uzivatel
		$res = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$admini = explode (" ", $ar['category_admin']); // Rozdeli se pole $ar[admin] na jednotlive uzivatele
		$admini2[] = $_POST['users']; // Do pole o jednom clenu se ulozi pridavany uzivatel
		$result = array_merge($admini, $admini2); // Do pole $result se ulozi na konec ten pridavany uzivatel
		$colon_separated = implode (" ", $result); // To vse se pak ulozi do retezce oddelene mezerami
		$res = mysql_query("UPDATE $db_category SET category_admin='".mysql_real_escape_string($colon_separated)."' WHERE category_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
}
header ("Location: ".$eden_cfg['url_cms']."sys_category.php?action=showmain&mode=".$_GET['mode']."&id=".$_POST['id']."&id1=".$_POST['id1']."&id2=".$_POST['id2']."&id3=".$_POST['id3']."&id4=".$_POST['id4']."&id5=".$_POST['id5']."&id6=".$_POST['id6']."&project=".$_SESSION['project']);
exit;