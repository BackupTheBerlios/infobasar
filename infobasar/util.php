<?php
// util.php: common utilites
// $Id: util.php,v 1.14 2004/10/30 23:55:10 hamatoma Exp $
/*
Diese Datei ist Teil von InfoBasar.
Copyright 2004 hamatoma@gmx.de München
InfoBasar ist freie Software. Du kannst es weitergeben oder verändern
unter den Bedingungen der GNU General Public Licence.
Näheres siehe Datei LICENCE.
InfoBasar sollte nützlich sein, es gibt aber absolut keine Garantie
der Funktionalität.
*/
function panicExit (&$session, $errormsg) {
	static $exitwiki = 0;
	global $dbi;

	if($exitwiki)		// just in case CloseDataBase calls us
		exit();
	$exitwiki = 1;


	if($errormsg <> '') {
		print "<P><hr noshade><h2>Schwerer Fehler</h2>\n";
		print $errormsg;
		print "\n</body></html>";
	}
	dbClose($session);

	exit;
}
function protoc ($message) {
	echo $message; echo "<br />\n";
}
function error ($message) {
	protoc ('<h1>+++ ' . $message . '</h1>');
}
function textToHtml ($text){
	return htmlentities ($text);
}
function htmlToText (&$session, $text){
	if ($session->fVersion >= 430)
		return html_entity_decode ($text);
	else
		return htmlspecialchars ($text);
}
function p ($message){
	echo "<p>$message</p>\n";
}
function extractHtmlBody ($page){
	$page = preg_replace ( '/^.*<\s*body\s*>/si', '', $page);
	$page = preg_replace ('/<\s*\/\s*body\s*>.*$/si', "", $page);
	return $page;
}
function getPos ($haystock, $needle){
	$rc = strpos ($haystock, $needle);
	return is_int ($rc) ? $rc : -1;
}
function countRepeats ($line, $char) {
	preg_match ("/^($char+)/", $line, $matches);
	return strlen ($matches [1]);
}
function findTextInLine ($body, $tofind, $count) {
	$lines = explode ("\n", $body);
	$rc = null;
	foreach ($lines as $ii => $line) {
		if ( ($pos = strpos ($line, $tofind)) >= 0 && is_int ($pos)) {
			$line = ($ii + 1) . ": " . str_replace ($tofind,
				'<b>' . $tofind . '</b>', htmlentities ($line));
			if ($rc)
				$rc .= "<br/>\n" . $line;
			else
				$rc = $line;
			if (--$count == 0)
				break;
		}
	}
	return $rc;
}
function encodeWikiName (&$session, $name){
	$session->trace (TC_Util2, 'encodeWikiName');
	return urlencode ($name);
}
function decodeWikiName (&$session, $name){
	$session->trace (TC_Util2, 'decodeWikiName');
	return urldecode ($name);
}
define ('CC_NotWikiChars', '/[^' . CL_WikiName . ']/');
function normalizeWikiName (&$session, $name){
	$session->trace (TC_Util2, 'normalizeWikiName');
	return preg_replace (CC_NotWikiChars, "", $name);
}
function normWikiName (&$session, &$name){
	$name2 = normalizeWikiName ($session, $name);
	if ($name2 == $name)
		return false;
	else {
		$name = $name2;
		return true;
	}
}
function writeExternLink ($link, $text, &$status) {
	$status->fSession->trace (TC_Util2, "writeExternLink: link: $link");
	if ($text == '')
		$text = $link;
	if (preg_match ('/\.(jpg|png|gif|bmp)$/i', $link)) {
		if (preg_match ('(http:[^/])i', $link)){
			$link = "http:" . $status->fSession->fScriptBase . "/" . substr ($link, 5);
			$status->fSession->trace (TC_Util2, "writeExternLink: link: $link");
		}
		echo '<img alt="' . $text . '" title="' . $text . '"src="' . $link
			. '">';
	} else {
		echo '<a href="' . $link . '">';
		echo htmlentities ($text);
		echo '</a>';
	}
}
function writePlugin ($name, $param, &$status) {
	$status->trace (TC_Util2, "writePlugin: $name ($param)");
	if ($name == 'BackLinks') {
		if (empty ($param))
			$param = $status->fSession->fPageName;
		guiBackLinks ($status->fSession, $param);
	} else
		$status->fSession->trace (TC_Error, PREFIX_Error
		. "writePlugin: unbekannt: $name ($param)");

}

