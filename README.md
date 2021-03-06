# TypeHints to DocBlock

the intention for this tiny Project is the lack of support for typehinted Collaborators in PHPSpec on PHP7+ 

https://github.com/phpspec/phpspec/issues/659

this library can convert typehinted methods to docblocks (and removing the typehints)
and the other way around

[![Build Status](https://img.shields.io/travis/digitalkaoz/typehint-to-docblock.svg?style=flat-square)](https://travis-ci.org/digitalkaoz/typehint-to-docblock)
[![Dependency Status](https://www.versioneye.com/user/projects/55fc863cedf404000b00050c/badge.svg?style=flat)](https://www.versioneye.com/user/projects/55fc863cedf404000b00050c)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/digitalkaoz/typehint-to-docblock.svg?style=flat-square)](https://scrutinizer-ci.com/g/digitalkaoz/typehint-to-docblock/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/digitalkaoz/typehint-to-docblock/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/digitalkaoz/typehint-to-docblock/?branch=master)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/2adf07d1-81ae-4c73-9640-74bbf841a9d4.svg?style=flat-square)](https://insight.sensiolabs.com/projects/f7633a7e-4577-4a86-b6d9-ccaa75cb7fa0)
[![Latest Stable Version](https://img.shields.io/packagist/v/digitalkaoz/typehint-to-docblock.svg?style=flat-square)](https://packagist.org/packages/digitalkaoz/typehint-to-docblock)
[![Total Downloads](https://img.shields.io/packagist/dt/digitalkaoz/typehint-to-docblock.svg?style=flat-square)](https://packagist.org/packages/digitalkaoz/typehint-to-docblock)
[![StyleCI](https://styleci.io/repos/42720187/shield)](https://styleci.io/repos/42720187)

## Installation

```
$ composer require digitalkaoz/typehint-to-docblock
``` 

## Usage

```
$ bin/typehint-to-docblock transform FOLDER
$ bin/typehint-to-docblock transform --pattern=/^foo$/ FOLDER
```

where `FOLDER` is one or more paths to php classes
if `--pattern` is provided only methods which matches this regex pattern will be modified

## Use on TravisCI

simple use this in your `before_scripts`

```yml
php:
  - 7.0

before_script:
  - bash -c 'if [ "$TRAVIS_PHP_VERSION" == "7.0" ]; then wget https://github.com/digitalkaoz/typehint-to-docblock/releases/download/0.2.2/typehint-to-docblock.phar && php typehint-to-docblock.phar transform spec; fi;'
```

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
