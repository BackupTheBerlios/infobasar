USE InfoBasar;


# --------------------------------------------------------
#
# Tabellenstruktur für Tabelle infobasar_forum
#

DROP TABLE IF EXISTS infobasar_address_book;
CREATE TABLE infobasar_address_book (
  id int(11) NOT NULL auto_increment,
  createdat datetime default NULL,
  changedat datetime NOT NULL default '0000-00-00 00:00:00',
  name varchar(64) default NULL,
  description varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM AUTO_INCREMENT=11;

insert into infobasar_address_book (name, description) values ("Mitglieder", "Adressen der Mitglieder des InfoBasars");

DROP TABLE IF EXISTS infobasar_address_card;
CREATE TABLE infobasar_address_card (
  id int(11) NOT NULL auto_increment,
  createdat datetime default NULL,
  changedat datetime NOT NULL default '0000-00-00 00:00:00',
  books varchar(255) NOT NULL, /* Bsp: " 11 12 27 " */
  firstname varchar(64) default NULL,
  lastname varchar(64) default NULL,
  nickname varchar(64) default NULL,
  emailprivate varchar(128) NULL,
  emailprivate2 varchar(128) NULL,
  phoneprivate varchar(32) default NULL,
  phoneprivate2 varchar(32) default NULL,
  faxprivate varchar(32) default NULL,
  mobileprivate varchar(32) default NULL,
  emailoffice varchar(128) default NULL,
  emailoffice2 varchar(128) default NULL,
  phoneoffice varchar(32) default NULL,
  phoneoffice2 varchar(32) default NULL,
  faxoffice varchar(32) default NULL,
  mobileoffice varchar(32) default NULL,
  street varchar(64) default NULL,
  country varchar (5) default "D",
  city varchar (64),
  zip varchar (12) default NULL,
  functions varchar (128) default NULL,
  notes varchar (255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM AUTO_INCREMENT=11;
