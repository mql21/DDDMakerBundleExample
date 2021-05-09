<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Renderer\PHPCodeRenderer;

class ValueObjectGenerator implements DDDElementGenerator
{
    private ConfigManager $configManager;
    private PHPCodeRenderer $renderer;
    
    public function __construct()
    {
        $this->configManager = new ConfigManager();
        $this->renderer = new PHPCodeRenderer();
    }
    
    public function generate(string $boundedContextName, string $moduleName, string $valueObjectName): void
    {
        $valueObjectSuffix = $this->configManager->getClassSuffixFor('value-object');
        $valueObjectFileName = "{$valueObjectName}{$valueObjectSuffix}.php";
        $valueObjectsPath = PathFactory::forValueObjectsIn($boundedContextName, $moduleName);
        $valueObjectFullPath = "{$valueObjectsPath}{$valueObjectFileName}";
        
        if (file_exists($valueObjectFullPath)) {
            throw new ElementAlreadyExistsException("Value Object {$valueObjectName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\".");
        }
        
        file_put_contents(
            $valueObjectFullPath,
            $this->renderer->render(
                "lib/DDDMakerBundle/src/Templates/value_object.php.template",
                [
                    "t_namespace" => $this->configManager->getNamespaceFor(
                        $boundedContextName,
                        $moduleName,
                        'value-object'
                    ),
                    "t_class_name" => $valueObjectName,
                ]
            )
        );
    }
    
}