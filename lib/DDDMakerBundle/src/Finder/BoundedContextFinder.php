<?php


namespace Mql21\DDDMakerBundle\Finder;

use Mql21\DDDMakerBundle\Factories\PathFactory;

class BoundedContextFinder
{
    public function find(): array
    {
        $basePath = PathFactory::forBoundedContexts();
        $elementsInSrcDirectory = scandir($basePath);
    
        return array_filter(
            $elementsInSrcDirectory,
            function ($element) use ($basePath) {
                $elementFullPath = $basePath . $element;
            
                return is_dir($elementFullPath) && $element !== "." && $element !== "..";
            }
        );
    }
}