<?php
namespace Czim\Processor\Test;

use Czim\DataObject\Contracts\DataObjectInterface;
use Czim\Processor\Contexts\SimpleProcessContext;

class SimpleProcessContextTest extends TestCase
{

    /**
     * @test
     */
    function it_can_be_instantiated_with_settings()
    {
        /** @var DataObjectInterface $data */
        $data = $this->getMockBuilder(DataObjectInterface::class)->getMock();

        $context = new SimpleProcessContext($data, [ 'test' => 'setting' ]);

        $this->assertSame($data, $context->getData(), "Data was not stored correctly");
        $this->assertSame([ 'test' => 'setting' ], $context->getSettings(), "Settings were not stored correctly");
    }

    /**
     * @test
     */
    function it_takes_and_retrieves_data()
    {
        /** @var DataObjectInterface $data */
        $data = $this->getMockBuilder(DataObjectInterface::class)->getMock();

        $context = new SimpleProcessContext($data);

        /** @var DataObjectInterface $newData */
        $newData = $this->getMockBuilder(DataObjectInterface::class)->getMock();

        // set new data
        $context->setData($newData);

        $this->assertSame($newData, $context->getData(), "New data was not stored correctly");
    }
    
    /**
     * @test
     */
    function it_sets_and_retrieves_cache_by_key()
    {
        /** @var DataObjectInterface $data */
        $data = $this->getMockBuilder(DataObjectInterface::class)->getMock();

        $context = new SimpleProcessContext($data);

        $this->assertNull($context->getSetting('some_setting'), "unset setting should return null");

        $context->setSetting('some_setting', 'special content');

        $this->assertEquals('special content', $context->getSetting('some_setting'), "setting not correctly set");
    }

    /**
     * @test
     */
    function it_sets_and_retrieves_settings_by_key()
    {
        /** @var DataObjectInterface $data */
        $data = $this->getMockBuilder(DataObjectInterface::class)->getMock();

        $context = new SimpleProcessContext($data);

        $this->assertNull($context->getCache('cache_key'), "unset cache entry should return null");

        $context->cache('cache_key', 'special content');

        $this->assertEquals('special content', $context->getCache('cache_key'), "cache entry not correctly set");
    }
}
