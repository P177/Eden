<?php
/***********************************************************************************************************
*
*		VYHLEDAVANI	- Formular
*
*		vyhledava podle zadaneho retezce
*
*		$size	=	sirka okenka pro zadani textu
*		$gfx	=	1 - graficke tlacitko
*					0 - textove tlacitko
*		$flat	=	0 - Prvky budou pod sebou
*					1 - Prvky budou vedle sebe
*
***********************************************************************************************************/
function Search($size,$gfx,$flat = 0){
	
	global $db_category,$db_articles;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	if (!empty($_POST['podminka'])){$podminka = $_POST['podminka'];} else {$podminka = AGet($_GET,'podminka');}
	if (!empty($_POST['search_choice'])){$search_choice = $_POST['search_choice'];} else {$search_choice = AGet($_GET,'search_choice');}
	if (!empty($_POST['retezec'])){$retezec = $_POST['retezec'];} else {$retezec = AGet($_GET,'retezec');}
	
	$retezec = stripslashes($retezec);
	$retezec = htmlspecialchars($retezec, ENT_QUOTES);
  	echo "<form action=\"index.php?action=search&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" method=\"post\">";
	echo "<input type=\"text\" name=\"retezec\" size=\"".$size."\" maxlength=\"50\" value=\"".$retezec."\">"; if ($flat == 0){ echo "<br>";}
	echo "<select name=\"podminka\"><option value=\"1\" "; if ($podminka == 1){echo "selected=\"selected\"";} echo ">"._SEARCH_PODMINKA_1."</option>";
	echo "  <option value=\"2\" "; if ($podminka == 2){ echo "selected=\"selected\"";} echo ">"._SEARCH_PODMINKA_2."</option>";
	echo "	<option value=\"3\" "; if ($podminka == 3){ echo "selected=\"selected\"";} echo ">"._SEARCH_PODMINKA_3."</option>";
	echo "</select>"; if ($flat == 0){echo "<br>"; }
	echo _SEARCH_CHOICE_WHERE; if ($flat == 0){echo"<br>";}
	echo "<input type=\"radio\" name=\"search_choice\" value=\"article\" "; if ($search_choice == "article" || $search_choice == ""){echo "checked";} echo "> "._SEARCH_CHOICE_ARTICLES; if ($flat == 0){echo "<br>"; }
	echo "<input type=\"radio\" name=\"search_choice\" value=\"news\" "; if ($search_choice == "news"){echo "checked";} echo "> "._SEARCH_CHOICE_ACT; if ($flat == 0){echo "<br>"; }
	echo "<input type=\"submit\" class=\"eden_button\" value=\" ";if ($gfx != 1){echo _CMN_SUBMIT_SEARCH;} echo "\">";
	echo "</form>";
}
/***********************************************************************************************************
*
*		VYHLEDAVANI	- Vysledek
*
*		vyhledava podle zadaneho retezce
*
***********************************************************************************************************/
function SearchRes(){
	
	global $db_articles,$db_category,$db_setup,$db_comments,$db_admin,$db_news;
	global $url_articles;
	global $eden_cfg;
	
	if (!empty($_POST['podminka'])){$podminka = $_POST['podminka'];} else {$podminka = $_GET['podminka'];}
	if (!empty($_POST['search_choice'])){$search_choice = $_POST['search_choice'];} else {$search_choice = $_GET['search_choice'];}
	if (!empty($_POST['retezec'])){$retezec = AGet($_POST,'retezec');} else {$retezec = AGet($_GET,'retezec');}
	$showtime = formatTime(time(),"YmdHis");
	// Výcet povolených tagu
	$allowtags = "";
	// Z obsahu promenné body vyjmout nepovolené tagy
	$retezec = strip_tags($retezec,$allowtags);
	$retezec = htmlspecialchars($retezec, ENT_QUOTES);
	$retezec = mysql_real_escape_string($retezec);
	$podminka = strip_tags($podminka,$allowtags);
	$podminka = mysql_real_escape_string($podminka);
	$podminka = htmlspecialchars($podminka, ENT_QUOTES);
	$search_choice = strip_tags($search_choice,$allowtags);
	$search_choice = mysql_real_escape_string($search_choice);
	$search_choice = htmlspecialchars($search_choice, ENT_QUOTES);
	
	if (($podminka == 1) || ($podminka == 2)){
		/* podle podminky si nastavime promennou $spojka */
		if ($podminka == 1) {$spojka = "AND"; }
		if ($podminka == 2) {$spojka = "OR"; }
		
		/* vstupni retezec rozdelime do samostatnych slov */
		$seznam_slov = explode (' ', $retezec);
		$pomocny = "LOWER(n.".$search_choice."_text ".$eden_cfg['db_collate'].") LIKE LOWER('%".$seznam_slov[0]."%' ".$eden_cfg['db_collate'].")";
		$pomocny2 = "LOWER(n.".$search_choice."_headline ".$eden_cfg['db_collate'].") LIKE LOWER('%".$seznam_slov[0]."%' ".$eden_cfg['db_collate'].")";
		$pomocny3 = "LOWER(n.".$search_choice."_perex ".$eden_cfg['db_collate'].") LIKE LOWER('%".$seznam_slov[0]."%' ".$eden_cfg['db_collate'].")";
		
		$seznam_slov_num = count($seznam_slov);
		for ($i = 1; $i < $seznam_slov_num; $i++) {
			$pomocny = $pomocny.$spojka." LOWER(n.".$search_choice."_text ".$eden_cfg['db_collate'].") LIKE LOWER('%".$seznam_slov[$i]."%' ".$eden_cfg['db_collate'].") ";
			$pomocny2 = $pomocny2.$spojka." LOWER(n.".$search_choice."_headline ".$eden_cfg['db_collate'].") LIKE LOWER('%".$seznam_slov[$i]."%' ".$eden_cfg['db_collate'].") ";
			$pomocny3 = $pomocny3.$spojka." LOWER(n.".$search_choice."_perex ".$eden_cfg['db_collate'].") LIKE LOWER('%".$seznam_slov[$i]."%' ".$eden_cfg['db_collate'].") ";
		}
		if ($search_choice == "article"){
			$res = mysql_query("
			SELECT n.article_id, n.article_headline, n.article_perex, n.article_text, n.article_ftext, n.article_date_on, n.article_img_1, n.article_link, n.article_author_id, n.article_comments, n.article_category_id, n.article_parent_id 
			FROM $db_articles AS n 
			JOIN $db_category AS c ON c.category_id=n.article_category_id AND c.category_archive=1 
			WHERE (($pomocny) OR ($pomocny2) OR ($pomocny3)) AND n.article_public=0 AND n.article_publish=1 AND $showtime >= n.article_date_on AND $showtime BETWEEN n.article_date_on AND n.article_date_off 
			ORDER BY n.article_date_on DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		} else {
			$res = mysql_query("
			SELECT n.news_id, n.news_headline, n.news_text, n.news_date_on, n.news_author_id, n.news_comments, n.news_category_id 
			FROM $db_news AS n 
			JOIN $db_category AS c ON c.category_id=n.news_category_id AND c.category_archive=1 
			WHERE (($pomocny) OR ($pomocny2)) AND n.news_publish=1 AND $showtime >= n.news_date_on AND $showtime BETWEEN n.news_date_on AND n.news_date_off 
			ORDER BY n.news_date_on DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}
	if ($podminka == 3){
		if ($search_choice == "article"){
			$res = mysql_query("
			SELECT n.article_id, n.article_headline, n.article_perex, n.article_text, n.article_ftext, n.article_date_on, n.article_img_1, n.article_link, n.article_author_id, n.article_comments, n.article_category_id, n.article_parent_id 
			FROM $db_articles AS n 
			JOIN $db_category AS c ON c.category_id=n.article_category_id AND c.category_archive=1 
			WHERE n.article_public=0 AND n.article_publish=1 AND $showtime >= n.article_date_on AND $showtime BETWEEN n.article_date_on AND n.article_date_off AND (n.article_text LIKE '%$retezec%') OR (n.article_perex LIKE '%$retezec%') OR (n.article_headline LIKE '%$retezec%') 
			ORDER BY article_date_on DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		} else {
			$res = mysql_query("
			SELECT n.news_id, n.news_headline, n.news_text, n.news_date_on, n.news_author_id, n.news_comments, n.news_category_id 
			FROM $db_news AS n 
			JOIN $db_category AS c ON c.category_id=n.news_category_id AND c.category_archive=1 
			WHERE n.news_publish=1 AND $showtime >= n.news_date_on AND $showtime BETWEEN n.news_date_on AND n.news_date_off AND (n.news_text LIKE '%$retezec%') OR (n.news_headline LIKE '%$retezec%') 
			ORDER BY n.news_date_on DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}
	$num = mysql_num_rows($res);
	//
	// Nacteni nastaveni poctu zobrazovanych novineks
	$res_setup = mysql_query("SELECT setup_article_number FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$hits = $ar_setup['setup_article_number'];
	$m = 0;// nastaveni iterace
	
	if (empty($_GET['page'])) {$_GET['page'] = 1;} // i kdyz je promenna $_GET['page'] prazdna nastavi se pocet starnek na 1
	if ($hits == 0){$hits = 15;}
	$stw2 = ($num/$hits);
	$stw2 = (integer) $stw2;
	if ($num%$hits > 0) {$stw2++;}
	$np = $_GET['page']+1;
	$pp = $_GET['page']-1;
	if ($_GET['page'] == 1) { $pp = 1; }
	if ($np > $stw2) { $np = $stw2;}
	
	$sp = ($_GET['page']-1)*$hits;
	$ep = ($_GET['page']-1)*$hits+$hits;
	
	// Nacteni sablony
	include "templates/tpl.search.header.php";
	
	//unset($retezec);
	while ($ar = mysql_fetch_array($res)){
		if ($search_choice == "article"){
			$article_id = $ar['article_id'];
			$article_headline = TreatText($ar['article_headline'],0);
			$article_perex = TreatText($ar['article_perex'],0);
			$article_text = TreatText($ar['article_text'],0);
			$article_date_on = $ar['article_date_on'];
			$article_ftext = $ar['article_ftext'];
			$article_img_1 = $ar['article_img_1'];
			$article_link = $ar['article_link'];
			$article_author_id = $ar['article_author_id'];
			$article_comments = $ar['article_comments'];
			$article_category_id = $ar['article_category_id'];
			if (AGet($ar,'article_id_parents') == 1){
				$parent_id = $ar['article_id'];
				$parent_headline = $article_headline;
				$parent_preview = $article_perex;
			}
		} else {
			$article_id = $ar['news_id'];
			$article_headline = TreatText($ar['news_headline'],0);
			$article_text = TreatText($ar['news_text'],0);
			$article_date_on = $ar['news_date_on'];
			$article_author_id = $ar['news_author_id'];
			$article_comments = $ar['news_comments'];
			$article_category_id = $ar['news_category_id'];
		}
		$m++;
		if ($m>$sp and $m<=$ep){ //Spravne nastaveni poctu zobrazeni na strance
			$res2 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$article_id." AND comment_modul='".$search_choice."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Nastaveni ukazatele na komentare v danem clanku
			$num2 = mysql_fetch_array($res2); // Zjisteni poctu prispevku k danemu clanku
			
			$article_text = str_replace( "&acute;","'",$article_text);
			
			/* Highlighter */
			$article_headline = textHighlighter::createInstance('<span class="zvyrazneni">', '</span>')->highlight($article_headline, $retezec);
			$article_perex = textHighlighter::createInstance('<span class="zvyrazneni">', '</span>')->highlight($article_perex, $retezec);
			$article_text = textHighlighter::createInstance('<span class="zvyrazneni">', '</span>')->highlight($article_text, $retezec);
			// Nastaveni datumu
			$datum_clanku = FormatTimestamp($article_date_on,"l d.m.Y, H:i");
			//***********************************************
			//			Novinky
			//***********************************************
			
			$res6 = mysql_query("SELECT admin_nick, admin_email FROM $db_admin WHERE admin_id=".(integer)$article_author_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Vybere jmeno autora
			$ar6 = mysql_fetch_array($res6);
			
			$admin_nick = $ar6['admin_nick'];
			$admin_email = $ar6['admin_email'];
			
			// Nacteni sablony
			include "templates/tpl.search.php";
		}
	}
	// Pokud je novinek vice, nezli se vejde na 1 stranku zobrazi se jejich pocitani a prechazeni mezi nima
	if ($stw2 > 1){
		// Nacteni sablony
		include "templates/tpl.search.dalsi.php";
	}
}