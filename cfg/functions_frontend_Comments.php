<?php
/***********************************************************************************************************
*
*		KOMENTARE
*
*		$pid			- Parent ID
*		$modul			- Modul kde se komentare zobrazi (article, news, poll, download, clanwars)
*		$string_wrap	- Zalamovat na dany pocet znaku.
*		$ifr_width		- Sirka iframe
*		$ifr_height		- Vyska iframe
*		$td_width		- Sirka tabulky s komentarem
*		$tbl_width		- Sirka tabulky s formularem
*
***********************************************************************************************************/
function Comments($pid,$modul,$string_wrap=90,$ifr_width=480,$ifr_height=400,$td_width=400,$tbl_width=407){
	
	global $db_comments,$db_comments_log,$db_news,$db_setup,$db_articles,$db_poll_questions,$db_country;
	global $db_admin,$db_admin_contact,$db_admin_category,$db_setup_lang,$db_category,$db_liga_player,$db_thumbs;
	global $url_admins_category,$url_eden_images,$url_flags,$url_category;;
	global $eden_cfg;
	global $project,$title;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	/* Nacteme nastaveni */
	$res_setup = mysql_query("SELECT
	s.setup_adds_in_com,
	s.setup_adds_in_com_id,
	s.setup_comm_flag,
	s.setup_comm_adm_img,
	s.setup_comm_anonym,
	s.setup_comm_autofill,
	s.setup_comm_link,
	s.setup_comm_thumbs,
	s.setup_reg_admin_nick,
	s.setup_show_author_nick,
	sl.setup_lang_comments_rules
	FROM $db_setup AS s, $db_setup_lang AS sl WHERE sl.setup_lang='".mysql_real_escape_string($_GET['lang'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$vysledek = mysql_query("
	SELECT	a.admin_id, nc.comment_id, nc.comment_author, nc.comment_subject, nc.comment_email, nc.comment_text, nc.comment_reg_user_comm, nc.comment_date, 
	INET_NTOA(nc.comment_ip) AS comment_ip, nc.comment_thumbs_up, nc.comment_thumbs_down, c.country_shortname, a.admin_cat1, ac.admin_contact_country, 
	acat.admin_category_id, acat.admin_category_topicimage, acat.admin_category_topictext  
	FROM $db_comments AS nc 
	LEFT JOIN $db_admin AS a ON a.admin_id=nc.comment_reg_user_comm 
	LEFT JOIN $db_admin_contact AS ac ON ac.aid=nc.comment_reg_user_comm 
	LEFT JOIN $db_admin_category AS acat ON acat.admin_category_id=a.admin_cat1 
	LEFT JOIN $db_country AS c ON c.country_id=ac.admin_contact_country 
	WHERE comment_show=1 AND comment_pid=".(integer)$pid." AND comment_modul='".mysql_real_escape_string($modul)."' 
	ORDER BY comment_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_num_rows($vysledek);
	
	echo "	<table cellpadding=\"3\">\n";
	echo "		<tr>\n";
	echo "			<td>\n";
		if ($modul == "article"){
			$res = mysql_query("SELECT article_comments FROM $db_articles WHERE article_id=".(integer)$pid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar = mysql_fetch_array($res);
			
			/* Zajisteni zobrazeni ci skryti komentářů */
			if ($ar['article_comments'] == 1){$allow_comments = 1;} else {$allow_comments = 0;}
			echo "<iframe id=\"Clanky\" width=\"".$ifr_width."\" height=\"".$ifr_height."\" frameborder=\"1\" noresize=\"noresize\" scrolling=\"auto\" src=\"".$eden_cfg['url_edencms']."eden_iframe.php?action=clanekiframe&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;id=".$pid."&amp;project=".$project."\"></iframe><br><br>"; 
		} elseif ($modul == "news"){
			$res_act = mysql_query("SELECT a.news_id, a.news_text, a.news_headline, a.news_author_id, a.news_comments, a.news_date_on, a.news_source, c.category_image FROM $db_news AS a, $db_category AS c WHERE a.news_id=".(integer)$pid." AND c.category_id=a.news_category_id") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_act = mysql_fetch_array($res_act);
			
			$vysledek3 = mysql_query("SELECT admin_id, admin_firstname, admin_name, admin_nick, admin_email FROM $db_admin WHERE admin_id=".(integer)$ar_act['news_author_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Vybere jmeno autora
			$ar3 = mysql_fetch_array($vysledek3);
			
			$admin_id = $ar3['admin_id'];
			$admin_nick = $ar3['admin_nick'];
			$admin_email = $ar3['admin_email'];
			if ($ar_setup['setup_show_author_nick'] == 1){$admin_nickname = $ar3['admin_nick'];} else {$admin_nickname = $ar3['admin_firstname'].' '.$ar3['admin_name'];}
			
			$news_id = $ar_act['news_id'];
			$news_text = TreatText($ar_act['news_text'],$string_wrap);
			$news_headline = TreatText($ar_act['news_headline'],$string_wrap);
			$news_source = $ar_act['news_source'];
			$news_comments = $ar_act['news_comments'];
			$news_date_on = $ar_act['news_date_on'];
			
			$category_image = $ar_act['category_image'];
			
			// Nacteni sablony
			include "templates/tpl.news_single.comm.php";
			/* Zajisteni zobrazeni ci skryti komentářů */
			if ($ar_act['news_comments'] == 1){$allow_comments = 1;} else {$allow_comments = 0;}
		} elseif ($modul == "poll" || $modul == "poll_users"){
			$res = mysql_query("SELECT poll_questions_question FROM $db_poll_questions WHERE poll_questions_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar = mysql_fetch_array($res);
			/* Zajisteni zobrazeni komentářů */
			$allow_comments = 1;
			echo "<h2>".$ar['poll_questions_question']."</h2><br>\n";
		} elseif ($modul == "user"){
			/* Zajisteni zobrazeni komentářů */
			$allow_comments = 1;
		} else {
			/* Zajisteni zobrazeni komentářů */
			$allow_comments = 1;
		}
		/* Pokud jsou povoleny komentare - zobrazi se */
		if ($allow_comments == 1) {
			echo "<table align=\"center\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">"; /* Zapsani poctu komentářů do logu */
			/* Zobrazi se jen pokud je nejaky komentar */
			if ($num > 0 && $ar_setup['setup_comm_anonym'] == 0 && $ar_setup['setup_comm_thumbs'] == 1){
				CommentsThumbsBestPlusMinus ($pid,$modul,$string_wrap,$td_width,$tbl_width);
			}
			if (AGet($_SESSION,'login') != ""){
				$res6 = mysql_query("SELECT admin_firstname, admin_name, admin_nick, admin_email, admin_reg_allow FROM $db_admin WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar6 = mysql_fetch_array($res6);
				/* Nastaveni zobrazovani Nicku/Jmena podle toho jestli je vyzadovan Nick pri registraci (uzivatel si toto muze prenastavit na zobrazovani nicku ve svem profilu) */
				if ($ar_setup['setup_reg_admin_nick'] == 1){$admin_nickname = $ar6['admin_nick'];} else {$admin_nickname = $ar6['admin_firstname'].' '.$ar6['admin_name'];}
				
				/* Zapsani datumu posledni navstevy do logu komentářů */
				$res7 = mysql_query("SELECT comments_log_comments FROM $db_comments_log WHERE comments_log_admin_id=".(integer)$_SESSION['loginid']." AND comments_log_item_id=".(integer)$pid." AND comments_log_modul='".mysql_real_escape_string($modul)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$num7 = mysql_num_rows($res7);
				$ar7 = mysql_fetch_array($res7);
			}
			$i = 1;
			echo "	<tr><td><h2>"._COMM_COMMENTS."</h2></td></tr>";
			$nasobek = 0;
			while ($ar = mysql_fetch_array($vysledek)){
				if ($ar_setup['setup_comm_anonym'] == 0){
					$res_thumbs = mysql_query("SELECT thumb_admin_id FROM $db_thumbs WHERE thumb_comment_id=".(integer)$ar['comment_id']."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$ar_thumbs = mysql_fetch_array($res_thumbs);
				}
				$autor = stripslashes($ar['comment_author']);
				$hlavicka = stripslashes($ar['comment_subject']);
				$email = stripslashes($ar['comment_email']);
				$text = stripslashes($ar['comment_text']);
				if ($ar['comment_reg_user_comm'] != 0){
					if ($ar['admin_contact_country'] == 0){$shortname = "00";} else {$shortname = $ar['country_shortname'];}
					if ($ar['country_shortname'] != ""){$country_short = $ar['country_shortname'];} else {$country_short = "CZ";}
				}
				$autor = wordwrap( $autor, 70, "\n<br>", 1);
				$hlavicka = wordwrap( $hlavicka, $string_wrap, "\n<br>", 1);
				$email = wordwrap( $email, 70, "\n<br>", 1);
                $text = new WrappedText($text,80,50,'<br>',1,1,'<strong><a><em><br>',1,0,'_new');
				$datum = FormatDatetime($ar['comment_date'],"d.m.Y, H:i");
				$cislo = $i;
				if ($ar_setup['setup_adds_in_com'] != 0 && ($cislo == 1	|| $nasobek == $cislo)){
					$nasobek += $ar_setup['setup_adds_in_com'];
					echo "	<tr>\n";
					echo "		<td colspan=\"2\" width=\"".$td_width."\" align=\"center\"><iframe src=\"".$eden_cfg['url_edencms']."eden_iframe.php?imode=adds&project=".$_SESSION['project']."&rid=".$ar_setup['setup_adds_in_com_id']."\" width=\"468\" height=\"60\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" noresize=\"noresize\"></iframe><br><br></td>\n";
					echo "	</tr>\n"; 
				}
				echo "	<tr "; if ($cislo % 2 == 0){echo "class=\"suda\"";} else {echo "class=\"licha\"";} /* stridani barev prispevku podle sudeho nebo licheho radku */ echo ">\n";
				echo "		<td align=\"left\" class=\"eden_comments\"><a name=\"".$cislo."\"></a>".$cislo."&nbsp;\n"; 
							if ($ar['comment_reg_user_comm'] != 0){
								if ($ar_setup['setup_comm_flag'] == 1){
									echo "			<img src=\"".$url_flags.$country_short.".gif\" width=\"18\" height=\"12\" alt=\""; 
									if ($ar['admin_contact_country'] == 0){
										echo "Wasn't Selected";
									} else {
										echo NazevVlajky($ar['country_shortname'],$_GET['lang']);
									} echo "\">&nbsp;\n";
								}
								if ($ar_setup['setup_comm_adm_img'] == 1 && $ar['admin_category_id'] != 0 && $ar['admin_cat1'] != 0){
									echo "			<img src=\"".$url_admins_category.$ar['admin_category_topicimage']."\" width=\"12\" height=\"12\" alt=\""; 
									$category_name = explode ("]", $ar['admin_category_topictext']);
									if ($category_name[1] != ""){
										echo $category_name[1];
									} else {
										echo $category_name[0];
									} echo "\">&nbsp;\n";
								}
							}
							if ($ar['comment_reg_user_comm'] != 0 && ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin")){
								echo "<a href=\"http://".$eden_cfg['misc_web']."index.php?action=user_details&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;user_id=".$ar['admin_id']."\">".$autor."</a>";
							} else {
								echo "<a href=\"mailto:".TransToASCII($email)."\">".$autor."</a>";
							}
							// Neregistrovany uzivatel
							if ($ar['comment_reg_user_comm'] == 0){
								echo " - "._ADMIN_NOT_REGISTERED;
							}
							// IP adresa
							if ($_SESSION['u_status'] == "admin"){
								echo " (".$ar['comment_ip'].")\n";
							}
							// New comments
							if (($_SESSION['u_status'] == "admin" || $_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller") && $i > $ar7['comments_log_comments']){
								echo "<span class=\"red\">NEW</span>";
							}
					echo "		</td>\n";
					echo "		<td align=\"right\" class=\"eden_comments\">";
					/****************************************************************
					*
					*	Hodnoceni komentářů - zobrazuje se jen pokud mohou komentovat registrovani a je povoleno v nastaveni
					*
					****************************************************************/
					if ($ar_setup['setup_comm_anonym'] == 0 && $ar_setup['setup_comm_thumbs'] == 1){
						echo "			<div class=\"thumbs_count_up\" id=\"thumbs_count_up".$ar['comment_id']."\">(+".$ar['comment_thumbs_up'].")</div>";
						echo "			<div id=\"thumb_buttons".$ar['comment_id']."\" "; if (!$_SESSION['loginid']){ echo " class=\"thumb_buttons\" title=\""._COMM_THUMBS_MUST_LOGIN."\"";} elseif ($ar_thumbs['thumb_admin_id'] == $_SESSION['loginid']){ echo " class=\"thumb_buttons\" title=\""._COMM_THUMBS_THUMBED."\"";} else { echo " class=\"thumb_buttons_1\"";} echo ">";
						if ($ar_thumbs['thumb_admin_id'] == $_SESSION['loginid']){
							echo "			<span class=\"thumb_up_2\">&nbsp;</span>";
	  						echo "			<span class=\"thumb_down_2\">&nbsp;</span>";
						} elseif ($_SESSION['loginid']) {
							echo "			<a href=\"javascript:;\" class=\"thumb_up\" id=\"".$ar['comment_id']."\">&nbsp;</a>";
	  						echo "			<a href=\"javascript:;\" class=\"thumb_down\" id=\"".$ar['comment_id']."\">&nbsp;</a>";
						} else {
							echo "			<span class=\"thumb_up_3\">&nbsp;</span>";
	  						echo "			<span class=\"thumb_down_3\">&nbsp;</span>";
						}
						echo "				</div>";
						echo "			<div class=\"thumbs_count_down\" id=\"thumbs_count_down".$ar['comment_id']."\">(-".$ar['comment_thumbs_down'].")</div>";
					}
 					echo "			&nbsp;".$datum."</td>\n";
					echo "	</tr>\n";
					echo "	<tr "; if ($cislo % 2 == 0){echo "class=\"suda\"";} else {echo "class=\"licha\"";} /* stridani barev prispevku podle sudeho nebo licheho radku */ echo ">\n";
					echo "		<td width=\"".$td_width."\" colspan=\"2\" class=\"eden_comments\" wrap>"; if ($hlavicka != ""){ echo "<strong>".$hlavicka."</strong><br>"; }
								$text->PrintIt(); echo "<br><br>\n";
					echo "		</td>\n";
					echo "	</tr>\n"; 
					$i++;
			}
		echo "</table>\n<br><br>";
		// Zapsani poctu komentářů do logu
		if ($_SESSION['loginid'] != ""){
			if ($num7 < 1){
				mysql_query("INSERT INTO $db_comments_log VALUES ('',".(integer)$_SESSION['loginid'].",".(integer)$pid.",".(integer)$num.",'',NOW(),'".mysql_real_escape_string($modul)."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}elseif ($num7 == 1){
				mysql_query("UPDATE $db_comments_log SET comments_log_comments=".(integer)$num.", comments_log_date=NOW(), comments_log_modul='".mysql_real_escape_string($modul)."' WHERE comments_log_admin_id=".(integer)$_SESSION['loginid']." AND comments_log_item_id=".(integer)$pid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			} else {
				$i=2;
				while ($i < $num_7){
					mysql_query("DELETE FROM $db_comments_log WHERE comments_log_admin_id=".(integer)$_SESSION['loginid']." AND comments_log_item_id=".(integer)$pid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$i++;
				}
				mysql_query("UPDATE $db_comments_log SET comments_log_comments=".(integer)$num.", comments_log_date=NOW(), comments_log_modul='".mysql_real_escape_string($modul)."' WHERE comments_log_admin_id=".(integer)$_SESSION['loginid']." AND comments_log_item_id=".(integer)$pid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}
		}
		/* Formular se zobrazi vsem pokud je zapnuto zobrazeni anonymum	*/
		if ($ar_setup['setup_comm_anonym'] == 1 || AGet($_SESSION,'login') != ""){
			echo "<a name=\"form\"></a>";
			echo "<form action=\"".$eden_cfg['url_edencms']."eden_save.php\" name=\"formular\" method=\"post\" onsubmit=\"return kontrola(this)\">";
			echo "<table width=\"".$tbl_width."\" align=\"center\" cellpadding=\"4\" cellspacing=\"0\" border=\"0\" class=\"sloupec\">\n";
			if (AGet($_GET,'cmsg') != ""){
				echo "<tr>\n";
				echo "	<td class=\"msg_err\" colspan=\"2\">"; if (AGet($_GET,'cmsg') == "bad_captcha"){echo _ERR_BAD_CAPTCHA;} echo "</td>\n";
				echo "</tr>";
			}
			/* Pokud neni uzivatel mute - zobrazi se formular */
			if($ar6['admin_reg_allow'] != 2){
				echo "	<tr>\n";
				echo "		<td valign=\"top\" colspan=\"2\">".PrepareFromDB($ar_setup['setup_lang_comments_rules']); /* Zobrazi pravidla pro komentare */ echo "<br><br></td>\n";
				echo "	</tr>"; 
				echo "	<tr>\n";
				echo "		<td>"._GUEST_NAME."</td>\n";
				echo "		<td>"; if ($ar_setup['setup_comm_autofill'] == 1 && ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin")){echo "<strong>".$admin_nickname."</strong><input type=\"hidden\" name=\"name\" value=\"".$admin_nickname."\">"; } else { echo "<input type=\"text\" name=\"name\" size=\"30\" "; if ($_GET['cmsg'] == "bad_captcha"){echo "value=\"".$_GET['n']."\" ";} echo "maxlength=\"30\">"; } echo "</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "	<td>"._GUEST_EMAIL."</td>\n";
				echo "		<td>"; if ($ar_setup['setup_comm_autofill'] == 1 && ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin")){echo "<strong>".$ar6['admin_email']."</strong><input type=\"hidden\" name=\"email\" value=\"".$ar6['admin_email']."\">"; } else { echo "<input type=\"text\" name=\"email\" size=\"30\" "; if ($_GET['cmsg'] == "bad_captcha"){echo "value=\"".$_GET['e']."\" ";} echo " maxlength=\"30\">"; } echo "</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "		<td>"._GUEST_PREDMET."<br><br></td>\n";
				echo "		<td><input type=\"text\" name=\"topic\" size=\"40\" maxlength=\"40\" "; if ($_GET['cmsg'] == "bad_captcha"){echo "value=\"".$_GET['t']."\" ";} echo "><br><br></td>\n";
				echo "	</tr>\n";
				if ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin"){
					
				} else {
					//Captcha
					echo "	<tr>\n";
					echo "		<td valign=\"top\">Captcha:<br><br></td>\n";
					echo "		<td>";
					$eden_captcha = new EdenCaptcha($eden_cfg);
					echo $eden_captcha->CaptchaShow();
					echo "		</td>\n";
					echo "	</tr>\n";
				}
				echo "	<tr>\n";
				echo "		<td colspan=\"2\">"._GUEST_COMMENTS."<br>\n";
								 if ($ar_setup['setup_comm_link'] == 1){ echo "<img alt=\""._GUEST_ADD_LINK."\" class=\"editor_v\" onmouseover=\"className='editor_o'\" onmouseout=\"className='editor_v'\" id=\"HyperLink\" onclick=\"window.open('./edencms/eden_add_link.php?project=".$_SESSION['project']."&input=comments&action=hyperlink&lang=".$_GET['lang']."&filter=".$_GET['filter']."','','menubar=no,resizable=no,toolbar=no,status=no,width=420,height=200')\" height=\"20\" src=\"".$url_eden_images."editor_hyperlink.gif\" width=\"20\"><br>"; } echo "\n";
				echo "			<textarea cols=\"40\" rows=\"7\" id=\"comments\" name=\"comments\"> "; if ($_GET['cmsg'] == "bad_captcha"){echo $_GET['n'];} echo "</textarea><br><br>\n";
				echo "			<input type=\"submit\" value=\""._CMN_SUBMIT."\" class=\"eden_button\">&nbsp;&nbsp;<input type=\"Reset\" value=\"Reset\" class=\"eden_button_reset\">\n";
				echo "		</td>\n";
				echo "	</tr>";
				if ($modul == "article"){$stranka = "index.php";}
				if ($modul == "news"){$stranka = "index.php";}
				if ($modul == "poll"){$stranka = "index.php";}
				if ($modul == "poll_users"){$stranka = "index.php";}
				if ($modul == "download"){$stranka = "index.php";}
				if ($modul == "clanwars"){$stranka = "index.php";}
				if ($modul == "user"){$stranka = "index.php";}
				echo "<input type=\"hidden\" name=\"id\" value=\"".$pid."\">\n";
				echo "<input type=\"hidden\" name=\"project\" value=\"".$project."\">\n";
				echo "<input type=\"hidden\" name=\"reg_user_comm\" value=\""; if ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin"){} else {echo "0";} echo "\">\n";
				echo "<input type=\"hidden\" name=\"modul\" value=\"".$modul."\">"; echo "\n";
				if ($modul == "download"){
					echo "<input type=\"hidden\" name=\"odkaz\" value=\"".$eden_cfg['misc_web'].$stranka."?action=files&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;modul=".$modul."&amp;page_mode=".AGet($_GET,'page_mode')."&amp;did=".$_GET['did']."&amp;project=".$project."&amp;dl_id=".$_GET['dl_id']."&amp;dl_id2=".$_GET['dl_id2']."&amp;dl_id3=".$_GET['dl_id3']."&amp;dl_id4=".$_GET['dl_id4']."&amp;dl_id5=".$_GET['dl_id5']."&amp;dl_id6=".$_GET['dl_id6']."&amp;dld=".$_GET['dld']."&amp;dld2=".$_GET['dld2']."&amp;dld3=".$_GET['dld3']."&amp;dld4=".$_GET['dld4']."&amp;dld5=".$_GET['dld5']."&amp;dld6=".$_GET['dld6']."&amp;stav=".$_GET['stav']."&amp;stav2=".$_GET['stav2']."&amp;stav3=".$_GET['stav3']."&amp;stav4=".$_GET['stav4']."&amp;stav5=".$_GET['stav5']."&amp;stav6=".$_GET['stav6']."\">\n"; 
				} elseif ($modul == "poll_users") {
					echo "<input type=\"hidden\" name=\"odkaz\" value=\"".$eden_cfg['misc_web'].$stranka."?action=users_polls&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;asid=".$_GET['asid']."&amp;status=".$_GET['status']."\">\n";
				} elseif ($modul == "user") {
					echo "<input type=\"hidden\" name=\"odkaz\" value=\"".$eden_cfg['misc_web'].$stranka."?action=player&amp;mode=player_acc&amp;id=".$pid."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">\n";
				} elseif ($modul == "clanwars") {
					echo "<input type=\"hidden\" name=\"odkaz\" value=\"".$eden_cfg['misc_web'].$stranka."?action=clanwars&amp;id_cw=".AGet($_GET,'id_cw')."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;stav=open&amp;cw_game2=".AGet($_GET,'cw_game2')."&page=".AGet($_GET,'page')."#".AGet($_GET,'id_cw')."\">\n";
				} else {
					echo "<input type=\"hidden\" name=\"odkaz\" value=\"".$eden_cfg['misc_web'].$stranka."?action=".$_GET['action']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;id=".$pid."&amp;modul=".$modul."&amp;page_mode=".AGet($_GET,'page_mode')."\">"; 
				}
				echo "<input type=\"hidden\" name=\"mode\" value=\"comments\">\n";
			}
			echo "</table>\n";
	   		echo "</form>\n";
		} else {
			echo "<table width=\"".$tbl_width."\" align=\"center\" cellpadding=\"4\" cellspacing=\"0\" border=\"0\" class=\"sloupec\">\n";
			echo "<tr>\n";
			echo "	<td colspan=\"2\"\">"._MUST_LOGIN."</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
		}
		
	}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}


function CommentsThumbsBestPlusMinus ($pid,$modul,$string_wrap=90,$td_width=400,$tbl_width=407){
	
	global $db_setup,$db_setup_lang,$db_comments,$db_admin,$db_admin_contact,$db_admin_category,$db_country,$db_thumbs;
	global $url_flags,$url_admins_category;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	/* Nacteme nastaveni */
	$res_setup = mysql_query("SELECT
	s.setup_adds_in_com,
	s.setup_adds_in_com_id,
	s.setup_comm_flag,
	s.setup_comm_adm_img,
	s.setup_comm_anonym,
	s.setup_comm_autofill,
	s.setup_comm_link,
	s.setup_reg_admin_nick,
	s.setup_show_author_nick,
	sl.setup_lang_comments_rules
	FROM $db_setup AS s, $db_setup_lang AS sl WHERE sl.setup_lang='".mysql_real_escape_string($_GET['lang'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$res_up = mysql_query("
	SELECT a.admin_id, nc.comment_id, nc.comment_author, nc.comment_subject, nc.comment_email, nc.comment_text, nc.comment_reg_user_comm, nc.comment_date, 
	INET_NTOA(nc.comment_ip) AS comment_ip, nc.comment_thumbs_up, nc.comment_thumbs_down, c.country_shortname, a.admin_cat1, ac.admin_contact_country, 
	acat.admin_category_id, acat.admin_category_topicimage, acat.admin_category_topictext 
	FROM $db_comments AS nc 
	LEFT JOIN $db_admin AS a ON a.admin_id=nc.comment_reg_user_comm 
	LEFT JOIN $db_admin_contact AS ac ON ac.aid=nc.comment_reg_user_comm 
	LEFT JOIN $db_admin_category AS acat ON acat.admin_category_id=a.admin_cat1 
	LEFT JOIN $db_country AS c ON c.country_id=ac.admin_contact_country 
	WHERE comment_show=1 AND comment_pid=".(integer)$pid." AND comment_modul='".mysql_real_escape_string($modul)."' 
	ORDER BY comment_thumbs_up DESC, comment_thumbs_down ASC, comment_id DESC 
	LIMIT 0,1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_up = mysql_fetch_array($res_up);
	
	$res_down = mysql_query("
	SELECT a.admin_id, nc.comment_id, nc.comment_author, nc.comment_subject, nc.comment_email, nc.comment_text, nc.comment_reg_user_comm, nc.comment_date, 
	INET_NTOA(nc.comment_ip) AS comment_ip, nc.comment_thumbs_up, nc.comment_thumbs_down, c.country_shortname, a.admin_cat1, ac.admin_contact_country, 
	acat.admin_category_id, acat.admin_category_topicimage, acat.admin_category_topictext 
	FROM $db_comments AS nc 
	LEFT JOIN $db_admin AS a ON a.admin_id=nc.comment_reg_user_comm 
	LEFT JOIN $db_admin_contact AS ac ON ac.aid=nc.comment_reg_user_comm 
	LEFT JOIN $db_admin_category AS acat ON acat.admin_category_id=a.admin_cat1 
	LEFT JOIN $db_country AS c ON c.country_id=ac.admin_contact_country 
	WHERE comment_show=1 AND comment_pid=".(integer)$pid." AND comment_modul='".mysql_real_escape_string($modul)."' 
	ORDER BY comment_thumbs_down DESC, comment_thumbs_up ASC, comment_id DESC 
	LIMIT 0,1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_down = mysql_fetch_array($res_down);
		
		$res_thumbs_up = mysql_query("SELECT thumb_admin_id FROM $db_thumbs WHERE thumb_comment_id=".(integer)$ar_up['comment_id']."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$res_thumbs_down = mysql_query("SELECT thumb_admin_id FROM $db_thumbs WHERE thumb_comment_id=".(integer)$ar_down['comment_id']."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_thumbs_up = mysql_fetch_array($res_thumbs_up);
		$ar_thumbs_down = mysql_fetch_array($res_thumbs_down);
		
		$autor_up = stripslashes($ar_up['comment_author']);
		$autor_down = stripslashes($ar_down['comment_author']);
		$hlavicka_up = stripslashes($ar_up['comment_subject']);
		$hlavicka_down = stripslashes($ar_down['comment_subject']);
		$email_up = stripslashes($ar_up['comment_email']);
		$email_down = stripslashes($ar_down['comment_email']);
		$text_up = stripslashes($ar_up['comment_text']);
		$text_down = stripslashes($ar_down['comment_text']);
		
		$autor_up = wordwrap( $autor_up, 70, "\n<br>", 1);
		$autor_down = wordwrap( $autor_down, 70, "\n<br>", 1);
		$hlavicka_up = wordwrap( $hlavicka_up, $string_wrap, "\n<br>", 1);
		$hlavicka_down = wordwrap( $hlavicka_down, $string_wrap, "\n<br>", 1);
		$email_up = wordwrap( $email_up, 70, "\n<br>", 1);
		$email_down = wordwrap( $email_down, 70, "\n<br>", 1);
	    $text_up = new WrappedText($text_up,80,50,'<br>',1,1,'<strong><a><em><br>',1,0,'_new');
	    $text_down = new WrappedText($text_down,80,50,'<br>',1,1,'<strong><a><em><br>',1,0,'_new');
		$datum_up = FormatDatetime($ar_up['comment_date'],"d.m.Y, H:i");
		$datum_down = FormatDatetime($ar_down['comment_date'],"d.m.Y, H:i");
		
		/* UP */
		echo "<tr><td><br><br><h2 style=\"color:#008040;\">"._COMM_THUMBS_RATED_BEST." (+".$ar_up['comment_thumbs_up'].")</h2></td></tr>";
		echo "	<tr style=\"background-color:#f3f3f3;\">\n";
		echo "		<td align=\"left\" class=\"eden_comments\">\n"; 
				if ($ar_up['comment_reg_user_comm'] != 0){
					if ($ar_setup['setup_comm_flag'] == 1){
						echo "<img src=\"".$url_flags.$ar_up['country_shortname'].".gif\" width=\"18\" height=\"12\" alt=\""; 
						if ($ar_down['admin_contact_country'] == 0){
							echo "Wasn't Selected";
						} else {
							echo NazevVlajky($ar_up['country_shortname'],$_GET['lang']);
						} echo "\">&nbsp;\n";
					}
					if ($ar_setup['setup_comm_adm_img'] == 1 && $ar_up['admin_category_id'] != 0 && $ar_up['admin_cat1'] != 0){
						echo "			<img src=\"".$url_admins_category.$ar_up['admin_category_topicimage']."\" width=\"12\" height=\"12\" alt=\""; 
						$category_name_up = explode ("]", $ar_up['admin_category_topictext']);
						if ($category_name_up[1] != ""){
							echo $category_name_up[1];
						} else {
							echo $category_name_up[0];
						} echo "\">&nbsp;\n";
					}
				}
				echo "<a href=\"http://".$eden_cfg['misc_web']."index.php?action=user_details&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;user_id=".$ar_up['admin_id']."\">".$autor_up."</a>";
				// IP adresa
				if ($_SESSION['u_status'] == "admin"){
					echo " (".$ar_up['comment_ip'].")\n";
				}
		echo "		</td>\n";
		echo "		<td align=\"right\" class=\"eden_comments\">";
		/****************************************************************
		*
		*	Hodnoceni komentářů - zobrazuje se jen pokud mohou komentovat registrovani
		*
		****************************************************************/
		echo "<div class=\"thumbs_count_up\" id=\"thumbs_count_up".$ar_up['comment_id']."\">(+".$ar_up['comment_thumbs_up'].")</div>";
		echo "<div id=\"thumb_buttons".$ar_up['comment_id']."\" "; if (!$_SESSION['loginid']){ echo " class=\"thumb_buttons\" title=\""._COMM_THUMBS_MUST_LOGIN."\"";} elseif ($ar_thumbs_up['thumb_admin_id'] == $_SESSION['loginid']){ echo " class=\"thumb_buttons\" title=\""._COMM_THUMBS_THUMBED."\"";} else { echo " class=\"thumb_buttons_1\"";} echo ">";
		if ($ar_thumbs_up['thumb_admin_id'] == $_SESSION['loginid']){
			echo "<span class=\"thumb_up_2\">&nbsp;</span>";
			echo "<span class=\"thumb_down_2\">&nbsp;</span>";
		} elseif ($_SESSION['loginid']) {
			echo "<a href=\"javascript:;\" class=\"thumb_up\" id=\"".$ar_up['comment_id']."\">&nbsp;</a>";
			echo "<a href=\"javascript:;\" class=\"thumb_down\" id=\"".$ar_up['comment_id']."\">&nbsp;</a>";
		} else {
			echo "<span class=\"thumb_up_3\">&nbsp;</span>";
			echo "<span class=\"thumb_down_3\">&nbsp;</span>";
		}
		echo "</div>";
		echo "<div class=\"thumbs_count_down\" id=\"thumbs_count_down".$ar_up['comment_id']."\">(-".$ar_up['comment_thumbs_down'].")</div>";
		echo "	&nbsp;".$datum_down."</td>\n";
		echo "</tr>\n";
		echo "<tr style=\"background-color:#f3f3f3;\">\n";
		echo "	<td width=\"".$td_width."\" colspan=\"2\" class=\"eden_comments\" wrap style=\"border-bottom: 1px #008040 solid;\">"; if ($hlavicka_up != ""){ echo "<strong>".$hlavicka_up."</strong><br>"; }
			  	$text_up->PrintIt(); echo "<br><br>\n";
		echo "	</td>\n";
		echo "</tr>\n";
		
		/* DOWN */
		echo "	<tr><td><br><br><h2 style=\"color:#ff0000;\">"._COMM_THUMBS_RATED_WORST." (-".$ar_down['comment_thumbs_down'].")</h2></td></tr>";
		echo "	<tr style=\"background-color:#f3f3f3;\">\n";
		echo "		<td align=\"left\" class=\"eden_comments\">\n"; 
				if ($ar_down['comment_reg_user_comm'] != 0){
					if ($ar_setup['setup_comm_flag'] == 1){
						echo "<img src=\"".$url_flags.$ar_down['country_shortname'].".gif\" width=\"18\" height=\"12\" alt=\""; 
						if ($ar_down['admin_contact_country'] == 0){
							echo "Wasn't Selected";
						} else {
							echo NazevVlajky($ar_down['country_shortname'],$_GET['lang']);
						} echo "\">&nbsp;\n";
					}
					if ($ar_setup['setup_comm_adm_img'] == 1 && $ar_down['admin_category_id'] != 0 && $ar_down['admin_cat1'] != 0){
						echo "			<img src=\"".$url_admins_category.$ar_down['admin_category_topicimage']."\" width=\"12\" height=\"12\" alt=\""; 
						$category_name_down = explode ("]", $ar_down['admin_category_topictext']);
						if ($category_name_down[1] != ""){
							echo $category_name_down[1];
						} else {
							echo $category_name_down[0];
						} echo "\">&nbsp;\n";
					}
				}
				echo "<a href=\"http://".$eden_cfg['misc_web']."index.php?action=user_details&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;user_id=".$ar_down['admin_id']."\">".$autor_down."</a>";
				// IP adresa
				if ($_SESSION['u_status'] == "admin"){
					echo " (".$ar_down['comment_ip'].")\n";
				}
		echo "	</td>\n";
		echo "	<td align=\"right\" class=\"eden_comments\">";
		/****************************************************************
		*
		*	Hodnoceni komentářů - zobrazuje se jen pokud mohou komentovat registrovani
		*
		****************************************************************/
		echo "<div class=\"thumbs_count_up\" id=\"thumbs_count_up".$ar_down['comment_id']."\">(+".$ar_down['comment_thumbs_up'].")</div>";
		echo "<div id=\"thumb_buttons".$ar_down['comment_id']."\" "; if (!$_SESSION['loginid']){ echo " class=\"thumb_buttons\" title=\""._COMM_THUMBS_MUST_LOGIN."\"";} elseif ($ar_thumbs_down['thumb_admin_id'] == $_SESSION['loginid']){ echo " class=\"thumb_buttons\" title=\""._COMM_THUMBS_THUMBED."\"";} else { echo " class=\"thumb_buttons_1\"";} echo ">";
		if ($ar_thumbs_down['thumb_admin_id'] == $_SESSION['loginid']){
			echo "<span class=\"thumb_up_2\">&nbsp;</span>";
			echo "<span class=\"thumb_down_2\">&nbsp;</span>";
		} elseif ($_SESSION['loginid']) {
			echo "<a href=\"javascript:;\" class=\"thumb_up\" id=\"".$ar_down['comment_id']."\">&nbsp;</a>";
			echo "<a href=\"javascript:;\" class=\"thumb_down\" id=\"".$ar_down['comment_id']."\">&nbsp;</a>";
		} else {
			echo "<span class=\"thumb_up_3\">&nbsp;</span>";
		 	echo "<span class=\"thumb_down_3\">&nbsp;</span>";
		}
		echo "</div>";
		echo "<div class=\"thumbs_count_down\" id=\"thumbs_count_down".$ar_down['comment_id']."\">(-".$ar_down['comment_thumbs_down'].")</div>";
		echo "&nbsp;".$datum_down."</td>\n";
		echo "	</tr>\n";
		echo "	<tr style=\"background-color:#f3f3f3;\">\n";
		echo "		<td width=\"".$td_width."\" colspan=\"2\" class=\"eden_comments\" wrap style=\"border-bottom: 1px #ff0000 solid;\">"; if ($hlavicka_down != ""){ echo "<strong>".$hlavicka_down."</strong><br>"; }
					$text_down->PrintIt(); echo "<br><br>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr><td><br><br>&nbsp;</td></tr>";
}