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
#CREATE DATABASE `infobasar`;
#USE infobasar;

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
) TYPE=MyISAM AUTO_INCREMENT=2 ;

#
# Daten für Tabelle `infobasar_forum`
#

INSERT INTO infobasar_forum VALUES (1, 'Allgemein', 'Was sonst nirgens hinpasst', 1, 1, 0);

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
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# Daten für Tabelle `infobasar_posting`
#

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
) TYPE=MyISAM AUTO_INCREMENT=9 ;

#
# Daten für Tabelle `infobasar_page`
#

INSERT INTO infobasar_page VALUES (1, 'StartSeite', 'w', NULL, '2004-04-09 21:56:48', 0, 0);
INSERT INTO infobasar_page VALUES (2, 'HilfeAendern', 'w', NULL, '0000-00-00 00:00:00', 1, 3);
INSERT INTO infobasar_page VALUES (3, 'Wiki', 'w', '2004-04-22 02:05:24', '0000-00-00 00:00:00', 0, 0);
INSERT INTO infobasar_page VALUES (4, 'HilfeFormatierungen', 'w', '2004-04-22 02:10:12', '0000-00-00 00:00:00', 0, 0);
INSERT INTO infobasar_page VALUES (5, 'HilfeFormatierungImAbsatz', 'w', '2004-04-22 02:11:32', '0000-00-00 00:00:00', 0, 0);
INSERT INTO infobasar_page VALUES (6, 'HilfeAbsatzFormate', 'w', '2004-04-22 02:12:43', '0000-00-00 00:00:00', 0, 0);
INSERT INTO infobasar_page VALUES (7, 'HilfeVerweise', 'w', '2004-04-22 02:15:05', '0000-00-00 00:00:00', 0, 0);
INSERT INTO infobasar_page VALUES (8, 'SandKiste', 'w', '2004-04-24 00:16:27', '2004-04-24 00:16:27', 0, 0);


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
) TYPE=MyISAM AUTO_INCREMENT=9 ;

#
# Daten für Tabelle `infobasar_text`
#

