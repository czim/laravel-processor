<?php
namespace Czim\Processor;

use Czim\Processor\Contracts\ProcessContextInterface;
use DB;
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
    protected $processContext;


    /**
     * Sets up the process context before running the pipeline
     */
    protected function before()
    {
        $this->processContext = $this->buildProcessContext();
    }

    /**
     * Extend this class to configure your own process context setup
     * Builds a generic processcontext with only the process data injected.
     */
    protected function buildProcessContext()
    {
        return app(Contexts\SimpleProcessContext::class, [ $this->data, $this->settings ]);
    }


    /**
     * Performs the actual processing
     */
    public function doProcessing()
    {
        // initialization process pipeline
        // preparation for processing

        $initSteps = $this->gatherInitProcessSteps();

        if ( ! empty($initSteps)) {

            $this->processContext = app(Pipeline::class)
                 ->send($this->processContext)
                 ->through($initSteps)
                 ->then(function (ProcessContextInterface $context) {

                    return $context;
                 });

            $this->enrichContextAfterInitSteps();
        }


        // main pipeline (actual processing)

        $steps = $this->gatherProcessSteps();

        if ($this->databaseTransaction) DB::beginTransaction();

        try {

            $this->processContext = app(Pipeline::class)
                 ->send($this->processContext)
                 ->through($steps)
                 ->then(function(ProcessContextInterface $context) {

                    if ($this->databaseTransaction) DB::commit();

                    return $context;
                 });

        } catch (Exception $e) {

            if ($this->databaseTransaction) DB::rollBack();

            $this->handleExceptionInPipeline($e);

            throw $e;
        }

        $this->handlePipelineCompleted();


        $this->populateResult();
    }


    /**
     * Populate the result property based on the current process context
     * This is called after the pipeline completes (and only if no exceptions are thrown)
     */
    protected function populateResult()
    {
        // default: only mark that the processor completed succesfully
        $this->result->setSuccess(true);
    }


    /**
     * Enriches the process context returned from the init pipeline.
     * This method does nothing, but can be overridden by child processors if
     * something needs to be injected in the context before starting the actual processing pipeline.
     */
    protected function enrichContextAfterInitSteps()
    {
    }

    /**
     * Gather the steps to initialize the processing context.
     * These are the steps to perform BEFORE the real processing
     * takes place. It is separate to ensure that we have contextual
     * information to handle exceptions during the real process
     * with.
     *
     * Nothing in here should require any cleanup!
     *
     * By default there is no init process, so it is skipped.
     * Extend this with your own init steps to enable the init
     * pipeline.
     *
     * @return array
     */
    protected function gatherInitProcessSteps()
    {
        return [];
    }

    /**
     * Gather the steps to pass the dataobject through as a collection
     * These are the steps for AFTER the initial checks and retrieval
     * has been handled.
     *
     * @return array
     */
    abstract protected function gatherProcessSteps();


    /**
     * Handles any type of exception thrown during the execution of (main) pipeline steps
     *
     * @param Exception $e
     */
    abstract protected function handleExceptionInPipeline(Exception $e);

    /**
     * Handles the succesful completion of the (main) pipeline
     */
    protected function handlePipelineCompleted()
    {
    }

}
