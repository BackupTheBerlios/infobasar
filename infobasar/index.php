<?php
// index.php: Start page of the InfoBasar
// $Id: index.php,v 1.14 2004/10/30 23:54:44 hamatoma Exp $
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
define ('PHP_ModuleVersion', '0.6.5.3 (2004.10.28)');
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
define ('A_Edit', 'edit');
define ('A_Search', 'search');
define ('A_PageInfo', 'pageinfo');
define ('A_ShowText', 'showtext');
define ('A_Diff', 'diff');
define ('A_Show', 'show');
// Predefined pages
define ('P_Login', '!login');
define ('P_Logout', '!logout');
define ('P_Account', '!account');
define ('P_Home', '!home');
define ('P_NewPage', '!newpage');
define ('P_ModifyPage', '!modifypage');
define ('P_Search', '!search');
define ('P_Start', '!start');
define ('P_LastChanges', '!lastchanges');
define ('P_Info', '!info');
define ('P_NewWiki', '!newwiki');
define ('P_Undef', '!undef');

define ('Th_HeaderHTML', 211);
define ('Th_BodyStartHTML', 212);
define ('Th_BodyEndHTML', 213);
define ('Th_EditHeaderHTML', 214);
define ('Th_EditStartHTML', 215);
define ('Th_EditEndHTML', 216);


define ('Th_LoginHeader', 221);
define ('Th_LoginBodyEnd', 222);
define ('Th_Overview', 223);
define ('Th_InfoHeader', 224);
define ('Th_InfoBodyEnd', 225);

define ('Th_SearchHeader', 231);
define ('Th_SearchBodyStart', 232);
define ('Th_SearchBodyEnd', 233);

define ('Th_HeaderWiki', 241);
define ('Th_BodyStartWiki', 242);
define ('Th_BodyEndWiki', 243);
define ('Th_EditHeaderWiki', 244);
define ('Th_EditStartWiki', 245);
define ('Th_EditEndWiki', 246);
define ('Th_PreviewStart', 247);
define ('Th_PreviewEnd', 248);



// ------------Program

$session = new Session ($start_time);

	// All requests require the database
dbOpen($session);
if (isset ($session_no) && $session_no > 0){
	$session_no++;
	$session->trace (TC_Init, "session_no: $session_no");
}
if ((empty ($session_user)) && getLoginCookie ($session, $user, $code)
	&& dbCheckUser ($session, $user, $code) == ''){
	$session->trace (TC_Init, 'index.php: Cookie erfolgreich gelesen');
}
$rc = dbCheckSession ($session);
$do_login = false;
#$session->dumpVars ("Init");
if ($rc != null) {
	$session->trace (TC_Init, 'keine Session gefunden: ' . $rc . ' ' 
		. (empty($login_user) ? "-" : '>' . $login_user));
	$do_login = true;
} else {
		$session->trace (TC_Init, 'login_user: ' . (isset ($login_user) ? $login_user : '-')); 
		if (isset ($login_user))
			$do_login = baseLoginAnswer ($session, $rc);
		else
			$do_login = $session->fPageName == P_Login || ! isset ($session_user) || $session_user <= 0;
}
$session->trace (TC_Init, "session_no: do_login: " . ($do_login ? "t" : "f"));
if ($do_login){
		clearLoginCookie ($session);
		baseLogin ($session, $rc);
} else {
		$session->trace (TC_Init, 'index.php: std_answer: ' . (empty ($std_answer) ? '' : "($std_answer)"));
	if (isset ($action)) {
		$session->trace (TC_Init, "index.php: action: $action");
		switch ($action){
		case A_Edit: baseEditPage ($session, C_Change); break;
		case A_Search:	baseSearch ($session, ''); break;
		case A_PageInfo: basePageInfo ($session); break;
		case A_ShowText: guiShowPageById ($session, $page_id, $text_id); break;
		case A_Diff: baseDiff ($session); break;
		case A_Show: baseShowCurrentPage ($session); break;
		case '': break;
		default:
			$session->trace (TC_Error, "index.php: unbek. Aktion: $action");
			baseFormTest ($session);
			break;
		}
	} elseif (isset ($std_answer) || ! baseCallStandardPage ($session)) {
		$session->trace (TC_Init, 'index.php: keine Standardseite'
			. (isset ($_POST ['edit_save']) 
				? (" (" . $_POST ['edit_save'] . ")") : ' []'));
		if (isset ($test))
			baseTest ($session);
		else if (substr ($session->fPageName, 0, 1) == '.')
			baseNewPageReference ($session);
		else if (isset ($last_refresh))
			baseLastChanges ($session);
		else if (isset ($alterpage_insert) 
			|| isset ($alterpage_appendtemplate)
			|| isset ($alterpage_cancel))
			baseAlterPageAnswer ($session, C_New);
		elseif (isset ($alterpage_changecontent))
			baseAlterPageAnswer ($session, C_Change);
		elseif (isset ($alterpage_preview))
			baseAlterPageAnswer ($session, C_LastMode);
		elseif (isset ($alterpage_changepage))
			baseAlterPageAnswerChangePage($session);
		elseif (isset ($account_new) || isset ($account_change)
			|| isset ($account_other))
			baseAccountAnswer ($session, $account_user);
		elseif (isset ($search_title) || isset ($search_body))
			baseSearch ($session, null);
		elseif (isset ($_POST ['edit_preview']))
			baseEditPage ($session, C_Preview);
		elseif (isset ($_POST ['edit_save']) 
				|| isset ($_POST ['edit_previewandsave']) 
				|| isset ($_POST ['edit_upload']))
			baseEditPageAnswerSave ($session);
		elseif (isset ($_POST ['edit_cancel']))
			guiShowPageById ($session, $_POST ['edit_pageid'], null);
		elseif (isset ($posting_preview) || isset ($posting_insert)
			|| isset ($posting_change))
			basePostingAnswer ($session);
		elseif ( ($page_id = dbPageId ($session, $session->fPageName)) > 0)
			guiShowPageById ($session, $page_id, null);
		else {
			baseLogin ($session, null);;
		}
	}
}
exit (0);

// ------------------------------------------------------
function baseStandardLinkString (&$session, $page) {
	$session->trace (TC_Gui3, 'baseStandardLinkString');
	$rc = null;
	$module = null;
	switch ($page) {
	case P_Home: $header = 'Übersicht'; break;
	case P_Account: $header = 'Einstellungen'; break;
	case P_Search: $header = 'Wikisuche'; break;
	case P_LastChanges: $header = 'Letzte Änderungen'; break;
	case P_Start: $header = 'Persönliche Startseite'; break;
	case P_Login: $header = 'Neu anmelden'; break;
	case P_Logout: $header = 'Abmelden'; break;
	case P_Info: $header = 'Über'; break;
	case P_NewWiki: $header = 'Neue Wiki-Seite'; break;
	case P_NewPage: $header = 'Neue Seite'; break;
	default: $header = null; break;
	}
	if ($header)
		$rc = guiInternLinkString ($session, $page, $header, $module);
	return $rc;
}
function baseStandardLink (&$session, $page) {
	$session->trace (TC_Gui3, 'baseStandardLink');
	echo baseStandardLinkString ($session, $page);
}

