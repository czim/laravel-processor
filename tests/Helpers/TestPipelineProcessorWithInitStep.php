<?php
namespace Czim\Processor\Test\Helpers;

class TestPipelineProcessorWithInitStep extends TestPipelineProcessor
{

    protected function initProcessSteps()
    {
        return [
            TestProcessStep::class,
        ];
    }

    // disable main steps
    protected function processSteps()
    {
        return [];
    }
}
