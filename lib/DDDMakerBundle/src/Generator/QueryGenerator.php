<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;

class QueryGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $queryName): void
    {
    
        $querySuffix = "Query";
        $queryClassName = "{$queryName}{$querySuffix}";
        $queryFileName = "{$queryClassName}.php";
        $modulePath = PathFactory::forBoundedContextModules($boundedContextName, $moduleName);
        $queryFullPath = "{$modulePath}/Application/Query/{$queryFileName}";
        
        if (file_exists($queryFullPath)) {
            throw new ElementAlreadyExistsException("Query {$queryName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\".");
        }
    
        file_put_contents($queryFullPath, "<?php \n\nnamespace Test\Module;\n\nclass {$queryClassName}\n{\n}\n");
    }
    
}