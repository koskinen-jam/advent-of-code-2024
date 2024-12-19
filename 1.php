<?php

if (count($argv) < 2) {
	print("Need input\n");
	exit(1);
}

function parseInput(string $input): array {
	$left = $right = [];

	foreach (
		array_map(
			function ($s) {
				$pair = explode('   ', $s);
				return [(int)$pair[0], (int)$pair[1]];
			},
			explode("\n", trim($input))
		) as $p) {
		$left[] = $p[0];
		$right[] = $p[1];
	}

	sort($left);
	sort($right);

	return [
		'left' => $left,
		'right' => $right,
	];
}

function calcDistance(array $l, array $r): int {
	$res = 0;

	foreach ($l as $i => $v) {
		$res += abs($v - $r[$i]);
	}

	return $res;
}

function calcSimilarity(array $l, array $r): int {
	$appearances = [];

	foreach ($r as $n) {
		if (! isset($appearances[$n])) {
			$appearances[$n] = 1;
		} else {
			$appearances[$n] += 1;
		}
	}

	$res = 0;

	foreach ($l as $n) {
		$res += $n * ($appearances[$n] ?? 0);
	}

	return $res;
}

$input = file_get_contents($argv[1]);
$input = parseInput($input);

printf("Distance: %d\n", calcDistance($input['left'], $input['right']));
printf("Similarity: %d\n", calcSimilarity($input['left'], $input['right']));
