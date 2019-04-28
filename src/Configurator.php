<?php

namespace NikolayOskin\Contents;

class Configurator
{
    public $defaultTags = ['h2', 'h3'];
    public $minLength = 10;
    public $minHeaders = 4;

    public function setMinLength(int $length)
    {
        $this->minLength = $length;
    }

    public function setMinHeaders(int $number)
    {
        $this->minHeaders = $number;
    }

    /**
     * @return array
     */
    public function getDefaultTags(): array
    {
        return $this->defaultTags;
    }

    /**
     * @return int
     */
    public function getMinLength(): int
    {
        return $this->minLength;
    }
}
