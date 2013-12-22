<?php
// Dnesni datum
$today = date("Y-m-d")." 00:00:01";

// Nastaveni spravne posloupnosti data pred ulozenim do databaze
$article_date_on = explode (".", $_POST['article_date_start']);
$article_date_off = explode (".", $_POST['article_date_end']);
$article_date_on = $article_date_on[2].$article_date_on[1].$article_date_on[0].Zerofill($_POST['article_date_on_4'],10).Zerofill($_POST['article_date_on_5'],10)."00";
$article_date_off = $article_date_off[2].$article_date_off[1].$article_date_off[0].Zerofill($_POST['article_date_off_4'],10).Zerofill($_POST['article_date_off_5'],10)."00";

// Z obsahu promenné body vyjmout nepovolené tagy
// Vycet povolenych tagù
$allowtags = "<table>,<tr>,<td>,<embed>,<marquee>,<blink>,<hr>,<ul>,<li>,<ol>,<p>,<br>,<strong>,<u>,<em>,<small>,<big>,<strong>,<a>,<strike>,<img>,<blockquote>,<h1>,<h2>,<h3>,<h4>,<h5>,<div>,<span>,<object>,<param>,<iframe>";
$article_perex = PrepareForDB($_POST['article_perex'],1,$allowtags,1);
$article_body = PrepareForDB($_POST['article_body'],1,$allowtags,1);
$article_headline = PrepareForDB($_POST['article_headline'],1,$allowtags,1);

// Change image source
//$article_perex = str_ireplace("src=&quot;../".$_SESSION['project']."/","src=\""._URL_ARTICLES,$article_perex);
//$article_body = str_ireplace("src=&quot;../".$_SESSION['project']."/","src=\""._URL_ARTICLES,$article_body);

