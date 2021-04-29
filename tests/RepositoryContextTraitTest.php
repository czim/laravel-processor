<?php
/** @noinspection PhpUnhandledExceptionInspection */

namespace Czim\Processor\Test;

use Czim\DataObject\Contracts\DataObjectInterface;
use Czim\Processor\Exceptions\ContextRepositoryException;
use Czim\Processor\Test\Helpers\TestRepositoryContext;
use Czim\Repository\Contracts\BaseRepositoryInterface;
use Mockery;

class RepositoryContextTraitTest extends TestCase
{
    /**
     * @test
     */
    public function it_takes_and_retrieves_a_repository_by_name(): void
    {
        /** @var DataObjectInterface|Mockery\Mock|Mockery\MockInterface $data */
        $data = Mockery::mock(DataObjectInterface::class);

        $context = new TestRepositoryContext($data);

        /** @var BaseRepositoryInterface|Mockery\Mock|Mockery\MockInterface $repository */
        $repository = Mockery::mock(BaseRepositoryInterface::class);

        $context->addRepository('test_repo', $repository);

        $this->assertSame($repository, $context->getRepository('test_repo'));
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_it_cannot_find_a_repository(): void
    {
        $this->expectException(ContextRepositoryException::class);
        $this->expectExceptionMessageRegExp('#not found.* unset_repo#');

        /** @var DataObjectInterface|Mockery\Mock|Mockery\MockInterface $data */
        $data = $this->getMockBuilder(DataObjectInterface::class)->getMock();

        $context = new TestRepositoryContext($data);

        $context->getRepository('unset_repo');
    }

    /**
     * @test
     */
    public function it_delegates_method_calls_to_a_repository_by_name(): void
    {
        /** @var DataObjectInterface|Mockery\Mock|Mockery\MockInterface $data */
        $data = Mockery::mock(DataObjectInterface::class);

        $context = new TestRepositoryContext($data);

        // must use getMock, not getMockBuilder, or a call_user_func_array() will cause the test to fail
        /** @var BaseRepositoryInterface|Mockery\Mock|Mockery\MockInterface $repository */
        $repository = Mockery::mock(BaseRepositoryInterface::class);
        $repository->shouldReceive('testMethod')->once();

        /** @var BaseRepositoryInterface|Mockery\Mock|Mockery\MockInterface $repository */
        $context->addRepository('test_repo', $repository);

        $context->repository('test_repo', 'testMethod', [ 'parameter' ]);
    }
}
