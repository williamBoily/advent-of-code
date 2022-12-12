<?php

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
		return (int) $val;
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

$lastId = array_key_last($monkeys);
$nbRounds = 20;
for ($i=1; $i<=$nbRounds; $i++){
	for ($m=0; $m <= $lastId; $m++) {
		foreach ($monkeys[$m]['list'] as $key => $item) {
			$a = $monkeys[$m]['operation_arg_1'] === 'old' ? $item : $monkeys[$m]['operation_arg_1'];
			$b = $monkeys[$m]['operation_arg_2'] === 'old' ? $item : $monkeys[$m]['operation_arg_2'];
			$item = $monkeys[$m]['operation']((int)$a, (int)$b);
			$item = (int)floor($item / 3);
	
			$result = divisible($item, $monkeys[$m]['test_arg_1']);
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
	return $a * $b;
}

function add($a, $b){
	return $a + $b;
}

function divisible($level, $const){
	return ($level % $const === 0) ? 'true' : 'false';
}




