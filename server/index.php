<?php
// index.php: Start page of the InfoBasar
// $Id: index.php,v 1.5 2004/05/31 23:19:34 hamatoma Exp $
/*
Diese Datei ist Teil von InfoBasar.
Copyright 2004 hamatoma@gmx.de München
InfoBasar ist freie Software. Du kannst es weitergeben oder verändern
unter den Bedingungen der GNU General Public Licence.
Näheres siehe Datei LICENCE.
InfoBasar sollte nützlich sein, es gibt aber absolut keine Garantie
der Funktionalität.
*/
set_magic_quotes_runtime(0);
error_reporting(E_ALL);

session_start();

 // If this is a new session, then the variable $user_id
 if (!session_is_registered("session_user")) {
	session_register("session_user");
	session_register("session_start");
	$start = time();
 }
 $session_id = session_id();
 ob_start (); // ob_flush() --> index.php + guiLoginAnswer()
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<?php
define ('C_ScriptName', 'index.php');

include "config.php";
include "classes.php";
include "util.php";
include "gui.php";

if ($db_type == 'MySQL') {
	include "db_mysql.php";
}
$session = new Session ();
init ($session, $dbType);

	// All requests require the database
dbOpen($session);

//p ('User,Id,Login: ' . $session_user . "," . $session_id . "/" . $login_user);
if ((empty ($session_user)) && getLoginCookie ($session, $user, $code)
	&& dbCheckUser ($session, $user, $code) == ''){
	$session->trace (TC_Init, 'index.php: Cookie erfolgreich gelesen');
}
$rc = dbCheckSession ($session);
$do_login = false;
if (! empty ($rc)) {
	// p ("Keine Session gefunden: $session_id / $session_user ($rc)");
	if (! empty ($login_user))
		guiLoginAnswer ($session);
	else 
		$do_login = true;
} else {
		if (isset ($login_user))
			guiLoginAnswer ($session);
		else
			$do_login = $session->fPageName == P_Login;
}
if ($do_login){
		clearLoginCookie ($session);
		ob_flush ();
		guiLogin ($session, '');
} else {
		ob_flush ();
		$session->trace (TC_Init, 'index.php: std_answer: ' . (empty ($std_answer) ? '' : "($std_answer)"));
	if (isset ($action)) {
		$session->trace (TC_Init, "index.php: action: $action");
		switch ($action){
		case A_Edit: guiEditPage ($session, null); break;
		case A_Search:	guiSearch ($session, ''); break;
		case A_NewThread: guiPosting ($session, '', C_New); break;
		case A_Answer: guiPosting ($session, '', C_New); break;
		case A_ChangeThread: guiPostingChange ($session); break;
		case A_ShowThread: guiThread ($session); break;
		case A_ShowForum: guiForum ($session); break;
		case A_PageInfo: guiPageInfo ($session); break;
		case A_ShowText: guiShowPageById ($session, $page_id, $text_id); break;
		case A_Diff: guiDiff ($session); break;
		case A_Show: guiShowCurrentPage ($session); break;
		case '': break;
		default:
			$session->trace (TC_Error, "index.php: unbek. Aktion: $action");
			guiFormTest ($session);
			break;
		}
	} elseif (isset ($std_answer) || ! guiCallStandardPage ($session)) {
		$session->trace (TC_Init, 'index.php: keine Standardseite'
			. (isset ($edit_save) ? " ($edit_save)" : ' []'));
		if (isset ($test))
			guiTest ($session);
		else if (substr ($session->fPageName, 0, 1) == '.')
			guiNewPageReference ($session);
		else if (isset ($alterpage_insert))
			guiAlterPageAnswer ($session, C_New);
		elseif (isset ($alterpage_changecontent))
			guiAlterPageAnswer ($session, C_Change);
		elseif (isset ($alterpage_preview))
			guiAlterPageAnswer ($session, C_LastMode);
		elseif (isset ($alterpage_changepage))
			guiAlterPageAnswerChangePage($session);
		elseif (isset ($account_new) || isset ($account_change)
			|| isset ($account_other))
			guiAccountAnswer ($session, $account_user);
		elseif (isset ($search_title) || isset ($search_body))
			guiSearchAnswer ($session, '');
		elseif (isset ($forum_title) || isset ($forum_body))
			guiForumSearchAnswer ($session, null);
		elseif (isset ($edit_preview))
			guiEditPage ($session, null);
		elseif (isset ($edit_save))
			guiEditPageAnswerSave ($session);
		elseif (isset ($edit_cancel))
			guiShowPageById ($session, $edit_pageid, null);
		elseif (isset ($posting_preview) || isset ($posting_insert)
			|| isset ($posting_change))
			guiPostingAnswer ($session);
		elseif ( ($page_id = dbPageId ($session, $session->fPageName)) > 0)
			guiShowPageById ($session, $page_id, null);
		else {
			$session->trace (TC_Warning, PREFIX_Warning . 'index.php: Nichts gefunden');
			guiHome ($session);
		}
	}
}
exit (0);
// ----------------------------------------------
function init (&$session, $dbType) {
	global $HTTP_HOST, $SCRIPT_FILENAME, $PHP_SELF;
	global $db_type, $db_server, $db_user, $db_passw, $db_name, $db_prefix;
	// Basisverzeichnis relativ zu html_root
	$session->setScriptBase ("http://$HTTP_HOST$PHP_SELF", $SCRIPT_FILENAME);

	// MySQL
	if ($dbType == 'MySQL') {
		// MySQL server host:
		$session->setDb ($db_type, $db_server, $db_name, $db_user, $db_passw, $db_prefix);
	} // mysql
	$session->fTraceFlags
		= 1 * TC_Util1 + 1 * TC_Util2 + 0 * TC_Util1
		+ 1 * TC_Gui1 + 0 * TC_Gui2 + 0 * TC_Gui3
		+ 0 * TC_Db1 + 0 * TC_Db2 + 0 * TC_Db3
		+ 0 * TC_Session1 + 0 * TC_Session2 + 0 * TC_Session3 
		+ 0 * TC_Layout1
		+ 1 * TC_Update + 1 * TC_Insert + 0 * TC_Query
		+ 0 * TC_Convert + 1 * TC_Init + 0 * TC_Diff2
		+ TC_Error + TC_Warning + TC_X;
	$session->fTraceFlags = TC_Error + TC_Warning + TC_X;
	#$session->fTraceFlags = TC_All;
} // Config

?>
