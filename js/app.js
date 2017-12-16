
angular.module('memeMaker', ['ngSanitize'])
.controller('MainController', function($scope) {
	$scope.layers = canvas.getObjects();
	$scope.canvas = canvas;
 // $scope.addCopy = addCopy;
	$scope.getActiveStyle = getActiveStyle;
	addAccessors($scope);
	watchCanvas($scope);
})
.directive('bindValueTo', function() {
  return {
    restrict: 'A',

    link: function ($scope, $element, $attrs) {

      var prop = capitalize($attrs.bindValueTo),
          getter = 'get' + prop,
          setter = 'set' + prop;

      $element.on('change keyup select', function() {
        $scope[setter] && $scope[setter](this.value);
      });

      $scope.$watch($scope[getter], function(newVal) {
        if ($element[0].type === 'radio') {
          var radioGroup = document.getElementsByName($element[0].name);
          for (var i = 0, len = radioGroup.length; i < len; i++) {
            radioGroup[i].checked = radioGroup[i].value === newVal;
          }
        }
        else {
          $element.val(newVal);
        }
      });
    }
  };
})
.directive('objectButtonsEnabled', function() {
  return {
    restrict: 'A',
    link: function ($scope, $element, $attrs) {
      $scope.$watch($attrs.objectButtonsEnabled, function(newVal) {
        $($element).find('.btn-object-action')
          .prop('disabled', !newVal);
      });
    }
  };
});
 
/*
*/

function getActiveStyle(styleName, object) {
  object = object || canvas.getActiveObject();
  if (!object) return '';

  return (object.getSelectionStyles && object.isEditing)
    ? (object.getSelectionStyles()[styleName] || '')
    : (object[styleName] || '');
};

function setActiveStyle(styleName, value, object) {
  object = object || canvas.getActiveObject();
  if (!object) return;

  if (object.setSelectionStyles && object.isEditing) {
    var style = { };
    style[styleName] = value;
    object.setSelectionStyles(style);
    object.setCoords();
  }
  else { object[styleName] = value; }
  object.setCoords(); canvas.renderAll();
};

function getActiveProp(name) { var object = canvas.getActiveObject(); if (!object) { return ''; } else { return object[name] || ''; } }
function setActiveProp(name, value) { var object = canvas.getActiveObject(); if (!object) { return; } else { object.set(name, value).setCoords(); } canvas.renderAll(); }

/*******************************************************************************************
******** addAccessors *****************************************************************************************/
  
