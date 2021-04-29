<?php

namespace Czim\Processor\Test\Helpers;

use Czim\Processor\Contexts\ContextRepositoryTrait;
use Czim\Processor\Contexts\SimpleProcessContext;

class TestRepositoryContext extends SimpleProcessContext
{
    use ContextRepositoryTrait;
}
