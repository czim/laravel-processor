<?php

namespace Czim\Processor\Contracts;

use Czim\DataObject\Contracts\DataObjectInterface;

/**
 * This provides all the contextual stuff that any Processor's ProcessStep
 * might need to do its job and to keep track of what it has done.
 */
interface ProcessContextInterface
{
    /**
     * @param DataObjectInterface     $data
     * @param array|null              $settings
     * @param ProcessorInterface|null $processor
     */
    public function __construct(DataObjectInterface $data, array $settings = null, ProcessorInterface $processor = null);

    /**
     * @param ProcessorInterface $processor
     * @return $this
     */
    public function setProcessor(ProcessorInterface $processor);

    /**
     * @return ProcessorInterface|null
     */
    public function getProcessor();

    /**
     * Set DataObject to process
     *
     * @param DataObjectInterface $data
     * @return $this
     */
    public function setData(DataObjectInterface $data);

    /**
     * Get DataObject being processed
     *
     * @return DataObjectInterface
     */
    public function getData();


    /**
     * Gets all settings
     *
     * @return array
     */
    public function getSettings();

    /**
     * Sets general context settings
     *
     * @param array $settings
     * @return $this
     */
    public function setSettings(array $settings);

    /**
     * Sets setting for context
     *
     * @param string $key . delimited array key (for array_set())
     * @param mixed  $value
     * @return $this
     */
    public function setSetting($key, $value);


    /**
     * Returns setting if set (or null)
     *
     * @param string $key
     * @return mixed
     */
    public function getSetting($key);


    /**
     * Stores something in context cache
     *
     * @param  string $key . delimited array key (for array_set())
     * @param  mixed  $value
     * @return $this
     */
    public function cache($key, $value);

    /**
     * Retrieves something from cache
     *
     * @param  string $key . delimited array key (for array_set())
     * @return mixed
     */
    public function getCache($key);
}
