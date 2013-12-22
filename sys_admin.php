<?php
/***********************************************************************************************************
*																											
*		ADMIN MENU																							
*																											
***********************************************************************************************************/
function SearchAdmin(){
	
	global $db_admin,$eden_cfg;
	echo "<tr>\n";
	echo "	<td valign=\"bottom\" colspan=\"2\"><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._ADMIN_ADD_ADMIN."\">&nbsp;&nbsp;\n";
	echo "	<a href=\"sys_admin.php?action=admins_add&amp;project=".$_SESSION['project']."\">"._ADMIN_ADD_ADMIN."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	echo "	<a href=\"sys_admin.php?action=admins&amp;show_status=admin&amp;project=".$_SESSION['project']."\">"._ADMIN_SHOW_ADMINS."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	echo "	<a href=\"sys_admin.php?action=admins&amp;show_status=user&amp;project=".$_SESSION['project']."\">"._ADMIN_SHOW_USERS."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	if ($eden_cfg['modul_shop'] == 1 && CheckPriv("groups_shop_add") == 1) { echo "<a href=\"sys_admin.php?action=admins&amp;show_status=seller&amp;project=".$_SESSION['project']."\">"._ADMIN_SHOW_SELLERS."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";}
	echo "	<a href=\"sys_admin.php?action=admins&amp;show_status=notallowed&amp;project=".$_SESSION['project']."\">"._ADMIN_SHOW_NOTALOWED."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	echo "	<a href=\"sys_admin.php?action=admins&amp;show_status=banned&amp;project=".$_SESSION['project']."\">"._ADMIN_BANNED."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	echo "	<a href=\"sys_admin.php?action=admins&amp;show_status=muted&amp;project=".$_SESSION['project']."\">"._ADMIN_SHOW_MUTED."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	echo "	<a href=\"sys_admin.php?action=showcategory&amp;project=".$_SESSION['project']."\">"._ADMIN_SHOW_CATEGORY."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	echo "	<a href=\"sys_admin.php?action=managepicture&amp;project=".$_SESSION['project']."\">"._DELETEIMAGE."</a>\n";
	echo "	</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"left\" colspan=\"2\">"; Alphabeth('sys_admin.php?action=showbyletter&amp;project='.$_SESSION['project'].'&amp;show_status=user&amp;sa=form&amp;letter=',''); echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"left\" colspan=\"2\"><form action=\"sys_admin.php?action=admins&amp;from=search&amp;show_status=user\" enctype=\"multipart/form-data\" method=\"post\">\n";
	echo "	<strong>"._ADMIN_SEARCH_BY.":</strong>\n";
	echo "	<select name=\"search_by\">\n";
	echo "		<option value=\"admin_id\">"._ADMIN_SEARCH_BY_ID."</option>\n";
	echo "		<option value=\"admin_name\">"._ADMIN_SEARCH_BY_NAME."</option>\n";
	echo "		<option value=\"admin_uname\">"._ADMIN_SEARCH_BY_UNAME."</option>\n";
	echo "		<option value=\"admin_nick\">"._ADMIN_SEARCH_BY_NICK."</option>\n";
	echo "		<option value=\"admin_email\">"._ADMIN_SEARCH_BY_EMAIL."</option>\n";
	echo "	</select>\n";
	echo "	<strong>"._ADMIN_SEARCH.":</strong>\n";
	echo "	<input type=\"text\" name=\"search_this\" maxlength=\"60\" size=\"40\" />\n";
	echo "	<input type=\"submit\" value=\""._ADMIN_SEARCH."\" class=\"eden_button\">\n";
	echo "	<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "	</form></td>\n";
	echo "</tr>";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['msg']){
		echo "<tr><td style=\"color:#ff0000;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
}
/***********************************************************************************************************
*																											
*		ZOBRAZENI STATISTIK																					
*																											
***********************************************************************************************************/
function ShowMain(){
	
	global $db_admin;
	
	$res = mysql_query("SELECT COUNT(*) FROM $db_admin") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	$res1 = mysql_query("SELECT COUNT(*) FROM $db_admin WHERE admin_reg_allow=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar1 = mysql_fetch_array($res1);
	$res2 = mysql_query("SELECT COUNT(*) FROM $db_admin WHERE admin_reg_allow=1 AND admin_status='user'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar2 = mysql_fetch_array($res2);
	$res3 = mysql_query("SELECT COUNT(*) FROM $db_admin WHERE admin_reg_allow=1 AND admin_status='admin'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar3 = mysql_fetch_array($res3);
	$res4 = mysql_query("SELECT COUNT(*) FROM $db_admin WHERE admin_reg_allow=2") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar4 = mysql_fetch_array($res4);
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>";
	echo "		<td align=\"left\" class=\"nadpis\">"._ADMINS; if ($_GET['show_status'] == "" || $_GET['show_status'] == "admin"){echo " - "._ADMIN_ADMINS;} elseif ($_GET['show_status'] == "user"){echo _ADMIN_USERS;} else {echo _ADMIN_NOTALLOW;} echo "</td>";
	echo "		<td valign=\"bottom\" align=\"right\">";
				$res_reg = mysql_query("SELECT COUNT(*) AS number FROM $db_admin WHERE admin_reg_allow=1 OR admin_reg_allow=2") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_reg = mysql_fetch_array($res_reg);
				echo _ADMIN_REGUSERS.$ar_reg['number'];
	echo "		</td>";
	echo "	</tr>";
	if (CheckPriv("groups_admin_add") == 1){SearchAdmin();}
	echo "<tr valign=\"top\">";
	echo "	<td width=\"90%\" valign=\"top\" colspan=\"2\">";
	echo "		<br>";
			if (empty($ar1[0])) {$ar1[0]=0;}
			if (empty($ar2[0])) {$ar2[0]=0;}
			if (empty($ar3[0])) {$ar3[0]=0;}
			if (empty($ar4[0])) {$ar4[0]=0;}
	echo "			<br>\n";
	echo "			<table cellspacing=\"1\" cellpadding=\"5\" bgcolor=\"#DDDDDD\" align=\"center\">\n";
	echo "				<tr bgcolor=\"#EEEEEE\">\n";
	echo "					<td><strong>"._STAT_STATISTICS."</strong></td>\n";
	echo "					<td><strong>"._STAT_NEWS."</strong></td>\n";
	echo "				</tr>\n";
	echo "				<tr bgcolor=\"#FFFFFF\">\n";
	echo "					<td>"._ADMIN_STAT_REGISTER.":</td>\n";
	echo "					<td align=\"right\"><strong>".$ar[0]."</strong></td>\n";
	echo "				</tr>\n";
	echo "				<tr bgcolor=\"#FFFFFF\">\n";
	echo "					<td>"._ADMIN_STAT_REGISTER_AND_ACTIVE.":</td>\n";
	echo "					<td align=\"right\"><strong>".$ar1[0]."</strong></td>\n";
	echo "				</tr>\n";
	echo "				<tr bgcolor=\"#FFFFFF\">\n";
	echo "					<td>"._ADMIN_STAT_MUTED.":</td>\n";
	echo "					<td align=\"right\"><strong>".$ar4[0]."</strong></td>\n";
	echo "				</tr>\n";
	echo "				<tr bgcolor=\"#FFFFFF\">\n";
	echo "					<td>"._ADMIN_STAT_STATUS_USER.":</td>\n";
	echo "					<td align=\"right\"><strong>".$ar2[0]."</strong></td>\n";
	echo "				</tr>\n";
	echo "				<tr bgcolor=\"#FFFFFF\">\n";
	echo "					<td>"._ADMIN_STAT_STATUS_ADMIN.":</td>\n";
	echo "					<td align=\"right\"><strong>".$ar3[0]."</strong></td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "			<br>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}

/***********************************************************************************************************
*																											
*		ZOBRAZENI ADMINISTRATORU 																			
*																											
***********************************************************************************************************/
function Admins(){
	
	global $db_admin,$db_admin_clan,$db_admin_contact,$db_admin_contact_shop,$db_admin_game,$db_admin_info,$db_admin_hw,$db_admin_poker;
	global $db_groups,$db_admin_category,$db_setup,$db_setup_lang;
	global $url_admins;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	$page		= $_GET['page'];
	if ($_GET['search_by'] != ""){$_POST["search_by"] = $_GET['search_by'];}
	if ($_GET['search_this'] != ""){$_POST["search_this"] = $_GET['search_this'];}
	
	/* Pokud je aktivovan ucet s volbou zaslani upozorneni na email */
	if ($_GET['act_by_email'] == 1){
		$res = mysql_query("SELECT admin_id, admin_uname, admin_firstname, admin_name, admin_nick, admin_email FROM $db_admin WHERE admin_id=".(float)$_GET['aid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); 
		$ar = mysql_fetch_array($res);
		$res_setup = mysql_query("SELECT s.setup_reg_from, s.setup_reg_from_name, s.setup_reg_mailer, sl.setup_lang_reg_subject FROM $db_setup AS s, $db_setup_lang AS sl WHERE sl.setup_lang='".mysql_real_escape_string($ar['admin_lang'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_setup = mysql_fetch_array($res_setup);
		
		mysql_query("UPDATE $db_admin SET admin_reg_allow=1 WHERE admin_id=".(float)$_GET['aid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		
		$mail = new PHPMailer();
		$mail->From = $ar_setup['setup_reg_from'];
		$mail->FromName = $ar_setup['setup_reg_from_name'];
		$mail->AddAddress($ar['admin_email']);
		$mail->Mailer = $ar_setup['setup_reg_mailer'];
		$mail->Subject = $ar_setup['setup_lang_reg_subject'];
		$mail->Body = "\n
		"._ADMIN_ALLOWED_1.$eden_cfg['misc_web']._ADMIN_ALLOWED_2."
		\n
		ID: $ar[admin_id]
		Username: $ar[admin_uname]
		Nickname: $ar[admin_nick]
		Firstname: $ar[admin_firstname]
		Surename: $ar[admin_name]
		
		Email: $ar[admin_email]
		
		\n";
		$mail->WordWrap = 100;
		
		if (!$mail->Send()){
			echo _ERROR_ADMIN_ALLOW_ACT_EMAIL_NO;
			$_GET['show_status'] = "notallowed";
		} else {
			echo _ERROR_ADMIN_ALLOW_ACT_EMAIL_YES;
			$_GET['show_status'] = "user";
		}
	}
	/* Ulozeni dat */
	if ($_POST['confirm'] == true){
		$i=1;
		$x=0;
		$admin_data = $_POST['admin_data'];
		while($i <= $_POST['admin_num']){
			$admin_id = $admin_data[$i.'_admin_id'];
		   	if ($admin_id != ""){
				/* Kontrola opravneni zmeny */
				$res_adm = mysql_query("SELECT admin_priv FROM $db_admin WHERE admin_id=".(integer)$admin_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); 
				$ar_adm = mysql_fetch_array($res_adm);
				$res_adm_group = mysql_query("SELECT groups_level FROM $db_groups WHERE groups_id=".$ar_adm['admin_priv']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); 
				$ar_adm_group = mysql_fetch_array($res_adm_group);
				
				if (CheckPriv("groups_level") > $ar_adm_group['groups_level'] || CheckPriv("groups_level") == 99){
					
					$res = mysql_query("SELECT admin_nick, admin_priv, admin_reg_allow, admin_cat1, admin_cat1_order, admin_cat2, admin_cat2_order, admin_cat3, admin_cat3_order, admin_status FROM $db_admin WHERE admin_id=".(integer)$admin_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); 
					$ar = mysql_fetch_array($res);
					
					if ($admin_data[$i.'_admin_cat1'] != ""){$admin_cat1 = $admin_data[$i.'_admin_cat1'];} else {$admin_cat1 = $ar['admin_cat1'];}
					if ($admin_data[$i.'_admin_cat1_order'] != ""){$admin_cat1_order =  $admin_data[$i.'_admin_cat1_order'];} else {$admin_cat1_order = $ar['admin_cat1_order'];}
					if ($admin_data[$i.'_admin_cat2'] != ""){$admin_cat2 =  $admin_data[$i.'_admin_cat2'];} else {$admin_cat2 = $ar['admin_cat2'];}
					if ($admin_data[$i.'_admin_cat2_order'] != ""){$admin_cat2_order =  $admin_data[$i.'_admin_cat2_order'];} else {$admin_cat2_order = $ar['admin_cat2_order'];}
					if ($admin_data[$i.'_admin_cat3'] != ""){$admin_cat3 =  $admin_data[$i.'_admin_cat3'];} else {$admin_cat3 = $ar['admin_cat3'];}
					if ($admin_data[$i.'_admin_cat3_order'] != ""){$admin_cat3_order =  $admin_data[$i.'_admin_cat3_order'];} else {$admin_cat3_order = $ar['admin_cat3_order'];}
					if ($admin_data[$i.'_admin_priv'] != ""){$admin_priv =  $admin_data[$i.'_admin_priv'];} else {$admin_priv = $ar['admin_priv'];}
					if ($admin_data[$i.'_admin_status'] != ""){$admin_status =  $admin_data[$i.'_admin_status'];} else {$admin_status = $ar['admin_status'];}
					if ($admin_data[$i.'_admin_reg_allow'] != ""){$admin_rg_allow =  $admin_data[$i.'_admin_reg_allow'];} else {$admin_rg_allow = $ar['admin_reg_allow'];}
					
					$res_admin_group = mysql_query("SELECT groups_level FROM $db_groups WHERE groups_id=".$ar['admin_priv']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); 
					$ar_admin_group = mysql_fetch_array($res_admin_group);
					
					/* Nastaveni zamezeni zobrazeni a editaci privilegii u uzivatelu na stejnem nebo vyssim levelu */
					if (CheckPriv("groups_level") > $ar_admin_group['groups_level'] || CheckPriv("groups_level") == 99){
						/* Nastavime spravne $admin_rg_allow 	*/
						/* 0 = neaktivovany 					*/
						/* 1 = aktivovany						*/
						/* 2 = aktivovany ale muted				*/
						/* 3 = neaktivovany adminem				*/
						/* 4 = banned							*/
						if ($admin_rg_allow == "" && $ar['admin_reg_allow'] == 0){$admin_rg_allow = 0;}
						
						/* Pokud neni vybrano odstranit uzivatelu */
						if ($admin_data[$i.'_admin_delete'] != 1){
							/* Zmeny se ulozi pouze pokud bylo neco zmeneno */
							if ($admin_cat1 != $ar['admin_cat1'] || $admin_cat1_order != $ar['admin_cat1_order'] || $admin_cat2 != $ar['admin_cat2'] || $admin_cat2_order != $ar['admin_cat2_order'] || $admin_priv != $ar['admin_priv'] || $admin_status != $ar['admin_status'] || $admin_rg_allow != $ar['admin_reg_allow']){
								if ($admin_status == "admin" && $admin_cat1 == "" && $admin_cat1_order == "" && $admin_cat2 == "" && $admin_cat2_order == ""){$admin_cat1 = 0; $admin_cat1_order = 99; $admin_cat2 = 0; $admin_cat2_order = 99;}
								mysql_query("UPDATE $db_admin 
								SET admin_cat1=".(integer)$admin_cat1.", admin_cat1_order=".(integer)$admin_cat1_order.", admin_cat2_order=".(integer)$admin_cat2_order.", admin_cat2=".(integer)$admin_cat2.", 
								admin_priv=".(integer)$admin_priv.", admin_status='".mysql_real_escape_string($admin_status)."', admin_reg_allow=".(integer)$admin_rg_allow." 
								WHERE admin_id=".(float)$admin_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								$x++;
								$_GET['msg'] = "ae_ok";
							}
						/* Pokud je vybrano odstraneni uzivatelu */
						} elseif ($admin_data[$i.'_admin_delete'] == 1){
							echo stripslashes($ar['admin_nick'])."<br>";
							mysql_query("DELETE FROM $db_admin WHERE admin_id=".(integer)$admin_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); 
							mysql_query("DELETE FROM $db_admin_clan WHERE aid=".(integer)$admin_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); 
							mysql_query("DELETE FROM $db_admin_contact WHERE aid=".(integer)$admin_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							mysql_query("DELETE FROM $db_admin_contact_shop WHERE aid=".(integer)$admin_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							mysql_query("DELETE FROM $db_admin_game WHERE aid=".(integer)$admin_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							mysql_query("DELETE FROM $db_admin_hw WHERE aid=".(integer)$admin_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							mysql_query("DELETE FROM $db_admin_info WHERE aid=".(integer)$admin_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							mysql_query("DELETE FROM $db_admin_poker WHERE aid=".(integer)$admin_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$x++;
						}
					} else {
						echo "ID: ".$admin_id." - "._ADMIN_ERROR_SAVE_NOT_ENOUGH_PRIV."<br>";
					}
				}
			}
			$i++;
		}
	}
	
	// Jestlize neni vybrano podle ceho se ma tridit, je vybrano podle datumu sestupne 
	if ($_GET['ser'] == "" && $_GET['as'] == ""){
		if($_GET['show_status'] == "notallowed"){ 
			$as = "admin_reg_date";
		} else {
			$as = "admin_uname";
		}
		$ser = "asc";
	} else {
		$as = mysql_real_escape_string($_GET['as']);
		$ser = mysql_real_escape_string($_GET['ser']);
	}
	
	/* Nacteni dat podle statutu admina/uzivatele */
	if (CheckPriv("groups_admin_add") != 1 || CheckPriv("groups_admin_edit") != 1) { // Pokud nema admin prava pro pridavani atd. zobrazi se mu ve vypisu adminu jen on sam
		$res = mysql_query("SELECT admin_id, admin_uname, admin_nick, admin_email, admin_priv, admin_status, admin_userimage, admin_cat1, admin_cat1_order, admin_cat2, admin_cat2_order, admin_reg_date, admin_reg_allow FROM $db_admin WHERE admin_id=".(integer)$_SESSION['loginid']." ORDER BY $as $ser $limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}elseif ($_GET['from'] == "groups"){ // Pokud se pristupuje z groups tak se zobrazi jen dany admin a jeho prava
		$res = mysql_query("SELECT admin_id, admin_uname, admin_nick, admin_email, admin_priv, admin_status, admin_userimage, admin_cat1, admin_cat1_order, admin_cat2, admin_cat2_order, admin_reg_date, admin_reg_allow FROM $db_admin WHERE admin_id=".(integer)$_GET['search_this']." ORDER BY $as $ser $limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}elseif ($_GET['from'] == "search"){ // Pokud se vyhedava pres search tak se zobrazi jen dani admini a jejich prava
		if ($_POST['search_by'] == "admin_id"){
			$where = mysql_real_escape_string($_POST['search_by'])." = '".mysql_real_escape_string($_POST['search_this'])."'";
		} else {
			$where = mysql_real_escape_string($_POST['search_by'])." LIKE '%".mysql_real_escape_string($_POST['search_this'])."%'";
		}
		$res = mysql_query("SELECT admin_id, admin_uname, admin_nick, admin_email, admin_priv, admin_status, admin_userimage, admin_cat1, admin_cat1_order, admin_cat2, admin_cat2_order, admin_reg_date, admin_reg_allow FROM $db_admin WHERE $where ORDER BY $as $ser $limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	} else { // Pokud ma nejaka prava zobrazi se mu vypis vsech adminu
		if ($_GET['sa'] == "form"){
			if ($_GET['letter'] == "Other"){$like2 = "REGEXP";} elseif ($_GET['letter'] != ""){$like2 = "LIKE";}
			if ($_GET['letter'] != "All"){ $like = "AND admin_nick ".$like2." ".AlphabethSelect(mysql_real_escape_string($_GET['letter']), "admin_nick");}
		}
		if ($_GET['show_status'] == "" || $_GET['show_status'] == "admin"){$where = "admin_status = 'admin'";}
		if ($_GET['show_status'] == "user"){$where = "admin_status='user'";}
		if ($_GET['show_status'] == "seller"){$where = "admin_status='seller'";}
		if ($_GET['show_status'] == "notallowed"){$where = "admin_reg_allow=0";}
		if ($_GET['show_status'] == "banned"){$where = "admin_reg_allow=3";}
		if ($_GET['show_status'] == "muted"){$where = "admin_reg_allow=2";}
		
		$res_num = mysql_query("SELECT COUNT(*) FROM $db_admin WHERE $where $like") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num = mysql_fetch_array($res_num);
		
		//Timto nastavime pocet prispevku na strance
		if (empty($page)) {$page = 1;} // i kdyz je promenna $page prazdna nastavi se pocet starnek na 1
		$hits = 150; //Zde se nastavuje pocet prispevku
		$stw2 = ($num[0]/$hits);
		$stw2 = (integer)$stw2;
		if ($num[0]%$hits > 0) {$stw2++;}
		$np = $page+1;
		$pp = $page-1;
		if ($page == 1) { $pp = 1; }
		if ($np > $stw2) { $np = $stw2;} 
		
		$sp = ($page-1)*$hits;
		$ep = ($page-1)*$hits+$hits;
		
		echo $sp."-".$ep;
		$limit = "LIMIT ".(integer)$sp.", ".(integer)$hits."";
		
		$res = mysql_query("SELECT admin_id, admin_uname, admin_nick, admin_email, admin_priv, admin_status, admin_userimage, admin_cat1, admin_cat1_order, admin_cat2, admin_cat2_order, admin_reg_date, admin_reg_allow  FROM $db_admin WHERE $where $like ORDER BY $as $ser $limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	// Nacteme privilegia do pole $check_priv[] a zvysime tak rychlost skriptu
	if (CheckPriv("groups_group_edit") == 1){$check_priv['group_edit'] = 1;} else {$check_priv['group_edit'] = 0;}
	if (CheckPriv("groups_admin_add") == 1) {$check_priv['admin_add'] = 1;} else {$check_priv['admin_add'] = 0;}
	if (CheckPriv("groups_admin_edit") == 1) {$check_priv['admin_edit'] = 1;} else {$check_priv['admin_edit'] = 0;}
	if (CheckPriv("groups_admin_del") == 1) {$check_priv['admin_del'] = 1;} else {$check_priv['admin_del'] = 0;}
	
	// Nacteme skupiny opravneni do vicerozmerneho pole a zvysime tak rychlost skriptu
	$res2 = mysql_query("SELECT groups_id, groups_name, groups_level FROM $db_groups ORDER BY groups_level DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); 
	$groups_num = mysql_num_rows($res2);
	$i=0;
	while ($ar2 = mysql_fetch_array($res2)){
		$groups[$i] = array($ar2['groups_id'], $ar2['groups_name'], $ar2['groups_level']);
		$i++;
	}
	$res_reg = mysql_query("SELECT COUNT(*) AS number FROM $db_admin WHERE admin_reg_allow=1 OR admin_reg_allow=2") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_reg = mysql_fetch_array($res_reg);
	
	echo "<script type=\"text/javascript\">\n";
	echo "	function toggleChecked(status) {\n";
	echo "		\$(\".checkbox_del_admin\").each( function() {\n";
	echo "		\$(this).attr(\"checked\",status);\n";
	echo "		})\n";
	echo "	}\n";
	echo "</script>";
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"; if ($_GET['show_status'] == "" || $_GET['show_status'] == "admin"){echo _ADMINS." - "._ADMIN_ADMINS;}elseif ($_GET['show_status'] == "user"){echo _ADMIN_USERS." - "._ADMIN_USERS;}elseif ($_GET['show_status'] == "seller"){echo _ADMIN_USERS." - "._ADMIN_SELLERS;}elseif ($_GET['show_status'] == "banned"){echo _ADMIN_USERS." - "._ADMIN_BANNED;}elseif ($_GET['show_status'] == "muted"){echo _ADMIN_USERS." - "._ADMIN_SHOW_MUTED;} else {echo _ADMIN_USERS." - "._ADMIN_SHOW_NOTALOWED;} echo "</td>\n";
	echo "		<td valign=\"bottom\" align=\"right\">";
	echo 			_ADMIN_REGUSERS.$ar_reg['number']."\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	if ($check_priv['admin_add'] == 1){SearchAdmin();}
	echo "</table>";
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._ADMIN_USERNAME."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">A</span></td>\n";
	// Nezobrazi se pokud je status seller
	if ($_GET['show_status'] != "seller"){
		echo "<td><span class=\"nadpis-boxy\">"._ADMIN_MUTE."</span></td>\n";
		echo "<td><span class=\"nadpis-boxy\">"._ADMIN_BAN."</span></td>\n";
		echo "<td><span class=\"nadpis-boxy\">"; if ($_GET['show_status'] == "notallowed" || $_GET['show_status'] == "banned" ||  $_GET['show_status'] == "muted" ||  $_GET['show_status'] == "user"){echo _ADMIN_DEL_ADMIN;} else {echo _ADMIN_CAT;} echo "</span></td>\n";
	}
	if ($_GET['show_status'] != "muted"){
		if ($_GET['show_status'] != "notallowed" && $_GET['show_status'] != "banned" && $_GET['show_status'] != "user" && $_GET['show_status'] != "seller"){
			echo "<td><span class=\"nadpis-boxy\">"._ADMIN_CAT." 2</span></td>\n";
		} else {
			echo "<td><span class=\"nadpis-boxy\">"._ADMIN_REG_DATE."</span></td>\n";
		}
		echo "<td><span class=\"nadpis-boxy\">"._ADMIN_STATUS."</span></td>\n";
	}
	
	if ($_GET['show_status'] != "user" && $_GET['show_status'] != "muted"){
		echo "<td><span class=\"nadpis-boxy\">"; if (($_GET['show_status'] == "notallowed" || $_GET['show_status'] == "banned")  && $check_priv['admin_add'] == 1){echo _ADMIN_ALLOW_AND_SEND_EMIAL;} else {echo _ADMIN_PRIVILEGES;} echo "</span></td>\n";
	}
	echo "	</tr>\n";
	
	
	echo " 	<tr class=\"popisky\">\n";
	echo " 		<td>&nbsp;</td>\n";
	echo " 		<td align=\"center\" "; if ($as == "admin_id"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"sys_admin.php?action=admins&amp;project=".$_SESSION['project']."&amp;show_status=".$_GET['show_status']."&amp;as=admin_id&amp;ser=asc&amp;hits=".$hits."&amp;sa=".$sa."\"><img src=\"images/asc_"; if ($as == "admin_id" && $ser == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"sys_admin.php?action=admins&amp;project=".$_SESSION['project']."&amp;show_status=".$_GET['show_status']."&amp;as=admin_id&amp;ser=desc&amp;hits=".$hits."&amp;sa=".$sa."\"><img src=\"images/des_"; if ($as == "admin_id" && $ser == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo " 		<td align=\"center\" "; if ($as == "admin_uname"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"sys_admin.php?action=admins&amp;project=".$_SESSION['project']."&amp;show_status=".$_GET['show_status']."&amp;as=admin_uname&amp;ser=asc&amp;hits=".$hits."&amp;sa=".$sa."\"><img src=\"images/asc_"; if ($as == "admin_uname" && $ser == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"sys_admin.php?action=admins&amp;project=".$_SESSION['project']."&amp;show_status=".$_GET['show_status']."&amp;as=admin_uname&amp;ser=desc&amp;hits=".$hits."&amp;sa=".$sa."\"><img src=\"images/des_"; if ($as == "admin_uname" && $ser == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
	echo " 		<td>&nbsp;</td>\n";
	// Nezobrazi se pokud je status seller
	if ($_GET['show_status'] != "seller"){
		echo "<td>&nbsp;</td>\n";
		echo "<td>&nbsp;</td>\n";
		echo "<td>&nbsp;</td>\n";
	}
	if ($_GET['show_status'] != "muted"){
		if ($_GET['show_status'] != "notallowed" && $_GET['show_status'] != "banned" && $_GET['show_status'] != "user" && $_GET['show_status'] != "seller"){
			echo "<td>&nbsp;</td>\n";
		} else {
			echo "<td width=\"100\" align=\"center\" "; if ($as == "admin_reg_date"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"sys_admin.php?action=admins&amp;project=".$_SESSION['project']."&amp;show_status=".$_GET['show_status']."&amp;as=admin_reg_date&amp;ser=asc&amp;hits=".$hits."&amp;sa=".$sa."\"><img src=\"images/asc_"; if ($as == "admin_reg_date" && $ser == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"sys_admin.php?action=admins&amp;project=".$_SESSION['project']."&amp;show_status=".$_GET['show_status']."&as=admin_reg_date&amp;ser=desc&amp;hits=".$hits."&amp;sa=".$sa."\"><img src=\"images/des_"; if ($as == "admin_reg_date" && $ser == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
		}
		echo "<td>&nbsp;</td>\n";
	}
	if ($_GET['show_status'] != "user" && $_GET['show_status'] != "muted"){
		echo "<td>&nbsp;</td>\n";
	}
	echo " 	</tr>\n";
	$admin_number = 1;
	$odd_even = 1;
	while ($ar = mysql_fetch_array($res)){
		/* Nastaveni zobrazeni podle statusu admina/uzivatele */
	if ($_GET['show_status'] == "user" || $_GET['show_status'] == "admin"){ $_GET['show_status'] = $ar['admin_status'];}
		/* Nastaveni zobrazeni zacatku formulare jen jedinkrat */
		if ($admin_number == 1){
			echo "<form action=\"sys_admin.php?action=edit_priv&amp;from=".$_GET['from']."&amp;id=".$ar['admin_id']."&amp;show_status=".$_GET['show_status']."\" method=\"post\" enctype=\"multipart/form-data\">";
		}
		$res_admin_group = mysql_query("SELECT groups_level FROM $db_groups WHERE groups_id=".$ar['admin_priv']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); 
		$ar_admin_group = mysql_fetch_array($res_admin_group);
		if ($ar['admin_userimage']== ""){$image = "0000000001.gif";} else {$image = $ar['admin_userimage'];}
		if ($odd_even % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td valign=\"top\">"; if (($check_priv['admin_edit'] == 1 || $ar['admin_id'] == $_SESSION['loginid']) && (CheckPriv("groups_level") > $ar_admin_group['groups_level'] || CheckPriv("groups_level") == 99)) { echo "<a href=\"sys_admin.php?action=admins_edit&amp;id=".$ar['admin_id']."&amp;show_status=".$_GET['show_status']."&amp;project=".$_SESSION['project']."&amk;from=".$_GET['from']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\"></a>";}
					if ($check_priv['admin_del'] == 1) { echo "<a href=\"sys_admin.php?action=admins_del&amp;show_status=".$_GET['show_status']."&amp;id=".$ar['admin_id']."&amp;project=".$_SESSION['project']."&amp;letter=".$_GET['letter']."&amp;sa=".$_GET['sa']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\"></a>";} 
		echo "	</td>\n";
		echo "	<td valign=\"top\" align=\"right\">[".$admin_number."]&nbsp;".$ar['admin_id']."</td>\n";
		echo "	<td valign=\"top\"><span style=\"color: #ff0000;\"><strong>".$ar['admin_uname']."</strong></span><br>".$ar['admin_nick']."<br>".$ar['admin_email']."</td>\n";
		echo "	<td valign=\"top\" "; if ($ar['admin_reg_allow'] == 0){echo "style=\"background-color:FFDEDF\"";} echo ">\n";
				/* Nastaveni zamezeni zobrazeni a editaci privilegii u uzivatelu na stejnem nebo vyssim levelu */
				if (CheckPriv("groups_level") > $ar_admin_group['groups_level'] || CheckPriv("groups_level") == 99){
					if ($check_priv['admin_edit'] == 1){ 
						echo "<input type=\"hidden\" name=\"admin_num\" value=\"".$admin_number."\">\n";
						echo "<input type=\"hidden\" name=\"admin_data[".$admin_number."_admin_id]\" value=\"".$ar['admin_id']."\">\n";
						echo "<input type=\"radio\" name=\"admin_data[".$admin_number."_admin_reg_allow]\" value=\"1\" "; if ($ar['admin_reg_allow'] == 1){echo "checked";} echo ">\n";
					} else {
						echo $ar['admin_reg_allow'];
					}
				}
			echo "</td>\n";
			// Nezobrazi se pokud je status seller
			if ($_GET['show_status'] != "seller"){
				echo "<td valign=\"top\" "; if ($ar['admin_reg_allow'] == 2){echo "style=\"background-color:FFDEDF\"";} echo ">\n";
					/* Nastaveni zamezeni zobrazeni a editaci privilegii u uzivatelu na stejnem nebo vyssim levelu */
					if (CheckPriv("groups_level") > $ar_admin_group['groups_level'] || CheckPriv("groups_level") == 99){
						if ($check_priv['admin_edit'] == 1){
							echo "<input type=\"radio\" name=\"admin_data[".$admin_number."_admin_reg_allow]\" value=\"2\" "; if ($ar['admin_reg_allow'] == 2){echo "checked";} echo ">\n";
						} else {
							echo $ar['admin_reg_allow'];
						}
					}
				echo "</td>\n";
				echo "<td valign=\"top\" "; if ($ar['admin_reg_allow'] == 3){echo "style=\"background-color:FFDEDF\"";} echo ">\n";
					/* Nastaveni zamezeni zobrazeni a editaci privilegii u uzivatelu na stejnem nebo vyssim levelu */
					if (CheckPriv("groups_level") > $ar_admin_group['groups_level'] || CheckPriv("groups_level") == 99){
						if ($check_priv['admin_edit'] == 1){
							echo "<input type=\"radio\" name=\"admin_data[".$admin_number."_admin_reg_allow]\" value=\"3\" "; if ($ar['admin_reg_allow'] == 3){echo "checked";} echo ">\n";
						} else {
							echo $ar['admin_reg_allow'];
						}
					}
				echo "</td>\n";
				echo "<td valign=\"top\" "; if ($check_priv['admin_del'] == 1 && ($_GET['show_status'] == "notallowed" || $_GET['show_status'] == "banned"  ||  $_GET['show_status'] == "muted" ||  $_GET['show_status'] == "user")){echo "style=\"background-color:FF0000\"";} echo ">\n";
					/* Nastaveni zamezeni zobrazeni a editaci privilegii u uzivatelu na stejnem nebo vyssim levelu */
					if (CheckPriv("groups_level") > $ar_admin_group['groups_level'] || CheckPriv("groups_level") == 99){
						if ($check_priv['group_edit'] == 1){
							if ($_GET['show_status'] == "admin"){ 
								echo "<select name=\"admin_data[".$admin_number."_admin_cat1_order]\" class=\"menu_text\">\n";
								$i = 1;
								while ($i <= 99){
									if ($i<10){$i = "0".$i;}
									echo "<option value=\"".$i."\"";
									if ($i == $ar['admin_cat1_order']) {echo " selected";}
									echo ">".$i."</option>\n";
									$i++;
								}
								echo "</select><br>\n";
								echo "<select name=\"admin_data[".$admin_number."_admin_cat1]\" class=\"menu_text\">\n";
									$res2 = mysql_query("SELECT admin_category_id, admin_category_shortname FROM $db_admin_category ORDER BY admin_category_topictext") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								echo "	<option name=\"admin_data[".$admin_number_cat1."]\" value=\"0\">"._ADMIN_SET_PRIV."</option>\n";
								while ($ar2 = mysql_fetch_array($res2))	{
									echo "<option value=\"".$ar2['admin_category_id']."\"";
									if ($ar2['admin_category_id'] == $ar['admin_cat1']) {echo " selected";}
									echo ">".$ar2['admin_category_shortname']."</option>\n";
								}
								echo "</select>";
							} elseif ($check_priv['admin_del'] == 1){
								echo "<input type=\"checkbox\" name=\"admin_data[".$admin_number."_admin_delete]\" class=\"checkbox_del_admin\" value=\"1\">\n"; 
							}
						}
					}
				echo "</td>";
			}
			/* Datum registrace zobrazime jen v nekterych pripadech */
			if ($_GET['show_status'] == "notallowed" || $_GET['show_status'] == "banned" || $_GET['show_status'] == "user" || $_GET['show_status'] == "seller"){
				echo "<td valign=\"top\">".FormatDatetime($ar['admin_reg_date'],"d.m.Y H:i:s")."</td>\n";
			}
			
			if ($check_priv['group_edit'] == 1 && $_GET['show_status'] != "user" && $_GET['show_status'] != "seller" && $_GET['show_status'] != "muted"){
				echo "<td valign=\"top\">\n";
				/* Nastaveni zamezeni zobrazeni a editaci privilegii u uzivatelu na stejnem nebo vyssim levelu */
				if (CheckPriv("groups_level") > $ar_admin_group['groups_level'] || CheckPriv("groups_level") == 99){
					echo "<select name=\"admin_data[".$admin_number."_admin_cat2_order]\" class=\"menu_text\">\n";
					$i = 1;
					while ($i <= 99){
						if ($i<10){$i = "0".$i;}
						echo "<option value=\"".$i."\"";
						if ($i == $ar['admin_cat2_order']) {echo " selected";}
						echo ">".$i."</option>\n";
						$i++;
					}
					echo "</select><br>\n";
					$res2 = mysql_query("SELECT admin_category_id, admin_category_shortname FROM $db_admin_category ORDER BY admin_category_topictext") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); 
					echo "<select name=\"admin_data[".$admin_number."_admin_cat2]\" class=\"menu_text\">\n";
					echo "<option name=\"priv\" value=\"0\">"._ADMIN_SET_PRIV."</option>\n";
					while ($ar2 = mysql_fetch_array($res2))	{
						echo "<option value=\"".$ar2['admin_category_id']."\"";
						if ($ar2['admin_category_id'] == $ar['admin_cat2']) {echo " selected";}
						echo ">".$ar2['admin_category_shortname']."</option>\n";
					}
					echo "</select>";
				}
				echo "</td>\n";
			}
			if ($check_priv['group_edit'] == 1 && $_GET['show_status'] != "notallowed" && $_GET['show_status'] != "banned"  && $_GET['show_status'] != "muted"){
				echo "<td valign=\"top\">\n";
					/* Nastaveni zamezeni zobrazeni a editaci privilegii u uzivatelu na stejnem nebo vyssim levelu */
					if (CheckPriv("groups_level") > $ar_admin_group['groups_level'] || CheckPriv("groups_level") == 99){
						echo "<select name=\"admin_data[".$admin_number."_admin_status]\" class=\"menu_text\">\n";
						echo "	<option value=\"user\" "; if ($ar['admin_status'] == "user") {echo " selected";} echo ">"._ADMIN_STATUS_USER."</option>\n";
						echo "	<option value=\"admin\" "; if ($ar['admin_status'] == "admin") {echo " selected";} echo ">"._ADMIN_STATUS_ADMIN."</option>\n";
						echo "	<option value=\"seller\" "; if ($ar['admin_status'] == "seller") {echo " selected";} echo ">"._ADMIN_STATUS_SELLER."</option>\n";
						echo "</select>\n";
					}
				echo "</td>\n";
			}
			if ($_GET['show_status'] != "user" && $check_priv['admin_edit'] == 1 && $_GET['show_status'] != "notallowed" && $_GET['show_status'] != "banned" && $_GET['show_status'] != "muted"){
				echo "<td valign=\"top\" align=\"left\">\n";
					/* Nastaveni zamezeni zobrazeni a editaci privilegii u uzivatelu na stejnem nebo vyssim levelu */
					if (CheckPriv("groups_level") > $ar_admin_group['groups_level'] || CheckPriv("groups_level") == 99){
						echo "<select name=\"admin_data[".$admin_number."_admin_priv]\" class=\"menu_text\">\n";
						echo "	<option name=\"admin_data[".$admin_number."_admin_priv]\" value=\"0\">"._ADMIN_SET_PRIV."</option>\n";
							$i=0;
							while ($groups_num > $i){
								if ($groups[$i][2] < CheckPriv("groups_level") || CheckPriv("groups_level") == 99) {
									echo "<option name=\"admin_data[".$admin_number."_admin_priv]\" value=\"".$groups[$i][0]."\" "; if ($groups[$i][0] == $ar['admin_priv']) {echo " selected";} echo ">".$groups[$i][1]."</option>\n";
								}
								$i++;
							}
						echo "</select>";
					}
				echo "</td>";
			}elseif (($_GET['show_status'] == "notallowed" || $_GET['show_status'] == "banned") && $check_priv['admin_edit'] == 1){
				echo "<td valign=\"top\" align=\"left\"><a href=\"sys_admin.php?action=edit_priv&amp;act_by_email=1&amp;aid=".$ar['admin_id']."&amp;project=".$_SESSION['project']."\">"._ADMIN_SUBMIT_ALLOW_ACT_AND_SEND_EMAIL."</a></td>\n";
			}	
		echo "</tr>";
	   	$admin_number++;
		$odd_even++;
	}
	echo "	<tr>\n";
	echo "		<td colspan=\"6\">&nbsp;</td>\n";
	echo "		<td colspan=\"4\">"; if ($check_priv['admin_del'] == 1){ echo "<input type=\"checkbox\" onclick=\"toggleChecked(this.checked)\"> Select / Deselect All";} echo "</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"9\">\n";
	echo "				<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "				<input type=\"hidden\" name=\"search_by\" value=\"".$_POST['search_by'],"\">\n";
	echo "				<input type=\"hidden\" name=\"search_this\" value=\"".$_POST['search_this']."\">\n";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "				<input type=\"submit\" value=\""._CMN_SUBMIT."\" class=\"eden_button\">\n";
	echo "			</form>\n";
	if ($_GET['from'] == "groups"){ 
		echo "	<a href=\"sys_groups.php?project=".$_SESSION['project']."\" targer=\"_SELF\">"._GROUPS."</a>";
	}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	/************************************************************************************************************/
	/*	POCITANI STRANEK - START																				*/
	/*	Pokud je novinek vice, nezli se vejde na 1 stranku zobrazi se jejich pocitani a prechazeni mezi nima	*/
	/************************************************************************************************************/
	if ($stw2 > 1){ 
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "<tr><td height=\"30\">";
		echo _CMN_SELECTPAGE; 
		//Zobrazeni cisla poctu stranek
		for ($i=1;$i<=$stw2;$i++) {
			if ($page == $i) {
				echo " <strong>".$i."</strong>";
			} else {
				echo " <a href=\"sys_admin.php?action=admins&show_status=user&amp;page=".$i."&amp;project=".$_SESSION['project']."\">".$i."</a> ";
			}
		}
		//Zobrazeni sipek s predchozimi a dalsimi strankami novinek
		if ($page > 1){echo "<center><a href=\"sys_admin.php?action=admins&show_status=user&amp;page=".$pp."&amp;project=".$_SESSION['project']."\">"._CMN_PREVIOUS."</a>";} else { echo "<br>"._CMN_PREVIOUS;} echo " <--|--> "; if ($page == $stw2){echo _CMN_NEXT;} else {echo "<a href=\"sys_admin.php?action=admins&show_status=user&amp;page=".$np."&amp;project=".$_SESSION['project']."\">"._CMN_NEXT."</a></center>";}
		echo "</td></tr></table>";
	}
	/************************************************************************************************************/
	/*	POCITANI STRANEK - END																					*/
	/************************************************************************************************************/
}

/***********************************************************************************************************
*																											
*		EDITACE ADMINISTRATORU																				
*																											
***********************************************************************************************************/
function AddUser(){
	
	/* global $id,$confirm; */
	global $db_admin,$db_admin_clan,$db_admin_contact,$db_admin_contact_shop,$db_admin_game,$db_admin_poker,$db_poker_variants;
	global $db_admin_hw,$db_admin_info,$db_groups,$db_country,$db_filters,$db_poker_cardrooms;
	global $eden_cfg;
	global $ftp_path_admins;
	global $url_admins;
	
	$res2 = mysql_query("SELECT * FROM $db_admin WHERE admin_id=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar2 = mysql_fetch_array($res2);
	// CHECK PRIVILEGIES
	if ($_GET['action'] == "admins_add"){
		if (CheckPriv("groups_admin_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($_GET['action'] == "admins_edit"){
		if (CheckPriv("groups_admin_del") <> 1){if (CheckPriv("groups_admin_edit") <> 1 && $ar2['admin_uname'] != $_SESSION['login']) { echo _NOTENOUGHPRIV;ShowMain();exit;}}
	} else {
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	if ($_POST['confirm'] <> "true"){
			$res = mysql_query("SELECT * 
			FROM $db_admin, $db_admin_clan, $db_admin_contact, $db_admin_contact_shop, $db_admin_game, $db_admin_hw, $db_admin_info, $db_admin_poker 
			WHERE $db_admin.admin_id=".(float)$_GET['id']." AND $db_admin_clan.aid=".(float)$_GET['id']." AND $db_admin_contact.aid=".(float)$_GET['id']." AND $db_admin_contact_shop.aid=".(float)$_GET['id']." AND $db_admin_game.aid=".(float)$_GET['id']." AND $db_admin_hw.aid=".(float)$_GET['id']." AND $db_admin_info.aid=".(float)$_GET['id']." AND $db_admin_poker.aid=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar = mysql_fetch_array($res);
			echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
			echo "	<tr>\n";
			echo "		<td align=\"left\" class=\"nadpis\">"._ADMINS." - "; if ($_GET['action'] == "admins_edit"){echo _CMN_EDIT;}elseif ($_GET['action'] == "addfromuser"){echo _ADMIN_FROM_USER;} else {echo _ADMIN_ADDUSER;} echo "</td>\n";
			echo "		<td>&nbsp;</td>\n";
			echo "	<tr>\n";
			echo "	<tr>\n";
			echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\"><a href=\"sys_admin.php?project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a><br></td>\n";
			echo "		<td align=\"right\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "</table>\n";
			echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
			echo "	<tr>\n";
			echo "		<td colspan=\"2\" align=\"left\" valign=\"top\">";
					if ($_POST['confirm'] == "no"){ 
						echo "<span class=\"nadpis\">";
						switch ($msg){
							case "nulovavelikost";
								echo _ADMIN_IMG_NULL;
							break;
							case "vetsivkb";
								echo _ADMIN_IMG_OVERSIZE;
							break;
							case "spatnehesla";
								echo _ADMIN_BADPASS;
							break;
							case "replynames";
								echo _ADMIN_REPLYNAMES;
							break;
							case "noname";
								echo _ADMIN_NONAME;
							break;
							default;
						}
						echo "</span><br>";
					}
			echo "	<form enctype=\"multipart/form-data\" action=\"sys_save.php?action="; if ($_GET['action'] == "admins_edit"){echo "admins_edit";} else {echo "admins_add";} echo "&amp;show_status=".$_GET['show_status']."&from=".$_GET['from']."&amp;id=".$_GET['id']."\" method=\"post\" ONSUBMIT=\"return kontrola(this)\">\n";
			echo "	</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"nadpis\" align=\"left\" valign=\"top\" colspan=\"4\"><strong>"._ADMIN_INFO_BASIC."</strong></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td align=\"right\" valign=\"top\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_ID."</strong></td>\n";
			echo "	<td align=\"left\" valign=\"top\" colspan=\"3\"><h3>".$ar['admin_uname']." - ".$_GET['id']."</h3></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td align=\"right\" valign=\"top\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_USERNAME."</strong></td>\n";
			echo "	<td align=\"left\" valign=\"top\" colspan=\"3\">"; if ($_GET['action'] == "admins_add"){ echo "<input type=\"text\" name=\"admin_username\" size=\"40\" maxlength=\"30\" value=\"\">"._ADMIN_USWARNING;} else {echo "<h3>".$ar['admin_uname']."</h3>";} echo "</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td align=\"right\" valign=\"top\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_NICK."</strong></td>\n";
			echo "	<td align=\"left\" valign=\"top\" colspan=\"3\">"; if (CheckPriv("groups_admin_del") == 1){ echo "<input type=\"text\" name=\"admin_nick\" size=\"40\" maxlength=\"80\" value=\"".$ar['admin_nick']."\">"; } else {echo " <h3 style=\"color: ff0000;\">".$ar['admin_nick']."</h3>";} echo "</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._PASSWORD."</strong></td>\n";
			echo "	<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"password\" name=\"admin_password1\" size=\"40\" value=\"\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._PASSWORD2."</strong></td>\n";
			echo "	<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"password\" name=\"admin_password2\" size=\"40\" value=\"\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_FIRSTNAME."</strong></td>\n";
			echo "	<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_firstname\" size=\"40\" maxlength=\"80\" value=\"".$ar['admin_firstname']."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_NAME."</strong></td>\n";
			echo "	<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_name\" size=\"40\" maxlength=\"80\" value=\"".$ar['admin_name']."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_BIRTH_DAY."</strong></td>\n";
			echo "	<td align=\"left\" valign=\"top\" colspan=\"3\">";
	 					$day = substr($ar['admin_contact_birth_day'], 6, 2);    // vrátí "den"
						$month = substr($ar['admin_contact_birth_day'], 4, 2); // vrátí "mesic"
						$year = substr($ar['admin_contact_birth_day'], 0, 4); // vrátí "rok"
						echo "<select name=\"day\" class=\"input\">\n";
						echo "	<option name=\"day\" value=\"01\" "; if ($day == "01") {echo " selected";} echo ">01</option>\n";
						echo "	<option name=\"day\" value=\"02\" "; if ($day == "02") {echo " selected";} echo ">02</option>\n";
						echo "	<option name=\"day\" value=\"03\" "; if ($day == "03") {echo " selected";} echo ">03</option>\n";
						echo "	<option name=\"day\" value=\"04\" "; if ($day == "04") {echo " selected";} echo ">04</option>\n";
						echo "	<option name=\"day\" value=\"05\" "; if ($day == "05") {echo " selected";} echo ">05</option>\n";
						echo "	<option name=\"day\" value=\"06\" "; if ($day == "06") {echo " selected";} echo ">06</option>\n";
						echo "	<option name=\"day\" value=\"07\" "; if ($day == "07") {echo " selected";} echo ">07</option>\n";
						echo "	<option name=\"day\" value=\"08\" "; if ($day == "08") {echo " selected";} echo ">08</option>\n";
						echo "	<option name=\"day\" value=\"09\" "; if ($day == "09") {echo " selected";} echo ">09</option>\n";
						echo "	<option name=\"day\" value=\"10\" "; if ($day == "10") {echo " selected";} echo ">10</option>\n";
						echo "	<option name=\"day\" value=\"11\" "; if ($day == "11") {echo " selected";} echo ">11</option>\n";
						echo "	<option name=\"day\" value=\"12\" "; if ($day == "12") {echo " selected";} echo ">12</option>\n";
						echo "	<option name=\"day\" value=\"13\" "; if ($day == "13") {echo " selected";} echo ">13</option>\n";
						echo "	<option name=\"day\" value=\"14\" "; if ($day == "14") {echo " selected";} echo ">14</option>\n";
						echo "	<option name=\"day\" value=\"15\" "; if ($day == "15") {echo " selected";} echo ">15</option>\n";
						echo "	<option name=\"day\" value=\"16\" "; if ($day == "16") {echo " selected";} echo ">16</option>\n";
						echo "	<option name=\"day\" value=\"17\" "; if ($day == "17") {echo " selected";} echo ">17</option>\n";
						echo "	<option name=\"day\" value=\"18\" "; if ($day == "18") {echo " selected";} echo ">18</option>\n";
						echo "	<option name=\"day\" value=\"19\" "; if ($day == "19") {echo " selected";} echo ">19</option>\n";
						echo "	<option name=\"day\" value=\"20\" "; if ($day == "20") {echo " selected";} echo ">20</option>\n";
						echo "	<option name=\"day\" value=\"21\" "; if ($day == "21") {echo " selected";} echo ">21</option>\n";
						echo "	<option name=\"day\" value=\"22\" "; if ($day == "22") {echo " selected";} echo ">22</option>\n";
						echo "	<option name=\"day\" value=\"23\" "; if ($day == "23") {echo " selected";} echo ">23</option>\n";
						echo "	<option name=\"day\" value=\"24\" "; if ($day == "24") {echo " selected";} echo ">24</option>\n";
						echo "	<option name=\"day\" value=\"25\" "; if ($day == "25") {echo " selected";} echo ">25</option>\n";
						echo "	<option name=\"day\" value=\"26\" "; if ($day == "26") {echo " selected";} echo ">26</option>\n";
						echo "	<option name=\"day\" value=\"27\" "; if ($day == "27") {echo " selected";} echo ">27</option>\n";
						echo "	<option name=\"day\" value=\"28\" "; if ($day == "28") {echo " selected";} echo ">28</option>\n";
						echo "	<option name=\"day\" value=\"29\" "; if ($day == "29") {echo " selected";} echo ">29</option>\n";
						echo "	<option name=\"day\" value=\"30\" "; if ($day == "30") {echo " selected";} echo ">30</option>\n";
						echo "	<option name=\"day\" value=\"31\" "; if ($day == "31") {echo " selected";} echo ">31</option>\n";
						echo "</select>\n";
						echo "<select name=\"month\" class=\"input\">\n";
						echo "	<option name=\"month\" value=\"01\" "; if ($month == "01") {echo " selected";} echo ">01</option>\n";
						echo "	<option name=\"month\" value=\"02\" "; if ($month == "02") {echo " selected";} echo ">02</option>\n";
						echo "	<option name=\"month\" value=\"03\" "; if ($month == "03") {echo " selected";} echo ">03</option>\n";
						echo "	<option name=\"month\" value=\"04\" "; if ($month == "04") {echo " selected";} echo ">04</option>\n";
						echo "	<option name=\"month\" value=\"05\" "; if ($month == "05") {echo " selected";} echo ">05</option>\n";
						echo "	<option name=\"month\" value=\"06\" "; if ($month == "06") {echo " selected";} echo ">06</option>\n";
						echo "	<option name=\"month\" value=\"07\" "; if ($month == "07") {echo " selected";} echo ">07</option>\n";
						echo "	<option name=\"month\" value=\"08\" "; if ($month == "08") {echo " selected";} echo ">08</option>\n";
						echo "	<option name=\"month\" value=\"09\" "; if ($month == "09") {echo " selected";} echo ">09</option>\n";
						echo "	<option name=\"month\" value=\"10\" "; if ($month == "10") {echo " selected";} echo ">10</option>\n";
						echo "	<option name=\"month\" value=\"11\" "; if ($month == "11") {echo " selected";} echo ">11</option>\n";
						echo "	<option name=\"month\" value=\"12\" "; if ($month == "12") {echo " selected";} echo ">12</option>\n";
						echo "</select>\n";
						echo "<select name=\"year\" class=\"input\">\n";
						echo "	<option	value=\"----\" "; if ($year == "----") {echo " selected";} echo ">----</option>";
							for($y = 1920; $y < 2005; $y++){
								echo "<option value=\"".$y."\""; if ($year == $y) {echo " selected";} echo ">".$y."</option>\n";
							}
		echo "			</select> \n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._CMN_IMAGE."</td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\">#1 <input type=\"file\" name=\"userfile\" size=\"20\"><br>"; if ($ar['admin_userimage'] != "") { echo "<img src=\"".$url_admins.$ar['admin_userimage']."\" border=\"0\" align=\"top\">"; } echo "</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\">&nbsp;</td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\">#2 <input type=\"file\" name=\"userfile2\" size=\"20\"><br>"; if ($ar['admin_userimage2'] != "") { echo "<img src=\"".$url_admins.$ar['admin_userimage2']."\" border=\"0\" align=\"top\">"; } echo "</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_LANG."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><select name=\"admin_lang\" class=\"input\">\n";
		echo "				<option name=\"admin_lang\" value=\"cz\" "; if ($ar['admin_lang'] == "cz") {echo " selected";} echo ">cz</option>\n";
		echo "				<option name=\"admin_lang\" value=\"en\" "; if ($ar['admin_lang'] == "en") {echo " selected";} echo ">en</option>\n";
		echo "			</select>\n";
		echo "		</td>\n";
		echo "		<td class=\"nadpis\" align=\"left\" valign=\"top\" colspan=\"2\"><strong>"._ADMIN_INFO_CONTACT_SHOP."</strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_CONTACT_COMPANYNAME."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_contact_companyname\" size=\"40\" maxlength=\"80\" value=\"".$ar['admin_contact_companyname']."\"></td>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_CONTACT_COMPANYNAME."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_contact_shop_companyname\" size=\"40\" maxlength=\"80\" value=\"".$ar['admin_contact_shop_companyname']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" bgcolor=\"#c0c0c0\"></strong><strong>"._ADMIN_TITLE."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><select name=\"admin_title\" class=\"input\">\n";
		echo "			<option name=\"admin_title\" value=\"\" "; if ($ar['admin_title'] == "") {echo " selected";} echo ">(Select)</option>\n";
		echo "			<option name=\"admin_title\" value=\"Dr\" "; if ($ar['admin_title'] == "Dr") {echo " selected";} echo ">Dr</option>\n";
		echo "			<option name=\"admin_title\" value=\"Mr\" "; if ($ar['admin_title'] == "Mr") {echo " selected";} echo ">Mr</option>\n";
		echo "			<option name=\"admin_title\" value=\"Mrs\" "; if ($ar['admin_title'] == "Mrs") {echo " selected";} echo ">Mrs</option>\n";
		echo "			<option name=\"admin_title\" value=\"Miss\" "; if ($ar['admin_title'] == "Miss") {echo " selected";} echo ">Miss</option>\n";
		echo "			<option name=\"admin_title\" value=\"Ms\" "; if ($ar['admin_title'] == "Ms") {echo " selected";} echo ">Ms</option>\n";
		echo "			</select></td>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_FIRSTNAME."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_contact_shop_firstname\" size=\"40\" maxlength=\"80\" value=\"".$ar['admin_contact_shop_firstname']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_GENDER."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><select name=\"admin_gender\" class=\"input\">\n";
		echo "			<option name=\"admin_gender\" value=\"male\" "; if ($ar['admin_gender'] == "male") {echo " selected";} echo ">"._ADMIN_GENDER_M."</option>\n";
		echo "			<option name=\"admin_gender\" value=\"female\" "; if ($ar['admin_gender'] == "female") {echo " selected";} echo ">"._ADMIN_GENDER_F."</option>\n";
		echo "			</select></td>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_NAME."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_contact_shop_name\" size=\"40\" maxlength=\"80\" value=\"".$ar['admin_contact_shop_name']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_CONTACT_ADDRESS."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_contact_address_1\" size=\"40\" maxlength=\"80\" value=\"".$ar['admin_contact_address_1']."\"></td>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_CONTACT_ADDRESS."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_contact_shop_address_1\" size=\"40\" maxlength=\"80\" value=\"".$ar['admin_contact_shop_address_1']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\">&nbsp;</td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_contact_address_2\" size=\"40\" maxlength=\"80\" value=\"".$ar['admin_contact_address_2']."\"></td>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\">&nbsp;</td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_contact_shop_address_2\" size=\"40\" maxlength=\"80\" value=\"".$ar['admin_contact_shop_address_2']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_CONTACT_POSTCODE."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_contact_postcode\" size=\"40\" maxlength=\"11\" value=\"".$ar['admin_contact_postcode']."\"></td>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_CONTACT_POSTCODE."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_contact_shop_postcode\" size=\"40\" maxlength=\"11\" value=\"".$ar['admin_contact_shop_postcode']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_CONTACT_CITY."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_contact_city\" size=\"40\" maxlength=\"80\" value=\"".$ar['admin_contact_city']."\"></td>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_CONTACT_CITY."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_contact_shop_city\" size=\"40\" maxlength=\"80\" value=\"".$ar['admin_contact_shop_city']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_CONTACT_COUNTRY."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><select name=\"admin_contact_country\" class=\"input\">\n";
						$res3 = mysql_query("SELECT * FROM $db_country ORDER BY country_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($ar3 = mysql_fetch_array($res3)){
							echo "<option value=\"".$ar3['country_id']."\" "; if ($ar['admin_contact_country'] == $ar3['country_id']) {echo " selected";} echo ">".$ar3['country_name']."</option>\n";
						}
		echo "			</select></td>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_CONTACT_COUNTRY."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><select name=\"admin_contact_shop_country\" class=\"input\">\n";
						$res3 = mysql_query("SELECT * FROM $db_country ORDER BY country_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($ar3 = mysql_fetch_array($res3)){
							echo "<option value=\"".$ar3['country_id']."\" "; if ($ar['admin_contact_shop_country'] == $ar3['country_id']) {echo " selected";} echo ">".$ar3['country_name']."</option>\n";
						}
		echo "			</select></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_AUTOLOGIN."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"checkbox\" name=\"admin_autologin\" value=\"1\" "; if ($ar['admin_autologin'] == 1){echo "checked";} echo "></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"nadpis\" align=\"left\" valign=\"top\" colspan=\"4\"><strong>"._ADMIN_INFO_CONTACT."</strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._EMAIL."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_email\" size=\"40\" value=\"".$ar['admin_email']."\"></td>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_MSN."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_contact_msn\" size=\"40\" maxlength=\"20\" value=\"".$ar['admin_contact_msn']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_TELEFON."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_contact_telefon\" size=\"40\" maxlength=\"20\" value=\"".$ar['admin_contact_telefon']."\"></td>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_AOL."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_contact_aol\" size=\"40\" maxlength=\"20\" value=\"".$ar['admin_contact_aol']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_MOBIL."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_contact_mobil\" size=\"40\" maxlength=\"20\" value=\"".$ar['admin_contact_mobil']."\"></td>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_SKYPE."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_contact_skype\" size=\"40\" maxlength=\"20\" value=\"".$ar['admin_contact_skype']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_ICQ."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_contact_icq\" size=\"40\" maxlength=\"20\" value=\"".$ar['admin_contact_icq']."\"></td>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_XFIRE."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_contact_xfire\" size=\"40\" maxlength=\"50\" value=\"".$ar['admin_contact_xfire']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"nadpis\" align=\"left\" valign=\"top\" colspan=\"4\"><strong>"._ADMIN_INFO_CLAN."</strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_CLANTAG."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_clan_tag\" size=\"40\" maxlength=\"50\" value=\"".$ar['admin_clan_tag']."\"></td>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_CLAN_WWW."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_clan_www\" size=\"40\" maxlength=\"255\" value=\"".$ar['admin_clan_www']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_CLANNAME."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_clan_name\" size=\"40\" maxlength=\"255\" value=\"".$ar['admin_clan_name']."\"></td>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_CLAN_IRC."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_clan_irc\" size=\"40\" maxlength=\"50\" value=\"".$ar['admin_clan_irc']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_PLAYER_STATUS."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><select name=\"player_status\" class=\"input\">\n";
		echo "			<option name=\"admin_clan_player_status\" value=\"Player\" "; if ($ar['admin_clan_player_status'] == "Player") {echo " selected";} echo ">Player</option>\n";
		echo "			<option name=\"admin_clan_player_status\" value=\"Clan Leader\" "; if ($ar['admin_clan_player_status'] == "Clan Leader") {echo " selected";} echo ">Clan Leader</option>\n";
		echo "			<option name=\"admin_clan_player_status\" value=\"Honorary Member\" "; if ($ar['admin_clan_player_status'] == "Honorary Member") {echo " selected";} echo ">Honorary Member</option>\n";
		echo "			<option name=\"admin_clan_player_status\" value=\"Inactive\" "; if ($ar['admin_clan_player_status'] == "Inactive") {echo " selected";} echo ">Inactive</option>\n";
		echo "			<option name=\"admin_clan_player_status\" value=\"Manager\" "; if ($ar['admin_clan_player_status'] == "Manager") {echo " selected";} echo ">Manager</option>\n";
		echo "			<option name=\"admin_clan_player_status\" value=\"Team Leader\" "; if ($ar['admin_clan_player_status'] == "Team Leader") {echo " selected";} echo ">Team Leader</option>\n";
		echo "			<option name=\"admin_clan_player_status\" value=\"War Aranger\" "; if ($ar['admin_clan_player_status'] == "War Aranger") {echo " selected";} echo ">War Aranger</option>\n";
		echo "			<option name=\"admin_clan_player_status\" value=\"Redactor\" "; if ($ar['admin_clan_player_status'] == "Redactor") {echo " selected";} echo ">Redactor</option>\n";
		echo "			<option name=\"admin_clan_player_status\" value=\"Cup Head Admin\" "; if ($ar['admin_clan_player_status'] == "Cup Head Admin") {echo " selected";} echo ">Cup Head Admin</option>\n";
		echo "			<option name=\"admin_clan_player_status\" value=\"Cup Admin\" "; if ($ar['admin_clan_player_status'] == "Cup Admin") {echo " selected";} echo ">Cup Admin</option>\n";
		echo "			</select></td>\n";
		echo "	</tr></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"nadpis\" align=\"left\" valign=\"top\" colspan=\"4\"><strong>"._ADMIN_INFO_HW."</strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_HW_CPU."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_hw_cpu\" size=\"120\" maxlength=\"250\" value=\"".$ar['admin_hw_cpu']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_HW_RAM."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_hw_ram\" size=\"120\" maxlength=\"250\" value=\"".$ar['admin_hw_ram']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_HW_MB."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_hw_mb\" size=\"120\" maxlength=\"250\" value=\"".$ar['admin_hw_mb']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_HW_HDD."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_hw_hdd\" size=\"120\" maxlength=\"250\" value=\"".$ar['admin_hw_hdd']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_HW_CD."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_hw_cd\" size=\"120\" maxlength=\"250\" value=\"".$ar['admin_hw_cd']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_HW_VGA."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_hw_vga\" size=\"120\" maxlength=\"250\" value=\"".$ar['admin_hw_vga']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_HW_SOUNDCARD."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_hw_soundcard\" size=\"120\" maxlength=\"250\" value=\"".$ar['admin_hw_soundcard']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_HW_MONITOR."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_hw_monitor\" size=\"120\" maxlength=\"250\" value=\"".$ar['admin_hw_monitor']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_HW_MOUSE."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_hw_mouse\" size=\"120\" maxlength=\"250\" value=\"".$ar['admin_hw_mouse']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_HW_MOUSEPAD."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_hw_mousepad\" size=\"120\" maxlength=\"250\" value=\"".$ar['admin_hw_mousepad']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_HW_HEADSET."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_hw_headset\" size=\"120\" maxlength=\"250\" value=\"".$ar['admin_hw_headset']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_HW_REPRO."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_hw_repro\" size=\"120\" maxlength=\"250\" value=\"".$ar['admin_hw_repro']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_HW_KEY."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_hw_key\" size=\"120\" maxlength=\"250\" value=\"".$ar['admin_hw_key']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_HW_GAMEPAD."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_hw_gamepad\" size=\"120\" maxlength=\"250\" value=\"".$ar['admin_hw_gamepad']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_HW_OS."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_hw_os\" size=\"120\" maxlength=\"250\" value=\"".$ar['admin_hw_os']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_HW_CONECTION."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_hw_conection\" size=\"120\" maxlength=\"250\" value=\"".$ar['admin_hw_conection']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_HW_BRAND_PC."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_hw_brand_pc\" size=\"120\" maxlength=\"250\" value=\"".$ar['admin_hw_brand_pc']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"nadpis\" align=\"left\" valign=\"top\" colspan=\"4\"><strong>"._ADMIN_INFO_GAME."</strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_GAME_RESOLUTION."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><select name=\"admin_game_resolution\" class=\"input\">\n";
		echo "			<option value=\"320x200\" "; if ($ar['admin_game_resolution'] == "320x200") {echo " selected";} echo ">320x200</option>\n";
		echo "			<option value=\"640x480\" "; if ($ar['admin_game_resolution'] == "640x480") {echo " selected";} echo ">640x480</option>\n";
		echo "			<option value=\"720x480\" "; if ($ar['admin_game_resolution'] == "720x480") {echo " selected";} echo ">720x480</option>\n";
		echo "			<option value=\"720x576\" "; if ($ar['admin_game_resolution'] == "720x576") {echo " selected";} echo ">720x576</option>\n";
		echo "			<option value=\"800x480\" "; if ($ar['admin_game_resolution'] == "800x480") {echo " selected";} echo ">800x480</option>\n";
		echo "			<option value=\"800x600\" "; if ($ar['admin_game_resolution'] == "800x600") {echo " selected";} echo ">800x600</option>\n";
		echo "			<option value=\"848x480\" "; if ($ar['admin_game_resolution'] == "848x480") {echo " selected";} echo ">848x480</option>\n";
		echo "			<option value=\"960x720\" "; if ($ar['admin_game_resolution'] == "960x720") {echo " selected";} echo ">960x720</option>\n";
		echo "			<option value=\"1024x480\" "; if ($ar['admin_game_resolution'] == "1024x480") {echo " selected";} echo ">1024x480</option>\n";
		echo "			<option value=\"1024x600\" "; if ($ar['admin_game_resolution'] == "1024x600") {echo " selected";} echo ">1024x600</option>\n";
		echo "			<option value=\"1024x768\" "; if ($ar['admin_game_resolution'] == "1024x768") {echo " selected";} echo ">1024x768</option>\n";
		echo "			<option value=\"1152x864\" "; if ($ar['admin_game_resolution'] == "1152x864") {echo " selected";} echo ">1152x864</option>\n";
		echo "			<option value=\"1200x900\" "; if ($ar['admin_game_resolution'] == "1200x900") {echo " selected";} echo ">1200x900</option>\n";
		echo "			<option value=\"1280x600\" "; if ($ar['admin_game_resolution'] == "1280x600") {echo " selected";} echo ">1280x600</option>\n";
		echo "			<option value=\"1280x768\" "; if ($ar['admin_game_resolution'] == "1280x768") {echo " selected";} echo ">1280x768</option>\n";
		echo "			<option value=\"1280x800\" "; if ($ar['admin_game_resolution'] == "1280x800") {echo " selected";} echo ">1280x800</option>\n";
		echo "			<option value=\"1280x960\" "; if ($ar['admin_game_resolution'] == "1280x960") {echo " selected";} echo ">1280x960</option>\n";
		echo "			<option value=\"1280x1024\" "; if ($ar['admin_game_resolution'] == "1280x1024") {echo " selected";} echo ">1280x1024</option>\n";
		echo "			<option value=\"1360x1020\" "; if ($ar['admin_game_resolution'] == "1360x1020") {echo " selected";} echo ">1360x1020</option>\n";
		echo "			<option value=\"1400x1050\" "; if ($ar['admin_game_resolution'] == "1400x1050") {echo " selected";} echo ">1400x1050</option>\n";
		echo "			<option value=\"1520x1140\" "; if ($ar['admin_game_resolution'] == "1520x1140") {echo " selected";} echo ">1520x1140</option>\n";
		echo "			<option value=\"1600x1200\" "; if ($ar['admin_game_resolution'] == "1600x1200") {echo " selected";} echo ">1600x1200</option>\n";
		echo "			<option value=\"1680x1050\" "; if ($ar['admin_game_resolution'] == "1680x1050") {echo " selected";} echo ">1680x1050</option>\n";
		echo "			<option value=\"1792x1344\" "; if ($ar['admin_game_resolution'] == "1792x1344") {echo " selected";} echo ">1792x1344</option>\n";
		echo "			<option value=\"1800x1440\" "; if ($ar['admin_game_resolution'] == "1800x1440") {echo " selected";} echo ">1800x1440</option>\n";
		echo "			<option value=\"1856x1392\" "; if ($ar['admin_game_resolution'] == "1856x1392") {echo " selected";} echo ">1856x1392</option>\n";
		echo "			<option value=\"1920x1080\" "; if ($ar['admin_game_resolution'] == "1920x1080") {echo " selected";} echo ">1920x1080</option>\n";
		echo "			<option value=\"1920x1200\" "; if ($ar['admin_game_resolution'] == "1920x1200") {echo " selected";} echo ">1920x1200</option>\n";
		echo "			<option value=\"1920x1440\" "; if ($ar['admin_game_resolution'] == "1920x1440") {echo " selected";} echo ">1920x1440</option>\n";
		echo "			<option value=\"2048x1536\" "; if ($ar['admin_game_resolution'] == "2048x1536") {echo " selected";} echo ">2048x1536</option>\n";
		echo "			</select></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_GAME_M_SENS."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_game_mouse_sens\" size=\"40\" maxlength=\"80\" value=\"".$ar[admin_game_mouse_sens]."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_GAME_M_ACCEL."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><select name=\"admin_game_mouse_accel\" class=\"input\">\n";
		echo "			<option value=\"1\" "; if ($ar['admin_game_mouse_accel'] == "1") {echo " selected";} echo ">"._CMN_YES."</option>\n";
		echo "			<option value=\"0\" "; if ($ar['admin_game_mouse_accel'] == "0" || $ar['admin_game_mouse_accel'] == "") {echo " selected";} echo ">"._CMN_NO."</option>\n";
		echo "			</select></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_GAME_M_INVERT."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><select name=\"admin_game_mouse_invert\" class=\"input\">\n";
		echo "			<option value=\"1\" "; if ($ar['admin_game_mouse_invert'] == "1") {echo " selected";} echo ">"._CMN_YES."</option>\n";
		echo "			<option value=\"0\" "; if ($ar['admin_game_mouse_invert'] == "0" || $ar['admin_game_mouse_invert'] == "") {echo " selected";} echo ">"._CMN_NO."</option>\n";
		echo "			</select></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"nadpis\" align=\"left\" valign=\"top\" colspan=\"4\"><strong>"._ADMIN_INFO_FAV."</strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_FAV_WPN."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_game_fav_weapon\" size=\"40\" maxlength=\"80\" value=\"".$ar['admin_game_fav_weapon']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_FAV_TEAM."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_game_fav_team\" size=\"40\" maxlength=\"80\" value=\"".$ar['admin_game_fav_team']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_FAV_MAP."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_game_fav_map\" size=\"40\" maxlength=\"80\" value=\"".$ar['admin_game_fav_map']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"nadpis\" colspan=\"4\">"._ADMIN_POKER."</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_POKER_FAV_VARIANTS."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><select name=\"admin_poker_fav_variants_1\" class=\"input\">\n";
						$fav_variants = explode ("||", $ar['admin_poker_fav_variants']);
		echo "			<option value=\"\" "; if ($fav_variants[0] == "") {echo " selected";} echo ">"._ADMIN_POKER_FAV_VARIANTS_SELECT."</option>\n";
						$res_variant = mysql_query("SELECT poker_variant_id, poker_variant_name FROM $db_poker_variants ORDER BY poker_variant_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($ar_variant = mysql_fetch_array($res_variant)){
							echo "<option value=\"".$ar_variant['poker_variant_id']."\""; if ($fav_variants[0] == $ar_variant['poker_variant_id']) {echo " selected";} echo ">".$ar_variant['poker_variant_name']."</option>\n";
						}
		echo "		</select></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\">&nbsp;</td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><select name=\"admin_poker_fav_variants_2\" class=\"input\">\n";
		echo "			<option value=\"\" "; if ($fav_variants[1] == "") {echo " selected";} echo ">"._ADMIN_POKER_FAV_VARIANTS_SELECT."</option><\n";
						$res_variant = mysql_query("SELECT poker_variant_id, poker_variant_name FROM $db_poker_variants ORDER BY poker_variant_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($ar_variant = mysql_fetch_array($res_variant)){
							echo "<option value=\"".$ar_variant['poker_variant_id']."\""; if ($fav_variants[1] == $ar_variant['poker_variant_id']) {echo " selected";} echo ">".$ar_variant['poker_variant_name']."</option>\n";
						}
		echo "		</select></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\">&nbsp;</td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><select name=\"admin_poker_fav_variants_3\" class=\"input\">\n";
		echo "			<option value=\"\" "; if ($fav_variants[2] == "") {echo " selected";} echo ">"._ADMIN_POKER_FAV_VARIANTS_SELECT."</option>\n";
						$res_variant = mysql_query("SELECT poker_variant_id, poker_variant_name FROM $db_poker_variants ORDER BY poker_variant_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($ar_variant = mysql_fetch_array($res_variant)){
							echo "<option value=\"".$ar_variant['poker_variant_id']."\""; if ($fav_variants[2] == $ar_variant['poker_variant_id']) {echo " selected";} echo ">".$ar_variant['poker_variant_name']."</option>\n";
						}
		echo "		</select></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_POKER_FAV_CARDROOMS."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><select name=\"admin_poker_fav_cardroom_1\" class=\"input\">\n";
						$fav_cardrooms = explode ("||", $ar['admin_poker_fav_cardrooms']);
		echo "			<option value=\"\" "; if ($fav_cardrooms[0] == "") {echo " selected";} echo ">"._ADMIN_POKER_FAV_CARDROOM_SELECT."</option>\n";
						$res_cardroom = mysql_query("SELECT poker_cardroom_id, poker_cardroom_name FROM $db_poker_cardrooms ORDER BY poker_cardroom_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($ar_cardroom = mysql_fetch_array($res_cardroom)){
							echo "<option value=\"".$ar_cardroom['poker_cardroom_id']."\""; if ($fav_cardrooms[0] == $ar_cardroom['poker_cardroom_id']) {echo " selected";} echo ">".$ar_cardroom['poker_cardroom_name']."</option>\n";
						}
		echo "		</select></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\">&nbsp;</td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><select name=\"admin_poker_fav_cardroom_2\" class=\"input\">\n";
		echo "			<option value=\"\" "; if ($fav_cardrooms[1] == "") {echo " selected";} echo ">"._ADMIN_POKER_FAV_CARDROOM_SELECT."</option>\n";
						$res_cardroom = mysql_query("SELECT poker_cardroom_id, poker_cardroom_name FROM $db_poker_cardrooms ORDER BY poker_cardroom_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($ar_cardroom = mysql_fetch_array($res_cardroom)){
							echo "<option value=\"".$ar_cardroom['poker_cardroom_id']."\""; if ($fav_cardrooms[1] == $ar_cardroom['poker_cardroom_id']) {echo " selected";} echo ">".$ar_cardroom['poker_cardroom_name']."</option>\n";
						}
		echo "		</select></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\">&nbsp;</td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><select name=\"admin_poker_fav_cardroom_3\" class=\"input\">\n";
		echo "			<option value=\"\" "; if ($fav_cardrooms[2] == "") {echo " selected";} echo ">"._ADMIN_POKER_FAV_CARDROOM_SELECT."</option>\n";
						$res_cardroom = mysql_query("SELECT poker_cardroom_id, poker_cardroom_name FROM $db_poker_cardrooms ORDER BY poker_cardroom_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($ar_cardroom = mysql_fetch_array($res_cardroom)){
							echo "<option value=\"".$ar_cardroom['poker_cardroom_id']."\""; if ($fav_cardrooms[2] == $ar_cardroom['poker_cardroom_id']) {echo " selected";} echo ">".$ar_cardroom['poker_cardroom_name']."</option>\n";
						}
		echo "			</select>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_POKER_FAV_PLAYER."</strong></td>\n";
		echo "		<td><input type=\"text\" name=\"admin_poker_fav_player\" size=\"40\" maxlength=\"60\" value=\"".htmlspecialchars($ar['admin_poker_fav_player'], ENT_QUOTES)."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_WEB." 1</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_contact_web\" size=\"40\" maxlength=\"250\" value=\"".$ar['admin_contact_web']."\"> www.address.com</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_WEB." 2</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_contact_web2\" size=\"40\" maxlength=\"250\" value=\"".$ar['admin_contact_web2']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_WEB." 3</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_contact_web3\" size=\"40\" maxlength=\"250\" value=\"".$ar['admin_contact_web3']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_WEB." 4</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"text\" name=\"admin_contact_web4\" size=\"40\" maxlength=\"250\" value=\"".$ar['admin_contact_web4']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"nadpis\" colspan=\"4\">"._ADMIN_OTHER."</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_USERINFO."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><textarea cols=\"40\" rows=\"5\" name=\"admin_userinfo\">".$ar['admin_userinfo']."</textarea></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_AGREE_EMAIL."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"3\"><input type=\"checkbox\" name=\"admin_agree_email\" value=\"1\" "; if ($ar['admin_agree_email'] == 1){echo "checked";} echo "></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"middle\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_FORUM_HITS."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"admin_hits\" size=\"5\" value=\"".$ar['admin_hits']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" bgcolor=\"#c0c0c0\"><strong>"._ADMIN_FILTERS."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
						$filter_selected = explode (",", $ar['admin_info_filter']);
						$res_filter = mysql_query("SELECT * FROM $db_filters ORDER BY filter_id") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($ar_filter = mysql_fetch_array($res_filter)){
							echo "<input type=\"checkbox\" name=\"admin_filter[]\" value=\"".$ar_filter['filter_shortname']."\" "; if (in_array($ar_filter['filter_shortname'],$filter_selected)){echo "checked";} echo "> ".$ar_filter['filter_name']."<br />\n";
						}
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"2\" align=\"left\" valign=\"top\" colspan=\"4\">\n";
		echo "			<input type=\"hidden\" name=\"search_by\" value=\"admin_id\">\n";
		echo "			<input type=\"hidden\" name=\"search_this\" value=\"".$_GET['id']."\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"hidden\" name=\"letter\" value=\"".$_GET['letter']."\">\n";
		echo "			<input type=\"hidden\" name=\"show_status\" value=\"".$_GET['show_status']."\">\n";
		echo "			<input type=\"hidden\" name=\"sa\" value=\"".$_GET['sa']."\">\n";
		echo "			<input type=\"hidden\" name=\"id\" value=\"".$ar['admin_id']."\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_SUBMIT."\" class=\"eden_button\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
}

/***********************************************************************************************************
*																											
*		ODSTRANOVANI ADMINISTRATORU																			
*																											
***********************************************************************************************************/
function DelAdmin(){
	
	global $db_admin,$db_admin_clan,$db_admin_contact,$db_admin_game,$db_admin_hw,$db_groups,$db_forum_pm;
	global $db_admin_contact_shop,$db_admin_info;
	global $eden_cfg;
	global $ftp_path_admins;
	global $url_admins;
	
	// Provereni privilegii
	if (CheckPriv("groups_admin_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	if ($_POST['confirm'] == "true"){
		$res = mysql_query("SELECT admin_uname, admin_userimage, admin_userimage2 FROM $db_admin WHERE admin_id=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		if ($ar['admin_userimage'] != "0000000001.gif"){
			$conn_id = ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP); 
			$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); 
			ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
			if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP;die;} 
			@ftp_delete($conn_id, $ftp_path_admins.$ar['admin_userimage']); // Odstrani se z FTP serveru
		}
		if ($ar['admin_userimage2'] != "0000000002.gif"){
			$conn_id = ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP); 
			$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); 
			ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
			if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP;die;} 
			@ftp_delete($conn_id, $ftp_path_admins.$ar['admin_userimage2']); // Odstrani se z FTP serveru
		}
		$res2 = mysql_query("SELECT forum_pm_del 
		FROM $db_forum_pm 
		WHERE forum_pm_recipient_id='".mysql_real_escape_string($ar['admin_uname'])."' OR forum_pm_author_id='".mysql_real_escape_string($ar['admin_uname'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		while ($ar2 = mysql_fetch_array($res2)) { // Kdyz je $z mensi nes pocet obrazku v danem clanku
			if ($ar2['forum_pm_del'] != $ar['admin_uname'] && $ar2['forum_pm_del'] != "NULL" && $a2r['forum_pm_del'] != ""){
				// Kdyz je dany prispevek zatrhnuty ke smazani
				mysql_query("DELETE 
				FROM $db_forum_pm 
				WHERE forum_pm_recipient_id='".mysql_real_escape_string($ar['admin_uname'])."' OR forum_pm_author_id='".mysql_real_escape_string($ar['admin_uname'])."' ") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Odstrani se ze zaznamu v databazi
			} else {
				mysql_query("UPDATE $db_forum_pm 
				SET forum_pm_del='".mysql_real_escape_string($ar['admin_uname'])."' 
				WHERE forum_pm_recipient_id='".mysql_real_escape_string($ar['admin_uname'])."' OR forum_pm_author_id='".mysql_real_escape_string($ar['admin_uname'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}
		}
		/* Zde muzou byt jen ty zaznamy ktere se automaticky vytvareji pro kazdeho uzivatele - jinak dojde k erroru pokazde kdyz dany zaznam dany uzivatel nema */
		mysql_query("DELETE FROM $db_admin WHERE admin_id=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		mysql_query("DELETE FROM $db_admin_clan WHERE aid=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		mysql_query("DELETE FROM $db_admin_contact WHERE aid=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		mysql_query("DELETE FROM $db_admin_contact_shop WHERE aid=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		mysql_query("DELETE FROM $db_admin_game WHERE aid=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		mysql_query("DELETE FROM $db_admin_hw WHERE aid=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		mysql_query("DELETE FROM $db_admin_info WHERE aid=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$_GET['action'] = "showbyletter";
		$_GET['msg'] = "ad_ok";
		Admins();
	}
	if ($_POST['confirm'] == "false"){Admins();}
	if ($_POST['confirm'] == ""){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" class=\"nadpis\">"._ADMINS." - "._ADMIN_DEL_ADMIN."</td>\n";
		echo "		<td align=\"right\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td><a href=\"sys_admin.php\"><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_MAINMENU."\"></a>\n";
		echo "			<a href=\"sys_admin.php?project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a></td>\n";
		echo "		<td align=\"right\">";
						$res = mysql_query("SELECT COUNT(*) FROM $db_admin") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$num = mysql_fetch_array($res);
						echo _REGISTEREDUSERS.": ".$num[0];
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr class=\"popisky\">\n";
		echo "		<td><span class=\"nadpis-boxy\">"._CMN_IMAGE."</span></td>\n";
		echo "		<td><span class=\"nadpis-boxy\">ID</span></td>\n";
		echo "		<td><span class=\"nadpis-boxy\">"._ADMIN_USERNAME."</span></td>\n";
		echo "		<td><span class=\"nadpis-boxy\">"._NAME."</span></td>\n";
		echo "		<td><span class=\"nadpis-boxy\">"._EMAIL."</span></td>\n";
		echo "		<td><span class=\"nadpis-boxy\">"._ADMIN_PRIVILEGES."</span></td>\n";
		echo "	</tr>";
			$res = mysql_query("SELECT admin_id, admin_uname, admin_name, admin_email, admin_userimage, admin_priv 
			FROM $db_admin 
			WHERE admin_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while ($ar = mysql_fetch_array($res)){
				if ($ar['admin_userimage']== ""){$image = "0000000001.gif";} else {$image = $ar['admin_userimage'];}
				$res2 = mysql_query("SELECT groups_name 
				FROM $db_groups 
				WHERE groups_id=".$ar['admin_priv']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar2 = mysql_fetch_array($res2);
				echo "<tr>";
				echo "	<td width=\"55\" align=\"center\" valign=\"top\"><img src=\"".$url_admins.$image."\" border=\"0\" alt=\"\"></td>";
				echo "	<td valign=\"top\">".$ar['admin_id']."</td>";
				echo "	<td valign=\"top\">".$ar['admin_uname']."</td>";
				echo "	<td valign=\"top\">".$ar['admin_name']."</td>";
				echo "	<td valign=\"top\">".$ar['admin_email']."</td>";
				echo "	<td align=\"left\" valign=\"top\">".$ar2['groups_name']."</td>";
				echo "</tr>";
 			}
		echo "</table><br><br>";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._ADMIN_CHECKDEL."</span></strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td width=\"50\" align=\"left\" valign=\"top\">\n";
		echo "			<form action=\"sys_admin.php?action=admins_del&amp;show_status=".$_GET['show_status']."\" method=\"post\">\n";
		echo "			<input type=\"submit\" value=\""._ADMIN_DEL_ADMIN."\" class=\"eden_button\"><br>\n";
		echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"hidden\" name=\"letter\" value=\"".$_GET['letter']."\">\n";
		echo "			<input type=\"hidden\" name=\"show_status\" value=\"".$_GET['show_status']."\">\n";
		echo "			<input type=\"hidden\" name=\"sa\" value=\"".$_GET['sa']."\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "		<td width=\"800\" align=\"left\" valign=\"top\">\n";
		echo "			<form action=\"sys_admin.php?action=admins_del&amp;show_status=".$_GET['show_status']."\" method=\"post\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
		echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"false\">\n";
		echo "			<input type=\"hidden\" name=\"letter\" value=\"".$_GET['letter']."\">\n";
		echo "			<input type=\"hidden\" name=\"show_status\" value=\"".$_GET['show_status']."\">\n";
		echo "			<input type=\"hidden\" name=\"sa\" value=\"".$_GET['sa']."\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
 	}
}
/***********************************************************************************************************
*																											
*		SPRAVA OBRAZKU																						
*																											
***********************************************************************************************************/
function ManagePicture(){
	
	global $db_admin;
	global $eden_cfg;
	global $ftp_path_admins;
	
		// CHECK PRIVILEGIES
	if (CheckPriv("groups_admin_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	// Zjisteni poctu obrazku v adresari na serveru klienta
	$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP); 
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); 
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	if ((!$conn_id) || (!$login_result)) { 
	   	echo _ERROR_FTP;
	   	die; 
	}
	$b = ftp_nlist($conn_id, $ftp_path_admins); // Ulozeni jednotlivych nazvu souboru do pole $b
	$d1 = count($b);
	$d2 = count($b)-2; // To minus 2 je pro odstraneni . a .. ze zobrazeni
	
	// Zjisteni poctu obrazku zapsanych v databazi
	$res = mysql_query("SELECT * FROM $db_admin") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_num_rows($res);
	$j = 0;
	while ($ar2 = mysql_fetch_array($res)){
		$x[$j] = $ar2['admin_userimage']; // Ulozeni nazvu jednotlivych souboru do pole $x
		$j++;
	}
	
	// Porovnani dvou poli (vysledek je pole s polozkami, ktere se nevyskytuji v druhem poli - v nasem pripade v databazi)
	if (isset ($x) && isset ($b)){$result = array_diff ($b, $x);}
	// Odstraneni obrazku, ktere nejsou v databazi ze serveru
	if ($deletepicture == "check"){
		$z = 2;
		while($z < $d1){
			if ($result[$z] != "" && $result[$z] != "0000000001.gif"){// Zamezeni odstraneni default obrazku
				@ftp_delete($conn_id, $ftp_path_admins.$result[$z]);
			}
		$z++;
		}
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._ADMINS." - "._ARTICLESMANAGEPIC."</td>\n";
	echo "		<td align=\"right\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><a href=\"sys_admin.php?project=".$_SESSION['project']."\"><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_MAINMENU."\">"._CMN_MAINMENU."</a></td>\n";
	echo "		<td align=\"right\"></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"857\" height=\"50\">&nbsp;</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"857\">"._ARTICLESMANAGEPICLIST."<br><br>";
				// Vypis souboru, ktere nejsou v databazi
				$z = 2;
				while($z < $d1){
					if ($result[$z] != "" && $result[$z] != "0000000001.gif"){// Zamezeni zobrazeni default obrazku
						echo $result[$z];
						echo "<br>";
					}
				$z++;
				}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"857\" height=\"20\">&nbsp;</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"857\">"._ARTICLESMANAGEPICSUMADB."&nbsp;&nbsp;".$num."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"857\">"._ARTICLESMANAGEPICSUMADIR."&nbsp;&nbsp;".$d2."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"857\"><br>\n";
	echo "			<form action=\"sys_admin.php?action=managepicture\" method=\"post\">\n";
	echo "			<input type=\"hidden\" name=\"deletepicture\" value=\"check\">\n";
	echo "			<input type=\"hidden\" name=\"show_status\" value=\"".$_GET['show_status']."\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"submit\" value=\""._ARTICLESMANAGEPICDEL."\" class=\"eden_button\">\n";
	echo "			</form></td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		PRIDAVANI A EDITACE KATEGORII																		
*																											
***********************************************************************************************************/
function AddCategory(){
	
	global $db_admin_category;
	global $eden_cfg;
	global $ftp_path_admins_category,$preload;
	global $url_admins_category;
	
	if ($_GET['action'] == "admins_cat_add"){
		if (CheckPriv("groups_admin_add") <> 1) { echo _NOTENOUGHPRIV;ShowCategory();exit;}
	}elseif ($_GET['action'] == "admins_cat_edit"){
		if (CheckPriv("groups_admin_edit") <> 1) { echo _NOTENOUGHPRIV;ShowCategory();exit;}
	} else {
		echo _NOTENOUGHPRIV;ShowCategory();exit;
	}
	
	if ($_POST['confirm'] <> "true"){
		if ($_GET['action'] == "admins_cat_edit"){
			$res = mysql_query("SELECT admin_category_shortname, admin_category_topictext, admin_category_comment, admin_category_topicimage, admin_category_shows 
			FROM $db_admin_category 
			WHERE admin_category_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar = mysql_fetch_array($res);
			$ar['admin_category_topictext'] = str_ireplace( "&quot;","\"",$ar['admin_category_topictext']);
			$ar['admin_category_topictext'] = str_ireplace( "&acute;","'",$ar['admin_category_topictext']);
		}
		// Spojeni s FTP serverem 
		$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP); 
		// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
		$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); 
		ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
		// Zjisteni stavu spojeni
		if ((!$conn_id) || (!$login_result)) { 
   			echo _ERROR_FTP;
    		die; 
		}
		echo "<SCRIPT type=\"text/javascript\">\n";
		echo "<!--\n";
		echo "  var _img = new Array();\n";
				$d = ftp_nlist($conn_id, $ftp_path_admins_category);
				$x=0;
				while($entry=$d[$x]){
					$x++;
					$entry = str_ireplace($ftp_path_admins_category,"",$entry); //Odstrani cestu k ftp adresari
					if ($entry != "." && $entry != ".."){
					    echo "_img[".$x."] = new Image(); _img[".$x."].src=\"".$url_admins_category.$entry."\";";
					}
				}
		echo "	function doIt(_obj){\n";
		echo "	  if (!_obj)return;\n";
		echo "	  var _index = _obj.selectedIndex;\n";
		echo "	  if (!_index)return;\n";
		echo "	  var _item = _obj[_index].id;\n";
		echo "	  if (!_item)return;\n";
		echo "	  if (_item<0 || _item >= _img.length)return;\n";
		echo "	  document.images[\"obrazek\"].src=_img[_item].src;\n";
		echo "	}\n";
		echo "	//-->\n";
		echo "</script>";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">"; if ($_GET['action'] == "admins_cat_add"){echo _ADMIN_CAT_ADD;} else {echo _ADMIN_CAT_EDIT." - ".$_GET['id'];} echo "</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\"><a href=\"sys_admin.php?action=admins&amp;project=".$_SESSION['project']."\">"._ADMIN_ADMINS."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
		echo "			<a href=\"sys_admin.php?action=showcategory&amp;project=".$_SESSION['project']."\">"._ADMIN_SHOW_CATEGORY."</a></td>\n";
		echo "		<td align=\"left\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<br>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><form action=\"sys_save.php?action="; if ($_GET['action'] == "admins_cat_edit"){echo "admins_cat_edit";} else {echo "admins_cat_add";} echo "\" method=\"post\" name=\"forma\"><strong>"._ADMIN_CAT_SHORTNAME."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"shortname\" maxlength=\"20\" value=\"".$ar['admin_category_shortname']."\" size=\"60\"> "._ADMIN_CAT_SHORTNAME_HELP."</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ADMIN_CAT_NAME."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"category_name\" value=\"".$ar['admin_category_topictext']."\" size=\"60\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CAT_SHOW."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"checkbox\" name=\"shows\" value=\"1\" "; if ($ar['admin_category_shows'] == 1 || !isset($ar['admin_category_shows'])){echo "checked";} echo "></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ADMIN_COMMENT."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><textarea name=\"comment\" rows=\"6\" cols=\"80\">".$ar['admin_category_comment']."</textarea></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CMN_IMAGE.$entry."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><select name=\"picture\" size=\"5\" onclick=\"doIt(this)\">";
						$d = ftp_nlist($conn_id, $ftp_path_admins_category);
						$x=0;
						echo "<option value=\"0\">"._ADMIN_CHOOSE_IMAGE."</option>\n";
						while($entry=$d[$x]) {
							$x++;
	             				$entry = str_ireplace ($ftp_path_admins_category,"",$entry); //Odstrani cestu k ftp adresari
							if ($entry != "." && $entry != "..") {
								echo "<option id=\"".$x."\" value=\"".$entry."\""; if ($ar['admin_category_topicimage'] == $entry){echo "selected=\"selected\"";} echo ">".$entry."</option>\n";
							}
						}
						ftp_close($conn_id);
		echo "			</select>&nbsp;&nbsp;\n";
		echo "			<img name=\"obrazek\" src=\""; if ($ar['admin_category_topicimage'] != ""){echo $url_admins_category.$ar['admin_category_topicimage'];} else {echo $url_admins_category."AllTopics.gif";} echo "\" border=\"0\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"2\">\n";
		echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_SUBMIT."\" class=\"eden_button\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
}
/***********************************************************************************************************
*																											
*		ZOBRAZENI KATEGORIE																					
*																											
***********************************************************************************************************/
function ShowCategory(){
	
	global $db_admin_category;
	global $url_admins_category;
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">"._ADMIN_CATEGORY."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\"><a href=\"sys_admin.php?action=admins&amp;project=".$_SESSION['project']."\">"._ADMIN_ADMINS."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	echo "			<a href=\"sys_admin.php?action=admins_cat_add&amp;project=".$_SESSION['project']."\">"._ADMIN_ADD_CATEGORY."</a></td>\n";
	echo "		<td align=\"left\">&nbsp;</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_IMAGE."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._ADMIN_CAT_SHORTNAME."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._ADMIN_CAT_NAME."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._ADMIN_SHOWS."</span></td>\n";
	echo "	</tr>";
	//***********************************************************************************
	// Hlavni Menu
	//***********************************************************************************
	$i=0;
	$res = mysql_query("SELECT admin_category_id, admin_category_topicimage, admin_category_shortname, admin_category_topictext, admin_category_comment, admin_category_shows 
	FROM $db_admin_category 
	ORDER BY admin_category_shows DESC, admin_category_topictext ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar = mysql_fetch_array($res)){
		$hlavicka = stripslashes($ar['admin_category_topictext']);
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		if ($ar['admin_category_shows'] == 0){$cat_class = "cat_over";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td align=\"left\" valign=\"middle\">";
				if (CheckPriv("groups_admin_edit") == 1){echo "<a href=\"sys_admin.php?action=admins_cat_edit&amp;id=".$ar['admin_category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
				if (CheckPriv("groups_admin_del") == 1){echo "<a href=\"sys_admin.php?action=admins_cat_del&amp;id=".$ar['admin_category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
		echo "	</td>";
		echo "	<td align=\"center\" valign=\"middle\"><strong>".$ar['admin_category_id']."</strong></td>";
		echo "	<td align=\"center\" valign=\"middle\"><img src=\"".$url_admins_category.$ar['admin_category_topicimage']."\"></td>";
		echo "	<td align=\"left\" valign=\"middle\">&nbsp;<strong>".$ar['admin_category_shortname']."</strong></td>";
		echo "	<td align=\"left\" valign=\"middle\">&nbsp;<strong><a href=\"sys_admin.php?action=".$command."&amp;id=".$ar['admin_category_id']."&amp;project=".$_SESSION['project']."\" title=\"".$ar['admin_category_comment']."\">".$hlavicka."</a></strong> </td>";
		echo "	<td align=\"center\" valign=\"middle\"><img src=\"images/sys_"; if ($ar['admin_category_shows'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></strong></td>";
		echo "</tr>";
		$i++;
	}
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		MAZANI KATEGORII																					
*																											
***********************************************************************************************************/
function DelCategory(){
	
	global $db_admin_category;
	global $url_category;
	
	if ($_POST['confirm'] == "false"){
		ShowCategory();
	}
	if ($_POST['confirm'] == "")	{
		$res = mysql_query("SELECT admin_category_topictext, admin_category_topicimage FROM $db_admin_category WHERE admin_category_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">"._ADMIN_CAT_DEL."</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td><a href=\"sys_admin.php?action=admins&amp;project=".$_SESSION['project']."\">"._ADMIN_ADMINS."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
		echo "			<a href=\"sys_admin.php?action=showcategory&amp;project=".$_SESSION['project']."\">"._ADMIN_SHOW_CATEGORY."</a></td>\n";
		echo "		<td align=\"left\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr class=\"popisky\">\n";
		echo "		<td><span class=\"nadpis-boxy\">"._CMN_IMAGE."</span></td>\n";
		echo "		<td><span class=\"nadpis-boxy\">"._NAME."</span></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" width=\"100\"><img src=\"".$url_category.$ar['admin_category_topicimage']."\"></td>\n";
		echo "		<td valign=\"top\">".$ar['admin_category_topictext']."</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._ADMIN_CAT_DEL_CHECK."</span></strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" width=\"50\">\n";
		echo "		<form action=\"sys_save.php?action=admins_cat_del\" method=\"post\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
		echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			</form>\n";
		echo "			\n";
		echo "		</td>\n";
		echo "		<td valign=\"top\" width=\"800\">\n";
		echo "			<form action=\"sys_admin.php?action=admins_cat_del\" method=\"post\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"false\"></form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
}
include ("inc.header.php");
	if ($_GET['action'] == "" || $_GET['action'] == "showmain"){ ShowMain();}
	if ($_GET['action'] == "edit_priv" || $_GET['action'] == "admins" || $_GET['action'] == "showbyletter") { Admins();}
	if ($_GET['action'] == "admins_edit") { AddUser();}
	if ($_GET['action'] == "admins_del") { DelAdmin();}
	if ($_GET['action'] == "admins_add") { AddUser();}
	if ($_GET['action'] == "managepicture") { ManagePicture();}
	if ($_GET['action'] == "showcategory") { ShowCategory();}
	if ($_GET['action'] == "admins_cat_add") { AddCategory();}
	if ($_GET['action'] == "admins_cat_edit") { AddCategory();}
	if ($_GET['action'] == "admins_cat_del") { DelCategory();}
	include ("inc.footer.php");