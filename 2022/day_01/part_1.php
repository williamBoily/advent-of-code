<?php

$inputFile = __DIR__ . '/input.txt';
// $inputFile = __DIR__ . '/input_example.txt';

$fp = fopen($inputFile, 'r');
if (!$fp) {
	echo 'Cannot read input file';
	exit;
}

$elfToAsk = 1;
$mostCalories = 0;

$currentCalories = 0;
$currentElf = 1;
while (($line = fgets($fp)) !== false) {
	$line = str_replace("\n", "", $line);
	
	var_dump($line);
	if($line === ""){
		if($currentCalories >= $mostCalories){
			$mostCalories = $currentCalories;
			$elfToAsk = $currentElf;
		}

		$currentElf++;
		$currentCalories = 0;
		continue;
	}

	$snack = intval($line);
	$currentCalories += $snack;
}
fclose($fp);

echo "elf To ask: $elfToAsk, total: $mostCalories\n";

