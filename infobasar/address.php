<?php
// address.php: Module for address administration.
// $Id: address.php,v 1.9 2004/12/05 18:41:07 hamatoma Exp $
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
define ('PHP_ModuleVersion', '0.6.5.4 (2004.12.5)');
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
}
$rc = dbCheckSession ($session);
#$session->dumpVars ("Init");
#$session->trace (TC_X, 'address_init: rc: ' . ($rc == null ? "null" : rc));
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
		case A_EditCard: addressEditCard ($session, null, $_GET ['card_id']); break;
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
		elseif (isset ($_POST ['card_change']) || isset ($_POST ['card_new']))
			addressEditCardAnswer ($session);
		elseif (isset ($_POST ['show_search']))
			addressShowCards ($session);
		else {
			#$session->trace (TC_X, 'address_init: fPageName: "' . $session->fPageName . '"');
			switch ($session->fPageName){
			case P_ShowBooks: addressShowBooks ($session); break;
			case P_EditBook: addressEditBook ($session); break;
			case P_EditCard: addressEditCard ($session); break;
			case P_ShowCards: addressShowCards ($session); break;
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
function addressEditCard (&$session, $message = null, $card_id = null){
	$session->trace (TC_Gui1, 'addressEditCard');
	if (! isset ($_POST ['card_id']) || empty ($_POST ['card_id'])){
		$session->trace (TC_X, 'addressEditCard-2');
		if ($card_id == null)
			$card_id = dbGetValueByClause ($session, Tab_Card, 'min(id)', '1');
		list ($books, $firstname, $lastname, $nickname,
			$emailprivate, $emailprivate2, $phoneprivate, $phoneprivate2, $faxprivate, $mobileprivate,
			$emailoffice, $emailoffice2, $phoneoffice, $phoneoffice2, $faxoffice, $mobileoffice,
			$street, $country, $zip, $city,
			$functions, $notes) = dbGetRecordById ($session, Tab_Card, 
			$card_id, 'books,firstname, lastname, nickname, '
			. 'emailprivate, emailprivate2, phoneprivate, phoneprivate2, faxprivate, mobileprivate, '
			. 'emailoffice, emailoffice2, phoneoffice, phoneoffice2, faxoffice, mobileoffice, '
			. 'street, country, zip, city, '
			. 'functions, notes'
			);
	} else {
		$session->trace (TC_Gui1, 'addressEditCard-3: ' . $_POST ['card_books']);
		$books = $_POST ['card_books'];
		$firstname = $_POST ['card_firstname']; 
		$lastname = $_POST ['card_lastname'];
		$nickname = $_POST ['card_nickname'];
		$emailprivate = $_POST ['card_emailprivate'];
		$emailprivate2 = $_POST ['card_emailprivate2'];
		$phoneprivate = $_POST ['card_phoneprivate'];
		$phoneprivate2 = $_POST ['card_phoneprivate2'];
		$faxprivate = $_POST ['card_faxprivate'];
		$mobileprivate = $_POST ['card_mobileprivate'];
		$emailoffice = $_POST ['card_emailoffice'];
		$emailoffice2 = $_POST ['card_emailoffice2'];
		$phoneoffice = $_POST ['card_phoneoffice'];
		$phoneoffice2 = $_POST ['card_phoneoffice2'];
		$faxoffice = $_POST ['card_faxoffice'];
		$mobileoffice = $_POST ['card_mobileoffice'];
		$street = $_POST ['card_street'];
		$country = $_POST ['card_country'];
		$zip = $_POST ['card_zip'];
		$city = $_POST ['card_city'];
		$functions = $_POST ['card_functions'];
		$notes = $_POST ['card_notes'];
		$card_id = $_POST ['card_id'];
	}
	guiStandardHeader ($session, 'Ändern einer Adresskarte ',
		Th_AddressHeader, Th_AddressBodyStart);
	$ids = preg_split ('/[ ,]+/', $books);
	$books = '';
	foreach ($ids as $ii => $id){
		$books .= dbSingleValue ($session, 'select name from ' . dbTable ($session, Tab_Book)
			. " where id=" . (0+$id)) . ($ii < count ($ids) - 1 ? ',' : '');
		$session->trace (TC_Gui1, 'addressEditCard-4: ' . $id . "/" . $books);
	}
	if (isset ($search_title) || isset ($search_body))
		baseSearchResults ($session);
	if ($message <> null)
		guiParagraph($session, $message, false);
	guiStartForm ($session, 'search', P_EditCard);
	guiHiddenField ('card_id', $card_id);
	outTable (0);
	outTableRecordCells ('Id:', $card_id);
	outTableRecord();
	outTableTextField ('Adressbücher:', 'card_books', $books, 34, 0);
	outTableRecordDelim();
	outTableTextField2 ('Vor-, Nachname:', 'card_firstname', $firstname,  16, 64,
		' ', 'card_lastname', $lastname, 16, 64);
	outTableRecordEnd();
	outTableTextField ('Spitzname:', 'card_nickname', $nickname,  16, 64);
	outTableRecordEnd();
	outTableRecordCells ('Privat', ' ');
	outTableRecord ();
	outTableTextField2 ('EMail EMail2:',
		'card_emailprivate', $emailprivate, 34, 128, ' ',
		'card_emailprivate2', $emailprivate2, 34, 128);
	outTableRecordDelim();
	outTableTextField2 ('Telefon Telefon2:', 'card_phoneprivate', $phoneprivate, 34, 128,
		' ', 'card_phoneprivate2', $phoneprivate2, 34, 128);
	outTableRecordDelim();
	outTableTextField2 ('Mobil Fax:', 'card_mobileprivate', $mobileprivate, 34, 128,
		' ', 'card_faxprivate', $faxprivate, 34, 128);
	outTableRecordDelim ();
	outTableCell ('Land PLZ Ort Straße:');
	outTableDelim();
	guiTextField ('card_country', $country, 3, 64);
	echo ' ';
	guiTextField ('card_zip', $zip, 5, 12);
	echo ' ';
	guiTextField ('card_city', $city, 22, 64);
	echo ' ';
	guiTextField ('card_street', $street, 34, 128);
	outTableDelimAndRecordEnd();
	
	outTableRecordCells ('Geschäftlich:', ' ');
	outTableRecord ();
	outTableTextField2 ('EMail: EMail2:',
		'card_emailoffice', $emailoffice, 34, 128, ' ',
		'card_emailoffice2', $emailoffice2, 34, 128);
	outTableRecordDelim();
	outTableTextField2 ('Telefon Telefon2:', 'card_phoneoffice', $phoneoffice, 34, 128,
		' ', 'card_phoneoffice2', $phoneoffice2, 34, 128);
	outTableRecordDelim();
	outTableTextField2 ('Mobil Fax:', 'card_mobileoffice', $mobileoffice, 34, 128,
		' ', 'card_faxoffice', $faxoffice, 34, 128);
	outTableRecordDelim();
	outTableTextField ('Funktionen', 'card_functions', $functions, 34, 128);
	outTableRecordDelim();
	outTableTextArea ('Bemerkungen:', 'card_notes', $notes, 31, 4);
	outTableRecordDelim ();
	outTableButton2 (' ', 'card_change', 'Ändern', ' ', 'card_new', 'Neu');
	outTableRecordDelim();
	outTableTextField ('Nächster DS (Id):', 'card_next', '', 8, 8);
	outTableRecordEnd ();
	outTableEnd ();
	guiFinishForm ($session, $session);
	guiStandardBodyEnd ($session, Th_AddressBodyEnd);
}
function addressCheckBooks (&$session, $books, &$id_list){
	$session->trace (TC_Gui2, 'addressCheckBooks');
	$ids = preg_split ('/,/', $books);
	$id_list = '';
	$error = null;
	foreach ($ids as $ii => $name){
		if ($name > 0)
			$id = $name;
		else {
			$id = dbSingleValue ($session, 'select id from ' 
				. dbTable ($session, Tab_Book)
				. ' where name=' . dbSqlString ($session, $name)); 
			if (empty ($id)){
				$error = 'Adressbuch ' . $name . ' unbekannt!';
				break;
			}
		}
		$id_list .= $id . ($ii < count ($ids) - 1 ? ',' : '');
	$session->trace (TC_Gui2, 'addressCheckBooks: ' . $id_list);
	}
	return $error;
}
function addressEditCardAnswer (&$session){
	$session->trace (TC_Gui1, 'addressEditCardAnswer');
	$session->trace (TC_X, 'addressEditCardAnswer');
	$message = null;
	if (isset ($_POST ['card_change'])){
		$session->trace (TC_Gui1, 'addressEditcard-change entdeckt');
		$name = $_POST ['card_lastname'];
		$email = $_POST ['card_emailprivate'];
		$id = $_POST ['card_id'];
		$books = $_POST ['card_books'];
		if (empty ($name))
			$message = 'Bitte Nachnamen angeben';
		else if (empty ($books))
			$message = 'Bitte mindestens ein Adressbuch angeben';
		else if ( ($msg2 = addressCheckBooks ($session, $books, $id_list)) != null)
			$message = $msg2;
		else if (! empty ($email)
			&& dbSingleValue ($session, 'select count(id) from ' . dbTable ($session, Tab_Card) 
			. ' where emailprivate=' . dbSqlString ($session, $email) . ' and id<>' . (0+$id)) > 0){
			$rec = dbGetRecordByClause($session, Tab_Card, 'id,firstname,lastname,city',
				' emailprivate=' . dbSqlString ($session, $email) 
				. ' and id<>' . (0+$id));
			$message = "Adresskarte mit EMai-Adresse $email existiert schon: '"
					. $rec [1] . ' ' . $rec [2] . ' ' . $rec [3] . ' (' 
					. $rec [0] . ')';
		} else {
			dbUpdate ($session, Tab_Card, $id, 
				'firstname=' . dbSqlString ($session, $_POST ['card_firstname'])
				. ',lastname=' . dbSqlString ($session, $_POST ['card_lastname'])
				. ',nickname=' . dbSqlString ($session, $_POST ['card_nickname'])
				. ',emailprivate=' . dbSqlString ($session, $_POST ['card_emailprivate'])
				. ',emailprivate2=' . dbSqlString ($session, $_POST ['card_emailprivate2'])
				. ',phoneprivate=' . dbSqlString ($session, $_POST ['card_phoneprivate'])
				. ',phoneprivate2=' . dbSqlString ($session, $_POST ['card_phoneprivate2'])
				. ',faxprivate=' . dbSqlString ($session, $_POST ['card_faxprivate'])
				. ',mobileprivate=' . dbSqlString ($session, $_POST ['card_mobileprivate'])
				. ',emailoffice=' . dbSqlString ($session, $_POST ['card_emailoffice'])
				. ',emailoffice2=' . dbSqlString ($session, $_POST ['card_emailoffice2'])
				. ',phoneoffice=' . dbSqlString ($session, $_POST ['card_phoneoffice'])
				. ',phoneoffice2=' . dbSqlString ($session, $_POST ['card_phoneoffice2'])
				. ',faxoffice=' . dbSqlString ($session, $_POST ['card_faxoffice'])
				. ',mobileoffice=' . dbSqlString ($session, $_POST ['card_mobileoffice'])
				. ',street=' . dbSqlString ($session, $_POST ['card_street'])
				. ',country=' . dbSqlString ($session, $_POST ['card_country'])
				. ',zip=' . dbSqlString ($session, $_POST ['card_zip'])
				. ',city=' . dbSqlString ($session, $_POST ['card_city'])
				. ',functions=' . dbSqlString ($session, $_POST ['card_functions'])
				. ',notes=' . dbSqlString ($session, $_POST ['card_notes'])
				. ',');
				$message = "Adresskarte $name wurde geändert.";
		}
	} elseif (isset ($_POST ['card_new'])){
		$session->trace (TC_Gui1, 'addressEditCard-new entdeckt');
		$name = $_POST ['card_lastname'];
		dbInsert ($session, Tab_Card,
			'firstname,lastname,nickname,'
			. 'emailprivate,emailprivate2,phoneprivate,phoneprivate2,faxprivate,mobileprivate,'
			. 'emailoffice,emailoffice2,phoneoffice,phoneoffice2,faxoffice,mobileoffice,'
			. 'street,country,zip,city,functions,notes',
			dbSqlString ($session, $_POST ['card_firstname'])
			. ',' . dbSqlString ($session, $_POST ['card_lastname'])
			. ',' . dbSqlString ($session, $_POST ['card_nickname'])
			. ',' . dbSqlString ($session, $_POST ['card_emailprivate'])
			. ',' . dbSqlString ($session, $_POST ['card_emailprivate2'])
			. ',' . dbSqlString ($session, $_POST ['card_phoneprivate'])
			. ',' . dbSqlString ($session, $_POST ['card_phoneprivate2'])
			. ',' . dbSqlString ($session, $_POST ['card_faxprivate'])
			. ',' . dbSqlString ($session, $_POST ['card_mobileprivate'])
			. ',' . dbSqlString ($session, $_POST ['card_emailoffice'])
			. ',' . dbSqlString ($session, $_POST ['card_emailoffice2'])
			. ',' . dbSqlString ($session, $_POST ['card_phoneoffice'])
			. ',' . dbSqlString ($session, $_POST ['card_phoneoffice2'])
			. ',' . dbSqlString ($session, $_POST ['card_faxoffice'])
			. ',' . dbSqlString ($session, $_POST ['card_mobileoffice'])
			. ',' . dbSqlString ($session, $_POST ['card_street'])
			. ',' . dbSqlString ($session, $_POST ['card_country'])
			. ',' . dbSqlString ($session, $_POST ['card_zip'])
			. ',' . dbSqlString ($session, $_POST ['card_city'])
			. ',' . dbSqlString ($session, $_POST ['card_functions'])
			. ',' . dbSqlString ($session, $_POST ['card_notes'])
			);
		$message = "Adresskarte $name wurde erstellt.";
	}
	addressEditcard ($session, $message);
}
function addressShowCards (&$session, $message = null){
	$session->trace (TC_Gui1, 'addressShowBook:');
	
	guiStandardHeader ($session, 'Anzeigen von Adressen',
		Th_AddressHeader, Th_AddressBodyStart);
	if ($message <> null)
		guiParagraph($session, $message, false);
	guiStartForm ($session, 'search', P_ShowCards);
	guiHeadline ($session, 2, 'Suchkriterien:');
	$books = dbColumnList($session, Tab_Book, 'name', '1');
	if (! isset ($_POST ['show_book']))
		$_POST ['show_book'] =  $books [0];
	echo 'Adressbuch: ';
	guiComboBox ('show_book', $books, null, null);
	$fields = array ('Name', 'Vorname', 'Spitzname', 'EMail', 'PLZ', 'Ort', 
		'Funktion', 'Notiz');
	echo ' Auswahlkriterium: '; 
	guiComboBox ('show_choice', $fields, null, null);
	echo ' Suchmuster: ';
	guiTextField ('show_pattern', null, 16);
	echo ' ';
	guiButton ('show_search', 'Suchen');
	guiHeadline ($session, 2, 'Ausgabefelder:');
	guiCheckBox ('show_withname', 'Name');
	echo ' ';
	guiCheckBox ('show_withprivate', 'Privat');
	echo ' ';
	guiCheckBox ('show_withoffice', 'Geschäftlich');
	echo ' ';
	guiCheckBox ('show_withphone', 'Telefon');
	echo ' ';
	guiCheckBox ('show_withemail', 'EMail');
	echo ' ';
	guiCheckBox ('show_withaddress', 'Postadresse');
	echo ' ';
	guiCheckBox ('show_withfunction', 'Funktion');
	echo ' ';
	guiCheckBox ('show_withnote', 'Notiz');
	echo ' ';
	guiCheckBox ('show_withdate', 'Datum');
	guiFinishForm ($session, $session);
	if (isset ($_POST ['show_pattern'])){
		$pattern = $_POST ['show_pattern'];
		if (empty ($pattern))
			$condidtion = '1';
		else {
			$pattern = '%' . $pattern . '%';
			switch ($_POST ['show_choice']){
			case 'Name': 
				$condition = 'lastname like ' . dbSqlString ($session, $pattern);
				break;
			case 'Vorname':
				$condition = 'firstname like ' . dbSqlString ($session, $pattern);
				break;
			case 'Spitzname':
				$condition = 'nickname like ' . dbSqlString ($session, $pattern);
				break;
			case 'EMail':
				$condition = 'emailprivate like ' . dbSqlString ($session, $pattern)
					. ' or emailprivate2 like ' . dbSqlString ($session, $pattern)
					. ' or emailoffice like ' . dbSqlString ($session, $pattern)
					. ' or emailoffice2 like ' . dbSqlString ($session, $pattern);
				break;
			case 'PLZ':
				$condition = 'zip like ' . dbSqlString ($session, $pattern);
				break;
			case 'Ort':
				$condition = 'city like ' . dbSqlString ($session, $pattern);
				break;
			case 'Funktion':
				$condition = 'function like ' . dbSqlString ($session, $pattern);
				break;
			case 'Notiz':
				$condition = 'note like ' . dbSqlString ($session, $pattern);
				break;
			default:
				$condition = '1';
				break;
			}
			$what = 'lastname,firstname,country,zip,city,street,'
				// 6.............7.............8.............9............10...........11
				. 'emailprivate,emailprivate2,phoneprivate,phoneprivate2,mobileprivate,faxprivate,'
				// 12...........13...........14...........15.........16...........17
				. 'emailoffice,emailoffice2,phoneoffice,phoneoffice2,mobileoffice,faxoffice,'
				// 18........19.....20......21.22........23
				. 'functions,notes,nickname,id,changedat,createdat';
			
			addressPrintTable ($session, "select $what from " 
				. dbTable ($session, Tab_Card) . " where $condition",
				30);
		}
	}
	guiStandardBodyEnd ($session, Th_AddressBodyEnd);
	
}
function addressPrintTableHeader (&$session){
	outTableRecord ();
	outTableCellStrong ('Id');
	if (guiChecked ($session, 'show_withname'))
		outTableCellStrong ('Name' . tagNewline() . 'Vorname' . tagNewline() . 'Spitzname');
	if (guiChecked ($session, 'show_withprivate')){
		if (guiChecked ($session, 'show_withemail'))
			outTableCellStrong ('Privat-EMail');
		if (guiChecked ($session, 'show_withphone'))
			outTableCellStrong ('Privat-Telefon');
	}	
	if (guiChecked ($session, 'show_withoffice')){
		if (guiChecked ($session, 'show_withemail'))
			outTableCellStrong ('Geschäft-EMail');
		if (guiChecked ($session, 'show_withphone'))
			outTableCellStrong ('Geschäft-Telefon');
	}
	if (guiChecked ($session, 'show_withaddress'))
		outTableCellStrong ('Strasse' . tagNewline() . 'Land-PLZ-Ort');
	if (guiChecked ($session, 'show_withfunction'))
		outTableCellStrong ('Funktion');
	if (guiChecked ($session, 'show_withnote'))
		outTableCellStrong ('Notizen');
	if (guiChecked ($session, 'show_withdate'))
		outTableCellStrong ('erzeugt am' . tagNewline() . 'geändert am');
	outTableRecordEnd ();
}
function addressAddLine ($string, $value, $prefix = ''){
	if (empty ($value))
		return $string;
	else 
		return $string . (empty ($string) ? "" : tagNewline ()) . $prefix . $value;
}
function addressPrintRow (&$session, $row){
	$what = 'lastname,firstname,country,zip,city,street,'
	// 6.............7.............8.............9............10...........11
	. 'emailprivate,emailprivate2,phoneprivate,phoneprivate2,mobileprivate,faxprivate,'
	// 12...........13...........14...........15.........16...........17
	. 'emailoffice,emailoffice2,phoneoffice,phoneoffice2,mobileoffice,faxoffice,'
	// 18........19.....20......21.22........23
	. 'functions,notes,nickname,id,changedat,createdat';
	outTableRecord ();
	outTableInternLink ($session, null, 
		'?action=' . A_EditCard . '&card_id=' . $row [21],
		$row [21]);
	if (guiChecked ($session, 'show_withname')){
		$name = addressAddLine ("", $row [0]);
		$name = addressAddLine ($name, $row [1]);
		$name = addressAddLine ($name, $row [20]);
		outTableCell ($name);
	}
	if (guiChecked ($session, 'show_withprivate')){
		if (guiChecked ($session, 'show_withemail')){
			$value = addressAddLine ("", $row [6]);
			$value = addressAddLine ($value, $row [7]);
			outTableCell ($value);
		}
		if (guiChecked ($session, 'show_withphone')){
			$value = addressAddLine ("", $row [8], 'T: ');
			$value = addressAddLine ($value, $row [9], 'T: ');
			$value = addressAddLine ($value, $row [10], 'H: ');
			$value = addressAddLine ($value, $row [11], 'F: ');
			outTableCell ($value);
		}
	}	
	if (guiChecked ($session, 'show_withoffice')){
		if (guiChecked ($session, 'show_withemail')){
			$value = addressAddLine ("", $row [12]);
			$value = addressAddLine ($value, $row [13]);
			outTableCell ($value);
		}
		if (guiChecked ($session, 'show_withphone')){
			$value = addressAddLine ("", $row [14], 'T: ');
			$value = addressAddLine ($value, $row [15], 'T: ');
			$value = addressAddLine ($value, $row [16], 'H: ');
			$value = addressAddLine ($value, $row [17], 'F: ');
			outTableCell ($value);
		}
	}
	if (guiChecked ($session, 'show_withaddress')){
		$value = addressAddLine ("", $row [5]);
		$str = empty ($row [2]) ? ($row [2] . '-') : '';
		$str .= empty ($row [3]) ? ($row [3] . '-') : '';
		$str .= empty ($row [4]) ? '' : (' ' . $row [4]);
		$value = addressAddLine ($value, $str);
		outTableCell ($value);
	}
	if (guiChecked ($session, 'show_withfunction'))
		outTableCell ($row [18]);
	if (guiChecked ($session, 'show_withnote'))
		outTableCell ($row [19]);
	if (guiChecked ($session, 'show_withdate')){
		$value = addressAddLine ("", $row [23]);
		$value = addressAddLine ($value, $row [22]);
		outTableCell ($value);
	}
	outTableRecordEnd ();
}
function addressPrintTable (&$session, $query, $max_lines){
	$result = mysql_query ($query, $session->fDbInfo);
	if (! $result)
		protoc (mysql_error ());
	else {
		guiHeadline ($session, 2, 'Suchergebnis:');
		$first = true;
		$no = 0;
		if ($max_lines <= 0)
			$max_lines = 1000;
		outTable(1);
		while ($row = mysql_fetch_row ($result)) {
			if ($first){
				addressPrintTableHeader ($session);
				$first = false;
			}
			if (++$no > $max_lines)
				break;
			outTableRecord ();
			addressPrintRow ($session, $row);
			outTableRecordEnd ();
		}
		if ($first){
			echo "Die Anfrage ergab keine Ergebnisse";
			outNewline();
		} else {
			outTableEnd();
			if ($no > $max_lines)
				guiParagraph ($session, "Es gibt noch weitere Ergebnisse!", false);
		}
		mysql_free_result ($result);
	}
}
?>