<?php declare(strict_types=1);

class Day5
{
    private array $map;

    public function run()
    {
        $data    = file_get_contents(__DIR__ . '/../input/day5.txt');
        $vectors = [];

        foreach (explode("\n", $data) as $vectorString) {
            $vectors[] = Vector::createFromString($vectorString);
        };

        $xMax = 0;
        $yMax = 0;

        /** @var Vector $vector */
        foreach ($vectors as $vector) {
            $xMax = max($vector->getStart()->getX(), $vector->getEnd()->getX(), $xMax);
            $yMax = max($vector->getStart()->getY(), $vector->getEnd()->getY(), $yMax);
        }

        $this->map = array_fill(0, $yMax + 1, []);

        for ($i = 0; $i <= $yMax; $i++) {
            $this->map[$i] = array_fill(0, $xMax + 1, 0);
        }

        foreach ($vectors as $vector) {
            if ($vector->getStart()->getX() === $vector->getEnd()->getX()) {
                $this->drawVerticalLine($vector);

                continue;
            }

            if ($vector->getStart()->getY() === $vector->getEnd()->getY()) {
                $this->drawHorizontalLine($vector);

                continue;
            }

            $this->drawDiagnonalLine($vector);
        }

        $count = 0;
        // echo "\n";

        foreach ($this->map as $line) {
            // echo implode('', $line) . "\n";
            $count += count(array_filter($line, function ($number) {
                return $number > 1;
            }));
        }

        echo $count;
    }

    private function drawVerticalLine(Vector $vector)
    {
        $yMin = min($vector->getStart()->getY(), $vector->getEnd()->getY());
        $yMax = max($vector->getStart()->getY(), $vector->getEnd()->getY());

        for ($i = $yMin; $i <= $yMax; $i++) {
            $this->map[$i][$vector->getStart()->getX()]++;
        }
    }

    private function drawHorizontalLine(Vector $vector)
    {
        $xMin = min($vector->getStart()->getX(), $vector->getEnd()->getX());
        $xMax = max($vector->getStart()->getX(), $vector->getEnd()->getX());

        for ($i = $xMin; $i <= $xMax; $i++) {
            $this->map[$vector->getStart()->getY()][$i]++;
        }
    }

    private function drawDiagnonalLine(Vector $vector)
    {
        $xMin = min($vector->getStart()->getX(), $vector->getEnd()->getX());
        $xMax = max($vector->getStart()->getX(), $vector->getEnd()->getX());
        [$yStart, $yProgression] = ($vector->getStart()->getX() < $vector->getEnd()->getX())
            ? [$vector->getStart()->getY(), ($vector->getStart()->getY() < $vector->getEnd()->getY()) ? 1 : -1]
            : [$vector->getEnd()->getY(), ($vector->getEnd()->getY() < $vector->getStart()->getY()) ? 1 : -1];

        for ($i = 0; $i <= ($xMax - $xMin); $i++) {
            $this->map[$yStart + ($i * $yProgression)][$i+$xMin]++;
        }
    }
}

class Vector
{
    private Point $start;
    private Point $end;

    private function __construct(Point $start, Point $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public static function createFromString(string $vector)
    {
        preg_match('/^(\d+),(\d+) -> (\d+),(\d+)$/', $vector, $matches);

        return new self(new Point((int)$matches[1], (int)$matches[2]), new Point((int)$matches[3], (int)$matches[4]));
    }

    public function getStart(): Point
    {
        return $this->start;
    }

    public function getEnd(): Point
    {
        return $this->end;
    }
}

class Point
{
    private int $x;
    private int $y;

    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }
}