<?php

namespace Mql21\DDDMakerBundle\Locator;

class ModuleLocator
{
    private PathLocator $locator;
    
    public function __construct(PathLocator $locator)
    {
        $this->locator = $locator;
    }
    
    public function exists(string $boundedContextName, string $moduleName): bool
    {
        return file_exists($this->locator->forBoundedContextModules($boundedContextName, $moduleName));
    }
}