<?php


namespace esee;

require_once 'esee/App.php';
App::autoload();

$image = new Image('../x1.jpg');
$image->open();

$diff = 16777215 / 2;
 
$provider = new provider\ProviderFile('data.txt');
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
                        list($ax, $ay) = Helper::avg	($c) ; 
                        $char = $provider->findByXY($ax, $ay);
                        if(!is_null($char)) {
                            echo "Find: {$char->char}\n"; exit();
                        } else {
                            echo "No find: {$ax}, {$ay}\n";
                        }
			array_push($ar, $c); 
		 }
 
	} 
}

//print_r($ar); 
//print_r($points);


$image->close();


