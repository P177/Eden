<?php
include "modul_comments.php";

function Menu(){
	
	global $eden_cfg;
	
	if ($_GET['act'] == "articles_public"){
		$title_pre = _ARTICLES_PUBLIC;
	} else {
		$title_pre = _ARTICLES;
	}
	
	switch ($_GET['action']){
		case "stream_add":
			$title = _ARTICLES_ARTICLE_ADD;
			break;
		case "stream_edit":
			$title = _ARTICLES_ARTICLE_EDIT;
			break;
		case "stream_del":
			$title = _ARTICLES_ARTICLE_DEL;
			break;
		default:
			$title = "";
	}
	
	$menu = "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td align=\"left\" class=\"nadpis\">".$title_pre.$title."</td>\n";
	$menu .= "	</tr>\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td align=\"left\"><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">&nbsp;";
	$menu .= "	  		<a href=\"modul_articles.php?project=".$_SESSION['project']."&amp;action=showmain&act=".$_GET['act']."\">"._ARTICLES_ARTICLES_LIST."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	$menu .= "	  		<a href=\"modul_articles.php?action=article_add&amp;project=".$_SESSION['project']."&act=".$_GET['act']."\">"._ARTICLES_ARTICLE_ADD."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	$menu .= "			<a href=\"modul_articles_channel.php?action=&amp;project=".$_SESSION['project']."\">"._ARTICLES_CHANNEL."</a>";
	if ($_GET['act'] == "article"){ 
		$menu .= "&nbsp;&nbsp;|&nbsp;&nbsp;\n";
		$menu .= "		<a href=\"modul_articles.php?action=managepicture&amp;project=".$_SESSION['project']."&act=article\">"._ARTICLES_MANAGE_PIC."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
		$menu .= "		<a href=\"modul_articles.php?action=managefiles&amp;project=".$_SESSION['project']."&act=article\">"._ARTICLES_MANAGE_FILES."</a>";
	}
	$menu .= "		</td>\n";
	$menu .= "	</tr>";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['action'] == "stream_del" && $_POST['confirm'] != "true"){$_GET['msg'] = "stream_del_ch";}
	if ($_GET['msg']){
		$menu .= "<tr><td class=\"msg\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	$menu .= "</table>";
	
	return $menu;
}

