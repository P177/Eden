<?php
/***********************************************************************************************************
*
*		Dictionary
*
*		Zobrazeni slovniku cizich slov
*
*
***********************************************************************************************************/
/**
 * Random MtG Card
 */
class MtGRandomCards {
	
	/**
     * @param $eden_cfg Eden configuration
     */
    public function __construct($eden_cfg) {
		
        $this->eden_cfg = $eden_cfg;
    }
	
	/**
	 * Show Random Card
	 * @return string
	 */
	public function showRandomCard() {
		
		$res = mysql_query("SELECT mtg_card_id, mtg_card_mtg_id, mtg_card_name, mtg_card_set, mtg_card_set_code, mtg_card_type, mtg_card_variation
		FROM "._DB_MTG_CARDS." 
		ORDER BY RAND() LIMIT 1") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar = mysql_fetch_array($res);
		
		$card_name = str_ireplace(" // ", "",$ar['mtg_card_name']);
		$card_name = str_ireplace("Æ", "AE",$ar['mtg_card_name']);
		$card_name = str_ireplace("œ", "OE",$ar['mtg_card_name']);
		
		$output = "<a href=\"".$this->eden_cfg['url']."?action=mtg_show_card&card_id=".$ar['mtg_card_id']."\"><img src=\""._URL_MTG_CARDS."/".$ar['mtg_card_set_code']."/".toAscii($card_name).$ar['mtg_card_variation'].".full.jpg"."\" width=\"163\" height=\"234\" border=\"0\" alt=\"".$ar['mtg_card_name']."\" /></a>";
		
		return $output;
	}
	
}


/**
 * Show MtG Card
 */
class MtGShowCard {
	
	/**
     * @param array 	Eden configuration
     */
    public function __construct($eden_cfg) {
		
        $this->eden_cfg = $eden_cfg;
    }
	
	/**
	 * Show Random Card
	 * @param integer	Card ID
	 * @param string	Mode - (full, lite...)
	 * @return string
	 */
	public function showCard($card_id, $mode = "full") {
		
		$mode =  EdenHelper::prepareInclude($mode);
		
		$res_mtg_card = mysql_query("SELECT * 
		FROM "._DB_MTG_CARDS." 
		WHERE mtg_card_id = ".(integer)$card_id) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar_mtg_card = mysql_fetch_array($res_mtg_card);
		
		$card_name = str_ireplace(" // ", "",$ar_mtg_card['mtg_card_name']);
		$card_name = str_ireplace("Æ", "AE",$ar_mtg_card['mtg_card_name']);
		$card_name = str_ireplace("œ", "OE",$ar_mtg_card['mtg_card_name']);
		
		include dirname(__FILE__)."/../templates/tpl.mtg_show_card_".$mode.".php";
	}
}


/**
 * Show List of MtG Cards
 */
class MtGShowCardList {
	
	/**
     * @param array Eden configuration
     */
    public function __construct($eden_cfg) {
		
        $this->eden_cfg = $eden_cfg;
    }
	
	/**
	 * Show Random Card
	 * @param string	letter
	 * @param integer	Card ID
	 * @param string	Mode (open, close)
	 * @return string
	 */
	public function showCardList($letter, $id = 0, $mode = "close") {
		
		if ($letter == "Other"){
			$like2 = "REGEXP";
		} elseif ($letter != ""){
			$like2 = "LIKE";
		} else {
			$like2 = FALSE;
		}
		
		if ($letter != "All"){ 
			$like = "AND mtg_card_name $like2 ".AlphabethSelect(mysql_real_escape_string($letter), "mtg_card_name");
		}
		//if ($id != 0){$like = "AND mtg_card_id=".(integer)$id;}
		
		$res = mysql_query("SELECT * 
		FROM "._DB_MTG_CARDS." 
		WHERE mtg_card_id > 0 $like ORDER BY mtg_card_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		ob_start();
		include "templates/tpl.mtg_show_card_list.php";
		$output = ob_get_contents();
		ob_end_clean();
		
		return $output;
	}
}


/**
 * Replace {#} for image
 */
class MtGTranslateFromDB {
	
