<?php
// $Id: install.php,v 1.25 2005/01/14 03:15:46 hamatoma Exp $
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

define ('Install_Version', '0.7.3 (2005.01.14)');
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

define ('C_CHECKBOX_TRUE', 'J');

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
	
	var $fSessionId;
	var $fStep;

	var $fOutputState; // Init Header Body
	var $fFormExists; // true: Es gab schon ein <form> im Text.

	var $fScriptURL; // Ohne / am Ende
	var $fScriptBase; // ohne Host, ohne *.php. Bsp: /hamatoma/wiki
	var $fScriptFile; // Relativ zu DocumentRoot
	var $fFileSystemBase; // Absolutpfad im Filesystem des Servers

	var $fTraceFlags;
	var $fTraceFile;

	function Session ($start_time){
		$this->fDbType = DB_MySQL;
		$this->fDbServer = 'localhost';
		$this->fDbTablePrefix = 'infobasar_';
		$this->fDbName = '';
		$this->fDbUser = '';
		$this->fDbPassw = '';
		$this->fOutputState = 'Init';
		$this->fTraceFlags = TC_X;
		# Flags unten setzen!;
		$this->fTraceFile = null;
		#$this->fTraceFile = '/tmp/inst.log';

		$this->fStartTime = getMicroTime ($this, $start_time);
		session_start();
		$this->fSessionId = session_id ();
		if (!session_is_registered('inst_step')) {
			session_register('inst_step');
			$_SESSION ['inst_step'] = 0;
		}
		if (isset ($_POST ['inst_next']))
			$_SESSION ['inst_step']++;
		elseif (isset ($_POST ['inst_last']))
			$_SESSION ['inst_step']--;
		if ($_SESSION ['inst_step'] > 10)
			$_SESSION ['inst_step'] = 0;
		$this->fStep = $_SESSION ['inst_step'];
 		$uri = $_SERVER['REQUEST_URI'];
		$this->fScriptURL = $uri;
		$pos = strpos ($uri, ".php");
		if ($pos <= 0){
			if (strpos ($uri, ".php") > 0)
				$this->fScriptURL = $uri;
			else
				$this->fScriptURL = $uri . "/install.php";
			$this->fPageURL = '';
		} else {
			$this->fScriptURL = substr ($uri, 0, $pos + 4);
			if ($pos + 5 < strlen ($uri)){
				$pageURL = substr ($uri, $pos + 5);
				if ( ($pos = strpos ($pageURL, '/', 1)) > 0)
					$this->fScriptURL = substr ($pageURL, 0, $pos);
			}
		}
		$this->fScriptFile = $_SERVER['SCRIPT_FILENAME'];
		$this->fScriptBase = preg_replace ('/\/\w+\.php.*$/', '', $_SERVER['PHP_SELF']);
		$this->fFileSystemBase =  preg_replace ('/\/\w+\.php.*$/', '', $this->fScriptFile);
		#dumpPost($this);
		#$this->trace (TC_X, "Step: $this->fStep");
	}
	function trace($class, $msg){
		if ($this->fTraceFile != null && ($file = fopen ($this->fTraceFile, "a")) != null){
			fwrite ($file, $msg . "\n");
			fclose ($file);
		}
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
	function setDbResult ($result) { $this->fDbResult = $result; }
	function setPageData ($name, $date, $by) {
		$this->fPageName = $name;
		$this->fPageChangedAt = dbSqlDateToText ($this, $date);
		$this->fPageChangedBy = $by;
	}
}

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

instGetConfig ($session);
if (substr ($session->fScriptBase, strlen ($session->fScriptBase) - 8) != "/install")
	fatalError ('install.php <strong>muss</strong> in einem Verzeichnis install liegen!'
		. "<br>\nScriptBase: " . $session->fScriptBase . "<br>\nVerzeichnis: "
		. substr ($session->fScriptBase, strlen ($session->fScriptBase) - 8));
