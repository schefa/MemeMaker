<?php

if(!defined('_EXEC')) {
    die();
}

?>
 
<script type="text/javascript">

const _browserWarningText ="<?php echo Language::_('browser_warning') ?>";
const _textfieldText = "<?php echo Language::_('new_textfield') ?>";
var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".gif", ".png"];
var canvas = new fabric.Canvas('canvas');
 
var fonsize = 37;
var cellpadding = 4;
var templateWidth = <?php echo $width; ?>;
var templateHeight = <?php echo $height; ?>;
var xOffset = 30;
var yOffset = 30;
var verseColor = '#fff';
var noteColor = '#999';
var checkExt = false;
var imageSrc = "<?php echo $bgimageResult; ?>";
var lastId = 0;

var getLayerLastID = function() {
	var objs = canvas.getObjects();
	var result = 0;
	if (typeof(objs) !== 'undefined' && objs.length > 0) {
		for (var i = 0; i < objs.length; i++) {
			if(objs[i].itemId > result)
				result = objs[i].itemId;
		}
	}
	return result;
};

jQuery.noConflict();
jQuery(document).ready(function($) {
	
    function addBgImage( imageSrc ) { 
    	if( imageSrc !== '' ) { 
    		fabric.Image.fromURL(imageSrc, function(img) { 
    			var oImg = img.set({ 
    				itemId : getLayerLastID() + 1, left: 0, top: 0, padding: cellpadding, hasRotatingPoint : false,opacity : 0.65,
    			    transparentCorners: false
    			});
    			canvas.add(oImg).renderAll();
    			canvas.sendToBack(oImg);
    			canvas.setActiveObject(oImg);
			});
		};
		return false;
	};

	$('.mm-sample-gallery li').click(function(){
		$('.mm-sample-gallery li').removeClass(); $(this).addClass('active');
		var va = $(this).children('img').attr('src');
		var fullimage = va.replace("thumbs\/","");
		$("#mm-bgimage").val(fullimage);
		setBgImage(fullimage);
	});
	
	$("#mm-bgimage-load").on('click', function(event) {
		event.preventDefault();
		var value = $("#mm-bgimage").val();
		if(value.length > 0) setBgImage(value);
	});
	
	var setBgImage = function(source) {
		for (var i = 0; i < canvas.getObjects().length; i++) { if((canvas.item(i).id == "imageSrc")) { canvas.remove(canvas.item(i)).renderAll(); } }
		var blnValid = false;
		var value = source;
		blnValid = cEx(value);
		console.log(blnValid);
		if(value !== '') {
			if (!blnValid) { 
				alert("Sorry, " + value + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
				$("#mm-bgimage").val(''); value = ""; return false;
			} 
			if(blnValid) {
				var start = Date.now();
				$('.mm-bgimage-loading').addClass('formLogLoading');
				convertImgToBase64(value, function(base64Img){
					$('.output').find('textarea').val(base64Img).end();
					addBgImage(base64Img);
					if(base64Img !== '') { $('.mm-bgimage-loading').removeClass('formLogLoading'); } 
					imageSrc = base64Img;
				});
			}
		} else {
			$("#mm-bgimage").val(''); return false;
		}
	};
 
	$('#cv-reset').on('click', function() {
		canvas.clear();
		addTexts(templateWidth, templateHeight);
		addBgImage(imageSrc);
		canvas.renderAll();
	}); 

	$("#meme_format").on('change', function(event) {
		event.preventDefault();
		value = parseInt($(this).val());
		var width, height ;
		switch (value) {
			default : case 1 : width = 640; height = 640; break;
			case 2 : width = 640; height = 320; break;
			case 3 : width = 320; height = 640; break;	
			case 4 : width = 640; height = 480; break;	
			case 5 : width = 640; height = 360; break;	
		};
		templateWidth = width; templateHeight = height;

		var json = JSON.stringify(canvas);
		$('.canvas-container').width(width).height(height).attr('width', width).attr('height', height);
		$('canvas').width(width).height(height).attr('width', width).attr('height', height);
		$('.mm-layer-hint').width(width);

		canvas.clear();
		canvas.setWidth(width);
		canvas.setHeight(height);
		canvas.loadFromJSON(json, canvas.renderAll.bind(canvas));
		canvas.renderAll();
	});

	var textbox_text_object	= new fabric.IText('<?php echo $text; ?>', {
		left: xOffset,
		top: yOffset,
		fontSize : fonsize,
		fontFamily: 'Oswald', fill : verseColor, textAlign : "left"
	});
	var wrappedVerse = wrapCanvasText(textbox_text_object, canvas, templateWidth - ( xOffset * 2 ), templateHeight * 3, textbox_text_object.getTextAlign());
	var text_textbox = new fabric.IText(wrappedVerse, {
		itemId : getLayerLastID() + 1,
        left: xOffset,
        top: yOffset,
        fontFamily: 'Oswald',
        fill: '#333',
        lineHeight : 1.4,
        fontSize : 41 ,
        textAlign : "center",
        transparentCorners: false
	});

	var text_textbox_height = ( templateHeight - text_textbox.getHeight() ) / 2;
	text_textbox.setTop(text_textbox_height);
	<?php if($extern) { ?>
	text_textbox.setLeft( 20 ); 
	<?php } else { ?>
	text_textbox.setLeft( text_textbox.getWidth() - (text_textbox.getWidth() / 4) );
	<?php } ?> 
	text_textbox.type = 'text';
	canvas.add( text_textbox );

	var author_textbox = new fabric.IText( '<?php echo $username; ?>', {
		itemId : getLayerLastID() + 1,
        left: xOffset,
        top: yOffset,
        fontFamily: 'Oswald',
        fill: '#333',
        fontSize : 30 ,
        textAlign : "center",
        transparentCorners: false
	}); 
	author_textbox.setTop(text_textbox_height + text_textbox.getHeight() + 20); 
	<?php if($extern) { ?>
	text_textbox.setLeft( 20 ); 
	<?php } else { ?>
	author_textbox.setLeft( (text_textbox.getWidth()));
	<?php } ?> 
	author_textbox.type = 'text';
	canvas.add( author_textbox );

	canvas.renderAll();

	addBgImage(imageSrc);
	
	document.getElementById('uploadedImg').onchange = function handleImage(ey) { 
		var oInput = ey.target;
		var sFileName = oInput.value;
		if (sFileName.length > 0) { 
			var blnValid = false;
			blnValid = cEx(sFileName);
			if (!blnValid) {
				alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
				oInput.value = "";
				return false;
			}
		}
		var reader = new FileReader();
		reader.onload = function (event){
			var io = new Image();
			io.src = event.target.result;
			io.onload = function () {
				var fushit = rsifu(templateWidth, templateHeight,this.width,this.height);
				var image = new fabric.Image(io);
				image.set({ itemId : getLayerLastID() + 1, padding: cellpadding, height:fushit[1], width:fushit[0] }); 
				canvas.centerObject(image);
				canvas.add(image);
				canvas.sendToBack(image);
				canvas.renderAll();
			}
		}
		reader.readAsDataURL(ey.target.files[0]);
	};

    // Tabs toggle
    $('.tabs-toggle li').click(function(){
    	$('.tabs-toggle li').removeClass('uk-active');
    	$(this).addClass('uk-active');
    	var target = $(this).data('target');
    	$('.tabs-content > div').removeClass('active');
    	$('.tabs-content > div#'+target).addClass('active');
    }); 

    // Loader image
	$('body').addClass('loaded');
});
 
</script>
            