# sql_update_0.6_0.7.sql
# $Id: update_0.6_0.7.sql,v 1.1 2005/01/09 16:35:27 hamatoma Exp $
# Ergänzen/Korrigieren der Datenbanktabellen von Version 0.6 auf 0.7
#


update infobasar_macro set value= '<small><a href="[M_S_BaseModule]HilfeFormatierungen]">Textformatierung:</a></small>\r\n<table border="1">\n</td><td><small><b>Im Absatz</b></small></td><td><small><b>Zeilenanfang</b></small></td>\n<td><small><b>Links</b></small></td>\n<td><small><b>Schriften</b></small></td>\n<td><small><b>Sonstiges</b></small></td></tr><tr>\n<td><small>\'\'\'<b>wichtig</b>\'\'\' (je 3 mal \')<br>\'\'<i>Zitat</i>\'\' (je 2 mal \')<br>__<u>unterstrichen</u>__ (je 2 _)<br>%%% Zeilenwechsel<br>[[!]] Zeichen !<br></small></td>\n<td><small>! &Uuml;berschrift<br>* Aufz&auml;hlung<br># num. Aufz.<br>; Einr&uuml;ckung<br>---- Linie (4 -)<br></td>\n<td><small>WikiName<br/> ["Seite"|Text]<br>!GmbH<br/>[[URL]]<br>[URL|Text]</small></td>\n<td><small>[big]Groß[/big]<br>[small]klein[/small]<br>[sup]<sup>hoch</sup>[/sup]<br>[sub]<sub>tief</sub>[/sub]<br>[tt]<tt>Fixfont</tt>[/tt]</small></td>\n<td><small>[code]<br>Quellcode<br>[/code]<br>!| Tabellenkopf<br>| Tabellenzeile<br>&lt;?plugin ...?&gt;</small></td></tr></table>\n' where name='HintFormating' and theme=1;


delete from infobasar_param where theme=1 and pos >= 100 and pos <= 199;

# Daten für Tabelle `infobasar_param`
# Alle Module, alle Designs (100-149):

INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Standard-Head', 1, 101, '<title>[BasarName]</title></head>\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Standard Body-Start', 1, 102, '[M_T_BodyWikiText]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Wiki-Seiten Body-Abschnitt-Ende', 1, 103, '[M_T_BodyEndWikiTextTitleSearch]');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Login-Head-Bodystart', 1, 105, '<title>Anmeldung fu&uml;r den InfoBasar</title>\r\n<body>\r\n<h1>Willkommen beim Infobasar</h1>');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Login-Body-End', 1, 106, '<p><small>Passwort vergessen? EMail an hamatoma AT gmx DOT de</small></p>[M_S_RuntimeSec]');

# Design Minimal:
# Alle Module:
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Design-Name', 10, 150, 'Minimal');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Header für alle Seiten', 10, 151, '<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">\r\n');
# Design PHPWiki:
# Alle Module:
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Design-Name', 11, 150, 'PHPWiki');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('Header für alle Seiten', 11, 151, '<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">\r\n');
INSERT INTO infobasar_param (name, theme, pos, text) VALUES ('CSS-Datei', 11, 152, '/infobasar/css/phpwiki.css');

