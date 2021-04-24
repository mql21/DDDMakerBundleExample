<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Renderer\PHPCodeRenderer;

class DomainEventSubscriberGenerator extends HandlerGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $eventName): void
    {
        $subscriberClassName = "{$this->useCaseResponse->useCase()}On{$eventName}";
        $subscriberFileName = "{$subscriberClassName}.php";
        $eventSubscribersPath = PathFactory::forEventSubscribersIn($boundedContextName, $moduleName);
        $subscriberFullPath = "{$eventSubscribersPath}{$subscriberFileName}";
        
        if (file_exists($subscriberFullPath)) {
            throw new ElementAlreadyExistsException(
                "Event subscriber {$eventName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        $baseClassReflector = new \ReflectionClass(
            $this->configManager->getClassToImplementFor('event-subscriber')
        );
        
        $useCaseNamespace = $this
            ->configManager->getNamespaceFor($boundedContextName, $moduleName, 'use-case');
        
        $eventNamespace = $this
            ->configManager->getNamespaceFor($boundedContextName, $moduleName, 'domain-event');
        
        $eventName = "{$eventName}DomainEvent";
        
        $renderer = new PHPCodeRenderer();
        file_put_contents(
            $subscriberFullPath,
            $renderer->render(
                "lib/DDDMakerBundle/src/Templates/event_subscriber.php.template",
                [
                    "t_namespace" => $this->configManager->getNamespaceFor(
                        $boundedContextName,
                        $moduleName,
                        'event-subscriber'
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
}