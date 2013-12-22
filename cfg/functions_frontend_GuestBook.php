<?php
/***********************************************************************************************************
*
*		GUESTBOOK
*
*		$width	=	sirka tabulky
*		$align	=	align
*
***********************************************************************************************************/
function GuestBook($gid,$td_width=400,$align="center"){
	
	global $db_admin,$db_guestbook,$db_setup;
	global $eden_cfg;
	global $project;
	
	$vysledek = mysql_query("SELECT guestbook_author, guestbook_topic, guestbook_email, guestbook_text, guestbook_date, guestbook_ip FROM $db_guestbook WHERE guestbook_nid=".(integer)$gid." ORDER BY guestbook_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_num_rows($vysledek);
	
	/* Nacteme nastaveni */
	$res_setup = mysql_query("SELECT setup_comm_anonym, setup_comm_autofill, setup_guestbook_comments FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	if ($_SESSION['login'] != ""){
		$res3 = mysql_query("SELECT admin_reg_allow, admin_nick, admin_email FROM $db_admin WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar3 = mysql_fetch_array($res3);
	}
	/* Formular se zobrazi vsem pokud je zapnuto zobrazeni anonymum	*/
	if ($ar_setup['setup_comm_anonym'] == 1 || $_SESSION['login'] != ""){
		/* Pokud neni uzivatel mute - zobrazi se formular */
		if($ar3['admin_reg_allow'] != 2){
			echo "<a name=\"form\"></a>";
			echo "<form action=\"".$eden_cfg['url_edencms']."eden_save.php\" name=\"formular\" method=\"post\" onsubmit=\"return kontrola(this)\">";
			echo "<table width=\"".$td_width."\" align=\"".$align."\" cellpadding=\"4\" cellspacing=\"0\" border=\"0\" class=\"sloupec\">";
			if (AGet($_GET,'cmsg') != ""){
				echo "<tr>\n";
				echo "	<td class=\"msg_err\" colspan=\"2\">"; if (AGet($_GET,'cmsg') == "bad_captcha"){echo _ERR_BAD_CAPTCHA;} echo "</td>\n";
				echo "</tr>";
			}
			echo "<tr>\n";
			echo "	<td>"._GUEST_NAME."</td>\n";
			echo "	<td>"; if ($ar_setup['setup_comm_autofill'] == 1 && ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin")){echo "<strong>".$ar3['admin_nick']."</strong><input type=\"hidden\" name=\"name\" value=\"".$ar3['admin_nick']."\">"; } else { echo "<input type=\"text\" name=\"name\" size=\"30\" maxlength=\"30\" "; if ($_GET['msg'] == "bad_captcha"){echo "value=\"".$_GET['n']."\" ";} echo ">"; } echo "</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td>"._GUEST_EMAIL."</td>\n";
			echo "	<td>"; if ($ar_setup['setup_comm_autofill'] == 1 && ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin")){echo "<strong>".$ar3['admin_email']."</strong><input type=\"hidden\" name=\"email\" value=\"".$ar3['admin_email']."\">"; } else { echo "<input type=\"text\" name=\"email\" size=\"30\" maxlength=\"30\" "; if ($_GET['msg'] == "bad_captcha"){echo "value=\"".$_GET['e']."\" ";} echo ">"; } echo "</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td>"._GUEST_PREDMET."<br><br></td>\n";
			echo "	<td><input type=\"text\" name=\"topic\" size=\"40\" maxlength=\"40\" "; if ($_GET['msg'] == "bad_captcha"){echo "value=\"".$_GET['t']."\" ";} echo "><br><br></td>\n";
			echo "</tr>\n";
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
			echo "		<tr>\n";
			echo "			<td colspan=\"2\">"._GUEST_COMMENTS."<br>\n";
			echo "				<textarea cols=\"40\" rows=\"7\" name=\"comments\">"; if ($_GET['msg'] == "bad_captcha"){echo $_GET['c'];} echo "</textarea><br><br>\n";
			echo "				<input type=\"submit\" value=\""._CMN_SUBMIT."\" class=\"eden_button\">&nbsp;&nbsp;<input type=\"Reset\" value=\"Reset\" class=\"eden_button_reset\">\n";
			echo "			</td>\n";
			echo "		</tr>\n";
			echo "		<input type=\"hidden\" name=\"id\" value=\"".$gid."\">\n";
			echo "		<input type=\"hidden\" name=\"width\" value=\"".$td_width."\">\n";
			echo "		<input type=\"hidden\" name=\"align\" value=\"".$align."\">\n";
			echo "		<input type=\"hidden\" name=\"project\" value=\"".$project."\">\n";
			echo "		<input type=\"hidden\" name=\"ip\" value=\"".$eden_cfg['ip']."\">\n";
			echo "		<input type=\"hidden\" name=\"odkaz\" value=\"".$eden_cfg['misc_web']."index.php?action=".$_GET['action']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;gid=".$gid."\">\n";
			echo "		<input type=\"hidden\" name=\"mode\" value=\""; if ($_SESSION['project'] != ""){echo "secretgb";} else {echo "guestbook";} echo "\">\n";
			echo "</table>\n";
			echo "</form>";
		}
	} else {
		echo "<table width=\"".$td_width."\" align=\"".$align."\" cellpadding=\"4\" cellspacing=\"0\" border=\"0\" class=\"sloupec\">";
		echo "		<tr>\n";
		echo "			<td colspan=\"2\"><br><br>"._MUST_LOGIN."<br><br><br></td>\n";
		echo "		</tr>";
		echo "</table>\n";
	}
	echo "<table width=\"".$td_width."\" align=\"".$align."\" cellpadding=\"3\">\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<table width=\"".$td_width."\" align=\"".$align."\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">";
	$hits = $ar_setup['setup_guestbook_comments']; // Nastaveni poctu radku na strankach
	$m = 0;// nastaveni iterace
	if (empty($_GET['page'])) {$_GET['page'] = 1;} // i kdyz je promenna $_GET['page'] prazdna nastavi se pocet starnek na 1
	if ($hits == 0){$hits = 30;}
	//$hits=3; //Zde se nastavuje pocet prispevku
	$stw2 = ($num/$hits);
	$stw2 = (integer) $stw2;
	if ($num%$hits > 0) {$stw2++;}
	$np = $_GET['page'] + 1;
	$pp = $_GET['page'] - 1;
	if ($_GET['page'] == 1) { $pp = 1; }
	if ($np > $stw2) { $np = $stw2;}
	
	$sp = ($_GET['page'] - 1)*$hits;
	$ep = ($_GET['page'] - 1)*$hits+$hits;
	
	//Jelikoz se prispevky zobrazuji v opacnem poradi musi byt i pocitani opacne
	if ($_GET['page'] == 1){$cislo_prispevku = $num;} else {$cislo_prispevku = ($num - ($hits * $_GET['page'] - $hits));}
	$i=1;
	while ($ar = mysql_fetch_array($vysledek)){
		$m++;
		if ($m>$sp and $m<=$ep){ //Spravne nastaveni poctu zobrazeni na strance
			$autor = stripslashes($ar['guestbook_author']);
			$hlavicka = stripslashes($ar['guestbook_topic']);
			$email = stripslashes($ar['guestbook_email']);
			$text = stripslashes($ar['guestbook_text']);
			$autor = wordwrap( $autor, 50, "\n", 1);
			$hlavicka = wordwrap( $hlavicka, 50, "\n", 1);
			$email = wordwrap( $email, 50, "\n", 1);
			$text = new WrappedText($text,50,50,'<br>',1,1,'<strong><a><em><br>',1,0,'_new');
			//$text = wordwrap( $text, 50, "\n", 1);
			$datum = FormatTimestamp($ar['guestbook_date'],"d.m.Y, H:i");
			$cislo = $i;
			echo "<tr "; if ($cislo % 2 == 0){echo "class=\"suda\"";} /* stridani barev prispevku podle sudeho nebo licheho radku*/ echo ">";
			echo "			<td align=\"left\">".$cislo_prispevku.".&nbsp;<a href=\"mailto:".TransToASCII($email)."\">".$autor."</a> "; if ($_SESSION['login'] != ""){echo "(".$ar['guestbook_ip'].")";} echo "</td>";
			echo "			<td align=\"right\">".$datum."</td>";
			echo "		</tr>";
			echo "		<tr "; if ($cislo % 2 == 0){echo "class=\"suda\"";} /* stridani barev prispevku podle sudeho nebo licheho radku */ echo ">";
			echo "			<td width=\"".$td_width."\" colspan=\"2\">"; if ($hlavicka != ""){ echo "<strong>".$hlavicka."</strong><br>"; }
							$text->PrintIt(); echo "<br><br>";
			echo "			</td>";
			echo "		</tr>";
			$cislo_prispevku = $cislo_prispevku - 1;
		}
		$i++;
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
			if ($_GET['page'] == $i) {
				echo " <strong>".$i."</strong> ";
			} else {
				echo " <a href=\"?action=".$_GET['action']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;page=".$i."&amp;gid=".$gid."\">".$i."</a> ";
			}
		}
		//Zobrazeni sipek s predchozimi a dalsimi strankami novinek
		if ($_GET['page'] > 1){echo "<br><a href=\"?action=".$_GET['action']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;page=".$pp."&amp;gid=".$gid."\">"._CMN_PREVIOUS."</a>";} else {echo "<br>"._CMN_PREVIOUS;} echo " <--|--> "; if ($_GET['page'] == $stw2){echo _CMN_NEXT;} else {echo "<a href=\"?action=".$_GET['action']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;page=".$np."&amp;gid=".$gid."\">"._CMN_NEXT."</a>";}
		echo "	</td>";
		echo "</tr>";
	}
	echo "			</table><br><br>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
}
/***********************************************************************************************************
*
*		GUESTBOOK PRO ADMINY
*
*		$width	=	sirka tabulky
*		$align	=	align
*
***********************************************************************************************************/
function GuestBookAdmin($gid,$td_width=400,$align="center"){
	
	global $db_guestbook_admin,$db_setup,$db_admin,$db_admin_contact,$db_admin_category,$db_country;
	global $url_flags,$url_admins_category;
	global $eden_cfg;
	global $project;
	
	$res_ga = mysql_query("SELECT guestbook_admin_author_id, guestbook_admin_topic, guestbook_admin_text, guestbook_admin_date FROM $db_guestbook_admin WHERE guestbook_admin_admin_id=".(integer)$gid." ORDER BY guestbook_admin_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num_ga = mysql_num_rows($res_ga);
	
	// Nacteme nastaveni
	$res_setup = mysql_query("SELECT setup_guestbook_comments, setup_comm_flag, setup_comm_adm_img FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	$res3 = mysql_query("SELECT admin_nick, admin_reg_allow FROM $db_admin WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar3 = mysql_fetch_array($res3);
	echo "<table width=\"".$td_width."\" align=\"".$align."\" cellpadding=\"4\" cellspacing=\"0\" border=\"0\" class=\"sloupec\">";
	/* Pokud neni uzivatel mute - zobrazi se formular */
	if($ar3['admin_reg_allow'] != 2){
		echo "<form action=\"".$eden_cfg['url_edencms']."eden_save.php\" name=\"formular\" method=\"post\" onsubmit=\"return kontrola(this)\">";
		echo "		<tr>\n";
		echo "			<td>"._GUEST_NAME."</td>\n";
		echo "			<td><strong>".$ar3['admin_nick']."</strong><input type=\"hidden\" name=\"jmeno\" value=\"".$ar3['admin_nick']."\"></td>\n";
		echo "		</tr>\n";
		echo "		<tr>\n";
		echo "			<td>"._GUEST_PREDMET."<br><br></td>\n";
		echo "			<td><input type=\"text\" name=\"topic\" size=\"40\" maxlength=\"40\"><br><br></td>\n";
		echo "		</tr>\n";
		echo "		<tr>\n";
		echo "			<td colspan=\"2\"\">"._GUEST_COMMENTS."<br>\n";
		echo "				<textarea cols=\"40\" rows=\"7\" name=\"comments\"></textarea><br><br>\n";
		echo "				<input type=\"submit\" value=\""._CMN_SUBMIT."\" class=\"eden_button\">&nbsp;&nbsp;<input type=\"Reset\" value=\"Reset\" class=\"eden_button_reset\">\n";
		echo "			</td>\n";
		echo "		</tr>\n";
		echo "		<input type=\"hidden\" name=\"id\" value=\"".$gid."\">\n";
		echo "		<input type=\"hidden\" name=\"width\" value=\"".$td_width."\">\n";
		echo "		<input type=\"hidden\" name=\"align\" value=\"".$align."\">\n";
		echo "		<input type=\"hidden\" name=\"project\" value=\"".$project."\">\n";
		echo "		<input type=\"hidden\" name=\"odkaz\" value=\"".$eden_cfg['misc_web']."index.php?action=user_details&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;user_id=".$gid."&amp;page_mode=".AGet($_GET,'page_mode')."\">\n";
		echo "		<input type=\"hidden\" name=\"mode\" value=\"admin_gb\">\n";
		echo "		</form>\n";
	}
	echo "</table>\n";
	echo "<table width=\"".$td_width."\" align=\"".$align."\" cellpadding=\"3\"\">\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<table width=\"".$td_width."\" align=\"".$align."\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">";
					$hits = $ar_setup['setup_guestbook_comments']; // Nastaveni poctu radku na strankach
					$m=0;// nastaveni iterace
					if (empty($_GET['page'])) {$_GET['page'] = 1;} // i kdyz je promenna $_GET['page'] prazdna nastavi se pocet starnek na 1
					if ($hits == 0){$hits = 30;}
					//$hits=3; //Zde se nastavuje pocet prispevku
					$stw2 = ($num_ga/$hits);
					$stw2 = (integer) $stw2;
					if ($num_ga%$hits > 0) {$stw2++;}
					$np = $_GET['page'] + 1;
					$pp = $_GET['page'] - 1;
					if ($_GET['page'] == 1) { $pp=1; }
					if ($np > $stw2) { $np = $stw2;}
					
					$sp=($_GET['page']-1)*$hits;
					$ep=($_GET['page']-1)*$hits+$hits;
					
					//Jelikoz se prispevky zobrazuji v opacnem poradi musi byt i pocitani opacne
					if ($_GET['page'] == 1){$cislo_prispevku = $num_ga;} else {$cislo_prispevku = ($num_ga - ($hits*$_GET['page']-$hits));}
					
					$i=1;
					while ($ar_ga = mysql_fetch_array($res_ga)){
						$m++;
						if ($m>$sp and $m<=$ep){ //Spravne nastaveni poctu zobrazeni na strance
							$res_admins = mysql_query("SELECT admin_nick, admin_id FROM $db_admin WHERE admin_id=".$ar_ga['guestbook_admin_author_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$ar_admins = mysql_fetch_array($res_admins);
							$author = stripslashes($ar_admins['admin_nick']);
							$hlavicka = stripslashes($ar_ga['guestbook_admin_topic']);
							$text = stripslashes($ar_ga['guestbook_admin_text']);
							$hlavicka = wordwrap( $hlavicka, 50, "\n", 1);
							$text = new WrappedText($text,50,50,'<br>',1,1,'<strong><a><em><br>',1,0,'_new');
							//$text = wordwrap( $text, 50, "\n", 1);
							$datum = FormatDatetime($ar_ga['guestbook_admin_date'],"d.m.Y, H:i");
							$cislo = $i;
							
							$res4 = mysql_query("SELECT
							a.admin_cat1,
							ac.admin_contact_country,
							c.country_shortname
							FROM $db_admin AS a, $db_admin_contact AS ac, $db_country AS c WHERE a.admin_id=".(integer)$ar_admins['admin_id']." AND ac.aid=a.admin_id AND ac.admin_contact_country=c.country_id") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$ar4 = mysql_fetch_array($res4);
							if ($ar4['admin_contact_country'] == 0){$shortname = "00";} else {$shortname = $ar4['country_shortname'];}
							if ($ar4['country_shortname'] != ""){$country_short = $ar4['country_shortname'];} else {$country_short = "CZ";}
							$res5 = mysql_query("SELECT admin_category_id, admin_category_topicimage, admin_category_topictext FROM $db_admin_category WHERE admin_category_id=".(integer)$ar4['admin_cat1']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$ar5 = mysql_fetch_array($res5);
							
							echo "<tr "; if ($cislo % 2 == 0){echo "class=\"suda\"";}	/* stridani barev prispevku podle sudeho nebo licheho radku */ echo ">";
							echo "	<td align=\"left\">".$cislo_prispevku.".&nbsp;"; 
							if ($ar_setup['setup_comm_flag'] == 1){
								echo "<img src=\"".$url_flags.$country_short.".gif\" width=\"18\" height=\"12\" alt=\""; 
								if ($ar4['admin_contact_country'] == 0){
									echo _GUESTBOOK_FLAG_NO_SELECTED;
								} else {
									echo NazevVlajky($ar4['country_shortname'],$_GET['lang']);
								}
								echo"\">&nbsp;";
							}
							if ($ar_setup['setup_comm_adm_img'] == 1 && $ar5['admin_category_id'] != 0){
								echo "<img src=\"".$url_admins_category.$ar5['admin_category_topicimage']."\" width=\"12\" height=\"12\" alt=\"";
								$category_name = explode ("]", $ar5['admin_category_topictext']);
								if ($category_name[1] != ""){
									echo $category_name[1];
								} else {
									echo $category_name[0];
								}
								echo "\">&nbsp;";
							}
							echo "<a href=\"index.php?action=user_details&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;user_id=".$ar_admins['admin_id']."&amp;page_mode=\">".$author."</a></td>";
							echo "	<td align=\"right\">".$datum."</td>";
							echo "</tr>";
							echo "<tr "; if ($cislo % 2 == 0){echo "class=\"suda\"";} /* stridani barev prispevku podle sudeho nebo licheho radku */ echo ">";
							echo "	<td width=\"".$td_width."\" colspan=\"2\">"; if ($hlavicka != ""){ echo "<strong>".$hlavicka."</strong><br>"; }
							$text->PrintIt(); echo "<br><br>";
							echo "	</td>";
							echo "</tr>";
							$cislo_prispevku = $cislo_prispevku - 1;
						}
						$i++;
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
								echo " <a href=\"index.php?action=".$_POST['action']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;page=".$i."&amp;gid=".$gid."\">".$i."</a> ";
							}
						}
						//Zobrazeni sipek s predchozimi a dalsimi strankami novinek
						if ($_GET['page'] > 1){echo "<br><a href=\"index.php?action=".$_POST['action']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;page=".$pp."&amp;gid=".$gid."\">"._CMN_PREVIOUS."</a>";} else {echo "<br>"._CMN_PREVIOUS;} echo " <--|--> "; if ($_GET['page'] == $stw2){echo _CMN_NEXT;} else {echo "<a href=\"index.php?action=".$_POST['action']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;page=".$np."&amp;gid=".$gid."\">"._CMN_NEXT."</a>";}
						echo "	</td>";
						echo "</tr>";
					}
	echo "			</table><br><br>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
}