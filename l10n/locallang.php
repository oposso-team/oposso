<?php

require_once($PATH_classes . '/class.User.php');

if (isset($_REQUEST['ln']) && !empty($_REQUEST['ln'] && is_file(__DIR__ . '/' . strtoupper($_REQUEST['ln']) . ".php"))) {
	$preferredLang = strtoupper($_REQUEST['ln']);
	setcookie("ln", $preferredLang, strtotime('+365 days'));
	$_COOKIE['ln'] = $preferredLang;
	if (isset($_SESSION["login"]) && $_SESSION["login"] && !empty($_SESSION['user'])) {
		setUserLang($_SESSION["user"], $preferredLang);
	}
} else if (!empty($_SESSION['user']['lang'])) {
	$preferredLang = $_SESSION['user']['lang'];
	$_COOKIE['ln'] = $preferredLang;
} else if (isset($_COOKIE['ln']) && !empty($_COOKIE['ln'] && is_file(__DIR__ . '/' . strtoupper($_COOKIE['ln']) . ".php"))) {
	$preferredLang = $_COOKIE['ln'];
} else {
	$preferredLang = getPreferredLanguage();
	setcookie("ln", $preferredLang, strtotime('+365 days'));
	$_COOKIE['ln'] = $preferredLang;
}

require_once('EN.php');
if ($preferredLang != 'EN') {
	require_once($preferredLang . '.php');
}

/**
 * Detect Browser Language
 */
function getPreferredLanguage($default = "EN") {

	if (!isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]) || empty($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
		return $default;
	}

	$acceptedLanguages = @explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
	$preferredLanguage = null;
	$maxWeight = 0.0;

	foreach ((array) $acceptedLanguages as $acceptedLanguage) {

		$weight = (float) @substr(explode(';', $acceptedLanguage)[1], 2);
		if (!$weight) {
			$weight = 1.0;
		}

		if ($weight > $maxWeight) {
			$preferredLanguage = strtoupper(substr($acceptedLanguage, 0, 2));
			$maxWeight = $weight;
		}
	}
	return is_file($PATH_l10n . '/' . $preferredLanguage . ".php") ? $preferredLanguage : $default;
}

/**
 * Set user settings
 */
function setUserLang($userData, $lang) {
	if (is_string($lang) && strlen($lang) <= 3) {

		$user = new User();
		if($user->set_language($userData["uID"], $userData["password"], $lang)){
			$_SESSION["user"] = $user->user;
		}
	}
}
