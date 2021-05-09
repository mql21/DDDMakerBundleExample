<?php


namespace Mql21\DDDMakerBundle\Finder;

use Mql21\DDDMakerBundle\Factories\PathFactory;

class ResponseFinder
{
    const RESPONSE_FILE_EXTENSION = ".php";
    
    public function findIn(string $boundedContextName, string $moduleName): array
    {
        $responsePath = PathFactory::for($boundedContextName, $moduleName, 'response');
        $elementsResponseDirectory = scandir($responsePath);
        
        $availableUseCaseFiles = $this->findAvailableResponseFiles($elementsResponseDirectory, $responsePath);
        
        return $this->removeFileExtensionFromResponseFiles($availableUseCaseFiles);
    }
    
    protected function findAvailableResponseFiles(array $elementsInBoundedContextDirectory, string $eventsPath): array
    {
        return array_filter(
            $elementsInBoundedContextDirectory,
            function ($element) use ($eventsPath) {
                $elementFullPath = $eventsPath . $element;
                
                return is_file($elementFullPath) && str_ends_with($elementFullPath, self::RESPONSE_FILE_EXTENSION);
            }
        );
    }
    
    protected function removeFileExtensionFromResponseFiles(array $availableQueryFiles): array
    {
        return array_map(
            function ($element) {
                
                return str_replace(self::RESPONSE_FILE_EXTENSION, '', $element);
            },
            $availableQueryFiles
        );
    }
}