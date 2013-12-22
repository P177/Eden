<?php
/***********************************************************************************************************
*
*		DOWNLOAD
*
*		Tato funkce zobrazi seznam kategorii
*
*		AGet($_GET,'dld')	=	dl_detail
*
***********************************************************************************************************/
function Download(){
	
	global $db_category,$db_download,$db_comments;
	global $url_download;
	global $eden_cfg;
	global $project;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	echo "<table id=\"download\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">\n";
	// Hlavni Menu
	//***********************************************************************************
	$res = mysql_query("SELECT category_id, category_name FROM $db_category WHERE category_parent=0 AND category_download=1 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i = 1;
	while ($ar = mysql_fetch_array($res)){
		$resdown = mysql_query("SELECT * FROM $db_download WHERE download_category1=".(integer)$ar['category_id']." OR download_category2=".(integer)$ar['category_id']." OR download_category3=".(integer)$ar['category_id']." OR download_category4=".(integer)$ar['category_id']." OR download_category5=".(integer)$ar['category_id']." ORDER BY download_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$numdown = mysql_num_rows($resdown);
		$hlavicka = stripslashes($ar['category_name']);
		$res01 = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".(integer)$ar['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar01 = mysql_fetch_array($res01);
		$cid = $ar['category_id'];
		echo "<tr class=\"download_1_level\">\n";
		echo "	<td width=\"450\" align=\"left\" valign=\"middle\" class=\"download_nadpis\"><a name=\"".$ar['category_id']."\"></a></td>\n";
		echo "	<td width=\"150\" align=\"right\" valign=\"middle\">&nbsp;</td>\n";
		echo "</tr>"; 
		if (AGet($_GET,'dld') == "open" && $ar['category_id'] == $_GET['dl_id']){
			echo "<tr>\n";
			echo "	<td colspan=\"2\">\n";
			echo "		<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\">"; 
			while($ardown = mysql_fetch_array($resdown)){
				// Spocitame komentare
				$rescom = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ardown['download_id']." AND comment_modul='download'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$numcom = mysql_fetch_array($rescom);
				echo "<tr class=\"download_nazev\">\n";
				echo " 	<td width=\"360\" align=\"left\" valign=\"middle\"><strong>".$ardown['download_name']."</strong><a name=\"#".$ardown['download_id']."\"></a></td>\n";
				echo " 	<td width=\"50\" align=\"left\" valign=\"middle\"><a href=\"".$eden_cfg['url_edencms']."eden_download.php?id=".$ardown['download_id']."&amp;mirr=1&amp;project=".$project."\" target=\"_blank\">Download</a></td>\n";
				echo "	<td width=\"60\" align=\"left\" valign=\"middle\">"; if ($ardown['download_link2'] != ""){ echo "<a href=\"".$eden_cfg['url_edencms']."eden_download.php?id=".$ardown['download_id']."&amp;mirr=2&amp;project=".$project."\" target=\"_blank\">Download&nbsp;2</a>"; } echo "</td>\n";
				echo "	<td width=\"60\" align=\"left\" valign=\"middle\">"; if ($ardown['download_link3'] != ""){ echo "<a href=\"".$eden_cfg['url_edencms']."eden_download.php?id=".$ardown['download_id']."&amp;mirr=3&amp;project=".$project."\" target=\"_blank\">Download&nbsp;3</a>"; } echo "</td>\n";
				echo "	<td width=\"70\" align=\"right\" valign=\"middle\">".number_format($ardown['download_size'], 0, ',', ' ')." kB</td>\n";
				echo "</tr>\n";
				echo "<tr class=\"download\">\n";
				echo "	<td align=\"left\" valign=\"middle\" colspan=\"5\">"; if ($ardown['download_img'] != ""){ echo "<img src=\"".$url_download.$ardown['download_img']."\" alt=\"\" border=\"0\" align=\"left\" hspace=\"5\" vspace=\"5\">"; } echo $ardown['download_description']."<br>"; if (AGet($_GET,'stav') == "open" && $_GET['did'] == $ardown['download_id']){ echo "<a href=\"index.php?&amp;project=".$project."&amp;action=files&amp;stav=close\">"._CLOSE."</a>"; } else {echo _COMMENTS_DOWNLOAD.": <a href=\"index.php?did=".$ardown['download_id']."&amp;project=".$project."&amp;action=files&amp;dl_id=".$ar['category_id']."&amp;dld=open&amp;stav=open#".$ardown['download_id']."\"> <strong>".$numcom[0]."</strong>&nbsp;&nbsp;"._ADD_COMMENTS."</a>"; } echo "\n";
					  		if ($ardown['download_author_web'] != ""){echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;"._WEB_AUTORA.": <a href=\"http://".$ardown['download_author_web']."\" target=\"_blank\">".$ardown['download_author_web']."</a>"; } echo "\n";
					  		if ($ardown['download_num_download'] != "0"){echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;"._NUM_DOWNLOAD.": <strong>".$ardown['download_num_download']."</strong>";} echo "\n";
				echo "	</td>\n";
				echo "</tr>"; 
				if (AGet($_GET,'stav') == "open" && $_GET['did'] == $ardown['download_id']){
					echo "<tr>\n";
					echo "	<td colspan=\"5\">".Comments($ardown['download_id'],"download")."</td>\n";
					echo "</tr>\n";
				}
			}
			echo "		</table>\n";
			echo "	</td>\n";
			echo "</tr>\n"; 
		}
		//***********************************************************************************
		// Prvni Podmenu
		//***********************************************************************************
		if ($cid == $ar['category_id']){
			$res2 = mysql_query("SELECT category_id, category_name FROM $db_category WHERE category_parent=".(integer)$cid." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$a = 1;
			while ($ar2 = mysql_fetch_array($res2)){
				// Nacteme programy v danych kategoriich
				$resdown2 = mysql_query("SELECT * FROM $db_download WHERE download_category1=".(integer)$ar2['category_id']." OR download_category2=".(integer)$ar2['category_id']." OR download_category3=".(integer)$ar2['category_id']." OR download_category4=".(integer)$ar2['category_id']." OR download_category5=".(integer)$ar2['category_id']." ORDER BY download_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$numdown2 = mysql_num_rows($resdown2);
				$hlavicka2 = stripslashes($ar2['category_name']);
				$res02 = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".(integer)$ar2['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar02 = mysql_fetch_array($res02);
				$cid2 = $ar2['category_id'];
				echo "<tr class=\"download_2_level\">\n";
				echo "	<td width=\"450\" align=\"left\" valign=\"middle\" class=\"download_nadpis\"><a name=\"".$ar2['category_id']."\"></a><a href=\"index.php?action=files&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;dl_id=".$ar['category_id']."&amp;dl_id2=".$ar2['category_id']."&amp;dld=close&amp;dld2="; if ((AGet($_GET,'dld2') == "" || AGet($_GET,'dld2') == "close") || $ar2['category_id'] != $_GET['dl_id2']){echo "open";} else {echo "close";} echo "#".$ar2['category_id']."\">".$hlavicka2." (<span class=\"download_nadpis_m\">"._DOWNLOAD_FILES_NUMBER.$numdown2."</span>)"."</a></td>\n";
				echo "	<td width=\"150\" align=\"right\" valign=\"middle\"><a href=\"index.php?action=files&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;dl_id=".$ar['category_id']."&amp;dl_id2=".$ar2['category_id']."&amp;dld=close&amp;dld2="; if ((AGet($_GET,'dld2') == "" || AGet($_GET,'dld2') == "close") || $ar2['category_id'] != $_GET['dl_id2']){echo "open";} else {echo "close";} echo "#".$ar2['category_id']."\">"; if (AGet($_GET,'dld2') == "open" && $ar2['category_id'] == $_GET['dl_id2']){echo _DL_CLOSE;} else {echo _DL_OPEN;} echo "</a></td>\n";
				echo "</tr>\n";
				if (AGet($_GET,'dld2') == "open" && $ar2['category_id'] == $_GET['dl_id2']){
					echo "<tr>\n";
					echo "	<td colspan=\"2\">\n";
					echo "		<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\">\n";
					while($ardown2 = mysql_fetch_array($resdown2)){
						// Spocitame komentare
						$rescom2 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ardown2['download_id']." AND comment_modul='download'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$numcom2 = mysql_fetch_array($rescom2);
						echo "<tr class=\"download_nazev\">\n";
	  					echo "	<td width=\"360\" align=\"left\" valign=\"middle\"><strong>".$ardown2['download_name']."</strong><a name=\"#".$ardown2['download_id']."\"></a></td>\n";
			 			echo "	<td width=\"50\" align=\"left\" valign=\"middle\"><a href=\"".$eden_cfg['url_edencms']."eden_download.php?id=".$ardown2['download_id']."&amp;mirr=1&amp;project=".$project."\" target=\"_blank\">Download</a></td>\n";
	   					echo "	<td width=\"60\" align=\"left\" valign=\"middle\">"; if ($ardown2['download_link2'] != ""){ echo "<a href=\"".$eden_cfg['url_edencms']."eden_download.php?id=".$ardown2['download_id']."&amp;mirr=2&amp;project=".$project."\" target=\"_blank\">Download&nbsp;2</a>"; } echo "</td>\n";
						echo "	<td width=\"60\" align=\"left\" valign=\"middle\">"; if ($ardown2['download_link3'] != ""){ echo "<a href=\"".$eden_cfg['url_edencms']."eden_download.php?id=".$ardown2['download_id']."&amp;mirr=3&amp;project=".$project."\" target=\"_blank\">Download&nbsp;3</a>"; } echo "</td>\n";
		 				echo "	<td width=\"70\" align=\"right\" valign=\"middle\">".number_format($ardown2['download_size'], 0, ',', ' ')." kB</td>\n";
	 					echo "</tr>\n";
		 				echo "<tr class=\"download\">\n";
	  					echo "	<td align=\"left\" valign=\"middle\" colspan=\"5\">"; if ($ardown2['download_img'] != ""){ echo "<img src=\"".$url_download.$ardown2['download_img']."\" alt=\"\" border=\"0\" align=\"left\" hspace=\"5\" vspace=\"5\">"; } echo $ardown2['download_description']."<br>"; if (AGet($_GET,'stav') == "open" && $_GET['did'] == $ardown2['download_id']){ echo "<a href=\"index.php?&amp;project=".$project."&amp;action=files&amp;stav=close\">"._CLOSE."</a>"; } else {echo _COMMENTS_DOWNLOAD.": <a href=\"index.php?did=".$ardown2['download_id']."&amp;project=".$project."&amp;action=files&amp;dl_id=".$ar['category_id']."&amp;dl_id2=".$ar2['category_id']."&amp;dld=open&amp;dld2=open&stav2=open#".$ardown2['download_id']."\"> <strong>".$numcom2[0]."</strong>&nbsp;&nbsp;"._ADD_COMMENTS."</a>"; } echo "\n";
  							  		if ($ardown2['download_author_web'] != ""){echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;"; echo _WEB_AUTORA.": <a href=\"http://".$ardown2['download_author_web']."\" target=\"_blank\">".$ardown2['download_author_web']."</a>"; } echo "\n";
							  		if ($ardown2['download_num_download'] != "0"){echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;"; echo _NUM_DOWNLOAD.": <strong>".$ardown2['download_num_download']."</strong>";} echo "\n";
			  			echo "	</td>\n";
			  			echo "</tr>\n"; 
						if (AGet($_GET,'stav2') == "open" && $_GET['did'] == $ardown2['download_id']){
			  				echo "<tr>\n";
			 				echo "	<td colspan=\"5\">".Comments($ardown2['download_id'],"download")."</td>\n";
			 				echo "</tr>\n";
						}
					}
					echo "		</table>\n";
					echo "	</td>\n";
					echo "</tr>\n"; 
				}
				//***********************************************************************************
				// Druhe Podmenu
				//***********************************************************************************
				if ($cid2 == $ar2['category_id']){
					// Zajisteni rozbaleni, nebo zbaleni senamu po kliknuti
					$res3 = mysql_query("SELECT category_id, category_name FROM $db_category WHERE category_parent=".(integer)$cid2." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$b = 1;
					while ($ar3 = mysql_fetch_array($res3)){
						$resdown3 = mysql_query("SELECT * FROM $db_download WHERE download_category1=".(integer)$ar3['category_id']." OR download_category2=".(integer)$ar3['category_id']." OR download_category3=".(integer)$ar3['category_id']." OR download_category4=".(integer)$ar3['category_id']." OR download_category5=".(integer)$ar3['category_id']." ORDER BY download_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$numdown3 = mysql_num_rows($resdown3);
						$hlavicka3 = stripslashes($ar3['category_name']);
						$res03 = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".(integer)$ar3['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$cid3 = $ar3['category_id'];
						echo "<tr class=\"download_3_level\">\n";
						echo "	<td width=\"450\" align=\"left\" valign=\"middle\" class=\"download_nadpis\"><a name=\"".$ar3['category_id']."\"></a><a href=\"?action=files&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;dl_id=".$ar['category_id']."&amp;dl_id2=".$ar2['category_id']."&amp;dl_id3=".$ar3['category_id']."&amp;dld=close&amp;dld2=close&amp;dld3="; if ((AGet($_GET,'dld3') == "" || AGet($_GET,'dld3') == "close") || $ar3['category_id'] != $_GET['dl_id3']){echo "open";} else {echo "close";} echo "#".$ar3['category_id']."\">".$hlavicka2." - ".$hlavicka3." (<span class=\"download_nadpis_m\">"._DOWNLOAD_FILES_NUMBER.$numdown3."</span>)</td>\n";
						echo "	<td width=\"150\" align=\"right\" valign=\"middle\"><a href=\"?action=files&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;dl_id=".$ar['category_id']."&amp;dl_id2=".$ar2['category_id']."&amp;dl_id3=".$ar3['category_id']."&amp;dld=close&amp;dld2=close&amp;dld3="; if ((AGet($_GET,'dld3') == "" || AGet($_GET,'dld3') == "close") || $ar3['category_id'] != $_GET['dl_id3']){echo "open";} else {echo "close";} echo "#".$ar3['category_id']."\">"; if (AGet($_GET,'dld3') == "open" && $ar3['category_id'] == $_GET['dl_id3']){echo _DL_CLOSE;} else {echo _DL_OPEN;} echo "</a></td>\n";
						echo "</tr>"; 
						if (AGet($_GET,'dld3') == "open" && $ar3['category_id'] == $_GET['dl_id3']){
							echo "<tr>\n";
							echo "	<td colspan=\"2\">\n";
							echo "		<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\">";
							while($ardown3 = mysql_fetch_array($resdown3)){
								// Spocitame komentare
								$rescom3 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ardown3['download_id']." AND comment_modul='download'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								$numcom3 = mysql_fetch_array($rescom3);
								echo "<tr class=\"download_nazev\">\n";
								echo "	<td width=\"360\" align=\"left\" valign=\"middle\"><strong>".$ardown3['download_name']."</strong><a name=\"#".$ardown3['download_id']."\"></a></td>\n";
								echo "	<td width=\"50\" align=\"left\" valign=\"middle\"><a href=\"".$eden_cfg['url_edencms']."eden_download.php?id=".$ardown3['download_id']."&amp;mirr=1&amp;project=".$project."\" target=\"_blank\">Download</a></td>\n";
								echo "	<td width=\"60\" align=\"left\" valign=\"middle\">"; if ($ardown3['download_link2'] != ""){ echo "<a href=\"".$eden_cfg['url_edencms']."eden_download.php?id=".$ardown3['download_id']."&amp;mirr=2&amp;project=".$project."\" target=\"_blank\">Download&nbsp;2</a>"; } echo "</td>\n";
								echo "	<td width=\"60\" align=\"left\" valign=\"middle\">"; if ($ardown3['download_link3'] != ""){ echo "<a href=\"".$eden_cfg['url_edencms']."eden_download.php?id=".$ardown3['download_id']."&amp;mirr=3&amp;project=".$project."\" target=\"_blank\">Download&nbsp;3</a>"; } echo "</td>\n";
								echo "	<td width=\"70\" align=\"right\" valign=\"middle\">".number_format($ardown3['download_size'], 0, ',', ' ')." kB</td>\n";
								echo "</tr>";
								echo "<tr class=\"download\">";
								echo "	<td align=\"left\" valign=\"middle\" colspan=\"5\">"; if ($ardown3['download_img'] != ""){ echo "<img src=\"".$url_download.$ardown3['download_img']."\" alt=\"\" border=\"0\" align=\"left\" hspace=\"5\" vspace=\"5\">"; } echo " ".$ardown3['download_description']."<br>"; if (AGet($_GET,'stav') == "open" && $_GET['did'] == $ardown3['download_id']){ echo "<a href=\"index.php?&amp;project=".$project."&amp;action=files&amp;stav=close\">"._CLOSE."</a>"; } else {echo _COMMENTS_DOWNLOAD.":<a href=\"index.php?did=".$ardown3['download_id']."&amp;project=".$project."&amp;action=files&amp;dl_id=".$ar['category_id']."&amp;dl_id2=".$ar2['category_id']."&amp;dl_id3=".$ar3['category_id']."&amp;dld=open&amp;dld2=open&amp;dld3=open&stav3=open#".$ardown3['download_id']."\"> <strong>".$numcom3[0]."</strong>&nbsp;&nbsp;"._ADD_COMMENTS."</a>"; }
								if ($ardown3['download_author_web'] != ""){echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;"; echo _WEB_AUTORA.": <a href=\"http://".$ardown3['download_author_web']."\" target=\"_blank\">".$ardown3['download_author_web']."</a>";}
								if ($ardown3['download_num_download'] != "0"){echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;"; echo _NUM_DOWNLOAD.": "; echo "<strong>".$ardown3['download_num_download']."</strong>";}
								echo "	</td>";
								echo "</tr>";
								if (AGet($_GET,'stav3') == "open" && $_GET['did'] == $ardown3['download_id']){
									echo "<tr>";
									echo "	<td colspan=\"5\">"; Comments($ardown3['download_id'],"download"); echo "</td>";
									echo "</tr>";
								}
							}
							echo "		</table>";
							echo "	</td>";
							echo "</tr>";
						}
						//***********************************************************************************
						// Treti Podmenu
						//***********************************************************************************
						if ($cid3 == $ar3['category_id']){
							$res4 = mysql_query("SELECT category_id, category_name FROM $db_category WHERE category_parent=".(integer)$cid3." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$c = 1;
							while ($ar4 = mysql_fetch_array($res4)){
								$resdown4 = mysql_query("SELECT * FROM $db_download WHERE download_category1=".(integer)$ar4['category_id']." OR download_category2=".(integer)$ar4['category_id']." OR download_category3=".(integer)$ar4['category_id']." OR download_category4=".(integer)$ar4['category_id']." OR download_category5=".(integer)$ar4['category_id']." ORDER BY download_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								$numdown4 = mysql_num_rows($resdown4);
								$hlavicka4 = stripslashes($ar4['category_name']);
								$res04 = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".(integer)$ar4['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								$ar04 = mysql_fetch_array($res04);
								$cid4 = $ar4['category_id'];
								echo "<tr>\n";
								echo "	<td width=\"450\" align=\"left\" valign=\"middle\" class=\"download_nadpis\"><a name=\"".$ar4['category_id']."\"></a><a href=\"?action=files&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;dl_id=".$ar['category_id']."&amp;dl_id2=".$ar2['category_id']."&amp;dl_id3=".$ar3['category_id']."&amp;dl_id4=".$ar4['category_id']."&amp;dld=close&amp;dld2=close&amp;dld3=close&amp;dld4="; if (($_GET['dld4'] == "" || $_GET['dld4'] == "close") || $ar4['category_id'] != $_GET['dl_id4']){echo "open";} else {echo "close";} echo "#".$ar4['category_id']."\">".$hlavicka2." - ".$hlavicka3." - ".$hlavicka4." (<span class=\"download_nadpis_m\">"._DOWNLOAD_FILES_NUMBER.$numdown4."</span>)</td>\n";
								echo "	<td width=\"150\" align=\"right\" valign=\"middle\"><a href=\"?action=files&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;dl_id=".$ar['category_id']."&amp;dl_id2=".$ar2['category_id']."&amp;dl_id3=".$ar3['category_id']."&amp;dl_id4=".$ar4['category_id']."&amp;dld=close&amp;dld2=close&amp;dld3=close&amp;dld4="; if (($_GET['dld4'] == "" || $_GET['dld4'] == "close") || $ar4['category_id'] != $_GET['dl_id4']){echo "open";} else {echo "close";} echo "#".$ar4['category_id']."\">"; if ($_GET['dld4'] == "open" && $ar4['category_id'] == $_GET['dl_id4']){echo _DL_CLOSE;} else {echo _DL_OPEN;} echo "</a></td>\n";
								echo "</tr>";
								if ($_GET['dld4'] == "open" && $ar4['category_id'] == $_GET['dl_id4']){
									echo "<tr>";
									echo "	<td colspan=\"2\">";
									echo "		<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\">";
									while($ardown4 = mysql_fetch_array($resdown4)){
										// Spocitame komentare
										$rescom4 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ardown4['download_id']." AND comment_modul='download'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
										$numcom4 = mysql_fetch_array($rescom4);
										echo "<tr class=\"download_nazev\">\n";
										echo "	<td width=\"360\" align=\"left\" valign=\"middle\"><strong>".$ardown4['download_name']."</strong><a name=\"#".$ardown4['download_id']."\"></a></td>\n";
										echo "	<td width=\"50\" align=\"left\" valign=\"middle\"><a href=\"".$eden_cfg['url_edencms']."eden_download.php?id=".$ardown4['id']."&amp;mirr=1&amp;project=".$project."\" target=\"_blank\">Download</a></td>\n";
										echo "	<td width=\"60\" align=\"left\" valign=\"middle\">"; if ($ardown4['download_link2'] != ""){ echo "<a href=\"".$eden_cfg['url_edencms']."eden_download.php?id=".$ardown4['download_id']."&amp;mirr=2&amp;project=".$project."\" target=\"_blank\">Download&nbsp;2</a>"; } echo "</td>\n";
										echo "	<td width=\"60\" align=\"left\" valign=\"middle\">"; if ($ardown4['download_link3'] != ""){ echo "<a href=\"".$eden_cfg['url_edencms']."eden_download.php?id=".$ardown4['download_id']."&amp;mirr=3&amp;project=".$project."\" target=\"_blank\">Download&nbsp;3</a>"; } echo "</td>\n";
										echo "	<td width=\"70\" align=\"right\" valign=\"middle\">".number_format($ardown4['download_size'], 0, ',', ' ')." kB</td>\n";
										echo "</tr>\n";
										echo "<tr class=\"download\">\n";
										echo "	<td align=\"left\" valign=\"middle\" colspan=\"5\">"; if ($ardown4['download_img'] != ""){ echo "<img src=\"".$url_download.$ardown4['download_img']."\" alt=\"\" border=\"0\" align=\"left\" hspace=\"5\" vspace=\"5\">"; } echo " ".$ardown4['download_description']."<br>"; if (AGet($_GET,'stav') == "open" && $_GET['did'] == $ardown4['download_id']){ echo "<a href=\"index.php?&amp;project=".$project."&amp;action=files&amp;stav=close\">"._CLOSE."</a>"; } else {echo _COMMENTS_DOWNLOAD.":<a href=\"index.php?did=".$ardown4['download_id']."&amp;project=".$project."&amp;action=files&amp;dl_id=".$ar['category_id']."&amp;dl_id2=".$ar2['category_id']."&amp;dl_id3=".$ar3['category_id']."&amp;dl_id4=".$ar4['category_id']."&amp;dld=open&amp;dld2=open&amp;dld3=open&amp;dld4=open&stav4=open#".$ardown4['download_id']."\"> <strong>".$numcom4[0]."</strong>&nbsp;&nbsp;"._ADD_COMMENTS."</a>"; }
													if ($ardown4['download_author_web'] != ""){echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;"; echo _WEB_AUTORA.": <a href=\"http://".$ardown4['download_author_web']."\" target=\"_blank\">".$ardown4['download_author_web']."</a>"; }
													if ($ardown4['download_num_download'] != "0"){echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;"._NUM_DOWNLOAD.": <strong>".$ardown4['download_num_download']."</strong>";}
										echo "	</td>\n";
										echo "</tr>";
										if (AGet($_GET,'stav4') == "open" && $_GET['did'] == $ardown4['download_id']){
											echo "<tr>\n";
											echo "	<td colspan=\"5\">"; Comments($ardown4['download_id'],"download"); echo "</td>\n";
											echo "</tr>";
										}
									}
									echo "		</table>";
									echo "	</td>";
									echo "</tr>";
								}
								//***********************************************************************************
								// Ctvrte Podmenu
								//***********************************************************************************
								if ($cid4 == $ar4['category_id']){
									$res5 = mysql_query("SELECT category_id, category_name FROM $db_category WHERE category_parent=".(integer)$cid4." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
									$d = 1;
									while ($ar5 = mysql_fetch_array($res5)){
										$resdown5 = mysql_query("SELECT * FROM $db_download WHERE download_category1=".(integer)$ar5['category_id']." OR download_category2=".(integer)$ar5['category_id']." OR download_category3=".(integer)$ar5['category_id']." OR download_category4=".(integer)$ar5['category_id']." OR download_category5=".(integer)$ar5['category_id']." ORDER BY download_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
										$numdown5 = mysql_num_rows($resdown5);
										$hlavicka5 = stripslashes($ar5['category_name']);
										$res05 = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".(integer)$ar5['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
										$ar05 = mysql_fetch_array($res05);
										$cid5 = $ar5['category_id'];
										echo "<tr>\n";
										echo "	<td width=\"450\" align=\"left\" valign=\"middle\" class=\"download_nadpis\"><a name=\"".$ar5['category_id']."\"></a><a href=\"?action=files&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;dl_id=".$ar['category_id']."&amp;dl_id2=".$ar2['category_id']."&amp;dl_id3=".$ar3['category_id']."&amp;dl_id4=".$ar4['category_id']."&amp;dl_id5=".$ar5['category_id']."&amp;dld=close&amp;dld2=close&amp;dld3=close&amp;dld4=close&amp;dld5="; if (($_GET['dld5'] == "" || $_GET['dld5'] == "close") || $ar5['category_id'] != $_GET['dl_id5']){echo "open";} else {echo "close";} echo "#".$ar5['category_id']."\">"; /*$hlavicka." - ".*/ echo $hlavicka2." - ".$hlavicka3." - ".$hlavicka4." - ".$hlavicka5." (<span class=\"download_nadpis_m\">"._DOWNLOAD_FILES_NUMBER.$numdown5."</span>)</td>\n";
										echo "	<td width=\"150\" align=\"right\" valign=\"middle\"><a href=\"?action=files&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;dl_id=".$ar['category_id']."&amp;dl_id2=".$ar2['category_id']."&amp;dl_id3=".$ar3['category_id']."&amp;dl_id4=".$ar4['category_id']."&amp;dl_id5=".$ar5['category_id']."&amp;dld=close&amp;dld2=close&amp;dld3=close&amp;dld4=close&amp;dld5="; if (($_GET['dld5'] == "" || $_GET['dld5'] == "close") || $ar5['category_id'] != $_GET['dl_id5']){echo "open";} else {echo "close";} echo "#".$ar5['category_id']."\">"; if ($_GET['dld5'] == "open" && $ar5['category_id'] == $_GET['dl_id5']){echo _DL_CLOSE;} else {echo _DL_OPEN;} echo "</a></td>\n";
										echo "</tr>";
										if ($_GET['dld5'] == "open" && $ar5['category_id'] == $_GET['dl_id5']){
											echo "<tr>";
											echo "	<td colspan=\"2\">";
											echo "		<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\">";
											//*********************** Zobrazeni programu z databaze, po rozbaleni
											while($ardown5 = mysql_fetch_array($resdown5)){
												// Spocitame komentare
												$rescom5 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ardown5['download_id']." AND comment_modul='download'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
												$numcom5 = mysql_fetch_array($rescom5);
												echo "<tr class=\"download_nazev\">\n";
												echo "	<td width=\"360\" align=\"left\" valign=\"middle\"><strong>".$ardown5['download_name']."</strong><a name=\"#".$ardown5['download_id']."\"></a></td>\n";
												echo "	<td width=\"50\" align=\"left\" valign=\"middle\"><a href=\"".$eden_cfg['url_edencms']."eden_download.php?id=".$ardown5['download_id']."&amp;mirr=1&amp;project=".$project."\" target=\"_blank\">Download</a></td>\n";
												echo "	<td width=\"60\" align=\"left\" valign=\"middle\">"; if ($ardown5['download_link2'] != ""){ echo "<a href=\"".$eden_cfg['url_edencms']."eden_download.php?id=".$ardown5['download_id']."&amp;mirr=2&amp;project=".$project."\" target=\"_blank\">Download&nbsp;2</a>"; } echo "</td>\n";
												echo "	<td width=\"60\" align=\"left\" valign=\"middle\">"; if ($ardown5['download_link3'] != ""){ echo "<a href=\"".$eden_cfg['url_edencms']."eden_download.php?id=".$ardown5['download_id']."&amp;mirr=3&amp;project=".$project."\" target=\"_blank\">Download&nbsp;3</a>"; } echo "</td>\n";
												echo "	<td width=\"70\" align=\"right\" valign=\"middle\">".number_format($ardown5['download_size'], 0, ',', ' ')." kB</td>\n";
												echo "</tr>\n";
												echo "<tr class=\"download\">\n";
												echo "	<td align=\"left\" valign=\"middle\" colspan=\"5\">"; if ($ardown5['download_img'] != ""){ echo "<img src=\"".$url_download.$ardown5['foto']."\" alt=\"\" border=\"0\" align=\"left\" hspace=\"5\" vspace=\"5\">"; } echo " ".$ardown5['download_description']."<br>"; if (AGet($_GET,'stav') == "open" && $_GET['did'] == $ardown5['download_id']){ echo "<a href=\"index.php?&amp;project=".$project."&amp;action=files&amp;stav=close\">"._CLOSE."</a>"; } else {echo _COMMENTS_DOWNLOAD.":<a href=\"index.php?did=".$ardown5['download_id']."&amp;project=".$project."&amp;action=files&amp;dl_id=".$ar['category_id']."&amp;dl_id2=".$ar2['category_id']."&amp;dl_id3=".$ar3['category_id']."&amp;dl_id4=".$ar4['category_id']."&amp;dl_id5=".$ar5['category_id']."&amp;dld=open&amp;dld2=open&amp;dld3=open&amp;dld4=open&amp;dld5=open&stav5=open#".$ardown5['download_id']."\"> <strong>".$numcom5[0]."</strong>&nbsp;&nbsp;"._ADD_COMMENTS."</a>"; }
														if ($ardown5['download_author_web'] != ""){echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;"; echo _WEB_AUTORA.": <a href=\"http://".$ardown5['download_author_web']."\" target=\"_blank\">".$ardown5['download_author_web']."</a>"; }
														if ($ardown5['download_num_download'] != "0"){echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;"; echo _NUM_DOWNLOAD.": "; echo "<strong>".$ardown5['download_num_download']."</strong>";}
											echo "	</td>";
											echo "</tr>";
											if (AGet($_GET,'stav5') == "open" && $_GET['did'] == $ardown5['download_id']){
												echo "<tr>";
												echo "	<td colspan=\"5\">"; Comments($ardown5['download_id'],"download"); echo "</td>";
												echo "</tr>";
											}
										}
										echo "		</table>";
										echo "	</td>";
										echo "</tr>"; 
									}
									//***********************************************************************************
									// Pate Podmenu
									//***********************************************************************************
									if ($cid5 == $ar5['category_id']){
											$res6 = mysql_query("SELECT category_id, category_name FROM $db_category WHERE category_parent=".(integer)$cid5." ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
											$e = 1;
											while ($ar6 = mysql_fetch_array($res6)){
												$resdown6 = mysql_query("SELECT * FROM $db_download WHERE download_category1=".(integer)$ar6['category_id']." OR download_category2=".(integer)$ar6['category_id']." OR download_category3=".(integer)$ar6['category_id']." OR download_category4=".(integer)$ar6['category_id']." OR download_category5=".(integer)$ar6['category_id']." ORDER BY download_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
												$numdown6 = mysql_num_rows($resdown6);
												$hlavicka6 = stripslashes($ar6['category_name']);
												$res06 = mysql_query("SELECT category_admin FROM $db_category WHERE category_id=".(integer)$ar6['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
												$ar06 = mysql_fetch_array($res06);
												$cid6 = $ar6['category_id'];
				 								echo "<tr>\n";	
												echo "	<td width=\"450\" align=\"left\" valign=\"middle\" class=\"download_nadpis\"><a name=\"".$ar6['category_id']."\"></a><a href=\"?action=files&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;dl_id=".$ar['category_id']."&amp;dl_id2=".$ar2['category_id']."&amp;dl_id3=".$ar3['category_id']."&amp;dl_id4=".$ar4['category_id']."&amp;dl_id5=".$ar5['category_id']."&amp;dld=close&amp;dld2=close&amp;dld3=close&amp;dld4=close&amp;dld5=close&amp;dl_id6=".$ar6['category_id']."&amp;dld6="; if (($_GET['dld6'] == "" || $_GET['dld6'] == "close") || $ar6['category_id'] != $_GET['dl_id6']){echo "open";} else {echo "close";} echo "#".$ar6['category_id']."\">"; /*$hlavicka." - ".*/ echo $hlavicka2." - ".$hlavicka3." - ".$hlavicka4." - ".$hlavicka5." - ".$hlavicka6." (<span class=\"download_nadpis_m\">"._DOWNLOAD_FILES_NUMBER.$numdown6."</span>)</td>\n";
												echo "	<td width=\"150\" align=\"right\" valign=\"middle\"><a href=\"?action=files&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;dl_id=".$ar['category_id']."&amp;dl_id2=".$ar2['category_id']."&amp;dl_id3=".$ar3['category_id']."&amp;dl_id4=".$ar4['category_id']."&amp;dl_id5=".$ar5['category_id']."&amp;dld=close&amp;dld2=close&amp;dld3=close&amp;dld4=close&amp;dld5=close&amp;dl_id6=".$ar6['category_id']."&amp;dld6="; if (($_GET['dld6'] == "" || $_GET['dld6'] == "close") || $ar6['category_id'] != $_GET['dl_id6']){echo "open";} else {echo "close";} echo "#".$ar6['category_id']."\">"; if ($_GET['dld6'] == "open" && $ar6['category_id'] == $_GET['dl_id6']){echo _DL_CLOSE;} else {echo _DL_OPEN;} echo "</a></td>\n";
												echo "</tr>"; 
											   	if ($_GET['dld6'] == "open" && $ar6['category_id'] == $_GET['dl_id6']){
													echo "	<tr>\n";
													echo "		<td colspan=\"2\">\n";
													echo "			<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\">";
													while($ardown6 = mysql_fetch_array($resdown6)){
														// Spocitame komentare
														$rescom6 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ardown6['download_id']." AND comment_modul='download'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
														$numcom6 = mysql_fetch_array($rescom6);
														echo "<tr class=\"download_nazev\">\n";
														echo "	<td width=\"460\" align=\"left\" valign=\"middle\"><strong>".$ardown6['download_name']."</strong><a name=\"#".$ardown6['download_id']."\"></a></td>\n";
														echo "	<td width=\"50\" align=\"left\" valign=\"middle\"><a href=\"".$eden_cfg['url_edencms']."eden_download.php?id=".$ardown6['download_id']."&amp;mirr=1&amp;project=".$project."\" target=\"_blank\">Download</a></td>\n";
														echo "	<td width=\"60\" align=\"left\" valign=\"middle\">"; if ($ardown6['download_link2'] != ""){ echo "<a href=\"".$eden_cfg['url_edencms']."eden_download.php?id=".$ardown6['download_id']."&amp;mirr=2&amp;project=".$project."\" target=\"_blank\">Download&nbsp;2</a>"; } echo "</td>\n";
														echo "	<td width=\"60\" align=\"left\" valign=\"middle\">"; if ($ardown6['download_link3'] != ""){ echo "<a href=\"".$eden_cfg['url_edencms']."eden_download.php?id=".$ardown6['download_id']."&amp;mirr=3&amp;project=".$project."\" target=\"_blank\">Download&nbsp;3</a>"; } echo "</td>\n";
														echo "	<td width=\"70\" align=\"right\" valign=\"middle\">".number_format($ardown6['download_size'], 0, ',', ' ')." kB</td>\n";
														echo "</tr>\n";
														echo "<tr class=\"download\">\n";
														echo "	<td align=\"left\" valign=\"middle\" colspan=\"5\">"; if ($ardown6['download_img'] != ""){ echo "<img src=\"".$url_download.$ardown6['download_img']."\" alt=\"\" border=\"0\" align=\"left\" hspace=\"5\" vspace=\"5\">"; } echo " ".$ardown6['download_description']."<br>"; if (AGet($_GET,'stav6') == "open" && $_GET['did'] == $ardown6['download_id']){ echo "<a href=\"index.php?&amp;project=".$project."&amp;action=files&stav6=close\">"._CLOSE."</a>"; } else {echo _COMMENTS_DOWNLOAD.":<a href=\"index.php?did=".$ardown6['download_id']."&amp;project=".$project."&amp;action=files&amp;dl_id=".$ar['category_id']."&amp;dl_id2=".$ar2['category_id']."&amp;dl_id3=".$ar3['category_id']."&amp;dl_id4=".$ar4['category_id']."&amp;dl_id5=".$ar5['category_id']."&amp;dl_id6=".$ar6['category_id']."&amp;dld=open&amp;dld2=open&amp;dld3=open&amp;dld4=open&amp;dld5=open&amp;dld6=open&stav6=open#".$ardown6['download_id']."\"> <strong>".$numcom6[0]."</strong>&nbsp;&nbsp;"._ADD_COMMENTS."</a>"; }
															  	if ($ardown6['download_author_web'] != ""){echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;"; echo _WEB_AUTORA.": <a href=\"http://".$ardown6['download_author_web']."\" target=\"_blank\">".$ardown6['download_author_web']."</a>"; }
															  	if ($ardown6['download_num_download'] != "0"){echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;"; echo _NUM_DOWNLOAD.": "; echo "<strong>".$ardown6['download_num_download']."</strong>";}
														echo "	</td>";
														echo "</tr>";
														if (AGet($_GET,'stav6') == "open" && $_GET['did'] == $ardown6['download_id']){
															echo "<tr>";
															echo "	<td colspan=\"5\">"; Comments($ardown6['download_id'],"download"); echo "</td>";
															echo "</tr>";
														}
													}
													echo "		</table>";
													echo "	</td>";
													echo "</tr>";
											   	}
												$e++;
											}
										}
										$d++;
									}
								}
								$c++;
							}
						}
				   		$b++;
					}
				}
				$a++;
			}
		}
		$i++;
	}
	echo "</table>";
}