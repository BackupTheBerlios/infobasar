<?php
// classes.php: constants and classes
// $Id: classes.php,v 1.13 2004/10/30 10:39:31 hamatoma Exp $
/*
Diese Datei ist Teil von InfoBasar.
Copyright 2004 hamatoma@gmx.de München
InfoBasar ist freie Software. Du kannst es weitergeben oder verändern
unter den Bedingungen der GNU General Public Licence.
Näheres siehe Datei LICENCE.
InfoBasar sollte nützlich sein, es gibt aber absolut keine Garantie
der Funktionalität.
*/

define ('PHP_ClassVersion', '0.6.5 (2004.09.20)');

define ('PATH_DELIM', '/');
define ('COOKIE_NAME', 'infobasar');
define ('C_New', 'new');
define ('C_Change', 'change');
define ('C_Auto', 'auto');
define ('C_LastMode', 'last');
define ('C_Preview', 'preview');

define ('C_CHECKBOX_TRUE', 'J');

// iso-8859-1: Umlaute A O U a o u sz : 196 214 220 . 228 246 252 . 223
// Hex:  c4 d6 dc f6 e4 fc df
// Character lists: (Buchstabenlisten für Wiki-Namen)
define ('CL_WikiName_Uppercase', '_A-Z\xc4\xd6\xdc');
define ('CL_WikiName_Lowercase', '_a-z0-9\xe4\xf6\xfc\xdf');
define ('CL_WikiName', '_A-Za-z0-9\xc4\xd6\xdc\xe4\xf6\xfc\xdf');
# Umlaute: ÄÖÜ: \xc4\xd6\xdc äöü: \xe4\xf6\xfc ß: \xdf

// Character classes: (Buchstabenklassen für Wiki-Namen)
define ('CC_WikiName_Uppercase', '[' . CL_WikiName_Uppercase . ']');
define ('CC_WikiName_Lowercase', '[' . CL_WikiName_Lowercase . ']');
define ('CC_WikiName', '[' . CL_WikiName . ']');

// Special page names:
define ('PN_SystemLog', '!log!');

// Regular Expressions:
define ('RExpr_Not_A_WikiName', '/[^' . CL_WikiName . ']/');

// Module
define ('Module_Base', 'index.php');
define ('Module_Forum', 'forum.php');

// Table names:
define ('T_Page', 'page');
define ('T_User', 'user');
define ('T_Text', 'text');
define ('T_Group', 'group');
define ('T_Param', 'param');
define ('T_Forum', 'forum');
define ('T_Posting', 'posting');
define ('T_Macro', 'macro');
define ('T_Module', 'module');

// Mime types:
define ('M_Undef', '?');
define ('M_Wiki', 'wiki');
define ('M_HTML', 'html');
define ('M_Text', 'text');

// Text types (in DB-Feldern)
define ('TT_Undef', '?');
define ('TT_Wiki', 'w');
define ('TT_HTML', 'h');
define ('TT_Text', 't');

// Themes: Index in Tabelle param: Einträge als HTML!
define ('Theme_All', 1);

define ('Param_DBScheme', 10);
define ('Param_DBBaseContent', 11);
define ('Param_DBExtensions', 12);
define ('Param_BasarName', 13);
define ('Param_UserTitles', 14);
define ('Param_BaseModule', 15);
define ('Param_ForumModule', 16);

define ('Theme_Standard', 10);

// Modulunabhängig je Design: 100-119
define ('C_MinIdForThemes', 100);
define ('Th_ThemeName', 100);
define ('Th_Header', 101);
define ('Th_CSSFile', 102);


define ('Th_StandardHeader', 111); // aus 141
define ('Th_StandardBodyStart', 112);
define ('Th_StandardBodyEnd', 113);



// Macros:
define ('Macro_Char', '[');
define ('Macro_Suffix', ']');
define ('TM_CurrentDate', '[Date]');
define ('TM_CurrentDateTime', '[DateTime]');
define ('TM_CurrentUser', '[User]');
define ('TM_PageName', '[PageName]');
define ('TM_PageLink', '[PageLink]');
define ('TM_PageTitle', '[PageTitle]');
define ('TM_PageChangedAt', '[PageChangedAt]');
define ('TM_PageChangedBy', '[PageChangedBy]');
define ('TM_Theme', '[Theme]');
define ('TM_Newline', '[Newline]');
define ('TM_BasarName', '[BasarName]');
define ('TM_RuntimePrefix', '[Runtime');
define ('TM_RuntimeSecMilli', '[RuntimeSecMilli]');
define ('TM_RuntimeSecMicro', '[RuntimeSecMicro]');

