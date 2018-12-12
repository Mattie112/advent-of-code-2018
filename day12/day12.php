<?php
/**
 * https://adventofcode.com/2018/day/11
 */

// todo add isset's to prevent notices
error_reporting(0);
$initial = "";
$notes = [];

if ($file = fopen(__DIR__ . "/day12-input.txt", "rb")) {
    while (!feof($file)) {
        $line = trim(fgets($file));
        if ($line === "") {
            continue;
        }

        if (preg_match("@initial state: (.*)@", $line, $matches)) {
            $initial = $matches[1];
            $initial = str_split($initial);
        }

        if (preg_match("@(.*) => (.)@", $line, $matches)) {
            $notes[$matches[1]] = $matches[2];
        }
    }
}

// Start by adding the 'left' lots
$pots = [];
$pots[-3] = ".";
$pots[-2] = ".";
$pots[-1] = ".";

// Setup the initial pots
foreach ($initial as $item) {
    if ($item === "#") {
        $pots[] = "#";
    } else {
        $pots[] = ".";
    }
}

// Now add the 'right' pots
$pots[] = ".";
$pots[] = ".";
$pots[] = ".";

$next_iter_pots = $pots;
$generations = 20;
$count = 0;
$number_count = 0;

for ($i = 0; $i < $generations; $i++) {
    foreach ($pots as $index => $pot) {
        // We simply use the string to check the nodes
        $str_to_check = "";
        $str_to_check .= $pots[$index - 2];
        $str_to_check .= $pots[$index - 1];
        $str_to_check .= $pots[$index];
        $str_to_check .= $pots[$index + 1];
        $str_to_check .= $pots[$index + 2];

        if (isset($notes[$str_to_check])) {
            $next_iter_pots[$index] = $notes[$str_to_check];
        } else {
            $next_iter_pots[$index] = ".";
        }
    }

    // Add more pots to the left when needed
    reset($pots);
    $first_key = key($pots);
    if ($pots[$first_key + 2] === "#") {
        $next_iter_pots[$first_key - 1] = ".";
        ksort($next_iter_pots);
    }

    // As we have negative indexes we'll need to know the last element
    end($pots);
    $last_key = key($pots);
    // If we end with a plant add more pots (that is how I read the example)
    if ($pots[$last_key - 2] === "#") {
        $next_iter_pots[] = ".";
    }
    // Store the next iteration into the current iteration
    $pots = $next_iter_pots;
    $count += array_count_values($pots)["#"];
    echo $count . PHP_EOL;
}

// Count the numbers, not the plants for part 1
$number_count = 0;
foreach ($pots as $index => $pot) {
    if ($pot === "#") {
        $number_count += $index;
    }
}

echo PHP_EOL . "Part #1: " . $number_count . PHP_EOL;
