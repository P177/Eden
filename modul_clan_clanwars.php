<?php
include "modul_comments.php";
/***********************************************************************************************************
*
*		ZOBRAZENI SEZNAMU ZAPASU
*
***********************************************************************************************************/
function ShowMain(){
	
	global $db_clan_clanwars,$db_comments,$db_clan_games,$db_clan_gametype;
	global $eden_cfg;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	$amount = mysql_query("SELECT COUNT(*) FROM $db_clan_clanwars") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_fetch_array($amount);
	
	$m = 0;// nastaveni iterace
	if (empty($_GET['page'])) {$page = 1;} else {$page = $_GET['page'];} // i kdyz je promenna $page prazdna nastavi se pocet starnek na 1
	if ($_GET['hits'] == "" && $_POST['hits'] == ""){$hits = 30;} elseif (!empty($_GET['hits'])){$hits = $_GET['hits'];} else {$hits = $_POST['hits'];}
	//$hits=3; //Zde se nastavuje pocet prispevku
	$stw2 = ($num[0]/$hits);
	$stw2 = (integer) $stw2;
	if ($num[0]%$hits > 0) {$stw2++;}
	$np = $page+1;
	$pp = $page-1;
	if ($page == 1) { $pp=1; }
	if ($np > $stw2) { $np = $stw2;}
	
	$sp = ($page-1)*$hits;
	$ep = ($page-1)*$hits+$hits;
	
	$limit = "LIMIT ".(integer)$sp.", ".(integer)$hits;
	$res = mysql_query("SELECT 
	cw.clan_cw_id, cw.clan_cw_date, cw.clan_cw_num, cw.clan_cw_team1_score, cw.clan_cw_team2_score, cg.clan_games_shortname, gt.clan_gametype_game_type, cw.clan_cw_clanwartype, cw.clan_cw_team2, cw.clan_cw_mode 
	FROM $db_clan_clanwars AS cw 
	JOIN $db_clan_games AS cg ON cg.clan_games_id=cw.clan_cw_game_id 
	JOIN $db_clan_gametype AS gt ON gt.clan_gametype_id=cw.clan_cw_gametype_id 
	ORDER BY clan_cw_id DESC $limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	if ($_GET['action'] == "save"){
		// Výčet povolených tagů
		$allowtags = "<embed>, <marquee>, <blink>, <hr>, <ul>, <li>, <ol>, <p>, <br>, <font>, <b>, <u>, <i>, <small>, <big>, <strong>, <em>, <a>, <img>";
		// Z obsahu proměnné body vyjmout nepovolené tagy
		$jmeno = strip_tags($_POST['jmeno'],$allowtags);
		$email = strip_tags($_POST['email'],$allowtags);
		$topic = strip_tags($_POST['topic'],$allowtags);
		$comments = strip_tags($_POST['comments'],$allowtags);
		// Aby se minimalizovalo znovuulozeni stejneho zaznamu do databaze po refreshi je treba zjistit prispevek s nejvyssim cislem
		$vysledek = mysql_query("SELECT MAX(comment_id) FROM $db_comments WHERE comment_pid=".(integer)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($vysledek);
		$datum = date("YmdHis");
		// Pokud neni text shodny tak se vse ulozi (kontrola nefunguje pokud uz nekdo neco vlozil)
		$vysledek3 = mysql_query("INSERT INTO $db_comments VALUES('','".(integer)$id."','".mysql_real_escape_string($jmeno)."','".mysql_real_escape_string($email)."','".(integer)$datum."','".mysql_real_escape_string($topic)."','".mysql_real_escape_string($comments)."','clanwars','".mysql_real_escape_string($eden_cfg['ip'])."','1','0','0')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		unset($jmeno,$email,$topic,$comments);
		$_GET['action'] = "open";
	}
	if ($_GET['action'] == "del_selected"){
		$num_del = count($del_comm);
		$i = 0;
		while($num_del > $i){
			$nci = $del_comm[$i];
			$vysledek3 = mysql_query("DELETE FROM $db_comments WHERE comment_id=".(integer)$nci) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$i++;
		}
		$_GET['action'] = "open";
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">"._CLAN_CLANWARS."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" valign=\"bottom\"><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\" align=\"left\"><form action=\"modul_clan_clanwars.php?action=clanwar_add&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
	echo "				<select name=\"game_id\">\n";
   						$res2 = mysql_query("SELECT clan_games_id, clan_games_game FROM $db_clan_games WHERE clan_games_active=1 ORDER BY clan_games_game ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while($ar2 = mysql_fetch_array($res2)){
							echo "<option value=\"".$ar2['clan_games_id']."\">".$ar2['clan_games_game']."</option>\n";
						}
	echo "				</select>\n";
	echo "					<input type=\"hidden\" name=\"mod\" value=\"".$mod."\">\n";
	echo "					<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\"><input type=\"submit\" value=\""._CLAN_CLANWAR_ADD."\" class=\"eden_button\">&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_clan_games.php?action=clan_game_add&amp;project=".$_SESSION['project']."\">"._CLAN_GAMES."</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_clan_clanwars.php?action=gametype_add&amp;project=".$_SESSION['project']."&amp;hits=".$hits."\">"._CLAN_GAMETYPE."</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_clan_clanwars.php?action=gameleague_add&amp;project=".$_SESSION['project']."&amp;hits=".$hits."\">"._CLAN_GAMELEAGUE."</a>\n";
	echo "			</form>	</td>\n";
	echo "		<td width=\"150\" align=\"right\">"._CLAN_NUM.$num[0]."</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"8\"><form action=\"modul_clan_clanwars.php?action=showmain&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
	echo "				<strong>"._CMN_HITS."</strong>&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"hits\" size=\"2\" maxlength=\"4\" value=\"".$hits."\">\n";
	echo "				<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "					<input type=\"hidden\" name=\"mod\" value=\"".$mod."\">\n";
	echo "					<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "					<input type=\"submit\" value=\"". _CMN_SUBMIT."\" class=\"eden_button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"50\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"150\" align=\"center\"><span class=\"nadpis-boxy\">"._CLAN_DATUM."</span></td>\n";
	echo "		<td width=\"100\" align=\"center\"><span class=\"nadpis-boxy\">"._CLAN_GAME."</span></td>\n";
	echo "		<td width=\"70\" align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_GAMETYPE."</span></td>\n";
	echo "		<td width=\"50\" align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_CLANWARTYPE."</span></td>\n";
	echo "		<td width=\"208\" align=\"center\"><span class=\"nadpis-boxy\">"._CLAN_CLAN2."</span></td>\n";
	echo "		<td width=\"50\" align=\"center\"><span class=\"nadpis-boxy\">"._CLAN_SCORE."</span></td>\n";
	echo "	</tr>";
	$y=1;
	while($ar = mysql_fetch_array($res)){
		/* Nacteni poctu komentářů */
		$res2 = mysql_query("SELECT * FROM $db_comments WHERE comment_pid=".(integer)$ar['clan_cw_id']." AND comment_modul='clanwars'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num2 = mysql_num_rows($res2);
		$m++;
		
		/* Uprava datumu */
		preg_match ("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $ar['clan_cw_date'], $date);
		$cw_date = $date[3].".".$date[2].".".$date[1]." - ".Zerofill($ar['clan_cw_num'],10);
		
		/* Ziskame score z funkce */
		$score = GetScore($ar['clan_cw_team1_score'],$ar['clan_cw_team2_score']);
		
		if ($y % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"80\" align=\"left\">";
				if (CheckPriv("groups_clanwars_edit") == 1){echo "<a href=\"modul_clan_clanwars.php?action=clanwar_edit&amp;id=".$ar['clan_cw_id']."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."&amp;page=".$page."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
				if (CheckPriv("groups_clanwars_del") == 1){echo " <a href=\"modul_clan_clanwars.php?action=clanwar_del&amp;id=".$ar['clan_cw_id']."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."&amp;page=".$page."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
				if (CheckPriv("groups_clanwars_add") == 1){echo " <a href=\"modul_clan_clanwars.php?action=komentar&amp;id=".$ar['clan_cw_id']."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."&amp;page=".$page."\" title=\""._NUMCOM."\"><span style=\"font-size:12px; font-weight: bold;\">".$num2."</span></a>";}
		echo "		</td>\n";
		echo "		<td width=\"50\" align=\"right\">".$ar['clan_cw_id']."</td>\n";
		echo "		<td width=\"150\" align=\"center\">"; if ($ar['clan_cw_mode'] == 0){echo "<span style=\"color:#0000ff; font-weight:bold;\" title=\""._CLAN_GAME_UPCOMMING."\">".substr(_CLAN_GAME_UPCOMMING, 0, 1)."</span> ";} echo $cw_date."</td>\n";
		echo "		<td width=\"100\" align=\"left\">".$ar['clan_games_shortname']."</td>\n";
		echo "		<td width=\"70\" align=\"left\">".$ar['clan_gametype_game_type']."</td>\n";
		echo "		<td width=\"50\" align=\"left\">".$ar['clan_cw_clanwartype']."</td>\n";
		echo "		<td width=\"208\" align=\"left\"><strong>".$ar['clan_cw_team2']."</strong></td>\n";
		echo "		<td width=\"50\" align=\"center\"><strong>".$score."</strong></td>\n";
		echo "	</tr>\n";
		if ($_GET['action'] == "open" && $ar['clan_cw_id'] == $id){
			echo "<tr>\n";
			echo "	<td colspan=\"8\">\n";
			echo "		<table width=\"830\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#c0c0c0\">\n";
			echo "			<form action=\"modul_clan_clanwars.php?action=del_selected&amp;project=".$_SESSION['project']."&amp;hits=".$hits."\" name=\"formular\" method=\"post\">";
				//Jelikoz se prispevky zobrazuji v opacnem poradi musi byt i pocitani opacne
			$i=1;
			while ($ar2 = mysql_fetch_array($res2)){
				$comment_author = stripslashes($ar2['comment_author']);
				$comment_subject = stripslashes($ar2['comment_subject']);
				$comment_email = stripslashes($ar2['comment_email']);
				$comment_text = stripslashes($ar2['comment_text']);
				$comments_author = wordwrap( $comments_author, 100, "\n", 1);
				$comment_subject = wordwrap( $comment_subject, 100, "\n", 1);
				$comment_email = wordwrap( $comment_email, 100, "\n", 1);
				$comment_text = wordwrap( $comment_text, 100, "\n", 1);
				$comment_date = FormatTimestamp($ar2['comment_date'],"d.m.Y, H:i");
				$cislo = $i;
				echo "<tr "; if ($cislo % 2 == 0){echo "class=\"suda\"";} /* stridani barev prispevku podle sudeho nebo licheho radku */ echo ">\n";
				echo "	<td width=\"80\" align=\"center\">"; if (CheckPriv("groups_guestbook_del_comm") == 1){ echo "<input type=\"checkbox\" name=\"del_comm[]\" value=\"".$ar2['comment_id']."\">"; } echo "</td>\n";
				echo "	<td width=\"70\" align=\"left\"><strong>"._GUEST_PREDMET." </strong></td>\n";
				echo "	<td width=\"620\" align=\"left\"><strong>".$comment_subject."</strong></td>\n";
				echo "	<td width=\"120\" align=\"left\">#".$i."</td>\n";
				echo "</tr>\n";
				echo "<tr "; if ($cislo % 2 == 0){echo "class=\"suda\"";} /* stridani barev prispevku podle sudeho nebo licheho radku */ echo ">\n";
				echo "	<td width=\"80\" align=\"center\">&nbsp;</td>\n";
				echo "	<td width=\"70\" align=\"left\"><strong>"._GUEST_AUTHOR.": </strong></td>\n";
				echo "	<td width=\"620\" align=\"left\"><strong>".$comment_author." (".$ar2['comment_ip'].")</strong></td>\n";
				echo "	<td width=\"120\" align=\"left\">".$comment_date."</td>\n";
				echo "</tr>\n";
				echo "<tr "; if ($cislo % 2 == 0){echo "class=\"suda\"";} /* stridani barev prispevku podle sudeho nebo licheho radku */ echo ">\n";
				echo "	<td width=\"80\" align=\"center\">&nbsp;</td>\n";
				echo "	<td colspan=\"3\">\n";
				echo 		$comment_text."<br><br>\n";
				echo "	</td>\n";
				echo "</tr>";
				$i++;
			}
			echo "	<tr "; if ($cislo % 2 == 0){echo "class=\"suda\"";} /* stridani barev prispevku podle sudeho nebo licheho radku */ echo ">\n";
			echo "		<td width=\"80\" align=\"center\">&nbsp;</td>\n";
			echo "		<td colspan=\"3\">";
						if (CheckPriv("groups_guestbook_del_comm") == 1){ 
							echo "	<input type=\"submit\" value=\""._GUEST_DEL_SELECTED."\" class=\"eden_button_no\">\n";
							echo "	<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
							echo "	<input type=\"hidden\" name=\"mod\" value=\"".$mod."\">\n";
							echo "</form>";
						}
			echo "<br><br></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td width=\"80\" align=\"center\"><br>&nbsp;<form action=\"modul_clan_clanwars.php?action=save&amp;project=".$_SESSION['project']."\" name=\"formular\" method=\"post\"></td>\n";
			echo "		<td><br>"._GUEST_AUTHOR."</td>\n";
			echo "		<td colspan=\"2\"><br><input type=\"text\" name=\"jmeno\" size=\"30\" maxlength=\"80\"></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td width=\"80\" align=\"center\">&nbsp;</td>\n";
			echo "		<td>"._GUEST_EMAIL."</td>\n";
			echo "		<td colspan=\"2\"><input type=\"text\" name=\"email\" size=\"30\" maxlength=\"30\"></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td width=\"80\" align=\"center\">&nbsp;</td>\n";
			echo "		<td>"._GUEST_PREDMET."<br><br></td>\n";
			echo "		<td colspan=\"2\"><input type=\"text\" name=\"topic\" size=\"40\" maxlength=\"40\"><br><br></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td width=\"80\" align=\"center\">&nbsp;</td>\n";
			echo "		<td colspan=\"3\">"._GUEST_COMMENTS."<br>\n";
			echo "			<textarea cols=\"40\" rows=\"7\" name=\"comments\"></textarea><br><br>\n";
			echo "			<input type=\"submit\" value=\"". _CMN_SUBMIT."\" class=\"eden_button\">&nbsp;&nbsp;<input type=\"Reset\" value=\"Reset\" class=\"button\"><br><br>\n";
			echo "			<input type=\"hidden\" name=\"id\" value=\"".$id."\">\n";
			echo "			<input type=\"hidden\" name=\"mod\" value=\"".$mod."\">\n";
			echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
			echo "			</form>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td align=\"center\" colspan=\"4\"><hr width=\"800\" size=\"1\" noshade></td>\n";
			echo "	</tr>\n";
			echo "</table>";
		}
		$y++;
	}
	// Pokud je novinek vice, nezli se vejde na 1 stranku zobrazi se jejich pocitani a prechazeni mezi nima
	if ($stw2 > 1){
		echo "<tr>";
		echo "	<td width=\"857\" align=\"left\" valign=\"top\" colspan=\"8\">";
		//Zobrazeni cisla poctu stranek
		for ($i=1;$i<=$stw2;$i++) {
			if ($page == $i) {
				echo " <strong>".$i."</strong> ";
			} elseif ($i == 1 || $i == ($page-4) || $i == ($page-3)|| $i == ($page-2) || $i == ($page-1) || $i == ($page+1) || $i == ($page+2) || $i == ($page+3) || $i == ($page+4) || $i == $stw2) {
				echo " <a href=\"modul_clan_clanwars.php?page=".$i."&amp;hits=".$hits."&amp;project=".$_SESSION['project']."\">".$i."</a> ";
			}
		}
		//Zobrazeni sipek s predchozimi a dalsimi strankami novinek
		if ($page > 1){echo "<br><a href=\"modul_clan_clanwars.php?page=".$pp."&amp;hits=".$hits."&amp;project=".$_SESSION['project']."\">"._CMN_PREVIOUS."</a>";} else {echo "<br>"._CMN_PREVIOUS;} echo " <--|--> "; if ($page == $stw2){echo _CMN_NEXT;} else {echo "<a href=\"modul_clan_clanwars.php?page=".$np."&amp;hits=".$hits."&amp;project=".$_SESSION['project']."\">"._CMN_NEXT."</a>";}
		echo "</td></tr>";
	}
	echo "</table>";
}

/***********************************************************************************************************
*
*		PRIDAVANI A EDITACE ZAPASU
*
***********************************************************************************************************/
function Clanwar(){
	
	global $db_clan_clanwars,$db_country,$db_clan_gametype,$db_clan_games,$db_clan_maps,$db_clan_setup,$db_clan_gameleague;
	global $eden_cfg;
	global $ftp_path_flags,$ftp_path_screenshots;
	global $url_screenshots;
	
	$res_clan_setup = mysql_query("SELECT * FROM $db_clan_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_clan_setup = mysql_fetch_array($res_clan_setup);

	if ($_GET['action'] == "clanwar_edit"){
		$res = mysql_query("SELECT * FROM $db_clan_clanwars WHERE clan_cw_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$clan1_name = $ar['clan_cw_team1_name'];
		$clan1_tag = $ar['clan_cw_team1'];
		$clan1_web = $ar['clan_cw_team1_www'];
		$clan1_irc = $ar['clan_cw_team1_irc'];
		$clan1_country = $ar['clan_cw_team1_country_id'];
	} else {
		$clan1_name = $ar_clan_setup['clan_setup_name'];
		$clan1_tag = $ar_clan_setup['clan_setup_tag'];
		$clan1_web = $ar_clan_setup['clan_setup_web'];
		$clan1_irc = $ar_clan_setup['clan_setup_irc'];
		$clan1_country = $ar_clan_setup['clan_setup_country_id'];
	}
	
	if ($_GET['action'] == "clanwar_edit"){$map_game = $ar['clan_cw_game_id'];} else {$map_game = $_POST['game_id'];}
	$res_game = mysql_query("SELECT clan_games_id, clan_games_game_main_id FROM $db_clan_games WHERE clan_games_id=".(integer)$map_game) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_game = mysql_fetch_array($res_game);
	$game_id = $ar_game['clan_games_id'];
	$game_main_id = $ar_game['clan_games_game_main_id'];
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._CLAN_CLANWARS." - "; if ($_GET['action'] == "clanwar_edit"){echo _CLAN_CLANWAR_EDIT;} else {echo _CLAN_CLANWAR_ADD;} echo "</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">\n";
	echo "			<a href=\"modul_clan_clanwars.php?action=showmain&amp;project=".$_SESSION['project']."&amp;hits=".$_GET['hits']."\">"._CLAN_MAIN."</a></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"4\"><form enctype=\"multipart/form-data\" action=\"sys_save.php?action="; if ($_GET['action'] == "clanwar_edit"){echo "clanwar_edit";} else {echo "clanwar_add";} echo "&amp;id=".$_GET['id']."&amp;hits=".$_GET['hits']."\" method=\"post\" name=\"forma\">\n";
	echo "			<strong>"._CLAN_DATUM."</strong>\n";
					if ($_GET['action'] == "clanwar_add"){
						$cwdate = formatTimeS(time());
						echo "<script language=\"javascript\">\n";
						echo "var StartDate = new ctlSpiffyCalendarBox(\"StartDate\", \"forma\", \"clan_cw_date\", \"btnDate1\",\"".$cwdate[1].".".$cwdate[2].".".$cwdate[3]."\",scBTNMODE_CUSTOMBLUE);\n";
						echo "</script>\n";
						echo "<script language=\"javascript\">StartDate.writeControl(); StartDate.dateFormat=\"dd.MM.yyyy\";</script> <select name=\"clan_cw_num\">\n";
						$res_num_cw = mysql_query("SELECT MAX(clan_cw_date) FROM $db_clan_clanwars WHERE clan_cw_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$num_cw = mysql_fetch_array($res_num_cw);
						for ($i=1;$i<=99;$i++){
							echo "<option value=\"".$i."\">".Zerofill($i,10)."</option>\n";
						}
						echo "</select>\n";
					} else {
						$cwdate = $ar['clan_cw_date'];
						echo "<script language=\"javascript\">\n";
						echo "var StartDate = new ctlSpiffyCalendarBox(\"StartDate\", \"forma\", \"clan_cw_date\", \"btnDate1\",\"".$cwdate[8].$cwdate[9].".".$cwdate[5].$cwdate[6].".".$cwdate[0].$cwdate[1].$cwdate[2].$cwdate[3]."\",scBTNMODE_CUSTOMBLUE);\n";
						echo "</script>\n";
						echo "<script language=\"javascript\">StartDate.writeControl(); StartDate.dateFormat=\"dd.MM.yyyy\";</script> <select name=\"clan_cw_num\">\n";
						for ($i=1;$i<=99;$i++){
							echo "<option value=\"".$i."\" "; if ($ar['clan_cw_num'] == $i){ echo " selected ";} echo ">".Zerofill($i,10)."</option>";
						}
						echo "</select>\n";
					}
	echo _CLAN_GAME_UPCOMMING.": <input type=\"radio\" name=\"clan_cw_mode\" value=\"0\" "; if ($ar['clan_cw_mode'] == 0){echo "checked";} echo "> "._CLAN_GAME_PLAYED.": <input type=\"radio\" name=\"clan_cw_mode\" value=\"1\" "; if ($_GET['action'] == "clanwar_add" || $ar['clan_cw_mode'] == 1){echo "checked";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<strong>"._CLAN_GAME."</strong>\n";
	echo "		</td>\n";
	echo "		<td>\n";
	echo "			<strong>"._CLAN_GAMETYPE."</strong>\n";
	echo "		</td>\n";
	echo "		<td>\n";
	echo "			<strong>"._CLAN_CLANWARTYPE."</strong>\n";
	echo "		</td>\n";
	echo "		<td>\n";
	echo "			<strong>"._CLAN_LIGA."</strong>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><select name=\"game_id\">\n";
					$res2 = mysql_query("SELECT clan_games_id, clan_games_game FROM $db_clan_games WHERE clan_games_active=1 ORDER BY clan_games_game ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
   					while($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2['clan_games_id']."\" "; if ($ar2['clan_games_id'] == $ar['clan_cw_game_id'] || $ar2['clan_games_id'] == $_POST['game_id']){echo " selected ";} echo ">".$ar2['clan_games_game']."</option>\n";
					}
	echo "			</select>\n";
	echo "		</td>\n";
	echo "		<td>\n";
	echo "			<select name=\"gametype_id\">\n";
					 	$res3 = mysql_query("SELECT clan_gametype_id, clan_gametype_game_type, clan_gametype_main FROM $db_clan_gametype WHERE clan_gametype_game_id = ".(integer)$game_main_id." ORDER BY clan_gametype_game_type ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while($ar3 = mysql_fetch_array($res3)){
							echo "<option name=\"gametype\" value=\"".$ar3['clan_gametype_id']."\" "; if ($_GET['action'] == "clanwar_add" && $ar3['clan_gametype_main'] == 1){ echo " selected ";} elseif ($ar['clan_cw_gametype_id'] == $ar3['clan_gametype_id']){echo " selected ";} echo ">".$ar3['clan_gametype_game_type']."</option>\n";
		 				}
	echo "			</select>\n";
	echo "		</td>\n";
	if ($mod != "liga"){
		echo "<td>\n";
		echo "	<select name=\"clanwartype\">\n";
		for ($i=1;$i<9;$i++){
			echo "<option name=\"clanwartype\" value=\"".$i."v".$i."\" "; if ($ar['clan_cw_clanwartype'] == $i."v".$i){echo " selected ";} echo ">".$i."v".$i."</option>\n";
		}
		echo "	</select>\n";
		echo "</td>\n";
	}
	echo "		<td>\n";
	echo "			<select name=\"liga\">\n";
					 	$res4 = mysql_query("SELECT clan_gameleague_league FROM $db_clan_gameleague ORDER BY clan_gameleague_league ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while($ar4 = mysql_fetch_array($res4)){
							echo "<option name=\"liga\" value=\"".$ar4['clan_gameleague_league']."\" "; if ($ar['clan_cw_league'] == $ar4['clan_gameleague_league']){echo " selected ";} echo ">".$ar4['clan_gameleague_league']."</option>\n";
						}
	echo "			</select>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><br><strong>"._CLAN_SCORE_FINAL."</strong></td>\n";
	echo "		<td width=\"250\" align=\"right\"><br><input type=\"text\" name=\"team1_score\" value=\"".$ar['clan_cw_team1_score']."\" size=\"5\" style=\"background-color:#aaede3;\"></td>\n";
	echo "		<td width=\"250\" align=\"left\"><br><input type=\"text\" name=\"team2_score\" value=\"".$ar['clan_cw_team2_score']."\" size=\"5\" style=\"background-color:#aaede3;\"></td>\n";
	echo "		<td align=\"left\"><br><strong>"._CLAN_SCORE_FINAL."</strong></td>\n";
	echo "	</tr>\n";
	if ($mod != "liga"){
		echo "	<tr>\n";
		echo "		<td width=\"150\" align=\"right\"><br>\n";
		echo "			<strong>"._CLAN_CLAN_TAG."</strong>\n";
		echo "		</td>\n";
		echo "		<td width=\"250\" align=\"right\"><br>\n";
		echo "			<input type=\"text\" name=\"team1\" value=\"".$clan1_tag."\" size=\"10\" maxlength=\"80\">\n";
		echo "		</td>\n";
		echo "		<td width=\"250\" align=\"left\"><br>\n";
		echo "			<input type=\"text\" name=\"team2\" value=\"".$ar['clan_cw_team2']."\" size=\"10\" maxlength=\"80\">\n";
		echo "		</td>\n";
		echo "		<td align=\"left\"><br>\n";
		echo "			<strong>"._CLAN_CLAN_TAG."</strong>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
	}
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\">\n";
	echo "			<strong>"._CLAN_CLAN1."</strong>\n";
	echo "		</td>\n";
	echo "		<td width=\"250\" align=\"right\">\n";
	if ($mod != "liga"){
		echo "		<input type=\"text\" name=\"team1_name\" value=\"".$clan1_name."\" size=\"30\" maxlength=\"255\">\n";
		echo "	</td>\n";
		echo "	<td width=\"250\" align=\"left\">\n";
		echo "		<input type=\"text\" name=\"team2_name\" value=\"".$ar['clan_cw_team2_name']."\" size=\"30\" maxlength=\"255\">\n";
	} else {
		echo "	<select name=\"team1_id\">\n";
			$res5 = mysql_query("SELECT * FROM $db_liga_team ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while($ar5 = mysql_fetch_array($res5)){
				echo "<option name=\"team1_id\" value=\"".$ar5['id']."\" "; if ($ar5['id'] == $ar['clan_cw_team1_id']){echo " selected ";} echo ">".$ar5['nazev']."</option>\n";
			}
		echo "</select>\n";
		echo "	</td>\n";
		echo "	<td width=\"250\" align=\"left\"><br>\n";
		echo "		<select name=\"team2_id\">\n";
			$res6 = mysql_query("SELECT * FROM $db_liga_team ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while($ar6 = mysql_fetch_array($res6)){
				echo "<option name=\"team2_id\" value=\"".$ar6['id']."\" "; if ($ar6['id'] == $ar['clan_cw_team2_id']){echo " selected ";} echo ">".$ar6['nazev']."</option>\n";
			}
		echo "</select>\n";
	}
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<strong>"._CLAN_CLAN2."</strong>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	if ($mod != "liga"){
		echo "	<tr>\n";
		echo "		<td width=\"150\" align=\"right\">\n";
		echo "			<strong>"._CLAN_COUNTRY."</strong>\n";
		echo "		</td>\n";
		echo "		<td width=\"250\" align=\"right\">";
		echo "			<select name=\"team1_country_id\" class=\"input\">\n";
						$res7 = mysql_query("SELECT country_id, country_name FROM $db_country ORDER BY country_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($ar7 = mysql_fetch_array($res7)){
							echo "<option value=\"".$ar7['country_id']."\" "; if ($clan1_country == $ar7['country_id']) {echo " selected";} echo ">".$ar7['country_name']."</option>\n";
						}
		echo "		</select>\n";
		echo "		</td>\n";
		echo "		<td width=\"250\" align=\"left\">\n";
		echo "			<select name=\"team2_country_id\">\n";
						$res7 = mysql_query("SELECT country_id, country_name FROM $db_country ORDER BY country_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($ar7 = mysql_fetch_array($res7)){
							echo "<option value=\"".$ar7['country_id']."\" "; if ($ar['clan_cw_team2_country_id'] == $ar7['country_id']) {echo " selected";} echo ">".$ar7['country_name']."</option>\n";
						}
		echo "		</select>\n";
		echo "		</td>\n";
		echo "		<td align=\"left\"><strong>"._CLAN_COUNTRY."</strong></td>\n";
		echo "	</tr>\n";
	}
	/*****************************************************************
	*		MAP 1
	*****************************************************************/
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._CLAN_MAP." 1</strong></td>\n";
	echo "		<td width=\"250\" align=\"right\">";
	echo "			<select name=\"map_1\">";
					$nomap_1 = 1;
					$res_map1 = mysql_query("SELECT clan_map_name FROM $db_clan_maps WHERE clan_map_game_id = ".(integer)$game_main_id." ORDER BY clan_map_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<option value=\"\""; if ($_GET['action'] == "clanwar_add"){echo "selected=\"selected\"";} echo ">"._CLAN_CLANWAR_CHOOSE_MAP."</option>\n";
					while($ar_map1 = mysql_fetch_array($res_map1)){
						echo "<option value=\"".$ar_map1['clan_map_name']."\""; if ($ar_map1['clan_map_name'] == $ar['clan_cw_map_1']){echo "selected=\"selected\""; $nomap_1 = 0;} echo ">".$ar_map1['clan_map_name']."</option>\n";
			 		}
					
	echo "			</select>"; 
					if ($nomap_1 == 1){echo $ar['clan_cw_map_1'];}
	echo "		</td>\n";
	echo "		<td width=\"250\" align=\"left\">";
	echo "			<input type=\"text\" name=\"team1_score_1\" value=\"".$ar['clan_cw_team1_score_1']."\" size=\"5\" style=\"background-color:#aaede3;margin-left:30px;\">&nbsp;&nbsp;<input type=\"text\" name=\"team2_score_1\" value=\"".$ar['clan_cw_team2_score_1']."\" size=\"5\" style=\"background-color:#aaede3;margin-right:30px;\">";
	echo "		</td>\n";
	echo "		<td align=\"left\"><strong>"._CLAN_SCORE."</strong></td>\n";
	echo "	</tr>\n";
	/*****************************************************************
	*		MAP 2
	*****************************************************************/
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._CLAN_MAP." 2</strong></td>\n";
	echo "		<td width=\"250\" align=\"right\">";
	echo "			<select name=\"map_2\">";
					$nomap_2 = 1;
					$res_map1 = mysql_query("SELECT clan_map_name FROM $db_clan_maps WHERE clan_map_game_id = ".(integer)$game_main_id." ORDER BY clan_map_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<option value=\"\""; if ($_GET['action'] == "clanwar_add"){echo "selected=\"selected\"";} echo ">"._CLAN_CLANWAR_CHOOSE_MAP."</option>\n";
					while($ar_map1 = mysql_fetch_array($res_map1)){
						echo "<option value=\"".$ar_map1['clan_map_name']."\""; if ($ar_map1['clan_map_name'] == $ar['clan_cw_map_2']){echo "selected=\"selected\""; $nomap_2 = 0;} echo ">".$ar_map1['clan_map_name']."</option>\n";
			 		}
	echo "			</select>\n";
					if ($nomap_2 == 1){echo $ar['clan_cw_map_2'];}
	echo "		</td>\n";
	echo "		<td width=\"250\" align=\"left\">";
	echo "			<input type=\"text\" name=\"team1_score_2\" value=\"".$ar['clan_cw_team1_score_2']."\" size=\"5\" style=\"background-color:#aaede3;margin-left:30px;\">&nbsp;&nbsp;<input type=\"text\" name=\"team2_score_2\" value=\"".$ar['clan_cw_team2_score_2']."\" size=\"5\" style=\"background-color:#aaede3;margin-right:30px;\">";
	echo "		</td>\n";
	echo "		<td align=\"left\"><strong>"._CLAN_SCORE."</strong></td>\n";
	echo "	</tr>\n";
	/*****************************************************************
	*		MAP 3
	*****************************************************************/
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._CLAN_MAP." 3</strong></td>\n";
	echo "		<td width=\"250\" align=\"right\">";
	echo "			<select name=\"map_3\">";
					$nomap_3 = 1;
					$res_map1 = mysql_query("SELECT clan_map_name FROM $db_clan_maps WHERE clan_map_game_id = ".(integer)$game_main_id." ORDER BY clan_map_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<option value=\"\""; if ($_GET['action'] == "clanwar_add"){echo "selected=\"selected\"";} echo ">"._CLAN_CLANWAR_CHOOSE_MAP."</option>\n";
					while($ar_map1 = mysql_fetch_array($res_map1)){
						echo "<option value=\"".$ar_map1['clan_map_name']."\""; if ($ar_map1['clan_map_name'] == $ar['clan_cw_map_3']){echo "selected=\"selected\""; $nomap_3 = 0;} echo ">".$ar_map1['clan_map_name']."</option>\n";
			 		}
	echo "			</select>\n";
					if ($nomap_3 == 1){echo $ar['clan_cw_map_3'];}
	echo "		</td>\n";
	echo "		<td width=\"250\" align=\"left\">";
	echo "			<input type=\"text\" name=\"team1_score_3\" value=\"".$ar['clan_cw_team1_score_3']."\" size=\"5\" style=\"background-color:#aaede3;margin-left:30px;\">&nbsp;&nbsp;<input type=\"text\" name=\"team2_score_3\" value=\"".$ar['clan_cw_team2_score_3']."\" size=\"5\" style=\"background-color:#aaede3;margin-right:30px;\">";
	echo "		</td>\n";
	echo "		<td align=\"left\"><strong>"._CLAN_SCORE."</strong></td>\n";
	echo "	</tr>\n";
	/*****************************************************************
	*		MAP 4
	*****************************************************************/
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._CLAN_MAP." 4</strong></td>\n";
	echo "		<td width=\"250\" align=\"right\">";
	echo "			<select name=\"map_4\">";
					$nomap_4 = 1;
					$res_map1 = mysql_query("SELECT clan_map_name FROM $db_clan_maps WHERE clan_map_game_id = ".(integer)$game_main_id." ORDER BY clan_map_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<option value=\"\""; if ($_GET['action'] == "clanwar_add"){echo "selected=\"selected\"";} echo ">"._CLAN_CLANWAR_CHOOSE_MAP."</option>\n";
					while($ar_map1 = mysql_fetch_array($res_map1)){
						echo "<option value=\"".$ar_map1['clan_map_name']."\""; if ($ar_map1['clan_map_name'] == $ar['clan_cw_map_4']){echo "selected=\"selected\""; $nomap_4 = 0;} echo ">".$ar_map1['clan_map_name']."</option>\n";
			 		}
	echo "			</select>\n";
					if ($nomap_4 == 1){echo $ar['clan_cw_map_4'];}
	echo "		</td>\n";
	echo "		<td width=\"250\" align=\"left\">";
	echo "			<input type=\"text\" name=\"team1_score_4\" value=\"".$ar['clan_cw_team1_score_4']."\" size=\"5\" style=\"background-color:#aaede3;margin-left:30px;\">&nbsp;&nbsp;<input type=\"text\" name=\"team2_score_4\" value=\"".$ar['clan_cw_team2_score_4']."\" size=\"5\" style=\"background-color:#aaede3;margin-right:30px;\">";
	echo "		</td>\n";
	echo "		<td align=\"left\"><strong>"._CLAN_SCORE."</strong></td>\n";
	echo "	</tr>\n";
	/*****************************************************************
	*		MAP 5
	*****************************************************************/
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._CLAN_MAP." 5</strong></td>\n";
	echo "		<td width=\"250\" align=\"right\">";
	echo "			<select name=\"map_5\">";
					$nomap_5 = 1;
					$res_map1 = mysql_query("SELECT clan_map_name FROM $db_clan_maps WHERE clan_map_game_id = ".(integer)$game_main_id." ORDER BY clan_map_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<option value=\"\""; if ($_GET['action'] == "clanwar_add"){echo "selected=\"selected\"";} echo ">"._CLAN_CLANWAR_CHOOSE_MAP."</option>\n";
					while($ar_map1 = mysql_fetch_array($res_map1)){
						echo "<option value=\"".$ar_map1['clan_map_name']."\""; if ($ar_map1['clan_map_name'] == $ar['clan_cw_map_5']){echo "selected=\"selected\""; $nomap_5 = 0;} echo ">".$ar_map1['clan_map_name']."</option>\n";
			 		}
	echo "			</select>\n";
					if ($nomap_5 == 1){echo $ar['clan_cw_map_5'];}
	echo "		</td>\n";
	echo "		<td width=\"250\" align=\"left\">";
	echo "			<input type=\"text\" name=\"team1_score_5\" value=\"".$ar['clan_cw_team1_score_5']."\" size=\"5\" style=\"background-color:#aaede3;margin-left:30px;\">&nbsp;&nbsp;<input type=\"text\" name=\"team2_score_5\" value=\"".$ar['clan_cw_team2_score_5']."\" size=\"5\" style=\"background-color:#aaede3;margin-right:30px;\">";
	echo "		</td>\n";
	echo "		<td align=\"left\"><strong>"._CLAN_SCORE."</strong></td>\n";
	echo "	</tr>\n";
	/*****************************************************************
	*		MAP 6
	*****************************************************************/
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._CLAN_MAP." 6</strong></td>\n";
	echo "		<td width=\"250\" align=\"right\">";
	echo "			<select name=\"map_6\">";
					$nomap_6 = 1;
					$res_map1 = mysql_query("SELECT clan_map_name FROM $db_clan_maps WHERE clan_map_game_id = ".(integer)$game_main_id." ORDER BY clan_map_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<option value=\"\""; if ($_GET['action'] == "clanwar_add"){echo "selected=\"selected\"";} echo ">"._CLAN_CLANWAR_CHOOSE_MAP."</option>\n";
					while($ar_map1 = mysql_fetch_array($res_map1)){
						echo "<option value=\"".$ar_map1['clan_map_name']."\""; if ($ar_map1['clan_map_name'] == $ar['clan_cw_map_6']){echo "selected=\"selected\""; $nomap_6 = 0;} echo ">".$ar_map1['clan_map_name']."</option>\n";
			 		}
	echo "			</select>\n";
					if ($nomap_6 == 1){echo $ar['clan_cw_map_6'];}
	echo "		</td>\n";
	echo "		<td width=\"250\" align=\"left\">";
	echo "			<input type=\"text\" name=\"team1_score_6\" value=\"".$ar['clan_cw_team1_score_6']."\" size=\"5\" style=\"background-color:#aaede3;margin-left:30px;\">&nbsp;&nbsp;<input type=\"text\" name=\"team2_score_6\" value=\"".$ar['clan_cw_team2_score_6']."\" size=\"5\" style=\"background-color:#aaede3;margin-right:30px;\">";
	echo "		</td>\n";
	echo "		<td align=\"left\"><strong>"._CLAN_SCORE."</strong></td>\n";
	echo "	</tr>\n";
	/*****************************************************************
	*		MAP 7
	*****************************************************************/
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._CLAN_MAP." 7</strong></td>\n";
	echo "		<td width=\"250\" align=\"right\">";
	echo "			<select name=\"map_7\">";
					$nomap_7 = 1;
					$res_map1 = mysql_query("SELECT clan_map_name FROM $db_clan_maps WHERE clan_map_game_id = ".(integer)$game_main_id." ORDER BY clan_map_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<option value=\"\""; if ($_GET['action'] == "clanwar_add"){echo "selected=\"selected\"";} echo ">"._CLAN_CLANWAR_CHOOSE_MAP."</option>\n";
					while($ar_map1 = mysql_fetch_array($res_map1)){
						echo "<option value=\"".$ar_map1['clan_map_name']."\""; if ($ar_map1['clan_map_name'] == $ar['clan_cw_map_7']){echo "selected=\"selected\""; $nomap_7 = 0;} echo ">".$ar_map1['clan_map_name']."</option>\n";
			 		}
	echo "			</select>\n";
					if ($nomap_7 == 1){echo $ar['clan_cw_map_7'];}
	echo "		</td>\n";
	echo "		<td width=\"250\" align=\"left\">";
	echo "			<input type=\"text\" name=\"team1_score_7\" value=\"".$ar['clan_cw_team1_score_7']."\" size=\"5\" style=\"background-color:#aaede3;margin-left:30px;\">&nbsp;&nbsp;<input type=\"text\" name=\"team2_score_7\" value=\"".$ar['clan_cw_team2_score_7']."\" size=\"5\" style=\"background-color:#aaede3;margin-right:30px;\">";
	echo "		</td>\n";
	echo "		<td align=\"left\"><strong>"._CLAN_SCORE."</strong></td>\n";
	echo "	</tr>\n";
	if ($mod != "liga"){
		echo "	<tr>\n";
		echo "		<td width=\"150\" align=\"right\"><strong>"._CLAN_SESTAVA."</strong></td>\n";
		echo "		<td width=\"250\" align=\"right\"><textarea name=\"team1_sestava\" rows=\"5\" cols=\"25\">".$ar['clan_cw_team1_roster']."</textarea></td>\n";
		echo "		<td width=\"250\" align=\"left\"><textarea name=\"team2_sestava\" rows=\"5\" cols=\"25\">".$ar['clan_cw_team2_roster']."</textarea></td>\n";
		echo "		<td align=\"left\"><strong>"._CLAN_SESTAVA."</strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td width=\"150\" align=\"right\"><strong>"._CLAN_WWW."</strong></td>\n";
		echo "		<td width=\"250\" align=\"right\"><input type=\"text\" name=\"team1_www\" value=\"".$clan1_web."\" size=\"30\" maxlength=\"250\"></td>\n";
		echo "		<td width=\"250\" align=\"left\"><input type=\"text\" name=\"team2_www\" value=\"".$ar['clan_cw_team2_www']."\" size=\"30\" maxlength=\"250\"></td>\n";
		echo "		<td align=\"left\"><strong>"._CLAN_WWW."</strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td width=\"150\" align=\"right\"><strong>"._CLAN_IRC."</strong></td>\n";
		echo "		<td width=\"250\" align=\"right\"><input type=\"text\" name=\"team1_irc\" value=\"".$clan1_irc."\" size=\"30\" maxlength=\"250\"></td>\n";
		echo "		<td width=\"250\" align=\"left\"><input type=\"text\" name=\"team2_irc\" value=\"".$ar['clan_cw_team2_irc']."\" size=\"30\" maxlength=\"250\"></td>\n";
		echo "		<td align=\"left\"><strong>"._CLAN_IRC."</strong></td>\n";
		echo "	</tr>\n";
	}
	echo "</table>\n";
	echo "<table width=\"400\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\">\n";
   	if ($_GET['action'] == "clanwar_edit"){
		echo "	<tr>\n";
		echo "		<td align=\"left\" colspan=\"2\">\n";
		echo "			<strong>"._CLAN_SCR."</strong><br>\n";
		echo "			01. <input type=\"file\" name=\"userfile1\" size=\"40\" accept=\"image/jpeg,image/gif\"><input name=\"del_uf1\" type=\"checkbox\" value=\"1\"> "._CMN_DEL." ".$ar['clan_cw_scr_1']."<br>\n";
		echo "			02. <input type=\"file\" name=\"userfile2\" size=\"40\" accept=\"image/jpeg,image/gif\"><input name=\"del_uf2\" type=\"checkbox\" value=\"1\"> "._CMN_DEL." ".$ar['clan_cw_scr_2']."<br>\n";
		echo "			03. <input type=\"file\" name=\"userfile3\" size=\"40\" accept=\"image/jpeg,image/gif\"><input name=\"del_uf3\" type=\"checkbox\" value=\"1\"> "._CMN_DEL." ".$ar['clan_cw_scr_3']."<br>\n";
		echo "			04. <input type=\"file\" name=\"userfile4\" size=\"40\" accept=\"image/jpeg,image/gif\"><input name=\"del_uf4\" type=\"checkbox\" value=\"1\"> "._CMN_DEL." ".$ar['clan_cw_scr_4']."<br><br>\n";
		echo "			05. <input type=\"file\" name=\"userfile5\" size=\"40\" accept=\"image/jpeg,image/gif\"><input name=\"del_uf5\" type=\"checkbox\" value=\"1\"> "._CMN_DEL." ".$ar['clan_cw_scr_5']."<br>\n";
		echo "			06. <input type=\"file\" name=\"userfile6\" size=\"40\" accept=\"image/jpeg,image/gif\"><input name=\"del_uf6\" type=\"checkbox\" value=\"1\"> "._CMN_DEL." ".$ar['clan_cw_scr_6']."<br>\n";
		echo "			07. <input type=\"file\" name=\"userfile7\" size=\"40\" accept=\"image/jpeg,image/gif\"><input name=\"del_uf7\" type=\"checkbox\" value=\"1\"> "._CMN_DEL." ".$ar['clan_cw_scr_7']."<br>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" colspan=\"2\">\n";
		echo "			<strong>"._CLAN_DMO."</strong><br> Aka. ftp://www.blackfoot.cz/pub/eden.zip<br>\n";
		echo "			01. <input name=\"demo01\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_01']."\"";} echo "> <input name=\"demopopis01\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_desc_01']."\"";} echo "><br>\n";
		echo "			02. <input name=\"demo02\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_02']."\"";} echo "> <input name=\"demopopis02\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_desc_02']."\"";} echo "><br>\n";
		echo "			03. <input name=\"demo03\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_03']."\"";} echo "> <input name=\"demopopis03\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_desc_03']."\"";} echo "><br>\n";
		echo "			04. <input name=\"demo04\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_04']."\"";} echo "> <input name=\"demopopis04\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_desc_04']."\"";} echo "><br>\n";
		echo "			05. <input name=\"demo05\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_05']."\"";} echo "> <input name=\"demopopis05\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_desc_05']."\"";} echo "><br>\n";
		echo "			06. <input name=\"demo06\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_06']."\"";} echo "> <input name=\"demopopis06\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_desc_06']."\"";} echo "><br>\n";
		echo "			07. <input name=\"demo07\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_07']."\"";} echo "> <input name=\"demopopis07\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_desc_07']."\"";} echo "><br>\n";
		echo "			08. <input name=\"demo08\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_08']."\"";} echo "> <input name=\"demopopis08\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_desc_08']."\"";} echo "><br>\n";
		echo "			09. <input name=\"demo09\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_09']."\"";} echo "> <input name=\"demopopis09\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_desc_09']."\"";} echo "><br>\n";
		echo "			10. <input name=\"demo10\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_10']."\"";} echo "> <input name=\"demopopis10\" maxlength=\"80\" size=\"40\" "; if ($_GET['action'] == "clanwar_edit"){echo "value=\"".$ar['clan_cw_demo_desc_10']."\"";} echo "><br>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
	}
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<strong>"._CLAN_COMMENTS_CS."</strong>\n";
	echo "		</td>\n";
	echo "		<td>\n";
	echo "			<strong>"._CLAN_COMMENTS_EN."</strong>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><img alt=\""._EDITOR_INS_LINK."\" class=\"editor_v\" onmouseover=\"className='editor_o'\" onmouseout=\"className='editor_v'\" id=\"HyperLink\" onclick=\"window.open('add_link.php?project=".$_SESSION['project']."&input=comments_cs&amp;action=clanwars_comment&amp;lang=cz','','menubar=no,resizable=no,toolbar=no,status=no,width=420,height=200')\" height=\"20\" src=\"images/editor_hyperlink.gif\" width=\"20\"><br />\n";
	echo "			<textarea name=\"comments_cs\" id=\"comments_cs\" rows=\"5\" cols=\"40\">"; $comments_cs = str_ireplace( "<br>","\n",$ar['clan_cw_comments_cs']); echo $comments_cs."</textarea>\n";
	echo "		</td>\n";
	echo "		<td><img alt=\""._EDITOR_INS_LINK."\" class=\"editor_v\" onmouseover=\"className='editor_o'\" onmouseout=\"className='editor_v'\" id=\"HyperLink\" onclick=\"window.open('add_link.php?project=".$_SESSION['project']."&input=comments_en&amp;action=clanwars_comment&amp;lang=en','','menubar=no,resizable=no,toolbar=no,status=no,width=420,height=200')\" height=\"20\" src=\"images/editor_hyperlink.gif\" width=\"20\"><br />\n";
	echo "			<textarea name=\"comments_en\" id=\"comments_en\" rows=\"5\" cols=\"40\">"; $comments_en = str_ireplace( "<br>","\n",$ar['clan_cw_comments_en']); echo $comments_en."</textarea>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"2\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"mod\" value=\"".$mod."\">\n";
	echo "			<input type=\"submit\" value=\"". _CMN_SUBMIT."\" class=\"eden_button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*
*		ODSTARNENI ZAPASU
*
***********************************************************************************************************/
function Del(){
	
	global $db_clan_clanwars,$db_clan_games,$db_clan_gametype;
	
	$res = mysql_query("SELECT 
	cw.clan_cw_id, cw.clan_cw_date, cw.clan_cw_num, cw.clan_cw_team1, cw.clan_cw_team2, cw.clan_cw_team1_score, cw.clan_cw_team2_score, cg.clan_games_shortname, gt.clan_gametype_game_type, cw.clan_cw_clanwartype, cw.clan_cw_team2, cw.clan_cw_mode 
	FROM $db_clan_clanwars AS cw 
	JOIN $db_clan_games AS cg ON cg.clan_games_id=cw.clan_cw_game_id 
	JOIN $db_clan_gametype AS gt ON gt.clan_gametype_id=cw.clan_cw_gametype_id 
	WHERE clan_cw_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_clanwars_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	if ($_POST['confirm'] == "true") {
		$res = mysql_query("DELETE FROM $db_clan_clanwars WHERE clan_cw_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		ShowMain();
	}
	if ($_POST['confirm'] == "false"){ShowMain();}
	if ($_POST['confirm'] <> "true" && $_POST['confirm'] <> "false"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" class=\"nadpis\">"._CLAN_CLANWARS." - "._CLAN_CLANWAR_DEL."</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"Přidat kategorii\">\n";
		echo "			<a href=\"modul_clan_clanwars.php?action=showmain&amp;project=".$_SESSION['project']."&amp;hits=".$_GET['hits']."\">"._CLAN_MAIN."</a></td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr class=\"popisky\">\n";
		echo "		<td width=\"120\" align=\"center\"><span class=\"nadpis-boxy\">"._CLAN_DATUM."</span></td>\n";
		echo "		<td width=\"150\" align=\"center\"><span class=\"nadpis-boxy\">"._CLAN_GAME."</span></td>\n";
		echo "		<td width=\"70\" align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_GAMETYPE."</span></td>\n";
		echo "		<td width=\"50\" align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_CLANWARTYPE."</span></td>\n";
		echo "		<td width=\"208\" align=\"center\"><span class=\"nadpis-boxy\">"._CLAN_CLAN1."</span></td>\n";
		echo "		<td width=\"208\" align=\"center\"><span class=\"nadpis-boxy\">"._CLAN_CLAN2."</span></td>\n";
		echo "		<td width=\"50\" align=\"center\"><span class=\"nadpis-boxy\">"._CLAN_SCORE."</span></td>\n";
		echo "	</tr>";
 		/* Uprava datumu */
		preg_match ("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $ar['clan_cw_date'], $date);
		$cw_date = $date[3].".".$date[2].".".$date[1];
		
		/* Ziskame score z funkce */
		$score = GetScore($ar['clan_cw_team1_score'],$ar['clan_cw_team2_score']);
		
		echo "		<tr>\n";
		echo "			<td width=\"120\" align=\"center\">".$cw_date."</td>\n";
		echo "			<td width=\"150\" align=\"center\">".$ar['clan_games_shortname']."</td>\n";
		echo "			<td width=\"70\" align=\"left\">".$ar['clan_gametype_game_type']."</td>\n";
		echo "			<td width=\"50\" align=\"left\">".$ar['clan_cw_clanwartype']."</td>\n";
		echo "			<td width=\"208\" align=\"center\">".$ar['clan_cw_team1']."</td>\n";
		echo "			<td width=\"208\" align=\"center\">".$ar['clan_cw_team2']."</td>\n";
		echo "			<td width=\"50\" align=\"center\"><strong>".$score."</strong></td>\n";
		echo "		</tr>\n";
		echo "</table>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" colspan=\"2\"><br><br><strong><span style=\"color : #FF0000;\">"._CLAN_DELCHECK."</span></strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td width=\"50\" valign=\"top\">\n";
		echo "			<form action=\"modul_clan_clanwars.php?action=clanwar_del&amp;hits=".$_GET['hits']."&amp;page=".$_GET['page']."\" method=\"post\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "		<td width=\"800\" valign=\"top\">\n";
		echo "			<form action=\"modul_clan_clanwars.php?action=&amp;hits=".$_GET['hits']."&amp;page=".$_GET['page']."\" method=\"post\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"false\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
 	}
}
/***********************************************************************************************************
*
*		PRIDANI HERNI LIGY
*
***********************************************************************************************************/
function GameLeague(){
	
	global $db_clan_gameleague;
	// CHECK PRIVILEGIES
	
	// Provereni opravneni
	if ($_GET['action'] == "gameleague_add"){
		if (CheckPriv("groups_clanwars_add") <> 1) { echo _NOTENOUGHPRIV;Products();exit;}
	}elseif ($_GET['action'] == "gameleague_edit"){
		if (CheckPriv("groups_clanwars_edit") <> 1) { echo _NOTENOUGHPRIV;Products();exit;}
	}elseif ($_GET['action'] == "gameleague_del"){
		if (CheckPriv("groups_clanwars_del") <> 1) { echo _NOTENOUGHPRIV;Products();exit;}
	} else {
		 echo _NOTENOUGHPRIV;Products();exit;
	}
	
	if ($_POST['confirm'] == "true"){
		// Výčet povolených tagů
		$allowtags = "";
		$game_league = strip_tags($_POST['game_league'],$allowtags);
		$league_www = strip_tags($_POST['league_www'],$allowtags);
		if ($_GET['action'] == "gameleague_add"){
				mysql_query("INSERT INTO $db_clan_gameleague VALUES('','".mysql_real_escape_string($game_league)."','".mysql_real_escape_string($league_www)."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
		if ($_GET['action'] == "gameleague_edit"){
			mysql_query("UPDATE $db_clan_gameleague SET  clan_gameleague_league='".mysql_real_escape_string($game_league)."', clan_gameleague_league_www='".mysql_real_escape_string($league_www)."' WHERE clan_gameleague_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$_GET['action'] = "gameleague_add";
		}
		if ($_GET['action'] == "gameleague_del"){
			mysql_query("DELETE FROM $db_clan_gameleague WHERE clan_gameleague_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$_GET['action'] = "gameleague_add";
		}
	}
	
	if ($_GET['action'] == "gameleague_edit" || $_GET['action'] == "gameleague_del"){
		$res = mysql_query("SELECT * FROM $db_clan_gameleague WHERE clan_gameleague_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._CLAN_CLANWARS." - "; if ($_GET['action'] == "gameleague_add"){ echo _CLAN_ADDGAMELEAGUE;} elseif ($_GET['action'] == "gameleague_del"){ echo _CLAN_DELGAMELEAGUE;} else {echo _CLAN_EDITGAMELEAGUE;} echo "</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\">\n";
	echo "			<a href=\"modul_clan_clanwars.php?action=showmain&amp;project=".$_SESSION['project']."&amp;hits=".$_GET['hits']."\">"._CLAN_MAIN."</a></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\"><form action=\"modul_clan_clanwars.php?action="; if ($_GET['action'] == "gameleague_add" || $_GET['action'] == "gameleague_add"){echo "gameleague_add";}elseif ($_GET['action'] == "gameleague_edit"){echo "gameleague_edit";} else {echo "gameleague_del";} echo "&amp;id=".$_GET['id']."&amp;hits=".$_GET['hits']."\" method=\"post\">\n";
	echo "			<strong>"._CLAN_GAMELEAGUE.":</strong>&nbsp;<input type=\"text\" name=\"game_league\" maxlength=\"255\" size=\"30\" "; if ($_GET['action'] == "gameleague_add"){echo "value=\"\"";} else {echo "value=\"".$ar['clan_gameleague_league']."\"";} echo ">\n";
	echo "			<strong>"._CLAN_GAMELEAGUEWWW.":</strong>&nbsp;<input type=\"text\" name=\"league_www\" maxlength=\"255\" size=\"30\" "; if ($_GET['action'] == "gameleague_add"){echo "value=\"\"";} else {echo "value=\"".$ar['clan_gameleague_league_www']."\"";} echo ">\n";
	echo "			<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "gameleague_add"){echo _CLAN_ADDGAMELEAGUE;} elseif ($_GET['action'] == "gameleague_del"){echo _CLAN_DELGAMELEAGUE;} else {echo _CLAN_EDITGAMELEAGUE;} echo "\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form></td>\n";
	echo "	</tr>\n";
	if($_GET['action'] == "gameleague_del"){
		echo "	<tr>\n";
		echo "		<td align=\"left\"><strong><span style=\"color : #FF0000;\">"._SHOP_MAN_DELCHECK."</span></strong><br><br></td>\n";
		echo "	</tr>\n";
	}
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"340\" align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_GAMELEAGUE."</span></td>\n";
	echo "		<td width=\"430\" align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_GAMELEAGUEWWW."</span></td>\n";
	echo "	</tr>\n";
	 	$res = mysql_query("SELECT * FROM $db_clan_gameleague ORDER BY clan_gameleague_league ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$i = 1;
		while($ar = mysql_fetch_array($res)){
			if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
			echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
			echo "	<td width=\"80\" align=\"center\">\n";
					if (CheckPriv("groups_clanwars_edit") == 1){echo "<a href=\"modul_clan_clanwars.php?action=gameleague_edit&amp;id=".$ar['clan_gameleague_id']."&amp;project=".$_SESSION['project']."&amp;hits=".$_GET['hits']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
					if (CheckPriv("groups_clanwars_del") == 1){echo " <a href=\"modul_clan_clanwars.php?action=gameleague_del&amp;id=".$ar['clan_gameleague_id']."&amp;project=".$_SESSION['project']."&amp;hits=".$_GET['hits']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
			echo "	</td>\n";
			echo "	<td width=\"20\" align=\"left\">".$ar['clan_gameleague_id']."</td>\n";
			echo "	<td width=\"340\" align=\"left\">".$ar['clan_gameleague_league']."</td>\n";
			echo "	<td width=\"430\" align=\"left\">".$ar['clan_gameleague_league_www']."</td>\n";
			echo "</tr>";
			$i++;
 		}
		echo "</table>";
}
/***********************************************************************************************************
*
*		ADD GAMETYPE
*
***********************************************************************************************************/
function GameType(){
	
	global $db_clan_gametype,$db_clan_games_main;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_clanwars_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	if ($_GET['action'] == "gametype_edit" || $_GET['action'] == "gametype_del"){
		$res = mysql_query("SELECT * FROM $db_clan_gametype WHERE clan_gametype_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._CLAN_CLANWARS." - "; if ($_GET['action'] == "gametype_add"){ echo _CLAN_ADDGAMETYPE;} else {echo _CLAN_EDITGAMETYPE;} echo "</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CAT_ADD."\">\n";
	echo "			<a href=\"modul_clan_clanwars.php?action=showmain&amp;project=".$_SESSION['project']."&amp;hits=".$_GET['hits']."\">"._CLAN_MAIN."</a></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['action'] == "gametype_del" && $_POST['confirm'] != "true"){$_GET['msg'] = "gametype_del_ch";}
	if ($_GET['msg']){
		echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "	<tr>\n";
	echo "		<td align=\"left\"><form action=\"sys_save.php?action=".$_GET['action']."&amp;id=".$_GET['id']."\" method=\"post\">\n";
	if ($_GET['action'] == "gametype_del"){
		echo "<strong>ID:</strong> ".$_GET['id']." ";
	}
	echo "			<strong>"._CLAN_GAMETYPE.":</strong>&nbsp;<input type=\"text\" name=\"game_type\" maxlength=\"255\" size=\"30\" "; if ($_GET['action'] == "gametype_edit" || $_GET['action'] == "gametype_del"){echo "value=\"".$ar['clan_gametype_game_type']."\"";} echo ">";
	echo "			<select name=\"game_id\">\n";
					$res_games = mysql_query("SELECT clan_games_main_id, clan_games_main_game FROM $db_clan_games_main ORDER BY clan_games_main_game ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while($ar_games = mysql_fetch_array($res_games)){
						echo "<option name=\"game\" value=\"".$ar_games['clan_games_main_id']."\" "; if ($ar_games['clan_games_main_id'] == $ar['clan_gametype_game_id']){ echo "selected=\"selected\"";} echo ">".$ar_games['clan_games_main_game']."</option>\n";
					}
	echo "			</select>";
	echo "			<input type=\"checkbox\" name=\"gametype_main\" value=\"1\" "; if ($ar['clan_gametype_main'] == 1) {echo "checked=\"checked\"";} echo ">";
	echo "			<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "gametype_edit"){echo _CLAN_GAMETYPE_EDIT;} elseif ($_GET['action'] == "gametype_del"){echo _CLAN_GAMETYPE_DEL;} else {echo _CLAN_GAMETYPE_ADD;} echo "\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"340\" align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_GAME."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_GAMETYPE."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_GAMETYPE_MAIN."</span></td>\n";
	echo "	</tr>";
	$res = mysql_query("SELECT cgt.clan_gametype_id, cgt.clan_gametype_game_id, cgt.clan_gametype_game_type, cgt.clan_gametype_main, cgm.clan_games_main_game FROM $db_clan_gametype AS cgt 
	LEFT JOIN $db_clan_games_main AS cgm ON cgm.clan_games_main_id=cgt.clan_gametype_game_id ORDER BY cgm.clan_games_main_game ASC, cgt.clan_gametype_game_type ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i=1;
	while($ar = mysql_fetch_array($res)){
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"80\" align=\"center\">\n";
					if (CheckPriv("groups_clanwars_edit") == 1){echo "<a href=\"modul_clan_clanwars.php?action=gametype_edit&amp;id=".$ar['clan_gametype_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
					if (CheckPriv("groups_clanwars_del") == 1){echo " <a href=\"modul_clan_clanwars.php?action=gametype_del&amp;id=".$ar['clan_gametype_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
		echo "	</td>\n";
		echo "	<td width=\"20\" align=\"left\">".$ar['clan_gametype_id']."</td>\n";
		echo "	<td width=\"340\" align=\"left\">".$ar['clan_games_main_game']."</td>\n";
		echo "	<td align=\"left\">".$ar['clan_gametype_game_type']."</td>\n";
		echo "	<td width=\"20\">"; if ($ar['clan_gametype_main'] == 1){ echo "<img src=\"images/sys_yes.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">"; } echo "</td>";
		echo "</tr>";
		$i++;
	}
	echo "</table>";
}
/***********************************************************************************************************
*
*		GET SCORE
*
*		$score1 - score prvniho tymu
*		$score2 - score druheho tymu
*
***********************************************************************************************************/
function GetScore ($score1 = 0,$score2 = 0){
	if ($score1 < 10){$team1_score = "0".$score1;} else {$team1_score = $score1;}
	if ($score2 < 10){$team2_score = "0".$score2;} else {$team2_score = $score2;}
	if ($score1 > $score2){
		$team1_score = "<span class=\"green\">".$team1_score."</span>";
		$team2_score = "<span class=\"green\">".$team2_score."</span>";
	} elseif ($score1 < $score2){
		$team1_score = "<span class=\"red\">".$team1_score."</span>";
		$team2_score = "<span class=\"red\">".$team2_score."</span>";
	} else {
		$team1_score = "<span class=\"dblue\">".$team1_score."</span>";
		$team2_score = "<span class=\"dblue\">".$team2_score."</span>";
	}
	return $team1_score.":".$team2_score;
}
include ("inc.header.php");
	if ($_GET['action'] == "") { ShowMain(); }
	if ($_GET['action'] == "open") { ShowMain(); }
	if ($_GET['action'] == "close") { ShowMain(); }
	if ($_GET['action'] == "showmain") { ShowMain(); }
	if ($_GET['action'] == "clanwar_add") { Clanwar(); }
	if ($_GET['action'] == "clanwar_edit") { Clanwar(); }
	if ($_GET['action'] == "clanwar_del") { Del(); }
	if ($_GET['action'] == "gametype_add") { GameType(); }
	if ($_GET['action'] == "gametype_edit") { GameType(); }
	if ($_GET['action'] == "gametype_del") { GameType(); }
	if ($_GET['action'] == "gameleague_add") { GameLeague(); }
	if ($_GET['action'] == "gameleague_edit") { GameLeague(); }
	if ($_GET['action'] == "gameleague_del") { GameLeague(); }
	if ($_GET['action'] == "komentar"){Comments($_GET['id'],"clanwars");}
	if ($_GET['action'] == "send"){Save("clanwars");}	// Ulozi komentar
	if ($_GET['action'] == "delete_comments"){DeleteComm();}
	if ($_GET['action'] == "save") { ShowMain(); }
	if ($_GET['action'] == "del_selected") { ShowMain(); }
include ("inc.footer.php");