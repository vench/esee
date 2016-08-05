<?php

define('VIEW_DEBAG', !true);

require './lib.php';

$file = '/home/vench/dev/see/esee/test-new/sq/1234.png';
$file = '/home/vench/dev/see/esee/test-new/circl/index.png';
$file = '/home/vench/dev/see/esee/test-new/photo.jpg';


$sing = getDirectionPath($file);
$length = strlen($sing);
if (empty($sing) || $length < MIN_TAG_LENGTH) {
    echo "...is empty\n";
    exit();
}

echo "{$file}\n";
echo "sig: " . $sing;
echo "\n";

$resultsPath = [];
for($i = 0; $i < $length - 1; $i ++) {
    $a = substr($sing, $i, 1);
    $b = substr($sing, $i + 1, 1);
            
    if(sizeof($paths = getPathsMax($a, $b, $i)) > 0) {
        do {
            $path = array_shift($paths);
            //next
            //var_dump($path); exit();
            $objId = $path['objId'];
            if(!isset($resultsPath[$objId])) {
                $resultsPath[$objId] = 0;
            }
            $j = $i;
            for(; $j < $length - 1; $j ++) {
                $a = substr($sing, $j, 1);
                $b = substr($sing, $j + 1, 1);
                $next = getPath($objId, $a, $b, $j); 
                if(is_null($next)) { 
                    break;//Идем другим путем - шаг назад
                } 
                $resultsPath[$objId] += ($j + 1);
            }
          // echo $i, '-',  $length, "\n";
            if($j == $length - 1) { //дошли до конца
               // echo " {$a}, {$b} \n";
                echo $objId, "\n";
                exit();
            } 
        } while(sizeof($paths) > 0);    
    } 
}

echo "--result--";
var_dump($resultsPath); 
echo "\n";