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
echo "\n\n";

$debug = !true;

$step = 10011;

$lineNum = -1;
foreach ($words as $n => $w) {
    $w->render($r);
    $chars = ImageHelper::getChars($r, $w);


    if ($lineNum != $w->lineNum) {
        echo "\n";
    } else {
        echo " ";
    }
    $lineNum = $w->lineNum;

    foreach ($chars as $char) {
        
        if($step -- == 0) exit();
        $char->trim($r);
        
        //TODO
        if($char->x1 == $char->x2) {
            continue;
        }
        

        $find = $lib->find($char, $r);
        
        if (empty($find)) {
            echo "=========", "\n";
            echo "{$char->x1}; {$char->x2}+\n";
            ImageHelper::viewSymbol($char, $r);
            echo "==========", "\n";
        } else { 
            $v = $find[0][1];
            echo trim($v), "";

            if ($debug && $v != '*') {
                echo "\n";
                echo "\n";
                var_dump(array_slice($find, 0, 3));
                echo "\n";
                ImageHelper::viewSymbol($char, $r);

                echo ">>>>>>>>>>>>>>>>>", "\n";
                echo "\n";

                // exit();
            }
        }
    }
}


echo "\n\n";




