<?php declare(strict_types=1);

class Day3
{
    public function run()
    {
        $diagnostics      = file_get_contents(__DIR__ . '/../input/day3.txt');
        $diagnosticsArray = explode("\n", $diagnostics);
        array_walk($diagnosticsArray, function (&$diagnostics) {
            $diagnostics = str_split($diagnostics);
        });

        $gammaRate   = $this->calculateGammaRate($diagnosticsArray);
        $epsilonRate = $this->calculateEpsilonRate($gammaRate);

        echo bindec($gammaRate) * bindec($epsilonRate);

        echo ' ### ';

        $oxygenGeneratorRating = $this->calculateOxygenGeneratorRating($diagnosticsArray);
        $co2ScrubberRating = $this->calculateCo2ScrubberRating($diagnosticsArray);

        echo bindec(implode('', reset($oxygenGeneratorRating))) * bindec(implode('', reset($co2ScrubberRating)));
    }

    private function calculateGammaRate(array $diagnosticsArray): string
    {
        $gammaRate = '';

        for ($i = 0; $i < 12; $i++) {
            $bitCount = array_count_values(array_column($diagnosticsArray, $i));
            ksort($bitCount);

            $gammaRate .= ($bitCount[0] > $bitCount[1]) ? '0' : '1';
        }

        return $gammaRate;
    }

    private function calculateEpsilonRate(string $gammaRateBinary): string
    {
        return strtr($gammaRateBinary, [1, 0]);
    }

    private function calculateOxygenGeneratorRating(array $diagnosticsArray): array
    {
        for ($i = 0; $i < 12; $i++) {
            $diagnosticsArray = $this->filterByBitAtPosition(
                $diagnosticsArray,
                $i,
                function (array $bitCount) {
                    return $bitCount[1] >= $bitCount[0] ? '1' : '0';
                });
        }

        return $diagnosticsArray;
    }

    private function calculateCo2ScrubberRating(array $diagnosticsArray): array
    {
        for ($i = 0; $i < 12; $i++) {
            $diagnosticsArray = $this->filterByBitAtPosition(
                $diagnosticsArray,
                $i,
                function (array $bitCount) {
                    return $bitCount[0] <= $bitCount[1] ? '0' : '1';
                });
        }

        return $diagnosticsArray;
    }

    private function filterByBitAtPosition(array $diagnosticsArray, int $position, callable $algorithm) {
        if(count($diagnosticsArray) === 1) {
            return $diagnosticsArray;
        }

        $bitCount = array_count_values(array_column($diagnosticsArray, $position));
        ksort($bitCount);

        $mostCommonBit = $algorithm($bitCount);

        return array_filter($diagnosticsArray, function ($diagnostics) use ($mostCommonBit, $position) {
            return $diagnostics[$position] === $mostCommonBit;
        });
    }
}
