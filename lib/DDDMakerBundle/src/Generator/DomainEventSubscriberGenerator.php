<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\DirectoryNotFoundException;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Generator\Builder\DDDClassBuilder;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;

class DomainEventSubscriberGenerator extends HandlerGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $eventName): void
    {
        $dddClassBuilder = DDDClassBuilder::create()
            ->forBoundedContext($boundedContextName)
            ->forModule($moduleName)
            ->withClassName($this->subscriberName($eventName))
            ->ofDDDElementType($this->type())
            ->build();
        
        if (file_exists($dddClassBuilder->elementFullPath())) {
            ElementAlreadyExistsException::raise($this->subscriberName($eventName), $boundedContextName, $moduleName);
        }
        
        $useCaseNamespace = $this
            ->configManager
            ->namespaceFor($boundedContextName, $moduleName, 'use-case');
        $eventNamespace = $this
            ->configManager
            ->namespaceFor($boundedContextName, $moduleName, 'domain-event');
        $eventSuffix = $this->configManager->classSuffixFor('domain-event');
        
        $eventName = "{$eventName}{$eventSuffix}";
        
        if (!file_exists(dirname($dddClassBuilder->elementFullPath()))) {
            DirectoryNotFoundException::raise($dddClassBuilder->elementFullPath());
        }
        
        file_put_contents(
            $dddClassBuilder->elementFullPath(),
            $this->renderer->render(
                "lib/DDDMakerBundle/src/Templates/event_subscriber.php.template",
                [
                    "t_namespace" => $dddClassBuilder->namespace(),
                    "t_class_name" => $dddClassBuilder->elementClassName(),
                    "t_interface_full_namespace" => $dddClassBuilder->interfaceToImplementNamespace(),
                    "t_use_case_full_namespace" => $this->useCaseNamespace($useCaseNamespace),
                    "t_event_full_namespace" => $this->eventNamespace($eventNamespace, $eventName),
                    "t_interface_name" => $dddClassBuilder->interfaceToImplementName(),
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
    
    private function subscriberName(string $eventName): string
    {
        $useCaseName = $this->useCaseResponse->useCase();
        return "{$useCaseName}On{$eventName}";
    }
    
    private function useCaseNamespace(string $useCaseNamespace): string
    {
        return "{$useCaseNamespace}\\{$this->useCaseResponse->useCase()}";
    }
    
    private function eventNamespace(string $eventNamespace, string $eventName): string
    {
        return "{$eventNamespace}\\{$eventName}";
    }
}