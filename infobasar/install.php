<?php
// install.php: Installation of the infobasar
// $Id: install.php,v 1.10 2004/12/22 17:53:33 hamatoma Exp $
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
set_magic_quotes_runtime(0);
error_reporting(E_ALL);

define ('Install_Version', '0.6.5 (2004.09.21)');
define ('PATH_DELIM', '/');
define ('REXPR_PATH_DELIM', '\/');
define ('C_ScriptName', 'install.php');
define ('NO_SERVER', 1);
define ('NO_DB', 2);
define ('DB_EXISTS', 3);
define ('CHECKBOX_TRUE', 'J');

// Trace-Klassen
define ('TC_Util1', 0x1);
define ('TC_Util2', 0x2);
define ('TC_Util3', 0x4);
define ('TC_Gui1', 0x10);
define ('TC_Gui2', 0x20);
define ('TC_Gui3', 0x40);
define ('TC_Db1', 0x100);
define ('TC_Db2', 0x200);
define ('TC_Db3', 0x400);
define ('TC_Update', 0x1000);
define ('TC_Insert', 0x2000);
define ('TC_Query', 0x4000);
define ('TC_Convert', 0x8000);
define ('TC_Init', 0x10000);
define ('TC_X', 0x20000);
define ('TC_Formating', 0x40000);
define ('TC_Session1', 0x1000000);
define ('TC_Session2', 0x2000000);
define ('TC_Session3', 0x4000000);

define ('TC_Error', 0x8);
define ('TC_Warning', 0x80);
define ('TC_Diff1', 0x800);
define ('TC_Diff2', 0x80000);
define ('TC_Diff3', 0x800000);

define ('TC_All', 0x7fffffff);
define ('PREFIX_Warning', 'InfoBasar: Warnung: ');
define ('PREFIX_Error', 'InfoBasar: Fehler: ');

// DB-Typen:
define ('DB_MySQL', 'MySQL');

// Tabellen:
define ('T_Page', 'page');
define ('T_Text', 'text');

// -----------------------------------
class Session {
	var $fDbType; // MySQL
	var $fDbServer;
	var $fDbUser;
	var $fDbPassw;
	var $fDbName;
	var $fDbInfo; // MySQL: Connection-Handle
	var $fDbConnection; // MySQL: = $fDbInfo
	var $fDbTablePrefix; // Vorspann bei Tabellennamen
	var $fDbResult; // MySQL: Handle für Abfragen über mehrere Datensätze

	var $fOutputState; // Init Header Body
	var $fFormExists; // true: Es gab schon ein <form> im Text.

	var $fScriptURL; // Ohne / am Ende
	var $fScriptBase; // ohne *.php
	var $fScriptFile; // Relativ zu DocumentRoot
	var $fFileSystemBase; // Absolutpfad im Filesystem des Servers

	var $fTraceFlags;

	function Session ($start_time){
		$this->fDbServer = 'localhost';
		$this->fOutputState = 'Init';
		$this->fTraceFlags = 0;
		$this->fStartTime = getMicroTime ($this, $start_time);
	}
	function trace($class, $msg){
		if (($class & $this->fTraceFlags) != 0){
			if ($this->fOutputState == 'Init') {
				echo "<head></head>\n<body>\n";
				$this->fOutputState = 'Body';
			}
			#echo sprintf (" Trace: %x / %x: ", $class, $this->fTraceFlags, ($class & $this->fTraceFlags));
			echo htmlentities ($msg) . "<br>\n";
		}
		#
	}
	function backTrace ($message) {
		$list = debug_backtrace ();
		foreach ($list as $no => $entry)
			if ($no > 0)
				$this->trace (TC_All, $entry ['file'] . "-" . $entry ['line'] . ': '
					. $entry ['function'] . ($message ? $message : ""));
	}
	function setDb($dbtype, $server, $db_name, $user, $passw, $prefix) {
		$this->fDbType = $dbtype;
		$this->fDbServer = $server;
		$this->fDbUser = $user;
		$this->fDbPassw = $passw;
		$this->fDbName = $db_name;
		$this->fDbTablePrefix = $prefix;
	}
	function setDbConnectionInfo ($connection, $info) {
		$this->fDbInfo = $info; $this->fDbConnection = $connection;
	}
	function setScriptBase () {
		global $HTTP_HOST, $SCRIPT_FILENAME, $PHP_SELF;

		// Basisverzeichnis relativ zu html_root
		$script_url = "http://" . $HTTP_HOST . $PHP_SELF;
		$script_file = $SCRIPT_FILENAME;
		$this->trace (TC_Init, "setScriptBase: $script_url | $script_file");
		$script_url = preg_replace ('/\.php.*$/', '.php', $script_url);
		$this->fScriptURL = $script_url; 
		$this->fScriptFile = $script_file;
		$this->fScriptBase = preg_replace ('/'  . REXPR_PATH_DELIM . '\w+\.php.*$/', '', $script_url);
		$this->fFileSystemBase =  preg_replace ('/' . REXPR_PATH_DELIM . '\w+\.php.*$/', '', $script_file);
		return true;
	}
	function setDbResult ($result) { $this->fDbResult = $result; }
	function setPageData ($name, $date, $by) {
		$this->fPageName = $name;
		$this->fPageChangedAt = dbSqlDateToText ($this, $date);
		$this->fPageChangedBy = $by;
	}
};
$session = new Session ($start_time);

