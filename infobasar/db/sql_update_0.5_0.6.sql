# sql_update_0.5_0.6.sql
# $Id: sql_update_0.5_0.6.sql,v 1.3 2005/01/10 20:07:16 hamatoma Exp $
# Ergänzen der Datenbanktabellen von Version 0.5 auf 0.6
#

# 2004.06.15
alter table group (
	
);
alter table infobasar_user
	add lastvisit datetime,
	add cookies char(1), /* boolean */
	add visits int(11),
	add pages int(11),
	add admin char(1) /* boolean */
;
update infobasar_user set cookies = 'j', admin = 'n' where 1;
update infobasar_user set admin = 'j' where name='admin';
 
CREATE TABLE infobasar_module (
  id int(11) NOT NULL auto_increment,
  createdat datetime default NULL,
  changedat datetime NOT NULL default '0000-00-00 00:00:00',
  moduleid varchar(255) NOT NULL,
  name varchar(64) default NULL,
  script varchar(255) default NULL,
  description varchar(255) NOT NULL default '',
  paramfrom int(11),
  paramcount int(11),
  release varchar(64),
  tables text,
  files text,
  directories text,
  dependencies text, // Module, die vorhanden sein müssen
  PRIMARY KEY  (id)
) TYPE=MyISAM AUTO_INCREMENT=1 ;


insert into infobasar_base (moduleid, name, script, description, paramfrom, paramto, release, files, directories, dependencies, paramfrom, paramcount) values ('basis.infobasar.hamatoma.de', 'basis', 'index.php', 'Wiki- und HTML-Seiten', 100, '0.6.1', 'classes.php;config.php;db_mysql.php;index.php;gui.php', '', '', 100, 50);
insert into infobasar_module (moduleid, name, script, description, themepos, release, files, directories, dependencies, paramfrom, paramcount) values ('admin.infobasar.hamatoma.de', 'admin', 'admin.php', 'Grundlegende Verwaltung Infobasar', '0.6.1', 'admin.php', '', '', 0, 0);
insert into infobasar_module (moduleid, name, script, description, themepos, release, files, directories, dependencies, paramfrom, paramcount) values ('forum.infobasar.hamatoma.de','forum', 'forum.php', 'Foren im InfoBasar', '0.6.1', 'admin.php', '', '', 140, 10);

CREATE TABLE infobasar_right (
  id int(11) NOT NULL auto_increment,
  createdat datetime default NULL,
  changedat datetime NOT NULL default '0000-00-00 00:00:00',
  name varchar(64) NOT NULL,
  fullname varchar(64) NOT NULL,
  description varchar(255) default NULL,
  put varchar(16) default NULL,
  get varchar(16) default NULL,
  new varchar(16) default NULL,
  del varchar(16) default NULL,
  lock varchar(16) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

insert into infobasar_right (name, fullname, description, put, get, new, del, lock) values ("myaccount", "myaccount", "Eigene Kontodaten", "Ändern", "Lesen", null, null, null);
insert into infobasar_right (name, fullname, description, put, get, new, lock) values ("accounts", "accounts", "Andere Benutzerkonten", "Ändern", "Lesen", "Hinzufügen", "Löschen", "Sperren");
insert into infobasar_right (name, fullname, description, put, get, new, lock) values ("html", "html", "Zugriff auf HTML-Seiten", "Ändern", "Lesen", "Erzeugen", "Löschen", "Sperren");
insert into infobasar_right (name, fullname, description, put, get, new, lock) values ("wiki", "wiki", "Zugriff auf WikiL-Seiten", "Ändern", "Lesen", "Erzeugen", "Löschen", "Sperren");

CREATE TABLE infobasar_division (
  id int(11) NOT NULL auto_increment,
  createdat datetime default NULL,
  changedat datetime NOT NULL default '0000-00-00 00:00:00',
  name varchar(64) NOT NULL,
  fullname varchar(64) NOT NULL,
  description varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
