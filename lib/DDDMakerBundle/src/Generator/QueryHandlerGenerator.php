<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;

class QueryHandlerGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $queryName): void
    {
    
        $querySuffix = "QueryHandler";
        $queryHandlerClassName = "{$queryName}{$querySuffix}";
        $queryHandlerFileName = "{$queryHandlerClassName}.php";
        $modulePath = PathFactory::forBoundedContextModules($boundedContextName, $moduleName);
        $commandquery = "{$modulePath}/Application/{$queryHandlerFileName}";
        
        if (file_exists($commandquery)) {
            throw new ElementAlreadyExistsException("Query handler {$queryHandlerClassName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\".");
        }
    
        file_put_contents($commandquery, "<?php \n\nnamespace Test\Module;\n\nclass {$queryHandlerClassName}\n{\n}\n");
    }
    
}