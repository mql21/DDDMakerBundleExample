<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Templates\DTOTemplateData;

class UseCaseGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $useCaseName): void
    {
        $useCaseFileName = "{$useCaseName}.php";
        $useCasePath = PathFactory::forUseCasesIn($boundedContextName, $moduleName);
        $useCaseFullPath = "{$useCasePath}{$useCaseFileName}";
        
        if (file_exists($useCaseFullPath)) {
            throw new ElementAlreadyExistsException("Use case {$useCaseName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\".");
        }
    
        file_put_contents($useCaseFullPath, $this->renderResponseTemplate($useCaseName));
    }
    
    // TODO extract and inject as collaborator
    protected function renderResponseTemplate(string $useCaseName): string
    {
        $template = file_get_contents(
            "lib/DDDMakerBundle/src/Templates/use_case.php.template"
        ); //TODO: get via config (DI)
        $classContent = str_replace("{{t_namespace}}", "Mql21\DDDMakerBundle\Generator", $template);
        $classContent = str_replace("{{t_class_name}}", $useCaseName, $classContent);
        
        return $classContent;
    }
}