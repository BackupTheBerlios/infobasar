<?php
// admin.php: Administration of the InfoBasar
// $Id: admin.php,v 1.3 2004/09/22 07:13:30 hamatoma Exp $
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
define ('PHP_ModuleVersion', '0.6.5 (2004.09.21)');

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

define ('ADMIN', true);
define ('C_ScriptName', 'admin.php');
// Modulnamen:
define ('M_Base', 'index.php');

// Seitennamen: (Admin-Modus)
define ('P_Login', 'login');
define ('P_Home', 'home');
define ('P_Param', 'param');
define ('P_Macro', 'macro');
define ('P_Forum', 'forum');
define ('P_Backup', 'backup');
define ('P_ExportPages', 'exportpages');
define ('P_ImportPages', 'importpages');
define ('P_Options', 'options');
define ('P_PHPInfo', 'info');
define ('P_Rename', 'rename');
// Dateinamen
define ('FN_PageExport', 'exp_pages.wiki');


include "config.php";
include "classes.php";

$session = new Session ($start_time);

// All requests require the database
dbOpen($session);

$rc = dbCheckSession ($session);
if (! empty ($rc)) {
	// p ("Keine Session gefunden: $session_id / $session_user ($rc)");
	if (! empty ($login_user))
		admLoginAnswer ($session);
	else
		admLogin ($session, '');
} else {
	switch ($session->fPageName) {
	case P_Param: admParam ($session, ''); break;
	case P_Macro: admMacro ($session, ''); break;
	case P_Home: admHome($session, ''); break;
	case P_Login: admLogin($session, ''); break;
	case P_Forum: admForum($session, '', C_New); break;
	case P_Backup: admBackup ($session, true, null); break;
	case P_ExportPages: admExportPages ($session, null); break;
	case P_ImportPages: 
		if (isset ($import_import))
			admImportPagesAnswer ($session);
		else 
			admImportPages ($session, null); 
		break;
	case P_Options: admOptions ($session, null); break;
	case P_Rename: admRename ($session, null); break;
	case P_PHPInfo: admInfo ($session); break;
	default:
		if (substr ($session->fPageName, 0, 1) == ".")
			guiNewPageReference ($session);
		if (isset ($param_load))
			admParamAnswerLoad ($session);
		elseif (isset ($param_insert))
			admParamAnswerChange ($session, C_New);
		elseif (isset ($param_change))
			admParamAnswerChange ($session, C_Change);
		elseif (isset ($macro_load))
			admMacroAnswerLoad ($session);
		elseif (isset ($macro_insert))
			admMacroAnswerChange ($session, C_New);
		elseif (isset ($macro_change))
			admMacroAnswerChange ($session, C_Change);
		elseif (isset ($backup_save))
			admBackupAnswer ($session);
		elseif (isset ($forum_change) || isset ($forum_load)
			|| isset ($forum_insert))
			admForumAnswer ($session);
		elseif (isset ($rename_rename) || isset ($rename_info))
			admAnswerRename ($session);
		elseif (isset ($export_export) || isset ($export_preview))
			admExportPagesAnswer ($session);
		elseif (isset ($import_import) || isset ($import_file))
			admImportPagesAnswer ($session);
		else admHome ($session);
	}
}
// --------------------------------------------------------------------
function admStandardLinkString(&$session, $page){
	$session->trace (TC_Gui3, 'admStandardLink');
	$rc = null;
	switch ($page) {
	case P_Home: $header = 'Übersicht'; break;
	case P_Login: $header = 'Ein/Ausloggen'; break;
	case P_Param: $header = 'Parameter ändern'; break;
	case P_Macro: $header = 'Makros ändern'; break;
	case P_Forum: $header = 'Forumsverwaltung'; break;
	case P_ExportPages: $header = 'Seitenexport'; break;
	case P_ImportPages: $header = 'Seitenimport'; break;
	case P_Backup: $header = 'Datensicherung'; break;
	case P_Options: $header = 'Einstellungen'; break;
	case P_Rename: $header = 'Umbenennen'; break;
	case P_PHPInfo: $header = 'PHP-Info'; break;
	default: $header = null; break;
	}
	if ($header)
		$rc = guiInternLinkString ($session, $page, $header);
	return $rc;
}
function admStandardLink(&$session, $page){
	echo admStandardLinkString ($session, $page);
}
function admLogin (&$session, $message) {
	global $login_user, $login_email;
	guiStandardHeader ($session, 'Anmeldung f&uuml;r den InfoBasar', Th_StandardHeader,
		Th_StandardBodyStart);
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
	guiButton ('but_forget', 'Passwort ändern');
	echo '<br/>(Das neue Passwort wird dann zugeschickt)';
	echo "</td></tr>\n</table>\n";
	guiFinishForm ($session, $session);
	guiStandardBodyEnd ($session, Th_LoginBodyEnd);
	return 1;
}
function admLoginAnswer (&$session) {
	$login_again = true;
	$session->trace (TC_Gui1, 'admLoginAnswer');
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
		admLogin ($session, $message);
	} else {
		$rc = dbCheckUser ($session, $login_user, $login_code);
		if (! empty ($rc))
			admLogin ($session, $rc);
		else {
			setLoginCookie ($session, $login_user, $login_code);
			$session->setPageName (P_Home);
			$login_again = false;
		}
	}
	return $login_again;
}	

