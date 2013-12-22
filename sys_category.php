<?php
/***********************************************************************************************************
*																											
*		VLOZENI ROZBALOVACIHO TLACITKA																		
*																											
***********************************************************************************************************/
function Menu(){
	
	switch ($_GET['action']){
   		case "category_add":
			$title = _CAT." - "._CAT_ADD." - ID:".$_GET['id'];
			break;
		case "category_edit":
			$title = _CAT." - "._CAT_EDIT." - ID:".$_GET['id'];
			break;
		case "category_del":
			$title = _CAT." - "._CAT_DELETE." - ID:".$_GET['id'];
			break;
		case "category_img_upload":
			$title = _CAT_UPLOAD_IMAGE;
			break;
		case "topic_add":
			$title = _CAT." - "._CAT_ADD." - ID:".$_GET['id'];
			break;
		case "topic_edit":
			$title = _CAT." - "._CAT_EDIT." - ID:".$_GET['id'];
			break;
		case "topic_manage":
			$title = _CAT." - "._CAT_MANAGE." - ID:".$_GET['id'];
			break;
		default:
			$title = _CAT;
	}
	
	$menu = "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">".$title."</td>\n";
	$menu .= "	</tr>\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">&nbsp;";
	$menu .= "			<a href=\"sys_category.php?action=showmain&amp;mode=".$_GET['mode']."&amp;id=".$_GET['id']."&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	$menu .= "			<a href=\"sys_category.php?action=category_add&amp;project=".$_SESSION['project']."\">"._CAT_ADD."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	$menu .= "			<a href=\"sys_category.php?action=category_img_upload&amp;project=".$_SESSION['project']."\">"._CAT_UPLOAD_IMAGE."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	$menu .= "			<a href=\"sys_category.php?action=topic_manage&amp;project=".$_SESSION['project']."\">"._CAT_MANAGE_TOPIC."</a>";
	$meni .= "		</td>\n";
	$menu .= "	</tr>\n";
	if ($_GET['msg']){
		$menu .= "<tr><td class=\"msg\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	$menu .= "</table>\n";
	
	return $menu;
}
/***********************************************************************************************************
*																											
*		VLOZENI ROZBALOVACIHO TLACITKA																		
*																											
***********************************************************************************************************/
function Rozbal($podcat,$command,$close,$ar,$ar1,$ar2,$ar3,$ar4,$ar5,$ar6,$obr){
	
	if ($podcat > 0 && $command == "open"){
		echo "<a href=\"sys_category.php?action=showmain&amp;mode=".$command."&amp;id=".$ar."&amp;id1=".$ar1."&amp;id2=".$ar2."&amp;id3=".$ar3."&amp;id4=".$ar4."&amp;id5=".$ar5."&amp;id6=".$ar6."&close=".$close."&amp;project=".$_SESSION['project']."\" title=\""._CAT_FOLD_UP."\"><img src=\"images/sys_strom_".$obr."_plus.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\"></a>";
	} elseif ($podcat > 0 && $command == "close"){
		echo "<a href=\"sys_category.php?action=showmain&amp;mode=".$command."&amp;id=".$ar."&amp;id1=".$ar1."&amp;id2=".$ar2."&amp;id3=".$ar3."&amp;id4=".$ar4."&amp;id5=".$ar5."&amp;id6=".$ar6."&close=".$close."&amp;project=".$_SESSION['project']."\" title=\""._CAT_FOLD_DOWN."\"><img src=\"images/sys_strom_".$obr."_minus.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\"></a>";
	} else {
		echo "<img src=\"images/sys_strom_".$obr.".gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
	}
}
/***********************************************************************************************************
*																											
*		ZOBRAZENI SEZNAMU KATEGORII																			
*																											
***********************************************************************************************************/
function ShowMain($mode,$id,$id1,$id2,$id3,$id4,$id5,$id6,$close){
	
	global $db_category,$db_articles;
	global $url_category;
	
	/* Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren */
	KillUse($_SESSION['loginid']);
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"50\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_IMAGE."</span></td>\n";
	echo "		<td width=\"440\" align=\"left\"><span class=\"nadpis-boxy\">"._CAT_NAME."</span></td>\n";
	echo "		<td width=\"50\" align=\"left\"><span class=\"nadpis-boxy\">"._CAT_ACTIVE."</span></td>\n";
	echo "		<td width=\"100\" align=\"left\"><span class=\"nadpis-boxy\">"._CAT_ARCHIVED."</span></td>\n";
	echo "		<td width=\"100\" align=\"center\"><span class=\"nadpis-boxy\">"._CAT_SUBTOPIC_NUMBER."</span></td>\n";
	echo "	</tr>";
	//***********************************************************************************
	// Hlavni Menu
	//***********************************************************************************
	$res = mysql_query("SELECT 
	category_id, 
	category_name, 
	category_news, 
	category_links, 
	category_image, 
	category_comment, 
	category_admin, 
	category_adds, 
	category_shop, 
	category_download, 
	category_stream, 
	category_shows, 
	category_archive, 
	category_active 
	FROM $db_category 
	WHERE category_parent=0 
	ORDER BY category_active DESC, category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i = 1;
	while ($ar = mysql_fetch_array($res)){
		$res1 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".$ar['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num = mysql_fetch_row($res1);
		$hlavicka = stripslashes($ar['category_name']);
		// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
		if ($mode == "open" && $id == $ar['category_id']) {$command = "close"; $command_now = "open";}elseif ($mode == "close" && $close != 0 && $id == $ar['category_id']){$command = "close"; $command_now = "open";} else {$command = "open";$command_now = "close";}
		$admini = explode (" ", $ar['category_admin']);
		$num01 = count($admini);
		if ($_SESSION['login'] == ""){$admini01 = "FALSE";} else {$admini01 = in_array($_SESSION['login'], $admini);}
		// Zvoleni spravne kategorie
		if ($ar['category_news'] == 1){
			$kategorie = "news";
		}elseif ($ar['category_links'] == 1){
			$kategorie = "links";
		} else {
			$kategorie = "articles";
		}
		if (($mode == "open" && $id1 == $ar['category_id']) || ($mode == "close" && $id1 == $ar['category_id'] && $id2 != "")) {$cat_class = "eden_title_middle"; } elseif ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		if ($ar['category_active'] == 0){$cat_class = "cat_over";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"80\" align=\"left\" valign=\"middle\">";
				if (CheckPriv("groups_cat_edit") == 1 || $admini01 == "TRUE"){echo "<a href=\"sys_category.php?action=category_edit&amp;mode=".$command_now."&amp;id=".$ar['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."&kategorie=".$kategorie."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
				if (CheckPriv("groups_cat_del") == 1){echo " <a href=\"sys_category.php?action=category_del&amp;mode=".$command_now."&amp;id=".$ar['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
				if (CheckPriv("groups_cat_add") == 1){echo " <a href=\"sys_category.php?action=topic_add&amp;mode=".$command_now."&amp;id=".$ar['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."&kategorie=".$kategorie."\"><img src=\"images/sys_dtopic.gif\" border=\"0\" alt=\""._CMN_ADD."\"></a>";}
		echo "	</td>\n";
		echo "	<td width=\"50\" align=\"right\" valign=\"middle\"><strong>".$ar['category_id']."</strong></td> \n";
		echo "	<td width=\"80\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar['category_image']."\"></td>\n";
		echo "	<td width=\"440\" align=\"left\" valign=\"middle\">";
				Rozbal($num[0],$command,"0",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"zacatek");
				echo "&nbsp;<strong><a href=\"sys_category.php?action=showmain&amp;mode=".$command."&amp;id=".$ar['category_id']."&amp;id=".$ar['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\" title=\"".$ar['category_comment']."\">".$hlavicka."</a></strong> "; 
				$x=0; 
				while($num01>=$x){
					if ($_SESSION['login'] == $admini[$x]){
						echo $admini[$x]."&nbsp;";
					}elseif ($_SESSION['login'] != $admini[$x] && $admini[$x] != ""){ 
						echo "[<a href=\"modul_forum.php?project=".$_SESSION['project']."&amp;action=posta&amp;user=".strtoupper($_SESSION['login'])."&amp;pm_rec=".$admini[$x]."\">".$admini[$x]."</a>&nbsp;]"; 
					}
					$x++;
				} 
				if ($ar['category_news'] == 1){echo "<span class=\"red\"> | "._CAT_NEWS_S."</span>";} 
				if ($ar['category_links'] == 1){echo "<span class=\"red\"> | "._CAT_LINKS_S."</span>";} 
				if ($ar['category_adds'] == 1){echo "<span class=\"red\"> | "._CAT_ADDS_S."</span>";} 
				if ($ar['category_shop'] == 1){echo "<span class=\"red\"> | "._CAT_SHOP_S."</span>";} 
				if ($ar['category_download'] == 1){echo "<span class=\"red\"> | "._CAT_DOWNLOAD_S."</span>";} 
				if ($ar['category_stream'] == 1){echo "<span class=\"red\"> | "._CAT_STREAM_S."</span>";} 
				if ($ar['category_shows'] != 1){echo "<span class=\"blue\"> | "._CAT_DONTSHOW."</span>";}
		echo 	"</td>\n";
		echo "	<td width=\"50\" align=\"center\" valign=\"middle\"><img src=\"images/sys_"; if ($ar['category_active'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
		echo "	<td width=\"100\" align=\"center\" valign=\"middle\"><strong>"; if ($ar['category_archive'] == 1){echo _CMN_YES;} else {echo _CMN_NO;} echo "</strong></td>\n";
		echo "	<td width=\"100\" align=\"center\" valign=\"middle\"><strong>".$num[0]."</strong></td>\n";
		echo "</tr>";
 	//***********************************************************************************
	// Prvni Podmenu
	//***********************************************************************************
		// Zajisteni rozbaleni, nebo zbaleni senamu po kliknuti
		if (($mode == "open" && $id1 == $ar['category_id']) || ($mode == "close" && $id1 == $ar['category_id'] && $id2 != "")) {
 			$res2 = mysql_query("SELECT 
			category_id, 
			category_name, 
			category_news, 
			category_links, 
			category_image, 
			category_comment, 
			category_admin, 
			category_adds, 
			category_shop, 
			category_download, 
			category_stream, 
			category_shows, 
			category_archive, 
			category_active 
			FROM $db_category 
			WHERE category_parent=".$id1." 
			ORDER BY category_active DESC, category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$num2 = mysql_num_rows($res2);
			$a = 1;
			while ($ar2 = mysql_fetch_array($res2)){
				$vys2 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".$ar2['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$podcat2 = mysql_fetch_array($vys2);
				$hlavicka2 = stripslashes($ar2['category_name']);
				// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
				if ($mode == "open" && $id2 == $ar2['category_id']) {$command = "close";}elseif ($mode == "close" && $close != 1 && $id2 == $ar2['category_id']){$command = "close";} else {$command = "open";}
				$admini = explode (" ", $ar2['category_admin']);
				$num02 = count($admini);
				if ($_SESSION['login'] == ""){$admini02 = "FALSE";} else {$admini02 = in_array($_SESSION['login'], $admini);}
				if ($a % 2 == 0){ $cat_class = "cat_level2_even";} else { $cat_class = "cat_level2_odd";}
				if ($ar2['category_active'] == 0){$cat_class = "cat_over";}
	   			echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
				echo "	<td width=\"80\" align=\"left\" valign=\"middle\">";
					if (CheckPriv("groups_cat_edit") == 1 || $admini02 == "TRUE"){echo "<a href=\"sys_category.php?action=topic_edit&amp;mode=".$command_now."&amp;id=".$ar2['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."&kategorie=".$kategorie."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> ";}
					if (CheckPriv("groups_cat_del") == 1){echo "<a href=\"sys_category.php?action=category_del&amp;mode=".$command_now."&amp;id=".$ar2['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a> ";}
					if (CheckPriv("groups_cat_add") == 1){echo "<a href=\"sys_category.php?action=topic_add&amp;mode=".$command_now."&amp;id=".$ar2['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."&kategorie=".$kategorie."\"><img src=\"images/sys_dtopic.gif\" border=\"0\" alt=\""._CMN_ADD."\"></a>";}
				echo "</td>\n";
				echo "<td width=\"50\" align=\"center\" valign=\"middle\">".$ar2['category_id']."</td> \n";
				echo "<td width=\"80\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar2['category_image']."\"></td>\n";
				echo "<td width=\"440\" align=\"left\" valign=\"middle\">";
					if ($num2 > $a){
						Rozbal($podcat2[0],$command,"1",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
					}elseif ($num2 == $a){
						Rozbal($podcat2[0],$command,"1",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
					}
					echo "		&nbsp;<a href=\"sys_category.php?action=showmain&amp;mode=".$command."&amp;id=".$ar['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&close=1&amp;project=".$_SESSION['project']."\" title=\"".$ar2['category_comment']."\">".$hlavicka2."</a> ";
					$x=0; 
					while($num02 >= $x){
						if ($_SESSION['login'] == $admini[$x]){
							echo $admini[$x]."&nbsp;";
						}elseif ($_SESSION['login'] != $admini[$x] && $admini[$x] != ""){
							echo "[<a href=\"modul_forum.php?project=".$_SESSION['project']."&amp;action=posta&amp;user=".strtoupper ($_SESSION['login'])."&amp;pm_rec=".$admini[$x]."\">".$admini[$x]."</a>&nbsp;]";
						}
						$x++;
					}
					if ($ar2['category_news'] == 1){echo "<span class=\"red\"> | "._CAT_NEWS_S."</span>";} 
					if ($ar2['category_links'] == 1){echo "<span class=\"red\"> | "._CAT_LINKS_S."</span>";}
					if ($ar2['category_adds'] == 1){echo "<span class=\"red\"> | "._CAT_ADDS_S."</span>";}
					if ($ar2['category_shop'] == 1){echo "<span class=\"red\"> | "._CAT_SHOP_S."</span>";}
					if ($ar2['category_download'] == 1){echo "<span class=\"red\"> | "._CAT_DOWNLOAD_S."</span>";}
					if ($ar2['category_stream'] == 1){echo "<span class=\"red\"> | "._CAT_STREAM_S."</span>";} 
					if ($ar2['category_shows'] != 1){echo "<span class=\"blue\"> | "._CAT_DONTSHOW."</span>";}
				echo "	</td>\n";
				echo "	<td width=\"50\" align=\"center\" valign=\"middle\"><img src=\"images/sys_"; if ($ar2['category_active'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
				echo "	<td width=\"100\" align=\"center\" valign=\"middle\"><strong>"; if ($ar2['category_archive'] == 1){echo _CMN_YES;} else {echo _CMN_NO;} echo "</strong></td>\n";
				echo "	<td width=\"100\" align=\"center\" valign=\"middle\">".$podcat2[0]."</td>\n";
				echo "</tr>";
 	//***********************************************************************************
	// Druhe Podmenu
	//***********************************************************************************
				// Zajisteni rozbaleni, nebo zbaleni senamu po kliknuti
				if (($mode == "open" && $id2 == $ar2['category_id']) || ($mode == "close" && $id2 == $ar2['category_id'] && $id3 != "")) {
 					$res3 = mysql_query("SELECT 
					category_id, 
					category_name, 
					category_news, 
					category_links, 
					category_image, 
					category_comment, 
					category_admin, 
					category_adds, 
					category_shop, 
					category_download, 
					category_stream, 
					category_shows, 
					category_archive, 
					category_active 
					FROM $db_category 
					WHERE category_parent=".$id2." 
					ORDER BY category_active DESC, category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$num3 = mysql_num_rows($res3);
					$b = 1;
					while ($ar3 = mysql_fetch_array($res3)){
						$vys3 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".$ar3['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$podcat3 = mysql_fetch_array($vys3);
						$hlavicka2 = stripslashes($ar3['category_name']);
						// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
						if ($mode == "open" && $id3 == $ar3['category_id']) {$command = "close";}elseif ($mode == "close" && $close != 2 && $id3 == $ar3['category_id']){$command = "close";} else {$command = "open";}
						$res03 = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".$ar3['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar03 = mysql_fetch_array($res03);
						$admini = explode (" ", $ar03['category_admin']);
						$num03 = count($admini);
						if ($_SESSION['login'] == ""){$admini03 = "FALSE";} else {$admini03 = in_array($_SESSION['login'], $admini);}
						if ($b % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
						if ($ar3['category_active'] == 0){$cat_class = "cat_over";}
						echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
						echo "	<td width=\"80\" align=\"left\" valign=\"middle\">";
						  	if (CheckPriv("groups_cat_edit") == 1 || $admini03 == "TRUE"){echo "<a href=\"sys_category.php?action=topic_edit&amp;mode=".$command_now."&amp;id=".$ar3['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."&kategorie=".$kategorie."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> ";}
						  	if (CheckPriv("groups_cat_del") == 1){echo "<a href=\"sys_category.php?action=category_del&amp;mode=".$command_now."&amp;id=".$ar3['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a> ";}
						  	if (CheckPriv("groups_cat_add") == 1){echo "<a href=\"sys_category.php?action=topic_add&amp;mode=".$command_now."&amp;id=".$ar3['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."&kategorie=".$kategorie."\"><img src=\"images/sys_dtopic.gif\" border=\"0\" alt=\""._CMN_ADD."\"></a> ";}
						echo "	</td> \n";
						echo "	<td width=\"50\" align=\"center\" valign=\"middle\">".$ar3['category_id']."</td>\n";
						echo "	<td width=\"80\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar3['category_image']."\"></td>\n";
						echo "	<td width=\"440\" align=\"left\" valign=\"middle\">\n";
							if ($num2 > $a && $num3 > $b){
								echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat3[0],$command,"2",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
							}elseif ($num2 > $a && $num3 == $b){
								echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat3[0],$command,"2",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
							}elseif ($num2 == $a && $num3 == $b){
								echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat3[0],$command,"2",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
							}elseif ($num2 == $a && $num3 > $b){
								echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat3[0],$command,"2",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
							}
							echo "&nbsp;<a href=\"sys_category.php?action=showmain&amp;mode=".$command."&amp;id=".$ar['category_id']."&amp;id=".$ar['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&close=2&amp;project=".$_SESSION['project']."\" title=\"".$ar2['category_comment']."\">".$hlavicka2."</a> ";
							$x=0; 
							while($num03 >= $x){
								if ($_SESSION['login'] == $admini[$x]){
									echo $admini[$x]."&nbsp;";
								}elseif ($_SESSION['login'] != $admini[$x] && $admini[$x] != ""){
									echo "[<a href=\"modul_forum.php?project=".$_SESSION['project']."&amp;action=posta&amp;user=".strtoupper ($_SESSION['login'])."&amp;pm_rec=".$admini[$x]."\">".$admini[$x]."</a>&nbsp;]";
										}
								$x++;
							} 
							if ($ar3['category_news'] == 1){echo "<span class=\"red\"> | "._CAT_NEWS_S."</span>";} 
							if ($ar3['category_links'] == 1){echo "<span class=\"red\"> | "._CAT_LINKS_S."</span>";}
							if ($ar3['category_adds'] == 1){echo "<span class=\"red\"> | "._CAT_ADDS_S."</span>";}
							if ($ar3['category_shop'] == 1){echo "<span class=\"red\"> | "._CAT_SHOP_S."</span>";}
							if ($ar3['category_download'] == 1){echo "<span class=\"red\"> | "._CAT_DOWNLOAD_S."</span>";}
							if ($ar3['category_stream'] == 1){echo "<span class=\"red\"> | "._CAT_STREAM_S."</span>";} 
							if ($ar3['category_shows'] != 1){echo "<span class=\"blue\"> | "._CAT_DONTSHOW."</span>";}
						echo "	</td>\n";
						echo "	<td width=\"50\" align=\"center\" valign=\"middle\"><img src=\"images/sys_"; if ($ar3['category_active'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
						echo "	<td width=\"100\" align=\"center\" valign=\"middle\"><strong>"; if ($ar3['category_archive'] == 1){echo _CMN_YES;} else {echo _CMN_NO;} echo "</strong></td>\n";
						echo "	<td width=\"100\" align=\"center\" valign=\"middle\">".$podcat3[0]."</td>\n";
						echo "</tr>";
 	//***********************************************************************************
	// Treti Podmenu
	//***********************************************************************************
						// Zajisteni rozbaleni, nebo zbaleni senamu po kliknuti
						if (($mode == "open" && $id3 == $ar3['category_id']) || ($mode == "close" && $id3 == $ar3['category_id'] && $id4 != "")) {
 							$res4 = mysql_query("SELECT 
							category_id, 
							category_name, 
							category_news, 
							category_links, 
							category_image, 
							category_comment, 
							category_admin, 
							category_adds, 
							category_shop, 
							category_download, 
							category_stream, 
							category_shows, 
							category_archive, 
							category_active 
							FROM $db_category 
							WHERE category_parent=".$id3." 
							ORDER BY category_active DESC, category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$num4 = mysql_num_rows($res4);
							$c = 1;
							while ($ar4 = mysql_fetch_array($res4)){
								$vys4 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".$ar4['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								$podcat4 = mysql_fetch_array($vys4);
								$hlavicka2 = stripslashes($ar4['category_name']);
								// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
								if ($mode == "open" && $id4 == $ar4['category_id']) {$command = "close";}elseif ($mode == "close" && $close != 3 && $id4 == $ar4['category_id']){$command = "close";} else {$command = "open";}
								$res04 = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".$ar4['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								$ar04 = mysql_fetch_array($res04);
								$admini = explode (" ", $ar04['category_admin']);
								$num04 = count($admini);
								if ($_SESSION['login'] == ""){$admini04 = "FALSE";} else {$admini04 = in_array($_SESSION['login'], $admini);}
								if ($c % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
								if ($ar4['category_active'] == 0){$cat_class = "cat_over";}
	   							echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
								echo "	<td width=\"80\" align=\"left\" valign=\"middle\">";
									if (CheckPriv("groups_cat_edit") == 1 || $admini04 == "TRUE"){echo "<a href=\"sys_category.php?action=topic_edit&amp;mode=".$command_now."&amp;id=".$ar4['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['id']."&amp;project=".$_SESSION['project']."&kategorie=".$kategorie."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> ";}
									if (CheckPriv("groups_cat_del") == 1){echo "<a href=\"sys_category.php?action=category_del&amp;mode=".$command_now."&amp;id=".$ar4['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a> ";}
									if (CheckPriv("groups_cat_add") == 1){echo "<a href=\"sys_category.php?action=topic_add&amp;mode=".$command_now."&amp;id=".$ar4['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."&kategorie=".$kategorie."\"><img src=\"images/sys_dtopic.gif\" border=\"0\" alt=\""._CMN_ADD."\"></a> ";}
								echo "	</td> \n";
								echo "	<td width=\"50\" align=\"center\" valign=\"middle\">".$ar4['category_id']."</td>\n";
								echo "	<td width=\"80\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar4['category_image']."\"></td>\n";
								echo "	<td width=\"440\" align=\"left\" valign=\"middle\">";
									if ($num2 > $a && $num3 > $b && $num4 > $c){
										echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat4[0],$command,"3",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
									}elseif ($num2 > $a && $num3 > $b && $num4 == $c){
										echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat4[0],$command,"3",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
									}elseif ($num2 > $a && $num3 == $b && $num4 == $c){
										echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat4[0],$command,"3",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
									}elseif ($num2 == $a && $num3 == $b && $num4 > $c){
						   				echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat4[0],$command,"3",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
									}elseif ($num2 > $a && $num3 == $b && $num4 > $c){
										echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat4[0],$command,"3",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
									} else {
										echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat4[0],$command,"3",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
									}echo "&nbsp;<a href=\"sys_category.php?action=showmain&amp;mode=".$command."&amp;id=".$ar['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&close=3&amp;project=".$_SESSION['project']."\" title=\"".$ar2['category_comment']."\">".$hlavicka2."</a> ["; 
									$x=0; 
									while($num04>=$x){
										if ($_SESSION['login'] == $admini[$x]){
											echo $admini[$x]."&nbsp;";
										}elseif ($_SESSION['login'] != $admini[$x] && $admini[$x] != ""){
											echo "<a href=\"modul_forum.php?project=".$_SESSION['project']."&amp;action=posta&amp;user=".strtoupper ($_SESSION['login'])."&amp;pm_rec=".$admini[$x]."\">".$admini[$x]."</a>&nbsp;";
										}
										$x++;
									}
									echo "]"; 
									if ($ar4['category_news'] == 1){echo "<span class=\"red\"> | "._CAT_NEWS_S."</span>";} 
									if ($ar4['category_links'] == 1){echo "<span class=\"red\"> | "._CAT_LINKS_S."</span>";}
									if ($ar4['category_adds'] == 1){echo "<span class=\"red\"> | "._CAT_ADDS_S."</span>";}
									if ($ar4['category_shop'] == 1){echo "<span class=\"red\"> | "._CAT_SHOP_S."</span>";}
									if ($ar4['category_download'] == 1){echo "<span class=\"red\"> | "._CAT_DOWNLOAD_S."</span>";}
									if ($ar4['category_stream'] == 1){echo "<span class=\"red\"> | "._CAT_STREAM_S."</span>";} 
									if ($ar4['category_shows'] != 1){echo "<span class=\"blue\"> | "._CAT_DONTSHOW."</span>";}
								echo "	</td>\n";
								echo "	<td width=\"50\" align=\"center\" valign=\"middle\"><img src=\"images/sys_"; if ($ar4['category_active'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
								echo "	<td width=\"100\" align=\"center\" valign=\"middle\"><strong>"; if ($ar4['category_archive'] == 1){echo _CMN_YES;} else {echo _CMN_NO;}echo "</strong></td>\n";
								echo "	<td width=\"100\" align=\"center\" valign=\"middle\">".$podcat4[0]."</td>\n";
								echo "</tr>";
 	//***********************************************************************************
	// Ctvrte Podmenu
	//***********************************************************************************
								// Zajisteni rozbaleni, nebo zbaleni senamu po kliknuti
								if (($mode == "open" && $id4 == $ar4['category_id']) || ($mode == "close" && $id4 == $ar4['category_id'] && $id5 != "")) {
 									$res5 = mysql_query("SELECT 
									category_id, 
									category_name, 
									category_news, 
									category_links, 
									category_image, 
									category_comment, 
									category_admin, 
									category_adds, 
									category_shop, 
									category_download, 
									category_stream, 
									category_shows, 
									category_archive, 
									category_active 
									FROM $db_category 
									WHERE category_parent=".$id4." 
									ORDER BY category_active DESC, category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
									$num5 = mysql_num_rows($res5);
									$d = 1;
									while ($ar5 = mysql_fetch_array($res5)){
										$vys5 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".$ar5['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
										$podcat5 = mysql_fetch_array($vys5);
										$hlavicka2 = stripslashes($ar5['category_name']);
										// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
										if ($mode == "open" && $id5 == $ar5['category_id']) {$command = "close";} else {$command = "open";}
										$res05 = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".$ar5['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
										$ar05 = mysql_fetch_array($res05);
										$admini = explode (" ", $ar05[category_admin]);
										$num05 = count($admini);
										if ($_SESSION['login'] == ""){$admini05 = "FALSE";} else {$admini05 = in_array($_SESSION['login'], $admini);}
										if ($d % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
										if ($ar5['category_active'] == 0){$cat_class = "cat_over";}
										echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
										echo "	<td width=\"80\" align=\"left\" valign=\"middle\">";
											if (CheckPriv("groups_cat_edit") == 1 || $admini05 == "TRUE"){echo "<a href=\"sys_category.php?action=topic_edit&amp;mode=".$command_now."&amp;id=".$ar5['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."&kategorie=".$kategorie."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\"></a> ";}
											if (CheckPriv("groups_cat_del") == 1){echo "<a href=\"sys_category.php?action=category_del&amp;mode=".$command_now."&amp;id=".$ar5['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\"></a> ";}
											if (CheckPriv("groups_cat_add") == 1){echo "<a href=\"sys_category.php?action=topic_add&amp;mode=".$command_now."&amp;id=".$ar5['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."&kategorie=".$kategorie."\"><img src=\"images/sys_dtopic.gif\" border=\"0\"></a> ";}
										echo "	</td>\n";
										echo "	<td width=\"50\" align=\"center\" valign=\"middle\">".$ar5['category_id']."</td>\n";
										echo "	<td width=\"80\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar4['category_image']."\"></td>\n";
										echo "	<td width=\"440\" align=\"left\" valign=\"middle\">\n";
											if ($num2 > $a && $num3 > $b && $num4 > $c && $num5 > $d){
												echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
											}elseif ($num2 > $a && $num3 > $b && $num4 > $c && $num5 == $d){
												echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
											}elseif ($num2 > $a && $num3 == $b && $num4 == $c && $num5 == $d){
												echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
											}elseif ($num2 == $a && $num3 == $b && $num4 > $c && $num5 > $d){
												echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
											}elseif ($num2 > $a && $num3 == $b && $num4 > $c && $num5 > $d){
												echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
											}elseif ($num2 == $a && $num3 > $b && $num4 > $c && $num5 == $d){
												echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
											}elseif ($num2 == $a && $num3 == $b && $num4 == $c && $num5 > $d){
												echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
											}elseif ($num2 == $a && $num3 > $b && $num4 == $c && $num5 > $d){
												echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
											}elseif ($num2 > $a && $num3 > $b && $num4 == $c && $num5 == $d){
												echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
											}elseif ($num2 == $a && $num3 > $b && $num4 == $c && $num5 == $d){
												echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
											}elseif ($num2 == $a && $num3 > $b && $num4 > $c && $num5 > $d){
												echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
											}elseif ($num2 == $a && $num3 == $b && $num4 > $c && $num5 == $d){
												echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
											}elseif ($num2 > $a && $num3 > $b && $num4 == $c && $num5 > $d){
												echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
											}elseif ($num2 > $a && $num3 == $b && $num4 == $c && $num5 > $d){
												echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
											}elseif ($num2 > $a && $num3 == $b && $num4 > $c && $num5 == $d){
												echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
											}elseif ($num2 == $a && $num3 == $b && $num4 == $c && $num5 == $d){
												echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;"; Rozbal($podcat5[0],$command,"4",$ar['category_id'],$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
											}
											echo "&nbsp;<a href=\"sys_category.php?action=showmain&amp;mode=".$command."&amp;id=".$ar['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&close=4&amp;project=".$_SESSION['project']."\" title=\"".$ar2['category_comment']."\">".$hlavicka2."</a> ["; 
											$x=0; 
											while($num05 >= $x){
												if ($_SESSION['login'] == $admini[$x]){
													echo $admini[$x]."&nbsp;";
												}elseif ($_SESSION['login'] != $admini[$x] && $admini[$x] != ""){
													echo "<a href=\"modul_forum.php?project=".$_SESSION['project']."&amp;action=posta&amp;user=".strtoupper ($_SESSION['login'])."&amp;pm_rec=".$admini[$x]."\">".$admini[$x]."</a>&nbsp;";
												}
												$x++;
											}
											echo "]";
											if ($ar5['category_news'] == 1){echo "<span class=\"red\"> | "._CAT_NEWS_S."</span>";} 
											if ($ar5['category_links'] == 1){echo "<span class=\"red\"> | "._CAT_LINKS_S."</span>";}
											if ($ar5['category_adds'] == 1){echo "<span class=\"red\"> | "._CAT_ADDS_S."</span>";}
											if ($ar5['category_shop'] == 1){echo "<span class=\"red\"> | "._CAT_SHOP_S."</span>";}
											if ($ar5['category_download'] == 1){echo "<span class=\"red\"> | "._CAT_DOWNLOAD_S."</span>";}
											if ($ar5['category_stream'] == 1){echo "<span class=\"red\"> | "._CAT_STREAM_S."</span>";} 
											if ($ar5['category_shows'] != 1){echo "<span class=\"blue\"> | "._CAT_DONTSHOW."</span>";}
										echo "	</td>\n";
										echo "	<td width=\"50\" align=\"center\" valign=\"middle\"><img src=\"images/sys_"; if ($ar5['category_active'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
										echo "	<td width=\"100\" align=\"center\" valign=\"middle\"><strong>"; if ($ar5['category_archive'] == 1){echo _CMN_YES;} else {echo _CMN_NO;}echo "</strong></td>\n";
										echo "	<td width=\"100\" align=\"center\" valign=\"middle\">".$podcat5[0]."</td>\n";
										echo "</tr>";
 	//***********************************************************************************
	// Pate Podmenu
	//***********************************************************************************
										// Zajisteni rozbaleni, nebo zbaleni senamu po kliknuti
										if (($mode == "open" && $id5 == $ar5['category_id']) || ($mode == "close" && $id5 == $ar5['category_id'] && $id6 != "")) {
 											$res6 = mysql_query("SELECT 
											category_id, 
											category_name, 
											category_news, 
											category_links, 
											category_image, 
											category_comment, 
											category_admin, 
											category_adds, 
											category_shop, 
											category_download, 
											category_stream, 
											category_shows, 
											category_archive, 
											category_active 
											FROM $db_category 
											WHERE category_parent=".$id5." 
											ORDER BY category_active DESC, category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
											$num6 = mysql_num_rows($res6);
											$e = 1;
											while ($ar6 = mysql_fetch_array($res6)){
												$vys6 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".$ar6['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
												$podcat6 = mysql_fetch_array($vys6);
												$hlavicka2 = stripslashes($ar6['category_name']);
												// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
												if ($mode == "open" && $id6 == $ar6['category_id']) {$command = "close";} else {$command = "open";}
												$res06 = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".$ar6['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
												$ar06 = mysql_fetch_array($res06);
												$admini = explode (" ", $ar06['category_admin']);
												$num06 = count($admini);
												if ($_SESSION['login'] == ""){$admini06 = "FALSE";} else {$admini06 = in_array($_SESSION['login'], $admini);}
												if ($e % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
												if ($ar6['category_active'] == 0){$cat_class = "cat_over";}
												echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
												echo "	<td width=\"80\" align=\"left\" valign=\"middle\">";
													if (CheckPriv("groups_cat_edit") == 1 || $admini06 == "TRUE"){echo "<a href=\"sys_category.php?action=topic_edit&amp;mode=".$command_now."&amp;id=".$ar6['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."&kategorie=".$kategorie."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\"></a> ";}
													if (CheckPriv("groups_cat_del") == 1){echo "<a href=\"sys_category.php?action=category_del&amp;mode=".$command_now."&amp;id=".$ar6['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\"></a> ";}
													/*if (CheckPriv("groups_cat_add") == 1){echo "<a href=\"sys_category.php?action=topic_add&amp;mode=".$command_now."&amp;id=".$ar6['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."&kategorie=".$kategorie."\"><img src=\"images/sys_dtopic.gif\" border=\"0\"></a> ";}*/
												echo "	</td>\n";
												echo "	<td width=\"50\" align=\"center\" valign=\"middle\">".$ar6['category_id']."</td> \n";
												echo "	<td width=\"80\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar4['category_image']."\"></td>\n";
												echo "	<td width=\"440\" align=\"left\" valign=\"middle\">";
													if ($num2 > $a && $num3 > $b && $num4 > $c && $num5 > $d && $num6 > $e){
														echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
													}elseif ($num2 > $a && $num3 > $b && $num4 > $c && $num5 > $d && $num6 == $e){
														echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
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
														echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
													}elseif ($num2 == $a && $num3 > $b && $num4 == $c && $num5 == $d && $num6 == $e){
														echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
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
														echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
													}elseif ($num2 > $a && $num3 == $b && $num4 > $c && $num5 == $d && $num6 == $e){
														echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
													}elseif ($num2 == $a && $num3 == $b && $num4 == $c && $num5 == $d && $num6 > $e){
														echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
													}elseif ($num2 == $a && $num3 == $b && $num4 == $c && $num5 == $d && $num6 == $e){
														echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_konec.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
													}
													echo "&nbsp;<a href=\"sys_category.php?action=".$command."&amp;id=".$ar['category_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&close=5&amp;project=".$_SESSION['project']."\" title=\"".$ar2['category_comment']."\">".$hlavicka2."</a> [";
													$x=0; 
													while($num06>=$x){
														if ($_SESSION['login'] == $admini[$x]){
															echo $admini[$x]."&nbsp;";
														}elseif ($_SESSION['login'] != $admini[$x] && $admini[$x] != ""){
															echo "<a href=\"modul_forum.php?project=".$_SESSION['project']."&amp;action=posta&amp;user=".strtoupper ($_SESSION['login'])."&amp;pm_rec=".$admini[$x]."\">".$admini[$x]."</a>&nbsp;";
														}
														$x++;
													}
													echo "]";
													if ($ar6['category_news'] == 1){echo "<span class=\"red\"> | "._CAT_NEWS_S."</span>";} 
													if ($ar6['category_links'] == 1){echo "<span class=\"red\"> | "._CAT_LINKS_S."</span>";}
													if ($ar6['category_adds'] == 1){echo "<span class=\"red\"> | "._CAT_ADDS_S."</span>";}
													if ($ar6['category_shop'] == 1){echo "<span class=\"red\"> | "._CAT_SHOP_S."</span>";}
													if ($ar6['category_download'] == 1){echo "<span class=\"red\"> | "._CAT_DOWNLOAD_S."</span>";}
													if ($ar6['category_stream'] == 1){echo "<span class=\"red\"> | "._CAT_STREAM_S."</span>";} 
													if ($ar6['category_shows'] != 1){echo "<span class=\"blue\"> | "._CAT_DONTSHOW."</span>";}
												echo "</td>\n";
												echo "	<td width=\"50\" align=\"center\" valign=\"middle\"><img src=\"images/sys_"; if ($ar6['category_active'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
												echo "	<td width=\"100\" align=\"center\" valign=\"middle\"><strong>"; if ($ar6['category_archive'] == 1){echo _CMN_YES;} else {echo _CMN_NO;}echo "</strong></td>\n";
												echo "	<td width=\"100\" align=\"center\" valign=\"middle\">".$podcat6[0]."</td>\n";
												echo "</tr>";
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
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		PRIDAVANI A EDITACE KATEGORII																		
*																											
***********************************************************************************************************/
function AddCategory(){
	
	global $db_category,$db_admin;
	global $eden_cfg;
	global $ftp_path_category;
	global $url_category;
	
	/* Provereni opravneni */
	if ($_GET['action'] == "category_add" || $_GET['action'] == "topic_add"){
		if (CheckPriv("groups_cat_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain($_GET['action'],$_GET['id'],$_GET['id1'],$_GET['id2'],$_GET['id3'],$_GET['id4'],$_GET['id5'],$_GET['id6'],$_GET['close']);exit;}
	}elseif ($_GET['action'] == "category_edit" || $_GET['action'] == "topic_edit"){
		$res = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$admini = explode (" ", $ar['category_admin']);
		if ($_SESSION['login'] == ""){$admini = "FALSE";} else {$admini = in_array($_SESSION['login'], $admini);}
		if (CheckPriv("groups_cat_edit") <> 1 && $admini != "TRUE") { echo _NOTENOUGHPRIV;ShowMain($_GET['action'],$_GET['id'],$_GET['id1'],$_GET['id2'],$_GET['id3'],$_GET['id4'],$_GET['id5'],$_GET['id6'],$_GET['close']);exit;}
	} else {
		echo _NOTENOUGHPRIV; ShowMain($_GET['action'],$_GET['id'],$_GET['id1'],$_GET['id2'],$_GET['id3'],$_GET['id4'],$_GET['id5'],$_GET['id6'],$_GET['close']) ;exit;
	}
	
	if ($_GET['action'] == "category_edit" || $_GET['action'] == "topic_edit"){
		$res = mysql_query("SELECT * FROM $db_category WHERE category_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$ar['category_name'] = str_ireplace( "&quot;","\"",$ar['category_name']);
		$ar['category_name'] = str_ireplace( "&acute;","'",$ar['category_name']);
	}
	/* Spojeni s FTP serverem */
	$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP); 
	/* Prihlaseni pres uzivatelske jmeno a heslo na FTP server */
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); 
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	
	/* Zjisteni stavu spojeni */
	if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP; die;}
	
	echo "<SCRIPT type=\"text/javascript\">\n";
	echo "<!--\n";
	echo "var _img = new Array();";
	$d = ftp_nlist($conn_id, $ftp_path_category);
	$x = 0; 
	
	while($entry = $d[$x]) {
		$x++;
    	$entry = str_ireplace ($ftp_path_category,"",$entry);//Odstrani cestu k ftp adresari
		if ($entry != "." && $entry != "..") {
			echo "_img[".$x."] = new Image(); _img[".$x."].src=\"".$url_category.$entry."\";\n";
		}
	}
	echo "function doIt(_obj){\n";
	echo "  if (!_obj)return;\n";
	echo "  var _index = _obj.selectedIndex;\n";
	echo "  if (!_index)return;\n";
	echo "  var _item  = _obj[_index].id;\n";
	echo "  if (!_item)return;\n";
	echo "  if (_item<0 || _item >=_img.length)return;\n";
	echo "  document.images[\"obrazek\"].src=_img[_item].src;\n";
	echo "}\n";
	echo "//-->\n";
	echo "</script>";
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\">";
	echo "			<form action=\"sys_save.php?action="; 
						if ($_GET['action'] == "category_edit"){
							echo "category_edit";
						} elseif ($_GET['action'] == "category_add"){
							echo "category_add";
						} elseif ($_GET['action'] == "topic_edit"){
							echo "topic_edit";
						} else {
							echo "topic_add";
						} 
						echo "&amp;id=".$_GET['id']."&amp;mode=".$_GET['mode']."\" method=\"post\" name=\"forma\"><strong>"._CAT_NAME."</strong></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"category_name\" value=\"".$ar['category_name']."\" size=\"60\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CAT_ACTIVE."</strong></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"checkbox\" name=\"active\" value=\"1\" "; if ($ar['category_active'] == 1){echo "checked";} echo "></td>\n";
	echo "	</tr>\n";
	if ($_GET['action'] == "category_edit"){
		echo "<tr>\n";
		echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CAT_PTOPIC."</strong></td>\n";
		echo "	<td align=\"left\" valign=\"top\"><select name=\"parent\">";
			$res3 = mysql_query("SELECT category_id, category_parent FROM $db_category WHERE category_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar3 = mysql_fetch_array($res3);
			$res2 = mysql_query("SELECT category_id, category_name FROM $db_category WHERE category_parent=0 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			if ($ar3['category_id'] == $ar2['category_id'] || $ar3['category_parent'] == $ar2['category_id']) { 
				echo "<option value=\"0\" selected>"._CAT_PARENT."</option>";
			} else {
				echo "<option value=\"0\" >"._CAT_MAIN."</option>";
			}
			while ($ar2 = mysql_fetch_array($res2)){
					echo "<option value=\"".$ar2['category_id']."\">".$ar2['category_name']."</option>";
			}
		echo "	</select>";
		echo "	</td>\n";
		echo "</tr>\n";
	}
	echo "<tr>\n";
	echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CAT_ARTICLES."</strong></td>\n";
	echo "	<td align=\"left\" valign=\"top\"><input type=\"radio\" name=\"category\" value=\"articles\" "; if ($ar['category_articles'] == 1 ){echo "checked";}elseif ($_GET['action'] == "category_add"){echo "checked";} echo "></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CAT_NEWS."</strong></td>\n";
	echo "	<td align=\"left\" valign=\"top\"><input type=\"radio\" name=\"category\" value=\"news\" "; if ($ar['category_news'] == 1){echo "checked";} echo "></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CAT_LINKS."</strong></td>\n";
	echo "	<td align=\"left\" valign=\"top\"><input type=\"radio\" name=\"category\" value=\"links\" "; if ($ar['category_links'] == 1){echo "checked";} echo "></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CAT_ADDS."</strong></td>\n";
	echo "	<td align=\"left\" valign=\"top\"><input type=\"radio\" name=\"category\" value=\"reklama\" "; if ($ar['category_adds'] == 1){echo "checked";} echo "></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CAT_SHOP."</strong></td>\n";
	echo "	<td align=\"left\" valign=\"top\"><input type=\"radio\" name=\"category\" value=\"shop\" "; if ($ar['category_shop'] == 1){echo "checked";} echo "></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CAT_DOWNLOAD."</strong></td>\n";
	echo "	<td align=\"left\" valign=\"top\"><input type=\"radio\" name=\"category\" value=\"download\" "; if ($ar['category_download'] == 1){echo "checked";} echo "></td>\n";
	echo "</tr>\n";
	echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CAT_STREAM."</strong></td>\n";
	echo "	<td align=\"left\" valign=\"top\"><input type=\"radio\" name=\"category\" value=\"stream\" "; if ($ar['category_stream'] == 1){echo "checked";} echo "></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CAT_SHOW."</strong></td>\n";
	echo "	<td align=\"left\" valign=\"top\"><input type=\"checkbox\" name=\"shows\" value=\"1\" "; if ($ar['category_shows'] == 1 || !isset($ar['category_shows'])){echo "checked";} echo "></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CAT_ARCHIV."</strong></td>\n";
	echo "	<td align=\"left\" valign=\"top\"><input type=\"checkbox\" name=\"archiv\" value=\"1\" "; if ($ar['category_archive'] == 1){echo "checked";} echo "></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CAT_DESC."</strong></td>\n";
	echo "	<td align=\"left\" valign=\"top\"><textarea name=\"category_desc\" rows=\"6\" cols=\"40\">".$ar['category_comment']."</textarea></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CAT_IMAGE."</strong></td>\n";
	echo "	<td align=\"left\" valign=\"top\"><select name=\"picture\" size=\"5\" onclick=\"doIt(this)\">";
		$d = ftp_nlist($conn_id, $ftp_path_category);
		$x = 0;
		echo "<option value=\"0\">"._CAT_CHOOSE_IMAGE."</option>\n";
		while($entry = $d[$x]) {
			$x++;
			$entry = str_ireplace ($ftp_path_category,"",$entry);//Odstrani cestu k ftp adresari
			if ($entry != "." && $entry != "..") {
				echo "<option id=\"".$x."\" value=\"".$entry."\""; if ($entry == $ar['category_image']){ echo "selected=\"selected\"";} echo ">".$entry."</option>\n";
			}
		}
		ftp_close($conn_id);
	echo "		</select>&nbsp;&nbsp;";
	echo "		<img name=\"obrazek\" src=\""; if ($ar['category_image'] != ""){echo $url_category.$ar['category_image'];} else {echo $url_category."AllTopics.gif";} echo "\" border=\"0\">";
	echo "	</td>";
	echo "</tr>";
	if ($_GET['action'] == "topic_edit" || $_GET['action'] == "topic_add"){
		if (CheckPriv("groups_cat_edit") == 1){
			// Pokud nekdo nebude mit dostatecne opravneni, nemuze ani prehazovat kategorie
			echo "<tr>";
			echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CAT_PTOPIC."</strong></td>";
			echo "	<td align=\"left\" valign=\"top\"><select name=\"parent\">";
			echo "		<option value=\"0\" >"._CAT_MAIN."</option>";
				$res3 = mysql_query("SELECT category_id, category_parent FROM $db_category WHERE category_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar3 = mysql_fetch_array($res3);
				
				/* Nastaveni spravneho zobrazeni pri vyberu Editace Kategorie nebo Pridani Podkategorie */
				if ($_GET['action'] == "topic_edit"){$cat_parent = $ar3['category_parent'];}
				if ($_GET['action'] == "topic_add"){$cat_parent = $_GET['id'];}
				
				$res2 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=0 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				while ($ar2 = mysql_fetch_array($res2)){
					$cat = $ar2['category_name'];
					echo "<option value=\"".$ar2['category_id']."\" ";
					if ($_GET['id1'] == $ar2['category_id']) { echo "selected=\"selected\"";}
					echo ">".$cat."</option>";
					$ress2 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$ar2['category_id']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($arr2 = mysql_fetch_array($ress2)){
						if ($arr2['category_parent'] == $ar2['category_id']){$cat2 = "&nbsp;&nbsp;&nbsp;".$arr2['category_name']; }
						echo "<option value=\"".$arr2['category_id']."\" ";
						if ($_GET['id2'] == $cat_parent) { echo "selected=\"selected\"";}
						echo ">".$cat2."</option>";
						$ress3 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr2['category_id']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($arr3 = mysql_fetch_array($ress3)){
							if ($arr3['category_parent'] == $arr2['category_id']){$cat3 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr3['category_name']; }
							echo "<option value=\"".$arr3['category_id']."\" ";
							if ($_GET['id3'] == $cat_parent) { echo "selected=\"selected\"";}
							echo ">".$cat3."</option>";
							$ress4 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr3['category_id']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							while ($arr4 = mysql_fetch_array($ress4)){
								if ($arr4['category_parent'] == $arr3['category_id']){$cat4 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr4['category_name']; }
								echo "<option value=\"".$arr4['category_id']."\" ";
								if ($_GET['id4'] == $cat_parent) { echo "selected=\"selected\"";}
								echo ">".$cat4."</option>";
								$ress5 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr4['category_id']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								while ($arr5 = mysql_fetch_array($ress5)){
									if ($arr5['category_parent'] == $arr4['category_id']){$cat5 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr5['category_name']; }
									echo "<option value=\"".$arr5['category_id']."\" ";
									if ($_GET['id5'] == $cat_parent) { echo "selected=\"selected\"";}
									echo ">".$cat5."</option>";
									$ress6 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr5['category_id']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
									while ($arr6 = mysql_fetch_array($ress6)){
										if ($arr6['category_parent'] == $arr5['category_id']){$cat6 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr6['category_name']; }
										echo "<option value=\"".$arr6['category_id']."\" ";
										if ($_GET['id6'] == $cat_parent) { echo "selected=\"selected\"";}
										echo ">".$cat6."</option>";
									}
								}
							}
						}
					}
				}//konec if	
			echo "</select></td>";
			echo "</tr>";
 		}
	}
	echo "<tr>\n";
	echo "	<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CMN_HITS."</strong></td>\n";
	echo "	<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"hits\" size=\"2\" maxlength=\"4\" value=\"".$ar['category_hits']."\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"left\" valign=\"top\" colspan=\"2\">\n";
	echo "		<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "		<input type=\"hidden\" name=\"id1\" value=\"".$_GET['id1']."\">\n";
	echo "		<input type=\"hidden\" name=\"id2\" value=\"".$_GET['id2']."\">\n";
	echo "		<input type=\"hidden\" name=\"id3\" value=\"".$_GET['id3']."\">\n";
	echo "		<input type=\"hidden\" name=\"id4\" value=\"".$_GET['id4']."\">\n";
	echo "		<input type=\"hidden\" name=\"id5\" value=\"".$_GET['id5']."\">\n";
	echo "		<input type=\"hidden\" name=\"id6\" value=\"".$_GET['id6']."\">\n";
	echo "		<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "		<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "		<input type=\"submit\" value=\"". _CMN_SUBMIT."\" class=\"eden_button\">\n";
	echo "		</form>\n";
	echo "	</td>\n";
	echo "</tr><!-- Vyber admina -->";
	if ($_GET['action'] == "category_edit" || $_GET['action'] == "topic_edit" ){				
		echo "<tr>\n";
		echo "	<td width=\"200\" align=\"center\">\n";
		echo "	<form action=\"sys_save.php?action=".$_GET['action']."\" method=\"post\">\n";
		echo "	<select name=\"admins\" size=\"8\">";
			$res4 = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".(float)$_GET['id']." ORDER BY category_admin ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar4 = mysql_fetch_array($res4);
			$admini = explode (" ", $ar4['category_admin']);// Rozdeli se pole $ar4[admin] na jednotlive uzivatele
			$i = count($admini); // Spocita se pocet adminu
			$x = 0;
			while ($i>$x){
				if ($admini[$x] != ""){ // Kdyz neni polozka retezce prazdna tak se zobrazi jako polozka select
					echo "<option value=\"".$admini[$x]."\" "; 
					echo ">".$admini[$x]."</option>";
				}
				$x++;
			}
		echo "	</select>";
		echo "</td>";
		echo "<td width=\"650\" align=\"left\">";
		echo "<select name=\"users\" size=\"8\">";
			$res5 = mysql_query("SELECT admin_uname FROM $db_admin WHERE admin_status='admin' ORDER BY admin_uname ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$num5 = mysql_num_rows($res5);
			// Ulozeni uzivatelu do pole $us
			while ($ar5 = mysql_fetch_array($res5)){
				$us[] = $ar5['admin_uname'];
			}
			
			$result = array_diff ($us, $admini); // Do pole $result se ulozi vsichni uzivatele, kteri zbyli
			$x = 0;
			while ($num5 > $x){
				if ($result[$x] != ""){ // Pokud neni pole $result[$x] prazdne tak se zobrazi
					echo "<option value=\"".$result[$x]."\" ";
					echo ">".$result[$x]."</option>";
				}
				$x++;
			}
		echo "		</select>\n";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td width=\"200\" align=\"center\"><input type=\"submit\" name=\"add_admin\" value=\"»»»»»»\" class=\"eden_button\"></td>\n";
		echo "	<td width=\"650\" align=\"left\"><input type=\"submit\" name=\"add_admin\" value=\"««««««\" class=\"eden_button\"></td>\n";
		echo "		<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "		<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
		echo "		<input type=\"hidden\" name=\"id1\" value=\"".$_GET['id1']."\">\n";
		echo "		<input type=\"hidden\" name=\"id2\" value=\"".$_GET['id2']."\">\n";
		echo "		<input type=\"hidden\" name=\"id3\" value=\"".$_GET['id3']."\">\n";
		echo "		<input type=\"hidden\" name=\"id4\" value=\"".$_GET['id4']."\">\n";
		echo "		<input type=\"hidden\" name=\"id5\" value=\"".$_GET['id5']."\">\n";
		echo "		<input type=\"hidden\" name=\"id6\" value=\"".$_GET['id6']."\">\n";
		echo "		<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "	</form></td>\n";
		echo "</tr>";
	}
	echo "</table>";
	// <!-- Konec vyberu admina -->
}
/***********************************************************************************************************
*																											
*		MAZANI KATEGORII																					
*																											
***********************************************************************************************************/
function DeleteCategory(){
	
	global $db_category;
	global $url_category;
	
	/* CHECK PRIVILEGIES */
	if (CheckPriv("groups_cat_del") <> 1) { 
		echo _NOTENOUGHPRIV;
		ShowMain("open",$_POST['id'],$_POST['id1'],$_POST['id2'],$_POST['id3'],$_POST['id4'],$_POST['id5'],$_POST['id6'],$_POST['close']);
		exit;
	}
	if ($_POST['confirm'] == "true") {
		mysql_query("DELETE FROM $db_category WHERE category_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); 
		ShowMain("open",$_POST['id'],$_POST['id1'],$_POST['id2'],$_POST['id3'],$_POST['id4'],$_POST['id5'],$_POST['id6'],$_POST['close']);
		exit;
	}
	if ($_POST['confirm'] == "false") {
		ShowMain("open",$_POST['id'],$_POST['id1'],$_POST['id2'],$_POST['id3'],$_POST['id4'],$_POST['id5'],$_POST['id6'],$_POST['close']);
		exit;
	}
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td><span class=\"nadpis-boxy\">"._CMN_IMAGE."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._CAT_NAME."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._CAT_NUMBER."</span></td>\n";
	echo "	</tr>";
 	$res = mysql_query("SELECT category_name, category_image FROM $db_category WHERE category_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
 	$ar = mysql_fetch_array($res);
 	$res2 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
 	$num = mysql_fetch_array($res2);
	echo "	<tr>";
	echo "		<td valign=\"top\" width=\"100\"><img src=\"".$url_category.$ar['category_image']."\"></td>";
	echo "		<td valign=\"top\">".$ar['category_name']."</td>";
	echo "		<td valign=\"top\">".$num[0]."</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._CAT_CHECK_DELETE."</span></strong></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" width=\"50\">\n";
	echo "		<form action=\"sys_category.php?action=category_del\" method=\"post\">\n";
	echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
	echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "			<input type=\"hidden\" name=\"id1\" value=\"".$_GET['id1']."\">\n";
	echo "			<input type=\"hidden\" name=\"id2\" value=\"".$_GET['id2']."\">\n";
	echo "			<input type=\"hidden\" name=\"id3\" value=\"".$_GET['id3']."\">\n";
	echo "			<input type=\"hidden\" name=\"id4\" value=\"".$_GET['id4']."\">\n";
	echo "			<input type=\"hidden\" name=\"id5\" value=\"".$_GET['id5']."\">\n";
	echo "			<input type=\"hidden\" name=\"id6\" value=\"".$_GET['id6']."\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "		<td valign=\"top\" width=\"800\">\n";
	echo "			<form action=\"sys_category.php?action=category_del\" method=\"post\">\n";
	echo "			<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
	echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "			<input type=\"hidden\" name=\"id1\" value=\"".$_GET['id1']."\">\n";
	echo "			<input type=\"hidden\" name=\"id2\" value=\"".$_GET['id2']."\">\n";
	echo "			<input type=\"hidden\" name=\"id3\" value=\"".$_GET['id3']."\">\n";
	echo "			<input type=\"hidden\" name=\"id4\" value=\"".$_GET['id4']."\">\n";
	echo "			<input type=\"hidden\" name=\"id5\" value=\"".$_GET['id5']."\">\n";
	echo "			<input type=\"hidden\" name=\"id6\" value=\"".$_GET['id6']."\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"false\"></form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		SPRAVA KATEGORII																					
*																											
***********************************************************************************************************/
function ManageTopic(){
	
	global $db_category;
	
	$res_cat = mysql_query("SELECT category_id, category_parent FROM $db_category") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num_cat = mysql_num_rows($res_cat);
	
	if ($_POST['confirm'] == "true"){
		$pocet = count($_POST['result3']);
		$i = 0;
		while ($pocet > $i){
			mysql_query("DELETE FROM $db_category WHERE category_id=".(integer)$_POST['result3'][$i]."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); 
			$i++;
		}
		ShowMain("open",$_POST['id'],$_POST['id1'],$_POST['id2'],$_POST['id3'],$_POST['id4'],$_POST['id5'],$_POST['id6'],$_POST['close']);
		exit;
	}
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\">\n";
	echo "			<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "				<tr>\n";
	echo "					<td width=\"850\" align=\"left\" colspan=\"2\"><strong>"._CAT_NON_PCAT."</strong></td>\n";
	echo "				</tr>\n";
	echo "				<tr class=\"popisky\">\n";
	echo "					<td width=\"50\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "					<td width=\"800\" align=\"left\"><span class=\"nadpis-boxy\">"._CAT_NAME."</span></td>\n";
	echo "				</tr>\n";
	echo "				<form action=\"sys_category.php\" method=\"post\" name=\"formb\">";
		while($ar_cat = mysql_fetch_array($res_cat)){
			$parent[] = $ar_cat['category_parent'];
			$id[] = $ar_cat['category_id'];
		}
		$result = array_diff ($parent, $id); //Porovna PARENT s ID
		$result2 = array_unique ($result); // Vyhodi duplicitni hodnoty
		sort ($result2);
		$i = 0;
		while($num_cat > $i){
			if ($result2[$i] != "" && $result2[$i] != "0"){
				$res_podcat = mysql_query("SELECT category_id, category_name FROM $db_category WHERE category_parent=".$result2[$i]) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); 
				while ($ar_podcat = mysql_fetch_array($res_podcat)){
					echo "<tr>";
					echo "	<td width=\"50\" align=\"center\">".$ar_podcat['category_id']."</td>";
					echo "	<td width=\"800\" align=\"left\">".$ar_podcat['category_name']."</td>";
					echo "	<input type=\"hidden\" name=\"result3[]\" value=\"".$ar_podcat['category_id']."\">";
					echo "</tr>";
				}
			}
			$i++;
		}
	echo "				<tr>\n";
	echo "					<td width=\"850\" align=\"left\" colspan=\"2\">\n";
	echo "						<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"topic_manage\">\n";
	echo "						<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "						<input type=\"submit\" value=\""._CAT_DEL_PTOPIC."\" class=\"eden_button_no\">\n";
	echo "						</form>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
include ("inc.header.php");
	if ($_GET['action'] == "" || $_GET['action'] == "showmain") { ShowMain($_GET['mode'],$_GET['id'],$_GET['id1'],$_GET['id2'],$_GET['id3'],$_GET['id4'],$_GET['id5'],$_GET['id6'],$_GET['close']); }
	if ($_GET['action'] == "topic_add") { AddCategory(); }
	if ($_GET['action'] == "topic_edit") { AddCategory(); }
	if ($_GET['action'] == "topic_manage") { ManageTopic(); }
	if ($_GET['action'] == "category_add") { AddCategory(); }
	if ($_GET['action'] == "category_edit") { AddCategory(); }
	if ($_GET['action'] == "category_del") { DeleteCategory(); }
	if ($_GET['action'] == "category_img_upload") { EdenSysImageManager(); }
	if ($_GET['action'] == "category_img_del") { EdenSysImageManager(); }
include ("inc.footer.php");