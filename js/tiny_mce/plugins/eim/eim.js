var local_url = eden_cms_url;

var browser_url = local_url + "js/tiny_mce/plugins/eim/index.php?project=" + project + "&adminid=" + adminid + "&lang=" + lang;

var fieldName = null;
var wind = null;

function openEdenImageManager(field_name, url, type, win){
	
	wind = win;
	fieldName = field_name;

	var height = 550;
	var width = 850;

	var top=Math.round((screen.height-height)/2);
	var left=Math.round(screen.width/2);

	var params = "top="+top+",left="+left+",width="+width+",height="+height+",buttons=no,scrollbars=no,location=no,menubar=no,resizable=no,status=no,directories=no,toolbar=no";

	var wnd = window.open(browser_url , name,  params);
	
	wnd.focus();
}  
function insertURL(url){
	wind.document.getElementById(fieldName).value = url;
}