function baseShowCurrentPage (&$session){
	if ( ($page = dbPageId ($session, $session->fPageName)) > 0)
		guiShowPageById ($session, $page, null);
	else {
		$session->SetLocation (P_Home);
		baseHome ($session);
	}
}
function baseLogin (&$session, $message) {
	global $login_user, $login_email;
	guiStandardHeader ($session, "Anmeldung f&uuml;r den InfoBasar", Th_LoginHeader,
		null);
	guiStartForm ($session, 'login', P_Login);
	if (! empty ($message)) {
		$message = preg_replace ('/^\+/', '+++ Fehler: ', $message);
		guiParagraph ($session, $message, false);
	}
	outTableAndRecord ();
	outTableTextField ('Benutzername:', 'login_user', $login_user, 32, 32);
	outTableRecordDelim();
	outTablePasswordField ('Passwort:', 'login_code', "", 32, 32);
	outTableRecordDelim();
	outTableButton (' ', 'but_login', 'Anmelden');
	outTableAndRecordEnd ();
	guiLine ($session, 2);
	guiParagraph ($session, 'Passwort vergessen?', false);
	outTableAndRecord();
	outTableTextField ('EMail-Adresse:', 'login_email', $login_email, 32, 0);
	outTableRecordDelim();
	outTableButton (' ', 'but_forget', 'Passwort ändern');
	outTableAndRecordEnd();
	echo '(Das neue Passwort wird dann zugeschickt.)';
	outNewline();
	outStrong('Achtung:');
	echo 'Benutzername muss ausgefüllt sein!';
	guiFinishForm ($session, $session);
	guiStandardBodyEnd ($session, Th_LoginBodyEnd);
	return 1;
}
function baseLoginAnswer (&$session, &$message) {
	global $login_user, $login_code, $session_user, $but_forget, $login_email, $session_no;
	$session->trace (TC_Gui1, 'baseLoginAnswer; login_user: ' . $login_user . " but_forget: $but_forget");
	$login_again = true;
	$message = null;
	$login_again = false;
	if (isset ($but_forget)) {
		if (empty ($login_user))
			$message = "+kein Benutzername angegeben";
		elseif (empty ($login_email))
			$message = "+keine EMail-Adresse angegeben";
		else {
			$row = dbSingleRecord ($session, 'select id,email from ' . dbTable ($session, T_User)
				. ' where name=' . dbSqlString ($session, $login_user));
			if (! $row)
				$message = "+unbekannter Benutzer";
			elseif (empty ($row [1]))
				$message = "+keine EMail-Adresse eingetragen";
			elseif (strcasecmp ($row [1], $login_email) != 0)
				$message = "+EMail-Adresse ist nicht bekannt";
			else {
				sendPassword ($session, $row [0], $login_user, $login_email);
				$message = 'Das Passwort wurde an ' . $login_email . ' verschickt';
			}
		}
		$login_again = true;
	} else {
		$message = dbCheckUser ($session, $login_user, $login_code);
		if (! empty ($message))
			$login_again = true;
		else {
			setLoginCookie ($session, $login_user, $login_code);
			$session->setPageName (P_Start);
			$session_no = 1;
		}
	}
	return $login_again;
}	
function baseLogout (&$session){
	global $last_page, $session_user, $session_start, $session_no;
	clearLoginCookie ($session);
	setLoginCookie ($session, '?', '?');
	$last_page = $session_user = $session_start = null;
	$session->fUserId = null;
	$name = $session->fUserName;
	$session->fUserName = null;
	$session_no = -99999;
	
	baseLogin ($session, 'Daten für automatische Anmeldung wurden gelöscht: ' . $name);
		
}

function baseSplitRights (&$session, &$account_right_user, &$account_right_rights, 
	&$account_rights_posting, &$account_rights_pages){
	$rights = $session->fUserRights;
	if (preg_match ('/user(' . R_KindSequence . ')/', $rights, $match))
		$account_right_user = $match [1];
	if (preg_match ('/rights(' . R_KindSequence . ')/', $rights, $match))
		$account_right_rights = $match [1];
	if (preg_match ('/posting(' . R_KindSequence . ')/', $rights, $match))
		$account_right_posting = $match [1];
	if (preg_match ('/pages(' . R_KindSequence . ')/', $rights, $match))
		$account_right_pages = $match [1];
}
	

