<?php

/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 */
require_once('./../../config/mainconf.php');
require_once($PATH_l10n . "/locallang.php");

header("Content-type: text/javascript");

$array = json_encode($LOCAL);
echo "var LOCAL = " . $array . ";\n";
?>
