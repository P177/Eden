<?php
/* Provereni opravneni */
if (CheckPriv("groups_setup_edit") <> 1) { header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']."&msg=nep");exit;}

if ($eden_doba_pripojeni < 60){$eden_doba_pripojeni = 60;} // Znemozneni nastaveni doby pro pristup mensi nez 1 minutu
if ($disk_doba_pripojeni < 60){$disk_doba_pripojeni = 60;} // Znemozneni nastaveni doby pro pristup mensi nez 1 minutu
$setup_basic_date = PrepareDateForSpiffyCalendar($_POST['setup_basic_date'],"00:00:01");
/* Nahrani obrazku pop-up okna */
if ($_FILES['setup_accessories_popup_logo']['name'] != ""){
	/* Spojeni s FTP serverem */
	$conn_id = ftp_connect($eden_cfg['ftp_server']); 
	/* Prihlaseni pres uzivatelske jmeno a heslo na FTP server */
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); 
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	/* Zjisteni stavu spojeni */
	if ((!$conn_id) || (!$login_result)){
		$msg = "no_ftp";
	}
	
	if (($popup_logo = getimagesize($_FILES['setup_accessories_popup_logo']['tmp_name'])) != false){
		/* Zjistime zda neni soubor jineho typu nez je povoleno a pokud ne priradime extenzi */
		if ($popup_logo[2] == 2){
			$extenze = ".jpg";
		} elseif ($popup_logo[2] == 3){
			$extenze = ".png";
		} else {
			$msg = "wft";
		}
		/* Zjistime zda neni obrazek mensi, nez je povoleno */
		if ($popup_logo[0] < 440 /*width*/ || $popup_logo[1] < 90 /*height*/){
			$msg = "ts";
		/* Zjistime zda neni obrazek vetsi, nez je povoleno */
		} elseif ($popup_logo[0] > 440 /*width*/ || $popup_logo[1] > 90 /*height*/){
			$msg = "tb";
		} else {
			/* Zjisti nazev souboru po ulozeni do docasneho adresare na serveru */
			$source_file = $_FILES['setup_accessories_popup_logo']['tmp_name'];
			$userfile_name = "eden_popup_window_logo".strtolower($extenze);
			/* Vlozi nazev souboru a cestu do konkretniho adresare */
			$destination_file = $ftp_path_project_images.$userfile_name;
			$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
			
			/* Zjisteni stavu uploadu */
			if (!$upload){ 
				$msg = "ue";
			} else {
				/* Nahrani nazvu obrazku do databaze */
				mysql_query("UPDATE $db_setup SET setup_accessories_popup_logo='".mysql_real_escape_string($userfile_name)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				
				/* Pokud vse probehne jak ma znicime dane promenne a nastavime $checkupload */
				unset($source_file);
				unset($destination_file);
				unset($extenze);
				unset($popup_logo);
				unset($_FILES['setup_accessories_popup_logo']);
			}
		}
	} else {
		$msg = "fni";
	}
	/* Uzavreni komunikace se serverem */
	ftp_close($conn_id);
}

$setup_update = mysql_query("UPDATE $db_setup SET
setup_basic_lang='".mysql_real_escape_string($_POST['setup_basic_lang'])."',
setup_basic_country='".mysql_real_escape_string($_POST['setup_basic_country'])."', 
setup_basic_timezone='".mysql_real_escape_string($_POST['setup_basic_timezone'])."',
setup_basic_date='".mysql_real_escape_string($setup_basic_date)."', 
setup_basic_doctype='".mysql_real_escape_string($_POST['setup_basic_doctype'])."', 
setup_eden_joining_time=".(integer)$_POST['setup_eden_joining_time'].", 
setup_eden_editor_style=2, 
setup_eden_editor_cleaner=".(integer)$_POST['setup_eden_editor_cleaner'].", 
setup_eden_editor_purificator=".(integer)$_POST['setup_eden_editor_purificator'].", 
setup_forum_posts_on_page=".(integer)$_POST['setup_forum_posts_on_page'].", 
setup_forum_topics_on_page=".(integer)$_POST['setup_forum_topics_on_page'].", 
setup_forum_anonym=".(integer)$_POST['setup_forum_anonym'].", 
setup_forum_open=".(integer)$_POST['setup_forum_open'].", 
setup_forum_allow_quote=".(integer)$_POST['setup_forum_allow_quote'].",
setup_forum_allow_edit=".(integer)$_POST['setup_forum_allow_edit'].",
setup_forum_allow_del=".(integer)$_POST['setup_forum_allow_del'].",
setup_forum_allow_sig=".(integer)$_POST['setup_forum_allow_sig'].",
setup_forum_joining_time=".(integer)$_POST['setup_forum_joining_time'].", 
setup_poll_iptime=".(integer)$_POST['setup_poll_iptime'].", 
setup_poll_show_results=".(integer)$_POST['setup_poll_show_results'].", 
setup_poll_results_as_number=".(integer)$_POST['setup_poll_results_as_number'].", 
setup_article_number=".(integer)$_POST['setup_article_number'].", 
setup_article_number_2=".(integer)$_POST['setup_article_number_2'].", 
setup_article_archive=".(integer)$_POST['setup_article_archive'].", 
setup_article_archivation_start=".(integer)$_POST['setup_article_archivation_start'].", 
setup_article_comments=".(integer)$_POST['setup_article_comments'].",
setup_article_show_best_article=".(integer)$_POST['setup_article_show_best_article'].",
setup_guestbook_comments=".(integer)$_POST['setup_guestbook_comments'].", 
setup_comm_flag=".(integer)$_POST['setup_comm_flag'].", 
setup_comm_adm_img=".(integer)$_POST['setup_comm_adm_img'].", 
setup_comm_link=".(integer)$_POST['setup_comm_link'].", 
setup_comm_anonym=".(integer)$_POST['setup_comm_anonym'].", 
setup_comm_autofill=".(integer)$_POST['setup_comm_autofill'].", 
setup_comm_thumbs=".(integer)$_POST['setup_comm_thumbs'].", 
setup_comm_thumbs_plus_1=".(integer)$_POST['setup_comm_thumbs_plus_1'].", 
setup_comm_thumbs_plus_2=".(integer)$_POST['setup_comm_thumbs_plus_2'].", 
setup_comm_thumbs_plus_3=".(integer)$_POST['setup_comm_thumbs_plus_3'].", 
setup_comm_thumbs_plus_4=".(integer)$_POST['setup_comm_thumbs_plus_4'].", 
setup_comm_thumbs_minus_1=".(integer)$_POST['setup_comm_thumbs_minus_1'].", 
setup_comm_thumbs_minus_2=".(integer)$_POST['setup_comm_thumbs_minus_2'].", 
setup_comm_thumbs_minus_3=".(integer)$_POST['setup_comm_thumbs_minus_3'].", 
setup_comm_thumbs_minus_4=".(integer)$_POST['setup_comm_thumbs_minus_4'].", 
setup_ban_color='".mysql_real_escape_string($_POST['setup_ban_color'])."', 
setup_top_article_archive=".(integer)$_POST['setup_top_article_archive'].", 
setup_top_article_mod=".(integer)$_POST['setup_top_article_mod'].", 
setup_news_number=".(integer)$_POST['setup_news_number'].", 
setup_news_archive=".(integer)$_POST['setup_news_archive'].", 
setup_news_archivation_start=".(integer)$_POST['setup_news_archivation_start'].", 
setup_reg_admin_nick=".(integer)$_POST['setup_reg_admin_nick'].", 
setup_reg_over_email=".(integer)$_POST['setup_reg_over_email'].", 
setup_reg_mailer='".mysql_real_escape_string($_POST['setup_reg_mailer'])."', 
setup_reg_from='".mysql_real_escape_string($_POST['setup_reg_from'])."', 
setup_reg_from_name='".mysql_real_escape_string($_POST['setup_reg_from_name'])."', 
setup_reg_agreement=".(integer)$_POST['setup_reg_agreement'].", 
setup_adds_in_com=".(integer)$_POST['setup_adds_in_com'].", 
setup_adds_in_com_id=".(integer)$_POST['setup_adds_in_com_id'].", 
setup_admin_show_contact=".(integer)$_POST['setup_admin_show_contact'].", 
setup_admin_show_contact_shop=".(integer)$_POST['setup_admin_show_contact_shop'].", 
setup_admin_show_clan=".(integer)$_POST['setup_admin_show_clan'].", 
setup_admin_show_game=".(integer)$_POST['setup_admin_show_game'].", 
setup_admin_show_hw=".(integer)$_POST['setup_admin_show_hw'].", 
setup_admin_show_info=".(integer)$_POST['setup_admin_show_info'].",
setup_admin_show_poker=".(integer)$_POST['setup_admin_show_poker'].",
setup_show_author_nick=".(integer)$_POST['setup_show_author_nick'].",
setup_contact_form_from='".mysql_real_escape_string($_POST['setup_contact_form_from'])."',
setup_league_email='".mysql_real_escape_string($_POST['setup_league_email'])."',
setup_league_email_from_name='".mysql_real_escape_string($_POST['setup_league_email_from_name'])."',
setup_league_check_24=".(integer)$_POST['setup_league_check_24']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
if ($setup_update){ 
	$msg = "setup_update_ok";
} else {
	$msg = "setup_update_er";
}
/***************************************************************************
*
*		SETUP IMAGES
*
***************************************************************************/
$eden_setup_image_for[0] = "avatar";
$eden_setup_image_width[0] = (integer)$_POST['setup_forum_avatar_max_width'];
$eden_setup_image_height[0] = (integer)$_POST['setup_forum_avatar_max_height'];
$eden_setup_image_filesize[0] = (integer)$_POST['setup_forum_avatar_max_filesize'];
$eden_setup_image_for[1] = "article_1";
$eden_setup_image_width[1] = (integer)$_POST['setup_article_img_1_width'];
$eden_setup_image_height[1] = (integer)$_POST['setup_article_img_1_height'];
$eden_setup_image_for[2] = "article_2";
$eden_setup_image_width[2] = (integer)$_POST['setup_article_img_2_width'];
$eden_setup_image_height[2] = (integer)$_POST['setup_article_img_2_height'];
$eden_setup_image_for[3] = "link_1";
$eden_setup_image_width[3] = (integer)$_POST['setup_links_img1_width'];
$eden_setup_image_height[3] = (integer)$_POST['setup_links_img1_height'];
$eden_setup_image_filesize[3] = (integer)$_POST['setup_links_img1_size'];
$eden_setup_image_for[4] = "link_2";
$eden_setup_image_width[4] = (integer)$_POST['setup_links_img2_width'];
$eden_setup_image_height[4] = (integer)$_POST['setup_links_img2_height'];
$eden_setup_image_filesize[4] = (integer)$_POST['setup_links_img2_size'];
$eden_setup_image_for[5] = "profile_1";
$eden_setup_image_width[5] = (integer)$_POST['setup_profile_img_1_width'];
$eden_setup_image_height[5] = (integer)$_POST['setup_profile_img_1_height'];
$eden_setup_image_filesize[5] = (integer)$_POST['setup_profile_img_1_size'];
$eden_setup_image_for[6] = "todo";
$eden_setup_image_width[6] = (integer)$_POST['setup_todo_img_width'];
$eden_setup_image_height[6] =(integer)$_POST['setup_todo_img_height'];
$eden_setup_image_filesize[6] = (integer)$_POST['setup_todo_img_size'];
$eden_setup_image_for[7] = "league_team_logo";
$eden_setup_image_width[7] = (integer)$_POST['setup_league_team_logo_width'];
$eden_setup_image_height[7] = (integer)$_POST['setup_league_team_logo_height'];
$eden_setup_image_filesize[7] = (integer)$_POST['setup_league_team_logo_size'];
$eden_setup_image_for[8] = "category";
$eden_setup_image_width[8] = (integer)$_POST['setup_cat_img_width'];
$eden_setup_image_height[8] = (integer)$_POST['setup_cat_img_height'];
$eden_setup_image_filesize[8] = (integer)$_POST['setup_cat_img_size'];
$eden_setup_image_for[9] = "league_award";
$eden_setup_image_width[9] = (integer)$_POST['setup_league_award_width'];
$eden_setup_image_height[9] = (integer)$_POST['setup_league_award_height'];
$eden_setup_image_filesize[9] = (integer)$_POST['setup_league_award_size'];
$eden_setup_image_for[10] = "smiles";
$eden_setup_image_width[10] = (integer)$_POST['setup_smiles_img_width'];
$eden_setup_image_height[10] = (integer)$_POST['setup_smiles_img_height'];
$eden_setup_image_filesize[10] = (integer)$_POST['setup_smiles_img_size'];
$eden_setup_image_for[11] = "games_1";
$eden_setup_image_width[11] = (integer)$_POST['setup_games_img_1_width'];
$eden_setup_image_height[11] = (integer)$_POST['setup_games_img_1_height'];
$eden_setup_image_filesize[11] = (integer)$_POST['setup_games_img_1_size'];
$eden_setup_image_for[12] = "games_2";
$eden_setup_image_width[12] = (integer)$_POST['setup_games_img_2_width'];
$eden_setup_image_height[12] = (integer)$_POST['setup_games_img_2_height'];
$eden_setup_image_filesize[12] = (integer)$_POST['setup_games_img_2_size'];
$eden_setup_image_for[13] = "rss";
$eden_setup_image_width[13] = (integer)$_POST['setup_rss_img_width'];
$eden_setup_image_height[13] = (integer)$_POST['setup_rss_img_height'];
$eden_setup_image_filesize[13] = (integer)$_POST['setup_rss_img_size'];
$i=0;
while ($i < count($eden_setup_image_for)){
	$res_setup_img = mysql_query("SELECT eden_setup_image_for FROM $db_setup_images WHERE eden_setup_image_for='".$eden_setup_image_for[$i]."'");
	if ($ar_setup_img = mysql_fetch_array($res_setup_img)){
		/* Tady je vsechno v poradku pokracuje se dale */
	} else {
		mysql_query("INSERT INTO $db_setup_images  VALUES (
		'',
		'".$eden_setup_image_for[$i]."',
		'".$eden_setup_image_width[$i]."',
		'".$eden_setup_image_height[$i]."',
		'".$eden_setup_image_filesize[$i]."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	
	if (!isset($eden_setup_image_width[$i])){$eden_setup_image_width[$i] = 0;}
	if (!isset($eden_setup_image_height[$i])){$eden_setup_image_height[$i] = 0;}
	if (!isset($eden_setup_image_filesize[$i])){$eden_setup_image_filesize[$i] = 0;}
	
	$setup_img_update = mysql_query("UPDATE $db_setup_images SET
	eden_setup_image_width=".$eden_setup_image_width[$i].",
	eden_setup_image_height=".$eden_setup_image_height[$i].",
	eden_setup_image_filesize=".$eden_setup_image_filesize[$i]." WHERE eden_setup_image_for='".$eden_setup_image_for[$i]."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	$i++;
}

/***************************************************************************
*
*		SETUP LANG
*
***************************************************************************/
$res_setup_lang = mysql_query("SELECT * FROM $db_setup_lang WHERE setup_lang='".$_SESSION['web_lang']."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar_setup_lang  = mysql_fetch_array($res_setup_lang);

if ($_POST['setup_lang_ban_text_checkbox'] == 1){$setup_lang_ban_text = PrepareForDB($_POST['setup_lang_ban_text'],1,"",1);} else {$setup_lang_ban_text = $ar_setup_lang['setup_lang_ban_text'];}
if ($_POST['setup_lang_reg_help_checkbox'] == 1){$setup_lang_reg_help = PrepareForDB($_POST['setup_lang_reg_help'],1,"<a>,<br>,<br />,<strong>",1);} else {$setup_lang_reg_help = $ar_setup_lang['setup_lang_reg_help'];}
if ($_POST['setup_lang_reg_text_checkbox'] == 1){$setup_lang_reg_text = PrepareForDB($_POST['setup_lang_reg_text'],1,"",1);} else {$setup_lang_reg_text = $ar_setup_lang['setup_lang_reg_text'];}
if ($_POST['setup_lang_reg_terms_checkbox'] == 1){$setup_lang_reg_terms = PrepareForDB($_POST['setup_lang_reg_terms'],1,"",1);} else {$setup_lang_reg_terms = $ar_setup_lang['setup_lang_reg_terms'];}
if ($_POST['setup_lang_comments_rules_checkbox'] == 1){$setup_lang_comments_rules = PrepareForDB($_POST['setup_lang_comments_rules'],1,"<a>,<br>,<br />,<strong>,<script>",1);} else {$setup_lang_comments_rules = $ar_setup_lang['setup_lang_comments_rules'];}
if ($_POST['setup_lang_change_email_checkbox'] == 1){$setup_lang_change_email = PrepareForDB($_POST['setup_lang_change_email'],1,"<a>,<br>,<br />,<strong>",1);} else {$setup_lang_change_email = $ar_setup_lang['setup_lang_change_email'];}
if ($_POST['setup_lang_changed_email_checkbox'] == 1){$setup_lang_changed_email = PrepareForDB($_POST['setup_lang_changed_email'],1,"<a>,<br>,<br />,<strong>",1);} else {$setup_lang_changed_email = $ar_setup_lang['setup_lang_changed_email'];}
if ($_POST['setup_lang_forgpass_text_checkbox'] == 1){$setup_lang_forgpass_text = PrepareForDB($_POST['setup_lang_forgpass_text'],1,"",1);} else {$setup_lang_forgpass_text = $ar_setup_lang['setup_lang_forgpass_text'];}
if ($_POST['setup_lang_league_email_body_add_player_checkbox'] == 1){$setup_lang_league_email_body_add_player = PrepareForDB($_POST['setup_lang_league_email_body_add_player'],1,"",1);} else {$setup_lang_league_email_body_add_player = $ar_setup_lang['setup_lang_league_email_body_add_player'];}

$setup_lang_reg_subject = PrepareForDB($_POST['setup_lang_reg_subject'],1,"",1);
$setup_lang_forgpass_subject = PrepareForDB($_POST['setup_lang_forgpass_subject'],1,"",1);
$setup_lang_change_subject = PrepareForDB($_POST['setup_lang_change_subject'],1,"",1);
$setup_lang_changed_subject = PrepareForDB($_POST['setup_lang_changed_subject'],1,"",1);
$setup_lang_contact_form_subject = PrepareForDB($_POST['setup_lang_contact_form_subject'],1,"",1);
$setup_lang_forum_title = PrepareForDB($_POST['setup_lang_forum_title'],1,"",1);
$setup_lang_league_email_subject_add_player = PrepareForDB($_POST['setup_lang_league_email_subject_add_player'],1,"",1);

mysql_query("UPDATE $db_setup_lang SET
setup_lang_ban_text = '".$setup_lang_ban_text."',
setup_lang_reg_help = '".$setup_lang_reg_help."',
setup_lang_reg_text = '".$setup_lang_reg_text."',
setup_lang_reg_terms = '".$setup_lang_reg_terms."',
setup_lang_reg_subject = '".$setup_lang_reg_subject."',
setup_lang_forgpass_subject = '".$setup_lang_forgpass_subject."',
setup_lang_forgpass_text = '".$setup_lang_forgpass_text."',
setup_lang_comments_rules = '".$setup_lang_comments_rules."',
setup_lang_change_subject = '".$setup_lang_changed_subject."',
setup_lang_change_email = '".$setup_lang_change_email."',
setup_lang_changed_subject = '".$setup_lang_change_subject."',
setup_lang_changed_email = '".$setup_lang_changed_email."',
setup_lang_contact_form_subject = '".$setup_lang_contact_form_subject."', 
setup_lang_forum_title = '".$setup_lang_forum_title."', 
setup_lang_league_email_body_add_player='".$setup_lang_league_email_body_add_player."', 
setup_lang_league_email_subject_add_player='".$setup_lang_league_email_subject_add_player."'
WHERE setup_lang='".$_SESSION['web_lang']."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());

header ("Location: ".$eden_cfg['url_cms']."sys_setup.php?project=".$_SESSION['project']."&msg=".$msg);
exit;