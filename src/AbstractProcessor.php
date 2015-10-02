<?php
namespace Czim\Processor;

use Czim\DataObject\Contracts\DataObjectInterface;
use Czim\Processor\Contracts\ProcessorInterface;
use Czim\Processor\DataObjects\ProcessorResult;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\MessageBag;

/**
 * @todo remove dependency on app() function, replace with injected Container
 * @todo work out how to deal with / set 'settings'
 * @todo work out how to deal with configurable logging
 */
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


    public function __construct()
    {
        $this->initializeResult();

        $this->initialize();
    }


    /**
     * Process the data sent
     *
     * @param DataObjectInterface $data
     * @return ProcessorResult
     */
    public function process(DataObjectInterface $data)
    {
        $this->data = $data;

        $this->before();

        $this->doProcessing();

        $this->after();

        return $this->getResult();
    }

    /**
     * Performs the actual processing
     */
    abstract protected function doProcessing();


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
