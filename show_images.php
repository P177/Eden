<?php
/*Tento skript zobrazuje v novem okne obrazek, na ktery vede JS odkaz
<a href="#" onmouseover="className='editor_o'" onclick="window.open('eden_show_images.php?&path=$path_images.$images[$i]','','menubar=no,resizable=no,toolbar=no,status=no,width=$size[0],height=$size[1]')"></a>
*/
extract ($HTTP_POST_VARS, EXTR_PREFIX_SAME,"wddx");
extract ($HTTP_GET_VARS, EXTR_PREFIX_SAME,"wddx");
if ($title == ""){$title = "EDEN - IMAGES";}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
	<TITLE><?php echo $title;?></TITLE>
	<LINK REL="StyleSheet" HREF="dh.css" TYPE=TEXT/CSS>
	<META HTTP-EQUIV="content-type" CONTENT="text/htm; charset=UTF-8">
	<META HTTP-EQUIV="content-language" CONTENT="<?php echo $lang;?>">
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
<!--
	function winclose() {
   window.close();
}
// -->
</SCRIPT>

</HEAD>
<BODY LEFTMARGIN="0" RIGHTMARGIN="0" MARGINHEIGHT="0" MARGINWIDTH="0" TOPMARGIN="0"  BOTTOMMARGIN="0">
<a href="#" onclick="winclose()"><img src="<?php echo $path;?>" border="0" alt=""></a>
</BODY>
</HTML>
<?php
?>