define ('TM_MacroPrefix', '[M_');
define ('TM_StandardMacroPrefix', 'S');
define ('TM_ThemeMacroPrefix', 'T');

// Benutzerspezifische Einstellungen:
define ('U_TextAreaWidth', 'TextAreaWidth');
define ('U_TextAreaHeight', 'TextAreaHeight');
define ('U_MaxHits', 'MaxHits');
define ('U_PostingsPerPage', 'PostingsPerPage');
define ('U_Theme', 'Theme');

// Trace-Klassen
define ('TC_Util1', 0x1);
define ('TC_Util2', 0x2);
define ('TC_Util3', 0x4);
define ('TC_Gui1', 0x10);
define ('TC_Gui2', 0x20);
define ('TC_Gui3', 0x40);
define ('TC_Db1', 0x100);
define ('TC_Db2', 0x200);
define ('TC_Db3', 0x400);
define ('TC_Update', 0x1000);
define ('TC_Insert', 0x2000);
define ('TC_Query', 0x4000);
define ('TC_Convert', 0x8000);
define ('TC_Init', 0x10000);
define ('TC_X', 0x20000);
define ('TC_Formating', 0x40000);
define ('TC_Session1', 0x1000000);
define ('TC_Session2', 0x2000000);
define ('TC_Session3', 0x4000000);
define ('TC_Layout1', 0x10000000);
define ('TC_Layout2', 0x20000000);
define ('TC_Layout3', 0x40000000);

define ('TC_Error', 0x8);
define ('TC_Warning', 0x80);
define ('TC_Diff1', 0x800);
define ('TC_Diff2', 0x80000);
define ('TC_Diff3', 0x800000);

define ('TC_All', 0x7fffffff);
define ('PREFIX_Warning', 'InfoBasar: Warnung: ');
define ('PREFIX_Error', 'InfoBasar: Fehler: ');

define ('R_None', '/');
define ('R_New', '+');
define ('R_Put', '=');
define ('R_Get', '?');
define ('R_Del', '-');
define ('R_Lock', '#');
define ('R_KindSequence', '[-+=?#]*');
define ('R_Posting', 'post');
define ('R_Wiki', 'wiki');
define ('R_User', 'user');
define ('R_Rights', 'right');

// Features:
define ('FEATURE_UPLOAD_ALLOWED', 1);

// Alignments:
define ('AL_None', null);
define ('AL_Right', 'Right');
define ('Al_Left', 'left');
define ('AL_Justify', 'justify');
define ('AL_Center', 'center');
define ('AL_Top', 'top');
define ('AL_Bottom', 'bottom');

class Session {
	// var, wenn protected nicht geht
	var $fDbType; // MySQL
	var $fDbServer;
	var $fDbUser;
	var $fDbPassw;
	var $fDbName;
	var $fDbInfo; // MySQL: Connection-Handle
	var $fDbConnection; // MySQL: = $fDbInfo
	var $fDbTablePrefix; // Vorspann bei Tabellennamen
	var $fDbResult; // MySQL: Handle für Abfragen über mehrere Datensätze

	var $fUserId; // in Datenbank
	var $fUserName;
	var $fUserRights; // mit : getrennte Liste
	// Berechtigung: <bereich><rechte>
	// Bereich: user rights posting pages
	// Recht: + - = ? # (new, del, put, get, lock)
	// Bsp: user+=?# rights +- posting+? pages+?
	var $fUserTheme;
	var $fUserTextareaWidth;
	var $fUserTextareaHeight;
	var $fUserMaxHits;
	var $fUserPostingsPerPage;
	var $fUserThreadsPerPage;
	var $fUserStartPage;

	var $fPageName;
	var $fPageChangedAt;
	var $fPageChangedBy;
	var $fPageTitle;

	var $fMacroBasarName;
	var $fMacroScriptBase;
	var $fMacroStandard;
	var $fMacroTheme;
	var $fMacroReplacementKeys; // Liste aller Macros als RegExpr
	var $fMacroReplacementValues; // Ersatzstrings (mit addslashes()))
	
	var $fParamBase; // Basis-Nummer des Moduls in der Param-Tabelle.

	var $fOutputState; // Init Header Body
	var $fFormExists; // true: Es gab schon ein <form> im Text.

