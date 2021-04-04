<?php


namespace Mql21\DDDMakerBundle;


class PathGenerator
{
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
    
    public static function forBoundedContextModuleCommands(string $boundedContextName, string $moduleName)
    {
        return self::$BASE_PATH . "{$boundedContextName}/{$moduleName}/Application/";
    }
}