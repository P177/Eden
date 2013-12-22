<?php

/* Provereni opravneni */
if ($_GET['action'] == "article_add"){
	if (CheckPriv("groups_article_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_articles.php?project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "article_edit"){
	if (CheckPriv("groups_article_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_articles.php?project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "article_del"){
	if (CheckPriv("groups_article_all_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_articles.php?project=".$_SESSION['project']."&msg=nep");}
}else{
	header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']);
}

// Jestlize se uklada novinka proveri se jestli je povolena - pokud ano zaradi se z prispevku do novinek
if ($_POST['act'] == "articles_public"){$articles_public = 1;} else {$articles_public = 0;}
if ($_POST['article_allowfulltext'] == ""){$allowfulltext = 0;} else {$allowfulltext = 1;}

// Nastaveni spravne posloupnosti data pred ulozenim do databaze
$article_date_on = explode (".", $_POST['article_date_start']);
$article_date_off = explode (".", $_POST['article_date_end']);
$article_date_on = $article_date_on[2].$article_date_on[1].$article_date_on[0].Zerofill($_POST['article_date_on_4'],10).Zerofill($_POST['article_date_on_5'],10)."00";
$article_date_off = $article_date_off[2].$article_date_off[1].$article_date_off[0].Zerofill($_POST['article_date_off_4'],10).Zerofill($_POST['article_date_off_5'],10)."00";

$article_perex = $_POST['article_perex'];
$article_body = $_POST['article_body'];
$article_headline = strip_tags($_POST['article_headline'],"");
$article_chapter_name = strip_tags($_POST['article_chapter_name'],"");

if ($_POST['kill_word'] == 1){
	$article_perex = HTMLcleaner::cleanup($_POST['article_perex'],$_POST['kill_word_font'],$_POST['kill_word_style'],$_POST['kill_word_class'],$_POST['kill_word_span'],$_POST['kill_word_p'],$_POST['kill_word_ul'],$_POST['kill_word_table'],$_POST['kill_word_object'],$_POST['kill_word_embed']);
	$article_body = HTMLcleaner::cleanup($article_body,$_POST['kill_word_font'],$_POST['kill_word_style'],$_POST['kill_word_class'],$_POST['kill_word_span'],$_POST['kill_word_p'],$_POST['kill_word_ul'],$_POST['kill_word_table'],$_POST['kill_word_object'],$_POST['kill_word_embed']);
// Kdyz je v Nastaveni zatrhnuto Cleaner
} elseif ($ar_setup['setup_eden_editor_cleaner'] == 1){
	$article_perex = HTMLcleaner::cleanup($article_perex,1,$_POST['kill_word_style'],$_POST['kill_word_class'],$_POST['kill_word_span'],$_POST['kill_word_p'],$_POST['kill_word_ul'],$_POST['kill_word_table'],$_POST['kill_word_object'],$_POST['kill_word_embed']);
	$article_body = HTMLcleaner::cleanup($article_body,1,$_POST['kill_word_style'],$_POST['kill_word_class'],$_POST['kill_word_span'],$_POST['kill_word_p'],$_POST['kill_word_ul'],$_POST['kill_word_table'],$_POST['kill_word_object'],$_POST['kill_word_embed']);
}
require_once './class/HTMLPurifier/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$config->set('HTML.Doctype', 'HTML 4.01 Transitional');
$config->set('HTML.SafeObject', true);
$config->set('Filter.YouTube', true);
$config->set('Attr.AllowedRel', 'facebox,nofollow,print');
$config->set('Attr.AllowedFrameTargets', '_blank,_self,_parent,_top');
$config->set('Output.FlashCompat', true);
$config->set('AutoFormat.AutoParagraph', true);
$config->set('CSS.AllowTricky', true);
//$config->set('Filter.Custom', array(new HTMLPurifier_Filter_MyIframe()));
$config->set('HTML.SafeIframe', true);
$config->set('URI.SafeIframeRegexp','%^http://(www.youtube(?:-nocookie)?.com/embed/|player.vimeo.com/video/'.$eden_cfg['tinymce_iframe_allowed'].')%');
$purifier = new HTMLPurifier($config);

// Purify string
$article_perex = $purifier->purify( $article_perex );
$article_body = $purifier->purify( $article_body );

$article_source = StripInetService($article_source);
$article_source = str_ireplace(" ", "", $article_source);

if ($_POST['article_top_article'] == 1){
	$res2 = mysql_query("SELECT article_id FROM "._DB_ARTICLES." WHERE article_top_article=1 AND article_lang='".mysql_real_escape_string($_POST['article_lang'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($ar2 = mysql_fetch_array($res2)){
		mysql_query("UPDATE "._DB_ARTICLES." SET article_top_article=0 WHERE article_id=".(integer)$ar2['article_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
}

if ($_GET['action'] == "article_edit"){
	/* Pokud je to kapitola, neuklada se headline */
	$article_id = $_POST['id'];
	
	if ($_POST['article_chapter_parent_id'] != 0){
		/* Zapsani zmenenych informaci do databaze */
		$res = mysql_query("SELECT article_author_id, article_category_id, article_chapter_number, article_date_on, article_date_off FROM "._DB_ARTICLES." WHERE article_id=".(integer)$article_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		
		if (!isset($_POST['zmena_redaktora'])){$article_author_id = $ar['article_author_id'];} else {$article_author_id = $_POST['zmena_redaktora'];}
		
		mysql_query("UPDATE "._DB_ARTICLES." SET 
		article_author_id=".(integer)$article_author_id.", 
		article_category_id=".(integer)$ar['article_category_id'].", 
		article_date_edit=".(float)$article_date.", 
		article_text='".mysql_real_escape_string($article_body)."', 
		article_lang='".mysql_real_escape_string($_POST['article_lang'])."', 
		article_link='".mysql_real_escape_string($_POST['article_link'])."', 
		article_date_on=".(float)$ar['article_date_on'].", 
		article_date_off=".(float)$ar['article_date_off'].", 
		article_user_use=0, 
		article_chapter_name='".mysql_real_escape_string($article_chapter_name)."',
		article_chapter_number=".(integer)$_POST['chapter_num']."	
		WHERE article_id=".(integer)$article_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		/*
		// Ulozime perex do parent novinky
		mysql_query("UPDATE "._DB_ARTICLES." SET 
		article_date_edit=".(float)$article_date.", 
		article_perex='".$article_perex."'
		WHERE article_id=".(integer)$_POST['article_chapter_parent_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		*/
	} else {
		$res_author = mysql_query("SELECT article_author_id FROM "._DB_ARTICLES." WHERE article_id=".(integer)$article_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_author = mysql_fetch_array($res_author);
		
		if (!isset($_POST['zmena_redaktora'])){$article_author_id = $ar_author['article_author_id'];} else {$article_author_id = $_POST['zmena_redaktora'];}
		
		if ($_POST['check_public'] == 1){$articles_public = 0;}
		
		mysql_query("UPDATE "._DB_ARTICLES." SET 
		article_headline='".mysql_real_escape_string($article_headline)."', 
		article_author_id='".(integer)$article_author_id."', 
		article_category_id=".(integer)$_POST['article_category_id'].", 
		article_category_sub_id=".(integer)$_POST['article_category_sub_id'].", 
		article_date_edit=".(float)$article_date.", 
		article_perex='".mysql_real_escape_string($article_perex)."', 
		article_text='".mysql_real_escape_string($article_body)."', 
		article_lang='".mysql_real_escape_string($_POST['article_lang'])."', 
		article_link='".mysql_real_escape_string($_POST['article_link'])."', 
		article_date_on=".(float)$article_date_on.", 
		article_date_off=".(float)$article_date_off.", 
		article_public=".(integer)$articles_public.", 
		article_comments=".(integer)$_POST['article_comments'].", 
		article_ftext=".(integer)$_POST['article_ftext'].", 
		article_prevoff=".(integer)$_POST['article_prevoff'].", 
		article_user_use=0, 
		article_chapter_name='".mysql_real_escape_string($article_chapter_name)."', 
		article_publish=".(integer)$_POST['article_publish'].", 
		article_top_article=".(integer)$_POST['article_top_article'].", 
		article_best_article=".(integer)$_POST['article_best_article'].", 
		article_source='".mysql_real_escape_string($article_source)."', 
		article_poll=".(integer)$_POST['article_poll'].", 
		article_channel_id=".(integer)$_POST['article_channel']."	
		WHERE article_id=".(integer)$article_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	EdenLog(3,(integer)$article_id,0,(integer)$_POST['article_category_id']);
}

if ($_GET['action'] == "article_add"){
	if ($_POST['article_chapter_number'] == 1){$article_parent_id = 0;} else {$article_parent_id = $_POST['article_parent_chapter'];}
	$res = mysql_query("INSERT INTO "._DB_ARTICLES." (
	article_headline, 
	article_author_id, 
	article_category_id, 
	article_category_sub_id, 
	article_date, 
	article_perex, 
	article_text, 
	article_lang, 
	article_link, 
	article_date_on, 
	article_date_off, 
	article_public, 
	article_comments, 
	article_ftext, 
	article_prevoff, 
	article_parent_id, 
	article_chapter_number, 
	article_chapter_name, 
	article_publish, 
	article_top_article, 
	article_best_article, 
	article_source, 
	article_poll, 
	article_channel_id, 
	article_hash) 
	VALUES(
	'".mysql_real_escape_string($article_headline)."',
	'".(integer)$_SESSION['loginid']."',
	'".(integer)$_POST['article_category_id']."',
	'".(integer)$_POST['article_category_sub_id']."',
	'".(float)$article_date."',
	'".mysql_real_escape_string($article_perex)."',
	'".mysql_real_escape_string($article_body)."',
	'".mysql_real_escape_string($_POST['article_lang'])."',
	'".mysql_real_escape_string($_POST['article_link'])."',
	'".(float)$article_date_on."',
	'".(float)$article_date_off."',
	'".(integer)$articles_public."',
	'".(integer)$_POST['article_comments']."',
	'".(integer)$_POST['article_ftext']."',
	'".(integer)$_POST['article_prevoff']."',
	'".(integer)$article_parent_id."',
	'".(integer)$_POST['article_chapter_number']."',
	'".mysql_real_escape_string($_POST['article_chapter_name'])."',
	'".(integer)$_POST['article_publish']."',
	'".(integer)$_POST['article_top_article']."',
	'".(integer)$_POST['article_best_article']."',
	'".mysql_real_escape_string($article_source)."',
	'".(integer)$_POST['article_poll']."',
	'".(integer)$_POST['article_channel']."',
	'".GeneratePass(8)."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$article_id = mysql_insert_id();
}



// Insert tags
$res = mysql_query("DELETE FROM "._DB_ARTICLES_TAGS." WHERE articles_article_id = ".(integer)$article_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
if ($_POST['article_tags']){
	foreach ($_POST['article_tags'] as $tag_id) {
		$res_ins = mysql_query("INSERT INTO "._DB_ARTICLES_TAGS." VALUES('".(integer)$article_id."','".(integer)$tag_id."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
}



if ($_GET['action'] == "article_del") {
	// Nacteme do pole vsechny podkapitoly a odstranime
	$dotaz = mysql_query("SELECT article_id FROM "._DB_ARTICLES." WHERE article_parent_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar = mysql_fetch_array($dotaz)){
		/* Nastaveni kapitol jako article_publish=2 (tvari se jako odstranena) Ostatni veci zustavaji nedknute */
		mysql_query("UPDATE "._DB_ARTICLES." SET article_publish=2 WHERE article_id=".(integer)$ar['article_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	/* Nastaveni kapitol jako article_publish=2 (tvari se jako odstranena) Ostatni veci zustavaji nedknute */
	mysql_query("UPDATE "._DB_ARTICLES." SET article_publish=2 WHERE article_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	header ("Location: ".$eden_cfg['url_cms']."modul_articles.php?action=showmain&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=".$msg);
	exit;
}
	
// Zapis nazvu obrazku do databaze
/*
if (file_exists($path_tmp.$_POST['picdbfilename'].".tmp")){
	$fpole = file($path_tmp.$_POST['picdbfilename'].".tmp");
	$numrows = count($fpole);
	$i = 0;
	while ($i < $numrows) { 
		$res = mysql_query("INSERT INTO $db_articles_images VALUES('".chop($fpole[$i])."','".(integer)$article_id."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$i++;
	}
	unlink($path_tmp.$_POST['picdbfilename'].".tmp");
}
*/
// Zapis nazvu souboru do databaze
/*
if (file_exists($path_tmp.$_POST['picdbfilename']."f.tmp")){
	$fpole = file($path_tmp.$_POST['picdbfilename']."f.tmp");
	$numrows = count($fpole);
	$i = 0;
	while ($i < $numrows) { 
		if ($_GET['action'] == "add"){$proper_article_id = $article_id;} else {$proper_article_id = $article_id;}
		$res = mysql_query("INSERT INTO $db_articles_files VALUES('".chop($fpole[$i])."','".(integer)$proper_article_id."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$i++;
	}
	unlink($path_tmp.$_POST['picdbfilename']."f.tmp");
}
*/

// Nahrani obrazku, ktery bude zobrazen u clanku
if ($_FILES['img_1']['name'] != "" || $_FILES['img_2']['name'] != "") {
	// Spojeni s FTP serverem 
	$conn_id = ftp_connect($eden_cfg['ftp_server']); 
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); 
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	// Zjisteni stavu spojeni
	if ((!$conn_id) || (!$login_result)) { echo _ERROR_FTP; die;} 
	
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
				header ("Location: ".$eden_cfg['url_cms']."modul_articles.php?action=article_edit&id=".$article_id."&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=editor_img_1_bad_ext");
		   		exit;
			}
			/* Zjistime zda neni obrazek mensi, nez je povoleno */
			if ($article_img_1[0] != GetSetupImageInfo("article_1","width") || $article_img_1[1] != GetSetupImageInfo("article_1","height")){
				header ("Location: ".$eden_cfg['url_cms']."modul_articles.php?action=article_edit&id=".$article_id."&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=editor_img_1_bad_size");
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
					mysql_query("UPDATE "._DB_ARTICLES." SET article_img_1='".mysql_real_escape_string($article_img_1)."' WHERE article_id=".(integer)$article_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				}
			}
		} else {
			header ("Location: ".$eden_cfg['url_cms']."modul_articles.php?action=article_edit&id=".$article_id."&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=editor_img_1_bad_file");
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
				header ("Location: ".$eden_cfg['url_cms']."modul_articles.php?action=article_edit&id=".$article_id."&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=editor_img_2_bad_ext");
		   		exit;
			}
			/* Zjistime zda neni obrazek mensi, nez je povoleno */
			if ($article_img_2[0] != GetSetupImageInfo("article_2","width") || $article_img_2[1] != GetSetupImageInfo("article_2","height")){
				header ("Location: ".$eden_cfg['url_cms']."modul_articles.php?action=article_edit&id=".$article_id."&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=editor_img_2_bad_size");
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
					mysql_query("UPDATE "._DB_ARTICLES." SET article_img_2='".mysql_real_escape_string($article_img_2)."' WHERE article_id=".(integer)$article_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				}
			}
		} else {
			header ("Location: ".$eden_cfg['url_cms']."modul_articles.php?action=article_edit&id=".$article_id."&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=editor_img_2_bad_file");
			exit;
		}
	}
	/* Uzavreni komunikace se serverem */
	ftp_close($conn_id); 
}
if ($_GET['action'] == "article_edit"){
	if ($_POST['save'] == 2){
		$action = "open";
		header ("Location: ".$eden_cfg['url_cms']."modul_articles.php?action=showmain&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=".$msg);
		exit;
	}elseif ($_POST['save'] == 3){
		$res = mysql_query("SELECT article_author_id, article_category_id, article_chapter_number, article_date_on, article_date_off FROM "._DB_ARTICLES." WHERE article_id=".(integer)$_POST['article_chapter_parent_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$res_ch = mysql_query("SELECT MAX(article_chapter_number) AS max_chapter_number FROM "._DB_ARTICLES." WHERE article_parent_id=".(integer)$_POST['article_chapter_parent_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_ch = mysql_fetch_array($res_ch);
		$article_chapter_number = $ar_ch['max_chapter_number']+1;
		if ($act == "articles_public" && $article_chapter_number >= 1){$articles_public = 0;}
		if ($_POST['article_chapter_parent_id'] == 0){$article_chapter_parent_id = $article_id;} else {$article_chapter_parent_id = $_POST['article_chapter_parent_id'];}
		mysql_query("INSERT INTO "._DB_ARTICLES." (
			article_author_id, 
			article_category_id, 
			article_date, 
			article_date_on, 
			article_date_off, 
			article_public, 
			article_parent_id, 
			article_chapter_number, 
			article_hash
			) VALUES (
			'".(integer)$ar['article_author_id']."',
			'".(integer)$ar['article_category_id']."',
			'".(float)$article_date."',
			'".(float)$ar['article_date_on']."',
			'".(float)$ar['article_date_off']."',
			'".(integer)$articles_public."',
			'".(integer)$article_chapter_parent_id."',
			'".(integer)$article_chapter_number."',
			'".GeneratePass(8)."'
		)") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$id = mysql_insert_id();
		header ("Location: ".$eden_cfg['url_cms']."modul_articles.php?action=article_edit&id=".$id."&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=".$msg);
		exit;
	}elseif ($_POST['save'] == 1){
		$id = $article_id;
		header ("Location: ".$eden_cfg['url_cms']."modul_articles.php?action=article_edit&id=".$id."&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=".$msg);
		exit;
	}
}
if ($_GET['action'] == "article_add"){
	// Kdyz je zatrhut checkbox pridani kapitoly, najede znova editor, pro jeji pridani
	if ($_POST['save'] == 1){
		if ($_POST['id'] == 0 || $_POST['id'] == ""){$id = $article_id;} else {$id = $_POST['id'];}
		header ("Location: ".$eden_cfg['url_cms']."modul_articles.php?action=article_edit&id=".$id."&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=".$msg);
		exit;
	}elseif ($_POST['save'] == 3){
		$res = mysql_query("SELECT article_author_id, article_category_id, article_chapter_number, article_date_on, article_date_off FROM "._DB_ARTICLES." WHERE article_id=".(integer)$article_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$article_chapter_number = $ar['article_chapter_number']+1;
		if ($_POST['act'] == "articles_public" && $article_chapter_number >= 1){$articles_public = 0;}
		mysql_query("INSERT INTO "._DB_ARTICLES." (
			article_author_id, 
			article_category_id, 
			article_date, 
			article_date_on, 
			article_date_off, 
			article_public, 
			article_parent_id, 
			article_chapter_number, 
			article_hash
			) VALUES (
			'".(integer)$ar['article_author_id']."',
			'".(integer)$ar['article_category_id']."',
			'".(float)$article_date."',
			'".(float)$ar['article_date_on']."',
			'".(float)$ar['article_date_off']."',
			'".(integer)$articles_public."',
			'".(integer)$article_id."',
			'".(integer)$article_chapter_number."',
			'".GeneratePass(8)."'
		)") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$id = mysql_insert_id();
		header ("Location: ".$eden_cfg['url_cms']."modul_articles.php?action=article_edit&id=".$id."&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=".$msg);
		exit;
	} else {
		header ("Location: ".$eden_cfg['url_cms']."modul_articles.php?action=showmain&project=".$_SESSION['project']."&act=".$_POST['act']."&page=".$_GET['page']."&kat=".$_GET['kat']."&sa=".$_GET['sa']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&msg=".$msg);
		exit;
	}
}
