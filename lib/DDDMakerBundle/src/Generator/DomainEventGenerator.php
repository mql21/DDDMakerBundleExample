<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\DirectoryNotFoundException;
use Mql21\DDDMakerBundle\Generator\Builder\DDDClassBuilder;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;

class DomainEventGenerator extends DTOGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $eventName): void
    {
        $dddClassBuilder = DDDClassBuilder::create()
            ->forBoundedContext($boundedContextName)
            ->forModule($moduleName)
            ->withClassName($eventName)
            ->ofDDDElementType($this->type())
            ->build();
        
        if (file_exists($dddClassBuilder->elementFullPath())) {
            ElementAlreadyExistsException::raise($eventName, $boundedContextName, $moduleName);
        }
    
        if (!file_exists(dirname($dddClassBuilder->elementFullPath()))) {
            DirectoryNotFoundException::raise($dddClassBuilder->elementFullPath());
        }
        
        file_put_contents(
            $dddClassBuilder->elementFullPath(),
            $this->renderer->render(
                "lib/DDDMakerBundle/src/Templates/event.php.template",
                [
                    "t_namespace" =>  $dddClassBuilder->namespace(),
                    "t_class_name" =>  $dddClassBuilder->elementClassName(),
                    "t_base_class_full_namespace" => $dddClassBuilder->classToExtendNamespace(),
                    "t_base_class_name" => $dddClassBuilder->classToExtendName(),
                    "t_attributes" => $this->classAttributes->attributes()
                ]
            )
        );
    }
    
    public function type(): string
    {
        return 'domain-event';
    }
}