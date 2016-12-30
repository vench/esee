<?php

define('IR_TMP', './tmp/');
define('IR_DATA', './data/');

 


        


class LibChars {

        private static $inst = null; 
        
        private $hash = [];
        
        private function __construct(){}
        
        
        public static function getInstance() {
                if(is_null(self::$inst)) {
                        self::$inst = new static();
                }
                return self::$inst;
        }
        
        
        public function find(Symbol $s, ImageReader $r){
                $p = $s->getPointsList($r);
                $data = $this->getData($s->x2 - $s->x1, $s->y2 - $s->y1);
                
                $find = [];
                
                foreach($data as $node) {
                        $w = Node::getWeight($p, $node);
                        if($w > 0) {
                                $find[] = [$w, $node->title];
                        }
                
                } 
                
                usort($find, function($a,$b){ return ($a[0] < $b[0]) ? 1 : -1;});
               
               return $find ;
        }
        
        public function findByTitle($title, $w, $h){
                $data = $this->getData($w, $h);
                foreach($data as $n) {
                        if($n->title == $title) { 
                                return ($n instanceof Node)  ? $n : Node::instance($n);
                        }
                }
                return null;
        }
        
        
        public function saveNode(Node $node) {
        
                $data = $this->getData($node->width, $node->height);
                $find = false;
                foreach($data as$k => $n) {
                        if($n->title == $node->title) {
                                $data[$k] = $node;
                                $find = true;
                                break;
                        }
                }
                
                if(!$find) {
                        $data[] = $node;
                }
                
                $file = IR_DATA . $node->width .'x'. $node->height . '.json';
                $this->hash[$file] = $data;
                
                file_put_contents($file, json_encode($data) );
        }
        
        /**
        * @return Node[]
        */
        public function getData($w, $h) {
                $file = IR_DATA . $w .'x'. $h . '.json';
                if(!isset($this->hash[$file])) {
                        $data = file_exists($file) ? file_get_contents($file) : "";
                        $this->hash[$file] = !empty($data) ? json_decode($data) : [];
                        
                        $sum = [];
                        foreach($this->hash[$file] as $n) {
                                foreach($n->weights as $k => $v) {
                                        if(!isset($sum[$k])) {
                                                $sum[$k] = 0;
                                        }
                                        $sum[$k] += $v;
                                }
                        }
                        
                        foreach($this->hash[$file] as $n) {
                                $n->staticSum = $sum;
                        }
                        
                }
                
                return $this->hash[$file];
        }
        
}

/**
 * 
 */
class Node {
	public $title; 
	public $weights;
	public $learn = 0;
	public $width;
	public $height; 
	public $staticSum = [];
	
        /**
         * 
         * @param Node $proto
         * @return Node
         */
	public static function instance($proto) {
	        $n = new Node();
	        $n->title = $proto->title;
	        $n->weights = $proto->weights;
	        $n->learn = $proto->learn;
	        $n->width = $proto->width;
	        $n->height = $proto->height;
	        return $n;
	}
	
        /**
         * 
         * @param array $points int[]
         * @param Node $n
         * @return Node
         * @throws \Exception
         */
	public static function updateWeight(array $points, Node $n) {
	        if(count($points) != count($n->weights)) {
		        throw new \Exception("Not equal size: " .count($points) . ", " . count($n->weights));
	        }

	        $n->learn ++; 
	        for($i = 0; $i < count($points); $i ++) {
		        if(isset($n->weights[$i]) && $points[$i]  > 0) {
			        $n->weights[$i] ++;
		        }
	        }
	        return $n;
        }
        
