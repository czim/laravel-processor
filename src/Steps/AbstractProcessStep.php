<?php
namespace Czim\Processor\Steps;

use Closure;
use Czim\DataObject\Contracts\DataObjectInterface;
use Czim\Processor\Contracts\ProcessContextInterface;
use Czim\Processor\Contracts\ProcessStepInterface;

abstract class AbstractProcessStep implements ProcessStepInterface
{

    /**
     * The context for the process to pass from step to step
     *
     * @var ProcessContextInterface
     */
    protected $context;

    /**
     * Data to process
     *
     * @var DataObjectInterface
     */
    protected $data;


    /**
     *
     * @param ProcessContextInterface $processContext
     * @param Closure                 $next
     * @return mixed
     */
    public function handle(ProcessContextInterface $processContext, Closure $next)
    {
        $this->context = $processContext;
        $this->data    = $this->context->getData();

        $this->process();

        return $next($processContext);
    }

    /**
     * Execute the actual processing of the step
     */
    abstract protected function process();
}

