<?php
function Menu(){
	
	switch ($_GET['action']){
		case "adds_add":
			$title = _ADDS_ADDS." - "._ADDS_ADD;
			break;
		case "adds_edit":
			$title = _ADDS_ADDS." - "._ADDS_EDIT." - ID:".$_GET['id'];
			break;
		case "adds_del":
			$title = _ADDS_ADDS." - "._ADDS_DEL." - ID:".$_GET['id'];
			break;
		case "adds_non_published":
			$title = _ADDS_ADDS." - "._ADDS_PUBLISH;
		default:
			$title = _ADDS_ADDS;
	}
	
	$menu = "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td align=\"left\" class=\"nadpis\">".$title."</td>\n";
	$menu .= "	</tr>\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">\n";
	$menu .= "			<a href=\"modul_ads.php?action=showmain&project=".$_SESSION['project']."&amp;id=".$_GET['id']."&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;id_link=".$_GET['id_link']."&amp;close=".$_GET['close']."&amp;action_link=".$_GET['action_link']."\">"._CMN_MAINMENU."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	$menu .= "			<a href=\"modul_ads.php?action=adds_add&amp;id=".$_GET['id']."&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\">"._ADDS_ADD."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	$menu .= "			<a href=\"modul_ads.php?action=adds_non_published&amp;project=".$_SESSION['project']."\">"._ADDS_SHOW_NOPUBLIC."</a>\n";
	$menu .= "		</td>\n";
	$menu .= "	</tr>";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['msg']){
		$menu .= "<tr><td class=\"msg\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	$menu .= "</table>\n";
	
	return $menu;
	//<a href="modul_ads.php?project=".$_SESSION['project']."&amp;id=".$_GET['id']."&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;id_link=".$_GET['id_link']."&amp;close=".$_GET['close']."&amp;action_link=".$_GET['action_link']."">"._CMN_MAINMENU."</a></td>
}
/***********************************************************************************************************
*																											
*		VLOZENI ROZBALOVACIHO TLACITKA  																	
*																											
***********************************************************************************************************/
function Rozbal($podcat,$command,$close,$ar,$ar1,$ar2,$ar3,$ar4,$ar5,$ar6,$obr){
	
	if ($podcat > 0 && $_GET['command'] == "open"){
		echo "<a href=\"modul_ads.php?action=".$command."&amp;id=".$ar."&amp;id1=".$ar1."&amp;id2=".$ar2."&amp;id3=".$ar3."&amp;id4=".$ar4."&amp;id5=".$ar5."&amp;id6=".$ar6."&close=".$close."&amp;project=".$_SESSION['project']."\" title=\""._ADDS_FOLD_UP."\"><img src=\"images/sys_strom_".$obr."_plus.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\"></a>";
	}elseif ($podcat > 0 && $_GET['command'] == "close"){
		echo "<a href=\"modul_ads.php?action=".$command."&amp;id=".$ar."&amp;id1=".$ar1."&amp;id2=".$ar2."&amp;id3=".$ar3."&amp;id4=".$ar4."&amp;id5=".$ar5."&amp;id6=".$ar6."&close=".$close."&amp;project=".$_SESSION['project']."\" title=\""._ADDS_FOLD_DOWN."\"><img src=\"images/sys_strom_".$obr."_minus.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\"></a>";
	} else {
		echo "<img src=\"images/sys_strom_".$obr.".gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
	}
}

