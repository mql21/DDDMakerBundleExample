<?php


namespace Mql21\DDDMakerBundle\Finder;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Factories\PathFactory;

class CommandFinder
{
    private string $commandFileSuffix;
    
    public function __construct()
    {
        $configManager = new ConfigManager();
        $this->commandFileSuffix = $configManager->classSuffixFor('command') . '.php';
    }
    
    public function findIn(string $boundedContextName, string $moduleName): array
    {
        $commandsPath = PathFactory::for($boundedContextName, $moduleName, 'command');
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