	var $fLocation; // null oder effektive URL (ab Script). Bsp: StartSeite
	var $fScriptURL; // Ohne / am Ende. Bsp: //localhost/index.php
	var $fPageURL; // ab ScriptURL Bsp: !home
	var $fScriptBase; // ohne *.php
	var $fScriptFile; // Relativ zu DocumentRoot
	var $fFileSystemBase; // Absolutpfad im Filesystem des Servers
	var $fHasHeader; // true: DOCTYPE und <html> ist ausgegeben
	var $fHasBody; // true: <body> ist ausgegeben
	var $fBodyLines; // null oder auszugebendes HTML (in guiHeader())

	var $fTraceFlags;
	var $fPreformated;

	var $fGroups; // array: gid => ",uid1,uid2,...uidX,";
	var $fVersion; // php-Version
	var $fModuleData; // array "plugin-name" => objekt
	var $fStartTime; // 

	var $fModules; // array: module_names => Plugin-Klasse (Module<Name>)
	var $fLogPageId; // Id der Seite SystemLog
	
	var $fFeatureList; // Bit-Liste der Eigenschaften (F_...)
	
	var $fTraceInFile; // true: trace() schreibt in Datei (statt in HTML-Ausgabe).
	var $fTraceFile; // null oder Datei, in das der Ablauftrace geschrieben wird.	
	function Session ($start_time){
		global $_SERVER;
		global $db_type, $db_server, $db_user, $db_passw, $db_name, $db_prefix;
		$this->fTraceFlags = 0;
		$this->fStartTime = getMicroTime ($this, $start_time);
		$this->fHasHeader = false;
		$this->fHasBody = false; // wird in guiHeader() gesetzt.
		$this->fOutputState = 'Init';
		$this->fTraceFlags = 0;
		$this->fVersion = 400;
		$this->fModuleData = array ();
		$this->fBodyLines = null;
		$this->fPreformated = false;
		$this->fLocation = "";
		$this->fFormExists = false;
		$this->fPageChangedAt = "";
		$this->fPageChangedBy = "";
		$this->fPageTitle = "";
		$this->fUserTheme = Theme_Standard;
		$this->fLogPageId = null;
		$this->fFeatureList = -1; // Alle Eigenschaften
		// Basisverzeichnis relativ zu html_root
		$uri = $_SERVER['REQUEST_URI'];
		$this->fScriptURL = $uri;
		$pos = strpos ($uri, ".php");
		if ($pos <= 0){
			if (strpos ($uri, ".php") > 0)
				$this->fScriptURL = $uri;
			else
				$this->fScriptURL = $uri . "/index.php";
			$this->fPageURL = '';
		} else {
			$this->fScriptURL = substr ($uri, 0, $pos + 4);
			if ($pos + 5 < strlen ($uri)){
				$this->fPageURL = substr ($uri, $pos + 5);
				if ( ($pos = strpos ($this->fPageURL, '/', 1)) > 0)
					$this->fScriptURL = substr ($this->fPageURL, 0, $pos);
			}
		}
		$this->fPageName = $this->fPageURL;
		$this->fScriptFile = $_SERVER['SCRIPT_FILENAME'];
		$this->fScriptBase = preg_replace ('/\/\w+\.php.*$/', '', $_SERVER['PHP_SELF']);
		$this->fFileSystemBase =  preg_replace ('/\/\w+\.php.*$/', '', $this->fScriptFile);
	
		// MySQL
		if ($db_type == 'MySQL') {
			// MySQL server host:
			$this->setDb ($db_type, $db_server, $db_name, $db_user, $db_passw, $db_prefix);
		} // mysql
		$this->fTraceFile = "/tmp/trace.log";
		#$this->fTraceFile = null;
		$this->fTraceFlags
			= 0 * (1 * TC_Util1 + 0 * TC_Util2 + 0 * TC_Util1)
			+ 1 * (1 * TC_Gui1 + 0 * TC_Gui2 + 0 * TC_Gui3)
			+ 1 * (1 * TC_Db1 + 1 * TC_Db2 + 0 * TC_Db3)
			+ 0 * (1 * TC_Session1 + 0 * TC_Session2 + 1 * TC_Session3) 
			+ 0 * TC_Layout1
			+ 0 * (1 * TC_Update + 1 * TC_Insert + 1 * TC_Query)
			+ 1 * (0 * TC_Convert + 1 * TC_Init + 0 * TC_Diff2)
			+ TC_Error + TC_Warning + TC_X;
		$this->fTraceFlags = TC_Error + TC_Warning + TC_X;
		#$this->fTraceFlags = TC_All;
		$this->fModules = null;
		$this->fTraceInFile = false;
		$this->fTraceFile = "/tmp/trace.log";
		$this->trace (TC_Init, "Session: fScriptURL: '" . $this->fScriptURL . "' Page: '" 
			. $this->fPageURL . "' ($pos) <== '" . $uri . "'");
	}
	function traceInFile($msg){
		if ($this->fTraceFile != null && ($file = fopen ($this->fTraceFile, "a")) != null){
			fwrite ($file, $msg . "\n");
			fclose ($file);
		}
	}
	function trace($class, $msg){
		if (($class & $this->fTraceFlags) != 0)
			if ($this->fTraceInFile)
				$this->traceInFile ($msg);
			else
				$this->WriteLine (htmlentities ($msg));
	}
	function dumpVars($header){
		$this->Write ("HTTP_POST_VARS: $header");
		foreach ($_POST as $name => $val){
			$this->Write ($name . ": " . textToHtml ($val) . "<br>");
		}
	}
	function traceArray ($class, $msg, $array){
		if (($class & $this->fTraceFlags) != 0){
			$this->trace ($class, $msg);
			print_r ($array);
		}
	}
	function Write ($line){
		if ($this->fHasBody)
			echo $line;
		else {
			if (!$this->fBodyLines)
				$this->fBodyLines = array ();
			array_push ($this->fBodyLines, $line);
		}
	}
	function WriteLine ($line){
		$this->Write ($line . ($this->fPreformated ? "\n" : "<br/>\n"));
	}
	function setFeatureList ($list) {
		$this->fFeatureList = $list;
	}
	function testFeature($feature){
		return $this->fFeatureList & $feature != 0;
	}
	function SetHasBody(){
		$this->fHasBody = true;
	}
	function SetLocation($location){
		$this->trace (TC_Session1, 'setLocation: ' . $location);
		$this->fLocation = $location;
	}
	function getLogPageId(){
		$this->trace (TC_Session1, 'getLogPageId');
		if ($this->fLogPageId == null){
			$this->fLogPageId = dbPageId ($this, PN_SystemLog);
			if ($this->fLogPageId <= 0)
				$this->fLogPageId = dbInsert ($this, T_Page,
					'name,type,createdat,changedat,readgroup,writegroup',
					dbSqlString ($this, PN_SystemLog) 
					. ',' . dbSqlString ($this, TT_Text)
					. ',now(),now(),0,0');
		}
		return $this->fLogPageId;
	}
	function PutHeader(){
		global $_SERVER;
		if (!$this->fHasHeader){
			if ($this->fLocation){
				$uri = $this->fScriptURL . "/" . $this->fLocation;
				$this->traceInFile ("PutHeader: Location: " . $this->fLocation
					. " uri: " . $uri . " REQ_URI: " . $_SERVER['REQUEST_URI']);
				if ($uri != $_SERVER['REQUEST_URI'])
					header ('Location: http://' . $_SERVER['HTTP_HOST'] . $uri);
			}
			echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">';
			echo "\n<html>\n";
			$this->fHasHeader = true;
		}
	}
	function startCode() {
		$this->trace (TC_Session1, 'startCode');
		if ($this->fPreformated)
			$this->trace (TC_Warning, PREFIX_Warning . 'Verschachtelung von [code]');
		else {
			echo '<pre>';
			$this->fPreformated = true;
		}
	}
	function finishCode() {
		$this->trace (TC_Session1, 'finishCode');
		if (! $this->fPreformated)
			$this->trace (TC_Warning, PREFIX_Warning . '[/code] ohne [code]');
		else {
			echo "</pre>\n";
			$this->fPreformated = false;
		}
	}
	function dump ($msg) {
		echo "Dump von Config aus " . $msg
			. ": DBName: " . $this->fDbName
			. " DBUser: " . $this->fDbUser
			. "<br />\n";
	}
	function backTrace ($message) {
		$list = debug_backtrace ();
		foreach ($list as $no => $entry)
			if ($no > 0)
				$this->trace (TC_All, $entry ['file'] . "-" . $entry ['line'] . ': '
					. $entry ['function'] . ($message ? $message : ""));
	}
	function setDb($dbtype, $server, $db, $user, $passw, $prefix) {
		$this->fDbType = $dbtype;
		$this->fDbServer = $server;
		$this->fDbUser = $user;
		$this->fDbPassw = $passw;
		$this->fDbName = $db;
		$this->fDbTablePrefix = $prefix;
	}
	function setDbConnectionInfo ($connection, $info) {
		$this->fDbInfo = $info; $this->fDbConnection = $connection;
	}
	function setUserData ($id, $name, $theme, $width, $height,
		$maxhits, $postingsperpage, $threadsperpage, $startpage) {
		$this->trace (TC_Session1, "setUserData: $id, $name, $theme, $startpage w=$width h=$height");
		$this->fUserId = $id;
		$this->fUserName = $name;
		$this->fUserRights = null; 
		$this->fUserTheme = $theme;
		$this->fUserTextareaWidth = $width; $this->fUserTextareaHeight = $height;
		$this->fUserMaxHits = $maxhits;
		$this->fUserPostingsPerPage = $postingsperpage <= 1
			? 10 : $postingsperpage;
		$this->fUserThreadsPerPage = $threadsperpage < 2 ? 25 : $threadsperpage;
		$this->fUserStartPage = $startpage;
	}
	function setFormExists($value){ $this->fFormExists = $value; }
	function setPageName ($uri){
		global $last_pagename;
		$this->trace (TC_Init, 'setPageName: ' . $uri
			. " last_pagename: ($last_pagename)");
		$this->fPageName = decodeWikiName ($this,
			preg_replace ('/\?.*$/', '', $uri));
		if (strpos ($this->fPageName, '.php') > 0){
			$this->fPageName = empty ($last_pagename)
				? P_Undef : $last_pagename;
			if (! empty ($last_pagename))
				$this->setLocation (encodeWikiName ($this, $last_pagename));
		}
		$this->fPageTitle = $this->fPageName;
		$this->trace (TC_Init, 'setPageName: PageName: ' . $this->fPageName . '<br>');
	}
	function setPageTitle ($title) {
		$this->trace (TC_Session1, 'setPageTitle: ' . $title);
		$this->fPageTitle = $title;
	}
	function setMacros () {
		$this->trace (TC_Init, 'setMacros');
		if ( ($this->fMacroBasarName = dbGetParam ($this, Theme_All,
				Param_BasarName)) == '')
			$this->fMacroBasarName = 'InfoBasar';
		$this->fMacroScriptBase = dbGetParam ($this, Theme_All, Param_BaseModule);
		dbReadMacros ($this, Theme_All, TM_MacroPrefix . TM_StandardMacroPrefix,
			$this->fMacroReplacementKeys = array (),
			$this->fMacroReplacementValues = array ());
		dbReadMacros ($this, $this->fUserTheme, 
			TM_MacroPrefix . TM_ThemeMacroPrefix,
			$this->fMacroReplacementKeys,
				$this->fMacroReplacementValues);
		$this->trace (TC_Init, 'setMacros: ' . count ($this->fMacroReplacementKeys));
		for ($ii = 0; $ii < count ($this->fMacroReplacementKeys); $ii++)
			$this->trace (TC_Session3, $this->fMacroReplacementKeys [$ii] . ' -> ' . ($this->fMacroReplacementValues [$ii]));
	}
	function setDbResult ($result) { $this->fDbResult = $result; }
	function hasRight ($area, $kind){
		$rc = $this->fUserRights == ':all:'
			|| preg_match ('/' . $area . R_KindSequence . '[' . $kind . ']/', $this->fUserRights);
		$this->trace (TC_Session2, 'hasRight: ' . $area . $kind . ': ' . $rc);
		return $rc;
	}
	function isMember ($group, $user) {
		return ($rc = strpos ($this->fGroups[$group], ',' . $user . ',')) >= 0
			&& is_int ($rc);
	}
	function fullPath($node, $with_trailing_delim = false){
		$rc = $this->fFileSystemBase . PATH_DELIM . $node;
		if ($with_trailing_delim)
			$rc .= PATH_DELIM;
		return $rc;
	}
	function setPageData ($name, $date, $by) {
		$this->fPageName = $name;
		if ($date != null){
			$this->fPageChangedAt = dbSqlDateToText ($this, $date);
			$this->fPageChangedBy = $by;
		}
	}
	function replaceMacrosNoHTML ($text){
		$this->trace (TC_Session2, 'replaceMacrosNoHTML: ' . $text);
		$count = 0;
		$again = ($pos = strpos ($text, Macro_Char)) >= 0 && is_int ($pos);
;
		$this->trace (TC_Session3, 'replaceMacrosNoHTML-2: ' . (0+$again));
		while ($again) {
			$again = false;
			if ( ($pos = strpos ($text, TM_MacroPrefix)) >= 0 && is_int ($pos)) {
				$text2 = preg_replace ($this->fMacroReplacementKeys,
					$this->fMacroReplacementValues, $text);
				$this->trace (TC_Session3, 'replaceMacrosNoHTML-3: ' . $text . ' --> ' . $text2);
				$text = $text2;
				if (++$count < 6) 
					$again = true;
				else {
					$macroname = substr ($text, $pos, 20);
					$this->trace (TC_Error, 'replaceMacrosNoHTML: zu verschachtelt: Pos: ' . $pos . " Macro: $macroname  $text");
					break;
				}
			}
			$text = str_replace (TM_CurrentUser, htmlentities ($this->fUserName), $text);
			$text = str_replace (TM_CurrentDate, strftime ('%Y.%m.%d'), $text);
			$text = str_replace (TM_CurrentDateTime, strftime ('%Y.%m.%d %H:%M'), $text);
			$text = str_replace (TM_PageName, $this->fPageName, $text);
			$text = str_replace (TM_PageLink, 
				encodeWikiName ($this, $this->fPageName), $text);
			$text = str_replace (TM_PageTitle, $this->fPageTitle, $text);
			$text = str_replace (TM_BasarName, $this->fMacroBasarName, $text);
			$text = str_replace (TM_PageChangedAt, $this->fPageChangedAt, $text);
			if ( ($pos = strpos ($text, TM_RuntimePrefix)) >= 0 && is_int ($pos)) {
				$time =  getMicroTime ($this) - $this->fStartTime;
				$pos = strpos ($time, '.');
				$text = str_replace (TM_RuntimeSecMilli, substr ($time, 0, $pos + 4), $text);
				$text = str_replace (TM_RuntimeSecMicro, substr ($time, 0, $pos + 4), $text);
			}
			$text = str_replace (TM_PageChangedBy, htmlentities ($this->fPageChangedBy),
					$text);		
		}
		#$this->trace (TC_Session2, 'replaceMacrosNoHTML-e: ' . $text);
		return $text;
	}
	function replaceMacrosHTML ($text){
		if ( ($pos = strpos ($text, Macro_Char)) >= 0 && is_int ($pos)){
			$text = str_replace (TM_Newline, "<br/>\n", $text);
			$text = preg_replace ('!(\[/?(big|small))\]!', '<$1>', $text);
		}
		return $text;
	}
}
class LayoutStatus {
	var $fSession;
	var $fEmphasisStack;	// String: [ubi]* ; underline bold italic
	var $fIndentLevel;
	var $fOrderedListLevel;
	var $fUListLevel;
	var $fOpenTable;
	var $fOpenParagraph;
	var $fTableWidth;
	var $fTableBorder;
	function LayoutStatus ($session) {
		$this->fSession = $session;
		$this->fEmphasisStack = "";
		$this->fIndentLevel = $this->fUListLevel = $this->fOrderedListLevel = 0;
		$this->fOpenTable = $this->fOpenParagraph = $this->fPreformated = false;
		$this->fTableBorder = 1;
		$this->fTableWidth = '100%';
	}
	function pushEmphasis ($type) {
		$this->fEmphasisStack .= $type;
		if ($type == 'x')
			echo '<b><i>';
		else
			echo '<' . $type . '>';
	}
	function popEmphasis ($type) {
		$this->fSession->trace (TC_Layout2 + TC_Formating,
			"popEmphasis-$type: $this->fEmphasisStack.");
		$pos = strrpos ($this->fEmphasisStack, $type);
		if (is_int ($pos)) {
			for ($ii = strlen ($this->fEmphasisStack) - 1; $ii >= $pos; $ii--) {
				$type = substr ($this->fEmphasisStack, $ii, 1);
				echo $type == 'x' ? '</i></b>' :  "</$type>";
			}
			$this->fEmphasisStack = substr ($this->fEmphasisStack, 0, $pos);
		}
	}
	function handleEmphasis ($type) {
		$this->fSession->trace (TC_Layout2 + TC_Formating, 'handleEmphasis');
		if ( ($pos = strpos ($this->fEmphasisStack, $type)) >= 0 && is_int ($pos))
			$this->popEmphasis ($type);
		else
			$this->pushEmphasis ($type);
	}
	function startTable (){
		$this->fSession->trace (TC_Layout2 + TC_Formating, 'handleEmphasis');
		if (! $this->fOpenTable) {
			$this->stopSentence();
			$this->fOpenTable = true;
			echo "<table border=\"$this->fTableBorder\" width=\"$this->fTableWidth\">\n";
		}
	}
	function startParagraph (){
		$this->fSession->trace (TC_Layout1 + TC_Formating, 'startParagraph');
		if (! $this->fOpenParagraph) {
			$this->fOpenParagraph = true;
			echo '<p>';
		}
	}
	function stopSentence () {
		$this->fSession->trace (TC_Layout1 + TC_Formating, 'stopSentence');
		while ($this->fEmphasisStack != "")
			$this->popEmphasis ($this->topEmphasis ());
	}
	function topEmphasis () {
		$this->fSession->trace (TC_Layout2 + TC_Formating, 'topEmphasis');
		return substr ($this->fEmphasisStack, strlen ($this->fEmphasisStack) - 1);
	}
	function changeListLevel ($val, &$level, $tag) {
		$this->fSession->trace (TC_Layout3 + TC_Formating, "changeListLevel: $val.$level.$tag");
		$this->stopSentence ();
		if ($val < $level) {
			while ($val < $level) {
				echo "</$tag>";
				$level--;
			}
		} else while ($val > $level) {
			echo "<$tag>";
			$level++;
		}
	}
	function changeUListLevel ($val) {
		$this->fSession->trace (TC_Layout2 + TC_Formating, "changeUListLevel");
		$this->changeListLevel ($val, $this->fUListLevel, 'ulist');
	}
	function changeOrderedListLevel ($val) {
		$this->fSession->trace (TC_Layout2 + TC_Formating, "changeOrderedListLevel");
		$this->changeListLevel ($val, $this->fOrderedListLevel, 'ol');
	}
	function stopTable () {
		if ($this->fOpenTable){
			$this->fOpenTable = false;
			echo "</table>\n";
		}
	}
	function stopParagraph () {
		$this->fSession->trace (TC_Layout1 + TC_Formating, "stopParagraph");
		$this->changeUListLevel (0);
		$this->changeOrderedListLevel (0);
		$this->stopTable();
		if ($this->fOpenParagraph) {
			$this->fOpenParagraph = false;
			echo "</p>\n";
		}
	}
	function trace ($class, $msg) {
		if (($class & $this->fSession->fTraceFlags) != 0)
			$this->fSession->trace ($class, $msg);
	}
}
class DiffEngine {
	var $fIx1;
	var $fIx2;
	var $fLines1;
	var $fLines2;
	var $fSession;

