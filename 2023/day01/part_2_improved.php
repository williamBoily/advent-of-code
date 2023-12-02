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

$numbersMap = [
	'one' => 1,
	'two' => 2,
	'three' => 3,
	'four' => 4,
	'five' => 5,
	'six' => 6,
	'seven' => 7,
	'eight' => 8,
	'nine' => 9,
];

$total = 0;
foreach ($inputs as $key => $line) {
	$digitsOnlyLine = '';
	$size = strlen($line);
	for ($i=0; $i < $size; $i++) { 
		if(is_numeric($line[$i])){
			$digitsOnlyLine .= $line[$i];
			continue;
		}

		foreach ($numbersMap as $stringNumber => $value) {
			if($i === strpos($line, $stringNumber, $i)){
				$digitsOnlyLine .= $value;
				break;
			}
		}
	}
	
	$digits = substr($digitsOnlyLine, 0, 1) . substr($digitsOnlyLine, -1);


	$total += $digits;
}

echo "$total\n";

$nano_end = hrtime(true);
$nano_time = $nano_end - $nano_start;
$time = $script->nanoToSec($nano_time);
echo "sec:$time\n";
exit;
