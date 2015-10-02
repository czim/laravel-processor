<?php
namespace Czim\Processor\Test\Helpers;

use Czim\Processor\AbstractProcessor;

class TestSimpleProcessor extends AbstractProcessor
{
    public $beforeWasCalled       = false;
    public $afterWasCalled        = false;
    public $doProcessingWasCalled = false;
    public $initializeWasCalled   = false;

    protected function initialize()
    {
        $this->initializeWasCalled = true;
    }

    protected function doProcessing()
    {
        $this->doProcessingWasCalled = true;
    }

    protected function before()
    {
        $this->beforeWasCalled = true;
    }

    protected function after()
    {
        $this->afterWasCalled = true;
    }


    public function testGetExtraData()
    {
        return $this->extraData;
    }

    public function testGetData()
    {
        return $this->data;
    }

}
