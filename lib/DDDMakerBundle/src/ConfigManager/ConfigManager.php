<?php

namespace Mql21\DDDMakerBundle\ConfigManager;

use Symfony\Component\Yaml\Yaml;

class ConfigManager
{
    private array $config;
    
    public function __construct()
    {
        $this->config = Yaml::parseFile("lib/DDDMakerBundle/config/config.yaml");
    }
    
    public function getBoundedContextPath()
    {
        return $this->config['ddd_elements']['bounded_context']['path'];
    }
    
    public function getModulePath(string $boundedContext)
    {
        return str_replace(
            '{bounded_context}',
            $boundedContext,
            $this->config['ddd_elements']['module']['path']
        );
    }
    
    public function getPathFor(string $boundedContext, string $module, string $dddElement)
    {
        return str_replace(
            ['{bounded_context}', '{module}'],
            [$boundedContext, $module],
            $this->config['ddd_elements'][$dddElement]['path']
        );
    }
    
    public function getNamespaceFor(string $boundedContext, string $module, string $dddElement)
    {
        return str_replace(
            ['{company_name}', '{bounded_context}', '{module}'],
            [$this->companyName(), $boundedContext, $module],
            $this->config['ddd_elements'][$dddElement]['namespace']
        );
    }
    
    public function getClassToImplementFor(string $dddElement)
    {
        return $this->config['ddd_elements'][$dddElement]['implements'] ?? null;
    }
    
    public function getClassToExtendFor(string $dddElement)
    {
        return $this->config['ddd_elements'][$dddElement]['extends'] ?? null;
    }
    
    private function companyName(): mixed
    {
        return $this->config['company_name'];
    }
}