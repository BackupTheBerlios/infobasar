<?php
// util.php: common utilites
// $Id: util.php,v 1.21 2005/01/05 05:28:25 hamatoma Exp $
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
function writeExternLink ($link, $text, &$status) {
	$status->fSession->trace (TC_Util2, "writeExternLink: link: $link");
	if ($text == '')
		$text = $link;
	if (preg_match ('/\.(jpg|png|gif|bmp)$/i', $link)) {
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
function showArray (&$array, $start){
	$rc = 0+count ($array);
	while ($start < count ($array)){
		$rc .= " " . ($start + 0) . ": " . $array [$start];
		$start++;
	}
	return $rc;
}
		// Klammer 0: Vorspann Klammer 1: Muster, das evt. ersetzt wird
		// unterstrichen, (kursiv, fett, kursiv-fett),
define ('ib_reg_expr', '/^(.*?)(__|\'{2,4}'
		//  Extern-Link
		// Klammer 2: [[...]] Klammer 3: Text
		. '|\[\[([a-z]+:[^|\]]+)(\|[^]]*)?\]\]'
		// http-Link, ftp-Link, mailto
		// Klammer 4: Protokollname
		. '|(https?|ftp):\/\/\S+'
		// (Nicht-)Wiki-Name
		. '|!?' . CC_WikiName_Uppercase . '+' . CC_WikiName_Lowercase . '+[' . CC_WikiName_Uppercase
			. CC_WikiName . '*'
		// Genau ein Zeichen:
		. '|\[\[.\]\]'
		// Klammer 5: Makros
		. '|\[(newline|\/?big|\/?small)\]'	// TM_Newline
		// Wiki-Verweis
		// Klammer 6: Wikiname Klammer 7: Text
		# '|\[\[([A-Za-zÄÖÜääöü]+[-äöüßa-zA-Z_0-9]+)\s*([^]]*)?\]\]'
		. '|\[\[(' . CC_WikiName . '+)\|([^]]*)?\]\]'
		// Plugin
		// Klammer 8: Plugin-Name Klammer 9: Parameter
		. '|<\?plugin\s+(\w+)(.*)\?>'
		// Hex-Anzeige:
		// Klammer 10:  Hexdumplbereich
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
		#$status->trace (TC_X, "writeText-2:" . showArray ($match, 2));
			
		switch ($match [2]){
		case '__': $status->handleEmphasis ('u'); break;
		case '\'\'': $status->handleEmphasis ('i'); break;
		case '\'\'\'': $status->handleEmphasis ('b'); break;
		case '\'\'\'\'': $status->handleEmphasis ('x'); break;
		default:
			if (strpos ($match [2], "hex(") == 1){
				for ($ii = 5; $ii < strlen ($match [2]) - 1; $ii++){
					printf ("%02x ", ord (substr ($match [2], $ii, 1)));
				}
			} elseif ($args == 9 &&  $match [7] != '')
				writeWikiName ($match [7], $match [8], $status);
			elseif ($args > 4 && $match [4] != '')
				writeExternLink ($match [3], substr ($match [4], 1), $status);
			elseif ($args > 3 && $match [3] != '')
				writeExternLink ($match [3], '', $status);
			elseif ( ($pos = strpos ($match [2], '[')) == 0 && is_int ($pos)) {
				if ($args == 3 && strlen ($match [2]) == 5)
					echo substr ($match [2], 2, 1);
				elseif ($args <= 7){
					switch ($match [6]){
					case 'newline': echo '<br />'; break;
					case 'big': case '/big': case 'small': case '/small': 
						echo TAG_PREFIX;
						echo $match [6];
						echo TAG_SUFFIX;
						break;
					default:
						echo $match [2]; break;
					}
				} else
					writeExternLink ($match [3], substr ($match [4], 1), $status);
			} elseif ($args > 9 && ($pos = strpos ($match [2], '<?')) == 0 && is_int ($pos))
				writePlugin ($match [8], $match [9], $status);
			else
				echo $match [2];
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
	if (! isset ($param) || empty ($param))
		switch ($name){
		case U_TextAreaWidth: $param = $_POST ['textarea_width'] = $session->fUserTextareaWidth; break;
		case U_TextAreaHeight: $param = $session->fUserTextareaHeight; break;
		case U_MaxHits: $param = $session->fUserMaxHits; break;
		case U_PostingsPerPage: $param = $session->fUserPostingsPerPage; break;
		case U_Theme: $param = $session->fUserTheme;
		default: $session->trace (TC_Error, "getUserParam: unbek. Param: $name"); break;
		}
	return $param;
}
function getTextareaSize (&$session, &$width, &$height){
	if (! isset ($_POST['textarea_width']) || empty ($_POST['textarea_width']))
		$_POST['textarea_width'] = getUserParam ($session, U_TextAreaWidth, $width);
	$width = $_POST['textarea_width'];
	if ($width <= 0)
		$width = $_POST['textarea_width'] = 70;
	if (! isset ($_POST['textarea_height']) || empty ($_POST['textarea_height']))
		$_POST['textarea_height'] = getUserParam ($session, U_TextAreaHeight, $height);
	$height = $_POST['textarea_height'];
	if ($height <= 0)
		$height = $_POST['textarea_height'] = 10;
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
?>