else {
	switch ($session->fStep){
	case 2: instDBAnswer ($session); break;
	case 3:	instFinish ($session); break;
	case 4: instExit ($session); break;
	case 1: instConfigFileAnswer ($session); break;
	default:
	case 0:	instArchiveAnswer ($session); break;
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
function dumpPost (&$session){
	echo 'Inhalt der Variable _POST:<br>';
	foreach ($_POST as $name => $value)
		echo $name . " = " . $value . "<br>";
}
function instShowDir (&$session, $path, $headline = null, $pattern = null, 
		$button_text = null, $button_prefix = null, $file_prefix = null, $with_form = true){
	$session->trace (TC_Init, 'instShowDir');
	$dir = opendir ($path);
	if ($headline == null)
		$headline = "Verzeichnis $path auf dem Server";
	guiHeadline ($session, 2, $headline);
	if ($button_text != null && $with_form){
		guiStartForm ($session, 'Form');
	}
	echo '<table border="1"><tr><td><b>Name:</b></td>';
	echo '<td><b>Gr&ouml;&szlig;e</b></td><td><b>Ge&auml;ndert am</b></td>';
	if ($button_text != null)
		echo '<td><b>Aktion</b></td>';
	echo '</tr>' . "\n";
	$no = 0;
	while ($file = readdir ($dir)){
		if ($file != '.' && $file != '..' 
			&& ($pattern == null || preg_match ($pattern, $file))){
			$name = $path . $file;
			echo '<tr><td>';
			echo htmlentities ($file);
			echo '</td><td>';
			echo is_dir ($name) ? 'Verzeichnis' : filesize ($name);
			echo '</td><td>';
			echo date ("Y.m.d H:i:s", filemtime ($name));
			if ($button_text != null){
				$no++;
				echo '<div>';
				guiHiddenField ($file_prefix . $no, $path . $file);
				echo '</div></td><td>';
				guiButton ($button_prefix . $no, $button_text);
			}
			echo '</td></tr>' . "\n";
		}
	}
	echo '</table>' . "\n";
	closedir ($dir);
	if ($button_text != null && $with_form)
		guiFinishForm ($session);
}
function instDocu (&$session, $install, $update){
	guiHeadline ($session, 2, 'Empfehlungen');
	echo '<table border="1"><tr><td><h2>Standard-Installation</h2></td>';
	echo '<td><h2>Standard-Update</h2></td>';
	echo "</tr>\n<tr><td><ul>";
	echo $install;
	echo "<li>Weiter</li></ul></td>\n<td><ul>";
	echo $update;
	echo "<li>Weiter</li></ul></td></tr>\n</table>\n";
}
function instArchive (&$session, $message =  null) {
	$session->trace (TC_Init, 'instArchive');
	guiHeader ($session, 'Schritt 0');
	guiHeadline ($session, 1, 'Datei- und Archivverwaltung');
		
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	guiHeadline ($session, 2, 'Archiv hochladen');
	if (empty ($_POST ['archive_dir_name']))
		$_POST ['archive_dir_name'] = '.';
	if (isset ($_POST ['archive_show']))
		$_POST ['archive_show_dir'] = CHECKBOX_TRUE;
	echo '<form enctype="multipart/form-data" action="' . C_ScriptName
		. '" method="post">' . "\n";
	echo '<p>';
	guiHiddenField ('MAX_FILE_SIZE', 1000000);
	guiUploadFile ('archive_uploadfile');
	echo ' ';
	guiButton ('archive_upload', 'Hochladen');
	echo '</p>';
	guiFinishForm ($session);
		
	$path = $session->fFileSystemBase . PATH_DELIM; 
	if ($_POST ['archive_dir_name'] != '.')
		$path .= $_POST ['archive_dir_name'] . PATH_DELIM;
	if (guiChecked ($session, 'archive_show_dir'))
		instShowDir ($session, $path);
	$path = $session->fFileSystemBase . PATH_DELIM; 
	instShowDir ($session, $path, "Archive (Verzeichnis install)", '/[\.]hma([.]gz)?$/',
		'Entpacken', 'archive_extract', 'archive_file');
	instDocu ($session, '<li>Durchsuchen. infobasar-X.Y.hma.gz-Archiv einstellen</li>'
		. '<li>Hochladen</li><li>Diese Datei "Entpacken"</li>',
		'<li>Durchsuchen. infobasar-X.Y-update.hma.gz-Archiv einstellen</li>'
		. '<li>Hochladen</li><li>Diese Datei "Entpacken"</li>');
	guiHeadline ($session, 2, 'Optionen');
	guiStartForm ($session, 'Form');
	echo '<p>Verzeichnis: ';
	guiComboBox ('archive_dir_name', 
		array ('.', '..', '../db', '../pic', '../import', '../css', '../docu'),
		null, null);
	echo ' ';
	guiCheckBox ('archive_show_dir', 'Anzeigen');
	guiButton ('archive_show', 'Aktualisieren');
	echo '</p>';
	guiLine ($session, 2);
	echo '<p>';
	guiButton ('inst_next', 'weiter');
	echo '</p>';
	guiFinishForm ($session);
	guiFinishBody ($session);
}
function instArchiveAnswer (&$session){
	$session->trace (TC_Init, "instArchiveAnswer");
	$message = null;
	if (isset ($_POST ['archive_upload'])){
		$session->trace (TC_Init, 'instArchiveAnswer: archive_upload');
		$name =  $_FILES['archive_uploadfile']['name'];
		if (move_uploaded_file($_FILES['archive_uploadfile']['tmp_name'],
			$session->fFileSystemBase . PATH_DELIM . $name)) {
			$message = 'Datei erfolgreich hochgeladen: ' . $name;
		} else {
			$message = 'Problem beim Hochladen von ' . $name . ': ' 
				. $_FILES['archive_uploadfile']['error'];
		}
	} else {
		$session->trace (TC_Init, 'instArchiveAnswer: Button-Antworten');
		foreach ($_POST as $name => $value){
			if (preg_match ('/^archive_extract(\d+)/', $name, $match)){
				$var = 'archive_file' . $match [1];
				$name = $_POST [$var];
				$session->trace (TC_Init, "instArchiveAnswer: $name");
				if (! ($message = extractFromArchive ($session, $name, false, "*")))
					$message = "Archiv $name wurde entpackt";
				break;
			}
		}
	}
	instArchive ($session, $message);
}
function instGetSqlFile (&$session){
	return $session->fFileSystemBase . PATH_DELIM . '../db/infobasar_start.sql';
}
function instGetDesignSqlFile (&$session){
	return $session->fFileSystemBase . PATH_DELIM . '../db/design_start.sql';
}

function instGetStandardPageFile (&$session){
	return $session->fFileSystemBase . PATH_DELIM . '../db/std_pages.wiki';
}
function instStandardEnd (&$session){
	guiLine ($session, 2);
	echo '<p>';
	guiButton ('inst_last', 'zurück');
	echo ' | ';
	guiButton ('inst_next', 'weiter');
	echo '</p>';
	guiFinishForm ($session);
	guiFinishBody ($session);
}
function instConfigFile (&$session, $message =  null) {
	$session->trace (TC_Init, 'instConfigFile');
	guiHeader ($session, 'Schritt 1');
	
	guiHeadline ($session, 2, 'Konfiguration der Datenbank');
	
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	
	$name = $session->fFileSystemBase . PATH_DELIM . '..' . PATH_DELIM . 'config.php';
	$config_exists = file_exists ($name);
	guiParagraph ($session, "Konfigurationsdatei $name existiert" 
		. ($config_exists ? '.' : ' <b>nicht</b>!'), false);
	
	guiStartForm ($session, 'Form');
	$file = instGetSqlFile ($session);
	$file_design = instGetDesignSqlFile ($session);
	$sql_exists = file_exists ($file);
	$design_sql_exists = file_exists ($file_design);

	if (empty ($_POST ['db_server']))
		$_POST ['db_server'] = $session->fDbServer;
	if (empty ($_POST ['db_prefix']))
		$_POST ['db_prefix'] = $session->fDbTablePrefix;
	if (empty ($_POST ['db_user']))
		$_POST ['db_user'] = $session->fDbUser;
	if (empty ($_POST ['db_name']))
		$_POST ['db_name'] = $session->fDbName;
	if (empty ($_POST ['db_passw']))
		$_POST ['db_passw'] = $session->fDbPassw;
	echo '<table><tr><td>MySQL-Server</td><td>';
	guiTextField ('db_server', null, 32, 0);
	echo "</td></tr>\n<tr><td>Datenbank</td><td>";
	guiTextField ('db_name', null, 32, 0);
	echo "</td></tr>\n<tr><td>Benutzer</td><td>";
	guiTextField ('db_user', null, 32, 0);
	echo "</td></tr>\n<tr><td>Passwort</td><td>";
	guiTextField ('db_passw', null, 32, 0);
	echo "</td></tr>\n<tr><td>Tabellenvorspann</td><td>";
	guiTextField ('db_prefix', null, 32, 0);
	echo "</td></tr>\n<tr><td></td><td>";
	guiButton ('config_save', 'Konfiguration speichern');
	echo "</td></tr>\n<tr><td></td><td>";
	guiButton ('config_access', 'Datenbankzugriff testen');
	echo "</td></tr>\n</table>\n";	
	guiLine ($session, 2);
	$status = checkDB ($session, $message);
	guiParagraph ($session, $message, false);
	if ($status == NO_DB)
		guiButton ('config_createdb', 'Datenbank ' . $_POST ['db_name'] . ' erzeugen');
	guiLine ($session, 2);
	guiParagraph ($session, "DB-Definitionsdatei $file " . ($sql_exists ? "" : "<b>nicht</b> ") . "gefunden.", false);;
	guiParagraph ($session, "Design-Definitionsdatei $file_design " . ($design_sql_exists ? "" : "<b>nicht</b> ") . "gefunden.", false);;
	guiLine ($session, 2);
	instDocu ($session, '<li>Felder ausfüllen</li><li>Konfiguration speichern</li>'
		. '<li>Solange korrigieren, bis "Zugang zur Datenbank test ist möglich" erscheint</li>',
		'');
	instStandardEnd ($session);
}
function instConfigFileAnswer (&$session){
	$session->trace (TC_Init, "instConfigFileAnswer");
	$message = "";
	if (isset ($_POST ['config_createdb'])){
		$db_name =  $_POST ['db_name'];
		if (! $session->fDbInfo && checkDB ($session, $message) == NO_DB) {
			$query = "create database $db_name;";
			$result = mysql_query ($query, $session->fDbInfo);
			if ($result)
				$message = 'DB ' .$db_name . ' wurde erzeugt.';
			else
				$messsage = "+++ DB $db_name nicht erzeugt: " 
					. htmlentities (mysql_error ());
		} else {
			$message = "Inkonsistenz";
		}
	} elseif (isset ($_POST ['config_access'])) {
		checkDB ($session, $message);
	} elseif (isset ($_POST ['config_save'])) {
		if (empty ($_POST ['db_server']))
			$message = '+++ Kein Server angegeben. Vielleicht "localhost"?';
		elseif (empty ($_POST ['db_user']))
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
			$session->fDbServer = $_POST ['db_server'];	
			$session->fDbName = $_POST ['db_name'];	
			$session->fDbUser = $_POST ['db_user'];	
			$session->fDbPassw = $_POST ['db_passw'];	
			$session->fDbTablePrefix = $_POST ['db_prefix'];	
			$file = fopen ($name, "w");
			fwrite ($file, 
				"<?php\n"
				. '# config.php: Created by InfoBasar install.php (version '
				. Install_Version
				. ') at ' . strftime ("%Y.%m.%d %H.%N.%S", time()) . "\n"
				. "\$db_type = 'MySQL';\n"
				. '$db_server = \'' . $_POST ['db_server'] . "';\n"
				. '$db_user = \'' . $_POST ['db_user'] . "';\n"
				. '$db_passw = \'' . $_POST ['db_passw'] . "';\n"
				. '$db_name = \'' . $_POST ['db_name'] . "';\n"
				. '$db_prefix = \'' . $_POST ['db_prefix'] . "';\n"
				. "?>\n"
				);
			fclose ($file);
			$message = guiAppendParagraph ($session, $message, 
				"$name wurde gespeichert.");
			
		}
	}
	instConfigFile ($session, $message);
}
function instDB (&$session, $message =  null) {
	$session->trace (TC_Init, 'instDB');
	guiHeader ($session, 'Schritt 2');
	guiHeadline ($session, 2, 'Bestückung der Datenbank');
	
	if (! empty ($message))
		guiParagraph ($session, $message, false);
	
	checkDB ($session, $message);
	$message = null;

	$path = $session->fFileSystemBase . PATH_DELIM; 
	instShowDir ($session, $session->fFileSystemBase . PATH_DELIM . '../db' . PATH_DELIM,
		 "DB-Inhalte (Verzeichnis db)", '/[\.](sql|wiki)$/', 'Installieren', 
		 'db_install', 'db_file');
	
	guiStartForm ($session, 'Form');
	
	$table_status = checkTableStatus ($session, $tables_exists);
	guiParagraph ($session, $table_status, false);
	
	guiParagraph ($session, 'Warnung: Wenn die Datenbank initalisiert wird, werden '
		. '<b>alle</b> vorhandenen Daten in den Tabellen, deren Name mit "'
		. $session->fDbTablePrefix
		. '" beginnen, <b>gelöscht</b>!', false);
	echo '<p>';
	guiButton ('inst_populate', 'Datenbank initialisieren');
	echo '</p>';
	instDocu ($session, '<li>Datenbank initialisieren</li>',
		'<li>std_pages.wiki installieren</li><li>update_x-y.sql installieren</li>');
	instStandardEnd ($session);
}
function instDBAnswer (&$session){
	$session->trace (TC_Init, 'instDBAnswer');
	$message = null;
	if (isset ($_POST ['inst_populate'])) {
		$message = InstPopulateDB ($session, instGetSqlFile ($session), 
			instGetDesignSqlFile ($session));
	} else {
		foreach ($_POST as $name => $value){
			# $session->trace (TC_X, 'instDBAnswer-2: ' . $name);
			if (preg_match ('/^db_install(\d+)/', $name, $match)){
				$var = 'db_file' . $match [1];
				$name = $_POST [$var];
				if ( ($pos = strpos ($name, '.sql')) > 0){
					if (! ($message = executeSqlFile ($session, $name,
							&$line_count, &$comments))){
						$message = "Ausgeführt: $name: $line_count Zeilen ($comments Kommentare)";
						if (getPos ($name, 'design_start.sql') >= 0)
							instAdaptPathInDB ($session, $message);
					}
				} elseif ( ($pos = strpos ($name, '.wiki')) > 0) {
					if (! ($message = instImportPages ($session, $name, true)))
						$message = "Importiert: $name";
				} else
					$message = "unbekannter Dateityp: $name";
				break;
			}
		}
	}
	instDB ($session, $message);
}
function instFinish (&$session, $message = null){
	$session->trace (TC_Init, 'instFinish');
	guiHeader ($session, 'Schritt 3');
	guiHeadline ($session, 2, 'Installation beenden');
	guiParagraph ($session, empty ($message) 
		? $message : 'Der InfoBasar ist jetzt installiert.', false);
	guiStartForm ($session, 'Form');
	echo '<p>Passwort für den Benutzer admin: ';
	guiTextField ('inst_passw', '', 32, 0);
	echo ' ';
	guiCheckbox ('inst_setpassw', 'Passwort setzen', true);
	echo '</p><p>';
	guiCheckbox ('inst_delete', 'Installationsdateien entfernen', true);
	echo '</p><p>';
	guiCheckbox ('inst_documentation', 'Dokumentation entfernen', false);
	echo '</p><p>';
	guiCheckbox ('inst_optimized', 'Laufzeitoptimierung', true);
	echo '</p>';
	instDocu ($session, '<li>Passwort eintragen</li>'
		. '<li>"Passwort setzen" ist angeklickt</li>'
		. '<li>"Installationsdateien löschen" ist angeklickt</li>',
		'<li>"Passwort setzen" ist <b>nicht</b> angeklickt</li>'
		. '<li>"Installationsdateien löschen" ist angeklickt</li>');
	instStandardEnd ($session);
}
function instLink (&$session, $source, $target){
	$message = null;
	$path = getParentDir ($session, $session->fFileSystemBase);
	$path_source = $path . $source;  
	$path_target = $path . $target;  
	if (file_exists ($path_target) && ! unlink ($path_target))
		$message = "Löschen missglückt: $path_target";
	elseif (! symlink ($path_source, $path_target))
		$message = "Symlink missglückt: $path_target -> $path_source";
	return $message;
}
function instUnlink (&$session, $dir, $pattern){
	$negate = false;
	if (ord ($pattern) == ord ('^')){
		$pattern = substr ($pattern, 1);
		$negate = true;
	}
	$path = ($dir == '.' ? $session->fFileSystemBase 
		: (getParentDir ($session, $session->fFileSystemBase) . $dir))
		. PATH_DELIM;
	$dir = opendir ($path);
	while ($file = readdir ($dir)){
		if ($file != '.' && $file != '..' 
			&& ($pattern == null || ($negate ^ preg_match ($pattern, $file)))){
			$name = $path . $file;
			echo $name;
			echo ' wurde ';
			if (! unlink ($name))
				echo '<b>nicht</b> ';
			echo 'gelöscht';
			outNewline ();
		}
	}
	closedir ($dir);
}
function instExit (&$session){
	$error = null;
	$message = null;
	if (guiChecked ($session, 'inst_setpassw')) {
		if (empty ($_POST ['inst_passw']))
			$error = '+++ leeres Passwort ist nicht zulässig!';
		else {
			checkDB ($session, $message);
			$passw = strrev (crypt ($_POST ['inst_passw'], 'admin'));
			sqlStatement ($session, 'update ' . $session->fDbTablePrefix . "user set code='"
				 . $passw . "' where name='admin'");
			$message = 'Passwort wurde gesetzt';
		}
	}
	if ($error)
		instFinish ($session, $error);
	else {
		guiHeader ($session, 'Ende');
		guiHeadline ($session, 2, 'Installation beenden');
		$error = null;
		if ($message)
			guiParagraph ($session, $message, false);
		if (guiChecked ($session, 'inst_optimized')){
			$error = instLink ($session, 'base_opt.php', 'index.php'); 
			if ($error != null)
				$message = $error; 
			else
				if ( ($error = instLink ($session, 'forum_opt.php', 'forum.php')) == null)
					$message = "Links auf optimierte Module wurden erstellt";
				else
					$message = $error;
		} else {
			$error = instLink ($session, 'base_module.php', 'index.php'); 
			if ($error != null)
				$message = $error; 
			else
				if ( ($error = instLink ($session, 'forum_module.php', 'forum.php')) == null)
					$message = "Links auf Standard-Module (nicht optimiert) wurden erstellt";
				else
					$message = $error;
		}
		guiParagraph ($session, $message, false);
		if ($error == null && guiChecked ($session, 'inst_documentation')){
			instUnlink ($session, 'docu', '^/index.html/');
		}
		if ($error == null && guiChecked ($session, 'inst_delete')){
			instUnlink ($session, '.', '^/index.html/');
			instUnlink ($session, 'db', '^/index.html/');
		}
		guiParagraph ($session, 'Die Installation ist jetzt beendet.', false);
		guiLine ($session, 2);
		guiExternLink ($session, '../index.php', 'Zur Anmeldung');
		guiFinishBody ($session);
	}
}
// ------------------------------------------
function checkDB (&$session, &$message) {
	$session->trace (TC_Init, 'checkDB: ' . $session->fDbName);
	$result = NO_SERVER;
	if (!($dbc = mySql_pconnect($session->fDbServer, $session->fDbUser, 
		$session->fDbPassw))) {
		$message = 'Kann mich mit mySQL-Server nicht verbinden.'
			. ' Stimmen Benutzer / Passwort?</p>'
			. '<p>MySql meldet: ' . htmlentities (mySql_error());
	} elseif (!mysql_select_db($session->fDbName, $dbc)) {
		$session->setDbConnectionInfo ($dbc, $dbc);
		$message = 'DB ' . $session->fDbName . ' nicht gefunden'
			. '</p><p>MySql meldet: ' . htmlentities (mySql_error());
		$result = NO_DB;
	} else {
		$session->setDbConnectionInfo ($dbc, $dbc);
		$message = 'Zugang zur Datenbank ' . $session->fDbName . ' ist möglich.';
		$result = DB_EXISTS;
	}
	$session->trace (TC_Init, 'checkDB: ' . $result);
	return $result;
}
function checkTableStatus (&$session, &$exists) {
	$session->trace (TC_Init, 'checkTableStatus');
	$status = '';
	$exists = false;
	if ($session->fDbConnection) {
		$session->trace (TC_Init, 'checkTableStatus-2');
		$result = mysql_list_tables($session->fDbName);

		if (!$result) {
			$session->trace (TC_Init, 'checkTableStatus-3');
			$status = "keine Tabellen vorhanden";
		} else {
			$session->trace (TC_Init, 'checkTableStatus-4');
			$count1 = $count2 = 0;
			while ($row = mysql_fetch_row($result)) {
				$count1++;
				if (getPos ($row[0], $session->fDbTablePrefix) >= 0)
					$count2++;
			}
			$session->trace (TC_Init, 'checkTableStatus-5');
			$status = ($count1 + 0) . ' Tabelle(n), davon ' . ($count2 + 0)
				. ' mit Vorspann ' . $session->fDbTablePrefix . ' in der DB';
			$exists = $count2 > 0;
			mysql_free_result($result);
		}
	}
	return $status;
}
function executeSqlFile (&$session, $fn_sql, &$line_count, &$comments){
	$message = null;
	$line_count = $comments = 0;
	if (checkDB ($session, $message) != DB_EXISTS)
		;
	elseif (! ($file = fopen ($fn_sql, "r"))) {
			$message = "+++ Kann Datei nicht &ouml;ffnen: $fn_sql";
	} else {
		$message = null;
		$status = null;
		$db_prefix = $session->fDbTablePrefix;
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
				} elseif (preg_match ('/^create\s+table/i', $line)) {
					$sql = str_replace (' infobasar_', ' ' . $db_prefix, $line);
					$status = 'create';
				} else {
					if ( ($pos = strpos ($line, 'infobasar_')) > 0)
						$line = substr ($line, 0, $pos) . $db_prefix
							. substr ($line, $pos + 10);
					sqlStatement ($session, $line);
				}
			}
		}
		fclose ($file);
	}
	return $message;
}
function instUpdateMacro (&$session, $macro, $value, &$message){
	$count = sqlUpdate ($session, 'macro', " value='" . $value . '\'', 
		"name = '$macro'", true);
	if ($count == 0)
		$message .= "\n<br>+++ Update missglückt: Makro $name nicht gefunden.";
	else {
		$message .= "\n<br>$macro wurde auf $value gesetzt.";
		if ($count > 1)
			$message .= " $count mal!";
	}
}
function instAdaptPathInDB (&$session, &$message){
	$path = getParentDir ($session, $session->fScriptBase);
	if (empty ($path))
		$path = PATH_DELIM;
	instUpdateMacro ($session, 'BaseModule', $path . "index.php/", $message); 
	instUpdateMacro ($session, 'ForumModule', $path . "forum.php/", $message); 
	instUpdateMacro ($session, 'ScriptBase', $path, $message); 
	
	$count = sqlUpdate ($session, 'param', " text='" . $path . "css/phpwiki.css'", 
		"pos=152", true);
	if ($count == 0)
		$message .= "\n<br>+++ Parameter 152 (CSS-Datei) nicht gefunden.";
	else
		$message .= "<br>\n" . 'CSS wurde auf ' . $path . "css/phpwiki.css gesetzt. ($count mal)";
}
function instPopulateDB (&$session, $fn_sql, $fn_sql_design) {
	$session->trace (TC_Init, "instPopulateDB:");
	$db_prefix = $session->fDbTablePrefix;
	$message = '';
	if (checkDB ($session, $message) == DB_EXISTS) {
		$message = executeSqlFile ($session, $fn_sql, $line_count, $comments);
		if (empty ($message))
			$message = executeSqlFile ($session, $fn_sql_design, $line_count2, $comments2);
		if (empty ($message)){
			$message = 'Die Infobasar-Tabellen wurden initialisiert: '
				 . ($line_count + $line_count2) . ' Zeilen gelesen, davon '
				. ($comments + $comments2) . ' Kommentare';
			instAdaptPathInDB ($session, $message);
			$fn_pages = instGetStandardPageFile ($session);
			$message .= '<br>' . instImportPages ($session, $fn_pages, true);
		}
	}
	return $message;
}
function sqlUpdate (&$session, $table, $what, $where, $return_count = false){
	$rc = -1;
	if ($return_count){
		$query = 'select count(id) from ' . $session->fDbTablePrefix . $table
			. ' where ' . $where;
		# $session->trace (TC_X, "sqlUpdate: $query");
		$rc = dbSingleValue ($session, $query);
	}
	$session->trace (TC_Db1, 'sqlUpdate: ' .$table .', ' . $what . ',' .  $where);
	$query = 'update ' . $session->fDbTablePrefix . $table . " set " . $what . " where " . $where;
	sqlStatement ($session, $query);
	return $rc;
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
	echo "<input type=$type name=\"$name\"";
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
function guiHiddenField ($name, $text = null) {
	if ($text == null)
		$text = isset ($_POST [$name]) ? $_POST [$name] : "";
	guiField ($name, '"hidden"', $text, 0, 0, null);
}
function guiTextField ($name, $text, $size, $maxlength){
	if ($text == null)
		$text = isset ($_POST [$name]) ? $_POST [$name] : "";
	guiField ($name, '"text"', $text, $size, $maxlength, null);
}
function guiPasswordField ($name, $text, $size, $maxlength){
	guiField ($name, '"password"', $text, $size, $maxlength, null);
}
function guiTextArea ($name, $content, $width, $height){
	if ($content == null && isset ($_POST [$name]))
		$content = $_POST [$name];
	echo "<textarea name=\"$name\" cols=\"$width\" rows=\"$height\">\n";
	echo $content;
	echo "</textarea>\n";
}
function guiButton ($name, $text){
	echo "<input class=\"wikiaction\" name=\"$name\" value=\"$text\" type=\"submit\">";
}
function guiRadioButton ($name, $text, $checked){
	guiField ($name, '"radio"', $text, 0, 0,
		isset ($checked) && $checked ? "checked" : "");
}
function guiCheckBox ($name, $text, $checked = null){
	if ($checked == null)
		$checked = isset ($_POST [$name]) && $_POST [$name] == C_CHECKBOX_TRUE;
	guiField ($name, '"checkbox"', C_CHECKBOX_TRUE, 0, 0,
		isset ($checked) && $checked ? 'checked' : '');
	echo htmlentities ($text) . " ";
}
function guiChecked(&$session, $name){
	return isset ($_POST [$name]) && $_POST [$name] == C_CHECKBOX_TRUE;
}
function guiComboBox ($name, $options, $values, $ix_selected = 0) {
	echo '<select name="' . $name . '" size="1' // . count ($options)
		. "\">\n";
	if ($ix_selected == null)
		$ix_selected = ! isset ($_POST [$name]) 
			? -1 : indexOf ($options, $_POST [$name]); 
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
	echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
	echo "\n<html>\n";
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
	$session->trace (TC_Init, 'instGetConfig: ');
	
	$name = $session->fFileSystemBase . PATH_DELIM . ".." . PATH_DELIM . "config.php";
	$session->trace (TC_Init, 'instGetConfig: ' . $name);
	if (is_file ($name)){
		$file = fopen ($name, "r");
		while ($line = fgets ($file, 128)) {
			if (preg_match (
				'/^\$(db_(server|user|passw|name|prefix))\s*=\s*\'([^\']+)\'/',
				$line, $match)) {
				# $session->trace (TC_X, 'instGetConfig-2: ' . $match [1] . "=" . $match [3]);
				switch ($match [2]){
				case 'server': $session->fDbServer = $match [3]; break;
				case 'user': $session->fDbUser = $match [3]; break;
				case 'name': $session->fDbName = $match [3]; break;
				case 'passw': $session->fDbPassw = $match [3]; break;
				case 'prefix': $session->fDbTablePrefix = $match [3]; break;
				default:
				}
			}
		}
		fclose ($file);
		# $session->trace (TC_X, 'instGetConfig: ' . "N: $session->fDbName U: $session->fDbUser");
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
					if (file_exists ($path))
						unlink ($path);
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
	elseif (checkDB ($session, $message) == DB_EXISTS) {
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
function indexOf ($array, $value){
	$ix = -1;
	for ($ii = 0; $ix < 0 && $ii < count ($array); $ii++)
		if ($array [$ii] == $value)
			$ix = $ii;
}
function getParentDir (&$session, $path){
	return preg_replace ('![^/]+$!', '', $path);
}
?>
