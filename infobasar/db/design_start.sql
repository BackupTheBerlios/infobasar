# $Id: design_start.sql,v 1.1 2005/01/13 03:28:36 hamatoma Exp $
# Initialisierung der Design-spezifischen Teile der InfoBasar-Datenbank.
# Dies betrifft die Makros und die Parameter.
#
# Daten für Tabelle infobasar_param
#

# Sicherheitshalber alles loeschen:
delete from infobasar_param;
delete from infobasar_macro;

# ===================================
# Daten für Tabelle `infobasar_param`
# Alle Module, alle Designs (100-149):
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Standard-Head', 1, 101, '<title>[BasarName]</title></head>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Standard Body-Start', 1, 102, '[M_T_BodyWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Ende', 1, 103, '[M_T_BodyEndWikiTextTitleSearch]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Login-Head-Bodystart', 1, 105, '<title>Anmeldung fu&uml;r den InfoBasar</title>\r\n<body>\r\n<h1>Willkommen beim Infobasar</h1><div>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Login-Body-End', 1, 106, '<p><small>Passwort vergessen? EMail an hamatoma AT gmx DOT de</small></p>[M_S_RuntimeSec]</div>');


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
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten Body-Abschnitt-Anfang', 10, 212, '<body>\r\n<h1>[PageName]</h1><div>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten Body-Abschnitt-Ende', 10, 213, '<hr style="width: 100%; height: 2px;">\r\n[M_S_PageLastChange]<br>\r\n[M_S_ButtonEdit]\r\n | [M_S_ButtonSearch]\r\n[M_S_RuntimeSec]</div>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Head-Abschnitt', 10, 214, '<title>[PageTitle]</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Body-Abschnitt-Anfang', 10, 215, '<body>\r\n<h1>[PageTitle]</h1><div>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Body-Abschnitt-Ende', 10, 216, '[M_S_BodyEnd]</div>');

INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten-Head-Abschnitt', 10, 241, '<title>[PageName] (Wiki)</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Anfang', 10, 242, '<body>\r\n<h1>[PageName]</h1>\r\n<div>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Ende', 10, 243, '<hr style="width: 100%; height: 2px;">\r\n[M_S_PageLastChange]<br>\r\n<table width="100%" border="0"><tr><td>[M_S_ButtonEdit]\r\n | [M_S_ButtonSearch]\r\n | [M_S_ButtonPageInfo]\r\n</td><td style="text-align: right;">\r\n[M_S_ButtonOverview]\r\n | [M_S_ButtonUserStart]\r\n | [M_S_ButtonForums]\r\n[M_S_ButtonHelp]\r\n | [M_S_ButtonAccount]\r\n</td></tr></table>[M_S_RuntimeSec]</div>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Head-Abschnitt', 10, 244, '<title>[PageTitle]</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Body-Abschnitt-Anfang', 10, 245, '<body>\r\n<h1>[PageTitle]</h1><div>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Body-Abschnitt-Ende', 10, 246, '[M_S_HintFormating][M_S_RuntimeSec]</div>');

/* ============================= */
# Design PHPWiki:
# Alle Module:
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Design-Name', 11, 150, 'PHPWiki');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Header für alle Seiten', 11, 151, '<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('CSS-Datei', 11, 152, '/infobasar/css/phpwiki.css');

# Basismodul:
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten-Head-Abschnitt', 11, 211, '<title>[PageName] (HTML)</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten Body-Abschnitt-Anfang', 11, 212, '<body><div><table border="0" width="100%"><tr><td>[M_S_ButtonEdit]\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_S_ButtonNewWiki]\r\n[M_S_ButtonPageInfo]</td>[M_T_TopRightButtons]\r\n<h1>[PageName]</h1>\r\n<div class="wikitext">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten Body-Abschnitt-Ende', 11, 213, '</div><br />\r\n<table border="0" width="100%"><tr><td>[M_S_ButtonOverview]\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonForums]\r\n[M_S_ButtonForumSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_S_ButtonInfo]\r\n</td><td style="text-align: right">\r\n[M_S_ButtonUserStart]\r\n</td></tr></table>[M_S_RuntimeSec]</div>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Head-Abschnitt', 11, 214, '<title>[PageTitle]</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Body-Abschnitt-Anfang', 11, 215, '<body>\r\n<h1>[PageTitle]</h1>\r\n<div class="wikitext">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Body-Abschnitt-Ende', 11, 216, '</div><div>[M_S_RuntimeSec]</div>');

INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Info-Head', 11, 224, '<title>[BasarName]</title></head>\r\n<body><div>\r\n<table border="0" width="100%"><tr><td><h1>[PageTitle]</h1>\r\n</td>[M_T_TopRightButtons]\r\n<div class="wikitext">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Info- Body-End', 11, 225, '[M_T_BodyEndWikiTextTitleSearch]</div>');
/*---------------*/
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Suche-Head', 11, 231, '<title>[BasarName]</title>\r\n</head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Suche Body-Start', 11, 232, '[M_T_BodyWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Standard Body-Abschnitt-Ende', 11, 233, '[M_T_BodyEndWikiTextTitleSearch]');

INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten-Head-Abschnitt', 11, 241, '<title>[PageName] (Wiki)</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Anfang', 11, 242, '<body><div><table border="0" width="100%"><tr><td  style="vertical-align: top">[M_S_ButtonEdit]\r\n[M_S_ButtonInfo]\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonLastChanges]</td>[M_T_TopRightButtons]\r\n<h1>[PageName]</h1>\r\n<div class="wikitext">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Ende', 11, 243, '</div>\r\n[M_S_PageLastChange]<br>\r\n<hr style="width: 100%; height: 2px;">\r\n<table width="100%" border="0"><tr><td  style="vertical-align: top">[M_S_ButtonEdit]\r\n[M_S_ButtonPageInfo]\r\n[M_S_ButtonNewWiki]\r\n</td><td style="text-align: center; vertical-align: top">[M_S_TitleSearch]\r\n</td><td style="text-align: right; vertical-align: top">\r\n[M_S_ButtonOverview]\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_S_ButtonUserStart]\r\n</td></tr></table>[M_S_RuntimeSec]</div>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Head-Abschnitt', 11, 244, '<title>[PageTitle]</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Body-Abschnitt-Anfang', 11, 245, '<body><div><table border="0" width="100%"><tr><td  style="vertical-align: top"><a class="wikiaction" href="[PageLink]?action=show">Verwerfen</a>\r\n[M_S_ButtonPageInfo]\r\n[M_S_ButtonSearch]</td>[M_T_TopRightButtons]\r\n<h1>[PageTitle]</h1>\r\n<div class="wikiedit">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Body-Abschnitt-Ende', 11, 246, '</div>[M_S_HintFormating][M_S_RuntimeSec]</div>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Vorschau-Anfang', 11, 247, '</div><h1>Vorschau auf [PageName]</h1><br><div class="wikipreview">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Vorschau-Ende', 11, 248, '</div><br/><h1>[PageTitle]</h1><br><div class="wikiedit">');

# ===================================
# Daten für Tabelle `infobasar_param`
# Modul Forum:
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('BenutzerTitel', 1, 300, '10:Neuling;25:Interessent;50:strebsam;75:fleißig;100:Spezialist;150:Profi;200:Meister;500:Guru');
# $Id: design_start.sql,v 1.1 2005/01/13 03:28:36 hamatoma Exp $
# Initialisierung der Designs minimal und PHPWiki
#

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

# ==========================
# Makros:
# ==========================
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
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'TitleSearch', 'Titelsuche', '<form action="index.php" method="post"><div>Titel: <input type="text" name="search_titletext" size="10" maxlength="64"> <input class="wikiaction" name="search_title" value="Suchen" type="submit"></div></form>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'RuntimeSec', NULL, '<br>Der Seitenaufbau benötigte [RuntimeSecMilli] sec auf dem Server.');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'BodyEnd', NULL, '[M_S_RuntimeSec]</body>\r\n');

INSERT INTO infobasar_macro (theme,name,description,value) VALUES (10, 'TopRightButtons', 'Aktionsbuttons am Seitenkopf rechts', '');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (10, 'BodyWikiText', NULL, '<body>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (10, 'BodyEndWikiText', NULL, '<br>[M_S_ButtonOverview] [M_S_RuntimeSec]');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (10, 'BodyEndWikiTextTitleSearch', NULL, '<br>\r\n[M_S_RuntimeSec]');
#                                                                  [M_T_BodyEndWikiTextTitleSearch]
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (10, 'BodyWikiText', NULL, '<body><div>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (10, 'BodyEndWikiText', NULL, '<br>[M_S_ButtonOverview] [M_S_RuntimeSec]<div>');

INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'TopRightButtons', 'Aktionsbuttons am Seitenkopf rechts', '<td style="text-align: center; vertical-align: top">\r\n[M_S_TitleSearch]</td>\r\n<td style="text-align: right; vertical-align: top">[M_S_ButtonHelp]\r\n[M_S_ButtonOverview]\r\n[M_S_ButtonAccount]</td>\r\n<td style="text-align: right; vertical-align: top"><img alt="Logo" src="[M_S_ScriptBase]pic/logo.png"></td></tr></table>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'BodyWikiText', NULL, '<body><div>\r\n<table border="0" width="100%"><tr><td><h3>[BasarName]</h3><h1>[PageTitle]</h1>\r\n</td>[M_T_TopRightButtons]\r\n<div class="wikitext">');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'BodyEndWikiText', NULL, '</div><br />\r\n<table border="0" width="100%"><tr><td>[M_S_ButtonOverview]\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonForums]\r\n[M_S_ButtonForumSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_S_ButtonInfo]\r\n</td><td style="text-align: right">\r\n[M_S_ButtonUserStart]\r\n</td></tr></table>\r\n[M_S_RuntimeSec]</div>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'BodyEndWikiTextTitleSearch', NULL, '</div><br />\r\n<table border="0" width="100%"><tr><td style="vertical-align: top">[M_S_ButtonOverview]\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonForums]\r\n[M_S_ButtonForumSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_S_ButtonInfo]\r\n</td><td style="text-align: center; vertical-align: top">[M_S_TitleSearch]\r\n</td><td style="text-align: right; vertical-align: top">\r\n[M_S_ButtonUserStart]\r\n</td></tr></table>\r\n[M_S_RuntimeSec]</div>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'BodyWikiText', NULL, '<body><div>\r\n<table border="0" width="100%"><tr><td><h3>[BasarName]</h3><h1>[PageTitle]</h1>\r\n</td>[M_T_TopRightButtons]\r\n<div class="wikitext">');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'BodyEndWikiText', NULL, '</div><br />\r\n<table border="0" width="100%"><tr><td>[M_S_ButtonOverview]\r\n[M_S_ButtonSearch]\r\n[M_S_ButtonForums]\r\n[M_S_ButtonForumSearch]\r\n[M_S_ButtonLastChanges]\r\n[M_S_ButtonInfo]\r\n</td><td style="text-align: right">\r\n[M_S_ButtonUserStart]\r\n</td></tr></table></div>\r\n');
