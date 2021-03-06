<?php
// db_mysql.php: DataBase functions implemented for MySQL
// $Id: db_mysql.php,v 1.21 2005/01/17 02:27:26 hamatoma Exp $
/*
Diese Datei ist Teil von InfoBasar.
Copyright 2004 hamatoma@gmx.de M�nchen
InfoBasar ist freie Software. Du kannst es weitergeben oder ver�ndern
unter den Bedingungen der GNU General Public Licence.
N�heres siehe Datei LICENCE.
InfoBasar sollte n�tzlich sein, es gibt aber absolut keine Garantie
der Funktionalit�t.
*/

function dbOpen (&$session) {
	$session->trace (TC_Db1, 'dbOpen');
	if (!($dbc = mysql_pconnect ($session->fDbServer,
			$session->fDbUser,
			$session->fDbPassw))) {
         $msg = 'Kann keine Verbindung zu Datenbank '
				. $session->fDbServer . ' (' . $session->fDbUser
				. ') aufbauen, ich gebe auf.';
	$session->fDbPassw = '\n\nDas stimmt sicher nicht!';
	$msg .= TAG_NEWLINE;
	$msg .= sprintf ("MySql error: %s", mysql_error ());
	panicExit ($session, $msg);
	}
	if (!mysql_select_db ($session->fDbName, $dbc)) {

		$msg =  sprintf ("Kann Datenbank nicht �ffnen %s, ich gebe auf.",
			 $session->fDbName);
	 $msg .= TAG_NEWLINE . 'DB=' . $session->fDbName . TAG_NEWLINE;
	 $msg .= sprintf ("MySql error: %s", mysql_error ());
	 panicExit ($session, $msg);
	} else {
		$session->setDbConnectionInfo ($dbc, $dbc);
		$session->trace (TC_Db1, 'dbOpen: DB ' . $session->fDbName . ' ge�ffnet');
	}
} // dbOpen

function dbClose (&$session) {
	$session->trace (TC_Db1, 'dbOpen');
	// NOP function
	// mySql connections are established as persistant
	// they cannot be closed through mysql_close ()
}

