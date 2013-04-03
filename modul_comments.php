<?php
/***********************************************************************************************************
*
*		KOMENTAR K CLANKUM
*
***********************************************************************************************************/
function Comments($id,$modul){
	
	global $db_comments,$db_articles,$db_poll_questions,$db_news,$db_clan_clanwars,$send;
	
	switch ($modul){
		case "article":
			$res = mysql_query("SELECT n.article_headline 
			FROM $db_comments AS nc 
			JOIN $db_articles AS n ON nc.comment_pid=n.article_id 
			WHERE comment_pid=".(integer)$id." AND comment_modul='".mysql_real_escape_string($modul)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar = mysql_fetch_array($res);
			$headline = $ar['article_headline'];
			break;
		case "poll":
			$res = mysql_query("SELECT p.poll_questions_question 
			FROM $db_comments AS nc 
			JOIN $db_poll_questions AS p ON nc.comment_pid=p.poll_questions_id 
			WHERE comment_pid=".(integer)$id." AND comment_modul='".mysql_real_escape_string($modul)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar = mysql_fetch_array($res);
			$headline = $ar['poll_questions_question'];
			break;
		case "news":
			$res = mysql_query("SELECT a.news_headline 
			FROM $db_comments AS nc 
			JOIN $db_news AS a ON nc.comment_pid=a.news_id 
			WHERE comment_pid=".(integer)$id." AND comment_modul='".mysql_real_escape_string($modul)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar = mysql_fetch_array($res);
			$headline = $ar['news_headline'];
			break;
		case "clanwars":
			$res = mysql_query("SELECT c.clan_cw_team1_name, c.clan_cw_team2_name 
			FROM $db_comments AS nc 
			JOIN $db_clan_clanwars AS c ON nc.comment_pid=c.clan_cw_id 
			WHERE comment_pid=".(integer)$id." AND comment_modul='".mysql_real_escape_string($modul)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar = mysql_fetch_array($res);
			$headline = $ar['clan_cw_team1_name']." vs ".$ar['clan_cw_team2_name'];
			break;
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>";
	echo "		<td align=\"left\" class=\"nadpis\">";
				if ($modul == "article"){echo _ARTICLES." - "._COMMENTS;}
				if ($modul == "poll"){echo _POLL." - "._POLL_COMMENTS;}
				if ($modul == "news"){echo _NEWS." - "._NEWS_COMMENTS;}
				if ($modul == "clanwars"){echo _CLAN_CLANWARS." - "._CLAN_COMMENT;}
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td><a href=\"modul_"; if ($modul == "article"){echo "articles";} if ($modul == "poll"){echo "poll";} if ($modul == "news"){echo "article";} if ($modul == "clanwars"){echo "clan_clanwars";} echo ".php?project=".$_SESSION['project']."\"><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_MAINMENU."\">"._CMN_MAINMENU."</a></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td><strong>".$headline."<strong></td>";
	echo "	</tr>";
	echo "</table><br><br>";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<form action=\"modul_articles.php?action=replace_comments&amp;id=".$id."\"  method=\"post\">";
	$res_comments = mysql_query("SELECT comment_id, comment_subject, comment_text, comment_author, comment_show, comment_date, INET_NTOA(comment_ip) AS comment_ip FROM $db_comments WHERE comment_pid=".(integer)$id." AND comment_modul='".mysql_real_escape_string($modul)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i = 0;
	$comments_number = 1;
	while ($ar_comments = mysql_fetch_array($res_comments)){
		$comment_subject = stripslashes($ar_comments['comment_subject']);
		$comment_text = stripslashes($ar_comments['comment_text']);
		$comment_author = wordwrap( $ar_comments['comment_author'], 100, "\n", 1);
		$comment_subject = wordwrap( $comment_subject, 100, "\n", 1);
		$comment_email = wordwrap( $comment_email, 100, "\n", 1);
		$comment_text = wordwrap( $comment_text, 100, "\n", 1);
		$comment_date = FormatDatetime($ar_comments['comment_date'],"d.m.Y, H:i");
		if ($comments_number % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"100\" rowspan=\"2\" valign=\"top\">\n";
		echo "		<input type=\"hidden\" name=\"comments_num\" value=\"".$comments_number."\">\n";
		echo "		<input type=\"hidden\" name=\"comments_data[".$comments_number."_comment_id]\" value=\"".$ar_comments['comment_id']."\">\n";
		echo 		_MNC_REPLACE_1."<input type=\"checkbox\" name=\"dc[]\" value=\"".$ar_comments['comment_id']."\"><br>\n";
		echo 		_MNC_SHOW_ON."<input type=\"radio\" name=\"comments_data[".$comments_number."_comment_show]\" value=\"1\" "; if ($ar_comments['comment_show'] == 1){echo "checked";} echo "><br>\n";
		echo 		_MNC_SHOW_OFF."<input type=\"radio\" name=\"comments_data[".$comments_number."_comment_show]\" value=\"0\" "; if ($ar_comments['comment_show'] == 0){echo "checked";} echo "><br>\n";
		echo 		_MNC_DEL."<input type=\"radio\" name=\"comments_data[".$comments_number."_comment_show]\" value=\"2\"><br>\n";
		echo "	</td>\n";
		echo "	<td width=\"680\">#"; echo $i+1; echo "&nbsp;-&nbsp;<span style=\"color: #FF0000\"><strong><a href=\"mailto:".$comment_email."\">".$comment_author."</a></strong></span>&nbsp;".$ar_comments['comment_ip']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$comment_date."<br>\n";
		echo "	<strong>".$comment_subject."</strong><br>\n";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td class=\"".$cat_class."\" "; if ($ar_comments['comment_show'] == 0){ echo "style=\"background-color:#ff0000;\"";} echo "><p>".$comment_text."</p><br></td>\n";
		echo "</tr>";
		echo "<tr>\n";
		echo "	<td></td>\n";
		echo "</tr>";
		$i++;
		$comments_number++;
	}
	echo "</table>\n";
	echo "<table width=\"700\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
	echo "	<tr class=\"komentar\">\n";
	echo "		<td width=\"300\"><strong>"._MNC_TOPIC_REPLACE."</strong></td>\n";
	echo "		<td><input type=\"text\" name=\"r_topic\" size=\"50\" maxlength=\"30\" value=\""._MNC_TOPIC_REPLACE_TXT."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr class=\"komentar\">\n";
	echo "		<td width=\"300\"><strong>"._MNC_TEXT_REPLACE."</strong></td>\n";
	echo "		<td><textarea cols=\"40\" rows=\"7\" name=\"r_tekst\">"._MNC_TEXT_REPLACE_TXT."</textarea></td>\n";
	echo "	</tr>\n";
	echo "	<tr class=\"komentar\">\n";
	echo "		<td class=\"komentar\" colspan=\"2\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<!-- <input type=\"hidden\" name=\"action\" value=\"delete_comments\"> -->\n";
	echo "			<input type=\"hidden\" name=\"r_modul\" value=\"".$modul."\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"submit\" value=\""._MNC_REPLACE."\" class=\"eden_button\"></form></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"400\" align=\"center\" cellpadding=\"4\" cellspacing=\"0\" border=\"0\" class=\"komentar\">\n";
	echo "	<form action=\"modul_articles.php?action=send&amp;id=".$id."&amp;modul=".$modul."\" name=\"formular\" method=\"post\" onsubmit=\"return kontrola(this)\">\n";
	echo "	<tr>\n";
	echo "		<td>"._MNC_NAME."</td>\n";
	echo "		<td><input type=\"text\" name=\"jmeno\" size=\"30\" maxlength=\"30\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>"._MNC_EMAIL."</td>\n";
	echo "		<td><input type=\"text\" name=\"email\" size=\"30\" maxlength=\"30\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>"._MNC_TOPIC."<br><br></td>\n";
	echo "		<td><input type=\"text\" name=\"topic\" size=\"40\" maxlength=\"40\"><br><br></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"2\">"._MNC_COMMENT."<br>\n";
	echo "			<textarea cols=\"40\" rows=\"7\" name=\"comments\"></textarea><br><br>\n";
	echo "			<input type=\"submit\" value=\"". _CMN_SUBMIT."\" class=\"eden_button\">&nbsp;&nbsp;<input type=\"Reset\" value=\"Reset\">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "	</form>\n";
	echo "</table>";
}

/***********************************************************************************************************
*
*		ULOZENI KOMENTARE K CLANKUM
*
***********************************************************************************************************/
function Save($modul){
	
	global $db_comments;
	global $eden_cfg;
	
	$comments		= $_POST['comments'];
	$email			= $_POST['email'];
	$jmeno			= $_POST['jmeno'];
	$id				= $_GET['id'];
	$topic			= $_POST['topic'];
	
	// Výcet povolených tagu
	$allowtags = "<embed>, <marquee>, <blink>, <hr>, <ul>, <li>, <ol>, <p>, <br>, <br />, <font>, <b>, <u>, <i>, <small>, <big>, <strong>, <em>, <a>";
	// Z obsahu promenné body vyjmout nepovolené tagy
	$jmeno = strip_tags($jmeno,$allowtags);
	$email = strip_tags($email,$allowtags);
	$topic = strip_tags($topic,$allowtags);
	$comments = strip_tags($_POST['comments'],$allowtags);
	$comments = str_ireplace( "&lt;","<",$comments);
	$comments = str_ireplace( "&gt;",">",$comments);
	$comments = str_ireplace( "&amp;","&",$comments);
	$comments = str_ireplace( "&quot;",'"',$comments);
	$comments = htmlspecialchars($comments, ENT_QUOTES);
	$comments = str_ireplace("\n","<br />",$comments);
	$comments = ConvertBracketLinks ($comments,3);
	
	// Aby se minimalizovalo znovuulozeni stejneho zaznamu do databaze po refreshi je treba zjistit prispevek s nejvyssim cislem
	$res = mysql_query("SELECT MAX(comment_id) FROM $db_comments WHERE comment_pid=".(integer)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	// Zjistene cislo se vlozi do dotazu
	$res2 = mysql_query("SELECT comment_text, comment_author FROM $db_comments WHERE comment_pid=".(integer)$id." AND comment_id=".(integer)$ar[0]) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar2 = mysql_fetch_array($res2);
	// A proveri se podminka
	if ($comments == $ar2['comment_text'] && $jmeno == $ar2['comment_author']){
		Comments($id,$modul);
	} else {
		// Pokud neni text shodny tak se vse ulozi (nefunguje pokud
		$datum = date("YmdHis");
		$res = mysql_query("INSERT INTO $db_comments VALUES('0','".(integer)$id."','".mysql_real_escape_string($jmeno)."','".mysql_real_escape_string($email)."',NOW(),'".mysql_real_escape_string($topic)."','".mysql_real_escape_string($comments)."','".mysql_real_escape_string($modul)."',INET_ATON('".mysql_real_escape_string($eden_cfg['ip'])."'),'','0','0')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		unset($jmeno,$email,$topic,$comments);
		Comments($id,$modul);
	}
}

/***********************************************************************************************************
*
*		SMAZANI OZNACENYCH POLOZEK Z KOMENTARE
*
***********************************************************************************************************/
function DeleteComm($modul){
	
	global $db_comments;
	
	$confirm	= $_POST['confirm'];
	$dc			= $_POST['dc'];
	$id			= $_GET['id'];
	
	if (CheckPriv("groups_article_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	if ($confirm == "true") {
		$res = mysql_query("SELECT comment_id FROM $db_comments WHERE comment_pid=".(integer)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num = mysql_num_rows($res);
		$i = 0;
		
		while ($i < $num) {
			$nci = $dc[$i];
 			$res = mysql_query("DELETE FROM $db_comments WHERE comment_id=".(integer)$nci) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$i++;
		}
	}
	Comments($id,$modul);
}

/***********************************************************************************************************
*
*		PREPSANI OZNACENYCH POLOZEK Z KOMENTARE
*
***********************************************************************************************************/
function ReplaceComm($topic,$tekst,$modul){
	
	global $db_comments;
	
	$confirm 		= $_POST['confirm'];
	$id				= $_GET['id'];
	$dc				= $_POST['dc'];
	$nc				= $_POST['comments_data'];
	$r_tekst		= $_POST['r_tekst'];
	
	if (CheckPriv("groups_article_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	if ($confirm == "true") {
		$r_tekst = strip_tags($r_tekst,$allowtags);
		$r_tekst = str_ireplace( "&lt;","<",$r_tekst);
		$r_tekst = str_ireplace( "&gt;",">",$r_tekst);
		$r_tekst = str_ireplace( "&amp;","&",$r_tekst);
		$r_tekst = str_ireplace( "&quot;",'"',$r_tekst);
		$r_tekst = htmlspecialchars($r_tekst, ENT_QUOTES);
		$r_tekst = str_ireplace("\n","<br />",$r_tekst);
		$r_tekst = ConvertBracketLinks ($r_tekst, 3);
		
		$res = mysql_query("SELECT comment_id FROM $db_comments WHERE comment_pid=".(integer)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num = mysql_num_rows($res);
		$i = 0;
		$comments_data = $_POST['comments_data'];
		while($i <= $_POST['comments_num']){
			$comment_id = $comments_data[$i.'_comment_id'];
			$comment_show = $comments_data[$i.'_comment_show'];
			$dci = $dc[$i];
 			/* Prepsani komentare textem admina */
			$res1 = mysql_query("UPDATE $db_comments SET comment_subject='".mysql_real_escape_string($topic)."', comment_text='".mysql_real_escape_string($r_tekst)."'  WHERE comment_id=".(integer)$dci) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			if ($comment_show < 2){
				/* Nezobrazovani vybraneho komentare */
				$res2 = mysql_query("UPDATE $db_comments SET comment_show=".(integer)$comment_show." WHERE comment_id=".(integer)$comment_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}
			if ($comment_show == 2){
				/* Odstarneni vybraneho komentare */
				$res3 = mysql_query("DELETE FROM $db_comments WHERE comment_id=".(integer)$comment_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}
			
			$i++;
		}
	}
	Comments($id,$modul);
}