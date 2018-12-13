<?php
run();
function run()
{
// convert input
    $input = explode("\n", trim(file_get_contents(__DIR__ . "/day12-input.txt")));
    $pattern = [];
    $state = [];
    foreach ($input as $line) {
        if (preg_match("/^([\.#]+)\s*=>\s*#$/", $line, $match)) {
            $pattern[] = str_split($match[1]);
        } elseif (preg_match("/^initial state: ([\.#]+)$/", $line, $match)) {
            $state = str_split($match[1]);
        }
    }
    $output1 = null;
    $output2 = null;
    $prev_answer = 0;
    $prev_diff = 0;
// loop over all generations
    for ($generation = 1; $generation < 50000000000; $generation++) {
// determine the max and min of the current positions and make the search range one wider
        $keys = array_keys($state);
        $max = max($keys) + 1;
        $min = min($keys) - 1;
        $new_state = [];
// now loop over all plants
        for ($i = $min; $i <= $max; $i++) {
// determine if the position should have a plant in the next iteration
// by comparing the plants and its four direct neighbours on both sites
// match a table of defined patterns
            $check =
                in_array(
                    [
                        $state[$i - 2] ?? ".",
                        $state[$i - 1] ?? ".",
                        $state[$i + 0] ?? ".",
                        $state[$i + 1] ?? ".",
                        $state[$i + 2] ?? ".",
                    ],
                    $pattern,
                    true
                );
// if so, place a plant in the next space, or an empty space
            $new_state[$i] = $check ? "#" : ".";
        }
// our new calculated state will be the new current state
        $state = $new_state;
// generate an answer, by summing up the positions were there is a plant
        $answer = array_reduce(array_keys($state), function ($carry, $key) use ($state) {
            return $carry + ($state[$key] === "#" ? $key : 0);
        }, 0);
// if we are at generation 20, we have found the answer for the first part of the question
        if ($generation === 20) {
// lets save it for later
            $output1 = $answer;
        }
// now we see what the diff it between this iterations answer and the previous iteration
        $diff = $answer - $prev_answer;
// repetition is the root of al optimizations: if we have the same difference between
// answers as we did in the previous iteration we are repeating indefinitely
        if ($prev_diff === $diff) {
// so our answer is the current answer, plus the difference we found times the number of generations left
            $output2 = $answer + ($diff * (50000000000 - $generation));
            echo PHP_EOL."2: " . $output2 . PHP_EOL;
// stop it, enough calculations already!
            break;
        }
// store the difference between this and the previous iteration, and the current answer
        $prev_diff = $diff;
        $prev_answer = $answer;
        echo $answer . PHP_EOL;

    }

}