function dbSingleValue (&$session, $query) {
	$session->trace (TC_Db3 + TC_Query, "dbSingleValue: $query");
	$value = "";
	$result = mysql_query ($query, $session->fDbInfo);
	if (! $result)
		protoc (mysql_error ());
	else {
		$row = mysql_fetch_row ($result);
		if ($row) {
			$value = $row [0];
			mysql_free_result ($result);
		} // $row
	}
	$session->trace ( TC_Query, "dbSingleValue Wert: $value");
	return $value;
} // dbSingleValue
function dbSingleRecord (&$session, $query) {
	$session->trace (TC_Db3 + TC_Query, "dbSingleRecord: $query");
	$row = null;
	$result = mysql_query ($query, $session->fDbInfo);
	if (! $result)
		protoc (mysql_error ());
	else {
		$row = mysql_fetch_row ($result);
		if ($row) {
			mysql_free_result ($result);
		} // $row
	}
	return $row;
} // dbSingleRecord
function dbGetRecordById (&$session, $table, $id, $what){
	$session->trace (TC_Db2 + TC_Query, "dbGetRecordById: $table, $id, $what");
	return dbSingleRecord ($session,
		'select ' . $what . ' from ' . dbTable ($session, $table)
		. ' where id=' . (0+$id));
}
function dbGetRecordByClause (&$session, $table, $what, $where){
	$session->trace (TC_Db2 + TC_Query, "dbGetRecordByClause: $table, $what, $where");
	return dbSingleRecord ($session,
		'select ' . $what . ' from ' . dbTable ($session, $table)
			. ' where ' . $where);
}
function dbGetValueByClause (&$session, $table, $what, $where){
	$session->trace (TC_Db2 + TC_Query, "dbGetValueByClause: $table, $what, $where");
	return dbSingleValue ($session,
		'select ' . $what . ' from ' . dbTable ($session, $table)
			. ' where ' . $where);
}
function dbFirstRecord (&$session, $query) {
	$session->trace (TC_Db2 + TC_Query, "dbFirstRecord: $query");
	$row = null;
	$result = mysql_query ($query, $session->fDbInfo);
	if (! $result)
		protoc (mysql_error ());
	else {
		$session->setDbResult ($result);
		$row = mysql_fetch_row ($result);
		$session->trace (TC_Query, "Erster Wert: $row[0]");
	}
	return $row;
}
function dbGetTableInfo (&$session, $table,
		&$names, &$types, &$is_string, &$max_lengths, &$ix_primary) {
	$ok = true;
	$session->trace (TC_Db2 + TC_Query, "dbGetTableInfo: $table");
	$result = mysql_query ('select * from ' . $table . ' where 1 limit 1',
		$session->fDbInfo);
	if (! $result) {
		$session->trace (TC_Error, 'dbGetTableInfo: ' . mysql_error ());
		protoc (mysql_error ());
		$ok = false;
	} else {
		$names = array ();
		$types = array ();
		$max_lengths = array ();
		$is_string = array ();
		$ix_primary = -1;
		for ($ix = 0; $ix < mysql_num_fields($result); $ix++) {
			$meta = mysql_fetch_field($result);
			if (!$meta) {
	 			$session->trace (TC_Error, 'dbGetTableInfo: keine Metainfo');
				array_push ($names, 'Field' . (0+$ix));
				array_push ($is_string, null);
				array_push ($types, null);
				array_push ($max_lengths, null);
				$ok = false;
    		} else {
	 			if ($meta->primary_key)
					$ix_primary = $ix;
				array_push ($names, $meta->name);
				array_push ($is_string, ! $meta->numeric);
				array_push ($types, $meta->type);
				array_push ($max_lengths, $meta->max_length);
	 		}
	 	}
	}
	return $ok;
}
function dbNextRecord (&$session){
	$row = mysql_fetch_row ($session->fDbResult);
	$session->trace (TC_Db2 + TC_Query, 'dbNext:' . ($row ? $row[0] : ''));
	return $row;
}
function dbFreeRecord (&$session) {
	$session->trace (TC_Db3 + TC_Query, 'dbFreeRecord:');
	mysql_free_result ($session->fDbResult);
	$session->setDbResult (null);
}
function dbInsert (&$session, $table, $idlist, $values){
	$query = 'insert into ' . dbTable ($session, $table) 
		. "($idlist) values ($values);";
	$session->trace (TC_Db1 + TC_Insert, "dbInsert: $query");
	#if (preg_match ('/TraceInsert/', $values)){
	#	$session->backtrace ("dbInsert");
	#}
	$rc = null;
	if (!mysql_query ($query, $session->fDbInfo))
		error ('dbInsert: ' . mysql_error () . ": $table ($idlist) ($values)");
	else
		$rc = mysql_insert_id ();
	return $rc;
}
function dbDeleteByClause (&$session, $table, $what) {
	$query = 'delete from ' . dbTable ($session, $table) . ' where ' . $what;
	$session->trace (TC_Db1 + TC_Update, "dbDeleteByClause: $query");
	if (!mysql_query ($query, $session->fDbInfo))
		error ('dbDelete: ' . mysql_error () . " $query");
}
function dbUpdate (&$session, $table, $id, $what, $return_count = false) {
	$session->trace (TC_Db1 + TC_Update, "dbUpdate: $table, $id, $what");
	$rc = -1;
	if ($return_count){
		$query = 'select count(id) from ' . dbTable ($session, $table)
			. ' where id=' . $id;
		# $session->trace (TC_X, "sqlUpdate: $query");
		$rc = dbSingleValue ($session, $query);
	}
	$query = 'update ' . dbTable ($session, $table) . ' set ' . $what
		. 'changedat=now()' . ' where id=' . $id;
	if (!mysql_query ($query, $session->fDbInfo))
		error ('dbUpdate: ' . mysql_error () . " $query");
	return $rc;
}
function dbUpdateRaw (&$session, $table, $id, $what, $return_count = false) {
	$session->trace (TC_Db1 + TC_Update, "dbUpdateRaw: $table, $id, $what");
	$rc = -1;
	if ($return_count){
		$query = 'select count(id) from ' . dbTable ($session, $table)
			. ' where id=' . $id;
		# $session->trace (TC_X, "sqlUpdateRaw: $query");
		$rc = dbSingleValue ($session, $query);
	}
	$query = 'update ' . dbTable ($session, $table) . ' set ' . $what
		. ' where id=' . $id;
	if (!mysql_query ($query, $session->fDbInfo))
		error ('dbUpdateRaw: ' . mysql_error () . " $query");
	return $rc;
}
function dbTable (&$session, $name) {
	$session->trace (TC_Db3, 'dbTable');
	return $session->fDbTablePrefix . $name;
}
function dbSqlString (&$session, $value) {
	$session->trace (TC_Db3, 'dbSqlString: ' . ".$value." );
	$value = addcslashes ($value, "\'\\\n\r");
	return '\'' . $value . '\'';
}
function dbSqlDateTime (&$session, $time) {
	$session->trace (TC_Db3 + TC_Convert, 'dbSqlDateTime');
	return strftime ('\'%Y-%m-%d %H:%M:%S\'', $time);
}
function dbSqlDateToText (&$session, $date) {
	$session->trace (TC_Db3 + TC_Convert, 'dbSqlDateToText');
	return str_replace ('-', '.', $date);
}
function dbSqlBool (&$session, $value) {
	$session->trace (TC_Db3 + TC_Convert, 'dbSqlBool');
	return $value ? '\'j\'' : '\'n\'';
}
function dbStringToBool (&$session, $value) {
	$session->trace (TC_Db3 + TC_Convert, 'dbStringToBool');
	$nVal = ord ($value);
	return $nVal == ord ('j') || $nVal == ord ('J');
}