function baseAccount (&$session, $message) {
	global $account_user, $account_code, $account_code2, $account_rights,
		$account_email,
		$account_locked, $account_user2, $account_theme, $account_width,
		$account_height, $account_maxhits,
		$account_right_posting, $account_right_user, $account_right_pages, $account_right_rights,
		$account_startpage, $account_startpageoffer;
	global $login_user, $login_passw;
	$session->trace (TC_Gui1, 'baseAccount');
	$reload = false;
	if (empty ($account_user)) {
		$account_user = $session->fUserName;
		$reload = true;
	}
	if (! empty ($account_user2) && $account_user != $account_user2) {
		$account_user = $account_user2;
		$reload = true;
	}
	if (! $reload)
		$id = dbUserId ($session, $account_user);
	else {
		list ($id, $account_locked, $account_width,
			$account_height, $account_maxhits,
			$account_theme, $account_startpage, $account_email)
			= dbGetRecordByClause ($session, T_User,
			'id,locked,width,height,maxhits,theme,startpage,email',
			'name=' . dbSqlString ($session, $account_user));
	}
	guiStandardHeader ($session, 'Einstellungen f&uuml;r ' . $account_user,
		Th_StandardHeader, Th_StandardBodyStart);
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	guiStartForm ($session, 'account', P_Account);
	outTableAndRecord();
	outTableCell ('Benutzername');
	outTableDelim();
	guiHiddenField ('account_user', $account_user);
	guiHeadline ($session, 2, $account_user);
	outTableDelimEnd();
	outTableRecordDelim();
	outTablePasswordField ('Passwort:', 'account_code', '', 64, 32);
	outTableRecordDelim ();
	outTablePasswordField ('Wiederholung:', 'account_code2', '', 64, 32);
	outTableRecordDelim ();
	outTableTextField ('EMail:', 'account_email', $account_email, 64, 64);
	outTableRecordDelim ();
	outTableCheckBox ('Gesperrt', 'account_locked', 'Gesperrt',
		$account_locked == C_CHECKBOX_TRUE);
	outTableRecordDelim ();
	dbGetThemes ($session, $theme_names, $theme_numbers);
	outTableComboBox ('Design:', 'account_theme', $theme_names, $theme_numbers, 
		array_search ($account_theme, $theme_numbers));
	outTableRecordDelim ();
	outTableTextField ('Eingabefeldbreite:', 'account_width', $account_width, 64, 3);
	outTableRecordDelim ();
	outTableTextField ('Eingabefeldhöhe:', 'account_height',
		$account_height, 64, 3);
	outTableRecordDelim ();
	outTableTextField ('Zahl Suchergebnisse:', 'account_maxhits', 
		$account_maxhits, 64, 3);
	outTableRecordDelim ();
	$names = array ('WikiSeite:', 'Übersicht', 'Einstellungen',
			'Wikisuche', 'Letze Änderungen', 'StartSeite', 'Hilfe');
	$values = array ('', P_Home, P_Account, 
			P_Search, P_LastChanges, 'StartSeite', 'Hilfe');
	if ( ($pos = strpos ($account_startpage, '!')) == 0 && is_int ($pos))
		$ix = array_search ($account_startpage, $values);
	else
		$ix = 0;
	outTableCell ('Startseite:');
	outTableDelim ();
	guiComboBox ('account_startpageoffer', $names, $values, $ix);
	echo ' ';
	guiTextField ("account_startpage", $account_startpage, 45, 128);
	outTableDelimAndRecordEnd ();
	modUserTableData ($session, $id);
	outTableEnd();
	modUserOwnData ($session, $id);
	outNewline();
	guiButton ("account_change", "&Auml;ndern");
	outNewline();
	outNewline();
	
	$change = $session->hasRight (R_User, R_Put);
	$new = $session->hasRight (R_User, R_New);
	$new = $session->fUserId <= 2 || $session->fUserName == 'wk' || $session->fUserName == 'admin';
	$change = $new;
	if ($change || $new){
		guiLine ($session, 2);
		outTable ();
		outTableRecord();
		outTableRecordEnd();
		outTableTextField ('Name:', "account_user2", $account_user2, 32, 32);
		outTableRecordDelim();
		outTableCell ('');
		outTableDelim();
		if ($change)
			guiButton ("account_other", "Benutzer wechseln");
		if ($new){
			echo " "; guiButton ("account_new", "Neu");
		}
	}
	outTableDelimEnd();
	outTableAndRecordEnd();
	guiFinishForm ($session, $session);
	guiStandardBodyEnd ($session, Th_StandardBodyEnd);
}
function baseAccountAnswer(&$session, $user) {
	global $account_user, $account_code, $account_code2, $account_email, $account_rights,
		$account_locked, $account_new, $account_change, $account_name,
		$account_other, $account_user2,  $account_theme,
		$account_width, $account_height, $account_maxhits,
		$account_startpage, $account_startpageoffer;

	$session->trace (TC_Gui1, 'baseAccountAnswer');
	$message = '';
	$code = encryptPassword ($session, $account_user, $account_code);
	$locked = dbSqlString ($session, ! empty ($account_locked));
	if (! empty ($account_startpageoffer))
		$account_startpage = $account_startpageoffer;
	if (isset ($account_new)) {
		if ($account_user2 == '')
			$message = '+++ Kein Benutzername angegeben';
		elseif (dbGetValueByClause ($session, T_User,
			'count(*)', 'name=' + dbSqlString ($session, $account_user)) > 0)
			$message = '+++ Name schon vorhanden: ' + $account_user2;
		else {
			$uid = dbUserAdd ($session, $account_user2, $code,
				dbSqlString ($session, false), $account_theme, $account_width, $account_height,
				$account_maxhits, $account_startpage, $account_email);
			modUserStoreData ($session, true, $uid);
			
			$message = "Benutzer $account_user2 wurde angelegt. ID: " . $uid;
		}
	} elseif (isset ($account_change)) {
		if (! empty ($account_code) && $account_code <> $account_code2)
			$message = '+++ Passwort stimmt mit Wiederholung nicht überein';
		elseif (! ($uid = dbUserId ($session, $account_user)) || empty ($uid))
			$message = '+++ unbekannter Benutzer: ' . $account_name;
		elseif ( ($message = modUserCheckData ($session, true, $uid)) != null)
			;
		else {
			if (empty ($account_theme))
				$account_theme = Theme_Standard;
			$what = 'locked=' . $locked . ',';
			if (! empty ($account_code))
				$what .= 'code=' . dbSqlString ($session, $code) . ",";
			$what .= "theme=$account_theme,width=$account_width,"
			. 'height=' . (0 + $account_height)
			. ',maxhits=' . (0 + $account_maxhits)
			. ',startpage=' . dbSqlString ($session, $account_startpage)
			. ',email=' . dbSqlString ($session, $account_email)
			 . ',';
			dbUpdate ($session, T_User, $uid, $what);
			modUserStoreData ($session, false, $uid);
			$message = 'Daten für ' . $account_user . ' (' . $uid
				. ') wurden geändert';
		}
	} elseif ($account_other) {
		if (empty ($account_user2))
			$message = '+++ kein Benutzername angegeben';
		elseif (! dbUserId ($session, $account_user2))
			$message = '+++ Unbekannter Benutzer: ' . $account_user2;
	} else {
		$message = 'keine Änderung';
	}
	baseAccount ($session, $message);
}
function baseOutStandardLink (&$session, $name, $description){
	outTableRecord();
	outTableDelim();
	baseStandardLink ($session, $name);
	outTableDelimEnd();
	outTableCell ($description);
	outTableRecordEnd();
}
function baseHome (&$session) {
	global $session_id, $session_user;

	$session->trace (TC_Gui1, 'baseHome');
	if ( ($text = guiParam ($session, Th_Overview, null)) != null
		&& ! empty ($text))
		echo $text;
	else {
		guiStandardHeader ($session, 'Übersicht', Th_StandardHeader,
			Th_StandardBodyStart);
		guiHeadline ($session, 1, 'Willkommen ' . $session->fUserName);
		outTable ();
		baseOutStandardLink($session, P_Account, 
			'Benutzerspezifische Einstellungen f&uuml;r ' . $session->fUserName);
		baseOutStandardLink($session, P_Logout, 'Abmelden');
		baseOutStandardLink($session, P_Login, 'Neu anmelden');
		baseOutStandardLink($session, P_Start, 
			'Startseite, wie sie in den benutzerspezifischen Einstellungen festgelegt ist');
		baseOutStandardLink($session, P_Info, 'Information &uuml;ber den InfoBasar');
		outTableRecordAndDelim ();
		outTableCell (tagNewline() .tagStrong ('Wiki- und HTML-Seiten:'));
		outTableCell (' ');
		outTableRecordDelim();
		outTableInternLink (null, $session, encodeWikiName ($session, 'StartSeite'), 
			'StartSeite'); 
		outTableCell ('Wiki-Startseite');
		baseOutStandardLink($session, P_LastChanges, 
			'Liste der in den letzten n Tagen ge&auml;nderten Wikiseiten');
		baseOutStandardLink($session, P_Search, 
			'Titel- oder Volltextsuche im Wikibereich');
		baseOutStandardLink($session, P_NewWiki, 'Anlegen einer neue Wikiseite');
		baseOutStandardLink($session, P_NewPage, 
			'Anlegen einer neue Seite (Wiki oder HTML)');
		modOverview ($session);
		outTableEnd();
		guiStandardBodyEnd ($session, Th_StandardBodyEnd);
	}
}
function baseEditPage (&$session, $mode, 
	$message = null,  $message2 = null, $type = M_Undef, $name = null) {
	if ($mode == C_New){
		$pageid = 0;
		$textid = 0;
		$pagename = $name == null ? "" : $name;
		$content = $name != null && strpos ($pagename, 'ategorie') == 1
			? "<?plugin BackLinks?>\n----\nKategorieKategorie" 
			: "";
		$changedby = $session->fUserName;
		$changedat = "";
		$mimetype = $type; 
		$textidpred = 0;
	} elseif (isset ($_POST ['edit_content'])){
		$pagename = $_POST ['edit_pagename'];
		$pageid = $_POST ['edit_pageid'];
		$textid = $_POST ['edit_textid'];
		$content = textAreaToWiki ($session, $_POST ['edit_content']);
		$changedby = $_POST ['edit_changedby'];
		$changedat = $_POST ['edit_changedat'];
		$mimetype = $_POST ['edit_mimetype'];
		$textidpred = $_POST ['edit_textidpred'];
	} else {
		$pagename = $session->fPageName;
		list ($pageid, $texttype) = dbGetRecordByClause ($session, T_Page,
				'id,type', 'name=' . dbSqlString ($session, $pagename));
		$mimetype = textTypeToMime ($texttype);
		$textidpred = dbGetLastText ($session,$pageid);
		list ($content, $changedat, $changedby)
			= dbGetRecordById ($session, T_Text, $textidpred,
				'text,createdat,createdby');
		$textid = null;
	}
	$session->setPageData ($pagename, $changedat, $changedby);
	getUserParam ($session, U_TextAreaWidth, $textarea_width);
	getUserParam ($session, U_TextAreaHeight, $textarea_height);
	if ($mode == C_New)
		$header =  "Neue Seite";
	else 
		$header = $pagename . ' (in Bearbeitung)';
	if ($mimetype == M_Wiki)
		guiStandardHeader ($session, $header, Th_EditHeaderWiki, Th_EditStartWiki);
	else
		guiStandardHeader ($session, $header, Th_EditHeaderHTML, Th_EditStartHTML);
	if (isset ($_POST ['edit_preview'])) {
		echo guiParam ($session, Th_PreviewStart, '<h1>Vorschau von '
			. $session->fPageName
			. '</h1><p>Warnung: Der Text ist noch nicht gesichert!</p>');
		guiFormatPage ($session, $mimetype, $content);
		echo guiParam ($session, Th_PreviewEnd, '<h1>Ende der Vorschau</h1>');
	}
	echo '<form enctype="multipart/form-data" action="' . $session->fScriptURL
		. '" method="post">' . "\n";
	
	guiHiddenField ('edit_pagename', $pagename);
	guiHiddenField ('edit_pageid', $pageid);
	guiHiddenField ('edit_textid', $textid);
	guiHiddenField ('edit_textidpred', $textidpred);
	guiHiddenField ('edit_changedat', $changedat);
	guiHiddenField ('edit_changedby', $changedby);

	if (! empty ($message)){
		outParagraph();
		outStrong (htmlentities ($message));
		outParagraphEnd();
	}
	if (! empty ($message2)){
		outParagraph();
		outStrong (htmlentities ($message2));
		outParagraphEnd();
	}
	outTable ();
	outTableRecordAndDelim();
	outTable ();
	if ($mode == C_New){
		outTableRecord();
		outTableTextField('Name:', 'edit_name', $name, 43, 64);
		outTableRecordEnd();
	}
	outTableRecord();
	if ($mode == C_New && $type == M_Undef)
		outTableComboBox ('Typ', 'edit_mimetype', array (M_Wiki, M_HTML), null);
	else {
		outTableRecord ();
		outTableCell ('Typ:');
		outTableCell ($mimetype);
		guiHiddenField ('edit_mimetype', $mimetype);
		outTableRecordEnd ();
	}
	if ($mode == C_New){
		$templates = dbColumnList ($session, T_Page, 'name', 
			'name like ' . dbSqlString ($session, 'Vorlage%'));
		if (count ($templates) > 0){
			outTableRecord ();
			outTableCell ('Seitenvorlage:');
			outTableDelim ();
			guiComboBox('edit_template', $templates, null);
			echo (' ');
			guiButton ('edit_appendtemplate', 'Vorlage einkopieren');
			outTableDelimAndRecordEnd();
		}
	}
	outTableEnd();
	outTableDelimAndRecordEnd();
	outTableRecordAndDelim();
	guiTextArea ('edit_content', $content, $textarea_width, $textarea_height);
	outTableDelimAndRecordEnd();
	outTableRecordAndDelim();
	outTable (0, '100%');
	outTableRecord();
	outTableButton (null, 'edit_save', 'Speichern (fertig)');
	outTableDelim(AL_Justify);
	guiButton ('edit_previewandsave', 'Zwischenspeichern');
	echo ' '; 
	guiButton ('edit_preview', ' Vorschau');
	outTableCellDelim();
	guiButton ('edit_cancel', ' Verwerfen'); 
	if (! $session->testFeature (FEATURE_UPLOAD_ALLOWED)){
		echo ' Breite: '; guiTextField ("textarea_width", $textarea_width, 3, 3);
		echo ' H&ouml;he: ';
	} else {	
		outTableDelimEnd();
		outTableTextField ('Breite:', 'textarea_width', $textarea_width, 3, 3);
		outTableRecordEnd();
		outTableRecord();
		outTableCell ('Bild einf&uuml;gen:');
		guiHiddenField ('MAX_FILE_SIZE', 500000);
		outTableDelim(AL_Justify);
		echo '<input name="edit_upload_file" type="file">';
		outTableDelimEnd();
		outTableButton (null, 'edit_upload', 'Hochladen');
		outTableCell ('H&ouml;he:');
		outTableDelim();
	} 
	guiTextField ("textarea_height", $textarea_height, 3, 3);
	outTableAndRecordEnd();
	outTableDelimEnd();
	outTableAndRecordEnd();
	
	guiFinishForm ($session, $session);
	outNewline();
	guiStandardBodyEnd ($session,
		$mimetype == M_Wiki ? Th_EditEndWiki : Th_EditEndHTML);
}
function baseEditPageAnswer (&$session, $mode){
	$session->trace (TC_Gui1, 'baseEditPageAnswer');
	if (! isset ($_POST ['edit_cancel'])) {
		$_POST ['edit_name'] = normalizeWikiName ($session, $_POST ['edit_name']);
		if ($mode == C_LastMode)
			$mode = $_POST ['edit_mimetype'];
		$content = $_POST ['edit_content'];
		$len = strlen ($content);
		$message = null;
		$content = textAreaToWiki ($session, $content);
		$content = extractHtmlBody ($content);
		if (isset ($_POST ['edit_preview']) 
			|| isset ($_POST ['edit_appendtemplate']))
			;
		elseif (empty ($_POST ['edit_pagename']))
			$message = '+++ kein Seitenname angegeben';
		elseif (dbSingleValue ($session,
				'select count(*) from ' . dbTable ($session, T_Page)
				. ' where name=' 
					. dbSqlString ($session, $_POST ['edit_pagename'])) > 0)
			$message = '+++ Seite existiert schon: ' . $_POST ['edit_pagename'];
		else {
			$read_group = 0;
			$write_group = 0;
			if (empty ($_POST ['edit_mimetype']))
				$_POST ['edit_mimetype'] = M_Wiki;
			$page = dbInsert ($session, T_Page,
				'name,type,createdat,changedat,readgroup,writegroup',
				dbSqlString ($session, $_POST ['edit_pagename']) .',' 
				. dbSqlString ($session, $_POST ['edit_mimetype'])
				. ',now(),now(),' . $read_group . ',' . $write_group);
			dbInsert ($session, T_Text, 'page,type,text,createdby,createdat,changedat',
				$page
				. "," . dbSqlString ($session, mimeToTextType ($_POST ['edit_mimetype']))
				. ',' . dbSqlString ($session, $_POST ['edit_content'])
				. ',' . dbSqlString ($session, $session->fUserName)
				. ',now(),now()');
		}
		$message2 = $len == strlen ($alterpage_content)
			? '' : 'Es wurde der Rumpf (body) extrahiert.';
		# $session->SetLocation (encodeWikiName ($session, $alterpage_name));
	} // ! isset ($alterpage_cancel)
	if (isset ($alterpage_cancel)){
		if (! empty ($alterpage_name) && dbPageId ($session, $alterpage_name) > 0)
			guiShowPage ($session, $alterpage_mime, $alterpage_name,
				$alterpage_name);
		else
			baseHome ($session);
	} elseif ($message != null || isset ($alterpage_preview)
		|| isset ($alterpage_appendtemplate))
		baseAlterPage ($session, $mode, $message, $message2, $alterpage_mime);
	else
		guiShowPage ($session, $alterpage_mime, $alterpage_name,
			$alterpage_name);
}

