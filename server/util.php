<?php
// util.php: common utilites
// $Id: util.php,v 1.6 2004/06/13 10:57:21 hamatoma Exp $
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
function makePageName ($name){
	return preg_replace ("/[^-\w]/", "", $name);
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
function normWikiName (&$session, &$name){
	$session->trace (TC_Util2, 'normWikiName');
	$rc = false;
	if (! preg_match ('/öäüÄÖÜß/', $name)){
		$name = preg_replace (array ('/ä/', '/ö/', '/ü/', '/Ä/', '/Ö/', '/Ü/', '/ß/'),
			array ('ae', 'oe', 'ue', 'Ae', 'Oe', 'Ue', 'ss'), $name);
		$rc = true;
	}
	$session->trace (TC_Util2, 'normWikiName-2: ' . $name);
	return $rc;
}

function writeExternLink ($link, $text, &$status) {
	if ($text == '')
		$text = $link;
	if (preg_match ('/\.(jpg|png|gif|bmp)$/i', $link)) {
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
		normWikiName ($status->fSession, $name);
		if (dbPageId ($status->fSession, $name) > 0)
			guiInternLink ($status->fSession, $name, $text);
		else
			guiPageReference ($status->fSession, $name, $text);
	}
}
function writeText ($body, &$status) {
	$status->trace (TC_Util2, "writeText: $body");
	$count = 0;
	while (strlen ($body) > 0
		// unterstrichen, (kursiv, fett, kursiv-fett),
		&& preg_match ('/^(.*?)(__|\'{2,4}'
		//  Extern-Link
		. '|\[([a-z]+:\S+)(\s+[^]]*)?\]'
		// http-Link, ftp-Link
		. '|(https?|ftp|mailto):\/\/\S+'
		// (Nicht-)Wiki-Name
		. '|!?\b[A-ZÄÖÜ][äüö\w]+[A-ZÄÖÜ][ÄÖÜäöü\w]*'
		// Genau ein Zeichen:
		. '|\[.\]'
		. '\[\[\]'
		// Zeilenwechsel:
		. '|\[Newline\]'	// TM_Newline
		// Wiki-Verweis
		. '|\[([A-ZÄÖÜ][-äöüß\w]+)\s*([^]]*)?\]'
		// Plugin
		. '|<\?plugin (\w+)(.*)\?>)/',
			$body, $match)) {
		$args = count ($match);
		$count++;
		if ($match[1] != '')
			echo htmlentities ($match[1]);
		switch ($match [2]){
		case '__': $status->handleEmphasis ('u'); break;
		case '\'\'': $status->handleEmphasis ('i'); break;
		case '\'\'\'': $status->handleEmphasis ('b'); break;
		case '\'\'\'\'': $status->handleEmphasis ('x'); break;

		default:
			# $status->trace (TC_X, '$#match: ' . count ($match));
			if ($args > 5 && $match [5] != '')
				writeExternLink ($match [2], '', $status);
			elseif ($args == 8 &&  $match [6] != '')
				writeWikiName ($match [6], $match [7], $status);
			elseif ( ($pos = strpos ($match [2], '[')) == 0 && is_int ($pos)) {
				if ($args <= 4)
					if (strlen ($match [0]) == 3)
						echo $match [0] == '[[]' ? '[' : $match [0];
					else
						echo '<br />';
				else
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
	if ($status->fOpenTable)
		$status->finishTable ();
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
		guiLine ($count - 3);
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
			#$session->trace (TC_X, "Neue Zeile: '$line_trimmed' $line]");
			if ($line_trimmed == '[code]')
				$session->startCode();
			elseif ($line_trimmed == '[/code]')
				$session->finishCode();
			#elseif (strpos ('code', $line_trimmed) > 0)
			#	$session->trace (TC_X, ":$line_trimmed: statt [/code]");
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
	if (! isset ($param))
		switch ($name){
		case U_TextAreaWidth: $param = $session->fUserTextareaWidth; break;
		case U_TextAreaHeight: $param = $param = $session->fUserTextareaHeight; break;
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
	$session->trace (TC_Util2, 'setLoginCookie');
	$value = encrypt ($session, $user . " " . $code, COOKIE_NAME);
	$session->trace (TC_Util2, 'setLoginCookie: ' . $value);
	setCookie (COOKIE_NAME, $value, time() + 60*60*24*365);
}
function getLoginCookie (&$session, &$user, &$code){
	$session->trace (TC_Util2, 'getLoginCookie');
	if ($rc = ! empty ($_COOKIE[COOKIE_NAME])){
		$session->trace (TC_Util2, 'getLoginCookie-2: ' . $_COOKIE[COOKIE_NAME]);
		$value = decrypt ($session, $_COOKIE[COOKIE_NAME], COOKIE_NAME);
		$session->trace (TC_Util2, 'getLoginCookie-3: ' . $value);
		if ( ($pos = strpos ($value, " ")) > 0){
			$user = substr ($value, 0, $pos);
			$code = substr ($value, $pos + 1);
		}
	}
	$session->trace (TC_Util2, 'getLoginCookie-4: ' . $user);
	return $rc;
}
function clearLoginCookie (&$session){
	$session->trace (TC_Util2, 'clearLoginCookie');
	setCookie (COOKIE_NAME, null);
}
function ObFlush(&$session){
	if ($session->fVersion >= 400)
		ob_end_flush();
}
function getMicroTime(&$session, $time = null){ 
	$session->trace (TC_Util1, 'getMicroTime');
	if (empty ($time))
		$time = microtime ();
	list($usec, $sec) = explode(" ", $time); 
	return ((float) $usec + (float)$sec); 
} 
?>
