<?php


namespace Mql21\DDDMakerBundle\Finder;

use Mql21\DDDMakerBundle\Factories\PathFactory;

class QueryFinder
{
    const QUERY_FILE_SUFFIX = "Query.php";
    
    public function findIn(string $boundedContextName, string $moduleName): array
    {
        $queriesPath = PathFactory::forBoundedContextModuleCommands($boundedContextName, $moduleName);
        $elementsInBoundedContextDirectory = scandir($queriesPath);
        
        $availableQueryFiles = $this->findAvailableCommandFiles($elementsInBoundedContextDirectory, $queriesPath);
        
        return $this->removeCommandSuffixFromCommandFiles($availableQueryFiles);
    }
    
    protected function findAvailableCommandFiles(array $elementsInBoundedContextDirectory, string $queriesPath): array
    {
        return array_filter(
            $elementsInBoundedContextDirectory,
            function ($element) use ($queriesPath) {
                $elementFullPath = $queriesPath . $element;
                
                return is_file($elementFullPath) && str_ends_with($elementFullPath, self::QUERY_FILE_SUFFIX);
            }
        );
    }
    
    protected function removeCommandSuffixFromCommandFiles(array $availableQueryFiles): array
    {
        return array_map(
            function ($element) {
                
                return str_replace(self::QUERY_FILE_SUFFIX, '', $element);
            },
            $availableQueryFiles
        );
    }
}