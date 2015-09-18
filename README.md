# TypeHints to DocBlock

the intention for this tiny Project is the lack of support for typehinted Collaborators in PHPSpec on PHP7+

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

## Tests

```
$ composer test
```

## TODO

* make the resaving of files a bit less obstrusive
* write a `reverse` Visitor which converts from DocBlock to TypeHint