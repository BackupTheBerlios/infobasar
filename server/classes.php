<?php
// classes.php: constants and classes
// $Id: classes.php,v 1.3 2004/05/27 22:40:50 hamatoma Exp $
/*
Diese Datei ist Teil von InfoBasar.
Copyright 2004 hamatoma@gmx.de München
InfoBasar ist freie Software. Du kannst es weitergeben oder verändern
unter den Bedingungen der GNU General Public Licence.
Näheres siehe Datei LICENCE.
InfoBasar sollte nützlich sein, es gibt aber absolut keine Garantie
der Funktionalität.
*/

define ('PHP_Version', '0.5.1 (2004.05.27)');

define ('PATH_DELIM', '/');
define ('COOKIE_NAME', 'infobasar');
define ('C_New', 'new');
define ('C_Change', 'change');
define ('C_Auto', 'auto');
define ('C_LastMode', 'last');
define ('C_Preview', 'preview');

define ('C_CHECKBOX_TRUE', 'J');

// Actions:
define ('A_Edit', 'edit');
define ('A_NewThread', 'newthread');
define ('A_Search', 'search');
define ('A_Answer', 'answer');
define ('A_ChangeThread', 'changethread');
define ('A_ShowThread', 'showthread');
define ('A_ShowForum', 'showforum');
define ('A_PageInfo', 'pageinfo');
define ('A_ShowText', 'showtext');
define ('A_Diff', 'diff');
define ('A_Show', 'show');


// Table names:
define ('T_Page', 'page');
define ('T_User', 'user');
define ('T_Text', 'text');
define ('T_Group', 'group');
define ('T_Param', 'param');
define ('T_Forum', 'forum');
define ('T_Posting', 'posting');
define ('T_Macro', 'macro');

// Predefined pages
define ('P_Login', '!login');
define ('P_Account', '!account');
define ('P_Home', '!home');
define ('P_NewPage', '!newpage');
define ('P_ModifyPage', '!modifypage');
define ('P_Search', '!search');
define ('P_ForumSearch', '!forumsearch');
define ('P_ForumHome', '!forumhome');
define ('P_Forum', '!forum');
define ('P_Thread', '!thread');
define ('P_Start', '!start');
define ('P_LastChanges', '!lastchanges');
define ('P_Info', '!info');
define ('P_NewWiki', '!newwiki');
define ('P_Undef', '!undef');

// Mime types:
define ('M_Undef', '?');
define ('M_Wiki', 'wiki');
define ('M_HTML', 'html');
// Text types
define ('TT_Wiki', 'w');
define ('TT_HTML', 'h');

// Themes: Index in Tabelle param: Einträge als HTML!
define ('Theme_All', 1);

define ('Param_DBScheme', 10);
define ('Param_DBBaseContent', 11);
define ('Param_DBExtensions', 12);
define ('Param_BasarName', 13);
define ('Param_UserTitles', 14);
define ('Param_ScriptBase', 15);

define ('Theme_Standard', 10);

define ('C_MinIdForThemes', 100);
define ('Th_ThemeName', 100);
define ('Th_Header', 101);
define ('Th_CSSFile', 102);

define ('Th_HeaderHTML', 111);
define ('Th_BodyStartHTML', 112);
define ('Th_BodyEndHTML', 113);
define ('Th_EditHeaderHTML', 114);
define ('Th_EditStartHTML', 115);
define ('Th_EditEndHTML', 116);

define ('Th_ThreadHeader', 121);
define ('Th_ThreadBodyStart', 122);
define ('Th_ThreadBodyEnd', 123);
define ('Th_NewThreadHeader', 124);
define ('Th_NewThreadBodyStart', 125);
define ('Th_NewThreadBodyEnd', 126);
define ('Th_AnswerHeader', 127);
define ('Th_AnswerBodyStart', 128);
define ('Th_AnswerBodyEnd', 129);

define ('Th_LoginHeader', 131);
define ('Th_LoginBodyEnd', 132);
define ('Th_Overview', 133);
define ('Th_InfoHeader', 134);
define ('Th_InfoBodyEnd', 135);