function admHome (&$session){
	global $session_id, $session_user;
	guiStandardHeader ($session, 'Adminstration-Startseite f&uuml;r ' . $session->fUserName,
		Th_StandardHeader, Th_StandardBodyStart);
	guiParagraph ($session, 'Willkommen ' . $session->fUserName, false);
	guiParagraph ($session, admStandardLinkString ($session, P_Param), false);
	guiParagraph ($session, admStandardLinkString ($session, P_Macro), false);
	guiParagraph ($session, admStandardLinkString ($session, P_Forum), false);
	guiParagraph ($session, admStandardLinkString ($session, P_ExportPages), false);
	guiParagraph ($session, admStandardLinkString ($session, P_ImportPages), false);
	guiParagraph ($session, admStandardLinkString ($session, P_Rename), false);
	guiParagraph ($session, admStandardLinkString ($session, P_Backup), false);
	guiParagraph ($session, admStandardLinkString ($session, P_Options), false);
	guiParagraph ($session, admStandardLinkString ($session, P_Login), false);
	guiParagraph ($session, admStandardLinkString ($session, P_PHPInfo), false);
	guiFinishBody ($session, null);
}
function admParam (&$session, $message){
	global $param_id, $param_theme, $param_pos, $param_name, $param_text,
		$textarea_width, $textarea_height;
	$session->trace(TC_Gui1, 'admParam');
	guiStandardHeader ($session, 'Parameter', Th_StandardHeader, Th_StandardBodyStart);

	if (empty ($param_theme))
		$param_theme = Theme_Standard;

	getUserParam ($session, U_TextAreaWidth, $textarea_width);
	getUserParam ($session, U_TextAreaHeight, $textarea_height);
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	guiStartForm ($session, "param", P_Param);
	echo "<table border=\"0\">\n<tr><td>Id:</td><td>";
	echo $param_id;
	guiHiddenField ("param_id", $param_id);
	echo "</td></tr>\n<tr><td>Theme/Pos:</td><td>";
	guiTextField ("param_theme", $param_theme, 4, 4);
	echo ' / ';
	guiTextField ("param_pos", $param_pos, 4, 4);
	echo ' ';
	guiButton ('param_load', 'Datensatz laden');
	echo "</td></tr>\n<tr><td>Name:</td><td>";
	guiTextField ("param_name", $param_name, 64, 64);
	echo "</td></tr>\n<tr><td>Text:</td><td>";
	guiTextArea ("param_text", $param_text, $textarea_width, $textarea_height);
	echo "</td></tr>\n<tr><td></td><td>";
	echo " "; guiButton ('param_insert', 'Eintragen');
	echo " "; guiButton ('param_change', 'Ändern');
	echo "<br /><br />Eingabefeld: Breite: ";
	guiTextField ("textarea_width", $textarea_width, 3, 3);
	echo " Höhe: ";
	guiTextField ("textarea_height", $textarea_height, 3, 3);
	echo "</td></tr>\n</table>\n";
	guiHeadline ($session, 2, 'Parameter von Theme ' . $param_theme);
	echo '<table border="1"><tr><td><b>Pos</b></td><td><b>Beschreibung</b></td>';
	echo '<td><b>Wert</b></td></tr>' . "\n";
	$row = dbFirstRecord ($session, 'select pos,name,text from '
		. dbTable ($session, T_Param) . ' where theme=' . $param_theme . ' order by pos');
	while ($row) {
		echo "<tr><td>$row[0]</td><td>";
		echo htmlentities ($row [1]);
		echo '</td><td>';
		echo htmlentities ($row [2]);
		echo "</td></tr>\n";
		$row  = dbNextRecord ($session);
	}
	echo '</table>' . "\n";
	guiFinishForm ($session);
	guiFinishBody ($session, null);
}
function admParamAnswerLoad (&$session){
	global $param_id, $param_theme, $param_pos, $param_name, $param_text;

	$session->trace(TC_Gui1, 'admParamAnswerLoad');
	$param_text = textAreaToWiki ($session, $param_text);
	if (! isInt ($param_theme))
		$error = 'Theme nicht gültig: ' . $param_theme;
	elseif (! isInt ($param_pos))
		$error = 'Pos nicht gültig: ' . $param_pos;
	else {
		$record = dbGetRecordByClause ($session, T_Param, 'id,name,text',
			"theme=$param_theme and pos=$param_pos");
		$param_id = $record[0];
		$param_name = $record[1];
		$param_text = $record [2];
		$error = '';
	}
	admParam ($session, $error);
}
function admParamAnswerChange (&$session, $mode){
	global $param_id, $param_theme, $param_pos, $param_name, $param_text;

	$session->trace(TC_Gui1, 'admParamAnswerChange');
	$param_text = textAreaToWiki ($session, $param_text);
	if (! isInt ($param_theme))
		$message = 'Theme nicht gültig: ' . $param_theme;
	elseif (! isInt ($param_pos))
		$message = 'Pos nicht gültig: ' . $param_pos;
	elseif ($mode == C_New
		&& dbSingleValue ($session, 'select count(id) from ' . dbTable ($session, T_Param)
		. " where theme=$param_theme and pos=$param_pos") > 0)
		$message = 'Eintrag nicht möglich, da (Theme,Pos) schon existiert';
	elseif ($mode == C_Change
		&& dbSingleValue ($session, 'select id from ' . dbTable ($session, T_Param)
		. " where theme=$param_theme and pos=$param_pos") <= 0)
		$message = 'Ändern nicht möglich, da (Id, Theme, Pos) nicht korrekt';
	else {
		if ($mode == C_New) {
			dbInsert ($session, T_Param, 'theme,pos,name,text',
				$param_theme . ',' . $param_pos . ','
				. dbSqlString ($session, $param_name) . ','
				. dbSqlString ($session, $param_text));
			$message = 'Parameter wurde eingefügt';
		} elseif ($mode == C_Change){
			dbUpdateRaw ($session, T_Param, $param_id, 'name='
				. dbSqlString ($session, $param_name)
				. ',' . 'text=' . dbSqlString ($session, $param_text));
			$message = 'Parameter wurde geändert';
		} else
			$message = 'Unbekannter Modus: ' . $mode;
	}
	admParam ($session, $message);
}

