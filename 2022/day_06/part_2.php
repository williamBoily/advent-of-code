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

$nbUniqueChar = 14;
$messageDelimiter = [];
for ($i=0; $i < $nbUniqueChar; $i++) { 
	$messageDelimiter[] = $buffer[$i]; 
}

$position = $nbUniqueChar;
do {
	if($nbUniqueChar === count(array_unique($messageDelimiter))){
		break;
	}

	array_shift($messageDelimiter);
	if(isset($buffer[$position])){
		$messageDelimiter[] = $buffer[$position];
	}
	$position++;
} while ($position < $nbChars);


echo "$position\n";
