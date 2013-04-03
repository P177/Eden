<?php
/***********************************************************************************************************
*																											
*			SETUP																							
*																											
***********************************************************************************************************/
function ClanSetup(){
	
	global $db_clan_setup,$db_country;
	global $eden_cfg;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	if (CheckPriv("groups_setup_edit") <> 1) { echo _NOTENOUGHPRIV;exit;}
	
	$res_clan_setup = mysql_query("SELECT * FROM $db_clan_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_clan_setup = mysql_fetch_array($res_clan_setup);
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._CLAN_SETUP."</td>\n";
	echo "	</tr>\n";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['msg']){
		echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr align=\"left\" valign=\"top\" style=\"background-color:FFDEDF\">\n";
	echo "		<td width=\"850\" colspan=\"2\"><span id=\"tab_2\" class=\"nadpis_sekce\">"._CLAN_SETUP_BASIC."</span><br></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><br><strong>"._CLAN_SETUP_NAME."</strong></td>\n";
	echo "		<td width=\"500\" align=\"left\"><form action=\"sys_save.php?action=clan_setup\" method=\"post\" name=\"form1\" enctype=\"multipart/form-data\"><br><input type=\"text\" value=\"".$ar_clan_setup['clan_setup_name']."\" name=\"clan_setup_name\" size=\"35\" maxlength=\"50\"></td>\n";
	echo "	</tr>";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._CLAN_SETUP_TAG."</strong></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input type=\"text\" value=\"".$ar_clan_setup['clan_setup_tag']."\" name=\"clan_setup_tag\" size=\"35\" maxlength=\"50\"></td>\n";
	echo "	</tr>";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._CLAN_SETUP_WEB."</strong></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input type=\"text\" value=\"".$ar_clan_setup['clan_setup_web']."\" name=\"clan_setup_web\" size=\"35\" maxlength=\"50\"></td>\n";
	echo "	</tr>";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._CLAN_SETUP_IRC."</strong></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input type=\"text\" value=\"".$ar_clan_setup['clan_setup_irc']."\" name=\"clan_setup_irc\" size=\"35\" maxlength=\"50\"></td>\n";
	echo "	</tr>";
	echo "<tr align=\"left\" valign=\"top\">\n";
	echo "	<td width=\"250\" align=\"right\"><strong>"._CLAN_SETUP_COUNTRY."</strong><br></td>\n";
	echo "	<td width=\"500\" align=\"left\">\n";
				$res_country = mysql_query("SELECT country_id, country_name FROM $db_country") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$num_country = mysql_num_rows($res_country);
				if($num_country > 1){
					echo "<select name=\"clan_setup_country\" class=\"input\">\n";
					while ($ar_country = mysql_fetch_array($res_country)){
						echo "<option value=\"".$ar_country['country_id']."\"";
						if ($ar_country['country_id'] == $ar_clan_setup['clan_setup_country_id']){echo " selected";}
						echo ">".$ar_country['country_name']."</option>\n";
					}
					echo "</select>\n";
				} else {
					$ar_country = mysql_fetch_array($res_country);
					echo $ar_country['country_name'];
					echo "<input type=\"hidden\" name=\"setup_basic_country\" value=\"".$ar_country['country_id']."\">\n";
				}
	echo "	</td>\n";
	echo "</tr>\n";
	echo "</table>";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"750\" colspan=\"2\"><br><br>\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"submit\" value=\""._SETUP_SET."\" class=\"eden_button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
include ("inc.header.php");
	if ($_GET['action'] == "") { ClanSetup();}
	if ($_GET['action'] == "clan_setup") { ClanSetup();}
include ("inc.footer.php");