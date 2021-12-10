<?php declare(strict_types=1);

class Day2 {
    public function run() {
        $movements = file_get_contents(__DIR__ . '/../input/day2.txt');
        $movementsArray = explode("\n", $movements);
        $horizontalPosition = 0;
        $aim = 0;
        $depth = 0;

        foreach ($movementsArray as $movement) {
            list($direction, $count) = explode(' ', $movement);

            switch ($direction) {
                case 'up':
                    $aim -= $count;
                    break;
                case 'down':
                    $aim += $count;
                    break;
                case 'forward':
                    $horizontalPosition += $count;
                    $depth += $aim * $count;
                    break;
            }
        }

        echo $horizontalPosition * $depth;
    }
}
