<?php
/*Tento skript zobrazuje v novem okne obrazek, na ktery vede JS odkaz
<a href="#" onmouseover="className='editor_o'" onclick="window.open('eden_show_images.php?&path=$path_images.$images[$i]','','menubar=no,resizable=no,toolbar=no,status=no,width=$size[0],height=$size[1]')"></a>
*/
$eden_cfg['www_dir'] = dirname(__FILE__);
require_once($eden_cfg['www_dir']."/eden_init.php");


$_GET['img'] = AGet($_GET,'img');
$_GET['lang'] = AGet($_GET,'lang');
$_GET['mode'] = AGet($_GET,'mode');
$_GET['project'] = AGet($_GET,'project');
$_POST['project'] = AGet($_POST,'project');

if ($_GET['project'] != ""){$project = $_GET['project'];} elseif ($_POST['project'] != ""){$project = $_POST['project'];} else {$project = $_SESSION['project'];}
require_once("db.".$project.".inc.php");

if ($_GET['mode'] == "scr"){
	$path = $url_screenshots;
	$title = "Clanwar Screenshots";
}else{
	$title = "Image";
}
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
echo "<html>\n";
echo "<head>\n";
echo "	<title>".$title."</title>\n";
echo "	<link rel=\"stylesheet\" href=\"eden-common.css\" type=text/css>\n";
echo "	<meta http-equiv=\"content-type\" content=\"text/htm; charset=UTF-8\">\n";
echo "	<meta http-equiv=\"content-language\" content=\"".$_GET['lang']."\">\n";
echo "<script language=\"javascript\" type=\"text/javascript\">\n";
echo "<!--\n";
echo "	function winclose() {\n";
echo "   window.close();\n";
echo "}\n";
echo "// -->\n";
echo "</script>\n";
echo "\n";
echo "</head>\n";
echo "<body leftmargin=\"0\" rightmargin=\"0\" marginheight=\"0\" marginwidth=\"0\" topmargin=\"0\"  bottommargin=\"0\">\n";
echo "<a href=\"#\" onclick=\"winclose()\"><img src=\"".$path.urlencode($_GET['img'])."\" border=\"0\" alt=\"\" "; if (isset($imgwidth)){ echo "width=".$imgwidth;} if (isset($imgheight)){ echo "width=".$imgheight;} echo "></a>\n";
echo "</body>\n";
echo "</html>";