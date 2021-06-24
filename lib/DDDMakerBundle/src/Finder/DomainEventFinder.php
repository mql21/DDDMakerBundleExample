<?php


namespace Mql21\DDDMakerBundle\Finder;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Locator\PathLocator;

class DomainEventFinder
{
    private string $eventFileSuffix;
    private PathLocator $pathLocator;
    private ConfigManager $configManager;
    
    public function __construct(PathLocator $pathLocator, ConfigManager $configManager)
    {
        $this->pathLocator = $pathLocator;
        $this->eventFileSuffix = $configManager->classSuffixFor('domain-event') . '.php';
        $this->configManager = $configManager;
    }
    
    public function findIn(string $boundedContextName, string $moduleName): array
    {
        $eventsPath = $this->pathLocator->for($boundedContextName, $moduleName, 'domain-event');
        $elementsInBoundedContextDirectory = scandir($eventsPath);
        
        $availableEventFiles = $this->findAvailableEventFiles($elementsInBoundedContextDirectory, $eventsPath);
        
        return $this->removeSuffixFromEventFiles($availableEventFiles);
    }
    
    protected function findAvailableEventFiles(array $elementsInBoundedContextDirectory, string $eventsPath): array
    {
        return array_filter(
            $elementsInBoundedContextDirectory,
            function ($element) use ($eventsPath) {
                $elementFullPath = $eventsPath . $element;
                
                return is_file($elementFullPath) && str_ends_with($elementFullPath, $this->eventFileSuffix);
            }
        );
    }
    
    protected function removeSuffixFromEventFiles(array $availableQueryFiles): array
    {
        return array_map(
            function ($element) {
                
                return str_replace($this->eventFileSuffix, '', $element);
            },
            $availableQueryFiles
        );
    }
}