function baseEditPageAnswerSave (&$session)
{
	global $_FILE;

	$session->trace (TC_Gui1, 'baseEditPageAnswerSave');
	if (isset ($_POST ['edit_upload'])){
		$session->trace (TC_Gui1, 'guiEditPageSaveAnswer:');
		$message = guiUploadFileAnswerUnique ($session, "/pic/",
			null, 'edit_upload_file', $name);
		$_POST ['edit_content'] .= "\n\n[http:pic/$name $name]\n\n";
	} else {
		$content = textAreaToWiki ($session, $_POST ['edit_content']);
		$new_textid = dbGetLastText ($session, $_POST ['edit_pageid']);
		$message = '';
		if ($new_textid > $_POST ['edit_textidpred'] 
			&& (! isset ($_POST ['edit_textid']) 
				|| $new_textid > $_POST ['edit_textid']))
			$message = "+++ Warnung: Seite wurde inzwischen geändert! "
				. "Bitte Differenz ermitteln und erneut eintragen! "
				. $new_textid . " /  " . $_POST ['edit_textidpred'];
		$date = dbSqlDateTime ($session, time ());
		if (empty ($_POST ['edit_textid'])){
			$_POST ['edit_textid'] = dbInsert ($session, T_Text,
				'page,type,createdat,changedat,createdby,text',
				$_POST ['edit_pageid'] . ',' 
					. dbSqlString ($session, 
						mimeToTextType ($_POST ['edit_mimetype'])
					. ",$date,$date," 
					. dbSqlString ($session, $session->fUserName)
					. ',' . dbSqlString ($session, $_POST ['edit_content'])));
			dbUpdate ($session, T_Text, $new_textid, 'replacedby=' 
				. $_POST ['edit_textid'] . ',');
		} else {
			dbUpdate ($session, T_Text, $_POST ['edit_textid'],
				"text=" . dbSqlString ($session, $_POST ['edit_content']) . ",");
		}
	}
	unset ($_POST ['edit_save']);
	if (empty ($message) && ! isset ($_POST ['edit_previewandsave']) 
		&& ! isset ($_POST ['edit_upload'])){
		guiShowPageById ($session, $_POST ['edit_pageid'], null);
	} else {
		baseEditPage ($session, $message, $message);
	}
}