$session->fTraceFlags
	= 0 * TC_Util1 + 1 * TC_Util2 + 0 * TC_Util1
	+ 0 * TC_Gui1 + 0 * TC_Gui2 + 0 * TC_Gui3
	+ 0 * TC_Db1 + 0 * TC_Db2 + 0 * TC_Db3
	+ 0 * TC_Session1 + 0 * TC_Session2 + 0 * TC_Session3 
	+ 0 * TC_Update + 0 * TC_Insert + 1 * TC_Query
	+ 0 * TC_Convert + 1 * TC_Init + 0 * TC_Diff2
	+ TC_Error + TC_Warning + TC_X;
$session->fTraceFlags = TC_Error + TC_Warning + TC_X;
#$session->fTraceFlags = TC_All;

if (empty ($HTTP_HOST))
	fatalError ("Kein HTTP_HOST definiert<br>Vermutlich register_globals=Off.");
elseif (! $session->setScriptBase())
	fatalError ("kann BasisVerzeichnis nicht finden");
elseif (substr ($session->fScriptBase, strlen ($session->fScriptBase) - 8) != "/install")
	fatalError ('install.php <strong>muss</strong> in einem Verzeichnis install liegen!'
		. "<br>\nScriptBase: " . $session->fScriptBase . "<br>\nVerzeichnis: "
		. substr ($session->fScriptBase, strlen ($session->fScriptBase) - 8));
else {
	if (! isset ($inst_step))
		$inst_step = 0;
	if (isset ($inst_next))
		$inst_step++;
	elseif (isset ($inst_last))
		$inst_step--;
	if ($inst_step > 1)
		include ('..' . PATH_DELIM . 'config.php');
	switch ($inst_step){
	case 2: 
		if (isset ($inst_populate))
			instDBAnswer ($session);
		else
			instDB ($session); 
		break;
	case 3: 
		instFinish ($session); 
		break;
	case 4: 
		instExit ($session);
		break;
	case 1:
		if (isset ($config_save) || isset ($config_access) || isset ($config_createdb))
			instConfigFileAnswer ($session);
		else
			instConfigFile ($session, null);
		break;
	default:
	case 0:
		instArchiveAnswer ($session);
		break;
	}
}
exit (0);