	/**
	 * Transform string to images
	 * @param string - Text which needs to be transcode
	 * @param integer - Width of the image
	 * @param integer - Height of the image
	 * @return string
	 */
	public function transformStringToImgs($data, $width = 16, $height = 16) {
		
		$code = array("0","1","2","3","4","5","6","7","8","9","10","11","12","13",
		"14","15","16","100","1000000","B","BG","BR","G","GU","GW","HR","HW","P","PB",
		"PG","PR","PU","PW","Q","R","RG","RW","S","T","U","UB","UR","W","WB","WU","X");
		
		$w = $width;
		$num = count($code);
		for ($i=0; $i < $num; $i++){
			$width = $w;
			if ($code[$i] == "1000000"){$width = round($height * 2.4);}
			if ($code[$i] == "HW" || $code[$i] == "HR"){$width = round($height * 0.52);}
			$data = str_replace("{".$code[$i]."}","<img src=\""._URL_MTG_CARDS.$code[$i].".gif\" alt=\"".$code[$i]."\" width=\"".$width."\" height=\"".$height."\">" ,$data);
		}
		$data = str_replace("|||","<br>" ,$data);
		
		return $data;
	}
	
	/**
 	* Transform rarity from DB (represented by only one character)
	* @param string - Rarity character from DB
	* @return string - Translated rarity text
 	*/
	public function transformRarityCharToWords($input) {
		
		switch ($input){
			case "C":
				echo _MTG_RARITY_C;
				break;
			case "M":
				echo _MTG_RARITY_M;
				break;
			case "R":
				echo _MTG_RARITY_R;
				break;
			case "T":
				echo _MTG_RARITY_T;
				break;
			case "U":
				echo _MTG_RARITY_U;
				break;
			default:
				$output = $input;
		}
		
		return $output;
	}
	
	/**
	 * Show Expansions card was released in
	 * @param string
	 * @return string
	 */
	public function showSets($card_name) {
		
		$res = mysql_query("SELECT mtg_card_set, mtg_card_set_code 
		FROM "._DB_MTG_CARDS." 
		WHERE mtg_card_name = '".mysql_real_escape_string($card_name)."'") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		while ($ar = mysql_fetch_array($res)){
			$output .= $ar['mtg_card_set']."<br>";
		}
		return $output;
	}
}

/**
 * MtG Decklists
 */
class MtGDecklists {
	
	/**
     * @param array Eden configuration
     */
    public function __construct($eden_cfg) {
		
        $this->eden_cfg = $eden_cfg;
    }
	
	/**
	 * Show Add Decklist template
	 * @param string	(add, edit)
	 * @param array		Only when $mode = edit - (decklist_id, decklist_name, decklist_format, decklist_description)
	 * @return string
	 */
	public function formAddDecklist($mode = "add", $data = array()){
		
		ob_start();
	    include "templates/tpl.mtg_form_add_decklist.php";
	    $output = ob_get_contents();
	    ob_end_clean();
	    
		return $output;
	}
	
