<?php
// address.php: Module for address administration.
// $Id: address.php,v 1.4 2004/10/17 21:16:06 hamatoma Exp $
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

// Theme param no:
define ('Th_AddressHeader', 223);
define ('Th_AddressBodyStart', 224);
define ('Th_AddressBodyEnd', 225);

// Tables:
define ('Tab_Book', 'address_book');
define ('Tab_Card', 'address_card');

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
$session->dumpVars ("Init");
$session->trace (TC_X, 'address_init: rc: ' . ($rc == null ? "null" : rc));
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
	} else {
		if (isset ($_POST ['book_change']) || isset ($_POST ['book_new']))
			addressEditBookAnswer ($session);
		else {
			$session->trace (TC_X, 'address_init: fPageName: "' . $session->fPageName . '"');
			switch ($session->fPageName){
			case P_ShowBooks: addressShowBooks ($session); break;
			case P_EditBook: addressEditBook ($session); break;
			case P_EditCard: addressEditCard ($session); break;
			case P_ShowCards: addressShowCard ($session); break;
			default:
				putHeaderBase ($session);
			}
		}
	}
}
exit (0);
// ---------------------------------
function addressEditBook (&$session, $message = null){
	$session->trace (TC_Gui1, 'addressEditBook');
	if (! isset ($_POST ['book_id']) || empty ($_POST ['book_id'])){
		$book_id = dbGetValueByClause ($session, Tab_Book, 'min(id)', '1');
		list ($name, $description) = dbGetRecordById ($session, Tab_Book, 
			$book_id, 'name, description');
	} else {
		$book_id = $_POST ['book_id'];
		$name = $_POST ['book_name'];
		$description = $_POST ['book_description'];	
	}
	guiStandardHeader ($session, 'Ändern eines Adressbuchs ',
		Th_AddressHeader, Th_AddressBodyStart);
	if (isset ($search_title) || isset ($search_body))
		baseSearchResults ($session);
	if ($message <> null)
		guiParagraph($session, $message, false);
	guiStartForm ($session, 'search', P_EditBook);
	guiHiddenField ('book_id', $book_id);
	echo "<table border=\"0\">\n<tr><td>Name:</td><td>";
	guiTextField ('book_name', $name, 32, 64);
	echo "</td></tr>\n<tr><td>Beschreibung:</td><td>";
	guiTextField ('book_description', $description, 32, 64);
	echo "</td></tr>\n<tr><td></td><td>";
	guiButton ('book_change', 'Ändern');
	echo ' ';
	guiButton ('book_new', 'Neu');
	echo "</td></tr>\n<tr><td>Nächster DS:</td><td>";
	$book_list = dbColumnList ($session, Tab_Book, 'name', '1');
	guiComboBox ('book_next', $book_list, null);
	echo "</td></tr>\n</table>\n";
	guiFinishForm ($session, $session);
	guiStandardBodyEnd ($session, Th_AddressBodyEnd);
}
function addressEditBookAnswer (&$session){
	$session->trace (TC_Gui1, 'addressEditBookAnswer');
	$session->trace (TC_X, 'addressEditBookAnswer');
	$message = null;
	if (isset ($_POST ['book_change'])){
		$session->trace (TC_Gui1, 'addressEditBook-change entdeckt');
		$name = $_POST ['book_name'];
		$id = $_POST ['book_id'];
		if (empty ($name))
			$message = 'Bitte Namen angeben';
		else if (dbSingleValue ($session, 'select count(id) from ' . dbTable ($session, Tab_Book) 
			. ' where name=' . dbSqlString ($session, $name) . ' and id<>' . (0+$id)) > 0)
			$message = "Addressbuch mit Namen $name existiert schon!";
		else {
			dbUpdate ($session, Tab_Book, $id, 'name=' . dbSqlString ($session, $name)
				. ',description=' . dbSqlString ($session, $_POST ['book_description']) . ',');
			$message = "Addressbuch $name wurde geändert.";
		}
	} elseif (isset ($_POST ['book_new'])){
		$session->trace (TC_Gui1, 'addressEditBook-new entdeckt');
		$name = $_POST ['book_name'];
		if (empty ($name))
			$message = 'Bitte Namen angeben';
		else if (dbSingleValue ($session, 'select count(id) from ' . dbTable ($session, Tab_Book) 
			. ' where name=' . dbSqlString ($session, $name)) > 0)
			$message = "Addressbuch mit Namen $name existiert schon!";
		else {
			dbInsert ($session, Tab_Book, 'name,description',
				dbSqlString ($session, $name) . ','
				. dbSqlString ($session, $_POST ['book_description']));
			$message = "Addressbuch $name wurde erstellt.";
		}
	}
	addressEditBook ($session, $message);
}
?>