        /**
         * 
         * @param array $points
         * @param Node $n
         * @param type $totalCount
         * @return int
         * @throws \Exception
         */
        public static function getWeight(array $points,  $n, $totalCount = null) {
	        if(count($points) != count($n->weights)) {
		        throw new \Exception("Not equal size");
	        }

                
                
                $weight = 0; 
                
                for($i = 0; $i < count($points); $i ++) {
                        if(isset($n->weights[$i]) && $n->weights[$i] > 0 && $points[$i]  > 0) {
                        
                                if(isset($n->staticSum[$i])) {
                                       $weight +=($n->weights[$i] / $n->staticSum[$i] )/ $n->learn; //$n->weights[$i] / $n->learn * ($n->weights[$i] / $n->staticSum[$i] ); 
                                } else {
                                        $weight += 1;//
                                }
                        } else if(isset($n->weights[$i]) && $n->weights[$i] > 0 && $n->weights[$i] == 0 && $points[$i]  == 0) { 
                                if(isset($n->staticSum[$i])) {
                                       $weight += 1 / $n->staticSum[$i]; 
                                } else {
                                        $weight += 1;//
                                }
                        }
                }
                
                return $weight;
                /*

	        $weight = 0; 
	        $black = 0;
	       
	        for($i = 0; $i < count($points); $i ++) {
		        if(isset($n->weights[$i]) && $n->weights[$i] > 0 && $points[$i]  > 0) {
			        $weight += ($n->weights[$i] / $n->learn); 
		        } else if(isset($n->weights[$i]) && $n->weights[$i] == 0 && $points[$i] == 0) {
			        //$weight +=  $black / count($points);
		        }
		        $black += ($points[$i]  > 0) ? 1 : 0;
	        }
	        return $weight  * (  $black / count($points));*/
        }
}


class Symbol {
        public $x1 = 0;
        public $x2 = 0;
        public $y1 = 0;
        public $y2 = 0;
        public $lineNum = 0;
        public $num = 0;
        
        private $points = null;
        
        public function render(ImageReader $r){
                $width = $this->x2 - $this->x1 ;
                $height = $this->y2 - $this->y1 ;
                $w = new ImageWrier($width, $height);
                $red = $w->getRedColor();
                for($y = 0; $y < $height; $y ++){
                        for($x = 0; $x < $width; $x ++) {
                                $color =  ImageHelper::isWhite($r,$x+$this->x1,$y+$this->y1)  ? 0 : $red;
                                $w->setPixel($x, $y, $color);
                        }
                } 
                $w->save(IR_TMP.$this->y1.'_'.$this->x1. '.jpg');
        }
        
        /**
        * @reurn array
        */
        public function getPoints(ImageReader $r) {
                if(is_null($this->points)) {
                        $width = $this->x2 - $this->x1 ;
                        $height = $this->y2 - $this->y1 ;
                          
                        $p = [];
                        for($y = 0; $y < $height; $y ++){
                                for($x = 0; $x < $width; $x ++) {
                                
                                        $color =  ImageHelper::isWhite($r,$x+$this->x1,$y+$this->y1)  ? 0 : 1;
                                        $p[$y + $this->y1][$x + $this->x1] = $color;
                                }
                        }   
                        
                        $this->points = $p;
                }
                return $this->points;
        }
        
        /**
        * @reurn array
        */
        public function getPointsList(ImageReader $r) {
                $this->trim($r);
                $p = $this->getPoints($r);
                $l = [];
                
                foreach($p as $y => $c) {
                        foreach($c as $x => $v) {
                                $l[] = $v;
                        }
                }
                
                return $l;
        }
        
        
        public function trim(ImageReader $r) {
                $this->points = null;
                
                $width = $this->x2 - $this->x1 ; 
                //top
                for(; $this->y1 < $this->y2; $this->y1 ++){
                      $count = 0;   
                      for($x = 0; $x < $width; $x ++) {
                           $count +=  ImageHelper::isWhite($r,$x+$this->x1,$this->y1)  ? 0 : 1; 
                      }
                      
                      if($count > 0) {
                                break;
                      } 
               }   
               //both
               /* */
               //var_dump($this->y2);
               for(; $this->y1 < $this->y2; $this->y2 --){
                      $count = 0;   
                      for($x = 0; $x < $width; $x ++) {
                           if(!ImageHelper::isWhite($r,$x+$this->x1,$this->y2-1) ) {
                                $count = 1;
                                break;
                           }
                      }
                     // var_dump($this->y2);
                      if($count > 0) {
                                break;
                      } 
               }
              //exit();
               //
               $height = $this->y2 - $this->y1 ;
               //left
                for(; $this->x1 < $this->x2; $this->x1 ++){
                      $count = 0;   
                      for($y = 0; $y < $height; $y ++) {
                           if(!ImageHelper::isWhite($r,$this->x1,$y+$this->y1) && (
                                !ImageHelper::isWhite($r,$this->x1 + 1,$y+$this->y1) || 
                                !ImageHelper::isWhite($r,$this->x1,$y+$this->y1 + 1) ||
                                !ImageHelper::isWhite($r,$this->x1,$y+$this->y1 - 1) ||
                                !ImageHelper::isWhite($r,$this->x1 + 1,$y+$this->y1 +1) || 
                                !ImageHelper::isWhite($r,$this->x1 + 1,$y+$this->y1 - 1) 
                            )) {
                                $count = 1;
                                break;
                           } 
                      }
                      
                      if($count > 0) {
                                break;
                      } 
               }
               //right
               /**/
               for(; $this->x1 < $this->x2; $this->x2 --){
                      $count = 0;   
                      for($y = 0; $y < $height; $y ++) {
                           if(!ImageHelper::isWhite($r,$this->x2-1,$y+$this->y1)) {
                                $count = 1;
                                break;
                           } 
                      }
                      
                      if($count > 0) {
                                break;
                      } 
               }
        }
        
}

