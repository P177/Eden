<?php
//********************************************************************************************************
//
//             ZOBRAZENI SEZNAMU ZAPASU
//
//********************************************************************************************************
function ShowMain(){

	global $eden_cfg;
	global $db_guestbook,$db_guestbook_topic,$db_setup,$action,$page,$id,$jmeno,$email,$topic,$comments,$del_comm,$cislo_prispevku;;

		// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	$res = mysql_query("SELECT * FROM $db_guestbook_topic ORDER BY guestbook_topic_topic ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());

	if ($action == "save"){
		// Výčet povolených tagů
		$allowtags = "<embed>, <marquee>, <blink>, <hr>, <ul>, <li>, <ol>, <p>, <br>, <font>, <b>, <u>, <i>, <small>, <big>, <strong>, <em>, <a>, <img>";
		// Z obsahu proměnné body vyjmout nepovolené tagy
		$jmeno = strip_tags($jmeno,$allowtags);
		$email = strip_tags($email,$allowtags);
		$topic = strip_tags($topic,$allowtags);
		$comments = strip_tags($comments,$allowtags);
		// Aby se minimalizovalo znovuulozeni stejneho zaznamu do databaze po refreshi je treba zjistit prispevek s nejvyssim cislem

		$vysledek = mysql_query("SELECT MAX(guestbook_id) FROM $db_guestbook WHERE guestbook_nid = ".(float)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($vysledek);
		// Zjistene cislo se vlozi do dotazu
		$vysledek2 = mysql_query("SELECT * FROM $db_guestbook WHERE guestbook_nid=".(float)$id." AND guestbook_id=".(float)$ar[0]) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar2 = mysql_fetch_array($vysledek2);
		$datum = date("YmdHis");
		// Pokud neni text shodny tak se vse ulozi (kontrola nefunguje pokud uz nekdo neco vlozil)
		$vysledek3 = mysql_query("INSERT INTO $db_guestbook VALUES('','".(float)$id."','".mysql_real_escape_string($jmeno)."','".mysql_real_escape_string($email)."','".(float)$datum."','".mysql_real_escape_string($topic)."','".mysql_real_escape_string($comments)."','".mysql_real_escape_string($eden_cfg['ip'])."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		unset($jmeno,$email,$topic,$comments);
		$action = "open";
	}
	if ($action == "del_selected"){
		$num_del = count($del_comm);
		$i = 0;
		while($num_del > $i){
			$vysledek3 = mysql_query("DELETE FROM $db_guestbook WHERE guestbook_id=".(float)$del_comm[$i]) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$i++;
		}
		$action = "open";
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td align="left" class="nadpis"><?php echo _GUEST_GUESTBOOK;?></td>
		</tr>
		<tr>
			<td><img src="images/sys_manage.gif" height="18" width="18" border="0" alt="">
				<a href="?action=add&amp;project=<?php echo $_SESSION['project'];?>"><?php echo _GUEST_ADD_TOPIC;?></a></td>
		</tr>
	</table>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr class="popisky">
			<td width="80" align="center"><span class="nadpis-boxy"><?php echo _CMN_OPTIONS;?></span></td>
			<td width="30" align="center"><span class="nadpis-boxy"><?php echo _GUEST_ID;?></span></td>
			<td width="490" align="left"><span class="nadpis-boxy"><?php echo _GUEST_NAME;?></span></td>
			<td width="100" align="left"><span class="nadpis-boxy"><?php echo _GUEST_AUTHOR;?></span></td>
			<td width="50" align="left"><span class="nadpis-boxy"><?php echo _GUEST_LANG;?></span></td>
			<td width="50" align="left"><span class="nadpis-boxy"><?php echo _GUEST_COUNT_COMM;?></span></td>
		</tr>
<?php 	$cislo=1;
		while($ar = mysql_fetch_array($res)){
			$datum = FormatTimestamp($ar[guestbook_topic_date]);
			// Nacteme do pole vsecny prispevky pro dane ID
			$res2 = mysql_query("SELECT * FROM $db_guestbook WHERE guestbook_nid=".(float)$ar['guestbook_topic_id']." ORDER BY guestbook_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$num2 = mysql_num_rows($res2);
			if ($cislo % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
			echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
			echo "	<td width=\"80\" align=\"center\">";
 					if (CheckPriv("groups_guestbook_edit") == 1){echo '<a href="?action=edit&amp;id='.$ar[guestbook_topic_id].'&amp;project='.$_SESSION[project].'"><img src="images/sys_edit.gif" height="18" width="18" border="0" alt="'._CMN_EDIT.'"></a>';}
					if (CheckPriv("groups_guestbook_del") == 1){echo ' <a href="?action=delete&amp;id='.$ar[guestbook_topic_id].'&amp;project='.$_SESSION[project].'"><img src="images/sys_del.gif" height="18" width="18" border="0" alt="'._CMN_DEL.'"></a>';}
?>				</td>
				<td width="30" align="center"><?php echo $ar[guestbook_topic_id];?></td>
				<td width="490" align="left"><a href="?action=<?php if ($action == "open"){echo "close";} else {echo "open";}?>&amp;id=<?php echo $ar[guestbook_topic_id];?>&amp;project=<?php echo $_SESSION[project]?>" target="_self"><?php echo $ar[guestbook_topic_topic];?></a></td>
				<td width="100" align="left"><?php echo $ar[guestbook_topic_author];?></td>
				<td width="50" align="left"><?php echo $ar[guestbook_topic_lang];?></td>
				<td width="50" align="left"><?php echo $num2;?></td>
			</tr>
<?php 			if ($action == "open" && $ar[guestbook_topic_id] == $id){?>
			<tr>
				<td colspan="6">
					<form action="modul_guestbook.php" name="formular" method="post">
					<table width="830" border="0" cellspacing="0" cellpadding="0" bgcolor="#c0c0c0">
						<tr>
							<td width="80" align="center"><br>&nbsp;<form action="modul_guestbook.php" name="formular" method="post"></td>
							<td><br><?php echo _GUEST_AUTHOR;?></td>
							<td colspan="2"><br><input type="text" name="jmeno" size="30" maxlength="80"></td>
						</tr>
						<tr>
							<td width="80" align="center">&nbsp;</td>
							<td><?php echo _GUEST_EMAIL;?></td>
							<td colspan="2"><input type="text" name="email" size="30" maxlength="30"></td>
						</tr>
						<tr>
							<td width="80" align="center">&nbsp;</td>
							<td><?php echo _GUEST_PREDMET;?><br><br></td>
							<td colspan="2"><input type="text" name="topic" size="40" maxlength="40"><br><br></td>
						</tr>
						<tr>
							<td width="80" align="center">&nbsp;</td>
							<td colspan="3"><?php echo _GUEST_COMMENTS;?><br>
								<textarea cols="40" rows="7" name="comments"></textarea><br><br>
								<input type="submit" value="<?php echo  _CMN_SUBMIT;?>" class="eden_button">&nbsp;&nbsp;<input type="Reset" value="Reset" class="button"><br><br>
								<input type="hidden" name="id" value="<?php echo $id;?>">
								<input type="hidden" name="action" value="save">
								<input type="hidden" name="project" value="<?php echo $_SESSION['project'];?>">
								</form>
							</td>
						</tr>
						<tr>
							<td align="center" colspan="4"><hr width="800" size="1" noshade></td>
						</tr>
						<form action="modul_guestbook.php" name="formular" method="post">
<?php 						// Nacteme nastaveni
						$res3 = mysql_query("SELECT * FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar3 = mysql_fetch_array($res3);
						$hits = $ar3['setup_guestbook_comments']; // Nastaveni poctu radku na strankach
						$m=0;// nastaveni iterace
						if (empty($page)) {$page=1;} // i kdyz je promenna $page prazdna nastavi se pocet starnek na 1
						if ($hits == 0){$hits = 30;}
						//$hits=3; //Zde se nastavuje pocet prispevku
						$stw2 = ($num/$hits);
						$stw2 = (integer) $stw2;
						if ($num%$hits > 0) {$stw2++;}
						$np = $page+1;
						$pp = $page-1;
						if ($page == 1) { $pp=1; }
						if ($np > $stw2) { $np = $stw2;}

						$sp=($page-1)*$hits;
						$ep=($page-1)*$hits+$hits;

						//Jelikoz se prispevky zobrazuji v opacnem poradi musi byt i pocitani opacne
						$cislo_prispevku = $num2;

						$i=1;
						while ($ar2 = mysql_fetch_array($res2)){
							$m++;
							if ($m>$sp and $m<=$ep){ //Spravne nastaveni poctu zobrazeni na strance
								$autor = stripslashes($ar2[guestbook_author]);
								$hlavicka = stripslashes($ar2[guestbook_topic]);
								$email = stripslashes($ar2[guestbook_email]);
								$text = stripslashes($ar2[guestbook_text]);
								$autor = wordwrap( $autor, 100, "\n", 1);
								$hlavicka = wordwrap( $hlavicka, 100, "\n", 1);
								$email = wordwrap( $email, 100, "\n", 1);
								$text = wordwrap( $text, 100, "\n", 1);
								$datum = FormatTimestamp($ar2[guestbook_date],"d.m.Y, H:i");
								$cislo = $i;?>
								<tr <?php if ($cislo % 2 == 0){echo 'class="suda"';}// stridani barev prispevku podle sudeho nebo licheho radku?>>
									<td width="80" align="center"><?php if (CheckPriv("groups_guestbook_del_comm") == 1){?><input type="checkbox" name="del_comm[]" value="<?php echo $ar2[guestbook_id];?>"><?php }?></td>
									<td width="70" align="left"><STRONG><?php echo _GUEST_PREDMET;?> </STRONG></td>
									<td width="620" align="left"><STRONG><?php echo $hlavicka;?></STRONG></td>
									<td width="120" align="left">#<?php echo $cislo_prispevku;;?></td>
								</tr>
								<tr <?php if ($cislo % 2 == 0){echo 'class="suda"';}// stridani barev prispevku podle sudeho nebo licheho radku?>>
									<td width="80" align="center">&nbsp;</td>
									<td width="70" align="left"><STRONG><?php echo _GUEST_AUTHOR;?>: </STRONG></td>
									<td width="620" align="left"><STRONG><?php echo $autor;echo " (".$ar2[guestbook_ip].")";?></STRONG></td>
									<td width="120" align="left"><?php echo $datum;?></td>
								</tr>
								<tr <?php if ($cislo % 2 == 0){echo 'class="suda"';}// stridani barev prispevku podle sudeho nebo licheho radku?>>
									<td width="80" align="center">&nbsp;</td>
									<td colspan="3">
										<?php echo $text;?><br><br>
									</td>
								</tr>
<?php 								$cislo_prispevku = $cislo_prispevku - 1;
							}
							$i++;
						}?>
						<tr <?php if ($cislo % 2 == 0){echo 'class="suda"';}// stridani barev prispevku podle sudeho nebo licheho radku?>>
							<td width="80" align="center">&nbsp;</td>
							<td colspan="3"><?php if (CheckPriv("groups_guestbook_del_comm") == 1){?><input type="submit" value="<?php echo _GUEST_DEL_SELECTED;?>" class="eden_button">
							<input type="hidden" name="action" value="del_selected">
							<input type="hidden" name="project" value="<?php echo $_SESSION['project'];?>">
							</form><?php }?><br><br></td>
						</tr>
<?php 				//***********************************************
				//     Pocitani stranek - Guestbook
				//***********************************************
				// Pokud je komentářů vice, nezli se vejde na 1 stranku zobrazi se jejich pocitani a prechazeni mezi nima
				if ($stw2 > 1){
					echo '<tr>
							<td align="left" valign="top" colspan="3">';
						//Zobrazeni cisla poctu stranek
					for ($i=1;$i<=$stw2;$i++) {
						if ($page==$i) {
							echo ' <strong>'.$i.'</strong> ';
						} elseif ($i==1 || $i==($page-4) || $i==($page-3)|| $i==($page-2) || $i==($page-1) || $i==($page+1) || $i==($page+2) || $i==($page+3) || $i==($page+4) || $i==$stw2) {
							echo ' <a href="'.$PHP_SELF.'?lang='.$lang.'&amp;page='.$i.'&amp;action='.$action.'&amp;id='.$id.'">'.$i.'</a> ';
						}
					}
					//Zobrazeni sipek s predchozimi a dalsimi strankami novinek
					echo '<br><a href="'.$PHP_SELF.'?lang='.$lang.'&amp;page='.$pp.'&amp;action='.$action.'&amp;id='.$id.'">'._CMN_PREVIOUS.'</a> <--|--> <a href="'.$PHP_SELF.'?lang='.$lang.'&amp;page='.$np.'&amp;action='.$action.'&amp;id='.$id.'">'._CMN_NEXT.'</a>';
					echo'</td></tr>';
				}
			echo "	</table>";
 			}
		$cislo++;
	}
	echo "</table>";
}
//********************************************************************************************************
//
//             ZOBRAZENI SEZNAMU KATEGORII
//
//********************************************************************************************************
function Add(){

	global $id,$action,$confirm;
	global $db_guestbook_topic,$db_admin,$nid,$topic,$popis,$author,$language;

	// Provereni opravneni
	if ($action == "add"){
		if (CheckPriv("groups_clanwars_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($action == "edit"){
		if (CheckPriv("groups_clanwars_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	} else {
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}

	if ($confirm == "true"){
		// Výčet povolených tagů
		$allowtags = "<br>,<strong>,<em>";
		// Z obsahu proměnné body vyjmout nepovolené tagy
		$topic = strip_tags($topic,$allowtags);
		$popis = strip_tags($popis,$allowtags);
		// Nacteni autora
		$res2 = mysql_query("SELECT * FROM $db_admin WHERE admin_uname='".mysql_real_escape_string($_SESSION['login'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar2 = mysql_fetch_array($res2);
		$datum = date("YmdHis");
		if ($action == "add"){
			mysql_query("INSERT INTO $db_guestbook_topic VALUES('','','".mysql_real_escape_string($topic)."','".mysql_real_escape_string($popis)."','".(float)$ar2['admin_id']."','".(float)$datum."','".mysql_real_escape_string($language)."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			ShowMain();
		}
		if ($action == "edit"){
			mysql_query("UPDATE $db_guestbook_topic SET guestbook_topic_topic='".mysql_real_escape_string($topic)."', guestbook_topic_description='".mysql_real_escape_string($popis)."', guestbook_topic_lang='".mysql_real_escape_string($language)."' WHERE guestbook_topic_id=".(float)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			ShowMain();
		}
	}

	if ($action == "edit"){
		$res = mysql_query("SELECT * FROM $db_guestbook_topic WHERE guestbook_topic_id=".(float)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	if ($confirm <> "true"){echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td align="left" class="nadpis"><?php echo _GUEST_GUESTBOOK." - "; if ($action == "edit"){echo _CMN_EDIT;} else {echo _ADD;}?></td>
		</tr>
		<tr>
			<td><img src="images/sys_manage.gif" height="18" width="18" border="0" alt="">
				<a href="?action=showmain&amp;project=<?php echo $_SESSION['project'];?>"><?php echo _GUEST_MAIN;?></a></td>
		</tr>
	</table>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td colspan="2"><form action="modul_guestbook.php" method="post" name="forma"></td>
		</tr>
		<tr>
			<td align="right" valign="top" width="200"><strong><?php echo _GUEST_TOPIC;?></strong></td>
			<td align="left" valign="top"><input type="text" name="topic" value="<?php echo $ar[guestbook_topic_topic];?>" size="50"  maxlength="80"></td>
		</tr>
		<tr>
			<td align="right" valign="top" width="200"><strong><?php echo _GUEST_POPIS;?></strong></td>
			<td align="left" valign="top"><textarea cols="50" rows="5" name="popis"><?php echo $ar[guestbook_topic_description];?></textarea></td>
		</tr>
		<tr>
			<td align="right" valign="top" width="200"><strong><?php echo _GUEST_LANG;?></strong></td>
			<td align="left" valign="top">
				<select name="language">
						<option name="language" value="cs" <?php if ($ar[guestbook_topic_language] == "cs"){echo "selected=\"selected\"";}?>>cs</option>
						<option name="language" value="de" <?php if ($ar[guestbook_topic_language] == "de"){echo "selected=\"selected\"";}?>>de</option>
						<option name="language" value="en" <?php if ($ar[guestbook_topic_language] == "en"){echo "selected=\"selected\"";}?>>en</option>
						<option name="language" value="pl" <?php if ($ar[guestbook_topic_language] == "pl"){echo "selected=\"selected\"";}?>>pl</option>
					</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="hidden" name="id" value="<?php echo $id;?>">
				<input type="hidden" name="project" value="<?php echo $_SESSION['project'];?>">
				<input type="hidden" name="action" value="<?php if ($action == "edit"){echo "edit";} else {echo "add";}?>">
				<input type="hidden" name="confirm" value="true">
				<input type="submit" value="<?php echo  _CMN_SUBMIT;?>" class="eden_button">
				</form>
			</td>
		</tr>
	</table>
<?php 	}
}
//********************************************************************************************************
//
//             ZOBRAZENI SEZNAMU KATEGORII
//
//********************************************************************************************************
function Del(){

	global $id,$db_guestbook_topic,$db_guestbook,$confirm;

	$res = mysql_query("SELECT * FROM $db_guestbook_topic WHERE guestbook_topic_id=".(float)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);

	// CHECK PRIVILEGIES
	if (CheckPriv("groups_clanwars_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}

	if ($confirm == "true") {
		$res = mysql_query("DELETE FROM $db_guestbook_topic WHERE guestbook_topic_id=".(float)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		ShowMain();
	}
	if ($confirm == "false"){ShowMain();}
	if ($confirm <> "true" && $confirm <> "false"){
echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td align="left" class="nadpis"><?php echo _GUEST_GUESTBOOK." - "._DEL;?></td>
		</tr>
		<tr>
			<td><img src="images/sys_manage.gif" height="18" width="18" border="0" alt="Přidat kategorii">
				<a href="?action=showmain&amp;project=<?php echo $_SESSION['project'];?>"><?php echo _GUEST_MAIN;?></a></td>
		</tr>
	</table>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr class="popisky">
			<td width="490" align="left"><span class="nadpis-boxy"><?php echo _GUEST_NAME;?></span></td>
			<td width="100" align="left"><span class="nadpis-boxy"><?php echo _GUEST_AUTHOR;?></span></td>
			<td width="50" align="left"><span class="nadpis-boxy"><?php echo _GUEST_LANG;?></span></td>
			<td width="50" align="left"><span class="nadpis-boxy"><?php echo _GUEST_COUNT_COMM;?></span></td>
			<td width="30" align="center"><span class="nadpis-boxy"><?php echo _GUEST_ID;?></span></td>
		</tr>
		<tr>
			<td width="490" align="left"><?php echo $ar[guestbook_topic_topic];?></td>
			<td width="100" align="left"><?php echo $ar[guestbook_topic_author];?></td>
			<td width="50" align="left"><?php echo $ar[guestbook_topic_lang];?></td>
			<td width="50" align="left"><?php echo $num2;?></td>
			<td width="30" align="center"><?php echo $ar[guestbook_topic_id];?></td>
		</tr>
		<tr>
			<td align="left" colspan="5"><br><br><strong><?php echo _GUEST_DEL_WARNING;?></strong></td>
		</tr>
	</table>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td valign="top" colspan="2"><br><br><strong><span style="color : #FF0000;"><?php echo _GUEST_DELCHECK; ?></span></strong></td>
		</tr>
		<tr>
			<td width="50" valign="top">
				<form action="modul_guestbook.php" method="post">
				<input type="submit" value="<?php echo _CMN_YES;?>" class="eden_button"><br>
				<input type="hidden" name="action" value="delete">
				<input type="hidden" name="id" value="<?php echo $id;?>">
				<input type="hidden" name="project" value="<?php echo $_SESSION['project'];?>">
				<input type="hidden" name="confirm" value="true">
				</form>

			</td>
			<td width="800" valign=TOP>
				<form action="modul_guestbook.php" method="post">
				<input type="submit" value="<?php echo _CMN_NO;?>" class="eden_button_no"><br>
				<input type="hidden" name="action" value="delete">
				<input type="hidden" name="id" value="<?php echo $id;?>">
				<input type="hidden" name="project" value="<?php echo $_SESSION['project'];?>">
				<input type="hidden" name="confirm" value="false">
				</form>
			</td>
		</tr>
	</table>
<?php 	}
}

include ("inc.header.php");
	if ($action == "") { ShowMain(); }
	if ($action == "showmain") { ShowMain(); }
	if ($action == "open") { ShowMain(); }
	if ($action == "close") { ShowMain(); }
	if ($action == "add") { Add(); }
	if ($action == "edit") { Add(); }
	if ($action == "delete") { Del(); }
	if ($action == "save") { ShowMain(); }
	if ($action == "del_selected") { ShowMain(); }
include ("inc.footer.php");