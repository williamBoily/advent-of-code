<?php

require_once __DIR__  . '/../util.php';

$inputFile = getInputFile(__DIR__, $argv[1] ?? '');

$inputs = file($inputFile, FILE_IGNORE_NEW_LINES);
if(false === $inputs){
	echo 'Cannot read input file';
	exit;
}

$tree = [];
$folderSize = [];

$dirLevel = [];
foreach ($inputs as $key => $line) {
	$line = trim($line);

	if(1 === preg_match('/^\$ cd (.*)$/', $line, $matches)){
		changeDir($dirLevel, $matches[1]);
		continue;
	}

	$currentDir = getCurrentDir($dirLevel);
	if(!isset($folderSize[$currentDir])){
		$folderSize[$currentDir] = 0;
	}

	if(1 === preg_match('/^dir (.*)$/', $line, $matches)){
		$fullPath = getFullPath($currentDir, $matches[1]);
		$tree[$fullPath] = $currentDir;
		continue;
	}

	if(1 === preg_match('/^(\d+) .+$/', $line, $matches)){
		$folderSize[$currentDir] += (int)$matches[1];
		continue;
	}
}

$totalSize = [];
calculateTotalSize("/", $tree, $totalSize, $folderSize);

asort($totalSize);

$unusedSpace = 70000000 - $totalSize['/'];
$spaceToFree = 30000000 - $unusedSpace;

$dirToDelete = '';
foreach ($totalSize as $dir => $size) {
	if($size >= $spaceToFree){
		echo $size . "\n";
		break;
	}
}

function calculateTotalSize($parentDir, $tree, &$totalSize, &$folderSize){
	$currentFolderSize = $folderSize[$parentDir];
	$nodes = array_keys($tree, $parentDir);
	foreach ($nodes as $dir) {
		$currentFolderSize += calculateTotalSize($dir, $tree, $totalSize, $folderSize);
	}

	$totalSize[$parentDir] = $currentFolderSize;
	return $currentFolderSize;
}

function changeDir(&$dirLevel, $targetDir){
	if($targetDir === '/'){
		$dirLevel = ['/'];
		return;
	}

	if($targetDir === '..'){
		array_pop($dirLevel);
		return;
	}

	$dirLevel[] = $targetDir;
}

function getCurrentDir($dirLevel){
	$pwd = implode('/', $dirLevel);
	if($pwd !== '/'){
		$pwd = substr($pwd, 1);
	}

	return $pwd;
}

function getFullPath($currentPath, $dir){
	if($currentPath === '/'){
		return $currentPath . $dir;
	}

	return $currentPath . '/' . $dir;
}
