<?php
// install.php: Installation of the infobasar
// $Id: install.php,v 1.1 2004/05/20 20:55:07 hamatoma Exp $

define ('INSTALL', true);
define ('C_ScriptName', 'install.php');
define ('NO_SERVER', 1);
define ('NO_DB', 2);
define ('DB_EXISTS', 3);

$install_dir = preg_replace ('![^\\/]+$!', '', $SCRIPT_FILENAME);
$base_dir = preg_replace ('!/install/$!', '/', $base_dir);

include "../config.php";

$session = new Session ();
init ($session, $dbType);


guiHeader ($session, 'Installation des InfoBasars');
echo "<h1>Installation des InfoBasars</h1>\n";

$file = $install_dir . 'infobasar_start.sql';
$sql_exists = file_exists ($file);
echo "<p>DB-Definitiondatei $file " . ($sql_exists ? "" : "<b>nicht</b> ") . "gefunden</p>\n";

if (! isset ($db_server))
	$db_server = $session->fDbServer;
if (! isset ($db_user))
	$db_user = $session->fDbUser;
if (! isset ($db_passw))
	$db_passw = $session->fDbPassw;
if (! isset ($db_name))
	$db_name = $session->fDbName;
if (! isset ($db_prefix))
	$db_prefix = $session->fDbTablePrefix;

$db_status = checkDB ($session);
if ($db_status == NO_DB && isset ($db_createdb)) {
	$query = "create database $db_name;";
	$result = mysql_query ($query, $session->fDbInfo);
	if (! $result)
		echo "<p>+++ DB $db_name nicht erzeugt: " . htmlentities (mysql_error ()) . "</p>\n";
	else {
		echo "<p>DB $db_name wurde erzeugt</p>\n";
		$db_status = checkDB ($session);
	}
}

if ($table_status = checkTableStatus ($db_status, $tables_exist))
	echo "<p>$table_status</p>\n";


if (isset ($db_overwrite))
	echo "db_overwrite: $db_overwrite<br/>\n";
if (isset ($db_populate) && (! isset ($db_overwrite) || $db_overwrite == "J")) {
	populate ($session, $file);
} else {
	echo "<form name=\"form\" action=\"install.php\" method=\"post\">\n";
	echo '<table><tr><td>MySQL-Server</td><td>';
	guiTextField ('db_server', $db_server, 16, 0);
	echo "</td></tr>\n<tr><td>Datenbank</td><td>";
	guiTextField ('db_name', $db_name, 16, 0);
	echo "</td></tr>\n<tr><td>Benutzer</td><td>";
	guiTextField ('db_user', $db_user, 16, 0);
	echo "</td></tr>\n<tr><td>Passwort</td><td>";
	guiTextField ('db_passw', $db_passw, 16, 0);
	echo "</td></tr>\n<tr><td>Tabellenvorspann</td><td>";
	guiTextField ('db_prefix', $db_prefix, 16, 0);
	echo "</td></tr>\n<tr><td>";
	if ($sql_exists) {
		if ($tables_exist) {
			guiCheckBox ('db_overwrite', 'Tabellen überschreiben', false);
		}
	}
	echo '</td><td>';
	switch ($db_status) {
	case NO_SERVER: guiButton ('db_retry', 'Erneut anmelden'); break;
	case NO_DB: guiButton ('db_createdb', 'Datenbank erzeugen'); break;
	case DB_EXISTS: guiButton ('db_populate', 'Startwerte eintragen'); break;
	default: break;
	}
	echo "</td></tr></table>\n";

	guiFinishForm ($session);
}
echo guiFinishBody ($session, null);


function checkDB (&$session) {
	global $db_server, $db_user, $db_passw, $db_name;
	$result = NO_SERVER;
	if (!($dbc = mySql_pconnect($db_server, $db_user, $db_passw))) {
		echo "<p>Kann mich mit mySQL-Server nicht verbinden. Stimmen User/Passw?</p>";
		echo '<p>MySql meldet: ' . htmlentities (mySql_error()) . "</p>\n";
	} elseif (!mySql_select_db($db_name, $dbc)) {
		$session->setDbConnectionInfo ($dbc, $dbc);
		echo  "<p>DB $db_name nicht gefunden<br/>\n";
		echo 'MySql meldet: ' . htmlentities (mySql_error()) . "</p>\n";
		$result = NO_DB;
	} else {
		$session->setDbConnectionInfo ($dbc, $dbc);
		$result = DB_EXISTS;
	}
	return $result;
}
function checkTableStatus ($db_status, &$exists) {
	global $db_name, $db_prefix;
	$status = '';
	$exists = false;
	if ($db_status == DB_EXISTS) {
		$result = mysql_list_tables($db_name);

		if (!$result) {
			$status = "keine Tabellen vorhanden";
			exit;
		} else {
			$count1 = $count2 = 0;
			while ($row = mysql_fetch_row($result)) {
				$count1++;
				if ( ($pos = strpos ($row[0], $db_prefix)) >= 0 && is_int ($pos))
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
function populate ($session, $fn_sql) {
	if (! ($file = fopen ($fn_sql, "r"))) {
		echo "<p>+++ Kann Datei nicht &ouml;ffnen: $fn_sql</p>\n";
	} else {
		$status = null;
		$line_count = $comments = 0;
		while (! feof ($file)) {
			$line_count++;
			$line = fgets ($file, 64000);
			if (strlen ($line) <= 3 || ($pos = strpos ($line, '#')) == 0 && is_int ($pos))
				$comments++;
			else if ($status == 'create') {
				$sql .= ' ' . $line;
				if ( ($pos = strpos ($line, ')')) == 0 && is_int ($pos)) {
					sqlStatement ($session, $sql);
					$status = null;
				} else {
				}
			} elseif ( ($pos = strpos ($line, 'CREATE TABLE')) == 0 && is_int ($pos)) {
					$sql = $line;
					$status = 'create';
			} else {
				sqlStatement ($session, $line);
			}
		}
		fclose ($file);
		echo "<p>$line_count Zeilen gelesen, $comments Kommentar(e)</p>\n";
	}
}
function sqlStatement ($session, $query) {
	if (!mysql_query($query, $session->fDbInfo))
		echo '<p>+++ SQL-Fehler: ' . htmlentities (mySql_error ()) . " <br/>$query</p>";
}
?>
