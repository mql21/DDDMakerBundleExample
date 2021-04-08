<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Templates\DTOTemplateData;

class DomainEventGenerator extends DTOGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $eventName): void
    {
        $eventSuffix = "DomainEvent";
        $eventClassName = "{$eventName}{$eventSuffix}";
        $eventFileName = "{$eventClassName}.php";
        $modulePath = PathFactory::forBoundedContextModules($boundedContextName, $moduleName);
        $eventFullPath = "{$modulePath}/Domain/Event/{$eventFileName}";
        
        if (file_exists($eventFullPath)) {
            throw new ElementAlreadyExistsException(
                "Domain event {$eventName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        file_put_contents(
            $eventFullPath,
            $this->renderEventTemplate(
                new DTOTemplateData(
                    "App\Shared\Domain\Bus\Event\DomainEvent",
                    $eventClassName,
                    $this->classAttributes->attributes()
                )
            )
        );
    }
    
    // TODO extract and inject as collaborator
    protected function renderEventTemplate(DTOTemplateData $templateData): string
    {
        $template = file_get_contents(
            "lib/DDDMakerBundle/src/Templates/event.php.template"
        ); //TODO: get via config (DI)
        $baseClassReflectionObject = new \ReflectionClass($templateData->baseClassNamespace());
        
        $classContent = str_replace("{{t_class_name}}", $templateData->getClassName(), $template);
        $classContent = str_replace(
            "{{t_base_class_full_namespace}}",
            $templateData->baseClassNamespace(),
            $classContent
        );
        
        $classContent = str_replace(
            "{{t_base_class_name}}",
            $baseClassReflectionObject->getShortName(),
            $classContent
        );
        
        $attributesPHPCode = [];
        $gettersPHPCode = [];
        
        foreach ($templateData->classAttributes() as $attribute => $type) {
            $attributesPHPCode[] = "    private {$type} \${$attribute};";
            $gettersPHPCode[] = $this->getGetterCode($attribute, $type);
        }
        
        $classContent = str_replace("{{t_attributes}}", implode("\n", $attributesPHPCode), $classContent);
        $classContent = str_replace("{{t_getters}}", implode("\n\n", $gettersPHPCode), $classContent);
        
        return $classContent;
    }
    
    protected function getGetterCode(string $attribute, mixed $type): string
    {
        return "    public function {$attribute}(): {$type}\n    {\n        return \$this->{$attribute};\n    }";
    }
    
}