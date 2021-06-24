<?php

namespace Mql21\DDDMakerBundle\Finder;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Locator\PathLocator;

class ResponseFinder
{
    private string $responseFileSuffix;
    private ConfigManager $configManager;
    private PathLocator $pathLocator;
    
    public function __construct(PathLocator $pathLocator, ConfigManager $configManager)
    {
        $this->configManager = $configManager;
        $this->responseFileSuffix = $configManager->classSuffixFor('response') . '.php';
        $this->pathLocator = $pathLocator;
    }
    
    public function findIn(string $boundedContextName, string $moduleName): array
    {
        $responsePath = $this->pathLocator->for($boundedContextName, $moduleName, 'response');
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