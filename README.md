# Laravel Pipeline Processor

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status](https://travis-ci.org/czim/laravel-processor.svg?branch=master)](https://travis-ci.org/czim/laravel-processor)
[![Latest Stable Version](http://img.shields.io/packagist/v/czim/laravel-processor.svg)](https://packagist.org/packages/czim/laravel-processor)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/7ee4d4b8-9e04-45f0-b5ad-5aeee60e92d6/mini.png)](https://insight.sensiolabs.com/projects/7ee4d4b8-9e04-45f0-b5ad-5aeee60e92d6)


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

Extend `Czim\PipelineProcessor` (or `Czim\AbstractProcessor`) and write implementations for the abstract methods.

Processing is done by calling the `process()` method on your class.
The parameter for this method must be an implementation of `Czim\DataObject\Contracts\DataObjectInterface`
(see the [czim\laravel-dataobject](https://github.com/czim/laravel-dataobject) for more information).

```php
    
    $processor = new Your\Processor();

    $data = new Your\DataObject($someData);
    
    $result = $processor->process($data);
    
    if ( ! $result->success) {
        ...
    }
```

The returned result is an instance of `Czim\Processor\DataObjects\ProcessorResult`.
This is a DataObject with a boolean `success` property, as well as `warnings` and `errors` MessageBags by default.


### Pipeline Processor

A pipeline processor consists of a series of process steps, which are executed in sequence.

A process context is passed into the pipeline and from step to step.
It contains the data to be processed, cache, settings and such and its contents may be modified to affect the way subsequent steps behave.

When exceptions are thrown, the pipeline ends and the remaining steps are not executed.

To use it, extend `Czim\PipelineProcessor` and add the following to your class:

```php

    /**
     * @return array
     */
    protected function processSteps()
    {
        // Set a series of process step classnames and return it
        // these steps must extend Czim\Processor\Steps\AbstractProcessStep
        // or otherwise implement Czim\Processor\Contracts\ProcessStepInterface
        return [
            Your\ProcessSteps\ClassNameHere::class,
            Your\ProcessSteps\AnotherClassNameHere::class,
        ];
    }

```

For more configuration options, see [the PipelineProcessor source](https://github.com/czim/laravel-processor/blob/master/src/PipelineProcessor.php).

#### Process Steps

Process steps can extend `Czim\Processor\Steps\AbstractProcessStep` and implement the `process()` method:
 
```php
    protected function process()
    {
        // Define your custom processing here.
        // The data object can be accessed through $this->data
        // and the process context through $this->context
    }
```

#### Process Context

A ProcessContext is an instance that represents the context in which the pipeline steps take place.
It stores the data passed into the `process()` method. It can also store settings and a cache.
 
A `ContextRepositoryTrait` for your own extensions is also  provided,
in case you want to store repositories with the [czim\laravel-repository](https://github.com/czim/laravel-repository) package in the context.


#### Database Transaction

By default, the (main) pipeline is executed in a database transaction; it is comitted on succesfully completing all the steps, and rolled back on any exception thrown.

To run the process without a database transaction, set the following property in your `PipelineProcessor` extension:

```php
    protected $databaseTransaction = false;
```

### Simple Processor

If a pipeline is overkill, you can also use a simpler approach.

Extend `Czim\AbstractProcessor` and add the following to your class:

```php

    protected function doProcessing()
    {
        // Define your custom processing here.
        // The data object can be accessed through $this->data
        
        // The result data object that will be returned can
        // be modified through $this->result
    }
```

For more configuration options, see [the AbstractProcessor source](https://github.com/czim/laravel-processor/blob/master/src/AbstractProcessor.php).

## To Do

- Make App/Container injectable, remove dependency on laravel's app() function


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
