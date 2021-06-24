<?php

namespace Mql21\DDDMakerBundle\Locator;

class BoundedContextLocator
{
    private PathLocator $locator;
    
    public function __construct(PathLocator $locator)
    {
        $this->locator = $locator;
    }
    
    public function exists(string $boundedContextName): bool
    {
        return file_exists($this->locator->forModules($boundedContextName));
    }
}