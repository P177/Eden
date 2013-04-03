<?php
/***********************************************************************************************************
*
*		MENU
*
***********************************************************************************************************/
function Menu(){
	
	global $db_links;
	
	switch ($_GET['action']){
		case "links_add":
			$title = _LINKS." - "._LINKS_ADD;
	   		break;
		case "links_edit";
			$title = _LINKS." - "._LINKS_EDIT;
	   		break;
		case "links_del";
			$title = _LINKS." - "._LINKS_DEL;
	   		break;
		case "links_show_nonpublished";
			$title = _LINKS." - "._LINKS_PUBLISH;
	   		break;
		default:
		$title = _LINKS;
	}
	
	$menu = "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	$menu .= "<tr>\n";
	$menu .= "	<td align=\"left\" class=\"nadpis\">".$title."</td>\n";
	$menu .= "</tr>\n";
	$menu .= "<tr>\n";
	$menu .= "	<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">\n";
	$menu .= "		<a href=\"modul_links.php?project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	$menu .= "		<a href=\"modul_links.php?action=links_add&amp;project=".$_SESSION['project']."\">"._LINKS_ADD."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	$menu .= "		<a href=\"modul_links.php?action=links_show_nonpublished&amp;project=".$_SESSION['project']."\">"._LINKS_SHOW_NEPUBLIC."</a></td>\n";
	$menu .= "</tr>";
	if ($_GET['msg']){
		$menu .= "<tr><td class=\"msg\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	$menu .= "</table>";
	
	return $menu;
}
/***********************************************************************************************************
*
*		VLOZENI ROZBALOVACIHO TLACITKA 
*
***********************************************************************************************************/
function Expand($podcat,$command,$close,$ar,$ar2,$ar3,$ar4,$ar5,$ar6,$obr){
	
	if ($podcat > 0 && $command == "open"){
		$expand = "<a href=\"modul_links.php?action=".$command."&amp;id1=".$ar."&amp;id2=".$ar2."&amp;id3=".$ar3."&amp;id4=".$ar4."&amp;id5=".$ar5."&amp;id6=".$ar6."&close=".$close."&amp;project=".$_SESSION['project']."\" title=\""._LINKS_SUBCAT_OPEN."\"><img src=\"images/sys_strom_".$obr."_plus.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\"></a>";
	}elseif ($podcat > 0 && $command == "close"){
		$expand = "<a href=\"modul_links.php?action=".$command."&amp;id1=".$ar."&amp;id2=".$ar2."&amp;id3=".$ar3."&amp;id4=".$ar4."&amp;id5=".$ar5."&amp;id6=".$ar6."&close=".$close."&amp;project=".$_SESSION['project']."\" title=\""._LINKS_SUBCAT_CLOSE."\"><img src=\"images/sys_strom_".$obr."_minus.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\"></a>";
	} else {
		$expand = "<img src=\"images/sys_strom_".$obr.".gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">";
	}
	
	return $expand;
}

//********************************************************************************************************
//                                                                                                        
//             ZOBRAZENI SEZNAMU KATEGORII                                                                
//                                                                                                        
//********************************************************************************************************
function ShowMain(){
	
	global $db_links,$db_category;
	global $url_category;
	
	if ($_GET['action'] == "links_edit"){$_GET['action'] = "open";}
	if ($_GET['action'] == "links_add"){$_GET['action'] = "open";}
	if ($_GET['action'] == "links_del"){$_GET['action'] = "open";}
	if ($_GET['action'] == "links_del_selected"){$_GET['action'] = "open";}
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	if (CheckPriv("groups_links_del") == 1){
		if ($_POST['confirm'] == "TRUE"){
			$n = count($_POST['del_link']);
			$i = 0;
			while($i < $n){
				$del_link = $_POST['del_link'][$i];
				mysql_query("DELETE FROM $db_links WHERE links_id=".(integer)$del_link."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); 
				$i++;
			}
			echo "<br>";
			echo "UPDATED";
		}
	}
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "<tr class=\"popisky\">\n";
	echo "	<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "	<td width=\"50\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "	<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_IMAGE."</span></td>\n";
	echo "	<td width=\"540\" align=\"left\"><span class=\"nadpis-boxy\">"._LINKS_NAME."</span></td>\n";
	echo "	<td width=\"100\" align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_NUMBER_SUBTOPIC."</span></td>\n";
	echo "</tr>";
	//***********************************************************************************
	// Hlavni Menu
	//***********************************************************************************
	$res = mysql_query("SELECT category_id, category_name, category_image, category_comment, category_admin FROM $db_category WHERE category_parent=0 AND category_links=1 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i = 1;
	while ($ar = mysql_fetch_array($res)){
		$res1 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".(integer)$ar['category_id']." AND category_links=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num = mysql_fetch_array($res1);
		$reslink = mysql_query("SELECT links_id, links_name, links_picture, links_picture2, links_link, links_description, links_clicks, links_out, links_list, links_main, links_gfx, links_publish 
		FROM $db_links WHERE links_category_id=".(integer)$ar['category_id']." ORDER BY links_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$numlink = mysql_num_rows($reslink);
		$hlavicka = stripslashes($ar['category_name']);
		// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
		if ($_GET['action'] == "open" && $_GET['id1'] == $ar['category_id']) {$command = "close";} elseif ($_GET['action'] == "close" && $_GET['close'] != 0 && $_GET['id1'] == $ar['category_id']){$command = "close";} else {$command = "open";}
		if ($_GET['action_link'] == "" || !isset($_GET['action_link'])){$_GET['action_link'] = "close";}
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"100\" align=\"left\" valign=\"middle\">";
				if ($numlink >= 1){echo "<a href=\"modul_links.php?action_link="; if ($_GET['action_link'] == "open" && $ar['category_id'] == $_GET['id_link']){echo "close";} else {echo "open";} echo "&amp;action=open&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$ar['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_open.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_OPEN."\"></a>"; } else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">"; }
				if (CheckPriv("groups_links_add") == 1){echo " <a href=\"modul_links.php?action=links_add&action_link=".$_GET['action_link']."&amp;id=".$arlink2['id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_dtopic.gif\" border=\"0\" alt=\""._CMN_ADD."\"></a>";} echo "</td>";
		echo "	<td width=\"50\" align=\"center\" valign=\"middle\"><strong>".$ar['category_id']."</strong></td> \n";
		echo "	<td width=\"60\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar['category_image']."\"></td>\n";
		echo "	<td width=\"540\" align=\"left\" valign=\"middle\">".Expand($num[0],$command,"0",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"zacatek");
		echo "		&nbsp;<strong><a href=\"?action=".$command."&amp;id1=".$ar['category_id']."&amp;project=".$_SESSION['project']."\" title=\"".$ar['category_comment']."\">".$hlavicka."</a></strong>(".$numlink.")"; if (!isset($ar['category_admin'])){echo "[".$ar['category_admin']."]";} 
		echo "	</td>\n";
		echo "	<td width=\"100\" align=\"center\" valign=\"middle\"><strong>".$num[0]."</strong></td>\n";
		echo "</tr>";
		if ($_GET['action_link'] == "open" && $ar['category_id'] == $_GET['id_link'] && $numlink > 0){
			echo "<tr>\n";
			echo "	<td colspan=\"5\" width=\"850\">\n";
			echo "		<form enctype=\"multipart/form-data\" action=\"modul_links.php?action=links_del_selected&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;id_link=".$_GET['id_link']."&amp;action_link=".$_GET['action_link']."&amp;project=".$_SESSION['project']."\" method=\"post\">";
			echo "		<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
			echo "			<tr bgcolor=\"#DCE3F1\">\n";
			echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
			echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
			echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"."D"."</span></td>\n";
			echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_NAME."</span></td>\n";
			echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_LINK."</span></td>\n";
			echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IN."</span></td>\n";
			echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_OUT."</span></td>\n";
			echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IMG1."</span></td>\n";
			echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IMG2."</span></td>\n";
			echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_GFX."</span></td>\n";
			echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_LIST."</span></td>\n";
			echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_PUBLISH."</span></td>\n";
			echo "			</tr>";
			$i2=1;
			while($arlink = mysql_fetch_array($reslink)){
				if ($i2 % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
	   			echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
				echo "	<td width=\"80\"><a href=\"modul_links.php?action=links_edit&action_link=".$_GET['action_link']."&amp;id=".$arlink['links_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> <a href=\"modul_links.php?action=links_del&action_link=".$_GET['action_link']."&amp;id=".$arlink['links_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a></td> \n";
				echo "	<td align=\"right\">".$arlink['links_id']."</td>\n";
				echo "	<td align=\"right\"><input type=\"checkbox\" name=\"del_link[]\" value=\"".$arlink['links_id']."\"></td>\n";
				echo "	<td align=\"left\"><a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('".$arlink['links_description']."', this, event, '450px')\">".$arlink['links_name']."</a></td>\n";
				echo "	<td align=\"left\" valign=\"top\"><a href=\"http://".$arlink['links_link']."\" TARGET=\"_blank\">".$arlink['links_link']."</a></td>\n";
				echo "	<td align=\"right\" valign=\"top\">".$arlink['links_clicks']."</td>\n";
				echo "	<td align=\"right\" valign=\"top\">".$arlink['links_out']."</td>\n";
				echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink['links_picture'] != ""){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
				echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink['links_picture2'] != ""){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
				echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink['links_gfx'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
				echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink['links_list'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
				echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink['links_publish'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
				echo "</tr>";
				$i2++;
			}
			if (CheckPriv("groups_links_del") == 1){
				echo "<td colspan=\"10\">\n";
				echo "<input type=\"hidden\" name=\"confirm\" value=\"TRUE\">\n";
				echo "<input type=\"submit\" value=\""._CMN_DEL."\" class=\"eden_button_no\">\n";
				echo "</form>\n";
				echo "	</td>\n";
				echo "</tr>";
			}
			echo "		</table>\n";
			echo "	</td>\n";
			echo "</tr>";
 		}
 	//***********************************************************************************
	// Prvni Podmenu
	//***********************************************************************************
		// Zajisteni rozbaleni, nebo zbaleni senamu po kliknuti
		if (($_GET['action'] == "open" && $_GET['id1'] == $ar['category_id']) || ($_GET['action'] == "close" && $_GET['id1'] == $ar['category_id'] && $_GET['id2'] != "")) {
 			$res2 = mysql_query("SELECT category_id, category_name, category_image, category_comment, category_admin FROM $db_category WHERE category_parent=".(integer)$_GET['id1']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$num2 = mysql_num_rows($res2);
			$a = 1;
			while ($ar2 = mysql_fetch_array($res2)){
				$vys2 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".(integer)$ar2['category_id']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$podcat2 = mysql_fetch_array($vys2);
				// Nacteme programy v danych kategoriich
				$reslink2 = mysql_query("SELECT links_id, links_name, links_picture, links_picture2, links_link, links_description, links_clicks, links_out, links_list, links_main, links_gfx, links_publish 
				FROM $db_links WHERE links_category_id=".(float)$ar2['category_id']." ORDER BY links_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$numlink2 = mysql_num_rows($reslink2);
				$hlavicka2 = stripslashes($ar2['category_name']);
				// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
				if ($_GET['action'] == "open" && $_GET['id2'] == $ar2['category_id']) {$command = "close";} elseif ($_GET['action'] == "close" && $_GET['close'] != 1 && $_GET['id2'] == $ar2['category_id']){$command = "close";} else {$command = "open";}
				if ($a % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
	   			echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
				echo "	<td width=\"100\" align=\"left\" valign=\"middle\">";
				if ($numlink2 >= 1){ echo "<a href=\"modul_links.php?action_link="; if ($_GET['action_link'] == "open" && $ar2['category_id'] == $_GET['id_link']){echo "close";} else {echo "open";} echo "&amp;action=open&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$ar2['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_open.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_OPEN."\"></a>"; } else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">"; }
				if (CheckPriv("groups_links_add") == 1){echo "<a href=\"modul_links.php?action=links_add&action_link=".$_GET['action_link']."&amp;id=".$arlink2['id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_dtopic.gif\" border=\"0\" alt=\""._CMN_ADD."\"></a>";}
				echo "</td>\n";
				echo "<td width=\"50\" align=\"center\" valign=\"middle\">".$ar2['category_id']."</td> \n";
				echo "<td width=\"60\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar2['category_image']."\"></td>\n";
				echo "<td width=\"540\" align=\"left\" valign=\"middle\">";
				if ($num2 > $a){
					echo Expand($podcat2[0],$command,"1",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
				}elseif ($num2 == $a){
					echo Expand($podcat2[0],$command,"1",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
				}
				echo "&nbsp;<a href=\"modul_links.php?action=".$command."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\" title=\"".$ar2['komentar']."\">".$hlavicka2."</a> (".$numlink2.") ["; $x=0; while($num02>=$x){if ($_SESSION['login'] == $admini['$x']){echo $admini['$x']."&nbsp;";}elseif ($_SESSION['login'] != $admini['$x'] && $admini['$x'] != ""){ echo "<a href=\"modul_forum.php?project=".$_SESSION['project']."&amp;action=posta&amp;user=".strtoupper($_SESSION['login'])."&amp;pm_rec=".$admini['$x']."\">".$admini['$x']."</a>&nbsp;"; }$x++;} echo "]</td>";
				echo "<td width=\"100\" align=\"center\" valign=\"middle\">".$podcat2[0]."</td>";
				echo "</tr>";
				if ($_GET['action_link'] == "open" && $ar2['category_id'] == $_GET['id_link'] && $numlink2 > 0){
					echo "<tr>\n";
					echo "	<td colspan=\"5\" width=\"850\">";
 					if (CheckPriv("groups_links_del") == 1){ echo "<form enctype=\"multipart/form-data\" action=\"modul_links.php?action=links_del_selected&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;id_link=".$_GET['id_link']."&amp;action_link=".$_GET['action_link']."&amp;project=".$_SESSION['project']."\" method=\"post\">"; }
					echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
					echo "<tr bgcolor=\"#DCE3F1\">\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"."D"."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_NAME."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_LINK."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IN."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_OUT."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IMG1."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IMG2."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_GFX."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_LIST."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_PUBLISH."</span></td>\n";
					echo "</tr>";
 					while($arlink2 = mysql_fetch_array($reslink2)){
						if ($a2 % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
	   					echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
						echo "	<td width=\"80\"><a href=\"modul_links.php?action=links_edit&action_link=".$_GET['action_link']."&amp;id=".$arlink2['links_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> <a href=\"modul_links.php?action=links_del&action_link=".$_GET['action_link']."&amp;id=".$arlink2['links_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a></td> \n";
						echo "	<td align=\"right\">".$arlink2['links_id']."</td>\n";
						echo "	<td align=\"right\"><input type=\"checkbox\" name=\"del_link[]\" value=\"". $arlink2['links_id'] ."\"></td>\n";
						echo "	<td align=\"left\"><a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('".$arlink2['links_description']."', this, event, '450px')\">".$arlink2['links_name']."</a></td>\n";
						echo "	<td align=\"left\" valign=\"top\"><a href=\"http://".$arlink2['links_link']."\" TARGET=\"_blank\">".$arlink2['links_link']."</a></td>\n";
						echo "	<td align=\"right\" valign=\"top\">".$arlink2['links_clicks']."</td>\n";
						echo "	<td align=\"right\" valign=\"top\">".$arlink2['links_out']."</td>\n";
						echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink2['links_picture'] != ""){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
						echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink2['links_picture2'] != ""){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
						echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink2['links_gfx'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
						echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink2['links_list'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
						echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink2['links_publish'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
						echo "</tr>";
					}
					echo "	<tr>";
 					if (CheckPriv("groups_links_del") == 1){
						echo "<td colspan=\"10\">\n";
						echo "	<input type=\"hidden\" name=\"confirm\" value=\"TRUE\">\n";
						echo "	<input type=\"submit\" value=\""._CMN_DEL."\" class=\"eden_button_no\">\n";
						echo "	</form>\n";
						echo "	</td>\n";
						echo "</tr>";
 					}
					echo "		</table>\n";
					echo "	</td>\n";
					echo "</tr>";
 				}
 	//***********************************************************************************
	// Druhe Podmenu
	//***********************************************************************************
				// Zajisteni rozbaleni, nebo zbaleni senamu po kliknuti
				if (($_GET['action'] == "open" && $_GET['id2'] == $ar2['category_id']) || ($_GET['action'] == "close" && $_GET['id2'] == $ar2['category_id'] && $_GET['id3'] != "")) {
 					$res3 = mysql_query("SELECT category_id, category_name, category_image, category_comment, category_admin FROM $db_category WHERE category_parent=".(integer)$_GET['id2']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$num3 = mysql_num_rows($res3);
					$b = 1;
					while ($ar3 = mysql_fetch_array($res3)){
						$vys3 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".(integer)$ar3['category_id']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$podcat3 = mysql_fetch_array($vys3);
						$reslink3 = mysql_query("SELECT links_id, links_name, links_picture, links_picture2, links_link, links_description, links_clicks, links_out, links_list, links_main, links_gfx, links_publish 
						FROM $db_links WHERE links_category_id=".(integer)$ar3['category_id']." ORDER BY links_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$numlink3 = mysql_num_rows($reslink3);
						$hlavicka2 = stripslashes($ar3['category_name']);
						// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
						if ($_GET['action'] == "open" && $_GET['id3'] == $ar3['category_id']) {$command = "close";} elseif ($_GET['action'] == "close" && $_GET['close'] != 2 && $_GET['id3'] == $ar3['category_id']){$command = "close";} else {$command = "open";}
							if ($b % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
	   						echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
							echo "	<td width=\"100\" align=\"left\" valign=\"middle\">";
								if ($numlink3 >= 1){echo "<a href=\"modul_links.php?action_link="; if ($_GET['action_link'] == "open" && $ar3['category_id'] == $_GET['id_link']){echo "close";} else {echo "open";} echo "&amp;action=open&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$ar3['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_open.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_OPEN."\"></a>"; } else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">"; }
								if (CheckPriv("groups_links_add") == 1){echo "<a href=\"modul_links.php?action=links_add&action_link=".$_GET['action_link']."&amp;id=".$arlink2['id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_dtopic.gif\" border=\"0\" alt=\""._CMN_ADD."\"></a> ";}
								echo "</td>\n";
								echo "<td width=\"50\" align=\"center\" valign=\"middle\">".$ar3['category_id']."</td> \n";
								echo "<td width=\"60\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar3['category_image']."\"></td>\n";
								echo "<td width=\"540\" align=\"left\" valign=\"middle\">";
								if ($num2 > $a && $num3 > $b){
									echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat3[0],$command,"2",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
								}elseif ($num2 > $a && $num3 == $b){
									echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat3[0],$command,"2",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
								}elseif ($num2 == $a && $num3 == $b){
									echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat3[0],$command,"2",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
								}elseif ($num2 == $a && $num3 > $b){
									echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat3[0],$command,"2",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
								}
								echo "&nbsp;<a href=\"modul_links.php?action=".$command."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\" title=\"".$ar2['category_comment']."\">".$hlavicka2."</a> (".$numlink3.") ['"; $x=0; while($num03>=$x){if ($_SESSION['login'] == $admini['$x']){echo $admini['$x']."&nbsp;";}elseif ($_SESSION['login'] != $admini['$x'] && $admini['$x'] != ""){ echo "<a href=\"modul_forum.php?project=".$_SESSION['project']."&amp;action=posta&amp;user=".strtoupper ($_SESSION['login'])."&amp;pm_rec=".$admini['$x']."\">".$admini['$x']."</a>&nbsp;"; }$x++;} echo "']</td>";
						echo "	<td width=\"100\" align=\"center\" valign=\"middle\">".$podcat3[0]."</td>\n";
						echo "</tr>";
 						if ($_GET['action_link'] == "open" && $ar3['category_id'] == $_GET['id_link'] && $numlink3 > 0){
						echo "<tr>";
						echo "<td colspan=\"5\" width=\"850\">";
 					if (CheckPriv("groups_links_del") == 1){
						echo "<form enctype=\"multipart/form-data\" action=\"modul_links.php?action=links_del_selected&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;id_link=".$_GET['id_link']."&amp;action_link=".$_GET['action_link']."&amp;project=".$_SESSION['project']."\" method=\"post\">";
					}
				   	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
					echo "<tr bgcolor=\"#DCE3F1\">\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"."D"."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_NAME."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_LINK."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IN."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_OUT."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IMG1."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IMG2."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_GFX."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_LIST."</span></td>\n";
					echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_PUBLISH."</span></td>\n";
					echo "</tr>";
 					while($arlink3 = mysql_fetch_array($reslink3)){
						echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FFFFFF'\">\n";
						echo "	<td width=\"80\"><a href=\"modul_links.php?action=links_edit&action_link=".$_GET['action_link']."&amp;id=".$arlink3['links_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> <a href=\"modul_links.php?action=links_del&action_link=".$_GET['action_link']."&amp;id=".$arlink3['links_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a></td> \n";
						echo "	<td align=\"right\">".$arlink3['links_id']."</td>\n";
						echo "	<td align=\"right\"><input type=\"checkbox\" name=\"del_link[]\" value=\"". $arlink3['links_id'] ."\"></td>\n";
						echo "	<td align=\"left\"><a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('".$arlink3['links_description']."', this, event, '450px')\">".$arlink3['links_name']."</a></td>\n";
						echo "	<td align=\"left\" valign=\"top\"><a href=\"http://".$arlink3['links_link']."\" TARGET=\"_blank\">".$arlink3['links_link']."</a></td>\n";
						echo "	<td align=\"right\" valign=\"top\">".$arlink3['links_clicks']."</td>\n";
						echo "	<td align=\"right\" valign=\"top\">".$arlink3['links_out']."</td>\n";
						echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink3['links_picture'] != ""){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
						echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink3['links_picture2'] != ""){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
						echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink3['links_gfx'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
						echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink3['links_list'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
						echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink3['links_publish'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
						echo "</tr>";
				   	}
				   	if (CheckPriv("groups_links_del") == 1){
						echo "	<td colspan=\"10\">\n";
						echo "		<input type=\"hidden\" name=\"confirm\" value=\"TRUE\">\n";
						echo "		<input type=\"submit\" value=\""._CMN_DEL."\" class=\"eden_button_no\">\n";
						echo "		</form>\n";
						echo "			</td>\n";
						echo "			</tr>\n";
					}
				echo "		</table>\n";
				echo "	</td>\n";
				echo "</tr>";
 			}
 	//***********************************************************************************
	// Treti Podmenu
	//***********************************************************************************
						// Zajisteni rozbaleni, nebo zbaleni senamu po kliknuti
						if (($_GET['action'] == "open" && $_GET['id3'] == $ar3['category_id']) || ($_GET['action'] == "close" && $_GET['id3'] == $ar3['category_id'] && $_GET['id4'] != "")) {
 							$res4 = mysql_query("SELECT category_id, category_name, category_image, category_comment, category_admin FROM $db_category WHERE category_parent=".(float)$_GET['id3']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$num4 = mysql_num_rows($res4);
							$c = 1;
							while ($ar4 = mysql_fetch_array($res4)){
								$vys4 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".$ar4['category_id']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								$podcat4 = mysql_fetch_array($vys4);
								$reslink4 = mysql_query("SELECT links_id, links_name, links_picture, links_picture2, links_description, links_link, links_clicks, links_out, links_list, links_main, links_gfx, links_publish 
								FROM $db_links WHERE links_category_id=".(float)$ar4['category_id']." ORDER BY links_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								$numlink4 = mysql_num_rows($reslink4);
								$hlavicka2 = stripslashes($ar4['category_name']);
								// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
								if ($_GET['action'] == "open" && $_GET['id4'] == $ar4['category_id']) {$command = "close";}elseif ($_GET['action'] == "close" && $_GET['close'] != 3 && $_GET['id4'] == $ar4['category_id']){$command = "close";} else {$command = "open";}
								echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='EBEBEC'\" bgcolor=\"#EBEBEC\">\n";
								echo "	<td width=\"100\" align=\"left\" valign=\"middle\">";
										if ($numlink4 >= 1){echo "<a href=\"modul_links.php?action_link="; if ($_GET['action_link'] == "open" && $ar4['category_id'] == $_GET['id_link']){echo "close";} else {echo "open";} echo "&amp;action=open&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$ar4['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_open.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_OPEN."\"></a>"; } else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">"; }
										if (CheckPriv("groups_links_add") == 1){echo "<a href=\"modul_links.php?action=links_add&action_link=".$_GET['action_link']."&amp;id=".$arlink2['id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_dtopic.gif\" border=\"0\" alt=\""._CMN_ADD."\"></a>";}
								echo "</td>\n";
								echo "<td width=\"50\" align=\"center\" valign=\"middle\">".$ar4['category_id']."</td>\n";
								echo "<td width=\"60\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar4['category_image']."\"></td>\n";
								echo "<td width=\"540\" align=\"left\" valign=\"middle\">";
								if ($num2 > $a && $num3 > $b && $num4 > $c){
									echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat4[0],$command,"3",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
								}elseif ($num2 > $a && $num3 > $b && $num4 == $c){
									echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat4[0],$command,"3",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
								}elseif ($num2 > $a && $num3 == $b && $num4 == $c){
									echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat4[0],$command,"3",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
								}elseif ($num2 == $a && $num3 == $b && $num4 > $c){
									echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat4[0],$command,"3",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
								}elseif ($num2 > $a && $num3 == $b && $num4 > $c){
									echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat4[0],$command,"3",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
								}elseif ($num2 == $a && $num3 == $b && $num4 == $c){
									echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat4[0],$command,"3",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
								}elseif ($num2 == $a && $num3 > $b && $num4 == $c){
									echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat4[0],$command,"3",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
								}elseif ($num2 == $a && $num3 > $b && $num4 > $c){
									echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat4[0],$command,"3",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
								} else {
									echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat3[0],$command,"3",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
								}
								echo "		&nbsp;<a href=\"modul_links.php?action=".$command."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\" title=\"".$ar2['category_comment']."\">".$hlavicka2."</a> (".$numlink4.") ["; $x=0; while($num04>=$x){if ($_SESSION['login'] == $admini['$x']){echo $admini['$x']."&nbsp;";} elseif ($_SESSION['login'] != $admini['$x'] && $admini['$x'] != ""){ echo "<a href=\"modul_forum.php?project=".$_SESSION['project']."&amp;action=posta&amp;user=".strtoupper ($_SESSION['login'])."&amp;pm_rec=".$admini['$x']."\">".$admini['$x']."</a>&nbsp;"; } $x++;} echo"]\n";
								echo "	</td>\n";
								echo "	<td width=\"100\" align=\"center\" valign=\"middle\">".$podcat4[0]."</td>\n";
								echo "</tr>";
								//*********************** Zobrazeni programu z databaze, po rozbaleni
								if ($_GET['action_link'] == "open" && $ar4['category_id'] == $_GET['id_link'] && $numlink4 > 0){
									echo "<tr>\n";
									echo "	<td colspan=\"5\" width=\"850\">";
								 		if (CheckPriv("groups_links_del") == 1){
											echo "<form enctype=\"multipart/form-data\" action=\"modul_links.php?action=links_del_selected&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;id_link=".$_GET['id_link']."&amp;action_link=".$_GET['action_link']."&amp;project=".$_SESSION['project']."\" method=\"post\">"; 
										}
										echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
										echo "<tr bgcolor=\"#DCE3F1\">\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"."D"."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_NAME."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_LINK."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IN."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_OUT."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IMG1."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IMG2."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_GFX."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_LIST."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_PUBLISH."</span></td>\n";
										echo "</tr>";
				 					while($arlink4 = mysql_fetch_array($reslink4)){
										echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FFFFFF'\">\n";
										echo "	<td width=\"80\"><a href=\"modul_links.php?action=links_edit&action_link=".$_GET['action_link']."&amp;id=".$arlink4['links_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> <a href=\"modul_links.php?action=links_del&action_link=".$_GET['action_link']."&amp;id=".$arlink4['links_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a></td> \n";
										echo "	<td align=\"right\">".$arlink4['links_id']."</td>\n";
										echo "	<td align=\"right\"><input type=\"checkbox\" name=\"del_link[]\" value=\"". $arlink4['links_id'] ."\"></td>\n";
										echo "	<td align=\"left\"><a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('".$arlink4['links_description']."', this, event, '450px')\">".$arlink4['links_name']."</a></td>\n";
										echo "	<td align=\"left\" valign=\"top\"><a href=\"http://".$arlink4['links_link']."\" TARGET=\"_blank\">".$arlink4['links_link']."</a></td>\n";
										echo "	<td align=\"right\" valign=\"top\">".$arlink4['links_clicks']."</td>\n";
										echo "	<td align=\"right\" valign=\"top\">".$arlink4['links_out']."</td>\n";
										echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink4['links_picture'] != ""){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
										echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink4['links_picture2'] != ""){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
										echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink4['links_gfx'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
										echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink4['links_list'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
										echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink4['links_publish'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
										echo "</tr>";
									}
				 					if (CheckPriv("groups_links_del") == 1){
										echo "<td colspan=\"10\">		\n";
										echo "	<input type=\"hidden\" name=\"confirm\" value=\"TRUE\">\n";
										echo "	<input type=\"submit\" value=\""._CMN_DEL."\" class=\"eden_button_no\"\n";
										echo "	</form>\n";
										echo "		</td>\n";
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
 									$res5 = mysql_query("SELECT category_id, category_name, category_image, category_comment, category_admin FROM $db_category WHERE category_parent=".(integer)$_GET['id4']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
									$num5 = mysql_num_rows($res5);
									$d = 1;
									while ($ar5 = mysql_fetch_array($res5)){
										$vys5 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".(integer)$ar5['category_id']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
										$podcat5 = mysql_fetch_array($vys5);
										$reslink5 = mysql_query("SELECT links_id, links_name, links_picture, links_picture2, links_description, links_link, links_clicks, links_out, links_list, links_main, links_gfx, links_publish 
										FROM $db_links WHERE links_category_id=".(integer)$ar5['category_id']." ORDER BY links_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
										$numlink5 = mysql_num_rows($reslink5);
										$hlavicka2 = stripslashes($ar5['category_name']);
										// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
										if ($_GET['action'] == "open" && $_GET['id5'] == $ar5['category_id']) {$command = "close";} else {$command = "open";}
										echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='EBEBEC'\" bgcolor=\"#EBEBEC\">\n";
										echo "	<td width=\"100\" align=\"left\" valign=\"middle\">";
												if ($numlink5 >= 1){echo "<a href=\"modul_links.php?action_link="; if ($_GET['action_link'] == "open" && $ar5['category_id'] == $_GET['id_link']){echo "close";} else {echo "open";} echo "&amp;action=open&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$ar5['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_open.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_OPEN."\"></a>"; } else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">"; }
												if (CheckPriv("groups_links_add") == 1){echo "<a href=\"modul_links.php?action=links_add&action_link=".$_GET['action_link']."&amp;id=".$arlink2['id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_dtopic.gif\" border=\"0\"></a>";} 
										echo "	</td>";
										echo "<td width=\"50\" align=\"center\" valign=\"middle\">".$ar5['category_id']."</td>\n";
										echo "<td width=\"60\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar4['category_image']."\"></td>\n";
										echo "<td width=\"540\" align=\"left\" valign=\"middle\">";
											if ($num2 > $a && $num3 > $b && $num4 > $c && $num5 > $d){
												echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
											}elseif ($num2 > $a && $num3 > $b && $num4 > $c && $num5 == $d){
												echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
											}elseif ($num2 > $a && $num3 == $b && $num4 == $c && $num5 == $d){
												echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
											}elseif ($num2 == $a && $num3 == $b && $num4 > $c && $num5 > $d){
												echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
											}elseif ($num2 > $a && $num3 == $b && $num4 > $c && $num5 > $d){
												echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_mezicara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
											}elseif ($num2 == $a && $num3 > $b && $num4 > $c && $num5 == $d){
												echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
											}elseif ($num2 == $a && $num3 == $b && $num4 == $c && $num5 > $d){
												echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
											}elseif ($num2 == $a && $num3 > $b && $num4 == $c && $num5 > $d){
												echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
											}elseif ($num2 > $a && $num3 > $b && $num4 == $c && $num5 == $d){
												echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
											}elseif ($num2 == $a && $num3 > $b && $num4 == $c && $num5 == $d){
												echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
											}elseif ($num2 == $a && $num3 > $b && $num4 > $c && $num5 > $d){
												echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
											}elseif ($num2 == $a && $num3 == $b && $num4 > $c && $num5 == $d){
												echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
											}elseif ($num2 > $a && $num3 > $b && $num4 == $c && $num5 > $d){
												echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
											}elseif ($num2 > $a && $num3 == $b && $num4 == $c && $num5 > $d){
												echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"mezicara");
											}elseif ($num2 > $a && $num3 == $b && $num4 > $c && $num5 == $d){
												echo "<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/sys_strom_cara.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
											}elseif ($num2 == $a && $num3 == $b && $num4 == $c && $num5 == $d){
												echo "<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;<img src=\"images/a_bod.gif\" width=\"13\" height=\"28\" border=\"0\" alt=\"\" align=\"middle\">&nbsp;&nbsp;".Expand($podcat5[0],$command,"4",$ar['category_id'],$ar2['category_id'],$ar3['category_id'],$ar4['category_id'],$ar5['category_id'],$ar6['category_id'],"konec");
											}
										echo "		&nbsp;<a href=\"modul_links.php?action=".$command."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\" title=\"".$ar2['category_comment']."\">".$hlavicka2."</a>(".$numlink5.") ["; $x=0; while($num05>=$x){if ($_SESSION['login'] == $admini['$x']){echo $admini['$x']."&nbsp;";}elseif ($_SESSION['login'] != $admini['$x'] && $admini['$x'] != ""){ echo "<a href=\"modul_forum.php?project=".$_SESSION['project']."&amp;action=posta&amp;user=".strtoupper ($_SESSION['login'])."&amp;pm_rec=".$admini['$x']."\">".$admini['$x']."</a>&nbsp;"; }$x++;} echo "]</td>\n";
										echo "	<td width=\"100\" align=\"center\" valign=\"middle\">".$podcat5[0]."</td>											\n";
										echo "</tr>";
										if ($_GET['action_link'] == "open" && $ar5['category_id'] == $_GET['id_link'] && $numlink5 > 0){
										echo "<tr>\n";
										echo "	<td colspan=\"5\" width=\"850\">";
										if (CheckPriv("groups_links_del") == 1){
											echo "<form enctype=\"multipart/form-data\" action=\"modul_links.php?action=links_del_selected&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;id_link=".$_GET['id_link']."&amp;action_link=".$_GET['action_link']."&amp;project=".$_SESSION['project']."\" method=\"post\">";
										}
										echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
										echo "<tr bgcolor=\"#DCE3F1\">\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"."D"."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_NAME."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_LINK."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IN."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_OUT."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IMG1."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IMG2."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_GFX."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_LIST."</span></td>\n";
										echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_PUBLISH."</span></td>\n";
										echo "</tr>";
										while($arlink5 = mysql_fetch_array($reslink5)){
											echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FFFFFF'\">\n";
											echo "	<td width=\"80\"><a href=\"modul_links.php?action=links_edit&action_link=".$_GET['action_link']."&amp;id=".$arlink5['links_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> <a href=\"modul_links.php?action=links_del&action_link=".$_GET['action_link']."&amp;id=".$arlink5['links_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a></td> \n";
											echo "	<td align=\"right\">".$arlink5['links_id']."</td>\n";
											echo "	<td align=\"right\"><input type=\"checkbox\" name=\"del_link[]\" value=\"". $arlink5['links_id'] ."\"></td>\n";
											echo "	<td align=\"left\"><a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('".$arlink5['links_description']."', this, event, '450px')\">".$arlink5['links_name']."</a></td>\n";
											echo "	<td align=\"left\" valign=\"top\"><a href=\"http://".$arlink5['link']."\" TARGET=\"_blank\">".$arlink5['links_link']."</a></td>\n";
											echo "	<td align=\"right\" valign=\"top\">".$arlink5['links_clicks']."</td>\n";
											echo "	<td align=\"right\" valign=\"top\">".$arlink5['links_out']."</td>\n";
											echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink5['links_picture'] != ""){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
											echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink5['links_picture2'] != ""){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
											echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink5['links_gfx'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
											echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink5['links_list'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
											echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink5['links_publish'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
											echo "</tr>";
										}
										if (CheckPriv("groups_links_del") == 1){
											echo "	<td colspan=\"10\">		\n";
											echo "		<input type=\"hidden\" name=\"confirm\" value=\"TRUE\">\n";
											echo "		<input type=\"submit\" value=\""._CMN_DEL."\" class=\"eden_button_no\">\n";
											echo "		</form>\n";
											echo "	</td>\n";
											echo "</tr>";
										}
										echo "		</table>\n";
										echo "	</td>\n";
										echo "</tr>";
									}
 	//***********************************************************************************
	// Pate Podmenu
	//***********************************************************************************
										// Zajisteni rozbaleni, nebo zbaleni senamu po kliknuti
										if (($_GET['action'] == "open" && $_GET['id5'] == $ar5['category_id']) || ($_GET['action'] == "close" && $_GET['id5'] == $ar5['category_id'] && $_GET['id6'] != "")) {
 											$res6 = mysql_query("SELECT category_id, category_name, category_image, category_comment, category_admin FROM $db_category WHERE category_parent=".(float)$_GET['id5']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
											$num6 = mysql_num_rows($res6);
											$e = 1;
											while ($ar6 = mysql_fetch_array($res6)){
												$vys6 = mysql_query("SELECT COUNT(*) FROM $db_category WHERE category_parent=".(float)$ar6['category_id']." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
												$podcat6 = mysql_fetch_array($vys6);
												$reslink6 = mysql_query("SELECT links_id, links_name, links_picture, links_picture2, links_description, links_link, links_clicks, links_out, links_list, links_main, links_gfx, links_publish 
												FROM $db_links WHERE links_category_id=".(float)$ar6['category_id']." ORDER BY links_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
												$numlink6 = mysql_num_rows($reslink6);
												$hlavicka2 = stripslashes($ar6['category_name']);
												// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
												if ($_GET['action'] == "open" && $_GET['id6'] == $ar6['category_id']) {$command = "close";} else {$command = "open";}
												echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='EBEBEC'\" bgcolor=\"#EBEBEC\">\n";
												echo "	<td width=\"100\" align=\"left\" valign=\"middle\">";
													if ($numlink6 >= 1){echo "<a href=\"modul_links.php?action_link="; if ($_GET['action_link'] == "open" && $ar6['category_id'] == $_GET['id_link']){echo "close";} else {echo "open";} echo "&amp;action=open&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_open.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_OPEN."\"></a>"; } else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">";}
												echo "	</td>";
												echo "	<td width=\"50\" align=\"center\" valign=\"middle\">".$ar6['category_id']."</td>\n";
												echo "	<td width=\"60\" align=\"center\" valign=\"middle\"><img height=\"26\" src=\"".$url_category.$ar4['category_image']."\"></td>\n";
												echo "	<td width=\"540\" align=\"left\" valign=\"middle\">";
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
												echo "		&nbsp;<a href=\"modul_links.php?action=".$command."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&amp;project=".$_SESSION['project']."\" title=\"".$ar2['category_comment']."\">".$hlavicka2."</a> (".$numlink6.") ["; $x=0; while($num06>=$x){if ($_SESSION['login'] == $admini['$x']){echo $admini['$x']."&nbsp;";} elseif ($_SESSION['login'] != $admini['$x'] && $admini['$x'] != ""){ echo "<a href=\"modul_forum.php?project=".$_SESSION['project']."&amp;action=posta&amp;user=".strtoupper ($_SESSION['login'])."&amp;pm_rec=".$admini['$x']."\">".$admini['$x']."</a>&nbsp;"; }$x++;} echo "]</td>\n";
												echo "	<td width=\"100\" align=\"center\" valign=\"middle\">".$podcat6[0]."</td>\n";
												echo "</tr>";
 		 //*********************** Zobrazeni programu z databaze, po rozbaleni
												if ($_GET['action_link'] == "open" && $ar6['category_id'] == $_GET['id_link'] && $numlink6 > 0){
													echo "<tr>\n";
													echo "	<td colspan=\"5\" width=\"850\">";
 				   									if (CheckPriv("groups_links_del") == 1){
														echo "<form enctype=\"multipart/form-data\" action=\"modul_links.php?action=links_del_selected&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;id_link=".$_GET['id_link']."&amp;action_link=".$_GET['action_link']."&amp;project=".$_SESSION['project']."\" method=\"post\">";
													}
													echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
													echo "<tr bgcolor=\"#DCE3F1\">\n";
													echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span>\n";
													echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"."D"."</span></td></td>\n";
													echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
													echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_NAME."</span></td>\n";
													echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_LINK."</span></td>\n";
													echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IN."</span></td>\n";
													echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_OUT."</span></td>\n";
													echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IMG1."</span></td>\n";
													echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IMG2."</span></td>\n";
													echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_GFX."</span></td>\n";
													echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_LIST."</span></td>\n";
													echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_PUBLISH."</span></td>\n";
													echo "</tr>";
								 					while($arlink6 = mysql_fetch_array($reslink6)){
														echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FFFFFF'\">\n";
														echo "	<td width=\"80\"><a href=\"modul_links.php?action=links_edit&action_link=".$_GET['action_link']."&amp;id=".$arlink6['links_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> <a href=\"modul_links.php?action=links_del&action_link=".$_GET['action_link']."&amp;id=".$arlink6['links_id']."&amp;id1=".$ar['category_id']."&amp;id2=".$ar2['category_id']."&amp;id3=".$ar3['category_id']."&amp;id4=".$ar4['category_id']."&amp;id5=".$ar5['category_id']."&amp;id6=".$ar6['category_id']."&id_link=".$_GET['id_link']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a></td> \n";
														echo "	<td align=\"right\">".$arlink6['links_id']."</td>\n";
														echo "	<td align=\"right\"><input type=\"checkbox\" name=\"del_link[]\" value=\"". $arlink6['links_id'] ."\"></td>\n";
														echo "	<td align=\"left\"><a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('".$arlink6['links_description']."', this, event, '450px')\">".$arlink6['links_name']."</a></td>\n";
														echo "	<td align=\"left\" valign=\"top\"><a href=\"http://".$arlink6['links_link']."\" TARGET=\"_blank\">".$arlink6['links_link']."</a></td>\n";
														echo "	<td align=\"right\" valign=\"top\">".$arlink6['links_clicks']."</td>\n";
														echo "	<td align=\"right\" valign=\"top\">".$arlink6['links_out']."</td>\n";
														echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink6['links_picture'] != ""){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
														echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink6['links_picture2'] != ""){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
														echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink6['links_gfx'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
														echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink6['links_list'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
														echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink6['links_publish'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
														echo "</tr>";
													}
													if (CheckPriv("groups_links_del") == 1){
														echo "	<td colspan=\"10\">		\n";
														echo "		<input type=\"hidden\" name=\"confirm\" value=\"TRUE\">\n";
														echo "		<input type=\"submit\" value=\""._CMN_DEL."\" class=\"eden_button_no\">\n";
														echo "		</form>\n";
														echo "	</td>\n";
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
	echo "<tr>\n";
	echo "	<td colspan=\"5\">\n";
	echo "		<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "			<tr>\n";
	echo "				<td colspan=\"6\" height=\"30\" align=\"left\" valign=\"middle\">&nbsp;</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td colspan=\"6\" align=\"left\" valign=\"middle\"><span class=\"nadpis-boxy\">"._LINKS_MISFILES."</span></td>\n";
	echo "			</tr>\n";
	echo "			<tr bgcolor=\"#DCE3F1\">\n";
	echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_NAME."</span></td>\n";
	echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_LINK."</span></td>\n";
	echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IN."</span></td>\n";
	echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_OUT."</span></td>\n";
	echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IMG1."</span></td>\n";
	echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_IMG2."</span></td>\n";
	echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_GFX."</span></td>\n";
	echo "				<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_LIST."</span></td>\n";
	echo "			</tr>";
 	$res7 = mysql_query("SELECT links_id, links_name, links_picture, links_picture2, links_link, links_clicks, links_out, links_list FROM $db_links WHERE links_category_id=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($arlink7 = mysql_fetch_array($res7)){
		echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FAF4FF'\" bgcolor=\"#FAF4FF\">";
		echo " 	<td width=\"80\"><a href=\"modul_links.php?action=links_edit&amp;id=".$arlink7['links_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\"></a> <a href=\"modul_links.php?action=links_del&amp;id=".$arlink7['links_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a></td> \n";
		echo "		<td align=\"left\">".$arlink7['links_name']."</td>\n";
		echo "		<td align=\"left\" valign=\"top\"><a href=\"http://".$arlink7['links_link']."\" TARGET=\"_blank\">".$arlink7['links_link']."</a></td>\n";
		echo "		<td align=\"right\" valign=\"top\">".$arlink7['links_clicks']."</td>\n";
		echo "		<td align=\"right\" valign=\"top\">".$arlink7['links_out']."</td>\n";
		echo "		<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink7['links_picture'] != ""){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
		echo "		<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink7['links_picture2'] != ""){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
		echo "		<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink7['links_gfx'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
		echo "		<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($arlink7['links_list'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>";
		echo "</tr>";
 	}
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>";
	echo "</table>";
}
//********************************************************************************************************
//                                                                                                        
//             ZOBRAZENI PARTNERU                                                                		  
//                                                                                                        
//********************************************************************************************************
function ShowNonpublished(){
	
	global $db_links;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['login']);
	
	if ($_POST['confirm'] == "true"){
		if ($_POST['mod_funkce'] == "delete"){
			$i = 0;
			$num = count ($_POST['mark']);
			while ($i < $num) {
				$mark = $_POST['mark'][$i];
				$res = mysql_query("DELETE FROM $db_links WHERE links_id=".(float)$mark) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$i++;
			}
			unset($_POST['confirm']);
			ShowNonpublished();
			exit;
		}
		if ($_POST['mod_funkce'] == "publikovat"){
			$i = 0;
			$num = count ($_POST['mark']);
			while ($i < $num) {
				$mark = $_POST['mark'][$i];
				$res = mysql_query("UPDATE $db_links SET links_publish=1 WHERE links_id=".(integer)$mark) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$i++;
			}
			unset($_POST['confirm']);
			ShowNonpublished();
			exit;
		}
	}
	if ($_POST['confirm'] != "true"){
		$res = mysql_query("SELECT links_id, links_name, links_link FROM $db_links WHERE links_publish=0 ORDER BY links_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num = mysql_num_rows($res);
		
		echo Menu();
		
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "<tr class=\"popisky\">\n";
		echo "	<td align=\"center\"><form action=\"modul_links.php?action=links_show_nonpublished&amp;project=".$_SESSION['project']."\" method=\"post\" name=\"forma\"\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
		echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_NAME."</span></td>\n";
		echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._LINKS_LINK."</span></td>\n";
		echo "</tr>";
 		while ($ar = mysql_fetch_array($res)){
			$res2 = mysql_query("SELECT COUNT(*) FROM $db_links WHERE links_publish=0 ORDER BY links_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar2 = mysql_fetch_array($res2);
			if ($ar2[0] <> 0){
				echo "<tr>\n";
				echo "	<td width=\"80\"><input type=\"checkbox\" name=\"mark[]\" value=\"".$ar['links_id']."\"><a href=\"modul_links.php?action=links_edit&amp;id=".$ar['links_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\"></a> <a href=\"modul_links.php?action=links_del&amp;id=".$ar['links_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\"></a></td> \n";
				echo "	<td align=\"left\">".$ar['links_name']."</td>\n";
				echo "	<td align=\"left\" valign=\"top\"><a href=\"http://".$ar['links_link']."\" TARGET=\"_blank\">".$ar['links_link']."</a></td>\n";
				echo "</tr>";
 			}
		}
		echo "	<tr>\n";
		echo "		<td colspan=\"3\"><select name=\"mod_funkce\">\n";
		echo "				<option value=\"publikovat\"> "._LINKS_PUBLISH_OZ."</option>\n";
		echo "				<option value=\"delete\"> "._LINKS_DELETE_OZ."</option>\n";
		echo "			</select>\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"submit\" value=\"". _CMN_SUBMIT."\" class=\"eden_button\"></form></td> \n";
		echo "	</tr>\n";
		echo "</table>";
 	}
}
//********************************************************************************************************
//                                                                                                        
//             PRIDAVANI A EDITACE PARTNERU 	                                                   		  
//                                                                                                        
//********************************************************************************************************
function AddLink(){
	
	global $db_links,$db_category;
	global $eden_cfg;
	global $ftp_path_links;
	global $url_links;
	
	// Provereni opravneni
	if ($_GET['action'] == "links_add"){
		if (CheckPriv("groups_links_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($_GET['action'] == "links_edit"){
		if (CheckPriv("groups_links_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	} else {
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	if ($_POST['confirm'] <> "true"){
		// Nastaveni vybrane kategorie pro nabidku nadrazene kategorie
		$links_id = $_GET['id1'];
		if ($_GET['action'] == "links_edit"){
			$res = mysql_query("SELECT * FROM $db_links WHERE links_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar = mysql_fetch_array($res);
			$links_id = $ar['links_category_id'];
		}
		
		$links_description = str_ireplace( "&quot;","\"",$ar['links_description']);
		$links_description = str_ireplace( "&acute","'",$links_description);
		
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
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LINKS_PUBLISH."</strong><form action=\"sys_save.php?action="; if ($_GET['action'] == "links_edit"){echo "links_edit";} else {echo "links_add";} echo "&amp;id=".$_GET['id']."&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;id_link=".$_GET['id_link']."&amp;action_link=".$_GET['action_link']."&amp;project=".$_SESSION['project']."&amp;close=".$_GET['close']."\" method=\"post\" name=\"forma\" enctype=\"multipart/form-data\"></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"checkbox\" name=\"links_publish\" value=\"1\" "; if ($ar['links_publish']==1) { echo " checked";} echo "></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LINKS_NAME."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"links_name\" size=\"60\" value=\"".stripslashes($ar['links_name'])."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LINKS_LINK."</strong><br>"._LINKS_HELP."</td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"links_link\" size=\"60\" value=\"".stripslashes($ar['links_link'])."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LINKS_DESCRIPTION."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><textarea name=\"links_description\" cols=\"60\" rows=\"7\">".stripslashes($ar['links_description'])."</textarea></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LINKS_CATEGORY."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\">";
					// Select category
					echo EdenCategorySelect($links_id, "links", "links_category_id", 0);
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CMN_IMAGE." 1</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input name=\"links_picture\" type=\"file\" size=\"30\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CMN_IMAGE." 2</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input name=\"links_picture2\" type=\"file\" size=\"30\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LINKS_ILL_IMAGE." 1</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\">";
				$extenze = substr ($ar['links_picture'], -3);if ($extenze == "jpg" || $extenze == "gif" || $extenze == "png"){echo "<p><img src=\"".$url_links.$ar['links_picture']."\" border=\"0\"></p>"; }
				$extenze = substr ($ar['links_picture'], -3);
				if ($extenze == "swf"){
					echo "<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://linkload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"468\" height=\"60\" id=\"adv2\" align=\"middle\">\n";
					echo "<param name=\"allowScriptAccess\" value=\"sameDomain\" />\n";
					echo "<param name=\"movie\" value=\"".$url_links.$ar['links_picture']."\" />\n";
					echo "<param name=\"menu\" value=\"false\" />\n";
					echo "<param name=\"quality\" value=\"high\" />\n";
					echo "<param name=\"bgcolor\" value=\"#ffffff\" />\n";
					echo "<embed src=\"".$url_links.$ar['links_picture']."\" menu=\"false\" quality=\"high\" bgcolor=\"#ffffff\" name=\"".$ar['links_picture']."\" align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />\n";
					echo "</object>";
 				}
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LINKS_ILL_IMAGE." 2</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\">";
				$extenze = substr ($ar['links_picture2'], -3);if ($extenze == "jpg" || $extenze == "gif" || $extenze == "png"){echo "<p><img src=\"".$url_links.$ar['links_picture2']."\" border=\"0\"></p>";}
				$extenze = substr ($ar['links_picture2'], -3);
				if ($extenze == "swf"){
					echo "<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://linkload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"468\" height=\"60\" id=\"adv2\" align=\"middle\">\n";
					echo "<param name=\"allowScriptAccess\" value=\"sameDomain\" />\n";
					echo "<param name=\"movie\" value=\"".$url_links.$ar['links_picture2']."\" />\n";
					echo "<param name=\"menu\" value=\"false\" />\n";
					echo "<param name=\"quality\" value=\"high\" />\n";
					echo "<param name=\"bgcolor\" value=\"#ffffff\" />\n";
					echo "<embed src=\"".$url_links.$ar['links_picture2']."\" menu=\"false\" quality=\"high\" bgcolor=\"#ffffff\" name=\"".$ar['links_picture2']."\" align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />\n";
					echo "</object>";
 				}
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LINKS_AFFILIATES."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\">";
		echo "			<input type=\"checkbox\" name=\"links_main\" value=\"1\" "; if ($ar['links_main'] == 1 || $_GET['action'] == "links_add") { echo "checked=\"checked\"";} echo ">";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LINKS_GFX."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		echo "			<input type=\"checkbox\" name=\"links_gfx\" value=\"1\" "; if ($ar['links_gfx'] == 1 || $_GET['action'] == "links_add") { echo "checked=\"checked\"";} echo ">";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LINKS_LIST."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		echo "			<input type=\"checkbox\" name=\"links_list\" value=\"1\" "; if ($ar['links_list'] == 1 || $_GET['action'] == "links_add") { echo "checked=\"checked\"";} echo ">";
		echo "		</td>\n";
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
//********************************************************************************************************
//                                                                                                        
//             MAZANI PARTNERU    	                                                            		  
//                                                                                                        
//********************************************************************************************************
function DeleteLinks(){
	
	global $db_links;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_links_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	$res = mysql_query("SELECT links_id, links_name, links_link FROM $db_links WHERE links_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"100\"><strong>ID</strong></td>\n";
	echo "		<td align=\"left\">".$ar['links_id']."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"100\"><strong>"._LINKS_NAME."</strong></td>\n";
	echo "		<td align=\"left\">".$ar['links_name']."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"100\"><strong>"._LINKS_LINK."</strong></td>\n";
	echo "		<td align=\"left\">".$ar['links_link']."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" colspan=\"2\"><br><strong><span style=\"color : #FF0000;\">"._LINKS_CHECKDELETE."</span></strong><br><br></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\">\n";
	echo "			<form action=\"sys_save.php?action=links_del&amp;id=".$_GET['id']."&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;id_link=".$_GET['id_link']."&amp;action_link=".$_GET['action_link']."&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
	echo "				<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
	echo "				<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "		<td valign=\"top\">\n";
	echo "			<form action=\"modul_links.php?action=&amp;id=".$_GET['id']."&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;id3=".$_GET['id3']."&amp;id4=".$_GET['id4']."&amp;id5=".$_GET['id5']."&amp;id6=".$_GET['id6']."&amp;id_link=".$_GET['id_link']."&amp;action_link=".$_GET['action_link']."&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
	echo "				<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}

include ("inc.header.php");
	if ($_GET['action'] == "") { ShowMain(); }
	if ($_GET['action'] == "links_del_selected") { ShowMain(); }
	if ($_GET['action'] == "links_show_nonpublished") {ShowNonpublished();}
	if ($_GET['action'] == "links_edit") { AddLink(); }
	if ($_GET['action'] == "links_add") { AddLink(); }
	if ($_GET['action'] == "links_del") { DeleteLinks(); }
	if ($_GET['action'] == "open") { ShowMain(); }
	if ($_GET['action'] == "close") { ShowMain(); }
include ("inc.footer.php");