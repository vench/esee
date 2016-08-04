<?php

define('VIEW_DEBAG', !true); 

require './lib.php';

$dirFiles = './../test-new/circl/'; ////circl/';
$objId = OBJ_ID_CIRCLE;

$sacn = scandir($dirFiles); 
foreach($sacn as $file) {
	if($file == '.' || $file == '..') {
		continue;
	}
	echo $file . "\n";
	$sing = getDirectionPath($dirFiles.$file);
        $length = strlen($sing);
	if(empty($sing) || $length < MIN_TAG_LENGTH) {
		echo "...is empty\n";
		continue;
        }
        
	echo $sing;
	echo "\n";

	
        for($i = 0; $i < $length; $i ++) {
            $a = substr($sing, $i, 1);
            $b = substr($sing, $i + 1, 1);
            
            if(!is_null($path = getPath($objId, $a, $b, $i))) {
                updatePathWeight($path['pathId']);
            } else {
                insertPath($objId, $a, $b, $i);
            }
            
        }  
}