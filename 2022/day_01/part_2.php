<?php

$inputFile = __DIR__ . '/input.txt';
// $inputFile = __DIR__ . '/input_example.txt';

$fp = fopen($inputFile, 'r');
if (!$fp) {
	echo 'Cannot read input file';
	exit;
}

$elfs = [];
$currentCalories = 0;
while (($line = fgets($fp)) !== false) {
	$line = str_replace("\n", "", $line);
	if($line === ""){
		$elfs[] = $currentCalories;
		$currentCalories = 0;
		continue;
	}

	$currentCalories += intval($line);
}
fclose($fp);

$elfs[] = $currentCalories;

rsort($elfs);

$total = $elfs[0] + $elfs[1] + $elfs[2];

echo "total: $total\n";

