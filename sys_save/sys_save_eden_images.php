<?php
switch($_GET['mode']){
	case "clan_game_img_upload":
		$priv = CheckPriv("groups_clan_games_add");
		$ftp_path = $ftp_path_games;
		$link = "modul_clan_games.php";
		$img_action = "upload";
		$mode = "games_1";
		$img_size = "equal";
		$get_mode = "clan_game_img_upload";
		$gif = 1;
		$jpg = 0;
		$png = 0;
		break;
	case "games_1_img_del":
		$priv = CheckPriv("groups_clan_games_del");
		$ftp_path = $ftp_path_games;
		$link = "modul_clan_games.php";
		$img_action = "del";
		$get_mode = "clan_game_img_upload";
		break;
	case "category_img_upload":
		$priv = CheckPriv("groups_cat_ul");
		$ftp_path = $ftp_path_category;
		$link = "sys_category.php";
		$img_action = "upload";
		$mode = "category";
		$img_size = "equal";
		$get_mode = "category_img_upload";
		$gif = 1;
		$jpg = 1;
		$png = 1;
		break;
	case "category_img_del":
		$priv = CheckPriv("groups_cat_del");
		$ftp_path = $ftp_path_category;
		$link = "sys_category.php";
		$img_action = "del";
		$get_mode = "category_img_upload";
		break;
	case "league_award_img_upload":
		$priv = CheckPriv("groups_league_add");
		$ftp_path = $ftp_path_league_awards;
		$link = "modul_league.php";
		$img_action = "upload";
		$mode = "league_award";
		$img_size = "equal";
		$get_mode = "league_award_img_upload";
		$gif = 1;
		$jpg = 1;
		$png = 1;
		break;
	case "league_award_img_del":
		$priv = CheckPriv("groups_league_del");
		$ftp_path = $ftp_path_league_awards;
		$link = "modul_league.php";
		$img_action = "del";
		$get_mode = "league_award_img_upload";
		break;
	case "smiles_img_upload":
		$priv = CheckPriv("groups_smiles_ul");
		$ftp_path = $ftp_path_smiles;
		$link = "modul_smiles.php";
		$img_action = "upload";
		$mode = "smiles";
		$img_size = "upto";
		$get_mode = "smiles_img_upload";
		$gif = 1;
		$jpg = 1;
		$png = 1;
		break;
	case "smiles_img_del":
		$priv = CheckPriv("groups_smiles_del");
		$ftp_path = $ftp_path_smiles;
		$link = "modul_smiles.php";
		$img_action = "del";
		$get_mode = "smiles_img_upload";
		break;
	default:
		$priv = 0;
		$priv_del = 0;
		$close_error = 1;
}
//echo $_GET['mode'];
//exit;
if($close_error == 1){header ("Location: ".$eden_cfg['url_cms'].$link."?action=".$get_mode."&msg=".$error."&project=".$_SESSION['project']."");}

// Spojeni s FTP serverem
$conn_id = ftp_connect($eden_cfg['ftp_server']);
// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
// Zjisteni stavu spojeni
if ((!$conn_id) || (!$login_result)){
	header ("Location: ".$eden_cfg['url_cms'].$link."?action=".$get_mode."&msg=eden_img_edb&project=".$_SESSION['project']."");
	exit;
}

