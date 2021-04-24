<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;

class QueryGenerator extends DTOGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $queryName): void
    {
        $querySuffix = "Query";
        $queryClassName = "{$queryName}{$querySuffix}";
        $queryFileName = "{$queryClassName}.php";
        $queryPath = PathFactory::forQueriesIn($boundedContextName, $moduleName);
        $queryFullPath = "{$queryPath}{$queryFileName}";
        
        if (file_exists($queryFullPath)) {
            throw new ElementAlreadyExistsException(
                "Query {$queryName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        $baseClassReflector = new \ReflectionClass($this->configManager->getClassToImplementFor('query'));
        
        file_put_contents(
            $queryFullPath,
            $this->renderer->render(
                "lib/DDDMakerBundle/src/Templates/query.php.template",
                [
                    "t_namespace" => $this->configManager->getNamespaceFor(
                        $boundedContextName,
                        $moduleName,
                        'query'
                    ),
                    "t_class_name" => $queryClassName,
                    "t_interface_full_namespace" => $baseClassReflector->getName(),
                    "t_interface_name" => $baseClassReflector->getShortName(),
                    "t_attributes" => $this->classAttributes->attributes()
                ]
            )
        );
    }
}