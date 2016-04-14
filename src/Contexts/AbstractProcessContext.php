<?php
namespace Czim\Processor\Contexts;

use Czim\Processor\Contracts\ProcessContextInterface;
use Czim\DataObject\Contracts\DataObjectInterface;
use Czim\Processor\Contracts\ProcessorInterface;
use Illuminate\Support\Arr;

abstract class AbstractProcessContext implements ProcessContextInterface
{

    /**
     * Data being processed
     *
     * @var DataObjectInterface
     */
    protected $data;

    /**
     * Particular settings for the context at large
     *
     * @var array
     */
    protected $settings = [];

    /**
     * Cached data
     *
     * @var array
     */
    protected $cache = [];

    /**
     * @var ProcessorInterface|null
     */
    protected $processor;


    /**
     * @param DataObjectInterface     $data
     * @param array|null              $settings
     * @param ProcessorInterface|null $processor
     */
    public function __construct(
        DataObjectInterface $data,
        array $settings = null,
        ProcessorInterface $processor = null
    ) {
        $this->data = $data;

        if ( ! is_null($settings)) {
            $this->setSettings($settings);
        }

        $this->cache = [];

        $this->initialize();
    }

    /**
     * @param ProcessorInterface $processor
     * @return $this
     */
    public function setProcessor(ProcessorInterface $processor)
    {
        $this->processor = $processor;

        return $this;
    }

    /**
     * @return ProcessorInterface|null
     */
    public function getProcessor()
    {
        return $this->processor;
    }


    // ------------------------------------------------------------------------------
    //      Data Object
    // ------------------------------------------------------------------------------

    /**
     * @param DataObjectInterface $data
     * @return $this
     */
    public function setData(DataObjectInterface $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return DataObjectInterface
     */
    public function getData()
    {
        return $this->data;
    }


    // ------------------------------------------------------------------------------
    //      Settings
    // ------------------------------------------------------------------------------

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param array $settings
     * @return $this
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getSetting($key)
    {
        return Arr::get($this->settings, $key);
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @return $this
     */
    public function setSetting($key, $value)
    {
        Arr::set($this->settings, $key, $value);

        return $this;
    }


    // ------------------------------------------------------------------------------
    //      Cache
    // ------------------------------------------------------------------------------

    /**
     * @param string $key
     * @param mixed  $value
     * @return $this
     */
    public function cache($key, $value)
    {
        Arr::set($this->cache, $key, $value);

        return $this;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getCache($key)
    {
        return Arr::get($this->cache, $key);
    }

    // ------------------------------------------------------------------------------
    //      Customizable / Abstractions
    // ------------------------------------------------------------------------------

    /**
     * Runs directly after construction
     * Extend this to customize your context
     */
    protected function initialize()
    {
    }

}
