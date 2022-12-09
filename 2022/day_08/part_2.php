<?php

require_once __DIR__  . '/../util.php';
$inputFile = getInputFile(__DIR__, $argv[1] ?? '');

$inputs = file($inputFile, FILE_IGNORE_NEW_LINES);
if(false === $inputs){
	echo 'Cannot read input file';
	exit;
}

$rows = count($inputs);
$columns = strlen($inputs[0]);

$highScore = 0;
foreach ($inputs as $row => $line) {
	for ($column=0; $column < $columns; $column++) {
		$score = 1; 
		$house = $line[$column];
		
		// look right
		$view = 0;
		$i = $column + 1;
		while ($i < $columns) {
			$view++;
			$tree = $line[$i];
			if($tree >= $house){
				break;
			}

			$i++;
		}
		$score *= $view;
		if($score == 0){
			continue;
		}

		// look left
		$view = 0;
		$i = $column - 1;
		while ($i >= 0) {
			$view++;
			$tree = $line[$i];
			if($tree >= $house){
				break;
			}

			$i--;
		}
		$score *= $view;
		if($score == 0){
			continue;
		}

		// look top
		$view = 0;
		$i = $row - 1;
		while ($i >= 0) {
			$view++;
			$tree = $inputs[$i][$column];
			if($tree >= $house){
				break;
			}

			$i--;
		}
		$score *= $view;
		if($score == 0){
			continue;
		}

		// look down
		$view = 0;
		$i = $row + 1;
		while ($i < $rows) {
			$view++;
			$tree = $inputs[$i][$column];
			if($tree >= $house){
				break;
			}

			$i++;
		}
		$score *= $view;

		if($score > $highScore){
			$highScore = $score;
		}
	}
}

echo "Highest Scenic Score: $highScore\n";

function insertTree(&$visibleTrees, $row, $column, $tree){
	if(!isset($visibleTrees[$row])){
		$visibleTrees[$row] = [];
	}

	if(!isset($visibleTrees[$row][$column])){
		$visibleTrees[$row][$column] = [];
	}

	$visibleTrees[$row][$column][] = $tree;
}
