<?php
$eden_cfg['www_dir'] = dirname(__FILE__);
$eden_cfg['www_dir_cms'] = $eden_cfg['www_dir']."/";
$eden_cfg['www_dir_lang'] = "../lang/";
$eden_cfg['ip'] = $_SERVER["REMOTE_ADDR"];
require_once($eden_cfg['www_dir_cms']."eden_init.php");
/***********************************************************************************************************
*
*		ZAPIS DAT DO DATABAZE
*
***********************************************************************************************************/
$_GET['faction'] = AGet($_GET,'faction');
$_GET['lang'] = AGet($_GET,'lang');
$_GET['mode'] = AGet($_GET,'mode');

if ($_GET['lang'] == ""){$_GET['lang'] = "cz";}
require_once("./eden_lang_".$_GET['lang'].".php");
if ($project != ""){/* Nic se nedeje */}elseif($_SESSION['project'] != ""){$project = $_SESSION['project'];}elseif($_GET['project'] != ""){$project = $_GET['project'];} else {$project = $_POST['project'];}
require_once("./db.".$project.".inc.php");
// Rekne ze sessions bylo volano z eden_save.php a pokud bude vyprseno, neodhlasi klienta
$edensave = 1;
require("./sessions.php");
require("./functions_frontend.php");
require("./eden_forum.php");
require("./class.mail.php");
	// Výcet povolených tagu
	$allowtags = "<embed>, <marquee>, <blink>, <hr>, <ul>, <li>, <ol>, <p>, <br>, <br>, <font>, <b>, <u>, <i>, <small>, <big>, <strong>, <em>, <a>";
	// Z obsahu promenné body vyjmout nepovolené tagy
	$name = strip_tags($_POST['name'],$allowtags);
	$email = strip_tags(strtolower($_POST['email']),$allowtags);
	$topic = strip_tags($_POST['topic'],$allowtags);
	$comments = strip_tags($_POST['comments'],$allowtags);
	$_POST['odkaz'] = str_replace( "&amp;","&",$_POST['odkaz']);
	$comments = str_ireplace("\n","<br>",$comments);
	$comments = ConvertBracketLinks($comments, 3);
	$date = date("YmdHis");
	// Nacteme $db_setup
	$res_setup = mysql_query("SELECT s.*, sl.* FROM $db_setup AS s, $db_setup_lang AS sl WHERE sl.setup_lang='".mysql_real_escape_string($_GET['lang'])."'") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	if ($eden_cfg['modul_shop'] == 1){
		$res_shop_setup = mysql_query("SELECT * FROM $db_shop_setup") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar_shop_setup = mysql_fetch_array($res_shop_setup);
	}
	// Pokud neni text shodny tak se vse ulozi (kontrola nefunguje pokud uz nekdo neco vlozil)
	/***********************************************************************************************************
	*
	*		KOMENTARE
	*
	***********************************************************************************************************/
	if ($_POST['mode'] == "comments"){
		$eden_captcha = new EdenCaptcha($eden_cfg);
		if ($eden_captcha->CaptchaCheck() === TRUE || ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin")) {
			if ($name != "" && $comments != ""){
				if ($_POST['reg_user_comm'] == "0"){$reg_user_comm = "0";} else {$reg_user_comm = $_SESSION['loginid'];}
				$res = mysql_query("INSERT INTO $db_comments 
				VALUES('',
				'".(integer)$_POST['id']."',
				'".mysql_real_escape_string($name)."',
				'".mysql_real_escape_string($email)."',
				NOW(),
				'".mysql_real_escape_string($topic)."',
				'".mysql_real_escape_string($comments)."',
				'".mysql_real_escape_string($_POST['modul'])."',
				INET_ATON('".mysql_real_escape_string($eden_cfg['ip'])."'),
				'".(integer)$reg_user_comm."',
				'1',
				'0',
				'0')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				if ($_SESSION['loginid'] != ""){
					/* Pricteme +1 k poctu zaslanych komentáru */
					mysql_query("UPDATE $db_admin SET admin_posts_comments = admin_posts_comments + 1 WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				}
				unset($name,$email,$topic,$comments);
			}
		} else {
			// What happens when the CAPTCHA was entered incorrectly
			header ("Location: http://".$_POST['odkaz']."&cmsg=bad_captcha&n=".$name."&e=".$email."&t=".$topic."&c=".$comments."#form");
			exit;
		}
		
		if ($_POST['modul'] == "clanwars"){
			$stav = "open";
			$id_cw = $_POST['id'];
			header ("Location: http://".$_POST['odkaz']."");
			exit;
			//ClanWars();
		} elseif ($_POST['modul'] == "download"){
			$stav = "open";
			$did = $_POST['id'];
			header ("Location: http://".$_POST['odkaz']."&stav=open&did=".$_POST['id']."#".$_POST['id']);
			exit;
			//Download();
		} elseif ($_POST['modul'] == "user"){
			header ("Location: http://".$_POST['odkaz']);
			exit;
		} else {
			header ("Location: http://".$_POST['odkaz']);
			exit;
			//Comments($_POST['id'],$_POST['modul']);
		}
	/***********************************************************************************************************
	*
	*		ODKAZY
	*
	*		Jako obrazek se pouziva links_picture2
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "addlink"){
		// pouze pokud neni zadany userstring (fake captcha)
		if (!$_POST['userstring']) {
			
			if ($_POST['addlink_name'] == ""){
				header ("Location: ".$eden_cfg['url']."index.php?action=teams_list&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=links_noname");
				exit;
			} elseif ($_POST['addlink_link'] == ""){
				header ("Location: ".$eden_cfg['url']."index.php?action=teams_list&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=links_nolink");
				exit;
			} elseif ($_POST['addlink_desc'] == ""){
				header ("Location: ".$eden_cfg['url']."index.php?action=teams_list&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=links_nodesc");
				exit;
			} elseif ($_FILES['addlink_img']['name'] == ""){
				header ("Location: ".$eden_cfg['url']."index.php?action=teams_list&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=links_noimg");
				exit;
			} elseif (($links_img = getimagesize($_FILES['addlink_img']['tmp_name'])) != false){
				/* Zjistime zda neni soubor jineho typu nez je povoleno a pokud ne priradime extenzi */
				if ($links_img[2] == 1){
					$extenze = ".gif";
				} elseif ($links_img[2] == 2){
					$extenze = ".jpg";
				} elseif ($links_img[2] == 3){
					$extenze = ".png";
				} else {
					header ("Location: ".$eden_cfg['url']."index.php?action=teams_list&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=ftp_av_wft");
					exit;
				}
				/* Zjistime zda neni obrazek mensi, nez je povoleno */
				if ($links_img[0] < GetSetupImageInfo("link_1","width") || $links_img[1] < GetSetupImageInfo("link_1","height")){
					header ("Location: ".$eden_cfg['url']."index.php?action=teams_list&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=ftp_av_ts");
					exit;
				/* Zjistime zda neni obrazek vetsi, nez je povoleno */
				} elseif ($links_img[0] > GetSetupImageInfo("link_2","width") || $links_img[1] > GetSetupImageInfo("link_2","height")){
					header ("Location: ".$eden_cfg['url']."index.php?action=teams_list&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=ftp_av_tb");
					exit;
				/* Zjistime zda neni soubor vetsi nez je povoleno */
				} elseif ($_FILES['addlink_img']['size'] > GetSetupImageInfo("link_2","filesize")){
					header ("Location: ".$eden_cfg['url']."index.php?action=teams_list&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=ftp_av_ftb");
					exit;
				} else {
					/* Spojeni s FTP serverem */
					$conn_id = ftp_connect($eden_cfg['ftp_server']);
					/* Prihlaseni pres uzivatelske jmeno a heslo na FTP server */
					$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
					ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
					
					/* Zjisteni stavu spojeni */
					if ((!$conn_id) || (!$login_result)){
						header ("Location: ".$eden_cfg['url']."index.php?action=teams_list&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=ftp_no_connection");
						exit;
					}
					/* Zjisti nazev souboru po ulozeni do docasneho adresare na serveru */
					$source_file = $_FILES['addlink_img']['tmp_name'];
					$userfile_name = Cislo().strtolower($extenze);
					/* Vlozi nazev souboru a cestu do konkretniho adresare */
					$destination_file = $ftp_path_links.$userfile_name;
					$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);

					/* Zjisteni stavu uploadu */
					if (!$upload){
						/* Uzavreni komunikace se serverem */
						ftp_close($conn_id);
						header ("Location: ".$eden_cfg['url']."index.php?action=teams_list&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=ftp_ue");
						exit;
					} else {
						/* Pokud vse probehne jak ma znicime dane promenne a nastavime $checkupload */
						unset($source_file);
						unset($destination_file);
						unset($extenze);
						unset($rss_channel_img);
						unset($_FILES['addlink_img']);
						$checkupload = 1;
					}
					$allowtags = "";
					// Z obsahu promenné body vyjmout nepovolené tagy
					$addlink_name = strip_tags($_POST['addlink_name'],$allowtags);
					$addlink_link = strip_tags($_POST['addlink_link'],$allowtags);
					$addlink_desc = strip_tags($_POST['addlink_desc'],$allowtags);
					$addlink_link = str_replace("https://", "", $addlink_link);
					$addlink_link = str_replace("http://", "", $addlink_link);
					$addlink_link = str_replace("ftp://", "", $addlink_link);

					$res = mysql_query("INSERT INTO $db_links VALUES ('','".mysql_real_escape_string($addlink_name)."','".mysql_real_escape_string($addlink_link)."','','".mysql_real_escape_string($userfile_name)."','".mysql_real_escape_string($addlink_desc)."',0,0,'0','0','".(float)$_POST['cid']."','0','0')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());

					header ("Location: ".$eden_cfg['url']."index.php?action=teams_list&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=add_link_ok");
					exit;
				}
			} else {
				header ("Location: ".$eden_cfg['url']."index.php?action=teams_list&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=links_noimg");
				exit;
			}
		} else {
			header ("Location: ".$eden_cfg['url']."index.php?action=teams_list&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=bad_captcha");
			exit;
		}
	/***********************************************************************************************************
	*
	*		GUESTBOOK
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "secretgb" || $_POST['mode'] == "guestbook"){
		// CAPTCHA
		$eden_captcha = new EdenCaptcha($eden_cfg);
		if ($eden_captcha->CaptchaCheck() === TRUE || ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin")) {
			$res = mysql_query("INSERT INTO $db_guestbook VALUES('','".(float)$_POST['id']."','".mysql_real_escape_string($name)."','".mysql_real_escape_string($email)."','".(float)$date."','".mysql_real_escape_string($topic)."','".mysql_real_escape_string($comments)."','".mysql_real_escape_string($eden_cfg['ip'])."')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			if ($_SESSION['loginid'] != ""){
				/* Pricteme +1 k poctu zaslanych prispevku do guestbooku */
				mysql_query("UPDATE $db_admin SET admin_posts_guestbook = admin_posts_guestbook + 1 WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			}
			unset($name,$email,$topic,$comments);
			header ("Location: http://".$_POST['odkaz']);
			exit;
		} else {
			// What happens when the CAPTCHA was entered incorrectly
			header ("Location: http://".$_POST['odkaz']."&cmsg=bad_captcha&n=".$name."&e=".$email."&t=".$topic."&c=".$comments."");
			exit;
		}
	/***********************************************************************************************************
	*
	*		ADMIN GUESTBOOK
	*
	***********************************************************************************************************/
	}elseif ($_POST['mode'] == "admin_gb"){
		if ($_SESSION['loginid'] != "") {
			$res = mysql_query("INSERT INTO $db_guestbook_admin VALUES('','".(float)$_POST['id']."','".(float)$_SESSION['loginid']."',NOW(),'".mysql_real_escape_string($topic)."','".mysql_real_escape_string($comments)."')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());

			/* Pricteme +1 k poctu zaslanych prispevku do guestbooku */
			mysql_query("UPDATE $db_admin SET admin_posts_guestbook = admin_posts_guestbook + 1 WHERE admin_id=".(float)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());

			unset($topic,$comments);
			header ("Location: http://".$_POST['odkaz']);
			exit;
		} else {
			unset($topic,$comments);
			header ("Location: http://".$_POST['odkaz']);
			exit;
		}
		//Guestbook($_POST['id'],$width,$align);
	/***********************************************************************************************************
	*
	*		REGISTRACE A EDITACE
	*
	***********************************************************************************************************/
	/*
	}elseif ($_POST['mode'] == "registrace_hracu" || $_POST['mode'] == "editace_hracu"){
		$allowtags = "";
		// Z obsahu promenné body vyjmout nepovolené tagy
		$admin_nick = strip_tags($admin_nick,$allowtags);
		$admin_firstname = strip_tags($admin_first,$allowtags);
		$admin_name = strip_tags($admin_name,$allowtags);
		$icq = strip_tags($icq,$allowtags);
		$admin_email = strip_tags($admin_email,$allowtags);
		$klan = strip_tags($klan,$allowtags);
		$cod_pbguid = strip_tags($cod_pbguid,$allowtags);

		$admin_nick = mysql_real_escape_string($admin_nick);
		$admin_firstname = mysql_real_escape_string($admin_firstname);
		$admin_name = mysql_real_escape_string($admin_name);
		$icq = mysql_real_escape_string($icq);
		$admin_email = mysql_real_escape_string($admin_email);
		$klan = mysql_real_escape_string($klan);
		$cod_pbguid = mysql_real_escape_string($cod_pbguid);
		if ($_POST['mode'] == "registrace_hracu"){
			$res = mysql_query("SELECT * FROM $db_liga_player WHERE nick='".mysql_real_escape_string($nick)."' AND jmeno='".mysql_real_escape_string($jmeno)."' AND prijmeni='".mysql_real_escape_string($prijmeni)."'");
			$num = mysql_num_rows($res);
			if ($num > 0){
				header ("Location: http://".$_POST['odkaz']."&msg=replynames");
				exit;
			} else {
				mt_srand((double)microtime()*1000000); // inicializácia generátora náhodných císiel

				$pass = GeneratePass(8);
				$p = MD5($pass);
				// Pokud dopadlo vse v poradku muze se zalozit novy uzivatel
				mysql_query("INSERT INTO $db_liga_player VALUES('','".mysql_real_escape_string($nick)."','".mysql_real_escape_string($name)."','".mysql_real_escape_string($prijmeni)."','".mysql_real_escape_string($p)."','".mysql_real_escape_string($icq)."','".mysql_real_escape_string($email)."','','','','','".mysql_real_escape_string($cod_pbguid)."','','','','','','','','','','','','','','','','','','','','','')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());


				$mail = new PHPMailer();
				$mail->CharSet = "utf-8";
				$mail->From = "autorizace@esuba.net";
				$mail->FromName = "eSuba";
				$mail->AddAddress($email);
				$mail->Mailer = "sendmail";

				$mail->Subject = $subj;
				$mail->Body = _REG_INFO."\r\n
					"._REG_USERNAME." $nick\r\n	"._REG_PASS." $pass\r\n
					"._REG_THX."\r\n http://$eden_cfg['misc_web']\r\n";
				$mail->WordWrap = 100;

				if (!$mail->Send()){
				header ("Location: http://".$_POST['odkaz']."&msg=reg_er");
				exit;
				} else {
				header ("Location: http://".$_POST['odkaz']."&msg=reg_ok");
				exit;
				}
			}
		}
		if ($_POST['mode'] == "editace_hracu"){
			$res = mysql_query("SELECT * FROM $db_liga_player WHERE id=".(float)$player_id."");
			$ar = mysql_fetch_array($res);
			if ($pass == ""){$p = $ar['password'];} else {$p = MD5($pass);}
			mysql_query("UPDATE $db_liga_player SET  nick='".mysql_real_escape_string($nick)."', name='".mysql_real_escape_string($name)."', prijmeni='".mysql_real_escape_string($prijmeni)."', password='".mysql_real_escape_string($p)."', icq='".mysql_real_escape_string($icq)."', email='".mysql_real_escape_string($email)."', team='".mysql_real_escape_string($klan)."', cod_pbguid='".mysql_real_escape_string($cod_pbguid)."' WHERE id=".(float)$player_id) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$number = count($pozice);
			$res = mysql_query("SELECT * FROM $db_liga_player WHERE team=".(float)$team_id." AND team_confirm != 0") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			while($ar = mysql_fetch_array($res)){
				$position = $pozice[$ar['id']][0];
				mysql_query("UPDATE $db_liga_player SET pozice='".mysql_real_escape_string($position)."' WHERE id='$ar[id]'") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			}
			if ($team_confirm != ""){
				$number = count($team_confirm);
				$i=0;
				while($number > $i){
					mysql_query("UPDATE $db_liga_player SET team_confirm='1' WHERE id='$team_confirm[$i]'") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					$i++;
				}
			}
			if ($team_del_player != ""){
				$number = count($team_del_player);
				$i=0;
				while($number > $i){
					mysql_query("UPDATE $db_liga_player SET team_confirm='0', team='0', pozice='0' WHERE id='$team_del_player[$i]'") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					$i++;
				}
			}
			header ("Location: http://".$_POST['odkaz']."&msg=edit_ok");
			exit;
		}
	*/
	/***********************************************************************************************************
	*
	*		REGISTRACE A EDITACE ADMINU/UZIVATELU
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "register_user" || $_POST['mode'] == "edit_user"){
		$allowtags = "";
		// Z obsahu promenné body vyjmout nepovolené tagy
		$admin_username = PrepareForDB($_POST['admin_username'],1,"",0);
		$admin_name = PrepareForDB($_POST['admin_name'],1,"",0);
		$admin_firstname = PrepareForDB($_POST['admin_firstname'],1,"",0);
		$admin_nick = PrepareForDB($_POST['admin_nick'],1,"",0);
		$admin_email = PrepareForDB(strtolower($_POST['admin_email']),1,"",0);
		$admin_email_repeat = PrepareForDB(strtolower($_POST['admin_email_repeat']),1,"",0);
		$admin_userinfo = PrepareForDB($_POST['admin_userinfo'],1,"",0);
		$admin_hits = PrepareForDB($_POST['admin_hits'],1,"",0);
		$admin_lang = PrepareForDB($_POST['admin_lang'],1,"",0);
		$admin_title = PrepareForDB($_POST['admin_title'],1,"",0);
		$admin_autologin = $_POST['admin_autologin'];

		$admin_contact_telefon = PrepareForDB($_POST['admin_contact_telefon'],1,"",0);
		$admin_contact_mobil = PrepareForDB($_POST['admin_contact_mobil'],1,"",0);
		$admin_contact_icq = PrepareForDB($_POST['admin_contact_icq'],1,"",0);
		$admin_contact_msn = PrepareForDB($_POST['admin_contact_msn'],1,"",0);
		$admin_contact_aol = PrepareForDB($_POST['admin_contact_aol'],1,"",0);
		$admin_contact_skype = PrepareForDB($_POST['admin_contact_skype'],1,"",0);
		$admin_contact_xfire = PrepareForDB($_POST['admin_contact_xfire'],1,"",0);
		$admin_contact_web = PrepareForDB($_POST['admin_contact_web'],1,"",0);
		$admin_contact_web2 = PrepareForDB($_POST['admin_contact_web2'],1,"",0);
		$admin_contact_web3 = PrepareForDB($_POST['admin_contact_web3'],1,"",0);
		$admin_contact_web4 = PrepareForDB($_POST['admin_contact_web4'],1,"",0);
		$admin_contact_city = PrepareForDB($_POST['admin_contact_city'],1,"",0);
		$admin_contact_companyname = PrepareForDB($_POST['admin_contact_companyname'],1,"",0);
		$admin_contact_address_1 = PrepareForDB($_POST['admin_contact_address_1'],1,"",0);
		$admin_contact_address_2 = PrepareForDB($_POST['admin_contact_address_2'],1,"",0);
		$admin_contact_postcode = PrepareForDB($_POST['admin_contact_postcode'],1,"",0);
		$admin_contact_country = PrepareForDB($_POST['admin_contact_country'],1,"",0);
		if ($admin_contact_country == "" || $admin_contact_country == "0"){
			$res_country = mysql_query("SELECT country_id FROM $db_country WHERE country_shortname='".$ar_setup['setup_basic_country']."'") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_country = mysql_fetch_array($res_country);
			$admin_contact_country = $ar_country['country_id'];
		}

		$admin_contact_shop_companyname = PrepareForDB($_POST['admin_contact_shop_companyname'],1,"",0);
		$admin_contact_shop_name = PrepareForDB($_POST['admin_contact_shop_name'],1,"",0);
		$admin_contact_shop_firstname = PrepareForDB($_POST['admin_contact_shop_firstname'],1,"",0);
		$admin_contact_shop_city = PrepareForDB($_POST['admin_contact_shop_city'],1,"",0);
		$admin_contact_shop_address_1 = PrepareForDB($_POST['admin_contact_shop_address_1'],1,"",0);
		$admin_contact_shop_address_2 = PrepareForDB($_POST['admin_contact_shop_address_2'],1,"",0);
		$admin_contact_shop_postode = PrepareForDB($_POST['admin_contact_shop_postode'],1,"",0);
		$admin_contact_shop_country = $_POST['admin_contact_shop_country'];

		$admin_clan_tag = PrepareForDB($_POST['admin_clan_tag'],1,"",0);
		$admin_clan_name = PrepareForDB($_POST['admin_clan_name'],1,"",0);
		$admin_clan_www = PrepareForDB($_POST['admin_clan_www'],1,"",0);
		$admin_clan_irc = PrepareForDB($_POST['admin_clan_irc'],1,"",0);

		$admin_hw_cpu = PrepareForDB($_POST['admin_hw_cpu'],1,"<a>",0);
		$admin_hw_ram = PrepareForDB($_POST['admin_hw_ram'],1,"<a>",0);
		$admin_hw_mb = PrepareForDB($_POST['admin_hw_mb'],1,"<a>",0);
		$admin_hw_hdd = PrepareForDB($_POST['admin_hw_hdd'],1,"<a>",0);
		$admin_hw_cd = PrepareForDB($_POST['admin_hw_cd'],1,"<a>",0);
		$admin_hw_vga = PrepareForDB($_POST['admin_hw_vga'],1,"<a>",0);
		$admin_hw_soundcard = PrepareForDB($_POST['admin_hw_soundcard'],1,"<a>",0);
		$admin_hw_monitor = PrepareForDB($_POST['admin_hw_monitor'],1,"<a>",0);
		$admin_hw_mouse = PrepareForDB($_POST['admin_hw_mouse'],1,"<a>",0);
		$admin_hw_mousepad = PrepareForDB($_POST['admin_hw_mousepad'],1,"<a>",0);
		$admin_hw_headset = PrepareForDB($_POST['admin_hw_headset'],1,"<a>",0);
		$admin_hw_repro = PrepareForDB($_POST['admin_hw_repro'],1,"<a>",0);
		$admin_hw_key = PrepareForDB($_POST['admin_hw_key'],1,"<a>",0);
		$admin_hw_gamepad = PrepareForDB($_POST['admin_hw_gamepad'],1,"<a>",0);
		$admin_hw_os = PrepareForDB($_POST['admin_hw_os'],1,"<a>",0);
		$admin_hw_conection = PrepareForDB($_POST['admin_hw_conection'],1,"<a>",0);
		$admin_hw_brand_pc = PrepareForDB($_POST['admin_hw_brand_pc'],1,"<a>",0);

		$admin_game_mouse_sens = PrepareForDB($_POST['admin_game_mouse_sens'],1,"",0);
		$admin_game_fav_weapon = PrepareForDB($_POST['admin_game_fav_weapon'],1,"",0);
		$admin_game_fav_team = PrepareForDB($_POST['admin_game_fav_team'],1,"",0);
		$admin_game_fav_map = PrepareForDB($_POST['admin_game_fav_map'],1,"",0);
		
		$admin_contact_postcode = strtoupper($admin_contact_postcode);
		$admin_contact_birth_day = $_POST['year'].$_POST['month'].$_POST['day'];
		
		$admin_poker_fav_variants = $_POST['admin_poker_fav_variants_1']."||".$_POST['admin_poker_fav_variants_2']."||".$_POST['admin_poker_fav_variants_3'];
		$admin_poker_fav_player = PrepareForDB($_POST['admin_poker_fav_player']);
		$admin_poker_fav_cardrooms = $_POST['admin_poker_fav_cardroom_1']."||".$_POST['admin_poker_fav_cardroom_2']."||".$_POST['admin_poker_fav_cardroom_3'];

		/* Do retezce oddelene carkami ulozime zvolene filtry */
		if ($_POST['admin_filter'] != ""){$admin_info_filter = implode ("||", $_POST['admin_filter']);}

		$admin_info_sig = strip_tags($_POST['admin_info_sig'],"");
		/* Prevedeme text z bb kodu do HTML */
		$admin_sig_html = new BB_to_HTML_Code();
		$admin_info_sig_html = $admin_sig_html->parse($admin_info_sig);

		/* Z obsahu promenné body vyjmout nepovolené tagy */
		$admin_info_sig_bb = strip_tags($admin_info_sig,"");
		$admin_info_sig_bb = nl2br($admin_info_sig_bb);

		if ($_POST['admin_password1'] == $_POST['admin_password2'] || ($_POST['admin_password1'] == "" && $_POST['admin_password2'] == "") || $_POST["admin_password1_e"] != ""){
			if ($_POST['mode'] == "edit_user"){
				$res2 = mysql_query("SELECT a.*, acl.*, ac.*, acs.*, ag.*, ah.*, ai.*, ap.* FROM $db_admin AS a, $db_admin_clan AS acl, $db_admin_contact AS ac, $db_admin_contact_shop AS acs, $db_admin_game AS ag, $db_admin_hw AS ah, $db_admin_info AS ai, $db_admin_poker AS ap WHERE a.admin_id=".(integer)$_SESSION['loginid']." AND acl.aid=".(integer)$_SESSION['loginid']." AND ac.aid=".(integer)$_SESSION['loginid']." AND acs.aid=".(integer)$_SESSION['loginid']." AND ag.aid=".(integer)$_SESSION['loginid']." AND ah.aid=".(integer)$_SESSION['loginid']." AND ai.aid=".(integer)$_SESSION['loginid']." AND ap.aid=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$ar2 = mysql_fetch_array($res2);

				/* V pripade zmeny hesla zkontrolujeme zda uzivatel zadal spravne sve stare heslo */
				/* Toto se nekontroluje v pripade zapomenuti hesla a editace po kliknuti na odkaz, ktery prihlasi uzivatele */
				if ($_POST['admin_password1'] != "" && $_SESSION['forg_pass'] != "true"){
					$p_check = MD5(MD5($_POST['admin_password_old']).$ar2['admin_uname']);
					if ($p_check != $ar2['admin_password']){
						$confirm = "no";
						header ("Location: ".$eden_cfg['url']."index.php?action=".$_GET['action']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=reg_old_pass_arent_match&mode=".$_POST['mode']);
						exit;
					}
				}
				/* Pokud se zmeni adresa emailu oproti emailu v databazi jiz ulozenemu */
				if ($admin_email != $ar2['admin_email']){
					/* Ulozime do promenne $p_check MD5 hash hesla */
					$p_check = MD5(MD5($_POST['admin_password1_e']).$ar2['admin_uname']);
					
					/* Provereni zda zadany email jiz v databazi existuje */
					$res_admins = mysql_query("SELECT COUNT(*) FROM $db_admin WHERE admin_email='".$admin_email."'") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					$num_admins = mysql_fetch_array($res_admins);
					
					/* Zjistime zda zadany email odpovida konvenci pro psani email adresy */
					if (CheckEmail($admin_email) != 1 || CheckEmail($admin_email_repeat) != 1){
						/* Pokud emailova adresa neodpovida konvencim, vrati se uzivateli chybova hlaska */
						header ("Location: ".$eden_cfg['url']."index.php?action=user_edit&lang=".$_GET['lang']."&filter=".$_GET['filter']."&mode=edit_user&msg=reg_email_is_not_vaild&e_mode=".$_GET['e_mode']."");
						exit;
						/* Zjisteni jestli se shoduji zadane emaily */
					}elseif ($admin_email != $admin_email_repeat){
						header ("Location: ".$eden_cfg['url']."index.php?action=user_edit&lang=".$_GET['lang']."&filter=".$_GET['filter']."&mode=edit_user&e_mode=open&msg=reg_nomatchemail&e_mode=".$_GET['e_mode']."");
						exit;
					/* Pokud neodpovida heslo presmerujeme zpatky na formular zadani noveho emailu s chybovou hlaskou */
					} elseif ($p_check != $ar2['admin_password']){
						header ("Location: ".$eden_cfg['url']."index.php?action=user_edit&lang=".$_GET['lang']."&filter=".$_GET['filter']."&mode=edit_user&e_mode=open&msg=reg_pass_arent_match&e_mode=".$_GET['e_mode']."");
						exit;
					} elseif ($num_admins[0] != 0){
						/* Pokud email jiz v databazi existuje */
						header ("Location: ".$eden_cfg['url']."index.php?action=user_edit&lang=".$_GET['lang']."&filter=".$_GET['filter']."&mode=edit_user&msg=replyemail&e_mode=".$_GET['e_mode']."");
						exit;
					} else{
						/* Pokud zadany email v databazi neexistuje, odesle se na nej kod, ktery dany email, po klinuti na nej aktivuje */
						/* Toto je zabezpeceni proti zadani falesneho emailu */
						$res_admin = mysql_query("SELECT a.admin_id, a.admin_uname, a.admin_email, ai.admin_info_email_new FROM $db_admin AS a, $db_admin_info AS ai WHERE a.admin_id=".(integer)$_SESSION['loginid']." AND ai.aid=a.admin_id") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						$ar_admin = mysql_fetch_array($res_admin);
						$admin_reg_code = GeneratePass(15);
						
						mysql_query("UPDATE $db_admin SET admin_reg_code='".$admin_reg_code."' WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						mysql_query("UPDATE $db_admin_info SET admin_info_email_new='".mysql_real_escape_string($admin_email)."' WHERE aid=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						
						$mail = new PHPMailer();
						$mail->From = $ar_setup['setup_reg_from'];
						$mail->FromName = $ar_setup['setup_reg_from_name'];
						$mail->AddAddress($admin_email);
						$mail->CharSet = "utf-8";
						$mail->IsHTML(true);
						$mail->Mailer = $ar_setup['setup_reg_mailer'];
						$mail->Subject = $ar_setup['setup_lang_change_subject'];
						
						$change_email_body = str_replace("[{admin_uname}]",$ar_admin['admin_uname'] ,$ar_setup['setup_lang_change_email']);
						$change_email_body = str_replace("[{setup_changes_old_email}]",$ar_admin['admin_email'] ,$change_email_body);
						$change_email_body = str_replace("[{setup_changes_new_email}]",$admin_email ,$change_email_body);
						
						$mail->Body .= "<html><head title=\"".$ar_setup['setup_lang_change_subject']."\"/><body>";
						$mail->Body .= "<p>".$change_email_body."</p>";
						$mail->Body .= "<p><a href =\"".$eden_cfg['url']."index.php?action=allow_change_email&lang=".$_GET['lang']."&filter=".$_GET['filter']."&rg_code=".$admin_reg_code."\">".$eden_cfg['url']."index.php?action=allow_change_email&lang=".$_GET['lang']."&filter=".$_GET['filter']."&rg_code=".$admin_reg_code."</a></p>";
						$mail->Body .= "</body></html>";
						
						$mail->AltBody = "\n";
						$mail->AltBody .= $change_email_body."\n";
						$mail->AltBody .= "".$eden_cfg['url']."index.php?action=allow_change_email&lang=".$_GET['lang']."&filter=".$_GET['filter']."&rg_code=".$admin_reg_code;
						$mail->AltBody .= "\n";
						
						$mail->WordWrap = 100;
						
						if (!$mail->Send()){
							header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=change_email_er_mail");
							exit;
						} else {
							header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=chanege_email_ok_mail");
							exit;
						}
					}
				}

				if ($_POST['admin_password1'] == ""){$p = $ar2['admin_password'];} else {$p = MD5(MD5($_POST['admin_password1']).$ar2['admin_uname']);}
				if ($ar2['admin_userimage'] == ""){$image = "0000000001.gif";} else {$image = $ar2['admin_userimage'];} // Pokud jeste neni nastaven obrazek tak se nastavi
				if ($admin_priv == ""){$admin_priv = $ar2['admin_priv'];}
				if ($admin_autologin == ""){
					$admin_autologin = 2; /* Pokud je 0 tak to nefunguje */
					setcookie($project.'_autologin', '', time() - 186400);
					setcookie($project.'_name', '', time() - 186400);
					setcookie($project.'_pass', '', time() - 186400);
				}
				if ($admin_autologin == 1){
					setcookie($project.'_autologin', 1, time() + 186400);
					setcookie($project.'_name', $ar2['admin_uname'], time() + 186400);
					setcookie($project.'_pass', $p, time() + 186400);
				}

				/* Nahrani obrazku Avatara */
				if ($_FILES['admin_userimage']['name'] != ""){
					/* Spojeni s FTP serverem */
					$conn_id = ftp_connect($eden_cfg['ftp_server']);
					/* Prihlaseni pres uzivatelske jmeno a heslo na FTP server */
					$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
					ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
					
					/* Zjisteni stavu spojeni */
					if ((!$conn_id) || (!$login_result)){
						header ("Location: ".$eden_cfg['url']."index.php?action=user_edit&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=ftp_no_connection&mode=edit_user");
						exit;
					}
					if (($avatar_img = getimagesize($_FILES['admin_userimage']['tmp_name'])) != false){
						/* Zjistime zda neni soubor jineho typu nez je povoleno a pokud ne priradime extenzi */
						if ($avatar_img[2] == 1){
							$extenze = ".gif";
						} elseif ($avatar_img[2] == 2){
							$extenze = ".jpg";
						} elseif ($avatar_img[2] == 3){
							$extenze = ".png";
						} else {
							header ("Location: ".$eden_cfg['url']."index.php?action=user_edit&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=ftp_av_wft&mode=edit_user");
							exit;
						}
						/* Zjistime zda neni obrazek mensi, nez je povoleno */
						if ($avatar_img[0] < GetSetupImageInfo("avatar","width") || $avatar_img[1] < GetSetupImageInfo("avatar","height")){
							header ("Location: ".$eden_cfg['url']."index.php?action=user_edit&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=ftp_av_ts&mode=edit_user");
							exit;
						/* Zjistime zda neni obrazek vetsi, nez je povoleno */
						} elseif ($avatar_img[0] > GetSetupImageInfo("avatar","width") || $avatar_img[1] > GetSetupImageInfo("avatar","height")){
							header ("Location: ".$eden_cfg['url']."index.php?action=user_edit&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=ftp_av_tb&mode=edit_user");
							exit;
						/* Zjistime zda neni soubor vetsi nez je povoleno */
						} elseif ($_FILES['admin_userimage']['size'] > GetSetupImageInfo("avatar","filesize")){
							header ("Location: ".$eden_cfg['url']."index.php?action=user_edit&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=ftp_av_ftb&mode=edit_user");
							exit;
						} else {
							/* Zjisti nazev souboru po ulozeni do docasneho adresare na serveru */
							$source_file = $_FILES['admin_userimage']['tmp_name'];
							$userfile_name = $_SESSION['loginid']."_1".$extenze;
							/* Vlozi nazev souboru a cestu do konkretniho adresare */
							$destination_file = $ftp_path_admins.$userfile_name;
							$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);

							/* Zjisteni stavu uploadu */
							if (!$upload){
								header ("Location: ".$eden_cfg['url']."index.php?action=user_edit&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=ftp_ue&mode=edit_user");
								exit;
							} else {
								/* Pokud vse probehne jak ma znicime dane promenne a nastavime $checkupload */
								unset($source_file);
								unset($destination_file);
								unset($extenze);
								unset($avatar_img);
								unset($_FILES['admin_userimage']);
								$checkupload = 1;
							}
						}
					} else {
						header ("Location: ".$eden_cfg['url']."index.php?action=user_edit&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=ftp_av_bi&mode=edit_user");
						exit;
					}

					/* Uzavreni komunikace se serverem */
					ftp_close($conn_id);
				}

				if ($checkupload == 1){$admin_userimage = $userfile_name;} else {$admin_userimage = $image;} // Pokud byl obrazek nasatven zapise se jeho jmeno, jinak se pouzije jmeno z databaze
				if ($admin_firstname == ""){$admin_firstname = $ar2['admin_firstname'];}elseif($admin_firstname == "-"){$admin_firstname = "";}
				if ($_POST['mode'] == "edit_user"){$admin_nick = $ar2['admin_nick'];}
				if ($admin_name == ""){$admin_name = $ar2['admin_name'];}elseif($admin_name == "-"){$admin_name = "";}
				if ($admin_gender == ""){$admin_gender = $ar2['admin_gender'];}elseif($admin_gender == "-"){$admin_gender = "";}
				if ($admin_email == ""){$admin_email = $ar2['admin_email'];}elseif($admin_email == "-"){$admin_email = "";}
				if ($admin_userinfo == ""){$admin_userinfo = $ar2['admin_userinfo'];}elseif($admin_userinfo == "-"){$admin_userinfo = "";}
				if ($admin_hits == ""){$admin_hits = $ar2['admin_hits'];}elseif($admin_hits == "-"){$admin_hits = "";}
				if ($admin_lang == ""){$admin_lang = $ar2['admin_lang'];}elseif($admin_lang == "-"){$admin_lang = "";}
				if ($admin_title == ""){$admin_title = $ar2['admin_title'];}elseif($admin_title == "-"){$admin_title = "";}
				if ($admin_autologin == ""){$admin_autologin = $ar2['admin_autologin'];}

				if ($admin_clan_tag == ""){$admin_clan_tag = $ar2['admin_clan_tag'];}elseif($admin_clan_tag == "-"){$admin_clan_tag = "";}
				if ($admin_clan_name == ""){$admin_clan_name = $ar2['admin_clan_name'];}elseif($admin_clan_name == "-"){$admin_clan_name = "";}
				if ($admin_clan_www == ""){$admin_clan_www = $ar2['admin_clan_www'];}elseif($admin_clan_www == "-"){$admin_clan_www = "";}
				if ($admin_clan_irc == ""){$admin_clan_irc = $ar2['admin_clan_irc'];}elseif($admin_clan_irc == "-"){$admin_clan_irc = "";}

				if ($admin_contact_telefon == ""){$admin_contact_telefon = $ar2['admin_contact_telefon'];}elseif($admin_contact_telefon == "-"){$admin_contact_telefon = "";}
				if ($admin_contact_mobil == ""){$admin_contact_mobil = $ar2['admin_contact_mobil'];}elseif($admin_contact_mobil == "-"){$admin_contact_mobil = "";}
				if ($admin_contact_icq == ""){$admin_contact_icq = $ar2['admin_contact_icq'];}elseif($admin_contact_icq == "-"){$admin_contact_icq = "";}
				if ($admin_contact_msn == ""){$admin_contact_msn = $ar2['admin_contact_msn'];}elseif($admin_contact_msn == "-"){$admin_contact_msn = "";}
				if ($admin_contact_aol == ""){$admin_contact_aol = $ar2['admin_contact_aol'];}elseif($admin_contact_aol == "-"){$admin_contact_aol = "";}
				if ($admin_contact_skype == ""){$admin_contact_skype = $ar2['admin_contact_skype'];}elseif($admin_contact_skype == "-"){$admin_contact_skype = "";}
				if ($admin_contact_xfire == ""){$admin_contact_xfire = $ar2['admin_contact_xfire'];}elseif($admin_contact_xfire == "-"){$admin_contact_xfire = "";}
				if ($admin_contact_web == ""){$admin_contact_web = $ar2['admin_contact_web'];}elseif($admin_contact_web == "-"){$admin_contact_web = "";}
				if ($admin_contact_web2 == ""){$admin_contact_web2 = $ar2['admin_contact_web2'];}elseif($admin_contact_web2 == "-"){$admin_contact_web2 = "";}
				if ($admin_contact_web3 == ""){$admin_contact_web3 = $ar2['admin_contact_web3'];}elseif($admin_contact_web3 == "-"){$admin_contact_web3 = "";}
				if ($admin_contact_web4 == ""){$admin_contact_web4 = $ar2['admin_contact_web4'];}elseif($admin_contact_web4 == "-"){$admin_contact_web4 = "";}
				if ($admin_contact_birth_day == ""){$admin_contact_birth_day = $ar2['admin_contact_birth_day'];}elseif($admin_contact_birth_day == "-"){$admin_contact_birth_day = "";}
				if ($admin_contact_city == ""){$admin_contact_city = $ar2['admin_contact_city'];}elseif($admin_contact_city == "-"){$admin_contact_city = "";}
				if ($admin_contact_companyname == ""){$admin_contact_companyname = $ar2['admin_contact_companyname'];}elseif($admin_contact_companyname == "-"){$admin_contact_companyname = "";}
				if ($admin_contact_address_1 == ""){$admin_contact_address_1 = $ar2['admin_contact_address_1'];}elseif($admin_contact_address_1 == "-"){$admin_contact_address_1 = "";}
				if ($admin_contact_address_2 == ""){$admin_contact_address_2 = $ar2['admin_contact_address_2'];}elseif($admin_contact_address_2 == "-"){$admin_contact_address_2 = "";}
				if ($admin_contact_postcode == ""){$admin_contact_postcode = $ar2['admin_contact_postcode'];}elseif($admin_contact_postcode == "-"){$admin_contact_postcode = "";}
				if ($admin_contact_country == ""){$admin_contact_country = $ar2['admin_contact_country'];}elseif($admin_contact_country == "-"){$admin_contact_country = "";}

				if ($admin_contact_shop_companyname == ""){$admin_contact_shop_companyname = $ar2['admin_contact_shop_companyname'];}elseif($admin_contact_shop_companyname == "-"){$admin_contact_shop_companyname = "";}
				if ($admin_contact_shop_firstname == ""){$admin_contact_shop_firstname = $ar2['admin_contact_shop_firstname'];}elseif($admin_contact_shop_firstname == "-"){$admin_contact_shop_firstname = "";}
				if ($admin_contact_shop_name == ""){$admin_contact_shop_name = $ar2['admin_contact_shop_name'];}elseif($admin_contact_shop_name == "-"){$admin_contact_shop_name = "";}
				if ($admin_contact_shop_city == ""){$admin_contact_shop_city = $ar2['admin_contact_shop_city'];}elseif($admin_contact_shop_city == "-"){$admin_contact_shop_city = "";}
				if ($admin_contact_shop_address_1 == ""){$admin_contact_shop_address_1 = $ar2['admin_contact_shop_address_1'];}elseif($admin_contact_shop_address_1 == "-"){$admin_contact_shop_address_1 = "";}
				if ($admin_contact_shop_address_2 == ""){$admin_contact_shop_address_2 = $ar2['admin_contact_shop_address_2'];}elseif($admin_contact_shop_address_2 == "-"){$admin_contact_shop_address_2 = "";}
				if ($admin_contact_shop_postcode == ""){$admin_contact_shop_postcode = $ar2['admin_contact_shop_postcode'];}elseif($admin_contact_shop_postcode == "-"){$admin_contact_shop_postcode = "";}
				if ($admin_contact_shop_country == ""){if ($ar2['admin_contact_shop_country'] == "" || $ar2['admin_contact_shop_country'] == 0){$admin_contact_shop_country = 223;} else {$admin_contact_shop_country = $ar2['admin_contact_shop_country'];}}
				if ($admin_contact_shop_title == ""){$admin_contact_shop_title = $ar2['admin_contact_shop_title'];}elseif($admin_contact_shop_title == "-"){$admin_contact_shop_title = "";}
				if ($admin_contact_shop_use == ""){$admin_contact_shop_use = $ar2['admin_contact_shop_use'];}elseif($admin_contact_shop_use == "-"){$admin_contact_shop_use = "";}

				if ($admin_game_resolution == ""){$admin_game_resolution = $ar2['admin_game_resolution'];}
				if ($admin_game_mouse_sens == ""){$admin_game_mouse_sens = $ar2['admin_game_mouse_sens'];}elseif($admin_game_mouse_sens == "-"){$admin_game_mouse_sens = "";}
				if ($admin_game_mouse_accel == ""){$admin_game_mouse_accel = $ar2['admin_game_mouse_accel'];}
				if ($admin_game_mouse_invert == ""){$admin_game_mouse_invert = $ar2['admin_game_mouse_invert'];}
				if ($admin_game_fav_weapon == ""){$admin_game_fav_weapon = $ar2['admin_game_fav_weapon'];}elseif($admin_game_fav_weapon == "-"){$admin_game_fav_weapon = "";}
				if ($admin_game_fav_team == ""){$admin_game_fav_team = $ar2['admin_game_fav_team'];}elseif($admin_game_fav_team == "-"){$admin_game_fav_team = "";}
				if ($admin_game_fav_map == ""){$admin_game_fav_map = $ar2['admin_game_fav_map'];}elseif($admin_game_fav_map == "-"){$admin_game_fav_map = "";}
				if ($admin_hw_cpu == ""){$admin_hw_cpu = $ar2['admin_hw_cpu'];}elseif($admin_hw_cpu == "-"){$admin_hw_cpu = "";}
				if ($admin_hw_ram == ""){$admin_hw_ram = $ar2['admin_hw_ram'];}elseif($admin_hw_ram == "-"){$admin_hw_ram = "";}
				if ($admin_hw_mb == ""){$admin_hw_mb = $ar2['admin_hw_mb'];}elseif($admin_hw_mb == "-"){$admin_hw_mb = "";}
				if ($admin_hw_hdd == ""){$admin_hw_hdd = $ar2['admin_hw_hdd'];}elseif($admin_hw_hdd == "-"){$admin_hw_hdd = "";}
				if ($admin_hw_cd == ""){$admin_hw_cd = $ar2['admin_hw_cd'];}elseif($admin_hw_cd == "-"){$admin_hw_cd = "";}
				if ($admin_hw_vga == ""){$admin_hw_vga = $ar2['admin_hw_vga'];}elseif($admin_hw_vga == "-"){$admin_hw_vga = "";}
				if ($admin_hw_soundcard == ""){$admin_hw_soundcard = $ar2['admin_hw_soundcard'];}elseif($admin_hw_soundcard == "-"){$admin_hw_soundcard = "";}
				if ($admin_hw_monitor == ""){$admin_hw_monitor = $ar2['admin_hw_monitor'];}elseif($admin_hw_monitor == "-"){$admin_hw_monitor = "";}
				if ($admin_hw_mouse == ""){$admin_hw_mouse = $ar2['admin_hw_mouse'];}elseif($admin_hw_mouse == "-"){$admin_hw_mouse = "";}
				if ($admin_hw_mousepad == ""){$admin_hw_mousepad = $ar2['admin_hw_mousepad'];}elseif($admin_hw_mousepad == "-"){$admin_hw_mousepad = "";}
				if ($admin_hw_headset == ""){$admin_hw_headset = $ar2['admin_hw_headset'];}elseif($admin_hw_headset == "-"){$admin_hw_headset = "";}
				if ($admin_hw_repro == ""){$admin_hw_repro = $ar2['admin_hw_repro'];}elseif($admin_hw_repro == "-"){$admin_hw_repro = "";}
				if ($admin_hw_key == ""){$admin_hw_key = $ar2['admin_hw_key'];}elseif($admin_hw_key == "-"){$admin_hw_key = "";}
				if ($admin_hw_gamepad == ""){$admin_hw_gamepad = $ar2['admin_hw_gamepad'];}elseif($admin_hw_gamepad == "-"){$admin_hw_gamepad = "";}
				if ($admin_hw_os == ""){$admin_hw_os = $ar2['admin_hw_os'];}elseif($admin_hw_os == "-"){$admin_hw_os = "";}
				if ($admin_hw_conection == ""){$admin_hw_conection = $ar2['admin_hw_conection'];}elseif($admin_hw_conection == "-"){$admin_hw_conection = "";}
				if ($admin_hw_brand_pc == ""){$admin_hw_brand_pc = $ar2['admin_hw_brand_pc'];}elseif($admin_hw_brand_pc == "-"){$admin_hw_brand_pc = "";}

				if ($admin_info_shop == ""){$admin_info_shop = $ar2['admin_info_shop'];}elseif($admin_info_shop == "-"){$admin_info_shop = "";}
				if ($admin_info_sig_html == ""){$admin_info_sig_html = $ar2['admin_info_sig_html'];}elseif($admin_info_sig_html == "-"){$admin_info_sig_html = "";}
				
				if ($admin_poker_fav_player == ""){$admin_poker_fav_player = $ar2['admin_poker_fav_player'];}elseif($admin_poker_fav_player == "-"){$admin_poker_fav_player = "";}

				mysql_query("UPDATE $db_admin SET  admin_password='".$p."', admin_firstname='".mysql_real_escape_string($admin_firstname)."', admin_name='".mysql_real_escape_string($admin_name)."', admin_gender='".mysql_real_escape_string($admin_gender)."', admin_email='".mysql_real_escape_string($admin_email)."', admin_autologin=".(integer)$admin_autologin.", admin_userimage='".mysql_real_escape_string($admin_userimage)."', admin_userinfo='".mysql_real_escape_string($admin_userinfo)."', admin_hits=".(float)$admin_hits.", admin_lang='".mysql_real_escape_string($admin_lang)."', admin_title='".mysql_real_escape_string($admin_title)."', admin_agree_email=".(integer)$_POST['admin_agree_email']." WHERE admin_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				mysql_query("UPDATE $db_admin_clan SET admin_clan_tag='".mysql_real_escape_string($admin_clan_tag)."', admin_clan_name='".mysql_real_escape_string($admin_clan_name)."', admin_clan_www='".mysql_real_escape_string($admin_clan_www)."', admin_clan_irc='".mysql_real_escape_string($admin_clan_irc)."'  WHERE aid=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				mysql_query("UPDATE $db_admin_contact SET admin_contact_telefon='".mysql_real_escape_string($admin_contact_telefon)."', admin_contact_mobil='".mysql_real_escape_string($admin_contact_mobil)."', admin_contact_icq='".mysql_real_escape_string($admin_contact_icq)."', admin_contact_msn='".mysql_real_escape_string($admin_contact_msn)."', admin_contact_aol='".mysql_real_escape_string($admin_contact_aol)."', admin_contact_skype='".mysql_real_escape_string($admin_contact_skype)."', admin_contact_xfire='".mysql_real_escape_string($admin_contact_xfire)."', admin_contact_web='".mysql_real_escape_string($admin_contact_web)."', admin_contact_web2='".mysql_real_escape_string($admin_contact_web2)."', admin_contact_web3='".mysql_real_escape_string($admin_contact_web3)."', admin_contact_web4='".mysql_real_escape_string($admin_contact_web4)."', admin_contact_birth_day=".(float)$admin_contact_birth_day.", admin_contact_city='".mysql_real_escape_string($admin_contact_city)."', admin_contact_companyname='".mysql_real_escape_string($admin_contact_companyname)."', admin_contact_address_1='".mysql_real_escape_string($admin_contact_address_1)."', admin_contact_address_2='".mysql_real_escape_string($admin_contact_address_2)."', admin_contact_postcode='".mysql_real_escape_string($admin_contact_postcode)."', admin_contact_country=".(float)$admin_contact_country."  WHERE aid=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				mysql_query("UPDATE $db_admin_contact_shop SET admin_contact_shop_companyname='".mysql_real_escape_string($admin_contact_shop_companyname)."', admin_contact_shop_firstname='".mysql_real_escape_string($admin_contact_shop_firstname)."', admin_contact_shop_name='".mysql_real_escape_string($admin_contact_shop_name)."', admin_contact_shop_city='".mysql_real_escape_string($admin_contact_shop_city)."', admin_contact_shop_address_1='".mysql_real_escape_string($admin_contact_shop_address_1)."', admin_contact_shop_address_2='".mysql_real_escape_string($admin_contact_shop_address_2)."', admin_contact_shop_postcode='".mysql_real_escape_string($admin_contact_shop_postcode)."', admin_contact_shop_country=".(float)$admin_contact_shop_country.", admin_contact_shop_title='".mysql_real_escape_string($admin_contact_shop_title)."', admin_contact_shop_use='".mysql_real_escape_string($admin_contact_shop_use)."'  WHERE aid=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				mysql_query("UPDATE $db_admin_game SET admin_game_resolution='".mysql_real_escape_string($admin_game_resolution)."', admin_game_mouse_sens='".mysql_real_escape_string($admin_game_mouse_sens)."', admin_game_mouse_accel=".(float)$admin_game_mouse_accel.", admin_game_mouse_invert=".(float)$admin_game_mouse_invert.", admin_game_fav_weapon='".mysql_real_escape_string($admin_game_fav_weapon)."', admin_game_fav_team='".mysql_real_escape_string($admin_game_fav_team)."', admin_game_fav_map='".mysql_real_escape_string($admin_game_fav_map)."'  WHERE aid=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				mysql_query("UPDATE $db_admin_hw SET admin_hw_cpu='".mysql_real_escape_string($admin_hw_cpu)."', admin_hw_ram='".mysql_real_escape_string($admin_hw_ram)."', admin_hw_mb='".mysql_real_escape_string($admin_hw_mb)."', admin_hw_hdd='".mysql_real_escape_string($admin_hw_hdd)."', admin_hw_cd='".mysql_real_escape_string($admin_hw_cd)."', admin_hw_vga='".mysql_real_escape_string($admin_hw_vga)."', admin_hw_soundcard='".mysql_real_escape_string($admin_hw_soundcard)."', admin_hw_monitor='".mysql_real_escape_string($admin_hw_monitor)."', admin_hw_mouse='".mysql_real_escape_string($admin_hw_mouse)."', admin_hw_mousepad='".mysql_real_escape_string($admin_hw_mousepad)."', admin_hw_headset='".mysql_real_escape_string($admin_hw_headset)."', admin_hw_repro='".mysql_real_escape_string($admin_hw_repro)."', admin_hw_key='".mysql_real_escape_string($admin_hw_key)."', admin_hw_gamepad='".mysql_real_escape_string($admin_hw_gamepad)."', admin_hw_os='".mysql_real_escape_string($admin_hw_os)."', admin_hw_conection='".mysql_real_escape_string($admin_hw_conection)."', admin_hw_brand_pc='".mysql_real_escape_string($admin_hw_brand_pc)."'  WHERE aid=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				mysql_query("UPDATE $db_admin_info SET admin_info_shop='".mysql_real_escape_string($admin_info_shop)."', admin_info_filter='".mysql_real_escape_string($admin_info_filter)."', admin_info_sig_html='".mysql_real_escape_string($admin_info_sig_html)."', admin_info_sig_bb='".mysql_real_escape_string($admin_info_sig_bb)."', admin_info_forum_posts_order=".(float)$_POSTS['admin_info_forum_posts_order']."  WHERE aid=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				mysql_query("UPDATE $db_admin_poker SET admin_poker_fav_cardrooms='".mysql_real_escape_string($admin_poker_fav_cardrooms)."', admin_poker_fav_variants='".mysql_real_escape_string($admin_poker_fav_variants)."', admin_poker_fav_player='".mysql_real_escape_string($admin_poker_fav_player)."'  WHERE aid=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());

				// Pokud si uzivatel zmenil heslo muzeme odhlasit nepotrebnost zadani stareho hesla
				$_SESSION['forg_pass'] = "";

				if ($_POST['action_shop'] == "shop_user_edit"){
					header ("Location: ".$eden_cfg['url']."index.php?action=01&lang=".$admin_lang."&filter=".$_GET['filter']."&msg=edit_ok&mode=shop_user_edit");
					exit;
				}elseif ($_POST['action_shop'] == "shop_check_del_address"){
					header ("Location: ".$eden_cfg['url']."index.php?action=03&lang=".$admin_lang."&filter=".$_GET['filter']."&msg=edit_ok&mode=shop_user_edit");
					exit;
				} else {
					header ("Location: ".$eden_cfg['url']."index.php?action=user_edit&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=edit_ok&mode=edit_user");
					exit;
				}
			}
			if ($_POST['mode'] == "register_user"){
				$eden_captcha = new EdenCaptcha($eden_cfg);
				$admin_lang = $_GET['lang'];

				/*	Odstraneni prebytecnych znaku	*/
				$admin_username = CleanAdminUsername($admin_username);

				$res = mysql_query("SELECT COUNT(*) FROM $db_admin WHERE admin_uname='".mysql_real_escape_string($admin_username)."'") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$num = mysql_fetch_array($res);
				$res2 = mysql_query("SELECT COUNT(*) FROM $db_admin WHERE admin_nick='".mysql_real_escape_string($admin_nick)."'") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$num2 = mysql_fetch_array($res2);
				$res3 = mysql_query("SELECT COUNT(*) FROM $db_admin WHERE admin_email='".mysql_real_escape_string($admin_email)."'") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$num3 = mysql_fetch_array($res3);
				$p = MD5(MD5($_POST['admin_password1']).$admin_username);

				if ($_POST['action_shop'] == "user_reg"){
					$reg_action = "01&action_shop=user_reg";
					if($num2[0] > 0){$rsz = GeneratePass(3); $admin_nick = $admin_nick.$rsz;}
				} else {
					$reg_action = "reg_scr";
				}
				$standard_variables = 'action='.$reg_action.'&lang='.$_GET['lang'].'&filter='.$_GET['filter'].'&mode=reg';
				$extended_variables = '&aun='.$admin_username.'&ani='.$admin_nick.'&ati='.$admin_title.'&afn='.$admin_firstname.'&anm='.$admin_name.'&aem='.$admin_email.'&aemr='.$admin_email_repeat.'&acby='.$_POST['year'].'&acbm='.$_POST['month'].'&acbd='.$_POST['day'].'&acci='.$admin_contact_city.'&acc='.$admin_contact_country.'&accn='.$admin_contact_companyname.'&aca1='.$admin_contact_address_1.'&aca2='.$admin_contact_address_2.'&acp='.$admin_contact_postcode.'&act='.$admin_contact_telefon.'&acm='.$admin_contact_mobil.'&aal='.$admin_autologin.'&aae='.$admin_agree_email;

				/* Zkontrolujeme zda byl zaskrtnut souhlas s pravidly registrace */
				if ($ar_setup['setup_reg_agreement'] == 1 && $_POST['admin_agreement'] != 1){
					header ("Location: ".$eden_cfg['url']."index.php?".$standard_variables."&msg=reg_disagree".$extended_variables);
					exit;
				/* Zjisteni jestli bylo zadano uzivatelske jmeno */
				}elseif (empty($admin_username)){
					header ("Location: ".$eden_cfg['url']."index.php?".$standard_variables."&msg=reg_emptyusername".$extended_variables);
					exit;
				/* Zjisteni jestli byl zadan nickname - pokud je toto vyzadovano */
				}elseif (empty($admin_nick) && $ar_setup['setup_reg_admin_nick'] == 1){
					header ("Location: ".$eden_cfg['url']."index.php?".$standard_variables."&msg=reg_emptynick".$extended_variables);
					exit;
				/* Zjisteni jestli bylo zadano heslo */
				}elseif (empty($_POST['admin_password1'])){
					header ("Location: ".$eden_cfg['url']."index.php?".$standard_variables."&msg=reg_emptypass".$extended_variables);
					exit;
				/* Zjisteni jestli bylo zadano heslo */
				}elseif (empty($_POST['admin_password2'])){
					header ("Location: ".$eden_cfg['url']."index.php?".$standard_variables."&msg=reg_emptypass".$extended_variables);
					exit;
				/* Zjisteni jestli bylo zadano jmeno */
				}elseif (empty($admin_firstname)){
					header ("Location: ".$eden_cfg['url']."index.php?".$standard_variables."&msg=reg_emptyfirstname".$extended_variables);
					exit;
				/* Zjisteni jestli bylo zadano prijmeni */
				}elseif (empty($admin_name)){
					header ("Location: ".$eden_cfg['url']."index.php?".$standard_variables."&msg=reg_emptyname".$extended_variables);
					exit;
				/* Zjisteni jestli bylo zadan email */
				}elseif (empty($admin_email)){
					header ("Location: ".$eden_cfg['url']."index.php?".$standard_variables."&msg=reg_emptyemail".$extended_variables);
					exit;
				/* Zjisteni jestli se shoduji zadane emaily */
				}elseif ($admin_email != $admin_email_repeat){
					header ("Location: ".$eden_cfg['url']."index.php?".$standard_variables."&msg=reg_nomatchemail".$extended_variables);
					exit;
				/* Zjisteni jestli jmeno jiz existuje */
				}elseif ($num[0] > 0){
					$confirm = "no";
					$_POST['mode'] = "";
					header ("Location: ".$eden_cfg['url']."index.php?".$standard_variables."&msg=replynames".$extended_variables);
					exit;
				/* Zjisteni jestli nick jiz existuje */
				}elseif ($num2[0] > 0){
					$confirm = "no";
					$_POST['mode'] = "";
					header ("Location: ".$eden_cfg['url']."index.php?".$standard_variables."&msg=replynick".$extended_variables);
					exit;
				/* Zjisteni jestli email jiz existuje */
				}elseif ($num3[0] > 0){
					$confirm = "no";
					$_POST['mode'] = "";
					header ("Location: ".$eden_cfg['url']."index.php?".$standard_variables."&msg=replyemail".$extended_variables);
					exit;
				/* Zjisteni jestli byla zadana captcha */
				}elseif ($eden_captcha->CaptchaCheck() === FALSE){
					header ("Location: ".$eden_cfg['url']."index.php?".$standard_variables."&msg=bad_captcha".$extended_variables);
					exit;
				/* Kdyz jse vse v poradku */
				} else {
					$admin_reg_code = GeneratePass(15);
					if ($autologin == ""){
						$autologin = 0;
						setcookie($project.'_autologin', '', time() - 186400);
						setcookie($project.'_name', '', time() - 186400);
						setcookie($project.'_pass', '', time() - 186400);
					}
					if ($autologin == "1"){
						setcookie($project.'_autologin', 1, time() + 186400);
						setcookie($project.'_name', strtoupper ($admin_username), time() + 186400);
						setcookie($project.'_pass', $p, time() + 186400);
					}
					/* Pokud neni vyzadovan nick, musime ho vygenerovat */
					if ($ar_setup['setup_reg_admin_nick'] == 0){
						$admin_nick = $admin_username."_".GeneratePass(4);
					}
					/* Kdyz je aktivace uzivatelskeho uctu pres email zakazana */
					if ($ar_setup['setup_reg_over_email'] == 0){
						mysql_query("INSERT INTO $db_admin VALUES('','".mysql_real_escape_string(strtoupper($admin_username))."','".mysql_real_escape_string($p)."','".mysql_real_escape_string($admin_firstname)."','".mysql_real_escape_string($admin_name)."','".mysql_real_escape_string($admin_gender)."','".mysql_real_escape_string($admin_nick)."','".mysql_real_escape_string($admin_email)."','','user','','99','','99','','99','".(integer)$admin_autologin."','','0000000001.gif','0000000002.gif','','0','','','0','0','0','0','0','0','0',NOW(),'".mysql_real_escape_string($admin_reg_code)."','1','".mysql_real_escape_string($admin_lang)."','','".mysql_real_escape_string($admin_title)."','','','".(integer)$admin_agree_email."')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						$res_id = mysql_query("SELECT LAST_INSERT_ID()") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						$ar_id = mysql_fetch_array($res_id);
						$adm_id = $ar_id[0];
						/* Pokud se pri ukladani zjisti ze jiz v tabulce existuje shodne admin_uname nebo admin_nick, nebo admin_email */
						if (mysql_errno() == 1062) {
							$confirm = "no";
							$_POST['mode'] = "";
							header ("Location: ".$eden_cfg['url']."index.php?".$standard_variables."&msg=replysomething".$extended_variables);
							exit;
						}
						mysql_query("INSERT INTO $db_admin_clan VALUES('".(float)$adm_id."','', '', '', '', '')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						mysql_query("INSERT INTO $db_admin_contact VALUES('".(float)$adm_id."', '".mysql_real_escape_string($admin_contact_telefon)."', '".mysql_real_escape_string($admin_contact_mobil)."', '', '', '', '', '', '', '', '', '', '', '".mysql_real_escape_string($admin_contact_city)."', '".mysql_real_escape_string($admin_contact_companyname)."', '".mysql_real_escape_string($admin_contact_address_1)."', '".mysql_real_escape_string($admin_contact_address_2)."', '".mysql_real_escape_string($admin_contact_postcode)."', '".mysql_real_escape_string($admin_contact_country)."', '')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						mysql_query("INSERT INTO $db_admin_contact_shop VALUES('".(float)$adm_id."', '".mysql_real_escape_string($admin_shop_companyname)."', '".mysql_real_escape_string($admin_shop_firstname)."', '".mysql_real_escape_string($admin_shop_name)."', '', '', '', '', '".mysql_real_escape_string($admin_contact_country)."','".mysql_real_escape_string($admin_contact_title)."','')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						mysql_query("INSERT INTO $db_admin_game VALUES('".(float)$adm_id."', '', '', '', '', '', '', '')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						mysql_query("INSERT INTO $db_admin_hw VALUES('".(float)$adm_id."', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						mysql_query("INSERT INTO $db_admin_info VALUES('".(float)$adm_id."', '', '".$admin_info_filter."', '', '', 'basic', '', '', '')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						mysql_query("INSERT INTO $db_admin_poker VALUES('".(float)$adm_id."', '', '', '')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						
						if ($_POST['action_shop'] == "user_reg"){
							header ("Location: ".$eden_cfg['url']."index.php?action=01&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=reg_ok");
							exit;
						} else {
							header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=reg_ok");
							exit;
						}
					/* Kdyz je aktivace uzivatelskeho uctu pres email povolena */
					} else {
						mysql_query("
						INSERT INTO $db_admin 
						VALUES('','".mysql_real_escape_string(strtoupper ($admin_username))."','".mysql_real_escape_string($p)."','".mysql_real_escape_string($admin_firstname)."','".mysql_real_escape_string($admin_name)."',
						'".mysql_real_escape_string($admin_gender)."','".mysql_real_escape_string($admin_nick)."','".mysql_real_escape_string($admin_email)."','','user','','99','','99','','99','".(integer)$admin_autologin."','',
						'0000000001.gif','0000000002.gif','','0','','','0','0','0','0','0','0','0',NOW(),'".mysql_real_escape_string($admin_reg_code)."',0,'".mysql_real_escape_string($admin_lang)."','',
						'".mysql_real_escape_string($admin_title)."','','','".(integer)$admin_agree_email."')"
						) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						$res_id = mysql_query("SELECT LAST_INSERT_ID()") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						$ar_id = mysql_fetch_array($res_id);
						$adm_id = $ar_id[0];
						/* Pokud se pri ukladani zjisti ze jiz v tabulce existuje shodne admin_uname nebo admin_nick, nebo admin_email */
						if (mysql_errno() == 1062) {
							$confirm = "no";
							$_POST['mode'] = "";
							header ("Location: ".$eden_cfg['url']."index.php?".$standard_variables."&msg=replysomething".$extended_variables);
							exit;
						}
						mysql_query("INSERT INTO $db_admin_clan VALUES('".(float)$adm_id."','', '', '', '', '')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						mysql_query("INSERT INTO $db_admin_contact VALUES('".(float)$adm_id."', '".mysql_real_escape_string($admin_contact_telefon)."', '".mysql_real_escape_string($admin_contact_mobil)."', '', '', '', '', '', '', '', '', '', '', '".mysql_real_escape_string($admin_contact_city)."', '".mysql_real_escape_string($admin_contact_companyname)."', '".mysql_real_escape_string($admin_contact_address_1)."', '".mysql_real_escape_string($admin_contact_address_2)."', '".mysql_real_escape_string($admin_contact_postcode)."', '".mysql_real_escape_string($admin_contact_country)."', '')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						mysql_query("INSERT INTO $db_admin_contact_shop VALUES('".(float)$adm_id."', '".mysql_real_escape_string($admin_shop_companyname)."', '".mysql_real_escape_string($admin_shop_firstname)."', '".mysql_real_escape_string($admin_shop_name)."', '', '', '', '', '".mysql_real_escape_string($admin_contact_country)."','".mysql_real_escape_string($admin_contact_title)."','')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						mysql_query("INSERT INTO $db_admin_game VALUES('".(float)$adm_id."', '', '', '', '', '', '', '')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						mysql_query("INSERT INTO $db_admin_hw VALUES('".(float)$adm_id."', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						mysql_query("INSERT INTO $db_admin_info VALUES('".(float)$adm_id."', '', '".$admin_info_filter."', '', '', 'basic', '', '','')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						mysql_query("INSERT INTO $db_admin_poker VALUES('".(float)$adm_id."', '', '', '')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());

						$mail = new PHPMailer();
						$mail->From = $ar_setup['setup_reg_from'];
						$mail->FromName = $ar_setup['setup_reg_from_name'];
						$mail->AddAddress($admin_email);
						$mail->CharSet = "utf-8";
						$mail->IsHTML(true);
						$mail->Mailer = $ar_setup['setup_reg_mailer'];
						$mail->Subject = $ar_setup['setup_lang_reg_subject'];
						// Pokud se registruje z progress obrazovky zaneseme do odkazu promennou, ktera poukazuje odkud bylo registrovano
						if ($_POST['action_shop'] == "user_reg"){$mail_body = '&reg_from=shop';}
						
						$mail->Body = "<html><head title=\"".$ar_setup['setup_lang_reg_subject']."\"/><body>";
						$mail->Body .= "<p><strong>"._REG_USERNAME."</strong> ".$admin_username."</p>";
						$mail->Body .= "<p><strong>"._REG_PASS."</strong> ".$_POST['admin_password1']."</p>";
						$mail->Body .= "<p>".$ar_setup['setup_lang_reg_text']."</p>";
						$mail->Body .= "<p><a href =\"".$eden_cfg['url']."index.php?action=allow_rg".$mail_body."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&rg_code=".$admin_reg_code."\">".$eden_cfg['url']."index.php?action=allow_rg".$mail_body."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&rg_code=".$admin_reg_code."</a></p>";
						$mail->Body .= "</body></html>";
						
						$mail->AltBody = _REG_USERNAME." ".$admin_username;
						$mail->AltBody .= _REG_PASS." ".$_POST['admin_password1']."\n";
						$mail->AltBody .= $ar_setup['setup_lang_reg_text'];
						$mail->AltBody .= $eden_cfg['url']."index.php?action=allow_rg".$mail_body."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&rg_code=".$admin_reg_code;
						
						$mail->WordWrap = 250;
						
						if (!$mail->Send()){
							if ($_POST['action_shop'] == "user_reg"){
								header ("Location: ".$eden_cfg['url']."index.php?action=01&lang=".$_GET['lang']."&filter=".$_GET['filter']."&action_shop=user_login&msg=reg_er_mail");
								exit;
							}elseif ($_POST['action_shop'] == "shop_check_del_address"){
								header ("Location: ".$eden_cfg['url']."index.php?action=03&lang=".$_GET['lang']."&filter=".$_GET['filter']."&action_shop=".$_POST['action_shop']."&msg=reg_er_mail&mode=edit_user");
								exit;
							} else {
								header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=reg_er_mail");
								exit;
							}
						} else {
							if ($_POST['action_shop'] == "user_reg"){
								header ("Location: ".$eden_cfg['url']."index.php?action=01&lang=".$_GET['lang']."&filter=".$_GET['filter']."&action_shop=user_login&msg=reg_ok_mail");
								exit;
							}elseif ($_POST['action_shop'] == "shop_check_del_address"){
								header ("Location: ".$eden_cfg['url']."index.php?action=03&lang=".$_GET['lang']."&filter=".$_GET['filter']."&action_shop=".$_POST['action_shop']."&msg=reg_ok_mail&mode=edit_user");
								exit;
							} else {
								// Kdyz je aktivace uzivatelskeho uctu povolena jen adminovi
								if ($ar_setup['setup_reg_over_email'] == 2){$msg = "reg_ok_mail_admin";} else {$msg = "reg_ok_mail";}
								header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
								exit;
							}
						}
					}
				}
			}
		} else {
			$confirm = "no";
			header ("Location: ".$eden_cfg['url']."index.php?action=".$_GET['action']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=reg_pass_arent_match&mode=".$_POST['mode']);
			exit;
		}
	/***********************************************************************************************************
	*
	*		ZAPOMENUTE HESLO
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "forgotten_pass"){
		$allowtags = "";
		// Z obsahu promenné body vyjmout nepovolené tagy
		$admin_uname = strtoupper($_POST['admin_uname']);
		$admin_uname = strip_tags($admin_uname,$allowtags);
		$admin_uname = str_ireplace( "'", "&acute;", $admin_uname);
		$admin_uname = str_ireplace( "’", "&rsquo;", $admin_uname);
		$admin_email = strip_tags(strtolower($_POST['admin_email']),$allowtags);
		$admin_email = str_ireplace( "'", "&acute;", $admin_email);
		$admin_email = str_ireplace( "’", "&rsquo;", $admin_email);

		if ($admin_uname != ""){
			$where = "WHERE admin_uname='".mysql_real_escape_string($admin_uname)."'";
		}elseif ($admin_email != ""){
			$where = "WHERE admin_email='".mysql_real_escape_string($admin_email)."'";
		} else {
			$where = "WHERE admin_uname='".mysql_real_escape_string($admin_uname)."' AND admin_email='".mysql_real_escape_string($admin_email)."'";
		}
		$res = mysql_query("SELECT * FROM $db_admin $where") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$num = mysql_num_rows($res);
		if ($num == 1){
			$ar = mysql_fetch_array($res);
			$admin_username = stripslashes($ar['admin_uname']);
			//$admin_pass = GeneratePass(6);
			//$p = MD5($admin_pass);
			mysql_query("UPDATE $db_admin SET admin_forgpass_check=1 WHERE admin_id=".(float)$ar['admin_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$mail = new PHPMailer();
			$mail->From = $ar_setup['setup_reg_from'];
			$mail->FromName = $ar_setup['setup_reg_from_name'];
			$mail->AddAddress($ar['admin_email']);
			$mail->CharSet = "utf-8";
			$mail->IsHTML(true);
			$mail->Mailer = $ar_setup['setup_reg_mailer'];
			$mail->Subject = $ar_setup['setup_lang_forgpass_subject'];
			
			$mail->Body = "<html><head title=\"".$ar_setup['setup_lang_forgpass_subject']."\"/><body>";
			$mail->Body .= "<p>".$ar_setup['setup_lang_forgpass_text']."</p>";
			$mail->Body .= "<p><strong>"._REG_USERNAME."</strong> ".$admin_username."</p>";
			$mail->Body .= "<p><a href =\"".$eden_cfg['url']."index.php?action=login&lang=".$_GET['lang']."&filter=".$_GET['filter']."&project=".$project."&pass_login=".$admin_username."&pass_code=".$ar['admin_password']."&sessid=".$_SESSION['sidd']."\">".$eden_cfg['url']."index.php?action=login&lang=".$_GET['lang']."&filter=".$_GET['filter']."&project=".$project."&pass_login=".$admin_username."&pass_code=".$ar['admin_password']."&sessid=".$_SESSION['sidd']."</a>\n";
			$mail->Body .= "</body></html>";
			
			$mail->AltBody = "\n".$ar_setup['setup_lang_forgpass_text']."\n\n";
			$mail->AltBody .= _REG_USERNAME." ".$admin_username."\n\n";
			$mail->AltBody .= $eden_cfg['url']."index.php?action=login&lang=".$_GET['lang']."&filter=".$_GET['filter']."&project=".$project."&pass_login=".$admin_username."&pass_code=".$ar['admin_password']."&sessid=".$_SESSION['sidd']."\n\n";
			
			$mail->WordWrap = 100;
			
			if (!$mail->Send()){
				header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=forgpass_er_mail");
				exit;
			} else {
				header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=forgpass_ok_mail");
				exit;
			}
		} else {
			header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=forgpass_baduser_or_email");
			exit;
		}
	/***********************************************************************************************************
	*
	*		SOUHLAS S PRAVIDLY
	*
	***********************************************************************************************************/
	}elseif ($_POST['mode'] == "web_terms_agreement"){
		if ($_POST['web_terms_agreement'] == 1){
			mysql_query("UPDATE $db_admin SET admin_agree_with_terms=1 WHERE admin_id=".(float)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=web_agreement_ok");
			exit;
		} else {
			header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=web_agreement_er");
			exit;
		}
	/***********************************************************************************************************
	*
	*		REGISTRACE DO CUPU
	*
	***********************************************************************************************************/
	}elseif ($_POST['mode'] == "cups_reg"){
		$allowtags = "";
		// Z obsahu promenné body vyjmout nepovolené tagy
		$cups_team_name = strip_tags($cups_team_name,$allowtags);
		$cups_team_tag = strip_tags($cups_team_tag,$allowtags);
		$cups_team_country = strip_tags($cups_team_country,$allowtags);
		$cups_team_cl_nick = strip_tags($cups_team_cl_nick,$allowtags);
		$cups_team_cl_icq = strip_tags($cups_team_cl_icq,$allowtags);
		$cups_team_cl_email = strip_tags($cups_team_cl_email,$allowtags);
		$cups_team_roster = strip_tags($cups_team_roster,$allowtags);
		$cups_team_cw_server = strip_tags($cups_team_cw_server,$allowtags);
		
		$res = mysql_query("SELECT * FROM $db_country WHERE country_id=".(float)$cups_team_country."") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar = mysql_fetch_array($res);
		
		include("./edencms/class.mail.php");
		$mail = new PHPMailer();
		$mail->From = $cups_team_cl_email;
		$mail->FromName = $cups_team_cl_nick ;
		$mail->AddAddress("leito@callofduty.cz");		
		$mail->CharSet = "utf-8";
		$mail->Mailer = "sendmail";
		
		$mail->Subject = "Registrace na eSuba Cup";
		$mail->Body = "Clan Name: ".$cups_team_name."\n
			Clan Tag: ".$cups_team_tag."\n
			Clan Country: ".$ar['country_name']."\n
			Clan Clan Leader: ".$cups_team_cl_nick."\n
			Clan Clan Leader ICQ: ".$cups_team_cl_icq."\n
			Clan Clan Leader Email: ".$cups_team_cl_email."\n
			Clan Roster: ".$cups_team_roster."\n
			Clan CW Server: ".$cups_team_cw_server."\n";
		$mail->WordWrap = 100;
		
		if (!$mail->Send()){
		header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=cups_reg_er_mail");
		exit;
		} else {
		header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=cups_reg_ok_mail");
		exit;
		}
	/***********************************************************************************************************
	*
	*		REGISTRACE DO CUPU
	*
	***********************************************************************************************************/
	}elseif ($_POST['mode'] == "cup_esuba_reg"){
		$res = mysql_query("SELECT admin_id, admin_nick, admin_email FROM $db_admin WHERE admin_id=".(float)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar = mysql_fetch_array($res);
		$allowtags = "";
		// Z obsahu promenné body vyjmout nepovolené tagy
		$cup_team_name = strip_tags($_POST['cup_team_name'],$allowtags);
		$cup_team_captain_nick = strip_tags($ar['admin_nick'],$allowtags);
		$cup_team_captain_xfire = strip_tags($_POST['cup_captain_xfire'],$allowtags);
		$cup_team_captain_email = strip_tags($ar['admin_email'],$allowtags);
		$cup_player_1_nick = strip_tags($_POST['cup_player_1_nick'],$allowtags);
		$cup_player_1_guid_pb = strip_tags($_POST['cup_player_1_guid_pb'],$allowtags);
		$cup_player_1_guid_xac = strip_tags($_POST['cup_player_1_guid_xac'],$allowtags);
		$cup_player_2_nick = strip_tags($_POST['cup_player_2_nick'],$allowtags);
		$cup_player_2_guid_pb = strip_tags($_POST['cup_player_2_guid_pb'],$allowtags);
		$cup_player_2_guid_xac = strip_tags($_POST['cup_player_2_guid_xac'],$allowtags);
		$cup_player_3_nick = strip_tags($_POST['cup_player_3_nick'],$allowtags);
		$cup_player_3_guid_pb = strip_tags($_POST['cup_player_3_guid_pb'],$allowtags);
		$cup_player_3_guid_xac = strip_tags($_POST['cup_player_3_guid_xac'],$allowtags);
		$cup_player_4_nick = strip_tags($_POST['cup_player_4_nick'],$allowtags);
		$cup_player_4_guid_pb = strip_tags($_POST['cup_player_4_guid_pb'],$allowtags);
		$cup_player_4_guid_xac = strip_tags($_POST['cup_player_4_guid_xac'],$allowtags);
		$cup_player_5_nick = strip_tags($_POST['cup_player_5_nick'],$allowtags);
		$cup_player_5_guid_pb = strip_tags($_POST['cup_player_5_guid_pb'],$allowtags);
		$cup_player_5_guid_xac = strip_tags($_POST['cup_player_5_guid_xac'],$allowtags);
		$cup_player_6_nick = strip_tags($_POST['cup_player_6_nick'],$allowtags);
		$cup_player_6_guid_pb = strip_tags($_POST['cup_player_6_guid_pb'],$allowtags);
		$cup_player_6_guid_xac = strip_tags($_POST['cup_player_6_guid_xac'],$allowtags);
		$cup_player_7_nick = strip_tags($_POST['cup_player_7_nick'],$allowtags);
		$cup_player_7_guid_pb = strip_tags($_POST['cup_player_7_guid_pb'],$allowtags);
		$cup_player_7_guid_xac = strip_tags($_POST['cup_player_7_guid_xac'],$allowtags);
		$cup_player_8_nick = strip_tags($_POST['cup_player_8_nick'],$allowtags);
		$cup_player_8_guid_pb = strip_tags($_POST['cup_player_8_guid_pb'],$allowtags);
		$cup_player_8_guid_xac = strip_tags($_POST['cup_player_8_guid_xac'],$allowtags);
		
		$res_country = mysql_query("SELECT country_name FROM $db_country WHERE country_id=".(float)$_POST['cup_team_country']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar_country = mysql_fetch_array($res_country);
		
		//include("./edencms/class.mail.php");
		$mail = new PHPMailer();
		$mail->CharSet = "utf-8";
		$mail->From = $cup_team_captain_email;
		$mail->FromName = $cup_team_captain_nick ;
		$mail->AddAddress("cup@esuba.eu");
		$mail->Mailer = $ar_setup['setup_reg_mailer'];
		
		$mail->Subject = "eSuba Cup Registration";
		$mail->Body = "Clan Name: ".$cup_team_name."\n
			Clan Country: ".stripslashes($ar_country['country_name'])."\n
			Clan Clan Leader: ".$cup_team_captain_nick."\n
			Clan Clan Leader XFire: ".$cup_team_captain_xfire."\n
			Clan Clan Leader Email: ".$cup_team_captain_email."\n\n
			Player 1
			Nick: ".$cup_player_1_nick."
			PB GUID: ".$cup_player_1_guid_pb."
			XAC GUID: ".$cup_player_1_guid_xac."\n
			
			Player 2
			Nick: ".$cup_player_2_nick."
			PB GUID: ".$cup_player_2_guid_pb."
			XAC GUID: ".$cup_player_2_guid_xac."\n
			
			Player 3
			Nick: ".$cup_player_3_nick."
			PB GUID: ".$cup_player_3_guid_pb."
			XAC GUID: ".$cup_player_3_guid_xac."\n
			
			Player 4
			Nick: ".$cup_player_4_nick."
			PB GUID: ".$cup_player_4_guid_pb."
			XAC GUID: ".$cup_player_4_guid_xac."\n
			
			Player 5
			Nick: ".$cup_player_5_nick."
			PB GUID: ".$cup_player_5_guid_pb."
			XAC GUID: ".$cup_player_5_guid_xac."\n
			
			Player 6
			Nick: ".$cup_player_6_nick."
			PB GUID: ".$cup_player_6_guid_pb."
			XAC GUID: ".$cup_player_6_guid_xac."\n
			
			Player 7
			Nick: ".$cup_player_7_nick."
			PB GUID: ".$cup_player_7_guid_pb."
			XAC GUID: ".$cup_player_7_guid_xac."\n
			
			Player 8
			Nick: ".$cup_player_8_nick."
			PB GUID: ".$cup_player_8_guid_pb."
			XAC GUID: ".$cup_player_8_guid_xac."\n";
		$mail->WordWrap = 100;
		
		if (!$mail->Send()){
	 		header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=cups_reg_er_mail");
			exit;
		} else {
			header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=cups_reg_ok_mail");
			exit;
		}
	/***********************************************************************************************************
	*
	*		ANKETA
	*
	***********************************************************************************************************/
	}elseif ($_GET['mode'] == "poll"){
		$link_back = urldecode($_GET['link_back']);
		$link_back = @htmlspecialchars_decode($link_back,ENT_QUOTES);
		if ($_GET['eden_poll_vote'] != ""){$eden_poll_vote = $_GET['eden_poll_vote'];} else {$eden_poll_vote = $_POST['eden_poll_vote'];}
		if ($eden_poll_vote != "" && $_GET['wid'] != ""){
			// Nacteme nastaveni anket
			// $ar3['setup_poll_iptime'] je pocet sekund, ktere musi uplynout aby se zapocitalo hlasovani se stejne IP pro danou otazku
			$res3 = mysql_query("SELECT setup_poll_iptime FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar3 = mysql_fetch_array($res3);
			// Nacteme do pole nejnovejsi datum odpovedi pro danou IP a otazku
			$res1 = mysql_query("SELECT MAX(poll_answers_date) FROM $db_poll_answers WHERE poll_answers_pid=".(integer)$_GET['wid']." AND poll_answers_ip=INET_ATON('".mysql_real_escape_string($eden_cfg['ip'])."')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar1 = mysql_fetch_array($res1);
			$at = (EdenGetMkTime($ar1[0],"Datetime") + $ar3['setup_poll_iptime']); // Doba pripojeni - prevedeme datum na UNIX epoch (sekundy od 1970)
			setcookie($project.'_poll_id', '', time() - 604800);
			setcookie($project.'_poll_id',$_GET['wid'], time() + 604800);
			if ($at < date("U")){
				mysql_query("INSERT INTO $db_poll_answers VALUES ('',".(integer)$_GET['wid'].",".(integer)$eden_poll_vote.",INET_ATON('".mysql_real_escape_string($eden_cfg['ip'])."'),NOW())") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				header ('Location: '.$eden_cfg['url'].$link_back.'&asid='.$_GET['wid'].'&msg=poll_ok');
				exit;
			} else {
				header ('Location: '.$eden_cfg['url'].$link_back.'&asid='.$_GET['wid'].'&msg=poll_voted');
				exit;
			}
		} else {
			header ('Location: '.$eden_cfg['url']);
			exit;
		}
	/***********************************************************************************************************
	*
	*		ANKETY UZIVATELU
	*
	***********************************************************************************************************/
	}elseif ($_POST['mode'] == "users_polls"){
		$res_p = mysql_query("SELECT MAX(poll_questions_date) FROM $db_poll_questions WHERE poll_questions_author=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		if ($ar_p = mysql_fetch_array($res_p)){$poll_date = $ar_p['poll_questions_date'];}else{$yest = time() + (24 * 60 * 60); $yesterday = date('Y-m-d H:i:s',$yest); $poll_date = $yesterday;}
		$tom = time() - (24 * 60 * 60);
		$tommorow = date('Y-m-d H:i:s',$tom);
		if ($poll_date > $tommorow){
			header ('Location: '.$eden_cfg['url']."index.php?action=users_polls&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=users_polls_24&answers=".$_POST['answers']."&pd=".$poll_date."&t=".$tommorow."");
			exit;
		} elseif ($_POST['question'] == ""){
			header ('Location: '.$eden_cfg['url']."index.php?action=users_polls&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=users_polls_noq&answers=".$_POST['answers']."&pq=".$_POST['question']."&pa_1=".$_POST['answer_1']."&pa_2=".$_POST['answer_2']."&pa_3=".$_POST['answer_3']."&pa_4=".$_POST['answer_4']."&pa_5=".$_POST['answer_5']."&pa_6=".$_POST['answer_6']."&pa_7=".$_POST['answer_7']."&pa_8=".$_POST['answer_8']."&pa_9=".$_POST['answer_9']."&pa_10=".$_POST['answer_10']."&pa_11=".$_POST['answer_11']."&pa_12=".$_POST['answer_12']."&pa_13=".$_POST['answer_13']."&pa_14=".$_POST['answer_14']."&pa_15=".$_POST['answer_15']."");
			exit;
		} elseif ($_POST['answer_1'] == "" || $_POST['answer_2'] == ""){
			header ('Location: '.$eden_cfg['url']."index.php?action=users_polls&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=users_polls_noa&answers=".$_POST['answers']."&pq=".$_POST['question']."&pa_1=".$_POST['answer_1']."&pa_2=".$_POST['answer_2']."&pa_3=".$_POST['answer_3']."&pa_4=".$_POST['answer_4']."&pa_5=".$_POST['answer_5']."&pa_6=".$_POST['answer_6']."&pa_7=".$_POST['answer_7']."&pa_8=".$_POST['answer_8']."&pa_9=".$_POST['answer_9']."&pa_10=".$_POST['answer_10']."&pa_11=".$_POST['answer_11']."&pa_12=".$_POST['answer_12']."&pa_13=".$_POST['answer_13']."&pa_14=".$_POST['answer_14']."&pa_15=".$_POST['answer_15']."");
			exit;
		}else{
			$question = PrepareForDB($_POST['question'],1,"",1);
			$answers = PrepareForDB($_POST['answer_1'],1,"",1);
			$answers .= "||".PrepareForDB($_POST['answer_2'],1,"",1);
			if ($_POST['answer_3'] != ""){$answers .= "||".PrepareForDB($_POST['answer_3'],1,"",1);}
			if ($_POST['answer_4'] != ""){$answers .= "||".PrepareForDB($_POST['answer_4'],1,"",1);}
			if ($_POST['answer_5'] != ""){$answers .= "||".PrepareForDB($_POST['answer_5'],1,"",1);}
			if ($_POST['answer_6'] != ""){$answers .= "||".PrepareForDB($_POST['answer_6'],1,"",1);}
			if ($_POST['answer_7'] != ""){$answers .= "||".PrepareForDB($_POST['answer_7'],1,"",1);}
			if ($_POST['answer_8'] != ""){$answers .= "||".PrepareForDB($_POST['answer_8'],1,"",1);}
			if ($_POST['answer_9'] != ""){$answers .= "||".PrepareForDB($_POST['answer_9'],1,"",1);}
			if ($_POST['answer_10'] != ""){$answers .= "||".PrepareForDB($_POST['answer_10'],1,"",1);}
			if ($_POST['answer_11'] != ""){$answers .= "||".PrepareForDB($_POST['answer_11'],1,"",1);}
			if ($_POST['answer_12'] != ""){$answers .= "||".PrepareForDB($_POST['answer_12'],1,"",1);}
			if ($_POST['answer_13'] != ""){$answers .= "||".PrepareForDB($_POST['answer_13'],1,"",1);}
			if ($_POST['answer_14'] != ""){$answers .= "||".PrepareForDB($_POST['answer_14'],1,"",1);}
			if ($_POST['answer_15'] != ""){$answers .= "||".PrepareForDB($_POST['answer_15'],1,"",1);}
			$language = PrepareForDB($_GET['lang'],1,"",1);
			mysql_query("INSERT INTO $db_poll_questions VALUES ('','$question','$answers','".(integer)$_SESSION['loginid']."','$language',2,NOW())") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			header ('Location: '.$eden_cfg['url']."index.php?action=users_polls&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=users_polls_ok");
			exit;
		}
	/***********************************************************************************************************
	*
	*		ODSTRANIT ANKETU UZIVATELE
	*
	***********************************************************************************************************/
	}elseif ($_POST['mode'] == "users_polls_del"){
		if ($_POST['confirm'] == "true"){
			mysql_query("DELETE FROM $db_poll_questions WHERE poll_questions_id=".(integer)$_POST['pid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			header ('Location: '.$eden_cfg['url']."index.php?action=users_polls&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=users_polls_del_ok");
			exit;
		}else{
			header ('Location: '.$eden_cfg['url']."index.php?action=users_polls&lang=".$_GET['lang']."&filter=".$_GET['filter']."");
			exit;
		}
	/***********************************************************************************************************
	*
	*		AJAX - Provereni nicku
	*
	***********************************************************************************************************/
	}elseif ($_GET['mode'] == "check_admin_nick"){
		$res = mysql_query("SELECT COUNT(*) AS num FROM $db_admin WHERE admin_nick='".mysql_real_escape_string($_GET['admin_nick'])."'") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar = mysql_fetch_array($res);
		if($ar['num'] > 0){
			echo "noooo";
		} else {
			echo "yeees";
		}
	/***********************************************************************************************************
	*
	*		FORUM
	*
	***********************************************************************************************************/
	}elseif ($_POST['mode'] == "forum"){
		
		// Zajistime aby byl vzdycky vyplnen nazev fora pred ulozenim
		if (empty($_POST['forum_topic_name'])){
			$confirmed = "FALSE";
			$msg = "no_topic_name";
		} else {
			$confirmed = "TRUE";
		}
		
		// Výcet povolených tagu
		$allowtags = "<ul>, <li>, <ol>, <p>, <br>, <font>, <strong>, <u>, <small>, <big>, <strong>, <em>, <a>, <img>";
		// Z obsahu promenné body vyjmout nepovolené tagy
		$forum_topic_name = strip_tags($_POST['forum_topic_name'],$allowtags);
		$forum_topic_comment = strip_tags($_POST['forum_topic_comment'],$allowtags);
		$forum_topic_comment = str_ireplace( "\n", "<br>",$forum_topic_comment);
		
		$tread = $_POST['friend_r'].$_POST['other_r'].$_POST['anon_r'];
		$tadd = $_POST['friend_w'].$_POST['other_w'].$_POST['anon_w'];
		$tdel = $_POST['friend_d'].$_POST['other_d'].$_POST['anon_d'];
		
		if ($_GET['faction'] == "edit_f" && $confirmed == "TRUE"){
			if (empty($_POST['forum_topic_megatopic'])){$megatopic = 0;} else {$megatopic = $_POST['forum_topic_megatopic'];}
			mysql_query("UPDATE $db_forum_topic SET forum_topic_parent_id=".(integer)$megatopic.", forum_topic_name='".mysql_real_escape_string($forum_topic_name)."', forum_topic_comment='".mysql_real_escape_string($forum_topic_comment)."',  forum_topic_admin_read='".mysql_real_escape_string($tread)."', forum_topic_admin_add='".mysql_real_escape_string($tadd)."', forum_topic_admin_del='".mysql_real_escape_string($tdel)."', forum_topic_importance=".(float)$_POST['forum_topic_importance']." WHERE forum_topic_id=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			//$res2 = mysql_query("SELECT * FROM $db_forum_topic WHERE forum_topic_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			//$ar2 = mysql_fetch_array($res2);
			unset ($_GET['faction']);
			if(!empty($_GET['id2'])){
				header ("Location: ".$eden_cfg['url']."index.php?action=forum&lang=".$_GET['lang']."&filter=".$_GET['filter']."&faction=topics&id0=".$_GET['id0']."&id1=".$_GET['id1']."&id2=".$_GET['id2']);
			} else {
				header ("Location: ".$eden_cfg['url']."index.php?action=forum&lang=".$_GET['lang']."&filter=".$_GET['filter']."&faction=tema&id0=".$_GET['id0']."&id1=".$_GET['id1']."&id2=".$_GET['id2']);
			}
			exit;
		} elseif ($_GET['faction'] == "add_f" && $confirmed == "TRUE"){
			mysql_query("INSERT INTO $db_forum_topic VALUES('','".(integer)$_POST['parent_topic']."','".(integer)$_SESSION['loginid']."','".mysql_real_escape_string($image)."','".mysql_real_escape_string($forum_topic_name)."','".mysql_real_escape_string($forum_topic_comment)."','".(float)$_SESSION['loginid']."','".mysql_real_escape_string($tread)."','".mysql_real_escape_string($tadd)."','".mysql_real_escape_string($tdell)."',NOW(),NOW(),'','".(float)$_POST['forum_topic_importance']."')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			unset ($_GET['faction']);
			header ("Location: ".$eden_cfg['url']."index.php?action=forum&lang=".$_GET['lang']."&filter=".$_GET['filter']."&faction=".$_GET['faction']."&id0=".$_GET['id0']."&id1=".$_GET['id1']."&id2=".$_GET['id2']);
			exit;
		} else {
			header ("Location: ".$eden_cfg['url']."index.php?action=forum&lang=".$_GET['lang']."&filter=".$_GET['filter']."&faction=".$_GET['faction']."&id0=".$_GET['id0']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&confirm=true&msg=".$msg);
			exit;
		}
	/***********************************************************************************************************
	*
	*		FORUM POSTS & PRIVATE MESSAGE
	*
	***********************************************************************************************************/
	}elseif (($_POST['confirm'] == "true" || $_GET['confirm'] == "true") && $_SESSION['loginid'] != "" && ($_POST['mode'] == "forum_add_posts" || $_GET['forum_add_posts'] || $_POST['mode'] == "forum_edit_posts" || $_POST['mode'] == "pm_add_post" || $_POST['mode'] == "forum_del_posts")){
		$allowtags = "";
		$forum_post = trim($_POST['forum_post']);
		$pm_post = trim($_POST['pm_post']);
		$forum_post_subject = strip_tags($_POST['forum_post_subject'],$allowtags);
		
		$res_posts = mysql_query("SELECT COUNT(*) FROM $db_forum_posts WHERE forum_posts_pid=".(integer)$_POST['tid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$num_posts = mysql_fetch_array($res_posts);
		
		$res_adm = mysql_query("SELECT admin_info_forum_posts_order FROM $db_admin_info WHERE aid=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar_adm = mysql_fetch_array($res_adm);
		
		if ($ar_adm['admin_info_forum_posts_order'] == 0){
			$stw2 = ($num_posts[0]/$ar_setup['setup_forum_posts_on_page']);
			$stw2 = (float)$stw2;
			if ($num_posts[0]%$ar_setup['setup_forum_posts_on_page'] > 0) {$stw2++;}
		} else {
			$stw2 = 1;
		}
		
		/* Pokud je zvolen checkbox Odeslat - zprava se normalne odesle */
		if ($_POST['form_send_mode'] == 1){
			/***********************************
			* Private Message
			***********************************/
			if ($_POST['mode'] == "pm_add_post"){
				if ($_POST['pm_rec'] == 0 || $_POST['pm_rec'] == ""){
					header ("Location: ".$eden_cfg['url']."index.php?action=forum&lang=".$_GET['lang']."&filter=".$_GET['filter']."&faction=pm&msg=forum_no_pm_rec&pm_post=".$_POST['pm_post']);
					exit;
				}elseif ($pm_post == ""){
					header ("Location: ".$eden_cfg['url']."index.php?action=forum&lang=".$_GET['lang']."&filter=".$_GET['filter']."&faction=pm&msg=forum_no_pm_post&pm_rec=".$_POST['pm_rec']);
					exit;
				}else{
					/* Prevedeme text z bb kodu do HTML */
					$pm_post_html = new BB_to_HTML_Code();
					$pm_post_html = $pm_post_html->parse($pm_post);
					
					/* Výcet povolených tagu */
					$allowtags = "<blockquote>";
					/* Z obsahu promenné body vyjmout nepovolené tagy */
					$pm_post_bb = strip_tags($pm_post,$allowtags);
					$pm_post_bb = nl2br($pm_post_bb);
					mysql_query("INSERT INTO $db_forum_pm VALUES('','".(integer)$_POST['pm_rec']."','".(integer)$_SESSION['loginid']."',NOW(),'".mysql_real_escape_string($pm_post_html)."','".mysql_real_escape_string($pm_post_bb)."','')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					
					header ("Location: ".$eden_cfg['url']."index.php?action=forum&lang=".$_GET['lang']."&filter=".$_GET['filter']."&faction=pm&pm_rec=".$_POST['pm_rec']);
					exit;
				}
			}
			/***********************************
			* Pridani prispevku do Fora
			***********************************/
			if (($_POST['mode'] == "forum_add_posts" || $_POST['mode'] == "forum_edit_posts") && $forum_post != ""){
				// Zajistime aby byl vzdycky vyplnen nazev fora pred ulozenim
				if (empty($_POST['forum_topic_name']) && $_GET['faction'] == "add_t"){
					$confirmed = "FALSE";
					$msg = "forum_no_topic_name";
					header ("Location: ".$eden_cfg['url']."index.php?action=forum&lang=".$_GET['lang']."&filter=".$_GET['filter']."&faction=add_t&id0=".$_GET['id0']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&forum_post=".urlencode($forum_post)."&forum_post_subject=".urlencode($forum_post_subject)."&confirm=true&msg=".$msg);
					exit;
				} else {
					$confirmed = "TRUE";
				}
				
				if ($_GET['faction'] == "add_t" && $confirmed == "TRUE"){
					// Výcet povolených tagu
					$allowtags = "<ul>, <li>, <ol>, <p>, <br>, <font>, <strong>, <u>, <small>, <big>, <strong>, <em>, <a>, <img>";
					// Z obsahu promenné body vyjmout nepovolené tagy
					$forum_topic_name = strip_tags($_POST['forum_topic_name'],$allowtags);
					$forum_topic_comment = strip_tags($_POST['forum_topic_comment'],$allowtags);
					$forum_topic_comment = str_ireplace( "\n", "<br>",$forum_topic_comment);
					
					$tread = $_POST['friend_r'].$_POST['other_r'].$_POST['anon_r'];
					$tadd = $_POST['friend_w'].$_POST['other_w'].$_POST['anon_w'];
					$tdel = $_POST['friend_d'].$_POST['other_d'].$_POST['anon_d'];
					mysql_query("INSERT INTO $db_forum_topic VALUES('','".(integer)$_POST['parent_topic']."','".(integer)$_SESSION['loginid']."','".mysql_real_escape_string($image)."','".mysql_real_escape_string($forum_topic_name)."','".mysql_real_escape_string($forum_topic_comment)."','".(float)$_SESSION['loginid']."','".mysql_real_escape_string($tread)."','".mysql_real_escape_string($tadd)."','".mysql_real_escape_string($tdell)."',NOW(),NOW(),'','".(float)$_POST['forum_topic_importance']."')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					$res_id = mysql_query("SELECT LAST_INSERT_ID()") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					$ar_id = mysql_fetch_array($res_id);
					$topic_id = $ar_id[0];
				}
				/* Prevedeme text z bb kodu do HTML */
				$forum_post_html = new BB_to_HTML_Code();
				$forum_post_html = $forum_post_html->parse($forum_post);
				
				/* Výcet povolených tagu */
				$allowtags = "<blockquote>";
				/* Z obsahu promenné body vyjmout nepovolené tagy */
				$forum_post_bb = strip_tags($forum_post,$allowtags);
				$forum_post_bb = nl2br($forum_post_bb);
				$forum_post_reason = strip_tags($_POST['forum_post_reason'],$allowtags);
				
				if ($_POST['mode'] == "forum_add_posts"){
					/* Pokud se pridava Topic, musi se zadat i jho ID */
					if ($_GET['faction'] == "add_t"){
						$tid = $topic_id;
						$_GET['id2'] = (integer)$topic_id;
					} else {
						$tid = (integer)$_POST['tid'];
					}
					mysql_query("INSERT INTO $db_forum_posts VALUES('','".$tid."','".(integer)$_SESSION['loginid']."',NOW(),'".mysql_real_escape_string($forum_post_subject)."','".mysql_real_escape_string($forum_post_html)."','".mysql_real_escape_string($forum_post_bb)."','')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					/* Updatujeme datum posledniho prispevku u topicu */
					mysql_query("UPDATE $db_forum_topic SET forum_topic_last_update=NOW() WHERE forum_topic_id=".(integer)$tid) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					/* Pricteme +1 k poctu zaslanych prispevku do fora */
					mysql_query("UPDATE $db_admin SET admin_posts_forum = admin_posts_forum + 1 WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				}elseif ($_POST['mode'] == "forum_edit_posts"){
					/* Nahradime 2 mezery dvema pevnyma mezerama, tak zajistime ze se nebude objevovat chyba Neocekavany kvantifikator */
					$forum_post = str_replace("  ","&nbsp;&nbsp;",$_POST['forum_post']);
					$forum_post_subject = str_replace("  ","&nbsp;&nbsp;",$_POST['forum_post_subject']);
					$forum_post_reason = str_replace("  ","&nbsp;&nbsp;",$_POST['forum_post_reason']);
					/* Musi byt zadan duvod editace */
					if ($forum_post_reason == ""){
						header ("Location: ".$eden_cfg['url']."index.php?action=forum&lang=".$_GET['lang']."&filter=".$_GET['filter']."&faction=post_preview&id0=".(integer)$_GET['id0']."&id1=".(integer)$_GET['id1']."&id2=".(integer)$_GET['id2']."&page=".(integer)$_GET['page']."&pid=".(float)$_POST['pid']."&forum_post=".urlencode($forum_post)."&forum_post_subject=".urlencode($forum_post_subject)."&forum_post_reason=".urlencode($forum_post_reason)."&msg=forum_edit_post_without_reason#editor");
						exit;
					} else {
						mysql_query("UPDATE $db_forum_posts SET forum_posts_subject='".mysql_real_escape_string($forum_post_subject)."', forum_posts_text='".mysql_real_escape_string($forum_post_html)."', forum_posts_text_bb='".mysql_real_escape_string($forum_post_bb)."' WHERE forum_posts_id=".(float)$_POST['pid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						/* Do logu ulozime informace o editaci */
						mysql_query("INSERT INTO $db_forum_posts_edit_log VALUES('','".(integer)$_SESSION['loginid']."','".(integer)$_POST['tid']."','".(integer)$_POST['pid']."',INET_ATON('".mysql_real_escape_string($eden_cfg['ip'])."'),NOW(),'".mysql_real_escape_string($forum_post_reason)."')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						/* Updatujeme datum posledniho prispevku u topicu */
						mysql_query("UPDATE $db_forum_topic SET forum_topic_last_update=NOW() WHERE forum_topic_id=".(integer)$_POST['tid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					}
				}
				
				unset ($_GET['faction']);
				header ("Location: ".$eden_cfg['url']."index.php?action=forum&lang=".$_GET['lang']."&filter=".$_GET['filter']."&faction=posts&id0=".$_GET['id0']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&page=".$stw2."#editor");
				exit;
			}
		/* Pokud je zvolen checkbox Nahled - zobrazi se nahled dane zpravy */
		} else {
			if ($_POST['mode'] == "forum_add_posts" || $_POST['mode'] == "post_preview"){
				
				// Zajistime aby byl vzdycky vyplnen nazev fora pred ulozenim
				if (empty($_POST['forum_topic_name']) && $_GET['faction'] == "add_t"){
					$confirmed = "FALSE";
					$msg = "forum_no_topic_name";
					header ("Location: ".$eden_cfg['url']."index.php?action=forum&lang=".$_GET['lang']."&filter=".$_GET['filter']."&faction=add_t&id0=".$_GET['id0']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&forum_post=".urlencode($forum_post)."&forum_post_subject=".urlencode($forum_post_subject)."&confirm=true&msg=".$msg);
					exit;
				} else {
					$confirmed = "TRUE";
				}
				
				if ($_GET['faction'] == "add_t" && $confirmed == "TRUE"){
					// Výcet povolených tagu
					$allowtags = "<ul>, <li>, <ol>, <p>, <br>, <font>, <strong>, <u>, <small>, <big>, <strong>, <em>, <a>, <img>";
					// Z obsahu promenné body vyjmout nepovolené tagy
					$forum_topic_name = strip_tags($_POST['forum_topic_name'],$allowtags);
					$forum_topic_comment = strip_tags($_POST['forum_topic_comment'],$allowtags);
					$forum_topic_comment = str_ireplace( "\n", "<br>",$forum_topic_comment);
					
					$tread = $_POST['friend_r'].$_POST['other_r'].$_POST['anon_r'];
					$tadd = $_POST['friend_w'].$_POST['other_w'].$_POST['anon_w'];
					$tdel = $_POST['friend_d'].$_POST['other_d'].$_POST['anon_d'];
					mysql_query("INSERT INTO $db_forum_topic VALUES('','".(integer)$_POST['parent_topic']."','".(integer)$_SESSION['loginid']."','".mysql_real_escape_string($image)."','".mysql_real_escape_string($forum_topic_name)."','".mysql_real_escape_string($forum_topic_comment)."','".(float)$_SESSION['loginid']."','".mysql_real_escape_string($tread)."','".mysql_real_escape_string($tadd)."','".mysql_real_escape_string($tdell)."',NOW(),NOW(),'','".(float)$_POST['forum_topic_importance']."')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					$res_id = mysql_query("SELECT LAST_INSERT_ID()") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					$ar_id = mysql_fetch_array($res_id);
					$_GET['id2'] = $ar_id[0];
				}
				
				/* Nahradime 2 mezery dvema pevnyma mezerama, tak zajistime ze se nebude objevovat chyba Neocekavany kvantifikator */
				$forum_post = str_replace("  ","&nbsp;&nbsp;",$_POST['forum_post']);
				$forum_post_subject = str_replace("  ","&nbsp;&nbsp;",$_POST['forum_post_subject']);
				$forum_post_reason = str_replace("  ","&nbsp;&nbsp;",$_POST['forum_post_reason']);
				header ("Location: ".$eden_cfg['url']."index.php?action=forum&lang=".$_GET['lang']."&filter=".$_GET['filter']."&faction=post_preview&id0=".$_GET['id0']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&page=".$stw2."&forum_post=".urlencode($forum_post)."&forum_post_subject=".urlencode($forum_post_subject)."&forum_post_reason=".urlencode($forum_post_reason)."#editor");
				exit;
			} elseif ($_POST['mode'] == "pm_add_post"){
				$pm_post = str_replace("  ","&nbsp;&nbsp;",$_POST['pm_post']);
				header ("Location: ".$eden_cfg['url']."index.php?action=forum&lang=".$_GET['lang']."&filter=".$_GET['filter']."&faction=pm_preview&user=".$_POST['user']."&pm_rec=".$_POST['pm_rec']."&pm_post=".urlencode($pm_post)."#editor");
				exit;
			}
			/***********************************
			* Odstraneni vybranych prispevku
			***********************************/
			if ($_POST['mode'] == "forum_del_posts"){
				$num = count($_POST['del']);
				$del = $_POST['del'];
				$z = 0;
				/* Kdyz je $z mensi nes pocet obrazku v danem clanku */
				while ($z < $num) {
					/* Kdyz je dany prispevek zatrhnuty ke smazani */
					$int_del = $del[$z];
					/* Odstrani se ze zaznamu v databazi */
					mysql_query("DELETE FROM $db_forum_posts WHERE forum_posts_id=".(integer)$int_del) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					/* Pricte se 1 aby mohlo cele kolo pokracovat */
					$z++;
				}
				unset ($_GET['faction']);
				header ("Location: ".$eden_cfg['url']."index.php?action=forum&lang=".$_GET['lang']."&filter=".$_GET['filter']."&faction=posts&id0=".$_GET['id0']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&page=".$stw2);
				exit;
			}
		}
	/***********************************************************************************************************
	*
	*		FORUM - REPORT POST
	*
	***********************************************************************************************************/
	}elseif ($_POST['confirm'] == "true" && $_SESSION['loginid'] != "" && ($_POST['mode'] == "forum_report_post")){
		if($_POST['forum_posts_report_it_reason'] == ""){
			header ("Location: ".$eden_cfg['url']."index.php?action=forum&lang=".$_GET['lang']."&filter=".$_GET['filter']."&faction=reportit&id0=".$_GET['id0']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&pid=".(integer)$_POST['pid']."&page=".$stw2."&msg=forum_report_without_reason#editor");
			exit;
		}else{
			$forum_posts_report_it_reason = strip_tags($_POST['forum_posts_report_it_reason'],"");
			mysql_query("UPDATE $db_forum_posts SET forum_posts_reported=1 WHERE forum_posts_id=".(integer)$_POST['pid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			/* Do logu ulozime informace o ohlaseni prispevku */
			mysql_query("INSERT INTO $db_forum_posts_report_log VALUES('','".(integer)$_SESSION['loginid']."','".(integer)$_POST['pid']."',INET_ATON('".mysql_real_escape_string($eden_cfg['ip'])."'),NOW(),'".mysql_real_escape_string($forum_posts_report_it_reason)."')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			header ("Location: ".$eden_cfg['url']."index.php?action=forum&lang=".$_GET['lang']."&filter=".$_GET['filter']."&faction=posts&id0=".$_GET['id0']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&page=".$stw2);
			exit;
		}
	/***********************************************************************************************************
	*
	*		FORUM - USER SETUP
	*
	***********************************************************************************************************/
	}elseif ($_POST['confirm'] == "true" && $_SESSION['loginid'] != "" && $_POST['mode'] == "forum_setup"){
		mysql_query("UPDATE $db_admin_info SET admin_info_forum_posts_order=".(integer)$_POST['admin_info_forum_posts_order']." WHERE aid=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		header ("Location: ".$eden_cfg['url']."index.php?action=forum&lang=".$_GET['lang']."&filter=".$_GET['filter']."&faction=setup");
		exit;
	/***********************************************************************************************************
	*
	*		CUSTOMIZE Zobrazeni polozek
	*
	***********************************************************************************************************/
	}elseif ($_POST['mode'] == "admin_custom_save"){
		$admin_num = count($_POST['admin_custom']);
		$admin_custom = $_POST['admin_custom'];
		$i = 1;
		while ($i <= $admin_num){
			mysql_query("UPDATE $db_admin_customize SET admin_customize_value='".(integer)$admin_custom['custom_'.$i]."' WHERE admin_customize_admin_id=".(integer)$_SESSION['loginid']." AND admin_customize_id=".(integer)$admin_custom['id_'.$i]) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$i++;
		}
		if ($_POST['admin_custom_skin'] != "" || $_POST['admin_custom_filter'] != ""){
			$admin_custom_skin = PrepareForDB($_POST['admin_custom_skin'],1,"",1);
			/* Do retezce oddelene carkami ulozime zvolene filtry */
			if ($_POST['admin_custom_filter'] != "") {$admin_info_filter = implode("||", $_POST['admin_custom_filter']);}
			mysql_query("UPDATE $db_admin_info SET admin_info_customize_skin='".$admin_custom_skin."', admin_info_filter='".mysql_real_escape_string($admin_info_filter)."' WHERE aid=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		}
		header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=edit_ok");
		exit;
	/***********************************************************************************************************
	*
	*		CONTACT FORM
	*
	***********************************************************************************************************/
	}elseif ($_POST['mode'] == "contact_form"){
		// CAPTCHA
		$eden_captcha = new EdenCaptcha($eden_cfg);
		if ($eden_captcha->CaptchaCheck() === TRUE || ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin")) {
			$allowtags = "";
			/* Z obsahu promenné body vyjmout nepovolené tagy */
			$_POST['contact_form_name'] = strip_tags($_POST['contact_form_name'],$allowtags);
			$_POST['contact_form_comment'] = strip_tags($_POST['contact_form_comment'],$allowtags);
			$_POST['contact_form_email'] = strip_tags(strtolower($_POST['contact_form_email']),$allowtags);
			
			if ($_POST['contact_form_name'] != "" && $_POST['contact_form_comment'] != ""){
				$mail = new PHPMailer();
				$mail->From = strtolower($_POST['contact_form_email']);
				$mail->FromName = $_POST['contact_form_name'] ;
				$mail->AddAddress($ar_setup['setup_contact_form_from']);
				$mail->CharSet = "utf-8";
				$mail->IsHTML(true);
				$mail->Mailer = $ar_setup['setup_reg_mailer'];
				$mail->Subject = $ar_setup['setup_lang_contact_form_subject'];
				
				$mail->Body = "<html><head title=\"".$ar_setup['setup_lang_contact_form_subject']."\"/><body>";
				$mail->Body .= $_POST['contact_form_comment'];
				$mail->Body .= "</body></html>";
				
				$mail->AltBody = $_POST['contact_form_comment'];
				
				$mail->WordWrap = 100;
				
				if (!$mail->Send()){
				header ("Location: ".$eden_cfg['url']."index.php?action=contact&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=contact_form_er_mail");
				exit;
				} else {
				header ("Location: ".$eden_cfg['url']."index.php?action=contact&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=contact_form_ok_mail");
				exit;
				}
				unset($_POST['contact_form_name'],$_POST['contact_form_email'],$_POST['contact_form_captcha'],$_POST['contact_form_comment']);
			}
		} else {
			// What happens when the CAPTCHA was entered incorrectly
			header ("Location: ".$eden_cfg['url']."index.php?action=contact&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=bad_captcha&cfc=".$_POST['contact_form_comment']."&cfn=".$_POST['contact_form_name']."&cfe=".strtolower($_POST['contact_form_email'])."");
			exit;
		}
	/***********************************************************************************************************
	*
	*		DICTIONARY
	*
	***********************************************************************************************************/
	}elseif ($_POST['mode'] == "dict_add_word" || $_POST['mode'] == "dict_add_rev"){
		
		$res_dict = mysql_query("SELECT dictionary_id FROM $db_dictionary WHERE dictionary_word='".mysql_real_escape_string($_POST['dictionary_word'])."'") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar_dict = mysql_fetch_array($res_dict);
		$num_dict = mysql_num_rows($res_dict);
		
		/* Proverime zda vyraz jiz existuje */
		if ($num_dict > 0){
			/* Pokud ano - odesleme zpravu o existenci vyrazu */
			header ("Location: ".$eden_cfg['url']."index.php?action=dict&lang=".$_GET['lang']."&filter=".$_GET['filter']."&mode=".$_POST['mode']."&id=".$ar_dict['dictionary_id']."&msg=dict_word_exist");
			exit;
		}else{
			/* Pokud ne - ulozime novy vyraz do databaze */
			if ($_POST['mode'] == "dict_add_word"){
				mysql_query("INSERT INTO $db_dictionary VALUES('','','".(integer)$_SESSION['loginid']."','".mysql_real_escape_string($_POST['dictionary_word'])."','".mysql_real_escape_string($_POST['dictionary_word_description'])."',NOW(),'0')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			}
			if ($_POST['mode'] == "dict_add_rev"){
				mysql_query("INSERT INTO $db_dictionary VALUES('','".(integer)$_GET['id']."','".(integer)$_SESSION['loginid']."','".mysql_real_escape_string($_POST['dictionary_word'])."','".mysql_real_escape_string($_POST['dictionary_word_description'])."',NOW(),'0')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			}
			$res_id = mysql_query("SELECT LAST_INSERT_ID()") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_id = mysql_fetch_array($res_id);
			$id = $ar_id[0];
			header ("Location: ".$eden_cfg['url']."index.php?action=dict&lang=".$_GET['lang']."&filter=".$_GET['filter']."&id=".$id."&msg=dict_add_ok");
			exit;
		}
	/***********************************************************************************************************
	*
	*		RECOMMEND TO FRIEND
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "rtf"){
		
		/* Nastaveni zobrazeni hlasek podle modulu */
		if ($_GET['action'] == "spec_t"){
			$res = mysql_query("SELECT shop_clothes_design_title, shop_clothes_design_description_short FROM $db_shop_clothes_design WHERE shop_clothes_design_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar = mysql_fetch_array($res);
			$error_empty = "rtf_empty_to_email_shop";
			$error_no = "rtf_no_to_email_shop";
			$email_subject_what = _RECOMMEND_EMAIL_FROM_SHOP_B;
			$email_text_what = _RECOMMEND_EMAIL_FROM_SHOP;
			$email_text_headline = stripslashes($ar['shop_clothes_design_title'])."\n\n".stripslashes($ar['shop_clothes_design_description_short']);
			$link = $eden_cfg['url']."index.php?action=spec_t&gen=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&did=".$_GET['id']."&project=".$_GET['project'];
		} elseif ($_GET['action'] == "komentar"){
			if ($_GET['modul'] == "news"){
				$res = mysql_query("SELECT news_headline FROM $db_news WHERE news_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$ar = mysql_fetch_array($ress);
				$error_empty = "rtf_empty_to_email_act";
				$error_no = "rtf_no_to_email_act";
				$email_subject_what = _RECOMMEND_EMAIL_FROM_ACT_B;
				$email_text_what = _RECOMMEND_EMAIL_FROM_ACT;
				$email_text_headline = stripslashes($ar['news_headline']);
				$link = $eden_cfg['url']."index.php?action=komentar&modul=news&lang=".$_GET['lang']."&filter=".$_GET['filter']."&id=".$_GET['id']."&page_mode=".$_GET['page_mode']."&project=".$_GET['project'];
			} elseif ($_GET['modul'] == "articles"){
				$res = mysql_query("SELECT article_headline FROM $db_articles WHERE article_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$ar = mysql_fetch_array($res);
				$error_empty = "rtf_empty_to_email_articles";
				$error_no = "rtf_no_to_email_articles";
				$email_subject_what = _RECOMMEND_EMAIL_FROM_ARTICLES_B;
				$email_text_what = _RECOMMEND_EMAIL_FROM_ARTICLES;
				$email_text_headline = stripslashes($ar['article_headline']);
				$link = $eden_cfg['url']."index.php?action=komentar&modul=articles&lang=".$_GET['lang']."&filter=".$_GET['filter']."&id=".$_GET['id']."&page_mode=".$_GET['page_mode']."&project=".$_GET['project'];
			}
		} else {
			$res = mysql_query("SELECT article_headline FROM $db_articles WHERE article_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar = mysql_fetch_array($res);
			$error_empty = "rtf_empty_to_email_articles";
			$error_no = "rtf_no_to_email_articles";
			$email_subject_what = _RECOMMEND_EMAIL_FROM_ARTICLES_B;
			$email_text_what = _RECOMMEND_EMAIL_FROM_ARTICLES;
			$email_text_headline = stripslashes($ar['article_headline']);
			$link = $eden_cfg['url']."index.php?action=clanek&lang=".$_GET['lang']."&filter=".$_GET['filter']."&id=".$_GET['id']."&page_mode=".$_GET['page_mode']."&project=".$_GET['project'];
		}
		
		/* Provereni zda vsechny pole jsou vyplneny spravne */
		if (strip_tags($_POST['recommend_from_name'],"") == "") {
			header ("Location: ".$eden_cfg['url']."index.php?action=recommend&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=rtf_empty_name");
			exit;
		} elseif (strip_tags(strtolower($_POST['recommend_from_email']),"") == "") {
			header ("Location: ".$eden_cfg['url']."index.php?action=recommend&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=rtf_empty_from_email");
			exit;
		} elseif (CheckEmail(strip_tags(strtolower($_POST['recommend_from_email']),"")) != 1){
			header ("Location: ".$eden_cfg['url']."index.php?action=recommend&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=rtf_no_from_email");
			exit;
		} elseif (strip_tags(strtolower($_POST['recommend_to_email']),"") == ""){
			header ("Location: ".$eden_cfg['url']."index.php?action=recommend&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$error_empty );
			exit;
		} elseif (CheckEmail(strip_tags(strtolower($_POST['recommend_to_email']),"")) != 1){
			header ("Location: ".$eden_cfg['url']."index.php?action=recommend&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$error_no);
			exit;
		} else {
			$mail = new PHPMailer();
			$mail->CharSet = $ar_shop_setup['shop_setup_email_charset'];
			$mail->From = $ar_shop_setup['shop_setup_email_from'];
			$mail->FromName = $ar_shop_setup['shop_setup_email_from_name'];
			$mail->AddAddress(strip_tags(strtolower($_POST['recommend_to_email']),""));
			$mail->Mailer = $ar_setup['setup_reg_mailer'];
			
			if (strip_tags($_POST['recommend_subject'],"") == ""){$mail->Subject = $eden_cfg['project_name'].": ".$email_subject_what._RECOMMEND_EMAIL_SUBJECT;} else {$mail->Subject = strip_tags($_POST['recommend_subject'],"");}
			if (strip_tags($_POST['recommend_msg'],"") != ""){$email_usr_msg = strip_tags($_POST['recommend_msg'],"")."\n";}
			$mail->Body = "\n
			"._RECOMMEND_EMAIL_TEXT_1.strip_tags($_POST['recommend_from_name'],"")." (".strip_tags(strtolower($_POST['recommend_from_email']),"").") "._RECOMMEND_EMAIL_TEXT_2.$email_text_what._RECOMMEND_EMAIL_TEXT_3."\n
			".$email_usr_msg."\n
			".$email_text_headline."
			"._RECOMMEND_EMAIL_TEXT_4.$email_text_what._RECOMMEND_EMAIL_TEXT_5."\n
			".$link."\n
			\n";
			$mail->WordWrap = 100;
			
			if (!$mail->Send()){
				header ("Location: ".$link."&msg=rtf_er");
				exit;
			} else {
				header ("Location: ".$link."&msg=rtf_ok");
				exit;
			}
		}
	/***********************************************************************************************************
	*
	*		LEAGUE TEAM - Registrace / Editace
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "league_team_reg" || $_POST['mode'] == "league_team_edit"){
		$league_team_name = PrepareForDB($_POST['league_team_name'],1,"",1);
		$league_team_tag = PrepareForDB($_POST['league_team_tag'],1,"",1);
		$league_team_web = PrepareForDB($_POST['league_team_web'],1,"",1);
		$league_team_irc = PrepareForDB($_POST['league_team_irc'],1,"",1);
		$league_team_motto = PrepareForDB($_POST['league_team_motto'],1,"",1);
		$league_team_server1 = PrepareForDB($_POST['league_team_server1'],1,"",1);
		$league_team_server2 = PrepareForDB($_POST['league_team_server2'],1,"",1);
		$league_team_server3 = PrepareForDB($_POST['league_team_server3'],1,"",1);
		$league_team_server4 = PrepareForDB($_POST['league_team_server4'],1,"",1);
		$league_team_comment = PrepareForDB($_POST['league_team_comment'],1,"",1);
		$league_team_pass = PrepareForDB($_POST['league_team_pass'],1,"",1);
		
		if($_POST['mode'] == "league_team_reg"){
			// Pokud najdeme jmeno teamu v databazi tak se odesle chybova hlaska
			$res = mysql_query("SELECT COUNT(*) FROM $db_league_teams WHERE league_team_name='".$league_team_name."'") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar= mysql_fetch_array($res);
			if ($ar[0] > 0){
				header ("Location: ".$eden_cfg['url']."index.php?action=league_team_reg&msg=league_team_name_exist");
				exit;
			}
			
			// Pokud najdeme ID uzivatele, ktery ma nejaky team jiz zaregistrovany odesle chybova hlaska
			$res = mysql_query("SELECT COUNT(*) FROM $db_league_teams WHERE league_team_owner_id=".(float)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar = mysql_fetch_array($res);
			if ($ar[0] > 0){
				header ("Location: ".$eden_cfg['url']."index.php?action=league_team_reg&msg=league_team_owner_have_team");
				exit;
			}
			
			// Pokud najdeme ID uzivatele, ktery ma nejaky team jiz zaregistrovany odesle chybova hlaska
			$res = mysql_query("SELECT COUNT(*) FROM $db_admin WHERE admin_id=".(float)$_SESSION['loginid']." AND admin_team_own_id<>0") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar = mysql_fetch_array($res);
			if ($ar[0] > 0){
				header ("Location: ".$eden_cfg['url']."index.php?action=league_team_reg&msg=league_team_owner_have_team");
				exit;
			}
			
			/****************************************************************/
			/* Zalozime novy team 											*/
			/****************************************************************/
			mysql_query("INSERT INTO $db_league_teams VALUES(
			'',
			'".(integer)$_SESSION['loginid']."',
			'".(integer)$_POST['league_team_country']."',
			'".$league_team_name."',
			'".$league_team_tag."',
			'".$league_team_web."',
			'".$league_team_irc."',
			'".$league_team_motto."',
			'".$league_team_server1."',
			'".$league_team_server2."',
			'".$league_team_server3."',
			'".$league_team_server4."',
			'',
			'".$league_team_pass."',
			'".$league_team_comment."',
			'',
			NOW(),
			'',
			NOW(),
			'0')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$res_id = mysql_query("SELECT LAST_INSERT_ID()") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_id = mysql_fetch_array($res_id);
			$team_id = $ar_id[0];
			LeagueAddToLOG (0,(integer)$team_id,0,0,0,50,"","","");		// Byl zalozen team
			/****************************************************************/
			/* Zalozime danemu teamu novy podteam 							*/
			/****************************************************************/
			mysql_query("INSERT INTO $db_league_teams_sub VALUES(
			'',
			'".(integer)$team_id."',
			'".(integer)$_POST['league_team_game']."',
			'',
			NOW(),
			'',
			NOW())") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$res_sub_id = mysql_query("SELECT LAST_INSERT_ID()") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_sub_id = mysql_fetch_array($res_sub_id);
			$team_sub_id = $ar_sub_id[0];
			LeagueAddToLOG (0,(integer)$team_id,(integer)$team_sub_id,0,(integer)$_POST['league_team_game'],56,"","","");									// Byl zalozen sub team
			/****************************************************************/
			/* Zalozime noveho hrace/majitele nebo upravime soucasneho 		*/
			/****************************************************************/
			$res_player = mysql_query("SELECT league_player_id, league_player_team_id, league_player_team_sub_id FROM $db_league_players WHERE league_player_admin_id=".(integer)$_SESSION['loginid']." AND league_player_game_id=".(integer)$_POST['league_team_game']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			// Hrac jiz ma zalozen ucet pro danou hru
			if ($ar_player = mysql_fetch_array($res_player)){
				// Odstranime hrace ze vsech lig, kde byl vybran v pripade ze liga je limitovana poctem hracu
		   		LeagueDelPlayerFromAllowed ((integer)$ar_player['league_player_team_sub_id'], (integer)$ar_player['league_player_id']);
				mysql_query("UPDATE $db_admin SET admin_team_own_id=".(integer)$team_id." WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				/*
				mysql_query("UPDATE $db_league_players SET 
				league_player_team_id=".(float)$team_id.", 
				league_player_team_sub_id=".(float)$team_sub_id.", 
				league_player_position_captain=1, 
				league_player_position_assistant=0, 
				league_player_position_player=0, 
				league_player_join_date=NOW() 
				WHERE league_player_id=".(float)$ar_player['league_player_id']." AND league_player_admin_id=".(float)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				*/
				LeagueAddPlayer($_SESSION['loginid'],1,(integer)$_POST['league_team_game'],$team_id,$team_sub_id,1,1,1);
				if ($ar_player['league_player_team_sub_id'] != 0){
					// Pokud je hrac uz clenem nejakeho tymu prejde do tymu noveho jako kapitan
					LeagueAddToLOG (0,(integer)$ar_player['league_player_team_id'],(integer)$ar_player['league_player_team_sub_id'],(integer)$_SESSION['loginid'],(integer)$_POST['league_team_game'],3,"","","");		// Player left team
					LeagueAddToLOG (0,(integer)$ar_player['league_player_team_id'],(integer)$ar_player['league_player_team_sub_id'],(integer)$_SESSION['loginid'],(integer)$_POST['league_team_game'],58,"","","");	// Team was left by player
				}
				LeagueAddToLOG (0,(integer)$team_id,(integer)$team_sub_id,(integer)$_SESSION['loginid'],(integer)$_POST['league_team_game'],12,"","","");		// Hrac zalozil team
				LeagueAddToLOG (0,(integer)$team_id,(integer)$team_sub_id,(integer)$_SESSION['loginid'],(integer)$_POST['league_team_game'],2,"","","");		// Hrac vstoupil do teamu
				LeagueAddToLOG (0,(integer)$team_id,(integer)$team_sub_id,(integer)$_SESSION['loginid'],(integer)$_POST['league_team_game'],57,"","","");		// Do tymu vstoupil hrac
				LeagueAddToLOG (0,(integer)$team_id,(integer)$team_sub_id,(integer)$_SESSION['loginid'],(integer)$_POST['league_team_game'],61,"","","");		// Team ziskal majitele
			// Hrac jeste nema zalozen ucet pro danou hru
			} else {
				mysql_query("UPDATE $db_admin SET admin_team_own_id=".(integer)$team_id." WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				/*
				mysql_query("INSERT INTO $db_league_players VALUES(
				'',
				'".(integer)$_SESSION['loginid']."',
				'".(integer)$_POST['league_team_game']."',
				'".(integer)$team_id."',
				'".(integer)$team_sub_id."',
				'1',
				'0',
				'0',
				'1',
				'1',
				NOW(),
				1)") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				*/
				LeagueAddPlayer($_SESSION['loginid'],1,(integer)$_POST['league_team_game'],$team_id,$team_sub_id,1,1,1);
				//LeagueAddToLOG (0,0,0,(integer)$_SESSION['loginid'],1,"","","");										// Zalozili jsme novy hracsky ucet
				LeagueAddToLOG (0,(integer)$team_id,(integer)$team_sub_id,(integer)$_SESSION['loginid'],(integer)$_POST['league_team_game'],12,"","","");		// Hrac zalozil team
				LeagueAddToLOG (0,(integer)$team_id,(integer)$team_sub_id,(integer)$_SESSION['loginid'],(integer)$_POST['league_team_game'],2,"","","");		// Hrac vstoupil do teamu
				LeagueAddToLOG (0,(integer)$team_id,(integer)$team_sub_id,(integer)$_SESSION['loginid'],(integer)$_POST['league_team_game'],57,"","","");		// Do tymu vstoupil hrac
				LeagueAddToLOG (0,(integer)$team_id,(integer)$team_sub_id,(integer)$_SESSION['loginid'],(integer)$_POST['league_team_game'],61,"","","");		// Team ziskal majitele
			}
			$msg = "league_team_add_ok";
		}
		
		if($_POST['mode'] == "league_team_edit"){
			mysql_query("UPDATE $db_league_teams SET 
			league_team_country_id=".(integer)$_POST['league_team_country'].", 
			league_team_tag='".$league_team_tag."', 
			league_team_web='".$league_team_web."', 
			league_team_irc='".$league_team_irc."', 
			league_team_motto='".$league_team_motto."', 
	  		league_team_server1='".$league_team_server1."', 
			league_team_server2='".$league_team_server2."', 
			league_team_server3='".$league_team_server3."', 
			league_team_server4='".$league_team_server4."', 
			league_team_comment='".$league_team_comment."',
			league_team_pass='".$league_team_pass."', 
			league_team_date_last_modified=NOW() 
			WHERE league_team_id=".(integer)$_GET['ltid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$msg = "league_team_edit_ok";
		}
		/****************************************/
		/* Uploadneme logo teamu 				*/
		/****************************************/
		if (($links_img = getimagesize($_FILES['league_team_logo']['tmp_name'])) != false){
			/* Zjistime zda neni soubor jineho typu nez je povoleno a pokud ne priradime extenzi */
			if ($links_img[2] == 1){
				$extenze = ".gif";
			} elseif ($links_img[2] == 2){
				$extenze = ".jpg";
			} elseif ($links_img[2] == 3){
				$extenze = ".png";
			} else {
				header ("Location: ".$eden_cfg['url']."index.php?action=league_team_reg&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=ftp_av_wft");
				exit;
			}
			/* Zjistime zda neni obrazek mensi, nez je povoleno */
			if ($links_img[0] < GetSetupImageInfo("league_team_logo","width") || $links_img[1] < GetSetupImageInfo("league_team_logo","height")){
				header ("Location: ".$eden_cfg['url']."index.php?action=league_team_reg&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=ftp_av_ts");
				exit;
			/* Zjistime zda neni obrazek vetsi, nez je povoleno */
			} elseif ($links_img[0] > GetSetupImageInfo("league_team_logo","width") || $links_img[1] > GetSetupImageInfo("league_team_logo","height")){
				header ("Location: ".$eden_cfg['url']."index.php?action=league_team_reg&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=ftp_av_tb");
				exit;
			/* Zjistime zda neni soubor vetsi nez je povoleno */
			} elseif ($_FILES['addlink_img']['size'] > GetSetupImageInfo("league_team_logo","filesize")){
				header ("Location: ".$eden_cfg['url']."index.php?action=league_team_reg&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=ftp_av_ftb");
				exit;
			} else {
				/* Spojeni s FTP serverem */
				$conn_id = ftp_connect($eden_cfg['ftp_server']);
				/* Prihlaseni pres uzivatelske jmeno a heslo na FTP server */
				$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
				ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
				
				/* Zjisteni stavu spojeni */
				if ((!$conn_id) || (!$login_result)){
					header ("Location: ".$eden_cfg['url']."index.php?action=league_team_reg&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=ftp_no_connection");
					exit;
				}
				/* Zjisti nazev souboru po ulozeni do docasneho adresare na serveru */
				$source_file = $_FILES['league_team_logo']['tmp_name'];
				$userfile_name = (integer)$_GET['ltid'].strtolower($extenze);
				/* Vlozi nazev souboru a cestu do konkretniho adresare */
				$destination_file = $ftp_path_league_team.$userfile_name;
				$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
				
				/* Zjisteni stavu uploadu */
				if (!$upload){
					/* Uzavreni komunikace se serverem */
					ftp_close($conn_id);
					header ("Location: ".$eden_cfg['url']."index.php?action=league_team_reg&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=ftp_ue");
					exit;
				} else {
					mysql_query("UPDATE $db_league_teams SET league_team_logo='".mysql_real_escape_string($userfile_name)."' WHERE league_team_id=".(integer)$_GET['ltid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					/* Pokud vse probehne jak ma znicime dane promenne a nastavime $checkupload */
					unset($source_file);
					unset($destination_file);
					unset($extenze);
					unset($_FILES['league_team_logo']);
					$checkupload = 1;
				}
			}
		}
		header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".(integer)$_GET['ltid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
		exit;
	/***********************************************************************************************************
	*
	*		LEAGUE GUIDS - Pridani / Editace
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "league_guid_add" || $_POST['mode'] == "league_guid_edit"){
		/*	Odstraneni prebytecnych znaku	*/
		$guid = $_POST['league_guid'];
		$guid = @htmlspecialchars_decode($guid,ENT_QUOTES);
		$allowtags = "";
		$guid = strip_tags($guid,$allowtags);
		if ($_POST['mode'] == "league_guid_add"){
			/* Otestujeme zda uz je GUID pro danou hru registrovany ci nikoliv*/
			$res_guid = mysql_query("SELECT aid FROM $db_admin_guids WHERE aid=".(integer)$_SESSION['loginid']." AND admin_guid_league_guid_id=".(integer)$_POST['league_guid_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			if (mysql_fetch_array($res_guid)){
				$msg = "league_guid_exist";
			} else {
				/****************************************/
				/* Zalozome novy GUID pro daneho uzivatele	*/
				/****************************************/
				mysql_query("INSERT INTO $db_admin_guids VALUES(
				'".(integer)$_SESSION['loginid']."',
				'',
				'".(integer)$_POST['league_guid_game_id']."',
				'".(integer)$_POST['league_guid_id']."',
				'".mysql_real_escape_string($guid)."',
				'GUID Added',
				NOW(),
				1)") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				/****************************************/
				/* Do LOGu ulozime co se odehralo 		*/
				/****************************************/
				LeagueAddToLOG (0,0,0,(integer)$_SESSION['loginid'],(integer)$_POST['league_guid_game_id'],7,mysql_real_escape_string($guid),"","");					// Byl pridan GUID
				$msg = "league_guid_add_ok";
			}
		}
		if ($_POST['mode'] == "league_guid_edit"){
			mysql_query("UPDATE $db_admin_guids SET 
			admin_guid_game_id=".(integer)$_POST['league_guid_game_id'].", 
			admin_guid_league_guid_id=".(integer)$_POST['league_guid_id'].", 
			admin_guid_guid='".mysql_real_escape_string($guid)."', 
			admin_guid_reason='".mysql_real_escape_string($_POST['league_guid_reason'])."', 
	  		admin_guid_added=NOW() 
			WHERE admin_guid_id=".(integer)$_GET['agid']." AND aid=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			/****************************************/
			/* Do LOGu ulozime co se odehralo 		*/
			/****************************************/
			LeagueAddToLOG (0,0,0,(integer)$_SESSION['loginid'],$_POST['league_guid_game_id'],8,mysql_real_escape_string($guid),mysql_real_escape_string($_POST['league_guid_old']),mysql_real_escape_string($_POST['league_guid_reason']));					// Byl pridan GUID
			$msg = "league_guid_edit_ok";
		}
		header ("Location: ".$eden_cfg['url']."index.php?action=user_edit&mode=guids&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
		exit;
	/***********************************************************************************************************
	*
	*		LEAGUE PLAYER ADD - Pridani hrace
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "team_player_check" || $_POST['mode'] == "team_player_add"){
		// Zobrazime hledaneho hrace, nebo zobrazime chybu
		if ($_POST['mode'] == "team_player_check"){
			/* Najdeme hrace podle ID nebo podle emailu */
			if ($_POST['league_player_id'] != ""){
				preg_match ("/^ID ([0-9]{1,}) - /", $_POST['league_player_id'], $regs);
    			$player_id = $regs[1];
				$where = "admin_id=".(integer)$player_id;
			} elseif ($_POST['league_player_email']){
				$where = "admin_email='".mysql_real_escape_string($_POST['league_player_email'])."'";
			} else {
				header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_add_player&ltid=".$_POST['ltid']."&ltsid=".$_POST['ltsid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=league_player_not_entered");
				exit;
			}
			$res_player = mysql_query("SELECT admin_id, admin_nick, admin_email FROM $db_admin WHERE $where") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			/* Pokud je hrac nalezen prejde se na zobrazeni potvrzeni pridani hrace kapitanem */
			if ($ar_player = mysql_fetch_array($res_player)){
				header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_player_add&ltid=".$_POST['ltid']."&ltsid=".$_POST['ltsid']."&pid=".$ar_player['admin_id']."&pe=".stripslashes($ar_player['admin_email'])."&pn=".stripslashes($ar_player['admin_nick'])."&lang=".$_GET['lang']."&filter=".$_GET['filter']);
				exit;
			/* Pokud hrac neni nalezen zobrazi se varovani */
			} else {
				header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_player_check&ltid=".$_POST['ltid']."&ltsid=".$_POST['ltsid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=league_player_non_exist");
				exit;
			}
		}
		
		// Kapitanu teamu zobrazime formular pro pridani hrace do temu
		if (LeagueCheckPrivileges("C",$_SESSION['loginid'],$_POST['ltid'],$_POST['ltsid']) == 1 || LeagueCheckPrivileges("O",$_SESSION['loginid'],$_POST['ltid'],$_POST['ltsid']) == $_POST['ltid']){
			// Pokud je liga locked nic se neulozi a uzivateli se zobrazi upozorneni 
			if (LeagueCheckIfLocked("S",$_POST['ltsid']) == true){
				$msg = "league_is_locked";
			} else {
				if ($_POST['mode'] == "team_player_add"){
					// Smazeme vsechny requesty, ktere jsou duplicitni hrac muze mit zalozen jen jeden request pro vstup do jednoho tymu)
					$res_request = mysql_query("SELECT COUNT(*) FROM $db_league_requests WHERE league_request_admin_id=".(integer)$_POST['pid']." AND league_request_team_sub_id=".(integer)$_POST['ltsid']." AND league_request_action=1") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					$ar_request = mysql_fetch_array($res_request);
					if ($ar_request[0] > 1){
						for ($i=1;$i<$ar_request[0];$i++){
							mysql_query("DELETE FROM $db_league_requests WHERE league_request_admin_id=".(integer)$_POST['pid']." AND league_request_team_sub_id=".(integer)$_POST['ltsid']." AND league_request_action=1 LIMIT 1") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						}
						// Odesleme zpravu, ze hrac uz ma request u tohoto tymu podany
						header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_player_check&ltid=".$_POST['ltid']."&ltsid=".$_POST['ltsid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=league_player_invite_exist");
						exit;
					}
					$res_admin = mysql_query("SELECT admin_email, admin_lang, admin_password FROM $db_admin WHERE admin_id=".(integer)$_POST['pid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					$ar_admin = mysql_fetch_array($res_admin);
					$res_team = mysql_query("SELECT lts.league_team_sub_id, lts.league_team_sub_team_id, lts.league_team_sub_game_id, a.admin_nick, lt.league_team_name, lt.league_team_id 
					FROM $db_league_teams_sub AS lts 
					JOIN $db_league_teams AS lt ON  lt.league_team_id=lts.league_team_sub_team_id 
					JOIN $db_admin AS a ON a.admin_id=".(integer)$_POST['pid']."
					WHERE league_team_sub_id=".(integer)$_POST['ltsid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					$ar_team = mysql_fetch_array($res_team);
					/****************************************
					* Zalozime request pro pridani hrace do teamu 
					* Request actions: 	0 	= zadna akce
					*					1	= zadost kapitana teamu hraci aby vstoupil do jeho teamu
					*					2	= zadost hrace zda muze vstoupit do teamu
					*****************************************/
					mysql_query("INSERT INTO $db_league_requests VALUES(
					'',
					'".(integer)$_POST['pid']."',
					'".(integer)$ar_team['league_team_sub_id']."',
					'1',
					NOW() )") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					$res_id = mysql_query("SELECT LAST_INSERT_ID()") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					$ar_id = mysql_fetch_array($res_id);
					$lrid = $ar_id[0];
					
					/* Nastavime regcode (tak se pozna hrac, ktereho jsme pozvali do teamu) */
					$reg_code = $ar_admin['admin_pass'].GeneratePass(15);
					mysql_query("UPDATE $db_admin SET admin_reg_code='".mysql_real_escape_string($reg_code)."' WHERE admin_id=".(integer)$_POST['pid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					
					/* Priprava tela zpravy pro odeslani */
					$body = stripslashes($ar_setup['setup_lang_league_email_body_add_player']);
					$body = str_replace("[{team_captain}]",$ar_team['admin_nick'] ,$body);
					$body = str_replace("[{team_name}]",$ar_team['league_team_name'] ,$body);
					$body = str_replace("[{team_request_link}]",$eden_cfg['url']."index.php?action=login&mode=team_player_confirm&lrid=".$lrid."&lang=".$ar_admin['admin_lang']."&reg_code=".$reg_code,$body);
					
					/* Odeslani emailu s vyzvou pro vstup do teamu hraci */
					$mail = new PHPMailer();
					$mail->CharSet = "utf-8";
					$mail->From = strtolower($ar_setup['setup_league_email']);
					$mail->FromName = $ar_setup['setup_league_from_name'];
					$mail->AddAddress($ar_admin['admin_email']);
					$mail->Mailer = $ar_setup['setup_reg_mailer'];
					
					$mail->Subject = $ar_setup['setup_lang_league_email_subject_add_player'];
					$mail->Body = $body;
					$mail->WordWrap = 100;
					
					if (!$mail->Send()){
					header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$ar_team['league_team_id']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=league_player_add_er_mail");
					exit;
					}
					$msg = "league_player_add_ok_mail";
				}
			}
		} else {
			$msg = "league_no_privilege";
		}
		header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$_POST['ltid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
		exit;
	/***********************************************************************************************************
	*
	*		LEAGUE PLAYER JOIN TEAM AGREEMENT - Souhlas nebo nesouhlas hrace se vstupem do teamu
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "team_player_agreed" || $_POST['mode'] == "team_player_disagreed"){
		if ($_POST['mode'] == "team_player_agreed"){
			// Znemoznime ulozeni cehokoliv nekomu jinemu, nez povolanemu hraci
			$res_pending = mysql_query("SELECT league_request_action, league_request_admin_id FROM $db_league_requests WHERE league_request_id=".(integer)$_POST['lrid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_pending = mysql_fetch_array($res_pending);
			
			$res_team = mysql_query("SELECT league_team_sub_id, league_team_sub_team_id, league_team_sub_game_id FROM $db_league_teams_sub WHERE league_team_sub_id=".(integer)$_POST['nltsid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_team = mysql_fetch_array($res_team);
			
			if ($ar_pending['league_request_action'] != 1 || $ar_pending['league_request_admin_id'] != $_SESSION['loginid']) {
				$msg = "league_no_privilege";
				header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
				exit;
			// Pokud je liga locked nic se neulozi a uzivateli se zobrazi upozorneni 
			} elseif (LeagueCheckIfLocked("S",$_POST['oltsid']) == true || LeagueCheckIfLocked("S",$_POST['nltsid']) == true){
				$msg = "league_is_locked";
				header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
				exit;
			} else {
				$res_player = mysql_query("SELECT league_player_id FROM $db_league_players WHERE league_player_admin_id=".(integer)$_SESSION['loginid']." AND league_player_game_id=".(integer)$ar_team['league_team_sub_game_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$num_player = mysql_num_rows($res_player);
				
				if ($_POST['oltid'] != 0 && $_POST['oltsid'] != 0){
					/* Hrac zmenil team*/
					$league_team_id = (integer)$_POST['nltid'];
					$league_team_sub_id = (integer)$_POST['nltsid'];
					$player_log_action = 11;
					$ar_player = mysql_fetch_array($res_player);
					// Odstranime hrace ze vsech lig, kde byl vybran v pripade ze liga je limitovana poctem hracu
		   			LeagueDelPlayerFromAllowed ((integer)$_POST['oltsid'], (integer)$ar_player['league_player_id']);
				} else {
					/* Hrac vstoupil do teamu*/
					$league_team_id = $ar_team['league_team_sub_team_id'];
					$league_team_sub_id = $ar_team['league_team_sub_id'];
					$player_log_action = 2;
				}
				
				/* Pokud jiz hrac v dane hre existuje, updatne se jeho team a sub team */
				if ($num_player > 0){
					LeagueAddPlayer($_SESSION['loginid'],1,$ar_team['league_team_sub_game_id'],$league_team_id,$league_team_sub_id,0,1,1);
			   		if (!empty($_POST['oltid'])) {
						LeagueAddToLOG (0,(integer)$_POST['oltid'],(integer)$_POST['oltsid'],(integer)$_SESSION['loginid'],$ar_team['league_team_sub_game_id'],3,"","",""); 		// Player vystoupil z teamu
						LeagueAddToLOG (0,(integer)$_POST['oltid'],(integer)$_POST['oltsid'],(integer)$_SESSION['loginid'],$ar_team['league_team_sub_game_id'],58,"","",""); 		// Team was left by player
					}
					LeagueAddToLOG (0,(integer)$league_team_id,(integer)$league_team_sub_id,(integer)$_SESSION['loginid'],$ar_team['league_team_sub_game_id'],2,"","",""); 	// Player vstoupil do teamu
					LeagueAddToLOG (0,(integer)$league_team_id,(integer)$league_team_sub_id,(integer)$_SESSION['loginid'],$ar_team['league_team_sub_game_id'],57,"","",""); 	// Team was joined by player
				} else {
					/* Zalozime hraci novy zaznam o tom, ze se stal hracem daneho teamu */
					LeagueAddPlayer($_SESSION['loginid'],1,$ar_team['league_team_sub_game_id'],$league_team_id,$league_team_sub_id,0,1,1);
					LeagueAddToLOG (0,(integer)$league_team_id,(integer)$league_team_sub_id,(integer)$_SESSION['loginid'],$ar_team['league_team_sub_game_id'],2,"","",""); // Player vstoupil do teamu
					LeagueAddToLOG (0,(integer)$league_team_id,(integer)$league_team_sub_id,(integer)$_SESSION['loginid'],$ar_team['league_team_sub_game_id'],57,"","",""); 	// Team was joined by player
				}
				/* Smazeme request */
				mysql_query("DELETE FROM $db_league_requests WHERE league_request_admin_id=".(integer)$_SESSION['loginid']." AND league_request_action=1") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				
				$msg = "league_team_player_agreed";
				header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$league_team_id."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
				exit;
			}
		} else {
			/* Smazeme request */
			mysql_query("DELETE FROM $db_league_requests WHERE league_request_admin_id=".(integer)$_SESSION['loginid']." AND league_request_action=1") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			
			/*	Do LOGu ulozime co se odehralo	*/
			LeagueAddToLOG (0,(integer)$_POST['nltid'],(integer)$_POST['nltsid'],(integer)$_SESSION['loginid'],(integer)$ar_team['league_team_sub_game_id'],19,"","",""); 			// Player odmitnul vstoupit do teamu
			$msg = "league_team_player_disagreed";
			header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
			exit;
		}
	/***********************************************************************************************************
	*
	*		LEAGUE TEAM JOIN TEAM AGREEMENT - Souhlas nebo nesouhlas kapitana / vlastnika se vstupem hrace do teamu
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "team_team_agreed" || $_POST['mode'] == "team_team_disagreed"){
		
		$res_team = mysql_query("SELECT league_team_sub_id, league_team_sub_team_id, league_team_sub_game_id FROM $db_league_teams_sub WHERE league_team_sub_id=".(integer)$_POST['nltsid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar_team = mysql_fetch_array($res_team);
		$res_pending = mysql_query("SELECT league_request_admin_id FROM $db_league_requests WHERE league_request_id=".(integer)$_POST['lrid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		switch ($_POST['mode']){
			case "team_team_agreed":
				// Znemoznime ulozeni cehokoliv nekomu jinemu, nez povolanemu hraci
				if (LeagueCheckPrivileges("O",$_SESSION['loginid'],$_POST['nltid'],$_POST['nltsid']) == $_POST['nltid'] || LeagueCheckPrivileges("C",$_SESSION['loginid'],$_POST['nltid'],$_POST['nltsid']) == 1){
					if (!$ar_pending = mysql_fetch_array($res_pending)){
						$msg = "eden_missing_request_id";
						header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$_POST['ltid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
						exit;
					} else {
						$res_player = mysql_query("SELECT league_player_id FROM $db_league_players WHERE league_player_admin_id=".(integer)$ar_pending['league_request_admin_id']." AND league_player_game_id=".(integer)$ar_team['league_team_sub_game_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						$num_player = mysql_num_rows($res_player);
						
						if ($_POST['oltid'] != 0 && $_POST['oltsid'] != 0){
							/* Hrac zmenil team*/
							$league_team_id = (integer)$_POST['nltid'];
							$league_team_sub_id = (integer)$_POST['nltsid'];
							$player_log_action = 11;
							$ar_player = mysql_fetch_array($res_player);
							// Odstranime hrace ze vsech lig, kde byl vybran v pripade ze liga je limitovana poctem hracu
		   					LeagueDelPlayerFromAllowed ((integer)$_POST['oltsid'], (integer)$ar_player['league_player_id']);
						} else {
							/* Hrac vstoupil do teamu*/
							$league_team_id = $ar_team['league_team_sub_team_id'];
							$league_team_sub_id = $ar_team['league_team_sub_id'];
							$player_log_action = 2;
						}
						/* Pokud jiz hrac v dane hre existuje, updatne se jeho team a sub team */
						if ($num_player > 0){
							LeagueAddPlayer($ar_pending['league_request_admin_id'],1,(integer)$ar_team['league_team_sub_game_id'],$league_team_id,$league_team_sub_id,0,1,1);
							
					   		if (!empty($_POST['oltid'])) {
								LeagueAddToLOG (0,(integer)$_POST['oltid'],(integer)$_POST['oltsid'],$ar_pending['league_request_admin_id'],(integer)$ar_team['league_team_sub_game_id'],3,"","",""); 		// Player vystoupil z teamu
								LeagueAddToLOG (0,(integer)$_POST['oltid'],(integer)$_POST['oltsid'],$ar_pending['league_request_admin_id'],(integer)$ar_team['league_team_sub_game_id'],58,"","",""); 		// Team was left by player
							}
							LeagueAddToLOG (0,(integer)$league_team_id,(integer)$league_team_sub_id,$ar_pending['league_request_admin_id'],(integer)$ar_team['league_team_sub_game_id'],2,"","",""); 	// Player vstoupil do teamu
							LeagueAddToLOG (0,(integer)$league_team_id,(integer)$league_team_sub_id,$ar_pending['league_request_admin_id'],(integer)$ar_team['league_team_sub_game_id'],57,"","",""); 	// Team was joined by player
						} else {
							/* Zalozime hraci novy zaznam o tom, ze se stal hracem daneho teamu */
							LeagueAddPlayer($ar_pending['league_request_admin_id'],1,(integer)$ar_team['league_team_sub_game_id'],$league_team_id,$league_team_sub_id,0,1,1);
							LeagueAddToLOG (0,(integer)$league_team_id,(integer)$league_team_sub_id,$ar_pending['league_request_admin_id'],(integer)$ar_team['league_team_sub_game_id'],2,"","",""); // Player vstoupil do teamu
							LeagueAddToLOG (0,(integer)$league_team_id,(integer)$league_team_sub_id,$ar_pending['league_request_admin_id'],(integer)$ar_team['league_team_sub_game_id'],57,"","",""); 	// Team was joined by player
						}
						/* Smazeme request */
						mysql_query("DELETE FROM $db_league_requests WHERE league_request_admin_id=".(integer)$ar_pending['league_request_admin_id']." AND league_request_action=2") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						
						$msg = "league_team_team_agreed";
						header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$league_team_id."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
						exit;
					}
				} else {
					$msg = "league_no_privilege";
					header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
					exit;
				}
			break;
			case  "team_team_disagreed":
				$ar_pending = mysql_fetch_array($res_pending);
				/* Smazeme request */
				mysql_query("DELETE FROM $db_league_requests WHERE league_request_id=".(integer)$_POST['lrid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				
				/*	Do LOGu ulozime co se odehralo	*/
				LeagueAddToLOG (0,(integer)$_POST['nltid'],(integer)$_POST['nltsid'],$ar_pending['league_request_admin_id'],(integer)$ar_team['league_team_sub_game_id'],68,"","",""); 			// Team refused to add player
				$msg = "league_team_team_disagreed";
				header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
				exit;
			break;
		}
	/***********************************************************************************************************
	*
	*		LEAGUE PLAYER LEAVE TEAM - Vystoupeni hrace z teamu
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "team_player_leave"){
		// If is league locked - nothing is saved and user gets notice
		if (LeagueCheckIfLocked("S",$_POST['ltsid']) == true){
			$msg = "league_is_locked";
		} else {
			$res_game = mysql_query("SELECT league_player_game_id FROM $db_league_players WHERE league_player_team_sub_id=".(integer)$_POST['ltsid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_game = mysql_fetch_array($res_game);
			
			$res_team_sub = mysql_query("SELECT COUNT(*) FROM $db_league_players WHERE league_player_admin_id=".(integer)$_SESSION['loginid']." AND league_player_team_id=".(integer)$_POST['ltid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$num_team_sub = mysql_fetch_array($res_team_sub);
			
			/* V pripade ze team a subteam opousti majitel, preda team vybranemu nastupci */
			if ($_POST['league_team_new_o'] != "" && LeagueCheckPrivileges("O",$_SESSION['loginid'],$_POST['ltid'],$_POST['ltsid']) == $_POST['ltid'] && $num_team_sub[0] == 1){
				$res_player = mysql_query("SELECT league_player_admin_id FROM $db_league_players WHERE league_player_id=".(integer)$_POST['league_team_new_o']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$ar_player = mysql_fetch_array($res_player);
				mysql_query("UPDATE $db_admin SET admin_team_own_id=".(integer)$_POST['ltid']." WHERE admin_id=".(integer)$ar_player['league_player_admin_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				mysql_query("UPDATE $db_admin SET admin_team_own_id=0 WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				mysql_query("UPDATE $db_league_teams SET league_team_owner_id=".(integer)$ar_player['league_player_admin_id']." WHERE league_team_id=".(integer)$_POST['ltid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				LeagueAddToLOG (0,(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$_SESSION['loginid'],$ar_game['league_player_game_id'],15,"","",""); 					// Player left ownership
				LeagueAddToLOG (0,(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$ar_player['league_player_admin_id'],$ar_game['league_player_game_id'],12,"","",""); 	// Player become owner
				LeagueAddToLOG (0,(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$ar_player['league_player_admin_id'],$ar_game['league_player_game_id'],64,"","","");   	// Team ownership was given to...
			}
			// In case captain is leaving the team, he pass team to choosen successor
			if ($_POST['league_team_new_c'] != "" && LeagueCheckPrivileges("C",$_SESSION['loginid'],$_POST['ltid'],$_POST['ltsid']) == 1){
				$res_player = mysql_query("SELECT lp.league_player_admin_id, a.admin_nick 
				FROM $db_league_players AS lp 
				JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
				WHERE lp.league_player_id=".(integer)$_POST['league_team_new_c']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$ar_player = mysql_fetch_array($res_player);
				mysql_query("UPDATE $db_league_players SET league_player_position_captain=1, league_player_position_assistant=0, league_player_position_player=0 WHERE league_player_id=".(integer)$_POST['league_team_new_c']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				LeagueAddToLOG (0,(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$_SESSION['loginid'],$ar_game['league_player_game_id'],17,$ar_player['admin_nick'],"","");					// Player gave captain position to someone else
				LeagueAddToLOG (0,(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$ar_player['league_player_admin_id'],$ar_game['league_player_game_id'],13,"","","");	// Player become captain
				LeagueAddToLOG (0,(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$ar_player['league_player_admin_id'],$ar_game['league_player_game_id'],62,"","","");	// Team get new captain
			}
			// In case owner is leaving the team (and he is last men in the team), team is going to hibernate
			if ($_POST['hit'] == 1 && LeagueCheckPrivileges("O",$_SESSION['loginid'],$_POST['ltid']) == $_POST['ltid']){
				// Check how many player are in sub teams
				$players = LeagueCheckHowManyPlayersInSubTeam((integer)$_POST['ltid']);
				if ($players == 0){
					mysql_query("UPDATE $db_admin SET admin_team_own_id=0 WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					mysql_query("UPDATE $db_league_teams SET league_team_owner_id=0, league_team_hibernate=1, league_team_date_last_modified=NOW() WHERE league_team_id=".(integer)$_POST['ltid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					LeagueAddToLOG (0,(integer)$_POST['ltid'],0,(integer)$_SESSION['loginid'],0,15,"","",""); 					// Player left ownership
					LeagueAddToLOG (0,(integer)$_POST['ltid'],0,0,0,69,"","",""); 	// Team went to hibernation
					// Delete all requests for players/ from players
					$res_sub = mysql_query("SELECT league_team_sub_id FROM $db_league_teams_sub WHERE league_team_sub_team_id=".(integer)$_POST['ltid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					while ($ar_sub = mysql_fetch_array($res_sub)){
						mysql_query("DELETE FROM $db_league_requests WHERE league_request_team_sub_id=".(integer)$ar_sub['league_team_sub_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					}
				}
			}
			// Odstranime hrace ze vsech lig, kde byl vybran v pripade ze liga je limitovana poctem hracu
			LeagueDelPlayerFromAllowed ((integer)$_POST['ltsid'], (integer)$_POST['lpid']);
			mysql_query("UPDATE $db_league_players SET 
			league_player_team_id=0, 
			league_player_team_sub_id=0, 
			league_player_position_captain=0, 
			league_player_position_assistant=0, 
			league_player_position_player=0, 
			league_player_team_confirm=0, 
			league_player_player_confirm=0, 
		  	league_player_join_date='1000-01-01 00:00:00', 
			league_player_main=0 
			WHERE league_player_id=".(integer)$_POST['lpid']." AND league_player_admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			LeagueAddToLOG (0,(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$_SESSION['loginid'],$ar_game['league_player_game_id'],3,"","","");		// Player left team
			LeagueAddToLOG (0,(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$_SESSION['loginid'],$ar_game['league_player_game_id'],58,"","","");	// Team was left by player
			$msg = "league_player_left_team";
		}
		header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
		exit;
	/***********************************************************************************************************
	*
	*		LEAGUE TEAM HIBERNATE - Hibernate team
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "team_hibernate"){
		// If is league locked - nothing is saved and user gets notice 
		if (LeagueCheckIfLocked("S",$_POST['ltsid']) == true){
			$msg = "league_is_locked";
		} else {
			// Check how many player are in sub teams
			$players = LeagueCheckHowManyPlayersInSubTeam((integer)$_POST['ltid']);
			
			if ($players > 0){
				$msg = "league_hibernate_players_in_team";
			} else {
				// Check if request is from owner
				if (LeagueCheckPrivileges("O",$_SESSION['loginid'],$_POST['ltid']) == $_POST['ltid']){
					mysql_query("UPDATE $db_admin SET admin_team_own_id=0 WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					mysql_query("UPDATE $db_league_teams SET league_team_owner_id=0, league_team_hibernate=1, league_team_date_last_modified=NOW() WHERE league_team_id=".(integer)$_POST['ltid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					LeagueAddToLOG (0,(integer)$_POST['ltid'],0,(integer)$_SESSION['loginid'],0,15,"","",""); 					// Player left ownership
					LeagueAddToLOG (0,(integer)$_POST['ltid'],0,0,0,69,"","",""); 	// Team went to hibernation
					// Delete all requests for players/ from players
					$res_sub = mysql_query("SELECT league_team_sub_id FROM $db_league_teams_sub WHERE league_team_sub_team_id=".(integer)$_POST['ltid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					while ($ar_sub = mysql_fetch_array($res_sub)){
						mysql_query("DELETE FROM $db_league_requests WHERE league_request_team_sub_id=".(integer)$ar_sub['league_team_sub_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					}
				}
				$msg = "league_hibernate_ok";
			}
		}
		header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
		exit;
	/***********************************************************************************************************
	*
	*		TEAM PLAYER KICK - Kick hrace z teamu
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "team_player_kick"){
		if (LeagueCheckPrivileges("O",$_SESSION['loginid'],$_POST['ltid'],$_POST['ltsid']) == $_POST['ltid'] || LeagueCheckPrivileges("C",$_SESSION['loginid'],$_POST['ltid'],$_POST['ltsid']) == 1){
			// If is league locked - nothing is saved and user gets notice
			if (LeagueCheckIfLocked("S",$_POST['ltsid']) == true){
				$msg = "league_is_locked";
			} else {
				$res_player_old = mysql_query("SELECT league_player_admin_id, league_player_game_id FROM $db_league_players WHERE league_player_id=".(integer)$_POST['pid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$ar_player_old = mysql_fetch_array($res_player_old);
				$player_old = $ar_player_old['league_player_admin_id'];
				/* V pripade ze z team je vyhazovan kapitan, je treba urcit jeho nastupce*/
				if ($_POST['league_team_new_c'] != ""){
					$res_player_new = mysql_query("SELECT league_player_admin_id, league_player_team_id, league_player_team_sub_id FROM $db_league_players WHERE league_player_id=".(integer)$_POST['league_team_new_c']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					$ar_player_new = mysql_fetch_array($res_player_new);
					$ltid = $ar_player_new['league_player_team_id'];
					$ltsid = $ar_player_new['league_player_team_sub_id'];
					mysql_query("UPDATE $db_league_players SET league_player_position_captain=1, league_player_position_assistant=0, league_player_position_player=0 WHERE league_player_id=".(integer)$_POST['league_team_new_c']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					LeagueAddToLOG (0,(integer)$ltid,(integer)$ltsid,(integer)$ar_player_old['league_player_admin_id'],(integer)$ar_player_old['league_player_game_id'],16,"","","");		// Players captain position was taken
					LeagueAddToLOG (0,(integer)$ltid,(integer)$ltsid,(integer)$ar_player_new['league_player_admin_id'],(integer)$ar_player_old['league_player_game_id'],13,"","","");		// Player become captain
					LeagueAddToLOG (0,(integer)$ltid,(integer)$ltsid,(integer)$ar_player_new['league_player_admin_id'],(integer)$ar_player_old['league_player_game_id'],62,"","","");		// Team get new captain
				}
				/* Odstranime hrace ze vsech lig, kde byl vybran v pripade ze liga je limitovana poctem hracu */
				LeagueDelPlayerFromAllowed ((integer)$_POST['ltsid'], (integer)$_POST['pid']);
				/* Prenastavime hraci vsechny udaje v uctu pro danou hru tak, ze neni clenem zadneho tymu */
				mysql_query("UPDATE $db_league_players SET 
				league_player_team_id=0, 
				league_player_team_sub_id=0, 
				league_player_position_captain=0, 
				league_player_position_assistant=0, 
				league_player_position_player=1, 
				league_player_team_confirm=0, 
				league_player_player_confirm=0, 
			  	league_player_join_date='1000-01-01 00:00:00', 
				league_player_main=0 
				WHERE league_player_id=".(integer)$_POST['pid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				
				LeagueAddToLOG (0,(integer)$_POST['ltid'],0,(integer)$player_old,(integer)$ar_player_old['league_player_game_id'],4,"","","");		// Player was kicked from team
				LeagueAddToLOG (0,(integer)$_POST['ltid'],0,(integer)$player_old,(integer)$ar_player_old['league_player_game_id'],59,"","","");		// Team kicked player
				$msg = "league_player_kicked";
			}
		} else {
			$msg = "league_no_privilege";
		}
		header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$_POST['ltid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
		exit;
	/***********************************************************************************************************
	*
	*		TEAM PLAYER JOIN - Zazadani hrace o vstup do teamu
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "team_player_join_request"){
		// Najdeme ID hry podle sub teamu do ktereho chce hrac vstoupit 
		$res_game = mysql_query("SELECT league_team_sub_game_id FROM $db_league_teams_sub WHERE league_team_sub_id=".(integer)$_POST['ltsid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		if ($ar_game = mysql_fetch_array($res_game)){
			$res_request = mysql_query("SELECT COUNT(*) FROM $db_league_requests WHERE league_request_team_sub_id=".(integer)$_POST['ltsid']." AND league_request_admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_request = mysql_fetch_array($res_request);
			if ($ar_request[0] <> 0){
				// Odstranime vsechny prebytecne requesty
				for ($i=1;$i<$ar_request[0];$i++){
					mysql_query("DELETE FROM $db_league_requests WHERE league_request_admin_id=".(integer)$_SESSION['loginid']." AND league_request_team_sub_id=".(integer)$_POST['ltsid']." AND league_request_action=2 LIMIT 1") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				}
				$msg = "league_team_join_request_exist";
				header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$_POST['ltid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
				exit;
			}
			$res_player_game = mysql_query("SELECT COUNT(*) FROM $db_league_players WHERE league_player_admin_id=".(integer)$_SESSION['loginid']." AND league_player_team_sub_id=".(integer)$_POST['ltsid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_player_game = mysql_fetch_array($res_player_game);
			if ($ar_player_game[0] <> 0){
				$msg = "league_team_join_request_inteam";
				header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$_POST['ltid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
				exit;
			}
		} else {
			$msg = "eden_missing_subteam_id";
			header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$_POST['ltid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
			exit;
		}
		
		// Dotazeme se zda uz je hrac v nejakem tymu registrovan
		$res_player = mysql_query("SELECT league_player_id FROM $db_league_players WHERE league_player_admin_id=".(integer)$_SESSION['loginid']." AND league_player_game_id=".(integer)$ar_game['league_team_sub_game_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar_player = mysql_fetch_array($res_player);
		$num_player = mysql_num_rows($res_player);
		if ($num_player == 0){
			// Zalozime novy hracsky ucet
			LeagueAddPlayer((integer)$_SESSION['loginid'],0,(integer)$ar_game['league_team_sub_game_id'],0,0,0,0,0);
		}
		//$num_player = mysql_num_rows($res_player);
		
		/****************************************
		* Zalozime request pro pridani hrace do teamu 
		* Request actions: 	0 	= zadna akce
		*					1	= zadost kapitana teamu hraci aby vstoupil do jeho teamu
		*					2	= zadost hrace zda muze vstoupit do teamu
		*****************************************/
		mysql_query("INSERT INTO $db_league_requests VALUES(
		'',
		'".(integer)$_SESSION['loginid']."',
		'".(integer)$_POST['ltsid']."',
		'2',
		NOW() )") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$msg = "league_team_join_request";
		header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$_POST['ltid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
		exit;
	/***********************************************************************************************************
	*
	*		TEAM GIVE TO PLAYER - Predani hraci bud vlastnictvi, kapitanstvi nebo asistentstvi
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "team_player_make_o" || $_POST['mode'] == "team_player_make_c" || $_POST['mode'] == "team_player_make_a" || $_POST['mode'] == "team_player_make_p"){
		/* Zjistime nick hrace, komu jsme neco predali */
		$res_player = mysql_query("SELECT a.admin_nick, lp.league_player_game_id 
		FROM $db_admin AS a 
		JOIN $db_league_players AS lp ON lp.league_player_admin_id=a.admin_id AND lp.league_player_team_sub_id=".$_POST['ltsid']." 
		WHERE a.admin_id=".(integer)$_POST['aid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar_player = mysql_fetch_array($res_player);
		/* V pripade ze chceme predat vlastnictvi teamu */
		if ($_POST['mode'] == "team_player_make_o"){
			if (LeagueCheckPrivileges("O",$_SESSION['loginid'],$_POST['ltid'],$_POST['ltsid']) == $_POST['ltid']){
				mysql_query("UPDATE $db_admin SET admin_team_own_id=0 WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				mysql_query("UPDATE $db_admin SET admin_team_own_id=".(integer)$_POST['ltid']." WHERE admin_id=".(integer)$_POST['aid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				mysql_query("UPDATE $db_league_teams SET league_team_owner_id=".(integer)$_POST['aid']." WHERE league_team_owner_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				LeagueAddToLOG (0,(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$_SESSION['loginid'],$ar_player['league_player_game_id'],15,"","","");	// Player left ownership
				LeagueAddToLOG (0,(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$_POST['aid'],$ar_player['league_player_game_id'],12,"","","");			// Player become owner
				LeagueAddToLOG (0,(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$_POST['aid'],$ar_player['league_player_game_id'],64,stripslashes($ar_player['admin_nick']),"","");   		// Team ownership was given to...
				$msg = "league_player_make_o";
			} else {
				$msg = "league_no_privilege";
			}
			header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$_POST['ltid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
			exit;
		}
		/* V pripade ze chceme udelit kapitanstvi v teamu */
		if ($_POST['mode'] == "team_player_make_c"){
			 if (LeagueCheckPrivileges("O",$_SESSION['loginid'],$_POST['ltid'],$_POST['ltsid']) == $_POST['ltid'] || LeagueCheckPrivileges("C",$_SESSION['loginid'],$_POST['ltid'],$_POST['ltsid']) == 1){
			 	$res_old_captain = mysql_query("SELECT league_player_id, league_player_game_id FROM $db_league_players WHERE league_player_team_sub_id=".(integer)$_POST['ltsid']." AND league_player_position_captain=1") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$ar_old_captain = mysql_fetch_array($res_old_captain);
				mysql_query("UPDATE $db_league_players SET league_player_position_captain=0, league_player_position_assistant=1, league_player_position_player=0 WHERE league_player_id=".(integer)$ar_old_captain['league_player_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				mysql_query("UPDATE $db_league_players SET league_player_position_captain=1, league_player_position_assistant=0, league_player_position_player=0  WHERE league_player_id=".(integer)$_POST['pid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				LeagueAddToLOG (0,(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$_SESSION['loginid'],(integer)$ar_old_captain['league_player_game_id'],17,stripslashes($ar_player['admin_nick']),"","");	// Player gave captain position to someone else
				LeagueAddToLOG (0,(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$_POST['aid'],$ar_player['league_player_game_id'],13,"","","");							// Player become captain
				LeagueAddToLOG (0,(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$_POST['aid'],$ar_player['league_player_game_id'],62,"","","");							// Team get new captain
				$msg = "league_player_make_c";
			} else {
				$msg = "league_no_privilege";
			}
			header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$_POST['ltid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
			exit;
		}
		/* V pripade ze chceme udelit asistentskou pozici */
		if ($_POST['mode'] == "team_player_make_a"){
			if (LeagueCheckPrivileges("O",$_SESSION['loginid'],$_POST['ltid'],$_POST['ltsid']) == $_POST['ltid'] || LeagueCheckPrivileges("C",$_SESSION['loginid'],$_POST['ltid'],$_POST['ltsid']) == 1){
				mysql_query("UPDATE $db_league_players SET league_player_position_captain=0, league_player_position_assistant=1, league_player_position_player=0  WHERE league_player_id=".(integer)$_POST['pid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				LeagueAddToLOG (0,(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$_POST['aid'],$ar_player['league_player_game_id'],14,"","","");			// Player become assistant
				LeagueAddToLOG (0,(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$_POST['aid'],$ar_player['league_player_game_id'],63,"","","");			// Team get new assistant
				$msg = "league_player_make_a";
			} else {
				$msg = "league_no_privilege";
			}
			header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$_POST['ltid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
			exit;
		}
		/* V pripade ze chceme udelit hracskou pozici */
		if ($_POST['mode'] == "team_player_make_p"){
			if (LeagueCheckPrivileges("O",$_SESSION['loginid'],$_POST['ltid'],$_POST['ltsid']) == $_POST['ltid'] || LeagueCheckPrivileges("C",$_SESSION['loginid'],$_POST['ltid'],$_POST['ltsid']) == 1){
				mysql_query("UPDATE $db_league_players SET league_player_position_captain=0, league_player_position_assistant=0, league_player_position_player=1  WHERE league_player_id=".(integer)$_POST['pid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				LeagueAddToLOG (0,(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$_POST['aid'],$ar_player['league_player_game_id'],18,"","","");			// Players assistant position was teaken
				LeagueAddToLOG (0,(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$_POST['aid'],$ar_player['league_player_game_id'],66,"","","");			// Team took assistant position
				$msg = "league_player_make_p";
			} else {
				$msg = "league_no_privilege";
			}
			header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$_POST['ltid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
			exit;
		}
	/***********************************************************************************************************
	*
	*		REGISTRACE TEAMU DO LIGY
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "team_league_reg"){
		if (LeagueCheckPrivileges("O",$_SESSION['loginid'],$_POST['ltid'],$_POST['ltsid']) == $_POST['ltid'] || LeagueCheckPrivileges("C",$_SESSION['loginid'],$_POST['ltid'],$_POST['ltsid']) == 1){
			// Pokud je liga locked nic se neulozi a uzivateli se zobrazi upozorneni 
			if (LeagueCheckIfLocked("L",$_POST['lid']) == True){
				$msg = "league_is_locked";
			} elseif ($_POST['league_reg_agree'] == 1){
				$res_game = mysql_query("SELECT league_player_game_id FROM $db_league_players WHERE league_player_team_sub_id=".(integer)$_POST['ltsid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$ar_game = mysql_fetch_array($res_game);
				/* Zkontrolujeme zda-li uz je team registrovany v dane lize */
				$res_team = mysql_query("SELECT COUNT(*) FROM $db_league_teams_sub_leagues WHERE league_teams_sub_league_league_id=".(integer)$ar_leagues['league_league_id']." AND league_teams_sub_league_team_id=".(integer)$_GET['ltid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$num_team = mysql_fetch_array($res_team);
				if ($num_team[0] == 0){
					/* Zalozime zaznam o vstupu teamu do ligy */
					mysql_query("INSERT INTO $db_league_teams_sub_leagues VALUES(
					'',
					'".(integer)$_POST['ltid']."',
					'".(integer)$_POST['ltsid']."',
					'".(integer)$_POST['lid']."',
					NOW(),
					'1',
					'',
					'1')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					LeagueAddToLOG ((integer)$_POST['lid'],(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$_SESSION['loginid'],$ar_game['league_player_game_id'],51,"","","");			// Team joined league
					$msg = "league_team_reg_to_league";
				} else {
					$msg = "league_team_reg_already";
				}
			} else {
				$msg = "league_team_reg_no_agree";
			}
		} else {
			$msg = "league_no_privilege";
		}
		header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$_POST['ltid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
		exit;
	/***********************************************************************************************************
	*
	*		REGISTRACE HRACE DO LIGY
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "player_league_reg"){
		// Pokud je liga locked nic se neulozi a uzivateli se zobrazi upozorneni 
		if (LeagueCheckIfLocked("L",$_POST['lid']) == true){
			$msg = "league_is_locked";
		} elseif ($_POST['league_reg_agree'] == 1){
			/* Zkontrolujeme zda-li uz je hrac registrovany v dane lize */
			$res_player = mysql_query("SELECT COUNT(*) FROM $db_league_players_leagues WHERE league_player_league_league_id=".(integer)$_POST['lid']." AND league_player_league_player_id=".(integer)$_GET['lpid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$num_player = mysql_fetch_array($res_player);
			if ($num_player[0] == 0){
				/* Zalozime zaznam o vstupu hrace do ligy jednotlivcu */
				mysql_query("INSERT INTO $db_league_players_leagues VALUES(
				'',
				'".(integer)$_POST['lpid']."',
				'".(integer)$_SESSION['loginid']."',
				'".(integer)$_POST['lid']."',
				'".(integer)$_POST['gid']."',
				NOW(),
				'',
				'1',
				'1')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				LeagueAddToLOG ((integer)$_POST['lid'],"","",(integer)$_SESSION['loginid'],(integer)$_POST['gid'],20,"","","");			// Player join league
				$msg = "league_player_reg_to_league";
			} else {
				$msg = "league_player_reg_already";
			}
		} else {
			$msg = "league_player_reg_no_agree";
		}
		
		header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=player_home&lpid=".$_POST['lpid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
		exit;
	/***********************************************************************************************************
	*
	*		ODHLASENI TEAMU Z LIGY - ACTIVE = 0
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "team_league_leave"){
		if (LeagueCheckPrivileges("O",$_SESSION['loginid'],$_POST['ltid'],$_POST['ltsid']) == $_POST['ltid'] || LeagueCheckPrivileges("C",$_SESSION['loginid'],$_POST['ltid'],$_POST['ltsid']) == 1){
			// Pokud je liga locked nic se neulozi a uzivateli se zobrazi upozorneni 
			if (LeagueCheckIfLocked("L",$_POST['lid']) == true){
				$msg = "league_is_locked";
			} else {
				/* Smazeme  */
				$res_game = mysql_query("SELECT league_player_game_id FROM $db_league_players WHERE league_player_team_sub_id=".(integer)$_POST['ltsid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$ar_game = mysql_fetch_array($res_game);
				mysql_query("DELETE FROM $db_league_teams_sub_leagues WHERE league_teams_sub_league_team_sub_id=".(integer)$_POST['ltsid']." AND league_teams_sub_league_league_id=".(integer)$_POST['lid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				LeagueAddToLOG ((integer)$_POST['lid'],(integer)$_POST['ltid'],(integer)$_POST['ltsid'],(integer)$_SESSION['loginid'],$ar_game['league_player_game_id'],52,"","",$_POST['league_team_leave_reason']);			// Team left league
				$msg = "league_team_left_league";
			}
		} else {
			$msg = "league_no_privilege";
		}
		header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$_POST['ltid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
		exit;
	/***********************************************************************************************************
	*
	*		ODHLASENI HRACE Z LIGY - ACTIVE = 0
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "player_league_leave"){
		// Pokud je liga locked nic se neulozi a uzivateli se zobrazi upozorneni 
		if (LeagueCheckIfLocked("L",$_POST['lid']) == true){
			$msg = "league_is_locked";
		} else {
			/* Smazeme  */
			$res_game = mysql_query("SELECT league_player_league_game_id FROM $db_league_players_leagues WHERE league_player_league_league_id=".(integer)$_POST['lid']." AND league_player_league_admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_game = mysql_fetch_array($res_game);
			mysql_query("DELETE FROM $db_league_players_leagues WHERE league_player_league_league_id=".(integer)$_POST['lid']." AND league_player_league_admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			LeagueAddToLOG ((integer)$_POST['lid'],0,0,(integer)$_SESSION['loginid'],$ar_game['league_player_league_game_id'],21,"","",$_POST['league_team_leave_reason']);			// Player left league
			$msg = "league_player_left_league";
		}
		header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=player_home&lpid=".$_POST['lpid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
		exit;
	/***********************************************************************************************************
	*
	*		ZALOZENI NOVEHO PODTEAMU (pridani hry)
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "team_game_add"){
		if (LeagueCheckPrivileges("O",$_SESSION['loginid'],$_POST['ltid'],0) == $_POST['ltid']){
			mysql_query("INSERT INTO $db_league_teams_sub VALUES(
			'',
			'".(integer)$_POST['ltid']."',
			'".(integer)$_POST['league_game']."',
			'',
			NOW(),
			'',
			NOW())") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$res_sub_id = mysql_query("SELECT LAST_INSERT_ID()") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_sub_id = mysql_fetch_array($res_sub_id);
			$team_sub_id = $ar_sub_id[0];
			LeagueAddToLOG (0,(integer)$_POST['ltid'],(integer)$team_sub_id,0,(integer)$_POST['league_game'],56,"","","");	// Byl zalozen sub team
			$msg = "league_team_sub_added";
		} else {
			$msg = "league_no_privilege";
		}
		header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$_POST['ltid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
		exit;
	/***********************************************************************************************************
	*
	*		NASTAVENI KTERI HRACI SE MUZOU ZUCASTNIT LIGY (pokud liga ma limit na maximalni pocet hracu hracjici danou ligu)
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "team_league_allow_players"){
		if ($_POST['lid'] == 0 || $_POST['lid'] ==  "" || $_POST['ltid'] == 0 || $_POST['ltid'] == "" || $_POST['ltsid'] == 0 || $_POST['ltsid'] == ""){
			$msg = "eden_missing_variables";
		} elseif (LeagueCheckPrivileges("O",$_SESSION['loginid'],$_POST['ltid'],0) == $_POST['ltid'] || LeagueCheckPrivileges("C",$_SESSION['loginid'],$_POST['ltid'],$_POST['ltsid']) == 1){
			/* V pripade vice hracu do Databaze */
			$i = 1;
			$allowed_players = "";
			while($i <= $_POST['allowed_player_num']){
				$allowed_player_data = $_POST['allowed_player_data'];
				$allowed_player_id = $allowed_player_data[$i.'_player_id'];
				$allowed_player_allowed = $allowed_player_data[$i.'_player_allowed'];
				if ($allowed_player_allowed == 1){
					$allowed_players .= $allowed_player_id."#";
				}
				$i++;
			}
			mysql_query("UPDATE $db_league_teams_sub_leagues SET league_teams_sub_league_players='".mysql_real_escape_string($allowed_players)."' WHERE league_teams_sub_league_team_sub_id=".(integer)$_POST['ltsid']." AND league_teams_sub_league_league_id=".(integer)$_POST['lid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$msg = "league_team_league_players_allowed";
		} else {
			$msg = "league_no_privilege";
		}
		header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$_POST['ltid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
		exit;
	/***********************************************************************************************************
	*
	*		NASTAVENI KTERI HRACI SE MUZOU ZUCASTNIT LIGY (pokud liga ma limit na maximalni pocet hracu hracjici danou ligu)
	*
	*		$draft_mode		1 = hrac hleda team
	*						2 = team hleda hrace
	*		$players		- plati jen kdyz team hleda hrace (urcuje se tim pocet hledanych hracu)
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "team_draft"){
		// Pokud je hledan hrac do teamu
		if ($_POST['team'] == 1){
			$res_team_sub = mysql_query("
			SELECT league_team_sub_id 
			FROM $db_league_teams_sub 
			WHERE league_team_sub_team_id=".(integer)$_POST['ltid']." AND league_team_sub_game_id=".(integer)$_POST['league_draft_game']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_team_sub = mysql_fetch_array($res_team_sub);
			$player_id = 0;
			$game_id = $_POST['league_draft_game'];
			$ltsid = $ar_team_sub['league_team_sub_id'];
			$players = $_POST['league_draft_players'];
			$draft_mode = 2;
			$msg_ok = "league_draft_search_team_ok";
			$msg_err = "league_draft_search_team_err";
			$msg_exist = "league_draft_search_team_exist";
			$where  = " WHERE league_draft_team_sub_id=".(integer)$ltsid." AND league_draft_game_id=".(integer)$game_id;
		// Pokud je hledan team
		} else {
			// Zalozime ucet pro pozadovanou hru
			LeagueAddPlayer($_SESSION["loginid"],0,(float)$_POST['league_draft_game'],0,0,0,0,0);
			$res_player = mysql_query("
			SELECT league_player_id 
			FROM $db_league_players 
			WHERE league_player_admin_id=".(integer)$_SESSION['loginid']." AND league_player_game_id=".(integer)$_POST['league_draft_game']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_player = mysql_fetch_array($res_player);
			$player_id = $ar_player['league_player_id'];
			$game_id = $_POST['league_draft_game'];
			$ltsid = 0;
			$players = 0;
			$draft_mode = 1;
			$msg_ok = "league_draft_search_player_ok";
			$msg_err = "league_draft_search_player_err";
			$msg_exist = "league_draft_search_player_exist";
			$where  = " WHERE league_draft_player_id=".(integer)$player_id." AND league_draft_game_id=".(integer)$game_id;
		}
		$res_draft = mysql_query("SELECT COUNT(*) FROM $db_league_draft $where") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$num_draft = mysql_fetch_array($res_draft);
		if ($num_draft[0] == 0){
			$res = mysql_query("INSERT INTO $db_league_draft VALUES(
			'',
			'".(integer)$_SESSION['loginid']."',
			'".(integer)$player_id."',
			'".(integer)$game_id."',
			'".(integer)$ltsid."',
			NOW(),
			'".(integer)$players."',
			'".(integer)$draft_mode."')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			if ($res){
				$msg = $msg_ok;
			} else {
				$msg = $msg_err;
			}
			header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_draft&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
			exit;
		} else {
			if ($num_draft[0] > 1){
				for ($i=1;$i<$ar_player;$i++){
					mysql_query("DELETE FROM $db_league_draft $where LIMIT 1") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				}
			}
			header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_draft&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg_exist);
			exit;
		}
	/***********************************************************************************************************
	*
	*		ODSTRANENI ZPRAV Z DRAFT BOARDU
	*
	***********************************************************************************************************/
	} elseif ($_POST['mode'] == "team_draft_del"){
		$league_draft_del = $_POST['league_draft_del'];
		$num = count($league_draft_del);
		for ($i=0;$i<$num;$i++){
			// If $league_draft_del is array it has to be called differently
			if (is_array($league_draft_del)) { $ldd = $league_draft_del[$i]; } else {$ldd = $league_draft_del;}
			$res = mysql_query("DELETE FROM $db_league_draft WHERE league_draft_id=".(integer)$ldd." AND league_draft_admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		}
		if ($res){
			$msg = "league_draft_del_ok";
		} else {
			$msg = "league_draft_del_er";
		}
		header ("Location: ".$eden_cfg['url']."index.php?action=league_team&mode=team_draft&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=".$msg);
		exit;
	/***********************************************************************************************************
	*
	*		PRIDANI PRODEJCE
	*
	***********************************************************************************************************/
	 } elseif ($_POST['mode'] == "seller_add"){
	 	$res_admin = mysql_query("SELECT admin_id, admin_name, admin_firstname, admin_email, admin_status FROM $db_admin WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar_admin = mysql_fetch_array($res_admin);
		if ($ar_admin['admin_status'] == "seller"){header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']); exit;}
	 	if ($_POST['seller_invoice_country'] == ""){$seller_invoice_country = $_POST['seller_delivery_country'];} else {$seller_invoice_country = $_POST['seller_invoice_country'];}
	 	if ($_POST['seller_invoice_city'] == ""){$seller_invoice_city = $_POST['seller_delivery_city'];} else {$seller_invoice_city = $_POST['seller_invoice_city'];}
	 	if ($_POST['seller_invoice_address_1'] == ""){$seller_invoice_address_1 = $_POST['seller_delivery_address_1'];} else {$seller_invoice_address_1 = $_POST['seller_address_1'];}
	 	if ($_POST['seller_invoice_address_2'] == ""){$seller_invoice_address_2 = $_POST['seller_delivery_address_2'];} else {$seller_invoice_address_2 = $_POST['seller_address_2'];}
	 	if ($_POST['seller_invoice_postcode'] == ""){$seller_invoice_postcode = $_POST['seller_delivery_postcode'];} else {$seller_invoice_poscode = $_POST['seller_invoice_postcode'];}
		
		// Provereni vsech udeaju na strane serveru
		
		if ($_POST['seller_company_name']){
			
		}
		
		$res = mysql_query("INSERT INTO $db_shop_sellers VALUES(
			'',
			'".(integer)$ar_admin['admin_id']."',
			'',
			'".mysql_real_escape_string($_POST['seller_company_name'])."',
			NOW(),
			NOW(),
			'',
			'',
			'".mysql_real_escape_string($_POST['seller_vat_number'])."',
			'".mysql_real_escape_string($_POST['seller_phone'])."',
			'',
			'".mysql_real_escape_string($_POST['seller_mobile'])."',
			'".mysql_real_escape_string($_POST['seller_fax'])."',
			'".mysql_real_escape_string($_POST['seller_web'])."',
			'".(integer)$_POST['seller_delivery_country']."',
			'".mysql_real_escape_string($_POST['seller_delivery_city'])."',
			'".mysql_real_escape_string($_POST['seller_delivery_address_1'])."',
			'".mysql_real_escape_string($_POST['seller_delivery_address_2'])."',
			'".mysql_real_escape_string($_POST['seller_delivery_postcode'])."',
			'".(integer)$seller_invoice_country."',
			'".mysql_real_escape_string($seller_invoice_city)."',
			'".mysql_real_escape_string($seller_invoice_address_1)."',
			'".mysql_real_escape_string($seller_invoice_address_2)."',
			'".mysql_real_escape_string($seller_invoice_postcode)."',
			'".mysql_real_escape_string($_POST['seller_note'])."',
			'',
			'".mysql_real_escape_string($_POST['seller_position'])."')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			
		// Email informujici o novem prodejci odesleme spravci obchodu 
		$mail = new PHPMailer();
		$mail->From = $ar_setup['setup_reg_from'];
		$mail->FromName = $ar_setup['setup_reg_from_name'];
		$mail->AddAddress($ar_setup['setup_contact_form_from']);
		$mail->CharSet = "utf-8";
		$mail->IsHTML(true);
		$mail->Mailer = $ar_setup['setup_reg_mailer'];
		$mail->Subject = _SHOP_SELLER_NEW." - ".$_POST['seller_company_name'];
		
		$mail->Body .= "<html><head title=\""._SHOP_SELLER_NEW." - ".$_POST['seller_company_name']."\"/><body>";
		$mail->Body .= "<p>"._SHOP_SELLER_COMPANY_NAME.": ".$_POST['seller_company_name']."</p>";
		$mail->Body .= "<p>ID: ".$ar_admin["admin_id"]."</p>";
		$mail->Body .= "<p>"._SHOP_SELLER_NAME.": ".$ar_admin["admin_firstname"]." ".$ar_admin["admin_name"]."</p>";
		$mail->Body .= "<p>"._SHOP_SELLER_EMAIL.": ".$ar_admin["admin_email"]."</p>";
		$mail->Body .= "</body></html>";
		
		$mail->AltBody = "\n";
		$mail->AltBody .= _SHOP_SELLER_COMPANY_NAME.": ".$_POST['seller_company_name']."\n";
		$mail->AltBody .= "ID: ".$ar_admin["admin_id"]."\n";
		$mail->AltBody .= _SHOP_SELLER_NAME.": ".$ar_admin["admin_firstname"]." ".$ar_admin["admin_name"]."\n";
		$mail->AltBody .= _SHOP_SELLER_EMAIL.": ".$ar_admin["admin_email"]."\n";
		$mail->AltBody .= "\n";
		
		$mail->WordWrap = 100;
		
		if (!$mail->Send()){
			header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=seller_add_er");
			exit;
		} else {
			header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=seller_add_ok");
			exit;
		}
	/***********************************************************************************************************
	*
	*		EDITACE PRODEJCE
	*
	***********************************************************************************************************/
	 } elseif ($_POST['mode'] == "seller_edit"){
	 	$res_admin = mysql_query("SELECT admin_id, admin_name, admin_firstname, admin_email FROM $db_admin WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar_admin = mysql_fetch_array($res_admin);
	 	if ($_POST['seller_invoice_country'] == ""){$seller_invoice_country = $_POST['seller_delivery_country'];} else {$seller_invoice_country = $_POST['seller_invoice_country'];}
	 	if ($_POST['seller_invoice_city'] == ""){$seller_invoice_city = $_POST['seller_delivery_city'];} else {$seller_invoice_city = $_POST['seller_invoice_city'];}
	 	if ($_POST['seller_invoice_address_1'] == ""){$seller_invoice_address_1 = $_POST['seller_delivery_address_1'];} else {$seller_invoice_address_1 = $_POST['seller_invoice_address_1'];}
	 	if ($_POST['seller_invoice_address_2'] == ""){$seller_invoice_address_2 = $_POST['seller_delivery_address_2'];} else {$seller_invoice_address_2 = $_POST['seller_invoice_address_2'];}
	 	if ($_POST['seller_invoice_postcode'] == ""){$seller_invoice_postcode = $_POST['seller_delivery_postcode'];} else {$seller_invoice_postcode = $_POST['seller_invoice_postcode'];}
		
		$res = mysql_query("UPDATE $db_shop_sellers SET 
			shop_seller_company_name='".mysql_real_escape_string($_POST['seller_company_name'])."',
			shop_seller_vat_number='".mysql_real_escape_string($_POST['seller_vat_number'])."',
			shop_seller_phone_1='".mysql_real_escape_string($_POST['seller_phone'])."',
			shop_seller_mobile='".mysql_real_escape_string($_POST['seller_mobile'])."',
			shop_seller_fax='".mysql_real_escape_string($_POST['seller_fax'])."',
			shop_seller_web='".mysql_real_escape_string($_POST['seller_web'])."',
			shop_seller_delivery_country_id=".(integer)$_POST['seller_delivery_country'].",
			shop_seller_delivery_city='".mysql_real_escape_string($_POST['seller_delivery_city'])."',
			shop_seller_delivery_address_1='".mysql_real_escape_string($_POST['seller_delivery_address_1'])."',
			shop_seller_delivery_address_2='".mysql_real_escape_string($_POST['seller_delivery_address_2'])."',
			shop_seller_delivery_postcode='".mysql_real_escape_string($_POST['seller_delivery_postcode'])."',
			shop_seller_invoice_country_id=".(integer)$seller_invoice_country.",
			shop_seller_invoice_city='".mysql_real_escape_string($seller_invoice_city)."',
			shop_seller_invoice_address_1='".mysql_real_escape_string($seller_invoice_address_1)."',
			shop_seller_invoice_address_2='".mysql_real_escape_string($seller_invoice_address_2)."',
			shop_seller_invoice_postcode='".mysql_real_escape_string($seller_invoice_postcode)."',
			shop_seller_note='".mysql_real_escape_string($_POST['seller_note'])."',
			shop_seller_staff_position='".mysql_real_escape_string($_POST['seller_position'])."' WHERE shop_seller_admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	   	if ($res){
			header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=seller_edit_ok");
			exit;
		} else {
			header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."&msg=seller_edit_er");
			exit;
		}
	/***********************************************************************************************************
	*
	*		UNSUBSCRIBE FROM EMAIL DB
	*
	***********************************************************************************************************/
	 } elseif ($_POST['mode'] == "mailing_off"){
	 	$res = mysql_query("UPDATE $db_admin SET admin_agree_email=0 WHERE shop_seller_admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	 /***********************************************************************************************************
	*
	*		DECKLISTS - ADD
	*
	***********************************************************************************************************/
	 } elseif ($_POST['mode'] == "decklists_add" && ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin")) {
	 	$decklist = new MtGDecklists($eden_cfg);
	 	$decklist->saveDecklist($_POST, $link = "&lang=".$_GET['lang']."&filter=".$_GET['filter']);
	/***********************************************************************************************************
	*
	*		ZBYTEK
	*
	***********************************************************************************************************/
	} else {
		header ("Location: http://".$_POST['odkaz']);
		exit;
	}