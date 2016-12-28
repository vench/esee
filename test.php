<?php

 
require './lib.php';


//clear trash
array_map(function($i){ return $i != '.' && $i != '..' ? unlink(IR_TMP. $i) : 0;}, scandir(IR_TMP));




$r = new ImageReader('./img/chek1.jpg');
//$r = new ImageReader('./img/x2.png'); 

$r = new ImageReader('./img/546.jpg');
/*$r = new ImageReader('./img/tants_04.jpg');
$r = new ImageReader('./img/wwq.png');
*/

//$r = new ImageReader('./img/c31.jpg');

$r = new ImageReader('./img/x2.png'); 

$words = ImageHelper::getWords($r);
$lib = LibChars::getInstance();
 //var_dump($words);
 echo "\n\n";
 
 $debug = true;
 
 $cou = 0;
 $maxCou = 0;
 $skeep = 155;
 
 $lineNum = -1;
 foreach($words as $n => $w) {
        //$w->render($r);
        $chars = ImageHelper::getChars($r, $w);
        
        
        if($lineNum != $w->lineNum) {
                echo "\n";
        } else {
                echo " ";
        }
        $lineNum = $w->lineNum;
        
        foreach($chars as $char) {
               // 
            
            
                if($skeep > 0) {
                    $skeep --;
                }
            
               $char->trim($r); 
               
               $find = $lib->find($char, $r);
               
               if(empty($find)) {
                        echo "=========", "\n";
                        echo "+\n";
                        ImageHelper::viewSymbol($char, $r); 
                        echo "==========", "\n"; //exit();  /**/
                        
               } else {
                
                //echo "\n";
                
                
                $v = $find[0][1];
                echo trim($v), "";
                
                if( $debug && $skeep == 0 && $v != '*') {
                        echo "\n";echo "\n";
                        var_dump( array_slice( $find, 0, 3) );
                        echo "\n";
                        ImageHelper::viewSymbol($char, $r); 
                        
                        echo ">>>>>>>>>>>>>>>>>", "\n";echo "\n";
                        
                       // exit();
                }
             
                 
               }
               
               
               
               if($debug && $skeep == 0 && $cou ++ > $maxCou) {
                         exit();
                 
               //$char->render($r);
               }
               
               
        }
        
        
        
        
        
 }


 echo "\n\n";


        
 
