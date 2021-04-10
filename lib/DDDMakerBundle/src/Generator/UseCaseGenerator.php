<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Renderer\PHPCodeRenderer;

class UseCaseGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $useCaseName): void
    {
        $useCaseFileName = "{$useCaseName}.php";
        $useCasePath = PathFactory::forUseCasesIn($boundedContextName, $moduleName);
        $useCaseFullPath = "{$useCasePath}{$useCaseFileName}";
        
        if (file_exists($useCaseFullPath)) {
            throw new ElementAlreadyExistsException(
                "Use case {$useCaseName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        $renderer = new PHPCodeRenderer();
        file_put_contents(
            $useCaseFullPath,
            $renderer->render(
                "lib/DDDMakerBundle/src/Templates/use_case.php.template",
                [
                    "t_namespace" => "Mql21\DDDMakerBundle\Generator",
                    "t_class_name" => $useCaseName,
                ]
            )
        );
    }
}