<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;

class ValueObjectGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $valueObjectName): void
    {
        $valueObjectFileName = "{$valueObjectName}.php";
        $modulePath = PathFactory::forBoundedContextModules($boundedContextName, $moduleName);
        $valueObjectFullPath = "{$modulePath}/Domain/ValueObject/{$valueObjectFileName}";
        
        if (file_exists($valueObjectFullPath)) {
            throw new ElementAlreadyExistsException("Value Object {$valueObjectName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\".");
        }
    
        file_put_contents($valueObjectFullPath, "<?php \n\nnamespace Test\Module;\n\nclass {$valueObjectName}\n{\n}\n");
    }
    
}