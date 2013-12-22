<?php
/***********************************************************************************************************
*																											
*		PRIDAVANI A EDITACE TAGS																		
*																											
***********************************************************************************************************/
function ShowMain(){
	
	global $db_dictionary;
	
	/* Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren */
	KillUse($_SESSION['loginid']);
	
	if ($_GET["action"] == ""){$_GET["action"] = "tag_add";}
	
	/* Provereni opravneni */
	if ($_GET['action'] == "tag_edit"){
		if (CheckPriv("groups_article_edit") <> 1) {
			header ("Location: ".$eden_cfg['url_cms']."modul_tags.php?action=tag_add&project=".$_SESSION['project']."&msg=nep");
		}
	} elseif ($_GET['action'] == "tag_del"){
		if (CheckPriv("groups_article_del") <> 1) {
			header ("Location: ".$eden_cfg['url_cms']."modul_tags.php?action=tag_add&project=".$_SESSION['project']."&msg=nep");
		}
	} else {
		if (CheckPriv("groups_article_add") <> 1) {
			header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']."&msg=nep");
		}
	}
	
	if ($_GET['action'] != "tag_add"){
		$res_tags = mysql_query("SELECT tag_id, tag_name FROM "._DB_TAGS." WHERE tag_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_tags = mysql_fetch_array($res_tags);
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._TAGS." - "; if ($_GET['action'] == "tag_add"){ echo _TAGS_ADD;} elseif ($_GET['action'] == "tag_del"){echo _TAGS_DEL;} else {echo _TAGS_EDIT;} echo "</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""; if ($_GET['action'] == "tag_add"){ echo _TAGS_ADD;} elseif ($_GET['action'] == "tag_del"){echo _TAGS_DEL;} else {echo _TAGS_EDIT;} echo "\">\n";
	echo "			<a href=\"modul_tags.php?action=tag_add&amp;project=".$_SESSION['project']."\">"._TAGS."</a></td>\n";
	echo "	</tr>\n";
		/* Zobrazeni chyb a hlasek systemu */
		if ($_GET['msg']){
	  		echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">".SysMsg($_GET['msg'])."</td></tr>";
		}
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\"><form action=\"sys_save.php?action=".$_GET['action']."&amp;id=".$_GET['id']."\" method=\"post\">\n";
	echo "			<strong>"._TAGS_NAME."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"tag_name\" maxlength=\"255\" size=\"60\" "; if ($_GET['action'] != "tag_add"){echo "value=\"".$ar_tags['tag_name']."\"";} echo "></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\">&nbsp;</td>\n";
	echo "		<td align=\"left\">";
	echo "			<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "tag_add"){echo _TAGS_ADD;} elseif ($_GET['action'] == "tag_del"){echo _TAGS_DEL;} else {echo _TAGS_EDIT;} echo "\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._TAGS_NAME."</span></td>\n";
	echo "	</tr>";
	$res = mysql_query("SELECT tag_id, tag_name FROM "._DB_TAGS." ORDER BY tag_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i=1;
	while($ar = mysql_fetch_array($res)){
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
   		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"80\" align=\"center\">";
		 		if (CheckPriv("groups_article_edit") == 1){echo "<a href=\"modul_tags.php?action=tag_edit&amp;id=".$ar['tag_id']."&amp;project=".$_SESSION['project']."&amp;\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
				if (CheckPriv("groups_article_del") == 1){echo " <a href=\"modul_tags.php?action=tag_del&amp;id=".$ar['tag_id']."&amp;project=".$_SESSION['project']."&amp;\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
		echo "	</td>\n";
		echo "	<td width=\"40\" align=\"left\">".$ar['tag_id']."</td>\n";
		echo "	<td align=\"left\">".PrepareFromDB($ar['tag_name'])."</td>\n";
		$i++;
	}
	echo "</table>";
}
include ("inc.header.php");
	if ($_GET['action'] == "" || $_GET['action'] == "tag_add") { ShowMain(); }
	if ($_GET['action'] == "tag_edit") { ShowMain(); }
	if ($_GET['action'] == "tag_del") { ShowMain(); }
include ("inc.footer.php");