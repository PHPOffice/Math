<?php

namespace PhpOffice\Math\Element;

class Subscript extends AbstractElement
{
    /**
     * @var AbstractElement
     */
    protected $base;

    /**
     * @var AbstractElement
     */
    protected $subscript;

    public function __construct(AbstractElement $base, AbstractElement $subscript)
    {
        $this->setBase($base);
        $this->setSubscript($subscript);
    }

    public function getBase(): AbstractElement
    {
        return $this->base;
    }

    public function getSubscript(): AbstractElement
    {
        return $this->subscript;
    }

    public function setBase(AbstractElement $element): self
    {
        $this->base = $element;

        return $this;
    }

    public function setSubscript(AbstractElement $element): self
    {
        $this->subscript = $element;

        return $this;
    }
}
