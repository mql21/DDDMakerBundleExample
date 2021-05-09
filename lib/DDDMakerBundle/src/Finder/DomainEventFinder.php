<?php


namespace Mql21\DDDMakerBundle\Finder;

use Mql21\DDDMakerBundle\Factories\PathFactory;

class DomainEventFinder
{
    const EVENT_FILE_SUFFIX = "DomainEvent.php";
    
    public function findIn(string $boundedContextName, string $moduleName): array
    {
        $eventsPath = PathFactory::for($boundedContextName, $moduleName, 'domain-event');
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
                
                return is_file($elementFullPath) && str_ends_with($elementFullPath, self::EVENT_FILE_SUFFIX);
            }
        );
    }
    
    protected function removeSuffixFromEventFiles(array $availableQueryFiles): array
    {
        return array_map(
            function ($element) {
                
                return str_replace(self::EVENT_FILE_SUFFIX, '', $element);
            },
            $availableQueryFiles
        );
    }
}