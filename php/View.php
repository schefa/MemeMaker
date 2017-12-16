<?php 

if(!defined('_EXEC')) {
    die();
}

?>

<html prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# denkschatz: http://ogp.me/ns/fb/denkschatz#" class="no-js" lang="de" ng-app="memeMaker" >

<head>
	<title><?php echo Language::_('site_title') ?></title>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="<?php echo Language::_('site_description') ?>">
 
	<style>
	#loader-wrapper {width: 100%;position: fixed;height: 100%;background-color: #fff;z-index: 1000;top: 0;}
	.loaded #loader-wrapper { opacity:0; transition: all 0.5s ease-out;display:none;}
	.spinner {margin: 100px auto 0;width: 70px;text-align: center;}
	.spinner > div {width: 18px;height: 18px;background-color:#d9d9d9;border-radius: 100%;display: inline-block;-webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;animation: sk-bouncedelay 1.4s infinite ease-in-out both;}
	.spinner .bounce1 {-webkit-animation-delay: -0.32s;animation-delay: -0.32s;}
	.spinner .bounce2 {-webkit-animation-delay: -0.16s;animation-delay: -0.16s;}
	@-webkit-keyframes sk-bouncedelay {0%, 80%, 100% { -webkit-transform: scale(0) } 40% { -webkit-transform: scale(1.0) }}
	@keyframes sk-bouncedelay {0%, 80%, 100% { -webkit-transform: scale(0);transform: scale(0);} 40% { -webkit-transform: scale(1.0);transform: scale(1.0);}}
	</style>
	
    <script src="vendors/jquery/jquery.js"></script>
	<script src="vendors/angular/angular.min.js" type="text/javascript"></script>
	<script src="vendors/angular/angular-sanitize.min.js" type="text/javascript"></script>
	
    <script src="js/app.js" type="text/javascript"></script>
	<script src="vendors/fabric/fabric.js" type="text/javascript"></script> 
	<script src="vendors/fabric/kitch.js" type="text/javascript"></script>

    <link href="vendors/uikit/css/uikit.min.css" rel="stylesheet" type="text/css" /> 
	<link href="vendors/fontawesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	
	<link href="https://fonts.googleapis.com/css?family=Kaushan+Script|Marcellus+SC|Oswald|Shadows+Into+Light+Two" rel="stylesheet"> 
	 
</head>

<body>

<div id="loader-wrapper">
    <div class="spinner">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
</div>

<div class="mm-container uk-clearfix" ng-controller="MainController as mainCtrl">

    <div class="mm-bottom-toolbar">
        <div class="clearfix fullwidth">
             
        <div class="mm-row mm-bottom-toolbar-inner-1">
			<h1 class="mm-headline">Meme Maker</h1>      
		</div>
             
        <div class="mm-row mm-bottom-toolbar-inner-2">
    
        <div id="image-url-controls" class="mm-row uk-form">
            
            <table class="fullwidth"><tbody>
                <tr>
                    <td><label><?php echo Language::_("work_space"); ?></label></td>
                    <td width="8"></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td>
                    <div id="image-format-controls" class="select-arrow category">
                    <select name="meme_format" id="meme_format" class="form-control">
                        <option value="1">1 : 1 (Facebook, Instagram)</option>
                        <option value="2">2 : 1 (Twitter)</option>
                        <option value="3">1 : 2 (Pinterest)</option>
                        <option value="4" selected="selected">4 : 3</option>
                        <option value="5" >16 : 9</option>
                    </select>
                    </div>
                    </td>
                    <td width="8"></td>
                    <td>
                    </td>
                </tr>
            </tbody></table>
            
        </div>
        
        </div>
    
        <div class="mm-send-or-save mm-bottom-toolbar-inner-3">
            <table>
            <tbody>
                <tr>
                    <td><button id="cv-reset" class="uk-button"><?php echo Language::_("reset");?></button></td>
                    <?php /* ?>
                    <td><button id="rasterize-json" class="uk-button" ng-click="rasterizeJSON()"><?php echo Language::_("JSON");?></button></td>
                    <?php */ ?>
                    <td>
                    <button id="rasterize" class="uk-button uk-button-large uk-button-primary" ng-click="rasterize()"><?php echo Language::_("print_image");?></button>
                    </td>
                </tr>
            </tbody>
            </table>
        </div>  
        
        </div>  
    </div>  
 
    
        <div class="mm-left-col">
        <div class="mm-left-col-inner">

            <div class="mm-canvas-bg" >
            <canvas id="canvas" width="640" height="480"></canvas>
            </div>
            
        </div>
		</div>
        
        <div class="mm-right-col">

            <div class="mm-row mm-group-fieldset  uk-clearfix">
            
            	<div class="mm-group-headline uk-clearfix">
				<h3 class="mm-headline"><?php echo Language::_('layers') ?></h3>
				</div>
            
				<div class="mm-group-fieldset-inner">
                	
                    <div class="mm-row mm-new-layer uk-clearfix">
                    
                        <div class="pull-left">
                        <label class="head"><?php echo Language::_('layer_new') ?></label>
                        <button class="uk-button" ng-click="addText()"><i class="fa fa-file-text-o"></i> <?php echo Language::_('text') ?></button>
                        </div>
                        
                        <div class="pull-left mm-layer-image">
                        
                            <div class="mm-layer-image-text">
                            <button class="uk-button"><i class="fa uk-icon-file-image-o"></i> <?php echo Language::_('image') ?> <i class="fa fa-angle-down"></i></button>
                            </div>
                        
                            <div class="mm-layer-image-dropdown"> 
                                
					<div class="uk-grid uk-form">
						<div class="mm-row">
    						<label><?php echo Language::_('image_upload') ?></label>
                            <span class="uk-form-file hasTooltip">
                                 <button class="uk-button"><?php echo Language::_('search') ?></button>
                                 <input id="uploadedImg" type="file" accept="image/*" />
                            </span>
                            <div class="mm-row-desc"><?php echo Language::_('image_upload_desc') ?></div>
						</div>
						<div class="mm-row">
    						<label><?php echo Language::_('image_url'); ?></label>
                            <input type="url" name="image" class="fullwidth" id="mm-bgimage" onClick="this.select();" value="<?php echo $bgimageResult; ?>" placeholder="<?php echo $bgimageResult; ?>">
                            <button id="mm-bgimage-load" class="uk-button"><?php echo Language::_('image_load') ?></button>
                            <span class="mm-bgimage-loading"></span>
						</div>
						<div class="mm-row mm-image-gallery">
    						<label><?php echo Language::_('image_sample'); ?></label>
                            <ul class="mm-sample-gallery">
                            <?php if ($handle = opendir('images/cover/thumbs')) {
                				while (false !== ($file = readdir($handle))) {
                					if ($file != "." && $file != "..") {
                						$checkFile = explode(".",$file);
                						if($checkFile[1] == 'jpg') {
                							$class =  '';
                							echo '<li'. $class .'><img src="images/cover/thumbs/'. $file . '" width="50" /></li>';
                						}
                					}
                				}
                				closedir($handle);
                				}
                			?>
                            </ul>
                        </div> 
					</div>
                        
                            </div>
                        
                        </div>
                        
                        <div class="pull-right">
            				<div class="uk-form mm-row">
                				<label><?php echo Language::_('background_color') ?></label>
                            	<input id="canvas-background" type="color" class="colorpicker" bind-value-to="canvasBgColor">
                            </div>
                        </div>
                        
                    </div>
                    
                	<div class="mm-layers uk-clearfix">
                		<div class="mm-layer uk-clearfix" ng-repeat="item in layers" ng-class="layerClass({{item.itemId}})" >
                    		<div class="mm-layer-type" ng-click="selectLayer(item)" ng-bind-html="getLayerType(item.type)"> </div>
                    		<div class="mm-layer-text" ng-click="selectLayer(item)">
                    		<span ng-if="item.type === 'image'"><img src="{{ item.getSrc() }}" width="32" height="32" /></span>
                    		<span ng-if="item.type != 'image'">{{ item.text | limitTo:80 }}</span>
                    		</div>
                    		
                    		<div class="mm-layer-remove"><button ng-click="removeLayer(item)" class="uk-button"><i class="fa fa-remove"></i></button></div>
                    		
                    		<div class="mm-layer-sort uk-button-group">
                        <button title="<?php echo Language::_('layer_to_back'); ?>" class="uk-button" ng-click="sendBackwards(item)"><i class="fa fa-angle-double-up"></i></button>
                        <button title="<?php echo Language::_('layer_to_front'); ?>" class="uk-button" ng-click="bringForward(item)"><i class="fa fa-angle-double-down"></i></button> 
                        	</div>
                        	 
                		</div>
                	</div>
                
                    
            	</div>
                
            </div>
        
        <div class="mm-row mm-layer-hint" ng-show="!canvas.getActiveObject()"><?php echo Language::_('layer_select_to_edit'); ?></div>
        
        <div class="mm-right-col-inner uk-form">
        
            <div class="mm-group-fieldset uk-clearfix" ng-show="canvas.getActiveObject()">
            
            	<div class="mm-group-headline uk-clearfix">
				<h3 class="mm-headline"><?php echo Language::_("layer_selected"); ?></h3>
				</div>
				
				<div class="mm-group-fieldset-inner">
				<div class="uk-grid mm-row">
					<div class="pull-left">
                        <label class="pull-left"><?php echo Language::_("opacity");?></label>
                        <input value="100" type="range" bind-value-to="opacity" class="fullwidth">
                    </div>
                    
                    <div class="pull-left">
                        <label><?php echo Language::_("width");?></label>
                        <input class="mm-input-short-with-margin" type="number" min="1" bind-value-to="width">
                    </div>
                    
                    <div class="pull-left">
                        <label><?php echo Language::_("height");?></label>
                        <input class="mm-input-short-with-margin" type="number" min="1" bind-value-to="height">
                    </div>
                </div>
                
				<div class="uk-grid mm-row">
                    <div class="uk-width-large-1-1">
                        <div class="pull-left">
                        <label class="head"><?php echo Language::_("position");?></label>
                        </div>
                        <div class="pull-left">
                            <label><?php echo Language::_("left");?></label>
                            <input class="mm-input-short-with-margin" type="number" min="1" bind-value-to="left">
                        </div>
                        <div class="pull-left">
                            <label><?php echo Language::_("top");?></label>
                            <input class="mm-input-short-with-margin" type="number" min="1" bind-value-to="top">
                        </div>
                    </div>
                </div>
				</div>
                
            </div>
                

            <div id="mm-wrapper" class="uk-clearfix" ng-show="getText()">
            
                <ul class="mm-ul-clear tabs-toggle">
                    <li data-target="tab-text" class="uk-active"><h3 class="mm-headline"><?php echo Language::_("text"); ?></h3></li>
                    <li data-target="tab-shadow"><h3 class="mm-headline"><?php echo Language::_("shadow"); ?></h3></li>
                    <li data-target="tab-outline"><h3 class="mm-headline"><?php echo Language::_("outline"); ?></h3></li>
                </ul>

				<div id="tabs" class="tabs-content">
    
                	<div id="tab-text" class="mm-group-fieldset-inner active">
                    	
                        <table class="mm-row mm-table">
                        <tbody>
                        <tr>
                        	<td colspan="5">
                            <div class="checkText" data-view="" style="display:none;"></div>
                            <textarea bind-value-to="text" id="denkschatz-text" class="fullwidth form-control" ng-show="isText()"></textarea>
                            </td>
                        </tr>
                        <tr>
                        	<td><label for="text-colorpicker"><?php echo Language::_("font_color");?></label>
                        	<input type="color" class="colorpicker" id="text-colorpicker" style="width:40px" bind-value-to="fill"></td>
                            <td width="8"></td>
                        	<td><label for="text-font-size"><?php echo Language::_("font_size");?></label>
                        	<input type="number" value="" min="1" max="120" step="1" id="text-font-size" class="form-control" bind-value-to="fontSize"></td>
                            <td width="8"></td>
                        	<td><label for="text-family"><?php echo Language::_("font_family");?></label>
                              <select id="text-family" class="form-control" bind-value-to="fontFamily">
                                <option value="Kaushan Script">Kaushan Script</option>
                                <option value="Shadows Into Light Two">Shadows Into Light Two</option> 
                                <option value="Marcellus SC">Marcellus SC</option>
                                <option value="comic sans ms">Comic Sans MS</option>
                                <option value="georgia">Georgia</option>
                                <option value="monaco">Monaco</option>
                                <option value="verdana">Verdana</option>
                                <option value="helvetica">Helvetica</option> 
                                <option value="Oswald" selected="selected">Oswald</option>
                                <option value="impact">Impact</option>
                              </select>                             
                              </div>
                            </td>
                        </tr>
                        <tr>
                        	<td colspan="3">
                        	<label for="text-align"><?php echo Language::_("text_align");?></label>
                            <div class="btn-group">
							  <button id="text-align" class="uk-button" ng-click="toggleTextAlign('left');" ng-class="{'active': isTextAlign('left')}" title="<?php echo Language::_("text_align_left");?>"><i class="fa fa-align-left"></i></button>
                              <button class="uk-button" ng-click="toggleTextAlign('center');" ng-class="{'uk-active': isTextAlign('center')}" title="<?php echo Language::_("text_align_center");?>"><i class="fa fa-align-center"></i></button>
                              <button class="uk-button" ng-click="toggleTextAlign('right');" ng-class="{'uk-active': isTextAlign('right')}" title="<?php echo Language::_("text_align_right");?>"><i class="fa fa-align-right"></i></button>
                              <button class="uk-button" ng-click="toggleTextAlign('justify');" ng-class="{'uk-active': isTextAlign('justify')}" title="<?php echo Language::_("text_align_block");?>"><i class="fa fa-align-justify"></i></button>
                            </div>
                            </td>
                        	<td width="10"></td>
                        	<td><label for="text-line-height"><?php echo Language::_("line_height");?></label><input type="range" value="" min="0" max="10" step="0.1" id="text-line-height" class="fullwidth btn-object-action" bind-value-to="lineHeight"></td>
                        </tr>
                        <tr>
                        	<td colspan="5">
                        	<label for="text-style"><?php echo Language::_("font_style");?></label>
                            <div id="mm-text-controls-additional" class="btn-group">
                              <button type="button" class="uk-button" id="text-cmd-capitalize" ng-click="toggleCapitalize()" ng-class="{'uk-active': isCapitalize()}">ABC</button>
                              <button type="button" class="uk-button" id="text-cmd-bold" ng-click="toggleBold()" ng-class="{'uk-active': isBold()}">Abc</button>
                              <button type="button" class="uk-button" id="text-cmd-italic" ng-click="toggleItalic()" ng-class="{'uk-active': isItalic()}">Abc</button>
                              <button type="button" class="uk-button" id="text-cmd-underline" ng-click="toggleUnderline()" ng-class="{'uk-active': isUnderline()}">Abc</button>
                              <button type="button" class="uk-button" id="text-cmd-linethrough" ng-click="toggleLinethrough()" ng-class="{'uk-active': isLinethrough()}">Abc</button>
                              <button type="button" class="uk-button" id="text-cmd-overline" ng-click="toggleOverline()" ng-class="{'uk-active': isOverline()}">Abc</button>
                            </div>
                            </td>
                        </tr>
                        </tbody>
                        </table>
                        
                    </div>
                   
                	<div id="tab-shadow" class="mm-group-fieldset-inner mm-shadow">
                	
                		<button id="shadowify" class="uk-button mm-tab-button" ng-click="shadowify()" ng-class="{'uk-active': isShadowified()}"><?php echo Language::_("apply");?></button>

                      <table class="fullwidth mm-row text-center" ng-show="isShadowified()">
                        <tbody>
                            <tr>
                                <td>
                                    <label for="shadow-color"><?php echo Language::_("color");?></label>
                                </td>
                                <td>
                                    <label for="shadow-blur"><?php echo Language::_("variance");?></label>
                                </td>
                                <td colspan="3">
                                    <label for="shadow-distance"><?php echo Language::_("distance");?></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="color" class="colorpicker" id="shadow-color" style="width:40px" bind-value-to="shadowColor">
                                </td>
                                
                                <td>
                                    <input type="range" value="" min="0" max="20" step="1" id="shadow-blur" class="fullwidth btn-object-action" bind-value-to="shadowBlur">
                                </td>
                                
                                <td width="5"></td>
                                
                                <td>
                                    x <input type="number" value="3" min="-50" max="50" step="1" id="shadow-distance" bind-value-to="shadowDX">
                                </td>
                                
                                <td>
                                    y <input type="number" value="3" min="-50" max="50" step="1" id="shadow-distance-y" bind-value-to="shadowDY">
                                </td>
                                
                            </tr>
                        </tbody>
                      </table>
                      
                    </div>
                    
                	<div id="tab-outline" class="mm-group-fieldset-inner">
                	
                		<button id="stroked" class="uk-button mm-tab-button" ng-click="toggleStroke()" ng-class="{'uk-active': isStroked()}"><?php echo Language::_("apply");?></button>
                		<br>
                        <table class="fullwidth mm-row mm-input-mini" ng-show="isStroked()">
                        <tbody>
                            <tr>
                                <td>
                            <label for="text-stroke-width"><?php echo Language::_("width");?></label>
                            <input type="number" value="0" min="1" max="6" id="text-stroke-width" class="btn-object-action" bind-value-to="strokeWidth">
                                </td>
                                <td class="mm-group-fieldset-padding">
                            <label for="text-stroke-color"><?php echo Language::_("color");?></label>
                            <input type="color" class="colorpicker" value="" id="text-stroke-color" bind-value-to="strokeColor">
                                </td>
                                <td class="mm-group-fieldset-padding">
                            <label for="text-lines-bg-color"><?php echo Language::_("text_background");?></label>
                            <input type="color" class="colorpicker" value="" id="text-lines-bg-color" size="10" bind-value-to="textBgColor">
                                </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                
				</div>

                
        	</div>
        	
        </div>

	</div>
  
	<?php include_once('php/addition.php'); ?>

</div>
 
</body>