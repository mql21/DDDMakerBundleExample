<?php


namespace Mql21\DDDMakerBundle\Finder;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Factories\PathFactory;

class UseCaseFinder
{
    private string $useCaseFileSuffix;
    
    public function __construct()
    {
        $configManager = new ConfigManager();
        $this->useCaseFileSuffix = $configManager->classSuffixFor('use-case') . '.php';
    }
    public function findIn(string $boundedContextName, string $moduleName): array
    {
        $useCasePath = PathFactory::for($boundedContextName, $moduleName, 'use-case');
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
                
                return is_file($elementFullPath) && str_ends_with($elementFullPath, $this->useCaseFileSuffix);
            }
        );
    }
    
    protected function removeFileExtensionFromUseCaseFiles(array $availableQueryFiles): array
    {
        return array_map(
            function ($element) {
                
                return str_replace($this->useCaseFileSuffix, '', $element);
            },
            $availableQueryFiles
        );
    }
}