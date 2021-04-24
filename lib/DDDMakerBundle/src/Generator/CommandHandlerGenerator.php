<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Renderer\PHPCodeRenderer;

class CommandHandlerGenerator extends HandlerGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $commandName): void
    {
        $commandHandlerSuffix = "CommandHandler";
        $commandHandlerClassName = "{$commandName}{$commandHandlerSuffix}";
        $commandHandlerFileName = "{$commandHandlerClassName}.php";
        $commandsPath = PathFactory::forCommandsIn($boundedContextName, $moduleName);
        $commandHandlerFullPath = "{$commandsPath}{$commandHandlerFileName}";
        
        if (file_exists($commandHandlerFullPath)) {
            throw new ElementAlreadyExistsException(
                "Command handler {$commandHandlerClassName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        $baseClassReflector = new \ReflectionClass(
            $this->configManager->getClassToImplementFor('command-handler')
        );
        $useCaseNamespace = $this->configManager->getNamespaceFor($boundedContextName, $moduleName, 'use-case');
        
        $renderer = new PHPCodeRenderer();
        file_put_contents(
            $commandHandlerFullPath,
            $renderer->render(
                "lib/DDDMakerBundle/src/Templates/command_handler.php.template",
                [
                    "t_namespace" => $this->configManager->getNamespaceFor(
                        $boundedContextName,
                        $moduleName,
                        'command-handler'
                    ),
                    "t_class_name" => $commandHandlerClassName,
                    "t_interface_full_namespace" => $baseClassReflector->getName(),
                    "t_interface_name" => $baseClassReflector->getShortName(),
                    "t_use_case_namespace" => "{$useCaseNamespace}\\" . $this->useCaseResponse->useCase(),
                    "t_use_case_class_name" => $this->useCaseResponse->useCase(),
                    "t_command_class_name" => "{$commandName}Command",
                ]
            )
        );
    }
}