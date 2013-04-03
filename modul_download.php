<?php
/***********************************************************************************************************
*
*		VLOZENI ROZBALOVACIHO TLACITKA
*
***********************************************************************************************************/
function Menu(){
	
	switch($_GET['action']){
		case "managefiles":
			$title = "";
			break;
		case "open":
			$title = "";
			break;
		case "close":
			$title = "";
			break;
		case "dl_add":
			$title = "";
			break;
		case "dl_edit":
			$title = "";
			break;
		case "dl_del":
			$title = "";
			break;
		default:
			$title = "";
	}
	
	$menu = "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td align=\"left\" class=\"nadpis\">"._DOWNLOAD."</td>\n";
	$menu .= "	</tr>\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"Pridat kategorii\">\n";
	$menu .= "			<a href=\"modul_download.php?action=dl_add&amp;project=".$_SESSION['project']."\">"._DOWNLOAD_FILE_ADD."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	$menu .= "			<a href=\"modul_download.php?action=managefiles&amp;project=".$_SESSION['project']."\">"._DOWNLOAD_MNG_FILES."</a>\n";
	$menu .= "		</td>\n";
	$menu .= "	</tr>\n";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['msg']){
		$menu .= "<tr><td class=\"msg\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	$menu .= "</table>";
	/*
	echo "			<tr>\n";
	echo "				<td align=\"left\" class=\"nadpis\">"._DOWNLOAD." - "; if ($_GET['action'] == "dl_edit"){echo _DOWNLOAD_FILE_EDIT." ID:".$_GET['id'];} else {echo _DOWNLOAD_FILE_ADD;} echo "</td>\n";
	echo "			<tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"left\"><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">\n";
	echo "					<a href=\"modul_download.php?project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	echo "				<a href=\"modul_download.php?action_dl=open&action=open&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_dl=".$_GET['id_dl']."&amp;project=".$_SESSION['project']."\">"._DOWNLOAD_LEVEL_UP."</a><br><br>\n";
	echo "				</td>\n";
	echo "		<tr>\n";
	echo "			<td align=\"left\" class=\"nadpis\">"._DOWNLOAD_FILE_DEL."</td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">\n";
	echo "				<a href=\"modul_download.php?id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&id_dl=".$_GET['id_dl']."&amp;action=open&action_dl=open&amp;project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	echo "				<a href=\"modul_download.php?action_dl=open&action=open&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_dl=".$_GET['id_dl']."&amp;project=".$_SESSION['project']."\">"._DOWNLOAD_LEVEL_UP."</a>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	</table>";
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr>\n";
	echo "			<td align=\"left\" class=\"nadpis\">"._DOWNLOAD_MNG_FILES."</td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">\n";
	echo "				<a href=\"modul_download.php?action=open&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&id_dl=".$_GET['id_dl']."&amp;action_dl=open&amp;project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	</table>";
	*/
	return $menu;
}
/***********************************************************************************************************
*
*		VLOZENI ROZBALOVACIHO TLACITKA
*
***********************************************************************************************************/
function Rozbal($podcat,$command,$close,$ar,$ar2,$ar3,$ar4,$ar5,$ar6,$obr){

	if ($podcat > 0 && $command == "open"){
		echo"<a href=\"modul_download.php?action=".$command."&amp;id1=".$ar."&amp;id2=".$ar2."&amp;id3=".$ar3."&amp;id4=".$ar4."&amp;id5=".$ar5."&amp;id6=".$ar6."&amp;close=".$close."&amp;project=".$_SESSION['project']."\" title=\""._DOWLONAD_FOLD_UP."\"><img src=\"images/sys_strom_".$obr."_plus.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\"></a>";
	}elseif ($podcat > 0 && $command == "close"){
		echo "<a href=\"modul_download.php?action=".$command."&amp;id1=".$ar."&amp;id2=".$ar2."&amp;id3=".$ar3."&amp;id4=".$ar4."&amp;id5=".$ar5."&amp;id6=".$ar6."&amp;close=".$close."&amp;project=".$_SESSION['project']."\" title=\""._DOWNLOAD_FOLD_DOWN."\"><img src=\"images/sys_strom_".$obr."_minus.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\"></a>";
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
	
	global $db_download,$db_category,$db_articles;
	global $url_category;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"50\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_IMAGE."</span></td>\n";
	echo "		<td width=\"540\" align=\"left\"><span class=\"nadpis-boxy\">"._DOWNLOAD_CAT_NAME."</span></td>\n";
	echo "		<td width=\"100\" align=\"center\"><span class=\"nadpis-boxy\">"._DOWNLOAD_SUBTOPIC_NUM."</span></td>\n";
	echo "	</tr>";
	//***********************************************************************************
	// Hlavni Menu
	//***********************************************************************************
	$res = mysql_query("SELECT category_id, category_name, category_image, category_admin FROM $db_category WHERE category_parent=0 AND category_download=1 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i = 1;
	while ($ar = mysql_fetch_array($res)){
		$res1 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".(integer)$ar['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num = mysql_fetch_array($res1);
		$resdown = mysql_query("SELECT download_id, download_name, download_version, download_service, download_link, download_size FROM $db_download WHERE download_category1=".(integer)$ar['category_id']." OR download_category2=".(integer)$ar['category_id']." OR download_category3=".(integer)$ar['category_id']." OR download_category4=".(integer)$ar['category_id']." OR download_category5=".(integer)$ar['category_id']." ORDER BY download_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$numdown = mysql_num_rows($resdown);
		$hlavicka = stripslashes($ar['category_name']);
		// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
		if ($_GET['action'] == "open" && $_GET['id1'] == $ar['category_id']) {$command = "close";}elseif ($_GET['action'] == "close" && $_GET['close'] != 0 && $_GET['id1'] == $ar['category_id']){$command = "close";} else {$command = "open";}
		$res01 = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".(integer)$ar['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar01 = mysql_fetch_array($res01);
		$admini = explode (" ", $ar01['category_admin']);
		if ($_GET['action_dl'] == "" || !isset($_GET['action_dl'])){$_GET['action_dl'] = "close";}
		if ($_SESSION['login'] == ""){$admini01 = "FALSE";} else {$admini01 = in_array($_SESSION['login'], $admini);}
		echo "<!-- HLAVNI MENU -->";
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"100\" align=\"left\" valign=\"middle\">";
				if ($numdown >= 1){echo "<a href=\"modul_download.php?action_dl="; if ($_GET['action_dl'] == "open" && $ar['category_id'] == $_GET['id_dl']){echo "close";} else {echo "open";} echo "&amp;action=open&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_open.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_OPEN."\"></a>"; } else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">"; }
		echo "	</td>\n";
		echo "	<td width=\"50\" align=\"center\" valign=\"middle\"><strong>".$ar['category_id']."</strong></td>\n";
		echo "	<td width=\"60\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar['category_image']."\"></td>\n";
		echo "	<td width=\"540\" align=\"left\" valign=\"middle\">"; Rozbal($num[0],$command,"0",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"zacatek");
		echo "		&nbsp;<strong><a href=\"modul_download.php?action=".$command."&amp;id1=".$ar['category_id']."&amp;project=".$_SESSION['project']."\" title=\"\">".$hlavicka."</a></strong> "; /*Zobrazeni poctu souboru v kategorii*/ echo "(".$numdown.")"; if (!isset($ar['category_admin'])){echo "[".$ar['category_admin']."]";} echo "</td>\n";
		echo "	<td width=\"100\" align=\"center\" valign=\"middle\"><strong>".$num[0]."</strong></td>\n";
		echo "</tr>";
		if ($_GET['action_dl'] == "open" && $ar['category_id'] == $_GET['id_dl'] && $numdown > 0){
		echo "	<tr>\n";
		echo "		<td colspan=\"5\">";
		echo "			<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "				<tr bgcolor=\"#DCE3F1\">\n";
		echo "				<td width=\"30\" align=\"left\" valign=\"middle\">&nbsp;</td>\n";
		echo "				<td width=\"40\" align=\"left\" valign=\"middle\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
		echo "				<td width=\"240\" align=\"left\" valign=\"middle\" colspan=\"2\"><span class=\"nadpis-boxy\">"._DOWNLOAD_NAME."</span></td>\n";
		echo "				<td width=\"100\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_VERSION."</span></td>\n";
		echo "				<td width=\"390\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_LINK."</span></td>\n";
		echo "				<td width=\"50\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_SIZE."</span></td>\n";
		echo "			</tr>";
	   	while($ardown = mysql_fetch_array($resdown)){
			echo "		<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FAF4FF'\" bgcolor=\"#FAF4FF\">\n";
			echo "			<td width=\"30\" align=\"left\" valign=\"middle\">&nbsp;</td>\n";
			echo "			<td width=\"40\" align=\"left\" valign=\"middle\">";
								if (CheckPriv("groups_download_edit") == 1 || $admini == "TRUE"){echo "<a href=\"modul_download.php?action=dl_edit&amp;id=".$ardown['download_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> ";}
								if (CheckPriv("groups_download_del") == 1 || $admini == "TRUE"){echo "<a href=\"modul_download.php?action=dl_del&amp;id=".$ardown['download_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a> ";}
			echo "			</td>\n";
			echo "			<td width=\"240\" align=\"left\" valign=\"middle\" colspan=\"2\">&nbsp;<a href=\"modul_download.php?action=".$command."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\">".$ardown['download_name']."</a></td>\n";
			echo "			<td width=\"100\" align=\"center\" valign=\"middle\">".$ardown['download_version']."</td>\n";
			echo "			<td width=\"390\" align=\"left\" valign=\"middle\">".$ardown['download_service'].$ardown['download_link']."</td>\n";
			echo "			<td width=\"50\" align=\"right\" valign=\"middle\">".$ardown['download_size']."</td>\n";
			echo "		</tr>";
		}
		echo "		</table>";
		echo "	</td>";
		echo "</tr>";
	}
	//***********************************************************************************
	// Prvni Podmenu
	//***********************************************************************************
		// Zajisteni rozbaleni, nebo zbaleni senamu po kliknuti
		if (($_GET['action'] == "open" && $_GET['id1'] == $ar['category_id']) || ($_GET['action'] == "close" && $_GET['id1'] == $ar['category_id'] && $_GET['id2'] != "")) {
			$res2 = mysql_query("SELECT category_id, category_name, category_image, category_admin FROM $db_category WHERE category_parent=".(integer)$_GET['id1']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$num2 = mysql_num_rows($res2);
			$a = 1;
			while ($ar2 = mysql_fetch_array($res2)){
				$vys2 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".(integer)$ar2['category_id']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$podcat2 = mysql_fetch_array($vys2);
				// Nacteme programy v danych kategoriich
				$resdown2 = mysql_query("SELECT download_id, download_name, download_version, download_service, download_link, download_size FROM $db_download WHERE download_category1=".(integer)$ar2['category_id']." OR download_category2=".(integer)$ar2['category_id']." OR download_category3=".(integer)$ar2['category_id']." OR download_category4=".(integer)$ar2['category_id']." OR download_category5=".(integer)$ar2['category_id']." ORDER BY download_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$numdown2 = mysql_num_rows($resdown2);
				$hlavicka2 = stripslashes($ar2['category_name']);
				// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
				if ($_GET['action'] == "open" && $_GET['id2'] == $ar2['category_id']) {$command = "close";}elseif ($_GET['action'] == "close" && $_GET['close'] != 1 && $_GET['id2'] == $ar2['category_id']){$command = "close";} else {$command = "open";}
				$res02 = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".(integer)$ar2['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar02 = mysql_fetch_array($res02);
				$admini = explode (" ", $ar02['category_admin']);
				$num02 = count($admini);
				if ($_SESSION['login'] == ""){$admini02 = "FALSE";} else {$admini02 = in_array($_SESSION['login'], $admini);}
				echo "<!-- PRVNI PODMENU -->\n";
				echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='EBEBEC'\" bgcolor=\"#EBEBEC\">\n";
				echo "	<td width=\"100\" align=\"left\" valign=\"middle\">";
						if ($numdown2 >= 1){echo "<a href=\"modul_download.php?action_dl="; if ($_GET['action_dl'] == "open" && $ar2['category_id'] == $_GET['id_dl']){echo "close";} else {echo "open";} echo "&amp;action=open&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar2['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_open.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_OPEN."\"></a>"; } else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">"; }
				echo "	</td>\n";
				echo "	<td width=\"50\" align=\"center\" valign=\"middle\">".$ar2['category_id']."</td>\n";
				echo "	<td width=\"60\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar2['category_image']."\"></td>\n";
				echo "	<td width=\"540\" align=\"left\" valign=\"middle\">";
						if ($num2 > $a){
							Rozbal($podcat2[0],$command,"1",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
						}elseif ($num2 == $a){
							Rozbal($podcat2[0],$command,"1",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
						}
				echo "		&nbsp;<a href=\"modul_download.php?action=".$command."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\" title=\"".$ar2['category_comment']."\">".$hlavicka2."</a>"; /*Zobrazeni poctu souboru v kategorii*/ echo "(".$numdown2.")"." ["; $x=0; while($num02>=$x){if ($_SESSION['login'] == $admini[$x]){echo $admini[$x]."&nbsp;";}elseif ($_SESSION['login'] != $admini[$x] && $admini[$x] != ""){ echo "<a href=\"modul_forum.php?project=".$_SESSION['project']."&amp;action=posta&amp;user=".strtoupper ($_SESSION['login'])."&amp;pm_rec=".$admini[$x]."\">".$admini[$x]."</a>&nbsp;"; }$x++;} echo "]</td>\n";
				echo "	<td width=\"100\" align=\"center\" valign=\"middle\">".$podcat2[0]."</td>\n";
				echo "</tr>";
				//echo "action_dl = ".$_GET['action_dl']."<br>";
				//echo "id_dl = ".$_GET['id_dl']."<br>";
				if ($_GET['action_dl'] == "open" && $ar2['category_id'] == $_GET['id_dl'] && $numdown2 > 0){
				echo "<tr>\n";
				echo "	<td colspan=\"5\">";
				echo "		<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
				echo "			<tr bgcolor=\"#DCE3F1\">\n";
				echo "				<td width=\"30\" align=\"left\" valign=\"middle\">&nbsp;</td>\n";
				echo "				<td width=\"40\" align=\"left\" valign=\"middle\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
				echo "				<td width=\"240\" align=\"left\" valign=\"middle\" colspan=\"2\"><span class=\"nadpis-boxy\">"._DOWNLOAD_NAME."</span></td>\n";
				echo "				<td width=\"100\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_VERSION."</span></td>\n";
				echo "				<td width=\"390\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_LINK."</span></td>\n";
				echo "				<td width=\"50\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_SIZE."</span></td>\n";
				echo "			</tr>";
				while($ardown2 = mysql_fetch_array($resdown2)){
					echo "			<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FAF4FF'\" bgcolor=\"#FAF4FF\">\n";
					echo "			<td width=\"30\" align=\"left\" valign=\"middle\">&nbsp;</td>\n";
					echo "			<td width=\"40\" align=\"left\" valign=\"middle\">"; if (CheckPriv("groups_download_edit") == 1 || $admini == "TRUE"){echo "<a href=\"modul_download.php?action=dl_edit&amp;id=".$ardown2['download_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar2['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> ";}
									if (CheckPriv("groups_download_del") == 1 || $admini == "TRUE"){echo "<a href=\"modul_download.php?action=dl_del&amp;id=".$ardown2['download_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar2['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a> ";}
					echo "			</td>\n";
					echo "			<td width=\"240\" align=\"left\" valign=\"middle\" colspan=\"2\">&nbsp;<a href=\"modul_download.php?action=".$command."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\">".$ardown2['download_name']."</a></td>\n";
					echo "			<td width=\"100\" align=\"center\" valign=\"middle\">".$ardown2['download_version']."</td>\n";
					echo "			<td width=\"390\" align=\"left\" valign=\"middle\">".$ardown2['download_service'].$ardown2['download_link']."</td>\n";
					echo "			<td width=\"50\" align=\"right\" valign=\"middle\">".$ardown2['download_size']."</td>\n";
					echo "		</tr>";
				}
				echo "		</table>";
				echo "	</td>";
				echo "</tr>";
			}
	//***********************************************************************************
	// Druhe Podmenu
	//***********************************************************************************
				// Zajisteni rozbaleni, nebo zbaleni senamu po kliknuti
				if (($_GET['action'] == "open" && $_GET['id2'] == $ar2['category_id']) || ($_GET['action'] == "close" && $_GET['id2'] == $ar2['category_id'] && $_GET['id3'] != "")) {
					$res3 = mysql_query("SELECT category_id, category_name, category_image, category_admin FROM $db_category WHERE category_parent=".(integer)$_GET['id2']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$num3 = mysql_num_rows($res3);
					$b = 1;
					while ($ar3 = mysql_fetch_array($res3)){
						$vys3 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".$ar3['category_id']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$podcat3 = mysql_fetch_array($vys3);
						$resdown3 = mysql_query("SELECT download_id, download_name, download_version, download_service, download_link, download_size FROM $db_download WHERE download_category1=".(float)$ar3['category_id']." OR download_category2=".(float)$ar3['category_id']." OR download_category3=".(float)$ar3['category_id']." OR download_category4=".(float)$ar3['category_id']." OR download_category5=".(float)$ar3['category_id']." ORDER BY download_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$numdown3 = mysql_num_rows($resdown3);
						$hlavicka2 = stripslashes($ar3['category_name']);
						// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
						if ($_GET['action'] == "open" && $_GET['id3'] == $ar3['category_id']) {$command = "close";}elseif ($_GET['action'] == "close" && $_GET['close'] != 2 && $_GET['id3'] == $ar3['category_id']){$command = "close";} else {$command = "open";}
						$res03 = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".(float)$ar3['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar03 = mysql_fetch_array($res03);
						$admini = explode (" ", $ar03['category_admin']);
						$num03 = count($admini);
						if ($_SESSION['login'] == ""){$admini03 = "FALSE";} else {$admini03 = in_array($_SESSION['login'], $admini);}
						echo "<!-- DRUHE PODMENU -->\n";
						echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='EBEBEC'\" bgcolor=\"#EBEBEC\">\n";
						echo "	<td width=\"100\" align=\"left\" valign=\"middle\">";
								if ($numdown3 >= 1){ echo "<a href=\"modul_download.php?action_dl="; if ($_GET['action_dl'] == "open" && $ar3['category_id'] == $_GET['id_dl']){echo "close";} else {echo "open";} echo "&amp;action=open&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar3['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_open.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_OPEN."\"></a>"; } else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">"; }
						echo "	</td>\n";
						echo "	<td width=\"50\" align=\"center\" valign=\"middle\">".$ar3['category_id']."</td>\n";
						echo "	<td width=\"60\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar3['category_image']."\"></td>\n";
						echo "	<td width=\"540\" align=\"left\" valign=\"middle\">";
						if ($num2 > $a && $num3 > $b){
							echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat3[0],$command,"2",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
						}elseif ($num2 > $a && $num3 == $b){
							echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat3[0],$command,"2",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
						}elseif ($num2 == $a && $num3 == $b){
							echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat3[0],$command,"2",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
						}elseif ($num2 == $a && $num3 > $b){
							echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat3[0],$command,"2",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
						}
						echo "	&nbsp;<a href=\"modul_download.php?action=".$command."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\" title=\"".$ar2['komentar']."\">".$hlavicka2."</a> "; /*Zobrazeni poctu souboru v kategorii*/ echo "(".$numdown3.")"." ["; $x=0; while($num03>=$x){if ($_SESSION['login'] == $admini[$x]){echo $admini[$x]."&nbsp;";}elseif ($_SESSION['login'] != $admini[$x] && $admini[$x] != ""){ echo "<a href=\"modul_forum.php?project=".$_SESSION['project']."&amp;action=posta&amp;user=".strtoupper ($_SESSION['login'])."&amp;pm_rec=".$admini[$x]."\">".$admini[$x]."</a>&nbsp;"; } $x++;} echo "]</td>\n";
						echo "	<td width=\"100\" align=\"center\" valign=\"middle\">".$podcat3[0]."</td>\n";
						echo "</tr>";
						if ($_GET['action_dl'] == "open" && $ar3['category_id'] == $_GET['id_dl'] && $numdown3 > 0){
							echo "<tr>\n";
							echo "	<td colspan=\"5\">";
							echo "		<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
							echo "			<tr bgcolor=\"#DCE3F1\">\n";
							echo "				<td width=\"30\" align=\"left\" valign=\"middle\">&nbsp;</td>\n";
							echo "				<td width=\"40\" align=\"left\" valign=\"middle\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
							echo "				<td width=\"240\" align=\"left\" valign=\"middle\" colspan=\"2\"><span class=\"nadpis-boxy\">"._DOWNLOAD_NAME."</span></td>\n";
							echo "				<td width=\"100\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_VERSION."</span></td>\n";
							echo "				<td width=\"390\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_LINK."</span></td>\n";
							echo "				<td width=\"50\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_SIZE."</span></td>\n";
							echo "			</tr>";
						while($ardown3 = mysql_fetch_array($resdown3)){
							echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FAF4FF'\" bgcolor=\"#FAF4FF\">\n";
							echo "	<td width=\"30\" align=\"left\" valign=\"middle\">&nbsp;</td>\n";
							echo "	<td width=\"40\" align=\"left\" valign=\"middle\">"; if (CheckPriv("groups_download_edit") == 1 || $admini == "TRUE"){echo "<a href=\"modul_download.php?action=dl_edit&amp;id=".$ardown3['download_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar3['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> ";}
									if (CheckPriv("groups_download_del") == 1 || $admini == "TRUE"){echo "<a href=\"modul_download.php?action=dl_del&amp;id=".$ardown3['download_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar3['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a> ";}
							echo "	</td>\n";
							echo "	<td width=\"240\" align=\"left\" valign=\"middle\" colspan=\"2\">&nbsp;<a href=\"modul_download.php?action=".$command."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\">".$ardown3['download_name']."</a></td>\n";
							echo "	<td width=\"100\" align=\"center\" valign=\"middle\">".$ardown3['download_version']."</td>\n";
							echo "	<td width=\"390\" align=\"left\" valign=\"middle\">".$ardown3['download_service'].$ardown3['download_link']."</td>\n";
							echo "	<td width=\"50\" align=\"center\" valign=\"middle\">".$ardown3['download_size']."</td>\n";
							echo "</tr>";
						}
						echo " 		</table>\n";
						echo "	</td>\n";
						echo "</tr>";
					}
	//***********************************************************************************
	// Treti Podmenu
	//***********************************************************************************
						// Zajisteni rozbaleni, nebo zbaleni senamu po kliknuti
						if (($_GET['action'] == "open" && $_GET['id3'] == $ar3['category_id']) || ($_GET['action'] == "close" && $_GET['id3'] == $ar3['category_id'] && $_GET['id4'] != "")) {
							$res4 = mysql_query("SELECT category_id, category_name, category_image, category_admin FROM $db_category WHERE category_parent=".(float)$_GET['id3']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$num4 = mysql_num_rows($res4);
							$c = 1;
							while ($ar4 = mysql_fetch_array($res4)){
								$vys4 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".$ar4['category_id']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								$podcat4 = mysql_fetch_array($vys4);
								$resdown4 = mysql_query("SELECT download_id, download_name, download_version, download_service, download_link, download_size FROM $db_download WHERE download_category1=".(float)$ar4['category_id']." OR download_category2=".$ar4['category_id']." OR download_category3=".$ar4['category_id']." OR download_category4=".$ar4['category_id']." OR download_category5=".$ar4['category_id']." ORDER BY download_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								$numdown4 = mysql_num_rows($resdown4);
								$hlavicka2 = stripslashes($ar4['category_name']);
								// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
								if ($_GET['action'] == "open" && $_GET['id4'] == $ar4['category_id']) {$command = "close";}elseif ($_GET['action'] == "close" && $_GET['close'] != 3 && $_GET['id4'] == $ar4['category_id']){$command = "close";} else {$command = "open";}
								$res04 = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".(integer)$ar4['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								$ar04 = mysql_fetch_array($res04);
								$admini = explode (" ", $ar04['category_admin']);
								$num04 = count($admini);
								if ($_SESSION['login'] == ""){$admini04 = "FALSE";} else {$admini04 = in_array($_SESSION['login'], $admini);}
								echo "<!-- TRETI PODMENU -->\n";
								echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='EBEBEC'\" bgcolor=\"#EBEBEC\">\n";
								echo "	<td width=\"100\" align=\"left\" valign=\"middle\">";
										if ($numdown4 >= 1){echo "<a href=\"modul_download.php?action_dl="; if ($_GET['action_dl'] == "open" && $ar4['category_id'] == $_GET['id_dl']){echo "close";} else {echo "open";} echo "&amp;action=open"."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar4['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_open.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_OPEN."\"></a>"; } else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">"; }
								echo "	</td>\n";
								echo "	<td width=\"50\" align=\"center\" valign=\"middle\">".$ar4['category_id']."</td>\n";
								echo "	<td width=\"60\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar4['category_image']."\"></td>\n";
								echo "	<td width=\"540\" align=\"left\" valign=\"middle\">";
										if ($num2 > $a && $num3 > $b && $num4 > $c){
											echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat4[0],$command,"3",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
										}elseif ($num2 > $a && $num3 > $b && $num4 == $c){
											echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat4[0],$command,"3",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
										}elseif ($num2 > $a && $num3 == $b && $num4 == $c){
											echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat4[0],$command,"3",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
										}elseif ($num2 == $a && $num3 == $b && $num4 > $c){
											echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat4[0],$command,"3",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
										}elseif ($num2 > $a && $num3 == $b && $num4 > $c){
											echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat4[0],$command,"3",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
										}elseif ($num2 == $a && $num3 == $b && $num4 == $c){
											echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat4[0],$command,"3",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
										}elseif ($num2 == $a && $num3 > $b && $num4 == $c){
											echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat4[0],$command,"3",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
										}elseif ($num2 == $a && $num3 > $b && $num4 > $c){
											echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat4[0],$command,"3",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
										} else {
											echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat3[0],$command,"3",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
										}
								echo "		&nbsp;<a href=\"modul_download.php?action=".$command."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\" title=\"".$ar2['komentar']."\">".$hlavicka2."</a> "; /*Zobrazeni poctu souboru v kategorii*/ echo "(".$numdown4.")"." ["; $x=0; while($num04>=$x){if ($_SESSION['login'] == $admini[$x]){echo $admini[$x]."&nbsp;";}elseif ($_SESSION['login'] != $admini[$x] && $admini[$x] != ""){ echo "<a href=\"modul_forum.php?project=".$_SESSION['project']."&amp;action=posta&amp;user=".strtoupper ($_SESSION['login'])."&amp;pm_rec=".$admini[$x]."\">".$admini[$x]."</a>&nbsp;"; } $x++;} echo "]</td>";
								echo "	<td width=\"100\" align=\"center\" valign=\"middle\">".$podcat4[0]."</td>\n";
								echo "</tr>";
  //*********************** Zobrazeni programu z databaze, po rozbaleni
								if ($_GET['action_dl'] == "open" && $ar4['category_id'] == $_GET['id_dl'] && $numdown4 > 0){
									echo "<tr>";
									echo "	<td colspan=\"5\">";
									echo "		<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
									echo "			<tr bgcolor=\"#DCE3F1\">\n";
									echo "				<td width=\"30\" align=\"left\" valign=\"middle\">&nbsp;</td>\n";
									echo "				<td width=\"40\" align=\"left\" valign=\"middle\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
									echo "				<td width=\"240\" align=\"left\" valign=\"middle\" colspan=\"2\"><span class=\"nadpis-boxy\">"._DOWNLOAD_NAME."</span></td>\n";
									echo "				<td width=\"100\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_VERSION."</span></td>\n";
									echo "				<td width=\"390\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_LINK."</span></td>\n";
									echo "				<td width=\"50\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_SIZE."</span></td>\n";
									echo "			</tr>";
	   								while($ardown4 = mysql_fetch_array($resdown4)){
										echo "	<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FAF4FF'\" bgcolor=\"#FAF4FF\">\n";
										echo "		<td width=\"30\" align=\"left\" valign=\"middle\">&nbsp;</td>\n";
										echo "		<td width=\"40\" align=\"left\" valign=\"middle\">"; if (CheckPriv("groups_download_edit") == 1 || $admini == "TRUE"){echo "<a href=\"modul_download.php?action=dl_edit&amp;id=".$ardown4['download_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar4['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> ";}
													if (CheckPriv("groups_download_del") == 1 || $admini == "TRUE"){echo "<a href=\"modul_download.php?action=dl_del&amp;id=".$ardown2['download_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar4['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a> ";}
										echo "		</td>\n";
										echo "		<td width=\"240\" align=\"left\" valign=\"middle\" colspan=\"2\">&nbsp;<a href=\"modul_download.php?action=".$command."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\">".$ardown4['download_name']."</a></td>\n";
										echo "		<td width=\"100\" align=\"center\" valign=\"middle\">".$ardown4['download_version']."</td>\n";
										echo "		<td width=\"390\" align=\"left\" valign=\"middle\">".$ardown4['download_service'].$ardown4['download_link']."</td>\n";
										echo "		<td width=\"50\" align=\"right\" valign=\"middle\">".$ardown4['download_size']."</td>\n";
										echo "	</tr>";
									}
									echo "		</table>\n";
									echo "	</td>\n";
									echo "</tr>";
								}
	//***********************************************************************************
	// Ctvrte Podmenu
	//***********************************************************************************
								// Zajisteni rozbaleni, nebo zbaleni senamu po kliknuti
								if (($_GET['action'] == "open" && $_GET['id4'] == $ar4['category_id']) || ($_GET['action'] == "close" && $_GET['id4'] == $ar4['category_id'] && $_GET['id5'] != "")) {
									$res5 = mysql_query("SELECT category_id, category_name, category_image, category_admin FROM $db_category WHERE category_parent=".(float)$_GET['id4']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
									$num5 = mysql_num_rows($res5);
									$d = 1;
									while ($ar5 = mysql_fetch_array($res5)){
										$vys5 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".$ar5['category_id']." ORDER BY `category_name` ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
										$podcat5 = mysql_fetch_array($vys5);
										$resdown5 = mysql_query("SELECT download_id, download_name, download_version, download_service, download_link, download_size FROM $db_download WHERE download_category1=".(float)$ar5['category_id']." OR download_category2=".(float)$ar5['category_id']." OR download_category3=".(float)$ar5['category_id']." OR download_category4=".(float)$ar5['category_id']." OR download_category5=".(float)$ar5['category_id']." ORDER BY download_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
										$numdown5 = mysql_num_rows($resdown5);
										$hlavicka2 = stripslashes($ar5['category_name']);
										// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
										if ($_GET['action'] == "open" && $_GET['id5'] == $ar5['category_id']) {$command = "close";} else {$command = "open";}
										$res05 = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".(float)$ar5['category_id']."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
										$ar05 = mysql_fetch_array($res05);
										$admini = explode (" ", $ar05['category_admin']);
										$num05 = count($admini);
										if ($_SESSION['login'] == ""){$admini05 = "FALSE";} else {$admini05 = in_array($_SESSION['login'], $admini);}
										echo "<!-- CTVRTE PODMENU -->\n";
										echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='EBEBEC'\" bgcolor=\"#EBEBEC\">\n";
										echo "	<td width=\"100\" align=\"left\" valign=\"middle\">";
												if ($numdown5 >= 1){ echo "<a href=\"modul_download.php?action_dl="; if ($_GET['action_dl'] == "open" && $ar5['category_id'] == $_GET['id_dl']){echo "close";} else {echo "open";} echo "&amp;action=open&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar5['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_open.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_OPEN."\"></a>"; } else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">"; }
										echo "	</td>\n";
										echo "	<td width=\"50\" align=\"center\" valign=\"middle\">".$ar5['category_id']."</td>\n";
										echo "	<td width=\"60\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar4['category_image']."\"></td>\n";
										echo "	<td width=\"540\" align=\"left\" valign=\"middle\">";
												if ($num2 > $a && $num3 > $b && $num4 > $c && $num5 > $d){
													echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
												}elseif ($num2 > $a && $num3 > $b && $num4 > $c && $num5 == $d){
													echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
												}elseif ($num2 > $a && $num3 == $b && $num4 == $c && $num5 == $d){
													echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
												}elseif ($num2 == $a && $num3 == $b && $num4 > $c && $num5 > $d){
													echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
												}elseif ($num2 > $a && $num3 == $b && $num4 > $c && $num5 > $d){
													echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
												}elseif ($num2 == $a && $num3 > $b && $num4 > $c && $num5 == $d){
													echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
												}elseif ($num2 == $a && $num3 == $b && $num4 == $c && $num5 > $d){
													echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
												}elseif ($num2 == $a && $num3 > $b && $num4 == $c && $num5 > $d){
													echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
												}elseif ($num2 > $a && $num3 > $b && $num4 == $c && $num5 == $d){
													echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
												}elseif ($num2 == $a && $num3 > $b && $num4 == $c && $num5 == $d){
													echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
												}elseif ($num2 == $a && $num3 > $b && $num4 > $c && $num5 > $d){
													echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
												}elseif ($num2 == $a && $num3 == $b && $num4 > $c && $num5 == $d){
													echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
												}elseif ($num2 > $a && $num3 > $b && $num4 == $c && $num5 > $d){
													echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
												}elseif ($num2 > $a && $num3 == $b && $num4 == $c && $num5 > $d){
													echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
												}elseif ($num2 > $a && $num3 == $b && $num4 > $c && $num5 == $d){
													echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
												}elseif ($num2 == $a && $num3 == $b && $num4 == $c && $num5 == $d){
													echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
												}
										echo "		&nbsp;<a href=\"modul_download.php?action=".$command."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\" title=\"".$ar2['komentar']."\">".$hlavicka2."</a> "; /*Zobrazeni poctu souboru v kategorii*/ echo "(".$numdown5.")"." ["; $x=0; while($num05>=$x){if ($_SESSION['login'] == $admini[$x]){echo $admini[$x]."&nbsp;";}elseif ($_SESSION['login'] != $admini[$x] && $admini[$x] != ""){ echo "<a href=\"modul_forum.php?project=".$_SESSION['project']."&amp;action=posta&amp;user=".strtoupper ($_SESSION['login'])."&amp;pm_rec=".$admini[$x]."\">".$admini[$x]."</a>&nbsp;"; } $x++;} echo "]</td>\n";
										echo "	<td width=\"100\" align=\"center\" valign=\"middle\">".$podcat5[0]."</td>\n";
										echo "</tr>";
										if ($_GET['action_dl'] == "open" && $ar5['category_id'] == $_GET['id_dl'] && $numdown5 > 0){
											echo "<tr>";
											echo "	<td colspan=\"5\">";
											echo "		<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
											echo "			<tr bgcolor=\"#DCE3F1\">\n";
											echo "				<td width=\"30\" align=\"left\" valign=\"middle\">&nbsp;</td>\n";
											echo "				<td width=\"40\" align=\"left\" valign=\"middle\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
											echo "				<td width=\"240\" align=\"left\" valign=\"middle\" colspan=\"2\"><span class=\"nadpis-boxy\">"._DOWNLOAD_NAME."</span></td>\n";
											echo "				<td width=\"100\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_VERSION."</span></td>\n";
											echo "				<td width=\"390\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_LINK."</span></td>\n";
											echo "				<td width=\"50\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_SIZE."</span></td>\n";
											echo "			</tr>";
 											//*********************** Zobrazeni programu z databaze, po rozbaleni
											while($ardown5 = mysql_fetch_array($resdown5)){
												echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FAF4FF'\" bgcolor=\"#FAF4FF\">\n";
												echo "	<td width=\"30\" align=\"left\" valign=\"middle\">&nbsp;</td>\n";
												echo "	<td width=\"40\" align=\"left\" valign=\"middle\">"; if (CheckPriv("groups_download_edit") == 1 || $admini == "TRUE"){echo "<a href=\"modul_download.php?action=dl_edit&amp;id=".$ardown5['download_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar5['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> ";}
														if (CheckPriv("groups_download_del") == 1 || $admini == "TRUE"){echo "<a href=\"modul_download.php?action=dl_del&amp;id=".$ardown5['download_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar5['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a> ";}
												echo "	</td>\n";
												echo "	<td width=\"240\" align=\"left\" valign=\"middle\" colspan=\"2\">&nbsp;<a href=\"modul_download.php?action=".$command."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\">".$ardown5['download_name']."</a></td>\n";
												echo "	<td width=\"100\" align=\"center\" valign=\"middle\">".$ardown5['download_version']."</td>\n";
												echo "	<td width=\"390\" align=\"left\" valign=\"middle\">".$ardown5['download_service'].$ardown5['download_link']."</td>\n";
												echo "	<td width=\"50\" align=\"right\" valign=\"middle\">".$ardown5['download_size']."</td>\n";
												echo "</tr>";
	 										}
											echo "		</table>";
											echo "	</td>";
											echo "</tr>";
 										}
	//***********************************************************************************
	// Pate Podmenu
	//***********************************************************************************
										// Zajisteni rozbaleni, nebo zbaleni senamu po kliknuti
										if (($_GET['action'] == "open" && $_GET['id5'] == $ar5['category_id']) || ($_GET['action'] == "close" && $_GET['id5'] == $ar5[category_id] && $_GET['id6'] != "")) {
	 										$res6 = mysql_query("SELECT category_id, category_name, category_image, category_admin FROM $db_category WHERE category_parent=".(float)$_GET['id5']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
											$num6 = mysql_num_rows($res6);
											$e = 1;
											while ($ar6 = mysql_fetch_array($res6)){
												$vys6 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".(float)$ar6['category_id']." ORDER BY `category_name` ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
												$podcat6 = mysql_fetch_array($vys6);
												$resdown6 = mysql_query("SELECT download_id, download_name, download_version, download_service, download_link, download_size FROM $db_download WHERE download_category1=".(float)$ar6['category_id']." OR download_category2=".(float)$ar6['category_id']." OR download_category3=".(float)$ar6['category_id']." OR download_category4=".(float)$ar6['category_id']." OR download_category5=".(float)$ar6['category_id']." ORDER BY download_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
												$numdown6 = mysql_num_rows($resdown6);
												$hlavicka2 = stripslashes($ar6['category_name']);
												// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
												if ($_GET['action'] == "open" && $_GET['id6'] == $ar6['category_id']) {$command = "close";} else {$command = "open";}
												$res06 = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".(float)$ar6['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
												$ar06 = mysql_fetch_array($res06);
												$admini = explode (" ", $ar06['category_admin']);
												$num06 = count($admini);
												if ($_SESSION['login'] == ""){$admini06 = "FALSE";} else {$admini06 = in_array($_SESSION['login'], $admini);}
												echo "<!-- PATE PODMENU -->\n";
												echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='EBEBEC'\" bgcolor=\"#EBEBEC\">\n";
												echo "	<td width=\"100\" align=\"left\" valign=\"middle\">";
														if ($numdown6 >= 1){ echo "<a href=\"modul_download.php?action_dl="; if ($_GET['action_dl'] == "open" && $ar6['category_id'] == $_GET['id_dl']){echo "close";} else {echo "open";} echo "&amp;action=open&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_open.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_OPEN."\"></a>"; } else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">"; }
												echo "	</td>\n";
												echo "	<td width=\"50\" align=\"center\" valign=\"middle\">".$ar6['category_id']."</td>\n";
												echo "	<td width=\"60\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar4['category_image']."\"></td>\n";
												echo "	<td width=\"540\" align=\"left\" valign=\"middle\">";
														if ($num2 > $a && $num3 > $b && $num4 > $c && $num5 > $d && $num6 > $e){
															echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 > $a && $num3 > $b && $num4 > $c && $num5 > $d && $num6 == $e){
															print "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 > $a && $num3 > $b && $num4 > $c && $num5 == $d && $num6 > $e){
															echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 > $a && $num3 > $b && $num4 > $c && $num5 == $d && $num6 == $e){
															echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 > $a && $num3 == $b && $num4 == $c && $num5 == $d && $num6 > $e){
															echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 > $a && $num3 == $b && $num4 == $c && $num5 == $d && $num6 == $e){
															echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 == $a && $num3 == $b && $num4 > $c && $num5 > $d && $num6 > $e){
															echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 == $a && $num3 == $b && $num4 > $c && $num5 > $d && $num6 == $e){
															echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 > $a && $num3 == $b && $num4 > $c && $num5 > $d && $num6 > $e){
															echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 > $a && $num3 == $b && $num4 > $c && $num5 > $d && $num6 == $e){
															echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 == $a && $num3 > $b && $num4 > $c && $num5 == $d && $num6 > $e){
															echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 == $a && $num3 > $b && $num4 > $c && $num5 == $d && $num6 == $e){
															echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 == $a && $num3 == $b && $num4 == $c && $num5 > $d && $num6 > $e){
															echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 == $a && $num3 == $b && $num4 == $c && $num5 > $d && $num6 == $e){
															echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 == $a && $num3 > $b && $num4 == $c && $num5 > $d && $num6 > $e){
															echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 == $a && $num3 > $b && $num4 == $c && $num5 > $d && $num6 == $e){
															echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 > $a && $num3 > $b && $num4 == $c && $num5 == $d && $num6 > $e){
															echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 > $a && $num3 > $b && $num4 == $c && $num5 == $d && $num6 == $e){
															echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 == $a && $num3 > $b && $num4 == $c && $num5 == $d && $num6 > $e){
															echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 == $a && $num3 > $b && $num4 == $c && $num5 == $d && $num6 == $e){
															echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 == $a && $num3 > $b && $num4 > $c && $num5 > $d && $num6 > $e){
															echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 == $a && $num3 > $b && $num4 > $c && $num5 > $d && $num6 == $e){
															echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 == $a && $num3 == $b && $num4 > $c && $num5 == $d && $num6 > $e){
															echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 == $a && $num3 == $b && $num4 > $c && $num5 == $d && $num6 == $e){
															echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 > $a && $num3 > $b && $num4 == $c && $num5 > $d && $num6 > $e){
															echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 > $a && $num3 > $b && $num4 == $c && $num5 > $d && $num6 == $e){
															echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 > $a && $num3 == $b && $num4 == $c && $num5 > $d && $num6 > $e){
															echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 > $a && $num3 == $b && $num4 == $c && $num5 > $d && $num6 == $e){
															echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 > $a && $num3 == $b && $num4 > $c && $num5 == $d && $num6 > $e){
															echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 > $a && $num3 == $b && $num4 > $c && $num5 == $d && $num6 == $e){
															echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 == $a && $num3 == $b && $num4 == $c && $num5 == $d && $num6 > $e){
															echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}elseif ($num2 == $a && $num3 == $b && $num4 == $c && $num5 == $d && $num6 == $e){
															echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
														}
												echo "		&nbsp;<a href=\"modul_download.php?action=".$command."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\" title=\"".$ar2['komentar']."\">".$hlavicka2."</a> "; /*Zobrazeni poctu souboru v kategorii*/ echo "(".$numdown6.")"." ["; $x=0; while($num06>=$x){if ($_SESSION['login'] == $admini[$x]){echo $admini[$x]."&nbsp;";}elseif ($_SESSION['login'] != $admini[$x] && $admini[$x] != ""){ echo "<a href=\"modul_forum.php?project=".$_SESSION['project']."&amp;action=posta&amp;user=".strtoupper ($_SESSION['login'])."&amp;pm_rec=".$admini[$x]."\">".$admini[$x]."</a>&nbsp;"; }$x++;} echo "]</td>\n";
												echo "	<td width=\"100\" align=\"center\" valign=\"middle\">".$podcat6[0]."</td>\n";
												echo "</tr>";
	 //*********************** Zobrazeni programu z databaze, po rozbaleni
												if ($_GET['action_dl'] == "open" && $ar6['category_id'] == $_GET['id_dl'] && $numdown6 > 0){
													echo "<tr>";
													echo "	<td colspan=\"5\">";
													echo "		<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
													echo "			<tr bgcolor=\"#DCE3F1\">\n";
													echo "				<td width=\"100\" align=\"left\" valign=\"middle\">&nbsp;</td>\n";
													echo "				<td width=\"40\" align=\"left\" valign=\"middle\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
													echo "				<td width=\"340\" align=\"left\" valign=\"middle\" colspan=\"2\"><span class=\"nadpis-boxy\">"._DOWNLOAD_NAME."</span></td>\n";
													echo "				<td width=\"100\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_VERSION."</span></td>\n";
													echo "				<td width=\"220\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_LINK."</span></td>\n";
													echo "				<td width=\"50\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_SIZE."</span></td>\n";
													echo "			</tr>";
													while($ardown6 = mysql_fetch_array($resdown6)){
														echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FAF4FF'\" bgcolor=\"#FAF4FF\">\n";
														echo "	<td width=\"100\" align=\"left\" valign=\"middle\">&nbsp;</td>\n";
														echo "	<td width=\"40\" align=\"left\" valign=\"middle\">"; if (CheckPriv("groups_download_edit") == 1 || $admini == "TRUE"){echo "<a href=\"modul_download.php?action=dl_edit&amp;id=".$ardown6['download_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> ";}
																if (CheckPriv("groups_download_del") == 1 || $admini == "TRUE"){echo "<a href=\"modul_download.php?action=dl_del&amp;id=".$ardown6['download_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a> ";}
														echo "	</td>\n";
														echo "	<td width=\"340\" align=\"left\" valign=\"middle\" colspan=\"2\">&nbsp;<a href=\"modul_download.php?action=".$command."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\">".$ardown6['download_name']."</a></td>\n";
														echo "	<td width=\"100\" align=\"center\" valign=\"middle\">".$ardown6['download_version']."</td>\n";
														echo "	<td width=\"300\" align=\"left\" valign=\"middle\">".$ardown6['download_service'].$ardown6['download_link']."</td>\n";
														echo "	<td width=\"50\" align=\"right\" valign=\"middle\">".$ardown6['download_size']."</td>\n";
														echo "</tr>";
													}
													echo "		</table>";
													echo "	</td>";
													echo "</tr>";
												}
											$e++;
											}
										}
									$d++;
									}
								}
							$c++;
							}
						}
					$b++;
					}
				}
			$a++;
			}
		}
	$i++;
	}
	echo "	<tr>";
	echo "		<td colspan=\"5\">";
	echo "			<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "				<tr bgcolor=\"#DCE3F1\">\n";
	echo "					<td colspan=\"6\" height=\"30\" align=\"left\" valign=\"middle\">&nbsp;</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td colspan=\"6\" align=\"left\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_MISFILES."</span></td>\n";
	echo "				</tr>\n";
	echo "				<tr bgcolor=\"#DCE3F1\">\n";
	echo "					<td width=\"40\" align=\"left\" valign=\"middle\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "					<td width=\"340\" align=\"left\" valign=\"middle\" colspan=\"2\"><span class=\"nadpis-boxy\">"._DOWNLOAD_NAME."</span></td>\n";
	echo "					<td width=\"100\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_VERSION."</span></td>\n";
	echo "					<td width=\"220\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_LINK."</span></td>\n";
	echo "					<td width=\"50\" align=\"center\" valign=\"middle\"><span class=\"nadpis-boxy\">"._DOWNLOAD_SIZE."</span></td>\n";
	echo "				</tr>";
 					$res7 = mysql_query("SELECT download_id, download_name, download_version, download_service, download_link, download_size FROM $db_download WHERE download_category1='none'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ardown7 = mysql_fetch_array($res7)){
						echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FAF4FF'\" bgcolor=\"#FAF4FF\">";
						echo "	<td width=\"40\" align=\"left\" valign=\"middle\">"; if (CheckPriv("groups_download_edit") == 1 || $admini == "TRUE"){echo "<a href=\"modul_download.php?action=dl_edit&amp;id=".$ardown7['download_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar2['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> ";}
							if (CheckPriv("groups_download_del") == 1 || $admini == "TRUE"){echo "<a href=\"modul_download.php?action=dl_del&amp;id=".$ardown7['download_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_dl=".$ar2['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a> ";}
						echo "	</td>\n";
						echo "	<td width=\"340\" align=\"left\" valign=\"middle\" colspan=\"2\">&nbsp;<a href=\"modul_download.php?action=".$command."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\">".$ardown7['download_name']."</a></td>\n";
						echo "	<td width=\"100\" align=\"center\" valign=\"middle\">".$ardown7['download_version']."</td>\n";
						echo "	<td width=\"300\" align=\"left\" valign=\"middle\">".$ardown7['download_link']."</td>\n";
						echo "	<td width=\"50\" align=\"center\" valign=\"middle\">".$ardown7['download_size']."</td>\n";
						echo "</tr>";
 					}
	echo "			</table>";
	echo "		</td>";
	echo "	</tr>";
 	echo "</table>";
}
/***********************************************************************************************************
*
*		PRIDANI PROGRAMU
*
***********************************************************************************************************/
function AddProgram(){
	
	global $db_download,$db_category;
	global $eden_cfg;
	global $url_download;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_download_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	// Provereni opravneni
	if ($_GET['action'] == "dl_add"){
		if (CheckPriv("groups_download_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($_GET['action'] == "dl_edit"){
		if (CheckPriv("groups_download_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}
	
	if ($_GET['action'] == "dl_edit"){
		// Nacteni polozek z databaze
		$res = mysql_query("SELECT * FROM $db_download WHERE download_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\" valign=\"top\"><form name=\"form1\" action=\"sys_save.php?action="; if ($_GET['action'] == "dl_edit"){echo "dl_edit";} else {echo "dl_add";} echo "&amp;id=".$_GET['id']."&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;id_dl=".$_GET['id_dl']."&amp;project=".$_SESSION['project']."\" enctype=\"multipart/form-data\" method=\"post\"><strong>"._DOWNLOAD_NAME."</strong></td>\n";
	echo "		<td width=\"649\" align=\"left\" valign=\"top\"><input name=\"dl_name\" maxlength=\"120\" size=\"40\" "; if ($_GET['action'] == "dl_edit"){echo "value=\"".stripslashes($ar['download_name'])."\"";} echo "></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\" valign=\"top\"><strong>"._DOWNLOAD_VERSION."</strong></td>\n";
	echo "		<td width=\"649\" align=\"left\" valign=\"top\"><input name=\"dl_version\" maxlength=\"120\" size=\"40\" "; if ($_GET['action'] == "dl_edit"){echo "value=\"".$ar['download_version']."\"";} echo "></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\" valign=\"top\"><strong>"._DOWNLOAD_DESC."</strong></td>\n";
	echo "		<td width=\"649\" align=\"left\" valign=\"top\"><textarea name=\"dl_desc\" rows=\"3\" cols=\"40\">"; if ($_GET['action'] == "dl_edit"){echo stripslashes($ar['download_description']);} echo "</textarea></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\" valign=\"top\"><strong>"._DOWNLOAD_WEB."</strong></td>\n";
	echo "		<td width=\"649\" align=\"left\" valign=\"top\"><input name=\"dl_author_web\" maxlength=\"120\" size=\"40\" "; if ($_GET['action'] == "dl_edit"){echo "value=\"".$ar['download_author_web']."\"";} echo "> Aka. www.blackfoot.cz</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\" valign=\"top\"><strong>"._DOWNLOAD_LINK."</strong></td>\n";
	echo "		<td width=\"649\" align=\"left\" valign=\"top\"><select name=\"dl_service\">\n";
	echo "			<option value=\"http://\" "; if ($ar['download_service'] == "http://"){echo "selected";} echo ">http://</option>\n";
	echo "			<option value=\"ftp://\" "; if ($ar['download_service'] == "ftp://"){echo "selected";} echo ">ftp://</option></select><input name=\"dl_link\" maxlength=\"120\" size=\"40\" "; if ($_GET['action'] == "dl_edit"){echo "value=\"".$ar['download_link']."\"";} echo "> Aka. www.blackfoot.cz/pub/eden.zip</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\" valign=\"top\"><strong>"._DOWNLOAD_LINK."</strong></td>\n";
	echo "		<td width=\"649\" align=\"left\" valign=\"top\"><select name=\"dl_service2\">\n";
	echo "			<option value=\"http://\" "; if ($ar['download_service2'] == "http://"){echo "selected";} echo ">http://</option>\n";
	echo "			<option value=\"ftp://\" "; if ($ar['download_service2'] == "ftp://"){echo "selected";} echo ">ftp://</option></select><input name=\"dl_link2\" maxlength=\"120\" size=\"40\" "; if ($_GET['action'] == "dl_edit"){echo "value=\"".$ar['download_link2']."\"";} echo "> Aka. www.blackfoot.cz/pub/eden.zip</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\" valign=\"top\"><strong>"._DOWNLOAD_LINK."</strong></td>\n";
	echo "		<td width=\"649\" align=\"left\" valign=\"top\"><select name=\"dl_service3\">\n";
	echo "			<option value=\"http://\" "; if ($ar['download_service3'] == "http://"){echo "selected";} echo ">http://</option>\n";
	echo "			<option value=\"ftp://\" "; if ($ar['download_service3'] == "ftp://"){echo "selected";} echo ">ftp://</option></select><input name=\"dl_link3\" maxlength=\"120\" size=\"40\" "; if ($_GET['action'] == "dl_edit"){echo "value=\"".$ar['download_link3']."\"";} echo "> Aka. www.blackfoot.cz/pub/eden.zip</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\" valign=\"top\"><strong>"._DOWNLOAD_SIZE."</strong></td>\n";
	echo "		<td width=\"649\" align=\"left\" valign=\"top\"><input name=\"dl_size\" maxlength=\"120\" size=\"20\" "; if ($_GET['action'] == "dl_edit"){echo "value=\"".$ar['download_size']."\"";} echo "></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\" valign=\"top\"><strong>"._DOWNLOAD_LANG."</strong></td>\n";
	echo "		<td width=\"649\" align=\"left\" valign=\"top\"><textarea name=\"dl_lang\" rows=\"3\" cols=\"40\"> "; if ($_GET['action'] == "dl_edit"){echo $ar['download_languages'];} echo "</textarea></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\" valign=\"top\"><strong>"._DOWNLOAD_FILE_ADD_CATEGORY."</strong></td>\n";
	echo "		<td width=\"649\" align=\"left\" valign=\"top\">";
					// Select category
					echo EdenCategorySelect($ar['download_category1'], "download", "dl_category1", _DOWNLOAD_NONE);
					echo "&nbsp;&nbsp;";
					
					echo EdenCategorySelect($ar['download_category1'], "download", "dl_category2", _DOWNLOAD_NONE);
					echo "&nbsp;&nbsp;";
					
					echo EdenCategorySelect($ar['download_category1'], "download", "dl_category3", _DOWNLOAD_NONE);
					echo "&nbsp;&nbsp;";
					
					echo EdenCategorySelect($ar['download_category1'], "download", "dl_category4", _DOWNLOAD_NONE);
					echo "&nbsp;&nbsp;";
					
					echo EdenCategorySelect($ar['download_category1'], "download", "dl_category5", _DOWNLOAD_NONE);
					echo "&nbsp;&nbsp;";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>";
			$dl_os = explode ("||", $ar['download_os']);
			$dl_os_num = count($dl_os);
	echo "		<td width=\"200\" align=\"right\" valign=\"top\"><strong>"._DOWNLOAD_OS."</strong></td>\n";
	echo "		<td width=\"649\" align=\"left\" valign=\"top\"><input type=\"checkbox\" name=\"dl_os[0]\" value=\"DOS\" "; for($i=0;$i<$os_num;$i++){ if ($dl_os[$i] == "DOS"){echo "checked";}} echo ">DOS<br />\n";
	echo "			<input type=\"checkbox\" name=\"dl_os[1]\" value=\"Windows 95\" "; for($i=0;$i<$dl_os_num;$i++){ if ($dl_os[$i] == "Windows 95"){echo "checked";}} echo ">Windows 95<br />\n";
	echo "			<input type=\"checkbox\" name=\"dl_os[2]\" value=\"Windows 98\" "; for($i=0;$i<$dl_os_num;$i++){ if ($dl_os[$i] == "Windows 98"){echo "checked";}} echo ">Windows 98<br />\n";
	echo "			<input type=\"checkbox\" name=\"dl_os[3]\" value=\"Windows ME\" "; for($i=0;$i<$dl_os_num;$i++){ if ($dl_os[$i] == "Windows ME"){echo "checked";}} echo ">Windows ME<br />\n";
	echo "			<input type=\"checkbox\" name=\"dl_os[4]\" value=\"Windows NT 4.0\" "; for($i=0;$i<$dl_os_num;$i++){ if ($dl_os[$i] == "Windows NT 4.0"){echo "checked";}} echo ">Windows NT 4.0<br />\n";
	echo "			<input type=\"checkbox\" name=\"dl_os[5]\" value=\"Windows 2000\" "; for($i=0;$i<$dl_os_num;$i++){ if ($dl_os[$i] == "Windows 2000"){echo "checked";}} echo ">Windows 2000<br />\n";
	echo "			<input type=\"checkbox\" name=\"dl_os[6]\" value=\"Windows XP\" "; for($i=0;$i<$dl_os_num;$i++){ if ($dl_os[$i] == "Windows XP"){echo "checked";}} echo ">Windows XP<br />\n";
	echo "			<input type=\"checkbox\" name=\"dl_os[7]\" value=\"Windows 2003 Server\" "; for($i=0;$i<$dl_os_num;$i++){ if ($dl_os[$i] == "Windows 2003 Server"){echo "checked";}} echo ">Windows 2003 Server<br />\n";
	echo "			<input type=\"checkbox\" name=\"dl_os[8]\" value=\"Windows Vista\" "; for($i=0;$i<$dl_os_num;$i++){ if ($dl_os[$i] == "Windows Vista"){echo "checked";}} echo ">Windows Vista<br />\n";
	echo "			<input type=\"checkbox\" name=\"dl_os[9]\" value=\"Windows 7\" "; for($i=0;$i<$dl_os_num;$i++){ if ($dl_os[$i] == "Windows 7"){echo "checked";}} echo ">Windows 7<br />\n";
	echo "			<input type=\"checkbox\" name=\"dl_os[10]\" value=\"Mac OS X\" "; for($i=0;$i<$dl_os_num;$i++){ if ($dl_os[$i] == "Mac OS X"){echo "checked";}} echo ">Mac OS X<br />\n";
	echo "			<input type=\"checkbox\" name=\"dl_os[11]\" value=\"Linux\" "; for($i=0;$i<$dl_os_num;$i++){ if ($dl_os[$i] == "Linux"){echo "checked";}} echo ">Linux<br />\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\" valign=\"top\"><strong>"._DOWNLOAD_LICENCE."</strong></td>\n";
	echo "		<td width=\"649\" align=\"left\" valign=\"top\">\n";
	echo "			<select name=\"dl_licence\">\n";
	echo "			<option value=\"none\" selected>"._DOWNLOAD_CHECK_LICENCE."</option>\n";
	echo "			<option value=\"Addware\" "; if ($_GET['action'] == "dl_edit" && $ar['download_licence'] == "Addware"){echo "selected";} echo ">Addware</option>\n";
	echo "			<option value=\"Cardware\" "; if ($_GET['action'] == "dl_edit" && $ar['download_licence'] == "Cardware"){echo "selected";} echo ">Cardware</option>\n";
	echo "			<option value=\"Demo\" "; if ($_GET['action'] == "dl_edit" && $ar['download_licence'] == "Demo"){echo "selected";} echo ">Demo</option>\n";
	echo "			<option value=\"Donateware\" "; if ($_GET['action'] == "dl_edit" && $ar['download_licence'] == "Donateware"){echo "selected";} echo ">Donateware</option>\n";
	echo "			<option value=\"Freeware\" "; if ($_GET['action'] == "dl_edit" && $ar['download_licence'] == "Freeware"){echo "selected";} echo ">Freeware</option>\n";
	echo "			<option value=\"GPL\" "; if ($_GET['action'] == "dl_edit" && $ar['download_licence'] == "GPL"){echo "selected";} echo ">GPL</option>\n";
	echo "			<option value=\"MS EULA\" "; if ($_GET['action'] == "dl_edit" && $ar['download_licence'] == "MS EULA"){echo "selected";} echo ">MS EULA</option>\n";
	echo "			<option value=\"Plná verze\" "; if ($_GET['action'] == "dl_edit" && $ar['download_licence'] == "Plná verze"){echo "selected";} echo ">Plná verze</option>\n";
	echo "			<option value=\"Public Domain\" "; if ($_GET['action'] == "dl_edit" && $ar['download_licence'] == "Public Domain"){echo "selected";} echo ">Public Domain</option>\n";
	echo "			<option value=\"Shareware\" "; if ($_GET['action'] == "dl_edit" && $ar['download_licence'] == "Shareware"){echo "selected";} echo ">Shareware</option>\n";
	echo "			<option value=\"Trial\" "; if ($_GET['action'] == "dl_edit" && $ar['download_licence'] == "Trial"){echo "selected";} echo ">Trial</option>\n";
	echo "			</select>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\" valign=\"top\"><strong>"._DOWNLOAD_IMG."</strong></td>\n";
	echo "		<td width=\"649\" align=\"left\" valign=\"top\"><input type=\"file\" name=\"dl_img_1\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LINKS_ILL_IMAGE."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\">\n";
					$extenze = substr ($ar['download_img'], -3);if ($extenze == "jpg" || $extenze == "gif" || $extenze == "png"){ echo "<p><img src=\"".$url_download.$ar['download_img']."\" border=\"0\"></p>"; }
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\" valign=\"top\"><strong>"._DOWNLOAD_RATING."</strong></td>\n";
	echo "		<td width=\"649\" align=\"left\" valign=\"top\">\n";
	echo "			<table>\n";
	echo "				<tr>\n";
	echo "					<td align=\"center\">1<br><input type=\"radio\" name=\"dl_rating\" value=\"1\" "; if ($ar['download_rating'] == "1"){echo "checked";} echo "></td>\n";
	echo "					<td align=\"center\">2<br><input type=\"radio\" name=\"dl_rating\" value=\"2\" "; if ($ar['download_rating'] == "2"){echo "checked";} echo "></td>\n";
	echo "					<td align=\"center\">3<br><input type=\"radio\" name=\"dl_rating\" value=\"3\" "; if ($ar['download_rating'] == "3"){echo "checked";} echo "></td>\n";
	echo "					<td align=\"center\">4<br><input type=\"radio\" name=\"dl_rating\" value=\"4\" "; if ($ar['download_rating'] == "4"){echo "checked";} echo "></td>\n";
	echo "					<td align=\"center\">5<br><input type=\"radio\" name=\"dl_rating\" value=\"5\" "; if ($ar['download_rating'] == "5"){echo "checked";} echo "></td>\n";
	echo "					<td align=\"center\">6<br><input type=\"radio\" name=\"dl_rating\" value=\"6\" "; if ($ar['download_rating'] == "6"){echo "checked";} echo "></td>\n";
	echo "					<td align=\"center\">7<br><input type=\"radio\" name=\"dl_rating\" value=\"7\" "; if ($ar['download_rating'] == "7"){echo "checked";} echo "></td>\n";
	echo "					<td align=\"center\">8<br><input type=\"radio\" name=\"dl_rating\" value=\"8\" "; if ($ar['download_rating'] == "8"){echo "checked";} echo "></td>\n";
	echo "					<td align=\"center\">9<br><input type=\"radio\" name=\"dl_rating\" value=\"9\" "; if ($ar['download_rating'] == "9"){echo "checked";} echo "></td>\n";
	echo "					<td align=\"center\">10<br><input type=\"radio\" name=\"dl_rating\" value=\"10\" "; if ($ar['download_rating'] == "10"){echo "checked";} echo "></td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<!-- <tr>\n";
	echo "		<td width=\"200\" align=\"right\" valign=\"top\"><strong>"._DOWNLOAD_PROGRAM."</strong></td>\n";
	echo "		<td width=\"649\" align=\"left\" valign=\"top\"><input type=\"file\" name=\"userfile\"></td>\n";
	echo "	</tr>-->\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"2\" align=\"center\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "dl_edit"){echo _DOWNLOAD_FILE_EDIT;} else {echo _DOWNLOAD_FILE_ADD;} echo "\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*
*		ODSTRANENI PROGRAMU
*
***********************************************************************************************************/
function DelProgram(){
	
	global $db_download,$db_category;
	global $eden_cfg;
	global $ftp_path_download;
	global $url_download;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_download_del") <> 1) {
		echo _NOTENOUGHPRIV;
		$_GET['action'] = "open";
		$_GET['action_dl'] = "open";
		ShowMain();
		exit;
	}
	if ($_POST['confirm'] == "true") {
		$res = mysql_query("SELECT download_img FROM $db_download WHERE download_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		if ($ar['download_img'] != ""){
			// Zjisteni poctu obrazku v adresari na serveru klienta
			$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP);
			$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
			ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
			
			if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP; die;}
			@ftp_delete($conn_id, $ftp_path_download.$ar[download_img]);
		}
		
		$res = mysql_query("DELETE FROM $db_download WHERE download_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$_GET['action'] = "open";
		$_GET['action_dl'] = "open";
		ShowMain();
		exit;
	}
	if ($confirm == "false"){
		$_GET['action'] = "open";
		$_GET['action_dl'] = "open";
		ShowMain();
		exit;
	}
	if ($_POST['confirm'] == ""){
	
		echo Menu();
		
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr class=\"popisky\">\n";
		echo "		<td><span class=\"nadpis-boxy\">"._DOWNLOAD_NAME."</span></td>\n";
		echo "		<td><span class=\"nadpis-boxy\">"._DOWNLOAD_VERSION."</span></td>\n";
		echo "		<td><span class=\"nadpis-boxy\">"._DOWNLOAD_LINK."</span></td>\n";
		echo "		<td><span class=\"nadpis-boxy\">"._DOWNLOAD_SIZE."</span></td>\n";
		echo "	</tr>";
	   	$res = mysql_query("SELECT download_name, download_version, download_link, download_size FROM $db_download WHERE download_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	   	$ar = mysql_fetch_array($res);
	   	echo "	<tr>";
		echo "		<td>".$ar['download_name']."</td>";
		echo "		<td>".$ar['download_version']."</td>";
		echo "		<td>".$ar['download_link']."</td>";
		echo "		<td>".$ar['download_size']."</td>";
		echo "	</tr>";
		echo "</table>";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" colspan=\"2\"><br><br><strong><span style=\"color : #FF0000;\">"._DOWNLOAD_CHECK_DELETE."</span></strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" width=\"50\">\n";
		echo "		<form action=\"modul_download.php?action=dl_del&amp;id=".$_GET['id']."&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;id_dl=".$_GET['id_dl']."&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "		<td valign=\"top\" width=\"800\">\n";
		echo "			<form action=\"modul_download.php?action=dl_del&amp;id=".$_GET['id']."&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;id_dl=".$_GET['id_dl']."&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"false\"></form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
 	}
}
/***********************************************************************************************************
*
*		SPRAVA SOUBORU - BEZ KATEGORIE
*
***********************************************************************************************************/
function ManageFiles(){
	
	global $db_download;
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._DOWNLOAD_NAME."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._DOWNLOAD_LINK."</span></td>\n";
	echo "	</tr>";
		$res = mysql_query("SELECT download_name, download_link FROM $db_download WHERE download_category1=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		while ($ar = mysql_fetch_array($res)){
			echo "<tr>";
			echo "	<td></td>";
			echo "	<td>".$ar['download_name']."</td>";
			echo "	<td>".$ar['download_link']."</td>";
			echo "</tr>";
		}
	echo "</table>";
}

include ("inc.header.php");
	if ($_GET['action'] == "") { ShowMain(); }
	if ($_GET['action'] == "managefiles") {ManageFiles(); }
	if ($_GET['action'] == "open") { ShowMain(); }
	if ($_GET['action'] == "close") { ShowMain(); }
	if ($_GET['action'] == "dl_add") { AddProgram(); }
	if ($_GET['action'] == "dl_edit") { AddProgram(); }
	if ($_GET['action'] == "dl_del") { DelProgram(); }
include ("inc.footer.php");