<?php

const VALID_INCR = 1;
const VALID_DECR = -1;
const INVALID = 0;

if (count($argv) < 2) {
	print("Need input\n");
	exit(1);
}

function parseInput(string $file): array {
	$raw = file_get_contents($file);

	return array_map(
		fn($l) => array_map(
			fn($i) => (int)$i,
			explode(' ', $l)
		),
		explode("\n", trim($raw))
	);
}

function check(int $a, int $b, ?int $prev): int {
	$diff = $a - $b;

	if ($diff == 0 || abs($diff) > 3) {
		return INVALID;
	}

	$kind = $diff > 0 ? VALID_INCR : VALID_DECR;

	if (is_null($prev)) {
		return $kind;
	}

	return $prev === $kind ? $kind : INVALID;
}

function isSafe(array $a): bool {
	$prev = null;

	for ($i = 0; $i + 1 < count($a); $i++) {
		$got = check($a[$i], $a[$i + 1], $prev);
		if ($got === INVALID) {
			return false;
		}
		$prev = $got;
	}

	return true;
}

function isSafeDamped(array $a): bool {
	if (isSafe($a)) {
		return true;
	}

	for ($i = 0; $i < count($a); $i++) {
		$t = $a;
		array_splice($t, $i, 1, []);

		if (isSafe($t)) {
			return true;
		}
	}

	return false;
}

function getSafeCount(array $ar, callable $heuristic): int {
	return array_sum(
		array_map(
			$heuristic,
			$ar
		)
	);
}

$data = parseInput($argv[1]);

printf("Safe: %d\n", getSafeCount($data, fn($a) => isSafe($a) ? 1 : 0));
printf("Safe damped: %d\n", getSafeCount($data, fn($a) => isSafeDamped($a) ? 1 : 0));