if ($_GET['action'] == "stream_edit"){
	/* Zapsani zmenenych informaci do databaze */
	if (!isset($_POST['zmena_redaktora'])){$article_author_id = $ar['article_author_id'];} else {$article_author_id = $_POST['zmena_redaktora'];}
	/* Pokud je to kapitola, neuklada se headline */
	mysql_query("UPDATE $db_articles SET 
	article_author_id=".(integer)$article_author_id.", 
	article_headline='".mysql_real_escape_string($article_headline)."', 
	article_category_id=".(integer)$_POST['article_category_id'].", 
	article_category_sub_id=".(integer)$_POST['article_category_sub_id'].", 
	article_date_edit=".(float)$article_date.", 
	article_perex='".$article_perex."', 
	article_text='".$article_body."', 
	article_lang='".mysql_real_escape_string($_POST['article_lang'])."',
	article_date_on=".(float)$article_date_on.", 
	article_date_off=".(float)$article_date_off.", 
	article_public=".(integer)$articles_public.", 
	article_comments=".(integer)$_POST['article_comments'].", 
	article_user_use=0, 
	article_source='".mysql_real_escape_string($_POST['article_source'])."',
	article_publish=".(integer)$_POST['article_publish']." 
	WHERE article_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	EdenLog(5,(integer)$_POST['id'],0,(integer)$_POST['article_category_id']);
}

if ($_GET['action'] == "stream_add"){
	$res = mysql_query("INSERT INTO $db_articles 
	VALUES(
	'',
	'".mysql_real_escape_string($article_headline)."',
	'".(integer)$_SESSION['loginid']."',
	'".(integer)$_POST['article_category_id']."',
	'".(integer)$_POST['article_category_sub_id']."',
	'',
	'',
	'".(float)$article_date."',
	'',
	'',
	'".$article_perex."',
	'".$article_body."',
	'".mysql_real_escape_string($_POST['article_lang'])."',
	0,
	'',
	'".(float)$article_date_on."',
	'".(float)$article_date_off."',
	'0',
	'".(integer)$_POST['article_comments']."',
	'1',
	'0',
	'',
	'',
	'0',
	'1',
	'',
	'".(integer)$_POST['article_publish']."',
	'0',
	'0',
	'".mysql_real_escape_string($_POST['article_source'])."',
	'0',
	'0',
	'')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$stream_id = mysql_insert_id();
}

// Nahrani obrazku, ktery bude zobrazen u clanku
if ($_FILES['img_1']['name'] != "" || $_FILES['img_2']['name'] != "") {
	// Spojeni s FTP serverem 
	$conn_id = ftp_connect($eden_cfg['ftp_server']); 
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); 
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	// Zjisteni stavu spojeni
	if ((!$conn_id) || (!$login_result)) { echo _ERROR_FTP; die;} 
	if ($_GET['action'] == "article_edit"){$article_id = $_POST['id'];}
	
	/* Ilustracni obrazek 1 */
	if ($_FILES['img_1']['name'] != "") {
		if (($article_img_1 = getimagesize($_FILES['img_1']['tmp_name'])) != false){
			/* Zjistime zda neni soubor jineho typu nez je povoleno a pokud ne priradime extenzi */
			if ($article_img_1[2] == 1){
				$extenze_1 = ".gif";
			} elseif ($article_img_1[2] == 2){
				$extenze_1 = ".jpg";
			} elseif ($article_img_1[2] == 3){
				$extenze_1 = ".png";
			} else {
				/* Pokud nesouhlasi pripona, zobrazime editor s chybovou hlaskou */
				header ("Location: ".$eden_cfg['url_cms']."modul_streams.php?action=stream_edit&id=".$article_id."&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=editor_img_1_bad_ext");
		   		exit;
			}
			/* Zjistime zda neni obrazek mensi, nez je povoleno */
			if ($article_img_1[0] != GetSetupImageInfo("article_1","width") || $article_img_1[1] != GetSetupImageInfo("article_1","height")){
				header ("Location: ".$eden_cfg['url_cms']."modul_streams.php?action=stream_edit&id=".$article_id."&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=editor_img_1_bad_size");
		   		exit;
			} else {
				/* Zjisti nazev souboru po ulozeni do docasneho adresare na serveru */
				$source_file_1 = $_FILES['img_1']['tmp_name'];
				$article_img_1 = Cislo().strtolower($extenze_1);
				/* Vlozi nazev souboru a cestu do konkretniho adresare */
				$destination_file_1 = $ftp_path_articles.$article_img_1;
				$upload_1 = ftp_put($conn_id, $destination_file_1, $source_file_1, FTP_BINARY);
				
				/* Zjisteni stavu uploadu */
				if (!$upload_1){ 
					$msg = "article_img_1_upl_er";
				} else {
					/* Pokud vse probehne jak ma znicime dane promenne a nastavime $checkupload */
					$msg = "article_img_1_upl_ok";
					// Zapise nazev obrazku do databaze
					mysql_query("UPDATE $db_articles SET article_img_1='".mysql_real_escape_string($article_img_1)."' WHERE article_id=".(integer)$article_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				}
			}
		} else {
			header ("Location: ".$eden_cfg['url_cms']."modul_streams.php?action=stream_edit&id=".$article_id."&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=editor_img_1_bad_file");
		   	exit;
		}
	}
	/* Ilustracni obrazek 2 */
	if ($_FILES['img_2']['name'] != "") {
		if (($article_img_2 = getimagesize($_FILES['img_2']['tmp_name'])) != false){
			/* Zjistime zda neni soubor jineho typu nez je povoleno a pokud ne priradime extenzi */
			if ($article_img_2[2] == 1){
				$extenze_2 = ".gif";
			} elseif ($article_img_2[2] == 2){
				$extenze_2 = ".jpg";
			} elseif ($article_img_2[2] == 3){
				$extenze_2 = ".png";
			} else {
				/* Pokud nesouhlasi pripona, zobrazime editor s chybovou hlaskou */
				header ("Location: ".$eden_cfg['url_cms']."modul_streams.php?action=stream_edit&id=".$article_id."&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=editor_img_2_bad_ext");
		   		exit;
			}
			/* Zjistime zda neni obrazek mensi, nez je povoleno */
			if ($article_img_2[0] != GetSetupImageInfo("article_2","width") || $article_img_2[1] != GetSetupImageInfo("article_2","height")){
				header ("Location: ".$eden_cfg['url_cms']."modul_streams.php?action=stream_edit&id=".$article_id."&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=editor_img_2_bad_size");
		   		exit;
			} else {
				/* Zjisti nazev souboru po ulozeni do docasneho adresare na serveru */
				$source_file_2 = $_FILES['img_2']['tmp_name'];
				$article_img_2 = Cislo().strtolower($extenze_2);
				/* Vlozi nazev souboru a cestu do konkretniho adresare */
				$destination_file_2 = $ftp_path_articles.$article_img_2;
				$upload_2 = ftp_put($conn_id, $destination_file_2, $source_file_2, FTP_BINARY);
				
				/* Zjisteni stavu uploadu */
				if (!$upload_2){ 
					$msg .= "article_img_2_upl_er";
				} else {
					/* Pokud vse probehne jak ma znicime dane promenne a nastavime $checkupload */
					$msg .= "article_img_2_upl_ok";
					// Zapise nazev obrazku do databaze
					mysql_query("UPDATE $db_articles SET article_img_2='".mysql_real_escape_string($article_img_2)."' WHERE article_id=".(integer)$article_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				}
			}
		} else {
			header ("Location: ".$eden_cfg['url_cms']."modul_streams.php?action=stream_edit&id=".$article_id."&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=editor_img_2_bad_file");
			exit;
		}
	}
	/* Uzavreni komunikace se serverem */
	ftp_close($conn_id); 
}
if ($_GET['action'] == "stream_edit"){
	if ($_POST['save'] == 2){
		$action = "open";
		header ("Location: ".$eden_cfg['url_cms']."modul_streams.php?action=showmain&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=".$msg);
		exit;
	}elseif ($_POST['save'] == 1){
		$id = $_POST['id'];
		header ("Location: ".$eden_cfg['url_cms']."modul_streams.php?action=stream_edit&id=".$id."&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=".$msg);
		exit;
	}
}

if ($_GET['action'] == "stream_add"){
	// Kdyz je zatrhut checkbox pridani kapitoly, najede znova editor, pro jeji pridani
	if ($_POST['save'] == 1){
		if ($_POST['id'] == 0 || $_POST['id'] == ""){$id = $article_id;} else {$id = $_POST['id'];}
		header ("Location: ".$eden_cfg['url_cms']."modul_streams.php?action=stream_edit&id=".$id."&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=".$msg);
		exit;
	} else {
		header ("Location: ".$eden_cfg['url_cms']."modul_streams.php?action=showmain&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=".$msg);
		exit;
	}
}

if ($_GET['action'] == "stream_del"){
	/* Nastaveni kapitol jako article_publish=2 (tvari se jako odstranena) Ostatni veci zustavaji nedknute */
	mysql_query("UPDATE $db_articles SET article_publish=2 WHERE article_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		/* Ostraneni novinky natrvalo
		$res = mysql_query("DELETE FROM $db_articles WHERE article_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$res2 = mysql_query("DELETE FROM $db_articles_images WHERE articles_images_article_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$res3 = mysql_query("DELETE FROM $db_articles_files WHERE article_files_article_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$res4 = mysql_query("DELETE FROM $db_comments WHERE comment_pid=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	*/
	header ("Location: ".$eden_cfg['url_cms']."modul_streams.php?action=showmain&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=".$msg);
	exit;
}