<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Renderer\PHPCodeRenderer;

class QueryResponseGenerator extends DTOGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $responseName): void
    {
        $responseSuffix = "Response";
        $responseClassName = "{$responseName}{$responseSuffix}";
        $responseFileName = "{$responseClassName}.php";
        $modulePath = PathFactory::forBoundedContextModules($boundedContextName, $moduleName);
        $responseFullPath = "{$modulePath}/Application/Response/{$responseFileName}";
        
        if (file_exists($responseFullPath)) {
            throw new ElementAlreadyExistsException(
                "Response {$responseName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        $renderer = new PHPCodeRenderer();
        file_put_contents(
            $responseFullPath,
            $renderer->render(
                "lib/DDDMakerBundle/src/Templates/response.php.template",
                [
                    "t_namespace" => "Mql21\DDDMakerBundle\Generator",
                    "t_class_name" => $responseClassName,
                    "t_attributes" => $this->classAttributes->attributes()
                ]
            )
        );
    }
}