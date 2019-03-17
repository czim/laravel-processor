<?php
namespace Czim\Processor\Test;

use Czim\DataObject\Contracts\DataObjectInterface;
use Czim\Processor\DataObjects\ProcessorResult;
use Czim\Processor\Test\Helpers\TestSimpleProcessor;
use Illuminate\Contracts\Support\MessageBag;

class AbstractProcessorTest extends TestCase
{

    /**
     * @test
     */
    function it_takes_settings_on_construction()
    {
        $array = [ 'random' => 'setting' ];

        $processor = new TestSimpleProcessor($array);

        static::assertSame($array, $processor->testGetSettings(), "Settings not correctly stored on construct");
    }

    /**
     * @test
     */
    function it_runs_the_doprocessing_and_before_and_after_methods_when_processing()
    {
        // note that this does not test the actual order in which
        // everything is called -- this is too trivial to worry about

        $processor = new TestSimpleProcessor();

        static::assertFalse($processor->beforeWasCalled, "Initial state incorrect");
        static::assertFalse($processor->doProcessingWasCalled, "Initial state incorrect");
        static::assertFalse($processor->afterWasCalled, "Initial state incorrect");

        /** @var DataObjectInterface $data */
        $data = $this->getMockBuilder(DataObjectInterface::class)->getMock();

        $processor->process($data);

        static::assertTrue($processor->beforeWasCalled, "before() was not called");
        static::assertTrue($processor->doProcessingWasCalled, "doProcessing() was not called");
        static::assertTrue($processor->afterWasCalled, "after() was not called");
    }

    /**
     * @test
     */
    function it_runs_the_initialize_method_after_construction()
    {
        $processor = new TestSimpleProcessor();

        static::assertTrue($processor->initializeWasCalled, "initialize() was not called");
    }


    /**
     * @test
     */
    function it_returns_a_result_dataobject()
    {
        // it initializes this by itself (as an empty dataobject)
        // so this test does not need to do any actual processing

        $processor = new TestSimpleProcessor();

        /** @var DataObjectInterface $data */
        $data = $this->getMockBuilder(DataObjectInterface::class)->getMock();

        $result = $processor->process($data);

        static::assertInstanceOf(ProcessorResult::class, $result, "Result of incorrect type");

        // test some default properties expected on the dataobject

        if (method_exists($this, 'assertIsBool')) {
            static::assertIsBool($result->getSuccess());
        } else {
            static::assertInternalType('boolean', $result->getSuccess());
        }
        static::assertInstanceOf(MessageBag::class, $result->getErrors());
        static::assertInstanceOf(MessageBag::class, $result->getWarnings());
    }


    /**
     * @test
     * @todo consider testing for Arrayable setExtraData() parameter
     */
    function it_takes_extra_data_to_be_set_besides_data_in_context()
    {
        $data = [ 'testing' => 'data' ];

        $processor = new TestSimpleProcessor();

        $processor->setExtraData($data);

        static::assertSame($data, $processor->testGetExtraData(), "ExtraData was not stored correctly");
    }

    /**
     * @test
     */
    function it_stores_the_data_to_process()
    {
        $processor = new TestSimpleProcessor();

        /** @var DataObjectInterface $data */
        $data = $this->getMockBuilder(DataObjectInterface::class)->getMock();

        $processor->process($data);

        static::assertSame($data, $processor->testGetData(), "process() parameter data was not stored correctly");
    }
}
