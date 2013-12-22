<?php
/***********************************************************************************************************
*
*		MENU
*
***********************************************************************************************************/
function Menu (){
	
	switch ($_GET['action']){
		case "compare_cat":
			$title = _COMPARE_CAT;
			break;
   		case "compare_cat_add":
			$title = _COMPARE_CAT_ADD;
			break;
		case "compare_cat_edit":
			$title = _COMPARE_CAT_EDIT;
			break;
		case "compare_cat_del":
			$title = _COMPARE_CAT_DEL;
			break;
		case "compare_maker_add":
			$title = _COMPARE_MAKER_ADD;
			break;
		case "compare_maker_edit":
			$title = _COMPARE_MAKER_EDIT;
			break;
		case "compare_maker_del":
			$title = _COMPARE_MAKER_DEL;
			break;
		case "compare_ntb_add":
			$title = _COMPARE_NTB_ADD;
			break;
		case "compare_ntb_edit":
			$title = _COMPARE_NTB_EDIT;
			break;
		case "compare_ntb_del":
			$title = _COMPARE_NTB_DEL;
			break;
		case "compare_part_add":
			$title = _COMPARE_PART_ADD;
			break;
		case "compare_part_edit":
			$title = _COMPARE_PART_EDIT;
			break;
		case "compare_part_del":
			$title = _COMPARE_PART_DEL;
			break;
		default:
			$title = _COMPARE;
	}
	
	$menu = "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" border=\"0\" class=\"eden_main_table\">\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">".$title."</td>\n";
	$menu .= "	</tr>\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\">\n";
	$menu .= "			<a href=\"modul_compare.php?action=compare_maker_add&amp;project=".$_SESSION['project']."\">"._COMPARE_MAKERS."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	$menu .= "			<a href=\"modul_compare.php?action=compare_cat&amp;project=".$_SESSION['project']."\">"._COMPARE_CAT."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	$menu .= "			<a href=\"modul_compare.php?action=compare_part_add&amp;project=".$_SESSION['project']."\">"._COMPARE_PARTS."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	$menu .= "			<a href=\"modul_compare.php?action=compare_ntb_add&amp;project=".$_SESSION['project']."\">"._COMPARE_NTB."</a>\n";
	$menu .= "		<td align=\"right\"></td>\n";
	$menu .= "	</tr>\n";
	if ($_GET['msg']){
		$menu .= "<tr><td class=\"msg\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	$menu .= "</table>\n";
	
	return $menu;
}
/***********************************************************************************************************
*
*		SHOW MAIN
*
***********************************************************************************************************/
function ShowMain(){
	
	global $db_compare_categories,$db_compare_makers,$db_compare_ntb,$db_compare_parts,$db_compare_txt;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" border=\"0\" class=\"eden_main_table\">\n";
	echo "	<tr valign=\"top\">";
	echo "		<td width=\"90%\" valign=\"top\" colspan=\"2\">";
	echo "		<br>";
		  		$res1 = mysql_query("SELECT COUNT(*) FROM $db_articles WHERE article_public=0 AND article_publish=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		  		$ar1 = mysql_fetch_array($res1);
		  		
		  		if (empty($ar1[0])) {$ar1[0]=0;}
 		  		
		  		$nfo = mysql_query("SELECT admin_info FROM $db_admin WHERE admin_id=".$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		  		$inf = mysql_fetch_array($nfo);
		  		$lst = explode("#", $inf[0]);
	echo "			<br>\n";
	echo "			<table cellspacing=\"1\" cellpadding=\"5\" bgcolor=\"#DDDDDD\" align=\"center\">\n";
	echo "				<tr bgcolor=\"#EEEEEE\">\n";
	echo "					<td><strong>"._STAT_STATISTICS."</strong></td>\n";
	echo "					<td><strong>"._STAT_NEWS_STATE."</strong></td>\n";
	echo "					<td><strong>"._STAT_LASTVISIT."</strong></td>\n";
	echo "					<td><strong>"._STAT_NEW."</strong></td>\n";
	echo "					<td><strong>"._STAT_WAIT_FOR_ALLOW."</strong></td>\n";
	echo "				</tr>\n";
		  				if ($eden_cfg['modul_news'] == "1") {
		  					echo "<tr bgcolor=\"#FFFFFF\">\n";
		  					echo "	<td>"._STAT_NEWS."</td>\n";
		  					echo "	<td align=\"right\"><strong>".$ar9[0]."</strong></td>\n";
		  					echo "	<td align=\"right\"><strong>".$lst[8]."</strong></td>\n";
		  					echo "	<td align=\"right\"><strong>"; echo $ar9[0] - $lst[8]; echo "</strong></td>\n";
		  					echo "	<td align=\"right\"><strong>&nbsp;</strong></td>\n";
		  					echo "</tr>\n";
		  				}
		  				if ($eden_cfg['modul_articles'] == "1") {
		  					echo "<tr bgcolor=\"#FFFFFF\">\n";
		  					echo "	<td>"._STAT_ARTICLES."</td>\n";
		  					echo "	<td align=\"right\"><strong>".$ar1[0]."</strong></td>\n";
		  					echo "	<td align=\"right\"><strong>".$lst[0]."</strong></td>\n";
		  					echo "	<td align=\"right\"><strong>"; echo $ar1[0] - $lst[0]; echo "</strong></td>\n";
		  					echo "	<td align=\"right\"><strong>&nbsp;</strong></td>\n";
		  					echo "</tr>";
		  				}
	echo "			</table>\n";
	echo "			<br>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}
/***********************************************************************************************************
*
*		SHOW NOTEBOOKS
*
***********************************************************************************************************/
function NtbShow(){
	
	global $db_compare_ntb,$db_compare_parts;
	/*
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	if (!isset($hits)){$hits = 30;} // Nastaveni poctu zaznamu na strance
	$res6 = mysql_query("SELECT * FROM $db_compare_ntb") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num6 = mysql_num_rows($res6);
	// Jestlize neni vybrano podle ceho se ma tridit, je vybrano podle datumu setupne
	if ($ser == "" && $podle == ""){
		$podle = "vyrobce";
		$ser = "ASC";
	}
	if ($podle2 == ""){$podle2 = "typ";}
	if ($podle3 == ""){$podle3 = "cpu";}
	if ($podle4 == ""){$podle4 = "display";}
	if ($podle5 == ""){$podle5 = "cena";}
	if ($search == "true"){ //Pokud je aktivovan formular pro hledani zacne se hledat

		$where_formula = "";
		if ($vyrobce != ""){$where_formula .= " AND vyrobce='$vyrobce'";}
		if ($typ != ""){$where_formula .= " AND typ='$typ'";}
		if ($cpu != ""){$where_formula .= " AND cpu='$cpu'";}
		if ($display != ""){$where_formula .= " AND display='$display'";}
		if ($display_res != ""){$where_formula .= " AND display_res='$display_res'";}
		if ($vga != ""){$where_formula .= " AND vga='$vga'";}
		if ($firewire != ""){$where_formula .= " AND firewire > 0";}
		if ($hdd != ""){$where_formula .= " AND hdd='$hdd'";}
		if ($irda != ""){$where_formula .= " AND irda > 0";}
		if ($tvout!= ""){$where_formula .= " AND tv_out > 0";}
		if ($wifi != ""){$where_formula .= " AND wifi > 0";}
		if ($bluetooth != ""){$where_formula .= " AND bluetooth>'0'";}

		$res = mysql_query("SELECT * FROM $db_compare_ntb WHERE lang='cs' ".$where_formula." ORDER BY $podle $ser, $podle2 $ser, $podle3 $ser, $podle4 $ser, $podle5 $ser") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num = mysql_num_rows($res);
	} else {
		$res = mysql_query("SELECT * FROM $db_compare_ntb ORDER BY $podle $ser, $podle2 $ser, $podle3 $ser, $podle4 $ser, $podle5 $ser") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num = mysql_num_rows($res);
	}
	//Timto nastavime pocet prispevku na strance
	$m=0;// nastaveni iterace
	if (empty($page)) {$page=1;} // i kdyz je promenna $page prazdna nastavi se pocet starnek na 1
	//$hits=20; //Zde se nastavuje pocet prispevku
	$stw2 = ($num/$hits);
	$stw2 = (integer) $stw2;
	if ($num%$hits > 0) {$stw2++;}
	$np = $page+1;
	$pp = $page-1;
	if ($page == 1) { $pp=1; }
	if ($np > $stw2) { $np = $stw2;}

	$sp=($page-1)*$hits;
	$ep=($page-1)*$hits+$hits;
*/
	echo Menu();

	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" border=\"0\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\">\n";
	echo "			<form name=\"forma\" action=\"modul_compare.php\" method=\"post\">\n";
	echo "				<select size=\"1\" name=\"vyrobce\">";
 				$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='VYROBCE' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<option value=\"\"";
					if ($vyrobce == ""){echo " selected";}
					echo ">Znacka</option>\n";
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\"";
						if ($ar2[id] == $vyrobce){echo "selected";}
						echo ">".$ar2[nazev]."</option>\n";
					}
					echo "</select><br>\n";
					echo "	<select size=\"1\" name=\"cpu\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='CPU' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<option value=\"\"";
					if ($cpu == ""){echo " selected";}
					echo ">CPU</option>\n";
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\"";
						if ($ar2[id] == $cpu){echo "selected";}
						echo ">".$ar2[nazev]."</option>\n";
					}
					echo "	</select>\n";
					echo "</td>\n";
					echo "<td align=\"left\">\n";
					echo "		<select size=\"1\" name=\"hdd\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='HDD' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<option value=\"\"";
					if ($hdd == ""){echo " selected";}
					echo ">HDD</option>\n";
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\"";
						if ($ar2[id] == $hdd){echo "selected";}
						echo ">".$ar2[nazev]."</option>\n";
					}
					echo "</select><br>\n";
					echo "	<select size=\"1\" name=\"display\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='LCD' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<option value=\"\"";
					if ($display == ""){echo " selected";}
					echo ">Display</option>\n";
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\"";
						if ($ar2[id] == $display){echo "selected";}
						echo ">".$ar2[nazev]."</option>\n";
					}
					echo "	</select>\n";
					echo "</td>\n";
					echo "<td align=\"left\">\n";
					echo "		<select size=\"1\" name=\"display_res\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='LCDROZ' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<option value=\"\"";
					if ($display_res == ""){echo " selected";}
					echo ">Rozlišení</option>\n";
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\"";
						if ($ar2[id] == $display_res){echo "selected";}
						echo ">".$ar2[nazev]."</option>\n";
					}
					echo "</select><br>\n";
					echo "	<select size=\"1\" name=\"vga\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='VGA' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<option value=\"\"";
					if ($vga == ""){echo " selected";}
					echo ">VGA</option>\n";
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\"";
						if ($ar2[id] == $vga){echo "selected";}
						echo ">".$ar2[nazev]."</option>\n";
					}
		echo "		</select>\n";
		echo "	</td>\n";
		echo "	<td align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Firewire<br>\n";
		echo "		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IrDA<br>\n";
		echo "	</td>\n";
		echo "	<td align=\"left\"><input type=\"checkbox\" name=\"firewire\""; if ($firewire != ""){echo "checked";} echo " value=\"1\">&nbsp;&nbsp;&nbsp;&nbsp;<br>\n";
		echo "		<input type=\"checkbox\" name=\"irda\""; if ($irda != ""){echo "checked";} echo " value=\"1\">&nbsp;&nbsp;&nbsp;&nbsp;<br>\n";
		echo "	</td>\n";
		echo "	<td align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TV Out<br>\n";
		echo "		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wi-Fi<br>\n";
		echo "	</td>\n";
		echo "	<td align=\"left\"><input type=\"checkbox\" name=\"tvout\""; if ($tvout != ""){echo "checked";} echo " value=\"1\">&nbsp;&nbsp;&nbsp;&nbsp;<br>\n";
		echo "		<input type=\"checkbox\" name=\"wifi\""; if ($wifi != ""){echo "checked";} echo " value=\"1\">&nbsp;&nbsp;&nbsp;&nbsp;<br>\n";
		echo "	</td>\n";
		echo "	<td align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BlueTooth<br>\n";
		echo "		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Net<br>\n";
		echo "	</td>\n";
		echo "	<td align=\"left\"><input type=\"checkbox\" name=\"bluetooth\""; if ($bluetooth != ""){echo "checked";} echo " value=\"1\">&nbsp;&nbsp;&nbsp;&nbsp;<br>\n";
		echo "		<input type=\"checkbox\" name=\"net\""; if ($net != ""){echo "checked";} echo " value=\"1\">&nbsp;&nbsp;&nbsp;&nbsp;<br>\n";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"left\" colspan=\"4\">\n";
		echo "		<input type=\"hidden\" value=\"search\" name=\"action\">\n";
		echo "		<input type=\"hidden\" value=\"true\" name=\"search\">\n";
		echo "		<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "		<input type=\"submit\" value=\"". _CMN_SUBMIT."\" class=\"eden_button\">\n";
		echo "		</form>\n";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "<tr class=\"popisky\">\n";
		echo "	<td width=\"80\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
		echo "	<td width=\"30\" align=\"center\"><span class=\"nadpis-boxy\">"._PICTURE."</span></td>\n";
		echo "	<td width=\"100\" align=\"center\""; if ($podle == "znacka"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">"._COMPARE_ZNACKA."</span></td>\n";
		echo "	<td width=\"100\" align=\"center\""; if ($podle == "typ"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">"._COMPARE_TYP."</span></td>\n";
		echo "	<td width=\"120\" align=\"center\""; if ($podle == "cpu"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">"._COMPARE_CPU."</span></td>\n";
		echo "	<td width=\"60\" align=\"center\""; if ($podle == "display"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">"._COMPARE_DISP."</span></td>\n";
		echo "	<td width=\"80\" align=\"center\""; if ($podle == "vga"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">"._COMPARE_VGA."</span></td>\n";
		echo "	<td width=\"60\" align=\"center\""; if ($podle == "hdd"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">"._COMPARE_HDD."</span></td>\n";
		echo "	<td width=\"80\" align=\"center\""; if ($podle == "cena"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\">"._COMPARE_CENA."</span></td>\n";
		echo "</tr>\n";
		echo "<tr class=\"popisky\">\n";
		echo "	<td width=\"80\">&nbsp;</td>\n";
		echo "	<td width=\"30\" align=\"center\">&nbsp;</td>\n";
		echo "	<td width=\"100\" align=\"center\""; if ($podle == "znacka"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_compare.php?project=".$_SESSION['project']."&podle=znacka&ser=asc&amp;hits=".$hits."\"><img src=\"images/asc.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_compare.php?project=".$_SESSION['project']."&podle=znacka&ser=desc&amp;hits=".$hits."\"><img src=\"images/des.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
		echo "	<td width=\"100\" align=\"center\""; if ($podle == "typ"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_compare.php?project=".$_SESSION['project']."&podle=typ&ser=asc&amp;hits=".$hits."\"><img src=\"images/asc.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_compare.php?project=".$_SESSION['project']."&podle=typ&ser=desc&amp;hits=".$hits."\"><img src=\"images/des.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
		echo "	<td width=\"120\" align=\"center\""; if ($podle == "cpu"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_compare.php?project=".$_SESSION['project']."&podle=cpu&ser=asc&amp;hits=".$hits."\"><img src=\"images/asc.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_compare.php?project=".$_SESSION['project']."&podle=cpu&ser=desc&amp;hits=".$hits."\"><img src=\"images/des.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
		echo "	<td width=\"60\" align=\"center\""; if ($podle == "display"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_compare.php?project=".$_SESSION['project']."&podle=display&ser=asc&amp;hits=".$hits."\"><img src=\"images/asc.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_compare.php?project=".$_SESSION['project']."&podle=display&ser=desc&amp;hits=".$hits."\"><img src=\"images/des.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
		echo "	<td width=\"80\" align=\"center\""; if ($podle == "vga"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_compare.php?project=".$_SESSION['project']."&podle=vga&ser=asc&amp;hits=".$hits."\"><img src=\"images/asc.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_compare.php?project=".$_SESSION['project']."&podle=vga&ser=desc&amp;hits=".$hits."\"><img src=\"images/des.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
		echo "	<td width=\"60\" align=\"center\""; if ($podle == "hdd"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_compare.php?project=".$_SESSION['project']."&podle=hdd&ser=asc&amp;hits=".$hits."\"><img src=\"images/asc.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_compare.php?project=".$_SESSION['project']."&podle=hdd&ser=desc&amp;hits=".$hits."\"><img src=\"images/des.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
		echo "	<td width=\"80\" align=\"center\""; if ($podle == "cena"){echo "bgcolor=\"#FFDEDF\"";} echo "><span class=\"nadpis-boxy\"><a href=\"modul_compare.php?project=".$_SESSION['project']."&podle=cena&ser=asc&amp;hits=".$hits."\"><img src=\"images/asc.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_compare.php?project=".$_SESSION['project']."&podle=cena&ser=desc&amp;hits=".$hits."\"><img src=\"images/des.gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\"></a></span></td>\n";
		echo "</tr>";
		while ($ar = mysql_fetch_array($res)){
			$res_znacka =  mysql_query("SELECT * FROM $db_compare_parts WHERE id='$ar[vyrobce]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_znacka = mysql_fetch_array($res_znacka);
			$res_cpu =  mysql_query("SELECT * FROM $db_compare_parts WHERE id='$ar[cpu]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_cpu = mysql_fetch_array($res_cpu);
			$res_display =  mysql_query("SELECT * FROM $db_compare_parts WHERE id='$ar[display]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_display = mysql_fetch_array($res_display);
			$res_vga =  mysql_query("SELECT * FROM $db_compare_parts WHERE id='$ar[vga]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_vga = mysql_fetch_array($res_vga);
			$res_hdd =  mysql_query("SELECT * FROM $db_compare_parts WHERE id='$ar[hdd]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_hdd = mysql_fetch_array($res_hdd);
			$m++;
			if ($m>$sp and $m<=$ep){ //Spravne nastaveni poctu zobrazeni na strance
				$czech_format_cena = number_format($ar[cena], 0, ',', ' '); //Spravne formatovani ceny
				/*// Zabezpeceni zobrazeni moznosti jen vyvolenym
				if ($_GET['action'] == "open" & $id == $ar[id]) {$command = "close";} else {$command = "open";}
				$admini = explode (" ", $ar2[admin]);
				$num02 = count($admini);
				if ($_SESSION[login] == ""){$admini02 = "FALSE";} else {$admini02 = in_array($_SESSION[login], $admini);}*/
				echo "<tr style=\"background-color:#FFDEDF;\" onmouseover=\"this.style.backgroundColor='FFFFFF'\" onmouseout=\"this.style.backgroundColor='FFDEDF'\">\n";
				echo "	<td width=\"80\" valign=\"middle\">";
 							if (CheckPriv("groups_compare_edit") == 1 || $admini02 == "TRUE"){echo " <a href=\"modul_compare.php?action=edit&amp;id=".$ar[id]."&amp;project=".$_SESSION[project]."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
							if (CheckPriv("groups_compare_del") == 1){echo " <a href=\"modul_compare.php?action=delete&amp;id=".$ar[id]."&amp;project=".$_SESSION[project]."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
				echo "	</td>\n";
				echo "	<td width=\"30\" align=\"center\" valign=\"top\"><img alt=\"Karta Notebooku - ".$ar_znacka[vyrobce]." ".$ar[typ]."\" style=\"cursor: hand;\" id=\"imageElink\" onclick=\"window.open('modul_ntb_card.php?project=".$_SESSION[project]."&amp;id=".$ar[id]."','','menubar=no,resizable=no,toolbar=no,status=no,scrollbars=yes,width=600,height=600')\" img src=\"images/modul_auto_fotak.gif\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
				echo "	<td width=\"100\" align=\"left\" valign=\"top\" class=\"menu_nadpis\">".$ar_znacka[nazev]."</td>\n";
				echo "	<td width=\"100\" align=\"center\" valign=\"top\">".$ar[typ]."</td>\n";
				echo "	<td width=\"120\" align=\"right\" valign=\"top\">".$ar_cpu[nazev]."</td>\n";
				echo "	<td width=\"60\" align=\"center\" valign=\"top\">".$ar_display[nazev]."\"</td>\n";
				echo "	<td width=\"80\" align=\"center\" valign=\"top\">".$ar_vga[nazev]."</td>\n";
				echo "	<td width=\"60\" align=\"center\" valign=\"top\">".$ar_hdd[nazev]."</td>\n";
				echo "	<td width=\"80\" align=\"center\" valign=\"top\">".$czech_format_cena.",-</td>\n";
				echo "</tr>";
 				if ($_GET['action'] == "open" & $id == $ar[id]) { echo "<tr bgcolor=\"#EEEEEE\"><td width=\"857\" STYLE=\"padding-left:120px;\" colspan=\"8\">$nahled</td></tr>";}
			}
		}
		echo "</table>";
		// Pokud je novinek vice, nezli se vejde na 1 stranku zobrazi se jejich pocitani a prechazeni mezi nima
		if ($stw2 > 1){
			echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
			echo "<tr><td height=\"30\">";
			echo "Vybrat stránku";
			//Zobrazeni cisla poctu stranek
			for ($i=1;$i<=$stw2;$i++){
				if ($page==$i){
					echo " <strong>".$i."</strong>";
				} else {
					echo " <a href=\"modul_compare.php?page=".$i."&amp;project=".$_SESSION[project]."&amp;hits=".$hits."&podle=".$podle."&ser=".$ser."&ondate=".$ondate."&ondatez=".$ondatez."&ondatek=".$ondatek."\">".$i."</a> ";
				}
			}
			//Zobrazeni sipek s predchozimi a dalsimi strankami novinek
			echo "<center><a href=\"modul_compare.php?page=".$pp."&amp;project=".$_SESSION[project]."&amp;hits=".$hits."&podle=".$podle."&ser=".$ser."&ondate=".$ondate."&ondatez=".$ondatez."&ondatek=".$ondatek."\">Predchozí</a> <--|--> <a href=\"modul_compare.php?page=".$np."&amp;project=".$_SESSION[project]."&amp;hits=".$hits."&podle=".$podle."&ser=".$ser."&ondate=".$ondate."&ondatez=".$ondatez."&ondatek=".$ondatek."\">Další</a></center>";
			echo "</td></tr></table>";
		}
}

//********************************************************************************************************
//
//             PRIDAVANI A EDITACE NOTEBOOKU
//
//********************************************************************************************************
function NtbAdd(){
	
	global $db_compare_ntb,$db_compare_parts,$db_compare_categories;
	global $url_ntb;
	global $eden_cfg;
	global $ftp_path_ntb;

	// Provereni opravneni
	if ($_GET['action'] == "compare_ntb_add"){
		if (CheckPriv("groups_compare_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($_GET['action'] == "compare_ntb_edit"){
		if (CheckPriv("groups_compare_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	} else {
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	if ($confirm == "true"){
			// Výcet povolených tagu
			$allowtags = "";
			// Z obsahu promenné body vyjmout nepovolené tagy
			$nazev = strip_tags($nazev,$allowtags);
			$vyrobce = strip_tags($vyrobce,$allowtags);
			$vyrobce2 = strip_tags($vyrobce2,$allowtags);
			$typ = strip_tags($typ,$allowtags);
			$typ2 = strip_tags($typ2,$allowtags);
			$popis = strip_tags($popis,$allowtags);
			$cpu_freq = strip_tags($cpu_freq,$allowtags);
			$ram = strip_tags($ram,$allowtags);
			$vga = strip_tags($vga,$allowtags);
			$vga_ram = strip_tags($vga_ram,$allowtags);
			$audio = strip_tags($audio,$allowtags);
			$bios = strip_tags($bios,$allowtags);
			$ac_adapter = strip_tags($ac_adapter,$allowtags);
			$baterie = strip_tags($baterie,$allowtags);
			$cpu_inst = strip_tags($cpu_inst,$allowtags);
			$power_managment = strip_tags($power_managment,$allowtags);

 			$card_reader = $card_reader[0].",".$card_reader[1].",".$card_reader[2].",".$card_reader[3].",".$card_reader[4].",".$card_reader[5].",".$card_reader[6].",".$card_reader[7].",".$card_reader[8].",".$card_reader[9].",".$card_reader[10].",".$card_reader[11].",".$card_reader[12].",".$card_reader[13].",".$card_reader[14].",".$card_reader[15];
			//***************
			//  ADD
			//***************

			// Nezamenitelne 9ti mistne cislo
			list($msec, $sec) = explode(' ', microtime());
			$restmsec = substr ($msec, 2, 6);
			$restsec = substr ($sec, 7, 10);

			//Zformatovani datumu
			$datum_zarazeni = date("Y-m-d H:i:s");

			// Nasteveni jmena pro fotku
			if ($_FILES['userfile']['name'] != ""){
				// ziskam extenzi souboru
			   	$extenze=strtok($_FILES['userfile']['name'] ,".");
			   	$extenze=strtok(".");

				// Ulozi jmeno obrazku jako
				$foto = $restsec.$restmsec.".".$extenze;
				$res = mysql_query("SELECT foto FROM $db_compare_ntb") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				while ($ar = mysql_fetch_array($res)){
					if ($foto == $ar[foto]){
						list($msec, $sec) = explode(' ', microtime());
						$restmsec = substr ($msec, 2, 6);
						$restsec = substr ($sec, 7, 10);
						$foto = $restsec.$restmsec.".".$extenze;
					}
				}
			} else {
				$res = mysql_query("SELECT foto FROM $db_compare_ntb WHERE id='$id'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar = mysql_fetch_array($res);
				$foto = $ar[foto];
			}

			if ($_GET['action'] == "add"){
				// Ulozeni zaznamu do databaze
				mysql_query("INSERT INTO $db_compare_ntb VALUES('','$nazev','$vyrobce','$vyrobce2','$typ','$typ2','$popis','$display','$display_typ','$display_res','$cpu','$cpu_freq','$cpu_inst','$ram','$ram_inst','$vga','$vga_ram','$hdd','$hddot','$hdd_inst','$cd','$cd_inst',$cd_prehravani','$polohovaci_zarizeni','$scrolling_button','$irda','$net','$bluetooth','$firewire','$wifi','$modem','$usb','$fdd','$mic','$mic_in','$audio','$audio_in','$audio_out','$repro','$serial','$parallel','$ps2','$pccard','$card_reader','$kensington','$vga_out','$tv_out','$tv_in','$bios','$ac_adapter','$baterie','$baterie_vydrz','$chipset','$port_replikator','$vaha','$sirka','$vyska','$delka','$power_managment','','$foto','$cena','$rok_vyroby','$datum_zarazeni','cs')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}

			// Ulozeni pri editaci
			if ($_GET['action'] == "edit"){
				$res = mysql_query("UPDATE $db_compare_ntb SET nazev='$nazev', vyrobce='$vyrobce', vyrobce2='$vyrobce2', typ='$typ', typ2='$typ2', popis='$popis', display='$display', display_typ='$display_typ', display_res='$display_res', cpu='$cpu', cpu_freq='$cpu_freq', cpu_inst='$cpu_inst', ram='$ram', ram_inst='$ram_inst', vga='$vga', vga_ram='$vga_ram', hdd='$hdd', hddot='$hddot', hdd_inst='$hdd_inst', cd='$cd', cd_inst='$cd_inst', cd_prehravani='$cd_prehravani', polohovaci_zarizeni='$polohovaci_zarizeni', scrolling_button='$scrolling_button', irda='$irda', net='$net', bluetooth='$bluetooth', firewire='$firewire', wifi='$wifi', modem='$modem', usb='$usb', fdd='$fdd', mic='$mic', mic_in='$mic_in', audio='$audio', audio_in='$audio_in', audio_out='$audio_out', repro='$repro', serial='$serial', parallel='$parallel', ps2='$ps2', pccard='$pccard', card_reader='$card_reader', kensington='$kensington', vga_out='$vga_out', tv_out='$tv_out', tv_in='$tv_in', bios='$bios', ac_adapter='$ac_adapter', baterie='$baterie', baterie_vydrz='$baterie_vydrz', chipset='$chipset', port_replikator='$port_replikator', vaha='$vaha', sirka='$sirka', vyska='$vyska', delka='$delka', power_managment='$power_managment', foto='$foto', cena='$cena', rok_vyroby='$rok_vyroby' WHERE id='$id'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}

			if ($_FILES['userfile']['name'] != ""){
				// Prenaseni obrazku
				$conn_id = ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP); // Spojeni s FTP serverem
				$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); // Prihlaseni pres uzivatelske jmeno a heslo na FTP server
				ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
				
				if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP;die;} // Zjisteni stavu spojeni
				$extenze = strtok($_FILES['userfile']['name'] ,".");// ziskam extenzi souboru
				$extenze = strtok(".");
				$userfile_name = $foto;// generuji nazev souboru
				$new_name = $url_ntb.$userfile_name;
				$source_file =  $_FILES['userfile']['tmp_name'];// Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
				$destination_file = $ftp_path_ntb.$userfile_name; // Vlozi nazev souboru a cestu do konkretniho adresare
				$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
				ftp_close($conn_id);// Uzavreni komunikace se serverem
			}
			ShowMain();
			exit;
	}

		if ($confirm == ""){
			if ($_GET['action'] == "compare_ntb_edit"){
				$res = mysql_query("SELECT * FROM $db_compare_ntb WHERE id='$id'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar = mysql_fetch_array($res);
				// Zapsani dat z polozky card_reader do pole
				$card_reader = explode (",", $ar[card_reader]);
			}

		echo Menu();
		
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td width=\"50\" align=\"left\" valign=\"top\">&nbsp;</td>\n";
		echo "		<td width=\"600\" align=\"left\" valign=\"top\">\n";
		echo "			<form action=\"modul_compare.php\" enctype=\"multipart/form-data\" method=\"post\" name=\"forma\">\n";
						if ($_GET['action'] == "edit"){if ($ar[foto] != ""){ echo "<img src=\"".$url_ntb.$ar[foto]."\" alt=\"\" border=\"0\"><br>"; }} echo "<br><br>\n";
		echo "			<input type=\"text\" name=\"nazev\" value=\"".$ar[nazev]."\" maxlength=\"250\"><strong> - "._COMPARE_NAME."</strong><br>\n";
		echo "			<br>\n";
		echo "			<select size=\"1\" name=\"vyrobce\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='VYROBCE' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[vyrobce]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}

					echo "</select><strong> - "._COMPARE_ZNACKA."</strong><br>\n";
					echo "<br>\n";
					echo "<input type=\"text\" name=\"typ\" value=\"".$ar[typ]."\" maxlength=\"255\"><strong> - "._COMPARE_TYP."</strong><br><br>\n";
					echo "\n";
					echo "<input type=\"text\" name=\"vyrobce2\" value=\"".$ar[vyrobce2]."\" maxlength=\"255\"><strong> - "._COMPARE_VYROBCE."</strong><br><br>\n";
					echo "\n";
					echo "<input type=\"text\" name=\"typ2\" value=\"".$ar[typ2]."\" maxlength=\"255\"><strong> - "._COMPARE_TYP2."</strong><br><br>\n";
					echo "\n";
					echo "<select size=\"1\" name=\"display\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='LCD' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[display]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select><strong> - "._COMPARE_DISP."</strong><br><br>\n";
					echo "<select size=\"1\" name=\"display_typ\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='LCDTYP' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[display_typ]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select><strong> - "._COMPARE_DISPTYP."</strong><br><br>\n";
					echo "<select size=\"1\" name=\"display_res\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='LCDROZ' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[display_res]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select><strong> - "._COMPARE_DISPRES."</strong><br><br>\n";
					echo "<select size=\"1\" name=\"cpu\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='CPU' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[cpu]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select><strong> - "._COMPARE_CPU."</strong><br><br>\n";
					echo "<input type=\"text\" name=\"cpu_freq\" value=\"".$ar[cpu_freq]."\" maxlength=\"11\"><strong> - "._COMPARE_CPUFREQ."</strong><br><br>\n";
					echo "<input type=\"text\" name=\"cpu_inst\" value=\"".$ar[cpu_inst]."\" maxlength=\"255\"><strong> - "._COMPARE_CPUINST."</strong><br><br>\n";
					echo "<select size=\"1\" name=\"chipset\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='CHIPSET' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[chipset]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select><strong> - "._COMPARE_CHIPSET."</strong><br><br>\n";
					echo "<select size=\"1\" name=\"ram\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='RAM' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[ram]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select><strong> - "._COMPARE_RAM."</strong><br><br>\n";
					echo "<select size=\"1\" name=\"ram_inst\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='RAMINST' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[ram_inst]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select><strong> - "._COMPARE_RAMINST."</strong><br><br>\n";
					echo "<select size=\"1\" name=\"vga\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='VGA' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[vga]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select><strong> - "._COMPARE_VGA."</strong><br><br>\n";
					echo "<select size=\"1\" name=\"vga_ram\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='VGARAM' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[vga_ram]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select><strong> - "._COMPARE_VGARAM."</strong><br><br>\n";
					echo "<select size=\"1\" name=\"hdd\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='HDD' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[hdd]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select><strong> - "._COMPARE_HDD."</strong><br><br>\n";
					echo "<select size=\"1\" name=\"hddot\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='HDDOT' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[hddot]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select><strong> - "._COMPARE_HDDOT."</strong><br><br>\n";
					echo "<select size=\"1\" name=\"hdd_inst\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='HDDINST' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[hdd_inst]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select><strong> - "._COMPARE_HDDINST."</strong><br><br>\n";
					echo "<select size=\"1\" name=\"cd\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='CDROM' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[cd]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select><strong> - "._COMPARE_CD."</strong><br><br>\n";
					echo "<select size=\"1\" name=\"cd_inst\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='CDROMINST' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[cd_inst]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select><strong> - "._COMPARE_CDINST."</strong><br><br>\n";
					echo "<select size=\"1\" name=\"polohovaci_zarizeni\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='PZ' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[pz]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select><strong> - "._COMPARE_POLOH."</strong><br><br>\n";
					echo "<select size=\"1\" name=\"net\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='NET' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[net]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select><strong> - "._COMPARE_NET."</strong><br><br>\n";
					echo "<select size=\"1\" name=\"wifi\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='WIFI' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[wifi]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select><strong> - "._COMPARE_WIFI."</strong><br><br>\n";
					echo "<select size=\"1\" name=\"usb\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='USB' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[usb]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select><strong> - "._COMPARE_USB."</strong><br><br>\n";
					echo "<select size=\"1\" name=\"pccard\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='PCCARD' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[pccard]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select><strong> - "._COMPARE_PCCARD."</strong><br><br>\n";
					echo "<input type=\"text\" name=\"bios\" value=\"".$ar[bios]."\" maxlength=\"255\"> - <strong>"._COMPARE_BIOS."</strong><br><br>\n";
					echo "<input type=\"text\" name=\"ac_adapter\" value=\"".$ar[ac_adapter]."\" maxlength=\"255\"> - <strong>"._COMPARE_ACADAPTER."</strong><br><br>\n";
					echo "<input type=\"text\" name=\"baterie\" value=\"".$ar[baterie]."\" maxlength=\"255\"> - <strong>"._COMPARE_BATERIE."</strong><br><br>\n";
					echo "<input type=\"text\" name=\"baterie_vydrz\" value=\"".$ar[baterie_vydrz]."\" style=\"FONT-SIZE: 12px; WIDTH: 50px;\" maxlength=\"4\"> - <strong>"._COMPARE_BATERIEVYDRZ."</strong><br><br>\n";
					echo "<input type=\"text\" name=\"vaha\" value=\"".$ar[vaha]."\" maxlength=\"11\"> - <strong>"._COMPARE_VAHA."</strong><br><br>\n";
					echo "<input type=\"text\" name=\"vyska\" value=\"".$ar[vyska]."\" maxlength=\"11\"> - <strong>"._COMPARE_VYSKA."</strong><br><br>\n";
					echo "<input type=\"text\" name=\"sirka\" value=\"".$ar[sirka]."\" maxlength=\"11\"> - <strong>"._COMPARE_SIRKA."</strong><br><br>\n";
					echo "<input type=\"text\" name=\"delka\" value=\"".$ar[delka]."\" maxlength=\"11\"> - <strong>"._COMPARE_DELKA."</strong><br><br>\n";
					echo "<input type=\"text\" name=\"power_managment\" value=\"".$ar[power_managment]."\" maxlength=\"255\"> - <strong>"._COMPARE_PM."</strong><br><br>\n";
					echo "<input type=\"text\" name=\"audio\" value=\"".$ar[audio]."\" maxlength=\"255\"> - <strong>"._COMPARE_AUDIO."</strong><br><br>\n";
					echo "<select size=\"1\" name=\"audio_out\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='AUDIOOUT' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[audio_out]){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
					echo "</select> - <strong>"._COMPARE_AUDIOOUT."</strong><br><br>\n";
					echo "<input type=\"checkbox\" name=\"audio_in\" value=\"1\""; if ($ar[audio_in] == 1){echo "checked";} echo "> <strong>"._COMPARE_AUDIOIN."</strong><br>\n";
					echo "<input type=\"checkbox\" name=\"repro\" value=\"1\""; if ($ar[repro] == 1){echo "checked";} echo "> <strong>"._COMPARE_REPRO."</strong><br>\n";
					echo "<input type=\"checkbox\" name=\"cd_prehravani\" value=\"1\""; if ($ar[cd_prehravani] == 1){echo "checked";} echo "> <strong>"._COMPARE_CDPREHRAVANI."</strong><br>\n";
					echo "<input type=\"checkbox\" name=\"scrolling_button\" value=\"1\""; if ($ar[scrolling_button] == 1){echo "checked";} echo "> <strong>"._COMPARE_SCROLLBUTT."</strong><br>\n";
					echo "<input type=\"checkbox\" name=\"irda\" value=\"1\""; if ($ar[irda] == 1){echo "checked";} echo "> <strong>"._COMPARE_IRDA."</strong><br>\n";
					echo "<input type=\"checkbox\" name=\"bluetooth\" value=\"1\""; if ($ar[bluetooth] == 1){echo "checked";} echo "> <strong>"._COMPARE_BLUE."</strong><br>\n";
					echo "<input type=\"checkbox\" name=\"modem\" value=\"1\""; if ($ar[modem] == 1){echo "checked";} echo "> <strong>"._COMPARE_MODEM."</strong><br>\n";
					echo "<input type=\"checkbox\" name=\"fdd\" value=\"1\""; if ($ar[fdd] == 1){echo "checked";} echo "> <strong>"._COMPARE_FDD."</strong><br>\n";
					echo "<input type=\"checkbox\" name=\"mic\" value=\"1\""; if ($ar[mic] == 1){echo "checked";} echo "> <strong>"._COMPARE_MIC."</strong><br>\n";
					echo "<input type=\"checkbox\" name=\"mic_in\" value=\"1\""; if ($ar[mic_in] == 1){echo "checked";} echo "> <strong>"._COMPARE_MICIN."</strong><br>\n";
					echo "<input type=\"checkbox\" name=\"firewire\" value=\"1\""; if ($ar[firewire] == 1){echo "checked";} echo "> <strong>"._COMPARE_FIREWIRE."</strong><br>\n";
					echo "<input type=\"checkbox\" name=\"serial\" value=\"1\""; if ($ar[serial] == 1){echo "checked";} echo "> <strong>"._COMPARE_SERIAL."</strong><br>\n";
					echo "<input type=\"checkbox\" name=\"parallel\" value=\"1\""; if ($ar[parallel] == 1){echo "checked";} echo "> <strong>"._COMPARE_PARALLEL."</strong><br>\n";
					echo "<input type=\"checkbox\" name=\"ps2\" value=\"1\""; if ($ar[ps2] == 1){echo "checked";} echo "> <strong>"._COMPARE_PS2."</strong><br>\n";
					echo "<input type=\"checkbox\" name=\"kensington\" value=\"1\""; if ($ar[kensington] == 1){echo "checked";} echo "> <strong>"._COMPARE_KENSINGTON."</strong><br>\n";
					echo "<input type=\"checkbox\" name=\"vga_out\" value=\"1\""; if ($ar[vga_out] == 1){echo "checked";} echo "> <strong>"._COMPARE_VGAOUT."</strong><br>\n";
					echo "<input type=\"checkbox\" name=\"tv_out\" value=\"1\""; if ($ar[tv_out] == 1){echo "checked";} echo "> <strong>"._COMPARE_TVOUT."</strong><br>\n";
					echo "<input type=\"checkbox\" name=\"tv_in\" value=\"1\""; if ($ar[tv_in] == 1){echo "checked";} echo "> <strong>"._COMPARE_TVIN."</strong><br>\n";
					echo "<input type=\"checkbox\" name=\"port_replikator\""; if ($ar[port_replikator] == 1){echo "checked";} echo " value=\"1\"> <strong>"._COMPARE_PORTREPLIKATOR."</strong><br><br><br>\n";
					echo "<strong>"._COMPARE_CARDREADER."</strong><br>";
 					$cr = explode (",", $ar[card_reader]);
					$cr_pocet = count($cr);
					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='CR' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$i=0;
					while ($ar2 = mysql_fetch_array($res2)){
							echo "<input type=\"checkbox\" name=\"card_reader[".$i."]\" ";
							$y=0;
							while($y <= $cr_pocet){
								if ($cr[$y] == $ar2[nazev]){echo "checked ";}
								$y++;
							}
							echo "value=\"".$ar2[nazev]."\">  <strong>".$ar2[nazev]."</strong><br>";
							echo "\n";
						$i++;
					}
					echo "		</td>\n";
					echo "</tr>\n";
					echo "<tr>\n";
					echo "	<td width=\"857\" colspan=\"2\" align=\"left\" valign=\"top\"><hr width=\"845\" size=\"1\" noshade>\n";
					echo "		<select size=\"1\" name=\"rok_vyroby\" style=\"FONT-SIZE: 12px; WIDTH: 150px;\">";
 					$res2 = mysql_query("SELECT * FROM $db_compare_parts WHERE zkratka='ROK' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<option value=\"".$ar2[id]."\" ";
						if ($ar2[id] == $ar[rok_vyroby] && $_GET['action'] == "edit"){echo "selected";}
						if ($ar2[id] == date("Y") && $_GET['action'] == "add"){echo "selected";}
						echo ">".$ar2[nazev]."</option>";
						echo "\n";
					}
		echo "			</select><strong> - "._COMPARE_ROK."</strong><br><br>\n";
		echo "			<input type=\"file\" name=\"userfile\" size=\"20\"><strong> - "._COMPARE_FOTO."</strong><br><br>\n";
		echo "			<input type=\"text\" name=\"cena\" value=\"".$ar[cena]."\" style=\"FONT-SIZE: 12px; WIDTH: 150px;\" maxlength=\"255\"> - <strong>"._COMPARE_CENA."</strong><br><br>\n";
		echo "			<strong>"._COMPARE_POPIS.":</strong><br>\n";
		echo "			<textarea cols=\"80\" rows=\"10\" name=\"popis\">".$ar[popis]."</textarea><br><br>\n";
		echo "			<input type=\"hidden\" name=\"lang\" value=\"cs\">\n";
		echo "			<input type=\"hidden\" name=\"action\" value=\""; if ($_GET['action'] == "edit"){echo "edit";} else {echo "add";} echo "\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_SUBMIT."\" class=\"eden_button\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
}
//********************************************************************************************************
//
//             MAZANI NOTEBOOKU
//
//********************************************************************************************************
function NtbDel(){

	global $db_compare_ntb;
	global $ftp_path_ntb;
	global $url_ntb;
	global $eden_cfg;

		// CHECK PRIVILEGIES
	if (CheckPriv("groups_compare_del") <> 1) { echo _NOTENOUGHPRIV; ShowMain();exit;}

	$res = mysql_query("SELECT * FROM $db_compare_ntb WHERE id='$id'");
	$ar = mysql_fetch_array($res);
	if ($confirm == "true") {
		// Spojeni s FTP serverem
		$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP);
		// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
		$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
		ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
		
		// Zjisteni stavu spojeni
		if ((!$conn_id) || (!$login_result)){
	    	echo _ERROR_FTP;
	    	die;
	 	}
		$del_foto = ftp_nlist($conn_id, $ftp_path_ntb);
		$num_del_foto = count($del_foto); // To minus 2 je pro odstraneni . a .. ze zobrazeni
		$num_del_foto1 = count($del_foto) - 2; // To minus 2 je pro odstraneni . a .. ze zobrazeni
		for($i = 0; $i <= $num_del_foto; $i++){
			if ($del_foto[$i] == $ar[foto]){
				ftp_delete ($conn_id, $ftp_path_ntb.$ar[foto]);
			}
		}
		$res = mysql_query("DELETE FROM $db_compare_ntb WHERE id='$id'");
		ShowMain();
	}

	if ($confirm == "false"){$_GET['action'] = "open"; ShowMain();}

	if ($confirm == ""){

		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">"._COMPARE." - "._COMPARE_DEL."</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"Pridat kategorii\">	<a href=\"modul_compare.php?project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a>\n";
		echo "			&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_compare.php?action=compare_cat_add&amp;project=".$_SESSION['project']."\">"._COMPARE_ADDKATEGORIE."</a>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr class=\"popisky\">\n";
		echo "		<td width=\"100\" align=\"center\"><span class=\"nadpis-boxy\">"._COMPARE_ZNACKA."</span></td>\n";
		echo "		<td width=\"100\" align=\"center\"><span class=\"nadpis-boxy\">"._COMPARE_TYP."</span></td>\n";
		echo "		<td width=\"120\" align=\"center\"><span class=\"nadpis-boxy\">"._COMPARE_CPU."</span></td>\n";
		echo "		<td width=\"60\" align=\"center\"><span class=\"nadpis-boxy\">"._COMPARE_DISP."</span></td>\n";
		echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._COMPARE_VGA."</span></td>\n";
		echo "		<td width=\"60\" align=\"center\"><span class=\"nadpis-boxy\">"._COMPARE_HDD."</span></td>\n";
		echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._COMPARE_CENA."</span></td>\n";
		echo "	</tr>";
			$res = mysql_query("SELECT * FROM $db_compare_ntb WHERE id='$id'");
			$ar = mysql_fetch_array($res);
			$czech_format_cena = number_format($ar[cena], 0, ',', ' '); //Spravne formatovani ceny
		echo "		<tr>\n";
		echo "			<td width=\"100\" align=\"center\" valign=\"top\">".$ar[vyrobce]."</td>\n";
		echo "			<td width=\"100\" align=\"right\" valign=\"top\">".$ar[typ]."</td>\n";
		echo "			<td width=\"120\" align=\"center\" valign=\"top\">".$ar[cpu]."<br></td>\n";
		echo "			<td width=\"60\" align=\"center\" valign=\"top\">".$ar[display]."</td>\n";
		echo "			<td width=\"80\" align=\"center\" valign=\"top\">".$ar[vga]."</td>\n";
		echo "			<td width=\"60\" align=\"center\" valign=\"top\">".$ar[hdd]."</td>\n";
		echo "			<td width=\"80\" align=\"center\" valign=\"top\">".$czech_format_cena.",-</td>\n";
		echo "		</tr>\n";
		echo "		<tr>\n";
		echo "			<td colspan=\"9\">"; if ($ar[foto] != ""){ echo "<img src=\"".$url_ntb.$ar[foto]."\" border=\"0\" alt=\"\">"; } echo "</td>\n";
		echo "		</tr>\n";
		echo "	</table>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._COMPARE_CHECKDELETE."></span></strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" width=\"50\">\n";
		echo "			<form action=\"modul_compare.php\" method=\"post\">\n";
		echo "				<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
		echo "				<input type=\"hidden\" name=\"action\" value=\"delete\">\n";
		echo "				<input type=\"hidden\" name=\"id\" value=\"".$id."\">\n";
		echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "				<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "		<td valign=\"top\" width=\"800\">\n";
		echo "			<form action=\"modul_compare.php\" method=\"post\">\n";
		echo "				<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
		echo "				<input type=\"hidden\" name=\"action\" value=\"delete\">\n";
		echo "				<input type=\"hidden\" name=\"id\" value=\"".$id."\">\n";
		echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "				<input type=\"hidden\" name=\"confirm\" value=\"false\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";

	}
}
/***********************************************************************************************************
*
*		PRIDAVANI A ZOBRAZENI KATEGORII
*
***********************************************************************************************************/
function CatAdd(){
	
	global $db_compare_categories,$db_compare_parts;
	
	// Provereni opravneni
	if ($_GET['action'] == "compare_cat_add"){
		if (CheckPriv("groups_compare_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($_GET['action'] == "compare_cat_edit"){
		if (CheckPriv("groups_compare_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($_GET['action'] == "compare_cat"){
		if (CheckPriv("groups_compare_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($_GET['action'] == "compare_cat_open"){
		if (CheckPriv("groups_compare_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	} else {
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	
	if ($_GET['action'] == "compare_cat_edit"){
		$res_cat = mysql_query("SELECT compare_cat_id, compare_cat_shortname, compare_cat_name, compare_cat_active FROM $db_compare_categories WHERE compare_cat_id=".(integer)$_GET['cid']." ORDER BY compare_cat_name ASC ") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_cat = mysql_fetch_array($res_cat);
		$submit = _CMN_SUBMIT;
		$cid = "<input type=\"hidden\" name=\"cid\" value=\"".$ar_cat['compare_cat_id']."\">";
		$cat_action = "compare_cat_edit";
	} else {
		$submit = _COMPARE_CAT_ADD;
		$cid = "";
		$cat_action = "compare_cat_add";
	}
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"850\" align=\"left\" valign=\"top\" colspan=\"5\">\n";
	echo "			<form action=\"sys_save.php?action=".$cat_action."\" method=\"post\">\n";
	echo "			<strong>"._COMPARE_CAT_SHORTNAME."</strong>\n";
	echo "			<input type=\"text\" name=\"compare_cat_shortname\" value=\"".$ar_cat['compare_cat_shortname']."\" maxlength=\"20\">&nbsp;&nbsp;&nbsp;\n";
	echo "			<strong>"._COMPARE_CAT_NAME."</strong>\n";
	echo "			<input type=\"text\" name=\"compare_cat_name\" value=\"".$ar_cat['compare_cat_name']."\" maxlength=\"100\">&nbsp;&nbsp;&nbsp;\n";
	echo "			<strong>"._COMPARE_CAT_ACTIVE."</strong>\n";
	echo "			<input type=\"checkbox\" name=\"compare_cat_active\" value=\"1\" "; if ($ar_cat['compare_cat_active'] == 1){ echo "checked=\"checked\"";} echo ">&nbsp;&nbsp;&nbsp;";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo 			$cid;
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"submit\" value=\"".$submit."\" class=\"eden_button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr class=\"popisky\" >\n";
	echo "		<td width=\"100\" align=\"left\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span>\n";
	echo "		<td width=\"30\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"100\" align=\"left\"><span class=\"nadpis-boxy\">"._COMPARE_CAT_SHORTNAME."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._COMPARE_CAT."</span></td>\n";
	echo "		<td width=\"50\" align=\"left\"><span class=\"nadpis-boxy\">"._COMPARE_CAT_ACTIVE."</span></td>\n";
	echo "		<td width=\"320\" align=\"left\"><span class=\"nadpis-boxy\">"._COMPARE_PARTS."</span></td>\n";
	echo "	</tr>";
		$res_cat = mysql_query("SELECT compare_cat_id, compare_cat_shortname, compare_cat_name, compare_cat_active FROM $db_compare_categories ORDER BY compare_cat_shortname ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$i=0;
		while($ar_cat = mysql_fetch_array($res_cat)){
			$res_part = mysql_query("SELECT compare_part_id, compare_part_name FROM $db_compare_parts WHERE compare_part_category_id=".(integer)$ar_cat['compare_cat_id']." AND compare_part_active=1 ORDER BY compare_part_rank DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
	   		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
			echo "	<td width=\"100\" align=\"left\">";
					if (CheckPriv("groups_cat_edit") == 1){echo " <a href=\"modul_compare.php?action=compare_cat_edit&amp;cid=".$ar_cat['compare_cat_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\" title=\""._CMN_EDIT."\" align=\"middle\"></a>";}
					if (CheckPriv("groups_cat_del") == 1){echo " <a href=\"modul_compare.php?action=compare_cat_del&amp;cid=".$ar_cat['compare_cat_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\" title=\""._CMN_DEL."\" align=\"middle\"></a>";}
					if (CheckPriv("groups_cat_add") == 1){echo " <a href=\"modul_compare.php?action=compare_part_add&cid=".$ar_cat['compare_cat_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_dtopic.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_ADD."\" title=\""._CMN_ADD."\" align=\"middle\"></a>";}
					if (CheckPriv("groups_cat_add") == 1){echo " <a href=\"modul_compare.php?action=compare_part_open&cid=".$ar_cat['compare_cat_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_open.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_OPEN."\" title=\""._CMN_OPEN."\" align=\"middle\"></a>";}
			echo "	</td>";
			echo "	<td width=\"30\" align=\"right\">".$ar_cat['compare_cat_id']."</td>";
			echo "	<td width=\"100\" align=\"left\">".$ar_cat['compare_cat_shortname']."</td>";
			echo "	<td align=\"left\">".$ar_cat['compare_cat_name']."</td>";
			echo "	<td width=\"50\" align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($ar_cat['compare_cat_active'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
			echo "	<td width=\"320\" align=\"left\">";
			echo "		<select name=\"".$ar_cat['compare_cat_name']."\">";
					   		while($ar_part = mysql_fetch_array($res_part)){
				   	   			echo "<option value=\"".$ar_part['compare_part_id']."\">".$ar_part['compare_part_name']."</option>";
					   		}
			echo "		</select>";
			echo "	</td>";
			echo "</tr>";
			$i++;
		}
	echo "</table>";
}
//********************************************************************************************************
//
//             MAZANI KATEGORII
//
//********************************************************************************************************
function CatDel(){
	
	global $db_compare_categories;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_cat_del") <> 1) { echo _NOTENOUGHPRIV; ShowMain();exit;}
	
	$res = mysql_query("SELECT * FROM $db_compare_categories WHERE id='$id'");
	$ar = mysql_fetch_array($res);
	if ($confirm == "true") {
		$res = mysql_query("DELETE FROM $db_compare_categories WHERE id='$id'");
		$confirm = "";
		$_GET['action'] = "compare_cat_add";
		AddKategorie();
		exit();
	}

	if ($confirm == "false"){$_GET['action'] = "compare_cat_add"; $confirm = ""; AddKategorie(); exit;}

	if ($confirm == ""){

	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"50\" align=\"left\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"807\" align=\"left\"><span class=\"nadpis-boxy\">"._COMPARE_KATEGORIE."</span></td>\n";
	echo "	</tr>";
			$res = mysql_query("SELECT * FROM $db_compare_categories WHERE id='$id'");
			$ar = mysql_fetch_array($res);
	echo "	<tr>\n";
	echo "		<td width=\"50\" align=\"right\" valign=\"top\">".$ar[id]."</td>\n";
	echo "		<td width=\"807\" align=\"left\" valign=\"top\">".$ar[kategorie]."</td>\n";
	echo "	</tr>";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._COMPARE_CHECKDELETEKAT."></span></strong></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" width=\"50\">\n";
	echo "			<form action=\"modul_compare.php\" method=\"post\">\n";
	echo "				<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
	echo "				<input type=\"hidden\" name=\"action\" value=\"compare_cat_del\">\n";
	echo "				<input type=\"hidden\" name=\"id\" value=\"".$id."\">\n";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "				<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "		<td valign=\"top\" width=\"800\">\n";
	echo "			<form action=\"modul_compare.php\" method=\"post\">\n";
	echo "				<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
	echo "				<input type=\"hidden\" name=\"action\" value=\"compare_cat_del\">\n";
	echo "				<input type=\"hidden\" name=\"id\" value=\"".$id."\">\n";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "				<input type=\"hidden\" name=\"confirm\" value=\"false\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
	}
}
/***********************************************************************************************************
*
*		PRIDAVANI A EDITACE KOMPONENT
*
***********************************************************************************************************/
function PartAdd(){
	
	global $db_compare_categories,$db_compare_parts,$db_compare_txt,$db_compare_makers,$db_language;
	
	// Provereni opravneni
	if ($_GET['action'] == "compare_part_add" || $_GET['action'] == "compare_part_open"){
		if (CheckPriv("groups_compare_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($_GET['action'] == "compare_part_edit"){
		if (CheckPriv("groups_compare_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	} else {
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	
	$res_lang = mysql_query("SELECT language_id, language_name FROM $db_language WHERE language_active=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	$res_cat = mysql_query("SELECT compare_cat_id, compare_cat_shortname FROM $db_compare_categories WHERE compare_cat_active=1 ORDER BY compare_cat_shortname ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	if ($_GET['action'] == "compare_part_add"){
		$part_action = "compare_part_add";
	}
	
	if ($_GET['action'] == "compare_part_edit"){
		$res_part = mysql_query("SELECT compare_part_id, compare_part_category_id, compare_part_maker_id, compare_part_name, compare_part_rank, compare_part_active FROM $db_compare_parts WHERE compare_part_id=".(integer)$_GET['pid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_part = mysql_fetch_array($res_part);
		$res_part_desc = mysql_query("SELECT compare_txt_description FROM $db_compare_txt WHERE compare_txt_mode='part' AND compare_txt_mode_id=".(integer)$ar_part['compare_part_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$part_action = "compare_part_edit";
	}
	
	if ($_GET['action'] == "compare_part_open"){
		$part_action = "compare_part_add";
	}
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><form action=\"sys_save.php?action=".$part_action."\" method=\"post\"><strong>"._COMPARE_PART_CAT."</strong></td>\n";
	echo "		<td width=\"750\" align=\"left\" valign=\"top\">";
	echo "			<select name=\"compare_part_cat_id\">";
						while($ar_cat = mysql_fetch_array($res_cat)){
				   			echo "<option value=\"".$ar_cat['compare_cat_id']."\" "; if ($_GET['cid'] == $ar_cat['compare_cat_id'] || $ar_part['compare_part_category_id'] == $ar_cat['compare_cat_id']){echo "selected=\"selected\"";} echo ">".$ar_cat['compare_cat_shortname']."</option>";
						}
	echo "			</select>";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._COMPARE_PART_MAKER."</strong></td>\n";
	echo "		<td width=\"750\" align=\"left\" valign=\"top\">";
	echo "			<select name=\"compare_part_maker_id\">";
						$res_maker = mysql_query("SELECT compare_maker_id, compare_maker_name FROM $db_compare_makers WHERE compare_maker_active=1 AND compare_maker_category_id=".$_GET['cid']." ORDER BY compare_maker_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while($ar_maker = mysql_fetch_array($res_maker)){
				   			echo "<option value=\"".$ar_maker['compare_maker_id']."\" "; if ($ar_part['compare_part_maker_id'] == $ar_maker['compare_maker_id']){echo "selected=\"selected\"";} echo ">".$ar_maker['compare_maker_name']."</option>";
						}
	echo "			</select>";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._COMPARE_PART_NAME."</strong></td>\n";
	echo "		<td width=\"750\" align=\"left\" valign=\"top\">\n";
	echo "			<input type=\"text\" name=\"compare_part_name\" value=\"".stripslashes($ar_part['compare_part_name'])."\" maxlength=\"100\">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._COMPARE_PART_ACTIVE."</strong></td>\n";
	echo "		<td width=\"750\" align=\"left\" valign=\"top\"><input type=\"checkbox\" name=\"compare_part_active\" value=\"1\" "; if ($ar_part['compare_part_active'] == 1) {echo "checked=\"checked\"";} echo "></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._COMPARE_PART_RANK."</strong></td>\n";
	echo "		<td width=\"750\" align=\"left\" valign=\"top\"><input type=\"text\" name=\"compare_part_rank\" value=\"".$ar_part['compare_part_rank']."\" maxlength=\"10\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._COMPARE_PART_DESC."</strong></td>\n";
	echo "		<td width=\"750\" align=\"left\" valign=\"top\">\n";
					while ($ar_lang = mysql_fetch_array($res_lang)){
		   				if ($_GET['action'] == "compare_part_edit"){$ar_part_desc = mysql_fetch_array($res_part_desc);}
		   				echo "<textarea name=\"compare_part_desc[".$ar_lang['language_id']."]\" cols=\"30\" rows=\"5\" style=\"vertical-align:top;\">".$ar_part_desc['compare_txt_description']."</textarea>".$ar_lang['language_name']."<br>\n";
					}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" valign=\"top\" colspan=\"2\">";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"cid\" value=\"".$_GET['cid']."\">\n";
	echo "			<input type=\"hidden\" name=\"pid\" value=\"".$_GET['pid']."\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "compare_part_edit"){echo _CMN_SUBMIT;} else {echo _COMPARE_PART_ADD;} echo "\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"90\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"67\"><span class=\"nadpis-boxy\">"._COMPARE_PART_ID."</span></td>\n";
	echo "		<td width=\"60\"><span class=\"nadpis-boxy\">"._COMPARE_PART_CAT."</span></td>\n";
	echo "		<td width=\"150\"><span class=\"nadpis-boxy\">"._COMPARE_PART_RANK."</span></td>\n";
	echo "		<td width=\"150\"><span class=\"nadpis-boxy\">"._COMPARE_PART_MAKER."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._COMPARE_PART_NAME."</span></td>\n";
	echo "		<td width=\"50\"><span class=\"nadpis-boxy\">"._COMPARE_PART_ACTIVE."</span></td>\n";
	echo "	</tr>";
	if ($_GET['action'] == "compare_part_edit" || $_GET['action'] == "compare_part_open"){ $where = "WHERE compare_part_category_id=".(integer)$_GET['cid'];} else {$where = "";}
	$res_parts = mysql_query("SELECT cp.compare_part_id, cp.compare_part_name, cp.compare_part_rank, cp.compare_part_active, cc.compare_cat_shortname, cm.compare_maker_name 
	FROM $db_compare_parts cp
	JOIN $db_compare_categories AS cc ON cc.compare_cat_id=cp.compare_part_category_id 
	LEFT JOIN $db_compare_makers AS cm ON cm.compare_maker_id=cp.compare_part_maker_id 
	WHERE compare_part_category_id=".(integer)$_GET['cid']." 
	ORDER BY cc.compare_cat_shortname ASC, cp.compare_part_active DESC, cp.compare_part_rank DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i=0;
	while($ar_parts = mysql_fetch_array($res_parts)){
		$res_part_desc = mysql_query("SELECT compare_txt_description FROM $db_compare_txt WHERE compare_txt_mode='part' AND compare_txt_mode_id=".(integer)$ar_part['compare_part_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
 		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"90\">";
			if (CheckPriv("groups_compare_add") == 1){echo "<a href=\"modul_compare.php?action=compare_part_edit&amp;pid=".$ar_parts['compare_part_id']."&cid=".$_GET['cid']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" align=\"middle\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></a>";}
			if (CheckPriv("groups_compare_del") == 1){echo "<a href=\"modul_compare.php?action=compare_part_del&amp;pid=".$ar_parts['compare_part_id']."&cid=".$_GET['cid']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" align=\"middle\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></a>";}
		echo "	</td>\n";
		echo "	<td width=\"67\">".$ar_parts['compare_part_id']."</td>\n";
		echo "	<td width=\"60\">".$ar_parts['compare_cat_shortname']."</td>\n";
		echo "	<td width=\"150\">".$ar_parts['compare_part_rank']."</td>\n";
		echo "	<td width=\"150\">".$ar_parts['compare_maker_name']."</td>\n";
		echo "	<td>".$ar_parts['compare_part_name']."</td>\n";
		echo "	<td width=\"50\"><img src=\"images/sys_"; if ($ar_parts['compare_part_active'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
		echo "</tr>";
		$i++;
	}
	echo "</table>";
}
//********************************************************************************************************
//
//             MAZANI NOTEBOOKU
//
//********************************************************************************************************
function PartDel(){

	global $db_compare_parts;

	// CHECK PRIVILEGIES
	if (CheckPriv("groups_compare_del") <> 1) { echo _NOTENOUGHPRIV; ShowMain();exit;}

	$res = mysql_query("SELECT * FROM $db_compare_parts WHERE id='$id'");
	$ar = mysql_fetch_array($res);
	if ($confirm == "true") {
		$res = mysql_query("DELETE FROM $db_compare_parts WHERE id='$id'");
		$confirm = "";
		$_GET['action'] = "compare_part_edit";
		AddKomponenty();
		exit();
	}

	if ($confirm == "false"){$_GET['action'] = "compare_part_edit"; $confirm = ""; AddKomponenty(); exit;}

	if ($confirm == ""){

	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"50\" align=\"left\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"807\" align=\"left\"><span class=\"nadpis-boxy\">"._COMPARE_KOMPZKRATKA."</span></td>\n";
	echo "		<td width=\"807\" align=\"left\"><span class=\"nadpis-boxy\">"._COMPARE_KOMPNAZEV."</span></td>\n";
	echo "	</tr>";
			$res = mysql_query("SELECT * FROM $db_compare_parts WHERE id='$id'");
			$ar = mysql_fetch_array($res);
	echo "		<tr>\n";
	echo "			<td width=\"50\" align=\"right\" valign=\"top\">".$ar[id]."</td>\n";
	echo "			<td width=\"807\" align=\"left\" valign=\"top\">".$ar[zkratka]."</td>\n";
	echo "			<td width=\"807\" align=\"left\" valign=\"top\">".$ar[nazev]."</td>\n";
	echo "		</tr>";
	echo "	</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._COMPARE_CHECKDELKOMP."></span></strong></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" width=\"50\">\n";
	echo "			<form action=\"modul_compare.php\" method=\"post\">\n";
	echo "				<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
	echo "				<input type=\"hidden\" name=\"action\" value=\"compare_part_del\">\n";
	echo "				<input type=\"hidden\" name=\"id\" value=\"".$id."\">\n";
	echo "				<input type=\"hidden\" name=\"id_cat\" value=\"".$id_cat."\">\n";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "				<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "		<td valign=\"top\" width=\"800\">\n";
	echo "			<form action=\"modul_compare.php\" method=\"post\">\n";
	echo "				<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
	echo "				<input type=\"hidden\" name=\"action\" value=\"compare_part_del\">\n";
	echo "				<input type=\"hidden\" name=\"id\" value=\"".$id."\">\n";
	echo "				<input type=\"hidden\" name=\"id_cat\" value=\"".$id_cat."\">\n";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "				<input type=\"hidden\" name=\"confirm\" value=\"false\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
	}
}
/***********************************************************************************************************
*
*		PRIDAVANI A ZOBRAZENI VYROBCU
*
***********************************************************************************************************/
function MakerAdd(){
	
	global $db_compare_categories,$db_compare_makers;
	
	// Provereni opravneni
	if ($_GET['action'] == "compare_maker_add"){
		if (CheckPriv("groups_compare_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($_GET['action'] == "compare_maker_edit"){
		if (CheckPriv("groups_compare_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	} else {
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	
	if ($_GET['action'] == "compare_maker_edit"){
		$res_maker = mysql_query("SELECT compare_maker_id, compare_maker_category_id, compare_maker_name, compare_maker_url, compare_maker_active FROM $db_compare_makers WHERE compare_maker_id=".(integer)$_GET['mid']." ORDER BY compare_maker_name ASC ") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_maker = mysql_fetch_array($res_maker);
		$submit = _CMN_SUBMIT;
		$mid = "<input type=\"hidden\" name=\"mid\" value=\"".$ar_maker['compare_maker_id']."\">";
		$maker_action = "compare_maker_edit";
	} else {
		$submit = _COMPARE_MAKER_ADD;
		$mid = "";
		$maker_action = "compare_maker_add";
	}
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"850\" align=\"left\" valign=\"top\" colspan=\"5\">\n";
	echo "			<form action=\"sys_save.php?action=".$maker_action."\" method=\"post\">\n";
	echo "			<strong>"._COMPARE_MAKER_CAT."</strong>\n";
	$res_cat = mysql_query("SELECT compare_cat_id, compare_cat_shortname FROM $db_compare_categories ORDER BY compare_cat_shortname ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
   	echo "			<select name=\"compare_maker_cat_id\">";
						while($ar_cat = mysql_fetch_array($res_cat)){
				   			echo "<option value=\"".$ar_cat['compare_cat_id']."\" "; if ($ar_maker['compare_maker_category_id'] == $ar_cat['compare_cat_id']){echo "selected=\"selected\"";} echo ">".$ar_cat['compare_cat_shortname']."</option>";
						}
	echo "			</select>";
	echo "			<strong>"._COMPARE_MAKER_NAME."</strong>\n";
	echo "			<input type=\"text\" name=\"compare_maker_name\" value=\"".$ar_maker['compare_maker_name']."\" maxlength=\"100\">&nbsp;&nbsp;&nbsp;\n";
	echo "			<strong>"._COMPARE_MAKER_URL."</strong>\n";
	echo "			<input type=\"text\" name=\"compare_maker_url\" value=\"".$ar_maker['compare_maker_url']."\" maxlength=\"255\">&nbsp;&nbsp;&nbsp;\n";
	echo "			<strong>"._COMPARE_MAKER_ACTIVE."</strong>\n";
	echo "			<input type=\"checkbox\" name=\"compare_maker_active\" value=\"1\" "; if ($ar_maker['compare_maker_active'] == 1){ echo "checked=\"checked\"";} echo ">&nbsp;&nbsp;&nbsp;";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo 			$mid;
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"submit\" value=\"".$submit."\" class=\"eden_button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr class=\"popisky\" >\n";
	echo "		<td width=\"100\" align=\"left\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span>\n";
	echo "		<td width=\"30\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"100\" align=\"left\"><span class=\"nadpis-boxy\">"._COMPARE_MAKER_CAT."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._COMPARE_MAKER_NAME."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._COMPARE_MAKER_URL."</span></td>\n";
	echo "		<td width=\"50\" align=\"left\"><span class=\"nadpis-boxy\">"._COMPARE_MAKER_ACTIVE."</span></td>\n";
	echo "	</tr>";
		$res_maker = mysql_query("SELECT cm.compare_maker_id, cm.compare_maker_category_id, cm.compare_maker_name, cm.compare_maker_url, cm.compare_maker_active, cc.compare_cat_shortname 
		FROM $db_compare_makers AS cm 
		JOIN $db_compare_categories AS cc ON cc.compare_cat_id=cm.compare_maker_category_id 
		ORDER BY cc.compare_cat_shortname ASC, cm.compare_maker_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$i=0;
		while($ar_maker = mysql_fetch_array($res_maker)){
			if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
	   		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
			echo "	<td width=\"100\" align=\"left\">";
					if (CheckPriv("groups_cat_edit") == 1){echo " <a href=\"modul_compare.php?action=compare_maker_edit&amp;mid=".$ar_maker['compare_maker_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\" title=\""._CMN_EDIT."\" align=\"middle\"></a>";}
					/* if (CheckPriv("groups_cat_del") == 1){echo " <a href=\"modul_compare.php?action=compare_maker_del&amp;mid=".$ar_maker['compare_maker_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\" title=\""._CMN_DEL."\" align=\"middle\"></a>";} */
					echo "	</td>";
			echo "	<td width=\"30\" align=\"right\">".$ar_maker['compare_maker_id']."</td>";
			echo "	<td width=\"100\" align=\"left\">".$ar_maker['compare_cat_shortname']."</td>";
			echo "	<td align=\"left\">".$ar_maker['compare_maker_name']."</td>";
			echo "	<td align=\"left\"><a href=\"http://".$ar_maker['compare_maker_url']."\" target=\"_blank\">".$ar_maker['compare_maker_url']."</a></td>";
			echo "	<td width=\"50\" align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($ar_maker['compare_maker_active'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
			echo "	</td>";
			echo "</tr>";
			$i++;
		}
	echo "</table>";
}
include ("inc.header.php");
	if ($_GET['action'] == "") { ShowMain(); }
	if ($_GET['action'] == "search_ntb") { ShowMain(); }
	if ($_GET['action'] == "compare_ntb_add") {NtbAdd();}
	if ($_GET['action'] == "compare_ntb_edit") {NtbAdd();}
	if ($_GET['action'] == "compare_cat") {CatAdd();}
	if ($_GET['action'] == "compare_cat_add") {CatAdd();}
	if ($_GET['action'] == "compare_cat_edit") {CatAdd();}
	if ($_GET['action'] == "compare_cat_del") {CatDel();}
	if ($_GET['action'] == "compare_maker_add") {MakerAdd();}
	if ($_GET['action'] == "compare_maker_edit") {MakerAdd();}
	if ($_GET['action'] == "compare_part_add") {PartAdd();}
	if ($_GET['action'] == "compare_part_edit") {PartAdd();}
	if ($_GET['action'] == "compare_part_open") {PartAdd();}
	if ($_GET['action'] == "compare_part_del") {PartDel();}
include ("inc.footer.php");