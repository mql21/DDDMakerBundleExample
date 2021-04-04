<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;

class CommandHandlerGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $commandName): void
    {
    
        $commandSuffix = "CommandHandler";
        $commandHandlerClassName = "{$commandName}{$commandSuffix}";
        $commandHandlerFileName = "{$commandHandlerClassName}.php";
        $modulePath = PathFactory::forBoundedContextModules($boundedContextName, $moduleName);
        $commandHandlerFullPath = "{$modulePath}/Application/{$commandHandlerFileName}";
        
        if (file_exists($commandHandlerFullPath)) {
            throw new ElementAlreadyExistsException("Command handler {$commandHandlerClassName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\".");
        }
    
        file_put_contents($commandHandlerFullPath, "<?php \n\nnamespace Test\Module;\n\nclass {$commandHandlerClassName}\n{\n}\n");
    }
    
}