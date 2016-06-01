<?php

/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
require_once('./../../config/mainconf.php');
require_once($PATH_classes . "/class.Helper.php");
require_once($PATH_classes . "/class.Customize.php");

$uploaddir = $PATH_home . '/layout/less/upload/';

if (!empty($_POST["action"])) {
	switch ($_POST["action"]) {

		case "save":
			$Customize = new Customize();
			$product_name = empty($_POST["product_name"]) ? "" : $_POST["product_name"];
			$pimary_color = empty($_POST["pimary_color"]) ? "" : $_POST["pimary_color"];
			$secondary_color = empty($_POST["secondary_color"]) ? "" : $_POST["secondary_color"];

			$settings = [
				'product_name' => $product_name,
				'pimary_color' => $pimary_color,
				'secondary_color' => $secondary_color
			];

			if (!empty($_FILES['logo']['name'])) {
				$logo = $_FILES['logo']['name'];
				$uploadfile = $uploaddir . basename($logo);

				$logo_old = $uploaddir . $Customize->get_settings('logo');
				if (is_file($logo_old)) {
					unlink($logo_old);
				}
				if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadfile)) {
					$settings['logo'] = $logo;
				} else {
					echo Helper::boxError("File could not be uploaded.");
					exit();
				}
			}
			$response = $Customize->set_settings($settings);
			if ($response) {
				echo Helper::boxHighlight("Settings successfully updated.");
			} else {
				echo Helper::boxError($Customize->error_msg);
			}

		default:
			break;
	}
} else {
	echo Helper::boxError("No action type given.");
}
exit();