	/**
	 * Show Add Decklist Cards template
	 * @param integer	Decklist ID
	 * @return string
	 */
	public function formEditDecklist($did = false){
		
		if ($did == false){
			$output = "";
		} else {
			$res = mysql_query("SELECT decklist_id, decklist_name, decklist_format, decklist_description, decklist_show 
			FROM "._DB_MTG_DECKLISTS." 
			WHERE decklist_id = ".(integer)$did) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar = mysql_fetch_array($res);
			
			ob_start();
		    include "templates/tpl.mtg_form_edit_decklist.php";
		    $output = ob_get_contents();
		    ob_end_clean();
	    }
		return $output;
	}
	
	/**
	 * Show Decklists
	 * @return string
	 */
	public function showDecklists(){
		$res = mysql_query("SELECT decklist_id, decklist_admin_id, decklist_name, decklist_format, decklist_date_created, admin_nick 
		FROM "._DB_MTG_DECKLISTS." 
		JOIN "._DB_ADMINS." ON admin_id = decklist_admin_id 
		WHERE decklist_complete = 1 AND decklist_show = 1 
		ORDER BY decklist_format ASC, decklist_date_created DESC") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		ob_start();
	    include "templates/tpl.mtg_show_decklists.php";
	    $output = ob_get_contents();
	    ob_end_clean();
	    
	    return $output;
	}
	
	/**
	 * Show Decklists Small
	 * @param integer	Limit
	 * @return string
	 */
	public function showDecklistsSmall($limit = 10){
		$res = mysql_query("SELECT decklist_id, decklist_admin_id, decklist_name, decklist_format, decklist_date_created, admin_nick 
		FROM "._DB_MTG_DECKLISTS." 
		JOIN "._DB_ADMINS." ON admin_id = decklist_admin_id 
		WHERE decklist_complete = 1 
		ORDER BY decklist_date_created DESC 
		LIMIT ".(integer)$limit) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		ob_start();
	    include "templates/tpl.mtg_show_decklists_small.php";
	    $output = ob_get_contents();
	    ob_end_clean();
	    
	    return $output;
	}
	
	/**
	 * Show Decklist - One
	 * @param integer	Decklist ID
	 * @param string	Decklist mode (standard, add)
	 * @return string
	 */
	public function showDecklist($did = false, $mode = "standard"){
		
		if ($did == false){
			$output = "";
		} else {
			
			$res = mysql_query("SELECT decklist_id, decklist_admin_id, decklist_name, decklist_format, decklist_description, decklist_show, admin_nick 
			FROM "._DB_MTG_DECKLISTS." 
			JOIN "._DB_ADMINS." ON admin_id = decklist_admin_id 
			WHERE decklist_id = ".(integer)$did) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar = mysql_fetch_array($res);
			ob_start();
			if ($mode == "add"){
		    	include dirname(__FILE__)."/../templates/tpl.mtg_show_decklist_add.php"; // Show cards added to the decklist in the form
			} else {
				include "templates/tpl.mtg_show_decklist.php";
			}
		    $output = ob_get_contents();
		    ob_end_clean();
	    }
	    return $output;
	}
	
	/**
	 * Show My Decklists
	 * @return string
	 */
	public function showMyDecklists(){
		
		$res = mysql_query("SELECT decklist_id, decklist_name, decklist_format 
		FROM "._DB_MTG_DECKLISTS." 
		WHERE decklist_admin_id = ".(integer)$_SESSION['loginid']." 
		ORDER BY decklist_format ASC, decklist_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		
		ob_start();
	    include "templates/tpl.mtg_show_my_decklists.php";
	    $output = ob_get_contents();
	    ob_end_clean();
	    
	    return $output;
	}
	
	/**
	 * Show His Decklists (well, even Hers ;)
	 * @return string
	 */
	public function showHisDecklists($admin_id = false){
		
		if ($admin_id == false){
			$output = "";
		} else {
			$res = mysql_query("
			SELECT decklist_id, decklist_admin_id, decklist_name, decklist_format 
			FROM "._DB_MTG_DECKLISTS." 
			WHERE decklist_admin_id = ".(integer)$admin_id." AND decklist_complete = 1  AND decklist_show = 1 
			ORDER BY decklist_format ASC, decklist_date_last_modified DESC") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			
			ob_start();
		    include "templates/tpl.mtg_show_his_decklists.php";
		    $output = ob_get_contents();
		    ob_end_clean();
	    }
	    return $output;
	}
	
	/**
	 * Save Decklist
	 * @param array		$_POST
	 * @param string	link - &lang=cz&filter=
	 * @param string	mode - decklist_add, decklist_edit
	 */
	public function saveDecklist($decklist, $link = "", $mode = "decklist_add"){
		
		$decklist_name = strip_tags($decklist['decklist_name'],"");
		$decklist_desc = strip_tags($decklist['decklist_desc'],"");
	 	$decklist_format = $decklist['decklist_format'];
		
		if ($decklist_name == ""){
			$action = "decklist_add";
			$msg = "decklist_add_er_no_name";
			$form_vars = "&dn=".$decklist_name."&df=".(integer)$decklist_format;
	 	} elseif ($decklist_format == 0){
			$action = "decklist_add";
			$msg = "decklist_add_er_no_format";
			$form_vars = "&dn=".$decklist_name."&dd=".$decklist_desc."&df=".(integer)$decklist_format."&ds=".(integer)$decklist['decklist_show'];
		} elseif ($mode == "decklist_add") {
			$res = mysql_query("
			INSERT INTO "._DB_MTG_DECKLISTS." (
			decklist_admin_id, 
			decklist_name, 
			decklist_format, 
			decklist_date_created, 
			decklist_date_last_modified,
			decklist_description,
			decklist_show 
			) VALUES(
			'".(integer)$_SESSION['loginid']."',
			'".mysql_real_escape_string($decklist_name)."',
			'".(integer)$decklist_format."',
			NOW(),
			NOW(),
			'".mysql_real_escape_string($decklist_desc)."',
			'".(integer)$decklist['decklist_show']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$res_id = mysql_query("SELECT LAST_INSERT_ID()") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_id = mysql_fetch_array($res_id);
			$decklist_id = $ar_id[0];
			if ($res){
				$action = "decklist_edit";
				$msg = "decklist_add_ok";
				$form_vars = "&did=".$decklist_id;
			} else {
				$action = "decklist_add";
				$msg = "decklist_add_er";
				$form_vars = "";
			}
		} elseif ($mode == "decklist_edit"){
			$res = mysql_query("
			UPDATE "._DB_MTG_DECKLISTS." 
			SET decklist_name = '".mysql_real_escape_string($decklist_name)."', 
			decklist_format = ".(integer)$decklist_format.", 
			decklist_description = '".mysql_real_escape_string($decklist_desc)."', 
			decklist_show = '".(integer)$decklist['decklist_show']."'
			WHERE decklist_id = ".(integer)$decklist['decklist_id']
			) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			if ($res){
				$action = "decklist_show";
				$msg = "decklist_edit_ok";
				$form_vars = "&did=".$decklist['decklist_id'];
			} else {
				$action = "decklist_edit";
				$msg = "decklist_edit_er";
				$form_vars = "";
			}
		}
		header ("Location: ".$this->eden_cfg['url']."index.php?action=".$action.$link.$form_vars."&msg=".$msg);
		exit;
	}
	
	
	
	
	/**
	 * Save Decklist Card
	 * @param array		$_POST
	 * @param string	"add/edit = 1, del = 2"
	 * @return boolen
	 */
	public function saveDecklistCard($card, $mode = 1){
		if ($card == ""){
			return false;
			exit;
	 	} elseif ($mode == 2) { 
			$res = mysql_query("DELETE FROM "._DB_MTG_DECKLISTS_CARDS." 
			WHERE decklist_card_id = ".(integer)$card['card_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		} else {
			// Check if card is already in decklist (main or sideboard)
			$res_card = mysql_query("SELECT decklist_card_id 
			FROM "._DB_MTG_DECKLISTS_CARDS." 
			WHERE decklist_card_decklist_id = ".(integer)$card['decklist_id']." AND decklist_card_card_id = ".(integer)$card['card_id']." AND decklist_card_mode = ".(integer)$card['card_mode']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$num_card = mysql_num_rows($res_card);
			$ar_card = mysql_fetch_array($res_card);
			
			$res_decklist = mysql_query("SELECT decklist_format 
			FROM "._DB_MTG_DECKLISTS." 
			WHERE decklist_id = ".(integer)$card['decklist_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_decklist = mysql_fetch_array($res_decklist);
			
			
			$card_numbers = 0;
			$card_num_all = $this->getDecklistCardNum($card['decklist_id'], $card['card_id'], 0); // All
			$card_num_main = $this->getDecklistCardNum($card['decklist_id'], $card['card_id'], 1); // Main
			$card_num_side = $this->getDecklistCardNum($card['decklist_id'], $card['card_id'], 2); // Side
			
			$card_max = $this->checkCardNumbersByRules($card['card_id'], $ar_decklist['decklist_format']);
			$card_nums = $card_max - $card_num_all;
			
			if ($card_nums == 0){
				// There is max number of this card in the Decklist
				$card_numbers = 0;
				$proceed = false;
			} elseif ($card_nums < $card['card_num']){
				if ($card['card_mode'] == 1){
					$card_numbers = $card_num_main + $card_nums;
				} else {
					$card_numbers = $card_num_side + $card_nums;
				}
				$proceed = true;
			} elseif ($card_nums == $card['card_num']){
				if ($card['card_mode'] == 1){
					$card_numbers = $card_num_main + $card_nums;
				} else {
					$card_numbers = $card_num_side + $card_nums;
				}
				$proceed = true;
			} elseif ($card_nums > $card['card_num']){
				if ($card['card_mode'] == 1){
					$card_numbers = $card_num_main + $card['card_num'];
				} else {
					$card_numbers = $card_num_side + $card['card_num'];
				}
				$proceed = true;
			}
			//echo "Card ID:".$card['card_id']."<br>card_nums:".$card_nums."<br>card_max:".$card_max."<br>card_num_all:".$card_num_all."<br>card[card_num]:".$card['card_num']."<br>card_num_main:".$card_num_main."<br>card_num_side:".$card_num_side."<br>card_numbers:".$card_numbers."<br>Mode:".$card['card_mode'];
			//exit;
			// Everything okay - proceed
			if ($proceed == true && $card_numbers > 0){
				// If there is this card in the decklist (main or sideboard)
				if ($num_card != 0){
					$res = mysql_query("UPDATE "._DB_MTG_DECKLISTS_CARDS." 
					SET 
					decklist_card_num = ".(integer)$card_numbers." 
					WHERE decklist_card_id = ".(integer)$ar_card['decklist_card_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				} else {
					$res = mysql_query("INSERT INTO "._DB_MTG_DECKLISTS_CARDS." (
					decklist_card_decklist_id, 
					decklist_card_card_id, 
					decklist_card_num, 
					decklist_card_mode 
					) VALUES(
					'".(integer)$card['decklist_id']."',
					'".(integer)$card['card_id']."',
					'".(integer)$card_numbers."',
					'".(integer)$card['card_mode']."'
					)") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				}
			}
		}
		
		if ($res){
			$this->checkDecklistComplete($card['decklist_id'], "standard");
			$output = true;
		} else {
			$output = false;
		}
		
		return $output;
	}
	
	
	/**
	 * Check if number of cards are in compliance with the rules
	 * @param integer		Card ID
	 * @param integer		Number of cards to be add to the Decklist
	 * @param string		Decklist type (1 = standard, 2 = modern, 3 = extended, 4 = legacy, 5 = vintage, 6 = commander) 
	 * @return integer		Return number of cards by rules - if number is not in 
	 *						compliance with the rules, highest allowed number is returned
	 */
	 public function checkCardNumbersByRules($card_id = 0, $type = 1){
	 	if ($card_id == 0){
			return false;
		 	exit;
		 } else {
		 	$output = 0;
		 	switch ($type){
				case 1:
					if ($this->isBasicLandType($card_id) == true){
						$output = 20;
					} else {
						$output = 4;
					}
				break;
				case 2:
				 	if ($this->isBasicLandType($card_id) == true){
						$output = 20;
					} else {
						$output = 4;
					}
				break;
				case 3:
				 	if ($this->isBasicLandType($card_id) == true){
						$output = 20;
					} else {
						$output = 4;
					}
				break;
				case 4:
				 	if ($this->isBasicLandType($card_id) == true){
						$output = 20;
					} else {
						$output = 4;
					}
				break;
				case 5:
				 	if ($this->isBasicLandType($card_id) == true){
						$output = 20;
					} else {
						$output = 4;
					}
				break;
				case 6:
				 	if ($this->isBasicLandType($card_id) == true){
						$output = 20;
					} else {
						$output = 1;
					}
				break;
				case 7:
				 	if ($this->isBasicLandType($card_id) == true){
						$output = 20;
					} else {
						$output = 4;
					}
				break;
				case 8:
				 	if ($this->isBasicLandType($card_id) == true){
						$output = 20;
					} else {
						$output = 1;
					}
				break;
			}
			
			return $output;
		 }
	 }
	/**
	 * Check if Decklist is complete (according the rules which say how many cards should be in main deck or sideboard)
	 * @param integer		Decklist ID
	 * @param integer		Card ID
	 * @param integer		Decklist mode (0 = all, 1 = main, 2 = side)
	 * @return integer
	 */
	public function getDecklistCardNum($did = 0, $card_id = 0, $mode = 0){
	 	if ($did == 0 || $card_id == 0){
			$output = 0;
		} else {
			if ($mode == 1){
				$where = " AND decklist_card_mode = 1 ";
			} elseif ($mode == 2){
				$where = " AND decklist_card_mode = 2 ";
			} else {
				$where = "";
			}
			
	 		$res = mysql_query("SELECT decklist_card_num 
			FROM "._DB_MTG_DECKLISTS_CARDS." 
			WHERE decklist_card_decklist_id = ".(integer)$did." AND decklist_card_card_id = ".(integer)$card_id." $where 
			") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$output = 0;
			while ($ar = mysql_fetch_array($res)){
				$output = $output + $ar['decklist_card_num'];
			}
		}
		
		return $output;
	}
	
	/**
	 * Check if Decklist is complete (according the rules which say how many cards should be in main deck or sideboard)
	 * @param integer		Decklist ID
	 * @param string		Decklist type
	 * @return bolean		
	 */
	 public function checkDecklistComplete($did){
	 	// Check how many cards is in the main decklist
		$num_main = 0;
		
		$res_main = mysql_query("SELECT decklist_card_num 
		FROM "._DB_MTG_DECKLISTS_CARDS." 
		WHERE decklist_card_decklist_id = ".(integer)$did." AND decklist_card_mode = 1
		") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		
		while ($ar_main = mysql_fetch_array($res_main)){
			$num_main = $num_main + $ar_main['decklist_card_num'];
		}
		
		// Check how many cards is in the sideboard
		$num_side = 0;
		$res_side = mysql_query("SELECT decklist_card_num 
		FROM "._DB_MTG_DECKLISTS_CARDS." 
		WHERE decklist_card_decklist_id = ".(integer)$did." AND decklist_card_mode = 2
		") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		while ($ar_side = mysql_fetch_array($res_side)){
			$num_side = $num_side + $ar_side['decklist_card_num'];
		}
		
		// If there is right number of cards in decklist, save decklist as complete
		if ($num_main >= 60 & ($num_side == 0 || $num_side == 15)){
			mysql_query("UPDATE "._DB_MTG_DECKLISTS." SET 
			decklist_complete = 1 
			WHERE decklist_id = ".(integer)$did) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		} else {
			mysql_query("UPDATE "._DB_MTG_DECKLISTS." SET 
			decklist_complete = 0 
			WHERE decklist_id = ".(integer)$did) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	 }
	
	
	
	/**
	 * Return Simple Card Type
	 * @param string		Card type
	 * @return string		Simple card type (Creature instead of Legendary Creature)
	 */
	 public function getSimpleCardType($type){
	 	$card_type = explode(" ", $type);
		if ($card_type[0]." ".$card_type[1] == "Legendary Artifact"){
		 	$output = "Artifact";
		} elseif ($card_type[0]." ".$card_type[1] == "Snow Artifact"){
		 	$output = "Artifact";
		} elseif ($card_type[0]." ".$card_type[1] == "Tribal Artifact"){
		 	$output = "Artifact";
		} elseif ($card_type[0]." ".$card_type[1] == "Artifact Creature"){
		 	$output = "Creature";
		} elseif ($card_type[0]." ".$card_type[1] == "Land Creature"){
		 	$output = "Creature";
		} elseif ($card_type[0]." ".$card_type[1] == "Legendary Creature"){
		 	$output = "Creature";
		} elseif ($card_type[0]." ".$card_type[1] == "Snow Creature"){
		 	$output = "Creature";
		} elseif ($card_type[0] == "Summon"){
		 	$output = "Creature";
		} elseif ($card_type[0]." ".$card_type[1] == "Enchant Creature"){
			$output = "Enchantment";
		} elseif ($card_type[0]." ".$card_type[1] == "Enchant Player"){
			$output = "Enchantment";
		} elseif ($card_type[0]." ".$card_type[1] == "Legendary Enchantment"){
			$output = "Enchantment";
		} elseif ($card_type[0]." ".$card_type[1] == "Snow Enchantment"){
			$output = "Enchantment";
		} elseif ($card_type[0]." ".$card_type[1] == "Tribal Enchantment"){
			$output = "Enchantment";
		} elseif ($card_type[0]." ".$card_type[1] == "World Enchantment"){
			$output = "Enchantment";
		} elseif ($card_type[0]." ".$card_type[1] == "Tribal Instant"){
			$output = "Instant";
		} elseif ($card_type[0]." ".$card_type[1] == "Artifact Land"){
			$output = "Land";
		} elseif ($card_type[0]." ".$card_type[1] == "Basic Land"){
			$output = "Land";
		} elseif ($card_type[0]." ".$card_type[1]." ".$card_type[2] == "Basic Snow Land"){
			$output = "Land";
		} elseif ($card_type[0]." ".$card_type[1] == "Legendary Land"){
			$output = "Land";
		} elseif ($card_type[0]." ".$card_type[1]." ".$card_type[2] == "Legendary Snow Land"){
			$output = "Land";
  	    } elseif ($card_type[0]." ".$card_type[1] == "Snow Land"){
			$output = "Land";
		} elseif ($card_type[0] == "Scheme"){
			$output = "Planeswalker";
		} elseif ($card_type[0]." ".$card_type[1] == "Ongoing Scheme"){
			$output = "Planeswalker";
		} elseif ($card_type[0]." ".$card_type[1] == "Tribal Sorcery"){
			$output = "Sorcery";
		} else {
			$output = $card_type[0];
		}
	 	return $output;
	 }
	 
	 
	 /**
	 * Check Basic Land Type
	 * @param integer		Card ID
	 * @return boolean		Return true if card is basic land
	 */
	 public function isBasicLandType($card_id){
	 	
	 	$res = mysql_query("SELECT mtg_card_type 
		FROM "._DB_MTG_CARDS." 
		WHERE mtg_card_id = ".$card_id) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar = mysql_fetch_array($res);
		
	 	$card_type = explode(" ", $ar['mtg_card_type']);
		if ($card_type[0]." ".$card_type[1] == "Basic Land"){
			$output = true;
		} elseif ($card_type[0]." ".$card_type[1]." ".$card_type[2] == "Basic Snow Land"){
			$output = true;
		} else {
			$output = false;
		}
	 	return $output;
	 }
	 
	 
	 /**
	 * Show decklist cards
	 * @param integer		Decklist ID
	 * @param integer		Mode - 1 = Main deck, 2 = Sideboard
	 * @return array		num, decklist
	 */
	 public function showDecklisCards($decklist_id, $mode = 1){
	 	$res_cards = mysql_query("
		SELECT decklist_card_num, mtg_card_id, mtg_card_mtg_id, mtg_card_name, mtg_card_set, mtg_card_set_code, mtg_card_type, mtg_card_variation 
		FROM "._DB_MTG_DECKLISTS_CARDS." 
		JOIN "._DB_MTG_CARDS." ON mtg_card_id = decklist_card_card_id 
		WHERE  	decklist_card_decklist_id = ".(integer)$decklist_id." AND decklist_card_mode = ".(integer)$mode."
		ORDER BY mtg_card_type ASC, mtg_card_name ASC 
		") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$type = "";
		$num = 0;
		
		$cards_artifact = "";
		$cards_artifact_num = 0;
		$cards_creature = "";
		$cards_creature_num = 0;
		$cards_ench = "";
		$cards_ench_num = 0;
		$cards_instant = "";
		$cards_instant_num = 0;
		$cards_planeswalker = "";
		$cards_planeswalker_num = 0;
		$cards_sorcery = "";
		$cards_sorcery_num = 0;
		$cards_plane = "";
		$cards_plane_num = 0;
		$cards_land = "";
		$cards_land_num = 0;
		
		while ($ar_cards = mysql_fetch_array($res_cards)){
			$card_type = $this->getSimpleCardType($ar_cards['mtg_card_type']);
			$num = $num + $ar_cards['decklist_card_num'];
			if ($card_type == "Artifact"){
				$cards_artifact .= "<span>".$ar_cards['decklist_card_num']."x <a href=\"#".$ar_cards['mtg_card_id']."\" rel=\"magic,cz,mtgcard,".$ar_cards['mtg_card_id']."\" class=\"eden_hintbox_trigger\">".$ar_cards['mtg_card_name']."</a></span><br>";
				$cards_artifact_num = $cards_artifact_num + $ar_cards['decklist_card_num'];
				$cards_artifact_title = "<h4>Artifact (".$cards_artifact_num.")</h4>";
			}
			if ($card_type == "Creature"){
				$cards_creature .= "<span>".$ar_cards['decklist_card_num']."x <a href=\"#".$ar_cards['mtg_card_id']."\" rel=\"magic,cz,mtgcard,".$ar_cards['mtg_card_id']."\" class=\"eden_hintbox_trigger\">".$ar_cards['mtg_card_name']."</a></span><br>";
				$cards_creature_num = $cards_creature_num + $ar_cards['decklist_card_num'];
				$cards_creature_title = "<h4>Creature (".$cards_creature_num.")</h4>";
			}
			if ($card_type == "Enchantment"){
				$cards_ench .= "<span>".$ar_cards['decklist_card_num']."x <a href=\"#".$ar_cards['mtg_card_id']."\" rel=\"magic,cz,mtgcard,".$ar_cards['mtg_card_id']."\" class=\"eden_hintbox_trigger\">".$ar_cards['mtg_card_name']."</a></span><br>";
				$cards_ench_num = $cards_ench_num + $ar_cards['decklist_card_num'];
				$cards_ench_title = "<h4>Enchantment (".$cards_ench_num.")</h4>";
			}
			if ($card_type == "Instant"){
				$cards_instant .= "<span>".$ar_cards['decklist_card_num']."x <a href=\"#".$ar_cards['mtg_card_id']."\" rel=\"magic,cz,mtgcard,".$ar_cards['mtg_card_id']."\" class=\"eden_hintbox_trigger\">".$ar_cards['mtg_card_name']."</a></span><br>";
				$cards_instant_num = $cards_instant_num + $ar_cards['decklist_card_num'];
				$cards_instant_title = "<h4>Instant (".$cards_instant_num.")</h4>";
			}
			if ($card_type == "Planeswalker"){
				$cards_planeswalker .= "<span>".$ar_cards['decklist_card_num']."x <a href=\"#".$ar_cards['mtg_card_id']."\" rel=\"magic,cz,mtgcard,".$ar_cards['mtg_card_id']."\" class=\"eden_hintbox_trigger\">".$ar_cards['mtg_card_name']."</a></span><br>";
				$cards_planeswalker_num = $cards_planeswalker_num + $ar_cards['decklist_card_num'];
				$cards_planeswalker_title = "<h4>Planeswalker (".$cards_planeswalker_num.")</h4>";
			}
			if ($card_type == "Sorcery"){
				$cards_sorcery .= "<span>".$ar_cards['decklist_card_num']."x <a href=\"#".$ar_cards['mtg_card_id']."\" rel=\"magic,cz,mtgcard,".$ar_cards['mtg_card_id']."\" class=\"eden_hintbox_trigger\">".$ar_cards['mtg_card_name']."</a></span><br>";
				$cards_sorcery_num = $cards_sorcery_num + $ar_cards['decklist_card_num'];
				$cards_sorcery_title = "<h4>Sorcery (".$cards_sorcery_num.")</h4>";
			}
			if ($card_type == "Plane"){
				$cards_plane .= "<span>".$ar_cards['decklist_card_num']."x <a href=\"#".$ar_cards['mtg_card_id']."\" rel=\"magic,cz,mtgcard,".$ar_cards['mtg_card_id']."\" class=\"eden_hintbox_trigger\">".$ar_cards['mtg_card_name']."</a></span><br>";
				$cards_plane_num = $cards_plane_num + $ar_cards['decklist_card_num'];
				$cards_plane_title = "<h4>Plane (".$cards_plane_num.")</h4>";
			}
			if ($card_type == "Land"){
				$cards_land .= "<span>".$ar_cards['decklist_card_num']."x <a href=\"#".$ar_cards['mtg_card_id']."\" rel=\"magic,cz,mtgcard,".$ar_cards['mtg_card_id']."\" class=\"eden_hintbox_trigger\">".$ar_cards['mtg_card_name']."</a></span><br>";
				$cards_land_num = $cards_land_num + $ar_cards['decklist_card_num'];
				$cards_land_title = "<h4>Land (".$cards_land_num.")</h4>";
			}
	  	}
		
		$decklist = "";
		
		if ($cards_artifact != ""){
			$decklist .= $cards_artifact_title;
			$decklist .= $cards_artifact;
		}
		if ($cards_creature != ""){
			$decklist .= $cards_creature_title;
			$decklist .= $cards_creature;
		}
		if ($cards_ench != ""){
			$decklist .= $cards_ench_title;
			$decklist .= $cards_ench;
		}
		if ($cards_instant != ""){
			$decklist .= $cards_instant_title;
			$decklist .= $cards_instant;
		}
		if ($cards_planeswalker != ""){
			$decklist .= $cards_planeswalker_title;
			$decklist .= $cards_planeswalker;
		}
		if ($cards_sorcery != ""){
			$decklist .= $cards_sorcery_title;
			$decklist .= $cards_sorcery;
		}
		if ($cards_plane != ""){
			$decklist .= $cards_plane_title;
			$decklist .= $cards_plane;
		}
		if ($cards_land != ""){
			$decklist .= $cards_land_title;
			$decklist .= $cards_land;
		}
		
		$output = array('num' => $num,	'decklist' => $decklist);
		
		return $output;
	}
	
	/**
	 * Get Decklist Format Name
	 * @param integer		Decklist Format ID
	 * @return string		Decklist Format Name
	 */
	 public function getDecklistFormatName($decklist_format){
	 	
	 	$output = "";
	 	switch ($decklist_format){
			case 1:
				$output = "Standard";
				break;
			case 2:
				$output = "Modern";
				break;
			case 3:
				$output = "Extended";
				break;
			case 4:
				$output = "Legacy";
				break;
			case 5:
				$output = "Vintage";
				break;
			case 6:
				$output = "Commander";
				break;
			case 7:
				$output = "Pauper";
				break;
			case 8:
				$output = "Draft";
				break;
		}
		return $output;
	 }
}


