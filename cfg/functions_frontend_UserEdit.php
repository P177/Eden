<?php
/***********************************************************************************************************
*
*		EDITACE UZIVATELU
*
*		DOSTUPNE MODY:
*		user_edit				-	Zobrazi vsechny udaje o uzivateli pro jejich upravu
*		reg 					-	Zobrazi udaje nutne pro registraci
*		shop_show				-	Zobrazi jen vypis udaju
*		shop_check_address		-	Nezobrazi obsah okna editace uzivatelu, ale jen vrati $shop_echo
*		shop_check_del_address	-	Pouzije se pri editaci delivery adresy
*
*		$shop_echo	- 	Pokud je promenna "true" znamena to ze nektere z policek adresy nejsou vyplneny. Tim
*						nemuze dojit k pokracovani v objednavce.
*
***********************************************************************************************************/
function UserEdit($useredit_mod){
	
	global 	$db_admin,$db_admin_contact,$db_admin_contact_shop,$db_admin_clan,$db_admin_game,$db_admin_hw,$db_admin_info,$db_admin_poker,$db_country;
	global 	$db_language,$db_setup,$db_setup_lang,$db_filters,$db_poker_cardrooms,$db_poker_variants;
	global 	$eden_cfg;
	global 	$url_admins;
	global	$shop_echo;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['mode'] = AGet($_GET,'mode');
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	$res = mysql_query("
	SELECT * 
	FROM $db_admin, $db_admin_clan, $db_admin_contact, $db_admin_contact_shop, $db_admin_game, $db_admin_hw, $db_admin_info, $db_admin_poker 
	WHERE admin_id=".(integer)$_SESSION['loginid']." AND $db_admin_clan.aid=".(integer)$_SESSION['loginid']." AND $db_admin_contact.aid=".(integer)$_SESSION['loginid']." 
	AND $db_admin_contact_shop.aid=".(integer)$_SESSION['loginid']." AND $db_admin_game.aid=".(integer)$_SESSION['loginid']." AND $db_admin_hw.aid=".(integer)$_SESSION['loginid']." 
	AND $db_admin_info.aid=".(integer)$_SESSION['loginid']
	) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	
	$res_setup = mysql_query("
	SELECT setup_reg_admin_nick, setup_admin_show_contact, setup_basic_country, setup_admin_show_contact_shop, setup_admin_show_clan, setup_admin_show_hw, setup_admin_show_game, 
	setup_admin_show_info, setup_admin_show_poker, setup_reg_agreement 
	FROM $db_setup"
	) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$res_setup_lang = mysql_query("
	SELECT setup_lang_reg_help, setup_lang_reg_terms 
	FROM $db_setup_lang 
	WHERE setup_lang='".mysql_real_escape_string($_GET['lang'])."'"
	) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup_lang = mysql_fetch_array($res_setup_lang);
	
	/* Editace uzivaleskeho uctu */
	if ($useredit_mod == "edit_user"){
		//session_start();
		$_SESSION['useredit_mod'] = "edit_user";
	}
	/* Registrace noveho uctu */
	if ($useredit_mod == "reg"){
		session_start();
		$_SESSION['useredit_mod'] = "user_reg";
	}
	if ($useredit_mod == "shop_show"){
		session_start();
		$_SESSION['useredit_mod'] = "shop_show";
	}
	if ($useredit_mod == "shop_check_address"){
		session_start();
		if ($ar['admin_contact_address_1'] == "" || $ar['admin_contact_postcode'] == "" || $ar['admin_contact_city'] == ""){$shop_echo = "true";}
		$_SESSION['useredit_mod'] = "shop_check_address";
	}
	if ($useredit_mod == "shop_check_del_address"){
		session_start();
		if ($ar['admin_contact_shop_title'] == "" || $ar['admin_contact_shop_firstname'] == "" || $ar['admin_contact_shop_name'] == "" || $ar['admin_contact_shop_address_1'] == "" || $ar['admin_contact_shop_address_2'] == "" || $ar['admin_contact_shop_postcode'] == "" || $ar['admin_contact_shop_city'] == ""){$shop_echo = "true";}
		$_SESSION['useredit_mod'] = "shop_check_del_address";
	}
	
	if ($_SESSION['useredit_mod'] != "shop_check_address"){
		if ($_SESSION['useredit_mod'] == "edit_user" || $_SESSION['useredit_mod'] == "user_reg" || $_SESSION['useredit_mod'] == "shop_check_del_address" || $ar['admin_contact_address_1'] == "" || $ar['admin_contact_address_2'] == "" || $ar['admin_contact_postcode'] == "" || $ar['admin_contact_city'] == ""){
			echo "		<form name=\"user_edit\" enctype=\"multipart/form-data\" action=\"".$eden_cfg['url_edencms']."eden_save.php?action="; if ($_SESSION['useredit_mod'] == "edit_user"){ echo "edit_user";} if ($_SESSION['useredit_mod'] == "user_reg"){ echo "user_reg";} echo "&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;e_mode=".AGet($_GET,'e_mode')."\" method=\"post\" "; if ($_SESSION['useredit_mod'] == "user_reg"){ echo "ONSUBMIT=\"return CheckRegistration(this,"; /* Zabezpeceni nekontrolovani nicku pokud tento neni vyzadovan pri registraci */ if ($ar_setup['setup_reg_admin_nick'] == 1){echo "1";} else {echo "0";} echo ")\""; } echo ">\n";
		}
		echo "<table cellspacing=\"0\" cellpadding=\"4\" class=\"eden_users_table\" border=\"0\">"; 
		if ($_SESSION['useredit_mod'] == "user_reg"){
			$admin_username = 			$_GET['aun'];
			$admin_nick = 				$_GET['ani'];
			$admin_title = 				$_GET['ati'];
			$admin_firstname = 			$_GET['afn'];
			$admin_name = 				$_GET['anm'];
			$admin_email = 				$_GET['aem'];
			$admin_email_repeat = 		$_GET['aemr'];
			$year = 					$_GET['acby'];
			$month = 					$_GET['acbm'];
			$day = 						$_GET['acbd'];
			$admin_contact_city = 		$_GET['acci'];
			$admin_contact_country = 	$_GET['acc'];
			$admin_contact_companyname = $_GET['accn'];
			$admin_contact_address_1 = 	$_GET['aca1'];
			$admin_contact_address_2 = 	$_GET['aca2'];
			$admin_contact_postcode = 	$_GET['acp'];
			$admin_contact_telefon = 	$_GET['act'];
			$admin_contact_mobil = 		$_GET['acm'];
			$admin_autologin = 			$_GET['aal'];
			$admin_agree_email = 		$_GET['aae'];
			echo "<tr>";
			echo "	<td colspan=\"2\">"._REG_INTRO."</td>";
			echo "</tr>";
		}
		if ($_SESSION['useredit_mod'] == "edit_user" || $_SESSION['useredit_mod'] == "shop_check_del_address"){
			$admin_username = $ar['admin_uname'];
			$admin_nick = $ar['admin_nick'];
			$admin_title = $ar['admin_title'];
			$admin_firstname = $ar['admin_firstname'];
			$admin_name = $ar['admin_name'];
			$admin_email = $ar['admin_email'];
			$day = substr($ar['admin_contact_birth_day'], 6, 2);		// vrátí "den"
			$month = substr($ar['admin_contact_birth_day'], 4, 2); // vrátí "mesic"
			$year = substr($ar['admin_contact_birth_day'], 0, 4); // vrátí "rok"
			$admin_contact_city = $ar['admin_contact_city'];
			$admin_contact_country = $ar['admin_contact_country'];
			$admin_contact_companyname = $ar['admin_contact_companyname'];
			$admin_contact_address_1 = $ar['admin_contact_address_1'];
			$admin_contact_address_2 = $ar['admin_contact_address_2'];
			$admin_contact_postcode = $ar['admin_contact_postcode'];
			$admin_contact_telefon = $ar['admin_contact_telefon'];
			$admin_contact_mobil = $ar['admin_contact_mobil'];
			$admin_autologin = $ar['admin_autologin'];
			$admin_agree_email = $ar['admin_agree_email'];
		}
		/***************************************************************************************
		*
		*		ZOBRAZENI ZAKLADNICH UDAJU
		*
		***************************************************************************************/
		echo "<tr>\n";
		echo "	<td class=\"eden_users_td_description\">&nbsp;</td>\n";
		echo "	<td class=\"eden_users_td_data\"><h2>"._ADMIN_INFO_BASIC."</h2></td>\n";
		echo "</tr>"; 
		if ($eden_cfg['show_admin_username'] == 1){
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"; if ($_SESSION['useredit_mod'] == "user_reg"){echo "<span style=\"color: #ff0000; font-weight: bold;\">*</span> ";} echo _USERNAME."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\">"; if ($_SESSION['useredit_mod'] == "user_reg"){ echo "<input type=\"text\" name=\"admin_username\" size=\"40\" maxlength=\"25\" value=\"".htmlspecialchars($admin_username, ENT_QUOTES)."\"> <a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('"._ADMIN_HINT_USERNAME."', this, event, '200px')\">[' ? ']</a><br>"; } else { echo "<strong>".htmlspecialchars($ar['admin_uname'], ENT_QUOTES)."</strong><input type=\"hidden\" name=\"admin_username\" value=\"".htmlspecialchars($ar['admin_uname'], ENT_QUOTES)."\">"; } echo "</td>\n";
			echo "</tr>";
		}
		if ($eden_cfg['show_admin_nick'] == 1 && $ar_setup['setup_reg_admin_nick'] == 1){
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"; if ($_SESSION['useredit_mod'] == "user_reg"){echo "<span style=\"color: #ff0000; font-weight: bold;\">*</span> ";} echo _ADMIN_NICK."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\">"; if ($_SESSION['useredit_mod'] == "user_reg"){ echo "<input type=\"text\" name=\"admin_nick\" size=\"40\" maxlength=\"25\" value=\"".htmlspecialchars($admin_nick, ENT_QUOTES)."\"> <a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('"._ADMIN_HINT_NICK."', this, event, '200px')\">[' ? ']</a>"; } else { echo "<strong>".$ar['admin_nick']."</strong><input type=\"hidden\" name=\"admin_nick\" value=\"".htmlspecialchars($ar['admin_nick'], ENT_QUOTES)."\">"; } echo "</td>\n";
			echo "</tr>";
		}
		if ($_SESSION['useredit_mod'] == "edit_user" && AGet($_SESSION,'forg_pass') != "true" && AGet($_GET,'e_mode') != "open"){
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._PASSWORD_OLD."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"password\" name=\"admin_password_old\" maxlength=\"25\" size=\"40\" value=\"\"> "; if ($_SESSION['useredit_mod'] == "user_reg"){ echo "<a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('"._ADMIN_HINT_PASSWORD_OLD."', this, event, '200px')\">[' ? ']</a>"; } echo "</td>\n";
			echo "</tr>";
		}
		if ($eden_cfg['show_admin_password1'] == 1){
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"; if ($_SESSION['useredit_mod'] == "user_reg"){echo "<span style=\"color: #ff0000; font-weight: bold;\">*</span> ";} echo ""._PASSWORD."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"password\" name=\"admin_password1"; if (AGet($_GET,'e_mode') == "open"){echo "_e";} echo "\" maxlength=\"25\" size=\"40\" value=\"\"> "; if ($_SESSION['useredit_mod'] == "user_reg"){ echo "<a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('"._ADMIN_HINT_PASSWORD1."', this, event, '200px')\">[' ? ']</a>"; } echo "</td>\n";
			echo "</tr>";
		}
		if ($eden_cfg['show_admin_password2'] == 1 && AGet($_GET,'e_mode') != "open"){
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"; if ($_SESSION['useredit_mod'] == "user_reg"){echo "<span style=\"color: #ff0000; font-weight: bold;\">*</span> ";} echo ""._PASSWORD2."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"password\" name=\"admin_password2\" maxlength=\"25\" size=\"40\" value=\"\"> "; if ($_SESSION['useredit_mod'] == "user_reg"){ echo "<a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('"._ADMIN_HINT_PASSWORD2."', this, event, '200px')\">[' ? ']</a>"; } echo "</td>\n";
			echo "</tr>";
		}
		if ($eden_cfg['show_admin_title'] == 1 && AGet($_GET,'e_mode') != "open"){
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_TITLE."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\">"; if ($_SESSION['useredit_mod'] != "shop_show"){ echo "<select name=\"admin_title\" class=\"input\">\n";
			echo "		<option value=\"\" "; if ($admin_title == "") {echo "selected=\"selected\"";} echo ">(Select)</option>\n";
			echo "		<option value=\"Dr\" "; if ($admin_title == "Dr") {echo "selected=\"selected\"";} echo ">Dr</option>\n";
			echo "		<option value=\"Mr\" "; if ($admin_title == "Mr") {echo "selected=\"selected\"";} echo ">Mr</option>\n";
			echo "		<option value=\"Mrs\" "; if ($admin_title == "Mrs") {echo "selected=\"selected\"";} echo ">Mrs</option>\n";
			echo "		<option value=\"Miss\" "; if ($admin_title == "Miss") {echo "selected=\"selected\"";} echo ">Miss</option>\n";
			echo "		<option value=\"Ms\" "; if ($admin_title == "Ms") {echo "selected=\"selected\"";} echo ">Ms</option>\n";
			echo "		</select>";
		} else {
			echo $ar['admin_title']."<input type=\"hidden\" name=\"admin_title\" value=\"".$ar['admin_title']."\">"; } echo "\n";
			echo "	</td>\n";
			echo "</tr>";
		}
		if ($eden_cfg['show_admin_firstname'] == 1){
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"; if ($_SESSION['useredit_mod'] == "user_reg"){echo "<span style=\"color: #ff0000; font-weight: bold;\">*</span> ";} echo _ADMIN_FIRSTNAME."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\">"; if (AGet($_GET,'e_mode') != "open"){ echo "<input type=\"text\" name=\"admin_firstname\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($admin_firstname, ENT_QUOTES)."\"> "; if ($_SESSION['useredit_mod'] == "user_reg"){ echo "<a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('"._ADMIN_HINT_FIRSTNAME."', this, event, '200px')\">[' ? ']</a>"; }} else { echo htmlspecialchars($admin_firstname, ENT_QUOTES);} echo "</td>\n";
			echo "</tr>";
		}
		if ($eden_cfg['show_admin_name'] == 1){
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"; if ($_SESSION['useredit_mod'] == "user_reg"){echo "<span style=\"color: #ff0000; font-weight: bold;\">*</span> ";} echo _ADMIN_NAME."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\">"; if (AGet($_GET,'e_mode') != "open"){ echo "<input type=\"text\" name=\"admin_name\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($admin_name, ENT_QUOTES)."\"> "; if ($_SESSION['useredit_mod'] == "user_reg"){ echo "<a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('"._ADMIN_HINT_NAME."', this, event, '200px')\">[' ? ']</a>"; }} else {echo htmlspecialchars($admin_name, ENT_QUOTES);} echo "</td>\n";
			echo "</tr>";
		}
		if ($eden_cfg['show_admin_email'] == 1){
			if ($_SESSION['useredit_mod'] == "user_reg"){
				echo "	<tr>\n";
				echo "		<td class=\"eden_users_td_description\">"; if ($_SESSION['useredit_mod'] == "user_reg"){echo "<span style=\"color: #ff0000; font-weight: bold;\">*</span> ";} echo "<strong>"._EMAIL."</strong></td>\n";
				echo "		<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_email\" size=\"40\" value=\"".htmlspecialchars($admin_email, ENT_QUOTES)."\"> "; if ($_SESSION['useredit_mod'] == "user_reg"){ echo "<a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('"._ADMIN_HINT_EMAIL."', this, event, '200px')\">[' ? ']</a>"; } echo "</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "		<td class=\"eden_users_td_description\">"; if ($_SESSION['useredit_mod'] == "user_reg"){echo "<span style=\"color: #ff0000; font-weight: bold;\">*</span> ";} echo "<strong>"._ADMIN_EMAIL_REPEAT."</strong></td>\n";
				echo "		<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_email_repeat\" size=\"40\" value=\"".htmlspecialchars($admin_email_repeat, ENT_QUOTES)."\"> </td>\n";
				echo "	</tr>";
			} else {
				echo "	<tr>\n";
				echo "		<td class=\"eden_users_td_description\"><strong>"._EMAIL."</strong></td>\n";
				echo "		<td class=\"eden_users_td_data\">".$admin_email; if (AGet($_GET,'e_mode') != "open") { echo "<input type=\"hidden\" name=\"admin_email\" value=\"".$admin_email."\"> <a href=\"".$eden_cfg['url']."index.php?action=user_edit&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;mode=edit_user&amp;e_mode=open\" target=\"_self\"><- "._ADMIN_EMAIL_CHANGE."</a>"; } echo "</td>\n";
				echo "	</tr>";
				if (AGet($_GET,'e_mode') == "open"){
					echo "		<tr>\n";
					echo "			<td class=\"eden_users_td_description\"><strong>"._ADMIN_EMAIL_NEW."</strong></td>\n";
					echo "			<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_email\" size=\"40\" value=\"\"> </td>\n";
					echo "		</tr>\n";
					echo "		<tr>\n";
					echo "			<td class=\"eden_users_td_description\"><strong>"._ADMIN_EMAIL_REPEAT."</strong></td>\n";
					echo "			<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_email_repeat\" size=\"40\" value=\"\"> </td>\n";
					echo "		</tr>\n";
					echo "		<tr>\n";
					echo "			<td class=\"eden_users_td_description\">&nbsp;</td>\n";
					echo "			<td class=\"eden_users_td_data\">"._ADMIN_EMAIL_CHANGE_HINT."</td>\n";
					echo "		</tr>";
				}
			}
		}
		if ($eden_cfg['show_admin_contact_birth_day'] == 1 && AGet($_GET,'e_mode') != "open"){
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\" valign=\"middle\"><strong>"._ADMIN_BIRTH_DAY."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\">\n";
			echo "	<select name=\"day\" class=\"input\">\n";
			echo "		<option value=\"--\" "; if ($day == "--") {echo "selected=\"selected\"";} echo ">--</option>\n";
			echo "		<option value=\"01\" "; if ($day == "01") {echo "selected=\"selected\"";} echo ">01</option>\n";
			echo "		<option value=\"02\" "; if ($day == "02") {echo "selected=\"selected\"";} echo ">02</option>\n";
			echo "		<option value=\"03\" "; if ($day == "03") {echo "selected=\"selected\"";} echo ">03</option>\n";
			echo "		<option value=\"04\" "; if ($day == "04") {echo "selected=\"selected\"";} echo ">04</option>\n";
			echo "		<option value=\"05\" "; if ($day == "05") {echo "selected=\"selected\"";} echo ">05</option>\n";
			echo "		<option value=\"06\" "; if ($day == "06") {echo "selected=\"selected\"";} echo ">06</option>\n";
			echo "		<option value=\"07\" "; if ($day == "07") {echo "selected=\"selected\"";} echo ">07</option>\n";
			echo "		<option value=\"08\" "; if ($day == "08") {echo "selected=\"selected\"";} echo ">08</option>\n";
			echo "		<option value=\"09\" "; if ($day == "09") {echo "selected=\"selected\"";} echo ">09</option>\n";
			echo "		<option value=\"10\" "; if ($day == "10") {echo "selected=\"selected\"";} echo ">10</option>\n";
			echo "		<option value=\"11\" "; if ($day == "11") {echo "selected=\"selected\"";} echo ">11</option>\n";
			echo "		<option value=\"12\" "; if ($day == "12") {echo "selected=\"selected\"";} echo ">12</option>\n";
			echo "		<option value=\"13\" "; if ($day == "13") {echo "selected=\"selected\"";} echo ">13</option>\n";
			echo "		<option value=\"14\" "; if ($day == "14") {echo "selected=\"selected\"";} echo ">14</option>\n";
			echo "		<option value=\"15\" "; if ($day == "15") {echo "selected=\"selected\"";} echo ">15</option>\n";
			echo "		<option value=\"16\" "; if ($day == "16") {echo "selected=\"selected\"";} echo ">16</option>\n";
			echo "		<option value=\"17\" "; if ($day == "17") {echo "selected=\"selected\"";} echo ">17</option>\n";
			echo "		<option value=\"18\" "; if ($day == "18") {echo "selected=\"selected\"";} echo ">18</option>\n";
			echo "		<option value=\"19\" "; if ($day == "19") {echo "selected=\"selected\"";} echo ">19</option>\n";
			echo "		<option value=\"20\" "; if ($day == "20") {echo "selected=\"selected\"";} echo ">20</option>\n";
			echo "		<option value=\"21\" "; if ($day == "21") {echo "selected=\"selected\"";} echo ">21</option>\n";
			echo "		<option value=\"22\" "; if ($day == "22") {echo "selected=\"selected\"";} echo ">22</option>\n";
			echo "		<option value=\"23\" "; if ($day == "23") {echo "selected=\"selected\"";} echo ">23</option>\n";
			echo "		<option value=\"24\" "; if ($day == "24") {echo "selected=\"selected\"";} echo ">24</option>\n";
			echo "		<option value=\"25\" "; if ($day == "25") {echo "selected=\"selected\"";} echo ">25</option>\n";
			echo "		<option value=\"26\" "; if ($day == "26") {echo "selected=\"selected\"";} echo ">26</option>\n";
			echo "		<option value=\"27\" "; if ($day == "27") {echo "selected=\"selected\"";} echo ">27</option>\n";
			echo "		<option value=\"28\" "; if ($day == "28") {echo "selected=\"selected\"";} echo ">28</option>\n";
			echo "		<option value=\"29\" "; if ($day == "29") {echo "selected=\"selected\"";} echo ">29</option>\n";
			echo "		<option value=\"30\" "; if ($day == "30") {echo "selected=\"selected\"";} echo ">30</option>\n";
			echo "		<option value=\"31\" "; if ($day == "31") {echo "selected=\"selected\"";} echo ">31</option>\n";
			echo "	</select>\n";
			echo "	<select name=\"month\" class=\"input\">\n";
			echo "		<option value=\"--\" "; if ($month == "--") {echo "selected=\"selected\"";} echo ">--</option>\n";
			echo "		<option value=\"01\" "; if ($month == "01") {echo "selected=\"selected\"";} echo ">01</option>\n";
			echo "		<option value=\"02\" "; if ($month == "02") {echo "selected=\"selected\"";} echo ">02</option>\n";
			echo "		<option value=\"03\" "; if ($month == "03") {echo "selected=\"selected\"";} echo ">03</option>\n";
			echo "		<option value=\"04\" "; if ($month == "04") {echo "selected=\"selected\"";} echo ">04</option>\n";
			echo "		<option value=\"05\" "; if ($month == "05") {echo "selected=\"selected\"";} echo ">05</option>\n";
			echo "		<option value=\"06\" "; if ($month == "06") {echo "selected=\"selected\"";} echo ">06</option>\n";
			echo "		<option value=\"07\" "; if ($month == "07") {echo "selected=\"selected\"";} echo ">07</option>\n";
			echo "		<option value=\"08\" "; if ($month == "08") {echo "selected=\"selected\"";} echo ">08</option>\n";
			echo "		<option value=\"09\" "; if ($month == "09") {echo "selected=\"selected\"";} echo ">09</option>\n";
			echo "		<option value=\"10\" "; if ($month == "10") {echo "selected=\"selected\"";} echo ">10</option>\n";
			echo "		<option value=\"11\" "; if ($month == "11") {echo "selected=\"selected\"";} echo ">11</option>\n";
			echo "		<option value=\"12\" "; if ($month == "12") {echo "selected=\"selected\"";} echo ">12</option>\n";
			echo "	</select>\n";
			echo "	<select name=\"year\" class=\"input\">\n";
			echo "		<option	value=\"----\" "; if ($year == "----") {echo "selected=\"selected\"";} echo ">----</option>\n";
						for($y = 1920; $y < 2005; $y++){
							echo "<option value=\"".$y."\""; if ($year == $y) {echo " selected";} echo ">".$y."</option>\n";
						}
			echo "	</select>\n";
			echo "	</td>\n";
			echo "</tr>";
			if($eden_cfg['show_admin_gender'] == 1	&& AGet($_GET,'e_mode') != "open"){
				echo "<tr>\n";
				echo "	<td align=\"right\" valign=\"middle\"><strong>"._ADMIN_GENDER."</strong></td>\n";
				echo "	<td align=\"left\"><select name=\"admin_gender\" class=\"input\">\n";
				echo "		<option value=\"male\" "; if ($ar['admin_gender'] == "male") {echo "selected=\"selected\"";} echo ">"._ADMIN_GENDER_M."</option>\n";
				echo "		<option value=\"female\" "; if ($ar['admin_gender'] == "female") {echo "selected=\"selected\"";} echo ">"._ADMIN_GENDER_F."</option>\n";
				echo "		</select></td>\n";
				echo "</tr>";
			}
		}
		echo "<tr>\n";
		echo "	<td class=\"eden_users_td_description\"><br></td>\n";
		echo "	<td class=\"eden_users_td_data\"><br></td>\n";
		echo "</tr>";
		/***************************************************************************************
		*
		*		ZOBRAZENI KONTAKTU
		*
		***************************************************************************************/
		if ($_SESSION['useredit_mod'] == "shop_check_del_address" || $ar_setup['setup_admin_show_contact'] == 1	&& AGet($_GET,'e_mode') != "open"){
			echo "<tr>";
			echo "	<td class=\"eden_users_td_description\">&nbsp;</td>";
			echo "	<td class=\"eden_users_td_data\"><h2>"._ADMIN_INFO_CONTACT."</h2></td>";
			echo "</tr>";
			if ($eden_cfg['show_admin_contact_companyname'] == 1){
				echo "<tr>";
				echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_CONTACT_COMPANYNAME."</strong></td>";
				echo "<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_contact_companyname\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($admin_contact_companyname, ENT_QUOTES)."\"></td>";
				echo "</tr>";
			}
			if ($eden_cfg['show_admin_contact_address_1'] == 1){
				echo "<tr>";
				echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_CONTACT_ADDRESS."</strong></td>";
				echo "<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_contact_address_1\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($admin_contact_address_1, ENT_QUOTES)."\"></td>";
				echo "</tr>";
			}
			if ($eden_cfg['show_admin_contact_address_2'] == 1){
				echo "<tr>";
				echo "	<td class=\"eden_users_td_description\">&nbsp;</td>";
				echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_contact_address_2\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($admin_contact_address_2, ENT_QUOTES)."\"></td>";
				echo "</tr>";
			}
			if ($eden_cfg['show_admin_contact_postcode'] == 1){
				echo "<tr>";
				echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_CONTACT_POSTCODE."</strong></td>";
				echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_contact_postcode\" size=\"12\" maxlength=\"11\" value=\"".htmlspecialchars($admin_contact_postcode, ENT_QUOTES)."\"></td>";
				echo "</tr>";
			}
			if ($eden_cfg['show_admin_contact_city'] == 1){
				echo "<tr>";
				echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_CONTACT_CITY."</strong></td>";
				echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_contact_city\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($admin_contact_city, ENT_QUOTES)."\"></td>";
				echo "</tr>";
			}
			if ($eden_cfg['show_admin_contact_country'] == 1){
				echo "<tr>";
				echo "	<td class=\"eden_users_td_description\" valign=\"middle\"><strong>"._ADMIN_CONTACT_COUNTRY."</strong></td>";
				echo "	<td class=\"eden_users_td_data\"><select name=\"admin_contact_country\" class=\"input\">";
				$res3 = mysql_query("SELECT country_id, country_shortname, country_name FROM $db_country ORDER BY country_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				while ($ar3 = mysql_fetch_array($res3)){
					if ($ar3['country_id'] == 237 /* Serbia and Montenegro */ ||
					$ar3['country_id'] == 240 /* Alp's States */ ||
					$ar3['country_id'] == 242 /* America */ ||
					$ar3['country_id'] == 241 /* Baltiic's States */ ||
					$ar3['country_id'] == 243 /* Benelux */ ||
					$ar3['country_id'] == 244 /* Skandinavia */ ||
					$ar3['country_id'] == 245 /* East */ ||
					$ar3['country_id'] == 246 /* Tibet */ ||
					$ar3['country_id'] == 247 /* European Union */ ||
					$ar3['country_id'] == 248 /* Scotland */ ||
					$ar3['country_id'] == 249 /* England */ ||
					$ar3['country_id'] == 251 /* Wales */ ||
					$ar3['country_id'] == 255 /* Jersey */ ||
					$ar3['country_id'] == 256 /* Isle of Man */ ||
					$ar3['country_id'] == 257 /* World */ ){
						/* Zde se zobrazujou staty a vlajky, ktere nenexistuji, nebo jsou soucasti nejakeho jineho celku (zeme jsou brany z vlajek */
					} else {
						echo "<option value=\"".$ar3['country_id']."\" "; if ($admin_contact_country == $ar3['country_id']) {echo " selected";} if ($_SESSION['useredit_mod'] == "user_reg" && $ar3['country_shortname'] == $ar_setup['setup_basic_country']){echo " selected";} echo ">".$ar3['country_name']."</option>";
					}
				}
				echo "		</select>";
				echo "	</td>";
				echo "</tr>";
			} else {
				$res3 = mysql_query("SELECT country_id FROM $db_country WHERE country_shortname='".$ar_setup['setup_basic_country']."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar3 = mysql_fetch_array($res3);
				echo '<input type="hidden" name="admin_contact_country" value="'.$ar3['country_id'].'">';
			}
			if ($eden_cfg['show_admin_contact_telefon'] == 1){
				echo "<tr>\n";
				echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_TELEFON."</strong></td>\n";
				echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_contact_telefon\" size=\"40\" maxlength=\"20\" value=\"".htmlspecialchars($admin_contact_telefon, ENT_QUOTES)."\"></td>\n";
				echo "</tr>\n";
			}
			if ($eden_cfg['show_admin_contact_mobil'] == 1){
				echo "<tr>\n";
				echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_MOBIL."</strong></td>\n";
				echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_contact_mobil\" size=\"40\" maxlength=\"20\" value=\"".htmlspecialchars($admin_contact_mobil, ENT_QUOTES)."\"></td>\n";
				echo "</tr>\n";
			}
			if ($eden_cfg['show_admin_contact_icq'] == 1){
				echo "<tr>\n";
				echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_ICQ."</strong></td>\n";
				echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_contact_icq\" size=\"40\" maxlength=\"20\" value=\"".htmlspecialchars($ar['admin_contact_icq'], ENT_QUOTES)."\"></td>\n";
				echo "</tr>\n";
			}
			if ($eden_cfg['show_admin_contact_msn'] == 1){
				echo "<tr>\n";
				echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_MSN."</strong></td>\n";
				echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_contact_msn\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_contact_msn'], ENT_QUOTES)."\"></td>\n";
				echo "</tr>\n";
			}
			if ($eden_cfg['show_admin_contact_aol'] == 1){
				echo "<tr>\n";
				echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_AOL."</strong></td>\n";
				echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_contact_aol\" size=\"40\" maxlength=\"20\" value=\"".htmlspecialchars($ar['admin_contact_aol'], ENT_QUOTES)."\"></td>\n";
				echo "</tr>\n";
			}
			if ($eden_cfg['show_admin_contact_skype'] == 1){
				echo "<tr>\n";
				echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_SKYPE."</strong></td>\n";
				echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_contact_skype\" size=\"40\" maxlength=\"20\" value=\"".htmlspecialchars($ar['admin_contact_skype'], ENT_QUOTES)."\"></td>\n";
				echo "</tr>\n";
			}
			if ($eden_cfg['show_admin_contact_xfire'] == 1){
				echo "<tr>\n";
				echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_XFIRE."</strong></td>\n";
				echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_contact_xfire\" size=\"40\" maxlength=\"50\" value=\"".htmlspecialchars($ar['admin_contact_xfire'], ENT_QUOTES)."\"></td>\n";
				echo "</tr>";
			}
			echo "<tr>";
			echo "	<td class=\"eden_users_td_description\"><br></td>";
			echo "	<td class=\"eden_users_td_data\"><br></td>";
			echo "</tr>";
		}
		/***************************************************************************************
		*
		*		ZOBRAZENI KONTAKTU PRO DODANI ZBOZI
		*
		***************************************************************************************/
		if ($_SESSION['useredit_mod'] == "shop_check_del_address" || $ar_setup['setup_admin_show_contact_shop'] == 1 && $eden_cfg['show_admin_contact_shop_heading'] == 1	&& AGet($_GET,'e_mode') != "open"){
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\">&nbsp;</td>\n";
			echo "	<td class=\"eden_users_td_data\"><h2>"._ADMIN_INFO_CONTACT_SHOP."</h2>"._ADMIN_INFO_CONTACT_SHOP_HELP."</td>\n";
			echo "</tr>";
			if ($eden_cfg['show_admin_contact_shop_heading'] == 1){
		   		echo "<tr>\n";
				echo "	<td class=\"eden_users_td_description\"></strong><strong>"._ADMIN_TITLE."</strong></td>\n";
				echo "	<td class=\"eden_users_td_data\"><select name=\"admin_contact_shop_title\" class=\"input\">\n";
				echo "		<option value=\"\""; if ($ar['admin_contact_shop_title'] == "") {echo " selected";}; echo ">"._ADMIN_TITLE_SELECT."</option>\n";
				echo "		<option value=\"Dr\""; if ($ar['admin_contact_shop_title'] == "Dr") {echo " selected";}; echo ">Dr</option>\n";
				echo "		<option value=\"Mr\""; if ($ar['admin_contact_shop_title'] == "Mr") {echo " selected";}; echo ">Mr</option>\n";
				echo "		<option value=\"Mrs\""; if ($ar['admin_contact_shop_title'] == "Mrs") {echo " selected";}; echo ">Mrs</option>\n";
				echo "		<option value=\"Miss\""; if ($ar['admin_contact_shop_title'] == "Miss") {echo " selected";}; echo ">Miss</option>\n";
				echo "		<option value=\"Ms\""; if ($ar['admin_contact_shop_title'] == "Ms") {echo " selected";}; echo ">Ms</option>\n";
				echo "		</select></td>\n";
				echo "</tr>";
			}
			if ($eden_cfg['show_admin_contact_shop_firstname'] == 1){
				echo "<tr>";
				echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_FIRSTNAME."</strong></td>";
				echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_contact_shop_firstname\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_contact_shop_firstname'], ENT_QUOTES)."\"></td>";
				echo "</tr>";
			}
			if ($eden_cfg['show_admin_contact_shop_name'] == 1){
				echo "<tr>";
				echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_NAME."</strong></td>";
				echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_contact_shop_name\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_contact_shop_name'], ENT_QUOTES)."\"></td>";
				echo "</tr>";
			}
			if ($eden_cfg['show_admin_contact_shop_address_1'] == 1){
				echo "<tr>";
				echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_CONTACT_ADDRESS."</strong></td>";
				echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_contact_shop_address_1\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_contact_shop_address_1'], ENT_QUOTES)."\"></td>";
				echo "</tr>";
			}
			if ($eden_cfg['show_admin_contact_shop_address_2'] == 1){
				echo "<tr>";
				echo "	<td class=\"eden_users_td_description\">&nbsp;</td>";
				echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_contact_shop_address_2\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_contact_shop_address_2'], ENT_QUOTES)."\"></td>";
				echo "</tr>";
			}
			if ($eden_cfg['show_admin_contact_shop_postcode'] == 1){
				echo "<tr>";
				echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_CONTACT_POSTCODE."</strong></td>";
				echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_contact_shop_postcode\" size=\"12\" maxlength=\"11\" value=\"".htmlspecialchars($ar['admin_contact_shop_postcode'], ENT_QUOTES)."\"></td>";
				echo "</tr>";
			}
			if ($eden_cfg['show_admin_contact_shop_city'] == 1){
				echo "<tr>";
				echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_CONTACT_CITY."</strong></td>";
				echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_contact_shop_city\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_contact_shop_city'], ENT_QUOTES)."\">";
				echo "		<input type=\"hidden\" name=\"admin_contact_shop_use\" value=\"1\">";
				echo "	</td>";
				echo "</tr>";
			}
			if ($eden_cfg['show_admin_contact_shop_country'] == 1){
				echo "<tr>";
				echo "	<td class=\"eden_users_td_description\" valign=\"middle\"><strong>"._ADMIN_CONTACT_COUNTRY."</strong></td>";
				echo "	<td class=\"eden_users_td_data\"><select name=\"admin_contact_shop_country\" class=\"input\">";
				$res3 = mysql_query("SELECT country_id, country_shortname, country_name FROM $db_country ORDER BY country_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				while ($ar3 = mysql_fetch_array($res3)){
					if ($ar3['country_id'] == 237 /* Serbia and Montenegro */ ||
						$ar3['country_id'] == 240 /* Alp's States */ ||
						$ar3['country_id'] == 242 /* America */ ||
						$ar3['country_id'] == 241 /* Baltiic's States */ ||
						$ar3['country_id'] == 243 /* Benelux */ ||
						$ar3['country_id'] == 244 /* Skandinavia */ ||
						$ar3['country_id'] == 245 /* East */ ||
						$ar3['country_id'] == 246 /* Tibet */ ||
						$ar3['country_id'] == 247 /* European Union */ ||
						$ar3['country_id'] == 248 /* Scotland */ ||
						$ar3['country_id'] == 249 /* England */ ||
						$ar3['country_id'] == 251 /* Wales */ ||
						$ar3['country_id'] == 255 /* Jersey */ ||
						$ar3['country_id'] == 256 /* Isle of Man */ ||
						$ar3['country_id'] == 257 /* World */ ){
						/* Zde se zobrazujou staty a vlajky, ktere nenexistuji, nebo jsou soucasti nejakeho jineho celku (zeme jsou brany z vlajek */
					} else {
						echo "<option value=\"".$ar3['country_id']."\" "; if ($ar['admin_contact_shop_country'] == $ar3['country_id']) {echo " selected";} if ($_SESSION['useredit_mod'] == "user_reg" && $ar3['country_shortname'] == $ar_setup['setup_basic_country']){echo " selected";} echo ">".$ar3['country_name']."</option>"; }
					}
					echo "		</select>";
					echo "	</td>";
					echo "</tr>";
				}
				echo "<tr>";
				echo "	<td class=\"eden_users_td_description\"><br></td>";
				echo "	<td class=\"eden_users_td_data\"><br></td>";
				echo "</tr>";
			}
		/***************************************************************************************
		*
		*		ZOBRAZENI INFORMACI O KLANU
		*
		***************************************************************************************/
		if ($_SESSION['useredit_mod'] == "edit_user" && $ar_setup['setup_admin_show_clan'] == 1	&& AGet($_GET,'e_mode') != "open"){
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\">&nbsp;</td>\n";
			echo "	<td class=\"eden_users_td_data\"><h2>"._ADMIN_INFO_CLAN."</h2></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_CLANTAG."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_clan_tag\" size=\"40\" maxlength=\"50\" value=\"".htmlspecialchars($ar['admin_clan_tag'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_CLANNAME."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_clan_name\" size=\"40\" maxlength=\"255\" value=\"".htmlspecialchars($ar['admin_clan_name'], ENT_QUOTES)."\"></td>\n";
			echo "\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_CLAN_WWW."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_clan_www\" size=\"40\" maxlength=\"255\" value=\"".htmlspecialchars($ar['admin_clan_www'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_CLAN_IRC."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_clan_irc\" size=\"40\" maxlength=\"50\" value=\"".htmlspecialchars($ar['admin_clan_irc'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><br></td>\n";
			echo "	<td class=\"eden_users_td_data\"><br></td>\n";
			echo "</tr>";
		}
		/***************************************************************************************
		*
		*		ZOBRAZENI INFORMACI O HARDWARE
		*
		***************************************************************************************/
		if ($_SESSION['useredit_mod'] == "edit_user" && $ar_setup['setup_admin_show_hw'] == 1	&& AGet($_GET,'e_mode') != "open"){
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\">&nbsp;</td>\n";
			echo "	<td class=\"eden_users_td_data\" colspan=\"2\"><h2>"._ADMIN_INFO_HW."</h2></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_HW_CPU."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_hw_cpu\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_hw_cpu'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_HW_RAM."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_hw_ram\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_hw_ram'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_HW_MB."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_hw_mb\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_hw_mb'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_HW_HDD."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_hw_hdd\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_hw_hdd'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_HW_CD."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_hw_cd\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_hw_cd'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_HW_VGA."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_hw_vga\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_hw_vga'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_HW_SOUNDCARD."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_hw_soundcard\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_hw_soundcard'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_HW_MONITOR."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_hw_monitor\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_hw_monitor'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_HW_MOUSE."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_hw_mouse\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_hw_mouse'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_HW_MOUSEPAD."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_hw_mousepad\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_hw_mousepad'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_HW_HEADSET."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_hw_headset\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_hw_headset'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_HW_REPRO."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_hw_repro\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_hw_repro'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_HW_KEY."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_hw_key\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_hw_key'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_HW_GAMEPAD."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_hw_gamepad\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_hw_gamepad'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_HW_OS."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_hw_os\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_hw_os'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_HW_CONECTION."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_hw_conection\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_hw_conection'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_HW_BRAND_PC."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_hw_brand_pc\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_hw_brand_pc'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><br></td>\n";
			echo "	<td class=\"eden_users_td_data\"><br></td>\n";
			echo "</tr>";
		}
		/***************************************************************************************
		*
		*		ZOBRAZENI INFORMACI O HRE
		*
		***************************************************************************************/
		if ($_SESSION['useredit_mod'] == "edit_user" && $ar_setup['setup_admin_show_game'] == 1	&& AGet($_GET,'e_mode') != "open"){
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\">&nbsp;</td>\n";
			echo "	<td class=\"eden_users_td_data\"><h2>"._ADMIN_INFO_GAME."</h2></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_GAME_RESOLUTION."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><select name=\"admin_game_resolution\" class=\"input\">\n";
			echo "		<option value=\"\""; if ($ar['admin_game_resolution'] == "") {echo "selected=\"selected\"";} echo ">"._ADMIN_GAME_RESOLUTION_SELECT."</option>\n";
			echo "		<option value=\"320x200\""; if ($ar['admin_game_resolution'] == "320x200") {echo "selected=\"selected\"";} echo ">320x200</option>\n";
			echo "		<option value=\"640x480\""; if ($ar['admin_game_resolution'] == "640x480") {echo "selected=\"selected\"";} echo ">640x480</option>\n";
			echo "		<option value=\"720x480\""; if ($ar['admin_game_resolution'] == "720x480") {echo "selected=\"selected\"";} echo ">720x480</option>\n";
			echo "		<option value=\"720x576\""; if ($ar['admin_game_resolution'] == "720x576") {echo "selected=\"selected\"";} echo ">720x576</option>\n";
			echo "		<option value=\"800x480\""; if ($ar['admin_game_resolution'] == "800x480") {echo "selected=\"selected\"";} echo ">800x480</option>\n";
			echo "		<option value=\"800x600\""; if ($ar['admin_game_resolution'] == "800x600") {echo "selected=\"selected\"";} echo ">800x600</option>\n";
			echo "		<option value=\"848x480\""; if ($ar['admin_game_resolution'] == "848x480") {echo "selected=\"selected\"";} echo ">848x480</option>\n";
			echo "		<option value=\"960x720\""; if ($ar['admin_game_resolution'] == "960x720") {echo "selected=\"selected\"";} echo ">960x720</option>\n";
			echo "		<option value=\"1024x480\""; if ($ar['admin_game_resolution'] == "1024x480") {echo "selected=\"selected\"";} echo ">1024x480</option>\n";
			echo "		<option value=\"1024x578\""; if ($ar['admin_game_resolution'] == "1024x578") {echo "selected=\"selected\"";} echo ">1024x578</option>\n";
			echo "		<option value=\"1024x600\""; if ($ar['admin_game_resolution'] == "1024x600") {echo "selected=\"selected\"";} echo ">1024x600</option>\n";
			echo "		<option value=\"1024x768\""; if ($ar['admin_game_resolution'] == "1024x768") {echo "selected=\"selected\"";} echo ">1024x768</option>\n";
			echo "		<option value=\"1152x768\""; if ($ar['admin_game_resolution'] == "1152x768") {echo "selected=\"selected\"";} echo ">1152x768</option>\n";
			echo "		<option value=\"1152x864\""; if ($ar['admin_game_resolution'] == "1152x864") {echo "selected=\"selected\"";} echo ">1152x864</option>\n";
			echo "		<option value=\"1200x900\""; if ($ar['admin_game_resolution'] == "1200x900") {echo "selected=\"selected\"";} echo ">1200x900</option>\n";
			echo "		<option value=\"1280x600\""; if ($ar['admin_game_resolution'] == "1280x600") {echo "selected=\"selected\"";} echo ">1280x600</option>\n";
			echo "		<option value=\"1280x768\""; if ($ar['admin_game_resolution'] == "1280x768") {echo "selected=\"selected\"";} echo ">1280x768</option>\n";
			echo "		<option value=\"1280x800\""; if ($ar['admin_game_resolution'] == "1280x800") {echo "selected=\"selected\"";} echo ">1280x800</option>\n";
			echo "		<option value=\"1280x960\""; if ($ar['admin_game_resolution'] == "1280x960") {echo "selected=\"selected\"";} echo ">1280x960</option>\n";
			echo "		<option value=\"1280x1024\""; if ($ar['admin_game_resolution'] == "1280x1024") {echo "selected=\"selected\"";} echo ">1280x1024</option>\n";
			echo "		<option value=\"1360x1020\""; if ($ar['admin_game_resolution'] == "1360x1020") {echo "selected=\"selected\"";} echo ">1360x1020</option>\n";
			echo "		<option value=\"1366x768\""; if ($ar['admin_game_resolution'] == "1366x768") {echo "selected=\"selected\"";} echo ">1366x768</option>\n";
			echo "		<option value=\"1400x1050\""; if ($ar['admin_game_resolution'] == "1400x1050") {echo "selected=\"selected\"";} echo ">1400x1050</option>\n";
			echo "		<option value=\"1440x900\""; if ($ar['admin_game_resolution'] == "1440x900") {echo "selected=\"selected\"";} echo ">1440x900</option>\n";
			echo "		<option value=\"1520x1140\""; if ($ar['admin_game_resolution'] == "1520x1140") {echo "selected=\"selected\"";} echo ">1520x1140</option>\n";
			echo "		<option value=\"1600x900\""; if ($ar['admin_game_resolution'] == "1600x900") {echo "selected=\"selected\"";} echo ">1600x900</option>\n";
			echo "		<option value=\"1600x1200\""; if ($ar['admin_game_resolution'] == "1600x1200") {echo "selected=\"selected\"";} echo ">1600x1200</option>\n";
			echo "		<option value=\"1680x945\""; if ($ar['admin_game_resolution'] == "1680x945") {echo "selected=\"selected\"";} echo ">1680x945</option>\n";
			echo "		<option value=\"1680x1050\""; if ($ar['admin_game_resolution'] == "1680x1050") {echo "selected=\"selected\"";} echo ">1680x1050</option>\n";
			echo "		<option value=\"1792x1344\""; if ($ar['admin_game_resolution'] == "1792x1344") {echo "selected=\"selected\"";} echo ">1792x1344</option>\n";
			echo "		<option value=\"1800x1440\""; if ($ar['admin_game_resolution'] == "1800x1440") {echo "selected=\"selected\"";} echo ">1800x1440</option>\n";
			echo "		<option value=\"1856x1392\""; if ($ar['admin_game_resolution'] == "1856x1392") {echo "selected=\"selected\"";} echo ">1856x1392</option>\n";
			echo "		<option value=\"1920x1080\""; if ($ar['admin_game_resolution'] == "1920x1080") {echo "selected=\"selected\"";} echo ">1920x1080</option>\n";
			echo "		<option value=\"1920x1200\""; if ($ar['admin_game_resolution'] == "1920x1200") {echo "selected=\"selected\"";} echo ">1920x1200</option>\n";
			echo "		<option value=\"1920x1440\""; if ($ar['admin_game_resolution'] == "1920x1440") {echo "selected=\"selected\"";} echo ">1920x1440</option>\n";
			echo "		<option value=\"2048x1536\""; if ($ar['admin_game_resolution'] == "2048x1536") {echo "selected=\"selected\"";} echo ">2048x1536</option>\n";
			echo "		</select></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_GAME_M_SENS."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_game_mouse_sens\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_game_mouse_sens'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_GAME_M_ACCEL."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><select name=\"admin_game_mouse_accel\" class=\"input\">\n";
			echo "		<option value=\"1\""; if ($ar['admin_game_mouse_accel'] == 1) {echo "selected=\"selected\"";} echo ">"._CMN_YES."</option>\n";
			echo "		<option value=\"0\""; if ($ar['admin_game_mouse_accel'] == 0 || $ar['admin_game_mouse_accel'] == "") {echo "selected=\"selected\"";} echo ">"._CMN_NO."</option>\n";
			echo "		</select></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_GAME_M_INVERT."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><select name=\"admin_game_mouse_invert\" class=\"input\">\n";
			echo "		<option value=\"1\""; if ($ar['admin_game_mouse_invert'] == 1) {echo "selected=\"selected\"";} echo ">"._CMN_YES."</option>\n";
			echo "		<option value=\"0\""; if ($ar['admin_game_mouse_invert'] == 0 || $ar['admin_game_mouse_invert'] == "") {echo "selected=\"selected\"";} echo ">"._CMN_NO."</option>\n";
			echo "		</select></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\">&nbsp;</td>\n";
			echo "	<td class=\"eden_users_td_data\"><h2>"._ADMIN_INFO_FAV."</h2></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_FAV_WPN."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_game_fav_weapon\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_game_fav_weapon'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_FAV_TEAM."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_game_fav_team\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_game_fav_team'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_FAV_MAP."</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_game_fav_map\" size=\"40\" maxlength=\"80\" value=\"".htmlspecialchars($ar['admin_game_fav_map'], ENT_QUOTES)."\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><br></td>\n";
			echo "	<td class=\"eden_users_td_data\"><br></td>\n";
			echo "</tr>";
		}
		/***************************************************************************************
		*
		*		ZOBRAZENI INFORMACI O POKERU
		*
		***************************************************************************************/
		if ($_SESSION['useredit_mod'] == "edit_user" && $ar_setup['setup_admin_show_poker'] == 1 && AGet($_GET,'e_mode') != "open"){
			echo "<tr>";
			echo "	<td class=\"eden_users_td_description\">&nbsp;</td>";
			echo "	<td class=\"eden_users_td_data\"><h2>"._ADMIN_INFO_POKER."</h2></td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_POKER_FAV_VARIANTS."</strong></td>";
			echo "	<td class=\"eden_users_td_data\"><select name=\"admin_poker_fav_variants_1\" class=\"input\">";
			$fav_variants = explode ("||", $ar['admin_poker_fav_variants']);
			echo "		<option value=\"\" "; if ($fav_variants[0] == "") {echo " selected";} echo ">"._ADMIN_POKER_FAV_VARIANTS_SELECT."</option>";
			$res_variant = mysql_query("SELECT poker_variant_id, poker_variant_name FROM $db_poker_variants ORDER BY poker_variant_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while ($ar_variant = mysql_fetch_array($res_variant)){
				echo "<option value=\"".$ar_variant['poker_variant_id']."\""; if ($fav_variants[0] == $ar_variant['poker_variant_id']) {echo " selected";} echo ">".$ar_variant['poker_variant_name']."</option>\n";
			}
			echo "	</select></td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td class=\"eden_users_td_description\">&nbsp;</td>";
			echo "	<td class=\"eden_users_td_data\"><select name=\"admin_poker_fav_variants_2\" class=\"input\">";
			echo "		<option value=\"\" "; if ($fav_variants[1] == "") {echo " selected";} echo ">"._ADMIN_POKER_FAV_VARIANTS_SELECT."</option>";
			$res_variant = mysql_query("SELECT poker_variant_id, poker_variant_name FROM $db_poker_variants ORDER BY poker_variant_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while ($ar_variant = mysql_fetch_array($res_variant)){
				echo "<option value=\"".$ar_variant['poker_variant_id']."\""; if ($fav_variants[1] == $ar_variant['poker_variant_id']) {echo " selected";} echo ">".$ar_variant['poker_variant_name']."</option>\n";
			}
			echo "	</select></td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td class=\"eden_users_td_description\">&nbsp;</td>";
			echo "	<td class=\"eden_users_td_data\"><select name=\"admin_poker_fav_variants_3\" class=\"input\">";
			echo "		<option value=\"\" "; if ($fav_variants[2] == "") {echo " selected";} echo ">"._ADMIN_POKER_FAV_VARIANTS_SELECT."</option>";
			$res_variant = mysql_query("SELECT poker_variant_id, poker_variant_name FROM $db_poker_variants ORDER BY poker_variant_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while ($ar_variant = mysql_fetch_array($res_variant)){
				echo "<option value=\"".$ar_variant['poker_variant_id']."\""; if ($fav_variants[2] == $ar_variant['poker_variant_id']) {echo " selected";} echo ">".$ar_variant['poker_variant_name']."</option>\n";
			}
			echo "	</select></td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_POKER_FAV_CARDROOMS."</strong></td>";
			echo "	<td class=\"eden_users_td_data\"><select name=\"admin_poker_fav_cardroom_1\" class=\"input\">";
			$fav_cardrooms = explode ("||", $ar['admin_poker_fav_cardrooms']);
			echo "<option value=\"\" "; if ($fav_cardrooms[0] == "") {echo " selected";} echo ">"._ADMIN_POKER_FAV_CARDROOM_SELECT."</option>";
			$res_cardroom = mysql_query("SELECT poker_cardroom_id, poker_cardroom_name FROM $db_poker_cardrooms ORDER BY poker_cardroom_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while ($ar_cardroom = mysql_fetch_array($res_cardroom)){
				echo "<option value=\"".$ar_cardroom['poker_cardroom_id']."\""; if ($fav_cardrooms[0] == $ar_cardroom['poker_cardroom_id']) {echo " selected";} echo ">".$ar_cardroom['poker_cardroom_name']."</option>\n";
			}
			echo "	</select></td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td class=\"eden_users_td_description\">&nbsp;</td>";
			echo "	<td class=\"eden_users_td_data\"><select name=\"admin_poker_fav_cardroom_2\" class=\"input\">";
			echo "		<option value=\"\" "; if ($fav_cardrooms[1] == "") {echo " selected";} echo ">"._ADMIN_POKER_FAV_CARDROOM_SELECT."</option>";
			$res_cardroom = mysql_query("SELECT poker_cardroom_id, poker_cardroom_name FROM $db_poker_cardrooms ORDER BY poker_cardroom_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while ($ar_cardroom = mysql_fetch_array($res_cardroom)){
				echo "<option value=\"".$ar_cardroom['poker_cardroom_id']."\""; if ($fav_cardrooms[1] == $ar_cardroom['poker_cardroom_id']) {echo " selected";} echo ">".$ar_cardroom['poker_cardroom_name']."</option>\n";
			}
			echo "	</select></td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td class=\"eden_users_td_description\">&nbsp;</td>";
			echo "	<td class=\"eden_users_td_data\"><select name=\"admin_poker_fav_cardroom_3\" class=\"input\">";
			echo "		<option value=\"\" "; if ($fav_cardrooms[2] == "") {echo " selected";} echo ">"._ADMIN_POKER_FAV_CARDROOM_SELECT."</option>";
			$res_cardroom = mysql_query("SELECT poker_cardroom_id, poker_cardroom_name FROM $db_poker_cardrooms ORDER BY poker_cardroom_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while ($ar_cardroom = mysql_fetch_array($res_cardroom)){
				echo "<option value=\"".$ar_cardroom['poker_cardroom_id']."\""; if ($fav_cardrooms[2] == $ar_cardroom['poker_cardroom_id']) {echo " selected";} echo ">".$ar_cardroom['poker_cardroom_name']."</option>\n";
			}
			echo "		</select>";
			echo "	</td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_POKER_FAV_PLAYER."</strong></td>";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_poker_fav_player\" size=\"40\" maxlength=\"60\" value=\"".htmlspecialchars($ar['admin_poker_fav_player'], ENT_QUOTES)."\"></td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td class=\"eden_users_td_description\"><br></td>";
			echo "	<td class=\"eden_users_td_data\"><br></td>";
			echo "</tr>";
		}
		/***************************************************************************************
		*
		*		ZOBRAZENI INFORMACI O UZIVATELI
		*
		***************************************************************************************/
		if ($_SESSION['useredit_mod'] == "edit_user" && $ar_setup['setup_admin_show_info'] == 1	&& AGet($_GET,'e_mode') != "open"){
			echo "	<tr>\n";
			echo "		<td class=\"eden_users_td_description\">&nbsp;</td>\n";
			echo "		<td class=\"eden_users_td_data\"><h2>"._ADMIN_INFO_BASIC."</h2></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"eden_users_td_description\" valign=\"top\"><strong>"._ADMIN_USERINFO."</strong></td>\n";
			echo "		<td class=\"eden_users_td_data\"><textarea cols=\"40\" rows=\"5\" name=\"admin_userinfo\">".htmlspecialchars($ar['admin_userinfo'], ENT_QUOTES)."</textarea>\n";
			echo "	</tr>\n";
			echo "</table>\n";
			echo "<table cellspacing=\"0\" cellpadding=\"4\" class=\"eden_users_table\" border=\"0\">\n";
			echo "	<tr>\n";
			echo "		<td class=\"eden_users_td_description\" valign=\"top\">"._ADMIN_SIGNATURE."</td>\n";
			echo "		<td style=\"width:350px;\">";
			$sig_editor = new PostEditor;
			$sig_editor->editor_name = "admin_info_sig";
			$sig_editor->form_name = "user_edit";
			$sig_editor->form_text = $ar['admin_info_sig_bb'];
			$sig_editor->table_width = "350";
			$sig_editor->textarea_width = "350";
			$sig_editor->textarea_rows = "15";
			$sig_editor->textarea_cols = "50";
			echo $sig_editor->BBEditor(); 
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "</table>\n";
			echo "<table cellspacing=\"0\" cellpadding=\"4\" class=\"eden_users_table\" border=\"0\">\n";
			echo "	<tr>\n";
			echo "		<td class=\"eden_users_td_description\"><strong>"._DISKHITS."</strong></td>\n";
			echo "		<td class=\"eden_users_td_data\"><input type=\"text\" name=\"admin_hits\" size=\"5\" value=\"".htmlspecialchars($ar['admin_hits'], ENT_QUOTES)."\"></td>\n";
			echo "	</tr>\n";
			echo "	 <tr>\n";
			echo "		<td class=\"eden_users_td_description\" valign=\"top\"><strong>"._ADMIN_AVATAR."</strong></td>\n";
			echo "		<td class=\"eden_users_td_data\"><input type=\"file\" name=\"admin_userimage\" size=\"40\"><br>\n";
			echo "		"._ADMIN_AVATAR_SIZE." <strong>".GetSetupImageInfo("avatar","width")."</strong> x <strong>".GetSetupImageInfo("avatar","height")."</strong><br>"._ADMIN_AVATAR_MAX_FILESIZE." <strong>".GetSetupImageInfo("avatar","filesize")."</strong> "._CMN_BYTES."<br>"._ADMIN_AVATAR_SUPPORTED_IMAGETYPE."\n";
			echo "		<br>"; if ($ar['admin_userimage'] != "NULL" && $ar['admin_userimage'] != "") { echo "<img src=\"".$url_admins.$ar['admin_userimage']."\" border=\"0\" align=\"top\">"; } echo "</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"eden_users_td_data\" colspan=\"2\" valign=\"top\"></td>\n";
			echo "	</tr>";
		}
		$res_language = mysql_query("SELECT language_code FROM $db_language ORDER BY language_code") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num_langague = mysql_num_rows($res_language);
		if ($num_langague > 1	&& AGet($_GET,'e_mode') != "open"){
			echo " <tr>";
			echo "	<td align=\"right\" valign=\"middle\"><strong>"._ADMIN_LANG."</strong></td>";
			echo "	<td align=\"left\"><select name=\"admin_lang\" class=\"input\">"; 
						while ($ar_language = mysql_fetch_array($res_language)){
							echo "<option value=\"".$ar_language['language_code']."\" "; if ($ar['admin_lang'] == $ar_language['language_code']) {echo " selected";} echo ">".$ar_language['language_code']."</option>";
						}
			echo "		</select></td>";
			echo "</tr>";
		} elseif ($num_langague == 0){
			echo "<input type=\"hidden\" name=\"admin_lang\" value=\"".$ar_setup['setup_basic_lang']."\">";
		} else {
			$ar_language = mysql_fetch_array($res_language);
			echo "<input type=\"hidden\" name=\"admin_lang\" value=\"".$ar_language['language_code']."\">";
		}
	}
	/***************************************************************************************
	*
	*		AUTOLOGIN A DALSI VECI K NASTAVENI WEBU
	*
	***************************************************************************************/
	if (($_SESSION['useredit_mod'] == "edit_user" || $_SESSION['useredit_mod'] == "user_reg" && $eden_cfg['show_admin_other_settings_heading'] == 1) && AGet($_GET,'e_mode') != "open"){
		echo "<tr>";
		echo "	<td class=\"eden_users_td_description\">&nbsp;</td>";
		echo "	<td class=\"eden_users_td_data\"><h2>"._ADMIN_OTHER_SETTINGS."</h2></td>";
		echo "</tr>";
	}
	if (($_SESSION['useredit_mod'] == "edit_user" || $_SESSION['useredit_mod'] == "user_reg") && AGet($_GET,'e_mode') != "open"){
		echo "<tr>\n";
		echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_AUTOLOGIN."</strong></td>\n";
		echo "	<td class=\"eden_users_td_data\"><input type=\"checkbox\" name=\"admin_autologin\" value=\"1\" "; if ($admin_autologin == 1){echo "checked";} echo "> <a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('"._ADMIN_HINT_AUTOLOGIN."', this, event, '200px')\">[' ? ']</a></td>\n";
		echo "</tr>";
		echo "<tr>\n";
		echo "	<td class=\"eden_users_td_description\"><strong>"._ADMIN_AGREE_EMAIL."</strong></td>\n";
		echo "	<td class=\"eden_users_td_data\"><input type=\"checkbox\" name=\"admin_agree_email\" value=\"1\" "; if ($admin_agree_email == 1){echo "checked";} echo "></td>\n";
		echo "</tr>";
	} else {
		echo "<input type=\"hidden\" name=\"admin_autologin\" value=\"".(integer)$admin_autologin."\">"; 
	}
	$res_filter = mysql_query("SELECT filter_shortname, filter_name FROM $db_filters ORDER BY filter_name") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num_filter = mysql_num_rows($res_filter);
	if ($_SESSION['useredit_mod'] == "edit_user" && $num_filter > 0	&& AGet($_GET,'e_mode') != "open"){
		echo "<tr>\n";
		echo "	<td align=\"right\" valign=\"top\"><strong>"._ADMIN_FILTERS."</strong></td>\n";
		echo "	<td align=\"left\">\n";
			$filter_selected = explode ("||", $ar['admin_info_filter']);
			while ($ar_filter = mysql_fetch_array($res_filter)){
				echo "<input type=\"checkbox\" name=\"admin_filter[]\" value=\"".$ar_filter['filter_shortname']."\" "; if (in_array($ar_filter['filter_shortname'],$filter_selected)){echo "checked";} echo "> "; $filter_name = explode ("]", $ar_filter['filter_name']); if (AGet($filter_name,1) != ""){echo $filter_name[1];} else {echo $filter_name[0];} echo "<br>";
			}
		echo "</td>\n";
		echo "</tr>\n";
	}
	if ($_SESSION['useredit_mod'] == "user_reg"){
		@mysql_data_seek($res_filter,0);
		while ($ar_filter = mysql_fetch_array($res_filter)){
			echo '<input type="hidden" name="admin_filter[]" value="'.$ar_filter['filter_shortname'].'">';
		}
	}
	if ($_SESSION['useredit_mod'] == "edit_user" || $_SESSION['useredit_mod'] == "user_reg"	|| $_SESSION['useredit_mod'] == "shop_check_del_address" || $ar['admin_contact_address_1'] == "" || $ar['admin_contact_address_2'] == "" || $ar['admin_contact_postcode'] == "" || $ar['admin_contact_city'] == ""){
		if (($_SESSION['useredit_mod'] == "shop_show") && ($ar['admin_contact_address_1'] == "" || $ar['admin_contact_postcode'] == "" || $ar['admin_contact_city'] == "")){
			if ($_GET['mode'] != "shop_user_edit"){$_GET['mode'] = "edit_user";}
			$shop_echo = "true";
		}
		if ($_SESSION['useredit_mod'] == "user_reg"){
			echo "<tr>\n";
			echo "	<td class=\"eden_users_td_description\"><span style=\"color: #ff0000; font-weight: bold;\">*</span> <strong>Captcha</strong></td>\n";
			echo "	<td class=\"eden_users_td_data\">";
			$eden_captcha = new EdenCaptcha($eden_cfg);
			echo $eden_captcha->CaptchaShow();
			echo "	</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td colspan=\"2\"><br><br><br>".PrepareFromDB($ar_setup_lang['setup_lang_reg_help'],1)."<br><br>".PrepareFromDB($ar_setup_lang['setup_lang_reg_terms'],1)."<br><br>"; if ($ar_setup['setup_reg_agreement'] == 1){ echo "<input type=\"checkbox\" name=\"admin_agreement\" value=\"1\"> "._ADMIN_AGREEMENT; } echo "<br><br></td>\n";
			echo "</tr>";
		}
		echo "<tr>\n";
		echo "	<td align=\"left\" colspan=\"2\">"; if ($shop_echo == "true"){ echo "<div align=\"center\" class=\"edenshop_error\">"._ADMIN_EDENSHOP_ERR_FILL_ALL."</div>"; }
		echo "		<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "		<input type=\"hidden\" name=\"action_shop\" value=\"".AGet($_GET,'action_shop')."\">\n";
		echo "		<input type=\"hidden\" name=\"mode\" value=\""; if ($_GET['mode'] == "" || $_GET['mode'] == "reg"){echo "register_user";} else {echo "edit_user";} echo "\">\n";
		echo "		<input type=\"hidden\" name=\"id\" value=\"".$ar['admin_id']."\">\n";
		echo "		<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "		<input type=\"submit\" value=\""; if ($_GET['mode'] == "" || $_GET['mode'] == "reg"){ echo _CMN_SUBMIT_REG;} else {echo _CMN_SUBMIT;} echo "\" class=\"eden_button\">\n";
		echo "	</td>";
		echo "</tr>"; 
	}
	echo "</table>";
	if ($_SESSION['useredit_mod'] == "edit_user" || $_SESSION['useredit_mod'] == "user_reg" || $_SESSION['useredit_mod'] == "shop_check_del_address" || $ar['admin_contact_address_1'] == "" || $ar['admin_contact_address_2'] == "" || $ar['admin_contact_postcode'] == "" || $ar['admin_contact_city'] == ""){
		echo "</form>\n";
	}
}