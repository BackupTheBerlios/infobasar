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
USE InfoBasar;


# --------------------------------------------------------
#
# Tabellenstruktur für Tabelle infobasar_forum
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
) TYPE=MyISAM AUTO_INCREMENT=11;


#
# Daten für Tabelle infobasar_forum
#

INSERT INTO infobasar_forum VALUES (10, 'Allgemein', 'Was sonst nirgens hinpasst', 1, 1, 0);

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle infobasar_user
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
) TYPE=MyISAM AUTO_INCREMENT=20 ;

#
# Daten für Tabelle infobasar_user
#

INSERT INTO infobasar_user (id,name,code) VALUES (11, 'admin', 'YpOYO9mCGFLda');
INSERT INTO infobasar_user (id,name,code) VALUES (12, 'gast', '');
INSERT INTO infobasar_user (id,name,code) VALUES (13, 'wk', 'QaDWWvaa6uTkw');

#
# Tabellenstruktur für Tabelle infobasar_group
#

DROP TABLE IF EXISTS infobasar_group;
CREATE TABLE infobasar_group (
  id int(11) NOT NULL auto_increment,
  changedat datetime NOT NULL default '0000-00-00 00:00:00',
  name varchar(32) default NULL,
  description varchar(255) default NULL,
  rights varchar(255) default NULL,
  users text, /* mit : getrennte Id-Liste. Bsp: :1:1023:7: */
  pages text, /* mit : getrennte Id-Liste. Bsp: :1:1023:7: */
  forums text,  /* mit : getrennte Id-Liste. Bsp: :1:1023:7: */
  PRIMARY KEY  (id)
) TYPE=MyISAM AUTO_INCREMENT=20 ;

#
# Daten für Tabelle infobasar_group
#

INSERT INTO infobasar_group (id,name,description,users) VALUES (11, 'Gast', 'Alle Besucher', ':12:');
INSERT INTO infobasar_group (id,name,description,users) VALUES (12, 'Registriert', 'Alle registrierten  Benutzer', '');
INSERT INTO infobasar_group (id,name,description,users) VALUES (13, 'Moderator', 'Moderatoren', ':13:');
INSERT INTO infobasar_group (id,name,description,users) VALUES (14, 'Administratoren', 'Administratoren', ':11:');

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle infobasar_macro
#


DROP TABLE IF EXISTS infobasar_macro;
CREATE TABLE infobasar_macro (
  id int(11) NOT NULL auto_increment,
  theme int(11) NOT NULL default '0',
  name varchar(64) NOT NULL default '',
  value text,
  description varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM AUTO_INCREMENT=10;

INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'BaseModule', 'PHP-Datei Basismodul', '/index.php/');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ForumModule', 'PHP-Datei Forumsmodul', '/forum.php/');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ScriptBase', 'Pfad zur PHP-Datei', '/');

INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'HintFormating', 'Formatierungshilfe bei Wikiseitenbearbeitung', '<small><a href="[M_S_BaseModule]HilfeFormatierungen]">Textformatierung:</a></small>\n<table border="1">\n</td><td><small><b>Im Absatz</b></small></td><td><small><b>Zeilenanfang</b></small></td>\n<td><small><b>Links</b></small></td>\n<td><small><b>Schriften</b></small></td>\n<td><small><b>Sonstiges</b></small></td></tr><tr>\n<td><small>\'\'\'<b>wichtig</b>\'\'\' (je 3 mal \')<br>\'\'<i>Zitat</i>\'\' (je 2 mal \')<br>__<u>unterstrichen</u>__ (je 2 _)<br>%%% Zeilenwechsel<br>[[!]] Zeichen !<br></small></td>\n<td><small>! &Uuml;berschrift<br>* Aufz&auml;hlung<br># num. Aufz.<br>; Einr&uuml;ckung<br>---- Linie (4 -)<br></td>\n<td><small>WikiName<br/> ["Seite"|Text]<br>!GmbH<br/>[[URL]]<br>[URL|Text]</small></td>\n<td><small>[big]Groß[/big]<br>[small]klein[/small]<br>[sup]<sup>hoch</sup>[/sup]<br>[sub]<sub>tief</sub>[/sub]<br>[tt]<tt>Fixfont</tt>[/tt]</small></td>\n<td><small>[code]<br>Quellcode<br>[/code]<br>!| Tabellenkopf<br>| Tabellenzeile<br>&lt;?plugin ...?&gt;</small></td></tr></table>\n');

INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonEdit', 'Button Bearbeiten', '<a class="wikiaction" href="[PageLink]?action=edit">Bearbeiten</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonSearch', 'Button Wikisuche', '<a class="wikiaction" href="[M_S_BaseModule]!search">Wikisuche</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonLastChanges', 'Button Letzte Änderung', '<a class="wikiaction" href="[M_S_BaseModule]!lastchanges">Letzte &Auml;nderungen</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonNewWiki', 'Button Neue Seite', '<a class="wikiaction" href="[M_S_BaseModule]!newwiki">Neue Seite</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonPageInfo', 'Button Seiteninfo', '<a class="wikiaction" href="[PageLink]?action=pageinfo">Info</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonOverview', 'Button Überblick', '<a class="wikiaction" href="[M_S_BaseModule]!home">&Uuml;berblick</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonForums', 'Button Forenübersicht', '<a class="wikiaction" href="[M_S_ForumModule]!forumhome">Foren&uuml;bersicht</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonForumSearch', 'Button Forumsuche', '<a class="wikiaction" href="[M_S_ForumModule]!forumsearch">Forensuche</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonInfo', 'Button Info', '<a class="wikiaction" href="[M_S_BaseModule]!info">Info</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonUserStart', 'Button Persönliche Startseite', '<a class="wikiaction" href="[M_S_BaseModule]!start">Pers&ouml;nliche Startseite</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonHelp', NULL, '<a class="wikiaction" href="[M_S_BaseModule]Hilfe">Hilfe</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonAccount', NULL, '<a class="wikiaction" href="[M_S_BaseModule]!account">Einstellungen f&uuml;r [User]</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'PageLastChange', 'Angabe der letzen Änderung', 'Letzte &Auml;nderung: [PageChangedAt]  [PageChangedBy]');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'TitleSearch', 'Titelsuche', '<form name="tsearch" action="index.php" method="post">Titel: <input type="text" name="search_titletext" size="10" maxlength="64"> <input class="wikiaction" name="search_title" value="Suchen" type="submit"></form>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'RuntimeSec', NULL, '<br>Der Seitenaufbau benötigte [RuntimeSecMilli] sec auf dem Server.');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'BodyEnd', NULL, '[M_S_RuntimeSec]</body>\r\n');

INSERT INTO infobasar_macro (theme,name,description,value) VALUES (10, 'TopRightButtons', 'Aktionsbuttons am Seitenkopf rechts', '');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (10, 'BodyWikiText', NULL, '<body>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (10, 'BodyEndWikiText', NULL, '<br>[M_S_ButtonOverview] [M_S_RuntimeSec]');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (10, 'BodyEndWikiTextTitleSearch', NULL, '<br>\r\n[M_S_RuntimeSec]');
#                                                                  [M_T_BodyEndWikiTextTitleSearch]
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (10, 'BodyWikiText', NULL, '<body>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (10, 'BodyEndWikiText', NULL, '<br>[M_S_ButtonOverview] [M_S_RuntimeSec]');

INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'TopRightButtons', 'Aktionsbuttons am Seitenkopf rechts', '<td style="text-align: center; vertical-align: top">\r\n[M_S_TitleSearch]</td>\r\n<td style="text-align: right; vertical-align: top">[M_S_ButtonHelp]\r\n[M_S_ButtonOverview]\r\n[M_S_ButtonAccount]</td>\r\n<td style="text-align: right; vertical-align: top"><img alt="Logo" src="[M_S_ScriptBase]pic/logo.png"></td></tr></table>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'BodyWikiText', NULL, '<body>\r\n<table border="0" width="100%"><tr><td><h3>[BasarName]</h3><h1>[PageTitle]</h1>\r\n</a></td>[M_T_TopRightButtons]\r\n<div class="wikitext">');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'BodyEndWikiText', NULL, '</div><br />\r\n<table border="0" width="100%"><tr><td>[M_S_ButtonOverview]\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonForums]\r\n[M_S_ButtonForumSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_S_ButtonInfo]\r\n</td><td style="text-align: right">\r\n[M_S_ButtonUserStart]\r\n</td></tr></table>\r\n[M_S_RuntimeSec]');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'BodyEndWikiTextTitleSearch', NULL, '</div><br />\r\n<table border="0" width="100%"><tr><td style="vertical-align: top">[M_S_ButtonOverview]\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonForums]\r\n[M_S_ButtonForumSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_S_ButtonInfo]\r\n</td><td style="text-align: center; vertical-align: top">[M_S_TitleSearch]\r\n</td><td style="text-align: right; vertical-align: top">\r\n[M_S_ButtonUserStart]\r\n</td></tr></table>\r\n[M_S_RuntimeSec]');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'BodyWikiText', NULL, '<body>\r\n<table border="0" width="100%"><tr><td><h3>[BasarName]</h3><h1>[PageTitle]</h1>\r\n</a></td>[M_T_TopRightButtons]\r\n<div class="wikitext">');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'BodyEndWikiText', NULL, '</div><br />\r\n<table border="0" width="100%"><tr><td>[M_S_ButtonOverview]\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonForums]\r\n[M_S_ButtonForumSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_S_ButtonInfo]\r\n</td><td style="text-align: right">\r\n[M_S_ButtonUserStart]\r\n</td></tr></table>\r\n');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle infobasar_param
#

