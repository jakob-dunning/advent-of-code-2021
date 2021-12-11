<?php declare(strict_types=1);

class Day4
{
    private $boards = [];

    public function run()
    {
        $gameData      = file_get_contents(__DIR__ . '/../input/day4.txt');
        $gameDataArray = explode("\n\n", $gameData);

        $calledNumbers = explode(',', $gameDataArray[0]);
        $boardData     = array_slice($gameDataArray, 1);

        foreach ($boardData as $data) {
            $this->boards[] = new Board(explode(' ', trim(str_replace(["\n", '  '], ' ', $data))));
        }

        /** @var Board $winner */
        [$lastWinner, $currentNumber] = $this->play($calledNumbers);

        echo $lastWinner->getSumOfUnmarkedFieldNumbers() * $currentNumber;
    }

    public function play(array $calledNumbers): array
    {
        foreach ($calledNumbers as $calledNumber) {
            $losers = array_filter($this->boards, function (Board $board) {
                return $board->hasWon() === false;
            });

            /** @var Board $board */
            foreach ($losers as $board) {
                $board->setCalledField((int)$calledNumber);

                if(count($losers) === 1 && $board->hasWon() === true) {
                    return [$board, $calledNumber];
                }
            }
        }
    }
}

class Board
{
    private const WIDTH  = 5;
    private const HEIGHT = 5;
    private array $fields              = [];
    private array $winningCombinations = [];

    public function __construct(array $numbers)
    {
        for ($i = 0; $i < self::WIDTH * self::HEIGHT; $i++) {
            $this->fields[] = new Field((int)$numbers[$i]);
        }

        for ($i = 0; $i < self::WIDTH; $i++) {
            $this->winningCombinations[] = range($i, $i + 20, 5);
        }

        for ($i = 0; $i < self::WIDTH; $i++) {
            $this->winningCombinations[] = range($i * self::HEIGHT, $i * self::HEIGHT + 4);
        }
    }

    public function setCalledField(int $number)
    {
        /** @var Field $field */
        foreach ($this->fields as $field) {
            if ($field->getNumber() === $number) {
                $field->set();
            }
        }
    }

    public function hasWon(): bool
    {
        foreach ($this->winningCombinations as $combination) {
            if($this->isWinningCombination($combination) === true) {
                return true;
            }
        }

        return false;
    }

    private function isWinningCombination(array $combination): bool
    {
        foreach ($combination as $number) {
            if ($this->fields[$number]->isSet() === false) {
                return false;
            }
        }

        return true;
    }

    public function getSumOfUnmarkedFieldNumbers(): int
    {
        $sum = 0;

        /** @var Field $field */
        foreach ($this->fields as $field) {
            if ($field->isSet() === false) {
                $sum += $field->getNumber();
            }
        }

        return $sum;
    }

    public function draw()
    {
        echo "\n";

        for ($i = 0; $i < self::HEIGHT; $i++) {
            for ($j = 0; $j < self::WIDTH; $j++) {
                echo $this->fields[($i * self::HEIGHT) + $j]->isSet() ? ' X ' : ' ' . $this->fields[($i * self::HEIGHT) + $j]->getNumber() . ' ';
            }
            echo "\n\n";
        }
        echo "\n\n";
    }
}

class Field
{
    private bool $isSet = false;
    private int  $number;

    public function __construct(int $number)
    {
        $this->number = $number;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function set(): void
    {
        $this->isSet = true;
    }

    public function isSet(): bool
    {
        return $this->isSet;
    }
}
