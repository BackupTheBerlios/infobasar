# phpMyAdmin SQL Dump
# version 2.5.5-rc1
# http://www.phpmyadmin.net
#
# Host: localhost
# Erstellungszeit: 24. April 2004 um 00:31
# Server Version: 4.0.17
# PHP-Version: 4.3.3
# 
# Datenbank: infobasar
# 
#CREATE DATABASE infobasar;
#USE infobasar;


# --------------------------------------------------------
#
# Tabellenstruktur f�r Tabelle infobasar_forum
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
# Daten f�r Tabelle infobasar_forum
#

INSERT INTO infobasar_forum VALUES (1, 'Allgemein', 'Was sonst nirgens hinpasst', 1, 1, 0);

# --------------------------------------------------------

#
# Tabellenstruktur f�r Tabelle infobasar_group
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
# Daten f�r Tabelle infobasar_group
#

INSERT INTO infobasar_group VALUES (1, '0000-00-00 00:00:00', 'Gast', 'Alle Besucher');
INSERT INTO infobasar_group VALUES (2, '0000-00-00 00:00:00', 'Registriert', 'Alle registrierten  Benutzer');
INSERT INTO infobasar_group VALUES (3, '0000-00-00 00:00:00', 'Moderator', 'Moderatoren');
INSERT INTO infobasar_group VALUES (4, '0000-00-00 00:00:00', 'Administratoren', 'Administratoren');

# --------------------------------------------------------

#
# Tabellenstruktur f�r Tabelle infobasar_macro
#


