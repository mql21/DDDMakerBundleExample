<?php


namespace Mql21\DDDMakerBundle\Locator;


use Mql21\DDDMakerBundle\PathGenerator;

class BoundedContextLocator
{
    public function exists(string $boundedContextName): bool
    {
        return file_exists(PathGenerator::forBoundedContexts($boundedContextName));
    }
}