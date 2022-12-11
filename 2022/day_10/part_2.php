<?php

require_once __DIR__  . '/../util.php';

$inputFile = getInputFile(__DIR__, $argv[1] ?? '');

$inputs = file($inputFile, FILE_IGNORE_NEW_LINES);
if(false === $inputs){
	echo 'Cannot read input file';
	exit;
}

$cycle = 1;
$registerX = 1;
$pixel = 0;
$crtRow = 0;
$screen = [''];
foreach ($inputs as $key => $line) {
	if($line == 'noop'){
		$instruction = 'noop';
		$x = 0;
	}else{
		list($instruction, $x) = explode(' ', $line);
	}

	$nbCycle = $instruction == 'noop' ? 1 : 2;

	for ($i=1; $i<=$nbCycle ; $i++) {
		$char = '.';
		if($pixel >= $registerX - 1 && $pixel <= $registerX + 1){
			$char = '#';
		}
		$screen[$crtRow] .= $char;
		$pixel++;

		if($pixel == 40){
			$pixel = 0;
			$crtRow++;
			$screen[$crtRow] = '';
		}

		$cycle++;
	}

	if($instruction == 'addx'){
		$registerX += $x;
	}
}


foreach ($screen as $key => $row) {
	echo "$row\n";
}


