<?php
// gui.php: functions for Graphical User Interface
// $Id: gui.php,v 1.4 2004/05/27 22:44:32 hamatoma Exp $
/*
Diese Datei ist Teil von InfoBasar.
Copyright 2004 hamatoma@gmx.de München
InfoBasar ist freie Software. Du kannst es weitergeben oder verändern
unter den Bedingungen der GNU General Public Licence.
Näheres siehe Datei LICENCE.
InfoBasar sollte nützlich sein, es gibt aber absolut keine Garantie
der Funktionalität.
*/
// --- Allgemeine Funktionen --------------
function guiField ($name, $type, $text, $size, $maxlength, $special){
	echo "<input type=\"$type\" name=\"$name\"";
	if (! empty ($text))
		 echo " value=\"$text\"";
	if ($size > 0)
		echo " size=\"$size\"";
	if ($maxlength > 0)
		echo " maxlength=\"$maxlength\"";
	if (! empty ($special))
		echo ' ' . $special;
	echo ">";
}
function guiHiddenField ($name, $text) {
	guiField ($name, "hidden", $text, 0, 0, null);
}
function guiTextField ($name, $text, $size, $maxlength){
	guiField ($name, "text", $text, $size, $maxlength, null);
}
function guiPasswordField ($name, $text, $size, $maxlength){
	guiField ($name, "password", $text, $size, $maxlength, null);
}
function guiTextArea ($name, $content, $width, $height){
	echo "<textarea name=\"$name\" cols=\"$width\" rows=\"$height\">\n";
	echo $content;
	echo "</textarea>\n";
}
function guiButton ($name, $text){
	echo "<input class=\"wikiaction\" name=\"$name\" value=\"$text\" type=\"submit\">";
}
function guiLinkAsButton (&$session, $command, $text){
	guiInternLink ($session, $session->fPageName . '?action=' . $command,
		$text);
}
function guiRadioButton ($name, $text, $checked){
	guiField ($name, "radio", $text, 0, 0,
		isset ($checked) && $checked ? "checked" : "");
}
function guiCheckBox ($name, $text, $checked){
	guiField ($name, "checkbox", C_CHECKBOX_TRUE, 0, 0,
		isset ($checked) && $checked ? "checked" : "");
	echo htmlentities ($text) . " ";
}
function guiComboBox ($name, $options, $values, $ix_selected = 0) {
	echo '<select name="' . $name . '" size="1' // . count ($options)
		. "\">\n";
	foreach ($options as $ix => $text)
		echo '<option' . ($ix == $ix_selected ? ' selected' : '')
			. ($values ? ' value="' . $values[$ix] . '"' : '')
			. '>' . htmlentities ($text) . "\n";
	echo "</select>\n";
}
function guiLine ($width) {
	if (! isset ($width))
		$width = 2;
	echo '<hr style="width: 100%; height: ' . $width . "px;\">\n";
}
function guiStandardLinkString (&$session, $page) {
	$session->trace (TC_Gui3, 'guiStandardLinkString');
	$rc = null;
	switch ($page) {
	case P_Home: $header = 'Übersicht'; break;
	case P_ForumSearch: $header = 'Forumsuche'; break;
	case P_ForumHome: $header = 'Forenübersicht'; break;
	case P_Account: $header = 'Einstellungen'; break;
	case P_Search: $header = 'Wikisuche'; break;
	case P_LastChanges: $header = 'Letzte Änderungen'; break;
	case P_Start: $header = 'Persönliche Startseite'; break;
	case P_Login: $header = 'Neu anmelden'; break;
	case P_Info: $header = 'Information'; break;
	case P_NewWiki: $header = 'Neue Seite'; break;
	default: $header = null; break;
	}
	if ($header)
		$rc = guiInternLinkString ($session, $page, $header);
	return $rc;
}
function guiStandardLink (&$session, $page) {
	$session->trace (TC_Gui3, 'guiStandardLink');
	echo guiStandardLinkString ($session, $page);
}
function guiFinishBody (&$session, $param_no){
	$session->trace (TC_Gui2, 'guiFinishBody');
	if ($session->fPreformated)
		$session->trace (TC_Warning, PREFIX_Warning . '[/code] fehlt');
	if (! empty ($param_no)
		&& ($text = guiParam ($session, $param_no, null)) != null
		&& ! empty ($text))
		echo $text;
	else {
		guiLine (1);
		guiStandardLink ($session, P_Home);
		echo ' | ';
		guiStandardLink ($session, P_ForumHome);
		echo ' | ';
		guiStandardLink ($session, P_ForumSearch);
		echo ' | ';
		guiStandardLink ($session, P_Search);
		echo ' | ';
		guiStandardLink ($session, P_Account);
		echo ' | ';
		guiStandardLink ($session, P_LastChanges);
		echo "\n</body>\n</html>\n";
	}
}
function guiParagraph (&$session, $text, $convert){
	$session->trace (TC_Gui2, 'guiParagraph');
	echo '<p>';
	if ($convert)
		echo textToHtml ($text);
	else
		echo $text;
	echo "</p>\n";
}
function guiHeader (&$session, $title) {
	$session->trace (TC_Gui1, 'guiHeader');
	echo '<head>' . "\r\n";
	$value = dbGetText ($session, Th_Header);
	if (! empty ($value))
		echo $value;
	else
		echo '<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">' . "\n";
	$value = dbGetText ($session, Th_CSSFile);
	if (! empty ($value))
		echo '<link rel="stylesheet" type="text/css" href="' . $value . '">' . "\n";
	if (! empty ($title))
		echo "<title>$title</title>\n</head>\n<body>\n";
}
function guiHeadline (&$session, $level, $text) {
	$session->trace (TC_Gui2, 'guiHeadline');
	echo "<h$level>" . htmlentities ($text) . "</h$level>\n";
}
function guiStartForm (&$session) {
	$session->trace (TC_Gui2, 'guiStartForm');
	echo '<form name="form" action="' . C_ScriptName . '" method="post">' . "\n";
}
function guiStartFormGet (&$session) {
	$session->trace (TC_Gui2, 'guiStartForm');
	echo '<form name="form" action="' . C_ScriptName . '" method="get">' . "\n";
}
function guiFinishForm (&$session){
	$session->trace (TC_Gui2, 'guiFinishForm');
	if (! $session->fFormExists) {
		$session->setFormExists(true);
	}
	echo "</form>\n";
}
function guiExternLinkString(&$session, $link, $text) {
	$session->trace (TC_Gui2, 'guiExternLinkString');
	if (empty ($text))
		$text = $link;
	return "<a href=\"$link\">" . htmlToText ($text) . "</a>\n";
}

function guiExternLink (&$session, $link, $text) {
	$session->trace (TC_Gui2, 'guiExternLink');
	echo "<a href=\"$link\">";
	echo htmlToText ($text);
	echo "</a>\n";
}
function guiInternLink (&$session, $link, $text) {
	$session->trace (TC_Gui2, 'guiInternLink');
	if (empty ($text))
		$text = $link;
	guiExternLink ($session, $session->fScriptURL . '/' . $link, $text);
}
function guiInternLinkString (&$session, $link, $text) {
	$session->trace (TC_Gui2, 'guiInternLinkString');
	if (empty ($text))
		$text = $link;
	return guiExternLinkString ($session, $session->fScriptURL . '/' . $link, $text);
}
function guiStaticDataLink (&$session, $subdir, $link, $text) {
	$session->trace (TC_Gui2, 'guiStaticDataLink');
	if (empty ($text))
		$text = $link;
	guiExternLink ($session, 
		$session->fScriptBase . '/' . $subdir . $link, $text);
}
function guiStaticDataLinkString (&$session, $subdir, $link, $text) {
	$session->trace (TC_Gui2, 'guiStaticDataLinkString');
	if (empty ($text))
		$text = $link;
	return guiExternLinkString ($session, 
		$session->fScriptBase . '/' . $subdir . $link, $text);
}

