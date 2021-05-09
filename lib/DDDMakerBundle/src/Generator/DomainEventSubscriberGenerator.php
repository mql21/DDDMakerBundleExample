<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;

class DomainEventSubscriberGenerator extends HandlerGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $eventName): void
    {
        $subscriberSuffix = $this->configManager->classSuffixFor($this->type());
        $subscriberClassName = "{$this->useCaseResponse->useCase()}On{$eventName}{$subscriberSuffix}";
        $subscriberFileName = "{$subscriberClassName}.php";
        $eventSubscribersPath = PathFactory::for($boundedContextName, $moduleName, $this->type());
        $subscriberFullPath = "{$eventSubscribersPath}{$subscriberFileName}";
        
        if (file_exists($subscriberFullPath)) {
            throw new ElementAlreadyExistsException(
                "Event subscriber {$eventName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        $baseClassReflector = new \ReflectionClass(
            $this->configManager->classToImplementFor($this->type())
        );
        
        $useCaseNamespace = $this
            ->configManager->namespaceFor($boundedContextName, $moduleName, 'use-case');
        
        $eventNamespace = $this
            ->configManager->namespaceFor($boundedContextName, $moduleName, 'domain-event');
        
        $eventName = "{$eventName}DomainEvent";
        
        file_put_contents(
            $subscriberFullPath,
            $this->renderer->render(
                "lib/DDDMakerBundle/src/Templates/event_subscriber.php.template",
                [
                    "t_namespace" => $this->configManager->namespaceFor(
                        $boundedContextName,
                        $moduleName,
                        $this->type()
                    ),
                    "t_class_name" => $subscriberClassName,
                    "t_interface_full_namespace" => $baseClassReflector->getName(),
                    "t_use_case_full_namespace" => $useCaseNamespace . "\\" . $this->useCaseResponse->useCase(),
                    "t_event_full_namespace" => $eventNamespace . "\\" . $eventName,
                    "t_interface_name" => $baseClassReflector->getShortName(),
                    "t_use_case_class_name" => $this->useCaseResponse->useCase(),
                    "t_event_class_name" => $eventName,
                ]
            )
        );
    }
    
    public function type(): string
    {
        return 'event-subscriber';
    }
}