<?php
/***********************************************************************************************************
*
*		ANKETA
*
*		$aid			=	cislo ankety, pokud je 0 zobrazi se posledni anketa v danem jazyce
*		$poll_lang			=	jazyk ve kterem chceme anketu zobrazit (je navoleno v EDENU u ankety)
*		$table_width	=	sirka tabuly v px
*		$sloupec_height	=	vyska sloupce v px
*		$poll_mode		=	mod ankety
*			poll		=	standardni mod pro ankety,
*			article		=	mod pro clanky
*			article_front	=	mod pro ankety viditelne v clancich na hlavni strance
*		$l_width		=	maximalni sirka sloupce ankety
*		$r_width		=	sirka prave casti s vysledkem
*
*		poll_questions_for	= 	0 - Poll
*								1 - Articles
								2 - Users
*
***********************************************************************************************************/
function Poll($aid, $poll_lang="cz", $table_width=150, $sloupec_height=10, $poll_mode="poll", $l_width=100, $r_width=40){
	
	global $db_poll_questions,$db_poll_answers,$db_comments,$db_setup,$db_comments_log;
	global $eden_cfg;
	global $project;
	
	$_GET['action'] = AGet($_GET,'action');
	$_GET['filter'] = AGet($_GET,'filter');
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	if ($poll_mode == "article"){
		$css_name = "_aerticle";
	}elseif ($poll_mode == "article_front"){
		$css_name = "_article_front";
	} else {
		$css_name = "";
	}
	if ($aid == 0){ // Pokud je zadana nula bude se vybirat posledni anketa v rade v danem jazyce
		$res1 = mysql_query("SELECT poll_questions_id, poll_questions_question, poll_questions_for FROM $db_poll_questions WHERE poll_questions_lang='".mysql_real_escape_string($poll_lang)."' AND poll_questions_for=0 ORDER BY poll_questions_id DESC LIMIT 1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar1 = mysql_fetch_array($res1);
		$aid = $ar1['poll_questions_id'];
	} else { // pokud je zadano cislo ankety bude se zobrazovat ona
		$res1 = mysql_query("SELECT poll_questions_id, poll_questions_question, poll_questions_for FROM $db_poll_questions WHERE poll_questions_id=".(integer)$aid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar1 = mysql_fetch_array($res1);
	}
	if (isset($pid) == ""){
		$res2 = mysql_query("SELECT poll_questions_id, poll_questions_answers FROM $db_poll_questions WHERE poll_questions_id=".(integer)$aid." ORDER BY poll_questions_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar2 = mysql_fetch_array($res2);
		$pid = $ar2['poll_questions_id'];
	}
	$res4 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$aid." AND comment_modul='poll'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Nastaveni ukazatele na komentare v danem clanku
	$num4 = mysql_fetch_array($res4); // Zjisteni poctu prispevku k danemu clanku
	
	$res3 = mysql_query("SELECT COUNT(*) AS suma FROM $db_poll_answers WHERE poll_answers_pid=".(integer)$pid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar3 = mysql_fetch_array($res3);
	
	$res_setup = mysql_query("SELECT setup_poll_iptime, setup_poll_show_results, setup_poll_results_as_number FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	// Zjisteni datumu posledniho posledni navstevy uzivatele v danych komentarich
	$res7 = mysql_query("SELECT comments_log_comments FROM $db_comments_log WHERE comments_log_admin_id=".(integer)$_SESSION['loginid']." AND comments_log_item_id=".(integer)$aid." AND comments_log_modul='poll'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar7 = mysql_fetch_array($res7);
	
	// Vyber rezimu ankety, pokud je vybrano poll je to klasicka anketa,
	// pokud je vybrano article, je to anketa z clanku a na ten pak zase musime ukazat
	if ($poll_mode == "poll"){
		$link_back = urlencode('index.php?action=article&amp;lang='.$poll_lang.'&amp;filter='.$_GET['filter']);
	}elseif ($poll_mode == "article"){
		$link_back = urlencode('index.php?action=clanek&amp;id='.$_GET['id'].'&amp;lang='.$poll_lang.'&amp;filter='.$_GET['filter'].'&amp;page_mode=');
	}elseif ($poll_mode == "article_front"){
		$link_back = urlencode('index.php?action=article&amp;lang='.$poll_lang.'&amp;filter='.$_GET['filter']);
	} else {
		$link_back = "";
	}
	// Nacteme do pole nejnovejsi datum odpovedi pro danou IP a otazku
	
	$res5 = mysql_query("SELECT MAX(poll_answers_date) FROM $db_poll_answers WHERE poll_answers_pid=".(integer)$aid." AND poll_answers_ip=INET_ATON('".mysql_real_escape_string($eden_cfg['ip'])."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar5 = mysql_fetch_array($res5);
	$at = (EdenGetMkTime($ar5[0],"Datetime") + $ar_setup['setup_poll_iptime']); // Doba pripojeni - $ar_setup['setup_poll_iptime'] je pocet sekund, ktere musi uplynout aby se zapocitalo hlasovani se stejne IP pro danou otazku
	if ($ar_setup['setup_poll_show_results'] == 0){$show_results = 0; $show_link = 1;}
	if ($ar_setup['setup_poll_show_results'] == 1){$show_results = 1; $show_link = 1;}
	if ($at > date("U")){$show_results = 1;$show_link = 0;}
	if (AGet($_COOKIE,$project."_poll_id") == $aid){$show_results = 1;$show_link = 0;}
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"".$table_width."\">";
	echo "	<tr>";
	echo "		<td width=\"".$table_width."\" id=\"eden_poll_q\">".$ar1['poll_questions_question']."</td>";
	echo "	 </tr>";
	echo "	 <tr>";
	echo "	   	<td width=\"".$table_width."\" id=\"eden_poll_a\">";
				if ($ar_setup['setup_poll_show_results'] == 0 && $show_link == 1){
			   		echo "<form enctype=\"multipart/form-data\" name=\"eden_poll\" action=\"".$eden_cfg['url_edencms']."eden_save.php?action=".$_GET['action']."&amp;mode=poll&amp;lang=".$poll_lang."&amp;filter=".$_GET['filter']."&amp;wid=".$aid."&amp;project=".$_SESSION['project']."&amp;link_back=".$link_back."\" method=\"post\" ONSUBMIT=\"return CheckPoll(this)\">";
			   	}
	 echo "	   	<ul class=\"eden_poll".$css_name."\">"; 
	$odg = explode ("||", $ar2['poll_questions_answers']);
	$x = 0;
	while (list($key,$values) = each($odg)){
		$res = mysql_query("SELECT COUNT(*) AS vote FROM $db_poll_answers WHERE poll_answers_pid=".(integer)$pid." AND poll_answers_answer='".mysql_real_escape_string($key)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$daily[$x] = $ar['vote'];
		$max = $ar3['suma'];
		if ($max != 0){
			$poll_percentage = $daily[$x]/$max*100;
			$poll_percentage = MyCeil($poll_percentage,1);
			$width = round(($l_width/100)*$poll_percentage);
		} else {
			$poll_percentage = 0;
			$width = 0;
		}
		echo "<li>";
		echo $x+1; echo ". "; 
		if ($show_results == 1){
			if($show_link == 0){
				echo $values;
			} else { 
				echo "<a href=\"".$eden_cfg['url_edencms']."eden_save.php?action=".$_GET['action']."&amp;lang=".$poll_lang."&amp;filter=".$_GET['filter']."&amp;mode=poll&amp;wid=".$aid."&amp;eden_poll_vote=".$x."&amp;project=".$_SESSION['project']."&amp;link_back=".$link_back."\" target=\"_self\">".$values."</a>"; 
			} 
			echo "<br>";
			echo "<div class=\"eden_poll_left\" style=\"width:".$l_width."px;\">"; 
			if ($width > 0){
				echo "<img src=\"images/poll_orange.gif\" width=\"".$width."\" height=\"".$sloupec_height."\" border=\"0\" alt=\"".$ar['vote']."\" title=\"".$ar['vote']."\">"; 
			}
			echo "</div><div class=\"eden_poll_right\" style=\"width:".$r_width."px;\">"; 
			if($ar_setup['setup_poll_results_as_number'] == 1){
				echo $ar['vote'];} else {echo $poll_percentage."%";} echo "</div>"; 
	   		} elseif ($show_results == 0){
	   			echo "<input type=\"radio\" name=\"eden_poll_vote\" class=\"eden_poll_vote\" value=\"".$x."\"> ".$values;
	   		}
	   		echo "</li>";
	   		$x++;
		}
		echo "</ul>";
		if ($ar_setup['setup_poll_show_results'] == 0 && $show_link == 1){
			echo "<input type=\"submit\" class=\"eden_button_poll\" value=\""._POLL_VOTE."\"></form>";
		}
		echo "	</td>";
		echo "</tr>";
		if ( $ar1['poll_questions_for'] == 0){
			echo "<tr>";
			echo "	<td width=\"".$table_width."\" id=\"eden_poll_footer\"><br><a href=\"index.php?action=komentar&amp;lang=".$poll_lang."&amp;filter=".$_GET['filter']."&amp;id=".$ar1['poll_questions_id']."&amp;modul=poll&amp;page_mode=".AGet($_GET,'page_mode')."\" target=\"_self\">"._COM_COM." ".$num4[0]."</a> "; if (($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin") && ($ar7['comments_log_comments'] < $num4[0])){$new_comments = ($num4[0] - $ar7['comments_log_comments']); echo "</br ><a href=\"index.php?action=komentar&amp;lang=".$poll_lang."&amp;filter=".$_GET['filter']."&amp;id=".$ar1['poll_questions_id']."&amp;modul=poll&amp;page_mode=".AGet($_GET,'page_mode')."\" target=\"_self\">"._COM_COM_NEW." ".$new_comments."</a>"; } echo "</td>";
			echo "</tr>";
	}
	echo "</table>";
}
/***********************************************************************************************************
*
*		ANKETY STARSI
*
*		Promenne pro ankety
*		$_GET['lang']			=	jazyk ve kterem chceme anketu zobrazit (je navoleno v EDENU u ankety)
*		$poll_table_width		=	sirka tabuly v px
*		$poll_column_height		=	vyska sloupce v px
*		$poll_l_width			=	maximalni sirka sloupce ankety
*		$poll_r_width			=	sirka prave casti s vysledkem
*		$poll_q_for				= 	0 - Poll
*									1 - Article
*									2 - Users
*		$poll_hits				=	Pocet anket na strance
*
*		Promenne pro komenare
*		$comm_string_wrap		= Pocet znaku pro zalamovani textu v komentarich
*		$comm_td_width			= Sirka bunky
*		$comm_tbl_width			= Sirka tabulky
*
***********************************************************************************************************/
class OlderPoll {
	
	var $poll_table_width;
	var $poll_column_height;
	var $poll_l_width;
	var $poll_r_width;
	var $poll_q_for;
	var $poll_hits;
	var $comm_string_wrap = 90;
	var $comm_td_width = 400;
	var $comm_tbl_width = 407;
	
	function OlderPolls(){
		
		global $db_poll_questions,$db_poll_answers,$db_comments,$db_setup;
		global $eden_cfg;
		
		$_GET['action'] = AGet($_GET,'action');
		$_GET['filter'] = AGet($_GET,'filter');
		$_GET['lang'] = AGet($_GET,'lang');
		
		$res_all_poll = mysql_query("SELECT COUNT(*) FROM $db_poll_questions WHERE poll_questions_lang='".mysql_real_escape_string($_GET['lang'])."' AND poll_questions_for=".(integer)$this->poll_q_for) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num_all_poll = mysql_fetch_array($res_all_poll);
		
		/* Nastaveni odkazu zpet na stranku ze ktere se hlasovalo */
		$link_back = urlencode('index.php?action='.$_GET['action'].'&amp;lang='.$poll_lang.'&amp;filter='.$_GET['filter'].'&amp;status=open');
		
		$m = 0;// nastaveni iterace
		if (empty($_GET['page'])) {$_GET['page'] = 1;} // i kdyz je promenna $_GET['page'] prazdna nastavi se pocet starnek na 1
		//$this->poll_hits=3; //Zde se nastavuje pocet prispevku
		$stw2 = ($num_all_poll[0] / $this->poll_hits);
		$stw2 = (integer) $stw2;
		if ($num_all_poll[0] % $this->poll_hits > 0) {$stw2++;}
		$np = $_GET['page'] + 1;
		$pp = $_GET['page'] - 1;
		if ($_GET['page'] == 1) { $pp = 1; }
		if ($np > $stw2) { $np = $stw2;}
		
		$sp = ($_GET['page']-1) * $this->poll_hits;
		$ep = ($_GET['page']-1) * $this->poll_hits + $this->poll_hits;
		
		echo "<table cellpadding=\"5\" cellspacing=\"2\" border=\"0\" width=\"".$this->poll_table_width."\">";
		echo "<tr id=\"old_poll_title\">";
		echo "		<td class=\"old_poll_date\">"._POLL_DATE."</td>";
		echo "		<td class=\"old_poll_quest\">"._POLL_QUESTIONS."</td>";
		echo "		<td class=\"old_poll_author\">"._POLL_AUTHOR."</td>";
		echo "		<td class=\"old_poll_comm\">"._POLL_COMMENTS."</td>";
		echo "	</tr>";
		$cislo = 0;
		$limit = "LIMIT ".(integer)$sp.", ".(integer)$this->poll_hits."";
		$res1 = mysql_query("SELECT poll_questions_id, poll_questions_question, poll_questions_answers, poll_questions_author, poll_questions_date FROM $db_poll_questions WHERE poll_questions_lang='".mysql_real_escape_string($_GET['lang'])."' AND poll_questions_for=".(integer)$this->poll_q_for." ORDER BY poll_questions_id DESC $limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		while($ar1 = mysql_fetch_array($res1)){
			$aid = $ar1['poll_questions_id'];
			$res2 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar1['poll_questions_id']." AND comment_modul='poll_users'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$num2 = mysql_fetch_array($res2);
			$res3 = mysql_query("SELECT COUNT(*) AS suma FROM $db_poll_answers WHERE poll_answers_pid=".(integer)$aid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar3 = mysql_fetch_array($res3);
			$res_setup = mysql_query("SELECT setup_poll_results_as_number FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_setup = mysql_fetch_array($res_setup);
			echo "<tr "; if ($cislo % 2 == 0){echo "class=\"suda\"";} else {echo "class=\"licha\"";} echo ">";
			echo "	<td valign=\"top\" class=\"old_poll_date\">".FormatDatetime($ar1['poll_questions_date'],"d.m.Y")."</td>";
			echo "	<td valign=\"top\" class=\"old_poll_quest\">"; if ($_SESSION['u_status'] == "admin" && CheckPriv("groups_wp_users_del") == 1){ echo "<a href=\"index.php?action=users_polls&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;pid=".$ar1['poll_questions_id']."&amp;page_mode=del\"><img src=\"./images/sys_del.gif\" width=\"18\" height=\"18\" border=\"0\" align=\"middle\"></a>&nbsp;"; } echo "<a href=\"index.php?action=".$_GET['action']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;asid=".$ar1['poll_questions_id']."&amp;status="; if ($_GET['status'] == "open" && $_GET['asid'] == $ar1['poll_questions_id']){echo "close";} else {echo "open";} echo "\">".$ar1['poll_questions_question']."</a></td>";
			echo "	<td valign=\"top\" class=\"old_poll_author\"><a href=\"index.php?action=user_details&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;user_id=".$ar1['poll_questions_author']."&amp;page_mode=\">".GetNickName($ar1['poll_questions_author'])."</a></td>";
			echo "	<td valign=\"top\" class=\"old_poll_comm\"><a href=\"index.php?action=".$_GET['action']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;asid=".$ar1['poll_questions_id']."&amp;status="; if ($_GET['status'] == "open" && $_GET['asid'] == $ar1['poll_questions_id']){echo "close";} else {echo "open";} echo "\">".$num2[0]."</a></td>";
			echo "</tr>"; if ($_GET['status'] == "open" && $ar1['poll_questions_id'] == $_GET['asid']){
				$odg = explode ("||", $ar1['poll_questions_answers']);
				$x = 0;
				echo "<tr>";
				echo "	<td colspan=\"4\" width=\"".$this->poll_table_width."\" id=\"eden_poll_a\"><ul class=\"eden_poll_older\">";
				while (list($key,$values) = each($odg)){
					$res = mysql_query("SELECT COUNT(*) AS vote FROM $db_poll_answers WHERE poll_answers_pid=".(integer)$aid." AND poll_answers_answer='".mysql_real_escape_string($key)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$ar = mysql_fetch_array($res);
					$daily[$x] = $ar['vote'];
					$max = $ar3['suma'];
					if ($max != 0){
						$poll_percentage = $daily[$x]/$max*100;
						$poll_percentage = MyCeil($poll_percentage,1);
						$width = round(($this->poll_l_width/100)*$poll_percentage);
					} else {
						$poll_percentage = 0;
						$width = 0;
					}
					echo "<li>\n";
					echo $x+1; echo ". <a href=\"".$eden_cfg['url_edencms']."eden_save.php?action=".$_GET['action']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;mode=poll&amp;wid=".$aid."&amp;eden_poll_vote=".$x."&amp;project=".$_SESSION['project']."&amp;link_back=".$link_back."\" target=\"_self\">".$values."</a><br>\n";
					echo "	<div class=\"eden_poll_left\" style=\"width:".$this->poll_l_width."px;\"><img src=\"images/poll_orange.gif\" width=\"".$width."\" height=\"".$this->poll_column_height."\" border=\"0\" alt=\"\"></div><div class='eden_poll_right' style=\"width:".$this->poll_r_width."px;\">"; if($ar_setup['setup_poll_results_as_number'] == 1){echo $ar['vote'];} else {echo $poll_percentage."%";} echo "</div>\n";
					echo "</li>";
					$x++;
				}
				echo "</ul>";
				Comments($ar1['poll_questions_id'],"poll_users",$this->comm_string_wrap,400,400,$this->comm_td_width,$this->comm_tbl_width);
				echo "</td>";
				echo "</tr>";
			}
			$cislo++;
		}
		//***********************************************
		//		 Pocitani stranek - Novinky
		//***********************************************
		// Pokud je novinek vice, nezli se vejde na 1 stranku zobrazi se jejich pocitani a prechazeni mezi nima
		if ($stw2 > 1){
			echo "<tr>";
			echo "	<td align=\"left\" valign=\"top\" colspan=\"3\">";
				//Zobrazeni cisla poctu stranek
			for ($i=1;$i<=$stw2;$i++) {
				if ($_GET['page']==$i) {
					echo " <strong>".$i."</strong> ";
				} else {
					echo " <a href=\"index.php?action=".$_GET['action']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;page=".$i."\">".$i."</a> ";
				}
			}
			//Zobrazeni sipek s predchozimi a dalsimi strankami novinek
			if ($_GET['page'] > 1){echo "<br><a href=\"index.php?action=".$_GET['action']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;page=".$pp."\">"._CMN_PREVIOUS."</a>";} else {echo "<br>"._CMN_PREVIOUS;} echo " <--|--> "; if ($_GET['page'] == $stw2){echo _CMN_NEXT;} else {echo "<a href=\"index.php?action=".$_GET['action']."lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;page=".$np."\">"._CMN_NEXT."</a>";}
			echo"	</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
}