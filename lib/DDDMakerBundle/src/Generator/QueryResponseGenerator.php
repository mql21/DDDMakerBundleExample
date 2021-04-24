<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;

class QueryResponseGenerator extends DTOGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $responseName): void
    {
        $responseSuffix = "Response";
        $responseClassName = "{$responseName}{$responseSuffix}";
        $responseFileName = "{$responseClassName}.php";
        $responsesPath = PathFactory::forResponsesIn($boundedContextName, $moduleName);
        $responseFullPath = "{$responsesPath}{$responseFileName}";
        
        if (file_exists($responseFullPath)) {
            throw new ElementAlreadyExistsException(
                "Response {$responseName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        file_put_contents(
            $responseFullPath,
            $this->renderer->render(
                "lib/DDDMakerBundle/src/Templates/response.php.template",
                [
                    "t_namespace" =>  $this->configManager->getNamespaceFor(
                        $boundedContextName,
                        $moduleName,
                        'response'
                    ),
                    "t_class_name" => $responseClassName,
                    "t_attributes" => $this->classAttributes->attributes()
                ]
            )
        );
    }
}