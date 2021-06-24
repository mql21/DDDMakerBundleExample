<?php


namespace Mql21\DDDMakerBundle\Finder;

use Mql21\DDDMakerBundle\Factories\PathLocator;

class ModuleFinder
{
    public function findIn(string $boundedContextName): array
    {
        $boundedContextPath = PathLocator::forModules($boundedContextName);
        $elementsInBoundedContextDirectory = scandir($boundedContextPath);
    
        return array_filter(
            $elementsInBoundedContextDirectory,
            function ($element) use ($boundedContextPath) {
                $elementFullPath = $boundedContextPath . $element;
            
                return is_dir($elementFullPath) && $element !== "." && $element !== "..";
            }
        );
    }
}