DROP TABLE IF EXISTS infobasar_param;
CREATE TABLE infobasar_param (
  id int(11) NOT NULL auto_increment,
  name varchar(128) default NULL,
  theme int(11) default NULL,
  pos int(11) default NULL,
  text text,
  PRIMARY KEY  (id)
) TYPE=MyISAM AUTO_INCREMENT=10;

#
# Daten für Tabelle infobasar_param
#

# ===================================
# Daten für Tabelle `infobasar_param`
# Alle Module, alle Designs (100-149):
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Standard-Head', 1, 101, '<title>[BasarName]</title></head>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Standard Body-Start', 1, 102, '[M_T_BodyWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Ende', 1, 103, '[M_T_BodyEndWikiTextTitleSearch]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Login-Head-Bodystart', 1, 105, '<title>Anmeldung fu&uml;r den InfoBasar</title>\r\n<body>\r\n<h1>Willkommen beim Infobasar</h1>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Login-Body-End', 1, 106, '<p><small>Passwort vergessen? EMail an hamatoma AT gmx DOT de</small></p>[M_S_RuntimeSec]');


# ===================================
# Daten für Tabelle `infobasar_param`
# Modul Basis:

INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('DB-Schema-Version', 1, 10, '1.0 (2004.04.15)');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('DB-Basisinhalt-Version', 1, 11, '1.0 (2004.04.15)');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('DB-Erweiterungen', 1, 12, 'Design-PHPWiki 1.1 (2004.06.07)');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('BasarName', 1, 13, 'InfoBasar');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Script-Basis', 1, 15, '');

/* ----------------- */
# Design Minimal:
# Alle Module:
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Design-Name', 10, 150, 'Minimal');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Header für alle Seiten', 10, 151, '<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">\r\n');
# Basismodul:
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten-Head-Abschnitt', 10, 211, '<title>[PageName] (HTML)</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten Body-Abschnitt-Anfang', 10, 212, '<body>\r\n<h1>[PageName]</h1>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten Body-Abschnitt-Ende', 10, 213, '<hr style="width: 100%; height: 2px;">\r\n[M_S_PageLastChange]<br>\r\n[M_S_ButtonEdit]\r\n | [M_S_ButtonSearch]\r\n[M_S_RuntimeSec]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Head-Abschnitt', 10, 214, '<title>[PageTitle]</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Body-Abschnitt-Anfang', 10, 215, '<body>\r\n<h1>[PageTitle]</h1>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Body-Abschnitt-Ende', 10, 216, '[M_S_BodyEnd]');

INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten-Head-Abschnitt', 10, 241, '<title>[PageName] (Wiki)</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Anfang', 10, 242, '<body>\r\n<h1>[PageName]</h1>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Ende', 10, 243, '<hr style="width: 100%; height: 2px;">\r\n[M_S_PageLastChange]<br>\r\n<table width="100%" border="0"><tr><td>[M_S_ButtonEdit]\r\n | [M_S_ButtonSearch]\r\n | [M_S_ButtonPageInfo]\r\n</td><td style="text-align: right;">\r\n[M_S_ButtonOverview]\r\n | [M_S_ButtonUserStart]\r\n | [M_S_ButtonForums]\r\n[M_S_ButtonHelp]\r\n | [M_S_ButtonAccount]\r\n</td><tr></table>[M_S_RuntimeSec]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Head-Abschnitt', 10, 244, '<title>[PageTitle]</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Body-Abschnitt-Anfang', 10, 245, '<body>\r\n<h1>[PageTitle]</h1>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Body-Abschnitt-Ende', 10, 246, '[M_S_HintFormating][M_S_RuntimeSec]');

/* ============================= */
# Design PHPWiki:
# Alle Module:
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Design-Name', 11, 150, 'PHPWiki');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Header für alle Seiten', 11, 151, '<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('CSS-Datei', 11, 152, '/infobasar/css/phpwiki.css');

# Basismodul:
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten-Head-Abschnitt', 11, 211, '<title>[PageName] (HTML)</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten Body-Abschnitt-Anfang', 11, 212, '<body><table border="0" width="100%"><tr><td>[M_S_ButtonEdit]\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_S_ButtonNewWiki]\r\n[M_S_ButtonPageInfo]</td>[M_T_TopRightButtons]\r\n<h1>[PageName]</h1>\r\n<div class="wikitext">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten Body-Abschnitt-Ende', 11, 213, '</div><br />\r\n<table border="0" width="100%"><tr><td>[M_S_ButtonOverview]\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonForums]\r\n[M_S_ButtonForumSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_S_ButtonInfo]\r\n</td><td style="text-align: right">\r\n[M_S_ButtonUserStart]\r\n</td></tr></table>[M_S_RuntimeSec]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Head-Abschnitt', 11, 214, '<title>[PageTitle]</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Body-Abschnitt-Anfang', 11, 215, '<body>\r\n<h1>[PageTitle]</h1>\r\n<div class="wikitext">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Body-Abschnitt-Ende', 11, 216, '</div>[M_S_RuntimeSec]');

INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Info-Head', 11, 224, '<title>[BasarName]</title></head>\r\n<body>\r\n<table border="0" width="100%"><tr><td><h1>[PageTitle]</h1>\r\n</a></td>[M_T_TopRightButtons]\r\n<div class="wikitext">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Info- Body-End', 11, 225, '[M_T_BodyEndWikiTextTitleSearch]');
/*---------------*/
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Suche-Head', 11, 231, '<title>[BasarName]</title>\r\n</head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Suche Body-Start', 11, 232, '[M_T_BodyWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Standard Body-Abschnitt-Ende', 11, 233, '[M_T_BodyEndWikiTextTitleSearch]');

INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten-Head-Abschnitt', 11, 241, '<title>[PageName] (Wiki)</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Anfang', 11, 242, '<body><table border="0" width="100%"><tr><td  style="vertical-align: top">[M_S_ButtonEdit]\r\n[M_S_ButtonInfo]\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonLastChanges]</td>[M_T_TopRightButtons]\r\n<h1>[PageName]</h1>\r\n<div class="wikitext">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Ende', 11, 243, '</div>\r\n[M_S_PageLastChange]<br>\r\n<hr style="width: 100%; height: 2px;">\r\n<table width="100%" border="0"><tr><td  style="vertical-align: top">[M_S_ButtonEdit]\r\n[M_S_ButtonPageInfo]\r\n[M_S_ButtonNewWiki]\r\n</td><td style="text-align: center; vertical-align: top">[M_S_TitleSearch]\r\n</td><td style="text-align: right; vertical-align: top">\r\n[M_S_ButtonOverview]</a>\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_S_ButtonUserStart]\r\n</td><tr></table>[M_S_RuntimeSec]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Head-Abschnitt', 11, 244, '<title>[PageTitle]</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Body-Abschnitt-Anfang', 11, 245, '<body><table border="0" width="100%"><tr><td  style="vertical-align: top"><a class="wikiaction" href="[PageLink]?action=show">Verwerfen</a>\r\n[M_S_ButtonPageInfo]\r\n[M_S_ButtonSearch]</td>[M_T_TopRightButtons]\r\n<h1>[PageTitle]</h1>\r\n<div class="wikiedit">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Body-Abschnitt-Ende', 11, 246, '</div>[M_S_HintFormating][M_S_RuntimeSec]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Vorschau-Anfang', 11, 247, '</div><h1>Vorschau auf [PageName]</h1><br><div class="wikipreview">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Vorschau-Ende', 11, 248, '</div><br/><h1>[PageTitle]</h1><br><div class="wikiedit">');

