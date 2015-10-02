<?php
namespace Czim\Processor\Test\Helpers;

use Czim\Processor\PipelineProcessor;

class TestPipelineProcessor extends PipelineProcessor
{
    /**
     * @var bool
     */
    private $throwExceptionInMainStep;

    /**
     * @return array
     */
    protected function gatherProcessSteps()
    {
        if ($this->throwExceptionInMainStep) {
            return [
                TestProcessStepException::class,
            ];
        }

        return [
            TestProcessStep::class,
        ];
    }


    public function __construct($throwExceptionInMainStep = false)
    {
        parent::__construct();
        $this->throwExceptionInMainStep = $throwExceptionInMainStep;
    }


    // allow tests to access the process context
    public function testGetContext()
    {
        return $this->context;
    }
}
