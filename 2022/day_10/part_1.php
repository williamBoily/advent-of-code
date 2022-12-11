<?php

require_once __DIR__  . '/../util.php';

$inputFile = getInputFile(__DIR__, $argv[1] ?? '');

$inputs = file($inputFile, FILE_IGNORE_NEW_LINES);
if(false === $inputs){
	echo 'Cannot read input file';
	exit;
}

$signalStrength = 0;
$cycle = 1;
$registerX = 1;
foreach ($inputs as $key => $line) {
	if($line == 'noop'){
		$instruction = 'noop';
		$x = 0;
	}else{
		list($instruction, $x) = explode(' ', $line);
	}

	$nbCycle = $instruction == 'noop' ? 1 : 2;

	for ($i=1; $i<=$nbCycle ; $i++) {
		if($cycle == 20 || (($cycle-20) % 40) == 0){
			$signalStrength += $cycle * $registerX;
		}

		$cycle++;
	}

	if($instruction == 'addx'){
		$registerX += $x;
	}
}


echo "total: $signalStrength\n";


