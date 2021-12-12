<?php declare(strict_types=1);

class Day6
{
    private const NUM_DAYS_PART1 = 80;
    private const NUM_DAYS_PART2 = 256;
    private Swarm $swarm;
    private array $swarmBuckets = [];

    public function run()
    {
        $data = file_get_contents(__DIR__ . '/../input/day6.txt');
        $data = explode(",", $data);

        $this->part1($data);

        echo '###';

        $this->part2($data);
    }

    private function part1(array $data): void
    {
        $this->swarm = new Swarm();

        foreach ($data as $timer) {
            $this->swarm->add(new Fish((int)$timer));
        };

        for ($i = 0; $i < self::NUM_DAYS_PART1; $i++) {
            /** @var Fish $fish */
            foreach ($this->swarm as $fish) {
                $fish->decreaseTimer();
                if ($fish->hasSpawned() === true) {
                    $this->swarm->add(new Fish(8));
                }
            }
        }

        echo count($this->swarm);
    }

    private function part2(array $data)
    {
        $this->swarmBuckets = array_fill(0, 9, 0);

        foreach ($data as $timer) {
            $this->swarmBuckets[$timer]++;
        }

        for ($i = 0; $i < self::NUM_DAYS_PART2; $i++) {
            $spawningFishCount = array_shift($this->swarmBuckets);

            $this->swarmBuckets[6] += $spawningFishCount;
            $this->swarmBuckets[8] = $spawningFishCount;
        }

        echo array_sum($this->swarmBuckets);
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
        if ($this->spawn === true) {
            $this->spawn = false;

            return true;
        }

        return false;
    }
}

class Swarm implements IteratorAggregate, Countable
{
    private array $fishes;

    public function add(Fish $fish)
    {
        $this->fishes[] = $fish;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->fishes);
    }

    public function count(): int
    {
        return count($this->fishes);
    }
}