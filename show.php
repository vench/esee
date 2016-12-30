<?php

require './lib.php';


//clear trash
array_map(function($i) {
    return $i != '.' && $i != '..' ? unlink(IR_TMP . $i) : 0;
}, scandir(IR_TMP));






$r = new ImageReader('./img/bs2.png');

$words = ImageHelper::getWords($r);
$lib = LibChars::getInstance();
//var_dump($words);
echo PHP_EOL;

$debug = !true;

$step = 10011;
 
foreach ($words as $n => $w) {
    //$w->render($r);
    $chars = ImageHelper::getChars($r, $w);
 

    foreach ($chars as $char) {
        
        if($step -- == 0) exit();
        $char->trim($r);
        
        //TODO
        if($char->x1 == $char->x2) {
            continue;
        }
        
        ImageHelper::viewSymbol($char, $r); 
        echo  "^X;", PHP_EOL;
    }
}

 




