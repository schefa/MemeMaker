<?php

define('_EXEC', 1);

$text = "Ohne Musik\\nwäre das Leben\\nein Irrtum"; 
$username ="Nietzsche";
$extern = false;

$vars = $_GET;
if(!empty($vars['id']) && !empty($vars['cid']) && !empty($vars['key'])) {
    $url = "http://www.denkschatz.de/index.php?option=com_denkschatz&view=item&format=json&id=".intval($vars['id'])."&cid=".intval($vars['cid'])."&c=".($vars['key']);
    $json = file_get_contents($url);
    $data = json_decode($json,true);
    
    if(isset($data['item']['text'])) {
        $text = htmlspecialchars($data['item']['text']);
        $username = htmlspecialchars($data['item']['username']);
        $extern = true;
    }
} 

$text = utf8_encode($text);
$username = utf8_encode($username);

$width = 640;
$height = 480;
$bgimageResult = "images/cover/".rand (1,14).".jpg";

include('classes/Language.php');
require_once('view.php');
?>