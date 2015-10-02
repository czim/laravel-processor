<?php
namespace Czim\Processor\DataObjects;

use Czim\DataObject\AbstractDataObject;
use Illuminate\Contracts\Support\MessageBag;

class ProcessorResult extends AbstractDataObject
{

    /**
     * @param bool $success
     */
    public function setSuccess($success)
    {
        $this->setAttribute('success', (bool) $success);
    }

    /**
     * @return boolean
     */
    public function getSuccess()
    {
        return $this->getAttribute('success');
    }


    /**
     * @param MessageBag $warnings
     */
    public function setWarnings(MessageBag $warnings)
    {
        $this->setAttribute('warnings', $warnings);
    }

    /**
     * @return MessageBag
     */
    public function getWarnings()
    {
        return $this->getAttribute('warnings');
    }


    /**
     * @param MessageBag $errors
     */
    public function setErrors(MessageBag $errors)
    {
        $this->setAttribute('errors', $errors);
    }

    /**
     * @return MessageBag
     */
    public function getErrors()
    {
        return $this->getAttribute('errors');
    }
}
