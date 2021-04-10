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
        $modulePath = PathFactory::forBoundedContextModules($boundedContextName, $moduleName);
        $commandHandlerFullPath = "{$modulePath}/Application/Command/{$commandHandlerFileName}";
        
        if (file_exists($commandHandlerFullPath)) {
            throw new ElementAlreadyExistsException(
                "Command handler {$commandHandlerClassName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        $baseClassReflectionObject = new \ReflectionClass("App\Shared\Domain\Bus\Command\CommandHandler");
        
        $renderer = new PHPCodeRenderer();
        file_put_contents(
            $commandHandlerFullPath,
            $renderer->render(
                "lib/DDDMakerBundle/src/Templates/command_handler.php.template",
                [
                    "t_namespace" => "Mql21\DDDMakerBundle\Generator",
                    "t_class_name" => $commandHandlerClassName,
                    "t_interface_full_namespace" => $baseClassReflectionObject->getName(),
                    "t_interface_name" => $baseClassReflectionObject->getShortName(),
                    "t_use_case_class_name" => $this->useCaseResponse->useCase(),
                    "t_command_class_name" => "{$commandName}Command",
                ]
            )
        );
    }
}