<?php
// admin.php: Administration of the InfoBasar
// $Id: admin.php,v 1.9 2005/01/05 05:24:34 hamatoma Exp $
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
define ('P_ConvertWiki', 'convertwiki');
define ('P_Options', 'options');
define ('P_PHPInfo', 'info');
define ('P_Rename', 'rename');
define ('P_ShowUsers', 'showusers');
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
	case P_ConvertWiki: 
		if (isset ($_POST ['conversion_run']))
			admConvertWikiAnswer ($session);
		else
			admConvertWiki ($session, null); 
		break;
	case P_ImportPages: 
		if (isset ($import_import))
			admImportPagesAnswer ($session);
		else 
			admImportPages ($session, null); 
		break;
	case P_Options: admOptions ($session, null); break;
	case P_Rename: admRename ($session, null); break;
	case P_ShowUsers: admShowUsers ($session, null); break;
	case P_PHPInfo: admInfo ($session); break;
	default:
		if (substr ($session->fPageName, 0, 1) == ".")
			guiNewPageReference ($session);
		if (isset ($_POST ['param_load']))
			admParamAnswerLoad ($session);
		elseif (isset ($_POST ['param_insert']))
			admParamAnswerChange ($session, C_New);
		elseif (isset ($_POST ['param_change']))
			admParamAnswerChange ($session, C_Change);
		elseif (isset ($_POST ['macro_load']))
			admMacroAnswerLoad ($session);
		elseif (isset ($_POST ['macro_insert']))
			admMacroAnswerChange ($session, C_New);
		elseif (isset ($_POST ['macro_change']))
			admMacroAnswerChange ($session, C_Change);
		elseif (isset ($_POST ['backup_save']))
			admBackupAnswer ($session);
		elseif (isset ($_POST ['forum_change']) || isset ($_POST ['forum_load'])
			|| isset ($_POST ['forum_insert']))
			admForumAnswer ($session);
		elseif (isset ($_POST ['rename_rename']) || isset ($_POST ['rename_info']))
			admAnswerRename ($session);
		elseif (isset ($_POST ['conversion_run']))
			admConvertWikiAnswer ($session);
		elseif (isset ($_POST ['export_export']) || isset ($_POST ['export_preview']))
			admExportPagesAnswer ($session);
		elseif (isset ($_POST ['import_import']) || isset ($_POST ['import_file']))
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
	case P_ConvertWiki: $header = 'Wikisyntax konvertieren'; break;
	case P_Backup: $header = 'Datensicherung'; break;
	case P_Options: $header = 'Einstellungen'; break;
	case P_Rename: $header = 'Umbenennen'; break;
	case P_ShowUsers: $header = 'Benutzerübersicht'; break;
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
	guiStandardHeader ($session, 'Anmeldung f&uuml;r den InfoBasar', Th_StandardHeader,
		Th_StandardBodyStart);
	guiStartForm ($session, 'login', P_Login);
	if (! empty ($message)) {
		$message = preg_replace ('/^\+/', '+++ Fehler: ', $message);
		guiParagraph ($session, $message, false);
	}
	outTableAndRecord ();
	outTableTextField ('Benutzername:', 'login_user', null, 32);
	outTableRecordDelim ();
	outTablePasswordField ('Passwort:', 'login_code', null, 32);
	outTableRecordDelim ();
	outTableRecordDelim ();
	outTableButton (' ', 'but_login', 'Anmelden');
	outTableAndRecordEnd ();
	guiFinishForm ($session, $session);
	guiStandardBodyEnd ($session, Th_StandardBodyEnd);
	return 1;
}
function admLoginAnswer (&$session) {
	$again = true;
	$session->trace (TC_Gui1, 'admLoginAnswer');
	global $session_user;
	$rc = dbCheckUser ($session, $_POST ['login_user'], $_POST ['login_code']);
	if (! empty ($rc))
		admLogin ($session, $rc);
	else {
		setLoginCookie ($session, $_POST ['login_user'], $_POST ['login_code']);
		$session->setPageName (P_Home);
		$again = false;
	}
	return $again;
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
	guiParagraph ($session, admStandardLinkString ($session, P_ConvertWiki), false);
	guiParagraph ($session, admStandardLinkString ($session, P_Rename), false);
	guiParagraph ($session, admStandardLinkString ($session, P_ShowUsers), false);
	guiParagraph ($session, admStandardLinkString ($session, P_Backup), false);
	guiParagraph ($session, admStandardLinkString ($session, P_Options), false);
	guiParagraph ($session, admStandardLinkString ($session, P_Login), false);
	guiParagraph ($session, admStandardLinkString ($session, P_PHPInfo), false);
	guiFinishBody ($session, null);
}
function admParam (&$session, $message){
	$session->trace(TC_Gui1, 'admParam');
	guiStandardHeader ($session, 'Parameter', Th_StandardHeader, Th_StandardBodyStart);

	getTextareaSize ($session, $width, $height);
	if (empty ($_POST ['param_theme']))
		$_POST ['param_theme'] = Theme_Standard;
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	guiStartForm ($session, 'param', P_Param);
	if (! isset ($_POST ['param_id']))
		$_POST ['param_id'] = 0;
	if (! isset ($_POST ['param_pos']))
		$_POST ['param_pos'] = 10;
	if (! isset ($_POST ['param_theme']))
		$_POST ['param_theme'] = 1;
	guiHiddenField ('param_id');
	outTableAndRecord ();
	outTableCell ('Id:');
	outTableCell ($_POST ['param_id']);
	outTableRecordDelim ();
	outTableCell ('Theme/Pos: ');
	outTableDelim ();
	guiTextField ('param_theme', null, 4, 4);
	echo ' / ';
	guiTextField ('param_pos', null, 4, 4);
	echo ' ';
	guiButton ('param_load', 'Datensatz laden');
	outTableDelimEnd ();
	outTableRecordDelim ();
	outTableTextField ('Name:', 'param_name', null, 64, 64);
	outTableRecordDelim ();
	outTableTextArea ('Text:', 'param_text', null, $width, $height);
	outTableRecordDelim ();
	outTableCell ('');
	outTableDelim ();
	guiButton2 ('param_insert', 'Eintragen', ' | ', 'param_change', 'Ändern');
	echo ' | Eingabefeld: Breite: ';
	guiTextField ('textarea_width', null, 3, 3);
	echo ' Höhe: ';
	guiTextField ('textarea_height', null, 3, 3);
	outTableDelimAndRecordEnd ();
	outTableEnd ();
	guiHeadline ($session, 2, 'Parameter von Theme ' . $_POST['param_theme']);
	outTableAndRecord (1);
	outTableCellStrong ('Pos');
	outTableCellStrong ('Beschreibung');
	outTableCellStrong ('Wert');
	outTableRecordEnd ();
	$row = dbFirstRecord ($session, 'select pos,name,text from '
		. dbTable ($session, T_Param) . ' where theme=' . $_POST['param_theme']
		. ' order by pos');
	while ($row) {
		outTableRecordAndDelim ();
		echo $row[0];
		outTableCellDelim ();
		echo htmlentities ($row [1]);
		outTableCellDelim ();
		echo htmlentities ($row [2]);
		outTableDelimAndRecordEnd ();
		$row  = dbNextRecord ($session);
	}
	outTableEnd ();
	guiFinishForm ($session);
	guiFinishBody ($session, null);
}
function admParamAnswerLoad (&$session){
	$session->trace(TC_Gui1, 'admParamAnswerLoad');
	$_POST ['param_text'] = textAreaToWiki ($session, $_POST ['param_text']);
	if (! isInt ($_POST ['param_theme']))
		$error = 'Theme nicht gültig: ' . $_POST ['param_theme'];
	elseif (! isInt ($_POST ['param_pos']))
		$error = 'Pos nicht gültig: ' . $_POST ['param_pos'];
	else {
		$record = dbGetRecordByClause ($session, T_Param, 'id,name,text',
			'theme=' . $_POST ['param_theme'] . ' and pos=' . $_POST ['param_pos']);
		$_POST ['param_id'] = $record[0];
		$_POST ['param_name'] = $record[1];
		$_POST ['param_text'] = $record [2];
		$error = null;
	}
	admParam ($session, $error);
}
function admParamAnswerChange (&$session, $mode){
	$session->trace(TC_Gui1, 'admParamAnswerChange');
	$_POST ['param_text'] = textAreaToWiki ($session, $_POST ['param_text']);
	if (! isInt ($_POST ['param_theme']))
		$message = 'Theme nicht gültig: ' . $_POST ['param_theme'];
	elseif (! isInt ($_POST ['param_pos']))
		$message = 'Pos nicht gültig: ' . $_POST ['param_pos'];
	elseif ($mode == C_New
		&& dbSingleValue ($session, 'select count(id) from ' . dbTable ($session, T_Param)
		. ' where theme=' . $_POST ['param_theme'] . ' and pos=' . $_POST ['param_pos']) > 0)
		$message = 'Eintrag nicht möglich, da (Theme,Pos) schon existiert';
	elseif ($mode == C_Change
		&& dbSingleValue ($session, 'select id from ' . dbTable ($session, T_Param)
		. ' where theme=' . $_POST ['param_theme'] . ' and pos=' . $_POST ['param_pos']) <= 0)
		$message = 'Ändern nicht möglich, da (Id, Theme, Pos) nicht korrekt';
	else {
		if ($mode == C_New) {
			dbInsert ($session, T_Param, 'theme,pos,name,text',
				$_POST ['param_theme'] . ',' . $_POST ['param_pos'] . ','
				. dbSqlString ($session, $_POST ['param_name']) . ','
				. dbSqlString ($session, $_POST ['param_text']));
			$message = 'Parameter wurde eingefügt';
		} elseif ($mode == C_Change){
			dbUpdateRaw ($session, T_Param, $_POST ['param_id'], 'name='
				. dbSqlString ($session, $_POST ['param_name'])
				. ',' . 'text=' . dbSqlString ($session, $_POST ['param_text']));
			$message = 'Parameter wurde geändert';
		} else
			$message = 'Unbekannter Modus: ' . $mode;
	}
	admParam ($session, $message);
}

