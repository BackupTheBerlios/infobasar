# phpMyAdmin SQL Dump
# version 2.5.5-rc1
# http://www.phpmyadmin.net
#
# Host: localhost
# Erstellungszeit: 24. April 2004 um 00:31
# Server Version: 4.0.17
# PHP-Version: 4.3.3
# 
# Datenbank: `infobasar`
# 
CREATE DATABASE `infobasar`;
USE infobasar;

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
) TYPE=MyISAM AUTO_INCREMENT=1 ;

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
) TYPE=MyISAM AUTO_INCREMENT=4 ;

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
) TYPE=MyISAM AUTO_INCREMENT=5 ;

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
) TYPE=MyISAM AUTO_INCREMENT=12 ;

#
# Daten für Tabelle `infobasar_page`
#

INSERT INTO infobasar_page VALUES (1, 'StartSeite', 'w', NULL, '2004-04-09 21:56:48', 0, 0);
INSERT INTO infobasar_page VALUES (2, 'HilfeAendern', 'w', NULL, '0000-00-00 00:00:00', 1, 3);
INSERT INTO infobasar_page VALUES (3, 'MeineSeite', 'w', '2004-03-30 21:11:41', '2004-03-30 23:14:10', 0, 0);
INSERT INTO infobasar_page VALUES (4, 'HtmlDemo', 'h', '2004-03-30 23:16:20', '0000-00-00 00:00:00', 0, 0);
INSERT INTO infobasar_page VALUES (5, 'Wiki', 'w', '2004-04-22 02:05:24', '0000-00-00 00:00:00', 0, 0);
INSERT INTO infobasar_page VALUES (6, 'HilfeFormatierungen', 'w', '2004-04-22 02:10:12', '0000-00-00 00:00:00', 0, 0);
INSERT INTO infobasar_page VALUES (7, 'HilfeFormatierungImAbsatz', 'w', '2004-04-22 02:11:32', '0000-00-00 00:00:00', 0, 0);
INSERT INTO infobasar_page VALUES (8, 'HilfeAbsatzFormate', 'w', '2004-04-22 02:12:43', '0000-00-00 00:00:00', 0, 0);
INSERT INTO infobasar_page VALUES (9, 'HilfeVerweise', 'w', '2004-04-22 02:15:05', '0000-00-00 00:00:00', 0, 0);
INSERT INTO infobasar_page VALUES (10, 'KategorieHilfe', 'w', '2004-04-23 00:59:02', '2004-04-23 00:59:02', 0, 0);
INSERT INTO infobasar_page VALUES (11, 'SandKiste', 'w', '2004-04-24 00:16:27', '2004-04-24 00:16:27', 0, 0);

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
) TYPE=MyISAM AUTO_INCREMENT=24 ;

#
# Daten für Tabelle `infobasar_param`
#

INSERT INTO infobasar_param VALUES (1, 'Wiki-Seiten-Head-Abschnitt', 10, 101, '<head><title>[PageName] (Wiki)</title></head>');
INSERT INTO infobasar_param VALUES (2, 'Wiki-Seiten Body-Abschnitt-Anfang', 10, 102, '<body>\r\n<h1>[PageName]</h1>\r\n');
INSERT INTO infobasar_param VALUES (3, 'Wiki-Seiten Body-Abschnitt-Ende', 10, 103, '<hr style="width: 100%; height: 2px;">\r\nLetzte Änderung: [PageChangedAt]  [PageChangedBy] <br>\r\n<a href="[PageName]?action=edit">Ändern</a>\r\n | <a href="!search">Suchen</a>\r\n | <a href="[PageName]?action=pageinfo">Info</a>\r\n | <a href="!start">Startseite</a>\r\n| <a href="!account">Einstellungen f&uuml;r [User]</a>\r\n</body>\r\n');
INSERT INTO infobasar_param VALUES (4, 'Wiki-Änderung Head-Abschnitt', 10, 104, '<head><title>&Auml;nderung von [PageName]</title></head>');
INSERT INTO infobasar_param VALUES (5, 'Wiki-Änderung Body-Abschnitt-Anfang', 10, 105, '<body>\r\n<h1>&Auml;nderung von [PageName]</h1>');
INSERT INTO infobasar_param VALUES (6, 'Wiki-Änderung Body-Abschnitt-Ende', 10, 106, '<small>Textformatierungsregeln:</small>\r\n<table border="1">\r\n<tr><td><small>Zeilenanfang</small></td><td><small>Im Text</small></td><td><small>Links</small></td></tr>\r\n<tr><td><small>! &Uuml;berschrift<br>* Aufz&auml;hlung<br># num. Aufz&auml;hlung<br>---- Linie (4 -)</small></td>\r\n<td><small>\'\'<i>Zitat</i>\'\' (je 2 mal \')<br>\'\'\'<b>wichtig</b>\'\'\' (je 3 mal \')<br>__<u>unterstrichen</u>__ (je 2 mal _)<br> [Newline] Zeilenwechsel</small></td>\r\n<td><small>WikiName<br/> !GmbH<br/>[URL]<br>[URL Text]</small></td>\r\n</tr></table>\r\n</body>\r\n\r\n');
INSERT INTO infobasar_param VALUES (7, 'HTML-Seiten-Head-Abschnitt', 10, 111, '<head><title>[PageName] (HTML)</title></head>');
INSERT INTO infobasar_param VALUES (8, 'HTML-Seiten Body-Abschnitt-Anfang', 10, 112, '<body>\r\n<h1>[PageName]</h1>');
INSERT INTO infobasar_param VALUES (9, 'HTML-Seiten Body-Abschnitt-Ende', 10, 113, '<hr style="width: 100%; height: 2px;">\r\nLetzte &Auml;nderung: [PageChangedAt]  [PageChangedBy] <br>\r\n<a href="[PageName]?action=edit">&Auml;ndern</a>\r\n | <a href="!search">Suchen</a>\r\n</body>\r\n');
INSERT INTO infobasar_param VALUES (10, 'HTML-Änderung Head-Abschnitt', 10, 114, '<head><title>&Auml;nderung von [PageName]</title></head>');
INSERT INTO infobasar_param VALUES (11, 'HTML-Änderung Body-Abschnitt-Anfang', 10, 115, '<body>\r\n<h1>&Auml;nderung von [PageName]</h1>');
INSERT INTO infobasar_param VALUES (12, 'HTML-Änderung Body-Abschnitt-Ende', 10, 116, '</body>');
INSERT INTO infobasar_param VALUES (13, 'Beitrag-Head-Abschnitt', 10, 121, '<head><title>Forumsbeitr&auml;ge</title></head>\r\n');
INSERT INTO infobasar_param VALUES (14, 'Forumsbeiträge-Body-Abschnitt-Anfang', 10, 122, '<body>\r\n');
INSERT INTO infobasar_param VALUES (15, 'ForumsbeiträgeBody-Abschnitt-Ende', 10, 123, '</body>\r\n');
INSERT INTO infobasar_param VALUES (16, 'Neuer-Beitrag-Head-Abschnitt', 10, 124, '<head><title>Neues Thema</title></head>\r\n');
INSERT INTO infobasar_param VALUES (17, 'Neues-Thema-Body-Abschnitt-Anfang', 10, 125, '<body>\r\n');
INSERT INTO infobasar_param VALUES (18, 'ForumsbeiträgeBody-Abschnitt-Ende', 10, 126, '</body>\r\n');
INSERT INTO infobasar_param VALUES (19, 'Forumantwort-Head-Abschnitt', 10, 127, '<head><title>Antwort erstellen</title></head>\r\n');
INSERT INTO infobasar_param VALUES (20, 'Forenantwort-Body-Abschnitt-Anfang', 10, 128, '<body>\r\n');
INSERT INTO infobasar_param VALUES (21, 'Forenantwort-Body-Abschnitt-Ende', 10, 129, '</body>\r\n');
INSERT INTO infobasar_param VALUES (22, 'Login-Head-Bodystart', 10, 131, '<head>\r\n<title>Anmeldung fu&uml;r den InfoBasar</title>\r\n<body>\r\n<h1>Willkommen beim Infobasar der TDH-Arbeitsgruppe M&uuml;nchen</h1>');
INSERT INTO infobasar_param VALUES (23, 'Login-Body-End', 10, 132, '<p><small>Passwort vergessen? EMail an w.kappeler AT gmx DOT de</small></p>\r\n</body>');

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
) TYPE=MyISAM AUTO_INCREMENT=29 ;