function addAccessors($scope) {
	
	var updateLayers = function() { 
		$scope.layers = canvas.getObjects();
	};
	
	$scope.removeLayer = function(layer) {
		canvas.remove(layer); 
	};
	$scope.selectLayer = function(item) {
		canvas.setActiveObject(item);
	};
    $scope.layerClass = function (id) {
        return (canvas.getActiveObject() !== null && id === canvas.getActiveObject().get('itemId')) ? 'active' : '';
    };   
	$scope.getLayerType = function(type) {
		var result = null;
		switch(type) {
			case 'image': result = '<i class="fa uk-icon-file-image-o"></i>'; break;
			case 'i-text': result = '<i class="fa fa-file-text-o"></i>'; break;
		}
		return result;
	};

	$scope.getOpacity = function() { return getActiveStyle('opacity') * 100; };
	$scope.setOpacity = function(value) { setActiveStyle('opacity', parseInt(value, 10) / 100); };

	$scope.getTop = function() { return Math.round(getActiveStyle('top')); };
	$scope.setTop = function(value) { setActiveStyle('top', parseInt(value)); };

	$scope.getLeft = function() { return Math.round(getActiveStyle('left')); };
	$scope.setLeft = function(value) { setActiveStyle('left', parseInt(value)); };

	$scope.getWidth = function() { return Math.round(getActiveStyle('width')); };
	$scope.setWidth = function(value) { setActiveStyle('width', parseInt(value)); };

	$scope.getHeight = function() { return Math.round(getActiveStyle('height')); };
	$scope.setHeight = function(value) { setActiveStyle('height', parseInt(value)); };
	
	$scope.getFill = function() { return getActiveStyle('fill'); };
	$scope.setFill = function(value) { setActiveStyle('fill', value); };
	
	$scope.isBold = function() { return getActiveStyle('fontWeight') === 'bold'; };
	$scope.toggleBold = function() { setActiveStyle('fontWeight', getActiveStyle('fontWeight') === 'bold' ? '' : 'bold'); };
	
	$scope.isItalic = function() { return getActiveStyle('fontStyle') === 'italic'; };
	$scope.toggleItalic = function() { setActiveStyle('fontStyle', getActiveStyle('fontStyle') === 'italic' ? '' : 'italic'); };
	
	$scope.isUnderline = function() { return getActiveStyle('textDecoration').indexOf('underline') > -1; };
	$scope.toggleUnderline = function() {
		var value = $scope.isUnderline() ? getActiveStyle('textDecoration').replace('underline', '') : (getActiveStyle('textDecoration') + ' underline');
		setActiveStyle('textDecoration', value);
	};
	
	$scope.isLinethrough = function() { return getActiveStyle('textDecoration').indexOf('line-through') > -1; };
	$scope.toggleLinethrough = function() {
		var value = $scope.isLinethrough() ? getActiveStyle('textDecoration').replace('line-through', '') : (getActiveStyle('textDecoration') + ' line-through');
		setActiveStyle('textDecoration', value);
	};
	
	$scope.isOverline = function() { return getActiveStyle('textDecoration').indexOf('overline') > -1; };
	$scope.toggleOverline = function() {
	  var value = $scope.isOverline() ? getActiveStyle('textDecoration').replace('overline', '') : (getActiveStyle('textDecoration') + ' overline');
		setActiveStyle('textDecoration', value);
	};

	$scope.isCapitalize = function() {
		var object = canvas.getActiveObject();
		if (!object) return '';
		if( typeof object['text'] !== 'undefined' && object['text'] == object['text'].toUpperCase()) { return true; }
		return '';
	};
  
	$scope.toggleCapitalize = function() {
		var object = canvas.getActiveObject();
		if (!object) return;
		
		if(getActiveProp('capitalizedText')) {
			object.set("capitalizedText", null).setCoords();
			object.set("text", object['textOriginal']).setCoords();
		} else {
			var textCapitalized = object.text.toUpperCase();
			object.set("capitalizedText", textCapitalized).setCoords();
			object.set("textOriginal", object['text']).setCoords();
			object.set("text", textCapitalized).setCoords();
		}
		canvas.renderAll();
	};
	
	$scope.isText = function() { return getActiveProp('textLocked') !== true; };
	$scope.getText = function() { return (getActiveProp('capitalizedText') || getActiveProp('text')); };
	$scope.setText = function(value) { setActiveProp('text', value); };

	$scope.addText = function() {
		var textSample = new fabric.IText(_textfieldText, {
			itemId: getLayerLastID() + 1,
			left: 20,
			top: 20,
			fontFamily: 'Oswald',
			fill: '#' + getRandomColor(),
			checkTextAjax : false, 
			hasRotatingPoint: true,
			centerTransform: true,
		    transparentCorners: false
		});
		canvas.add(textSample);
		updateLayers();
	}; 
	
	$scope.getTextAlign = function() { return getActiveProp('textAlign') || "left"; };
	$scope.setTextAlign = function(value) { setActiveProp('textAlign', value ); };
	
	$scope.isTextAlign = function(value) {
		var object = canvas.getActiveObject(); if (!object) { return false; }
		if( typeof object['textAlign'] !== 'undefined' && object['textAlign'] == value) { return true; }
		return '';
	};
	$scope.toggleTextAlign = function( value ) { 
		var object = canvas.getActiveObject(); if (!object) { return; } else { object.set("textAlign", value).setCoords(); } canvas.renderAll(); };
	
	$scope.getFontFamily = function() { return getActiveProp('fontFamily'); };
	$scope.setFontFamily = function(value) { setActiveProp('fontFamily', value); };
	$scope.getFontSize = function() { return getActiveStyle('fontSize'); };
	$scope.setFontSize = function(value) { setActiveStyle('fontSize', parseInt(value, 10)); };
	
	$scope.getLineHeight = function() { return getActiveStyle('lineHeight'); };
	$scope.setLineHeight = function(value) { setActiveStyle('lineHeight', parseFloat(value, 10)); };
	
	$scope.getBold = function() { return getActiveStyle('fontWeight'); };
	$scope.setBold = function(value) { setActiveStyle('fontWeight', value ? 'bold' : ''); };
	
	$scope.getCanvasBgColor = function() { return canvas.backgroundColor; };
	$scope.setCanvasBgColor = function(value) { canvas.backgroundColor = value; canvas.renderAll(); };
	
	$scope.rasterize = function() { 
		// addCopy(canvas.getWidth(), canvas.getHeight());
		canvas.renderAll(); canvas.deactivateAll().renderAll(); 
		if (!fabric.Canvas.supports('toDataURL')) { 
			alert(_browserWarningText); 
		} else { 
			window.open(canvas.toDataURL('png'));
		}
	};

	$scope.rasterizeJSON = function() { alert(JSON.stringify(canvas)); };
	$scope.removeShape = function() { canvas.remove(canvas.getActiveObject()); }

	$scope.getHorizontalLock = function() { return getActiveProp('lockMovementX'); };
	$scope.setHorizontalLock = function(value) { setActiveProp('lockMovementX', value); };

	$scope.getVerticalLock = function() { return getActiveProp('lockMovementY'); };
	$scope.setVerticalLock = function(value) { setActiveProp('lockMovementY', value); };

	$scope.getScaleLockX = function() { return getActiveProp('lockScalingX'); },
	$scope.setScaleLockX = function(value) { setActiveProp('lockScalingX', value); };

	$scope.getScaleLockY = function() { return getActiveProp('lockScalingY'); };
	$scope.setScaleLockY = function(value) { setActiveProp('lockScalingY', value); };

	$scope.getRotationLock = function() { return getActiveProp('lockRotation'); };
	$scope.setRotationLock = function(value) { setActiveProp('lockRotation', value); };

	$scope.getOriginX = function() { return getActiveProp('originX'); };
	$scope.setOriginX = function(value) { setActiveProp('originX', value); };

	$scope.getOriginY = function() { return getActiveProp('originY'); };
  	$scope.setOriginY = function(value) { setActiveProp('originY', value); };
  
	/************************************/
	/************** Shadow **************/
	/************************************/
  
	$scope.getShadowColor = function() {
		var object = canvas.getActiveObject(); if(object && object.getShadow() && object.getShadow().color) { return object.getShadow().color; } else { return ''; } };
  
	$scope.setShadowColor = function(value) {
		var object = canvas.getActiveObject(); if(object && object.getShadow() ) { object.shadow.color = value; canvas.renderAll();  } else { return false } };

	$scope.getShadowBlur = function() {
		var object = canvas.getActiveObject(); if(object && object.getShadow() && object.getShadow().blur) { return object.getShadow().blur; } else { return ''; } };
  
	$scope.setShadowBlur = function(value) {
	  var object = canvas.getActiveObject(); if(object && object.getShadow() ) { object.shadow.blur = value; canvas.renderAll();  } else { return false } };

	$scope.getShadowDX = function() {
	 var object = canvas.getActiveObject(); if(object && object.getShadow() && object.getShadow().offsetX) { return object.getShadow().offsetX; } else { return ''; } };
  
	$scope.setShadowDX = function(value) {
		var object = canvas.getActiveObject(); if(object && object.getShadow() ) { object.shadow.offsetX = value; canvas.renderAll();  } else { return false } };

	$scope.getShadowDY = function() {
		var object = canvas.getActiveObject(); if(object && object.getShadow() && object.getShadow().offsetY) {return object.getShadow().offsetY; } else { return ''; }};
  
	$scope.setShadowDY = function(value) { 
		var object = canvas.getActiveObject(); if(object && object.getShadow() ) { object.shadow.offsetY = value; canvas.renderAll();  } else { return false } };

	$scope.isShadowified = function() { return getActiveStyle('shadow') !== ''; };
	$scope.shadowify = function() {
	  var obj = canvas.getActiveObject();
	  if (!obj) { return; }
	  if (obj.shadow) { obj.shadow = null; } else { obj.setShadow({ color: 'rgba(0,0,0,0.5)', blur: 10, offsetX: 0.1, offsetY: 3 }); }
	  canvas.renderAll();
	};
  
	/************************************/
	/************** Stroke **************/
	/************************************/
  
	$scope.getTextBgColor = function() { return getActiveProp('textBackgroundColor'); };
	$scope.setTextBgColor = function(value) { setActiveProp('textBackgroundColor', value); };
	
	$scope.getStrokeColor = function() { return getActiveStyle('stroke'); };
	$scope.setStrokeColor = function(value) { setActiveStyle('stroke', value); };
	
	$scope.getStrokeWidth = function() { return getActiveStyle('strokeWidth'); };
	$scope.setStrokeWidth = function(value) { setActiveStyle('strokeWidth', parseInt(value, 10)); };
	
	$scope.isStroked = function() { return ( getActiveStyle('stroke') !== '' ); };
	$scope.toggleStroke = function() {
		var obj = canvas.getActiveObject();
		if ( obj.stroke || obj.strokeWidth) {
			obj.stroke = null; 
			obj.strokeWidth = null;
		} else {
			obj.stroke = "black";
			obj.strokeWidth = 1;
		}
		canvas.renderAll();
	};

	/************************************/
	/************** Layers **************/
	/************************************/
	$scope.sendBackwards	= function(item) { 
		var activeObject = canvas.getActiveObject(); 
		if (activeObject) { canvas.sendBackwards(item); }
	};
	$scope.sendToBack		= function(item) { 
		var activeObject = canvas.getActiveObject();
		if (item) { canvas.sendToBack(item); }
	};
	$scope.bringForward		= function(item) { 
		var activeObject = canvas.getActiveObject();
		if (item) { canvas.bringForward(item); } 
	};
	$scope.bringToFront		= function(item) { 
		var activeObject = canvas.getActiveObject(); 
		if (item) { canvas.bringToFront(item); }
	};

}