function admMacro (&$session, $message){
	$session->trace(TC_Gui1, 'admMacro');
	guiStandardHeader ($session, 'Makros', Th_StandardHeader, Th_StandardBodyStart);
	getTextareaSize ($session, $width, $height);
	if (empty ($_POST ['macro_theme']))
		$_POST ['macro_theme'] = Theme_All;
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	guiStartForm ($session, "macro", P_Macro);
	outTableAndRecord ();
	outTableCell ('Theme/Name: ');
	outTableDelim ();
	guiTextField ('macro_theme', null, 4, 4);
	echo ' / ';
	guiTextField ('macro_name', null, 32, 64);
	echo ' ';
	guiButton ('macro_load', 'Datensatz laden');
	outTableDelimEnd ();
	outTableRecordDelim ();
	outTableTextField ('Beschreibung:', 'macro_description', null, 64, 255);
	outTableRecordDelim ();
	outTableTextArea ('Wert:', 'macro_text', null, $width, $height);
	outTableRecordDelim ();
	outTableCell ('');
	outTableDelim ();
	guiButton2 ('macro_insert', 'Eintragen', ' | ', 'macro_change', 'Ändern');
	echo ' | Eingabefeld: Breite: ';
	guiTextField ('textarea_width', null, 3, 3);
	echo ' Höhe: ';
	guiTextField ('textarea_height', null, 3, 3);
	outTableDelimAndRecordEnd ();
	outTableEnd ();
	guiHeadline ($session, 2, 'Makros von Theme ' . $_POST['macro_theme']);
	outTableAndRecord (1);
	outTableCellStrong ('Id');
	outTableCellStrong ('Theme');
	outTableCellStrong ('Name');
	outTableCellStrong ('Beschreibung');
	outTableCellStrong ('Wert');
	outTableRecordEnd ();

	$row = dbFirstRecord ($session, 'select id,theme,name,description,value from '
		. dbTable ($session, T_Macro) . ' where theme=' . $_POST['macro_theme']
		. ' order by name');
	while ($row) {
		outTableRecordAndDelim ();
		echo $row[0];
		outTableCellDelim ();
		echo htmlentities ($row [1]);
		outTableCellDelim ();
		echo htmlentities ($row [2]);
		outTableCellDelim ();
		echo htmlentities ($row [3]);
		outTableCellDelim ();
		echo htmlentities ($row [4]);
		outTableDelimAndRecordEnd ();
		$row  = dbNextRecord ($session);
	}
	outTableEnd ();
	guiFinishForm ($session);
	guiFinishBody ($session, null);
}
function admMacroAnswerLoad (&$session){
	$session->trace(TC_Gui1, 'admMacroAnswerLoad');
	$_POST['macro_text'] = textAreaToWiki ($session, $_POST['macro_text']);
	if (! isInt ($_POST['macro_theme']))
		$error = 'Theme nicht gültig: ' . $_POST['macro_theme'];
	elseif (empty ($_POST['macro_name']))
		$error = 'Kein Name angegeben: ' . $_POST['macro_name'];
	else {
		list ($_POST['macro_name'], $_POST['macro_description'], $_POST['macro_text']) 
			= dbGetRecordByClause ($session, 
			T_Macro, 'name,description,value', 'theme=' . $_POST['macro_theme'] . ' and name=' 
			. dbSqlString ($session, $_POST['macro_name']));
		$error = '';
	}
	admMacro ($session, $error);
}
function admMacroAnswerChange (&$session, $mode){
	$session->trace(TC_Gui1, 'admMacroAnswerChange');
	$id = null;
	$_POST['macro_text'] = textAreaToWiki ($session, $_POST['macro_text']);
	$count = 0;
	if (! isInt ($_POST['macro_theme']))
		$message = 'Theme nicht gültig: ' . $_POST['macro_theme'];
	elseif (empty ($_POST['macro_name']))
		$message = 'kein Name angegeben: ' . $_POST['macro_name'];
	elseif ($mode == C_New
		&& ($count = dbSingleValue ($session, 'select count(id) from ' . dbTable ($session, T_Macro)
		. ' where theme=' . $_POST['macro_theme'] . ' and name='
		. dbSqlString ($session, $_POST['macro_name']))) > 0)
		$message = 'Eintrag nicht möglich, da (Theme,Name) schon existiert';
	elseif ($mode == C_Change
		&& ($id = dbSingleValue ($session, 'select id from ' . dbTable ($session, T_Macro)
		. ' where theme=' . $_POST['macro_theme'] . ' and name='
		. dbSqlString ($session, $_POST['macro_name']))) <= 0)
		$message = 'Ändern nicht möglich, da (Theme, Name) nicht existiert';
	else {
		$session->trace(TC_Gui3, 'admMacroAnswerChange-2: ' . $count . ' / ' . $mode);
		
		if ($mode == C_New) {
			dbInsert ($session, T_Macro, 'theme,name,description,value',
				$_POST['macro_theme'] . ',' 
				. dbSqlString ($session, $_POST['macro_name']) . ','
				. dbSqlString ($session, $_POST['macro_description']) . ','
				. dbSqlString ($session, $_POST['macro_text']));
			$message = 'Makro ' . $_POST['macro_name'] . ' wurde eingefügt';
		} elseif ($mode == C_Change){
			dbUpdateRaw ($session, T_Macro, $id, 'value='
				. dbSqlString ($session, $_POST['macro_text'])
				. ',' . 'description=' . dbSqlString ($session, $_POST['macro_description'])
				. ',' . 'value=' . dbSqlString ($session, $_POST['macro_text']));
			$message = 'Makro ' . $_POST['macro_name'] . ' wurde geändert';
		} else
			$message = 'Unbekannter Modus: ' . $mode;
	}
	admMacro ($session, $message);
}
function admForum (&$session, $message, $mode){
	$session->trace(TC_Gui1, 'admForum');
	guiStandardHeader ($session, 'Forumsverwaltung', Th_StandardHeader, Th_StandardBodyStart);
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	guiStartForm ($session, "forum", P_Forum);
	guiHiddenField ('forum_id');
	guiShowTable ($session, "<h2>Existierende Foren</h2>\n",
		array ('Id', 'Name', 'Beschreibung'),
		'select id,name,description from ' . dbTable ($session, T_Forum),
		true, 'border="1" width="100%"');
	guiHeadline ($session, 2, 'Änderungen');
	outTableAndRecord ();
	if ($mode == C_Change){
		outTableCell ('Id:');
		outTableCell ($_POST['forum_id']);
		outTableRecordDelim ();
	}
	outTableTextField ('Name:', 'forum_name', null, 64, 64);
	outTableRecordDelim ();
	outTableTextField ('Beschreibung:', 'forum_description', null, 64, 255);
	outTableRecordDelim ();
	outTableCell (' ');
	if (! empty ($_POST['forum_id'])) {
		guiButton ('forum_change', 'Ändern');
		echo ' | ';
	}
	guiButton ('forum_insert', 'Eintragen');
	outTableDelimEnd ();
	outTableRecordDelim ();
	outTableCell ('Id:'); 
	outTableDelim ();
	guiTextField ('forum_changeid', null, 4, 4);
	echo  ' ';
	guiButton ('forum_load', 'Datensatz laden');
	outTableDelimAndRecordEnd ();
	outTableEnd ();
	guiFinishForm ($session);
	guiFinishBody ($session, null);
}
function admForumAnswer (&$session){
	$message = null;
	$mode = C_New;
	if (isset ($_POST['forum_load'])) {
		if (empty ($_POST['forum_changeid']) || ! IsInt ($_POST['forum_changeid'])){
			$message = '+++ keine Id angegeben';
		} else {
			list ($_POST['forum_name'], $_POST['forum_description'], $dummy)
				= dbGetRecordById ($session, T_Forum, $_POST['forum_changeid'],
					'name,description,readgroup,writegroup,admingroup');
				$_POST['forum_id'] = $_POST['forum_changeid'];
				$_POST['forum_changeid'] = null;
				$mode = C_Change;
		}
	} elseif (isset ($_POST['forum_insert'])) {
		if (empty ($_POST['forum_name']))
			$message = '+++ kein Name angegeben';
		elseif (empty ($_POST['forum_description']))
			$message = '+++ keine Beschreibung angegeben';
		elseif (dbSingleValue ($session, 'select count(id) from ' . dbTable ($session, T_Forum)
			. ' where name=' . dbSqlString ($session, $_POST['forum_name'])) > 0)
			$message = '+++ Forum mit diesem Namen existiert schon';
		else {
			$id = dbInsert ($session, T_Forum,
				'name,description,readgroup,writegroup,admingroup',
				dbSqlString ($session, $_POST['forum_name'])
				. ',' . dbSqlString ($session, $_POST['forum_description'])
				. ',1,1,1');
			$_POST['forum_name'] = $_POST['forum_description'] = null;
			$message = 'Forum wurde unter der Id ' . $id . ' eingetragen';
			$mode = C_New;
		}
	} elseif (isset ($_POST['forum_change'])) {
		if (dbSingleValue ($session,
			'select count(id) from ' . dbTable ($session, T_Forum)
			. ' where name=' . dbSqlString ($session, $_POST['forum_name'])
			. ' and id<>' . $_POST['forum_id']) > 0)
			$message = '+++ Forum mit diesem Namen existiert schon: ' . $_POST['forum_name'];
		else {
			dbUpdateRaw ($session, T_Forum, $_POST['forum_id'],
				'name=' . dbSqlString ($session, $_POST['forum_name'])
				. ',description=' . dbSqlString ($session, $_POST['forum_description']));
			$message = "Forum " . $_POST['forum_name'] . ' wurde geändert';
			$_POST['forum_id'] = $_POST['forum_name'] = $_POST['forum_description']
				= $_POST['forum_changeid'] = null;
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
function admConvertWiki (&$session, $message) {
	$session->trace(TC_Gui1, 'admConvertWiki');
	guiStandardHeader ($session, 'Wiki-Syntax konvertieren', Th_StandardHeader,
		Th_StandardBodyStart);
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	guiStartForm ($session, 'convert', P_ConvertWiki);
	outTableandRecord();
	outTableComboBox ('Konversionstyp:', 'conversion_type', array ('0.6 nach 0.7'), null, null);
	outTableCell (' ');
	outTableRecordDelim ();
	outTableButton (' ', 'conversion_run', 'Konvertieren');
	outTableAndRecordEnd ();
	guiFinishForm ($session);
	guiFinishBody ($session, null);
}
function wiki06To07 ($text){
	$text = str_replace (array ("'''", '[', ']'), array ('!%!Zitat!%!', '[[', ']]'), $text);
	$text = str_replace (array ("''", '!%!Zitat!%!', '[[Newline]]', '[[code]]', '[[/code]]'), 
		array ("'''", "''", '[newline]', '[code]', '[/code]'), $text); 
	$text = preg_replace ('/(\[\S+) ([^\]]+\])/', '$1|$2', $text);
	return $text;
}
function admConvertWikiAnswer (&$session){
	$session->trace(TC_Gui1, 'admConvertWikiAnswer');
	$message = null;
	if (isset ($_POST ['conversion_run'])) {
		if (empty ($_POST ['conversion_type']) ){
			$message = '+++ kein Konversionstyp angegeben';
		} elseif ($_POST ['conversion_type'] != '0.6 nach 0.7')
			$message = 'unbekannter Konversionstyp: ' . $_POST ['conversion_type']; 
		else {
			$query = 'select id, text from ' . dbTable ($session, T_Text)
				. ' where 1';
			$count = $count_changed = 0;
			$result = mysql_query ($query, $session->fDbInfo);
			if (! $result)
				$message = mysql_error ();
			else {
				do {
					$count++;
					$row = mysql_fetch_row ($result);
					if ($row){
						$text = wiki06To07 ($row [1]);
						if ($text != $row [1]){
							$count_changed++;
							dbUpdateRaw ($session, T_Text, $row [0], 
								'text=' . dbSqlString ($session, $text));
						}
					}
				} while ($row);
				$message = 'Von ' . ($count + 0) . ' Texten wurden ' . ($count_changed + 0) 
					. ' konvertiert';
			}
		}
	}
	admConvertWiki ($session, $message);
}

function admExportPages (&$session, $message) {
	$session->trace(TC_Gui1, 'admExportPages');
	guiStandardHeader ($session, 'Seitenexport', Th_StandardHeader,
		Th_StandardBodyStart);
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	if (isset ($_POST ['export_preview']) && ! empty ($_POST ['export_pattern']))
		guiShowTable ($session, '<h2>Passende Seiten ('
			. htmlentities ($_POST ['export_pattern']) . "):</h2>\n",
			array ('Id', 'Name'),
			'select id,name from ' . dbTable ($session, T_Page)
				.  ' where ' . admBuildCondition ($session, $_POST ['export_pattern']),
			true, 'border="1"');
	if (isset ($_POST ['export_exists']))
		guiParagraph ($session, 'Exportdatei: '
			. guiInternLinkString ($session, $_POST ['export_exists'], null), false);
	guiStartForm ($session, "export", P_ExportPages);
	guiHiddenField ('export_exists');
	outTableAndRecord();
	outTableTextField ('Namensmuster', 'export_pattern', null, 64);
	outTableCell ('Joker: %: beliebig viele Zeichen _: ein Zeichen |: neues Teilmuster'
		. TAG_NEWLINE . 'Bsp: Hilfe%|%Test%');
	outTableRecordDelim ();
	outTableComboBox ('Exportform:', 'export_type', array ('wiki'), null, null);
	outTableCell (' ');
	outTableRecordDelim ();
	outTableButton2 (' ', 'export_preview', 'Vorschau', ' | ', 'export_export', 'Exportieren');
	outTableAndRecordEnd ();
	guiFinishForm ($session);
	guiFinishBody ($session, null);
}
function admExportPagesAnswer (&$session){
	$session->trace(TC_Gui1, 'admExportPagesAnswer');
	$message = null;
	if (isset ($_POST ['export_export'])) {
		if (empty ($_POST ['export_pattern']) ){
			$message = '+++ kein Suchmuster angegeben';
		} else {
			$page_list = dbIdList ($session, T_Page,
				admBuildCondition ($session, $_POST ['export_pattern']));
			$fn = $session->fullPath ("import", true) . FN_PageExport;
			if (! $file = fopen ($fn, 'w'))
				$message = 'kann Datei nicht öffnen: ' . $fn;
			else {
				$prefix = $session->fDbTablePrefix;
				fputs ($file, '# Export ' . "\n"
					. '# am: ' . strftime ("%Y.%m.%d %H:%M:%S") . "\n"
					. '# von: ' . $session->fUserName . "\n"
					. '# Modus: ' . $_POST ['export_type'] . "\n"
					. '# Prefix: ' . $prefix . "\n"
					. '# Muster: ' . $_POST ['export_pattern'] . "\n"
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
					if ($_POST ['export_type'] == 'wiki') {
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
				$_POST ['export_exists'] = $fn;
				$message = 'Datei ' . $fn . ' wurde exportiert: ' . ($count+0) . " Seite(n)";
			}
		}
	}
	admExportPages ($session, $message);
}
function admImportPages (&$session,  $message) {
	$session->trace(TC_Gui1, 'admImportPages');
	if (false && $message == null && isset ($_POST ['import_import']))
		admImportPagesAnswer ($session);
	else {
		guiStandardHeader ($session, 'Seitenimport', Th_StandardHeader,
			Th_StandardBodyStart);
		if (! empty ($message))
			guiParagraph ($session, $message, false);
	
		guiUploadFile ($session, 'Importdatei:', $_POST ['last_page'], null, null, 
			'Hochladen', 'import_upload', 'import_file', 500000);
		$dir_name = $session->fullPath ("import");
		$dir = opendir ($dir_name);
		guiHeadline ($session, 3, "Importverzeichnis auf dem Server: " . $dir_name);
		guiStartForm ($session, "import", P_ImportPages);
		guiCheckBox ('import_replace', 'Historie löschen', null);
		outNewline ();
		outTableAndRecord (1);
		outTableCellStrong ('Name');
		outTableCellStrong ('Größe');
		outTableCellStrong ('Geändert am');
		outTableCellStrong ('Aktion');
		outTableDelimAndRecordEnd ();
		$path = $session->fullPath ("import", true); 
		$no = 0;
		while ($file = readdir ($dir)){
			if ($file != '.' && $file != '..'){
				$name = $path . $file;
				outTableRecordAndDelim ();
				echo htmlentities ($file);
				outTableCellDelim ();
				echo is_dir ($name) ? 'Verzeichnis' : filesize ($name);
				outTableCellDelim ();
				echo date ("Y.m.d H:i:s", filemtime ($name));
				outTableCellDelim ();
				guiHiddenField ('import_file', $name);
				guiButton ('import_import', 'Importieren');
				outTableDelimAndRecordEnd ();
			}
		}
		outTableEnd ();
		closedir ($dir);
	
		guiFinishForm ($session);
		guiFinishBody ($session, null);
	}
}
function admImportPagesAnswer (&$session){
	guiStandardHeader ($session, 'SeitenimportAntwort', Th_StandardHeader,
		Th_StandardBodyStart);
	$session->trace(TC_Gui1, 'admImportPagesAnswer');
	$message = null;
	if (isset ($import_upload)){
		$message = guiUploadFileAnswer ($session,  "/import/",
			null, 'import_upload', 'import_file'); 
	} elseif (isset ($_POST ['import_import'])){
		if (! file_exists ($_POST ['import_file']))
			$message = "Datei nicht gefunden: " . $_POST ['import_file'];
		else {
			$file = fopen ($_POST ['import_file'], "r");
			$count_inserts = 0;
			$count_updates = 0;
			$count_lines = 0;
			while ($line = fgets ($file)){
				if (preg_match ('/^#name=(\S+)\tlines=(\d+)\ttype=(\w+)\t/', $line, $param)){
					$name = $param[1];
					$lines = $param[2];
					$type = $param [3];
					$session->trace(TC_Gui1, 'admImportPagesAnswer-2: ' . $line);
					if ( ($page = dbPageId ($session, $name)) > 0){
						$count_updates++;
						if ($_POST ['import_replace'] == C_CHECKBOX_TRUE)
							dbDeleteByClause ($session, T_Text, 'page=' . $page);
					} else {
						$page = dbInsert ($session, T_Page, 'name,type', 
							dbSqlString ($session, $name) . ',' 
							. dbSqlString ($session, $type));
						$count_inserts++;
					}
					$text = "";
					$session->trace(TC_Gui1, 'admImportPagesAnswer-3: ' . $lines);
					$count_lines += $lines;
					for ($ii = 0; $ii < $lines; $ii++)
						$text .= fgets ($file);
					if ($_POST ['import_replace'] == C_CHECKBOX_TRUE)
						$old_id = dbSingleValue ($session, 'select max(id) from ' 
							. dbTable ($session, T_Text) . ' where page=' . (0+$page));
					$text_id = dbInsert ($session, T_Text, 'page,type,text', 
						$page . ',' . dbSqlString ($session, $type)
						. ',' . dbSqlString ($session, $text));
					if ($_POST ['import_replace'] == C_CHECKBOX_TRUE && $old_id > 0)
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
	$session->trace(TC_Gui1, 'admBackup');
	if ($with_header)
		guiStandardHeader ($session, 'Datenbank-Backup', Th_StandardHeader, Th_StandardBodyStart);
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	if (empty ($_POST ['backup_file']))
		$_POST ['backup_file'] = $session->fDbTablePrefix
			. strftime ("_%Y_%m_%d") . '.sql';
	guiHeadLine ($session, 1, 'Backup');;
	guiStartForm ($session, 'backup', P_Backup);
	outTableAndRecord ();
	outTableTextField ('Dateiname:', 'backup_file', null, 64, 64);
	outTableRecordDelim ();
	outTableCell ('Tabelle:');
	outTableDelim ();
	guiTextField ('backup_table', null, 64, 64);
	echo ' (leer: alle Tabellen)';
	outTableDelimEnd ();
	outTableRecordDelim ();
	outTableCheckBox (' ', 'backup_compressed', 'komprimiert', null);
	outTableRecordDelim ();
	outTableButton (' ', 'backup_save', 'Sichern');
	outTableAndRecordEnd ();
	guiFinishForm ($session);
	guiFinishBody ($session, null);
}

function admWriteOneTable (&$session, $table, $file){
	outTableRecordAndDelim ();
	echo $table;
	outTableCellDelim ();
	fwrite ($file, "\n# Tabelle " . $table . "\n\n");
	$bytes = admSaveTable ($session,$table, false, $file);
	echo (0 + $bytes);
	outTableDelimAndRecordEnd ();
	return $bytes;
}
function admBackupAnswer (&$session){
	$session->trace(TC_Gui1, 'admBackupAnswer');
	$message = null;
	
	guiHeader ($session, 'Datenbank-Backup');
	if (isset ($_POST ['backup_save'])) {
		if (empty ($_POST ['backup_table']))
			$_POST ['backup_table'] = '*';
		guiHeadline ($session, 1, 'Backup der Tabelle ' . $_POST ['backup_table']);
		$filename = $_POST ['backup_table'] == '*' ? 'db_infobasar' : 'table_' 
			. $_POST ['backup_table'];
		if (! is_dir ($dir =  $session->fullPath ("backup")))
			mkdir ($dir);
		if (empty ($_POST ['backup_file']))
			$_POST ['backup_file'] = $session->fMacroBasarName 
				. strftime ('_%Y_%m_%d.sql');
		$filename = 'backup/' . $_POST ['backup_file'];
		$is_compressed = guiChecked ($session, 'backup_compressed');
		if ($is_compressed)
			$filename .= '.gz';
		$open_name = $is_compressed 
			? 'compress.zlib://' . $session->fullPath ($filename)
			:  $session->fullPath ($filename);
		$file = fopen ($open_name, $is_compressed  ? 'wb9' : 'wb');
		fwrite ($file, '# InfoBasar: SQL Dump / Version: ' . PHP_ClassVersion
			. " \n# gesichert am " 
			. strftime ('%Y.%m.%d %H:%M:%S', time ())
			. "\n");
		echo '<table border="0">';
		if ($_POST ['backup_table'] != '*') {
			$bytes = admSaveTable ($session, $_POST ['backup_table'], false, $file);
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
		$size = ! $is_compressed ? $bytes : filesize ( $session->fullPath ($filename));
		echo '<tr><td>Summe:</td><td>' . (0 + $bytes);
		if ($is_compressed)
			echo ' (' . (0 + $size) . ')';
		echo '</td></tr></table>' . "\n";
		echo '<br/>';
		guiStaticDataLink ($session, '', $filename, 'Datei ' . $filename);
	}
	admBackup ($session, false, $message);
}
function admOptions (&$session, $message){
	$session->trace (TC_Gui1, 'admOptions');
	guiStandardHeader ($session, 'Allgemeine Einstellungen', Th_StandardHeader, Th_StandardBodyStart);
	if (isset ($_POST ['upload_go']))
		$message = guiUploadFileAnswer ($session, PATH_DELIM . 'pic' . PATH_DELIM, 
			'logo.png');
	if (! empty ($message))
		guiParagraph ($session, $message, false);

	guiHeadline ($session, 2, 'Texte:');
	if (empty ($_POST ['opt_basarname']))
		$_POST ['opt_basarname'] = $session->fMacroBasarName;
	if (empty ($_POST ['opt_css']))
		$_POST ['opt_css'] = dbGetText ($session, Th_CSSFile);
	guiStartForm ($session, 'Form', P_Options);
	outTableAndRecord ();
	outTableTextField ('Basarname:', 'opt_basarname', null, 32, 128);
	outTableRecordDelim ();
	outTableButton (' ', 'opt_save', '&Auml;ndern');
	outTableAndRecordEnd ();
	guiFinishForm ($session);
	guiHeadline ($session, 2, 'Dateien:');
	guiUploadFile ($session, 'Logo:', P_Options);
	guiFinishBody ($session, null);
}
function admRename (&$session, $message){
	$session->trace (TC_Gui1, 'admRename');
	guiStandardHeader ($session, 'Umbenennen einer Seite', Th_StandardHeader, Th_StandardBodyStart);
	if (! empty ($message))
		guiParagraph ($session, $message, false);

	guiStartForm ($session, 'Form', P_Rename);
	outTableAndRecord ();
	outTableTextField ('Bisheriger Name:', 'rename_oldname', null, 64, 64);
	outTableRecordDelim ();
	outTableTextField ('Neuer Name:', 'rename_newname', null, 64, 64);
	outTableRecordDelim ();
	outTableCell (' ');
	outTableDelim ();
	guiButton ('rename_info', 'Info');
	if (! empty ($_POST ['rename_oldname']) && ! empty ($_POST ['rename_newname'])){
		echo ' | ';
		guiButton ('rename_rename', 'Umbenennen');
		outNewline ();
		guiCheckBox ('rename_backlinks', 'Alle Verweise umbenennen', null);
	}
	outTableDelimAndRecordEnd ();
	outTableEnd ();
	guiFinishForm ($session);
	
	if (! empty ($_POST ['rename_oldname']) && dbPageId ($session, $_POST ['rename_oldname']) > 0){
		$row = dbFirstRecord ($session,
				'select page,text,createdby,createdat from '
				. dbTable ($session, T_Text)
				. ' where replacedby is null and text like '
				. dbSqlString ($session, '%' . $_POST ['rename_oldname'] . '%'));
		if (! $row)
			guiParagraph ($session, '+++ keine Verweise gefunden', false);
		else {
			outTableAndRecord (1);
			outTableCellStrong ('Seite');
			outTableCellStrong ('Typ');
			outTableCellStrong ('von');
			outTableCellStrong ('Letzte &Auml;nderung');
			outTableCellStrong ('Fundstelle');
			outTableCellStrong ('Seite');
			outTableRecordEnd ();
			while ($row) {
				$pagerecord = dbGetRecordById ($session, T_Page, $row[0],
					'name,type');
				$text = findTextInLine ($row [1], $_POST ['rename_oldname'], 10, true);
				if (! empty ($text)){
					outTableRecord ();
					outTableInternLink ($session, null,
							encodeWikiName ($session, $pagerecord[0]),
							$pagerecord[0], M_Base);
					outTableCell ($pagerecord [1]);
					outTableCell ($row [2]);
					outTableCell (htmlentities ($row [3]));
					outTableCell ($text);
					outTableRecordEnd ();
				}
				$row = dbNextRecord ($session);
			}
			outTableEnd ();
		}
	}
	guiFinishBody ($session, null);
}
function admAnswerRename (&$session){
	$session->trace (TC_Gui1, 'admAnswerRename');
	
	$message = null;
	$origin = isset ($_POST ['rename_newname']) ? $_POST ['rename_newname'] : null;

	if (!isset ($_POST ['rename_oldname']))
		$message = '+++ kein bisheriger Name angegeben!';
	elseif ( ($page_id = dbPageId ($session, $_POST ['rename_oldname'])) <= 0)
		$message = '+++ Seite ' . $_POST ['rename_oldname'] . ' existiert nicht';
	elseif (isset ($_POST ['rename_rename']) && !isset ($_POST ['rename_newname']))
		$message = '+++ kein neuer Name angegeben!';
	elseif (isset ($_POST ['rename_rename']) 
		&& ($_POST ['rename_newname'] = normalizeWikiName ($session, $_POST ['rename_newname']))
			!= $origin)
		$message = '+++ Unzulässiger neuer Name (' . $origin
				. ') wurde korrigiert';
	elseif (isset ($_POST ['rename_rename']) && dbPageId ($session, $_POST ['rename_newname']) > 0)
		$message = '+++ Seite ' . $_POST ['rename_newname'] . ' existiert schon!';
	elseif (isset ($_POST ['rename_rename'])){
		dbUpdate ($session, T_Page, $page_id, 
			'name=' . dbSQLString ($session, $_POST ['rename_newname']) . ',');
		$message = 'Seite ' . $_POST ['rename_oldname'] . ' wurde in ' . $_POST ['rename_newname'] 
			. ' umbenannt.';
		$pages = 0;
		$hits = 0;
		if (guiChecked ($session, 'rename_backlinks')){
			$row = dbFirstRecord ($session,
					'select id,text from '
					. dbTable ($session, T_Text)
					. ' where replacedby is null and text like '
					. dbSqlString ($session, '%' . $_POST ['rename_oldname'] . '%'));
			$pattern1 = '/([^' . CL_WikiName . '])' 
				. $_POST ['rename_oldname'] . '([^' . CL_WikiName . '])/';
			$pattern2 = '/^' . $_POST ['rename_oldname'] . '([^' . CL_WikiName . '])/';
			$pattern3 = '/([^' . CL_WikiName . '])' .  $_POST ['rename_oldname'] . '$/';
			$replacement1 = '\1' . $_POST ['rename_newname'] . '\2';
			$replacement2 = $_POST ['rename_newname'] . '\1';
			$replacement3 = '\1' . $_POST ['rename_newname'];
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
		addSystemMessage ($session, $_POST ['rename_oldname']
					. ' >> ' . $_POST ['rename_newname'] . ': ' . (0+$hits));
		$_POST ['rename_oldname'] = '';
		$_POST ['rename_newname'] = '';
	}
	admRename ($session, $message);
}
function admShowUsers (&$session, $message){
	$session->trace (TC_Gui1, 'admShowUsers');
	guiStandardHeader ($session, 'Alle Benutzer', Th_StandardHeader, Th_StandardBodyStart);
	if (! empty ($message))
		guiParagraph ($session, $message, false);

	guiStartForm ($session, 'Form', P_ShowUsers);
	dbPrintTable ($session, 'Select id,name,email,theme,startpage from ' . dbTable ($session, T_User)
		. ' where 1', array ("Id", "Name", "EMail", "Design", "Startseite"), 0);
	guiFinishForm ($session);
	guiFinishBody ($session, null);
}

function addSystemMessage (&$session, $message){
	echo "nicht implementiert: addSystemMessage()<br>\n";
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