function guiAuthorLink (&$session, $author) {
	echo htmlentities ($author);
}
function guiPageReference (&$session, $name, $text) {
	$session->trace (TC_Gui1, 'guiPageReference');
	guiInternLink ($session, '.' . $name, '?' . $text);
}
function guiParam (&$session, $pos, $default) {
	$rc = dbGetText ($session, $pos);
	if (empty ($rc))
		$rc = $default;
	else
		$rc = $session->replaceMacrosNoHTML ($rc);
	return $rc;
}
function guiStandardHeader (&$session, $title, $pos_header, $pos_body){
	$session->trace (TC_Gui1, 'guiStandardHeader');
	$session->setPageTitle ($title);
	guiHeader ($session, null);
	$header = dbGetText ($session, $pos_header);
	if (empty ($header))
		echo '<head><title>' . htmlentities ($title) . '</title></head>' . "\n";
	else
		echo $session->replaceMacrosNoHTML ($header);
	if ($pos_body) {
		$header = dbGetText ($session, $pos_body);
		if (empty ($header))
			$pos_body = null;
		else
			echo $session->replaceMacrosNoHTML ($header);
	}
	if (! $pos_body)
		echo '<body><h1>' . $title . '</h1>' . "\n";
}
function guiStandardBodyEnd (&$session, $pos) {
	$session->trace (TC_Gui1, 'guiStandardBodyEnd');
	$html = guiParam ($session, $pos, null);
	if (! empty ($html))
		echo $session->replaceMacrosNoHTML ($html);
	else {
		if ($pos !=  Th_LoginBodyEnd){
			guiLine (1);
			guiStandardLink ($session, P_Home);
			echo ' | ';
			guiStandardLink ($session, P_ForumHome);
			echo ' | ';
			guiStandardLink ($session, P_ForumSearch);
			echo ' | ';
			guiStandardLink ($session, P_Search);
			echo ' | ';
			guiStandardLink ($session, P_Account);
			echo ' | ';
			guiStandardLink ($session, P_LastChanges);
			echo '</body>';
		}
	}
	echo "\n" . '</body></html>' . "\n";
}
// --------------------------------
function guiBacklinks ($session, $page_name) {
	$session->trace (TC_Gui1, 'pluginBacklinks');
	$name = $page_name;
	$condition = '(text like ' . dbSqlString ($session, '%' . $name . '%');
	if (normWikiName ($session, $name))
		$condition .= ' or text like '
			. dbSqlString ($session, '%' . $name . '%');
	$condition .= ') and replacedby is NULL';
	if (! ($ids = dbIdList2 ($session, T_Text, 'distinct page',
		$condition)))
		guiParagraph ($session, 'keine Verweise auf ' . $page_name
			. ' gefunden', false);
	else {
		echo '<p>Es gibt folgende Verweise auf ';
		guiInternLink ($session, $name, $page_name);
		echo '</p>' . "\n" . '<ulist>';
		foreach ($ids as $ii => $id) {
			$page = dbGetRecordById ($session, T_Page, $id, 'name');
			echo '<li>';
			guiInternLink ($session, $page [0], null);
			echo '</li>';
		}
		echo '</ulist>' . "\n";
	}
}

function guiFormatPage (&$session, $mime, $content) {
	$session->trace (TC_Gui2, 'guiFormatPage');
	switch ($mime) {
	case TT_Wiki:
	case M_Wiki: wikiToHtml ($session, $content); break;
	case TT_HTML:
	case M_HTML: echo $content; break;
	default:
		wikiToHtml ($session, $content); break;
	}
}
function guiShowTable (&$session, $headline, $header, $query, $always,
		$table_properties) {
	$session->trace (TC_Gui1, 'guiShowTable');
	$has_table = false;
	$row = dbFirstRecord ($session, $query);
	if ($always || $row) {
		$has_table = true;
		if ($headline)
			echo $headline . "\n";
		echo "<table $table_properties>\n";
		if ($header) {
			echo '<tr>';
			for ($ii = 0; $ii < count ($header); $ii++)
				echo '<td><b>' . htmlentities ($header [$ii]) . '</b></td>';
			echo "</tr>\n";
		}
	}
	while ($row) {
		echo '<tr>';
		for ($ii = 0; $ii < count ($row); $ii++)
			echo '<td>' . htmlentities ($row [$ii]) . '</td>';
		echo "</tr>\n";
		$row = dbNextRecord ($session);
	}
	if ($has_table)
		echo "</table>\n";
}
function guiThreadPageLink (&$session, $thread_id, $page_no, $text) {
	$session->trace (TC_Gui3, 'guiThreadPageLink');
	guiInternLink ($session, P_Thread . '?action=' . A_ShowThread
		. '&thread_id=' . $thread_id . '&page_no=' . $page_no, $text);
}
function guiPageLinks ($session, $prefix, $page_no, $pages){
	$session->trace (TC_Gui2, 'guiPageLinks');
	if ($pages != 1) {
		if ($page_no == 1)
			echo 'Seite 1';
		else
			guiInternLink ($session, $prefix . '&page_no=1', 'Seite 1');
		$min = $page_no < 5 ? 2 : $page_no - 2;
		$max = $page_no > $pages - 3 ? $pages : $page_no + 2;
		for ($ii = $min; $ii <= $max; $ii++) {
			echo $ii == $min && $min > 2 ? '...' : ', ';
			if ($ii == $page_no)
				echo $ii;
			else
				guiInternLink ($session, $prefix . '&page_no=' . $ii, $ii);
		}
		if ($max < $pages) {
			echo ($max < $pages - 1) ? '...' : ', ';
			guiInternLink ($session, $prefix . '&page_no=' . $pages, $pages);
		}
	}
}
function guiShowPage (&$session, $mime, $title, $content) {
	$session->trace (TC_Gui1, 'guiShowPage');
	guiHeader ($session, $title);
	guiFormatPage ($session, $mime, $content);
	guiFinishBody ($session, null);
}
// --- Erstellen von Ausgabeseiten ---------------------
function guiShowPageById (&$session, $page, $text_id) {
	$session->trace (TC_Gui1, 'guiShowPageById');
	list ($name, $type, $readgroup) = dbGetRecordById ($session,
		T_Page, $page, 'name,type,readgroup');
	if (! empty ($text_id) && $text_id > 0)
		$count_newer = dbSingleValue ($session, 'select count(id) from '
			. dbTable ($session, T_Text) . ' where page=' . $page . ' and id>'
			. $text_id);
	else {
		$count_newer = 0;
		list ($text_id) = dbGetRecordByClause ($session, T_Text,
			'max(id)', 'page=' . $page);
	}
	list ($content, $created_at, $created_by) = dbGetRecordById ($session,
		T_Text, $text_id, 'text,createdat,createdby');
	$session->SetPageData ($session->fPageName, $created_at, $created_by);
	if ($type == TT_Wiki)
		guiStandardHeader ($session, $session->fPageName, Th_HeaderWiki,
			Th_BodyStartWiki);
	else
		guiStandardHeader ($session, $session->fPageName, Th_HeaderHTML,
			Th_BodyStartHTML);
	if ($count_newer > 0)
		if ($count_newer == 1)
		guiParagraph ($session, 'Achtung: es existier'
			. ($count_newer == 1 ? 't eine neuere Version'
			: ('en ' . $count_newer . ' neuere Versionen')), false);
	guiFormatPage ($session, $type, $content);
	guiStandardBodyEnd ($session, $type == TT_Wiki ? Th_BodyEndWiki : Th_BodyEndHTML);
}
function guiShowCurrentPage (&$session){
	if ( ($page = dbPageId ($session, $session->fPageName)) > 0)
		guiShowPageById ($session, $page, null);
	else
		guiHome ($session);
}
function guiEditPage (&$session, $message) {
	global $edit_preview, $edit_pageid, $edit_textid, $edit_content,
		$edit_changedat, $edit_changedby, $edit_texttype, $last_pagename;

	$session->trace (TC_Gui1, 'guiEditPage');
	if (! isset ($last_pagename)) {
		$last_pagename = $session->fPageName;
	}
	if (isset ($edit_content))
		$edit_content = textAreaToWiki ($session, $edit_content);

	if (! isset ($edit_pageid)) {
		list ($edit_pageid, $edit_texttype) = dbGetRecordByClause ($session, T_Page,
			'id,type', 'name=' . dbSqlString ($session, $session->fPageName));
		$edit_textid = dbGetValueByClause ($session, T_Text,
			'max(id)', 'page=' . $edit_pageid);
		list ($edit_content, $edit_changedat, $edit_changedby)
			= dbGetRecordById ($session, T_Text, $edit_textid,
				'text,createdat,createdby');
	}
	$session->setPageData ($last_pagename, $edit_changedat,
		$edit_changedby);
	getUserParam ($session, U_TextAreaWidth, $textarea_width);
	getUserParam ($session, U_TextAreaHeight, $textarea_height);
	if ($edit_texttype == TT_Wiki)
		guiStandardHeader ($session, $session->fPageName . ' (in Bearbeitung)',
			Th_EditHeaderWiki, Th_EditStartWiki);
	else
		guiStandardHeader ($session, $session->fPageName . ' (in Bearbeitung)',
			Th_EditHeaderHTML, Th_EditStartHTML);
	if (isset ($edit_preview)) {
		echo guiParam ($session, Th_PreviewStart, '<h1>Vorschau von '
			. $session->fPageName
			. '</h1><p>Warnung: Der Text ist noch nicht gesichtert!</p>');
		guiFormatPage ($session, $edit_texttype, $edit_content);
		echo guiParam ($session, Th_PreviewEnd, '<h1>Ende der Vorschau</h1>');
	}
	guiStartForm ($session, 'edit');
	guiHiddenField ('edit_texttype', $edit_texttype);
	guiHiddenField ('last_pagename', $last_pagename);
	guiHiddenField ('edit_pageid', $edit_pageid);
	guiHiddenField ('edit_textid', $edit_textid);
	guiHiddenField ('edit_changedat', $edit_changedat);
	guiHiddenField ('edit_changedby', $edit_changedby);
	echo "<table border=\"0\">\n";
	if (! empty ($message))
		echo '<td><strong>' . htmlentities ($message) . '</strong></tr>' . "\n";
	echo "</td></tr>\n<tr><td>";
	guiTextArea ("edit_content", $edit_content, $textarea_width,
		$textarea_height);
	echo '</td></tr>' . "\n" . '<tr><td><table border="0" width="100%"><tr><td>';
	guiButton ('edit_preview', 'Vorschau');
	echo ' | '; guiButton ('edit_save', 'Speichern');
	echo ' | '; guiButton ('edit_cancel', 'Verwerfen');
	echo '</td><td style="text-align: right;">Breite: ';
	guiTextField ("textarea_width", $textarea_width, 3, 3);
	echo " H&ouml;he: ";
	guiTextField ("textarea_height", $textarea_height, 3, 3);
	echo "</td></tr>\n</table>\n</td></tr></table>\n";
	guiFinishForm ($session, $session);
	guiStandardBodyEnd ($session,
		$edit_texttype == TT_Wiki ? Th_EditEndWiki : Th_EditEndHTML);
}
function guiEditPageAnswerSave (&$session)
{
	global $edit_pageid, $edit_textid, $edit_content,
		$edit_changedat, $edit_changedby, $edit_texttype;

	$session->trace (TC_Gui1, 'guiEditPageAnswerSave');
	$edit_content = textAreaToWiki ($session, $edit_content);
	$new_textid = dbGetValueByClause ($session, T_Text,
		'max(id)', 'page=' . $edit_pageid);
	$message = '';
	if ($new_textid > $edit_textid)
		$message = "+++ Warnung: Seite wurde inzwischen geändert! Änderungen wurden überschrieben! $new_textid / $edit_textid";
	$date = dbSqlDateTime ($session, time ());
	$id = dbInsert ($session, T_Text,
		'page,type,createdat,changedat,createdby,text',
		$edit_pageid . ',' . dbSqlString ($session, $edit_texttype)
			. ",$date,$date," . dbSqlString ($session, $session->fUserName)
			. ',' . dbSqlString ($session, $edit_content));
	dbUpdate ($session, T_Text, $new_textid, 'replacedby=' . $id . ',');
	unset ($edit_save);
	if (empty ($message))
		guiShowPageById ($session, $edit_pageid, null);
	else
		guiEditPage ($session, $message, $message);
}

