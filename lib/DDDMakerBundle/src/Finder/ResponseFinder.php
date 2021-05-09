<?php


namespace Mql21\DDDMakerBundle\Finder;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Factories\PathFactory;

class ResponseFinder
{
    private string $responseFileSuffix;
    
    public function __construct()
    {
        $configManager = new ConfigManager();
        $this->responseFileSuffix = $configManager->classSuffixFor('response') . '.php';
    }
    
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
                
                return is_file($elementFullPath) && str_ends_with($elementFullPath, $this->responseFileSuffix);
            }
        );
    }
    
    protected function removeFileExtensionFromResponseFiles(array $availableQueryFiles): array
    {
        return array_map(
            function ($element) {
                
                return str_replace($this->responseFileSuffix, '', $element);
            },
            $availableQueryFiles
        );
    }
}