function writeWikiName ($name, $text, &$status) {
	$status->trace (TC_Util2, "WriteWikiName: $name / $text");
	if ($text == '')
		$text = $name;
	if (substr ($name, 0, 1) == "!")
		echo htmlentities (substr ($name, 1));
	else {
		$link = encodeWikiName ($status->fSession, $name);
		if (dbPageId ($status->fSession, $name) > 0)
			guiInternLink ($status->fSession, $link, $text);
		else
			guiPageReference ($status->fSession, $link, $text);
	}
}
		// Klammer 0: Vorspann Klammer 1: Muster, das evt. ersetzt wird
		// unterstrichen, (kursiv, fett, kursiv-fett),
define ('ib_reg_expr', '/^(.*?)(__|\'{2,4}'
		//  Extern-Link
		// Klammer 2: URL Klammer 3: Text
		. '|\[([a-z]+:\S+)(\s+[^]]*)?\]'
		// http-Link, ftp-Link, mailto
		// Klammer 4: Protokollname
		. '|(https?|ftp):\/\/\S+'
		// (Nicht-)Wiki-Name
		. '|!?' . CC_WikiName_Uppercase . '+' . CC_WikiName_Lowercase . '+[' . CC_WikiName_Uppercase
			. CC_WikiName . '*'
		// Genau ein Zeichen:
		. '|\[.\]'
		// Zeilenwechsel:
		. '|\[Newline\]'	// TM_Newline
		// Wiki-Verweis
		// Klammer 5: Wikiname Klammer 6: Text
		# '|\[([A-Za-zÄÖÜääöü]+[-äöüßa-zA-Z_0-9]+)\s*([^]]*)?\]'
		. '|\[(' . CC_WikiName . '+)\s*([^]]*)?\]'
		// Plugin
		// Klammer 7: Plugin-Name Klammer 8: Parameter
		. '|<\?plugin\s+(\w+)(.*)\?>'
		// Hex-Anzeige:
		// Klammer 9:  Hexdumplbereich
		. '|%hex\((.*?)\)'
		. ')/');
