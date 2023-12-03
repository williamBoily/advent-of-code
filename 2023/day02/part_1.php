<?php
namespace AOC2023;

require_once __DIR__ . '/Script.php';

$script = new Script();
$script->printInputFile();

$inputs = file($script->inputFilePath, FILE_IGNORE_NEW_LINES);
if(false === $inputs){
	echo 'Cannot read input file';
	exit;
}

$nano_start = hrtime(true);

$conditions = [
	'red' => 12,
	'green' => 13,
	'blue' => 14
];

$total = 0;
foreach ($inputs as $key => $line) {
	$gameId = preg_replace('/\D/', '', substr($line, 0, strpos($line, ': ')));
	$sets = substr($line, strpos($line, ': ') + 2);
	$sets = explode(';', $sets);
	foreach ($sets as $key => $cubes) {
		$cubes = explode(', ', $cubes);
		
		foreach ($cubes as $colorDraw) {
			list($value, $color) = sscanf($colorDraw, '%d %s');
			if($value > $conditions[$color]){
				continue 3;
			}
		}
	}
	$total += $gameId;
}

echo "$total\n";

$nano_end = hrtime(true);
$nano_time = $nano_end - $nano_start;
$time = $script->nanoToSec($nano_time);
echo "sec:$time\n";
exit;
