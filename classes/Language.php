<?php

if(!defined('_EXEC')) {
    die();
}

class Language {
    
    private static $language = array();
    private static $english = array(
        'site_title' => 'Online Editor to create Memes',
        'site_description'=>'Make incredible Memes to increase your visibility on social media platforms',
        'layers'=>'Layers',
        'layer'=>'Layer',
        'layer_new'=>'New layer',
        'layer_select_to_edit'=>'Select a layer to edit',
        'layer_selected'=>'Selected layer',
        'text'=>'Text',
        'image'=>'Image',
        'image_url'=>'Insert Image-URL',
        'image_upload'=>'Load image',
        'image_upload_desc'=>'Nothing will be stored on our server!',
        'image_load'=>'Load',
        'image_sample'=>'Sample image',
        'search'=>'Browse...',
        'background_color'=>'Background color',
        'layer_to_back'=>'Put layer to back',
        'layer_to_front'=>'Put layer to front',
        'opacity'=>'Opacity',
        'font_color'=>'Font color',
        'font_size'=>'Font size',
        'font_family'=>'Font family',
        'text_align'=>'Text align',
        'text_align_left'=>'left-aligned',
        'text_align_center'=>'centered',
        'text_align_right'=>'right-aligned',
        'text_align_block'=>'justified',
        'line_height'=>'Line-height',
        'font_style'=>'Font style',
        'shadow'=>'Shadow',
        'apply'=>'apply',
        'variance'=>'Variance',
        'distance'=>'Distance',
        'outline'=>'Outline',
        'width'=>'Width',
        'height'=>'Height',
        'text_background'=>'Text background',
        'work_space'=>'Working space',
        'reset'=>'Reset',
        'print_image'=>'Create image',
        'position'=>'Position',
        'left'=>'Left',
        'top'=>'Top',
        'color'=>'Color',
        'new_textfield'=>'New Textfield',
        'browser_warning'=>"This browser doesn\'t provide means to serialize canvas to an image",
        'nietzsche_quote'=>'Without music\nlife would be\na mistake',
    );
    
    private static $german = array(
        'site_title' => 'Online Editor zur Erstellung von Memes und Bildbotschaften',
        'site_description'=>'Erstellung von beeindruckenden Meme und Bildbotschaften zur Steigerung der Social Media Kommunikation',
        'layers'=>'Ebenen',
        'layer'=>'Ebene',
        'layer_new'=>'Neue Ebene',
        'layer_select_to_edit'=>'Zum Bearbeiten eine Ebene auswählen',
        'layer_selected'=>'Auswählte Ebene',
        'text'=>'Text',
        'image'=>'Bild',
        'image_url'=>'Bild-URL einfügen',
        'image_upload'=>'Bild hochladen',
        'image_upload_desc'=>'Nichts wird gespeichert auf unserem Server!',
        'image_load'=>'Bild laden',
        'image_sample'=>'Beispielbild',
        'search'=>'Durchsuchen...',
        'background_color'=>'Hintergrundfarbe',
        'layer_to_back'=>'Ebene in den Hintergrund',
        'layer_to_front'=>'Ebene in den Vordergrund',
        'opacity'=>'Transparenz',
        'font_color'=>'Schriftfarbe',
        'font_size'=>'Schriftgröße',
        'font_family'=>'Schriftfamilie',
        'text_align'=>'Ausrichtung',
        'text_align_left'=>'Linksbündig',
        'text_align_center'=>'Zentriert',
        'text_align_right'=>'Rechtsbündig',
        'text_align_block'=>'Blocksatz',
        'line_height'=>'Zeilenabstand',
        'font_style'=>'Stile',
        'shadow'=>'Schatten',
        'apply'=>'anwenden',
        'variance'=>'Streuung',
        'distance'=>'Abstand',
        'outline'=>'Kontur',
        'width'=>'Breite',
        'height'=>'Höhe',
        'text_background'=>'Texthintergrund',
        'work_space'=>'Arbeitsfläche',
        'reset'=>'Reset',
        'print_image'=>'Grafik erstellen',
        'position'=>'Position',
        'left'=>'Links',
        'top'=>'Oben',
        'color'=>'Farbe',
        'new_textfield'=>'Neues Textfeld',
        'browser_warning'=>"Dieser Browser unterstützt nicht das Serialisieren von SVG in ein Bild",
        'nietzsche_quote'=>'Ohne Musik\nwäre das Leben\nein Irrtum',
    );
    
    
    public static function getLanguage() {
        
        $ip =  $_SERVER['REMOTE_ADDR'];
        $country = file_get_contents('http://ipinfo.io/'.$ip.'/country');
        $defaultLanguage = trim(strtolower($country));
        
        self::$language = self::$english;
        if($defaultLanguage == 'de') {
            self::$language = self::$german; 
        }
    }
    
    public static function _($shortCut) {
        self::getLanguage();
        
        if(isset(self::$language[$shortCut])) {
            $shortCut = self::$language[$shortCut];
        }
        return $shortCut;
    }
    
}