function admMacro (&$session, $message){
	global $macro_theme, $macro_description, $macro_name, $macro_text,
		$textarea_width, $textarea_height;
	$session->trace(TC_Gui1, 'admMacro');
	guiStandardHeader ($session, 'Makros', Th_StandardHeader, Th_StandardBodyStart);
	if (empty ($macro_theme))
		$macro_theme = Theme_All;
	getUserParam ($session, U_TextAreaWidth, $textarea_width);
	getUserParam ($session, U_TextAreaHeight, $textarea_height);
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	guiStartForm ($session, "macro", P_Macro);
	echo "<table border=\"0\">\n<tr><td>Theme/Name:</td><td>";
	guiTextField ("macro_theme", $macro_theme, 4, 4);
	echo ' / ';
	guiTextField ("macro_name", $macro_name, 32, 64);
	echo ' ';
	guiButton ('macro_load', 'Datensatz laden');
	echo "</td></tr>\n<tr><td>Beschreibung:</td><td>";
	guiTextField ("macro_description", $macro_description, 64, 255);
	echo "</td></tr>\n<tr><td>Wert:</td><td>";
	guiTextArea ("macro_text", $macro_text, $textarea_width, $textarea_height);
	echo "</td></tr>\n<tr><td></td><td>";
	echo " "; guiButton ('macro_insert', 'Eintragen');
	echo " "; guiButton ('macro_change', 'Ändern');
	echo "<br /><br />Eingabefeld: Breite: ";
	guiTextField ("textarea_width", $textarea_width, 3, 3);
	echo " Höhe: ";
	guiTextField ("textarea_height", $textarea_height, 3, 3);
	echo "</td></tr>\n</table>\n";
	guiHeadline ($session, 2, 'Makros von Theme ' . $macro_theme);
	echo '<table border="1"><tr><td><b>Id</b></td><td><b>Theme</b></td><td><b>Name</b></td>';
	echo '<td><b>Beschreibung</b></td><td><b>Wert</b></td></tr>' . "\n";
	$row = dbFirstRecord ($session, 'select id,theme,name,description,value from '
		. dbTable ($session, T_Macro) . ' where theme=' . $macro_theme . ' order by name');
	while ($row) {
		echo "<tr><td>$row[0]</td><td>";
		echo htmlentities ($row [1]);
		echo '</td><td>';
		echo htmlentities ($row [2]);
		echo '</td><td>';
		echo htmlentities ($row [3]);
		echo '</td><td>';
		echo htmlentities ($row [4]);
		echo "</td></tr>\n";
		$row  = dbNextRecord ($session);
	}
	echo '</table>' . "\n";
	guiFinishForm ($session);
	guiFinishBody ($session, null);
}
function admMacroAnswerLoad (&$session){
	global $macro_theme, $macro_name, $macro_text, $macro_description;

	$session->trace(TC_Gui1, 'admMacroAnswerLoad');
	$macro_text = textAreaToWiki ($session, $macro_text);
	if (! isInt ($macro_theme))
		$error = 'Theme nicht gültig: ' . $macro_theme;
	elseif (empty ($macro_name))
		$error = 'Kein Name angegeben: ' . $macro_name;
	else {
		list ($macro_name, $macro_description, $macro_text) = dbGetRecordByClause ($session, 
			T_Macro, 'name,description,value', "theme=$macro_theme and name=" 
			. dbSqlString ($session, $macro_name));
		$error = '';
	}
	admMacro ($session, $error);
}
function admMacroAnswerChange (&$session, $mode){
	global $macro_theme, $macro_name, $macro_text, $macro_description;

	$session->trace(TC_Gui1, 'admMacroAnswerChange');
	$id = null;
	$macro_text = textAreaToWiki ($session, $macro_text);
	if (! isInt ($macro_theme))
		$message = 'Theme nicht gültig: ' . $macro_theme;
	elseif (empty ($macro_name))
		$message = 'kein Name angegeben: ' . $macro_name;
	elseif ($mode == C_New
		&& ($count = dbSingleValue ($session, 'select count(id) from ' . dbTable ($session, T_Macro)
		. " where theme=$macro_theme and name="
		. dbSqlString ($session, $macro_name))) > 0)
		$message = 'Eintrag nicht möglich, da (Theme,Name) schon existiert';
	elseif ($mode == C_Change
		&& ($id = dbSingleValue ($session, 'select id from ' . dbTable ($session, T_Macro)
		. " where theme=$macro_theme and name=$macro_name")) <= 0)
		$message = 'Ändern nicht möglich, da (Theme, Name) nicht existiert';
	else {
		$session->trace(TC_X, 'admMacroAnswerChange-2: ' . $count . " / " . $mode);
		
		if ($mode == C_New) {
			dbInsert ($session, T_Macro, 'theme,name,description,value',
				$macro_theme . ',' 
				. dbSqlString ($session, $macro_name) . ','
				. dbSqlString ($session, $macro_description) . ','
				. dbSqlString ($session, $macro_text));
			$message = "Makro $macro_name wurde eingefügt";
		} elseif ($mode == C_Change){
			dbUpdateRaw ($session, T_Macro, $id, 'value='
				. dbSqlString ($session, $macro_text)
				. ',' . 'description=' . dbSqlString ($session, $macro_description)
				. ',' . 'value=' . dbSqlString ($session, $macro_text));
			$message = "Makro $macro_name wurde geändert";
		} else
			$message = 'Unbekannter Modus: ' . $mode;
	}
	admMacro ($session, $message);
}
function admForum (&$session, $message, $mode){
	global $forum_id, $forum_changeid, $forum_name, $forum_description;
	$session->trace(TC_Gui1, 'admForum');
	guiStandardHeader ($session, 'Forumsverwaltung', Th_StandardHeader, Th_StandardBodyStart);
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	guiStartForm ($session, "forum", P_Forum);
	guiHiddenField ('forum_id', $forum_id);
	guiShowTable ($session, "<h2>Existierende Foren</h2>\n",
		array ('Id', 'Name', 'Beschreibung'),
		'select id,name,description from ' . dbTable ($session, T_Forum),
		true, 'border="1" width="100%"');
	echo "<h2>&Auml;nderungen<h2>\n<table border=\"0\">\n<tr>";
	if ($mode == C_Change){
		echo '<td>Id:</td><td>' . $forum_id . "</td></tr>\n";
	}
	echo "<td>Name:</td><td>";
	guiTextField ("forum_name", $forum_name, 64, 64);
	echo "</td><tr>\n<tr><td>Beschreibung</td><td>";
	guiTextField ("forum_description", $forum_description, 64, 255);
	echo "</td></tr>\n<tr><td></td><td>";
	if (! empty ($forum_id)) {
		guiButton ('forum_change', 'Ändern');
		echo ' | ';
	}
	guiButton ('forum_insert', 'Eintragen');
	echo "</td></tr>\n<td>Id:</td><td>";
	guiTextField ('forum_changeid', $forum_changeid, 4, 4);
	echo  ' ';
	guiButton ('forum_load', 'Datensatz laden');
	echo "</td></tr>\n</table>\n";
	guiFinishForm ($session);
	guiFinishBody ($session, null);
}
function admForumAnswer (&$session){
	global $forum_id, $forum_name, $forum_description, $forum_changeid,
		$forum_load, $forum_insert, $forum_change;
	$message = null;
	$mode = C_New;
	if (isset ($forum_load)) {
		if (empty ($forum_changeid) || ! IsInt ($forum_changeid)){
			$message = '+++ keine Id angegeben';
		} else {
			list ($forum_name, $forum_description, $dummy)
				= dbGetRecordById ($session, T_Forum, $forum_changeid,
					'name,description,readgroup,writegroup,admingroup');
				$forum_id = $forum_changeid;
				$forum_changeid = null;
				$mode = C_Change;
		}
	} elseif (isset ($forum_insert)) {
		if (empty ($forum_name))
			$message = '+++ kein Name angegeben';
		elseif (empty ($forum_description))
			$message = '+++ keine Beschreibung angegeben';
		elseif (dbSingleValue ($session, 'select count(id) from ' . dbTable ($session, T_Forum)
			. ' where name=' . dbSqlString ($session, $forum_name)) > 0)
			$message = '+++ Forum mit diesem Namen existiert schon';
		else {
			$id = dbInsert ($session, T_Forum,
				'name,description,readgroup,writegroup,admingroup',
				dbSqlString ($session, $forum_name)
				. ',' . dbSqlString ($session, $forum_description)
				. ',1,1,1');
			$forum_name = $forum_description = null;
			$message = 'Forum wurde unter der Id ' . $id . ' eingetragen';
			$mode = C_New;
		}
	} elseif (isset ($forum_change)) {
		if (dbSingleValue ($session,
			'select count(id) from ' . dbTable ($session, T_Forum)
			. ' where name=' . dbSqlString ($session, $forum_name)
			. " and id<>$forum_id") > 0)
			$message = '+++ Forum mit diesem Namen existiert schon';
		else {
			dbUpdateRaw ($session, T_Forum, $forum_id,
				'name=' . dbSqlString ($session, $forum_name)
				. ',description=' . dbSqlString ($session, $forum_description));
			$message = "Forum $forum_name wurde geändert";
			$forum_id = $forum_name = $forum_description = $forum_changeid = null;
			$mode = C_New;
		}
	}
	admForum ($session, $message, $mode);
}
function admBuildCondition (&$session, $pattern){
	$session->trace(TC_Gui2, 'admBuildCondition');
	$patterns = explode ('|', $pattern);
	$session->trace(TC_Gui2, 'admBuildCondition');
	if (count ($pattern) == 0)
		$condition = 'name like ' . dbSqlString ($session, $pattern);
	else {
		$condition = '(';
		foreach ($patterns as $ii => $pattern)
			$condition .= ($ii == 0 ? 'name like ' : ' or name like ')
				. dbSqlString ($session, $pattern);
		$condition .= ')';
	}
	return $condition;
}
function admExportPages (&$session, $message) {
	global $export_pattern, $export_preview, $export_exists;
	$session->trace(TC_Gui1, 'admExportPages');

	guiStandardHeader ($session, 'Seitenexport', Th_StandardHeader,
		Th_StandardBodyStart);
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	if (isset ($export_preview) && ! empty ($export_pattern))
		guiShowTable ($session, '<h2>Ausgesuchte Seiten ('
			. htmlentities ($export_pattern) . "):</h2>\n",
			array ('Id', 'Name'),
			'select id,name from ' . dbTable ($session, T_Page)
				.  ' where ' . admBuildCondition ($session, $export_pattern),
			true, 'border="1"');
	if (isset ($export_exists))
		guiParagraph ($session, 'Exportdatei: '
			. guiInternLinkString ($session, $export_exists, null), false);
	guiStartForm ($session, "export", P_ExportPages);
	if (isset ($export_exists))
		guiHiddenField ('export_exists', $export_exists);
	echo '<table border="0">';
	echo '<tr><td>Namensmuster:</td><td>';
	guiTextField ('export_pattern', $export_pattern, 64, 0);
	echo '</td><td>Joker: %: beliebig viele Zeichen _: ein Zeichen |: neues Teilmuster Bsp: Hilfe%|%Test%';
	echo '</td></tr>' . "\n" . '<tr><td>Exportform:</td><td>';
	guiComboBox ('export_type', array ('wiki'), null);
	echo '</td><tr>' . "\n" . '<tr><td></td><td>';
	guiButton ('export_preview', 'Vorschau');
	echo ' | ';
	guiButton ('export_export', 'Exportieren');
	echo "</td></tr>\n</table>\n";
	guiFinishForm ($session);
	guiFinishBody ($session, null);
}
function admExportPagesAnswer (&$session){
	global $export_pattern, $export_exists, $export_type,
		$export_export, $export_preview;
	$session->trace(TC_Gui1, 'admExportPagesAnswer');
	$message = null;
	if (isset ($export_export)) {
		if (empty ($export_pattern) ){
			$message = '+++ kein Suchmuster angegeben';
		} else {
			$page_list = dbIdList ($session, T_Page,
				admBuildCondition ($session, $export_pattern));
			$fn = $session->fullPath ("import", true) . FN_PageExport;
			if (! $file = fopen ($fn, 'w'))
				$message = 'kann Datei nicht öffnen: ' . $fn;
			else {
				$prefix = $session->fDbTablePrefix;
				fputs ($file, '# Export ' . "\n"
					. '# am: ' . strftime ("%Y.%m.%d %H:%M:%S") . "\n"
					. '# von: ' . $session->fUserName . "\n"
					. '# Modus: ' . $export_type . "\n"
					. '# Prefix: ' . $prefix . "\n"
					. '# Muster: ' . $export_pattern . "\n"
					. '# Seiten: ' . implode (', ', $page_list) . "\n"
					. "\n");
				$count = 0;
				foreach ($page_list As $ii => $page_id) {
					$count++;
					$page = dbGetRecordById ($session, T_Page, $page_id,
						'name,type');
					$text_id = dbSingleValue ($session, 'select max(id) from '
						. dbTable ($session, T_Text) . ' where page=' . (0+$page_id));
					$text = dbGetRecordById ($session, T_Text, $text_id,
						'type,createdby,createdat,text');
					if ($export_type == 'wiki') {
						fputs ($file,
							"\n#name=" . $page [0]  
								. "\tlines=" . (1+substr_count ($text [3], "\n")) 
								. "\ttype=" . $text [0] 
								. "\tpage=" . $page_id
								. "\ttext=" . $text_id 
								. "\tby=" . $text [1]
								. "\tat=" . $text [2]
								. "\n");
						fputs ($file, $text [3]);
					}
				}
				fclose ($file);
				$export_exists = $fn;
				$message = 'Datei ' . $fn . ' wurde exportiert: ' . ($count+0) . " Seite(n)";
			}
		}
	}
	admExportPages ($session, $message);
}
function admImportPages (&$session,  $message) {
	global $import_pattern, $import_preview, $import_exists, $last_page, $import_import, $import_replace;
	$session->trace(TC_Gui1, 'admImportPages');
	
	if (false && $message == null && isset ($import_import))
		admImportPagesAnswer ($session);
	else {
		guiStandardHeader ($session, 'Seitenimport', Th_StandardHeader,
			Th_StandardBodyStart);
		if (! empty ($message))
			guiParagraph ($session, $message, false);
	
		guiUploadFile ($session, 'Importdatei:', $last_page, null, null, "Hochladen", "import_upload",
			"import_file", 500000);
		$dir_name = $session->fullPath ("import");
		$dir = opendir ($dir_name);
		guiHeadline ($session, 3, "Importverzeichnis auf dem Server: " . $dir_name);
		guiStartForm ($session, "import", P_ImportPages);
		guiCheckBox ('import_replace', 'Historie löschen', 
			isset ($import_replace) && $import_replace == C_CHECKBOX_TRUE);
		echo '<br/>';
		echo '<table border="1" width="100%"><tr><td><b>Name:</b></td>';
		echo '<td><b>Gr&ouml;&szlig;e</b></td><td><b>Ge&auml;ndert am</b></td><td><b>Aktion</b></td></tr>' . "\n";
		$path = $session->fullPath ("import", true); 
		$no = 0;
		while ($file = readdir ($dir)){
			if ($file != '.' && $file != '..'){
				$name = $path . $file;
				echo '<tr><td>';
				echo htmlentities ($file);
				echo '</td><td>';
				echo is_dir ($name) ? 'Verzeichnis' : filesize ($name);
				echo '</td><td>';
				echo date ("Y.m.d H:i:s", filemtime ($name));
				echo '</td><td>';
				guiHiddenField ('import_file', $name);
				guiButton ('import_import', 'Importieren');
				echo '</td></tr>' . "\n";
			}
		}
		echo '</table>' . "\n";
		closedir ($dir);
	
		guiFinishForm ($session);
		guiFinishBody ($session, null);
	}
}
function admImportPagesAnswer (&$session){
	global $import_upload, $import_file, $import_preview, $import_import, $import_replace;
	
	guiStandardHeader ($session, 'SeitenimportAntwort', Th_StandardHeader,
		Th_StandardBodyStart);
	$session->trace(TC_Gui1, 'admImportPagesAnswer');
	$message = null;
	if (isset ($import_upload)){
		$message = guiUploadFileAnswer ($session,  $session->fullPath ("import"),
			$import_file, "import_upload", "upload_file"); 
		# $destination = PATH_DELIM,
		# $filename = null, $button = 'upload_go', $file = 'upload_file'
	} elseif (isset ($import_import)){
		if (! file_exists ($import_file))
			$message = "Datei nicht gefunden: " . $import_file;
		else {
			$file = fopen ($import_file, "r");
			$count_inserts = 0;
			$count_updates = 0;
			$count_lines = 0;
			while ($line = fgets ($file)){
				if (preg_match ('/^#name=(\S+)\tlines=(\d+)\ttype=(\w+)\t/', $line, $param)){
					$name = $param[1];
					$lines = $param[2];
					$type = $param [3];
					$session->trace(TC_X, 'admImportPagesAnswer-2: ' . $line);
					if ( ($page = dbPageId ($session, $name)) > 0){
						$count_updates++;
						if ($import_replace == C_CHECKBOX_TRUE)
							dbDeleteByClause ($session, T_Text, 'page=' . $page);
					} else {
						$page = dbInsert ($session, T_Page, 'name,type', 
							dbSqlString ($session, $name) . ',' 
							. dbSqlString ($session, $type));
						$count_inserts++;
					}
					$text = "";
					$session->trace(TC_X, 'admImportPagesAnswer-3: ' . $lines);
					$count_lines += $lines;
					for ($ii = 0; $ii < $lines; $ii++)
						$text .= fgets ($file);
					if ($import_replace == C_CHECKBOX_TRUE)
						$old_id = dbSingleValue ($session, 'select max(id) from ' . dbTable ($session, T_Text) 
							. ' where page=' . (0+$page));
					$text_id = dbInsert ($session, T_Text, 'page,type,text', 
						$page . ',' . dbSqlString ($session, $type)
						. ',' . dbSqlString ($session, $text));
					if ($import_replace == C_CHECKBOX_TRUE && $old_id > 0)
						dbUpdate ($session, T_Text, $old_id, 'replacedby=' . $text_id);
				}
			}
			fclose ($file);
			$message = 'Datei ' . $import_file . ' wurde eingelesen. Neu: ' . (0 + $count_inserts)
				. ' Geändert: ' . (0 + $count_updates) . ' Zeilen: ' . (0 + $count_lines);
		}
	} else
		$message = "unbekannte Antwort.";
	
	admImportPages ($session, $message);
}
function admSaveTable (&$session, $table, $ignore_id, &$file){
	$session->trace (TC_Gui1, 'admSaveTable: ' . $table);
	$bytes = 0;
	if (! dbGetTableInfo ($session, $table, $names, $types,
			$is_string, $max_lengths, $ix_primary)) {
		$session->trace (TC_Error, 'admSaveTable: keine Metainfo');
		$bytes = -1;
	}
	$header = 'insert into ' . $table . ' (' 
		. join (',', $ignore_id 
			? array_splice ($names, $ix_primary, 1) : $names)
		. ') values (';
	if ($bytes == 0 && ($row = dbFirstRecord ($session, 
			'select * from ' . $table . ' where 1')))
		do {
			$line = $header;
			$no = 0;
			for ($ix = 0; $ix < count ($row); $ix++)
				if (! $ignore_id || $ix != $ix_primary) {
					if ($no++ > 0)
						$line .= ',';
					if ($row [$ix] == null)
						$line .= 'NULL';
					else
						$line .= $is_string [$ix] 
							? dbSqlString ($session, $row [$ix])
							: (0 + $row [$ix]);
				}
			$line .= ');';
			$bytes += fwrite ($file, $line);
			$bytes += fwrite ($file, "\r\n");
		} while ( ($row = dbNextRecord ($session)));
	return $bytes;
}
function admBackup (&$session, $with_header, $message){
	global $backup_table, $backup_compressed, $backup_save, $backup_file;
	$session->trace(TC_Gui1, 'admBackup');
	if ($with_header)
		guiStandardHeader ($session, 'Datenbank-Backup', Th_StandardHeader, Th_StandardBodyStart);
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	if (empty ($backup_file))
		$backup_file = $session->fDbTablePrefix
			. strftime ("_%Y_%m_%d") . '.sql';
	guiHeadLine ($session, 1, 'Backup');;
	guiStartForm ($session, "backup", P_Backup);
	// guiHiddenField ('forum_id', $forum_id);
	echo "<table border=\"0\">\n";
	echo "<tr><td>Dateiname:</td><td>";
	guiTextField ('backup_file', $backup_file, 64, 64);
	echo "</td></tr>\n<tr><td>Tabelle:</td><td>";
	guiTextField ('backup_table', $backup_table, 64, 64);
	echo " (leer: alle Tabellen)</td></tr>\n<tr><td></td><td>";
	guiCheckBox ('backup_compressed', 'komprimiert', $backup_compressed);
	echo "</td></tr>\n<tr><td>";
	guiButton ('backup_save', 'Sichern');
	echo "</td></tr>";
	echo "\n</table>\n";
	guiFinishForm ($session);
	guiFinishBody ($session, null);
}

