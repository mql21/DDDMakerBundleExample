<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Templates\DTOTemplateData;
use Mql21\DDDMakerBundle\Templates\HandlerTemplateData;

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
        
        file_put_contents(
            $commandHandlerFullPath,
            $this->renderCommandHandlerTemplate(
                new HandlerTemplateData(
                    "Mql21\DDDMakerBundle\Generator",
                    $commandHandlerClassName,
                    "App\Shared\Domain\Bus\Command\CommandHandler",
                    "{$commandName}Command",
                    $this->useCaseResponse->useCase()
                )
            )
        );
    }
    
    // TODO extract and inject as collaborator
    protected function renderCommandHandlerTemplate(HandlerTemplateData $templateData): string
    {
        $template = file_get_contents(
            "lib/DDDMakerBundle/src/Templates/command_handler.php.template"
        ); //TODO: get via config (DI)
        
        $interfaceReflectionClass = new \ReflectionClass($templateData->interfaceNamespace());
        
        $classContent = str_replace("{{t_namespace}}", $templateData->classNamespace(), $template);
        $classContent = str_replace("{{t_interface_full_namespace}}", $templateData->interfaceNamespace(), $classContent);
        $classContent = str_replace("{{t_interface_name}}", $interfaceReflectionClass->getShortName(), $classContent);
        $classContent = str_replace("{{t_class_name}}", $templateData->className(), $classContent);
        $classContent = str_replace("{{t_use_case_class_name}}", $templateData->useCaseName(), $classContent);
        $classContent = str_replace("{{t_command_class_name}}", $templateData->command(), $classContent);
        
        return $classContent;
    }
}