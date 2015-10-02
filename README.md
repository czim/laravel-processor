# Laravel Pipeline Processor

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status](https://travis-ci.org/czim/laravel-processor.svg?branch=master)](https://travis-ci.org/czim/laravel-processor)
[![Latest Stable Version](http://img.shields.io/packagist/v/czim/laravel-processor.svg)](https://packagist.org/packages/czim/laravel-processor)

Framework for building modular, pipelined data processors. 

The idea behind this is to have a configurable, clean and testable setup for complex data processing.
It carries a lot of overhead, of course, so this only makes sense for fairly demanding (background) processing.

Usage example: This was constructed to better handle extensive product and debtor datasheet imports for a particular project.
The imported data is converted to a relational database structure spanning many tables.
Using pipelined processing, this can be done in discrete, separately testable process step classes that each have their own responsibility.  


## Install

Via Composer

``` bash
$ composer require czim/laravel-processor
```

## Usage

...

## To Do

- Make App/Container injectable, remove dependency on laravel's app() function
- Add settings initialization for AbstractProcessor
- Test repositories in ProcessContext?

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [Coen Zimmerman][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/czim/laravel-processor.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/czim/laravel-processor.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/czim/laravel-processor
[link-downloads]: https://packagist.org/packages/czim/laravel-processor
[link-author]: https://github.com/czim
[link-contributors]: ../../contributors
