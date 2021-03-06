<?php
// index.php: Start page of the InfoBasar
// $Id: index.php,v 1.11 2004/09/02 21:25:20 hamatoma Exp $
/*
Diese Datei ist Teil von InfoBasar.
Copyright 2004 hamatoma@gmx.de M�nchen
InfoBasar ist freie Software. Du kannst es weitergeben oder ver�ndern
unter den Bedingungen der GNU General Public Licence.
N�heres siehe Datei LICENCE.
InfoBasar sollte n�tzlich sein, es gibt aber absolut keine Garantie
der Funktionalit�t.
*/
$start_time = microtime ();
define ('PHP_ModuleVersion', '0.6.2 (2004.09.01)');
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

define ('Th_HeaderHTML', 211); // von 111
define ('Th_BodyStartHTML', 212);
define ('Th_BodyEndHTML', 213);
define ('Th_EditHeaderHTML', 214);
define ('Th_EditStartHTML', 215);
define ('Th_EditEndHTML', 216);


define ('Th_LoginHeader', 221); // von 131
define ('Th_LoginBodyEnd', 222);
define ('Th_Overview', 223);
define ('Th_InfoHeader', 224);
define ('Th_InfoBodyEnd', 225);

define ('Th_SearchHeader', 231); // von 151
define ('Th_SearchBodyStart', 232);
define ('Th_SearchBodyEnd', 233);

