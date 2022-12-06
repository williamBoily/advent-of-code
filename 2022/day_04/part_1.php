<?php

require_once __DIR__  . '/../util.php';

$inputFile = getInputFile(__DIR__, $argv[1] ?? '');

$inputs = file($inputFile, FILE_IGNORE_NEW_LINES);
if(false === $inputs){
	echo 'Cannot read input file';
	exit;
}

$nb = 0;
foreach ($inputs as $key => $line) {
	$line = trim($line);
	$sections = explode(",", $line);
	$rangeOne = explode("-", $sections[0]);
	$rangeTwo = explode("-", $sections[1]);

	$rangeOne = range($rangeOne[0], $rangeOne[1]);
	$rangeTwo = range($rangeTwo[0], $rangeTwo[1]);

	$biggest = $rangeOne;
	$smallest = $rangeTwo;
	if(count($biggest) < count($rangeTwo)){
		$biggest = $rangeTwo;
		$smallest = $rangeOne;
	}

	if($smallest[0] >= $biggest[0] && end($smallest) <= end($biggest)){
		$nb++;
	}
}

echo "Nb: $nb\n";



