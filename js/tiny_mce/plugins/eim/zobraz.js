var dataSource; 
function getHTTPObject() { 
	request = false; 
	if (window.ActiveXObject){
		request = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		request = new XMLHttpRequest();
	}
	return request; 
} 
function zobraz() {
	dataSource = getHTTPObject(); 
	var url = "zobraz.php";
	var rnd = new String(Math.random());
	var c_url = new String(url); 
	dataSource.open("GET", url+(c_url.search(/\?/gi)==-1?'?':'&')+'rnd='+rnd.substr(2),true); 
	dataSource.onreadystatechange = pozdrav;
	dataSource.send(null); 
	return true; 
} 
function pozdrav() { 
	if (dataSource.readyState == 4) { 
		var okno = document.getElementById('okno');
		okno.innerHTML = dataSource.responseText;
	} 
} 