function baseAlterPage (&$session, $mode, $message, $message2, $type = M_Undef){
	global $alterpage_name, $alterpage_content, $textarea_width,
		$textarea_height, $alterpage_content, $alterpage_mime,
		$alterpage_lastmode, $alterpage_preview, 
		$alterpage_template, $alterpage_appendtemplate;
	$session->trace (TC_Gui1, 'baseAlterPage');
	if ($type != M_Undef)
		$alterpage_mime = $type;
	if ($alterpage_mime == M_Wiki)
		guiStandardHeader ($session, $mode != C_Change
			? 'Neue Seite eintragen' : 'Seite ändern', Th_EditHeaderWiki,
			Th_EditStartWiki);
	else
		guiStandardHeader ($session, $mode != C_Change
			? 'Neue Seite eintragen' : 'Seite ändern', Th_EditHeaderHTML,
			Th_EditStartHTML);

	getUserParam ($session, U_TextAreaWidth, $textarea_width);
	getUserParam ($session, U_TextAreaHeight, $textarea_height);
	if (! empty ($message))
		guiParagraph ($session, $message, true);
	if (! empty ($message2))
		guiParagraph ($session, $message2, true);

	if (isset ($alterpage_preview)) {
		guiHeadline ($session, 1, 'Vorschau');
		guiFormatPage ($session, $alterpage_mime, $alterpage_content);
		guiLine ($session, 1);
	}
	if (isset ($alterpage_appendtemplate)){
		$page_id = dbPageId ($session,  $alterpage_template);
		if ($page_id > 0){
			$id = dbGetLastText ($session, $page_id);
			$alterpage_content .= dbSingleValue ($session, 'select text from '
				. dbTable ($session, T_Text) . ' where id=' . (0+$id)); 
		}
	}
	guiStartForm ($session, 'alterpage');
	guiHiddenField ('alterpage_lastmode', $mode);
	echo "<table border=\"0\">\n<tr><td>Name:</td><td>";
	guiTextField ('alterpage_name', $alterpage_name, 64, 64);
	if ($mode == C_Change)
		guiButton ('alterpage_changepage', 'Seite laden');
	echo "</td></tr>\n<tr><td>Typ:</td><td>";
	if ($mode == C_New && $type == M_Undef)
		guiComboBox ('alterpage_mime', array (M_Wiki, M_HTML), null);
	else {
		echo $alterpage_mime;
		guiHiddenField ($session, 'alterpage_mime', $alterpage_mime);
	}
	if ($mode == C_New){
		$templates = dbColumnList ($session, T_Page, 'name', 
			'name like ' . dbSqlString ($session, 'Vorlage%'));
		if (count ($templates) > 0){
			echo "</td></tr>\n<tr><td>Seitenvorlage:</td><td>";
			guiComboBox('alterpage_template', $templates, null);
			echo (' ');
			guiButton ('alterpage_appendtemplate', 'Vorlage einkopieren');
		}
	}
	echo "</td></tr>\n<tr><td>Inhalt:</td><td>";
	guiTextArea ("alterpage_content", $alterpage_content,
		$textarea_width, $textarea_height);

	echo "</td></tr>\n<tr><td></td><td>";
	if ($mode != C_Change)
		guiButton ('alterpage_insert', 'Eintragen');
	else
		guiButton ('alterpage_changecontent', 'Ändern');
	echo " "; guiButton ('alterpage_preview', 'Vorschau');
	echo " "; guiButton ('alterpage_cancel', 'Abbrechen');
	echo "<br /><br />Eingabefeld: Breite: ";
	guiTextField ("alterpage_width", $textarea_width, 3, 3);
	echo " Höhe: ";
	guiTextField ("alterpage_height", $textarea_height, 3, 3);
	echo "</td></tr>\n</table>\n";
	guiFinishForm ($session, $session);
	if ($alterpage_mime == M_Wiki)
		guiStandardBodyEnd ($session, Th_EditEndWiki);
	else
		guiStandardBodyEnd ($session, Th_EditEndHTML);
}

function baseNewPageReference (&$session) {
	global $alterpage_name, $alterpage_content, $textarea_width, $alterpage_mime,
		 $textarea_height, $alterpage_content, $alterpage_insert, 
		 $alterpage_appendtemplate;
	$session->trace (TC_Gui1, 'baseNewPageReference');
	$name = substr ($session->fPageName, 1);
	if ( ($page = dbPageId ($session, $name)) > 0)
		guiShowPageById ($session, $page, null);
	else {
		baseEditPage ($session, C_New, null, null, M_Wiki, $name);
	}
}

