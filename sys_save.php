<?php
include_once "./inc.config.php";
if (isset($_POST['project'])) {$_SESSION['project'] = $_POST['project'];} else {$_SESSION['project'] = $_GET['project'];}
if (isset($_GET['lang'])) {$_SESSION['lang'] = $_GET['lang'];}
if (isset($_GET['web_lang'])) {$_SESSION['web_lang'] = $_GET['web_lang'];}
if (!empty($_SESSION['project'])) {
	include_once "./sessions.php";
	include_once "./functions.php";
	include_once "./class/class.mail.php";
	
	if (empty($_SESSION['web_lang'])){
		$res = mysql_query("SELECT language_code FROM $db_language") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$_SESSION['web_lang'] = $ar['language_code'];
	}
	if ($_GET['action'] == "generate_players"){$_POST['confirm'] = "true";}
	if ($_GET['action'] == "shop_seller_act"){$_POST['confirm'] = "true";}
	
	if ($_POST['confirm'] == "true"){
/***********************************************************************************************************
*		sys_setup.php - SYSTEM SETUP
***********************************************************************************************************/
		if ($_GET['action'] == "sys_setup"){
			require "./sys_save/sys_save_setup.php";
		}
/***********************************************************************************************************
*		sys_admin.php - ADMINISTRATORS
***********************************************************************************************************/
		if ($_GET['action'] == "admins_add" || $_GET['action'] == "admins_edit" || $_GET['action'] == "admins_del") {
			require "./sys_save/sys_save_admins.php";
		}
/***********************************************************************************************************
*		sys_admin.php - ADMINISTRATORS CATEGORIES
***********************************************************************************************************/
		if ($_GET['action'] == "admins_cat_add" || $_GET['action'] == "admins_cat_edit" || $_GET['action'] == "admins_cat_del") {
			require "./sys_save/sys_save_admins_cat.php";
		}
/***********************************************************************************************************
*		sys_category.php - CATEGORY - ADD OR EDIT CATEGORIES
***********************************************************************************************************/
		if ($_GET['action'] == "category_add" || $_GET['action'] == "topic_add" || $_GET['action'] == "category_edit" || $_GET['action'] == "topic_edit"){
			require "./sys_save/sys_save_categories.php";
		}
/***********************************************************************************************************
*		UPLOAD EDEN IMAGES (category, smiles, league_awards...)
***********************************************************************************************************/
		if ($_GET['action'] == "eden_img_upload" || $_GET['action'] == "eden_img_del"){
			require "./sys_save/sys_save_eden_images.php";
		}
/***********************************************************************************************************
*		sys_language.php - JAZYKY
***********************************************************************************************************/
		if ($_GET['action'] == "lang_add" || $_GET['action'] == "lang_edit" || $_GET['action'] == "lang_del") {
			require "./sys_save/sys_save_language.php";
		}
/***********************************************************************************************************
*		sys_reserved.php - REZERVOVANE SLOVA
***********************************************************************************************************/
		if ($_GET['action'] == "res_words_add" || $_GET['action'] == "res_words_edit" || $_GET['action'] == "res_words_del") {
			require "./sys_save/sys_save_reserved.php";
		}
/***********************************************************************************************************
*		modul_ads.php - REKLAMA
***********************************************************************************************************/
		if ($_GET['action'] == "adds_add" || $_GET['action'] == "adds_edit") {
			require "./sys_save/sys_save_ads.php";
		}
/***********************************************************************************************************
*		modul_articles.php - ARTICLES
***********************************************************************************************************/
		if ($_GET['action'] == "article_add" || $_GET['action'] == "article_edit" || $_GET['action'] == "article_del") {
			require "./sys_save/sys_save_article.php";
		}
/***********************************************************************************************************
*		modul_ban.php - BAN
***********************************************************************************************************/
		if ($_GET['action'] == "ban_add" || $_GET['action'] == "ban_edit" || $_GET['action'] == "ban_del") {
			require "./sys_save/sys_save_ban.php";
		}
/***********************************************************************************************************
*		modul_clan_awards.php - AWARDS
***********************************************************************************************************/
		if ($_GET['action'] == "clan_awards_add" || $_GET['action'] == "clan_awards_edit" || $_GET['action'] == "clan_awards_del"){
			require "./sys_save/sys_save_clan_awards.php";
		}
/***********************************************************************************************************
*		modul_clan_clanwars.php - CLANWARS
***********************************************************************************************************/
		if ($_GET['action'] == "clanwar_add" || $_GET['action'] == "clanwar_edit" || $_GET['action'] == "clanwar_del"){
			require "./sys_save/sys_save_clan_clanwars.php";
		}
/***********************************************************************************************************
*		modul_clan_games.php - GAMES
***********************************************************************************************************/
		if ($_GET['action'] == "clan_game_add" || $_GET['action'] == "clan_game_edit" || $_GET['action'] == "clan_game_del"){
			require "./sys_save/sys_save_clan_games.php";
		}
/***********************************************************************************************************
*		modul_clan_games_main.php - GAMES MAIN
***********************************************************************************************************/
		if ($_GET['action'] == "clan_game_main_add" || $_GET['action'] == "clan_game_main_edit" || $_GET['action'] == "clan_game_main_del"){
			require "./sys_save/sys_save_clan_games_main.php";
		}
/***********************************************************************************************************
*		modul_clan_gametype.php - GAMETYPE
***********************************************************************************************************/
		if ($_GET['action'] == "gametype_add" || $_GET['action'] == "gametype_edit" || $_GET['action'] == "gametype_del"){
			require "./sys_save/sys_save_clan_gametype.php";
		}
/***********************************************************************************************************
*		modul_clan_maps.php - CLAN MAPS
***********************************************************************************************************/
		if ($_GET['action'] == "clan_map_add" || $_GET['action'] == "clan_map_edit" || $_GET['action'] == "clan_map_del"){
			require "./sys_save/sys_save_clan_maps.php";
		}
/***********************************************************************************************************
*		modul_clan_setup.php - CLAN SETUP
***********************************************************************************************************/
		if ($_GET['action'] == "clan_setup"){
			require "./sys_save/sys_save_clan_setup.php";
		}
/***********************************************************************************************************
*		modul_compare.php - COMPARE CATEGORIES
***********************************************************************************************************/
		if ($_GET['action'] == "compare_cat_add" || $_GET['action'] == "compare_cat_edit" || $_GET['action'] == "compare_cat_del"){
			require "./sys_save/sys_save_compare_cat.php";
		}
/***********************************************************************************************************
*		modul_compare.php - COMPARE MAKERS
***********************************************************************************************************/
		if ($_GET['action'] == "compare_maker_add" || $_GET['action'] == "compare_maker_edit" || $_GET['action'] == "compare_maker_del"){
			require "./sys_save/sys_save_compare_makers.php";
		}
/***********************************************************************************************************
*		modul_compare.php - COMPARE PARTS
***********************************************************************************************************/
		if ($_GET['action'] == "compare_part_add" || $_GET['action'] == "compare_part_edit" || $_GET['action'] == "compare_cat_del"){
			require "./sys_save/sys_save_compare_parts.php";
		}
/***********************************************************************************************************
*		modul_compare.php - COMPARE NTB
***********************************************************************************************************/
		if ($_GET['action'] == "compare_ntb_add" || $_GET['action'] == "compare_ntb_edit" || $_GET['action'] == "compare_ntb_del"){
			require "./sys_save/sys_save_compare_ntb.php";
		}
/***********************************************************************************************************
*		modul_download.php - DOWNLOAD
***********************************************************************************************************/
		if ($_GET['action'] == "dl_add" || $_GET['action'] == "dl_edit") {
			require "./sys_save/sys_save_downloads.php";
		}
/***********************************************************************************************************
*		modul_filter.php - FILTER - ADD, EDIT OR DELETE FILTER
***********************************************************************************************************/
		if ($_GET['action'] == "filter_add" || $_GET['action'] == "filter_edit" || $_GET['action'] == "filter_del"){
			require "./sys_save/sys_save_filters.php";
		}
/***********************************************************************************************************
*		modul_dictionary.php - DICTIONARY - ADD, EDIT OR DELETE
***********************************************************************************************************/
		if ($_GET['action'] == "add_word" || $_GET['action'] == "edit_word" || $_GET['action'] == "del_word"){
			require "./sys_save/sys_save_dictionary.php";
		}
/***********************************************************************************************************
*		modul_league.php - LEAGUE - ADD, EDIT OR DELETE and LEAGUE SEASON - ADD, EDIT OR DELETE ...
***********************************************************************************************************/
		if ($_GET['action'] == "league_award_add" || $_GET['action'] == "league_award_edit" || $_GET['action'] == "league_award_" || $_GET['action'] == "league_add" 
			|| $_GET['action'] == "league_edit" || $_GET['action'] == "league_del" || $_GET['action'] == "league_season_add" || $_GET['action'] == "league_season_edit" 
			|| $_GET['action'] == "league_season_del" || $_GET['action'] == "league_season_rounds_edit" || $_GET['action'] == "league_team_add" || $_GET['action'] == "league_team_edit" 
			|| $_GET['action'] == "league_team_del" || $_GET['action'] == "guid_add" || $_GET['action'] == "guid_edit" || $_GET['action'] == "generate_players" 
			|| $_GET['action'] == "results_add" || $_GET['action'] == "league_player_del" || $_GET['action'] == "league_player_ban_add" || $_GET['action'] == "league_player_ban_edit" 
			|| $_GET['action'] == "league_awards_give_to_players" || $_GET['action'] == "league_awards_give_to_teams"){
			require "./sys_save/sys_save_league.php";
		}
/***********************************************************************************************************
*		modul_league.php - LEAGUE - SAVE PLAYERS ALLOWED TO PLAY LEAGUE
***********************************************************************************************************/
		if ($_GET['action'] == "league_generate"){
			require "./sys_save/sys_league.php";
		}
/***********************************************************************************************************
*		modul_links.php - ODKAZY
***********************************************************************************************************/
		if ($_GET['action'] == "links_add" || $_GET['action'] == "links_edit" || $_GET['action'] == "links_del") {
			require "./sys_save/sys_save_links.php";
		}
/***********************************************************************************************************
*		modul_articles_channel.php - CHANNELS - ADD OR EDIT CHANNELS
***********************************************************************************************************/
		if ($_GET['action'] == "article_channel_add" || $_GET['action'] == "article_channel_edit" || $_GET['action'] == "article_channel_del"){
			require "./sys_save/sys_save_channels.php";
		}
/***********************************************************************************************************
*		modul_articles_channel.php - CHANNELS - UPLOAD CHANNEL IMAGE
***********************************************************************************************************/
		if ($_GET['action'] == "article_channel_upload_img"){
			require "./sys_save/sys_save_channels_images.php";
		}
/***********************************************************************************************************
*		modul_poker.php - POKER CARDROOM - ADD, EDIT OR DELETE
***********************************************************************************************************/
		if ($_GET['action'] == "add_cardroom" || $_GET['action'] == "edit_cardroom" || $_GET['action'] == "del_cardroom"){
			require "./sys_save/sys_save_poker_cardrooms.php";
		}
/***********************************************************************************************************
*		modul_poker.php - POKER VARIANTS - ADD, EDIT OR DELETE
***********************************************************************************************************/
		if ($_GET['action'] == "add_variant" || $_GET['action'] == "edit_variant" || $_GET['action'] == "del_variant"){
			require "./sys_save/sys_save_poker_variants.php";
		}
/***********************************************************************************************************
*		modul_poll.php - ANKETY - ADD, EDIT OR DELETE
***********************************************************************************************************/
		if ($_GET['action'] == "poll_add" || $_GET['action'] == "poll_edit" || $_GET['action'] == "poll_del" || $_GET['action'] == "poll_del_data"){
			require "./sys_save/sys_save_poll.php";
		}
/***********************************************************************************************************
*		modul_profile.php - PROFILY - ADD, EDIT OR DELETE
***********************************************************************************************************/
		if ($_GET['action'] == "add_profile" || $_GET['action'] == "edit_profile" || $_GET['action'] == "del_profile"){
			require "./sys_save/sys_save_profiles.php";
		}
/***********************************************************************************************************
*		modul_rss.php - CHANNELS - ADD OR EDIT CHANNELS
***********************************************************************************************************/
		if ($_GET['action'] == "rss_add" || $_GET['action'] == "rss_edit" || $_GET['action'] == "rss_del"){
			require "./sys_save/sys_save_rss.php";
		}
/***********************************************************************************************************
*		modul_rss_itunes.php - CHANNELS - ADD OR EDIT CHANNELS
***********************************************************************************************************/
		if ($_GET['action'] == "add_rss_ch" || $_GET['action'] == "edit_rss_ch"){
			require "./sys_save/sys_save_rss_channels.php";
		}
/***********************************************************************************************************
*		modul_rss_itunes.php - ITEMS - ADD OR EDIT ITEM	(podcast)
***********************************************************************************************************/
		if ($_GET['action'] == "add_rss_i" || $_GET['action'] == "edit_rss_i"){
			require "./sys_save/sys_save_rss_items.php";
		}
/***********************************************************************************************************
*		modul_shop.php - MANUFACTURERS
***********************************************************************************************************/
		if ($_GET['action'] == "add_man" || $_GET['action'] == "edit_man" || $_GET['action'] == "del_man"){
			require "./sys_save/sys_save_shop_man.php";
		}
/***********************************************************************************************************
*		modul_shop.php - PRODUKTY
***********************************************************************************************************/
		if ($_GET['action'] == "add_prod" || $_GET['action'] == "edit_prod" || $_GET['action'] == "del_prod"){
			require "./sys_save/sys_save_shop_products.php";
		}
/***********************************************************************************************************
*		modul_shop.php - CLOTHES COLORS
***********************************************************************************************************/
		if ($_GET['action'] == "clothes_add_color" || $_GET['action'] == "clothes_edit_color" || $_GET['action'] == "clothes_del_color"){
			require "./sys_save/sys_save_clothes_colors.php";
		}
/***********************************************************************************************************
*		modul_shop.php - CLOTHES - VELIKOSTI
***********************************************************************************************************/
		if ($_GET['action'] == "clothes_add_size" || $_GET['action'] == "clothes_edit_size" || $_GET['action'] == "clothes_del_size"){
			require "./sys_save/sys_save_clothes_sizes.php";
		}
/***********************************************************************************************************
*		modul_shop.php - CLOTHES - STYLY
***********************************************************************************************************/
		if ($_GET['action'] == "clothes_add_style" || $_GET['action'] == "clothes_edit_style" || $_GET['action'] == "clothes_del_style"){
			require "./sys_save/sys_save_clothes_styles.php";
		}
/***********************************************************************************************************
*		modul_shop.php - CLOTHES - STYLY PARENTS
***********************************************************************************************************/
		if ($_GET['action'] == "clothes_add_style_parents" || $_GET['action'] == "clothes_edit_style_parents" || $_GET['action'] == "clothes_del_style_parents"){
			require "./sys_save/sys_save_clothes_styles_parents.php";
		}
/***********************************************************************************************************
*		modul_shop.php - CLOTHES - DESIGN
***********************************************************************************************************/
		if ($_GET['action'] == "clothes_add_design" || $_GET['action'] == "clothes_edit_design" || $_GET['action'] == "clothes_del_design"){
			require "./sys_save/sys_save_clothes_designes.php";
		}
/***********************************************************************************************************
*		modul_shop.php - CLOTHES - GENERATE CLOTHES PRODDUCTS
***********************************************************************************************************/
		if ($_GET['action'] == "generate_products"){
			require "./sys_save/sys_save_clothes_generate_products.php";
		}
/***********************************************************************************************************
*		modul_shop_setup.php - CURRENCY
***********************************************************************************************************/
		if ($_GET['action'] == "shop_currency_add" || $_GET['action'] == "shop_currency_edit" || $_GET['action'] == "shop_currency_del"){
			require "./sys_save/sys_save_shop_currency.php";
		}
/***********************************************************************************************************
*		modul_shop_sellers.php - SHOP - DISCOUNT
***********************************************************************************************************/
		if ($_GET['action'] == "discount_cat_add" || $_GET['action'] == "discount_cat_edit" || $_GET['action'] == "discount_cat_del" || $_GET['action'] == "discount_cat_add_sub" || $_GET['action'] == "discount_cat_edit_sub" || $_GET['action'] == "discount_cat_del_sub"){
			require "./sys_save/sys_save_shop_discount_cat.php";
		}
/***********************************************************************************************************
*		modul_shop_sellers.php - SHOP - SELLERS
***********************************************************************************************************/
		if ($_GET['action'] == "shop_seller_act" || $_GET['action'] == "shop_seller_edit"){
			require "./sys_save/sys_save_shop_sellers.php";
		}
/***********************************************************************************************************
*		modul_smiles.php - SMILES - ADD, EDIT OR DELETE
***********************************************************************************************************/
		if ($_GET['action'] == "add_smiles" || $_GET['action'] == "edit_smiles" || $_GET['action'] == "del_smiles"){
			require "./sys_save/sys_save_smiles.php";
		}
/***********************************************************************************************************
*		modul_streams.php - STREAMS - ADD, EDIT OR DELETE
***********************************************************************************************************/
		if ($_GET['action'] == "stream_add" || $_GET['action'] == "stream_edit" || $_GET['action'] == "stream_del"){
			require "./sys_save/sys_save_streams.php";
		}
/***********************************************************************************************************
*		modul_tags.php - TAGS - ADD, EDIT OR DELETE
***********************************************************************************************************/
		if ($_GET['action'] == "tag_add" || $_GET['action'] == "tag_edit" || $_GET['action'] == "tag_del"){
			require "./sys_save/sys_save_tags.php";
		}
/***********************************************************************************************************
*		modul_news.php - NEWS - ADD, EDIT OR DELETE
***********************************************************************************************************/
		if ($_GET['action'] == "news_add" || $_GET['action'] == "news_edit" || $_GET['action'] == "news_del"){
			require "./sys_save/sys_save_news.php";
		}
/***********************************************************************************************************
*		modul_todo.php - TODO - ADD, EDIT OR DELETE and TODO CATEGORY - ADD, EDIT OR DELETE
***********************************************************************************************************/
		if ($_GET['action'] == "add_todo" || $_GET['action'] == "edit_todo" || $_GET['action'] == "del_todo" || $_GET['action'] == "add_todo_category" || $_GET['action'] == "edit_todo_category" || $_GET['action'] == "del_todo_category"){
			require "./sys_save/sys_save_todo.php";
		}
/***********************************************************************************************************
*		modul_videos.php - VIDEOS - ADD, EDIT OR DELETE 
***********************************************************************************************************/
		if ($_GET['action'] == "video_add" || $_GET['action'] == "video_edit" || $_GET['action'] == "video_del"){
			require "./sys_save/sys_save_videos.php";
		}
	} /* $_POST['confirm'] == "true" */
} /* !empty($_SESSION['project']*/