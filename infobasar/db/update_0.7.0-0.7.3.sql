# $Id: update_0.7.0-0.7.3.sql,v 1.2 2005/01/17 02:22:19 hamatoma Exp $
# Update der Versionen 0.7.0 bis 0.7.2 auf 0.7.3
#

delete from infobasar_macro where theme=1 and name like 'base:%';
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'base:BaseModule', 'PHP-Datei Basismodul', '/index.php/');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'forum:ForumModule', 'PHP-Datei Forumsmodul', '/forum.php/');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'base:ScriptBase', 'Pfad zur PHP-Datei', '/');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'base:BasarName', 'Wird in Titelzeile angezeigt', 'InfoBasar');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'base:Webmaster', 'EMail-Adresse des Webmasters', 'hamatoma (AT) berlios (DOT) de');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'base:DBSchemaVersion', 'Version der DB-Struktur', '1.0 (2004.04.15)');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'base:DBBaseContentVersion', 'DB-Basisinhalt-Version', '1.1 (2005.01.13)');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'base:DBExtensions', 'DB-Erweiterungen, mit ; getrennt', ';minimal.skin;PHPWiki.skin;');