function guiLogin (&$session, $message) {
	global $login_user, $login_email;
	guiStandardHeader ($session, "Anmeldung f&uuml;r den InfoBasar", Th_LoginHeader,
		null);
	guiStartForm ($session, "login");
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
function guiLoginAnswer (&$session) {
	$session->trace (TC_Gui1, 'guiLoginAnswer');
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
		guiLogin ($session, $message);
	} else {
		$rc = dbCheckUser ($session, $login_user, $login_code);
		if (! empty ($rc))
			guiLogin ($session, $rc);
		else {
			setLoginCookie ($session, $login_user, $login_code);
			ob_flush (); // ob_start() -->  index.php
			guiCustomStart ($session);
		}
	}
}	
function guiAccount (&$session, $message) {
	global $account_user, $account_code, $account_code2, $account_rights,
		$account_email,
		$account_locked, $account_user2, $account_theme, $account_width,
		$account_height, $account_maxhits, $account_postingsperpage,
		$account_startpage, $account_startpageoffer, $account_threadsperpage;
	global $login_user, $login_passw;
	$session->trace (TC_Gui1, 'guiAccount');
	$reload = false;
	if (empty ($account_user)) {
		$account_user = $session->fUserName;
		$reload = true;
	}
	if (! empty ($account_user2) && $account_user != $account_user2) {
		$account_user = $account_user2;
		$reload = true;
	}
	if ($reload){
		list ($id, $account_rights, $account_locked, $account_width,
			$account_height, $account_maxhits, $account_postingsperpage,
			$account_theme, $account_threadsperpage, $account_startpage,
			$account_email)
			= dbGetRecordByClause ($session, T_User,
			'id,rights,locked,width,height,maxhits,postingsperpage,theme,threadsperpage,startpage,email',
			'name=' . dbSqlString ($session, $account_user));
	}
	guiStandardHeader ($session, 'Einstellungen f&uuml;r ' . $account_user,
		Th_StandardHeader, Th_StandardBodyStart);
	if (! empty ($message))
		guiParagraph ($session, $message, true);
	guiStartForm ($session, 'account');
	echo "<table border=\"0\">\n<tr><td>Benutzername:</td><td>";
	guiHiddenField ('account_user', $account_user);
	guiHeadline ($session, 2, $account_user);
	echo "</td></tr>\n<tr><td>Passwort:</td><td>";
	guiPasswordField ("account_code", "", 64, 32);
	echo "</td></tr>\n<tr><td>Wiederholung:</td><td>";
	guiPasswordField ("account_code2", "", 64, 32);
	echo "</td></tr>\n<tr><td>EMail:</td><td>";
	guiTextField ("account_email", $account_email, 64, 64);
	echo "</td></tr>\n<tr><td>Rechte:</td><td>";
	guiTextField ("account_rights", $account_rights, 64, 64);
	echo "</td></tr>\n<tr><td>Gesperrt:</td><td>";
	guiCheckBox ("account_locked", "Gesperrt", $account_locked);
	echo "</td></tr>\n<tr><td>Design:</td><td>";
	dbGetThemes ($session, $theme_names, $theme_numbers);
	guiComboBox ('account_theme', $theme_names, $theme_numbers, 
		array_search ($account_theme, $theme_numbers));
	echo "</td></tr>\n<tr><td>Eingabefeldbreite:</td><td>";
	guiTextField ("account_width", $account_width, 64, 3);
	echo "</td></tr>\n<tr><td>Eingabefeldh&ouml;he:</td><td>";
	guiTextField ("account_height", $account_height, 64, 3);
	echo "</td></tr>\n<tr><td>Zahl Suchergebnisse:</td><td>";
	guiTextField ("account_maxhits", $account_maxhits, 64, 3);
	echo "</td></tr>\n<tr><td>Forumsbeitr&auml;ge je Seite:</td><td>";
	guiTextField ("account_postingsperpage", $account_postingsperpage, 64, 3);
	echo "</td></tr>\n<tr><td>Themen je Seite:</td><td>";
	guiTextField ("account_threadsperpage", $account_threadsperpage, 64, 3);
	echo "</td></tr>\n<tr><td>Startseite:</td><td>";
	$names = array ('WikiSeite:', 'Übersicht', 'Forenübersicht', 'Forumsuche', 'Einstellungen',
			'Wikisuche', 'Letze Änderungen', 'StartSeite', 'Hilfe');
	$values = array ('', P_Home, P_ForumHome, P_ForumSearch, P_Account, 
			P_Search, P_LastChanges, 'StartSeite', 'Hilfe');
	if ( ($pos = strpos ($account_startpage, '!')) == 0 && is_int ($pos))
		$ix = array_search ($account_startpage, $values);
	else
		$ix = 0;
	guiComboBox ('account_startpageoffer', $names, $values, $ix);
	echo ' ';
	guiTextField ("account_startpage", $account_startpage, 32, 128);
	echo "</td></tr>\n<tr><td></td><td>";
	guiButton ("account_change", "&Auml;ndern");
	if ($session->hasRight ('uadd') || $session->hasRight ('uadd')){
		echo "</td></tr><tr></tr>\n<tr><td>Name:</td><td>";
		guiTextField ("account_user2", $account_user2, 32, 32);
		echo "</td></tr>\n<tr><td></td><td>";
		if ($session->hasRight ('umod'))
			guiButton ("account_other", "Benutzer wechseln");
		if ($session->hasRight ('uadd')){
			echo " "; guiButton ("account_new", "Neu");
		}
	}
	echo "</td></tr>\n</table>\n";
	guiFinishForm ($session, $session);
	guiStandardBodyEnd ($session, Th_StandardBodyEnd);
}
function guiAccountAnswer(&$session, $user) {
	global $account_user, $account_code, $account_code2, $account_email, $account_rights,
		$account_locked, $account_new, $account_change, $account_name,
		$account_other, $account_user2,  $account_theme,
		$account_width, $account_height, $account_maxhits,
		$account_postingsperpage, $account_threadsperpage, 
		$account_startpage, $account_startpageoffer;

	$session->trace (TC_Gui1, 'guiAccountAnswer');
	$message = '';
	$code = encryptPassword ($session, $account_user, $account_code);
	$locked = dbSqlString ($session, ! empty ($account_locked));
	if (! empty ($account_startpageoffer))
		$account_startpage = $account_startpageoffer;
	if (isset ($account_new)) {
		if ($account_user2 == '')
			$message = '+++ Kein Benutzername angegeben';
		elseif (dbGetValueByClause ($session, T_User,
			'count(*)', 'name=' + dbSqlString ($session, $account_user)) > 0)
			$message = '+++ Name schon vorhanden: ' + $account_user2;
		else {
			dbUserAdd ($session, $account_user2, $code, $session->fUserRights,
				dbSqlString ($session, false), $account_theme, $account_width, $account_height,
				$account_maxhits, $account_postingsperpage,
				$account_threadsperpage, $account_startpage, $account_email);
			$message = "Benutzer $account_user2 wurde angelegt";
		}
	} elseif (isset ($account_change)) {
		if (! empty ($account_code) && $account_code <> $account_code2)
			$message = '+++ Passwort stimmt mit Wiederholung nicht überein';
		elseif (! ($uid = dbUserId ($session, $account_user)) || empty ($uid))
			$message = '+++ unbekannter Benutzer: ' . $account_name;
		else {
			if (empty ($account_theme))
				$account_theme = Theme_Standard;
			$what = 'rights=' . dbSqlString ($session, $account_rights) . ',locked='
				. $locked . ',';
			if (! empty ($account_code))
				$what .= 'code=' . dbSqlString ($session, $code) . ",";
			$what .= "theme=$account_theme,width=$account_width,"
			. 'height=' . (0 + $account_height)
			. ',maxhits=' . (0 + $account_maxhits)
			. ',postingsperpage=' . (0 + $account_postingsperpage)
			. ',threadsperpage=' . (0 + $account_threadsperpage)
			. ',startpage=' . dbSqlString ($session, $account_startpage)
			. ',email=' . dbSqlString ($session, $account_email)
			 . ',';
			dbUpdate ($session, T_User, $uid, $what);
			$message = 'Daten für ' . $account_user . ' (' . $uid
				. ') wurden geändert';
		}
	} elseif ($account_other) {
		if (empty ($account_user2))
			$message = '+++ kein Benutzername angegeben';
		elseif (! dbUserId ($session, $account_user2))
			$message = '+++ Unbekannter Benutzer: ' . $account_user2;
	} else {
		$message = 'keine Änderung';
	}
	guiAccount ($session, $message);
}
function guiHome (&$session) {
	global $session_id, $session_user;

	$session->trace (TC_Gui1, 'guiHome');
	if ( ($text = guiParam ($session, Th_Overview, null)) != null
		&& ! empty ($text))
		echo $text;
	else {
		guiStandardHeader ($session, 'Übersicht', Th_StandardHeader,
			Th_StandardBodyStart);
		guiHeadline ($session, 1, 'Willkommen ' . $session->fUserName);
		echo '<table border="0"><tr><td>';
		guiStandardLink ($session, P_Account);
		echo '</td><td>Einstellungen</td></tr>' . "\n" . '<tr><td>';
		guiStandardLink ($session, P_Login);
		echo '</td><td>Abmelden (oder neu anmelden)</td></tr>'  . "\n" . '<tr><td>';
		# echo '<p>';	guiStandardLink ($session, P_NewPage); echo '</p>';
		# echo '<p>';	guiStandardLink ($session, P_ModifyPage); echo '</p>';
		guiStandardLink ($session, P_Search);
		echo '</td><td>Suche auf den Wiki-Seiten</td></tr>'  . "\n" . '<tr><td>';
		guiStandardLink ($session, P_ForumHome);
		echo '</td><td>Foren&uuml;bersicht</td></tr>' . '<tr><td>';
		guiStandardLink ($session, P_ForumSearch);
		echo '</td><td>Suche nach einem Forumsbeitrag</td></tr>'  . "\n" . '<tr><td>';
		guiStandardLink ($session, P_Start);
		echo '</td><td>Pers&ouml;nliche Startseite</td></tr>'  . "\n" . '<tr><td>';
		guiInternLink ($session, 'StartSeite', 'StartSeite');
		echo '</td><td>Wiki-Startseite</td></tr>'  . "\n" . '<tr><td>';
		guiStandardLink ($session, P_NewWiki);
		echo '</td><td>Neue Wikiseite</td></tr>'  . "\n" . '<tr><td>';
		guiStandardLink ($session, P_LastChanges);
		echo '</td><td>Neueste &Auml;nderungen</td></tr>'  . "\n" . '<tr><td>';
		guiStandardLink ($session, P_Info);
		echo '</td><td>Information &uuml;ber den InfoBasar</td></tr></table>'  . "\n" ;
		// echo 'Session-Id: ' . $session_id . ' User: ' . $session_user . '<br>';
		guiStandardBodyEnd ($session, Th_StandardBodyEnd);
	}
}
function guiAlterPage (&$session, $mode, $message, $message2, $type = M_Undef){
	global $alterpage_name, $alterpage_content, $textarea_width,
		$textarea_height, $alterpage_content, $alterpage_mime,
		$alterpage_lastmode, $alterpage_preview;
	$session->trace (TC_Gui1, 'guiAlterPage');
	$session->trace (TC_X, 'guiAlterPage:' . $alterpage_mime);
	if ($type != M_Undef)
		$alterpage_mime = $type;
	if ($alterpage_mime == M_Wiki)
		guiStandardHeader ($session, $mode != C_Change
			? 'Neue Seite eintragen' : 'Seite ändern', Th_EditHeaderWiki,
			Th_EditStartWiki);
	else
		guiStandardHeader ($session, $mode != C_Change
			? 'Neue Seite eintragen' : 'Seite ändern', Th_EditHeaderHTML,
			Th_EditStartHTML);

	getUserParam ($session, U_TextAreaWidth, $textarea_width);
	getUserParam ($session, U_TextAreaHeight, $textarea_height);
	if (! empty ($message))
		guiParagraph ($session, $message, true);
	if (! empty ($message2))
		guiParagraph ($session, $message2, true);

	if (isset ($alterpage_preview)) {
		guiHeadline ($session, 1, 'Vorschau');
		guiFormatPage ($session, $alterpage_mime, $alterpage_content);
		guiLine (1);
	}

	guiStartForm ($session, 'alterpage');
	guiHiddenField ('alterpage_lastmode', $mode);
	echo "<table border=\"0\">\n<tr><td>Name:</td><td>";
	guiTextField ('alterpage_name', $alterpage_name, 64, 64);
	if ($mode == C_Change)
		guiButton ('alterpage_changepage', 'Seite laden');
	if ($type == M_Wiki)
		;
	echo "</td></tr>\n<tr><td>Typ:</td><td>";
	if ($mode == C_New && $type == M_Undef)
		guiComboBox ('alterpage_mime', array (M_Wiki, M_HTML), null);
	else {
		echo $alterpage_mime;
		guiHiddenField ($session, 'alterpage_mime', $alterpage_mime);
	}
	echo "</td></tr>\n<tr><td>Inhalt:</td><td>";
	guiTextArea ("alterpage_content", $alterpage_content,
		$textarea_width, $textarea_height);

	echo "</td></tr>\n<tr><td></td><td>";
	if ($mode != C_Change)
		guiButton ('alterpage_insert', 'Eintragen');
	else
		guiButton ('alterpage_changecontent', 'Ändern');
	echo " "; guiButton ('alterpage_preview', 'Vorschau');
	echo " "; guiButton ('alterpage_cancel', 'Abbrechen');
	echo "<br /><br />Eingabefeld: Breite: ";
	guiTextField ("alterpage_width", $textarea_width, 3, 3);
	echo " Höhe: ";
	guiTextField ("alterpage_height", $textarea_height, 3, 3);
	echo "</td></tr>\n</table>\n";
	guiFinishForm ($session, $session);
	guiStandardBodyEnd ($session, Th_StandardBodyEnd);
}
function guiNewPageReference (&$session) {
	global $alterpage_name, $alterpage_content, $textarea_width, $alterpage_mime,
		 $textarea_height, $alterpage_content, $alterpage_insert;
	$session->trace (TC_Gui1, 'guiNewPageReference');
	$alterpage_name = substr ($session->fPageName, 1);
	if ( ($page = dbPageId ($session, $alterpage_name)) > 0)
		guiShowPageById ($session, $page, null);
	else {
		$alterpage_content = strpos ($alterpage_name, 'ategorie') == 1
			? '<?plugin BackLinks?>' : "Beschreibung der Seite $alterpage_name\n";
		$alterpage_mime = M_Wiki;
		guiAlterPage ($session, C_Auto, 'neue Seite', '');
	}
}