// -----------------------------------
function fatalError ($message){
	echo '<html><head>Ernster Fehler</head><body>';
	echo '<h1>Ernster Fehler:</h1>';
	echo $message;
	echo '</body></html>';
}
function protoc ($message) {
	echo $message; echo "<br />\n";
}
function error ($message) {
	protoc ('<h1>+++ ' . $message . '</h1>');
}
function getMicroTime(&$session, $time = null){ 
	$session->trace (TC_Util1, 'getMicroTime');
	if (empty ($time))
		$time = microtime ();
	list($usec, $sec) = explode(" ", $time); 
	return ((float) $usec + (float)$sec); 
}
function getPos ($haystock, $needle){
	$rc = strpos ($haystock, $needle);
	return is_int ($rc) ? $rc : -1;
}
function instArchive (&$session, $message =  null) {
	global $archive_dir;
	$session->trace (TC_Init, 'instArchive');
	guiHeader ($session, 'Schritt 0');
	guiHeadline ($session, 1, 'Datei- und Archivverwaltung');
		
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	
	instGetConfig ($session);

	guiHeadline ($session, 2, 'Archiv hochladen');
	
	if (empty ($archive_name))
		$archive_name = $session->fFileSystemBase . PATH_DELIM . "install" . PATH_DELIM . 'infobasar.hma';
	$archive_exists = file_exists ($archive_name);

	echo '<form enctype="multipart/form-data" action="' . C_ScriptName
		. '" method="post">' . "\n";
	guiHiddenField ('inst_step', 0);
	guiHiddenField ('MAX_FILE_SIZE', 1000000);
	guiUploadFile ('archive_uploadfile');
	echo ' ';
	guiButton ('archive_upload', 'Hochladen');
	guiFinishForm ($session);
		
	guiStartForm ($session, 'Form');
	guiHiddenField ('inst_step', 0);
	guiHiddenField ('archive_name', $archive_name);
	
	$path = $session->fFileSystemBase . PATH_DELIM;
	if ($archive_dir == CHECKBOX_TRUE){
		$dir = opendir ($path);
		guiHeadline ($session, 3, "Verzeichnis auf dem Server");
		echo '<table border="1" width="100%"><tr><td><b>Name:</b></td>';
		echo '<td><b>Gr&ouml;&szlig;e</b></td><td><b>Ge&auml;ndert am</b></td></tr>' . "\n";
		while ($file = readdir ($dir)){
			if ($file != '.' && $file != '..'){
				$name = $path . $file;
				echo '<tr><td>';
				echo htmlentities ($file);
				echo '</td><td>';
				echo is_dir ($name) ? 'Verzeichnis' : filesize ($name);
				echo '</td><td>';
				echo date ("Y.m.d H:i:s", filemtime ($name));
				echo '</td></tr>' . "\n";
			}
		}
		echo '</table>' . "\n";
		closedir ($dir);
	}
	$dir = opendir ($path);
	guiHeadline ($session, 3, "Archive");
	echo '<table border="1" width="100%"><tr><td><b>Name:</b></td>';
	echo '<td><b>Gr&ouml;&szlig;e</b></td><td><b>Ge&auml;ndert am</b></td>';
	echo '<td><b>Aktion</b></td></tr>' . "\n";
	$no = 0;
	while ($file = readdir ($dir)){
		if (preg_match ('/[\.]hma([.]gz)?$/', $file)){
			$no++;
			$name = $path . $file;
			echo '<tr><td>';
			echo htmlentities ($file);
			echo '</td><td>';
			echo filesize ($name);
			echo '</td><td>';
			echo date ("Y.m.d H:i:s", filemtime ($name));
			echo '</td><td>';
			guiHiddenField ('archive_file' . $no, $file);
			guiButton ('archive_extract' . $no, 'Entpacken');
			echo '</td><tr>' . "\n";
		}
	}
	echo '</table>' . "\n";
	guiHeadline ($session, 2, 'Optionen');
	guiCheckBox ('archive_dir', 'Alle Dateien anzeigen', $archive_dir);
	echo ' ';
	guiButton ('archive_show', 'Aktualisieren');
	outNewline();
	echo 'Kommentare aus PHP-Quellen entfernen: ';
	guiButton ('archive_strip', 'Optimieren');
	guiLine ($session, 2);
	guiButton ('inst_next', 'weiter');
	guiFinishForm ($session);
	guiFinishBody ($session);
}
function instArchiveAnswer (&$session){
	global $archive_dir, $archive_name, $HTTP_POST_VARS, 
		$archive_uploadfile, $archive_upload, $archive_strip;
	$session->trace (TC_Init, "instArchiveAnswer");
	$message = null;
	if (isset ($archive_upload)){
		$name =  $_FILES['archive_uploadfile']['name'];
		if (move_uploaded_file($_FILES['archive_uploadfile']['tmp_name'],
			$session->fFileSystemBase . PATH_DELIM . $name)) {
			$message = 'Datei erfolgreich hochgeladen: ' . $name;
		} else {
			$message = 'Problem beim Hochladen von ' . $name . ': ' 
				. $_FILES['archive_uploadfile']['error'];
		}
	} elseif (isset ($archive_strip)){
		$dir_name = preg_replace ('|/[^/]+$|', '', $session->fFileSystemBase);
		$dir = opendir ($dir_name);
		while ($file = readdir ($dir)) {
			if (strpos ($file, '.php') == strlen ($file) - 4){
				$file = $dir_name . '/' . $file;
				$message .= '<br/>' . stripPhpSource ($session, $file, $file);
			}
		}
		closedir($dir); 
	} else {
		for ($no = 1; $no < 100; $no++){
			$ref = 'archive_extract' . $no;
			global $$ref;
			if (isset ($$ref)){
				$ref = 'archive_file' . $no;
				$archive_name = $HTTP_POST_VARS[$ref];
				if (! ($message = extractFromArchive ($session, $archive_name, false, "*")))
					$message = "Archiv $archive_name wurde entpackt";
				break;
			}
		}
	}
	instArchive ($session, $message);
}
function instGetSqlFile (&$session){
	return $session->fFileSystemBase . PATH_DELIM . '../db/infobasar_start.sql';
}
function instGetStandardPageFile (&$session){
	return $session->fFileSystemBase . PATH_DELIM . '../db/infobasar_pages.wiki';
}
function instConfigFile (&$session, $message =  null) {
	global $db_server, $db_user, $db_passw, $db_name, $db_prefix;
	$session->trace (TC_Init, 'instConfigFile');
	guiHeader ($session, 'Schritt 1');
	
	guiHeadline ($session, 2, 'Konfiguration der Datenbank');
	
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	
	instGetConfig ($session);
	
	$name = $session->fFileSystemBase . PATH_DELIM . '..' . PATH_DELIM . 'config.php';
	$config_exists = file_exists ($name);
	guiParagraph ($session, 'Konfigurationsdatei existiert ' 
		. ($config_exists ? '.' : ' <b>nicht</b>!'), false);
	
	guiStartForm ($session, 'Form');
	guiHiddenField ('inst_step', 1);
	$file = instGetSqlFile ($session);
	$sql_exists = file_exists ($file);

	if (empty ($db_server))
		$db_server = 'localhost';
	if (empty ($db_prefix))
		$db_prefix = 'infobasar_';
	echo '<table><tr><td>MySQL-Server</td><td>';
	guiTextField ('db_server', $db_server, 32, 0);
	echo "</td></tr>\n<tr><td>Datenbank</td><td>";
	guiTextField ('db_name', $db_name, 32, 0);
	echo "</td></tr>\n<tr><td>Benutzer</td><td>";
	guiTextField ('db_user', $db_user, 32, 0);
	echo "</td></tr>\n<tr><td>Passwort</td><td>";
	guiTextField ('db_passw', $db_passw, 32, 0);
	echo "</td></tr>\n<tr><td>Tabellenvorspann</td><td>";
	guiTextField ('db_prefix', $db_prefix, 32, 0);
	echo "</td></tr>\n<tr><td></td><td>";
	guiButton ('config_save', 'Konfiguration speichern');
	echo "</td></tr>\n<tr><td></td><td>";
	guiButton ('config_access', 'Datenbankzugriff testen');
	echo "</td></tr>\n</table><br/>";	
	guiLine ($session, 2);
	$status = checkDB ($session, $message);
	guiParagraph ($session, $message, false);
	if ($status == NO_DB)
		guiButton ('config_createdb', 'Datenbank ' . $db_name . ' erzeugen');
	guiLine ($session, 2);
	guiParagraph ($session, "DB-Definitionsdatei $file " . ($sql_exists ? "" : "<b>nicht</b> ") . "gefunden.", false);;
	guiButton ('inst_last', 'zurück');
	echo ' | ';
	guiButton ('inst_next', 'weiter');
	guiFinishForm ($session);
	guiFinishBody ($session);
}
function instConfigFileAnswer (&$session){
	global $db_server, $db_user, $db_passw, $db_name, $db_prefix;
	global $config_save, $config_access, $config_createdb;
	$session->trace (TC_Init, "instConfigFileAnswer");
	$message = "";
	if (isset ($config_createdb)){
		if (! $session->fDbInfo && checkDB ($session, $message) == NO_DB) {
			$query = "create database $db_name;";
			$result = mysql_query ($query, $session->fDbInfo);
			if ($result)
				$message = "DB $db_name wurde erzeugt.";
			else
				$messsage = "+++ DB $db_name nicht erzeugt: " 
					. htmlentities (mysql_error ());
		} else {
			$message = "Inkonstistenz";
		}
	} elseif (isset ($config_access)) {
		checkDB ($session, $message);
	} elseif (isset ($config_save)) {
		if (empty ($db_server))
			$message = '+++ Kein Server angegeben. Vielleicht "localhost"?';
		elseif (empty ($db_user))
			$message = '+++ Kein Datenbank-Benutzer angegeben. Evt. Administrator fragen.';
		else {
			$name = $session->fFileSystemBase . PATH_DELIM . '..' . PATH_DELIM . 'config.php';
			if (file_exists ($name)){
				$no = 1;
				while (file_exists (
						$new_name = str_replace ('.php', '_' . $no . '.php',
							$name)))
					$no++;
				if (rename ($name, $new_name))
					$message = $name . ' wurde in ' . $new_name 
						. ' umbenannt.';
				else
					$message = "+++ Fehler beim Umbenennen von $name in $new_name!";
				
			}
				
			$file = fopen ($name, "w");
			fwrite ($file, 
				"<?php\n"
				. '# config.php: Created by InfoBasar install.php (version '
				. Install_Version
				. ') at ' . strftime ("%Y.%m.%d %H.%N.%S", time()) . "\n"
				. "\$db_type = 'MySQL';\n"
				. "\$db_server = '$db_server';\n"
				. "\$db_user = '$db_user';\n"
				. "\$db_passw = '$db_passw';\n"
				. "\$db_name = '$db_name';\n"
				. "\$db_prefix = '$db_prefix';\n"
				. "?>\n"
				);
			fclose ($file);
			$message = guiAppendParagraph ($session, $message, 
				"$name wurde gespeichert.");
			
		}
	} // config_save
	instConfigFile ($session, $message);
}
function instDB (&$session, $message =  null) {
	global $db_server, $db_user, $db_passw, $db_name, $db_prefix;
	$session->trace (TC_Init, 'instDB');
	guiHeader ($session, 'Schritt 2');
	guiHeadline ($session, 2, 'Bestückung der Datenbank');
	
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	
	checkDB ($session, $message);
	$message = '';
	
	guiStartForm ($session, 'Form');
	guiHiddenField ('inst_step', 2);
	$table_status = checkTableStatus ($session, $tables_exist);
	guiParagraph ($session, $table_status, false);
	
	guiParagraph ($session, 'Warnung: Wenn die Datenbank initalisiert wird, werden 	<b>alle</b> vorhandenen Daten <b>gelöscht</b>!', false);
	guiButton ('inst_populate', 'Datenbank initialisieren');
	guiLine ($session, 2);
	guiButton ('inst_last', 'zurück');
	echo ' | ';
	guiButton ('inst_next', 'weiter');
	guiFinishForm ($session);
	guiFinishBody ($session);
}
function instDBAnswer (&$session){
	global $inst_populate;
	
	$session->trace (TC_Init, 'instDBAnswer');
	$message = '';
	if (isset ($inst_populate)) {
		$message = populate ($session, instGetSqlFile ($session));
	}
	instDB ($session, $message);
}
function instFinish (&$session, $message = null){
	global $inst_populate;
	$session->trace (TC_Init, 'instFinish');
	guiHeader ($session, 'Schritt 3');
	guiHeadline ($session, 2, 'Installation beenden');
	guiParagraph ($session, empty ($message) 
		? $message : 'Der InfoBasar ist jetzt installiert.', false);
	guiStartForm ($session, 'Form');
	guiHiddenField ('inst_step', 3);
	echo 'Passwort für den Benutzer admin: ';
	guiTextField ('inst_passw', '', 32, 0);
	guiCheckbox ('inst_setpassw', 'Passwort setzen', true);
	echo '<br />';
	guiCheckbox ('inst_delete', 'Installationsdateien entfernen', true);
	guiLine ($session, 2);
	guiButton ('inst_last', 'zurück');
	echo ' | ';
	guiButton ('inst_next', 'weiter');
	guiFinishForm ($session);
	guiFinishBody ($session);
}
function instExit (&$session){
	global $db_prefix, $inst_delete, $inst_passw, $inst_setpassw;
	$session->trace (TC_Init, 'instExit');
	$error = null;
	$message = null;
	if (isset ($inst_setpassw) && $inst_setpassw == CHECKBOX_TRUE) {
		if (empty ($inst_passw))
			$error = '+++ leeres Passwort ist nicht zulässig!';
		else {
			checkDB ($session, $message);
			$passw = strrev (crypt ($inst_passw, 'admin'));
			sqlStatement ($session, 'update ' . $db_prefix . "user set code='"
				 . $passw . "' where name='admin'");
			$message = 'Passwort wurde gesetzt';
		}
	}
	if ($error)
		instFinish ($session, $error);
	else {
		guiHeader ($session, 'Ende');
		guiHeadline ($session, 2, 'Installation beenden');
		
		if ($message)
			guiParagraph ($session, $message, false);
		if (isset ($inst_delete)){
			guiParagraph ($session, $session->fScriptFile . ' wurde '
				. (unlink ($session->fScriptFile) ? ' ' : ' <b>nicht</b>')
				. 'gelöscht', false);
		}
		guiParagraph ($session, 'Die Installation ist jetzt beendet.', false);
		guiLine ($session, 2);
		guiExternLink ($session, '../index.php', 'Zur Anmeldung');
		guiFinishBody ($session);
	}
}
// ------------------------------------------
function checkDB (&$session, &$message) {
	global $db_server, $db_user, $db_passw, $db_name;
	$session->trace (TC_Init, 'checkDB: ' . $db_name);
	$result = NO_SERVER;
	if (!($dbc = mySql_pconnect($db_server, $db_user, $db_passw))) {
		$message = 'Kann mich mit mySQL-Server nicht verbinden.'
			. ' Stimmen Benutzer / Passwort?</p>'
			. '<p>MySql meldet: ' . htmlentities (mySql_error());
	} elseif (!mysql_select_db($db_name, $dbc)) {
		$session->setDbConnectionInfo ($dbc, $dbc);
		$message = "DB $db_name nicht gefunden"
			. '</p><p>MySql meldet: ' . htmlentities (mySql_error());
		$result = NO_DB;
	} else {
		$session->setDbConnectionInfo ($dbc, $dbc);
		$message = 'Zugang zur Datenbank ' . $db_name . ' ist möglich.';
		$result = DB_EXISTS;
	}
	$session->trace (TC_Init, 'checkDB: ' . $result);
	return $result;
}
function checkTableStatus (&$session, &$exists) {
	global $db_name, $db_prefix;
	$status = '';
	$exists = false;
	if ($session->fDbConnection) {
		$result = mysql_list_tables($db_name);

		if (!$result) {
			$status = "keine Tabellen vorhanden";
			exit;
		} else {
			$count1 = $count2 = 0;
			while ($row = mysql_fetch_row($result)) {
				$count1++;
				if (getPos ($row[0], $db_prefix) >= 0)
					$count2++;
			}
			$status = ($count1 + 0) . ' Tabelle(n), davon ' . ($count2 + 0)
				. " mit Vorspann $db_prefix in der DB";
			$exists = $count2 > 0;
			mysql_free_result($result);
		}
	}
	return $status;
}

