<?php
// gui.php: functions for Graphical User Interface
// $Id: gui.php,v 1.14 2004/11/08 13:30:13 hamatoma Exp $
/*
Diese Datei ist Teil von InfoBasar.
Copyright 2004 hamatoma@gmx.de München
InfoBasar ist freie Software. Du kannst es weitergeben oder verändern
unter den Bedingungen der GNU General Public Licence.
Näheres siehe Datei LICENCE.
InfoBasar sollte nützlich sein, es gibt aber absolut keine Garantie
der Funktionalität.
*/
// --- HTML-Strings:
function tagStrong($text = null){
	if ($text == null)
		return '<strong>';
	else
		return '<strong>' . $text . '</strong>';
}
function outStrong($text = null){
	if ($text == null)
		echo '<strong>';
	else {
		echo '<strong>';
		echo $text;
		echo '</strong>';
	}
}
function tagQuotation($text = null){
	if ($text == null)
		return '<cite>';
	else
		return '<cite>' . $text . '</cite>';
}
function outQuotation($text = null){
	if ($text == null)
		echo '<cite>';
	else {
		echo '<cite>';
		echo $text;
		echo '</cite>';
	}
}
function tagStrongEnd(){
	return '</strong>';
}
function outStrongEnd(){
	echo '</strong>';
}

