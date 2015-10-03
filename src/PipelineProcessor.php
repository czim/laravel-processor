<?php
namespace Czim\Processor;

use Czim\Processor\Contracts\ProcessContextInterface;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Template for processors that work with a (dual) pipeline.
 * The processing is separated by an optional initialization pipeline
 * and the actual/main processing pipeline. Each pipeline performs
 * a number of sequential steps.
 */
abstract class PipelineProcessor extends AbstractProcessor
{

    /**
     * Whether the main process should be run in a DB transaction
     * and rolled back on exceptions
     *
     * @var bool
     */
    protected $databaseTransaction = true;

    /**
     * The (current) process context being passed through the steps
     *
     * @var ProcessContextInterface
     */
    protected $context;


    /**
     * @param array                   $settings
     * @param ProcessContextInterface $context      optional: inject a fully prepared context (only data is overwritten)
     */
    public function __construct(array $settings = [], ProcessContextInterface $context = null)
    {
        $this->context = $context;

        parent::__construct($settings);
    }


    /**
     * Extend this class to configure your own process context setup
     * Builds a generic processcontext with only the process data injected.
     * If a context was injected in the constructor, data for it is set,
     * but settings are not applied.
     */
    protected function prepareProcessContext()
    {
        if ( ! is_null($this->context)) {

            $this->context->setData($this->data);
            return;
        }

        $this->context = app(Contexts\SimpleProcessContext::class, [ $this->data, $this->settings ]);
    }


    /**
     * Performs the actual processing
     */
    protected function doProcessing()
    {
        $this->prepareProcessContext();


        // initialization process pipeline

        $initSteps = $this->initProcessSteps();

        if ( ! empty($initSteps)) {

            $this->context = app(Pipeline::class)
                 ->send($this->context)
                 ->through($initSteps)
                 ->then(function (ProcessContextInterface $context) {

                    return $context;
                 });

            $this->afterInitSteps();
        }


        // main pipeline (actual processing)

        $steps = $this->processSteps();

        if ($this->databaseTransaction) DB::beginTransaction();

        try {

            $this->context = app(Pipeline::class)
                 ->send($this->context)
                 ->through($steps)
                 ->then(function(ProcessContextInterface $context) {

                    if ($this->databaseTransaction) DB::commit();

                    return $context;
                 });

        } catch (Exception $e) {

            if ($this->databaseTransaction) DB::rollBack();

            $this->onExceptionInPipeline($e);

            throw $e;
        }

        $this->afterPipeline();


        $this->populateResult();
    }


    // ------------------------------------------------------------------------------
    //      Process Steps configuration
    // ------------------------------------------------------------------------------

    /**
     * Returns the steps to initialize the processing context.
     * These are the steps to perform BEFORE the real processing
     * takes place. It is separate to ensure that we have contextual
     * information to handle exceptions during the real process
     * with.
     *
     * Nothing in here should require any cleanup!
     * The init steps also do not get executed in a database transaction.
     *
     * By default there is no init process (empty array returned),
     * so it is skipped. Extend this with your own init steps to enable the
     * init pipeline.
     *
     * @return array
     */
    protected function initProcessSteps()
    {
        return [];
    }

    /**
     * Gathers the steps to pass the dataobject through as a collection
     * These are the steps for AFTER the initial checks and retrieval
     * has been handled.
     *
     * @return array
     */
    abstract protected function processSteps();


    // ------------------------------------------------------------------------------
    //      Customizable / Abstractions
    // ------------------------------------------------------------------------------

    /**
     * Populate the result property based on the current process context
     * This is called after the pipeline completes (and only if no exceptions are thrown)
     */
    protected function populateResult()
    {
        // default: mark that the processor completed succesfully
        $this->result->setSuccess(true);
    }

    /**
     * Runs directly after init pipeline.
     * Extend this to customize your processor
     */
    protected function afterInitSteps()
    {
    }

    /**
     * Runs directly after succesfully completing main pipeline process
     * Extend this to customize your processor
     */
    protected function afterPipeline()
    {
    }

    /**
     * Runs when any exception in the main pipeline steps is thrown.
     * Override this to handle exceptions.
     * The exception will be re-thrown after this.
     *
     * @param Exception $e
     */
    protected function onExceptionInPipeline(Exception $e)
    {
    }

}
