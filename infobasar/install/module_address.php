<?php
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
		outTableRecord();
		outNewLine();
		outTableCellStrong(tagNewline () . 'Modul Adressen');
		outTableRecordEnd();
		outTableRecordInternLink ($session, 'Adressbuch erstellen oder ändern.',
			'editbook', 'Adressbücher', 'address');
		outTableRecordInternLink ($session, 'Adresse erstellen oder ändern.',
			'editcard', 'Adresskarte', 'address');
		outTableRecordInternLink ($session, 'Adressen suchen, exportieren.',
			'showcards', 'Suchen', 'address');
	}
} // class ModuleAddress

?>