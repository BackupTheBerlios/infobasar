<?php
class ModuleForum {

	function userTableData (&$session, $id){
		$session->trace (TC_Gui3, 'forum.UserTableData');
		$name = 'forum';
		outTableRecord();
		outTableCellStrong (TAG_NEWLINE . 'Modul ' . $name);
		$rec = dbGetRecordById ($session, T_User, $id,
			'postingsperpage,threadsperpage,postings');
		outTableRecordDelim();
		outTableTextField ($session, 'Beiträge je Seite:', 'forum_postingsperpage',
			 $rec [0], 3, 3);
		outTableRecordDelim();
		outTableTextField ($session, 'Forumsinhalt: Themen je Seite:', 'forum_threadsperpage' , $rec [1], 3, 3);
		outTableRecordEnd();
		outTableRecordCells ('Bisher erstellte Forumsbeiträge:', $rec [2]);		
	}
	function userOwnData (&$session, $id) {
		$session->trace (TC_Gui3, 'forum.UserOwnData');
	}
	function userCheckData (&$session){
		$session->trace (TC_Gui3, 'forum.UserCheckData');
		return null;
	}
	function userStoreData (&$session, $isnew, $id){
		$session->trace (TC_Gui3, 'forum.UserStoreData');
		global $forum_postingsperpage, $forum_threadsperpage;
		if ($forum_postingsperpage <= 0)
			$forum_postingsperpage = 10;
		if ($forum_threadsperpage <= 0)
			$forum_threadsperpage = 10;
		dbUpdate ($session, T_User, $id, 
			'postingsperpage=' . $forum_postingsperpage
			. ',threadsperpage=' . $forum_threadsperpage . ',');
	}
	function userGetData (&$session){
		$session->trace (TC_Gui3, 'forum.UserGetData');
	}
	function overview(&$session){
		$session->trace (TC_Gui3, 'forum.overview');
		outTableRecord();
		outNewLine();
		outTableCellStrong(tagNewline () . 'Modul Foren');
		outTableRecordEnd();
		outTableRecordInternLink ($session, 'Auflistung der existierenden Foren.',
			'forumhome', 'Forenübersicht', 'forum');
		outTableRecordInternLink ($session, 'Suche in den Foren.',
			'forumsearch', 'Forensuche', 'forum');
	}
} // class module_forum

?>