<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\DirectoryNotFoundException;
use Mql21\DDDMakerBundle\Generator\Builder\DDDClassBuilder;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;

class CommandGenerator extends DTOGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $commandName): void
    {
        $dddClassBuilder = DDDClassBuilder::create()
            ->forBoundedContext($boundedContextName)
            ->forModule($moduleName)
            ->withClassName($commandName)
            ->ofDDDElementType($this->type())
            ->build();
        
        if (file_exists($dddClassBuilder->elementFullPath())) {
            ElementAlreadyExistsException::raise($commandName, $boundedContextName, $moduleName);
        }
    
        if (!file_exists(dirname($dddClassBuilder->elementFullPath()))) {
            DirectoryNotFoundException::raise($dddClassBuilder->elementFullPath());
        }
        
        file_put_contents(
            $dddClassBuilder->elementFullPath(),
            $this->renderer->render(
                "lib/DDDMakerBundle/src/Templates/command.php.template",
                [
                    "t_namespace" => $dddClassBuilder->namespace(),
                    "t_class_name" => $dddClassBuilder->elementClassName(),
                    "t_interface_full_namespace" => $dddClassBuilder->interfaceToImplementNamespace(),
                    "t_interface_name" => $dddClassBuilder->interfaceToImplementName(),
                    "t_attributes" => $this->classAttributes->attributes()
                ]
            )
        );
    }
    
    public function type(): string
    {
        return 'command';
    }
}