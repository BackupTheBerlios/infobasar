<?php
// This module is created by the install routine of InfoBasar.
// Do not change it!
// BeginOfClasses
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
		if ($_POST ['forum_postingsperpage'] <= 0)
			$_POST ['forum_postingsperpage'] = 10;
		if ($_POST ['forum_threadsperpage'] <= 0)
			$_POST ['forum_threadsperpage'] = 10;
		dbUpdate ($session, T_User, $id, 
			'postingsperpage=' . $_POST ['forum_postingsperpage']
			. ',threadsperpage=' . $_POST ['forum_threadsperpage'] . ',');
	}
	function userGetData (&$session){
		$session->trace (TC_Gui3, 'forum.UserGetData');
	}
	function overview(&$session){
		$session->trace (TC_Gui3, 'forum.overview');
		echo "<tr><td><br><strong>Modul Foren:</strong></td>\n<td> </td></tr><tr><td>";
		guiInternLink ($session, 'forumhome', 'Forenübersicht', 'forum');
		echo "</td><td>Auflistung der existierenden Foren</td></tr>\n";
		echo '<tr><td>';
		guiInternLink ($session, 'forumsearch', 'Forensuche', 'forum');
		echo "</td><td>Suche in den Foren</td></tr>\n";
	}
} // class module_forum
// EndOfClasses

function InitModules(&$session){
	if ($session->fModules == null){
		$session->fModules = array ();
		# ModuleLoop:
		# $session->fModules ["[Module]"] = new Module[Module] ();
		$session->fModules ["forum"] = new ModuleForum ();
		// EndLoop
	}
}

function modUserTableData(&$session, $uid){
	InitModules($session);
	foreach ($session->fModules as $name => $module)
		$module->userTableData ($session, $uid);
}
function modUserOwnData(&$session, $uid){
	InitModules($session);
	foreach ($session->fModules as $name => $module)
		$module->userOwnData ($session, $uid);
}
function modUserCheckData (&$session, $isnew, $id){
	$rc = "";
	InitModules($session);
	foreach ($session->fModules as $name => $module){
		$msg = $module->userCheckData ($session, $isnew, $id);
		if ($msg != null)
			$rc .= textToHtml ($msg) . "<br>\n";
	}
	return empty ($rc) ? null : $rc;
}
function modUserStoreData (&$session, $isnew, $uid){
	$rc = "";
	InitModules($session);
	foreach ($session->fModules as $name => $module)
		$msg = $module->userStoreData ($session, $isnew, $uid);
	return $rc;
}
function modUserGetData(&$session){
	InitModules($session);
	foreach ($session->fModules as $name => $module)
		$module->userGetData ($session);
}
function modOverview(&$session){
	InitModules($session);
	foreach ($session->fModules as $name => $module){
		$module->overview ($session);
	}
}
?>