DROP TABLE IF EXISTS infobasar_macro;
CREATE TABLE infobasar_macro (
  id int(11) NOT NULL auto_increment,
  theme int(11) NOT NULL default '0',
  name varchar(64) NOT NULL default '',
  value text,
  description varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM AUTO_INCREMENT=69 ;

INSERT INTO infobasar_macro VALUES (52, 1, 'HintFormating', '<small><a href="[ScriptBase]HilfeFormatierungen]">Textformatierung:</a></small>\r\n<table border="1">\r\n<tr><td><small>Zeilenanfang</small></td><td><small>Im Text</small></td><td><small>Links</small></td></tr>\r\n<tr><td><small>! &Uuml;berschrift<br>* Aufz&auml;hlung<br># num. Aufz&auml;hlung<br>---- Linie (4 -)</small></td>\r\n<td><small>\'\'<i>Zitat</i>\'\' (je 2 mal \')<br>\'\'\'<b>wichtig</b>\'\'\' (je 3 mal \')<br>__<u>unterstrichen</u>__ (je 2 mal _)<br> [Newline] Zeilenwechsel</small></td>\r\n<td><small>WikiName<br/> !GmbH<br/>[URL]<br>[URL Text]</small></td>\r\n</tr></table>', 'Formatierungshilfe bei Wikiseitenbearbeitung');
INSERT INTO infobasar_macro VALUES (53, 1, 'ButtonEdit', '<a class="wikiaction" href="[PageName]?action=edit">Bearbeiten</a>', 'Button Bearbeiten');
INSERT INTO infobasar_macro VALUES (54, 1, 'ButtonSearch', '<a class="wikiaction" href="[ScriptBase]!search">Wikisuche</a>', 'Button Wikisuche');
INSERT INTO infobasar_macro VALUES (55, 1, 'ButtonLastChanges', '<a class="wikiaction" href="[ScriptBase]!lastchanges">Letzte &Auml;nderungen</a>', 'Button Letzte �nderung');
INSERT INTO infobasar_macro VALUES (56, 1, 'ButtonNewWiki', '<a class="wikiaction" href="[ScriptBase]!newwiki">Neue Seite</a>', 'Button Neue Seite');
INSERT INTO infobasar_macro VALUES (57, 1, 'ButtonPageInfo', '<a class="wikiaction" href="[PageName]?action=pageinfo">Info</a>', 'Button Seiteninfo');
INSERT INTO infobasar_macro VALUES (58, 1, 'ButtonOverview', '<a class="wikiaction" href="[ScriptBase]!home">&Uuml;berblick</a>', 'Button �bersicht');
INSERT INTO infobasar_macro VALUES (59, 1, 'ButtonForums', '<a class="wikiaction" href="[ScriptBase]!forumhome">Foren&uuml;bersicht</a>', 'Button Foren�bersicht');
INSERT INTO infobasar_macro VALUES (60, 1, 'ButtonForumSearch', '<a class="wikiaction" href="[ScriptBase]!forumsearch">Forensuche</a>', 'Button Forumsuche');
INSERT INTO infobasar_macro VALUES (61, 1, 'ButtonInfo', '<a class="wikiaction" href="[ScriptBase]!info">Info</a>', 'Button Info');
INSERT INTO infobasar_macro VALUES (62, 1, 'ButtonUserStart', '<a class="wikiaction" href="[ScriptBase]!start">Pers&ouml;nliche Startseite</a>', 'Button Pers�nliche Startseite');
INSERT INTO infobasar_macro VALUES (63, 1, 'ButtonHelp', '<a class="wikiaction" href="[ScriptBase]Hilfe">Hilfe</a>', NULL);
INSERT INTO infobasar_macro VALUES (64, 1, 'ButtonAccount', '<a class="wikiaction" href="[ScriptBase]!account">Einstellungen f&uuml;r [User]</a>', NULL);
INSERT INTO infobasar_macro VALUES (65, 1, 'PageLastChange', 'Letzte &Auml;nderung: [PageChangedAt]  [PageChangedBy]', 'Angabe der letzen �nderung');
INSERT INTO infobasar_macro VALUES (66, 11, 'TopRightButtons', '<td style="text-align: right; vertical-align: center">\r\n[M_S_ButtonHelp]\r\n[M_S_ButtonOverview]\r\n[M_S_ButtonAccount]</td>\r\n<td style="text-align: right; vertical-align: bottom"><img alt="Logo" src="[ScriptBase]../pic/logo.png"></td></tr></table>', 'Aktionsbuttons am Seitenkopf rechts');
INSERT INTO infobasar_macro VALUES (67, 11, 'BodyWikiText', '<body>\r\n<table border="0" width="100%"><tr><td><h3>[BasarName]</h3><h1>[PageTitle]</h1>\r\n</a></td>[M_T_TopRightButtons]\r\n<div class="wikitext">', NULL);
INSERT INTO infobasar_macro VALUES (68, 11, 'BodyEndWikiText', '</div><br />\r\n<table border="0" width="100%"><tr><td>[M_S_ButtonOverview]\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonForums]\r\n[M_S_ButtonForumSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_S_ButtonInfo]\r\n</td><td style="text-align: right">\r\n[M_S_ButtonUserStart]\r\n</td></tr></table>\r\n', NULL);

# --------------------------------------------------------

#
# Tabellenstruktur f�r Tabelle infobasar_param
#

DROP TABLE IF EXISTS infobasar_param;
CREATE TABLE infobasar_param (
  id int(11) NOT NULL auto_increment,
  name varchar(128) default NULL,
  theme int(11) default NULL,
  pos int(11) default NULL,
  text text,
  PRIMARY KEY  (id)
) TYPE=MyISAM AUTO_INCREMENT=3303 ;

#
# Daten f�r Tabelle infobasar_param
#

INSERT INTO infobasar_param VALUES (3237, 'DB-Schema-Version', 1, 10, '1.0 (2004.04.15)');
INSERT INTO infobasar_param VALUES (3238, 'DB-Basisinhalt-Version', 1, 11, '1.0 (2004.04.15)');
INSERT INTO infobasar_param VALUES (3239, 'DB-Erweiterungen', 1, 12, 'Design-PHPWiki 1.0 (2004.04.15)');
INSERT INTO infobasar_param VALUES (3240, 'BasarName', 1, 13, 'Matobas lokaler InfoBasar');
INSERT INTO infobasar_param VALUES (3241, 'BenutzerTitel', 1, 14, '10:Neuling;25:Interessent;50:strebsam;75:flei�ig;100:Spezialist;150:Profi;200:Meister;500:Guru');
INSERT INTO infobasar_param VALUES (3242, 'Script-Basis', 1, 15, '/infobasar/index.php/');
INSERT INTO infobasar_param VALUES (3243, 'Header f�r alle Seiten', 10, 101, '<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">\r\n');
INSERT INTO infobasar_param VALUES (3244, 'HTML-Seiten-Head-Abschnitt', 10, 111, '<title>[PageName] (HTML)</title></head>');
INSERT INTO infobasar_param VALUES (3245, 'HTML-Seiten Body-Abschnitt-Anfang', 10, 112, '<body>\r\n<h1>[PageName]</h1>');
INSERT INTO infobasar_param VALUES (3246, 'HTML-Seiten Body-Abschnitt-Ende', 10, 113, '<hr style="width: 100%; height: 2px;">\r\n[M_S_PageLastChange]<br>\r\n[M_S_ButtonEdit]\r\n | [M_S_ButtonSearch]\r\n');
INSERT INTO infobasar_param VALUES (3247, 'HTML-�nderung Head-Abschnitt', 10, 114, '<title>[PageTitle]</title></head>');
INSERT INTO infobasar_param VALUES (3248, 'HTML-�nderung Body-Abschnitt-Anfang', 10, 115, '<body>\r\n<h1>[PageTitle]</h1>');
INSERT INTO infobasar_param VALUES (3249, 'HTML-�nderung Body-Abschnitt-Ende', 10, 116, '</body>');
INSERT INTO infobasar_param VALUES (3250, 'Beitrag-Head-Abschnitt', 10, 121, '<title>Forumsbeitr&auml;ge</title></head>\r\n');
INSERT INTO infobasar_param VALUES (3251, 'Forumsbeitr�ge-Body-Abschnitt-Anfang', 10, 122, '<body>\r\n');
INSERT INTO infobasar_param VALUES (3252, 'Forumsbeitr�geBody-Abschnitt-Ende', 10, 123, '</body>\r\n');
INSERT INTO infobasar_param VALUES (3253, 'Neuer-Beitrag-Head-Abschnitt', 10, 124, '<title>Neues Thema</title></head>\r\n');
INSERT INTO infobasar_param VALUES (3254, 'Neues-Thema-Body-Abschnitt-Anfang', 10, 125, '<body>\r\n');
INSERT INTO infobasar_param VALUES (3255, 'Forumsbeitr�geBody-Abschnitt-Ende', 10, 126, '</body>\r\n');
INSERT INTO infobasar_param VALUES (3256, 'Forumantwort-Head-Abschnitt', 10, 127, '<title>Antwort erstellen</title></head>\r\n');
INSERT INTO infobasar_param VALUES (3257, 'Forenantwort-Body-Abschnitt-Anfang', 10, 128, '<body>\r\n');
INSERT INTO infobasar_param VALUES (3258, 'Forenantwort-Body-Abschnitt-Ende', 10, 129, '</body>\r\n');
INSERT INTO infobasar_param VALUES (3259, 'Login-Head-Bodystart', 10, 131, '<title>Anmeldung fu&uml;r den InfoBasar</title>\r\n<body>\r\n<h1>Willkommen beim lokalen Infobasar</h1>');
INSERT INTO infobasar_param VALUES (3260, 'Login-Body-End', 10, 132, '<p><small>Passwort vergessen? EMail an matoba AT gmx DOT de</small></p>');
INSERT INTO infobasar_param VALUES (3261, 'Wiki-Seiten-Head-Abschnitt', 10, 161, '<title>[PageName] (Wiki)</title></head>');
INSERT INTO infobasar_param VALUES (3262, 'Wiki-Seiten Body-Abschnitt-Anfang', 10, 162, '<body>\r\n<h1>[PageName]</h1>\r\n');
INSERT INTO infobasar_param VALUES (3263, 'Wiki-Seiten Body-Abschnitt-Ende', 10, 163, '<hr style="width: 100%; height: 2px;">\r\n[M_S_PageLastChange]<br>\r\n<table width="100%" border="0"><tr><td>[M_S_ButtonEdit]\r\n | [M_S_ButtonSearch]\r\n | [M_S_ButtonPageInfo]\r\n</td><td style="text-align: right;">\r\n[M_S_ButtonOverview]\r\n | [M_S_ButtonUserStart]\r\n | [M_S_ButtonForums]\r\n[M_S_ButtonHelp]\r\n | [M_S_ButtonAccount]\r\n</td><tr></table>');
INSERT INTO infobasar_param VALUES (3264, 'Wiki-�nderung Head-Abschnitt', 10, 164, '<title>[PageTitle]</title></head>');
INSERT INTO infobasar_param VALUES (3265, 'Wiki-�nderung Body-Abschnitt-Anfang', 10, 165, '<body>\r\n<h1>[PageTitle]</h1>');
INSERT INTO infobasar_param VALUES (3266, 'Wiki-�nderung Body-Abschnitt-Ende', 10, 166, '[M_S_HintFormating]');
INSERT INTO infobasar_param VALUES (3267, 'Design-Name', 11, 100, 'PHPWiki');
INSERT INTO infobasar_param VALUES (3268, 'Header f�r alle Seiten', 11, 101, '<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">\r\n');
INSERT INTO infobasar_param VALUES (3269, 'CSS-Datei', 11, 102, '/infobasar/css/phpwiki.css');
INSERT INTO infobasar_param VALUES (3270, 'HTML-Seiten Body-Abschnitt-Anfang', 11, 112, '<body><table border="0" width="100%"><tr><td>[M_S_ButtonEdit]\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_T_NewWiki]\r\n[M_S_ButtonPageInfo]</td>[M_T_TopRightButtons]\r\n<h1>[PageName]</h1>\r\n<div class="wikitext">');
INSERT INTO infobasar_param VALUES (3271, 'HTML-Seiten Body-Abschnitt-Ende', 11, 113, '</div><br />\r\n<table border="0" width="100%"><tr><td>[M_S_ButtonOverview]\r\n[T_S_ButtonSearch]\r\n[M_S_ButtonForums]\r\n[M_S_ButtonForumSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_S_ButtonInfo]\r\n</td><td style="text-align: right">\r\n[M_S_ButtonUserStart]\r\n</td></tr></table>\r\n');
INSERT INTO infobasar_param VALUES (3272, 'HTML-�nderung Head-Abschnitt', 11, 114, '<title>[PageTitle]</title></head>');
INSERT INTO infobasar_param VALUES (3273, 'HTML-�nderung Body-Abschnitt-Anfang', 11, 115, '<body>\r\n<h1>[PageTitle]</h1>\r\n<div class="wikitext">');
INSERT INTO infobasar_param VALUES (3274, 'HTML-�nderung Body-Abschnitt-Ende', 11, 116, '</div>');
INSERT INTO infobasar_param VALUES (3275, 'Beitrag-Head-Abschnitt', 11, 121, '<title>Forumsbeitr&auml;ge</title></head>\r\n');
INSERT INTO infobasar_param VALUES (3276, 'Forumsbeitr�ge-Body-Abschnitt-Anfang', 11, 122, '[M_T_BodyWikiText]');
INSERT INTO infobasar_param VALUES (3277, 'Forumsbeitr�geBody-Abschnitt-Ende', 11, 123, '[M_T_BodyEndWikiText]');
INSERT INTO infobasar_param VALUES (3278, 'Neuer-Beitrag-Head-Abschnitt', 11, 124, '<title>[PageTitle]</title></head>\r\n');
INSERT INTO infobasar_param VALUES (3279, 'Neues-Thema-Body-Abschnitt-Anfang', 11, 125, '[M_T_BodyWikiText]');
INSERT INTO infobasar_param VALUES (3280, 'Forumsbeitr�geBody-Abschnitt-Ende', 11, 126, '[M_T_BodyEndWikiText]');
INSERT INTO infobasar_param VALUES (3281, 'Forumantwort-Head-Abschnitt', 11, 127, '<title>[PageTitle]</title></head>\r\n');
INSERT INTO infobasar_param VALUES (3282, 'Forenantwort-Body-Abschnitt-Anfang', 11, 128, '[M_T_BodyWikiText]');
INSERT INTO infobasar_param VALUES (3283, 'Forenantwort-Body-Abschnitt-Ende', 11, 129, '[M_S_HintFormating]');
INSERT INTO infobasar_param VALUES (3284, 'Login-Head-Bodystart', 11, 131, '<title>Anmeldung fu&uml;r den InfoBasar</title><body>\r\n<h1>Willkommen beim Infobasar der TDH-Arbeitsgruppe M&uuml;nchen</h1>\r\n<div class="wikitext">');
INSERT INTO infobasar_param VALUES (3285, 'Login-Body-End', 11, 132, '<p><small>Passwort vergessen? EMail an matoba AT gmx DOT de</small></p>');
INSERT INTO infobasar_param VALUES (3286, 'Info-Head', 11, 134, '<title>[BasarName]</title></head>\r\n<body>\r\n<table border="0" width="100%"><tr><td><h1>[PageTitle]</h1>\r\n</a></td>[M_T_TopRightButtons]\r\n<div class="wikitext">');
INSERT INTO infobasar_param VALUES (3287, 'Info- Body-End', 11, 135, '[M_T_BodyEndWikiText]');
INSERT INTO infobasar_param VALUES (3288, 'Standard-Head', 11, 141, '<title>[BasarName]</title></head>\r\n');
INSERT INTO infobasar_param VALUES (3289, 'Standard Body-Start', 11, 142, '[M_T_BodyWikiText]');
INSERT INTO infobasar_param VALUES (3290, 'Wiki-Seiten Body-Abschnitt-Ende', 11, 143, '[M_T_BodyEndWikiText]');
INSERT INTO infobasar_param VALUES (3291, 'Suche-Head', 11, 151, '<title>[BasarName]</title>\r\n</head>');
INSERT INTO infobasar_param VALUES (3292, 'Suche Body-Start', 11, 152, '[M_T_BodyWikiText]');
INSERT INTO infobasar_param VALUES (3293, 'Standard Body-Abschnitt-Ende', 11, 153, '[M_T_BodyEndWikiText]');
INSERT INTO infobasar_param VALUES (3294, 'Wiki-Seiten-Head-Abschnitt', 11, 161, '<title>[PageName] (Wiki)</title></head>');
INSERT INTO infobasar_param VALUES (3295, 'Wiki-Seiten Body-Abschnitt-Anfang', 11, 162, '<body><table border="0" width="100%"><tr><td>[M_S_ButtonEdit]\r\n[M_S_ButtonInfo]\r\n[M_S_ButtonSearch]r\n[M_S_ButtonLastChanges]</td>[M_T_TopRightButtons]\r\n<h1>[PageName]</h1>\r\n<div class="wikitext">');
INSERT INTO infobasar_param VALUES (3296, 'Wiki-Seiten Body-Abschnitt-Ende', 11, 163, '</div>\r\n[M_S_PageLastChange]<br>\r\n<hr style="width: 100%; height: 2px;">\r\n<table width="100%" border="0"><tr><td>[M_S_ButtonEdit]\r\n[M_S_ButtonEdit]\r\n[M_S_ButtonNewWiki]\r\n</td><td style="text-align: right;">\r\n[M_S_ButtonOverview]</a>\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_S_ButtonUserStart]\r\n</td><tr></table>');
INSERT INTO infobasar_param VALUES (3297, 'Wiki-�nderung Head-Abschnitt', 11, 164, '<title>[PageTitle]</title></head>');
INSERT INTO infobasar_param VALUES (3298, 'Wiki-�nderung Body-Abschnitt-Anfang', 11, 165, '<body><table border="0" width="100%"><tr><td><a class="wikiaction" href="[PageName]?action=show">Verwerfen</a>\r\n[M_S_ButtonPageInfo]\r\n[M_S_ButtonSearch]</td><[M_T_TopRightButtons]\r\n<h1>[PageTitle]</h1>\r\n<div class="wikiedit">');
INSERT INTO infobasar_param VALUES (3299, 'Wiki-�nderung Body-Abschnitt-Ende', 11, 166, '</div>[M_S_HintFormating]');
INSERT INTO infobasar_param VALUES (3300, 'Wiki-Vorschau-Anfang', 11, 167, '</div><h1>Vorschau auf [PageName]</h1><br><div class="wikipreview">');
INSERT INTO infobasar_param VALUES (3301, 'Wiki-Vorschau-Ende', 11, 168, '</div><br/><h1>[PageTitle]</h1><br><div class="wikiedit">');
INSERT INTO infobasar_param VALUES (3302, 'Design-Name', 10, 100, 'Minimal');

# --------------------------------------------------------

#
# Tabellenstruktur f�r Tabelle infobasar_posting
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
# Daten f�r Tabelle infobasar_posting
#

# --------------------------------------------------------

#
# Tabellenstruktur f�r Tabelle infobasar_page
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
) TYPE=MyISAM AUTO_INCREMENT=22 ;

