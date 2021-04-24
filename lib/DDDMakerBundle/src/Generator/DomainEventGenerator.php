<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;

class DomainEventGenerator extends DTOGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $eventName): void
    {
        $eventSuffix = "DomainEvent";
        $eventClassName = "{$eventName}{$eventSuffix}";
        $eventFileName = "{$eventClassName}.php";
        $domainEventsPath = PathFactory::forDomainEventsIn($boundedContextName, $moduleName);
        $eventFullPath = "{$domainEventsPath}{$eventFileName}";
        
        if (file_exists($eventFullPath)) {
            throw new ElementAlreadyExistsException(
                "Domain event {$eventName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        $baseClassReflector = new \ReflectionClass($this->configManager->getClassToExtendFor('domain-event'));
        
        file_put_contents(
            $eventFullPath,
            $this->renderer->render(
                "lib/DDDMakerBundle/src/Templates/event.php.template",
                [
                    "t_namespace" => $this->configManager->getNamespaceFor(
                        $boundedContextName,
                        $moduleName,
                        'domain-event'
                    ),
                    "t_class_name" => $eventClassName,
                    "t_base_class_full_namespace" => $baseClassReflector->getName(),
                    "t_base_class_name" => $baseClassReflector->getShortName(),
                    "t_attributes" => $this->classAttributes->attributes()
                ]
            )
        );
    }
}