function tagNewline(){
	return "<br />\n";
}
function outNewline(){
	echo "<br />\n";
}
function tagParagraph(){
	return '<p>';
}
function outParagraph(){
	echo '<p>';
}
function tagParagraphEnd(){
	return '<p>';
}
function outParagraphEnd(){
	echo '<p>';
}
function tagTable($border = 0){
	return "\n<table border=\"" . (0+$border) . '">'; 
}
function outTable($border = 0, $width = 0){
	echo "\n<table border=\"";
	echo $border;
	if ($width != null){
		echo '" width="';
		echo $width;
	}
	echo '">'; 
}
function tagTableEnd(){
	return "</table>\n"; 
}
function outTableEnd(){
	echo "</table>\n"; 
}
function tagTableRecord(){
	return '<tr>';
}
function outTableRecord(){
	echo '<tr>';
}
function tagTableAndRecord($border = 0){
	return tagTable ($border) . tagTableRecord();
}
function outTableAndRecord($border = 0){
	outTable ($border);
	outTableRecord();
}
function tagTableAndRecordEnd(){
	return tagTableRecordEnd() . tagTableEnd();
}
function outTableAndRecordEnd(){
	outTableRecordEnd();
	outTableEnd();
}
function tagTableRecordEnd(){
	return '</tr>';
}
function outTableRecordEnd(){
	echo '</tr>';
}
function tagTableDelim($halignment = AL_None){
	if ($halignment == AL_None)
		return '<td>';
	else
		return '<td text-align: ' . $alignment . '>';
}
function outTableDelim($halignment = AL_None){
	if ($halignment == AL_None)
		echo '<td>';
	else{
		echo '<td text-align: ';
		echo $halignment;
		echo  '>';
	}
}
function tagTableRecordAndDelim($halignment = AL_None){
	return tagTableRecord . tagTableDelim ($halignment);
}
function outTableRecordAndDelim($halignment = AL_None){
	outTableRecord ();
	outTableDelim ($halignment);
}
function tagTableDelimEnd(){
	return '</td>';
}
function outTableDelimEnd(){
	echo '</td>';
}
function tagTableDelimAndRecordEnd(){
	return "</td></tr>\n";
}
function outTableDelimAndRecordEnd(){
	echo "</td></tr>\n";
}
function tagTableCellDelim(){
	return '</td><td>';
}
function outTableCellDelim(){
	echo '</td><td>';
}
function tagTableRecordDelim(){
	return "</tr>\n<tr>";
}
function outTableRecordDelim(){
	echo "</tr>\n<tr>";
}
function tagTableCell($text, $halignment = AL_None){
	return tagTableDelim ($halignment) . htmlentities ($text) . tagTableDelimEnd();
}
function outTableCell($text, $halignment = AL_None){
	outTableDelim ($halignment);
	echo $text;
	outTableDelimEnd();
}
function outTableRecordCells ($text1, $text2, $text3 = null){
	outTableRecord ();
	outTableCell ($text1);
	outTableCell ($text2);
	if ($text3 != null)
		outTableCell ($text3);
	outTableRecordEnd();
}
function outTableCellStrong($text, $halignment = AL_None){
	outTableDelim ($halignment);
	echo '<strong>';
	echo $text;
	echo '</strong>';
	outTableDelimEnd();
}
function tagTableCellConvert($text, $halignment = AL_None){
	return tagTableDelim ($halignment) . htmlentities ($text) . tagTableDelimEnd();
}
function outTableCellConvert($text, $halignment = AL_None){
	outTableDelim ($halignment);
	echo htmlentities ($text);
	outTableDelimEnd();
}
function outTableTextField ($prefix, $name, $value, $size, 
		$maxsize=0, $halignment = AL_None){
	if ($prefix != null)
		outTableCell ($prefix);
	outTableDelim($halignment);
	guiTextField ($name, $value, $size, $maxsize);
	outTableDelimEnd();
}
function outTableTextField2 ($prefix, $name1, $value1, $size1, 
		$maxsize1, $delim, $name2, $value2, $size2, $maxsize2 = 0){
	if ($prefix != null)
		outTableCell ($prefix);
	outTableDelim();
	guiTextField ($name1, $value1, $size1, $maxsize1);
	echo $delim;
	guiTextField ($name2, $value2, $size2, $maxsize2);
	outTableDelimEnd();
}
function outTablePasswordfield ($prefix, $name, $value, $size, 
		$maxsize=0, $halignment = AL_None){
	if ($prefix != null)
		outTableCell ($prefix);
	outTableDelim($halignment);
	guiPasswordField ($name, $value, $size, $maxsize);
	outTableDelimEnd();
}
function outTableButton ($prefix, $name, $text, $halignment = AL_None){
	if ($prefix != null)
		outTableCell ($prefix);
	outTableDelim ($halignment);
	guiButton ($name, $text);
	outTableDelimEnd(); 
}
function outTableButton2 ($prefix, $name1, $text1, $delim, $name2, $text2, 
		$halignment = AL_None){
	if ($prefix != null)
		outTableCell ($prefix);
	outTableDelim ($halignment);
	guiButton ($name1, $text1);
	echo $delim;
	guiButton ($name2, $text2);
	outTableDelimEnd(); 
}
function outTableCheckBox ($prefix, $name, $text, $selected, $halignment = AL_None){
	if ($prefix != null)
		outTableCell ($prefix);
	outTableDelim ($halignment);
	guiCheckBox ($name, $text, $selected);
	outTableDelimEnd(); 
}
function outTableComboBox ($prefix, $name, $names, $values, $index, $halignment = AL_None){
	if ($prefix != null)
		outTableCell ($prefix);
	outTableDelim ($halignment);
	guiComboBox ($name, $names, $values, $index);
	outTableDelimEnd(); 
}
function outTableInternLink ($session, $prefix, $link, $text = null, $module = null, 
		$halignment = AL_None){
	if ($prefix != null)
		outTableCell ($prefix);
	outTableDelim ($halignment);
	guiInternLink ($session, $link, $text, $module);
	outTableDelimEnd(); 
}
function outTableAuthorLink ($session, $author){
	outTableDelim();
	guiAuthorLink ($session, $author);
	outTableDelimEnd();
}
function outTableTextArea ($prefix, $name, $content, $width, $height){
	if ($prefix != null)
		outTableCell ($prefix);
	outTableDelim();
	guiTextArea ($name, $content, $width, $height);
	outTableDelimEnd();
}

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
function guiFileField ($name){
	echo '<input name="';
	echo $name;
	echo '" type="file">'; 
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
	guiInternLink ($session, encodeWikiName ($session, $session->fPageName) 
		. '?action=' . $command, $text);
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
function guiComboBox ($name, $options, $values = null, $ix_selected = 0) {
	echo '<select name="';
	echo $name;
	echo '" size="1';
	echo "\">\n";
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
	global $last_pagename;
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
		$new_target_name = null, $button = 'upload_go', $file = 'upload_file'){
	global $_FILE;
	$session->trace (TC_Gui2, "guiUploadFileAnswer: dest: $destination new_name: $new_target_name name");
	$message = null;
	print_r ($_FILE);
	$temp_name =  $_FILES[$file]['tmp_name'];
	$name =  $_FILES[$file]['name'];
	$target = $session->fFileSystemBase . $destination 
			. ($new_target_name != null ? $new_target_name : $name);
	$session->trace (TC_Gui2, "guiUploadFileAnswer: name: $name target: $target");
	if (move_uploaded_file($temp_name, $target)) {
		$message = 'Datei erfolgreich hochgeladen: ' . $name;
		if ($new_target_name != null)
			$message .= ' als ' . $new_target_name;
	} else {
		$message = "+++ Problem beim Hochladen von $name ($temp_name) -> $target: " 
			. $_FILES[$file]['error'];
		print_r ($_FILES);
	}
	return $message;
}
function guiUploadFileAnswerUnique (&$session, $destination,
		$new_target_name, $var_file, &$name){
	global $_FILE;
	$session->trace (TC_Gui2, "guiUploadFileAnswerUnique: dest: $destination new_name: $new_target_name name");
	$message = null;
	print_r ($_FILE);
	$temp_name =  $_FILES[$var_file]['tmp_name'];
	$name =  $new_target_name == null ? $_FILES[$var_file]['name'] : $new_target_name;
	$ext = substr ($name, strrpos ($name, '.'));
	$org_name = substr ($name, 0, strlen ($name) - strlen ($ext));
	$target = $session->fFileSystemBase . $destination . $org_name . $ext;
	$session->trace (TC_Gui2, "guiUploadFileAnswerUnique: name: $name target: $target");
	$no = 0;
	while (file_exists ($target)){
		$name = $org_name . (++$no) . $ext;
		$target = $session->fFileSystemBase . $destination . $name;
	}
	if (move_uploaded_file($temp_name, $target)) {
		$message = 'Datei erfolgreich hochgeladen: ' . $_FILES[$var_file]['name'];
		if ($new_target_name != null || $no > 0)
			$message .= ' als ' . $name;
	} else {
		$message = "+++ Problem beim Hochladen von $name ($temp_name) -> $target: " 
			. $_FILES[$var_file]['error'];
		$session->traceArray (TC_Warning, "_FILE", $_FILE);
	}
	return $message;
}

function guiLine (&$session, $width = 2) {
	$session->trace (TC_Gui2, "guiLine: $width");
	echo '<hr style="width: 100%; height: ' . (0+$width) . "px;\">\n";
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
		guiLine ($session, 1);
		modStandardLinks ($session);
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
	$session->putHeader ();
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
	$session->SetHasBody();
	if ($session->fBodyLines)
		echo join ("\n", $session->fBodyLines) . "\n";
}
function guiHeadline (&$session, $level, $text) {
	$session->trace (TC_Gui2, 'guiHeadline');
	echo "<h$level>" . htmlentities ($text) . "</h$level>\n";
}
function guiStartForm (&$session, $pagename = null) {
	$session->trace (TC_Gui2, 'guiStartForm');
	echo '<form action="' . $session->fScriptURL . '" method="post">' . "\n";
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
	if (empty ($module))
		$module = $session->fScriptURL;
	else{
		if (strpos ($module, '.php') > 0)
			$module = $session->fScriptBase . '/' . $module;
		else
			$module = $session->fScriptBase . '/' . $module . '.php';
	}
		
	guiExternLink ($session, $module . '/' . $link, $text);
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
	guiInternLink ($session, '.' .  $name, '?' . $text);
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
	if (empty ($header)){
		echo '<head><title>' . htmlentities ($title) . '</title></head>' . "\n";
	} else
		echo $session->replaceMacrosNoHTML ($header);
	if ($pos_body > 0) {
		$header = dbGetText ($session, $pos_body);
		if (empty ($header))
			$pos_body = 0;
		else
			echo $session->replaceMacrosNoHTML ($header);
	}
	if ($pos_body == 0)
		echo '<body><h1>' . $title . '</h1>' . "\n";
}
function guiStandardBodyEnd (&$session, $pos) {
	$session->trace (TC_Gui1, 'guiStandardBodyEnd');
	$html = guiParam ($session, $pos, null);
	if (! empty ($html))
		echo $session->replaceMacrosNoHTML ($html);
	else {
		if (! defined ('Th_LoginBodyEnd'))
			define ('Th_LoginBodyEnd', 0);
		if ($pos != Th_LoginBodyEnd){
			guiLine ($session, 1);
			modStandardLinks ($session);
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
	if (normalizeWikiName ($session, $name))
		$condition .= ' or text like '
			. dbSqlString ($session, '%' . $name . '%');
	$condition .= ') and replacedby is NULL';
	if (! ($ids = dbColumnList ($session, T_Text, 'distinct page',
		$condition)))
		guiParagraph ($session, 'keine Verweise auf ' . $page_name
			. ' gefunden', false);
	else {
		echo '<p>Es gibt folgende Verweise auf ';
		guiInternLink ($session,  encodeWikiName ($session, $name), $page_name);
		echo '</p>' . "\n" . '<ulist>';
		foreach ($ids as $ii => $id) {
			$page = dbGetRecordById ($session, T_Page, $id, 'name');
			echo '<li>';
			guiInternLink ($session,  encodeWikiName ($session, $page [0]), $page [0]);
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
	case TT_Text:
	case M_Text:
		echo "<pre>\n";
		textToHtml ($session, $content);
		echo "\n</pre>";
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
	$session->trace (TC_Gui1, 'guiShowPageById: ' . $page . '/' . $text_id);
	list ($name, $type, $readgroup) = dbGetRecordById ($session,
		T_Page, $page, 'name,type,readgroup');
	if (! empty ($text_id) && $text_id > 0)
		$count_newer = dbSingleValue ($session, 'select count(id) from '
			. dbTable ($session, T_Text) . ' where page=' . (0+$page) . ' and id>'
			. $text_id);
	else {
		$count_newer = 0;
		list ($text_id) = dbGetRecordByClause ($session, T_Text,
			'max(id)', 'page=' . (0+$page));
	}
	$session->trace (TC_Gui1, 'guiShowPageById-2: ' . $count_newer);
	list ($content, $created_at, $created_by) = dbGetRecordById ($session,
		T_Text, $text_id, 'text,createdat,createdby');
	$has_changed = $name != $session->fPageName;
	$session->SetPageData ($name, $created_at, $created_by);
	if ($has_changed)
		$session->SetLocation ($name);
	$header = $count_newer == 0 ? $session->fPageName 
		: $session->fPageName . ' (Version ' . $text_id . ')';
	if ($type == TT_Wiki)
		guiStandardHeader ($session, $header, Th_HeaderWiki,
			Th_BodyStartWiki);
	else
		guiStandardHeader ($session, $header, Th_HeaderHTML,
			Th_BodyStartHTML);
	if ($count_newer > 0)
		guiParagraph ($session, 'Achtung: es existier'
			. ($count_newer == 1 ? 't eine neuere Version'
			: ('en ' . $count_newer . ' neuere Versionen')), false);
	guiFormatPage ($session, $type, $content);
	guiStandardBodyEnd ($session, $type == TT_Wiki ? Th_BodyEndWiki : Th_BodyEndHTML);
}

?>
