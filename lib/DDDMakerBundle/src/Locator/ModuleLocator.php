<?php


namespace Mql21\DDDMakerBundle\Locator;


use Mql21\DDDMakerBundle\PathGenerator;

class ModuleLocator
{
    public function exists(string $boundedContextName, string $moduleName): bool
    {
        return file_exists(PathGenerator::forBoundedContextModules($boundedContextName, $moduleName));
    }
}