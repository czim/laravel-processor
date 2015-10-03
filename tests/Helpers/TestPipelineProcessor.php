<?php
namespace Czim\Processor\Test\Helpers;

use Czim\Processor\Contracts\ProcessContextInterface;
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
    protected function processSteps()
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


    public function __construct(array $settings = [], ProcessContextInterface $context = null, $throwExceptionInMainStep = false)
    {
        parent::__construct($settings, $context);

        $this->throwExceptionInMainStep = $throwExceptionInMainStep;
    }


    // allow tests to access the process context
    public function testGetContext()
    {
        return $this->context;
    }
}