INSERT INTO infobasar_text VALUES (1, 1, 'w', '! Wo finde ich was?\r\n\r\n* Was ist ein [Wiki]\r\n* HilfeFormatierungen\r\n* [http:!home Übersicht]\r\n* [http:!forumhome Foren-Übersicht]\r\nMeineSeite\r\n\r\n--------\r\nKategorieOrdnung', '2004-04-24 00:15:43', 'wk', '2004-04-24 00:15:43', NULL);
INSERT INTO infobasar_text VALUES (2, 2, 'w', '!Hilfe', '2004-04-08 22:59:59', 'Adam', '2004-04-08 23:00:51', NULL);
INSERT INTO infobasar_text VALUES (3, 3, 'w', 'Wiki ist die Abkürzung für Wiki-Wiki und stellt eine Interaktionsform im Internet dar:\r\n\r\n! Grundprinzip\r\n\r\nJeder darf zum Wissensschatz eines Wikis beitragen\r\n\r\n!!Erster Einwand:\r\n\r\nAber wenn jemand was kaputtmacht?\r\n\r\n!!!Antwort:\r\n\r\nEin Wiki vergisst nichts. Ist also mal was absichtlich oder unabsichtlich "kaputtgemacht", so kann man jede vorige Version wiederherstellen.\r\n\r\n!! Zweiter Einwand:\r\nUnd wenn die Zerstörung unbemerkt bleibt?\r\n\r\n!!! Antwort:\r\n* Ein Wiki bietet die Möglichkeit, sich die geänderten Seiten anzeigen zu lassen. Damit kann \'\'\'jeder Leser\'\'\' Korrekturen anbringen.\r\n* Man kann sich die Unterschiede von 2 Versionen anzeigen lassen: Damit gehen kleine Änderungen nicht unter, auch wenn die Seite sehr groß ist.\r\n\r\n!Was bedeutet der Name Wiki-Wiki?\r\nWiki Wiki kommt aus dem Hawaianischen und bedeutet schnell, schnell.\r\n\r\n\r\n!Was zeichnet ein Wiki aus:\r\n* Einfache [HilfeFormatierungen Formatierungsmöglichkeiten]\r\n* Jede normale Seite kann von jedem geändert werden\r\n* Bei Änderungen bleiben die vorigen Versionen gespeichert. Diese können wiederhergestellt werden.\r\n* Es können ganz einfach neue Seiten erstellt werden: Auf einer bestehenden Seite wird einfach der Name der neuen Seite (normalerweise ein Wiki-Name) eingetragen. Wird die Seite gespeichert, so erscheint vor dem Wiki-Namen ein ?. Dies sagt aus, dass diese Seite noch nicht existiert. Klickt man auf den Verweis, so wird diese Seite neu angelegt.\r\n----\r\nKategorieHilfe', '2004-04-23 00:58:18', 'wk', '2004-04-23 00:58:18', NULL);
INSERT INTO infobasar_text VALUES (5, 5, 'w', 'Ein Wiki bietet einfach zu bedienende Formatierungsmöglichkeiten:\r\n\r\n! Zitat\r\nEin Zitat wird in doppelte einfache Apostrophe (auf der Tastatur neben dem Ä, über dem #) eingeschlossen:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nEr sprach von \'[\']hochgradigem Blödsinn[\']\'.\r\n\r\nergibt\r\n\r\nEr sprach von \'\'hochgradigem Blödsinn\'\'.\r\n! Betonung\r\nEine Betonung wird in  doppelte einfache Apostrophe (auf der Tastatur neben dem Ä, über dem #) eingeschlossen:\r\n\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nEine \'[\']\'echte\'[\']\' Qualifikation ist nachzuweisen.\r\n\r\nergibt\r\n\r\nEine \'\'\'echte\'\'\' Qualifikation ist nachzuweisen.\r\n\r\n!! Absatz\r\nEin Absatz wird durch eine Leerzeile beendet.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nErster Teil des Absatzes in Zeile1,[Newline]\r\nzweiter Teil in Zeile 2\r\n\r\nwird zu\r\n\r\nErster Teil des Absatzes in Zeile1, zweiter Teil in Zeile 2\r\n\r\n! Unterstreichung:\r\nEine Unterstreichung wird mit zwei Unterstrichen eingerahmt:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nIch weise  _[_]besonders[_]_ darauf hin.\r\n\r\nwird zu\r\n\r\nIch weise  __besonders__ darauf hin.\r\n\r\n\r\n! Sonstiges\r\nSoll ein Sonderzeichen nicht seine Sonderfunktion annehmen, so wird es einfach in eckige Klammern gesetzt:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nHier kommen 2 _[[]_[]], die aber keine Unterstreichung bewirken sollen.\r\n\r\nwird zu:\r\n\r\nHier kommen 2 _[_], die aber keine Unterstreichung bewirken sollen.\r\n----\r\nKategorieHilfe', '2004-04-23 00:56:11', 'wk', '2004-04-23 00:56:11', NULL);
INSERT INTO infobasar_text VALUES (4, 4, '', 'Es gibt folgende Formatierungsmöglichkeiten im Wiki:\r\n* [HilfeFormatierungImAbsatz] (\'\'\'Betonung\'\'\', \'\'Zitat\'\', __unterstrichen__...)\r\n* [HilfeAbsatzFormate] (Überschriften, Aufzählungen, Tabellen)\r\n* HilfeVerweise (Wiki-Namen, Externe Links, Bilder...)\r\n----\r\nKategorieHilfe', '2004-04-22 02:14:10', 'wk', '2004-04-22 02:14:10', NULL);
INSERT INTO infobasar_text VALUES (6, 6, '', 'Absatzformate werden durch ein bestimmtes Zeichen am Zeilenanfang eingeleitet.\r\n\r\nWeitere Formatierungsmöglichkeiten:\r\n* HilfeFormatierungen\r\n* HilfeFormatierungImAbsatz\r\n* HilfeVerweise\r\n! Absatzende\r\nEin Absatzende wird durch eine Leerzeile bewirkt.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nHier werden 2 Zeilen\r\n[Newline]ohne Leerzeile geschrieben.\r\n[Newline]\r\n[Newline]Nach einer Leerzeile beginnt der nächste Absatz.\r\n\r\nwird zu\r\n\r\nHier werden 2 Zeilen \r\nohne Leerzeile geschrieben.\r\n\r\nNach einer Leerzeile beginnt der nächste Absatz.\r\n! Überschrift\r\nEine Überschrift wird mit einem \'!\' am Zeilenanfang erzeugt. Je mehr \'!\', um so höher der Grad der Überschrift:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[!] Hauptüberschrift\r\n[Newline]!! Überschrift 2. Grades\r\n[Newline]!!! Überschrift 3. Grades\r\n\r\nwird zu\r\n\r\n! Hauptüberschrift\r\n!! Überschrift 2. Grades\r\n!!! Überschrift 3. Grades\r\n\r\n!Horizontale Linie\r\nBeginnt eine Zeile mit mindestens 4 - (Minus), wird eine horizontale Linie gezeichnet. Je mehr -, um so dicker.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[-]---\r\n[Newline]--------\r\n\r\nergibt\r\n----\r\n----------\r\n\r\n\r\n! Aufzählung\r\nSteht als erstes Zeichen in der Zeile ein *, wird die Zeile als Teil einer Aufzählung verstanden:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[*] Nordpferde\r\n[Newline]* Südpferde\r\n\r\nergibt\r\n* Nordpferde\r\n* Südpferde\r\n! Nummerierte Aufzählung\r\nBei nummerierten Aufzählungen ist das erste Zeichen ein #.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[#] Nordpferde\r\n[Newline][#] Südpferde\r\n\r\nergibt\r\n# Nordpferde\r\n# Südpferde\r\n\r\n! Tabellen\r\nEine Tabelle wird mit einem | (links neben dem Y auf der Tastatur) eingeleitet.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[|]\'[\']\'Platz\'[\']\'|\'[\']\'Name\'[\']\'|\'[\']\'Pferd\'[\']\'\r\n[Newline]|1|Deppisch|Tiny Boy\r\n\r\nergibt\r\n\r\n|\'\'\'Platz\'\'\'|\'\'\'Name\'\'\'|\'\'\'Pferd\'\'\'\r\n|1|Mayer|Dchungelboy\r\n\r\n----\r\nKategorieHilfe', '2004-04-22 03:04:53', 'wk', '2004-04-22 03:04:53', NULL);
INSERT INTO infobasar_text VALUES (7, 7, '', '! Wiki-Namen\r\nWiki-Namen sind die einfachste Möglichkeit eines Verweises: Jedes Wort, das innerhalb des Wortes einen oder mehrere Großbuchstaben enthält, ist ein Wiki-Wort. Ein Wiki-Wort benennt eine Seite: Wird ein Wiki-Name im Text geschrieben, so ist dies automatisch ein Verweis auf die Seite dieses Namens.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nStartSeite\r\n\r\n!! Wiki-Namen entwerten\r\nSoll ein Wort mit mehreren Großbuchstaben kein Wiki-Namen sein, so wird ein ! vorangestellt:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nDies ist eine !!GmbH\r\n\r\nwird zu\r\n\r\nDies ist eine !GmbH\r\n\r\n!Externe Links\r\nWird eine korrekte URL im Text eingetragen, so wird daraus automatisch ein Verweis:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nhttp://www.bewegendepferde.de\r\n\r\nSoll ein Verweis einen anderen Text haben, so gilt die Formatierung: [[]Verweis Text[]]\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nSiehe [[]http://www.bewegendepferde.de Fortbildungszentrum \r\n"Bewegende Pferde"[]]\r\n\r\nwird zu\r\n\r\nSiehe [http://www.bewegendepferde.de Fortbildungszentrum "Bewegende Pferde"]\r\n\r\n\r\n! Seitennamen ohne Wiki-Namen\r\nSoll eine Seite anderst als Wiki-Namen benannt werden, so ist die Bezeichnung in eckige Klammern zu setzen:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[[]Wiki[]]\r\n\r\nwird zu\r\n\r\n[Wiki]\r\n\r\n! Bilder\r\nBilder sind einfach externe Verweise, jedoch mit einer URL, die auf .jpg, .png, .gif endet.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nhttp[:]//home.arcor.de/bewegendepferde/Pic/Logo3d.jpg\r\n\r\nwird zu\r\n\r\nhttp://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg\r\n\r\n----\r\nKategorieHilfe', '2004-04-22 03:25:57', 'wk', '2004-04-22 03:25:57', NULL);
INSERT INTO infobasar_text VALUES (8, 8, 'w', '! Überschrift\r\nSandKiste\r\nhttp://www.heise.de', '2004-04-08 22:51:54', 'wk', '2004-04-08 22:54:16', NULL);

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
) TYPE=MyISAM AUTO_INCREMENT=3 ;

#
# Daten für Tabelle `infobasar_user`
#

INSERT INTO infobasar_user VALUES (1, '2004-03-12 20:20:57', '2004-04-18 01:04:15', 'admin', 'YpOYO9mCGFLda', NULL, '', ':all:uadd:umod:udel:', 70, 20, 30, 10, 10, 6, NULL, 20, '15');
INSERT INTO infobasar_user VALUES (2, '2004-03-15 23:09:23', '2004-04-23 00:56:35', 'wk', 'QaDWWvaa6uTkw', NULL, '', ':all:', 81, 26, 31, 2, 10, 16, NULL, 2, '!forumhome');