function dbCheckUser (&$session, $user, $code) {
	$session->trace (TC_Db1, 'dbCheckUser');
	$uid = dbUserId ($session, $user);
	if (! $uid)
		$rc = 1;
	else {
		$fields = dbSingleRecord ($session,
			'select id,code,locked,theme,width,height,maxhits,postingsperpage,'
			. 'threadsperpage,startpage from '
			. dbTable ($session, "user") . ' where name="' . $user . '";');
		if ($fields == null)
			$rc = 1;
		elseif ($fields [1] == '')
			$rc = 0;
		else {
			$code = encryptPassword ($session, $user, $code);
			$session->trace (TC_Db1, 'dbCheckUser akt/db: ' . $code . " / " . $fields [1]);
			$rc = strcmp ($code, $fields [1]) == 0 ? 0 : 2;
		}
	} // $count != 0
	switch ($rc) {
	case 1: 
		$rc = "Nicht definiert: $user"; 
		break;
	case 2: 
		$session->trace (TC_Db1, 'dbCheckUser-4:' . $code . " / " . $fields [1]);
		$rc = "Passwort nicht korrekt!";
		break;
	case 3: 
		$rc = "Benutzer gesperrt!"; 
		break;
	default:
		$rc = '';
		$session->setSessionUser ($fields [0]);
	#function setUserData ($id, $name, $theme, $width, $height,
	#	$maxhits, $postingsperpage, $threadsperpage, $startpage) {
		$session->setUserData ($fields [0], $user, $fields [3], $fields [4], $fields [5], 
			$fields [6], $fields [7], $fields [8],
			$fields [9]);
		break;
	}
	$session->trace (TC_Db1, 'dbCheckUser: rc="' . $rc . '"');
	return $rc;
}
function dbUserAdd (&$session, $user, $code, $locked,
	$theme, $width, $height,$maxhits, $startpage, $email) {
	$session->trace (TC_Db1, 'dbUserAdd');
	$theme = 10; 
	$width = max ($width, 10); 
	$height = max ($height, 1);
	$maxhits = max ($maxhits, 1);
	return dbInsert ($session, T_User,
		'name,createdat,code,locked,theme,width,height,maxhits,'
			. 'startpage,email',
		dbSqlString ($session, $user) . ',now(),' . dbSqlString ($session, $code)
		. ',' . dbSqlBool ($session, $locked)
		. ",$theme,$width,$height,$maxhits,"
		. dbSqlString ($session, $startpage) . ',' . dbSqlString ($session, $email));
}
function dbCheckSession (&$session) {
	$rc = null;
	$session->trace (TC_Db1, 'dbCheckSession');
	if ($session->fSessionUser != null){
		$fields = dbSingleRecord ($session,
			'select name,locked,theme,width,height,maxhits,postingsperpage,'
			. 'threadsperpage,startpage from '
				. dbTable ($session, T_User) . ' where id=' . $session->fSessionUser);
		if ($fields == null)
			$rc = 'Unbekannter Benutzer' 
				. ($session->fSessionUser == null ? '!' : ':' . $session->fSessionUser);
		else {
			if (false && dbStringToBool ($session, $fields[1]))
				$rc = 'Benutzer ' . $session->fSessionUser . ' ist gesperrt';
			else {
			# function setUserData ($id, $name, $theme, $width, $height,
			#	$maxhits, $postingsperpage, $threadsperpage, $startpage) {
				$session->setUserData ($session->fSessionUser, $fields[0],
					$fields[2], $fields[3], $fields[4], $fields[5], $fields[6],
					$fields[7], $fields [8]);
				if (! empty ($_SERVER ['PATH_INFO']))
					$session->setPageName (substr ($_SERVER ['PATH_INFO'], 1));
				$rc = null;
			}
		}
	}
	$session->trace (TC_Db1, 'dbCheckSession: rc=' . ($rc == null ? 'null' : $rc));
	return $rc;
}
function dbUserId (&$session, $name){
	$session->trace (TC_Db2, "dbUserId: $name");
	return dbSingleValue ($session, 'select id from ' . dbTable ($session, T_User)
		. ' where name=' . dbSqlString ($session, $name));
}
function dbPageId (&$session, $name){
	$session->trace (TC_Db2 + TC_Query, "dbPageId: $name");
	return dbSingleValue ($session, 'select id from ' . dbTable ($session, T_Page)
		. ' where name=' . dbSqlString ($session, $name));
}
function dbGetLastText (&$session, $pageid){
	return dbGetValueByClause ($session, T_Text,
			'max(id)', 'page=' . (0+$pageid));
}
function dbGetParam (&$session, $theme, $pos){
	$session->trace (TC_Db2 + TC_Query, "dbGetParam: $theme $pos");
	return dbSingleValue ($session, 'select text from ' . dbTable ($session, T_Param)
		. ' where theme=' . (0 + $theme) . ' and pos=' . (0 + $pos));
}
function dbGetText (&$session, $pos) {
	$session->trace (TC_Db2 + TC_Query, "dbGetText: $pos");
	if ($pos <= C_MaxAllModulesAllDesigns)
	 	$theme = Theme_All;
	else if (($theme = $session->fUserTheme) <= 0)
		$theme = Theme_Standard;

	return dbSingleValue ($session, 'select text from ' . dbTable ($session, T_Param)
		. ' where theme=' . (0 + $theme) . ' and pos=' . (0 + $pos));
}
function dbIdList (&$session, $table, $where)
{
	$session->trace (TC_Db2 + TC_Query, "dbIdList: $table, $where");
	return dbColumnList ($session, $table, 'id', $where);
}
function dbColumnList (&$session, $table, $what, $where)
{
	$session->trace (TC_Db2 + TC_Query, "dbColumnList: $table, $what, $where");
	$rc = array ();
	$query = 'select ' . $what . ' from ' . dbTable ($session, $table)
		. " where $where";
	$result = mysql_query ($query, $session->fDbInfo);
	if (! $result)
		protoc (mysql_error ());
	else {
		while ($row = mysql_fetch_row ($result)) {
			array_push ($rc, $row [0]);
		}
		mysql_free_result ($result);
	}
	return $rc;
}
function dbPrintTable (&$session, $query, $headers, $max_lines){
	$session->trace (TC_Db1, "dbPrintTable");
	$result = mysql_query ($query, $session->fDbInfo);
	if (! $result)
		protoc (mysql_error ());
	else {
		$first = true;
		$no = 0;
		if ($max_lines <= 0)
			$max_lines = 1000;
		while ($row = mysql_fetch_row ($result)) {
			if ($first){
				outTable (1);
				outTableRecord ();
				foreach ($headers as $key => $value)
					outTableCellStrong ($value);
				outTableRecordEnd ();
				$first = false;
			}
			if (++$no > $max_lines)
				break;
			outTableRecord ();
			foreach ($row as $key => $value)
				outTableCell ($value);
			outTableRecordEnd ();
		}
		if ($first)
			echo "Die Anfrage ergab keine Ergebnisse<br>\n";
		else {
			outTableEnd();
			if ($no > $max_lines)
				guiParagraph ($session, "Es gibt noch weitere Ergebnisse!", false);
		}
		mysql_free_result ($result);
	}
}
function dbIdListOfThreadPage (&$session, $thread_id, $page) {
	$session->trace (TC_Db3 + TC_Query, "dbIdListOfThreadPage: $thread_id, $page");
	$rc = $page == 1 ? array ($thread_id) : array ();
	$page_size = $session->fUserPostingsPerPage;
	$query = 'select id from ' . dbTable ($session, T_Posting)
		. " where top=$thread_id order by id";
	$result = mysql_query ($query, $session->fDbInfo);
	if (! $result)
		protoc (mysql_error ());
	else {
		if ( ($offset = $page_size * ($page - 1)) != 0)
			# mysql_data_seek ($result, $offset);
			while ($offset-- > 1)
				 mysql_fetch_row ($result);
		while (count ($rc) < $page_size && ($row = mysql_fetch_row ($result))) {
			array_push ($rc, $row [0]);
		}
		mysql_free_result ($result);
	}
	return $rc;
}
function dbPageOfPosting (&$session, $thread_id, $posting_id) {
	$session->trace (TC_Db2 + TC_Query, "dbPageOfPosting: $thread_id, $posting_id");
	$count = dbSingleValue ($session, 'select count(id) from '
		. dbTable ($session, T_Posting)
		. " where top=$thread_id and id<=$posting_id");
	$rc = 1 + floor ($count / $session->fUserPostingsPerPage);
	return $rc;
}
function dbThreadPageNumber (&$session, $thread_id) {
	$session->trace (TC_Db3 + TC_Query, "dbThreadPageNumber: $thread_id");
	$count = 1 + dbSingleValue ($session, 'select count(id) from '
		. dbTable ($session, T_Posting) . " where top=$thread_id");
	return 1 + floor ($count / $session->fUserPostingsPerPage);
}
function dbGetAuthorInfo (&$session, $author,
		&$link, &$postings, &$avatar, &$ranking) {
	$session->trace (TC_Db3 + TC_Query, "dbGetAuthorInfo: $author");
	list ($postings, $avatar) = dbGetRecordByClause ($session, T_User,
			'postings,avatar', 'name=' . dbSqlString ($session, $author));
	$ranking = 'Stallknecht';
	if (! empty ($avatar))
		$avatar = guiExternLinkString ($session, $avatar, $author);
	$link = null;
}
function dbThreadInfo (&$session, $thread_id, &$answers, &$pages, &$last) {
	$session->trace (TC_Db3 + TC_Query, "dbThreadInfo: $thread_id");
	list ($answers, $last) = dbGetRecordByClause ($session, T_Posting,
		'count(id),max(id)', "top=$thread_id");
	if (empty ($last))
		$last = $thread_id;
	$pages = empty ($answers) ? 1
		: 1 + floor ($answers / $session->fUserPostingsPerPage);
}
function dbForumInfo (&$session, $forum_id, &$threads, &$pages) {
	$session->trace (TC_Db3 + TC_Query, "dbForumInfo: $forum_id");
	$threads = dbSingleValue ($session, 'Select count(id) from '
		. dbTable ($session, T_Posting) . ' where forum=' . $forum_id
		. ' and top is null');
	$pages = 1 + floor (($threads - 1) / $session->fUserThreadsPerPage);
}
function dbIdListOfPage (&$session, $table, $where, $page_size, $page) {
	$session->trace (TC_Db3 + TC_Query, "dbIdListOfPage: $table, $where");
	$rc = array ();
	$query = 'select id from ' . dbTable ($session, $table)
		. " where $where";
	$result = mysql_query ($query, $session->fDbInfo);
	if (! $result)
		protoc (mysql_error ());
	else {
		if ( ($offset = $page_size * ($page - 1)) != 0)
			mysql_data_seek ($result, $offset);
		while (count ($rc) < $page_size && ($row = mysql_fetch_row ($result))) {
			array_push ($rc, $row [0]);
		}
		mysql_free_result ($result);
	}
	return $rc;
}
function dbForumName (&$session, $id, $with_link) {
	$session->trace (TC_Db3 + TC_Query, "dbForumName: $id");
	$rc = dbSingleValue ($session,
		'select name from ' . dbTable ($session, T_Forum) . ' where id=' . $id);
	if ($with_link)
		$rc = guiInternLinkString ($session, P_Forum . '?forum_id=' . $id, $rc);
	return $rc;
}
function dbGetThemes (&$session, &$names, &$numbers){
	$names = array ();
	$numbers = array ();
	if ($row =dbFirstRecord ($session, 'select theme,text from ' . dbTable ($session, T_Param)
		. ' where pos=' . Th_ThemeName . ' and theme>=' . Theme_Standard . ' order by theme'))
		do {
			array_push ($names, $row [1]);
			array_push ($numbers, $row [0]);
		} while ($row = dbNextRecord ($session));
}
function dbGetAndStoreMacroPattern (&$session){
	$session->trace (TC_Db2, 'dbGetAndStoreMacroPattern:');
	$theme = $session->fUserTheme;
	$pattern = dbGetParam ($session, $theme, Th_MacroPattern);
	if (empty ($pattern)){
		$row = dbFirstRecord ($session,
				'select name from ' . dbTable ($session, T_Macro)
				. ' where theme=' . Theme_All . ' or theme=' . ($theme+0) . ' order by name');
		$last_var = null;
		$open_parenthesis = false;			
		while ($row) {
			$pos = strpos ($row [0], ':');
			if ($pos <= 0 ){
				$val = $row [0];
				$var = null;
			} else {
				$var = substr ($row [0], 0, $pos + 1);
				$val = substr ($row [0], $pos + 1);
			}
			$session->trace (TC_Db3, 'dbGetAndStoreMacroPattern-2: ' . $pos . ' ' . $var . ' / ' . $val);
			if ($var != $last_var) {
				$session->trace (TC_Db2, 'dbGetAndStoreMacroPattern-3: ');
				if ($open_parenthesis){
					$session->trace (TC_Db2, 'dbGetAndStoreMacroPattern-4: ');
					$pattern .= ')';
					$open_parenthesis = false;
				}
				$last_var = $var;
				if (! empty ($var)){
					$session->trace (TC_Db2, 'dbGetAndStoreMacroPattern-5: ' . $pattern);
					$val = $var . '(' . $val;
					$open_parenthesis = true;
				}
			}
			$new = '|' . $val;
			if (getPos ($pattern, $new . '|') < 0)
				$pattern .= $new;
			$row = dbNextRecord ($session);
		}
		if ($open_parenthesis)
			$pattern .= ')';
		$id = dbSingleValue ($session, 'select id from ' . dbTable ($session, T_Param)
			. ' where theme=' . (0+$theme) . ' and pos=' . Th_MacroPattern);
		if ($id > 0){
			dbUpdateRaw ($session, T_Param, $id, 'text=' . dbSqlString ($session, $pattern));
			$session->trace (TC_Db1, "dbGetAndStoreMacroPattern: Id: $id Th: $theme P: $pattern");
		}
	}
	return $pattern;
}
?>
