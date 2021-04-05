<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;

class DomainEventSubscriberGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $subscriberName): void
    {
        $subscriberFileName = "{$subscriberName}.php";
        $modulePath = PathFactory::forBoundedContextModules($boundedContextName, $moduleName);
        $subscriberFullPath = "{$modulePath}Application/EventSubscriber/{$subscriberFileName}";
        
        if (file_exists($subscriberFullPath)) {
            throw new ElementAlreadyExistsException("Event subscriber {$subscriberName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\".");
        }
    
        file_put_contents($subscriberFullPath, "<?php \n\nnamespace Test\Module;\n\nclass {$subscriberName}\n{\n}\n");
    }
    
}