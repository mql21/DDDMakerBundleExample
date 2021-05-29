<?php


namespace Mql21\DDDMakerBundle\Factories;


use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;

class PathFactory
{
    // TODO, This shouldn't be a factory and ConfigManager should be injected via constructor
    public static function forBoundedContexts(): string
    {
        $configManager = new ConfigManager();
        return $configManager->getBoundedContextPath();
    }
    
    public static function forModules(string $boundedContextName): string
    {
        $configManager = new ConfigManager();
        return $configManager->getModulePath($boundedContextName);
    }
    
    public static function forBoundedContextModules(string $boundedContextName, string $moduleName): string
    {
        $configManager = new ConfigManager();
        return $configManager->getModulePath($boundedContextName) . "/{$moduleName}/";
    }
    
    public static function for(string $boundedContextName, string $moduleName, string $dddElement): string
    {
        $configManager = new ConfigManager();
        return $configManager->pathFor($boundedContextName, $moduleName, $dddElement);
    }
}