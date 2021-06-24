<?php


namespace Mql21\DDDMakerBundle\Finder;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Locator\PathLocator;

class QueryFinder
{
    private string $queryFileSuffix;
    private ConfigManager $configManager;
    private PathLocator $pathLocator;
    
    public function __construct(PathLocator $pathLocator, ConfigManager $configManager)
    {
        $this->configManager = $configManager;
        $this->queryFileSuffix = $configManager->classSuffixFor('query') . '.php';
        $this->pathLocator = $pathLocator;
    }
    
    public function findIn(string $boundedContextName, string $moduleName): array
    {
        $queriesPath = $this->pathLocator->for($boundedContextName, $moduleName, 'query');
        $elementsInBoundedContextDirectory = scandir($queriesPath);
        
        $availableQueryFiles = $this->findAvailableQueryFiles($elementsInBoundedContextDirectory, $queriesPath);
        
        return $this->removeSuffixFromQueryFiles($availableQueryFiles);
    }
    
    protected function findAvailableQueryFiles(array $elementsInBoundedContextDirectory, string $queriesPath): array
    {
        return array_filter(
            $elementsInBoundedContextDirectory,
            function ($element) use ($queriesPath) {
                $elementFullPath = $queriesPath . $element;
                
                return is_file($elementFullPath) && str_ends_with($elementFullPath, $this->queryFileSuffix);
            }
        );
    }
    
    protected function removeSuffixFromQueryFiles(array $availableQueryFiles): array
    {
        return array_map(
            function ($element) {
                return str_replace($this->queryFileSuffix, '', $element);
            },
            $availableQueryFiles
        );
    }
}