function admWriteOneTable (&$session, $table, $file){
	echo '<tr><td>' . $table . '</td><td>';
	fwrite ($file, "\n# Tabelle " . $table . "\n\n");
	$bytes = admSaveTable ($session,$table, false, $file);
	echo (0 + $bytes) . '</td></tr>' . "\n";
	return $bytes;
}
function admBackupAnswer (&$session){
	global $backup_table, $backup_compressed, $backup_save, $backup_file;
	$session->trace(TC_Gui1, 'admBackupAnswer');
	$message = null;
	
	guiHeader ($session, 'Datenbank-Backup');
	if (isset ($backup_save)) {
		if (empty ($backup_table))
			$backup_table = '*';
		guiHeadline ($session, 1, 'Backup der Tabelle ' . $backup_table);
		$filename = $backup_table == '*' ? 'db_infobasar' : 'table_' . $backup_table;
		if (! is_dir ($dir =  $session->fullPath ("backup")))
			mkdir ($dir);
		if (empty ($backup_file))
			$backup_file = $session->fMacroBasarName 
				. strftime ('_%Y_%m_%d.sql');
		$filename = 'backup/' . $backup_file;
		if ($backup_compressed)
			$filename .= '.gz';
		$open_name = $backup_compressed 
			? 'compress.zlib://' . $session->fullPath ($filename)
			:  $session->fullPath ($filename);
		$file = fopen ($open_name, $backup_compressed  ? 'wb9' : 'wb');
		fwrite ($file, '# InfoBasar: SQL Dump / Version: ' . PHP_ClassVersion
			. " \n# gesichert am " 
			. strftime ('%Y.%m.%d %H:%M:%S', time ())
			. "\n");
		echo '<table border="0">';
		if ($backup_table != '*') {
			$bytes = admSaveTable ($session, $backup_table,
				false, $file);
		} else {
			$bytes = admWriteOneTable ($session, dbTable ($session, T_Param),
				$file);
			$bytes += admWriteOneTable ($session, dbTable ($session, T_Macro),
				$file);
			$bytes += admWriteOneTable ($session, dbTable ($session, T_User),
				$file);
			$bytes += admWriteOneTable ($session, dbTable ($session, T_Group),
				$file);
			$bytes += admWriteOneTable ($session, dbTable ($session, T_Forum),
				$file);
			$bytes += admWriteOneTable ($session, dbTable ($session, T_Posting),
				$file);
			$bytes += admWriteOneTable ($session, dbTable ($session, T_Text),
				$file);
			$bytes += admWriteOneTable ($session, dbTable ($session, T_Module),
				$file);
		}
		fclose ($file);
		$size = ! $backup_compressed ? $bytes
			: filesize ( $session->fullPath ($filename));
		echo '<tr><td>Summe:</td><td>' . (0 + $bytes);
		if ($backup_compressed)
			echo ' (' . (0 + $size) . ')';
		echo '</td></tr></table>' . "\n";
		echo '<br/>';
		guiStaticDataLink ($session, '', $filename, 'Datei ' . $filename);
	}
	admBackup ($session, false, $message);
}
function admOptions (&$session, $message){
	global $opt_basarname, $opt_save, $upload_go, $upload_file;
	$session->trace (TC_Gui1, 'admOptions');
	guiStandardHeader ($session, 'Allgemeine Einstellungen', Th_StandardHeader, Th_StandardBodyStart);
	if (isset ($upload_go))
		$message = guiUploadFileAnswer ($session, PATH_DELIM . 'pic' . PATH_DELIM, 
			'logo.png');
	if (! empty ($message))
		guiParagraph ($session, $message, false);

	guiHeadline ($session, 2, 'Texte:');
	if (empty ($opt_basarname))
		$opt_basarname = $session->fMacroBasarName;
	if (empty ($opt_css))
		$opt_css = dbGetText ($session, Th_CSSFile);
	guiStartForm ($session, 'Form', P_Options);
	echo '<table border="0">';
	echo '<tr><td>Basarname:</td><td>';
	guiTextField ('opt_basarname', $opt_basarname, 32, 128);
	echo '</td></tr>' . "\n";
	echo '<tr><td></td><td>';
	guiButton ('opt_save', '&Auml;ndern');
	echo '</td></tr></table>' . "\n";
	guiFinishForm ($session);
	guiHeadline ($session, 2, 'Dateien:');
	guiUploadFile ($session, 'Logo:', P_Options);
	guiFinishBody ($session, null);
}
function admRename (&$session, $message){
	global $rename_oldname, $rename_newname, $rename_backlinks;
	$session->trace (TC_Gui1, 'admRename');
	guiStandardHeader ($session, 'Umbenennen einer Seite', Th_StandardHeader, Th_StandardBodyStart);
	if (! empty ($message))
		guiParagraph ($session, $message, false);

	guiStartForm ($session, 'Form', P_Rename);
	echo '<table border="0">';
	echo '<tr><td>Bisheriger Name:</td><td>';
	guiTextField ('rename_oldname', $rename_oldname, 64, 64);
	echo '<tr><td>Neuer Name:</td><td>';
	guiTextField ('rename_newname', $rename_newname, 64, 64);
	echo '</td></tr>' . "\n";
	echo '<tr><td></td><td>';
	guiButton ('rename_info', 'Info');
	if (! empty ($rename_oldname) && ! empty ($rename_newname))
		echo ' | ';
		guiButton ('rename_rename', 'Umbenennen');
		echo '<br>';
		guiCheckBox ('rename_backlinks', 'Alle Verweise umbenennen', 
			! isset ($rename_backlinks) || $rename_backlinks == C_CHECKBOX_TRUE);
	echo '</td></tr></table>' . "\n";
	guiFinishForm ($session);
	
	if (! empty ($rename_oldname) && dbPageId ($session, $rename_oldname) > 0){
		$row = dbFirstRecord ($session,
				'select page,text,createdby,createdat from '
				. dbTable ($session, T_Text)
				. ' where replacedby is null and text like '
				. dbSqlString ($session, "%$rename_oldname%"));
		if (! $row)
			guiParagraph ($session, '+++ keine Verweise gefunden', false);
		else {
			echo '<table border="1"><tr><td>Seite:</td><td>Typ:</td>'
			. '<td>von</td><td>Letzte &Auml;nderung</td><td>Fundstelle</td></tr>';
			while ($row) {
				$pagerecord = dbGetRecordById ($session, T_Page, $row[0],
					'name,type');
				$text = findTextInLine ($row [1], $rename_oldname, 10, true);
				if (! empty ($text)){
					echo "\n<tr><td>";
					guiInternLink ($session, 
							encodeWikiName ($session, $pagerecord[0]),
							$pagerecord[0], M_Base);
					echo '</td><td>';
					echo $pagerecord [1];
					echo '</td><td>';
					echo $row [2];
					echo '</td><td>';
					echo htmlentities ($row [3]);
					echo '</td><td>';
					echo $text;
					echo "</td><tr>\n";
				}
				$row = dbNextRecord ($session);
			}
			echo "\n</table>\n";
		}
	}
	guiFinishBody ($session, null);
}
function admAnswerRename (&$session){
	global $rename_oldname, $rename_newname, $rename_rename, $rename_backlinks;
	$session->trace (TC_Gui1, 'admAnswerRename');
	
	$message = null;
	$origin = isset ($rename_newname) ? $rename_newname : null;

	if (!isset ($rename_oldname))
		$message = '+++ kein bisheriger Name angegeben!';
	elseif ( ($page_id = dbPageId ($session, $rename_oldname)) <= 0)
		$message = '+++ Seite ' . $rename_oldname . ' existiert nicht';
	elseif (isset ($rename_rename) && !isset ($rename_newname))
		$message = '+++ kein neuer Name angegeben!';
	elseif (isset ($rename_rename) 
		&& ($rename_newname = normalizeWikiName ($session, $rename_newname))
			!= $origin)
		$message = '+++ Unzulässiger neuer Name (' . $origin
				. ') wurde korrigiert';
	elseif (isset ($rename_rename) && dbPageId ($session, $rename_newname) > 0)
		$message = '+++ Seite ' . $rename_newname . ' existiert schon!';
	elseif (isset ($rename_rename)){
		dbUpdate ($session, T_Page, $page_id, 
			'name=' . dbSQLString ($session, $rename_newname) . ',');
		$message = 'Seite ' . $rename_oldname . ' wurde in ' . $rename_newname 
			. ' umbenannt.';
		$pages = 0;
		$hits = 0;
		if ($rename_backlinks == C_CHECKBOX_TRUE){
			$row = dbFirstRecord ($session,
					'select id,text from '
					. dbTable ($session, T_Text)
					. ' where replacedby is null and text like '
					. dbSqlString ($session, "%$rename_oldname%"));
			$pattern1 = '/([^' . CL_WikiName . '])' 
				. $rename_oldname . '([^' . CL_WikiName . '])/';
			$pattern2 = '/^' . $rename_oldname . '([^' . CL_WikiName . '])/';
			$pattern3 = '/([^' . CL_WikiName . '])' .  $rename_oldname . '$/';
			$replacement1 = '\1' . $rename_newname . '\2';
			$replacement2 = $rename_newname . '\1';
			$replacement3 = '\1' . $rename_newname;
			while ($row){
				$text = $row [1];
				$count1 = preg_match_all ($pattern1, $row [1], $dummy);
				if ($count1 > 0)
					$text = preg_replace ($pattern1, $replacement1, $text);
				$count2 = preg_match ($pattern2, $row [1]);
				if ($count2 > 0)
					$text = preg_replace ($pattern2, $replacement2, $text);
				$count3 = preg_match ($pattern3, $text);
				if ($count3 > 0)
					$text = preg_replace ($pattern3, $replacement3, $text);
				
				if ($count1  + $count2 + $count3  > 0){
					dbUpdate ($session, T_Text, $row [0], 
							'text=' . dbSQLString ($session, $text) . ',');
					$pages++;
					$hits += $count1 + $count2 + $count3;
				}
				$row = dbNextRecord ($session);
			}
			if ($pages > 0)
				$message .= '<br>Es wurde' 
					. ($hits == 1 ? ' ' : 'n ')
					. $hits . ($hits == 1 ? ' Verweis auf ' : ' Verweise auf ' )
					. $pages 
					. ($pages == 1 ? ' Seite umbenannt.' 
						: ' Seiten umbenannt.');
		}
		addSystemMessage ($session, $rename_oldname 
					. ' >> ' . $rename_newname . ': ' . (0+$hits));
		$rename_oldname = '';
		$rename_newname = '';
	}
	admRename ($session, $message);
}

function admInfo (&$session) {
	$session->trace (TC_Gui1, 'admInfo');
	guiStandardHeader ($session, 'PHP-Info', Th_StandardHeader, Th_StandardBodyStart);
	phpinfo ();
}
function modStandardLinks (&$session){
	admStandardLink ($session, P_Home);
	echo ' | ';
	admStandardLink ($session, P_Param);
	echo ' | ';
	admStandardLink ($session, P_Forum);
	echo ' | ';
	admStandardLink ($session, P_ExportPages);
	echo ' | ';
	admStandardLink ($session, P_Backup);
	echo ' | ';
	admStandardLink ($session, P_Options);
}

?>
