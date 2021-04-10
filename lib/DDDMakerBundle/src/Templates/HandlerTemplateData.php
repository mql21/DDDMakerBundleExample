<?php

namespace Mql21\DDDMakerBundle\Templates;

class HandlerTemplateData
{
    private string $classNamespace;
    private string $className;
    private string $interfaceNamespace;
    private string $classToHandle;
    private string $useCaseName;
    private ?string $response;
    
    public function __construct(
        string $classNamespace,
        string $className,
        string $interfaceNamespace,
        string $classToHandle,
        string $useCaseName,
        string $response = null
    ) {
        $this->classNamespace = $classNamespace;
        $this->className = $className;
        $this->interfaceNamespace = $interfaceNamespace;
        $this->classToHandle = $classToHandle;
        $this->useCaseName = $useCaseName;
        $this->response = $response;
    }
    
    public function classNamespace(): string
    {
        return $this->classNamespace;
    }
    
    public function className(): string
    {
        return $this->className;
    }
    
    public function interfaceNamespace(): string
    {
        return $this->interfaceNamespace;
    }
    
    public function classToHandle(): string
    {
        return $this->classToHandle;
    }
    
    public function useCaseName(): string
    {
        return $this->useCaseName;
    }
    
    public function response(): ?string
    {
        return $this->response;
    }
}