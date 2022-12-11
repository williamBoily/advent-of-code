<?php

require_once __DIR__  . '/../util.php';

$inputFile = getInputFile(__DIR__, $argv[1] ?? '');

$inputs = file($inputFile, FILE_IGNORE_NEW_LINES);
if(false === $inputs){
	echo 'Cannot read input file';
	exit;
}


$knots = [];
for ($i=0; $i <=9 ; $i++) { 
	$knots[$i] = ['r' => 0, 'c' => 0];
}

$positions = [];

foreach ($inputs as $key => $motions) {
	list($dir, $steps) = explode(' ', $motions);

	for ($j=1; $j <= $steps; $j++) { 
		$knots[0] = moveHead($knots[0], $dir);
		for ($i=1; $i <= 9; $i++) { 
			if(isKnotTouchingFront($knots[$i - 1], $knots[$i])){
				continue;
			}

			$knots[$i] = moveKnot($knots[$i - 1], $knots[$i]);
			// tail
			if($i == 9){
				addPosition($positions, $knots[$i]['r'], $knots[$i]['c']);
			}
		}
	}
}

$visitedPositions = 1;
foreach ($positions as $row => $columns) {
	$visitedPositions += count($columns);
}

echo "total: $visitedPositions\n";

function isKnotTouchingFront($front, $knot){
	if(abs($front['r'] - $knot['r']) > 1){
		return false;
	}

	if(abs($front['c'] - $knot['c']) > 1){
		return false;
	}

	return true;
}

function moveHead($head, $direction){
	if($direction == 'R'){
		$head['c']++;
	}

	if($direction == 'L'){
		$head['c']--;
	}

	if($direction == 'U'){
		$head['r']++;
	}

	if($direction == 'D'){
		$head['r']--;
	}

	return $head;
}

function moveKnot($head, $tail){
	$r = $head['r'] - $tail['r'];
	if($r > 0){
		$tail['r']++;
	}

	if($r < 0){
		$tail['r']--;
	}

	$c = $head['c'] - $tail['c'];
	if($c > 0){
		$tail['c']++;
	}

	if($c < 0){
		$tail['c']--;
	}

	return $tail;
}

function addPosition(&$positions, $row, $column){
	if(!isset($positions[$row])){
		$positions[$row] = [];
	}

	if(!isset($positions[$row][$column])){
		$positions[$row][$column] = [];
	}

	$positions[$row][$column][] = 1;
}

