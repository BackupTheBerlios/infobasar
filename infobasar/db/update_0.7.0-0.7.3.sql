# $Id: update_0.7.0-0.7.3.sql,v 1.1 2005/01/14 12:58:01 hamatoma Exp $
# Update der Versionen 0.7.0 bis 0.7.2 auf 0.7.3
#


INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'BaseModule', 'PHP-Datei Basismodul', '/index.php/');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ForumModule', 'PHP-Datei Forumsmodul', '/forum.php/');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ScriptBase', 'Pfad zur PHP-Datei', '/');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'BasarName', 'Wird in Titelzeile angezeigt', 'InfoBasar');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'Webmaster', 'EMail-Adresse des Webmasters', 'hamatoma (AT) berlios (DOT) de');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'DBSchemaVersion', 'Version der DB-Struktur', '1.0 (2004.04.15)');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'DBBaseContentVersion', 'DB-Basisinhalt-Version', '1.1 (2005.01.13)');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'DBExtensions', 'DB-Erweiterungen, mit ; getrennt', ';Skin-,Minimal;Skin-PHPWiki;');
