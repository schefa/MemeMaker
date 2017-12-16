/*
* @ Revisted and edited by Schefa
*/

(function(global) {

	function capitalize(string) { return string.charAt(0).toUpperCase() + string.slice(1); }
	function pad(str, length) { while (str.length < length) { str = '0' + str; } return str; }
	
	var getRandomInt = fabric.util.getRandomInt;
	function getRandomColor() { return ( pad(getRandomInt(0, 255).toString(16), 2) + pad(getRandomInt(0, 255).toString(16), 2) + pad(getRandomInt(0, 255).toString(16), 2) ); }
	
	var supportsInputOfType = function(type) {
	  return function() {
		var el = document.createElement('input');
		try {
		  el.type = type;
		}
		catch(err) { }
		return el.type === type;
	  };
	};
	
	var supportsSlider = supportsInputOfType('range'), supportsColorpicker = supportsInputOfType('color');
	
	var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".gif", ".png"];
	function cEx(u) { var ret = false; for (var j = 0; j < _validFileExtensions.length; j++) { var sCurExtension = _validFileExtensions[j]; if (u.substr(u.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) { ret = true; break; } } return ret; }
	
	function convertImgToBase64(url, callback, outputFormat){
		var ca = document.createElement('CANVAS'); var ctx = ca.getContext('2d');
		var img = new Image;
		img.crossOrigin = 'Anonymous';
		img.onload = function(){
			var bimbo = rsifu(templateWidth, templateHeight,img.width,img.height);
			ca.height = bimbo[1]; ca.width = bimbo[0];
			ctx.drawImage(img,0,0,bimbo[0],bimbo[1]);
			var dataURL = ca.toDataURL(outputFormat || 'image/png');
			callback.call(this, dataURL); ca = null;
		};
		img.src = url;
	}
	
	function rsifu(maxW, maxH, currW, currH){ var ratio = currH / currW; if(currW >= maxW && ratio <= 1){ currW = maxW; currH = currW * ratio; } else if (currH >= maxH){ currH = maxH; currW = currH / ratio; } return [currW, currH]; }

	global._validFileExtensions = _validFileExtensions;
	global.rsifu = rsifu;
	global.convertImgToBase64 = convertImgToBase64;
	global.cEx = cEx;
	global.getRandomInt = getRandomInt;
	global.getRandomColor = getRandomColor;
	global.supportsSlider = supportsSlider;
	global.supportsColorpicker = supportsColorpicker;
	global.capitalize = capitalize;

})(this);
