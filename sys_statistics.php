<?php
include "inc.header.php";
/* Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren */
KillUse($_SESSION['loginid']);
echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
echo "	<tr>\n";
echo "		<td valign=\"top\">\n";
echo "			<table width=\"99%\" cellspacing=\"1\" cellpadding=\"5\">\n";
echo "				<tr> \n";
echo "					<td width=\"95%\" class=\"nadpis\">"._STAT_STATISTICS."</td>\n";
echo "				</tr>\n";
echo "				<tr>\n";
echo "					<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">\n";
echo "						<a href=\"http://www.edenstat.cz?action=login_u&login=".$eden_cfg['modul_stat_id']."&pass=".$eden_cfg['modul_stat_pw']."\" target=\"_new\">"._STAT_SHOW."</a>\n";
echo "					</td>\n";
echo "				</tr>";
					/* Zobrazeni chyb a hlasek systemu */
					if ($_GET['msg']){		
						echo "<tr><td style=\"color:#ff0000;\">".SysMsg($_GET['msg'])."</td></tr>";
					}
echo "				<tr valign=\"top\">";
echo "					<td width=\"90%\" valign=\"top\" colspan=\"2\">";
echo "					<br>";
						$res1 = mysql_query("SELECT COUNT(*) FROM $db_articles WHERE article_public=0 AND article_publish=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar1 = mysql_fetch_array($res1);
						$res2 = mysql_query("SELECT COUNT(*) FROM $db_comments") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar2 = mysql_fetch_array($res2);
						$res3 = mysql_query("SELECT COUNT(*) FROM $db_poll_questions") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar3 = mysql_fetch_array($res3);
						$res4 = mysql_query("SELECT COUNT(*) FROM $db_poll_answers") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar4 = mysql_fetch_array($res4);
						$res5 = mysql_query("SELECT COUNT(*) FROM $db_articles WHERE article_public=1 AND article_publish=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar5 = mysql_fetch_array($res5);
						$res6 = mysql_query("SELECT COUNT(*) FROM $db_admin WHERE admin_reg_allow=1 OR admin_reg_allow=2 ") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar6 = mysql_fetch_array($res6);
						$res6_a = mysql_query("SELECT COUNT(*) FROM $db_admin WHERE admin_reg_allow=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar6_a = mysql_fetch_array($res6_a);
						$res7 = mysql_query("SELECT COUNT(*) FROM $db_clan_clanwars") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar7 = mysql_fetch_array($res7);
						$res8 = mysql_query("SELECT COUNT(*) FROM $db_download") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar8 = mysql_fetch_array($res8);
						$res9 = mysql_query("SELECT COUNT(*) FROM $db_news") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar9 = mysql_fetch_array($res9);
						/*
						$res10 = mysql_query("SELECT COUNT(*) FROM $db_ntb_porovnani") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar10 = mysql_fetch_array($res10);
						*/
						$res11 = mysql_query("SELECT COUNT(*) FROM $db_links") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar11 = mysql_fetch_array($res11);
						$res11_a = mysql_query("SELECT COUNT(*) FROM $db_links WHERE links_publish=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar11_a = mysql_fetch_array($res11_a);
						$res12 = mysql_query("SELECT COUNT(*) FROM $db_dictionary") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar12 = mysql_fetch_array($res12);
						$res12_a = mysql_query("SELECT COUNT(*) FROM $db_dictionary WHERE dictionary_allow=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar12_a = mysql_fetch_array($res12_a);
						
						if (empty($ar1[0])) {$ar1[0]=0;}
						if (empty($ar2[0])) {$ar2[0]=0;}
						if (empty($ar3[0])) {$ar3[0]=0;}
						if (empty($ar4[0])) {$ar4[0]=0;}
						if (empty($ar5[0])) {$ar5[0]=0;}
						if (empty($ar6[0])) {$ar6[0]=0;}
						if (empty($ar7[0])) {$ar7[0]=0;}
						if (empty($ar8[0])) {$ar8[0]=0;}
						if (empty($ar9[0])) {$ar9[0]=0;}
						if (empty($ar10[0])) {$ar10[0]=0;}
						if (empty($ar11[0])) {$ar11[0]=0;}
						if (empty($ar12[0])) {$ar12[0]=0;}
						
						$nfo = mysql_query("SELECT admin_info FROM $db_admin WHERE admin_id=".$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$inf = mysql_fetch_array($nfo);
						$lst = explode("#", $inf[0]);
echo "						<br>\n";
echo "						<table cellspacing=\"1\" cellpadding=\"5\" bgcolor=\"#DDDDDD\" align=\"center\">\n";
echo "							<tr bgcolor=\"#EEEEEE\">\n";
echo "								<td><strong>"._STAT_STATISTICS."</strong></td>\n";
echo "								<td><strong>"._STAT_NEWS_STATE."</strong></td>\n";
echo "								<td><strong>"._STAT_LASTVISIT."</strong></td>\n";
echo "								<td><strong>"._STAT_NEW."</strong></td>\n";
echo "								<td><strong>"._STAT_WAIT_FOR_ALLOW."</strong></td>\n";
echo "							</tr>\n";
								if ($eden_cfg['modul_news'] == "1") {
									echo "<tr bgcolor=\"#FFFFFF\">\n";
									echo "	<td>"._STAT_NEWS."</td>\n";
									echo "	<td align=\"right\"><strong>".$ar9[0]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>".$lst[8]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>"; echo $ar9[0] - $lst[8]; echo "</strong></td>\n";
									echo "	<td align=\"right\"><strong>&nbsp;</strong></td>\n";
									echo "</tr>\n";
								}
								if ($eden_cfg['modul_articles'] == "1") {
									echo "<tr bgcolor=\"#FFFFFF\">\n";
									echo "	<td>"._STAT_ARTICLES."</td>\n";
									echo "	<td align=\"right\"><strong>".$ar1[0]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>".$lst[0]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>"; echo $ar1[0] - $lst[0]; echo "</strong></td>\n";
									echo "	<td align=\"right\"><strong>&nbsp;</strong></td>\n";
									echo "</tr>";
								}
echo "							<tr bgcolor=\"#FFFFFF\">\n";
echo "								<td>"._STAT_COMMENTS."</td>\n";
echo "								<td align=\"right\"><strong>".$ar2[0]."</strong></td>\n";
echo "								<td align=\"right\"><strong>".$lst[1]."</strong></td>\n";
echo "								<td align=\"right\"><strong>"; echo $ar2[0] - $lst[1]; echo "</strong></td>\n";
echo "								<td align=\"right\"><strong>&nbsp;</strong></td>\n";
echo "							</tr>\n";
								if ($eden_cfg['modul_poll'] == "1") {
									echo "<tr bgcolor=\"#FFFFFF\">\n";
									echo "	<td>"._STAT_WEEKLYPOLLQ."</td>\n";
									echo "	<td align=\"right\"><strong>".$ar3[0]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>".$lst[2]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>"; echo $ar3[0] - $lst[2]; echo "</strong></td>\n";
									echo "	<td align=\"right\"><strong>&nbsp;</strong></td>\n";
									echo "</tr>\n";
									echo "<tr bgcolor=\"#FFFFFF\">\n";
									echo "	<td>"._STAT_WEEKLYPOLLA."</td>\n";
									echo "	<td align=\"right\"><strong>".$ar4[0]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>".$lst[3]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>"; echo $ar4[0] - $lst[3]; echo "</strong></td>\n";
									echo "	<td align=\"right\"><strong>&nbsp;</strong></td>\n";
									echo "</tr>\n";
								}
								if ($eden_cfg['modul_articles_public'] == "1") {
									echo "<tr bgcolor=\"#FFFFFF\">\n";
									echo "	<td>"._STAT_PUBLIC_ARTICLES."</td>\n";
									echo "	<td align=\"right\"><strong>".$ar5[0]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>".$lst[4]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>"; echo $ar5[0] - $lst[4]; echo "</strong></td>\n";
									echo "	<td align=\"right\"><strong>&nbsp;</strong></td>\n";
									echo "</tr>\n";
								}
echo "							<tr bgcolor=\"#FFFFFF\">\n";
echo "								<td>"._STAT_REGISTEREDUSERS."</td>\n";
echo "								<td align=\"right\"><strong>".$ar6[0]."</strong></td>\n";
echo "								<td align=\"right\"><strong>".$lst[5]."</strong></td>\n";
echo "								<td align=\"right\"><strong>"; echo $ar6[0] - $lst[5]; echo "</strong></td>\n";
echo "								<td align=\"right\"><strong>".$ar6_a[0]."</strong></td>\n";
echo "							</tr>\n";
								if ($eden_cfg['modul_clanwars'] == "1") {
									echo "<tr bgcolor=\"#FFFFFF\">\n";
									echo "	<td>"._STAT_CLAN_CLANWARS."</td>\n";
									echo "	<td align=\"right\"><strong>".$ar7[0]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>".$lst[6]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>"; echo $ar7[0] - $lst[6]; echo "</strong></td>\n";
									echo "	<td align=\"right\"><strong>&nbsp;</strong></td>\n";
									echo "</tr>\n";
								}
								if ($eden_cfg['modul_download'] == "1") {
									echo "<tr bgcolor=\"#FFFFFF\">\n";
									echo "	<td>"._STAT_DOWNLOAD."</td>\n";
									echo "	<td align=\"right\"><strong>".$ar8[0]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>".$lst[7]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>"; echo $ar8[0] - $lst[7]; echo "</strong></td>\n";
									echo "	<td align=\"right\"><strong>&nbsp;</strong></td>\n";
									echo "</tr>\n";
								}
								if ($eden_cfg['modul_ntb'] == "1") {
									echo "<tr bgcolor=\"#FFFFFF\">\n";
									echo "	<td>"._STAT_NTB."</td>\n";
									echo "	<td align=\"right\"><strong>".$ar10[0]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>".$lst[9]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>"; echo $ar10[0] - $lst[9]; echo "</strong></td>\n";
									echo "	<td align=\"right\"><strong>&nbsp;</strong></td>\n";
									echo "</tr>\n";
								}
								if ($eden_cfg['modul_links'] == "1") {
									echo "<tr bgcolor=\"#FFFFFF\">\n";
									echo "	<td>"._STAT_LINKS."</td>\n";
									echo "	<td align=\"right\"><strong>".$ar11[0]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>".$lst[10]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>"; echo $ar11[0] - $lst[10]; echo "</strong></td>\n";
									echo "	<td align=\"right\"><strong>".$ar11_a[0]."</strong></td>\n";
									echo "</tr>\n";
								}
								if ($eden_cfg['modul_dictionary'] == "1") {
									echo "<tr bgcolor=\"#FFFFFF\">\n";
									echo "	<td>"._STAT_DICTIONARY."</td>\n";
									echo "	<td align=\"right\"><strong>".$ar12[0]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>".$lst[11]."</strong></td>\n";
									echo "	<td align=\"right\"><strong>"; echo $ar12[0] - $lst[11]; echo "</strong></td>\n";
									echo "	<td align=\"right\"><strong>".$ar12_a[0]."</strong></td>\n";
									echo "</tr>\n";
								}
echo "						</table>\n";
echo "						<br>\n";
echo "					</td>\n";
echo "				</tr>\n";
echo "			</table>\n";
				mysql_query("UPDATE $db_admin SET admin_info='".$ar1[0]."#".$ar2[0]."#".$ar3[0]."#".$ar4[0]."#".$ar5[0]."#".$ar6[0]."#".$ar7[0]."#".$ar8[0]."#".$ar9[0]."#".$ar10[0]."#".$ar11[0]."#".$ar12[0]."' WHERE admin_id=".$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
echo "		</td>\n";
echo "	</tr>\n";
echo "</table>";
include "inc.footer.php";