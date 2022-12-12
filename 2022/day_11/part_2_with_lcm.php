<?php

require_once __DIR__  . '/../util.php';
require_once __DIR__  . '/util.php';
$puzzleStart = microtime(true);

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


$expectations = buildExpectations();

$divNumbers = array_column($monkeys, 'test_arg_1');
$lcm = LCM($divNumbers);

$lastId = array_key_last($monkeys);
$nbRounds = 10000;
for ($i=1; $i<=$nbRounds; $i++){
	for ($m=0; $m <= $lastId; $m++) {
		foreach ($monkeys[$m]['list'] as $key => $item) {
			$a = $monkeys[$m]['operation_arg_1'] === 'old' ? $item : $monkeys[$m]['operation_arg_1'];
			$b = $monkeys[$m]['operation_arg_2'] === 'old' ? $item : $monkeys[$m]['operation_arg_2'];
			$item = (int)$monkeys[$m]['operation']((int)$a, (int)$b);

			if($item > $lcm){
				$item = $item % $lcm; 
			}
	
			$result = divisible($item, $monkeys[$m]['test_arg_1']);
			$targetMonkey = $monkeys[$m]["test_result_$result"];
	
			$monkeys[$m]['inspect_count']++;
	
			$monkeys[$targetMonkey]['list'][] = $item;
		}
		$monkeys[$m]['list'] = [];
	}

	$debug = false;
	if($debug){
		expect($expectations, $i, $monkeys);
	}
}

uasort($monkeys, function($a, $b) {
	return $a['inspect_count'] <=> $b['inspect_count'];
});

$first = array_pop($monkeys);
$second = array_pop($monkeys);

echo microtime(true) - $puzzleStart . "\n";
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



function findLowestCommonMultiplicator(&$numbers){
	$max = 1000000;

	$bases = $numbers;

	$trackers = [];
	$list = array_fill(0, count($numbers), []);
	foreach ($numbers as $key => $value) {
		$trackers[] = [
			'base' => $value,
			'current' => $value
		];
	}

	do {
		foreach ($numbers as $key => $value) {
			while ($value < $max) {
				$list[$key][] = $value;
				$value += $bases[$key];
			}
		}

		$lcm = array_intersect($list[0], ...$list);
		$lcm = empty($lcm) ? false : min($lcm);
		foreach ($numbers as $key => $value) {
			$numbers[$key] = end($list[$key]);
		}
		unset($list);
		$list = array_fill(0, count($numbers), []);

		$max *= 10;
	} while ($lcm === false);
	
	return $lcm;
}

function findLowestCommonMultiplicator_v1(&$numbers){
	$max = 1000;

	$bases = $numbers;
	$list = array_fill(0, count($numbers), []);
	do {
		foreach ($numbers as $key => $value) {
			while ($value < $max) {
				$list[$key][] = $value;
				$value += $bases[$key];
			}
		}

		$lcm = array_intersect($list[0], ...$list);
		$lcm = empty($lcm) ? false : min($lcm);
		foreach ($numbers as $key => $value) {
			$numbers[$key] = end($list[$key]);
		}
		unset($list);
		$list = array_fill(0, count($numbers), []);

		$max *= 10;
	} while ($lcm === false);
	
	return $lcm;
}

function expect($expectations, $round, $monkeys){
	if(!isset($expectations[$round])){
		return;
	}

	foreach ($monkeys as $key => $monkey) {
		if($monkey['inspect_count'] != $expectations[$round][$key]){
			echo "Error at round $round\n";
			var_dump($monkey);
			exit;
		}
	}

	echo "Round $round passed!\n";
}

function buildExpectations()
{

	$inputs = 
'== After round 1 ==
Monkey 0 inspected items 2 times.
Monkey 1 inspected items 4 times.
Monkey 2 inspected items 3 times.
Monkey 3 inspected items 6 times.

== After round 20 ==
Monkey 0 inspected items 99 times.
Monkey 1 inspected items 97 times.
Monkey 2 inspected items 8 times.
Monkey 3 inspected items 103 times.

== After round 1000 ==
Monkey 0 inspected items 5204 times.
Monkey 1 inspected items 4792 times.
Monkey 2 inspected items 199 times.
Monkey 3 inspected items 5192 times.

== After round 2000 ==
Monkey 0 inspected items 10419 times.
Monkey 1 inspected items 9577 times.
Monkey 2 inspected items 392 times.
Monkey 3 inspected items 10391 times.

== After round 3000 ==
Monkey 0 inspected items 15638 times.
Monkey 1 inspected items 14358 times.
Monkey 2 inspected items 587 times.
Monkey 3 inspected items 15593 times.

== After round 4000 ==
Monkey 0 inspected items 20858 times.
Monkey 1 inspected items 19138 times.
Monkey 2 inspected items 780 times.
Monkey 3 inspected items 20797 times.

== After round 5000 ==
Monkey 0 inspected items 26075 times.
Monkey 1 inspected items 23921 times.
Monkey 2 inspected items 974 times.
Monkey 3 inspected items 26000 times.

== After round 6000 ==
Monkey 0 inspected items 31294 times.
Monkey 1 inspected items 28702 times.
Monkey 2 inspected items 1165 times.
Monkey 3 inspected items 31204 times.

== After round 7000 ==
Monkey 0 inspected items 36508 times.
Monkey 1 inspected items 33488 times.
Monkey 2 inspected items 1360 times.
Monkey 3 inspected items 36400 times.

== After round 8000 ==
Monkey 0 inspected items 41728 times.
Monkey 1 inspected items 38268 times.
Monkey 2 inspected items 1553 times.
Monkey 3 inspected items 41606 times.

== After round 9000 ==
Monkey 0 inspected items 46945 times.
Monkey 1 inspected items 43051 times.
Monkey 2 inspected items 1746 times.
Monkey 3 inspected items 46807 times.

== After round 10000 ==
Monkey 0 inspected items 52166 times.
Monkey 1 inspected items 47830 times.
Monkey 2 inspected items 1938 times.
Monkey 3 inspected items 52013 times.';
	$expectations = [];
	$lines = explode("\n", $inputs);
	$round = 0;
	foreach ($lines as $key => $line) {
		$line = trim($line);
		if(empty($line)){
			continue;
		}

		$nb = null;
		sscanf($line, "== After round %d ==", $nb);
		if(!empty($nb)){
			$round = $nb;
			continue;
		}

		sscanf($line, "Monkey %d inspected items %d times.", $monkey, $nb);
		$expectations[$round][$monkey] = $nb;
	}

	return $expectations;
}

