<?php

$inputFile = __DIR__ . '/input.txt';
// $inputFile = __DIR__ . '/input_example.txt';

// X == lose
// Y == draw
// Z == win

const SHAPE_VICTORY = [
	'A' => 'C',
	'B' => 'A',
	'C' => 'B'
];

const OUTCOME_SCORE = [
	'X' => 0,
	'Y' => 3,
	'Z' => 6
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
	list($opponent, $outcome) = explode(' ', $line);
	$me = determineMyShape(OUTCOME_SCORE[$outcome], $opponent);
	$roundPoint = outcomeOfTheRound($me, $opponent);
	$roundPoint += SCORE_MAP[$me];

	$totalScore += $roundPoint;
}
fclose($fp);

echo "$totalScore\n";

function outcomeOfTheRound($me, $opponent){
	if($me == $opponent){
		return 3;
	}

	if(SHAPE_VICTORY[$me] == $opponent){
		return 6;
	}

	return 0;
}

function determineMyShape($outcome, $opponent){
	foreach (['A', 'B', 'C'] as $me) {
		if($outcome == outcomeOfTheRound($me, $opponent)){
			return $me;
		}
	}

	throw new \Exception("cannot find shape to play!");
}