class ImageReader {
        private $filename;
        private $img;
        
        public function __construct($filename){ $this->filename = $filename;}
        public function __destruct() {/*TODO */}
        public function getImage() { 
                if(is_null($this->img)) {
                
                        $this->img = strpos($this->filename, '.png') ? imagecreatefrompng($this->filename)
                                : imagecreatefromjpeg($this->filename);
                }
                return $this->img;        
        }
        public function colorAt($x, $y) { return imagecolorat ( $this->getImage(), $x, $y );}   
        public function colorAtRed($x, $y) { return ($this->colorAt($x, $y)  >> 16) & 0xFF; } 
        public function colorAtGreen($x, $y) { return ($this->colorAt($x, $y)  >> 8) & 0xFF; } 
        public function colorAtBlue($x, $y) { return ($this->colorAt($x, $y) ) & 0xFF; } 
        public function getWidth(){ return imagesx ($this->getImage()) ;} 
        public function getHeight(){ return imagesy ($this->getImage()) ;}    
}
 
 
 
class ImageWrier {
        private $width;
        private $height;        
        private $img;
        
        public function __construct($width, $height){ $this->width = $width; $this->height = $height;}
        public function __destruct() { /*TODO */}
        public function getImage() { 
                if(is_null($this->img)) {
                        $this->img = imagecreatetruecolor($this->width, $this->height);
                }
                return $this->img;        
        }
        public function getRedColor() { return imagecolorallocate($this->getImage(), 255, 0, 0); }
        public function save($filename) { imagejpeg($this->getImage(), $filename); }
        public function setPixel($x, $y, $color) { return imagesetpixel ( $this->getImage(), $x, $y, $color );}
        
        
} 



class ImageHelper { 


    
     public static function getChars(ImageReader $r, Symbol $s){
          
         $chars  = self::getChars2($r, $s);
         
         
         //todo
         
          return $chars; //array_filter($chars, function($c) { return $c->x1 != $c->x2; });
        
         $testOne = function(Symbol $char1, Symbol $char2, $r){
              
             
             if($char1->x2 != $char2->x1) {
                 return false;
             }
             
             $p1 = $char1->getPoints($r);
             $p2 = $char2->getPoints($r);
             
             if(empty($p1) || empty($p2)) {
                  return false;
             }
             
             $x1 = $char1->x2;//get last x
            if($char1->x2 == 100) {
                ImageHelper::viewSymbol($char1, $r);
                ImageHelper::viewSymbol($char2, $r); exit();
            }
             
             $sum = 0;
             foreach ($p1 as $y1 => $d1) {  
                     if($d1[$x1] == 1 && isset($p2[$y1][$x1+1]) && $p2[$y1][$x1+1] == $d1[$x1]) {
                         $sum ++;
                     }  
             }
             
             return $sum > 0;
         }
         ;
         
         foreach($chars as $k => $char) {
              
             $char->trim($r);
               
               if($char->x1 - $char->x2 == 0) { //echo $char->x2, ' ', $char->x1; echo PHP_EOL; 
                  unset($chars[$k]);
                  continue;
              }
         } 
         
         //return $chars;
         $chars = array_values($chars);
         $optimize = [];
         for($n = 0; $n < count($chars); $n ++) {
             
              
             
              $last =  count($optimize) > 0 ? $optimize[count($optimize) -1] : null;
             
             if(!is_null($last) && $testOne($last,$chars[$n], $r)) {
                // echo 'X: ', $chars[$n]->x2 , ' ', $chars[$n+1]->x1; echo PHP_EOL; exit();
                  
                $last->x2 = $chars[$n]->x2;
                // $optimize[] = $chars[$n];
                  
         } else {//if($n == 0 || $chars[$n]->x1 < $optimize[count($optimize) -1]->x2) {
                 $optimize[] = $chars[$n];
             }
             
         }
         return $optimize;
     }
    
