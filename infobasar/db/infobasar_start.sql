# $Id: infobasar_start.sql,v 1.11 2005/01/17 02:21:49 hamatoma Exp $
# Initialisierung der InfoBasar-Datenbank
#
# DB-Scheme-Version: 1.0 (vom 2004.04.01)
# DB-Base-Content-Version: 1.3 vom (2005.01.14)
#
# Reservierte Makro-Praefix: base:
#
# Hinweis: Die Skin-Daten (Oberflaeche) sind in base_skin.sql
# 
# Aenderungen:
#
# DB-Content-Version: 1.3 (vom 2005.01.14)
# design_start.sql in base_skin.sql umbenannt
# Aus skin_start.sql (zurueck-)verlagert: (Makros):
#     base:BaseModule forum:ForumModule base:ScriptBase base:BasarName 
#     base:Webmaster base:DBSchemaVersion base:DBBaseContentVersion base:DBExtensions
#
# DB-Content-Version: 1.2 (vom 2005.01.11)
# Skin-Daten ausgelagert in design_start.sql (jetzt base_skin.sql).
#

# Irrelevant fuer install.php:
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
# Hinweis: Die Design-spezifischen Daten stehen in design_start.sql

INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'base:BaseModule', 'PHP-Datei Basismodul', '/index.php/');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'forum:ForumModule', 'PHP-Datei Forumsmodul', '/forum.php/');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'base:ScriptBase', 'Pfad zur PHP-Datei', '/');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'base:BasarName', 'Wird in Titelzeile angezeigt', 'InfoBasar');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'base:Webmaster', 'EMail-Adresse des Webmasters', 'hamatoma (AT) berlios (DOT) de');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'base:DBSchemaVersion', 'Version der DB-Struktur', '1.0 (2004.04.15)');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'base:DBBaseContentVersion', 'DB-Basisinhalt-Version', '1.1 (2005.01.13)');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'base:DBExtensions', 'DB-Erweiterungen, mit ; getrennt', ';minimal.skin;PHPWiki.skin;');

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

INSERT INTO infobasar_text (page, type, text) VALUES (11, 'w', '! Wo finde ich was?\r\n\r\n* Was ist ein ["Wiki"]\r\n* HilfeFormatierungen\r\n* [[http:!home|Übersicht]]\r\n* [[http:!forumhome|Foren-Übersicht]]\r\n\r\n--------\r\nKategorieOrdnung');
INSERT INTO infobasar_text (page, type, text) VALUES (12, 'w', '! Probieren geht über studieren\r\nHier darfst Du Dich austoben und ["Wiki"] in Aktion erleben.\r\n\r\nEinfach ''Bearbeiten'' (oben und unten auf der Seite!) anklicken und los geht\'s!.\r\n----\r\nKategorieHilfe');

