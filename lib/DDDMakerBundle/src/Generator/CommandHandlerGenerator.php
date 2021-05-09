<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Generator\Builder\DDDClassBuilder;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;

class CommandHandlerGenerator extends HandlerGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $handlerName): void
    {
        $dddClassBuilder = DDDClassBuilder::create()
            ->forBoundedContext($boundedContextName)
            ->forModule($moduleName)
            ->withClassName($handlerName)
            ->ofDDDElementType($this->type())
            ->build();
        
        if (file_exists($dddClassBuilder->elementFullPath())) {
            throw new ElementAlreadyExistsException(
                "Command handler {$dddClassBuilder->elementClassName()} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        $useCaseNamespace = $this->configManager->getNamespaceFor($boundedContextName, $moduleName, 'use-case');
        $commandSuffix = $this->configManager->getClassSuffixFor('command');
        
        file_put_contents(
            $dddClassBuilder->elementFullPath(),
            $this->renderer->render(
                "lib/DDDMakerBundle/src/Templates/command_handler.php.template",
                [
                    "t_namespace" => $dddClassBuilder->namespace(),
                    "t_class_name" => $dddClassBuilder->elementClassName(),
                    "t_interface_full_namespace" => $dddClassBuilder->interfaceToImplementNamespace(),
                    "t_interface_name" => $dddClassBuilder->interfaceToImplementName(),
                    "t_use_case_namespace" => "{$useCaseNamespace}\\" . $this->useCaseResponse->useCase(),
                    "t_use_case_class_name" => $this->useCaseResponse->useCase(),
                    "t_command_class_name" => "{$handlerName}{$commandSuffix}",
                ]
            )
        );
    }
    
    public function type(): string
    {
        return 'command-handler';
    }
}