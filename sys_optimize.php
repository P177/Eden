<?php
include ("inc.header.php");
// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
KillUse($_SESSION['loginid']);
echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
echo "	<tr>\n";
echo "		<td width=\"20\"><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\"></td>\n";
echo "		<td>"._OPTIMIZEDATABASE."</a></td>\n";
echo "	</tr>\n";
echo "</table>\n";
echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
echo "	<tr>\n";
echo "		<td>\n";
				$result = mysql_list_tables($eden_cfg['db_name']);
				while ($row = mysql_fetch_row($result)){
					$result1 = mysql_query("OPTIMIZE table ".$row[0]);
					echo "<li><strong>".$row[0]."</strong> "._OPTIMIZED."<br></li>\n";
				}
			  	mysql_free_result($result);
echo "		</td>\n";
echo "	</tr>\n";
echo "</table>\n";
include ("inc.footer.php");