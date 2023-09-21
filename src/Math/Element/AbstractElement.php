<?php

namespace PhpOffice\Math\Element;

abstract class AbstractElement
{
    /**
     * @var string
     */
    protected $parent;

    public function setParent($parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }
}
