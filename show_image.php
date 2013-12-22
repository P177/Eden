<?php
include (dirname(__FILE__)."/functions.php");
include (dirname(__FILE__)."/lang/lang-".$_SESSION['lang'].".php");

if (!isset($project)) {
  echo _ERRORPRISTUP;
} else {
  $res = mysql_query("SELECT * FROM $db_articles_pictures WHERE articles_images_article_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
  $num = mysql_num_rows($res); // Kazdy radek je jeden obrazek k danemu clanku
  $res2 = mysql_query("SELECT * FROM $db_articles WHERE article_id='$id'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
  $ar2 = mysql_fetch_array($res2); // Nacteni daneho clanku do pole

  if ($send == "true") { // Jesltlize je odeslan pozadavek na smazani obrazku
    
    // Pripojime se na FTP server
    $conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP);
    $login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	
    if ((!$conn_id) || (!$login_result)) {
        echo _ERROR_FTP;
        die;
     }
    
    // Zobrazime info o smazanych obrazcich
    echo _ARTICLES_DEL_PIC;
    echo "<br><br>";
    
    $z = 0;
    while ($z < $num) { // Kdyz je $z mensi nes pocet obrazku v danem clanku
      echo $del[$z];
      echo "<br>";
        echo $image[$z]; // Vypise se jeho nazev
        echo "<br>";
        @ftp_delete($conn_id, $ftp_path_articles.$image[$z]); // Odstrani se z FTP serveru
        mysql_query("DELETE FROM $db_articles_pictures WHERE article_img_1s_picture='$del[$z]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Odstrani se ze zaznamu v databazi
    $z++; // Pricte se 1 aby mohlo cele kolo pokracovat
    }
  exit; // Po probehnuti vsech operaci se ukonci skript
  }
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
echo "  <head>\n";
echo "  <title>"._ARTICLES_IMAGES."</title>\n";
echo "  <LINK REL=\"stylesheet\" type=\"text/css\" HREF=\"novinky.css\">\n";
echo "  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
echo "  </head>\n";
echo "  <body style=\"background: threedface; color: windowtext;\">\n";
echo "  <table width=\"600\" align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "  <form action=\"".$PHP_SELF."\" method=\"post\">\n";
echo "  <tr>\n";
echo "    <td colspan=\"2\"><input type=\"hidden\" name=\"send\" value=\"true\">\n";
echo "      <input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
echo "      <input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
echo "      <input type=\"submit\" value=\""._ARTICLES_MANAGE_PIC_DEL."\" class=\"eden_button\"><img src=\"images/a_bod.gif\" width=\"50\" height=\"10\" border=\"0\" alt=\"\">\n";
echo "      <button type=reset onClick=\"window.close();\">"._CMN_CANCEL."</button>\n";
echo "    </td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td width=\"600\" height=\"10\" colspan=\"2\">&nbsp;</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td width=\"600\" height=\"10\" colspan=\"2\">"._ARTICLES_IMAGE_NUM.$num."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td width=\"600\" height=\"10\" colspan=\"2\">&nbsp;</td>\n";
echo "  </tr>"; 
while($ar = mysql_fetch_array($res)){
  echo "    <tr>";
  echo "      <td width=\"20\"><input type=\"checkbox\" name=\"del[]\" value=\"".$ar['picture']."\"></td>";
  echo "      <td><img src=\"".$url_images.$ar['article_img_1s_picture']."\" border=\"1\"><br>";
                if (preg_match ("/(".$ar['article_img_1s_picture'].")/i", $ar2["article_text"], $regs)) { // Porovna se jestli je obrazek v clanku
                    echo "<strong>".$regs[0]."</storng>"; // Pokud ano zvyrazni se tucne
                }elseif (preg_match ("/(".$ar['article_img_1s_picture'].")/", $ar2['article_perex'], $regs)){ // Porovna se jestli je obrazek v nahledu
                  echo "<strong>".$regs[0]."</storng>"; // Pokud ano zvyrazni se tucne
                } else {
                    echo $ar["article_img_1s_picture"]; // Pokud ne tak normalne
                }
  echo "      </td>\n";
  echo "    </tr>\n";
  echo "    <tr>\n";
  echo "      <td width=\"600\" height=\"10\" colspan=\"2\">\n";
  echo "      </td>\n";
  echo "    </tr>";
}
echo "    <tr>\n";
echo "      <td colspan=\"2\"></form></td>\n";
echo "    </tr>\n";
echo "  </table>\n";
echo "</body>\n";
echo "</html>";
}