#!/usr/bin/perl
# $Id: opt_php_module.pl,v 1.1 2005/01/10 19:38:41 hamatoma Exp $
# Optimieren einer PHP-Datei.
# Zusammensetzen von Quellen zu einer php-Quelle:
# Start ist index.php oder aus der Kommandozeile
# includes werden expandiert, ausser config.php.
# Kommentare werden entfernt.
# trace-Aufrufe werden entfernt.

my $start = "base_module.php";

$start = shift if $#ARGV >= 0;

my $fn_trg = $start;
$fn_trg =~ s/module/opt/;
$fn_trg = shift if $#ARGV >= 0;

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
			if ($1 ne "config.php"){
				&readSource ($1);
			} else {
				print "config.php als Include-Datei\n";
				$s_size_out += length ($_);
				print OUT $_, "\n";
			}
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

