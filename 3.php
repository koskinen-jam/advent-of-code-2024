<?php

if (count($argv) < 2) {
	print("Need input\n");
	exit(1);
}

$input = file_get_contents($argv[1]);

function parseInput(string $input): array {
	preg_match_all('/(?<inst>mul)\((?<a>\d{1,3}),(?<b>\d{1,3})\)/', $input, $match);

	$ret = [];

	for ($i = 0; $i < count($match[0]); $i++) {
		$ret[] = [
			'full' => $match[0][$i],
			'inst' => $match['inst'][$i],
			'a' => (int)$match['a'][$i],
			'b' => (int)$match['b'][$i],
		];
	}

	return $ret;
}

function calc(array $data): int {
	$res = 0;

	foreach ($data as $d) {
		$res += $d['a'] * $d['b'];
	}

	return $res;
}

$data = parseInput($input);

printf("Sum of multiplications: %d\n", calc($data));