# ===================================
# Daten für Tabelle `infobasar_param`
# Modul Forum:
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('BenutzerTitel', 1, 300, '10:Neuling;25:Interessent;50:strebsam;75:fleißig;100:Spezialist;150:Profi;200:Meister;500:Guru');

# Minimal-Design:
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Beitrag-Head-Abschnitt', 10, 301, '<title>Forumsbeitr&auml;ge</title></head>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forumsbeiträge-Body-Abschnitt-Anfang', 10, 302, '<body>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('ForumsbeiträgeBody-Abschnitt-Ende', 10, 303, '[M_S_BodyEnd]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Neuer-Beitrag-Head-Abschnitt', 10, 304, '<title>Neues Thema</title></head>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Neues-Thema-Body-Abschnitt-Anfang', 10, 305, '<body>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('ForumsbeiträgeBody-Abschnitt-Ende', 10, 306, '[M_S_BodyEnd]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forumantwort-Head-Abschnitt', 10, 307, '<title>Antwort erstellen</title></head>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forenantwort-Body-Abschnitt-Anfang', 10, 308, '<body>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forenantwort-Body-Abschnitt-Ende', 10, 309, '[M_S_BodyEnd]');

# PHPWiki: 
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Beitrag-Head-Abschnitt', 11, 301, '<title>Forumsbeitr&auml;ge</title></head>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forumsbeiträge-Body-Abschnitt-Anfang', 11, 302, '[M_T_BodyWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('ForumsbeiträgeBody-Abschnitt-Ende', 11, 303, '[M_T_BodyEndWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Neuer-Beitrag-Head-Abschnitt', 11, 304, '<title>[PageTitle]</title></head>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Neues-Thema-Body-Abschnitt-Anfang', 11, 305, '[M_T_BodyWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('ForumsbeiträgeBody-Abschnitt-Ende', 11, 306, '[M_T_BodyEndWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forumantwort-Head-Abschnitt', 11, 307, '<title>[PageTitle]</title></head>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forenantwort-Body-Abschnitt-Anfang', 11, 308, '[M_T_BodyWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forenantwort-Body-Abschnitt-Ende', 11, 309, '[M_S_HintFormating][M_S_RuntimeSec]');

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle infobasar_posting
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
# Daten für Tabelle infobasar_posting
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle infobasar_page
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
) TYPE=MyISAM AUTO_INCREMENT=20;

#
# Daten für Tabelle infobasar_page
#

INSERT INTO infobasar_page (id, name, type) VALUES (11, 'StartSeite', 'w');
INSERT INTO infobasar_page (id, name, type) VALUES (12, 'SandKiste', 'w');


# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle infobasar_text
#

DROP TABLE IF EXISTS infobasar_text;
CREATE TABLE infobasar_text (
  id int(11) NOT NULL auto_increment,
  page int(11) default NULL,
  type char(1) default NULL,
  text text,
  createdat datetime default NULL,
  createdby varchar(64) default NULL,
  changedat datetime NOT NULL default '0000-00-00 00:00:00',
  replacedby int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM AUTO_INCREMENT=20;

#
# Daten für Tabelle infobasar_text
#

INSERT INTO infobasar_text (page, type, text) VALUES (11, 'w', '! Wo finde ich was?\r\n\r\n* Was ist ein [Wiki]\r\n* HilfeFormatierungen\r\n* [http:!home Übersicht]\r\n* [http:!forumhome Foren-Übersicht]\r\n\r\n--------\r\nKategorieOrdnung');
INSERT INTO infobasar_text (page, type, text) VALUES (12, 'w', '! Probieren geht über studieren\r\nHier darfst Du Dich austoben und [Wiki] in Aktion erleben.\r\n\r\nEinfach ''Bearbeiten'' (oben und unten!) anklicken.\r\n----\r\nKategorieHilfe');

