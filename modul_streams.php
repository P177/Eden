<?php
include "modul_comments.php";

function Menu(){
	
	global $eden_cfg;
	
	switch ($_GET['action']){
		case "stream_add":
			$title = " - "._STREAMS_STREAM_ADD;
			break;
		case "stream_edit":
			$title = " - "._STREAMS_STREAM_EDIT." - ID:".$_GET['id'];
			break;
		case "stream_del":
			$title = " - "._STREAMS_STREAM_DEL." - ID:".$_GET['id'];
			break;
		default:
			$title = "";
	}
	
	$menu = "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td align=\"left\" class=\"nadpis\">"._STREAMS.$title."</td>\n";
	$menu .= "	</tr>\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td align=\"left\"><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">&nbsp;";
	$menu .= "	  		<a href=\"modul_streams.php?project=".$_SESSION['project']."&amp;action=showmain&act=".$_GET['act']."\">"._STREAMS_LIST."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	$menu .= "	  		<a href=\"modul_streams.php?action=stream_add&amp;project=".$_SESSION['project']."&act=".$_GET['act']."\">"._STREAMS_STREAM_ADD."</a>\n";
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
	
	global $db_articles,$db_comments,$db_category,$db_articles_images,$db_admin,$db_eden_log;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	if (isset($_GET['hits'])){$hits = $_GET['hits'];}elseif (isset($_POST['hits'])){$hits = $_POST['hits'];} else {$hits = FALSE;}
	if (isset($_GET['search_c'])){$search_c = $_GET['search_c'];}elseif (isset($_POST['search_c'])){$search_c = $_POST['search_c'];} else {$search_c = FALSE;}
	if (isset($_GET['ondatez'])){$ondatez = $_GET['ondatez'];}elseif (isset($_POST['ondatez'])){$ondatez = $_POST['ondatez'];} else {$ondatez = FALSE;}
	if (isset($_GET['ondatek'])){$ondatek = $_GET['ondatek'];}elseif (isset($_POST['ondatek'])){$ondatek = $_POST['ondatek'];} else {$ondatek = FALSE;}
	$ser = $_GET['ser'];
	$podle = $_GET['podle'];
	$page = $_GET['page'];
	
	if (!isset($act)){$act = $_GET['act'];}
	
	$groups_priv_stream_edit = "groups_stream_edit";
	$groups_priv_stream_del = "groups_stream_del";
	$groups_priv_stream_add = "groups_stream_add";
	$articles_public = 0;
	
	if ($hits < 1){$hits = 30;}
	
	// Jestlize neni vybrano podle ceho se ma tridit, je vybrano podle datumu sestupne 
	if ($ser == "" && $podle == ""){
		$podle = "article_id";
		$podle_db = "article_id";
		$ser = "DESC";
	} else {
		$podle = mysql_real_escape_string($_GET['podle']);
		$podle_db = mysql_real_escape_string($_GET['podle']);
		$ser = mysql_real_escape_string($_GET['ser']);
	}
	
	// Show articles only from specific category
	if ($search_c != ""){$select_category = " AND article_category_id = ".(integer)$search_c;}
	
	$amount = mysql_query("SELECT COUNT(*) 
	FROM $db_articles 
	JOIN $db_category ON category_id = article_category_id 
	WHERE category_stream = 1 AND article_publish < 2 AND article_public = ".(integer)$articles_public." $select_category  AND article_parent_id = 0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
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
	
	$res = mysql_query("SELECT article_id, article_date, article_date_on, article_date_off, article_category_id, article_headline, article_perex, article_lang, article_author_id, article_user_use, 
	article_user_open, article_publish, article_views, admin_id, admin_nick 
	FROM $db_articles 
	JOIN $db_admin ON admin_id = article_author_id 
	JOIN $db_category ON category_id = article_category_id 
	WHERE category_stream = 1 AND article_publish < 2 AND article_public = 0 AND article_parent_id = 0 
	$select_category 
	ORDER BY $podle_db $ser $limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	echo Menu();
	
	echo "	<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr>\n";
	echo "			<td width=\"857\">";
	echo "				<div style=\"float:left;padding:3px 3px 3px 3px;\">";
	echo "					<form action=\"modul_streams.php?action=showmain&amp;project=".$_SESSION['project']."\" method=\"post\" name=\"formSearch\">\n";
	echo "				<br clear=\"all\"/>";
							// Select category
							echo EdenCategorySelect($search_c, "stream", "search_c", _ARTICLES_SEL_CAT);
	echo "					<input type=\"submit\" value=\""._CMN_SUBMIT."\" class=\"eden_button\">\n";
	echo "					<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "					<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\"><br />\n";
	echo "				</form>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	</table>\n";
	echo "	<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr class=\"popisky\">\n";
	echo "			<td width=\"80\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "			<td width=\"45\" align=\"center\" "; if ($podle == "article_id"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">ID</span></td>\n";
	echo "			<td width=\"450\" align=\"center\" "; if ($podle == "article_headline"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">"._ARTICLES_HEADLINE."</span></td>\n";
	echo "			<td align=\"center\" "; if ($podle == "article_views"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">"._ARTICLES_ZOB."</span></td>\n";
	echo "			<td align=\"center\" "; if ($podle == "article_date"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">"._ARTICLES_DATE_FROM."</span></td>\n";
	echo "			<td width=\"200\" align=\"center\" "; if ($podle == "article_category_id"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">"._ARTICLES_CATEGORY."</span></td>\n";
	echo "		</tr>\n";
	echo "		<tr class=\"popisky\">\n";
	echo "			<td width=\"80\">&nbsp;</td>\n";
	echo "			<td width=\"45\" align=\"center\" "; if ($podle == "article_id"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_streams.php?project=".$_SESSION['project']."&podle=article_id&ser=asc&amp;hits=".$hits."&section=".$section."&search_c=".$search_c."\"><img src=\"images/asc_"; if ($podle == "article_id" && $_GET['ser'] == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_streams.php?project=".$_SESSION['project']."&podle=article_id&ser=desc&amp;hits=".$hits."&section=".$section."&search_c=".$search_c."\"><img src=\"images/des_"; if ($podle == "article_id" && $_GET['ser'] == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "			<td width=\"450\" align=\"center\" "; if ($podle == "article_headline"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_streams.php?project=".$_SESSION['project']."&podle=article_headline&ser=asc&amp;hits=".$hits."&section=".$section."&search_c=".$search_c."\"><img src=\"images/asc_"; if ($podle == "article_headline" && $_GET['ser'] == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_streams.php?project=".$_SESSION['project']."&podle=article_headline&ser=desc&amp;hits=".$hits."&section=".$section."&search_c=".$search_c."\"><img src=\"images/des_"; if ($podle == "article_headline" && $_GET['ser'] == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "			<td align=\"center\" "; if ($podle == "article_views"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_streams.php?project=".$_SESSION['project']."&podle=article_views&ser=asc&amp;hits=".$hits."&section=".$section."&search_c=".$search_c."\"><img src=\"images/asc_"; if ($podle == "article_views" && $_GET['ser'] == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_streams.php?project=".$_SESSION['project']."&podle=article_views&ser=desc&amp;hits=".$hits."&section=".$section."&search_c=".$search_c."\"><img src=\"images/des_"; if ($podle == "article_views" && $_GET['ser'] == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "			<td align=\"center\" "; if ($podle == "article_date"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_streams.php?project=".$_SESSION['project']."&podle=article_date&ser=asc&amp;hits=".$hits."&section=".$section."&search_c=".$search_c."\"><img src=\"images/asc_"; if ($podle == "article_date" && $_GET['ser'] == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_streams.php?project=".$_SESSION['project']."&podle=article_date&ser=desc&amp;hits=".$hits."&section=".$section."&search_c=".$search_c."\"><img src=\"images/des_"; if ($podle == "article_date" && $_GET['ser'] == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "			<td width=\"200\" align=\"center\" "; if ($podle == "article_category_id"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_streams.php?project=".$_SESSION['project']."&podle=article_category_id&ser=asc&amp;hits=".$hits."&section=".$section."&search_c=".$search_c."\"><img src=\"images/asc_"; if ($podle == "article_category_id" && $_GET['ser'] == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_streams.php?project=".$_SESSION['project']."&podle=article_category_id&ser=desc&amp;hits=".$hits."&section=".$section."&search_c=".$search_c."\"><img src=\"images/des_"; if ($podle == "article_category_id" && $_GET['ser'] == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "		</tr>\n";
	$i=1;
	while ($ar = mysql_fetch_array($res)){
		$m++;
		$datum = FormatTimestamp($ar['article_date']);
		$datum_h = FormatTimestamp($ar['article_date']);
		$datumed = FormatTimestamp($ar['article_date_on']);
		$showtime = date("YmdHis");
		$res2 = mysql_query("SELECT category_id, category_admin, category_name, category_parent FROM $db_category WHERE category_id=".(integer)$ar['article_category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar2 = mysql_fetch_array($res2);
		if ($ar2['category_parent']){
			$res22 = mysql_query("SELECT category_name FROM $db_category WHERE category_id=".(integer)$ar2['category_parent']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar22 = mysql_fetch_array($res22);
		}
		
		// Zjisteni poctu obrazku, na ktere je odkazovano z prispevku
		$picture = substr_count($ar['article_text'], "edencms/img_articles");
		
		// Zjisteni poctu komentářů
		$res4 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar['article_id']." AND comment_modul='article'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num4 = mysql_fetch_array($res4);
		
		
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
		
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "		<td width=\"80\" valign=\"top\">";
					$res5 = mysql_query("SELECT article_id, article_date, article_date_edit, article_user_use, article_category_id, article_parent_id, article_chapter_name, article_views 
					FROM $db_articles AS n WHERE n.article_parent_id=".$ar['article_id']." AND n.article_publish<2 $select_category 
					ORDER BY n.article_chapter_number ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$num5 = mysql_num_rows($res5);
					// Kdyz clanek obsahuje vice kapitol zobrazi se tlacitko pro otevreni, jinak se zobrazi prazdne misto
					if ((CheckPriv($groups_priv_stream_edit) == 1 || $admini02 == "TRUE")	&& (CheckPriv("groups_article_all_edit") == 1 ||	$_SESSION['loginid'] == $ar['article_author_id'])){
						/* Pokud nekdo edituje, nebo maze tak se nezobrazi ikona pro editaci a mazani */ 
						if ($ar['article_user_use'] == "0" || $ar['article_user_use'] == $_SESSION['loginid']){
							echo " <a href=\"modul_streams.php?action=stream_edit&amp;id=".$ar['article_id']."&amp;project=".$_SESSION['project']."&search_c=".$search_c."&act=".$act."&amp;page=".$_GET['page']."&podle=".$podle."&ser=".$ser."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";
						} else { 
							if (CheckPriv("groups_article_all_kill_user") == 1){
								echo "<a href=\"modul_streams.php?action=kill_use_article&amp;id=".$ar['article_id']."&amp;project=".$_SESSION['project']."&search_c=".$search_c."&act=".$act."&amp;page=".$_GET['page']."&podle=".$podle."&ser=".$ser."\"><img src=\"images/sys_killuse.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._ARTICLES_KILL_USE." - ".GetUserName($ar['article_user_use'])." - ".FormatTimestamp($ar['article_user_open'],"d.m.Y H:i:s")."\"></a>";
							}
						}
					} else {
						echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"22\" border=\"0\" alt=\"\">";
					}
					if ((CheckPriv($groups_priv_stream_del) == 1 && $_SESSION['loginid'] == $ar['article_author_id']) || (CheckPriv("groups_article_all_del") == 1)){
						if ($ar['article_user_use'] == "0" || $ar['article_user_use'] == $_SESSION['loginid']){
							/* Pokud nekdo edituje, nebo maze tak se nezobrazi ikona pro editaci a mazani */ 
							echo ' <a href="modul_streams.php?action=stream_del&amp;id='.$ar['article_id'].'&amp;id2='.$ar['article_category_id'].'&amp;project='.$_SESSION['project'].'&search_c='.$search_c.'&act='.$act.'&amp;page='.$_GET['page'].'&podle='.$podle.'&ser='.$ser.'"><img src="images/sys_del.gif" height="18" width="18" border="0" alt="'._CMN_DEL.'"></a>';
						}
					} else {
						echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"22\" border=\"0\" alt=\"\">"; 
					}
				   if (CheckPriv("groups_comments_change") == 1 || $admini02 == "TRUE"){echo " <a href=\"modul_streams.php?action=komentar&amp;id=".$ar['article_id']."&amp;id2=".$ar['article_category_id']."&amp;project=".$_SESSION['project']."&search_c=".$search_c."&act=".$act."&amp;page=".$_GET['page']."&podle=".$podle."&ser=".$ser."\" title=\""._NUMCOM."\"><span style=\"font-size:10px; font-weight: bold;\">".$num4[0]."</span></a>";}
		echo "		</td>\n";
		echo "		<td width=\"45\" valign=\"top\">".$ar['article_id']."</td>\n";
		echo "		<td width=\"450\" valign=\"top\">"; 
						if ($ar['article_publish'] == "0"){
							echo "<img src=\"images/sys_no.gif\" alt=\""._CMN_DONT_PUBLISH."\" title=\""._CMN_DONT_PUBLISH."\" width=\"12\" height=\"12\" border=\"0\"> ";
						} 
						if ($ar['article_user_use'] != "0" && $ar['article_user_use'] != $_SESSION['loginid']){
							echo "<img src=\"images/sys_use.gif\" width=\"18\" height=\"18\" border=\"0\" alt=\""._ARTICLES_USE.GetUserName($ar['article_user_use'])." [ID ".$ar['article_user_use']."]\" title=\""._ARTICLES_USE.GetUserName($ar['article_user_use'])." [ID ".$ar['article_user_use']."]\">";
						} 
						echo "<img src=\""._URL_FLAGS.strtoupper($ar['article_lang']).".gif\" height=\"12\" width=\"18\" border=\"0\" alt=\"\"> "; 
						echo $hlavicka; 
						if ($_GET['action'] == "open" & $_GET['id'] == $ar['article_id']) {
							echo "<br>".$ar['article_chapter_name'];
						} 
		echo "		</td>\n";
		echo "		<td align=\"right\" valign=\"top\">".$ar['article_views']."</td>\n";
		echo "		<td align=\"center\" valign=\"top\">"; if ( $showtime < $ar['article_date_on'] || $showtime > $ar['article_date_off']){echo "<span class=\"red\">".$datumed."</span>";} else {echo $datumed;} echo "</td>\n";
		echo "		<td width=\"200\" align=\"left\" valign=\"top\">"."ID - ".$ar2['category_id']."<br>".$name2."</td>\n";
		echo "	</tr>";
		if ($_GET['action'] == "open" & $_GET['id'] == $ar['article_id']) {
			while($ar5 = mysql_fetch_array($res5)){
				// Zjisteni poctu obrazku v databazi k jednotlivemu prispevku
				$res7 = mysql_query("SELECT COUNT(*) FROM $db_articles_images WHERE articles_images_article_id=".(integer)$ar5['article_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$num7 = mysql_fetch_array($res7);
				$datum = FormatTimestamp($ar5['article_date']);
				$datum_h = FormatTimestamp($ar5['article_date']);
				$datumed = FormatTimestamp($ar5['article_date_edit']);
				echo "<tr bgcolor=\"#EEEEEE\">";
				echo "	<td width=\"120\"><img src=\"images/a_bod.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">";
						if (CheckPriv($groups_priv_stream_edit) == 1 || $admini02 == "TRUE" || $_SESSION['loginid'] == $ar['article_author_id']){ 
							/*Pokud nekdo edituje, nebo maze tak se nezobrazi ikona pro editaci a mazani*/
							if ($ar5['article_user_use'] == "0" || $ar5['article_user_use'] == $_SESSION['loginid']){
								echo " <a href=\"modul_streams.php?action=stream_edit&amp;id=".$ar5['article_id']."&amp;id2=".$ar5['article_category_id']."&parent_chap=".$ar5['article_parent_id']."&amp;project=".$_SESSION['project']."&search_c=".$search_c."&act=".$act."&amp;page=".$_GET['page']."&podle=".$podle."&ser=".$ser."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";
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
				echo " <a href=\"modul_streams.php?page=".$i."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."&search_c=".$search_c."&podle=".$podle."&ser=".$ser."&ondatez=".$ondatez."&ondatek=".$ondatek."\">".$i."</a> ";
			}
		}
		//Zobrazeni sipek s predchozimi a dalsimi strankami novinek
		if ($page > 1){echo "<center><a href=\"modul_streams.php?page=".$pp."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."&search_c=".$search_c."&podle=".$podle."&ser=".$ser."&ondatez=".$ondatez."&ondatek=".$ondatek."\">"._CMN_PREVIOUS."</a>";} else { echo "<br>"._CMN_PREVIOUS;} echo " <--|--> "; if ($page == $stw2){echo _CMN_NEXT;} else {echo "<a href=\"modul_streams.php?page=".$np."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."&search_c=".$search_c."&podle=".$podle."&ser=".$ser."&ondatez=".$ondatez."&ondatek=".$ondatek."\">"._CMN_NEXT."</a></center>";}
		echo "</td></tr></table>";
	}
}
//********************************************************************************************************
//																										
//			 PRIDAVANI ZPRAV																			
//																										
//********************************************************************************************************
function AddStream(){
	
	global $db_category,$db_articles,$db_articles_images,$db_articles_files,$db_language,$db_setup;
	global $eden_cfg;
	global $ftp_path_articles;
	global $article_path,$url_articles;
	
	if (isset($_GET['search_c'])){$search_c = $_GET['search_c'];}elseif (isset($_POST['search_c'])){$search_c = $_POST['search_c'];}
	if (isset($_GET['act'])){$act = $_GET['act'];}elseif (isset($_POST['act'])){$act = $_POST['act'];}
	
	if ($act == "articles_public"){
		$groups_priv_stream_edit = "groups_article_public_edit";
		$groups_priv_stream_del = "groups_article_public_del";
		$groups_priv_stream_add = "groups_article_public_add";
		$articles_public = 1;
	} else {
		$groups_priv_stream_edit = "groups_stream_edit";
		$groups_priv_stream_del = "groups_stream_del";
		$groups_priv_stream_add = "groups_stream_add";
		$articles_public = 0;
	}
	
	$res_setup = mysql_query("SELECT setup_eden_editor_cleaner, setup_eden_editor_purificator FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$article_date = date("YmdHis");
	if ($_GET['action'] == "stream_edit"){
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
	if ($_GET['action'] == "stream_add"){
		if (CheckPriv($groups_priv_stream_add) <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($_GET['action'] == "stream_edit"){
		if (CheckPriv("groups_article_all_edit") <> 1){
			if ((CheckPriv($groups_priv_stream_edit) == 1 && $_SESSION['loginid'] == $ar['article_author_id']) || (CheckPriv($groups_priv_stream_edit) == 1 && $admini == "TRUE")){
				// Vstup povolen
			} else {
				// Vstup zamitnut
				echo _NOTENOUGHPRIV;ShowMain();exit;
			}
		}
	} else {
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	
	if ($_GET['action'] == "stream_edit" && !isset($_POST['confirm'])){
		$id = $_GET['id'];
		EdiTor($id,$act,"stream_edit");
	}
	/*
	*	Pridani novinky uplne od zacatku
	*/
	if ($_GET['action'] == "stream_add" && $article_headline == ""){ 
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
	
	global $db_articles,$body,$db_category,$db_admin,$db_language,$db_country;
	global $eden_cfg;
	global $url_articles;
	global $preview,$sa,$kat,$search_c;
	
	if ($action != "stream_add"){
		$res = mysql_query("SELECT * FROM $db_articles WHERE article_id=".(integer)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	
	$article_headline = stripslashes($ar['article_headline']);
	$article_perex = stripslashes($ar['article_perex']);
	$article_body = stripslashes($ar['article_text']);
	
	echo Menu();
	
	echo "<table  cellspacing=\"2\" cellpadding=\"0\" border=\"0\""; echo (isset($enh)) ? "class=\"editor_bg\"" : ">";
	echo "	<form action=\"sys_save.php?action="; if ($id == 0 || $action == "stream_add"){echo "stream_add";} else {echo "stream_edit";} echo "&amp;id=".$id."&parent_chap=0&amp;page=".$_GET['page']."&amp;kat=".$_GET['kat']."&amp;sa=".$_GET['sa']."&amp;podle=".$_GET['podle']."&amp;ser=".$_GET['ser']."\" enctype=\"multipart/form-data\" name=\"form1\" method=\"post\" ".$script_on_submit.">";
	echo "	<tr>";
	echo "		<td nowrap>";
	echo "			<table width=\"849\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\" class=\"editor_bg\">\n";
	echo "				<tr>\n";
	echo "					<td>\n";
	echo "						<strong>"._ARTICLES_HEADLINE."</strong><br />";
								echo "<input type=\"text\" name=\"article_headline\" value=\""; if ($action == "stream_edit"){ echo $article_headline;} echo "\" size=\"60\"  maxlength=\"250\">&nbsp;&nbsp;\n";
								echo "<input type=\"checkbox\" name=\"article_publish\" value=\"1\" "; if ($ar['article_publish'] == "1" || $ar['article_publish'] == ""){echo "CHECKED";} echo ">"._CMN_PUBLISH."&nbsp;&nbsp;\n";
								echo "<br /><br />";
								if ($article_parent_id == 0){
								echo "<strong>"._ARTICLES_CATEGORY."</strong><img src=\"images/a_bod.gif\" width=\"195\" height=\"5\" border=\"0\" alt=\"\">"; if (CheckPriv("groups_change_redactor") == 1){echo "<strong>"._ARTICLES_FUNC_CHANGE_REDACTOR."</strong>";} echo "<br />";
									// Select category
									echo EdenCategorySelect($ar['article_category_id'], "stream", "article_category_id", 0);
									echo "<img src=\"images/a_bod.gif\" width=\"10\" height=\"5\" border=\"0\" alt=\"\">";
										if (CheckPriv("groups_change_redactor") == 1){
										echo "<select name=\"zmena_redaktora\">";
											$reszr = mysql_query("SELECT admin_id, admin_uname FROM $db_admin WHERE admin_status='admin' ORDER BY admin_uname ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
											while ($arzr = mysql_fetch_array($reszr)){
												echo "<option value=\"".$arzr['admin_id']."\" ";
												if ($arzr['admin_id'] == $ar['article_author_id'] || ($_GET['action'] == "stream_add" && $arzr['admin_id'] == $_SESSION['loginid'])) { echo "selected=\"selected\"";}
												echo">".$arzr['admin_uname']."</option>";
											}
										echo "</select>";
										echo "<br /><br />";
									}
									
								} else {
									echo "<input type=\"hidden\" name=\"article_category_id\" value=\"".$ar['category_name']."\">";
									echo "<br /><br />";
								}
				$res_country = mysql_query("SELECT country_id, country_shortname, country_name FROM $db_country ORDER BY country_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				echo "<select name=\"article_lang\">";
				echo "	<option value=\"0\""; if ($_GET['action'] == "add"){echo " selected ";} echo ">"._PROFILE_COUNTRY_SELECT."</option>";
				while($ar_country = mysql_fetch_array($res_country)){
					echo "<option value=\"".$ar_country['country_shortname']."\""; if ($ar['article_lang'] == $ar_country['country_shortname']){echo " selected ";} echo ">".$ar_country['country_name']."</option>";
				}
				echo "</select>";
	echo "					</td>\n";
				echo "<td width=\"230\" align=\"left\" valign=\"top\">";
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
				echo "<input type=\"checkbox\" name=\"article_comments\" value=\"1\" "; if ($ar['article_comments'] == 1){echo "checked";} echo "> <span>"._ARTICLES_ALLOW_COMMENTS."</span><br /><br />";
				echo "					</td>\n";
				echo "				</tr>\n";
				echo "			</table>\n";
				echo "			<table width=\"849\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\" class=\"editor_bg\">\n";
				echo "				<tr>\n";
				echo "					<td>";
				/* Editor - Start */
				echo "<div><strong>Perex:</strong><br>";
				echo "	<textarea id=\"article_perex\" name=\"article_perex\" class=\"article_perex\" rows=\"15\" cols=\"120\" style=\"width: 100%\">".$article_perex."</textarea><br>";
				echo "</div>";
				echo "<div><strong>Text:</strong><br>";
				echo "	<textarea id=\"article_body\" name=\"article_body\" class=\"article_body\" rows=\"50\" cols=\"120\" style=\"width: 100%\">".$article_body."</textarea>";
				echo "</div><br><br>";
				/* Editor - End */
				echo "<span><strong>"._ARTICLES_SOURCE."</strong></span> <input type=\"text\" name=\"article_source\" size=\"80\" maxlength=\"255\" value=\"".$ar['article_source']."\" /><a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('"._STREAMS_HINT_SOURCE."', this, event, '200px')\"><img src=\"images/editor_help.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></a><br /><br />\n";
				
	echo "			</table>\n";
	echo "				<br />\n";
	echo "				<input type=\"hidden\" name=\"info\" value=\"".$info."\">\n";
	echo "				<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "				<input type=\"hidden\" name=\"sa\" value=\"".$sa."\">\n";
	echo "				<input type=\"hidden\" name=\"kat\" value=\"".$kat."\">\n";
	echo "				<input type=\"hidden\" name=\"act\" value=\"".$act."\">\n";
	echo "				<input type=\"hidden\" name=\"id\" value=\""; if ($action == "stream_add"){ echo "0";} else {echo $id;} echo "\">\n";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "				<input type=\"hidden\" name=\"picdbfilename\" value=\"".$picdbfilename."\">\n";
	echo "				<input type=\"submit\" value=\""._CMN_SUBMIT."\" "; echo (isset($enh)) ? "class=\"button\"" : ""; echo ">\n";
						//Podminka nastavi zobrazeni checkboxu pouze v pripade, ze je pouzito funkce Add
	echo "				<input type=\"radio\" name=\"save\" value=\"1\"><span>"._ARTICLES_FUNC_SAVE."</span>&nbsp;&nbsp;&nbsp;\n";
	echo "				<input type=\"radio\" name=\"save\" value=\"2\" checked><span>"._ARTICLES_FUNC_SAVE_SEND."</span>&nbsp;&nbsp;&nbsp;\n";
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
function DeleteStream(){
	
	global $db_articles,$db_category,$db_comments,$db_admin,$db_articles_images,$db_articles_files;
	
	$act = $_GET['act'];
	
	if ($act == "articles_public"){
		$groups_priv_stream_edit = "groups_article_public_edit";
		$groups_priv_stream_del = "groups_article_public_del";
		$groups_priv_stream_add = "groups_article_public_add";
		$articles_public = 1;
	} else {
		$groups_priv_stream_edit = "groups_stream_edit";
		$groups_priv_stream_del = "groups_stream_del";
		$groups_priv_stream_add = "groups_stream_add";
		$articles_public = 0;
	}
	$res = mysql_query("SELECT n.article_id, n.article_author_id, n.article_headline, n.article_perex, n.article_date, a.admin_uname, a.admin_nick, c.category_id, c.category_name, c.category_id FROM $db_articles AS n, $db_admin AS a, $db_category AS c WHERE n.article_id=".(integer)$_GET['id']." AND n.article_author_id=a.admin_id AND c.category_id=n.article_category_id") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_article_all_del") <> 1){
		if ((CheckPriv($groups_priv_stream_del) == 1 && $_SESSION['loginid'] == $ar['article_author_id']) || (CheckPriv($groups_priv_stream_del) == 1 && $admini == "TRUE")){
			// Vstup povolen
		} else {
			// Vstup zamitnut
			echo _NOTENOUGHPRIV;ShowMain();exit;
		}
	}
	
	
	echo Menu();
	
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
	echo "			<td align=\"right\"><a href=\"modul_streams.php?action=stream_edit&amp;id=".$ar['article_id']."&amp;project=esuba&amp;search_c=&act=article&page=&amp;podle=article_id&amp;ser=DESC\">".$ar['article_id']."</a></td>";
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
	echo "				<form action=\"sys_save.php?action=stream_del&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
	echo "		 		<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
	echo "				<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "				<input type=\"hidden\" name=\"page\" value=\"".$_GET['page']."\">\n";
	echo "				<input type=\"hidden\" name=\"podle\" value=\"".$_GET['podle']."\">\n";
	echo "				<input type=\"hidden\" name=\"ser\" value=\"".$_GET['ser']."\">\n";
	echo "				<input type=\"hidden\" name=\"search_c\" value=\"".$_GET['search_c']."\">\n";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "				<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "				</form>\n";
	echo "			</td>\n";
	echo "			<td width=\"800\" valign=TOP>\n";
	echo "				<form action=\"modul_streams.php?action=showmain&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
	echo "				<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
	echo "				<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "				<input type=\"hidden\" name=\"page\" value=\"".$_GET['page']."\">\n";
	echo "				<input type=\"hidden\" name=\"podle\" value=\"".$_GET['podle']."\">\n";
	echo "				<input type=\"hidden\" name=\"ser\" value=\"".$_GET['ser']."\">\n";
	echo "				<input type=\"hidden\" name=\"search_c\" value=\"".$_GET['search_c']."\">\n";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "				<input type=\"hidden\" name=\"confirm\" value=\"false\">\n";
	echo "				</form>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	</table>\n";
}
// Rekne skriptu v header.php ze ma aktivovat tinyMCE editor
$tinymce_init_mode = "article"; // pouzije se v inc.header.php pro inicializaci TinyMCE
include ("inc.header.php");
	if ($_GET['action'] == "") { ShowMain(); }
	if ($_GET['action'] == "showmain") { ShowMain(); }
	if ($_GET['action'] == "stream_edit") { AddStream(); }
	if ($_GET['action'] == "stream_del") { DeleteStream(); }
	if ($_GET['action'] == "stream_add") { AddStream(); }
	if ($_GET['action'] == "open") { ShowMain(); }
	if ($_GET['action'] == "zmeny") { ShowMain(); }
	if ($_GET['action'] == "close") { ShowMain(); }
	if ($_GET['action'] == "logout") { Logout(); }
	if ($_GET['action'] == "komentar"){Comments($_GET['id'],"article");}
	if ($_GET['action'] == "send"){Save("article");}	// Ulozi komentar
	if ($_GET['action'] == "delete_comments"){DeleteComm();}
	if ($_GET['action'] == "replace_comments"){ReplaceComm($_POST['r_topic'],$_POST['r_tekst'],$_POST['r_modul']);}	
	if ($_GET['action'] == "kill_use_article"){KillUseById($_GET['id'],$_GET['action']);ShowMain();} // Adminum s pravy kill_use umozni odstranit priznak "pouzivano" u novinek a aktualit
include ("inc.footer.php");