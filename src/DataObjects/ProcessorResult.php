<?php
namespace Czim\Processor\DataObjects;

use Czim\DataObject\AbstractDataObject;
use Illuminate\Contracts\Support\MessageBag;

class ProcessorResult extends AbstractDataObject
{

    /**
     * @param bool $success
     *
     * @return $this
     */
    public function setSuccess($success)
    {
        $this->setAttribute('success', (bool) $success);

        return $this;
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
     * @return $this
     */
    public function setWarnings(MessageBag $warnings)
    {
        $this->setAttribute('warnings', $warnings);

        return $this;
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
     * @return $this
     */
    public function setErrors(MessageBag $errors)
    {
        $this->setAttribute('errors', $errors);

        return $this;
    }

    /**
     * @return MessageBag
     */
    public function getErrors()
    {
        return $this->getAttribute('errors');
    }
}
