<?php


namespace Mql21\DDDMakerBundle\Factories;


class PathFactory
{
    // TODO: All relative paths should be injected via config
    private static string $BASE_PATH = "src/";
    
    public static function basePath(): string
    {
        return self::$BASE_PATH;
    }
    
    public static function forBoundedContexts(string $boundedContextName)
    {
        return self::$BASE_PATH . "{$boundedContextName}/";
    }
    
    public static function forBoundedContextModules(string $boundedContextName, string $moduleName)
    {
        return self::$BASE_PATH . "{$boundedContextName}/{$moduleName}/";
    }
    
    public static function forCommandsIn(string $boundedContextName, string $moduleName)
    {
        return self::$BASE_PATH . "{$boundedContextName}/{$moduleName}/Application/Command/";
    }
    
    public static function forQueriesIn(string $boundedContextName, string $moduleName)
    {
        return self::$BASE_PATH . "{$boundedContextName}/{$moduleName}/Application/Query/";
    }
    
    public static function forDomainEventsIn(string $boundedContextName, string $moduleName)
    {
        return self::$BASE_PATH . "{$boundedContextName}/{$moduleName}/Domain/Event/";
    }
    
    public static function forUseCasesIn(string $boundedContextName, string $moduleName)
    {
        return self::$BASE_PATH . "{$boundedContextName}/{$moduleName}/Application/UseCase/";
    }
    
    public static function forResponsesIn(string $boundedContextName, string $moduleName)
    {
        return self::$BASE_PATH . "{$boundedContextName}/{$moduleName}/Application/Response/";
    }
}