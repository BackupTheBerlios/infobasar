<?php
// index.php: Start page of the InfoBasar
// $Id: index.php,v 1.25 2005/01/07 21:14:47 hamatoma Exp $
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
define ('PHP_ModuleVersion', '0.7.0 (2005.01.03)');
set_magic_quotes_runtime(0);
error_reporting(E_ALL);

define ('C_ScriptName', 'index.php');

include "config.php";
include "classes.php";

$session_id = sessionStart ();


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
define ('P_Account', '!account');
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

$session = new Session ($start_time, $session_id, 
	$_SESSION ['session_user'], $_SESSION ['session_start'], $_SESSION ['session_no'],
	$db_type, $db_server, $db_user, $db_passw, $db_name, $db_prefix);
if (successfullLogin ($session)){
	$session->trace (TC_Init, 'index.php: std_answer: ' . (empty ($std_answer) ? '' : "($std_answer)"));
	if (isset ($_GET ['action'])) {
		$session->trace (TC_Init, 'index.php: action: ' . $_GET ['action']);
		switch ($_GET ['action']){
		case A_Edit: baseEditPage ($session, C_Change); break;
		case A_Search:	baseSearch ($session, ''); break;
		case A_PageInfo: basePageInfo ($session); break;
		case A_ShowText: guiShowPageById ($session, $_GET['page_id'], $_GET ['text_id']); break;
		case A_Diff: baseDiff ($session); break;
		case A_Show: baseShowCurrentPage ($session); break;
		case '': break;
		default:
			$session->trace (TC_Error, "index.php: unbek. Aktion: $action");
			baseFormTest ($session);
			break;
		}
	} elseif (isset ($_REQUEST ['std_answer']) || ! baseCallStandardPage ($session)) {
		$session->trace (TC_Init, 'index.php: keine Standardseite'
			. (isset ($_REQUEST ['edit_save']) 
				? (" (" . $_REQUEST ['edit_save'] . ")") : ' []'));
		if (isset ($test))
			baseTest ($session);
		else if (substr ($session->fPageName, 0, 1) == '.')
			baseNewPageReference ($session);
		else if (isset ($_POST ['last_refresh']))
			baseLastChanges ($session);
		elseif (isset ($_POST ['account_new']) || isset ($_POST ['account_change'])
			|| isset ($_POST ['account_other']))
			baseAccountAnswer ($session, $_POST ['account_user']);
		elseif (isset ($_POST ['search_title']) || isset ($_POST ['search_body']))
			baseSearch ($session, null);
		elseif (isset ($_POST ['edit_save']) 
				|| isset ($_POST ['edit_previewandsave']))
			baseEditPageAnswerSave ($session);
		elseif (isset ($_POST ['edit_cancel']) 
				|| isset ($_POST ['edit_preview']) 
				|| isset ($_POST ['edit_appendtemplate']) 
				|| isset ($_POST ['edit_upload']))
			baseEditPageAnswerNosave ($session);
		elseif (isset ($_POST ['posting_preview']) || isset ($_POST ['posting_insert'])
			|| isset ($_POST ['posting_change']))
			basePostingAnswer ($session);
		elseif ( ($page_id = dbPageId ($session, $session->fPageName)) > 0)
			guiShowPageById ($session, $page_id, null);
		else {
			guiLogin ($session, null);;
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
	$session->trace (TC_Gui1, 'baseAccount');
	$reload = false;
	if (isset ($_POST ['account_user']) && ! empty ($_POST ['account_user']))
		$account_user = $_POST ['account_user']; 
	else {
		$account_user = $session->fUserName;
		$reload = true;
	}
	if (! empty ($_POST ['account_user2']) 
			&& $account_user != $_POST ['account_user2']) {
		$account_user = $_POST ['account_user2'];
		$reload = true;
	}
	if (! $reload)
		$id = dbUserId ($session, $account_user);
	else {
		list ($id, $_POST ['account_locked'], $_POST ['account_width'],
			$_POST ['account_height'], $_POST ['account_maxhits'],
			$_POST ['account_theme'], $_POST ['account_startpage'],
			$_POST ['account_email'])
			= dbGetRecordByClause ($session, T_User,
			'id,locked,width,height,maxhits,theme,startpage,email',
			'name=' . dbSqlString ($session, $account_user));
	}
	guiStandardHeader ($session, 'Einstellungen f&uuml;r ' . $account_user,
		Th_StandardHeader, Th_StandardBodyStart);
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	guiStartForm ($session, 'account', P_Account);
	guiHiddenField ('account_user', $account_user);
	outTable();
	outTableRecordCells ('Benutzername', $account_user);
	outTableRecord();
	outTablePasswordField ('Passwort:', 'account_code', '', 64, 32);
	outTableRecordDelim ();
	outTablePasswordField ('Wiederholung:', 'account_code2', '', 64, 32);
	outTableRecordDelim ();
	outTableTextField ('EMail:', 'account_email', null, 64, 64);
	outTableRecordDelim ();
	outTableCheckBox ('Gesperrt', 'account_locked', 'Gesperrt');
	outTableRecordDelim ();
	dbGetThemes ($session, $theme_names, $theme_numbers);
	outTableComboBox ('Design:', 'account_theme', $theme_names, $theme_numbers, 
		array_search ($_POST ['account_theme'], $theme_numbers));
	outTableRecordDelim ();
	outTableTextField ('Eingabefeldbreite:', 'account_width', null, 64, 3);
	outTableRecordDelim ();
	outTableTextField ('Eingabefeldhöhe:', 'account_height', null, 64, 3);
	outTableRecordDelim ();
	outTableTextField ('Zahl Suchergebnisse:', 'account_maxhits', null, 64, 3);
	outTableRecordDelim ();
	$names = array ('WikiSeite:', 'Übersicht', 'Einstellungen',
			'Wikisuche', 'Letze Änderungen', 'StartSeite', 'Hilfe');
	$values = array ('', P_Home, P_Account, 
			P_Search, P_LastChanges, 'StartSeite', 'Hilfe');
	if ( ($pos = strpos ($_POST ['account_startpage'], '!')) == 0 && is_int ($pos))
		$ix = array_search ($_POST ['account_startpage'], $values);
	else
		$ix = 0;
	outTableCell ('Startseite:');
	outTableDelim ();
	guiComboBox ('account_startpageoffer', $names, $values, $ix);
	echo ' ';
	guiTextField ("account_startpage", null, 45, 128);
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
	$new = $session->fUserId <= 2 || $session->fUserName == 'wk' 
		|| $session->fUserName == 'admin'
		|| $session->testFeature (FEATURE_SIMPLE_USER_MANAGEMENT)
		|| strpos ($session->fUserName, $session->fAdmins) > 0;
	$change = $new;
	if ($change || $new){
		guiLine ($session, 2);
		outTable ();
		outTableRecord();
		outTableRecordEnd();
		outTableTextField ('Name:', "account_user2", null, 32, 32);
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
	$session->trace (TC_Gui1, 'baseAccountAnswer');
	$message = '';
	$code = encryptPassword ($session, $_POST ['account_user'], $_POST ['account_code']);
	$locked = dbSqlString ($session, ! empty ($_POST ['account_locked']));
	if (! empty ($_POST ['account_startpageoffer']))
		$_POST ['account_startpage'] = $_POST ['account_startpageoffer'];
	if (isset ($_POST ['account_new'])) {
		if ($_POST ['account_user2'] == '')
			$message = '+++ Kein Benutzername angegeben';
		elseif (dbGetValueByClause ($session, T_User,
			'count(*)', 'name=' + dbSqlString ($session, $_POST ['account_user'])) > 0)
			$message = '+++ Name schon vorhanden: ' + $_POST ['account_user2'];
		else {
			$uid = dbUserAdd ($session, $_POST ['account_user2'], $code,
				dbSqlString ($session, false), $_POST ['account_theme'], 
				$_POST ['account_width'], $_POST ['account_height'],
				$_POST ['account_maxhits'], $_POST ['account_startpage'], $_POST ['account_email']);
			modUserStoreData ($session, true, $uid);
			
			$message = 'Benutzer ' . $_POST ['account_user2'] . ' wurde angelegt. ID: ' . $uid;
		}
	} elseif (isset ($_POST ['account_change'])) {
		if (! empty ($_POST ['account_code']) 
			&& $_POST ['account_code'] != $_POST ['account_code2'])
			$message = '+++ Passwort stimmt mit Wiederholung nicht überein';
		elseif (! ($uid = dbUserId ($session, $_POST ['account_user'])) || empty ($uid))
			$message = '+++ unbekannter Benutzer: ' . $_POST ['account_name'];
		elseif ( ($message = modUserCheckData ($session, true, $uid)) != null)
			;
		else {
			if (empty ($_POST ['account_theme']))
				$_POST ['account_theme'] = Theme_Standard;
			$what = 'locked=' . $locked . ',';
			if (! empty ($_POST ['account_code']))
				$what .= 'code=' . dbSqlString ($session, $code) . ",";
			$what .= 'theme=' . $_POST ['account_theme'] . ',width=' 
			. $_POST ['account_width'] . ',height=' . (0 + $_POST ['account_height'])
			. ',maxhits=' . (0 + $_POST ['account_maxhits'])
			. ',startpage=' . dbSqlString ($session, $_POST ['account_startpage'])
			. ',email=' . dbSqlString ($session, $_POST ['account_email'])
			 . ',';
			dbUpdate ($session, T_User, $uid, $what);
			modUserStoreData ($session, false, $uid);
			$message = 'Daten für ' . $_POST ['account_user'] . ' (' . $uid
				. ') wurden geändert';
		}
	} elseif ($_POST ['account_other']) {
		if (empty ($_POST ['account_user2']))
			$message = '+++ kein Benutzername angegeben';
		elseif (! dbUserId ($session, $_POST ['account_user2']))
			$message = '+++ Unbekannter Benutzer: ' . $_POST ['account_user2'];
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
		outTableInternLink ($session, null, encodeWikiName ($session, 'StartSeite'), 
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
	} elseif ($mode == C_Change) {
		$pagename = $session->fPageName;
		list ($pageid, $texttype) = dbGetRecordByClause ($session, T_Page,
				'id,type', 'name=' . dbSqlString ($session, $pagename));
		$mimetype = textTypeToMime ($texttype);
		$textidpred = dbGetLastText ($session,$pageid);
		list ($content, $changedat, $changedby)
			= dbGetRecordById ($session, T_Text, $textidpred,
				'text,createdat,createdby');
		$textid = null;
	
	} else {
		$pagename = $_POST ['edit_pagename'];
		$pageid = $_POST ['edit_pageid'];
		$textid = $_POST ['edit_textid'];
		$content = textAreaToWiki ($session, $_POST ['edit_content']);
		$changedby = $_POST ['edit_changedby'];
		$changedat = $_POST ['edit_changedat'];
		$mimetype = $_POST ['edit_mimetype'];
		$textidpred = $_POST ['edit_textidpred'];
	}
	$session->setPageData (empty ($pagename) ? 'Neue Seite' : $pagename,
		 $changedat, $changedby);
	if ($pageid <= 0)
		$mode = C_New;
	if ($mode == C_New)
		$header =  empty ($pagename) ? 'Neue Seite' : $pagename . ' (Neu)';
	else 
		$header = $pagename . ' (in Bearbeitung)';
	if ($mimetype == M_Wiki)
		guiStandardHeader ($session, $header, Th_EditHeaderWiki, Th_EditStartWiki);
	else
		guiStandardHeader ($session, $header, Th_EditHeaderHTML, Th_EditStartHTML);
	if (isset ($_POST ['edit_preview']) || isset ($_POST ['edit_previewandsave'])) {
		echo guiParam ($session, Th_PreviewStart, '<h1>Vorschau von '
			. $session->fPageName
			. '</h1><p>Warnung: Der Text ist noch nicht gesichert!</p>');
		guiFormatPage ($session, $mimetype, $content);
		echo guiParam ($session, Th_PreviewEnd, '<h1>Ende der Vorschau</h1>');
	}
	echo '<form enctype="multipart/form-data" action="' . $session->fScriptURL
		. '" method="post">' . "\n";
	
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
		outTableTextField('Name:', 'edit_pagename', $pagename, 43, 64);
		outTableRecordEnd();
	} else {
		guiHiddenField ('edit_pagename', $pagename);
	}
	outTableRecord();
	if ($mode == C_New && $type == M_Undef)
		outTableComboBox ('Typ', 'edit_mimetype', array (M_Wiki, M_HTML), null, 0);
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
	getTextareaSize ($session, $width, $height);
	guiTextArea ('edit_content', $content, $width, $height);
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
		echo ' Breite: '; 
		guiTextField (U_TextAreaWidth, null, 3, 3);
		echo ' H&ouml;he: ';
	} else {	
		outTableDelimEnd();
		outTableTextField ('Breite:', 'textarea_width',null, 3, 3);
		outTableRecordEnd();
		outTableRecord();
		outTableCell ('Bild einf&uuml;gen:');
		guiHiddenField ('MAX_FILE_SIZE', 500000);
		outTableDelim(AL_Justify);
		guiFileField ('edit_upload_file');
		outTableDelimEnd();
		outTableButton (null, 'edit_upload', 'Hochladen');
		outTableCell ('H&ouml;he:');
		outTableDelim();
	} 
	guiTextField (U_TextAreaHeight, null, 3, 3);
	outTableAndRecordEnd();
	outTableDelimEnd();
	outTableAndRecordEnd();
	
	guiFinishForm ($session, $session);
	outNewline();
	guiStandardBodyEnd ($session,
		$mimetype == M_Wiki ? Th_EditEndWiki : Th_EditEndHTML);
}
function baseEditPageAnswerNoSave (&$session){
	$session->trace (TC_Gui1, 'baseEditPageAnswerNoSave');
	if (isset ($_POST ['edit_cancel'])) {
		if (isset ($_POST ['edit_pageid']) && $_POST ['edit_pageid'] > 0)
			guiShowPageById ($session, $_POST ['edit_pageid'], 0);
		else
			baseHome ($session, null);
	} else {
		$message = null;
		if (isset ($_POST ['edit_preview']))
			;
		elseif (isset ($_POST ['edit_appendtemplate'])){
			$page_id = dbPageId ($session,  $_POST ['edit_template']);
			if ($page_id > 0){
				$id = dbGetLastText ($session, $page_id);
				$_POST ['edit_content'] .= dbSingleValue ($session, 'select text from '
					. dbTable ($session, T_Text) . ' where id=' . (0+$id)); 
			}
		} elseif (isset ($_POST ['edit_upload'])){
			$name = $_POST ['edit_upload'];
			$session->trace (TC_Gui1, 'guiEditPageSaveAnswer:');
			$message = guiUploadFileAnswerUnique ($session, "/pic/",
				null, 'edit_upload_file', $name);
			$_POST ['edit_content'] .= "\n\n[http:pic/$name $name]\n\n";
		}
		baseEditPage ($session, C_LastMode, $message, null, $_POST ['edit_mimetype']);
	}
}

function baseEditPageAnswerSave (&$session)
{
	$session->trace (TC_Gui1, 'baseEditPageAnswerSave');
	$message2 = null;
	$message = null;
	$content = $_POST ['edit_content'];
	$len = strlen ($content);
	$content = textAreaToWiki ($session, $content);
	$session->trace (TC_Gui1, 'baseEditPageAnswerSave: ' . $content);
	if (! isset ($_POST ['edit_pageid']) || $_POST ['edit_pageid'] <= 0) {
		$session->trace (TC_Gui1, 'baseEditPageAnswerSave-2: ' . $_POST ['edit_pagename']);
		$_POST ['edit_pagename'] = normalizeWikiName ($session, $_POST ['edit_pagename']);
		$content = extractHtmlBody ($content);
		if (empty ($_POST ['edit_pagename']))
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
			$_POST ['edit_pageid'] = $page;
			$_POST ['edit_textid'] = dbInsert ($session, T_Text, 
				'page,type,text,createdby,createdat,changedat',
				$page+0
				. "," . dbSqlString ($session, mimeToTextType ($_POST ['edit_mimetype']))
				. ',' . dbSqlString ($session, $content)
				. ',' . dbSqlString ($session, $session->fUserName)
				. ',now(),now()');
			$session->trace (TC_Gui1, 'baseEditPageAnswerSave-3: ' . $page . '/' . $_POST ['edit_textid']);
		}
		$message2 = $len == strlen ($content)
			? '' : 'Es wurde der Rumpf (body) extrahiert.';
	} else {
		$pageid =  $_POST ['edit_pageid'];
		$new_textid = dbGetLastText ($session, $pageid);
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
						mimeToTextType ($_POST ['edit_mimetype']))
					. ",$date,$date," 
					. dbSqlString ($session, $session->fUserName)
					. ',' . dbSqlString ($session, $content));
			dbUpdate ($session, T_Text, $new_textid, 'replacedby=' 
				. $_POST ['edit_textid'] . ',');
		} else {
			dbUpdate ($session, T_Text, $_POST ['edit_textid'],
				"text=" . dbSqlString ($session, $content) . ",");
		}
	}
	if (isset ($_POST ['edit_save']) && $message == null)
		guiShowPageById ($session, $_POST ['edit_pageid'], 0);
	else
		baseEditPage ($session, C_Auto, $message, $message2);
}

