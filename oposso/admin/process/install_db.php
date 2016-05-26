<?php

/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 */
require_once('./../../config/mainconf.php');
require_once($PATH_classes . '/class.DBconn.php');

error_reporting(E_ALL);

$db = new DBconn();
$SQL[] = "
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';";

$SQL[] = "
CREATE TABLE IF NOT EXISTS `subscription` (
  `sID` int(11) NOT NULL,
  `uID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `exp_time` timestamp NULL DEFAULT NULL,
  `deact_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;";

$SQL[] = "
CREATE TABLE IF NOT EXISTS `subscription_key` (
`kID` int(11) NOT NULL,
  `tID` int(11) NOT NULL,
  `duration` int(10) unsigned NOT NULL,
  `CSPRN` varchar(32) NOT NULL,
  `platform` varchar(255) DEFAULT NULL,
  `contract` varchar(255) DEFAULT NULL,
  `sID` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `act_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=681 DEFAULT CHARSET=utf8;";

$SQL[] = "
CREATE TABLE IF NOT EXISTS `subscription_type` (
`tID` int(11) NOT NULL,
  `short` varchar(5) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;";

$SQL[] = "
CREATE TABLE IF NOT EXISTS `user` (
`uID` int(11) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `organization` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `lang` varchar(3) DEFAULT NULL,
  `allow_extend` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `hash` varchar(50) NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;";

$SQL[] = "
ALTER TABLE `subscription`
 ADD PRIMARY KEY (`sID`), ADD UNIQUE KEY `username_UNIQUE` (`username`), ADD KEY `fk_user.uID` (`uID`);";

$SQL[] = "
ALTER TABLE `subscription_key`
 ADD PRIMARY KEY (`kID`), ADD UNIQUE KEY `CSPRN_UNIQUE` (`CSPRN`), ADD KEY `fk_subscription.sID` (`sID`), ADD KEY `fk_typ.tID` (`tID`);";

$SQL[] = "
ALTER TABLE `subscription_type`
 ADD PRIMARY KEY (`tID`);";

$SQL[] = "
ALTER TABLE `user`
 ADD PRIMARY KEY (`uID`), ADD UNIQUE KEY `email_UNIQUE` (`email`), ADD UNIQUE KEY `hash_UNIQUE` (`hash`);";

$SQL[] = "
ALTER TABLE `subscription`
MODIFY `sID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=32;";

$SQL[] = "
ALTER TABLE `subscription_key`
MODIFY `kID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=681;";

$SQL[] = "
ALTER TABLE `subscription_type`
MODIFY `tID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;";

$SQL[] = "
ALTER TABLE `user`
MODIFY `uID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;";
$error = false;
foreach ($SQL as $value) {
	if ($db->query($value) === FALSE) {
		echo $db->error."<br/>";
		$error = true;
	}
}
if (!$error)
	echo "Database successfully created.";
exit();
?>
