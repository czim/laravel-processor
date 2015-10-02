<?php
namespace Czim\Processor\Test\Helpers;

class TestPipelineProcessorWithInitStep extends TestPipelineProcessor
{

    protected function gatherInitProcessSteps()
    {
        return [
            TestProcessStep::class,
        ];
    }

    // disable main steps
    protected function gatherProcessSteps()
    {
        return [];
    }
}
