<?php

namespace Czim\Processor\Contracts;

use Closure;

interface ProcessStepInterface
{
    /**
     * Process the step with the given process context
     *
     * @param ProcessContextInterface $processContext
     * @param Closure                 $next
     * @return Closure
     */
    public function handle(ProcessContextInterface $processContext, Closure $next);
}
