<?php
// gui.php: functions for Graphical User Interface
// $Id: gui.php,v 1.21 2005/01/05 05:27:21 hamatoma Exp $
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
		returnTAG_STRONG;
	else
		return TAG_STRONG . $text . TAG_STRONG_END;
}
function outStrong($text = null){
	if ($text == null)
		echo TAG_STRONG;
	else {
		echo TAG_STRONG;
		echo $text;
		echo TAG_STRONG_END;
	}
}
function tagQuotation($text = null){
	if ($text == null)
		return TAG_CITE;
	else
		return TAG_CITE . $text . TAG_CITE_END;
}
function outQuotation($text = null){
	if ($text == null)
		echo TAG_CITE;
	else {
		echo TAG_CITE;
		echo $text;
		echo TAG_CITE_END;
	}
}
function tagStrongEnd(){
	return TAG_STRONG;
}
function outStrongEnd(){
	echo TAG_STRONG_END;
}

function tagNewline(){
	return TAG_NEWLINE;
}
function outNewline(){
	echo TAG_NEWLINE;
}
function tagParagraph(){
	return TAG_PARAGRAPH;
}
function outParagraph(){
	echo TAG_PARAGRAPH;
}
function tagParagraphEnd(){
	return TAG_PARAGRAPH_END;
}
function outParagraphEnd(){
	echo TAG_PARAGRAPH_END;
}
function tagTable($border = 0){
	return "\n" . TAG_TABLE_OPEN . TAGA_BORDER . (0+$border) . TAG_APO_SUFFIX; 
}
function outTable($border = 0, $width = 0){
	echo "\n";
	echo TAG_TABLE_OPEN;
	echo TAGA_BORDER;
	echo $border;
	if ($width != null){
		echo TAGA_APO_WIDTH;
		echo $width;
	}
	echo TAG_APO_SUFFIX; 
}
function tagTableEnd(){
	return TAG_TABLE_END; 
}
function outTableEnd(){
	echo TAG_TABLE_END; 
}
function tagTableRecord(){
	return TAG_TABLE_RECORD;
}
function outTableRecord(){
	echo TAG_TABLE_RECORD;
}
function tagTableAndRecord($border = 0){
	return tagTable ($border) . TAG_TABLE_RECORD;
}
function outTableAndRecord($border = 0){
	outTable ($border);
	echo TAG_TABLE_RECORD;
}
function tagTableAndRecordEnd(){
	return tagTableRecordEnd() . TAG_TABLE_RECORD_END;
}
function outTableAndRecordEnd(){
	outTableRecordEnd();
	echo TAG_TABLE_END;
}
function tagTableRecordEnd(){
	return TAG_TABLE_RECORD_END;
}
function outTableRecordEnd(){
	echo TAG_TABLE_RECORD_END;
}
function tagTableDelim($halignment = AL_None){
	if ($halignment == AL_None)
		return TAG_TABLE_DELIM;
	else
		return TAG_TABLE_DELIM_ALIGN . $alignment .TAG_SUFFIX;
}
function outTableDelim($halignment = AL_None){
	if ($halignment == AL_None)
		echo TAG_TABLE_DELIM;
	else{
		echo TAG_TABLE_DELIM_ALIGN;
		echo $halignment;
		echo TAG_SUFFIX;
	}
}
function tagTableRecordAndDelim($halignment = AL_None){
	return TAG_TABLE_RECORD . tagTableDelim ($halignment);
}
function outTableRecordAndDelim($halignment = AL_None){
	echo TAG_TABLE_RECORD;
	outTableDelim ($halignment);
}
function tagTableDelimEnd(){
	return TAG_TABLE_DELIM_END;
}
function outTableDelimEnd(){
	echo TAG_TABLE_DELIM_END;
}
function tagTableDelimAndRecordEnd(){
	return TAG_TABLE_DELIM_RECORD_END;
}
function outTableDelimAndRecordEnd(){
	echo TAG_TABLE_DELIM_RECORD_END;
}
function tagTableCellDelim(){
	return TAG_TABLE_DELIM_END_DELIM;
}
function outTableCellDelim(){
	echo TAG_TABLE_DELIM_END_DELIM;
}
function tagTableRecordDelim(){
	return TAG_TABLE_RECORD_END_RECORD;
}
function outTableRecordDelim(){
	echo TAG_TABLE_RECORD_END_RECORD;
}
function tagTableCell($text, $halignment = AL_None){
	return tagTableDelim ($halignment) . htmlentities ($text) . tagTableDelimEnd();
}
function outTableCell($text, $halignment = AL_None){
	outTableDelim ($halignment);
	echo $text;
	echo TAG_TABLE_DELIM_END;
}
function outTableRecordCells ($text1, $text2, $text3 = null){
	echo TAG_TABLE_RECORD;
	outTableCell ($text1);
	outTableCell ($text2);
	if ($text3 != null)
		outTableCell ($text3);
	outTableRecordEnd();
}
function outTableCellStrong($text, $halignment = AL_None){
	outTableDelim ($halignment);
	echo TAG_STRONG;
	echo $text;
	echo TAG_STRONG_END;
	echo TAG_TABLE_DELIM_END;
}
function tagTableCellConvert($text, $halignment = AL_None){
	return tagTableDelim ($halignment) . htmlentities ($text) . tagTableDelimEnd();
}
function outTableCellConvert($text, $halignment = AL_None){
	outTableDelim ($halignment);
	echo htmlentities ($text);
	echo TAG_TABLE_DELIM_END;
}
function outTableTextField ($prefix, $name, $value, $size, 
		$maxsize=0, $halignment = AL_None){
	if ($prefix != null)
		outTableCell ($prefix);
	outTableDelim($halignment);
	guiTextField ($name, $value, $size, $maxsize);
	echo TAG_TABLE_DELIM_END;
}
function outTableTextField2 ($prefix, $name1, $value1, $size1, 
		$maxsize1, $delim, $name2, $value2, $size2, $maxsize2 = 0){
	if ($prefix != null)
		outTableCell ($prefix);
	echo TAG_TABLE_DELIM;
	guiTextField ($name1, $value1, $size1, $maxsize1);
	echo $delim;
	guiTextField ($name2, $value2, $size2, $maxsize2);
	echo TAG_TABLE_DELIM_END;
}
function outTablePasswordfield ($prefix, $name, $value, $size, 
		$maxsize=0, $halignment = AL_None){
	if ($prefix != null)
		outTableCell ($prefix);
	outTableDelim($halignment);
	if ($value == null)
		$value = isset ($_POST [$name]) ? $_POST [$name] : '' ;
	guiPasswordField ($name, $value, $size, $maxsize);
	echo TAG_TABLE_DELIM_END;
}
function outTableButton ($prefix, $name, $text, $halignment = AL_None){
	if ($prefix != null)
		outTableCell ($prefix);
	outTableDelim ($halignment);
	guiButton ($name, $text);
	echo TAG_TABLE_DELIM_END; 
}
function outTableButton2 ($prefix, $name1, $text1, $delim, $name2, $text2, 
		$halignment = AL_None){
	if ($prefix != null)
		outTableCell ($prefix);
	outTableDelim ($halignment);
	guiButton ($name1, $text1);
	echo $delim;
	guiButton ($name2, $text2);
	echo TAG_TABLE_DELIM_END; 
}
function outTableCheckBox ($prefix, $name, $text, $selected = null, $halignment = AL_None){
	if ($prefix != null)
		outTableCell ($prefix);
	outTableDelim ($halignment);
	guiCheckBox ($name, $text, $selected);
	echo TAG_TABLE_DELIM_END; 
}
function outTableComboBox ($prefix, $name, $names, $values, $index_selected,
		$halignment = AL_None){
	if ($prefix != null)
		outTableCell ($prefix);
	outTableDelim ($halignment);
	guiComboBox ($name, $names, $values, $index_selected);
	echo TAG_TABLE_DELIM_END; 
}
function outTableInternLink (&$session, $prefix, $link, $text = null, $module = null, 
		$halignment = AL_None){
	if ($prefix != null)
		outTableCell ($prefix);
	outTableDelim ($halignment);
	guiInternLink ($session, $link, $text, $module);
	echo TAG_TABLE_DELIM_END; 
}
function outTableRecordInternLink (&$session, $suffix, $link, $text = null, $module = null, 
		$halignment = AL_None){
	outTableDelim ($halignment);
	guiInternLink ($session, $link, $text, $module);
	echo TAG_TABLE_DELIM_END; 
	if ($suffix != null)
		outTableCell ($suffix);
	echo TAG_TABLE_RECORD_END;
}
function outTableAuthorLink (&$session, $author){
	echo TAG_TABLE_DELIM;
	guiAuthorLink ($session, $author);
	echo TAG_TABLE_DELIM_END;
}
function outTableTextArea ($prefix, $name, $content, $width, $height){
	if ($prefix != null)
		outTableCell ($prefix);
	echo TAG_TABLE_DELIM;
	guiTextArea ($name, $content, $width, $height);
	echo TAG_TABLE_DELIM_END;
}