function writeText ($body, &$status) {
	global $ib_reg_expr;
	$status->trace (TC_Util2, "writeText: $body");
	$count = 0;
	while (strlen ($body) > 0
		&& preg_match (ib_reg_expr,
			$body, $match)) {
		$args = count ($match);
		$count++;
		if ($match[1] != '')
			echo htmlentities ($match[1]);
		switch ($match [2]){
		case '__': $status->handleEmphasis ('u'); break;
		case '\'\'': $status->handleEmphasis ('b'); break;
		case '\'\'\'': $status->handleEmphasis ('i'); break;
		case '\'\'\'\'': $status->handleEmphasis ('x'); break;
		default:
			if (strpos ($match [2], "hex(") == 1){
				for ($ii = 5; $ii < strlen ($match [2]) - 1; $ii++){
					printf ("%02x ", ord (substr ($match [2], $ii, 1)));
				}
			} elseif ($args == 8 &&  $match [6] != '')
				writeWikiName ($match [6], $match [7], $status);
			elseif ($args > 5 && $match [5] != '')
				writeExternLink ($match [2], '', $status);
			elseif ( ($pos = strpos ($match [2], '[')) == 0 && is_int ($pos)) {
				if ($args <= 4){
					if (strlen ($match [2]) == 3)
						echo substr ($match [2], 1, 1);
					else if ($match [2] == '[Newline]')
						echo '<br />';
					else
						echo $match [2];
				} else
					writeExternLink ($match [3], $match [4], $status);
			} elseif ($args > 9 && ($pos = strpos ($match [2], '<?')) == 0 && is_int ($pos))
				writePlugin ($match [8], $match [9], $status);
			else
				writeWikiName ($match [2], '', $status);
			break;
		} // switch

		$body = substr ($body, strlen ($match [1]) + strlen ($match [2]));
	}
	if ($body != '')
		echo $status->fSession->replaceMacrosHTML (
			htmlentities ($status->fSession->replaceMacrosNoHTML ($body)));
}
function writeTagPair ($tag, $body, &$status) {
	$status->trace (TC_Util3, "writeTagPair: $tag, $body");
	echo "<" . $tag . ">";
	writeText ($body, $status);
	$status->stopSentence ();
	echo "</" . $tag . ">\n";
}
function writeHeader ($line, &$status) {
	$status->trace (TC_Util2, "writeHeader: $line");
	$status->stopSentence ();
	$count = countRepeats ($line, '!');
	$tag = "h$count";
	writeTagPair ($tag, substr ($line, $count), $status);
}
function writeUList ($line, &$status) {
	$status->trace (TC_Util3, "writeUList: $line");
	$count = countRepeats ($line, '\*');
	if ($status->fUListLevel != $count)
		$status->changeUListLevel ($count);
	writeTagPair ('li', substr ($line, $count), $status);
}
function writeOrderedList ($line, &$status) {
	$status->trace (TC_Util3, "writeOrderedList: $line");
	$count = countRepeats ($line, '#');
	if ($status->fOrderedListLevel != $count)
		$status->changeOrderedListLevel ($count);
	writeTagPair ('li', substr ($line, $count), $status);
}
function writeIndent ($line, &$status) {
	$status->trace (TC_Util3, "writeIndent: $line");
	echo "<br/>Einrücken fehlt noch<br/>";
}
function writeTable ($line, &$status) {
	$status->trace (TC_Util3, "writeTable: $line");
	$status->startTable ();
	$cols = explode ('|', substr ($line, 1));
	echo '<tr>';
	foreach ($cols as $ii => $col) {
		writeTagPair ('td', $col, $status);
	}
	echo "</tr>\n";
}
function writeTableHeader ($line, &$status) {
	$status->trace (TC_Util3, "writeTableHeader: $line");
	$status->stopTable ();
	$status->startTable ();
	$cols = explode ('|', substr ($line, 2));
	echo '<tr>';
	foreach ($cols as $ii => $col) {
		echo '<td><b>';
		writeText ($col, $status);
		$status->stopSentence();
		echo '</b></td>';
	}
	echo "</tr>\n";
}
function writeParagraphEnd (&$status) {
	$status->trace (TC_Util3, 'writeParagraphEnd');
	$status->stopParagraph ();
}
function writeLine ($line, &$status) {
	$status->trace (TC_Util3, "writeLine: $line");
	$status->startParagraph ();
	writeText ($line, $status);
	echo " ";
}
function writeHoricontalLine ($line, &$status) {
	$status->trace (TC_Util2, 'writeHoricontalLine');
	$count = countRepeats ($line, '-');
	if ($count < 4)
		writeLine ($line, $status);
	else
		guiLine ($status->fSession, $count - 3);
}
function wikiToHtml (&$session, $wiki_text) {
	$lines = explode ("\n", $wiki_text);
	$session->trace (TC_Util1, 'wikiToHtml: ' . (0+count ($lines)) . ' Zeilen'
		. "($lines[0])");
	$status = new LayoutStatus ($session);
	foreach ($lines as $ii => $line) {
		if ( ($line_trimmed = trim ($line)) == '')
			writeParagraphEnd ($status);
		else {
			if ($line_trimmed == '[code]')
				$session->startCode();
			elseif ($line_trimmed == '[/code]')
				$session->finishCode();
			elseif ($session->fPreformated) {
				echo htmlentities ($line);
			} else
				switch (substr ($line, 0, 1)) {
				case '!':
					if (substr ($line, 1, 1) == '|')
						writeTableHeader ($line, $status);
					else
						writeHeader ($line, $status);
					break;
				case ' ': writeLine ($line, $status); break;
					# writeIndent ($line, $status); break;
				case '*': writeUList ($line, $status); break;
				case '#': writeOrderedList ($line, $status); break;
				case '|': writeTable ($line, $status); break;
				case '-': writeHoricontalLine ($line, $status); break;
				default: writeLine ($line, $status); break;
				}
		}
	} // foreach
	$session->trace (TC_Util1, 'wikiToHtml-Ende');
}
function getUserParam ($session, $name, &$param) {
	$session->trace (TC_Util2, "getUserParam: $name");
	if (! isset ($param) || empty ($param))
		switch ($name){
		case U_TextAreaWidth: $param = $session->fUserTextareaWidth; break;
		case U_TextAreaHeight: $param = $session->fUserTextareaHeight; break;
		case U_MaxHits: $param = $session->fUserMaxHits; break;
		case U_PostingsPerPage: $param = $session->fUserPostingsPerPage; break;
		case U_Theme: $param = $session->fUserTheme;
		default: $session->trace (TC_Error, "getUserParam: unbek. Param: $name"); break;
		}
}
function isInt ($val) {
	return preg_match ('/^\d+\s*$/', $val);
}
function textAreaToWiki (&$session, $text) {
	$session->trace (TC_Util3, 'textAreaToWiki');
	$text = stripcslashes ($text);
	$text = preg_replace ('/\s+$/', '', $text);
	return $text;
}
function decDump ($text) {
	echo 'decDump: ' . $text . '<br>';
	$rc = "";
	for ($ii = 0; $ii < strlen ($text); $ii++)
		$rc .= ' ' . ord (substr ($text, $ii, 1));
	return $rc;
}
function createPassword (&$session, $length){
	$session->trace (TC_Util2, "createPassword");
	srand ((double)microtime()*87785);
	$rc = "";
	$base = 'BCDFGHJKLMNPQRSTVWX1234567890.!$&';
	for ($ii = 0; $ii < $length; $ii += 2)
			$rc .= substr ('aeiouy', rand (0, 5), 1)
				. substr ($base, rand (0, strlen ($base) - 1), 1);
	return $rc;
}
function sendPassword (&$session, $id, $user, $email){
	$session->trace (TC_Util1, "sendPassword");
	$password = createPassword ($session, 6);
	dbUpdate ($session, T_User, $id,
		'code=' . dbSqlString ($session, 
			encryptPassword ($session, $user, $password)) . ',');
	mail ($email, 'Deine Anmeldedaten für den Infobasar',
		'Es wurde ein neues Passwort erzeugt:' . "\n$password\n"
		. 'Bitte nach dem Anmelden das Passwort wieder ändern');
}
function encryptPassword (&$session, $user, $code){
	$session->trace (TC_Util2, "encryptPassword");
	return strrev (crypt ($code, $user));
}
function encrypt (&$session, $value, $salt){
	return createPassword ($session, 6) . strrev ($value) . 'jfi9';
}
function decrypt (&$session, $value, $salt){
	return strrev (substr ($value, 6, strlen ($value) - 10));
}
function setLoginCookie (&$session, $user, $code) {
	global $session_no;
	$session->trace (TC_Init, 'setLoginCookie');
	if (isset ($session_no) && $session_no > 0){
		$session->trace (TC_Init, 'setLoginCookie-2');
		$value = encrypt ($session, $user . " " . $code, COOKIE_NAME);
		$session->trace (TC_Init, 'setLoginCookie: ' . COOKIE_NAME . ": ". $user . "/" . $value);
		setCookie (COOKIE_NAME, $value, time() + 60*60*24*365);
	}
}
function getLoginCookie (&$session, &$user, &$code){
	global $session_no;
	$session->trace (TC_Init, 'getLoginCookie');
	$user = null;
	$code = null;
	if (! isset ($session_no) || $session_no < 0)
		$rc = false;
	elseif ($rc = ! empty ($_COOKIE[COOKIE_NAME])){
		$session->trace (TC_Init, 'getLoginCookie-2: ' . $_COOKIE[COOKIE_NAME]);
		$value = decrypt ($session, $_COOKIE[COOKIE_NAME], COOKIE_NAME);
		$session->trace (TC_Init, 'getLoginCookie-3: ' . $value);
		if ( ($pos = strpos ($value, " ")) > 0){
			$user = substr ($value, 0, $pos);
			$code = substr ($value, $pos + 1);
			$session->trace (TC_Init, 'getLoginCookie-4: ' . COOKIE_NAME . ": ". $user . "/" . $code);
		}
	}
	$session->trace (TC_Init, 'getLoginCookie-4: ' . $user);
	return $rc;
}
function clearLoginCookie (&$session){
	$session->trace (TC_Init, 'clearLoginCookie');
	setCookie (COOKIE_NAME, null);
}
function getMicroTime(&$session, $time = null){ 
	$session->trace (TC_Util1, 'getMicroTime');
	if (empty ($time))
		$time = microtime ();
	list($usec, $sec) = explode(" ", $time); 
	return ((float) $usec + (float)$sec); 
}
function setRelativeURL (&$session, $url){
	header("Location: http://" . $_SERVER['HTTP_HOST']
	. dirname($_SERVER['PHP_SELF'])                    
	. "/" . $relative_url);
}
function putHeaderBase(&$session){
	global $_SERVER;
	$uri = $session->fScriptBase . "/index.php/!login"; 
	if ($uri != $_SERVER['REQUEST_URI'])
		header ('Location: http://' . $_SERVER['HTTP_HOST'] . $uri);
	echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">';
	echo "\n<html>\n<body>";
	echo "\nBitte erst anmelden: <a href=\"" . $uri . "\">\n";
	echo "</body>\n</html>\n";
}
function mimeToTextType ($mime){
	switch ($mime){
	case M_Wiki: return TT_Wiki;
	case M_HTML: return TT_HTML;
	case M_Text: return TT_Text;
	default: return TT_Undef;
	}
}
function textTypeToMime ($type){
	switch ($type){
	case TT_Wiki: return M_Wiki;
	case TT_HTML: return M_HTML;
	case TT_Text: return M_Text;
	default: return M_Undef;
	}
}
?>
