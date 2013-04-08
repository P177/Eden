<?php /*ř*/
include "modul_comments.php";
/***********************************************************************************************************
*
*		ZOBRAZENI ANKET
*
***********************************************************************************************************/
function Menu(){
	
	global $db_tournaments;
	
	$res = mysql_query("SELECT tournament_id FROM $db_tournaments") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_num_rows($res);
	
	switch ($_GET['action']){
		case "tournament_add":
			$title = _TOURNAMENT_ADD;
			$link = "<a href=\"modul_tournaments.php?project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a>";
			break;
		case "tournament_edit":
			$title = _TOURNAMENT_EDIT;
			$link = "<a href=\"modul_tournaments.php?project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a>";
			break;
		case "tournament_del":
			$title = _TOURNAMENT_DEL;
			$link = "<a href=\"modul_tournaments.php?project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a>";
			break;
		default:
			$title = _TOURNAMENT;
			$link = "<a href=\"modul_tournaments.php?action=tournament_add&amp;project=".$_SESSION['project']."\">"._TOURNAMENT_ADD."</a>";
	}
	
	$menu =  "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	$menu .= "	<tr>\n";
	$menu .=  "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">".$title."</td>\n";
	$menu .=  "	</tr>\n";
	$menu .=  "	<tr>\n";
	$menu .=  "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"".$title."\" alt=\"".$title."\">".$link."</td>\n";
	$menu .=  "		<td align=\"right\">"._TOURNAMENT_SUBMITED.": ".$num."</td>\n";
	$menu .=  "	</tr>\n";
	if ($_GET['msg']){
		$menu .= "<tr><td class=\"msg\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	$menu .=  "</table>\n";
	
	return $menu;
	
}
/***********************************************************************************************************
*
*		ZOBRAZENI ANKET
*
***********************************************************************************************************/
function ShowMain(){
	
	global $db_tournaments,$db_admin,$db_comments;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	$res_num = mysql_query("SELECT COUNT(*) AS suma FROM $db_tournaments") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_num = mysql_fetch_array($res_num);
	
	$m = 0;
	if (empty($_GET['page'])) {$page = 1;} else {$page = $_GET['page'];} // i kdyz je promenna $page prazdna nastavi se pocet starnek na 1
	$hits = 50;
	$stw2 = ($ar_num['suma']/$hits);
	$stw2 = (integer) $stw2;
	if ($ar_num['suma']%$hits > 0) {$stw2++;}
	$np = $page+1;
	$pp = $page-1;
	if ($page == 1) { $pp = 1; }
	if ($np > $stw2) { $np = $stw2;}
	
	$sp = ($page-1)*$hits;
	$ep = ($page-1)*$hits+$hits;
	
	$limit = "LIMIT ".(integer)$sp.", ".(integer)$hits."";
	
	$res = mysql_query("SELECT t.*, a.admin_nick 
	FROM $db_tournaments AS t 
	LEFT JOIN $db_admin AS a ON t.tournament_admin_id=a.admin_id 
	ORDER BY t.tournament_id DESC 
	$limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"125\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"30\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
        echo "		<td width=\"90\"><span class=\"nadpis-boxy\">"._TOURNAMENT_DATE."</span></td>\n";
	echo "		<td width=\"90\"><span class=\"nadpis-boxy\">"._TOURNAMENT_NAME."</span></td>\n";
        echo "		<td width=\"90\"><span class=\"nadpis-boxy\">"._TOURNAMENT_FORMAT."</span></td>\n";
	echo "		<td width=\"100\"><span class=\"nadpis-boxy\">"._TOURNAMENT_AUTHOR."</span></td>\n";
	echo "	</tr>";
	$i=1;
	while ($ar = mysql_fetch_array($res)){
		$m++;
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"125\">\n";
		   			if (CheckPriv("groups_tournament_edit") == 1){ echo "<a href=\"modul_tournaments.php?action=tournament_edit&amp;project=".$_SESSION['project']."&amp;id=".$ar['tournament_id']."&amp;page=".$page."&amp;hits=".$hits."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._POLL_EDITPOLL."\"></a> ";}
					if (CheckPriv("groups_tournament_del") == 1){ echo "<a href=\"modul_tournaments.php?action=tournament_del&amp;project=".$_SESSION['project']."&amp;id=".$ar['tournament_id']."&amp;page=".$page."&amp;hits=".$hits."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._TOURNAMENT_DELETEPTOURNAMENT."\"></a> ";}
		echo "	</td>\n";
		echo "	<td width=\"30\" align=\"right\">".$ar['tournament_id']."</td>\n";
		echo "	<td>".FormatDate($ar['tournament_date'],"d.m.Y")."</td>\n";
		echo "	<td>".ShortText($ar['tournament_name'],65)."</td>\n";
		echo "	<td width=\"100\" align=\"left\">".FormatTournamentFormat($ar['tournament_format'])."</td>\n";
                echo "	<td width=\"100\" align=\"left\">".$ar['admin_nick']."</td>\n";
		echo "</tr>";
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
			 		echo " <a href=\"modul_tournaments.php?page=".$i."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."\">".$i."</a> ";
				}
			}
			//Zobrazeni sipek s predchozimi a dalsimi strankami novinek
			echo "<center><a href=\"modul_tournaments.php?page=".$pp."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."\">"._CMN_PREVIOUS."</a> <--|--> <a href=\"modul_tournaments.php?page=".$np."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."\">"._CMN_NEXT."</a></center>";
			echo "</td></tr></table>";
		}
}