function guiAlterPageAnswer (&$session, $mode){
	global $alterpage_name, $alterpage_content, $textarea_width,
		 $textarea_height, $alterpage_content, $alterpage_insert,
		 $alterpage_preview, $alterpage_lastmode, $alterpage_mime;
	$session->trace (TC_Gui1, 'guiAlterPageAnswer');
	$alterpage_name = makePageName ($alterpage_name);
	if ($mode == C_LastMode)
		$mode = $alterpage_lastmode;
	$len = strlen ($alterpage_content);
	$message = null;
	$alterpage_content = textAreaToWiki ($session, $alterpage_content);
	$alterpage_content = extractHtmlBody ($alterpage_content);
	if (isset ($alterpage_preview))
		;
	elseif (empty ($alterpage_name))
		$message = '+++ kein Seitenname angegeben';
	elseif (dbSingleValue ($session,
			'select count(*) from ' . dbTable ($session, T_Page)
			. ' where name=' . dbSqlString ($session, $alterpage_name)) > 0)
		$message = '+++ Seite existiert schon: ' . $alterpage_name;
	else {
		$read_group = 0;
		$write_group = 0;
		if (empty ($alterpage_mime))
			$alterpage_mime = TT_Wiki;
		$page = dbInsert ($session, T_Page,
			'name,type,createdat,changedat,readgroup,writegroup',
			dbSqlString ($session, $alterpage_name) .',' . dbSqlString ($session, $alterpage_mime)
			. ',now(),now(),' . $read_group . ',' . $write_group);
		dbInsert ($session, T_Text, 'page,type,text,createdby',
			$page
			. "," . dbSqlString ($session, $alterpage_mime)
			. ',' . dbSqlString ($session, $alterpage_content)
			. ',' . dbSqlString ($session, $session->fUserName));
	}
	$message2 = $len == strlen ($alterpage_content)
		? '' : 'Es wurde der Rumpf (body) extrahiert.';
	if ($message != null || isset ($alterpage_preview))
		guiAlterPage ($session, $mode, $message, $message2, $alterpage_mime);
	else
		guiShowPage ($session, $alterpage_mime,
			$alterpage_name, $alterpage_name);
}

