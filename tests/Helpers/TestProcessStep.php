<?php

namespace Czim\Processor\Test\Helpers;

use Czim\Processor\Steps\AbstractProcessStep;

class TestProcessStep extends AbstractProcessStep
{
    public $processWasCalled = false;

    protected function process()
    {
        $this->processWasCalled = true;

        $this->context->setSetting('step_was_run', true);
    }


    // ------------------------------------------------------------------------------
    //      Make it possible to test the data that should be set automatically
    // ------------------------------------------------------------------------------

    public function testGetData()
    {
        return $this->data;
    }

    public function testGetContext()
    {
        return $this->context;
    }
}
