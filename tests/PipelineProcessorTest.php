<?php
namespace Czim\Processor\Test;

use Czim\DataObject\Contracts\DataObjectInterface;
use Czim\Processor\Contracts\ProcessContextInterface;
use Czim\Processor\Test\Helpers\TestPipelineProcessor;
use Czim\Processor\Test\Helpers\TestPipelineProcessorWithInitStep;
use Illuminate\Support\Facades\DB;

class PipelineProcessorTest extends TestCase
{
    const EXCEPTION_IN_MAIN_STEP = true;

    /**
     * @test
     */
    function it_runs_an_init_step_if_defined()
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $processor = new TestPipelineProcessorWithInitStep();

        /** @var DataObjectInterface $data */
        $data = $this->getMockBuilder(DataObjectInterface::class)->getMock();

        $processor->process($data);

        // context should be altered by init step
        $this->assertInstanceOf(
            ProcessContextInterface::class, $processor->testGetContext(), "context not set in processor"
        );
        $this->assertTrue(
            $processor->testGetContext()->getSetting('step_was_run'), "step_was_run not set in context"
        );
    }

    /**
     * @test
     */
    function it_runs_a_main_step()
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $processor = new TestPipelineProcessor();

        /** @var DataObjectInterface $data */
        $data = $this->getMockBuilder(DataObjectInterface::class)->getMock();

        $processor->process($data);

        // context should be altered by main step
        $this->assertInstanceOf(
            ProcessContextInterface::class, $processor->testGetContext(), "context not set in processor"
        );
        $this->assertTrue(
            $processor->testGetContext()->getSetting('step_was_run'), "step_was_run not set in context"
        );
    }

    /**
     * @test
     */
    function it_runs_the_main_process_steps_in_a_database_transaction()
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $processor = new TestPipelineProcessor();

        /** @var DataObjectInterface $data */
        $data = $this->getMockBuilder(DataObjectInterface::class)->getMock();

        $processor->process($data);
    }

    /**
     * @test
     * @depends it_runs_the_main_process_steps_in_a_database_transaction
     */
    function it_does_a_rollback_for_main_process_steps_if_an_exception_is_thrown()
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();

        $processor = new TestPipelineProcessor(self::EXCEPTION_IN_MAIN_STEP);

        /** @var DataObjectInterface $data */
        $data = $this->getMockBuilder(DataObjectInterface::class)->getMock();

        try {

            $processor->process($data);

        } catch (\Exception $e) {
            // caught only to let test pass and check rollBack
        }
    }

}
