<?php

namespace Mql21\DDDMakerBundle\Templates;

class HandlerTemplateData
{
    private string $classNamespace;
    private string $className;
    private string $interfaceNamespace;
    private string $command;
    private string $useCaseName;
    private ?string $response;
    
    public function __construct(
        string $classNamespace,
        string $className,
        string $interfaceNamespace,
        string $command,
        string $useCaseName,
        string $response = null
    ) {
        $this->classNamespace = $classNamespace;
        $this->className = $className;
        $this->interfaceNamespace = $interfaceNamespace;
        $this->command = $command;
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
    
    public function command(): string
    {
        return $this->command;
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