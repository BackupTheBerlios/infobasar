<?php
// config.php: Configuration of the InfoBasar
// $Id: config.php,v 1.1 2004/05/20 20:55:00 hamatoma Exp $
set_magic_quotes_runtime(0);
error_reporting(E_ALL); //  ^ E_NOTICE

include "classes.php";
$dbType = "MySQL";

function init (&$session, $dbType) {
	global $HTTP_HOST, $SCRIPT_FILENAME, $PHP_SELF;
	// Basisverzeichnis relativ zu html_root
	$session->setScriptBase ("http://$HTTP_HOST$PHP_SELF", $SCRIPT_FILENAME);

	// MySQL
	if ($dbType == 'MySQL') {
		// MySQL server host:
		$server = 'localhost';
		// MySQL User
		$user = 'forum';
		$passw = 'forum';
		$db = 'infobasar';
		// Präfix für die Tabellennamen. Sinnvoll, wenn mehrere Foren in einer DB
		$prefix = 'infobasar_';
		$session->setDb ($dbType, $server, $db, $user, $passw, $prefix);
	} // mysql
	$session->fTraceFlags
		= 0 * TC_Util1 + 1 * TC_Util2 + 0 * TC_Util1
		+ 1 * TC_Gui1 + 0 * TC_Gui2 + 0 * TC_Gui3
		+ 0 * TC_Db1 + 0 * TC_Db2 + 0 * TC_Db3
		+ 0 * TC_Session1 + 0 * TC_Session2 + 0 * TC_Session3 
		+ 0 * TC_Layout1
		+ 1 * TC_Update + 0 * TC_Insert + 1 * TC_Query
		+ 0 * TC_Convert + 1 * TC_Init + 0 * TC_Diff2
		+ TC_Error + TC_Warning + TC_X;
	$session->fTraceFlags = TC_Error + TC_Warning + TC_X;
	# $session->fTraceFlags = TC_All;
} // Config

include "util.php";
if ($dbType == 'MySQL') {
	include "db_mysql.php";
}
include "gui.php";
?>
