#!/usr/bin/perl
# Zusammensetzen von Quellen zu einer php-Quelle:
# Start ist index.php
# includes werden expandiert.
# Kommentare werden entfernt.
# trace-Aufrufe werden entfernt.

my $start = "index.php";
my $fn_trg = "all.php";
my $s_size_inp = 0;
my $s_size_out = 0;

print "Ausgabe nach $fn_trg:\n";
open (OUT, ">$fn_trg") || die "$fn_trg: $!";
print OUT "<?php\n";

readSource ($start);
print OUT "?>\n";
print "Gelesen: $s_size_inp Geschrieben: $s_size_out (",
	int (($s_size_inp - $s_size_out) * 100 / $s_size_inp), "%)\n";
exit 0;

sub readSource {
	my $name = shift;
	print OUT "#== Modul $name:\n";
	print $name, "\n";
	open (INP, $name) || die "$name: $!";
	my @lines = <INP>;
	close (INP);
	my $line;
	foreach (@lines){
		chomp;
		next if /^\<\?php/ || /^\?\>/;
		$s_size_inp += length ($_);
		s/^\s+//;
		if (/include\s+"(\S+)"/){
			&readSource ($1);
		} else {
			# Kommentare entfernen:
			s!(//|#)[^'"]*$!!;
			# trace-Aufrufe entfernen:
			$_ = ';' if /-\>trace\s*\(/ && /;\s*$/;
			if (! /["']/){
				s/ +([-()?:+=\/*,.<>{}\[\]!&|])/\1/g;
				s/([-()?:+=\/*,.<>{}\[\]!&|]) +/\1/g;
			} elsif (/^define/){
				s/ \(/(/;
				s/, /,/;
			} else {
				s/^([-()?:+=\/*,.<>{}\[\]!]) +/\1/;
				s/^([^"' ]+) +([-()?:+=\/*,.<>{}\[\]!&|]+) */\1\2/;
				s/ *([-()?:+=\/*,.<>{}\[\]!&|]+) +([^"' ]+) *$/\1\2/;
			}
			$s_size_out += length ($_);
			print OUT $_, "\n";
		}
	}
}

