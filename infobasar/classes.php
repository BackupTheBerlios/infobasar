<?php
// classes.php: constants and classes
// $Id: classes.php,v 1.29 2005/01/11 01:45:03 hamatoma Exp $
/*
Diese Datei ist Teil von InfoBasar.
Copyright 2004 hamatoma@gmx.de München
InfoBasar ist freie Software. Du kannst es weitergeben oder verändern
unter den Bedingungen der GNU General Public Licence.
Näheres siehe Datei LICENCE.
InfoBasar sollte nützlich sein, es gibt aber absolut keine Garantie
der Funktionalität.
*/

define ('PHP_ClassVersion', '0.7.0 (2005.01.03)');

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

// Standard page names:
define ('P_Home', '!home');
define ('P_Login', '!login');
define ('P_Logout', '!logout');

// Special page names:
define ('PN_SystemLog', '!log!');

// Regular Expressions:
define ('RExpr_Not_A_WikiName', '/[^' . CL_WikiName . ']/');

// Module
define ('Module_Base', 'index.php');
define ('Module_Forum', 'forum.php');

// DB-Typen:
define ('DB_MySQL', 'MySQL');

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

// Parameter zur Oberflaechengestaltung:
define ('C_ParamGUIBase', 100);

// Modul- und Design-unabhängig: 100-149
define ('C_MinAllModulesAllDesigns', 100);
define ('C_MaxAllModulesAllDesigns', 149);

define ('Th_StandardHeader', 101);
define ('Th_StandardBodyStart', 102);
define ('Th_StandardBodyEnd', 103);
define ('Th_LoginHeader', 105);
define ('Th_LoginBodyEnd', 106);


// Modulunabhängig je Design: 150-199
define ('C_MinIdForThemes', 150); // von 100
define ('Th_ThemeName', 150);
define ('Th_Header', 151);
define ('Th_CSSFile', 152);


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
define ('TM_Newline', '[newline]');
define ('TM_BasarName', '[BasarName]');
define ('TM_RuntimePrefix', '[Runtime');
define ('TM_RuntimeSecMilli', '[RuntimeSecMilli]');
define ('TM_RuntimeSecMicro', '[RuntimeSecMicro]');

define ('TM_MacroPrefix', '[M_');
define ('TM_StandardMacroPrefix', 'S');
define ('TM_ThemeMacroPrefix', 'T');

// Benutzerspezifische Einstellungen:
define ('U_TextAreaWidth', 'textarea_width');
define ('U_TextAreaHeight', 'textarea_height');
define ('U_MaxHits', 'search_maxhits');
define ('U_PostingsPerPage', 'posting_perpage');
define ('U_Theme', 'user_theme');

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
define ('FEATURE_SIMPLE_USER_MANAGEMENT', 2); // Jeder darf Konten anlegen/aendern
define ('FEATURE_SECURE_LOGOUT', 4);

// Alignments:
define ('AL_None', null);
define ('AL_Right', 'Right');
define ('Al_Left', 'left');
define ('AL_Justify', 'justify');
define ('AL_Center', 'center');
define ('AL_Top', 'top');
define ('AL_Bottom', 'bottom');

