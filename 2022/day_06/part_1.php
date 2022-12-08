<?php

require_once __DIR__  . '/../util.php';

$inputFile = getInputFile(__DIR__, $argv[1] ?? '');

$inputs = file($inputFile, FILE_IGNORE_NEW_LINES);
if(false === $inputs){
	echo 'Cannot read input file';
	exit;
}

$buffer = $inputs[0];

$nbChars = strlen($buffer);

$packetDelimiter = [$buffer[0], $buffer[1], $buffer[2], $buffer[3]];
$position = 4;
do {
	if(4 === count(array_unique($packetDelimiter))){
		break;
	}

	array_shift($packetDelimiter);
	if(isset($buffer[$position])){
		$packetDelimiter[] = $buffer[$position];
	}
	$position++;
} while ($position < $nbChars);


echo "$position\n";
