<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\DTO\ClassDTO;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;

class QueryResponseGenerator extends DTOGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $responseName): void
    {
        $classDTO = new ClassDTO($boundedContextName, $moduleName, $responseName, 'response');
    
        if (file_exists($classDTO->elementFullPath())) {
            throw new ElementAlreadyExistsException(
                "Response {$responseName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        file_put_contents(
            $classDTO->elementFullPath(),
            $this->renderer->render(
                "lib/DDDMakerBundle/src/Templates/response.php.template",
                [
                    "t_namespace" => $classDTO->namespace(),
                    "t_class_name" => $classDTO->elementClassName(),
                    "t_attributes" => $this->classAttributes->attributes()
                ]
            )
        );
    }
}