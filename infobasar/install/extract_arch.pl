#!/usr/bin/perl
# $Id: extract_arch.pl,v 1.1 2005/01/11 17:47:16 hamatoma Exp $
# Entpacken eines Archivs
#
# Aufruf: extract_arch.pl <opts> archive
#
# Archivformat:
# Kopf:
# <hex2_size_header>hamatomaarchive<char4_version>
# Je Datei
# <hex4_name_size> <filename_with_path> Pfadnamen mit '/' als Trenner!
# <hex8_sec_since_1970)> <char_file_type> <char3_res_1>
# <hex2_info_size> <hex8_rights>
# <hex8_data_size> <file_data>
# <hex8_magic> <hex16_checksum>
use strict;

sub help {
	print <<EOS;
Aufruf: extract_arch.pl [<opts>] archive
<opt>:
 -t             Nur Inhaltsverzeichnis ausgeben.
 -v             Meldungen ausgeben
 -vv            Techn. Details ausgeben.
<filelist>      Listendatei mit einem Dateinamen pro Zeile, Kommentare mit #
+++ $_[0]
EOS
	exit 1;
}

my $s_block_size = 1000;
my $s_magic = 'HaMaToMa';
my $s_toc_only = 0;
my $path_delim = $ENV{'PATH'} =~ m!/! ? "/" : "\\";
#              12345678

my $s_verbose = 0;

while ($ARGV[0] =~ /^-(.)(.*)/) {
	if ($1 eq "t") {
		$s_toc_only = 1;
	} elsif ($1 eq "v") {
		$s_verbose = 1 + length ($2);
	} else {
		&help ("unbekannte Option: $1");
	}
	shift;
} # while

my $archive = shift;
my $header = "HamatomaArchive\t0100";


&help ("kein Archiv angegeben") unless $archive;

open (ARCHIVE, $archive) || die "$archive: $!";
my ($len, $val) = ReadBlock (2);
print "Headerlaenge: ", $len, "\nHeader: ", $val, "\n" if $s_verbose > 1;
&help ("Kein Archiv: $archive") if ($val ne $header);
while (! eof (ARCHIVE)){
	&OneFile;
}


sub ReadHex{
	my $len = shift;
	my $val;
	$len = read ARCHIVE, $val, $len;
	$len = hex ('0x' . $val);
	return $len;
}
sub ReadBlock{
	my $len = shift;
	my $val;
	$len = &ReadHex ($len);
	read ARCHIVE, $val, $len;
	return ($len, $val);
}
sub OneFile {
# <hex4_name_size> <filename_with_path> 
# <hex8_sec_since_1970)> <char_file_type> <char3_res_1>
# <hex2_info_size> <hex8_rights>
# <hex8_data_size> <file_data>
# <char8_magic>
# <hex16_checksum>
	my $match = 1;
	my $checksum = '0' x 16;
	my ($len, $val, $name, $time, $magic, $file_type, $info, $data_size, $rights);
	($len, $name) = &ReadBlock (4);
	$time = &ReadHex (8);
	read ARCHIVE, $file_type, 1;
	read ARCHIVE, $val, 3;
	($len, $rights) = &ReadBlock (2);
	$data_size = &ReadHex(8);
	if ($match){
		print sprintf ('%8d ', $data_size) if $match && ($s_verbose || $s_toc_only);
		print sprintf ('$%08x ', $time), $file_type, ' ', $rights." " if $s_verbose > 1;
		print $name, "\n" if $s_verbose || $s_toc_only;
	}
	if ($file_type eq "d"){
		&MkDir ($name) if $match;
	} else {
		if ($match){
			my $dir = $name;
			$dir =~ s!/[^/]+$!!;
			&MkDir ($dir) unless -d $dir;
		}
		$name =~ s!/!\\!g if $path_delim ne "/";
		my $write = $match && open (OUT, ">$name");
		if (! $write && $match){
			print "+++ $name: $!\n";
		}
		while ($data_size >= $s_block_size){
			$len = read ARCHIVE, $val, $s_block_size;
			if ($len <= 0){
				print die "+++ Archiv fehlerhaft: Block nicht lesbar\n";
				exit 2;
			}
			print OUT $val if $write && ! $s_toc_only;	
			$data_size -= $len;
		}
		if ($data_size > 0){
			$len = read ARCHIVE, $val, $data_size;
			print OUT $val if $write && ! $s_toc_only;	
		}
	}
	$len = read ARCHIVE, $val, 8;
	if ($val ne $s_magic){
		print "+++ Magic-Konstante fehlerhaft: $val\n";
	}
	$len = read ARCHIVE, $val, 16;
	if ($val ne $checksum){
		print "+++ Checksumme fehlerhaft: $val / $checksum\n";
	}
	close OUT;
}
sub MkDir{
	my $fullname = shift;
	my $abs_path = ord($fullname) == ord('/');
	if ($fullname !~ /^\.{1,2}$/){
		my @f = split (/\//, $fullname);
		my $name = "";
		foreach (@f){
			next unless $_;
			if (! $abs_path && $name eq ""){
				$name = $_;
			} else {
				$name .= $path_delim . $_;
			}
			if (! -d $name){
				if (mkdir ($name)){
					print "angelegt: $name\n" if $s_verbose > 0;
				} else {
					print  "+++ missglueckt: mkdir $name\n";
				}
			}
		}
	}
}
close ARCHIVE;


