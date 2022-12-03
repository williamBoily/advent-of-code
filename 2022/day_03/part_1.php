<?php

require_once __DIR__  . '/../util.php';

$inputFile = getInputFile(__DIR__, $argv[1] ?? '');

$inputs = file($inputFile, FILE_IGNORE_NEW_LINES);
if(false === $inputs){
	echo 'Cannot read input file';
	exit;
}

$sum = 0;
foreach ($inputs as $key => $rucksack) {
	$compartmentsLength = strlen($rucksack) / 2;
	
	$compartments = str_split($rucksack, $compartmentsLength);
	$compartments[0] = str_split($compartments[0]);
	$compartments[1] = str_split($compartments[1]);

	$sameType = array_intersect($compartments[0], $compartments[1]);
	$sameType = current($sameType);

	$sum += getPriotity($sameType);
}

echo "Sum: $sum\n";

function getPriotity($item){
	if(ctype_upper($item)){
		return ord($item) - 38;
	}

	return ord($item) - 96;
}