	function DiffEngine (&$session, $lines1, $lines2) {
		$this->fSession = $session;
		$this->fLines1 = explode ("\n", $lines1);
		$this->fLines2 = explode ("\n", $lines2);
		$this->fIx1 = $this->fIx2 = 0;
	}
	function addLines ($ix2, $count) {
		echo "<pre>\n";
		for ($ii = 0; $ii < $count; $ii++) {
			echo '+ ' . ($ix2 + $ii + 1) . ': ' . $this->fLines2 [$ix2 + $ii]
				. "\n";
		}
		echo "</pre>\n";
	}
	function delLines ($ix1, $count) {
		echo "<pre>\n";
		for ($ii = 0; $ii < $count; $ii++) {
			echo '- ' . ($ix1 + $ii + 1) . ': ' . $this->fLines1 [$ix1 + $ii]
				. "\n";
		}
		echo "</pre>\n";
	}
	function differentRange ($ix1, $count1, $ix2, $count2) {
		echo '<p>Bereich ' . (1 + $ix1) . '-' . ($ix1 + $count1)
			. ' / ' . (1 + $ix2) . '-' . ($ix2 + $count2) . ' verschieden</p>';
		$this->delLines ($ix1, $count1);
		$this->addLines ($ix2, $count2);
	}
	function equalLines ($ix1, $ix2, $count) {
		echo '<p>Es sind ' . ($count+0) . ' Zeile(n) gleich</p>';
	}
	function skipEqual ($ix1, $ix2) {
		$this->fSession->trace (TC_Diff3, 'skipEqual');
		$count = 0;
		while ($ix1 < count ($this->fLines1) && $ix2 < count ($this->fLines2)
			&& $this->fLines1 [$ix1] == $this->fLines2 [$ix2]) {
			$ix1++; $ix2++; $count++;
		}
		$this->fSession->trace (TC_Diff3, 'skipEqual-' . $count);
		return $count;
	}
	function skipBlankLines (&$ix1, &$ix2) {
		$this->fSession->trace (TC_Diff3, 'skipBlankLines');
		$count1 = 0;
		while (preg_match ('/^\s*$/', $this->fLines1 [$ix1])) {
			$count1++;
			if (++$ix1 == count ($this->fLines1))
				break;
		}
		if ($count1 > 0)
			$this->delLines ($ix1 - $count1, $count1);
		$count = 0;
		while (preg_match ('/^\s*$/', $this->fLines2 [$ix2])) {
			$count++;
			if (++$ix2 == count ($this->fLines2))
				break;
		}
		if ($count > 0)
			$this->addLines ($ix2 - $count, $count);
		$this->fSession->trace (TC_Diff3, 'skipBlankLines-' . $count1 . '/' . $count);
		return $count1 + $count > 0;
	}
	function findAnchor ($line, $ix, &$lines, &$count) {
		$size = count ($lines);
		$this->fSession->trace (TC_Diff2, 'findAnchor' . ": $ix, $size, $line" . ', ' . $lines [$ix]);
		$count = 1;
		while ($ix < $size && ($rc = strcmp ($line, $lines [$ix])) != 0) {
			$ix++;
			$count++;
		}
		$this->fSession->trace (TC_Diff2, 'findAnchor-' . ($ix < $size ? 't': 'f') . " $count/$ix/$rc");
		return $ix < $size;
	}
	function findBothAnchors (&$ix1, &$ix2) {
		$this->fSession->trace (TC_Diff2, 'findBothAnchors');
		$start1 = $ix1;
		$start2 = $ix2;
		$size1 = count ($this->fLines1);
		$size2 = count ($this->fLines2);
		$count = 0;
		while (true) {
			if (++$ix1 >= $size1 || ++$ix2 >= $size2) {
				$this->differentRange ($start1, $size1 - $start1, $start2, $size2 - $start2);
				break;
			}
			$count++;
			$found1 = $this->findAnchor ($this->fLines1 [$ix1], $ix2,
				$this->fLines2, $count1);
			$found2 = $this->findAnchor ($this->fLines2 [$ix2], $ix1,
				$this->fLines1, $count2);
			$found = $found1 && $found2;
			if ($found1 && ! $found2 || $found && $count1 <= $count2) {
				$count1--;
				$this->differentRange ($start1, $count, $start2,
					$count + $count1);
				$ix2 += $count1;
				break;
			} elseif (! $found1 && $found2 || $found && $count1 > $count2) {
				$count2--;
				$this->differentRange ($start1, $count + $count2, $start2,
					$count);
				$ix1 += $count2;
				break;
			}
		}
		$this->fSession->trace (TC_Diff2, 'findBothAnchors-Ende');
	}
	function compare ($ix1, $ix2) {
		$size1 = count ($this->fLines1);
		$size2 = count ($this->fLines2);
		$ix1 = $ix2 = 0;
		while (true) {
			$count = $this->skipEqual ($ix1, $ix2);
			if ($count > 0) {
				$this->equalLines ($ix1, $ix2, $count);
				$ix1 += $count;
				$ix2 += $count;
			}
			if ($ix1 >= $size1 || $ix2 >= $size2)
				break;
			if ($this->skipBlankLines ($ix1, $ix2))
				continue;
			if ($ix1 >= $size1 || $ix2 >= $size2)
				break;
			$found1 = $this->findAnchor ($this->fLines1 [$ix1], $ix2,
				$this->fLines2, $count1);
			$found2 = $this->findAnchor ($this->fLines2 [$ix2], $ix1,
				$this->fLines1, $count2);
			$found = $found1 && $found2;
			if ($found1 && ! $found2 || $found && $count1 <= $count2) {
				$this->addLines ($ix2, $count1);
				$ix2 += $count1;
			} elseif (! $found1 && $found2 || $found && $count1 > $count2) {
				$this->delLines ($ix1, $count2);
				$ix1 += $count2;
			} else {
				$this->findBothAnchors ($ix1, $ix2);
			}
		}
	}
}
include "util.php";
include "gui.php";

if ($db_type == 'MySQL') {
	include "db_mysql.php";
}

?>