        public static function getChars2(ImageReader $r, Symbol $s){
               $chars = [];
               $hashPoints = $s->getPoints($r);
              // echo ">", $s->x1, PHP_EOL; 
               $lastPoints = [];
               $fCheck = function($x, $y)use(&$lastPoints){                  // var_dump($lastPoints);
               /*
                        return (isset($hashPoints[$y][$x+1]) && $hashPoints[$y][$x+1] == 1) ||
                        (isset($hashPoints[$y+1][$x]) && $hashPoints[$y+1][$x] == 1) ||
                        (isset($hashPoints[$y+1][$x+1]) && $hashPoints[$y+1][$x+1] == 1) ||
                        (isset($hashPoints[$y+1][$x-1]) && $hashPoints[$y+1][$x-1] == 1) ||
                        (isset($hashPoints[$y-1][$x+1]) && $hashPoints[$y-1][$x+1] == 1) ||
                        (isset($hashPoints[$y-1][$x]) && $hashPoints[$y-1][$x] == 1) ||
                        (isset($hashPoints[$y][$x-1]) && $hashPoints[$y][$x-1] == 1) ||
                        (isset($hashPoints[$y-1][$x-1]) && $hashPoints[$y-1][$x-1] == 1) ;*/
                   return (isset($lastPoints[$y][$x+1]) && $lastPoints[$y][$x+1] == 1) ||
                        (isset($lastPoints[$y+1][$x]) && $lastPoints[$y+1][$x] == 1) ||
                        (isset($lastPoints[$y+1][$x+1]) && $lastPoints[$y+1][$x+1] == 1) ||
                        (isset($lastPoints[$y+1][$x-1]) && $lastPoints[$y+1][$x-1] == 1) ||
                        (isset($lastPoints[$y-1][$x+1]) && $lastPoints[$y-1][$x+1] == 1) ||
                        (isset($lastPoints[$y-1][$x]) && $lastPoints[$y-1][$x] == 1) ||
                        (isset($lastPoints[$y][$x-1]) && $lastPoints[$y][$x-1] == 1) ||
                        (isset($lastPoints[$y-1][$x-1]) && $lastPoints[$y-1][$x-1] == 1) ||
                   
                   (isset($lastPoints[$y-1][$x-2]) && $lastPoints[$y-1][$x-2] == 1)
                   ||
                   (isset($lastPoints[$y+1][$x-2]) && $lastPoints[$y+1][$x-2] == 1)
                   ||
                   (isset($lastPoints[$y][$x-2]) && $lastPoints[$y][$x-2] == 1)
                   ||
                   (isset($lastPoints[$y-2][$x-1]) && $lastPoints[$y-2][$x-1] == 1)
                   ||
                   (isset($lastPoints[$y+2][$x-1]) && $lastPoints[$y+2][$x-1] == 1) ;
               };
               
               
               for($x = 0; $x < $s->x2 - $s->x1; $x++){
                        $count =0;
                    
                    for($y = 0; $y < $s->y2 - $s->y1; $y++){
                        $value =  $hashPoints[$y+$s->y1][$x+$s->x1] == 1 && $fCheck($x+$s->x1, $y+$s->y1) ? 1 : 0;
                        
                        $count += $value;
                    }  
                    
                    if($s->x1 == 89) {
                      //  echo '>> ', $count , PHP_EOL;
                    }
                    //if($count <=  (($s->y2 - $s->y1) * .1)) {
                    if($count <=  0) {
                        $cs = clone $s;
                        $cs->x1 = count($chars) ? $chars[count($chars)-1]->x2 : $s->x1;
                        $cs->x2 = $x+$s->x1;
                        
                        if($cs->x2 - $cs->x1 > 1) {
                                $chars[] = $cs;
                                  
                        }
                        
                    }   
                    
                    $lastPoints = [];
                    for($y = 0; $y < $s->y2 - $s->y1; $y++){
                        if(isset($lastPoints[$y+$s->y1][$x+$s->x1-1])) {
                            $lastPoints[$y+$s->y1][$x+$s->x1-1] = $hashPoints[$y+$s->y1][$x+$s->x1-1];
                        }    
                        $lastPoints[$y+$s->y1][$x+$s->x1] = $hashPoints[$y+$s->y1][$x+$s->x1];
                    }
                     
                    
                    
                    
               }
              // exit();
               
               return $chars; 
        }
        
