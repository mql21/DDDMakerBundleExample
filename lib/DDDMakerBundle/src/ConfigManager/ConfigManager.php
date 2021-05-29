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
    
    public function getModulePath(string $boundedContext): string
    {
        return str_replace(
            '{bounded_context}',
            $boundedContext,
            $this->config['ddd_elements']['module']['path']
        );
    }
    
    public function pathFor(string $boundedContext, string $module, string $dddElement): string
    {
        return str_replace(
            ['{bounded_context}', '{module}'],
            [$boundedContext, $module],
            $this->config['ddd_elements'][$dddElement]['path']
        );
    }
    
    public function namespaceFor(string $boundedContext, string $module, string $dddElement): string
    {
        return str_replace(
            ['{vendor}', '{bounded_context}', '{module}'],
            [$this->vendor(), $boundedContext, $module],
            $this->config['ddd_elements'][$dddElement]['namespace']
        );
    }
    
    public function classSuffixFor(string $dddElement): ?string
    {
        return $this->config['ddd_elements'][$dddElement]['suffix'] ?? null;
    }
    
    public function classToImplementFor(string $dddElement): ?string
    {
        return $this->config['ddd_elements'][$dddElement]['implements'] ?? null;
    }
    
    public function classToExtendFor(string $dddElement): ?string
    {
        return $this->config['ddd_elements'][$dddElement]['extends'] ?? null;
    }
    
    private function vendor(): string
    {
        return $this->config['vendor'];
    }
    
    public function directoriesFor(string $boundedContextName, string $moduleName): array
    {
        $directories = [];
        foreach ($this->config["ddd_elements"] as $dddElementConfig) {
            $path = $dddElementConfig["path"];
            $directories[] = str_replace(
                ['{vendor}', '{bounded_context}', '{module}'],
                [$this->vendor(), $boundedContextName, $moduleName],
                $path
            );
        }
        
        return $directories;
    }
}