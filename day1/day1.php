<?php
/**
 * https://adventofcode.com/2018/day/1
 */

$frequency = 0;
$tmp_frequency = 0;
$frequency2 = null;
$pass = 0;
$stored_frequencies = [];
$run = true;
while ($run) {
    if ($file = fopen(__DIR__ . "/day1-input.txt", "rb")) {
        while (!feof($file)) {
            $line = trim(fgets($file));

            // Yes Joff I've added a check this time before EVALLING :)
            if (preg_match("#[\+\-]\d+#", $line)) {
                // eval FTW (again)
                $tmp_frequency = eval("return " . $tmp_frequency . $line . ";");

                // Only store answer 1 the first loop
                if ($pass === 0) {
                    $frequency = $tmp_frequency;
                }

                // Check to see if we already got the frequecy
                if (isset($stored_frequencies[$tmp_frequency])) {
                    $frequency2 = $tmp_frequency;
                    $run = false;
                    break;
                }

                $stored_frequencies[$tmp_frequency] = true;
            }
        }
        fclose($file);
    }
    $pass++;
}

echo "Part 1: " . $frequency . PHP_EOL;
echo "Part 2: " . $frequency2 . " (" . $pass . " passes)" . PHP_EOL;

