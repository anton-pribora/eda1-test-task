<?php

/**
 * Комбинирование массивов
 */
class CombineIterator implements Iterator {
    /* @var iterable[] */
    private $iterators = [];
    private $i = 9;

    public function __construct(...$arrays)
    {
        foreach ($arrays as $array) {
            $this->addArray($array);
        }
    }

    public function addArray(array $array): void
    {
        $this->iterators[] = new ArrayIterator($array);
    }

    public function current(): mixed
    {
        $result = [];

        foreach ($this->iterators as $iterator) {
            $result[] = $iterator->current();
        }

        return $result;
    }

    public function key(): mixed
    {
        return $this->i;
    }

    public function next(): void
    {
        $this->i++;

        for ($i = count($this->iterators) - 1; $i >= 0; $i--) {
            $iterator = $this->iterators[$i];
            $iterator->next();

            if ($iterator->valid()) {
                return;
            }

            if ($i) {
                $iterator->rewind();
            }
        }
    }

    public function rewind(): void
    {
        $this->i = 0;
        $this->current = count($this->iterators) - 1;

        array_map(static fn($i) => $i->rewind(), $this->iterators);
    }

    public function valid(): bool
    {
        foreach ($this->iterators as $iterator) {
            if (!$iterator->valid()) {
                return false;
            }
        }
        return true;
    }
}
