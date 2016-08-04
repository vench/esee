<?php

define('VIEW_DEBAG', !true);

require './lib.php';

$file = 'xxxx';

$sing = getDirectionPath($file);
$length = strlen($sing);
if (empty($sing) || $length < MIN_TAG_LENGTH) {
    echo "...is empty\n";
    exit();
}


$oldPath = null;
for($i = 0; $i < $length; $i ++) {
            $a = substr($sing, $i, 1);
            $b = substr($sing, $i + 1, 1);
            
            if(!is_null($path = getPathX($a, $b, $i))) {
                var_dump($path);
                exit();
            } 
}