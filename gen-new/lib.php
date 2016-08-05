<?php

define('OBJ_ID_CIRCLE', 1);
define('OBJ_ID_SQ', 2);

/**
 * Минимальная длинна для разбора
 */
define('MIN_TAG_LENGTH', 3);

if (!defined('VIEW_DEBAG')) {
    define('VIEW_DEBAG', true);
}


if (!defined('DB_STR_CONN')) {
    define('DB_STR_CONN', 'mysql:host=localhost;dbname=testdb');
}

if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}  

if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', 'admin');
}
/**
 * 
 * @param type $rgb
 * @return boolean
 */
function isDark($rgb) { //var_dump($rgb);   
    return $rgb == 0; // var_dump($rgb);
    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = $rgb & 0xFF;

// var_dump($r, $g, $b);
    return $r < 127 || $g < 127 || $b < 127; // == 0;
}

/**
 * Получить путь картинки
 * @param string $filename
 * @return string  $directions
 */
function getDirectionPath($filename) {
    if(!file_exists($filename)) {
        throw new \Exception('File {'.$filename.'} not found');
    } 
    $im = strpos($filename, 'png') !== false ?
            imagecreatefrompng($filename) : imagecreatefromjpeg($filename);

    $width = imagesx($im);
    $height = imagesy($im);

    $directions = [];
    
    $points = [];

    for ($y = 0; $y < $height; $y ++) {
        for ($x = 0; $x < $width; $x ++) { 
            //TODO chcnge to check color
            $a1 = isDark(imagecolorat($im, $x, $y));
            $a2 = ($width == $x + 1) ? $a1 : isDark(imagecolorat($im, $x + 1, $y));
            $a3 = ($height == $y + 1) ? $a1 : isDark(imagecolorat($im, $x, $y + 1));

            if ($a1 != $a2 || $a1 != $a3) {
                if (VIEW_DEBAG)
                    echo 1;
                $points[] = [$x, $y];
            } else {
                if (VIEW_DEBAG)
                    echo '.';
            }
        }
        if (VIEW_DEBAG)
            echo "\n";
    }
    imagedestroy($im);


    usort($points, function($a, $b) {
        $d = sqrt(pow(abs($a[0] - $b[0]), 2) + pow(abs($a[1] - $b[1]), 2));
        return $a[1] > $b[1] ? -1 : 1;
    });


    $a = null;
    while (sizeof($points) > 0) {
        if (is_null($a)) {
            $a = array_shift($points);
        }
        $min = null;
        foreach ($points as $k => $b) {
            $d = sqrt(pow(abs($a[0] - $b[0]), 2) + pow(abs($a[1] - $b[1]), 2));
            if (is_null($min) || $min[0] > $d) {
                $min = [$d, $b, $k];
            }
        }
        unset($points[$min[2]]);

        $x = $min[1][0];
        $y = $min[1][1];

        $d = 0;
        if ($a[0] < $x && $a[1] < $y) {
            $d = 1; //'BR';
        } else if ($a[0] == $x && $a[1] < $y) {
            $d = 2; //'B';
        } else if ($a[0] < $x && $a[1] == $y) {
            $d = 3; //'R';
        } else if ($a[0] < $x && $a[1] > $y) {
            $d = 4; //'TR';
        } else if ($a[0] == $x && $a[1] > $y) {
            $d = 5; //'T';
        } else if ($a[0] > $x && $a[1] > $y) {
            $d = 6; //'TL';
        } else if ($a[0] > $x && $a[1] == $y) {
            $d = 7; //'L';
        } else if ($a[0] > $x && $a[1] < $y) {
            $d = 8; //'BL';
        }
        if (sizeof($directions) == 0 || $directions[sizeof($directions) - 1] != $d) {
            $directions[] = $d;
        }
        $a = $min[1];
    }


    return join('', $directions);
}

//mysql

/**
 * 
 * @staticvar \PDO $dbh
 * @return \PDO
 */
function getDb() {
    static $dbh = null;
    if (is_null($dbh)) {
        $dbh = new \PDO(DB_STR_CONN, DB_USER, DB_PASSWORD, [
            \PDO::ATTR_PERSISTENT => true
        ]);
    }
    return $dbh;
}

/**
 * 
 * @param type $objectId
 * @param type $a
 * @param type $b
 * @param type $numstep
 * @return int
 */
function insertPath($objectId, $a, $b, $numstep) {
    $db = getDb(); 
    $sql = "INSERT INTO myl_path (objId,tagIdA,tagIdB,numstep,weight) VALUES (?,?,?,?,?)";
    $stm = $db->prepare($sql);
    $stm->execute([$objectId, $a, $b, $numstep, 1]);
    return $db->lastInsertId();
}

/**
 * 
 * @param type $objectId
 * @param type $a
 * @param type $b
 * @param type $numstep 
 * @return array [pathId, objId, tagIdA, tagIdB, numstep, weight]
 */
function getPath($objectId, $a, $b, $numstep) {
    $db = getDb();
    
    $sth = $db->prepare("SELECT * FROM myl_path WHERE objId=? AND tagIdA=? AND tagIdB =? AND numstep =? LIMIT 1");
    $sth->execute([$objectId, $a, $b, $numstep]);
    $data = $sth->fetch();
    if(isset($data['pathId'])) {
        return $data;
    }
    return null;
}

/**
 * Получаем самый популярный вариант
 * 
 * @param int $a
 * @param int $b
 * @param int $numstep
 * @return array [pathId, objId, tagIdA, tagIdB, numstep, weight]
 */
function getPathMax($a, $b, $numstep) {
    $db = getDb();
    
    $sth = $db->prepare("SELECT * FROM myl_path WHERE tagIdA=? AND tagIdB =? AND numstep =? ORDER BY weight DESC ");
    $sth->execute([$a, $b, $numstep]);
    $data = $sth->fetch();
    if(isset($data['pathId'])) {
        return $data;
    }
    return null;   
}


/**
 * 
 * @param int $a
 * @param int $b
 * @param int $numstep
 * @return array [ [pathId, objId, tagIdA, tagIdB, numstep, weight], ... ]
 */
function getPathsMax($a, $b, $numstep) {
    $db = getDb();
    
    $sth = $db->prepare("SELECT * FROM myl_path WHERE tagIdA=? AND tagIdB =? AND numstep =? ORDER BY weight DESC ");
    $sth->execute([$a, $b, $numstep]);
    return $sth->fetchAll();    
}


/**
 * 
 * @param type $id
 * @return boolean
 */
function updatePathWeight($id) {
    $db = getDb(); 
    $sql = "UPDATE myl_path SET weight = weight + 1 WHERE pathId=?";
    $stm = $db->prepare($sql);
    return $stm->execute([$id]);
}