function populate (&$session, $fn_sql) {
	global $db_prefix;
	$session->trace (TC_Init, "populate:");
	$message = '';
	if (checkDB ($session, $message) == DB_EXISTS) {
		if (! ($file = fopen ($fn_sql, "r"))) {
			$message = "+++ Kann Datei nicht &ouml;ffnen: $fn_sql";
		} else {
			$status = null;
			$line_count = $comments = 0;
			while (! feof ($file)) {
				$line_count++;
				$line = fgets ($file, 0x7fff);
				if (strlen ($line) <= 3 
					|| preg_match ('!^\s*(#|/[*/])!', $line))
					$comments++;
				else if (strpos ($line, 'SE InfoBasar;') == 1)
					$session->trace (TC_Init, "Use db gefunden");
				else { 
					$len = strlen ($line);
					$with_colon = ($pos = strpos ($line, ';', $len - 2)) > 0;
					if ($with_colon){
						$line = substr ($line, 0, $pos);
						$session->trace (TC_Gui3, "Strichpunkt entfernt: $line");
					}
					if ($status == 'create') {
						$sql .= ' ' . $line;
						if ($with_colon) {
							sqlStatement ($session, $sql);
							$status = null;
						}
					} elseif (getPos ($line, 'CREATE TABLE') == 0) {
							$sql = str_replace ('infobasar_', $db_prefix, $line);
							$status = 'create';
					} else {
						if (strpos ($line, 'ROP TABLE') == 1)
							$line = str_replace ('infobasar_', $db_prefix, $line);
						elseif (strpos ($line, 'NSERT INTO') == 1)
							$line = str_replace (' INTO infobasar_', ' INTO ' . $db_prefix,
								$line);
						elseif (strpos ($line, 'PDATE ') == 1)
							$line = str_replace ('UPDATE infobasar_', 'UPDATE ' . $db_prefix,
								$line);
						sqlStatement ($session, $line);
					}
				}
			}
			fclose ($file);
			$message = 'Die Infobasar-Tabellen wurden initialisiert: '
				 . (0+$line_count) . ' Zeilen gelesen, davon '
				. (0+$comments) . ' Kommentare';
			$path = $session->fScriptBase;
			$path = preg_replace ('/^(\w+:)?\/\/.+?\//', '/', $path);
			$path = preg_replace ('/\/install$/', "", $path);
			sqlUpdate ($session, 'macro', " value='" . $path . "/index.php/'", "name = 'BaseModule'");
			sqlUpdate ($session, 'macro', " value='" . $path . "/forum.php/'", "name = 'ForumModule'");
			$message .= '<br>BaseModule wurde auf ' . $path . '/index.php gesetzt.';
			sqlUpdate ($session, 'macro', " value='" . $path . "/'", "name = 'ScriptBase'");
			$message .= '<br>ScriptBase wurde auf ' . $path . ' gesetzt.';
			
			sqlUpdate ($session, 'param', " text='" . $path . "/css/phpwiki.css'", "theme=11 and pos=102");
			$message .= '<br>CSS wurde auf ' . $path . '/css/phpwiki.css gesetzt.';
			$fn_pages = instGetStandardPageFile ($session);
			$message .= '<br>' . instImportPages ($session, $fn_pages, true);
		}
	}
	return $message;
}
function sqlUpdate (&$session, $table, $what, $where){
	global $db_prefix;
	$session->trace (TC_Db1, 'sqlUpdate: ' .$table .', ' . $what . ',' .  $where);
	$query = 'update ' . $db_prefix . $table . " set " . $what . " where " . $where;
	sqlStatement ($session, $query);
}
function dbInit (&$session){
	if ($session->fDbType != DB_MySQL)
		instGetConfig ($session);
}
function sqlStatement (&$session, $query) {
	$session->trace (TC_Db1, 'sqlStatement: ' . $query);
	dbInit ($session);
	if (!mysql_query($query, $session->fDbInfo))
		echo '<p>+++ SQL-Fehler: ' . htmlentities (mySql_error ()) . '<br/>' 
			. htmlentities ($query) . '</p>';
}
function dbTable (&$session, $name) {
	$session->trace (TC_Db3, 'dbTable');
	dbInit ($session);
	return $session->fDbTablePrefix . $name;
}
function dbSqlString (&$session, $value) {
	$session->trace (TC_Db3 + TC_Convert, 'dbSqlString: ' . ".$value." );
	$value = addcslashes ($value, "\'\\\n\r");
	return '\'' . $value . '\'';
}
function dbDeleteByClause (&$session, $table, $what) {
	dbInit ($session);
	$query = 'delete from ' . dbTable ($session, $table) . ' where ' . $what;
	$session->trace (TC_Db1 + TC_Update, "dbDeleteByClause: $query");
	if (!mysql_query ($query, $session->fDbInfo))
		error ('dbDelete: ' . mysql_error () . " $query");
}
function dbSingleValue (&$session, $query) {
	$session->trace (TC_Db3 + TC_Query, "dbSingleValue: $query");
	dbInit ($session);
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
function dbUpdate (&$session, $table, $id, $what) {
	$session->trace (TC_Db1 + TC_Update, "dbUpdate: $table, $id, $what");
	$query = 'update ' . dbTable ($session, $table) . ' set ' . $what
		. 'changedat=now()' . ' where id=' . $id;
	if (!mysql_query ($query, $session->fDbInfo))
		error ('dbUpdate: ' . mysql_error () . " $query");
}
function dbPageId (&$session, $name){
	$session->trace (TC_Db2 + TC_Query, "dbPageId: $name");
	return dbSingleValue ($session, 'select id from ' 
		. dbTable ($session, T_Page)
		. ' where name=' . dbSqlString ($session, $name));
}

// -----------------------
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
function guiRadioButton ($name, $text, $checked){
	guiField ($name, "radio", $text, 0, 0,
		isset ($checked) && $checked ? "checked" : "");
}
function guiCheckBox ($name, $text, $checked){
	guiField ($name, "checkbox", CHECKBOX_TRUE, 0, 0,
		isset ($checked) && $checked ? "checked" : "");
	echo htmlentities ($text) . " ";
}
function guiComboBox ($name, $options, $values, $ix_selected = 0) {
	echo '<select name="' . $name . '" size="1' // . count ($options)
		. "\">\n";
	foreach ($options as $ix => $text)
		echo '<option' . ($ix == $ix_selected ? ' selected' : '')
			. ($values ? ' value="' . $values[$ix] . '"' : '')
			. '>' . htmlentities ($text) . "\n";
	echo "</select>\n";
}
function guiUploadFile ($name){
echo '<input name="' . $name . '" type="file">' . "\n";
}
function outNewline(){
	echo '<br/>';
}
function guiLine (&$session, $width) {
	if (! isset ($width))
		$width = 2;
	echo '<hr style="width: 100%; height: ' . (0+$width) . "px;\">\n";
}
function guiHeader (&$session, $title) {
	$session->trace (TC_Gui1, 'guiHeader');
	if (empty ($title))
		$title = "Installation Infobasar";
	echo '<head>' . "\r\n";
		echo '<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">' . "\n";
	echo "<title>$title</title>\n</head>\n<body>\n";
	guiHeadline ($session, 1, 'Installation des InfoBasar');
}
function guiHeadline (&$session, $level, $text) {
	$session->trace (TC_Gui2, 'guiHeadline');
	echo "<h$level>" . htmlentities ($text) . "</h$level>\n";
}
function guiFinishBody (&$session){
	$session->trace (TC_Gui2, 'guiFinishBody');
		guiLine ($session, 1);
		// ($session, ')
		echo "\n</body>\n</html>\n";
}
function guiStartForm (&$session) {
	$session->trace (TC_Gui2, 'guiStartForm');
	echo '<form name="form" action="' . C_ScriptName . '" method="post">' . "\n";
}
function guiFinishForm (&$session){
	$session->trace (TC_Gui2, 'guiFinishForm');
	echo "</form>\n";
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
function guiAppendParagraph (&$session, $text, $appendix){
	$session->trace (TC_Gui2, 'guiAppendParagraph');
	if (empty ($text))
		$text = $appendix;
	else
		$text .= "</p>\n" . $appendix;
	return $text;
}
function guiExternLink (&$session, $link, $text) {
	$session->trace (TC_Gui2, 'guiExternLink');
	echo "<a href=\"$link\">";
	echo htmlspecialchars ($text);
	echo "</a>\n";
}
//----------------------------------------
function instGetConfig (&$session){
	// Zugriff indirekt!
	global $db_server, $db_user, $db_passw, $db_name, $db_prefix;
	$session->trace (TC_Init, 'instGetConfig: ');
	
	$name = $session->fFileSystemBase . PATH_DELIM . ".." . PATH_DELIM . "config.php";
	$session->trace (TC_Init, 'instGetConfig: ' . $name);
	if (is_file ($name)){
		$file = fopen ($name, "r");
		while ($line = fgets ($file, 128)) {
			if (preg_match (
				'/^\$(db_(server|user|passw|name|prefix))\s*=\s*\'([^\']+)\'/',
				$line, $match)) {
				$$match[1] = $match[3];
				$session->trace (TC_Init, 'instGetConfig: ' . $match[1] . '=' . $$match[1]);
			}
		}
		fclose ($file);
		$session->setDb(DB_MySQL, $db_server, $db_name, $db_user, $db_passw, $db_prefix);
	}
}
function getArchiveHexValue (&$file, $width){
	$hexvalue = fread ($file, $width);
	if (! preg_match ('/[0-9a-fA-F]{' . $width . '}/', $hexvalue))
		$rc = 0;
	else
		list ($rc) = sscanf ($hexvalue, "%x");
	return $rc;
}
function getArchiveString (&$file, $width){
	if ($width = getArchiveHexValue ($file, $width))
		$rc = fread ($file, $width);
	else
		$rc = null;
	return $rc;
}
function extractFromArchive (&$session, $archive, $compressed, $what){
	$session->trace (TC_Util1, "extractFromArchive: $archive, $what");
	$file = fopen ($compressed ? "compress.zlib://$archive" : $archive, "rb");
	$rc = null;
	if (! $file)
		$rc = "extractFromArchive: Öffnen missglückt: $archive";
	else {
		if (! ($header = getArchiveString ($file, 2)) && ! $compressed){
			fclose ($file);
			$rc = extractFromArchive ($session, $archive, true, $what);
		} else {
			if ($header != "HamatomaArchive\t0100")
				$rc = "Formatfehler in $archive: '$header'";
			else {
# <hex4_name_size> <filename_with_path> Pfadnamen mit '/' als Trenner!
# <hex8_sec_since_1970)> <char_file_type> <char3_res_1>
# <hex2_info_size> <hex8_rights>
# <hex8_data_size> <file_data>
# <hex8_magic> <hex16_checksum>
				while (true){
					$name = getArchiveString ($file, 4);
					if ($name == '')
						break;
					$time = getArchiveHexValue ($file, 8);
					$type = fread ($file, 4);
					$rights = getArchiveString ($file, 2);
					$size = getArchiveHexValue ($file, 8);
					$node = strrchr ($name, '/');
					if (! $node){
						$path = $session->fFileSystemBase;
						$node = PATH_DELIM . $name;
					} else {
						$path = $session->fFileSystemBase . PATH_DELIM 
							. substr ($name, 0, strlen ($name) - strlen ($node));
						if (! is_dir ($path))
							if (!mkdir ($path, 0777)){
								$rc = "mkdir $path missglückt";
								break;
							}
					}
					$path .= $node;
					if (! ($out = fopen ($path, "wb")))
						$rc = "öffnen missglückt: $path";
					else {
						$bytes = 0;
						$blocksize = $size < 1024 ? $size : 1024;
						while ($bytes <= $size) {
							$data = fread ($file, $blocksize);
							fwrite ($out, $data);
							$bytes += $blocksize;
							if ($size == $bytes)
								break;
							else if ($size - $bytes < $blocksize)
								$blocksize = $size - $bytes;
						}
						fclose ($out);
						$magic = fread ($file, 8);
						if ($magic != "HaMaToMa"){
							$rc = "Magic nicht gefunden: $magic statt HaMaToMa";
							break;
						}
						$checksum = fread ($file, 16);
						if ($checksum != '0000000000000000'){
							$rc = "Prüfsumme: $checksum";
							break;
						}
					}
				}
			}
			fclose ($file);
		}
	}
	return $rc;
}
function instImportPages (&$session,  $import_file, $import_replace) {
	$message = null;
	if (! file_exists ($import_file))
		$message = "Datei nicht gefunden: " . $import_file;
	else {
		$file = fopen ($import_file, "r");
		$count_inserts = 0;
		$count_updates = 0;
		$count_lines = 0;
		while ($line = fgets ($file)){
			if (preg_match ('/^#name=(\S+)\tlines=(\d+)\ttype=(\w+)\t/', $line, $param)){
				$name = $param[1];
				$lines = $param[2];
				$type = $param [3];
				$session->trace(TC_Gui1, 'instImportPagesAnswer-2: ' . $line);
				if ( ($page = dbPageId ($session, $name)) > 0){
					$count_updates++;
					if ($import_replace)
						dbDeleteByClause ($session, T_Text, 'page=' . $page);
				} else {
					$page = dbInsert ($session, T_Page, 'name,type', 
						dbSqlString ($session, $name) . ',' 
						. dbSqlString ($session, $type));
					$count_inserts++;
				}
				$text = "";
				$session->trace(TC_Gui1, 'instImportPagesAnswer-3: ' . $lines);
				$count_lines += $lines;
				for ($ii = 0; $ii < $lines; $ii++)
					$text .= fgets ($file);
				if ($import_replace)
					$old_id = dbSingleValue ($session, 'select max(id) from ' . dbTable ($session, T_Text) 
						. ' where page=' . (0+$page));
				$text_id = dbInsert ($session, T_Text, 'page,type,text', 
					$page . ',' . dbSqlString ($session, $type)
					. ',' . dbSqlString ($session, $text));
				if ($import_replace && $old_id > 0)
					dbUpdate ($session, T_Text, $old_id, 'replacedby=' . $text_id);
			}
		}
		fclose ($file);
		$message = 'Datei ' . $import_file . ' wurde eingelesen. Neu: ' . (0 + $count_inserts)
			. ' Geändert: ' . (0 + $count_updates) . ' Zeilen: ' . (0 + $count_lines);
	}
	return $message;
}
function stripPhpSource (&$session, $fn_source, $fn_target){
	$message = null;
	if (! ($file = fopen ($fn_source, "r")))
		$message = "+++ Kann Datei nicht öffnen: $fn_source";
	else {
		$lines = array ();
		$line_count = $old_size = $new_size = 0;
		$comment = false;
		while ( ($line = fgets ($file))) {
			$line_count++;
			$old_size += strlen ($line);
			if ($comment) {
				if ( ($pos = getPos ($line, '*/')) >= 0){
					$line = substr ($line, $pos + 2);
					$comment = false;
				} else
					$line = "\n";
			}  // comment
			$line = preg_replace ('/^[ \t]+/', '', $line);
			$line = preg_replace ('/\s+$/', "\n", $line);
			if (preg_match ("!^([^\"']*)(//|#)!", $line, $match))
				$line = $match [1]  . "\n";
			# $session->trace (TC_X, 'Zeile: ' . $line);
			if (getPos ($line, "'")< 0 && getPos ($line, '"') < 0){
				# $session->trace (TC_X, '1: ' . $line);
				$line = preg_replace ('|\s*/\*.*?\*/\s*|', ' ', $line);
				# $session->trace (TC_X, '2: ' . $line);
				if ( ($pos = getPos ($line, '/*')) >= 0){
					$line = ($pos > 0 ? substr ($line, 0, $pos) : '') . "\n";
					$comment = true;
				}
			}
			if (preg_match ('/->trace\b.*;\s*$/', $line) 
					&& ! preg_match ('/TC_(Warning|Error)/', $line))
				$line = "\n";
			array_push ($lines, $line);
			$new_size += strlen ($line);
		} // while
		fclose ($file);
		if ($comment)
			$message = "Kommentar offen";
		else {
			$diff = $old_size - $new_size;
			$message = $fn_source . ': ' . ($diff) . " Zeichen entfernt ("
				. ($old_size == 0 ? 0 : round (100 * $diff / $old_size)) . '%)';
			if (! ($file = fopen ($fn_target, "w"))) {
				$message = "+++ Kann Datei nicht öffnen: $fn_target";
			} else {
				for ($ii = 0; $ii < $line_count; $ii++)
					fwrite ($file, $lines [$ii]);
				fclose ($file);
			} // $no_comment
		}
	}
	return $message;
}
?>