// --- Allgemeine Funktionen --------------
function guiField ($name, $type, $text, $size, $maxlength, $special){
	echo TAG_INPUT_TYPE;
	echo $type;
	echo TAGA_NAME;
	echo $name;
	echo TAG_APO;
	if (! empty ($text)){
		 echo TAGA_VALUE;
		 echo $text;
		 echo TAG_APO;
	}
	if ($size > 0){
		echo TAGA_SIZE;
		echo $size;
		echo TAG_APO;
	}
	if ($maxlength > 0){
		echo TAGA_MAXSIZE;
		echo $maxlength;
		echo TAG_APO;
	}
	if (! empty ($special))
		echo ' ' . $special;
	echo TAG_SUFFIX;
}
function guiFileField ($name){
	echo TAG_INPUT_TYPE;
	echo TAGAV_FILE;
	echo TAGA_NAME;
	echo $name;
	echo TAG_APO_SUFFIX_NEWLINE; 
}
function guiHiddenField ($name, $text = null) {
	if ($text == null)
		$text = isset ($_POST [$name]) ? $_POST [$name] : "";
	guiField ($name, TAGAV_HIDDEN, $text, 0, 0, null);
}
function guiTextField ($name, $text, $size, $maxlength = 0){
	if ($text == null)
		$text = isset ($_POST [$name]) ? $_POST [$name] : "";
	guiField ($name, TAGAV_TEXT, $text, $size, $maxlength, null);
}
function guiPasswordField ($name, $text, $size, $maxlength){
	guiField ($name, TAGAV_PASSWORD, $text, $size, $maxlength, null);
}
function guiTextArea ($name, $content, $width, $height){
	echo TAG_TEXTAREA_NAME;
	echo $name;
	echo TAGA_APO_COLS;
	echo $width;
	echo TAGA_APO_ROWS;
	echo $height;
	echo TAG_APO_SUFFIX_NEWLINE;
	if ($content == null && isset ($_POST [$name]))
		$content = $_POST [$name];
	echo $content;
	echo TAG_TEXTAREA_END;
}
function guiButton ($name, $text){
	echo TAG_INPUT_WIKIACTION_NAME;
	echo $name;
	echo TAGA_APO_VALUE;
	echo $text;
	echo TAGA_SUBMIT_END;
}
function guiButton2 ($name, $text, $delim, $name2, $text2){
	guiButton ($name, $text);
	if (! empty ($delim))
		echo $delim;
	guiButton ($name2, $text2);
}
function guiLinkAsButton (&$session, $command, $text){
	guiInternLink ($session, encodeWikiName ($session, $session->fPageName) 
		. '?action=' . $command, $text);
}
function guiRadioButton ($name, $text, $checked){
	guiField ($name, TAGAV_RADIO, $text, 0, 0,
		isset ($checked) && $checked ? "checked" : "");
}
function guiCheckBox ($name, $text, $checked = false){
	if ($checked == null)
		$checked = isset ($_POST [$name]) && $_POST [$name] == C_CHECKBOX_TRUE;
	guiField ($name, TAGAV_CHECKBOX, C_CHECKBOX_TRUE, 0, 0,
		isset ($checked) && $checked ? TAGAV_CHECKED : "");
	echo htmlentities ($text) . " ";
}
function guiChecked(&$session, $name){
	return isset ($_POST [$name]) && $_POST [$name] == C_CHECKBOX_TRUE;
}
function guiComboBox ($name, $options, $values = null, $ix_selected = 0) {
	echo TAG_SELECT_NAME;
	echo $name;
	echo TAGA_APO_SIZE_END;
	if ($ix_selected == null)
		$ix_selected = ! isset ($_POST [$name]) 
			? -1 : indexOf ($options, $_POST [$name]); 
	foreach ($options as $ix => $text){
		echo TAG_OPTION;
		if ($ix == $ix_selected)
			echo TAGAV_SELECTED;
		if ($values){
			echo TAGA_VALUE;
			echo $values[$ix];
			echo  TAG_APO;
		}
		echo TAG_SUFFIX;
		echo  htmlentities ($text);
		echo "\n";
	}
	echo TAG_SELECT_END;
}
function guiUploadFile (&$session, $prefix, $lastpage = null,
		$custom_field = null, $custom_value = null,
		$caption = 'Hochladen', 
		$button = 'upload_go', $file = 'upload_file', $max_file_size = 100000) {
	global $last_pagename;
	echo TAG_FORM_MULTIPART_POST_ACTION;
	echo $session->fScriptURL;
	echo TAG_APO_SUFFIX_NEWLINE;
	guiHiddenField ('last_pagename', $lastpage ? $lastpage : $last_pagename);
	if ($custom_field)
		guiHiddenField ($custom_field, $custom_value);
	guiHiddenField ('MAX_FILE_SIZE', $max_file_size);
	if (! empty ($prefix))
		echo $prefix . ' ';
	echo TAG_INPUT_FILE_NAME;
	echo $file;
	echo TAG_APO_SUFFIX_NEWLINE;
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
	echo TAG_HRULE_HEIGHT;
	echo (0+$width);
	echo TAGA_PX_END;
}
function guiFinishBody (&$session, $param_no){
	$session->trace (TC_Gui2, 'guiFinishBody');
	if (! empty ($param_no)
		&& ($text = guiParam ($session, $param_no, null)) != null
		&& ! empty ($text))
		echo $text;
	else {
		guiLine ($session, 1);
		modStandardLinks ($session);
		echo TAG_BODY_HTML_END;
	}
}
function guiParagraph (&$session, $text, $convert){
	$session->trace (TC_Gui2, 'guiParagraph');
	echo TAG_PARAGRAPH;
	if ($convert)
		echo textToHtml ($text);
	else
		echo $text;
	echo TAG_PARAGRAPH_END;
}
function guiHeader (&$session, $title) {
	$session->putHeader ();
	$session->trace (TC_Gui1, 'guiHeader');
	echo TAG_HEAD;
	echo "\r\n";
	$value = dbGetText ($session, Th_Header);
	if (! empty ($value))
		echo $value;
	else
		echo TAG_META;
	$value = dbGetText ($session, Th_CSSFile);
	if (! empty ($value)){
		echo TAG_LINK_STYLESHEET;
		echo $value;
		echo TAG_APO_SUFFIX_NEWLINE;
	}
	if (! empty ($title)){
		echo TAG_TITLE;
		echo $title;
		echo TAG_TITLE_END_BODY;
	}
	$session->SetHasBody();
	if ($session->fBodyLines){
		echo join ("\n", $session->fBodyLines);
		echo "\n";
	}
}
function guiHeadline (&$session, $level, $text) {
	$session->trace (TC_Gui2, 'guiHeadline');
	echo TAG_PREFIX;
	echo TAGN_HEADLINE;
	echo $level;
	echo TAG_SUFFIX;
	echo htmlentities ($text);
	echo TAG_ENDPREFIX;
	echo TAGN_HEADLINE;
	echo $level;
	echo TAG_SUFFIX_NEWLINE;
}
function guiStartForm (&$session, $pagename = null) {
	$session->trace (TC_Gui2, 'guiStartForm');
	echo TAG_FORM_POST_ACTION;
	echo $session->fScriptURL;
	echo TAG_APO_SUFFIX_NEWLINE;
	if ($pagename)
		guiHiddenField ('last_pagename', $pagename);
}
function guiFinishForm (&$session){
	$session->trace (TC_Gui2, 'guiFinishForm');
	if (! $session->fFormExists) {
		$session->setFormExists(true);
	}
	echo TAG_FORM_END;
}
function guiExternLinkString(&$session, $link, $text) {
	$session->trace (TC_Gui2, 'guiExternLinkString');
	if (empty ($text))
		$text = $link;
	return TAG_ANCOR_HREF . $link . TAG_APO_SUFFIX . htmlToText ($session, $text) 
		. TAG_ANCOR_END;
}

