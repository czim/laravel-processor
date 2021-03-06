<?php

namespace Czim\Processor\Test;

use Czim\DataObject\Contracts\DataObjectInterface;
use Czim\Processor\Contracts\ProcessContextInterface;
use Czim\Processor\Test\Helpers\TestProcessStep;
use Exception;
use Mockery;

class ProcessStepTest extends TestCase
{
    /**
     * @test
     */
    public function it_calls_its_process_function_when_run_by_a_processor(): void
    {
        /** @var ProcessContextInterface|Mockery\Mock|Mockery\MockInterface $context */
        $context = Mockery::mock(ProcessContextInterface::class);
        $context->shouldReceive('setSetting');
        $context->shouldReceive('getData')->andReturn(Mockery::mock(DataObjectInterface::class));

        $step = new TestProcessStep();

        // processor runs the handle() method, so simulate that
        $step->handle($context, function () {});

        static::assertTrue($step->processWasCalled, 'process() method was not called');
    }

    /**
     * @test
     */
    public function it_calls_the_next_step_in_the_pipeline(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('!next was called!');

        /** @var ProcessContextInterface|Mockery\Mock|Mockery\MockInterface $context */
        $context = Mockery::mock(ProcessContextInterface::class);
        $context->shouldReceive('setSetting');
        $context->shouldReceive('getData')->andReturn(Mockery::mock(DataObjectInterface::class));

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
    public function it_stores_context_and_context_data_before_process_is_called(): void
    {
        /** @var DataObjectInterface|Mockery\Mock|Mockery\MockInterface $data */
        $data = Mockery::mock(DataObjectInterface::class);

        /** @var ProcessContextInterface|Mockery\Mock|Mockery\MockInterface $context */
        $context = Mockery::mock(ProcessContextInterface::class);
        $context->shouldReceive('setSetting');
        $context->shouldReceive('getData')->andReturn($data);

        $step = new TestProcessStep();

        // processor runs the handle() method, so simulate that
        /** @var ProcessContextInterface $context */
        $step->handle($context, function () {});

        static::assertSame($context, $step->testGetContext(), 'Context was not stored');
        static::assertSame($data, $step->testGetData(), 'Data was not stored');
    }
}
