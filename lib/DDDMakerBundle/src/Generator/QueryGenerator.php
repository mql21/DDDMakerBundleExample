<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Renderer\PHPCodeRenderer;

class QueryGenerator extends DTOGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $queryName): void
    {
        $querySuffix = "Query";
        $queryClassName = "{$queryName}{$querySuffix}";
        $queryFileName = "{$queryClassName}.php";
        $modulePath = PathFactory::forBoundedContextModules($boundedContextName, $moduleName);
        $queryFullPath = "{$modulePath}/Application/Query/{$queryFileName}";
        
        if (file_exists($queryFullPath)) {
            throw new ElementAlreadyExistsException(
                "Query {$queryName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        $baseClassReflectionObject = new \ReflectionClass("App\Shared\Domain\Bus\Query\Query");
        
        $renderer = new PHPCodeRenderer();
        file_put_contents(
            $queryFullPath,
            $renderer->render(
                "lib/DDDMakerBundle/src/Templates/query.php.template",
                [
                    "t_namespace" => "Mql21\DDDMakerBundle\Generator",
                    "t_class_name" => $queryClassName,
                    "t_interface_full_namespace" => $baseClassReflectionObject->getName(),
                    "t_interface_name" => $baseClassReflectionObject->getShortName(),
                    "t_attributes" => $this->classAttributes->attributes()
                ]
            )
        );
    }
}