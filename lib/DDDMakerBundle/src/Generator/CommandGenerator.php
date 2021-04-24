<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Renderer\PHPCodeRenderer;

class CommandGenerator extends DTOGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $commandName): void
    {
        $commandSuffix = "Command";
        $commandClassName = "{$commandName}{$commandSuffix}";
        $commandFileName = "{$commandClassName}.php";
        $commandsPath = PathFactory::forCommandsIn($boundedContextName, $moduleName);
        $commandFullPath = "{$commandsPath}{$commandFileName}";
        
        if (file_exists($commandFullPath)) {
            throw new ElementAlreadyExistsException(
                "Command {$commandName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        $configManager = new ConfigManager(); // TODO Inject via DI
        $classToImplementReflector = new \ReflectionClass($configManager->getClassToImplementFor('command'));
        
        $renderer = new PHPCodeRenderer();
        file_put_contents(
            $commandFullPath,
            $renderer->render(
                "lib/DDDMakerBundle/src/Templates/command.php.template",
                [
                    "t_namespace" => "Mql21\DDDMakerBundle\Generator",
                    "t_class_name" => $commandClassName,
                    "t_interface_full_namespace" => $classToImplementReflector->getName(),
                    "t_interface_name" => $classToImplementReflector->getShortName(),
                    "t_attributes" => $this->classAttributes->attributes()
                ]
            )
        );
    }
}