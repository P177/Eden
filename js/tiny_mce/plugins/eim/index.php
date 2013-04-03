<?php
$eden_editor_add_include_lang = true;
$eden_editor_tinymce_plugin_path = "../../../..";
if ($_GET['lang'] != ""){$lang = $_GET['lang'];} elseif ($_POST['lang'] != ""){$lang = $_POST['lang'];} else {$lang = "cz";}
if ($_GET['project'] != ""){$project = $_GET['project'];} elseif ($_POST['project'] != ""){$project = $_POST['project'];} else {$project = "";}
if ($_GET['adminid'] != ""){$adminid = $_GET['adminid'];} elseif ($_POST['adminid'] != ""){$adminid = $_POST['adminid'];} else {$adminid = "";}
if ($_GET['img_mode'] != ""){$img_mode = $_GET['img_mode'];} elseif ($_POST['img_mode'] != ""){$img_mode = $_POST['img_mode'];} else {$img_mode = "usr";}
include_once($eden_editor_tinymce_plugin_path."/sessions.php");
include_once($eden_editor_tinymce_plugin_path."/cfg/db.".$project.".inc.php");
include_once($eden_editor_tinymce_plugin_path."/functions.php");
include_once($eden_editor_tinymce_plugin_path."/lang/lang-".$lang.".php");
// Zkontrolujeme zda je dany adresar vytvoren
$ftp_array = array(
	array($eden_cfg['ftp_images_usr'],$adminid."/"),
	array($eden_cfg['ftp_images_usr'].$adminid."/","_thumb/")
);
FtpCheckDirArray($ftp_array);
$res_admin = mysql_query("SELECT admin_nick FROM $db_admin WHERE admin_id=".(integer)$adminid."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar_admin = mysql_fetch_array($res_admin);

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n";
echo "<html>\n";
echo "	<head>\n";
echo "		<title>EDEN Image Manager</title>\n";
echo "		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
echo "		<script type=\"text/javascript\" src=\"./js/jquery.js\"></script>\n";
echo "		<script type=\"text/javascript\" src=\"./js/jquery-ui.js\"></script>\n";
echo "		<script type=\"text/javascript\" src=\"./js/eden_tiny.js\"></script>\n";
echo "		<script type=\"text/javascript\" src=\"./js/jquery.form.js\"></script>\n";
echo "		<style type=\"text/css\">\n";
echo "			body, td { font-size:11px;font-family:Trebuchet MS, Verdana CE, Verdana, Geneva CE, Geneva, Arial CE, Arial, Helvetica CE, Helvetica, sans-serif;}\n";
echo "			.refresh{ margin:0px 0px 0px 0px }\n";
echo "			#eimNewFile{ position: absolute; width: 260px; background-color: #f0f0ee;visibility:hidden;}\n";
echo "			.eimTH1{ margin:4px;width:16px;height:14px;cursor:pointer;text-align:center;background:#0a246a url(icons/close_0.gif) no-repeat; }\n";
echo "			a {text-decoration:none;color:#0000ff;}\n";
echo "			a:hover {text-decoration:underline;color:#0000ff;}\n";
echo "			a:visited {text-decoration:none;color:#0000ff;}\n";
echo "		</style>\n";
echo "	</head>\n";
echo "	<body style=\"background-color:#F0F0F0\">";?>
	<script language="JavaScript" type="text/javascript">
		/* User ID */
		var img_usr = "<?php echo $adminid;?>";
		var url_to_php = "<?php echo $eden_cfg['url_cms'];?>js/tiny_mce/plugins/eim/zobraz.php?project=<?php echo $project;?>&lang=<?php echo $lang;?>&adminid=<?php echo $adminid;?>&mode=";
		// Ziskame z editoru
		$(document).ready(function(){
 			/* Solve IE cache problem */
			$.ajaxSetup({cache: false});
			
			function f_refresh(){
				$("#okno").load(url_to_php+"show_list&img_mode="+img_mode+"&img_usr="+img_usr);
				$("#eimNewFile").css('visibility','hidden').fadeOut(200);
				$('#eimForm').resetForm();
				eimNewFileDeSelector();
			}
			
			function eim_usr(){
				$("#okno").load(url_to_php+"show_list&img_mode=usr&img_usr="+img_usr);
				$("#load_main").css('font-weight','normal');
				$("#load_usr").css('font-weight','bold');
			}
			
			function eim_main(){
				$("#okno").load(url_to_php+"show_list&img_mode=main&img_usr="+img_usr);
				$("#load_usr").css('font-weight','normal');
				$("#load_main").css('font-weight','bold');
			}
			
			/* Load images list */
			if ('<?php echo $img_mode;?>' == 'usr'){
				eim_usr();
			} else {
				eim_main();
			}
			/* Refresh images list */
			$(".refresh").click(function(){
				// img_mode ziskame z php scriptu
				$("#okno").load(url_to_php+"show_list&img_mode="+img_mode+"&img_usr="+img_usr);
			});
			
			
			/* Load main (shared) images list */
			$("#load_main").click(function(){
			   eim_main();
			});
			
			/* Load user images list (based on User ID) */
			$("#load_usr").click(function(){
			   eim_usr();
			});
			
			/* Make file upload dialog appeared under mouse pointer */
			$("#add_img").click(function(e){
				var leftVal = e.pageX-155 + "px";
			    var topVal = e.pageY-10 + "px";
				var options = { 
			        success: f_refresh,
					data: { mode: img_mode, imgusr: img_usr},
			        clearForm: true,
			        resetForm: true
			    };
				// bind form using 'ajaxForm' 
		    	$('#eimForm').ajaxForm(options);
			    $("#eimNewFile").css({'left' : leftVal,'top' : topVal,'visibility' : 'visible'}).fadeIn(200);
			});
			/* Change image when click on X to close file upload dialog & make it fade out */
			$(".eimTH1").mouseup(function(){
		    	$(this).css('background-image','url(icons/close_0.gif)');
		    }).mousedown(function(){
		    	$(this).css('background-image','url(icons/close_1.gif)');
		    }).click(function(){
				$("#eimNewFile").css('visibility','hidden').fadeOut(200);
				$('#eimForm').resetForm();
				eimNewFileDeSelector();
			});
						
			/* Make file upload dialog draggable */
			$("#eimNewFile").draggable();
		});
</script>
<?php
if ($_GET['mode'] == "img_del" && $_POST['confirm'] == 1){
	/* Spojeni s FTP serverem */
	$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP); 
	/* Prihlaseni pres uzivatelske jmeno a heslo na FTP server */
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	
	/* Zjisteni stavu spojeni */
	if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP; die;}
	
	$num_images_to_del = count($_POST['img_to_del']);
	$i = 0;
	$y = 1;
	$query_to_del = "";
	while ($num_images_to_del >= $y){
		if ($y == 1){$or = "";} else {$or = " OR ";}
		$query_to_del .= $or." admin_image_id=".(integer)$_POST['img_to_del'][$i]." ";
		$i++;
		$y++;
	}
	if (!empty($query_to_del)){
		$res_images = mysql_query("SELECT * FROM $db_admin_images WHERE $query_to_del AND aid=".(integer)$adminid."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	 	while ($ar_images = mysql_fetch_array($res_images)){
			if($_POST['img_mode'] == "main"){
				$ftp_path_images = $ftp_path_images_main;
			} else {
				$ftp_path_images = $ftp_path_images_usr.(integer)$adminid;
			}
			ftp_delete($conn_id, $ftp_path_images."/".$ar_images['admin_image_name']);
			ftp_delete($conn_id, $ftp_path_images."/_thumb/".$ar_images['admin_image_name']);
			mysql_query("DELETE FROM $db_admin_images WHERE admin_image_id=".(integer)$ar_images['admin_image_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	   }
	}
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	echo "alert('"._EIM_IMAGES_DELETED.": ".$num_images_to_del."');";
	echo "</script>";
	unset($_POST['img_to_del']);
	unset($_POST['confirm']);
}
echo "	<div style=\"width:810px;height:523px;text-align:left;margin:auto;background-color:#ffffff;border:1px solid #000000;\">\n";
echo "		<div style=\"width:795px;height:17px;padding:3px 0px 0px 15px;\"><!--<a href=\"#\" class=\"refresh\">Refresh</a>&nbsp;&nbsp;|&nbsp;&nbsp;--><a href=\"#\" id=\"load_main\">"._EIM_SHARED_IMG."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
echo "			<a href=\"#\" id=\"load_usr\" style=\"font-weight:bold;\">".$ar_admin['admin_nick']."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
echo "			<a href=\"#\" id=\"add_img\">"._EIM_ADD_IMAGES."</a></div>\n";
echo "		<div style=\"width:810px;height:500px;overflow:auto;background-color:#ffffff;border-top:1px solid #000000;margin:0px 0px 0px 0px;\">\n";
echo "			<div id=\"okno\">\n";
echo "			</div>\n";
echo "		</div>\n";
echo "	</div>\n";
echo " 	<div id=\"eimNewFile\">\n";
echo "		<form action=\"zobraz.php?mode=img_upl\" name=\"NewImgFile\" id=\"eimForm\" method=\"post\" enctype=\"multipart/form-data\">\n";
echo "		<input type=\"hidden\" name=\"eimMode\" value=\"newFile\">\n";
echo "		<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"border:1px solid #000000;\">\n";
echo "			<tr>\n";
echo "				<td style=\"padding:4px;width:234px;cursor:move;background-color:#0a246a;font-weight:bold;color:#ffffff;\" align=\"left\" nowrap=\"nowrap\">"._EIM_ADD_IMAGE."</td>\n";
echo "				<td style=\"width:20px;height:18px;text-align:center;background:#0a246a;\"><div class=\"eimTH1\"></div></td>\n";
echo "			</tr>\n";
echo "			<tr>\n";
echo "				<td class=\"eimTH3\" colspan=\"2\" align=\"center\" style=\"padding:4px;background-color:#e0f0ff;\">\n";
echo "					<input type=\"file\" name=\"ImgFile[0]\" size=\"20\" class=\"eimField\" onClick=\"eimNewFileSelector(1)\" onChange=\"eimNewFileSelector(1)\">\n";
echo "					<input type=\"file\" name=\"ImgFile[1]\" size=\"20\" class=\"eimField\" onClick=\"eimNewFileSelector(2)\" onChange=\"eimNewFileSelector(2)\" style=\"display:none\">\n";
echo "					<input type=\"file\" name=\"ImgFile[2]\" size=\"20\" class=\"eimField\" onClick=\"eimNewFileSelector(3)\" onChange=\"eimNewFileSelector(3)\" style=\"display:none\">\n";
echo "					<input type=\"file\" name=\"ImgFile[3]\" size=\"20\" class=\"eimField\" onClick=\"eimNewFileSelector(4)\" onChange=\"eimNewFileSelector(4)\" style=\"display:none\">\n";
echo "					<input type=\"file\" name=\"ImgFile[4]\" size=\"20\" class=\"eimField\" onClick=\"eimNewFileSelector(5)\" onChange=\"eimNewFileSelector(5)\" style=\"display:none\">\n";
echo "					<input type=\"file\" name=\"ImgFile[5]\" size=\"20\" class=\"eimField\" onClick=\"eimNewFileSelector(6)\" onChange=\"eimNewFileSelector(6)\" style=\"display:none\">\n";
echo "					<input type=\"file\" name=\"ImgFile[6]\" size=\"20\" class=\"eimField\" onClick=\"eimNewFileSelector(7)\" onChange=\"eimNewFileSelector(7)\" style=\"display:none\">\n";
echo "					<input type=\"file\" name=\"ImgFile[7]\" size=\"20\" class=\"eimField\" onClick=\"eimNewFileSelector(8)\" onChange=\"eimNewFileSelector(8)\" style=\"display:none\">\n";
echo "					<input type=\"file\" name=\"ImgFile[8]\" size=\"20\" class=\"eimField\" onClick=\"eimNewFileSelector(9)\" onChange=\"eimNewFileSelector(9)\" style=\"display:none\">\n";
echo "					<input type=\"file\" name=\"ImgFile[9]\" size=\"20\" class=\"eimField\" style=\"display:none\">\n";
echo "					<div class=\"eimTH3\" style=\"font-weight:normal; text-align:left; border:none\">\n";
echo "						<input type=\"radio\" name=\"eim_img_size\" value=\"0\" checked>\n";
echo 						_EIM_ORIGINAL."<br>\n";
echo "						<input type=\"radio\" name=\"eim_img_size\" value=\"1\">\n";
echo 						_EIM_PROPORTIONAL."<br>\n";
echo "						<table border=\"0\">\n";
echo "							<tr>\n";
echo "						   		<td>"._EIM_MAX_WIDTH.":</td> \n";
echo "								<td><input type=\"text\" name=\"eim_max_width\" value=\"600\" size=\"3\" maxlength=\"4\"> px</td>\n";
echo "							</tr>\n";
echo "							<tr>\n";
echo "						   		<td>"._EIM_MAX_HEIGHT.":</td>\n";
echo "								<td><input type=\"text\" name=\"eim_max_height\" value=\"600\" size=\"3\" maxlength=\"4\"> px</td>\n";
echo "							</tr>\n";
echo "						</table>\n";
echo "					</div>\n";
echo "					<input type=\"hidden\" name=\"project\" value=\"".$project."\">\n";
echo "					<input type=\"hidden\" name=\"adminid\" value=\"".$adminid."\">\n";
echo "					<input type=\"hidden\" name=\"lang\" value=\"".$lang.">\">\n";
echo "					<input type=\"submit\" class=\"button\" value=\""._EIM_UPLOAD."\">\n";
echo "				</td>\n";
echo "			</tr>\n";
echo "		</table>\n";
echo "		</form>\n";
echo "	</div>\n";
echo "	</body>\n";
echo "</html>";