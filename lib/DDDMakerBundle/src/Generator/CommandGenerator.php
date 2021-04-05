<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;

class CommandGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $commandName): void
    {
    
        $commandSuffix = "Command";
        $commandClassName = "{$commandName}{$commandSuffix}";
        $commandFileName = "{$commandClassName}.php";
        $modulePath = PathFactory::forBoundedContextModules($boundedContextName, $moduleName);
        $commandFullPath = "{$modulePath}/Application/Command/{$commandFileName}";
        
        if (file_exists($commandFullPath)) {
            throw new ElementAlreadyExistsException("Command {$commandName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\".");
        }
    
        file_put_contents($commandFullPath, "<?php \n\nnamespace Test\Module;\n\nclass {$commandClassName}\n{\n}\n");
    }
    
}