/***********************************************************************************************************
*
*		PRIDAVANI A EDITACE ANKET
*
***********************************************************************************************************/
function AddTournament(){
	
	global $db_admin,$db_tournaments;
	
	// CHECK PRIVILEGIES
	if ($_GET['action'] == "tournament_add"){
		if (CheckPriv("groups_wp_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($_GET['action'] == "tournament_edit"){
		if (CheckPriv("groups_wp_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	} else {
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	if ($_GET['action'] == "tournament_edit"){
		$res = mysql_query("SELECT * FROM $db_tournaments WHERE tournament_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$tournament_name = $ar['tournament_name'];
		$tournament_format = $ar['tournament_format'];
                $tournament_regularity = $ar['tournament_regularity'];
                $tournament_date = $ar['tournament_date'];
                $tournament_time = $ar['tournament_time'];
                $tournament_registration = $ar['tournament_registration_start'];
                $tournament_buyin = $ar['tournament_buyin'];
                $tournament_prizes = $ar['tournament_prizes'];
                $tournament_description = $ar['tournament_description'];
	}
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"2\"></td><form action=\"sys_save.php?action=".$_GET['action']."&amp;page=".$_GET['page']."&amp;hits=".$_GET['hits']."\" method=\"post\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"200\"><strong>"._TOURNAMENT_NAME.":</strong> <br><br></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"name\" size=\"100\" value=\"".$tournament_name."\" required=\"required\" maxlength=\"20\"><br><br></td>\n";
	echo "	</tr>\n";
        echo "	<tr>\n";
	echo "		<td align=\"right\"  width=\"200\"><strong>"._TOURNAMENT_FORMAT.":</strong> <br><br></td>\n";
	echo "		<td align=\"left\" ><select name=\"format\">\n";
        echo "          <option value=\"doubledraft\" "; if($tournament_format == "doubledraft") {echo " selected=\"selected\"";} echo">Double Draft</option>\n";
        echo "          <option value=\"draft\" "; if($tournament_format == "draft") {echo " selected=\"selected\"";} echo">Draft</option>\n";
        echo "          <option value=\"extended\" "; if($tournament_format == "extended") {echo " selected=\"selected\"";} echo">Extended</option>\n";
        echo "          <option value=\"highlander\" "; if($tournament_format == "highlander") {echo " selected=\"selected\"";} echo">Highlander</option>\n";
        echo "          <option value=\"legacy\" "; if($tournament_format == "legacy") {echo " selected=\"selected\"";} echo">Legacy</option>\n";
        echo "          <option value=\"modern\" "; if($tournament_format == "modern") {echo " selected=\"selected\"";} echo">Modern</option>\n";
        echo "          <option value=\"standard\" "; if($tournament_format == "standard") {echo " selected=\"selected\"";} echo">Standard</option>\n";
        echo "          <option value=\"vintage\" "; if($tournament_format == "vintage") {echo " selected=\"selected\"";} echo">Vintage</option>\n";
        echo "          </select><br><br></td>\n";
	echo "	</tr>\n";
        echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"200\"><strong>"._TOURNAMENT_REGULARITY.":</strong> <br><br></td>\n";
	echo "		<td align=\"left\" ><select name=\"regularity\">\n";
        echo "          <option value=\"onetime\">Jednorázový</option>\n";
        echo "          <option value=\"weekly\">Týdenní</option>\n";
        echo "          </select><br><br></td>\n";
	echo "	</tr>\n";
        echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"200\"><strong>"._TOURNAMENT_DATE.":</strong> <br><br></td>\n";
	echo "		<td align=\"left\" ><input type=\"date\" name=\"date\" value=\"".$tournament_date."\" required=\"required\"><br><br></td>\n";
	echo "	</tr>\n";
        echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"200\"><strong>"._TOURNAMENT_REGISTRATION.":</strong> <br><br></td>\n";
	echo "		<td align=\"left\" ><input type=\"time\" name=\"registration_start\" value=\"".$tournament_registration."\" required=\"required\"><br><br></td>\n";
	echo "	</tr>\n";
        echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"200\"><strong>"._TOURNAMENT_TIME.":</strong> <br><br></td>\n";
	echo "		<td align=\"left\" ><input type=\"time\" name=\"time\" value=\"".$tournament_time."\" required=\"required\"><br><br></td>\n";
	echo "	</tr>\n";
        echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"200\"><strong>"._TOURNAMENT_BUYIN.":</strong> <br><br></td>\n";
	echo "		<td align=\"left\" ><input type=\"text\" name=\"buyin\" size=\"6\" value=\"".$tournament_buyin."\"> Kč<br><br></td>\n";
	echo "	</tr>\n";
        echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"200\"><strong>"._TOURNAMENT_PRIZES.":</strong> <br><br></td>\n";
	echo "		<td align=\"left\" ><input type=\"text\" name=\"prizes\" size=\"100\" value=\"".$tournament_prizes."\"><br><br></td>\n";
	echo "	</tr>\n";
        echo "</table>";
        echo "<div><strong>"._TOURNAMENT_DESCRIPTION.":</strong><br>";
	echo "							<textarea id=\"tournament_description\" name=\"description\" class=\"tournament_description\" rows=\"30\" cols=\"60\" style=\"width: 100%\">".$tournament_description."</textarea><br>";
	echo "						</div>";
        echo "<table>";
        echo "	<tr>\n";
	echo "		<td colspan=\"2\"><br><br>\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "			<input type=\"submit\" value=\"". _CMN_SUBMIT."\" class=\"eden_button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}

/***********************************************************************************************************
*
*		MAZANI ANKET
*
***********************************************************************************************************/
function DeleteTournament(){
	
	global $db_tournaments;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_wp_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	echo Menu();
	
	echo "<br>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\">\n";
					$res = mysql_query("SELECT tournament_name, tournament_date FROM $db_tournaments WHERE tournament_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$ar = mysql_fetch_array($res);
					echo "	<strong>"._TOURNAMENT_NAME." :</strong> ".$ar['tournament_name']."<br>\n";
					echo "	<strong>"._TOURNAMENT_DATE." :</strong> ".$ar['tournament_date']."<br>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._TOURNAMENT_CHECK_DELETE."</span></strong></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" width=\"50\">\n";
	echo "		<form action=\"sys_save.php?action=tournament_del&amp;id=".$_GET['id']."&amp;page=".$_GET['page']."&amp;hits=".$_GET['hits']."\" method=\"post\">\n";
	echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\" >\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "		<td valign=\"top\" width=\"800\">\n";
	echo "			<form action=\"modul_tournaments.php?action=showmain&amp;id=".$_GET['id']."&amp;page=".$_GET['page']."&amp;hits=".$_GET['hits']."\" method=\"post\">\n";
	echo "			<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"false\"></form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*
*		MAZANI DAT V ANKETACH
*
*		Odstraneni vsech zaznamu v databazi tykajicich se anket
*
***********************************************************************************************************/
$tinymce_init_mode = "tournament"; // pouzije se v inc.header.php pro inicializaci TinyMCE
include ("inc.header.php");
		if ($_GET['action'] == "") { ShowMain(); }
		if ($_GET['action'] == "showmain") { ShowMain(); }
		if ($_GET['action'] == "tournament_edit") { AddTournament(); }
		if ($_GET['action'] == "tournament_add") { AddTournament(); }
		if ($_GET['action'] == "tournament_del") { DeleteTournament(); }
		if ($_GET['action'] == "open") { ShowMain(); }
		if ($_GET['action'] == "logout") { Logout(); }
include ("inc.footer.php");