# phpMyAdmin SQL Dump
# version 2.5.5-rc1
# http://www.phpmyadmin.net
#
# Host: localhost
# Erstellungszeit: 15. April 2004 um 00:57
# Server Version: 4.0.17
# PHP-Version: 4.3.3
# 
# Datenbank: `infobasar`
# 
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `infobasar_download`
#

DROP TABLE IF EXISTS infobasar_download;
CREATE TABLE infobasar_download (
  id int(11) NOT NULL auto_increment,
  changedat datetime NOT NULL default '0000-00-00 00:00:00',
  area varchar(128) default NULL,
  name varchar(255) default NULL,
  code varchar(32) default NULL,
  description varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Daten für Tabelle `infobasar_download`
#


# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `infobasar_forum`
#

DROP TABLE IF EXISTS infobasar_forum;
CREATE TABLE infobasar_forum (
  id int(11) NOT NULL auto_increment,
  name varchar(64) default NULL,
  description varchar(255) NOT NULL default '',
  readgroup int(11) default NULL,
  writegroup int(11) default NULL,
  admingroup int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Daten für Tabelle `infobasar_forum`
#

INSERT INTO infobasar_forum VALUES (1, 'Allgemein', 'Was sonst nirgens hinpasst', 1, 1, 0);
INSERT INTO infobasar_forum VALUES (2, 'Reiten', 'Rund ums Reiten', 1, 1, 1);
INSERT INTO infobasar_forum VALUES (3, 'Feldenkrais', 'Gesundheit durch richtiges Bewegen', 1, 1, 1);

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `infobasar_group`
#

DROP TABLE IF EXISTS infobasar_group;
CREATE TABLE infobasar_group (
  id int(11) NOT NULL auto_increment,
  changedat datetime NOT NULL default '0000-00-00 00:00:00',
  name varchar(32) default NULL,
  description varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Daten für Tabelle `infobasar_group`
#

INSERT INTO infobasar_group VALUES (1, '0000-00-00 00:00:00', 'Gast', 'Alle Besucher');
INSERT INTO infobasar_group VALUES (2, '0000-00-00 00:00:00', 'Registriert', 'Alle registrierten  Benutzer');
INSERT INTO infobasar_group VALUES (3, '0000-00-00 00:00:00', 'Moderator', 'Moderatoren');
INSERT INTO infobasar_group VALUES (4, '0000-00-00 00:00:00', 'Administratoren', 'Administratoren');

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `infobasar_page`
#

DROP TABLE IF EXISTS infobasar_page;
CREATE TABLE infobasar_page (
  id int(11) NOT NULL auto_increment,
  name varchar(64) NOT NULL default '',
  TYPE char(1) default NULL,
  createdat datetime default NULL,
  changedat datetime NOT NULL default '0000-00-00 00:00:00',
  readgroup int(11) default NULL,
  writegroup int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Daten für Tabelle `infobasar_page`
#

INSERT INTO infobasar_page VALUES (1, 'StartSeite', 'w', NULL, '2004-04-09 21:56:48', 0, 0);
INSERT INTO infobasar_page VALUES (2, 'HilfeAendern', 'w', NULL, '0000-00-00 00:00:00', 1, 3);
INSERT INTO infobasar_page VALUES (3, 'MeineSeite', 'w', '2004-03-30 21:11:41', '2004-03-30 23:14:10', 0, 0);
INSERT INTO infobasar_page VALUES (4, 'HtmlDemo', 'h', '2004-03-30 23:16:20', '0000-00-00 00:00:00', 0, 0);

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `infobasar_param`
#

DROP TABLE IF EXISTS infobasar_param;
CREATE TABLE infobasar_param (
  id int(11) NOT NULL auto_increment,
  name varchar(128) default NULL,
  theme int(11) default NULL,
  pos int(11) default NULL,
  text text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Daten für Tabelle `infobasar_param`
#

INSERT INTO infobasar_param VALUES (1, 'Wiki-Seiten-Head-Abschnitt', 10, 101, '<head><title>[PageName] (Wiki)</title></head>');
INSERT INTO infobasar_param VALUES (2, 'Wiki-Seiten Body-Abschnitt-Anfang', 10, 102, '<body>\r\n<h1>[PageName]</h1>\r\n');
INSERT INTO infobasar_param VALUES (3, 'Wiki-Seiten Body-Abschnitt-Ende', 10, 103, '<hr style="width: 100%; height: 2px;">\r\nLetzte &Auml;nderung: [PageChangedAt]  [PageChangedBy] <br>\r\n<a href="[PageName]?action=edit">&Auml;ndern</a>\r\n | <a href="!search">Suchen</a>\r\n</body>\r\n');
INSERT INTO infobasar_param VALUES (4, 'Wiki-Änderung Head-Abschnitt', 10, 104, '<head><title>&Auml;nderung von [PageName]</title></head>');
INSERT INTO infobasar_param VALUES (5, 'Wiki-Änderung Body-Abschnitt-Anfang', 10, 105, '<body>\r\n<h1>&Auml;nderung von [PageName]</h1>');
INSERT INTO infobasar_param VALUES (6, 'Wiki-Änderung Body-Abschnitt-Ende', 10, 106, '<small>Textformatierungsregeln:</small>\r\n<table border="1">\r\n<tr><td><small>Zeilenanfang</small></td><td><small>Im Text</small></td><td><small>Links</small></td></tr>\r\n<tr><td><small>! &Uuml;berschrift<br>* Aufz&auml;hlung<br># num. Aufz&auml;hlung<br>---- Linie (4 -)</small></td>\r\n<td><small>\'\'<i>Zitat</i>\'\' (je 2 mal \')<br>\'\'\'<b>wichtig</b>\'\'\' (je 3 mal \')<br>__<u>unterstrichen</u>__ (je 2 mal _)<br> [Newline] Zeilenwechsel</small></td>\r\n<td><small>WikiName<br/> !GmbH<br/>[URL]<br>[URL Text]</small></td>\r\n</tr></table>\r\n</body>\r\n\r\n');
INSERT INTO infobasar_param VALUES (7, 'HTML-Seiten-Head-Abschnitt', 10, 201, '<head><title>[PageName] (HTML)</title></head>');
INSERT INTO infobasar_param VALUES (8, 'HTML-Seiten Body-Abschnitt-Anfang', 10, 202, '<body>\r\n<h1>$PageName$</h1>');
INSERT INTO infobasar_param VALUES (9, 'HTML-Seiten Body-Abschnitt-Ende', 10, 203, '<hr style="width: 100%; height: 2px;">\r\nLetzte &Auml;nderung: [PageChangedAt]  [PageChangedBy] <br>\r\n<a href="[PageName]?action=edit">&Auml;ndern</a>\r\n | <a href="!search">Suchen</a>\r\n</body>\r\n');
INSERT INTO infobasar_param VALUES (10, 'HTML-Änderung Head-Abschnitt', 10, 204, '<head><title>&Auml;nderung von [PageName]</title></head>');
INSERT INTO infobasar_param VALUES (11, 'HTML-Änderung Body-Abschnitt-Anfang', 10, 205, '<body>\r\n<h1>&Auml;nderung von [PageName]</h1>');
INSERT INTO infobasar_param VALUES (12, 'HTML-Änderung Body-Abschnitt-Ende', 10, 206, '</body>');

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `infobasar_posting`
#

DROP TABLE IF EXISTS infobasar_posting;
CREATE TABLE infobasar_posting (
  id int(11) NOT NULL auto_increment,
  createdat datetime default '0000-00-00 00:00:00',
  changedat datetime NOT NULL default '0000-00-00 00:00:00',
  changedby int(11) default NULL,
  forum int(11) default NULL,
  author varchar(64) default NULL,
  top int(11) default NULL,
  reference int(11) default NULL,
  subject varchar(64) default NULL,
  text text,
  calls int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Daten für Tabelle `infobasar_posting`
#

#
# Tabellenstruktur für Tabelle `infobasar_text`
#

DROP TABLE IF EXISTS infobasar_text;
CREATE TABLE infobasar_text (
  id int(11) NOT NULL auto_increment,
  page int(11) default NULL,
  TYPE char(1) default NULL,
  text text,
  createdat datetime default NULL,
  createdby varchar(64) default NULL,
  changedat datetime NOT NULL default '0000-00-00 00:00:00',
  replacedby int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Daten für Tabelle `infobasar_text`
#

INSERT INTO infobasar_text VALUES (117, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n\r\n\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '2004-04-11 11:54:04', 'wk', '2004-04-11 11:54:04', NULL);
INSERT INTO infobasar_text VALUES (115, 1, 'w', '!Startseite[Newline](Allgemein)\r\n!! meine Links\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n', '2004-04-11 11:43:25', 'wk', '2004-04-11 11:43:25', NULL);
INSERT INTO infobasar_text VALUES (114, 1, 'w', '!Startseite[Newline](Allgemein)\r\n!! meine Links\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n', '2004-04-11 11:43:11', 'wk', '2004-04-11 11:43:11', NULL);
INSERT INTO infobasar_text VALUES (116, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n\r\n\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '2004-04-11 11:53:00', 'wk', '2004-04-11 11:53:00', NULL);
INSERT INTO infobasar_text VALUES (3, 3, 'w', '! Überschrift\r\nSandKiste\r\nhttp://www.heise.de', '2004-04-08 22:51:54', 'wk', '2004-04-08 22:54:16', NULL);
INSERT INTO infobasar_text VALUES (4, 4, 'h', '<h2>Demo einer HTML-Seite<h2>\r\n<p>Dies ist ein Absatz</p>', '2004-04-08 22:54:16', 'wk', '2004-04-08 22:55:52', NULL);
INSERT INTO infobasar_text VALUES (109, 1, 'w', '!Startseite\r\n!! meine Links\r\n----\r\nKategorieOrdnung\r\n', '2004-04-11 09:55:10', 'wk', '2004-04-11 09:55:10', NULL);
INSERT INTO infobasar_text VALUES (110, 1, 'w', '!Startseite\r\n!! meine Links\r\n----\r\nKategorieOrdnung\r\n', '2004-04-11 09:55:53', 'wk', '2004-04-11 09:55:53', NULL);
INSERT INTO infobasar_text VALUES (111, 1, 'w', '!Startseite\r\n!! meine Links\r\n----\r\nKategorieOrdnung\r\n', '2004-04-11 09:58:32', 'wk', '2004-04-11 09:58:32', NULL);
INSERT INTO infobasar_text VALUES (112, 1, 'w', '!Startseite\r\n!! meine Links\r\n----\r\nKategorieOrdnung\r\n', '2004-04-11 09:58:34', 'wk', '2004-04-11 09:58:34', NULL);
INSERT INTO infobasar_text VALUES (113, 1, 'w', '!Startseite[Newline](Allgemein)\r\n!! meine Links\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n', '2004-04-11 11:08:31', 'wk', '2004-04-11 11:08:31', NULL);
INSERT INTO infobasar_text VALUES (1, 1, 'w', '!Startseite\r\n', '2004-04-08 22:58:54', 'adam', '2004-04-08 22:59:59', NULL);
INSERT INTO infobasar_text VALUES (2, 2, 'w', '!Hilfe', '2004-04-08 22:59:59', 'Adam', '2004-04-08 23:00:51', NULL);
INSERT INTO infobasar_text VALUES (108, 1, 'w', '!Startseite\r\nNochLeer\r\n\r\n', '2004-04-11 01:30:41', '2004-04-11 01:30:41', '0000-00-00 00:00:00', NULL);
INSERT INTO infobasar_text VALUES (118, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n\r\n\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '2004-04-11 11:54:08', 'wk', '2004-04-11 11:54:08', NULL);
INSERT INTO infobasar_text VALUES (119, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#[http://localhost/Logo3d.jpg Bild]\r\n\r\n\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '2004-04-11 12:17:19', 'wk', '2004-04-11 12:17:19', NULL);

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `infobasar_user`
#

DROP TABLE IF EXISTS infobasar_user;
CREATE TABLE infobasar_user (
  id int(11) NOT NULL auto_increment,
  createdat datetime default NULL,
  changedat datetime NOT NULL default '0000-00-00 00:00:00',
  name varchar(128) default NULL,
  code varchar(64) default NULL,
  email varchar(128) default NULL,
  locked char(1) default NULL,
  rights varchar(255) default NULL,
  width int(11) NOT NULL default '70',
  height int(11) NOT NULL default '20',
  maxhits int(11) NOT NULL default '30',
  postingsperpage int(11) NOT NULL default '10',
  theme int(11) NOT NULL default '10',
  postings int(11) NOT NULL default '0',
  avatar varchar(128) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Daten für Tabelle `infobasar_user`
#

INSERT INTO infobasar_user VALUES (1, '2004-03-12 20:20:57', '2004-03-17 23:01:26', 'admin', 'QaDWWvaa6uTkw', NULL, 'n', ':all:uadd:umod:udel:', 70, 20, 30, 10, 10, 0, NULL);
INSERT INTO infobasar_user VALUES (2, '2004-03-15 23:09:23', '2004-04-13 21:32:03', 'wk', 'QaDWWvaa6uTkw', NULL, 'n', ':all:', 81, 26, 31, 2, 10, 8, NULL);
