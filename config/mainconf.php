<?php

if (!isset($_SESSION))
	session_start();

# Main database
define("DB_host", "");
define("DB_name", "");
define("DB_user", "");
define("DB_pass", "");

# external database on download server, required for authentication immediately after adding a new subscription.
define("ext_DB_host", "");
define("ext_DB_name", "");
define("ext_DB_user", "");
define("ext_DB_pass", "");
define("ext_DB_flag", 0);
define("ext_DB_ca", "/etc/ssl/certs/ca-certificates.crt");

define("sync_external_db", FALSE); // Sync an external Database with User Authentication Data

define("SESSION_lifetime", 3600); // Session lifetime set to 1h

$CONF["email"]["From"] = "oposso@oposso.net";
$CONF["email"]["From_name"] = "OPOSSO";
$CONF["user"]["expire"] = 24; // Time (h) after the user will be deleted if not confirmed
$CONF["notification"] = ["twoMonth" => "2 months", "twoWeeks" => "2 weeks"]; // Notification settings
$CONF["tc_required"] = TRUE; // terms and conditions have to be accepted

$PATH_htpasswd = "HTPASSWD"; // absolute filepath to .htpasswd which contains username and password

$PATH_uri = ""; // Subdirectory of the website root OPOSSO located in
$PATH_home = $_SERVER["DOCUMENT_ROOT"] . $PATH_uri; // Path to website root
$PATH_classes = $PATH_home . "/classes"; // Path to class directory
$PATH_l10n = $PATH_home . "/l10n"; // Path to language files
if (isset($_SERVER['HTTP_HOST'])) {
	$URL_root = "http://" . $_SERVER['HTTP_HOST'] . $PATH_uri; // URL of the rootpage
	$URL_home = $URL_root . "/index.php"; // URL of the homepage
}

$GLOBALS["PATH_log"] = $PATH_home . "/admin/logs"; // Path to the logfile directory