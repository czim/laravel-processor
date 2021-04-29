<?php

namespace Czim\Processor\Test;

use Czim\DataObject\Contracts\DataObjectInterface;
use Czim\Processor\Contexts\SimpleProcessContext;
use Mockery;
use Mockery\Mock;
use Mockery\MockInterface;

class SimpleProcessContextTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_instantiated_with_settings(): void
    {
        /** @var DataObjectInterface|Mock|MockInterface $data */
        $data = Mockery::mock(DataObjectInterface::class);

        $context = new SimpleProcessContext($data, [ 'test' => 'setting' ]);

        static::assertSame($data, $context->getData(), 'Data was not stored correctly');
        static::assertSame([ 'test' => 'setting' ], $context->getSettings(), 'Settings were not stored correctly');
    }

    /**
     * @test
     */
    public function it_takes_and_retrieves_data(): void
    {
        /** @var DataObjectInterface|Mock|MockInterface $data */
        $data = Mockery::mock(DataObjectInterface::class);

        $context = new SimpleProcessContext($data);

        /** @var DataObjectInterface|Mock|MockInterface $newData */
        $newData = Mockery::mock(DataObjectInterface::class);

        // set new data
        $context->setData($newData);

        static::assertSame($newData, $context->getData(), 'New data was not stored correctly');
    }

    /**
     * @test
     */
    public function it_sets_and_retrieves_cache_by_key(): void
    {
        /** @var DataObjectInterface|Mock|MockInterface $data */
        $data = Mockery::mock(DataObjectInterface::class);

        $context = new SimpleProcessContext($data);

        static::assertNull($context->getSetting('some_setting'), 'unset setting should return null');

        $context->setSetting('some_setting', 'special content');

        static::assertEquals('special content', $context->getSetting('some_setting'), 'setting not correctly set');
    }

    /**
     * @test
     */
    public function it_sets_and_retrieves_settings_by_key(): void
    {
        /** @var DataObjectInterface|Mock|MockInterface $data */
        $data = Mockery::mock(DataObjectInterface::class);

        $context = new SimpleProcessContext($data);

        static::assertNull($context->getCache('cache_key'), 'unset cache entry should return null');

        $context->cache('cache_key', 'special content');

        static::assertEquals('special content', $context->getCache('cache_key'), 'cache entry not correctly set');
    }
}