define ('Th_StandardHeader', 141);
define ('Th_StandardBodyStart', 142);
define ('Th_StandardBodyEnd', 143);

define ('Th_SearchHeader', 151);
define ('Th_SearchBodyStart', 152);
define ('Th_SearchBodyEnd', 153);

define ('Th_HeaderWiki', 161);
define ('Th_BodyStartWiki', 162);
define ('Th_BodyEndWiki', 163);
define ('Th_EditHeaderWiki', 164);
define ('Th_EditStartWiki', 165);
define ('Th_EditEndWiki', 166);
define ('Th_PreviewStart', 167);
define ('Th_PreviewEnd', 168);

define ('Th_ForumHomeHeader', 171);
define ('Th_ForumHomeBodyStart', 172);
define ('Th_ForumHomeBodyEnd', 173);



// Macros:
define ('Macro_Char', '[');
define ('Macro_Suffix', ']');
define ('TM_CurrentDate', '[Date]');
define ('TM_CurrentDateTime', '[DateTime]');
define ('TM_CurrentUser', '[User]');
define ('TM_PageName', '[PageName]');
define ('TM_PageTitle', '[PageTitle]');
define ('TM_PageChangedAt', '[PageChangedAt]');
define ('TM_PageChangedBy', '[PageChangedBy]');
define ('TM_Theme', '[Theme]');
define ('TM_Newline', '[Newline]');
define ('TM_BasarName', '[BasarName]');
define ('TM_ScriptBase', '[ScriptBase]');

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

class Session {
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
	// Recht: <gruppe><art>
	// Gruppe: u(ser) h(tml) w(iki) g(roups) r(ights)
	// Art: add mod(ify) del(ete)
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

	var $fOutputState; // Init Header Body
	var $fFormExists; // true: Es gab schon ein <form> im Text.

	var $fScriptURL; // Ohne / am Ende
	var $fScriptBase; // ohne *.php
	var $fScriptFile; // Relativ zu DocumentRoot
	var $fFileSystemBase; // Absolutpfad im Filesystem des Servers

	var $fTraceFlags;
	var $fPreformated;

	var $fGroups; // array: gid => ",uid1,uid2,...uidX,";

