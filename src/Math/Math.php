<?php

namespace PhpOffice\Math;

class Math
{
    /**
     * @var AbstractElement[]
     */
    protected $elements = [];

    /**
     * @param Element\AbstractElement $element
     * @return self
     */
    public function add(Element\AbstractElement $element): self
    {
        $this->elements[] = $element;
        $element->setParent($this);

        return $this;
    }

    /**
     * @param Element\AbstractElement $element
     * @return self
     */
    public function remove(Element\AbstractElement $element): self
    {
        $this->elements = array_filter($this->elements, function ($child) use ($element) {
            return $child != $element;
        });
        $component->setParent(null);

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