/***********************************************************************************************************
*																											
*		ZOBRAZENI SEZNAMU KATEGORII 																		
*																											
***********************************************************************************************************/
function ShowMain(){
	
	global $adds_path,$db_adds,$db_category;
	global $url_category;
	
	if ($_GET['action'] == "adds_edit"){$_GET['action'] = "open";}
	if ($_GET['action'] == "adds_add"){$_GET['action'] = "open";}
	if ($_GET['action'] == "adds_del"){$_GET['action'] = "open";}
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"50\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_IMAGE."</span></td>\n";
	echo "		<td width=\"540\" align=\"left\"><span class=\"nadpis-boxy\">"._ADDS_NAME."</span></td>\n";
	echo "		<td width=\"100\" align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_SUBCATEGORY_NUMBER."</span></td>\n";
	echo "	</tr>";
	//***********************************************************************************
	// Hlavni Menu
	//***********************************************************************************
	$res = mysql_query("SELECT category_id, category_name, category_image, category_comment, category_admin FROM $db_category WHERE category_parent=0 AND category_adds=1 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i = 1;
	while ($ar = mysql_fetch_array($res)){
		$res1 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".(integer)$ar['category_id']." AND category_adds=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num = mysql_num_rows($res1);
		$reslink = mysql_query("SELECT adds_id, adds_name, adds_link, adds_views, adds_out, adds_start, adds_end, adds_publish, adds_gfx FROM $db_adds WHERE adds_category=".(integer)$ar['category_id']." ORDER BY adds_publish DESC, adds_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$numlink = mysql_num_rows($reslink);
		$res_link_active = mysql_query("SELECT COUNT(*) FROM $db_adds WHERE adds_category=".(integer)$ar['category_id']." AND adds_publish=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num_link_active = mysql_fetch_row($res_link_active);
		$hlavicka = stripslashes($ar['category_name']);
		// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
		//if ($_GET['action'] == "open" && $_GET['id'] == $ar[id]) {$_GET['command'] = "close";}elseif ($_GET['action'] == "close" && $close != 0 && $_GET['id'] == $ar[id]){$_GET['command'] = "close";} else {$_GET['command'] = "open";}
		$_GET['action'] = "open";
		$_GET['command'] = "close";
		if ($_GET['action_link'] == "" || !isset($_GET['action_link'])){$_GET['action_link'] = "close";}
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"100\" align=\"left\" valign=\"middle\">";
				if ($numlink >= 1){echo "<a href=\"modul_ads.php?action_link="; if ($_GET['action_link'] == "open" && $ar['category_id'] == $_GET['id_link']){echo "close";} else {echo "open";} echo "&amp;action=open&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;id_link=".$ar['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_open.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_OPEN."\"></a>"; } else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">"; }
				if (CheckPriv("groups_adds_add") == 1){echo " <a href=\"modul_ads.php?action=adds_add&amp;action_link=open&amp;id=".$arlink2['adds_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;id_link=".$ar['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_dtopic.gif\" border=\"0\" alt=\""._CMN_ADD."\"></a>";}
		echo "	</td>";
		echo "	<td width=\"50\" align=\"center\" valign=\"middle\"><strong>".$ar['category_id']."</strong></td>";
		echo "	<td width=\"60\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar['category_image']."\"></td>";
		echo "	<td width=\"540\" align=\"left\" valign=\"middle\">"; Rozbal($num[0],$_GET['command'],"0",$_GET['id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"zacatek");
		echo "		&nbsp;<strong><a href=\"modul_ads.php?action=".$_GET['command']."&amp;id1=".$ar['category_id']."&amp;project=".$_SESSION['project']."\" title=\"".$ar['category_comment']."\">".$hlavicka."</a></strong> "; /*Zobrazeni poctu reklam v kategorii*/ $adds_inactive = $numlink - $num_link_active[0]; echo "(".$numlink.", ".$num_link_active[0]." "._ADDS_ACTIVE.", ".$adds_inactive." "._ADDS_INACTIVE.")"; if (!isset($ar['category_admin'])){echo "[".$ar['category_admin']."]";} echo "</td>";
		echo "	<td width=\"100\" align=\"center\" valign=\"middle\"><strong>".$num[0]."</strong></td>";
		echo "</tr>";
		if ($_GET['action_link'] == "open" && $ar['category_id'] == $_GET['id_link'] && $numlink > 0){
			echo "<tr>\n";
			echo "	<td colspan=\"5\" width=\"850\">\n";
			echo "		<table width=\"850\">\n";
			echo "			<tr bgcolor=\"#DCE3F1\">\n";
			echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
			echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
			echo "				<td align=\"left\"><span class=\"nadpis-boxy\">"._ADDS_NAME."</span><br>\n";
			echo "					<span class=\"nadpis-boxy\">"._ADDS_LINK."</span></td>\n";
			echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_VIEWS."</span></td>\n";
			echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_OUT."</span></td>\n";
			echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_SHOW."</span></td>\n";
			echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_GFX."</span></td>\n";
			echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_START."</span><br>\n";
			echo "					<span class=\"nadpis-boxy\">"._ADDS_END."</span></td>\n";
			echo "			</tr>";
			$y=1;
			while($arlink = mysql_fetch_array($reslink)){
				$adds_start = FormatTimestamp($arlink['adds_start'],"d.m.Y, H:i");
				$adds_end = FormatTimestamp($arlink['adds_end'],"d.m.Y, H:i");
				if ($arlink['adds_gfx'] == 0){$gfx = _CMN_NO;} else {$gfx = _CMN_YES;}
				if ($y % 2 == 0){ $cat_class = "cat_level2_even";} else { $cat_class = "cat_level2_odd";}
   				echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
				echo "			<td width=\"40\"><a href=\"modul_ads.php?action=adds_edit&amp;action_link=".$_GET['action_link']."&amp;id=".$arlink['adds_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\"></a> <a href=\"modul_ads.php?action=adds_del&amp;action_link=".$_GET['action_link']."&amp;id=".$arlink['adds_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\"></a></td>";
				echo "			<td align=\"right\" valign=\"top\">".$arlink['adds_id']."</td>";
				echo "			<td align=\"left\"><strong>".$arlink['adds_name']."</strong><br>";
				echo "				<a href=\"http://".$arlink['adds_link']."\" TARGET=\"_blank\">".$arlink['adds_link']."</a></td>";
				echo "			<td align=\"right\" valign=\"top\">".$arlink['adds_views']."</td>";
				echo "			<td align=\"right\" valign=\"top\">".$arlink['adds_out']."</td>";
				echo "		<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink['adds_publish'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
				echo "		<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink['adds_gfx'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
				echo "		<td align=\"center\" valign=\"top\">".$adds_start."<br>".$adds_end."</td>\n";
				echo "		</tr>\n";
				$y++;
			}
			echo "</table>";
			echo "</td>";
			echo "</tr>";
 		}
	//***********************************************************************************
	// Prvni Podmenu
	//***********************************************************************************
		// Zajisteni rozbaleni, nebo zbaleni senamu po kliknuti
		$res2 = mysql_query("SELECT category_id, category_name, category_image, category_comment FROM $db_category WHERE category_parent=".(float)$ar['category_id']." AND category_adds=1 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num2 = mysql_num_rows($res2);
		$a = 1;
		while ($ar2 = mysql_fetch_array($res2)){
			$vys2 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".(float)$ar2['category_id']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$podcat2 = mysql_num_rows($vys2);
			// Nacteme programy v danych kategoriich
			$reslink2 = mysql_query("SELECT adds_id, adds_name, adds_link, adds_views, adds_out, adds_show_unlimited, adds_show_by_date, adds_show_by_count, adds_start, adds_end, adds_publish, adds_gfx FROM $db_adds WHERE adds_category=".(float)$ar2['category_id']." ORDER BY adds_publish DESC, adds_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$numlink2 = mysql_num_rows($reslink2);
			$res_link_active_2 = mysql_query("SELECT COUNT(*) FROM $db_adds WHERE adds_category=".(float)$ar2['category_id']." AND adds_publish=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$num_link_active_2 = mysql_fetch_row($res_link_active_2);
			$hlavicka2 = stripslashes($ar2['category_name']);
			// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
			if ($_GET['action'] == "open" && $_GET['id2'] == $ar2['category_id']) {$_GET['command'] = "close";}elseif ($_GET['action'] == "close" && $close != 1 && $_GET['id2'] == $ar2['category_id']){$_GET['command'] = "close";} else {$_GET['command'] = "open";}
			if ($a % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
			echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
			echo "	<td width=\"100\" align=\"left\" valign=\"middle\">";
					if ($numlink2 >= 1){echo "<a href=\"modul_ads.php?action_link="; if ($_GET['action_link'] == "open" && $ar2['category_id'] == $_GET['id_link']){echo "close";} else {echo "open";} echo "&amp;action=open&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['id']."&amp;id_link=".$ar2['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_open.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_OPEN."\"></a>"; } else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">"; }
					if (CheckPriv("groups_adds_add") == 1){echo "<a href=\"?action=adds_add&amp;action_link=open&amp;id=".$arlink2['adds_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;id_link=".$ar2['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_dtopic.gif\" border=\"0\" alt=\""._CMN_ADD."\"></a>";}
					echo "</td> \n";
					echo "<td width=\"50\" align=\"center\" valign=\"middle\">".$ar2['category_id'],"</td>\n";
					echo "<td width=\"60\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar2['category_image']."\"></td>\n";
					echo "<td width=\"540\" align=\"left\" valign=\"middle\">";
					if ($num2 > $a){
						Rozbal($podcat2[0],$_GET['command'],"1",$_GET['id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
					}elseif ($num2 == $a){
						Rozbal($podcat2[0],$_GET['command'],"1",$_GET['id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
					}
			echo "			&nbsp;<a href=\"modul_ads.php?action=".$_GET['command']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\" title=\"".$ar2['category_comment']."\">".$hlavicka2."</a> "; /*Zobrazeni poctu souboru v kategorii*/ $adds_inactive_2 = $numlink2 - $num_link_active_2[0]; echo "(".$numlink2.", ".$num_link_active_2[0]." "._ADDS_ACTIVE.", ".$adds_inactive_2." "._ADDS_INACTIVE.") ["; $x=0; while($num02>=$x){if ($_SESSION['login'] == $admini[$x]){echo $admini[$x]."&nbsp;";} elseif ($_SESSION['login'] != $admini[$x] && $admini[$x] != ""){ echo "<a href=\"modul_forum.php?project=".$_SESSION['project']."&amp;action=posta&amp;user=".strtoupper($_SESSION['login'])."&amp;pm_rec=".$admini[$x]."\">".$admini[$x]."</a>&nbsp;"; }$x++;} echo "]</td>";
			echo "		<td width=\"100\" align=\"center\" valign=\"middle\">".$podcat2[0]."</td>";
			echo "	</tr>";
			if ($_GET['action_link'] == "open" && $ar2['category_id'] == $_GET['id_link'] && $numlink2 > 0){
				echo "<tr>\n";
				echo "	<td colspan=\"5\" width=\"850\">\n";
				echo "		<table width=\"850\">\n";
				echo "			<tr bgcolor=\"#ff8080\">\n";
				echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
				echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
				echo "				<td align=\"left\"><span class=\"nadpis-boxy\">"._ADDS_NAME."</span><br>\n";
				echo "					<span class=\"nadpis-boxy\">"._ADDS_LINK."</span></td>\n";
				echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_VIEWS."</span></td>\n";
				echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_OUT."</span></td>\n";
				echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_SHOW."</span></td>\n";
				echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_GFX."</span></td>\n";
				echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_SHOWS_BY."</span>\n";
				echo "				</td>\n";
				echo "			</tr>";
				$b=1;
				while($arlink2 = mysql_fetch_array($reslink2)){
				$adds_start2 = FormatTimestamp($arlink2['adds_start'],"d.m.Y, H:i");
				$adds_end2 = FormatTimestamp($arlink2['adds_end'],"d.m.Y, H:i");
					if ($b % 2 == 0){ $cat_class = "cat_level2_even";} else { $cat_class = "cat_level2_odd";}
					echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
					echo "	<td width=\"40\"><a href=\"modul_ads.php?action=adds_edit&amp;action_link=".$_GET['action_link']."&amp;id=".$arlink2['adds_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\"></a> <a href=\"modul_ads.php?action=adds_del&amp;action_link=".$_GET['action_link']."&amp;id=".$arlink2['adds_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\"></a></td>\n";
					echo "	<td align=\"right\" valign=\"top\">".$arlink2['adds_id']."</td>\n";
					echo "	<td align=\"left\"><strong>".$arlink2['adds_name']."</strong><br>\n";
					echo "		<a href=\"http://".$arlink2['adds_link']."\" TARGET=\"_blank\">".$arlink2['adds_link']."</a></td>\n";
					echo "	<td align=\"right\" valign=\"top\">".$arlink2['adds_views']."</td>\n";
					echo "	<td align=\"right\" valign=\"top\">".$arlink2['adds_out']."</td>";
					echo "		<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink2['adds_publish'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
					echo "		<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink2['adds_gfx'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
					echo "		<td align=\"right\" valign=\"top\">"; if ($arlink2['adds_show_unlimited'] == 1){echo _ADDS_UNLIMITED;}
								if ($arlink2['adds_show_by_date'] == 1){echo $adds_start2."<br>".$adds_end2;}
								if ($arlink2['adds_show_by_count'] == 1){echo $arlink2['adds_count'];} echo "</td>\n";
					echo "	</tr>\n";
					$b++;
				}
				echo "	</table>";
				echo "</td>";
				echo "</tr>";
 			}
			$a++;
		}
		$i++;
	}
	echo "	<tr>\n";
	echo "		<td colspan=\"5\">\n";
	echo "			<table width=\"850\">\n";
	echo "				<tr>\n";
	echo "					<td colspan=\"6\" height=\"30\" align=\"left\" valign=\"middle\">&nbsp;</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td colspan=\"6\" align=\"left\" valign=\"middle\"><span class=\"nadpis-boxy\">"._ADDS_MISFILES."</span></td>\n";
	echo "				</tr>\n";
	echo "				<tr bgcolor=\"#DCE3F1\">\n";
	echo "					<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "					<td align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_NAME."</span></td>\n";
	echo "					<td align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_LINK."</span></td>\n";
	echo "					<td align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_IN."</span></td>\n";
	echo "					<td align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_OUT."</span></td>\n";
	echo "					<td align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_ADDS."</span></td>\n";
	echo "					<td align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_GFX."</span></td>\n";
	echo "					<td align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_LIST."</span></td>\n";
	echo "				</tr>";
 	$res7 = mysql_query("SELECT adds_id, adds_name, adds_link, adds_views, adds_out, adds_list FROM $db_adds WHERE adds_category=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($arlink7 = mysql_fetch_array($res7)){
		echo "	<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FAF4FF'\" bgcolor=\"#FAF4FF\">";
 		echo "		<td width=\"80\"><a href=\"modul_ads.php?action=adds_edit&amp;id=".$arlink7['adds_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\"></a> <a href=\"modul_ads.php?action=adds_del&amp;id=".$arlink7['adds_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\"></a></td>";
		echo "		<td align=\"left\">".$arlink7['adds_name']."</td>";
		echo "		<td align=\"left\" valign=\"top\"><a href=\"http://".$arlink7['adds_link']."\" TARGET=\"_blank\">".$arlink7['adds_link']."</a></td>";
		echo "		<td align=\"right\" valign=\"top\">".$arlink7['adds_views']."</td>";
		echo "		<td align=\"right\" valign=\"top\">".$arlink7['adds_out']."</td>";
		echo "		<td align=\"center\" valign=\"top\">".$main7."</td>";
		echo "		<td align=\"center\" valign=\"top\">".$gfx7."</td>";
		echo "		<td align=\"center\" valign=\"top\">".$arlink7['adds_list']."</td>";
		echo " 	</tr>";
 	}
  echo "			</table>";
  echo "		</td>";
  echo "	</tr>";
  echo "</table>";
}

/***********************************************************************************************************
*																											
*		ZOBRAZENI NEPUBLIKOVANE REKLAMY																		
*																											
***********************************************************************************************************/
function ShowNonPublished(){
	
	global $db_adds,$adds_path;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	if ($_POST['confirm'] == "true"){
		if ($_POST['mod_funkce'] == "delete"){
			$i = 0;
			$num = count ($_POST['mark']);
			while ($i < $num) {
				$mark = $_POST['mark'][$i];
 				$res = mysql_query("DELETE FROM $db_adds WHERE adds_id=".(integer)$mark) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$i++;
			}
			unset($_POST['confirm']);
			ShowNonPublished();
			exit;
		}
		if ($_POST['mod_funkce'] == "publish"){
			$i = 0;
			$num = count ($_POST['mark']);
			while ($i < $num) {
				$mark = $_POST['mark'][$i];
				$res = mysql_query("UPDATE $db_adds SET adds_publish=1 WHERE adds_id=".(integer)$mark) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$i++;
			}
			unset($_POST['confirm']);
			ShowNonPublished();
			exit;
		}
	}
	if ($_POST['confirm'] != "true"){
		$res = mysql_query("SELECT adds_id, adds_name, adds_link FROM $db_adds WHERE adds_publish=0 ORDER BY adds_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num = mysql_num_rows($res);
		
		echo Menu();
		
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr class=\"popisky\">\n";
		echo "		<td align=\"center\"><form action=\"modul_ads.php?action=adds_non_published&amp;project=".$_SESSION['project']."&amp;id=".$_GET['id']."&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;id_link=".$_GET['id_link']."&amp;close=".$_GET['close']."&amp;action_link=".$_GET['action_link']."\" method=\"post\" name=\"forma\"\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
		echo "		<td align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_NAME."</span></td>\n";
		echo "		<td align=\"center\"><span class=\"nadpis-boxy\">"._ADDS_LINK."</span></td>\n";
		echo "	</tr>";
	 	while ($ar = mysql_fetch_array($res)){
			$res2 = mysql_query("SELECT COUNT(*) FROM $db_adds WHERE adds_publish=0 ORDER BY adds_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			if (mysql_num_rows($res2) <> 0){
				echo "<tr>\n";
				echo "	<td width=\"80\"><input type=\"checkbox\" name=\"mark[]\" value=\"".$ar['adds_id']."\"><a href=\"modul_ads.php?action=adds_edit&amp;id=".$ar['adds_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\"></a> <a href=\"modul_ads.php?action=adds_del&amp;id=".$ar['adds_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\"></a></td> \n";
				echo "	<td align=\"left\">".$ar['adds_name']."</td>\n";
				echo "	<td align=\"left\" valign=\"top\"><a href=\"http://".$ar['adds_link']."\" TARGET=\"_blank\">".$ar['adds_link']."</a></td>\n";
				echo "</tr>\n";
			}
		}
		echo "	<tr>\n";
		echo "		<td colspan=\"3\"><select name=\"mod_funkce\">\n";
		echo "				<option value=\"publish\"> "._ADDS_PUBLISH_SEL."</option>\n";
		echo "				<option value=\"delete\"> "._ADDS_DEL_SEL."</option>\n";
		echo "			</select>\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"submit\" value=\"". _CMN_SUBMIT."\" class=\"eden_button\"></form></td> \n";
		echo "	</tr>\n";
		echo "</table>";
 	}
}

/***********************************************************************************************************
*																											
*		PRIDAVANI A EDITACE REKLAM																			
*																											
***********************************************************************************************************/
function Add(){
	
	global $db_adds,$db_category;
	global $ftp_path_adds;
	global $eden_cfg;
	global $adds_path,$url_adds;
	
	// Provereni opravneni
	if ($_GET['action'] == "adds_add"){
		if (CheckPriv("groups_adds_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($_GET['action'] == "adds_edit"){
		if (CheckPriv("groups_adds_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	} else {
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	
	if ($_POST['confirm'] <> "true"){
		$res = mysql_query("SELECT * FROM $db_adds WHERE adds_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		
		$adds_description = str_ireplace( "&quot;","\"",$ar['adds_description']);
		$adds_description = str_ireplace( "&acute","'",$adds_desccription);
		
		// Spojeni s FTP serverem 
		$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP); 
		// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
		$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
		ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
		
		// Zjisteni stavu spojeni
		if ((!$conn_id) || (!$login_result)) { echo _ERROR_FTP;	die; }
		
		echo Menu();
		
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ADDS_PUBLISH."</strong><form action=\"sys_save.php?action="; if ($_GET['action'] == "adds_edit"){echo "adds_edit";} else {echo "adds_add";} echo "&amp;project=".$_SESSION['project']."&amp;id=".$_GET['id']."&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;id_link=".$_GET['id_link']."&amp;close=".$_GET['close']."&amp;action_link=".$_GET['action_link']."\" method=\"post\" name=\"forma\" enctype=\"multipart/form-data\"></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"checkbox\" name=\"adds_publish\" value=\"1\" "; if ($ar['adds_publish'] == 1) {echo "checked=\"checked\"";} echo "></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ADDS_NAME."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"adds_name\" size=\"60\" value=\"".$ar['adds_name']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ADDS_LINK."</strong><br>"._ADDS_HELP_FLASH."?clickTAG=http://</td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"adds_link\" size=\"120\" value=\"".$ar['adds_link']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ADDS_LINK_ONCLICK."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"adds_link_onclick\" size=\"120\" value=\"".$ar['adds_link_onclick']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ADDS_DESC."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><textarea name=\"adds_description\" cols=\"60\" rows=\"7\">".$ar['adds_description']."</textarea></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ADDS_CATEGORY."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\">";
					// Select category
					echo EdenCategorySelect($ar['adds_category'], "adds", "adds_category", 0);
		echo "		</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CMN_IMAGE." 1</strong></td>\n";
		echo "	<td align=\"left\" valign=\"top\"><input name=\"adds_picture\" type=\"file\" size=\"30\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CMN_IMAGE." 2</strong></td>\n";
		echo "	<td align=\"left\" valign=\"top\"><input name=\"adds_picture2\" type=\"file\" size=\"30\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ADDS_IMAGE." 1</strong></td>\n";
		echo "	<td align=\"left\" valign=\"top\">";
			$extenze = substr ($ar['adds_picture'], -3);
			if ($extenze == "jpg" || $extenze == "gif" || $extenze == "png"){ 
				echo "<p><img src=\"".$url_adds.$ar['adds_picture']."\" border=\"0\"></p>";
			}
			if ($extenze == "swf"){
				$size = getimagesize($url_adds.$ar['adds_picture']);
				echo "<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" width=\"".$size[0]."\" height=\"".$size[1]."\" id=\"ad1\" align=\"middle\">\n";
				echo "	<param name=\"movie\" value=\"".$url_adds.$ar['adds_picture']."\" />\n";
				echo "	<param name=\"quality\" value=\"high\" />\n";
				echo "	<param name=\"bgcolor\" value=\"#ffffff\" />\n";
				echo "	<param name=\"play\" value=\"true\" />\n";
				echo "	<param name=\"loop\" value=\"true\" />\n";
				echo "	<param name=\"wmode\" value=\"window\" />\n";
				echo "	<param name=\"scale\" value=\"showall\" />\n";
				echo "	<param name=\"menu\" value=\"true\" />\n";
				echo "	<param name=\"devicefont\" value=\"false\" />\n";
				echo "	<param name=\"salign\" value=\"\" />\n";
				echo "	<param name=\"allowScriptAccess\" value=\"sameDomain\" />\n";
				echo "	<!--[if !IE]>-->\n";
				echo "	<object type=\"application/x-shockwave-flash\" data=\"".$url_adds.$ar['adds_picture']."\" width=\"".$size[0]."\" height=\"".$size[1]."\">\n";
				echo "		<param name=\"movie\" value=\"".$url_adds.$ar['adds_picture']."\" />\n";
				echo "		<param name=\"quality\" value=\"high\" />\n";
				echo "		<param name=\"bgcolor\" value=\"#ffffff\" />\n";
				echo "		<param name=\"play\" value=\"true\" />\n";
				echo "		<param name=\"loop\" value=\"true\" />\n";
				echo "		<param name=\"wmode\" value=\"window\" />\n";
				echo "		<param name=\"scale\" value=\"showall\" />\n";
				echo "		<param name=\"menu\" value=\"true\" />\n";
				echo "		<param name=\"devicefont\" value=\"false\" />\n";
				echo "		<param name=\"salign\" value=\"\" />\n";
				echo "		<param name=\"allowScriptAccess\" value=\"sameDomain\" />\n";
				echo "	<!--<![endif]-->\n";
				echo "		<a href=\"http://www.adobe.com/go/getflash\">\n";
				echo "			<img src=\"http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif\" alt=\"Get Adobe Flash player\" />\n";
				echo "		</a>\n";
				echo "	<!--[if !IE]>-->\n";
				echo "	</object>\n";
				echo "	<!--<![endif]-->\n";
				echo "</object>";
			}
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ADDS_IMAGE." 2</strong></td>\n";
		echo "	<td align=\"left\" valign=\"top\">";
			$extenze = substr ($ar['adds_picture2'], -3);if ($extenze == "jpg" || $extenze == "gif" || $extenze == "png"){ echo "<p><img src=\"".$url_adds.$ar['adds_picture2']."\" border=\"0\"></p>";}
			$extenze = substr ($ar['adds_picture2'], -3);
	   		if ($extenze == "swf"){
				$size = getimagesize($url_adds.$ar['adds_picture2']);
				echo "<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" width=\"".$size[0]."\" height=\"".$size[1]."\" id=\"ad2\" align=\"middle\">\n";
				echo "	<param name=\"movie\" value=\"".$url_adds.$ar['adds_picture2']."\" />\n";
				echo "	<param name=\"quality\" value=\"high\" />\n";
				echo "	<param name=\"bgcolor\" value=\"#ffffff\" />\n";
				echo "	<param name=\"play\" value=\"true\" />\n";
				echo "	<param name=\"loop\" value=\"true\" />\n";
				echo "	<param name=\"wmode\" value=\"window\" />\n";
				echo "	<param name=\"scale\" value=\"showall\" />\n";
				echo "	<param name=\"menu\" value=\"true\" />\n";
				echo "	<param name=\"devicefont\" value=\"false\" />\n";
				echo "	<param name=\"salign\" value=\"\" />\n";
				echo "	<param name=\"allowScriptAccess\" value=\"sameDomain\" />\n";
				echo "	<!--[if !IE]>-->\n";
				echo "	<object type=\"application/x-shockwave-flash\" data=\"".$url_adds.$ar['adds_picture2']."\" width=\"".$size[0]."\" height=\"".$size[1]."\">\n";
				echo "		<param name=\"movie\" value=\"".$url_adds.$ar['adds_picture2']."\" />\n";
				echo "		<param name=\"quality\" value=\"high\" />\n";
				echo "		<param name=\"bgcolor\" value=\"#ffffff\" />\n";
				echo "		<param name=\"play\" value=\"true\" />\n";
				echo "		<param name=\"loop\" value=\"true\" />\n";
				echo "		<param name=\"wmode\" value=\"window\" />\n";
				echo "		<param name=\"scale\" value=\"showall\" />\n";
				echo "		<param name=\"menu\" value=\"true\" />\n";
				echo "		<param name=\"devicefont\" value=\"false\" />\n";
				echo "		<param name=\"salign\" value=\"\" />\n";
				echo "		<param name=\"allowScriptAccess\" value=\"sameDomain\" />\n";
				echo "	<!--<![endif]-->\n";
				echo "		<a href=\"http://www.adobe.com/go/getflash\">\n";
				echo "			<img src=\"http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif\" alt=\"Get Adobe Flash player\" />\n";
				echo "		</a>\n";
				echo "	<!--[if !IE]>-->\n";
				echo "	</object>\n";
				echo "	<!--<![endif]-->\n";
				echo "</object>";
			}
		echo "	<br><br></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ADDS_ADDS."</strong></td>\n";
		echo "	<td align=\"left\" valign=\"top\"><input type=\"checkbox\" name=\"adds_main\" value=\"1\" ";if ($ar['adds_main'] == 1 || $_GET['action'] == "adds_add") { echo "checked=\"checked\"></td>\n";}
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ADDS_GFX."</strong></td>\n";
		echo "	<td align=\"left\" valign=\"top\"><input type=\"checkbox\" name=\"adds_gfx\" value=\"1\" ";if ($ar['adds_gfx'] == 1 || $_GET['action'] == "adds_add") { echo "checked=\"checked\"></td>\n";}
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ADDS_LIST."</strong></td>\n";
		echo "	<td align=\"left\" valign=\"top\"><input type=\"checkbox\" name=\"adds_list\" value=\"1\" ";if ($ar['adds_list'] == 1 || $_GET['action'] == "adds_add") { echo "checked=\"checked\"><br><br></td>\n";}
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ADDS_SHOWS."</strong><br><br><br><br><br><br></td>\n";
		echo "	<td align=\"left\" valign=\"top\"><input type=\"radio\" name=\"adds_show\" value=\"unlimited\" "; if ($ar['adds_show_unlimited'] == 1 || $_GET['action'] == "adds_add"){ echo "checked"; } echo ">"._ADDS_UNLIMITED."<br>\n";
		echo "	<input type=\"radio\" name=\"adds_show\" value=\"by_date\" "; if ($ar['adds_show_by_date'] == 1){ echo "checked=\"checked\""; } echo ">"._ADDS_BY_DATE."<br>\n";
		echo "	<input type=\"radio\" name=\"adds_show\" value=\"by_count\" "; if ($ar['adds_show_by_count'] == 1){ echo "checked=\"checked\""; } echo ">"._ADDS_BY_COUNT."</td>\n";
		echo "</tr>\n";
		echo "<tr>";
		if ($_GET['action'] == "adds_add"){
			$dateon = formatTimeS(time());
			$adds_start_date = $dateon[1].".".$dateon[2].".".$dateon[3];
			$dateend = formatTimeS(time() + 60 * 60 * 24 * 7);
			$adds_end_date = $dateend[1].".".$dateend[2].".".$dateend[3];
			$adds_start_h = "00";
			$adds_start_m = "00";
			$adds_end_h = "23";
			$adds_end_m = "59";
		} else {
			$adds_start_date = FormatTimestamp($ar['adds_start'],"d.m.Y");
			$adds_end_date = FormatTimestamp($ar['adds_end'],"d.m.Y");
			$adds_start_h = $ar['adds_start'][9].$ar['adds_start'][10];
			$adds_start_m = $ar['adds_start'][11].$ar['adds_start'][12];
			$adds_end_h = $ar['adds_end'][9].$ar['adds_end'][10];
			$adds_end_m = $ar['adds_end'][11].$ar['adds_end'][12];
		}
		echo "<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ADDS_START."</strong><br></td>\n";
		echo "<td align=\"left\" valign=\"top\">\n";
		echo "		<script language=\"javascript\">\n";
		echo "		var StartDate = new ctlSpiffyCalendarBox(\"StartDate\", \"forma\", \"adds_start_date\", \"btnDate1\",\"".$adds_start_date."\",scBTNMODE_CUSTOMBLUE);\n";
		echo "		var EndDate = new ctlSpiffyCalendarBox(\"EndDate\", \"forma\", \"adds_end_date\", \"btnDate2\",\"".$adds_end_date."\",scBTNMODE_CUSTOMBLUE);\n";
		echo "		</script>\n";
		echo "		<script language=\"javascript\">StartDate.writeControl(); StartDate.dateFormat=\"dd.MM.yyyy\";</script> <strong>-</strong><select name=\"adds_start_h\">";
					for ($i=0;$i<=23;$i++){
						echo "<option value=\"".Zerofill($i,10)."\" "; if ($adds_start_h == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
					}
					echo "</select><strong>:</strong><select name=\"adds_start_m\">";
					for ($i=0;$i<=59;$i++){
						echo "<option value=\"".Zerofill($i,10)."\" "; if ($adds_start_m == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
					}
		echo "			</select>\n";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ADDS_END."</strong><br></td>\n";
		echo "	<td align=\"left\" valign=\"top\">\n";
		echo "	   	<script language=\"javascript\">EndDate.writeControl(); EndDate.dateFormat=\"dd.MM.yyyy\";</script> <strong>-</strong><select name=\"adds_end_h\">";
					for ($i=0;$i<=23;$i++){
						echo "<option value=\"".Zerofill($i,10)."\" "; if ($adds_end_h == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
					}				
					echo "</select><strong>:</strong><select name=\"adds_end_m\">";
					for ($i=0;$i<=59;$i++){
						echo "<option value=\"".Zerofill($i,10)."\" "; if ($adds_end_m == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
					}
		echo "			</select>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ADDS_COUNT."</strong><br></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"adds_count\" size=\"6\" maxlength=\"10\" value=\"".$ar['adds_count']."\">x</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"2\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"submit\" value=\"". _CMN_SUBMIT."\" class=\"eden_button\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
}

/***********************************************************************************************************
*																											
*		MAZANI REKLAMY																						
*																											
***********************************************************************************************************/
function DeleteAdds(){
	
	global $db_adds;
	global $url_adds;
	global $adds_path;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_adds_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	if ($_POST['confirm'] == "true"){
		$res = mysql_query("DELETE FROM $db_adds WHERE adds_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$_GET['action'] = "open";
		$_GET['action_link'] = "open";
		ShowMain();
		include("inc.footer.php");
		exit();
	}
	
	if ($_POST['confirm'] == "false"){
		$_GET['action'] = "open";
		$_GET['action_link'] = "open";
		ShowMain();
		include("inc.footer.php");
		exit();
	}
	
	if (!isset($_POST['confirm'])){
	
	echo Menu();
	
	echo "<br>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\">";
			$res = mysql_query("SELECT adds_name, adds_link, adds_picture, adds_picture2 FROM $db_adds WHERE adds_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar = mysql_fetch_array($res);
			echo "	"._ADDS_NAME.": ".$ar['adds_name']."<br>
					"._ADDS_LINK.": ".$ar['adds_link']."<br>";
				$extenze = substr ($ar['adds_picture'], -3);if ($extenze == "jpg" || $extenze == "gif" || $extenze == "png"){ echo "<p><img src=\"".$url_adds.$ar['adds_picture']."\" border=\"0\"></p>";}
				$extenze = substr ($ar['adds_picture'], -3);
				if ($extenze == "swf"){
					echo "<br><object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://linkload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"468\" height=\"60\" id=\"adv2\" align=\"middle\">\n";
					echo "<param name=\"allowScriptAccess\" value=\"sameDomain\" />\n";
					echo "<param name=\"movie\" value=\"".$url_adds.$ar['adds_picture']."\" />\n";
					echo "<param name=\"menu\" value=\"false\" />\n";
					echo "<param name=\"quality\" value=\"high\" />\n";
					echo "<param name=\"bgcolor\" value=\"#ffffff\" />\n";
					echo "<embed src=\"".$url_adds.$ar['adds_picture']."\" menu=\"false\" quality=\"high\" bgcolor=\"#ffffff\" name=\"".$ar['adds_picture']."\" align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />\n";
					echo "</object><br>";
 				}
				$extenze = substr ($ar['adds_picture2'], -3);if ($extenze == "jpg" || $extenze == "gif" || $extenze == "png"){ echo "<p><img src=\"".$url_adds.$ar['adds_picture2']."\" border=\"0\"></p>";}
 				$extenze = substr ($ar['adds_picture2'], -3);
				if ($extenze == "swf"){
					echo "<br><object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://linkload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"468\" height=\"60\" id=\"adv2\" align=\"middle\">\n";
					echo "<param name=\"allowScriptAccess\" value=\"sameDomain\" />\n";
					echo "<param name=\"movie\" value=\"".$url_adds.$ar['adds_picture2']."\" />\n";
					echo "<param name=\"menu\" value=\"false\" />\n";
					echo "<param name=\"quality\" value=\"high\" />\n";
					echo "<param name=\"bgcolor\" value=\"#ffffff\" />\n";
					echo "<embed src=\"".$url_adds.$ar['adds_picture2']."\" menu=\"false\" quality=\"high\" bgcolor=\"#ffffff\" name=\"".$ar['adds_picture2']."\" align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />\n";
					echo "</object><br>";
 				}
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color:#FF0000;\">"._ADDS_CHECK_DEL."</span></strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\">\n";
		echo "			<form action=\"modul_ads.php?action=adds_del&amp;project=".$_SESSION['project']."&amp;id=".$_GET['id']."&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;id_link=".$_GET['id_link']."&amp;close=".$_GET['close']."&amp;action_link=".$_GET['action_link']."\" method=\"post\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\"  class=\"eden_button\"><br>\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "		<td valign=\"top\">\n";
		echo "			<form action=\"modul_ads.php?action=adds_del&amp;project=".$_SESSION['project']."&amp;id=".$_GET['id']."&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;id_link=".$_GET['id_link']."&amp;close=".$_GET['close']."&amp;action_link=".$_GET['action_link']."\" method=\"post\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_NO."\"  class=\"eden_button_no\"><br>\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"false\"></form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
}

include ("inc.header.php");
	if ($_GET['action'] == "adds_non_published") {ShowNonPublished();}
	if ($_GET['action'] == "adds_edit") { Add(); }
	if ($_GET['action'] == "adds_add") { Add(); }
	if ($_GET['action'] == "adds_del") { DeleteAdds(); }
	if ($_GET['action'] == "open") { ShowMain(); }
	if ($_GET['action'] == "close") { ShowMain(); }
	if ($_GET['action'] == "") { ShowMain(); }
	if ($_GET['action'] == "showmain") { ShowMain(); }
include ("inc.footer.php");