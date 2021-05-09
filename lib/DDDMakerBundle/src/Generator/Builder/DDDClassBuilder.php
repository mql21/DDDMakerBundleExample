<?php

declare(strict_types=1);

namespace Mql21\DDDMakerBundle\Generator\Builder;

use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;

class DDDClassBuilder
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
    private string $boundedContextName;
    private string $moduleName;
    private string $className;
    private string $dddElementType;
    
    public static function create()
    {
        return new self();
    }
    
    public function forBoundedContext(string $boundedContextName)
    {
        $this->boundedContextName = $boundedContextName;
        
        return $this;
    }
    
    public function forModule(string $moduleName)
    {
        $this->moduleName = $moduleName;
        
        return $this;
    }
    
    public function withClassName(string $className)
    {
        $this->className = $className;
        
        return $this;
    }
    
    public function ofDDDElementType(string $dddElementType)
    {
        $this->dddElementType = $dddElementType;
        
        return $this;
    }
    
    public function build()
    {
        $this->checkIfClassCanBeBuilt();
    
        $this->configManager = new ConfigManager();
        $this->classSuffix = $this->configManager->classSuffixFor($this->dddElementType);
        $this->elementClassName = "{$this->className}{$this->classSuffix}";
        $this->namespace = $this->configManager->namespaceFor(
            $this->boundedContextName,
            $this->moduleName,
            $this->dddElementType
        );
        $classToImplementReflector = $this->classToImplementReflector($this->dddElementType);
        $this->interfaceToImplementNamespace = $classToImplementReflector
            ? $classToImplementReflector->getName()
            : null;
        $this->interfaceToImplementName = $classToImplementReflector
            ? $classToImplementReflector->getShortName()
            : null;
        $classToExtendReflector = $this->classToExtendReflector($this->dddElementType);
        $this->classToExtendNamespace = $classToExtendReflector ? $classToExtendReflector->getName() : null;
        $this->classToExtendName = $classToExtendReflector ? $classToExtendReflector->getShortName() : null;
        $this->elementFileName = "{$this->elementClassName}.php";
        $this->elementPath = PathFactory::for($this->boundedContextName, $this->moduleName, $this->dddElementType);
        $this->elementFullPath = "{$this->elementPath}{$this->elementFileName}";
        
        return $this;
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
        $classToImplement = $this->configManager->classToImplementFor($dddElementType);
        if (empty($classToImplement)) {
            return null;
        }
        
        return new \ReflectionClass($classToImplement);
    }
    
    private function classToExtendReflector(string $dddElementType): ?\ReflectionClass
    {
        $classToExtend = $this->configManager->classToExtendFor($dddElementType);
        if (empty($classToExtend)) {
            return null;
        }
        
        return new \ReflectionClass($classToExtend);
    }
    
    private function checkIfClassCanBeBuilt(): void
    {
        if (empty($this->boundedContextName)) {
            throw new \Exception(
                "Bounded context name is required. Please use method 'forBoundedContext()' before calling build method."
            );
        }
        
        if (empty($this->moduleName)) {
            throw new \Exception(
                "Module name is required. Please use method 'forModule()' before calling build method."
            );
        }
        
        if (empty($this->className)) {
            throw new \Exception(
                "Class name is required. Please use method 'withClassName()' before calling build method."
            );
        }
        
        if (empty($this->dddElementType)) {
            throw new \Exception(
                "DDD element type is required. Please use method 'ofDDDElementType()' before calling build method."
            );
        }
    }
}