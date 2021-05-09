<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\DTO\ClassDTO;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;

class DomainEventGenerator extends DTOGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $subscriberName): void
    {
        $classDTO = new ClassDTO($boundedContextName, $moduleName, $subscriberName, 'domain-event');
        
        if (file_exists($classDTO->elementFullPath())) {
            throw new ElementAlreadyExistsException(
                "Domain event {$subscriberName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        file_put_contents(
            $classDTO->elementFullPath(),
            $this->renderer->render(
                "lib/DDDMakerBundle/src/Templates/event.php.template",
                [
                    "t_namespace" =>  $classDTO->namespace(),
                    "t_class_name" =>  $classDTO->elementClassName(),
                    "t_base_class_full_namespace" => $classDTO->classToExtendNamespace(),
                    "t_base_class_name" => $classDTO->classToExtendName(),
                    "t_attributes" => $this->classAttributes->attributes()
                ]
            )
        );
    }
}