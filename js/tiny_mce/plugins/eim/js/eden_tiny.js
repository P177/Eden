//ř
function eimNewFileSelector(cnt) {
	var f = document.forms.NewImgFile;
	if(f && f['ImgFile['+cnt+']']) f['ImgFile['+cnt+']'].style.display = 'block';
}
function eimNewFileDeSelector() {
	var f = document.forms.NewImgFile;
	for (i=1;i<10;i++){
		f['ImgFile['+i+']'].style.display = 'none';
	}
}