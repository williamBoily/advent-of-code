<?php

function getInputFile($basePath, $type){
	if($type == 'real'){
		return $basePath . "/input.txt";
	}

	return $basePath . "/input_example.txt";
}
