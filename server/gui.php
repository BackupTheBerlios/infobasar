<?php
// gui.php: functions for Graphical User Interface
// $Id: gui.php,v 1.10 2004/06/13 10:54:05 hamatoma Exp $
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
function guiUploadFile (&$session, $prefix, $lastpage = null,
		$custom_field = null, $custom_value = null,
		$caption = 'Hochladen', 
		$button = 'upload_go', $file = 'upload_file', $max_file_size = 100000) {
	echo '<form enctype="multipart/form-data" action="' . $session->fScriptURL
		. '" method="post">' . "\n";
	guiHiddenField ('last_pagename', $lastpage ? $lastpage : $last_pagename);
	if ($custom_field)
		guiHiddenField ($custom_field, $custom_value);
	guiHiddenField ('MAX_FILE_SIZE', $max_file_size);
	if (! empty ($prefix))
		echo $prefix . ' ';
	echo '<input name="' . $file . '" type="file">' . "\n";
	echo ' ';
	guiButton ($button, $caption);
	guiFinishForm ($session);
}
function guiUploadFileAnswer (&$session, $destination = PATH_DELIM,
		$filename = null, $button = 'upload_go', $file = 'upload_file'){
	$message = null;
	$name =  $_FILES[$file]['name'];
	if (move_uploaded_file($_FILES[$file]['tmp_name'],
		$session->fFileSystemBase . $destination . ($filename ? $filename : $name))) {
		$message = 'Datei erfolgreich hochgeladen: ' . $name;
		if ($filename)
			$message .= ' als ' . $filename;
	} else {
		$message = '+++ Problem beim Hochladen von ' . $name . ': ' 
			. $_FILES['archive_uploadfile']['error'];
	}
	return $message;
}
function guiLine ($width) {
	if (! isset ($width))
		$width = 2;
	echo '<hr style="width: 100%; height: ' . $width . "px;\">\n";
}
function guiStandardLinkString (&$session, $page) {
	$session->trace (TC_Gui3, 'guiStandardLinkString');
	$rc = null;
	$module = null;
	switch ($page) {
	case P_Home: $header = 'Übersicht'; break;
	case P_ForumSearch: $header = 'Forumsuche'; $module = Module_Forum; break;
	case P_ForumHome: $header = 'Forenübersicht'; $module = Module_Forum; break;
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
		$rc = guiInternLinkString ($session, $page, $header, $module);
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
function guiStartForm (&$session, $pagename = null) {
	$session->trace (TC_Gui2, 'guiStartForm');
	echo '<form name="form" action="' . $session->fScriptURL . '" method="post">' . "\n";
	if ($pagename)
		guiHiddenField ('last_pagename', $pagename);
}
function guiStartFormGet (&$session) {
	$session->trace (TC_Gui2, 'guiStartForm');
	echo '<form name="form" action="' . $session->fScriptURL . '" method="get">' . "\n";
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
	return "<a href=\"$link\">" . htmlToText ($session, $text) . "</a>\n";
}

function guiExternLink (&$session, $link, $text) {
	$session->trace (TC_Gui2, 'guiExternLink');
	echo "<a href=\"$link\">";
	echo htmlToText ($session, $text);
	echo "</a>\n";
}
function guiInternLink (&$session, $link, $text, $module = null) {
	$session->trace (TC_Gui2, 'guiInternLink');
	if (empty ($text))
		$text = $link;
	guiExternLink ($session, $session->fScriptURL . '/' . $link, $text);
}
function guiInternLinkString (&$session, $link, $text, $module = null) {
	$session->trace (TC_Gui2, 'guiInternLinkString');
	if (empty ($text))
		$text = $link;
	if (empty ($module))
		$module = $session->fScriptURL;
	else
		$module = $session->fScriptBase . '/' . $module;
	return guiExternLinkString ($session, $module . '/' . $link, $text);
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
	if ($pos_body > 0) {
		$header = dbGetText ($session, $pos_body);
		if (empty ($header))
			$pos_body = null;
		else
			echo $session->replaceMacrosNoHTML ($header);
	}
	if (! $pos_body && $pos_body != 0)
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
?>
