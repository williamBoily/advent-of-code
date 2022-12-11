<?php

require_once __DIR__  . '/../util.php';

$inputFile = getInputFile(__DIR__, $argv[1] ?? '');

$inputs = file($inputFile, FILE_IGNORE_NEW_LINES);
if(false === $inputs){
	echo 'Cannot read input file';
	exit;
}

$sample = 
'R 4
U 4
L 3
D 1
R 4
D 1
L 5
R 2';


$tail = ['r' => 0, 'c' => 0];
$head = ['r' => 0, 'c' => 0];

$positions = [];

foreach ($inputs as $key => $motions) {
	list($dir, $steps) = explode(' ', $motions);
	for ($i=1; $i <= $steps ; $i++) { 
		$head = moveHead($head, $dir);
		if(isTailTouching($head, $tail)){
			continue;
		}
	
		$tail = moveTail($head, $tail);
		addPosition($positions, $tail['r'], $tail['c']);
	}
}

$visitedPositions = 1;
foreach ($positions as $row => $columns) {
	$visitedPositions += count($columns);
}

echo "total: $visitedPositions\n";

function isTailTouching($head, $tail){
	if(abs($head['r'] - $tail['r']) > 1){
		return false;
	}

	if(abs($head['c'] - $tail['c']) > 1){
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

function moveTail($head, $tail){
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

