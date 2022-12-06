<?php

require_once __DIR__  . '/../util.php';

$inputFile = getInputFile(__DIR__, $argv[1] ?? '');

$inputs = file($inputFile, FILE_IGNORE_NEW_LINES);
if(false === $inputs){
	echo 'Cannot read input file';
	exit;
}

$lineStartIndex = 0;
$stacks = [];
foreach ($inputs as $key => $line) {
	$line = trim($line, "\n");

	for ($i=0; $i < strlen($line); $i++) {
		$stackNum = (int)floor($i / 4) + 1;
		if($line[$i] === "["){
			$stacks[$stackNum][] = $line[$i + 1];
			continue;
		}
	}

	if(empty($line)){
		$lineStartIndex = $key + 1;
		break;
	}
}

// index 0 will be the bottom of each stack
foreach ($stacks as $key => $value) {
	$stacks[$key] = array_reverse($stacks[$key]);
}

$moves = array_slice($inputs, $lineStartIndex);
foreach ($moves as $key => $line) {
	sscanf($line, "move %d from %d to %d", $nb, $from, $to);
	$crates = array_splice($stacks[$from], -$nb);
	array_push($stacks[$to], ...$crates);
}


for ($i=1; $i <= count($stacks); $i++) { 
	echo end($stacks[$i]);
}

echo "\n";
