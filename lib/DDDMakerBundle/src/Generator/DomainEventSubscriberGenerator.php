<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
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
    
        $configManager = new ConfigManager(); // TODO Inject via DI
        $baseClassReflector = new \ReflectionClass($configManager->getClassToImplementFor('event-subscriber'));
        
        $renderer = new PHPCodeRenderer();
        file_put_contents(
            $subscriberFullPath,
            $renderer->render(
                "lib/DDDMakerBundle/src/Templates/event_subscriber.php.template",
                [
                    "t_namespace" => "Mql21\DDDMakerBundle\Generator",
                    "t_class_name" => $subscriberClassName,
                    "t_interface_full_namespace" => $baseClassReflector->getName(),
                    "t_interface_name" => $baseClassReflector->getShortName(),
                    "t_use_case_class_name" => $this->useCaseResponse->useCase(),
                    "t_event_class_name" => "{$eventName}DomainEvent",
                ]
            )
        );
    }
}