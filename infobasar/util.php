<?php
// util.php: common utilites
// $Id: util.php,v 1.25 2005/01/13 03:38:57 hamatoma Exp $
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

	if($exitwiki)		// just in case CloseDataBase calls us
		exit();
	$exitwiki = 1;


	if($errormsg <> '') {
		echo TAG_PARAGRAPH;
		echo TAG_HRULE;
		echo TAG_H2;
		echo "Schwerer Fehler";
		echo TAG_H2_END;
		echo $errormsg;
		echo TAG_BODY_HTML_END;
	}
	dbClose($session);

	exit;
}
function protoc ($message) {
	echo $message; outNewline ();
}
function error ($message) {
	protoc (TAG_H1 . '+++ ' . $message . TAG_H1_END);
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
	echo TAG_PARAGRAPH;
	echo $message;
	echo TAG_PARAGRAPH_END;
}
function extractHtmlBody ($page){
	$page = preg_replace (TAG_REXPR_BODY, '', $page);
	$page = preg_replace (TAG_REXPR_BODY_END, '', $page);
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
				TAG_STRONG . $tofind . TAG_STRONG_END, htmlentities ($line));
			if ($rc)
				$rc .= TAG_NEWLINE . $line;
			else
				$rc = $line;
			if (--$count == 0)
				break;
		}
	}
	return $rc;
}
function indexOf ($array, $value){
	$ix = -1;
	for ($ii = 0; $ix < 0 && $ii < count ($array); $ii++)
		if ($array [$ii] == $value)
			$ix = $ii;
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
function writeExternLink ($link, $text, $link_only, &$status) {
	$status->fSession->trace (TC_Util2, "writeExternLink: link: $link");
	if ($text == '')
		$text = $link;
	if (! $link_only && preg_match ('/\.(jpe?g|png|gif|bmp)$/i', $link)) {
		if (preg_match ('(http:[^/])i', $link)){
			$link = "http:" . $status->fSession->fScriptBase . "/" . substr ($link, 5);
			$status->fSession->trace (TC_Util2, "writeExternLink: link: $link");
		}
		echo  TAG_IMAGE_ALT . $text . TAG_IMAGE_TITLE . $text . TAG_IMAGE_SOURCE . $link
			. TAG_APO_SUFFIX;
	} else {
		echo TAG_ANCOR_HREF . $link . TAG_APO_SUFFIX;
		echo htmlentities ($text);
		echo TAG_ANCOR_END;
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
	if (empty ($text))
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
function showArray (&$array, $start){
	$rc = 0+count ($array);
	while ($start < count ($array)){
		$rc .= " " . ($start + 0) . ": " . $array [$start];
		$start++;
	}
	return $rc;
}
		// Klammer 1: Vorspann Klammer 2: Muster, das evt. ersetzt wird
		// unterstrichen, (kursiv, fett, kursiv-fett),
define ('IB_REG_EXPR', '/^(.*?)(__|\'{2,4}|%%%'
		//  Extern-Link Klammer 3: Text
		. '|\[\[[a-z]+:[^]|]+(\|[^\]]*)?\]\]'
		// http-Link, ftp-Link, mailto: // Klammer 4: Potokoll
		. '|(https?:|ftp:|mailto:\w+@)\S+'
		// (Nicht-)Wiki-Name: Klammer 5: Wiki-Name
		. '|!?(' . CC_WikiName_Uppercase . '+' . CC_WikiName_Lowercase . '+' . CC_WikiName_Uppercase
			. CC_WikiName . '*)'
		// Genau ein Zeichen:
		. '|\[\[.\]\]'
		// Klammer 6: Makros
		. '|\[(newline|\/?big|\/?small|\/?su[pb]erscript|\/?teletype|\/?tt|\/?su[bp])\]'	// TM_Newline
		// Wiki-Verweis
		// Klammer 7: Wikiname Klammer 8: |Text
		. '|\["(' . CC_WikiName . '+)"(\|[^\]]*)?\]'
		// Plugin
		// Klammer 9: Plugin-Name Klammer 10: Parameter
		. '|<\?plugin\s+(\w+)(.*)\?>'
		// Hex-Anzeige:
		// Klammer 11:  Hexdumplbereich
		. '|%hex\((.*?)\)'
		. ')/');
function writeText ($body, &$status) {
	$status->trace (TC_Util2, "writeText: $body");
	$count = 0;
	while (strlen ($body) > 0 && preg_match (IB_REG_EXPR, $body, $match)) {
		$args = count ($match);
		$count++;
		if ($match[1] != '')
			echo htmlentities ($match[1]);
		#$status->trace (TC_X, "writeText-2:" . showArray ($match, 2));
		// Alle Ausdrücke ohne Klammern:	
		if ($args == 3){
			switch ($match [2]){
			case '__': $status->handleEmphasis ('u'); break;
			case '\'\'': $status->handleEmphasis ('i'); break;
			case '\'\'\'': $status->handleEmphasis ('b'); break;
			case '\'\'\'\'': $status->handleEmphasis ('x'); break;
			case '%%%': outNewline (); break;
			default:
				if (strpos ($match [2], "hex(") == 1){
					for ($ii = 5; $ii < strlen ($match [2]) - 1; $ii++)
						printf ("%02x ", ord (substr ($match [2], $ii, 1)));
				} elseif (getPos ($match [2], '[[') == 0){
					if (strlen ($match [2]) == 5)
						echo substr ($match [2], 2, 1);
					else
						writeExternLink (substr ($match [2], 2, strlen ($match [2]) - 4), 
							null, true, $status);
				} else
					echo htmlentities ($match [2]);
			}
		} else {
			if ($args == 9)
				writeWikiName ($match [7], substr ($match [8], 1), $status);
			elseif ($args == 8)
				writeWikiName ($match [7], null, $status);
			elseif ($args == 5) // Direkter Verweis (ohne [[]]:
				writeExternLink ($match [2], null, false, $status);
			elseif ($args == 4){ // [[Verweis]]: 
				$len = strpos ($match [2], '|') - 2;
				writeExternLink (substr ($match [2], 2, $len > 0 ? $len : strlen ($match [2])),
					substr ($match [3], 1), true, $status);
			} elseif ($args == 6) // (Nicht-)Wikiname
				writeWikiName ($match [2], null, $status);
			elseif ($args == 7){
				switch ($match [6]){
				case 'newline': echo '<br />'; break;
				case 'big': case '/big': case 'small': case '/small':
				case 'sub': case '/sub': case 'sup': case '/sup': case 'tt': case '/tt': 
					echo TAG_PREFIX;
					echo $match [6];
					echo TAG_SUFFIX;
					break;
				case 'subscript':	echo TAG_SUB; break;
				case 'superscript':	echo TAG_SUP; break;
				case '/subscript':  echo TAG_SUP_END; break;
				case '/superscript':	echo TAG_SUP_END; break;
				case '/teletype':	echo TAG_TT; break; 
				case 'teletype':	echo TAG_TT_END; break;
				default:
					echo $match [2]; break;
				}
			} elseif ($args == 11)
				writePlugin ($match [9], $match [10], $status);
			else
				echo $match [2];
		} // args != 3

		$body = substr ($body, strlen ($match [1]) + strlen ($match [2]));
	}
	if ($body != '')
		echo $status->fSession->replaceMacrosHTML (
			htmlentities ($status->fSession->replaceMacrosNoHTML ($body)));
}
function writeTagPair ($tag, $body, &$status) {
	$status->trace (TC_Util3, "writeTagPair: $tag, $body");
	echo TAG_PREFIX . $tag . TAG_SUFFIX;
	writeText ($body, $status);
	$status->stopSentence ();
	echo TAG_ENDPREFIX;
	echo $tag;
	echo TAG_SUFFIX_NEWLINE;
}
function writeHeader ($line, &$status) {
	$status->trace (TC_Util2, "writeHeader: $line");
	$status->stopSentence ();
	$count = countRepeats ($line, '!');
	writeTagPair (TAGN_HEADLINE . $count, substr ($line, $count), $status);
}
function writeUList ($line, &$status) {
	$status->trace (TC_Util3, "writeUList: $line");
	$count = countRepeats ($line, '\*');
	$status->changeUListLevel ($count);
	writeText (substr ($line, $count), $status);
}

function writeOrderedList ($line, &$status) {
	$status->trace (TC_Util3, "writeOrderedList: $line");
	$count = countRepeats ($line, '#');
	$status->changeOrderedListLevel ($count);
	writeText (substr ($line, $count), $status);
}
function writeIndent ($line, &$status) {
	$status->trace (TC_Util3, "writeIndent: $line");
	$count = countRepeats ($line, ';');
	$status->changeIndentLevel ($count);
	writeLine (substr ($line, $count), $status);
}
function writeTable ($line, &$status) {
	$status->trace (TC_Util3, "writeTable: $line");
	$status->startTable ();
	$cols = explode ('|', substr ($line, 1));
	echo TAG_TABLE_RECORD;
	foreach ($cols as $ii => $col) {
		writeTagPair (TAGN_TABLE_DELIM, $col, $status);
	}
	echo TAG_TABLE_RECORD_END;
}

function writeTableHeader ($line, &$status) {
	$status->trace (TC_Util3, "writeTableHeader: $line");
	$status->stopTable ();
	$status->startTable ();
	$cols = explode ('|', substr ($line, 2));
	echo TAG_TABLE_RECORD;
	foreach ($cols as $ii => $col) {
		echo TAG_TABLE_DELIM;
		echo TAG_STRONG;
		writeText ($col, $status);
		$status->stopSentence();
		echo TAG_STRONG_END;
		echo TAG_TABLE_DELIM_END;
	}
	echo TAG_TABLE_RECORD_END;
}
function writeLine ($line, &$status) {
	$status->trace (TC_Util3, "writeLine: $line " . ($status->fPreformatted ? "T" : "F"));
	if ($status->fPreformatted){
		echo htmlentities ($line);
		echo "\n";
	} else {
		$status->startParagraph ();
		writeText ($line, $status);
		echo " ";
	}
}
function wikiToHtml (&$session, $wiki_text) {
	$lines = explode ("\n", $wiki_text);
	$session->trace (TC_Util1, 'wikiToHtml: ' . (0+count ($lines)) . ' Zeilen'
		. "($lines[0])");
	$status = new LayoutStatus ($session);
	$last_linetype = '';
	foreach ($lines as $ii => $line) {
		$start_code = false;
		if (! $status->fPreformatted && ($line_trimmed = trim ($line)) == ''){
			$last_linetype = '';
			$status->changeOfLineType ($last_linetype, '');
		} else {
			$linetype = $status->fPreformatted ? '[' : substr ($line, 0, 1);
			switch ($linetype){
			case '-': 
				$count = countRepeats ($line, '-');
				if ($count < 4)
					$linetype = 'x';
				break;
			case '[':
				if (strpos ($line, 'code]') == 1)
					$start_code = true;
				elseif (strpos ($line, '/code]') == 1){
					$status->finishCode();
					$last_linetype = 'x';
					$line = $line_trimmed = substr ($line, 7);
					$session->trace (TC_Util2, 'wikiToHtml: /code-Restzeile: ' . $line);
				} else
					$linetype = 'x'; 
				break;
			case '!': case ';': case '*': case '#': case '|':
				break;
			default:
				$linetype = 'x'; break;
			}
			$last_linetype = $status->testChangeOfLineType ($last_linetype, $linetype);
			switch ($linetype) {
			case '!':
				if (strpos ($line, '|') != 1)
					writeHeader ($line, $status);
				else {
					writeTableHeader ($line, $status);
					$last_linetype = '|';
				}
				break;
			case '[':
				if ($start_code)
					$status->startCode();
				$line = substr ($line_trimmed, 6);
				if (! empty ($line))
					writeLine ($line, $status); 
				break;
			case ';': writeIndent ($line, $status); break;
			case '*': writeUList ($line, $status); break;
			case '#': writeOrderedList ($line, $status); break;
			case '|': writeTable ($line, $status); break;
			case '-': guiLine ($status->fSession, $count - 3); break;
			default: 
				writeLine ($line, $status); break; 
			}
		}
	} // foreach
	if ($status->fPreformatted)
		$session->trace (TC_Warning, PREFIX_Warning . '[/code] fehlt');
	$session->trace (TC_Util1, 'wikiToHtml-Ende');
}
function getUserParam (&$session, $name, &$param) {
	$session->trace (TC_Util2, "getUserParam: $name");
	if (! isset ($param) || empty ($param)){
		if (isset ($_POST [$name]) && ! empty ($_POST [$name]))
			$param = $_POST [$name];
		else {
			switch ($name){
			case U_TextAreaWidth: $param = $session->fUserTextareaWidth; break;
			case U_TextAreaHeight: $param = $session->fUserTextareaHeight; break;
			case U_MaxHits: $param = $session->fUserMaxHits; break;
			case U_PostingsPerPage: $param = $session->fUserPostingsPerPage; break;
			case U_Theme: $param = $session->fUserTheme;
			default: $session->trace (TC_Error, "getUserParam: unbek. Param: $name"); break;
			}
		}
	}
	return $param;
}
function getTextareaSize (&$session, &$width, &$height){
	if (! isset ($_POST[U_TextAreaWidth]) || empty ($_POST[U_TextAreaWidth]))
		$_POST[U_TextAreaWidth] = getUserParam ($session, U_TextAreaWidth, $width);
	$width = $_POST[U_TextAreaWidth];
	if ($width <= 0)
		$width = $_POST[U_TextAreaWidth] = 70;
	if (! isset ($_POST[U_TextAreaHeight]) || empty ($_POST[U_TextAreaHeight]))
		$_POST[U_TextAreaHeight] = getUserParam ($session, U_TextAreaHeight, $height);
	$height = $_POST[U_TextAreaHeight];
	if ($height <= 0)
		$height = $_POST[U_TextAreaHeight] = 10;
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
	$session->trace (TC_Init, 'setLoginCookie');
	if ($session->fSessionNo != null && $session->fSessionNo > 0){
		$session->trace (TC_Init, 'setLoginCookie-2');
		$value = encrypt ($session, $user . " " . $code, COOKIE_NAME);
		$session->trace (TC_Init, 'setLoginCookie: ' . COOKIE_NAME . ": ". $user . "/" . $value);
		setCookie (COOKIE_NAME, $value, time() + 60*60*24*365);
	}
}
function getLoginCookie (&$session, &$user, &$code){
	$session->trace (TC_Init, 'getLoginCookie');
	$user = null;
	$code = null;
	if ($session->fSessionNo == null || $session->fSessionNo < 0)
		$rc = false;
	elseif ($rc = ! empty ($_COOKIE[COOKIE_NAME])){
		$session->trace (TC_Init, 'getLoginCookie-2: ' . $_COOKIE[COOKIE_NAME]);
		$value = decrypt ($session, $_COOKIE[COOKIE_NAME], COOKIE_NAME);
		$session->trace (TC_Init, 'getLoginCookie-3: ' . $value);
		if ( ($pos = strpos ($value, " ")) > 0){
			$user = substr ($value, 0, $pos);
			$code = substr ($value, $pos + 1);
			$session->trace (TC_Init, 'getLoginCookie-4: ' . COOKIE_NAME 
				. ": ". $user . "/" . $code);
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
	$uri = $session->fScriptBase . "/index.php/!login"; 
	if ($uri != $_SERVER['REQUEST_URI'])
		header ('Location: http://' . $_SERVER['HTTP_HOST'] . $uri);
	echo TAG_DOC_TYPE;
	echo TAG_HTML_BODY;
	echo "\nBitte erst anmelden: ";
	echo TAG_ANCOR_HREF;
	echo $uri;
	echo TAG_APO_SUFFIX_NEWLINE;
	echo TAG_BODY_HTML_END;
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
function getPostVar ($name){
	return isset ($_POST [$name]) ? $_POST [$name] : '';
}
function sessionStart (){
	session_start();
	if (!session_is_registered("session_user")) {
		session_register("session_user");
		session_register("session_start");
		session_register("session_no");
		$_SESSION ['session_user'] = $_SESSION ['session_start'] = $_SESSION ['session_no'] = null;
 	}
	return session_id();
}
function successfullLogin (&$session){
	dbOpen($session);

	if ((empty ($session_user)) && getLoginCookie ($session, $user, $code)
		&& dbCheckUser ($session, $user, $code) == ''){
	$session->trace (TC_Init, 'index.php: Cookie erfolgreich gelesen');
	}
	$rc = dbCheckSession ($session);
	$do_login = false;
	#$session->dumpVars ("Init");
	if ($rc != null) {
		$session->trace (TC_Init, 'keine Session gefunden: ' . $rc . ' ' 
			. (empty($_POST ['login_user']) ? "-" : '>' . $_POST ['login_user']));
		$do_login = true;
	} else {
		$session->trace (TC_Init, 'login_user: ' . getPostVar ('login_user')); 
		if (isset ($_POST ['login_user']))
			$do_login = guiLoginAnswer ($session, $rc);
		else {
			$known_user = $session->fSessionUser != null && $session->fSessionUser > 0; 
			$do_login = $session->fPageName == P_Login || ! $known_user;
			$session->trace (TC_Init, 'known_user: ' . ($known_user ? 't' : 'f'));
		}
	}
	$session->trace (TC_Init, "session_no: do_login: " . ($do_login ? "t" : "f"));
	if ($do_login){
		clearLoginCookie ($session);
		guiLogin ($session, $rc);
	} else {
		$session->storeSession ();
	}
	return ! $do_login;
}
function dumpPost (&$session){
	echo 'Inhalt der Variable _POST:<br>';
	foreach ($_POST as $name => $value)
		echo $name . " = " . $value . "<br>";
}
?>
