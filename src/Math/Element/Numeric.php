<?php

namespace PhpOffice\Math\Element;

class Numeric extends AbstractElement
{
    /**
     * @var float
     */
    protected $content;

    public function __construct(float $content)
    {
        $this->content = $content;
    }

    public function getContent(): float
    {
        return $this->content;
    }
}