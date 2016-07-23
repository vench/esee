<?php


define('OBJ_ID_CIRK', 1);
define('OBJ_ID_SQ', 2);

/**
* Минимальная длинна тэга
*/
define('MIN_TAG_LENGTH', 3);

if(!defined('VIEW_DEBAG')) {
	define('VIEW_DEBAG', true);
}

function isDark($rgb) { //var_dump($rgb);   
return $rgb == 0;// var_dump($rgb);
$r = ($rgb >> 16) & 0xFF;
$g = ($rgb >> 8) & 0xFF;
$b = $rgb & 0xFF;

// var_dump($r, $g, $b);
	return $r < 127 || $g < 127 || $b < 127;// == 0;
}

/**
* Получить путь картинки
*/
function getDirectionPath($filename) {
	$im = strpos($filename, 'png') !== false ?
		 imagecreatefrompng($filename) : imagecreatefromjpeg($filename);

	$width = imagesx($im);
	$height = imagesy($im);

	$directions = [];
	$point  = null;
	$points = [];

	for($y = 0; $y < $height; $y ++) {
		for($x = 0; $x < $width; $x ++) {
			 
		
			$a1 = isDark(imagecolorat($im, $x, $y));
			$a2 = ($width == $x + 1) ? $a1 : isDark(imagecolorat($im, $x + 1, $y));
			$a3 = ($height == $y + 1) ? $a1 : isDark(imagecolorat($im, $x, $y + 1));
		
			if($a1 !=  $a2 || $a1 !=  $a3) {
				if(VIEW_DEBAG) echo 1;  
				$points[] = [$x, $y];	
			 
			} else {
				if(VIEW_DEBAG) echo '.';
			}
		}
		if(VIEW_DEBAG) echo "\n";
	}
	imagedestroy($im); 


	usort($points, function($a, $b){
		$d = sqrt(pow(abs($a[0] - $b[0]), 2)  + pow(abs($a[1] - $b[1]), 2)); 
		return $a[1] > $b[1] ? -1 : 1;
	});
 
	 	
 	$a = null;
	while(sizeof($points) > 0) {
		if(is_null($a)) {
			$a = array_shift($points);
		} 
		$min = null;
		foreach($points as $k => $b) {
			$d = sqrt(pow(abs($a[0] - $b[0]), 2)  + pow(abs($a[1] - $b[1]), 2));
			if(is_null($min) || $min[0] > $d) {
				$min = [$d, $b, $k];
			} 
		}
		unset($points[$min[2]]); 

		$x = $min[1][0];
		$y = $min[1][1];
		
		$d = 0;
		if($a[0] < $x && $a[1] < $y) {
			$d = 1;//'BR';
		} else if($a[0] == $x && $a[1] < $y) {
			$d = 2;//'B';
		}  else if($a[0] < $x && $a[1] == $y) {
			$d = 3;//'R';
		} else if($a[0] < $x && $a[1] > $y) {
			$d = 4;//'TR';
		}else if($a[0] == $x && $a[1] > $y) {
			$d = 5;//'T';
		}else if($a[0] > $x && $a[1] > $y) {
			$d = 6;//'TL';
		}else if($a[0] > $x && $a[1] == $y) {
			$d = 7;//'L';
		} else if($a[0] > $x && $a[1] < $y) {
			$d = 8;//'BL';
		}
		if(sizeof($directions) == 0 || $directions[sizeof($directions) -1] != $d) {
			$directions[] = $d;
		}
 		$a = $min[1]; 
	}

 
	return join('', $directions);
}





//mysql

function getDb() {
	static $dbh = null;
	if(is_null($dbh)) {
		$dbh = new PDO('mysql:host=localhost;dbname=see', 'root', '');
	}
	return $dbh;
}



function registerTObject($objectId, $data) {
	$db = getDb(); 
	$sql = "INSERT INTO object_templ (object_id, data) VALUES (?,?)";
	$stm = $db->prepare($sql);
	$stm->execute([$objectId, $data]);
	 return $db->lastInsertId();
}



function getHashTObjects($objectId) {
	$db = getDb(); 

	$sth = $db->prepare("SELECT id,data FROM object_templ WHERE object_id=?");
	$sth->execute([$objectId]); 

	$hash = [];
	foreach($sth->fetchAll() as $row) {
		$hash[$row['id']] = $row['data'];
	}

	return $hash;
}




function findTags($s, $a) {
	if(VIEW_DEBAG)  echo $s . "\n\n";
	$findAll = [];		
	for($i = 0; $i < strlen($s); $i ++){
		$n = MIN_TAG_LENGTH;
		$findOk;
		$find = [];
		do {
			$findOk = $find;
			$find = [];
			$p = substr($s, $i, $n ++);
			foreach($a as $k=>$s1) {
				if(strpos($s1, $p) !== false) {
					$find[$k]=$p;
				}
			}
			 	
		} while(!empty($find) && $i + $n < strlen($s)); 
		 
		foreach($findOk as $id => $tag) {
			$findAll[] = [$id, $tag];
		}
	}

	return $findAll;	
}

/**
*/
function getTagHash() {
	$db = getDb(); 

	$sth = $db->prepare("SELECT id,data FROM tag");
	$sth->execute(); 

	$hash = [];
	foreach($sth->fetchAll() as $row) {
		$hash[$row['data']] = $row['id'];
	}

	return $hash;
}


function insertTag($data) {
	$db = getDb(); 
	$sql = "INSERT INTO tag (data) VALUES (?)";
	$stm = $db->prepare($sql);
	$stm->execute([$data]);
	 return $db->lastInsertId();
}

function insertTagToTempl($objectTemplId, $tagId) {
	$db = getDb(); 
	$sql = "INSERT IGNORE INTO tag_object_templ (object_templ_id, tag_id) VALUES (?,?)";
	$stm = $db->prepare($sql);
	$stm->execute([$objectTemplId, $tagId]); 
}

