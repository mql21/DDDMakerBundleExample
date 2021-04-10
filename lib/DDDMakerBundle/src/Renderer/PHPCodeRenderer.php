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
        
        return $this->renderAttributtesAndGetters($classAttributes, $template);
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
    
    private function renderAttributesPhpCode(array $attributes): string
    {
        $attributesPHPCode = [];
        
        foreach ($attributes as $attribute => $type) {
            $attributesPHPCode[] = "    private {$type} \${$attribute};";
        }
        
        return implode("\n", $attributesPHPCode);
    }
    
    private function getGetterCode(string $attribute, mixed $type): string
    {
        return "    public function {$attribute}(): {$type}\n    {\n        return \$this->{$attribute};\n    }";
    }
    
    private function renderGettersPhpCode(array $attributes): string
    {
        $gettersPHPCode = [];
        
        foreach ($attributes as $attribute => $type) {
            $gettersPHPCode[] = $this->getGetterCode($attribute, $type);
        }
        
        return implode("\n\n", $gettersPHPCode);
    }
    
    protected function renderAttributtesAndGetters(mixed $classAttributes, string $template): string
    {
        $template = str_replace("{{t_attributes}}", $this->renderAttributesPhpCode($classAttributes), $template);
        $template = str_replace("{{t_getters}}", $this->renderGettersPhpCode($classAttributes), $template);
        
        return $template;
    }
}