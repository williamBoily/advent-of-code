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

$total = 0;
foreach ($inputs as $key => $line) {
	$digits = preg_replace('/\D/', '', $line);
	$digits =  substr($digits, 0, 1) . substr($digits, -1);

	$total += $digits;
}

echo "$total";
echo PHP_EOL;
exit;
