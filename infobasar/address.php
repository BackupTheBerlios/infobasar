<?php
// address.php: Module for address administration.
// $Id: address.php,v 1.1 2004/10/13 22:20:09 hamatoma Exp $
/*
Diese Datei ist Teil von InfoBasar.
Copyright 2004 hamatoma@gmx.de München
InfoBasar ist freie Software. Du kannst es weitergeben oder verändern
unter den Bedingungen der GNU General Public Licence.
Näheres siehe Datei LICENCE.
InfoBasar sollte nützlich sein, es gibt aber absolut keine Garantie
der Funktionalität.
*/
$start_time = microtime ();
define ('PHP_ModuleVersion', '0.6.5 (2004.09.20)');
set_magic_quotes_runtime(0);
error_reporting(E_ALL);

session_start();

 // If this is a new session, then the variable $user_id
 if (!session_is_registered("session_user")) {
	session_register("session_user");
	session_register("session_start");
	session_register("session_no");
	$start = time();
 }
 $session_id = session_id();
define ('C_ScriptName', 'index.php');

include "config.php";
include "classes.php";
include "modules.php";
// ----------- Definitions

// Actions:
define ('A_EditBook', 'editbook');
define ('A_EditCard', 'editcard');
define ('A_ShowBooks', 'showbooks');
define ('A_ShowCards', 'showcards');

define ('P_EditBook', 'editbook');
define ('P_EditCard', 'editcard');
define ('P_ShowBooks', 'showbooks');
define ('P_ShowCards', 'showcards');

define ('Th_AddressHeader', 223);
define ('Th_AddressBodyStart', 224);
define ('Th_AddressBodyEnd', 225);

$session = new Session ($start_time);

	// All requests require the database
dbOpen($session);
if (isset ($session_no) && $session_no > 0){
	$session_no++;
	$session->trace (TC_Init, "session_no: $session_no");
}
if ((empty ($session_user)) && getLoginCookie ($session, $user, $code)
	&& dbCheckUser ($session, $user, $code) == ''){
	$session->trace (TC_Init, 'address.php: Cookie erfolgreich gelesen');
	$session->trace (TC_X, 'address.php: Cookie erfolgreich gelesen. User: ' . $session_user);
}
$rc = dbCheckSession ($session);
#$session->dumpVars ("Init");
if ($rc != null) {
	$session->trace (TC_Init, 'keine Session gefunden: ' . $rc . ' ' 
		. (empty($login_user) ? "-" : '>' . $login_user));
	putHeaderBase ($session);
} else {
	$session->trace (TC_Init, 'address.php: std_answer: ' . (empty ($std_answer) ? '' : "($std_answer)"));
	if (isset ($action)) {
		$session->trace (TC_Init, "action.php: action: $action");
		switch ($action){
		case A_EditBook: addressEditBook ($session); break;
		case A_EditCard: addressEditCard ($session); break;
		case A_ShowBooks: addressShowBooks ($session); break;
		case A_ShowCards: addressShowCards ($session); break;
		case '': break;
		default:
			$session->trace (TC_Error, "address.php: unbek. Aktion: $action");
			putHeaderBase ($session);
			break;
		}
	} 
}
exit (0);
// ---------------------------------
function addressEditBook (&$session){
	global $HTTP_POST_VAR;
	$bookname = $HTTP_POST []
	guiStandardHeader ($session, 'Ändern eines Adressbuchs',
		Th_AddressHeader, Th_AddressBodyStart);
	if (isset ($search_title) || isset ($search_body))
		baseSearchResults ($session);
	echo '<p>Addressbuch ';
	echo $
	guiStartForm ($session, 'search', P_EditBook);
	guiHiddenField ('last_pagename', $last_pagename);
	echo "<table border=\"0\">\n<tr><td>Titel:</td><td>";
	guiTextField ('search_titletext', $search_titletext, 32, 64);
	echo " "; guiButton ('search_title', "Suchen");
	echo "</td></tr>\n<tr><td>Beitrag:</td><td>";
	guiTextField ('search_bodytext', $search_bodytext, 32, 64);
	echo " "; guiButton ('search_body', "Suchen");
	echo "</td></tr>\n<tr><td>Maximale Trefferzahl:</td><td>";
	guiTextField ("search_maxhits", $search_maxhits, 10, 10);
	echo "</td></tr>\n</table>\n";
	guiFinishForm ($session, $session);
	guiStandardBodyEnd ($session, Th_AddressBodyEnd);
	
}
?>