<?php

require_once __DIR__  . '/../util.php';

$inputFile = getInputFile(__DIR__, $argv[1] ?? '');

$inputs = file($inputFile, FILE_IGNORE_NEW_LINES);
if(false === $inputs){
	echo 'Cannot read input file';
	exit;
}

$groups = array_chunk($inputs, 3);

$sum = 0;
foreach ($groups as $key => $elfs) {
	for ($i=0; $i < 3; $i++) { 
		$elfs[$i] = str_split($elfs[$i]);
	}

	$badge = array_intersect($elfs[0], $elfs[1], $elfs[2]);	
	$badge = current($badge);

	$sum += getPriotity($badge);
}

echo "Sum: $sum\n";

function getPriotity($item){
	if(ctype_upper($item)){
		return ord($item) - 38;
	}

	return ord($item) - 96;
}

