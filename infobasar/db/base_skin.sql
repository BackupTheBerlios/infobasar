# $Id: base_skin.sql,v 1.2 2005/01/17 02:20:41 hamatoma Exp $
# Initialisierung der Oberflächen-spezifischen Teile der InfoBasar-Datenbank. (Basis-Ausstattung)
# Dies betrifft die Makros und die Parameter.
#
# Daten für Tabelle infobasar_param
#
# Skin-minimal-Version: 2.0 (vom 2005.01.14)
# Skin-PHPWiki-Version: 2.0 (vom 2005.01.14)
# Reservierte Makro-Praefixe: minimal.skin: und PHPWiki.skin:
#
# Aenderungen:
#
# Umbenannt von design_start.sql
#
# Skin-minimal-Version: 2.0 (vom 2005.01.14)
# Unterstuetzt ab PHPVersion 0.7.3
# Makros haben jetzt keine Praefixe M_S_ und M_T_ mehr.
# Makros haben die Form url:name
# Validiertes HTML 
# Neu: Makros: Skin-minimal Skin-PHPWiki
#
# Skin-minimal-Version: 2.0 (vom 2005.01.14)
# Unterstuetzt ab PHPVersion 0.7.3
# Makros haben jetzt keine Praefixe M_S_ und M_T_ mehr.
# Makros haben die Form url:name
# Validiertes HTML 
#
# Hinweis:
# Jedes <body>-Element zieht ein <div> nach sich, ebenso jedes </body> ein vorausgehendes </div>
# Die Makros BasarName und Webmaster sind in infobasar_start.sql

# Relevante Daten vorher loeschen:
delete from infobasar_param;
delete from infobasar_macro where name like 'minimal.skin:%' or name like 'PHPWiki.skin:%';

# ===================================
# Daten für Tabelle `infobasar_param`
# Alle Module, alle Skins (100-149):
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Standard-Head', 1, 101, '<title>[base:BasarName]</title></head>\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Standard Body-Start', 1, 102, '[minimal.skin:BodyWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Standard Body-Ende', 1, 103, '[minimal.skin:BodyEndWikiTextTitleSearch]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Login-Head', 1, 105, '<title>Anmeldung [base:BasarName]</title></head>\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Login-Body-Start', 1, 106, '<body><div>\n<table border="0" width="100%"><tr><td><h1>Willkommen bei [base:BasarName]</h1></td><td style="text-align: right;"><img alt="Logo" src="[base:ScriptBase]pic/logo.png"></td></tr></table>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Login-Body-End', 1, 107, '<p><small>Passwort vergessen? EMail an [base:Webmaster]</small></p>[minimal.skin:RuntimeSec]');

INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Admin-Standard-Head', 1, 111, '<title>Administration [base:BasarName] ([base:PageTitle])</title></head>\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Admin-Standard Body-Start', 1, 112, '<body><div><h1>[base:PageTitle] ([base:BasarName])</h1>');
// Admin-Standard Body-Start wird von modStandardLinks() erledigt

# ===================================
# Daten für Tabelle `infobasar_param`
# Modul Basis:

/* ----------------- */
# Skin-Minimal:
# Alle Module:
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Skin-Name', 10, 150, 'Minimal');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Header für alle Seiten', 10, 151, '<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Makro-Pattern', 10, 153, '');

# Basismodul:
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten-Head-Abschnitt', 10, 211, '<title>[base:PageName] (HTML)</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten Body-Abschnitt-Anfang', 10, 212, '<body><div>\n<h1>[base:PageName]</h1>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten Body-Abschnitt-Ende', 10, 213, '<hr style="width: 100%; height: 2px;">\n[minimal.skin:PageLastChange]<br>\n[minimal.skin:ButtonEdit]\n | [minimal.skin:ButtonSearch]\n[minimal.skin:RuntimeSec]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Head-Abschnitt', 10, 214, '<title>[base:PageTitle]</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Body-Abschnitt-Anfang', 10, 215, '<body><div>\n<h1>[base:PageTitle]</h1>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Body-Abschnitt-Ende', 10, 216, '[minimal.skin:BodyEnd]');

INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten-Head-Abschnitt', 10, 241, '<title>[base:PageName] (Wiki)</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Anfang', 10, 242, '<body><div>\n<h1>[base:PageName]</h1>\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Ende', 10, 243, '<hr style="width: 100%; height: 2px;">\n[minimal.skin:PageLastChange]<br>\n<table width="100%" border="0"><tr><td>[minimal.skin:ButtonEdit]\n | [minimal.skin:ButtonSearch]\n | [minimal.skin:ButtonPageInfo]\n</td><td style="text-align: right;">\n[minimal.skin:ButtonOverview]\n | [minimal.skin:ButtonUserStart]\n | [minimal.skin:ButtonForums]\n[minimal.skin:ButtonHelp]\n | [minimal.skin:ButtonAccount]\n</td></tr></table>[minimal.skin:RuntimeSec]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Head-Abschnitt', 10, 244, '<title>[base:PageTitle]</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Body-Abschnitt-Anfang', 10, 245, '<body><div>\n<h1>[base:PageTitle]</h1>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Body-Abschnitt-Ende', 10, 246, '[minimal.skin:HintFormating][minimal.skin:RuntimeSec]');

/* ============================= */
# Skin-PHPWiki:
# Alle Module:
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Skin-Name', 11, 150, 'PHPWiki');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Header für alle Seiten', 11, 151, '<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('CSS-Datei', 11, 152, '/infobasar/css/phpwiki.css');

# Basismodul:
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten-Head-Abschnitt', 11, 211, '<title>[base:PageName] (HTML)</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten Body-Abschnitt-Anfang', 11, 212, '<body><div><table border="0" width="100%"><tr><td>[minimal.skin:ButtonEdit]\n[minimal.skin:ButtonSearch]\n[minimal.skin:ButtonLastChanges]\n[minimal.skin:ButtonNewWiki]\n[minimal.skin:ButtonPageInfo]</td>[minimal.skin:TopRightButtons]\n<h1>[base:PageName]</h1>\n<div class="wikitext">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Seiten Body-Abschnitt-Ende', 11, 213, '<br />\n<table border="0" width="100%"><tr><td>[minimal.skin:ButtonOverview]\n[minimal.skin:ButtonSearch]\n[minimal.skin:ButtonForums]\n[minimal.skin:ButtonForumSearch]\n[minimal.skin:ButtonLastChanges]\n[minimal.skin:ButtonInfo]\n</td><td style="text-align: right">\n[minimal.skin:ButtonUserStart]\n</td></tr></table>[minimal.skin:RuntimeSec]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Head-Abschnitt', 11, 214, '<title>[base:PageTitle]</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Body-Abschnitt-Anfang', 11, 215, '<body><div>\n<h1>[base:PageTitle]</h1>\n<div class="wikitext">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('HTML-Änderung Body-Abschnitt-Ende', 11, 216, '</div>[minimal.skin:RuntimeSec]');

INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Info-Head', 11, 224, '<title>[base:BasarName]</title></head>\n<body><div>\n<table border="0" width="100%"><tr><td><h1>[base:PageTitle]</h1>\n</td>[minimal.skin:TopRightButtons]\n<div class="wikitext">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Info- Body-End', 11, 225, '[minimal.skin:BodyEndWikiTextTitleSearch]');
/*---------------*/
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Suche-Head', 11, 231, '<title>[base:BasarName]</title>\n</head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Suche Body-Start', 11, 232, '[minimal.skin:BodyWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Standard Body-Abschnitt-Ende', 11, 233, '[minimal.skin:BodyEndWikiTextTitleSearch]');

INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten-Head-Abschnitt', 11, 241, '<title>[base:PageName] (Wiki)</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Anfang', 11, 242, '<body><div><table border="0" width="100%"><tr><td  style="vertical-align: top">[minimal.skin:ButtonEdit]\n[minimal.skin:ButtonInfo]\n[minimal.skin:ButtonSearch]\n[minimal.skin:ButtonLastChanges]</td>[minimal.skin:TopRightButtons]\n<h1>[base:PageName]</h1>\n<div class="wikitext">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Ende', 11, 243, '</div>\n[minimal.skin:PageLastChange]<br>\n<hr style="width: 100%; height: 2px;">\n<table width="100%" border="0"><tr><td  style="vertical-align: top">[minimal.skin:ButtonEdit]\n[minimal.skin:ButtonPageInfo]\n[minimal.skin:ButtonNewWiki]\n</td><td style="text-align: center; vertical-align: top">[minimal.skin:TitleSearch]\n</td><td style="text-align: right; vertical-align: top">\n[minimal.skin:ButtonOverview]\n[minimal.skin:ButtonSearch]\n[minimal.skin:ButtonLastChanges]\n[minimal.skin:ButtonUserStart]\n</td></tr></table>[minimal.skin:RuntimeSec]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Head-Abschnitt', 11, 244, '<title>[base:PageTitle]</title></head>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Body-Abschnitt-Anfang', 11, 245, '<body><div><table border="0" width="100%"><tr><td  style="vertical-align: top"><a class="wikiaction" href="[base:PageLink]?action=show">Verwerfen</a>\n[minimal.skin:ButtonPageInfo]\n[minimal.skin:ButtonSearch]</td>[minimal.skin:TopRightButtons]\n<h1>[base:PageTitle]</h1>\n<div class="wikiedit">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Änderung Body-Abschnitt-Ende', 11, 246, '</div>[minimal.skin:HintFormating][minimal.skin:RuntimeSec]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Vorschau-Anfang', 11, 247, '</div><h1>Vorschau auf [base:PageName]</h1><br><div class="wikipreview">');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Vorschau-Ende', 11, 248, '</div><br/><h1>[base:PageTitle]</h1><br><div class="wikiedit">');

# ===================================
# Daten für Tabelle `infobasar_param`
# Modul Forum:
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('BenutzerTitel', 1, 300, '10:Neuling;25:Interessent;50:strebsam;75:fleißig;100:Spezialist;150:Profi;200:Meister;500:Guru');
# Initialisierung der Skins minimal und PHPWiki
#

# Skin minimal:
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Beitrag-Head-Abschnitt', 10, 301, '<title>Forumsbeitr&auml;ge</title></head>\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forumsbeiträge-Body-Abschnitt-Anfang', 10, 302, '<body><div>\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('ForumsbeiträgeBody-Abschnitt-Ende', 10, 303, '[minimal.skin:BodyEnd]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Neuer-Beitrag-Head-Abschnitt', 10, 304, '<title>Neues Thema</title></head>\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Neues-Thema-Body-Abschnitt-Anfang', 10, 305, '<body><div>\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('ForumsbeiträgeBody-Abschnitt-Ende', 10, 306, '[minimal.skin:BodyEnd]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forumantwort-Head-Abschnitt', 10, 307, '<title>Antwort erstellen</title></head>\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forenantwort-Body-Abschnitt-Anfang', 10, 308, '<body><div>\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forenantwort-Body-Abschnitt-Ende', 10, 309, '[minimal.skin:BodyEnd]');

# Skin PHPWiki: 
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Beitrag-Head-Abschnitt', 11, 301, '<title>Forumsbeitr&auml;ge</title></head>\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forumsbeiträge-Body-Abschnitt-Anfang', 11, 302, '[minimal.skin:BodyWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('ForumsbeiträgeBody-Abschnitt-Ende', 11, 303, '[minimal.skin:BodyEndWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Neuer-Beitrag-Head-Abschnitt', 11, 304, '<title>[base:PageTitle]</title></head>\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Neues-Thema-Body-Abschnitt-Anfang', 11, 305, '[minimal.skin:BodyWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('ForumsbeiträgeBody-Abschnitt-Ende', 11, 306, '[minimal.skin:BodyEndWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forumantwort-Head-Abschnitt', 11, 307, '<title>[base:PageTitle]</title></head>\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forenantwort-Body-Abschnitt-Anfang', 11, 308, '[minimal.skin:BodyWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Forenantwort-Body-Abschnitt-Ende', 11, 309, '[minimal.skin:HintFormating][minimal.skin:RuntimeSec]');

# ==========================
# Makros:
# ==========================

INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'minimal.skin:about', 'Minimale Oberfläche', 'Erstellt von hamatoma (AT) berlios (DOT) de');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'minimal.skin:version', 'Version', '2.0 (2005.01.14)');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'PHPWiki.skin:about', 'Oberfläche ähnlich PHPWiki', 'Erstellt von hamatoma (AT) berlios (DOT) de');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'PHPWiki.skin:version', 'Version', '2.0 (2005.01.14)');

INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'minimal.skin:HintFormating', 'Formatierungshilfe bei Wikiseitenbearbeitung', '<small><a href="[base:BaseModule]HilfeFormatierungen]">Textformatierung:</a></small>\n<table border="1">\n</td><td><small><b>Im Absatz</b></small></td><td><small><b>Zeilenanfang</b></small></td>\n<td><small><b>Links</b></small></td>\n<td><small><b>Schriften</b></small></td>\n<td><small><b>Sonstiges</b></small></td></tr><tr>\n<td><small>\'\'\'<b>wichtig</b>\'\'\' (je 3 mal \')<br>\'\'<i>Zitat</i>\'\' (je 2 mal \')<br>__<u>unterstrichen</u>__ (je 2 _)<br>%%% Zeilenwechsel<br>[[!]] Zeichen !<br></small></td>\n<td><small>! &Uuml;berschrift<br>* Aufz&auml;hlung<br># num. Aufz.<br>; Einr&uuml;ckung<br>---- Linie (4 -)<br></td>\n<td><small>WikiName<br/> ["Seite"|Text]<br>!GmbH<br/>[[URL]]<br>[URL|Text]</small></td>\n<td><small>[big]Groß[/big]<br>[small]klein[/small]<br>[sup]<sup>hoch</sup>[/sup]<br>[sub]<sub>tief</sub>[/sub]<br>[tt]<tt>Fixfont</tt>[/tt]</small></td>\n<td><small>[code]<br>Quellcode<br>[/code]<br>!| Tabellenkopf<br>| Tabellenzeile<br>&lt;?plugin ...?&gt;</small></td></tr></table>\n');

INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'minimal.skin:ButtonEdit', 'Button Bearbeiten', '<a class="wikiaction" href="[baes:PageLink]?action=edit">Bearbeiten</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'minimal.skin:ButtonSearch', 'Button Wikisuche', '<a class="wikiaction" href="[base:BaseModule]!search">Wikisuche</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'minimal.skin:ButtonLastChanges', 'Button Letzte Änderung', '<a class="wikiaction" href="[base:BaseModule]!lastchanges">Letzte &Auml;nderungen</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'minimal.skin:ButtonNewWiki', 'Button Neue Seite', '<a class="wikiaction" href="[base:BaseModule]!newwiki">Neue Seite</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'minimal.skin:ButtonPageInfo', 'Button Seiteninfo', '<a class="wikiaction" href="[base:PageLink]?action=pageinfo">Info</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'minimal.skin:ButtonOverview', 'Button Überblick', '<a class="wikiaction" href="[base:BaseModule]!home">&Uuml;berblick</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'minimal.skin:ButtonForums', 'Button Forenübersicht', '<a class="wikiaction" href="[minimal.skin:ForumModule]!forumhome">Foren&uuml;bersicht</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'minimal.skin:ButtonForumSearch', 'Button Forumsuche', '<a class="wikiaction" href="[minimal.skin:ForumModule]!forumsearch">Forensuche</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'minimal.skin:ButtonInfo', 'Button Info', '<a class="wikiaction" href="[base:BaseModule]!info">Info</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'minimal.skin:ButtonUserStart', 'Button Persönliche Startseite', '<a class="wikiaction" href="[base:BaseModule]!start">Pers&ouml;nliche Startseite</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'minimal.skin:ButtonHelp', 'Button Hilfe', '<a class="wikiaction" href="[base:BaseModule]Hilfe">Hilfe</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'minimal.skin:ButtonAccount', 'Button Einstellungen', '<a class="wikiaction" href="[base:BaseModule]!account">Einstellungen f&uuml;r [base:User]</a>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'base:PageLastChange', 'Angabe der letzen Änderung', 'Letzte &Auml;nderung: [base:PageChangedAt]  [base:PageChangedBy]');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'minimal.skin:TitleSearch', 'Titelsuche', '<form action="index.php" method="post"><div>Titel: <input type="text" name="search_titletext" size="10" maxlength="64"> <input class="wikiaction" name="search_title" value="Suchen" type="submit"></div></form>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'minimal.skin:RuntimeSec', NULL, '<br>Der Seitenaufbau benötigte [base:RuntimeSecMilli] sec auf dem Server.');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (1, 'minimal.skin:BodyEnd', NULL, '[minimal.skin:RuntimeSec]</body>\n');

