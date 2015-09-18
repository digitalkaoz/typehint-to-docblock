# TypeHints to DocBlock

the intention for this tiny Project is the lack of support for typehinted Collaborators in PHPSpec on PHP7+ 

https://github.com/phpspec/phpspec/issues/659

this library can convert typehinted methods to docblocks (and removing the typehints)
and the other way around

[![Build Status](https://travis-ci.org/digitalkaoz/typehint-to-docblock.svg?branch=master)](https://travis-ci.org/digitalkaoz/typehint-to-docblock)

## Installation

```
$ composer require digitalkaoz/typehint-to-docblock dev-master
``` 

## Usage

```
$ bin/typehint-to-docblock transform FOLDER
$ bin/typehint-to-docblock transform --pattern=/^foo$/ FOLDER
```

where `FOLDER` is one or more paths to php classes
if `--pattern` is provided only methods which matches this regex pattern will be modified

## Examples

this

```php

namespace Foo\Bar;

use Lol\Cat;
use Bar\Bazz;

class Test
{
    function it_can_do_something(Cat $cat, Bazz $bazz)
    {
    }
}
```

will be converted to this

```php

namespace Foo\Bar;

use Lol\Cat;
use Bar\Bazz;

class Test
{
    /**
     * it_can_do_something
     * 
     * @param \Lol\Cat $cat
     * @param \Bar\Bazz $bazz
     */
    function it_can_do_something($cat, $bazz)
    {
    }
}
```

## Tests

```
$ composer test
```

## TODO

* make the resaving of files a bit less obstrusive
* write a `reverse` Visitor which converts from DocBlock to TypeHint