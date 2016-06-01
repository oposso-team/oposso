<?php

/**
 * several helper functions
 *
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
class Helper {

	public static function boxError($text) {
		return "
		<div class='ui-widget message'>
			<div class='ui-state-error ui-corner-all'>
				<p><span class='ui-icon ui-icon-alert' style='float: left; margin-right: .3em;'></span>{$text}</p>
			</div>
		</div>
		";
	}

	public static function boxHighlight($text) {
		return "
		<div class='ui-widget message'>
			<div class='ui-state-highlight ui-corner-all'>
				<p><span class='ui-icon ui-icon-info' style='float: left; margin-right: .3em;'></span>{$text}</p>
			</div>
		</div>
		";
	}

	/**
	 * 
	 * @param mixed $value
	 * @param string $msgType
	 * @param string $msgText
	 * @return json
	 */
	public static function jsonResponse($value, $msgType = "", $msgText = "") {
		switch ($msgType) {
			case "error":
				$msgText = self::boxError($msgText);
				break;
			case "success":
				$msgText = self::boxHighlight($msgText);
				break;
		}
		return json_encode(array(
			"value" => $value,
			"message" => array(
				"type" => $msgType,
				"text" => $msgText
			)
		));
		
	}

	public static function validate_Email($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
	
	/**
	 * Replace each value of marker array with the corresponding content value on template string
	 * @param array $marker
	 * @param array $content
	 * @param string $template
	 * @return string
	 */
	public static function setMarker($marker, $content, $template) {
		for ($i = 0; $i < count($marker); $i++) {
			$m = "###".strtoupper($marker[$i])."###";
			$c = $content[$i];
			$template = str_replace($m, $c, $template);
		}
		return $template;
	}
	
	public static function debug_console () {
		
	}

}

?>