#
# Daten für Tabelle `infobasar_posting`
#

INSERT INTO infobasar_posting VALUES (1, '2004-04-12 01:18:47', '2004-04-13 21:09:50', 0, 1, 'wk', NULL, NULL, 'Skunk', 'fdafdafda\r\n\r\n', 10);
INSERT INTO infobasar_posting VALUES (2, '2004-04-12 01:19:29', '2004-04-12 01:19:29', NULL, 1, 'kawi', NULL, NULL, 'a', 'fdafdafda\r\n', 0);
INSERT INTO infobasar_posting VALUES (3, '2004-04-12 01:20:27', '2004-04-12 01:20:27', NULL, 1, 'adam', NULL, NULL, 'a', 'fjdklafjdkla', 0);
INSERT INTO infobasar_posting VALUES (4, '2004-04-12 02:15:39', '2004-04-12 02:15:39', NULL, 1, 'wk', NULL, NULL, 'Was können wir tun?', 'Wieder stellt sich die Frage:\r\n\r\nWas können wir tun?\r\n\r\n', 3);
INSERT INTO infobasar_posting VALUES (5, '2004-04-12 02:15:59', '2004-04-13 21:07:16', 0, 1, 'wk', NULL, NULL, 'Was kannst Du tun?', 'Wieder stellt sich die Frage:\r\n\r\nWas können wir tun?\r\n\r\nKeine Ahnung!\r\n\r\n\r\n\r\n\r\n', 73);
INSERT INTO infobasar_posting VALUES (6, '2004-04-13 12:58:38', '2004-04-13 21:06:32', 0, 1, 'wk', NULL, NULL, 'Re: Reh', 'Leider steht hier gar nichts!\r\n\r\n', 2);
INSERT INTO infobasar_posting VALUES (7, '2004-04-13 13:09:46', '2004-04-13 21:06:14', 0, 1, 'wk', NULL, NULL, 'Re: Hase', 'Leider steht hier gar nichts!\r\n\r\n\r\n', 4);
INSERT INTO infobasar_posting VALUES (8, '2004-04-13 13:15:30', '2004-04-13 13:15:30', NULL, 1, 'wk', 5, NULL, 'Re: Was können wir tun?', 'Eigentlich gar nichts!\r\n', 0);
INSERT INTO infobasar_posting VALUES (9, '2004-04-13 21:25:03', '2004-04-13 21:25:03', NULL, 1, 'wk', 5, NULL, 'Re: Re: Was können wir tun?', 'Fangen wir einfach an!\r\n', 0);
INSERT INTO infobasar_posting VALUES (10, '2004-04-13 21:25:16', '2004-04-13 21:25:16', NULL, 1, 'wk', 5, NULL, 'Re: Re: Was können wir tun?', 'Fangen wir einfach an!\r\n', 0);
INSERT INTO infobasar_posting VALUES (11, '2004-04-13 23:50:33', '2004-04-13 23:50:33', NULL, 1, 'wk', 5, NULL, 'Re: Was können wir tun?', 'Reden, reden, reden\r\n\r\n\r\n', 0);
INSERT INTO infobasar_posting VALUES (12, '2004-04-13 23:51:23', '2004-04-13 23:51:23', NULL, 1, 'wk', 5, NULL, 'Handeln!', 'Handeln, handeln, handeln!\r\n', 0);
INSERT INTO infobasar_posting VALUES (13, '2004-04-13 23:52:25', '2004-04-13 23:52:25', NULL, 1, 'wk', NULL, NULL, 'Musik', 'Wie tönts?\r\n\r\n', 9);
INSERT INTO infobasar_posting VALUES (14, '2004-04-13 23:52:48', '2004-04-13 23:52:48', NULL, 1, 'wk', 13, NULL, 'Re: Musik', 'Laut!', 0);
INSERT INTO infobasar_posting VALUES (15, '2004-04-15 23:35:26', '2004-04-15 23:35:26', NULL, 1, 'wk', 5, NULL, 'Re: Handeln!', '\'\'Zitat\'\' und \'\'\'fett\'\'\'\r\n\r\nNeuer Absatz\r\n* Punkt 1\r\n* Punkt 2\r\n', 0);
INSERT INTO infobasar_posting VALUES (16, '0000-00-00 00:00:00', '2004-04-18 00:33:53', 0, 1, 'admin', NULL, NULL, 'x', 'fdfd\r\nfdsa\r\n\r\n', 3);
INSERT INTO infobasar_posting VALUES (17, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, 1, 'admin', NULL, NULL, 'y', 'leer1\r\n', 0);
INSERT INTO infobasar_posting VALUES (18, '2004-04-18 00:37:34', '2004-04-18 00:44:55', 0, 1, 'admin', NULL, NULL, 'y ist zu wenig!', 'leer1\r\n', 2);
INSERT INTO infobasar_posting VALUES (19, '2004-04-18 00:39:42', '2004-04-18 00:44:08', 0, 1, 'admin', NULL, NULL, 'Das war y', 'leer1\r\n\r\n', 14);
INSERT INTO infobasar_posting VALUES (20, '2004-04-18 00:40:14', '2004-04-18 00:40:14', NULL, 1, 'admin', 19, NULL, 'Re: y', 'na, na, na\r\n', 0);
INSERT INTO infobasar_posting VALUES (21, '2004-04-18 00:44:22', '2004-04-18 00:44:22', NULL, 1, 'admin', 19, NULL, 'Re: Das war y', 'Jetzt stimmts!', 0);
INSERT INTO infobasar_posting VALUES (22, '2004-04-21 21:41:41', '2004-04-21 21:42:11', 0, 1, 'wk', NULL, NULL, 'Sehr interessant', 'Glaub ich wenigstens.\r\nSo sehe ich das\r\n\r\n', 2);
INSERT INTO infobasar_posting VALUES (23, '2004-04-21 21:44:52', '2004-04-21 21:44:52', NULL, 1, 'wk', 22, NULL, 'Re: Sehr interessant...Stimmt!', 'Ganz meine Meinung', 0);
INSERT INTO infobasar_posting VALUES (24, '2004-04-21 21:45:58', '2004-04-21 21:45:58', NULL, 1, 'wk', NULL, NULL, 'Bla Bla Bla', 'Bla Bla Bla', 5);
INSERT INTO infobasar_posting VALUES (25, '2004-04-21 21:58:00', '2004-04-21 21:58:00', NULL, 1, 'wk', NULL, NULL, 'Bla Bla Bla', 'Bla Bla Bla', 0);
INSERT INTO infobasar_posting VALUES (26, '2004-04-23 00:23:25', '2004-04-23 00:23:25', NULL, 2, 'wk', NULL, NULL, 'Sport ist Mord', 'Reiten macht die Pferde krank', 8);
INSERT INTO infobasar_posting VALUES (27, '2004-04-23 00:23:43', '2004-04-23 00:23:43', NULL, 2, 'wk', 26, NULL, 'Re: Sport ist Mord', 'Dann lass es halt!', 0);
INSERT INTO infobasar_posting VALUES (28, '2004-04-23 00:24:29', '2004-04-23 00:24:29', NULL, 2, 'wk', 26, NULL, 'Re: Sport ist Mord', 'Aber ich zahl für die anderen!', 0);

# --------------------------------------------------------

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
) TYPE=MyISAM AUTO_INCREMENT=175 ;