define ('Th_HeaderWiki', 241); // von 161
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
		baseLoginAnswer ($session);
	else 
		$do_login = true;
} else {
		if (isset ($login_user))
			baseLoginAnswer ($session);
		else
			$do_login = $session->fPageName == P_Login;
}
if ($do_login){
		clearLoginCookie ($session);
		baseLogin ($session, '');
} else {
		$session->trace (TC_Init, 'index.php: std_answer: ' . (empty ($std_answer) ? '' : "($std_answer)"));
	if (isset ($action)) {
		$session->trace (TC_Init, "index.php: action: $action");
		switch ($action){
		case A_Edit: baseEditPage ($session, null); break;
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
			. (isset ($edit_save) ? " ($edit_save)" : ' []'));
		if (isset ($test))
			baseTest ($session);
		else if (substr ($session->fPageName, 0, 1) == '.')
			baseNewPageReference ($session);
		else if (isset ($last_refresh))
			baseLastChanges ($session);
		else if (isset ($alterpage_insert))
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
		elseif (isset ($edit_preview))
			baseEditPage ($session, null);
		elseif (isset ($edit_save) || isset ($edit_previewandsave))
			baseEditPageAnswerSave ($session);
		elseif (isset ($edit_cancel))
			guiShowPageById ($session, $edit_pageid, null);
		elseif (isset ($posting_preview) || isset ($posting_insert)
			|| isset ($posting_change))
			basePostingAnswer ($session);
		elseif ( ($page_id = dbPageId ($session, $session->fPageName)) > 0)
			guiShowPageById ($session, $page_id, null);
		else {
			$session->trace (TC_Warning, PREFIX_Warning . 'index.php: Nichts gefunden');
			$session->SetLocation (P_Home);
			baseHome ($session);
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
	case P_Home: $header = '�bersicht'; break;
	case P_Account: $header = 'Einstellungen'; break;
	case P_Search: $header = 'Wikisuche'; break;
	case P_LastChanges: $header = 'Letzte �nderungen'; break;
	case P_Start: $header = 'Pers�nliche Startseite'; break;
	case P_Login: $header = 'Neu anmelden'; break;
	case P_Info: $header = 'Information'; break;
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
function baseEditPage (&$session, $message) {
	global $edit_preview, $edit_pageid, $edit_textid, $edit_content,
		$edit_changedat, $edit_changedby, $edit_texttype, $last_pagename,
		$edit_textidpred;

	$session->trace (TC_Gui1, 'baseEditPage');
	if (! isset ($last_pagename)) {
		$last_pagename = $session->fPageName;
	}
	if (isset ($edit_content))
		$edit_content = textAreaToWiki ($session, $edit_content);

	if (! isset ($edit_pageid)) {
		list ($edit_pageid, $edit_texttype) = dbGetRecordByClause ($session, T_Page,
			'id,type', 'name=' . dbSqlString ($session, $session->fPageName));
		$edit_textidpred = dbGetValueByClause ($session, T_Text,
			'max(id)', 'page=' . (0+$edit_pageid));
		list ($edit_content, $edit_changedat, $edit_changedby)
			= dbGetRecordById ($session, T_Text, $edit_textidpred,
				'text,createdat,createdby');
		$edit_textid = null;
		
	}
	$session->setPageData ($last_pagename, $edit_changedat,
		$edit_changedby);
	getUserParam ($session, U_TextAreaWidth, $textarea_width);
	getUserParam ($session, U_TextAreaHeight, $textarea_height);
	if ($edit_texttype == TT_Wiki)
		guiStandardHeader ($session, $session->fPageName . ' (in Bearbeitung)',
			Th_EditHeaderWiki, Th_EditStartWiki);
	else
		guiStandardHeader ($session, $session->fPageName . ' (in Bearbeitung)',
			Th_EditHeaderHTML, Th_EditStartHTML);
	if (isset ($edit_preview)) {
		echo guiParam ($session, Th_PreviewStart, '<h1>Vorschau von '
			. $session->fPageName
			. '</h1><p>Warnung: Der Text ist noch nicht gesichert!</p>');
		guiFormatPage ($session, $edit_texttype, $edit_content);
		echo guiParam ($session, Th_PreviewEnd, '<h1>Ende der Vorschau</h1>');
	}
	guiStartForm ($session, 'edit');
	guiHiddenField ('edit_texttype', $edit_texttype);
	guiHiddenField ('last_pagename', $last_pagename);
	guiHiddenField ('edit_pageid', $edit_pageid);
	guiHiddenField ('edit_textid', $edit_textid);
	guiHiddenField ('edit_textidpred', $edit_textidpred);
	guiHiddenField ('edit_changedat', $edit_changedat);
	guiHiddenField ('edit_changedby', $edit_changedby);
	echo "<table border=\"0\">\n";
	if (! empty ($message))
		echo '<td><strong>' . htmlentities ($message) . '</strong></tr>' . "\n";
	echo "</td></tr>\n<tr><td>";
	guiTextArea ("edit_content", $edit_content, $textarea_width,
		$textarea_height);
	echo '</td></tr>' . "\n" . '<tr><td><table border="0" width="100%"><tr><td>';
	guiButton ('edit_previewandsave', 'Zwischenspeichern');
	echo ' | '; guiButton ('edit_save', 'Speichern (fertig)');
	echo ' | '; guiButton ('edit_cancel', 'Verwerfen');
	echo ' | '; guiButton ('edit_preview', 'Vorschau');
	echo '</td><td style="text-align: right;">Breite: ';
	guiTextField ("textarea_width", $textarea_width, 3, 3);
	echo " H&ouml;he: ";
	guiTextField ("textarea_height", $textarea_height, 3, 3);
	echo "</td></tr>\n</table>\n</td></tr></table>\n";
	guiFinishForm ($session, $session);
	guiStandardBodyEnd ($session,
		$edit_texttype == TT_Wiki ? Th_EditEndWiki : Th_EditEndHTML);
}
function baseEditPageAnswerSave (&$session)
{
	global $edit_pageid, $edit_textid, $edit_textidpred, $edit_content,
		$edit_changedat, $edit_changedby, $edit_texttype,
		$edit_previewandsave, $last_pagename;

	$session->trace (TC_Gui1, 'baseEditPageAnswerSave');
	$edit_content = textAreaToWiki ($session, $edit_content);
	$new_textid = dbGetValueByClause ($session, T_Text,
		'max(id)', 'page=' . $edit_pageid);
	$message = '';
	if ($new_textid > $edit_textidpred 
		&& (! isset ($edit_textid) || $new_textid > $edit_textid))
		$message = "+++ Warnung: Seite wurde inzwischen ge�ndert! "
			. "Bitte Differenz ermitteln und erneut eintragen! "
			. $new_textid . " /  " . $edit_textidpred;
	$date = dbSqlDateTime ($session, time ());
	if (empty ($edit_textid)){
		$edit_textid = dbInsert ($session, T_Text,
			'page,type,createdat,changedat,createdby,text',
			$edit_pageid . ',' . dbSqlString ($session, $edit_texttype)
				. ",$date,$date," . dbSqlString ($session, $session->fUserName)
				. ',' . dbSqlString ($session, $edit_content));
		dbUpdate ($session, T_Text, $new_textid, 'replacedby=' . $edit_textid . ',');
	} else {
		dbUpdate ($session, T_Text, $edit_textid, "text=" . dbSqlString ($session, $edit_content) . ",");
	}
	unset ($edit_save);
	if (empty ($message) && ! isset ($edit_previewandsave)){
		guiShowPageById ($session, $edit_pageid, null);
	} else {
		# $session->SetLocation ($last_pagename);
		baseEditPage ($session, $message, $message);
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
	echo "<table border=\"0\">\n<tr><td>Benutzername:</td><td>";
	guiTextField ("login_user", $login_user, 32, 32);
	echo "</td></tr>\n<tr><td>Passwort:</td><td>";
	guiPasswordField ("login_code", "", 32, 32);
	echo "</td></tr>\n<tr><td></td><td>";
	guiButton ("but_login", "Anmelden");
	echo "</td></tr>\n</table>\n";
	guiLine ($session, 2);
	guiParagraph ($session, "Passwort vergessen? ", false);
	echo "<table border=\"0\">\n<tr><td>EMail-Adresse:</td><td>";
	guiTextField ('login_email', $login_email, 32, 0);
	echo "</td></tr>\n<tr><td></td><td>";
	guiButton ('but_forget', 'Passwort �ndern');
	echo '<br/>(Das neue Passwort wird dann zugeschickt)';
	echo "</td></tr>\n</table>\n";
	guiFinishForm ($session, $session);
	guiStandardBodyEnd ($session, Th_LoginBodyEnd);
	return 1;
}
function baseLoginAnswer (&$session) {
	$login_again = true;
	$session->trace (TC_Gui1, 'baseLoginAnswer');
	global $login_user, $login_code, $session_user, $but_forget, $login_email;
	if (isset ($but_forget)) {
		$message = null;
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
		guiLogin ($session, $message);
	} else {
		$rc = dbCheckUser ($session, $login_user, $login_code);
		if (! empty ($rc))
			guiLogin ($session, $rc);
		else {
			setLoginCookie ($session, $login_user, $login_code);
			$session->setPageName (P_Start);
			$login_again = false;
		}
	}
	return $login_again;
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
		list ($id, $account_rights, $account_locked, $account_width,
			$account_height, $account_maxhits,
			$account_theme, $account_startpage, $account_email)
			= dbGetRecordByClause ($session, T_User,
			'id,rights,locked,width,height,maxhits,theme,startpage,email',
			'name=' . dbSqlString ($session, $account_user));
			baseSplitRights ($session, $account_right_user, $account_right_rights, $account_rights_posting,
				$account_rights_pages);
	}
	guiStandardHeader ($session, 'Einstellungen f&uuml;r ' . $account_user,
		Th_StandardHeader, Th_StandardBodyStart);
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	guiStartForm ($session, 'account', P_Account);
	echo "<table border=\"0\">\n<tr><td>Benutzername:</td><td>";
	guiHiddenField ('account_user', $account_user);
	guiHeadline ($session, 2, $account_user);
	echo '</td></tr>' . "\n" . '<tr><td>Passwort:</td><td>';
	guiPasswordField ('account_code', '', 64, 32);
	echo '</td></tr>' . "\n" . '<tr><td>Wiederholung:</td><td>';
	guiPasswordField ('account_code2', '', 64, 32);
	echo '</td></tr>' . "\n" . '<tr><td>EMail:</td><td>';
	guiTextField ('account_email', $account_email, 64, 64);
	echo "</td></tr>\n<tr><td>Gesperrt:</td><td>";
	guiCheckBox ("account_locked", "Gesperrt", $account_locked == C_CHECKBOX_TRUE);
	echo "</td></tr>\n<tr><td>Design:</td><td>";
	dbGetThemes ($session, $theme_names, $theme_numbers);
	guiComboBox ('account_theme', $theme_names, $theme_numbers, 
		array_search ($account_theme, $theme_numbers));
	echo "</td></tr>\n<tr><td>Eingabefeldbreite:</td><td>";
	guiTextField ("account_width", $account_width, 64, 3);
	echo "</td></tr>\n<tr><td>Eingabefeldh&ouml;he:</td><td>";
	guiTextField ("account_height", $account_height, 64, 3);
	echo "</td></tr>\n<tr><td>Zahl Suchergebnisse:</td><td>";
	guiTextField ("account_maxhits", $account_maxhits, 64, 3);
	echo "</td></tr><tr><td>\nStartseite:</td><td>";
	$names = array ('WikiSeite:', '�bersicht', 'Einstellungen',
			'Wikisuche', 'Letze �nderungen', 'StartSeite', 'Hilfe');
	$values = array ('', P_Home, P_Account, 
			P_Search, P_LastChanges, 'StartSeite', 'Hilfe');
	if ( ($pos = strpos ($account_startpage, '!')) == 0 && is_int ($pos))
		$ix = array_search ($account_startpage, $values);
	else
		$ix = 0;
	guiComboBox ('account_startpageoffer', $names, $values, $ix);
	echo ' ';
	guiTextField ("account_startpage", $account_startpage, 32, 128);
	echo "</td></tr>\n";
	modUserTableData ($session, $id);
	echo "</table>\n";
	modUserOwnData ($session, $id);
	echo "<br>\n";
	guiButton ("account_change", "&Auml;ndern");
	echo "<br>\n<br>\n";
	
	$change = $session->hasRight (R_User, R_Put);
	$new = $session->hasRight (R_User, R_New);
	$new = $session->fUserId <= 2 || $session->fUserName == 'wk' || $session->fUserName == 'admin';
	$change = $new;
	if ($change || $new){
		guiLine ($session, 2);
		echo "<table border=\"0\"></td></tr><tr></tr>\n<tr><td>Name:</td><td>";
		guiTextField ("account_user2", $account_user2, 32, 32);
		echo "</td></tr>\n<tr><td></td><td>";
		if ($change)
			guiButton ("account_other", "Benutzer wechseln");
		if ($new){
			echo " "; guiButton ("account_new", "Neu");
		}
	}
	echo "</td></tr>\n</table>\n";
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
			$uid = dbUserAdd ($session, $account_user2, $code, $session->fUserRights,
				dbSqlString ($session, false), $account_theme, $account_width, $account_height,
				$account_maxhits, $account_startpage, $account_email);
			modUserStoreData ($session, true, $uid);
			
			$message = "Benutzer $account_user2 wurde angelegt. ID: " . $uid;
		}
	} elseif (isset ($account_change)) {
		if (! empty ($account_code) && $account_code <> $account_code2)
			$message = '+++ Passwort stimmt mit Wiederholung nicht �berein';
		elseif (! ($uid = dbUserId ($session, $account_user)) || empty ($uid))
			$message = '+++ unbekannter Benutzer: ' . $account_name;
		elseif ( ($message = modUserCheckData ($session, true, $uid)) != null)
			;
		else {
			if (empty ($account_theme))
				$account_theme = Theme_Standard;
			$what = 'rights=' . dbSqlString ($session, $account_rights) . ',locked='
				. $locked . ',';
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
			$message = 'Daten f�r ' . $account_user . ' (' . $uid
				. ') wurden ge�ndert';
		}
	} elseif ($account_other) {
		if (empty ($account_user2))
			$message = '+++ kein Benutzername angegeben';
		elseif (! dbUserId ($session, $account_user2))
			$message = '+++ Unbekannter Benutzer: ' . $account_user2;
	} else {
		$message = 'keine �nderung';
	}
	baseAccount ($session, $message);
}
function baseHome (&$session) {
	global $session_id, $session_user;

	$session->trace (TC_Gui1, 'baseHome');
	if ( ($text = guiParam ($session, Th_Overview, null)) != null
		&& ! empty ($text))
		echo $text;
	else {
		guiStandardHeader ($session, '�bersicht', Th_StandardHeader,
			Th_StandardBodyStart);
		guiHeadline ($session, 1, 'Willkommen ' . $session->fUserName);
		echo '<table border="0"><tr><td>';
		baseStandardLink ($session, P_Account);
		echo '</td><td>Einstellungen</td></tr>' . "\n" . '<tr><td>';
		baseStandardLink ($session, P_Login);
		echo '</td><td>Abmelden (oder neu anmelden)</td></tr>'  . "\n" . '<tr><td>';
		# echo '<p>';	baseStandardLink ($session, P_NewPage); echo '</p>';
		# echo '<p>';	baseStandardLink ($session, P_ModifyPage); echo '</p>';
		baseStandardLink ($session, P_Search);
		echo '</td></tr>'  . "\n" . '<tr><td>';
		baseStandardLink ($session, P_Start);
		echo '</td><td>Pers&ouml;nliche Startseite</td></tr>'  . "\n" . '<tr><td>';
		guiInternLink ($session, 'StartSeite', 'StartSeite');
		echo '</td><td>Wiki-Startseite</td></tr>'  . "\n" . '<tr><td>';
		baseStandardLink ($session, P_NewWiki);
		echo '</td><td>Neue Wikiseite</td></tr>'  . "\n" . '<tr><td>';
		baseStandardLink ($session, P_LastChanges);
		echo '</td><td>Neueste &Auml;nderungen</td></tr>'  . "\n" . '<tr><td>';
		baseStandardLink ($session, P_Info);
		echo '</td><td>Information &uuml;ber den InfoBasar</td></tr>';
		modOverview ($session);
		echo '</table>'  . "\n" ;
		// echo 'Session-Id: ' . $session_id . ' User: ' . $session_user . '<br>';
		guiStandardBodyEnd ($session, Th_StandardBodyEnd);
	}
}
function baseAlterPage (&$session, $mode, $message, $message2, $type = M_Undef){
	global $alterpage_name, $alterpage_content, $textarea_width,
		$textarea_height, $alterpage_content, $alterpage_mime,
		$alterpage_lastmode, $alterpage_preview;
	$session->trace (TC_Gui1, 'baseAlterPage');
	if ($type != M_Undef)
		$alterpage_mime = $type;
	if ($alterpage_mime == M_Wiki)
		guiStandardHeader ($session, $mode != C_Change
			? 'Neue Seite eintragen' : 'Seite �ndern', Th_EditHeaderWiki,
			Th_EditStartWiki);
	else
		guiStandardHeader ($session, $mode != C_Change
			? 'Neue Seite eintragen' : 'Seite �ndern', Th_EditHeaderHTML,
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
		guiLine (1);
	}

	guiStartForm ($session, 'alterpage');
	guiHiddenField ('alterpage_lastmode', $mode);
	echo "<table border=\"0\">\n<tr><td>Name:</td><td>";
	guiTextField ('alterpage_name', $alterpage_name, 64, 64);
	if ($mode == C_Change)
		guiButton ('alterpage_changepage', 'Seite laden');
	if ($type == M_Wiki)
		;
	echo "</td></tr>\n<tr><td>Typ:</td><td>";
	if ($mode == C_New && $type == M_Undef)
		guiComboBox ('alterpage_mime', array (M_Wiki, M_HTML), null);
	else {
		echo $alterpage_mime;
		guiHiddenField ($session, 'alterpage_mime', $alterpage_mime);
	}
	echo "</td></tr>\n<tr><td>Inhalt:</td><td>";
	guiTextArea ("alterpage_content", $alterpage_content,
		$textarea_width, $textarea_height);

	echo "</td></tr>\n<tr><td></td><td>";
	if ($mode != C_Change)
		guiButton ('alterpage_insert', 'Eintragen');
	else
		guiButton ('alterpage_changecontent', '�ndern');
	echo " "; guiButton ('alterpage_preview', 'Vorschau');
	echo " "; guiButton ('alterpage_cancel', 'Abbrechen');
	echo "<br /><br />Eingabefeld: Breite: ";
	guiTextField ("alterpage_width", $textarea_width, 3, 3);
	echo " H�he: ";
	guiTextField ("alterpage_height", $textarea_height, 3, 3);
	echo "</td></tr>\n</table>\n";
	guiFinishForm ($session, $session);
	guiStandardBodyEnd ($session, Th_StandardBodyEnd);
}
function baseNewPageReference (&$session) {
	global $alterpage_name, $alterpage_content, $textarea_width, $alterpage_mime,
		 $textarea_height, $alterpage_content, $alterpage_insert;
	$session->trace (TC_Gui1, 'baseNewPageReference');
	$alterpage_name = substr ($session->fPageName, 1);
	if ( ($page = dbPageId ($session, $alterpage_name)) > 0)
		guiShowPageById ($session, $page, null);
	else {
		$alterpage_content = strpos ($alterpage_name, 'ategorie') == 1
			? "<?plugin BackLinks?>\n----\nKategorieKategorie" : "Beschreibung der Seite $alterpage_name\n";
		$alterpage_mime = M_Wiki;
		baseAlterPage ($session, C_Auto, 'neue Seite', '');
	}
}

function baseAlterPageAnswer (&$session, $mode){
	global $alterpage_name, $alterpage_content, $textarea_width,
		 $textarea_height, $alterpage_content, $alterpage_insert,
		 $alterpage_preview, $alterpage_lastmode, $alterpage_mime;
	$session->trace (TC_Gui1, 'baseAlterPageAnswer');
	$alterpage_name = makePageName ($alterpage_name);
	if ($mode == C_LastMode)
		$mode = $alterpage_lastmode;
	$len = strlen ($alterpage_content);
	$message = null;
	$alterpage_content = textAreaToWiki ($session, $alterpage_content);
	$alterpage_content = extractHtmlBody ($alterpage_content);
	if (isset ($alterpage_preview))
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
	$session->SetLocation ($alterpage_name);
	if ($message != null || isset ($alterpage_preview))
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
	$alterpage_name = makePageName ($alterpage_name);

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
		$message = 'Seite ' . $alterpage_name . ' wurde ge�ndert';
	}
	if ($len != strlen ($alterpage_content))
		$message2 = 'Es wurde der Rumpf (body) extrahiert.';
	if ($message != null)
		baseAlterPage ($session, C_Change, $message, $message2);
	else{
		$session->SetLocation ($alterpage_name);
		guiShowPage ($session, $alterpage_name);
	}
}
function baseSearch (&$session, $message){
	global $search_titletext, $search_maxhits, $search_bodytext, $last_pagename,
		$search_title, $search_body;
	$session->trace (TC_Gui1, 'baseSearch');
	if (! isset ($last_pagename))
		$last_pagename = $session->fPageName;
	getUserParam ($session, U_MaxHits, $search_maxhits);
	guiStandardHeader ($session, 'Suchen auf den Wiki-Seiten',
		Th_SearchHeader, Th_SearchBodyStart);
	if (isset ($search_title) || isset ($search_body))
		baseSearchResults ($session);
	guiParagraph ($session, 'Hinweis: vorl&auml;ufig nur ein Suchbegriff m&ouml;glich', false);
	guiStartForm ($session, 'search', P_Search);
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
			echo '<table border="1"><tr><td>Seite:</td><td>Typ:</td></tr>';
			while ($row) {
				echo "\n<tr><td>"
					. guiInternLinkString ($session, $row[0], $row[0])
					. "</td><td>$row[1]</td></tr>";
				$row = dbNextRecord ($session);
			}
			echo "\n</table>\n";
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
					echo "\n<tr><td>"
						. guiInternLinkString ($session, $pagerecord[0],
							$pagerecord[0]) . '</td><td>'
						. $pagerecord [1] . '</td><td>'
						. $row [2] . '</td><td>'
						. htmlentities ($row [3]) . '</td><td>'
						. findTextInLine ($row [1], $search_bodytext, 3)
						. '</td><tr>' . "\n";
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
			$session->SetLocation ($session->fUserStartPage);
			guiShowPageById ($session, $page_id, null);
		} else {
			$session->SetLocation (P_Home);
			guiHome ($session);
		}
}
function basePageInfo (&$session) {
	$pagename = $session->fPageName;
	$headline = 'Info �ber ' . $pagename;
	guiStandardHeader ($session, $headline, Th_InfoHeader, 0);
	$page = dbGetRecordByClause ($session, T_Page,
		'id,createdat,type,readgroup,writegroup', 'name='
		. dbSqlString ($session, $pagename));
	$pageid = $page [0];
	guiParagraph ($session, 'Erzeugt: ' . dbSqlDateToText ($session, $page [1]), false);
	$count = dbSingleValue ($session, 'select count(id) from '
		. dbTable ($session, T_Text) . ' where page=' . (0 + $pageid));
	if ($count <= 1)
		guiParagraph ($session, 'Die Seite wurde nie ge�ndert', false);
	else {
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
				$pagename . '?action=' . A_ShowText
				. '&page_id=' . $pageid . '&text_id=' . ($text_id+0), $text_id);
			echo '</td><td>';
			guiAuthorLink ($session, $row [1]);
			echo '</td><td>' . dbSqlDateToText ($session, $row [2]);
			echo '</td><td>';
			if ($replacedby > 0) {
				guiInternLink ($session, $pagename . '?action=' . A_Diff
					. '&text_id=' . $replacedby . '&text_id2=' . $text_id,
					' Unterschied zu ' . $replacedby);
				if ($replacedby != $act_text_id){
					echo '</td><td>';
					guiInternLink ($session, $pagename . '?action=' . A_Diff
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
		guiParagraph ($session, 
			guiInternLinkString ($session, $pagename, $pagename)
			. ': �nderungen von Version '
			. guiInternLinkString ($session, $pagename . '?action=' . A_ShowText
				. '&page_id=' . $page_id . '&text_id=' . ($idold+0), $idold)
			. ' zu Version ' 
			. guiInternLinkString ($session, $pagename . '?action=' . A_ShowText
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
	$headline = '�bersicht �ber die letzten �nderungen';
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
				echo '<tr><td>';
				echo dbSingleValue ($session, 'select min(id) from '
					. dbTable ($session, T_Text) . ' where page=' . $rec[5])
					== $rec [0] ? 'Neu' : '�nderung';
				echo '</td><td>';
				echo $rec [0];
				echo '</td><td>';
				echo guiInternLink ($session, $rec [1], $rec [1]);
				echo '</td><td>';
				guiAuthorLink ($session, $rec [2]);
				echo '</td><td>';
				echo dbSqlDateToText ($session, $rec [3]);
				$pred_rec = dbSingleValue ($session, 'select max(id) from '
					. dbTable ($session, T_Text) . ' where page=' . $rec[5]
					. ' and createdat<'
					. dbSqlDateTime ($session, $time_2));
				if ($pred_rec > 0) {
					echo '</td><td>';
					guiInternLink ($session, $rec [5] . '?action=' . A_Diff
						. '&text_id=' . $rec[0] . '&text_id2=' . $pred_rec,
						'Unterschied zum Vortag (' . $pred_rec . ')');
				}
				echo '</td></tr>' . "\n";
			} while ( ($rec = dbNextRecord ($session)) != null);
		}

/*		if (false){
		$ids = dbIdList2 ($session, T_Text, 'distinct page', $condition);
		if ($ids) {
			echo '<tr><td><b>';
			echo $time_0;
			echo'</b></td></tr>' . "\n";
			foreach ($ids as $ii => $pageid) {
				$page = dbGetRecordById ($session, T_Page, $pageid, 'name');
				$text = dbFirstRecord ($session,
					'select id,createdby,createdat,replacedby from '
				. dbTable ($session, T_Text) . ' where page=' . (0 + $pageid)
				. ' and ' . $condition . ' order by id desc');
				$count = 0;
				while ($text) {
					$pred_text = dbSingleValue ($session, 'select max(id) from '
						. dbTable ($session, T_Text) . ' where page=' . $pageid
						. ' and createdat<'
						. dbSqlDateTime ($session, $time_2));
					echo '<tr><td>';
					echo dbSingleValue ($session, 'select min(id) from '
						. dbTable ($session, T_Text) . ' where page=' . $pageid)
						== $text [0] ? 'Neu' : '�nderung';
					echo '</td><td>';
					echo $text [0];
					echo '</td><td>';
					echo $count++ > 0 ? htmlentities ($page [0])
						: guiInternLink ($session, $page [0], $page [0]);
					echo '</td><td>';
					guiAuthorLink ($session, $text [1]);
					echo '</td><td>';
					echo dbSqlDateToText ($session, $text [2]);
					if ($pred_text > 0) {
						echo '</td><td>';
						guiInternLink ($session, $page [0] . '?action=' . A_Diff
							. '&text_id=' . $pred_text . '&text_id2=' . $text [0],
							'Unterschied zum Vortag (' . $pred_text . ')');
					}
					echo '</td></tr>' . "\n";
					$text = dbNextRecord ($session);
				}
			}
		}
		} */
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
		"!!! 3-er�berschrift\n"
		. "!! 2-er�berschrift\n"
		. "! 1-er�berschrift\n"
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
