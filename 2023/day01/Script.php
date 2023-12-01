<?php
namespace AOC2023;

class Script {
	public readonly string $inputFilePath;
	private string $fileColorCode = '\033[0m';

	public function __construct()
	{
		$this->inputFilePath = $this->getInputFile();
		if(! file_exists($this->inputFilePath)){
			exit("File not found: {$this->inputFilePath}\n");
		}
	}

	private function getInputFile() {
		$shortopts = 'i::s';
		$longopts = [
			'input-file::',
			'sample',
			'help'
		];
	
		$options = getopt($shortopts, $longopts);

		if(isset($options['help'])){
			echo "Usage:\n[-i [name]] [-s]\n\n";
			echo "Options\n";
			echo "i, --input-file: -i [name] <name> will use input_<name>.txt\n";
			echo "s, --sample: Force the usage of input.txt. Take precedence over -i.\n\n";
			echo "Test script with -i [name]. When ready to test with the real data, add -s\n";
			exit;
		}
		
		if(null !== ($options['s'] ?? $options['sample'] ?? null)) {
			$this->fileColorCode = "\033[32m\033[40m";
			return __DIR__ . '/input.txt';
		}

		$this->fileColorCode = "\033[1;33m\033[44m";
		$inputFile = $options['i'] ?? $options['input-file'] ?? '';
		return match ((string)$inputFile) {
			'' => __DIR__ . '/input_example.txt',
			default => __DIR__ . "/input_$inputFile.txt"
		};
	}

	public function printInputFile()
	{
		$file = basename($this->inputFilePath);
		echo "{$this->fileColorCode}$file\033[0m\n";
	}

}

