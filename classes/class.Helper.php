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
	public static function jsonResponse($value, $msgType = "", $msgText = "", $command = "") {
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
			),
			"command" => $command,
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
			$m = "###" . strtoupper($marker[$i]) . "###";
			$c = $content[$i];
			$template = str_replace($m, $c, $template);
		}
		return $template;
	}

	public static function createToken() {
		$token = bin2hex(openssl_random_pseudo_bytes(16));
		$_SESSION['token'] = $token;

		return $token;
	}

	public static function compareToken($token) {
		if (!empty($_SESSION['token'])) {
			return ($token === $_SESSION['token']);
		} else {
			return false;
		}
	}

	public static function createCaptchaString($length = 6) {
		$signs = 'aAbBcCdDeEfFgGhHjJkKLmMnNpPqQrRsStTuUvVwWxXyYzZ23456789';
		$string = '';
		for ($i = 1; $i <= $length; $i++) {
			$sign = $signs{rand(0, strlen($signs) - 1)};
			$string .= $sign;
		}
		$_SESSION['captcha'] = $string;

		return $string;
	}

	public static function compareCaptcha($captcha) {
		if (!empty($_SESSION['captcha'])) {
			return ($captcha === $_SESSION['captcha']);
		} else {
			return false;
		}
	}

	public static function arrayContains($str, array $arr) {
		foreach ($arr as $a) {
			if (stripos($str, $a) !== false) {
				return true;
			}
		}
		return false;
	}

	public static function debug_console() {
		
	}

}
