<?php
// forum.php: page handling of forums
// $Id: forum_module.php,v 1.1 2005/01/10 16:36:41 hamatoma Exp $
/*
Diese Datei ist Teil von InfoBasar.
Copyright 2004 hamatoma@gmx.de München
InfoBasar ist freie Software. Du kannst es weitergeben oder verändern
unter den Bedingungen der GNU General Public Licence.
Näheres siehe Datei LICENCE.
InfoBasar sollte nützlich sein, es gibt aber absolut keine Garantie
der Funktionalität.
*/
$start_time = microtime ();
define ('PHP_ModuleVersion', '0.7.0 (2005.01.05)');
set_magic_quotes_runtime(0);
error_reporting(E_ALL);

define ('PARAM_BASE', 300);

include "config.php";
include "classes.php";

$session_id = sessionStart ();

// ----------- Definitions
// Alle Designs:
define ('Th_UserTitles', PARAM_BASE + 1);
// Designspezifisch:
define ('Th_ThreadHeader', PARAM_BASE + 1); // aus 121
define ('Th_ThreadBodyStart', PARAM_BASE + 2);
define ('Th_ThreadBodyEnd', PARAM_BASE + 3);
define ('Th_NewThreadHeader', PARAM_BASE + 4);
define ('Th_NewThreadBodyStart', PARAM_BASE + 5);
define ('Th_NewThreadBodyEnd', PARAM_BASE + 6);
define ('Th_AnswerHeader', PARAM_BASE + 7);
define ('Th_AnswerBodyStart', PARAM_BASE + 8);
define ('Th_AnswerBodyEnd', PARAM_BASE + 9);

define ('Th_ForumHomeHeader', PARAM_BASE + 11); // aus 171
define ('Th_ForumHomeBodyStart', PARAM_BASE + 12);
define ('Th_ForumHomeBodyEnd', PARAM_BASE + 13);

define ('Th_SearchHeader', PARAM_BASE + 21);
define ('Th_SearchBodyStart', PARAM_BASE + 22);
define ('Th_SearchBodyEnd', PARAM_BASE + 23);


define ('A_Answer', 'answer');
define ('A_NewThread', 'newthread');
define ('A_ChangeThread', 'changethread');
define ('A_ShowThread', 'showthread');
define ('A_ShowForum', 'showforum');

// Änderungen auch in forum_inc.php erledigen:
define ('P_ForumSearch', 'forumsearch');
define ('P_ForumHome', 'forumhome');
define ('P_Forum', 'forum');
define ('P_Thread', 'thread');

$session = new Session ($start_time, $session_id, 
	$_SESSION ['session_user'], $_SESSION ['session_start'], $_SESSION ['session_no'],
	$db_type, $db_server, $db_user, $db_passw, $db_name, $db_prefix);
if (successfullLogin ($session)){
	$session->trace (TC_Init, 'forum.php: std_answer: ' . (empty ($std_answer) ? '' : "($std_answer)"));
	if (isset ($_GET ['action'])) {
		$session->trace (TC_Init, 'index.php: action: ' . $_GET ['action']);
		switch ($_GET ['action']){
		case A_NewThread: basePosting ($session, '', C_New); break;
		case A_Answer: basePosting ($session, '', C_New); break;
		case A_ChangeThread: basePostingChange ($session); break;
		case A_ShowThread: baseThread ($session); break;
		case A_ShowForum: baseForum ($session); break;
		case '': break;
		default:
			$session->trace (TC_Error, "index.php: unbek. Aktion: $action");
			baseFormTest ($session);
			break;
		}
	} elseif (isset ($_REQUEST ['std_answer']) || ! baseCallStandardPage ($session)) {
		$session->trace (TC_Init, 'forum.php: keine Standardseite');
		if (isset ($test))
			baseTest ($session);
		elseif (isset ($_POST ['forum_title']) || isset ($_POST ['forum_body']))
			baseForumSearchAnswer ($session, null);
		elseif (isset ($_POST ['posting_preview']) || isset ($_POST ['posting_insert'])
			|| isset ($_POST ['posting_change']))
			basePostingAnswer ($session);
		else {
			$session->SetLocation (P_ForumHome);
			baseForumHome ($session);
		}
	}
}
exit (0);

