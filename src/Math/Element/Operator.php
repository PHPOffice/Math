<?php

namespace PhpOffice\Math\Element;

class Operator extends AbstractElement
{
    /**
     * @var string
     */
    protected $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}