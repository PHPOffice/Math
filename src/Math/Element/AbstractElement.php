<?php

namespace PhpOffice\Math\Element;

use PhpOffice\Math\Math;

abstract class AbstractElement
{
    /**
     * @var Math|AbstractGroupElement|null
     */
    protected $parent;

    /**
     * @param Math|AbstractGroupElement|null $parent
     */
    public function setParent($parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Math|AbstractGroupElement|null
     */
    public function getParent()
    {
        return $this->parent;
    }
}