function baseAlterPageAnswer (&$session, $mode){
	global $alterpage_name, $alterpage_content, $textarea_width,
		 $textarea_height, $alterpage_content, $alterpage_insert,
		 $alterpage_preview, $alterpage_lastmode, $alterpage_mime,
		 $alterpage_appendtemplate, $alterpage_cancel;
	$session->trace (TC_Gui1, 'baseAlterPageAnswer');
	if (! isset ($alterpage_cancel)) {
		$alterpage_name = normalizeWikiName ($session, $alterpage_name);
		if ($mode == C_LastMode)
			$mode = $alterpage_lastmode;
		$len = strlen ($alterpage_content);
		$message = null;
		$alterpage_content = textAreaToWiki ($session, $alterpage_content);
		$alterpage_content = extractHtmlBody ($alterpage_content);
		if (isset ($alterpage_preview) || isset ($alterpage_appendtemplate))
			;
		elseif (empty ($alterpage_name))
			$message = '+++ kein Seitenname angegeben';
		elseif (dbSingleValue ($session,
				'select count(*) from ' . dbTable ($session, T_Page)
				. ' where name=' . dbSqlString ($session, $alterpage_name)) > 0)
			$message = '+++ Seite existiert schon: ' . $alterpage_name;
		else {
			$read_group = 0;
			$write_group = 0;
			if (empty ($alterpage_mime))
				$alterpage_mime = TT_Wiki;
			$page = dbInsert ($session, T_Page,
				'name,type,createdat,changedat,readgroup,writegroup',
				dbSqlString ($session, $alterpage_name) .',' . dbSqlString ($session, $alterpage_mime)
				. ',now(),now(),' . $read_group . ',' . $write_group);
			dbInsert ($session, T_Text, 'page,type,text,createdby,createdat,changedat',
				$page
				. "," . dbSqlString ($session, $alterpage_mime)
				. ',' . dbSqlString ($session, $alterpage_content)
				. ',' . dbSqlString ($session, $session->fUserName)
				. ',now(),now()');
		}
		$message2 = $len == strlen ($alterpage_content)
			? '' : 'Es wurde der Rumpf (body) extrahiert.';
		# $session->SetLocation (encodeWikiName ($session, $alterpage_name));
	} // ! isset ($alterpage_cancel)
	if (isset ($alterpage_cancel)){
		if (! empty ($alterpage_name) && dbPageId ($session, $alterpage_name) > 0)
			guiShowPage ($session, $alterpage_mime, $alterpage_name,
				$alterpage_name);
		else
			baseHome ($session);
	} elseif ($message != null || isset ($alterpage_preview)
		|| isset ($alterpage_appendtemplate))
		baseAlterPage ($session, $mode, $message, $message2, $alterpage_mime);
	else
		guiShowPage ($session, $alterpage_mime, $alterpage_name,
			$alterpage_name);
}

