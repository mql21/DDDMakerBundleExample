<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;

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
        
        $domainEventBaseClassNamespace = "App\Shared\Domain\Bus\Event\DomainEvent"; // TODO, get from config
        $eventClassContent = $this->renderEventTemplate(
            $eventClassName,
            $domainEventBaseClassNamespace,
            $this->classAttributes
        );
        file_put_contents($eventFullPath, $eventClassContent);
    }
    
    protected function renderEventTemplate(
        string $eventClassName,
        string $domainEventBaseClassNamespace,
        array $classAttributes
    ): string {
        $template = file_get_contents("lib/DDDMakerBundle/src/Templates/event.php.template"); //TODO: get via config (DI)
        $eventBaseClassReflectionObject = new \ReflectionClass($domainEventBaseClassNamespace);
        
        $eventClassContent = str_replace("{{t_class_name}}", $eventClassName, $template);
        $eventClassContent = str_replace(
            "{{t_base_class_full_namespace}}",
            $domainEventBaseClassNamespace,
            $eventClassContent
        );
        
        $eventClassContent = str_replace(
            "{{t_base_class_name}}",
            $eventBaseClassReflectionObject->getShortName(),
            $eventClassContent
        );
        
        $attributesPHPCode = [];
        $gettersPHPCode = [];
        
        foreach ($classAttributes as $attribute => $type) {
            $attributesPHPCode[] = "    private {$type} \${$attribute};";
            $gettersPHPCode[] = $this->getGetterCode($attribute, $type);
        }
        
        $eventClassContent = str_replace("{{t_attributes}}", implode("\n", $attributesPHPCode), $eventClassContent);
        $eventClassContent = str_replace("{{t_getters}}", implode("\n\n", $gettersPHPCode), $eventClassContent);
        
        return $eventClassContent;
    }
    
    protected function getGetterCode(string $attribute, mixed $type): string
    {
        return "    public function {$attribute}(): {$type}\n    {\n        return \$this->{$attribute};\n    }";
    }
    
}