//********************************************************************************************************
//																										
//			 ZOBRAZENI SEZNAMU ZPRAV																	
//																										
//********************************************************************************************************
function ShowMain(){
	
	global $db_articles,$db_comments,$db_category,$db_articles_images,$db_admin,$db_eden_log,$db_articles_channel;
	global $url_articles_channels;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	if (isset($_GET['hits'])){$hits = $_GET['hits'];}elseif (isset($_POST['hits'])){$hits = $_POST['hits'];} else {$hits = FALSE;}
	if (isset($_GET['search_c'])){$search_c = $_GET['search_c'];}elseif (isset($_POST['search_c'])){$search_c = $_POST['search_c'];} else {$search_c = FALSE;}
	if (isset($_GET['search_a'])){$search_a = $_GET['search_a'];}elseif (isset($_POST['search_a'])){$search_a = $_POST['search_a'];} else {$search_a = FALSE;}
	if (isset($_GET['search_id'])){$search_id = $_GET['search_id'];}elseif (isset($_POST['search_id'])){$search_id = $_POST['search_id'];} else {$search_id = FALSE;}
	if (isset($_GET['search_mode'])){$search_mode = $_GET['search_mode'];}elseif (isset($_POST['search_mode'])){$search_mode = $_POST['search_mode'];} else {$search_mode = FALSE;}
	if (isset($_GET['ondatez'])){$ondatez = $_GET['ondatez'];}elseif (isset($_POST['ondatez'])){$ondatez = $_POST['ondatez'];} else {$ondatez = FALSE;}
	if (isset($_GET['ondatek'])){$ondatek = $_GET['ondatek'];}elseif (isset($_POST['ondatek'])){$ondatek = $_POST['ondatek'];} else {$ondatek = FALSE;}
	$ser = $_GET['ser'];
	$podle = $_GET['podle'];
	$page = $_GET['page'];
	
	if (!isset($act)){$act = $_GET['act'];}
	
	if ($act == "articles_public"){
		$groups_priv_article_edit = "groups_article_public_edit";
		$groups_priv_article_del = "groups_article_public_del";
		$groups_priv_article_add = "groups_article_public_add";
		$articles_public = 1;
	} else {
		$groups_priv_article_edit = "groups_article_edit";
		$groups_priv_article_del = "groups_article_del";
		$groups_priv_article_add = "groups_article_add";
		$articles_public = 0;
	}
	
	if ($search_mode != 2){
		$article_date_on = formatTimeS(time()); // Dnesni datum
		$article_date_off = formatTimeS(time() - 60 * 60 * 24 * 30); // Datum pred 30 dny
		$ondatez = $article_date_off[1].".".$article_date_off[2].".".$article_date_off[3];
		$ondatek = $article_date_on[1].".".$article_date_on[2].".".$article_date_on[3];
	}
	
	if ($hits < 1){$hits = 30;}
	
	// Jestlize neni vybrano podle ceho se ma tridit, je vybrano podle datumu sestupne 
	if ($ser == "" && $podle == ""){
		$podle = "article_id";
		$podle_db = "n.article_id";
		$ser = "DESC";
	} else {
		$podle = mysql_real_escape_string($_GET['podle']);
		$podle_db = "n.".mysql_real_escape_string($_GET['podle']);
		$ser = mysql_real_escape_string($_GET['ser']);
	}
	
	// Search mode
	if ($search_mode == 1) { // Categories
		// Show articles only from specific category
		if ($search_c != ""){$select_category = " AND n.article_category_id=".(integer)$search_c;}
		if ($search_a != ""){$select_author = " AND n.article_author_id=".(integer)$search_a;}
		$where = "";
	} elseif ($search_mode == 2) { // Date
		// Show articles only from specific category
		if ($search_c != ""){$select_category = " AND n.article_category_id=".(integer)$search_c;}
		if ($search_a != ""){$select_author = " AND n.article_author_id=".(integer)$search_a;}
		$start = preg_replace ("/([0-9]{1,2}).([0-9]{1,2}).([0-9]{4})/","\${3}\${2}\${1}000000", $ondatez);
		$end = preg_replace ("/([0-9]{1,2}).([0-9]{1,2}).([0-9]{4})/","\${3}\${2}\${1}235959", $ondatek);
		$where = "AND (n.article_date > ".(float)$start." AND n.article_date < ".(float)$end.")";
	} elseif ($search_mode == 3) { // ID
		$select_category = "";
		$select_author = "";
		$where = "AND n.article_id=".(integer)$search_id;
	} else {
		$select_category = "";
		$select_author = "";
		$where = "";
	}
	$amount = mysql_query("SELECT COUNT(*) FROM $db_articles AS n WHERE n.article_publish < 2 AND n.article_public=".(integer)$articles_public." $where $select_category $select_author AND n.article_parent_id = 0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_fetch_array($amount);
	
	//Timto nastavime pocet prispevku na strance
	$m = 0;// nastaveni iterace
	if (empty($page)) {$page = 1;} // i kdyz je promenna $page prazdna nastavi se pocet starnek na 1
	//$hits=20; //Zde se nastavuje pocet prispevku
	$stw2 = ($num[0]/$hits);
	$stw2 = (integer)$stw2;
	if ($num[0]%$hits > 0) {$stw2++;}
	$np = $page+1;
	$pp = $page-1;
	if ($page == 1) { $pp = 1; }
	if ($np > $stw2) { $np = $stw2;} 
	
	$sp = ($page-1)*$hits;
	$ep = ($page-1)*$hits+$hits;
	
	$limit = "LIMIT ".(integer)$sp.", ".(integer)$hits."";
	
	$res = mysql_query("SELECT n.article_id, n.article_date, n.article_date_on, n.article_date_off, n.article_category_id, n.article_text, n.article_headline, n.article_perex, n.article_author_id, n.article_user_use, n.article_top_article, 
	n.article_best_article, n.article_user_open, n.article_publish, n.article_chapter_name, n.article_lang, n.article_views, n.article_poll, n.article_channel_id, a.admin_id, a.admin_nick 
	FROM $db_articles AS n, 
	$db_admin AS a 
	WHERE a.admin_id=n.article_author_id AND n.article_publish < 2 AND n.article_public=".(integer)$articles_public." $where AND n.article_parent_id = 0 
	$select_category 
	AND article_category_sub_id = 0
	$select_author 
	ORDER BY $podle_db $ser $limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	echo Menu();
	
	echo "	<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr>\n";
	echo "			<td width=\"857\">";
	echo "				<div style=\"float:left;padding:3px 3px 3px 3px;\">";
	echo "					<form action=\"modul_articles.php?action=showmain&amp;project=".$_SESSION['project']."\" method=\"post\" name=\"formSearch\">\n";
	echo 					_ARTICLES_SEARCH_AS;
	echo "  				<input name=\"search_mode\" id=\"Category\" type=\"radio\" value=\"1\" "; if ($search_mode == 1 || $search_mode == ""){ echo "checked=\"checked\"";} echo "/>\n";
	echo "					<label for=\"Category\">"._ARTICLES_SEARCH_AS_CAT."</label>\n";
	echo "  				<input name=\"search_mode\" id=\"Date\" type=\"radio\" value=\"2\" "; if ($search_mode == 2){ echo "checked=\"checked\"";} echo " />\n";
	echo "  				<label for=\"Date\">"._ARTICLES_SEARCH_AS_DATE."</label>\n";
	echo "  				<input name=\"search_mode\" id=\"ID\" type=\"radio\" value=\"3\" "; if ($search_mode == 3){ echo "checked=\"checked\"";} echo " />\n";
	echo "  				<label for=\"ID\">"._ARTICLES_SEARCH_AS_ID."</label>\n";
	echo "				</div>";
	echo "				<div style=\"float:right;padding:3px 3px 3px 3px;\">";
	echo "					<label for=\"hits\">"._CMN_HITS."</label>\n";
	echo "					<input type=\"text\" name=\"hits\" size=\"2\" maxlength=\"4\" value=\"".$hits."\">\n";
	echo "				</div>";
	echo "				<br clear=\"all\"/>";
	echo "				<div id=\"search_date\" style=\"float:left;padding:3px 3px 3px 3px;\">";
	echo "					<script language=\"javascript\">\n";
	echo "						var StartDate = new ctlSpiffyCalendarBox(\"StartDate\", \"formSearch\", \"ondatez\", \"btnDate1\",\"".$ondatez."\",scBTNMODE_CUSTOMBLUE);\n";
	echo "						var EndDate = new ctlSpiffyCalendarBox(\"EndDate\", \"formSearch\", \"ondatek\", \"btnDate2\",\"".$ondatek."\",scBTNMODE_CUSTOMBLUE);\n";
	echo "					</script>\n";
	echo "					"._ADMIN_FROM." <script language=\"javascript\">StartDate.writeControl(); StartDate.dateFormat=\"dd.MM.yyyy\";</script> "._ADMIN_TO."";
	echo "	   				<script language=\"javascript\">EndDate.writeControl(); EndDate.dateFormat=\"dd.MM.yyyy\";</script>";
	echo "				<br clear=\"all\"/>";
	echo "				</div>";
	echo "				<div id=\"search_c\" style=\"float:left;padding:3px 3px 3px 3px;\">";
							// Select category
							echo EdenCategorySelect($search_c, "articles", "search_c", _ARTICLES_SEL_CAT);
	echo "					<select name=\"search_a\">\n";
								$res8 = mysql_query("SELECT admin_id, admin_nick, admin_status FROM $db_admin WHERE admin_status='admin' ORDER BY admin_nick ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								echo "<option value=\"\" selected>"._ARTICLES_SEL_AUT."</option>\n";
								while ($ar8 = mysql_fetch_array($res8)){
									echo "<option value=\"".$ar8['admin_id']."\" ";
									if ($ar8['admin_id'] == $ar['article_author_id']) { echo "selected=\"selected\"";}
									echo ">".$ar8['admin_nick']."</option>\n";
								}
	echo "					</select>\n";
	echo "				<br clear=\"all\"/>";
	echo "				</div>";
	echo "				<div id=\"search_id\" style=\"float:left;padding:3px 3px 3px 3px;\">";
	echo "					<input type=\"text\" name=\"search_id\" size=\"10\" maxlength=\"8\" value=\"".$search_id."\">&nbsp;\n";
	echo "				</div>";
	echo "				<br clear=\"all\"/>";
	echo "				<div style=\"float:left;padding:3px 3px 3px 3px;\">";
	echo "					<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "					<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\"><br />\n";
	echo "					<input type=\"submit\" value=\""._CMN_SUBMIT."\" class=\"eden_button\">\n";
	echo "				</div>";
	echo "				</form>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	</table>\n";
	echo "	<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr class=\"popisky\">\n";
	echo "			<td width=\"140\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "			<td width=\"45\" align=\"center\" "; if ($podle == "article_id"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">ID</span></td>\n";
	echo "			<td width=\"12\">CH</td>\n";
	echo "			<td width=\"450\" align=\"center\" "; if ($podle == "article_headline"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">"._ARTICLES_HEADLINE."</span></td>\n";
	echo "			<td align=\"center\"><span class=\"nadpis-boxy\">"._ARTICLES_AUTHOR."</span></td>\n";
	echo "			<td align=\"center\" "; if ($podle == "article_views"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">"._ARTICLES_ZOB."</span></td>\n";
	echo "			<td align=\"center\" "; if ($podle == "article_date"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">"._ARTICLES_DATE_FROM."</span></td>\n";
	echo "			<td width=\"100\" align=\"center\" "; if ($podle == "article_category_id"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">"._ARTICLES_CATEGORY."</span></td>\n";
	echo "		</tr>\n";
	echo "		<tr class=\"popisky\">\n";
	echo "			<td width=\"140\">&nbsp;</td>\n";
	echo "			<td width=\"45\" align=\"center\" "; if ($podle == "article_id"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_articles.php?project=".$_SESSION['project']."&podle=article_id&ser=asc&amp;hits=".$hits."&section=".$section."&search_a=".$search_a."&search_c=".$search_c."\"><img src=\"images/asc_"; if ($podle == "article_id" && $_GET['ser'] == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_articles.php?project=".$_SESSION['project']."&podle=article_id&ser=desc&amp;hits=".$hits."&section=".$section."&search_a=".$search_a."&search_c=".$search_c."\"><img src=\"images/des_"; if ($podle == "article_id" && $_GET['ser'] == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "			<td width=\"12\">&nbsp;</td>\n";
	echo "			<td width=\"450\" align=\"center\" "; if ($podle == "article_headline"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_articles.php?project=".$_SESSION['project']."&podle=article_headline&ser=asc&amp;hits=".$hits."&section=".$section."&search_a=".$search_a."&search_c=".$search_c."\"><img src=\"images/asc_"; if ($podle == "article_headline" && $_GET['ser'] == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_articles.php?project=".$_SESSION['project']."&podle=article_headline&ser=desc&amp;hits=".$hits."&section=".$section."&search_a=".$search_a."&search_c=".$search_c."\"><img src=\"images/des_"; if ($podle == "article_headline" && $_GET['ser'] == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "			<td width=\"100\" align=\"center\">&nbsp;</td>\n";
	echo "			<td align=\"center\" "; if ($podle == "article_views"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_articles.php?project=".$_SESSION['project']."&podle=article_views&ser=asc&amp;hits=".$hits."&section=".$section."&search_a=".$search_a."&search_c=".$search_c."\"><img src=\"images/asc_"; if ($podle == "article_views" && $_GET['ser'] == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_articles.php?project=".$_SESSION['project']."&podle=article_views&ser=desc&amp;hits=".$hits."&section=".$section."&search_a=".$search_a."&search_c=".$search_c."\"><img src=\"images/des_"; if ($podle == "article_views" && $_GET['ser'] == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "			<td align=\"center\" "; if ($podle == "article_date"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_articles.php?project=".$_SESSION['project']."&podle=article_date&ser=asc&amp;hits=".$hits."&section=".$section."&search_a=".$search_a."&search_c=".$search_c."\"><img src=\"images/asc_"; if ($podle == "article_date" && $_GET['ser'] == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_articles.php?project=".$_SESSION['project']."&podle=article_date&ser=desc&amp;hits=".$hits."&section=".$section."&search_a=".$search_a."&search_c=".$search_c."\"><img src=\"images/des_"; if ($podle == "article_date" && $_GET['ser'] == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "			<td width=\"100\" align=\"center\" "; if ($podle == "article_category_id"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_articles.php?project=".$_SESSION['project']."&podle=article_category_id&ser=asc&amp;hits=".$hits."&section=".$section."&search_a=".$search_a."&search_c=".$search_c."\"><img src=\"images/asc_"; if ($podle == "article_category_id" && $_GET['ser'] == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_articles.php?project=".$_SESSION['project']."&podle=article_category_id&ser=desc&amp;hits=".$hits."&section=".$section."&search_a=".$search_a."&search_c=".$search_c."\"><img src=\"images/des_"; if ($podle == "article_category_id" && $_GET['ser'] == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "		</tr>\n";
	$i=1;
	while ($ar = mysql_fetch_array($res)){
		$m++;
		//$datum = FormatTimestamp($ar['article_date']);
		//$datum_h = FormatTimestamp($ar['article_date']);
		$datumed = FormatTimestamp($ar['article_date_on']);
		$showtime = date("YmdHis");
		$res2 = mysql_query("SELECT category_id, category_admin, category_name, category_parent FROM $db_category WHERE category_id=".(integer)$ar['article_category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar2 = mysql_fetch_array($res2);
		if ($ar2['category_parent']){
			$res22 = mysql_query("SELECT category_name FROM $db_category WHERE category_id=".(integer)$ar2['category_parent']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar22 = mysql_fetch_array($res22);
		}
		// Zjisteni poctu obrazku v databazi k jednotlivemu prispevku
		$res3 = mysql_query("SELECT COUNT(*) AS suma FROM $db_articles_images WHERE articles_images_article_id=".(integer)$ar['article_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar3 = mysql_fetch_array($res3);
		
		// Zjisteni poctu obrazku, na ktere je odkazovano z prispevku
		$picture = substr_count($ar['article_text'], "edencms/img_articles");
		
		// Zjisteni poctu komentářů
		$res4 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar['article_id']." AND comment_modul='article'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num4 = mysql_fetch_array($res4);
		
		/* Kanal */
		$res_article_channel = mysql_query("SELECT article_channel_title, article_channel_image FROM $db_articles_channel WHERE article_channel_id=".(integer)$ar['article_channel_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_article_channel = mysql_fetch_array($res_article_channel);
		
		$hlavicka = ShortText($ar['article_headline'],60);
		
		// Zabezpeceni zobrazeni moznosti jen vyvolenym
		if ($_GET['action'] == "open" & $_GET['id'] == $ar['article_id']) {$command = "close";} else {$command = "open";}
		$admini = explode (" ", $ar2['category_admin']);
		$num02 = count($admini);
		
		$name1 = explode ("]", $ar22['category_name']);
		$name2 = explode ("]", $ar2['category_name']);
		if ($name1[1] == ""){$name1 = $name1[0];} else {$name1 = $name1[1];}
		if ($name2[1] == ""){$name2 = $name2[0];} else {$name2 = $name2[1];}
		
		if ($_SESSION['login'] == ""){$admini02 = "FALSE";} else {$admini02 = in_array($_SESSION['login'], $admini);}
		
 		/* Spocitani kolik je logovanych zmen v novince */
 		$res9 = mysql_query("SELECT COUNT(*) FROM $db_eden_log WHERE log_article_id=".(integer)$ar['article_id']." AND log_action=3") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num9 = mysql_fetch_array($res9);
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "		<td width=\"140\" valign=\"top\">";
					$res5 = mysql_query("SELECT article_id, article_date, article_date_edit, article_user_use, article_category_id, article_parent_id, article_chapter_name, article_views 
					FROM $db_articles AS n WHERE n.article_parent_id=".$ar['article_id']." AND n.article_publish<2 $select_category 
					ORDER BY n.article_chapter_number ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$num5 = mysql_num_rows($res5);
					// Kdyz clanek obsahuje vice kapitol zobrazi se tlacitko pro otevreni, jinak se zobrazi prazdne misto
					if ($num5 >= 1){ echo "<a href=\"modul_articles.php?action=".$command."&amp;id=".$ar['article_id']."&amp;id2=".$ar['article_category_id']."&amp;project=".$_SESSION['project']."&podle=".$podle."&ser=".$ser."&amp;hits=".$hits."&search_a=".$search_a."&search_c=".$search_c."&amp;page=".$_GET['page']."\"><img src=\"images/sys_".$command.".gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""; if ($command == "open"){echo _CMN_OPEN;} else {echo _CMN_CLOSE;} echo "\"></a>"; } else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">";}
					if ((CheckPriv($groups_priv_article_edit) == 1 || $admini02 == "TRUE")	&& (CheckPriv("groups_article_all_edit") == 1 ||	$_SESSION['loginid'] == $ar['article_author_id'])){
						/* Pokud nekdo edituje, nebo maze tak se nezobrazi ikona pro editaci a mazani */ 
						if ($ar['article_user_use'] == "0" || $ar['article_user_use'] == $_SESSION['loginid']){
							echo " <a href=\"modul_articles.php?action=article_edit&amp;id=".$ar['article_id']."&amp;project=".$_SESSION['project']."&search_c=".$search_c."&search_a=".$search_a."&act=".$act."&amp;page=".$_GET['page']."&podle=".$podle."&ser=".$ser."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";
						} else { 
							if (CheckPriv("groups_article_all_kill_user") == 1){
								echo "<a href=\"modul_articles.php?action=kill_use_article&amp;id=".$ar['article_id']."&amp;project=".$_SESSION['project']."&search_c=".$search_c."&search_a=".$search_a."&act=".$act."&amp;page=".$_GET['page']."&podle=".$podle."&ser=".$ser."\"><img src=\"images/sys_killuse.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._ARTICLES_KILL_USE." - ".GetUserName($ar['article_user_use'])." - ".FormatTimestamp($ar['article_user_open'],"d.m.Y H:i:s")."\"></a>";
							}
						}
					} else {
						echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"22\" border=\"0\" alt=\"\">";
					}
					if ((CheckPriv($groups_priv_article_del) == 1 && $_SESSION['loginid'] == $ar['article_author_id']) || (CheckPriv("groups_article_all_del") == 1)){
						if ($ar['article_user_use'] == "0" || $ar['article_user_use'] == $_SESSION['loginid']){
							/* Pokud nekdo edituje, nebo maze tak se nezobrazi ikona pro editaci a mazani */ 
							echo ' <a href="modul_articles.php?action=article_del&amp;id='.$ar['article_id'].'&amp;id2='.$ar['article_category_id'].'&amp;project='.$_SESSION['project'].'&search_c='.$search_c.'&search_a='.$search_a.'&act='.$act.'&amp;page='.$_GET['page'].'&podle='.$podle.'&ser='.$ser.'"><img src="images/sys_del.gif" height="18" width="18" border="0" alt="'._CMN_DEL.'"></a>';
						}
					} else {
						echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"22\" border=\"0\" alt=\"\">"; 
					}
					if (CheckPriv("groups_article_changes") == 1){echo " <a href=\"modul_articles.php?action=zmeny&amp;id=".$ar['article_id']."&amp;id2=".$ar['article_category_id']."&amp;project=".$_SESSION['project']."&search_c=".$search_c."&search_a=".$search_a."&act=".$act."&amp;page=".$_GET['page']."&podle=".$podle."&ser=".$ser."\"><img src=\"images/sys_zmeny.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._ARTICLES_ZMENY." - ".$num9[0]."\" title=\""._ARTICLES_ZMENY." - ".$num9[0]."\"></a>";}
					if (CheckPriv("groups_comments_change") == 1 || $admini02 == "TRUE"){echo " <a href=\"modul_articles.php?action=komentar&amp;id=".$ar['article_id']."&amp;id2=".$ar['article_category_id']."&amp;project=".$_SESSION['project']."&search_c=".$search_c."&search_a=".$search_a."&act=".$act."&amp;page=".$_GET['page']."&podle=".$podle."&ser=".$ser."\" title=\""._NUMCOM."\"><span style=\"font-size:10px; font-weight: bold;\">".$num4[0]."</span></a>";}
		echo "		</td>\n";
		echo "		<td width=\"45\" valign=\"top\">".$ar['article_id']."</td>\n";
		echo "		<td width=\"12\" valign=\"top\">"; if ($ar_article_channel['article_channel_image'] != ""){ echo "<img src=\"".$url_articles_channels.$ar_article_channel['article_channel_image']."\" width=\"12\" height=\"12\" title=\"".$ar_article_channel['article_channel_title']."\" />";} echo "</td>\n";
		echo "		<td width=\"450\" valign=\"top\">"; if ($ar['article_best_article'] == "1"){echo "<span style=\"color:#0000ff\" title=\""._ARTICLES_BEST_ARTICLE."\"><strong>B</strong></span> ";}	if ($ar['article_top_article'] == "1"){echo "<span class=\"red\"><strong>"._ARTICLES_TOP_ARTICLE."</strong></span> ";} if ($ar['article_publish'] == "0"){echo "<img src=\"images/sys_no.gif\" alt=\""._CMN_DONT_PUBLISH."\" title=\""._CMN_DONT_PUBLISH."\" width=\"12\" height=\"12\" border=\"0\"> ";} if ($ar['article_user_use'] != "0" && $ar['article_user_use'] != $_SESSION['loginid']){echo "<img src=\"images/sys_use.gif\" width=\"18\" height=\"18\" border=\"0\" alt=\""._ARTICLES_USE.GetUserName($ar['article_user_use'])." [ID ".$ar['article_user_use']."]\" title=\""._ARTICLES_USE.GetUserName($ar['article_user_use'])." [ID ".$ar['article_user_use']."]\">";} echo $hlavicka; if ($_GET['action'] == "open" & $_GET['id'] == $ar['article_id']) {echo "<br>".$ar['article_chapter_name'];} echo "</td>\n";
		echo "		<td width=\"100\" align=\"left\" valign=\"top\"><a href=\"#\" style=\"font-size:9px\" title=\"".$ar['admin_nick']."\">".ShortText($ar['admin_nick'], 13)."</a><br><span class=\"article_undertext\">".$ar['article_lang']." | <a href=\"#\" title=\"images in text\">".$picture."</a> | <a href=\"#\" title=\"images in database\">".$ar3['suma']."</a>"; if($ar['article_poll'] != 0){echo " | poll";} echo "</span></td>\n";
		echo "		<td align=\"right\" valign=\"top\">".$ar['article_views']."</td>\n";
		echo "		<td align=\"center\" valign=\"top\">"; if ( $showtime < $ar['article_date_on'] || $showtime > $ar['article_date_off']){echo "<span class=\"red\">".$datumed."</span>";} else {echo $datumed;} echo "</td>\n";
		echo "		<td width=\"100\" align=\"left\" valign=\"top\">"."ID - ".$ar2['category_id']."<br>".$name2."</td>\n";
		echo "	</tr>";
		if ($_GET['action'] == "open" & $_GET['id'] == $ar['article_id']) {
			while($ar5 = mysql_fetch_array($res5)){
				// Zjisteni poctu obrazku v databazi k jednotlivemu prispevku
				$res7 = mysql_query("SELECT COUNT(*) FROM $db_articles_images WHERE articles_images_article_id=".(integer)$ar5['article_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$num7 = mysql_fetch_array($res7);
				//$datum = FormatTimestamp($ar5['article_date']);
				//$datum_h = FormatTimestamp($ar5['article_date']);
				$datumed = FormatTimestamp($ar5['article_date_edit']);
				echo "<tr bgcolor=\"#EEEEEE\">";
				echo "	<td width=\"120\"><img src=\"images/a_bod.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">";
						if (CheckPriv($groups_priv_article_edit) == 1 || $admini02 == "TRUE" || $_SESSION['loginid'] == $ar['article_author_id']){ 
							/*Pokud nekdo edituje, nebo maze tak se nezobrazi ikona pro editaci a mazani*/
							if ($ar5['article_user_use'] == "0" || $ar5['article_user_use'] == $_SESSION['loginid']){
								echo " <a href=\"modul_articles.php?action=article_edit&amp;id=".$ar5['article_id']."&amp;id2=".$ar5['article_category_id']."&parent_chap=".$ar5['article_parent_id']."&amp;project=".$_SESSION['project']."&search_c=".$search_c."&search_a=".$search_a."&act=".$act."&amp;page=".$_GET['page']."&podle=".$podle."&ser=".$ser."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";
							}
						}
				echo "	</td>\n";
				echo "	<td>".$ar5['article_id']."</td>\n";
				echo "	<td>"; if ($ar5['article_user_use'] != "0" && $ar5['article_user_use'] != $_SESSION['loginid']){echo "<img src=\"images/sys_use.gif\" width=\"18\" height=\"18\" border=\"0\" alt=\"".$ar5['article_user_use']."\">";} echo $ar5['article_chapter_name']."</td>\n";
				echo "	<td align=\"center\" valign=\"top\" width=\"50\">".$picture."&nbsp;&nbsp;".$num7[0]."&nbsp;&nbsp;</td>\n";
				echo "	<td align=\"right\" valign=\"top\">".$ar5['article_views']."</td>\n";
				echo "	<td align=\"center\" width=\"70\" valign=\"top\">".$datumed."</td>\n";
				echo "	<td align=\"left\" width=\"150\" valign=\"top\">ID - ".$ar2['category_id']."<br><strong>".$name1."&raquo;</strong> ".$name2."</td>\n";
				echo "</tr>";
				}
		}
		if ($_GET['action'] == "zmeny" & $_GET['id'] == $ar['article_id']){
			// Vypis logovanych zmen v novince
			$res_log = mysql_query("SELECT log_date, log_admin_id, INET_NTOA(log_ip) AS log_ip FROM $db_eden_log WHERE log_article_id=".(integer)$_GET['id']." AND log_action=3 ORDER BY log_date DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while ($ar_log = mysql_fetch_array($res_log)){
				echo "<tr bgcolor=\"#EEEEEE\">";
				echo "	<td colspan=\"8\">".formatDatetime($ar_log['log_date'],"d.m.Y H:i:s")."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ID ".$ar_log['log_admin_id']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".GetUserName($ar_log['log_admin_id'])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$ar_log['log_ip']."</td>";
				echo "</tr>";
			}
		}
		$i++;
	}
	echo "</table>";
	// Pokud je novinek vice, nezli se vejde na 1 stranku zobrazi se jejich pocitani a prechazeni mezi nima 
	if ($stw2 > 1){ 
		echo "	<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "<tr><td height=\"30\">";
		echo _CMN_SELECTPAGE; 
		//Zobrazeni cisla poctu stranek
		for ($i=1;$i<=$stw2;$i++) {
			if ($page==$i) {
				echo " <strong>".$i."</strong>";
			} else {
				echo " <a href=\"modul_articles.php?page=".$i."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."&search_c=".$search_c."&search_a=".$search_a."&podle=".$podle."&ser=".$ser."&search_mode=".$search_mode."&ondatez=".$ondatez."&ondatek=".$ondatek."\">".$i."</a> ";
			}
		}
		//Zobrazeni sipek s predchozimi a dalsimi strankami novinek
		if ($page > 1){
			echo "<center><a href=\"modul_articles.php?page=".$pp."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."&search_c=".$search_c."&search_a=".$search_a."&podle=".$podle."&ser=".$ser."&search_mode=".$search_mode."&ondatez=".$ondatez."&ondatek=".$ondatek."\">"._CMN_PREVIOUS."</a>";
		} else { 
			echo "<br>"._CMN_PREVIOUS;
		} 
		echo " <--|--> "; 
		if ($page == $stw2){
			echo _CMN_NEXT;
		} else {
			echo "<a href=\"modul_articles.php?page=".$np."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."&search_c=".$search_c."&search_a=".$search_a."&podle=".$podle."&ser=".$ser."&search_mode=".$search_mode."&ondatez=".$ondatez."&ondatek=".$ondatek."\">"._CMN_NEXT."</a></center>";
		}
		echo "</td></tr></table>";
	}
}
//********************************************************************************************************
//																										
//			 PRIDAVANI ZPRAV																			
//																										
//********************************************************************************************************
function AddArticle(){
	
	global $db_category,$db_articles,$db_articles_images,$db_articles_files,$db_language,$db_setup;
	global $eden_cfg;
	global $ftp_path_articles;
	global $article_path,$url_articles;
	
	if (isset($_GET['search_a'])){$search_a = $_GET['search_a'];}elseif (isset($_POST['search_a'])){$search_a = $_POST['search_a'];}
	if (isset($_GET['search_c'])){$search_c = $_GET['search_c'];}elseif (isset($_POST['search_c'])){$search_c = $_POST['search_c'];}
	if (isset($_GET['act'])){$act = $_GET['act'];}elseif (isset($_POST['act'])){$act = $_POST['act'];}
	
	if ($act == "articles_public"){
		$groups_priv_article_edit = "groups_article_public_edit";
		$groups_priv_article_del = "groups_article_public_del";
		$groups_priv_article_add = "groups_article_public_add";
		$articles_public = 1;
	} else {
		$groups_priv_article_edit = "groups_article_edit";
		$groups_priv_article_del = "groups_article_del";
		$groups_priv_article_add = "groups_article_add";
		$articles_public = 0;
	}
	
	$res_setup = mysql_query("SELECT setup_eden_editor_cleaner, setup_eden_editor_purificator FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$article_date = date("YmdHis");
	if ($_GET['action'] == "article_edit"){
		if ($_GET['parent_chap'] != "0" && $_GET['parent_chap'] != ""){$nid = $_GET['parent_chap'];} else {$nid = $_GET['id'];}
		$res = mysql_query("SELECT n.*, c.category_admin FROM $db_articles AS n JOIN $db_category AS c ON c.category_id=n.article_category_id WHERE n.article_id=".(integer)$nid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		if ($ar['article_user_use'] == "0" || $ar['article_user_use'] == $_SESSION['loginid']){
			// Zapsani k novince jmeno uzivatele, ktery otevrel novinku
			mysql_query("UPDATE $db_articles SET article_user_use=".(integer)$_SESSION['loginid'].", article_user_open=".(float)$article_date." WHERE article_id=".(integer)$nid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		} else {
			
			//ShowMain();
			exit;
		}
		$admini = explode (" ", $ar['category_admin']);
		if ($_SESSION['login'] == ""){$admini = "FALSE";} else {$admini = in_array($_SESSION['login'], $admini);}
	}
	
	// Provereni opravneni
	if ($_GET['action'] == "article_add"){
		if (CheckPriv($groups_priv_article_add) <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($_GET['action'] == "article_edit"){
		if (CheckPriv("groups_article_all_edit") <> 1){
			if ((CheckPriv($groups_priv_article_edit) == 1 && $_SESSION['loginid'] == $ar['article_author_id']) || (CheckPriv($groups_priv_article_edit) == 1 && $admini == "TRUE")){
				// Vstup povolen
			} else {
				// Vstup zamitnut
				echo _NOTENOUGHPRIV;ShowMain();exit;
			}
		}
	} else {
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	
	if ($_GET['action'] == "article_edit" && !isset($_POST['confirm'])){
		$id = $_GET['id'];
		EdiTor($id,$act,"article_edit");
	}
	/*
	*	Pridani novinky uplne od zacatku
	*/
	if ($_GET['action'] == "article_add" && $article_headline == ""){ 
		if (!isset($id)){$id = 0;}
		EdiTor($id,$act,$_GET['action']);
	}
}
/***********************************************************************************************************
*
*		EDITOR
*
*		$id		-	ID novinky
*		$act	-	articles / articles_public - Verejne nebo normalni novinky
*		$action	-	add / edit
*
***********************************************************************************************************/
function EdiTor($id,$act,$action){
	
	global $db_articles,$body,$db_category,$db_admin,$db_language,$db_poll_questions,$db_articles_channel;
	global $eden_cfg;
	global $url_articles;
	global $preview,$sa,$kat,$search_c,$search_a,$search_id,$search_mode;
	
	if ($act == "articles_public") {$articles_public = 1;} else {$articles_public = 0;}
	$picdbfilename = Cislo();
	
	if ($action != "article_add"){
		$res = mysql_query("SELECT * FROM $db_articles WHERE article_id=".(integer)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$article_chapter_number = $ar['article_chapter_number'];
		$article_parent_id = $ar['article_parent_id'];
	} else {
		$article_chapter_number = 1;
		$article_parent_id = 0;
	}
	if ($article_chapter_number > 1){
		$res_chap = mysql_query("SELECT article_headline, article_perex FROM $db_articles WHERE article_id=".(integer)$ar['article_parent_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_chap = mysql_fetch_array($res_chap);
	}
	$article_headline = stripslashes($ar['article_headline']);
	//if ($article_parent_id == 0) {$article_perex = stripslashes($ar['article_perex']);} else {$article_perex = stripslashes($ar_chap['article_perex']);}
	$article_perex = $ar['article_perex'];
	$article_body = $ar['article_text'];
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"0\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"; 
					switch ($action){
						case "add":
							if ($act == "articles_public"){echo _ARTICLES_PUBLIC." - "._ARTICLES_ARTICLE_ADD;} else {echo _ARTICLES." - "._ARTICLES_ARTICLE_ADD;}
						break;
						case "edit":
							if ($act == "articles_public"){echo _ARTICLES_PUBLIC." - "._ARTICLES_ARTICLE_EDIT." ID: ".$id;} else {echo _ARTICLES." - "._ARTICLES_ARTICLE_EDIT." ID: ".$id;}
						break;
					}
	echo "		</td>";
	echo "		<td>&nbsp;</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">&nbsp;<a href=\"modul_articles.php?project=".$_SESSION['project']."&amp;action=showmain&act=".$act."\">"._ARTICLES_ARTICLES_LIST."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	echo "			<a href=\"#\" onclick=\"window.open('show_image.php?project=".$_SESSION['project']."&amp;id=".$id."&act=".$act."','','menubar=no,resizable=yes,toolbar=no,status=yes,scrollbars=yes,width=650,height=600')\">"._ARTICLES_IMAGES."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	echo "			<a href=\"http://".$eden_cfg['misc_web']."index.php?lang=".$lang."&amp;action=clanek&amp;id=".$id."&amp;nhash=".$ar['article_hash']."\" target=\"_blank\">"._ARTICLES_FUNC_PREVIEW."</a>";
	echo "		</td>";
	echo "		<td align=\"right\">";
					//$res = mysql_query("SELECT * FROM $db_articles ORDER BY datum DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					//$num = mysql_num_rows($res);
					//echo _SUBMITED_ARTICLE.": ".$num;
	echo "		</td>";
	echo "	</tr>";
	if ($_GET['msg']){
		echo "<tr><td class=\"msg\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "</table>";
	echo "<table  cellspacing=\"2\" cellpadding=\"0\" border=\"0\">";
	echo "	<form action=\"sys_save.php?action="; 
				if ($id == 0 || $action == "article_add"){
					echo "article_add";
				} else {
					echo "article_edit";
				} 
				echo "&amp;id=".$id; 
				if ($article_chapter_number == 1 && $article_parent_id == 0){
					echo "&parent_chap=0";
				} elseif ($article_parent_id != 0){
					echo "&parent_chap=".$ar['article_parent_id'];
				} 
				echo "&amp;page=".$_GET['page']."&amp;kat=".$_GET['kat']."&amp;sa=".$_GET['sa']."&amp;podle=".$_GET['podle']."&amp;ser=".$_GET['ser']."\" enctype=\"multipart/form-data\" name=\"form1\" method=\"post\" ".$script_on_submit.">";
				
 				// Kdyz je pridavana nova kapitola zobrazi se 1, jinak se zobrazi poradi kapitoly
				if ($action == "article_add" && $id == 0){
					echo "<input type=\"hidden\" name=\"article_chapter_number\" value=\"1\">";
				} else {
					echo "<input type=\"hidden\" name=\"article_chapter_number\" value=\"".$article_chapter_number."\">";
				}
	echo "	<tr>";
	echo "		<td>";
	echo "			<table width=\"849\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\" class=\"editor_bg\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"left\" valign=\"top\">\n";
	echo "						<strong>"._ARTICLES_HEADLINE."</strong><br />";
			 					if ($article_parent_id == 0){
									echo "<input type=\"text\" name=\"article_headline\" value=\""; if ($action == "article_edit"){ echo $article_headline;} echo "\" size=\"60\"  maxlength=\"250\">&nbsp;&nbsp;\n";
									
									if ($action != "article_add"){
										$reskap = mysql_query("SELECT article_id, article_chapter_number, article_chapter_name FROM $db_articles WHERE article_id=".(float)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
										$arkap = mysql_fetch_array($reskap);
										echo "<br />".$arkap['article_chapter_number'].". <a href=\"modul_articles.php?project=".$_SESSION['project']."&amp;action=article_edit&amp;id=".$arkap['article_id']."\" style=\"color:#0000FF;\">".$arkap['article_chapter_name']."</a>";
										$reskap2 = mysql_query("SELECT article_id, article_chapter_number, article_chapter_name FROM $db_articles WHERE article_parent_id=".(float)$arkap['article_id']." ORDER BY article_chapter_number ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
										$numkap2 = mysql_num_rows($reskap2);
										echo "<br />";
										$i=0;
										while($arkap2 = mysql_fetch_array($reskap2)){
											echo $arkap2['article_chapter_number'].". "; if ($numkap2 >= $i){echo "<a href=\"modul_articles.php?project=".$_SESSION['project']."&amp;action=article_edit&amp;id=".$arkap2['article_id']."&parent_chap=".$arkap['article_id']."\">".$arkap2['article_chapter_name']."</a>";} else {echo $arkap2['article_chapter_name'];}
											echo "<br />";
											$i++;
										}
									}
								} else {
									$article_headline = stripslashes($ar_chap['article_headline']);
									$reskap = mysql_query("SELECT article_id, article_chapter_number, article_chapter_name FROM $db_articles WHERE article_id=".(float)$ar['article_parent_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
									$arkap = mysql_fetch_array($reskap);
									echo "<strong class=\"red\">".$article_headline."</strong><br />";
									echo $arkap['article_chapter_number'].". <a href=\"modul_articles.php?project=".$_SESSION['project']."&amp;action=article_edit&amp;id=".$arkap['article_id']."\" style=\"color:#0000FF;\">".$arkap['article_chapter_name']."</a>";
									$reskap2 = mysql_query("SELECT article_id, article_chapter_number, article_chapter_name FROM $db_articles WHERE article_parent_id=".(float)$arkap['article_id']." ORDER BY article_chapter_number ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
									$numkap2 = mysql_num_rows($reskap2);
									echo "<br />";
									while($arkap2 = mysql_fetch_array($reskap2)){
										echo $arkap2['article_chapter_number'].". "; if ($article_chapter_number != $arkap2['article_chapter_number']){echo "<a href=\"modul_articles.php?project=".$_SESSION['project']."&amp;action=article_edit&amp;id=".$arkap2['article_id']."&parent_chap=".$arkap['article_id']."\" style=\"color:#0000FF;\">".$arkap2['article_chapter_name']."</a>";} else {echo $arkap2['article_chapter_name'];}
										echo "<br />";
									}
									echo "<br /><br />";
								}
								echo "<br /><br />";
								echo "<strong>"._ARTICLES_CHAP_NAME."</strong><br />";
								echo "<input type=\"text\" name=\"article_chapter_name\" value=\""; if ($action == "article_edit"){ echo $ar['article_chapter_name'];} echo "\" size=\"60\"><br /><br />";
								if ($article_parent_id != 0){
									echo "<strong>"._ARTICLES_CHAP_NUM."</strong>";
									if (CheckPriv("groups_article_all_edit") == 1){
									echo "<select name=\"chapter_num\">";
										for ($i=1;$i<10;$i++){
											echo "<option value=\"".$i."\" ";	if ($i == $article_chapter_number) { echo "selected=\"selected\"";} echo ">".$i."</option>";
										}
									echo "</select>";
									} else {
										echo $article_chapter_number;
									}
								}
								if ($article_parent_id == 0){
									echo "<strong>"._ARTICLES_CATEGORY."</strong><br />";
									
									// Select category
									echo EdenCategorySelect($ar['article_category_id'], "articles", "article_category_id", 0);
								   	echo "<br /><br />";
									
									echo "<strong>"._ARTICLES_CHANNELS."</strong>\n<br />";
									$res_channel = mysql_query("SELECT article_channel_id, article_channel_title FROM $db_articles_channel ORDER BY article_channel_title ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
									echo "<select name=\"article_channel\">\n";
									echo "<option value=\"0\" "; if ($_GET['action'] == "article_add" || $ar['article_channel_id'] == 0) { echo "selected=\"selected\"";} echo">"._ARTICLES_CHANNEL_SELECT."</option>\n";
									while ($ar_channel = mysql_fetch_array($res_channel)){
										echo "<option value=\"".$ar_channel['article_channel_id']."\" ";
												if ($ar_channel['article_channel_id'] == $ar['article_channel_id']) { echo "selected=\"selected\"";}
												echo">".$ar_channel['article_channel_title']."</option>\n";
									}
									echo "</select>\n";
									echo "<br />\n<br />\n";
									if ($ar['article_img_1'] != "" && $article_parent_id == 0){echo "<img src=\"".$url_articles.$ar['article_img_1']."\" border=\"0\" alt=\"\"><br>"; }
									echo "<strong>"._ARTICLES_ILU_PIC." ".GetSetupImageInfo("article_1","width")."x".GetSetupImageInfo("article_1","height")."</strong><br>";
									echo "<input type=\"file\" name=\"img_1\" size=\"20\"><img src=\"images/a_bod.gif\" width=\"10\" height=\"5\" border=\"0\" alt=\"\"><br><br>";
									if ($ar['article_img_2'] != "" && $article_parent_id == 0){echo "<img src=\"".$url_articles.$ar['article_img_2']."\" border=\"0\" alt=\"\"><br>"; }
									echo "<strong>"._ARTICLES_ILU_PIC." ".GetSetupImageInfo("article_2","width")."x".GetSetupImageInfo("article_2","height")."</strong><br>";
									echo "<input type=\"file\" name=\"img_2\" size=\"20\"><img src=\"images/a_bod.gif\" width=\"10\" height=\"5\" border=\"0\" alt=\"\"><br><br>";
								} else {
									echo "<input type=\"hidden\" name=\"article_category_id\" value=\"".$ar['category_name']."\">";
									echo "<br /><br />";
								}
								echo "</td>\n";
								
								
								echo "<td width=\"250\" align=\"left\" valign=\"top\">";
									if ($article_parent_id == 0){
								   		echo "<input type=\"checkbox\" name=\"article_publish\" value=\"1\" "; if ($ar['article_publish'] == "1" || $ar['article_publish'] == ""){echo "CHECKED";} echo ">"._CMN_PUBLISH."<br /><br />\n";
										
										echo "<strong>"._ARTICLES_TAGS."</strong><br />";
										echo "<select name=\"article_tags[]\" multiple=\"multiple\" size=\"8\" style=\"width: 200px\">";
											$res_tags = mysql_query("SELECT tag_id, tag_name FROM "._DB_TAGS." ORDER BY tag_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
											while ($ar_tags = mysql_fetch_array($res_tags)){
												$res_article_tags = mysql_query("SELECT COUNT(*) AS num FROM "._DB_ARTICLES_TAGS." WHERE articles_article_id = ".(integer)$id." AND articles_tag_id = ".(integer)$ar_tags['tag_id']."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
												$ar_article_tags = mysql_fetch_array($res_article_tags);
												echo "<option value=\"".$ar_tags['tag_id']."\" ";
												if ($ar_article_tags['num'] > 0) { echo "selected=\"selected\"";}
												echo">".$ar_tags['tag_name']."</option>";
											}
										echo "</select>";
										
									} else {
										echo "<input type=\"hidden\" name=\"article_publish\" value=\"1\">";
									}
									echo "<br /><br />";
									if (CheckPriv("groups_change_redactor") == 1){
										echo "<strong>"._ARTICLES_FUNC_CHANGE_REDACTOR."</strong><br />";
										echo "<select name=\"zmena_redaktora\">";
											$reszr = mysql_query("SELECT admin_id, admin_uname FROM $db_admin WHERE admin_status='admin' ORDER BY admin_uname ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
											while ($arzr = mysql_fetch_array($reszr)){
												echo "<option value=\"".$arzr['admin_id']."\" ";
												if ($arzr['admin_id'] == $ar['article_author_id'] || ($_GET['action'] == "article_add" && $arzr['admin_id'] == $_SESSION['loginid'])) { echo "selected=\"selected\"";}
												echo">".$arzr['admin_uname']."</option>";
											}
										echo "</select>";
										echo "<br /><br />";
									}
								echo "</td>\n";
								
								echo "<td width=\"230\" align=\"left\" valign=\"top\">";
								// Pokud budeme pridavat kapitoly, neni treba zobrazit nektera nastaveni
								if ($article_parent_id == 0){
									echo "<strong>"._ARTICLES_DATE_ON."</strong><br />";
									if ($id == 0){
										$article_date_on = formatTimeS(time());
										echo "<script language=\"javascript\">";
										echo "var StartDate = new ctlSpiffyCalendarBox(\"StartDate\", \"form1\", \"article_date_start\", \"btnDate1\",\"".$article_date_on[1].".".$article_date_on[2].".".$article_date_on[3]."\",scBTNMODE_CUSTOMBLUE);";
										echo "var EndDate = new ctlSpiffyCalendarBox(\"EndDate\", \"form1\", \"article_date_end\", \"btnDate2\",\"01.01.2050\",scBTNMODE_CUSTOMBLUE);";
										echo "</script>";
										echo "<script language=\"javascript\">StartDate.writeControl(); StartDate.dateFormat=\"dd.MM.yyyy\";</script> <strong>-</strong><select name=\"article_date_on_4\">";
										for ($i=0;$i<=23;$i++){
											echo "<option value=\"".$i."\" "; if ($article_date_on[4] == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
										}
										echo "</select><strong>:</strong><select name=\"article_date_on_5\">";
										for ($i=0;$i<=59;$i++){
											echo "<option value=\"".$i."\" "; if ($article_date_on[5] == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
										}
										echo "</select><br><br>";
										echo "<strong>"._ARTICLES_DATE_OFF."</strong><br>";
										echo "<script language=\"javascript\">EndDate.writeControl(); EndDate.dateFormat=\"dd.MM.yyyy\";</script> <strong>-</strong><select name=\"article_date_off_4\">";
										for ($i=0;$i<=23;$i++){
											echo "<option value=\"".$i."\" "; if (0 == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
										}
										echo "</select><strong>:</strong><select name=\"article_date_off_5\">";
										for ($i=0;$i<=59;$i++){
											echo "<option value=\"".$i."\" "; if (1 == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
										}
										echo "</select><br><br><br>";
									} else {
										$article_date_on = $ar['article_date_on'];
										$article_date_off = $ar['article_date_off'];
										echo "<script language=\"javascript\">";
										echo "var StartDate = new ctlSpiffyCalendarBox(\"StartDate\", \"form1\", \"article_date_start\", \"btnDate1\",\"".$article_date_on[6].$article_date_on[7].".".$article_date_on[4].$article_date_on[5].".".$article_date_on[0].$article_date_on[1].$article_date_on[2].$article_date_on[3]."\",scBTNMODE_CUSTOMBLUE);";
										echo "var EndDate = new ctlSpiffyCalendarBox(\"EndDate\", \"form1\", \"article_date_end\", \"btnDate2\",\"".$article_date_off[6].$article_date_off[7].".".$article_date_off[4].$article_date_off[5].".".$article_date_off[0].$article_date_off[1].$article_date_off[2].$article_date_off[3]."\",scBTNMODE_CUSTOMBLUE);";
										echo "</script>";
										echo "<script language=\"javascript\">StartDate.writeControl(); StartDate.dateFormat=\"dd.MM.yyyy\";</script> <strong>-</strong> <select name=\"article_date_on_4\">";
										for ($i=0;$i<=23;$i++){
											echo "<option value=\"".$i."\" "; if ($article_date_on[8].$article_date_on[9] == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
										}
										echo "</select><select name=\"article_date_on_5\">";
										for ($i=0;$i<=59;$i++){
											echo "<option value=\"".$i."\" "; if ($article_date_on[10].$article_date_on[11] == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
										}
										echo "</select><br><br>";
									 	echo "<strong>"._ARTICLES_DATE_OFF."</strong><br />";
										echo "<script language=\"javascript\">EndDate.writeControl(); EndDate.dateFormat=\"dd.MM.yyyy\";</script> <strong>-</strong> <select name=\"article_date_off_4\">";
										for ($i=0;$i<=23;$i++){
											echo "<option value=\"".$i."\" "; if ($article_date_off[8].$article_date_off[9] == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
										}
										echo "</select><strong>:</strong><select name=\"article_date_off_5\">";
										for ($i=0;$i<=59;$i++){
											echo "<option value=\"".$i."\" "; if ($article_date_off[10].$article_date_off[11] == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
										}
										echo "</select><br><br>";
									}
									echo "<select name=\"article_lang\">";
										$reslg = mysql_query("SELECT language_code, language_name FROM $db_language ORDER BY language_code ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
										while ($arlg = mysql_fetch_array($reslg)){
											echo "<option name=\"article_lang\" value=\"".$arlg['language_code']."\" ";
											if ($arlg['language_code'] == $ar['article_lang']) { echo "selected=\"selected\"";}
											echo">".$arlg['language_name']."</option>";
										}
									echo "</select><br /><br />";
									echo "<input type=\"checkbox\" name=\"article_comments\" value=\"1\" "; if ($ar['article_comments'] == 1){echo "checked";} echo "> <span>"._ARTICLES_ALLOW_COMMENTS."</span><br /><br />";
									echo "<input type=\"checkbox\" name=\"article_ftext\" value=\"1\" "; if ($ar['article_ftext'] == 1){echo "checked";} echo "> <span>"._ARTICLES_ALLOW_FULLTEXT."</span><br /><br />";
									echo "<input type=\"checkbox\" name=\"article_prevoff\" value=\"1\" "; if ($ar['article_prevoff'] == "1"){echo "checked";} echo "> <span>"._ARTICLES_PREVOFF."</span><br /><br />";
									if ($article_parent_id == 0){
								  		echo "<input type=\"checkbox\" name=\"article_link\" value=\"TRUE\" "; if ($ar['article_link'] == "TRUE"){echo "CHECKED";} echo ">"._ARTICLES_HEADLINE_IS_LINK."<br /><br />";
								  		echo "<input type=\"checkbox\" name=\"article_top_article\" value=\"1\" "; if ($ar['article_top_article'] == "1"){echo "CHECKED";} echo ">"._ARTICLES_TOP_ARTICLE."&nbsp;&nbsp;\n";
								 		echo "<input type=\"checkbox\" name=\"article_best_article\" value=\"1\" "; if ($ar['article_best_article'] == "1"){echo "CHECKED";} echo ">"._ARTICLES_BEST_ARTICLE."<br /><br />";
							   		} else {
							   	  		echo "<input type=\"hidden\" name=\"article_headline\" value=\"".$article_headline."\">";
								   		echo "<input type=\"hidden\" name=\"article_top_article\" value=\"0\">";
									}
								}
				echo "		</td>\n";
				echo "	</tr>\n";
				echo "</table>\n";
				echo "<table width=\"849\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\" class=\"editor_bg\">\n";
				echo "	<tr>\n";
				echo "		<td>";
				/* Editor - Start */
				echo "<div><strong>Perex:</strong><br>";
				$article_perex = htmlspecialchars($article_perex,ENT_QUOTES);
				echo "	<textarea id=\"article_perex\" name=\"article_perex\" class=\"article_perex\" rows=\"15\" cols=\"120\" style=\"width: 100%\">".$article_perex."</textarea><br>";
				echo "</div>";
				echo "<div><strong>Text:</strong><br>";
				$article_body = htmlspecialchars($article_body,ENT_QUOTES);
				echo "	<textarea id=\"article_body\" name=\"article_body\" class=\"article_body\" rows=\"50\" cols=\"120\" style=\"width: 100%\">".$article_body."</textarea>";
				echo "</div><br><br>";
				/* Editor - End */
				echo "<span><strong>"._ARTICLES_SOURCE."</strong></span> <input type=\"text\" name=\"article_source\" size=\"80\" maxlength=\"255\" value=\"".$ar['article_source']."\" /><a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('"._ARTICLES_HINT_SOURCE."', this, event, '200px')\"><img src=\"images/editor_help.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></a><br /><br />\n";
				echo "<span><strong>"._ARTICLES_POLL."</strong></span> <select name=\"article_poll\">\n";
				echo "<option value=\"0\">"._ARTICLES_POLL_HINT."</option>";
						$respoll = mysql_query("SELECT poll_questions_id, poll_questions_question FROM $db_poll_questions WHERE poll_questions_for=1 ORDER BY poll_questions_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($arpoll = mysql_fetch_array($respoll)){
							echo "<option value=\"".$arpoll['poll_questions_id']."\" ";
							if ($arpoll['poll_questions_id'] == $ar['article_poll']) { echo "selected=\"selected\"";}
							echo ">".$arpoll['poll_questions_id']." - ".$arpoll['poll_questions_question']."</option>";
						}
	echo "						</select>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td>"._ARTICLES_HINTS_2."</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "				<br />\n";
	echo "				<input type=\"hidden\" name=\"info\" value=\"".$info."\">\n";
	echo "				<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
						if ($action == "article_edit"){
							echo "<input type=\"hidden\" name=\"article_chapter_parent_id\" value=\""; if ($article_chapter_number == 1 && $article_parent_id == 0){echo 0;}elseif ($article_parent_id != 0){echo $ar['article_parent_id'];} echo "\">\n";
						}
	echo "				<input type=\"hidden\" name=\"sa\" value=\"".$sa."\">\n";
	echo "				<input type=\"hidden\" name=\"kat\" value=\"".$kat."\">\n";
	echo "				<input type=\"hidden\" name=\"act\" value=\"".$act."\">\n";
	echo "				<input type=\"hidden\" name=\"id\" value=\""; if ($action == "article_add" && $cislokapitoly == ""){ echo "0";} else {echo $id;} echo "\">\n";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "				<input type=\"hidden\" name=\"picdbfilename\" value=\"".$picdbfilename."\">\n";
	echo "				<input type=\"checkbox\" name=\"kill_word\" value=\"1\"><span>"._ARTICLES_KILL_WORD."</span><br />\n";
	echo "				<input type=\"checkbox\" name=\"kill_word_font\" value=\"1\">&lt;font&gt;&nbsp;&nbsp;&nbsp;\n";
	echo "				<input type=\"checkbox\" name=\"kill_word_style\" value=\"1\">style&nbsp;&nbsp;&nbsp;\n";
	echo "				<input type=\"checkbox\" name=\"kill_word_class\" value=\"1\">class&nbsp;&nbsp;&nbsp;\n";
	echo "				<input type=\"checkbox\" name=\"kill_word_span\" value=\"1\">&lt;span&gt;&nbsp;&nbsp;&nbsp;\n";
	echo "				<input type=\"checkbox\" name=\"kill_word_p\" value=\"1\">&lt;p&gt;&nbsp;&nbsp;&nbsp;\n";
	echo "				<input type=\"checkbox\" name=\"kill_word_ul\" value=\"1\">&lt;ul&gt;&lt;li&gt;&nbsp;&nbsp;&nbsp;\n";
	echo "				<input type=\"checkbox\" name=\"kill_word_object\" value=\"1\">&lt;object&gt;&nbsp;&nbsp;&nbsp;\n";
	echo "				<input type=\"checkbox\" name=\"kill_word_embed\" value=\"1\">&lt;embed&gt;&nbsp;&nbsp;&nbsp;\n";
	echo "				<input type=\"checkbox\" name=\"kill_word_table\" value=\"1\">&lt;table&gt;&lt;tbody&gt;&lt;tr&gt;&lt;td&gt;<br /><br />\n";
	echo "				<input type=\"submit\" value=\""._CMN_SUBMIT."\">\n";
						//Podminka nastavi zobrazeni checkboxu pouze v pripade, ze je pouzito funkce Add
	echo "				<input type=\"radio\" name=\"save\" value=\"1\"><span>"._ARTICLES_FUNC_SAVE."</span>&nbsp;&nbsp;&nbsp;\n";
	echo "				<input type=\"radio\" name=\"save\" value=\"2\" checked><span>"._ARTICLES_FUNC_SAVE_SEND."</span>&nbsp;&nbsp;&nbsp;\n";
	echo "				<input type=\"radio\" name=\"save\" value=\"3\"><span>"._ARTICLES_FUNC_ADD_CHAPTER."</span>&nbsp;&nbsp;&nbsp;\n";
	echo "				<input type=\"checkbox\" name=\"check_public\" value=\"1\" "; if (CheckPriv("groups_article_public_submit") <> 1 || $act != "articles_public") { echo "disabled";} echo "><span>"._ARTICLES_ALLOW."</span>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	</form>\n";
	echo "</table>";
}
//********************************************************************************************************
//																										
//			 MAZANI ZPRAV																				 
//																										
//********************************************************************************************************
function DeleteArticle(){
	
	global $db_articles,$db_category,$db_comments,$db_admin,$db_articles_images,$db_articles_files;
	
	$act = $_GET['act'];
	
	if ($act == "articles_public"){
		$groups_priv_article_edit = "groups_article_public_edit";
		$groups_priv_article_del = "groups_article_public_del";
		$groups_priv_article_add = "groups_article_public_add";
		$articles_public = 1;
	} else {
		$groups_priv_article_edit = "groups_article_edit";
		$groups_priv_article_del = "groups_article_del";
		$groups_priv_article_add = "groups_article_add";
		$articles_public = 0;
	}
	$res = mysql_query("SELECT n.article_id, n.article_author_id, n.article_headline, n.article_perex, n.article_date, a.admin_uname, a.admin_nick, c.category_id, c.category_name, c.category_id FROM $db_articles AS n, $db_admin AS a, $db_category AS c WHERE n.article_id=".(integer)$_GET['id']." AND n.article_author_id=a.admin_id AND c.category_id=n.article_category_id") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_article_all_del") <> 1){
		if ((CheckPriv($groups_priv_article_del) == 1 && $_SESSION['loginid'] == $ar['article_author_id']) || (CheckPriv($groups_priv_article_del) == 1 && $admini == "TRUE")){
			// Vstup povolen
		} else {
			// Vstup zamitnut
			echo _NOTENOUGHPRIV;ShowMain();exit;
		}
	}
	echo "	<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr>\n";
	echo "			<td align=\"left\" class=\"nadpis\">"; if ($act == "articles_public"){echo _ARTICLES_DEL_PUBLIC;} else {echo _ARTICLES_DEL_ARTICLES;} echo "</td>\n";
	echo "		<tr>\n";
	echo "		<tr>\n";
	echo "			<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">\n";
	echo "				<a href=\"modul_articles.php?project=".$_SESSION['project']."&act=".$act."\">"._ARTICLES_ARTICLES_LIST."</a>";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	</table>\n";
	echo "	<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr class=\"popisky\">\n";
	echo "			<td align=\"right\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "			<td><span class=\"nadpis-boxy\">"._ARTICLES_HEADLINE."</span></td>\n";
	echo "			<td><span class=\"nadpis-boxy\">"._ARTICLES_AUTHOR."</span></td>\n";
	echo "			<td><span class=\"nadpis-boxy\">"._ARTICLES_DATE_IN."</span></td>\n";
	echo "			<td><span class=\"nadpis-boxy\">"._ARTICLES_CATEGORY."</span></td>\n";
	echo "		</tr>\n";
   	$datum = FormatTimestamp($ar['article_date']);
	$hlavicka = PrepareFromDB($ar['article_headline'],1);
   	$nahled = PrepareFromDB($ar['article_perex'],1);
	echo "	<tr>";
	echo "			<td align=\"right\"><a href=\"modul_articles.php?action=article_edit&amp;id=".$ar['article_id']."&amp;project=esuba&amp;search_c=&search_a=&act=article&page=&amp;podle=article_id&amp;ser=DESC\">".$ar['article_id']."</a></td>";
	echo "	 	<td>".$hlavicka."</td>";
	echo "		<td>".$ar['admin_nick']."</td>";
	echo "		<td>".$datum."</td>";
	echo "		<td width=\"150\" align=\"left\" valign=\"top\">".$ar['category_name']."</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td colspan=\"4\">".$nahled."</td>";
	echo "	</tr>";
	echo "	</table>";
	echo "	<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr>\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._ARTICLES_CHECK_DEL."</span></strong></td>\n";
	echo "			</tr>\n";
	echo "			<td width=\"50\" valign=\"top\">\n";
	echo "				<form action=\"sys_save.php?action=article_del&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
	echo "		 		<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
	echo "				<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "				<input type=\"hidden\" name=\"page\" value=\"".$_GET['page']."\">\n";
	echo "				<input type=\"hidden\" name=\"podle\" value=\"".$_GET['podle']."\">\n";
	echo "				<input type=\"hidden\" name=\"ser\" value=\"".$_GET['ser']."\">\n";
	echo "				<input type=\"hidden\" name=\"search_a\" value=\"".$_GET['search_a']."\">\n";
	echo "				<input type=\"hidden\" name=\"search_c\" value=\"".$_GET['search_c']."\">\n";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "				<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "				</form>\n";
	echo "			</td>\n";
	echo "			<td width=\"800\" valign=\"top\">\n";
	echo "				<form action=\"modul_articles.php?action=&project=".$_SESSION['project']."&page=".$_GET['page']."&podle=".$_GET['podle']."&ser=".$_GET['ser']."&search_a=".$_GET['search_a']."&search_c=".$_GET['search_c']."\">";
	echo "				<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "				</form>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	</table>\n";
}

//********************************************************************************************************
//																										
//			 SPRAVA SOUBORU																			 
//																										
//********************************************************************************************************
function ManageFiles(){
	
	global $db_articles,$db_articles_files;
	global $eden_cfg;
	global $ftp_path_files;
	
		// CHECK PRIVILEGIES
	if (CheckPriv("groups_article_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	// Zjisteni poctu obrazku v adresari na serveru klienta
	$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP); 
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); 
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	
	if ((!$conn_id) || (!$login_result)) { 
		echo _ERROR_FTP;
		die; 
	}
	$filesftp = ftp_nlist($conn_id, $ftp_path_files); // Ulozeni jednotlivych nazvu souboru do pole $b
	$d1 = count($filesftp);
	$d2 = count($filesftp)-2; // To minus 2 je pro odstraneni . a .. ze zobrazeni
	
	
	// Zjisteni poctu souboru zapsanych v databazi
	$res = mysql_query("SELECT * FROM $db_articles_files") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_num_rows($res);
	$j = 0;
	while ($ar2 = mysql_fetch_array($res)){
		$filesdb[$j] = $ar2['article_files_file']; // Ulozeni nazvu jednotlivych souboru do pole $filesdb
		$j++;
	}
	// Zjisteni poctu souboru zapsanych v databazi
	$res3 = mysql_query("SELECT * FROM $db_articles_files") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num3 = mysql_num_rows($res3);
	$j = 0;
	while ($ar3 = mysql_fetch_array($res3)){
		$filesdb2[$j] = $ar3['article_files_file']; // Ulozeni nazvu jednotlivych souboru do pole $filesdb
		$j++;
	}
	$filesdb = array_merge ($filesdb, $filesdb2);
	
	// Porovnani dvou poli (vysledek je pole s polozkami, ktere se nevyskytuji v druhem poli - v nasem pripade v databazi)
	if (isset ($filesdb) && isset ($filesftp)){$result = array_diff ($filesftp, $filesdb);}
	// Odstraneni souboru, ktere nejsou v databazi ze serveru
	if ($_POST[deletefiles] == "check"){
		$z = 2;
		while($z < $d1){
			if ($result[$z] != ""){
				ftp_delete($conn_id, $ftp_path_files.$result[$z]);
			}
			$z++;
		}
	}
	echo "	<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td align="left" class="nadpis"><?php echo _ARTICLES;?> - <?php echo _ARTICLES_MANAGE_FILES;?></td>
			<td align="right"></td>
		</tr>
		<tr>
			<td><a href="modul_articles.php?project=<?php echo $_SESSION['project'];?>"><img src="images/sys_manage.gif" height="18" width="18" border="0" alt="<?php echo _ARTICLESMANAGER;?>"><?php echo _ARTICLESMANAGER;?></a></td>
			<td align="right"></td>
		</tr>
	</table>
	<?php echo "	<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td width="857" height="50">&nbsp;</td>
		</tr>
		<tr>
			<td width="857"><?php echo _ARTICLES_MANAGE_PICLIST;?><br><br><?php 
				// Vypis souboru, ktere nejsou v databazi
				$z = 2;
				while($z < $d1){
					if ($result[$z] != ""){
					echo $result[$z];
					echo "<br>";
					}
					$z++;
				}?>
			</td>
		</tr>
		<tr>
			<td width="857" height="20">&nbsp;</td>
		</tr>
		<tr>
			<td width="857"><?php echo _ARTICLES_MANAGE_FILE_SUMA_DB;?>&nbsp;&nbsp;<?php echo $num;?></td>
		</tr>
		<tr>
			<td width="857"><?php echo _ARTICLES_MANAGE_FILE_SUMA_DIR;?>&nbsp;&nbsp;<?php echo $d2;?></td>
		</tr>
		<tr>
			<td width="857"><br>
				<form action="modul_articles.php?action=managefiles" method="post">
				<input type="hidden" name="deletefiles" value="check">
				<input type="hidden" name="project" value="<?php echo $_SESSION['project'];?>">
				<input type="submit" value="<?php echo _ARTICLES_MANAGE_FILE_DEL;?>" class="eden_button_no">
				</form></td>
		</tr>
	</table><?php 
}


//********************************************************************************************************
//																										
//			 SPRAVA OBRAZKU																			 
//																										
//********************************************************************************************************
function ManagePicture(){
	
	global $db_articles,$db_articles_images;
	global $eden_cfg;
	global $ftp_path_articles;
	
		// CHECK PRIVILEGIES
	if (CheckPriv("groups_article_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	// Zjisteni poctu obrazku v adresari na serveru klienta
	$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP); 
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); 
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	
	if ((!$conn_id) || (!$login_result)){ 
		 	echo _ERROR_FTP;
		 	die; 
	} else {
		$b = ftp_nlist($conn_id, $ftp_path_articles); // Ulozeni jednotlivych nazvu souboru do pole $b
		$d1 = count($b);
		$d2 = count($b)-2; // To minus 2 je pro odstraneni . a .. ze zobrazeni
	}
	
	// Pocet obrazku v clancich
	$res2 = mysql_query("SELECT article_text FROM $db_articles") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i = 1;
	$i2 = 2;
	while ($ar = mysql_fetch_array($res2)){
		$a = substr_count($ar['article_text'], "edencms/img_article");
		$picture[$i2] = $picture[$i] + $a; // Vysledek z predchozi iterace se pricte k soucasnemu vysledku a vse se ulozi do pole $picture
		$i++; //Musi tady byt 2 promenne, ktere se meni a jedna musi mit mensi hodnotu, aby doslo ke 
		$i2++; // scitani (aby si skript zapamatoval hodnotu z predchoziho kola).
	}
	
	// Zjisteni poctu obrazku zapsanych v databazi jako obrazek pro clanek
	$res3 = mysql_query("SELECT article_img_1 FROM $db_articles") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num3 = mysql_num_rows($res3);
	$i = 0;
	while ($ar3 = mysql_fetch_array($res3)){
		$y[$i] = $ar3['article_img_1']; // Ulozeni nazvu jednotlivych souboru do pole $y
		$i++;
	}
	
	// Zjisteni poctu obrazku zapsanych v databazi
	$res = mysql_query("SELECT article_img_1s_picture FROM $db_articles_images") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_num_rows($res);
	$j = 0;
	while ($ar2 = mysql_fetch_array($res)){
		$x[$j] = $ar2['article_img_1s_picture']; // Ulozeni nazvu jednotlivych souboru do pole $x
		$j++;
	}
	//Sloucime pole s obrazky
	$w = array_merge ($x, $y);
	
	// Porovnani dvou poli (vysledek je pole s polozkami, ktere se nevyskytuji v druhem poli - v nasem pripade v databazi)
	if ($b != "" && $w != ""){$result = array_diff ($b, $w);}
	// Odstraneni obrazku, ktere nejsou v databazi ze serveru
	if ($_POST['deletepicture'] == "check"){
		$z = 2;
		while($z < $d1){
			if ($result[$z] != ""){
				@ftp_delete($conn_id, $ftp_path_articles.$result[$z]);
			}
			$z++;
		}
	}
	echo "	<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td align="left" class="nadpis"><?php echo _ARTICLES;?> - <?php echo _ARTICLES_MANAGE_PIC;?></td>
			<td align="right"></td>
		</tr>
		<tr>
			<td><a href="modul_articles.php?project=<?php echo $_SESSION['project'];?>"><img src="images/sys_manage.gif" height="18" width="18" border="0" alt="<?php echo _ARTICLESMANAGER;?>"><?php echo _ARTICLESMANAGER;?></a></td>
			<td align="right"></td>
		</tr>
	</table>
	<?php echo "	<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td width="857" height="50">&nbsp;</td>
		</tr>
		<tr>
			<td width="857"><?php echo _ARTICLES_MANAGE_PICLIST;?><br><br><?php 
				// Vypis souboru, ktere nejsou v databazi
				$z = 2;
				while($z < $d1){
					if ($result[$z] != ""){
					echo $result[$z];
					echo "<br>";
					}
				$z++;
				}?>
			</td>
		</tr>
		<tr>
			<td width="857" height="20">&nbsp;</td>
		</tr>
		<tr>
			<td width="857"><?php echo _ARTICLES_MANAGE_PIC_SUMA_ARTICLES;?>&nbsp;&nbsp;<?php if (!isset($picture[$i])){echo "0";} else {echo $picture[$i];} // pokud neni zadny zaznam v databazi zobrazi se nula
			?>
			</td>
		</tr>
		<tr>
			<td width="857"><?php echo _ARTICLES_MANAGE_PIC_SUMA_DB;?>&nbsp;&nbsp;<?php echo $num;?></td>
		</tr>
		<tr>
			<td width="857"><?php echo _ARTICLES_MANAGE_PIC_SUMA_DIR;?>&nbsp;&nbsp;<?php echo $d2;?></td>
		</tr>
		<tr>
			<td width="857"><br>
				<form action="modul_articles.php?action=managepicture&amp;project=<?php echo $_SESSION['project'];?>" method="post">
				<input type="hidden" name="deletepicture" value="check">
				<input type="hidden" name="project" value="<?php echo $_SESSION['project'];?>">
				<input type="submit" value="<?php echo _ARTICLES_MANAGE_PIC_DEL;?>" class="eden_button_no">
				</form></td>
		</tr>
	</table>
<?php 
}
// Rekne skriptu v header.php ze ma aktivovat tinyMCE editor
$tinymce_init_mode = "article"; // pouzije se v inc.header.php pro inicializaci TinyMCE
include ("inc.header.php");
	if ($_GET['action'] == "") { ShowMain(); }
	if ($_GET['action'] == "showmain") { ShowMain(); }
	if ($_GET['action'] == "article_edit") { AddArticle(); }
	if ($_GET['action'] == "article_del") { DeleteArticle(); }
	if ($_GET['action'] == "article_add") { AddArticle(); }
	if ($_GET['action'] == "open") { ShowMain(); }
	if ($_GET['action'] == "zmeny") { ShowMain(); }
	if ($_GET['action'] == "close") { ShowMain(); }
	if ($_GET['action'] == "logout") { Logout(); }
	if ($_GET['action'] == "managepicture") {ManagePicture(); }
	if ($_GET['action'] == "managefiles") {ManageFiles(); }
	if ($_GET['action'] == "komentar"){Comments($_GET['id'],"article");}
	if ($_GET['action'] == "send"){Save("article");}	// Ulozi komentar
	if ($_GET['action'] == "delete_comments"){DeleteComm();}
	if ($_GET['action'] == "replace_comments"){ReplaceComm($_POST['r_topic'],$_POST['r_tekst'],$_POST['r_modul']);}	
	if ($_GET['action'] == "kill_use_article"){KillUseById($_GET['id'],$_GET['action']);ShowMain();} // Adminum s pravy kill_use umozni odstranit priznak "pouzivano" u novinek a aktualit
include ("inc.footer.php");