        /**
         * 
         * @param ImageReader $r
         * @param Symbol $s
         * @return \Symbol
         * @deprecated since version number
         * @todo remove
         */
        public static function getChars1(ImageReader $r, Symbol $s){
                $chars = [];
                $hashPoints = $s->getPoints($r);
                $see = [];
                $chain = function($x, $y, &$ch = null) use(&$hashPoints, &$see, &$chain){
                        if(isset($see[$y][$x]) || !isset($hashPoints[$y][$x]) || $hashPoints[$y][$x] == 0) {
                                return null;
                        }
                        
                        $see[$y][$x] = 1;
                        
                        if(is_null($ch)) {
                                $ch = new Chain();
                        }
                        $ch->addPoint($x, $y);
                        $chain($x+1, $y, $ch);
                        $chain($x, $y+1, $ch);
                        $chain($x+1, $y+1, $ch);
                        $chain($x-1, $y, $ch);
                        $chain($x, $y-1, $ch);
                        $chain($x-1, $y-1, $ch);
                        $chain($x+1, $y-1, $ch);
                        $chain($x-1, $y+1, $ch);
                        
                        return $ch;
                
                };
             //   var_dump($hashPoints);
                $xx = [];
                
                foreach($hashPoints as $y => $line){
                        foreach($line as $x => $v) {
                                if(!is_null($c = $chain($x, $y)) && !isset($xx[$c->maxX])) {
                                       $xx[$c->maxX] = $c->maxX;  
                                        //view
                echo "+++++++++++++++++++++\n\n";
                ImageHelper::viewChain($c); 
                                }
                        }
                }
                
                sort( $xx);
                $sm = [];
                foreach($xx  as $n=>$x) {
                        $cs = clone $s;
                        $cs->x1 = isset($xx[$n-1]) ? $xx[$n-1] : $s->x1;
                       $cs->x2 = $x;
                       $sm[] = $cs;
                }
                
               // return $chars;
               return $sm;
                
        }
        


        /**
        * @param ImageReader $r
        * @return boolean
        */
        public static function isWhite(ImageReader $r,$x,$y){
                $limit = 200; 
                return $r->colorAtRed($x,$y) > $limit || $r->colorAtGreen($x,$y) > $limit || $r->colorAtBlue($x,$y) > $limit;
        }

        /**
        * @param ImageReader $r
        * @return Symbol[] 
        */
        public static function getWords(ImageReader $r) {
                $words = [];
                $beginerY = ImageHelper::getLines($r);
                foreach($beginerY as $num => $a) {
                        list($y1, $y2) = $a;
                        $height = $y2 - $y1;
        
                        $beginer = [];
                        $beginerIndex = 0;
                        $rHeight = 0;//ceil($height * .25);
                        $limit = 3;//?
 
                        for($x = 0; $x < $r->getWidth(); $x ++) {
                                $counter  = 0;
                                for($y = 0; $y <  $height; $y ++) { 
                                        $counter += ImageHelper::isWhite($r,$x,$y+$y1) ? 0 : 1;
                                } 
                
                                if(!isset($beginer[$beginerIndex])) {
                                        $beginer[$beginerIndex] = [$x, $x, 0];
                                } else if($counter <=  $rHeight ) { //zero
                                        $beginer[$beginerIndex][1] = $x;
                        
                                        $beginer[$beginerIndex][2] ++;
                                        if($beginer[$beginerIndex][2] > $limit ) {
                                                $beginerIndex ++;
                                        }         
                        
                                } else {
                                        $beginer[$beginerIndex][1] = $x;
                                        $beginer[$beginerIndex][2] = 0;
                                }     
                        }
                        $beginer = array_filter($beginer, function($a) use(&$limit ){ return $a[1] - $a[0] > $limit + 1;});
       
                        foreach($beginer as $m) {
                                list($x1,$x2) = $m;
                                $s = new Symbol();
                                $s->x1 = $x1;
                                $s->x2 = $x2;
                                $s->y1 = $y1;
                                $s->y2 = $y2; 
                                $s->lineNum = $num ;
                                $s->num = count($words) + 1;
                                
                                $words[] = $s;       
                              // exit();
                        }  
                }
                
                return $words;                           
        }


