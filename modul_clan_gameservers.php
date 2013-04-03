<?php
//********************************************************************************************************
//
//             ZOBRAZENI SEZNAMU KATEGORII
//
//********************************************************************************************************
function ShowMain(){
	
	global $db_gamesrv,$db_country,$db_clan_games;
	global $url_flags;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	// Provereni opravneni
	if ($_GET['action'] == "add_gamesrv"){
		if (CheckPriv(groups_gamesrv_add) <> 1) {$_GET['action'] = "showmain"; echo _NOTENOUGHPRIV;}
	}elseif ($_GET['action'] == "edit_gamesrv"){
		if (CheckPriv(groups_gamesrv_edit) <> 1){$_GET['action'] = "showmain"; echo _NOTENOUGHPRIV;}
	}elseif ($_GET['action'] == "del_gamesrv"){
		if (CheckPriv(groups_gamesrv_del) <> 1){$_GET['action'] = "showmain"; echo _NOTENOUGHPRIV;}
	}
	
	if (isset($_GET['hits'])){$hits = $_GET['hits'];} else {$hits = $_POST['hits'];}
	if (!isset($_GET['action']) || $_GET['action'] == ""){$action = "add_gamesrv";} else {$action = $_GET['action'];}
	$ser		= mysql_real_escape_string($_GET['ser']);
	$podle		= mysql_real_escape_string($_GET['podle']);
	$page		= (integer)$_GET['page'];
	if ($hits < 1){$hits = 100;}
	if ($ser == "" && $podle == ""){
		$podle = "gs.clans_gameservers_name";
		$ser = "ASC";
	} else {
		$podle = "gs.".$_GET['podle'];
		$ser = $_GET['ser'];
	}
	if ($_POST['confirm'] == "true"){
		// Výčet povolených tagů
		$allowtags = "";
		$clans_gameservers_name = PrepareForDB($_POST['gamesrv_name']);
		$clans_gameservers_ip = PrepareForDB($_POST['gamesrv_ip']);
		$clans_gameservers_description = PrepareForDB($_POST['gamesrv_desc']);
		$clans_gameservers_mode = PrepareForDB($_POST['gamesrv_mode']);
		if ($_GET['action'] == "add_gamesrv"){
			mysql_query("INSERT INTO $db_gamesrv VALUES('','$clans_gameservers_name','$clans_gameservers_ip','$clans_gameservers_description','".(float)$_POST['gamesrv_country']."','".(float)$_POST['gamesrv_game']."','$clans_gameservers_mode')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
		if ($_GET['action'] == "edit_gamesrv"){
			mysql_query("UPDATE $db_gamesrv SET
				clans_gameservers_name='$clans_gameservers_name',
				clans_gameservers_ip='$clans_gameservers_ip',
				clans_gameservers_description='$clans_gameservers_description',
				clans_gameservers_country_id=".(float)$_POST['gamesrv_country'].",
				clans_gameservers_game_id=".(float)$_POST['gamesrv_game'].",
				clans_gameservers_mode='$clans_gameservers_mode' WHERE clans_gameservers_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$action = "add_gamesrv";
		}
		if ($_GET['action'] == "del_gamesrv"){
			mysql_query("DELETE FROM $db_gamesrv WHERE clans_gameservers_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			unset($_GET['action']);
			$action = "add_gamesrv";
		}
	}
	if ($action != "add_gamesrv"){
		$res = mysql_query("SELECT * FROM $db_gamesrv WHERE clans_gameservers_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">"._GAMESRV."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\">\n";
	echo "			<a href=\"modul_clan_gameservers.php?action=add_gamesrv&amp;project=".$_SESSION['project']."\">"._GAMESRV_ADD."</a>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	if ($_GET['action'] == "del_gamesrv"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><form action=\"modul_clan_gameservers.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "add_gamesrv" || $action == "add_gamesrv"){echo "add_gamesrv";} elseif ($_GET['action'] == "edit_gamesrv"){echo "edit_gamesrv";} else {echo "del_gamesrv";} echo "\" method=\"post\">\n";
		echo "			<strong><span style=\"color : #FF0000;\">"._GAMESRV_DELCHECK."</span></strong>\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><form action=\"modul_clan_gameservers.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "add_gamesrv" || $action == "add_gamesrv"){echo "add_gamesrv";} else {echo "edit_gamesrv";} echo "\" method=\"post\">\n";
	echo "			<strong>"._GAMESRV_NAME.":</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"gamesrv_name\" maxlength=\"60\" size=\"60\" "; if ($_GET['action'] == "edit_gamesrv" || $_GET['action'] == "del_gamesrv"){echo "value=\"".$ar['clans_gameservers_name']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._GAMESRV_DESC.":</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<textarea cols=\"38\" rows=\"4\" name=\"gamesrv_desc\">"; if ($_GET['action'] == "edit_gamesrv" || $_GET['action'] == "del_gamesrv"){echo $ar['clans_gameservers_description'];} echo "</textarea>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._GAMESRV_COUNTRY.":</strong></td>\n";
	echo "		<td align=\"left\">\n";
					$res2 = mysql_query("SELECT * FROM $db_country ORDER BY country_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	echo "			<select name=\"gamesrv_country\" style=\"width: 250px;\">\";\n";
					while($ar2 = mysql_fetch_array($res2)){
						echo "<option name=\"gamesrv_country\" value=\"".$ar2['country_id']."\""; if ($ar['clans_gameservers_country_id'] == $ar2['country_id']){echo "selected=\"selected\"";} echo ">".$ar2['country_name']."</option>";
					}
	echo "			</select>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._GAMESRV_GAME.":</strong></td>\n";
	echo "		<td align=\"left\">";
	 			$res2 = mysql_query("SELECT * FROM $db_clan_games ORDER BY clan_games_game ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	echo "		<select name=\"gamesrv_game\" style=\"width: 250px;\">";
					while($ar2 = mysql_fetch_array($res2)){
						echo "<option name=\"gamesrv_game\" value=\"".$ar2['clan_games_id']."\""; if ($ar['clans_gameservers_game_id'] == $ar2['clan_games_id']){echo "selected=\"selected\"";} echo ">".$ar2['clan_games_game']."</option>";
					}
	echo "		</select>";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._GAMESRV_MODE.":</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"gamesrv_mode\" maxlength=\"50\" size=\"30\""; if ($_GET['action'] == "edit_gamesrv" || $_GET['action'] == "del_gamesrv"){echo "value=\"".$ar['clans_gameservers_mode']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._GAMESRV_IP.":</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"gamesrv_ip\" maxlength=\"50\" size=\"30\""; if ($_GET['action'] == "edit_gamesrv" || $_GET['action'] == "del_gamesrv"){echo "value=\"".$ar['clans_gameservers_ip']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"2\">\n";
	echo "			<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "add_gamesrv" || $action == "add_gamesrv"){echo _GAMESRV_ADD;} else {echo _GAMESRV_EDIT;} echo "\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"65\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"45\" align=\"center\""; if ($podle == "clans_gameservers_id"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">ID</span></td>\n";
	echo "		<td width=\"45\" align=\"center\""; if ($podle == "clans_gameservers_country_id"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">"._GAMESRV_COUNTRY."</span></td>\n";
	echo "		<td align=\"center\""; if ($podle == "clans_gameservers_game_id"){echo "bgcolor=\"#FFDEDF\"";} echo " width=\"50\"><span class=\"nadpis-boxy\">"._GAMESRV_GAME."</span></td>\n";
	echo "		<td align=\"center\""; if ($podle == "clans_gameservers_mode"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">"._GAMESRV_MODE."</span></td>\n";
	echo "		<td width=\"350\" align=\"center\""; if ($podle == "clans_gameservers_name"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">"._GAMESRV_NAME."</span></td>\n";
	echo "		<td align=\"center\""; if ($podle == "clans_gameservers_ip"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">"._GAMESRV_IP."</span></td>\n";
	echo "	</tr>\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"65\">&nbsp;</td>\n";
	echo "		<td width=\"45\" align=\"center\""; if ($podle == "clans_gameservers_id"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_clan_gameservers.php?project=".$_SESSION['project']."&podle=clans_gameservers_id&ser=asc&amp;hits=".$hits."\"><img src=\"images/asc.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_clan_gameservers.php?project=".$_SESSION['project']."&podle=clans_gameservers_id&ser=desc&amp;hits=".$hits."\"><img src=\"images/des.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "		<td width=\"45\" align=\"center\""; if ($podle == "clans_gameservers_country_id"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_clan_gameservers.php?project=".$_SESSION['project']."&podle=clans_gameservers_country_id&ser=asc&amp;hits=".$hits."\"><img src=\"images/asc.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_clan_gameservers.php?project=".$_SESSION['project']."&podle=clans_gameservers_country_id&ser=desc&amp;hits=".$hits."\"><img src=\"images/des.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "		<td align=\"center\""; if ($podle == "clans_gameservers_game_id"){echo "bgcolor=\"#FFDEDF\"";} echo " width=\"50\"><span class=\"nadpis-boxy\"><a href=\"modul_clan_gameservers.php?project=".$_SESSION['project']."&podle=clans_gameservers_game_id&ser=asc&amp;hits=".$hits."\"><img src=\"images/asc.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_clan_gameservers.php?project=".$_SESSION['project']."&podle=clans_gameservers_game_id&ser=desc&amp;hits=".$hits."\"><img src=\"images/des.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "		<td align=\"center\""; if ($podle == "clans_gameservers_mode"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_clan_gameservers.php?project=".$_SESSION['project']."&podle=clans_gameservers_mode&ser=asc&amp;hits=".$hits."\"><img src=\"images/asc.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_clan_gameservers.php?project=".$_SESSION['project']."&podle=clans_gameservers_mode&ser=desc&amp;hits=".$hits."\"><img src=\"images/des.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "		<td width=\"350\" align=\"center\""; if ($podle == "clans_gameservers_name"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_clan_gameservers.php?project=".$_SESSION['project']."&podle=clans_gameservers_name&ser=asc&amp;hits=".$hits."\"><img src=\"images/asc.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_clan_gameservers.php?project=".$_SESSION['project']."&podle=clans_gameservers_name&ser=desc&amp;hits=".$hits."\"><img src=\"images/des.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "		<td align=\"center\""; if ($podle == "clans_gameservers_ip"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_clan_gameservers.php?project=".$_SESSION['project']."&podle=clans_gameservers_ip&ser=asc&amp;hits=".$hits."\"><img src=\"images/asc.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_clan_gameservers.php?project=".$_SESSION['project']."&podle=clans_gameservers_ip&ser=desc&amp;hits=".$hits."\"><img src=\"images/des.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo "	</tr>";
	$amount = mysql_query("SELECT COUNT(clans_gameservers_id) FROM $db_gamesrv") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_fetch_array($amount);
	
	//Timto nastavime pocet prispevku na strance
	$m=0;// nastaveni iterace
	if (empty($page)) {$page=1;} // i kdyz je promenna $page prazdna nastavi se pocet starnek na 1
	//$hits=20; //Zde se nastavuje pocet prispevku
	$stw2 = ($num[0]/$hits);
	$stw2 = (integer) $stw2;
	if ($num[0]%$hits > 0) {$stw2++;}
	$np = $page+1;
	$pp = $page-1;
	if ($page == 1) { $pp=1; }
	if ($np > $stw2) { $np = $stw2;}
	
	$sp=($page-1)*$hits;
	$ep=($page-1)*$hits+$hits;
	
	$limit = "LIMIT ".$sp.", ".$hits."";
	$res = mysql_query("SELECT gs.*, c.*, cg.* FROM $db_gamesrv AS gs, $db_country AS c, $db_clan_games AS cg WHERE c.country_id = gs.clans_gameservers_country_id AND cg.clan_games_id = gs.clans_gameservers_game_id ORDER BY $podle $ser");
	$i=1;
	while ($ar = mysql_fetch_array($res)){
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"65\" valign=\"top\">"; if (CheckPriv(groups_gamesrv_edit) == 1){ echo "<a href=\"modul_clan_gameservers.php?action=edit_gamesrv&amp;id=".$ar['clans_gameservers_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>"; }
				if (CheckPriv(groups_gamesrv_del) == 1){ echo " <a href=\"modul_clan_gameservers.php?action=del_gamesrv&amp;id=".$ar['clans_gameservers_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>"; } echo "</td>\n";
		echo "	<td width=\"45\" align=\"left\" valign=\"top\">".$ar['clans_gameservers_id']."</td>\n";
		echo "	<td width=\"45\" align=\"left\" valign=\"top\"><img src=\"".$url_flags.$ar['country_shortname'].".gif\" alt=\"\" width=\"18\" height=\"12\" border=\"0\"></td>\n";
		echo "	<td width=\"80\" align=\"left\" valign=\"top\" align=\"right\">".$ar['clan_games_shortname']."</td>\n";
		echo "	<td width=\"80\" align=\"left\" valign=\"top\" align=\"right\">".$ar['clans_gameservers_mode']."</td>\n";
		echo "	<td width=\"350\" align=\"left\" valign=\"top\" >".$ar['clans_gameservers_name']."</td>\n";
		echo "	<td align=\"right\" valign=\"top\">".$ar['clans_gameservers_ip']."</td>\n";
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
		if ($page==$i) {
			echo " <strong>".$i."</strong>";
		} else {
			echo " <a href=\"modul_clan_gameservers.php?page=".$i."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."&podle=".$podle."&ser=".$ser."&ondate=".$ondate."&ondatez=".$ondatez."&ondatek=".$ondatek."\">".$i."</a> ";
			}
		}
		//Zobrazeni sipek s predchozimi a dalsimi strankami novinek
		if ($page > 1){echo "<center><a href=\"modul_clan_gameservers.php?page=".$pp."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."&podle=".$podle."&ser=".$ser."&ondate=".$ondate."&ondatez=".$ondatez."&ondatek=".$ondatek."\">"._CMN_PREVIOUS."</a>";} else { echo "<br>"._CMN_PREVIOUS;} echo " <--|--> "; if ($page == $stw2){echo _CMN_NEXT;} else {echo "<a href=\"modul_clan_gameservers.php?page=".$np."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."&podle=".$podle."&ser=".$ser."&ondate=".$ondate."&ondatez=".$ondatez."&ondatek=".$ondatek."\">"._CMN_NEXT."</a></center>";}
		echo "</td></tr></table>";
	}
}
include ("inc.header.php");
	if ($_GET['action'] == "") { ShowMain(); }
	if ($_GET['action'] == "showmain") { ShowMain(); }
	if ($_GET['action'] == "add_gamesrv") {ShowMain();}
	if ($_GET['action'] == "edit_gamesrv") {ShowMain();}
	if ($_GET['action'] == "del_gamesrv") {ShowMain();}
include ("inc.footer.php");