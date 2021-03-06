<?php

namespace Czim\Processor;

use Czim\DataObject\Contracts\DataObjectInterface;
use Czim\Processor\Contracts\ProcessorInterface;
use Czim\Processor\DataObjects\ProcessorData;
use Czim\Processor\DataObjects\ProcessorResult;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\MessageBag;

abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * Data to be processed
     *
     * @var DataObjectInterface
     */
    protected $data;

    /**
     * Extra data that we (may) use to process
     *
     * @var array
     */
    protected $extraData = [];

    /**
     * Settings to configure processing
     *
     * @var array
     */
    protected $settings = [];

    /**
     * @var ProcessorResult
     */
    protected $result = null;


    /**
     * @param array $settings
     */
    public function __construct(array $settings = [])
    {
        $this->settings = $settings;

        $this->initializeResult();

        $this->initialize();

        $this->data = $this->getDefaultData();
    }


    /**
     * Process the data sent
     *
     * @param DataObjectInterface $data
     * @return ProcessorResult
     */
    public function process(DataObjectInterface $data = null)
    {
        if ( ! is_null($data)) {
            $this->data = $data;
        }

        $this->before();

        $this->doProcessing();

        $this->after();

        return $this->getResult();
    }


    /**
     * Set extra data that the processor should use during the import
     *
     * @param array|Arrayable $data
     * @return $this
     */
    public function setExtraData($data)
    {
        if (is_a($data, Arrayable::class)) {
            $data = $data->toArray();
        }

        if ( ! is_array($data)) {
            $data = [ $data ];
        }

        $this->extraData = $data;

        return $this;
    }

    /**
     * Returns the data (to be) processed
     *
     * @return DataObjectInterface
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the data to be processed
     *
     * @param DataObjectInterface $data
     */
    public function setData(DataObjectInterface $data)
    {
        $this->data = $data;
    }

    /**
     * Returns default data-object if none is passed into the process method
     *
     * @return ProcessorData
     */
    protected function getDefaultData()
    {
        return new ProcessorData();
    }

    /**
     * Initializes / prepopulates processor result
     *
     * @return ProcessorResult
     */
    protected function initializeResult()
    {
        /** @var ProcessorResult $result */
        $this->result = app(ProcessorResult::class);

        $this->result->setSuccess(false);
        $this->result->setErrors( app(MessageBag::class) );
        $this->result->setWarnings( app(MessageBag::class) );
    }

    /**
     * Get the result dataobject
     *
     * @return ProcessorResult
     */
    protected function getResult()
    {
        return $this->result;
    }


    // ------------------------------------------------------------------------------
    //      Customizable / Abstractions
    // ------------------------------------------------------------------------------

    /**
     * Performs the actual processing
     */
    abstract protected function doProcessing();


    /**
     * Runs directly after construction
     * Extend this to customize your processor
     */
    protected function initialize()
    {
    }

    /**
     * Runs before processing is started
     * Extend this to customize your processor
     */
    protected function before()
    {
    }

    /**
     * Runs after processing is completed (without encountering exceptions)
     * Extend this to customize your processor
     */
    protected function after()
    {
    }
}