        public static function getLines(ImageReader $r){
        $beginer = [];
        $beginerIndex = 0;
        $rWidth = $r->getWidth() * .03;
        $limit = 3;


        for($y = 0; $y < $r->getHeight(); $y ++) {
                $counter  = 0;
                for($x = 0; $x < $r->getWidth(); $x ++) {
                        $counter += self::isWhite($r,$x,$y) ? 0 : 1;
                } 
                
                if(!isset($beginer[$beginerIndex])) {
                        $beginer[$beginerIndex] = [$y, $y, 0];
                } else if($counter <=  $rWidth ) { //zero
                        $beginer[$beginerIndex][1] = $y;
                        
                        $beginer[$beginerIndex][2] ++;
                        if($beginer[$beginerIndex][2] > $limit ) {
                                 $beginerIndex ++;
                        }         
                        
                } else {
                        $beginer[$beginerIndex][1] = $y;
                        $beginer[$beginerIndex][2] = 0;
                }
                
        }

        $beginer = array_filter($beginer, function($a) use(&$limit ){ return $a[1] - $a[0] > $limit + 1;});
        return ($beginer);
        }
        
        /**
	* @param Chain $c
	*/
	public static function viewChain(Chain $c) { 
		for($y = 0; $y <= ($c->maxY - $c->minY); $y ++){
			for($x = 0; $x <= ($c->maxX - $c->minX); $x ++){
				echo isset($c->p[$y + $c->minY][$x + $c->minX]) ?  '#' : '.';
			} 
			echo "\n";
		} 
	}
	
	/**
	* @param Symbol $s
	*/
	public static function viewSymbol(Symbol $s, ImageReader $r) { 
	        $p = $s->getPoints($r); //var_dump($p ); exit();
		for($y = 0; $y < ($s->y2 - $s->y1); $y ++){
			for($x = 0; $x < ($s->x2 - $s->x1); $x ++){
				echo isset($p[$y + $s->y1][$x + $s->x1]) && $p[$y + $s->y1][$x + $s->x1] ?  '#' : '.';
			} 
			echo "\n";
		} 
	}
}



class Chain {
	public $minX = null;
	public $maxX = null;
	public $minY = null;
	public $maxY = null;	

	/**
	* @var array points [[x,y], [...]]
	*/
	public $p = [];

	/**
	* @var array optimize points [[x,y], [...]]
	*/
	public $op = [];

	/**
	* @param int $x
	* @param int $y
	*/
	public function addPoint($x, $y) {
		$this->p[$y][$x] = 1;
		$this->op[] = [$x, $y];
		if(is_null($this->minX) || $this->minX > $x) {
			$this->minX = $x;
		}
		if(is_null($this->maxX) || $this->maxX < $x) {
			$this->maxX = $x;
		}
		if(is_null($this->minY) || $this->minY > $y) {
			$this->minY = $y;
		}
		if(is_null($this->maxY) || $this->maxY < $y) {
			$this->maxY = $y;
		}
	}


	public function getHeight() {
		return ($this->maxY - $this->minY);
	}

	public function getWidth() {
		return ($this->maxX - $this->minX);
	}

	public function getPointsList() {
		$res = [];
	echo ($this->getWidth()) . ' ' . ($this->getHeight()) . ' ' . "\n";
	for($y = 0; $y <= ($this->getHeight()); $y ++){
			for($x = 0; $x <= ($this->getWidth()); $x ++){
				$res[] = isset($this->p[$y + $this->minY][$x + $this->minX]) ?  1 : 0;
			}  
		}
		return $res;
	}
}
