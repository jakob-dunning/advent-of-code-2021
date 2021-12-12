<?php declare(strict_types=1);

class Day6
{
    private const NUM_DAYS = 256;
    private Swarm $swarm;

    public function run()
    {
        $this->swarm = new Swarm();
        $data = file_get_contents(__DIR__ . '/../input/day6.txt');

        foreach (explode(",", $data) as $timer) {
            $this->swarm->add(new Fish((int)$timer));
        };

        for ($i = 0; $i < self::NUM_DAYS; $i++) {
            echo "$i\n";
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