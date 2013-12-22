<?php
/***********************************************************************************************************
*																											
*		ZOBRAZENI SKUPIN																					
*																											
***********************************************************************************************************/
function ShowMain(){
	
	global $db_admin,$db_groups;
	
	/* Kontrola opravneni zmeny */
	//$res_adm = mysql_query("SELECT admin_priv FROM $db_admin WHERE admin_id=".(float)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	//$ar_adm = mysql_fetch_array($res_adm);
	//$res_adm_group = mysql_query("SELECT groups_level FROM $db_groups WHERE groups_id=".$ar_adm['admin_priv']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	//$ar_adm_group = mysql_fetch_array($res_adm_group);
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._GROUPS."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"500\"><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._GROUPS_ADDGROUP."\"><a href=\"sys_groups.php?action=add&amp;project=".$_SESSION['project']."\">"._GROUPS_ADDGROUP."</a></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"60\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"30\"><span class=\"nadpis-boxy\">ID</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._GROUPS_LEVEL_1."</span></td>\n";
	echo "		<td width=\"80\"><span class=\"nadpis-boxy\">"._USERS."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._GROUPS_NAME."</span></td>\n";
	echo "	</tr>";
		$res = mysql_query("SELECT groups_id, groups_level, groups_name, groups_description FROM $db_groups ORDER BY groups_level DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$cislo = 0;
		$y=1;
		while ($ar = mysql_fetch_array($res)){
			$res2 = mysql_query("SELECT admin_id, admin_firstname, admin_name, admin_nick FROM $db_admin WHERE admin_priv=".(integer)$ar['groups_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$num2 = mysql_num_rows($res2);
			if ($y % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
			echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
			echo "	<td valign=\"top\">"; if (CheckPriv("groups_group_edit") == 1 && (CheckPriv("groups_level") > $ar['groups_level'] || CheckPriv("groups_level") == 99)){ echo "<a href=\"sys_groups.php?action=edit&amp;id=".$ar['groups_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\"></a> "; } if (CheckPriv("groups_group_edit") == 1 && (CheckPriv("groups_level") > $ar['groups_level'] || CheckPriv("groups_level") == 99)){ echo "<a href=\"sys_groups.php?action=del&amp;id=".$ar['groups_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\"></a>"; } echo "</td>\n";
			echo "	<td align=\"right\" valign=\"top\">".$ar['groups_id']."</td>\n";
			echo "	<td align=\"right\" valign=\"top\">".$ar['groups_level']."</td>\n";
			echo "	<td align=\"center\" valign=\"top\">".$num2."</td>\n";
			echo "	<td valign=\"top\"><strong>".$ar['groups_name']."</strong><br>";
		   	echo $ar['groups_description']."<br><em>";
	 		$i=1;
			while ($ar2 = mysql_fetch_array($res2)){
				echo "<a href=\"sys_admin.php?action=admins&from=groups&show_status=user&search_by=admin_id&search_this=".$ar2["admin_id"]."&project=".$_SESSION['project']."\" target=\"_self\" style=\"color: #ff0000;\">".$ar2['admin_nick']."</a>";
				if ($i < $num2){echo ", ";}
				$i++;
			}
			echo "</em><br><br>";
			echo "		</td>";
			echo "	</tr>";
			$y++;
	 		$cislo++;
		}
	echo "</table>";
}

/***********************************************************************************************************
*																											
*		PRIDANI A EDITACE SKUPIN																			
*																											
***********************************************************************************************************/
function AddGroup(){
	
	global $db_groups;
	
	/* Zabraneni ulozeni bezpecnostniho levelu o vetsi hodnote nez ma dany admin */
	if ($_GET['action'] == "add" || $_GET['action'] == "edit" ){
		if (CheckPriv("groups_level") >= (float)$_POST['groups_level']){
			$groups_level = (float)$_POST['groups_level'];
		} else {
			$groups_level = CheckPriv("groups_level");
		}
	}
   	// CHECK PRIVILEGIES
	if (CheckPriv("groups_group_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	if ($_POST['confirm'] == TRUE){
		if ($_GET['action'] == "add"){
			$res2 = mysql_query("SELECT * FROM $db_groups WHERE groups_name='".mysql_real_escape_string($_POST['groups_name'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			if (mysql_num_rows($res2)<1) {$res = mysql_query("INSERT INTO $db_groups VALUES('',
			'".(integer)$groups_level."',
			'".mysql_real_escape_string($_POST['groups_name'])."',
			'".mysql_real_escape_string($_POST['groups_desc'])."',
			'".(integer)$_POST['groups_article_add']."',
			'".(integer)$_POST['groups_article_edit']."',
			'".(integer)$_POST['groups_article_del']."',
			'".(integer)$_POST['groups_article_all_add']."',
			'".(integer)$_POST['groups_article_all_edit']."',
			'".(integer)$_POST['groups_article_all_del']."',
			'".(integer)$_POST['groups_article_all_kill_user']."',
			'".(integer)$_POST['groups_article_changes']."',
			'".(integer)$_POST['groups_article_ul']."',
			'".(integer)$_POST['groups_article_channel_add']."',
			'".(integer)$_POST['groups_article_channel_edit']."',
			'".(integer)$_POST['groups_article_channel_del']."',
			'".(integer)$_POST['groups_admin_add']."',
			'".(integer)$_POST['groups_admin_edit']."',
			'".(integer)$_POST['groups_admin_del']."',
			'".(integer)$_POST['groups_admin_ban']."',
			'".(integer)$_POST['groups_article_public_add']."',
			'".(integer)$_POST['groups_article_public_edit']."',
			'".(integer)$_POST['groups_article_public_del']."',
			'".(integer)$_POST['groups_article_public_submit']."',
			'".(integer)$_POST['groups_cat_add']."',
			'".(integer)$_POST['groups_cat_edit']."',
			'".(integer)$_POST['groups_cat_del']."',
			'".(integer)$_POST['groups_cat_ul']."',
			'".(integer)$_POST['groups_users_add']."',
			'".(integer)$_POST['groups_users_edit']."',
			'".(integer)$_POST['groups_users_del']."',
			'".(integer)$_POST['groups_links_add']."',
			'".(integer)$_POST['groups_links_edit']."',
			'".(integer)$_POST['groups_links_del']."',
			'".(integer)$_POST['groups_links_ul']."',
			'".(integer)$_POST['groups_wp_add']."',
			'".(integer)$_POST['groups_wp_edit']."',
			'".(integer)$_POST['groups_wp_del']."',
			'".(integer)$_POST['groups_wp_users_del']."',
			'".(integer)$_POST['groups_groups_add']."',
			'".(integer)$_POST['groups_groups_edit']."',
			'".(integer)$_POST['groups_groups_del']."',
			'".(integer)$_POST['groups_smiles_add']."',
			'".(integer)$_POST['groups_smiles_edit']."',
			'".(integer)$_POST['groups_smiles_del']."',
			'".(integer)$_POST['groups_smiles_ul']."',
			'".(integer)$_POST['groups_rss_add']."',
			'".(integer)$_POST['groups_rss_edit']."',
			'".(integer)$_POST['groups_rss_del']."',
			'".(integer)$_POST['groups_forum_add']."',
			'".(integer)$_POST['groups_forum_edit']."',
			'".(integer)$_POST['groups_forum_del']."',
			'".(integer)$_POST['groups_forum_all']."',
			'".(integer)$_POST['groups_reserved_add']."',
			'".(integer)$_POST['groups_reserved_edit']."',
			'".(integer)$_POST['groups_reserved_del']."',
			'".(integer)$_POST['groups_news_add']."',
			'".(integer)$_POST['groups_news_edit']."',
			'".(integer)$_POST['groups_news_del']."',
			'".(integer)$_POST['groups_news_all_add']."',
			'".(integer)$_POST['groups_news_all_edit']."',
			'".(integer)$_POST['groups_news_all_del']."',
			'".(integer)$_POST['groups_news_all_kill_user']."',
			'".(integer)$_POST['groups_auto_add']."',
			'".(integer)$_POST['groups_auto_edit']."',
			'".(integer)$_POST['groups_auto_del']."',
			'".(integer)$_POST['groups_download_add']."',
			'".(integer)$_POST['groups_download_edit']."',
			'".(integer)$_POST['groups_download_del']."',
			'".(integer)$_POST['groups_clan_setup_edit']."',
			'".(integer)$_POST['groups_clan_awards_add']."',
			'".(integer)$_POST['groups_clan_awards_edit']."',
			'".(integer)$_POST['groups_clan_awards_del']."',
			'".(integer)$_POST['groups_clan_games_add']."',
			'".(integer)$_POST['groups_clan_games_edit']."',
			'".(integer)$_POST['groups_clan_games_del']."',
			'".(integer)$_POST['groups_clanwars_add']."',
			'".(integer)$_POST['groups_clanwars_edit']."',
			'".(integer)$_POST['groups_clanwars_del']."',
			'".(integer)$_POST['groups_calendar_add']."',
			'".(integer)$_POST['groups_calendar_edit']."',
			'".(integer)$_POST['groups_calendar_del']."',
			'".(integer)$_POST['groups_compare_add']."',
			'".(integer)$_POST['groups_compare_edit']."',
			'".(integer)$_POST['groups_compare_del']."',
			'".(integer)$_POST['groups_comments_change']."',
			'".(integer)$_POST['groups_guestbook_add']."',
			'".(integer)$_POST['groups_guestbook_edit']."',
			'".(integer)$_POST['groups_guestbook_del']."',
			'".(integer)$_POST['groups_guestbook_del_comm']."',
			'".(integer)$_POST['groups_change_redactor']."',
			'".(integer)$_POST['groups_secret_gb_look']."',
			'".(integer)$_POST['groups_ban_add']."',
			'".(integer)$_POST['groups_ban_edit']."',
			'".(integer)$_POST['groups_ban_del']."',
			'".(integer)$_POST['groups_setup_edit']."',
			'".(integer)$_POST['groups_league_add']."',
			'".(integer)$_POST['groups_league_edit']."',
			'".(integer)$_POST['groups_league_del']."',
			'".(integer)$_POST['groups_league_player_add']."',
			'".(integer)$_POST['groups_league_player_edit']."',
			'".(integer)$_POST['groups_league_player_del']."',
			'".(integer)$_POST['groups_league_team_add']."',
			'".(integer)$_POST['groups_league_team_edit']."',
			'".(integer)$_POST['groups_league_team_del']."',
			'".(integer)$_POST['groups_league_season_add']."',
			'".(integer)$_POST['groups_league_season_edit']."',
			'".(integer)$_POST['groups_league_season_del']."',
			'".(integer)$_POST['groups_league_season_gen']."',
			'".(integer)$_POST['groups_profile_add']."',
			'".(integer)$_POST['groups_profile_edit']."',
			'".(integer)$_POST['groups_profile_del']."',
			'".(integer)$_POST['groups_adds_add']."',
			'".(integer)$_POST['groups_adds_edit']."',
			'".(integer)$_POST['groups_adds_del']."',
			'".(integer)$_POST['groups_adds_view']."',
			'".(integer)$_POST['groups_cups_add']."',
			'".(integer)$_POST['groups_cups_edit']."',
			'".(integer)$_POST['groups_cups_del']."',
			'".(integer)$_POST['groups_shop_add']."',
			'".(integer)$_POST['groups_shop_edit']."',
			'".(integer)$_POST['groups_shop_del']."',
			'".(integer)$_POST['groups_shop_changes']."',
			'".(integer)$_POST['groups_gamesrv_add']."',
			'".(integer)$_POST['groups_gamesrv_edit']."',
			'".(integer)$_POST['groups_gamesrv_del']."',
			'".(integer)$_POST['groups_filter_add']."',
			'".(integer)$_POST['groups_filter_edit']."',
			'".(integer)$_POST['groups_filter_del']."',
			'".(integer)$_POST['groups_shop_setup_view']."',
			'".(integer)$_POST['groups_shop_setup_add']."',
			'".(integer)$_POST['groups_shop_setup_edit']."',
			'".(integer)$_POST['groups_shop_disc_cat_add']."',
			'".(integer)$_POST['groups_shop_disc_cat_edit']."',
			'".(integer)$_POST['groups_shop_disc_cat_del']."',
			'".(integer)$_POST['groups_dictionary_add']."',
			'".(integer)$_POST['groups_dictionary_edit']."',
			'".(integer)$_POST['groups_dictionary_del']."',
			'".(integer)$_POST['groups_todo_add']."',
			'".(integer)$_POST['groups_todo_edit']."',
			'".(integer)$_POST['groups_todo_del']."',
			'".(integer)$_POST['groups_todo_view_all']."',
			'".(integer)$_POST['groups_todo_category_add']."',
			'".(integer)$_POST['groups_todo_category_edit']."',
			'".(integer)$_POST['groups_todo_category_del']."',
			'".(integer)$_POST['groups_poker_add']."',
			'".(integer)$_POST['groups_poker_edit']."',
			'".(integer)$_POST['groups_poker_del']."',
			'".(integer)$_POST['groups_video_add']."',
			'".(integer)$_POST['groups_video_edit']."',
			'".(integer)$_POST['groups_video_del']."',
			'".(integer)$_POST['groups_stream_add']."',
			'".(integer)$_POST['groups_stream_edit']."',
			'".(integer)$_POST['groups_stream_del']."',
			'".(integer)$_POST['groups_tornament_add']."',
			'".(integer)$_POST['groups_tornament_edit']."',
			'".(integer)$_POST['groups_tornament_del']."'
			)") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			ShowMain();
		}
	}
	
	if ($_GET['action'] == "edit"){
		// CHECK PRIVILEGIES
		if (CheckPriv("groups_group_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
		
		$res = mysql_query("UPDATE $db_groups SET groups_name='".mysql_real_escape_string($_POST['groups_name'])."',
		groups_level='".(integer)$groups_level."',
		groups_description='".mysql_real_escape_string($_POST['groups_desc'])."',
		groups_article_add='".(integer)$_POST['groups_article_add']."',
		groups_article_edit='".(integer)$_POST['groups_article_edit']."',
		groups_article_del='".(integer)$_POST['groups_article_del']."',
		groups_article_all_add='".(integer)$_POST['groups_article_all_add']."',
		groups_article_all_edit='".(integer)$_POST['groups_article_all_edit']."',
		groups_article_all_del='".(integer)$_POST['groups_article_all_del']."',
		groups_article_all_kill_user='".(integer)$_POST['groups_article_all_kill_user']."',
		groups_article_changes='".(integer)$_POST['groups_article_changes']."',
		groups_article_ul='".(integer)$_POST['groups_article_ul']."',
		groups_article_channel_add='".(integer)$_POST['groups_article_channel_add']."',
		groups_article_channel_edit='".(integer)$_POST['groups_article_channel_edit']."',
		groups_article_channel_del='".(integer)$_POST['groups_article_channel_del']."',
		groups_admin_add='".(integer)$_POST['groups_admin_add']."',
		groups_admin_edit='".(integer)$_POST['groups_admin_edit']."',
		groups_admin_del='".(integer)$_POST['groups_admin_del']."',
		groups_admin_ban='".(integer)$_POST['groups_admin_ban']."',
		groups_article_public_add='".(integer)$_POST['groups_article_public_add']."',
		groups_article_public_edit='".(integer)$_POST['groups_article_public_edit']."',
		groups_article_public_del='".(integer)$_POST['groups_article_public_del']."',
		groups_article_public_submit='".(integer)$_POST['groups_article_public_submit']."',
		groups_cat_add='".(integer)$_POST['groups_cat_add']."',
		groups_cat_edit='".(integer)$_POST['groups_cat_edit']."',
		groups_cat_del='".(integer)$_POST['groups_cat_del']."',
		groups_cat_ul='".(integer)$_POST['groups_cat_ul']."',
		groups_users_add='".(integer)$_POST['groups_users_add']."',
		groups_users_edit='".(integer)$_POST['groups_users_edit']."',
		groups_users_del='".(integer)$_POST['groups_users_del']."',
		groups_links_add='".(integer)$_POST['groups_links_add']."',
		groups_links_edit='".(integer)$_POST['groups_links_edit']."',
		groups_links_del='".(integer)$_POST['groups_links_del']."',
		groups_links_ul='".(integer)$_POST['groups_links_ul']."',
		groups_wp_add='".(integer)$_POST['groups_wp_add']."',
		groups_wp_edit='".(integer)$_POST['groups_wp_edit']."',
		groups_wp_del='".(integer)$_POST['groups_wp_del']."',
		groups_wp_users_del='".(integer)$_POST['groups_wp_users_del']."',
		groups_group_add='".(integer)$_POST['groups_groups_add']."',
		groups_group_edit='".(integer)$_POST['groups_groups_edit']."',
		groups_group_del='".(integer)$_POST['groups_groups_del']."',
		groups_smiles_add='".(integer)$_POST['groups_smiles_add']."',
		groups_smiles_edit='".(integer)$_POST['groups_smiles_edit']."',
		groups_smiles_del='".(integer)$_POST['groups_smiles_del']."',
		groups_smiles_ul='".(integer)$_POST['groups_smiles_ul']."',
		groups_rss_add='".(integer)$_POST['groups_rss_add']."',
		groups_rss_edit='".(integer)$_POST['groups_rss_edit']."',
		groups_rss_del='".(integer)$_POST['groups_rss_del']."',
		groups_forum_add='".(integer)$_POST['groups_forum_add']."',
		groups_forum_edit='".(integer)$_POST['groups_forum_edit']."',
		groups_forum_del='".(integer)$_POST['groups_forum_del']."',
		groups_forum_all='".(integer)$_POST['groups_forum_all']."',
		groups_reserved_add='".(integer)$_POST['groups_reserved_add']."',
		groups_reserved_edit='".(integer)$_POST['groups_reserved_edit']."',
		groups_reserved_del='".(integer)$_POST['groups_reserved_del']."',
		groups_news_add='".(integer)$_POST['groups_news_add']."',
		groups_news_edit='".(integer)$_POST['groups_news_edit']."',
		groups_news_del='".(integer)$_POST['groups_news_del']."',
		groups_news_all_add='".(integer)$_POST['groups_news_all_add']."',
		groups_news_all_edit='".(integer)$_POST['groups_news_all_edit']."',
		groups_news_all_del='".(integer)$_POST['groups_news_all_del']."',
		groups_news_all_kill_user='".(integer)$_POST['groups_news_all_kill_user']."',
		groups_auto_add='".(integer)$_POST['groups_auto_add']."',
		groups_auto_edit='".(integer)$_POST['groups_auto_edit']."',
		groups_auto_del='".(integer)$_POST['groups_auto_del']."',
		groups_download_add='".(integer)$_POST['groups_download_add']."',
		groups_download_edit='".(integer)$_POST['groups_download_edit']."',
		groups_download_del='".(integer)$_POST['groups_download_del']."',
		groups_clan_setup_edit='".(integer)$_POST['groups_clan_setup_edit']."',
		groups_clan_awards_add='".(integer)$_POST['groups_clan_awards_add']."',
		groups_clan_awards_edit='".(integer)$_POST['groups_clan_awards_edit']."',
		groups_clan_awards_del='".(integer)$_POST['groups_clan_awards_del']."',
		groups_clan_games_add='".(integer)$_POST['groups_clan_games_add']."',
		groups_clan_games_edit='".(integer)$_POST['groups_clan_games_edit']."',
		groups_clan_games_del='".(integer)$_POST['groups_clan_games_del']."',
		groups_clanwars_add='".(integer)$_POST['groups_clanwars_add']."',
		groups_clanwars_edit='".(integer)$_POST['groups_clanwars_edit']."',
		groups_clanwars_del='".(integer)$_POST['groups_clanwars_del']."',
		groups_league_add='".(integer)$_POST['groups_league_add']."',
		groups_league_edit='".(integer)$_POST['groups_league_edit']."',
		groups_league_del='".(integer)$_POST['groups_league_del']."',
		groups_league_player_add='".(integer)$_POST['groups_league_player_add']."',
		groups_league_player_edit='".(integer)$_POST['groups_league_player_edit']."',
		groups_league_player_del='".(integer)$_POST['groups_league_player_del']."',
		groups_league_season_add='".(integer)$_POST['groups_league_season_add']."',
		groups_league_season_edit='".(integer)$_POST['groups_league_season_edit']."',
		groups_league_season_del='".(integer)$_POST['groups_league_season_del']."',
		groups_league_season_gen='".(integer)$_POST['groups_league_season_gen']."',
		groups_league_team_add='".(integer)$_POST['groups_league_team_add']."',
		groups_league_team_edit='".(integer)$_POST['groups_league_team_edit']."',
		groups_league_team_del='".(integer)$_POST['groups_league_team_del']."',
		groups_calendar_add='".(integer)$_POST['groups_calendar_add']."',
		groups_calendar_edit='".(integer)$_POST['groups_calendar_edit']."',
		groups_calendar_del='".(integer)$_POST['groups_calendar_del']."',
		groups_compare_add='".(integer)$_POST['groups_compare_add']."',
		groups_compare_edit='".(integer)$_POST['groups_compare_edit']."',
		groups_compare_del='".(integer)$_POST['groups_compare_del']."',
		groups_comments_change='".(integer)$_POST['groups_comments_change']."',
		groups_guestbook_add='".(integer)$_POST['groups_guestbook_add']."',
		groups_guestbook_edit='".(integer)$_POST['groups_guestbook_edit']."',
		groups_guestbook_del='".(integer)$_POST['groups_guestbook_del']."',
		groups_guestbook_del_comm='".(integer)$_POST['groups_guestbook_del_comm']."',
		groups_change_redactor='".(integer)$_POST['groups_change_redactor']."',
		groups_secret_guestbook_look='".(integer)$_POST['groups_secret_gb_look']."',
		groups_ban_add='".(integer)$_POST['groups_ban_add']."',
		groups_ban_edit='".(integer)$_POST['groups_ban_edit']."',
		groups_ban_del='".(integer)$_POST['groups_ban_del']."',
        groups_setup_edit='".(integer)$_POST['groups_setup_edit']."',
		groups_profile_add='".(integer)$_POST['groups_profile_add']."',
		groups_profile_edit='".(integer)$_POST['groups_profile_edit']."',
		groups_profile_del='".(integer)$_POST['groups_profile_del']."',
		groups_adds_add='".(integer)$_POST['groups_adds_add']."',
		groups_adds_edit='".(integer)$_POST['groups_adds_edit']."',
		groups_adds_del='".(integer)$_POST['groups_adds_del']."',
		groups_adds_view='".(integer)$_POST['groups_adds_view']."',
		groups_cups_add='".(integer)$_POST['groups_cups_add']."',
		groups_cups_edit='".(integer)$_POST['groups_cups_edit']."',
		groups_cups_del='".(integer)$_POST['groups_cups_del']."',
		groups_shop_add='".(integer)$_POST['groups_shop_add']."',
		groups_shop_edit='".(integer)$_POST['groups_shop_edit']."',
		groups_shop_del='".(integer)$_POST['groups_shop_del']."',
		groups_shop_changes='".(integer)$_POST['groups_shop_changes']."',
		groups_gamesrv_add='".(integer)$_POST['groups_gamesrv_add']."',
		groups_gamesrv_edit='".(integer)$_POST['groups_gamesrv_edit']."',
		groups_gamesrv_del='".(integer)$_POST['groups_gamesrv_del']."',
		groups_filter_add='".(integer)$_POST['groups_filter_add']."',
		groups_filter_edit='".(integer)$_POST['groups_filter_edit']."',
		groups_filter_del='".(integer)$_POST['groups_filter_del']."',
		groups_shop_setup_view='".(integer)$_POST['groups_shop_setup_view']."',
		groups_shop_setup_add='".(integer)$_POST['groups_shop_setup_add']."',
		groups_shop_setup_edit='".(integer)$_POST['groups_shop_setup_edit']."',
		groups_shop_disc_cat_add='".(integer)$_POST['groups_shop_disc_cat_add']."',
		groups_shop_disc_cat_edit='".(integer)$_POST['groups_shop_disc_cat_edit']."',
		groups_shop_disc_cat_del='".(integer)$_POST['groups_shop_disc_cat_del']."',
		groups_dictionary_add='".(integer)$_POST['groups_dictionary_add']."',
		groups_dictionary_edit='".(integer)$_POST['groups_dictionary_edit']."',
		groups_dictionary_del='".(integer)$_POST['groups_dictionary_del']."',
		groups_todo_add='".(integer)$_POST['groups_todo_add']."',
		groups_todo_edit='".(integer)$_POST['groups_todo_edit']."',
		groups_todo_del='".(integer)$_POST['groups_todo_del']."',
		groups_todo_category_add='".(integer)$_POST['groups_todo_category_add']."',
		groups_todo_category_edit='".(integer)$_POST['groups_todo_category_edit']."',
		groups_todo_category_del='".(integer)$_POST['groups_todo_category_del']."',
		groups_todo_view_all='".(integer)$_POST['groups_todo_view_all']."',
		groups_poker_add=".(integer)$_POST['groups_poker_add'].",
		groups_poker_edit=".(integer)$_POST['groups_poker_edit'].",
		groups_poker_del=".(integer)$_POST['groups_poker_del'].",
		groups_video_add=".(integer)$_POST['groups_video_add'].",
		groups_video_edit=".(integer)$_POST['groups_video_edit'].",
		groups_video_del=".(integer)$_POST['groups_video_del'].",
		groups_stream_add=".(integer)$_POST['groups_stream_add'].",
		groups_stream_edit=".(integer)$_POST['groups_stream_edit'].",
		groups_stream_del=".(integer)$_POST['groups_stream_del'].",
		groups_tournament_add=".(integer)$_POST['groups_tournament_add'].",
		groups_tournament_edit=".(integer)$_POST['groups_tournament_edit'].",
		groups_tournament_del=".(integer)$_POST['groups_tournament_del']."  
		WHERE groups_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());}
		ShowMain();
	}
	
	if ($_POST['confirm'] <> TRUE){
		$res = mysql_query("SELECT * FROM $db_groups WHERE groups_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><span class=\"nadpis\">"._GROUPS; if ($_GET['action'] == "add"){echo " - "._CMN_ADD;} else {echo " - "._CMN_EDIT;} echo "</span></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td width=\"500\"><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._GROUPS_ADDGROUP."\">&nbsp;&nbsp;<a href=\"sys_groups.php?project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a></td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td>\n";
		echo "			<form action=\"sys_groups.php?action=".$_GET['action']."\" method=\"post\">\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "				<tr>\n";
		echo "					<td valign=\"top\"><strong>"._GROUPS_LEVEL.":</strong></td>\n";
		echo "					<td>\n";
								if (CheckPriv("groups_level") == 99){
									echo "<select name=\"groups_level\">";
									$i = 0;
									while ($i <= 99){
										if ($i<10){$i = "0".$i;}
										echo "<option value=\"".$i."\"";
										if ($i == $ar['groups_level']) {echo " selected";}
										echo ">".$i."</option>\n";
										$i++;
									}
									echo "</select>";
								}
		echo "						</td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td valign=\"top\"><strong>"._GROUPS_NAME.":</strong></td>\n";
		echo "					<td><input type=\"text\" name=\"groups_name\" value=\"".$ar['groups_name']."\" size=\"40\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td valign=\"top\"><strong>"._GROUPS_DESCRIPTION.":</strong></td>\n";
		echo "					<td><textarea name=\"groups_desc\" cols=\"60\" rows=\"4\">".$ar['groups_description']."</textarea></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td colspan=\"2\"><strong>"._GROUPS_SELECT.":</strong></td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "			<table cellspacing=\"2\" cellpadding=\"2\" bgcolor=\"#9999AA\">\n";
		echo "				<tr bgcolor=\"#888899\">\n";
		echo "					<td>"._GROUPS_MODIFY."</td>\n";
		echo "					<td>"._GROUPS_VIEW."</td>\n";
		echo "					<td>"._CMN_ADD."</td>\n";
		echo "					<td>"._CMN_EDIT."</td>\n";
		echo "					<td>"._CMN_DEL."</td>\n";
		echo "					<td>"._CMN_SUBMIT."/"._GROUPS_UPLOAD."</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._NEWSS."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_news_add\" value=\"1\""; if ($ar['groups_news_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_news_edit\" value=\"1\""; if ($ar['groups_news_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_news_del\" value=\"1\""; if ($ar['groups_news_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._NEWS_ALL."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_news_all_add\" value=\"1\""; if ($ar['groups_news_all_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_news_all_edit\" value=\"1\""; if ($ar['groups_news_all_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_news_all_del\" value=\"1\""; if ($ar['groups_news_all_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">"._GROUPS_ACT_KILL_USER."<br><input type=\"checkbox\" name=\"groups_news_all_kill_user\" value=\"1\""; if ($ar['groups_news_all_kill_user'] == 1){echo "checked";} echo "></td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._SETUP_ARTICLES."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_article_add\" value=\"1\""; if ($ar['groups_article_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_article_edit\" value=\"1\""; if ($ar['groups_article_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_article_del\" value=\"1\""; if ($ar['groups_article_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">"._GROUPS_ARTICLES_ZMENY."<br><input type=\"checkbox\" name=\"groups_article_changes\" value=\"1\""; if ($ar['groups_article_changes'] == 1){echo "checked";} echo "></td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._SETUP_ARTICLES_ALL."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_article_all_add\" value=\"1\""; if ($ar['groups_article_all_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_article_all_edit\" value=\"1\""; if ($ar['groups_article_all_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_article_all_del\" value=\"1\""; if ($ar['groups_article_all_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">"._GROUPS_ARTICLES_KILL_USER."<br><input type=\"checkbox\" name=\"groups_article_all_kill_user\" value=\"1\""; if ($ar['groups_article_all_kill_user'] == 1){echo "checked";} echo "></td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._SETUP_ARTICLES_CHANNELS."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_article_channel_add\" value=\"1\""; if ($ar['groups_article_channel_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_article_channel_edit\" value=\"1\""; if ($ar['groups_article_channel_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_article_channel_del\" value=\"1\""; if ($ar['groups_article_channel_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._ADMIN."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_admin_add\" value=\"1\""; if ($ar['groups_admin_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_admin_edit\" value=\"1\""; if ($ar['groups_admin_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_admin_del\" value=\"1\""; if ($ar['groups_admin_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">"._GROUPS_ADMIN_BAN."<br><input type=\"checkbox\" name=\"groups_admin_ban\" value=\"1\""; if ($ar['groups_admin_ban'] == 1){echo "checked";} echo "></td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._ARTICLES_PUBLIC."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_article_public_add\" value=\"1\""; if ($ar['groups_article_public_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_article_public_edit\" value=\"1\""; if ($ar['groups_article_public_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_article_public_del\" value=\"1\""; if ($ar['groups_article_public_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_article_public_submit\" value=\"1\""; if ($ar['groups_article_public_submit'] == 1){echo "checked";} echo "></td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._CATEGORY."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_cat_add\" value=\"1\""; if ($ar['groups_cat_add'] == 1) {echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_cat_edit\" value=\"1\""; if ($ar['groups_cat_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_cat_del\" value=\"1\""; if ($ar['groups_cat_del'] == 1) {echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_cat_ul\" value=\"1\""; if ($ar['groups_cat_ul'] == 1)  {echo "checked";} echo "></td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._FILTER."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_filter_add\" value=\"1\""; if ($ar['groups_filter_add'] == 1) {echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_filter_edit\" value=\"1\""; if ($ar['groups_filter_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_filter_del\" value=\"1\""; if ($ar['groups_filter_del'] == 1) {echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._USERS."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_users_add\" value=\"1\""; if ($ar['groups_users_add'] == 1) {echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_users_edit\" value=\"1\""; if ($ar['groups_users_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_users_del\" value=\"1\""; if ($ar['groups_users_del'] == 1) {echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._AUTO."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_auto_add\" value=\"1\""; if ($ar['groups_auto_add'] == 1) {echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_auto_edit\" value=\"1\""; if ($ar['groups_auto_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_auto_del\" value=\"1\""; if ($ar['groups_auto_del'] == 1) {echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._LINKS."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_links_add\" value=\"1\""; if ($ar['groups_links_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_links_edit\" value=\"1\""; if ($ar['groups_links_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_links_del\" value=\"1\""; if ($ar['groups_links_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_links_ul\" value=\"1\""; if ($ar['groups_links_ul'] == 1){echo "checked";} echo "></td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_ADDS."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_adds_add\" value=\"1\""; if ($ar['groups_adds_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_adds_edit\" value=\"1\""; if ($ar['groups_adds_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_adds_del\" value=\"1\""; if ($ar['groups_adds_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">"._GROUPS_ADDS_VIEW."<br><input type=\"checkbox\" name=\"groups_adds_view\" value=\"1\""; if ($ar['groups_adds_view'] == 1){echo "checked";} echo "></td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._WEEKLYPOLL."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_wp_add\" value=\"1\""; if ($ar['groups_wp_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_wp_edit\" value=\"1\""; if ($ar['groups_wp_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_wp_del\" value=\"1\""; if ($ar['groups_wp_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">"._GROUPS_POLL_DEL_USERS_POLL."<br><input type=\"checkbox\" name=\"groups_wp_users_del\" value=\"1\""; if ($ar['groups_wp_users_del'] == 1){echo "checked";} echo "></td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._BAN_MODUL."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_ban_add\" value=\"1\""; if ($ar['groups_ban_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_ban_edit\" value=\"1\""; if ($ar['groups_ban_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_ban_del\" value=\"1\""; if ($ar['groups_ban_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_groups_add\" value=\"1\""; if ($ar['groups_group_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_groups_edit\" value=\"1\""; if ($ar['groups_group_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_groups_del\" value=\"1\""; if ($ar['groups_group_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_VIDEO."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_video_add\" value=\"1\""; if ($ar['groups_video_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_video_edit\" value=\"1\""; if ($ar['groups_video_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_video_del\" value=\"1\""; if ($ar['groups_video_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._DICTIONARY."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_dictionary_add\" value=\"1\""; if ($ar['groups_dictionary_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_dictionary_edit\" value=\"1\""; if ($ar['groups_dictionary_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_dictionary_del\" value=\"1\""; if ($ar['groups_dictionary_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._SMILES."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_smiles_add\" value=\"1\""; if ($ar['groups_smiles_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_smiles_edit\" value=\"1\""; if ($ar['groups_smiles_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_smiles_del\" value=\"1\""; if ($ar['groups_smiles_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">"._GROUPS_SMILES_UPLOAD."<br><input type=\"checkbox\" name=\"groups_smiles_ul\" value=\"1\""; if ($ar['groups_smiles_ul'] == 1){echo "checked";} echo "></td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._FORUM."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_forum_add\" value=\"1\""; if ($ar['groups_forum_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_forum_edit\" value=\"1\""; if ($ar['groups_forum_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_forum_del\" value=\"1\""; if ($ar['groups_forum_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._RES_WORDS."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_reserved_add\" value=\"1\""; if ($ar['groups_reserved_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_reserved_edit\" value=\"1\""; if ($ar['groups_reserved_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_reserved_del\" value=\"1\""; if ($ar['groups_reserved_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._DOWNLOAD."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_download_add\" value=\"1\""; if ($ar['groups_download_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_download_edit\" value=\"1\""; if ($ar['groups_download_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_download_del\" value=\"1\""; if ($ar['groups_download_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_CLAN_SETUP."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_clan_setup_edit\" value=\"1\""; if ($ar['groups_clan_setup_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_CLAN_AWARDS."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_clan_awards_add\" value=\"1\""; if ($ar['groups_clan_awards_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_clan_awards_edit\" value=\"1\""; if ($ar['groups_clan_awards_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_clan_awards_del\" value=\"1\""; if ($ar['groups_clan_awards_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_CLAN_GAMES."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_clan_games_add\" value=\"1\""; if ($ar['groups_clan_games_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_clan_games_edit\" value=\"1\""; if ($ar['groups_clan_games_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_clan_games_del\" value=\"1\""; if ($ar['groups_clan_games_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_CLANWARS."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_clanwars_add\" value=\"1\""; if ($ar['groups_clanwars_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_clanwars_edit\" value=\"1\""; if ($ar['groups_clanwars_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_clanwars_del\" value=\"1\""; if ($ar['groups_clanwars_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_GAMESRV."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_gamesrv_add\" value=\"1\""; if ($ar['groups_gamesrv_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_gamesrv_edit\" value=\"1\""; if ($ar['groups_gamesrv_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_gamesrv_del\" value=\"1\""; if ($ar['groups_gamesrv_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_LEAGUE."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_league_add\" value=\"1\""; if ($ar['groups_league_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_league_edit\" value=\"1\""; if ($ar['groups_league_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_league_del\" value=\"1\""; if ($ar['groups_league_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_LEAGUE_PLAYERS."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_league_player_add\" value=\"1\""; if ($ar['groups_league_player_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_league_player_edit\" value=\"1\""; if ($ar['groups_league_player_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_league_player_del\" value=\"1\""; if ($ar['groups_league_player_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_LEAGUE_SEASONS."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_league_season_add\" value=\"1\""; if ($ar['groups_league_season_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_league_season_edit\" value=\"1\""; if ($ar['groups_league_season_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_league_season_del\" value=\"1\""; if ($ar['groups_league_season_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">"._GROUPS_LEAGUE_GENERATE."<br><input type=\"checkbox\" name=\"groups_league_season_gen\" value=\"1\""; if ($ar['groups_league_season_gen'] == 1){echo "checked";} echo "></td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_LEAGUE_TEAMS."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_league_team_add\" value=\"1\""; if ($ar['groups_league_team_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_league_team_edit\" value=\"1\""; if ($ar['groups_league_team_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_league_team_del\" value=\"1\""; if ($ar['groups_league_team_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._CUPS."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_cups_add\" value=\"1\""; if ($ar['groups_cups_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_cups_edit\" value=\"1\""; if ($ar['groups_cups_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_cups_del\" value=\"1\""; if ($ar['groups_cups_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._POKER."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_poker_add\" value=\"1\""; if ($ar['groups_poker_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_poker_edit\" value=\"1\""; if ($ar['groups_poker_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_poker_del\" value=\"1\""; if ($ar['groups_poker_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "                   <tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._PROFILE."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_profile_add\" value=\"1\""; if ($ar['groups_profile_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_profile_edit\" value=\"1\""; if ($ar['groups_profile_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_profile_del\" value=\"1\""; if ($ar['groups_profile_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._CALENDAR."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_calendar_add\" value=\"1\""; if ($ar['groups_calendar_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_calendar_edit\" value=\"1\""; if ($ar['groups_calendar_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_calendar_del\" value=\"1\""; if ($ar['groups_calendar_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._COMPARE."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_compare_add\" value=\"1\""; if ($ar['groups_compare_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_compare_edit\" value=\"1\""; if ($ar['groups_compare_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_compare_del\" value=\"1\""; if ($ar['groups_compare_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GUEST_GUESTBOOK."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_guestbook_add\" value=\"1\""; if ($ar['groups_guestbook_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_guestbook_edit\" value=\"1\""; if ($ar['groups_guestbook_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_guestbook_del\" value=\"1\""; if ($ar['groups_guestbook_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">"._GROUPS_GUEST_DEL_COMM."<br><input type=\"checkbox\" name=\"groups_guestbook_del_comm\" value=\"1\""; if ($ar['groups_guestbook_del_comm'] == 1){echo "checked";} echo "></td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_SECRET_GB."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\">"._GROUPS_SECRET_GB_LOOK."<br><input type=\"checkbox\" name=\"groups_secret_gb_look\" value=\"1\""; if ($ar['groups_secret_guestbook_look'] == 1){echo "checked";} echo "></td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_RSS."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_rss_add\"  value=\"1\""; if ($ar['groups_rss_add'] == 1){echo "checked";} echo "></td></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_rss_edit\"  value=\"1\""; if ($ar['groups_rss_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_rss_del\"  value=\"1\""; if ($ar['groups_rss_del'] == 1){echo "checked";} echo "></td></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_CHANGE_REDACTOR."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_change_redactor\"  value=\"1\""; if ($ar['groups_change_redactor'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_ARTICLES_COMM_CHANGE."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_comments_change\"  value=\"1\""; if ($ar['groups_comments_change'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_SETUP_EDIT."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_setup_edit\"  value=\"1\""; if ($ar['groups_setup_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_TODO."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_todo_add\" value=\"1\""; if ($ar['groups_todo_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_todo_edit\" value=\"1\""; if ($ar['groups_todo_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_todo_del\" value=\"1\""; if ($ar['groups_todo_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">"._GROUPS_TODO_VIEW_ALL."<br><input type=\"checkbox\" name=\"groups_todo_view_all\" value=\"1\""; if ($ar['groups_todo_view_all'] == 1){echo "checked";} echo "></td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_TODO_CATEGORIES."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_todo_category_add\" value=\"1\""; if ($ar['groups_todo_category_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_todo_category_edit\" value=\"1\""; if ($ar['groups_todo_category_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_todo_category_del\" value=\"1\""; if ($ar['groups_todo_category_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_STREAMS."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_stream_add\" value=\"1\""; if ($ar['groups_stream_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_stream_edit\" value=\"1\""; if ($ar['groups_stream_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_stream_del\" value=\"1\""; if ($ar['groups_stream_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td>"._GROUPS_TOURNAMENTS."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_tournament_add\" value=\"1\""; if ($ar['groups_tournament_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_tournament_edit\" value=\"1\""; if ($ar['groups_tournament_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_tournament_del\" value=\"1\""; if ($ar['groups_tournament_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td bgcolor=\"#00ffff\">"._SHOP."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_shop_add\" value=\"1\""; if ($ar['groups_shop_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_shop_edit\" value=\"1\""; if ($ar['groups_shop_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_shop_del\" value=\"1\""; if ($ar['groups_shop_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">"._GROUPS_ARTICLES_ZMENY."<br><input type=\"checkbox\" name=\"groups_shop_changes\" value=\"1\""; if ($ar['groups_shop_changes'] == 1){echo "checked";} echo "></td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td bgcolor=\"#00ffff\">"._GROUPS_SHOP_DISC_CAT."</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_shop_disc_cat_add\" value=\"1\""; if ($ar['groups_shop_disc_cat_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_shop_disc_cat_edit\" value=\"1\""; if ($ar['groups_shop_disc_cat_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_shop_disc_cat_del\" value=\"1\""; if ($ar['groups_shop_disc_cat_del'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr bgcolor=\"#FFFFFF\">\n";
		echo "					<td bgcolor=\"#00ffff\">"._GROUPS_SHOP_SETUP."</td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_shop_setup_view\" value=\"1\""; if ($ar['groups_shop_setup_view'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_shop_setup_add\" value=\"1\""; if ($ar['groups_shop_setup_add'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\"><input type=\"checkbox\" name=\"groups_shop_setup_edit\" value=\"1\""; if ($ar['groups_shop_setup_edit'] == 1){echo "checked";} echo "></td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "					<td align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "			<br>\n";
		echo "			<strong>"._GROUPS_ALLOWALLDISK."</strong> <input type=\"checkbox\" name=\"groups_forum_all\"  value=\"1\""; if ($ar['groups_forum_all'] == 1){echo "checked";} echo "><br>\n";
		echo "			<br>\n";
		echo "			<br>\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			<input type=\"submit\" value=\"". _CMN_SUBMIT."\" class=\"eden_button\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
}

/***********************************************************************************************************
*																											
*		MAZANI SKUPIN																						
*																											
***********************************************************************************************************/
function DeleteGroup(){
	
	global $db_groups;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_group_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	if ($_POST['confirm'] == "true") {$res = mysql_query("DELETE FROM $db_groups WHERE groups_id=".(integer)$_POST['id']."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); ShowMain();exit();}
	if ($_POST['confirm'] == "false"){ShowMain();}
	if ($_POST['confirm'] == ""){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" colspan=\"2\"><span class=\"nadpis\">"._GROUPS; echo " - "._CMN_DEL."</span></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td width=\"20\"><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\"></td>\n";
		echo "		<td><a href=\"sys_groups.php?action=add&amp;project=".$_SESSION['project']."\">"._GROUPS_ADDGROUP."</a></td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr class=\"popisky\">\n";
		echo "		<td><span class=\"nadpis-boxy\">"._GROUPS_NAME."</span></td>\n";
		echo "		<td><span class=\"nadpis-boxy\">"._GROUPS_PRIVILEGS."</span></td>\n";
		echo "	</tr><?php \n";
		$res = mysql_query("SELECT * FROM $db_groups WHERE groups_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		while ($ar = mysql_fetch_array($res)){
			echo "<tr>\n";
			echo "	<td valign=\"top\">".$ar['groups_name']."</td>\n";
			echo "	<td valign=\"top\" align=\"right\">".$ar['groups_name']."</td>\n";
			echo "</tr>";
		}
		echo "</table><br><br>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._GROUPS_CHECKDELETE."</span></strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\">\n";
		echo "			<form action=\"sys_groups.php?action=del\" method=\"post\">\n";
		echo "				<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
		echo "				<input type=\"hidden\" name=\"info\" value=\"".$info."\">\n";
		echo "				<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
		echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "				<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			</form>\n";
		echo "			\n";
		echo "		</td>\n";
		echo "		<td valign=\"top\">\n";
		echo "			<form action=\"sys_groups.php?action=del\" method=\"post\">\n";
		echo "				<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
		echo "				<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
		echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "				<input type=\"hidden\" name=\"confirm\" value=\"false\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
}
include ("inc.header.php");
	if ($_GET['action'] == "") { ShowMain();}
	if ($_GET['action'] == "edit") {AddGroup();}
	if ($_GET['action'] == "del") { DeleteGroup();}
	if ($_GET['action'] == "add") { AddGroup();}
include ("inc.footer.php");