#
# Daten für Tabelle `infobasar_text`
#

INSERT INTO infobasar_text VALUES (117, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n\r\n\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '2004-04-11 11:54:04', 'wk', '2004-04-11 11:54:04', NULL);
INSERT INTO infobasar_text VALUES (115, 1, 'w', '!Startseite[Newline](Allgemein)\r\n!! meine Links\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n', '2004-04-11 11:43:25', 'wk', '2004-04-11 11:43:25', NULL);
INSERT INTO infobasar_text VALUES (114, 1, 'w', '!Startseite[Newline](Allgemein)\r\n!! meine Links\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n', '2004-04-11 11:43:11', 'wk', '2004-04-11 11:43:11', NULL);
INSERT INTO infobasar_text VALUES (116, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n\r\n\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '2004-04-11 11:53:00', 'wk', '2004-04-11 11:53:00', NULL);
INSERT INTO infobasar_text VALUES (3, 3, 'w', '! Überschrift\r\nSandKiste\r\nhttp://www.heise.de', '2004-04-08 22:51:54', 'wk', '2004-04-08 22:54:16', NULL);
INSERT INTO infobasar_text VALUES (4, 4, 'h', '<h2>Demo einer HTML-Seite<h2>\r\n<p>Dies ist ein Absatz</p>', '2004-04-08 22:54:16', 'wk', '2004-04-18 00:08:44', 151);
INSERT INTO infobasar_text VALUES (109, 1, 'w', '!Startseite\r\n!! meine Links\r\n----\r\nKategorieOrdnung\r\n', '2004-04-11 09:55:10', 'wk', '2004-04-11 09:55:10', NULL);
INSERT INTO infobasar_text VALUES (110, 1, 'w', '!Startseite\r\n!! meine Links\r\n----\r\nKategorieOrdnung\r\n', '2004-04-11 09:55:53', 'wk', '2004-04-11 09:55:53', NULL);
INSERT INTO infobasar_text VALUES (111, 1, 'w', '!Startseite\r\n!! meine Links\r\n----\r\nKategorieOrdnung\r\n', '2004-04-11 09:58:32', 'wk', '2004-04-11 09:58:32', NULL);
INSERT INTO infobasar_text VALUES (112, 1, 'w', '!Startseite\r\n!! meine Links\r\n----\r\nKategorieOrdnung\r\n', '2004-04-11 09:58:34', 'wk', '2004-04-11 09:58:34', NULL);
INSERT INTO infobasar_text VALUES (113, 1, 'w', '!Startseite[Newline](Allgemein)\r\n!! meine Links\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n', '2004-04-11 11:08:31', 'wk', '2004-04-11 11:08:31', NULL);
INSERT INTO infobasar_text VALUES (1, 1, 'w', '!Startseite\r\n', '2004-04-08 22:58:54', 'adam', '2004-04-08 22:59:59', NULL);
INSERT INTO infobasar_text VALUES (2, 2, 'w', '!Hilfe', '2004-04-08 22:59:59', 'Adam', '2004-04-08 23:00:51', NULL);
INSERT INTO infobasar_text VALUES (108, 1, 'w', '!Startseite\r\nNochLeer\r\n\r\n', '2004-04-11 01:30:41', '2004-04-11 01:30:41', '0000-00-00 00:00:00', NULL);
INSERT INTO infobasar_text VALUES (118, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n\r\n\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '2004-04-11 11:54:08', 'wk', '2004-04-11 11:54:08', NULL);
INSERT INTO infobasar_text VALUES (119, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#[http://localhost/Logo3d.jpg Bild]\r\n\r\n\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '2004-04-11 12:17:19', 'wk', '2004-04-16 00:23:53', 120);
INSERT INTO infobasar_text VALUES (120, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#[http://localhost/Logo3d.jpg Bild]\r\n\r\n\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '2004-04-16 00:23:53', 'wk', '2004-04-16 00:24:05', 121);
INSERT INTO infobasar_text VALUES (121, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#[http://localhost/Logo3d.jpg Bild]\r\n\r\n\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '2004-04-16 00:24:05', 'wk', '2004-04-16 00:26:11', 122);
INSERT INTO infobasar_text VALUES (122, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#[http://localhost/Logo3d.jpg Bild]\r\n\r\n\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '2004-04-16 00:26:11', 'wk', '2004-04-16 00:28:09', 123);
INSERT INTO infobasar_text VALUES (123, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#[http://localhost/Logo3d.jpg Bild]\r\n\r\n\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '2004-04-16 00:28:09', 'wk', '2004-04-16 00:28:53', 124);
INSERT INTO infobasar_text VALUES (124, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#[http://localhost/Logo3d.jpg Bild]\r\n\r\n\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '2004-04-16 00:28:53', 'wk', '2004-04-16 00:28:59', 125);
INSERT INTO infobasar_text VALUES (125, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#[http://localhost/Logo3d.jpg Bild]\r\n\r\n\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '2004-04-16 00:28:59', 'wk', '2004-04-16 00:29:01', 126);
INSERT INTO infobasar_text VALUES (126, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#[http://localhost/Logo3d.jpg Bild]\r\n\r\n\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '2004-04-16 00:29:01', 'wk', '2004-04-16 00:29:54', 127);
INSERT INTO infobasar_text VALUES (127, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#[http://localhost/Logo3d.jpg Bild]\r\n\r\n\r\n--------\r\nKategorieOrdnung\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '2004-04-16 00:29:54', 'wk', '2004-04-16 00:30:52', 128);
INSERT INTO infobasar_text VALUES (128, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#[http://home.arcor.de/bewpferde/Pic/Logo3d.jpg Bild]\r\n\r\n\r\n--------\r\nKategorieOrdnung\r\n', '2004-04-16 00:30:52', 'wk', '2004-04-16 00:32:13', 129);
INSERT INTO infobasar_text VALUES (129, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#[http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\n\r\n\r\n--------\r\nKategorieOrdnung\r\n', '2004-04-16 00:32:13', 'wk', '2004-04-16 01:20:41', 130);
INSERT INTO infobasar_text VALUES (130, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#[http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett!\r\n\r\n--------\r\nKategorieOrdnung\r\n\r\n', '2004-04-16 01:20:41', 'wk', '2004-04-16 01:21:11', 131);
INSERT INTO infobasar_text VALUES (131, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#[http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett!\r\n\r\n--------\r\nKategorieOrdnung\r\n', '2004-04-16 01:21:11', 'wk', '2004-04-16 01:26:51', 132);
INSERT INTO infobasar_text VALUES (132, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#Hier gibt es ein Bild: [http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett, oder!\r\n\r\n--------\r\nKategorieOrdnung\r\n', '2004-04-16 01:26:51', 'wk', '2004-04-17 23:03:10', 133);
INSERT INTO infobasar_text VALUES (133, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#Hier gibt es ein Bild: [http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett, oder!\r\nStimmt\r\n--------\r\nKategorieOrdnung\r\n\r\n', '2004-04-17 23:03:10', 'admin', '2004-04-17 23:05:48', 134);
INSERT INTO infobasar_text VALUES (134, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#Hier gibt es ein Bild: [http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett, oder!\r\nStimmt\r\n--------\r\nKategorieOrdnung\r\n\r\n', '2004-04-17 23:05:48', 'admin', '2004-04-17 23:28:30', 135);
INSERT INTO infobasar_text VALUES (135, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#Hier gibt es ein Bild: [http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett, oder!\r\nStimmt!\r\n--------\r\nKategorieOrdnung\r\n', '2004-04-17 23:28:30', 'admin', '2004-04-17 23:29:21', 136);
INSERT INTO infobasar_text VALUES (136, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#Hier gibt es ein Bild: [http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett, oder!\r\nStimmt!!\r\n--------\r\nKategorieOrdnung\r\n\r\n', '2004-04-17 23:29:21', 'admin', '2004-04-17 23:33:16', 137);
INSERT INTO infobasar_text VALUES (137, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#Hier gibt es ein Bild: [http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett, oder!\r\nStimmt!!\r\n--------\r\nKategorieOrdnung\r\n\r\n', '2004-04-17 23:33:16', 'admin', '2004-04-17 23:36:46', 138);
INSERT INTO infobasar_text VALUES (138, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#Hier gibt es ein Bild: [http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett, oder!\r\nStimmt!!\r\n--------\r\nKategorieOrdnung\r\n\r\n', '2004-04-17 23:36:46', 'admin', '2004-04-17 23:37:16', 139);
INSERT INTO infobasar_text VALUES (139, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#Hier gibt es ein Bild: [http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett, oder!\r\nStimmt!!\r\n--------\r\nKategorieOrdnung\r\n\r\n', '2004-04-17 23:37:16', 'admin', '2004-04-17 23:37:51', 140);
INSERT INTO infobasar_text VALUES (140, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#Hier gibt es ein Bild: [http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett, oder!\r\nStimmt!!\r\n--------\r\nKategorieOrdnung\r\n\r\n', '2004-04-17 23:37:51', 'admin', '2004-04-17 23:37:54', 141);
INSERT INTO infobasar_text VALUES (141, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#Hier gibt es ein Bild: [http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett, oder!\r\nStimmt!!\r\n--------\r\nKategorieOrdnung\r\n\r\n', '2004-04-17 23:37:54', 'admin', '2004-04-17 23:38:32', 142);
INSERT INTO infobasar_text VALUES (142, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#Hier gibt es ein Bild: [http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett, oder!\r\nStimmt!!\r\n--------\r\nKategorieOrdnung\r\n\r\n', '2004-04-17 23:38:32', 'admin', '2004-04-17 23:41:07', 143);
INSERT INTO infobasar_text VALUES (143, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#Hier gibt es ein Bild: [http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett, oder!\r\nStimmt!!\r\n--------\r\nKategorieOrdnung\r\n\r\n', '2004-04-17 23:41:07', 'admin', '2004-04-17 23:41:40', 144);
INSERT INTO infobasar_text VALUES (144, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#Hier gibt es ein Bild: [http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett, oder!\r\nStimmt!!\r\n--------\r\nKategorieOrdnung\r\n\r\n', '2004-04-17 23:41:40', 'admin', '2004-04-17 23:42:07', 145);
INSERT INTO infobasar_text VALUES (145, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#Hier gibt es ein Bild: [http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett, oder!\r\nStimmt!!\r\n--------\r\nKategorieOrdnung\r\n\r\n', '2004-04-17 23:42:07', 'admin', '2004-04-17 23:43:12', 146);
INSERT INTO infobasar_text VALUES (146, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#Hier gibt es ein Bild: [http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett, oder!\r\nStimmt!!\r\n--------\r\nKategorieOrdnung\r\n\r\n', '2004-04-17 23:43:12', 'admin', '2004-04-17 23:44:14', 147);
INSERT INTO infobasar_text VALUES (147, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#Hier gibt es ein Bild: [http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett, oder!\r\nStimmt!!\r\n--------\r\nKategorieOrdnung\r\n', '2004-04-17 23:44:14', 'admin', '2004-04-17 23:45:51', 148);
INSERT INTO infobasar_text VALUES (148, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#Hier gibt es ein Bild: [http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett, oder!\r\nStimmt!!\r\n--------\r\nKategorieOrdnung\r\n', '2004-04-17 23:45:51', 'admin', '2004-04-17 23:48:15', 149);
INSERT INTO infobasar_text VALUES (149, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#Hier gibt es ein Bild: [http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett, oder!\r\nStimmt!!\r\n--------\r\nKategorieOrdnung\r\n', '2004-04-17 23:48:15', 'admin', '2004-04-17 23:50:15', 150);
INSERT INTO infobasar_text VALUES (150, 1, 'w', '! Inhalt\r\n!! meine Links\r\n!!! Rest\r\n!Formatierungen\r\nEs gibt:\r\n* Aufzählung\r\n* num. Aufzählung\r\n!! Links\r\n#WikiNamen\r\n#[Wiki Dies ist ein Seite namens Wiki]\r\n#http://www.heise.de\r\n#Hier gibt es ein Bild: [http://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg Bild]\r\nGanz nett, oder!\r\nStimmt!!!\r\n--------\r\nKategorieOrdnung\r\n\r\n', '2004-04-17 23:50:15', 'admin', '2004-04-22 02:04:51', 155);
INSERT INTO infobasar_text VALUES (151, 4, 'h', '<h2>Demo einer HTML-Seite<h2>\r\n<p>Dies ist ein Absatz</p>\r\n', '2004-04-18 00:08:44', 'admin', '2004-04-18 00:09:22', 152);
INSERT INTO infobasar_text VALUES (152, 4, 'h', '<h2>Demo einer HTML-Seite<h2>\r\n<p>Dies ist ein Absatz</p>\r\n', '2004-04-18 00:09:22', 'admin', '2004-04-18 00:09:26', 153);
INSERT INTO infobasar_text VALUES (153, 4, 'h', '<h2>Demo einer HTML-Seite<h2>\r\n<p>Dies ist ein Absatz</p>\r\n', '2004-04-18 00:09:26', 'admin', '2004-04-18 00:09:48', 154);
INSERT INTO infobasar_text VALUES (154, 4, 'h', '<h2>Demo einer HTML-Seite<h2>\r\n<p>Dies ist ein Absatz</p>\r\nText ohne Absatz\r\n\r\n', '2004-04-18 00:09:48', 'admin', '2004-04-18 00:09:48', NULL);
INSERT INTO infobasar_text VALUES (155, 1, 'w', '! Wo finde ich was?\r\n\r\n* Was ist ein [Wiki]\r\n* HilfeFormatierungen\r\n* [http:!home Übersicht]\r\n* [http:!forumhome Foren-Übersicht]\r\n\r\n--------\r\nKategorieOrdnung', '2004-04-22 02:04:51', 'wk', '2004-04-24 00:13:50', 172);
INSERT INTO infobasar_text VALUES (156, 5, '', 'Wiki ist die Abkürzung für Wiki-Wiki und stellt eine Interaktionsform im Internet dar:\r\n\r\n! Grundprinzip\r\n\r\nJeder darf zum Wissensschatz eines Wikis beitragen\r\n\r\n!!Erster Einwand:\r\n\r\nAber wenn jemand was kaputtmacht?\r\n\r\n!!!Antwort:\r\n\r\nEin Wiki vergisst nichts. Ist also mal was absichtlich oder unabsichtlich "kaputtgemacht", so kann man jede vorige Version wiederherstellen.\r\n\r\n!! Zweiter Einwand:\r\nUnd wenn die Zerstörung unbemerkt bleibt?\r\n\r\n!!! Antwort:\r\n* Ein Wiki bietet die Möglichkeit, sich die geänderten Seiten anzeigen zu lassen. Damit kann \'\'\'jeder Leser\'\'\' Korrekturen anbringen.\r\n* Man kann sich die Unterschiede von 2 Versionen anzeigen lassen: Damit gehen kleine Änderungen nicht unter, auch wenn die Seite sehr groß ist.\r\n\r\n!Was bedeutet der name Wiki-Wiki?\r\nWiki Wiki kommt aus dem Hawaianischen und bedeutet schnell, schnell.\r\n\r\n\r\n!Was zeichnet ein Wiki aus:\r\n* Einfache [HilfeFormatierungen Formatierungsmöglichkeiten]\r\n* Jede normale Seite kann von jedem geändert werden\r\n* Bei Änderungen bleiben die vorigen Versionen gespeichert. Diese können wiederhergestellt werden.\r\n* Es können ganz einfach neue Seiten erstellt werden: Auf einer bestehenden Seite wird einfach der Name der neuen Seite (normalerweise ein Wiki-Name) eingetragen. Wird die Seite gespeichert, so erscheint vor dem Wiki-Namen ein ?. Dies sagt aus, dass diese Seite noch nicht existiert. Klickt man auf den Verweis, so wird diese Seite neu angelegt.\r\n----\r\nKategorieHilfe', NULL, 'wk', '2004-04-23 00:58:18', 170);
INSERT INTO infobasar_text VALUES (157, 6, '', 'Es gibt folgende Formatierungsmöglichkeiten im Wiki:\r\n* [HilfeFormatierungImAbsatz] (Betonung, Zitat ...)\r\n* [HilfeAbsatzFormate] (Überschriften, Aufzählungen, Tabellen)\r\n* HilfeVerweise (Wiki-Namen, Externe Links, Bilder...)\r\n----\r\nKategorieHilfe', NULL, 'wk', '2004-04-22 02:14:10', 160);
INSERT INTO infobasar_text VALUES (158, 7, '', 'Ein Wiki bietet einfach zu bedienende Formatierungsmöglichkeiten:\r\n\r\n! Zitat\r\nEin Zitat wird in doppelte einfache Apostrophe (auf der Tastatur neben dem Ä, über dem #) eingeschlossen:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nEr sprach von \'[\']hochgradigem Blödsinn[\']\'.\r\n\r\nergibt\r\n\r\nEr sprach von \'\'hochgradigem Blödsinn\'\'.\r\n! Betonung\r\nEine Betonung wird in  doppelte einfache Apostrophe (auf der Tastatur neben dem Ä, über dem #) eingeschlossen:\r\n\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nEine \'[\']\'echte\'[\']\' Qualifikation ist nachzuweisen.\r\n\r\nergibt\r\n\r\nEine \'\'\'echte\'\'\' Qualifikation ist nachzuweisen.\r\n\r\n!! Absatz\r\nEin Absatz wird durch eine Leerzeile beendet.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nErster Teil des Absatzes in Zeile1,[Newline]\r\nzweiter Teil in Zeile 2\r\n\r\nwird zu\r\n\r\nErster Teil des Absatzes in Zeile1, zweiter Teil in Zeile 2\r\n\r\n! Unterstreichung:\r\nEine Unterstreichung wird mit zwei Unterstrichen eingerahmt:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nIch weise  _[_]besonders[_]_ darauf hin.\r\n\r\nwird zu\r\n\r\nIch weise  __besonders__ darauf hin.\r\n\r\n\r\n! Sonstiges\r\nSoll ein Sonderzeichen nicht seine Sonderfunktion annehmen, so wird es einfach in eckige Klammern gesetzt:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nHier kommen 2 _[[]_[]], die aber keine Unterstreichung bewirken sollen.\r\n\r\nwird zu:\r\n\r\nHier kommen 2 _[_], die aber keine Unterstreichung bewirken sollen.\r\n----\r\nKategorieHilfe', NULL, 'wk', '2004-04-23 00:56:11', 169);
INSERT INTO infobasar_text VALUES (159, 8, '', 'Absatzformate werden durch ein bestimmtes Zeichen am Zeilenanfang eingeleitet\r\n!! Überschrift\r\nEine Überschrift wird mit einem \'!\' am Zeilenanfang erzeugt. Je mehr \'!\', um so höher der Grad der Überschrift:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[!] Hauptüberschrift\r\n\r\n[!]! Überschrift 2. Grades\r\n\r\n[!]!! Überschrift 3. Grades\r\n\r\nwird zu\r\n\r\n! Hauptüberschrift\r\n!! Überschrift 2. Grades\r\n!!! Überschrift 3. Grades\r\nusw.\r\n!Horizontale Linie\r\nBeginnt eine Zeile mit mindestens 4 - (Minus), wird eine horizontale Linie gezeichnet. Je mehr -, um so dicker.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[-]---\r\n\r\n[-]-------\r\n\r\nergibt\r\n----\r\n----------\r\n\r\n\r\n! Aufzählung\r\nSteht als erstes Zeichen in der Zeile ein *, wird die Zeile als Teil einer Aufzählung verstanden:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[*] Nordpferde\r\n\r\n[*] Südpferde\r\n\r\nergibt\r\n* Nordpferde\r\n* Südpferde\r\n! Nummerierte Aufzählung\r\nBei nummerierten Aufzählungen ist das erste Zeichen ein #.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[#] Nordpferde\r\n\r\n[#] Südpferde\r\n\r\nergibt\r\n# Nordpferde\r\n# Südpferde\r\n\r\n! Tabellen\r\nEine Tabelle wird mit einem | (links neben dem Y auf der Tastatur) eingeleitet.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[|]Platz|Name|Pferd\r\n\r\n[|]1|Deppisch|Tiny Boy\r\n\r\nergibt\r\n|Platz|Name|Pferd\r\n|1|Deppisch|Tiny Boy\r\n\r\n----\r\nKategorieHilfe', NULL, 'wk', '2004-04-22 02:45:47', 162);
INSERT INTO infobasar_text VALUES (160, 6, '', 'Es gibt folgende Formatierungsmöglichkeiten im Wiki:\r\n* [HilfeFormatierungImAbsatz] (\'\'\'Betonung\'\'\', \'\'Zitat\'\', __unterstrichen__...)\r\n* [HilfeAbsatzFormate] (Überschriften, Aufzählungen, Tabellen)\r\n* HilfeVerweise (Wiki-Namen, Externe Links, Bilder...)\r\n----\r\nKategorieHilfe', '2004-04-22 02:14:10', 'wk', '2004-04-22 02:14:10', NULL);
INSERT INTO infobasar_text VALUES (161, 9, '', '! Wiki-Namen\r\nWiki-Namen sind die einfachste Möglichkeit eines Verweises: Jedes Wort, das innerhalb des Wortes einen oder mehrere Großbuchstaben enthält, ist ein Wiki-Wort. Ein Wiki-Wort benennt eine Seite: Wird ein Wiki-Name im Text geschrieben, so ist dies automatisch ein Verweis auf die Seite dieses Namens.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nStartSeite\r\n\r\nHinweis:\r\nSoll ein Wort mit mehreren Großbuchstaben kein Wiki-Namen sein, so wird ein ! vorangestellt:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nDies ist eine !!GmbH\r\n\r\nwird zu\r\n\r\nDies ist eine !GmbH\r\n\r\n!Externe Links\r\nWird eine korrekte URL im Text eingetragen, so wird daraus automatisch ein Verweis:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nhttp://www.bewegendepferde.de\r\n\r\nSoll ein Verweis einen anderen Text haben, so gilt die Formatierung:\r\n[[]Verweis Text[]]\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n [[]http://www.bewegendepferde.de Fortbildungszentrum "Bewegende Pferde"[]]\r\n\r\nwird zu\r\n\r\n [http://www.bewegendepferde.de Fortbildungszentrum "Bewegende Pferde"]\r\n\r\n\r\n! Seitennamen ohne Wiki-Namen\r\nSoll eine Seite anderst als Wiki-Namen benannt werden, so ist die Bezeichnung in eckige Klammern zu setzen:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[[]Wiki[]]\r\n\r\nwird zu\r\n\r\n[Wiki]\r\n\r\n! Bilder\r\nBilder sind einfach externe Verweise, jedoch mit einer URL, die auf .jpg, .png, .gif endet.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nhttp[:]//home.arcor.de/bewegendepferde/Pic/Logo3d.jpg\r\n\r\nwird zu\r\n\r\nhttp://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg\r\n\r\n----\r\nKategorieHilfe', NULL, 'wk', '2004-04-22 03:23:46', 167);
INSERT INTO infobasar_text VALUES (162, 8, '', 'Absatzformate werden durch ein bestimmtes Zeichen am Zeilenanfang eingeleitet\r\n!! Überschrift\r\nEine Überschrift wird mit einem \'!\' am Zeilenanfang erzeugt. Je mehr \'!\', um so höher der Grad der Überschrift:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[!] Hauptüberschrift\r\n[Newline]!! Überschrift 2. Grades\r\n[Newline]!!! Überschrift 3. Grades\r\n\r\nwird zu\r\n\r\n! Hauptüberschrift\r\n!! Überschrift 2. Grades\r\n!!! Überschrift 3. Grades\r\n\r\n!Horizontale Linie\r\nBeginnt eine Zeile mit mindestens 4 - (Minus), wird eine horizontale Linie gezeichnet. Je mehr -, um so dicker.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[-]---\r\n[Newline]--------\r\n\r\nergibt\r\n----\r\n----------\r\n\r\n\r\n! Aufzählung\r\nSteht als erstes Zeichen in der Zeile ein *, wird die Zeile als Teil einer Aufzählung verstanden:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[*] Nordpferde\r\n[Newline]* Südpferde\r\n\r\nergibt\r\n* Nordpferde\r\n* Südpferde\r\n! Nummerierte Aufzählung\r\nBei nummerierten Aufzählungen ist das erste Zeichen ein #.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[#] Nordpferde\r\n[Newline][#] Südpferde\r\n\r\nergibt\r\n# Nordpferde\r\n# Südpferde\r\n\r\n! Tabellen\r\nEine Tabelle wird mit einem | (links neben dem Y auf der Tastatur) eingeleitet.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[|]Platz|Name|Pferd\r\n[Newline]|1|Deppisch|Tiny Boy\r\n\r\nergibt\r\n\r\n|Platz|Name|Pferd\r\n|1|Deppisch|Tiny Boy\r\n\r\n----\r\nKategorieHilfe', '2004-04-22 02:45:47', 'wk', '2004-04-22 02:48:36', 163);
INSERT INTO infobasar_text VALUES (163, 8, '', 'Absatzformate werden durch ein bestimmtes Zeichen am Zeilenanfang eingeleitet\r\n!! Überschrift\r\nEine Überschrift wird mit einem \'!\' am Zeilenanfang erzeugt. Je mehr \'!\', um so höher der Grad der Überschrift:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[!] Hauptüberschrift\r\n[Newline]!! Überschrift 2. Grades\r\n[Newline]!!! Überschrift 3. Grades\r\n\r\nwird zu\r\n\r\n! Hauptüberschrift\r\n!! Überschrift 2. Grades\r\n!!! Überschrift 3. Grades\r\n\r\n!Horizontale Linie\r\nBeginnt eine Zeile mit mindestens 4 - (Minus), wird eine horizontale Linie gezeichnet. Je mehr -, um so dicker.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[-]---\r\n[Newline]--------\r\n\r\nergibt\r\n----\r\n----------\r\n\r\n\r\n! Aufzählung\r\nSteht als erstes Zeichen in der Zeile ein *, wird die Zeile als Teil einer Aufzählung verstanden:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[*] Nordpferde\r\n[Newline]* Südpferde\r\n\r\nergibt\r\n* Nordpferde\r\n* Südpferde\r\n! Nummerierte Aufzählung\r\nBei nummerierten Aufzählungen ist das erste Zeichen ein #.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[#] Nordpferde\r\n[Newline][#] Südpferde\r\n\r\nergibt\r\n# Nordpferde\r\n# Südpferde\r\n\r\n! Tabellen\r\nEine Tabelle wird mit einem | (links neben dem Y auf der Tastatur) eingeleitet.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[|]\'[\']\'Platz\'[\']\'|\'[\']\'Name\'[\']\'|\'[\']\'Pferd\'[\']\'\r\n[Newline]|1|Deppisch|Tiny Boy\r\n\r\nergibt\r\n\r\n|\'\'\'Platz\'\'\'|\'\'\'Name\'\'\'|\'\'\'Pferd\'\'\'\r\n|1|Deppisch|Tiny Boy\r\n\r\n----\r\nKategorieHilfe', '2004-04-22 02:48:36', 'wk', '2004-04-22 02:53:33', 164);
INSERT INTO infobasar_text VALUES (164, 8, '', 'Absatzformate werden durch ein bestimmtes Zeichen am Zeilenanfang eingeleitet\r\n! Absatzende\r\nEin Absatzende wird durch eine Leerzeile bewirkt.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nHier werden 2 Zeilen\r\n[Newline]ohne Leerzeile geschrieben.\r\n[Newline]\r\n[Newline]Nach einer Leerzeile beginnt der nächste Absatz.\r\n\r\nwird zu\r\n\r\nHier werden 2 Zeilen \r\nohne Leerzeile geschrieben.\r\n\r\nNach einer Leerzeile beginnt der nächste Absatz.\r\n! Überschrift\r\nEine Überschrift wird mit einem \'!\' am Zeilenanfang erzeugt. Je mehr \'!\', um so höher der Grad der Überschrift:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[!] Hauptüberschrift\r\n[Newline]!! Überschrift 2. Grades\r\n[Newline]!!! Überschrift 3. Grades\r\n\r\nwird zu\r\n\r\n! Hauptüberschrift\r\n!! Überschrift 2. Grades\r\n!!! Überschrift 3. Grades\r\n\r\n!Horizontale Linie\r\nBeginnt eine Zeile mit mindestens 4 - (Minus), wird eine horizontale Linie gezeichnet. Je mehr -, um so dicker.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[-]---\r\n[Newline]--------\r\n\r\nergibt\r\n----\r\n----------\r\n\r\n\r\n! Aufzählung\r\nSteht als erstes Zeichen in der Zeile ein *, wird die Zeile als Teil einer Aufzählung verstanden:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[*] Nordpferde\r\n[Newline]* Südpferde\r\n\r\nergibt\r\n* Nordpferde\r\n* Südpferde\r\n! Nummerierte Aufzählung\r\nBei nummerierten Aufzählungen ist das erste Zeichen ein #.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[#] Nordpferde\r\n[Newline][#] Südpferde\r\n\r\nergibt\r\n# Nordpferde\r\n# Südpferde\r\n\r\n! Tabellen\r\nEine Tabelle wird mit einem | (links neben dem Y auf der Tastatur) eingeleitet.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[|]\'[\']\'Platz\'[\']\'|\'[\']\'Name\'[\']\'|\'[\']\'Pferd\'[\']\'\r\n[Newline]|1|Deppisch|Tiny Boy\r\n\r\nergibt\r\n\r\n|\'\'\'Platz\'\'\'|\'\'\'Name\'\'\'|\'\'\'Pferd\'\'\'\r\n|1|Deppisch|Tiny Boy\r\n\r\n----\r\nKategorieHilfe', '2004-04-22 02:53:33', 'wk', '2004-04-22 03:00:44', 165);
INSERT INTO infobasar_text VALUES (165, 8, '', 'Absatzformate werden durch ein bestimmtes Zeichen am Zeilenanfang eingeleitet.\r\n\r\nWeitere Formatierungsmöglichkeiten:\r\n* HilfeFormatierungen\r\n* [HilfeFormatierungImAbsatz]\r\n* HilfeVerweise\r\n! Absatzende\r\nEin Absatzende wird durch eine Leerzeile bewirkt.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nHier werden 2 Zeilen\r\n[Newline]ohne Leerzeile geschrieben.\r\n[Newline]\r\n[Newline]Nach einer Leerzeile beginnt der nächste Absatz.\r\n\r\nwird zu\r\n\r\nHier werden 2 Zeilen \r\nohne Leerzeile geschrieben.\r\n\r\nNach einer Leerzeile beginnt der nächste Absatz.\r\n! Überschrift\r\nEine Überschrift wird mit einem \'!\' am Zeilenanfang erzeugt. Je mehr \'!\', um so höher der Grad der Überschrift:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[!] Hauptüberschrift\r\n[Newline]!! Überschrift 2. Grades\r\n[Newline]!!! Überschrift 3. Grades\r\n\r\nwird zu\r\n\r\n! Hauptüberschrift\r\n!! Überschrift 2. Grades\r\n!!! Überschrift 3. Grades\r\n\r\n!Horizontale Linie\r\nBeginnt eine Zeile mit mindestens 4 - (Minus), wird eine horizontale Linie gezeichnet. Je mehr -, um so dicker.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[-]---\r\n[Newline]--------\r\n\r\nergibt\r\n----\r\n----------\r\n\r\n\r\n! Aufzählung\r\nSteht als erstes Zeichen in der Zeile ein *, wird die Zeile als Teil einer Aufzählung verstanden:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[*] Nordpferde\r\n[Newline]* Südpferde\r\n\r\nergibt\r\n* Nordpferde\r\n* Südpferde\r\n! Nummerierte Aufzählung\r\nBei nummerierten Aufzählungen ist das erste Zeichen ein #.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[#] Nordpferde\r\n[Newline][#] Südpferde\r\n\r\nergibt\r\n# Nordpferde\r\n# Südpferde\r\n\r\n! Tabellen\r\nEine Tabelle wird mit einem | (links neben dem Y auf der Tastatur) eingeleitet.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[|]\'[\']\'Platz\'[\']\'|\'[\']\'Name\'[\']\'|\'[\']\'Pferd\'[\']\'\r\n[Newline]|1|Deppisch|Tiny Boy\r\n\r\nergibt\r\n\r\n|\'\'\'Platz\'\'\'|\'\'\'Name\'\'\'|\'\'\'Pferd\'\'\'\r\n|1|Deppisch|Tiny Boy\r\n\r\n----\r\nKategorieHilfe', '2004-04-22 03:00:44', 'wk', '2004-04-22 03:04:53', 166);
INSERT INTO infobasar_text VALUES (166, 8, '', 'Absatzformate werden durch ein bestimmtes Zeichen am Zeilenanfang eingeleitet.\r\n\r\nWeitere Formatierungsmöglichkeiten:\r\n* HilfeFormatierungen\r\n* HilfeFormatierungImAbsatz\r\n* HilfeVerweise\r\n! Absatzende\r\nEin Absatzende wird durch eine Leerzeile bewirkt.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nHier werden 2 Zeilen\r\n[Newline]ohne Leerzeile geschrieben.\r\n[Newline]\r\n[Newline]Nach einer Leerzeile beginnt der nächste Absatz.\r\n\r\nwird zu\r\n\r\nHier werden 2 Zeilen \r\nohne Leerzeile geschrieben.\r\n\r\nNach einer Leerzeile beginnt der nächste Absatz.\r\n! Überschrift\r\nEine Überschrift wird mit einem \'!\' am Zeilenanfang erzeugt. Je mehr \'!\', um so höher der Grad der Überschrift:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[!] Hauptüberschrift\r\n[Newline]!! Überschrift 2. Grades\r\n[Newline]!!! Überschrift 3. Grades\r\n\r\nwird zu\r\n\r\n! Hauptüberschrift\r\n!! Überschrift 2. Grades\r\n!!! Überschrift 3. Grades\r\n\r\n!Horizontale Linie\r\nBeginnt eine Zeile mit mindestens 4 - (Minus), wird eine horizontale Linie gezeichnet. Je mehr -, um so dicker.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[-]---\r\n[Newline]--------\r\n\r\nergibt\r\n----\r\n----------\r\n\r\n\r\n! Aufzählung\r\nSteht als erstes Zeichen in der Zeile ein *, wird die Zeile als Teil einer Aufzählung verstanden:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[*] Nordpferde\r\n[Newline]* Südpferde\r\n\r\nergibt\r\n* Nordpferde\r\n* Südpferde\r\n! Nummerierte Aufzählung\r\nBei nummerierten Aufzählungen ist das erste Zeichen ein #.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[#] Nordpferde\r\n[Newline][#] Südpferde\r\n\r\nergibt\r\n# Nordpferde\r\n# Südpferde\r\n\r\n! Tabellen\r\nEine Tabelle wird mit einem | (links neben dem Y auf der Tastatur) eingeleitet.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[|]\'[\']\'Platz\'[\']\'|\'[\']\'Name\'[\']\'|\'[\']\'Pferd\'[\']\'\r\n[Newline]|1|Deppisch|Tiny Boy\r\n\r\nergibt\r\n\r\n|\'\'\'Platz\'\'\'|\'\'\'Name\'\'\'|\'\'\'Pferd\'\'\'\r\n|1|Deppisch|Tiny Boy\r\n\r\n----\r\nKategorieHilfe', '2004-04-22 03:04:53', 'wk', '2004-04-22 03:04:53', NULL);
INSERT INTO infobasar_text VALUES (167, 9, '', '! Wiki-Namen\r\nWiki-Namen sind die einfachste Möglichkeit eines Verweises: Jedes Wort, das innerhalb des Wortes einen oder mehrere Großbuchstaben enthält, ist ein Wiki-Wort. Ein Wiki-Wort benennt eine Seite: Wird ein Wiki-Name im Text geschrieben, so ist dies automatisch ein Verweis auf die Seite dieses Namens.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nStartSeite\r\n\r\nHinweis:\r\nSoll ein Wort mit mehreren Großbuchstaben kein Wiki-Namen sein, so wird ein ! vorangestellt:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nDies ist eine !!GmbH\r\n\r\nwird zu\r\n\r\nDies ist eine !GmbH\r\n\r\n!Externe Links\r\nWird eine korrekte URL im Text eingetragen, so wird daraus automatisch ein Verweis:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nhttp://www.bewegendepferde.de\r\n\r\nSoll ein Verweis einen anderen Text haben, so gilt die Formatierung: [[]Verweis Text[]]\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nSiehe [[]http://www.bewegendepferde.de Fortbildungszentrum \r\n"Bewegende Pferde"[]]\r\n\r\nwird zu\r\n\r\nSiehe [http://www.bewegendepferde.de Fortbildungszentrum "Bewegende Pferde"]\r\n\r\n\r\n! Seitennamen ohne Wiki-Namen\r\nSoll eine Seite anderst als Wiki-Namen benannt werden, so ist die Bezeichnung in eckige Klammern zu setzen:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[[]Wiki[]]\r\n\r\nwird zu\r\n\r\n[Wiki]\r\n\r\n! Bilder\r\nBilder sind einfach externe Verweise, jedoch mit einer URL, die auf .jpg, .png, .gif endet.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nhttp[:]//home.arcor.de/bewegendepferde/Pic/Logo3d.jpg\r\n\r\nwird zu\r\n\r\nhttp://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg\r\n\r\n----\r\nKategorieHilfe', '2004-04-22 03:23:46', 'wk', '2004-04-22 03:25:57', 168);
INSERT INTO infobasar_text VALUES (168, 9, '', '! Wiki-Namen\r\nWiki-Namen sind die einfachste Möglichkeit eines Verweises: Jedes Wort, das innerhalb des Wortes einen oder mehrere Großbuchstaben enthält, ist ein Wiki-Wort. Ein Wiki-Wort benennt eine Seite: Wird ein Wiki-Name im Text geschrieben, so ist dies automatisch ein Verweis auf die Seite dieses Namens.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nStartSeite\r\n\r\n!! Wiki-Namen entwerten\r\nSoll ein Wort mit mehreren Großbuchstaben kein Wiki-Namen sein, so wird ein ! vorangestellt:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nDies ist eine !!GmbH\r\n\r\nwird zu\r\n\r\nDies ist eine !GmbH\r\n\r\n!Externe Links\r\nWird eine korrekte URL im Text eingetragen, so wird daraus automatisch ein Verweis:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nhttp://www.bewegendepferde.de\r\n\r\nSoll ein Verweis einen anderen Text haben, so gilt die Formatierung: [[]Verweis Text[]]\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nSiehe [[]http://www.bewegendepferde.de Fortbildungszentrum \r\n"Bewegende Pferde"[]]\r\n\r\nwird zu\r\n\r\nSiehe [http://www.bewegendepferde.de Fortbildungszentrum "Bewegende Pferde"]\r\n\r\n\r\n! Seitennamen ohne Wiki-Namen\r\nSoll eine Seite anderst als Wiki-Namen benannt werden, so ist die Bezeichnung in eckige Klammern zu setzen:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[[]Wiki[]]\r\n\r\nwird zu\r\n\r\n[Wiki]\r\n\r\n! Bilder\r\nBilder sind einfach externe Verweise, jedoch mit einer URL, die auf .jpg, .png, .gif endet.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nhttp[:]//home.arcor.de/bewegendepferde/Pic/Logo3d.jpg\r\n\r\nwird zu\r\n\r\nhttp://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg\r\n\r\n----\r\nKategorieHilfe', '2004-04-22 03:25:57', 'wk', '2004-04-22 03:25:57', NULL);
INSERT INTO infobasar_text VALUES (169, 7, 'w', 'Ein Wiki bietet einfach zu bedienende Formatierungsmöglichkeiten:\r\n\r\n! Zitat\r\nEin Zitat wird in doppelte einfache Apostrophe (auf der Tastatur neben dem Ä, über dem #) eingeschlossen:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nEr sprach von \'[\']hochgradigem Blödsinn[\']\'.\r\n\r\nergibt\r\n\r\nEr sprach von \'\'hochgradigem Blödsinn\'\'.\r\n! Betonung\r\nEine Betonung wird in  doppelte einfache Apostrophe (auf der Tastatur neben dem Ä, über dem #) eingeschlossen:\r\n\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nEine \'[\']\'echte\'[\']\' Qualifikation ist nachzuweisen.\r\n\r\nergibt\r\n\r\nEine \'\'\'echte\'\'\' Qualifikation ist nachzuweisen.\r\n\r\n!! Absatz\r\nEin Absatz wird durch eine Leerzeile beendet.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nErster Teil des Absatzes in Zeile1,[Newline]\r\nzweiter Teil in Zeile 2\r\n\r\nwird zu\r\n\r\nErster Teil des Absatzes in Zeile1, zweiter Teil in Zeile 2\r\n\r\n! Unterstreichung:\r\nEine Unterstreichung wird mit zwei Unterstrichen eingerahmt:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nIch weise  _[_]besonders[_]_ darauf hin.\r\n\r\nwird zu\r\n\r\nIch weise  __besonders__ darauf hin.\r\n\r\n\r\n! Sonstiges\r\nSoll ein Sonderzeichen nicht seine Sonderfunktion annehmen, so wird es einfach in eckige Klammern gesetzt:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nHier kommen 2 _[[]_[]], die aber keine Unterstreichung bewirken sollen.\r\n\r\nwird zu:\r\n\r\nHier kommen 2 _[_], die aber keine Unterstreichung bewirken sollen.\r\n----\r\nKategorieHilfe', '2004-04-23 00:56:11', 'wk', '2004-04-23 00:56:11', NULL);
INSERT INTO infobasar_text VALUES (170, 5, 'w', 'Wiki ist die Abkürzung für Wiki-Wiki und stellt eine Interaktionsform im Internet dar:\r\n\r\n! Grundprinzip\r\n\r\nJeder darf zum Wissensschatz eines Wikis beitragen\r\n\r\n!!Erster Einwand:\r\n\r\nAber wenn jemand was kaputtmacht?\r\n\r\n!!!Antwort:\r\n\r\nEin Wiki vergisst nichts. Ist also mal was absichtlich oder unabsichtlich "kaputtgemacht", so kann man jede vorige Version wiederherstellen.\r\n\r\n!! Zweiter Einwand:\r\nUnd wenn die Zerstörung unbemerkt bleibt?\r\n\r\n!!! Antwort:\r\n* Ein Wiki bietet die Möglichkeit, sich die geänderten Seiten anzeigen zu lassen. Damit kann \'\'\'jeder Leser\'\'\' Korrekturen anbringen.\r\n* Man kann sich die Unterschiede von 2 Versionen anzeigen lassen: Damit gehen kleine Änderungen nicht unter, auch wenn die Seite sehr groß ist.\r\n\r\n!Was bedeutet der Name Wiki-Wiki?\r\nWiki Wiki kommt aus dem Hawaianischen und bedeutet schnell, schnell.\r\n\r\n\r\n!Was zeichnet ein Wiki aus:\r\n* Einfache [HilfeFormatierungen Formatierungsmöglichkeiten]\r\n* Jede normale Seite kann von jedem geändert werden\r\n* Bei Änderungen bleiben die vorigen Versionen gespeichert. Diese können wiederhergestellt werden.\r\n* Es können ganz einfach neue Seiten erstellt werden: Auf einer bestehenden Seite wird einfach der Name der neuen Seite (normalerweise ein Wiki-Name) eingetragen. Wird die Seite gespeichert, so erscheint vor dem Wiki-Namen ein ?. Dies sagt aus, dass diese Seite noch nicht existiert. Klickt man auf den Verweis, so wird diese Seite neu angelegt.\r\n----\r\nKategorieHilfe', '2004-04-23 00:58:18', 'wk', '2004-04-23 00:58:18', NULL);
INSERT INTO infobasar_text VALUES (171, 10, 'w', '<?plugin BackLinks?>', NULL, 'wk', '0000-00-00 00:00:00', NULL);
INSERT INTO infobasar_text VALUES (172, 1, 'w', '! Wo finde ich was?\r\n\r\n* Was ist ein [Wiki]\r\n* HilfeFormatierungen\r\n* [http:!home Übersicht]\r\n* [http:!forumhome Foren-Übersicht]\r\n2\r\n\r\n--------\r\nKategorieOrdnung', '2004-04-24 00:13:50', 'wk', '2004-04-24 00:15:43', 173);
INSERT INTO infobasar_text VALUES (173, 1, 'w', '! Wo finde ich was?\r\n\r\n* Was ist ein [Wiki]\r\n* HilfeFormatierungen\r\n* [http:!home Übersicht]\r\n* [http:!forumhome Foren-Übersicht]\r\nMeineSeite\r\n\r\n--------\r\nKategorieOrdnung', '2004-04-24 00:15:43', 'wk', '2004-04-24 00:15:43', NULL);
INSERT INTO infobasar_text VALUES (174, 11, 'w', 'Beschreibung der Seite SandKiste', NULL, 'wk', '0000-00-00 00:00:00', NULL);

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
  threadsperpage int(11) NOT NULL default '0',
  startpage varchar(128) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM AUTO_INCREMENT=9 ;

#
# Daten für Tabelle `infobasar_user`
#

INSERT INTO infobasar_user VALUES (1, '2004-03-12 20:20:57', '2004-04-18 01:04:15', 'admin', 'YpOYO9mCGFLda', NULL, '', ':all:uadd:umod:udel:', 70, 20, 30, 10, 10, 6, NULL, 20, '15');
INSERT INTO infobasar_user VALUES (2, '2004-03-15 23:09:23', '2004-04-23 00:56:35', 'wk', 'QaDWWvaa6uTkw', NULL, '', ':all:', 81, 26, 31, 2, 10, 16, NULL, 2, '!forumhome');
INSERT INTO infobasar_user VALUES (6, '2004-04-15 13:31:58', '2004-04-15 13:32:39', 'jd', 'EnnLehz1a.Zdj', NULL, 'n', ':all:', 81, 26, 31, 2, 10, 0, NULL, 20, '15');
INSERT INTO infobasar_user VALUES (7, '2004-04-17 22:04:30', '2004-04-17 22:05:53', 'jonny', 'wuWCmqXf1oyoj', NULL, '', ':all:', 81, 26, 31, 2, 10, 0, NULL, 2, '2');
INSERT INTO infobasar_user VALUES (8, '2004-04-17 22:04:53', '0000-00-00 00:00:00', 'jonny', 'MMQkJWxx/Cxkw', NULL, 'j', ':all:', 81, 26, 31, 2, 10, 0, NULL, 2, '2');