INSERT INTO infobasar_macro (theme,name,description,value) VALUES (10, 'minimal.skin:TopRightButtons', 'Aktionsbuttons am Seitenkopf rechts', '');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (10, 'minimal.skin:BodyWikiText', NULL, '<body><div>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (10, 'minimal.skin:BodyEndWikiText', NULL, '<br>[minimal.skin:ButtonOverview] [minimal.skin:RuntimeSec]');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (10, 'minimal.skin:BodyEndWikiTextTitleSearch', NULL, '<br>\n[minimal.skin:RuntimeSec]');
#                                                                  [minimal.skin:BodyEndWikiTextTitleSearch]
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (10, 'minimal.skin:BodyWikiText', NULL, '<body><div>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (10, 'minimal.skin:BodyEndWikiText', NULL, '<br>[minimal.skin:ButtonOverview] [minimal.skin:RuntimeSec]');

INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'minimal.skin:TopRightButtons', 'Aktionsbuttons am Seitenkopf rechts', '<td style="text-align: center; vertical-align: top">\n[minimal.skin:TitleSearch]</td>\n<td style="text-align: right; vertical-align: top">[minimal.skin:ButtonHelp]\n[minimal.skin:ButtonOverview]\n[minimal.skin:ButtonAccount]</td>\n<td style="text-align: right; vertical-align: top"><img alt="Logo" src="[base:ScriptBase]pic/logo.png"></td></tr></table>');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'minimal.skin:BodyWikiText', NULL, '<body><div>\n<table border="0" width="100%"><tr><td><h3>[base:BasarName]</h3><h1>[base:PageTitle]</h1>\n</td>[minimal.skin:TopRightButtons]\n<div class="wikitext">');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'minimal.skin:BodyEndWikiText', NULL, '</div><br />\n<table border="0" width="100%"><tr><td>[minimal.skin:ButtonOverview]\n[minimal.skin:ButtonSearch]\n[minimal.skin:ButtonForums]\n[minimal.skin:ButtonForumSearch]\n[minimal.skin:ButtonLastChanges]\n[minimal.skin:ButtonInfo]\n</td><td style="text-align: right">\n[minimal.skin:ButtonUserStart]\n</td></tr></table>\n[minimal.skin:RuntimeSec]');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'minimal.skin:BodyEndWikiTextTitleSearch', NULL, '</div><br />\n<table border="0" width="100%"><tr><td style="vertical-align: top">[minimal.skin:ButtonOverview]\n[minimal.skin:ButtonSearch]\n[minimal.skin:ButtonForums]\n[minimal.skin:ButtonForumSearch]\n[minimal.skin:ButtonLastChanges]\n[minimal.skin:ButtonInfo]\n</td><td style="text-align: center; vertical-align: top">[minimal.skin:TitleSearch]\n</td><td style="text-align: right; vertical-align: top">\n[minimal.skin:ButtonUserStart]\n</td></tr></table>\n[minimal.skin:RuntimeSec]');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'minimal.skin:BodyWikiText', NULL, '<body><div>\n<table border="0" width="100%"><tr><td><h3>[base:BasarName]</h3><h1>[base:PageTitle]</h1>\n</td>[minimal.skin:TopRightButtons]\n<div class="wikitext">');
INSERT INTO infobasar_macro (theme,name,description,value) VALUES (11, 'minimal.skin:BodyEndWikiText', NULL, '</div><br />\n<table border="0" width="100%"><tr><td>[minimal.skin:ButtonOverview]\n[minimal.skin:ButtonSearch]\n[minimal.skin:ButtonForums]\n[minimal.skin:ButtonForumSearch]\n[minimal.skin:ButtonLastChanges]\n[minimal.skin:ButtonInfo]\n</td><td style="text-align: right">\n[minimal.skin:ButtonUserStart]\n</td></tr></table></div>\n');
