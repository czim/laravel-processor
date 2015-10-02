<?php
namespace Czim\Processor\Test\Helpers;

class TestProcessStepException extends TestProcessStep
{
    protected function process()
    {
        parent::process();

        throw new \Exception('!process step exception!');
    }
}
