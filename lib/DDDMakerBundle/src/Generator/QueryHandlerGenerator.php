<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;

class QueryHandlerGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $queryName): void
    {
    
        $querySuffix = "QueryHandler";
        $queryHandlerClassName = "{$queryName}{$querySuffix}";
        $queryHandlerFileName = "{$queryHandlerClassName}.php";
        $modulePath = PathFactory::forBoundedContextModules($boundedContextName, $moduleName);
        $queryHandlerFullPath = "{$modulePath}/Application/Query/{$queryHandlerFileName}";
        
        if (file_exists($queryHandlerFullPath)) {
            throw new ElementAlreadyExistsException("Query handler {$queryHandlerClassName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\".");
        }
    
        file_put_contents($queryHandlerFullPath, "<?php \n\nnamespace Test\Module;\n\nclass {$queryHandlerClassName}\n{\n}\n");
    }
    
}