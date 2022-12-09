<?php

require_once __DIR__  . '/../util.php';

$inputFile = getInputFile(__DIR__, $argv[1] ?? '');

$inputs = file($inputFile, FILE_IGNORE_NEW_LINES);
if(false === $inputs){
	echo 'Cannot read input file';
	exit;
}


$column = 0;
$visibleTrees = [];

$rows = count($inputs);
$columns = strlen($inputs[0]);

// --> left to right
foreach ($inputs as $row => $line) {
	$tallest = -1;
	for ($column=0; $column < $columns; $column++) {
		$tree = $line[$column];
		if($tree > $tallest){
			$tallest = $tree;
			insertTree($visibleTrees, $row, $column, $line[$column]);
		}
	}
}

// <-- right to left
foreach ($inputs as $row => $line) {
	$tallest = -1;
	for ($column=$columns-1; $column >= 0; $column--) { 
		$tree = $line[$column];
		if($tree > $tallest){
			$tallest = $tree;
			insertTree($visibleTrees, $row, $column, $line[$column]);
		}
	}
}

// top to bottom
for ($column=0; $column < $columns; $column++) { 
	$tallest = -1;
	for ($row=0; $row < $rows; $row++) {
		$tree = $inputs[$row][$column];
		if($tree > $tallest){
			$tallest = $tree;
			insertTree($visibleTrees, $row, $column, $line[$column]);
		}
	}
}

// bottom to top
for ($column=0; $column < $columns; $column++) { 
	$tallest = -1;
	for ($row=$rows-1; $row >= 0; $row--) {
		$tree = $inputs[$row][$column];
		if($tree > $tallest){
			$tallest = $tree;
			insertTree($visibleTrees, $row, $column, $line[$column]);
		}
	}
}

$nbVisibleTree = 0;
foreach ($visibleTrees as $row => $columns) {
	$nbVisibleTree += count($columns);
}

echo "Total visible trees: $nbVisibleTree\n";

function insertTree(&$visibleTrees, $row, $column, $tree){
	if(!isset($visibleTrees[$row])){
		$visibleTrees[$row] = [];
	}

	if(!isset($visibleTrees[$row][$column])){
		$visibleTrees[$row][$column] = [];
	}

	$visibleTrees[$row][$column][] = $tree;
}
