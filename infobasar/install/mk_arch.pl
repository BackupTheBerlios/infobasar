#!/usr/bin/perl
# Erstellen eines Archivs aus einem Dateibaum
#
# Aufruf: mk_arch.pl <opts> file_list
# Format Dateiliste:
# Je Zeile ein Dateiname (mit Pfad).
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

my $archive = "a.hma";
my $dir = ".";
my $s_buf_size = 64;
my $s_magic = 'HaMaToMa';
#              12345678

my $s_verbose = 0;

while ($ARGV[0] =~ /^-(.)(.*)/) {
	if ($1 eq "a") {
		$archive = $2;
	} elsif ($1 eq "v") {
		$s_verbose = 1;
	} else {
		&help ("unbekannte Option: $1");
	}
	shift;
} # while

if ($#ARGV < 0) {
	&help ("keine Dateiliste angegeben");
} else {
	open (ARCHIVE, ">$archive") || die "$archive: $!";
	my $header = "HamatomaArchive\t0100";
	#             123456789 123456789
	print ARCHIVE sprintf ("%02x", length ($header)), $header;
	&FileList ($ARGV [0]);
}
exit 0;

sub help {
	print <<EOS;
Aufruf: mk_archiv.pl [<opts>] filelist
<opt>:
 -a<archive>    Archiv. VE: a.hma
 -v             Meldungen ausgeben
<filelist>      Listendatei mit einem Dateinamen pro Zeile, Kommentare mit #
+++ $_[0]
EOS
	exit 1;
}

sub OneFile {
# <hex4_name_size> <filename_with_path> 
# <hex8_sec_since_1970)> <char_file_type> <char3_res_1>
# <hex2_info_size> <hex8_rights>
# <hex8_data_size> <file_data>
# <hex8_magic_3141592653>
# <hex128_checksum>
	my $name = shift;
	my $name_archive = shift;
	my $date = shift;
	my $size = shift;
	my $rights = shift;
	
	if ($name_archive eq "") {
		$name_archive = $name;
		print $name, "\n" if $s_verbose;
	} else {
		print $name, " -> ", $name_archive, "\n" if $s_verbose;
	}
	if (! open (FILE, $name)){
		print "+++ nicht zu öffnen: $name: $!";
	} else {
		my $name2 = $name_archive;
		$name2 =~ s!\\!/!g;
		print ARCHIVE sprintf ("%04x", length ($name2)), $name2;
		print ARCHIVE sprintf ("%08x", $date);
		print ARCHIVE -d $name ? 'd' : 'f', '   ';
		print ARCHIVE sprintf ("%02x", 8);
		print ARCHIVE sprintf ("%08x", $rights);
		print ARCHIVE sprintf ("%08x", $size);
		my $sum = 0;
		my $checksum = '0' x 16;
		my ($bytes, $buffer);
		while ( ($bytes = read (FILE, $buffer, $s_buf_size)) > 0) {
			print ARCHIVE $buffer;
			$sum += $bytes;
		}
		close (FILE);
		if ($sum ne $size){
			print "+++ $name: Größe falsch: $bytes statt $size\n";
		}
		print ARCHIVE $s_magic, $checksum;
	}
}

sub FileList {
	my $fn_list = shift;
	open (LIST, $fn_list) || die "$fn_list: $!";
	while (<LIST>){
		chomp;
		if (/\S/ && ! /^[#;]/) {
			my $name_archive = '';
			my @names = split (/\t+/);
			if ($#names > 0 && $names [1]){
				$_ = $names [0];
				$name_archive = $names [1];
			}
			my ($dev,$ino,$mode,$nlink,$uid,$gid,$rdev,$size,
				$atime,$mtime,$ctime,$blksize,$blocks) = stat;
			if (! $mtime) {
				print "+++ $_: $!\n";
			} else {
				OneFile ($_, $name_archive, $mtime, $size, $mode);
			}
		}
	}
	close ARCHIVE;
	close (LIST);
}


