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
		echo "</td></tr>\n<tr><td>Bisher erstellte Forumsbeitr�ge:</td><td>";
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
		echo "<tr><td><br><strong>Modul Foren:</strong></td>\n<td> </td></tr><tr><td>";
		guiInternLink ($session, 'forumhome', 'Foren�bersicht', 'forum');
		echo "</td><td>Auflistung der existierenden Foren</td></tr>\n";
		echo '<tr><td>';
		guiInternLink ($session, 'forumsearch', 'Forensuche', 'forum');
		echo "</td><td>Suche in den Foren</td></tr>\n";
	}
} // class module_forum
class ModuleAddress {

	function userTableData (&$session, $id){
		$session->trace (TC_Gui3, 'address.UserTableData');
	}
	function userOwnData (&$session, $id) {
		$session->trace (TC_Gui3, 'address.UserOwnData');
	}
	function userCheckData (&$session){
		$session->trace (TC_Gui3, 'address.UserCheckData');
		return null;
	}
	function userStoreData (&$session, $isnew, $id){
		$session->trace (TC_Gui3, 'address.UserStoreData');
	}
	function userGetData (&$session){
		$session->trace (TC_Gui3, 'address.UserGetData');
	}
	function overview(&$session){
		$session->trace (TC_Gui3, 'address.overview');
		echo "<tr><td><br><strong>Modul Adressen:</strong></td>\n<td> </td></tr><tr><td>";
		guiInternLink ($session, 'editbook', 'Adressb�cher', 'address');
		echo "</td><td>Adressbuch erstellen oder �ndern</td></tr>\n";
		echo '<tr><td>';
		guiInternLink ($session, 'editcard', 'Adresskarte', 'address');
		echo "</td><td>Adresse erstellen/�ndern</td></tr>\n";
	}
} // class module_forum
// EndOfClasses

function InitModules(&$session){
	if ($session->fModules == null){
		$session->fModules = array ();
		# ModuleLoop:
		# $session->fModules ["[Module]"] = new Module[Module] ();
		$session->fModules ["forum"] = new ModuleForum ();
		$session->fModules ["address"] = new ModuleAddress ();
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