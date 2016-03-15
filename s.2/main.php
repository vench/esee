<?php


namespace esee;

spl_autoload_register(function($className){  
    if(class_exists($className)) {
        return true;
    }
    $fileName = str_replace('\\', '/', $className) . '.php';
    include_once $fileName;
});

$image = new Image('../x2.png');
$image->open();

$diff = 16777215 / 2;
 

$chainBuilder = new ChainBuilder($image, $diff);
$ar = [];
 
 for($y = 0; $y < $image->getHeight(); $y ++) { 
	for($x = 0; $x < $image->getWidth(); $x ++) { 
           
		if($chainBuilder->getValue($x, $y) == 1){
			$c = $chainBuilder->makeChain($x, $y);
			if(is_null($c)) {
				continue;
			} 
			Helper::view2	($c);	
                        print_r( Helper::avg	($c) );     
			array_push($ar, $c); 
		 }
 
	} 
}

//print_r($ar); 
//print_r($points);


$image->close();


