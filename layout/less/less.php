<?php

/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
require_once('./../../config/mainconf.php');
require_once($PATH_classes . "/class.Customize.php");
require_once($PATH_classes . "/less/Less.php");


$Customize = new Customize();
$settings = $Customize->get_settings();
$values = array();
foreach ($settings as $set) {
	$values[$set['title']] = $set['value'];
}
$logo = empty($values["logo"]) ? "" : '/layout/less/upload/' . $values["logo"];
$pimary_color = empty($values["pimary_color"]) ? "" : $values["pimary_color"];
$secondary_color = empty($values["secondary_color"]) ? "" : $values["secondary_color"];

if (is_file($PATH_home . $logo)) {
	$logo_dimension = getimagesize($PATH_home . $logo);
}

$parser = new Less_Parser();
$parser->parse("@colorPrimary: {$pimary_color}; @colorSecondary: {$secondary_color}; @logo: '{$logo}';");
if (is_file($PATH_home . $logo)) {
	$parser->parse("@logoWidth: {$logo_dimension[0]}px; @logoHeight: {$logo_dimension[1]}px;");
}
$parser->parseFile($PATH_home . '/layout/less/main.less', $PATH_home . '/layout/');

header('Content-type: text/css');
header('Cache-control: must-revalidate');

echo $parser->getCss();