if ($img_action == "upload"){
	/* Nahrani obrazku */
	if ($_FILES['eden_image']['name'] != ""){
		if (($eden_image = getimagesize($_FILES['eden_image']['tmp_name'])) != false){
			/* Zjistime zda neni soubor jineho typu nez je povoleno a pokud ne priradime extenzi */
			if ($gif = 1 && $eden_image[2] == 1){
				$image_name = $_FILES['eden_image']['name']; //gif
			} elseif ($jpg = 1 && $eden_image[2] == 2){
				$image_name = $_FILES['eden_image']['name']; //jpg
			} elseif ($png = 1 && $eden_image[2] == 3){
				$image_name = $_FILES['eden_image']['name']; //png
			} else {
				header ("Location: ".$eden_cfg['url_cms'].$link."?action=".$get_mode."&project=".$_SESSION['project']."&msg=wft");
				exit;
			}
			if ($img_size == "equal"){
				/* Zjistime zda neni obrazek vetsi nebo mensi, nez je povoleno */
				if ($eden_image[0] != GetSetupImageInfo($mode,"width")){
					header ("Location: ".$eden_cfg['url_cms'].$link."?action=".$get_mode."&project=".$_SESSION['project']."&msg=img_different_size");
					exit;
				}
				/* Zjistime zda neni obrazek vetsi nebo mensi, nez je povoleno */
				if ($eden_image[1] != GetSetupImageInfo($mode,"height")){
					header ("Location: ".$eden_cfg['url_cms'].$link."?action=".$get_mode."&project=".$_SESSION['project']."&msg=img_different_size");
					exit;
				}
			}
			if ($img_size == "upto"){
				/* Zjistime zda neni obrazek vetsi nebo mensi, nez je povoleno */
				if ($eden_image[0] > GetSetupImageInfo($mode,"width")){
					header ("Location: ".$eden_cfg['url_cms'].$link."?action=".$get_mode."&project=".$_SESSION['project']."&msg=img_too_big");
					exit;
				}
				/* Zjistime zda neni obrazek vetsi nebo mensi, nez je povoleno */
				if ($eden_image[1] > GetSetupImageInfo($mode,"height")){
					header ("Location: ".$eden_cfg['url_cms'].$link."?action=".$get_mode."&project=".$_SESSION['project']."&msg=img_too_big");
					exit;
				}
			}
			/* Zjistime zda neni soubor vetsi nez je povoleno */
			if ($_FILES['eden_image']['size'] > GetSetupImageInfo($mode,"filesize")){
				header ("Location: ".$eden_cfg['url_cms'].$link."?action=".$get_mode."&project=".$_SESSION['project']."&msg=ftb");
				exit;
			} else {
				/* Zjisti nazev souboru po ulozeni do docasneho adresare na serveru */
				$source_file = $_FILES['eden_image']['tmp_name'];
				
				/*	Odstraneni prebytecnych znaku	
				$image_name = CleanAdminUsername($image_name);
				$userfile_name = $image_name.strtolower($extenze);
				*/
				
				/* Vlozi nazev souboru a cestu do konkretniho adresare */
				$destination_file = $ftp_path.$image_name;
				$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
				/* Zjisteni stavu uploadu */
				if (!$upload){
					header ("Location: ".$eden_cfg['url_cms'].$link."?action=".$get_mode."&project=".$_SESSION['project']."&msg=ue");
					exit;
				} else {
					/* Pokud vse probehne jak ma znicime dane promenne a nastavime $checkupload */
					unset($source_file);
					unset($destination_file);
					unset($extenze);
					unset($eden_image);
					unset($_FILES['eden_image']);
					$checkupload = 1;
				}
			}
		} else {
			header ("Location: ".$eden_cfg['url_cms'].$link."?action=showmain&project=".$_SESSION['project']."&msg=img_up_er");
			exit;
		}
		/* Uzavreni komunikace se serverem */
		ftp_close($conn_id);
	} else {
		$error = "img_up_er_no_name";
	}
	$error = "img_up_ok";
}

if ($img_action == "del"){
	$num = count($_POST['img_data']);
	$i=0;
	while ($i < $num){
		if (ftp_delete($conn_id, $ftp_path.$_POST['img_data'][$i])) {
			/* vsechno v pohode */
			$error = "img_del_ok";
		} else {
			exit;
			header ("Location: ".$eden_cfg['url_cms'].$link."?action=showmain&project=".$_SESSION['project']."&msg=img_del_er");
			exit;
		}
		$i++;
	}
	/* Plural pokud bylo smazano vice obrazku */
	if ($i > 1){
		$error = "img_del_oks";
	}
	/* Uzavreni komunikace se serverem */
	ftp_close($conn_id);
}
header ("Location: ".$eden_cfg['url_cms'].$link."?action=".$get_mode."&msg=".$error."&project=".$_SESSION['project']."");
exit;