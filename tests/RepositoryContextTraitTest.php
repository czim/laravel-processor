<?php
namespace Czim\Processor\Test;

use Czim\DataObject\Contracts\DataObjectInterface;
use Czim\Processor\Test\Helpers\TestRepositoryContext;
use Czim\Repository\Contracts\BaseRepositoryInterface;

class RepositoryContextTraitTest extends TestCase
{
    /**
     * @test
     */
    function it_takes_and_retrieves_a_repository_by_name()
    {
        /** @var DataObjectInterface $data */
        $data = $this->getMockBuilder(DataObjectInterface::class)->getMock();

        $context = new TestRepositoryContext($data);

        /** @var BaseRepositoryInterface $repository */
        $repository = $this->getMockBuilder(BaseRepositoryInterface::class)->getMock();

        $context->addRepository('test_repo', $repository);

        $this->assertSame($repository, $context->getRepository('test_repo'));
    }

    /**
     * @test
     * @expectedException \Czim\Processor\Exceptions\ContextRepositoryException
     * @expectedExceptionMessageRegExp #not found.* unset_repo#
     */
    function it_throws_an_exception_if_it_cannot_find_a_repository()
    {
        /** @var DataObjectInterface $data */
        $data = $this->getMockBuilder(DataObjectInterface::class)->getMock();

        $context = new TestRepositoryContext($data);

        $context->getRepository('unset_repo');
    }

    /**
     * @test
     */
    function it_delegates_method_calls_to_a_repository_by_name()
    {
        /** @var DataObjectInterface $data */
        $data = $this->getMockBuilder(DataObjectInterface::class)->getMock();

        $context = new TestRepositoryContext($data);

        // must use getMock, not getMockBuilder, or a call_user_func_array() will cause the test to fail
        $repository = $this->getMock(BaseRepositoryInterface::class, [ 'testMethod' ]);
        $repository->expects($this->exactly(1))
                   ->method('testMethod');

        /** @var BaseRepositoryInterface $repository */
        $context->addRepository('test_repo', $repository);

        $context->repository('test_repo', 'testMethod', [ 'parameter' ]);
    }
}
