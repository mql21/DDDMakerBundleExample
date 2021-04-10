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
        $modulePath = PathFactory::forBoundedContextModules($boundedContextName, $moduleName);
        $subscriberFullPath = "{$modulePath}Application/EventSubscriber/{$subscriberFileName}";
        
        if (file_exists($subscriberFullPath)) {
            throw new ElementAlreadyExistsException(
                "Event subscriber {$eventName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        $baseClassReflectionObject = new \ReflectionClass("App\Shared\Domain\Bus\Event\DomainEventSubscriber");
        
        $renderer = new PHPCodeRenderer();
        file_put_contents(
            $subscriberFullPath,
            $renderer->render(
                "lib/DDDMakerBundle/src/Templates/event_subscriber.php.template",
                [
                    "t_namespace" => "Mql21\DDDMakerBundle\Generator",
                    "t_class_name" => $subscriberClassName,
                    "t_interface_full_namespace" => $baseClassReflectionObject->getName(),
                    "t_interface_name" => $baseClassReflectionObject->getShortName(),
                    "t_use_case_class_name" => $this->useCaseResponse->useCase(),
                    "t_event_class_name" => "{$eventName}DomainEvent",
                ]
            )
        );
    }
}