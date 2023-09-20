<?php

namespace PhpOffice\Math\Element;

abstract class AbstractGroupElement extends AbstractElement
{
    /**
     * @var AbstractElement[]
     */
    protected $elements = [];

    public function add(AbstractElement $element): self
    {
        $this->elements[] = $element;
        $element->setParent($this);

        return $this;
    }

    public function remove(AbstractElement $element): self
    {
        $this->elements = array_filter($this->elements, function ($child) use ($element) {
            return $child != $element;
        });

        $element->setParent(null);

        return $this;
    }

    /**
     * @return AbstractElement[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }
}