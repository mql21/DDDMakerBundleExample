<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Templates\DTOTemplateData;

class QueryGenerator extends DTOGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $queryName): void
    {
        $querySuffix = "Query";
        $queryClassName = "{$queryName}{$querySuffix}";
        $queryFileName = "{$queryClassName}.php";
        $modulePath = PathFactory::forBoundedContextModules($boundedContextName, $moduleName);
        $queryFullPath = "{$modulePath}/Application/Query/{$queryFileName}";
        
        if (file_exists($queryFullPath)) {
            throw new ElementAlreadyExistsException(
                "Query {$queryName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        file_put_contents($queryFullPath,  $this->renderCommandTemplate(
            new DTOTemplateData(
                "App\Shared\Domain\Bus\Query\Query",
                $queryClassName,
                $this->classAttributes->attributes()
            )
        ));
    }
    
    // TODO extract and inject as collaborator
    protected function renderCommandTemplate(DTOTemplateData $templateData): string
    {
        $template = file_get_contents(
            "lib/DDDMakerBundle/src/Templates/command.php.template"
        ); //TODO: get via config (DI)
        $baseClassReflectionObject = new \ReflectionClass($templateData->baseClassNamespace());
        
        $classContent = str_replace("{{t_namespace}}", "Mql21\DDDMakerBundle\Generator", $template);
        $classContent = str_replace("{{t_class_name}}", $templateData->getClassName(), $classContent);
        $classContent = str_replace(
            "{{t_interface_full_namespace}}",
            $templateData->baseClassNamespace(),
            $classContent
        );
        
        $classContent = str_replace(
            "{{t_interface_name}}",
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