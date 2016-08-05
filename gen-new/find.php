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


$oldPath = [];
for($i = 0; $i < $length; $i ++) {
    $a = substr($sing, $i, 1);
    $b = substr($sing, $i + 1, 1);
            
    if(sizeof($paths = getPathsMax($a, $b, $i)) > 0) {
        do {
            $path = array_shift($paths);
            //next
            //var_dump($path); exit();
            $objId = $path['objId'];
            
            for($j = $i; $j < $length; $j ++) {
                $a = substr($sing, $j, 1);
                $b = substr($sing, $j + 1, 1);
                $next = getPath($objId, $a, $b, $j);//TODO игнорируем ужже пройденые шаги
                if(is_null($next)) {
                    $j --;
                    break;//Идем другим путем - шаг назад
                }
                $oldPath[] = $next['pathId'];
            }
           
            if($j == $length - 1) { //дошли до конца
                echo $objId;
                exit();
            } else {
                
            }
        } while(sizeof($paths) > 0);    
    } 
}