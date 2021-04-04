<?php


namespace Mql21\DDDMakerBundle\Locator;


use Mql21\DDDMakerBundle\Factories\PathFactory;

class ModuleLocator
{
    public function exists(string $boundedContextName, string $moduleName): bool
    {
        return file_exists(PathFactory::forBoundedContextModules($boundedContextName, $moduleName));
    }
}