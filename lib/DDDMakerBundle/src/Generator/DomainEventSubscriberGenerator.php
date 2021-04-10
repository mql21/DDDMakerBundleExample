<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Templates\HandlerTemplateData;

class DomainEventSubscriberGenerator extends HandlerGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $eventName): void
    {
        $subscriberClassName = "{$this->useCaseResponse->useCase()}On{$eventName}";
        $subscriberFileName = "{$subscriberClassName}.php";
        $modulePath = PathFactory::forBoundedContextModules($boundedContextName, $moduleName);
        $subscriberFullPath = "{$modulePath}Application/EventSubscriber/{$subscriberFileName}";
        
        if (file_exists($subscriberFullPath)) {
            throw new ElementAlreadyExistsException("Event subscriber {$eventName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\".");
        }
    
        file_put_contents(
            $subscriberFullPath,
            $this->renderEventSubscriberTemplate(
                new HandlerTemplateData(
                    "Mql21\DDDMakerBundle\Generator",
                    $subscriberClassName,
                    "App\Shared\Domain\Bus\Event\DomainEventSubscriber",
                    "{$eventName}DomainEvent",
                    $this->useCaseResponse->useCase()
                )
            )
        );
    }
    
    // TODO extract and inject as collaborator
    protected function renderEventSubscriberTemplate(HandlerTemplateData $templateData): string
    {
        $template = file_get_contents(
            "lib/DDDMakerBundle/src/Templates/event_subscriber.php.template"
        ); //TODO: get via config (DI)
        
        $interfaceReflectionClass = new \ReflectionClass($templateData->interfaceNamespace());
        
        $classContent = str_replace("{{t_namespace}}", $templateData->classNamespace(), $template);
        $classContent = str_replace(
            "{{t_interface_full_namespace}}",
            $templateData->interfaceNamespace(),
            $classContent
        );
        $classContent = str_replace("{{t_interface_name}}", $interfaceReflectionClass->getShortName(), $classContent);
        $classContent = str_replace("{{t_class_name}}", $templateData->className(), $classContent);
        $classContent = str_replace("{{t_use_case_class_name}}", $templateData->useCaseName(), $classContent);
        $classContent = str_replace("{{t_event_class_name}}", $templateData->classToHandle(), $classContent);
        
        return $classContent;
    }
}