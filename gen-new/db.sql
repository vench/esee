
DROP TABLE IF EXISTS `myl_object`;

CREATE TABLE `myl_object` (
  `objId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(2) NOT NULL, 
  PRIMARY KEY (`objId`)
  
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `myl_path`;

CREATE TABLE `myl_path` (
  `pathId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objId` int(10) unsigned NOT NULL,
  `tagIdA` tinyint(1) unsigned NOT NULL,
  `tagIdB` tinyint(1) unsigned NOT NULL,
  `numstep` int(10) unsigned NOT NULL DEFAULT 0,  
  `weight` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`pathId`),
   KEY `myl_pathtagIdA` (`tagIdA`),
   KEY `myl_pathtagIdB` (`tagIdB`)
  
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;