function guiAlterPageAnswerChangePage (&$session){
	global $alterpage_name, $alterpage_content, $textarea_width,
		 $textarea_height, $alterpage_content, $alterpage_mime,
		 $alterpage_changepage, $alterpage_changecontent,
		 $alterpage_previe;
	$session->trace (TC_Gui1, 'guiAlterPageAnswerChangePage');
	$alterpage_name = makePageName ($alterpage_name);

	$len = strlen ($alterpage_content);
	$alterpage_content = extractHtmlBody ($alterpage_content);
	$message = $message2 = null;
	if (empty ($alterpage_name))
		$message = '+++ kein Seitenname angegeben';
	if ( ($page_id = dbPageId ($session, $alterpage_name)) <= 0)
		$message = '+++ Seite nicht gefunden: ' . $alterpage_name;
	else {
		$readgroup = $writegroup = 0;
		dbUpdate ($session, T_Page, $page_id,
			"changedat=now(),readgroup=$readgroup,writegroup=$writegroup,");
		dbInsert ($session, T_Text, 'page,type,text,createdby',
			$page_id
			. "," . dbSqlString ($session, $alterpage_mime)
			. ',' . dbSqlString ($session, $alterpage_content)
			. ',' . dbSqlString ($session, $session->fUserName));
		$message = 'Seite ' . $alterpage_name . ' wurde geändert';
	}
	if ($len != strlen ($alterpage_content))
		$message2 = 'Es wurde der Rumpf (body) extrahiert.';
	if ($message != null)
		guiAlterPage ($session, C_Change, $message, $message2);
	else
		guiShowPage ($session, $alterpage_name);
}
function guiSearch (&$session, $message){
	global $search_titletext, $search_maxhits, $search_bodytext, $last_pagename,
		$search_title, $search_body;
	$session->trace (TC_Gui1, 'guiSearch');
	if (! isset ($last_pagename))
		$last_pagename = $session->fPageName;
	getUserParam ($session, U_MaxHits, $search_maxhits);
	guiStandardHeader ($session, 'Suchen auf den Wiki-Seiten',
		Th_SearchHeader, Th_SearchBodyStart);
	if (isset ($search_title) || isset ($search_body))
		guiSearchResults ($session);
	guiParagraph ($session, 'Hinweis: vorl&auml;ufig nur ein Suchbegriff m&ouml;glich', false);
	guiStartForm ($session, 'search');
	guiHiddenField ('last_pagename', $last_pagename);
	echo "<table border=\"0\">\n<tr><td>Titel:</td><td>";
	guiTextField ('search_titletext', $search_titletext, 32, 64);
	echo " "; guiButton ('search_title', "Suchen");
	echo "</td></tr>\n<tr><td>Beitrag:</td><td>";
	guiTextField ('search_bodytext', $search_bodytext, 32, 64);
	echo " "; guiButton ('search_body', "Suchen");
	echo "</td></tr>\n<tr><td>Maximale Trefferzahl:</td><td>";
	guiTextField ("search_maxhits", $search_maxhits, 10, 10);
	echo "</td></tr>\n</table>\n";
	guiFinishForm ($session, $session);
	guiStandardBodyEnd ($session, Th_SearchBodyEnd);
}
function guiSearchResults (&$session){
	global $search_titletext, $search_title, $search_maxhits,
		$search_bodytext, $search_body;

	$session->trace (TC_Gui1, 'guiSearchAnswer');
	if (isset ($search_title)) {
		if (empty ($search_titletext))
			guiParagraph ($session, $session, '+++ kein Seitentitel angegeben', false);
		$row = dbFirstRecord ($session,
				'select name,type from ' . dbTable ($session, T_Page)
				. ' where name like ' . dbSqlString ($session, "%$search_titletext%")
				. " limit $search_maxhits");
		if (! $row)
			guiParagraph ($session, '+++ keine passenden Seiten gefunden', false);
		else {
			echo '<table border="1"><tr><td>Seite:</td><td>Typ:</td></tr>';
			while ($row) {
				echo "\n<tr><td>"
					. guiInternLinkString ($session, $row[0], $row[0])
					. "</td><td>$row[1]</td></tr>";
				$row = dbNextRecord ($session);
			}
			echo "\n</table>\n";
		}
	} else {
		if (empty ($search_bodytext))
			guiParagraph ($session, '+++ kein Suchtext angegeben');
		else {
			$row = dbFirstRecord ($session,
					'select page,text,createdby,createdat from '
					. dbTable ($session, T_Text)
					. ' where replacedby is null and text like '
					. dbSqlString ($session, "%$search_bodytext%")
					. " limit $search_maxhits");
			if (! $row)
				guiParagraph ($session, '+++ keine passende Seiten gefunden', false);
			else {
				echo '<table border="1"><tr><td>Seite:</td><td>Typ:</td>'
				. '<td>von</td><td>Letzte &Auml;nderung</td><td>Fundstelle</td></tr>';
				while ($row) {
					$pagerecord = dbGetRecordById ($session, T_Page, $row[0],
						'name,type');
					echo "\n<tr><td>"
						. guiInternLinkString ($session, $pagerecord[0],
							$pagerecord[0]) . '</td><td>'
						. $pagerecord [1] . '</td><td>'
						. $row [2] . '</td><td>'
						. htmlentities ($row [3]) . '</td><td>'
						. findTextInLine ($row [1], $search_bodytext, 3)
						. '</td><tr>' . "\n";
					$row = dbNextRecord ($session);
				}
				echo "\n</table>\n";
			}
		}
	}
}
function guiForumSearch (&$session, $message){
	global $forum_titletext, $search_maxhits, $forum_bodytext;

	$session->trace (TC_Gui1, 'guiForumSearch');
	getUserParam ($session, U_MaxHits, $search_maxhits);
	guiStandardHeader ($session, 'Forumsuche', Th_SearchHeader, Th_SearchBodyStart);
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	guiParagraph ($session, 'Hinweis: vorl&auml;ufig nur ein Suchbegriff m&ouml;glich', false);
	guiStartForm ($session, 'search');
	echo "<table border=\"0\">\n<tr><td>Im Titel:</td><td>";
	guiTextField ('forum_titletext', $forum_titletext, 32, 64);
	echo " "; guiButton ('forum_title', "Suchen");
	echo "</td></tr>\n<tr><td>Im Beitrag:</td><td>";
	guiTextField ('forum_bodytext', $forum_bodytext, 32, 64);
	echo " "; guiButton ('forum_body', "Suchen");
	echo "</td></tr>\n<tr><td>Maximale Trefferzahl:</td><td>";
	guiTextField ("search_maxhits", $search_maxhits, 10, 10);
	echo "</td></tr>\n</table>\n";
	guiFinishForm ($session, $session);
	guiStandardBodyEnd ($session, Th_SearchBodyEnd);
}
function forumSearch (&$session, $with_text, $condition, $to_find) {
	global $search_maxhits;
	$session->trace (TC_Gui2, 'forumSearch');
	$rc = '';
	$row = dbFirstRecord ($session,
			'select id,subject,author,createdat,top,forum'
			. ($with_text ? ',text from ' : ' from ')
			. dbTable ($session, T_Posting)
			. ' where ' . $condition . ' order by changedat desc limit '
			. $search_maxhits);
	if (! $row)
		$rc = '+++ keine passenden Seiten gefunden';
	else {
		$rc = '<table border="1"><tr><td><b>Nr</b></td><td><b>Forum</b></td>'
			. '<td>Titel</b></td><td><b>Autor</b></td><td><b>geschrieben am</b></td>'
			. '<td><b>Typ</b></td>'
			. ($with_text ? '<td><b>Fundstelle</b></td>' : '') . '</tr>';
		$no = 0;
		while ($row) {
			$rc .= "\n<tr><td>"
				. ++$no . '</td><td>'
				. dbForumName ($session, $row [5], true) . '</td><td>'
				. guiInternLinkString ($session,
					P_Thread . '?action=' . A_ShowThread
						. '&posting_id=' . $row[0] . '&forum_id=' . $row [5],
					$row[1])
				. '</td><td>' . htmlentities ($row [2]) . '</td><td>'
				. dbSqlDateToText ($session, $row [3])
				. '</td><td>' . (empty ($row[4]) ? 'Thema' : 'Antwort');
			if ($with_text)
				$rc .= '</td><td>' . findTextInLine ($row [6], $to_find, 3);
			$rc .= '</td></tr>';
			$row = dbNextRecord ($session);
		}
		$rc .= "\n</table>\n";
	}
	return $rc;
}
function guiForumSearchAnswer (&$session){
	global $forum_titletext, $forum_title, $search_maxhits,
		$forum_bodytext, $forum_body;

	$session->trace (TC_Gui1, 'guiForumSearchAnswer');
	if (isset ($forum_title)) {
		if (empty ($forum_titletext))
			$message = '+++ kein Titelbestandteil angegeben';
		else
			$message = forumSearch ($session, false,
				'subject like ' . dbSqlString ($session, "%$forum_titletext%"),
				$forum_titletext);
	} else {
		if (empty ($forum_bodytext))
			$message = '+++ kein Suchtext angegeben';
		else
			$message = forumSearch ($session, true,
				'text like ' . dbSqlString ($session, "%$forum_bodytext%"),
				$forum_bodytext);
	}
	guiForumSearch ($session, $message);
}
function guiForumHome ($session) {
	$session->trace (TC_Gui1, 'guiForumHome');
	guiStandardHeader ($session, 'Forenübersicht', Th_StandardHeader,
		Th_StandardBodyStart);
	$id_list = dbIdList ($session, T_Forum, '1');
	echo '<table width="100%" border="1">' . "\n"
		. '<tr><td><b>Forum</b></td><td><b>Beschreibung</b></td>'
		. '<td><b>Themen</b></td><td><b>Beitr&auml;ge</b></td>'
		. '<td><b>Letzter Beitrag</b></td>'
		. "</tr>\n";
	foreach ($id_list as $ii => $id) {
		list ($name, $description) = dbGetRecordById ($session, T_Forum, $id,
			'name,description');
		$threads = dbSingleValue ($session, 'select count(id) from '
			. dbTable ($session, T_Posting)
			. " where top is null and forum=$id");
		$posting_data1 = dbGetRecordByClause ($session, T_Posting,
			'count(id),max(id)', "forum=$id");
		$posting_data2 = dbGetRecordById ($session, T_Posting,
			0+$posting_data1[1], 'changedat,author,createdat,subject');
		echo '<tr><td>';
		guiInternLink ($session, P_Forum . '?forum_id=' . $id, $name);
		echo '</td><td>' . htmlentities ($description) . '</td>';
		echo "<td>$threads</td><td>$posting_data1[0]</td><td>";
		if ($posting_data2) {
			echo htmlentities ($posting_data2 [1]);
			echo ' am ';
			echo dbSqlDateToText ($session, $posting_data2 [empty ($posting_data2 [0])
				? 0 : 2]);
			echo '<br/>';
			guiInternLink ($session, P_Thread . '?action=' . A_ShowThread
				. '&posting_id=' . $posting_data1[1],
				$posting_data2[3]);
		}
		echo "</td><tr>\n";
	}
	echo "</table>\n";
	guiStandardBodyEnd ($session, Th_StandardBodyEnd);
}
function guiForum (&$session) {
	global $page_no, $forum_id;

	$session->trace (TC_Gui1, 'guiForum');
	if (! isset ($forum_id) || ! isInt ($forum_id))
		$forum_id = 1;
	if (empty ($page_no))
		$page_no = 1;
	$forum = dbGetRecordById ($session, T_Forum, $forum_id, 'name,description');
	dbForumInfo ($session, $forum_id, $threads, $pages);
	$headline = 'Forum ' . $forum [0];
	guiStandardHeader ($session, $headline, Th_StandardHeader, Th_StandardBodyStart);
	guiParagraph ($session, $forum [1], true);
	guiParagraph ($session, 'Seite ' . $page_no . ' von ' . $pages . ' ('
		. (0 + $threads) . ($threads == 1 ? ' Thema)' : ' Themen)'), false);
	echo '<table width="100%" border="0"><tr><td>';
	guiInternLink ($session, P_Forum . '?forum_id=' . $forum_id
		. '&action=' . A_NewThread, 'Neues Thema');
	echo '</td><td style="text-align: right">';
	guiPageLinks ($session, P_Forum . '?action=' . A_ShowForum
		. '&forum_id=' . $forum_id, $page_no, $pages);
	echo '</td></tr></table>' . "\n";

	$id_list = dbIdListOfPage ($session, T_Posting,
		"forum=$forum_id and top is null order by id desc",
		$session->fUserThreadsPerPage, $page_no);
	echo '<table width="100%" border="1">' . "\n"
		. '<tr><td><b>Thema</b></td><td><b>Autor</b></td>'
		. '<td><b>Antworten</b></td><td><b>Aufrufe</b></td>'
		. '<td><b>Letzter Beitrag</b></td>'
		. "</tr>\n";
	foreach ($id_list as $ii => $id) {
		$thread = dbGetRecordById ($session, T_Posting, $id,
			'author,changedat,subject,changedby,calls');
		$last_id = null;
		dbThreadInfo ($session, $id, $answers, $thread_pages, $last_id);
		if (empty ($last_id)) {
			$last = $thread;
			$last_id = $id;
		} else {
			$last = dbGetRecordById ($session, T_Posting, $last_id,
				'author,changedat,subject');
		}
		echo '<tr><td>';
		guiThreadPageLink ($session, $id, 1, $thread [2]);
		echo '</td><td>' . htmlentities ($thread [0]) . '</td><td>';
		echo $answers + 0;
		echo '</td><td>' . ($thread [4] + 0) . '</td><td>';
		echo $last [0] . ' ' . dbSqlDateToText ($session, $last [1])
			. '<br/>';
		guiInternLink ($session, P_Thread. '?action=' . A_ShowThread
						. '&posting_id=' . $last_id,
			$last [2]);
		echo '</td></tr>' . "\n";
	}
	echo "</table>\n";
	echo '<table width="100%" border="0"><tr><td>';
	guiInternLink ($session, P_Forum . '?forum_id=' . $forum_id
		. '&action=' . A_NewThread, 'Neues Thema');
	echo '</td><td style="text-align: right">';
	guiPageLinks ($session, P_Forum . '?action=' . A_ShowForum
		. '&forum_id=' . $forum_id, $page_no, $pages);
	echo '</td></tr></table>' . "\n";
	guiStandardBodyEnd ($session, Th_StandardBodyEnd);
}
function guiPostingChange (&$session) {
	global $posting_id, $last_pagename;
	$session->trace (TC_Gui1, 'guiPostingChange');
	list ($posting_subject, $posting_text) = dbGetRecordById ($session,
		T_Posting, $posting_id, 'subject,text');
	guiPosting ($session, '', C_Change);
}
function guiPosting (&$session, $message, $mode) {
	global $forum_id, $thread_id, $reference_id,
		$posting_id, $posting_subject, $posting_text,
		$posting_preview,
		$textarea_width, $textarea_height, $last_pagename;
	$session->trace (TC_Gui1, 'guiPosting: ' . $mode);
	if (! isset ($last_pagename))
		$last_pagename = $session->fPageName;
	$headline = ($mode == C_New)
		? (empty ($reference_id) ? 'Neues Thema' : 'Antworten')
		: (empty ($reference_id) ? 'Thema ändern' : 'Antwort ändern');
	guiStandardHeader ($session, $headline, Th_AnswerHeader, Th_AnswerBodyStart);
	if (! empty ($reference_id)
		&& ($posting = dbGetRecordById ($session, T_Posting, $reference_id,
			'author,subject,text,forum'))) {
		guiHeadline ($session, 1, 'Beitrag: ' . $posting [1]);
		guiParagraph ($session, 'Autor: ' . $posting [0], true);
		wikiToHTML ($session, $posting [2]);
		guiLine (2);
		if ($mode == C_New) {
			$pos = strpos ($posting [1], 'Re: ');
			$posting_subject = (is_int ($pos) ? '' : 'Re: ') . $posting [1];
		}
		$forum_id = $posting [3];
	} else
		str_replace ($posting_text, "\\'", "'");
	if (isset ($posting_preview)) {
		guiHeadline ($session, 1, 'Vorschau');
		wikiToHtml ($session, $posting_text);
		guiLine (2);
	}
	guiHeadline ($session, 1, $headline);
	if (! empty ($message))
		guiParagraph ($session, $message, true);
	if ($mode == C_Change && ! isset ($posting_text)) {
		$posting = dbGetRecordById ($session, T_Posting, $posting_id,
			'subject,text,forum');
		$forum_id = $posting [2];
		$posting_text = $posting [1];
		$posting_subject = $posting [0];
	}
	getUserParam ($session, U_TextAreaWidth, $textarea_width);
	getUserParam ($session, U_TextAreaHeight, $textarea_height);
	guiStartForm ($session, 'thread');
	guiHiddenField ('std_answer', 'j');
	guiHiddenField ('last_pagename', $last_pagename);
	guiHiddenField ('forum_id', $forum_id);
	guiHiddenField ('thread_id', $thread_id);
	guiHiddenField ('posting_id', $posting_id);
	guiHiddenField ('reference_id', $reference_id);
	echo "<table border=\"0\">\n<tr><td>Thema:</td><td>";
	guiTextField ('posting_subject', $posting_subject, $textarea_width, 64);
	echo "</td></tr>\n<tr><td>Text</td><td>";
	guiTextArea ('posting_text', $posting_text, $textarea_width,
		$textarea_height);
	echo '</td></tr><tr><td></td><td style="text-align: right;">Eingabefeld: Breite: ';
	guiTextField ('textarea_width', $textarea_width, 3, 3);
	echo " H&ouml;he: ";
	guiTextField ('textarea_height', $textarea_height, 3, 3);
	echo "</td></tr>\n<tr><td></td><td>";
	guiButton ('posting_preview', 'Vorschau');
	echo ' | ';
	if ($mode == C_New)
		guiButton ('posting_insert', 'Eintragen');
	else
		guiButton ('posting_change', '&Auml;ndern');
	echo "</td></tr>\n</table>\n";

	guiFinishForm ($session, $session);
	guiStandardBodyEnd ($session, Th_AnswerBodyEnd);
}
function guiPostingAnswer (&$session) {
	global $forum_id, $thread_id,
		$posting_id, $posting_subject, $posting_text,
		$posting_preview, $posting_insert, $posting_change,
		$textarea_width, $textarea_height;
	$session->trace (TC_Gui1, 'guiPostingAnswer');
	$message = null;
	$mode = null;
	$posting_text = textAreaToWiki ($session, $posting_text);
	if (isset ($posting_preview)) {
		$mode = (isset ($posting_id) && isInt ($posting_id)) ? C_Change : C_New;
	} elseif (isset ($posting_insert)) {
		$mode = C_New;
		if (empty ($posting_subject))
			$message = '+++ Thema fehlt';
		elseif (strlen ($posting_text) < 5)
			$message = '+++ Beitrag zu kurz';
		else {
			$date = dbSqlDateTime ($session, time ());
			$thread_id = $posting_id = dbInsert ($session, T_Posting,
				'createdat,changedat,forum,author,top,reference,subject,text',
				"$date,$date,$forum_id," . dbSqlString ($session, $session->fUserName)
					. ',' . (empty ($thread_id) ? 'null' : $thread_id)
					. ',' . (empty ($reference_id) ? 'null' : $reference_id)
					. ',' . dbSqlString ($session, $posting_subject)
					. ',' . dbSqlString ($session, $posting_text));
			dbUpdateRaw ($session, T_User, $session->fUserId,
				'postings=postings+1');
			guiForum ($session);
			$mode = NULL;
		}
	} elseif (isset ($posting_change)) {
		$mode = C_Change;
		if (empty ($posting_subject))
			$message = '+++ Thema fehlt';
		elseif (strlen ($posting_text) < 5)
			$message = '+++ Beitrag zu kurz';
		else {
			$date = dbSqlString ($session, time ());
			dbUpdate ($session, T_Posting, $posting_id,
				'changedby=' . dbSqlString ($session, $session->fUserName)
				. ',changedat=' . $date
				. ',subject=' . dbSqlString ($session, $posting_subject)
				. ',text=' . dbSqlString ($session, $posting_text) . ',');
			guiThread ($session);
			$mode = NULL;
		}
	}
	if ($mode)
		guiPosting ($session, $message, $mode);
}
function guiThread (&$session) {
	global $thread_id, $posting_id, $page_no;

	$session->trace (TC_Gui1, 'guiThread');
	if (empty ($thread_id)) {
		if (empty ($posting_id))
			$thread_id = 1;
		else {
			$thread_id = dbSingleValue ($session,
				'select top from ' . dbTable ($session, T_Posting)
				. " where id=$posting_id");
			if (empty ($thread_id))
				$thread_id = $posting_id;
		}
	}
	if (! isset ($page_no))
		$page_no = isset ($posting_id)
			? dbPageOfPosting ($session, $thread_id, $posting_id) : 1;
	$id_list = dbIdListOfThreadPage ($session, $thread_id, $page_no);
	$thread = dbGetRecordById ($session, T_Posting, $thread_id,
		'author,subject,text,createdat,changedby,changedat,calls,forum');
	dbThreadInfo ($session, $thread_id, $answers, $pages, $last);
	$thread_date = dbSqlDateToText ($session, $thread [3]);
	$forum = dbGetRecordById ($session, T_Forum, $thread [7], 'name');

	guiStandardHeader ($session, 'Thema: ' . $thread [1], Th_ThreadHeader,
		Th_ThreadBodyStart);
	guiParagraph ($session, $thread [0] . ', ' . $thread_date
		. ', ' . (0 + $answers) . ' Antwort' . ($answers == 1 ? ', ' : 'en, ')
		. $pages . ' Seite' . ($pages == 1 ? '' : 'n'), true);
	echo '<table width="100%" border="0"><tr><td>';
	echo 'Forum: ';
	guiInternLink ($session, P_Forum . '?forum_id=' . $thread [7],
		$forum [0]);
	if ($pages > 1) {
		echo '</td><td style="text-align: right">';
		guiPageLinks ($session, P_Thread . '?action=' . A_ShowThread
			. '&thread_id=' . $thread_id, $page_no, $pages);
	}
	echo "</td></tr></table>\n";

	dbUpdateRaw ($session, T_Posting, $thread_id,
		'calls=calls+1');
	echo '<table width="100%" border="0">' . "\n";
	// Autor Thema-Text
	foreach ($id_list as $ii => $id) {
		# echo "alle Postings: $ii / $id<br>";
		if ($id == $thread_id)
			$posting = $thread;
		else
			$posting = dbGetRecordById ($session, T_Posting, $id,
				'author,subject,text,createdat,changedby,changedat');
		dbGetAuthorInfo ($session, $posting [0], $author_link,
			$postings, $avatar, $ranking);
		echo '<tr><td style="vertical-align:top">';
		guiLine (1);
		echo $thread[0] . '<br/>'
			. ($postings + 0) . ' Beitr&auml;ge<br/>' . $ranking;
		if (! empty ($avatar))
			echo '<br/>' . $avatar;
		echo '</td><td>';
		guiLine (1);
		if ($id == $thread_id)
			guiHeadline ($session, 3, 'Thema: ' . $posting [1]);
		else
			guiHeadline ($session, 3, 'Antwort '
				. ($ii + $session->fUserPostingsPerPage * ($page_no - 1))
				. ': ' . $posting [1]);
		echo 'Geschrieben am ' . dbSqlDateToText ($session, $posting [3]);
		if (strcmp ($posting [5], $posting [3]) != 0) {
			echo ' Letzte &Auml;nderung: '
				. dbSqlDateToText ($session, $posting [5]);
			if (! empty ($posting [4]) && strcmp ($posting [4], $posting [0]) != 0)
				echo ' von ' . $posting [5];
		}
		echo '<br/>';
		wikiToHTML ($session, $posting [2]);
		echo '<p>';
		guiInternLink ($session, P_Thread
			. '?action=' . A_Answer . "&thread_id=$thread_id&reference_id=$id",
			'Antworten');
		if (strcmp ($posting [0], $session->fUserName) == 0) {
			echo ' ';
		guiInternLink ($session, P_Thread
			. '?action=' . A_ChangeThread . "&thread_id=$thread_id&posting_id=$id",
			'Ändern');
		echo "</p>\n";
		}
		echo "</td><tr>\n";
	}
	echo "</table>\n";
	guiStandardBodyEnd ($session, Th_ThreadBodyEnd);
}
function guiCallStandardPage (&$session) {
	$session->trace (TC_Gui2, 'guiCallStandardPage');
	$found = true;
	switch ($session->fPageName) {
	case P_Login:	guiLogin ($session, ''); break;
	case P_Account: guiAccount ($session, ''); break;
	case P_Home: 	guiHome ($session); break;
	case P_NewPage:	guiAlterPage ($session, C_New, '', ''); break;
	case P_NewWiki:	guiAlterPage ($session, C_New, '', '', M_Wiki); break;
	case P_ModifyPage: guiAlterPage ($session, C_Change, '', ''); break;
	case P_ForumHome: guiForumHome ($session); break;
	case P_Forum: guiForum ($session); break;
	case P_Thread: guiThread ($session); break;
	case P_ForumSearch: guiForumSearch ($session, null); break;
	case '!test': guiTest ($session); break;
	case '!form': guiFormTest ($session); break;
	case P_Search:	guiSearch ($session, ''); break;
	case P_Start: guiCustomStart ($session); break;
	case P_LastChanges: guiLastChanges ($session); break;
	case P_Info: guiInfo ($session); break;
	default:
		$session->trace (TC_Gui2, 'guiCallStandardPage-kein Std');
		$found = false;
		break;
	}
	return $found;
}
function guiCustomStart (&$session) {
	$session->trace (TC_Gui2, 'guiCustomStart');
	if (empty ($session->fUserStartPage))
		$session->fUserStartPage = P_ForumHome;
	$session->setPageName ($session->fUserStartPage);
	if (! guiCallStandardPage ($session))
		if (($page_id = dbPageId ($session, $session->fUserStartPage)) > 0)
			guiShowPageById ($session, $page_id, null);
		else
			guiHome ($session);
}
function guiPageInfo (&$session) {
	$pagename = $session->fPageName;
	$headline = 'Info über ' . $pagename;
	guiStandardHeader ($session, $headline, Th_InfoHeader, null);
	$page = dbGetRecordByClause ($session, T_Page,
		'id,createdat,type,readgroup,writegroup', 'name='
		. dbSqlString ($session, $pagename));
	$pageid = $page [0];
	guiParagraph ($session, 'Erzeugt: ' . dbSqlDateToText ($session, $page [1]), false);
	$count = dbSingleValue ($session, 'select count(id) from '
		. dbTable ($session, T_Text) . ' where page=' . (0 + $pageid));
	if ($count <= 1)
		guiParagraph ($session, 'Die Seite wurde nie geändert', false);
	else {
		guiHeadline ($session, 2, "Versionen ($count)");
		echo '<table border="1"><tr><td><b>Id</b></td>'
			. '<td><b>Autor</b></td><td><b>erzeugt</b></td>'
			. '<td><b><b>Unterschied zum Nachfolger</b></td>'
			. '<td><b>Unterschied zu jetzt</b></td></tr>' . "\n";
		$row = dbFirstRecord ($session,
			'select id,createdby,createdat,changedat,replacedby from '
				. dbTable ($session, T_Text) . ' where page=' . (0 + $pageid)
				. ' order by id desc');
		$act_text_id = $row [0];
		while ($row) {
			$text_id = $row [0];
			$replacedby = $row [4];
			echo '<tr><td>';
			guiInternLink ($session,
				$pagename . '?action=' . A_ShowText
				. '&page_id=' . $pageid . '&text_id=' . ($text_id+0), $text_id);
			echo '</td><td>';
			guiAuthorLink ($session, $row [1]);
			echo '</td><td>' . dbSqlDateToText ($session, $row [2]);
			echo '</td><td>';
			if ($replacedby > 0) {
				guiInternLink ($session, $pagename . '?action=' . A_Diff
					. '&text_id=' . $replacedby . '&text_id2=' . $text_id,
					' Unterschied zu ' . $replacedby);
				if ($replacedby != $act_text_id){
					echo '</td><td>';
					guiInternLink ($session, $pagename . '?action=' . A_Diff
						. '&text_id=' . $text_id . '&text_id2=' . $act_text_id,
						' Unterschied zu jetzt');
				}
			}
			echo '</td></tr>' . "\n";
			$row = dbNextRecord ($session);
		}
		echo '</table>' . "\n";
	}
	guiStandardBodyEnd ($session, Th_InfoBodyEnd);
}
function guiDiff ($session) {
	global $text_id, $text_id2;
	$headline = 'Versionsvergleich von ' . $session->fPageName;
	guiStandardHeader ($session, $headline, Th_StandardHeader, Th_StandardBodyStart);
	$version1 = dbGetRecordById ($session, T_Text, $text_id2,
		'page,createdat,createdby,text');
	$version2 = dbGetRecordById ($session, T_Text, $text_id,
		'page,createdat,createdby,text');
	if ($version1 [0] != $version2 [0])
		guiParagraph ($session, 'Texte nicht von einer Seite: '
			. (0 + $version1 [0]) . ' / ' .  (0 + $version2 [0]), false);
	else {
		guiParagraph ($session, 'verglichen werden die Versionen '
			. $text_id2 . ' / ' . $text_id . ' von ' . $version1 [2]
			. ($version1 [2] != $version2 [2] ? ' / ' . $version2 [2] : '')
			. ' (' . dbSqlDateToText ($session, $version1 [1])
			. ' / ' .  dbSqlDateToText ($session, $version2 [1]) . ')', false);
		$engine = new DiffEngine ($session, $version1 [3], $version2 [3]);
		$engine->compare (1, 1);
	}
	guiStandardBodyEnd ($session, Th_StandardBodyEnd);
}
function guiLastChanges (&$session) {
	global $last_days;
	$headline = 'Übersicht über die letzten Änderungen';
	guiStandardHeader ($session, $headline, Th_StandardHeader, Th_StandardBodyStart);
	if (! isset ($last_days))
		$last_days = 7;
	echo '<table border="0">' . "\n";

	for ($day = 0; $day <= $last_days; $day++) {
		$date = localtime (time () - $day * 86400);
		$time_0 = strftime ('%Y.%m.%d', time () - $day * 86400) ;
		$time_2 = mktime (0, 0, 0, $date [4] + 1, $date [3], $date [5]);
		$time_1 = dbSqlDateTime ($session, $time_2);
		$condition = 'createdat>=' . $time_1
			. ' and createdat<=' . str_replace ('00:00:00', '23:59:59', $time_1);
		$ids = dbIdList2 ($session, T_Text, 'distinct page', $condition);
		if ($ids) {
			echo '<tr><td><b>';
			echo $time_0;
			echo'</b></td></tr>' . "\n";
			foreach ($ids as $ii => $pageid) {
				$page = dbGetRecordById ($session, T_Page, $pageid, 'name');
				$text = dbFirstRecord ($session,
					'select id,createdby,createdat,replacedby from '
				. dbTable ($session, T_Text) . ' where page=' . (0 + $pageid)
				. ' and ' . $condition . ' order by id desc');
				$count = 0;
				while ($text) {
					$pred_text = dbSingleValue ($session, 'select max(id) from '
						. dbTable ($session, T_Text) . ' where page=' . $pageid
						. ' and createdat<'
						. dbSqlDateTime ($session, $time_2));
					echo '<tr><td>';
					echo dbSingleValue ($session, 'select min(id) from '
						. dbTable ($session, T_Text) . ' where page=' . $pageid)
						== $text [0] ? 'Neu' : 'Änderung';
					echo '</td><td>';
					echo $text [0];
					echo '</td><td>';
					echo $count++ > 0 ? htmlentities ($page [0])
						: guiInternLink ($session, $page [0], $page [0]);
					echo '</td><td>';
					guiAuthorLink ($session, $text [1]);
					echo '</td><td>';
					echo dbSqlDateToText ($session, $text [2]);
					if ($pred_text > 0) {
						echo '</td><td>';
						guiInternLink ($session, $page [0] . '?action=' . A_Diff
							. '&text_id=' . $pred_text . '&text_id2=' . $text [0],
							'Unterschied zum Vortag (' . $pred_text . ')');
					}
					echo '</td></tr>' . "\n";
					$text = dbNextRecord ($session);
				}
			}
		}
	}
	echo '</table>';
	guiStandardBodyEnd ($session, Th_StandardBodyEnd);
}
function guiInfo (&$session) {
	guiStandardHeader ($session, 'Infobasar-Info', Th_InfoHeader, null);
	guiParagraph ($session, '(C) Hamatoma AT gmx DOT de 2004', 0);
	echo '<table border="0"><tr><td><b>Gegenstand</b></td>';
	echo '<td><b>Version</b></td></tr>' . "\n";
	echo '<tr><td>PHP-Script:</td><td>';
	echo PHP_Version;
	echo '</td></tr><tr><td>DB-Schema:</td><td>';
	echo htmlentities (dbGetParam ($session, Theme_All, Param_DBScheme));
	echo '<tr><td>DB-Basisinhalt:</td><td>';
	echo htmlentities (dbGetParam ($session, Theme_All, Param_DBBaseContent));
	echo '<tr><td>DB-Erweiterungen:</td><td>';
	echo htmlentities (dbGetParam ($session, Theme_All, Param_DBExtensions));
	echo '</td></tr></table>' . "\n";
	guiStandardBodyEnd ($session, Th_InfoBodyEnd);
}
// --------------------
function diffTest ($session) {
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
function guiTest (&$session) {
	global $test_text, $test;
	guiStandardHeader ($session, 'Test', Th_StandardHeader, Th_StandardBodyStart);
	echo WikiToHtml ($session, "[code]\n\ra<b\n\rZeile2\n\r[/code]\n\r");
	guiStandardBodyEnd ($session, Th_StandardBodyEnd);
	# guiTestAll ($session);
}
function guiFormTest (&$session) {
	global $test_text, $test;
	if (! isset ($test_text))
		$test_text = "Noch nix!";
	guiStandardHeader ($session, 'Test', Th_StandardHeader, Th_StandardBodyStart);
	$engine = new DiffEnginge ("a\nx\b", "a\nb");
	$engine->compare (1, 1);
	guiStandardBodyEnd ($session, Th_StandardBodyEnd);
}
function guiTestAll (&$session) {
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

?>
