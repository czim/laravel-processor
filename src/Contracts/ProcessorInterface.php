<?php

namespace Czim\Processor\Contracts;

use Czim\DataObject\Contracts\DataObjectInterface;
use Czim\Processor\DataObjects\ProcessorResult;
use Czim\Processor\Exceptions\CouldNotHandleDataException;
use Illuminate\Contracts\Support\Arrayable;

interface ProcessorInterface
{
    /**
     * @param DataObjectInterface|null $data
     * @return ProcessorResult
     * @throws CouldNotHandleDataException
     */
    public function process(DataObjectInterface $data = null);

    /**
     * Set extra data that the processor should use during the import
     * For instance, local data as opposed to the data imported externally
     * and stored through the process context's setData()
     *
     * @param mixed[]|Arrayable $data
     * @return $this
     */
    public function setExtraData($data);
}
