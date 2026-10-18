## Usage

To attach a subscript to an expression, use the `PhpOffice\Math\Element\Subscript` class.

### Methods
#### getBase

The method has no parameter.

#### getSubscript

The method has no parameter.

#### setBase

The method has one parameter : 

* `PhpOffice\Math\Element\AbstractElement` **$element**

#### setSubscript

The method has one parameter : 

* `PhpOffice\Math\Element\AbstractElement` **$element**

## Example

### Math
<math display="block">
  <msub>
    <mi>X</mi>
    <mn>2</mn>
  </msub>
</math>

### XML
``` xml
<math display="block">
  <msub>
    <mi>X</mi>
    <mn>2</mn>
  </msub>
</math>
```

### PHP

``` php
<?php

use PhpOffice\Math\Element;
use PhpOffice\Math\Math;

$math = new Math();

$subscript = new Element\Subscript();
$subscript->setBase(new Element\Identifier('X'));
$subscript->setSubscript(new Element\Numeric(2));

$math->add($subscript);
```
