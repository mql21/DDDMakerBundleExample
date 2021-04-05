<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;

class QueryResponseGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $responseName): void
    {
    
        $responseSuffix = "Response";
        $responseClassName = "{$responseName}{$responseSuffix}";
        $responseFileName = "{$responseClassName}.php";
        $modulePath = PathFactory::forBoundedContextModules($boundedContextName, $moduleName);
        $responseFullPath = "{$modulePath}/Application/{$responseFileName}";
        
        if (file_exists($responseFullPath)) {
            throw new ElementAlreadyExistsException("Command {$responseName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\".");
        }
    
        file_put_contents($responseFullPath, "<?php \n\nnamespace Test\Module;\n\nclass {$responseClassName}\n{\n}\n");
    }
    
}