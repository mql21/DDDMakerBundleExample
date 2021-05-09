<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\DTO\ClassDTO;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;

class CommandGenerator extends DTOGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $handlerName): void
    {
        $classDTO = new ClassDTO($boundedContextName, $moduleName, $handlerName, 'command');
        
        if (file_exists($classDTO->elementFullPath())) {
            throw new ElementAlreadyExistsException(
                "Command {$handlerName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        file_put_contents(
            $classDTO->elementFullPath(),
            $this->renderer->render(
                "lib/DDDMakerBundle/src/Templates/command.php.template",
                [
                    "t_namespace" => $classDTO->namespace(),
                    "t_class_name" => $classDTO->elementClassName(),
                    "t_interface_full_namespace" => $classDTO->interfaceToImplementNamespace(),
                    "t_interface_name" => $classDTO->interfaceToImplementName(),
                    "t_attributes" => $this->classAttributes->attributes()
                ]
            )
        );
    }
}