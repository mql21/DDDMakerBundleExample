<?php

namespace Mql21\DDDMakerBundle\Renderer;

use Mql21\DDDMakerBundle\Renderer\Contract\Renderer;

class PHPCodeRenderer implements Renderer
{
    public function render(string $templatePath, array $templateVars): string
    {
        $template = file_get_contents($templatePath);
        
        $this->checkRequiredVars($this->requiredVars(), $template, $templateVars);
        
        $classAttributes = [];
        if (isset($templateVars['t_attributes'])) {
            $classAttributes = $templateVars['t_attributes'];
            unset($templateVars['t_attributes']);
        }
        
        foreach ($templateVars as $name => $value) {
            $template = str_replace("{{{$name}}}", $value, $template);
        }
        
        if (empty($classAttributes)) {
            return $template;
        }
        $template = $this->renderClassConstructor($classAttributes, $template);
        
        return $this->renderAttributesAndGetters($classAttributes, $template);
    }
    
    private function checkRequiredVars(array $requiredVars, string $template, array $templateVars): void
    {
        foreach ($requiredVars as $requiredVar) {
            if (str_contains($template, $requiredVar) && !isset($templateVars[$requiredVar])) {
                throw new \Exception("Var '{$requiredVar}' is required.");
            }
        }
    }
    
    private function requiredVars(): array
    {
        return ['t_attributes'];
    }
    
    private function attributesPhpCode(array $attributes): string
    {
        $attributesPHPCode = [];
        
        foreach ($attributes as $name => $type) {
            $attributesPHPCode[] = "    private {$type} \${$name};";
        }
        
        return implode("\n", $attributesPHPCode);
    }
    
    private function getGetterCode(string $attribute, mixed $type): string
    {
        return "    public function {$attribute}(): {$type}\n    {\n        return \$this->{$attribute};\n    }";
    }
    
    private function gettersPhpCode(array $attributes): string
    {
        $gettersPHPCode = [];
        
        foreach ($attributes as $name => $type) {
            $gettersPHPCode[] = $this->getGetterCode($name, $type);
        }
        
        return implode("\n\n", $gettersPHPCode);
    }
    
    protected function renderAttributesAndGetters(mixed $classAttributes, string $template): string
    {
        $template = str_replace("{{t_attributes}}", $this->attributesPhpCode($classAttributes), $template);
        $template = str_replace("{{t_getters}}", $this->gettersPhpCode($classAttributes), $template);
        
        return $template;
    }
    
    private function renderClassConstructor(array $attributes, string $template): string
    {
        $template = str_replace("{{t_constructor_parameters}}", $this->constructorParameters($attributes), $template);
        
        return str_replace(
            "{{t_constructor_initialization}}",
            $this->constructorInitialization($attributes),
            $template
        );
    }
    
    private function constructorParameters(array $attributes)
    {
        $constructorParameters = [];
        
        foreach ($attributes as $name => $type) {
            $constructorParameters[] = "{$type} \${$name}";
        }
        
        return implode(", ", $constructorParameters);
    }
    
    private function constructorInitialization(array $attributes): string
    {
        $constructorInitializationAttributes = [];
        
        foreach ($attributes as $name => $type) {
            $constructorInitializationAttributes[] = "        \$this->{$name} = \${$name};";
        }
        
        return implode("\n", $constructorInitializationAttributes);
    }
}