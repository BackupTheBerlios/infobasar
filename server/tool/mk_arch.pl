#!/usr/bin/perl
# Erstellen eines Archivs aus einem Dateibaum
#
# Format:
# <hex4_size> <filename_with_path> <hex8_sec_since_1970)>
# <hex8_size> <file_data>
use strict;

my $archive = "a.hma";
my $dir = ".";
my $s_buf_size = 64;

my $s_verbose = 0;

while ($ARG[0] =~ /^-(.)(.*)/) {
	if ($1 eq "a") {
		$archive = $2;
	} elsif ($1 eq "v") {
		$s_verbose = 1;
	} else {
		&help ("unbekannte Option: $1");
	}
	shift;
} # while

if ($#ARGS < 0) {
	&one_dir (".");
} else {
	while ($#ARGS >= 0){
		$dir = shift;
		chomp($olddir = `pwd`);
		&one_dir ($dir);
		
	}
}
exit 0;

sub help {
	print <<EOS;
Aufruf: mk_archiv <opts> [<dir>]
<opt>:
 -a<archive>    Archiv. VE: a.hma
 -v             Meldungen ausgeben
 -r             
<dir>           VerzeichnisbaumDateibaum
+++ $_[0]
EOS
	exit 1;
}

sub OneFile {
	my $name = shift;
	my $date = shift;
	my $size = shift;
	print $name, "\n" if $s_verbose;
	if (! open (FILE, $name)){
		print "+++ nicht zu öffnen: $name: $!";
	} else {
		print ARCHIVE sprintf ("%04x", length ($name), $name, sprintf ("%08x%08x", $date), $size);
		my $sum = 0;
		my $bytes;
		while ( ($bytes = fwrite (FILE, $buffer, $s_buf_size) {
			print ARCHIVE $buffer;
			$sum += bytes;
		}
		close (FILE);
		if ($sum ne $size)
			print "$name: Größe falsch: $bytes statt $size\n";
	}
}

sub OneDir {
	my $dir = shift;
	print "= ", $dir, "\n" if $s_verbose;
	if (! opendir (DIR, $dir)) {
		print "+++ kann Verzeichnis nicht lesen: $dir ($!)\n";
	} else {
		@files = readdir (DIR);
		close (DIR);
		foreach (@files) {
			next if /^\.{1,2}$/;
			$name = $dir . $s_delim . $_;
			# Filter
			my ($dev,$ino,$mode,$nlink,$uid,$gid,$rdev,$size,
       		$atime,$mtime,$ctime,$blksize,$blocks) = stat;
			if (-d) {
				&OneDir ($name);
			} else {
				&OneFile ($name, $size, $mtime);
			}
		}
	}
}


