<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;

class DomainEventGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $eventName): void
    {
    
        $eventSuffix = "DomainEvent";
        $eventClassName = "{$eventName}{$eventSuffix}";
        $eventFileName = "{$eventClassName}.php";
        $modulePath = PathFactory::forBoundedContextModules($boundedContextName, $moduleName);
        $eventFullPath = "{$modulePath}/Domain/Event/{$eventFileName}";
        
        if (file_exists($eventFullPath)) {
            throw new ElementAlreadyExistsException("Domain event {$eventName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\".");
        }
    
        file_put_contents($eventFullPath, "<?php \n\nnamespace Test\Module;\n\nclass {$eventClassName}\n{\n}\n");
    }
    
}