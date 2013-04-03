<?php
/***********************************************************************************************************
*
*		CHYBOVE HLASKY
*
***********************************************************************************************************/
function Msg($msg){

	switch ($msg){
		case "eden_missing_request_id":
			echo _ERR_EDEN_MISSING_REQUEST_ID;
			break;
		case "eden_missing_subteam_id":
			echo _ERR_EDEN_MISSING_SUBTEAM_ID;
			break;
		case "eden_missing_variables":
			echo _ERR_EDEN_MISSING_VARIABLES;
			break;
		case "replynames":
			echo _ERR_REG_REPLYNAMES;
			break;
		case "replynick":
			echo _ERR_REG_REPLYNICK;
			break;
		case "replysomething":
			echo _ERR_REG_REPLYSOMETHING;
			break;
		case "replyemail":
			echo _ERR_REG_REPLYEMAIL;
			break;
		case "noname":
			echo _ERR_REG_NONAME;;
			break;
		case "reg_old_pass_arent_match":
			echo _ERR_REG_BAD_OLD_PASS;
			break;
		case "reg_pass_arent_match":
			echo _ERR_REG_BADPASS;
			break;
		case "reg_email_is_not_vaild":
			echo _ERR_REG_EMAIL_IS_NOT_VALID;
			break;
		case "badlogin":
			echo _ERR_REG_BADLOGIN;
			break;
		case "bad_captcha":
			echo _ERR_BAD_CAPTCHA;
			break;
		case "edit_ok":
			echo _ERR_REG_EDIT_OK;
			break;
		case "reg_ok":
			echo _ERR_REG_REG_OK;
			break;
		case "reg_ok_mail":
			echo _ERR_REG_REG_OK_MAIL;
			break;
		case "reg_ok_mail_admin":
			echo _ERR_REG_REG_OK_MAIL_ADMIN;
			break;
		case "reg_er_mail":
			echo _ERR_REG_REG_ER_MAIL;
			break;
		case "reg_emptyusername":
			echo _ERR_REG_EMPTY_USERNAME;
			break;
		case "reg_emptyfirstname":
			echo _ERR_REG_EMPTY_FIRSTNAME;
			break;
		case "reg_emptyname":
			echo _ERR_REG_EMPTY_NAME;
			break;
		case "reg_emptynick":
			echo _ERR_REG_EMPTY_NICK;
			break;
		case "reg_emptypass":
			echo _ERR_REG_EMPTY_PASS;
			break;
		case "reg_emptyemail":
			echo _ERR_REG_EMPTY_EMAIL;
			break;
		case "reg_nomatchemail":
			echo _ERR_REG_NOMATCH_EMAIL;
			break;
		case "reg_fill_all_cells":
			echo _ERR_REG_FILL_ALL_CELLS;
			break;
		case "forgpass_baduser_or_email":
			echo _ERR_FORGPASS_BADUSR_MAIL;
			break;
		case "forgpass_ok_mail":
			echo _ERR_FORGPASS_OK_MAIL;
			break;
		case "forgpass_er_mail":
			echo _ERR_FORGPASS_ER_MAIL;
			break;
		case "forgpass_allready_used":
			echo _ERR_ALLREADY_USED;
			break;
		case "cups_reg_ok_mail":
			echo _ERR_CUPS_REG_OK_MAIL;
			break;
		case "cups_reg_er_mail":
			echo _ERR_CUPS_REG_ER_MAIL;
			break;
		case "reg_er":
			echo _ERR_REG_REG_ER;
			break;
		case "notallow":
			echo _ERR_REG_NOT_ALLOW;
			break;
		case "allow_no":
			echo _ERR_REG_ALLOW_NO;
			break;
		case "allow_ok":
			echo _ERR_REG_ALLOW_OK;
			break;
		case "allow_ok_admin":
			echo _ERR_REG_ALLOW_OK_ADMIN;
			break;
		case "add_link_ok":
			echo _ERR_ADD_LINK_OK;
			break;
		case "reg_disagree":
			echo _ERR_REG_DISAGREE;
			break;
		case "edit_ok":
			echo _ERR_EDIT_OK;
			break;
		case "poll_ok":
			echo _ERR_POLL_OK;
			break;
		case "poll_voted":
			echo _ERR_POLL_VOTED;
			break;
		case "shop_order_ok":
			echo _ERR_SHOP_ORDER_OK;
			break;
		case "shop_order_er":
			echo _ERR_SHOP_ORDER_ER;
			break;
		case "session_expired":
			echo _ERR_SESSION_EXPIRED;
			break;
		case "change_email_ok":
			echo _ERR_CHANGES_EMAIL_OK;
			break;
		case "change_email_ok_mail":
			echo _ERR_CHANGES_EMAIL_MAIL_OK;
			break;
		case "change_email_er_mail":
			echo _ERR_CHANGES_EMAIL_MAIL_ER;
			break;
		case "contact_form_er_mail":
			echo _ERR_CONTACT_FORM_MAIL_ER;
			break;
		case "contact_form_ok_mail":
			echo _ERR_CONTACT_FORM_MAIL_OK;
			break;
		case "tryagain1":
			echo _ERR_TRY_AGAIN;
			break;
		case "ftp_no_connection":
			echo _ERR_FTP_NO_CONNECTION;
			break;
		case "ftp_av_ts":
			echo _ERR_FTP_AV_IMG_TOO_SMALL;
			break;
		case "ftp_av_tb":
			echo _ERR_FTP_AV_IMG_TOO_BIG;
			break;
		case "ftp_av_ftb":
			echo _ERR_FTP_AV_FILESIZE_TOO_BIG;
			break;
		case "ftp_av_wft":
			echo _ERR_FTP_AV_IMG_WRONG_FILETYPE;
			break;
		case "ftp_av_bi":
			echo _ERR_FTP_AV_IMG_BAD;
			break;
		case "ftp_ue":
			echo _ERR_FTP_UPLOAD_ERROR;
			break;
		case "forum_edit_post_without_reason":
			echo _ERR_FORUM_EDIT_WITHOUT_REASON;
			break;
		case "forum_report_without_reason":
			echo _ERR_FORUM_REPORT_WITHOUT_REASON;
			break;
		case "forum_no_pm_rec":
			echo _ERR_FORUM_NO_PM_REC;
			break;
		case "forum_no_pm_post":
			echo _ERR_FORUM_NO_PM_POST;
			break;
		case "forum_no_topic_name":
			echo _ERR_FORUM_NO_TOPIC_NAME;
			break;
		case "banned":
			echo _ERR_BANNED;
			break;
		case "users_polls_ok":
			echo _ERR_USERS_POLLS_OK;
			break;
		case "users_polls_24":
			echo _ERR_USERS_POLLS_24;
			break;
		case "users_polls_noq":
			echo _ERR_USERS_POLLS_NOQ;
			break;
		case "users_polls_noa":
			echo _ERR_USERS_POLLS_NOA;
			break;
		case "users_polls_del_ok":
			echo _ERR_USERS_POLLS_DEL_OK;
			break;
		case "form_sent_ok":
			echo _ERR_FORM_SENT_OK;
			break;
		case "form_sent_er":
			echo _ERR_FORM_SENT_ER;
			break;
		case "dict_add_ok":
			echo _ERR_DICT_ADD_OK;
			break;
		case "dict_word_exist":
			echo _ERR_DICT_WORD_EXIST;
			break;
		case "league_draft_del_er":
			echo _ERR_LEAGUE_DRAFT_DEL_ER;
			break;
		case "league_draft_del_ok":
			echo _ERR_LEAGUE_DRAFT_DEL_OK;
			break;
		case "league_draft_search_player_err":
			echo _ERR_LEAGUE_DRAFT_SEARCH_PLAYER_ERR;
			break;
		case "league_draft_search_player_exist":
			echo _ERR_LEAGUE_DRAFT_SEARCH_PLAYER_EXIST;
			break;
		case "league_draft_search_player_ok":
			echo _ERR_LEAGUE_DRAFT_SEARCH_PLAYER_OK;
			break;
		case "league_draft_search_team_err":
			echo _ERR_LEAGUE_DRAFT_SEARCH_TEAM_ERR;
			break;
		case "league_draft_search_team_exist":
			echo _ERR_LEAGUE_DRAFT_SEARCH_TEAM_EXIST;
			break;
		case "league_draft_search_team_ok":
			echo _ERR_LEAGUE_DRAFT_SEARCH_TEAM_OK;
			break;
		case "league_guid_add_ok":
			echo _ERR_LEAGUE_GUID_ADD_OK;
			break;
		case "league_guid_edit_ok":
			echo _ERR_LEAGUE_GUID_EDIT_OK;
			break;
		case "league_guid_exist":
			echo _ERR_LEAGUE_GUID_EXIST;
			break;
		case "league_hibernate_er":
			echo _ERR_LEAGUE_HIBERNATE_ER;
			break;
		case "league_hibernate_ok":
			echo _ERR_LEAGUE_HIBERNATE_OK;
			break;
		case "league_hibernate_players_in_team":
			echo _ERR_LEAGUE_HIBERNATE_PLAYERS_IN_TEAM;
			break;
		case "league_is_locked":
			echo _ERR_LEAGUE_IS_LOCKED;
			break;
		case "league_no_privilege":
			echo _ERR_LEAGUE_NO_PRIVILEGE;
			break;
		case "league_player_add_er_mail":
			echo _ERR_LEAGUE_PLAYER_ADD_ER_MAIL;
			break;
		case "league_player_add_ok_mail":
			echo _ERR_LEAGUE_PLAYER_ADD_OK_MAIL;
			break;
		case "league_player_ban_add_all_ok":
			echo _ERR_LEAGUE_PLAYER_BAN_ADD_ALL_OK;
			break;
		case "league_player_make_a":
			echo _ERR_LEAGUE_PLAYER_MAKE_A;
			break;
		case "league_player_make_c":
			echo _ERR_LEAGUE_PLAYER_MAKE_C;
			break;
		case "league_player_make_o":
			echo _ERR_LEAGUE_PLAYER_MAKE_O;
			break;
		case "league_player_make_p":
			echo _ERR_LEAGUE_PLAYER_MAKE_P;
			break;
		case "league_player_kicked":
			echo _ERR_LEAGUE_PLAYER_KICKED;
			break;
		case "league_player_left_team":
			echo _ERR_LEAGUE_PLAYER_LEFT_TEAM;
			break;
		case "league_player_left_league":
			echo _ERR_LEAGUE_PLAYER_LEFT_LEAGUE;
			break;
		case "league_player_non_exist":
			echo _ERR_LEAGUE_PLAYER_NON_EXIST;
			break;
		case "league_player_not_entered":
			echo _ERR_LEAGUE_PLAYER_NOT_ENTERED;
			break;
		case "league_player_invite_exist":
			echo _ERR_LEAGUE_PLAYER_INVITE_EXIST;
			break;
		case "league_player_reg_no_agree":
			echo _ERR_LEAGUE_PLAYER_REG_NO_AGREE;
			break;
		case "league_player_reg_already":
			echo _ERR_LEAGUE_PLAYER_REG_ALREADY;
			break;
		case "league_player_reg_to_league":
			echo _ERR_LEAGUE_PLAYER_REG_TO_LEAGUE;
			break;
		case "league_team_add_ok":
			echo _ERR_LEAGUE_TEAM_ADD_OK;
			break;
		case "league_team_edit_ok":
			echo _ERR_LEAGUE_TEAM_EDIT_OK;
			break;
		case "league_team_join_request":
			echo _ERR_LEAGUE_TEAM_JOIN_REQUEST;
			break;
		case "league_team_join_request_exist":
			echo _ERR_LEAGUE_TEAM_JOIN_REQUEST_EXIST;
			break;
		case "league_team_join_request_inteam":
			echo _ERR_LEAGUE_TEAM_JOIN_REQUEST_INTEAM;
			break;
		case "league_team_league_players_allowed":
			echo _ERR_LEAGUE_TEAM_LEFT_PLAYERS_ALLOWED;
			break;
		case "league_team_left_league":
			echo _ERR_LEAGUE_TEAM_LEFT_LEAGUE;
			break;
		case "league_team_owner_have_team":
			echo _ERR_LEAGUE_TEAM_OWNER_HAVE_TEAM;
			break;
		case "league_team_player_agreed":
			echo _ERR_LEAGUE_TEAM_PLAYER_AGREED;
			break;
		case "league_team_player_disagreed":
			echo _ERR_LEAGUE_TEAM_PLAYER_DISAGREED;
			break;
		case "league_team_team_agreed":
			echo _ERR_LEAGUE_TEAM_TEAM_AGREED;
			break;
		case "league_team_team_disagreed":
			echo _ERR_LEAGUE_TEAM_TEAM_DISAGREED;
			break;
		case "league_team_player_play_same_game":
			echo _ERR_LEAGUE_TEAM_PLAYER_PLAY_SAME_GAME;
			break;
		case "league_team_reached_max_players":
			echo _ERR_LEAGUE_TEAM_REACHED_MAX_PLAYERS;
			break;
		case "league_team_reg_no_agree":
			echo _ERR_LEAGUE_TEAM_REG_NO_AGREE;
			break;
		case "league_team_reg_to_league":
			echo _ERR_LEAGUE_TEAM_REG_TO_LEAGUE;
			break;
		case "league_team_reg_already":
			echo _ERR_LEAGUE_TEAM_REG_ALREADY;
			break;
		case "league_team_sub_added":
			echo _ERR_LEAGUE_TEAM_SUB_ADDED;
			break;
		case "league_team_name_exist":
			echo _ERR_LEAGUE_TEAM_NAME_EXIST;
			break;
		case "links_noname":
			echo _ERR_LINKS_NO_NAME;
			break;
		case "links_nolink":
			echo _ERR_LINKS_NO_LINK;
			break;
		case "links_nodesc":
			echo _ERR_LINKS_NO_DESC;
			break;
		case "links_noimg":
			echo _ERR_LINKS_NO_IMG;
			break;
		case "rtf_empty_name":
			echo _ERR_RECOMMEND_EMPTY_NAME;
			break;
		case "rtf_empty_from_email":
			echo _ERR_RECOMMEND_FROM_EMPTY_EMAIL;
			break;
		case "rtf_no_from_email":
			echo _ERR_RECOMMEND_FROM_BAD_EMAIL;
			break;
		case "rtf_empty_to_email_articles":
			echo _ERR_RECOMMEND_TO_ARTICLES_EMPTY_EMAIL;
			break;
		case "rtf_empty_to_email_act":
			echo _ERR_RECOMMEND_TO_ACT_EMPTY_EMAIL;
			break;
		case "rtf_empty_to_email_shop":
			echo _ERR_RECOMMEND_TO_SHOP_EMPTY_EMAIL;
			break;
		case "rtf_no_to_email_articles":
			echo _ERR_RECOMMEND_TO_ARTICLES_BAD_EMAIL;
			break;
		case "rtf_no_to_email_act":
			echo _ERR_RECOMMEND_TO_ACT_BAD_EMAIL;
			break;
		case "rtf_no_to_email_shop":
			echo _ERR_RECOMMEND_TO_SHOP_BAD_EMAIL;
			break;
		case "seller_add_ok":
			echo _ERR_SELLER_ADD_OK;
			break;
		case "seller_add_er":
			echo _ERR_SELLER_ADD_ER;
			break;
		case "seller_edit_ok":
			echo _ERR_SELLER_EDIT_OK;
			break;
		case "seller_edit_er":
			echo _ERR_SELLER_EDIT_ER;
			break;
		case "web_agreement_ok":
			echo _ERR_WEB_AGREEMENT_OK;
			break;
		case "web_agreement_er":
			echo _ERR_WEB_AGREEMENT_ER;
			break;
		case "email_opt_out_er":
			echo _ERR_EMAIL_OPT_OUT_ER;
			break;
		case "email_opt_out_ok":
			echo _ERR_EMAIL_OPT_OUT_OK;
			break;
		case "decklist_add_ok":
			echo _ERR_DECKLIST_ADD_OK;
			break;
		case "decklist_add_er":
			echo _ERR_DECKLIST_ADD_ER;
			break;
		case "decklist_add_er_no_format":
			echo _ERR_DECKLIST_ADD_ER_NO_FORMAT;
			break;
		case "decklist_add_er_no_name":
			echo _ERR_DECKLIST_ADD_ER_NO_NAME;
			break;
		default:
			echo "";
	}
}