<?php
/***********************************************************************************************************
*
*		Dictionary
*
*		Zobrazeni slovniku cizich slov
*
*
***********************************************************************************************************/
function Dictionary(){
	
	global $db_dictionary;
	global $eden_cfg;
	
	$_GET['mode'] = AGet($_GET,'mode');
	
	echo "<table class=\"eden_dictionary\" cellspacing=\"2\" cellpadding=\"1\">\n";
	if ($_GET['mode'] == "dict_add_word" || $_GET['mode'] == "dict_add_rev" && $_SESSION['loginid'] != ""){
		echo "<tr>\n";
		echo "	<td align=\"left\" colspan=\"3\">\n";
		echo "		<table cellspacing=\"2\" cellpadding=\"2\" border=\"0\">\n";
		echo "			<tr>\n";
		echo "				<td align=\"right\" valign=\"top\"><form action=\"".$eden_cfg['url_edencms']."eden_save.php?lang=".$_GET['lang']."&amp;filter=".$_GET['filter']." method=\"post\" enctype=\"multipart/form-data\"><strong>"._DICT_WORD."</strong></td>\n";
		echo "				<td align=\"left\"><input type=\"text\" name=\"dictionary_word\" maxlength=\"255\" size=\"60\"></td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td align=\"right\" valign=\"top\"><strong>"._DICT_WORD_DESC."</strong></td>\n";
		echo "				<td align=\"left\"><textarea name=\"dictionary_word_description\" rows=\"8\" cols=\"50\"></textarea><br>\n";
		echo "					<input type=\"submit\" value=\""; if ($_GET['mode'] == "dict_add_word"){echo _DICT_ADD_WORD;} else {echo _DICT_ADD_REV;} echo"\" class=\"eden_button\">\n";
		echo "					<input type=\"hidden\" name=\"mode\" value=\"".$_GET['mode']."\">\n";
		echo "					<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "					<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "					</form>\n";
		echo "				</td>\n";
		echo "			</tr>\n";
		echo "		</table>\n";
		echo "	</td>\n";
		echo "</tr>\n";
	}
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"3\">".Alphabeth('index.php?action=dict&amp;letter=','');	if ($_SESSION['loginid'] != ""){ echo " &nbsp; <a href=\"index.php?action=dict&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;mode=dict_add_word\">"._DICT_ADD."</a>"; } echo "</td>\n";
	echo "	</tr>\n";
	echo "	<tr class=\"eden_dictionary\">\n";
	echo "		<td class=\"eden_dictionary_title_word\">"._DICT_TITLE_WORD."</td>\n";
	echo "		<td class=\"eden_dictionary_title_author\">"._DICT_TITLE_AUTHOR."</td>\n";
	echo "		<td class=\"eden_dictionary_title_date\">"._DICT_TITLE_DATE."</td>\n";
	echo "	</tr>\n";
	if (AGet($_GET,'letter') == "Other"){$like2 = "REGEXP";} elseif (AGet($_GET,'letter') != ""){$like2 = "LIKE";} else {$like2 = FALSE;}
	if (AGet($_GET,'letter') != "All"){ $like = "AND dictionary_word ".$like2." ".AlphabethSelect(mysql_real_escape_string(AGet($_GET,'letter')), "dictionary_word");}
	if (AGet($_GET,'id') != ""){$like = "AND dictionary_id=".(integer)AGet($_GET,'id');}// else { $word = preg_replace("/[^a-z0-9 ]/si","",$_GET['word']); $like = "AND dictionary_word LIKE '".$word."%'";}
	$res = mysql_query("SELECT dictionary_id, dictionary_author_id, dictionary_word, dictionary_word_description, dictionary_date FROM $db_dictionary WHERE dictionary_parent_id=0 AND dictionary_allow=1 $like ORDER BY dictionary_word ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$cislo = 0;
	while($ar = mysql_fetch_array($res)){
		if ($cislo % 2 == 0){$class = "suda";} else { $class = "licha";}
		echo "	<tr class=\"".$class."\">\n";
		echo "		<td class=\"eden_dictionary_word\"><a href=\"".$eden_cfg['url']."index.php?action=dict&amp;id=".$ar['dictionary_id']."&amp;letter=".AGet($_GET,'letter')."&amp;mode="; if ($_GET['mode'] == "open"	&& AGet($_GET,'id') == $ar['dictionary_id'] ){echo "close";}else{ echo "open";} echo "\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('".addcslashes($ar['dictionary_word_description'],"\0..\37!@\40..\41")."', this, event, '450px')\">".$ar['dictionary_word']."</a></td>\n";
		echo "		<td class=\"eden_dictionary_author\">".GetNickName($ar['dictionary_author_id'])."</td>\n";
		echo "		<td class=\"eden_dictionary_date\">".FormatDatetime($ar['dictionary_date'],$format = "d.m.Y")."</td>\n";
		echo "	</tr>\n"; 
		if ($_GET['mode'] == "open" && AGet($_GET,'id') == $ar['dictionary_id']){
			echo "	<tr>\n";
			echo "		<td class=\"eden_dictionary_desc\" colspan=\"3\">".$ar['dictionary_word_description']."</td>\n";
			echo "	</tr>\n"; 
		}
		$cislo++;
	}
	echo "</table>\n";
}
/***********************************************************************************************************
*
*		Dictionary What Means
*
*		Zobrazi slovo ze slovniku cizich slov a vypisejeho vyznam
*
*
***********************************************************************************************************/
function DictionaryWhatMeans(){
	
	global $db_dictionary;
	global $eden_cfg;
	
	$res = mysql_query("SELECT dictionary_id FROM $db_dictionary WHERE dictionary_parent_id=0 AND dictionary_allow=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$words = Array();
	while ($ar = mysql_fetch_array($res)){
		$words[] .= $ar['dictionary_id'];
	}
	$num = count($words) - 1; // -1 je prevence proti zobrazeni cisla minus rozsah pole
	$random_num = rand (0,$num);
	$random_word = $words[$random_num];
	$res_word = mysql_query("SELECT dictionary_word, dictionary_word_description FROM $db_dictionary WHERE dictionary_id=".(integer)$random_word) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_word = mysql_fetch_array($res_word);
	echo "<span class=\"h2\">".$ar_word['dictionary_word']."</span><br>".$ar_word['dictionary_word_description'];
}