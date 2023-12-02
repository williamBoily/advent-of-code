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

$stringNumbers = [
	'one',
	'two',
	'three',
	'four',
	'five',
	'six',
	'seven',
	'eight',
	'nine',
];

$numbers = array_combine(range('1','9'), $stringNumbers);

$total = 0;
foreach ($inputs as $key => $line) {

	$a = $b = null;
	$aDigit = $bDigit = '';

	foreach ($numbers as $numeric => $string) {
		$offset = 0;
		while(isset($line[$offset]) && false !== ($pos = strpos($line, $numeric, $offset))){
			if($a === null || $pos < $a){
				$a = $pos;
				$aDigit = $numeric;
				echo 'numeric $aDigit = ' . $numeric . PHP_EOL;
			}

			if($b === null || $pos > $b){
				$b = $pos;
				echo 'numeric $bDigit = ' . $numeric . PHP_EOL;
				$bDigit = $numeric;
			}

			$offset = $pos + 1;
		}

		$offset = 0;
		while(isset($line[$offset]) && false !== ($pos = strpos($line, $string, $offset))){
			if($a === null || $pos < $a){
				$a = $pos;
				echo 'string $aDigit = ' . "$string($numeric)" . PHP_EOL;
				$aDigit = $numeric;
			}

			if($b === null || $pos > $b){
				$b = $pos;
				echo 'string $bDigit = ' . "$string($numeric)" . PHP_EOL;
				$bDigit = $numeric;
			}

			$offset = $pos + 1;
		}
	}
	
	$digits = $aDigit . $bDigit;
	echo "$digits\n";

	$total += $digits;
}

echo "$total";
echo PHP_EOL;
exit;
