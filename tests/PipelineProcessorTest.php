<?php

namespace Czim\Processor\Test;

use Czim\DataObject\Contracts\DataObjectInterface;
use Czim\Processor\Contracts\ProcessContextInterface;
use Czim\Processor\Test\Helpers\TestPipelineProcessor;
use Czim\Processor\Test\Helpers\TestPipelineProcessorWithInitStep;
use Exception;
use Illuminate\Support\Facades\DB;
use Mockery;

class PipelineProcessorTest extends TestCase
{
    protected const EXCEPTION_IN_MAIN_STEP = true;


    /**
     * @test
     */
    public function it_takes_a_process_context_on_construction(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        /** @var ProcessContextInterface|Mockery\Mock|Mockery\MockInterface $data */
        $context = Mockery::mock(ProcessContextInterface::class);

        $processor = new TestPipelineProcessor([], $context);

        /** @var DataObjectInterface $data */
        $data = Mockery::mock(DataObjectInterface::class);

        $context->shouldReceive('setData');
        $context->shouldReceive('setProcessor');
        $context->shouldReceive('setSetting');
        $context->shouldReceive('getData')->andReturn($data);


        $processor->process($data);

        static::assertSame($context, $processor->testGetContext(), 'Injected context was not correctly stored');
    }

    /**
     * @test
     */
    public function it_builds_a_standard_process_context_if_not_injected_on_construction(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $processor = new TestPipelineProcessor();

        /** @var DataObjectInterface $data */
        $data = Mockery::mock(DataObjectInterface::class);

        $processor->process($data);

        static::assertInstanceOf(
            ProcessContextInterface::class, $processor->testGetContext(), 'Context was not built correctly'
        );
    }

    /**
     * @test
     * @depends it_builds_a_standard_process_context_if_not_injected_on_construction
     */
    public function it_runs_an_init_step_if_defined(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $processor = new TestPipelineProcessorWithInitStep();

        /** @var DataObjectInterface $data */
        $data = Mockery::mock(DataObjectInterface::class);

        $processor->process($data);

        // context should be altered by init step
        static::assertTrue(
            $processor->testGetContext()->getSetting('step_was_run'), 'step_was_run not set in context'
        );
    }

    /**
     * @test
     * @depends it_builds_a_standard_process_context_if_not_injected_on_construction
     */
    public function it_runs_a_main_step(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $processor = new TestPipelineProcessor();

        /** @var DataObjectInterface $data */
        $data = Mockery::mock(DataObjectInterface::class);

        $processor->process($data);

        // context should be altered by main step
        static::assertTrue(
            $processor->testGetContext()->getSetting('step_was_run'), 'step_was_run not set in context'
        );
    }

    /**
     * @test
     */
    public function it_runs_the_main_process_steps_in_a_database_transaction(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $processor = new TestPipelineProcessor();

        /** @var DataObjectInterface $data */
        $data = Mockery::mock(DataObjectInterface::class);

        $processor->process($data);
    }

    /**
     * @test
     * @depends it_runs_the_main_process_steps_in_a_database_transaction
     */
    public function it_does_a_rollback_for_main_process_steps_if_an_exception_is_thrown(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();

        $processor = new TestPipelineProcessor([], null, self::EXCEPTION_IN_MAIN_STEP);

        /** @var DataObjectInterface $data */
        $data = Mockery::mock(DataObjectInterface::class);

        try {

            $processor->process($data);

        } catch (Exception $e) {
            // caught only to let test pass and check rollBack
        }
    }
}
