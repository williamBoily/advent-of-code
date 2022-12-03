<?php

$inputFile = __DIR__ . '/input.txt';
// $inputFile = __DIR__ . '/input_example.txt';

const SHAPE_VICTORY = [
	'A' => 'C',
	'B' => 'A',
	'C' => 'B'
];

const RESPONSE_MAP = [
	'X' => 'A',
	'Y' => 'B',
	'Z' => 'C'
];

const SCORE_MAP = [
	'A' => 1,
	'B' => 2,
	'C' => 3
];

$fp = fopen($inputFile, 'r');
if (!$fp) {
	echo 'Cannot read input file';
	exit;
}

$totalScore = 0;
while (($line = fgets($fp)) !== false) {
	$line = str_replace("\n", "", $line);
	list($opponent, $me) = explode(' ', $line);
	$roundPoint = outcomeOfTheRound($me, $opponent);
	$roundPoint += SCORE_MAP[RESPONSE_MAP[$me]];

	$totalScore += $roundPoint;
}
fclose($fp);

echo "$totalScore\n";

function outcomeOfTheRound($me, $opponent){
	$me = RESPONSE_MAP[$me];
	if($me == $opponent){
		return 3;
	}

	if(SHAPE_VICTORY[$me] == $opponent){
		return 6;
	}

	return 0;
}
