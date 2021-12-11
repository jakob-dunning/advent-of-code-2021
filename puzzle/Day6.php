<?php declare(strict_types=1);

class Day6
{
    private const NUM_DAYS = 256;
    private array $fishes = [];

    public function run()
    {
        $data = file_get_contents(__DIR__ . '/../input/day6.txt');
        $fishes = new ArrayObject($this->fishes);

        foreach (explode(",", $data) as $timer) {
            $this->fishes[] = new Fish((int)$timer);
        };

        for ($i = 0; $i < self::NUM_DAYS; $i++) {
            echo "$i\n";

            /** @var Fish $fish */
            foreach (new ArrayIterator($this->fishes) as $fish) {
                $fish->decreaseTimer();
                if($fish->hasSpawned() === true) {
                    $this->fishes[] = new Fish(8);
                }
            }
        }

        echo count($this->fishes);
    }
}

class Fish
{
    private int  $timer;
    private bool $spawn = false;

    public function __construct(int $timer)
    {
        $this->timer = $timer;
    }

    public function decreaseTimer(): void
    {
        if ($this->timer === 0) {
            $this->timer = 6;
            $this->spawn = true;

            return;
        }

        $this->timer--;
    }

    public function hasSpawned(): bool
    {
        if($this->spawn === true) {
            $this->spawn = false;

            return true;
        }

        return false;
    }
}