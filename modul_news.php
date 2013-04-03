<?php  /*r*/
include "modul_comments.php";
/***********************************************************************************************************
*																											
*		ZOBRAZENI SEZNAMU AKTUALIT																			
*																											
***********************************************************************************************************/
function ShowMain(){
	
	global $db_news,$db_eden_log,$db_admin,$db_category,$act;
	global $search_c,$search_a,$db_comments;
	
	if (!isset($act)){$act = htmlspecialchars($_GET['act'], ENT_QUOTES);}
	if (isset($_GET['hits'])){$hits = $_GET['hits'];}elseif (isset($_POST['hits'])){$hits = $_POST['hits'];} else {$hits = 30;}
	if (isset($_GET['search_c'])){$search_c = $_GET['search_c'];}elseif (isset($_POST['search_c'])){$search_c = $_POST['search_c'];} else {$search_c = FALSE;}
	if (isset($_GET['search_a'])){$search_a = $_GET['search_a'];}elseif (isset($_POST['search_a'])){$search_a = $_POST['search_a'];} else {$search_a = FALSE;}
	if (isset($_GET['search_id'])){$search_id = $_GET['search_id'];}elseif (isset($_POST['search_id'])){$search_id = $_POST['search_id'];} else {$search_id = FALSE;}
	if (isset($_GET['search_mode'])){$search_mode = $_GET['search_mode'];}elseif (isset($_POST['search_mode'])){$search_mode = $_POST['search_mode'];} else {$search_mode = FALSE;}
	if (isset($_GET['ondatez'])){$ondatez = $_GET['ondatez'];}elseif (isset($_POST['ondatez'])){$ondatez = $_POST['ondatez'];} else {$ondatez = FALSE;}
	if (isset($_GET['ondatek'])){$ondatek = $_GET['ondatek'];}elseif (isset($_POST['ondatek'])){$ondatek = $_POST['ondatek'];} else {$ondatek = FALSE;}
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	// Jestlize neni vybrano podle ceho se ma tridit, je vybrano podle datumu setupne 
	if ($_GET['ser'] == "" && $_GET['podle'] == ""){
		$podle = "news_id";
		$podle_db = "act.news_id";
		$ser = "DESC";
	} else {
		$podle = htmlspecialchars($_GET['podle'], ENT_QUOTES);
		$podle_db = "act.".htmlspecialchars($_GET['podle'], ENT_QUOTES);
		$ser = htmlspecialchars($_GET['ser'], ENT_QUOTES);
	}
	
	if ($search_mode != 2){
		$news_date_on = formatTimeS(time()); // Dnesni datum
		$news_date_off = formatTimeS(time() - 60 * 60 * 24 * 30); // Datum pred 30 dny
		$ondatez = $news_date_off[1].".".$news_date_off[2].".".$news_date_off[3];
		$ondatek = $news_date_on[1].".".$news_date_on[2].".".$news_date_on[3];
	}
	// Search mode
	if ($search_mode == 1) { // Categories
		// Show articles only from specific category
		if ($search_c != ""){$select_category = " AND act.news_category_id=".(integer)$search_c."";}
   		if ($search_a != ""){$select_author = " AND act.news_author_id=".(integer)$search_a."";}
 		$where = "";
	} elseif ($search_mode == 2) { // Date
		// Show articles only from specific category
		if ($search_c != ""){$select_category = " AND act.news_category_id=".(integer)$search_c."";}
   		if ($search_a != ""){$select_author = " AND act.news_author_id=".(integer)$search_a."";}
		$start = preg_replace ("/([0-9]{1,2}).([0-9]{1,2}).([0-9]{4})/","\${3}\${2}\${1}000000", $ondatez);
		$end = preg_replace ("/([0-9]{1,2}).([0-9]{1,2}).([0-9]{4})/","\${3}\${2}\${1}235959", $ondatek);
 		$where = "AND (act.news_date > ".(float)$start." AND act.news_date < ".(float)$end.")";
	} elseif ($search_mode == 3) { // ID
		$select_category = "";
		$select_author = "";
		$where = "AND act.news_id=".(integer)$search_id;
	} else {
		$select_category = "";
		$select_author = "";
		$where = "";
	}
	
	$amount = mysql_query("SELECT COUNT(act.news_id) FROM $db_news AS act WHERE act.news_id != 0 $where $select_category $select_author") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_fetch_array($amount);
	
	//Timto nastavime pocet prispevku na strance
	$m=0;// nastaveni iterace
	if (empty($_GET['page'])) {$page = 1;} else {$page = (integer)$_GET['page'];} // i kdyz je promenna $page prazdna nastavi se pocet starnek na 1
	//$hits=20; //Zde se nastavuje pocet prispevku
	$stw2 = ($num[0] / $hits);
	$stw2 = (integer)$stw2;
	if ($num[0] % $hits > 0) {$stw2++;}
	$np = $page+1;
	$pp = $page-1;
	if ($page == 1) { $pp = 1; }
	if ($np > $stw2) { $np = $stw2;} 
	
	$sp = ($page - 1) * $hits;
	$ep = ($page - 1) * $hits + $hits;
	
	$limit = "LIMIT ".(integer)$sp.", ".(integer)$hits."";
	
	$res = mysql_query("SELECT act.*, a.admin_id, a.admin_nick, a.admin_uname 
	FROM $db_news AS act, $db_admin AS a 
	WHERE act.news_publish < 2 AND a.admin_id = act.news_author_id $where 
	$select_category 
	$select_author 
	ORDER BY ".mysql_real_escape_string($podle_db)." ".mysql_real_escape_string($ser)." $limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	echo "	<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr>\n";
	echo "			<td align=\"left\" class=\"nadpis\">"._NEWSS."</td>\n";
	echo "			<td>&nbsp;</td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"left\"><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">&nbsp;";
	echo "				<a href=\"modul_news.php?action=&amp;project=".$_SESSION['project']."\">"._NEWS_LIST."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	echo "				<a href=\"modul_news.php?action=news_add&amp;project=".$_SESSION['project']."&amp;act=news\">"._NEWS_ADD."</a>";
	echo "			</td>\n";
	echo "			<td align=\"right\">";
	echo 			_NEWS_SUBMITED.": ".$num[0]."&nbsp; &nbsp;".$sp."-".$ep;
	echo "			</td>\n";
	echo "		</tr>\n";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['msg']){
		echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "	</table>\n";
	echo "	<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr>\n";
	echo "			<td width=\"857\">";
	echo "				<div style=\"float:left;padding:3px 3px 3px 3px;\">";
	echo "					<form action=\"modul_news.php\" method=\"post\" name=\"formSearch\">\n";
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
	echo "				<select name=\"search_c\">\n";
				$res2 = mysql_query("SELECT category_id, category_name FROM $db_category WHERE category_parent=0 AND category_news=1 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						echo "<option value=\"\" selected>"._ARTICLES_SEL_CAT."</option>";
						while ($ar2 = mysql_fetch_array($res2)){
							$cat = $ar2['category_name'];
							echo "<option value=\"".stripslashes($ar2['category_id'])."\" ";
							if ($ar2['category_id'] == $search_c){echo "selected=\"selected\"";}
							echo ">".$cat."</option>";
							$ress2 = mysql_query("SELECT category_id, category_parent, category_name FROM $db_category WHERE category_parent=".(float)$ar2['category_id']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							while ($arr2 = mysql_fetch_array($ress2)){
								if ($arr2['category_parent'] == $ar2['category_id']){$cat2 = "&nbsp;&nbsp;&nbsp;".stripslashes($arr2['category_name']); }
								echo "<option value=\"".$arr2['category_id']."\" ";
								if ($arr2['category_id'] == $search_c){echo "selected=\"selected\"";}
								echo ">".$cat2."</option>";
								$ress3 = mysql_query("SELECT category_id, category_parent, category_name FROM $db_category WHERE category_parent=".(float)$arr2['category_id']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								while ($arr3 = mysql_fetch_array($ress3)){
									if ($arr3['category_parent'] == $arr2['category_id']){$cat3 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".stripslashes($arr3['category_name']); }
									echo "<option value=\"".$arr3['category_id']."\" ";
									if ($arr3['category_id'] == $search_c){echo "selected=\"selected\"";}
									echo ">".$cat3."</option>";
									$ress4 = mysql_query("SELECT category_id, category_parent, category_name FROM $db_category WHERE category_parent=".(float)$arr3['category_id']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
									while ($arr4 = mysql_fetch_array($ress4)){
										if ($arr4['category_parent'] == $arr3['category_id']){$cat4 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".stripslashes($arr4['category_name']); }
										echo "<option value=\"".$arr4['category_id']."\" ";
										if ($arr4['category_id'] == $search_c){echo "selected=\"selected\"";}
										echo ">".$cat4."</option>";
										$ress5 = mysql_query("SELECT category_id, category_parent, category_name FROM $db_category WHERE category_parent=".(float)$arr4['category_id']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
										while ($arr5 = mysql_fetch_array($ress5)){
											if ($arr5['category_parent'] == $arr4['category_id']){$cat5 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".stripslashes($arr5['category_name']); }
											echo "<option value=\"".$arr5['category_id']."\" ";
											if ($arr5['category_id'] == $search_c){echo "selected=\"selected\"";}
											echo ">".$cat5."</option>";
											$ress6 = mysql_query("SELECT category_id, category_parent, category_name FROM $db_category WHERE category_parent=".(float)$arr5['category_id']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
											while ($arr6 = mysql_fetch_array($ress6)){
												if ($arr6['category_parent'] == $arr5['category_id']){$cat6 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".stripslashes($arr6['category_name']); }
												echo "<option value=\"".$arr6['category_id']."\" ";
												if ($arr6['category_id'] == $search_c){echo "selected=\"selected\"";}
												echo ">".$cat6."</option>";
											}
										}
									}
								}
							}
						}
					echo "</select>";
					echo "<select name=\"search_a\">";
						$res8 = mysql_query("SELECT admin_id, admin_nick FROM $db_admin WHERE admin_status='admin' ORDER BY admin_uname ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						echo "<option value=\"\" selected>"._ARTICLES_SEL_AUT."</option>";
						while ($ar8 = mysql_fetch_array($res8)){
							echo "<option value=\"".$ar8['admin_id']."\" ";
							if ($ar8['admin_id'] == $ar['news_author_id']) { echo "selected=\"selected\"";}
							echo ">".$ar8['admin_nick']."</option>";
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
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"120\"><span class=\"nadpis-boxy\">". _CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"45\" align=\"center\" "; if ($podle == "news_id"){echo "bgcolor=\"#FFDEDF\"";}echo "><span class=\"nadpis-boxy\">ID</span></td>\n";
	echo "		<td width=\"352\" align=\"center\" "; if ($podle == "news_text"){echo "bgcolor=\"#FFDEDF\"";}echo "><span class=\"nadpis-boxy\">". _NEWS."</span></td>\n";
	echo "		<td width=\"100\" align=\"center\" "; if ($podle == "news_author"){echo "bgcolor=\"#FFDEDF\"";}echo "><span class=\"nadpis-boxy\">". _NEWS_AUTHOR."</span></td>\n";
	echo "		<td align=\"center\" "; if ($podle == "news_date"){echo "bgcolor=\"#FFDEDF\"";}echo "><span class=\"nadpis-boxy\">". _NEWS_DATE_FROM."</span></td>\n";
	echo "		<td align=\"center\" "; if ($podle == "news_date_edit"){echo "bgcolor=\"#FFDEDF\"";}echo "><span class=\"nadpis-boxy\">". _NEWS_DATE_ED."</span></td>\n";
	echo "		<td align=\"center\" "; if ($podle == "news_category_id"){echo "bgcolor=\"#FFDEDF\"";}echo "><span class=\"nadpis-boxy\">". _NEWS_CATEGORY."</span></td>\n";
	echo "	</tr>\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"120\">&nbsp;</td>\n";
	echo "		<td width=\"45\" align=\"center\" "; if ($podle == "news_id"){echo "bgcolor=\"#FFDEDF\"";}echo "><span class=\"nadpis-boxy\"><a href=\"modul_news.php?project=".$_SESSION['project']."&podle=news_id&ser=asc&amp;hits=".$hits."\"><img src=\"images/asc.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\"". _CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_news.php?project=".$_SESSION['project']."&podle=news_id&ser=desc&amp;hits=".$hits."\"><img src=\"images/des.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "		<td width=\"352\" align=\"center\" "; if ($podle == "news_text"){echo "bgcolor=\"#FFDEDF\"";}echo "><span class=\"nadpis-boxy\"><a href=\"modul_news.php?project=".$_SESSION['project']."&podle=news_headline&ser=asc&amp;hits=".$hits."\"><img src=\"images/asc.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\"". _CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_news.php?project=".$_SESSION['project']."&podle=news_headline&ser=desc&amp;hits=".$hits."\"><img src=\"images/des.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "		<td width=\"100\" align=\"center\" "; if ($podle == "news_author"){echo "bgcolor=\"#FFDEDF\"";}echo "><span class=\"nadpis-boxy\"><a href=\"modul_v.php?project=".$_SESSION['project']."&podle=news_author&ser=asc&amp;hits=".$hits."\"><img src=\"images/asc.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\"". _CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_news.php?project=".$_SESSION['project']."&podle=news_author&ser=desc&amp;hits=".$hits."\"><img src=\"images/des.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "		<td width=\"70\" align=\"center\" "; if ($podle == "news_date"){echo "bgcolor=\"#FFDEDF\"";}echo "><span class=\"nadpis-boxy\"><a href=\"modul_news.php?project=".$_SESSION['project']."&podle=news_date&ser=asc&amp;hits=".$hits."\"><img src=\"images/asc.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\"". _CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_news.php?project=".$_SESSION['project']."&podle=news_date&ser=desc&amp;hits=".$hits."\"><img src=\"images/des.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "		<td width=\"70\" align=\"center\" "; if ($podle == "news_date_edit"){echo "bgcolor=\"#FFDEDF\"";}echo "><span class=\"nadpis-boxy\"><a href=\"modul_news.php?project=".$_SESSION['project']."&podle=news_date_edit&ser=asc&amp;hits=".$hits."\"><img src=\"images/asc.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\"". _CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_news.php?project=".$_SESSION['project']."&podle=news_date_edit&ser=desc&amp;hits=".$hits."\"><img src=\"images/des.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "		<td width=\"150\" align=\"center\" "; if ($podle == "news_category_id"){echo "bgcolor=\"#FFDEDF\"";}echo "><span class=\"nadpis-boxy\"><a href=\"modul_news.php?project=".$_SESSION['project']."&podle=news_category_id&ser=asc&amp;hits=".$hits."\"><img src=\"images/asc.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\"". _CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_news.php?project=".$_SESSION['project']."&podle=news_category_id&ser=desc&amp;hits=".$hits."\"><img src=\"images/des.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "	</tr>";
	$i=1;
	while ($ar = mysql_fetch_array($res)){
		$m++;
		$datum = FormatTimestamp($ar['news_date']);
		$datum_h = FormatTimestamp($ar['news_date']);
		$datumed = FormatTimestamp($ar['news_date_on']);
		$text = TreatText($ar['news_text'],0);
		$showtime = date("YmdHis");
		$res2 = mysql_query("SELECT category_name, category_admin FROM $db_category WHERE category_id=".(integer)$ar['news_category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar2 = mysql_fetch_array($res2);
		
		// Zjisteni poctu komentářů
		$res4 = mysql_query("SELECT comment_id FROM $db_comments WHERE comment_pid=".(integer)$ar['news_id']." AND comment_modul='news'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num4 = mysql_num_rows($res4);
		
		$headline = wordwrap( $ar['news_headline'], 60, "\n", 1); // Zalomi text
		$headline = substr ($headline, 0, 120); // Zobrazeni jen 120 znaku
		// Zabezpeceni zobrazeni moznosti jen vyvolenym
		if ($_GET['action'] == "open" & $_GET['id'] == $ar['news_id']) {$command = "close";} else {$command = "open";}
		$admini = explode (" ", $ar2['category_admin']);
		$num02 = count($admini);
		if ($_SESSION['login'] == ""){$admini02 = "FALSE";} else {$admini02 = in_array($_SESSION['login'], $admini);}
		$res9 = mysql_query("SELECT COUNT(*) FROM $db_eden_log WHERE log_news_id=".(integer)$ar['news_id']." AND log_action=4") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num9 = mysql_fetch_array($res9);
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "		<td width=\"120\" valign=\"middle\">";
		echo "			<a href=\"modul_news.php?action=".$command."&amp;id=".$ar['news_id']."&topic_id=".$ar['news_category_id']."&amp;project=".$_SESSION['project']."&podle=".$podle."&ser=".$ser."&amp;hits=".$hits."\"><img src=\"images/sys_".$command.".gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""; if ($command == "open"){echo _CMN_OPEN;} else {echo _CMN_CLOSE;} echo "\" title=\""; if ($command == "open"){echo _CMN_OPEN;} else {echo _CMN_CLOSE;} echo "\"></a>";
					if ((CheckPriv("groups_news_edit") == 1 && $admini02 == "TRUE") || (CheckPriv("groups_article_all_edit") == 1 ||	$_SESSION['loginid'] == $ar['news_author_id'])){ /*Pokud nekdo edituje, nebo maze tak se nezobrazi ikona pro editaci a mazani*/if ($ar['news_user_use'] == "0" || $ar['news_user_use'] == $_SESSION['loginid']){echo " <a href=\"?action=news_edit&amp;id=".$ar['news_id']."&topic_id=".$ar['news_category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}}
					if ((CheckPriv("groups_news_del") == 1 && $admini02 == "TRUE") || (CheckPriv("groups_news_all_del") == 1) || (CheckPriv("groups_article_all_del") == 1 ||	$_SESSION['loginid'] == $ar['news_author_id'])){ /*Pokud nekdo edituje, nebo maze tak se nezobrazi ikona pro editaci a mazani*/if ($ar['news_user_use'] == "0" || $ar['news_user_use'] == $_SESSION['loginid']){echo " <a href=\"?action=news_del&amp;id=".$ar['news_id']."&topic_id=".$ar['news_category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";} else { if (CheckPriv("groups_news_all_kill_user") == 1){echo "<a href=\"?action=kill_use_news&amp;id=".$ar['news_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_killuse.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._ARTICLES_KILL_USE." - ".GetUserName($ar['news_user_use'])." - ".$ar['news_user_open']."\"></a>";}}}
					if (CheckPriv("groups_article_changes") == 1){echo " <a href=\"modul_news.php?action=zmeny&amp;id=".$ar['news_id']."&topic_id=".$ar['news_category_id']."&amp;project=".$_SESSION['project']."&search_c=".$search_c."&search_a=".$search_a."&act=".$act."&amp;page=".$_GET['page']."\"><img src=\"images/sys_zmeny.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._ARTICLES_ZMENY." - ".$num9[0]."\" title=\""._ARTICLES_ZMENY." - ".$num9[0]."\"></a>";}
					if (CheckPriv("groups_comments_change") == 1 || $admini02 == "TRUE"){echo " <a href=\"modul_news.php?action=komentar&amp;id=".$ar['news_id']."&amp;id2=".$ar['news_category_id']."&amp;project=".$_SESSION['project']."\" title=\""._NUMCOM."\"><span style=\"font-size:12px; font-weight: bold;\">".$num4."</span></a>";}
		echo "		</td>";
		echo "		<td width=\"45\">".$ar['news_id']."</td>";
		echo "		<td width=\"352\">"; if ($ar['news_user_use'] != "0" && $ar['news_user_use'] != $_SESSION['loginid']){echo "<img src=\"images/sys_use.gif\" width=\"18\" height=\"18\" border=\"0\" alt=\"".$ar['news_user_use']."\">";}if ($ar['news_publish'] == "0"){echo "<img src=\"images/sys_no.gif\" alt=\""._CMN_DONT_PUBLISH."\" title=\""._CMN_DONT_PUBLISH."\" width=\"12\" height=\"12\" border=\"0\"> ";} echo $headline."</td>";
		echo "		<td width=\"100\" align=\"left\" valign=\"top\">".$ar['admin_nick']."</td>";
		echo "		<td align=\"center\" width=\"70\" valign=\"top\">".$datum."<br></td>";
		echo "		<td align=\"center\" width=\"70\" valign=\"top\">"; if ( $showtime < $ar['news_date_on'] OR $showtime > $ar['news_date_off']){echo "<span class=\"red\">".$datumed."</span>";} else {echo $datumed;} echo "</td>";
		echo "		<td align=\"left\" width=\"150\" valign=\"top\">".$ar2['category_name']."</td>";
		echo "	</tr>";
		if ($_GET['action'] == "open" & $_GET['id'] == $ar['news_id']) { echo "<tr bgcolor=\"#EEEEEE\"><td width=\"857\" style=\"padding-left:120px;\" colspan=\"7\"><br />".$text."<br /><br /></td></tr>";}
		if ($_GET['action'] == "zmeny" & $_GET['id'] == $ar['news_id']){
			// Zjisteni poctu obrazku v databazi k jednotlivemu prispevku
			$res_log = mysql_query("SELECT log_date, log_admin_id, INET_NTOA(log_ip) AS log_ip FROM $db_eden_log WHERE log_news_id=".(integer)$_GET['id']." AND log_action=4 ORDER BY log_date DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while ($ar_log = mysql_fetch_array($res_log)){
				echo "<tr bgcolor=\"#EEEEEE\">";
				echo "	<td colspan=\"7\">".formatDatetime($ar_log['log_date'],"d.m.Y H:i:s")."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ID ".$ar_log['log_admin_id']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".GetUserName($ar_log['log_admin_id'])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$ar_log['log_ip']."</td>";
				echo "</tr>";
			}
		}
		$i++;
	}
	echo "</table>";
	// Pokud je novinek vice, nezli se vejde na 1 stranku zobrazi se jejich pocitani a prechazeni mezi nima 
	if ($stw2 > 1){ 
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "<tr><td height=\"30\">";
		echo _CMN_SELECTPAGE; 
		//Zobrazeni cisla poctu stranek
		for ($i=1;$i<=$stw2;$i++) {
		if ($page == $i) {
			echo " <strong>".$i."</strong>";
		} else {
			echo " <a href=\"modul_news.php?page=".$i."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."&search_c=".$search_c."&search_a=".$search_a."&podle=".$podle."&ser=".$ser."&search_mode=".$search_mode."&ondatez=".$ondatez."&ondatek=".$ondatek."\">".$i."</a> ";
			}
		}
		//Zobrazeni sipek s predchozimi a dalsimi strankami novinek
		echo "<center><a href=\"modul_news.php?page=".$pp."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."&search_c=".$search_c."&search_a=".$search_a."&podle=".$podle."&ser=".$ser."&search_mode=".$search_mode."&ondatez=".$ondatez."&ondatek=".$ondatek."\">"._CMN_PREVIOUS."</a> <--|--> <a href=\"modul_news.php?page=".$np."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."&search_c=".$search_c."&search_a=".$search_a."&podle=".$podle."&ser=".$ser."&search_mode=".$search_mode."&ondatez=".$ondatez."&ondatek=".$ondatek."\">"._CMN_NEXT."</a></center>";
		echo "</td></tr></table>";
	}
}

/***********************************************************************************************************
*																											
*		PRIDAVANI A EDITACE AKTUALIT 																		
*																											
***********************************************************************************************************/
function AddNews(){
	
	global $db_news,$db_category,$db_admin,$db_setup;
	global $eden_cfg;
	
	$datum = date("YmdHis");
	$picdbfilename = Cislo();
	$res2 = mysql_query("SELECT news_author_id, news_user_use FROM $db_news WHERE news_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar2 = mysql_fetch_array($res2);
	$res = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".(float)$_GET['topic_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	$admini = explode (" ", $ar['category_admin']);
	if ($_SESSION['login'] == ""){$admini = "FALSE";} else {$admini = in_array($_SESSION['login'], $admini);}
	
	// Provereni opravneni
	if ($_GET['action'] == "news_add"){
		if (CheckPriv("groups_news_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	} elseif ($_GET['action'] == "news_edit"){
		if (CheckPriv("groups_news_all_edit") <> 1){
			if ((CheckPriv("groups_news_edit") == 1 && $_SESSION['loginid'] == $ar2['news_author_id']) || (CheckPriv("groups_edit") == 1 && $admini == "TRUE")){
				// Vstup povolen
				if ($ar2['news_user_use'] == "0" || $ar2['news_user_use'] == $_SESSION['loginid']){
					// Zapsani k novince jmeno uzivatele, ktery otevrel novinku
					mysql_query("UPDATE $db_news SET news_user_use=".(integer)$_SESSION['loginid'].", news_user_open=".(float)$datum." WHERE news_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				} else {
					echo $ar2['news_user_use'];
					//ShowMain();
					exit;
				}
			} else {
				// Vstup zamitnut
				echo _NOTENOUGHPRIV;ShowMain();exit;
			}
		}
	} else {
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	
 	$res = mysql_query("SELECT * FROM $db_news WHERE news_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
 	$ar = mysql_fetch_array($res);
 	
 	$news_text = PrepareFromDB($ar['news_text'],1);
 	//$news_text = wordwrap( $news_text, 100, "\n", 1);
 	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
 	echo "	<tr>";
 	echo "		<td align=\"left\" class=\"nadpis\">";
 	echo "			"._NEWS." - "; if ($_GET['action'] == "news_add"){echo _NEWS_ADD;} else {echo _NEWS_EDIT." ID: ".$_GET['id'];}
 	echo "		</td>";
 	echo "		<td>&nbsp;</td>";
 	echo "	<tr>";
 	echo "	<tr>";
 	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">&nbsp;<a href=\"modul_news.php?project=".$_SESSION['project']."\">"._NEWS_LIST."</a></td>";
 	echo "		<td align=\"right\">";
 					$res = mysql_query("SELECT COUNT(*) FROM $db_news") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
 					$num = mysql_fetch_array($res);
 					echo _NEWS_SUBMITED.": ".$num[0];
 	echo "		</td>";
 	echo "	</tr>";
 	echo "</table>";
	echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td>";
	echo "			<form action=\"sys_save.php?action="; if ($_GET['action'] == "news_add"){echo "news_add";} else {echo "news_edit";} echo "&ser=".$_GET['ser']."&podle=".$_GET['podle']."\" name=\"form1\" method=\"post\">";
	echo "			<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "				<tr>";
	echo "					<td align=\"left\" valign=\"top\">";
	echo "						<strong>"._NEWS_HEADLINE."</strong><br>";
	echo "						<input type=\"text\" name=\"news_headline\" value=\""; if ($_GET['action'] == "news_edit"){ echo $ar['news_headline'];} echo "\" size=\"60\"><br /><br />";
	echo "						<strong>"._NEWS_CATEGORY."</strong><img src=\"images/a_bod.gif\" width=\"145\" height=\"5\" border=\"0\" alt=\"\"><!-- <strong>"._CATEGORYSUB."</strong> --><br>";
								// Select category
								echo EdenCategorySelect($ar['news_category_id'], "news", "news_section", 0);
								
								echo "<br><br />";
		 						if (CheckPriv("groups_change_redactor") == 1){
									echo "<strong>"._ARTICLES_FUNC_CHANGE_REDACTOR."</strong><br />";
									echo "<select name=\"zmena_redaktora\">";
										$reszr = mysql_query("SELECT admin_id, admin_uname FROM $db_admin WHERE admin_status='admin' ORDER BY admin_uname ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
										while ($arzr = mysql_fetch_array($reszr)){
											echo "<option name=\"zmena_redaktora\" value=\"".$arzr['admin_id']."\" "; 
											if ($arzr['admin_id'] == $ar['news_author_id'] || ($_GET['action'] == "news_add" && $arzr['admin_id'] == $_SESSION['loginid'])) { echo "selected=\"selected\"";}
											echo">".$arzr['admin_uname']."</option>";
										}
									echo "</select>";
									echo "<br><br>";
								}
	echo "					</td>\n";


	echo "					<td width=\"250\" align=\"left\" valign=\"top\">";
								if ($article_parent_id == 0){
									echo "<input type=\"checkbox\" name=\"news_publish\" value=\"1\""; if ($ar['news_publish'] == "1" || $ar['news_publish'] == ""){echo "CHECKED";} echo ">"._CMN_PUBLISH."<br><br>";
									echo "<strong>"._NEWS_TAGS."</strong><br />";
									echo "<select name=\"news_tags[]\" multiple=\"multiple\" size=\"8\" style=\"width: 200px\">";
										$res_tags = mysql_query("SELECT tag_id, tag_name FROM "._DB_TAGS." ORDER BY tag_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
										while ($ar_tags = mysql_fetch_array($res_tags)){
											$res_news_tags = mysql_query("SELECT COUNT(*) AS num FROM "._DB_NEWS_TAGS." WHERE news_news_id = ".(integer)$_GET['id']." AND news_tag_id = ".(integer)$ar_tags['tag_id']."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
											$ar_news_tags = mysql_fetch_array($res_news_tags);
											echo "<option value=\"".$ar_tags['tag_id']."\" ";
											if ($ar_news_tags['num'] > 0) { echo "selected=\"selected\"";}
											echo">".$ar_tags['tag_name']."</option>";
										}
									echo "</select>";
									
								} else {
									echo "<input type=\"hidden\" name=\"news_publish\" value=\"1\">";
								}
	echo "						<br /><br />";
	echo "					</td>\n";


	echo "					<td width=\"249\" align=\"left\" valign=\"top\"><strong>"._NEWS_DATE_ON."</strong><br>\n";
				 						if ($_GET['action'] == "news_add"){
											$news_date_on = formatTimeS(time());
											echo "<script language=\"javascript\">\n";
											echo "	var StartDate = new ctlSpiffyCalendarBox(\"StartDate\", \"form1\", \"news_date_start\", \"btnDate1\",\"".$news_date_on[1].".".$news_date_on[2].".".$news_date_on[3]."\",scBTNMODE_CUSTOMBLUE);\n";
											echo "	var EndDate = new ctlSpiffyCalendarBox(\"EndDate\", \"form1\", \"news_date_end\", \"btnDate2\",\"01.01.2050\",scBTNMODE_CUSTOMBLUE);\n";
											echo "</script>\n";
											echo "<script language=\"javascript\">StartDate.writeControl(); StartDate.dateFormat=\"dd.MM.yyyy\";</script> <strong>-</strong>";
											echo "<select name=\"news_date_on_4\">\n";
													for ($i=0;$i<=23;$i++){
														echo "<option value=\"".$i."\" "; if ($news_date_on[4] == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>\";\n";
													}
											echo "</select><strong>:</strong>";
											echo "<select name=\"news_date_on_5\">\n";
													for ($i=0;$i<=59;$i++){
														echo "<option value=\"".$i."\" "; if ($news_date_on[5] == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
													}
											echo "</select><strong>:</strong>00<br><br>\n";
											echo "<strong>"._NEWS_DATE_OFF."</strong><br>\n";
											echo "<script language=\"javascript\">EndDate.writeControl(); EndDate.dateFormat=\"dd.MM.yyyy\";</script> <strong>-</strong>";
											echo "<select name=\"news_date_off_4\">\n";
												  	for ($i=0;$i<=23;$i++){
														echo "<option value=\"".$i."\" "; if (0 == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
													}
											echo "</select><strong>:</strong>";
											echo "<select name=\"news_date_off_5\">\n";
													for ($i=0;$i<=59;$i++){
														echo "<option value=\"".$i."\" "; if (1 == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
													}
											echo "</select><strong>:</strong>00<br><br><br>\n";
										} else {
											$news_date_on = $ar['news_date_on'];
											$news_date_off = $ar['news_date_off'];
											echo "<script language=\"javascript\">\n";
											echo "	var StartDate = new ctlSpiffyCalendarBox(\"StartDate\", \"form1\", \"news_date_start\", \"btnDate1\",\"".$news_date_on[6].$news_date_on[7].".".$news_date_on[4].$news_date_on[5].".".$news_date_on[0].$news_date_on[1].$news_date_on[2].$news_date_on[3]."\",scBTNMODE_CUSTOMBLUE);\n";
											echo "	var EndDate = new ctlSpiffyCalendarBox(\"EndDate\", \"form1\", \"news_date_end\", \"btnDate2\",\"".$news_date_off[6].$news_date_off[7].".".$news_date_off[4].$news_date_off[5].".".$news_date_off[0].$news_date_off[1].$news_date_off[2].$news_date_off[3]."\",scBTNMODE_CUSTOMBLUE);\n";
											echo "</script>\n";
											echo "<script language=\"javascript\">StartDate.writeControl(); StartDate.dateFormat=\"dd.MM.yyyy\";</script> <strong>-</strong> ";
											echo "<select name=\"news_date_on_4\">\n";
													for ($i=0;$i<=23;$i++){
														echo "<option value=\"".$i."\" "; if ($news_date_on[8].$news_date_on[9] == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
													}
											echo "</select><strong>:</strong>";
											echo "<select name=\"news_date_on_5\">\n";
													for ($i=0;$i<=59;$i++){
														echo "<option value=\"".$i."\" "; if ($news_date_on[10].$news_date_on[11] == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
													}
											echo "</select><strong>:</strong>00<br><br>\n";
											echo "<strong>"._NEWS_DATE_OFF."</strong><br>\n";
											echo "<script language=\"javascript\">EndDate.writeControl(); EndDate.dateFormat=\"dd.MM.yyyy\";</script> <strong>-</strong> ";
											echo "<select name=\"news_date_off_4\">\n";
													for ($i=0;$i<=23;$i++){
														echo "<option value=\"".$i."\" "; if ($news_date_off[8].$news_date_off[9] == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
													}
											echo "</select><strong>:</strong>";
											echo "<select name=\"news_date_off_5\">\n";
													for ($i=0;$i<=59;$i++){
														echo "<option value=\"".$i."\" "; if ($news_date_off[10].$news_date_off[11] == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
													}
											echo "</select><strong>:</strong>00<br><br>\n";
										}
	echo "						<input type=\"checkbox\" name=\"news_comments\" value=\"1\" "; if ($ar['news_comments'] == 1){echo "checked";} echo "> <span>"._NEWS_ALLOW_COMMENTS."</span><br><br><br><br>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
	echo "	</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td>";
	echo "						<div><strong>"._NEWS.":</strong><br>";
	echo "							<textarea id=\"news_text\" name=\"news_text\" class=\"news_text\" rows=\"30\" cols=\"60\" style=\"width: 100%\">".$news_text."</textarea><br>";
	echo "						</div>";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td>\n";
	echo "						<span><strong>"._NEWS_SOURCE."</strong></span>: <input type=\"text\" name=\"news_source\" size=\"80\" maxlength=\"255\" value=\"".$ar['news_source']."\" /><a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('"._NEWS_HINT_SOURCE."', this, event, '200px')\"><img src=\"images/editor_help.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></a><br><br>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td>"._NEWS_HINTS_2."</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "				<br />\n";
	echo "				<br />\n";
	echo "				<input type=\"checkbox\" name=\"kill_word\" value=\"1\"><span>"._ARTICLES_KILL_WORD."</span>\n";
	echo "				<input type=\"checkbox\" name=\"kill_word_font\" value=\"1\">&lt;font&gt;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"kill_word_style\" value=\"1\">style&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"kill_word_class\" value=\"1\">class&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"kill_word_span\" value=\"1\">&lt;span&gt;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"kill_word_p\" value=\"1\">&lt;p&gt;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"kill_word_ul\" value=\"1\">&lt;ul&gt;&lt;li&gt;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"kill_word_table\" value=\"1\">&lt;table&gt;&lt;tbody&gt;&lt;tr&gt;&lt;td&gt;<br /><br />\n";
	echo "				<input type=\"radio\" name=\"save\" value=\"1\"><span>"._ARTICLES_FUNC_SAVE."</span>&nbsp;&nbsp;&nbsp;\n";
	echo "				<input type=\"radio\" name=\"save\" value=\"2\" checked><span>"._ARTICLES_FUNC_SAVE_SEND."</span>&nbsp;&nbsp;&nbsp;<br /> <br />\n";
	echo "				<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "				<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "				<input type=\"submit\" value=\""._CMN_SUBMIT."\">\n";
	echo "				</form>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}

/***********************************************************************************************************
*																											
*		MAZANI AKTUALIT 																					
*																											
***********************************************************************************************************/
function DeleteNews(){
	
	global $db_news,$db_category;
	
	$res = mysql_query("SELECT act.news_id, act.news_author_id, act.news_date, act.news_headline, c.category_name, c.category_id, c.category_admin FROM $db_news AS act, $db_category AS c WHERE act.news_id=".(integer)$_GET['id']." AND c.category_id = act.news_category_id") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	$admini = explode (" ", $ar['category_admin']);
	if ($_SESSION['login'] == ""){$admini = "FALSE";} else {$admini = in_array($_SESSION['login'], $admini);}
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_news_all_del") <> 1){
		if ((CheckPriv("groups_news_del") == 1 && $_SESSION['loginid'] == $ar['news_author_id']) || (CheckPriv("groups_news_del") == 1 && $admini == "TRUE")){
			// Vstup povolen
		} else {
			// Vstup zamitnut
			echo _NOTENOUGHPRIV;ShowMain();exit;
		}
	}
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._NEWS_DEL."</td>\n";
	echo "		<td>&nbsp;</td>\n";
	echo "	<tr>\n";
	echo "	<tr>\n";
	echo "		<td><a href=\"modul_news.php?project=".$_SESSION['project']."\"><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_MAINMENU."\">\n";
	echo "		"._NEWS_LIST."</a></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td><span class=\"nadpis-boxy\">ID</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._NEWS_HEADLINE."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._NEWS_AUTHOR."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._NEWS_DATE_IN."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._NEWS_CATEGORY."</span></td>\n";
	echo "	</tr>";
	$datum = FormatTimestamp($ar['news_date']);
	
	$headline = PrepareFromDB($ar['news_headline']);
	
	echo "	<tr>";
	echo "		<td>".$ar['news_id']."</td>";
	echo "		<td>".$headline."</td>";
	echo "		<td>".$ar['news_author_id']."</td>";
	echo "		<td>".$datum."</td>";
	echo "		<td width=\"150\" align=\"left\" valign=\"top\">".$ar['category_name']."</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._NEWS_CHECK_DEL."</span></strong></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"50\" valign=\"top\">\n";
	echo "			<form action=\"sys_save.php?action=news_del&amp;id=".$_GET['id']."&ser=".$_GET['ser']."&podle=".$_GET['podle']."\" method=\"post\">\n";
	echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "		<td width=\"800\" valign=\"top\">\n";
	echo "			<form action=\"modul_news.php?action=news_del&amp;id=".$_GET['id']."&ser=".$_GET['ser']."&podle=".$_GET['podle']."\" method=\"post\">\n";
	echo "			<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"false\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
$tinymce_init_mode = "act"; // pouzije se v inc.header.php pro inicializaci TinyMCE
include ("inc.header.php");
	if ($_GET['action'] == "") { ShowMain(); }
	if ($_GET['action'] == "news_edit") { AddNews(); }
	if ($_GET['action'] == "news_del") { DeleteNews(); }
	if ($_GET['action'] == "news_add") { AddNews(); }
	if ($_GET['action'] == "open") { ShowMain(); }
	if ($_GET['action'] == "close") { ShowMain(); }
	if ($_GET['action'] == "zmeny") { ShowMain(); }
	if ($_GET['action'] == "logout") { Logout(); }
	if ($_GET['action'] == "komentar"){Comments($_GET['id'],"news");}
	if ($_GET['action'] == "send"){Save("news");}	// Ulozi komentar
	if ($_GET['action'] == "delete_comments"){DeleteComm();}
	if ($_GET['action'] == "replace_comments"){ReplaceComm($_POST['r_topic'],$_POST['r_tekst'],$_POST['r_modul']);}	
	if ($_GET['action'] == "kill_use_news"){KillUseById($_GET['id'],$_GET['action']);ShowMain();} // Adminum s pravy kill_use umozni odstranit priznak "pouzivano" u novinek a aktualit
include ("inc.footer.php");