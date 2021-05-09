<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Generator\Builder\DDDClassBuilder;
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
        $dddClassBuilder = DDDClassBuilder::create()
            ->forBoundedContext($boundedContextName)
            ->forModule($moduleName)
            ->withClassName($valueObjectName)
            ->ofDDDElementType($this->type())
            ->build();
    
        if (file_exists($dddClassBuilder->elementFullPath())) {
            throw new ElementAlreadyExistsException("Value Object {$valueObjectName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\".");
        }
        
        file_put_contents(
            $dddClassBuilder->elementFullPath(),
            $this->renderer->render(
                "lib/DDDMakerBundle/src/Templates/value_object.php.template",
                [
                    "t_namespace" => $dddClassBuilder->namespace(),
                    "t_class_name" => $valueObjectName,
                ]
            )
        );
    }
    
    public function type(): string
    {
        return 'value-object';
    }
}