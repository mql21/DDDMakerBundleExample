<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;

class UseCaseGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $useCaseName): void
    {
        $useCaseFileName = "{$useCaseName}.php";
        $useCasePath = PathFactory::forUseCasesIn($boundedContextName, $moduleName);
        $useCaseFullPath = "{$useCasePath}{$useCaseFileName}";
        
        if (file_exists($useCaseFullPath)) {
            throw new ElementAlreadyExistsException("Use case {$useCaseName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\".");
        }
    
        file_put_contents($useCaseFullPath, "<?php \n\nnamespace Test\Module;\n\nclass {$useCaseName}\n{\n}\n");
    }
    
}