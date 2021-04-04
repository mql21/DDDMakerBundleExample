<?php


namespace Mql21\DDDMakerBundle\Locator;


use Mql21\DDDMakerBundle\Factories\PathFactory;

class BoundedContextLocator
{
    public function exists(string $boundedContextName): bool
    {
        return file_exists(PathFactory::forBoundedContexts($boundedContextName));
    }
}