<?php
namespace Czim\Processor\Test;

use Czim\DataObject\Contracts\DataObjectInterface;
use Czim\Processor\Contracts\ProcessContextInterface;
use Czim\Processor\Test\Helpers\TestProcessStep;
use Exception;

class ProcessStepTest extends TestCase
{

    /**
     * @test
     */
    function it_calls_its_process_function_when_run_by_a_processor()
    {
        /** @var ProcessContextInterface $context */
        $context = $this->getMockBuilder(ProcessContextInterface::class)
                        ->getMock();

        $step = new TestProcessStep();

        // processor runs the handle() method, so simulate that
        $step->handle($context, function () {});

        static::assertTrue($step->processWasCalled, 'process() method was not called');
    }

    /**
     * @test
     */
    function it_calls_the_next_step_in_the_pipeline()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('!next was called!');

        /** @var ProcessContextInterface $context */
        $context = $this->getMockBuilder(ProcessContextInterface::class)
                        ->getMock();

        $closure = function () {
            // throw custom exception to assert that we got here
            throw new Exception('!next was called!');
        };

        $step = new TestProcessStep();

        // processor runs the handle() method, so simulate that
        $step->handle($context, $closure);
    }

    /**
     * @test
     */
    function it_stores_context_and_context_data_before_process_is_called()
    {
        /** @var DataObjectInterface $data */
        $data = $this->getMockBuilder(DataObjectInterface::class)
                     ->getMock();

        $context = $this->getMockBuilder(ProcessContextInterface::class)
                        ->getMock();

        $context->method('getData')
                ->willReturn($data);

        $step = new TestProcessStep();

        // processor runs the handle() method, so simulate that
        /** @var ProcessContextInterface $context */
        $step->handle($context, function () {});

        static::assertSame($context, $step->testGetContext(), 'Context was not stored');
        static::assertSame($data, $step->testGetData(), 'Data was not stored');
    }

}
