<?php


namespace Mql21\DDDMakerBundle\Finder;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Locator\PathLocator;

class CommandFinder
{
    private string $commandFileSuffix;
    private ConfigManager $configManager;
    private PathLocator $pathLocator;
    
    public function __construct(PathLocator $pathLocator, ConfigManager $configManager)
    {
        $this->configManager = $configManager;
        $this->commandFileSuffix = $configManager->classSuffixFor('command') . '.php';
        $this->pathLocator = $pathLocator;
    }
    
    public function findIn(string $boundedContextName, string $moduleName): array
    {
        $commandsPath = $this->pathLocator->for($boundedContextName, $moduleName, 'command');
        $elementsInBoundedContextDirectory = scandir($commandsPath);
        
        $availableCommandFiles = $this->findAvailableCommandFiles($elementsInBoundedContextDirectory, $commandsPath);
        
        return $this->removeSuffixFromCommandFiles($availableCommandFiles);
    }
    
    protected function findAvailableCommandFiles(array $elementsInBoundedContextDirectory, string $commandsPath): array
    {
        return array_filter(
            $elementsInBoundedContextDirectory,
            function ($element) use ($commandsPath) {
                $elementFullPath = $commandsPath . $element;
                
                return is_file($elementFullPath) && str_ends_with($elementFullPath, $this->commandFileSuffix);
            }
        );
    }
    
    protected function removeSuffixFromCommandFiles(array $availableCommandFiles): array
    {
        return array_map(
            function ($element) {
                return str_replace($this->commandFileSuffix, '', $element);
            },
            $availableCommandFiles
        );
    }
}