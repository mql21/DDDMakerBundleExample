<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\DTO\ClassDTO;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;

class CommandHandlerGenerator extends HandlerGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $handlerName): void
    {
        $classDTO = new ClassDTO($boundedContextName, $moduleName, $handlerName, 'command-handler');
        
        if (file_exists($classDTO->elementFullPath())) {
            throw new ElementAlreadyExistsException(
                "Command handler {$classDTO->elementClassName()} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        $useCaseNamespace = $this->configManager->getNamespaceFor($boundedContextName, $moduleName, 'use-case');
        $commandSuffix = $this->configManager->getClassSuffixFor('command');
        
        file_put_contents(
            $classDTO->elementFullPath(),
            $this->renderer->render(
                "lib/DDDMakerBundle/src/Templates/command_handler.php.template",
                [
                    "t_namespace" => $classDTO->namespace(),
                    "t_class_name" => $classDTO->elementClassName(),
                    "t_interface_full_namespace" => $classDTO->interfaceToImplementNamespace(),
                    "t_interface_name" => $classDTO->interfaceToImplementName(),
                    "t_use_case_namespace" => "{$useCaseNamespace}\\" . $this->useCaseResponse->useCase(),
                    "t_use_case_class_name" => $this->useCaseResponse->useCase(),
                    "t_command_class_name" => "{$handlerName}{$commandSuffix}",
                ]
            )
        );
    }
}