// ---------------------------------------------------------------------
function baseStandardLinkString (&$session, $page) {
	$session->trace (TC_Gui3, 'baseStandardLinkString');
	$rc = null;
	$module = null;
	switch ($page) {
	case P_ForumSearch: $header = 'Forumsuche'; break;
	case P_ForumHome: $header = 'Forenübersicht'; break;
	default: $header = null; break;
	}
	if ($header)
		$rc = guiInternLinkString ($session, $page, $header, $module);
	return $rc;
}
function baseStandardLink (&$session, $page) {
	$session->trace (TC_Gui3, 'baseStandardLink');
	echo baseStandardLinkString ($session, $page);
}

function getModuleInfo (&$name, &$description, &$copyright, &$version){
	$name = 'forum';
	$description = 'Diskussionsforen für den Infobasar';
	$copyright = '(C) 2004 Hamatoma AT gmx DOT de, München';
	$version = PHP_ModuleVersion;
}
function baseForumSearch (&$session, $message){
	$session->trace (TC_Gui1, 'baseForumSearch');
	getUserParam ($session, U_MaxHits, $maxhits);
	guiStandardHeader ($session, 'Forumsuche', Th_SearchHeader, Th_SearchBodyStart);
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	guiParagraph ($session, 'Hinweis: vorl&auml;ufig nur ein Suchbegriff m&ouml;glich', false);
	guiStartForm ($session, 'search', P_ForumSearch);
	outTableAndRecord ();
	outTableTextFieldButton ('Im Titel:', 'forum_titletext', null, 32, 64, 
		' ', 'forum_title', 'Suchen');
	outTableRecordDelim ();
	outTableTextFieldButton ('Im Beitrag:', 'forum_bodytext', null, 32, 64,
		' ', 'forum_body', 'Suchen');
	outTableRecordDelim ();
	outTableTextField ('Maximale Trefferzahl:', U_MaxHits, $maxhits, 10, 10);
	outTableAndRecordEnd ();
	guiFinishForm ($session, $session);
	guiStandardBodyEnd ($session, Th_SearchBodyEnd);
}
function forumSearch (&$session, $with_text, $condition, $to_find) {
	$session->trace (TC_Gui2, 'forumSearch');
	$rc = '';
	$row = dbFirstRecord ($session,
			'select id,subject,author,createdat,top,forum'
			. ($with_text ? ',text from ' : ' from ')
			. dbTable ($session, T_Posting)
			. ' where ' . $condition . ' order by changedat desc limit '
			. $_POST ['search_maxhits']);
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
function baseForumSearchAnswer (&$session){
	$session->trace (TC_Gui1, 'baseForumSearchAnswer');
	if (isset ($_POST ['forum_title'])) {
		if (empty ($_POST ['forum_titletext']))
			$message = '+++ kein Titelbestandteil angegeben';
		else
			$message = forumSearch ($session, false,
				'subject like ' . dbSqlString ($session, '%' . $_POST ['forum_titletext'] . '%'),
				$_POST ['forum_titletext']);
	} else {
		if (empty ($_POST ['forum_bodytext']))
			$message = '+++ kein Suchtext angegeben';
		else
			$message = forumSearch ($session, true,
				'text like ' . dbSqlString ($session, '%' . $_POST ['forum_bodytext'] . '%'),
				$_POST ['forum_bodytext']);
	}
	guiForumSearch ($session, $message);
}
function baseForumHome (&$session) {
	$session->trace (TC_Gui1, 'baseForumHome');
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
function baseForum (&$session) {
	$session->trace (TC_Gui1, 'baseForum');
	if (! isset ($_POST ['forum_id']) || ! isInt ($_POST ['forum_id']))
		$_POST ['forum_id'] = 1;
	$forum_id = $_POST ['forum_id'];
	if (empty ($_POST ['page_no']))
		$_POST ['page_no'] = 1;
	$page_no = $_POST ['page_no'];
	$forum = dbGetRecordById ($session, T_Forum, $forum_id, 'name,description');
	dbForumInfo ($session, $forum_id, $threads, $pages);
	$headline = 'Forum ' . $forum [0];
	guiStandardHeader ($session, $headline, Th_StandardHeader, Th_StandardBodyStart);
	guiParagraph ($session, $forum [1], true);
	guiParagraph ($session, 'Seite ' . $page_no . ' von ' . $pages . ' ('
		. (0 + $threads) . ($threads == 1 ? ' Thema)' : ' Themen)'), false);
	outTableAndRecord ();
	outTableInternLink ($session, null, P_Forum . '?forum_id=' . $forum_id
		. '&action=' . A_NewThread, 'Neues Thema');
	outTableDelim (AL_Right);
	guiPageLinks ($session, P_Forum . '?action=' . A_ShowForum
		. '&forum_id=' . $forum_id, $page_no, $pages);
	outTableDelimAndRecordEnd ();
	outTableEnd ();
	
	$id_list = dbIdListOfPage ($session, T_Posting,
		"forum=$forum_id and top is null order by id desc",
		$session->fUserThreadsPerPage, $page_no);
	outTableAndRecord (1);
	outTableCellStrong ('Thema');
	outTableCellStrong ('Autor');
	outTableCellStrong ('Antworten');
	outTableCellStrong ('Aufrufe');
	outTableCellStrong ('Letzter Beitrag');
	outTableCellStrong ('Thema');
	outTableRecordEnd ();
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
		outTableRecordAndDelim ();
		guiThreadPageLink ($session, $id, 1, $thread [2]);
		outTableDelimEnd ();
		outTableCell (htmlentities ($thread [0]));
		outTableCell ($answers + 0);
		outTableCell ($thread [4] + 0);
		outTableDelim ();
		echo $last [0];
		echo ' ';
		echo dbSqlDateToText ($session, $last [1]);
		outNewline ();
		guiInternLink ($session, P_Thread. '?action=' . A_ShowThread . '&posting_id=' . $last_id,
			$last [2]);
		outTableDelimAndRecordEnd ();
	}
	outTableEnd ();
	outTableAndRecord ();
	outTableInternLink ($session, null, P_Forum . '?forum_id=' . $forum_id
		. '&action=' . A_NewThread, 'Neues Thema');
	outTableDelim (AL_Right);
	guiPageLinks ($session, P_Forum . '?action=' . A_ShowForum
		. '&forum_id=' . $forum_id, $page_no, $pages);
	outTableDelimAndRecordEnd ();
	outTableEnd ();
	guiStandardBodyEnd ($session, Th_StandardBodyEnd);
}
function basePostingChange (&$session) {
	$session->trace (TC_Gui1, 'basePostingChange');
	list ($posting_subject, $posting_text) = dbGetRecordById ($session,
		T_Posting, $_POST ['posting_id'], 'subject,text');
	basePosting ($session, '', C_Change);
}
function basePosting (&$session, $message, $mode) {
	$session->trace (TC_Gui1, 'basePosting: ' . $mode);
	$headline = ($mode == C_New)
		? (empty ($_POST ['reference_id']) ? 'Neues Thema' : 'Antworten')
		: (empty ($_POST ['reference_id']) ? 'Thema ändern' : 'Antwort ändern');
	guiStandardHeader ($session, $headline, Th_AnswerHeader, Th_AnswerBodyStart);
	if (! empty ($_POST ['reference_id'])
		&& ($posting = dbGetRecordById ($session, T_Posting, $_POST ['reference_id'],
			'author,subject,text,forum'))) {
		guiHeadline ($session, 1, 'Beitrag: ' . $posting [1]);
		guiParagraph ($session, 'Autor: ' . $posting [0], true);
		wikiToHTML ($session, $posting [2]);
		guiLine ($session, 2);
		if ($mode == C_New) {
			$pos = strpos ($posting [1], 'Re: ');
			$_POST ['posting_subject'] = (is_int ($pos) ? '' : 'Re: ') . $posting [1];
		}
		$_POST ['forum_id'] = $posting [3];
	} else {
		if (isset ($_POST ['posting_text']))
			$_POST ['posting_text'] = str_replace ($_POST ['posting_text'], "\\'", "'");
		else
			$_POST ['posting_text'] = '';
	}
	if (isset ($_POST ['posting_preview'])) {
		guiHeadline ($session, 1, 'Vorschau');
		wikiToHtml ($session, $_POST ['posting_text']);
		guiLine ($session, 2);
	}
	guiHeadline ($session, 1, $headline);
	if (! empty ($message))
		guiParagraph ($session, $message, true);
	if ($mode == C_Change && ! isset ($_POST ['posting_text'])) {
		$posting = dbGetRecordById ($session, T_Posting, $_POST ['posting_id'],
			'subject,text,forum');
		$_POST ['forum_id'] = $posting [2];
		$_POST ['posting_text'] = $posting [1];
		$_POST ['posting_subject'] = $posting [0];
	}
	getUserParam ($session, U_TextAreaWidth, $textarea_width);
	getUserParam ($session, U_TextAreaHeight, $textarea_height);
	guiStartForm ($session, 'thread');
	guiHiddenField ('std_answer', 'j');
	guiHiddenField ('last_pagename', null);
	guiHiddenField ('forum_id',null);
	guiHiddenField ('thread_id',null);
	guiHiddenField ('posting_id', null);
	guiHiddenField ('reference_id', null);
	echo "<table border=\"0\">\n<tr><td>Thema:</td><td>";
	guiTextField ('posting_subject', null, $textarea_width, 64);
	echo "</td></tr>\n<tr><td>Text</td><td>";
	guiTextArea ('posting_text', null, $textarea_width,
		$textarea_height);
	echo '</td></tr><tr><td></td><td style="text-align: right;">Eingabefeld: Breite: ';
	guiTextField (U_TextAreaWidth, $textarea_width, 3, 3);
	echo " H&ouml;he: ";
	guiTextField (U_TextAreaHeight, $textarea_height, 3, 3);
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
function basePostingAnswer (&$session) {
	$session->trace (TC_Gui1, 'basePostingAnswer');
	$message = null;
	$mode = null;
	$_POST ['posting_text'] = textAreaToWiki ($session, $_POST ['posting_text']);
	if (isset ($_POST ['posting_preview'])) {
		$mode = (isset ($_POST ['posting_id']) 
			&& isInt ($_POST ['posting_id'])) ? C_Change : C_New;
	} elseif (isset ($_POST ['posting_insert'])) {
		$mode = C_New;
		if (empty ($_POST ['posting_subject']))
			$message = '+++ Thema fehlt';
		elseif (strlen ($_POST ['posting_text']) < 5)
			$message = '+++ Beitrag zu kurz';
		else {
			$date = dbSqlDateTime ($session, time ());
			$_POST ['thread_id'] = $_POST ['posting_id'] = dbInsert ($session, T_Posting,
				'createdat,changedat,forum,author,top,reference,subject,text',
				"$date,$date," . $_POST ['forum_id'] . ',' 
					. dbSqlString ($session, $session->fUserName)
					. ',' . (empty ($_POST ['thread_id']) ? 'null' : $_POST ['thread_id'])
					. ',' . (empty ($_POST ['reference_id']) ? 'null' : $_POST ['reference_id'])
					. ',' . dbSqlString ($session, $_POST ['posting_subject'])
					. ',' . dbSqlString ($session, $_POST ['posting_text']));
			dbUpdateRaw ($session, T_User, $session->fUserId,
				'postings=postings+1');
			baseForum ($session);
			$mode = NULL;
		}
	} elseif (isset ($_POST ['posting_change'])) {
		$mode = C_Change;
		if (empty ($_POST ['posting_subject']))
			$message = '+++ Thema fehlt';
		elseif (strlen ($_POST ['posting_text']) < 5)
			$message = '+++ Beitrag zu kurz';
		else {
			$date = dbSqlString ($session, time ());
			dbUpdate ($session, T_Posting, $_POST ['posting_id'],
				'changedby=' . dbSqlString ($session, $session->fUserName)
				. ',changedat=' . $date
				. ',subject=' . dbSqlString ($session, $_POST ['posting_subject'])
				. ',text=' . dbSqlString ($session, $_POST ['posting_text']) . ',');
			baseThread ($session);
			$mode = NULL;
		}
	}
	if ($mode)
		basePosting ($session, $message, $mode);
}
function baseThread (&$session) {
	$session->trace (TC_Gui1, 'baseThread');
	if (empty ($_POST ['thread_id'])) {
		if (empty ($_POST ['posting_id']))
			$_POST ['thread_id'] = 1;
		else {
			$_POST ['thread_id'] = dbSingleValue ($session,
				'select top from ' . dbTable ($session, T_Posting)
				. ' where id=' . $_POST ['posting_id']);
			if (empty ($_POST ['thread_id']))
				$_POST ['thread_id'] = $_POST ['posting_id'];
		}
	}
	if (! isset ($_POST ['page_no']))
		$_POST ['page_no'] = isset ($_POST ['posting_id'])
			? dbPageOfPosting ($session, $_POST ['thread_id'], $_POST ['posting_id']) : 1;
	$id_list = dbIdListOfThreadPage ($session, $_POST ['thread_id'], $_POST ['page_no']);
	$thread = dbGetRecordById ($session, T_Posting, $_POST ['thread_id'],
		'author,subject,text,createdat,changedby,changedat,calls,forum');
	dbThreadInfo ($session, $_POST ['thread_id'], $answers, $pages, $last);
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
			. '&thread_id=' . $_POST ['thread_id'], $_POST ['page_no'], $pages);
	}
	echo "</td></tr></table>\n";

	dbUpdateRaw ($session, T_Posting, $_POST ['thread_id'],
		'calls=calls+1');
	echo '<table width="100%" border="0">' . "\n";
	// Autor Thema-Text
	foreach ($id_list as $ii => $id) {
		# echo "alle Postings: $ii / $id<br>";
		if ($id == $_POST ['thread_id'])
			$posting = $thread;
		else
			$posting = dbGetRecordById ($session, T_Posting, $id,
				'author,subject,text,createdat,changedby,changedat');
		dbGetAuthorInfo ($session, $posting [0], $author_link,
			$postings, $avatar, $ranking);
		echo '<tr><td style="vertical-align:top">';
		guiLine ($session, 1);
		echo $thread[0] . '<br/>'
			. ($postings + 0) . ' Beitr&auml;ge<br/>' . $ranking;
		if (! empty ($avatar))
			echo '<br/>' . $avatar;
		echo '</td><td>';
		guiLine ($session, 1);
		if ($id == $_POST ['thread_id'])
			guiHeadline ($session, 3, 'Thema: ' . $posting [1]);
		else
			guiHeadline ($session, 3, 'Antwort '
				. ($ii + $session->fUserPostingsPerPage * ($_POST ['page_no'] - 1))
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
			. '?action=' . A_Answer . '&thread_id=' . $_POST ['thread_id'] . "&reference_id=$id",
			'Antworten');
		if (strcmp ($posting [0], $session->fUserName) == 0) {
			echo ' ';
		guiInternLink ($session, P_Thread
			. '?action=' . A_ChangeThread . '&thread_id=' . $_POST ['thread_id'] 
			. "&posting_id=$id",
			'Ändern');
		echo "</p>\n";
		}
		echo "</td><tr>\n";
	}
	echo "</table>\n";
	guiStandardBodyEnd ($session, Th_ThreadBodyEnd);
}
function baseCallStandardPage (&$session) {
	$session->trace (TC_Gui2, 'baseCallStandardPage');
	$found = true;
	switch ($session->fPageName) {
	case P_Login: guiLogin ($session, null); break;
	case P_Logout: guiLogout ($session); null;
	case P_ForumHome: baseForumHome ($session); break;
	case P_Forum: baseForum ($session); break;
	case P_Thread: baseThread ($session); break;
	case P_ForumSearch: baseForumSearch ($session, null); break;
	case '!test': baseTest ($session); break;
	case '!form': baseFormTest ($session); break;
	default:
		$session->trace (TC_Gui2, 'baseCallStandardPage-kein Std');
		$found = false;
		break;
	}
	return $found;
}
function modStandardLinks (&$session){
	guiInternLink ($session, P_Home, "Überblick", "index");
	echo ' | ';
	baseStandardLink ($session, P_ForumHome);
	echo ' | ';
	baseStandardLink ($session, P_ForumSearch);
}

?>