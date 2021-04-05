<?php


namespace Mql21\DDDMakerBundle\Finder;

use Mql21\DDDMakerBundle\Factories\PathFactory;

class UseCaseFinder
{
    const USE_CASE_FILE_EXTENSION = ".php";
    
    public function findIn(string $boundedContextName, string $moduleName): array
    {
        $useCasePath = PathFactory::forUseCasesIn($boundedContextName, $moduleName);
        $elementsInBoundedContextDirectory = scandir($useCasePath);
        
        $availableUseCaseFiles = $this->findAvailableUseCaseFiles($elementsInBoundedContextDirectory, $useCasePath);
        
        return $this->removeFileExtensionFromUseCaseFiles($availableUseCaseFiles);
    }
    
    protected function findAvailableUseCaseFiles(array $elementsInBoundedContextDirectory, string $eventsPath): array
    {
        return array_filter(
            $elementsInBoundedContextDirectory,
            function ($element) use ($eventsPath) {
                $elementFullPath = $eventsPath . $element;
                
                return is_file($elementFullPath) && str_ends_with($elementFullPath, self::USE_CASE_FILE_EXTENSION);
            }
        );
    }
    
    protected function removeFileExtensionFromUseCaseFiles(array $availableQueryFiles): array
    {
        return array_map(
            function ($element) {
                
                return str_replace(self::USE_CASE_FILE_EXTENSION, '', $element);
            },
            $availableQueryFiles
        );
    }
}