// HTML-Tags:
define ('TAG_ANCOR_END', "</a>\n");	
define ('TAG_ANCOR_HREF', '<a href="');	
define ('TAG_APO', '"');
define ('TAG_APO_SUFFIX', '">');
define ('TAG_APO_SUFFIX_NEWLINE', "\">\n");
define ('TAG_BODY_END',  '</body>');
define ('TAG_BODY_H1', '<body><h1>');
define ('TAG_BODY_HTML_END', "\n</body></html>");
define ('TAG_BOLD_ITALIC', '<b><i>');
define ('TAG_CITE', '<cite>');
define ('TAG_CITE_END', '</cite>');
define ('TAG_DIV_INDENT', '<div style="margin-left: 40px;">');
define ('TAG_DIV_END', '</div>');
define ('TAG_DOC_TYPE', '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">');
define ('TAG_ENDPREFIX', '</'); 	
define ('TAG_FORM_END', "</form>\n");
define ('TAG_FORM_MULTIPART_POST_ACTION', '<form enctype="multipart/form-data" " method="post" action="');
define ('TAG_FORM_POST_ACTION', '<form method="post" action="');
define ('TAG_H1_END', "</h1>\n");
define ('TAG_H1', '<h1>');
define ('TAG_H2_END', "</h2>\n");
define ('TAG_H2', '<h2>');
define ('TAG_HEAD_END', '</head>');
define ('TAG_HEAD', '<head>');
define ('TAG_HEAD_TITLE', '<head><title>');
define ('TAG_HRULE_HEIGHT', '<hr style="width: 100%; height: ');
define ('TAG_HRULE', '<hr>');
define ('TAG_HTML', "\n<html>");
define ('TAG_HTML_BODY', "\n<html>\n<body>");
define ('TAG_IMAGE_ALT', '<img alt="');
define ('TAG_IMAGE_SOURCE', '" src="');
define ('TAG_IMAGE_TITLE', '" title="');
define ('TAG_INPUT_FILE_NAME', '<input type="file" name="');
define ('TAG_INPUT_TYPE', '<input type=');
define ('TAG_INPUT_WIKIACTION_NAME', '<input class="wikiaction" name="');
define ('TAG_ITALIC_BOLD_END', '</i></b>');
define ('TAG_LINK_STYLESHEET', '<link rel="stylesheet" type="text/css" href="');
define ('TAG_LISTITEM', '<li>');
define ('TAG_LISTITEM_END', "</li>\n");
define ('TAG_LISTITEM_END_LISTITEM', "</li>\n<li>");
define ('TAG_META', "<meta http-equiv=\"content-type\" content=\"text/html; charset=ISO-8859-1\">\n");
define ('TAG_NEWLINE', "<br/>\n");
define ('TAG_OPTION', '<option');
define ('TAG_PARAGRAPH_END', "</p>\n");
define ('TAG_PARAGRAPH', '<p>');
define ('TAG_PRE_END', "</pre>\n");
define ('TAG_PREFIX', '<'); 	
define ('TAG_PRE', "<pre>\n");
define ('TAG_REPLACEMENT_MATCH1',  '<$1>');	
define ('TAG_REXPR_BIG_SMALL', '!(\[/?(big|small))\]!');
define ('TAG_REXPR_BODY_END', '/<\s*\/\s*body\s*>.*$/si');
define ('TAG_REXPR_BODY', '/^.*<\s*body\s*>/si');
define ('TAG_SELECT_END', "</select>\n");
define ('TAG_SELECT_NAME', '<select name="');
define ('TAG_STRONG_END', '</strong>');
define ('TAG_STRONG', '<strong>');
define ('TAG_SUB', '<sub>');
define ('TAG_SUP', '<sup>');
define ('TAG_SUB_END', '</sub>');
define ('TAG_SUP_END', '</sup>');
define ('TAG_SUFFIX', '>'); 	
define ('TAG_SUFFIX_NEWLINE', ">\n"); 	
define ('TAG_TABLE_BOLD_DELIM_END', '</b></td>');
define ('TAG_TABLE_DELIM_ALIGN', '<td text-align: ');
define ('TAG_TABLE_DELIM_BOLD', '<td><b>');
define ('TAG_TABLE_DELIM_END_DELIM', '</td><td>');
define ('TAG_TABLE_DELIM_END', '</td>');
define ('TAG_TABLE_DELIM_RECORD_END', "</td></tr>\n");
define ('TAG_TABLE_DELIM', '<td>');
define ('TAG_TABLE_END', "</table>\n");
define ('TAG_TABLE_OPEN', '<table ');
define ('TAG_TABLE_RECORD_END_RECORD', "</tr>\n<tr>");
define ('TAG_TABLE_RECORD_END', "</tr>\n");
define ('TAG_TABLE_RECORD', '<tr>');
define ('TAG_TEXTAREA_END', "</textarea>\n");
define ('TAG_TEXTAREA_NAME', '<textarea name="');
define ('TAG_TT', '<tt>');
define ('TAG_TT_END', '</tt>');
define ('TAG_TITLE_END_BODY', "</title>\n</head>\n<body>\n");
define ('TAG_TITLE_HEAD_END',  "</title></head>\n");
define ('TAG_TITLE', '<title>');
define ('TAG_ULIST_END', "</ul>\n");
define ('TAG_ULIST', '<ul>');
// Tag-Names:
define ('TAGN_HEADLINE', 'h');		
define ('TAGN_LISTITEM', 'li');
define ('TAGN_OLIST', 'ol');
define ('TAGN_TABLE_DELIM', 'td');
define ('TAGN_ULIST', 'ul');		
// Tag-Attributes:
define ('TAGA_APO_COLS',  '" cols="');
define ('TAGA_APO_NAME', '" name="');
define ('TAGA_APO_ROWS', ' " rows="');
define ('TAGA_APO_SIZE_END', "\" size=\"1\">\n");
define ('TAGA_APO_VALUE', '" value="');	
define ('TAGA_APO_WIDTH',  '" width="');
define ('TAGA_BORDER_1', ' border="1"');		
define ('TAGA_BORDER', ' border="');
define ('TAGA_MAXSIZE', ' maxlength="');
define ('TAGA_NAME', '" name="');
define ('TAGA_PX_END', "px;\">\n");
define ('TAGA_SIZE', ' size="');
define ('TAGA_SUBMIT_END', '" type="submit">');	
define ('TAGA_VALUE_COLON', ' value=";');
define ('TAGA_VALUE', ' value="');	
define ('TAGA_WIDTH_100', ' width="100%"');
// Tag-Attribut-Value:
define ('TAGAV_CHECKBOX', '"checkbox"');
define ('TAGAV_CHECKED', 'checked');
define ('TAGAV_FILE', '"file"');
define ('TAGAV_HIDDEN', '"hidden"');
define ('TAGAV_PASSWORD', '"password"');
define ('TAGAV_RADIO', '"radio"');
define ('TAGAV_SELECTED', ' selected');
define ('TAGAV_TEXT', '"text"');

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

	var $fSessionId;
	var $fSessionUser;
	var $fSessionStart;
	var $fSessionNo;
	
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
	var $fScriptBase; // ohne Host, ohne *.php. Bsp: /hamatoma/wiki
	var $fScriptFile; // Relativ zu DocumentRoot
	var $fFileSystemBase; // Absolutpfad im Filesystem des Servers
	var $fHasHeader; // true: DOCTYPE und <html> ist ausgegeben
	var $fHasBody; // true: <body> ist ausgegeben
	var $fBodyLines; // null oder auszugebendes HTML (in guiHeader())

	var $fTraceFlags;

	var $fGroups; // array: gid => ",uid1,uid2,...uidX,";
	var $fVersion; // php-Version
	var $fModuleData; // array "plugin-name" => objekt
	var $fStartTime; // 

	var $fModules; // array: module_names => Plugin-Klasse (Module<Name>)
	var $fLogPageId; // Id der Seite SystemLog
	
	var $fFeatureList; // Bit-Liste der Eigenschaften (F_...)
	
	var $fTraceInFile; // true: trace() schreibt in Datei (statt in HTML-Ausgabe).
	var $fTraceFile; // null oder Datei, in das der Ablauftrace geschrieben wird.
	var $fTraceDirect; // true: sofortiges echo	
	var $fAdmins; // mit ':' getrennte Usernamen der Admins
	function Session ($start_time, $session_id, $session_user, $session_start, $session_no,
		$db_type, $db_server, $db_user, $db_passw, $db_name, $db_prefix){
		$this->fTraceFlags = 0;
		$this->fStartTime = getMicroTime ($this, $start_time);
		$this->fSessionId = $session_id;
		$this->fSessionUser = $session_user;
		$this->fSessionStart = $session_start == null ? $start_time : $session_start;
		$this->fSessionNo = $session_no == null ? 1 : $session_no + 1;
		$this->fHasHeader = false;
		$this->fHasBody = false; // wird in guiHeader() gesetzt.
		$this->fOutputState = 'Init';
		$this->fTraceFlags = 0;
		$this->fVersion = 400;
		$this->fModuleData = array ();
		$this->fBodyLines = null;
		$this->fLocation = "";
		$this->fFormExists = false;
		$this->fPageChangedAt = "";
		$this->fPageChangedBy = "";
		$this->fPageTitle = "";
		$this->fUserTheme = Theme_Standard;
		$this->fLogPageId = null;
		$this->fAdmins = ':wk:';
		$this->fFeatureList = FEATURE_UPLOAD_ALLOWED;
		; // FEATURE_UPLOAD_ALLOWED FEATURE_SIMPLE_USER_MANAGEMENT FEATURE_SECURE_LOGOUT 
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
		if ($db_type == DB_MySQL) {
			// MySQL server host:
			$this->setDb ($db_type, $db_server, $db_name, $db_user, $db_passw, $db_prefix);
		} // mysql
		$this->fTraceFile = "/tmp/trace.log";
		#$this->fTraceFile = null;
		$this->fTraceDirect = false;
		$this->fTraceFlags
			= 1 * (1 * TC_Util1 + 1 * TC_Util2 + 1 * TC_Util3)
			+ 0 * (1 * TC_Gui1 + 2 * TC_Gui2 + 0 * TC_Gui3)
			+ 0 * (1 * TC_Db1 + 1 * TC_Db2 + 0 * TC_Db3)
			+ 0 * (1 * TC_Session1 + 0 * TC_Session2 + 1 * TC_Session3) 
			+ 1 * (1 * TC_Layout1 + 1 * 1 * TC_Layout2 + 1 * 1 * TC_Layout3)
			+ 0 * (1 * TC_Update + 1 * TC_Insert + 0 * TC_Query)
			+ 1 * (0 * TC_Convert + 1 * TC_Init + 0 * TC_Diff2)
			+ TC_Error + TC_Warning + TC_X;
		$this->fTraceFlags = TC_Error + TC_Warning + TC_X;
		#$this->fTraceFlags = TC_All;
		$this->fModules = null;
		$this->fTraceInFile = false;
		$this->fTraceInFile = true;
		$this->trace (TC_Init, "TC: " . $this->fTraceFlags . " InFile: " . ($this->fTraceInFile ? 'f' : 'f'));
		$this->fTraceFile = "/tmp/trace.log";
		$this->trace (TC_Init, "Session.Session: fScriptURL: '" . $this->fScriptURL . "' Page: '" 
			. $this->fPageURL . "' ($pos) <== '" . $uri . "'");
		$this->trace (TC_Init, "Session.Session: Id: $session_id User: $session_user No: $session_no");
	}
	function storeSession (){
		global $session_user, $session_start, $session_no;
		$_SESSION ['session_user'] = $this->fSessionUser;
		$_SESSION ['session_start'] = $this->fSessionStart;
		$_SESSION ['session_no'] = $this->fSessionNo;
	}
	function setSessionNo ($no){
		$this->fSessionNo = $no;
	}
	function clearSessionData(){
		$this->fSessionUser = null;
		$this->fSessionStart = null;
		$this->fSessionNo = -99999;
		$this->fSessionId = null;
	}
	function setSessionUser ($user){
		$this->fSessionUser = $user;
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
			$this->Write ($name . ": " . textToHtml ($val) . TAG_NEWLINE);
		}
	}
	function traceArray ($class, $msg, $array){
		if (($class & $this->fTraceFlags) != 0){
			$this->trace ($class, $msg);
			print_r ($array);
		}
	}
	function Write ($line){
		if ($this->fHasBody || $this->fTraceDirect)
			echo $line;
		else {
			if (!$this->fBodyLines)
				$this->fBodyLines = array ();
			array_push ($this->fBodyLines, $line);
		}
	}
	function WriteLine ($line){
		$this->Write ($line);
		$this->Write (TAG_NEWLINE);
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
		if (!$this->fHasHeader){
			if ($this->fLocation){
				$uri = $this->fScriptURL . "/" . $this->fLocation;
				$this->traceInFile ("PutHeader: Location: " . $this->fLocation
					. " uri: " . $uri . " REQ_URI: " . $_SERVER['REQUEST_URI']);
				if ($uri != $_SERVER['REQUEST_URI'])
					header ('Location: http://' . $_SERVER['HTTP_HOST'] . $uri);
			}
			echo TAG_DOC_TYPE;
			echo TAG_HTML;
			$this->fHasHeader = true;
		}
	}
	function dump ($msg) {
		echo "Dump von Config aus " . $msg
			. ": DBName: " . $this->fDbName
			. " DBUser: " . $this->fDbUser
			. TAG_NEWLINE;
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
		$last = getPostVar ('last_pagename');
		$this->trace (TC_Init, 'setPageName: ' . $uri . ' last_pagename: (' . $last . ')');
		$this->fPageName = decodeWikiName ($this,
			preg_replace ('/\?.*$/', '', $uri));
		if (strpos ($this->fPageName, '.php') > 0){
			$this->fPageName = empty ($last) ? P_Undef : $last;
			if (! empty ($last))
				$this->setLocation (encodeWikiName ($this, $last));
		}
		$this->fPageTitle = $this->fPageName;
		$this->trace (TC_Init, 'setPageName: PageName: ' . $this->fPageName . TAG_NEWLINE);
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
		if ($this->fTraceFlags & TC_Init){
			$this->trace (TC_Init, 'setMacros-2: ' . count ($this->fMacroReplacementKeys));
			for ($ii = 0; $ii < count ($this->fMacroReplacementKeys); $ii++)
				$this->trace (TC_Session3, $this->fMacroReplacementKeys [$ii] . ' -> ' . ($this->fMacroReplacementValues [$ii]));
		}
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
					$this->trace (TC_Error, 'replaceMacrosNoHTML: zu verschachtelt: Pos: ' . $pos . " Macro: $macroname Text: $text");
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
		# if ( ($pos = strpos ($text, Macro_Char)) >= 0 && is_int ($pos)){
		#	$text = str_replace (TM_Newline, TAG_NEWLINE, $text);
		#}
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
	var $fPreformatted;
	function LayoutStatus (&$session) {
		$this->fSession = $session;
		$this->fEmphasisStack = "";
		$this->fIndentLevel = $this->fUListLevel = $this->fOrderedListLevel = 0;
		$this->fOpenTable = $this->fOpenParagraph = $this->fPreformatted = false;
		$this->fTableBorder = TAGA_BORDER_1;
		$this->fTableWidth = TAGA_WIDTH_100;
	}
	function pushEmphasis ($type) {
		$this->fEmphasisStack .= $type;
		if ($type == 'x')
			echo TAG_BOLD_ITALIC;
		else {
			echo TAG_PREFIX;
			echo $type;
			echo TAG_SUFFIX;
		}
	}
	function popEmphasis ($type) {
		$this->fSession->trace (TC_Layout2 + TC_Formating,
			"popEmphasis-$type: $this->fEmphasisStack.");
		$pos = strrpos ($this->fEmphasisStack, $type);
		if (is_int ($pos)) {
			for ($ii = strlen ($this->fEmphasisStack) - 1; $ii >= $pos; $ii--) {
				$type = substr ($this->fEmphasisStack, $ii, 1);
				echo $type == 'x' ? TAG_ITALIC_BOLD_END :  TAG_ENDPREFIX . $type . TAG_SUFFIX;
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
			echo TAG_TABLE_OPEN;
			echo $this->fTableBorder;
			echo $this->fTableWidth;
			echo TAG_SUFFIX_NEWLINE;
		}
	}
	function startParagraph (){
		$this->fSession->trace (TC_Layout1 + TC_Formating, 'startParagraph');
		if (! $this->fOpenParagraph) {
			$this->fOpenParagraph = true;
			echo TAG_PARAGRAPH;
		}
	}
	function stopSentence () {
		$this->fSession->trace (TC_Layout1 + TC_Formating, 'stopSentence: ' . ($this->fPreformatted ? 'T' : 'F'));
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
		if ($val == $level){
			echo TAG_LISTITEM_END_LISTITEM; 
		} elseif ($val < $level) {
			while ($val < $level) {
				$this->fSession->trace (TC_Layout3 + TC_Formating, "changeListLevel-1: .$tag");
				echo TAG_LISTITEM_END;
				echo TAG_ENDPREFIX;
				echo $tag;
				echo TAG_SUFFIX;
				$level--;
			}
			if ($val > 0)
				echo TAG_LISTITEM_END_LISTITEM;
		} else {
			while ($val > $level) {
				$text = TAG_PREFIX . $tag . TAG_SUFFIX . TAG_LISTITEM;
				$this->fSession->trace (TC_Layout3 + TC_Formating, "changeListLevel-2: .$text");
				if (true)
					echo $text;
				else {
				echo TAG_PREFIX;
				echo $tag;
				echo TAG_SUFFIX;
				echo TAG_LISTITEM;
				}
				$level++;
				$this->fSession->trace (TC_Layout3 + TC_Formating, "changeListLevel-2b: .$tag");
			}
		}
		$this->fSession->trace (TC_Layout3 + TC_Formating, 'changeListLevel: end');
	}
	function changeUListLevel ($val) {
		$this->fSession->trace (TC_Layout2 + TC_Formating, "changeUListLevel");
		$this->changeListLevel ($val, $this->fUListLevel, TAGN_ULIST);
	}
	function changeOrderedListLevel ($val) {
		$this->fSession->trace (TC_Layout2 + TC_Formating, "changeOrderedListLevel");
		$this->changeListLevel ($val, $this->fOrderedListLevel, TAGN_OLIST);
	}
	function changeIndentLevel ($val) {
		$this->fSession->trace (TC_Layout3 + TC_Formating, 'changeIndentLevel: ' . $val . '.' . $this->fIndentLevel);
		$this->stopSentence ();
		if ($val < $this->fIndentLevel) {
			while ($val < $this->fIndentLevel) {
				echo TAG_DIV_END;
				$this->fIndentLevel--;
			}
		} else while ($val > $this->fIndentLevel) {
			echo TAG_DIV_INDENT;
			$this->fIndentLevel++;
		}
	}
	function stopTable () {
		if ($this->fOpenTable){
			$this->fOpenTable = false;
			echo TAG_TABLE_END;
		}
	}
	function stopParagraph () {
		$this->fSession->trace (TC_Layout1 + TC_Formating, "stopParagraph");
		$this->stopTable();
	}
	function changeOfLineType (){
		$this->trace (TC_Layout1, 'changeOfLineType:');
		if ($this->fUListLevel > 0)
			$this->changeUListLevel (0);
		if ($this->fOrderedListLevel > 0)
			$this->changeOrderedListLevel (0);
		if ($this->fIndentLevel > 0)
			$this->changeIndentLevel(0);
		$this->stopTable ();
		if ($this->fOpenParagraph) {
			$this->fOpenParagraph = false;
			echo TAG_PARAGRAPH_END;
		}
	}
	function testChangeOfLineType ($oldtype, $type){
		$this->trace (TC_Layout1, "testChangeOfLineType: $oldtype $type " . ($this->fPreformatted ? 'T' : 'F'));
		if ($this->fPreformatted)
			$type = '[';
		else {
			if ($oldtype != $type)
				$this->changeOfLineType ();
		}
		return $type;
	}
	function startCode() {
		$this->trace (TC_Layout1, 'startCode');
		if ($this->fPreformatted)
			$this->trace (TC_Warning, PREFIX_Warning . 'Verschachtelung von [code]');
		else {
			echo TAG_PRE;
			$this->fPreformatted = true;
		}
		$this->trace (TC_Layout1, 'startCode: ' . ($this->fPreformatted ? 'T' : 'F'));
	}
	function finishCode() {
		$this->trace (TC_Layout1, 'finishCode');
		if (! $this->fPreformatted)
			$this->trace (TC_Warning, PREFIX_Warning . '[/code] ohne [code]');
		else {
			echo TAG_PRE_END;
			$this->fPreformatted = false;
		}
	}
	function trace ($class, $msg) {
		if (($class & $this->fSession->fTraceFlags) != 0){
			$this->fSession->trace ($class, $msg);
		}
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
		echo TAG_PRE;

		for ($ii = 0; $ii < $count; $ii++) {
			echo '+ ';
			echo  ($ix2 + $ii + 1);
			echo ': ';
			echo $this->fLines2 [$ix2 + $ii];
			echo "\n";
		}
		echo TAG_PRE_END;
	}
	function delLines ($ix1, $count) {
		echo TAG_PRE;
		for ($ii = 0; $ii < $count; $ii++) {
			echo '- ';
			echo  ($ix1 + $ii + 1);
			echo ': ';
			echo $this->fLines1 [$ix1 + $ii];
			echo  "\n";
		}
		echo TAG_PRE_END;
	}
	function differentRange ($ix1, $count1, $ix2, $count2) {
		echo TAG_PARAGRAPH;
		echo 'Bereich ';
		echo (1 + $ix1);
		echo '-';
		echo ($ix1 + $count1);
		echo ' / ';
		echo (1 + $ix2);
		echo '-';
		echo ($ix2 + $count2);
		echo ' verschieden';
		echo TAG_PARAGRAPH_END;
		$this->delLines ($ix1, $count1);
		$this->addLines ($ix2, $count2);
	}
	function equalLines ($ix1, $ix2, $count) {
		echo TAG_PARAGRAPH;
		echo 'Es sind ';
		echo ($count+0);
		echo ' Zeile(n) gleich.';
		echo TAG_PARAGRAPH_END;
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