function baseAlterPageAnswerChangePage (&$session){
	global $alterpage_name, $alterpage_content, $textarea_width,
		 $textarea_height, $alterpage_content, $alterpage_mime,
		 $alterpage_changepage, $alterpage_changecontent,
		 $alterpage_previe;
	$session->trace (TC_Gui1, 'baseAlterPageAnswerChangePage');
	$alterpage_name = normalizeWikiName ($session, $alterpage_name);

	$len = strlen ($alterpage_content);
	$alterpage_content = extractHtmlBody ($alterpage_content);
	$message = $message2 = null;
	if (empty ($alterpage_name))
		$message = '+++ kein Seitenname angegeben';
	if ( ($page_id = dbPageId ($session, $alterpage_name)) <= 0)
		$message = '+++ Seite nicht gefunden: ' . $alterpage_name;
	else {
		$readgroup = $writegroup = 0;
		dbUpdate ($session, T_Page, $page_id,
			"changedat=now(),readgroup=$readgroup,writegroup=$writegroup,");
		dbInsert ($session, T_Text, 'page,type,text,createdby',
			$page_id
			. "," . dbSqlString ($session, $alterpage_mime)
			. ',' . dbSqlString ($session, $alterpage_content)
			. ',' . dbSqlString ($session, $session->fUserName));
		$message = 'Seite ' . $alterpage_name . ' wurde geändert';
	}
	if ($len != strlen ($alterpage_content))
		$message2 = 'Es wurde der Rumpf (body) extrahiert.';
	if ($message != null)
		baseAlterPage ($session, C_Change, $message, $message2);
	else{
		$session->SetLocation (encodeWikiName ($session, $alterpage_name));
		guiShowPage ($session, $alterpage_name);
	}
}
function baseSearch (&$session, $message){
	global $search_titletext, $search_maxhits, $search_bodytext, $last_pagename,
		$search_title, $search_body;
	$session->trace (TC_Gui1, 'baseSearch');
	if (! isset ($last_pagename))
		$last_pagename = $session->fPageName;
	if (! isset ($search_bodytext) && isset ($search_titletext))
		$search_bodytext = $search_titletext;
	getUserParam ($session, U_MaxHits, $search_maxhits);
	guiStandardHeader ($session, 'Suchen auf den Wiki-Seiten',
		Th_SearchHeader, Th_SearchBodyStart);
	if (isset ($search_title) || isset ($search_body))
		baseSearchResults ($session);
	guiStartForm ($session, 'search', P_Search);
	guiHiddenField ('last_pagename', $last_pagename);
	outTableAndRecord();
	outTableCell ('Titel:');
	outTableDelim();
	guiTextField ('search_titletext', $search_titletext, 32, 64);
	echo " "; guiButton ('search_title', "Suchen");
	outTableDelimAndRecordEnd();
	outTableRecord();
	outTableCell ('Beitrag:');
	outTableDelim();
	guiTextField ('search_bodytext', $search_bodytext, 32, 64);
	echo " "; guiButton ('search_body', "Suchen");
	outTableDelimAndRecordEnd();
	outTableTextField('Maximale Trefferzahl:', 'search_maxhits',
		$search_maxhits, 10, 10);
	outTableAndRecordEnd();
	guiFinishForm ($session, $session);
	outParagraph ();
	outStrong ('Hinweis:');
	outNewline();
	echo 'Vorl&auml;ufig nur ein Suchbegriff m&ouml;glich.';
	outNewline();
	echo 'Joker (Wildcards) sind % (beliebig) und _ (1 Zeichen).';
	outNewline();
	outStrong ('Bsp:');
	outNewline();
	outQuotation('a_t ');
	echo ' findet "Kin';
	outStrong ('ast');
	echo '" und "';
	outStrong ('Amt');
	echo 'sperson", aber nicht "h';
	outStrong ('a');
	echo 's';
	outStrong ('st');
	echo '"';
	outNewline();
	outQuotation('Hilfe%format');
	echo ' findet ';
	outStrong ('Hilfe');
	echo 'Bei';
	outStrong ('Format');
	echo 'ierung und "';
	outStrong ('Hilfe');
	echo ' f&uuml;r ein Datei';
	outStrong ('format');
	echo '".',
	outParagraphEnd();
	guiStandardBodyEnd ($session, Th_SearchBodyEnd);
}
function baseSearchResults (&$session){
	global $search_titletext, $search_title, $search_maxhits,
		$search_bodytext, $search_body;

	$session->trace (TC_Gui1, 'baseSearchAnswer');
	if (isset ($search_title)) {
		if (empty ($search_titletext))
			guiParagraph ($session, $session, '+++ kein Seitentitel angegeben', false);
		$row = dbFirstRecord ($session,
				'select name,type from ' . dbTable ($session, T_Page)
				. ' where name like ' . dbSqlString ($session, "%$search_titletext%")
				. " limit $search_maxhits");
		if (! $row)
			guiParagraph ($session, '+++ keine passenden Seiten gefunden', false);
		else {
			outTable (1);
			outTableRecord();
			outTableCell ('Seite:');
			outTableCell ('Typ:');
			outTableRecordEnd();
			while ($row) {
				outTableRecord();
				outTableInternLink(null, $session, 
						encodeWikiName ($session, $row[0]), $row[0]);
				outTableCell (textTypeToMime($row[1]));
				outTableRecordEnd();
				$row = dbNextRecord ($session);
			}
			outTableEnd();
		}
	} else {
		if (empty ($search_bodytext))
			guiParagraph ($session, '+++ kein Suchtext angegeben');
		else {
			$row = dbFirstRecord ($session,
					'select page,text,createdby,createdat from '
					. dbTable ($session, T_Text)
					. ' where replacedby is null and text like '
					. dbSqlString ($session, "%$search_bodytext%")
					. " limit $search_maxhits");
			if (! $row)
				guiParagraph ($session, '+++ keine passende Seiten gefunden', false);
			else {
				echo '<table border="1"><tr><td>Seite:</td><td>Typ:</td>'
				. '<td>von</td><td>Letzte &Auml;nderung</td><td>Fundstelle</td></tr>';
				while ($row) {
					$pagerecord = dbGetRecordById ($session, T_Page, $row[0],
						'name,type');
					echo "\n<tr><td>";
					guiInternLink ($session, 
						encodeWikiName ($session, $pagerecord[0]), $pagerecord[0]);
					echo '</td><td>';
					echo $pagerecord [1];
					echo  '</td><td>';
					echo $row [2];
					echo '</td><td>';
					echo htmlentities ($row [3]);
					echo '</td><td>';
					echo findTextInLine ($row [1], $search_bodytext, 3);
					echo '</td><tr>' . "\n";
					$row = dbNextRecord ($session);
				}
				echo "\n</table>\n";
			}
		}
	}
}
function baseCallStandardPage (&$session) {
	$session->trace (TC_Gui2, 'baseCallStandardPage');
	$found = true;
	switch ($session->fPageName) {
	case P_Login:	baseLogin ($session, ''); break;
	case P_Logout:	baseLogout ($session); break;
	case P_Account: baseAccount ($session, ''); break;
	case P_Home: 	baseHome ($session); break;
	case P_NewPage:	baseAlterPage ($session, C_New, '', ''); break;
	case P_NewWiki:	baseAlterPage ($session, C_New, '', '', M_Wiki); break;
	case P_ModifyPage: baseAlterPage ($session, C_Change, '', ''); break;
	case '!test': baseTest ($session); break;
	case '!form': baseFormTest ($session); break;
	case P_Search:	baseSearch ($session, ''); break;
	case P_Start: baseCustomStart ($session); break;
	case P_LastChanges: baseLastChanges ($session); break;
	case P_Info: baseInfo ($session); break;
	default:
		$session->trace (TC_Gui2, 'baseCallStandardPage-kein Std');
		$found = false;
		break;
	}
	return $found;
}
function baseCustomStart (&$session) {
	$session->trace (TC_Gui2, 'baseCustomStart');
	if (empty ($session->fUserStartPage))
		$session->fUserStartPage = P_Home;
	$session->setPageName ($session->fUserStartPage);
	if (! baseCallStandardPage ($session))
		if (($page_id = dbPageId ($session, $session->fUserStartPage)) > 0){
			$session->SetLocation (encodeWikiName ($session, $session->fUserStartPage));
			guiShowPageById ($session, $page_id, null);
		} else {
			$session->SetLocation (P_Home);
			guiHome ($session);
		}
}
function basePageInfo (&$session) {
	$pagename = $session->fPageName;
	$pagelink = encodeWikiName ($session, $pagename);
	$headline = 'Info über ' . $pagename;
	guiStandardHeader ($session, $headline, Th_InfoHeader, 0);
	$page = dbGetRecordByClause ($session, T_Page,
		'id,createdat,type,readgroup,writegroup', 'name='
		. dbSqlString ($session, $pagename));
	$pageid = $page [0];
	guiParagraph ($session, 'Erzeugt: ' . dbSqlDateToText ($session, $page [1]), false);
	$count = dbSingleValue ($session, 'select count(id) from '
		. dbTable ($session, T_Text) . ' where page=' . (0 + $pageid));
	if ($count <= 1){
		guiParagraph ($session, 'Die Seite wurde nie geändert', false);
		guiParagraph ($session, guiInternLinkString ($session, 
			encodeWikiName ($session, $pagename), "Aktuelle Version"), false);
	} else {
		guiHeadline ($session, 2, "Versionen ($count)");
		echo '<table border="1"><tr><td><b>Id</b></td>'
			. '<td><b>Autor</b></td><td><b>erzeugt</b></td>'
			. '<td><b><b>Unterschied zum Nachfolger</b></td>'
			. '<td><b>Unterschied zu jetzt</b></td></tr>' . "\n";
		$row = dbFirstRecord ($session,
			'select id,createdby,createdat,changedat,replacedby from '
				. dbTable ($session, T_Text) . ' where page=' . (0 + $pageid)
				. ' order by id desc');
		$act_text_id = $row [0];
		while ($row) {
			$text_id = $row [0];
			$replacedby = $row [4];
			echo '<tr><td>';
			guiInternLink ($session,
				$pagelink . '?action=' . A_ShowText
				. '&page_id=' . $pageid . '&text_id=' . ($text_id+0), $text_id);
			echo '</td><td>';
			guiAuthorLink ($session, $row [1]);
			echo '</td><td>' . dbSqlDateToText ($session, $row [2]);
			echo '</td><td>';
			if ($replacedby > 0) {
				guiInternLink ($session, $pagelink . '?action=' . A_Diff
					. '&text_id=' . $replacedby . '&text_id2=' . $text_id,
					' Unterschied zu ' . $replacedby);
				if ($replacedby != $act_text_id){
					echo '</td><td>';
					guiInternLink ($session, $pagelink . '?action=' . A_Diff
						. '&text_id=' . $text_id . '&text_id2=' . $act_text_id,
						' Unterschied zu jetzt');
				}
			}
			echo '</td></tr>' . "\n";
			$row = dbNextRecord ($session);
		}
		echo '</table>' . "\n";
	}
	guiStandardBodyEnd ($session, Th_InfoBodyEnd);
}
function baseDiff (&$session) {
	global $text_id, $text_id2;
	baseCompare ($session, $session->fPageName, $text_id, $text_id2);
}
function baseCompare (&$session, $pagename, $idnew, $idold){
	$headline = 'Versionsvergleich';
	guiStandardHeader ($session, $headline, Th_StandardHeader, Th_StandardBodyStart);
	$version_new = dbGetRecordById ($session, T_Text, $idnew+0,
		'page,createdat,createdby,text');
	$version_old = dbGetRecordById ($session, T_Text, $idold+0,
		'page,createdat,createdby,text');
	if ($version_new [0] != $version_old [0])
		guiParagraph ($session, 'Texte nicht von einer Seite: '
			. (0 + $version_new [0]) . ' / ' .  (0 + $version_old [0]), false);
	else {
		$page_id = $version_new[0];
		$page_link = encodeWikiName ($session, $pagename);
		guiParagraph ($session, 
			guiInternLinkString ($session, $page_link, $pagename)
			. ': Änderungen von Version '
			. guiInternLinkString ($session, $page_link . '?action=' . A_ShowText
				. '&page_id=' . $page_id . '&text_id=' . ($idold+0), $idold)
			. ' zu Version ' 
			. guiInternLinkString ($session, $page_link . '?action=' . A_ShowText
				. '&page_id=' . $page_id . '&text_id=' . ($idnew+0), $idnew)
			. ' von ' . $version_new [2]
			. ($version_new [2] != $version_old [2] ? ' / ' . $version_old [2] : '')
			. ' (' . dbSqlDateToText ($session, $version_new [1])
			. ' / ' .  dbSqlDateToText ($session, $version_old [1]) . ')', false);
		$engine = new DiffEngine ($session, $version_old [3], $version_new [3]);
		$engine->compare (1, 1);
	}
	guiStandardBodyEnd ($session, Th_StandardBodyEnd);
}
function baseLastChanges (&$session) {
	global $last_days;
	$headline = 'Übersicht über die letzten Änderungen';
	guiStandardHeader ($session, $headline, Th_StandardHeader, Th_StandardBodyStart);
	if (! isset ($last_days) || $last_days < 1)
		$last_days = 7;
	guiStartForm ($session);
	echo '<p>Zeitraum: die letzten ';
	guiTextField ('last_days', $last_days, 3, 4);
	echo ' Tage ';
	guiButton ('last_refresh', 'Aktualisieren');
	echo '</p>' . "\n";
	echo '<table border="0">' . "\n";

	for ($day = 0; $day <= $last_days; $day++) {
		$date = localtime (time () - $day * 86400);
		$time_0 = strftime ('%Y.%m.%d', time () - $day * 86400) ;
		$time_2 = mktime (0, 0, 0, $date [4] + 1, $date [3], $date [5]);
		$time_1 = dbSqlDateTime ($session, $time_2);
		$condition = 't.createdat>=' . $time_1
			. ' and t.createdat<=' 
			. str_replace ('00:00:00', '23:59:59', $time_1);
		$rec = dbFirstRecord ($session,
			'select t.id,p.name,t.createdby,t.createdat,t.replacedby,p.id from '
			. dbTable ($session, T_Text)  . ' t, ' 
			. dbTable ($session, T_Page) . ' p where p.id=t.page and '
			. $condition . ' order by createdat desc');
		if ($rec){
			echo '<tr><td><b>';
			echo $time_0;
			echo '</b></td></tr>' . "\n";
			do {
				$text_id = $rec [0]+0;
				$page_id = $rec [5]+0;
				$page_name = $rec [1];
				$page_link = encodeWikiName ($session, $page_name);
				echo '<tr><td>';
				echo dbSingleValue ($session, 'select min(id) from '
					. dbTable ($session, T_Text) . ' where page=' . $page_id)
					== $text_id ? 'Neu' : 'Änderung';
				echo '</td><td>';
				guiInternLink ($session, $page_link . '?action=' . A_ShowText
					. '&page_id=' . $page_id . '&text_id=' . $text_id, $text_id);
				echo '</td><td>';
				guiInternLink ($session, $page_link, $page_name);
				echo '</td><td>';
				guiAuthorLink ($session, $rec [2]);
				echo '</td><td>';
				echo dbSqlDateToText ($session, $rec [3]);
				$pred_rec = dbSingleValue ($session, 'select max(id) from '
					. dbTable ($session, T_Text) . ' where page=' . $page_id
					. ' and createdat<'
					. dbSqlDateTime ($session, $time_2));
				if ($pred_rec > 0) {
					echo '</td><td>';
					guiInternLink ($session, $page_link . '?action=' . A_Diff
						. '&text_id=' . $text_id . '&text_id2=' . $pred_rec,
						'Unterschied zum Vortag (' . $pred_rec . ')');
				}
				echo '</td></tr>' . "\n";
			} while ( ($rec = dbNextRecord ($session)) != null);
		}
	}
	echo '</table>';
	guiStandardBodyEnd ($session, Th_StandardBodyEnd);
}
function baseInfo (&$session) {
	guiStandardHeader ($session, 'Infobasar-Info', Th_InfoHeader, null);
	guiParagraph ($session, '(C) Hamatoma AT gmx DOT de 2004', 0);
	echo '<table border="0"><tr><td><b>Gegenstand</b></td>';
	echo '<td><b>Version</b></td></tr>' . "\n";
	echo '<tr><td>PHP-Klasse:</td><td>';
	echo PHP_ClassVersion;
	echo '<tr><td>PHP-Basismodul:</td><td>';
	echo PHP_ModuleVersion;
	echo '</td></tr><tr><td>DB-Schema:</td><td>';
	echo htmlentities (dbGetParam ($session, Theme_All, Param_DBScheme));
	echo '<tr><td>DB-Basisinhalt:</td><td>';
	echo htmlentities (dbGetParam ($session, Theme_All, Param_DBBaseContent));
	echo '<tr><td>DB-Erweiterungen:</td><td>';
	echo htmlentities (dbGetParam ($session, Theme_All, Param_DBExtensions));
	echo '</td></tr></table>' . "\n";
	guiStandardBodyEnd ($session, Th_InfoBodyEnd);
}
// --------------------
function diffTest ($session) {
	$x2 = "a\nx\ny\nz\nb";
	$x1 = "a\nb";
	$x2 = "x\ny\nb";
	$x1 = "m\nb";
	$x2 = "m";
	$x1 = "x\ny";
	guiParagraph ($session, str_replace ("\n", "<br>", $x1), false);
	guiParagraph ($session,  str_replace ("\n", "<br>", $x2), false);
	$engine = new DiffEngine ($session, $x1, $x2);
	$engine->compare (1, 1);
}
function baseTest (&$session) {
	global $test_text, $test;
	guiStandardHeader ($session, 'Test', Th_StandardHeader, Th_StandardBodyStart);
	echo WikiToHtml ($session, "[code]\n\ra<b\n\rZeile2\n\r[/code]\n\r");
	guiStandardBodyEnd ($session, Th_StandardBodyEnd);
	# guiTestAll ($session);
}
function baseFormTest (&$session) {
	global $test_text, $test;
	if (! isset ($test_text))
		$test_text = "Noch nix!";
	guiStandardHeader ($session, 'Test', Th_StandardHeader, Th_StandardBodyStart);
	$engine = new DiffEnginge ("a\nx\b", "a\nb");
	$engine->compare (1, 1);
	guiStandardBodyEnd ($session, Th_StandardBodyEnd);
}
function baseTestAll (&$session) {
	guiStandardHeader ($session, 'Test', Th_StandardHeader, Th_StandardBodyStart);
	echo wikiToHtml ($session,
		"!!! 3-erÜberschrift\n"
		. "!! 2-erÜberschrift\n"
		. "! 1-erÜberschrift\n"
		. "Normaler Text\n"
		. "Dies ist ein !WikiWort (WikiWort)\n"
		. "[\"WikiWort\" wikiwort mal anderst]\n"
		. "http://www.heise.de Heise\n"
		. "[http://www.heise.de Heise]\n"
		. "\n"
		. "''kursiv'' '''fett''' ''''kursiv und fett'''' normal __unterstrichen__\n"
		. "'''fett'''\n"
		. "<?plugin BackLinks?>\n"
		. "* Punkt1\n* Punkt2\n## Punkt 21\n## Punkt 22\n"
		. "# Punkt1\n# Punkt2\n** Punkt 21\n** Punkt 22\n\n"
		. "|1|2|3\n|1|2|3\n"
		. "\n");
	guiStandardBodyEnd ($session, Th_StandardBodyEnd);
}
function modStandardLinks (&$session){
	baseStandardLink ($session, P_Home);
	baseStandardLink ($session, P_Search);
	echo ' | ';
	baseStandardLink ($session, P_Account);
	echo ' | ';
	baseStandardLink ($session, P_LastChanges);
}
?>
