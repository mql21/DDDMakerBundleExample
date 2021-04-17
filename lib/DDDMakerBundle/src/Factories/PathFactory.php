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
    
    public static function forModules(string $boundedContextName)
    {
        $configManager = new ConfigManager();
        return $configManager->getModulePath($boundedContextName);
    }
    
    public static function forBoundedContextModules(string $boundedContextName, string $moduleName)
    {
        $configManager = new ConfigManager();
        return $configManager->getModulePath($boundedContextName) . "/{$moduleName}/";
    }
    
    public static function forCommandsIn(string $boundedContextName, string $moduleName)
    {
        $configManager = new ConfigManager();
        return $configManager->getPathFor($boundedContextName, $moduleName, 'command');
    }
    
    public static function forQueriesIn(string $boundedContextName, string $moduleName)
    {
        $configManager = new ConfigManager();
        return $configManager->getPathFor($boundedContextName, $moduleName, 'query');
    }
    
    public static function forDomainEventsIn(string $boundedContextName, string $moduleName)
    {
        $configManager = new ConfigManager();
        return $configManager->getPathFor($boundedContextName, $moduleName, 'domain-event');
    }
    
    public static function forEventSubscribersIn(string $boundedContextName, string $moduleName)
    {
        $configManager = new ConfigManager();
        return $configManager->getPathFor($boundedContextName, $moduleName, 'event-subscriber');
    }
    
    public static function forUseCasesIn(string $boundedContextName, string $moduleName)
    {
        $configManager = new ConfigManager();
        return $configManager->getPathFor($boundedContextName, $moduleName, 'use-case');
    }
    
    public static function forResponsesIn(string $boundedContextName, string $moduleName)
    {
        $configManager = new ConfigManager();
        return $configManager->getPathFor($boundedContextName, $moduleName, 'response');
    }
    
    public static function forValueObjectsIn(string $boundedContextName, string $moduleName)
    {
        $configManager = new ConfigManager();
        return $configManager->getPathFor($boundedContextName, $moduleName, 'value-object');
    }
}