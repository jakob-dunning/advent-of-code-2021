<?php declare(strict_types=1);

class Day1 {
    public function run() {
        $depths = file_get_contents(__DIR__ . '/../input/day1.txt');
        $depthsArray = explode("\n", $depths);
        $depthIncreasedCounter = 0;

        for($i=3; $i < count($depthsArray); $i++) {
            $measurement1 = $depthsArray[$i-1] + $depthsArray[$i-2] + $depthsArray[$i-3];
            $measurement2 = $depthsArray[$i] + $depthsArray[$i-1] + $depthsArray[$i-2];

            if($measurement2 > $measurement1) {
                $depthIncreasedCounter++;
            }
        }

        echo $depthIncreasedCounter;
    }
}
