<?php
// This module is created by the install routine of InfoBasar.
// Do not change it!
// BeginOfClasses
class ModuleForum {

	function userTableData (&$session, $id){
		$session->trace (TC_Gui3, 'forum.UserTableData');
		$name = "forum";
		echo '<tr><td><br><strong>Modul ';
		echo $name;
		echo ":</strong></td><td> </td</tr>\n"; 
		echo '<tr><td>Beitr&auml;ge je Seite:</td><td>';
		$rec = dbGetRecordById ($session, T_User, $id,
			'postingsperpage,threadsperpage,postings');
		guiTextField ('forum_postingsperpage' , $rec [0], 3, 3);
		echo "</td></tr><tr><td>Forumsinhalt: Themen je Seite:</td><td>\n";
		guiTextField ('forum_threadsperpage' , $rec [1], 3, 3);
		echo "</td></tr>\n<tr><td>Bisher erstellte Forumsbeiträge:</td><td>";
		echo $rec [2];
		echo "</td></tr>\n";
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
		echo "<tr><td><br><strong>Modul forum:</strong></td>\n<td> </td></tr><tr><td>";
		guiInternLink ($session, 'forumhome', 'Forenübersicht', 'forum');
		echo "</td><td>Auflistung der existierenden Foren</td></tr>\n";
		echo '<tr><td>';
		guiInternLink ($session, 'forumsearch', 'Forensuche', 'forum');
		echo "</td><td>Suche in den Foren</td></tr>\n";
	}
} // class module_forum

// ModuleFunction: InitModules
function InitModules(&$session){
	if ($session->fModules == null){
		$session->fModules = array ();
		// ModuleLoop:
		// $session->fModules ["[Module]"] = new [Module]Forum ();
		$session->fModules ["forum"] = new ModuleForum ();
		// EndLoop
	}
}
// EndOfClasses

// ModuleFunction: modUserTableData
function modUserTableData(&$session, $uid){
	InitModules($session);
	// ModuleLoop:
	// $session->fModules ["[Module]"]->userTableData ($session);
	$session->fModules ["forum"]->userTableData ($session, $uid);
	// EndLoop
}
// ModuleFunction: modUserOwnData
function modUserOwnData(&$session, $uid){
	InitModules($session);
	// ModuleLoop:
	// $session->fModules ["[Module]"]->userOwnData ($session, $uid);
	$session->fModules ["forum"]->userOwnData ($session, $uid);
	// EndLoop
}
// ModuleFunction: modUserCheckData
function modUserCheckData (&$session, $isnew, $id){
	$rc = "";
	InitModules($session);
	// ModuleLoop:
	// $msg = $session->fModules ["[Module]"]->userCheckData ($session, $isnew, $id);
	// if ($msg != null)
	// 	$rc .= textToHtml ($msg) . "<br>\n";
	$msg = $session->fModules ["forum"]->userCheckData ($session, $isnew, $id);
	if ($msg != null)
		$rc .= textToHtml ($msg) . "<br>\n";
	// EndLoop
	return empty ($rc) ? null : $rc;
}
// ModuleFunction: modUserStoreData
function modUserStoreData (&$session, $isnew, $uid){
	$rc = "";
	InitModules($session);
	// ModuleLoop:
	// $session->fModules ["[Module]"]->userStoreData ($session, $isnew, $id);
	$msg = $session->fModules ["forum"]->userStoreData ($session, $isnew, $uid);
	// EndLoop
	return $rc;
}
// ModuleFunction: modUserGetData
function modUserGetData(&$session){
	InitModules($session);
	// ModuleLoop:
	// $session->fModules ["[Module]"]->userGetData ($session);
	$session->fModules ["forum"]->userGetData ($session);
	// EndLoop
}
// ModuleFunction: modOverview
function modOverview(&$session){
	InitModules($session);
	// ModuleLoop:
	// $session->fModules ["[Module]"]->overview ($session);
	$session->fModules ["forum"]->overview ($session);
	// EndLoop
}
?>