function baseNewPageReference (&$session) {
	$session->trace (TC_Gui1, 'baseNewPageReference');
	$name = substr ($session->fPageName, 1);
	if ( ($page = dbPageId ($session, $name)) > 0)
		guiShowPageById ($session, $page, null);
	else {
		baseEditPage ($session, C_New, null, null, M_Wiki, $name);
	}
}

function baseSearch (&$session, $message){
	$session->trace (TC_Gui1, 'baseSearch');
	if (! isset ($_POST ['search_bodytext']) && isset ($_POST ['search_titletext']))
		$_POST ['search_bodytext'] = $_POST ['search_titletext'];
	getUserParam ($session, U_MaxHits, $_POST ['search_maxhits']);
	guiStandardHeader ($session, 'Suchen auf den Wiki-Seiten',
		Th_SearchHeader, Th_SearchBodyStart);
	if (isset ($_POST ['search_title']) || isset ($_POST ['search_body']))
		baseSearchResults ($session);
	guiStartForm ($session, 'search', P_Search);
	outTableAndRecord();
	outTableCell ('Titel:');
	outTableDelim();
	guiTextField ('search_titletext', null, 32, 64);
	echo " "; guiButton ('search_title', "Suchen");
	outTableDelimAndRecordEnd();
	outTableRecord();
	outTableCell ('Beitrag:');
	outTableDelim();
	guiTextField ('search_bodytext', null, 32, 64);
	echo " "; guiButton ('search_body', 'Suchen');
	outTableDelimAndRecordEnd();
	outTableTextField('Maximale Trefferzahl:', 'search_maxhits', null, 10, 10);
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
	$session->trace (TC_Gui1, 'baseSearchAnswer');
	if (isset ($_POST ['search_title'])) {
		if (empty ($_POST ['search_titletext']))
			guiParagraph ($session, $session, '+++ kein Seitentitel angegeben', false);
		$row = dbFirstRecord ($session,
				'select name,type from ' . dbTable ($session, T_Page)
				. ' where name like ' . dbSqlString ($session, '%' 
				. $_POST ['search_titletext'] . '%') 
				. ' limit ' . $_POST ['search_maxhits']);
		if (! $row)
			guiParagraph ($session, '+++ keine passenden Seiten gefunden', false);
		else {
			outTable (1);
			outTableRecord();
			outTableCellStrong ('Seite:');
			outTableCellStrong ('Typ:');
			outTableRecordEnd();
			while ($row) {
				outTableRecord();
				outTableInternLink($session, null, 
						encodeWikiName ($session, $row[0]), $row[0]);
				outTableCell (textTypeToMime($row[1]));
				outTableRecordEnd();
				$row = dbNextRecord ($session);
			}
			outTableEnd();
		}
	} else {
		if (empty ($_POST ['search_bodytext']))
			guiParagraph ($session, '+++ kein Suchtext angegeben');
		else {
			$row = dbFirstRecord ($session,
					'select page,text,createdby,createdat from '
					. dbTable ($session, T_Text)
					. ' where replacedby is null and text like '
					. dbSqlString ($session, '%' . $_POST ['search_bodytext'] . '%')
					. ' limit ' . $_POST ['search_maxhits']);
			if (! $row)
				guiParagraph ($session, '+++ keine passende Seiten gefunden', false);
			else {
				outTableAndRecord ($session, 1);
				outTableCellStrong ('Seite:');
				outTableCellStrong ('Typ:');
				outTableCellStrong ('von:');
				outTableCellStrong ('Letzte &Auml;nderung:');
				outTableCellStrong ('Fundstelle:');
				outTableRecordEnd();
				while ($row) {
					$pagerecord = dbGetRecordById ($session, T_Page, $row[0],
						'name,type');
					outTableRecord ();
					outTableInternLink ($session, null,
						encodeWikiName ($session, $pagerecord[0]), $pagerecord[0]);
					outTableCell ($pagerecord [1]);
					outTableCell ($row [2]);
					outTableCell (htmlentities ($row [3]));
					outTableCell (findTextInLine ($row [1], $_POST ['search_bodytext'], 3));
					outTableRecordEnd ();
					$row = dbNextRecord ($session);
				}
				outTableEnd ();
			}
		}
	}
}
function baseCallStandardPage (&$session) {
	$session->trace (TC_Gui2, 'baseCallStandardPage: ' . $session->fPageName);
	$found = true;
	switch ($session->fPageName) {
	case P_Login:	guiLogin ($session, ''); break;
	case P_Logout:	guiLogout ($session); break;
	case P_Account: baseAccount ($session, ''); break;
	case P_Home: 	baseHome ($session); break;
	case P_NewPage:	baseEditPage ($session, C_New); break;
	case P_NewWiki:	baseEditPage ($session, C_New, null, null, M_Wiki); break;
	case P_ModifyPage: EditPage ($session, C_Change); break;
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
		outTableAndRecord (1);
		outTableCellStrong ('Id');
		outTableCellStrong ('Autor');
		outTableCellStrong ('erzeugt');
		outTableCellStrong ('Unterschied zum Nachfolger');
		outTableCellStrong ('Unterschied zu jetzt');
		outTableRecordEnd ();
		$row = dbFirstRecord ($session,
			'select id,createdby,createdat,changedat,replacedby from '
				. dbTable ($session, T_Text) . ' where page=' . (0 + $pageid)
				. ' order by id desc');
		$act_text_id = $row [0];
		while ($row) {
			outTableRecord ();
			$text_id = $row [0];
			$replacedby = $row [4];
			outTableInternLink ($session, null,
				$pagelink . '?action=' . A_ShowText
				. '&page_id=' . $pageid . '&text_id=' . ($text_id+0), $text_id);
			outTableAuthorLink ($session, $row [1]);
			outTableCell (dbSqlDateToText ($session, $row [2]));
			if ($replacedby > 0) {
				outTableInternLink ($session, null, $pagelink . '?action=' . A_Diff
					. '&text_id=' . $replacedby . '&text_id2=' . $text_id,
					' Unterschied zu ' . $replacedby);
				if ($replacedby == $act_text_id)
					outTableCell (' ');
				else
					outTableInternLink ($session, null, $pagelink . '?action=' . A_Diff
						. '&text_id=' . $text_id . '&text_id2=' . $act_text_id,
						' Unterschied zu jetzt');
			} else {
				outTableCell (' ');
				outTableCell (' ');
			}
			outTableRecordEnd ();
			$row = dbNextRecord ($session);
		}
		outTableEnd ();
	}
	guiStandardBodyEnd ($session, Th_InfoBodyEnd);
}
function baseDiff (&$session) {
	baseCompare ($session, $session->fPageName, $_GET ['text_id'], $_GET ['text_id2']);
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
	$headline = 'Übersicht über die letzten Änderungen';
	guiStandardHeader ($session, $headline, Th_StandardHeader, Th_StandardBodyStart);
	if (! isset ($_POST ['last_days']) || $_POST ['last_days'] < 1)
		$_POST ['last_days'] = 7;
	guiStartForm ($session);
	outParagraph ();
	echo 'Zeitraum: die letzten ';
	guiTextField ('last_days', $_POST ['last_days'], 3, 4);
	echo ' Tage ';
	guiButton ('last_refresh', 'Aktualisieren');
	outParagraphEnd ();
	outTable ();

	for ($day = 0; $day <= $_POST ['last_days']; $day++) {
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
			outTableRecord ();
			outTableCellStrong ($time_0);
			outTableRecordEnd ();
			do {
				$text_id = $rec [0]+0;
				$page_id = $rec [5]+0;
				$page_name = $rec [1];
				$page_link = encodeWikiName ($session, $page_name);
				outTableRecord ();
				outTableCell (dbSingleValue ($session, 'select min(id) from '
					. dbTable ($session, T_Text) . ' where page=' . $page_id)
					== $text_id ? 'Neu' : 'Änderung');
				outTableInternLink ($session, null, $page_link . '?action=' . A_ShowText
					. '&page_id=' . $page_id . '&text_id=' . $text_id, $text_id);
				outTableInternLink ($session, null, $page_link, $page_name);
				outTableAuthorLink ($session, $rec [2]);
				outTableCell (dbSqlDateToText ($session, $rec [3]));
				$pred_rec = dbSingleValue ($session, 'select max(id) from '
					. dbTable ($session, T_Text) . ' where page=' . $page_id
					. ' and createdat<'
					. dbSqlDateTime ($session, $time_2));
				if ($pred_rec > 0) {
					outTableInternLink ($session, null, $page_link . '?action=' . A_Diff
						. '&text_id=' . $text_id . '&text_id2=' . $pred_rec,
						'Unterschied zum Vortag (' . $pred_rec . ')');
				}
				outTableRecordEnd ();
			} while ( ($rec = dbNextRecord ($session)) != null);
		}
	}
	outTableEnd();
	guiStandardBodyEnd ($session, Th_StandardBodyEnd);
}
function baseInfo (&$session) {
	guiStandardHeader ($session, 'Infobasar-Info', Th_InfoHeader, null);
	guiParagraph ($session, '(C) Hamatoma AT gmx DOT de 2004', 0);
	outTable();
	outTableRecord();
	outTableCellStrong ('Gegenstand');
	outTableCellStrong ('Version');
	outTableRecordDelim ();
	outTableCell ('PHP-Klasse:');
	outTableCell ('PHP_ClassVersion');
	outTableRecordDelim ();
	outTableCell ('DB-Schema:');
	outTableCell (htmlentities (dbGetParam ($session, Theme_All, Param_DBScheme)));
	outTableRecordDelim ();
	outTableCell ('DB-Basisinhalt:');
	outTableCell (htmlentities (dbGetParam ($session, Theme_All, Param_DBBaseContent)));
	outTableRecordDelim ();
	outTableCell ('DB-Erweiterungen:');
	outTableCell (htmlentities (dbGetParam ($session, Theme_All, Param_DBExtensions)));
	outTableRecordEnd ();
	outTableEnd ();
	guiStandardBodyEnd ($session, Th_InfoBodyEnd);
}
// --------------------
function diffTest (&$session) {
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
	guiStandardHeader ($session, 'Test', Th_StandardHeader, Th_StandardBodyStart);
	echo WikiToHtml ($session, "[code]\n\ra<b\n\rZeile2\n\r[/code]\n\r");
	guiStandardBodyEnd ($session, Th_StandardBodyEnd);
	# guiTestAll ($session);
}
function baseFormTest (&$session) {
	if (! isset ($_POST ['test_text']))
		$_POST ['test_text'] = 'Noch nix!';
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
