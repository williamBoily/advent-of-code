<?php

function getInputFile($basePath, $type){
	if($type == 'test'){
		return $basePath . "/input_example.txt";
	}

	return $basePath . "/input.txt";
}