function guiExternLink (&$session, $link, $text) {
	$session->trace (TC_Gui2, 'guiExternLink');
	echo TAG_ANCOR_HREF;
	echo $link;
	echo TAG_APO_SUFFIX;
	echo htmlToText ($session, $text);
	echo TAG_ANCOR_END;
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
		echo TAG_HEAD_TITLE;
		echo htmlentities ($title);
		echo  TAG_TITLE_HEAD_END;
	} else
		echo $session->replaceMacrosNoHTML ($header);
	if ($pos_body > 0) {
		$header = dbGetText ($session, $pos_body);
		if (empty ($header))
			$pos_body = 0;
		else
			echo $session->replaceMacrosNoHTML ($header);
	}
	if ($pos_body == 0){
		echo TAG_BODY_H1;
		echo $title;
		echo TAG_H1_END;
	}
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
			echo TAG_BODY_END;
		}
	}
	echo TAG_BODY_HTML_END;
}
// --------------------------------
function guiBacklinks (&$session, $page_name) {
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
		echo TAG_PARAGRAPH;
		echo 'Es gibt folgende Verweise auf ';
		guiInternLink ($session,  encodeWikiName ($session, $name), $page_name);
		echo TAG_PARAGRAPH_END;
		echo TAG_ULIST;
		foreach ($ids as $ii => $id) {
			$page = dbGetRecordById ($session, T_Page, $id, 'name');
			echo TAG_LISTITEM;
			guiInternLink ($session,  encodeWikiName ($session, $page [0]), $page [0]);
			echo TAG_LISTITEM_END;
		}
		echo TAG_ULIST_END;
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
		echo TAG_PRE;
		textToHtml ($session, $content);
		echo TAG_PRE_END;
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
		echo TAG_TABLE_OPEN;
		echo $table_properties;
		echo TAG_SUFFIX_NEWLINE;
		if ($header) {
			echo TAG_TABLE_RECORD;
			for ($ii = 0; $ii < count ($header); $ii++){
				echo TAG_TABLE_DELIM_BOLD;
				echo htmlentities ($header [$ii]);
				echo TAG_TABLE_BOLD_DELIM_END;
			}
			echo TAG_TABLE_DELIM_END;
		}
	}
	while ($row) {
		echo TAG_TABLE_RECORD;
		for ($ii = 0; $ii < count ($row); $ii++){
			echo TAG_TABLE_DELIM;
			echo htmlentities ($row [$ii]);
			echo TAG_TABLE_DELIM_END;
		}
		echo TAG_TABLE_RECORD_END;
		$row = dbNextRecord ($session);
	}
	if ($has_table)
		echo TAG_TABLE_END;
}
function guiThreadPageLink (&$session, $thread_id, $page_no, $text) {
	$session->trace (TC_Gui3, 'guiThreadPageLink');
	guiInternLink ($session, P_Thread . '?action=' . A_ShowThread
		. '&thread_id=' . $thread_id . '&page_no=' . $page_no, $text);
}
function guiPageLinks (&$session, $prefix, $page_no, $pages){
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
