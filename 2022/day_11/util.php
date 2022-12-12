<?php

/**
 * LCM(Lowest Common Multiplier) by Prime Factorization Method
 * 
 * 
 * Prime number: number that can only be divided by 1 and itself.
 * Prime Factors of a Number: set of prime numbers that when multipled all together, give the original number.
 * 
 * One way to find the least common multiple of two numbers is
 * first to list the prime factors of each number.
 * Then multiply each factor the greatest number of times it occurs in either number.
 * If the same factor occurs more than once in both numbers,
 * you multiply the factor by the greatest number of times it occurs.
 *
 */
function LCM($numbers){
	$factors = [];
	// find the prime factors of each numbers
	foreach ($numbers as $key => $number) {
		$factors = array_merge($factors, find_prime_factors($number));
	}

	// count the number each prime in use.
	// This will give the greatest exponent to use on each prime.
	$exponents = array_count_values($factors);
	
	// The product of each factor will give the LCM
	$lcm = 1;
	foreach ($exponents as $factor => $exponent) {
		$lcm *= pow($factor, $exponent);
	}

	return $lcm;
}

function find_prime_factors($num) {
	$factors = [];
	$prime = 2;
	while ($num > 1) {
		if ($num % $prime == 0) {
			$factors[] = $prime;
			$num /= $prime;
		} else {
			$prime++;
		}
	}
	return $factors;
}
