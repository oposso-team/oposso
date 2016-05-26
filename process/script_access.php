<?php

/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2016, Alexander Rein
 */
if(!isset($_SESSION)) 
	session_start();
$_SESSION['script_access'] = TRUE;
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_lifetime)) {
	if (ini_get('session.use_cookies')) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
	}
	session_unset();
	session_destroy();
	header('Location: ' . $URL_home);
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
?>
