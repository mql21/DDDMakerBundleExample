<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\DirectoryNotFoundException;
use Mql21\DDDMakerBundle\Generator\Builder\DDDClassBuilder;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Renderer\PHPCodeRenderer;

class UseCaseGenerator implements DDDElementGenerator
{
    private PHPCodeRenderer $renderer;
    
    public function __construct()
    {
        $this->renderer = new PHPCodeRenderer();
    }
    
    public function generate(string $boundedContextName, string $moduleName, string $useCaseName): void
    {
        $dddClassBuilder = DDDClassBuilder::create()
            ->forBoundedContext($boundedContextName)
            ->forModule($moduleName)
            ->withClassName($useCaseName)
            ->ofDDDElementType($this->type())
            ->build();
    
        if (file_exists($dddClassBuilder->elementFullPath())) {
            ElementAlreadyExistsException::raise($useCaseName, $boundedContextName, $moduleName);
        }
    
        if (!file_exists(dirname($dddClassBuilder->elementFullPath()))) {
            DirectoryNotFoundException::raise($dddClassBuilder->elementFullPath());
        }
        
        file_put_contents(
            $dddClassBuilder->elementFullPath(),
            $this->renderer->render(
                "lib/DDDMakerBundle/src/Templates/use_case.php.template",
                [
                    "t_namespace" => $dddClassBuilder->namespace(),
                    "t_class_name" => $useCaseName,
                ]
            )
        );
    }
    
    public function type(): string
    {
        return 'use-case';
    }
}