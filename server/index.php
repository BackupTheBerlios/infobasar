<?php
// index.php: Start page of the InfoBasar
// $Id: index.php,v 1.1 2004/05/20 20:55:05 hamatoma Exp $
session_start();

 // If this is a new session, then the variable $user_id
 if (!session_is_registered("session_user")) {
	session_register("session_user");
	session_register("session_start");
	$start = time();
 }
 $session_id = session_id();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<!-- $Id: index.php,v 1.1 2004/05/20 20:55:05 hamatoma Exp $ -->
<?php
define ('C_ScriptName', 'index.php');

if (!defined('ADMIN')) { // index.php not included by admin.php?
	include "config.php";

	$session = new Session ();
	init ($session, $dbType);

      // All requests require the database
	dbOpen($session);

	//p ('User,Id,Login: ' . $session_user . "," . $session_id . "/" . $login_user);
	$rc = dbCheckSession ($session);
	if (! empty ($rc)) {
		// p ("Keine Session gefunden: $session_id / $session_user ($rc)");
		if (! empty ($login_user))
			guiLoginAnswer ($session);
		else
			guiLogin ($session, '');
	} else {
			$session->trace (TC_Init, 'index.php: std_answer: ' . (empty ($std_answer) ? '' : "($std_answer)"));
		if (isset ($action)) {
			$session->trace (TC_Init, "index.php: action: $action");
			switch ($action){
			case A_Edit: guiEditPage ($session, null); break;
			case A_Search:	guiSearch ($session, ''); break;
			case A_NewThread: guiPosting ($session, '', C_New); break;
			case A_Answer: guiPosting ($session, '', C_New); break;
			case A_ChangeThread: guiPostingChange ($session); break;
			case A_ShowThread: guiThread ($session); break;
			case A_ShowForum: guiForum ($session); break;
			case A_PageInfo: guiPageInfo ($session); break;
			case A_ShowText: guiShowPageById ($session, $page_id, $text_id); break;
			case A_Diff: guiDiff ($session); break;
			case A_Show: guiShowCurrentPage ($session); break;
			case '': break;
			default:
				$session->trace (TC_Error, "index.php: unbek. Aktion: $action");
				guiFormTest ($session);
				break;
			}
		} elseif (isset ($std_answer) || ! guiCallStandardPage ($session)) {
			$session->trace (TC_Init, 'index.php: keine Standardseite'
				. (isset ($edit_save) ? " ($edit_save)" : ' []'));
			if (isset ($test))
				guiTest ($session);
			else if (substr ($session->fPageName, 0, 1) == '.')
				guiNewPageReference ($session);
			else if (isset ($alterpage_insert))
				guiAlterPageAnswer ($session, C_New);
			elseif (isset ($alterpage_changecontent))
				guiAlterPageAnswer ($session, C_Change);
			elseif (isset ($alterpage_preview))
				guiAlterPageAnswer ($session, C_LastMode);
			elseif (isset ($alterpage_changepage))
				guiAlterPageAnswerChangePage($session);
			elseif (isset ($account_new) || isset ($account_change)
				|| isset ($account_other))
				guiAccountAnswer ($session, $account_user);
			elseif (isset ($login_user))
				guiLoginAnswer ($session);
			elseif (isset ($search_title) || isset ($search_body))
				guiSearchAnswer ($session, '');
			elseif (isset ($forum_title) || isset ($forum_body))
				guiForumSearchAnswer ($session, null);
			elseif (isset ($edit_preview))
				guiEditPage ($session, null);
			elseif (isset ($edit_save))
				guiEditPageAnswerSave ($session);
			elseif (isset ($edit_cancel))
				guiShowPageById ($session, $edit_pageid, null);
			elseif (isset ($posting_preview) || isset ($posting_insert)
				|| isset ($posting_change))
				guiPostingAnswer ($session);
			elseif ( ($page_id = dbPageId ($session, $session->fPageName)) > 0)
				guiShowPageById ($session, $page_id, null);
			else {
				$session->trace (TC_Warning, PREFIX_Warning . 'index.php: Nichts gefunden');
				guiHome ($session);
			}
		}
	}
}
?>