#
# Daten f�r Tabelle infobasar_page
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
# Tabellenstruktur f�r Tabelle infobasar_text
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
) TYPE=MyISAM AUTO_INCREMENT=201 ;

#
# Daten f�r Tabelle infobasar_text
#

INSERT INTO infobasar_text VALUES (1, 1, 'w', '! Wo finde ich was?\r\n\r\n* Was ist ein [Wiki]\r\n* HilfeFormatierungen\r\n* [http:!home �bersicht]\r\n* [http:!forumhome Foren-�bersicht]\r\nMeineSeite\r\n\r\n--------\r\nKategorieOrdnung', '2004-04-24 00:15:43', 'wk', '2004-04-24 00:15:43', NULL);
INSERT INTO infobasar_text VALUES (2, 2, 'w', '!Hilfe', '2004-04-08 22:59:59', 'Adam', '2004-04-08 23:00:51', NULL);
INSERT INTO infobasar_text VALUES (3, 3, 'w', 'Wiki ist die Abk�rzung f�r Wiki-Wiki und stellt eine Interaktionsform im Internet dar:\r\n\r\n! Grundprinzip\r\n\r\nJeder darf zum Wissensschatz eines Wikis beitragen\r\n\r\n!!Erster Einwand:\r\n\r\nAber wenn jemand was kaputtmacht?\r\n\r\n!!!Antwort:\r\n\r\nEin Wiki vergisst nichts. Ist also mal was absichtlich oder unabsichtlich "kaputtgemacht", so kann man jede vorige Version wiederherstellen.\r\n\r\n!! Zweiter Einwand:\r\nUnd wenn die Zerst�rung unbemerkt bleibt?\r\n\r\n!!! Antwort:\r\n* Ein Wiki bietet die M�glichkeit, sich die ge�nderten Seiten anzeigen zu lassen. Damit kann \'\'\'jeder Leser\'\'\' Korrekturen anbringen.\r\n* Man kann sich die Unterschiede von 2 Versionen anzeigen lassen: Damit gehen kleine �nderungen nicht unter, auch wenn die Seite sehr gro� ist.\r\n\r\n!Was bedeutet der Name Wiki-Wiki?\r\nWiki Wiki kommt aus dem Hawaianischen und bedeutet schnell, schnell.\r\n\r\n\r\n!Was zeichnet ein Wiki aus:\r\n* Einfache [HilfeFormatierungen Formatierungsm�glichkeiten]\r\n* Jede normale Seite kann von jedem ge�ndert werden\r\n* Bei �nderungen bleiben die vorigen Versionen gespeichert. Diese k�nnen wiederhergestellt werden.\r\n* Es k�nnen ganz einfach neue Seiten erstellt werden: Auf einer bestehenden Seite wird einfach der Name der neuen Seite (normalerweise ein Wiki-Name) eingetragen. Wird die Seite gespeichert, so erscheint vor dem Wiki-Namen ein ?. Dies sagt aus, dass diese Seite noch nicht existiert. Klickt man auf den Verweis, so wird diese Seite neu angelegt.\r\n----\r\nKategorieHilfe', '2004-04-23 00:58:18', 'wk', '2004-04-23 00:58:18', NULL);
INSERT INTO infobasar_text VALUES (5, 5, 'w', 'Ein Wiki bietet einfach zu bedienende Formatierungsm�glichkeiten:\r\n\r\n! Zitat\r\nEin Zitat wird in doppelte einfache Apostrophe (auf der Tastatur neben dem �, �ber dem #) eingeschlossen:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nEr sprach von \'[\']hochgradigem Bl�dsinn[\']\'.\r\n\r\nergibt\r\n\r\nEr sprach von \'\'hochgradigem Bl�dsinn\'\'.\r\n! Betonung\r\nEine Betonung wird in  doppelte einfache Apostrophe (auf der Tastatur neben dem �, �ber dem #) eingeschlossen:\r\n\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nEine \'[\']\'echte\'[\']\' Qualifikation ist nachzuweisen.\r\n\r\nergibt\r\n\r\nEine \'\'\'echte\'\'\' Qualifikation ist nachzuweisen.\r\n\r\n!! Absatz\r\nEin Absatz wird durch eine Leerzeile beendet.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nErster Teil des Absatzes in Zeile1,[Newline]\r\nzweiter Teil in Zeile 2\r\n\r\nwird zu\r\n\r\nErster Teil des Absatzes in Zeile1, zweiter Teil in Zeile 2\r\n\r\n! Unterstreichung:\r\nEine Unterstreichung wird mit zwei Unterstrichen eingerahmt:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nIch weise  _[_]besonders[_]_ darauf hin.\r\n\r\nwird zu\r\n\r\nIch weise  __besonders__ darauf hin.\r\n\r\n\r\n! Sonstiges\r\nSoll ein Sonderzeichen nicht seine Sonderfunktion annehmen, so wird es einfach in eckige Klammern gesetzt:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nHier kommen 2 _[[]_[]], die aber keine Unterstreichung bewirken sollen.\r\n\r\nwird zu:\r\n\r\nHier kommen 2 _[_], die aber keine Unterstreichung bewirken sollen.\r\n----\r\nKategorieHilfe', '2004-04-23 00:56:11', 'wk', '2004-04-23 00:56:11', NULL);
INSERT INTO infobasar_text VALUES (4, 4, '', 'Es gibt folgende Formatierungsm�glichkeiten im Wiki:\r\n* [HilfeFormatierungImAbsatz] (\'\'\'Betonung\'\'\', \'\'Zitat\'\', __unterstrichen__...)\r\n* [HilfeAbsatzFormate] (�berschriften, Aufz�hlungen, Tabellen)\r\n* HilfeVerweise (Wiki-Namen, Externe Links, Bilder...)\r\n----\r\nKategorieHilfe', '2004-04-22 02:14:10', 'wk', '2004-04-22 02:14:10', NULL);
INSERT INTO infobasar_text VALUES (6, 6, '', 'Absatzformate werden durch ein bestimmtes Zeichen am Zeilenanfang eingeleitet.\r\n\r\nWeitere Formatierungsm�glichkeiten:\r\n* HilfeFormatierungen\r\n* HilfeFormatierungImAbsatz\r\n* HilfeVerweise\r\n! Absatzende\r\nEin Absatzende wird durch eine Leerzeile bewirkt.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nHier werden 2 Zeilen\r\n[Newline]ohne Leerzeile geschrieben.\r\n[Newline]\r\n[Newline]Nach einer Leerzeile beginnt der n�chste Absatz.\r\n\r\nwird zu\r\n\r\nHier werden 2 Zeilen \r\nohne Leerzeile geschrieben.\r\n\r\nNach einer Leerzeile beginnt der n�chste Absatz.\r\n! �berschrift\r\nEine �berschrift wird mit einem \'!\' am Zeilenanfang erzeugt. Je mehr \'!\', um so h�her der Grad der �berschrift:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[!] Haupt�berschrift\r\n[Newline]!! �berschrift 2. Grades\r\n[Newline]!!! �berschrift 3. Grades\r\n\r\nwird zu\r\n\r\n! Haupt�berschrift\r\n!! �berschrift 2. Grades\r\n!!! �berschrift 3. Grades\r\n\r\n!Horizontale Linie\r\nBeginnt eine Zeile mit mindestens 4 - (Minus), wird eine horizontale Linie gezeichnet. Je mehr -, um so dicker.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[-]---\r\n[Newline]--------\r\n\r\nergibt\r\n----\r\n----------\r\n\r\n\r\n! Aufz�hlung\r\nSteht als erstes Zeichen in der Zeile ein *, wird die Zeile als Teil einer Aufz�hlung verstanden:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[*] Nordpferde\r\n[Newline]* S�dpferde\r\n\r\nergibt\r\n* Nordpferde\r\n* S�dpferde\r\n! Nummerierte Aufz�hlung\r\nBei nummerierten Aufz�hlungen ist das erste Zeichen ein #.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[#] Nordpferde\r\n[Newline][#] S�dpferde\r\n\r\nergibt\r\n# Nordpferde\r\n# S�dpferde\r\n\r\n! Tabellen\r\nEine Tabelle wird mit einem | (links neben dem Y auf der Tastatur) eingeleitet.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[|]\'[\']\'Platz\'[\']\'|\'[\']\'Name\'[\']\'|\'[\']\'Pferd\'[\']\'\r\n[Newline]|1|Deppisch|Tiny Boy\r\n\r\nergibt\r\n\r\n|\'\'\'Platz\'\'\'|\'\'\'Name\'\'\'|\'\'\'Pferd\'\'\'\r\n|1|Mayer|Dchungelboy\r\n\r\n----\r\nKategorieHilfe', '2004-04-22 03:04:53', 'wk', '2004-04-22 03:04:53', NULL);
INSERT INTO infobasar_text VALUES (7, 7, '', '! Wiki-Namen\r\nWiki-Namen sind die einfachste M�glichkeit eines Verweises: Jedes Wort, das innerhalb des Wortes einen oder mehrere Gro�buchstaben enth�lt, ist ein Wiki-Wort. Ein Wiki-Wort benennt eine Seite: Wird ein Wiki-Name im Text geschrieben, so ist dies automatisch ein Verweis auf die Seite dieses Namens.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nStartSeite\r\n\r\n!! Wiki-Namen entwerten\r\nSoll ein Wort mit mehreren Gro�buchstaben kein Wiki-Namen sein, so wird ein ! vorangestellt:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nDies ist eine !!GmbH\r\n\r\nwird zu\r\n\r\nDies ist eine !GmbH\r\n\r\n!Externe Links\r\nWird eine korrekte URL im Text eingetragen, so wird daraus automatisch ein Verweis:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nhttp://www.bewegendepferde.de\r\n\r\nSoll ein Verweis einen anderen Text haben, so gilt die Formatierung: [[]Verweis Text[]]\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nSiehe [[]http://www.bewegendepferde.de Fortbildungszentrum \r\n"Bewegende Pferde"[]]\r\n\r\nwird zu\r\n\r\nSiehe [http://www.bewegendepferde.de Fortbildungszentrum "Bewegende Pferde"]\r\n\r\n\r\n! Seitennamen ohne Wiki-Namen\r\nSoll eine Seite anderst als Wiki-Namen benannt werden, so ist die Bezeichnung in eckige Klammern zu setzen:\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\n[[]Wiki[]]\r\n\r\nwird zu\r\n\r\n[Wiki]\r\n\r\n! Bilder\r\nBilder sind einfach externe Verweise, jedoch mit einer URL, die auf .jpg, .png, .gif endet.\r\n\r\n\'\'\'Beispiel\'\'\':\r\n\r\nhttp[:]//home.arcor.de/bewegendepferde/Pic/Logo3d.jpg\r\n\r\nwird zu\r\n\r\nhttp://home.arcor.de/bewegendepferde/Pic/Logo3d.jpg\r\n\r\n----\r\nKategorieHilfe', '2004-04-22 03:25:57', 'wk', '2004-04-22 03:25:57', NULL);
INSERT INTO infobasar_text VALUES (8, 8, 'w', '! �berschrift\r\nSandKiste\r\nhttp://www.heise.de', '2004-04-08 22:51:54', 'wk', '2004-04-08 22:54:16', NULL);

# --------------------------------------------------------

#
# Tabellenstruktur f�r Tabelle infobasar_user
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
# Daten f�r Tabelle infobasar_user
#

INSERT INTO infobasar_user VALUES (1, '2004-03-12 20:20:57', '2004-04-18 01:04:15', 'admin', 'YpOYO9mCGFLda', NULL, '', ':all:uadd:umod:udel:', 70, 20, 30, 10, 10, 6, NULL, 20, '15');
INSERT INTO infobasar_user VALUES (2, '2004-03-15 23:09:23', '2004-04-23 00:56:35', 'wk', 'QaDWWvaa6uTkw', NULL, '', ':all:', 81, 26, 31, 2, 10, 16, NULL, 2, '!forumhome');
