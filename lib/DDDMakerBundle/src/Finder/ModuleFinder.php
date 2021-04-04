<?php


namespace Mql21\DDDMakerBundle\Finder;


use Mql21\DDDMakerBundle\PathGenerator;

class ModuleFinder
{
    public function findIn(string $boundedContextName): array
    {
        $boundedContextPath = PathGenerator::forBoundedContexts($boundedContextName);
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