	function Config (){
		$this->fDbServer = 'localhost';
		$this->fOutputState = 'Init';
	}
	function trace($class, $msg){
		if (($class & $this->fTraceFlags) != 0){
			if ($this->fOutputState == 'Init') {
				echo "<head></head>\n<body>\n";
				$this->fOutputState = 'Body';
			}
			#echo sprintf (" Trace: %x / %x: ", $class, $this->fTraceFlags, ($class & $this->fTraceFlags));
			if ($this->fPreformated)
				echo htmlentities ($msg) . "\n";
			else
				echo htmlentities ($msg) . "<br>\n";
		}
		#
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
	function setUserData ($id, $name, $rights, $theme, $width, $height,
		$maxhits, $postingsperpage, $threadsperpage, $startpage) {
		$this->trace (TC_Session1, "setUserData: $id, $name, $rights, $theme, $startpage");
		$this->fUserId = $id;
		$this->fUserName = $name;
		$this->fUserRights = $rights; 
		$this->fUserTheme = $theme;
		$this->fUserTextareaWidth = $width; $this->fUserTextareaHeight = $height;
		$this->fUserMaxHits = $maxhits;
		$this->fUserPostingsPerPage = $postingsperpage <= 1
			? 10 : $postingsperpage;
		$this->fUserThreadsPerPage = $threadsperpage < 2 ? 25 : $threadsperpage;
		$this->fUserStartPage = $startpage;
	}
	function setFormExists($value){ $this->fFormExists = $value; }
	function setScriptBase ($script_url, $script_file, $fs_filename = '') {
		$this->trace (TC_Init, "setScriptBase: $script_url | $script_file");
		$script_url = preg_replace ('/\.php.*$/', '.php', $script_url);
		$this->fScriptURL = $script_url; $this->fScriptFile = $script_file;
		$this->fScriptBase = preg_replace ('/\/\w+\.php.*$/', '', $script_url);
		$this->fFileSystemBase =  preg_replace ('/\/\w+\.php.*$/', '', $fs_filename);
	}
	function setPageName ($uri){
		global $last_pagename;
		$this->trace (TC_Init, 'setPageName: ' . $uri . "($last_pagename)<br>");
		$this->fPageName = preg_replace ('/\?.*$/', '', $uri);
		if (strpos ($this->fPageName, '.php') > 0)
			$this->fPageName = empty ($last_pagename)
				? P_Undef : $last_pagename;
		$this->fPageTitle = $this->fPageName;
		$this->trace (TC_Init, 'setPageName: PageName: ' . $this->fPageName . '<br>');
	}
	function setPageTitle ($title) {
		$this->fPageTitle = $title;
	}
	function setMacros () {
		$this->trace (TC_Init, 'setMacros');
		if ( ($this->fMacroBasarName = dbGetParam ($this, Theme_All,
				Param_BasarName)) == '')
			$this->fMacroBasarName = 'InfoBasar';
		$this->fMacroScriptBase = dbGetParam ($this, Theme_All, Param_ScriptBase);
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
	function hasRight ($right) {
		$rc = ($rc = getPos ($this->fUserRights, ':all:')) >= 0 && is_int ($rc)
			|| ($rc = getPos ($this->fUserRights, ":$right:")) >= 0 && is_int ($rc);
		// p ("hasRight: $this->fUserRights, $right, $rc ");
		return $rc >= 0;
	}
	function isMember ($group, $user) {
		return ($rc = strpos ($this->fGroups[$group], ',' . $user . ',')) >= 0
			&& is_int ($rc);
	}
	function setPageData ($name, $date, $by) {
		$this->fPageName = $name;
		$this->fPageChangedAt = dbSqlDateToText ($this, $date);
		$this->fPageChangedBy = $by;
	}
	function replaceMacrosNoHTML ($text){
		$this->trace (TC_Session2, 'replaceMacrosNoHTML: ' . $text);
		$count = 1;
		$again = ($pos = strpos ($text, Macro_Char)) >= 0 && is_int ($pos);
;
		$this->trace (TC_Session3, 'replaceMacrosNoHTML-2: ' . (0+$again));
		while ($again) {
			$again = false;
			if (++$count > 5) {
				$this->trace (TC_Error, 'replaceMacrosNoHTML: zu verschachtelt: ' . $text);
				break;
			}
			if ( ($pos = strpos ($text, TM_MacroPrefix)) >= 0 && is_int ($pos)) {
				$text = preg_replace ($this->fMacroReplacementKeys,
					$this->fMacroReplacementValues, $text);
				$this->trace (TC_Session3, 'replaceMacrosNoHTML-3: ' . $text);
				$again = true;
			}
			$text = str_replace (TM_CurrentUser, htmlentities ($this->fUserName), $text);
			$text = str_replace (TM_CurrentDate, strftime ('%Y.%m.%d'), $text);
			$text = str_replace (TM_CurrentDateTime, strftime ('%Y.%m.%d %H:%M'), $text);
			$text = str_replace (TM_PageName, $this->fPageName, $text);
			$text = str_replace (TM_PageTitle, $this->fPageTitle, $text);
			$text = str_replace (TM_BasarName, $this->fMacroBasarName, $text);
			$text = str_replace (TM_ScriptBase, $this->fMacroScriptBase, $text);
			$text = str_replace (TM_PageChangedAt, $this->fPageChangedAt, $text);
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
};
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
	function stopParagraph () {
		$this->fSession->trace (TC_Layout1 + TC_Formating, "stopParagraph");
		$this->changeUListLevel (0);
		$this->changeOrderedListLevel (0);
		if ($this->fOpenTable){
			$this->fOpenTable = false;
			echo "</table>\n";
		}
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
$dbType = "MySQL";
?>
