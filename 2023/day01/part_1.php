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

echo "done";
echo PHP_EOL;
exit;
