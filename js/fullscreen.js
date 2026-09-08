function fullscreen(url,windowname,width,height) {

	var isWin=(navigator.appVersion.indexOf("Win")!=-1)? true : false;
	var isIE=(navigator.appVersion.indexOf("MSIE")!=-1)? true : false;
	var notIE7 = (navigator.appVersion.indexOf("MSIE 7.0")!=-1)? false : true;
	var windowwidth =screen.availWidth;
	var windowheight =screen.availheight;
	
	if (width != null && height != null) {
		windowwidth = width;windowheight =height;
		isWin = false;
	}


	//if(isWin&&isIE&&notIE7){
		var fullwindow = window.open(url,windowname,"fullscreen=1, scrollbars=1, titlebar=0");
		fullwindow.focus();
	//}else{
	 //for non-ie browsers, specify width and height instead of using fullscreen 
		//var fullwindow = window.open(url,windowname,"width="+windowwidth +",height="+windowheight);
		//fullwindow.moveTo(0,0);
		//fullwindow.focus();
		//var fullwindow = window.open(url,windowname,"fullscreen=1, scrollbars=1");
		//fullwindow.focus();
	//}

}