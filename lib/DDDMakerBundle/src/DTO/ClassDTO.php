<?php

declare(strict_types=1);

namespace Mql21\DDDMakerBundle\DTO;

use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;

class ClassDTO
{
    private ?string $classSuffix;
    private string $elementClassName;
    private string $elementFileName;
    private string $elementPath;
    private string $elementFullPath;
    private string $namespace;
    private ?string $interfaceToImplementNamespace;
    private ?string $interfaceToImplementName;
    private ?string $classToExtendNamespace;
    private ?string $classToExtendName;
    private ConfigManager $configManager;
    
    public function __construct(
        string $boundedContextName,
        string $moduleName,
        string $className,
        string $dddElementType
    ) {
        $this->configManager = new ConfigManager();
        $this->classSuffix = $this->configManager->getClassSuffixFor($dddElementType);
        $this->elementClassName = "{$className}{$this->classSuffix}";
        $this->namespace = $this->configManager->getNamespaceFor($boundedContextName, $moduleName, $dddElementType);
        $classToImplementReflector = $this->classToImplementReflector($dddElementType);
        $this->interfaceToImplementNamespace = $classToImplementReflector
            ? $classToImplementReflector->getName()
            : null;
        $this->interfaceToImplementName = $classToImplementReflector
            ? $classToImplementReflector->getShortName()
            : null;
        $classToExtendReflector = $this->classToExtendReflector($dddElementType);
        $this->classToExtendNamespace = $classToExtendReflector ? $classToExtendReflector->getName() : null;
        $this->classToExtendName = $classToExtendReflector ? $classToExtendReflector->getShortName() : null;
        $this->elementFileName = "{$this->elementClassName}.php";
        $this->elementPath = PathFactory::for($boundedContextName, $moduleName, $dddElementType);
        $this->elementFullPath = "{$this->elementPath}{$this->elementFileName}";
    }
    
    public function elementClassName(): string
    {
        return $this->elementClassName;
    }
    
    public function namespace(): string
    {
        return $this->namespace;
    }
    
    public function interfaceToImplementNamespace(): ?string
    {
        return $this->interfaceToImplementNamespace;
    }
    
    public function interfaceToImplementName(): ?string
    {
        return $this->interfaceToImplementName;
    }
    
    public function classToExtendNamespace(): ?string
    {
        return $this->classToExtendNamespace;
    }
    
    public function classToExtendName(): ?string
    {
        return $this->classToExtendName;
    }
    
    public function elementFullPath(): string
    {
        return $this->elementFullPath;
    }
    
    private function classToImplementReflector(string $dddElementType): ?\ReflectionClass
    {
        $classToImplement = $this->configManager->getClassToImplementFor($dddElementType);
        if (empty($classToImplement)) {
            return null;
        }
        
        return new \ReflectionClass($classToImplement);
    }
    
    private function classToExtendReflector(string $dddElementType): ?\ReflectionClass
    {
        $classToExtend = $this->configManager->getClassToExtendFor($dddElementType);
        if (empty($classToExtend)) {
            return null;
        }
        
        return new \ReflectionClass($classToExtend);
    }
}