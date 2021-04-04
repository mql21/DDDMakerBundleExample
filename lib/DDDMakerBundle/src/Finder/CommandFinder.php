<?php


namespace Mql21\DDDMakerBundle\Finder;


use Mql21\DDDMakerBundle\PathGenerator;

class CommandFinder
{
    const COMMAND_FILE_SUFFIX = "Command.php";
    
    public function find(string $boundedContextName, string $moduleName): array
    {
        $commandsPath = PathGenerator::forBoundedContextModuleCommands($boundedContextName, $moduleName);
        $elementsInBoundedContextDirectory = scandir($commandsPath);
        
        $availableCommandFiles = $this->findAvailableCommandFiles($elementsInBoundedContextDirectory, $commandsPath);
    
        return $this->removeCommandSuffixFromCommandFiles($availableCommandFiles);
    }
    
    protected function findAvailableCommandFiles(array $elementsInBoundedContextDirectory, string $commandsPath): array
    {
        return array_filter(
            $elementsInBoundedContextDirectory,
            function ($element) use ($commandsPath) {
                $elementFullPath = $commandsPath . $element;
                
                return is_file($elementFullPath) && str_ends_with($elementFullPath, self::COMMAND_FILE_SUFFIX);
            }
        );
    }
    
    protected function removeCommandSuffixFromCommandFiles(array $availableCommandFiles): array
    {
        return array_map(
            function ($element) {
                
                return str_replace('Command.php', '', $element);
            },
            $availableCommandFiles
        );
    }
}