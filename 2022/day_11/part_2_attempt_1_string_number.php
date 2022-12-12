<?php

/**
 * idiot attempt trying to bypass max INT by calculating large number as string.
 * 
 * don't even know if this works, it was too slow
 */
require_once __DIR__  . '/../util.php';

$inputFile = getInputFile(__DIR__, $argv[1] ?? '');

$inputs = file($inputFile, FILE_IGNORE_NEW_LINES);
if(false === $inputs){
	echo 'Cannot read input file';
	exit;
}

$monkeysInput = [];

foreach ($inputs as $key => $line) {
	if(0 !== strpos($line, 'Monkey')){
		continue;
	}

	sscanf($line, "Monkey %d:", $monkeyId);
	$monkeysInput[$monkeyId] = $key;
}

$monkeys = [];
foreach ($monkeysInput as $id => $index) {
	$startingItems = trim($inputs[$index + 1]);
	$startingItems = str_replace('Starting items: ', '', $startingItems);
	$startingItems = array_map(function($val) {
		return $val;
	}, explode(', ', $startingItems));
	$monkeys[$id]['list'] = $startingItems;

	$operation = trim($inputs[$index + 2]);
	sscanf($operation, "Operation: new = %s %s %s", $a, $operator, $b);

	if($operator == '*'){
		$monkeys[$id]['operation'] = 'multiply';
	}

	if($operator == '+'){
		$monkeys[$id]['operation'] = 'add';
	}
	$monkeys[$id]['operation_arg_1'] = $a;
	$monkeys[$id]['operation_arg_2'] = $b;

	$test = trim($inputs[$index + 3]);
	sscanf($test, "Test: divisible by %d", $nb);
	$monkeys[$id]['test_arg_1'] = $nb;

	$test = trim($inputs[$index + 4]);
	sscanf($test, "If true: throw to monkey %d", $target);
	$monkeys[$id]['test_result_true'] = $target;

	$test = trim($inputs[$index + 5]);
	sscanf($test, "If false: throw to monkey %d", $target);
	$monkeys[$id]['test_result_false'] = $target;

	$monkeys[$id]['inspect_count'] = 0;
}



// $numbers = 
// for ($i=0; $i < 10; $i++) { 
// 	$max  = max($numbers);
// 	foreach ($numbers as $key => $value) {
// 		while ($max > $value) {
// 			$value += $value;
// 		}
	
// 		$numbers[$key] = $value;
// 	}
// }
// $common = findGreatestCommonDivisor($numbers);

$divNumbers = array_column($monkeys, 'test_arg_1');
$minDiv = min($divNumbers);

$lastId = array_key_last($monkeys);
$nbRounds = 6;
for ($i=1; $i<=$nbRounds; $i++){
	for ($m=0; $m <= $lastId; $m++) {
		foreach ($monkeys[$m]['list'] as $key => $item) {
			$a = $monkeys[$m]['operation_arg_1'] === 'old' ? $item : $monkeys[$m]['operation_arg_1'];
			$b = $monkeys[$m]['operation_arg_2'] === 'old' ? $item : $monkeys[$m]['operation_arg_2'];
			$item = $monkeys[$m]['operation']((string)$a, (string)$b);
			// list($item, $remain) = division_string($item, '3');
	
			$result = is_divisible_string((string)$item, (string)$monkeys[$m]['test_arg_1']);
			$targetMonkey = $monkeys[$m]["test_result_$result"];
	
			$monkeys[$m]['inspect_count']++;
	
			$monkeys[$targetMonkey]['list'][] = $item;
		}
		$monkeys[$m]['list'] = [];
	}
}

uasort($monkeys, function($a, $b) {
	return $a['inspect_count'] <=> $b['inspect_count'];
});

$first = array_pop($monkeys);
$second = array_pop($monkeys);

echo "total: ". $first['inspect_count'] * $second['inspect_count'] . "\n";

function multiply($a, $b){
	$result = "0";
	for ($i=1; $i <= (int)$b ; $i++) { 
		$result = sum_strings($result, $a);
	}

	return $result;
}

function add($a, $b){
	return sum_strings($a, $b);
}

function divisible($level, $const){
	return ($level % $const === 0) ? 'true' : 'false';
}

function sum_strings($a, $b) {
	$result = "";

	$a = array_reverse(str_split($a));
	$b = array_reverse(str_split($b));
	$lengthA = count($a);
	$lengthB = count($b);
	
	$biggestLength = $lengthA;
	if($lengthB > $lengthA){
	$biggestLength = $lengthB;
	}
	
	$ret = 0;
	for($i = 0; $i <= $biggestLength; $i++){
	$numberA = (int)(isset($a[$i]) ? $a[$i] : 0);
	$numberB = (int)(isset($b[$i]) ? $b[$i] : 0);
	$numberSum = $numberA + $numberB + $ret;
	
	$ret = (int)floor($numberSum / 10);
	$result = (string)($numberSum - ($ret * 10)) . $result;
	}
	
	
	return ltrim($result, "0");
}

function is_divisible_string($stringNum, $const){
	list($full, $remaining) = division_string($stringNum, $const);

	return empty($remaining) ? 'true' : 'false';
}

function division_string($stringNum, $const){
	$const = (int)$const;
	$result = '';
	$currentNumber = (int)$stringNum[0];
	$length = strlen($stringNum);
	for ($i=0; $i <= $length - 1; $i++) { 
		$stepResult = (int)floor($currentNumber / $const);
		$result .= $stepResult;

		$currentNumber = ($currentNumber - ($stepResult * $const));

		if(isset($stringNum[$i + 1])){
			$currentNumber *= 10;
			$currentNumber += $stringNum[$i + 1];
		}
	}
	
	return [ltrim($result, '0'), $currentNumber];
}

function sortStringNumber($a, $b){
	$la = strlen($a);
	$lb = strlen($b);
	if($la > $lb){
		return 1;
	}

	if($la < $lb){
		return -1;
	}

	for ($i=0; $i < $la; $i++) { 
		if((int)$a[$i] > (int)$b[$i]){
			return 1;
		}
	}

	return -1;
}

