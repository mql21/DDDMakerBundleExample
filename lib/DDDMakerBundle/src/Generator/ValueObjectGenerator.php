<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\DTO\ClassDTO;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Renderer\PHPCodeRenderer;

class ValueObjectGenerator implements DDDElementGenerator
{
    private PHPCodeRenderer $renderer;
    
    public function __construct()
    {
        $this->renderer = new PHPCodeRenderer();
    }
    
    public function generate(string $boundedContextName, string $moduleName, string $valueObjectName): void
    {
        $classDTO = new ClassDTO($boundedContextName, $moduleName, $valueObjectName, 'value-object');
    
        if (file_exists($classDTO->elementFullPath())) {
            throw new ElementAlreadyExistsException("Value Object {$valueObjectName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\".");
        }
        
        file_put_contents(
            $classDTO->elementFullPath(),
            $this->renderer->render(
                "lib/DDDMakerBundle/src/Templates/value_object.php.template",
                [
                    "t_namespace" => $classDTO->namespace(),
                    "t_class_name" => $valueObjectName,
                ]
            )
        );
    }
    
}