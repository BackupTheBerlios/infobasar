# param_std.sql
# $Id: param_std.sql,v 1.3 2004/06/08 11:41:41 hamatoma Exp $
# Inhalt der Tabellen infobasar_macro und infobasar_param
# Diese Werte legen die mandantenspezifischen Daten fest, z.B. die Designs.

delete from infobasar_macro where 1;

INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'HintFormating', 'Formatierungshilfe bei Wikiseitenbearbeitung', '<small><a href="[ScriptBase]HilfeFormatierungen]">Textformatierung:</a></small>\r\n<table border="1">\r\n<tr><td><small>Zeilenanfang</small></td><td><small>Im Text</small></td><td><small>Links</small></td></tr>\r\n<tr><td><small>! &Uuml;berschrift<br>* Aufz&auml;hlung<br># num. Aufz&auml;hlung<br>---- Linie (4 -)</small></td>\r\n<td><small>\'\'<i>Zitat</i>\'\' (je 2 mal \')<br>\'\'\'<b>wichtig</b>\'\'\' (je 3 mal \')<br>__<u>unterstrichen</u>__ (je 2 mal _)<br> [Newline] Zeilenwechsel</small></td>\r\n<td><small>WikiName<br/> !GmbH<br/>[URL]<br>[URL Text]</small></td>\r\n</tr></table>');

INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonEdit', 'Button Bearbeiten', '<a class="wikiaction" href="[PageName]?action=edit">Bearbeiten</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonSearch', 'Button Wikisuche', '<a class="wikiaction" href="[ScriptBase]!search">Wikisuche</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonLastChanges', 'Button Letzte Änderung', '<a class="wikiaction" href="[ScriptBase]!lastchanges">Letzte &Auml;nderungen</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonNewWiki', 'Button Neue Seite', '<a class="wikiaction" href="[ScriptBase]!newwiki">Neue Seite</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonPageInfo', 'Button Seiteninfo', '<a class="wikiaction" href="[PageName]?action=pageinfo">Info</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonOverview', 'Button Übersicht', '<a class="wikiaction" href="[ScriptBase]!home">&Uuml;berblick</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonForums', 'Button Forenübersicht', '<a class="wikiaction" href="[ScriptBase]!forumhome">Foren&uuml;bersicht</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonForumSearch', 'Button Forumsuche', '<a class="wikiaction" href="[ScriptBase]!forumsearch">Forensuche</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonInfo', 'Button Info', '<a class="wikiaction" href="[ScriptBase]!info">Info</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonUserStart', 'Button Persönliche Startseite', '<a class="wikiaction" href="[ScriptBase]!start">Pers&ouml;nliche Startseite</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonHelp', NULL, '<a class="wikiaction" href="[ScriptBase]Hilfe">Hilfe</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'ButtonAccount', NULL, '<a class="wikiaction" href="[ScriptBase]!account">Einstellungen f&uuml;r [User]</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'PageLastChange', 'Angabe der letzen Änderung', 'Letzte &Auml;nderung: [PageChangedAt]  [PageChangedBy]');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'TitleSearch', 'Titelsuche', '<form name="tsearch" action="index.php" method="post">Titel: <input type="text" name="search_titletext" size="10" maxlength="64"> <input class="wikiaction" name="search_title" value="Suchen" type="submit"></form>');


INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'TopRightButtons', 'Aktionsbuttons am Seitenkopf rechts', '<td style="text-align: center; vertical-align: top">\r\n[M_S_TitleSearch]</td>\r\n<td style="text-align: right; vertical-align: top">[M_S_ButtonHelp]\r\n[M_S_ButtonOverview]\r\n[M_S_ButtonAccount]</td>\r\n<td style="text-align: right; vertical-align: top"><img alt="Logo" src="[ScriptBase]../pic/logo.png"></td></tr></table>');

INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'BodyWikiText', NULL, '<body>\r\n<table border="0" width="100%"><tr><td><h3>[BasarName]</h3><h1>[PageTitle]</h1>\r\n</a></td>[M_T_TopRightButtons]\r\n<div class="wikitext">');

INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'BodyEndWikiText', NULL, '</div><br />\r\n<table border="0" width="100%"><tr><td>[M_S_ButtonOverview]\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonForums]\r\n[M_S_ButtonForumSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_S_ButtonInfo]\r\n</td><td style="text-align: right">\r\n[M_S_ButtonUserStart]\r\n</td></tr></table>\r\n');

INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'BodyEndWikiTextTitleSearch', NULL, '</div><br />\r\n<table border="0" width="100%"><tr><td style="vertical-align: top">[M_S_ButtonOverview]\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonForums]\r\n[M_S_ButtonForumSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_S_ButtonInfo]\r\n</td><td style="text-align: center; vertical-align: top">[M_S_TitleSearch]\r\n</td><td style="text-align: right; vertical-align: top">\r\n[M_S_ButtonUserStart]\r\n</td></tr></table>\r\n');

delete from infobasar_param where 1;
#
# Daten für Tabelle `infobasar_param`
#

INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('DB-Schema-Version', 1, 10, '1.0 (2004.04.15)');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('DB-Basisinhalt-Version', 1, 11, '1.0 (2004.04.15)');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('DB-Erweiterungen', 1, 12, 'Design-PHPWiki 1.1 (2004.06.07)');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('BasarName', 1, 13, 'InfoBasar');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('BenutzerTitel', 1, 14, '10:Neuling;25:Interessent;50:strebsam;75:fleißig;100:Spezialist;150:Profi;200:Meister;500:Guru');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Script-Basis', 1, 15, '');

/* ----------------- */
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Design-Name', 10, 100, 'Minimal');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Header für alle Seiten', 10, 101, '<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten-Head-Abschnitt', 10, 111, '<title>[PageName] (HTML)</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten Body-Abschnitt-Anfang', 10, 112, '<body>\r\n<h1>[PageName]</h1>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten Body-Abschnitt-Ende', 10, 113, '<hr style="width: 100%; height: 2px;">\r\n[M_S_PageLastChange]<br>\r\n[M_S_ButtonEdit]\r\n | [M_S_ButtonSearch]\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Head-Abschnitt', 10, 114, '<title>[PageTitle]</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Body-Abschnitt-Anfang', 10, 115, '<body>\r\n<h1>[PageTitle]</h1>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Body-Abschnitt-Ende', 10, 116, '</body>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Beitrag-Head-Abschnitt', 10, 121, '<title>Forumsbeitr&auml;ge</title></head>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forumsbeiträge-Body-Abschnitt-Anfang', 10, 122, '<body>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('ForumsbeiträgeBody-Abschnitt-Ende', 10, 123, '</body>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Neuer-Beitrag-Head-Abschnitt', 10, 124, '<title>Neues Thema</title></head>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Neues-Thema-Body-Abschnitt-Anfang', 10, 125, '<body>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('ForumsbeiträgeBody-Abschnitt-Ende', 10, 126, '</body>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forumantwort-Head-Abschnitt', 10, 127, '<title>Antwort erstellen</title></head>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forenantwort-Body-Abschnitt-Anfang', 10, 128, '<body>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forenantwort-Body-Abschnitt-Ende', 10, 129, '</body>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Login-Head-Bodystart', 10, 131, '<title>Anmeldung fu&uml;r den InfoBasar</title>\r\n<body>\r\n<h1>Willkommen beim Infobasar der TDH-Arbeitsgruppe M&uuml;nchen</h1>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Login-Body-End', 10, 132, '<p><small>Passwort vergessen? EMail an w.kappeler AT gmx DOT de</small></p>');

INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten-Head-Abschnitt', 10, 161, '<title>[PageName] (Wiki)</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Anfang', 10, 162, '<body>\r\n<h1>[PageName]</h1>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Ende', 10, 163, '<hr style="width: 100%; height: 2px;">\r\n[M_S_PageLastChange]<br>\r\n<table width="100%" border="0"><tr><td>[M_S_ButtonEdit]\r\n | [M_S_ButtonSearch]\r\n | [M_S_ButtonPageInfo]\r\n</td><td style="text-align: right;">\r\n[M_S_ButtonOverview]\r\n | [M_S_ButtonUserStart]\r\n | [M_S_ButtonForums]\r\n[M_S_ButtonHelp]\r\n | [M_S_ButtonAccount]\r\n</td><tr></table>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Head-Abschnitt', 10, 164, '<title>[PageTitle]</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Body-Abschnitt-Anfang', 10, 165, '<body>\r\n<h1>[PageTitle]</h1>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Body-Abschnitt-Ende', 10, 166, '[M_S_HintFormating]');

/* ============================= */
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Design-Name', 11, 100, 'PHPWiki');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Header für alle Seiten', 11, 101, '<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('CSS-Datei', 11, 102, '/infobasar/css/phpwiki.css');
/*----------- */

INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten-Head-Abschnitt', 11, 111, '<title>[PageName] (HTML)</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten Body-Abschnitt-Anfang', 11, 112, '<body><table border="0" width="100%"><tr><td>[M_S_ButtonEdit]\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_T_NewWiki]\r\n[M_S_ButtonPageInfo]</td>[M_T_TopRightButtons]\r\n<h1>[PageName]</h1>\r\n<div class="wikitext">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten Body-Abschnitt-Ende', 11, 113, '</div><br />\r\n<table border="0" width="100%"><tr><td>[M_S_ButtonOverview]\r\n[T_S_ButtonSearch]\r\n[M_S_ButtonForums]\r\n[M_S_ButtonForumSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_S_ButtonInfo]\r\n</td><td style="text-align: right">\r\n[M_S_ButtonUserStart]\r\n</td></tr></table>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Head-Abschnitt', 11, 114, '<title>[PageTitle]</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Body-Abschnitt-Anfang', 11, 115, '<body>\r\n<h1>[PageTitle]</h1>\r\n<div class="wikitext">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Body-Abschnitt-Ende', 11, 116, '</div>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Beitrag-Head-Abschnitt', 11, 121, '<title>Forumsbeitr&auml;ge</title></head>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forumsbeiträge-Body-Abschnitt-Anfang', 11, 122, '[M_T_BodyWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('ForumsbeiträgeBody-Abschnitt-Ende', 11, 123, '[M_T_BodyEndWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Neuer-Beitrag-Head-Abschnitt', 11, 124, '<title>[PageTitle]</title></head>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Neues-Thema-Body-Abschnitt-Anfang', 11, 125, '[M_T_BodyWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('ForumsbeiträgeBody-Abschnitt-Ende', 11, 126, '[M_T_BodyEndWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forumantwort-Head-Abschnitt', 11, 127, '<title>[PageTitle]</title></head>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forenantwort-Body-Abschnitt-Anfang', 11, 128, '[M_T_BodyWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forenantwort-Body-Abschnitt-Ende', 11, 129, '[M_S_HintFormating]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Login-Head-Bodystart', 11, 131, '<title>Anmeldung fu&uml;r den InfoBasar</title><body>\r\n<h1>Willkommen beim Infobasar der TDH-Arbeitsgruppe M&uuml;nchen</h1>\r\n<div class="wikitext">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Login-Body-End', 11, 132, '<p><small>Passwort vergessen? EMail an [AdminEMail]</small></p>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Info-Head', 11, 134, '<title>[BasarName]</title></head>\r\n<body>\r\n<table border="0" width="100%"><tr><td><h1>[PageTitle]</h1>\r\n</a></td>[M_T_TopRightButtons]\r\n<div class="wikitext">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Info- Body-End', 11, 135, '[M_T_BodyEndWikiTextTitleSearch]');
/*---------------*/
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Standard-Head', 11, 141, '<title>[BasarName]</title></head>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Standard Body-Start', 11, 142, '[M_T_BodyWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Ende', 11, 143, '[M_T_BodyEndWikiTextTitleSearch]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Suche-Head', 11, 151, '<title>[BasarName]</title>\r\n</head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Suche Body-Start', 11, 152, '[M_T_BodyWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Standard Body-Abschnitt-Ende', 11, 153, '[M_T_BodyEndWikiTextTitleSearch]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten-Head-Abschnitt', 11, 161, '<title>[PageName] (Wiki)</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Anfang', 11, 162, '<body><table border="0" width="100%"><tr><td  style="vertical-align: top">[M_S_ButtonEdit]\r\n[M_S_ButtonInfo]\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonLastChanges]</td>[M_T_TopRightButtons]\r\n<h1>[PageName]</h1>\r\n<div class="wikitext">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Ende', 11, 163, '</div>\r\n[M_S_PageLastChange]<br>\r\n<hr style="width: 100%; height: 2px;">\r\n<table width="100%" border="0"><tr><td  style="vertical-align: top">[M_S_ButtonEdit]\r\n[M_S_ButtonPageInfo]\r\n[M_S_ButtonNewWiki]\r\n</td><td style="text-align: center; vertical-align: top">[M_S_TitleSearch]\r\n</td><td style="text-align: right; vertical-align: top">\r\n[M_S_ButtonOverview]</a>\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_S_ButtonUserStart]\r\n</td><tr></table>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Head-Abschnitt', 11, 164, '<title>[PageTitle]</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Body-Abschnitt-Anfang', 11, 165, '<body><table border="0" width="100%"><tr><td  style="vertical-align: top"><a class="wikiaction" href="[PageName]?action=show">Verwerfen</a>\r\n[M_S_ButtonPageInfo]\r\n[M_S_ButtonSearch]</td>[M_T_TopRightButtons]\r\n<h1>[PageTitle]</h1>\r\n<div class="wikiedit">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Body-Abschnitt-Ende', 11, 166, '</div>[M_S_HintFormating]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Vorschau-Anfang', 11, 167, '</div><h1>Vorschau auf [PageName]</h1><br><div class="wikipreview">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Vorschau-Ende', 11, 168, '</div><br/><h1>[PageTitle]</h1><br><div class="wikiedit">');
# Lokaler Infobasar
/*
DB-Schema
DB-Basisinhalt-Version
DB-Erweiterungen
BasarName
UserTitles
ScriptBase
*/
# Daten für Tabelle `infobasar_param`
#
update infobasar_param set text='1.0 (2004.04.15)' where theme=1 and pos=10;
update infobasar_param set text='1.0 (2004.04.15)' where theme=1 and pos=11;
update infobasar_param set text='Design-PHPWiki 1.1 (2004.06.07)' where theme=1 and pos=12;
update infobasar_param set text='Matobas lokaler InfoBasar' where theme=1 and pos=13;
update infobasar_param set text='10:Neuling;25:Interessent;50:strebsam;75:fleißig;100:Spezialist;150:Profi;200:Meister;500:Guru' where theme=1 and pos=14;
update infobasar_param set text='/infobasar/index.php/' where theme=1 and pos=15;
/*
Login-Head-Bodystart
Login-Body-End
*/
update infobasar_param set text='<title>Anmeldung fu&uml;r den InfoBasar</title>\r\n<body>\r\n<h1>Willkommen beim lokalen Infobasar</h1>' where theme=10 and pos=131;
update infobasar_param set text='<p><small>Passwort vergessen? EMail an matoba AT gmx DOT de</small></p>' where pos=132;
/*
CSS-Datei
*/
update infobasar_param set text='/infobasar/css/phpwiki.css' where theme=11 and pos=102;

#insert into `infobasar_param` (theme,pos,text) values (1,15,'/index.php')