function watchCanvas($scope) {

  function updateScope() {
    $scope.$$phase || $scope.$digest();
    canvas.renderAll();
  }

  canvas
    .on('object:selected', updateScope)
    .on('group:selected', updateScope)
    .on('path:created', updateScope)
    .on('selection:cleared', updateScope);
}

function addCopy(templateW, templateH) {
	var copy = new fabric.Text('denkschatz.de', { id:"copyright", top : (templateH - 19), left : (templateW - 89), fontSize : 16, fill : noteColor, fontFamily: 'Oswald', lockRotation : true, selectable : false });
	canvas.add(copy).renderAll();
	canvas.bringToFront(copy);
	canvas.setActiveObject(copy);
}

function wrapCanvasText(t, canvas, maxW, maxH, justify) {

	if (typeof maxH === "undefined") { maxH = 0; }
	var words = t.text.split(" ");
	var formatted = '';

	// This works only with monospace fonts
	justify = justify || 'left';

	// clear newlines
	var sansBreaks = t.text.replace(/(\r\n|\n|\r)/gm, "");
	
	// calc line height
	var lineHeight = new fabric.Text(sansBreaks, {
		fontFamily: t.fontFamily,
		fontSize: t.fontSize
	}).height;

	// adjust for vertical offset
	var maxHAdjusted = maxH > 0 ? maxH - lineHeight : 0;
	var context = canvas.getContext("2d");

	context.font = t.fontSize + "px " + t.fontFamily;
	var currentLine = '';
	var breakLineCount = 0;

	n = 0;
	while (n < words.length) {
		var isNewLine = currentLine == "";
		var testOverlap = currentLine + ' ' + words[n];

		// are we over width?
		var w = context.measureText(testOverlap).width;
		
		if(words[n] === '|br|') {
			
			while (justify == 'center' && context.measureText(' ' + currentLine + ' ').width < maxW)
			currentLine = ' ' + currentLine + ' ';
			
			formatted += currentLine + '\n';
			breakLineCount++;
			currentLine = "";
			n++;
			continue; // restart cycle
		}
		
		if (w < maxW) { // if not, keep adding words
			if (currentLine != '') currentLine += ' ';
			currentLine += words[n];
			// formatted += words[n] + ' ';
		} else {

			// if this hits, we got a word that need to be hypenated
			if (isNewLine) {
				var wordOverlap = "";

				// test word length until its over maxW
				for (var i = 0; i < words[n].length; ++i) {

					wordOverlap += words[n].charAt(i);
					var withHypeh = wordOverlap + "-";

					if (context.measureText(withHypeh).width >= maxW) {
						// add hyphen when splitting a word
						withHypeh = wordOverlap.substr(0, wordOverlap.length - 2) + "-";
						// update current word with remainder
						words[n] = words[n].substr(wordOverlap.length - 1, words[n].length);
						formatted += withHypeh; // add hypenated word
						break;
					}
				}
			}
			//while (justify == 'right' && context.measureText(' ' + currentLine).width < maxW)
			//currentLine = ' ' + currentLine;

			//while (justify == 'center' && context.measureText(' ' + currentLine + ' ').width < maxW)
			//currentLine = ' ' + currentLine + ' ';
			
			formatted += currentLine + '\n';
			breakLineCount++;
			currentLine = "";

			continue; // restart cycle
		}
		if (maxHAdjusted > 0 && (breakLineCount * lineHeight) > maxHAdjusted) {
			// add ... at the end indicating text was cutoff
			formatted = formatted.substr(0, formatted.length - 3) + "...\n";
			currentLine = "";
			break;
		}
		
		n++;
	}

	if (currentLine != '') {
		//while (justify == 'right' && context.measureText(' ' + currentLine).width < maxW)
		//currentLine = ' ' + currentLine;

		//while (justify == 'center' && context.measureText(' ' + currentLine + ' ').width < maxW)
		//currentLine = ' ' + currentLine + ' ';

		formatted += currentLine + '\n';
		breakLineCount++;
		currentLine = "";
	}

	// get rid of empy newline at the end
	formatted = formatted.substr(0, formatted.length - 1);
	return formatted;
}
 